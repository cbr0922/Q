<?php
include_once "Check_Admin.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_GET['id']!="" && $_GET['Action']=='Modi'){
	$id = intval($_GET['id']);
	$Action_value = "Update";
	$Action_say  = "修改金流" ;//修改問題類別
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}paymanager` where pid=".intval($id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$payname            =  $Result['payname'];
		$content            =  $Result['content'];
		$shopcode            =  $Result['shopcode'];
		$f1            =  $Result['f1'];
		$f2            =  $Result['f2'];
		$f3            =  $Result['f3'];
		$f4            =  $Result['f4'];
		$f5            =  $Result['f5'];
		$month            =  $Result['month'];
		$payurl            =  $Result['payurl'];
		$returnurl            =  $Result['returnurl'];
		$returnurl2            =  $Result['returnurl2'];
		$paytype            =  $Result['paytype'];
		$f1_array = explode("|",$f1);
		$f2_array = explode("|",$f2);
		$f3_array = explode("|",$f3);
		$f4_array = explode("|",$f4);
		$f5_array = explode("|",$f5);
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增金流"; ///添加問題類別
	$status  = 1;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>設置--&gt;金流管理</TITLE>
<script language="javascript" src="../js/TitleI.js"></script>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
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
				if ($_GET['id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_paymanager_save.php?act=autosave&id=<?php echo $_GET['id'];?>',
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

		if (chkblank(form1.payname.value)){
			form1.payname.focus();
			alert('請填寫名稱');  //请选择問題類別名稱			
			return;
		}
	
		form1.submit();
	}
	function checkform1(){

		if (chkblank(form1.payname.value)){
			form1.payname.focus();
			alert('請填寫名稱');  //请选择問題類別名稱			
			return;
		}
		form1.submit();
	}

</SCRIPT>

<div id="contain_out">
  <FORM name=form1 action='admin_paymanager_save.php' method=post >
    <? include "Order_state.php";?>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="id" value="<?php echo $id?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>設置--&gt;金流管理</SPAN></TD>
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
                            <a href="admin_paymanager_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                                  </TR></TBODY></TABLE><!--BUTTON_END-->
                            
                            </td>
                          
                          </TR></TBODY></TABLE>
                    
                    </TD></TR></TBODY></TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>

                      <TABLE class=allborder cellSpacing=0 cellPadding=2 width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>
                          
                          <TR>
                            <TD noWrap align=right>金流名稱：</TD>
                            <TD height="25" colspan="2" align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','payname',$payname,"      maxLength=100 size=50 ")?>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>類型：</TD>
                            <TD height="25" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio_strand','paytype',$paytype,$add = array("在線支付","離線支付"))?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%">商家帳號：</TD>
                            <TD height="25" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','shopcode',$shopcode,"      maxLength=100 size=50 ")?></TD>
                            </TR>
                          
                          <TR>
                            <TD noWrap align=right>支付網址：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','payurl',$payurl,"      maxLength=200 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>回傳網址1：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','returnurl',$returnurl,"      maxLength=200 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>回傳網址2：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','returnurl2',$returnurl2,"      maxLength=200 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>擴充欄位：</TD>
                            <TD colspan="2" align=left noWrap>欄位名稱：<?php echo $FUNCTIONS->Input_Box('text','f1_1',$f1_array[0],"      maxLength=50 size=20 ")?>值：<?php echo $FUNCTIONS->Input_Box('text','f1_2',$f1_array[1],"      maxLength=50 size=20 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>欄位名稱：<?php echo $FUNCTIONS->Input_Box('text','f2_1',$f2_array[0],"      maxLength=50 size=20 ")?>值：<?php echo $FUNCTIONS->Input_Box('text','f2_2',$f2_array[1],"      maxLength=50 size=20 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>欄位名稱：<?php echo $FUNCTIONS->Input_Box('text','f3_1',$f3_array[0],"      maxLength=50 size=20 ")?>值：<?php echo $FUNCTIONS->Input_Box('text','f3_2',$f3_array[1],"      maxLength=50 size=20 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>欄位名稱：<?php echo $FUNCTIONS->Input_Box('text','f4_1',$f4_array[0],"      maxLength=50 size=20 ")?>值：<?php echo $FUNCTIONS->Input_Box('text','f4_2',$f4_array[1],"      maxLength=50 size=20 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>欄位名稱：<?php echo $FUNCTIONS->Input_Box('text','f5_1',$f5_array[0],"      maxLength=50 size=20 ")?>值：<?php echo $FUNCTIONS->Input_Box('text','f5_2',$f5_array[1],"      maxLength=50 size=20 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>說明：</TD>
                            <TD colspan="2" align=left noWrap>
                            <div  class="editorwidth">
                              <textarea name="content" id="redactor" cols="30" rows="10"  style="width:800px !important;"><?php echo $content;?></textarea>
                              </div>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          </TBODY>
 </TABLE>
                     </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
