<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";

if ($_GET['hid']!="" && $_GET['Action']=='Modi'){
	$hid = intval($_GET['hid']);
	$Action_value = "Update";
	$Action_say  = "修改假日資訊"; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}holiday` where hid=".intval($hid)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$name =  $Result['name'];
		$startdate  =  $Result['startdate'];
		$enddate  =  $Result['enddate'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增假日信息" ; //插入
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;假日管理</title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		//form1.action="admin_pcat_act.php?action=add";
		form1.submit();
	}
	
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_holiday_save.php' method=post>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="hid" value="<?php echo $hid?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;假日管理</SPAN></TD>
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
                            <a href="admin_holiday_list.php?top_id=<?php echo $top_id;?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2   width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              <DIV align=right>假日名稱：</DIV></TD>
                            <TD noWrap align=right>
                              <DIV align=left><?php echo $FUNCTIONS->Input_Box('text','name',$name,"      maxLength=50 size=40 ")?></DIV>					  </TD>
                            </TR>
                          
                          
                          <TR>
                            <TD noWrap align=right width="18%">假日日期：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','startdate',$startdate," id=startdate   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?>至<?php echo $FUNCTIONS->Input_Box('text','enddate',$enddate," id=enddate   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?></TD>
                            </TR>
                          
                          <TR>
                            <TD colspan="2" align=center noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>

    </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
