<?php
set_time_limit(0);
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

// PROCESSING USING POP3
$deleteAutoresponders 	= $obj->getSetting("groupDeleteAutoResponders", $idGroup);
$pop3Server 		    = $obj->getSetting("groupPop3Server", $idGroup);
$pop3Username 		    = $obj->getSetting("groupPop3Username", $idGroup);
$pop3Password 		    = $obj->getSetting("groupPop3Password", $idGroup);
$groupPop3Port          = $obj->getSetting("groupPop3Port", $idGroup);
$groupPop3Batch         = ceil($obj->getSetting("groupPop3Batch", $idGroup));

$softs=0; $hards=0; $noWeightAssigned=0; $autoResponders=0; $totals=0;
$newLine = "\r\n";

function identifyBounce($strEmailBody) {
    $keywords = simplexml_load_file('_bmSigs.xml');
    foreach ($keywords->keyword as $kw) {
        if (stripos($strEmailBody, (string)$kw['signature'])!==FALSE) {return ceil($kw['weight']);break;}
    }
}
//OPEN CONNECTION
if (!$pop3Connect =fsockopen($pop3Server,$groupPop3Port,$errno,$errstr,60)) {
    echo ('Failed to connect to server');
    die;
}
else {
	$pop3Response = fgets($pop3Connect, 515);
	//$logArray['SERVER CONNECT: '] = "$pop3Response";

	//AUTHENTICATE
	fputs($pop3Connect, "USER $pop3Username".$newLine); 	//Send username
	$pop3Response = fgets($pop3Connect, 515);
	//$logArray['SEND USERNAME: '] = "$pop3Response";

	fputs($pop3Connect, "PASS $pop3Password".$newLine); 	//Send password
	$pop3Response = fgets($pop3Connect, 515);
	//$logArray['SEND PASSWORD: '] = "$pop3Response";

	// FIND TOTAL
	fputs($pop3Connect, "LIST".$newLine);
	$pop3Response = fgets($pop3Connect, 1024);
	//$logArray['LIST: '] = $pop3Response;

	while  (substr($pop3Response=fgets($pop3Connect, 1024),  0,  1)  <>  ".")  { //if the first character is not a dot.
	    $articles[$totals] = $pop3Response;
	    $totals++;
	}
	// LOOP THROUGH MESSAGES
	$groupPop3Batch = min($groupPop3Batch, $totals);
	if ($groupPop3Batch>0) {
		$pattern1 = '/X-SBD: (.*?)SBDEND/';
		$pattern2 = '/X-CMP: (.*?)CMPEND/';

		for ($z=1; $z<=$groupPop3Batch; $z++)  {
			$msgBody="";
			$line="";
			$mySQL=""; $strSQL1=" idEmail=0"; $strXSID=""; $strEmail=""; $iStartElse=""; $iEndElse="";
			$gotCmp=false;$gotSub=false;$gotWeight=false; $sqlPart="";

		    fputs($pop3Connect, "RETR $z".$newLine);
			$count = 0;
			//$MsgArray = array();
		    $line = fgets($pop3Connect);
		    while($line != ".\r\n") {
	    	    if ($line{0}=='.') {$line=substr($line,1);}
				//$MsgArray[$count] = $line;
				$msgBody .=$line;
	        	$count++;
	        	$line = fgets($pop3Connect);
	    	}
			//    print_r($MsgArray);

			$iStartElse   = stripos($msgBody, "Final-Recipient: rfc822;");
	        $iEndElse     = stripos($msgBody, "Action: failed");
	        if (preg_match ($pattern1, $msgBody, $match1)>0) {
	        	$strXSID = trim($match1[1]);
	            //echo '<br /><b>-'.$strXSID.'-</b><br />';
	            if (is_finite($strXSID)) {
	            	$strSQL1	= " idEmail=$strXSID";
					//echo '<br /><b>'.$strSQL1.'</b><br />';
	                $gotSub=true;
				}
	        }
	        else if ($iStartElse!==FALSE && $iEndElse!==FALSE) {
	        	$strEmail=substr($msgBody , ($iStartElse+24), $iEndElse-$iStartElse-24);
	            //echo '<br /><b>-'.$strEmail.'-</b><br />';
				$strSQL1	= " email='".trim(dbQuotes($strEmail))."'";
	            //echo '<br /><b>'.$strSQL1.'</b><br />';
	            $gotSub=true;
	        }
	        // EXTRACT CAMPAIGN ID
			if (preg_match ($pattern2, $msgBody, $match2)>0) {
				$cID = trim($match2[1]);
				//echo '<br /><b>C-'.$cID.'-</b><br />';
				if (is_finite($cID)) {
					$gotCmp=true;
				}
			}
			$bounceType = identifyBounce($msgBody);
			If ($bounceType==1) {
				//echo "<font color=#FF9933><b>SOFT</b></font><br>";
			    $softs=$softs+1;
			    $sqlPart=" soft_bounces=soft_bounces+1 ";
			    $gotWeight=true;
			 }
			 else if ($bounceType==2) {
			   //echo "<font color=red><b>HARD</b></font><br>";
			   $hards=$hards+1;
			   $sqlPart=" hard_bounces=hard_bounces+1 ";
			   $gotWeight=true;
			 }
			 else if ($bounceType==0) {
			 	//echo "<font color=blue><b>RESPONDER</b></font><br>";
			    $autoResponders=$autoResponders+1;
			    if ($deleteAutoresponders==-1) {
			    	fwrite($pop3Connect,"DELE $z".$newLine);
					//imap_delete($pop3Connect, $msgno);
					//	if (!fwrite(fgets($pop3Connect),"DELE $z".$newLine)) {die ('cannot delete it');};
					//if (!fwrite($pop3Connect,"DELE $z".$newLine)) {die ('cannot delete it');};
			     }
			 }
			 else {
	  			$bounceType==-1;
			    //echo "<font color=green><b>NO-WEIGHT</b></font><br>";
			    $noWeightAssigned=$noWeightAssigned+1;
			 }
			 IF ($gotWeight===true && $gotSub===true) {
				fwrite($pop3Connect,"DELE $z".$newLine);
	  			$mySQL="UPDATE ".$idGroup."_subscribers set $sqlPart WHERE $strSQL1";
	            $obj->query($mySQL);
	            //echo $mySQL.'<br />';
	            //echo 'we process it <br />';
			}
	        // UPDATE CAMPAIGN'S BOUNCED FIELD
	        if ($gotWeight===true && $gotSub===true && $gotCmp===true) {
	        	//$allBounced=$softs+$hards;  //+$noWeightAssigned;
	            $mySQLc="UPDATE ".$idGroup."_campaigns set bounced=bounced+1 WHERE idCampaign=$cID";
	            $obj->query($mySQLc);
	        }
		}	//for looping through messages

	}	//when totals>0

	// CLOSE CONNECTION
	$closing = fclose($pop3Connect);
	//$logArray['CLOSE'] = $closing;
}   //server connect OK

/*foreach ($logArray as $key => $value) {
   echo "$key $value<br />";
}*/

// COUNT TOTALS
//STAT: Sample response: +OK 3 345910
//The response to this is: +OK #msgs #bytes Where #msgs is the number of messages in the mail box and #bytes is the total bytes used by all messages.
//fputs($pop3Connect, "STAT".$newLine);
//$pop3Response = fgets($pop3Connect, 515);
//$logArray['STAT: '] = "$pop3Response";
//LIST: Sample response: +OK 3 messages
//The response to this lists a line for each message with its number and size in bytes, ending with a period on a line by itself.
//RETR
//Again the server responds with +OK to indicate success and shows how large the message is in bytes on the first line.
//The following lines contain the entire raw email message with full headers and message body.
//The last line of the message with be a full stop on a single line followed by a carriage return and line feed to indicate the end of the message.
?>