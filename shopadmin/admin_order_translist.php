<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Cart.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

include_once Classes . "/Time.class.php";
include_once Classes . "/orderClass.php";
$TimeClass = new TimeClass;
$orderClass = new orderClass;

$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()-7*24*60*60);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());


/**
 * 这里是使用了WHERE IN 但是要求MYSQL版本在4。1以上
 */
if($_POST['action']=="delphone" && intval($_POST['days'])>0){
	$d = date('d',time())-intval($_POST['days']);
	$y = intval(date('Y',time()));
	$m = intval(date('m',time()));
	$starttime = gmmktime(0,0,0,$m,$d,$y);	
	$Sql_order = "update `{$INFO[DBPrefix]}order_table` set receiver_tele='',receiver_mobile='' where createtime<='" . $starttime . "' ";
	$Query_order  = $DB->query($Sql_order);
	$FUNCTIONS->header_location('admin_order_list.php');
}
if (intval($_GET['provider_id'])>0){
	/*
	$Sql_Provider = "SELECT order_serial,order_id
	FROM  `{$INFO[DBPrefix]}order_table`  where order_id  in  (select order_id from `{$INFO[DBPrefix]}order_detail`
	where  provider_id=".intval($_GET['provider_id']).")
	";
	$Query_Provider =  $DB->query($Sql_Provider);
	*/
	$Query_Provider =  $DB->query("SELECT DISTINCT order_id FROM  `{$INFO[DBPrefix]}order_detail`   where  provider_id=".intval($_GET['provider_id']));
	$Num_Provider   =  $DB->num_rows($Query_Provider);
	if ($Num_Provider>0){
		$Provider_Search = " and ( ";
		while ($Rs_Provider   =  $DB->fetch_array($Query_Provider)){
			$Provider_Search .= "o.order_id='".intval($Rs_Provider[order_id])."'  or ";
		}

		$Provider_Search   = substr($Provider_Search,0,strlen($Provider_Search)-3);
		$Provider_Search  .= " )";
	}
}


$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;

switch (trim($_GET['State'])){
	case "NoOp":
		$Add = " and o.order_state=0 ";  // 这里设置为小于等于2，就是说包括未确定，已确定，部分发货三类内容
		$Join = " left join ";
		//$begtimeunix = 0;
		break;
	case "NoView":
		$Add = " and ou.userback_alread=0 ";
		$Join = " inner join ";
		//$begtimeunix = 0;
		break;
	case "Cancel":
		$Add = " and o.order_state=2 ";  // 这里设置为5
		$Join = " left join ";
		$Topname = "[申請取消]";//已取消
		//$begtimeunix = 0;
		break;
	case "Back":
		$Add = " and o.transport_state=5 ";  // 这里设置为5
		$Join = " left join ";
		$Topname = "[申請退款]";//已取消
		//$begtimeunix = 0;
		break;
	case "Pigeonhole":
		$Add = " and o.order_state=4 ";  // 这里设置为4
		$Join = " left join ";
		//$begtimeunix = 0;
		$Topname = "[".$Order_Pack[OrderState_say_five]."]";//已归档
		break;
	case "Noreplay":
		$Add = " and ou.sys_say='' ";
		$Join = " inner join ";
		//$begtimeunix = 0;
		break;
    default :
		$Join = " left join ";
		break;
}

if ($_GET['action']=='search') {
	if (trim(urldecode($_GET['skey']))!=$Basic_Command['InputKeyWord']   && trim($_GET['skey'])!=""){

		if ( trim($_GET['type'])=='u.true_name'   || trim($_GET['type'])=='o.invoice_code'){
			$Add_one = " and ".$_GET['type']."  like '%".trim(urldecode($_GET['skey']))."%' ";
		}elseif (trim($_GET['type'])=='o.order_serial'){
			$Add_one = " and (o.order_serial like '" . trim(urldecode($_GET['skey'])) . "' or o.order_serial_together like '" . trim(urldecode($_GET['skey'])) . "')";
		}elseif (trim($_GET['type'])=='od.goodsname'){
			$Add_one = " and (od.goodsname like '" . trim(urldecode($_GET['skey'])) . "' or od.bn like '" . trim(urldecode($_GET['skey'])) . "')";
		}else{
			$Add_one = " and ".$_GET['type']."'".trim(urldecode($_GET['skey']))."' ";
		}
	}

	if (intval($_GET['ifinvoice'])>0){
		$Add_three  .=  " and  o.ifinvoice=".intval($_GET['ifinvoice'])." ";
	}
	if (intval($_GET['ifinvoice'])==-1){
		$Add_three  .=  " and  o.ifinvoice<>3 ";
	}
	if (intval($_GET['iftogether'])==-1){
		$Add_three  .=  " and  o.iftogether='0'";
	}
	if (intval($_GET['iftogether'])==1){
		$Add_three  .=  " and  o.iftogether='1'";
	}

	
	if (($_GET['company'])!=""){
		$Add_three  =  " and  o.saler='".($_GET['company'])."' ";
	}

	if ($_GET['transportation']!="" && $_GET['transportation']!="0"){
		$Add_four  =  " and  o.deliveryname='".$_GET['transportation']."' ";
	}
	if (intval($_GET['fbtype'])==1){
		$Add_three  .=  " and  o.MallgicOrderId=''";
	}
	if (intval($_GET['fbtype'])==2){
		$Add_three  .=  " and  o.MallgicOrderId<>''";
	}
	if (intval($_GET['ifmobile'])==1){
		$Add_three  .=  " and  o.ifmobile='1'";
	}
	if (intval($_GET['ifmobile'])==2){
		$Add_three  .=  " and  o.ifmobile='0'";
	}
	if (intval($_GET['payment'])>0){
		$Add_three  .=  " and  o.paymentid='".intval($_GET['payment'])."' ";
	}
	if (trim($_GET['type'])=='od.goodsname'){
		$goodsSql = " inner join `{$INFO[DBPrefix]}order_detail` as od on o.order_id=od.order_id";
		$goodsSql1 = " group by o.order_id";
	}

	 $Sql = " select  u.true_name,o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,o.invoice_code,
	         ou.sys_say,ou.userback_type,ou.userback_alread  ,o.MallgicOrderId,o.ticketmoney,o.bonuspoint,o.ticketcode,o.discount_totalPrices,o.piaocode,o.transport_state,o.provider_id,o.MallgicOrderId,o.ifgroup,o.order_serial_together
	         from `{$INFO[DBPrefix]}order_table` o  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)
	         ".$Join." `{$INFO[DBPrefix]}order_userback` ou on (o.order_id=ou.order_id)  " . $goodsSql . "
	         where  ";
			 if(trim($_GET['State'])=="NoOp")
			 	$Sql      .= " 1=1 ";
			 else
			 	$Sql      .= " o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' and ((o.order_state=0 and o.transport_state=0) or (o.order_state=1 and o.transport_state=0) or (o.order_state=1 and o.transport_state=1) or (o.order_state=1 and o.transport_state=12))";
	$Sql      .=$Add_one."   ".$Add_two."   ".$Add_three. " " . $Add_four ."  ".$Add."  ".$Provider_Search." ";
	$Sql      .= $goodsSql1 . "  order by o.order_serial_together desc,o.createtime desc";



}else{
	//下边如果不参与查询的资料
	$_GET['ifpage'] = 1;
	if ($_GET[Order_Tracks]=="Show")
		$begtimeunix = 0;

	if(trim($_GET['State'])=="NoOp"||trim($_GET['State'])=="Cancel"||trim($_GET['State'])=="Back")
	  $sSql      = " 1=1 ";
   else
	  $sSql      = " o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' and ((o.order_state=0 and o.transport_state=0) or (o.order_state=1 and o.transport_state=0) or (o.order_state=1 and o.transport_state=1) or (o.order_state=1 and o.transport_state=12)) ";

	$Add  = $_GET['State']!=""  ? str_replace("and","where",$Add)." and  " . $sSql :  " where  " . $sSql;


	$Add  = $_GET[Order_Tracks]=="Show" ? $Add." and o.order007_status=1 and  o.order007_begtime <= '".date("Y-m-d",time())."' " : $Add ;


	$Sql  = " select u.true_name,o.order_serial,o.order_id,o.createtime,o.totalprice,o.transport_price,o.invoice_code,o.MallgicOrderId,
	         o.transport_price,o.deliveryname,o.paymentname,o.receiver_name,o.order_state,o.pay_state,
	         ou.sys_say,ou.userback_type,ou.userback_alread  ,o.ticketmoney,o.bonuspoint,o.ticketcode,o.discount_totalPrices ,o.piaocode,o.transport_state,o.provider_id,o.MallgicOrderId,o.ifgroup,o.order_serial_together
	         from `{$INFO[DBPrefix]}order_table` o  left join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)
	         ".$Join." `{$INFO[DBPrefix]}order_userback` ou on (o.order_id=ou.order_id ) ".$Add."  ";
	$Sql      .= "  order by o.order_serial_together desc,o.createtime desc";

}

//echo $Sql;

if ($_GET['ifpage']==1){
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if ($Num>0){
		$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
		$Nav->total_result=$Num;
		$Nav->execute($Sql,$limit);
		$Nums     = $Num<$limit ? $Num : $limit ;
		$Query    = $Nav->sql_result;
	}
}else{
	$Query    = $DB->query($Sql);
	$Nums      = $DB->num_rows($Query);
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Order_List];//定单列表?> <?php echo $Topname?>
</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<div id="fullBg"></div>
<div id="msg">
<div id="close"></div>
<div id="ctt"></div>
</div>
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toUser(id){
	var checkvalue;

		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
		//return false;

	if (checkvalue!=false){
		//alert(checkvalue);
		document.adminForm.method = "post";
		document.adminForm.action = "admin_userback.php?order_id="+checkvalue;
		//document.adminForm.target="";
		document.adminForm.submit();
	}
	return false;
	//return false;
}

function toExcel(id){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		//if (confirm('<?php echo $Basic_Command['Print_Select']?>')){
		    document.adminForm.method = "post";
		    document.adminForm.action = "admin_order_excel.php";
			//document.adminForm.Order_id.value="Order_id";
			//document.adminForm.target="_blank";
			document.adminForm.submit();
		//}
	}
}
function toExcelTrans(id){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		//if (confirm('<?php echo $Basic_Command['Print_Select']?>')){
		    document.adminForm.method = "post";
		    document.adminForm.action = "admin_order_exceltrans.php";
			//document.adminForm.Order_id.value="Order_id";
			//document.adminForm.target="_blank";
			document.adminForm.submit();
		//}
	}
}

function toyouju(id){
	document.adminForm.action = "admin_order_youju.php?<?php echo $_SERVER['QUERY_STRING'];?>";
	document.adminForm.target="";
	document.adminForm.submit();
}
function todizhi(id){
	document.adminForm.action = "admin_order_addr.php?<?php echo $_SERVER['QUERY_STRING'];?>";
	document.adminForm.target="";
	document.adminForm.submit();
}
function torid(id){
	document.adminForm.action = "admin_order_excelrid.php?<?php echo $_SERVER['QUERY_STRING'];?>";
	document.adminForm.target="";
	document.adminForm.submit();
}

function tocat(id){

	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		//if (confirm('<?php echo $Basic_Command['Print_Select']?>')){
		    document.adminForm.method = "post";
		    document.adminForm.action = "admin_order_excelcat.php";
			//document.adminForm.Order_id.value="Order_id";
			//document.adminForm.target="_blank";
			document.adminForm.submit();
		//}
	}
}
function toxinzhu(id){

	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		//if (confirm('<?php echo $Basic_Command['Print_Select']?>')){
		    document.adminForm.method = "post";
		    document.adminForm.action = "admin_order_excelxinzhu.php";
			//document.adminForm.Order_id.value="Order_id";
			//document.adminForm.target="_blank";
			document.adminForm.submit();
		//}
	}
}
function toEdit(id){
	var checkvalue;
	var catvalue = "";

	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');

	}
	//}else{
	//	checkvalue = id;
	//}

	//alert("aaaaaaa");
	if (checkvalue!=false){
		if (id == 0) {
			id = checkvalue;
		}
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_order.php?Action=Modi&order_id="+id;
		document.adminForm.act.value="edit";
		document.adminForm.method = "post";
		document.adminForm.submit();
	}
	return false;
}

function toDel(){
	var checkvalue;
	///alert("a");
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');

	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_order_act.php";
			document.adminForm.act.value="Del";
			document.adminForm.Order_id.value="Order_id";
			document.adminForm.submit();
		}
	}
	return false;

}

function toPrint(){
    // javascript:print();
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Print_Select']?>')){
		    document.adminForm.method = "get";
		    document.adminForm.action = "admin_order_more.php";
			document.adminForm.act.value="Print_Sel";
			document.adminForm.target="_blank";
			//document.adminForm.Order_id.value="Order_id";
			document.adminForm.submit();
		}
	}

}
function toTrans(){
    // javascript:print();
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('您確認列印出貨明細嗎？')){
		    document.adminForm.method = "post";
		    document.adminForm.action = "admin_order_trans.php";
			document.adminForm.act.value="Print_Sel";
			document.adminForm.target="_blank";
			//document.adminForm.Order_id.value="Order_id";
			document.adminForm.submit();
		}
	}

}
</SCRIPT>

<SCRIPT language=JavaScript>
<!--
function MM_findObj(n, d) { //v4.0
  var p,i,x;
  if(!d) d=document;
  if((p=n.indexOf("?"))>0 && parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n];
  for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n);
  return x;
}

function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3)
    if ((obj=MM_findObj(args[i]))!=null) {
	  v=args[i+2];
      if (obj.style) {
	    obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
      obj.visibility=v;
	}
}

function changeOrderState(state_values,state_types){
	document.all.state_value.value =state_values;
	document.all.state_type.value =state_types;
	showWin('url','admin_order_showact.php?state_value='+state_values+'&state_type='+state_types,'',300,300);
}
function showPsd(act){
	showWin('url','admin_excel_check.php?act='+act,'',300,250);
}
//-->
</SCRIPT>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="28%">
              <TABLE width="69%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD width="150" noWrap class=p12black><SPAN class=p9orange><?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Order_List];//定单列表?> <?php echo $Topname?>
                      </SPAN>				</TD>
              </TR></TBODY></TABLE></TD>
            <TD align=right width="72%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=middle>

                    <TABLE>
                      <TBODY>
                        <TR>
                          <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:javascript:showPsd('toExcel');"><IMG  src="images/smallbutton.gif"  border=0>&nbsp;宅配通&nbsp;</a> </TD>
                          </TR>
                        </TBODY>
                      </TABLE>

                    </TD>
                  <TD align=middle>

                    <TABLE>
                      <TBODY>
                        <TR>
                          <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:javascript:showPsd('tocat');"><IMG  src="images/ico-export-blackcat.gif"  border=0>&nbsp;黑貓格式&nbsp;</a> </TD>
                          </TR>
                        </TBODY>
                      </TABLE>

                    </TD>
                  <TD align=middle><TABLE>
                      <TBODY>
                        <TR>
                          <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:javascript:showPsd('toxinzhu');"><IMG  src="images/ico-export-hct.gif"  border=0>&nbsp;新竹貨運&nbsp;</a> </TD>
                          </TR>
                        </TBODY>
                      </TABLE></TD>
                  <TD align=middle>

                    <TABLE>
                      <TBODY>
                        <TR>
                          <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:javascript:showPsd('toExcelTrans');"><IMG  src="images/smallbutton.gif"  border=0>&nbsp;導出物流EXCEL&nbsp;</a> </TD>
                          </TR>
                        </TBODY>
                      </TABLE>

                    </TD>
                  <TD align=middle>

                    <TABLE>
                      <TBODY>
                        <TR>
                          <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:showPsd('torid');"><IMG  src="images/ico-export-markettaiwan.gif"  border=0>&nbsp;美安訂單&nbsp;</a> </TD>
                          </TR>
                        </TBODY>
                      </TABLE>

                    </TD>
                  <!--TD align=middle>

                    <TABLE>
                      <TBODY>
                        <TR>
                          <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:todizhi(0);"><IMG  src="images/<?php echo $INFO[IS]?>/smallbutton_address.gif"  border=0>&nbsp;地址套印&nbsp;</a> </TD>
                          </TR>
                        </TBODY>
                      </TABLE>

                    </TD-->

                  <!--TD align=middle>

                    <TABLE >
                      <TBODY>
                        <TR>
                          <TD vAlign=bottom noWrap class="link_buttom">
                            <a  href="#" onClick="javascript: toUser(0);"><IMG  src="images/<?php echo $INFO[IS]?>/ni0029-32.gif"  border=0>&nbsp;回覆取消訂單</a></TD>
                          </TR>
                        </TBODY>
                      </TABLE>

                    </TD-->

                  <TD align=middle>

                            <TABLE >
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD>
                          </TR></TBODY></TABLE></TD>
                  <?php
					if($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
					?>
                  <TD align=middle>

                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href='javascript:void(0);' onclick="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD>
                          </TR></TBODY></TABLE></TD>
                  <?php
					}
?>
                  <!--?php if (intval($_GET[orderstate])==2 || intval($_GET[orderstate])=="3" || intval($_GET['paystate'])=='1') {?-->
                  <TD align=middle>

                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                                    <a href="javascript:toPrint();"><IMG  src="images/<?php echo $INFO[IS]?>/fb-print.gif" width="32" height="29"  border=0>&nbsp;揀貨明細</a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE>

                    </TD>
                  <!--?php } ?-->
                  <TD align=middle>

                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                                    <a href="javascript:toTrans();"><IMG  src="images/<?php echo $INFO[IS]?>/fb-print.gif" width="32" height="29"  border=0>&nbsp;出貨明細</a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE>

                    </TD>
                  </TR>
                </TBODY>
              </TABLE>

              </TD>
            </TR>
          </TBODY>
        </TABLE>

<FORM name=optForm method=get action="" onSubmit="return searchsubmit('<?php echo $Order_Pack[DateError_i]?>','<?php echo $Order_Pack[DateError_ii]?>','<?php echo $Order_Pack[DateError_iii]?>');">
        <input type="hidden" name="action" value="search">
        <input type="hidden" name="State" value="<?php echo $_GET['State'];?>">
<TABLE class=allborder cellSpacing=0 cellPadding=0 width="100%"  align=center border=0 style="margin-top:10px;margin-bottom:10px">
          <TR>
            <TD align=left colSpan=2 height=71 style="padding-left:10px">
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width='100%' border=0>
                <TBODY>
                  <TR>
                    <TD height=31 colspan="3" align=left nowrap>
                      <SELECT name='type' class="trans-input" >
                        <OPTION    value="o.order_serial" <?php if ($_GET['type']=='o.order_serial') echo " selected ";?>><?php echo $Order_Pack[OrderSerial_say];//订单号?></OPTION>
                        <OPTION    value="od.goodsname" <?php if ($_GET['type']=='od.goodsname') echo " selected ";?>>商品名稱/編號</OPTION>
                        <OPTION    value="o.invoice_code" <?php if ($_GET['type']=='o.invoice_code') echo " selected ";?>>發票號碼</OPTION>
                        <OPTION    value="u.true_name" <?php if ($_GET['type']=='u.true_name') echo " selected ";?>><?php echo $Order_Pack[S_dgr];//购货人?></OPTION>
                        <OPTION    value="o.totalprice <" <?php if ($_GET['type']=='o.totalprice <') echo " selected ";?>><?php echo $Order_Pack[S_Szje];//＜（总金额）?></OPTION>
                        <OPTION    value="o.totalprice >" <?php if ($_GET['type']=='o.totalprice >') echo " selected ";?>><?php echo $Order_Pack[S_Lzje];//＞（总金额）?></OPTION>
                        <OPTION    value="o.totalprice =" <?php if ($_GET['type']=='o.totalprice =') echo " selected ";?>><?php echo $Order_Pack[S_Gzje];//＝（总金额）?></OPTION>
                        </SELECT>
                      <span class="p9black">&nbsp;<?php echo $Good[ProviderNameText] ;?><!--供貨商--></span>：<?php echo $FUNCTIONS->select_type("select provider_name,provider_id from `{$INFO[DBPrefix]}provider` order by provider_id asc  ","provider_id","provider_id","provider_name",intval($_GET['provider_id']));  ?>&nbsp;&nbsp;From
                      <INPUT   id=begtime3 size=10 value=<?php echo $begtime?>    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />
                      To
                      <INPUT    id=endtime3 size=10 value=<?php echo $endtime?>      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />				 </TD>
                    </TR>
                  <TR>
                    <TD width="79%" height=31 align=left nowrap class="p9black">
                      <?php
							$Sql      = "select u.*  from `{$INFO[DBPrefix]}saler` u order by u.id desc";
$Query_c    = $DB->query($Sql);
$Num      = $DB->num_rows($Query_c);
while ($Rs=$DB->fetch_array($Query_c)) {
	$company .="<option value='".$Rs['login']."' ";
	if($_GET['company']==$Rs['login'])
		$company .= " selected ";
	$company .= " >".$Rs['name']."</option>\n";
}
							?>
                      <select name="company">
                        <option value="">請選擇公司</option>
                        <?php echo $company;?>
                        </select>
                      <select name="transportation">
                        <option value="0">請選擇貨運方式</option>
                        <?php
                            $Sql_t      = "select * from `{$INFO[DBPrefix]}transportation` order by transport_id ";
							$Query_t    = $DB->query($Sql_t);
							$Num_t      = $DB->num_rows($Query_t);
							while ($Rs_t=$DB->fetch_array($Query_t)) {
							?>
                        <option value="<?php echo $Rs_t['transport_name'];?>" <?php if($Rs_t['transport_name'] == $_GET['transportation']) echo "selected";?>><?php echo $Rs_t['transport_name'];?></option>
                        <?
							}
							?>
                        </select>
                      <SELECT name='ifmobile' class="trans-input">
                        <OPTION value="" >所有訂單</OPTION>
                        <OPTION value="2" <?php if($_GET['ifmobile']==2) echo "selected";?>>電腦訂單</OPTION>
                        <OPTION value="1" <?php if($_GET['ifmobile']==1) echo "selected";?> >手機訂單</OPTION>
                        </SELECT>
                        <SELECT name='payment' class="trans-input">
                        <OPTION value="" >所有訂單</OPTION>
                        <?php
				$paysql = "select * from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1 order by p.mid";
				$payQuery = $DB->query($paysql);
				while($payResult= $DB->fetch_array($payQuery)){
				?>
                      <option value="<?php echo $payResult['mid'];?>"  <?php if($_GET['payment']==$payResult['mid']){?>selected<?php }?>><?php echo $payResult['methodname'];?>
                      <?php
				}
				?>
                </select>
                      <INPUT    onmouseover='this.focus()' onfocus='this.select()'  onclick="if(this.value=='<?php echo $Basic_Command['InputKeyWord'] ;?>') this.value=''"  value='<?php echo $Basic_Command['InputKeyWord'] ;//输入关键字?>'   size='30'  name='skey'><input name="iftogether" type="radio" value="1" <?php if (intval($_GET[iftogether])==1  ) { echo " checked "; }?>>超商取貨<input type="radio" name="iftogether" value="-1" <?php if (intval($_GET[iftogether])=="-1" ) { echo " checked "; }?>>一般宅配&nbsp;&nbsp;捐贈發票
                      <input name="ifinvoice" type="radio" id="ifinvoice" value="3" <?php if($_GET['ifinvoice']==3) echo "checked";?> />
                      是
                      <input name="ifinvoice" type="radio" id="ifinvoice" value="-1" <?php if($_GET['ifinvoice']==-1) echo "checked";?> />
                      否</TD>
                    <TD width="8%" align=left nowrap class="p9black">

                      <input name="ifpage" type="checkbox" value="1" <?php if($_GET['ifpage']==1) echo "checked";?>>分頁顯示</TD>
                    <TD width="13%" align=left nowrap><input  type='image' src="images/<?php echo $INFO[IS]?>/t_go.gif" border='0' name='imageField' /></TD>
                    </TR>
                  </TBODY>
              </TABLE></TD>
            <TD width=29% height=71 align=right valign="top" nowrap class=p9black style="padding-top:5px;padding-right:10px">
              <p><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>
                <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </p></TD>
            </TR>

          </TABLE>

        </form>
        <FORM name=optForm method=post action="admin_order_list.php" id="delform" >
        <input type="hidden" name="action" value="delphone">
<TABLE class=allborder cellSpacing=0 cellPadding=0 width="100%"  align=center border=0 style="margin-top:10px;margin-bottom:10px">
          <TR>
            <TD align=left colSpan=2 height=71 style="padding-left:10px">
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width='100%' border=0>
                <TBODY>
                  <TR>
                    <TD height=31 colspan="3" align=left nowrap>刪除前<span class="p9black">
                      <input size='5' name='days' />
                    </span>天訂單電話
<a href="#" onclick="if(confirm('您確認刪除訂單電話嗎？'))document.getElementById('delform').submit();"><img  type='image' src="images/<?php echo $INFO[IS]?>/t_go.gif" border='0' name='imageField2' /></a></TD>
                  </TR>
                  </TBODY>
              </TABLE></TD>
              </TR>

          </TABLE>
          
          </form>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <!--form name="ExportExcel" action="admin_order_excelput.php" method="post"  enctype="multipart/form-data" >

            <TR>
              <TD width="34%" height=31 align=left style="padding-left:10px">郵局格式導入：
                <input type="file" name="cvsEXCEL"  ID='cvsEXCEL' />
                <button name="Submit" type="submit" value="導入" size="20"/>
              導入</button></TD>
              <TD width="66%" align=right></TD>
            </TR>
          </form-->
        <form name="ExportExcel" action="admin_order_excelput2.php" method="post"  enctype="multipart/form-data" >

          <TR>
            <TD align=left style="padding-left:10px">發票號碼導入：
              <input type="file" name="cvsEXCEL"  ID='cvsEXCEL' />
              <button name="Submit" type="submit" value="導入" size="20"/>
              導入</button></TD>
            <TD align="right" valign="top" style="padding-bottom:10px">
            <div class="order_button">
            <ul>
             <li><a href="javascript:changeOrderState(1,1);">確認</a></li>
             <li><a href="javascript:changeOrderState(3,1);">取消訂單</a></li>
             <li><a href="javascript:changeOrderState(1,2);">到款</a></li>
             <li><a href="javascript:changeOrderState(1,3);">出貨</a></li>
             <li><a href="javascript:changeOrderState(2,3);">已到貨</a></li>
             <li><a href="javascript:changeOrderState(6,3);">退貨</a></li>
             <li><a href="javascript:changeOrderState(12,3);">備貨</a></li>
             <li><a href="javascript:changeOrderState(13,3);">退貨異常</a></li>
             <li><a href="javascript:changeOrderState(15,3);">配送異常</a></li>
             <li><a href="javascript:changeOrderState(16,3);">貨已到店</a></li>
             <li><a href="javascript:changeOrderState(17,3);">逾期未取店退</a></li>
             <li><a href="javascript:changeOrderState(18,3);">已取貨</a></li>
             <li><a href="javascript:changeOrderState(6,1);">異常</a></li>
             <li><a href="javascript:changeOrderState(4,1);">完成交易</a></li>
            </ul>
            </div></TD>
            </TR>
          </form>
        <TR>
          <TD height=210 colspan="2" vAlign=top>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TR>
                  <TD bgColor=#ffffff>
                    <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                      <FORM name=adminForm id=adminForm action="" method=post>
                        <INPUT type=hidden name=Order_id>
                        <INPUT type=hidden name=remark id=remark>
                        <INPUT type=hidden name=state_value>
                        <INPUT type=hidden name=optype value="1">
                        <INPUT type=hidden name=state_type>
                        <INPUT type=hidden name=act>
                        <input type='hidden' name="Url" value="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>">
                        <INPUT type=hidden value=0  name=boxchecked>
                        <INPUT type=hidden name=piaocode id=piaocode>
                        <INPUT type=hidden name=sendtime id=sendtime>
                        <INPUT type=hidden name=sendname id=sendname>

                          <TR align=middle>
                            <TD width="3%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                              <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                               <TD width="100" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>合併訂單號</TD>
                            <TD width="100"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[OrderSerial_say];//订单号?></TD>
                            <TD width="7%" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>   <?php echo $Order_Pack[OrderCreatetime_say];//下单日期?></TD>
                            <TD width="7%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Cart[send_money_say];//配送費用?></TD>
                            <TD width="6%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>消費總金額</TD>
                            <TD width="7%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>配送方式</TD>
                            <TD width="14%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Cart[Pay_type];//付款方式?></TD>
                            <TD width="6%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[S_dgr];//訂購人?></TD>
                            <TD width="6%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[ShouHuoren];//收貨人?></TD>
                            <TD width="8%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>發票號碼</TD>
                            <TD width="8%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>物流號碼</TD>
                            <TD width="5%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>訂單類型</TD>
                            <TD width="5%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>訂單狀態</TD>
                            <TD width="5%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>支付狀態</TD>
                            <TD width="5%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> 配送狀態 </TD>
                            </TR>
                          <?php


					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {


					?><tbody>
                            <TR class=row0>
                              <TD align=middle height=26>
                              <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['order_id']?>' name=cid[]> </TD>
                              <TD align="left" noWrap>
                      <?php if ($Rs['order_serial_together']!="") echo"<a href=\"javascript:showWin('url','admin_order_together.php?order_id=" . $Rs['order_id'] . "','',300,250);\">" . $Rs['order_serial_together'] . "</a>"; else echo "<div class=link_box><a href=\"javascript:showWin('url','admin_order_together.php?order_id=" . $Rs['order_id'] . "','',300,250);\">填寫</a></div>";?></TD>
                              <TD height=26 align="left" noWrap>
                                <a href="javascript:toEdit('<?php echo $Rs['order_id']?>')">
                              <?php echo $Rs['order_serial']?><?php if ($Rs['ifgroup']==1) echo "[團購]";?>                        </a> </TD>
                              <TD height=26 align="left" noWrap>
                              <?php echo date("Y-m-d",$Rs['createtime'])?>&nbsp;</TD>
                              <TD height=26 align="center" noWrap><?php echo $Rs['transport_price']?></TD>
                              <TD height=26 align="center" noWrap><?php echo $Rs['discount_totalPrices']+$Rs[transport_price];?></TD>
                              <TD align="center" noWrap><?php echo $Rs['deliveryname']?></TD>
                              <TD height=26 align="center" noWrap>
                              <?php echo $Rs['paymentname']?>&nbsp;</TD>
                              <TD height=26 align="center" noWrap>
                              <?php echo $Rs['true_name']?>&nbsp;</TD>
                              <TD height=26 align="center" noWrap><?php echo $Rs['receiver_name']?>&nbsp;</TD>
                              <TD align=center nowrap><?php if ($Rs['invoice_code']!="") echo"<a href=\"javascript:showWin('url','admin_order_invoice.php?order_id=" . $Rs['order_id'] . "','',300,250);\">" . $Rs['invoice_code'] ."</a>"; else echo "<div class=link_box><a href=\"javascript:showWin('url','admin_order_invoice.php?order_id=" . $Rs['order_id'] . "','',300,250);\">填寫</a></div>";?></TD>
                              <TD align=center nowrap>
                                <?php if ($Rs['piaocode']!="") echo "<a href=\"javascript:showWin('url','admin_order_piaocode.php?order_id=" . $Rs['order_id'] . "','',300,250);\">". $Rs['piaocode'] . "</a>"; else echo "<div class=link_box><a href=\"javascript:showWin('url','admin_order_piaocode.php?order_id=" . $Rs['order_id'] . "','',300,250);\">填寫</a></div>";?>
                              </TD>
                              <TD align=center nowrap>
                                <?php
					  if ($Rs['MallgicOrderId']!="")
					  	echo "FACEBOOK";
					 elseif ($Rs['provider_id']>0)
						 echo "供應商";
					 elseif ($Rs['provider_id']==0)
					 	echo "統倉";
					  ?>
                              </TD>
                              <TD align=center nowrap><?php echo  $orderClass->getOrderState($Rs['order_state'],1)?></TD>
                              <TD align=center nowrap><?php echo $orderClass->getOrderState(intval($Rs['pay_state']),2)  ?></TD>
                              <TD height=26 align=center nowrap><?php echo $orderClass->getOrderState(intval($Rs['transport_state']),3) ?></TD>
                            </TR></tbody>
                        <?php
					$i++;
					}
					?>
                        <TR>
                          <TD align=middle height=14>&nbsp;</TD>
                          <TD height=14>&nbsp;</TD>
                          <TD height=14>&nbsp;</TD>
                          <TD height=14>&nbsp;</TD>
                          <TD height=14>&nbsp;</TD>
                          <TD>&nbsp;</TD>
                          <TD height=14>&nbsp;</TD>
                          <TD height=14>&nbsp;</TD>
                          <TD height=14>&nbsp;</TD>
                          <TD>&nbsp;</TD>
                          <TD>&nbsp;</TD>
                          <TD height=14>&nbsp;</TD>
                          <TD>&nbsp;</TD>
                          <TD>&nbsp;</TD>
                          <TD height=14>&nbsp;</TD>
                          </TR>
                        </FORM>
                      </TABLE>

                    </TD>
                  </TR>
              </TABLE>
            <?php if ($Num>0){ ?>
            <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
              <TBODY>
                <TR>
                  <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                    <?php echo $Nav->pagenav()?>
                    </TD>
                  </TR>
              </TABLE>
            <?php } ?>
        </TD></TR></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
