<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
if($_POST['act']=="Insert"){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}user` WHERE email='".$_POST['email']."'");
	$Num   = $DB->num_rows($Query);
	if($Num > 0){
		$Sql="Insert into `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) select '" . intval($_POST['group_id']) . "',user_id,email FROM `{$INFO[DBPrefix]}user` WHERE email='".$_POST['email']."'";
		$DB->query($Sql);
		echo "1";
	}else {
		echo "0";
	}
	exit;
}
if($_POST['act']=="Update"){
	$Sql = "update `{$INFO[DBPrefix]}mail_group_list` set email='" . $_POST['email'] . "' where group_id='".intval($_POST['group_id'])."' and user_id='".intval($_POST['user_id']) . "'";
	$DB->query($Sql);
	echo "1";
	exit;
}
if($_POST['act']=="Delete"){
	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}mail_group_list` where group_id='".intval($_POST['group_id'])."' and user_id='".intval($Array_bid[$i])."'");
	}
	$FUNCTIONS->header_location("admin_group_mail.php?group_id=".intval($_POST['group_id'])."");
}
if ($_GET['user_id']!="" && $_GET['Action']=='Modi'){
	$Action_value = "Update";
	$Action = "編輯";
	$Sql = "select user_id,email from `{$INFO[DBPrefix]}mail_group_list` where group_id=".intval($_GET['group_id'])." and user_id=".intval($_GET['user_id'])." order by user_id desc";
	$Query    = $DB->query($Sql);
	$Rs=$DB->fetch_array($Query);
}else {
	$Action_value = "Insert";
	$Action = "新增";
}
?>
<form action="admin_group_edit.php" method="post" name="nameform" id="nameform">
<input type="hidden" name="act" id="act" value="<?php echo $Action_value;?>" />
<input type="hidden" name="group_id" id="group_id" value="<?php echo $_GET['group_id'];?>" />
<input type="hidden" name="user_id" id="user_id" value="<?php echo $_GET['user_id'];?>" />
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td colspan="2" align="left">郵件組管理 </td>
  </tr>
	<tr>
		<td align="left">&nbsp;</td>
	</tr>
  <tr>
    <td width="31%" align="left">Email：</td>
    <td width="69%" align="left"><?php echo $FUNCTIONS->Input_Box('text','email',$Rs['email'],"    maxLength=40 size=25 ")?></td>
  </tr>
  <tr>
    <td height="36" align="center">&nbsp;</td>
    <td height="36" align="left"><input type="button" name="button" id="button" value="<?php echo $Action;?>" onclick="submitNameformAct();" />
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
							alert("操作完成");
							window.location.reload();
						}else if (msg=="0") {
							closeWin();
							alert("操作失敗");
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
