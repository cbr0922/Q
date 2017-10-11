<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($_SESSION['sa_id'])." limit 0,1");
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
	$mianyunfei           =  $Result['mianyunfei'];
	$mianyunfei1           =  $Result['mianyunfei1'];
	$mianyunfei2           =  $Result['mianyunfei2'];
	$yunfei           =  $Result['yunfei'];
	$yunfei1           =  $Result['yunfei1'];
	$yunfei2           =  $Result['yunfei2'];
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>供應商資料</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?></TD>
  </TR></TBODY></TABLE>
<SCRIPT language=javascript>
	function checkform(){
		document.form1.action="provider_save.php";
		document.form1.submit();
	}	
	
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='' method=post  >
  <input type="hidden" name="Action" value="Update">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange>供應商資料    </SPAN></TD>
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
                              <TABLE><TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  </TR>			  
                </TBODY>
              </TABLE>
            </TD>
          </TR>
      </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0 id="table2">
                      <TBODY>
                        <TR>
                          <TD noWrap align=right width="18%">&nbsp;</TD>
                          <TD align=right noWrap>&nbsp;</TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>&nbsp;</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','provider_type',$provider_type,$Add=array("個人","公司"))?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>公司名稱：</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_name',$provider_name,"      maxLength=50 size=50 ")?>需輸入完整公司名稱</TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right width="18%">品牌名稱：</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','brandname',$brandname,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>公司電話：</TD>
                          <TD width="82%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','company_tel',$company_tel,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>公司傳真：</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','fax',$fax,"      maxLength=50 size=50  ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>公司地址：</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_addr',$provider_addr,"      maxLength=20 size=20 ")?></TD>
                          </TR>
                        <TR>
                          <TD align=right valign="top" noWrap>公司主要接收信：</TD>
                          <TD align=left valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','receive_mail1',$receive_mail1,"      maxLength=50 size=50 ")?><BR>
                            <?php echo $FUNCTIONS->Input_Box('text','receive_mail2',$receive_mail2,"      maxLength=50 size=50 ")?><BR>
                            <?php echo $FUNCTIONS->Input_Box('text','receive_mail3',$receive_mail3,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>公司網址：</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','websit',$websit,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>聯絡窗口：</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_lxr',$provider_lxr,"      maxLength=20 size=20 ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>電話：</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_tel',$provider_tel,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>手機：</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_mobile',$provider_mobile,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>Mail：</TD>
                          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_email',$provider_email,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                        <tr>
                          <td nowrap="nowrap" align="right">物流設置：</td>
                          <td align="left" nowrap="nowrap">常溫：<?php echo $FUNCTIONS->Input_Box('text','yunfei',$yunfei,"      maxLength=10 size=10 ")?>,消費滿<?php echo $FUNCTIONS->Input_Box('text','mianyunfei',$mianyunfei,"      maxLength=10 size=10 ")?>元免運費<br />
                            冷藏：<?php echo $FUNCTIONS->Input_Box('text','yunfei1',$yunfei1,"      maxLength=10 size=10 ")?>,消費滿<?php echo $FUNCTIONS->Input_Box('text','mianyunfei1',$mianyunfei1,"      maxLength=10 size=10 ")?>元免運費<br />
                            冷凍：<?php echo $FUNCTIONS->Input_Box('text','yunfei2',$yunfei2,"      maxLength=10 size=10 ")?>,消費滿<?php echo $FUNCTIONS->Input_Box('text','mianyunfei2',$mianyunfei2,"      maxLength=10 size=10 ")?>元免運費</td>
                        </tr>
                        </TBODY>
                      </TABLE>
<br>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0 id="table3">
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="18%">對帳窗口聯繫人：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','account_lxr',$account_lxr,"      maxLength=50 size=50 ")?>
                              <input type="checkbox" name="ifsame" id="ifsame" onClick="sameto();" value="1">
                              同聯絡窗口</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>對帳窗口電話：</TD>
                            <TD width="82%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','account_tel',$account_tel,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>對帳窗口手機：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','account_mobile',$account_mobile,"      maxLength=50 size=50  ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>對帳窗口Mail：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','account_mail',$account_mail,"      maxLength=100 size=20 ")?>對帳通知</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>公司統一編號：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','invoice_num',$invoice_num,"      maxLength=50 size=50 ")?>個人則免填寫</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>發票地址：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','invoice_addr',$invoice_addr,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>往來銀行/分行：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','bank',$bank,"      maxLength=50 size=50 ")?> 銀行代號<?php echo $FUNCTIONS->Input_Box('text','bankno',$bankno,"      maxLength=10 size=10 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>帳號：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','bankuser',$bankuser,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>戶名：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','bankname',$bankname,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                          </TR>
                        </TBODY>
                  </TABLE>
 </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
<script language="javascript">
function sameto(){
	if($('#ifsame').val()==1){
		$('#account_lxr').val($('#provider_lxr').val());
		$('#account_tel').val($('#provider_tel').val());
		$('#account_mobile').val($('#provider_mobile').val());
		$('#account_mail').val($('#provider_email').val());
	}else{
		$('#account_lxr').val("");
		$('#account_tel').val("");
		$('#account_mobile').val("");
		$('#account_mail').val("");	
	}
}
</script>
</BODY></HTML>
