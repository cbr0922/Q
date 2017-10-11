<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/ProductVisit_Pack.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD><TITLE><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>-->進階商品統計</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>-->進階商品統計</SPAN></TD>
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
              <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
                <TBODY>
                  <TR>
                    <TD height="300" vAlign=top bgColor=#ffffff>
                      <TABLE class=9pv cellSpacing=0 cellPadding=2
                  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>                          <TR align="center">
                            <TD height="300" valign="middle">

                              <TABLE width="61%" border=0 cellPadding=3 cellSpacing=0 bgcolor="#F7F7F7" class="allborder">
                                <TBODY>

                                  <TR>
                                    <TD width="14%"  height=30 align=right bgColor=#ffffff class=p9orange>
                                      </TD>
                                    <TD style="font-size:18px; font-weight:bold" width="39%" height=30 align=left bgColor=#ffffff >&nbsp;</TD>

                                    <TD width="4%" align=right bgColor=#ffffff class=p9orange>&nbsp;</TD>
                                    <TD width="43%" height=30 align=left bgColor=#ffffff class=p9orange>&nbsp;</TD></TR>
                                    <TR>
                                    <TD width="14%"  height=30 align=right bgColor=#ffffff class=p9orange>
                                      <IMG src="images/bluearrow.gif" border="0" /></TD>
                                    <TD width="39%" height=30 align=left bgColor=#ffffff class=p9orange><a href="admin_sale_dashiboard.php">銷售概況</a></TD>
                                    <TD width="4%" align=right bgColor=#ffffff class=p9orange>
                                      <IMG src="images/bluearrow.gif" border="0" /></TD>
                                    <TD width="43%" height=30 align=left bgColor=#ffffff class=p9orange><a href="admin_shopping_cart_goods_statistics.php">購物車商品統計</a></TD></TR>
                                  <TR>
                                    <TD class=p9orange align=right bgColor=#ffffff>
                                      <IMG src="images/bluearrow.gif" border="0" /></TD>
                                    <TD class=p9orange align=left bgColor=#ffffff  height=30><a href="admin_sale_hot.php">熱門商品統計</a></TD>
                                    <TD class=p9orange align=right bgColor=#ffffff><IMG src="images/bluearrow.gif" border="0" /></TD>
                                    <TD class=p9orange align=left bgColor=#ffffff  height=30><a href="admin_sale_brand.php">品牌銷售統計</a></TD>
                                  </TR>
                                  <TR>
                                    <TD align=right bgColor=#ffffff class=p9orange><img src="images/bluearrow.gif" alt="" border="0" /></TD>
                                    <TD class=p9orange align=left bgColor=#ffffff  height=30><a href="admin_sale_class.php">品類銷售統計</a></TD>
                                    <TD align=right bgColor=#ffffff class=p9orange><img src="images/bluearrow.gif" alt="" border="0" /></TD>
                                    <TD align=left bgColor=#ffffff class=p9orange><a href="admin_sale_provider.php">供應商銷售統計</a></TD>
                                  </TR>
                                  <TR>
                                    <TD align=right bgColor=#ffffff class=p9orange><img src="images/bluearrow.gif" alt="" border="0" /></TD>
                                    <TD class=p9orange align=left bgColor=#ffffff  height=30><a href="admin_track_goods_statistics.php">追蹤商品統計</a></TD>
                                    <TD align=right bgColor=#ffffff class=p9orange>&nbsp;</TD>
                                    <TD align=left bgColor=#ffffff class=p9orange>&nbsp;</TD>
                                  </TR>
                                  
                                  <TR>
                                    <TD height="25" colspan="4" align=left bgColor=#ffffff class=p9orange>&nbsp;</TD>
                                  </TR>
                                </TBODY>
                              </TABLE>
                            </TD>
                          </TR>
                        </TBODY></TABLE></TD>
              </TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
