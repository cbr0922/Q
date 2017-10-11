<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Forum_Pack.php";
if (!empty($_GET['copypid'])){
	$Query = $DB->query("select bid,top_id from `{$INFO[DBPrefix]}forum_class` where bid=".intval($_GET['copypid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Copy_id =  $Result['bid'];
		$Top_id  =  $Result['top_id'];


	}else{
		$FUNCTIONS->header_location('admin_create_forumclassshow.php');
		// echo "<script language=javascript>javascript:window.history.back(-1);< / script>";
		exit;
	}
}



if ($_GET['bid']!="" && $_GET['Action']=='Modi'){
	$Bid = intval($_GET['bid']);
	$Action_value = "Update";
	$Action_say   = $Forum_Pack[EditForumClass] ; //編輯論壇分類
	$Sql = "select * from `{$INFO[DBPrefix]}forum_class` where bid=".intval($Bid)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Catname =  $Result['catname'];
		$Catord  =  $Result['catord'];
		$Top_id  =  $Result['top_id'];
		$Catiffb =  $Result['catiffb'];
		$Attr    =  $Result['attr'];
		$Attr_id =  $Result['attr_id'];
		$Intro   =  $Result['intro'];
		$Content =  $Result['content'];




		$Attr_array    =  explode(",",$Attr);
		$Attr_array_id =  explode(",",$Attr_id);

	}else{
		$FUNCTIONS->header_location('admin_create_forumclassshow.php');
		// echo "<script language=javascript>javascript:window.history.back(-1);< / script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = $Forum_Pack[AddForumClass]; //新建立论坛分类
}
include RootDocumentShare."/cache/Forumclass_show.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Forum_Man];//论坛管理?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		if (chkblank(form1.catname.value) || form1.catname.value.length>50){
			form1.catname.focus();
			alert('<?php echo $Forum_Pack[PleaseInputForumClassName]?>');
			return;
		}
		if (form1.bid.value != "" && form1.bid.value == form1.top_id.value){
			form1.top_id.focus();
			alert('<?php echo $Forum_Pack[BadClassOp]?>');
			return;
		}		
      var Actions ;
  	  Actions = document.form1.TheAction.value;		
      if (Actions!='Insert'){
		if ((form1.attr_name_id.value != "" && form1.attr_name.value=="") || (form1.attr_name_id.value == "" && form1.attr_name.value!="")){
			form1.attr_name_id.focus();
			alert('<?php echo $Forum_Pack[PleaseInputForumClassBzName]?>');
			return;
		}		
	  }	
		form1.submit();
	}

</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name="form1" action='admin_fcat_save.php' method=post >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="TheAction" value="<?php echo $Action_value?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Forum_Man];//论坛管理?>--&gt;<?php echo $Action_say?> </SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <?php if ($Ie_Type != "mozilla") { ?>
                  <?php } else {?> 
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
                            <a href="admin_fcat_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                      <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->							</TD></TR></TBODY></TABLE>			    </TD></TR>
                  <?php } ?>			
                </TBODY></TABLE></TD></TR>
        </TBODY>
    </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top height=262>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff height=300>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2   width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              <?php echo $Forum_Pack[FatherForumClassName];//父级类别名称?>：</TD>
                            <TD noWrap align=left>
                              <input type="hidden" name="bid" value="<?php echo $Bid?>"><?php echo  $Char_class->get_page_select("top_id",$Top_id,"  class=\"trans-input\" ");?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              <?php echo $Forum_Pack[ForumClassName];//論壇分類名称?>：</TD>
                            <TD noWrap align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','catname',$Catname,"      maxLength=50 size=40 ")?></TD>
                          </TR>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Forum_Pack[ForumClassIntro]?>：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('textarea','intro',$Intro,"  cols=50 rows=5    ")?></TD>
                          </TR>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Forum_Pack[LunTanRule];?>：</TD>
                            <TD noWrap align=left>
                              <?php
						//echo OtherPach."/".Resources."/ckeditor/";;
						$CKEditor = new CKEditor();
						$CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						
						$CKEditor->config['width'] = 700;
						$CKEditor->config['height'] = 400;
						//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
						echo $code = $CKEditor->editor("FCKeditor1", $Content);
					   ?> 
                              
                            </TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              <?php echo $Basic_Command['DisplayOrderby'];//显示顺序：?>：</TD>
                            <TD noWrap align=right>
                              
                              <?php echo $FUNCTIONS->Input_Box('text','catord',$Catord,"      maxLength=10 size=10 ")?>                        </TD></TR>
                          <TR>
                            <TD align=right ><?php echo $Basic_Command['Iffb'];//是否发布?>：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('radio','catiffb',$Catiffb,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                          </TR>					 
                          <?php
						if ($Action_value != "Insert")	{
					?>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Forum_Pack[BanZhu];//斑竹?>：</TD>
                            <TD align=left bgcolor="#FFFFCC"><?php echo $Forum_Pack[ZhuYi]?></TD>
                          </TR>
                          
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD noWrap align=left> ID：<input type="text" name="attr_name_id" size="8" />&nbsp;&nbsp;&nbsp;<?php echo $Forum_Pack[ZhangHao];?>：<input type="text" name="attr_name" size="20" />
                              &nbsp;&nbsp;</TD>
                          </TR>					
                          <?php		
					 }
					if (!empty($Attr)){
						for ($i=0;$i<count($Attr_array);$i++){
							if (!empty($Attr_array[$i])){
					?>
                          <TR>
                            <TD align=right noWrap></TD>
                            <TD align=left noWrap>
                              <input type="hidden" name="all[]" value="<?php echo $Attr_array_id[$i]?>">
                              <?php echo $FUNCTIONS->Input_Box('text','attrid[]',$Attr_array_id[$i],"      maxLength=10 size=10 ")?>   
                              <?php echo $Attr_array[$i]?>&nbsp;[ID:<?php echo $Attr_array_id[$i]?>] 
                              <input type="hidden" name="attr[]" value="<?php echo $Attr_array[$i]?>"  /> &nbsp;<a href="admin_fcat_save.php?ACT=DelAttr&bid=<?php echo $bid?>&Action=Modi&attrid=<?php echo $Attr_array_id[$i]?>&attrname=<?php echo $Attr_array[$i]?>"><?php echo $Basic_Command['Del'] ?></a></TD>
                          </TR>
                          <?php }
						}
					}
					?>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD noWrap align=left>&nbsp;</TD>
                          </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
 </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
