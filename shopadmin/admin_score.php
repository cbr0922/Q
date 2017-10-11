<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$score_id  = $FUNCTIONS->Value_Manage($_GET['score_id'],$_POST['score_id'],'back','');

$Sql         = "select gc.* ,g.goodsname from `{$INFO[DBPrefix]}score` gc  inner join `{$INFO[DBPrefix]}goods` g on (gc.gid=g.gid) where score_id=".intval($score_id)."  ".$Provider_string." limit 0,1 ";
//$Query       = $DB->query("select * from good_comment where comment_id=".intval($Comment_id)." limit 0,1");
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);

if ($Num>0){
	$Result    = $DB->fetch_array($Query);
	$GoodsName = $Result['goodsname'];
	$scoretime   = $Result['scoretime'];
	$content = nl2br($Result['content']);
	$answer  = $Result['answer'];
	$score1  = $Result['score1'];
	$ifcheck  = $Result['ifcheck'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;回覆評價</TITLE></HEAD>
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
				if ($_GET['score_id']!=""){
				?>
				autosave: 'admin_score_save.php?act=autosave&score_id=<?php echo $_GET['score_id'];?>',
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
<SCRIPT language=javascript>
	function checkform(){
		document.form1.action="admin_score_save.php";
		document.form1.submit();
	}		
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='' method=post  >
  <input type="hidden" name="Action" value="Update">
  <INPUT type=hidden  name='score_id' value="<?php echo $score_id?>"> 

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;回覆評價</SPAN></TD>
                  </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_score_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"   border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE><TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2 
                  width="100%" bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="12%">&nbsp;</TD>
                            <TD>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Product[ProductName];//商品名称?>：</TD>
                            <TD><?php echo  $GoodsName?> </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="12%"> 評價時間：</TD>
                            <TD><?php echo date("Y-m-d H: i a ",$scoretime)?>
                              </TD></TR>
                          <!--TR>
                      <TD noWrap align=right>評分：</TD>
                      <TD><?php echo $score1;?></TD>
                    </TR-->
                          <TR>
                            <TD noWrap align=right>審核狀態：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('radio','ifcheck',$ifcheck,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="12%"> 評價內容：</TD>
                            <TD><?php echo $content;?></TD></TR>
                          <TR>
                            <TD align=right valign="top" noWrap>回覆內容：</TD>
                            <TD>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>&nbsp;</TD>
                            <TD align=left valign="top" noWrap>
                              <TABLE width="91%" height="18"  border=0 align="left" cellPadding=0 cellSpacing=0>
                                <TBODY>
                                  <TR>
                                    <TD valign="top">
                                    <div  class="editorwidth">
                                    <textarea name="FCKeditor1" id="redactor" cols="30" rows="10"  style="width:800px !important;"><?php echo $answer;?></textarea>
                                    </div>
                                      </TD>
                                    </TR>
                                  </TBODY>
                                </TABLE></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="12%">&nbsp;</TD>
                            <TD>&nbsp; 
            </TD></TR></TBODY></TABLE>
</FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
