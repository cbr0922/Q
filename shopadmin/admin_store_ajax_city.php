<?php
include_once "Check_Admin.php";
if ($_GET['province']=='')
	$_GET['province']="臺北市";
?>
<select id="city" name="city">
<?php
$t_Sql      = "select * from `{$INFO[DBPrefix]}area` where areaname='" . $_GET['province'] . "' and top_id=1";
$t_Query    = $DB->query($t_Sql);
$t_Rs=$DB->fetch_array($t_Query);

$a_Sql      = "select * from `{$INFO[DBPrefix]}area` where top_id='" . $t_Rs['area_id'] . "'";
$a_Query    = $DB->query($a_Sql);
while ($a_Rs=$DB->fetch_array($a_Query)) {
?>
<option value="<?php echo $a_Rs['areaname']?>" <?php if($_GET['city']==$a_Rs['areaname'])echo "selected";?>><?php echo $a_Rs['areaname']?></option>
<?php
}
?>
</select>