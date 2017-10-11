<?php
include_once "Check_Admin.php";

if($_GET['act']=="add" && $_GET['name']!=''){
  $Sql="INSERT INTO `{$INFO[DBPrefix]}unit` (`name`) VALUES ('$_GET[name]')";
  $DB->query($Sql);

  $Insert_id = $DB->insert_id();

  switch (strlen((string)$Insert_id)) {
    case 1:
      $code="00".$Insert_id;
      break;
    case 2:
      $code="0".$Insert_id;
      break;
    case 3:
      $code=$Insert_id;
      break;
  }

  $Sql="UPDATE  `{$INFO[DBPrefix]}unit` SET `code`='$code' WHERE `id`=$Insert_id";
  $DB->query($Sql);
}

if($_GET['act']=="edit" && $_GET['unit']!='' && $_GET['name']!=''){
  $Sql="UPDATE  `{$INFO[DBPrefix]}unit` SET `name`='$_GET[name]' WHERE `name`='$_GET[unit]'";
  $DB->query($Sql);
}

if($_GET['act']=="del" && $_GET['unit']!=''){
  $Sql="DELETE FROM `{$INFO[DBPrefix]}unit` WHERE `name`='$_GET[unit]'";
  $DB->query($Sql);
}
?>

<SELECT id='unit' name='unit' class="trans-input">
  <OPTION value="">(單位代號)單位名稱</OPTION>
  <?php 
  $Sql = "select * from `{$INFO[DBPrefix]}unit`";
  $Query    = $DB->query($Sql);
  while ($Rs=$DB->fetch_array($Query)) {
    ?>
    <OPTION value=<?php echo $Rs['name'];?> <?php if ($_GET['select']==$Rs['name']) echo " selected ";?>><?php echo "（".$Rs['code']."）".$Rs['name'];?></OPTION>
    <?php
  }
  ?>
</SELECT>

