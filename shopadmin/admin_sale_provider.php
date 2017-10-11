<?php

include_once "Check_Admin.php";

include_once Classes . "/pagenav_stard.php";

include      "../language/".$INFO['IS']."/Mail_Pack.php";

$objClass = "9pv";

$Nav      = new buildNav($DB,$objClass);

/**

 *  装载语言包

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

$starttime  = $_GET['starttime']!="" ? $_GET['starttime'] : date("Y-m-d",time()-7*24*60*60);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());

$Sql = "SELECT `provider_name`,SUM(`hadsend`) `totalcount`,SUM(`hadsend`*d.`price`) `totalprice` FROM `ntssi_order_table` t";
$Sql .= " JOIN `ntssi_order_detail` d ON t.`order_id` = d.`order_id`";
$Sql .= " JOIN `ntssi_provider` p ON d.`provider_id` = p.`provider_id`";
$Sql .= " WHERE FROM_UNIXTIME(`createtime`,'%y-%m-%d') BETWEEN DATE_FORMAT('".$starttime."','%y-%m-%d') AND DATE_FORMAT('".$endtime."','%y-%m-%d') AND pay_state=1 AND order_state=4 AND t.iftogether=0";
$Sql .= " GROUP BY p.`provider_id`";

if( isset($_GET['type']) ){
	if( $_GET['type'] == 0 )
		$Sql .=	" ORDER BY totalprice DESC";
	else if( $_GET['type'] == 1 )
		$Sql .=	" ORDER BY totalcount DESC";
}
else{
	$Sql .=	" ORDER BY totalprice DESC";
}

//echo $Sql;

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$Results_count = 0;
$Results;
$Total = 0;

if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		$Field['index'] = ($Results_count + 1);
		$Field['provider_name'] = $value['provider_name'];
		
		if( isset($_GET['type']) ){
			if( $_GET['type'] == 0 )
				$Field['value'] = $value['totalprice'];
			else if( $_GET['type'] == 1 )
				$Field['value'] = $value['totalcount'];
		}
		else{
			$Field['value'] = $value['totalprice'];
		}
		
		$Total += $Field['value'];
		
		$Results[$Results_count] = $Field;
		$Results_count++;
	}	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE>行銷工具--&gt;統計分析--&gt; 供應商銷售統計</TITLE>

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

                    --&gt; 供應商銷售統計</SPAN></TD>

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

              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=begtime size=10  onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $starttime;?>' name='starttime'>

              - 結束日期

              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=endtime size=10   onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $endtime;?>' name='endtime'>

            </TD>

            </TR>

           <TR>

             <TD height="30" align=left class=p9black>查詢種類：</TD>

             <TD height="0" colspan="2" align=left class=p9black>
             	<input type="radio" name="type" value="0" checked="checked" />銷售金額
				<input type="radio" name="type" value="1" <?php if($_GET['type'] == 1) echo "checked";?>/>數量
			</TD>

           </TR>

           <!--<TR>
             <TD height="30" align=left class=p9black>排序：</TD>
             <TD height="0" colspan="2" align=left class=p9black>
             	<input name="type1" type="radio" checked="checked" />由高至低
				<input type="radio" name="type1" />由低至高
			</TD>
           </TR>-->

          <TR>

            <TD height="30" align=left class=p9black>&nbsp;</TD>

            <TD height="30" colspan="2" align=left class=p9black>
            	<input type="submit" value="送出結果" /> 
            	<input type="button" value="匯出excel表" onclick="javascript:location.href='admin_sale_provider_excel.php?<?php echo $_SERVER['QUERY_STRING']?>'" <?php if(!$_SERVER['QUERY_STRING']){echo "disabled";} ?>/>
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

              <form name="form1" id="form2" method="get" action="">

                <input type="hidden" name="Action2" value="Search" />

                <tr>

                  <td height="19" colspan="4" align="right"></td>

                </tr>

                <tr>
                  <td>
					<table id="itemTable" class="tablesorter">
						<thead> 
							<tr>
							  <th width="84" height="30" align="left" class="p9black">排名</th>
							  <th width="285" height="1" align="left" class="p9black">供應商名稱</th>
							  <th width="430" height="1" align="left" class="p9black">佔比(%)</th>
							  <th width="138" align="left" class="p9black">
							  <?php
								if( isset($_GET['type']) ){
									if( $_GET['type'] == 0 )
										echo "銷售金額";
									else if( $_GET['type'] == 1 )
										echo "數量";
								}
								else{
									echo "銷售金額";
								}
							  ?>
							  </th>
							</tr>
						</thead>
						<tbody id="listBox">
							<script src="progressbar/jquery-ui.js"></script>
							<?php
								for($i=0; $i<$Results_count; $i++){
									$compare = floor(($Results[$i]['value']/$Total) * 100);
							?>
							<tr>
								<td align="left" class="p9black"><?php echo $Results[$i]['index'];?></td>
								<td align="left" class="p9black"><?php echo $Results[$i]['provider_name'];?></td>
								<td align="left" class="p9black">
									<div id="item<?php echo $Results[$i]['index'];?>"></div>
								</td>
								<td align="left" class="p9black"><?php echo floor($Results[$i]['value']);?></td>
							</tr>
							<script>
								$(function() {
									$( "#item<?php echo $Results[$i]['index'];?>" ).progressbar({
										value: <?php echo $compare;?>
									});
								});
							</script>
							<?php
								}
							?>
						</tbody>
					</table>
				</td>
                </tr>
              </form>
            </table></TD>

        </TR></TABLE>

</div>

<script language="javascript" src="tablesorter2/jquery.tablesorter.js"></script>
<link rel="stylesheet" type="text/css" href="tablesorter2/tablesorter-style.css">
<link rel="stylesheet" href="progressbar/jquery-ui.css">
<script language="javascript">

$(document).ready(function(){   
    $("#itemTable").tablesorter({widgets: ['zebra']});
});  

</script>
<script language="javascript" src="../js/modi_bigarea1.js"></script>

<script language="javascript">

initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")

initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")

</script>

<div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>

