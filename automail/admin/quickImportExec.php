<?php
set_time_limit(0);
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj 		= new db_class();
$groupName	= $obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
header('Content-type: text/html; charset='.$groupGlobalCharset.'');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
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
//$start = (float) array_sum(explode(' ',microtime()));
$myDay = myDatenow();

@$pprefers 		= $_POST['prefers'];
if ($pprefers!=-1) {$pprefers=0;}
@$pconfirmed	= $_POST['confirmed'];
if ($pconfirmed!=-1) {$pconfirmed=0;}
@$pupdateduplicates	= $_POST['updateduplicates'];
if ($pupdateduplicates!=-1) {$pupdateduplicates=0;}

@$excludeGlobalOpts = $_POST['excludeGlobalOpts'];
if ($excludeGlobalOpts!=-1) {$excludeGlobalOpts=0;}

@$excludeListOpts = $_POST['excludeListOpts'];
if ($excludeListOpts!=-1) {$excludeListOpts=0;}

$psubscribers = $_POST['subform'];
//$psubscribers = str_ireplace("\n", ";", $psubscribers);
$psubscribers = explode("\n", $psubscribers);

$subs = sizeof($psubscribers);
@$listsTicked	= count($_POST['idList']);

if ($pupdateduplicates==-1 && $listsTicked==0) {
	echo '<span class=errormessage><img src=./images/warning.png>  '.SUBSCRIBERSIMPORT_15.'</span>';
	die;
}
else
{
	$inserted=0;
	$existed=0;
	$errors =0;
	$foundInGlobaloptOut=0;
   for ($i=0; $i<$subs; $i++)  {
			if (!empty($psubscribers[$i]) && strstr($psubscribers[$i],"@") && strstr($psubscribers[$i],".")) {
				//exists check
				$mySQL1="SELECT idEmail, email FROM ".$idGroup."_subscribers where idGroup=$idGroup AND email='".trim(dbQuotes($psubscribers[$i]))."'";
				$result	= $obj->query($mySQL1);
				$row = $obj->fetch_array($result);
				if (!$row) {	//HE DOES NOT EXIST SO WE INSERT HIM
					$ppass=rand(1, 15000);
					if ($excludeGlobalOpts==-1) {	//this qery does not insert subs found in optOus with gloal opt-out. Returns 0 for $last insert_id
						$iSQL="INSERT INTO ".$idGroup."_subscribers (idGroup, email, dateSubscribed, confirmed, prefersHtml, subPassword) SELECT
						$idGroup, '".trim(dbQuotes($psubscribers[$i]))."', '$myDay', $pconfirmed, $pprefers, '$ppass' FROM dual
						WHERE NOT EXISTS (SELECT subscriberEmail FROM 1_optOutReasons WHERE optOutType='g' AND subscriberEmail='".trim(dbQuotes($psubscribers[$i]))."')";

					}
					else {	//check:
						$iSQL="INSERT INTO ".$idGroup."_subscribers (idGroup, email, dateSubscribed, confirmed, prefersHtml, subPassword) VALUES ($idGroup, '".trim(dbQuotes($psubscribers[$i]))."', '$myDay', $pconfirmed, $pprefers, '$ppass')";
					}
					$obj->query($iSQL);
					//echo ($iSQL."<br>");
					$last =  $obj->insert_id();
					//echo $last."<br>";
					if ($last!=0) {$inserted=$inserted+1;} else {$foundInGlobaloptOut++;}
					if ($listsTicked!=0) {
						for ($z=0; $z<$listsTicked; $z++)  {
							if ($last!=0) {
								$mySQL3="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$last.", ".$_POST['idList'][$z].", $idGroup)";
								$obj->query($mySQL3);
							}
					  	} // for lists
					}	//listsTicked not 0
				} //if (!$row), not existed
				else {	//he exists already, we get his email id
					$existed=$existed+1;
				 	$nidemail	= $row['0'];
					if ($pupdateduplicates==-1 && $listsTicked!=0) {
							For ($z=0; $z<$listsTicked; $z++)  {
								$mySQL3="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$nidemail.", ".$_POST['idList'][$z].", $idGroup)";
								$obj->query($mySQL3);
							}
					}	//pupdateduplicates = "-1"
 				}	//when exists

			}
			else {	//array validity check
			 $errors=$errors+1;
 			}	//array lines validity check
} //for.. going through subs array

//Clear list opt-outs
if ($excludeListOpts==-1 && $listsTicked!=0) {
	for ($z=0; $z<$listsTicked; $z++)  {
 		$mySQLd="DELETE FROM ".$idGroup."_listRecipients WHERE idList=".$_POST['idList'][$z]." AND idEmail IN (SELECT idEmail FROM ".$idGroup."_subscribers WHERE email IN (SELECT subscriberEmail FROM ".$idGroup."_optOutReasons WHERE optOutType=".$_POST['idList'][$z]."))";
		$obj->query($mySQLd);
		//echo $mySQLd."<br>";
	}
}
echo '<p><span class=menu>'.SUBSCRIBERSIMPORT_13.'</span></p>';
echo $inserted .' '.SUBSCRIBERSIMPORT_27.'<br>';
echo $existed  .' '.SUBSCRIBERSIMPORT_28.'<br>';
echo $foundInGlobaloptOut  .' '.SUBSCRIBERSIMPORT_36.'<br>';
if ($pupdateduplicates==-1) {echo ' '.SUBSCRIBERSIMPORT_14.'<br>';}
echo $errors .' '.SUBSCRIBERSIMPORT_26.'<br>';
}

//timer
/*      $mtime = microtime();
      $mtime = explode(" ", $mtime);
      $mtime = $mtime[1] + $mtime[0];
      $endtime = $mtime;
      $totaltime = ($endtime - $starttime);
      echo '<br>Operation took ' .$totaltime. ' seconds.';
*/
//timer

$obj->free_result($result);
$obj->closeDb();
?>