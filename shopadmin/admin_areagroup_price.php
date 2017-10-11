<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
include_once Resources."/FCKeditor/fckeditor.php";

$group_id = intval($_GET['group_id']);
$Sql = "select tg.* from `{$INFO[DBPrefix]}transgroup` as tg where tg.group_id=".intval($group_id)." limit 0,1";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$group_id  =  $Result['group_id'];
		$trans_id  =  $Result['trans_id'];
		$groupname  =  $Result['groupname'];
		$money  =  $Result['money'];
		$content  =  $Result['content'];
	}
	
if ($_GET['tp_id']!="" && $_GET['Action']=='Modi'){
	$tp_id = intval($_GET['tp_id']);
	$Action_value = "Update";
	$Action_say  = "修改地區重量運費"; //修改商品類別
	$Sql = "select tg.* from `{$INFO[DBPrefix]}transgroup_price` as tg where tg.tp_id=".intval($tp_id)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$group_id  =  $Result['group_id'];
		$tp_id  =  $Result['tp_id'];
		$startweight  =  $Result['startweight'];
		$endweight  =  $Result['endweight'];
		$price  =  $Result['price'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "添加地區重量運費" ; //插入
}




?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<head>
<LINK href="./css/theme.css" type=text/css rel=stylesheet>
<LINK href="./css/css.css" type=text/css rel=stylesheet>
<LINK href="./css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>地區運費管理--&gt;<?php echo $Action_say;?></title>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" 
background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
  <TABLE class=9pv height=15 cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD width="2%" bgColor=#e7e7e7 height=15>&nbsp;</TD>
    <TD class=p9black vAlign=bottom width="98%" bgColor=#e7e7e7 height=15>
	<?php  include_once "Order_state.php";?></TD></TR>
	</TBODY>
	</TABLE>
    <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
    <TBODY>
     <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD></TR>
	</TBODY>
	</TABLE>
<SCRIPT language=javascript src="include/functions.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>
	function checkform(){
		//form1.action="admin_pcat_act.php?action=add";
		form1.submit();
	}
	
	function changecat(bid){
		//form1.action="admin_pcat.php?Action=Modi&bid="+bid;
		//form1.action="admin_pcat.php?Action=Insert&bid="+bid;		
		//form1.submit();
		location.href="admin_areagroup_save.php?Action=Insert&group_id="+bid;
	}
</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <FORM name=form1 action='admin_areagroup_price_save.php' method=post>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="group_id" value="<?php echo $group_id?>">
   <input type="hidden" name="tp_id" value="<?php echo $tp_id?>">
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif"  width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif height=319>&nbsp;</TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;地區運費管理-&gt;<?php echo $groupname;?>--&gt;<?php echo $Action_say;?></SPAN></TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%">
          

    <?php if ($Ie_Type != "mozilla") { ?>
		    <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="admin_areagroup_list.php">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew border=0 link="javascript:checkform();">
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap ><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->
							
							</TD></TR></TBODY></TABLE>
							
						</TD></TR></TBODY></TABLE>

    <?php } else {?> 
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
                            <TD vAlign=bottom noWrap>
							<a href="admin_areagroup_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap ><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->
							
							</TD></TR></TBODY></TABLE>
							
						</TD></TR></TBODY></TABLE>
    <?php } ?>							
		  
		  </TD></TR>
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
                      <TD align=right noWrap>起始重量：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','startweight',$startweight,"      maxLength=10 size=10 ")?>&nbsp;KG</TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>結束重量：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','endweight',$endweight,"      maxLength=10 size=10 ")?>&nbsp;KG注：設置0.5kg，起始重量和結束重量都填寫0.5</TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>運費：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','price',$price,"      maxLength=10 size=10 ")?></TD>
                    </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
                      </FORM>
					  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
