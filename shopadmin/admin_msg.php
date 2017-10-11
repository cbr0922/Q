<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Message_Pack.php";
if ($_GET['sendtype_id']!=""){
	$sendtype_id = intval($_GET['sendtype_id']);
	$Action_value = "Update";

	$Query = $DB->query("select sendtitle,sendstatus,sendcontent,sendtype from `{$INFO[DBPrefix]}sendmsg` where sendtype_id=".intval($sendtype_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$sendtitle      =  $Result['sendtitle'];
		$sendstatus     =  $Result['sendstatus'];
		$sendcontent    =  $Result['sendcontent'];
		$sendtype       =  trim($Result['sendtype']);

	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}
switch ($sendtype){
	case "user_register":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_II];
		break;
	case "user_chpass":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_III];
		break;
	case "user_passback":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_III];
		break;
	case "user_bbsback":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_IV];
		break;
	case "user_commentback":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_V];
		break;
	case "order_create":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_VI];
		break;
	case "pay_success":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_VII];
		break;
	case "order_check":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_VIII];
		break;
	case "order_confirm":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_VIII];
		break;
	case "order_cancel":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_VIII];
		break;
	case "order_delivery":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_IX];
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>設置-->簡訊管理-->簡訊內容</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
	
	   if (chkblank(form1.sendtitle.value) || form1.sendtitle.value.length>100){
			form1.sendtitle.focus();
			alert('<?php echo $Mail_Pack[PleaseInputMailTitle]."!"?>');
			return;
		}
		form1.action="admin_msg_save.php";
		form1.submit();
    }
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_msg_save.php' method=post >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="sendtype_id" value="<?php echo $sendtype_id?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;簡訊管理--&gt;<?php echo $JsMenu[Email_ModiSend]?></SPAN></TD>
                  </TR>
                </TBODY>
              </TABLE>
              
            </TD>
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
                            <a href="admin_msglist.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
                          </TD></TR></TBODY></TABLE>
                    
                  </TD></TR></TBODY></TABLE>
            </TD>
            
            
          </TR>
        </TBODY>
    </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>&nbsp;</TD>
                            <TD width="45%" align="left" valign="top" noWrap>&nbsp;</TD>
                            <TD width="37%" valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>&nbsp;</TD>
                            <TD align="left" valign="top" noWrap><?php echo $sendtype_title?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>&nbsp;</TD>
                            <TD align="left" valign="top" noWrap><?php echo $sendtype_txt?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD width="18%" align="right" valign="top" noWrap> <?php echo $Mail_Pack[MailType];?>：</TD>
                            <TD align="left" valign="top" noWrap><?php echo $FUNCTIONS->sendtype($sendtype);?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap> <?php echo $Mail_Pack[MailTitle]?>：</TD>
                            <TD align="left" valign="middle" noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','sendtitle',$sendtitle,"  maxLength=50 size=50  ")?>
                              </TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap><?php echo $Basic_Command['IfCloseOrOpen']?>：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('radio','sendstatus',intval($sendstatus),$add=array($Basic_Command['Open'] ,$Basic_Command['Close']))?>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap> <?php echo $Mail_Pack[MailContent]?>：</TD>
                            <TD colspan="2" align="left" valign="top" noWrap>
                              <textarea name="FCKeditor1" cols="70" rows="10" id="FCKeditor1"><?=$sendcontent?></textarea>
                              (注意：字數不宜過多)<br />
                              <br /></TD>
                            </TR>
                        </TBODY></TABLE></TD>
                </TR></TBODY></TABLE></TD>
        </TR></TBODY></TABLE>
  </FORM>
</div>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
