<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
if (@$pdemomode) {
	forDemo("lists.php", DEMOMODE_1);
}
include('header.php');
showMessageBox();

$myDay = myDatenow();


//insert List
$listName = dbQuotes(CREATEFILTEREDLIST_5);
$mySQL="INSERT INTO ".$idGroup."_lists (listName, isPublic, dateCreated, createdBy, idGroup) VALUES ('$listName', 0, '$myDay',$sesIDAdmin, $idGroup)";
$result	= $obj->query($mySQL);
$lastList =  $obj->insert_id();

//get subscribers and add them to this list
$mySQL3		= $_POST['mySQL'];
$mySQL3		= my_stripslashes($mySQL3);

$mySQLi = str_ireplace("distinct ".$idGroup."_subscribers.idEmail","distinct ".$idGroup."_subscribers.idEmail, ".$idGroup.", ".$lastList,$mySQL3);
$mySQL4="REPLACE INTO ".$idGroup."_listRecipients (idEmail, idGroup, idList) ".$mySQLi;
$result4	= $obj->query($mySQL4);
$count=$obj->affected_rows($result4);

?>

<br><span class="title"><?php echo CREATEFILTEREDLIST_1; ?></span>
<br><br><?php echo $count.' '.CREATEFILTEREDLIST_2; ?>
<br><br><?php echo CREATEFILTEREDLIST_3; ?><a href="lists.php"><?php echo CREATEFILTEREDLIST_4; ?></a>.


<?php
$obj->closeDb();
include('footer.php');
?>