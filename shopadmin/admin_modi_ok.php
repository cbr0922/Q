<?php
include_once "Check_Admin.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD><TITLE></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
      <?php  include_once "Order_state.php";?>
<SCRIPT language=javascript>
	function checkform(){
		form1.submit();
	}
	

</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>

  <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319><table width="100%" border="0" cellpadding="0" cellspacing="0" class="allborder">
                        <tr> 
                          <td height="292" width="36%" align="right"><img src="images/<?php echo $INFO[IS]?>/info.gif" width="64" height="64"> 
                            </td>
                          <td height="292" width="64%"> 
                            <table width="100%" border="0" cellspacing="0" cellpadding="2" class="p9black">
                              <tr> 
                                <td class="p9orange"><img src="images/<?php echo $INFO[IS]?>/OK.gif"></td>
                                </tr>
                              <tr> 
                                <td></td>
                                </tr>
                              </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr> 
                                <td width="9%">&nbsp;</td>
                                <td width="91%"><a href="desktop.php"><?php echo $Basic_Command['GoToDesktop']?><!--返回桌面--></a></td>
                                </tr>
                              </table>
                            </td>
                          </tr>
        </table></TD>
    </TR>
                    <TR>
                      <TD width="100%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      </TR>

					  </TBODY>
</TABLE>
</div>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
