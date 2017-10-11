<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";


if ($_GET['attrid']!="" && $_GET['Action']=='Modi'){
	$attrid = intval($_GET['attrid']);
	$Action_value = "Update";
	$Action_say  = "修改類別屬性"; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}attribute` where attrid=".intval($attrid)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);


	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$attributename =  $Result['attributename'];
		$attrid  =  $Result['attrid'];
    $Sort  =  $Result['sort'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增類別屬性" ; //插入
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>商品類別屬性管理--&gt;<?php echo $Action_say;?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>



<SCRIPT language=javascript>

	function checkform(){

		//form1.action="admin_pcat_act.php?action=add";

		form1.submit();

	}

	

	function changecat(bid){

		//form1.action="admin_pcat.php?Action=Modi&bid="+bid;

		//form1.action="admin_pcat.php?Action=Insert&bid="+bid;		

		//form1.submit();

		location.href="admin_attribute_save.php?Action=Insert&bid="+bid;

	}

</SCRIPT>



<div id="contain_out">

<?php  include_once "Order_state.php";?>

  <FORM name=form1 action='admin_attribute_save.php' method=post>

  <input type="hidden" name="Action" value="<?php echo $Action_value?>">

  <input type="hidden" name="attrid" value="<?php echo $attrid?>"> 

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

                <TBODY>

                  <TR>

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>

                    <TD class=p12black noWrap><SPAN  class=p9orange>商品類別屬性管理--&gt;<?php echo $Action_say;?></SPAN></TD>

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

                            <a href="admin_attribute_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

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

                              商品類別屬性名稱：</TD>

                            <TD noWrap align=left>

                              <?php echo $FUNCTIONS->Input_Box('text','attributename',$attributename,"  maxLength=50 size=40 ")?>					  </TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right width="18%">

                              顯示順序：</TD>

                            <TD noWrap align=left>

                              <?php echo $FUNCTIONS->Input_Box('text','sort',$Sort,"      maxLength=10 size=10 ")?>                        </TD></TR>
                          

                          <TR>

                            <TD noWrap align=right width="18%">&nbsp;</TD>

                            <TD noWrap align=right>&nbsp;</TD></TR>

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD noWrap align=right>&nbsp;</TD>

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

