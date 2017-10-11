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

$Results;
$Results_count = 0;
$total_price=0;
$total_discount_price=0;

$Sql      = "select t.ticketname,t.money as tmoney,ot.ticketmoney,ut.*,u.username,ut.ordercode,ot.totalprice,ot.discount_totalPrices,ot.order_id,t.type from `{$INFO[DBPrefix]}use_ticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid inner join `{$INFO[DBPrefix]}order_table` as ot on ot.order_serial=ut.ordercode inner join `{$INFO[DBPrefix]}user`  as u on u.user_id=ut.userid where ut.ticketid='" . intval($_GET['ticketid']) . "' order by ut.useid desc";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num>0){
	while($Rs = $DB->fetch_array($Query)){
		$total_price+=$Rs['totalprice'];
		$total_discount_price+=$Rs['discount_totalPrices'];
	}
}

if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>行銷工具--&gt;電子折價券管理--&gt;電子折價券</TITLE>
<link href="css/css.css" rel="stylesheet" type="text/css" />
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
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange>行銷工具--&gt;電子折價券管理--&gt;電子折價券使用情況</SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">&nbsp;</TD>
          </TR>
        </TBODY>
  </TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=form1 id=form1 method=get action="">        
          <!--<input type="hidden" name="Action" value="Search">-->
          <input type="hidden" name="ticketid" value='<?php echo $_GET['ticketid'];?>'>

          <TR>
          	<TD width="128" height=31 vAlign=center style="padding-left:20px;"><!--<input type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle">-->
       	    <i class="icon-check-sign"></i> 使用數量： <span class="red_big"><?php echo $Num?></span></TD>
            <TD width="143" align=left><i class="icon-check-sign"></i> 銷售總金額： <span class="red_big"><?php echo $total_price?></span>
              
            </TD>
            <TD width="431" align=left><i class="icon-check-sign"></i> 折扣後總金額<span class="red_big">： <?php echo $total_discount_price?></span></TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
            </TD>
          </TR>
        </FORM>
  </TABLE>	
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD width="43" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>折價券名稱</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>使用人</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>抵用金額/百分比 </TD>
                              
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>使用時間</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>訂單號</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>金額</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>折扣後金額</TD>
                            </TR>
                            <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

					?>
                            <TR class=row0>
                              <TD width=43 height=26 align=center>
                                <?php echo $Rs['useid']?></TD>
                              <TD height=26 align="left" noWrap>
                                
                                <?php echo $Rs['ticketname']?> 
                                <?php
					  if ($Rs['type'] == "1"){
					  ?>
                                [<?php echo $Rs['ticketcode']?>]
                                <?php
					  }
					  ?>
                              </TD>
                              <TD height=26 align="left" noWrap>
                                <?php echo $Rs['username']?>                        </TD>
                              <TD height=26 align="center" noWrap><?php echo $Rs['tmoney']?></TD>
                              
                              <TD align="center" noWrap><?php echo date("Y-m-d",$Rs['usetime'])?></TD>
                              <TD align="center" noWrap><a href="admin_order.php?Action=Modi&order_id=<?php echo $Rs['order_id']?>"><?php echo $Rs['ordercode']?></a></TD>
                              <TD height=26 align="center" noWrap><?php echo intval($Rs['totalprice'])?></TD>
                              <TD height=26 align="center" noWrap><?php echo $Rs['discount_totalPrices']?></TD>
                            </TR>
                            <?php
					$i++;
					}
					?>
                        </FORM>
                      </TABLE>					 </TD>
                  </TR>
              </TABLE>
              
              <?php if ($Num>0){ ?>
              <table class=p9gray cellspacing=0 cellpadding=0 width="100%"    border=0>
                <tbody>
                  <tr>
                    <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav()?> </td>
                  </tr>
                  <?php } ?>
              </table></TD>
  </TR></TABLE>
</div>
<script language="javascript" src="../js/modi_bigarea1.js"></script>
<script language="javascript">
initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")
initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")
</script>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
