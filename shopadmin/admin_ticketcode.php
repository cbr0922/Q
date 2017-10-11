<?php
include_once "Check_Admin.php";
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
$ticketid = intval($_GET['ticketid']);
if ($_GET['codeid']!="" && $_GET['Action']=='Modi'){
	$id = intval($_GET['codeid']);
	$Action_value = "Update";
	$UserNameAction = " disabled ";
	$Action_say  = "修改折價券號碼"; //修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}ticketcode` as tc inner jion `{$INFO[DBPrefix]}ticket` as t on tc.ticketid=t.ticketid where tc.codeid=".intval($id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$ticketname       =  $Result['ticketname'];
		$ticketcode       =  $Result['ticketcode'];
		$ticketid       =  $Result['ticketid'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$UserNameAction = "  ";
	$Action_say   = "新增折價券號碼"; //添加
	$reg_date     = date("Y-m-d",time());
	$reg_ip       = $FUNCTIONS->getip();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
行銷工具--&gt;電子折價券管理--&gt;<?php echo $Action_say;?>
</TITLE>
</HEAD>
<script language="javascript" src="../js/function.js"></script>
 <?php if ( VersionArea == "gb" ) {
 	$Onload =  " onLoad=\"createMenus('".$city."','".$canton."','','')\"  ";
 }else{
 	$Onload =  " onload=\"addMouseEvent();\"";
 }
 ?> 
<SCRIPT language=javascript>
	function checkform(){
		form1.submit();
}
</script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  <?php echo  $Onload ?> >
<?php include_once "head.php";?>
<div id="contain_out">
<?php include_once "Order_state.php";?>
  <FORM name=form1 action='admin_ticketcode_save.php' method=post >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%"><table width="90%" border=0 cellpadding=0 cellspacing=0>
              <tbody>
                <tr>
                  <td width=38><img height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32 /></td>
                  <td class=p12black nowrap><span  class=p9orange>行銷工具--&gt;電子折價券管理--&gt;<?php echo $Action_say;?></span></td>
                  </tr>
                </tbody>
              </table></TD>
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
                            <a href="admin_ticketcode_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                        <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->							</TD></TR></TBODY></TABLE>						</TD></TR></TBODY></TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD></TR>
                          
                          
                          <TR>
                            <TD noWrap align=right>折價券號碼： </TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','ticketcode',$ticketcode," maxLength=20 size=20 ")?></TD>
                            </TR>
                          <?php if ($_GET['ticketid']!="" && $_GET['Action']=='Modi'){  ?>
                          <input type="hidden" name="codeid" value="<?php echo $id?>">
                        
                        
                        <?php  } ?>
                        <input type="hidden" name="ticketid" value="<?php echo $ticketid?>">
                        <TR>
                          <TD colspan="2" align=right noWrap>&nbsp;</TD>
                          </TR>
                    </TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>






