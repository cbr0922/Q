<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
$self 		 = 	$_SERVER['PHP_SELF'];
include('header.php');
showMessageBox();

(isset($_GET['sort']))?$sort = $_GET['sort']:$sort="0";
(isset($_GET['confirmed']))?$pconfirmed = $_GET['confirmed']:$pconfirmed="1";
(isset($_GET['idList']))?$idlist = $_GET['idList']:$idlist=0;


(isset($_GET['records']))?$rowsPerPage = $_GET['records']:$rowsPerPage = $obj->getSetting("groupNumPerPage", $idGroup);
$obj->query("UPDATE ".$idGroup."_groupSettings SET groupNumPerPage=$rowsPerPage WHERE idGroup=$idGroup");

(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
// counting the offset
$offset = ($page - 1) * $rowsPerPage;
?>
<script type="text/javascript" language="javascript">
function reloadPage() {
	document.location.href='<?php echo $self?>?sort='+$("#sort").val()+'&confirmed='+$("#confirmed").val()+'&idList='+$("#idList").val()+'&records='+$("#records").val();
}
</script>

<table border="0" width="960px" cellpadding=2 cellspacing=0>
	<tr>
		<td width="160" valign="top">
			<span class="title"><?php echo LISTNEWSLETTERSUBSCRIBERS_2?></span>
		</td>
		<td valign=middle>
             <form method="get" name="selectlist" id="selectlist" style="display:block;">
			   <?php ECHO LISTNEWSLETTERSUBSCRIBERS_3;
			   	//$SQL="SELECT * FROM ".$idGroup."_lists WHERE idGroup=$idGroup order by idList desc";
				$SQL="SELECT count(idEmail) as subs, ".$idGroup."_lists.listName, ".$idGroup."_lists.idList FROM ".$idGroup."_listRecipients  right join ".$idGroup."_lists on ".$idGroup."_listRecipients.idList=".$idGroup."_lists.idList GROUP BY listName, ".$idGroup."_lists.idList order by ".$idGroup."_lists.idList desc";
				$resultLists	= $obj->query($SQL);?>
				<SELECT class="select" id="idList" name="idList" onChange="reloadPage();">
					<OPTION value="0" <?php if ($idlist=="0"){echo ' selected';}?>><?php echo LISTNEWSLETTERSUBSCRIBERS_4; ?></option>
					<?php while ($row = $obj->fetch_array($resultLists)){?>
					<option value="<?php echo $row['idList'];?>"<?php if ($row['idList']==$idlist) {echo " selected";}?>><?php echo $row['idList'];?>. <?php echo $row['listName'] .' ('.$row['subs'].')';?></option>
					<?php }
					 $obj->data_seek($resultLists,0);?>
					<OPTION value="-1" <?php if ($idlist=="-1"){echo ' selected';}?>><?php echo LISTNEWSLETTERSUBSCRIBERS_5; ?></option>
				</SELECT>
				</form>
		</td>
		<td valign="top" width="48">
			<div align=right><img src="./images/subscribers.png" width="65" height="51"></div>
		</td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td valign="top" width="25%">
			<?php echo LISTNEWSLETTERSUBSCRIBERS_15; ?>: <a href="findSubscriberExec.php"><?php echo LISTNEWSLETTERSUBSCRIBERS_7; ?></a> <a href="createSendFilter.php"><?php echo LISTNEWSLETTERSUBSCRIBERS_8; ?></a>
		</td>
		<td valign="top" width="25%">
            <?php include('changeRecs.php');?>
		</td>
		<td valign=top>
			<form method="get" name="sortorder">
			<?php echo SORTORDERFORM_1; ?>
			<select class="select" id="sort" name="sort" onChange="reloadPage();">
			<option value="0" 	 <?php if ($sort=="0"){echo " selected";}?>><?php echo SORTORDERFORM_2; ?></option>
			<option value="LNASC" <?php if ($sort=="LNASC"){echo " selected";}?>><?php echo DBFIELD_16.' '.SORTORDERFORM_3; ?></option>
			<option value="LNDSC" <?php if ($sort=="LNDSC"){echo " selected";}?>><?php echo DBFIELD_16.' '.SORTORDERFORM_4; ?></option>
			<option value="NASC" <?php if ($sort=="NASC"){echo " selected";}?>><?php echo DBFIELD_2.' '.SORTORDERFORM_3; ?></option>
			<option value="NDSC" <?php if ($sort=="NDSC"){echo " selected";}?>><?php echo DBFIELD_2.' '.SORTORDERFORM_4; ?></option>
			<option value="EASC" <?php if ($sort=="EASC"){echo " selected";}?>><?php echo DBFIELD_1.' '.SORTORDERFORM_3; ?></option>
			<option value="EDSC" <?php if ($sort=="EDSC"){echo " selected";}?>><?php echo DBFIELD_1.' '.SORTORDERFORM_4; ?></option>
			</select>
			</form>
		</td>
		<td valign=top>
			<form method="get" name="confirmedorder">
			<?php echo CONFIRMEDFORM_1; ?>
			<select class="select" id="confirmed" name="confirmed" onChange="reloadPage();">
			<option value="-1" <?php if ($pconfirmed=="-1") {echo " selected";}?>><?php echo CONFIRMEDFORM_3; ?></option>
			<option value="0" <?php if ($pconfirmed=="0") {echo " selected";}?>><?php echo CONFIRMEDFORM_4; ?></option>
			<option value="1" <?php if ($pconfirmed=="1") {echo " selected";}?>><?php echo CONFIRMEDFORM_5; ?></option>
			</select>
			</form>
		</td>
	</tr>
	<tr>
		<td colspan=4><hr>
		</td>
	</tr>
</table>
<?php
// JOIN 	WHERE	GROUP BY	ORDER LIMIT
$plists =  $obj->tableCount_condition($idGroup."_lists", " WHERE idGroup=".$idGroup."");

if ($sort=="NASC") {$sortSQL=" order by name asc";}
else if ($sort=="NDSC") {$sortSQL=" ORDER BY name desc";}
else if ($sort=="LNASC") {$sortSQL=" ORDER BY lastName asc";}
else if ($sort=="LNDSC") {$sortSQL=" ORDER BY lastName desc";}
else if ($sort=="EASC") {$sortSQL=" ORDER BY email asc";}
else if ($sort=="EDSC") {$sortSQL=" ORDER BY email desc";}
else {$sortSQL=" ORDER BY ".$idGroup."_subscribers.idEmail desc";}

if ($pconfirmed=="-1") {$confSQL= " AND confirmed=-1";}
else if ($pconfirmed=="0") {$confSQL= " AND confirmed=0";}
else {$confSQL="";}
$limitSQL 		= " LIMIT $offset, $rowsPerPage";
if ($idlist=="0") {
	//ALL SUBS
	$mySQL='SELECT '.$idGroup.'_subscribers.idEmail, name, lastName, email FROM '.$idGroup.'_subscribers WHERE '.$idGroup.'_subscribers.idGroup='.$idGroup .$confSQL .$sortSQL .$limitSQL;
}
elseif ($idlist=="-1") {
	//NON-ASSIGNED:
	$mySQL="SELECT distinct ".$idGroup."_subscribers.idEmail, email, name, lastName FROM ".$idGroup."_subscribers WHERE NOT EXISTS (SELECT idEmail FROM ".$idGroup."_listRecipients WHERE ".$idGroup."_listRecipients.idEmail=".$idGroup."_subscribers.idEmail) $confSQL $sortSQL $limitSQL";
    //$mySQL="SELECT distinct ".$idGroup."_subscribers.idEmail, ".$idGroup."_subscribers.email, ".$idGroup."_subscribers.name, ".$idGroup."_subscribers.lastName, ".$idGroup."_subscribers.confirmed FROM ".$idGroup."_subscribers LEFT JOIN ".$idGroup."_listRecipients ON ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail WHERE (((".$idGroup."_listRecipients.idEmail) Is Null)) $confSQL $sortSQL $limitSQL";
}
else {
    $mySQL='SELECT '.$idGroup.'_subscribers.idEmail, name, lastName, email FROM '.$idGroup.'_subscribers INNER JOIN '.$idGroup.'_listRecipients on '.$idGroup.'_subscribers.idEmail='.$idGroup.'_listRecipients.idEmail WHERE '.$idGroup.'_listRecipients.idGroup='.$idGroup.' AND idList='.$idlist .$confSQL .$sortSQL .$limitSQL;
}
//echo $mySQL .'<br>';
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);

if (!$rows){
  echo "<img src='images/warning.png'>" ." ".LISTNEWSLETTERSUBSCRIBERS_1;
}
else {

// ***** WHEN VIEWING NON-ASSIGNED SHOW SOME EXTRA OPTIONS
if ($idlist=="-1"){
echo '<div><table style="border: 1px #ed7700 solid;" width=600 cellpadding=4 cellspacing=0><tr><td>
<div style="margin-top:8px;margin-bottom:8px;"><span style="FONT-SIZE: 13px; FONT-WEIGHT: NORMAL; color:#ed7700 ">'.LISTNEWSLETTERSUBSCRIBERS_10.'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'?>
<a href=# onclick="openConfirmBox('delete.php?action=unassigned','<?php echo fixJSstring(LISTNEWSLETTERSUBSCRIBERS_16);?><br><?php echo fixJSstring(GENERIC_2);?>');return false;"><?php echo LISTNEWSLETTERSUBSCRIBERS_9?></a></div>
<form action="addNonAssignedSubscribersToList.php" method="post" name="removeall">
	<?php
	echo LISTNEWSLETTERSUBSCRIBERS_6.'&nbsp;';
	?>
	<SELECT class="select" id="idList" name="idList">
		<?php while ($row = $obj->fetch_array($resultLists)){?>
		<option value="<?php echo $row['idList'];?>"<?php if ($row['idList']==$idlist) {echo " selected";}?>><?php echo $row['idList'];?>. <?php echo $row['listName'];?></option>
	<?php }?>
	</SELECT>&nbsp;<input type="submit" name="Submit" class="submit" value="<?php echo LISTNEWSLETTERSUBSCRIBERS_14; ?>">
</form>
</td></tr></table></div>
<?php
}	//UNASSIGNED PART END

	// find total # of rows in the table
	If ($idlist=="0"){
	$numrows=$obj->tableCount_condition($idGroup."_subscribers", "WHERE idGroup=$idGroup $confSQL");

	}
elseif ($idlist=="-1") {
	//NON-ASSIGNED:
	$numrows=$obj->tableCount_condition($idGroup."_subscribers", "WHERE NOT EXISTS (SELECT idEmail FROM ".$idGroup."_listRecipients WHERE ".$idGroup."_listRecipients.idEmail=".$idGroup."_subscribers.idEmail) $confSQL");
}
	else {  // BY LIST
	$countSQL="SELECT count(idList) from ".$idGroup."_listRecipients inner join ".$idGroup."_subscribers on ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail WHERE ".$idGroup."_listRecipients.idGroup=$idGroup AND idList=$idlist $confSQL";
	//echo $oS;
	$numrows=$obj->get_rows($countSQL);
	}
$maxPage = ceil($numrows/$rowsPerPage);
$urlPaging ="$self?confirmed=$pconfirmed&sort=$sort&idList=$idlist&records=$rowsPerPage";
$range=10;
?>
<div align="right" style="margin-right:7px"><span class="menu"><?php echo $numrows;?></span></div>
<?php include('nav.php'); ?>
<br><br>
<?php
	$redirectUrl="listNewsletterSubscribers.php?sort=".$sort."&confirmed=".$pconfirmed."&idList=".$idlist."&records=".$rowsPerPage;
	include('doSubscribersList.php');
?>
<br><br>
<?php include('nav.php');
}

$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>