<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
if ($_GET['transport_id']!="" && $_GET['Action']=='Modi'){
	$Transport_id = intval($_GET['transport_id']);
	$Action_value = "Update";
	$Action_say   = $Admin_Product[ModiCarriageType];
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}transportation` where transport_id=".intval($Transport_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$transport_name      =  $Result['transport_name'];
		$transport_price     =  $Result['transport_price'];
		$transport_content   =  $Result['transport_content'];
		$type   =  $Result['type'];
		$payment   =  $Result['payment'];
		$ttype   =  $Result['ttype'];
		$orderby   =  $Result['orderby'];
		$co_name   =  $Result['co_name'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = $Admin_Product[AddCarriageType];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
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
				if ($_GET['transport_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_ttype_save.php?act=autosave&Transport_id=<?php echo $_GET['transport_id'];?>',
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
	
	   if (chkblank(form1.transport_name.value) || form1.transport_name.value.length>100){
			form1.transport_name.focus();
			alert('<?php echo $Admin_Product[PleaseInputCarriageName]?>');
			return;
		}
		
	   if (chkblank(form1.transport_price.value) || form1.transport_price.value.length>100){
			form1.transport_price.focus();
			alert('<?php echo $Admin_Product[PleaseInputCarriagePrice]?>');
			return;
		}
		form1.action="admin_ttype_save.php";
		form1.submit();
    }
</SCRIPT>
<div id="contain_out">
  <FORM name=form1 action='admin_ttype_save.php' method=post >
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
                            <TD align="left" valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','transport_name',$transport_name,"      maxLength=50 size=50  ")?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD width="18%" align="right" valign="middle" noWrap> 廠商名稱：</TD>
                            <TD align="left" valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','co_name',$co_name,"      maxLength=50 size=50  ")?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>  
                          <TR id="showmonth" <?php echo $DISPLAYmonth;?>>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colSpan=2>
                              <?php 
					 // echo $month;
					  $type_array = explode(",",$type);
					  ?>
                              <input name="type[]" type="checkbox" id="type" value="1" <?php if(in_array(1,$type_array)) echo "checked";?> />
                              電腦
                              <input name="type[]" type="checkbox" id="type" value="2" <?php if(in_array(2,$type_array)) echo "checked";?> />
                              手機</TD>
                            </TR>
                            <TR align="center">
                      <TD align="right" valign="middle" noWrap>貨運類型：</TD>
                      <TD align="left" valign="top" ><input type="radio" value="0" <?php if($ttype == 0 || $_GET['Action']=='') echo "checked";?> name="ttype"  />常溫
                      <input type="radio" value="1" <?php if($ttype == 1) echo "checked";?> name="ttype" onclick= />低溫
                      <input type="radio" value="2" <?php if($ttype == 2) echo "checked";?> name="ttype" onclick= />冷凍&nbsp;</TD>
                      <TD valign="top" noWrap>&nbsp;</TD>
                    </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap>對應金流：</TD>
                            <TD align="left" valign="top" >
                              <?php
					  $payment_array = explode(",",$payment);
                      //$pSql      = "select * from `{$INFO[DBPrefix]}paymanager` as p order by p.pid  ";
					 // $pQuery    = $DB->query($pSql);
					 // while ($pRs=$DB->fetch_array($pQuery)) {
					//	  echo $pRs['payname'] . "<br>";
						 // $Pmsql = "select * from `{$INFO[DBPrefix]}paymethod` as p where p.ifopen=1 order by p.mid";
						  $Pmsql = "select *,p.content as pcontent,p.month as pmonth from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1 order by pm.paytype desc,p.mid";
					  	  $PmQuery    = $DB->query($Pmsql);
					      while ($PmRs=$DB->fetch_array($PmQuery)) {
							  echo "<input name='payment[]' id='payment' type='checkbox' value='" . $PmRs['mid'] . "'";
							  if (in_array($PmRs['mid'],$payment_array))
							  	echo " checked ";
							  echo ">" . $PmRs['methodname'];
						  }
					//	  echo "<br>";
					//  }
					  ?>
                              </TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="middle" noWrap> <?php echo $Admin_Product[CarriagePrice] ;//运费金额?>：</TD>
                            <TD align="left" valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','transport_price',$transport_price,"      maxLength=50 size=50  ")?></TD>
                            <TD valign="top" noWrap>&nbsp;</TD>
                            </TR>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap>排序：</TD>
                            <TD colspan="2" align="left" valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','orderby',$orderby,"      maxLength=50 size=5  ")?></TD>
                          </TR>
                          <TR align="center">
                            <TD align="right" valign="top" noWrap> <?php echo $Admin_Product[Detail_intro] ; //详细介绍?>：</TD>
                            <TD colspan="2" align="left" valign="top" noWrap>
                             <div  class="editorwidth">
                            <textarea name="FCKeditor1" id="redactor" cols="30" rows="10"><?php echo $transport_content;?></textarea>
                            </div>
                              <p>&nbsp;</p></TD>
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
