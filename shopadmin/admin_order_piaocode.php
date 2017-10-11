<?php
include_once "Check_Admin.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
@header("Content-type: text/html; charset=utf-8");
if($_POST['act']=="save"){
	if($_POST['piaocode']!=""){
		$orderClass->setOrderState(1,3,intval($_POST['order_id']),'',1);
	}
	$Sql = "update `{$INFO[DBPrefix]}order_table` set piaocode='" . $_POST['piaocode'] . "',sendtime='" . $_POST['sendtime'] . "',sendname='" . $_POST['sendname'] . "' where order_id='" . intval($_POST['order_id']) . "'";
	$DB->query($Sql);
	$FUNCTIONS->setLog("填寫物流單號資料");
	echo "1";
	exit;
}
$Sql = "select ot.* from  `{$INFO[DBPrefix]}order_table` ot where ot.order_id=".intval($_GET['order_id'])." ";
$Query    = $DB->query($Sql);
$Rs=$DB->fetch_array($Query);
?>
<form action="admin_order_piaocode.php" method="post" name="piaocodeform" id="piaocodeform">
<input type="hidden" name="act" id="act" value="save" />
<input type="hidden" name="order_id" id="order_id" value="<?php echo $_GET['order_id'];?>" />
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" align="left">填寫物流單號資料 </td>
  </tr>
  <tr>
    <td width="31%" align="left">物流單號：</td>
    <td width="69%" align="left"><?php echo $FUNCTIONS->Input_Box('text','piaocode',$Rs['piaocode'],"    maxLength=40 size=10 ")?></td>
  </tr>
  <tr>
    <td width="31%" align="left">物流單位：</td>
    <td width="69%" align="left"><?php echo $FUNCTIONS->Input_Box('text','sendname',$Rs['sendname'],"    maxLength=40 size=10 ")?></td>
  </tr>
  <tr>
    <td align="left">發貨日期：</td>
    <td align="left">
    <INPUT   id="sendtime" size=10 value="<?php echo $Rs['sendtime']?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name="sendtime" />
    </td>
  </tr>
  <tr>
    <td height="36" align="center">&nbsp;</td>
    <td height="36" align="left"><input type="button" name="button" id="button" value="確定送出" onclick="submitInvoiceAct();" />
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
function submitInvoiceAct(){
	$('#piaocodeform').ajaxSubmit(options1);
}
</script>
