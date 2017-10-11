<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj = new db_class();
$groupName 	=	$obj->getSetting("groupName", $idGroup);
include('header.php');
showMessageBox();
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
isset($_GET['sort'])?$sort = $_GET['sort']:$sort ='';
isset($_GET['formfilter'])?$pformfilter = $_GET['formfilter']:$pformfilter ='';
$self 		 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
$rowsPerPage 	= 50;	//for the listing
$offset 		= ($page - 1) * $rowsPerPage;
$dateFormatsorter=dateSorter($groupDateTimeFormat);
?>

<script type="text/javascript" language="javascript">
function switchPublic(idNewsletter,p) {
	var updatedNPDiv= "NP"+idNewsletter;
	var url = 'updatePublic.php?idNewsletter='+idNewsletter+'&p='+p;
	showSmallLoader(updatedNPDiv);
	$.ajax({ 
		type: "GET", 
		url: url, 
		dataType: "html", 
		cache : false, 
		success: function(response, status) { 
			if (response=="sessionexpired") {
				alert('<?php echo fixJSstring(GENERIC_3);?>');
				document.location.href="index.php";
			}
			else {
				$('#'+updatedNPDiv).html(response);
				openmessageBox('<?php echo fixJSstring(UPDATEPUBLIC_1)?>');
				$('#'+updatedNPDiv).show("highlight", { duration: 5000}); 
			}          
		}, 
	    error: function (xhr, status) {  $('#'+updatedNPDiv).html(status);
	    		alert('Please refresh the current page.');
	    }    
    }); 
}
</script>
<form method="get" action="htmlNewsletters.php" id="formfilter" name="formfilter">
<table width="960px" border="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo ALLNEWSLETTERS_1; ?></span>
			<br><br>
			<a href="sendNewsletterForm.php"><?php echo ADMIN_HEADER_45; ?></a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo ALLNEWSLETTERS_3; ?>
			<select class="select" name="formfilterrecord" onChange="location=document.formfilter.formfilterrecord.options[document.formfilter.formfilterrecord.selectedIndex].value;" value="GO">
			<option value="htmlNewsletters.php?formfilter=0&sort=<?php echo $sort;?>" <?php if ($pformfilter=="0"){echo " selected";}?>><?php echo ALLNEWSLETTERS_4; ?></option>
			<option value="htmlNewsletters.php?formfilter=-1&sort=<?php echo $sort;?>" <?php if ($pformfilter=="-1") {echo " selected";}?>><?php echo ALLNEWSLETTERS_22; ?></option>
			<option value="htmlNewsletters.php?formfilter=-2&sort=<?php echo $sort;?>" <?php if ($pformfilter=="-2") { echo " selected";}?>><?php echo ALLNEWSLETTERS_23; ?></option>
			<option value="htmlNewsletters.php?formfilter=-3&sort=<?php echo $sort;?>" <?php if ($pformfilter=="-3"){ echo " selected";}?>><?php echo ALLNEWSLETTERS_24; ?></option>
			<option value="htmlNewsletters.php?formfilter=-4&sort=<?php echo $sort;?>" <?php if ($pformfilter=="-4") {echo " selected";}?>><?php echo ALLNEWSLETTERS_25; ?></option>
			</select>
		</td>
		<td align="right" valign="top">
			<img src="./images/htmlnewsletter.png" width='60' height='57'>
		</td>
	</tr>

</table>
</form>
<br>
<?php
//get newsletters
//$mySQL="SELECT idNewsletter, attachment, name, sent, DATE_ADD(dateSent, INTERVAL ".$pTimeOffsetFromServer." HOUR) as dateSent, DATE_ADD(dateCreated, INTERVAL ".$pTimeOffsetFromServer." HOUR) as dateCreated, nisPublic createdBy FROM newsletter WHERE html=-1";
$limitSQL 		= " LIMIT $offset, $rowsPerPage";
$mySQL="SELECT idNewsletter, attachments, name, sent, dateSent, dateCreated, isPublic, createdBy FROM ".$idGroup."_newsletters WHERE idGroup=".$idGroup." AND html=-1";

if ($sort=="NASC") {
	$strSQL=" ORDER by name asc";
}
else if ($sort=="NDSC") {
	$strSQL=" ORDER by name desc";
}
else {
	$strSQL=" ORDER by idNewsletter desc";
}

switch ($pformfilter) {
	case -1:
		$strSQL2=" AND sent=-1 ";
  		break;
  	case -2:
		$strSQL2=" AND sent=0 ";
  		break;
 	case -3:
		$strSQL2=" AND isPublic=-1 ";
		break;
	case -4:
		$strSQL2=" AND isPublic=0 ";
		break;
	default:
		$strSQL2="";
}
$mySQL=$mySQL.$strSQL2.$strSQL.$limitSQL;
//echo $mySQL .'<br>';
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if (!$rows){
	echo "<br><img src='./images/warning.png'>" ." ". ALLNEWSLETTERS_17;
}
else {
	$countSQL="SELECT count(*) from ".$idGroup."_newsletters where idGroup=$idGroup AND html=-1 $strSQL2";
	$numrows=$obj->get_rows($countSQL);
	$maxPage = ceil($numrows/$rowsPerPage);
	$urlPaging ="$self?formfilter=$pformfilter&sort=$sort";
	$range=10;	//the pages before/after current page
?>
<div style="float:left;"><?php include('nav.php');?></div><div style="float:right;padding-right:100px;"><?php echo '<span class=menu>'.$numrows.'</span>';?></div>
<div style="clear:both;"></div>
<table class="sortable"  width="940px" cellpadding="0" cellspacing="0" style="margin-bottom:20px;margin-top:20px;BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid">
<thead>
<tr>
	<td class="nosort leftCorner"></td>
	<td class="number sortfirstdesc headerCell" style="BORDER-left:0px;">ID</td>
	<td class="text headerCell"><?php echo ALLNEWSLETTERS_5; ?></td>
	<td  class="<?php echo $dateFormatsorter?> headerCell"><?php echo ALLNEWSLETTERS_20; ?></td>
	<td class="<?php echo $dateFormatsorter?> headerCell"><?php echo ALLNEWSLETTERS_12; ?></td>
	<td class="nosort headerCell" align=center><?php echo ALLNEWSLETTERS_21; ?></td>
	<td class="nosort headerCell" align=center><?php echo ALLNEWSLETTERS_8; ?></td>
	<td class="nosort headerCell" align=center><?php echo ALLNEWSLETTERS_10; ?></td>
	<td class="nosort headerCell" align="right">&nbsp;<img src="./images/cliph.png" width="20" height="15" alt="<?php echo ALLNEWSLETTERS_13; ?>" title="<?php echo ALLNEWSLETTERS_13; ?>"></td>
	<td class="nosort rightCorner"></td>
</tr>
</thead>
<tbody>
<?php
while ($row = $obj->fetch_array($result)){
?>
<tr onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
	<td class="listingCell"></td>
	<td class="listingCell" style="BORDER-left:0px;"><?php echo $row['idNewsletter']?></td>
	<td class="listingCell"width=300><div style="float:left;width:280px;border:#333 0px solid"><?php echo $row['name']?></div><div style="float:right;margin-left:0px"><a href="#" onclick="popUpLayer('<?php echo $row['name']?>','previewHtmlNewsletter.php?idNewsletter=<?php echo $row['idNewsletter']?>',750,500);return false;"><img src="./images/magnifier.png"  width="18" height="18" border=0 title="<?php echo ALLNEWSLETTERS_15; ?>"></a></div></td>
	<td class="listingCell"><?php echo addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat);?></td>
	<td class="listingCell"><?php $row['sent']==-1 ? print addOffset($row['dateSent'], $pTimeOffsetFromServer, $groupDateTimeFormat) : print ALLNEWSLETTERS_19; ?></td>
	<td class="listingCell" align="center">
		<div id="NP<?php echo $row['idNewsletter']?>" style="cursor:hand">
		<?php if ($row['isPublic']==-1) {?>
			<a href=# onclick="switchPublic(<?php echo $row['idNewsletter']?>,0);return false;"><img src="./images/public.png" border="0" width="15" height="15"  title="<?php echo LISTS_23; ?>"></a>
		<?php
		}
		else {?>
			<a href=# onclick="switchPublic(<?php echo $row['idNewsletter']?>,-1);return false;"><img src="./images/notpublic.png" border="0" width="15" height="15" title="<?php echo LISTS_22; ?>"></a>
		<?php
		};?>
		</div>
	</td>
	<td class="listingCell" align="center" valign="bottom"><a href=# onclick="openConfirmBox('delete.php?idNewsletter=<?php echo $row['idNewsletter']?>&redirecturl=html','<?php echo fixJSstring(CONFIRM_5) .'<br>'. fixJSstring(GENERIC_2);?>');return false;"><img src="./images/delete.png"  width="18" height="18" border="0" title="<?php echo ALLNEWSLETTERS_14; ?>"></a></td>
	<td class="listingCell" align="center"><a href="sendNewsletterForm.php?idNewsletterEdit=<?php echo $row['idNewsletter']?>"><img src="./images/openEdit.png" width="20" height="19" border=0 title="<?php echo ALLNEWSLETTERS_16; ?>"></a></td>	
	<td class="listingCell" align="center">
		<?php
		if ($row['attachments']!="") {
		$row['attachments'] = str_ireplace(",", "<BR>", $row['attachments']);
		?>
		<img onmouseover="infoBox('AT<?php echo $row['idNewsletter']?>', '<?php echo fixJSstring(ALLNEWSLETTERS_13);?>', '<?php echo $row['attachments']?>','', '0'); " onmouseout="hide_info_bubble('AT<?php echo $row['idNewsletter']?>','1')" src="./images/clip.png" width="20" height="15"><span style="display: none;" id="AT<?php echo $row['idNewsletter']?>"></span>
		<?php
		}
		else {
			echo '&nbsp;';
			}
		?>
	</td>
	<td class="listingCell" style="BORDER-left:0px;BORDER-right: #c9c9c9 1px solid;"></td>
</tr>
<?php } ?>
</tbody>
</table>
<div><?php include('nav.php');?></div>
<?php
}
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>