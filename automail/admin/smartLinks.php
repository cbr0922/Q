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
$groupGlobalCharset =$obj->getSetting("groupGlobalCharset", $idGroup);
?>
<script type="text/javascript" language="javascript">var myCustomEncoding="<?php echo $groupGlobalCharset;?>"</script>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<link href="./includes/site.css" rel=stylesheet type=text/css>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset; ?>">
<title><?php echo SMARTLINKS_1; ?></title>
<script src="./scripts/jQuery_2.1.0.js" type="text/javascript"></script>
<script src="./scripts/scripts.js" type="text/javascript"></script>
<script>
function insertObject(sHTML)  {
	var obj=parent.oUtil.obj;
	//Use insertHTML() function to insert your custom html
  	obj.insertHTML(sHTML);
}
function insertLink(url,title,target) {
    var obj=parent.oUtil.obj;
    obj.insertLink(url,title,target);
}
</script>
<style>
html, BODY {background: #ffffe0;font-family:Verdana, Arial, Helvetica;font-size: 12px; margin:3 3 3 3;}
li {		FONT-FAMILY: Verdana, Arial, Tahoma, helvetica, sans-serif; FONT-SIZE: 12px; COLOR:#32393d; margin-top:5px;}
</style>
</head>
<body>
<br>
<b><?php echo SMARTLINKS_1; ?></b>
<br>
<?php echo SMARTLINKS_2; ?>
<ul>
	<li onclick="insertObject(document.getElementById('webpagelink').innerHTML)" style="cursor:pointer"><u><?php echo SMARTLINKS_4; ?></u></li>
	<!--li onclick="insertLink('webpagelink','<?php //echo fixJSstring(SMARTLINKS_3); ?>')" style="cursor:pointer"><u><?php //echo SMARTLINKS_4; ?></u></li-->
	<li onclick="insertLink('friendforwardlink','<?php echo fixJSstring(SMARTLINKS_5); ?>')" style="cursor:pointer"><u><?php echo SMARTLINKS_6; ?></u></li>
	<li onclick="insertLink('<?php echo $groupScriptUrl?>/subscriber/privacy.php','<?php echo fixJSstring(SMARTLINKS_7); ?>')" style="cursor:pointer"><u><?php echo SMARTLINKS_8; ?></u></li>
    <li onclick="insertLink('<?php echo $groupScriptUrl?>/subscriber/newsletterArchive.php','<?php echo fixJSstring(SMARTLINKS_24); ?>')" style="cursor:pointer"><u><?php echo SMARTLINKS_23; ?></u></li>
    <li onclick="insertLink('subconfirmationlink','<?php echo fixJSstring(SMARTLINKS_19); ?>')" style="cursor:pointer"><u><?php echo SMARTLINKS_18; ?></u></li>
	<li onclick="insertObject(document.getElementById('linkToYourSite').innerHTML)" style="cursor:pointer"><u><?php echo SMARTLINKS_9; ?></u></li>
	<li onclick="insertObject(document.getElementById('contactEmail').innerHTML)" style="cursor:pointer"><u><?php echo SMARTLINKS_10; ?></u></li>
	<li onclick="insertObject(document.getElementById('companyName').innerHTML)" style="cursor:pointer"><u><?php echo SMARTLINKS_11; ?></u></li>
	<li onclick="insertObject(document.getElementById('companyNameLink').innerHTML)" style="cursor:pointer"><u><?php echo SMARTLINKS_12; ?></u></li>
	<li onclick="insertObject(document.getElementById('divSignature').innerHTML)" style="cursor:pointer"><u><?php echo SMARTLINKS_13; ?></u></li>
	<li onclick="insertObject(document.getElementById('logo').innerHTML)" style="cursor:pointer"><u><?php echo SMARTLINKS_14; ?></u>&nbsp;<img onmouseover="infoBox('smartLinks_1', '<?php echo fixJSstring(GENERIC_17);?>', '<?php echo fixJSstring(SMARTLINKS_17);?>', '10em','0')" onmouseout="hide_info_bubble('smartLinks_1','0')" src="./images/i1.gif"><span style="display: none;" id="smartLinks_1"></span></li>
</ul>

<div><strong><?php echo SMARTLINKS_22;?></strong></div>
<ul>
	<li onclick="insertLink('ratelink1','<?php echo fixJSstring(ALLNEWSLETTERS_32); ?>')" style="cursor:pointer"><u><?php echo ALLNEWSLETTERS_32; ?></u></li>
	<li onclick="insertLink('ratelink2','<?php echo fixJSstring(ALLNEWSLETTERS_33); ?>')" style="cursor:pointer"><u><?php echo ALLNEWSLETTERS_33; ?></u></li>
	<li onclick="insertLink('ratelink3','<?php echo fixJSstring(ALLNEWSLETTERS_34); ?>')" style="cursor:pointer"><u><?php echo ALLNEWSLETTERS_34; ?></u></li>
	<li onclick="insertLink('ratelink4','<?php echo fixJSstring(ALLNEWSLETTERS_35); ?>')" style="cursor:pointer"><u><?php echo ALLNEWSLETTERS_35; ?></u></li>
	<li onclick="insertLink('ratelink5','<?php echo fixJSstring(ALLNEWSLETTERS_36); ?>')" style="cursor:pointer"><u><?php echo ALLNEWSLETTERS_36; ?></u></li>
</ul>

<div id="webpagelink" style="display:none">
	<a href="webpagelink" nvhide=""><?php echo SMARTLINKS_3; ?></a>
</div>

<div id="linkToYourSite" style="display:none">
	<a href="<?php echo $groupSite;?>"><?php echo $groupSite;?></a>
</div>

<div id="contactEmail" style="display:none">
	<a href="mailto:<?php echo $groupContactEmail;?>"><?php echo $groupContactEmail;?></a>
</div>

<div id="companyName" style="display:none">
	<?php echo $groupName;?>
</div>

<div id="companyNameLink" style="display:none">
	<a href="<?php echo $groupSite;?>"><?php  echo $groupName;?></a>
</div>

<div id="divSignature" style="display:none">
	Best Regards<br>
	Sales Team<br>
	<a href="<?php echo $groupSite;?>"><?php echo  $groupName?></a><br>
	<a href="mailto:<?php echo $groupContactEmail;?>"><?php echo $groupContactEmail;?></a>
</div>

<div id="logo" style="display:none">
	<a href="<?php echo $groupSite;?>"><img src="<?php echo $groupScriptUrl;?>/assets/<?php echo $groupLogo?>" margin="0" border="0"></a>
 </div>
<span style="font-size:10px;"><?php echo SMARTLINKS_15; ?></span>
<div align=right>
		<input class="submit" type='button' name='button' value='<?php echo fixJSstring(SMARTLINKS_16); ?>' onclick ="parent.box.close();">
</div>

</body>
</html>
