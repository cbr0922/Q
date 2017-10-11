<?php
include_once "Check_Admin.php";
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();

include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Order_Pack_Txt.php";
include_once 'crypt.class.php';
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;

if (is_array($_GET[cid])){
	$Order_id = 	$_GET[cid][0];
}else{
	$Order_id        = intval($FUNCTIONS->Value_Manage($_GET['order_id'],$_POST['order_id'],'back',''));
}
$Query_first = $DB->query(" select ot.order_state,ot.order007_begtime,ot.order007_status,ot.order007_content,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_id='".intval($Order_id)."' and  ot.provider_id='".$_SESSION[sa_id]."' and ot.iftogether=0 limit 0,1");
$Num_first   = $DB->num_rows($Query_first);

if ($Num_first>0){
	$Result_first           = $DB->fetch_array($Query_first);
	$Transtime_id           = $Result_first[transtime_id];
	$Transtime_name         = $Result_first[transtime_name];
	$Order007begtime        = $Result_first[order007_begtime]!="" ? $Result_first[order007_begtime] : "0000-00-00" ;
	$Order007status         = $Result_first[order007_status];
	$Order007Content        = $Result_first[order007_content];
}else{
	$FUNCTIONS->sorry_back('back','');
}



$Sql = "select od.detail_pay_state,od.detail_order_state,od.price,od.goodscount,od.hadsend,od.unit,od.goodsname,od.gid,od.order_detail_id,od.provider_id,od.good_color,od.good_size,od.market_price,ot.order_serial,ot.user_id,ot.createtime,ot.paymentname,ot.deliveryname,ot.totalprice,ot.transport_price,ot.ifinvoice,ot.invoiceform,ot.invoice_num,
ot.order_state,ot.pay_state,ot.receiver_name,ot.atm,ot.receiver_address,ot.receiver_email,ot.receiver_post,ot.receiver_tele,ot.receiver_mobile,receiver_memo,od.month,od.detail_id,od.detail_name,od.detail_bn,od.detail_des,ot.ticketcode,ot.discount_totalPrices,od.xygoods_des,od.ifxygoods,od.ifchange,ot.saler,ot.totalbonuspoint,ot.rid,ot.ticket_discount_money,od.memberorprice,od.memberprice,od.combipoint,od.detail_transport_state,ot.transport_state,ot.piaocode,ot.sendtime,ot.sendname,ot.invoice_code from `{$INFO[DBPrefix]}order_detail` od inner join `{$INFO[DBPrefix]}order_table` ot  on (od.order_id=ot.order_id)  where ot.order_id='".intval($Order_id)."' and  od.provider_id='".$_SESSION[sa_id]."' and od.iftogether=0 order by  od.order_detail_id desc ";


$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$Nums     = $Num ;
}else{
	$FUNCTIONS->sorry_back('back','');
}
//echo "<hr color=green>";
//print_r($_SESSION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<LINK id=css href="css/calendar.css" type='text/css' rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD>
<title><?php echo $JsMenu[Order_Detail]?>  ---  <?php echo $INFO[company_name]?></title>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<script type="text/javascript" src="../js/alter.js"></script>
<style type="text/css">
body{
margin:0px;
}


#fullBg{
background-color: Black;
display:none;
z-index:30;
position:absolute;
left:0px;
top:0px;
filter:Alpha(Opacity=30);
/* IE */
-moz-opacity:0.4; 
/* Moz + FF */
opacity: 0.4; 
}
#msg{
	position:absolute;
	z-index:40;
	display:none;
	background-color:#FFFFFF;
	border:1px solid #6633CC;
}
#msg #close{
height:30px;
text-align:right;
padding-top:8px;
padding-right:15px;
}
#msg #ctt{
text-align:center;
font-size:12px;
padding-bottom:15px;
}
#cPic{
cursor:pointer;
}


</style>
<div id="fullBg"></div>
<div id="msg">
<div id="close"></div>
<div id="ctt"></div>
</div>

<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
 <TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center   border=0>
 <TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
	<?php  include_once "desktop_title.php";?></TD>
  </TR>
  </TBODY>
 </TABLE>
<SCRIPT language=javascript>


function toDelivered(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
	    if (confirm('<?php echo $Order_Pack[Order_Js_one]?>')){  //"是否取消定单操作? 操作对象要求:只有当定单状态为<未确定>或<确定>并且<未交付>的时候。本次操作对已经归档的资料无效！ "
		document.adminForm.action = "provider_order_act.php";
		document.adminForm.act.value='toDelivered';
		document.adminForm.submit();
		}
	}
}

function toConfirm(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');

	if (checkvalue!=false){
		document.adminForm.action = "provider_order_act.php";
		document.adminForm.act.value='toConfirm';
		document.adminForm.submit();
	}
}

function toRestart(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');

	if (checkvalue!=false){
		document.adminForm.action = "provider_order_act.php";
		document.adminForm.act.value='toRestart';
		document.adminForm.submit();
	}
}


function toPayed(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');

	if (checkvalue!=false){
	 if (confirm('<?php echo $Order_Pack[Order_Js_two]?>')){  //是否操作到款? 操作对象要求:当定单状态不为<已取消>及不为<未确定>的时候。本次操作对已经归档的资料无效！
		document.adminForm.action = "provider_order_act.php";
		document.adminForm.act.value='toPayed';
		document.adminForm.submit();
 	 }
	}
}


function toConsignment(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');

	if (checkvalue!=false){
	  if (confirm('<?php echo $Order_Pack[Order_Js_three]?>')){ //"是否操作发货? 操作对象要求:当定单状态不为<已取消>或不为<未确定>并且为<已交付>的时候。本次操作对已经归档的资料无效！
		document.adminForm.action = "provider_order_act.php";
		document.adminForm.act.value='toConsignment';
		document.adminForm.submit();
		}
	}
}

function toPrintDown(id){
		document.OrderPrint.order_id.value=id;
		OrderPrint.target="_blank";
		OrderPrint.submit();
}


function toPigeonhole(){

  var flag ;
	    flag=confirm("<?php echo $Order_Pack[Order_Js_four];?>");
		if(flag==true){
		 document.adminForm.action = "provider_order_act.php";
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
	//showWin('url','provider_order_showact.php?','',300,200);	
	showWin('url','provider_order_showact.php?state_value='+state_values+'&state_type='+state_types,'',300,250);	
}

</SCRIPT>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TBODY>
  <TR>
    <TD width="100%" height=302 align="right" vAlign=top>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD>
              <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD><table width="160" border="0" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                          <td width="38"><img height="32" src="images/<?php echo $INFO[IS]?>/program-1.gif" width="32" /></td>
                          <td width="199" nowrap="nowrap" class="p12black"><span class="p9orange"><?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Order_Detail];//定单明细?></span></td>
                          </tr>
                        </tbody>
                      </table></TD>
					  
					  
					<TD align="right" valign="top" style="padding-bottom:10px">
					
					<div class="order_button" style="float: right; padding-right: 100px;">
					<ul>
					<li><a href="javascript:changeOrderState(1,3);">出貨</a> </li>
					<li><a href="javascript:changeOrderState(2,3);">已到貨</a> </li>
					<li><a href="javascript:changeOrderState(4,3);">換貨</a> </li>
					<li><a href="javascript:changeOrderState(6,3);">退貨</a> </li>
					<li><a href="javascript:changeOrderState(12,3);">備貨</a> </li>
					<li><a href="javascript:changeOrderState(20,3);">商品取回</a> </li>
					<li><a href="javascript:changeOrderState(19,3);">新品寄出</a> </li>
					<li><a href="javascript:changeOrderState(13,3);">退貨異常</a> </li>
					<li><a href="javascript:changeOrderState(14,3);">換貨異常</a> </li>
					<li><a href="javascript:changeOrderState(15,3);">配送異常</a> </li>
					
					</ul>
					</div>
					
					</TD>
					  

                    </TR>
                </TBODY></TABLE>            </TD>
            </TR>
          </TBODY>
        </TABLE>
      
      
      <br>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
                        <FORM name='adminForm' id='adminForm' action="" method=post>
                          <!-- input type='hidden' name="Url" value="<?php //echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" -->
                          <input type='hidden' name="Url" value="provider_order.php">
                          <input type='hidden' name="Order_id" value="<?php echo $Order_id?>">
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden name='Num' value="<?php echo $Num?>">
                          <INPUT type=hidden value=0  name=boxchecked>
                          <INPUT type=hidden name=remark id=remark>
                          <INPUT type=hidden name=state_value>
                          <INPUT type=hidden name=optype value="2">
                          <INPUT type=hidden name=state_type> 
                          <INPUT type=hidden name=piaocode id=piaocode> 
                          <INPUT type=hidden name=sendtime id=sendtime> 
                          <INPUT type=hidden name=sendname id=sendname> 
                          <TBODY>
                            <TR align=middle>
                              <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Good[Product_Name];//商品名称?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Good[Pricedesc_say];//網購價?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Good[JianNum_say];//件數?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>顏色/尺寸&nbsp;</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[AlsendProductNum];//已发货数量?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[NeedsendProductNum];//需发货数量?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[Items_xj_say];//单项小计?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Order_Pack[OrderState_say];//订单状态?> </TD>
                              </TR>
                            <?php
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {

						$Order_serial = $Rs['order_serial'];
						$User_id      = $Rs['user_id'];
						$Order_Time   = date("Y-m-d H: i a ",$Rs['createtime']);
						$Paymentname  = $Rs['paymentname'];
						$Deliveryname = $Rs['deliveryname'];
						$Totalprice   = $Rs['totalprice'];
						$Transport_price = $Rs['transport_price'];



						switch (intval($Rs['ifinvoice'])){
							case 0:
								$Ifinvoice   = $Cart[Two_piao];
								$Invoiceform = $Basic_Command['Null'];
								$TheOneNum   = $Basic_Command['Null'];
								break;
							case 1:
								$Ifinvoice   =  $Cart[Three_piao];
								$Invoiceform =  trim($Result['invoiceform']);
								$TheOneNum   =  "<font color=red>".trim($Result['invoice_num'])."</font>";

								break;
							case 2:
								$Ifinvoice   = $Basic_Command['Null'];
								$Invoiceform = $Basic_Command['Null'];
								$TheOneNum   = $Basic_Command['Null'];
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
  						$Receiver_mobile  = MD5Crypt::Decrypt ( trim($Rs['receiver_mobile']), $INFO['mcrypt']);
						$Receiver_memo    = nl2br($Rs['receiver_memo']);
						$piaocode  = $Rs['piaocode'];
						$sendtime = $Rs['sendtime'];
						$sendname = $Rs['sendname'];
						$invoice_code = $Rs['invoice_code'];

					?>
                            <TR class=row0>
                              <TD align=middle height=20>
                                <?php if ($_SESSION['LOGINADMIN_TYPE']==2) { ?>
                                <?php if ($_SESSION['sa_id']==$Rs['provider_id']){ ?>
                                <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs['order_detail_id']?>' name="cid[]">
                                <?php }else{ ?>
                                <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs['order_detail_id']?>' name="cid[]" disabled="disabled">
                                <?php }?>
                                <?php }else{ ?>
                                <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs['order_detail_id']?>' name="cid[]">
                                <?php } ?>
                                <INPUT type=hidden value='<?php echo $Rs['order_detail_id']?>' name=Ci[]>
                                <INPUT type=hidden value='<?php echo $Rs['gid']?>' name=gid[]>
                                </TD>
                              <TD height=20 align="left" noWrap>
                                <a href="provider_goods.php?Action=Modi&gid=<?php echo $Rs['gid']?>" target="_blank">
                                  <?php echo $Rs['goodsname']?>
                                  </a> </TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs['price']?>&nbsp;</TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs['goodscount']?>&nbsp;<?php echo $Rs['unit']?></TD>
                              <TD align="center" noWrap><?php echo $Rs['good_color']?>/<?php echo $Rs['good_size']?>&nbsp;</TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs['hadsend']?>&nbsp;</TD>
                              <TD height=20 align="center" noWrap>                        &nbsp;
                                <input type='text' name='tonum[]' size='5'  value='<?php echo $Tonum = intval($Rs['goodscount'])-intval($Rs['hadsend']) ?>'>					  </TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo  $Total_detailprice = $Rs['hadsend']!=0 ? abs($Rs['hadsend']*$Rs['price']) : abs($Rs['goodscount']*$Rs['price']) ;  ?>&nbsp;</TD>
                              <TD height=20 align=center nowrap><?php echo $orderClass->getOrderState($Rs['detail_order_state'],1)?>,<?php echo $orderClass->getOrderState(intval($Rs['detail_pay_state']),2)  ?>,<?php echo $orderClass->getOrderState(intval($Rs['detail_transport_state']),3)  ?>&nbsp;</TD>
                              </TR>
                            
                            <input type="hidden" name="DetailOrderState[]" value="<?php echo $Rs['detail_order_state']?>">
                          <input type="hidden" name="DetailPayState[]" value="<?php echo $Rs['detail_pay_state']?>">
                          <?php
					$i++;
					}
					?>
                          </FORM>
                        </TABLE>
                      </TD></TR>
                  </TABLE>
              
              <TABLE width="98%"    border=0 align="center" cellPadding=0 cellSpacing=0 class=p9gray>
                <TBODY>
                  <TR>
                    <TD height=1 align=left vAlign=top>&nbsp;</TD>
                    <TD colspan="2" align=left vAlign=center>&nbsp;</TD>
                    </TR>
                  <TR>
                    <TD width="120" height=1 align=left vAlign=top nowrap="nowrap"><?php echo $Order_Pack[Order_Txt_Title];?><!--訂單操作方式-->
                      ：</TD>
                    <TD colspan="2" align=left vAlign=center><?php echo $Order_Pack[Order_Txt_Content_I]?><!--1:若取消訂單,則不能選取到款、發貨、歸檔操作！ 但是可以選擇重新啟動訂單狀態！--></TD>
                    </TR>
                  <TR>
                    <TD height=1 align=left vAlign=top>&nbsp;</TD>
                    <TD colspan="2" align=left vAlign=center><?php echo $Order_Pack[Order_Txt_Content_II]?><!--2:若操作者身份確定為用戶本人，則可開始處理新增修改管理資料動作！--></TD>
                    </TR>
                  <TR>
                    <TD height=3 align=left vAlign=top>&nbsp;</TD>
                    <TD colspan="2" align=left vAlign=center><?php echo $Order_Pack[Order_Txt_Content_III]?><!--3:一旦執行到款操作，則不能再選擇訂單取消！同樣地，已取消、及未確定的定單將不能操作到款動作！--></TD>
                    </TR>
                  <TR>
                    <TD height=6 align=left vAlign=top>&nbsp;</TD>
                    <TD colspan="2" align=left vAlign=center><?php echo $Order_Pack[Order_Txt_Content_IV]?><!--4: 一旦執行發貨操作，則不能再選擇訂單取消！同樣地，已取消、及未確定的定單將不能操作到款動作！--></TD>
                    </TR>
                  <TR>
                    <TD height=11 align=left vAlign=top>&nbsp;</TD>
                    <TD colspan="2" align=left vAlign=center><?php echo $Order_Pack[Order_Txt_Content_V]?><!--5:執行歸檔後，資料將不能再被更改！--></TD>
                    </TR>
                  <TR>
                    <TD height=11 align=left vAlign=top>&nbsp;</TD>
                    <TD colspan="2" align=left vAlign=center>&nbsp;</TD>
                    </TR>
                  
                  <TR>
                    <TD height=11 align=left vAlign=top>&nbsp;</TD>
                    <TD colspan="2" align=left vAlign=center>
                      <table width="100%" border="0" class="allborder">
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
                            <div id="begtimetips" class="tips"><?php echo $Order_Pack[to_start_order_time_Div]?></div>
                            </TD>
                          <TD align=left vAlign=left><div id='show_begtime'>&nbsp;</div> </TD>
                          </TR>
                        <TR id='TrOrder007content'  <?php echo  $DISPLAYOrder007content ?> >
                          <TD height=11 align=right vAlign=middle nowrap="nowrap"><?php echo $Order_Pack[to_start_order_content];?><!--备注内容-->：</TD>
                          <TD align=left vAlign=center>
                            <?php echo $FUNCTIONS->Input_Box('textarea','Order007Content',$Order007Content,"  onchange=\"UpdateContent(this.value)\" cols=80 rows=6  ")?>
                            <div id="Order007Contenttips" class="tips"><?php echo $Order_Pack[to_start_order_content_Div]?></div>
                            </TD>
                          <TD align=left vAlign=top>                            <div id='show_Content'>&nbsp;</div>
                            
                            </TD>
                          </TR>
                        </table>
                      </TD>
                    </TR>
                  <TR>
                    <TD height=11 align=left vAlign=top>&nbsp;</TD>
                    <TD colspan="2" align=left vAlign=center>&nbsp;</TD>
                    </TR>
                  </TABLE>
              
              </TD>
          </TR></TABLE>
      
      <div align="left">
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td bgcolor="#FFFFFF" class="p12black"><br> 訂單操作記錄</td>
            </tr>
          <tr>
            <td>
              <table width="100%" border="0" align="left" cellpadding="4" cellspacing="0" bgcolor="#F7F7F7"   class="allborder">
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
					$usertitle = "[會員]";
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
					echo "<br><input type='button' value='審核通過' onclick='location.href=\"provider_order_act.php?op=checked&order_id=" . $Order_id . "&state_type=" . $Rs_action['state_type'] . "&state_value=" . $checkedvalue . "&order_action_id=" . $Rs_action['action_id'] . "&detail_id=" . $Rs_action['order_detail_id'] . "\"'><input type='button' value='審核未通過' onclick='location.href=\"provider_order_act.php?op=nochecked&order_id=" . $Order_id . "&state_type=" . $Rs_action['state_type'] . "&state_value=" . $nocheckedvalue . "&order_action_id=" . $Rs_action['action_id'] . "&detail_id=" . $Rs_action['order_detail_id'] . "\"'>";
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
            <td bgcolor="#FFFFFF" class="p12black"><br> <?php echo $Order_Pack[OrderInfo_say];//訂單資訊?></td>
            </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="4"   class="allborder" bgcolor="#F7F7F7">
                <tr>
                  <td width="18%" align="right" nowrap class="p9orange"><?php echo $Order_Pack[OrderSerial_say];//订单编号?>
                    ：</td>
                  <td width="27%" nowrap>
                    <?php echo $Order_serial?></td>
                  <td width="16%" align="right" nowrap class="p9orange"><?php echo $Order_Pack[OrderState_say];//定单状态?>
                    ：</td>
                  <td width="39%" nowrap><?php echo$orderClass->getOrderState($Order_state,1)?>,<?php echo $orderClass->getOrderState(intval($Pay_state),2)  ?>,<?php echo $orderClass->getOrderState(intval($Transport_state),3)  ?></td>
                  </tr>
                <tr>
                  <td width="18%" align="right" valign="top" nowrap class="p9orange"><?php echo $Order_Pack[OrderCreatetime_say];//下单时间?>
                    ：</td>
                  <td width="27%" nowrap><?php echo $Order_Time?></td>
                  <td width="16%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[Product_totalprice_say];//商品总金额?>
                    ：</td>
                  <td width="39%" nowrap><?php echo $Totalprice?></td>
                  </tr>
                <tr>
                  <td width="18%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[send_type_say] ;//配送方式?>
                    ：</td>
                  <td width="27%" nowrap><?php echo $Deliveryname?></td>
                  <td width="16%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[send_money_say];//配送費用?>
                    ：</td>
                  <td width="39%" nowrap><?php echo $Transport_price?></td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[pay_type_say];//付款方式?>
                    ：</td>
                  <td nowrap><?php echo $Paymentname?></td>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Order_Pack[OrderTotalPrice_say];//订单总金额?>
                    ：</td>
                  <td nowrap><?php echo $Totalprice+$Transport_price?></td>
                  </tr>
                
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[Need_invoice_say];//需要发票?>
                    ：</td>
                  <td nowrap><?php echo $Ifinvoice?></td>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[Top_invoice_say];//发票抬头?>
                    ：</td>
                  <td nowrap><?php echo $Invoiceform?></td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[ATM_say];//ATM 转帐账号"?>
                    ：</td>
                  <td nowrap><?php echo $ATM?></td>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[Invoice_num_say];//统一编号?>：</td>
                  <td nowrap>
                    <?php echo $TheOneNum;?>
                    
                    </td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange">掛號單號：</td>
                  <td nowrap><?php echo $piaocode;?></td>
                  <td align="right" valign="top" nowrap class="p9orange">發票號碼：</td>
                  <td nowrap><?php echo $invoice_code;?></td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange">物流單位：</td>
                  <td nowrap><?php echo $sendname;?></td>
                  <td align="right" valign="top" nowrap class="p9orange">發貨日期：</td>
                  <td nowrap><?php echo $sendtime;?></td>
                  </tr>
                </table></td>
            </tr>
          </table>
        <br>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td bgcolor="#FFFFFF" class="p12black"> <?php echo $Order_Pack[Buyer_info];//购货人信息?><?php if (intval($User_id)==0) {  echo  $Cart[NO_member] ;}?></td>
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
                  <td width="18%" align="right" valign="top" nowrap class="p9orange"> <?php echo $Admin_Member[TrueName] ;//真實姓名?>
                    ：</td>
                  <td width="27%" align="left" nowrap>
                    <?php echo $Result_user['true_name']?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td width="16%" align="right" valign="top" nowrap class="p9orange"><?php echo $Admin_Member[UserName];//帳號?>
                    ：</td>
                  <td width="39%" align="left" nowrap>
                    
                    <?php echo $Result_user['username']?>
                    </td>
                  </tr>
                <tr>
                  <td width="18%" align="right" valign="top" nowrap class="p9orange"><?php echo $Admin_Member[Phone];//联系电话?>
                    ：</td>
                  <td width="27%" align="left" nowrap>
                    
                   <?php echo MD5Crypt::Decrypt ( trim($Result_user['tel']), $INFO['tcrypt'])//$Result_user['tel']?>
                    <span class="STYLE2"> /</span>                    <?php echo MD5Crypt::Decrypt ( trim($Result_user['other_tel']), $INFO['tcrypt'])//$Result_user['other_tel']?>
                  <td width="16%" align="right" valign="top" nowrap class="p9orange"><?php echo $Admin_Member[Email];//Email地址?>
                    ：</td>
                  <td width="39%" align="left" nowrap>
                    
                    <?php echo $Result_user['email']?>
                    </td>
                  </tr>
                <tr>
                  <td width="18%" align="right" valign="top" nowrap class="p9orange"><?php echo $Admin_Member[Address];//地址?>
                    ：</td>
                  <td align="left">
                    
                    <?php echo $Result_user['addr']?>
                    </td>
                  <td align="right"><span class="p9orange"><?php echo $Admin_Member[Area];//地區?>
                    ：</span></td>
                  <td align="left"><?php echo $Result_user['city']?></td>
                  </tr>
                <tr>
                  <td align="right" valign="top" nowrap class="p9orange"><?php echo $Admin_Member[Post];//邮政编码?>
                    ：</td>
                  <td><?php echo $Result_user['post']?></td>
                  <td align="right"><span class="p9orange"><?php echo $Admin_Member[RegTime] ;//注册时间?>
                    ：</span></td>
                  <td><?php echo $Result_user['reg_date']." &nbsp;&nbsp; [<font color=red>".$Result_user['reg_ip']."</font>]";?></td>
                  </tr>
                </table>
              <?php } ?>
              </td>
            </tr>
          </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td bgcolor="#FFFFFF" class="p12black"> <br>
              <?php echo $Cart[Getpersoninfo] ;//收货人信息?></td>
            </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="4"   class="allborder" bgcolor="#F7F7F7">
              <tr>
                <td width="18%" align="right" valign="top" nowrap class="p9orange"> <?php echo $Cart[name_say] ;//收貨人姓名?>
                  ：</td>
                <td width="27%" align="left" nowrap>
                  <?php echo $Receiver_name?>                  </td>
                <td width="16%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[mobile_say] ;//聯絡手機?>
                  ：</td>
                <td width="39%" align="left" nowrap><?php echo $Receiver_mobile?></td>
                </tr>
              <tr>
                <td width="18%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[tel_say];//联系电话?>
                  ：</td>
                <td width="27%" align="left" nowrap>
                  
                  <?php echo $Receiver_tele?>                  </td>
                <td width="16%" align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[email_say];//Email地址?>
                  ：</td>
                <td width="39%" align="left" nowrap>
                  
                  <?php echo $Receiver_email?>                  </td>
                </tr>
              <tr>
                <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[addr_say] ;//地址?>
                  ：</td>
                <td><?php echo $Receiver_address?></td>
                <td align="right" class="p9orange"><?php echo $Cart[HomeSend_TimeType] ;//宅配時間?>
                  ：</td>
                <td><?php echo $Transtime_name ?></td>
                </tr>
              <tr>
                <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[post_say];//邮政编码?>
                  ：</td>
                <td colspan="3" nowrap><?php echo $Receiver_post?></td>
                </tr>
              <tr>
                <td align="right" valign="top" nowrap class="p9orange"><?php echo $Cart[content_say];//订单附言?>
                  ：</td>
                <td colspan="3" nowrap><?php echo $Receiver_memo?></td>
                </tr>
              </table></td>
            </tr>
        </table></div></TD>
    </TR>
</TBODY></TABLE>
</div>
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

