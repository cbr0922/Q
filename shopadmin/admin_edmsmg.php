<?php
include "Check_Admin.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
if ($_GET['publication_id']!="" && $_GET['Action']=='Modi'){
	$publication_id = intval($_GET['publication_id']);
	$Action_value = "Update";
	$Action_say  = "新增簡訊";
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}edmsmg` where publication_id=".intval($publication_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$publication_title        =  $Result['publication_title'];
		$publication_start_time   =  $Result['publication_start_time'];
		$publication_end_time     =  $Result['publication_end_time'];
		$publication_content      =  $Result['publication_content'];
		$publication_alreadyread  =  $Result['publication_alreadyread'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$Action_say   = "編輯簡訊";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.publication_title.value) || form1.publication_title.value.length>100 || form1.publication_title.value.length<4){
			form1.publication_title.focus();
			alert('<?php echo $Mail_Pack[PleaseInputEmailBaoName]?>'); //请输入会刊标题
			return;
		}
		form1.submit();
	}
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_edmsmg_save.php' method='post' >    
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="publication_id" value="<?php echo $publication_id?>">
  <input type="hidden" name="publication_start_time" value="<?php echo $publication_start_time?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Action_say?>
                      </SPAN></TD>
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
                            <a href="admin_edmsmg_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                    
                  </TD></TR></TBODY></TABLE>
            </TD>
          </TR>
        </TBODY>
    </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>
                          
                          <TR>
                            <TD noWrap align=right> 簡訊標題： </TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','publication_title',$publication_title,"      maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Mail_Pack[LastModiTime];//最后修改日期?>：</TD>
                            <TD width="38%" align=left noWrap><?php echo  $Last_moditime = $publication_start_time!="" ? date("Y-m-d H: i a",$publication_start_time) : date("Y-m-d H: ia",time()) ;?></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Mail_Pack[LastSendTime];//最后发送日期?>：</TD>
                            <TD align=left noWrap><?php echo  $Last_sendtime = $publication_end_time!="" ? date("Y-m-d H: i a",$publication_end_time) : "" ;?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                          </TR>
                          
                          <TR>
                            <TD noWrap align=right> 簡訊內容： </TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" rowspan="2" align=left valign="top" noWrap>
                              <textarea name="FCKeditor1" id="FCKeditsor1" cols="45" rows="10"><?php echo $publication_content;?></textarea>
                              不要超過60個字符，空格包含在內</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" rowspan="3" align=left valign="top" noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                          </TR>
                    </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
