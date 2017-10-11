<?php
include_once "Check_Admin.php";
include_once "../language/".$INFO['IS']."/Desktop_Pack.php";

include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
include      "../language/".$INFO['IS']."/Visit_Pack.php";

$Nav      = new buildNav($DB,$objClass);
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
if (intval($_GET['year'])<=0){
	$_GET['year'] = date("Y",time());
}
if (intval($_GET['month'])<=0){
	$_GET['month'] = date("m",time());
}elseif($_GET['month']<10){
	$_GET['month'] = "0" . $_GET['month'];
}
$DayNum = $TimeClass->getMonthLastDay($_GET['month'],$_GET['year']); //获得当前月天数
$Sql = " select sum(totalprice) as totalprices ,order_day from `{$INFO[DBPrefix]}order_table` where order_year='".$_GET['year']."' and order_month='".$_GET['month']."' and  (pay_state=1 or pay_state=6 or pay_state=7) and order_state=4 ";
/*if ($_GET['payment']!=""){
	$Sql .= " and paymentname='" . $_GET['payment'] . "'"; 
}*/
$Sql .= " and saler='" . $_SESSION['Provider_thenum'] . "'"; 
$Sql .= " group by order_day  order by order_day asc ";
$Query = $DB->query($Sql);
$tmp_date = array();
$Num      = $DB->num_rows($Query);
while ($Result = $DB->fetch_array($Query)) {
	//echo $Result['totalprices'] . "|";
	$tmp_date[intval($Result['order_day']).'-'.intval($Result['order_day'])] = $Result['totalprices'];
}

$date = array();
for ($i = 1;$i<=$DayNum;$i++){
	if ($tmp_date == null) {
		$date[$_GET['month'].'-'.$i] = 0;
	}
	else {
		foreach ($tmp_date as $key => $value) {
			if ($i == $key) {
				$date[$_GET['month'].'-'.$i] = $value;
				break;
			}
			else {
				$date[$_GET['month'].'-'.$i] = 0;
			}
		}
	}
}
$date_value = "";
foreach ($date as $key => $value) {
	$date_value .= $key."day\\t".$value."\\n";
	$date_num += $value;
}
//print_r($tmp_date);
/*
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['year'] . "-" . $_GET['month'] . "-" . "1","-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['year'] . "-" . $_GET['month'] . "-" . getDays($_GET['month'], $_GET['year']),"-");
$endtimeunix  = $endtimeunix  + 24*60*60;
*/
$Sql = "select od.*,o.saler,o.createtime,o.order_serial,o.paymentname,u.true_name from `{$INFO[DBPrefix]}order_table` as o left join  `{$INFO[DBPrefix]}order_detail` od on o.order_id=od.order_id left join `{$INFO[DBPrefix]}user` as u on o.user_id=u.user_id where o.order_year=" . $_GET['year'] . " and o.order_month=" . $_GET['month'] . " and (od.detail_pay_state=1 ) and o.pay_state=1";
/*if ($_GET['payment']!=""){
	$Sql .= " and o.paymentname='" . $_GET['payment'] . "'"; 
}*/
$Sql .= " and o.saler='" . $_SESSION['Provider_thenum'] . "'"; 

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query    = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="javascript" src="../js/TitleI.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
        /*****************************************************
         * 滑鼠hover變顏色
         ******************************************************/
$(document).ready(function() {
$("#orderedlist tbody tr").hover(function() {
		$(this).addClass("blue");
	}, function() {
		$(this).removeClass("blue");
	});
});
</script>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?> 
<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.name.value)){
			form1.name.focus();
			alert('請輸入經銷商名稱');  //请选择供應商名稱			
			return;
		}

		
		if (chkblank(form1.password.value)){
			form1.password.focus();
			alert('請輸入登入密碼');  //请输入登入密碼
			return;
		}

			
		form1.submit();
	}

</SCRIPT>
  <FORM name=form1 action='' method=get>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">

<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=./images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="./images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=400 cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD vAlign=top align=right width="70%">
      <TABLE class=p9black cellSpacing=0 cellPadding=10 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="51%"></TD>
            </TR>
          </TBODY></TABLE>
      <TABLE width="90%" border=0 align="center" cellPadding=0 cellSpacing=0 class=p9black>
        <TBODY>
          <TR>
            <TD width="50%"><TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
                <TR>
                  <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                  <TD class=p12black noWrap><SPAN  class=p9orange>業績管理--&gt;業績查詢</SPAN></TD>
                  </TR>
                </TBODY>
            </TABLE></TD>
            </TR>
          <TR>
            <TD><TABLE class=9pv cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=left height=31 width=230>銷售期間：
                    <select name="year" id="year">
                      <?php for ($o=2007;$o<=2020;$o++){?>
                      <option value="<?php echo $o?>" <?php if($o==$_GET['year']){?>selected<?php }?>><?php echo $o?></option>
                      <?php }?>
                      </select>
                    年
                    <select name="month" id="month">
                      <?php for ($o=1;$o<=12;$o++){?>
                      <option value="<?php echo $o?>" <?php if($o==$_GET['month']){?>selected<?php }?>><?php echo $o?></option>
                      <?php }?>
                      </select>
                    月</TD>
                  <TD class=p9black vAlign=center height=31 ><input type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle"></TD>
                  
                </TR>
<!--                <TR>
                  <TD align=left height=31>付款方式：
                    <?php 
				$paysql = "select distinct paymentname from `{$INFO[DBPrefix]}order_table`";
				$payQuery = $DB->query($paysql);
				while($payResult= $DB->fetch_array($payQuery)){
				?>
                    <input value="<?php echo $payResult['paymentname'];?>" name="payment" type="radio" <?php if($_GET['payment']==$payResult['paymentname']){?>checked<?php }?>>
                    <?php echo $payResult['paymentname'];?>
                    <?php
				}
				?>
                    <input value="" name="payment" type="radio" <?php if($_GET['payment']==""){?>checked<?php }?>>
                    所有</TD>
                  <TD class=p9black vAlign=center height=31><input type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle"></TD>
                </TR>-->
              </TBODY>
            </TABLE></TD>
            <TD class=p9black align=right width=31% height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示 ?><?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=\"trans-input\" onchange=document.form1.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
            </TR>
          </TBODY>
        </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  		<TR>
            <TD height="30" colspan="3" align=center class=p9black>
            	<canvas id="market" height="250" width="850"></canvas>
            </TD>
		</TR>
		</FORM>
      </TABLE>
<!--        <TBODY>
          <TR>
            <TD height=300 align="center" vAlign=top bgColor=#ffffff><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" id="UrchinGraph0" align="middle" height="375" width="800">
              <param name="allowScriptAccess" value="never">
              <param name="movie" value="images/shopnc.swf">
              <param name="quality" value="high">
              <param name="bgcolor" value="#ffffff">
              <param name="wmode" value="transparent">
              <embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="375" width="800" flashvars="ntitle=<?php echo $Visit_Packs[Date];//日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[SalePrice];//销售金额?>&cnames=&datatype=20&rtitle=<?php echo $_GET['year']?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $_GET['month']?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $JsMenu[Sale];//销售统计?>&fsize=1&gtypes=line|bar&uloc= cn|$|0|2&total=<?php echo $date_num;?>&xdata=<?php echo $date_value?>"  >
              <param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[Date];//日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[SalePrice];//销售金额?>&cnames=&datatype=20&rtitle=<?php echo $_GET['year']?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $_GET['month']?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $JsMenu[Sale];//销售统计?>&fsize=1&gtypes=line|bar&uloc= cn|$|0|2&total=<?php echo $date_num;?>&xdata=<?php echo $date_value?>" />
            </object>-->
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD align="center" bgColor=#ffffff>
                    <TABLE class=listtable cellSpacing=0 cellPadding=0 width="90%" border=0 id="orderedlist">
                      <INPUT type=hidden name=act>
                      <INPUT type=hidden value=0  name=boxchecked>
                      <TBODY>
                        <TR align=middle>
                          <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Basic_Command['SNo_say'] ?></TD>
                          <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 訂單日期</TD>
                          <TD height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>訂購人</TD>
                          <TD width="203" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> 商品名稱</TD>
                          <TD width="90" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>商品編號</TD>
                          <TD width="73" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>銷售數量</TD>
                          <TD width="73" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>銷售金額</TD>
                          <TD width="62" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>業績</TD>
                          <TD width="62" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>經銷商</TD>
                        </TR>
                        <?php               
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                        <TR class=row0>
                          <TD height=26 align="left" noWrap><?php echo  $i+1;?></TD>
                          <TD height=26 align="left" noWrap><?php echo $Rs['order_serial']?>&nbsp;</TD>
                          <TD height=26 align=center nowrap><?php echo $Rs['true_name']?>&nbsp;</TD>
                          <TD height=26 align=center nowrap><?php echo $Rs['goodsname']?>&nbsp;</TD>
                          <TD align=center nowrap><?php echo $Rs['goodsname']?></TD>
                          <TD align=center nowrap><?php echo ($Rs['goodscount']-$Rs['backcount'])?></TD>
                          <TD align=center nowrap><?php echo ($Rs['goodscount']-$Rs['backcount'])*$Rs['price']?></TD>
                          <TD align=center nowrap><?php
						  if($Rs['packgid']==0){
                      $Query_s = $DB->query("select * from `{$INFO[DBPrefix]}saler` where login='" . trim($Rs['saler']) . "' limit 0,1");
	$Num_a   = $DB->num_rows($Query_s);
  
  	if ($Num_a>0){
		$Result_s= $DB->fetch_array($Query_s);
  		if (intval($Result_s['salerset'])>0){
			$salerset = intval($Result_s['salerset']);
		}else{
			$Query_s = $DB->query("select * from `{$INFO[DBPrefix]}salermoney` limit 0,1");
			$Num_s   = $DB->num_rows($Query_s);
			  
			  if ($Num_s>0){
			  	$Result_s= $DB->fetch_array($Query_s);
				$salerset           =  $Result_s['salerset'];
			  }	
		}
	}
	$salerset;
	if ($salerset > 0){
		echo round($Rs['goodscount']*$Rs['price']*($salerset/100),0);	
	}
	}else{
								echo 0;	
								}
					  ?>&nbsp;</TD>
                          <TD align=center nowrap><?php echo $Rs['saler']?></TD>
                        </TR>
                        <?php
					$i++;
					}
					?>
                      </TBODY>
                    </TABLE>
                  
                
              <?php if ($Num>0){ ?>
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="90%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav()?></TD>
                  </TR>
                  </TBODY>
                </TABLE></TD></TR>
                  </TBODY></TABLE>
              <?php } ?></TD>
            </TR>
          </TBODY>
      </TABLE></form></BODY></HTML>
<!--載入圖表工具-->
<script src="../js/ChartNew.js"></script>
<script>
	//銷售金額(長條圖)
	var marketData = {
		labels : <?php echo json_encode(array_keys($date)); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($date)); ?>
			}
		]
	}
	//顯示圖表
	new Chart(document.getElementById("market").getContext("2d")).Bar(marketData,{inGraphDataShow : true, annotateDisplay : true, yAxisUnit: "NT", annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3%>"});
</script>