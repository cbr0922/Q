<?php
include_once "Check_Admin.php";

include_once Classes . "/pagenav_stard.php";

include      "../language/".$INFO['IS']."/Mail_Pack.php";

$objClass = "9pv";

$Nav      = new buildNav($DB,$objClass);

/*
//
// *  装载语言包
//
*/

include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

$Sql      = "select * from `{$INFO[DBPrefix]}ticketpubrecord` as r inner join `{$INFO[DBPrefix]}ticket` as t on r.ticketid=t.ticketid where r.ticketid='" . $_GET['ticketid'] . "' order by r.recordid desc";



$Query    = $DB->query($Sql);

$Num      = $DB->num_rows($Query);

if ($Num>0){

	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;

	$Nav->total_result=$Num;

	$Nav->execute($Sql,$limit);

	$Query = $Nav->sql_result;

	$Nums     = $Num<$limit ? $Num : $limit ;

}
//--------------------------------------------------瀏覽人數(折線圖，最近30天)
$skim;
$todate = new DateTime("now");
$todate->modify("-30 day");
for($i=0;$i<30;$i++){
	$todate->modify("+1 day");
	$skim[$todate->format("m-d")] = 0;
}
$Sql      = "SELECT DATE_FORMAT(visitdate,'%m-%d') as visitdate,COUNT(*) as totle FROM `{$INFO[DBPrefix]}count` ";
$Sql	 .= "WHERE DATE_FORMAT(visitdate,'%y-%m-%d') > DATE_FORMAT(NOW() - INTERVAL 30 DAY,'%y-%m-%d') ";
$Sql	 .= "AND DATE_FORMAT(visitdate,'%y-%m-%d') <= DATE_FORMAT(NOW(),'%y-%m-%d') GROUP BY DATE_FORMAT(visitdate,'%y-%m-%d')";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if(array_key_exists($value['visitdate'], $skim)){
			$skim[$value['visitdate']] = $value['totle'];
		}
	}
}

//--------------------------------------------------會員註冊人數(長條圖，最近12個月)
$k_date;
$v_date = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

$todate = new DateTime("now");
$todate->modify("-12 month");
for($i=0;$i<12;$i++){
	$todate->modify("+1 month");
	$k_date[] = $todate->format("y-m");
}

$Sql      = "SELECT DATE_FORMAT(reg_date,'%y-%m') as reg_date, COUNT(*) as totle FROM `{$INFO[DBPrefix]}user` ";
$Sql	 .= "WHERE DATE_FORMAT(reg_date,'%y-%m') > DATE_FORMAT(NOW() - INTERVAL 12 MONTH,'%y-%m') ";
$Sql	 .= "AND DATE_FORMAT(reg_date,'%y-%m') <= DATE_FORMAT(NOW(),'%y-%m') GROUP BY DATE_FORMAT(reg_date,'%y-%m')";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$reg_date = array_combine($k_date, $v_date);

if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if(array_key_exists($value['reg_date'], $reg_date)){
			$reg_date[$value['reg_date']] = $value['totle'];
		}
	}
}

//--------------------------------------------------會員成長圖(折線圖，最近12個月)
$growing = $reg_date;
$n = current($growing);
next($growing);
while (list($key, $val) = each($growing))
{
  $growing[$key] = $n = $val == 0 ? $n : $n+$val;
}

//--------------------------------------------------男女比例(餅圖)
$Sql      = "SELECT `sex`, COUNT(*) as totle FROM `{$INFO[DBPrefix]}user` GROUP BY sex";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$sex;
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		$sex[$value['sex']] = $value['totle']; //0男 1女
	}
}

//--------------------------------------------------會員地區分布
$Sql      = "SELECT Country, COUNT(*) as totle FROM `{$INFO[DBPrefix]}user` GROUP BY Country";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$Country;
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if($value['Country'] != NULL)
			$Country[$value['Country']] = $value['totle'];
}
}//print_r($Country);

$Sql      = "SELECT Country, canton, COUNT(*) as totle FROM `{$INFO[DBPrefix]}user` GROUP BY Country, canton";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$canton = array_keys($Country);
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if($value['Country'] != NULL && $value['canton'] != NULL){
			$canton[$value['Country']][$value['canton']] = $value['totle'];
		}
	}
}

//--------------------------------------------------購買行為者人數(線圖與會員總數比較)
$k_date1 = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");

//--------會員購買筆數
$Sql	  = "SELECT`order_month`, COUNT(*) as totle FROM `{$INFO[DBPrefix]}order_table` JOIN `{$INFO[DBPrefix]}user` ";
$Sql	  .= "ON {$INFO[DBPrefix]}order_table.user_id = {$INFO[DBPrefix]}user.user_id GROUP BY `order_month`";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$order_date[0] = array_combine($k_date1, $v_date);

if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if(array_key_exists($value['order_month'],$order_date[0])){
			$order_date[0][$value['order_month']] = $value['totle'];
		}
	}
}
//--------會員總數
$Sql	  = "SELECT DATE_FORMAT(reg_date,'%m') as reg_date, COUNT(*) as totle FROM `{$INFO[DBPrefix]}user` GROUP BY DATE_FORMAT(reg_date,'%m')";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$order_date[1] = array_combine($k_date1, $v_date);

if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if(array_key_exists($value['reg_date'],$order_date[1])){
			$order_date[1][$value['reg_date']] = $value['totle'];
		}
	}
}
//--------非會員買筆數
$Sql	  = "SELECT `order_month`, COUNT(*) as totle FROM `{$INFO[DBPrefix]}order_table` LEFT JOIN `{$INFO[DBPrefix]}user` ";
$Sql	  .= "ON {$INFO[DBPrefix]}order_table.user_id = {$INFO[DBPrefix]}user.user_id WHERE `{$INFO[DBPrefix]}user`.`user_id` IS NULL GROUP BY `order_month`";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$order_date[2] = array_combine($k_date1, $v_date);

if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if(array_key_exists($value['order_month'],$order_date[2])){
			$order_date[2][$value['order_month']] = $value['totle'];
		}
	}
}
//print_r($order_date);

//--------------------------------------------------會員生日分布
$Sql      = "SELECT DATE_FORMAT(born_date,'%m') as born_date, COUNT(*) as totle FROM `{$INFO[DBPrefix]}user` GROUP BY DATE_FORMAT(born_date,'%m')";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$born = array_combine($k_date1, $v_date);
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if(array_key_exists($value['born_date'], $born))
			$born[$value['born_date']] = $value['totle'];
	}
}

//--------------------------------------------------訂閱電子報人數
$mail;			//陣列索引 0=>會員未訂閱 1=>會員訂閱 58=>非會員訂閱
$mail_totle;	//陣列索引 0=>會員未訂閱 1=>會員訂閱 58=>非會員訂閱 2=>總訂閱人數

$Sql      = "SELECT `dianzibao`,GROUP_CONCAT(`email`) as email FROM `ntssi_user` GROUP BY `dianzibao`";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if($value['dianzibao'] != NULL)
			$mail[$value['dianzibao']] = array_filter(explode(',',$value['email']));
			$mail_totle[$value['dianzibao']] = count($mail[$value['dianzibao']],1);
	}
}

$Sql      = "SELECT `mgroup_id`, `mgroup_list` FROM `{$INFO[DBPrefix]}mail_group` WHERE `mgroup_id` = 58";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if($value['mgroup_id'] != NULL)
			$mail[$value['mgroup_id']] = array_filter(explode(',',$value['mgroup_list']));
			$mail_totle[$value['mgroup_id']] = count($mail[$value['mgroup_id']],1);
	}
}

$mail_totle[2] = $mail_totle[1] + $mail_totle[58];

//print_r(array_diff($mail[58], array_diff($mail[58], $mail[484])));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE>行銷工具--&gt;進階客戶關係管理--&gt;會員概況</TITLE>

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<script language="javascript">

function toExprot(){

	form2.submit();

}

</script>

<form name="form2" method="post" action="admin_group_excel.php" target='_blank'  >

<input type="hidden" name="Action" value="Excel">

</form>

<SCRIPT language=javascript>

function toEdit(id,catid){

	var checkvalue;

	var catvalue = "";



	if (id == 0) {

		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');

	}else{

		checkvalue = id;

	}



	if (catid != 0) {

		catvalue = "&scat="+catid;

	}



	if (checkvalue!=false){

		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;

		document.adminForm.action = "admin_ticket.php?Action=Modi&ticketid="+checkvalue;

		document.adminForm.act.value="edit";

		document.adminForm.submit();

	}

}



function toDel(){

	var checkvalue;

	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');

	if (checkvalue!=false){

		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){

			document.adminForm.action = "admin_ticket_save.php";

			document.adminForm.act.value="Del";

			document.adminForm.submit();

		}

	}

}

</SCRIPT>

<div id="contain_out">

  <?php  include_once "Order_state.php";?>

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

                <TBODY>

                  <TR>

                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>

                    <TD class=p12black noWrap><SPAN class=p9orange>行銷工具--&gt; 進階客戶關係管理

                    --&gt; 會員概況</SPAN></TD>

                </TR></TBODY></TABLE></TD>

            <TD align=right width="50%">&nbsp;</TD>

          </TR>

        </TBODY>

  </TABLE>



  <table class="allborder" style="margin-bottom:10px" width="100%">

  <tr>

  <td>

<TABLE class=p12black cellSpacing=0 cellPadding=0 width="85%"   align=center border=0 >

        <FORM name=form1 id=form1 method=post action="admin_ticketrecord_save.php">

          <input type="hidden" name="Action" value="Search">

          <TR>

            <TD height=19 colspan="3" align=right>		 </TD>

            </TR>

          <!--TR>

            <TD height="30" colspan="3" align=left class=p9black>瀏覽人數(折線圖，最近30天)</TD>

            </TR>
          <TR>

            <TD height="30" colspan="3" align=left class=p9black><canvas id="skim" height="250" width="850"></canvas></TD>

            </TR-->


          <TR>

            <TD width="443" height="30" align=left class=p9black>會員註冊人數(長條圖，最近12個月)</TD>

            <TD height="30" colspan="2" align=left class=p9black>會員成長圖(折線圖，最近12個月)</TD>

            </TR>

          <TR>

            <TD width="443" height="30" align=left class=p9black><canvas id="register" height="250" width="400"></canvas></TD>

            <TD height="30" colspan="2" align=left class=p9black><canvas id="growing" height="250" width="400"></canvas></TD>

            </TR>
           <TR>
             <TD height="30" align=left class=p9black>男女比例(餅圖)</TD>

             <TD height="30" colspan="2" align=left class=p9black>購買行為者人數(線圖與會員總數比較)</TD>

            </TR>
           <TR>
             <TD height="30" align=left class=p9black><canvas id="sex" height="250" width="400"></canvas></TD>

             <TD height="30" colspan="2" align=left class=p9black><div><div id="lineLegend"></div>
    			<canvas id="buy" height="250" width="400"></canvas>
    			</div>
    		</TD>

            </TR>
           <TR>

             <TD height="30" align=left class=p9black>會員年齡分布：</TD>

             <TD height="30" colspan="2" align=left class=p9black>會員生日分布：</TD>

            </TR>

          <TR>

            <TD height="30" align=left class=p9black><canvas id="age" height="250" width="400"></canvas></TD>
            <TD height="30" colspan="2" align=left class=p9black><canvas id="born" height="250" width="400"></canvas></TD>

          </TR>
          <TR>
						<TD height="30" align=left class=p9black><p>會員地區分布：<br />
						<?php
						foreach( $Country as $k => $v ){
							echo $k. ' : '. $v. '</br>';
							$i=1;
							foreach( $canton[$k] as $k1 => $v1 ){
								echo $k1. ' : '. $v1;
								if($i%2 != 0) echo " ; ";
								else echo "</br>";
								$i++;
							}
							echo '</br>';
						}
						?>
					</TD>
            <TD height="35" colspan="2" align=left valign="top" class=p9black>
              <p>訂閱電子報人數：<br />

              <?php echo '訂閱人數：'. $mail_totle[2].'<br/>未訂閱人數：'. $mail_totle[0].'<br/>非會員訂閱人數：'. $mail_totle[58] ?></p></TD>
          </TR>
          <TR>						 <TD height="30" align=left class=p9black>&nbsp;</TD>
            <TD height="35" colspan="2" align=left valign="top" class=p9black>&nbsp;</TD>
          </TR>

          </FORM>

        </TABLE>



</td>

        </tr>

        </table>



</div>
<!--載入圖表工具-->
<script src="../js/Chart.js"></script>
<script src="../js/legend.js"></script>
<style type="text/css">
.legend {
	margin-left: 20px;
}
.legend .title {
    margin: 0.5em;
    border-style: solid;
    border-width: 0 0 0 1em;
    padding: 0 0.3em;
}
</style>
<script>
	//瀏覽人數(折線圖，最近30天)
	var skimData = {
		labels : <?php echo json_encode(array_keys($skim)); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($skim)); ?>
			}
		]
	}
	//會員註冊人數(長條圖，最近12個月)
	var registerData = {
		labels : <?php echo json_encode($k_date); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($reg_date)); ?>
			}
		]
	}
	//會員成長圖(折線圖，最近12個月)
	var growingData = {
		labels : <?php echo json_encode($k_date); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($growing)); ?>
			}
		]
	}
	//男女比例(餅圖)
	var sexData = [
		{
			value: <?php echo $sex[1]; ?>,
			color:"#F7464A",
			label: "女"
		},
		{
			value: <?php echo $sex[0]; ?>,
			color: "#3D82C4",
			label: "男"
		}
	]
	//購買行為者人數(線圖與會員總數比較)
	var buyData = {
		labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
		datasets : [
			{
				fillColor: "rgba(247,70,74,0.5)",
				strokeColor: "rgba(247,70,74,1)",
				pointColor: "rgba(247,70,74,1)",
				data : <?php echo json_encode(array_values($order_date[0])); ?>,
				title: "會員購買筆數"
			},
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				pointColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($order_date[1])); ?>,
				title: "會員總數"
			},
			{
				fillColor : "rgba(245,247,70,0.5)",
				strokeColor : "rgba(245,247,70,1)",
				pointColor: "rgba(245,247,70,1)",
				data : <?php echo json_encode(array_values($order_date[2])); ?>,
				title: "非會員購買筆數"
			}
		]
	}
	//會員年齡分布
	var ageData = {
		labels : ["<10", "11-20", "21-30", "31-40", "41-50", "51-60", "61-70", "71-80", "81-90", "91-100", "100<"],
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode($v_date); ?>
			}
		]
	}
	var bornData = {
		labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode($born); ?>
			}
		]
	}
	//顯示圖表
	//new Chart(document.getElementById("skim").getContext("2d")).Line(skimData, {bezierCurve: false});
	new Chart(document.getElementById("register").getContext("2d")).Bar(registerData);
	new Chart(document.getElementById("growing").getContext("2d")).Line(growingData, {bezierCurve: false});
	new Chart(document.getElementById("sex").getContext("2d")).Pie(sexData);
	new Chart(document.getElementById("buy").getContext("2d")).Line(buyData, {bezierCurve: false});
	legend(document.getElementById("lineLegend"), buyData);
	new Chart(document.getElementById("age").getContext("2d")).Bar(ageData);
	new Chart(document.getElementById("born").getContext("2d")).Bar(bornData);

</script>
<script language="javascript" src="../js/modi_bigarea1.js"></script>

<script language="javascript">

initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")

initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")

</script>

<div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>
