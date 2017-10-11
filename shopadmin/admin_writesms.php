<?php
include_once "Check_Admin.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;會員管理--&gt;編寫簡訊</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>


	
    
	
<? //include "Order_state.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.domain.value)){
			form1.domain.focus();
			alert('請填寫域名');  //请选择問題類別名稱			
			return;
		}
	
		form1.submit();
	}
	function checkform1(){

		if (chkblank(form1.domain.value)){
			form1.domain.focus();
			alert('請填寫域名');  //请选择問題類別名稱			
			return;
		}
		form1.submit();
	}

</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_writesms_send.php' method=post >
  <input type="hidden" name="uid" value="<?php echo $_GET['uid']?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>客戶關係--&gt;會員管理--&gt;編寫簡訊</SPAN></TD>
                  </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              
              
              </TD></TR>
          </TBODY>
        </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top height=262>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff height=300>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2 width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>
                          
                          <!--TR>
                            <TD noWrap align=right>主題：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','title',$title,"       size=50 ")?></TD>
                            </TR-->
                          <TR>
                            <TD noWrap align=right>內容：</TD>
                            <TD colspan="2" align=left noWrap>
                            <div  class="editorwidth">
							<textarea name="body" id="redactor" cols="60" rows="10" ><?php echo $Nbody;?></textarea>
                            </div>
							</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap><input type="submit" name="button" id="button" value="發送"></TD>
                            </TR>
                          </TBODY>
                  </TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
                     </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
