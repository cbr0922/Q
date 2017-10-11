<?php
@header("Content-type: text/html; charset=utf-8");
@session_start();
switch (trim($_GET[language])){
	case "gb";
	$LanguageIndexIs = "gb";
	break;
	case "big5";
	$LanguageIndexIs = "big5";
	break;
	case "en";
	$LanguageIndexIs = "en";
	break;
	default:
	$LanguageIndexIs = "big5";
	break;
}
$Url = trim($_GET[url])!="" ? base64_decode(trim($_GET[url])) : $INFO[site_url] ;
$_SESSION[CurrentUserLanguage] = $LanguageIndexIs;
//setcookie("CurrentUser",$LanguageIndexIs,time()+3600);
@header("location:$Url");
//echo "<script language=javascript>alert(\"已经改变语言类型$Url\");location.href=\"$Url\";</script>";

/*
		  Language:
		   <a href="<{ $site_url }>/language/change_language.php?language=gb&url=<{ $url_From }>">[gb]</a> &nbsp;&nbsp;<a href="<{ $site_url }>/language/change_language.php?language=big5&url=<{ $url_From }>">[big5]</a>
*/
?>

