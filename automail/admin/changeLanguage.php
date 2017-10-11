<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

$newLang=trim($_GET['language']);
session_start();
$_SESSION['adminLang']=$newLang;
include('./includes/languages.php');
header('Location: index.php?message='.urlencode(ADMIN_CHANGELANGUAGE_1).'');
?>