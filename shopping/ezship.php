<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
<?php
echo "<form name=\"form_ezpay\" id=\"form_ezpay\" method=\"post\" ACTION=\"" . $_POST['payurl'] . "\">
		  <input type=\"hidden\" name=\"su_id\" value=\"" . $_POST['su_id'] . "\">
		   <input type=\"hidden\" name=\"order_id\" value=\"" . $_POST['order_id'] . "\"> 
		    <input type=\"hidden\" name=\"rtn_url\" value=\"" .$_POST['rtn_url'] . "\">
			<input type=\"hidden\" name=\"rv_name\" value=\"" . ($_POST['rv_name']) . "\">
			<input type=\"hidden\" name=\"rv_email\" value=\"" . $_POST['rv_email'] . "\"> 
			<input type=\"hidden\" name=\"rv_mobile\" value=\"" . $_POST['rv_mobile'] . "\">
			<input type=\"hidden\" name=\"web_para\" value=\"" . $_POST['web_para'] . "\"> 
			<input type=\"hidden\" name=\"order_status\" value=\"A02\">
			<input type=\"hidden\" name=\"order_type\" value=\"" . $_POST['order_type'] . "\">
			<input type=\"hidden\" name=\"order_amount\" value=\"" . $_POST['order_amount'] . "\">
			<input type=\"\" name=\"st_code\" value=\"" . $_POST['st_code'] . "\">
		   <input type=submit value='選擇取貨超商' style='display:none'>
		</form>"
?>
<script>
document.getElementById("form_ezpay").submit();
</script>
</body>
</html>
