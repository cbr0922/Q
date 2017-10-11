<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
$obj = new db_class();
@$idNewsletter	= $_REQUEST['idNewsletter'];
$mySQL="Select name, body, charset from ".$idGroup."_newsletters where idGroup=$idGroup AND idNewsletter=".$idNewsletter;
$result	= $obj->query($mySQL);
$row = $obj->fetch_array($result);
$psubject=$row['name'];
$pbody=$row['body'];
$pcharset=$row['charset'];
header('Content-Type: text/html; charset="'.$pcharset.'"'); ?>
<textarea rows="30" cols="80" class="textarea" style="word-wrap:break-word;" wrap=hard><?php echo $pbody;?></textarea>
