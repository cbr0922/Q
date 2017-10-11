<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
if (isset( $_SERVER["HTTP_ACCEPT_LANGUAGE"]) )	{$localLang=strtolower(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2));} 
//echo($localLang);
if (!isset($_SESSION['adminLang'])) {
	session_start();
}

if (!isset($_SESSION['adminLang'])) {	//find it from locale settings
	if (isset($localLang)) {
		switch ($localLang) {
		  case  "en":
		  	$_SESSION['adminLang']="english";
		  	break;
		  case  "el":
		  	$_SESSION['adminLang']="greek";  
		  	break;
		  case  "de":
		  	$_SESSION['adminLang']="german";  
		  	break;
		  case  "pt":
		  	$_SESSION['adminLang']="portuguese";  
		  	break;
		  case  "fr":
		  	$_SESSION['adminLang']="french";  
		  	break;
		  case  "it":
		  	$_SESSION['adminLang']="italiano";  
		  	break;
		  case  "es":
		  	$_SESSION['adminLang']="spanish";  
		  	break;
		  case  "zh":
		  	$_SESSION['adminLang']="chinese_translation.php";  
		  	break;
		  default:
		 	$_SESSION['adminLang']="english";	//zn
		}
	}
	else {
		$_SESSION['adminLang']="english";
	}
}
$_SESSION['adminLang']="chinese";  
//$_SESSION['adminLang']="chinese";  //in case the user wants to ovveride detection.

if ($_SESSION['adminLang']=="english") {include('lang/english.php');}

else if ($_SESSION['adminLang']=="french") {include('lang/french.php');}

else if ($_SESSION['adminLang']=="italiano") {include('lang/italiano.php');}

else if ($_SESSION['adminLang']=="spanish") {include('lang/spanish.php');}

else if ($_SESSION['adminLang']=="portuguese") {include('lang/portuguese.php');}

else if ($_SESSION['adminLang']=="dutch") {include('lang/dutch.php');}

else if ($_SESSION['adminLang']=="german") {include('lang/german.php');}

else if ($_SESSION['adminLang']=="greek") {include('lang/greek_utf8.php');}

//else if ($_SESSION['adminLang']=="chinese") {include('lang/chinese_utf8.php');}

else if ($_SESSION['adminLang']=="chinese") {include('lang/chinese_translation.php');}

else if ($_SESSION['adminLang']=="other") {include('lang/other.php');}
?>