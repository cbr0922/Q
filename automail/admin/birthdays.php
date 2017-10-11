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
//$rowsPerPage =	$obj->getSetting("groupNumPerPage", $idGroup);
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
$self 		 = 	$_SERVER['PHP_SELF'];
include('header.php');
showMessageBox();

(isset($_GET['sort']))?$sort = $_GET['sort']:$sort="0";
(isset($_GET['confirmed']))?$pconfirmed = $_GET['confirmed']:$pconfirmed="1";
(isset($_GET['idList']))?$idlist = $_GET['idList']:$idlist=0;

(isset($_GET['records']))?$rowsPerPage = $_GET['records']:$rowsPerPage = $obj->getSetting("groupNumPerPage", $idGroup);
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
// counting the offset
$offset = ($page - 1) * $rowsPerPage;

$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$dayNow 	= date("j", strtotime("+$pTimeOffsetFromServer hours"));
$monthNow 	= date("n", strtotime("+$pTimeOffsetFromServer hours"));
?>
<script type="text/javascript" language="javascript">
function reloadPage() {
	document.location.href='<?php echo $self?>?sort='+$("#sort").val()+'&confirmed='+$("#confirmed").val()+'&idList='+$("#idList").val()+'&records='+$("#records").val();
}
</script>

<table border="0" width="960px" cellpadding=2 cellspacing=0>
	<tr>
		<td width="300" valign="top">
			<span class="title"><?php echo LISTNEWSLETTERSUBSCRIBERS_25?></span>
		</td>
		<td valign=bottom>
             <form method="get" name="selectlist" id="selectlist" style="display:block;">
			   <?php ECHO LISTNEWSLETTERSUBSCRIBERS_3;
			   	$SQL="SELECT * FROM ".$idGroup."_lists WHERE idGroup=$idGroup order by idList desc";
				$result	= $obj->query($SQL);?>
				<SELECT class="select" id="idList" name="idList" onChange="reloadPage();">
					<OPTION value="0"><?php echo LISTNEWSLETTERSUBSCRIBERS_4; ?></option>
					<?php while ($row = $obj->fetch_array($result)){?>
					<option value="<?php echo $row['idList'];?>"<?php if ($row['idList']==$idlist) {echo " selected";}?>><?php echo $row['idList'];?>. <?php echo $row['listName'];?></option>
					<?php }?>
				</SELECT>
				</form>

		</td>
		<td valign="top" width="70">
			<div align=right><img src="./images/birthdays.png" width="65" height="51"></div>
		</td>
	</tr>
</table>
<br><br>
<table border="0" width="960px" cellspacing="0" cellpadding="2">
	<tr>
		<td valign="bottom" width="350px">
          <img border=0 src="./images/i2.gif">&nbsp;<a href="#" onclick="show_hide_div('birthdayNews','');return false;"><?php echo LISTNEWSLETTERSUBSCRIBERS_26; ?></a>
		</td>
		<td valign="bottom" width="25%">
			<?php include('changeRecs.php');?>
		</td>
		<td valign=bottom>
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
		<td valign=bottom>
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
		<td colspan=4>
			<div id="birthdayNews" style="display:none;width:500px">
				<ol>
					<li><?php echo LISTNEWSLETTERSUBSCRIBERS_27; ?></li>
					<li><?php echo LISTNEWSLETTERSUBSCRIBERS_28; ?></li>
					<li><?php echo LISTNEWSLETTERSUBSCRIBERS_29; ?></li>
					<li><?php echo LISTNEWSLETTERSUBSCRIBERS_30; ?></li>
					<li><?php echo LISTNEWSLETTERSUBSCRIBERS_31; ?></li>
					<li><?php echo LISTNEWSLETTERSUBSCRIBERS_32; ?></li>
				</ol>
			</div>
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
if (!$idlist) {
	$mySQL='SELECT '.$idGroup.'_subscribers.idEmail, name, lastName, email FROM '.$idGroup.'_subscribers WHERE '.$idGroup.'_subscribers.idGroup='.$idGroup .' AND subBirthDay=\''.$dayNow.'\' AND subBirthMonth=\''.$monthNow.'\'' .$confSQL .$sortSQL .$limitSQL;
}
else {
	$mySQL='SELECT '.$idGroup.'_subscribers.idEmail, name, lastName, email FROM '.$idGroup.'_subscribers LEFT JOIN '.$idGroup.'_listRecipients on '.$idGroup.'_listRecipients.idEmail='.$idGroup.'_subscribers.idEmail WHERE '.$idGroup.'_listRecipients.idGroup='.$idGroup.' AND idList='.$idlist .' AND subBirthDay=\''.$dayNow.'\' AND subBirthMonth=\''.$monthNow.'\'' .$confSQL .$sortSQL .$limitSQL;
}
//echo $mySQL .'<br>';
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);

if (!$rows){
  echo "<img src='images/warning.png'>" ." ".LISTNEWSLETTERSUBSCRIBERS_1;
}
else {
	// find total # of rows in the table
	If (!$idlist){
	$numrows=$obj->tableCount_condition($idGroup."_subscribers", "WHERE idGroup=$idGroup AND subBirthDay='".$dayNow."' AND subBirthMonth='".$monthNow."' $confSQL");
	}
	else {
	$countSQL="SELECT count(idList) from ".$idGroup."_listRecipients inner join ".$idGroup."_subscribers on ".$idGroup."_subscribers.idEmail=".$idGroup."_listRecipients.idEmail WHERE ".$idGroup."_listRecipients.idGroup=$idGroup AND idList=$idlist AND subBirthDay='".$dayNow."' AND subBirthMonth='".$monthNow."' $confSQL";
	$numrows=$obj->get_rows($countSQL);
	}
//total # of pages when paging
$maxPage = ceil($numrows/$rowsPerPage);
$urlPaging ="$self?confirmed=$pconfirmed&sort=$sort&idList=$idlist&records=$rowsPerPage";
$range=10;	//the pages before/after current page
?>
<table width="900" border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td valign=top>
		<?php
			include('nav.php');
		?>
			</td>
			<td width=10 valign="top">
				<font class="menu"><?php echo $numrows;?></font>
			</td>
		</tr>
	</table>

	<br><br>
	<?php
		$redirectUrl="birthdays.php?sort=".$sort."&confirmed=".$pconfirmed."&idList=".$idlist."&records=".$rowsPerPage;
		include('doSubscribersList.php');
	?>
	<br><br>

	<?php
		include('nav.php');
}

$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>