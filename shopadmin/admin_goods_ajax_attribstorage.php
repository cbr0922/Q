<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
$gid        = trim($FUNCTIONS->Value_Manage($_GET['gid'],'','back',''));
/**
	 * 这里是当供应商进入的时候。只能修改自己的产品资料。
	 */
if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$Provider_string = " and g.provider_id=".intval($_SESSION['sa_id'])." ";
}else{
	$Provider_string = "";
}
$G_Sql      = "select g.goodsname,g.storage,good_color,good_size,g.bn from `{$INFO[DBPrefix]}goods` g where g.gid='".intval($gid)."' ".$Provider_strings." limit 0,1";
$G_Query    = $DB->query($G_Sql);
$G_Num      = $DB->num_rows($G_Query);
if ($G_Num>0){
	$G_result    =  $DB->fetch_array($G_Query);
	$G_goodsname = $G_result['goodsname'];
	$G_storage     = $G_result['storage'];
	$good_color = $G_result['good_color'];
	$good_size = $G_result['good_size'];
	$G_bn        = $G_result['bn'];
}else{
	$FUNCTIONS->sorry_back('back','');
}

$DB->free_result($G_Query);

$Sql      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid);
$Query    = $DB->query($Sql);
$Nums      = $DB->num_rows($Query);
if ($Nums>0){
	$Nav->total_result=$Nums;
	$Nav->execute($Sql,$Nums);
}

?>
 <TABLE class=allborder cellSpacing=0 cellPadding=2 width="100%" bgColor=#f7f7f7 border=0  >
                    <TBODY>
                    <TR>
                      <TD align=right valign="middle" noWrap>&nbsp;</TD>
                    </TR>
                    <tr><td>
<form action="admin_goods_ajax_attribstorage_save.php" method=post id="storageform">
<input type="hidden" name="action" value="add">
<INPUT type=hidden name=gid value="<?php echo $gid?>">
                  <table width="100%" border="0">
                    <tr>
                      <td>商品顏色<select name="color"><?php if (trim($good_color)!=""){
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
		$Good_Color_Option = "";
		$Good_color_array = array("");
	}
	echo $Good_Color_Option;
	?></select>商品尺寸<select name="size"><?php if (trim($good_size)!=""){
		$Good_size_array    =  explode(',',trim($good_size));

		if (is_array($Good_size_array)){
			foreach($Good_size_array as $k=>$v )
			{
				$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";
			$Good_size_array = array("");
		}
	}else{
		$Good_Size_Option = "";
		$Good_size_array = array("");
	}
	echo $Good_Size_Option;
	?></select>庫存數量
                        <input name="storage" type="text" id="storage">
                        <input type="button" name="Submit" id="storagesave" value="新增"></td>
                    </tr>
                  </table>
				  </form>
                  <TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                    <FORM name=form1 action="admin_goods_ajax_attribstorage_save.php" method=post id="storagelistform">
					<INPUT type=hidden name=act>
					<INPUT type=hidden name=gid value="<?php echo $gid?>">
					 <INPUT type=hidden value=0  name=boxchecked>
                    <TBODY>
                    <TR align=middle>
                      <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26><?php echo $Basic_Command['SNo_say'];//序号?></TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>商品顏色</TD>
                     
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>商品尺寸</TD>
                      <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>庫存數量</TD>
                      </TR>
					<?php
					$i=1;

					while ($Rs=$DB->fetch_array($Query)) {
					//print_r($Rs);
					if ((in_array($Rs['color'],$Good_color_array) || trim($Rs['color']) == "") && (in_array($Rs['size'],$Good_size_array) || trim($Rs['size'])=="")){

					?>
                    <TR class=row0>
                      <TD align=middle width=91 height=20>
                      <?php echo $i?>					  </TD>
                      <TD height=20 align="left" noWrap>
                        <?php echo $Rs['color']?>                      </TD>
                      <TD align=left nowrap><?php echo $Rs['size']?> </TD>
                      <TD height=20 align=center nowrap>
					 
					  <input type="hidden" name="storage_id[]" value="<?php echo $Rs['storage_id']?>">
					  <?php echo $FUNCTIONS->Input_Box('text','storage[]',intval($Rs['storage']),"      maxLength=10 size=10 onblur='changestorage();'")?>
					  			  </TD>
                      </TR>
					<?php
					$i++;
					}
					}
					?>
                    <TR>
                      <TD align=middle width=91 height=14>&nbsp;</TD>
                      <TD width=245 height=14>&nbsp;</TD>
                      <TD width=202 align="left">&nbsp;</TD>
                      <TD width=202 height=14>&nbsp;</TD>
                      </TR>
					 </FORM>
					 </TABLE>
                     </TR></TR>
<TR>
                      <TD align=right valign="middle" noWrap>&nbsp;</TD>
                    </TR>
                    </tbody></Table>
<script>
$(document).ready(function() {
	var options = {
		success:       function(msg){
						if (msg==1){
							showtajaxfun('storage');
						}else{
							alert(msg);
						}
					},
		type:      'post',
		dataType:  'json',
		clearForm: true
	};
	
	$("#storagesave").click(function(){$('#storageform').ajaxSubmit(options);});
	
	
						   });
var options1 = {
	success:       function(msg){
						if (msg==1){
							showtajaxfun('storage');
						}else{
							alert(msg);
						}
					},
		type:      'post',
		dataType:  'json',
		clearForm: true
	};
function changestorage(){
		$('#storagelistform').ajaxSubmit(options1);
	}
</script>
            