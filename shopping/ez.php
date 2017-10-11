<?php
require_once( '../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
$key = $_POST['webPara'];
$cart =&$_SESSION['cart'];

if ($cart->get_key != $key){
	$cart->resetCart();
}
$cart->cvsname = trim($_POST['stName']);
$cart->cvsnum = $_POST['stCode'];
$cart->cvscate = $_POST['stCate'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="<{ $Site_Url }>/js/jquery/jquery-1.4.2.min.js"></script>
<script language="javascript">
//alert(window.opener.document.getElementById("showez").innerHTML);
window.opener.document.getElementById("showez").innerHTML="超商取貨門市：<?php echo $_POST['stName'];?>";
window.close();
//$("#showez", window.parent.document).innerHTML("超商取貨門市：<?php echo $_POST['stName'];?>");
</script>
</head>
</html>