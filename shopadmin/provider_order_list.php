<?php
session_start();
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Cart.php";

include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : $TimeClass->ForGetDate("Month","-6","Y-m-d");
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : $TimeClass->ForGetDate("Day","1","Y-m-d");

$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-");

if ($_GET['action']=='search') {
	if (trim(urldecode($_GET['skey']))!=$Basic_Command['InputKeyWord']   && trim($_GET['skey'])!=""){
		$Add_one = " and ".$_GET['type']."'".trim(urldecode($_GET['skey']))."' ";
	}

	if ($_GET['orderstate']!=''){
		$Add_two  =  " and  ot.order_state=".intval($_GET['orderstate'])." ";
	}

	if ($_GET['paystate']!=''){
		$Add_three  =  " and  ot.pay_state=".intval($_GET['paystate'])." ";
	}
	if ($_GET['shipstate']!=''){
		$Add_three  .=  " and  ot.transport_state=".intval($_GET['shipstate'])." ";
	}
	
	if ($_GET['Order_Tracks']=='Show'){
		$Add  .=  " and   ot.order007_status=1 and  ot.order007_begtime <= '".date("Y-m-d",time())."' ";
	}

	$Sql       = "select ot.* from   `{$INFO[DBPrefix]}order_table` ot  where ot.createtime>='$begtimeunix' and ot.createtime<='$endtimeunix' and ot.provider_id='".$_SESSION[sa_id]."' and ot.iftogether=0 and ot.pay_state=1 ".$Add_one."   ".$Add_two."   ".$Add_three."   " . $Add . "   ";
	$Sql      .= "  order by ot.order_id desc";
}else{
	if ($_GET['Order_Tracks']=='Show'){
		$Add  .=  " and   ot.order007_status=1 and  ot.order007_begtime <= '".date("Y-m-d",time())."' ";
	}
	//$Add  = $_GET['State']!="" ? str_replace("and","where",$Add)." and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' " :  " where o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' ";
	$Sql       = "select ot.* from  `{$INFO[DBPrefix]}order_table` ot where ot.createtime>='$begtimeunix' and ot.createtime<='$endtimeunix' and   ot.provider_id='".$_SESSION[sa_id]."' and ot.iftogether=0  " . $Add . " and ot.order_state<>0 and ot.order_state<>2 and ot.pay_state<>0 and ot.pay_state<>2 and ot.pay_state<>3   ";
	$Sql      .= "  order by ot.order_id desc";
}

//echo $Sql;

if ($_GET['ifpage']==1){
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if ($Num>0){
		$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
		$Nav->total_result=$Num;
		$Nav->execute($Sql,$limit);
		$Nums     = $Num<$limit ? $Num : $limit ;
		$Query    = $Nav->sql_result;
	}
}else{
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK id=css href="css/calendar.css" type='text/css' rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Order_List];//定单管理?>
</TITLE>
<script language="javascript" src="../js/TitleI.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
        /*****************************************************
         * 滑鼠hover變顏色
         ******************************************************/
$(document).ready(function() {
$("#orderedlist tbody tr").hover(function() {
		$(this).addClass("blue");
	}, function() {
		$(this).removeClass("blue");
	});
});
</script>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
 <TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center   border=0>
 <TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
	<? 

	include "desktop_title.php";

	?></TD>
  </TR>
  </TBODY>
 </TABLE>
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

function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3)
    if ((obj=MM_findObj(args[i]))!=null) {
	  v=args[i+2];
      if (obj.style) {
	    obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
      obj.visibility=v;
	}
}

function toPrint(){
    // javascript:print();
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Print_Select']?>')){
		    document.adminForm.method = "get";	
		    document.adminForm.action = "admin_order_more.php";
			document.adminForm.act.value="Print_Select";
			document.adminForm.target="_blank";
			//document.adminForm.Order_id.value="Order_id";			
			document.adminForm.submit();
		}
	}

}
function toTrans(){
    // javascript:print();
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('您確認要打印出貨明細嗎？')){
		    document.adminForm.method = "get";	
		    document.adminForm.action = "admin_order_trans.php";
			document.adminForm.act.value="Print_Select";
			document.adminForm.target="_blank";
			//document.adminForm.Order_id.value="Order_id";			
			document.adminForm.submit();
		}
	}

}
function toExcel(id){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		//if (confirm('<?php echo $Basic_Command['Print_Select']?>')){
		    document.adminForm.method = "post";	
		    document.adminForm.action = "provider_order_excel.php";
			//document.adminForm.Order_id.value="Order_id";	
			//document.adminForm.target="_blank";
			document.adminForm.submit();
		//}
	}
}
//-->
</SCRIPT>
<div id="contain_out">
  <?
    include "Order_state.php";
	?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Order_List];//定单管理?>
                      </SPAN>
                      </TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <?php if (intval($_GET[orderstate])==2 || intval($_GET[orderstate])=="3" || intval($_GET['paystate'])=='1') {?>            
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                                    <a href="javascript:toPrint();"><IMG  src="images/<?php echo $INFO[IS]?>/fb-print.gif" width="32" height="29"  border=0>&nbsp;<?php echo $Basic_Command['Print_order'];//訂單列印?></a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE>
                            <!--BUTTON_END--></TD>
                          </TR>
                        </TBODY>
                      </TABLE>                </TD>
                  <?php } ?>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                                    <a href="javascript:toTrans();"><IMG  src="images/<?php echo $INFO[IS]?>/fb-print.gif" width="32" height="29"  border=0>&nbsp;出貨明細</a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE>
                            <!--BUTTON_END--></TD>
                          </TR>
                        </TBODY>
                      </TABLE>                </TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                                    <a href="javascript:toExcel();"><IMG  src="images/<?php echo $INFO[IS]?>/fb-print.gif" width="32" height="29"  border=0>&nbsp;導出訂單</a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE>
                            <!--BUTTON_END--></TD>
                          </TR>
                        </TBODY>
                      </TABLE>                </TD>
                  </TR>
                </TBODY>
              </TABLE>
              
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      
      <FORM name=optForm method=get action="" onSubmit="return searchsubmit('<?php echo $Order_Pack[DateError_i]?>','<?php echo $Order_Pack[DateError_ii]?>','<?php echo $Order_Pack[DateError_iii]?>');">        
        <input type="hidden" name="action" value="search">
        <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
          
          <TR>
            <TD align=left colSpan=2 height=31>
              
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width='100%' border=0>
                <TBODY>
                  <TR>
                    <TD width="681" height=31 align=left nowrap>
                      <INPUT class='inputstyle'   onmouseover=this.focus() onfocus=this.select()  onclick="if(this.value=='<?php echo $Basic_Command['InputKeyWord'] ;?>') this.value=''"  value='<?php echo $Basic_Command['InputKeyWord'] ;//输入关键字?>'   size=15  name=skey>
                      &nbsp;
                      <SELECT name=type>
                        <OPTION    value="ot.order_serial =" <?php if ($_GET['type']=='o.order_serial =') echo " selected ";?>><?php echo $Order_Pack[OrderSerial_say];//订单号?></OPTION>
                        <OPTION    value="ot.totalprice <" <?php if ($_GET['type']=='o.totalprice <') echo " selected ";?>><?php echo $Order_Pack[S_Szje];//＜（总金额）?></OPTION>
                        <OPTION    value="ot.totalprice >" <?php if ($_GET['type']=='o.totalprice >') echo " selected ";?>><?php echo $Order_Pack[S_Lzje];//＞（总金额）?></OPTION>
                        <OPTION    value="ot.totalprice =" <?php if ($_GET['type']=='o.totalprice =') echo " selected ";?>><?php echo $Order_Pack[S_Gzje];//＝（总金额）?></OPTION>
                        </SELECT>
                      &nbsp;
                      <SELECT  name='orderstate' class="trans-input">
                        <OPTION value="">- <?php echo $Order_Pack[OrderState_say];//订单状态?> -</OPTION>
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
                      <SELECT name='paystate' class="trans-input">
                        <OPTION value="" >- <?php echo $Order_Pack[OrderPayState_say] ;//支付状态?> -</OPTION>
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
                      <SELECT name='shipstate' class="trans-input">
                        <OPTION value="" >- 配送狀態 -</OPTION>
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
                      From
                      <INPUT   id=begtime3 size=10 value=<?php echo $begtime?>    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />
                      To
                      <INPUT    id=endtime3 size=10 value=<?php echo $endtime?>      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime /></TD>
                    <TD width="100" align=left nowrap><input name="ifpage" type="checkbox" value="1" <?php if($_GET['ifpage']==1) echo "checked";?>>分頁顯示</TD>
                    <TD width=135 height=31 vAlign=center nowrap class=p9black>
                      <INPUT  type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name='imageField' />&nbsp; 
                      
                      
                      
                    </TD>
                    <TD width=2 height=31 vAlign=center nowrap class=p9black> </TD>
                  </TR>
                </TBODY>
                </TABLE></TD>
            <TD width=14% height=31 align=right nowrap class=p9black><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=p9black onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
              </TD>
            </TR>
          
          </TABLE>	
        
        </form>
      
      
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                      <FORM name="adminForm" action="" method="post">
                        <INPUT type=hidden name=Order_id>
                        <INPUT type=hidden name=act>
                        <input type='hidden' name="Url" value="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>">
                        <INPUT type=hidden value=0  name=boxchecked> 
                        <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                          <TBODY>
                            <TR align=middle>
                              <TD width="4%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle>
                                </TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[OrderSerial_say];//订单号?></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Order_Pack[OrderCreatetime_say];//下单日期?></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>訂單總金額</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>配送費用</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>優惠後總金額</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>付款方式</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>收貨人</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Cart[Order_state_say] ;//订单状态?> </TD>
                              </TR>
                            <?php	           
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
						$Order_serial = $Rs['order_serial'];
						$Order_Time   = date("Y-m-d H: i a ",$Rs['createtime']);
						$Order_state  = $Rs['order_state'];
					?>
                            <TR class=row0>
                              <TD align=middle height=20>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['order_id']?>' name=cid[]>
                                </TD>
                              <TD height=20 align="left" noWrap><a href="provider_order.php?Action=Modi&order_id=<?php echo $Rs['order_id']?>"><?php echo $Rs['order_serial']?></a></TD>
                              <TD height=20 align="left" noWrap><?php echo date("Y-m-d",$Rs['createtime'])?></TD>
                              <TD height=20 align="left" noWrap><?php echo $Rs['totalprice']+$Rs[transport_price]?>&nbsp;							  </TD>
                              <TD height=20 align="center" noWrap>
                                <?php echo $Rs['transport_price']?></TD>
                              <TD align="center" noWrap><?php echo $Rs['discount_totalPrices']+$Rs[transport_price]?></TD>
                              <TD height=20 align="center" noWrap><?php echo $Rs['paymentname']?></TD>
                              <TD height=20 align="center" noWrap><?php echo $Rs['receiver_name']?></TD>
                              <TD height=20 align=center nowrap><?php echo  $orderClass->getOrderState($Rs['order_state'],1)?>,<?php echo $orderClass->getOrderState(intval($Rs['pay_state']),2)  ?>,<?php echo $orderClass->getOrderState(intval($Rs['transport_state']),3) ?>&nbsp;</TD>
                              </TR>
                            <?php
					$i++;
					}
					?>
                            <TR>
                              <TD align=middle height=14>&nbsp;</TD>
                              <TD height=14 colspan="2">&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              
                              </TR>
                            
                            </TABLE>
                        </form>
                      </TD>
                    </TR>
                  </TABLE>
              <?php if ($Num>0){ ?>   
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                      </TD>
                    </TR>
                  </TABLE>
              <?php } ?>
        </TD></TR></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
