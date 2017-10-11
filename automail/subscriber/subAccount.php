<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/encryption.php');
include('../inc/languages.php');

$groupEncryptionPassword = $obj->getSetting("groupEncryptionPassword", $idGroup);
$groupGlobalCharset      =	$obj->getSetting("groupGlobalCharset", $idGroup);
$mailData["groupName"]   =	$obj->getSetting("groupName", $idGroup);
$thisYear=date("Y");
//data coming from optOut.php
(isset($_GET['email']))?$sub["email"] = dbQuotes(dbProtect($_GET['email'],500)):$sub["email"]="";
(isset($_GET['password']))?$sub["password"] = dbQuotes(dbProtect($_GET['password'],500)):$sub["password"]="";
(isset($_GET['idEmail']))?$sub["idEmail"] = dbQuotes(dbProtect($_GET['idEmail'],20)):$sub["idEmail"]="";

if (!$sub["idEmail"] && !$sub["password"])  {
    $message=SUBACCOUNT_27;
    header("Location:subLogin.php?subemail=".$sub["email"]."&message=".$message."");
    die;
}
if ($sub["password"])  {
	$pSQL = " AND subPassword='".$sub["password"]."'";
} else {
	$pSQL = "";
}
if ($sub["idEmail"])  {
	$eSQL = " AND idEmail='".$sub["idEmail"]."'";
} else {
	$eSQL = "";
}
@$message =dbQuotes(dbProtect($_GET['message'],500));
//Get name and id of subscriber based on the email entered	//query extend for rest of subscriber fields
$mySQL2="SELECT idEmail, email, name, lastName, subPassword, subCompany, address, city, state, zip, country, subPhone1, subPhone2, subMobile, customSubField1, customSubField2, customSubField3, customSubField4, customSubField5, subBirthDay, subBirthMonth, subBirthYear FROM ".$idGroup."_subscribers WHERE email='".$sub["email"]."'".$pSQL.$eSQL;
$result	= $obj->query($mySQL2);
$row = $obj->fetch_array($result);
if (!$row) {
    $message=SUBACCOUNT_13;
    header("Location:subLogin.php?subemail=".$sub["email"]."&message=".$message."");
    die;
}
else {
    //there is a record of this subscriber
		$pidemail			=  $row['idEmail'];
		$pName 				= $row['name'];
		$pSubPassword		=  $row['subPassword'];
		$pSubLastName  		= $row['lastName'];
		// new lines in v.200 for other subscriber data.
		$psubCompany		= $row['subCompany'];
		$paddress			= $row['address'];
		$pcity				= $row['city'];
		$pstateCode				= $row['state'];
		$pzip				= $row['zip'];
		$pcountryCode			= $row['country'];
		$psubPhone1			= $row['subPhone1'];
		$psubPhone2			= $row['subPhone2'];
		$psubMobile			= $row['subMobile'];
		$dcustomSubField1 	= $row['customSubField1'];
		$dcustomSubField2	= $row['customSubField2'];
		$dcustomSubField3	= $row['customSubField3'];
		$dcustomSubField4	= $row['customSubField4'];
		$dcustomSubField5	= $row['customSubField5'];
		$psubBirthDay		= $row['subBirthDay'];
		$psubBirthMonth		= $row['subBirthMonth'];
		$psubBirthYear		= $row['subBirthYear'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $mailData["groupName"].' - '.SUBACCOUNT_2;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $groupGlobalCharset?>">
</head>
<body style="FONT-FAMILY: Arial, Verdana, Helvetica, sans-serif; FONT-SIZE: 12px;">
<!-- if you want to resize the window use the following <body>.
Handy if you used a direct unsubscribe link to come here.
<body onLoad="resizeTo(700,500);"> -->

<div align="center">
    <span style=" FONT-FAMILY: Arial, helvetica; FONT-SIZE: 20px; FONT-WEIGHT: bold; color:#565656;"><?php echo $mailData["groupName"]?></span>
</div>
<hr/>
<div align="left">
<form action="subUpdate.php" method="get" name="showMeMy">
<div id="profileData" style="float:left;border: #000 0px solid;margin-left:200px;">
<table cellpadding="3" border="0" align="center" cellspacing="0">
	<tr>
		<td colspan=2>
			<b><?php echo SUBACCOUNT_6?>&nbsp;<?php echo $pName.' '.$pSubLastName?></b>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_23?>:
		</td>
		<td>
			<input type="text" name="subname" class="button" value="<?php echo $pName?>">
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_25?>:
		</td>
		<td>
			<input type="text" name="sublastname" class="button" value="<?php echo $pSubLastName?>">
		</td>
	</tr>

	<tr>
		<td>
			<?php echo SUBACCOUNT_4?>:
		</td>
		<td>
			<input type="text" name="email" class="button" value="<?php echo $sub["email"]?>">
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_17?>:
		</td>
		<td>
			<input type="password" name="password" class="button" value="<?php echo $sub["password"]?>">
		</td>
	</tr>
<!--START new lines for other subscriber data -->
	<tr>
		<td>
			<?php echo SUBACCOUNT_56;?>:
		</td>
		<td>
			<input type="text" name="subCompany" class="button" value="<?php echo $psubCompany?>">
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_57;?>:
		</td>
		<td>
			<input type="text" name="address" class="button" value="<?php echo $paddress?>">
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_58;?>:
		</td>
		<td>
			<input type="text" name="city" class="button" value="<?php echo $pcity?>">
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_59;?>:
		</td>
		<td>
			<input type="text" name="zip" class="button" value="<?php echo $pzip?>">
		</td>
	</tr>

	<tr>
		<td>
			<?php echo SUBACCOUNT_60;?>:
		</td>
		<td>
			<?php
			$mySQLs="SELECT * from ".$idGroup."_states where idGroup=$idGroup order by stateName asc ";
			$states	= $obj->query($mySQLs);?>
			<select name="stateCode" class="select">
				<option value=""><?php echo SUBACCOUNT_66;?></option>
				<?php while ($row = $obj->fetch_array($states)){ ?>
				<option value="<?php echo $row['stateCode'].'"'; if ($row['stateCode']==strtoupper($pstateCode)){echo ' selected';} echo '>' .$row['stateName'];?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_61;?>:
		</td>
		<td>
			<?php
			$mySQLs="SELECT * from ".$idGroup."_countries where idGroup=$idGroup order by countryName asc";
			$countries	= $obj->query($mySQLs);?>
			<select name="countryCode" class="select">
				<option value=""><?php echo SUBACCOUNT_66;?></option>
				<?php while ($row = $obj->fetch_array($countries)){ ?>
				<option value="<?php echo $row['countryCode'].'"'; if ($row['countryCode']==strtoupper($pcountryCode)){echo ' selected';} echo '>' .$row['countryName'];?></option>
				<?php } ?>
			</select>

		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_62;?>:
		</td>
		<td>
			<input type="text" name="subPhone1" class="button" value="<?php echo $psubPhone1?>">
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_63;?>:
		</td>
		<td>
			<input type="text" name="subPhone2" class="button" value="<?php echo $psubPhone2?>">
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_64;?>:
		</td>
		<td>
			<input type="text" name="subMobile" class="button" value="<?php echo $psubMobile?>">
		</td>
	</tr>
	<tr>
		<td>
			<?php echo SUBACCOUNT_65;?>:
		</td>
		<td>
			<select name="subBirthDay" class="select">
				<option value=""><?php echo SUBACCOUNT_66;?></option>
				<?php
				for ($i = 1; $i<=31; $i++) {?>
				<option value="<?php echo $i?>" <?php if ($i==$psubBirthDay) {echo ' selected';}?>><?php echo $i?></option>
				<?php }?>
			</select>&nbsp;
			<select name="subBirthMonth" class="select">
				<option value=""><?php echo SUBACCOUNT_66;?></option>
				<?php
				for ($m = 1; $m<=12; $m++) {?>
				<option value="<?php echo $m?>" <?php if ($m==$psubBirthMonth) {echo ' selected';}?>><?php echo $m?></option>
				<?php }?>
			</select>&nbsp;
			<select name="subBirthYear" id="subBirthYear" class="select">
				<option value=""><?php echo SUBACCOUNT_66; ?></option>
				<?php for ($y=1920; $y<$thisYear+1; $y++) {?>
				<option value="<?php echo $y;?>" <?php if ($y==$psubBirthYear) {echo " selected";}?>><?php echo $y?></option>
				<?php }?></select>
 		</td>
	</tr>
	<?php if ($obj->getSetting("groupCustomSubField1", $idGroup)!="") { ?>
	<tr>
		<td>
			<?php echo "";?><?php echo $obj->getSetting("groupCustomSubField1", $idGroup);?>:
		</td>
		<td>
			<input type="text" name="customSubField1" class="button" value="<?php echo $dcustomSubField1 ?>">
		</td>
	</tr>
	<?php }
 	if ($obj->getSetting("groupCustomSubField2", $idGroup)!="") { ?>
	<tr>
		<td>
			<?php echo "";?><?php echo $obj->getSetting("groupCustomSubField2", $idGroup);?>:
		</td>
		<td>
			<input type="text" name="customSubField2" class="button" value="<?php echo $dcustomSubField2 ?>">
		</td>
	</tr>
	<?php }
 	if ($obj->getSetting("groupCustomSubField3", $idGroup)!="") { ?>
	<tr>
		<td>
			<?php echo "";?><?php echo $obj->getSetting("groupCustomSubField3", $idGroup);?>:
		</td>
		<td>
			<input type="text" name="customSubField3" class="button" value="<?php echo $dcustomSubField3 ?>">
		</td>
	</tr>
	<?php }
 	if ($obj->getSetting("groupCustomSubField4", $idGroup)!="") { ?>
	<tr>
		<td>
			<?php echo "";?><?php echo $obj->getSetting("groupCustomSubField4", $idGroup);?>:
		</td>
		<td>
			<input type="text" name="customSubField4" class="button" value="<?php echo $dcustomSubField4 ?>">
		</td>
	</tr>
	<?php }
 	if ($obj->getSetting("groupCustomSubField5", $idGroup)!="") { ?>
	<tr>
		<td>
			<?php echo "";?><?php echo $obj->getSetting("groupCustomSubField5", $idGroup);?>:
		</td>
		<td>
			<input type="text" name="customSubField5" class="button" value="<?php echo $dcustomSubField5 ?>">
		</td>
	</tr>
	<?php }?>
<!--END new lines for other subscriber data -->
</table>
</div>
<div id="listData" style="float:left;border: #000 0px solid;margin-left:50px;margin-top:30px;">
<table cellpadding="3" border="0" align="center" cellspacing="0" width="350px">
	<tr>
		<td>
			<b><?php echo SUBACCOUNT_7?></b>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?php
			//get lists where he is not subscribed
          $mySQL3="SELECT idList, listName FROM ".$idGroup."_lists where isPublic=-1 AND idGroup=$idGroup ORDER BY idList desc";
          $result	= $obj->query($mySQL3);
          $rows 	= $obj->num_rows($result);
          if ($rows) {
                while ($row = $obj->fetch_array($result)){?>
               <input name="idList[]" type="checkbox" value="<?php echo $row['idList'];?>"
                <?php if ($pidemail) {
                $mySQL4="SELECT * from ".$idGroup."_listRecipients WHERE idList=".$row['idList']." AND idEmail=".$pidemail;
                $checked	= $obj->query($mySQL4);
                $isIn = $obj->fetch_array($checked);
                if ($isIn) {echo ' checked';}}?>><?php echo $row['idList'].'. '.$row['listName'].'<br>';?>
          <?php }
                } else {echo SUBACCOUNT_28;}?>
		</td>
	</tr>
	<tr>
		<td style="padding-top:20px;">
			<?php echo SUBACCOUNT_8?><br>
		</td>
	</tr>
	<tr>
		<td align=right>
			<?php if ($message) {?><div style="margin-top:15px;"><span style="color:#007700;background:#fafae3;border:#989898 1px solid;padding:7px;FONT-SIZE: 12px;"><?php echo $message?></span></div>&nbsp;&nbsp;&nbsp;&nbsp;<?php }?>
			<div><input class="button" type="submit" name="update" value="<?php echo SUBACCOUNT_9?>"></div>
		</td>
	</tr>
		<tr>
			<td style="padding-top:20px; text-align:right;"><a target="_blank" href="privacy.php"><?php echo SUBACCOUNT_26?></a></td>
		</tr>
</table>
</div>
<div id="cleaner" style="clear:both;"></div>
<input type="hidden" name="idEmail" value="<?php echo $pidemail?>">
<input type="hidden" name="oldEmail" value="<?php echo $sub["email"]?>">
<input type="hidden" name="oldPass" value="<?php echo $sub["password"]?>">
</form>
<?php 	$obj->closeDb();?>
</div>
<?php if (@$pdemomode) { ?>
	<div align="center" style="margin-top:50px; border-top:#888 1px solid">
		<span style="color:#000;font-size:12px;font-family:Verdana, Arial">This is a demonstration of nuevoMailer.</span>
		<br><a target="blank" href="http://www.nuevomailer.com?demo"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">Click here to learn more.</span></a>
		<div style="margin-top:12px"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">&copy; <?php echo date('Y')?> - DesignerFreeSolutions.com</span></div>
	</div>
<?php   } ?>

</body>
</html>