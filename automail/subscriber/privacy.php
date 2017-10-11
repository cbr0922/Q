<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('../inc/dbFunctions.php');
$obj 		= new db_class();
$SQL="UPDATE ".$idGroup."_privacyPage SET timesVisited=timesVisited+1";
$obj->query($SQL);
$mySQL="SELECT details FROM ".$idGroup."_privacyPage";
$result = $obj->query($mySQL);
$row = $obj->fetch_row($result);
$pdetails = $row[0];
echo $pdetails;

if (@$pdemomode) { ?>
	<div align="center" style="margin-top:50px; border-top:#888 1px solid">
		<span style="color:#000;font-size:12px;font-family:Verdana, Arial">This is a demonstration of nuevoMailer.</span>
		<br><a target="blank" href="http://www.nuevomailer.com?demo"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">Click here to learn more.</span></a>
		<div style="margin-top:12px"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">&copy; <?php echo date('Y')?> - DesignerFreeSolutions.com</span></div>
	</div>
<?php   } ?>
