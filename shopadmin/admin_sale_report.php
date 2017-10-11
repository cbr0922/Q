<?php

include_once "Check_Admin.php";

include_once Classes . "/GD_Drive.php";
include_once Classes . "/Time.class.php";
include_once Classes . "/SaleMap.class.php";

include_once Classes . "/pagenav_stard.php";

include      "../language/".$INFO['IS']."/Mail_Pack.php";

include RootDocumentShare."/cache/Productclass_show.php";

$objClass = "9pv";

$Nav      = new buildNav($DB,$objClass);

/**

 *  装载语言包

 */

include      "../language/".$INFO['IS']."/Visit_Pack.php";
include      "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include      "../language/".$INFO['IS']."/Admin_Product_Pack.php";

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

$pub_starttime  = $_GET['pub_starttime']!="" ? $_GET['pub_starttime'] : date("Y-m-d",time()-7*24*60*60);
$pub_endtime  = $_GET['pub_endtime']!="" ? $_GET['pub_endtime'] : date("Y-m-d",time());

$market;
$startdate = new DateTime($pub_starttime);
$enddate = new DateTime($pub_endtime);

$Sql = "";
$Data = "";
$Join = "";
$Where = " WHERE FROM_UNIXTIME(`createtime`,'%y-%m-%d') BETWEEN DATE_FORMAT('".$pub_starttime."','%y-%m-%d') AND DATE_FORMAT('".$pub_endtime."','%y-%m-%d') and d.packgid=0";
$Group = "";
$top_ids = "";

if((strtotime($pub_endtime) - strtotime($pub_starttime))/(60*60*24*365) >= 1){ //跨年
	while($startdate->format("y")<=$enddate->format("y")){
		$market[$startdate->format("y")] = 0;
		$startdate->modify("+1 year");
	}
	$Data .= "FROM_UNIXTIME(`createtime`,'%y')";
	$Group .= " GROUP BY FROM_UNIXTIME(`createtime`,'%y')";
}
else if((strtotime($pub_endtime) - strtotime($pub_starttime))/(60*60*24*30) >= 1){ //跨月
	$startdate->setDate($startdate->format("y"),$startdate->format("m"),1);
	while($startdate->format("y-m")<=$enddate->format("y-m")){
		$market[$startdate->format("y-m")] = 0;
		$startdate->modify("+1 month");
	}
	$Data .= "FROM_UNIXTIME(`createtime`,'%y-%m')";
	$Group .= " GROUP BY FROM_UNIXTIME(`createtime`,'%y-%m')";
}
else{ //月內
	while($startdate->format("y-m-d")<=$enddate->format("y-m-d")){
		$market[$startdate->format("m-d")] = 0;
		$startdate->modify("+1 day");
	}
	$Data .= "FROM_UNIXTIME(`createtime`,'%m-%d')";
	$Group .= " GROUP BY FROM_UNIXTIME(`createtime`,'%y-%m-%d')";
}

if($_GET['provider'] != "na" && isset($_GET['provider'])){
	if($_GET['provider'] == "yes"){
		$Where .= " AND t.provider_id=".intval($_GET['provider_id']);
	}else if($_GET['provider'] == "no"){
		if($_GET['provider_id'] == 0 && isset($_GET['provider_id'])){
			$Where .= " AND t.provider_id>".intval($_GET['provider_id']);
		}else{
			$Where .= " AND t.provider_id=".intval($_GET['provider_id']);
		}
	}
}

if($_GET['item_id']!="" && isset($_GET['item_id'])){
	$Where .= " AND (d.`gid`=".intval($_GET['item_id'])." OR d.`bn` REGEXP '".trim($_GET['item_id'])."')";
}

if(isset($_GET['order_state'])){
	if($_GET['order_state'] == 4){
		$Where .= " AND t.order_state=".intval($_GET['order_state']);
	}else{
		$Where .= " AND (t.order_state=4 OR t.transport_state=".intval($_GET['order_state']).")";
	}
}else{
	$Where .= " AND t.order_state=4";
}

if($_GET['top_id'] != 0 && isset($_GET['top_id'])){
	$Join .= " JOIN `{$INFO[DBPrefix]}goods` g ON d.`gid` = g.`gid`";
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
	$Next_ArrayClass  = array_filter(explode(",",$Next_ArrayClass));
	$Array_class      = array_unique($Next_ArrayClass);
	//print_r($Array_class);
	if (count($Array_class)>0){
		$top_ids = intval($_GET['top_id']).",".implode(",",$Array_class);
		$Where .= " AND g.bid in (".$top_ids.")";
	}else{
		$top_ids = intval($_GET['top_id']);
		$Where .= " AND g.`bid`=".$top_ids;
	}
}

$Sql .= "SELECT ".$Data." `createtime`,SUM(`hadsend`) `totalcount`,SUM(`hadsend`*d.`price`) `totalprice` FROM `{$INFO[DBPrefix]}order_table` t JOIN `{$INFO[DBPrefix]}order_detail` d ON t.`order_id` = d.`order_id`";
$Sql .= $Join.$Where.$Group;

//echo $Sql;

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$totalcount=0;

if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if(array_key_exists($value['createtime'], $market)){
			$market[$value['createtime']] = $value['totalprice'];
			$totalcount += $value['totalcount'];
		}
	}
}

function Sun_pcon_class($id)
{
	global $DB,$INFO;
	$Egg  = "";
	$Eggs = "";
	$Sql = "select `bid` from `{$INFO[DBPrefix]}bclass` where top_id='".intval($id)."'";
	$Query  = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		while($Rs=$DB->fetch_array($Query)){
			$Eggs .=$Rs['bid'].",";
			$Egg  .=$Eggs.Sun_pcon_class($Rs['bid']);
		}
		return  $Egg;
	}else{
		return;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE>行銷工具--&gt;統計分析--&gt; 商品銷售統計</TITLE>

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

                    --&gt; 商品銷售統計</SPAN></TD>

                </TR></TBODY></TABLE></TD>

            <TD align=right width="50%">&nbsp;</TD>

          </TR>

        </TBODY>

  </TABLE>



  <table class="allborder" style="margin-bottom:10px" width="100%">

  <tr>

  <td>

<TABLE class=p12black cellSpacing=0 cellPadding=0 width="85%"   align=center border=0 >

        <FORM name=form1 id=form1 method=get action="">

          <TR>

            <TD height=19 colspan="3" align=right>		 </TD>

            </TR>

          <TR>

            <TD width="160" height="30" align=left class=p9black>日期：</TD>

            <TD height="1" colspan="2" align=left class=p9black>起始日期

              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=begtime size=10  onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $pub_starttime;?>' name='pub_starttime'>

              - 結束日期

              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=endtime size=10   onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $pub_endtime;?>' name='pub_endtime'>

            </TD>

            </TR>

          <TR>

            <TD height="30" align=left class=p9black>商品：</TD>

            <TD height="0" colspan="2" align=left class=p9black>
            	<input type="radio" name="provider" value="na" checked="checked" />全部
				<input type="radio" name="provider" value="yes" <?php if($_GET['provider'] == "yes") echo "checked";?>/>統倉
				<input type="radio" name="provider" value="no" <?php if($_GET['provider'] == "no") echo "checked";?>/>非統倉　供應商

                <select name="provider_id">
                	<?php
					$provider = "";
					$Sql      = "SELECT `provider_id`,`provider_name` FROM `{$INFO[DBPrefix]}provider` ORDER BY provider_id desc";
					$Query    = $DB->query($Sql);
					$Num      = $DB->num_rows($Query);

					while ($Rs=$DB->fetch_array($Query)) {
						$provider .="<option value=".$Rs['provider_id']." ";

						if ($_GET['provider_id'] == $Rs['provider_id'])
							$provider .= " selected";

						$provider .= " >".$Rs['provider_name']."</option>\n";
					}
					?>
                	<option value="0">請選擇供應商</option>
                	<?php echo $provider;?>
             	</select>

                輸入商品ID或產品編號<input size="20" name="item_id" onkeyup="value=value.replace(/\W/g,'')" value='<?php echo $_GET['item_id'];?>' />
           	</TD>

          </TR>

          <TR>

            <TD height="30" align=left class=p9black>商品分類：</TD>

            <TD height="0" colspan="2" align=left class=p9black>
            	<?php echo $Char_class->get_page_select("top_id",$_GET['top_id'],"  class=\"trans-input\" ");?>
            </TD>

          </TR>

 		  <TR>

           	<TD height="30" align=left class=p9black>訂單狀態：</TD>

            <TD height="0" colspan="2" align=left class=p9black>
             	<input type="radio" name="order_state" value="4" checked="checked" />完成交易訂單
				<input type="radio" name="order_state" value="1" <?php if($_GET['order_state'] == 1) echo "checked";?>/>已出貨訂單
			</TD>
          </TR>

          <TR>

            <TD height="30" align=left class=p9black>&nbsp;</TD>

            <TD height="30" colspan="2" align=left class=p9black>
            	<input type="submit" value="送出結果" />
            	<input type="button" value="匯出Excel" onclick="javascript:location.href='admin_sale_report_excel.php?<?php echo $_SERVER['QUERY_STRING']?>'" <?php if(!$_SERVER['QUERY_STRING']){echo "disabled";} ?>/>
            </TD>

            </TR>

          <TR>

            <TD height="19" colspan="3" align=left class=p9black>&nbsp;</TD>

            </TR>

          </FORM>

        </TABLE>



</td>

        </tr>

        </table>

<TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">

        <TBODY>

          <TR>

            <TD vAlign=top height=210><table class="p12black" cellspacing="0" cellpadding="0" width="85%"   align="center" border="0" >

              <form name="form1" id="form2" method="post" action="">

                <input type="hidden" name="Action2" value="Search" />

                <tr>

                  <td height="19" colspan="3" align="right"></td>

                </tr>

                <tr>

                  <td width="142" height="30" align="left" class="p9black">銷售金額：<?php echo array_sum($market) ?></td>

                  <td width="161" height="1" align="left" class="p9black">銷售數量：<?php echo $totalcount ?></td>

                  <td width="634" height="1" align="left" class="p9black">


                  </td>

                </tr>

                <tr>

                  <td height="30" align="left" class="p9black">圖表：</td>

                  <td height="0" colspan="2" align="left" class="p9black">只要長條圖和折線圖，Y軸人數，X軸日期(按月或其他可能切換)</td>

                </tr>

                <tr>

                  <td height="30" align="left" class="p9black">&nbsp;</td>

                  <td height="0" colspan="2" align="left" class="p9black">
                  	<canvas id="market" height="300" width="700"></canvas>
                  </td>

                </tr>

                <tr>

                  <td height="19" colspan="3" align="left" class="p9black"></td>

                </tr>

              </form>

            </table></TD>

        </TR></TABLE>

</div>
<!--載入圖表工具-->
<script src="../js/ChartNew.js"></script>
<script>
	//銷售金額(長條圖)
	var marketData = {
		labels : <?php echo json_encode(array_keys($market)); ?>,
		datasets : [
			{
				fillColor: "rgba(61,130,196,0.5)",
				strokeColor: "rgba(61,130,196,1)",
				data : <?php echo json_encode(array_values($market)); ?>
			}
		]
	}
	//顯示圖表
	new Chart(document.getElementById("market").getContext("2d")).Bar(marketData,{inGraphDataShow : true, annotateDisplay : true, yAxisUnit: "NT", annotateLabel: "<%=(v1 == '' ? '' : v1) + (v1!='' && v2 !='' ? ' - ' : '')+(v2 == '' ? '' : v2)+(v1!='' || v2 !='' ? ':' : '') + v3%>"});
</script>

<script language="javascript" src="../js/modi_bigarea1.js"></script>

<script language="javascript">

initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")

initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")

</script>

<div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>
