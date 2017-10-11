<?php
include_once "Check_Admin.php";
include_once Classes . "/GD_Drive.php";
include_once Classes . "/Time.class.php";

include_once Classes . "/orderClass.php";
$TimeClass = new TimeClass;
$orderClass = new orderClass;
$year  = $_GET['year']!="" ? $_GET['year'] : date("Y",time());
$month  = $_GET['month']!="" ? $_GET['month'] : date("m",time());
/*
$begtime  =date("Y-m-d H:i:s",mktime(0, 0 , 0,$month-1,26,$year));
$endtime  = date("Y-m-d H:i:s",mktime(0, 0 , 0,$month,25,$year));
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
*/
$begtimeunix  =mktime(0, 0 , 0,$month-1,26,$year);
$endtimeunix  = mktime(0, 0 , 0,$month,25,$year)+60*60*24;

$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
$end_month = date("m",mktime(0, 0 , 0,date("m",$endtimeunix)+1,26,date("Y",$endtimeunix)));
$end_year = date("Y",mktime(0, 0 , 0,date("m",$endtimeunix)+1,26,date("Y",$endtimeunix)));														 
$over_month = date("m",mktime(0, 0 , 0,date("m",$endtimeunix),26,date("Y",$endtimeunix)));			
$over_year = date("Y",mktime(0, 0 , 0,date("m",$endtimeunix),26,date("Y",$endtimeunix)));			



if (intval($_GET['province'])>0 ){
	$subSql = " and od.provider_id='" . intval($_GET['province']) . "'";
}
if (intval($_GET['provider_id'])>0){
	$subSql = " and od.provider_id='" . intval($_GET['provider_id']) . "'";
}
if (intval($_GET['iftogether'])>0){
	$togetherSql .= " and ot.deliveryid='" . intval($_GET['iftogether']) . "'";
}
if(intval($_GET['provider_id'])==114)
	$cost = "cast(od.goods_cost as DECIMAL )";
else
	$cost = "od.cost*od.goodscount";

$Sql = "select od.*,ot.*," . $cost . " as cost from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0 )and oa.order_id=od.order_id inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . $togetherSql . " group by od.order_detail_id order by ot.order_id";
$t_Query    = $DB->query($Sql);

foreach($_GET as $k=>$v){
	$restring .= "&" . $k . "=" . $v;	
}
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
			document.adminForm.action = "admin_statistic_provider_detail_excel.php?<?php echo $restring;?>";
			document.adminForm.submit();
}
//-->
</SCRIPT>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<div id="contain_out">
<form action="" method="post"  name="adminForm">
</form>
<?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
 <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%" height="44">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>供應商管理--&gt;供應商對帳--&gt;交易明細</SPAN></TD>
              </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><input type="button" name="button2" id="button2" value="回對帳列表" onClick="history.back(-1);">
              <input type="button" name="button2" id="button2" value="下載" onClick="toExcel();">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD align=middle>
                      
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
            <TD vAlign=top height=262><div align="center"><?php echo $month;?>月 交易明細<br>
              <br>
              <?php
                  if (intval($_GET['provider_id'])>0){
					$Provider_Search = " and provider_id='" . intval($_GET['provider_id']) . "'";
				  }
				  $Sql      = "select * from `{$INFO[DBPrefix]}provider` where 1=1 ".$Provider_Search." order by providerno desc  ";
				  $Query    = $DB->query($Sql);
				  $Rs=$DB->fetch_array($Query);
				  echo $Rs['providerno'] . $Rs['provider_name'];
				  ?>
              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td width="10%">廠商名稱：</td>
                  <td width="55%"><?php echo $Rs['providerno'] . $Rs['provider_name'];?></td>
                  <td width="10%">對帳區間：</td>
                  <td width="25%"><?php echo $current_year;?>/<?php echo $current_month;?>/26~<?php echo $over_year;?>/<?php echo $over_month;?>/25</td>
                  </tr>
                <tr>
                  <td>統一編號：</td>
                  <td><?php echo $Rs['invoice_num'] ;?></td>
                  <td>結算日：</td>
                  <td><?php echo $year;?>/<?php echo $month ;?>/26</td>
                  </tr>
                </table>
              </div>
              <TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                <TBODY>
                  <TR align=middle>
                    <TD  height=26 colspan="9" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>科目：代收金額(+)</TD>
                    </TR>
                  <TR align=middle>
                    <TD align="left" noWrap class=p9black>序號</TD>
                    <TD  height=26 align="left" noWrap class=p9black>訂單編號</TD>
                    <TD  height=26 align="left" noWrap class=p9black style1>訂單日期</TD>
                    <TD align="center" noWrap class=p9black>商品貨號</TD>
                    <TD align="center" noWrap class=p9black>品名</TD>
                    <TD align="center" noWrap class=p9black>數量</TD>
                    <TD align="center" noWrap class=p9black>成本小計</TD>
                    <TD align="center" noWrap class=p9black>稅額</TD>
                    <TD align="center" noWrap class=p9black>總計</TD>
                    </TR>
                  <?php               
					$i=0;
					//exit;
					$total1 = 0;
					while ($Rs=$DB->fetch_array($t_Query)) {
						$trans_array[$Rs['order_id']] = $Rs['transport_price'];
				if($curorder!=$Rs['order_serial'] && $transport_price>0){
				?>
                  <TR>
                    <TD width=123 nowrap>&nbsp;</TD>
                    <TD width=123 height=14 nowrap><?php echo $curorder;?></TD>
                    <TD width=113 height=14><?php echo $curcreatetime;?></TD>
                    <TD width=328>&nbsp;</TD>
                    <TD width=328 align="left">
                      運費
                      </TD>
                    <TD width=328 align="center">1</TD>
                    <TD width=328 align="center"><?php echo $transport_price;?></TD>
                    <TD width=328 align="center"><?php echo round($transport_price*0.05,0)?></TD>
                    <TD width=328 align="center"><?php echo $transport_price+round($transport_price*0.05,0)?></TD>
                    </TR>
                  <?php
					$total1 += $transport_price+round($transport_price*0.05,0);
				} 
				
				?>
                  <TR class=row0>
                    <TD align="left" noWrap><?php echo $i+1;?></TD>
                    <TD height=26 align="left" noWrap><?php echo $Rs['order_serial']?>&nbsp; 
                      </TD>
                    <TD height=26 align="left" noWrap><?php echo date("Y-m-d",$Rs['createtime'])?></TD>
                    <TD align=center nowrap><?php echo $Rs['bn']?></TD>
                    <TD align=left nowrap><?php echo $Rs['goodsname']?>&nbsp;</TD>
                    <TD align=center nowrap><?php echo $Rs['goodscount']?>&nbsp;</TD>
                    <TD align=center nowrap><?php echo $Rs['cost']?>&nbsp;</TD>
                    <TD align=center nowrap><?php echo round($Rs['cost']*0.05,0)?></TD>
                    <TD align=center nowrap><?php echo $Rs['cost']+round($Rs['cost']*0.05,0)?>&nbsp;</TD>
                    </TR>
                  <?php
					$total1 += $Rs['cost']+round($Rs['cost']*0.05,0);
					$curorder = $Rs['order_serial'];
					$curdeliveryid = $Rs['deliveryid'];
					$curcreatetime = date("Y-m-d",$Rs['createtime']);
					$transport_price = $Rs['transport_price'];
					$i++;
					
					}
				
				
				
                if($transport_price>0){
					?>
                    <TR>
                    <TD width=123 nowrap>&nbsp;</TD>
                    <TD width=123 height=14 nowrap><?php echo $curorder;?></TD>
                    <TD width=113 height=14><?php echo $curcreatetime;?></TD>
                    <TD width=328>&nbsp;</TD>
                    <TD width=328 align="left">
                      運費
                      </TD>
                    <TD width=328 align="center">1</TD>
                    <TD width=328 align="center"><?php echo $transport_price;?></TD>
                    <TD width=328 align="center"><?php echo round($transport_price*0.05,0)?></TD>
                    <TD width=328 align="center"><?php echo $transport_price+round($transport_price*0.05,0)?></TD>
                    </TR>
					<?php
					$total1 += $transport_price+round($transport_price*0.05,0);
					}?>
                  <TR>
                    <TD width=123 nowrap>&nbsp;</TD>
                    <TD width=123 height=14 nowrap>&nbsp;</TD>
                    <TD width=113 height=14>&nbsp;</TD>
                    <TD width=328>&nbsp;</TD>
                    <TD width=328>&nbsp;</TD>
                    <TD width=328>&nbsp;</TD>
                    <TD width=328>&nbsp;</TD>
                    <TD width=328>小計</TD>
                    <TD width=328><?php echo $total1;?></TD>
                    </TR>
                  <TR>
                    <TD nowrap>&nbsp;</TD>
                    <TD height=14 nowrap>&nbsp;</TD>
                    <TD height=14>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    
                    <TD></TD>
                    <TD>
                      
                      </TD>
                   
                    </TR>
                  <TR>
                    <TD nowrap>&nbsp;</TD>
                    <TD height=14 nowrap>&nbsp;</TD>
                    <TD height=14>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>小計(A)</TD>
                    <TD><?php echo $total1 = $total1+round(intval($Rs_m['zhang'])*1.05,0);?></TD>
                    </TR>
                </TABLE>
              <br />
              <TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                <TBODY>
                  <TR align=middle>
                    <TD  height=26 colspan="9" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>科目：待退金額(-)</TD>
                    </TR>
                  <TR align=middle>
                    <TD align="left" noWrap class=p9black>序號</TD>
                    <TD  height=26 align="left" noWrap class=p9black>訂單編號</TD>
                    <TD  height=26 align="left" noWrap class=p9black style1>訂單日期</TD>
                    <TD align="center" noWrap class=p9black>商品貨號</TD>
                    <TD align="center" noWrap class=p9black>品名</TD>
                    <TD align="center" noWrap class=p9black>數量</TD>
                    <TD align="center" noWrap class=p9black>成本小計</TD>
                    <TD align="center" noWrap class=p9black>稅額</TD>
                    <TD align="center" noWrap class=p9black>總計</TD>
                    </TR>
                  <?php  
$Sql = "select ot.*,od.*," . $cost . " as cost from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on oa.order_detail_id=od.order_detail_id or (oa.order_detail_id=0 and oa.order_id=od.order_id) inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=3 and (oa.state_value=20) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . $togetherSql . " group by od.order_detail_id";
$t_Query    = $DB->query($Sql);
$transport_price = 0;
					$i=0;
					//exit;
					$total2=0;
					while ($Rs=$DB->fetch_array($t_Query)) {
						
					?>
                  <?php
				if($curorder!=$Rs['order_serial'] && $transport_price>0){
				?>
                  <TR>
                    <TD width=123 nowrap>&nbsp;</TD>
                    <TD width=123 height=14 nowrap><?php echo $curorder;?></TD>
                    <TD width=113 height=14><?php echo $curcreatetime;?></TD>
                    <TD width=328>&nbsp;</TD>
                    <TD width=328 align="left">
                      運費
                      </TD>
                    <TD width=328 align="center">1</TD>
                    <TD width=328 align="center"><?php echo $transport_price;?></TD>
                    <TD width=328 align="center"><?php echo round($transport_price*0.05,0)?></TD>
                    <TD width=328 align="center"><?php echo $transport_price+round($transport_price*0.05,0)?></TD>
                    </TR>
                  <?php
					$total2 += $transport_price+round($transport_price*0.05,0);
				} 
				
				?>
                  <tr class="row0">
                    <td align="left" nowrap="nowrap"><?php echo $i+1;?></td>
                    <td height="26" align="left" nowrap="nowrap"><?php echo $Rs['order_serial']?>&nbsp; </td>
                    <td height="26" align="left" nowrap="nowrap"><?php echo date("Y-m-d",$Rs['createtime'])?></td>
                    <td align="center" nowrap="nowrap"><?php echo $Rs['bn']?></td>
                    <td align="left" nowrap="nowrap"><?php echo $Rs['goodsname']?>&nbsp;</td>
                    <td align="center" nowrap="nowrap"><?php echo $Rs['goodscount']?>&nbsp;</td>
                    <td align="center" nowrap="nowrap"><?php echo $Rs['cost']?>&nbsp;</td>
                    <td align="center" nowrap="nowrap"><?php echo round($Rs['cost']*0.05,0)?></td>
                    <td align="center" nowrap="nowrap"><?php echo $Rs['cost']+round($Rs['cost']*0.05,0)?>&nbsp;</td>
                    </tr>
                  <?php
	$total2 += $Rs['cost']+round($Rs['cost']*0.05,0);
				
					
					$curorder = $Rs['order_serial'];
					$curdeliveryid = $Rs['deliveryid'];
					$curcreatetime = date("Y-m-d",$Rs['createtime']);
					
					$transport_price = $Rs['transport_price'];
					$i++;
					}
					
                  if($transport_price>0){
					?>
                    <TR>
                    <TD width=123 nowrap>&nbsp;</TD>
                    <TD width=123 height=14 nowrap><?php echo $curorder;?></TD>
                    <TD width=113 height=14><?php echo $curcreatetime;?></TD>
                    <TD width=328>&nbsp;</TD>
                    <TD width=328 align="left">
                      運費
                      </TD>
                    <TD width=328 align="center">1</TD>
                    <TD width=328 align="center"><?php echo $transport_price;?></TD>
                    <TD width=328 align="center"><?php echo round($transport_price*0.05,0)?></TD>
                    <TD width=328 align="center"><?php echo $transport_price+round($transport_price*0.05,0)?></TD>
                    </TR>
					<?php
					$total2 += $transport_price+round($transport_price*0.05,0);
					}
					?>
                  <TR>
                    <TD nowrap>&nbsp;</TD>
                    <TD height=14 nowrap>&nbsp;</TD>
                    <TD height=14>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>小計(B)</TD>
                    <TD><?php echo $total2;?></TD>
                    </TR>
                </TABLE>
              <TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                <TBODY>
                  
                  <TR>
                    <TD width="5%" nowrap>&nbsp;</TD>
                    <TD width="5%" height=14 nowrap>&nbsp;</TD>
                    <TD width="5%" height=14>&nbsp;</TD>
                    <TD width="5%">&nbsp;</TD>
                    <TD width="5%">&nbsp;</TD>
                    <TD width="5%">&nbsp;</TD>
                    <TD width="41%">&nbsp;</TD>
                    <TD width="13%"><strong>合計(D=A-B/1.05)</strong></TD>
                    <TD width="16%"><?php echo $total4 = round(($total1-$total2)/1.05);?></TD>
                    </TR>
                  <TR>
                    <TD nowrap>&nbsp;</TD>
                    <TD height=14 nowrap>&nbsp;</TD>
                    <TD height=14>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD><strong>營業稅(E=D*5%)</strong></TD>
                    <TD><?php echo $total5 = round($total4*0.05);?></TD>
                    </TR>
                  <TR>
                    <TD nowrap>&nbsp;</TD>
                    <TD height=14 nowrap>&nbsp;</TD>
                    <TD height=14>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD>&nbsp;</TD>
                    <TD><strong>總計(F=D+E)</strong></TD>
                    <TD><?php echo $total6 = round($total5+$total4);?></TD>
                    </TR>
      </TABLE></TD></TR></TBODY></TABLE></TD>
    </TR>

  </TBODY>
</TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
