<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

$Provider_id = intval($_GET['provider_id']);
$Action_value = "Update";
$Action_say  = $Admin_Product[ModiProviderData]; //修改供应商
$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Provider_id)." limit 0,1");
$Num   = $DB->num_rows($Query);
  
  if ($Num>0){
  $Result= $DB->fetch_array($Query);
   $provider_name            =  $Result['provider_name'];
   $provider_thenum          =  $Result['provider_thenum'];
   $provider_tel             =  $Result['provider_tel'];
   $provider_email           =  $Result['provider_email'];
   $provider_loginpassword   =  $Result['provider_loginpassword'];
   $provider_lxr             =  $Result['provider_lxr'];
   $provider_addr            =  $Result['provider_addr'];
   $provider_content         =  $Result['provider_content'];
   $provider_idate           =  $Result['provider_idate'];
   $providerno           =  $Result['providerno'];
   $brandname           =  $Result['brandname'];
	$mode           =  $Result['mode'];
	$piaoqi           =  $Result['piaoqi'];
	$PM           =  $Result['PM'];
	$start_date           =  $Result['start_date'];
	$end_date           =  $Result['end_date'];
	$state           =  $Result['state'];
	$payment           =  $Result['payment'];
	$agreementno           =  $Result['agreementno'];
	$fax           =  $Result['fax'];
	$websit           =  $Result['websit'];
	$receive_mail1           =  $Result['receive_mail1'];
	$receive_mail2           =  $Result['receive_mail2'];
	$receive_mail3           =  $Result['receive_mail3'];
	$account_lxr           =  $Result['account_lxr'];
	$account_tel           =  $Result['account_tel'];
	$account_mobile           =  $Result['account_mobile'];
	$account_mail           =  $Result['account_mail'];
	$provider_mobile           =  $Result['provider_mobile'];
	$bankno           =  $Result['bankno'];
	$invoice_num           =  $Result['invoice_num'];
	$invoice_title           =  $Result['invoice_title'];
	$invoice_addr           =  $Result['invoice_addr'];
	$provider_type           =  $Result['provider_type'];
	$paytype           =  $Result['paytype'];
	$bankuser           =  $Result['bankuser'];
	$bankname           =  $Result['bankname'];
	$bank           =  $Result['bank'];
$company_tel           =  $Result['company_tel'];
   

  }

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<TABLE class=allborder cellSpacing=0 cellPadding=2  width="717" align=center border=0>
  <TBODY>
    <TR>
      <TD height="33" colspan="2" align=left noWrap style="border-bottom:#999 1px solid;font-size:17px"> <strong>供應商資料</strong></TD>
    </TR>
    <TR>
      <TD noWrap align=right width="25%">廠編：</TD>
      <TD align=left noWrap><?php echo $providerno;?></TD>
    </TR>
    <TR>
      <TD noWrap align=right width="25%">廠商名稱：</TD>
      <TD align=left noWrap><?php echo $provider_name?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>店家帳號：</TD>
      <TD width="75%" align=left noWrap><?php echo $provider_thenum?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>店家密碼：</TD>
      <TD align=left noWrap><?php echo $provider_loginpassword?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>聯絡窗口：</TD>
      <TD align=left noWrap><?php echo $provider_lxr?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>電話：</TD>
      <TD align=left noWrap><?php echo $provider_tel?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>手機：</TD>
      <TD align=left noWrap><?php echo $provider_mobile?></TD>
    </TR>
    <TR>
      <TD noWrap align=right width="25%">Mail：</TD>
      <TD align=left noWrap><?php echo $provider_email?></TD>
    </TR>
    <TR>
      <TD noWrap align=right width="25%">合作模式：</TD>
      <TD align=left noWrap><?php echo $mode?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>付款方式：</TD>
      <TD align=left noWrap><?php echo $payment?></TD>
    </TR>
    <TR>
      <TD noWrap align=right width="25%">票期：</TD>
      <TD align=left noWrap><?php echo $piaoqi?></TD>
    </TR>
    <TR>
      <TD noWrap align=right width="25%">結帳方式：</TD>
      <TD align=left noWrap><?php echo $paytype?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>合約編號：</TD>
      <TD align=left noWrap><?php echo $agreementno?></TD>
    </TR>
    <TR>
      <TD noWrap align=right width="25%">合約日期：</TD>
      <TD align=left noWrap>From
        <?php echo $start_date?>
        To
        <?php echo $end_date?></TD>
    </TR>
    <TR>
      <TD align=right >狀態：</TD>
      <TD><?php echo $provider_state[$state]?></TD>
    </TR>
    <TR>
      <TD width="25%" align=right valign="top" noWrap><?php echo $Admin_Product[Detail_intro]?>：</TD>
      <TD align=left valign="top" noWrap><?php 					   
					  		echo $provider_content;
					   ?>
      </TD>
    </TR>
    <TR>
      <TD height="33" colspan="2" align=left style="border-top:#999 1px solid;border-bottom:#999 1px solid;font-size:17px"><strong>公司基本資料</strong></TD>
    </TR>
    <TR>
      <TD noWrap align=right>&nbsp;</TD>
      <TD align=left noWrap><?php echo $provider_type==0?"公司":"個人"?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>品牌名稱：</TD>
      <TD align=left noWrap><?php echo $brandname?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>公司電話：</TD>
      <TD align=left noWrap><?php echo $company_tel?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>公司傳真：</TD>
      <TD align=left noWrap><?php echo $fax?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>公司地址：</TD>
      <TD align=left noWrap><?php echo $provider_addr?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>公司主要接收信：</TD>
      <TD align=left noWrap><?php echo $receive_mail1?><BR>
        <?php echo $receive_mail2?><BR>
        <?php echo $receive_mail3?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>公司網址：</TD>
      <TD align=left noWrap><?php echo $websit?></TD>
    </TR>
    <TR>
      <TD height="33" colspan="2" align=left style="border-top:#999 1px solid;border-bottom:#999 1px solid;font-size:17px"><strong>帳務資料</strong></TD>
    </TR>
    <TR>
      <TD noWrap align=right>對帳窗口聯繫人：</TD>
      <TD align=left noWrap><?php echo $account_lxr?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>對帳窗口電話：</TD>
      <TD align=left noWrap><?php echo $account_tel?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>對帳窗口手機：</TD>
      <TD align=left noWrap><?php echo $account_mobile?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>對帳窗口Mail：</TD>
      <TD align=left noWrap><?php echo $account_mail?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>公司統一編號：</TD>
      <TD align=left noWrap><?php echo $invoice_num?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>發票地址：</TD>
      <TD align=left noWrap><?php echo $invoice_addr?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>往來銀行/分行：</TD>
      <TD align=left noWrap><?php echo $bank?> 銀行代號<?php echo $bankno?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>帳號：</TD>
      <TD align=left noWrap><?php echo $bankuser?></TD>
    </TR>
    <TR>
      <TD noWrap align=right>戶名：</TD>
      <TD align=left noWrap><?php echo $bankname?></TD>
    </TR>
  </TBODY>
</TABLE>
<br>
</BODY>
</HTML>
