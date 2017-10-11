<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$Comment_id  = $FUNCTIONS->Value_Manage($_GET['comment_id'],$_POST['comment_id'],'back','');
/**
 * 这里是当供应商进入的时候。只能修改自己产品的评论资料。
 */
if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$Provider_string = " and g.provider_id=".intval($_SESSION['sa_id'])." ";
}else{
	$Provider_string = "";
}
$Sql         = "select gc.* ,g.goodsname from `{$INFO[DBPrefix]}good_comment` gc  inner join `{$INFO[DBPrefix]}goods` g on (gc.good_id=g.gid) where comment_id=".intval($Comment_id)."  ".$Provider_string." limit 0,1 ";
//$Query       = $DB->query("select * from good_comment where comment_id=".intval($Comment_id)." limit 0,1");
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);

if ($Num>0){
	$Result    = $DB->fetch_array($Query);
	$GoodsName = $Result['goodsname'];
	$CoIdate   = $Result['comment_idate'];
	$CoContent = nl2br($Result['comment_content']);
	$CoAnswer  = $Result['comment_answer'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[Comment_System] ;//回复评论?></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?></TD>
  </TR></TBODY></TABLE>
<SCRIPT language=javascript>
	function checkform(){
		document.form1.action="provider_comment_save.php";
		document.form1.submit();
	}	
	
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='' method=post  >
  <input type="hidden" name="Action" value="Update">
  <INPUT type=hidden  name='comment_id' value="<?php echo $Comment_id?>"> 
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[Comment_System] ;//回复评论?>    </SPAN></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="provider_comment_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"   border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                            <TD noWrap align=right width="12%"> <?php echo $Admin_Product[Comment_Time];//评论时间?>：</TD>
                            <TD><?php echo date("Y-m-d H: i a ",$CoIdate)?>
                              </TD></TR>
                          <TR>
                            <TD noWrap align=right width="12%"> <?php echo $Admin_Product[Comment_User]; //评论内容?>：</TD>
                            <TD><?php echo $CoContent;?></TD></TR>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Admin_Product[Comment_System]?>：</TD>
                            <TD>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>&nbsp;</TD>
                            <TD align=left valign="top" noWrap>
                              <TABLE width="91%" height="18"  border=0 align="left" cellPadding=0 cellSpacing=0>
                                <TBODY>
                                  <TR>
                                    <TD valign="top">
                                      <?php
						
						//echo OtherPach."/".Resources."/ckeditor/";;
						$CKEditor = new CKEditor();
						$CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						
						$CKEditor->config['width'] = 700;
						$CKEditor->config['height'] = 400;
						//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
						echo $code = $CKEditor->editor("FCKeditor1", $CoAnswer);

					   ?>
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
