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
$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y")));
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
if (intval($_GET['province'])>0){
	$Provider_Search = " and provider_id='" . intval($_GET['province']) . "'";
}

$Sql = " select  o.* from `{$INFO[DBPrefix]}order_table` o where  o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' ".$Provider_Search." and o.order_state=4";
$Sql      .= "  order by o.createtime desc";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Nums     = $Num<$limit ? $Num : $limit ;
	$Query    = $Nav->sql_result;
}
$DayNum = $TimeClass->getMonthLastDay($current_month,$current_year); //获得当前月天
$Sql_t = " select sum(discount_totalPrices) as totalprices ,order_day from `{$INFO[DBPrefix]}order_table` where order_year='".$current_year."' and order_month='".$current_month."' and order_state=4  group by order_day  order by order_day asc ";
$Query_t = $DB->query($Sql_t);
$tmp_date = array();
while ($Result_t = $DB->fetch_array($Query_t)) {
	$tmp_date[intval($Result_t['order_day'])] = $Result_t['totalprices'];
}
$date = array();
for ($i = 1;$i<=$DayNum;$i++){
	if ($tmp_date == null) {
		$date[$i] = 0;
	}
	else {
		foreach ($tmp_date as $key => $value) {
			if ($i == $key) {
				$date[$i] = $value;
				break;
			}
			else {
				$date[$i] = 0;
			}
		}
	}
}
$date_value = "";
foreach ($date as $key => $value) {
	$date_value .= $key."day\\t".$value."\\n";
	$date_num += $value;
}

$Sql_m = "select sum(discount_totalPrices) as totalprices ,order_month from `{$INFO[DBPrefix]}order_table` where  order_state=4 and order_year='".$current_year."'  group by order_month  order by order_month asc ";
$Query_m = $DB->query($Sql_m);
$tmp_m = array();
while ($Result_m = $DB->fetch_array($Query_m)) {
	$tmp_m[intval($Result_m['order_month'])] = $Result_m['totalprices'];
}
$month_array = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
$month_value = array();
for ($i = 1;$i<=12;$i++){
	if ($tmp_m == null) {
		$month_value[$i]['num'] = 0;
		$month_value[$i]['month'] = $month_array[$i-1];
	}
	else {
		foreach ($tmp_m as $key => $value) {
			if ($i == $key) {
				$month_value[$i]['num'] = $value;
				$month_value[$i]['month'] = $month_array[$i-1];
				break;
			}
			else {
				$month_value[$i]['num'] = 0;
				$month_value[$i]['month'] = $month_array[$i-1];
			}
		}
	}
}

$month_str = "";
foreach ($month_value as $value) {
	$month_str .= $value['month']."\\t".$value['num']."\\n";
	$month_num += $value['num'];
}
?>
<?php  include $Js_Top ;  ?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK id=css href="../css/calendar.css" type='text/css' rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[TjFx];//统计分析?>--&gt;<?php echo $JsMenu[Sale];//销售统计?></title>
<style type="text/css">
<!--
.STYLE1 {color: #0000FF}
-->
</style>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
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
//-->
</SCRIPT>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">

<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
      <?php  include_once "Order_state.php";?>
  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD></TR>
  </TBODY>
  </TABLE>



<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>

  
   <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif"   width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[TjFx];//统计分析?>--&gt;<?php echo $JsMenu[Sale];//销售统计?></SPAN></TD>
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
        <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD vAlign=top bgColor=#ffffff height=300>
                  <br>
                  <TABLE  width="96%" border=0 align=center cellPadding=2 cellSpacing=0 bgColor=#f7f7f7 class=allborder>
                    <TBODY>
                      <TR>
                        <TD valign="top" noWrap>
                            <FORM method=get action="" name="form2">
							 <input type="hidden" name="action" value="txtsearch">
                             <input type="hidden" name="province" value="<?php echo $_GET['province'];?>">
							 <table width="98%"  border="0" align="center" class="p9black">
                                <tr>
                                  <td width="85%" align="left"></td>
                                </tr>
                                <tr>
                                  <td align="left">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <?php
                                  $provider_Sql      = "select * from `{$INFO[DBPrefix]}provider` ".$Where." order by provider_idate  ";
								  $provider_Query    = $DB->query($provider_Sql);
								  $row = 6;
								  $i = 0;
								  while($provider_Rs=$DB->fetch_array($provider_Query)){
									  if(($i+1)%$row==1){
								  ?>
                                    <tr>
                                  <?php
									  }
								  ?>
                                      <td width="17%" ><a href="admin_statistic_provider2.php?province=<?php echo $provider_Rs['provider_id']?>&begtime=<?php echo $begtime?>&endtime=<?php echo $endtime?>"><font style="font-size:12px" <?php if($_GET['province']==$provider_Rs['provider_id']) echo "color='red'";?>><?php echo $provider_Rs['provider_name'];?></font></a></td>
                                   <?php
                                   	if(($i+1)%$row==0){
								   ?>
                                    </tr>
                                  <?php
									}
								  	$i++;
								  }
								  ?>
                                  </table></td>
                                  </tr>
                                <tr>
                                  <td><span class="STYLE1"><strong>FROM</strong><INPUT   id=begtime size=10 value=<?php echo $begtime?>    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />
                                    <strong>To</strong>
                                    <INPUT    id=endtime size=10 value=<?php echo $endtime?>      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />
                                    &nbsp;
                                    <input type="submit" name="button" id="button" value="查詢">
                                    <input type="button" name="button2" id="button2" value="下載" onClick="location.href='admin_statistic_provider_excel.php?begtime='+document.all.begtime.value+'&endtime='+document.all.endtime.value+'&province='+document.all.province.value;"></td>
                                </tr>
                                <tr>
                                  <td align="center">&nbsp;</td>
                                </tr>
                              </table>
                            </FORM>
</TD>
                      </TR>
                    </TBODY>
                  </TABLE>
                  <br>
                  <a href="#" onClick="document.all.showtable.style.display='block';document.all.showmap.style.display='none';">列表</a> | <a href="#" onClick="document.all.showtable.style.display='none';document.all.showmap.style.display='block';">圖表</a> | <a href="admin_statistic_provider.php">供應商對帳</a><br>
                  <div align="center" style="display:none" id="showmap">
                  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" id="UrchinGraph0" align="middle" height="375" width="800">
									<param name="allowScriptAccess" value="never">
									<param name="movie" value="images/shopnc.swf">
									<param name="quality" value="high">
									<param name="bgcolor" value="#ffffff">
									<param name="wmode" value="transparent">
									<embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="375" width="800" flashvars="ntitle=<?php echo $Visit_Packs[Date];//日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[SalePrice];//销售金额?>&cnames=&datatype=20&rtitle=<?php echo $current_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $current_month?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $JsMenu[Sale];//销售统计?>&fsize=1&gtypes=bar|line&uloc= cn|$|0|2&total=<?php echo $date_num;?>&xdata=<?php echo $date_value?>"  >
									<param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[Date];//日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[SalePrice];//销售金额?>&cnames=&datatype=20&rtitle=<?php echo $current_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $current_month?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $JsMenu[Sale];//销售统计?>&fsize=1&gtypes=bar|line&uloc= cn|$|0|2&total=<?php echo $date_num;?>&xdata=<?php echo $date_value?>" />
								</object>
                                <br>
                                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle" height="375" width="800">
									<param name="allowScriptAccess" value="never">
									<param name="movie" value="images/shopnc.swf">
									<param name="quality" value="high">
									<param name="bgcolor" value="#ffffff">
									<param name="wmode" value="transparent">
									<embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="375" width="800" flashvars="ntitle=<?php echo $Visit_Packs[Date];//日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[SalePrice];//销售金额?>&cnames=&datatype=20&rtitle=<?php echo $current_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $JsMenu[Sale];//销售统计?>&fsize=1&gtypes=bar|line&uloc= cn|$|0|2&total=<?php echo $month_num;?>&xdata=<?php echo $month_str?>"  >
									<param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[Date];//日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[SalePrice];//销售金额?>&cnames=&datatype=20&rtitle=<?php echo $current_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $JsMenu[Sale];//销售统计?>&fsize=1&gtypes=bar|line&uloc= cn|$|0|2&total=<?php echo $month_num;?>&xdata=<?php echo $month_str?>" />
								</object>
                                </div>
                                <div style="display:block" id="showtable">
                  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                    <TBODY>
                      <TR>
                        <TD bgColor=#ffffff><TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                          <FORM name=adminForm action="" method=post>
                            <INPUT type=hidden name=act>
                            <INPUT type=hidden value=0  name=boxchecked>
                            <TBODY>
                              <TR align=middle>
                                <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>訂單編號</TD>
                                <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>下單日期</TD>
                                <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>總成本</TD>
                                <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>結算商品總金額</TD>
                                <TD width="114" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>運費</TD>
                                <TD width="93" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>結算紅利</TD>
                                <TD width="177" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>訂單狀態</TD>
                              </TR>
                              <?php               
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                              <TR class=row0>
                                <TD height=26 align="left" noWrap><A href="javascript:void(0);" onMouseOver="MM_showHideLayers('showdetail<?php echo $Rs['order_id']?>','','show')" onMouseOut="MM_showHideLayers('showdetail<?php echo $Rs['order_id']?>','','hide')"> <?php echo $Rs['order_serial']?>&nbsp; </A>
                                  <div style="VISIBILITY: hidden;POSITION: absolute; HEIGHT: 67px"" id="showdetail<?php echo $Rs['order_id']?>">
                                    <table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFCC" width="600">
                                      <tr>
                                        <Td>商品名稱</Td>
                                        <Td>價格</Td>
                                        <Td>成本</Td>
                                        <Td>數量</Td>
                                        <Td>小計</Td>
                                        <Td>狀態</Td>
                                      </tr>
                                      <?php
                                $Sql_detail = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . $Rs['order_id'] . "'";
								$Query_detail    = $DB->query($Sql_detail);
								$costt = 0;
								while ($Rs_detail=$DB->fetch_array($Query_detail)) {
								?>
                                      <tr>
                                        <td bgcolor="#FFFFCC"><?php
                                echo $Rs_detail['goodsname'];
								?></td>
                                        <td bgcolor="#FFFFCC"><?php
								if ($Rs_detail['memberorprice']==1){
									$buyprice = intval($Rs_detail[price]);
									$xiaoji = intval($Rs_detail[price])*$Rs_detail['goodscount'];
								}else{
									$buyprice = intval($Rs_detail[memberprice]) . "+" . intval($Rs_detail[combipoint]) . "積分";
									$xiaoji = intval($Rs_detail[memberprice])*$Rs_detail['goodscount'] . "+" . intval($Rs_detail[combipoint])*$Rs_detail['goodscount'] . "積分";
								}
								if ($Rs_detail['ifbonus']==1){
									$buyprice = intval($Rs_detail[odbonuspoint]) . "積分";
									$xiaoji = intval($Rs_detail[odbonuspoint])*$Rs_detail['goodscount'] . "積分";
								}
								echo $buyprice;
								?></td>
                                        <Td><?php
                                echo $Rs_detail['cost'] . $Rs_detail['salecontent'];
								?></Td>
                                        <Td><?php
                                echo $Rs_detail['goodscount'];
								?></Td>
                                        <td bgcolor="#FFFFCC"><?php echo $xiaoji;?></td>
                                        <Td><?php echo $orderClass->getOrderState($Rs_detail['detail_order_state'],1)?>,<?php echo $orderClass->getOrderState(intval($Rs_detail['detail_pay_state']),2)  ?>,<?php echo $orderClass->getOrderState(intval($Rs_detail['detail_transport_state']),3)  ?></Td>
                                      </tr>
                                      <?php
									  $costt += $Rs_detail['cost'];
								}
								?>
                                    </table>
                                  </div></TD>
                                <TD height=26 align="left" noWrap><?php echo date("Y-m-d",$Rs['createtime'])?></TD>
                                <TD align=center nowrap><?php echo $costt;?></TD>
                                <TD height=26 align=center nowrap><?php echo $Rs['discount_totalPrices']?>&nbsp;</TD>
                                <TD align=center nowrap><?php echo $Rs['transport_price']?></TD>
                                <TD height=26 align=center nowrap><?php echo $Rs['bonuspoint']+$Rs['totalbonuspoint']?>&nbsp;</TD>
                                <TD height=26 align=center nowrap><?php echo  $orderClass->getOrderState($Rs['order_state'],1)?>,<?php echo $orderClass->getOrderState(intval($Rs['pay_state']),2)  ?>,<?php echo $orderClass->getOrderState(intval($Rs['transport_state']),3) ?>&nbsp;</TD>
                              </TR>
                              <?php
					$i++;
					}
					?>
                              <TR>
                                <TD width=123 height=14 nowrap>&nbsp;</TD>
                                <TD width=113 height=14>&nbsp;</TD>
                                <TD width=328>&nbsp;</TD>
                                <TD width=133>&nbsp;</TD>
                                <TD width=133 height=14>&nbsp;</TD>
                                <TD height=14 colspan="3">&nbsp;</TD>
                              </TR>
                            </FORM>
                        </TABLE></TD>
                      </TR>
                    </TABLE>
                  <?php if ($Num>0){ ?>
                  <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                    <TBODY>
                      <TR>
                        <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav()?></TD>
                      </TR>
                    </TABLE>
                  <?php } ?></div></TD></TR></TBODY></TABLE>
					
					</TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
