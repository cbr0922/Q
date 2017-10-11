<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";

$goods_id = intval($_GET['goods_id']);
if ($_GET['detail_id']!="" && $_GET['Action']=='Modi'){
	$detail_id = intval($_GET['detail_id']);
	$Action_value = "Update";
	$Action_say  = "修改商品詳細資料"; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}goods_detail` where detail_id=".intval($detail_id)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$detail_id =  $Result['detail_id'];
		$goods_id = $gid  =  $Result['gid'];
		$detail_name  =  $Result['detail_name'];
		$detail_bn  =  $Result['detail_bn'];
		$detail_price  =  $Result['detail_price'];
		$detail_pricedes  =  $Result['detail_pricedes'];
		$detail_des  =  $Result['detail_des'];
		$detail_pic  =  $Result['detail_pic'];
		$storage  =  $Result['storage'];
		$detail_cost  =  $Result['detail_cost'];
		$guojima  =  $Result['guojima'];
		$orgno  =  $Result['orgno'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增加商品詳細資料" ; //插入
}

$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($goods_id)." limit 0,1");
$Num_goods   = $DB->num_rows($Query_goods);
if ($Num_goods>0){
	$Result_goods= $DB->fetch_array($Query_goods);
	$Goodsname  =  $Result_goods['goodsname'];
}

?>

<div style="height:400px;overflow:auto">
  <FORM name=detailform id=detailform action='admin_goods_ajax_detail_save.php' method=post encType=multipart/form-data>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="detail_id" value="<?php echo $detail_id?>">
  <input type="hidden" name="goods_id" value="<?php echo $goods_id?>">
  <input type="hidden" name="old_pic" value="<?php echo $detail_pic?>">
                  <TABLE cellSpacing=0 cellPadding=2   width="100%" align=center border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="18%">
                        <DIV align=right>商品名稱：</DIV></TD>
                      <TD noWrap align=right>
                        <DIV align=left><?php echo $FUNCTIONS->Input_Box('text','detail_name',$detail_name,"      maxLength=50 size=40 ")?><label style="display:none" for="detail_name" class="wrong" metaDone="true" generated="true"></label></DIV>					  </TD>
					  </TR>


                    <TR>
                      <TD noWrap align=right width="18%">型號 ： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','detail_bn',$detail_bn,"      maxLength=50 size=40 ")?></TD></TR>
					<TR>
                      <TD noWrap align=right width="18%">國際條碼 ： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','guojima',$guojima,"      maxLength=50 size=40 ")?></TD></TR>
					<TR>
                      <TD noWrap align=right width="18%">原廠條碼 ： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','orgno',$orgno,"      maxLength=50 size=40 ")?></TD></TR>
                    <TR>
                      <TD noWrap align=right>原價 ： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','detail_price',$detail_price,"      maxLength=10 size=10 ")?></TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>售價： </TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','detail_pricedes',$detail_pricedes,"      maxLength=10 size=10 ")?></TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>成本：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','detail_cost',$detail_cost,"      maxLength=10 size=10 ")?>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>庫存：</TD>
                      <TD align=left noWrap>
					  <?php
					  if ($_GET['Action'] == "Modi"){
						  echo $storage;
					 ?>
                     <a href="javascript:void(0);" onclick="showWin('url','admin_goods_ajax_changestorage.php?gid=<?php echo $goods_id?>','',750,450);">設置</a>
					  <?php
					  }else{
					  echo $FUNCTIONS->Input_Box('text','storage',$storage,"      maxLength=10 size=10 ");
					  }
					  ?></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>商品說明： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('textarea','detail_des',$detail_des," cols=80 rows=6  ")?></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>商品圖片： </TD>
                      <TD noWrap align=left><INPUT  id="img"  type="file" size="40" name="img" ></TD>
                    </TR>
					<?php if (is_file("../".$INFO['good_pic_path']."/".$detail_pic)){?>

                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align="left">
					  <div id="Mid_img">
					  &nbsp;<img src="<?php echo "../".$INFO['good_pic_path']."/".$detail_pic?>" width="100">
					  <a href="#" id="delgpic"><font color="#FF0000"><?php echo $Basic_Command['Del']?></font></a>					  </div>                      </TD>
                      </TR>

					<?php } ?>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align="left"><input type="button" name="detailsave" id="detailsave" value="保存" />
                      <input type="button" name="cPic" id="cPic" value="返回" onclick="closeWin();" /></TD>
                    </TR>
                    </TBODY>
                    </TABLE>
                   </FORM>
                    </div>
<script>
$(document).ready(function() {
	var options = {
		success:       function(msg){
						if (msg==1){
								closeWin();
								showtajaxfun("goodsdetail");
						}else{
							alert('型號不可重複!');
						}
					},
		type:      'post',
		dataType:  'json',
		clearForm: false
	};
	/*
	$("#detailform").validate({
		errorClass: "wrong",
		rules: {
			detail_name: {required:true},
		},
		messages: {
			detail_name: {required: "請填寫商品名稱"},
		},
		submitHandler: function() {
			$('#detailform').ajaxForm(options);
		}
	});
	*/
	$("#detailsave").click(function(){
		if (isNaN($('#detail_price').val())){
			alert("請填寫正確的價格");
			return false;
		}
		if ($('#detail_price').val()<=0){
			alert("請填寫正確的價格");
			return false;
		}
		if (parseInt($('#detail_price').val(),10)!=$('#detail_price').val()){
			alert("請填寫正確的價格");
			return false;
		}
		if (isNaN($('#detail_pricedes').val())){
			alert("請填寫正確的價格");
			return false;
		}
		if ($('#detail_pricedes').val()<=0){
			alert("請填寫正確的價格");
			return false;
		}
		if (parseInt($('#detail_pricedes').val(),10)!=$('#detail_pricedes').val()){
			alert("請填寫正確的價格");
			return false;
		}
	$('#detailform').ajaxSubmit(options);});
	$("#delgpic").click(function(){
		$.ajax({
					url: "admin_goods_ajax_detail_save.php",
					data: 'goods_id=<?=$goods_id?>&Action=DelPic&detail_id=<?php echo $detail_id?>',
					type:'get',
					dataType:"html",
					success: function(msg){
						$('#Mid_img').html("");
					}
		});
	});


})
</script>
