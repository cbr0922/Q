<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Watermark.php";
include ConfigDir."/cache/setwatermark.php";

if (trim($INFO[SystemWaterMark])=='pic'){
	$Display_pic="style=\"DISPLAY: display\"";
	$Display_text="style=\"DISPLAY: none\"" ;
}else if (trim($INFO[SystemWaterMark])=='txt'){
	$Display_pic="style=\"DISPLAY: none\"";
	$Display_text="style=\"DISPLAY: display\"" ;
}else{
	$Display_pic="style=\"DISPLAY: none\"";
	$Display_text="style=\"DISPLAY: none\"" ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_WaterMark];//商品水印?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
	function checkform(){
		form1.submit();
	}	
    function view(obj,obj2,v){
	 if (v=='1'){
	   obj.style.display="";
	   obj2.style.display="none";
	 }
	}
</SCRIPT>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_WaterMark];//商品水印?></SPAN></TD>
              </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <!--TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<a href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END</TD></TR></TBODY></TABLE></TD-->
                  
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                                    <a  href="javascript:checkform();">
                                      <IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                                </TR>
                              </TBODY>
                            </TABLE><!--BUTTON_END-->
                          </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </TD>
                  
                </TR>
              </TBODY>
              </TABLE>
            </TD>
          </TR>
        </TBODY>
  </TABLE>

  <FORM name="form1" action='admin_goods_watermark_manager_save.php' method="post"  enctype="multipart/form-data" >
    <input type="hidden" name="Action" value="Modi">
                <input type="hidden" name="Old_WatermarkPicfile" value="<?php echo $INFO[WatermarkPicfile];?>">
    <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
              <TBODY>
                <TR>
                  <TD width="14%" align=right noWrap>&nbsp;</TD>
                  <TD colspan="3" align=right noWrap>&nbsp;</TD></TR>
                <TR>
                  <TD align=left noWrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Watermark[Watermark_type_say]?>：<!--水印类型： -->
                    <input type="radio" id="water_type" name="water_type" value="pic" <?php if ($INFO[SystemWaterMark] ==  "pic") { echo " checked ";}?> onClick="view(water_pic_type,water_text_type,1)" /><?php echo $Watermark[pic_type];?>
                    <input name="water_type" id="water_type" type="radio" value="txt" <?php if ( $INFO[SystemWaterMark] ==  "txt") { echo " checked ";}?>  onclick="view(water_text_type,water_pic_type,1)"/><?php echo $Watermark[text_type];?>   
                    <input name="water_type" id="water_type" type="radio" value="" <?php if ( !isset($INFO[SystemWaterMark]) or $INFO[SystemWaterMark] ==  "") { echo " checked ";}?>  onclick="water_text_type.style.display='none';water_pic_type.style.display='none';"/>無水印
                  </TD>
                  <TD width="11%" align=left noWrap>					      </TD>
                  <TD width="37%" align=right noWrap>&nbsp;</TD>
                  <TD align=left noWrap>&nbsp;</TD>
                </TR>
                <TR>
                  <TD align=right noWrap>&nbsp;</TD>
                  <TD height="22" align=left noWrap>&nbsp;</TD>
                  <TD align=right noWrap>&nbsp;</TD>
                  <TD align=left noWrap>&nbsp;</TD>
                </TR>
                            
                <TR id="water_text_type" <?php echo $Display_text;?>>
                  <TD colspan="5" align=center noWrap>
                    <!---------------------------------------------------pic----------------->
                    <table width="90%" border="0" class="allborder">
                      <TR>
                        <TD height="40" align=center noWrap><?php echo $Watermark[text_intro];?></TD>
                      </TR>
                    </table>
                                
                  <!---------------------------------------------------------------------->					  </TD>
                </TR>
                            
                <TR id="water_pic_type" <?php echo $Display_pic;?>>
                  <TD colspan="5" align=center noWrap>
                    <!---------------------------------------------------pic----------------->
                    <table width="90%" border="0" class="allborder">
                      <TR>
                        <TD height="40" align=right noWrap>水印圖片大小：</TD>
                        <TD colspan="3" align=left noWrap>
                        小圖<?php echo $FUNCTIONS->Input_Box('text','Watermark_pic_small',$INFO['Watermark_pic_small'],"          maxLength=30 size=3  ")?>px | 中圖<?php echo $FUNCTIONS->Input_Box('text','Watermark_pic_middle',$INFO['Watermark_pic_middle'],"          maxLength=30 size=3  ")?>px | 大圖<?php echo $FUNCTIONS->Input_Box('text','Watermark_pic_big',$INFO['Watermark_pic_big'],"          maxLength=30 size=3  ")?>px
                        </TD>
                      </TR>
                      <TR>
                        <TD width="25%" height="40" align=right noWrap><?php echo $Watermark[watermark_transition]?>：<!--水印图片与原图片的融合度 --></TD>
                        <TD width="25%" align=left noWrap>
                          <input name="watermark_transition" type="text"   id="watermark_transition" value="<?php echo trim($INFO[watermark_transition])!="" ? intval($INFO[watermark_transition]) : 50 ; ?>">
                          <div id="watermark_transitiontips" class="tips_big"><?php echo $Watermark[watermark_transition_intro] ?></div>					  </TD>
                        <TD width="3%" align=right noWrap><?php echo $Watermark[watermark_pic_where_say]?>：<!--水印图片放置的位置： --></TD>
                        <TD width="30%" align=left noWrap>
                          <select  name="WatermarkWhere"  class="trans-input">
                            <option value="1" <?php if (intval($INFO[WatermarkWhere])==1) { echo " selected ";}?>>左上</option>
                          
                            <option value="2" <?php if (intval($INFO[WatermarkWhere])==2) { echo " selected ";}?>>左下</option>
                            
                            <option value="4" <?php if (intval($INFO[WatermarkWhere])==4) { echo " selected ";}?>>右下</option>
                            
                            <option value="3" <?php if (intval($INFO[WatermarkWhere])==3) { echo " selected ";}?>>右上</option>						
                          </select>                     </TD>
                      </TR>
                      <TR>
                        <TD noWrap align=right><?php echo $Watermark[WatermarkPic]?>：<!--水印图片： --></TD>
                        <TD colspan="3" align=left noWrap><input name="WatermarkPicfile" type="file"   id="WatermarkPicfile" >
                          <div id="WatermarkPicfiletips" class="tips"><?php echo $Watermark[WatermarkPic_intro] ?></div>  <?php echo $Watermark[WatermarkPic_intro];?></TD>
                      </TR>
                      <TR>
                        <TD noWrap align=right>&nbsp;</TD>
                        <TD colspan="3" align=left noWrap>
                          <?php if  ( $INFO[WatermarkPicfile]!="" ) {?>
                          <img src="../UploadFile/UserFiles/<?php echo $INFO[WatermarkPicfile];?>" border="0">
                          <?php  } ?>
                        </TD>
                      </TR>
                    </table>
                    
                  <!---------------------------------------------------------------------->					  </TD>
                </TR>					
                            
                <TR>
                  <TD align=right noWrap>&nbsp;</TD>
                  <TD height="22" align=left noWrap>&nbsp;</TD>
                  <TD align=right noWrap>&nbsp;</TD>
                  <TD align=left noWrap>&nbsp;</TD>
                </TR>
                <TR>
                  <TD align=right noWrap>&nbsp;</TD>
                  <TD colspan="3" align=left noWrap>&nbsp;</TD>
                </TR>
        </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>

