<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td align="left">您真的要進行退審操作嗎？</td>
  </tr>
  <tr>
    <td align="left">退審原因：</td>
  </tr>
  <tr>
    <td align="left"><textarea name="desc" id="desc" cols="37" rows="5"></textarea></td>
  </tr>
  <tr>
    <td align="center"><input type="button" name="button" id="button" value="確定送出" onClick="submitOrderAct();">
    <input type="button" name="button2" id="button2" value="取消" onclick="closeWin();"></td>
  </tr>
</table>
<script language="javascript">
function submitOrderAct(){
	//alert("aaaaaaaaaaaa");
	$('#nocheckreason').attr("value",$('#desc').attr("value"));
	//alert("aaaaaaaaaaaa");
	$('#adminForm').attr("action","admin_goods_save.php");
	//alert("aaaaaaaaaaaa");
	$('#act').attr("value","nocheck");
	//alert($('#adminForm').attr("action"));
	$('#adminForm').submit();
}
</script>
