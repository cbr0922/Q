<?php
include_once "Check_Admin.php";
include_once "Version.class.php";
$Version = new Version;
/*
if (!empty($_POST[VERSION_NUM])){
include_once "Version.class.php";
$Version = new Version;
$Version = trim($_POST[VERSION_NUM]);
$Version->ViewVersion($Version);
}
*/
?>
<HTML><HEAD><TITLE>关于我们</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<META content="MSHTML 6.00.2900.2523" name=GENERATOR></HEAD>
<BODY bgColor=#ffffff>

<script language="JavaScript">
//initSend();

	function initSend()
	{
//				document.form1.VERSION_NUM.value = VERSION_NUM;
				document.form1.action = "";
//				document.form1.target = '';
				document.form1.submit();
	}

</script>

<TABLE cellSpacing=0 cellPadding=0 border=0>
  <TBODY>
  <TR>
    <TD background=images/<?php echo $INFO[IS]?>/about.gif height=31>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR vAlign=bottom>
          <TD width="9%" height=21>&nbsp;</TD>
          <TD width="79%">&nbsp;</TD>
          <TD width="12%">&nbsp;</TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TD><a href="<?php echo $INFO['site_url']?>"><IMG height=70  src="images/<?php echo $INFO[IS]?>/about-03.gif" width=505 border=0></a></TD>
  </TR>
  <TR>
    <TD vAlign=top align=middle background=images/<?php echo $INFO[IS]?>/about-04.gif height=169>
	<form name="form1" action="" method="post">
    <INPUT type="hidden" name="VERSION_NUM" value="smartshop.1.20060819">
    </form>
      <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="93%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=156>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>            
            &nbsp; <font color="#FF0000"><?php echo $Version->ViewVersion($Version="smartshop.1.20050519") ?></font>
            <p><BR>
                <BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;smartshop-the 
              furture of shopping <BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <A href="<?php echo $INFO['site_url']?>" 
            target=_blank>&nbsp;&nbsp;&nbsp;&nbsp;<SPAN 
            class=p9orange>&nbsp;
              <?php echo $INFO['email']?>
              </SPAN></A><BR>
              <BR>
            </p></TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TD><IMG height=31 src="images/<?php echo $INFO[IS]?>/about-05.gif" width=505></TD></TR></TBODY></TABLE></BODY></HTML>
