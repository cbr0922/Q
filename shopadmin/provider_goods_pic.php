<?php
include "Check_Admin.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";

$Good_id    = intval($FUNCTIONS->Value_Manage($_GET['good_id'],'','back',''));

/**
 * 这里是当供应商进入的时候。只能修改自己的产品资料。
 */
if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$Provider_string = " and g.provider_id=".intval($_SESSION['sa_id'])." ";
}else{
	$Provider_string = "";
}

$Query = $DB->query("select g.gid,g.goodsname from `{$INFO[DBPrefix]}goods` g where g.gid=".intval($Good_id)." ".$Provider_string." limit 0,1");
$Num = $DB->num_rows($Query);
if ($Num>0){
	$Result =  $DB->fetch_array($Query);
	$GoodsName =  $Result['goodsname'];
}else{
	echo "<script language=javascript>javascript:window.back();</script>";
	exit;
}
$DB->free_result($Query);

$Query = $DB->query("select p.* from `{$INFO[DBPrefix]}good_pic` p  where p.good_id=".intval($Good_id)." limit 0,20 ");
$Num   = $DB->num_rows($Query);

if ($Num>0){
	$i=0;

	$Goodpic_id_array      = array();
	$Goodpic_name_array    = array();
	$Goodpic_content_array = array();
	while ( $Result   =  $DB->fetch_array($Query)){

		$Goodpic_id_array[$i]       =  $Result['goodpic_id'];
		$Goodpic_name_array[$i]     =  $Result['goodpic_name'];
		$Goodpic_content_array[$i]  =  $Result['goodpic_content'];
		$i++;
	}
}


?>
<HTML  xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[UpMorePic];//商品多图?></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>

<SCRIPT>
var  img=null;
var diskuse     = 2074319;
var quota       = 20971520;
var maxfilesize = 204800;  // 2097152 //524288 //262144 // 131072  //65536 // 32768

function  checkimage(){
	//setTimeout('function wait(){}',5000);
	document.upload.photo.value = trim(document.upload.photo.value);
	var name = document.upload.photo.value;
	if(/^.+\.(JPG|GIF|gif|jpg|png)$/i.test(name)){
	if(img)img.removeNode(true);
	img=document.createElement("img");
	img.style.position="absolute";
	img.style.visibility="hidden";
	img.attachEvent("onerror",ErrImgType);
	img.attachEvent("onreadystatechange",ErrImgBig);
	document.body.insertAdjacentElement("beforeend",img);
	img.src=name;

	var showimg_width = img.width;
	var showimg_height = img.height;

	if(showimg_width > 500)
	{
		var ori_w= showimg_width;
		var ori_h = showimg_height;
		showimg_width = 500;
		showimg_height = ori_h * showimg_width / ori_w;
	}

	if(showimg_width < 50 && showimg_height <50)
	{
		showimg_width = showimg_height = 150;
	}

	/*
	document.all.view.src = name;
	document.all.view.style.width = showimg_width;
	document.all.view.style.height = showimg_height;

	*/
	document.getElementById("view").src          = name;
	document.getElementById("view").style.width  = showimg_width;
	document.getElementById("view").style.height = showimg_height;
}
else{ErrImgType()}
}

function  ErrImgType(){
	upload.reset();
	alert('<?php echo $FUNCTIONS_Pack['Upload_File_FileFormat_Say'];?>'); //"上传图片文件类型只能是jpg或gif!" ADD SWF
	return false;
}

function  ErrImgBig(){
	if  (img.fileSize>maxfilesize){
		upload.reset();
		alert('<?php echo $FUNCTIONS_Pack['Upload_File_FileSize_Say'];?>'); //"文件大小不能超过200K!"
		return false;
	}
}



function picsubmit() {
	/*
	document.upload.PICINTRO.value = trim(document.upload.PICINTRO.value);

	if(document.upload.PICINTRO.value.length==0) {
	alert("请简单介绍一下您的图片");
	return false;
	}

	if(document.upload.PICINTRO.value.length>32) {
	alert("您的图片介绍超过32字,目前字数="+upload.PICINTRO.value.length);
	return false;
	}
	*/
	filename = document.upload.photo.value.toLowerCase();
	filext = filename.substring(filename.length-3,filename.length);
	if(filext!="jpg" &&  filext!="gif" && filext!="swf" && filext!="png" ) {
		alert('<?php echo $FUNCTIONS_Pack['Upload_File_FileFormatError_Say'];?>'); //"上傳的圖片格式錯誤,只能上傳gif,jpg,png或swf格式
		return;
	}

	/*
	document.all.sending2.style.visibility="visible";
	document.upload.action = "admin_goods_pic_save.php";
	document.upload.Action.value="Addpic";
	document.upload.submit();

	*/
	document.getElementById("sending2").style.visibility="visible";
	document.upload.action = "provider_goods_pic_save.php";
	document.upload.Action.value="Addpic";
	document.upload.submit();

}
</SCRIPT>

<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>

<?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD></TR></TBODY></TABLE>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<script language="javascript">
function checkform(){
	if (chkblank(upload.photo.value)){
		upload.photo.focus();
		alert('<?php echo $Admin_Product[PleaseSelectUploadFile] ;?>'); //"请选择上传图片"
		return;
	}
	//form1.action="admin_goods_act.php";
	upload.submit();
}

function delpic(goodpic_id,filename){
	var pic   = filename ;
	var gpid  = goodpic_id
	if (confirm('<?php echo $Admin_Product[Del_Pic];?>')){  //您是否确认删除该图片
		document.upload.action = "provider_goods_pic_save.php";
		document.upload.Action.value="DelPic";
		document.upload.GoodpicName.value=pic;
		document.upload.Delid.value=gpid;
		document.upload.submit();
	}
}

</script>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <FORM  action='provider_goods_pic_save.php' method=post enctype='multipart/form-data' 	name='upload' onSubmit="return picsubmit()" >
  <input type="hidden" name="Action" >
  <input type="hidden" name="GoodpicName" >
  <input type="hidden" name="Delid" >
  <input type="hidden" name="good_id" value="<?php echo $Good_id?>">
  
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif"   width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[UpMorePic];//商品多图?></SPAN></TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79>
					  <!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom">
							<a href="provider_goods.php?Action=Modi&gid=<?php echo $Good_id?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD>
							</TR>
						  </TBODY>
						 </TABLE>
					 <!--BUTTON_END-->
						</TD>
						</TR>
						</TBODY>
						</TABLE>
				</TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:picsubmit();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->
							
							</TD></TR></TBODY></TABLE>
							
						</TD></TR></TBODY></TABLE>
</TD>
		  
		  </TR>
		 </TBODY>
	  </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
              <TBODY>
              <TR>
                <TD vAlign=top bgColor=#ffffff height=300>
                  <TABLE class=9pv cellSpacing=0 cellPadding=2 
                  width="100%" align=center bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="18%">&nbsp;</TD>
                      <TD noWrap align=right>&nbsp;</TD></TR>
                    <TR>
                      <TD noWrap align=right><?php echo $Admin_Product["ProductName"];//商品名称：?>：</TD>
                      <TD noWrap align=left><font color="#FF0000"><?php echo $GoodsName?></font></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right width="18%">
                      <?php echo $Admin_Product["Goods_Pic_Title"];//标题?>：</TD>
                      <TD noWrap align=left><input name="goodpic_title" type="text"  id="goodpic_title">                        
					  </TD>
					  </TR>

					 
                    <TR>
                      <TD noWrap align=right width="18%"> <?php echo $Admin_Product["Goods_Pic_UpPicture"];//上传文件?>：</TD>
                      <TD noWrap align=left><input name="photo" type="file" id="photo" onChange="setTimeout('checkimage()',500)">
					  <div id="phototips" class="tips"><?php echo $Admin_Product[UploadIntro] ?></div>	
					  </TD></TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD noWrap align=left><IMG id=view  src="../include/blank.gif" border=0></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>
					 
					  </TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right> <?php echo $Admin_Product["Goods_Pic_PictureShow"];//图片显示?> </TD>
                      <TD noWrap align=left>
					  
<table border="0" cellspacing="0" cellpadding="2" class="allborder" bgcolor="#F7F7F7">
<tr>
<?php
$GoodPicNum = count($Goodpic_id_array);
if ( $GoodPicNum >0 ){
	for ($i=0;$i<$GoodPicNum;$i++){
?>
<td width=90 height=75 align="center"><img src="<?php echo $INFO['site_url']."/".$INFO['good_pic_path']?>/<?php echo $Goodpic_name_array[$i]?>"><br>
  <br><input type="button"  class="inputstyle" name="botton" value="<?php echo $Basic_Command["Del"];//删除?>" onClick='javascript:delpic("<?php echo $Goodpic_id_array[$i]?>","<?php echo $Goodpic_name_array[$i]?>");'></td>
<?php 
  }
}else{
?>
<td width=160 height=75 align="center"><?php echo $Basic_Command["Null"];//无?></td>
<?php }?>
</tr>
</table>					  </TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>
					   <DIV id=sending2 style="Z-INDEX: 10; VISIBILITY: hidden">
					  <TABLE cellSpacing=0 cellPadding=0 width="90%" border=0>
                        <TBODY>
                          <TR>
                            <TD width="30%"></TD>
                            <TD bgColor=red>							
                              <TABLE class=form height=50 cellSpacing=2 cellPadding=0   width="100%" border=0>
                                <TBODY>
                                  <TR>
                                    <TD align=middle bgColor=#ffffcc>
                                      <DIV align=center><?php echo $Basic_Command["Goods_Pic_Waiting"];//图片上传中,请稍候...?></DIV></TD>
                                  </TR>
                                </TBODY>
                            </TABLE>
							
							</TD>
                            <TD width="30%"> </TD>
                          </TR>
                        </TBODY>
                      </TABLE>
					   </DIV>  
					  
					  </TD>
                      </TR>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
  </FORM>
					  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
