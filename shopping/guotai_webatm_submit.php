<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>國泰</title>
</head>

<body>
<?php
echo "
		<form name=\"form_gtwpay\" id=\"form_gtwpay\" method=\"get\" ACTION=\"" . $_POST['url'] . "\" >
		  <input type=\"hidden\" name=\"CompanyID\" value=\"" . $_POST['CompanyID'] . "\">
		  <input type=\"hidden\" name=\"orderNoGenDate\" value=\"" . $_POST['orderNoGenDate'] . "\">
		  <input type=\"hidden\" name=\"PtrAcno\" value=\"" . $_POST['PtrAcno'] . "\">
		  <input type=\"hidden\" name=\"ItemNo\" value=\"" .($_POST['ItemNo']) . "\">
		  <input type=\"hidden\" name=\"PurQuantity\" value=\"" . $_POST['PurQuantity'] . "\">
		  <input type=\"hidden\" name=\"amount\" value=\"" . $_POST['amount'] . "\">
		  <input type=\"hidden\" name=\"MerchantKey\" value=\"" .($_POST['MerchantKey']) . "\">
		</form>
		<script language=\"javascript\">
			alert('請稍後，轉國泰世華系統頁面');
			setTimeout(\"document.getElementById('form_gtwpay').submit()\",2000);
		</script>
		  ";
?>
</body>
</html>