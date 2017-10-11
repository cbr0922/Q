<?php
include_once "Check_Admin.php";
include_once Classes . "/GD_Drive.php";
include_once Classes . "/Time.class.php";
include_once Classes . "/SaleMap.class.php";

include_once Classes . "/Time.class.php";
include_once Classes . "/orderClass.php";
$TimeClass = new TimeClass;
$orderClass = new orderClass;
$year  = $_GET['year']!="" ? $_GET['year'] : date("Y",time());
$month  = $_GET['month']!="" ? $_GET['month'] : date("m",time());
$begtime  =date("Y-m-d H:i:s",mktime(0, 0 , 0,$month-1,26,$year));
$endtime  = date("Y-m-d H:i:s",mktime(0, 0 , 0,$month,25,$year));

$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
$end_month = date("m",mktime(0, 0 , 0,date("m",$endtimeunix)+1,26,date("Y",$endtimeunix)));
$end_year = date("Y",mktime(0, 0 , 0,date("m",$endtimeunix)+1,26,date("Y",$endtimeunix)));														 
$over_month = date("m",mktime(0, 0 , 0,date("m",$endtimeunix),26,date("Y",$endtimeunix)));			
$over_year = date("Y",mktime(0, 0 , 0,date("m",$endtimeunix),26,date("Y",$endtimeunix)));			
function numToCny($num){      
    $capUnit=array('萬','億','萬','元','');      
    $capDigit=array(2=>array('角','分',''), 4=>array('仟','佰','拾',''));      
    $capNum=array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖');      
    if ((strpos(strval($num),'.')>16)||(!is_numeric($num)))      
        return '';      
    $num = sprintf("%019.2f",$num);      
    $CurChr=array('','');      
    for ($i=0,$ret='',$j=0;$i<5;$i++,$j=$i*4+floor($i/4)){      
        $nodeNum=substr($num,$j,4);      
        for($k=0,$subret='',$len=strlen($nodeNum);(($k<$len)&&(intval(substr($nodeNum,$k))!=0));$k++){      
            $CurChr[$k%2] = $capNum[$nodeNum{$k}].(($nodeNum{$k}==0)?'':$capDigit[$len][$k]);      
            if (!(($CurChr[0]==$CurChr[1]) && ($CurChr[$k%2]==$capNum[0])))      
                if(!(($CurChr[$k%2] == $capNum[0]) && ($subret=='') && ($ret=='')))      
                    $subret .= $CurChr[$k%2];      
        }      
        $subChr = $subret.(($subret=='')?'':$capUnit[$i]);      
        if(!(($subChr == $capNum[0]) && ($ret=='')))      
            $ret .= $subChr;      
    }      
    $ret=($ret=="")?$capNum[0].$capUnit[3]:$ret;          
    return $ret;      
}
if (intval($_GET['province'])>0 ){
	$subSql = " and od.provider_id='" . intval($_GET['province']) . "'";
}
if (intval($_GET['provider_id'])>0){
	$subSql = " and od.provider_id='" . intval($_GET['provider_id']) . "'";
}
if (intval($_GET['iftogether'])>0){
	$subSql .= " and ot.deliveryid='" . intval($_GET['iftogether']) . "'";
}
if(intval($_GET['provider_id'])==114)
	$cost = "cast(od.goods_cost as DECIMAL )";
else
	$cost = "od.cost*od.goodscount";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>請款申請單</title>
</head>

<body>
<table width="688" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" align="center"><strong><?php echo $month;?>月份 <?php echo $_GET['iftogether']==23?"門市取貨":"宅配";?>請款單</strong></td>
  </tr>
  <?php
  if (intval($_GET['provider_id'])>0){
  $Provider_Search = " and provider_id='" . intval($_GET['provider_id']) . "'";
}
$Sql_p      = "select * from `{$INFO[DBPrefix]}provider` where 1=1 ".$Provider_Search." order by providerno desc  ";
$Query_p    = $DB->query($Sql_p);
$Rs_p=$DB->fetch_array($Query_p);
  ?>
  <tr>
    <td align="left">公司名稱：<?php echo $Rs_p['providerno'] . $Rs_p['provider_name'];?></td>
    <td align="right">對帳區間：<?php echo $current_year . "/" . $current_month . "/26~" . $over_year . "/" . $over_month . "/25";?></td>
  </tr>
  <tr>
    <td colspan="2"><table width="900" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td width="561"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center">科目</td>
            <td align="center">金額</td>
          </tr>
          <tr>
            <td>代收金額(+)</td>
            <td align="center">
            <?php
			//$Sql = "select " . $cost . " as sumcost,ot.*,od.* from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0) and oa.order_id=od.order_id  inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' ".$subSql." group by od.order_detail_id order by ot.order_id";
								$Sql = "select " . $cost . " as sumcost,ot.* from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0 )and oa.order_id=od.order_id inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' ".$subSql."  group by od.order_detail_id order by ot.order_id";

		    $curorder = "";
		    $total = 0;
			 $yunfei = 0;
			$t_Query    = $DB->query($Sql);
			$Num   = $DB->num_rows($t_Query);
			while($Rs_d=$DB->fetch_array($t_Query)){
				$total+= round($Rs_d['sumcost']*1.05,0);
				//echo round($Rs_d['sumcost']*1.05,0) . "|";
							if($curorder!=$Rs_d['order_serial']){
									//echo round($transport_price*1.05,0) . "|";
								$yunfei += round($transport_price*1.05,0);
							}
							$curorder = $Rs_d['order_serial'];
							
							$transport_price = $Rs_d['transport_price'];
			}
		       $yunfei += round($transport_price*1.05,0);
			 $total+= $yunfei;
			echo $total1=$total;
			?>
            </td>
          </tr>
          <tr>
            <td>代退金額(-)</td>
            <td align="center">
			<?php
			$total = 0;
			$yunfei = 0;
			$Sql = "select " . $cost . " as sumcost,ot.*,od.* from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0 )and oa.order_id=od.order_id inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and  (oa.state_value=20) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' ".$subSql." group by od.order_detail_id order by ot.order_id";
		  $curorder = "";
		  
			$t_Query    = $DB->query($Sql);
			$Num   = $DB->num_rows($t_Query);
			$transport_price = 0;
			while($Rs_d=$DB->fetch_array($t_Query)){
				//echo round($Rs_d['sumcost']*1.05,0) . "|";
				$total+= round($Rs_d['sumcost']*1.05,0);
							if($curorder!=$Rs_d['order_serial']){
							//	echo round($transport_price*1.05,0) . "|";
								//$yunfei +=round(($cur_store_bundle_count*10 + $cur_store_single_count*16 + $cur_house_bundle_count*10 + $cur_house_single_count*16-$cur_store_return_bundle_count*5-$cur_store_return_single_count*8-$cur_house_return_bundle_count*5-$cur_house_return_single_count*8)*1.05,0);
							
									$yunfei += round($transport_price*1.05,0);
							}
							$curorder = $Rs_d['order_serial'];
							$curdeliveryid = $Rs_d['deliveryid'];
							$transport_price = $Rs_d['transport_price'];
							
			}
			//$yunfei +=round(($cur_store_bundle_count*10 + $cur_store_single_count*16 + $cur_house_bundle_count*10 + $cur_house_single_count*16-$cur_store_return_bundle_count*5-$cur_store_return_single_count*8-$cur_house_return_bundle_count*5-$cur_house_return_single_count*8)*1.05,0);
			
						$yunfei += round($transport_price*1.05,0);
			$total+= $yunfei;
			$total2 = $total;
			echo "-" . $total2;
			?></td>
          </tr>
          
          <tr>
            <td colspan="2"><strong>請確認系統已鍵入發票資料，以便完成請款作業</strong></td>
            </tr>
          <tr>
            <td><strong>發票銷售金額</strong></td>
            <td align="center"><?php echo $total4 = round(($total1-$total2)/1.05,0);?></td>
          </tr>
          <tr>
            <td><strong>發票營業稅</strong></td>
            <td align="center"><?php echo $total5 = round($total4*0.05,0);?></td>
          </tr>
          <tr>
            <td><strong>發票總計</strong></td>
            <td align="center"><?php echo $total5+$total4?></td>
          </tr>
        </table></td>
        <td width="331"><p>發票抬頭 <br />
          <?php echo $Rs_p['provider_name'];?> </p>
          <p>統一編號 <br />
            <?php echo $Rs_p['invoice_num'];?></p>
          <p>開立發票日期 <br />
            <?php echo $current_year . "/" . $current_month . "/26~" . $over_year . "/" . $over_month . "/25";?>          </p>
          <p>注意：系統未鍵入正確之發票資料則無法完成請款</p></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"><table width="900" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><p>付款戶名：<?php echo $Rs_p['bankname'];?> <br />
          統一編號：<?php echo $Rs_p['invoice_num'];?><br />
          付款銀行：<?php echo $Rs_p['bank'];?> <br />
          分行別：<?php echo $Rs_p['bankno'];?> <br />
          付款帳號：<?php echo $Rs_p['bankuser'];?><br />
          聯絡人：<?php echo $Rs_p['account_lxr'];?> </p>
聯絡人電話：<?php echo $Rs_p['account_tel'];?></td>
        <td align="right"><p align="right">請蓋發票章 或 公司大小章    </p>
          <p align="right">&nbsp;</p>
確認蓋章：<u>                      </u></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
