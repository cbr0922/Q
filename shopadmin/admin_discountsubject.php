<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
if ($_GET['subject_id']!="" && $_GET['Action']=='Modi'){
	$subject_id = intval($_GET['subject_id']);
	$Action_value = "Update";
	$Action_say  = "修改活動主題"; //修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}discountsubject` where dsid=".intval($subject_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Subject_name      =  $Result['subject_name'];
		$Subject_open      =  $Result['subject_open'];
		$start_date       =  $Result['start_date'];
		$end_date   =  $Result['end_date'];
		$min_money   =  $Result['min_money']==0?"":$Result['min_money'];
		$min_count   =  $Result['min_count']==0?"":$Result['min_count'];
		$Subject_content   =  $Result['subject_content'];
		$mianyunfei   =  $Result['mianyunfei']==0?"":$Result['mianyunfei'];
		$template   =  $Result['template'];
		$remark   =  $Result['remark'];
		$point   =  $Result['point'];
		$saleoff   =  $Result['saleoff'];
		$buycount   =  $Result['buycount'];
		$buyprice   =  $Result['buyprice'];
		$buytype   =  $Result['buytype'];
		$pic   =  $Result['pic'];
		$presentgid   =  $Result['presentgid']==0?"":$Result['presentgid'];
		$level1money   =  $Result['level1money']==0?"":$Result['level1money'];
		$level2money_min   =  $Result['level2money_min']==0?"":$Result['level2money_min'];
		$level2money_max   =  $Result['level2money_max']==0?"":$Result['level2money_max'];
		$level2gid   =  $Result['level2gid']==0?"":$Result['level2gid'];
		$level3money   =  $Result['level3money']==0?"":$Result['level3money'];
		$level3gid   =  $Result['level3gid']==0?"":$Result['level3gid'];
	}else{
		echo "<script language=javascript>javascript:window.history.back(-1);</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$Action_say   = "新增活動主題";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;活動主題--&gt;<?php echo $Action_say;?></TITLE></HEAD>
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
				if ($_GET['subject_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_discountsubject_save.php?act=autosave1&subject_id=<?php echo $_GET['subject_id'];?>',
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
			$('#redactor1').redactor({
				imageUpload: '../Resources/redactor-js-master/demo/scripts/image_upload.php',
				imageManagerJson: '../Resources/redactor-js-master/demo/scripts/image_json.php',
				plugins: ['source','imagemanager', 'video','fontsize','fontcolor','alignment','fontfamily','table','textdirection','properties','inlinestyle','fullscreen'],
				imagePosition: true,
                imageResizable: true,
				<?php
				if ($_GET['subject_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_discountsubject_save.php?act=autosave2&subject_id=<?php echo $_GET['subject_id'];?>',
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
		var re = /^[0-9]+.?[0-9]*$/;
	   if (chkblank(form1.subject_name.value) || form1.subject_name.value.length>100){
			form1.subject_name.focus();
			alert('<?php echo $Admin_Product[PleaseInputSubjectName]?>');
			return;
		}/*
		if(chkblank(form1.buycount.value)){
			form1.buycount.focus();
			alert('請填寫折扣類型');
			return;
		}
		
		if (!re.test(form1.buycount.value))
    	{
       	 alert("請填寫折扣類型");
       	 form1.buycount.focus();
       	 return false;
     	}
		if(form1.buycount.value<1){
			form1.buycount.focus();
			alert('請填寫折扣類型');
			return;
		}
		if(chkblank(form1.buyprice.value)){
			form1.buyprice.focus();
			alert('請填寫折扣類型');
			return;
		}
		if (!re.test(form1.buyprice.value))
    	{
       	 alert("請填寫折扣類型");
       	 form1.buyprice.focus();
       	 return false;
     	}
		if(form1.buyprice.value<1){
			form1.buyprice.focus();
			alert('請填寫折扣類型');
			return;
		}
		*/
		form1.action="admin_discountsubject_save.php";
		form1.submit();
    }
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_discountsubject_save.php' method=post  enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="subject_id" value="<?php echo $subject_id?>">
    <input type="hidden" name="Old_pic" value="<?php echo $pic?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;活動主題--&gt;<?php echo $Action_say;?></SPAN></TD>
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
                                    <a href="admin_discountsubject_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"  border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD>
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
        </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff>
                      <br>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2 
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
                            <TD align="right" valign="middle" noWrap>活動日期：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','start_date',$start_date," id=start_date   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 readonly=readonly ")?>至<?php echo $FUNCTIONS->Input_Box('text','end_date',$end_date," id=end_date   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"  readonly=readonly  maxLength=12 size=12 ")?></TD>
                          </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>折扣類型：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><input type="radio" name="buytype" id="buytype" value="0" onclick="setSaleoff(0)" <?php if($buytype==0) echo "checked";?> />折扣
                            <input type="radio" name="buytype" id="buytype" value="1" onclick="setSaleoff(1)" <?php if($buytype==1) echo "checked";?> />金額
                            <input type="radio" name="buytype" id="buytype" value="2" onclick="setSaleoff(2)" <?php if($buytype==2) echo "checked";?> />滿額折扣</TD>
                          </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>&nbsp;</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><div id="showSaleoff">買<?php echo $FUNCTIONS->Input_Box('text','buycount',$buycount,"      maxLength=10 size=10  ")?>件，<?php echo $FUNCTIONS->Input_Box('text','buyprice',$buyprice,"      maxLength=10 size=10  ")?>元 / %折扣 <a href="#" class="easyui-tooltip" title="一、9折請填寫90，85折請填寫85。<br/>若選擇折扣不需打折請填寫100"><img src="images/tip.png" width="16" height="16" border="0"></a></div>
                            <div id="showSaleoff2"></div>
                            </TD>
                          </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>  最小購買金額： </TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','min_money',$min_money,"      maxLength=10 size=10  ")?></TD>
                          </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>最小購買數量：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','min_count',$min_count,"      maxLength=10 size=5  ")?></TD>
                          </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>免運費金額：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','mianyunfei',$mianyunfei,"      maxLength=10 size=10  ")?></TD>
                          </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap> 贈送紅利：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','point',$point,"      maxLength=10 size=10  ")?>點</TD>
                          </TR>
                          <!--TR align="center">
                      <TD align="right" valign="middle" noWrap>現金折扣：</TD>
                      <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','saleoff',$saleoff,"      maxLength=10 size=10  ")?></TD>
                    </TR-->
                    <TR align="center">
                            <TD align="right" valign="middle" noWrap>贈品：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap>購買總金額<?php echo $FUNCTIONS->Input_Box('text','level1money',$level1money,"      maxLength=10 size=10  ")?>以下，送<?php echo $FUNCTIONS->Input_Box('text','presentgid',$presentgid,"      maxLength=10 size=10  ")?>&nbsp;&nbsp;<a href="#" class="easyui-tooltip" title="請填商品ID"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>&nbsp;</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap>購買總金額<?php echo $FUNCTIONS->Input_Box('text','level2money_min',$level2money_min,"      maxLength=10 size=10  ")?>~<?php echo $FUNCTIONS->Input_Box('text','level2money_max',$level2money_max,"      maxLength=10 size=10  ")?>，送<?php echo $FUNCTIONS->Input_Box('text','level2gid',$level2gid,"      maxLength=10 size=10  ")?>&nbsp;</TD>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>&nbsp;</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap>購買總金額<?php echo $FUNCTIONS->Input_Box('text','level3money',$level3money,"      maxLength=10 size=10  ")?>以上，送<?php echo $FUNCTIONS->Input_Box('text','level3gid',$level3gid,"      maxLength=10 size=10  ")?>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>圖片：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><input name="ncimg" type="file" id="ncimg"  size="40" /></TD>
                            <?php if ($_GET['Action']=='Modi' && $pic!="") {?>
                          <TR>
                              <TD align=right>&nbsp;</TD>
                              <TD><img src="../UploadFile/LogoPic/<?php echo $pic?>"><a href="admin_discountsubject_save.php?subject_id=<?php echo $subject_id;?>&pic=<?php echo $pic;?>&type=pic1&Action=delPic">刪除圖片</a></TD>
                          </TR>
                          <?php } ?>
                          
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>模板文件：</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','template',$template,"      maxLength=255 size=20  ")?></TD>
                          </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap><?php echo $Admin_Product[Detail_intro]?></TD>
                            <TD colspan="2" align="left" valign="middle" noWrap>
                            <div  class="editorwidth">
                            <textarea name="FCKeditor1" id="redactor" cols="30" rows="10"  style="width:800px !important;"><?php echo $Subject_content;?></textarea></div>
                            </TD>
                          </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>備註</TD>
                            <TD colspan="2" align="left" valign="middle" noWrap>
                               <div  class="editorwidth">
                                <textarea name="remark" id="redactor1" cols="30" rows="10"  style="width:800px !important;"><?php echo $remark;?></textarea>
                              </div>
                              <p>&nbsp; </p>
                            </TD>
                          </TR>
                        </TBODY></TABLE>
</FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
<script language="javascript">
function saveSaleoff(){
	$.ajax({
				url: "admin_discountsubject_save.php",
				data: 'money=' + $('#s_money').val()+'&saleoff=' + $('#s_saleoff').val()+'&subject_id=<?php echo intval($_GET['subject_id']);?>&act=SaveSale',
				type:'post',
				dataType:"html",
				success: function(msg){
					showSalfe();
				}
			});	
}
function UpdateSaleoff(id){
	$.ajax({
				url: "admin_discountsubject_save.php",
				data: 'money=' + $('#money'+id).val()+'&saleoff=' + $('#saleoff'+id).val()+'&id=' + id +'&subject_id=<?php echo intval($_GET['subject_id']);?>&act=UpdateSale',
				type:'post',
				dataType:"html",
				success: function(msg){
					showSalfe();
				}
			});	
}
function DelSaleoff(id){
	$.ajax({
				url: "admin_discountsubject_save.php",
				data: 'id=' + id +'&subject_id=<?php echo intval($_GET['subject_id']);?>&act=DelSale',
				type:'post',
				dataType:"html",
				success: function(msg){
					showSalfe();
				}
			});	
}
function setSaleoff(type){
	<?php
       if ($Action_value == "Update"){
	?>
	$("#showSaleoff2").css("display","none");
	<?php
		}
	?>
	if(type==1||type==0){
		$("#showSaleoff2").css("display","none");	
		$("#showSaleoff").css("display","block");	
	}
	if(type==2){
		$("#showSaleoff2").css("display","block");	
		$("#showSaleoff").css("display","none");	
	}
}
function showSalfe(){
	$.ajax({
		url: "admin_discountsubject_ajax_saleoff.php",
		data: 'subject_id=<?php echo intval($_GET['subject_id']);?>',
		type:'get',
		dataType:"html",
		success: function(msg){
			$("#showSaleoff2").html(msg);
		}
	});		
}
<?php
if ($Action_value == "Update"){
?>
showSalfe();
<?php
}
?>
setSaleoff(<?php echo $buytype;?>)
</script>
</BODY>
</HTML>
