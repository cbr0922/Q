<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

$Action_say  = "傭金設定"; //修改供应商
$Query = $DB->query("select * from `{$INFO[DBPrefix]}msgconfig` limit 0,1");
$Num   = $DB->num_rows($Query);
  
  if ($Num>0){
  	$Result= $DB->fetch_array($Query);
	$ip           =  $Result['ip'];
	$port           =  $Result['port'];
	$user           =  $Result['user'];
	$password           =  $Result['password'];
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>設置-->簡訊管理-->簡訊設置</TITLE>
</HEAD>
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
  <FORM name=form1 action='admin_msgconfig_save.php' method=post enctype="multipart/form-data">
<input type="hidden" name="Action" value="<?php echo $Action_value?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>設置-->簡訊管理-->簡訊設置</SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                        </TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
                            </TD></TR></TBODY></TABLE>
                    
                    </TD></TR></TBODY></TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff height=300>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD width="55%" align=right noWrap>&nbsp;</TD></TR>
                          <TR>
                            <TD colspan="2" align=left noWrap style="padding-left:180px"><table width="408" border="0" cellpadding="0" cellspacing="0" class="9pv">
                              <tr>
                                <td width="29"><img height=24 src="images/<?php echo $INFO[IS]?>/note01.gif" width=24></td>
                                <td width="379" class="p9black">1. 使用者須自行申請中華電信企業簡訊<br>
                                  2. 部份虛擬主機鎖Port:8000，需要向主機商申請 <br />
                                  3. 三竹簡訊須申請Port:80(http)發送模式</td>
                                </tr>
                              </table>
                              <br></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>SMS伺服器IP：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','ip',$ip,"      maxLength=50 size=50  ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>SMS伺服器Port：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','port',$port,"      maxLength=50 size=50  ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>帳號：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','user',$user,"      maxLength=50 size=50  ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>密碼：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','password',$password,"      maxLength=50 size=50  ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            </TR>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
    </TR>
                     </FORM>
</div>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
