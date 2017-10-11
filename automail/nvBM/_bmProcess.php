<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

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
//$imapServer="{".$pop3Server.":".$groupPop3Port."/imap/ssl/novalidate-cert}";  // GMAIL OK
//$imapServer="{".$pop3Server.":".$groupPop3Port."/novalidate-cert}";           //WINDOWS SERVERS WITH PORT 143
$imapServer="{".$pop3Server.":".$groupPop3Port."/pop3/notls}";                  //GOOD: RedHat & WINDOWS servers WITH PORT 110

if (!$pop3Connect = imap_open($imapServer, $pop3Username, $pop3Password, OP_SILENT)) {
    echo ('Failed to connect to server');
    die;
}
else {
    $totals=imap_num_msg($pop3Connect);
    $groupPop3Batch = min($groupPop3Batch, $totals);
    if ($groupPop3Batch>0) {
		$pattern1 = '/X-SID: (.*?)SIDEND/';
        $pattern2 = '/X-CMP: (.*?)CMPEND/';
        for($msgno = 1; $msgno <=$groupPop3Batch; $msgno++) {  // LOOP THROUGH MESSAGES
            $mySQL=""; $strSQL1=" idEmail=0"; $msgBody=""; $strXSID=""; $strEmail=""; $iStartElse=""; $iEndElse="";
            $gotCmp=false;$gotSub=false;$gotWeight=false; $sqlPart="";
            $msgBody=imap_body ($pop3Connect, $msgno);
            $iStartElse   = stripos($msgBody, "Final-Recipient: rfc822;");
            $iEndElse     = stripos($msgBody, "Action: failed");
            if (preg_match ($pattern1, $msgBody, $match1)>0) {
                $strXSID = trim($match1[1]);
                if (is_finite($strXSID)) {
                    $strSQL1	= " idEmail=$strXSID";
                    $gotSub=true;
                }
            }
            else if ($iStartElse!==FALSE && $iEndElse!==FALSE) {
                $strEmail=substr($msgBody , ($iStartElse+24), $iEndElse-$iStartElse-24);
                $strSQL1	= " email='".trim(dbQuotes($strEmail))."'";
                $gotSub=true;
            }
            // EXTRACT CAMPAIGN ID
            if (preg_match ($pattern2, $msgBody, $match2)>0) {
                $cID = trim($match2[1]);
                if (is_finite($cID)) {
                    $gotCmp=true;
                }
            }
            $bounceType = identifyBounce($msgBody);
            If ($bounceType==1) {
                $softs=$softs+1;
                $sqlPart=" soft_bounces=soft_bounces+1 ";
                $gotWeight=true;
            }
            else if ($bounceType==2) {
                $hards=$hards+1;
                $sqlPart=" hard_bounces=hard_bounces+1 ";
                $gotWeight=true;
            }
            else if ($bounceType==0) {
                $autoResponders=$autoResponders+1;
                if ($deleteAutoresponders==-1) {
                    imap_delete($pop3Connect, $msgno);
                }
            }
            else {
                $bounceType==-1;
                //echo "<font color=green><b>NO-WEIGHT</b></font><br>";
                $noWeightAssigned=$noWeightAssigned+1;
				// activate next line if you want to delete messages that were not identified as hard/soft/autoresponder
				//imap_delete($pop3Connect, $msgno);
            }
            IF ($gotWeight===true && $gotSub===true) {
                imap_delete($pop3Connect, $msgno);
                $mySQL="UPDATE ".$idGroup."_subscribers set $sqlPart WHERE $strSQL1";
                $obj->query($mySQL);
            }
            // UPDATE CAMPAIGN'S BOUNCED FIELD
            if ($gotWeight===true && $gotSub===true && $gotCmp===true) {
                //$allBounced=$softs+$hards;  //+$noWeightAssigned;
                $mySQLc="UPDATE ".$idGroup."_campaigns set bounced=bounced+1 WHERE idCampaign=$cID";
                $obj->query($mySQLc);
            }

            //echo '<hr>';
        }   // LOOP THROUGH MESSAGES
    }	//when totals>0
}   //server connect OK


//DELETE MESSAGES THAT PROCESSED FULLY.
//imap_delete($pop3Connect, $msgno);
//Marks messages listed in msg_number for deletion. Messages marked for deletion will stay in the mailbox until either imap_expunge() is called
//or imap_close() is called with the optional parameter CL_EXPUNGE.

// CLOSE CONNECTION
imap_expunge($pop3Connect);
imap_close ($pop3Connect);
?>