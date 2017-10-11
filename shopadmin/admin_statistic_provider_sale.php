<?php
include_once "Check_Admin.php";
include_once Classes . "/GD_Drive.php";
include_once Classes . "/Time.class.php";
include_once Classes . "/SaleMap.class.php";

include_once Classes . "/Time.class.php";
include_once Classes . "/orderClass.php";
$TimeClass = new TimeClass;
$orderClass = new orderClass;
$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y")));
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
$times = $begtimeunix;
$current_year = date("Y",$times);
$current_month = date("m",$times);
if (intval($_GET['province'])>0 ){
	$subSql = " and ot.provider_id='" . intval($_GET['province']) . "'";
}
if (intval($_GET['provider_id'])>0){
	$subSql = " and ot.provider_id='" . intval($_GET['provider_id']) . "'";
}
if ($_GET['iftogether']=="1"){
	$subSql .= " and ot.iftogether=1";
}
if ($_GET['iftogether']=="0"){
	$subSql .= " and ot.iftogether=0";
}

$Sql = "select sum(od.cost*od.goodscount) as sumcost,od.*,sum(od.goodscount) as goodscounts,ot.order_id,ot.transport_price from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on oa.order_detail_id=od.order_detail_id or (oa.order_detail_id=0 and oa.order_id=od.order_id) inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=2 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . " group by od.gid,od.cost order by ot.order_id";
$d_Query    = $DB->query($Sql);

foreach($_GET as $k=>$v){
	$restring .= "&" . $k . "=" . $v;	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK id=css href="css/calendar.css" type='text/css' rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[TjFx];//统计分析?>--&gt;<?php echo $JsMenu[Sale];//销售统计?></title>
<style type="text/css">
<!--
.STYLE1 {color: #0000FF}
-->
</style>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
<SCRIPT language=JavaScript>
<!--
function MM_findObj(n, d) { //v4.0
  var p,i,x;
  if(!d) d=document;
  if((p=n.indexOf("?"))>0 && parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n];
  for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n);
  return x;
}
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
function toExcel(){
		document.adminForm.target ="_self";
			document.adminForm.action = "admin_statistic_provider_sale_excel.php?<?php echo $restring;?>";
			document.adminForm.submit();
}
function toPrint(){
	document.adminForm.target ="_blank";
			document.adminForm.action = "admin_statistic_provider_sale_print.php?<?php echo $restring;?>";
			document.adminForm.submit();
}
//-->
</SCRIPT>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php  include $Js_Top ;  ?>
<form action="" method="post"  name="adminForm">
</form>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[TjFx];//统计分析?>--&gt;<?php echo $JsMenu[Sale];//销售统计?></SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD align=middle>
                      <input type="button" name="button2" id="button2" value="下載" onClick="toExcel();">
                      <input type="button" name="button3" id="button3" value="列印" onClick="toPrint();">
                      </TD>
                    
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD align="center" vAlign=top bgColor=#ffffff><?php
                  if (intval($_GET['provider_id'])>0){
					$Provider_Search = " and provider_id='" . intval($_GET['provider_id']) . "'";
				  }
				  $Sql      = "select * from `{$INFO[DBPrefix]}provider` where 1=1 ".$Provider_Search." order by providerno desc  ";
				  $Query    = $DB->query($Sql);
				  $Rs=$DB->fetch_array($Query);
				  echo $Rs['providerno'] . $Rs['provider_name'];
				  ?>                      <?php echo $current_month;?>月對帳銷售表<br>
                      <br>
                      
                      <div style="display:block" id="showtable">
                        <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="allborder">
                          <TBODY>
                            <TR>
                              <TD bgColor=#ffffff><TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                                <TBODY>
                                  <TR align=middle>
                                    <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>商品編碼</TD>
                                    <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>品名</TD>
                                    <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>數量</TD>
                                    <TD width="186" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>單成本價</TD>
                                    <TD width="94" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>成本小計</TD>
                                    <TD width="77" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>備註</TD>
                                    </TR>
                                  <?php
							  $pay_total = 0;
								  while($d_Rs=$DB->fetch_array($d_Query)){
									  $pay_total += $d_Rs['sumcost'];
									  $order_id = $d_Rs['order_id'];
							  ?>
                                  <TR class=row0>
                                    <TD height=26 align="left" noWrap><?php echo $d_Rs['bn']?></TD>
                                    <TD height=26 align="left" noWrap><?php echo $d_Rs['goodsname']?></TD>
                                    
                                    <TD align=center nowrap><?php echo $d_Rs['goodscounts'];?></TD>
                                    <TD align=center nowrap><?php echo $d_Rs['cost']?></TD>
                                    <TD height=26 align=center nowrap><?php echo $d_Rs['sumcost']?></TD>
                                    <TD height=26 align=center nowrap><?php echo $d_Rs['receiver_memo ']?></TD>
                                    </TR>
                                  <?php
                                if ($order_id != $o_order_id){
								?>
                                  <TR class=row0>
                                    <TD height=26 align="left" noWrap></TD>
                                    <TD height=26 align="left" noWrap>運費</TD>
                                    
                                    <TD align=center nowrap>1</TD>
                                    <TD align=center nowrap><?php echo $d_Rs['transport_price']?></TD>
                                    <TD height=26 align=center nowrap><?php echo $d_Rs['transport_price']?></TD>
                                    <TD height=26 align=center nowrap></TD>
                                    </TR>
                                  <?php
								}
							  		$o_order_id = $order_id;
								}
								?>
                                  <TR>
                                    <TD width=122 height=14 nowrap>&nbsp;</TD>
                                    <TD width=296 height=14>&nbsp;</TD>
                                    <TD width=153>&nbsp;</TD>
                                    <TD width=186 height=14 align="center">(+a)銷售小計</TD>
                                    <TD height=14 align="center"><?php echo $pay_total;?></TD>
                                    <TD width=77 height=14>&nbsp;</TD>
                                    </TR>
                                  <TR>
                                    <TD height=14 colspan="6" nowrap>&nbsp;</TD>
                                    </TR>
                                  <TR>
                                    <TD height=14 nowrap>&nbsp;</TD>
                                    <TD height=14>&nbsp;</TD>
                                    <TD>&nbsp;</TD>
                                    <TD height="14" align="right">(+b)<?php echo $current_month;?>月物流運費總金額</TD>
                                    <TD height=14 align="center">
                                      <?php
                                $t_Sql = "select sum(ot.transport_price) as sumtrans from `{$INFO[DBPrefix]}order_action` oa inner join `{$INFO[DBPrefix]}order_detail` od on oa.order_detail_id=od.order_detail_id or (oa.order_detail_id=0 and oa.order_id=od.order_id) inner join `{$INFO[DBPrefix]}order_table` ot on ot.order_id=od.order_id  where oa.state_type=2 and oa.state_value=1 and oa.actiontime>='$begtimeunix' and oa.actiontime<='$endtimeunix' " . $subSql . " ";
								$t_Query    = $DB->query($t_Sql);
								$t_Rs=$DB->fetch_array($t_Query);
								echo $t_Rs['sumtrans'];
								?>
                                      </TD>
                                    <TD height=14>&nbsp;</TD>
                                    </TR>
                                  <TR>
                                    <TD height=14 nowrap>&nbsp;</TD>
                                    <TD height=14>&nbsp;</TD>
                                    <TD>&nbsp;</TD>
                                    <TD height="14" align="right">(a+b)合計</TD>
                                    <TD height=14 align="center"><?php echo  $t_Rs['sumtrans']+$pay_total;?></TD>
                                    <TD height=14>&nbsp;</TD>
                                    </TR>
                                  <?php
                              foreach($_GET as $k=>$v){
									$restring .= "&" . $k . "=" . $v;	
								}
								$Sql = "select * from `{$INFO[DBPrefix]}provider_month` where mid='" . intval($_GET['mid']) . "'";
									$Query    = $DB->query($Sql);
									$Rs=$DB->fetch_array($Query);
							  ?>
                                  <form action="admin_statistic_provider_save.php?acts=sale<?php echo $restring;?>" method="post">
                                    <TR>
                                      <TD height=14 nowrap>&nbsp;</TD>
                                      <TD height=14>&nbsp;</TD>
                                      <TD>&nbsp;</TD>
                                      <TD height="14" align="right">沖銷帳務</TD>
                                      <TD height=14 align="center"><?php echo $FUNCTIONS->Input_Box('text','zhang',$Rs['zhang'],"      maxLength=40 size=6 ")?></TD>
                                      <TD height=14><input type="submit" name="button" id="button" value="填寫"></TD>
                                      </TR>
                                    </form>
                                <TR>
                                  <TD height=14 nowrap>&nbsp;</TD>
                                  <TD height=14>&nbsp;</TD>
                                  <TD>&nbsp;</TD>
                                  <TD height="14" align="right">請款總金額</TD>
                                  <TD height=14 align="center">
                                    <?php 
								if (intval($_GET['mid'])>0){
									
									echo  $Rs['paymoney'];
								}else
									echo $t_Rs['sumtrans'];?></TD>
                                  <TD height=14>&nbsp;</TD>
                                  </TR>
                                </TABLE></TD>
                              </TR>
                      </TABLE></div></TD></TR>
                  <Tr>
                    <td align="right">&nbsp;</td></Tr>
                </TBODY></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
