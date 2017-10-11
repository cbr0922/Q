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
<table border="0" width="960px" cellpadding="2" cellspacing="0">
	<tr>
		<td valign=top>
			<font class="title"><?php echo EDITSENDFILTERSFORM_1; ?></font>
		</td>
		<td align=right>
			<img src="./images/filters.png" width="60" height="47">
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<a href="#"  class="cross" onclick="show_hide_div('about','cross1');return false;"><span id="cross1">[+]</span>&nbsp;<?php echo EDITSENDFILTERSFORM_2; ?></a>
			<div id="about" style="display:none;"><?php echo EDITSENDFILTERSFORM_9; ?></div>
		</td>
	</tr>
</table>
<?php
$mySQL="SELECT idSendFilter, sendFilterCode, sendFilterDesc FROM ".$idGroup."_sendFilters ORDER by idSendFilter desc";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if (!$rows){
	echo "<br><img src='./images/warning.png'>" ." ".EDITSENDFILTERSFORM_10."<br>";
}
else {
?>
<br><br>
<table width=850  cellpadding=2 cellspacing=0 style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #c9c9c9 1px solid">
	<TR>
		<td class="leftCorner"></td>
		<TD class="headerCell" width="20" style="BORDER-left: #999999 0px solid;"><?php echo EDITSENDFILTERSFORM_4; ?></td>
		<TD class="headerCell" width="570" style="BORDER-left: #999999 0px solid;"><?php echo EDITSENDFILTERSFORM_5; ?></td>
		<TD class="headerCell" width="240" align=right style="BORDER-left: #999999 0px solid;"><?php echo EDITSENDFILTERSFORM_6; ?></td>
		<td class="rightCorner"></td>

	</TR>
	<?php while ($row = $obj->fetch_array($result)){
		$pidSendFilter 	= $row['idSendFilter'];
		$psendFilterCode = $row['sendFilterCode'];
		$psendFilterDesc = $row['sendFilterDesc'];
		?>
	<TR class=listRow>
    	<td colspan=5 style="BORDER-RIGHT: #c9c9c9 1px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #c9c9c9 1px solid; BORDER-BOTTOM: #999999 0px solid">
        <form name="editSendFilters" method="get" action="editSendFiltersExec.php">
            <table border=0 width=850  cellpadding=2 cellspacing=0>
                <tr>
        		    <TD  valign=top width="10">&nbsp;</TD>
                    <TD  valign=top width="30"><?php echo $pidSendFilter?></TD>
    		        <TD  valign=top width="560">
            			<textarea class="filterDesc" rows=2 cols=90 Name="sendFilterDesc"><?php echo $psendFilterDesc?></textarea><br />
            			<textarea class="filterSql" rows=2 cols=90 Name="sendFilterCode"><?php echo $psendFilterCode?></textarea>
            			<input type="hidden" name="idSendFilter" value="<?php echo $pidSendFilter?>">
            		</TD>
            		<TD  valign="top" align="center" width="250">
            			<input class="submit" type="Submit" name="update" value="<?php echo EDITSENDFILTERSFORM_7; ?>">&nbsp;<input class="submit" type="Submit" onclick="openConfirmBox('editSendFiltersExec.php?action=delete&idSendFilter=<?php echo $pidSendFilter?>','<?php echo fixJSstring(CONFIRM_11)?><br><?php echo fixJSstring(GENERIC_2)?>');return false;" name="delete" value="<?php echo EDITSENDFILTERSFORM_8; ?>">
            		</TD>
                </tr>
            </table>
        </form>
        </td>
	</TR>
	<?php
	}
	?>
</table>

<?php
} //we have rows
$obj->closeDb();
include('footer.php');
?>