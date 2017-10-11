<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";

$top_id = intval($_GET['top_id']);
if ($_GET['area_id']!="" && $_GET['Action']=='Modi'){
	$area_id = intval($_GET['area_id']);
	$Action_value = "Update";
	$Action_say  = "修改地區信息"; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}area` where area_id=".intval($area_id)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$areaname =  $Result['areaname'];
		$areatype  =  $Result['areatype'];
		$top_id  =  $Result['top_id'];
		$zip  =  $Result['zip'];
		$membercode  =  $Result['membercode'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "添加地區信息" ; //插入
}

if (intval($top_id) > 0){

	$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}area` where area_id=".intval($top_id)." limit 0,1");
	$Num_goods   = $DB->num_rows($Query_goods);
	if ($Num_goods>0){
		$Result_goods= $DB->fetch_array($Query_goods);
		$top_areaname =  $Result_goods['areaname'];
		$top_areatype  =  $Result_goods['areatype'];
		$top_top_id  =  $Result_goods['top_id'];
		$top_zip  =  $Result_goods['zip'];
	}

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;地區管理<?php if (intval($top_id) > 0){?>--&gt;<?php echo $top_areaname;}?></title>
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
  <FORM name=form1 action='admin_area_save.php' method=post>
    <?php  include_once "Order_state.php";?>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="area_id" value="<?php echo $area_id?>">
  <input type="hidden" name="top_id" value="<?php echo $top_id?>">
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
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p9orange noWrap><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<a href="admin_area_list.php">地區管理</a><?php if (intval($top_id) > 0){?>--&gt;<?php echo $top_areaname;}?></TD>
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
                            <a href="admin_area_list.php?top_id=<?php echo $top_id;?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                      <TABLE class=allborder cellSpacing=0 cellPadding=2   width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              <DIV align=right>地區名稱：</DIV></TD>
                            <TD noWrap align=right>
                              <DIV align=left><?php echo $FUNCTIONS->Input_Box('text','areaname',$areaname,"      maxLength=50 size=40 ")?></DIV>					  </TD>
                            </TR>
                          
                          
                          <TR>
                            <TD noWrap align=right width="18%">郵遞區號：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','zip',$zip,"      maxLength=10 size=10 ")?>
                              <input type="hidden" name="areatype" value="<?php echo $_GET['areatype'];?>"></TD>
                            </TR>
                          <?php if($top_id==0){?>
                          <TR>
                            <TD noWrap align=right>會員編號：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','membercode',$membercode,"      maxLength=10 size=10 ")?>會員編號首位字符</TD>
                            </TR>
                          <?php
					}
					?>
                          <TR>
                            <TD colspan="2" align=center noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
    </TR>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
