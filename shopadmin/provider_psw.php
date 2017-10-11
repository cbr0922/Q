<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

if ($_POST['Action']=='Modi'){

	$Old_pw =  trim($_POST['old_pwd']);
	$New_pw =  trim($_POST['f_pwd']);

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($_SESSION['sa_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Pw      =  trim($Result['provider_loginpassword']);
	}else{
		$FUNCTIONS->sorry_back("provider_psw.php",$MemberLanguage_Pack[Ydm_bad]); //并不存在您的资料
		exit;
	}

	if ($Pw!=$Old_pw){
		$FUNCTIONS->sorry_back("provider_psw.php",$MemberLanguage_Pack[Twobadpassword]);
		exit;
	}

	$Sql = "update `{$INFO[DBPrefix]}provider` set provider_loginpassword='".$New_pw."'  where provider_id=".intval($_SESSION['sa_id']);
	$Modi_query = $DB->query($Sql);
	$FUNCTIONS->sorry_back("provider_desktop.php",$MemberLanguage_Pack[PassWordModiIsPass_say]);

}
$DB->my_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<TITLE><?php echo $JsMenu[Change_Pass]?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" 
background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
<SCRIPT language=javascript src="../js/functions.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.old_pwd.value) || form1.old_pwd.value.length>100 || form1.old_pwd.value.length<3){			
			alert('<?php echo $MemberLanguage_Pack[JsPassword] ?>');
			form1.old_pwd.focus();
			return;
		}
		
		if (chkblank(form1.f_pwd.value) || form1.f_pwd.value.length>100  || form1.f_pwd.value.length<3){
			alert('<?php echo $MemberLanguage_Pack[JsPassword]?>');
			form1.f_pwd.focus();
			return;
		}

		if (form1.f_pwd.value != form1.s_pwd.value){
			alert('<?php echo $MemberLanguage_Pack[Twobadpassword] ?>');
			form1.s_pwd.focus();
			return;
		}

		form1.submit();
	}
	

</SCRIPT>
<div id="contain_out">

  <FORM name=form1 action='' method=post >
  <input type="hidden" name="Action" value="Modi">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Change_Pass]?></SPAN></TD>
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
                            <a href="provider_desktop.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>
                          
                          <TR>
                            <TD noWrap align=right><?php echo $MemberLanguage_Pack[Username_say] ?>：</TD>
                            <TD align=left noWrap><?php echo $_SESSION['Admin_Sa']?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $MemberLanguage_Pack[Ymm]; //原密码 ?>：</TD>
                            <TD width="38%" align=left noWrap><input name="old_pwd" type="password" class="inputstyle" id="old_pwd"></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $MemberLanguage_Pack[Xmm]; //新密码 ?>：</TD>
                            <TD align=left noWrap><input name="f_pwd" type="password" class="inputstyle" id="f_pwd"></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $MemberLanguage_Pack[Qrmm]; //确认密码 ?>：</TD>
                            <TD colspan="4" align=left noWrap><input name="s_pwd" type="password" class="inputstyle" id="s_pwd"></TD>
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
