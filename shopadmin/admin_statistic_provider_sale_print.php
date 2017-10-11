<?php
include_once "Check_Admin.php";
include_once Classes . "/GD_Drive.php";
include_once Classes . "/Time.class.php";
include_once Classes . "/SaleMap.class.php";

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
if (intval($_GET['province'])>0 ){
	$subSql = " and ot.provider_id='" . intval($_GET['province']) . "'";
}
if (intval($_GET['provider_id'])>0){
	$subSql = " and ot.provider_id='" . intval($_GET['provider_id']) . "'";
}
if ($_GET['iftogether']=="1"){
	$subSql .= " and ot.iftogether=1";
}
if ($_GET['iftogether']=="0"){
	$subSql .= " and ot.iftogether=0";
}

$Sql = "select sum(od.cost*od.goodscount) as sumcost,od.*,sum(od.goodscount) as goodscounts from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on oa.order_detail_id=od.order_detail_id or (oa.order_detail_id=0 and oa.order_id=od.order_id) inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=2 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . " group by od.gid,od.cost";
$d_Query    = $DB->query($Sql);
?>
<div align="center">
UTV金連網<?php echo $current_month;?>月對帳銷售表<br>
                  <br>
                  <?php
                  if (intval($_GET['provider_id'])>0){
					$Provider_Search = " and provider_id='" . intval($_GET['provider_id']) . "'";
				  }
				  $Sql      = "select * from `{$INFO[DBPrefix]}provider` where 1=1 ".$Provider_Search." order by providerno desc  ";
				  $Query    = $DB->query($Sql);
				  $Rs=$DB->fetch_array($Query);
				  echo $Rs['providerno'] . $Rs['provider_name'];
				  ?></div>
                  <br>
<TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                            <TBODY>
                              <TR align=middle>
                                <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>商品編碼</TD>
                                <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>品名</TD>
                                <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>數量</TD>
                                <TD width="186" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>單成本價</TD>
                                <TD width="94" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>成本小計</TD>
                                <TD width="77" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>備註</TD>
                                </TR>
                              <?php
							  $pay_total = 0;
								  while($d_Rs=$DB->fetch_array($d_Query)){
									  $pay_total += $d_Rs['sumcost'];
							  ?>
                              <TR class=row0>
                                <TD height=26 align="left" noWrap><?php echo $d_Rs['bn']?></TD>
                                <TD height=26 align="left" noWrap><?php echo $d_Rs['goodsname']?></TD>
                                
                                <TD align=center nowrap><?php echo $d_Rs['goodscounts'];?></TD>
                                <TD align=center nowrap><?php echo $d_Rs['cost']?></TD>
                                <TD height=26 align=center nowrap><?php echo $d_Rs['sumcost']?></TD>
                                <TD height=26 align=center nowrap><?php echo $d_Rs['receiver_memo ']?></TD>
                                </TR>
                              <?php
								}
								?>
                              <TR>
                                <TD width=122 height=14 nowrap>&nbsp;</TD>
                                <TD width=296 height=14>&nbsp;</TD>
                                <TD width=153>&nbsp;</TD>
                                <TD width=186 height=14 align="center">(+a)銷售小計</TD>
                                <TD height=14 align="center"><?php echo $pay_total;?></TD>
                                <TD width=77 height=14>&nbsp;</TD>
                              </TR>
                              <TR>
                                <TD height=14 colspan="6" nowrap>&nbsp;</TD>
                                </TR>
                              <TR>
                                <TD height=14 nowrap>&nbsp;</TD>
                                <TD height=14>&nbsp;</TD>
                                <TD>&nbsp;</TD>
                                <TD height="14" align="right">(+b)<?php echo $current_month;?>月物流運費總金額</TD>
                                <TD height=14 align="center">
                                <?php
                                $t_Sql = "select sum(ot.transport_price) as sumtrans from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on oa.order_detail_id=od.order_detail_id or (oa.order_detail_id=0 and oa.order_id=od.order_id) inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=2 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . " ";
								$t_Query    = $DB->query($t_Sql);
								$t_Rs=$DB->fetch_array($t_Query);
								echo $t_Rs['sumtrans'];
								?>
                                </TD>
                                <TD height=14>&nbsp;</TD>
                              </TR>
                              <TR>
                                <TD height=14 nowrap>&nbsp;</TD>
                                <TD height=14>&nbsp;</TD>
                                <TD>&nbsp;</TD>
                                <TD height="14" align="right">(a+b)合計</TD>
                                <TD height=14 align="center"><?php echo  $t_Rs['sumtrans']+$pay_total;?></TD>
                                <TD height=14>&nbsp;</TD>
                              </TR>
                              <TR>
                                <TD height=14 colspan="6" nowrap>&nbsp;</TD>
                              </TR>
                              <TR>
                                <TD height=14 colspan="6" nowrap><p>備註：<Br />1.列印對帳銷售表，於對帳總表蓋發票章及負責窗口簽章<Br />2.對帳銷售表與發票、回郵信封一起寄回UTV金連網 財務部 收<Br />
                                                              105 台北市松山區復興北路1號4摟 02-27416166</p>
                                  <p><strong>                                                                                                                                      廠商發票章          負責窗口簽章</strong></p></TD>
                              </TR>
                            </TABLE>
                             <script language="javascript">
						window.print() ;
						</script>
