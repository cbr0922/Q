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
if(date("d",time())>=28){
	$btime = time();	
	
}else{
	$btime = mktime(0, 0 , 0,date("m",time())-1,date("d",time()),date("Y",time()));	
	
}
$years  = date("Y",$btime);
$year  = $_GET['year']!="" ? $_GET['year'] : date("Y",$btime);
$month  = $_GET['month']!="" ? $_GET['month'] : date("m",$btime);
$begtime  =date("Y-m-d H:i:s",mktime(0, 0 , 0,$month-1,26,$year));
$endtime  = date("Y-m-d H:i:s",mktime(0, 0 , 0,$month,25,$year));
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
	$subSql = " and od.provider_id='" . intval($_SESSION[sa_id]) . "'";
if ($_GET['skey']!=""){
	$togetherSql3 .= " and p." . $_GET['select_type'] . " like '%" . $_GET['skey'] . "%'";
}
if ($_GET['ifcheck']=="1"){
	$togetherSql3 .= " and pm.mid>0";
}
if ($_GET['ifcheck']=="0"){
	$togetherSql3 .= " and pm.mid is null";
}
if (intval($_GET['iftogether'])>0){
	$togetherSql .= " and ot.deliveryid='" . intval($_GET['iftogether']) . "'";
	$togetherSql2 .= " and iftogether='" . intval($_GET['iftogether']) . "'";
	$togetherSql3 .= " and pm.iftogether='" . intval($_GET['iftogether']) . "'";
}

$restring = "&provider_id=" . intval($_SESSION[sa_id]) . "&type=" . $_GET['type'] . "&skey=" . $_GET['skey'] . "&select_type=" . $_GET['select_type'] . "&ifcheck=" . $_GET['ifcheck'] . "&iftogether=" . $_GET['iftogether'] . "&year=" . $_GET['year'] . "&month=" . $_GET['month'];

//判斷是否存在這個月的結算，沒有就生成
//先判斷查詢月份是否到25日
if(strlen($month)==1)
	$month = "0" . $month;
	
if(($month==date("m",time())&&date("d",time())>25) || $year.$month<date("Ym",time())){
	$Sql = "select * from `{$INFO[DBPrefix]}provider_month` where year='" . $year . "' and month='" . $month . "' " . $togetherSql2 . "  and pid='" . intval($_SESSION[sa_id]) . "'";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if($Num<=0){
		$Sql_t = "select p.*,ot.deliveryid,p.provider_id as provider_id from `{$INFO[DBPrefix]}order_action` oa 
inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0) and oa.order_id=od.order_id
inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=oa.order_id 
inner join `{$INFO[DBPrefix]}provider` as p on p.provider_id=od.provider_id 
left join `{$INFO[DBPrefix]}provider_month` as pm on (p.provider_id=pm.pid and pm.year='" . $year . "' and pm.month='" . $month . "'  and pm.iftogether=ot.deliveryid)
where   oa.state_type=3 and (oa.state_value=13 or oa.state_value=17 or oa.state_value=20 or oa.state_value=1) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . " " . $togetherSql . " group by p.provider_id,ot.deliveryid";
		$Query_t    = $DB->query($Sql_t);
		$Num_t      = $DB->num_rows($Query_t);
		if($Num_t>0){
				$i=0;
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
					
					$Result =  $DB->query("insert into `{$INFO[DBPrefix]}provider_month` (pid,year,month,money,optime,said,iftogether,paymoney,mark,ifok)values('" . intval($_GET['provider_id']) . "','" . $year . "','" . $month . "','" . $total . "','" . time() . "','" .  $_SESSION['sa_id'] . "','" . intval($_GET['iftogether']) . "','" . $total . "','" . ($_GET['mark']) . "',0)");
				
		}
	}
	$Sql = "select p.*,pm.* from `{$INFO[DBPrefix]}provider_month` as pm inner join `{$INFO[DBPrefix]}provider` as p on p.provider_id=pm.pid where pm.year='" . $year . "' and pm.month='" . $month . "' " . $togetherSql3 . " and pm.pid='" . intval($_SESSION[sa_id]) . "'";
	
	
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
/*
$Sql = "select p.*,ot.deliveryid,p.provider_id as provider_id from `{$INFO[DBPrefix]}order_action` oa 
inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0) and oa.order_id=od.order_id
inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=oa.order_id 
inner join `{$INFO[DBPrefix]}provider` as p on p.provider_id=od.provider_id 
left join `{$INFO[DBPrefix]}provider_month` as pm on (p.provider_id=pm.pid and pm.year='" . $current_year . "' and pm.month='" . $current_month . "'  and pm.iftogether=ot.deliveryid)
where   oa.state_type=3 and (oa.state_value=13 or oa.state_value=17 or oa.state_value=20 or oa.state_value=1) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' and ot.deliveryid>0 " . $subSql . " " . $togetherSql . " group by p.provider_id,ot.deliveryid";
*/


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK id=css href="../css/calendar.css" type='text/css' rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
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
      <?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD>
  </TR>
  </TBODY>
</TABLE>
<SCRIPT language=javascript>
function toExcel(){
			document.adminForm.action = "provider_statistic_provider_excel.php";
			document.adminForm.submit();
}
function toSearch(){
			document.adminForm.action = "provider_statistic_provider.php";
			document.adminForm.submit();
}
function toSave(){
			document.adminForm.action = "provider_statistic_provider_save.php";
			document.adminForm.submit();
}

</SCRIPT>
<table cellspacing="0" cellpadding="0" width="97%" align="center" border="0">
  <tbody>
    <tr>
      <td width="1%" height="7"><img height="9" src="images/<?php echo $INFO[IS]?>/lt.gif" width="9" /></td>
      <td width="98%" background="images/<?php echo $INFO[IS]?>/top.gif" height="7"><img height="1"  src="images/<?php echo $INFO[IS]?>/spacer.gif" width="1" /></td>
      <td width="1%" height="7"><img height="9" src="images/<?php echo $INFO[IS]?>/rt.gif"   width="9" /></td>
    </tr>
    <tr>
      <td width="1%" background="images/<?php echo $INFO[IS]?>/left.gif" height="319"></td>
      <td valign="top" width="100%" height="319"><table class="p9black" cellspacing="0" cellpadding="0" width="100%" border="0">
        <tbody>
          <tr>
            <td width="50%"><table width="90%" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td width="38"><img height="32" src="images/<?php echo $INFO[IS]?>/program-1.gif"      width="32" /></td>
                  <td class="p12black" nowrap="nowrap"><span  class="p9orange">訂單管理--&gt;<?php echo $JsMenu[Sale];//销售统计?></span></td>
                </tr>
              </tbody>
            </table></td>
            <td align="right" width="50%"><table cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td align="middle"><!--TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
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
						</TABLE --></td>
                </tr>
              </tbody>
            </table></td>
          </tr>
        </tbody>
      </table>
        <table cellspacing="0" cellpadding="0" width="100%" border="0">
          <tbody>
            <tr>
              <td valign="top" height="262"><table cellspacing="0" cellpadding="0" width="100%" border="0">
                <tbody>
                  <tr>
                    <td valign="top" bgcolor="#ffffff" height="300"><br />
                    <form action="" method="get"  name="adminForm">
                      <table class="p12black" cellspacing="0" cellpadding="0" width="474" border="0">
                        <tbody>
                          <tr>
                            <td width="474" height="31" colspan="2" align="left" class="p9black"><input type="radio" name="iftogether" id="radio" value="23" <?php if($_GET['iftogether']=="23") echo "checked"; ?> />
門市取貨
  <input type="radio" name="iftogether" id="radio" value="22" <?php if($_GET['iftogether']=="22") echo "checked"; ?> />
宅配
<select name="year" id="year">
                                <?php
for($i=2012;$i<=$years;$i++){
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
&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="31" colspan="2" align="left"><input type="button" name="button" id="button" value="查詢" onclick="toSearch();" />
                              <input type="button" name="button2" id="button2" value="下載" onclick="toExcel();" /></td>
                          </tr>
                        </tbody>
                      </table>
                      </form>
                      <?php echo $error;
					  if ($Nums>0){
					  ?>
                      <div style="display:block" id="showtable">
                        <table cellspacing="0" cellpadding="0" width="100%" border="0">
                          <tbody>
                            <tr>
                              <td bgcolor="#ffffff"><table class="listtable" cellspacing="0" cellpadding="0"    width="100%" border="0">
                                <input type="hidden" name="act" />
                                <input type="hidden" value="0"  name="boxchecked" />
                                <tbody>
                                  <tr align="middle">
                                    <td align="left" nowrap="nowrap" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black">編號</td>
                                    <td  height="26" align="left" nowrap="nowrap" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black">對帳月份</td>
                                    <td  height="26" align="left" nowrap="nowrap" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black" style1="style1">廠編</td>
                                    <td align="center" nowrap="nowrap" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black">廠商</td>
                                    <td  height="26" align="center" nowrap="nowrap" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black">請款金額</td>
                                    <td width="165" align="center" nowrap="nowrap" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black">廠商發票</td>
                                    <td align="center" nowrap="nowrap" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black">查看</td>
                                    <td width="72" align="center" nowrap="nowrap" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black">請款單</td>
                                    <td width="72" align="center" nowrap="nowrap" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black">&nbsp;</td>
                                  </tr>
                                  <?php               
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
						/*
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
						*/
					?>
                    <form method="post" action="provider_statistic_save.php?provider_id=<?php echo $Rs['provider_id'].$restring;?>&iftogether=<?php echo $Rs['iftogether'];?>">
                    <input type="hidden" name="together" value="<?php echo $Rs['iftogether'];?>" />
                    <input type="hidden" name="mid" value="<?php echo $Rs['mid'];?>" />
                                  <tr class="row0">
                                    <td align="left" nowrap="nowrap"><?PHP echo $i+1;?></td>
                                    <td height="26" align="left" nowrap="nowrap"><?php echo $month;?>月</td>
                                    <td height="26" align="left" nowrap="nowrap"><?php echo $Rs['providerno']?></td>
                                    <td align="center" nowrap="nowrap"><?php echo $Rs['provider_name'];?></td>
                                    <td height="26" align="center" nowrap="nowrap">
                                    <?php
                                echo intval($Rs['paymoney']+round(intval($Rs['zhang'])*1.05,0));	
								?>  
                                    </td>
                                    <td align="center" nowrap="nowrap">
                                    <?php
                                    if ($Rs['invoice_titles']==""){
									?>
                                    <input name="invoice_title" id="invoice_title" value="<?php echo $Rs['invoice_titles']?>" />
                                    <?php
									}else
										echo $Rs['invoice_titles'];
									?>
                                    </td>
                                    <td width="42" align="center" nowrap="nowrap"><a href="provider_statistic_provider_detail.php?provider_id=<?php echo $Rs['pid'];?>&iftogether=<?php echo $Rs['iftogether'];?>&mid=<?php echo $Rs['mid'];?>&year=<?php echo $year;?>&month=<?php echo intval($month);?>">對帳清單</a>
                                    
                                    </td>
                                    <td align="center" nowrap="nowrap"><a href="provider_statistic_provider_detail_print.php?provider_id=<?php echo $Rs['pid'].$restring;?>&iftogether=<?php echo $Rs['iftogether'];?>&mid=<?php echo $Rs['mid'];?>" target="_blank">列印</a></td>
                                    <td height="26" align="center" nowrap="nowrap">
                                     <?php
                                    if ($Rs['invoice_titles']==""){
									?>
                                    <input type="submit" name="button4" id="button4" value="提交" />
                                    <?php
									}
									?>
                                    </td>
                                  </tr>
                                  </form>
                                  <?php
					$i++;
					}
					?>
                                  <tr>
                                    <td width="55" nowrap="nowrap">&nbsp;</td>
                                    <td width="55" height="14" nowrap="nowrap">&nbsp;</td>
                                    <td width="104" height="14">&nbsp;</td>
                                    <td width="207">&nbsp;</td>
                                    <td width="92">&nbsp;</td>
                                    <td height="14" colspan="10">&nbsp;</td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                          </tbody>
                        </table>
                        <?php if ($Num>0){ ?>
                        <table class="p9gray" cellspacing="0" cellpadding="0" width="100%"    border="0">
                          <tbody>
                            <tr>
                              <td valign="center" align="middle" background="images/<?php echo $INFO[IS]?>/03_content_backgr.png" height="23"><?php echo $Nav->pagenav()?></td>
                            </tr>
                          </tbody>
                        </table>
                        <?php } 
					  }
						?>
                      </div></td>
                  </tr>
                </tbody>
              </table></td>
            </tr>
          </tbody>
        </table></td>
      <td width="1%" background="images/<?php echo $INFO[IS]?>/right.gif" height="319">&nbsp;</td>
    </tr>
    <tr>
      <td width="1%"><img height="9" src="images/<?php echo $INFO[IS]?>/lb.gif" width="9" /></td>
      <td width="98%" background="images/<?php echo $INFO[IS]?>/bottom.gif"><img height="1" src="images/<?php echo $INFO[IS]?>/spacer.gif" width="1" /></td>
      <td width="1%"><img height="9" src="images/<?php echo $INFO[IS]?>/rb.gif"  width="9" /></td>
    </tr>
  </tbody>
</table>
<div align="center">
  <?php include_once "botto.php";?>
</div>
<div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>
