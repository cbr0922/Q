<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
if ($_GET['subject_id']!="" && $_GET['Action']=='Modi'){
	$subject_id = intval($_GET['subject_id']);
	$Action_value = "Update";
	$Action_say  = $Admin_Product[ModiSubjectName]; //修改
	$Query = $DB->query("select subject_name,subject_open,subject_num,subject_content,salecount from `{$INFO[DBPrefix]}sale_subject` where subject_id=".intval($subject_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Subject_name      =  $Result['subject_name'];
		$Subject_open      =  $Result['subject_open'];
		$Subject_num       =  $Result['subject_num'];
		$Subject_content   =  $Result['subject_content'];
		$salecount   =  $Result['salecount'];
	}else{
		echo "<script language=javascript>javascript:window.history.back(-1);</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$Action_say   = $Admin_Product[AddSubjectName];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;折扣主題--&gt;添加折扣主題</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
	
	   if (chkblank(form1.subject_name.value) || form1.subject_name.value.length>100){
			form1.subject_name.focus();
			alert('<?php echo $Admin_Product[PleaseInputSubjectName]?>');
			return;
		}
		form1.action="admin_salesubject_save.php";
		form1.submit();
    }
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_salesubject_save.php' method=post >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="subject_id" value="<?php echo $subject_id?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;折扣主題--&gt;新增折扣主題</SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD width="50%" align=right valign="top"><TABLE cellSpacing=0 cellPadding=0 border=0>
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
                                    <a href="admin_subject_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"  border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE>
                            <!--BUTTON_END-->							
                            </TD>
                          <TD align=middle width=79><TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                            <TBODY>
                              <TR>
                                <TD align=middle width=79><TABLE>
                                  <TBODY>
                                    <TR>
                                      <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;
                                        <?php echo $Basic_Command['Save'];//保存?></a></TD>
                                      </TR>
                                    </TBODY>
                                  </TABLE>                              <!--BUTTON_END--></TD>
                                </TR>
                              </TBODY>
                            </TABLE></TD>
                          </TR></TBODY></TABLE>
                    
                    </TD></TR></TBODY></TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2 
                  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>&nbsp;</TD>
                            <TD align="left" valign="top" noWrap>&nbsp;</TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD width="18%" align="right" valign="middle" noWrap><?php echo $Admin_Product[Subject_name]?>：</TD>
                            <TD align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','subject_name',$Subject_name,"      maxLength=50 size=50  ")?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap> <?php echo $Basic_Command['Iffb'];//是否發佈?>：</TD>
                            <TD align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('radio','subject_open',$Subject_open,$Add=array($Basic_Command['Open'],$Basic_Command['Close']))?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>多件折扣數量：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','salecount',$salecount,"      maxLength=5 size=5  ")?></TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>  <?php echo $Basic_Command['SNo_say']; //序号?>： </TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','subject_num',$Subject_num,"      maxLength=5 size=5  ")?></TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap><?php echo $Admin_Product[Detail_intro]?></TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php
						
						//echo OtherPach."/".Resources."/ckeditor/";;
						$CKEditor = new CKEditor();
						$CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						
						$CKEditor->config['width'] = 700;
						$CKEditor->config['height'] = 400;
						//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
						echo $code = $CKEditor->editor("FCKeditor1", $Subject_content);

					   ?></TD>
                            </TR>
                        </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
