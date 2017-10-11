<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

include('adminVerify.php');
include('./includes/languages.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
$obj   				= new db_class();
$groupName 	   		= $obj->getSetting("groupName", $idGroup);
$groupScriptUrl		= $obj->getSetting("groupScriptUrl", $idGroup);
$groupSite 			= $obj->getSetting("groupSite", $idGroup);
$groupContactEmail	= $obj->getSetting("groupContactEmail", $idGroup);
$groupLogo			= $obj->getSetting("groupLogo", $idGroup);
?>

<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $obj->getSetting("groupGlobalCharset", $idGroup); ?>">
<title><?php echo SMARTLINKS_1; ?></title>
<script>

function insertLink(url,title,target) {
    var obj=parent.oUtil.obj;
    obj.insertLink(url,title,target);
}
</script>

<style>
html, BODY {background: #fff;font-family:Verdana, Arial, Helvetica;font-size: 12px;margin:3 3 3 3;}
</style>
</head>
<body>
<br>
<b><?php echo SMARTLINKS_1; ?></b>
<br>
<?php echo SMARTLINKS_2; ?>
<ul>
	<li onclick="insertLink('subconfirmationlink','<?php echo fixJSstring(SMARTLINKS_20); ?>')" style="cursor:pointer"><u><?php echo SMARTLINKS_20; ?></u></li>
	<li onclick="insertLink('unsubscribelink','<?php echo fixJSstring(SMARTLINKS_21); ?>')" style="cursor:pointer"><u><?php echo SMARTLINKS_21; ?></u></li>
</ul>
	<p align=right>
		<input type = 'button' name = 'button' value = '<?php echo fixJSstring(SMARTLINKS_16); ?>' onclick = "parent.box.close();">
	</p>

</body>
</html>
