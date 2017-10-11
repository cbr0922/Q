<?php
include_once "Check_Admin.php";
$gid = intval($_GET['gid']);
$Query = $DB->query("select goodsname,good_color,good_size from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)." limit 0,1");
$Num   = $DB->num_rows($Query);
$Result= $DB->fetch_array($Query);
$good_color       =  $Result['good_color'];
$good_size        =  $Result['good_size'];
?>
<div align="center">
<form action="admin_goods_ajax_attribno_save.php"  method="post" name="attribForm" id="attribForm">
<INPUT type=hidden name=gid value="<?php echo $gid?>">
<INPUT type=hidden name=anid id=anid value="">
<INPUT type=hidden name=action value="save">
<table width="236" border="0" cellspacing="1" cellpadding="3">
 <tr id="divshowcolor">
    <td align="right" bgcolor="#FFFFFF">顏色</td>
    <td align="left" bgcolor="#FFFFFF"><select name="color" id="attrib_color" onchange="getattribno()">
      <?php
		if (trim($good_color)!=""){
			$Good_color_array    =  explode(',',trim($good_color));

			if (is_array($Good_color_array)){
				foreach($Good_color_array as $k=>$v )
				{
					$Good_Color_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Color_Option = "<option value='".$v."'>".$v."</option>\n";
				$Good_color_array = array();
			}
		}else{
			$Good_Color_Option = "<option value=''>無</option>\n";
			$Good_color_array = array("");
		}
		echo $Good_Color_Option;
	   ?>
    </select></td>
  </tr>
  <tr id="divshowsize">
    <td width="67" align="right" bgcolor="#FFFFFF">尺寸</td>
    <td width="173" align="left" bgcolor="#FFFFFF"><select name="size" id="attrib_size" onchange="getattribno()">
      <?php
		if (trim($good_size)!=""){
			$Good_size_array    =  explode(',',trim($good_size));

			if (is_array($Good_size_array)){
				foreach($Good_size_array as $k=>$v ){
					$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";
				$Good_size_array = array("");
			}
		}else{
			$Good_Size_Option = "<option value=''>無</option>\n";
			$Good_size_array = array("");
		}
		echo $Good_Size_Option;
	?>
    </select></td>
  </tr>

  <tr>
    <td align="right" bgcolor="#FFFFFF">貨號</td>
    <td align="left" bgcolor="#FFFFFF"><input name="attribno" id="attribno" type="text" value="" size="20" maxlength="100" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFFF">國際碼</td>
    <td align="left" bgcolor="#FFFFFF"><input name="guojima" id="guojima" type="text" value="" size="20" maxlength="100" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFFF">原出廠碼</td>
    <td align="left" bgcolor="#FFFFFF"><input name="orgno" id="orgno" type="text" value="" size="20" maxlength="100" /></td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF"><label>
      <input type="button" name="attribSave" id="attribSave" value="保存" />
      <input type="button" name="button2" id="button2" value="返回" onclick="closeWin();" />
    </label></td>
  </tr>
</table>
</form>
</div>
<script language="javascript">
function getattribno(){
	//alert("action=get&gid=<?php echo $gid;?>&goodstype=" + $('#goodstype').val() + "&detail_id=" + $('#detail_id').val() + "&color=" + encodeURI($('#color').val()) + "&size=" + encodeURI($('#size').val()) + "");return;
	//f = document.getElementById("storageForm");
	//goodstype = f.getElementById("goodstype");
	//alert(goodstype);
	$.ajax({
		  url: "admin_goods_ajax_attribno_save.php",
		  data: "action=get&gid=<?php echo $gid;?>&color=" + encodeURIComponent($('#attrib_color').val()) + "&size=" + encodeURIComponent($('#attrib_size').val()) + "",
		  type:'get',
		  dataType:"json",
		  success: function(msg){
		  //alert(msg);
        $('#anid').attr("value",msg.anid);
			  $('#attribno').attr("value",msg.goodsno);
			  $('#guojima').attr("value",msg.guojima);
			  $('#orgno').attr("value",msg.orgno);
			  //$('#classcount').attr("value",counts+1);
			  //$(msg).appendTo('#extclass')
		  }
	});
}

var options_ab = {
	success:       function(msg){
					if (msg==1){
						alert('修改成功');
						closeWin();
					}else{
						alert('貨號不可重複!');
					}
				},
	type:      'post',
	dataType:  'json',
	clearForm: false
};
$("#attribSave").click(function(){$('#attribForm').ajaxSubmit(options_ab);});
getattribno();
</script>
