<?php
include_once "Check_Admin.php";
$PSql      = "select * from `{$INFO[DBPrefix]}paymanager` as p where p.pid='" . $_GET['pid'] . "' order by p.pid  ";
$PQuery    = $DB->query($PSql);
$PRs= $DB->fetch_array($PQuery);
$payname = $PRs['payname'];
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_GET['id']!="" && $_GET['Action']=='Modi'){
	$id = intval($_GET['id']);
	$Action_value = "Update";
	$Action_say  = "修改金流" ;//修改問題類別
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}paymethod` where mid=".intval($id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$methodname            =  $Result['methodname'];
		$content            =  $Result['content'];
		$payno            =  $Result['payno'];
		$pid            =  $Result['pid'];
		$ifopen            =  $Result['ifopen'];
		$month            =  $Result['month'];
		$showtype   =  $Result['showtype'];
		$orderby   =  $Result['orderby'];
		$ifcanappoint   =  $Result['ifcanappoint'];
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
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>設置--&gt;金流管理--&gt;支付方式</TITLE>
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
				autosave: 'admin_paymethod_save.php?act=autosave&id=<?php echo $_GET['id'];?>',
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

		if (chkblank(form1.methodname.value)){
			form1.methodname.focus();
			alert('請填寫名稱');  //请选择問題類別名稱			
			return;
		}
	
		form1.submit();
	}
	function checkform1(){

		if (chkblank(form1.methodname.value)){
			form1.methodname.focus();
			alert('請填寫名稱');  //请选择問題類別名稱			
			return;
		}
		form1.submit();
	}

</SCRIPT>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_paymethod_save.php' method=post >
    <input type="hidden" name="Action" value="<?php echo $Action_value?>">
    <input type="hidden" name="id" value="<?php echo $id?>">
    <input type="hidden" name="pid" value="<?php echo $pid?>">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
      <TBODY>
        <TR>
          <TD width="50%"><TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
                <TR>
                  <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                  <TD class=p12black noWrap><SPAN  class=p9orange>設置--&gt; <a href="admin_paymanager_list.php" style="FONT-SIZE: 11px; COLOR: #ff6600;">金流管理</a>--&gt; <a href="admin_paymethod_list.php?id=<?php echo $pid;?>" style="FONT-SIZE: 11px; COLOR: #ff6600;"><?php echo $payname;?></a> --&gt; 支付方式</SPAN></TD>
                </TR>
              </TBODY>
            </TABLE></TD>
          <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=middle><TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_paymanager_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD>
                                </TR>
                              </TBODY>
                            </TABLE>
                            <!--BUTTON_END--></TD>
                        </TR>
                      </TBODY>
                    </TABLE></TD>
                  <TD align=middle><TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD>
                                </TR>
                              </TBODY>
                            </TABLE>
                            <!--BUTTON_END--></td>
                        </TR>
                      </TBODY>
                    </TABLE></TD>
                </TR>
              </TBODY>
            </TABLE></TD>
        </TR>
      </TBODY>
    </TABLE>
    <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
      <TBODY>
        <TR>
          <TD vAlign=top height=262><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
                <TR>
                  <TD vAlign=top bgColor=#ffffff height=300><TABLE class=allborder cellSpacing=0 cellPadding=2 width="100%" align=center bgColor=#f7f7f7 border=0>
                      <TBODY>
                        <TR>
                          <TD noWrap align=right width="18%">&nbsp;</TD>
                          <TD colspan="2" align=right noWrap>&nbsp;</TD>
                        </TR>
                        <TR>
                          <TD noWrap align=right>支付方式：</TD>
                          <TD height="25" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','methodname',$methodname,"      maxLength=100 size=50 ")?></TD>
                        </TR>
                        <TR>
                          <TD noWrap align=right>編號：</TD>
                          <TD height="25" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','payno',$payno,"      maxLength=100 size=10 ")?></TD>
                        </TR>
                        <TR>
                          <TD noWrap align=right width="18%">分期數：</TD>
                          <TD height="25" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','month',$month,"      maxLength=100 size=10 ")?></TD>
                        </TR>
                        <TR>
                          <TD noWrap align=right>狀態：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio_strand','ifopen',$ifopen,$add = array("關閉","開啟"))?></TD>
                        </TR>
                        <TR>
                          <TD noWrap align=right>接受預定：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio_strand','ifcanappoint',$ifcanappoint,$add = array("關閉","開啟"))?></TD>
                        </TR>
                        <TR>
                          <TD noWrap align=right>類型：</TD>
                          <TD colspan="2" align=left noWrap>
                          <?php 
					  $type_array = explode(",",$showtype);
					  ?>

                              <input name="type[]" type="checkbox" id="type" value="1" <?php if(in_array(1,$type_array)) echo "checked";?> />

                              電腦

                              <input name="type[]" type="checkbox" id="type" value="2" <?php if(in_array(2,$type_array)) echo "checked";?> />

                              手機
                          &nbsp;</TD>
                        </TR>
                        <TR align="center">
                            <TD align="right" valign="top" noWrap>排序：</TD>
                            <TD colspan="2" align="left" valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','orderby',$orderby,"      maxLength=50 size=5  ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>說明：</TD>
                          <TD colspan="2" align=left noWrap>
						    <p>
                            <div  class="editorwidth">
						      <textarea name="content" id="redactor" cols="30" rows="10"  style="width:800px !important;"><?php echo $content;?></textarea>
                              </div>
						      <?php
						
						//echo OtherPach."/".Resources."/ckeditor/";;
						$CKEditor = new CKEditor();
						$CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						
						$CKEditor->config['width'] = 700;
						$CKEditor->config['height'] = 250;
						//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
						echo $code = $CKEditor->editor("content", $content);

					   ?>
						    </p>
					      <p>&nbsp; </p></TD>
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
                  </TABLE></TD>
                </TR>
              </TBODY>
            </TABLE></TD>
        </TR>
      </TBODY>
    </TABLE>
  </FORM>
</div>
<div align="center">
  <?php include_once "botto.php";?>
</div>
</BODY>
</HTML>
