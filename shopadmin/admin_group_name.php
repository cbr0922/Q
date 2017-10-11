<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
if($_POST['act']=="save"){
	$Sql = "update `{$INFO[DBPrefix]}mail_group` set mgroup_name='" . $_POST['mgroup_name'] . "' where mgroup_id='" . intval($_POST['mgroup_id']) . "'";
	$DB->query($Sql);
	echo "1";
	exit;
}
$Sql = "select * from  `{$INFO[DBPrefix]}mail_group` where mgroup_id=".intval($_GET['mgroup_id'])."";
$Query    = $DB->query($Sql);
$Rs=$DB->fetch_array($Query);
?>
<form action="admin_group_name.php" method="post" name="nameform" id="nameform">
<input type="hidden" name="act" id="act" value="save" />
<input type="hidden" name="mgroup_id" id="mgroup_id" value="<?php echo $_GET['mgroup_id'];?>" />
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" align="left">編輯郵件組名稱 </td>
  </tr>
  <tr>
    <td width="31%" align="left">名稱：</td>
    <td width="69%" align="left"><?php echo $FUNCTIONS->Input_Box('text','mgroup_name',$Rs['mgroup_name'],"    maxLength=40 size=10 ")?></td>
  </tr>
  <tr>
    <td height="36" align="center">&nbsp;</td>
    <td height="36" align="left"><input type="button" name="button" id="button" value="確定送出" onclick="submitNameformAct();" />
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
function submitNameformAct(){
	$('#nameform').ajaxSubmit(options1);
}
</script>
