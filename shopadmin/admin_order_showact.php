<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
?>
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td align="left">您真的要進行這個操作嗎？</td>
  </tr>
  <tr><td align="left" style="display:<?php if($_GET['state_type']==2&&$_GET['state_value']==4) echo "block"; else echo"none";?>"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr >
    <td>退款金額：<?php echo $FUNCTIONS->Input_Box('text','backPrices',"0","  maxLength=40 size=10 ")?><!--(值為-1即為退還商品全款)--></td>
  </tr>
  <tr>
    <td>退款方式：

      <input type="radio" name="refundtypes" id="refundtypes" value="1" />
      銀行帳戶（線下）
      <input type="radio" name="refundtypes" id="refundtypes" value="2" />刷退
      </td>
  </tr>
  <tr>
    <td>返還積點：<?php echo $FUNCTIONS->Input_Box('text','backBounss',"0","    maxLength=40 size=10 ")?></td>
  </tr>
  <tr><td><span style="font-size:10px;color: #F00;">　　　　　未出貨前的訂單取消後，系統已自動歸還積點</span></td></tr>
  <!--<tr>
    <td>退還金額及積點除了-1值外不可以為負數，如果商品是使用會員價格（會員價+積點）請填寫退還金額及積點</td>
  </tr>-->
  </table></td></tr>


  <tr style="display:<?php if(($_GET['state_type']==3&&$_GET['state_value']==1) || ($_GET['state_type']==3&&$_GET['state_value']==8)) echo "block"; else echo"none";?>">
    <td align="left">物流單號<?php echo $FUNCTIONS->Input_Box('text','piaocodes',"","    maxLength=40 size=10 ")?><br />出貨日期<input type="text" name="senddate" id="senddate"  onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" /><br />物流單位<input type="text" name="sendnames" id="sendnames" /></td>
  </tr>
  <tr>
    <td align="left">操作原因：</td>
  </tr>
  <tr>
    <td align="left"><textarea name="desc" id="desc" cols="37" rows="5"></textarea></td>
  </tr>
  <tr>
    <td align="center"><input type="button" name="button" id="button" value="確定送出" onClick="return submitOrderAct();">
    <input type="button" name="button2" id="button2" value="取消" onclick="closeWin();"></td>
  </tr>
</table>
<script language="javascript">
function submitOrderAct(){
	<?php
	if(($_GET['state_type']==1&&$_GET['state_value']==3) || ($_GET['state_type']==3&&($_GET['state_value']==4 || $_GET['state_value']==6 || $_GET['state_value']==13 || $_GET['state_value']==14 || $_GET['state_value']==15))){
	?>
	if ($('#desc').val()==""){
		alert("請填寫操作原因");
		return false;
	}
	<?php
	}
	?>
	<?php if($_GET['state_type']==2&&$_GET['state_value']==4){?>    if ($('#backPrices').val()==""){
  		$('#backPrices').attr("value",0)
  	}
    if ($('#backBounss').val()==""){
      $('#backBounss').attr("value",0)
    }

  	if ($('#backPrices').val()<=0){
  		alert("請填寫正確的退款金額");
  		return false;
  	}
  	if (isNaN($('#backPrices').val())){
  		alert("請填寫正確的退款金額");
  		return false;
  	}
  	if (parseInt($('#backPrices').val(),10)!=$('#backPrices').val()){
  		alert("請填寫正確的退款金額");
  		return false;
  	}

    if ($('#backBounss').val()<0){
      alert("請填寫正確的返還積點");
      return false;
    }
    if (isNaN($('#backBounss').val())){
      alert("請填寫正確的返還積點");
      return false;
    }
    if (parseInt($('#backBounss').val(),10)!=$('#backBounss').val()){
      alert("請填寫正確的返還積點");
      return false;
    }

	if($("input[type='radio'][name='refundtypes']:checked").length == 0){
	//if ($('#refundtypes').attr("value")==""){
		alert("請選擇退刷方式");
		return false;
	}
	<?php
	}
	?>
	//alert($(":radio[name='refundtypes']:checked").val());
	//alert($('#desc').val());
	$('#remark').val($('#desc').val());
	$('#backPrice').val($('#backPrices').val());
	$('#backBouns').val($('#backBounss').val());
	$('#piaocode').val($('#piaocodes').val());
	$('#sendtime').val($('#senddate').val());
	$('#sendname').val($('#sendnames').val());
	$('#refundtype').val($(":radio[name='refundtypes']:checked").val());
	$('#adminForm').attr("action","admin_order_act.php");
	//alert($('#adminForm').attr("action"));
	$('#adminForm').submit();
}
</script>