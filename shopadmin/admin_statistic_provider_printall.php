<?php
include_once "Check_Admin.php";
include_once Classes . "/GD_Drive.php";
include_once Classes . "/Time.class.php";
include_once Classes . "/SaleMap.class.php";
include      "../language/".$INFO['IS']."/Visit_Pack.php";
include      "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include      "../language/".$INFO['IS']."/Admin_Product_Pack.php";


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
if($_GET['type']=="ll"){
	$subSql = " and p.provider_id='114'";
	$_GET['provider_id'] = '114';
	$subSql2 = " and pm.pid='114'";	
}elseif (intval($_GET['province'])>0 || intval($_GET['provider_id'])>0){
	$subSql = " and p.provider_id='" . intval($_GET['provider_id']) . "'";
	$subSql2 = " and pm.pid='" . intval($_GET['provider_id']) . "'";
}else{
	$subSql = " and p.provider_id<>'114'";	
	$subSql2 = " and pm.pid<>'114'";	
	
}
if ($_GET['ifcheck']=="1"){
	$togetherSql2 .= " and pm.ifok=1";
}
if ($_GET['ifcheck']=="0"){
	$togetherSql2 .= " and pm.ifok=0";
}
if ($_GET['skey']!=""){
	$togetherSql2 .= " and p." . $_GET['provider_type'] . " like '%" . $_GET['skey'] . "%'";
}

if (intval($_GET['iftogether'])>0){
	$togetherSql2 .= " and pm.iftogether='" . intval($_GET['iftogether']) . "'";
}
$Sql = "select p.*,pm.* from `{$INFO[DBPrefix]}provider_month` as pm inner join `{$INFO[DBPrefix]}provider` as p on p.provider_id=pm.pid where pm.year='" . $year . "' and pm.month='" . $month . "' " . $togetherSql2 . " " . $subSql2 . "";

/*
$Sql = "select p.*,ot.deliveryid,p.provider_id as provider_id from `{$INFO[DBPrefix]}order_action` oa 
inner join `{$INFO[DBPrefix]}order_detail` od on (oa.order_detail_id=od.order_detail_id or oa.order_detail_id=0) and oa.order_id=od.order_id
left join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=oa.order_id 
left join `{$INFO[DBPrefix]}provider` as p on p.provider_id=od.provider_id 
left join `{$INFO[DBPrefix]}provider_month` as pm on (p.provider_id=pm.pid and pm.year='" . $current_year . "' and pm.month='" . $current_month . "'  and pm.iftogether=ot.deliveryid)
where oa.state_type=3 and (oa.state_value=13 or oa.state_value=17 or oa.state_value=20 or oa.state_value=1) and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' and  ot.deliveryid>0 " . $subSql . " " . $togetherSql . " group by p.provider_id,ot.deliveryid";
*/
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
?>
<TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                            <INPUT type=hidden name=act>
                            <INPUT type=hidden value=0  name=boxchecked>
                            <TBODY>
                              <TR align=middle>
                                <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><INPUT onclick=checkAll('<?php echo intval($Nums)?>'); type=checkbox value=checkbox   name=toggle></TD>
                                <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>編號</TD>
                                <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>月份</TD>
                                <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>廠編</TD>
                                <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>廠商簡稱</TD>
                                <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>商品狀態</TD>
                                <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>請款金額</TD>
                                <TD width="79" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>實際請款金額</TD>
                                <TD width="79" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>票期</TD>
                                <TD width="81" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>兌現日</TD>
                                <TD width="165" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>廠商發票</TD>
                                <TD width="72" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>對帳狀態</TD>
                              </TR>
                              <?php               
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                              <TR class=row0>
                                <TD align="left" noWrap><INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['provider_id']?>' name=cid[]> </TD>
                                <TD align="left" noWrap><?PHP echo $i+1;?></TD>
                                <TD height=26 align="left" noWrap><?php echo $month;?>月</TD>
                                <TD height=26 align="left" noWrap><?php echo $Rs['providerno']?></TD>
                                
                                <TD align=center nowrap><?php echo $Rs['provider_name'];?></TD>
                                <TD align=center nowrap><?php echo $Rs['iftogether']==23?"門市取貨":"宅配";?></TD>
                                <TD height=26 align=center nowrap>
                                <?php
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
							$total+=round($Rs_d['sumcost']*1.05,0);
							if($curorder!=$Rs_d['order_serial'])
								$yunfei += round($cur_store_bundle_count*10*1.05,0) + round($cur_store_single_count*16*1.05,0) + round($cur_house_bundle_count*10*1.05,0) + round($cur_house_single_count*16*1.05,0)-round($cur_store_return_bundle_count*5*1.05,0)-round($cur_store_return_single_count*8*1.05,0)-round($cur_house_return_bundle_count*5*1.05,0)-round($cur_house_return_single_count*8*1.05,0);
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
						$total+= $yunfei;
								echo $total;
								foreach($_GET as $k=>$v){
									$restring .= "&" . $k . "=" . $v;	
								}
								*/
								echo $Rs['money'];
								?>
                                </TD>
                                <TD align=center nowrap>
                                <?php
                               
									echo intval($Rs['paymoney']+round(intval($Rs['zhang'])*1.05,0));	
								
								?> 
                                </TD>
                                <TD align=center nowrap><?php echo $Rs['piaoqi']?></TD>
                                <TD height=26 align=center nowrap><?php echo date("Y-m-d",$endtimeunix+intval($Rs['piaoqi'])*60*60*24);?></TD>
                                <TD align=center nowrap><?php echo $Rs['invoice_title']?></TD>
                                <TD height=26 align=center nowrap>
                                <?php 
								if (intval($Rs['ifok'])==1)
									echo "結案";
								else
									echo "<font color='red'>未結案</font>";
								?>
                                </TD>
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
                                <TD width=92>&nbsp;</TD>
                                <TD width=79>&nbsp;</TD>
                                <TD width=79 height=14>&nbsp;</TD>
                                <TD height=14 colspan="8">&nbsp;</TD>
                              </TR>
                            
                        </TABLE>
                        <script language="javascript">
						window.print() ;
						</script>