<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Order_Pack.php";

$order_id  = intval($FUNCTIONS->Value_Manage($_GET['order_id'],$_POST['order_id'],'back',''));
//$Sql         = "select gc.* ,g.goodsname from good_comment gc  inner join goods g on (gc.good_id=g.gid) where comment_id=".intval($Comment_id)." limit 0,1 ";
$Sql         = "select * from `{$INFO[DBPrefix]}order_userback` where order_id=".$order_id." order by userback_idate desc limit 0,1";
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);

if ($_POST['Action']=='Update'){
	$UserBackId  = intval($FUNCTIONS->Value_Manage($_GET['UserBackId'],$_POST['UserBackId'],'back',''));
	$DB->query("update `{$INFO[DBPrefix]}order_userback` set sys_say='".$_POST['sys_say']."' where userback_id=".intval($UserBackId));
	$FUNCTIONS->sorry_back("admin_userback.php?order_id=$order_id","");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $Order_Pack[System_say];?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
	function checkform(){
		document.form1.action="admin_userback.php";
		document.form1.submit();
	}	
</SCRIPT>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
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
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $Order_Pack[System_say];?>
                      </SPAN>
                      </TD>
                  </TR></TBODY></TABLE>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_order_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"   border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79>
                            <?
					  if ($Num>0){
					  ?>
                            <!--BUTTON_BEGIN-->
                            <TABLE><TBODY>
                              <TR>
                                <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE>
                            <?php } ?>	
                            <!--BUTTON_END--></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
              </TD>
          </TR></TBODY></TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top height=262>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff height=300>
                      <table width="100%"  border="0">
                        <tr>
                          <td><span class="p12black">&nbsp;<?php echo $Order_Pack[OrderLy];//订单留言?>
                            </span></td>
                          </tr>
                        </table>
                      <?
				  if ($Num>0){
				  	while ($Result    = $DB->fetch_array($Query)){
				  		$UserBackId = $Result['userback_id'];
				  		$Alread     = $Result['userback_alread'];
				  		if ($Alread==0){
				  			$DB->query("update `{$INFO[DBPrefix]}order_userback` set userback_alread=1 where userback_id=".intval($UserBackId));
				  		}
				  		$SysSay     = $Result['sys_say'];
				  ?>
                      <TABLE class=allborder cellSpacing=3 cellPadding=3  width="100%" bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD width="12%" align=right noWrap ><?php echo $Order_Pack[Member_say];//会员留言?></TD>
                            <TD><?php echo nl2br($Result['user_say'])?></TD></TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Order_Pack[System_say];//系統回覆?> </TD>
                            <TD><?php echo nl2br($Result['sys_say'])?></TD>
                          </TR></TBODY></TABLE>
                      <table width="100%"  border="0">
                        <tr>
                          <td>&nbsp;</td>
                          </tr>
                        </table>
                      <? }
				  }
				   ?>
                      <table width="100%"  border="0">
                        <tr>
                          <td><span class="p12black">&nbsp;<?php echo $Order_Pack[System_say];//系統回覆?></span></td>
                          </tr>
                        </table>  
                      <FORM name=form1 action='' method=post  >
                        <input type="hidden" name="Action" value="Update">
                        <INPUT type=hidden  name='order_id' value="<?php echo $order_id ?>">  
                        <INPUT type=hidden  name='UserBackId' value="<?php echo $UserBackId?>">                 
                        <TABLE class=allborder cellSpacing=3 cellPadding=3  width="100%" bgColor=#f7f7f7 border=0>
                          <TBODY>
                            <TR>
                              <TD width="12%" align=right noWrap><?php echo $Order_Pack[OrderHFNR];?> </TD>
                              <TD><textarea name="sys_say" cols="80" rows="10"><?php echo $SysSay?></textarea></TD>
                              </TR>
                            </TBODY>
                          </TABLE>		
                        </FORM>		  
                      </TD>
              </TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
    </TR>
</TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
