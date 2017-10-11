<?php
include_once "Check_Admin.php";
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

if ($_GET['user_id']!="" && $_GET['Action']=='Modi'){
	$id = intval($_GET['user_id']);
	$Action_value = "Update";
	$UserNameAction = " disabled ";
	$Action_say  = "修改公司資料"; //修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}company` where id=".intval($id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$companyname       =  $Result['companyname'];
		$password       =  $Result['password'];
		$content      =  $Result['content'];
		$level     =  $Result['level'];


	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$UserNameAction = "  ";
	$Action_say   = "新增公司資料"; //添加
	$reg_date     = date("Y-m-d",time());
	$reg_ip       = $FUNCTIONS->getip();
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK id=css href="css/calendar.css" type='text/css' rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;<?php echo $Action_say;?>
</TITLE>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
<script language="javascript" src="../js/function.js"></script>

 <?php if ( VersionArea == "gb" ) {
 	$Onload =  " onLoad=\"createMenus('".$city."','".$canton."','','')\"  ";
 }else{
 	$Onload =  " onload=\"addMouseEvent();\"";
 }
 ?> 
<script language="javascript">
function Order_history(){
	document.form1.action = "Member_HistoryList.php";
	document.form1.method ="get";
	document.form1.target ="_blank";
	document.form1.submit();
}
</script>
<SCRIPT language=javascript>
	function checkform(){
		if (chkblank(form1.companyname.value) || form1.companyname.value.length>20){
			alert('請輸入公司名稱'); //請輸入帳號！!
			form1.username.focus();
			return;
		}
		form1.submit();
}
</script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  <?php echo  $Onload ?> >
<?php include $Js_Top ;  ?>

<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<div id="contain_out">
<?php include_once "Order_state.php";?>
  <FORM name=form1 action='admin_company_save.php' method=post >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap>
                      <SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;<?php echo $Action_say;?></SPAN></TD>
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
                            <a href="admin_member_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                            <TD noWrap align=right> 公司名稱： </TD>
                            <TD width="5%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','companyname',$companyname,"  maxLength=20 size=20  ")?>					  </TD>
                            <TD width="57%" align=left noWrap><div id='show_UsernameContent'>&nbsp;</div></TD>
                            <TD width="10%" align=left noWrap>&nbsp;</TD>
                            <TD width="10%" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Admin_Member[Password] ;?>： </TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','password',$password," maxLength=20 size=20 ")?></TD>
                            </TR>
                          <?php if ($_GET['user_id']!="" && $_GET['Action']=='Modi'){  ?>
                          <input type="hidden" name="user_id" value="<?php echo $id?>">
                        
                        
                        <?php  } ?>
                        
                        <TR>
                          <TD noWrap align=right> <?php echo $Admin_Member[UserLevel];//会员等级?>
                            ： </TD>
                          <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}user_level` order by level_id asc ",'user_level','level_id','level_name',intval($level)); //$FUNCTIONS->Level_name($member_point)?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>公司介紹：</TD>
                          <TD colspan="4" align=left noWrap><textarea name="content" cols="70" rows="5" id="content"><?php echo $content?></textarea></TD>
                          </TR>
                        
                        <TR>
                          <TD colspan="5" align=right noWrap>&nbsp;</TD>
                          </TR>
                    </TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>






