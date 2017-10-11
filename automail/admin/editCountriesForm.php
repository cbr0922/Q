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
		<td valign="top">
			<font class="title"><?php echo EDITCOUNTRIESFORM_1; ?></font>
			<br><?php echo EDITCOUNTRIESFORM_9; ?>: <a href=# onclick="openConfirmBox('editCountriesExec.php?action=deleteAll','<?php echo fixJSstring(EDITCOUNTRIESFORM_10)?><br><?php echo fixJSstring(GENERIC_2)?>');return false;"><img src="./images/delete.png" width="18" height="18" border=0></a>
		</td>
		<td align="right" valign="top"><img src="./images/globe.png" alt="" width="65" height="58"></td>
	</tr>
	<tr>
		<td colspan=2>
			<br>
			<form name="newcountry" method="get" action="editCountriesExec.php">
				<?php echo EDITCOUNTRIESFORM_2; ?>:
				<INPUT class="fieldbox11" TYPE="Text" Name="countryCode" VALUE="" SIZE="5">
				<?php echo EDITCOUNTRIESFORM_5; ?>:
				<INPUT class="fieldbox11" TYPE="Text" Name="countryName" VALUE="" SIZE="40">
				<INPUT TYPE="hidden" Name="action" VALUE="add">
				<input class=submit type="Submit" name="add" value="<?php echo EDITCOUNTRIESFORM_3; ?>">
			</form>
		</td>
	</tr>
</table>
<?php
$mySQL="SELECT countryCode, countryName FROM ".$idGroup."_countries WHERE idGroup=$idGroup ORDER by countryName asc";
$result	= $obj->query($mySQL);
$rows 	= $obj->num_rows($result);

if (!$rows){
  echo '<br><img src="./images/warning.png">&nbsp;'.EDITCOUNTRIESFORM_11.'<br>';
} else {
?>
<br />
<table width=650 style="BORDER-RIGHT: #999999 0px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 0px solid; BORDER-BOTTOM: #999999 1px solid" cellpadding=2 cellspacing=0>
    <tr>
        <td width="10" class="leftCorner"></td>
        <TD width="130" class="headerCell" style="BORDER-left: #999999 0px solid;"><?php echo EDITCOUNTRIESFORM_2; ?></td>
        <TD width="300" class="headerCell" style="BORDER-left: #999999 0px solid;"><?php echo EDITCOUNTRIESFORM_5; ?></td>
        <TD width="200" class="headerCell" style="BORDER-left: #999999 0px solid;" align=center><?php echo EDITCOUNTRIESFORM_6; ?></td>
        <td width="10" class="rightCorner"></td>
    </TR>
	<?php
	while ($row = $obj->fetch_array($result)){
	    $poldcountrycode = $row['countryCode'];?>
    <TR>
        <td  width=650 colspan=5 style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #6666CC 0px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 0px solid">
             <table  border=0 cellpadding=0 cellspacing=0>
           <form name="editCountries" method="get" action="editCountriesExec.php">
            <tr>
              <TD  valign=top align=left width="150" height="21" style="padding-top: 8px">&nbsp;&nbsp;&nbsp;
                  <input class="fieldbox11" type="text" name="countryCode" value="<?php echo strForInput($row['countryCode'])?>" size="5">
              </TD>
              <TD  valign=top width="290" height="21" style="padding-top: 8px">
                  <INPUT class="fieldbox11" TYPE="Text" Name="countryName" VALUE="<?php  echo strForInput($row['countryName'])?>" SIZE="40">
                  <input type="hidden" name="oldcountrycode" value="<?php echo strForInput($poldcountrycode)?>">
              </TD>
              <TD  valign=top width="210" align=center>
                  <input type="hidden" name="action" value="update">
                  <input class="submit" type="Submit" name="update" value="<?php echo EDITCOUNTRIESFORM_7; ?>">&nbsp;&nbsp;&nbsp;
                  <input class="submit" type="button" name="delete" onclick="openConfirmBox('editCountriesExec.php?oldcountrycode=<?php echo $poldcountrycode?>&action=delete','<?php echo fixJSstring(GENERIC_2)?>');return false;" value="<?php echo EDITCOUNTRIESFORM_8; ?>">
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
