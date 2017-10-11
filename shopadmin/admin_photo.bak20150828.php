<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_GET['id']!="" && $_GET['Action']=='Modi'){
	$id = intval($_GET['id']);
	$Action_value = "Update";
	$Action_say  = "修改相簿" ;//修改問題類別
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}photo` where id=".intval($id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$name            =  $Result['name'];
		$classid         =  $Result['classid'];
		$film            =  $Result['film'];
		$type            =  $Result['type'];
		$content         =  $Result['content'];
		$language        =  $Result['language'];
		$pubdate         =  $Result['pubdate'];
		$videoid         =  $Result['videoid'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增相簿"; ///添加問題類別
	$status  = 1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>內容管理--&gt;相簿管理</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.name.value)){
			form1.name.focus();
			alert('請填寫名稱');  //请选择問題類別名稱			
			return;
		}
	
		form1.submit();
	}
	function checkform1(){

		if (chkblank(form1.name.value)){
			form1.name.focus();
			alert('請填寫名稱');  //请选择問題類別名稱			
			return;
		}
		form1.submit();
	}

</SCRIPT>

<div id="contain_out"><? include "Order_state.php";?>
  <FORM name=form1 action='admin_photo_save.php' method=post >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="id" value="<?php echo $id?>">
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
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>內容管理--&gt;相簿管理</SPAN></TD>
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
                            <a href="admin_photo_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                                  </TR></TBODY></TABLE><!--BUTTON_END-->
                            
                            </td>
                          
                          </TR></TBODY></TABLE>
                    
                    </TD></TR></TBODY></TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
                      <TABLE class=allborder cellSpacing=0 cellPadding=3 width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>
                          
                          <TR>
                            <TD noWrap align=right>類別：</TD>
                            <TD height="25" colspan="2" align=left noWrap>
                              <?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}photoclass` ","classid","id","name",intval($classid));  ?>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>類型：</TD>
                            <TD height="25" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','type',$type,$Add=array("影音","圖片"))?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>日期：</TD>
                            <TD height="25" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','pubdate',$pubdate," id=pubdate   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?></TD>
                            </TR>
                          <TR>
                            <TD width="18%" align=right noWrap>名稱：</TD>
                            <TD height="12" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','name',$name,"      maxLength=100 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD width="18%" align=right noWrap>影音ID：</TD>
                            <TD height="13" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','videoid',$videoid,"      maxLength=20 size=20 ")?></TD>
                          </TR>
                          
                          <TR id="filmshow">
                            <TD noWrap align=right>影音位置：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('textarea','film',$film,"cols=80 rows=8      ")?> *圖片類型不用填寫</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>說明：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('textarea','content',$content,"cols=80 rows=8      ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          </TBODY>
                  </TABLE>
                     </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>                      
</BODY>
</HTML>
