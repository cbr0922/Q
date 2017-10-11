<?php

include_once "Check_Admin.php";

include_once Classes . "/pagenav_stard.php";

include      "../language/".$INFO['IS']."/Mail_Pack.php";

$objClass = "9pv";

$Nav      = new buildNav($DB,$objClass);

/**

 *  装载语言包

 */
include      "../language/".$INFO['IS']."/Admin_Member_Pack.php";

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
	/************************************
	 *
	 * 	銷售金額(折線圖，最近30天)
	 *
	 ************************************/

	$sell_day;
	$todate = new DateTime("now");
	$todate->modify("-30 day");
	for($i=0;$i<30;$i++){
		$todate->modify("+1 day");
		$sell_day[$todate->format("m-d")] = 0;
	}

	$Sql = "SELECT FROM_UNIXTIME(`createtime`,'%m-%d') as `createtime`,SUM(`totalprice`) as `totalprice` FROM `{$INFO[DBPrefix]}order_table` WHERE FROM_UNIXTIME(`createtime`,'%y-%m-%d') BETWEEN DATE_FORMAT(NOW() - INTERVAL 30 DAY,'%y-%m-%d') AND DATE_FORMAT(NOW(),'%y-%m-%d') AND pay_state=1 AND order_state=4 GROUP BY FROM_UNIXTIME(`createtime`,'%y-%m-%d')";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);

	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			if(array_key_exists($value['createtime'], $sell_day)){
				$sell_day[$value['createtime']] = $value['totalprice'];
			}
		}
	}
	/************************************
	 *
	 * 	網頁 / 手機 / 全部購買訂單筆數(長條圖，最近6個月)
	 *
	 ************************************/

	$orders;
	$todate = new DateTime("now");
	$todate->modify("-6 month");
	for($i=0;$i<6;$i++){
		$todate->modify("+1 month");
		$orders[$todate->format("y-m")] = 0;
	}

	$web = $phone = $orders;

	$Sql = "SELECT FROM_UNIXTIME(`createtime`,'%y-%m') as `createtime`,COUNT(*) as totle,`ifmobile` FROM `{$INFO[DBPrefix]}order_table` WHERE FROM_UNIXTIME(`createtime`,'%y-%m') BETWEEN DATE_FORMAT(NOW() - INTERVAL 6 MONTH,'%y-%m') AND DATE_FORMAT(NOW(),'%y-%m') AND pay_state=1 AND order_state=4 GROUP BY FROM_UNIXTIME(`createtime`,'%y-%m'), `ifmobile`";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);

	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			if(array_key_exists($value['createtime'], $orders)){
				if($value['ifmobile']==0){
					$web[$value['createtime']] = $value['totle'];
				}else{
					$phone[$value['createtime']] = $value['totle'];
				}
				$orders[$value['createtime']]=$web[$value['createtime']]+$phone[$value['createtime']];
			}
		}
	}
	/************************************
	 *
	 * 	銷售金額(長條圖，最近12個月)
	 *
	 ************************************/

	$k_date;
	$v_date = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

	$todate = new DateTime("now");
	$todate->modify("-12 month");
	for($i=0;$i<12;$i++){
		$todate->modify("+1 month");
		$k_date[] = $todate->format("y-m");
	}

	$sell_month = array_combine($k_date, $v_date);

	$Sql = "SELECT FROM_UNIXTIME(`createtime`,'%y-%m') as `createtime`,SUM(`totalprice`) as `totalprice` FROM `{$INFO[DBPrefix]}order_table` WHERE FROM_UNIXTIME(`createtime`,'%y-%m') BETWEEN DATE_FORMAT(NOW() - INTERVAL 12 MONTH,'%y-%m') AND DATE_FORMAT(NOW(),'%y-%m') AND pay_state=1 AND order_state=4 GROUP BY FROM_UNIXTIME(`createtime`,'%y-%m')";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);

	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			if(array_key_exists($value['createtime'], $sell_month)){
				$sell_month[$value['createtime']] = $value['totalprice'];
			}
		}
	}

	/************************************
	 *
	 * 	銷售商品排行(橫的長條圖，TOP 10)
	 *
	 ************************************/

	$top;

	$Sql = "SELECT `goodsname`,SUM(`hadsend`) as totle FROM `{$INFO[DBPrefix]}order_table` as t, `{$INFO[DBPrefix]}order_detail` as d WHERE t.`order_id`=d.`order_id` AND  pay_state=1 AND order_state=4 GROUP BY `gid` ORDER BY `totle` DESC LIMIT 10";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);

	if ($Num>0){
		$i=1;
		while($value = $DB->fetch_array($Query)){
			$top['TOP'.$i++.':'.$value['goodsname']] = $value['totle'];
		}
	}

	/************************************
	 *
	 * 	商品分類銷售金額(餅圖12個月)
	 *
	 ************************************/
	$color = array("#FF0033", "#CCFFFF", "#FFCCCC", "#FFFF33", "#99CCFF", "#CCCCCC", "#FFCC00", "#66CC00", "#FF9966", "#6666CC", "#CC9999", "#993399", "#66CCCC", "#0066CC");

	$Sql = "SELECT `bid`,`top_id`,`catname` FROM `{$INFO[DBPrefix]}bclass` ORDER BY `top_id`,`bid` ASC";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);

	$bid;$top_id;$catname;$assort;
	if ($Num>0){
		$i=0;
		while($value = $DB->fetch_array($Query)){
			if($value['top_id']==0){
				$assort[$value['catname']]['v'] = 0;
				$assort[$value['catname']]['c'] = $color[$i%14];
			}
			$bid[$i]=$value['bid'];
			$top_id[$i]=$value['top_id'];
			$catname[$i]=$value['catname'];
			$i++;
		}
	}

	foreach($bid as $k=>$v){
		foreach($top_id as $key=>$value){
			if($v==$value) $catname[$key]=$catname[$k];
		}
	}

	$Sql = "SELECT d.`gid`,`bid`,SUM(`hadsend`) `totalcount`,SUM(`hadsend`*d.`price`) `totalprice` FROM `{$INFO[DBPrefix]}order_table` t JOIN `{$INFO[DBPrefix]}order_detail` d ON t.`order_id` = d.`order_id` JOIN `{$INFO[DBPrefix]}goods` g ON d.`gid` = g.`gid` WHERE FROM_UNIXTIME(`createtime`,'%y-%m') BETWEEN DATE_FORMAT(NOW() - INTERVAL 12 MONTH,'%y-%m') AND DATE_FORMAT(NOW(),'%y-%m') AND t.`pay_state`=1 AND t.`order_state` = 4 GROUP BY d.`gid`";
	$Query = $DB->query($Sql);
	$Num = $DB->num_rows($Query);
	$Total = 0;
	if ($Num>0){
		$i=0;
		while($value = $DB->fetch_array($Query)){
			$key=$catname[array_search($value['bid'],$bid)];
			if(array_key_exists($key, $assort)){
				$assort[$key]['v'] += $value['totalprice'];
				$Total += $value['totalprice'];
			}
		}
	}

	/************************************
	 *
	 * 	下單時間(長條圖，最近12個月)
	 *
	 ************************************/

	$single = array_combine($k_date, $v_date);

	$Sql = "SELECT FROM_UNIXTIME(`createtime`,'%y-%m') as `createtime`,COUNT(*) as totle FROM `{$INFO[DBPrefix]}order_table` WHERE FROM_UNIXTIME(`createtime`,'%y-%m') BETWEEN DATE_FORMAT(NOW() - INTERVAL 12 MONTH,'%y-%m') AND DATE_FORMAT(NOW(),'%y-%m') AND  pay_state=1 AND order_state=4 GROUP BY FROM_UNIXTIME(`createtime`,'%y-%m')";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);

	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			if(array_key_exists($value['createtime'], $single)){
				$single[$value['createtime']] = $value['totle'];
			}
		}
	}
	/************************************
	 *
	 * 	地區銷售金額分布
	 *
	 ************************************/
	/*$area_county;$area_canton;$area_city;
	$county;$canton;
	$Sql = "select * from `{$INFO[DBPrefix]}area` where top_id=0";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);

	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			$area_county[$value['areaname']]['area_id']=$value['area_id'];
			$area_county[$value['areaname']]['top_id']=$value['top_id'];
			$county[$value['areaname']] = 0;

			$Sql1 = "select * from `{$INFO[DBPrefix]}area` where top_id=".$value['area_id'];
			$Query1    = $DB->query($Sql1);
			$Num1      = $DB->num_rows($Query1);

			if ($Num1>0){
				while($value1 = $DB->fetch_array($Query1)){
					$area_canton[$value1['areaname']]['area_id']=$value1['area_id'];
					$area_canton[$value1['areaname']]['top_id']=$value1['top_id'];
					$canton[$value['areaname']][$value1['areaname']] = 0;

					$Sql2 = "select * from `{$INFO[DBPrefix]}area` where top_id=".$value1['area_id'];
					$Query2    = $DB->query($Sql2);
					$Num2      = $DB->num_rows($Query2);

					if ($Num2>0){
						while($value2 = $DB->fetch_array($Query2)){
							$area_city[$value2['areaname']]['area_id']=$value2['area_id'];
							$area_city[$value2['areaname']]['top_id']=$value2['top_id'];
						}
					}
				}
			}
		}
	}

	$address;
	$Sql = "SELECT `receiver_address`, SUM(`totalprice`) `totalprice` FROM `{$INFO[DBPrefix]}order_table` WHERE pay_state=1 AND order_state=4 GROUP BY `receiver_address` LIMIT 10";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);

	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			$address[$value['receiver_address']] = $value['totalprice'];
		}
	}

	$is;
	foreach($address as $key=>$value){
		//echo $key."<br/>";
		foreach($county as $key1=>$value1){
			//echo $key1."<br/>";
			$is=false;
			if(stripos($key,$key1) !== false){
				$county[$key1] += $value;
				$is=true;
				break;
			}
		}

		if($is){
			foreach($canton[$key1] as $key2=>$value2){
				//echo $key2."<br/>";
				$is=false;
				if(stripos($key,$key2) !== false){
					//echo $key2."<br/>";
					$canton[$key1][$key2] += $value;
					$is=true;
					break;
				}
			}
		}else{
			foreach($area_canton as $key2=>$value2){
				if(stripos($key,$key2) !== false){
					$area_county;
					//$canton[$key1][$key2] += $value;
				}
			}
		}

		if($is){

		}else{

		}
	}
	//echo stripos("台灣臺北市大同區","灣台");
	//print_r($county);
	//$canton = array_filter($canton);
	print_r($canton);
	foreach($area_county as $key=>$value){
		$Sql = "select * from `{$INFO[DBPrefix]}area` where top_id=".$key;
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);

		if ($Num>0){
			while($value = $DB->fetch_array($Query)){
				$area_county[$value['area_id']] = $value['areaname'];
			}
		}
	}

	function Sun_pcon_class($id)
	{
		global $DB,$INFO;
		$Egg  = "";
		$Eggs = "";
		$Sql = "select `area_id`, `areaname` from `{$INFO[DBPrefix]}area` where top_id=".intval($id);
		$Query  = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		echo $id;
		if ($Num>0){
			while($Rs=$DB->fetch_array($Query)){echo $value['area_id'].$value['areaname']."<br/>";
				$Eggs .=$Rs['area_id'].",";
				$Egg  .=$Eggs.Sun_pcon_class($Rs['area_id']);
			}
			return  $Egg;
		}else{
			return;
		}
	}*/
	/*$gResult = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id=0");
	$num_row = $DB->num_rows($gResult);
	if($num_row>0){
		$i = 0;
		while ($gRow =  $DB->fetch_array($gResult)){
			//$area_array[$i]['text'] = $gRow['areaname'];
			$gResult1 = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id='" .$gRow['area_id']  . "'");
			$num_row1 = $DB->num_rows($gResult1);
			if($num_row1>0){
				$j = 0;
				while ($gRow1 =  $DB->fetch_array($gResult1)){
					$gResult2 = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id='" .$gRow1['area_id']  . "'");
					$num_row2 = $DB->num_rows($gResult2);
					if($num_row2>0){
						$j = 0;
						while ($gRow2 =  $DB->fetch_array($gResult2)){
							$area_array[$gRow['areaname']][$gRow1['areaname']] = $gRow2['areaname'];
						}
					}
					$j++;
				}

			}
			$i++;
		}
	}*/
	$Sql = "SELECT Country, SUM(`totalprice`) `totalprice` FROM `{$INFO[DBPrefix]}user` u JOIN `{$INFO[DBPrefix]}order_table` o ON u.`user_id`=o.`user_id` WHERE pay_state=1 AND order_state=4 GROUP BY Country";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$Country;
	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			if($value['Country'] != NULL)
				$Country[$value['Country']] = $value['totalprice'];
	}
	}//print_r($Country);

	$Sql = "SELECT Country,canton,SUM(`totalprice`) `totalprice` FROM `{$INFO[DBPrefix]}user` u JOIN `{$INFO[DBPrefix]}order_table` o ON u.`user_id`=o.`user_id` WHERE pay_state=1 AND order_state=4 GROUP BY Country,canton";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$canton = array_keys($Country);
	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			if($value['Country'] != NULL && $value['canton'] != NULL){
				$canton[$value['Country']][$value['canton']] = $value['totalprice'];
			}
		}
	}

	/************************************
	 *
	 * 	付款方式統計(訂單數)
	 *
	 ************************************/

	$Sql = "SELECT `paymentname`,COUNT(*) as totle FROM `{$INFO[DBPrefix]}order_table` WHERE pay_state=1 AND order_state=4 GROUP BY `paymentname`";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$paymentname;
	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			if($value['paymentname'] != NULL)
				$paymentname[$value['paymentname']] = $value['totle'];
		}
	}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE>行銷工具--&gt;CRM--&gt;會員概況</TITLE>

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

                    <TD class=p12black noWrap><SPAN class=p9orange>行銷工具--&gt; 統計分析

                    --&gt;銷售概況</SPAN></TD>

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

            <TD height=19 colspan="2" align=left>&nbsp;</TD>

            </TR>

          	<TR>
            	<TD height="30" align=left class=p9black>銷售金額(折線圖，最近30天)</TD>
			</TR>

			<TR>
	            <TD height="30" colspan="3" align=left class=p9black>
	            	<canvas id="sell_day" height="250" width="850"></canvas>
	            </TD>
			</TR>

			<TR>
            	<TD height="30" align=left class=p9black>網頁 / 手機 / 全部購買訂單筆數(長條圖，最近6個月)</TD>
            </TR>

			<TR>
            	<TD height="30" colspan="3" align=left class=p9black>
            		<canvas id="orders" height="250" width="850"></canvas>
            	</TD>
			</TR>
          <TR>

            <TD width="443" height="30" align=left class=p9black>銷售金額(長條圖，最近12個月)</TD>

            <TD height="30" align=left class=p9black>銷售商品排行(橫的長條圖，TOP 10)</TD>

            </TR>

			<TR>

            <TD height="30" align=left class=p9black>
            	<canvas id="sell_month" height="250" width="400"></canvas>
            </TD>

            <TD height="30" align=left class=p9black>
            	<canvas id="top" height="250" width="400"></canvas>
            </TD>

            </TR>

           <TR>

             <TD height="30" align=left class=p9black>商品分類銷售金額(餅圖12個月)</TD>

             <TD height="30" align=left class=p9black>每月訂單(長條圖，最近12個月)</TD>

            </TR>

			<TR>

            <TD height="30" align=left class=p9black>
            	<canvas id="assort" height="250" width="400"></canvas>
            </TD>

            <TD height="30" align=left class=p9black>
            	<canvas id="single" height="250" width="400"></canvas>
            </TD>

            </TR>

           <TR>

             <TD height="30" align=left class=p9black>地區銷售金額分布</TD>

             <TD height="30" align=left class=p9black>付款方式統計(訂單數)：</TD>

            </TR>

          <TR>

            <TD align=left valign="top" class=p9black>
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

            <TD align=left valign="top" class=p9black>
        	<?php
        	$i=0;
			foreach($paymentname as $k => $v ){
				echo $k. ' : '. $v. '筆';
				if($i++%2 == 0) echo ' ; ';
				else echo '</br>';
			}
			?>
            </TD>

          </TR>

					<TR>            <TD height="30" align=left class=p9black>&nbsp;</TD>            <TD height="30" align=left class=p9black>&nbsp;</TD>          </TR>
          <TR>

            <TD height="30" align=left class=p9black>&nbsp;</TD>

            <TD height="30" align=left class=p9black>&nbsp;</TD>

            </TR>

          <TR>

            <TD height="19" colspan="2" align=left class=p9black>&nbsp;</TD>

            </TR>

          </FORM>

        </TABLE>



</td>

        </tr>

        </table>



</div>
<!--載入圖表工具-->
<script src="../js/ChartNew.js"></script>
<script>
	//銷售金額(折線圖，最近30天)
	var sell_dayData = {
		labels : <?php echo json_encode(array_keys($sell_day)); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($sell_day)); ?>
			}
		]
	}
	//網頁 / 手機 / 全部購買訂單筆數(長條圖，最近6個月)
	var ordersData = {
		labels : <?php echo json_encode(array_keys($orders)); ?>,
		datasets : [
			{
				fillColor: "rgba(247,70,74,0.5)",
				strokeColor: "rgba(247,70,74,1)",
				data : <?php echo json_encode(array_values($web)); ?>,
				title : "網頁"
			},
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($phone)); ?>,
				title : "手機"
			},
			{
				fillColor: "rgba(245,247,70,0.5)",
				strokeColor: "rgba(245,247,70,1)",
				data : <?php echo json_encode(array_values($orders)); ?>,
				title : "全部"
			}
		]
	}
	//銷售金額(長條圖，最近12個月)
	var sell_monthData = {
		labels : <?php echo json_encode(array_keys($sell_month)); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($sell_month)); ?>
			}
		]
	}
	//銷售商品排行(橫的長條圖，TOP 10)
	var topData = {
		labels : <?php echo json_encode(array_keys(array_reverse($top))); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values(array_reverse($top))); ?>
			}
		]
	}
	//商品分類銷售金額(餅圖12個月)
	var assortData = [
		<?php
		end($assort);
		$endkey = key($assort);
		reset($assort);
		while(list($key, $value) = each($assort)){
			echo '{value : '.$assort[$key]['v'].',';
			echo 'color : "'.$assort[$key]['c'].'",';
			echo 'title : "'.$key.' '.floor(($assort[$key]['v']/$Total)*100).'%"}';
			if($key!=$endkey) echo ',';
		}
		?>
	];
	//下單時間(長條圖，最近12個月)
	var singleData = {
		labels : <?php echo json_encode(array_keys($single)); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($single)); ?>
			}
		]
	};

	//顯示圖表
	new Chart(document.getElementById("sell_day").getContext("2d")).Line(sell_dayData,{bezierCurve: false, inGraphDataShow : true, annotateDisplay : true, yAxisUnit: "NT"});
	new Chart(document.getElementById("orders").getContext("2d")).Bar(ordersData,{legend : true, inGraphDataShow : true, annotateDisplay : true, yAxisUnit: "Count", annotateLabel: "<%=(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3%>"});
	new Chart(document.getElementById("sell_month").getContext("2d")).Bar(sell_monthData,{inGraphDataShow : true, annotateDisplay : true, yAxisUnit: "NT", annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3%>"});
	new Chart(document.getElementById("top").getContext("2d")).HorizontalBar(topData,{yAxisLeft: false,xAxisBottom: false,inGraphDataShow : true, annotateDisplay : true, annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3%>"});
	new Chart(document.getElementById("assort").getContext("2d")).Pie(assortData,{legend : true, annotateDisplay : true});
	new Chart(document.getElementById("single").getContext("2d")).Bar(singleData,{inGraphDataShow : true, annotateDisplay : true, yAxisUnit: "Count", annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3%>"});
</script>

<script language="javascript" src="../js/modi_bigarea1.js"></script>

<script language="javascript">

initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")

initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")

</script>

<div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>