<?php
include_once "Check_Admin.php";
include Classes . "/PageNav.class.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack.php";
include "../language/".$INFO['IS']."/Cart.php";

$User_id = intval($_GET['user_id']);
if ($User_id >0){

	$Sql  = "select  *  from `{$INFO[DBPrefix]}order_table` o  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)  where u.user_id=".intval($User_id)." ";
	$Query = $DB->query($Sql);
	$Num =  $DB->num_rows($Query);
	if ($Num>0){
		$PageNav = new PageItem($Sql,20);
		$arrRecords = $PageNav->ReadList();
		$Num     = $PageNav->iTotal;

		$Query_S  = $DB->query("select  sum(totalprice) as totalPrices from `{$INFO[DBPrefix]}order_table` o  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=o.user_id)  where u.user_id=".intval($User_id)." ");
		$Rs_S   = $DB->fetch_array($Query_S);
		$Num_S  = $Rs_S[totalPrices];

	}else{
		$FUNCTIONS->sorry_back("close",$Basic_Command['NullDate']);
	}

}else{
	$FUNCTIONS->sorry_back("close",$Basic_Command['NullDate']);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >

<script language="javascript">
function Order_history(){
	document.form1.action = "Member_HistoryList.php";
	document.form1.target ="_blank";
	document.form1.submit();
}
</script>
<br>
<br>
<div id="contain_out"><br />
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
      <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class=allborder>
              <TBODY>
                <TR>
                  <TD vAlign=top bgColor=#ffffff height=300>
                    <?php if ($Num>0){ ?>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="listtable">
                      <tr>
                        <td height="27" nowrap><?php echo $Admin_Member[UserName];//帳號?>：<?php echo trim($_GET[username])?></td>
                        <td nowrap><?php echo $Admin_Member[TrueName];//姓名?>：<?php echo trim($_GET[true_name])?></td>
                        <td nowrap><?php echo $Admin_Member[Sex];//性别?>：<?php echo   intval($_GET[sex])==0 ? $Admin_Member[Sex_men] : $Admin_Member[Sex_women];?></td>
                        <td nowrap><?php echo $Admin_Member[Area];//地区名称?>：<?php echo trim($_GET[othercity]).trim($_GET[province]).trim($_GET[city])?></td>
                        <td nowrap><?php echo $Admin_Member[Address];//联系地址?>：<?php echo trim($_GET[addr])?></td>
                        <td nowrap><?php echo $Order_Pack[OrderTotalNum_say];//訂單總筆數?>：<?php echo $Num?></td>
                        <td nowrap><?php echo $Order_Pack[OrderTotalPrice_say];//訂單總金額?>：<?php echo $Num_S?></td>
                      </tr>
                    </table>
                    <br>
                    <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
                      
                      <TBODY>
                        <TR align=middle>
                          <TD class=p9black noWrap align=center  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26><?php echo $Order_Pack[OrderSerial_say]?></TD>
                          <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[OrderSerial_say];//订单号?></TD>
                          <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>   <?php echo $Order_Pack[OrderCreatetime_say];//下单日期?></TD>
                          <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[OrderTotalPrice_say];//订单总金额?></TD>
                          <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Cart[send_money_say];//配送費用?></TD>
                          <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Cart[Pay_type];//付款方式?></TD>
                          <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[S_dgr];//訂購人?></TD>
                          <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Order_Pack[ShouHuoren];//收貨人?></TD>
                          <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Order_Pack[OrderState_say] ;//订单状态?> </TD>
                        </TR>
                        
                        <?    
					$i=1;
					while ($Rs=$DB->fetch_array($arrRecords)) {
 				    ?>
                        <TR class=row0>
                          <TD align=center height=20><?php echo $i;?></TD>
                          <TD height=20 align="left" noWrap>
                            <a href="admin_order.php?Action=Modi&order_id=<?php echo $Rs['order_id']?>"  target="_blank">
                              <?php echo $Rs['order_serial']?>
                            </a> </TD>
                          <TD height=20 align="left" noWrap>
                            <?php echo date("Y-m-d",$Rs['createtime'])?>&nbsp;</TD>
                          <TD height=20 align="center" noWrap>
                            <?php echo $Rs['totalprice']+$Rs[transport_price]?>&nbsp;</TD>
                          <TD height=20 align="center" noWrap>                        <?php echo $Rs['transport_price']?>                        &nbsp;</TD>
                          <TD height=20 align="center" noWrap>
                            <?php echo $Rs['paymentname']?>&nbsp;</TD>
                          <TD height=20 align="center" noWrap>
                            <?php echo $Rs['true_name']?>&nbsp;</TD>
                          <TD height=20 align="center" noWrap><?php echo $Rs['receiver_name']?>&nbsp;</TD>
                          <TD height=20 align=center nowrap>
                            <?php echo  $orderClass->getOrderState($Rs['order_state'],1)?>,<?php echo $orderClass->getOrderState(intval($Rs['pay_state']),2)  ?>,<?php echo $orderClass->getOrderState(intval($Rs['transport_state']),3) ?></TD>
                        </TR>
                        <?php
					$i++;
					}
					?>                    
                        
                    </TABLE>				 
                    
                    <p align="right"><span class="p9orange"><?php echo $PageNav->myPageItem();?></span></p>
                    <? }else{ ?>  &nbsp;</p>
                    <p align="center"><span class="9pv"></span><font class="9pv"><?php echo $Basic_Command['NullDate'];?></font></p>
                    
                    <? }?>
                  </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
