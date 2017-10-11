<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
set_time_limit(0);
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
include('header.php');

include('_bmProcess.php');

?>
<br>
<span class="title"><?php echo BOUNCEMANAGER_26;?></span>
<br><br>
<?php echo BOUNCEMANAGER_27.' '.$totals.' '.BOUNCEMANAGER_28.' '.$groupPop3Batch.'.';?>
<br><br>
<?php echo BOUNCEMANAGER_29.' '.$softs;?>
<br><br>
<?php echo BOUNCEMANAGER_30.' '.$hards;?>
<br><br>
<?php echo BOUNCEMANAGER_33.' '.$autoResponders;?>
<br><br>
<?php echo BOUNCEMANAGER_31.' '.$noWeightAssigned.' '.BOUNCEMANAGER_32;?>
<br><br>
<?php echo BOUNCEMANAGER_25?><a href="_bm.php"><?php echo BOUNCEMANAGER_11;?></a>
<?php include('footer.php'); ?>