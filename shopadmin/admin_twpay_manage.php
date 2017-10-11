<?php
include_once "Check_Admin.php";
/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";
$AddrArray = explode("/",$_SERVER['PHP_SELF']);
$Sub_host = "";
foreach ($AddrArray as $k=>$v){
	if ($v!="shopadmin"){
		$Sub_host .= $v."/";
	}elseif($v=="shopadmin"){
		break;
	}
}

function Checked($INputArray,$Value){

	$Array = explode(",",$INputArray);
	foreach ($Array as $k=>$v){
		if ($v==$Value){
			return   "checked";
			break 1;
		}
	}

}

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;金流管理</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>

<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
      <?php  include_once "Order_state.php";?>
  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD>
	</TR>
  </TBODY>
  </TABLE>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<script language="javascript">
function view(obj,a)
{
	if(a == 1){
		obj.style.display="";
	}else{
		obj.style.display="none";
	}
}
</script>

<SCRIPT language=javascript>
	function checkform(){
		form1.submit();
	}


</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <FORM name='form1' action='' method='post' id="theform">
  <input type="hidden" name="Action" value="Modi">



  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1
      src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;金流管理</SPAN></TD>
              </TR>
			  </TBODY>
			 </TABLE>
		  </TD>
          <TD align=right width="50%">
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
			  <?php if ($Ie_Type != "mozilla") { ?>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="javascript:window.history.back(-1);">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew border=0 link="javascript:checkform();">
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap ><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save']?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->

							</TD></TR></TBODY></TABLE>
				</TD>
				</TR>
			   <?php } else {?>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<a  href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap ><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save']?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->

							</TD></TR></TBODY></TABLE>
				</TD>
				</TR>
			   <?php } ?>
				</TBODY></TABLE></TD></TR>
			</TBODY>
		  </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD vAlign=top bgColor=#ffffff height=300>
                  <TABLE class=allborder cellSpacing=0 cellPadding=2  width="96%" align=center bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right>&nbsp; </TD>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD colspan="2" noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right width="13%"><input name="Pay_twpay" type="radio" value="1" checked <?php echo Checked($INFO[Pay_twpay],"1") ?>></TD>
                      <TD noWrap align=right width="5%"><?php echo $TwPay_Pack[TWli];?>
                        <!--臺灣裏-->
                        ：</TD>
                      <TD colspan="2" noWrap>
					    <input type="checkbox" name="Tw_OnePay[]" value="I" <?php echo Checked($INFO[Tw_OnePay],"I") ?>>  <?php echo $TwPayOne_Pack[one]?>
       					<input type="checkbox" name="Tw_OnePay[]" value="II" <?php echo Checked($INFO[Tw_OnePay],"II") ?>>  <?php echo $TwPayOne_Pack[two]?>
						<input type="checkbox" name="Tw_OnePay[]" value="III" <?php echo Checked($INFO[Tw_OnePay],"III") ?>>  <?php echo $TwPayOne_Pack[three]?>
						<input type="checkbox" name="Tw_OnePay[]" value="IV" <?php echo Checked($INFO[Tw_OnePay],"IV") ?>>  <?php echo $TwPayOne_Pack[four]?>
						<input type="checkbox" name="Tw_OnePay[]" value="V" <?php echo Checked($INFO[Tw_OnePay],"V") ?>>  <?php echo $TwPayOne_Pack[five]?>
						<!--input type="checkbox" name="Tw_OnePay[]" value="VI" <?php //echo Checked($INFO[Tw_OnePay],"VI") ?>>  <?php //echo $TwPayOne_Pack[six]?>
						<input type="checkbox" name="Tw_OnePay[]" value="VII" <?php //echo Checked($INFO[Tw_OnePay],"VII") ?>> <?php //echo $TwPayOne_Pack[serven]?>
						-->
						<input type="checkbox" name="Tw_OnePay[]" value="VIII" <?php echo Checked($INFO[Tw_OnePay],"VIII") ?>> <?php echo $TwPayOne_Pack[eight]?><input type="checkbox" name="Tw_OnePay[]" value="VIIII" <?php echo Checked($INFO[Tw_OnePay],"VIIII") ?>> <?php echo $TwPayOne_Pack[nine]?>					  </TD></TR>


                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD width="3%" noWrap>
					  <?php echo $Admin_sys_Pack[Sys_ShopCode];?><!--商店代碼-->：					  </TD>
                      <TD width="79%" noWrap><?php echo $FUNCTIONS->Input_Box('text','Shop_Code',$INFO['Shop_Code'],"          maxLength=30 size=30  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD noWrap><?php echo $Admin_sys_Pack[Sys_ShopCheckCodeI];?><!--商店驗證碼I-->：</TD>
                      <TD noWrap><?php echo $FUNCTIONS->Input_Box('text','Shop_I',$INFO['Shop_I'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD noWrap><?php echo $Admin_sys_Pack[Sys_ShopCheckCodeII];?><!--商店驗證碼II-->：</TD>
                      <TD noWrap><?php echo $FUNCTIONS->Input_Box('text','Shop_II',$INFO['Shop_II'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap><input type="radio" name="Pay_twpay" value="2" <?php echo Checked($INFO[Pay_twpay],"2") ?>></TD>
                      <TD align=right noWrap>PayNow：</TD>
                      <TD colspan="2" noWrap>
					  <input name="Tw_TwoPay[]" type="checkbox" id="Tw_TwoPay[]" value="I" <?php echo Checked($INFO[Tw_TwoPay],"I") ?>> <?php echo $TwPayTwo_Pack[one]?>
					  <input name="Tw_TwoPay[]" type="checkbox" id="Tw_TwoPay[]" value="II" <?php echo Checked($INFO[Tw_TwoPay],"II") ?>> <?php echo $TwPayTwo_Pack[two]?>
					  <input name="Tw_TwoPay[]" type="checkbox" id="Tw_TwoPay[]" value="III" <?php echo Checked($INFO[Tw_TwoPay],"III") ?>> <?php echo $TwPayTwo_Pack[three]?>
					  <!--input name="Tw_TwoPay[]" type="checkbox" id="Tw_TwoPay[]" value="IV" <?php echo Checked($INFO[Tw_TwoPay],"IV") ?>> <?php echo $TwPayTwo_Pack[four]?>
					  <input name="Tw_TwoPay[]" type="checkbox" id="Tw_TwoPay[]" value="V" <?php echo Checked($INFO[Tw_TwoPay],"V") ?>> <?php echo $TwPayTwo_Pack[five]?>-->					   </TD>
                      </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap> <?php echo $TwPay_Pack[commer_account_number];?><!--商家帳號-->：<?php echo $FUNCTIONS->Input_Box('text','Shop_two_loginname',$INFO['Shop_two_loginname'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap><?php echo $TwPay_Pack[commer_account_passwd];?><!--商家密碼-->：<?php echo $FUNCTIONS->Input_Box('text','Shop_two_password',$INFO['Shop_two_password'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap><span class="p9v">聯合信用卡付款</span>：</TD>
                      <TD colspan="2" noWrap>
                      <?php
                      foreach($TwPayFour as $k=>$v){
						?>
                        <input type="checkbox" value="<?php echo $v;?>" name="lianhe_paytype[]" <?php echo Checked($INFO[lianhe_paytype],$v) ?>><?php echo $TwPayFour_Pack[$k];?>
                        <?php  
					  }
					  ?>
                      </TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>特約商店代號：<?php echo $FUNCTIONS->Input_Box('text','Shop_Code',$INFO['Shop_Code'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>端末機代號：<?php echo $FUNCTIONS->Input_Box('text','CardPay_TerminalID',$INFO['CardPay_TerminalID'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>
                      <?php
                      foreach($TwPaySeven as $k=>$v){
						?>
                        <input type="checkbox" value="<?php echo $v;?>" name="paytype[]" <?php echo Checked($INFO[paytype],$v) ?>><?php echo $TwPaySeven_Pack[$k];?>
                        <?php  
					  }
					  ?>
                      </TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>彰銀虛擬帳號：</TD>
                      <TD colspan="2" noWrap>萬用帳號：<?php echo $FUNCTIONS->Input_Box('text','CHB_NO',$INFO['CHB_NO'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>cKey：<?php echo $FUNCTIONS->Input_Box('text','CHB_ckey',$INFO['CHB_ckey'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>wKey：<?php echo $FUNCTIONS->Input_Box('text','CHB_wkey',$INFO['CHB_wkey'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>華南線上：</TD>
                      <TD colspan="2" noWrap>特店代號：<?php echo $FUNCTIONS->Input_Box('text','HN_MerchantID',$INFO['HN_MerchantID'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>機台代號：<?php echo $FUNCTIONS->Input_Box('text','HN_TerminalID',$INFO['HN_TerminalID'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>merID：<?php echo $FUNCTIONS->Input_Box('text','HN_merID',$INFO['HN_merID'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>台新線上：</TD>
                      <TD colspan="2" noWrap>商家代碼：<?php echo $FUNCTIONS->Input_Box('text','TX_storeid',$INFO['TX_storeid'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>
                      <?php
                      foreach($TwPayThree as $k=>$v){
						?>
                        <input type="checkbox" value="<?php echo $v;?>" name="zxtype[]" <?php echo Checked($INFO[zxtype],$v) ?>><?php echo $TwPayThree_Pack[$k];?>
                        <?php  
					  }
					  ?>
                      </TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>中國信託【信用卡一次付清】：</TD>
                      <TD colspan="2" noWrap>特店編號merID：<?php echo $FUNCTIONS->Input_Box('text','Shop_three_merID_zI',$INFO['Shop_three_merID_zI'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>MerchantID：<?php echo $FUNCTIONS->Input_Box('text','Shop_three_MerchantID_zI',$INFO['Shop_three_MerchantID_zI'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>TerminalID：<?php echo $FUNCTIONS->Input_Box('text','Shop_three_TerminalID_zI',$INFO['Shop_three_TerminalID_zI'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>KEY:<?php echo $FUNCTIONS->Input_Box('text','Shop_three_key_zI',$INFO['Shop_three_key_zI'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>中國信託【信用卡分期付款】：</TD>
                      <TD colspan="2" noWrap>特店編號merID：<?php echo $FUNCTIONS->Input_Box('text','Shop_three_merID_zII',$INFO['Shop_three_merID_zII'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>MerchantID：<?php echo $FUNCTIONS->Input_Box('text','Shop_three_MerchantID_zII',$INFO['Shop_three_MerchantID_zII'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>TerminalID：<?php echo $FUNCTIONS->Input_Box('text','Shop_three_TerminalID_zII',$INFO['Shop_three_TerminalID_zII'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>KEY:<?php echo $FUNCTIONS->Input_Box('text','Shop_three_key_zII',$INFO['Shop_three_key_zII'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>中國信託【網路ATM】：</TD>
                      <TD colspan="2" noWrap>特店編號merID：<?php echo $FUNCTIONS->Input_Box('text','Shop_three_merID_zIII',$INFO['Shop_three_merID_zIII'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>MerchantID：<?php echo $FUNCTIONS->Input_Box('text','Shop_three_MerchantID_zIII',$INFO['Shop_three_MerchantID_zIII'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>TerminalID：<?php echo $FUNCTIONS->Input_Box('text','Shop_three_TerminalID_zIII',$INFO['Shop_three_TerminalID_zIII'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>KEY:<?php echo $FUNCTIONS->Input_Box('text','Shop_three_key_zIII',$INFO['Shop_three_key_zIII'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>轉入帳號:<?php echo $FUNCTIONS->Input_Box('text','Shop_three_Atm_zIII',$INFO['Shop_three_Atm_zIII'],"          maxLength=40 size=40  ")?></TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                      <TD colspan="2" noWrap>&nbsp;</TD>
                    </TR>
                    </TBODY>
                  </TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif>
					  <IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
  </FORM>
					  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>

<?php

if ( $_POST['Action']=="Modi" ){

	$Ex_Function->save_config( $new = array("Shop_I","Shop_II","Shop_Code","Tw_OnePay","Shop_two_loginname","Shop_two_password","Tw_TwoPay","Pay_twpay","Shop_Code","CardPay_TerminalID","CHB_NO","CHB_ckey","CHB_wkey","HN_MerchantID","HN_TerminalID","HN_merID","TX_storeid","paytype","lianhe_paytype","Shop_three_merID_zI","Shop_three_MerchantID_zI","Shop_three_TerminalID_zI","Shop_three_key_zI","Shop_three_merID_zII","Shop_three_MerchantID_zII","Shop_three_TerminalID_zII","Shop_three_key_zII","Shop_three_merID_zIII","Shop_three_MerchantID_zIII","Shop_three_TerminalID_zIII","Shop_three_key_zIII","Shop_three_Atm_zIII","zxtype"),"conf.global") ;
	$FUNCTIONS->setLog("編輯金流設置");

	// $Ex_Function->save_config( $new = array("IS","chartset","site_name","lxr","sex","email","city","addr","other_tel","tel","fax","post","site_title","company_name","content","site_url","site_shopadmin","b_attr","meta_desc","meta_keyword")) ;  //数组的名称需要对应修改的字段名称！
	echo " <script language=javascript> alert('".$Basic_Command[Back_System_Sucuess]."'); location.href='admin_twpay_manage.php'; </script>";
}
?>
