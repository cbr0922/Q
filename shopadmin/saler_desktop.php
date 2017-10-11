<?php
include_once "Check_Admin.php";
include_once "../language/".$INFO['IS']."/Desktop_Pack.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>經銷商 --> 桌面</TITLE>
<script language="javascript" src="../js/TitleI.js"></script>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?> 
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=./images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="./images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=15 cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD>&nbsp;</TD></TR></TBODY></TABLE>
<TABLE height=400 cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD vAlign=top align=right width="30%">
      <TABLE cellSpacing=0 cellPadding=0 width="49%" border=0>
        <TBODY>
        <TR>
          <TD><IMG height=18 src="./images/<?php echo $INFO[IS]?>/tipstop.gif" width=210></TD></TR>
        <TR>
          <TD align=middle background=./images/<?php echo $INFO[IS]?>/tipsbg.gif>
            <TABLE width="95%" border=0 cellPadding=2 cellSpacing=0 class="9pv">
              <TBODY>
              <TR>
                <TD class=p9black background=./images/<?php echo $INFO[IS]?>/03_content_backgr.png 
                height=25><B><?php echo $Desktop_Pack[LoginInfo]?></B></TD></TR>
              <TR>
                <TD class=p9orange align=right height=22><?php echo $Desktop_Pack[LoginUser]?>&nbsp;<?php echo $_SESSION['Admin_Sa'];?></TD>
              </TR>
              <TR>
                <TD class=p9orange align=right height=20><?php echo $Desktop_Pack[LoginTime]?>&nbsp;<?php echo date("Y-m-d H: ia ",$_SESSION['Admin_Logintime'])?> </TD></TR>

                			</TABLE>
			</TD>
		  </TR>
        <TR>
          <TD><IMG height=21 src="./images/<?php echo $INFO[IS]?>/topsbottom.gif" 
        width=210></TD></TR></TBODY></TABLE></TD>
    <TD vAlign=top align=right width="70%">
      <TABLE class=p9black cellSpacing=0 cellPadding=10 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="51%"></TD>
          </TR>
        </TBODY></TABLE></TD></TR></TBODY></TABLE></BODY></HTML>
