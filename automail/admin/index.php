<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<?php 
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj = new db_class();
$groupName 	=	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset =	$obj->getSetting("groupGlobalCharset", $idGroup);?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<title>nuevoMailer Newsletter and mailing list software by DesignerFreeSolutions.com</title>
<meta name="keywords" content="newsletter, newsletter software, mailing list, mailing list manager, mailing lists, newsletter manager, bulk mailing, bulk mail, PHP newsletter, email software, PHP, mailing list, html emails, text emails">
<meta name="description" content="nuevoMailer by Designerfreesolutions.com. Design and send unlimited personalized html and text newsletters to your subscribers. Manage mailing lists.  Newsletter personalization & merging. Supports all email components. Send statistics and reporting clicks and views.">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $obj->getSetting("groupGlobalCharset", $idGroup); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script type="text/javascript" language="javascript">var myCustomEncoding="<?php echo $groupGlobalCharset;?>";</script>
<script src="./scripts/jQuery_2.1.0.js" type="text/javascript"></script>
<script src="./scripts/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
<script src="./scripts/index.js" type="text/javascript"></script>
<script src="./scripts/scripts.js" type="text/javascript"></script>
<link href="./includes/site.css" rel="stylesheet" type="text/css">
</head>
<body id="body">
<?php showMessageBox();?>
<div id="pageHeader" class="pageHeader">
	<div style="margin-right:auto;margin-left:auto;width:1000px; ">
		<div>	
			<img style="position:absolute;top:0;left:0;margin:0px 0px 0px 0px;" alt="<?php echo $obj->getSetting("groupName", $idGroup); ?>" src="../assets/<?php echo  $obj->getSetting("groupLogo", $idGroup);?>">
			<span class="company"  style="position:absolute;top:0;right:0;margin-top:5px;margin-right:20px"><?php echo $obj->getSetting("groupName", $idGroup); ?></span>
		</div>
		<div>&nbsp;</div>

		<div style="float:left;margin-top:30px;margin-left:50px;">&nbsp;</div>
		<div style="float:right;margin-top:40px;margin-right:50px;">&nbsp;</div>
		<div style="clear:both;"></div>
	</div>
</div><!-- "header"-->
<div class="nvmark"><span>nuevo<br>Mailer</span></div>
<div id="pageContainer" class="pageContainer">
	<div id="pageWrapper" class="pageWrapper">
		<div style="width:960px;">
		<div id="pageCenter" class="iCenter">
			<div id="adiv" style="padding-top:00px;padding-left:00px;">
				<form name="jump" id="langform" method="post" action="changeLanguage.php">
					<div><span class="title"><?php echo ADMIN_INDEX_1; ?></span></div>
					<div><span style="color:#999;padding-left:10px">nuevoMailer v.<?php echo $nuevoRelease?></span></div>
					<div style="margin-top:20px">
		  				<label style="display: inline-block;width:135px"><?php //echo ADMIN_INDEX_6; ?></label>
						<select class="select" name="language" onChange="location=document.jump.language.options[document.jump.language.selectedIndex].value;">
							<option value="changeLanguage.php?language=english"><?php echo ADMIN_INDEX_7; ?></option>
							<option value="changeLanguage.php?language=english">English</option>
							<option value="changeLanguage.php?language=portuguese">Portuguese</option>
							<option value="changeLanguage.php?language=spanish">Spanish</option>
							<option value="changeLanguage.php?language=italiano">Italian</option>
							<option value="changeLanguage.php?language=french">French</option>
							<option value="changeLanguage.php?language=german">German</option>
							<option value="changeLanguage.php?language=greek">Greek</option>
							<option value="changeLanguage.php?language=chinese">Chinese</option>
							<!--option value="changeLanguage.php?language=dutch">Dutch</option-->
							<option value="changeLanguage.php?language=other">Your language</option>
						  </select>
					</div>
				</form>
			</div>
			<div id="bdiv" style="padding-top:10px;padding-left:00px;">
				<form name="loginform" id="loginform" onsubmit="doLogin();return false;" method="get" action="">
					<div style="margin-top:10px">
						<label style="display:inline-block;width:135px;"><?php echo ADMIN_INDEX_2; ?></label>
							<?php
								if (@$pdemomode){
								echo ('<input type="text" id="adminName" name="adminName" size="18" value="admin" class="fieldbox11">');}
								else {
								echo ('<input type="text" id="adminName" name="adminName" size="18" value="" class=fieldbox11>');
								} ?>
				    </div>
					<div style="margin-top:5px">
						<label style="display: inline-block;width:135px"><?php echo ADMIN_INDEX_3; ?></label>
					    	<?php
								if (@$pdemomode)
								echo ('<input type="password" id="adminPassword" name="adminPassword" size="18" value="123" class="fieldbox11">');
								else
								echo ('<input type="password" id="adminPassword" name="adminPassword" size="18" value="" class=fieldbox11>');?>
					</div>
						<div>
							<div style="display:inline-block;width:135px;">&nbsp;</div>
							<input id="submitButton" type="submit" class="submit" name="Submit2"  value="<?php echo ADMIN_INDEX_4; ?>">
							<div><?php //echo ADMIN_INDEX_10.':'; ?>  <input type=hidden id="rememberme" name="rememberme" value="-1"></div>
							<div id="ForgotPassword"><br><a class="cross" href="#" onclick="show_hide_div('ForgotPasswordForm','cross');clearAllLoginErrors();return false;"><span id="cross">[+]</span>&nbsp;<?php echo ADMIN_INDEX_5; ?></a></div>
						</div>
				</form>
			</div>
			<div id="cdiv" style="padding-top:10px;padding-left:00px;">
				<div id="ForgotPasswordForm" style="display:none;">
						<label style="display: inline-block;width:135px"><b><?php echo ADMIN_INDEX_8; ?></b></label>
						<input type="text" class="fieldbox11" id="adminEmail" name="adminEmail" value="" size="40">
						<input id="sendPassButton" type="submit" class="submit" onclick="sendPassword();return false;" name="sendPassButton" value="<?php echo ADMIN_INDEX_9; ?>">
				</div>
			</div>
			<div id="ddiv" style="padding-top:10px;padding-left:00px;width:400px;">
						<div id="loading" style="display:none;text-align:center"><img alt="" src="./images/waitBig.gif"><br><?php echo GENERIC_4; ?></div>
						<div id="loginerror1" class="errormessage" style="display:none;"><img alt="" src="./images/warning.png">&nbsp;<?php echo ADMIN_LOGIN_1; ?></div>
						<div id="loginerror2" class="errormessage" style="display:none;"><img alt="" src="./images/warning.png">&nbsp;<?php echo ADMIN_LOGIN_2; ?></div>
						<div id="loginerror3" class="errormessage" style="display:none;"><img alt="" src="./images/warning.png">&nbsp;<?php echo ADMIN_INDEX_11; ?></div>
						<div id="loginerror4" class="errormessage" style="display:none;"><img alt="" src="./images/warning.png">&nbsp;<?php echo ADMIN_INDEX_15; ?></div>
						<div id="loginerror6" class="errormessage" style="display:none;"><img alt="" src="./images/warning.png">&nbsp;<?php echo GENERIC_8; ?></div>
						<div id="confirm1"    class="okmessage"    style="display:none;"><img alt="" src="./images/doneOk.png">&nbsp;<?php echo ADMIN_INDEX_13; ?></div>
						<div id="exception" style="display:none;"></div>
			</div>
			<br><br>
			<div align=center>
						<?php  if ($pdemomode && $_SESSION['adminLang']!=="english") {?>
				             <div align="center"><span style="FONT-SIZE: 12px; FONT-FAMILY: Arial">Would you like to contribute with a language translation?</span></div>
				             <div align="center"><span style="FONT-SIZE: 12px; FONT-FAMILY: Arial">We offer a license in exchange.</span></div>
						<?php } ?>
			</div>
		</div>	<!--pageCenter-->
		</div>
    		<?php if (@$pdemomode) { ?>
    		<div align="center" style="margin-top:15px;">
    			<span style="color:#000;font-size:12px">This is a demonstration of nuevoMailer.</span>
				<br><a target="blank" href="http://www.nuevomailer.com?demo"><span style="color:#000;font-size:12px">Click here to learn more.</span></a>
				<div style="margin-top:12px"><span style="color:#000;">&copy; <?php echo date('Y')?> - DesignerFreeSolutions.com</span></div>
			</div>
 	   		<?php   } ?>
	</div>	<!--pageWrapper-->
	</div>	<!--Container-->
	<div id="screenblur" style="display: none; position: fixed; top: 0pt; left: 0pt; z-index: 998; width: 100%; height:100%; background-image: url(./images/screenblur.png);"></div>
</body>
</html>