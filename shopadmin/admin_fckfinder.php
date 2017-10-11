<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
include_once Resources."/ckeditor/ckeditor.php";

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
<?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0 style="margin-bottom:5px;margin-top:10px">
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>內容管理--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;圖片管理<?php if (intval($top_id) > 0){?>--&gt;<?php echo $top_areaname;}?></SPAN></TD>
              </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">&nbsp;</TD>
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
                            <TD align="center"><iframe name=top marginwidth=0 marginheight=0  src="../Resources/ckfinder/ckfinder.html" frameborder=no width="100%"   scrolling=no height=600px topmargin="0" leftmargin="0"></iframe></TD>
                            </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>

</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
