<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Good.php";

$Gid = intval($_REQUEST['goods_id'])==0 ? $Gid : intval($_REQUEST['goods_id']);

if ($_POST[Action]=='Up'){
	$Sql  = " update `{$INFO[DBPrefix]}goods` set good_color='".strip_tags($_POST['good_color'])."',good_size='".strip_tags($_POST['good_size'])."' where gid=".intval($_POST[gid]);
	$DB->query($Sql);
	//$FUNCTIONS->sorry_back("admin_goods.php?Action=Modi&gid=".intval($_POST[gid]),"");
}
if ($_POST[Action]=='Pic'){
	$photo   = $FUNCTIONS->Upload_File($_FILES['photo']['name'],$_FILES['photo']['tmp_name'],'',"../".$INFO['good_pic_path']);
	
	$Query = $DB->query("select goodsname,good_color,good_color_pic from `{$INFO[DBPrefix]}goods` where gid=".intval($_POST[gid])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$good_color       =  $Result['good_color'];
		$good_color_pic       =  $Result['good_color_pic'];
	}
	$good_color_array = explode(",",$good_color);
	$good_color_pic_array = explode(",",$good_color_pic);
	foreach($good_color_array as $k=>$v){
		if ($v == $_POST['color']){
			$good_color_pic_array[$k] = $photo;	
		}else{
			$good_color_pic_array[$k] = $good_color_pic_array[$k];		
		}
	}
	$good_color_pic = implode(",",$good_color_pic_array);
	
	
	$Sql  = " update `{$INFO[DBPrefix]}goods` set good_color_pic='".$good_color_pic."' where gid=".intval($_POST[gid]);
	$DB->query($Sql);
	echo "1";exit;
	//$FUNCTIONS->sorry_back("admin_goods.php?Action=Modi&gid=".intval($_POST[gid]),"");
}
if ($_POST['Actions']=='DelPic' ) {

	@unlink ("../".$INFO['good_pic_path']."/".trim($_POST['GoodpicsName']));
	$Query = $DB->query("select goodsname,good_color,good_color_pic from `{$INFO[DBPrefix]}goods` where gid=".intval($_POST[goods_id])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$good_color_pic       =  $Result['good_color_pic'];
	}
	$good_color_pic_array = explode(",",$good_color_pic);
	foreach($good_color_pic_array as $k=>$v){
		if ($v == $_POST['GoodpicsName']){
			$good_color_pic_array[$k] = "";	
		}else{
			$good_color_pic_array[$k] = $good_color_pic_array[$k];		
		}
	}
	$good_color_pic = implode(",",$good_color_pic_array);
	
	
	$Sql  = " update `{$INFO[DBPrefix]}goods` set good_color_pic='".$good_color_pic."' where gid=".intval($_POST[goods_id]);
	$DB->query($Sql);
	echo "1";exit;
}
$Query = $DB->query("select goodsname,good_color,good_size,good_color_pic from `{$INFO[DBPrefix]}goods` where gid=".intval($Gid)." limit 0,1");
$Num   = $DB->num_rows($Query);

if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$Goodsname        =  $Result['goodsname'];
	$good_color       =  $Result['good_color'];
	$good_size        =  $Result['good_size'];
	$good_color_pic       =  $Result['good_color_pic'];
	
	
	

}else{
	echo "1";
	exit;
}
?>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
  <FORM name='form1' id="attribform" action='admin_goods_ajax_attrib.php' method=post >
    <input type="hidden" name="Action" value="Up">
    <INPUT type=hidden  name='gid' value="<?php echo $Gid?>">
<TABLE class=allborder cellSpacing=0 cellPadding=2 width="100%" bgColor=#f7f7f7 border=0>
  <TBODY>

    <TR>
      <TD noWrap align=right width="12%">&nbsp;</TD>
      <TD colSpan=2>&nbsp;</TD>
    </TR>
    <TR>
      <TD width="12%" align=right valign="top" noWrap>商品顏色：</TD>
      <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('textarea','good_color',$good_color," cols=80 rows=6")?></TD>
    </TR>
    <TR>
      <TD width="12%" align=right valign="top" noWrap><?php echo $Good[Product_Size];//商品尺寸：?>：</TD>
      <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('textarea','good_size',$good_size," cols=80 rows=6")?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>&nbsp;</TD>
      <TD colSpan=2><input type="button" name="attribsave" id="attribsave" value="儲存">
        <input type="button" name="attribsave2" id="attribsave2" value="屬性貨號管理"  onclick="showWin('url','admin_goods_ajax_attribno.php?gid=<?php echo $Gid?>','',300,250);"/></TD>
    </TR></table>
  </FORM>
  <?php
                      if($good_color!=""){
					  ?>
  <FORM name='form2' id="attribpicform" action='admin_goods_ajax_attrib.php' method=post enctype="multipart/form-data" >
  <input type="hidden" name="Action" value="Pic">
  <INPUT type=hidden  name='gid' value="<?php echo $Gid?>"> <table class=allborder cellSpacing=0 cellPadding=2 width="100%" bgColor=#f7f7f7 border=0> 
                                          <TR>
                                            <TD noWrap align=right>顏色：</TD>
                                            <TD colSpan=2><select name="color" id="color">
                                              <?php
                $good_color_array = explode(",",$good_color);
				foreach($good_color_array as $k=>$v){
					echo "<option value='" . $v . "'>" . $v . "</option>";	
				}
				?>
                                            </select></TD>
                                          </TR>
                                          <TR>
                                            <TD noWrap align=right>示意圖：</TD>
                                            <TD colSpan=2><input name="photo" type="file" id="photo"/></TD>
                                          </TR>
                                          <TR>
                                            <TD noWrap align=right>&nbsp;</TD>
                                            <TD colSpan=2><input type="button" name="attribpicsave" id="attribpicsave" value="上傳" /></TD>
                                          </TR>
                                          <TR>
                                            <TD noWrap align=right>&nbsp;</TD>
                                            <TD colSpan=2>&nbsp;</TD>
                                          </TR></table>			
                 </FORM><table>
					 <TR>
					   <TD noWrap align=right>
                       
                       </TD>
					   <TD colSpan=2 style="text-align: center;"><table>
                       <tr>
						   
								<?php
								$good_color_array = explode(",",$good_color);
								$good_color_pic_array = explode(",",$good_color_pic);
								
									foreach($good_color_array as $k=>$v){
										if(trim($good_color_pic_array[$k])!=""){
										echo "<td>".$v."</td>";	
										}
								}
								?>
						   
					   
					   </tr>
					   
					   
					   <tr>
                       
                       <?php 
					   $good_color_pic_array = explode(",",$good_color_pic);
					   foreach($good_color_pic_array as $k=>$v){
						   if(trim($v)!=""){
						?>
                        <td><img src="<?php echo $INFO['site_url']."/".$INFO['good_pic_path']?>/<?php echo $v?>" width="30" /><br /><br />
						<input type="button"  class="inputstyle" name="botton" value="<?php echo $Basic_Command["Del"];//删除?>" onClick='javascript:delpic("<?php echo $v?>");'></td>
                        <?php
						   }
					    }
					   ?>
                       
                       </tr></table></TD>
				    </TR>
  <?php
					  }
					  ?>
  <TR>
    <TD noWrap align=right>&nbsp;</TD>
    <TD colSpan=2>&nbsp;</TD>
  </TR>
  </TBODY>
  
</TABLE>
<FORM  action='admin_goods_ajax_attrib.php' method=post name='upload' id="picforms" >
    <input type="hidden" name="Actions" id="Actions">
    <input type="hidden" name="GoodpicsName" id="GoodpicsName">
    <input type="hidden" name="goods_id" value="<?php echo $Gid?>">
    </FORM>
<script>
var options1 = {
		success:       function(msg){
						if (msg==1){
							showtajaxfun('attrib');
						}else{
							alert(msg);
						}
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
function delpic(filename){
	if (confirm('是否刪除這個圖片')){  //您是否确认删除该图片
		var pic   = filename ;
		$('#Actions').attr("value","DelPic");
		$('#GoodpicsName').attr("value",pic);
		$('#picforms').ajaxSubmit(options1);
	}
}
$(document).ready(function() {
	var options = {
		success:       function(msg){
						if (msg==1){
							showtajaxfun('attrib');
						}else{
							alert(msg);
						}
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
	$("#attribsave").click(function(){$('#attribform').ajaxSubmit(options);});
	$("#attribpicsave").click(function(){$('#attribpicform').ajaxSubmit(options);});

						   });
</script>