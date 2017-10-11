<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$Comment_id  = $FUNCTIONS->Value_Manage($_GET['comment_id'],$_POST['comment_id'],'back','');
/**
 * 这里是当供应商进入的时候。只能修改自己产品的评论资料。
 */
$Sql         = "select gc.* from `{$INFO[DBPrefix]}message` gc  where comment_id=".intval($Comment_id)."  ".$Provider_string." limit 0,1 ";
//$Query       = $DB->query("select * from good_comment where comment_id=".intval($Comment_id)." limit 0,1");
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);

if ($Num>0){
	$Result    = $DB->fetch_array($Query);
	$CoIdate   = $Result['comment_idate'];
	$username   = $Result['username'];
	$email   = $Result['email'];
	$CoContent = nl2br($Result['comment_content']);
	$CoAnswer  = $Result['comment_answer'];
	$tel  = $Result['tel'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>留言管理--&gt;<?php echo $Admin_Product[Comment_System] ;//回复评论?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
	function checkform(){
		document.form1.action="admin_message_save.php";
		document.form1.submit();
	}	
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='' method=post  >
  <input type="hidden" name="Action" value="Update">
  <INPUT type=hidden  name='comment_id' value="<?php echo $Comment_id?>"> 
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange>留言管理--&gt;回覆留言    </SPAN></TD>
                  </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_comment_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"   border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE><TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    </TR>
                  
                  </TBODY>
                </TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2 
                  width="100%" bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="12%">&nbsp;</TD>
                            <TD>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right> 姓名：</TD>
                            <TD><?php echo  $username?> </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>郵件地址：</TD>
                            <TD><?php echo  $email?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>聯繫電話：</TD>
                            <TD><?php echo  $tel?>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="12%"> 留言時間：</TD>
                            <TD><?php echo date("Y-m-d H: i a ",$CoIdate)?>
                              </TD></TR>
                          <TR>
                            <TD noWrap align=right width="12%"> 留言內容：</TD>
                            <TD><?php echo $CoContent;?></TD></TR>
                          <TR>
                            <TD align=right valign="top" noWrap>回覆留言：</TD>
                            <TD>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>&nbsp;</TD>
                            <TD align=left valign="top" noWrap>
                              <TABLE width="91%" height="18"  border=0 align="left" cellPadding=0 cellSpacing=0>
                                <TBODY>
                                  <TR>
                                    <TD valign="top">
                                      <?php
						$CKEditor = new CKEditor();
						$CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						$CKEditor->config['width'] = 700;
						$CKEditor->config['height'] = 200;
						echo $code = $CKEditor->editor("FCKeditor1", $CoAnswer);
					   ?>
                                      </TD>
                                    </TR>
                                  </TBODY>
                                </TABLE></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="12%">&nbsp;</TD>
                            <TD>&nbsp; 
            </TD></TR></TBODY></TABLE>
</FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
