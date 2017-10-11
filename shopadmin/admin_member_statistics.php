<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/ProductVisit_Pack.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD><TITLE><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>-->會員統計</TITLE></HEAD>
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
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>-->會員統計</SPAN></TD>
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
                        <TBODY>
                          <TR align="center">
                            <TD height="300" valign="middle">

                              <TABLE width="61%" border=0 cellPadding=3 cellSpacing=0 bgcolor="#F7F7F7" class="allborder">
                                <TBODY>
                                  <TR>
                                    <TD height="25" colspan="4" align=left  bgColor=#ffffff class=p9orange>&nbsp;</TD>
                                  </TR>                                  <TR>                                    <TD width="14%"  height=30 align=right bgColor=#ffffff class=p9orange>                                      <IMG src="images/bluearrow.gif" border="0" /></TD>                                    <TD width="39%" height=30 align=left bgColor=#ffffff class=p9orange><a href="admin_crm_dashiboard.php">會員概況</a></TD>                                    <TD width="4%" align=right bgColor=#ffffff class=p9orange>                                      <IMG src="images/bluearrow.gif" border="0" /></TD>                                    <TD width="43%" height=30 align=left bgColor=#ffffff class=p9orange><a href="admin_member_consumption_record_list.php">會員消費紀錄</a></TD>                                  </TR>                                  <TR>                                    <TD width="14%"  height=30 align=right bgColor=#ffffff class=p9orange>                                      <IMG src="images/bluearrow.gif" border="0" /></TD>                                    <TD width="39%" height=30 align=left bgColor=#ffffff class=p9orange><a href="admin_crm_member_report.php">註冊會員統計</a></TD>                                    <TD width="4%" align=right bgColor=#ffffff class=p9orange>                                      <IMG src="images/bluearrow.gif" border="0" /></TD>                                    <TD width="43%" height=30 align=left bgColor=#ffffff class=p9orange><a href="admin_google_analytics.php">Google Analytics概況</a></TD>                                  </TR>
                                  <TR>
                                    <TD height="25" align=right bgColor=#ffffff class=p9orange><img src="images/bluearrow.gif" border="0" /></TD>
                                    <TD height="25" align=left bgColor=#ffffff class=p9orange><a href="admin_crm_member_advantage.php">會員活耀度統計</a></TD>
                                    <TD height="25" align=right bgColor=#ffffff class=p9orange><img src="images/bluearrow.gif" border="0" /></TD>
                                    <TD height="25" align=left bgColor=#ffffff class=p9orange><a href="admin_member_recommend_record_list.php">會員推薦消費統計</a></TD>
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