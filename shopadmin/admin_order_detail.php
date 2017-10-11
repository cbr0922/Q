<?php
include_once "Check_Admin.php";
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
include_once 'crypt.class.php';

include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Order_Pack_Txt.php";

include_once Classes . "/orderClass.php";
$orderClass = new orderClass;

if ($_POST['act']=="save"){
			$db_string = $DB->compile_db_update_string( array (
			'receiver_name'             => trim($_POST['Receiver_name']),
			'receiver_email'             => trim($_POST['Receiver_email']),
			'receiver_address'             => trim($_POST['Receiver_address']),
			'receiver_mobile'             =>  MD5Crypt::Encrypt ( trim($_POST['Receiver_mobile']), $INFO['mcrypt']),//trim($_POST['Receiver_mobile']),
			'receiver_tele'             => MD5Crypt::Encrypt ( trim($_POST['Receiver_tele']), $INFO['tcrypt']),//trim($_POST['Receiver_tele']),
			'receiver_post'             => trim($_POST['Receiver_post']),
			'receiver_memo'             => trim($_POST['Receiver_memo']),
			)      );	
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_id='".intval($_POST['orderid'])."'";

			$Result_Insert = $DB->query($Sql);
			$FUNCTIONS->header_location('admin_order.php?order_id='. intval($_POST['orderid']));
}


if (is_array($_GET[cid])){
	$Order_id = 	$_GET[cid][0];
}else{
	$Order_id        = intval($FUNCTIONS->Value_Manage($_GET['order_id'],$_POST['order_id'],'back',''));
}
$Query_first = $DB->query(" select ot.order_state,ot.order007_begtime,ot.order007_status,ot.order007_content,ttime.transtime_id,ttime.transtime_name,ot.ticketmoney,ot.ticketid,ot.ticketcode from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_id='".intval($Order_id)."' limit 0,1");
$Num_first   = $DB->num_rows($Query_first);

if ($Num_first>0){
	$Result_first           = $DB->fetch_array($Query_first);
	$Transtime_id           = $Result_first[transtime_id];
	$Transtime_name         = $Result_first[transtime_name];
	$Order007begtime        = $Result_first[order007_begtime]!="" ? $Result_first[order007_begtime] : "0000-00-00" ;
	$Order007status         = $Result_first[order007_status];
	$Order007Content        = $Result_first[order007_content];
	$ticketMoney = $Result_first[ticketmoney];
	$ticketid = $Result_first[ticketid];
}else{
	$FUNCTIONS->sorry_back('back','');
}



$Sql = "select ot.* from  `{$INFO[DBPrefix]}order_table` ot where ot.order_id=".$Order_id." ";


$Query    = $DB->query($Sql);

$Num      = $DB->num_rows($Query);
if ($Num>0){
	
	$Sql_c = "select count(*) as counts from  `{$INFO[DBPrefix]}order_detail` ot where ot.order_id='".$Order_id."' ";
	$Query_c    = $DB->query($Sql_c);
	$Rs_c=$DB->fetch_array($Query_c);
	$Nums     = $Rs_c['counts'] ;
	$Rs=$DB->fetch_array($Query);
}else{
	$FUNCTIONS->sorry_back('back','');
}
//print_r($Rs);
//echo "<hr color=green>";
//print_r($_SESSION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Order_Detail]?>  ---  <?php echo $INFO[company_name]?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<div id="fullBg"></div>
<div id="msg">
<div id="close"></div>
<div id="ctt"></div>
</div>
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toDelivered(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');	
	if (checkvalue!=false){
	    if (confirm('<?php echo $Order_Pack[Order_Js_one]?>')){  //"是否取消定单操作? 操作对象要求:只有当定单状态为<未确定>或<确定>并且<未交付>的时候。本次操作对已经归档的资料无效！ "
		document.adminForm.action = "admin_order_act.php";
		document.adminForm.act.value='toDelivered';
		document.adminForm.submit();
		}  
	}
}

function toConfirm(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	
	if (checkvalue!=false){
		document.adminForm.action = "admin_order_act.php";
		document.adminForm.act.value='toConfirm';
		document.adminForm.submit();
	}
}

function toRestart(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	
	if (checkvalue!=false){
		document.adminForm.action = "admin_order_act.php";
		document.adminForm.act.value='toRestart';
		document.adminForm.submit();
	}
}


function toPayed(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	
	if (checkvalue!=false){
	 if (confirm('<?php echo $Order_Pack[Order_Js_two]?>')){  //是否操作到款? 操作对象要求:当定单状态不为<已取消>及不为<未确定>的时候。本次操作对已经归档的资料无效！
		document.adminForm.action = "admin_order_act.php";
		document.adminForm.act.value='toPayed';
		document.adminForm.submit();
 	 }
	}
}


function toConsignment(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	
	if (checkvalue!=false){
	  if (confirm('當訂單為未取消或已確認才能進行此操作')){ //"是否操作发货? 操作对象要求:当定单状态不为<已取消>或不为<未确定>并且为<已交付>的时候。本次操作对已经归档的资料无效！
		document.adminForm.action = "admin_order_act.php";
		document.adminForm.act.value='toConsignment';
		document.adminForm.submit();
		}
	}
}

function toPrintDown(id){
	location.href="admin_order_print.php?order_id="+id;
	/*
		document.OrderPrint.order_id.value=id;
		OrderPrint.target="_blank";
		OrderPrint.submit();
		*/
}

function toBack(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	
	if (checkvalue!=false){
	  if (confirm('<?php echo $Order_Pack[Order_Js_three]?>')){ //"是否操作发货? 操作对象要求:当定单状态不为<已取消>或不为<未确定>并且为<已交付>的时候。本次操作对已经归档的资料无效！
		document.adminForm.action = "admin_order_act.php";
		document.adminForm.act.value='toBack';
		document.adminForm.submit();
		}
	}
}
function toPigeonhole(){
    
  var flag ;
	    flag=confirm("<?php echo $Order_Pack[Order_Js_four];?>");
		if(flag==true){
		 document.adminForm.action = "admin_order_act.php";
		 document.adminForm.act.value='toPigeonhole';
		 document.adminForm.submit();
	    }else{
		//window.history.back(-1);
		}
}



function toPrint(order_id){

        //javascript:print();
		window.open("admin_order_more.php?order_id="+order_id);
		//location.href="admin_order_more.php?order_id="+order_id;
		
}

function changeOrderState(state_values,state_types){
	document.all.state_value.value =state_values; 
	document.all.state_type.value =state_types; 
	showWin('url','admin_order_showact.php?state_value='+state_values+'&state_type='+state_types,'',350,350);	
}

</SCRIPT>
<script language="javascript">
function ChangeTonum(order_group_id){
	var order_group_id = order_group_id;
	$("input[name='tonum[]']").each(function(k,v){
		//alert(this.id);
		//alert("tonum" + order_group_id + "_" + k);
		if (this.id == "tonum" + order_group_id + "_" + k)
			this.value = $('#tonum_group' + order_group_id).attr("value");
	}); 
}
function ClickGroup(order_group_id){
	var order_group_id = order_group_id;
	//alert("a");
	$("input[name='cid[]']").each(function(k,v){
	   // alert(this.id);
	   //alert($('#gdid' + order_group_id).attr("checked"));
		
		if (this.id == "cb" + order_group_id + "_" + k)
			if($('#gdid' + order_group_id).attr("checked") == true)
				this.checked = true;
			else
				this.checked = false;
				
	});	
}
function CcheckAll(){
	$("input[name='cid[]']").each(function(k,v){
		if($('#Ctoggle').attr("checked") == true)
			this.checked = true;
		else
			this.checked = false;
	});	
	$("input[name='gdid[]']").each(function(k,v){
		if($('#Ctoggle').attr("checked") == true)
			this.checked = true;
		else
			this.checked = false;
	});	
}
</script>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0 style="margin-bottom:5px;margin-top:10px">
        <TBODY>
          <TR>
            <TD width="16%">
              <TABLE width="160" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD width="199" noWrap class=p12black><SPAN class=p9orange><?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Order_Detail];//定单明细?> </SPAN>				</TD>
              </TR></TBODY></TABLE>            </TD>
            <TD width="84%" align="right">
            <div class="order_button">
            <ul>
             <li><a href="javascript:history.back(-1);">返回</a></li>
             <li><a href="admin_order_more.php?order_id=<?php echo $Order_id?>" target="_blank">揀貨明細</a></li>
            </ul>
            </div>
           </TD>
            </TR>
          </TBODY>
        </TABLE>
      
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="allborder" style="margin-top:10px;margin-bottom:10px">
        <tr>
          <td width="56" height="123" align="center" valign="top" style="padding-top:8px"><img src="images/<?php echo $INFO[IS]?>/note01.gif" width="24" height="24"></td>
          <td><div align="left"><?php echo $Order_Pack[Order_Txt_Title];?>：</div><!--訂單操作方式-->
            <div id="tip01tips" class="tips_note" align="left">
              <?php echo $Order_Pack[Order_Txt_Content_I]?><br>
              <?php echo $Order_Pack[Order_Txt_Content_II]?><br>
              <?php echo $Order_Pack[Order_Txt_Content_IV]?><br>
              <?php echo $Order_Pack[Order_Txt_Content_V]?><br>
              </div></td>
          </tr>
        </table>
  <br>
<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="allborder" style="margin-top:10px;margin-bottom:10px">
                          <?php
					 $Order_serial = $Rs['order_serial'];
						$User_id      = $Rs['user_id'];
						$Order_Time   = date("Y-m-d H: i a ",$Rs['createtime']);
						$Paymentname  = $Rs['paymentname'];
						$Deliveryname = $Rs['deliveryname'];
						$Totalprice   = $Rs['totalprice'];
						$Transport_price = $Rs['transport_price'];
						?>

        <tr>
          <td width="20" align="left" valign="top" style="padding-top:16px;padding-left:10px;"><?php echo $Order_Pack[OrderSerial_say];//订单编号?>：</td>
          <td width="100" align="left" valign="top" style="padding-top:8px"><font color="#bb1c21" size="5"><b><?php echo $Order_serial?></b></font></td>
          <td width="334"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="23%" align="right" valign="middle">訂單狀態 ：</td>
              <td width="77%" valign="top"><div class="order_button">
            <ul>
             <li><a href="javascript:changeOrderState(1,1);">確認訂單</a></li>
             <li><a href="javascript:changeOrderState(3,1);">取消訂單</a></li>
             <li><a href="javascript:changeOrderState(4,1);">完成交易</a></li>
            </ul>
            </div></td>
            </tr>
            <tr>
              <td align="right" valign="middle">付款狀態 ：</td>
              <td valign="top"><div class="order_button">
            <ul>
             <li><a href="javascript:changeOrderState(1,2);">到款</a></li>
             <li><a href="javascript:changeOrderState(4,2);">退款中</a></li>
            </ul>
            </div></td>
            </tr>
            <tr>
              <td align="right" valign="middle">配送狀態 ：</td>
              <td valign="top"><div class="order_button">
            <ul>
             <li><a href="javascript:changeOrderState(12,3);">備貨</a></li>
             <li><a href="javascript:changeOrderState(1,3);">出貨</a></li>
             <li><a href="javascript:changeOrderState(2,3);">已到貨</a></li>
             <li><a href="javascript:changeOrderState(18,3);">已取貨</a></li>
             <li><a href="javascript:changeOrderState(6,3);">退貨</a></li>
             <li><a href="javascript:changeOrderState(20,3);">商品取回</a></li>
             <li><a href="javascript:changeOrderState(17,3);">逾期未取店退</a></li>
             <li><a href="javascript:changeOrderState(13,3);">退貨異常</a></li>
            </ul>
            </div></td>
            </tr>
          </table></td>
        </tr>
  </table>  
  <br>      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD align="left" vAlign=top>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
					<FORM name='adminForm' id='adminForm' action="" method=post>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
                        
                          <input type='hidden' name="Url" value="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>">
                          <input type='hidden' name="Order_id" value="<?php echo $Order_id?>">					
                          <INPUT type=hidden name=act id=act>
                          <INPUT type=hidden name='Num' value="<?php echo $Num?>">
                          <INPUT type=hidden value=0  name=boxchecked>
                          <INPUT type=hidden name=remark id=remark>
                          <INPUT type=hidden name=state_value>
                          <INPUT type=hidden name=optype value="2">
                          <INPUT type=hidden name=state_type> 
                          <INPUT type=hidden name=backPrice id=backPrice> 
                          <INPUT type=hidden name=backBouns id=backBouns>
                          <INPUT type=hidden name=piaocode id=piaocode> 
                          <INPUT type=hidden name=sendtime id=sendtime> 
                          <INPUT type=hidden name=sendname id=sendname> 
                          <INPUT type=hidden name=refundtype id=refundtype> 
                          <?php
					 $Order_serial = $Rs['order_serial'];
						$User_id      = $Rs['user_id'];
						$Order_Time   = date("Y-m-d H: i a ",$Rs['createtime']);
						$Paymentname  = $Rs['paymentname'];
						$Deliveryname = $Rs['deliveryname'];
						$Totalprice   = round($Rs['totalprice'],0);
						$Transport_price = $Rs['transport_price'];



						switch (intval($Rs['ifinvoice'])){
							case 0:
								$Ifinvoice   = $Cart[Two_piao];
								$Invoiceform = $Basic_Command['Null'];
								$TheOneNum   = $Basic_Command['Null'];
								$pCarrierNum = $Rs['pCarrierNum'];
								$pCarrierType = $Rs['pCarrierType'];
								break;
							case 1:
								$Ifinvoice   =  $Cart[Three_piao];
								$Invoiceform =  trim($Rs['invoiceform']);
								$TheOneNum   =  "<font color=red>".trim($Rs['invoice_num'])."</font>";

								break;
							case 2:
								$Ifinvoice   = $Basic_Command['Null'];
								$Invoiceform = $Basic_Command['Null'];
								$TheOneNum   = $Basic_Command['Null'];
								break;
							case 3:
								$Ifinvoice   =  "捐贈華民國全球元母大慈協會";
								$Invoiceform =  $Basic_Command['Null'];
								$TheOneNum   =  $Basic_Command['Null'];

								break;
						}




						$Order_state      = $Rs['order_state'];
						$Pay_state        = $Rs['pay_state'];
						$Transport_state        = $Rs['transport_state'];
						$Receiver_name    = $Rs['receiver_name'];
						$ATM              = trim($Rs['atm'])!="" ? trim($Rs['atm']) : $Basic_Command['Null'] ;
						$Receiver_address = $Rs['receiver_address'];
						$Receiver_email   = $Rs['receiver_email'];
						$Receiver_post    = $Rs['receiver_post'];
						$Receiver_tele    = MD5Crypt::Decrypt ( trim($Rs['receiver_tele']), $INFO['tcrypt']);
						$Receiver_mobile  = MD5Crypt::Decrypt ( trim($Rs['receiver_mobile']), $INFO['mcrypt']);//$Rs['receiver_mobile'];
						$Receiver_memo    = nl2br($Rs['receiver_memo']);
						$discount_totalPrices  = $Rs['discount_totalPrices']+$Rs['buyPoint'];
						
						$bonuspoint = $Rs[bonuspoint];
						$totalbonuspoint = $Rs[totalbonuspoint];
						$ticketcode = $Rs[ticketcode];
                        $saler = $Rs[saler];
						$rid = $Rs['rid'];
						$piaocode = $Rs['piaocode'];
						$ticket_discount_money = $Rs['ticket_discount_money'];
						$invoice_code = $Rs['invoice_code'];
						$sendtime = $Rs['sendtime'];
						$sendname = $Rs['sendname'];
						$totalGrouppoint = $Rs['totalGrouppoint'];
						$buyPoint = $Rs['buyPoint'];
						$paycode = $Rs['paycode'];
						$storename = $Rs['storename'];
						$senddate = $Rs['senddate'];
						$invoice_donate = $Rs['invoice_donate'];
						$invoice_print = $Rs['invoice_print'];
				$flightstyle = $Rs['flightstyle'];
				$flightid = $Rs['flightid'];
				$flightno = $Rs['flightno'];
				$flightdate = $Rs['flightdate'];
				$Departure = $Rs['Departure'];
                     if ($Rs['ifgroup']==1){
					 ?>
                          <TBODY>
                                    <tr>
            <td class="p12black"><br> <i class="icon-list-alt" style="font-size:14px; margin-right:5px; margin-left:5px; color:#666;"></i> 訂單操作記錄</td>
            </tr>

                            <TR align=middle>
                              <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                                <INPUT onclick=CcheckAll(); type=checkbox value=checkbox id="Ctoggle"   name=toggle> </TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>貨號</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Good[Product_Name];//商品名称?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 團購價</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 團購金</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 購買數量</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[AlsendProductNum];//已发货数量?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[NeedsendProductNum];//需发货数量?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[Items_xj_say];//单项小计?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>狀態</TD>
                              </TR>
                            <?php               
					$i=0;
					$Query_d = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_group` as g where order_id='".intval($Order_id)."' ");
					while ($Rs_d=$DB->fetch_array($Query_d)) {
					?>
                            <TR class=row0>
                              <TD align=middle height=20>
                                <INPUT type=checkbox id='gdid<?php echo $Rs_d['order_group_id']?>' name=gdid[] value="<?php echo $Rs_d['gdid']?>" onclick="ClickGroup(<?php echo $Rs_d['order_group_id'];?>);">                          </TD>
                              <TD align="left" noWrap><?php echo $Rs_d['groupbn']?></TD>
                              <TD height=20 align="left" noWrap>
                                
                                <?php 
						  echo "<a href=\"admin_groupdetail.php?Action=Modi&gid=" . $Rs_d['gdid'] . "\" target=\"_blank\">" . $Rs_d['groupname'] . "</a>";?>
                                </TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs_d['groupprice'];?>&nbsp;</TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs_d['grouppoint']?></TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs_d['count']?>&nbsp;</TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs_d['hadsend']?>&nbsp;</TD>
                              <TD height=20 align="center" noWrap>
                                <input type='text' id='tonum_group<?php echo $Rs_d['order_group_id'];?>' name='tonum_group<?php echo $Rs_d['order_group_id'];?>' size='5'  value='<?php echo $Tonum = intval($Rs_d['count'])-intval($Rs_d['hadsend'])?>'  onchange="ChangeTonum(<?php echo $Rs_d['order_group_id'];?>);">					  </TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs_d['groupprice']*$Rs_d['count'];?><?php if($Rs_d['grouppoint']>0) echo "+" . ($Rs_d['grouppoint']*$Rs_d['count']) . "團購金";?></TD>
                              </TR>
                            <?php               
					$j=0;
					$Query_g= $DB->query(" select g.* from `{$INFO[DBPrefix]}order_detail` as g where order_id='".intval($Order_id)."' and order_group_id='" . $Rs_d['order_group_id'] . "' order by order_detail_id desc ");
					while ($Rs_g=$DB->fetch_array($Query_g)) {
					?>
                            <TR class=row0 bgcolor="#F7F7F7">
                              <TD align=middle height=20>
                                <INPUT type=hidden value='<?php echo $Rs_g['order_detail_id']?>' name=Ci[]> 
                                <INPUT type=hidden value='<?php echo $Rs_g['gid']?>' name=gid[]> 
                                <INPUT id='cb<?php echo $Rs_d['order_group_id'];?>_<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs_g['order_detail_id']?>' name="cid[]" style="display:none">
                                </TD>
                              <TD align="left" noWrap><?php echo $Rs_g['bn']?></TD>
                              <TD height=20 align="left" noWrap>
                                
                                <?php 
						  echo "<a href=\"admin_goods.php?Action=Modi&gid=" . $Rs_g['gid'] . "\" target=\"_blank\">" . $Rs_g['goodsname'] . "</a>";
                        if (intval($Rs_g['provider_id'])>0){
							$Sql_p      = "select * from `{$INFO[DBPrefix]}provider` where  provider_id='".intval($Rs_g['provider_id']) . "' order by provider_idate  ";
							$Query_p    = $DB->query($Sql_p);
							$Rs_p=$DB->fetch_array($Query_p);
							echo "[<a href='admin_provider.php?Action=Modi&provider_id=" . $Rs_g['provider_id'] . "' target='_blank'>" . $Rs_p['provider_name'] . "</a>]";
						}
						?>
                                </TD>
                              
                              <TD height=20 align="center" noWrap>
                                </TD>
                              <TD height=20 align="center" noWrap>
                                </TD>
                              <TD height=20 align="center" noWrap>
                                <TD height=20 align="center" noWrap>
                                <?php echo $Rs_g['hadsend']?>&nbsp;</TD>
                              <TD height=20 align="center" noWrap>                        &nbsp;
                                <input type='text' name='tonum[]' id='tonum<?php echo $Rs_g['order_group_id'];?>_<?php echo $j;?>' size='5'  value='<?php echo $Tonum = intval($Rs_g['goodscount'])-intval($Rs_g['hadsend']) ?>' readonly="readonly">					  </TD>
                              <TD height=20 align="center" noWrap>
                                </TD>
                              <TD height=20 align=center nowrap><?php echo $orderClass->getOrderState($Rs_g['detail_order_state'],1)?>,<?php echo $orderClass->getOrderState(intval($Rs_g['detail_pay_state']),2)  ?>,<?php echo $orderClass->getOrderState(intval($Rs_g['detail_transport_state']),3)  ?>&nbsp;</TD>
                              </TR>
                            <input type="hidden" name="DetailOrderState[]" value="<?php echo $Rs_g['detail_order_state']?>">
                          <input type="hidden" name="DetailPayState[]" value="<?php echo $Rs_g['detail_pay_state']?>">
                          <?php
					$j++;
					$i++;
					}
					?>
                          
                          <?php
					
					}
					?>
                          <?php
					 }else{
					 ?>
                          <TBODY>
                            <TR align=middle>
                              <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>貨號</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Good[Product_Name];//商品名称?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>供應商</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 顏色/尺寸</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Good[Pricedesc_say];//網購價?> </TD>
                              <td width="60" align="center" nowrap="nowrap"  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Good[JianNum_say];//件數?></td>
                              <!--<TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[AlsendProductNum];//已发货数量?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[NeedsendProductNum];//需发货数量?></TD>-->
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[Items_xj_say];//单项小计?></TD>
                              <!--<TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Order_Pack[OrderState_say];//订单状态?> </TD>-->
                              </TR>
                            <?php               
					$i=0;
					$Query_d = $DB->query(" select g.* from `{$INFO[DBPrefix]}order_detail` as g where order_id='".intval($Order_id)."' order by order_detail_id asc ");
					while ($Rs_d=$DB->fetch_array($Query_d)) {
					?>
                            <TR class=row0>
                              <TD align=middle height=20>
                                <?php if ($_SESSION['LOGINADMIN_TYPE']==2 &&$Rs_d['packgid']==0 ) { ?>
                                <?php if ($_SESSION['sa_id']==$Rs_d['provider_id']){ ?>
                                <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs_d['order_detail_id']?>' name="cid[]"> 
                                <?php }else{ ?>
                                <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs_d['order_detail_id']?>' name="cid[]" disabled="disabled"> 
                                <?php }?>
                                <?php }elseif($Rs_d['packgid']==0){ ?>
                                <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs_d['order_detail_id']?>' name="cid[]"> 
                                <?php } ?>    
                                <INPUT type=hidden value='<?php echo $Rs_d['order_detail_id']?>' name=Ci[]> 
                                <INPUT type=hidden value='<?php echo $Rs_d['gid']?>' name=gid[]>                          </TD>
                              <TD align="left" noWrap><?php echo $Rs_d['bn']?></TD>
                              <TD height=20 align="left" noWrap>
                                
                                <?php 
						  echo "<a href=\"admin_goods.php?Action=Modi&gid=" . $Rs_d['gid'] . "\" target=\"_blank\">" . $Rs_d['goodsname'] . "</a>";
						 if ($Rs_d['ifchange'] == 1){
							echo  "[加購商品]";	
						 }
						 if ($Rs_d['packgid'] >0){
							echo "[組合商品]";	
						 }
						  if ($Rs_d['detail_bn']!="")	
							echo "<br>" . $Rs_d['detail_bn'];
						if ($Rs_d['detail_name']!="")	
							echo "<br>" . $Rs_d['detail_name'];
						if ($Rs_d['detail_des']!="")	
							echo "<br>" . $Rs_d['detail_des'];
						
						if ($Rs_d['ifxygoods'] == 1){
							echo "<br>" . $Rs_d['xygoods_des'];	
						}
						
								$buyprice = intval($Rs_d[price]);
								$xiaoji = intval($Rs_d[price])*$Rs_d['goodscount'];
							
							if ($Rs_d['ifbonus']==1){
								$buyprice = intval($Rs_d[odbonuspoint]) . "積分";
								$xiaoji = intval($Rs_d[odbonuspoint])*$Rs_d['goodscount'] . "積分";
							}
							?>
                                </TD>
                              <TD align="center" noWrap>
                                <?php
                        if (intval($Rs_d['provider_id'])>0){
							$Sql_p      = "select * from `{$INFO[DBPrefix]}provider` where  provider_id='".intval($Rs_d['provider_id']) . "' order by provider_idate  ";
							$Query_p    = $DB->query($Sql_p);
							$Rs_p=$DB->fetch_array($Query_p);
							echo "<a href='admin_provider.php?Action=Modi&provider_id=" . $Rs_d['provider_id'] . "' target='_blank'>" . $Rs_p['provider_name'] . "</a>";
						}
						?>
                                </TD>
                              <TD height=20 align="center" noWrap><?php echo $Rs_d['good_color']?>/<?php echo $Rs_d['good_size']?>
                              </TD>
                              <TD height=20 align="center" noWrap><?php if ($Rs_d['packgid']==0) echo $buyprice?>&nbsp;
                                </TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs_d['goodscount']?>&nbsp;<!--<?php echo $Rs_d['unit']?>--></TD>
                              <!--<TD height=20 align="center" noWrap>
                                <?php if ($Rs_d['packgid']==0) echo $Rs_d['hadsend']?>&nbsp;</TD>
                              <TD height=20 align="center" noWrap> 
                                <?php 
						if ($Rs_d['packgid']==0){  ?>                     &nbsp;
                                <input type='text' name='tonum[]' size='5'  value='<?php echo $Tonum = intval($Rs_d['goodscount'])-intval($Rs_d['hadsend']) ?>'>				
                                <?php
						}
						  ?>
                                </TD>-->
                              <TD height=20 align="center" noWrap>
                                <?php //echo  $Total_detailprice = $Rs['hadsend']!=0 ? abs($Rs['hadsend']*$Rs['price']) : abs($Rs['goodscount']*$Rs['price']) ;
						  if ($Rs_d['packgid']==0) echo $xiaoji;
						  ?>&nbsp;</TD>
                              <!--<TD height=20 align=center nowrap>
                                <?php 
						if ($Rs_d['packgid']==0){
						 echo $orderClass->getOrderState($Rs_d['detail_order_state'],1)?>,<?php echo $orderClass->getOrderState(intval($Rs_d['detail_pay_state']),2)  ?>,<?php echo $orderClass->getOrderState(intval($Rs_d['detail_transport_state']),3)  ;
						}
						?>
                                
                                &nbsp;</TD>-->
                              </TR>
                            <input type="hidden" name="DetailOrderState[]" value="<?php echo $Rs_d['detail_order_state']?>">
                          <input type="hidden" name="DetailPayState[]" value="<?php echo $Rs_d['detail_pay_state']?>">
                          <?php
					$i++;
					}
					?>
                          
                          <?php
					 }
					?>
                        </TABLE></FORM>
                    </TD></TR>
                </TABLE>
              
              <TABLE width="100%"    border=0 align="center" cellPadding=0 cellSpacing=0 class=p9gray>
                <TBODY>
                  <TR>
                    <TD colspan="3" align=center vAlign=top>
                      <table width="98%" border="0" class="allborder" style="margin-top:10px;margin-bottom:10px">
                        <TR>
                          <TD width="100" height=11 align=right vAlign=middle><?php echo $Order_Pack[to_start_order_tracks];?><!--启动定单跟踪-->：</TD>
                          <TD align=left vAlign=center nowrap="nowrap">
                            <input type="radio" name="order007status"  value="1"  onclick="UpdateStauts(this.value)" <?php if ($Order007status==1) { echo " checked "; }?>/><?php echo $Basic_Command['Yes']?> <input type="radio" name="order007status" value="0" <?php if ($Order007status==0) { echo " checked "; }?>  onclick="UpdateStauts(this.value)" /><?php echo $Basic_Command['No']?></TD>
                          <TD align=left vAlign=left><div id='show_status'>&nbsp;</div></TD>
                          </TR>
                        <?php  $DISPLAYOrder007content = $DISPLAYOrder007time =  $Order007status==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>	
                        <TR  id='TrOrder007time'  <?php echo  $DISPLAYOrder007time ?>>
                          <TD height=11 align=right vAlign=middle><?php echo $Order_Pack[to_start_order_time] ;?><!--到期时间-->：</TD>
                          <TD align=left vAlign=center>
                            <?php echo $FUNCTIONS->Input_Box('text','begtime',$Order007begtime,"    onchange=\"UpdateBegtime(this.value)\" maxLength=12 size=12 ")
				 ?>	
                          <?php echo $Order_Pack[to_start_order_time_Div]?></TD>
                          <TD align=left vAlign=left><div id='show_begtime'>&nbsp;</div> </TD>
                          </TR>
                        <TR id='TrOrder007content'  <?php echo  $DISPLAYOrder007content ?> >
                          <TD height=11 align=right vAlign=middle nowrap="nowrap"><?php echo $Order_Pack[to_start_order_content];?><!--备注内容-->：</TD>
                          <TD align=left vAlign=center>
                            <?php echo $FUNCTIONS->Input_Box('textarea','Order007Content',$Order007Content,"  onchange=\"UpdateContent(this.value)\" cols=80 rows=6  ")?>
                            <div id="Order007Contenttips" class="tips"><?php echo $Order_Pack[to_start_order_content_Div]?></div>							</TD>
                          <TD align=left vAlign=top>                    <div id='show_Content'>&nbsp;</div>				</TD>
                          </TR>
                      </table></TD>
                    </TR>
                </TABLE>
              
              </TD>
        </TR></TABLE>
      <div align="left">
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="p12black"><br> <i class="icon-list-alt" style="font-size:14px; margin-right:5px; margin-left:5px; color:#666;"></i> 訂單操作記錄</td>
            </tr>
          <tr>
            <td bgcolor="#F7F7F7" class="allborder">
              <table width="95%" border="0" align="center" cellpadding="4" cellspacing="0">
                <tr>
                  <td>時間</td>
                  <td>操作人</td>
                  <td>操作</td>
                  <td>說明</td>
                  </tr>
                <?php
                $Sql_action = "select oa.*,os.ifcheck as osifcheck from `{$INFO[DBPrefix]}order_action` as oa inner join `{$INFO[DBPrefix]}order_state` as os on oa.state_value=os.state_value and oa.state_type=os.state_type where oa.order_id='" . $Order_id . "' order by oa.actiontime asc";
				$Query_action    = $DB->query($Sql_action);
				while($Rs_action=$DB->fetch_array($Query_action)){
				?>
                <tr>
                  <td><?php echo date("Y-m-d H:i:s",$Rs_action['actiontime']);?></td>
                  <td>
                    <?php
                if($Rs_action['usertype']==-1){
					$Sql_U = "select username as uname from `{$INFO[DBPrefix]}user` where user_id='" . $Rs_action['user_id'] . "'";	
					$Query_S  = $DB->query(" select * from `{$INFO[DBPrefix]}shopinfo` where uid='".$Rs_action['user_id']."' and state=1 limit 0,1");
					$Num_S    = $DB->num_rows($Query_S);
					if ($Num_S==0){
						$$usertitle = "[會員]";
					}else{
						$Rs_S=$DB->fetch_array($Query_S);
						$usertitle = "[" . $Rs_S['shopname'] . "店主]";
					}
				}elseif($Rs_action['usertype']==0){
					$Sql_U = "select sa as uname from `{$INFO[DBPrefix]}administrator` where sa_id='".$Rs_action['user_id']."' limit 0,1";
					$usertitle = "[高級管理員]";
				}elseif($Rs_action['usertype']==1){
					$Sql_U = "select username as uname from `{$INFO[DBPrefix]}operater` where opid='".$Rs_action['user_id']."' limit 0,1";
					$usertitle = "[一般管理員]";
				}elseif($Rs_action['usertype']==2){
					$Sql_U = "select provider_name as uname from `{$INFO[DBPrefix]}provider` where provider_id='".$Rs_action['user_id']."' limit 0,1";
					$usertitle = "[供應商]";
				}
				$Query_U    = $DB->query($Sql_U);
				$Rs_U=$DB->fetch_array($Query_U);
				echo $Rs_U['uname'].$usertitle;
				?>
                    </td>
                  <td>
                    <?php 
				if($Rs_action['order_detail_id']>0){
					$Sql_D="select * from `{$INFO[DBPrefix]}order_detail` where order_detail_id='" . $Rs_action['order_detail_id'] . "'";
					$Query_D    = $DB->query($Sql_D);
					$Rs_D=$DB->fetch_array($Query_D);
					echo $Rs_D['goodsname'] . "<br>";
				}
				echo $orderClass->getOrderState($Rs_action['state_value'],$Rs_action['state_type']);
				if ($Rs_action['ifcheck']==0 && $Rs_action['osifcheck']==1){
					if ($Rs_action['state_type']==1 && $Rs_action['state_value']==2){
						$checkedvalue = 3;	
						$nocheckedvalue = 1;	
					}elseif ($Rs_action['state_type']==3 && $Rs_action['state_value']==3){
						$checkedvalue = 4;	
						$nocheckedvalue = 1;	
					}elseif ($Rs_action['state_type']==3 && $Rs_action['state_value']==5){
						$checkedvalue = 6;	
						$nocheckedvalue = 1;	
					}
					echo "<br><input type='button' value='審核通過' onclick='location.href=\"admin_order_act.php?op=checked&order_id=" . $Order_id . "&state_type=" . $Rs_action['state_type'] . "&state_value=" . $checkedvalue . "&order_action_id=" . $Rs_action['action_id'] . "&detail_id=" . $Rs_action['order_detail_id'] . "\"'><input type='button' value='審核未通過' onclick='location.href=\"admin_order_act.php?op=nochecked&order_id=" . $Order_id . "&state_type=" . $Rs_action['state_type'] . "&state_value=" . $nocheckedvalue . "&order_action_id=" . $Rs_action['action_id'] . "&detail_id=" . $Rs_action['order_detail_id'] . "\"'>";
				}elseif ($Rs_action['ifcheck']==1){
					echo "[審核通過]";
				}elseif ($Rs_action['ifcheck']==2){
					echo "[審核未通過]";
				}
				?></td>
                  <td><?php echo $Rs_action['remark'];?></td>
                  </tr>
                <?php
				}
				?>
          </table></td></tr></table>
        
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td align="left" bgcolor="#FFFFFF" class="p12black"><br> <i class="icon-file-text-alt" style="font-size:14px; margin-right:5px; margin-left:5px; color:#666;"></i> <?php echo $Order_Pack[OrderInfo_say];//訂單資訊?></td>
            </tr>
          <tr>
            <td>
              <table width="100%" border="0" align="left" cellpadding="4" cellspacing="0" bgcolor="#F7F7F7"   class="allborder">
                <tr>
                  <td width="18%" align="right" nowrap class="p9orange"><?php echo $Order_Pack[OrderSerial_say];//订单编号?>
                    ：</td>
                  <td width="27%" nowrap>
                    <?php echo $Order_serial?></td>
                  <td width="16%" align="right" nowrap class="p9orange"><?php echo $Order_Pack[OrderState_say];//定单状态?>
                    ：</td>
                  <td width="39%" nowrap>
                    <?php echo $orderClass->getOrderState($Order_state,1)?></td>
                  </tr>
                <tr>
                  <td align="right" width="18%"  valign="top" nowrap class="p9orange">支付狀態
                    ：</td>
                  <td width="27%" nowrap><?php echo $orderClass->getOrderState(intval($Pay_state),2)  ?>&nbsp;</td>
                  <td align="right" width="16%" valign="top" nowrap class="p9orange">配送狀態
                    ：</td>
                  <td width="39%" nowrap><?php echo $orderClass->getOrderState(intval($Transport_state),3)  ?>&nbsp;</td>
                  </tr>
                <tr>
                  <td width="18%" align="right" valign="top" nowrap class="p9orange"><?php echo $Order_Pack[OrderCreatetime_say];//下单时间?>
                    ：</td>
                  <td width="27%" nowrap><?php echo $Order_Time?></td>
                  <td width="16%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[Product_totalprice_say];//商品总金额?>
                    ：</td>
                  <td width="39%" nowrap><?php echo $Totalprice?> 元</td>
                  </tr>
                <?php
                 if ($Rs['ifgroup']==1){
				?>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange">&nbsp;</td>
                  <td nowrap>&nbsp;</td>
                  <td align="right" valign="top" nowrap class="p9orange">購物金：</td>
                  <td nowrap><?php echo $buyPoint;?> 元</td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange"></td>
                  <td nowrap></td>
                  <td align="right" valign="top" nowrap class="p9orange">消費總金額：</td>
                  <td nowrap><?php echo $Totalprice+$Transport_price;?> 元</td>
                  </tr>
                <?php
				 }else{
				?>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange">使用現金券：</td>
                  <td nowrap><?php echo $ticket_discount_money;?> 元 <?php echo $ticketcode;?>
                    <?php
				  if ($ticketcode>0){
				  	  echo "<a href='admin_ticket.php?Action=Modi&ticketid=" . $ticketid . "'>[查看]</a>";
				  }
				  ?>				  </td>
                  <td align="right" valign="top" nowrap class="p9orange">折價後金額 ：</td>
                  <td nowrap><?php echo ($discount_totalPrices)?> 元</td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange">紅利折抵點數：</td>
                  <td nowrap><?php echo $bonuspoint;?> 點</td>
                  <td align="right" valign="top" nowrap class="p9orange">消費總金額：</td>
                  <td nowrap><?php echo $discount_totalPrices+$Transport_price-$buyPoint;?> 元</td>
                  </tr>
                <?php
				 }
				?>
                
                <!--tr>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[Need_invoice_say];//需要发票?>
                    ：</td>
                  <td nowrap><?php echo $Ifinvoice?></td>
                  <td align="right" valign="top" nowrap class="p9orange">發票號碼：</td>
                  <td nowrap><?php echo $invoice_code;?></td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[Top_invoice_say];//发票抬头?>：</td>
                  <td nowrap><?php echo $Invoiceform?></td>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[Invoice_num_say];//统一编号?>：</td>
                  <td nowrap>
                    <?php echo $TheOneNum;?>				  </td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange">載具類型：</td>
                  <td nowrap="nowrap"><?php
				  if(intval($Rs['ifinvoice'])==0){
					  switch($pCarrierType){
							 case "3"  :
								echo "手機條碼載具";
								break;
							case "2"  :
								echo "自然人憑證條碼載具";
								break;
							case "1"  :
								echo "會員載具";
								break;
					  }
				  }
				  ?></td>
                  <td align="right" valign="top" nowrap class="p9orange">載具編號：</td>
                  <td nowrap><?php
				  if(intval($Rs['ifinvoice'])==0){
                  if($pCarrierType==1)
				  	echo "";
				  else
				  	echo $pCarrierNum;
				}
				  ?>
                  </td>
                  </tr-->
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange">經銷商：</td>
                  <td nowrap><?php echo $saler;?></td>
                  <td align="right" valign="top" nowrap class="p9orange">美安：</td>
                  <td nowrap><?php echo $rid;?></td>
                </tr>
              </table></td>
          </tr>
          </table>
        <br>
        <table width="100%" border="0" align="left" cellpadding="2" cellspacing="0">
          <tr>
            <td bgcolor="#FFFFFF" class="p12black"> <i class="icon-user" style="font-size:14px; margin-right:5px; margin-left:5px; color:#666;"></i> <?php echo $Order_Pack[Buyer_info];//购货人信息?><?php if (intval($User_id)==0) {  echo  $Cart[NO_member] ;}?></td>
            </tr>
          <tr>
            <td>
              <?php
			$Query_user = $DB->query(" select * from `{$INFO[DBPrefix]}user` where user_id=".intval($User_id)." limit 0,1 ");
			$Num_user   = $DB->num_rows($Query_user);
			if ($Num_user>0){
				$Result_user= $DB->fetch_array($Query_user);
			?>
              <table width="100%" border="0" cellspacing="0" cellpadding="4"   class="allborder" bgcolor="#F7F7F7">
                <tr>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange"><?php echo $Admin_Member[UserName];//帳號?>會員
                    ：</td>
                  <td align="left" nowrap="nowrap"><?php echo $Result_user['username']?></td>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange"><?php echo $Admin_Member[RegTime] ;//注册时间?> ：</td>
                  <td align="left" nowrap="nowrap"><?php echo $Result_user['reg_date']." &nbsp;&nbsp; [<font color=red>".$Result_user['reg_ip']."</font>]";?></td>
                </tr>
                <tr>
                  <td width="18%" align="right" valign="top" nowrap class="p9orange"> 中文姓名
                    ：</td>
                  <td width="27%" align="left" nowrap>
                    <?php echo $Result_user['true_name']?> <?php echo $Result_user['cn_secondname']?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange"> 英文姓名：</td>
                  <td align="left" nowrap="nowrap"><?php echo $Result_user['en_firstname']?> <?php echo $Result_user['en_secondname']?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  </tr>
                <tr>
                  <td width="18%" align="right" valign="top" nowrap class="p9orange">手機
                    ：</td>
                  <td width="27%" align="left" nowrap><?php echo MD5Crypt::Decrypt ( trim($Result_user['other_tel']), $INFO['mcrypt'])//$Result_user['other_tel']?></td>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange"><?php echo $Admin_Member[Phone];//联系电话?> ：</td>
                  <td align="left" nowrap="nowrap"><?php echo MD5Crypt::Decrypt ( trim($Result_user['tel']), $INFO['tcrypt'])//$Result_user['tel']?></td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange">護照號碼
                    ：</td>
                  <td><?php echo $Result_user['certcode']?></td>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange"><?php echo $Admin_Member[Email];//Email地址?> ：</td>
                  <td align="left" nowrap="nowrap"><?php echo $Result_user['email']?></td>
                </tr>
                <tr>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange">目的地：</td>
                  <td nowrap="nowrap"><?php echo $Departure;?></td>
                  <td align="right"><span class="p9orange">班機號碼：</span></td>
                  <td><?php echo $flightid.$flightno;?></td>
                </tr>
                <tr>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange">班機時間：</td>
                  <td nowrap="nowrap"><?php echo $flightdate;?></td>
                  <td align="right" valign="top" nowrap="nowrap" class="p9orange">直飛/轉機：</td>
                  <td nowrap="nowrap"><?php echo $flightstyle=="direct"?"直飛":"轉機";?>&nbsp;</td>
                </tr>
                </table>
              <?php } ?>
              </td>
          </tr>
          </table>
        
        <!--table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td bgcolor="#FFFFFF" class="p12black"> <br>
              <i class="icon-truck" style="font-size:14px; margin-right:5px; margin-left:5px; color:#666;"></i> <?php echo $Cart[Getpersoninfo] ;//收货人信息?></td>
            </tr>
          <tr>
            <td>
              <form action="admin_order.php" method="post">
                <input name="act" value="save" type="hidden">
                <input name="orderid" value="<?php echo $Order_id;?>" type="hidden">
                <table width="100%" border="0" cellspacing="0" cellpadding="4"   class="allborder" bgcolor="#F7F7F7">
                  <tr>
                    <td width="18%" align="right" valign="top" nowrap class="p9orange"> <?php echo $Cart[name_say] ;//收貨人姓名?>
                      ：</td>
                    <td width="27%" align="left" nowrap>                  
                      <?php echo $FUNCTIONS->Input_Box('text','Receiver_name',$Receiver_name,"      maxLength=40 size=20 ")?></td>
                    <td width="16%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[mobile_say] ;//聯絡手機?>
                      ：</td>
                    <td width="39%" align="left" nowrap><?php echo $FUNCTIONS->Input_Box('text','Receiver_mobile',$Receiver_mobile,"      maxLength=40 size=20 ")?></td>
                    </tr>
                  <tr>
                    <td width="18%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[tel_say];//联系电话?>
                      ：</td>
                    <td width="27%" align="left" nowrap>
                      
                      <?php echo $FUNCTIONS->Input_Box('text','Receiver_tele',$Receiver_tele,"      maxLength=40 size=20 ")?>                  </td>
                    <td width="16%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[email_say];//Email地址?>
                      ：</td>
                    <td width="39%" align="left" nowrap>
                      
                      <?php echo $FUNCTIONS->Input_Box('text','Receiver_email',$Receiver_email,"      maxLength=40 size=20 ")?>                </td>
                    </tr>
                  <tr>
                    <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[addr_say] ;//地址?>
                      ：</td>
                    <td><?php echo $FUNCTIONS->Input_Box('text','Receiver_address',$Receiver_address,"      maxLength=40 size=40 ")?></td>
                    <td align="right" class="p9orange"><?php echo $Cart[HomeSend_TimeType] ;//宅配時間?>
                      ：</td>
                    <td><?php echo $Transtime_name ?></td>
                    </tr>
                  <tr>
                    <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[post_say];//邮政编码?>
                      ：</td>
                    <td colspan="3" nowrap><?php echo $FUNCTIONS->Input_Box('text','Receiver_post',$Receiver_post,"  maxLength=40 size=20 ")?></td>
                    </tr>
                  <tr>
                    <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[content_say];//订单附言?>
                      ：</td>
                    <td colspan="3" nowrap><textarea name="Receiver_memo" cols="70" rows="5" id="content"><?php echo $Receiver_memo?></textarea></td>
                    </tr>
                  <tr>
                    <td align="right" valign="top" nowrap class="p9orange">&nbsp;</td>
                    <td colspan="3" nowrap><input type="submit" name="button" id="button" value="儲存"></td>
                    </tr>
                  </table-->
                </form>
              </td>
            </tr>
      </table></div></div>
                      <div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>
<form name='OrderPrint' method='get' action='admin_order_print.php' >
<input type='hidden' name='order_id'>
</form>



<?php echo $InitAjax;?>
 <script language="javascript">


 function UpdateContent(Content){
 	var url     = "Check_ajax.php";
 	var show    = document.getElementById("show_Content");

 	AjaxPostRequest(url,show)
 }

 function AjaxPostRequest(url,show){
 	if (typeof(url) == 'undefined'){
 		  //  return false;
 	}
 	var Content = document.getElementById("Order007Content").value;
 	var Order_id = <?php echo $Order_id;?>;

 	var ajax = InitAjax();
 	ajax.open("POST", url, true);
 	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=utf-8");
 	ajax.onreadystatechange = function() {
 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		            	if (ajax.readyState == 4 && ajax.status == 200) {
 		            		  //alert (ajax.responseText);        		      
 		            		  show.innerHTML = ajax.responseText;
 		            	}		      
        	}

 	ajax.send('type=order007content&content='+Content+'&Order_id='+Order_id);	        	
 	}

 
   function UpdateStauts(Status){
    var url = "Check_ajax.php?type=order007status&status="+Status+"&Order_id="+<?php echo $Order_id;?>;
 	var show = document.getElementById("show_Status");
	 if(Status == 1){
	   TrOrder007time.style.display    ="";
	   TrOrder007content.style.display ="";
	  }else{
	   TrOrder007time.style.display    ="none";
	   TrOrder007content.style.display ="none";	   
	  }
	AjaxGetRequest(url,show)
	}
	
	
 
 
 
  function UpdateBegtime(begtime){
    var url = "Check_ajax.php?type=order007time&begtime="+begtime+"&Order_id="+<?php echo $Order_id;?>;
 	var show = document.getElementById("show_begtime");
	
	AjaxGetRequest(url,show)
	}
 
 function AjaxGetRequest(url,show){
 	if (typeof(url) == 'undefined'){
 		    return false;
 	}

 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
	
	/**ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');*/
	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
	
	
		//alert(url);
		//alert('is ok');
 	ajax.onreadystatechange = function() {
 		//alert ('d');
 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		        	if (ajax.readyState == 4 && ajax.status == 200) {
 		  //alert (ajax.responseText);        		      
		show.innerHTML = ajax.responseText;
      		          }
 		        		      
 		        	}

 		        	ajax.send(null);
 	}
</script>

