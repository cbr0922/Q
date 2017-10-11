<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
if ($_GET['transport_id']!="" && $_GET['Action']=='Modi'){
	$Transport_id = intval($_GET['transport_id']);
	$Action_value = "Update";
	$Action_say   = $Admin_Product[ModiCarriageType];
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}transportation_special` where trid=".intval($Transport_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$name      =  $Result['name'];
		$type     =  $Result['type'];
		$content   =  $Result['content'];
		$image   =  $Result['image'];

	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增特殊配送方式";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
	<script type="text/javascript" src="../Resources/redactor-js-master/lib/jquery-1.9.0.min.js"></script>

	<!-- Redactor is here -->
	<link rel="stylesheet" href="../Resources/redactor-js-master/redactor/redactor.css" />
	<script src="../Resources/redactor-js-master/redactor/redactor.js"></script>
   <!-- Plugin -->
          <script src="/Resources/redactor-js-master/redactor/plugins/source.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/table.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fullscreen.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontsize.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontfamily.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontcolor.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/inlinestyle.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/video.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/properties.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/textdirection.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/imagemanager.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/alignment/alignment.js"></script>
          <link rel="stylesheet" href="../Resources/redactor-js-master/redactor/plugins/alignment/alignment.css" />
    <!--/ Plugin -->
    
	<script type="text/javascript">
	$(document).ready(
		function()
		{
			$('#redactor').redactor({
				imageUpload: '../Resources/redactor-js-master/demo/scripts/image_upload.php',
				imageManagerJson: '../Resources/redactor-js-master/demo/scripts/image_json.php',
				plugins: ['source','imagemanager', 'video','fontsize','fontcolor','alignment','fontfamily','table','textdirection','properties','inlinestyle','fullscreen'],
				imagePosition: true,
                imageResizable: true,
				<?php
				if ($_GET['transport_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_transportation_save.php?act=autosave&Transport_id=<?php echo $_GET['transport_id'];?>',
				callbacks: {
					autosave: function(json)
					{
						 console.log(json);
					}
				}
				<?php
				}
				?>
			});
		}
	);
	</script>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
	
	   if (chkblank(form1.name.value) || form1.name.value.length>100){
			form1.name.focus();
			alert('<?php echo $Admin_Product[PleaseInputCarriageName]?>');
			return;
		}
		
		form1.action="admin_transportation_save.php";
		form1.submit();
    }
</SCRIPT>
<div id="contain_out">
  <FORM name=form1 action='admin_transportation_save.php' method=post enctype="multipart/form-data" >
    <?php  include_once "Order_state.php";?>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="Transport_id" value="<?php echo $Transport_id?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $Action_say?></SPAN></TD>
                </TR></TBODY></TABLE></TD>  
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
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
                                <a href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
        </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2 
                  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>&nbsp;</TD>
                            <TD align="left" valign="top" noWrap>&nbsp;</TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD width="18%" align="right" valign="middle" noWrap> <?php echo $JsMenu[Send_Type];//配送方式?>：</TD>
                            <TD align="left" valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','name',$name,"      maxLength=50 size=50  ")?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right \>圖片：</TD>
                            <TD><INPUT  id="image"  type="file" size="40" name="image" ><INPUT type=hidden   name='old_image'  value="<?php echo $image?>"></TD>
                            </TR>
                          <?php if (is_file("../".$INFO['good_pic_path']."/".$image)){?>
                          <TR>
                            <TD align=right \>&nbsp;</TD>
                            <TD><img src="<?php echo "../".$INFO['good_pic_path']."/".$image?>"></TD>
                            </TR>
                          <?php
					}
					?>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap> <?php echo $Admin_Product[Detail_intro] ; //详细介绍?>：</TD>
                            <TD colspan="2" align="left" valign="top" noWrap>
                            <div  class="editorwidth">
							<textarea name="FCKeditor1" id="redactor" cols="30" rows="10"><?php echo $content;?></textarea>
                            </div>
						</TD>
                            </TR>
                        </TBODY></TABLE></TD>
                </TR></TBODY></TABLE></TD>
        </TR></TBODY></TABLE></TD>
    </TR>
  </FORM>
</div>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
