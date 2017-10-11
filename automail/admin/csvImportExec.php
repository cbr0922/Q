<?php
set_time_limit(0);
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj = new db_class();
$groupName 	=	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header('Content-type: text/html; charset='.$groupGlobalCharset.'');
// timer
/*      $mtime = microtime();
      $mtime = explode(' ', $mtime);
      $mtime = $mtime[1] + $mtime[0];
      $starttime = $mtime;
*/
//timer

if (@$pdemomode) {
	forDemo2(DEMOMODE_1);
}
$myDay = myDatenow();
@$pprefers 		= $_GET['prefers'];
if ($pprefers!=-1) {$pprefers=0;}
@$pconfirmed	= $_GET['confirmed'];
if ($pconfirmed!=-1) {$pconfirmed=0;}
@$pupdateduplicates	= $_GET['updateduplicates'];
if ($pupdateduplicates!=-1) {$pupdateduplicates=0;}

@$excludeGlobalOpts = $_GET['excludeGlobalOpts'];
if ($excludeGlobalOpts!=-1) {$excludeGlobalOpts=0;}

@$excludeListOpts = $_GET['excludeListOpts'];
if ($excludeListOpts!=-1) {$excludeListOpts=0;}

@$listsTicked		= count($_GET['idList']);
if ($pupdateduplicates==-1 && $listsTicked==0) {
	echo '<span class=errormessage><img src=./images/warning.png>  '.SUBSCRIBERSIMPORT_15.'</span>';
    return false;
}
$pfileName=$_GET['fileName'];
$inserted=0;
$existed=0;
$errors=0;
$foundInGlobaloptOut=0;
$f=0;
$cSQL="";
$uSQL="";
$sSQL="";
$numberOfLines=0;
$x=0;
$updateSQL="";
$qualifier="";
@$useQualifiers = $_GET['useQualifiers'];
if ($useQualifiers!=-1) {$useQualifiers=0;}
if ($useQualifiers==-1) {
	$qualifier="\"";
}
$delimiter=$_GET['delimiter'];
switch ($delimiter) {
  case "semicolon":
	$delimiter=$qualifier.";".$qualifier;
  break;
  case "comma":
	$delimiter=$qualifier.",".$qualifier;
  break;
  case "tab":
  	$delimiter=$qualifier."\t".$qualifier;
  break;
  default:
	$delimiter=$qualifier.";".$qualifier;
}
//construct the left part of the insert query and find where email is
foreach ($_GET as $key => $value) {
	if ($value!="" && $key!="delimiter" && $value!="ignore" && $value!=$pfileName && is_numeric($value)==false && is_array($value)==false) {
		$cSQL .=', '.$value;
		$i=Ceil(str_ireplace("col", "", $key));
		$columnsArray[$f]=$i;
		$f=$f+1;
	}
	if ($value=="email") {
		$emailIsAtCol=Ceil(str_ireplace("col", "", $key));
		$emailIsAtCol=$emailIsAtCol-1;
	}
}
$cSQL = ltrim($cSQL, ", ");
$updateArrayLeft = explode(", ", $cSQL);	//will use this later for the update query
$MyFile = fopen("../data_files/".$pfileName, "rb");
if (!$MyFile) {
	echo 'File not found';
	return false;
}
while (!feof($MyFile)) {
	$pLine 		= fgets($MyFile);
	$counter=0;
	$cols=0;
	if (strlen($pLine)>5 && strpos($pLine, "@")!==false && strpos($pLine, ".")!==false) { //&& strstr($pLine,",,")==0) {		//&& strstr($pLine,",,")==0 Outlook hack
		$myarray    = explode("$delimiter", $pLine);
		$cols		= sizeof($myarray);
		@$inemail 	= $myarray[$emailIsAtCol];
		foreach($columnsArray as $key => $value){
			If ($counter==0) {$divider="'";}
			else {$divider="', '";}
			$ender="";
			If ($counter==sizeof($columnsArray)-1) {$ender="'";}
			@$theValue = $myarray[$value-1];
	 		if ($useQualifiers==-1) {
				$theValue=str_ireplace("\"","",$theValue);
				$inemail=str_ireplace("\"","",$inemail);
			}
			$uSQL= dbQuotesArr(trim($theValue));
			$sSQL.=$divider.$uSQL.$ender;
			$counter=$counter+1;
		}
		$numberOfLines=$numberOfLines+1;
	}	
	//construct the update query
	if ($pupdateduplicates==-1) {
		$updateArrayRight = explode("', ", $sSQL); 
 		for ($x=0; $x<$counter; $x++)  {
 			$ender2='\',';
			If ($x==sizeof($updateArrayRight)-1) {$ender2="#";}
			@$updateSQL .= $updateArrayLeft[$x]."=".$updateArrayRight[$x].$ender2;	
		}
		@$updateSQL=rtrim($updateSQL, ",\'");
		@$updateSQL=rtrim($updateSQL, "#");
	}
   	
	if (((strstr($pLine,"$delimiter") && $cols>1) || ($cols==1  && strpos($pLine,"$delimiter")==false)) && strlen($pLine)>5 && strpos($pLine, "@")!==false && strpos($pLine, ".")!==false && $inemail!="") {		//there is delimiter in the line
			//exists check
			$mySQL1="SELECT idEmail, email FROM ".$idGroup."_subscribers where idGroup=$idGroup AND email='".dbQuotesArr(trim($inemail))."'";
			$result	= $obj->query($mySQL1);
			$row = $obj->fetch_array($result);
			if (!$row) {	//HE DOES NOT EXIST SO WE INSERT HIM
				$ppass=rand(1, 15000);
				if ($excludeGlobalOpts==-1) {	//this qery does not insert subs found in optOus with gloal opt-out. Returns 0 for $last insert_id
					$iSQL="INSERT INTO ".$idGroup."_subscribers ($cSQL, idGroup, dateSubscribed, confirmed, prefersHtml, subPassword) SELECT
					$sSQL, $idGroup, '$myDay', $pconfirmed, $pprefers, '$ppass' FROM dual
					WHERE NOT EXISTS (SELECT subscriberEmail FROM 1_optOutReasons WHERE optOutType='g' AND subscriberEmail='".dbQuotesArr(trim($inemail))."')";
				}
				else {	//check:
					$iSQL="INSERT INTO ".$idGroup."_subscribers ($cSQL, idGroup, dateSubscribed, confirmed, prefersHtml, subPassword) VALUES
					($sSQL, $idGroup, '$myDay', $pconfirmed, $pprefers, '$ppass')";
				}
				$obj->query($iSQL);
				$last =  $obj->insert_id();
				if ($last!=0) {$inserted=$inserted+1;} else {$foundInGlobaloptOut++;}
				if ($listsTicked!=0) {
					for ($z=0; $z<$listsTicked; $z++)  {
						if ($last!=0) {
							$mySQL3="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$last.", ".$_GET['idList'][$z].", $idGroup)";
							$obj->query($mySQL3);
						}
					} // for
				}	//listsTicked not 0
			}
			else {	//exists, get email id and add to lists
					$existed=$existed+1;
					$nidemail	= $row['0'];
					if ($pupdateduplicates==-1 && $listsTicked!=0) {
						$obj->query("UPDATE ".$idGroup."_subscribers set $updateSQL WHERE idEmail=$nidemail");
						//echo "UPDATE ".$idGroup."_subscribers set $updateSQL WHERE idEmail=$nidemail<br>";
						For ($z=0; $z<$listsTicked; $z++)  {
							$mySQL3="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$nidemail.", ".$_GET['idList'][$z].", $idGroup)";
							$obj->query($mySQL3);
						}
					}	//pupdateduplicates = "-1"
				} //he exists already


	} else {$errors=$errors+1;}
$sSQL="";
$updateSQL="";

}	//while

fclose($MyFile);

//Clear list opt-outs
if ($excludeListOpts==-1 && $listsTicked!=0) {
	for ($z=0; $z<$listsTicked; $z++)  {
 		$mySQLd="DELETE FROM ".$idGroup."_listRecipients WHERE idList=".$_GET['idList'][$z]." AND idEmail IN (SELECT idEmail FROM ".$idGroup."_subscribers WHERE email IN (SELECT subscriberEmail FROM ".$idGroup."_optOutReasons WHERE optOutType=".$_GET['idList'][$z]."))";
		$obj->query($mySQLd);
		//echo $mySQLd."<br>";
	}
}

//	==> Not needed since release 2.00 since we use REPLACE INTO and added a unique index.
//	'START LIST CLEAN UP USING LISTRECIPIENTSTEMP
/*	$mySQLx="SELECT ".$idGroup."_listRecipients.idEmail, ".$idGroup."_listRecipients.idList, Count(".$idGroup."_listRecipients.idEmail) AS NumOccurrences FROM ".$idGroup."_listRecipients WHERE idGroup=$idGroup GROUP BY ".$idGroup."_listRecipients.idEmail, ".$idGroup."_listRecipients.idList HAVING (((Count(*))>1))";
	$result	= $obj->query($mySQLx);
	$row = $obj->fetch_array($result);
	if ($row){ //we have dupl pairs so start cleaning
		//Fill listRecipientsTemp with unique pairs
		$mySQLx0="insert into ".$idGroup."_listRecipientsTemp (idlist, idEmail, idGroup) select distinct idList, idEmail, idGroup from ".$idGroup."_listRecipients WHERE idGroup=$idGroup";
		$obj->query($mySQLx0);
		//Empty main table (listRecipients)
		$mySQLx1="delete from ".$idGroup."_listRecipients where idGroup=$idGroup";
		$obj->query($mySQLx1);
		//Copy back to main table
		$mySQLx2="insert into ".$idGroup."_listRecipients (idList, idEmail, idGroup) select idList, idEmail, idGroup from ".$idGroup."_listRecipientsTemp WHERE idGroup=$idGroup";
		$obj->query($mySQLx2);
		//Empty temp table (listRecipientsTemp)
		$mySQLx3="delete from ".$idGroup."_listRecipientsTemp where idGroup=$idGroup";
		$obj->query($mySQLx3);
	}	//END OF CLEANING DUPLICATES
*/
echo '<p><span class=menu>'.SUBSCRIBERSIMPORT_13.'</span></p>';
echo $inserted .' '.SUBSCRIBERSIMPORT_27.'<br>';
echo $existed  .' '.SUBSCRIBERSIMPORT_28.'<br>';
echo $foundInGlobaloptOut  .' '.SUBSCRIBERSIMPORT_36.'<br>';
if ($pupdateduplicates==-1) {echo ' '.SUBSCRIBERSIMPORT_14.'<br>';}
echo $errors .' '.SUBSCRIBERSIMPORT_26.'<br>';

//timer
/*      $mtime = microtime();
      $mtime = explode(" ", $mtime);
      $mtime = $mtime[1] + $mtime[0];
      $endtime = $mtime;
      $totaltime = ($endtime - $starttime);
      echo '<br>Operation took ' .$totaltime. ' seconds.';
*/
//timer
//$obj->free_result($result);
$obj->closeDb();


?>