<?php
/**
/** Version information */
class version {
	/** @var string Product */
	var $PRODUCT = 'SmartShop';
	/** @var int Main Release Level */
	var $RELEASE = '5.0.2';
	/** @var string Development Status */
	var $DEV_STATUS = 'Stable';
	/** @var int Sub Release Level */
	var $DEV_LEVEL = '0.1';
	/** @var string Codename */
	var $CODENAME = 'Reloaded';
	/** @var string Date */
	var $RELDATE = '25-02-2014';
	/** @var string Time */
	var $RELTIME = '07:30';
	/** @var string Timezone */
	var $RELTZ = 'GMT';
	/** @var string VersionType */
	var $VersionType = 'Business'; //Business
	var $VersionClass = 'Business'; //Business //Demo
	/** @var string Copyright Text */
	var $COPYRIGHT = "";
	var $URL = "";
	var $COPYRIGHT_Tw = "";
	var $URL_Tw = "";
}
if ( version_compare( phpversion(), '5', '<' ) ){
	// for php 4.x
	$_VERSION =& new version();
}
else{
	// for php >= 5.x
	$_VERSION = new version();
}

$_VERSION->COPYRIGHT = "Copyright 2005 - 2007 © <a href=\"http://www.SmartShop.com.tw\" target=\"_blank\">SmartShop Software Inc.</a> <font color=\"red\">SmartShop®  Version {$_VERSION->RELEASE} </font> team. All rights reserved.";
$_VERSION->URL = "<a href=\"http://www.SmartShop.com.tw\" target=\"_blank\">Copyright 2005 - 2006 © SmartShop Software Inc. <font color=\"red\">SmartShop® Version {$_VERSION->RELEASE}</font> team. All rights reserved.</a>";
$_VERSION->COPYRIGHT_Tw = "Copyright 2005 - 2007 © <a href=\"http://www.SmartShop.com.tw\" target=\"_blank\">SmartShop Software Inc.</a> <font color=\"red\">SmartShop® Version {$_VERSION->RELEASE} </font> team. All rights reserved.";
$_VERSION->URL_Tw = "<a href=\"http://www.SmartShop.com.tw\" target=\"_blank\">Copyright 2005 - 2006 © SmartShop Software Inc. <font color=\"red\">SmartShop® Version {$_VERSION->RELEASE}</font> team. All rights reserved.</a>";


$version = $_VERSION->PRODUCT .' '. $_VERSION->RELEASE .'.'. $_VERSION->DEV_LEVEL .' '
. $_VERSION->DEV_STATUS
.' [ '.$_VERSION->CODENAME .' ] '. $_VERSION->RELDATE .' '
. $_VERSION->RELTIME .' '. $_VERSION->RELTZ;
?>
