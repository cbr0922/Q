<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
include_once Resources."/ckeditor/ckeditor.php";

$goods_id = intval($_GET['goods_id']);
if ($_GET['soid']!="" && $_GET['Action']=='Modi'){
	$soid = intval($_GET['soid']);
	$Action_value = "Update";
	$Action_say  = "修改商品促銷價格資料"; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}goods_saleoffe` where soid=".intval($soid)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$soid =  $Result['soid'];
		$goods_id = $gid  =  $Result['gid'];
		$mincount  =  $Result['mincount'];
		$maxcount  =  $Result['maxcount'];
		$price  =  $Result['price'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增商品促銷價格資料" ; //插入
}

$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($goods_id)." limit 0,1");
$Num_goods   = $DB->num_rows($Query_goods);
if ($Num_goods>0){
	$Result_goods= $DB->fetch_array($Query_goods);
	$Goodsname  =  $Result_goods['goodsname'];
}

?>

<div style="height:400px;overflow:auto"><FORM name=saleoffeform id=saleoffeform action='admin_goods_ajax_saleoffe_save.php' method=post>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="soid" value="<?php echo $soid?>">
  <input type="hidden" name="goods_id" value="<?php echo $goods_id?>">
                  <TABLE cellSpacing=0 cellPadding=2   width="90%" align=center border=0>

                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="39%">
                      最少購買數量：</TD>
                      <TD width="61%" align=left noWrap>
                      <?php echo $FUNCTIONS->Input_Box('text','mincount',$mincount," maxLength=50 size=10 ")?>
                      <label style="display:none" for="detail_name" class="wrong" metaDone="true" generated="true"></label>					  </TD>
					  </TR>


                    <TR>
                      <TD noWrap align=right width="39%">最大購買數量 ： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','maxcount',$maxcount,"      maxLength=50 size=10 ")?></TD></TR>
                    <TR>
                      <TD align=right noWrap>售價： </TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','price',$price," maxLength=10 size=10 onchange='setprice(this.value)' ")?></TD>
                    </TR>
                    <?php
                      $Sql_l      = "select *  from `{$INFO[DBPrefix]}user_level` u order by u.level_num asc";
						$Query_l    = $DB->query($Sql_l);
						$Nums_l      = $DB->num_rows($Query_l);
						while ($Rs_l=$DB->fetch_array($Query_l)) {
							$Sql_M    = "select * from `{$INFO[DBPrefix]}member_price` where m_level_id=".intval($Rs_l['level_id'])." and m_saleoffid=".intval($soid)." and m_goods_id='" . $goods_id . "' limit 0,1";
					  $Query_M  = $DB->query($Sql_M);
					  $Result_M = $DB->fetch_array($Query_M);
					  $Nums_M      = $DB->num_rows($Query_M);
					  if($Nums_M>0)
					  	$price = $Result_M['m_price'];
					  else
					  	$price = intval($Rs_l['pricerate']/100*$price);
					  ?>
                      <TR>
                      <TD align=right noWrap><?php echo $Rs_l['level_name']?>售價： </TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','price' . $Rs_l['level_id'],$price,"      maxLength=10 size=10 ")?></TD>
                    </TR>
                      <?php
						}
					  ?>

                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align="left"><input type="button" name="saleoffesave" id="saleoffesave" value="保存" />
                      <input type="button" name="cPic" id="cPic" value="返回" onclick="closeWin();" /></TD>
                    </TR>
                    </TBODY>

  </TABLE></FORM>
</div>
<script>
function setprice(price){
	<?php
	$Sql_l      = "select *  from `{$INFO[DBPrefix]}user_level` u order by u.level_num asc";
						$Query_l    = $DB->query($Sql_l);
						$Nums_l      = $DB->num_rows($Query_l);
						while ($Rs_l=$DB->fetch_array($Query_l)) {
	?>
	//alert("<?php echo intval($Rs_l['pricerate'])?>");
	$('#price<?php echo $Rs_l['level_id'];?>').attr("value",parseInt(<?php echo intval($Rs_l['pricerate'])?>/100*price));
	<?php
		}
	?>
}
$(document).ready(function() {
	var options11 = {
		success:       function(msg){
			//alert(msg);
						if (msg==1){
								closeWin();
								showtajaxfun("goodssaleoffe");
						}else{
							alert(msg);
						}
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
	$("#saleoffesave").click(function(){
		if (isNaN($('#price').val())){
			alert("請填寫正確的價格");
			return false;
		}
		if ($('#price').val()<=0){
			alert("請填寫正確的價格");
			return false;
		}
		if (parseInt($('#price').val(),10)!=$('#price').val()){
			alert("請填寫正確的價格");
			return false;
		}
		 <?php
            $Sql_l      = "select *  from `{$INFO[DBPrefix]}user_level` u order by u.level_num asc";
						$Query_l    = $DB->query($Sql_l);
						$Nums_l      = $DB->num_rows($Query_l);
						while ($Rs_l=$DB->fetch_array($Query_l)) {

		?>
		if (isNaN($('#price<?php echo $Rs_l['level_id'];?>').val())){
			alert("請填寫正確的價格");
			return false;
		}
		if ($('#price<?php echo $Rs_l['level_id'];?>').val()<=0){
			alert("請填寫正確的價格");
			return false;
		}
		if (parseInt($('#price<?php echo $Rs_l['level_id'];?>').val(),10)!=$('#price<?php echo $Rs_l['level_id'];?>').val()){
			alert("請填寫正確的價格");
			return false;
		}

    <?php
						}
		?>

		$('#saleoffeform').ajaxSubmit(options11);});


})
</script>
