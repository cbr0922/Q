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
$starttime = mktime(0,0,0,$m,$d,$y);
$endtime = mktime(0,0,0,$m,$d,$y);
$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",$starttime);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",$endtime);
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
if ($_GET['orderstate']!=''){
	$Add  =  " and  ot.order_state=".intval($_GET['orderstate'])." ";
}
if ($_GET['paystate']!=''){
	$Add  .=  " and  ot.pay_state=".intval($_GET['paystate'])." ";
}
if ($_GET['shipstate']!=''){
	$Add  .=  " and  ot.transport_state=".intval($_GET['shipstate'])." ";
}

/************************************
 *
 * 	網頁 / 手機 / 全部購買訂單筆數(長條圖，最近6個月)
 *
 ************************************/
/*
$pc = $phone = $totle1 = 0;
$Sql = "select COUNT(*) as totle,`ifmobile`";
$Sql .= " from `{$INFO[DBPrefix]}order_table` as ot";
$Sql .=	" where ot.createtime>='" . $begtimeunix . "' and ot.createtime<='" . $endtimeunix . "'".$Add." GROUP BY `ifmobile`";
//echo $Sql;
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if($value['ifmobile']==0){
			$pc = $value['totle'];
		}else{
			$phone = $value['totle'];
		}
	}
	$totle1=$pc+$phone;
}
*/
/************************************
 *
 * 	付款方式統計(訂單數)
 *
 ************************************/
$paymentname;
$Sql = "select methodname FROM `{$INFO[DBPrefix]}paymethod` WHERE ifopen='1'";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
 while($value = $DB->fetch_array($Query)){
	 $paymentname[$value['methodname']] = 0;
 }
}
$totle2 = 0;
$Sql = "select `paymentname`,COUNT(*) as totle FROM `{$INFO[DBPrefix]}order_table` as ot WHERE ot.createtime>='" . $begtimeunix . "' and ot.createtime<='" . $endtimeunix . "'".$Add." GROUP BY `paymentname`";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if($value['paymentname'] != NULL){
			$paymentname[$value['paymentname']] = $value['totle'];
			$totle2+=$value['totle'];
		}
	}
}
/************************************
 *
 * 	銷售金額
 *
 ************************************/
$Sql = "select sum(od.goodscount*od.price) as smtotal,sum(od.goodscount) as sntotal,od.goodsname,od.gid,od.order_id,od.packgid";//,og.goodsname goodsname1,od.mix2gid, og.bid, oc.top_id";
$Sql .=	" from `{$INFO[DBPrefix]}order_detail` as od";
$Sql .=	" inner join `{$INFO[DBPrefix]}order_table` as ot on od.order_id=ot.order_id";
//$Sql .=	" left join `{$INFO[DBPrefix]}goods_mix` as gm on od.gid=gm.mixgid";
//$Sql .=	" left join `{$INFO[DBPrefix]}goods` as og on od.mix2gid=og.gid";
//$Sql .=	" left join `{$INFO[DBPrefix]}bclass` as oc on og.bid=oc.bid";
$Sql .=	" where ot.createtime>='" . $begtimeunix . "' and ot.createtime<='" . $endtimeunix . "'".$Add;
/*if( $_GET['top_id'] != 0 && isset($_GET['top_id']) )
	$Sql .=	" and oc.top_id=" . $_GET['top_id'];*/
$Sql .=	" group by od.gid,od.packgid";//,od.mix2gid
$Sql .=	" order by od.packgid asc";
//echo $Sql;
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$Total_sm = 0;
$Total_sn = 0;
$Results_count = 0;
$Results;
$good = array();
$Total = 0;
if ($Num>0){
	while($value = $DB->fetch_array($Query)){
		if($value['packgid']==0){
			$good[$value['gid']][$value['order_id']] = $Results_count;
			$Field['goodsname'] = $value['goodsname'];
			/*if($value['mix2gid']==0){
				$Field['goodsname'] = $value['goodsname'];
			}else{
				$Field['goodsname'] = $value['goodsname1'].$value['goodsname'];
			}*/
			$Field['gid'] = $value['gid'];
			$Field['link'] = $_SERVER['QUERY_STRING'] == "" ? $_SERVER['REQUEST_URI']."?gid=".$value['gid'] : $_SERVER['REQUEST_URI']."&gid=".$value['gid'];
	    $Field['smtotal'] = $value['smtotal'];
	    $Field['sntotal'] = $value['sntotal'];
			$Total_sm =  $Total_sm + $value['smtotal'];
	    $Total_sn =  $Total_sn + $value['sntotal'];
			$Results[$Results_count] = $Field;
			$Results_count++;
		}else{
			//$Results[$good[$value['packgid']][$value['order_id']]]['goodsname'] .= "<br>" . $value['goodsname'] . "[組合商品]";
		}
	}
}

if(isset($_GET['gid']) && intval($_GET['gid'])>0){
	$Sql = "select ot.order_serial,ot.createtime,od.price,od.goodscount,od.goodsname,od.gid,od.order_id,od.packgid";//,og.goodsname goodsname1,od.mix2gid
	$Sql .=	" from `{$INFO[DBPrefix]}order_detail` as od";
	$Sql .=	" inner join `{$INFO[DBPrefix]}order_table` as ot on od.order_id=ot.order_id";
	//$Sql .=	" left join `{$INFO[DBPrefix]}goods` as og on od.mix2gid=og.gid";
	$Sql .=	" where ot.createtime>='" . $begtimeunix . "' and ot.createtime<='" . $endtimeunix . "'".$Add." and od.gid='".$_GET['gid']."'";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$Results_count1 = 0;
	$Results1;//echo $Sql;
	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			if($value['packgid']==0){
				$Field['goodsname'] = $value['goodsname'];
				/*if($value['mix2gid']==0){
					$Field['goodsname'] = $value['goodsname'];
				}else{
					$Field['goodsname'] = $value['goodsname1'].$value['goodsname'];
				}*/
				$Field['order_id'] = $value['order_id'];
				$Field['order_serial'] = $value['order_serial'];
				$Field['createtime'] = date("Y-m-d h:m:s",$value['createtime']);
				$Field['price'] = $value['price'];
				$Field['smtotal'] = $value['price']*$value['goodscount'].".00";
				$Field['sntotal'] = $value['goodscount'];
				$Total_sm1 =  $Total_sm1 + $Field['smtotal'];
				$Total_sn1 =  $Total_sn1 + $Field['sntotal'];
				$Results1[$Results_count1] = $Field;
				$Results_count1++;
			}
		}
		$Total_avg1 = round($Total_sm1/$Total_sn1).".00";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>行銷工具--&gt;統計分析--&gt; 即時統計分析</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<LINK href="../css/theme.css" type=text/css rel=stylesheet><!--主選單樣式-->

<LINK href="../css/css.css" type=text/css rel=stylesheet>

<LINK href="../css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet><!--font icon-->

<LINK href="../css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet><!--font icon-->

<LINK href="../css/title_style.css" type=text/css rel=stylesheet>

<LINK id=css href="../css/calendar.css" type='text/css' rel=stylesheet>

<link rel="stylesheet" type="text/css" href="../js/css/easyui.css">

<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>

<script language="javascript" src="../js/TitleI.js"></script>

<SCRIPT src="../js/common.js" language="javascript"></SCRIPT>

<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>

<script language="javascript" type="text/javascript" src="../js/show_dialog.js"></script>

<script type="text/javascript" src="../js/alter.js"></script>

<script language="javascript" src="../js/jquery.easyui.min.js"></script>

<script type="text/javascript" src="../js/jquery/jquery.sticky.js"></script><!--頭部位置固定-->

<SCRIPT src="../js/calendar.js" language="javascript"></SCRIPT>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
<style>
/* REQUIRED CSS: change your reflow breakpoint here (35em below)
@media ( max-width: 35em ) {*/
<?php
$ua = $_SERVER["HTTP_USER_AGENT"];
$android = strpos($ua, 'Android') ? true : false;
$blackberry = strpos($ua, 'BlackBerry') ? true : false;
$iphone = strpos($ua, 'iPhone') ? true : false;
$palm = strpos($ua, 'Palm') ? true : false;
if ($android || $blackberry || $iphone || $palm) { ?>
  .ui-table-reflow td,
  .ui-table-reflow th {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    float: right;
		font-size: 3em;
    /* if not using the stickyHeaders widget (not the css3 version)
     * the "!important" flag, and "height: auto" can be removed */
    width: 100% !important;
    height: auto !important;
  }
  /* reflow widget
  .ui-table-reflow tbody td[data-title]:before {
    color: #469;
    font-size: .9em;
    content: attr(data-title);
    float: left;
    width: 10%;
    white-space: pre-wrap;
    text-align: bottom;
    display: inline-block;
  }*/
  /* allow toggle of thead */
  thead.hide-header {
    display: none;
  }
/*}*/
.ui-table-reflow .ui-table-cell-label {
  display: none;
}
.tablesorter-header-inner{
	font-size: 2.5em;
	height: 50px;
	line-height: 50px;
}
<?php } ?>
a{font-size:16px !important}
@media only screen and (min-width:1025px) {
table.size tr td a,table.size tr td{
	font-size: 20px !important;
	line-height: 25px;
}
table.size tr th div{
	font-size: 20px !important;
	line-height: 25px;
}
table.size1 tr td a,table.size1 tr td{
	font-size: 20px !important;
	line-height: 25px;
}
select,.box1,.box2,input,input:focus{
	width:150px;
	font-size: 16px;
    border-width: 1px;
    padding: 0 3px;

}

}
@media only screen and (min-width:769px) and (max-width:1024px) {
table.size tr td a,table.size tr td{
	font-size: 30px !important;
	line-height: 40px;
}
table.size1 tr td a,table.size1 tr td{
	font-size: 40px !important;
	line-height: 40px;
}
table.size tr th div{
	font-size: 32px !important;
	line-height: 40px;
}
select,.box1,.box2,input,input:focus{
	font-size: 30px;
    border-width: 1px;
    height: 50px !important;
	line-height:50px;
    padding: 0 3px;
	width:200px;
}
}
@media only screen and (min-width:240px) and (max-width:768px) {
table.size tr td a,table.size tr td{
	font-size: 35px !important;
	line-height: 40px;
}
table.size1 tr td a,table.size1 tr td{
	font-size: 40px !important;
	line-height: 40px;
}
table.size tr th div{
	font-size: 35px !important;
	line-height: 40px;
}
select,.box1,.box2,input,input:focus{
	font-size: 30px;
    border-width: 1px;
    height: 50px !important;
	line-height:50px;
    padding: 0 3px;
	width:200px;
}
}
</style>
<div id="contain_out">
  <?php  //include_once "Order_state.php";?>
      <!--TABLE class=p9black1 cellSpacing=0 cellPadding=0 width="100%" border=0>
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
  </TABLE-->

  <table class="" style="margin-bottom:10px" width="100%">
  <tr>
  <td>
<TABLE class="size1 p12blakc" cellSpacing=0 cellPadding=0 width="100%"   align=center border=0 >
          <FORM name=form1 method=get>
          <input type="hidden" name="act" value="<?php echo $_GET[act]?>">
          <TR>
            <TD height=19 colspan="3" align=right>		 </TD>
            </TR>
          <TR>
            <TD width="25%" align=right class="p9black1" style="padding:10px 0">日期：</TD>
            <TD width="60%" colspan="2" align=left class="p9black1" style="padding:10px 0">
				<?php echo $Visit_Packs[VisFrom];//从?>
              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id='begtime' size=10  onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $begtime;?>' name='begtime'>
              -
              <?php echo $Visit_Packs[VisFrom];//从?>
              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id='endtime' size=10   onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $endtime;?>' name='endtime'>
            </TD>
			<td width="15%" >&nbsp;</td>
            </TR>
						<TR>
							<TD width="25%" align=right class="p9black1" style="padding:10px 0">訂單狀態：</TD>
							<TD width="60%" colspan="2" align=left class="p9black1" style="padding:10px 0">
						<SELECT  name='orderstate' class="trans-input">
							<OPTION value="">- 請選擇 -</OPTION>
							<?php
							$s_Sql = "select * from `{$INFO[DBPrefix]}order_state` where state_type=1";
							$Query_s    = $DB->query($s_Sql);
							while ($Rs_s=$DB->fetch_array($Query_s)) {
								?>
								<OPTION value=<?php echo $Rs_s['state_value'];?> <?php if ($_GET['orderstate']==$Rs_s['state_value']) echo " selected ";?>><?php echo $Rs_s[order_state_name];?></OPTION>
								<?php
							}
							?>
						</SELECT>
					</TD>
					<td width="15%" >&nbsp;</td>
				</TR>
				<TR>
					<TD width="25%" align=right class="p9black1" style="padding:10px 0">支付狀態：</TD>
					<TD width="60%" colspan="2" align=left class="p9black1" style="padding:10px 0">
						<SELECT name='paystate' class="trans-input">
							<OPTION value="" >- 請選擇 -</OPTION>
							<?php
							$s_Sql = "select * from `{$INFO[DBPrefix]}order_state` where state_type=2";
							$Query_s    = $DB->query($s_Sql);
							while ($Rs_s=$DB->fetch_array($Query_s)) {
								?>
								<OPTION value=<?php echo $Rs_s['state_value'];?> <?php if ($_GET['paystate']==$Rs_s['state_value']) echo " selected ";?>><?php echo $Rs_s[order_state_name];?></OPTION>
								<?php
							}
							?>
						</SELECT>
					 </TD>
					 <td width="15%" >&nbsp;</td>
					</TR>
					<TR>
						<TD width="25%" align=right class="p9black1" style="padding:10px 0">配送狀態：</TD>
						<TD width="60%" colspan="2" align=left class="p9black1" style="padding:10px 0">
						<SELECT name='shipstate' class="trans-input" >
							<OPTION value="" >- 請選擇 -</OPTION>
							<?php
							$s_Sql = "select * from `{$INFO[DBPrefix]}order_state` where state_type=3";
							$Query_s    = $DB->query($s_Sql);
							while ($Rs_s=$DB->fetch_array($Query_s)) {
								?>
								<OPTION value=<?php echo $Rs_s['state_value'];?> <?php if ($_GET['shipstate']==$Rs_s['state_value']) echo " selected ";?>><?php echo $Rs_s[order_state_name];?></OPTION>
								<?php
							}
							?>
						</SELECT>
					</TD>
					<td width="15%" >&nbsp;</td>
				 </TR>
        <!--  <TR>
            <TD height="30" align=left class=p9black1>商品分類：</TD>
            <TD height="0" colspan="2" align=left class=p9black1>


			<?php //echo  $Char_class->get_page_select("top_id",$_GET[top_id],"  class=\"trans-input\" ");?>

            </TR>
            <TR>
             <TD height="30" align=left class=p9black1>最大筆數：</TD>
             <TD height="0" colspan="2" align=left class=p9black1>
				<input type="text" name="count" value="100" onkeyup="value=value.replace(/[^-_0-9]/g,'')" onfocusout="value=(value=='')?'100':value"/>
			</TD>
           </TR>
           <TR>
             <TD height="30" align=left class=p9black1>查詢種類：</TD>
             <TD height="0" colspan="2" align=left class=p9black1>
				<input type="radio" name="type" value="0" checked="checked"/>
				銷售金額
				<input type="radio" name="type" value="1"/>
				數量
			</TD>
    </TR> -->
			<?php if(isset($_GET['gid']) && intval($_GET['gid'])>0){ ?>
					<TR>
						<TD height="30" align=right class=p9black1>商品ID：</TD>
						<TD height="30" align=left class=p9black1><input type="text" name="gid" value="<?php echo $_GET['gid'];?>" onkeyup="value=value.replace(/[^-_0-9]/g,'')" style="width:100px;"/></TD>
					</TR>
			<?php	}?>
          <TR>
            <TD height="30" align=left class=p9black1>&nbsp;</TD>
            <TD height="30" colspan="2" align=left class=p9black1 style="padding-top:10px"><input type="submit" value="送出結果"/></TD>
          </TR>
          <TR>
            <TD height="19" colspan="3" align=left class=p9black1>&nbsp;</TD>
          </TR>
          </FORM>
        </TABLE>

</td>
        </tr>
        </table>
<!--
<hr>
<table class="" style="margin-bottom:10px" width="300px;">
		<TR>
			<TD width="100" align=left valign=top rowspan="2" class=p9black1>電腦 / 手機：</TD>
			<TD width="100" align=left class=p9black1>電腦：</TD>
			<TD width="30" align=left class=p9black1><?php echo $pc;?></TD>
			<TD width="70" align=left class=p9black1><?php echo " / ".(round($pc/$totle1, 2)*100)."%";?></TD>
		</TR>
		<TR>
			<TD width="100" align=left class=p9black1>手機：</TD>
			<TD width="30" align=left class=p9black1><?php echo $phone;?></TD>
			<TD width="70" align=left class=p9black1><?php echo " / ".(round($phone/$totle1, 2)*100)."%";?></TD>
		</TR>
</table>
-->
<?php if(!isset($_GET['gid']) || intval($_GET['gid'])==0){ ?>
<hr>
<table class="size1" style="margin-bottom:10px" >
		<?php	$i=0; foreach($paymentname as $k => $v ){?>
		<TR>
			<?php if($i==0) echo "<TD width='13%' align=left valign=top rowspan='".count($paymentname)."' class=p9black1>金流：</TD>";?>
			<TD width="30%" align=left class=p9black1><?php echo $k."：";?></TD>
			<TD width="10%" align=left class=p9black1><?php echo $v;?></TD>
			<TD width="17%" align=left class=p9black1><?php echo " / ".(round($v/$totle2, 2)*100)."%";?></TD>
			<td width="28%" >&nbsp;</td>
		</TR>
		<?php	$i++; } ?>
</table>
<hr>
<table class="size1" style="margin-bottom:10px">
	<TR>
		<TD width="20%" align=left valign=top rowspan="2" class=p9black1>訂單量：</TD>
		<TD width="20%" align=left class=p9black1><?php echo $totle2." 張";?></TD>
		<TD width="20%" align=left class=p9black1>&nbsp;</TD>
		<TD width="40%" align=left class=p9black1>&nbsp;</TD>
	</TR>
</table>
<?php	}?>
<hr>
<div>
<TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="size">
        <TBODY>
          <TR>
            <TD vAlign=top height=210><table class="p12black" cellspacing="0" cellpadding="0" width="100%"   align="center" border="0" >
              <form name="form2" method="post">
                <input type="hidden" name="Action2" value="Search" />
					<?php if(!isset($_GET['gid']) || intval($_GET['gid'])==0){ ?>
                <tr>
                  <td height="19" colspan="4" align="center">
					  <table id="itemTable" class="tablesorter">
						<thead>
							<tr style="height: 60px;">
							  <th width="70%" height="1" align="left" class="p9black1">商品名稱</th>
							  <th width="10%" align="left" class="p9black1">數量</th>
							  <th width="20%" align="left" class="p9black1">銷售金額</th>

							</tr>
						</thead>
						<tbody id="listBox">
							<script src="progressbar/jquery-ui.js"></script>
							<?php
								for($i=0; $i<$Results_count; $i++){
							?>
							<tr>
								<td align="left" class="p9black1"><a href="<?php echo $Results[$i]['link'];?>"><?php echo $Results[$i]['goodsname'];?></a></td>
								<td align="left" class="p9black1"><?php echo $Results[$i]['sntotal'];?></td>
                <td align="left" class="p9black1"><?php echo $Results[$i]['smtotal'];?></td>
							</tr>
							<?php
								}
							?>
						</tbody>
						<tbody>
              <tr>
                <td style="background-color: #aaa;" align="left" class="p9black1"><?php echo 銷售總金額;?></td>
                <td style="background-color: #aaa;" align="left" class="p9black1"><?php echo $Total_sn;?></td>
                <td style="background-color: #aaa;" align="left" class="p9black1"><?php echo $Total_sm;?>.00</td>
              </tr>
						</tbody>
					</table>
                  </td>
                </tr>
						<?php }else{ ?>
                <tr>
                  <td height="19" colspan="4" align="left" class="p9black1"><?php echo "商品名稱：".$Results1[0]['goodsname'];?></td>
                </tr>
								<tr>
                  <td height="19" colspan="4" align="center">
					  <table id="itemTable1" class="tablesorter">
						<thead>
							<tr style="height: 60px;">
							  <th width="20%" align="left" class="p9black1">訂單編號</th>
								<th width="20%" align="left" class="p9black1">下單時間</th>
								<th width="20%" align="left" class="p9black1">單價</th>
							  <th width="20%" align="left" class="p9black1">數量</th>
                <th width="20%" align="left" class="p9black1">金額</th>
							</tr>
						</thead>
						<tbody id="listBox">
							<script src="progressbar/jquery-ui.js"></script>
							<?php
								for($i=0; $i<$Results_count1; $i++){
							?>
							<tr>
								<td align="left" class="p9black1"><a href="javascript:void(window.open('../shopadmin/admin_order.php?Action=Modi&order_id=<?php echo $Results1[$i]['order_id'];?>'));"><?php echo $Results1[$i]['order_serial'];?></a></td>
								<td align="left" class="p9black1"><?php echo $Results1[$i]['createtime'];?></td>
								<td align="left" class="p9black1"><?php echo $Results1[$i]['price'];?></td>
								<td align="left" class="p9black1"><?php echo $Results1[$i]['sntotal'];?></td>
                <td align="left" class="p9black1"><?php echo $Results1[$i]['smtotal'];?></td>
							</tr>
							<?php
								}
							?>
						</tbody>
						<tbody>
              <tr>
                <td style="background-color: #aaa;" align="left" class="p9black1" colspan="2"><?php echo 平均單價、銷售總金額;?></td>
								<td style="background-color: #aaa;" align="left" class="p9black1"><?php echo $Total_avg1;?></td>
                <td style="background-color: #aaa;" align="left" class="p9black1"><?php echo $Total_sn1;?></td>
                <td style="background-color: #aaa;" align="left" class="p9black1"><?php echo $Total_sm1;?>.00</td>
              </tr>
						</tbody>
					</table>
                  </td>
                </tr>
						<?php } ?>
                <tr>
                  <td height="19" colspan="4" align="left" class="p9black1">&nbsp;</td>
                </tr>
              </form>
            </table></TD>
        </TR></TABLE>
		</div>
</div>

<!-- Tablesorter: required -->
<link rel="stylesheet" href="tablesorter2/theme.blue.css">
<script src="tablesorter2/jquery.tablesorter-2.26.4.js"></script>
<script src="tablesorter2/jquery.tablesorter.widgets.js"></script>


<link rel="stylesheet" href="progressbar/jquery-ui.css">

<script language="javascript">

$(document).ready(function(){
    $("#itemTable,#itemTable1").tablesorter({
		theme: 'blue',
		widgets: ['zebra', 'reflow'],
		widgetOptions : {
			// class name added to make it responsive (class name within media query)
			reflow_className    : 'ui-table-reflow',
			// header attribute containing modified header name
			reflow_headerAttrib : 'data-name',
			// data attribute added to each tbody cell
			// it contains the header cell text, visible upon reflow
			reflow_dataAttrib   : 'data-title'
		}
	});

});

</script>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
