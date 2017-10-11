<?php
include "Check_Admin.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";

$Good_id    = intval($FUNCTIONS->Value_Manage($_GET['goods_id'],'','back',''));

/**
 * 这里是当供应商进入的时候。只能修改自己的产品资料。
 */
if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$Provider_string = " and g.provider_id=".intval($_SESSION['sa_id'])." ";
}else{
	$Provider_string = "";
}

$Query = $DB->query("select g.gid,g.goodsname,good_color from `{$INFO[DBPrefix]}goods` g where g.gid=".intval($Good_id)." ".$Provider_string." limit 0,1");
$Num = $DB->num_rows($Query);
if ($Num>0){
	$Result =  $DB->fetch_array($Query);
	$GoodsName =  $Result['goodsname'];
	$good_color =  $Result['good_color'];
}else{
	echo "<script language=javascript>javascript:window.back();</script>";
	exit;
}
$DB->free_result($Query);

$Query = $DB->query("select detail_name from `{$INFO[DBPrefix]}goods_detail` where gid='" . intval($gid) . "' order by detail_id desc");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$i=0;
	while ($Result = $DB->fetch_array($Query)){
		$detail_name[$i] =  $Result['detail_name'];
		$i++;
	}
}

$Query = $DB->query("select p.* from `{$INFO[DBPrefix]}good_pic` p  where p.good_id=".intval($Good_id)." order by orderby");
$Num   = $DB->num_rows($Query);

if ($Num>0){
	$i=0;

	$Goodpic_id_array      = array();
	$Goodpic_name_array    = array();
	$Goodpic_content_array = array();
	$Goodpic_orderby_array = array();
	while ( $Result   =  $DB->fetch_array($Query)){

		$Goodpic_id_array[$i]       =  $Result['goodpic_id'];
		$Goodpic_name_array[$i]     =  $Result['goodpic_name'];
		$Goodpic_content_array[$i]  =  $Result['goodpic_content'];
		$Goodpic_color_array[$i]  =  $Result['color'];
		$Goodpic_detail_name_array[$i]  =  $Result['detail_name'];
		$Goodpic_orderby_array[$i]  =  $Result['orderby'];
		$i++;
	}
}


?>
<link href="../css/uploadfile.css" rel="stylesheet">
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
	document.upload.action = "admin_goods_pic_save.php";
	document.upload.Action.value="Addpic";
	document.upload.submit();

}
</SCRIPT>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
  <FORM  action='admin_goods_ajax_pic_save.php' method=post enctype='multipart/form-data' 	name='upload' id="picform" >
    <input type="hidden" name="Action" id="Action">
    <input type="hidden" name="GoodpicName" id="GoodpicName" >
    <input type="hidden" name="Delid" id="Delid" >
    <input type="hidden" name="good_id" value="<?php echo $Good_id?>">
<TABLE cellSpacing=0 cellPadding=1 width="100%" align=center border=0 bgColor=#f7f7f7>
    <TBODY>
      <TR>
        <TD vAlign=top bgColor=#ffffff><TABLE class=9pv cellSpacing=0 cellPadding=2
                  width="100%" align=center bgColor=#f7f7f7 border=0 style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid">
            <TBODY>
              <TR>
                <TD noWrap align=right width="18%">&nbsp;</TD>
                <TD noWrap align=right>&nbsp;</TD>
              </TR>
              <TR>
                <TD noWrap align=right width="18%" class=p9black><?php echo $Admin_Product["Goods_Pic_Title"];//标题?>：</TD>
                <TD noWrap align=left><input name="goodpic_title" type="text"  id="goodpic_title"></TD>
              </TR>
              <TR>
                <TD noWrap align=right class=p9black>顏色：</TD>
                <TD noWrap align=left>
                <select name="color" id="color">
                <option value=''>請選擇</option>
                <?php
                $good_color_array = explode(",",$good_color);
				foreach($good_color_array as $k=>$v){
					echo "<option value='" . $v . "'>" . $v . "</option>";
				}
				?>
                </select>
                </TD>
              </TR>
							<TR>
								<TD noWrap align=right class=p9black>詳細規格：</TD>
								<TD noWrap align=left>
								<select name="detail_name" id="detail_name">
								<option value=''>請選擇</option>
								<?php
				foreach($detail_name as $k=>$v){
					echo "<option value='" . $v . "'>" . $v . "</option>";
				}
				?>
								</select>
								</TD>
							</TR>
              <TR>
                <TD noWrap align=right width="18%" class=p9black><?php echo $Admin_Product["Goods_Pic_UpPicture"];//上传文件?>：</TD>
                <TD noWrap align=left><div id="mulitplefileuploader">Upload</div>
				<div id="status"></div>
                <div id="fileslist"></div>
                  <input type="button" name="button" id="updatepic" value="上傳">
                  </div></TD>
              </TR>
              <TR>
                <TD noWrap align=right>&nbsp;</TD>
                <TD noWrap align=left><IMG id="view"  src="images/big5/blank.png" border=0></TD>
              </TR>
              <TR>
                <TD colspan="2" align=right noWrap></TD>
              </TR>
              <TR>
                <TD noWrap align=right><?php echo $Admin_Product["Goods_Pic_PictureShow"];//图片显示?></TD>
                <TD noWrap align=left><table border="0" cellspacing="0" cellpadding="2" class="allborder" bgcolor="#F7F7F7">
                    <?php
$GoodPicNum = count($Goodpic_id_array);
if ( $GoodPicNum >0 ){
	$hang = ceil($GoodPicNum/6);
	$n=0;
	for ($j = 0;$j<$hang;$j++){
?>
                    <tr>
                      <?
	for ($i=0;$i<6;$i++){
?>
                      <td width=110 height=75 align="center"><?
if ($Goodpic_name_array[$n]!=""){
?>
                        <img src="<?php echo $INFO['site_url']."/".$INFO['good_pic_path']?>/<?php echo $Goodpic_name_array[$n]?>" width="100"><br>
                        <br>
                        <?php echo $Goodpic_color_array[$n].$Goodpic_detail_name_array[$n]?>
                        <br />
                        <input type="text" value="<?php echo $Goodpic_orderby_array[$n]?>" onchange="changeorder(<?php echo $Goodpic_id_array[$n]?>,this.value);"/>
                        <br>
                        <input type="button"  class="inputstyle" name="botton" value="<?php echo $Basic_Command["Del"];//删除?>" onClick='javascript:delpic("<?php echo $Goodpic_id_array[$n]?>","<?php echo $Goodpic_name_array[$n]?>");'>
                        <?
}
  ?></td>
                      <?php
$n++;
  }
 ?>
                    </tr>
                    <?

	}
}else{
?>
                    <tr>
                      <td width=160 height=75 align="center"><?php echo $Basic_Command["Null"];//无?></td>
                    </tr>
                    <?php }?>
                  </table></TD>
              </TR>
              <TR>
                <TD colspan="2" align=right noWrap><DIV id=sending2 style="Z-INDEX: 10; VISIBILITY: hidden">
                    <TABLE cellSpacing=0 cellPadding=0 width="90%" border=0>
                      <TBODY>
                        <TR>
                          <TD width="30%"></TD>
                          <TD bgColor=red><TABLE class=form height=50 cellSpacing=2 cellPadding=0   width="100%" border=0>
                              <TBODY>
                                <TR>
                                  <TD align=middle bgColor=#ffffcc><DIV align=center><?php echo $Basic_Command["Goods_Pic_Waiting"];//图片上传中,请稍候...?></DIV></TD>
                                </TR>
                              </TBODY>
                            </TABLE></TD>
                          <TD width="30%"></TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </DIV></TD>
              </TR>
              <TR>
                <TD colspan="2" align=right noWrap>&nbsp;</TD>
              </TR>
            </TBODY>
          </TABLE></TD>
      </TR>

  </TBODY>

</TABLE></FORM>
<script>
$(document).ready(function()
{
	var options = {
		success:       function(msg){
						if (msg==1){
							showtajaxfun('morepic');
						}else{
							alert(msg);
						}
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};

	$("#updatepic").click(function(){

								   $('#Action').attr("value","Addpic");
								   $('#picform').ajaxSubmit(options);
								   });
var settings = {
    url: "admin_goods_ajax_picupload.php",
    dragDrop:true,
    fileName: "myfile",
    allowedTypes:"jpg,png,gif,doc,pdf,zip",
    returnType:"json",
	 onSuccess:function(files,data,xhr)
    {

		$("#fileslist").append("<input style='display:none' type='text' name='files[]' value='" + data + "'>");
    },
    showDelete:true,
    deleteCallback: function(data,pd)
	{
    for(var i=0;i<data.length;i++)
    {
        $.post("admin_goods_ajax_picdelete.php",{op:"delete",name:data[i]},
        function(resp, textStatus, jqXHR)
        {
            //Show Message
			len = document.getElementsByName("files[]").length;
			obj_file = document.getElementsByName("files[]");
			for(j=0;j<len;j++){
				if(obj_file[j].text=resp){
					obj_file[j].remove();
				}
			}
			//alert(resp);
            $("#status").append("<div>File Deleted</div>");
        });
     }
    pd.statusbar.hide(); //You choice to hide/not.

}
}
var uploadObj = $("#mulitplefileuploader").uploadFile(settings);


});

function delepic(id){
	$.ajax({
				url: "admin_photo_save.php",
				data: "id="+id+"&act=delpic",
				type:'post',
				dataType:"html",
				success: function(msg){
				//	alert(msg);
					$("#image"+id).remove();
				}

	});
}
var options1 = {
		success:       function(msg){

						if (msg==1){
							showtajaxfun('morepic');
						}else{
							alert(msg);
						}
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
function delpic(goodpic_id,filename){

	if (confirm('<?php echo $Admin_Product[Del_Pic];?>')){  //您是否确认删除该图片
		var pic   = filename ;
		var gpid  = goodpic_id
		$('#Action').attr("value","DelPic");
		$('#Delid').attr("value",gpid);
		$('#GoodpicName').attr("value",pic);
		$('#picform').ajaxSubmit(options1);
	}
}
function changeorder(goodpic_id,orderby){
	$.ajax({
				url: "admin_goods_ajax_pic_save.php",
				data: "id="+goodpic_id+"&orderby="+orderby+"&act=changeorder",
				type:'post',
				dataType:"html",
				success: function(msg){
				//	alert(msg);
					showtajaxfun('morepic');
				}

	});
}
</script>
