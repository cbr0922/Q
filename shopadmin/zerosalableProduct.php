<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/ProductVisit_Pack.php";
//$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()) ;
$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y",time())."-01-01" ;
$Endtime = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time()) ;


include_once "pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

switch (trim($_GET[act])){
case "sale":
    $title   =$ProductVisit_Packs[ZerosalableProduct];//商品銷售排行
	$Sql     = " SELECT gid,goodsname from  `{$INFO[DBPrefix]}goods` where salenum=0 order by gid desc ";
break;

default:
    $title   =$ProductVisit_Packs[ZerosalableProduct];//商品銷售排行
	$Sql     = " SELECT gid,goodsname from  `{$INFO[DBPrefix]}goods` where salenum=0 order by gid desc ";
break;

}
$Sql = "select sum(od.goodscount*od.price) as smtotal,sum(od.goodscount) as sntotal,g.goodsname,g.gid from `{$INFO[DBPrefix]}goods`  g left join `{$INFO[DBPrefix]}order_detail` as od on g.gid=od.gid left join `{$INFO[DBPrefix]}order_table` as ot on (od.order_id=ot.order_id and ot.createtime>='" . $begtimeunix . "' and ot.createtime<='" . $endtimeunix . "' and od.detail_pay_state=1) group by g.gid having sntotal=0 or sntotal is null order by sntotal asc,g.gid";


$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 50  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--&gt;<?php echo $title ?>
</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[ProductVisit];//商品統計?>--&gt;<?php echo $title ?></SPAN></TD>
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
                              <TABLE>
                                <TBODY>
                                  <TR>
                                    <TD vAlign=bottom noWrap class="link_buttom">
                                      <a href="productVisit.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Return'];//返回?></a></TD>
                                    </TR>
                                  </TBODY>
                                </TABLE>
                              <!--BUTTON_END--></TD>
                          </TR></TBODY></TABLE>
                      
                      </TD></TR></TBODY></TABLE></TD></TR>
          </TBODY>
        </TABLE><TABLE cellSpacing=0 cellPadding=0 width="100%" align=center class="allborder">
                        <TBODY>
                          <TR align="center">
                            <TD height="300" valign="top">
                              
                              
                              <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                                <TBODY>
                                  <TR align=middle class=row1>
                                    <TD width="100"  align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Basic_Command['SNo_say']?></TD>
                                    <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $ProductVisit_Packs[Product_Name] ?></TD>
                                    <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>&nbsp;</TD>
                                    <TD width="80" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>&nbsp;</TD>
                                    </TR>
                                  <?php
	             			if ($Num>0){
	             				$i=1;
	             				$TotalNum = 0;
	             				while ($Result = $DB->fetch_array($Query)){
						    ?>   
                                  <TR class=row0>
                                    <TD width="100" height=20 align=center><?php echo $i?></TD>
                                    <TD height=20 align="left" noWrap><?php echo $Result['goodsname'];?></TD>
                                    <TD height=20 align="left" noWrap>&nbsp;</TD>
                                    <TD height=20 align="center" noWrap>&nbsp;</TD>
                                    </TR>
                                  <?php
   						    $TotalNum = $TotalNum+$Result['totalvisit'];
   						    $i++;
	             				}
						    ?>
                                  <?php
	             			}else{
						    ?>
                                  <TR class=row1>
                                    <TD height=20 colspan="4" align=center class="p9orange"><?php echo $Visit_Packs[NoVisit_Say];//没有参与统计的资料?></TD>
                                    </TR>
                                  <?php
	             			}
						    ?>
                                  </TABLE>
                              
                              
                              <?php if ($Num>0){ ?>
                              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                                <TBODY>
                                  <TR>
                                    <TD vAlign=center align=right background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                                      <?php echo $Nav->pagenav()?>
                                      </TD>
                                    </TR>
                                  </TABLE>
                              <?php } ?>
                              </TD>
                            </TR>
                        </TBODY></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
