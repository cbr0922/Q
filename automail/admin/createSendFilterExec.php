<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
$obj 	 = new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupGlobalCharset =   $obj->getSetting("groupGlobalCharset", $idGroup);
@$emailisvalid			= dbQuotes($_POST['emailisvalid']);
if ($emailisvalid!=-1) { $emailisvalid=0;}

@$emailisbanned			= dbQuotes($_POST['emailisbanned']);
if ($emailisbanned!=-1) { $emailisbanned=0;}

//FIRST CONSTRUCT THE SQL FOR THE FILTER
if (!empty($_POST['processButton'])) {
	//lets do a check first
	if (!empty($_POST['processButton']) AND !($emailisbanned) AND !($emailisvalid) AND ($_POST['timesMailedOPR']=="" OR $_POST['timesMailed']=="") AND ($_POST['soft_bouncesOPR']=="" OR $_POST['soft_bounces']=="") AND ($_POST['hard_bounces']=="" OR $_POST['hard_bouncesOPR']=="") AND  $_POST['internalmemocontains']=="" AND $_POST['dateSubscribed']=="" AND $_POST['dateLastUpdated']=="" AND $_POST['dateLastEmailed']=="" AND $_POST['ipcontains']==""  AND $_POST['pconfirmed']==3 AND $_POST['prefers']==3 AND $_POST['countryCodeis']=="" AND $_POST['zipcontains']=="" AND $_POST['emailcontains']=="" AND  $_POST['lastnamecontains']=="" AND  $_POST['subcompanycontains']=="" AND  $_POST['subphone1contains']=="" AND  $_POST['subphone2contains']=="" AND  $_POST['submobilecontains']=="" AND  $_POST['namecontains']=="" AND $_POST['addresscontains']=="" AND $_POST['citycontains']=="" AND $_POST['stateCodeis']=="" AND $_POST['birthdayis']=="" AND $_POST['birthmonthis']=="" AND $_POST['birthyearis']=="" AND $_POST['pcustomsubfield1']=="" AND $_POST['pcustomsubfield2']=="" AND $_POST['pcustomsubfield3']=="" AND $_POST['pcustomsubfield4']=="" AND $_POST['pcustomsubfield5']=="") {
		echo '<img src="./images/warning.png">&nbsp;'.CREATESENDFILTER_4;
		die;
	}
	$strSQL="";
	if (!empty($_POST['emailcontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.email LIKE '%".dbQuotes($_POST['emailcontains'])."%'";
	}
	if (!empty($_POST['namecontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.name LIKE '%".dbQuotes($_POST['namecontains'])."%'";
	}
	if (!empty($_POST['lastnamecontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.lastname LIKE '%".dbQuotes($_POST['lastnamecontains'])."%'";
	}
	if (!empty($_POST['subcompanycontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.subcompany LIKE '%".dbQuotes($_POST['subcompanycontains'])."%'";
	}
	if (!empty($_POST['addresscontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.address LIKE '%".dbQuotes($_POST['addresscontains'])."%'";
	}
   if (!empty($_POST['citycontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.city LIKE '%".dbQuotes($_POST['citycontains'])."%'";
	}
	if (!empty($_POST['stateCodeis'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.state = '".dbQuotes($_POST['stateCodeis'])."'";
	}
	if (!empty($_POST['zipcontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.zip LIKE '%".dbQuotes($_POST['zipcontains'])."%'";
	}
	if (!empty($_POST['countryCodeis'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.country = '".dbQuotes($_POST['countryCodeis'])."'";
	}
	if (!empty($_POST['subphone1contains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.subphone1 LIKE '%".dbQuotes($_POST['subphone1contains'])."%'";
	}
	if (!empty($_POST['subphone2contains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.subphone2 LIKE '%".dbQuotes($_POST['subphone2contains'])."%'";
	}
	if (!empty($_POST['submobilecontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.submobile LIKE '%".dbQuotes($_POST['submobilecontains'])."%'";
	}
	if ( (!empty($_POST['timesMailed']) || $_POST['timesMailed']==0) AND !empty($_POST['timesMailedOPR'])) {
		$strSQL=$strSQL." AND (".$idGroup."_subscribers.timesMailed".$_POST['timesMailedOPR'].$_POST['timesMailed'].")";
	}
	if ((!empty($_POST['soft_bounces']) || $_POST['soft_bounces']==0) AND !empty($_POST['soft_bouncesOPR'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.soft_bounces".$_POST['soft_bouncesOPR'].$_POST['soft_bounces'];
	}
	if ((!empty($_POST['hard_bounces']) || $_POST['hard_bounces']==0) AND !empty($_POST['hard_bouncesOPR'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.hard_bounces".$_POST['hard_bouncesOPR'].$_POST['hard_bounces'];
	}
	if (!empty($_POST['ipcontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.ipSubscribed LIKE '%".dbQuotes($_POST['ipcontains'])."%'";
	}
	if (!empty($_POST['pcustomsubfield1'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.customSubField1 LIKE '%".dbQuotes($_POST['pcustomsubfield1'])."%'";
	}
	if (!empty($_POST['pcustomsubfield2'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.customSubField2 LIKE '%".dbQuotes($_POST['pcustomsubfield2'])."%'";
	}
	if (!empty($_POST['pcustomsubfield3'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.customSubField3 LIKE '%".dbQuotes($_POST['pcustomsubfield3'])."%'";
	}
	if (!empty($_POST['pcustomsubfield4'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.customSubField4 LIKE '%".dbQuotes($_POST['pcustomsubfield4'])."%'";
	}
	if (!empty($_POST['pcustomsubfield5'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.customSubField5 LIKE '%".dbQuotes($_POST['pcustomsubfield5'])."%'";
	}
	if (!empty($_POST['dateSubscribed'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.dateSubscribed ".my_stripslashes($_POST['dateSubscribed']);
	}
	if (!empty($_POST['dateLastUpdated'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.dateLastUpdated ".my_stripslashes($_POST['dateLastUpdated']);
	}
	if (!empty($_POST['dateLastEmailed'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.dateLastEmailed ".my_stripslashes($_POST['dateLastEmailed']);
	}
	if (!empty($_POST['internalmemocontains'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.internalmemo LIKE '%".dbQuotes($_POST['internalmemocontains'])."%'";
	}
	if (!empty($_POST['birthdayis'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.subBirthDay ='".$_POST['birthdayis']."'";
	}
	if (!empty($_POST['birthmonthis'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.subBirthMonth ='".$_POST['birthmonthis']."'";
	}
	if (!empty($_POST['birthyearis'])) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.subBirthYear ='".$_POST['birthyearis']."'";
	}
	if ($emailisvalid==-1) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.emailIsValid=0";
	} //else {$strSQL=$strSQL." AND ".$idGroup."_subscribers.emailIsValid=-1";}
	if ($emailisbanned==-1) {
		$strSQL=$strSQL." AND ".$idGroup."_subscribers.emailIsBanned=-1";
	} //else {$strSQL=$strSQL." AND ".$idGroup."_subscribers.emailIsBanned=0";}


	$strSQL5=$strSQL;

//NOW PREPARE THE REST OF THE QUERY FOR THE COUNT
	$pidList 		=	$_POST['idList'];
	$pidfromemail	=	0;

	//Prepare the SQL for subscribers
	$pprefers		=	$_POST['prefers'];
	switch ($pprefers) {
	  case 1:
		$strSQL2 = ' AND '.$idGroup.'_subscribers.prefersHtml=-1';
	  break;
	  case 2:
		$strSQL2 = ' AND '.$idGroup.'_subscribers.prefersHtml=0';
	  break;
	  default:
		$strSQL2='';
	}

	$pconfirmed		=	$_POST['pconfirmed'];
	switch ($pconfirmed) {
	  case 1:
		$strSQL3 = ' AND '.$idGroup.'_subscribers.confirmed=-1';
	  break;
	  case 2:
		$strSQL3 = ' AND '.$idGroup.'_subscribers.confirmed=0';
	  break;
	  default:
		$strSQL3='';
	}

	$strSELECT = 'SELECT distinct '.$idGroup.'_subscribers.idEmail as ID, '.$idGroup.'_subscribers.email, '.$idGroup.'_subscribers.name as first_name, '.$idGroup.'_subscribers.lastName as last_name, '.$idGroup.'_subscribers.subCompany as company_name, '.$idGroup.'_subscribers.address, '.$idGroup.'_subscribers.city, '.$idGroup.'_subscribers.state, '.$idGroup.'_subscribers.zip, '.$idGroup.'_subscribers.country, '.$idGroup.'_subscribers.subPhone1 as Tel_1, '.$idGroup.'_subscribers.subPhone2 as Tel_2, '.$idGroup.'_subscribers.subMobile as mobile, '.$idGroup.'_subscribers.prefersHtml, '.$idGroup.'_subscribers.confirmed, '.$idGroup.'_subscribers.timesMailed, '.$idGroup.'_subscribers.soft_bounces, '.$idGroup.'_subscribers.hard_bounces, '.$idGroup.'_subscribers.emailIsValid, '.$idGroup.'_subscribers.emailIsBanned, '.$idGroup.'_subscribers.ipSubscribed, '.$idGroup.'_subscribers.customSubField1 as custom_field_1, '.$idGroup.'_subscribers.customSubField2 as custom_field_2, '.$idGroup.'_subscribers.customSubField3 as custom_field_3, '.$idGroup.'_subscribers.customSubField4 as custom_field_4, '.$idGroup.'_subscribers.customSubField5 as custom_field_5, '.$idGroup.'_subscribers.dateSubscribed, '.$idGroup.'_subscribers.dateLastUpdated, '.$idGroup.'_subscribers.dateLastEmailed, '.$idGroup.'_subscribers.internalMemo, '.$idGroup.'_subscribers.subBirthDay as birth_day, '.$idGroup.'_subscribers.subBirthMonth as birth_month, '.$idGroup.'_subscribers.subBirthYear as birth_year ';
	$strSQL4 = " ORDER by ".$idGroup."_subscribers.idEmail asc";

	if ($pidList==-2) {	//all lists
		$mySQL=$strSELECT." FROM ".$idGroup."_subscribers, ".$idGroup."_listRecipients WHERE ".$idGroup."_subscribers.idGroup=$idGroup AND ".$idGroup."_listRecipients.idEmail=".$idGroup."_subscribers.idEmail AND ".$idGroup."_subscribers.idEmail>".$pidfromemail .$strSQL2 .$strSQL3 .$strSQL5 .$strSQL4;
		$mySQLB = "SELECT distinct ".$idGroup."_subscribers.idEmail FROM ".$idGroup."_subscribers INNER JOIN ".$idGroup."_listRecipients on ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail WHERE ".$idGroup."_subscribers.idGroup=$idGroup ".$strSQL2 .$strSQL3 .$strSQL5;
	} else if ($pidList==-1) {	//all subscribers
		$mySQL=$strSELECT." FROM ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idGroup=$idGroup AND ".$idGroup."_subscribers.idEmail>".$pidfromemail .$strSQL2 .$strSQL3 .$strSQL5 .$strSQL4;
		$mySQLB = "SELECT distinct ".$idGroup."_subscribers.idEmail FROM ".$idGroup."_subscribers WHERE ".$idGroup."_subscribers.idGroup=$idGroup ".$strSQL2 .$strSQL3 .$strSQL5;
	} else {	//specific list.
		$mySQL=$strSELECT.", ".$idGroup."_listRecipients.idList FROM ".$idGroup."_subscribers, ".$idGroup."_listRecipients WHERE ".$idGroup."_listRecipients.idGroup=$idGroup AND ".$idGroup."_listRecipients.idList=".$pidList." AND ".$idGroup."_listRecipients.idEmail=".$idGroup."_subscribers.idEmail AND ".$idGroup."_subscribers.idEmail>".$pidfromemail.$strSQL2 .$strSQL3 .$strSQL5 .$strSQL4;
		$mySQLB = "SELECT distinct ".$idGroup."_subscribers.idEmail FROM ".$idGroup."_subscribers INNER JOIN ".$idGroup."_listRecipients on ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail WHERE ".$idGroup."_listRecipients.idList=".$pidList." AND ".$idGroup."_listRecipients.idGroup=$idGroup ".$strSQL2 .$strSQL3 .$strSQL5;
	}
	$result = $obj->query($mySQLB);
	$rows 	= $obj->num_rows($result);

?>
	<table width="900" cellpadding="2" cellspacing="0" border="0">
		<tr>
			<td valign="top" width="150" colspan="3" bgcolor="#f1e2d3">
				<?php echo '<b>'.CREATESENDFILTER_33 .$rows .CREATESENDFILTER_34 .'</b>'?>
			</td>
			<td colspan=2 bgcolor="#f1e2d3"><?php If (!empty($strSQL5) AND $rows!=0) {?><b><?php echo CREATESENDFILTER_55?></b><?php }?></td>
		</tr>
		<tr>
			<?php If ($rows!=0) {?>
			<td bgcolor="#f1e2d3">
				<form method=post action="quickView.php" name="view" target="_blank">
					<input type="hidden" name="mySQL" value="<?php echo $mySQL?>">
					<input class="submit" type="submit"  name="view" value="<?php echo CREATESENDFILTER_35; ?>">
				</form>
			</td>
			<td bgcolor="#f1e2d3">
				<form method=post action="quickXL.php" name="view" target="blank">
					<input type="hidden" name="mySQL" value="<?php echo $mySQL?>">
					<input class="submit" type="submit"  name="view" value="<?php echo CREATESENDFILTER_46; ?>">
				</form>
			</td>
			<td bgcolor="#f1e2d3">
				<form method=post action="createFilteredList.php" name="createFilteredList">
					<input type="hidden" name="mySQL" value="<?php echo $mySQLB?>">
					<input class="submit" type="submit"  name="createList" value="<?php echo CREATESENDFILTER_43; ?>">
				</form>
			</td>
			<?php }?>
			<?php If (!empty($strSQL5) AND $rows!=0) {?>
			<td valign=top bgcolor="#f1e2d3">
				<?php echo CREATESENDFILTER_36; ?>
			</td>
			<td bgcolor="#f1e2d3">
				<form method="get" action="editSendFiltersExec.php" name="addNew">
					<input type="hidden" name="sendFilterCode" value="<?php echo stripslashes($strSQL5)?>">
					<div align=center><input class="submit" type="submit"  name="add" value="<?php echo CREATESENDFILTER_37; ?>">
				</form>
			</td>
			<?php
			} else {?>
			<td valign="top" width="150" colspan="5" bgcolor="#f1e2d3">
				<?php echo CREATESENDFILTER_38; ?>
			</td>
			<?php }?>
		</tr>
	</table>
    <?php
}	//for generate form
$obj->closeDb();
?>