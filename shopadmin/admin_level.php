<?php
include "Check_Admin.php";

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";


if ($_GET['level_id']!="" && $_GET['Action']=='Modi'){
	$level_id = intval($_GET['level_id']);
	$Action_value = "Update";
	$Action_say  = $Admin_Member[UserLevelName_Modi]; //修改會員等級
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}user_level` where level_id=".intval($level_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$level_name      =  $Result['level_name'];
		$level_num       =  $Result['level_num'];
		$pricerate       =  $Result['pricerate'];
		$vip_yearmoney       =  $Result['vip_yearmoney'];
		$vip_money       =  $Result['vip_money'];
		$vip_days       =  $Result['vip_days'];		$sort       =  $Result['sort'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$Action_say   = $Admin_Member[UserLevelName_Add]; //新增會員等級
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;<?php echo $Action_say;?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		if (chkblank(form1.level_name.value) || form1.level_name.value.length>20){
			form1.level_name.focus();
			alert('<?php echo $Admin_Member[PleaseInputLevelName]?>'); //请输入级别名称！
			return;
		}

		form1.submit();
	}


</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_level_save.php' method=post >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="level_id" value="<?php echo $level_id?>">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;<?php echo $Action_say;?></SPAN></TD>
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
                            <a href="admin_level_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                            <TD width="23%" align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Member[UserLevelName] ;//等级名称?> ：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','level_name',$level_name,"     maxLength=\"50\" size=\"50\"   ")?></TD>
                            <TD width="13%" align=right noWrap>&nbsp;</TD>
                            <TD width="14%" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <!--TR>
                            <TD noWrap align=right> <?php echo $Admin_Member[PerNum];//积分满足点?> ：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','level_num',$level_num,"      maxLength=\"50\" size=\"50\"  ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR-->
                          <TR>
                            <TD noWrap align=right>會員價格百分比：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','pricerate',$pricerate,"      maxLength=\"50\" size=\"50\"  ")?>%</TD>
                            </TR>

                          <TR>
                            <TD noWrap align=right>會員升降等：</TD>
                            <TD width="10%" align=left>一年內累積購買滿</TD>
                            <TD width="40%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','vip_yearmoney',$vip_yearmoney,"          maxLength=15 size=20  ")?>升級</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>或單筆消費</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','vip_money',$vip_money,"          maxLength=15 size=20  ")?>升級</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>若VIP會員於</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','vip_days',$vip_days,"          maxLength=15 size=20  ")?>天內未消費則降級</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                          </TR>													<TR>														<TD noWrap align=right>排序：</TD>														<TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','sort',$sort,"     maxLength=\"10\" size=\"10\"   ")?></TD>														<TD width="13%" align=right noWrap>&nbsp;</TD>														<TD width="14%" align=left noWrap>&nbsp;</TD>														</TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                          </TR>
                    </TBODY>
        </TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>