<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
$objClass = "9pv";
include      "../language/".$INFO['IS']."/Visit_Pack.php";

$Nav      = new buildNav($DB,$objClass);
include_once "Time.class.php";
$TimeClass = new TimeClass;
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";




if ($_GET['Action']=="Search"){
	$S_year        = $_GET['S_year'];
	$S_month       = $_GET['S_month']>9 ? $_GET['S_month'] : "0".$_GET['S_month'] ;
	$S_day       = $_GET['S_day']>9 ? $_GET['S_day'] : "0".$_GET['S_day'] ;
	$E_year        = $_GET['E_year'];
	$E_month       = $_GET['E_month']>9 ? $_GET['E_month'] : "0".$_GET['E_month'] ;
	$E_day       = $_GET['E_day']>9 ? $_GET['E_day'] : "0".$_GET['E_day'] ;
}else{
	$S_year =  date("Y",time()-30*24*60*60);
	$S_month = date("m",time()-30*24*60*60);
	$S_day = date("d",time()-30*24*60*60);
	$E_year        = date("Y",time());
	$E_month       = date("m",time());
	$E_day       = date("d",time());
}
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($S_year."-".$S_month."-".$S_day,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($E_year."-".$E_month."-".$E_day,"-")+24*60*60-1;

$market;
$startdate = new DateTime(date("Y-m-d",$begtimeunix));
$enddate = new DateTime(date("Y-m-d",$endtimeunix));/**/

$Data = "";
$Group = "";

$DayNum = ($endtimeunix-$begtimeunix)/(60*60*24); //获得当前月天数

/*if ($_GET['payment']!=""){
	$Sql .= " and paymentname='" . $_GET['payment'] . "'"; 
}*/

if(($endtimeunix-$begtimeunix)/(60*60*24*365) >= 1){ //跨年
	while($startdate->format("y")<=$enddate->format("y")){
		$market[$startdate->format("Y")] = 0;
		$startdate->modify("+1 year");
	}
	$Data .= "FROM_UNIXTIME(`createtime`,'%y')";
	$Group .= " GROUP BY FROM_UNIXTIME(`createtime`,'%y')";
}
else if(($endtimeunix-$begtimeunix)/(60*60*24*30) >= 1){ //跨月
	$startdate->setDate($startdate->format("y"),$startdate->format("m"),1);
	while($startdate->format("y-m")<=$enddate->format("y-m")){		
		$market[$startdate->format("y-m")] = 0;
		$startdate->modify("+1 month");
	}
	$Data .= "FROM_UNIXTIME(`createtime`,'%y-%m')";
	$Group .= " GROUP BY FROM_UNIXTIME(`createtime`,'%y-%m')";
}
else{ //月內	
	while($startdate->format("y-m-d")<=$enddate->format("y-m-d")){
		$market[$startdate->format("m-d")] = 0;
		$startdate->modify("+1 day");
	}
	$Data .= "FROM_UNIXTIME(`createtime`,'%m-%d')";
	$Group .= " GROUP BY FROM_UNIXTIME(`createtime`,'%y-%m-%d')";
}

$Sql = "select ".$Data." `createtime` ,sum(totalprice) as totalprices ,order_day from `{$INFO[DBPrefix]}order_table` where createtime>='$begtimeunix' and createtime<='$endtimeunix'";

if ($_GET['saler']!=""){
	$Sql .= " and saler='" . $_GET['saler'] . "'"; 
}

if( isset($_GET['type']) ){
  if( $_GET['type'] == 0 )
    $Sql .= " and order_state=4";
  else if( $_GET['type'] == 1 )
    $Sql .= " and pay_state=1 and transport_state=1";
}
else{
  $Sql .= " and order_state=4";
}

$Sql .= $Group;
//echo $Sql;
//$Sql .= " group by order_day  order by order_day asc ";
$Query = $DB->query($Sql);
$tmp_date = array();
$Num      = $DB->num_rows($Query);
while ($Result = $DB->fetch_array($Query)) {
	$market[$Result['createtime']] = $Result['totalprices'];
}
//print_r($tmp_date);
/*$date = array();

for ($i = 1;$i<=$DayNum;$i++){
	if ($tmp_date == null) {
		$date[$i] = 0;
	}
	else {
		$j = 1;
		foreach ($tmp_date as $key => $value) {
			if ($i == $j) {
				$date[$i] = $value;
				break;
			}
			else {
				$date[$i] = 0;
			}
			$j++;
		}
	}
}



$date_value = "";
foreach ($date as $key => $value) {
	$date_value .= $key."day\\t".$value."\\n";
	$date_num += $value;
}*/
//print_r($date);
/*
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['year'] . "-" . $_GET['month'] . "-" . "1","-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['year'] . "-" . $_GET['month'] . "-" . getDays($_GET['month'], $_GET['year']),"-");
$endtimeunix  = $endtimeunix  + 24*60*60;
*/
$Sql = "select od.*,o.saler,o.createtime,o.order_serial,o.paymentname,g.bn,s.name,u.true_name from `{$INFO[DBPrefix]}order_table` as o left join  `{$INFO[DBPrefix]}order_detail` od on o.order_id=od.order_id left join `{$INFO[DBPrefix]}goods` as g on g.gid=od.gid left join `{$INFO[DBPrefix]}saler` as s on s.login=o.saler left join `{$INFO[DBPrefix]}user` as u on o.user_id=u.user_id where o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix'";
/*if ($_GET['payment']!=""){
	$Sql .= " and o.paymentname='" . $_GET['payment'] . "'"; 
}*/
if ($_GET['saler']!=""){
	$Sql .= " and o.saler='" . $_GET['saler'] . "'"; 
}

if( isset($_GET['type']) ){
  if( $_GET['type'] == 0 )
    $Sql .= " and od.detail_order_state=4";
  else if( $_GET['type'] == 1 )
    $Sql .= " and od.detail_pay_state=1 and od.detail_transport_state=1";
}
else{
  $Sql .= " and od.detail_order_state=4";
}

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>經銷商管理--&gt;經銷商業績查詢</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Provider_Man]?>--&gt;業績查詢  </SPAN>
                      </TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=optForm method=get action="">        
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD width="69%" height=31 align=right>
              <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class=9pv>
                <TBODY>
                  <TR>
                    <TD width="68%" height=31 align=left>經銷商：
                      <select name="saler" id="saler" onchange="document.all.optForm.submit();">
                        <option value="" >請選擇</option>
                        <?php 
				$paysql = "select * from `{$INFO[DBPrefix]}saler` where name <>''";
				$payQuery = $DB->query($paysql);
				while($payResult= $DB->fetch_array($payQuery)){
				?>
                        <option value="<?php echo $payResult['login'];?>" <?php if($payResult['login']==$_GET['saler']){?>selected<?php }?>><?php echo $payResult['name'];?></option>
                        <?php
				}
				?>
                        </select></TD>
                    <TD width="32%" height=31 vAlign=center class=p9black>&nbsp;</TD>
                    </TR>

                  <TR>
                    <TD height=31 colspan="2" align=left>銷售期間：
                      <span class="STYLE1"><strong>FROM</strong></span>&nbsp;&nbsp;&nbsp;
                      <SELECT name='S_year'>
                        <?php for ($i=2002;$i<=intval(date("Y",time()));$i++) { ?>
                        <OPTION value='<?php echo  $i; ?>' <?php if ($i==$S_year){  echo "  selected ";} ?>> <?php echo $i?> </OPTION>
                        <?php } ?>
                        </SELECT>
                      &nbsp; <?php echo $Visit_Packs[Year];//年?> &nbsp;
                      <SELECT name='S_month'>
                        <?php for ($j=1;$j<=12;$j++) { ?>
                        <OPTION value='<?php echo $j;?>' <?php if ($j==$S_month){  echo "  selected ";} ?>> <?php echo $j;?> </OPTION>
                        <?php } ?>
                        </SELECT>
                      &nbsp; <?php echo $Visit_Packs[Month];//月?>
                      <SELECT name='S_day'>
                        <?php for ($j=1;$j<=31;$j++) { ?>
                        <OPTION value='<?php echo $j;?>' <?php if ($j==$S_day){  echo "  selected ";} ?>> <?php echo $j;?> </OPTION>
                        <?php } ?>
                        </SELECT>
                      &nbsp;日
                      &nbsp;&nbsp;&nbsp;&nbsp;<span class="STYLE1"><strong>TO</strong></span>&nbsp;&nbsp; &nbsp;
                      <SELECT name='E_year'>
                        <?php for ($i=2002;$i<=intval(date("Y",time()));$i++) { ?>
                        <OPTION value='<?php echo  $i; ?>' <?php if ($i==$E_year){  echo "  selected ";} ?>> <?php echo $i?> </OPTION>
                        <?php } ?>
                        </SELECT>
                      &nbsp; <?php echo $Visit_Packs[Year];//年?> &nbsp;
                      <SELECT name='E_month'>
                        <?php for ($j=1;$j<=12;$j++) { ?>
                        <OPTION value='<?php echo $j;?>' <?php if ($j==$E_month){  echo "  selected ";} ?>> <?php echo $j;?> </OPTION>
                        <?php } ?>
                        </SELECT>
                      &nbsp; <?php echo $Visit_Packs[Month];//月?>
                      <SELECT name='E_day'>
                        <?php for ($j=1;$j<=31;$j++) { ?>
                        <OPTION value='<?php echo $j;?>' <?php if ($j==$E_day){  echo "  selected ";} ?>> <?php echo $j;?> </OPTION>
                        <?php } ?>
                        </SELECT>
                      &nbsp;日
                      &nbsp;</TD>
                    </TR>
            <TR>
             <TD height="30" align=left class=p9black>商品狀態：
              <input type="radio" name="type" value="0" checked="checked" />完成交易
              <input type="radio" name="type" value="1" <?php if($_GET['type'] == 1) echo "checked";?>/>已出貨已付款
             </TD>
           </TR>
                  <TR>
<!--                    <TD align=left height=31>付款方式：
                      <?php 
				$paysql = "select distinct paymentname from `{$INFO[DBPrefix]}order_table`";
				$payQuery = $DB->query($paysql);
				while($payResult= $DB->fetch_array($payQuery)){
				?>
                      <input value="<?php echo $payResult['paymentname'];?>" name="payment" type="radio" <?php if($_GET['payment']==$payResult['paymentname']){?>checked<?php }?>><?php echo $payResult['paymentname'];?>
                      <?php
				}
				?><input value="" name="payment" type="radio" <?php if($_GET['payment']==""){?>checked<?php }?>>所有</TD>-->
                    <TD class=p9black vAlign=center height=31><a href="javascript:void(0);" onClick="document.optForm.action='admin_saler_search.php';document.all.optForm.submit();"><img src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle"></a>
                      <input type="submit" name="button2" id="button2" value="提交" style="display:none">
                      <input type="button" name="button" id="button" value="匯出EXCEL" onclick="javascript:location.href='admin_saler_excel.php?<?php echo $_SERVER['QUERY_STRING']?>'" <?php if(!$_SERVER['QUERY_STRING']){echo "disabled";}?>/>
                      </TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
            <TD class=p9black align=right width=31% height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示 ?><?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
            </TR>
          <TR>
<!--            <TD align=center colSpan=2 height=31><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" id="UrchinGraph0" align="middle" height="375" width="800">
              <param name="allowScriptAccess" value="never">
              <param name="movie" value="images/shopnc.swf">
              <param name="quality" value="high">
              <param name="bgcolor" value="#ffffff">
              <param name="wmode" value="transparent">
              <embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="375" width="800" flashvars="ntitle=<?php echo $Visit_Packs[Date];//日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[SalePrice];//销售金额?>&cnames=&datatype=20&rtitle=<?php echo $S_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $S_month?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $S_day;?>日 TO <?php echo $E_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $E_month?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $E_day;?>日 <?php echo $JsMenu[Sale];//销售统计?>&fsize=1&gtypes=line|bar&uloc= cn|$|0|2&total=<?php echo $date_num;?>&xdata=<?php echo $date_value?>"  >
                <param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[Date];//日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[SalePrice];//销售金额?>&cnames=&datatype=20&rtitle=<?php echo $S_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $S_month?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $S_day;?>日 TO <?php echo $E_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $E_month?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $E_day;?>日 <?php echo $JsMenu[Sale];//销售统计?>&fsize=1&gtypes=line|bar&uloc= cn|$|0|2&total=<?php echo $date_num;?>&xdata=<?php echo $date_value?>" />
              </object></TD>-->
              	<TD height="30" colspan="3" align=center class=p9black>
            		<canvas id="market" height="250" width="850"></canvas>
            	</TD>
            </TR>
          </FORM>
        </TABLE>
      
      
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="allborder">
        <TBODY>
          <TR>
            <TD>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD align="center" bgColor=#ffffff>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD width="3%"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <?php echo $Basic_Command['SNo_say'] ?></TD>
                              <TD width="7%"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
                                訂單日期</TD>
                              <TD width="7%" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>訂購人</TD>
                              <TD width="27%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                商品名稱</TD>
                              <TD width="7%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>商品編號</TD>
                              <TD width="5%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>單位</TD>
                              <TD width="7%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>市價</TD>
                              <TD width="7%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>網購價</TD>
                              <TD width="7%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>銷售數量</TD>
                              <TD width="7%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>銷售金額</TD>
                              <TD width="8%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>業績</TD>
                              <TD width="8%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>經銷商</TD>
                              </TR>
                            <?php               
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                            <TR class=row0>
                              <TD height=26 align="center" noWrap><?php echo  $i+1;?></TD>
                              <TD height=26 align="left" noWrap>
                                <?php echo $Rs['order_serial']?>&nbsp;</TD>
                              <TD height=26 align=center nowrap><?php echo $Rs['true_name']?>&nbsp;</TD>
                              <TD height=26 align=left nowrap><?php echo $Rs['goodsname']?>&nbsp;</TD>
                              <TD align=center nowrap><?php echo $Rs['bn']?></TD>
                              <TD align=center nowrap><?php echo $Rs['unit']?></TD>
                              <TD align=right nowrap><?php echo $Rs['market_price']?></TD>
                              <TD align=right nowrap><?php echo $Rs['price']?></TD>
                              <TD align=center nowrap><?php echo $Rs['goodscount']-$Rs['backcount']?></TD>
                              <TD align=right nowrap><?php echo ($Rs['goodscount']-$Rs['backcount'])*$Rs['price']?></TD>
                              <TD align=right nowrap>
                                <?php
                      $Query_s = $DB->query("select * from `{$INFO[DBPrefix]}saler` where login='" . trim($Rs['saler']) . "' limit 0,1");
	$Num_s   = $DB->num_rows($Query_s);
  
  	if ($Num_s>0){
		$Result= $DB->fetch_array($Query_s);
  		if (intval($Result['salerset'])>0){
			$salerset = intval($Result['salerset']);
		}else{
			$Query_s = $DB->query("select * from `{$INFO[DBPrefix]}salermoney` limit 0,1");
			$Num_s   = $DB->num_rows($Query_s);
			  
			  if ($Num>0){
			  	$Result= $DB->fetch_array($Query_s);
				$salerset           =  $Result['salerset'];
			  }	
		}
	}
	$salerset;
	if ($salerset > 0){
		echo round(($Rs['goodscount']-$Rs['backcount'])*$Rs['price']*($salerset/100),0);	
	}
					  ?>                      </TD>
                              <TD align=center nowrap><?php echo $Rs['name']?></TD>
                              </TR>
                            <?php
					$i++;
					}
					?>
							</TBODY>
                            </FORM>                            
                        </TABLE>
                      </TD>
                    </TR>
                    </TBODY>
                  </TABLE>
              <?php if ($Num>0){ ?>
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                      </TD>
                    </TR>
                    </TBODY>
                  </TABLE>
              <?php } ?>  
              </TD>
            </TR>
            </TBODY>
          </TABLE>
    </TD>
    </TR>
</TBODY></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
<!--載入圖表工具-->
<script src="../js/ChartNew.js"></script>
<script>
	//銷售金額(長條圖)
	var marketData = {
		labels : <?php echo json_encode(array_keys($market)); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($market)); ?>
			}
		]
	}
	//顯示圖表
	new Chart(document.getElementById("market").getContext("2d")).Bar(marketData,{inGraphDataShow : true, annotateDisplay : true, yAxisUnit: "NT", annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3%>"});
</script>
