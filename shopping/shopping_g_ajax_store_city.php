<?php
include("../configs.inc.php");
include("global.php");
@header("Content-type: text/html; charset=utf-8");
if ($_GET['province']=='')
	$_GET['province']="臺北市";
?>
<select id="scity" name="scity">
<?php
/*
$t_Sql      = "select distinct city from `{$INFO[DBPrefix]}store` where province='" . $_GET['province'] . "' ";
$t_Query    = $DB->query($t_Sql);
$t_Rs=$DB->fetch_array($t_Query);
*/
$a_Sql      = "select distinct city from `{$INFO[DBPrefix]}store` where province='" . $_GET['province'] . "' ";
$a_Query    = $DB->query($a_Sql);
while ($a_Rs=$DB->fetch_array($a_Query)) {
?>
<option value="<?php echo $a_Rs['city']?>" <?php if($_GET['city']==$a_Rs['city'])echo "selected";?>><?php echo $a_Rs['city']?></option>
<?php
}
?>
</select>