<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
include_once Resources."/ckeditor/ckeditor.php";

$goods_id = intval($_GET['goods_id']);
if ($_GET['detail_id']!="" && $_GET['Action']=='Modi'){
	$detail_id = intval($_GET['detail_id']);
	$Action_value = "Update";
	$Action_say  = "修改商品促銷價格信息"; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}goods_saleoffe` where soid=".intval($detail_id)." limit 0,1";
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
	$Action_say   = "添加商品促銷價格信息" ; //插入
}

$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($goods_id)." limit 0,1");
$Num_goods   = $DB->num_rows($Query_goods);
if ($Num_goods>0){
	$Result_goods= $DB->fetch_array($Query_goods);
	$Goodsname  =  $Result_goods['goodsname'];
}

?>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<script>
var optionsc = {
		success:       function(msg){
			//alert(msg);
						if (msg=="1"){
								closeWin();
								showtajaxfun('goodssaleoffe');
							}
						if (msg=="2"){
							alert('請填寫正確的價格');
								closeWin();
								showtajaxfun('goodssaleoffe');
							}
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};

</script>
<div style="height:400px;overflow:auto">
                  <TABLE cellSpacing=0 cellPadding=2   width="90%" align=center border=0>
  <FORM name=saleoffeform id=saleoffeform action='admin_goods_ajax_saleofee_save.php' method=post>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="soid" value="<?php echo $soid?>">
  <input type="hidden" name="goods_id" value="<?php echo $goods_id?>">
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="43%">
                      最少購買數量：</TD>
                      <TD width="57%" align=left noWrap>
                      <?php echo $FUNCTIONS->Input_Box('text','mincount',$mincount," maxLength=50 size=10 ")?>
                      <label style="display:none" for="detail_name" class="wrong" metaDone="true" generated="true"></label></TD>
					  </TR>
			
					 
                    <TR>
                      <TD noWrap align=right width="43%">最大購買數量 ： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','maxcount',$maxcount,"      maxLength=50 size=10 ")?></TD></TR>
                    <TR>
                      <TD align=right noWrap>售價： </TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','price',$price,"      maxLength=10 size=10 ")?></TD>
                    </TR>
					
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align="left"><input type="button" name="saleoffesave" id="saleoffesave" value="保存" />
                      <input type="button" name="cPic" id="cPic" value="返回" onclick="closeWin();" /></TD>
                    </TR>
                    </TBODY>
                    </FORM>
  </TABLE>
</div>
