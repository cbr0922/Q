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

include RootDocumentShare."/cache/Productclass_show.php";

foreach ($_GET as $_get_name => $_get_value) {
	if ($_get_name != "offset"&$_get_name != "order") {
		if ($_get_name=='skey'){
			$_get_vars .= "&$_get_name=".urlencode(trim($_get_value))."";
		}else{
			$_get_vars .= "&$_get_name=".trim($_get_value)."";
		}
		
		if (isset($_GET['Type'])){
			$_get_vars    .="&Type=".trim($_GET['Type']);								  
		}
	}    
}

include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

$y = date("Y",time());
$m = date("m",time());
$d = date("d",time());
$starttime = mktime(0,0,0,$m-1,1,$y);
$endtime = mktime(0,0,0,$m,1,$y);
$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",$starttime);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",$endtime);
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;

$Sql = "select sum(od.goodscount*od.price) as smtotal,sum(od.goodscount) as sntotal,od.goodsname, og.bid, og.view_num, oc.top_id";
$Sql .=	" from `{$INFO[DBPrefix]}order_detail` as od";
$Sql .=	" inner join `{$INFO[DBPrefix]}order_table` as ot on od.order_id=ot.order_id";
$Sql .=	" left join `{$INFO[DBPrefix]}goods` as og on od.gid=og.gid";
$Sql .=	" left join `{$INFO[DBPrefix]}bclass` as oc on og.bid=oc.bid";
$Sql .=	" where ot.createtime>='" . $begtimeunix . "' and ot.createtime<='" . $endtimeunix . "' and ot.order_state=4";
if( $_GET['top_id'] != 0 && isset($_GET['top_id']) )
	$Sql .=	" and oc.top_id=" . $_GET['top_id'];
$Sql .=	" group by od.gid";
if( isset($_GET['type']) ){
	if( $_GET['type'] == 0 )
		$Sql .=	" order by smtotal desc";
	else if( $_GET['type'] == 1 )
		$Sql .=	" order by sntotal desc";
	else if( $_GET['type'] == 2 )
		$Sql .=	" order by view_num desc";
}
else{
	$Sql .=	" order by sntotal desc";
}

if( isset($_GET['count']) ){
	$Sql .=	" limit ".$_GET['count'];
}
else{
	$Sql .=	" limit 100";
}
//echo $Sql;

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$Results_count = 0;
$Results;
$Total = 0;
//echo $Query;
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		$Field['index'] = ($Results_count + 1);
		$Field['goodsname'] = $value['goodsname'];
		
		if( isset($_GET['type']) ){
			if( $_GET['type'] == 0 )
				$Field['value'] = $value['smtotal'];
			else if( $_GET['type'] == 1 )
				$Field['value'] = $value['sntotal'];
			else if( $_GET['type'] == 2 )
				$Field['value'] = $value['view_num'];
		}
		else{
			$Field['value'] = $value['smtotal'];
		}
	
		$Field['smtotal'] = $value['smtotal'];
		//$Field['compare'] = floor(($value['sntotal']/$value['view_num']) * 100);
		$Total =  $Total + $value['smtotal'];
		
		$Results[$Results_count] = $Field;
		$Results_count++;
	}	
}

//echo $INFO['company_name'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>行銷工具--&gt;統計分析--&gt; 熱門商品統計</TITLE>
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

/*function creatListBoxNode( index, description, proportion, value ){
	var obj=document.getElementById( "listBox" );
	var tr=document.createElement('tr');
	var td;
	td=document.createElement('td');
	td.align="left";
	td.className="p9black";
	td.innerHTML = index;
	tr.appendChild( td );
	
	td=document.createElement('td');
	td.align="left";
	td.className="p9black";
	td.innerHTML = description;
	tr.appendChild( td );
	
	td=document.createElement('td');
	td.align="left";
	td.className="p9black";
	td.innerHTML = "<div id=\"item" + index + "\">" + index + "</div>";
	tr.appendChild( td );
	
	td=document.createElement('td');
	td.align="left";
	td.className="p9black";
	td.innerHTML = value;
	tr.appendChild( td );
	
	obj.appendChild( tr );
	
	$(function() {
		$( "#item" + index ).progressbar({
			value: proportion
		});
	});
}*/

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
                    --&gt; 熱門商品統計</SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">&nbsp;</TD>
          </TR>
        </TBODY>
  </TABLE>
  
  <table class="allborder" style="margin-bottom:10px" width="100%">
  <tr>
  <td>
<TABLE class=p12black cellSpacing=0 cellPadding=0 width="85%"   align=center border=0 >
        <!--<FORM name=form1 id=form1 method=post action="">-->      
          <FORM name=form1 method=get>
          <!--<input type="hidden" name="Action" value="Search">-->
          <input type="hidden" name="act" value="<?php echo $_GET[act]?>">
          <TR>
            <TD height=19 colspan="3" align=right>		 </TD>
            </TR>
          <TR>
            <TD width="160" height="30" align=left class=p9black>日期：</TD>
            <TD height="1" colspan="2" align=left class=p9black>起始日期
				<?php echo $Visit_Packs[VisFrom];//从?>
              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id='begtime' size=10  onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $begtime;?>' name='begtime'>
              - 結束日期
              <?php echo $Visit_Packs[VisFrom];//从?>
              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id='endtime' size=10   onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $endtime;?>' name='endtime'>
            </TD>
            </TR>
          <TR>
            <TD height="30" align=left class=p9black>商品分類：</TD>
            <TD height="0" colspan="2" align=left class=p9black>

			
			<?php echo  $Char_class->get_page_select("top_id",$_GET[top_id],"  class=\"trans-input\" ");?>

            </TR>
            <TR>
             <TD height="30" align=left class=p9black>最大筆數：</TD>
             <TD height="0" colspan="2" align=left class=p9black>
				<input type="text" name="count" value="100" onkeyup="value=value.replace(/[^-_0-9]/g,'')" onfocusout="value=(value=='')?'100':value"/>
			</TD>
           </TR>
           <TR>
             <TD height="30" align=left class=p9black>查詢種類：</TD>
             <TD height="0" colspan="2" align=left class=p9black>
				<input type="radio" name="type" value="0" checked="checked"/>
				銷售金額
				<input type="radio" name="type" value="1"/>
				數量
				<!--<input type="radio" name="type" value="2"/>
				瀏覽數-->
			</TD>
            </TR>
			
			<!--<TR>
             <TD height="30" align=left class=p9black>比例項目：</TD>
             <TD height="0" colspan="2" align=left class=p9black>
				<input type="radio" name="type1" value="0" checked="checked"/>
				數量
				<input type="radio" name="type1" value="1"/>
				瀏覽數
			</TD>
           </TR>-->
          <TR>
            <TD height="30" align=left class=p9black>&nbsp;</TD>
            <TD height="30" colspan="2" align=left class=p9black>
				<!--<input type="submit" value="送出結果" />
				<input type="button" value="Excel" onclick=""/>-->
				<input type="submit" value="送出結果"/>
				<input type="button" value="匯出Excel" onclick="javascript:location.href='admin_sale_hot_excel.php?<?php echo $_SERVER['QUERY_STRING']?>'" <?php if($_SERVER['QUERY_STRING']){}else{echo "disabled";} ?>/>
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
							  <th width="430" height="1" align="left" class="p9black">佔比(%) (商品銷售金額 / 銷售總金額：<?php echo $Total; ?>)</th>
							  <th width="138" align="left" class="p9black">
							  <?php
								if( isset($_GET['type']) ){
									if( $_GET['type'] == 0 )
										echo "銷售金額";
									else if( $_GET['type'] == 1 )
										echo "數量";
									else if( $_GET['type'] == 2 )
										echo "瀏覽數";
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
									$compare = floor(($Results[$i]['smtotal']/$Total) * 100);
							?>
							<tr>
								<td align="left" class="p9black"><?php echo $Results[$i]['index'];?></td>
								<td align="left" class="p9black"><?php echo $Results[$i]['goodsname'];?></td>
								<td align="left" class="p9black">
									<div id="item<?php echo $Results[$i]['index'];?>"></div>
								</td>
								<td align="left" class="p9black"><?php echo $Results[$i]['value'];?></td>
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
