<?php
include_once "Check_Admin.php";
?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD><TITLE></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
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
      <?php  include_once "Order_state.php";?>
  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD></TR></TBODY></TABLE>


<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>

  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 
      src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" 
  width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange>工具--&gt;统计分析--&gt;销售统计</SPAN></TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%">
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <!--BUTTON_END--></TD>
                    </TR></TBODY></TABLE>
							
						</TD></TR></TBODY></TABLE></TD></TR>
			</TBODY>
		  </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD height="300" vAlign=top bgColor=#ffffff>
                  <TABLE class=9pv cellSpacing=0 cellPadding=2 
                  width="100%" align=center bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR align="center">
                      <TD height="300" valign="middle">
					  
			    <TABLE width="61%" border=0 cellPadding=3 cellSpacing=0 bgcolor="#F7F7F7" class="allborder">
                    <TBODY>
                    <TR>
                      <TD height="25" colspan="4" align=left  bgColor=#ffffff class=p9orange>&nbsp;</TD>
                      </TR>
                    <TR>
                      <TD width="14%"  height=30 align=right bgColor=#ffffff class=p9orange>
					  <IMG height=14  src="images/<?php echo $INFO[IS]?>/intromenu-o_03.gif" width=14></TD>
                      <TD width="39%" height=30 align=left bgColor=#ffffff class=p9orange>
					  <A  href="SalesGuide.php">销售指标分析</A></TD>
                      <TD width="4%" align=right bgColor=#ffffff class=p9orange>
					  <IMG height=14 src="images/<?php echo $INFO[IS]?>/intromenu-o_03.gif" width=14></TD>
                      <TD width="43%" height=30 align=left bgColor=#ffffff class=p9orange>
					  <A  href="GetGSTotal.php">商品销售量与销售额排名</A></TD></TR>
                    <TR>
                      <TD class=p9orange align=right bgColor=#ffffff>
					  <IMG height=14 src="images/<?php echo $INFO[IS]?>/intromenu-o_03.gif" width=14></TD>
                      <TD class=p9orange align=left bgColor=#ffffff  height=30>
					  <A  href="SaleMap.php">销售额</A></TD>
                      <TD class=p9orange align=right bgColor=#ffffff>
					  <IMG height=14 src="images/<?php echo $INFO[IS]?>/intromenu-o_03.gif" width=14></TD>
                      <TD class=p9orange align=left bgColor=#ffffff  height=30>
					  <A  href="GoodsCatInfo.php">商品大类销售量与销售额排名</A></TD></TR>
                    <TR>
                      <TD class=p9orange align=right bgColor=#ffffff>
					  <IMG height=14 src="images/<?php echo $INFO[IS]?>/intromenu-o_03.gif" width=14></TD>
                      <TD class=p9orange align=left bgColor=#ffffff  height=30>
					  <A  href="GetOrderTotal.php">会员订单量排名</A></TD>
                      <TD class=p9orange align=right bgColor=#ffffff>
					  <IMG height=14 src="images/<?php echo $INFO[IS]?>/intromenu-o_03.gif"  width=14></TD>
                      <TD class=p9orange align=left bgColor=#ffffff  height=30>
					  <A href="GetGVisTotal.php">商品访问次数排名</A></TD></TR>
                    <TR>
                      <TD class=p9orange align=right bgColor=#ffffff>
					  <IMG height=14 src="images/<?php echo $INFO[IS]?>/intromenu-o_03.gif"   width=14></TD>
                      <TD class=p9orange align=left bgColor=#ffffff  height=30>
					  <A href="GetBuyAmount.php">会员购物额排名</A></TD>
                      <TD class=p9orange align=right bgColor=#ffffff>
					  <IMG height=14 src="images/<?php echo $INFO[IS]?>/intromenu-o_03.gif"  width=14></TD>
                      <TD class=p9orange align=left bgColor=#ffffff height=30>
					  <A href="GoodsVisBuy.php">商品访问次数与购买次数对比</A>
					  </TD>
					  </TR>
                    <TR>
                      <TD height="25" colspan="4" align=left bgColor=#ffffff class=p9orange>&nbsp;</TD>
                      </TR>
					  </TBODY>
					  </TABLE>
					  </TD>
                      </TR>
                    </TBODY></TABLE></TD>
              </TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
              
					  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
