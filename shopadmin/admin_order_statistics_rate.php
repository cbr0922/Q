<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/ProductVisit_Pack.php";
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
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
                                    <TD height="25"  align=left  bgColor=#ffffff class=p9orange><table class="p12black" cellspacing="0" cellpadding="0" width="85%"   align="center" border="0" >
                                      <form name="form1" id="form1" method="get" action="">
                                        <tr>
                                          <td height="19" colspan="3" align="right"></td>
                                        </tr>
                                        <?php
										  $begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()-30*24*60*60);
										  $endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());

										?>
                                        <tr>
                                          <td width="160" height="30" align="left" class="p9black">日期：</td>
                                          <td height="1" colspan="2" align="left" class="p9black">起始日期
                                            <input  onmouseover="this.className='box2'" onmouseout="this.className='box1'"  id="begtime" size="10"  onclick="showcalendar(event, this)" onfocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $begtime;?>' name='begtime' />
                                            - 結束日期
                                            <input  onmouseover="this.className='box2'" onmouseout="this.className='box1'"  id="endtime" size="10"   onclick="showcalendar(event, this)" onfocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $endtime;?>' name='endtime' /></td>
                                        </tr>
                                        <tr>
                                          <td height="30" align="left" class="p9black">付款方式：</td>
                                          <td height="0" colspan="2" align="left" class="p9black"><select name="payment">
                                            <option value="0">請選擇付款方式</option>
                                            <?php
                            $Sql_t      = "select *,p.content as pcontent,p.month as pmonth from `{$INFO[DBPrefix]}paymethod` as p inner join `{$INFO[DBPrefix]}paymanager` as pm on p.pid=pm.pid where p.ifopen=1 order by pm.paytype desc,p.mid";
							$Query_t    = $DB->query($Sql_t);
							$Num_t      = $DB->num_rows($Query_t);
							while ($Rs_t=$DB->fetch_array($Query_t)) {
							?>
                                            <option value="<?php echo $Rs_t['mid'];?>" <?php if($Rs_t['mid'] == $_GET['payment']) echo "selected";?>><?php echo $Rs_t['methodname'];?></option>
                                            <?
							}
							?>
                                          </select>
											
                                          </td>
                                        </tr>
                                        <tr>
                                          <td height="30" align="left" class="p9black">&nbsp;</td>
                                          <td height="30" colspan="2" align="left" class="p9black"><input type="submit" value="送出結果" /></td>
                                        </tr>
                                        <tr>
                                          <td height="19" colspan="3" align="left" class="p9black">&nbsp;</td>
                                        </tr>
                                      </form>
                                    </table></TD>
                                  </TR>
                                  <?php
									if($_GET['payment']>0){
										$where_Sql = " and paymentid='" . intval($_GET['payment']) . "'";
									}
									$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
									$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
									
									$Sql = "select sum(discount_totalPrices) as totals,count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where createtime>='$begtimeunix' and createtime<='$endtimeunix'" . $where_Sql;
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
									$all_counts = $Rs['counts'];
									$all_totals = $Rs['totals'];
									
									$Sql = "select count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where createtime>='$begtimeunix' and createtime<='$endtimeunix' and (order_state=3 or pay_state<>1)" . $where_Sql;
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
									$cancel_counts = $Rs['counts'];
									
									$Sql = "select count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where createtime>='$begtimeunix' and createtime<='$endtimeunix' and (transport_state=6 || transport_state=11 || transport_state=20)" . $where_Sql;
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
									$return_counts = $Rs['counts'];
									
									$Sql = "select user_id,count(order_id) as counts from `{$INFO[DBPrefix]}order_table` where createtime>='$begtimeunix' and createtime<='$endtimeunix'" . $where_Sql . " group by user_id";
									$Query    = $DB->query($Sql);
									$AryUser = array();
									$AryUserHave = array();
									$AryCounts = array();
									$i= 0 ;
									while($Rs=$DB->fetch_array($Query)){
										$AryUser[$i]=$Rs['user_id'];
										$AryCounts[$Rs['user_id']]=$Rs['counts'];
										$i++;
									}
									$havecounts = 0;
									if(count($AryUser)>0){
										$Sql = "select user_id from `{$INFO[DBPrefix]}order_table` where createtime<'$begtimeunix' and user_id in (" . implode(",",$AryUser) . ") and order_state=4 group by user_id";
										$Query    = $DB->query($Sql);
										$AryUser = array();
										$i= 0 ;
										while($Rs=$DB->fetch_array($Query)){
											$AryUserHave[$i]=$Rs['user_id'];
											$i++;
										}
										
										foreach($AryUserHave as $k=>$v){
											$havecounts+=$AryCounts[$v];
										}
									}
								  ?>
<TR>
                                    <TD width="100%"  height=30 align=left bgColor=#ffffff class=p9orange><table width="100%" border="1">
                                      <tbody>
                                        <tr>
                                          <td width="10%">棄單數量/比率</td>
                                          <td width="90%"><?php echo $cancel_counts?>/<?php echo round($cancel_counts/$all_counts,2)*100?>%</td>
                                        </tr>
                                        <tr>
                                          <td>退貨數量/比率</td>
                                          <td><?php echo $return_counts?>/<?php echo round($return_counts/$all_counts,2)*100?>%</td>
                                        </tr>
                                        <tr>
                                          <td>回購率</td>
                                          <td><?php echo round($havecounts/$all_counts,2)*100?>%</td>
                                        </tr>
                                        <tr>
                                          <td>客單價</td>
                                          <td><?php echo round($all_totals/$all_counts,0)?></td>
                                        </tr>
                                      </tbody>
                                    </table></TD>
                                    
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