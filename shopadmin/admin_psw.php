<?php
include_once "Check_Admin.php";
//print_r($_SESSION);
@header("Content-type: text/html; charset=utf-8");
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

if ($_POST['Action']=='Modi'){

	$Old_pw =  trim($_POST['old_pwd']);
	$New_pw =  password_hash(trim($_POST['f_pwd']), PASSWORD_BCRYPT);

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}administrator` where sa_id=".intval($_SESSION['sa_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Pw      =  $Result['pw'];
		if (!password_verify($Old_pw , $Pw)){
			$FUNCTIONS->sorry_back("admin_psw.php",$MemberLanguage_Pack[Ydm_bad]); //原密碼輸入不正確
			exit;
		}

	}else{
		$FUNCTIONS->sorry_back("index.php",'');
		exit;
	}




	//演示的时候禁止修改管理员密码！！
	if (is_file( RootDocument."/Classes/version.php" )){
		require RootDocument."/Classes/version.php";
		if ( $_VERSION->VersionClass == 'Demo'){
			$FUNCTIONS->sorry_back("admin_psw.php",'演示的时候禁止修改管理员密码！！');
		}
	}else{
		$FUNCTIONS->sorry_back("admin_psw.php",'演示的时候禁止修改管理员密码！！');
	}

	//------------------wordpress和shopnc整合-------------------
	if(file_exists(dirname(__FILE__)."/../api/wordpress.php")) {
		include_once(dirname(__FILE__)."/../api/wordpress.php");
	}
	//------------------wordpress和shopnc整合-------------------

	$Sql = "update `{$INFO[DBPrefix]}administrator` set pw='".$New_pw."'  where sa_id=".intval($_SESSION['sa_id']);
	$Modi_query = $DB->query($Sql);
	/**
	nuevoMailer系統串接
	**/
	if($INFO['nuevo.ifopen']==true){
		include_once("../modules/apmail/nuevomailer.class.php");
		$nuevo = new NuevoMailer;
		$nuevo->modiAdmin(trim($_POST['f_pwd']));
	}
	$FUNCTIONS->setLog("修改密碼");
	$FUNCTIONS->sorry_back("index.php",$MemberLanguage_Pack[PassWordModiIsPass_say]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META http-equiv=ever content=no-cache>
<META content="MSHTML 6.00.2600.0" name=GENERATOR>
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Change_Pass]?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/functions.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.old_pwd.value) || form1.old_pwd.value.length>100 || form1.old_pwd.value.length<3){
			alert('<?php echo $MemberLanguage_Pack[JsPassword]?>');
			form1.old_pwd.focus();
			return;
		}

		if (chkblank(form1.f_pwd.value) || form1.f_pwd.value.length>100  || form1.f_pwd.value.length<3){
			alert('<?php echo $MemberLanguage_Pack[JsPassword]?>');
			form1.f_pwd.focus();
			return;
		}

		if (form1.f_pwd.value != form1.s_pwd.value){
			alert('<?php echo $MemberLanguage_Pack[Twobadpassword]?>');
			form1.s_pwd.focus();
			return;
		}

		form1.submit();
	}
</SCRIPT>
<div id="contain_out">
  <FORM name=form1 action='' method=post >
    <?php  include_once "Order_state.php";?>
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
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Change_Pass]?></SPAN></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
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
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->

                            </TD></TR></TBODY></TABLE>

                      </TD></TR></TBODY></TABLE>
              </TD>
            </TR>
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
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>

                          <TR>
                            <TD noWrap align=right><?php echo $MemberLanguage_Pack[Username_say]; //帳號 ?>：</TD>
                            <TD align=left noWrap><?php echo $_SESSION['Admin_Sa']?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $MemberLanguage_Pack[Ymm]; //原密码 ?>：</TD>
                            <TD width="38%" align=left noWrap><input name="old_pwd" type="password" id="old_pwd"  class='box_no_pic1'  onmouseover="this.className='box_no_pic2'" onMouseOut="this.className='box_no_pic1'"  /></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $MemberLanguage_Pack[Xmm]; //新密码 ?>：</TD>
                            <TD align=left noWrap><input name="f_pwd" type="password"  id="f_pwd"  class='box_no_pic1'  onmouseover="this.className='box_no_pic2'" onMouseOut="this.className='box_no_pic1'"  /></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $MemberLanguage_Pack[Qrmm]; //确认密码 ?>：</TD>
                            <TD colspan="4" align=left noWrap><input name="s_pwd" type="password"  id="s_pwd"  class='box_no_pic1'  onmouseover="this.className='box_no_pic2'" onMouseOut="this.className='box_no_pic1'"  /></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
    </TR>
                      </FORM>
</div>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
