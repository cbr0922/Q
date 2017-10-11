<?php
include_once "Check_Admin.php";
//include_once Resources."/ckeditor/ckeditor.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";

if ($_GET['transtime_id']!="" && $_GET['Action']=='Modi'){
	$Transtime_id = intval($_GET['transtime_id']);
	$Action_value = "Update";
	$Action_say   = $Admin_Product[ModiHomeTimeType];
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}transtime` where transtime_id=".intval($Transtime_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$transtime_name      =  $Result['transtime_name'];
	}else{
		echo "<script language=javascript>javascript:window.history.back(-1);</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = $Admin_Product[AddHomeTimeType];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $Action_say?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
	
	   if (chkblank(form1.transtime_name.value) || form1.transtime_name.value.length>100){
			form1.transtime_name.focus();
			alert('<?php echo $Admin_Product[PleaseInputHomeTimeType]?>');
			return;
		}
		form1.action="admin_timetype_save.php";
		form1.submit();
    }
</SCRIPT>
<div id="contain_out">
  <FORM name=form1 action='' method=post >
    <?php  include_once "Order_state.php";?>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="Transtime_id" value="<?php echo $Transtime_id?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $Action_say?></SPAN></TD>
                </TR></TBODY></TABLE></TD>  
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
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
                            <TD align="left" valign="top" noWrap>&nbsp;</TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD width="18%" align="right" valign="middle" noWrap> <?php echo $JsMenu[HomeSend_TimeType];//宅配時間?>：</TD>
                            <TD align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','transtime_name',$transtime_name,"  maxLength=50 size=50  ")?> <a href="#" title="<?php echo  $Admin_Product[Comment_transtime_name]?>"  class="easyui-tooltip"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>&nbsp;</TD>
                            <TD align="left" valign="top" noWrap>&nbsp;</TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                        </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
