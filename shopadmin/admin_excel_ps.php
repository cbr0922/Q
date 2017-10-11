<?php
include_once "Check_Admin.php";
/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_Operater.php";
if ($_POST['Action']=='mod' ) {
	
	$db_string = $DB->compile_db_update_string( array (
	'ps'                => md5(trim($_POST['userpass'])),
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}excel_ps` SET $db_string";
	$Result_Insert = $DB->query($Sql);
	if ($Result_Insert)
	{
		
		$FUNCTIONS->setLog("修改導出資料密碼");
		$FUNCTIONS->header_location('desktop.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man]?>--&gt;修改導出資料密碼</title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="include/functions.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		
		
		if (form1.userpass.value.length<6){
			alert('<?php echo $Admin_Operater[PleaseInputMaxPass]?>'); //请输入６位以上密码！
			form1.userpass.value = ""
			form1.userpass2.value = ""
			form1.userpass.focus();
			return;			
		}
		if (form1.userpass.value == ""){
			alert('<?php echo $Admin_Operater[PleaseInputPw]?>');  //请输入密码！
			form1.userpass.value = ""
			form1.userpass2.value = ""
			form1.userpass.focus();
			return;			
		}
		
		if (form1.userpass2.value != form1.userpass.value){
			alert('<?php echo $Admin_Operater[TowPwDiff]?>'); //"两次输入的密码不一致！"
			form1.userpass.value = ""
			form1.userpass2.value = ""
			form1.userpass.focus();
			return;			
		}
		
	 
		form1.submit();
	}
	
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_excel_ps.php' method='post' >
  <input type="hidden" name="Action" value="mod">
   <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man]?>--&gt;修改導出資料密碼</SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                  <TR>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                                    <TD vAlign=bottom noWrap class="link_buttom">
                            <a href="admin_operater_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                              
                            </TD></TR></TBODY></TABLE>
                      
                      </TD></TR></TBODY></TABLE></TD></TR>
          </TBODY>
        </TABLE>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Operater[Password] ;//密码?>：</TD>
                            <TD width="38%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('password','userpass','',"   class='box_no_pic1'  onmouseover=\"this.className='box_no_pic2'\" onMouseOut=\"this.className='box_no_pic1'\"      maxLength=40 size=40 ")?></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Operater[ConfigPass];//确认密码?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('password','userpass2','',"   class='box_no_pic1'  onmouseover=\"this.className='box_no_pic2'\" onMouseOut=\"this.className='box_no_pic1'\"       maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>