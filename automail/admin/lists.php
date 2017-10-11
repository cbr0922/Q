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
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
include('header.php');
showMessageBox();
$self 		 	= 	$_SERVER['PHP_SELF'];
(isset($_GET['page']))?$page = $_GET['page']:$page = 1;
$rowsPerPage 	= 50;	//for the listing
$offset 		= ($page - 1) * $rowsPerPage;
$dateFormatsorter=dateSorter($groupDateTimeFormat);
?>
<script type="text/javascript" language="javascript">
function addRemoveCopy(action) {
	var action;
	var url = "listOperations.php";
	if (action=="add" && $("#idListOption1").val()=="0") 	{
		hideAllMessages();
		$("#checkselections").show();
		$("#checkselections").html('<img alt="" src="./images/warning.png">&nbsp;<?php echo fixJSstring(LISTS_35);?>');
		$("#idListOption1").focus();
		return false;
	}
	else if (action=="remove" && $("#idListOption2").val()=="0") 	{
		hideAllMessages();
		$("#checkselections").show();
		$("#checkselections").html('<img alt="" src="./images/warning.png">&nbsp;<?php echo fixJSstring(LISTS_35);?>');
 		$("#idListOption2").focus();
		return false;
	}
	else if (action=="copy" && ($("#idListOption3").val()=="0" || $("#idListOption4").val()=="0")) 	{
		hideAllMessages();
		$("#checkselections").show();
		$("#checkselections").html('<img alt="" src="./images/warning.png">&nbsp;<?php echo fixJSstring(LISTS_38);?>');
		$("#idListOption3").focus();
		return false;
	}
	else if (action=="copy" &&  ($("#idListOption3").val()==$("#idListOption4").val())) 	{
		hideAllMessages();
		$("#checkselections").show();
		$("#checkselections").html('<img alt="" src="./images/warning.png">&nbsp;<?php echo fixJSstring(LISTS_28);?>');
		$("#idListOption4").focus();
		return false;
	}
	else {
		switch(action)
		{
		case "add":
			url=url+'?idListOption1='+$("#idListOption1").val()+'&action='+action;
			var updatedLDiv = "L"+$("#idListOption1").val();
			break;
		case "remove":
			url=url+'?idListOption2='+$("#idListOption2").val()+'&action='+action;
			var updatedLDiv = "L"+$("#idListOption2").val();
			break;
		case "copy":
			url=url+'?idListOption3='+$("#idListOption3").val()+'&idListOption4='+$("#idListOption4").val()+'&action='+action;
			var updatedLDiv = "L"+$("#idListOption4").val();
			break;
		default:
			alert('Ooops');
			return false;
		}
		$.get(url)
			.done(function(data,status) {
				showResponse(data);
				 })
			.fail(function(data, status) {showException(status); });
		

		hideAllMessages();
		$(updatedLDiv).html('<img alt="" src="./images/waitSmall.gif">');
        	hideAllButtons();
        	$("#indicator").show();
	}
	function showResponse(data) {	//document.write(data);
		var response = data.split("#");
		var part_one = response[0];
		var part_two = response[1];
		if (data=="sessionexpired") 	{
			alert('<?php echo fixJSstring(GENERIC_3);?>');
			document.location.href="index.php";
		}
		else {
			$("#indicator").hide();
			showAllButtons();
			switch(action) {
			case "add":
				$("#addOKMessage").show();	
				$("#addOKMessage").effect( "highlight",{color:"#ffff99"}, 5000 );
				
				break;
			case "remove":
				$("#removeOKMessage").show();
				$("#removeOKMessage").effect( "highlight",{color:"#ffff99"}, 5000 );
				break;
			case "copy":
				if (part_two!="" && action=="copy") {
					hideAllMessages();
					$("#checkselections").show();
					$("#checkselections").html('<img alt="" src="./images/warning.png">&nbsp;'+part_two);
					$("#idListOption3").focus();
					$("#copyOKMessage").hide();
					return false;
				}
				else {
					
					$("#copyOKMessage").effect( "highlight",{color:"#ffff99"}, 5000 );
				}
				break;
			default:
				alert('Ooops');
				return false;
			}
			$('#'+updatedLDiv).html(part_one);
		   	$('#'+updatedLDiv).effect( "highlight",{color:"#ffff99"}, 5000 );

		}
	}	//end showResponse function
}	//main function ends

function showException(status) {
	alert('<?php echo fixJSstring(GENERIC_8)?>');
	$("#indicator").hide();
	hideAllMessages();
	showAllButtons();
}
function showAllButtons() {	//showAllButtons();
	$("#addToListButton, #removeFromListButton, #copyListsButton").show();
}
function hideAllButtons() {
	$("#addToListButton, #removeFromListButton, #copyListsButton").hide();
}
function hideAllMessages() {
	$("#removeOKMessage, #addOKMessage, #copyOKMessage, #checkselections, #listnameerror").hide();
}

function switchPublic(idList,p) {
	hideAllMessages();
	var updatedLPDiv= "LP"+idList;
   	showSmallLoader(updatedLPDiv);
	var url = 'updatePublic.php?idList='+idList+'&p='+p;
	$.ajax({ 
		type: "GET", 
		url: url, 
   		cache : false, 
		success: function(response, status) { 
			if (response=="sessionexpired") {
				alert('<?php echo fixJSstring(GENERIC_3);?>');
				document.location.href="index.php";
			}
			else {
				$('#'+updatedLPDiv).html(response);
				$('#'+updatedLPDiv).show("highlight", { duration: 5000}); 
				openmessageBox('<?php echo fixJSstring(UPDATEPUBLIC_2)?>');
			}          
		}, 
	    error: function (xhr, status) {  $('#'+updatedLPDiv).html(status);
	    		alert('Please refresh the current page.');
	    }    
    }); 
}
</script>
<table border="0" width="960px" cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo LISTS_20; ?></span>
		</td>
		<td align=right>
			<Img alt="" src="./images/lists.png" width="50" height="63">
		</td>
	</tr>
</table>

<a href="#" class="cross" onclick="show_hide_div('addNewListForm','cross1'); hideAllMessages(); return false;"><span id="cross1">[+]</span>&nbsp;<?php echo LISTS_2; ?></a>
<br><br>
<div id="addNewListForm" style="display:none; width:600px; border: #DCDCDC 1px solid; margin-bottom:15px; padding:5px; -moz-border-radius: 10px; border-radius:10px; BACKGROUND:#f4f4f9;">
	<?php	include('newListForm.php'); ?>
</div>
<?php
$limitSQL 		= " LIMIT $offset, $rowsPerPage";
$mySQL="SELECT idList, listName, listDescription, isPublic, lastDateMailed, idGroup FROM ".$idGroup."_lists WHERE ".$idGroup."_lists.idGroup=$idGroup order by idList desc ".$limitSQL;
$mySQL_all="SELECT idList, listName, listDescription, isPublic, lastDateMailed, idGroup FROM ".$idGroup."_lists WHERE ".$idGroup."_lists.idGroup=$idGroup order by idList desc ";
$result_all	= $obj->query($mySQL_all);
//	Getting #subs for each list with a left join is slow.
//	$mySQL="SELECT lists.idList, listName, listDescription, isPublic, lastDateMailed, lists.idGroup, count(listRecipients.idList) as psum FROM lists left join listRecipients on listRecipients.idList=lists.idList WHERE lists.idGroup=$idGroup  GROUP BY idList, listName, listDescription, isPublic, lastDateMailed order by idList desc ".$limitSQL;
//echo $mySQL;
$result	= $obj->query($mySQL);
   $rows 	= $obj->num_rows($result);
if (!$rows){
		echo "<tr><td colspan=2><img alt='' src='./images/warning.png'>"." ".LISTS_21."</td></tr></table>";
}
else {
?>

<a href="#" class="cross" onclick="show_hide_div('listmenutable','cross2'); hideAllMessages(); return false;"><span id="cross2">[+]</span>&nbsp;<?php echo LISTS_3; ?></a>
<br><br>
<div id="listmenutable"  style="display:none; width:900px; border: #DCDCDC 1px solid; padding:10px; -moz-border-radius: 10px; border-radius:10px; BACKGROUND:#f4f4f9;">
	<table border="0" width="100%" cellpadding="2">
		<tr>
			<td valign="middle">
				<?php echo LISTS_11; ?>:
			</td>
			<td valign="middle">
			  <SELECT name="idList" class="select" id="idListOption1">
			  <OPTION value="0"><?php echo LISTS_12; ?></OPTION>
			  <?php
				while ($row = $obj->fetch_array($result_all)){
			  ?>
			  <option value="<?php echo $row['idList'];?>"><?php echo $row['idList'];?>. <?php echo $row['listName'];?>
			  <?php }
                            $obj->data_seek($result_all,0);
			  ?>
			  </OPTION>
			  </SELECT>&nbsp;<input id="addToListButton" type="submit" name="Submit" class="submit" onclick="addRemoveCopy('add')" value="<?php echo LISTS_13; ?>">
			</td>
			<td width=16 rowspan=4  valign="top" align=right>
				<a href="#" onclick="show_hide_div('listmenutable','cross2'); hideAllMessages(); return false;"><img alt="<?php echo LISTS_27; ?>" border="0" src="./images/closeRound.gif" title="<?php echo LISTS_27; ?>"></a>
			</td>
		</tr>
		<tr>
			<td valign="middle">
				<?php echo LISTS_14; ?>:
			</td>
			<td valign="middle">
				<SELECT name="idList" class="select"  id="idListOption2">
			  <OPTION value="0"><?php echo LISTS_12; ?></OPTION>
			  <?php
				while ($row = $obj->fetch_array($result_all)){
			  ?>
			  <option value="<?php echo $row['idList'];?>"><?php echo $row['idList'];?>. <?php echo $row['listName'];?>
			  <?php } $obj->data_seek($result_all,0);?>
			  </OPTION>
				</SELECT>&nbsp;<input id="removeFromListButton" type="submit" class="submit" onclick="addRemoveCopy('remove')" name="Submit" value="<?php echo LISTS_15; ?>">
			</td>
		</tr>
		<tr>
			<td valign="middle">
				<?php echo LISTS_16; ?>:
			</td>
			<td valign="middle">
				<SELECT name="idlist1" class="select"  id="idListOption3">
			  <OPTION value="0"><?php echo LISTS_12; ?></OPTION>
			  <?php
				while ($row = $obj->fetch_array($result_all)){
			  ?>
			  <option value="<?php echo $row['idList'];?>"><?php echo $row['idList'];?>. <?php echo $row['listName'];?>
			  <?php } $obj->data_seek($result_all,0);?>
			  </OPTION>
		</SELECT>
			</td>
		</tr>
		<tr>
			<td valign="middle">
				<?php echo LISTS_17; ?>:&nbsp;
			</td>
			<td valign="middle">
				<SELECT name="idlist2" class="select"  id="idListOption4">
			  <OPTION value="0"><?php echo LISTS_12; ?></OPTION>
			  <?php
				while ($row = $obj->fetch_array($result_all)){
			  ?>
			  <option value="<?php echo $row['idList'];?>"><?php echo $row['idList'];?>. <?php echo $row['listName'];?>
			  <?php } $obj->data_seek($result_all,0);?>
				</OPTION>
				</SELECT>&nbsp;<input id="copyListsButton" type="submit" name="Submit" class="submit" onclick="addRemoveCopy('copy')" value=" <?php echo LISTS_18; ?> ">
			</td>
		</tr>
		<tr>
			<td colspan=3  valign="top" align=center style="PADDING-TOP:5px;">
			  <span id="indicator" style="display:none;"><img alt="" src="./images/waitBig.gif">&nbsp;<?php echo GENERIC_4; ?></span>
			  <span id="addOKMessage" class="okmessage" style="display:none;"><img alt="" src="./images/doneOk.png">&nbsp;<?php echo LISTS_36; ?></span>
			  <span id="removeOKMessage" class="okmessage" style="display:none;"><img alt="" src="./images/doneOk.png">&nbsp;<?php echo LISTS_37; ?></span>
			  <span id="copyOKMessage" class="okmessage" style="display:none;"><img alt="" src="./images/doneOk.png">&nbsp;<?php echo LISTS_40; ?></span>
			  <span id="checkselections" class="errormessage" style="display:none;"></span>
			</td>
		</tr>
	</table>
</div>

	<?php
	$countSQL="SELECT count(idList) from ".$idGroup."_lists where idGroup=$idGroup";
	$numrows=$obj->get_rows($countSQL);
	$maxPage = ceil($numrows/$rowsPerPage);
	$urlPaging ="$self?";
	$range=10;	//the pages before/after current page
	?>
<table  width="950"  cellpadding="4" cellspacing="0">
	<tr>
		<td valign="top">
		<?php
			include('nav.php');
		?>
		</td>
		<td width=10 valign="top"><?php echo '<span class=menu>'.$numrows.'</span>';?></td>
	</tr>
</table>
<br>
<table class="sortable" width="950"  cellpadding="0" cellspacing="0" style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 0px solid">
	<thead>
	<tr>
		<td class="nosort leftCorner"></td>
		<td class="number sortfirstdesc headerCell" style="BORDER-left: #999999 0px solid;">ID</td>
		<td class="text headerCell" width=150><?php echo LISTS_4; ?></td>
		<td class="text headerCell"><?php echo LISTS_5; ?></td>
		<td class="<?php echo $dateFormatsorter?> headerCell"><?php echo LISTS_1; ?></td>
		<td class="number headerCell" width=90><?php echo LISTS_6; ?></td>
		<td class="nosort headerCell" align=center><?php echo LISTS_24; ?></td>
		<td class="nosort headerCell" align=center><?php echo LISTS_7.' / '.LISTS_41;?></td>
		<td class="nosort headerCell" align=center><?php echo LISTS_8; ?></td>
		<td class="nosort headerCell" align=center><?php echo LISTS_25; ?></td>
		<td class="nosort rightCorner"></td>
	</tr>
	</thead>
	<tbody id="listsTable">
		<?php
		while($row = $obj->fetch_array($result)) {
			?>
	<tr valign=top onMouseOver="this.bgColor='#f4f4f9';" onMouseOut="this.bgColor='#ffffff';">
		<td class="listingCell"></td>
		<td  class="listingCell" style="BORDER-left:0px;"><?php echo $row['idList'];?></td>
		<td class="listingCell"><?php echo $row['listName'];?></td>
		<td class="listingCell"><?php if ($row['listDescription']) {echo str_replace(chr(10), "<br>", $row['listDescription']);}?></td>
		<td class="listingCell"><?php echo addOffset($row['lastDateMailed'], $pTimeOffsetFromServer, $groupDateTimeFormat);?></td>
		<td class="listingCell" align="right" style="padding-right:15px;">
			<span id="L<?php echo $row['idList'];?>">
			<?php
			$mySQL5="SELECT count(idEmail) as subs FROM ".$idGroup."_listRecipients WHERE idGroup=$idGroup AND idList=".$row['idList'];
			$psum = $obj->get_rows($mySQL5);
			echo $psum;?></span>&nbsp;<a href="listNewsletterSubscribers.php?idList=<?php echo $row['idList'];?>"><img alt="" src="./images/subSm.png" width="28" height="19" border="0" title="<?php echo LISTS_19; ?>"></a>
		</td>
		<td class="listingCell" align=center>
        
				<div id="LP<?php echo $row['idList'];?>" style="cursor:hand">
			<?php if ($row['isPublic']==-1) { ?>
				<a href="#" onclick="switchPublic(<?php echo $row['idList']?>,0);return false;"><img alt="" src="./images/public.png" border="0" width="15" height="15" title="<?php echo LISTS_23; ?>"></a>
			<?php } else { ?>
				<a href="#" onclick="switchPublic(<?php echo $row['idList']?>,-1);return false;"><img alt="" src="./images/notpublic.png" border="0" width="15" height="15" title="<?php echo LISTS_22; ?>"></a>
			<?php } ?>
			</div>
            
		</td>
		<td class="listingCell" align=center>
        <?php
        if($row['idList']!=1){
		?>
            <a href="#" onclick="openConfirmBox('delete.php?idList=<?php echo $row['idList'];?>','<?php echo fixJSstring(CONFIRM_6);?><br><?php echo fixJSstring(GENERIC_2);?>');return false;"><img alt="" src="./images/delete.png" width="17" height="17" border="0" title="<?php echo LISTS_9; ?>"></a>&nbsp;
            <a href="#" onclick="openConfirmBox('delete.php?idDropList=<?php echo $row['idList'];?>','<?php echo fixJSstring(CONFIRM_16);?><br><?php echo fixJSstring(GENERIC_2);?>');return false;"><img alt="" src="./images/drop.png" width="17" height="17" border="0" title="<?php echo LISTS_42; ?>"></a>
            <?php
		}
			?>
        </td>
		<td class="listingCell" align=center><a href='modifyList.php?idList=<?php echo $row['idList'];?>'><img alt="" src="images/edit.png" width="22" height="22" border="0" title="<?php echo LISTS_10; ?>"></a></td>
		<td class="listingCell" align=center style="BORDER-right:0px;"><a href='listTraffic.php?idList=<?php echo $row['idList'];?>'><img alt="" src="./images/pie.png" width="21" height="20" border="0" title="<?php echo LISTS_26; ?>"></a></td>
		<td class="listingCell" style="BORDER-left: #c9c9c9 0px solid; BORDER-right: #c9c9c9 1px solid;"></td>
	</tr>
			<?php
			}
		?>
</tbody>
</table>

<?php
	echo '<br>';
	include('nav.php');
}

include('footer.php');
$obj->free_result($result);
$obj->closeDb();
?>