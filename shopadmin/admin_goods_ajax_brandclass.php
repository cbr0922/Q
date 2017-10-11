<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
$return = "";
$brand_id = $_GET['brand_id'];
$brand_bid = $_GET['brand_bid'];
$Char_class->getBrandClassSelect(0,0,$brand_id,$brand_bid);
?>
<option value="0">請選擇</option>
<?php echo $return;?>