<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
?>
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td align="left">您真的要進行這個操作嗎？</td>
  </tr>
  <tr style="display:<?php if(($_GET['state_type']==3&&$_GET['state_value']==1) || ($_GET['state_type']==3&&$_GET['state_value']==8)) echo "block"; else echo"none";?>">
    <td align="left">物流單號<?php echo $FUNCTIONS->Input_Box('text','piaocodes',"","    maxLength=40 size=10 ")?><br />出貨日期<input type="text" name="senddate" id="senddate" /><br />物流單位<input type="text" name="sendnames" id="sendnames" /></td>
  </tr>
  <tr>
    <td align="left">操作原因：</td>
  </tr>
  <tr>
    <td align="left"><textarea name="desc" id="desc" cols="37" rows="5"></textarea></td>
  </tr>
  <tr>
    <td align="center"><input type="button" name="button" id="button" value="確定送出" onClick="return submitOrderAct();">
    <input type="button" name="button2" id="button2" value="取消" onclick="closeWin();"></td>
  </tr>
</table>
<script language="javascript">
function submitOrderAct(){
	<?php
	if(($_GET['state_type']==1&&$_GET['state_value']==3) || ($_GET['state_type']==3&&($_GET['state_value']==4 || $_GET['state_value']==6 || $_GET['state_value']==13 || $_GET['state_value']==14 || $_GET['state_value']==15))){
	?>
	if ($('#desc').attr("value")==""){
		alert("請填寫操作原因");	
		return false;
	}
	<?php
	}
	?>
	//alert("aaaaaaaaaaaa");
	$('#remark').attr("value",$('#desc').attr("value"));
	$('#piaocode').attr("value",$('#piaocodes').attr("value"));
	$('#sendtime').attr("value",$('#senddate').attr("value"));
	$('#sendname').attr("value",$('#sendnames').attr("value"));
	$('#adminForm').attr("action","provider_order_act.php");
	//alert($('#adminForm').attr("action"));
	$('#adminForm').submit();
}
</script>
