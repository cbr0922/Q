<?php
include_once "Check_Admin.php";
include RootDocumentShare."/Smtp.config.php";
include "../language/".$INFO['IS']."/Mail_Pack.php";

if ($mail_type=='smtp'){
	$Display="style=\"DISPLAY: display\"";
}else{
	$Display="style=\"DISPLAY: none\"" ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Email_Man]?>--&gt;<?php echo $JsMenu[Email_Set]?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
	function checkform(){
		form1.submit();
	}	
    function view(obj,v){
	 if (v=='1'){
	   obj.style.display="";
	 }
	 if (v=='2'){
	   obj.style.display="none";
	 }
	}
</SCRIPT>
<SCRIPT language=JavaScript>
<!--

function doMailEnc(enc)
{
    if(enc=="utf8")
    {
        mail1.style.display = "none";
        mail2.style.display = "none";
        mail3.style.display = "none";
        mail4.style.display = "none";
    }
    else
    {
        mail1.style.display = "block";
        mail2.style.display = "block";
        mail3.style.display = "block";
        mail4.style.display = "block";
    }
}
//-->
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_create_smtpconfig.php' method=post >    
  <input type="hidden" name="Action" value="Modi">
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
                    <TD class=p12black><SPAN class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Email_Man]?>--&gt;<?php echo $JsMenu[Email_Set]?></SPAN></TD>
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
                            <a href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                                    <a  href="javascript:checkform();">
                                      <IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE><!--BUTTON_END-->
                            </TD>
                          </TR>
                        </TBODY>
                      </TABLE>
                    </TD>
                  
                  </TR>
                </TBODY>
              </TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD width="14%" align=right noWrap>&nbsp;</TD>
                            <TD colspan="3" align=right noWrap>&nbsp;</TD></TR>
                          <TR>
                            <TD align=left noWrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Mail_Pack[MailSendMode]?>：<!--邮件发送模式： -->
                              <input type="radio" id="mail_type" name="mail_type" value="smtp" <?php if ($mail_type ==  "smtp") { echo " checked ";}?> onClick="view(mail_typedisplay,1)" />SMTP 
                              <input name="mail_type" id="mail_type" type="radio" value="mailto" <?php if ($mail_type ==  "mailto") { echo " checked ";}?>  onclick="view(mail_typedisplay,2)"/>
                              MAIL FUNCTION					  </TD>
                            <TD width="11%" align=left noWrap>					      </TD>
                            <TD width="37%" align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD height="22" align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                          </TR>
                          
                          <TR id="mail_typedisplay" <?php echo $Display;?>>
                            <TD colspan="5" align=center noWrap>
                              <!---------------------------------------------------smtp----------------->
                              <table width="90%" border="0" class="allborder">
                                <TR>
                                  <TD width="25%" height="40" align=right noWrap><?php echo $Mail_Pack[SMTPServerName]?>：<!--SMTP服务器名称： --></TD>
                                  <TD width="25%" align=left noWrap><input name="smtpserver" type="text" id="smtpserver" value="<?php echo $smtpserver?>" class="easyui-tooltip" title="<?php echo $Mail_Pack[WhatIsSMTPServerName] ?>"></TD>
                                  <TD width="3%" align=right noWrap><?php echo $Mail_Pack[SMTPEmailUName]?>：<!--SMTP邮箱帐户名称： --></TD>
                                  <TD width="30%" align=left noWrap><input name="smtpuser" type="text"   id="smtpuser" value="<?php echo $smtpuser?>" class="easyui-tooltip" title="<?php echo $Mail_Pack[WhatIsSMTPEmailUName] ?>"></TD>
                                </TR>
                                <TR>
                                  <TD noWrap align=right><?php echo $Mail_Pack[SMTPEmailUPass]?>：<!--SMTP邮箱帐户密码： --></TD>
                                  <TD align=left noWrap><input name="smtppass" type="password" id="smtppass" value="<?php echo $smtppass?>" class="easyui-tooltip" title="<?php echo $Mail_Pack[WhatIsSMTPEmailUPass] ?>"></TD>
                                  <TD align=right noWrap><?php echo $Mail_Pack[SMTPEmail]?>：<!--SMTP邮箱： --></TD>
                                  <TD align=left noWrap><input name="smtpusermail" type="text" id="smtpusermail" value="<?php echo $smtpusermail?>"></TD>
                                </TR>
                                <TR>
                                  <TD noWrap align=right><?php echo $Mail_Pack[SMTPServerPort]?>：<!--SMTP服务器端口：--></TD>
                                  <TD align=left noWrap><input name="smtpserverport" type="text"   id="smtpserverport"  value="<?php echo $smtpserverport = empty($smtpserverport) ? "25" : $smtpserverport ; ?>" class="easyui-tooltip" title="<?php echo $Mail_Pack[WhatIsSMTPServerPort] ?>"></TD>
                                  <TD align=right noWrap><?php echo $Mail_Pack[SMTPServerIfCheck]?>：<!--SMTP发信是否需要身份验证：--></TD>
                                  <TD align=left noWrap><input name="auth" type="radio" value="true"  <?php if ( $auth==true ) { echo " checked ";}?>> <?php echo $Basic_Command['Yes'];?> <input type="radio" name="auth" value="false" <?php if ( $auth==false ) { echo " checked ";}?>> <?php echo $Basic_Command['No']?></TD>
                                </TR>
                                <TR>
                                  <TD noWrap align=right>安全模式：</TD>
                                  <TD align=left noWrap><input name="ssl" type="radio" value="ssl"  <?php if ( $ssl=="ssl" ) { echo " checked ";}?> />
                                    SSL
                                      <input type="radio" name="ssl" value="tls" <?php if ( $ssl=="tls" ) { echo " checked ";}?> />
TLS
<input type="radio" name="ssl" value="" <?php if ( $ssl=="" ) { echo " checked ";}?> />
                                  無</TD>
                                  <TD align=right noWrap>&nbsp;</TD>
                                  <TD align=left noWrap>&nbsp;</TD>
                                </TR>
                                <TR>
                                  <TD height="52" align=right noWrap>&nbsp;</TD>
                                  <TD colspan="3" align=left noWrap><i class="icon-warning-sign red_big"></i><span class="red_small"> 使用Gmail信箱服務時，SMTP請設定ssl://smtp.gmail.com，並設定安全模式為SSL。</span></TD>
                                </TR>
                              </table>
                              <!---------------------------------------------------------------------->					  </TD>
                          </TR>
                          <TR>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD height="22" align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                          </TR>
                          
                          <TR>
                            <TD colspan="2" align=left noWrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Mail_Pack[SMTPCodeMode]?>：<!--邮件编码方式：--><SELECT  name='sel_mail_enc' class="inputstyle" onchange=doMailEnc(this.value)>
                              <OPTION value='UTF-8'  <?php if ($sel_mail_enc=='UTF-8')  { echo " selected=\"selected\" "; }?>><?php echo $Mail_Pack[Utf8Code];?><!--国际化编码(utf-8)--></OPTION>
                              </SELECT>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                              
                              <?php echo $Mail_Pack[MdEmailCode]?>：<!--目的邮件本地编码：-->
                              <SELECT name='sel_mail_tar_lang' class="inputstyle">
                                <OPTION value='UTF-8'   <?php if ($sel_mail_tar_lang=='UTF-8')   { echo " selected=\"selected\" " ; } ?>><?php echo $Mail_Pack[Utf8Code];?><!--国际化编码--></OPTION>					  
                                <OPTION value='GB2312'   <?php if ($sel_mail_tar_lang=='GB2312')   { echo " selected=\"selected\" " ; } ?>><?php echo $ARR_LANGNAME['zh']?><!--简体中文--></OPTION>
                                <OPTION value='BIG5' <?php if ($sel_mail_tar_lang=='BIG5') { echo " selected=\"selected\" " ; } ?>><?php echo $ARR_LANGNAME['big5']?><!--繁体中文--></OPTION>
                              </SELECT>						</TD>
                            <TD align=right valign="middle" id=mail1 style="DISPLAY: <?php echo $diplay?>" <?php if ($sel_mail_enc=='utf8' || empty($sel_mail_enc)) { $diplay=""; }?>></TD>
                            <TD valign="middle" id=mail2 style="DISPLAY: <?php echo $diplay?>" <?php if ($sel_mail_enc=='utf8' || empty($sel_mail_enc)) { $diplay=""; }?> >				  </TD>
                          </TR>
                          <TR>
                            <TD colspan="2" align=left noWrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Mail_Pack[SMTPEmailFormat]?>：<!--邮件格式--><select name="mailtype" class="inputstyle" id="mailtype">
                              <option value="HTML" <?php if ( $mailtype=='HTML' ) { echo " selected=\"selected\" ";}?> >HTML</option>
                              <option value="TXT"  <?php if ( $mailtype=='TXT' )  { echo " selected=\"selected\" ";}?>>TXT</option>
                              </select></TD>
                            <TD id=mail10 <?php if ($sel_mail_enc=='utf8' || empty($sel_mail_enc)) { $diplay="none"; }?> style="DISPLAY:NONE <?php //echo $diplay?>" align=right>
                              <?php echo $Mail_Pack[BkEmailCode]?><!--后台邮件本地编码：-->
                            <TD id=mail11 style="DISPLAY:NONE <?php //echo $diplay?>" <?php if ($sel_mail_enc=='utf8' || empty($sel_mail_enc)) { $diplay="none"; }?> >
                                
                                <SELECT name='sel_mail_lang' class="inputstyle">
                                  <OPTION value='zh'   <?php if ($sel_mail_lang=='zh')   { echo " selected=\"selected\" " ; } ?>><?php echo $ARR_LANGNAME['zh']?><!--简体中文--></OPTION>
                                  <OPTION value='big5' <?php if ($sel_mail_lang=='big5') { echo " selected=\"selected\" " ; } ?>><?php echo $ARR_LANGNAME['big5']?><!--繁体中文--></OPTION>
                                  <OPTION value='en'   <?php if ($sel_mail_lang=='en')   { echo " selected=\"selected\" " ; } ?>><?php echo $ARR_LANGNAME['en']?><!--英文--></OPTION>
                              </SELECT>                </TR>
                          <TR>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="3" align=left noWrap>&nbsp;</TD>
                          </TR>
                    </TBODY></TABLE>
  </FORM>
</div>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
