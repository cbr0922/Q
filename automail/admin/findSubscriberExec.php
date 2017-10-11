<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj 					= new db_class();
$groupName 				=	$obj->getSetting("groupName", $idGroup);
include('header.php');
showMessageBox();
$self 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['idList']))?$idlist = $_GET['idList']:$idlist=0;

(isset($_GET['searchStr']))?$searchStr = dbQuotes($_GET['searchStr']):$searchStr="";
(isset($_GET['searchBy']))?$searchBy = $_GET['searchBy']:$searchBy="3";

$plists =  $obj->tableCount_condition($idGroup."_lists", " WHERE idGroup=".$idGroup."");
?>
<table width="960px" border=0>
	<tr>
		<td valign=top>
			<font class="title"><?php echo FINDSUBSCRIBEREXEC_1; ?></font>
			<br><br><a href="createSendFilter.php"><?php echo FINDSUBSCRIBEREXEC_18; ?></a>
		</td>
		<td align="right" valign="top">
			<img src="./images/findsubscriber.png" width="60" height="47">
		</td>
	</tr>
</table>
<br>
<form method="get" name="findSubscr" action="findSubscriberExec.php">
	<b><?php echo FINDSUBSCRIBEREXEC_3; ?></b>
	&nbsp;
	<SELECT name="searchBy" class="select">
		<option value=""><?php echo FINDSUBSCRIBEREXEC_4; ?></option>
		<option value="1" <?php if ($searchBy=="1") { echo " selected";}?>><?php echo FINDSUBSCRIBEREXEC_7; ?></option>
		<option value="2" <?php if ($searchBy=="2") { echo " selected";}?>><?php echo FINDSUBSCRIBEREXEC_8; ?></option>
		<option value="3" <?php if ($searchBy=="3") { echo " selected";}?>><?php echo FINDSUBSCRIBEREXEC_17; ?></option>
		<option value="4" <?php if ($searchBy=="4") { echo " selected";}?>>Email id</option>
	</select>
	&nbsp;&nbsp;&nbsp;
	<b><?php echo FINDSUBSCRIBEREXEC_16; ?></b>
	<input class="fieldbox11" type=text name="searchStr" value='<?php echo $searchStr?>'>
	&nbsp;&nbsp;&nbsp;
	<input type="submit" class="submit" name="search" value="<?php echo FINDSUBSCRIBEREXEC_5; ?>">
<?php
if (($searchStr=="" OR $searchBy=="") AND !empty($_GET['search'])) {
	echo '<span style=COLOR:red>'.FINDSUBSCRIBEREXEC_2.'</span>';
}
?>
</form>
<?php
if (!empty($_REQUEST['search']) AND !empty($searchStr) AND !empty($searchBy)) {
	if ($searchBy=="1") {
		$mySQL= "SELECT idEmail, email, name, lastName FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND name LIKE '%$searchStr%' OR lastName LIKE '%$searchStr%'";
	}
	elseif ($searchBy=="2") {
		$mySQL= "SELECT idEmail, email, name, lastName FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND email LIKE '%$searchStr%'";
		}
	elseif ($searchBy=="3") {
		$mySQL= "SELECT idEmail, email, name, lastName FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND email LIKE '%$searchStr%' OR name LIKE '%$searchStr%' OR lastName LIKE '%$searchStr%'";
	}
	elseif ($searchBy=="4") {
		$mySQL= "SELECT idEmail, email, name, lastName FROM ".$idGroup."_subscribers WHERE idGroup=$idGroup AND idEmail=$searchStr";
	}
	$result	= $obj->query($mySQL);
	 $rows 	= $obj->num_rows($result);
	if (!$rows){
		echo '<img src=./images/warning.png>&nbsp;'.FINDSUBSCRIBEREXEC_15;
	} else {
?>
		<br><span class=menuSmall><?php echo $rows .' '.FINDSUBSCRIBEREXEC_6; ?>(<?php echo $searchStr?>)</font><br><br>
		<?php $redirectUrl="findSubscriberExec.php?searchStr=$searchStr&searchBy=$searchBy&search=search";
		include('doSubscribersList.php');

	}
}
include('footer.php');
//$obj->free_result($result);
$obj->closeDb();
?>