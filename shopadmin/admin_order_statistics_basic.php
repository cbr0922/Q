<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/ProductVisit_Pack.php";
function num($no,$len){
	if(strlen($no)<6)
		return str_repeat("0",$len-strlen($no)) . $no;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD><TITLE><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>-->訂單統計</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>-->訂單統計</SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <!--BUTTON_END--></TD>
                          </TR></TBODY></TABLE>

                    </TD></TR></TBODY></TABLE></TD></TR>
        </TBODY>
  </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top height=262>
              <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
                <TBODY>
                  <TR>
                    <TD height="300" vAlign=top bgColor=#ffffff>
                      <TABLE class=9pv cellSpacing=0 cellPadding=2
                  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR align="center">
                            <TD height="300" valign="middle">

                              <TABLE width="90%" border=0 cellPadding=3 cellSpacing=0 bgcolor="#F7F7F7" class="allborder">
                                <TBODY>
                                  <TR>
                                    <TD height="25"  align=left  bgColor=#ffffff class=p9orange>
                                    <select name="order_state" id="order_state" onChange="location.href='admin_order_statistics_new.php?order_state='+this.value;">
                                    	<option value="4" <?php if($_GET['order_state']==4) echo "selected";?>>完成交易訂單</option>
                                    	<option value="0" <?php if($_GET['order_state']==0) echo "selected";?>>已出貨訂單(包含完成交易)</option>
                                    </select></TD>
                                  </TR>
                                  <?php
									if($_GET['order_state']==0){
										$where_Sql = " and (order_state=1 or order_state=4) and transport_state=1";
									}elseif($_GET['order_state']==4){
										$where_Sql = " and order_state=4";
									}
									
									$Sql = "select sum(discount_totalPrices) as totals,count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where order_year='" . date("Y") . "' and order_month='" . date("m") . "' and order_day='" . date("d") . "'" . $where_Sql;
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
									$day_totals = $Rs['totals'];
									$day_counts = $Rs['counts'];
									$d = date('d',time())-1;
									$y = intval(date('Y',time()));
									$m = intval(date('m',time()));
									$yesdaytime = gmmktime(0,0,0,$m,$d,$y);
									
									$Sql = "select sum(discount_totalPrices) as totals,count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where order_year='" . date("Y",$yesdaytime) . "' and order_month='" . date("m",$yesdaytime) . "' and order_day='" . date("d",$yesdaytime) . "'" . $where_Sql;
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
									$yesday_totals = $Rs['totals'];
									$yesday_counts = $Rs['counts'];
									
									$starttime = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
									$overtime = mktime(0, 0 , 0,date("m"),date("d")-date("w")+7,date("Y"));
									$Sql = "select sum(discount_totalPrices) as totals,count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where createtime>='" . $starttime . "' and createtime<='" . $overtime . "'" . $where_Sql;
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
									$week_totals = $Rs['totals'];
									$week_counts = $Rs['counts'];
									
									$starttime = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"));
									$overtime = mktime(0, 0 , 0,date("m"),date("d")-date("w")+7-7,date("Y"));
									$Sql = "select sum(discount_totalPrices) as totals,count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where createtime>='" . $starttime . "' and createtime<='" . $overtime . "'" . $where_Sql;
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
									$lastweek_totals = $Rs['totals'];
									$lastweek_counts = $Rs['counts'];
									
									$Sql = "select sum(discount_totalPrices) as totals,count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where order_year='" . date("Y") . "' and order_month='" . date("m") . "' " . $where_Sql;
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
									$month_totals = $Rs['totals'];
									$month_counts = $Rs['counts'];
									
									$starttime = mktime(0, 0 , 0,date("m")-1,1,date("Y"));
									$overtime = mktime(23,59,59,date("m") ,0,date("Y"));
									$Sql = "select sum(discount_totalPrices) as totals,count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where createtime>='" . $starttime . "' and createtime<='" . $overtime . "'" . $where_Sql;
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
									$lastmonth_totals = $Rs['totals'];
									$lastmonth_counts = $Rs['counts'];
								  ?>
<TR>
                                    <TD width="100%"  height=30 align=left bgColor=#ffffff class=p9orange><table width="100%" border="1">
                                      <tbody>
                                        <tr>
                                          <td width="7%">&nbsp;</td>
                                          <td width="18%">本日</td>
                                          <td width="18%">昨日</td>
                                          <td width="18%">本周</td>
                                          <td width="18%">上周</td>
                                          <td width="18%">本月</td>
                                          <td width="18%">上月</td>
                                        </tr>
                                        <tr>
                                          <td>訂單數</td>
                                          <td><?php echo $day_counts?></td>
                                          <td><?php echo $yesday_counts?></td>
                                          <td><?php echo $week_counts?></td>
                                          <td><?php echo $lastweek_counts?></td>
                                          <td><?php echo $month_counts?></td>
                                          <td><?php echo $lastmonth_counts?></td>
                                        </tr>
                                        <tr>
                                          <td>總金額</td>
                                          <td><?php echo intval($day_totals)?></td>
                                          <td><?php echo intval($yesday_totals)?></td>
                                          <td><?php echo intval($week_totals)?></td>
                                          <td><?php echo intval($lastweek_totals)?></td>
                                          <td><?php echo intval($month_totals)?></td>
                                          <td><?php echo intval($lastmonth_totals)?></td>
                                        </tr>
                                        
                                      </tbody>
										</table></TD></tr>
                                   <TR>
                                
                                </TR>
                                    
                                                                  </TBODY>
                              </TABLE>
                            </TD>
                          </TR>
                          
                        </TBODY></TABLE></TD>
              </TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
</div>

<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>