<?php
include ("../configs.inc.php");
include Classes . "/global.php";
@header("Content-type: text/html; charset=utf-8");
include_once "../language/".$INFO['IS']."/Desktop_Pack.php";
include_once "../language/".$INFO['IS']."/JsMenu.php";
?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD><TITLE></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
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
    <TD vAlign=center align=center width="30%"><img src="images/<?php echo $INFO[IS]?>/error.gif" width="114" height="98">

	<a href='index.php'><?php echo $JsMenu[PrivilegeError];?></a>
	<br />
	<br />
	<br />
	<br />
	<a href='<?php echo $INFO['site_shopadmin']?>/login.php?Action=Logout'>Logout</a></TD>
    </TR></TBODY></TABLE>
</BODY></HTML>
