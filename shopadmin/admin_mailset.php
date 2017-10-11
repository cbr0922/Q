<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Mail_Pack.php";
if ($_GET['sendtype_id']!=""){
	$sendtype_id = intval($_GET['sendtype_id']);
	$Action_value = "Update";

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}sendtype` where sendtype_id=".intval($sendtype_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$sendtitle      =  $Result['sendtitle'];
		$sendstatus     =  $Result['sendstatus'];
		$sendcontent    =  $Result['sendcontent'];
		$sendtype       =  trim($Result['sendtype']);
		$sendname       =  trim($Result['sendname']);

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
	case "order_back":
	case "order_change":
		$sendtype_title = $Mail_Pack[MailSetTxt_I];
		$sendtype_txt   = $Mail_Pack[MailSetTxt_IX];
		break;
	case "sendticket":
		$sendtype_title = "派發優惠券";
		$sendtype_txt   = "@shopname@ 商店名<br>@ticketname@ 優惠券標題 <br>@username@ 帳號";;
		break;
	case "waitbuy":
		$sendtype_title = "到貨通知";
		$sendtype_txt   = "@shopname@ 商店名<br>@goodsname@ 商品名稱 <br>@username@ 帳號";;
		break;
	case "pointalert":
		$sendtype_title = "積分過期提醒";
		$sendtype_txt   = "@shopname@ 商店名<br>@memberpoint會員積分數 <br>@username@ 帳號";;
		break;
	case "kefureplay":
		$sendtype_title = "客服回覆";
		$sendtype_txt   = "@shopname@ 商店名<br>@k_kefu_con@詢問內容 <br>@k_post_con@ 回覆內容";;
		break;
	case "provideropen":
		$sendtype_title = "供應商開通信";
		$sendtype_txt   = "@provider_name@ 供應商名稱<br>@provider_thenum@帳號 <br>@provider_loginpassword@ 密碼";;
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Email_Man]?>--&gt;<?php echo $JsMenu[Email_ModiSend]?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<script type="text/javascript" src="../Resources/redactor-js-master/lib/jquery-1.9.0.min.js"></script>

	<!-- Redactor is here -->
	<link rel="stylesheet" href="../Resources/redactor-js-master/redactor/redactor.css" />
	<script src="../Resources/redactor-js-master/redactor/redactor.js"></script>
   <!-- Plugin -->
          <script src="/Resources/redactor-js-master/redactor/plugins/source.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/table.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fullscreen.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontsize.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontfamily.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontcolor.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/inlinestyle.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/video.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/properties.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/textdirection.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/imagemanager.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/alignment/alignment.js"></script>
          <link rel="stylesheet" href="../Resources/redactor-js-master/redactor/plugins/alignment/alignment.css" />
    <!--/ Plugin -->
    
	<script type="text/javascript">
	$(document).ready(
		function()
		{
			$('#redactor').redactor({
				imageUpload: '../Resources/redactor-js-master/demo/scripts/image_upload.php',
				imageManagerJson: '../Resources/redactor-js-master/demo/scripts/image_json.php',
				plugins: ['source','imagemanager', 'video','fontsize','fontcolor','alignment','fontfamily','table','textdirection','properties','inlinestyle','fullscreen'],
				imagePosition: true,
                imageResizable: true,
				<?php
				if ($_GET['sendtype_id']!=""){
				?>
				autosave: 'admin_mailset_save.php?act=autosave&sendtype_id=<?php echo $_GET['sendtype_id'];?>',
				callbacks: {
					autosave: function(json)
					{
						 console.log(json);
					}
				}
				<?php
				}
				?>
			});
		}
	);
	</script>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
	
	   if (chkblank(form1.sendtitle.value) || form1.sendtitle.value.length>100){
			form1.sendtitle.focus();
			alert('<?php echo $Mail_Pack[PleaseInputMailTitle]."!"?>');
			return;
		}
		form1.action="admin_mailset_save.php";
		form1.submit();
    }
</SCRIPT>

<div id="contain_out"><?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_mailset_save.php' method=post >
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
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Email_Man]?>--&gt;<?php echo $JsMenu[Email_ModiSend]?></SPAN></TD>
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
                            <a href="admin_mailset_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
        </TABLE>
<TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>&nbsp;</TD>
                            <TD width="45%" align="left" valign="top" noWrap>&nbsp;</TD>
                            <TD width="37%" valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>&nbsp;</TD>
                            <TD align="left" valign="top" noWrap bgcolor="#FFFFCC"><?php echo $sendtype_title?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>&nbsp;</TD>
                            <TD align="left" valign="top" noWrap bgcolor="#FFFFCC"><?php echo $sendtype_txt?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD width="18%" align="right" valign="top" noWrap> <?php echo $Mail_Pack[MailType];?>：</TD>
                            <TD align="left" valign="top" noWrap><?php echo $sendname;?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap> <?php echo $Mail_Pack[MailTitle]?>：</TD>
                            <TD align="left" valign="middle" noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','sendtitle',$sendtitle,"  maxLength=\"50\" size=\"50\" ")?> <a href="#" class="easyui-tooltip" title="<?php echo $Mail_Pack[ToMailSubjectSendIntro]?>"><img src="images/tip.png" width="16" height="16" border="0"></a>
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
                            <div  class="editorwidth">
                            <textarea name="FCKeditor1" id="redactor" cols="30" rows="10" ><?php echo $sendcontent;?></textarea>
                            </div>
                            </TD>
                            </TR>
                        </TBODY></TABLE>
                        
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
