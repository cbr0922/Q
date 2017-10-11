<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj 		= new db_class();
$groupName 	=	$obj->getSetting("groupName", $idGroup);
include('header.php');
?>
<script type="text/javascript" language="javascript">
function checkEntries() {
	if ($('#listName').blank()) {
		$("#listnameerror").show();
		$('#listName').clear();
		return false;
	}
}
</script>
<?php

showMessageBox();
(isset($_GET['idList']))?$idlist = $_GET['idList']:$idlist=0;

if($idlist){
	$mySQL="SELECT * from ".$idGroup."_lists WHERE idList=$idlist";
	$result	= $obj->query($mySQL);
	$row = $obj->fetch_array($result);
	$listname 	   	= $row['listName'];
	$listdescription= $row['listDescription'];
	$isPublic 	= $row['isPublic'];
	$pageTitle	= LISTS_32;
	$loadicon		= 'editlist';
}
else {
	@$listname 	   	= "";
	@$listdescription= "";
	@$isPublic 	= 0;
	$pageTitle	= LISTS_2;
	$loadicon		= 'addlist';
}

?>
<table border="0" width="960px" cellpadding="2" cellspacing="0">
	<tr>
		<td>
			<span class="title"><?php echo $pageTitle; ?></span>
		</td>
		<td align=right>
			<Img alt="" src="./images/<?php echo $loadicon?>.png" width="50" height="63">
		</td>
	</tr>
</table>
<br>
<form action="listExec.php" method="post" id="editList" name="editList[]" onsubmit="return checkEntries();">
<input type="hidden" name="idList" value="<?php echo $idlist?>">

<table  cellpadding=3>
	<tr>
		<td><?php echo LISTS_4; ?>:</td>
		<td><input id="listName" name="listName" type="text" value="<?php echo strForInput($listname)?>" class=fieldbox11 size=60></INPUT></td>
	<tr>
		<td valign=top><?php echo LISTS_5; ?>:</td>
		<td><TEXTAREA id="listDescription" name="listDescription" COLS="70" ROWS=5 class=textarea><?php echo $listdescription?></TEXTAREA></td>
	</tr>
	<tr>
		<td><?php echo LISTS_24; ?>:</td>
		<td><input type="checkbox" id="isPublic" name="isPublic" value="-1" <?php if($isPublic==-1) { echo ' checked';}?>></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type="submit" name="Submit" class="submit" value=" <?php echo LISTS_33; ?> "></td>
	</tr>
		<td colspan=2 align=center><span id="listnameerror" class="errormessage" style="display:none;"><img src="./images/warning.png">&nbsp;<?php echo fixJSstring(LISTS_29)?></span></td>
	</tr>
</table>
</form>

<?php
 	include("footer.php");
?>