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
include('header.php');
showMessageBox();
?>
<script type="text/javascript" language="javascript">
<!--
function validateInput() {
	if ($("#linkUrl").blank())
	{	openAlertBox('<?php echo fixJSstring(EDITTRACKINGLINKSFORM_14);?>','');
		$("#linkUrl").focus();
		return false;
	}
}
-->
</script>
<table width="960px" border="0">
	<tr>
		<td valign="top">
			<span class="title"><?php echo ADMIN_HEADER_55; ?></span>
		</td>
		<td align=right>
			<img src="./images/editlinks.png"  alt="" width="65" height="51">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="#"  class="cross" onclick="show_hide_div('about','cross1');return false;"><span id="cross1">[+]</span>&nbsp;<?php echo EDITTRACKINGLINKSFORM_9; ?></a>
			<div id="about" style="display:none;margin-top:15px;margin-bottom:15px;margin-right:15px;width:500px">
			<?php echo EDITTRACKINGLINKSFORM_10?>&nbsp;&nbsp;<a href="#" onclick="show_hide_div('about2','');return false;"><?php echo EDITTRACKINGLINKSFORM_11?></a>
			<div id="about2" style="display:none;margin-top:15px;margin-bottom:15px;width:500px"><?php echo EDITTRACKINGLINKSFORM_13; ?></div>
			</div>
		</td>
	</tr>
</table>
<br />
<table border="0" width="100%">
	<tr>
		<td>
			<form name="newTrackingLink" method="post" action="editTrackingLinksExec.php" onsubmit="return validateInput(this)">
				<?php echo EDITTRACKINGLINKSFORM_2; ?><INPUT class="fieldbox11" TYPE="Text" id="linkUrl" Name="linkUrl" VALUE="" SIZE="80">
				<input class="submit" type="Submit" name="add" value="<?php echo EDITTRACKINGLINKSFORM_3; ?>">
			</form>
		</td>
	</tr>
</table>
<?php
$mySQL="SELECT idLink, linkUrl FROM ".$idGroup."_links ORDER by idLink desc";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if (!$rows){
	echo "<br><img src='./images/warning.png'>" ." ".EDITTRACKINGLINKSFORM_12."<br>";
}
else {
?>
<br>
<table width=850  cellpadding=2 cellspacing=0 style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #c9c9c9 1px solid">
	<tr>
		<td class="leftCorner"></td>
		<TD class="headerCell" width="20" style="BORDER-left: #999999 0px solid;"><?php echo EDITTRACKINGLINKSFORM_4; ?></td>
		<TD class="headerCell" width="570" style="BORDER-left: #999999 0px solid;"><?php echo EDITTRACKINGLINKSFORM_5; ?></td>
		<TD class="headerCell" width="240" align=center style="BORDER-left: #999999 0px solid;"><?php echo EDITTRACKINGLINKSFORM_6; ?></td>
		<td class="rightCorner"></td>
	</TR>
	<?php while ($row = $obj->fetch_array($result)){
     $pidLink = $row['idLink'];
     $plinkUrl = $row['linkUrl'];
?>

	<TR>
        <td colspan=5 style="BORDER-RIGHT: #c9c9c9 1px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #c9c9c9 1px solid; BORDER-BOTTOM: #999999 0px solid">
            	<form name="editTrackingLinks" method="post" action="editTrackingLinksExec.php">
                    <table border=0 width=850  cellpadding=2 cellspacing=0>
                        <tr>
                    		<TD  valign=top width="10">&nbsp;</TD>
                            <TD  valign=top width="30"><?php echo $pidLink?></TD>
                    		<TD  valign=top width="560" style="padding-top: 10px">
                                <input class="fieldbox11" TYPE="Text" Name="linkUrl" VALUE="<?php echo $plinkUrl?>" SIZE="80">
                    		    <input type="hidden" name="idLink" value="<?php echo $pidLink?>"></TD>
                    		<TD  valign="top" align="center" width="250">
                                <input class="submit" type="Submit" name="update" value="<?php echo EDITTRACKINGLINKSFORM_7; ?>">
                                &nbsp;<input class="submit" type="Submit" name="delete" onclick="openConfirmBox('editTrackingLinksExec.php?action=delete&idLink=<?php echo $pidLink?>','<?php echo fixJSstring(EDITTRACKINGLINKSFORM_15)?><br /><?php echo fixJSstring(GENERIC_2)?>');return false;" value="<?php echo EDITTRACKINGLINKSFORM_8; ?>">
                            </TD>
	                    </TR>
                    </table>
	            </form>
        </td>
    </tr>
	<?php
}	?>
</table>

<?php
} //we have rows
$obj->closeDb();
include('footer.php');
?>
