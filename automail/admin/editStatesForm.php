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
			<font class="title"><?php echo EDITSTATESFORM_1; ?></font>
			<br><?php echo EDITSTATESFORM_9; ?>: <a href=# onclick="openConfirmBox('editStatesExec.php?action=deleteAll','<?php echo fixJSstring(EDITSTATESFORM_10)?><br><?php echo fixJSstring(GENERIC_2)?>');return false;"><img src="./images/delete.png" width="18" height="18" border=0></a>
		</td>
		<td align="right" valign="top"><img src="./images/globe2.png" alt="" width="65" height="58"></td>
	</tr>
	<tr>
		<td colspan=2>
			<br>
			<form name="newstate" method="get" action="editStatesExec.php">
				<?php echo EDITSTATESFORM_2; ?>:
				<INPUT class="fieldbox11" TYPE="Text" Name="stateCode" VALUE="" SIZE="5">
				<?php echo EDITSTATESFORM_5; ?>:
				<INPUT class="fieldbox11" TYPE="Text" Name="stateName" VALUE="" SIZE="40">
				<INPUT TYPE="hidden" Name="action" VALUE="add">
				<input class="submit" type="Submit" name="add" value="<?php echo EDITSTATESFORM_3; ?>">
			</form>
		</td>
	</tr>
</table>
<?php
$mySQL="SELECT stateCode, stateName FROM ".$idGroup."_states ORDER by stateName asc";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
if (!$rows){
  echo '<br><img src="./images/warning.png">&nbsp;'.EDITSTATESFORM_11."<br>";
} else {
?>
<br />

<table width=650 style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 1px solid" cellpadding=2 cellspacing=0>
    <tr>
        <td width="10" class="leftCorner"></td>
        <TD width="130" class="headerCell" style="BORDER-left: #999999 0px solid;"><?php echo EDITSTATESFORM_2; ?></td>
        <TD width="300" class="headerCell" style="BORDER-left: #999999 0px solid;"><?php echo EDITSTATESFORM_5; ?></td>
        <TD width="200" class="headerCell" style="BORDER-left: #999999 0px solid;" align=center><?php echo EDITSTATESFORM_6; ?></td>
        <td width="10" class="rightCorner"></td>
    </TR>
	<?php
	while ($row = $obj->fetch_array($result)){
	    $poldstatecode = $row['stateCode'];?>
        <td  width=650 colspan=5 style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 0px solid">
             <table  border=0 cellpadding=0 cellspacing=0>
           <form name="editStates" method="get" action="editStatesExec.php">
            <tr>
			 <TD  valign=top align=left width="150" height="21" style="padding-top: 8px">&nbsp;&nbsp;&nbsp;
		        <INPUT class="fieldbox11" TYPE="Text" Name="stateCode" VALUE="<?php echo strForInput($row['stateCode']) ?>" SIZE="5">
             </TD>
              <TD  valign=top width="290" height="21" style="padding-top: 8px">
                <INPUT class="fieldbox11" TYPE="Text" Name="stateName" VALUE="<?php echo strForInput($row['stateName']) ?>" SIZE="40">
				<input type="hidden" name="oldstatecode" value="<?php echo strForInput($poldstatecode)?>">
			</TD>
			 <TD  valign=top width="210" align=center >
				<input type="hidden" name="action" value="update">
				<input class="submit" type="Submit" name="update" value="<?php echo EDITSTATESFORM_7; ?>">&nbsp;&nbsp;&nbsp;
				<input class="submit" type="button" name="delete" value="<?php echo EDITSTATESFORM_8; ?>" onclick="openConfirmBox('editStatesExec.php?oldstatecode=<?php echo $poldstatecode?>&action=delete','<?php echo fixJSstring(GENERIC_2)?>');return false;">
			</TD>
		</TR>
           </form>
           </table>
        </td>
    </TR>
 		<?php
 		}
 		?>
	</table>

<?php
}
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>
