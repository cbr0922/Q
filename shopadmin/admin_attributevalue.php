<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
$attrid = intval($_GET['attrid']);
if ($_GET['valueid']!="" && $_GET['Action']=='Modi'){
	$valueid = intval($_GET['valueid']);
	$Action_value = "Update";
	$Action_say  = "修改類別屬性值"; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}attributevalue` where valueid=".intval($valueid)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$valuename =  $Result['value'];
		$attrid  =  $Result['attrid'];
		$content  =  $Result['content'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增類別屬性值" ; //插入
}
$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}attribute` where attrid=".intval($attrid)." limit 0,1");
$Num_goods   = $DB->num_rows($Query_goods);
if ($Num_goods>0){
	$Result_goods= $DB->fetch_array($Query_goods);
	$attributename  =  $Result_goods['attributename'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>商品類別屬性管理--&gt;<?php echo $Action_say;?>--&gt;<?php echo $attributename;?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
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
				if ($_GET['valueid']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_attributevalue_save.php?act=autosave&valueid=<?php echo $_GET['valueid'];?>',
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
		//form1.action="admin_pcat_act.php?action=add";
		form1.submit();
	}
	
	function changecat(bid){
		//form1.action="admin_pcat.php?Action=Modi&bid="+bid;
		//form1.action="admin_pcat.php?Action=Insert&bid="+bid;		
		//form1.submit();
		location.href="admin_attributevalue_save.php?Action=Insert&bid="+bid;
	}
</SCRIPT>

<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_attributevalue_save.php' method=post> 
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="valueid" value="<?php echo $valueid?>">
  <input type="hidden" name="attrid" value="<?php echo $attrid?>">

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p9orange noWrap>商品類別屬性管理 --&gt; <?php echo $Action_say;?> --&gt; <a href="admin_attributevalue_list.php?attrid=<?php echo $attrid?>" style="font-size:11px;color:#ff6600;"><?php echo $attributename;?></a></TD>
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
                            <a href="admin_attributevalue_list.php?attrid=<?php echo $attrid;?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrapv class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
                            </TD></TR></TBODY></TABLE>
                    
                    </TD></TR></TBODY></TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2   width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              商品類別屬性值：</TD>
                            <TD noWrap align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','valuename',$valuename," maxLength=50 size=40 ")?>					  </TD>
                            </TR>
                          
                          
                          <TR>
                            <TD noWrap align=right width="18%">說明：</TD>
                            <TD noWrap align=left>
                            <div class="editorwidth">
							<textarea name="content" id="redactor" cols="30" rows="10" ><?php echo $content;?></textarea>
                            </div>
							</TD></TR>
                          <TR>
                            <TD colspan="2" align=center noWrap>&nbsp;</TD>
                          </TR>
                    </TBODY></TABLE>
                      </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
