<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

//Connection to mySQL server
include("../../Config/conf.global.php");
include("../../Classes/function.php");

$FUNCTIONS = new FUNCTIONS;
define('DB_HOST',$INFO['DBhostname']);             //CHANGE REQUIRED *** Enter server name/address/IP. *Windows-servers-PHP.5.3 may use: 127.0.0.1 instead of localhost
define('DB_NAME', $FUNCTIONS->authcode($INFO['DBname'],"DECODE",$INFO['site_userc']));              //CHANGE REQUIRED *** Enter database name
define('DB_USER', $FUNCTIONS->authcode($INFO['DBuserName'],"DECODE",$INFO['site_userc']));                  //CHANGE REQUIRED *** Enter database username
define('DB_PASS', $FUNCTIONS->authcode($INFO['DBpassword'],"DECODE",$INFO['site_userc']));                  //CHANGE REQUIRED *** Enter database user password

/*******************************************
 CHANGE with your own timezone here
 List of Supported Timezones: http://php.net/manual/en/timezones.php
 *******************************************/
date_default_timezone_set('Europe/Athens');      //CHANGE REQUIRED ***
//ini_set('date.timezone', 'Europe/Athens');

/*******************************************
 GOOGLE API REPORTING
 Change to -1 to see two additional reports based on Google visualization api.
 1. Email clients used by subscribers: pie chart
 2. Geo-map report: subscribers by country and/or state. A new menu item will appear under Menu>Reports.
 *******************************************/
$showGoogleApiReports = "-1";				//CHANGE REQUIRED *** Can take the values of 0 or -1 (0:hides, -1:visible)


/* NO FURTHER CHANGES */
$nuevoRelease	=	'3.70';      //DO NOT CHANGE THIS
$pdemomode		=	false;       //DO NOT CHANGE THIS
$nuevoLanguage	=	'english';   //DO NOT CHANGE THIS
$databaseType	=   "mySQL";     //DO NOT CHANGE THIS
$idGroup        =   1;           //DO NOT CHANGE THIS
?>