<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */

include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

include RootDocumentShare."/cache/Productclass_show.php";

$Sql = "select count(cg.gid) as sntotal,g.goodsname,g.gid";
$Sql .=	" from  `{$INFO[DBPrefix]}user` u";
$Sql .=	" left join `{$INFO[DBPrefix]}collection_goods` as cg on u.user_id=cg.user_id";
$Sql .=	" right join `{$INFO[DBPrefix]}goods` as g on cg.gid=g.gid";
$Sql .=	" where cg.user_id!=0";
$Sql .=	" group by cg.gid order by sntotal desc";

//echo $Sql;

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$Results_count = 0;
$Results;
$Total = 0;
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		$Field['index'] = ($Results_count + 1);
		$Field['goodsname'] = $value['goodsname'];
		$Field['gid'] = $value['gid'];
		$Field['value'] = $value['sntotal'];
		$Total = $Total + $value['sntotal'];
		$Results[$Results_count] = $Field;
		$Results_count++;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>行銷工具--&gt;統計分析--&gt;商品統計--&gt;追蹤商品統計</TITLE>
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
                    <TD class=p12black noWrap><SPAN class=p9orange>行銷工具--&gt;統計分析--&gt;商品統計--&gt;追蹤商品統計</SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">&nbsp;</TD>
          </TR>
        </TBODY>
  </TABLE>

<TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD><table class="p12black" cellspacing="0" cellpadding="0" width="85%"   align="center" border="0" >
              <!--<form name="form1" id="form2" method="post" action="admin_ticketrecord_save.php">-->
              <form name="form2" method="post">
                <input type="hidden" name="Action2" value="Search" />
                <tr>
                  <td height="19" colspan="4" align="right">
					  <table id="itemTable" class="tablesorter">
						<thead>
							<tr>
							  <th width="84" height="30" align="left" class="p9black">排名</th>
							  <th width="285" height="1" align="left" class="p9black">商品名稱</th>
							  <th width="430" height="1" align="left" class="p9black">佔比(%)</th>
							  <th width="138" align="left" class="p9black">數量:<?php echo $Total; ?></th>
								<th width="100" height="30" align="center" class="p9black">商品郵件組</th>
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
								<td align="left" class="p9black"><?php echo $Results[$i]['goodsname'];?></td>
								<td align="left" class="p9black">
									<div id="item<?php echo $Results[$i]['index'];?>"></div>
								</td>
								<td align="left" class="p9black"><?php echo $Results[$i]['value'];?></td>
								<td align="center" class="p9black link_box"><a href="admin_track_goods_statistics_save.php?gid=<?php echo $Results[$i]['gid'];?>" target="_blank">保存郵件組</a></td>
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
                <tr>
                  <td height="19" colspan="4" align="left" class="p9black">&nbsp;</td>
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
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
