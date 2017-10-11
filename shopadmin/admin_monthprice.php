<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
$gid        = trim($FUNCTIONS->Value_Manage($_GET['gid'],'','back',''));
/**
	 * 这里是当供应商进入的时候。只能修改自己的产品资料。
	 */
if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$Provider_string = " and g.provider_id=".intval($_SESSION['sa_id'])." ";
}else{
	$Provider_string = "";
}
$G_Sql      = "select g.goodsname,g.pricedesc,g.price,g.bn from `{$INFO[DBPrefix]}goods` g where g.gid='".intval($gid)."' ".$Provider_strings." limit 0,1";
$G_Query    = $DB->query($G_Sql);
$G_Num      = $DB->num_rows($G_Query);
if ($G_Num>0){
	$G_result    =  $DB->fetch_array($G_Query);
	$G_goodsname = $G_result['goodsname'];
	$G_price     = $G_result['price'];
	$G_pricedesc = $G_result['pricedesc'];
	$G_bn        = $G_result['bn'];
}else{
	$FUNCTIONS->sorry_back('back','');
}

$DB->free_result($G_Query);


?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<META http-equiv=ever content=no-cache>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2600.0" name=GENERATOR>
<title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[SetMemberPrice];//会员价格设定?></title>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" onLoad="addMouseEvent();">
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
    <TD align="right">
	  <?php  include_once "desktop_title.php";?></TD>
    </TR>
  </TBODY>
 </TABLE>
<SCRIPT language=javascript>


function checkform(){
 	form1.action="admin_monthprice_save.php";
	form1.submit();
}

/*
function toDel(){
	var checkvalue;
	checkvalue = isSelected(<?php echo $Nums?>,'<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_memberprice_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}
*/

</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1
      src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif"
  width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=302></TD>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black noWrap><SPAN
                  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[SetMemberPrice];//会员价格设定?></SPAN></TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%">
        <?php if ($Ie_Type != "mozilla") { ?>
		    <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>

                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
					 <TD align=middle width=79>  <!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="admin_goods.php?Action=Modi&gid=<?php echo $gid?>">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?>&nbsp; </TD>
							</TR>
						  </TBODY>
						 </TABLE>
					 <!--BUTTON_END-->
					 </td>
                      <TD align=middle width=79>
					  <!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="javascript:checkform();">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?>&nbsp; </TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                </TR>
				 </TBODY>
				</TABLE>
			<?php } else {?>
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
					 <TD align=middle width=79>  <!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<a href="admin_goods.php?Action=Modi&gid=<?php echo $gid?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD>
							</TR>
						  </TBODY>
						 </TABLE>
					 <!--BUTTON_END-->
					 </td>
                      <TD align=middle width=79>
					  <!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                </TR>
				 </TBODY>
				</TABLE>
        <?php } ?>
			</TD>
		  </TR>
		  </TBODY>
		</TABLE>

      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>

        <TR>
          <TD align=right colSpan=2 height=31>&nbsp;            </TD>
           <TD class=p9black align=right width=400 height=31>

		  </TD>
		 </TR>

	</TABLE>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
        <TR>
          <TD vAlign=top height=210>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD bgColor=#ffffff>
                  <table width="100%"  border="0" cellspacing="2" bgcolor="#EBEBEB" class="p9black">
                    <tr>
                      <td colspan="6">&nbsp;</td>
                      </tr>
                    <tr>
                      <td width="20%" align="right">&nbsp;</td>
                      <td  align="left" nowrap><?php echo $Admin_Product[Bn];//貨號?>：<?php echo $G_bn?></td>
                      <td  align="left" nowrap><?php echo $Admin_Product[ProductName];//商品名称?>：<?php echo $G_goodsname?></td>
                      <td  align="left" nowrap><?php echo $Admin_Product[ProductPrice];//建議售價?>：<?php echo $G_price?></td>
                      <td  align="left" nowrap><?php echo $Admin_Product[ProductNetPrice];//網購價?>：<?php echo $G_pricedesc?></td>
                      <td width="20%" align="right">&nbsp;</td>
                      </tr>
                    <tr height="30">
                      <td colspan="6" align="right" bgcolor="#EBEBEB"></td>
                    </tr>
                  </table>

                  <TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                    <FORM name=form1 action="admin_monthprice_save.php" method=post>
					<INPUT type=hidden name=act>
					<INPUT type=hidden name=gid value="<?php echo $gid?>">
					 <INPUT type=hidden value=0  name=boxchecked>
                    <TBODY>
                    <TR align=middle>
                      <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26><?php echo $Basic_Command['SNo_say'];//序号?></TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 付款期數</TD>
                     
                      <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> 每期金額</TD>
                      </TR>
					<?php
					$i=0;
					$monthcount = array("3","6","12","24");
					foreach ($monthcount as $k=>$v) {


					?>
                    <TR class=row0>
                      <TD align=middle width=91 height=20>
                      <?php echo $k?>
					  </TD>
                      <TD height=20 align="left" noWrap>
                        <?php echo $v?>期                      </TD>
                      <TD height=20 align=center nowrap>
					  <?php
					  $Sql_M    = "select * from `{$INFO[DBPrefix]}month_price` where month=".intval($v)." and goods_id=".intval($gid)." limit 0,1";
					  $Query_M  = $DB->query($Sql_M);
					  $Result_M = $DB->fetch_array($Query_M);
					  ?>
					  <input type="hidden" name="m_price_id[]" value="<?php echo $Result_M['m_price_id']?>">
					  <input type="hidden" name="month[]" value="<?php echo $v?>">
					  <?php echo $FUNCTIONS->Input_Box('text','month_price[]',intval($Result_M['month_price']),"      maxLength=10 size=10 ")?>
					  <?php $DB->free_result($Query_M);  ?>					  </TD>
                      </TR>
					<?php
					$i++;
					}
					?>
                    <TR>
                      <TD align=middle width=91 height=14>&nbsp;</TD>
                      <TD width=245 height=14>&nbsp;</TD>
                      <TD width=202 height=14>&nbsp;</TD>
                      </TR>
					 </FORM>
					 </TABLE>
					 </TD>
				    </TR>
			    </TABLE>

            <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>

              <TBODY>
              <TR>
                <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
				<?php //echo $Nav->pagenav()?>
				</TD>
              </TR>


	    </TABLE></TD></TR></TABLE></TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=302>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></TBODY></TABLE>

</BODY></HTML>
