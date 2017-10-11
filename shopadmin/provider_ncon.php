<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
include "../language/".$INFO['IS']."/Article_Pack.php";
if ($_GET['provider_nid']!="" && $_GET['Action']=='Modi'){
	$provider_nid = intval($_GET['provider_nid']);
	$Action_value = "Update";
	$Action_say  = $Article_Pack[ModiProviderArticle]; //修改公告
	$Query = $DB->query("select provider_ntitle,provider_ncontent,provider_nfb from `{$INFO[DBPrefix]}provider_news` where provider_nid=".intval($provider_nid)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$provider_ntitle          =  $Result['provider_ntitle'];
		$provider_ncontent        =  $Result['provider_ncontent'];
		$provider_nfb             =  $Result['provider_nfb'];

	}else{
		echo "<script language=javascript>javascript:window.close();</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$Action_say  = $Article_Pack[AddProviderArticle]; //添加公告
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Provider_Man] ?>--&gt;<?php echo $Action_say ?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		if (chkblank(form1.provider_ntitle.value) || form1.provider_ntitle.value.length>50){
			form1.provider_ntitle.focus();
			alert('<?php echo $Article_Pack[PleaseInputProviderArticle_title]?>');  //请输入公告标题
			return;
		}
		form1.submit();
	}
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='provider_ncon_save.php' method=post enctype="multipart/form-data"> 
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="provider_nid" value="<?php echo $provider_nid?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Provider_Man] ?>--&gt;<?php echo $Action_say ?></SPAN></TD>
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
                            <a href="provider_ncon_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
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
                            <TD noWrap align=right width="18%">
                              </TD>
                            <TD colspan="4" align=right noWrap>
                              
                              </TD>
                            </TR>
                          
                          <TR>
                            <TD noWrap align=right><?php echo $Article_Pack[ProviderArticle_title];//公告标题?>：</TD>
                            <TD width="38%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_ntitle',$provider_ntitle,"      maxLength=50 size=50 ")?></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right \><?php echo $Basic_Command['Iffb'];//是否发布：?>：</TD>
                            <TD colspan="4"><?php echo $FUNCTIONS->Input_Box('radio','provider_nfb',$provider_nfb,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                            </TR>
                          
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD width="18%" align=right valign="top" noWrap><?php echo $Article_Pack[Article_Body];//内容?>：</TD>
                            <TD colspan="3" align=left valign="top" noWrap>
                              <?php
						
						//echo OtherPach."/".Resources."/ckeditor/";;
						$CKEditor = new CKEditor();
						$CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						
						$CKEditor->config['width'] = 700;
						$CKEditor->config['height'] = 400;
						//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
						echo $code = $CKEditor->editor("FCKeditor1", $provider_ncontent);

					   ?>
                              
                              </TD>
                            <TD width="9%" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>
                     </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
