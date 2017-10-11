<?php
include_once "Check_Admin.php";
include_once Classes . "/GD_Drive.php";
include_once Classes . "/Time.class.php";
include_once Classes . "/SaleMap.class.php";
include      "../language/".$INFO['IS']."/Visit_Pack.php";
include      "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include      "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);


include_once Classes . "/Time.class.php";
include_once Classes . "/orderClass.php";
$TimeClass = new TimeClass;
$orderClass = new orderClass;
$year  = $_GET['year']!="" ? $_GET['year'] : date("Y",time());
$month  = $_GET['month']!="" ? $_GET['month'] : date("m",time());

$begtime  =date("Y-m-d H:i:s",mktime(0, 0 , 0,$month-1,26,$year));
$endtime  = date("Y-m-d H:i:s",mktime(0, 0 , 0,$month,25,$year));
$begtimeunix  =mktime(0, 0 , 0,$month-1,26,$year);
$endtimeunix  = mktime(0, 0 , 0,$month,25,$year)+60*60*24;
$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
if($_GET['type']=="ll"){
	$subSql = " and p.provider_id='114'";
	$_GET['provider_id'] = '114';
	$subSql2 = " and pm.pid='114'";	
	$cost = "cast(od.goods_cost as DECIMAL )";
}elseif (intval($_GET['province'])>0 || intval($_GET['provider_id'])>0){
	$subSql = " and p.provider_id='" . intval($_GET['provider_id']) . "'";
	$subSql2 = " and pm.pid='" . intval($_GET['provider_id']) . "'";
	$cost = "od.cost*od.goodscount";
}else{
	$subSql = " and p.provider_id<>'114'";	
	$subSql2 = " and pm.pid<>'114'";	
	$cost = "od.cost*od.goodscount";
	
}
if ($_GET['skey']!=""){
	 $togetherSql3 .= " and p." . $_GET['provider_type'] . " like '%" . $_GET['skey'] . "%'";
}
if ($_GET['ifcheck']=="1"){
	$togetherSql3 .= " and pm.ifok=1";
}
if ($_GET['ifcheck']=="0"){
	$togetherSql3 .= " and pm.ifok=0";
}
if (intval($_GET['iftogether'])>0){
	$togetherSql .= " and ot.deliveryid='" . intval($_GET['iftogether']) . "'";
	$togetherSql2 .= " and iftogether='" . intval($_GET['iftogether']) . "'";
	$togetherSql3 .= " and pm.iftogether='" . intval($_GET['iftogether']) . "'";
}
$restring = "&type=" . $_GET['type'] . "&skey=" . $_GET['skey'] . "&provider_type=" . $_GET['provider_type'] . "&ifcheck=" . $_GET['ifcheck'] . "&iftogether=" . $_GET['iftogether'] . "&year=" . $_GET['year'] . "&month=" . $_GET['month'];
//判斷是否存在這個月的結算，沒有就生成
//先判斷查詢月份是否到25日
if(($month==date("m",time())&&date("d",time())>25) || $year.$month<date("Ym",time())){
	//$Sql = "select * from `{$INFO[DBPrefix]}provider_month` as pm where pm.year='" . $year . "' and pm.month='" . $month . "' " . $togetherSql2 . " " . $subSql2 . "";
	//$Query_mm    = $DB->query($Sql);
	//$Num_mm      = $DB->num_rows($Query_mm);
	//$Rs_mm=$DB->fetch_array($Query_mm);
	
		$Sql_t = "select p.*,ot.deliveryid,p.provider_id as provider_id,pm.mid,pm.ifok from `{$INFO[DBPrefix]}order_action` oa 
inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0) and oa.order_id=od.order_id
left join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=oa.order_id 
left join `{$INFO[DBPrefix]}provider` as p on p.provider_id=od.provider_id left join  `{$INFO[DBPrefix]}provider_month` as pm on (p.provider_id=pm.pid and pm.year='" . $year . "' and pm.month='" . $month . "'  )

where oa.state_type=3 and (oa.state_value=13 or oa.state_value=17 or oa.state_value=20 or oa.state_value=1) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . " " . $togetherSql . " group by p.provider_id";  
		$Query_t    = $DB->query($Sql_t);
		$Num_t      = $DB->num_rows($Query_t);
		if($Num_t>0){
			$i=0;
			while ($Rs=$DB->fetch_array($Query_t)) {
				if($Rs['ifok']==0){
					$Sql_d = "select " . $cost . " as sumcost,ot.* from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0 )and oa.order_id=od.order_id inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' and od.provider_id='" . $Rs['provider_id'] . "'  group by od.order_detail_id order by ot.order_id";
					$Query_d    = $DB->query($Sql_d);
					$total = 0;
					$yunfei = 0;
					$Num_d      = $DB->num_rows($Query_d);
					$curorder = "";
					
					$transport_price = 0;
					while ($Rs_d=$DB->fetch_array($Query_d)) {
						//echo round($Rs_d['sumcost']*1.05,0) . "|";
						$total_a = $total+= round($Rs_d['sumcost']*1.05,0);
						if($curorder!=$Rs_d['order_serial']){
							$yunfei += round($transport_price*1.05,0);
						//	echo round($transport_price*1.05,0) . "|";
						}
						$curorder = $Rs_d['order_serial'];
						$curdeliveryid = $Rs_d['deliveryid'];
						$transport_price = $Rs_d['transport_price'];
					}
					
					$yunfei += round($transport_price*1.05,0);
					$total+= $yunfei;
					//echo  "\r\n<br>";
					//exit;
					
					//$Sql_d = "select cast(od.goods_cost as DECIMAL) as sumcost,ot.* from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0 )and oa.order_id=od.order_id inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and (oa.state_value=13 or oa.state_value=17 or oa.state_value=20) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' and od.provider_id='" . $Rs['provider_id'] . "' and ot.deliveryid='" . $Rs['deliveryid'] . "' group by od.order_detail_id order by ot.order_id";
					//echo date("Y-m-d",$begtimeunix);
					$Sql_d = "select ot.*," . $cost . " as sumcost,od.* from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on oa.order_detail_id=od.order_detail_id or (oa.order_detail_id=0 and oa.order_id=od.order_id) inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and (oa.state_value=20) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' and od.provider_id='" . $Rs['provider_id'] . "'   group by od.order_detail_id";
					$Query_d    = $DB->query($Sql_d);
					$yunfei = 0;
					$Num_d      = $DB->num_rows($Query_d);
					$curorder = "";
					
					$cur_house_return_single_count = 0;
					$transport_price = 0;
					while ($Rs_d=$DB->fetch_array($Query_d)) {
						//echo round($Rs_d['sumcost']*1.05,0) . "|";
						$total-= round($Rs_d['sumcost']*1.05,0);
						if($curorder!=$Rs_d['order_serial']){
						
							$yunfei += round($transport_price*1.05,0);
						}
						$curorder = $Rs_d['order_serial'];
						$curdeliveryid = $Rs_d['deliveryid'];
						
						$transport_price = $Rs_d['transport_price'];
					}
					
						$yunfei += round($transport_price*1.05,0);
					$total-= $yunfei;
					//echo  "\r\n<br>";
					
					
					 $total_d= round($total/1.05);
					 $total_e = round($total_d*0.05);
					 $total = $total_d+$total_e ;
				if($Rs['mid']<=0){
					$Result =  $DB->query("insert into `{$INFO[DBPrefix]}provider_month` (pid,year,month,money,optime,said,iftogether,paymoney,mark,ifok)values('" . intval($Rs['provider_id']) . "','" . $year . "','" . $month . "','" . $total . "','" . time() . "','" .  $_SESSION['sa_id'] . "','0','" . $total . "','" . ($_GET['mark']) . "',0)");
				}else{
					///echo "update `{$INFO[DBPrefix]}provider_month`  set paymoney = '" . $total . "',money = '" . $total . "' where mid='" . intval($Rs['mid']) . "'";
					$Result =  $DB->query("update `{$INFO[DBPrefix]}provider_month`  set paymoney = '" . $total . "',money = '" . $total . "' where mid='" . intval($Rs['mid']) . "'");	
				}
			}
		}
	}
	$Sql = "select p.*,pm.* from `{$INFO[DBPrefix]}provider_month` as pm inner join `{$INFO[DBPrefix]}provider` as p on p.provider_id=pm.pid where pm.year='" . $year . "' and pm.month='" . $month . "' " . $togetherSql3 . " " . $subSql2 . "";

	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if ($Num>0){
		$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
		$Nav->total_result=$Num;
		$Nav->execute($Sql,$limit);
		$Query    = $Nav->sql_result;
		$Nums     = $Num<$limit ? $Num : $limit ;
	}
}else{
	$error = "您查詢的月份還未到結算日，尚不能查看";
}

/*$Sql = "select p.*,ot.deliveryid,p.provider_id as provider_id from `{$INFO[DBPrefix]}order_action` oa 
inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0) and oa.order_id=od.order_id
left join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=oa.order_id 
left join `{$INFO[DBPrefix]}provider` as p on p.provider_id=od.provider_id 
left join `{$INFO[DBPrefix]}provider_month` as pm on (p.provider_id=pm.pid and pm.year='" . $current_year . "' and pm.month='" . $current_month . "'  and pm.iftogether=ot.deliveryid)
where oa.state_type=3 and (oa.state_value=13 or oa.state_value=17 or oa.state_value=20 or oa.state_value=1) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' and   ot.deliveryid>0 " . $subSql . " " . $togetherSql . " group by p.provider_id,ot.deliveryid";
*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>供應商管理--&gt;供應商對帳--&gt;交易明細</title>
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
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function toExcel(){
		document.adminForm.target ="_self";
			document.adminForm.action = "admin_statistic_provider_excel.php";
			document.adminForm.submit();
}
function toSearch(){
	document.adminForm.target ="_self";
			document.adminForm.action = "admin_statistic_provider_month.php";
			document.adminForm.submit();
}
function toSave(){
	document.adminForm.target ="_self";
			document.adminForm.action = "admin_statistic_provider_save.php";
			document.adminForm.submit();
}
function toPrint(){
	document.adminForm.target ="_blank";
			document.adminForm.action = "admin_statistic_provider_printall.php";
			document.adminForm.submit();
}
//-->
</SCRIPT>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>

  
   <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%" height="47">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>供應商管理--&gt;供應商對帳--&gt;交易總表</SPAN></TD>
              </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD align=middle>
                      <!--TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN Cache.php 
                        <TABLE class=fbottonnew link="">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Return'];//返回?>&nbsp; 
							</TD>
							</TR>
						  </TBODY>
					    </TABLE>
						<!--BUTTON_END
						</TD>
						</TR>
						</TBODY>
						</TABLE -->
                      </TD>
                    
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top height=262>
              <TABLE cellSpacing=0 cellPadding=0 width="100%"  class="allborder" style="margin-top:10px;">
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff height=300>
                      <form action="" method="get"  name="adminForm">
                        <input type="hidden" name="acts" value="suan">
                        <input type="hidden" name="type" value="<?php echo $_GET['type'];?>">
                        <TABLE class=p12black cellSpacing=0 cellPadding=0 width=474 border=0 style="margin-left:10px;">
                          <TBODY>
                            <TR>
                              <TD align=left width=220 height=31><INPUT  name='skey'    onfocus=this.select()  onclick="if(this.value=='<?php echo $Basic_Command['InputKeyWord'];?>')this.value=''"  onmouseover=this.focus() value="<?php echo $_GET['skey'];?>" size="30"></TD>
                              <TD width=254 height=31 align=left nowrap><select name="provider_type"  class="submit">
                                <option value="provider_name" <?php if($_GET['provider_type']=="provider_name") echo "selected"; ?>>廠商名稱</option>
                                <option value="providerno" <?php if($_GET['provider_type']=="providerno") echo "selected"; ?>>廠編</option>
                                </select></TD>
                              </TR>
                            <TR>
                              <TD height=31 colspan="2" align=left class=p9black><select name="year" id="year">
                                <?php
for($i=2012;$i<=date("Y",time());$i++){
?>
                                <option value="<?php echo $i;?>" <?php if($i==$year) echo "selected";?>><?php echo $i;?></option>
                                <?php
}
?>
                                </select>
                                年
                                <select name="month" id="month">
                                  <?php
for($i=1;$i<=12;$i++){
?>
                                  <option value="<?php echo $i;?>" <?php if($i==$month) echo "selected";?>><?php echo $i;?></option>
                                  <?php
}
?>
                                  </select>
                                月
                                &nbsp;</TD>
                              </TR>
                            <!--TR>
                              <TD height=31 colspan="2" align=left class=p9black><input type="radio" name="iftogether" id="radio" value="23" <?php if($_GET['iftogether']=="23") echo "checked"; ?>>
                                門市取貨
                                <input type="radio" name="iftogether" id="radio" value="22" <?php if($_GET['iftogether']=="22") echo "checked"; ?>>
                                宅配</TD>
                              </TR-->
                            <TR>
                              <TD height=31 colspan="2" align=left class=p9black><input type="radio" name="ifcheck" id="ifcheck" value="-1" <?php if($_GET['ifcheck']=="-1") echo "checked"; ?>>
                                全部
                                <input type="radio" name="ifcheck" id="ifcheck" value="1" <?php if($_GET['ifcheck']=="1") echo "checked"; ?>>
                                結案
                                <input type="radio" name="ifcheck" id="ifcheck" value="0" <?php if($_GET['ifcheck']=="0") echo "checked"; ?>>
                                未結案</TD>
                              </TR>
                            <TR>
                              <TD height=31 colspan="2" align=left><input type="button" name="button" id="button" value="查詢" onClick="toSearch();">
                                <input type="button" name="button2" id="button2" value="下載" onClick="toExcel();">
                                <input type="button" name="button5" id="button5" value="結案" onClick="toSave();">
                                <input type="button" name="button3" id="button3" value="列印總表" onClick="toPrint();"></TD>
                              </TR>
                            <TR>
                              <TD height=31 colspan="2" align=left class=p9black>共有<?php echo $Num;?>家供應商</TD>
                              </TR>
                            </TBODY>
                          </TABLE>
                        <?php echo $error;
						if ($Num>0){
						?>
                        <div style="display:block" id="showtable">
                        <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                          <TBODY>
                            <TR>
                              <TD bgColor=#ffffff><TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                                <INPUT type=hidden name=act>
                                <INPUT type=hidden value=0  name=boxchecked>
                                <TBODY>
                                  <TR align=middle>
                                    <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><INPUT onclick=checkAll('<?php echo intval($Nums)?>'); type=checkbox value=checkbox   name=toggle></TD>
                                    <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>編號</TD>
                                    <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>月份</TD>
                                    <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>廠編</TD>
                                    <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>廠商簡稱</TD>
                                    <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>請款金額</TD>
                                    <TD width="79" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>實際請款金額</TD>
                                    <TD width="79" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>票期</TD>
                                    <TD width="81" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>兌現日</TD>
                                    <TD width="122" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>廠商發票</TD>
                                    <TD width="175" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>備註</TD>
                                    <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>查看</TD>
                                    <TD width="61" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>請款單</TD>
                                    <TD width="72" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>對帳狀態</TD>
                                    <TD width="72" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>確認</TD>
                                    </TR>
                                  <?php  
							
					$i=0;
					
					while ($Rs=$DB->fetch_array($Query)) {  /*
						$Sql_d = "select case when (oa.state_value=13 or oa.state_value=17 or oa.state_value=20) then (0-cast(od.goods_cost as DECIMAL)) else (cast(od.goods_cost as DECIMAL )) end as sumcost,ot.* from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0 )and oa.order_id=od.order_id inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and (oa.state_value=13 or oa.state_value=17 or oa.state_value=20 or oa.state_value=1) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' and od.provider_id='" . $Rs['provider_id'] . "' and ot.deliveryid='" . $Rs['deliveryid'] . "' group by od.order_detail_id order by ot.order_id";
						$Query_d    = $DB->query($Sql_d);
						$total = 0;
						$yunfei = 0;
					    $Num_d      = $DB->num_rows($Query_d);
						$curorder = "";
						$cur_store_bundle_count = 0;
							$cur_store_single_count = 0;
							$cur_house_bundle_count = 0;
							$cur_house_single_count = 0;
							$cur_store_return_bundle_count = 0;
							$cur_store_return_single_count = 0;
							$cur_house_return_bundle_count =0;
							$cur_house_return_single_count = 0;
						while ($Rs_d=$DB->fetch_array($Query_d)) {
							//echo $Rs_d['sumcost'];
							//echo round($Rs_d['sumcost']*1.05,0) . "||";
							$total+= round($Rs_d['sumcost']*1.05,0);
							if($curorder!=$Rs_d['order_serial']){
								$yunfei += round($cur_store_bundle_count*10*1.05,0) + round($cur_store_single_count*16*1.05,0) + round($cur_house_bundle_count*10*1.05,0) + round($cur_house_single_count*16*1.05,0)-round($cur_store_return_bundle_count*5*1.05,0)-round($cur_store_return_single_count*8*1.05,0)-round($cur_house_return_bundle_count*5*1.05,0)-round($cur_house_return_single_count*8*1.05,0);
							}
							$curorder = $Rs_d['order_serial'];
							$curdeliveryid = $Rs_d['deliveryid'];
							$cur_store_bundle_count = $Rs_d['store_bundle_count'];
							$cur_store_single_count = $Rs_d['store_single_count'];
							$cur_house_bundle_count = $Rs_d['house_bundle_count'];
							$cur_house_single_count = $Rs_d['house_single_count'];
							$cur_store_return_bundle_count = $Rs_d['store_return_bundle_count'];
							$cur_store_return_single_count = $Rs_d['store_return_single_count'];
							$cur_house_return_bundle_count = $Rs_d['house_return_bundle_count'];
							$cur_house_return_single_count = $Rs_d['house_return_single_count'];
						}
								$yunfei += round($cur_store_bundle_count*10*1.05,0) + round($cur_store_single_count*16*1.05,0) + round($cur_house_bundle_count*10*1.05,0) + round($cur_house_single_count*16*1.05,0)-round($cur_store_return_bundle_count*5*1.05,0)-round($cur_store_return_single_count*8*1.05,0)-round($cur_house_return_bundle_count*5*1.05,0)-round($cur_house_return_single_count*8*1.05,0);
						//$yunfei = $Rs['store_bundle_count']*10 + $Rs['store_single_count']*16 + $Rs['house_bundle_count']*10 + $Rs['house_single_count']*16-$Rs['store_return_bundle_count']*5-$Rs['store_return_single_count']*8-$Rs['house_return_bundle_count']*5-$Rs['house_return_single_count']*8;
						$total+= $yunfei;
						//echo $yunfei;
						*/
					?>
                                  <TR class=row0>
                                    <TD align="left" noWrap><INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['mid']?>' name=cid[]><INPUT id='together<?php echo $i?>'  type=hidden value='<?php echo $Rs['iftogether']?>' name=together[]> </TD>
                                    <TD align="left" noWrap><?PHP echo $i+1;?></TD>
                                    <TD height=26 align="left" noWrap><?php echo $month;?>月</TD>
                                    <TD height=26 align="left" noWrap><?php echo $Rs['providerno']?></TD>
                                    
                                    <TD align=center nowrap><?php echo $Rs['provider_name'];?></TD>
                                    <TD height=26 align=center nowrap>
                                      <?php
								
								
								echo $Rs['money'];
								
								?>                                </TD>
                                    <TD align=center nowrap>
                                      
                                      <?php
								//echo $Rs['paymoney'];
                                //if (intval($Rs['ifok'])==0){
								?>
                                      <?php //echo $FUNCTIONS->Input_Box('text','paymoney' . $i,intval($Rs['paymoney']+round(intval($Rs['zhang'])*1.05,0)),"      maxLength=40 size=6 ")?>
                                      <?php
								//}else{
									echo intval($Rs['paymoney']+round(intval($Rs['zhang'])*1.05,0));	
								//}
								?>                                </TD>
                                    <TD align=center nowrap><?php echo $Rs['piaoqi']?></TD>
                                    <TD height=26 align=center nowrap><?php echo date("Y-m-d",$endtimeunix+intval($Rs['piaoqi'])*60*60*24);?></TD>
                                    <TD align=center nowrap><?php echo $FUNCTIONS->Input_Box('text','invoice_titles' . $i,$Rs['invoice_titles'],"      maxLength=40 size=6 ")?>&nbsp;</TD>
                                    <TD align=center nowrap>
                                      <?php
                                if (intval($Rs['ifok'])==0){
								?>
                                      <textarea name="mark<?php echo $i?>" id="mark<?php echo $i?>" cols="20" rows="3"><?php echo $Rs['mark']?></textarea>
                                      <?php
								}else{
									echo $Rs['mark'];	
								}
								?></TD>
                                    <!--TD width="51" align=center nowrap><div class="link_box"><a href="admin_statistic_provider_sale.php?provider_id=<?php echo $Rs['provider_id'].$restring;?>&iftogether=<?php echo $Rs['iftogether'];?>&mid=<?php echo intval($Rs['mid'])?>">銷售表</a></div></TD-->
                                    <TD width="42" align=center nowrap><div class="link_box"><a href="admin_statistic_provider_detail.php?provider_id=<?php echo $Rs['provider_id'].$restring;?>&iftogether=<?php echo $Rs['iftogether'];?>&mid=<?php echo intval($Rs['mid'])?>">對帳清單</a></div></TD>
                                    <TD height=26 align=center nowrap><div class="link_box"><a target="_blank" href="admin_statistic_provider_print.php?provider_id=<?php echo $Rs['provider_id'].$restring;?>&iftogether=<?php echo $Rs['iftogether'];?>&mid=<?php echo intval($Rs['mid'])?>">列印</a></div></TD>
                                    <TD height=26 align=center nowrap>
                                      <?php 
								if ($Rs['ifok']==1)
									echo "結案";
								else
									echo "<font color='red'>未結案</font>";
								?>                                </TD>
                                    <TD align=center nowrap><label>
                                      <?php
                                if (intval($Rs['ifok'])==0){
								?>
                                      <input type="button" name="button4" id="button4" value="確認" onClick="location.href='admin_statistic_provider_save.php?action=OK&mark=' + document.getElementById('mark<?php echo $i?>').value + '&invoice_titles=' + document.getElementById('invoice_titles<?php echo $i?>').value + '&provider_id=<?php echo $Rs['provider_id'];?>&iftogether=<?php echo $Rs['iftogether'];?>&mid=<?php echo $Rs['mid'];?><?php echo $restring;?>';">
                                      <?php
								}
								 ?>
                                      </label></TD>
                                    </TR>
                                  <?php
					$i++;
					}
					?>
                                  <TR>
                                    <TD width=20 nowrap>&nbsp;</TD>
                                    <TD width=55 nowrap>&nbsp;</TD>
                                    <TD width=55 height=14 nowrap>&nbsp;</TD>
                                    <TD width=104 height=14>&nbsp;</TD>
                                    <TD width=207>&nbsp;</TD>
                                    <TD width=92>&nbsp;</TD>
                                    <TD width=79>&nbsp;</TD>
                                    <TD width=79 height=14>&nbsp;</TD>
                                    <TD height=14 colspan="14">&nbsp;</TD>
                                    </TR>
                                </TABLE></TD>
                              </TR>
                        </TABLE></FORM>
                      <?php if ($Num>0){ ?>
                      <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                        <TBODY>
                          <TR>
                            <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav()?></TD>
                            </TR>
                        </TABLE>
                  <?php } 
						}
				  ?></div></TD></TR></TBODY></TABLE>
              
            </TD></TR></TBODY></TABLE></TD>
  </TR>
                    <TR>
                      <TD width="100%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
     </TR>
  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
