<?php
include_once "Check_Admin.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_GET['k_type_id']!="" && $_GET['Action']=='Modi'){
	$k_type_id = intval($_GET['k_type_id']);
	$Action_value = "Update";
	$Action_say  = $KeFu_Pack['Back_Nav_title'].$KeFu_Pack['Back_ModiType'] ;//修改問題類別
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}kefu_type` where k_type_id=".intval($k_type_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$k_type_name            =  $Result['k_type_name'];
		$k_type_content         =  $Result['k_type_content'];
		$status                 =  $Result['status'];
		$checked                =  $Result['checked'];
		$typegroup                =  $Result['typegroup'];

	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = $KeFu_Pack['Back_Nav_title'].$KeFu_Pack['Back_AddType']; ///添加問題類別
	$status  = 1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
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
				if ($_GET['k_type_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_kefu_type_save.php?act=autosave&k_type_id=<?php echo $_GET['k_type_id'];?>',
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
<? //include "Order_state.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.k_type_name.value)){
			form1.k_type_name.focus();
			alert('<?php echo $KeFu_Pack['Js_input_typename']?>');  //请选择問題類別名稱			
			return;
		}
	
		form1.submit();
	}
	function checkform1(){

		if (chkblank(form1.k_type_name.value)){
			form1.k_type_name.focus();
			alert('<?php echo $KeFu_Pack['Js_input_typename']?>');  //请选择問題類別名稱			
			return;
		}
	    document.form1.ifgo_on.value=1;
		form1.submit();
	}

</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_kefu_type_save.php' method=post >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="k_type_id" value="<?php echo $k_type_id?>">
  
  <?php
  if ($_GET['type']) {
  	$type = str_replace('+','',$_GET['type']);
  	$offset = intval($_GET['offset']);
  }else {
  	$type = $_POST['type'];
  	$offset = intval($_POST['offset']);
  }
  ?>
  <input type="hidden" name="type" value="<?php echo $type?>">
  <input type="hidden" name="offset" value="<?php echo $offset?>">
  <input type="hidden" name="ifgo_on" value="0">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $Action_say?></SPAN></TD>
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
                            <a href=<?php echo (isset($_GET['type'])||isset($_POST['type']))?'admin_kefu_type_list.php?type='.$type.'&offset='.$offset:"admin_kefu_type_list.php"?>><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                            
                          </TD></TR></TBODY></TABLE>
                    
                  </TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                                    <a href="javascript:checkform1();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save-add.gif" border=0 >&nbsp;<?php echo $KeFu_Pack['Back_SaveGoAdd'];//保存后繼續新增?></a></TD>
                                </TR></TBODY></TABLE><!--BUTTON_END-->
                            
                          </TD></TR></TBODY></TABLE>
                    
                  </TD></TR></TBODY></TABLE>
            </TD>
          </TR>
      </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2 width="100%" align=center bgColor=#f7f7f7 border=0>
                      <TBODY>
                      <TR></TR>
                      </TBODY>
                      <tbody>
                        <tr>
                          <td nowrap align=right width="18%">&nbsp;</td>
                          <td colspan="2" align=right nowrap>&nbsp;</td>
                        </tr>
                        <tr>
                          <td nowrap align=right>所屬類別：</td>
                          <td height="25" colspan="2" align=left nowrap>
                          <SELECT name='typegroup' class="trans-input">
                        <OPTION value="" >- ROOT -</OPTION>
                        <?php 
		  $s_Sql = "select * from `{$INFO[DBPrefix]}kefu_type` where typegroup=0";
		  $Query_s    = $DB->query($s_Sql);
		  while ($Rs_s=$DB->fetch_array($Query_s)) {
		  ?>
                        <OPTION value=<?php echo $Rs_s['k_type_id'];?> <?php if ($typegroup==$Rs_s['k_type_id']) echo " selected ";?>><?php echo $Rs_s[k_type_name];?></OPTION>
                        <?php
		  }
		  ?>
                        </SELECT>
                          </td>
                        </tr>
                        <tr>
                          <td nowrap align=right width="18%"><?php echo $KeFu_Pack['Back_Type_Name'];//問題類別名稱?>：</td>
                          <td height="25" colspan="2" align=left nowrap><?php echo $FUNCTIONS->Input_Box('text','k_type_name',$k_type_name,"      maxLength=50 size=50 ")?></td>
                        </tr>
                        <tr>
                          <td align=right ><?php echo $Basic_Command['Iffb'];//是否发布?>：</td>
                          <td height="25" colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','status',$status,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></td>
                        </tr>
                        <tr>
                          <td nowrap align=right width="18%"><?php echo $KeFu_Pack['Back_IfNeiDing'];//是否内定?>：</td>
                          <td height="25" colspan="2" align=left nowrap><?php echo $FUNCTIONS->Input_Box('radio','checked',$checked,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></td>
                        </tr>
                        <tr>
                          <td align=right valign="top" nowrap>&nbsp;</td>
                          <td colspan="2" align=left nowrap>&nbsp;</td>
                        </tr>
                        <tr>
                          <td align=right valign="top" nowrap><?php echo $KeFu_Pack['Back_TypeReplayEmailContent'] ;?>：</td>
                          <td colspan="2" align=left nowrap>
                          <div  class="editorwidth">
						  <textarea name="FCKeditor1" id="redactor" cols="30" rows="10" ><?php echo $k_type_content;?></textarea>
                          </div>
						  </td>
                        </tr>
                        <tr>
                          <td nowrap align=right>&nbsp;</td>
                          <td colspan="2" align=right nowrap>&nbsp;</td>
                        </tr>
                      </tbody>
                      <TBODY>
                      </TBODY>
                    </TABLE>
                     </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
