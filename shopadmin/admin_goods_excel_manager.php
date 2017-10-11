<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Excel]?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		form1.action="admin_otherinfo_save.php";
		form1.submit();
    }
	function changecat(){
		form2.action="admin_otherinfo.php";
		//save();
		form2.submit();
	}	
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
<TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%" height="49">
              <TABLE width="231" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap>
                      <SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Excel]?></SPAN></TD>
                    </TR>
                  </TBODY>
              </TABLE></TD>
            <TD align=center width="50%">&nbsp;		  </TD>
            <TD align=right width="50%">&nbsp;	
              
              </TD>
            </TR>
          </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2 
                  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR align="center">
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap><table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="allborder">
                              <form name="ExportExcel" action="admin_goods_excelwrite.php" method="post" target="send_act_one">
                                <input type="hidden" name="Action" value="OutputExcel">
                                  <tr>
                                  <td align="center">&nbsp;</td>
                                  <td rowspan="2">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="center"><i class="icon-download-alt" style="font-size:14px"></i> 商品匯出</td>
                                  </tr>
                                
                                <tr>
                                  <td colspan="2" align="center">&nbsp;</td>
                                  </tr>
                                <tr>
                                  <td colspan="2" align="center">
                                    <input name="Submit" type="submit" class="bg-bottom" value="<?php echo $Admin_Product[BeginRun]?>" size="20"/>                          </td>
                                  </tr>
                                <tr>
                                  <td colspan="2">&nbsp;</td>
                                  </tr>
                                </form>
                              </table>
                              
                              <p>&nbsp;</p>
                              <form name="ExportExcel" action="admin_goods_excelread.php" method="post"  enctype="multipart/form-data" >
                                  <input type="hidden" name="Action" value="InputExcel">
                              <table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="allborder">
                                
                                  <tr>
                                    <td width="23%" align="right">&nbsp;</td>
                                    <td width="77%">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td width="23%" align="center"><i class="icon-upload-alt" style="font-size:14px"></i> <?php echo $Admin_Product[InputExcelProduct];?></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right">&nbsp;</td>
                                    <td>
                                      xls格式檔
                                      ：
                                      <input type="file" name="cvsEXCEL"  ID='cvsEXCEL' />
                                      <div id="cvsEXCELtips" class="tips"><?php echo $Admin_Product[UploadCsvIntro] ?></div>						  </td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" align="center">
                                      <input name="Submit" type="submit" class="bg-bottom" value="<?php echo $Admin_Product[BeginRun]?>" size="20"/></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">&nbsp;</td>
                                  </tr>
                               
                              </table>
                               </FORM>
                          <p>&nbsp;</p></TD></TR>
                        </TBODY></TABLE>
</div>
                      <div align="center"><?php include_once "botto.php";?></div>
					   <iframe name="send_act_one" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe>
</BODY>
</HTML>
