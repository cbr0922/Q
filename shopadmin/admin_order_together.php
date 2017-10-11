<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
if($_POST['act']=="save"){
	$Sql = "update `{$INFO[DBPrefix]}order_table` set order_serial_together='" . $_POST['order_serial_together'] . "' where order_id='" . intval($_POST['order_id']) . "'";
	$DB->query($Sql);
	$FUNCTIONS->setLog("合併訂單");
	echo "1";
	exit;
}
$Sql = "select ot.* from  `{$INFO[DBPrefix]}order_table` ot where ot.order_id=".intval($_GET['order_id'])." ";
$Query    = $DB->query($Sql);
$Rs=$DB->fetch_array($Query);
?>
<form action="admin_order_together.php" method="post" name="togetherform" id="togetherform">
<input type="hidden" name="act" id="act" value="save" />
<input type="hidden" name="order_id" id="order_id" value="<?php echo $_GET['order_id'];?>" />
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" align="left">合併訂單</td>
  </tr>
  <tr>
    <td width="31%" align="left">訂單編號：</td>
    <td width="69%" align="left"><?php echo $Rs['order_serial']?></td>
  </tr>
  <tr>
    <td align="left">合併訂單號：</td>
    <td align="left">
    <?php echo $FUNCTIONS->Input_Box('text','order_serial_together',$Rs['order_serial_together'],"    maxLength=40 size=10 ")?>
    </td>
  </tr>
  <tr>
    <td height="36" align="center">&nbsp;</td>
    <td height="36" align="left"><input type="button" name="button" id="button" value="確定送出" onclick="submitTogetherAct();" />
      <input type="button" name="button2" id="button2" value="取消" onclick="closeWin();" /></td>
  </tr>
</table>
</form>
<script language="javascript">
var options1 = {
		success:       function(msg){
			//alert(msg);
						if (msg=="1"){
								closeWin();
								window.location.reload()
							}
						
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
function submitTogetherAct(){
	$('#togetherform').ajaxSubmit(options1);
}
</script>
