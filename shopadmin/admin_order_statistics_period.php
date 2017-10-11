<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/ProductVisit_Pack.php";
function num($no,$len){
	if(strlen($no)<6)
		return str_repeat("0",$len-strlen($no)) . $no;
}
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()-30*24*60*60);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
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
  <TD height="25" align=left bgColor=#ffffff class=p9orange>
                                           <form name="form1" id="form1" method="get" action="">
                                           起始日期
                                            <input  onmouseover="this.className='box2'" onmouseout="this.className='box1'"  id="begtime" size="10"  onclick="showcalendar(event, this)" onfocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $begtime;?>' name='begtime' />
                                            - 結束日期
                                            <input  onmouseover="this.className='box2'" onmouseout="this.className='box1'"  id="endtime" size="10"   onclick="showcalendar(event, this)" onfocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $endtime;?>' name='endtime' />
                                            <span class="p9black">
                                            <input type="submit" value="送出結果" />
												
                                            </span></form></TD>
</TR>
<TR>
                                <TD width="100%" height="25" align=left bgColor=#ffffff class=p9orange>
								<canvas id="born" height="350" width="960" style="width: 800px; height: 250px;"></canvas>	  
                                </TD>
                                </TR>
                                </TBODY>
                              </TABLE>
                            </TD>
                          </TR>
                        </TBODY></TABLE></TD>
              </TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
</div>
<?php
//时段统计图
$Sql = "select count(order_id) as counts,from_unixtime(createtime, '%H') as hours,ifmobile from `{$INFO[DBPrefix]}order_table` where createtime>='$begtimeunix' and createtime<='$endtimeunix' group by ifmobile,from_unixtime(createtime, '%H')";
$Query    = $DB->query($Sql);
$AryData = array();
while($Rs=$DB->fetch_array($Query)){
	
	$AryData[$Rs['ifmobile']][$Rs['hours']]=intval($Rs['counts']);
}//print_r($AryData);
?>
<script src="include/Chart.js"></script>
<script language="javascript">
	
	var test = {
		labels : [	<?php for($j=0;$j<=23;$j++){
							echo "\"".num($j,2)."\",";
						}?>],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
                strokeColor : "rgba(151,187,205,1)",
				data : [<?php for($j=0;$j<=23;$j++){
							echo "\"".intval($AryData[0][num($j,2)])."\",";
						}?>]
			},
			{
				fillColor : "rgba(220,220,220,0.5)",
                strokeColor : "rgba(151,187,205,1)",
				data : [<?php for($j=0;$j<=23;$j++){
							echo "\"".intval($AryData[1][num($j,2)])."\",";
						}?>]
			}
		]
	}

	new Chart(document.getElementById("born").getContext("2d")).Bar(test);

</script>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>