<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/Link_Pack.php";
if ($_GET['Link_id']!="" && $_GET['Action']=='Modi'){
	$Link_id = intval($_GET['Link_id']);
	$Action_value = "Update";
	$Action_say  = $Link_Pack[ModiLink] ;// "修改連結资料"
	$Query = $DB->query("select link_title,link_url,link_width,link_height,link_ima,link_display,orderby   from `{$INFO[DBPrefix]}link` where link_id=".intval($Link_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$link_title  =  $Result['link_title'];
		$link_url    =  $Result['link_url'];
		$link_width  =  $Result['link_width'];
		$link_height =  $Result['link_height'];
		$link_ima    =  $Result['link_ima'];
		$link_display=  $Result['link_display'];
		$orderby=  $Result['orderby'];

	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$Action_say   = $Link_Pack[AddLink]; //添加連結资料
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Link_Friend];//相關連結?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.link_title.value) || form1.link_title.value.length>100 || form1.link_title.value.length<2){
			form1.link_title.focus();
			alert('<?php echo $Link_Pack[PleaseInputLinkName] ?>'); //请输入链接标题
			return;
		}
		
		if (chkblank(form1.link_url.value)){
			form1.link_url.focus();
			alert('<?php echo $Link_Pack[PleaseInputLinkUrl]?>'); //"请输入链接地址"
			return;
		}
		
		if (form1.ima.value != "")
		{
			if (isnum(form1.link_width.value)<0){
				alert('<?php echo $Link_Pack[PleaseInputLinkWidth]?>'); //"请输入宽度"
				form1.link_width.value="";
				form1.link_width.focus();
				return;			
			}
			if (isnum(form1.link_height.value)<0){
				alert('<?php echo $Link_Pack[PleaseInputLinkHeight]?>'); //"请输入高度"
				form1.link_height.value="";
				form1.link_height.focus();
				return;			
			}
		}
		
		form1.submit();
	}
</SCRIPT>
<div id="contain_out">
  <FORM name=form1 action='admin_link_save.php' method=post enctype="multipart/form-data" >
    <?php  include_once "Order_state.php";?>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="Link_id" value="<?php echo $Link_id?>">
  <input type="hidden" name="Link_ima" value="<?php echo $link_ima?>">

    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Link_Friend];//相關連結?>--&gt;<?php echo $Action_say?></SPAN></TD>
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
							<a href="admin_link_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
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
    </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="18%">&nbsp;</TD>
                      <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>

                    <TR>
                      <TD noWrap align=right> <?php echo $Link_Pack[LinkName];//链接标题?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','link_title',$link_title,"      maxLength=40 size=40 ")?></TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right> <?php echo $Link_Pack[LinkUrl];//链接地址?>：</TD>
                      <TD width="38%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','link_url',$link_url,"      maxLength=200 size=60 ")?></TD>
                      <TD width="8%" align=right noWrap>&nbsp;</TD>
                      <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right> <?php echo $Link_Pack[LinkWidth];//宽度?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','link_width',$link_width,"      maxLength=40 size=10 ")?>&nbsp;<?php echo $Link_Pack[Pix] ?></TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right> <?php echo $Link_Pack[LinkHeight];//高度?>：</TD>
                      <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','link_height',$link_height,"      maxLength=40 size=10 ")?>&nbsp;<?php echo $Link_Pack[Pix] ?></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>排序：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','orderby',$orderby,"      maxLength=40 size=10 ")?></TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right> <?php echo $Basic_Command['UploadFile'];//上传文件：?>：</TD>
                      <TD align=left noWrap>
					  <input name="ima" type="file" class="inputstyle">					  </TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->ImgTypeReturn($INFO['link_pic_path'],$link_ima,$link_height,$link_width);?></TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right><?php echo $Basic_Command['Iffb'];//是否发布：?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','link_display',$link_display,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" align=left noWrap>&nbsp;</TD>
                    </TR>
                    </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
