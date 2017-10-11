<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/ProductVisit_Pack.php";

include_once "pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);


//$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()) ;
$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y",time())."-01-01" ;
$Endtime = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time()) ;
$companyid = intval($_GET['company']);
if ($companyid <= 0 ){
	$Sql      = "select u.*  from `{$INFO[DBPrefix]}company` u order by u.id asc limit 0,1";
	$Query    = $DB->query($Sql);
	$Rs=$DB->fetch_array($Query);
	$companyid = $Rs['id'];
}
/*
switch (trim($_GET[act])){
case "sale":
    $title   =$ProductVisit_Packs[ProductSaleSort];//商品銷售排行
	$Sql     = " SELECT sum( s.salemoney ) AS smtotal, sum( s.salenum ) AS sntotal , s.gid,g.goodsname FROM `{$INFO[DBPrefix]}saletotal` s  inner join   `{$INFO[DBPrefix]}goods`  g  ON ( g.gid = s.gid )  WHERE s.saledate > '".$Begtime."' AND  s.saledate <= '".$Endtime."'  GROUP BY s.gid order by smtotal desc ";
break;

default:
    $title   =$ProductVisit_Packs[ProductSaleSort];//商品銷售排行
	$Sql     = " SELECT sum( s.salemoney ) AS smtotal, sum( s.salenum ) AS sntotal , s.gid,g.goodsname FROM `{$INFO[DBPrefix]}saletotal` s  inner join   `{$INFO[DBPrefix]}goods`  g  ON ( g.gid = s.gid )  WHERE s.saledate > '".$Begtime."' AND  s.saledate <= '".$Endtime."'  GROUP BY s.gid order by smtotal desc ";
break;

}
*/
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($Begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($Endtime,"-");
$Sql = "select od.goodsname ,od.gid,sum(od.goodscount) as sntotal,sum(od.price*od.goodscount) as smtotal from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}order_table`  as o on od.order_id = o.order_id  inner join `{$INFO[DBPrefix]}user` as u on o.user_id = u.user_id where o.createtime > '".$begtimeunix."' AND o.createtime > '".$endtimeunix."' and u.companyid = '" .$companyid ."' group by od.gid";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
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
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>-->&gt;公司購買統計</SPAN></TD>
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
  </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top height=262>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD height="300" vAlign=top bgColor=#ffffff>
                      <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center class="allborder">
                        <TBODY>
                          <TR align="center">
                            <TD height="300" align="left" valign="top">
                              
                              <br>
                              <FORM name=form1 method=get>
                                <input type="hidden" name="act" value="<?php echo $_GET[act]?>">
                                <br>
                                <span class="p9black"><?php
							$Sql      = "select u.*  from `{$INFO[DBPrefix]}company` u order by u.id desc";
$Query_c    = $DB->query($Sql);
$Num      = $DB->num_rows($Query_c);
while ($Rs=$DB->fetch_array($Query_c)) {
	$company .="<option value=".$Rs['id']." ";
	if ($companyid == $Rs['id'])
		$company .= " selected";
	$company .= " >".$Rs['companyname']."</option>\n";
}
							?>
                                  <select name="company">
                                    <option value="0">請選擇公司</option>
                                    <?php echo $company;?>
                                </select><?php echo $Visit_Packs[VisFrom];//从?>&nbsp; 
                                  <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=begtime size=10  onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $Begtime;?>' name='begtime'> 
                                  
                                  &nbsp;<?php echo $Visit_Packs[VisTo];//至?>&nbsp; 
                                  <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=endtime size=10   onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $Endtime;?>' name='endtime'>
                                  
                                  <INPUT class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'" type=submit value=<?php echo $Visit_Packs[ReSearch] ;//重新查询?>>
                                </span>
                              </FORM>
                              <br>
                              <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                                <TBODY>
                                  <TR align=middle class=row1>
                                    <TD  align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Basic_Command['SNo_say']?></TD>
                                    <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $ProductVisit_Packs[Product_Name] ?></TD>
                                    <TD align="right" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $ProductVisit_Packs[ProductSaleMoney] ?></TD>
                                    <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>&nbsp;</TD>
                                    <TD  colspan="2" align="right" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $ProductVisit_Packs[ProductSaleNum] ?>&nbsp;&nbsp;</TD>
                                    </TR>
                                  <?php
	             			if ($Num>0){
	             				$i=1;
	             				$TotalNum = 0;
	             				while ($Result = $DB->fetch_array($Query)){
						    ?>   
                                  <TR class=row0>
                                    <TD height=20 align=center><?php echo $i?></TD>
                                    <TD height=20 align="left" noWrap><?php echo $Result['goodsname'];?></TD>
                                    <TD align="right" noWrap><?php echo $Result['smtotal'];?>&nbsp;</TD>
                                    <TD height=20 align="left" noWrap>&nbsp;</TD>
                                    <TD height=20 colspan="2" align="right" noWrap><?php echo $Result['sntotal'];?>&nbsp;&nbsp;&nbsp;</TD>
                                    </TR>
                                  <?php
   						    $TotalNum = $TotalNum+$Result['totalvisit'];
   						    $i++;
	             				}
						    ?>
                                  <TR align="right">
                                    <TD colspan="6" class="p9orange" align="right">							   </TD>
                                    </TR>
                                  <?php
	             			}else{
						    ?>
                                  <TR class=row1>
                                    <TD height=20 colspan="6" align=center class="p9orange"><?php echo $Visit_Packs[NoVisit_Say];//没有参与统计的资料?></TD>
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
                    </TBODY></TABLE></TD>
              </TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
