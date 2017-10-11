<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
if($_POST['op']=="check"){
	$Sql      = "select * from `{$INFO[DBPrefix]}excel_ps` where ps='" . md5(trim($_POST['userpassword'])) . "' ";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if($Num>0)
		echo 1;
	else
		echo 0;
	exit;
}
?>
<form action="admin_excel_check.php" id="psdform" name="psdform" method="post">
<input type="hidden" name="op" value="check" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">請輸入密碼：      <input type="password" name="userpassword" id="userpassword" /></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><input type="button" name="button" id="button" value="確認送出" onClick="submitPsd();">
    <input type="button" name="button2" id="button2" value="取消" onclick="closeWin();"></td>
  </tr>
</table>
</form>
<script language="javascript">
var options1 = {
		success:       function(msg){
			//alert(msg);
						if (msg=="1"){
								closeWin();
								<?php echo $_GET['act']."(0);";?>
							}else{
							alert("密碼錯誤");	
							}
						
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
function submitPsd(){
	//$("#psdform").submit();
	 $("#psdform").ajaxSubmit(options1);
}
</script>
