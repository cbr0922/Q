<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$Sql_level   = "select level_id,level_name from `{$INFO[DBPrefix]}user_level`";
$Query_level = $DB->query($Sql_level);
$Num_level = $DB->num_rows($Query_level);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $JsMenu[Email_Exprot_List];//导出邮件列表?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
			 form1.submit();
	}
	
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_outputemail_act.php' method='post' >
  <input type="hidden" name="Action" value="Output">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $JsMenu[Email_Exprot_List];//导出邮件列表?>
                      </SPAN></TD>
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
                            <a href="desktop.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-feeback.gif" border=0 >&nbsp;<?php echo $Mail_Pack[ExportMailList];//导出?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
                          </TD></TR></TBODY></TABLE>
                    
                  </TD></TR></TBODY></TABLE>
            </TD>
          </TR>
      </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>
                          
                          <TR>
                            <TD noWrap align=right><input name="emailtype" type="radio" value="0" checked> </TD>
                            <TD width="38%" align=left noWrap>All</TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <?php
					if ($Num_level>0){
						while ($Result_level=$DB->fetch_array($Query_level))
						{
					?> 
                          <TR>
                            <TD noWrap align=right><input type="radio" name="emailtype" value="<?php echo $Result_level['level_id']; ?>"> </TD>
                            <TD colspan="4" align=left noWrap><?php echo $Result_level['level_name']; ?></TD>
                            </TR>
                          <?php
						}
					}
					 ?> 
                          <TR>
                            <TD colspan="5" align=right noWrap>&nbsp;  </TD>
                            </TR>
                          <TR>
                            <TD colspan="5" align=right noWrap>&nbsp;					    </TD>
                            </TR>
                          <TR>
                            <TD colspan="5" align=right noWrap>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
