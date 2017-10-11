<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

if ($_GET['provider_id']!="" && $_GET['Action']=='Modi'){
$Provider_id = intval($_GET['provider_id']);
$Action_value = "Update";
$Action_say  = $Admin_Product[ModiProviderData]; //修改供应商
$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($Provider_id)." limit 0,1");
$Num   = $DB->num_rows($Query);
  $Result= $DB->fetch_array($Query);
  if ($Num>0 &&( $_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $Result['state']==0)|| ($_SESSION['LOGINADMIN_TYPE']==1 && $Result['state']==2))){
  
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
	$fanid           =  $Result['fanid'];
  }else{
  echo "<script language=javascript>javascript:window.history.back();</script>";
  exit;
  }

}else{
$Action_value = "Insert";
$Action_say   = $Admin_Product[AddProviderData]; //添加供应商
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK id=css href="css/calendar.css" type='text/css' rel=stylesheet>
<TITLE>供應商管理--&gt;<?php echo $Action_say?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<?php  include_once "Order_state.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		<?php
            if ($_SESSION['LOGINADMIN_TYPE']==1){
		?>
		
		if (chkblank(form1.provider_name.value)){
			form1.provider_name.focus();
			alert('<?php echo $Admin_Product[PleaseSelectProviderName];?>');  //请选择供應商名稱			
			return;
		}
    	if (chkblank(form1.provider_lxr.value)){
			form1.provider_lxr.focus();
			alert('<?php echo $Admin_Product[PleaseInputLxr] ;?>');  //请输入联系人
			return;
		}


		if (chkblank(form1.provider_tel.value)){
			form1.provider_tel.focus();
			alert('<?php echo $Admin_Product[PleaseInputTel];?>');  //请输入聯絡電話
			return;
		}

		if (chkblank(form1.provider_email.value)){
			form1.provider_email.focus();
			alert('<?php echo $Admin_Product[PleaseInputEmail] ;?>');  //请输入電子信箱
			return;
		}		
		<?php
			}
			if ($_SESSION['LOGINADMIN_TYPE']==0){
		?>
		if(form1.state.value==2||form1.state.value==3||form1.state.value==7){
		if (chkblank(form1.provider_thenum.value)){
			form1.provider_thenum.focus();
			alert('請填寫店家帳號');  //请输入電子信箱
			return;
		}
		if (chkblank(form1.provider_loginpassword.value)){
			form1.provider_loginpassword.focus();
			alert('請填寫店家密碼');  //请输入電子信箱
			return;
		}
		
			if (chkblank(form1.agreementno.value)){
				form1.agreementno.focus();
				alert('請填寫合約編號');  //请输入電子信箱
				return;
			}
			if (chkblank(form1.start_date.value)){
				form1.start_date.focus();
				alert('請填寫合約期間');  //请输入電子信箱
				return;
			}
			if (chkblank(form1.end_date.value)){
				form1.end_date.focus();
				alert('請填寫合約期間');  //请输入電子信箱
				return;
			}
		}
		<?php
			}
		?>
		form1.submit();
	}

</SCRIPT>
<div id="contain_out">
  <FORM name=form1 action='admin_provider_save.php' method=post enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="Provider_id" value="<?php echo $Provider_id?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>供應商管理--&gt;<?php echo $Action_say?></SPAN></TD>
                  </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
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
                            <a href="admin_provider_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
                          </TD></TR></TBODY></TABLE>
                    
                  </TD></TR></TBODY></TABLE>
            </TD>
          </TR>
        </TBODY>
    </TABLE>
                      <?php
                   // if ($_SESSION['LOGINADMIN_TYPE']==0){
					?>
                      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class=p12black>
                        <tr>
                          <td><a href="javascript:void(0)" onClick="showProvider(1);">供應商資料</a> | <a href="javascript:void(0)" onClick="showProvider(2);">公司基本資料</a> | <a href="javascript:void(0)" onClick="showProvider(3);">帳務資料</a></td>
                        </tr>
    </table>
                      <?php
					// }
					  ?>
    <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0 id="table1">
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="23%">&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD></TR>
                          <?php 
					 if ($_GET['provider_id']!="" && $_GET['Action']=='Modi'){
					 ?>
                          <TR>
                            <TD noWrap align=right width="23%">廠編：</TD>
                            <TD align=left noWrap><?php echo $providerno;?>
                            </TD>
                          </TR>
                          <?php
					 }
					  ?>
                          <TR>
                            <TD noWrap align=right width="23%">廠商名稱：</TD>
                            <TD align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','provider_name',$provider_name,"      maxLength=50 size=50 ")?>需輸入完整公司名稱</TD>
                          </TR>
                          <?php
                    if ($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
					?>
                          <TR>
                            <TD noWrap align=right>店家帳號：</TD>
                            <TD width="77%" align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','provider_thenum',$provider_thenum,"      maxLength=50 size=50 ")?>*</TD>
                          </TR>
                          
                          <TR>
                            <TD noWrap align=right>店家密碼：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_loginpassword',$provider_loginpassword,"      maxLength=50 size=50  ")?>*</TD>
                          </TR>
                          <?php
					}
					?>
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
                            <TD noWrap align=right width="23%">Mail：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_email',$provider_email,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="23%">合作模式：</TD>
                            <TD align=left noWrap>
                              <select name="mode" id="mode">
                                <?php
                      foreach($mode_array as $k=>$v){
					  ?>
                                <option value="<?php echo $v;?>" <?php if($mode==$v) echo "selected";?>><?php echo $v;?></option>
                                <?php
					  }
					  ?>
                              </select>
                            </TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>付款方式：</TD>
                            <TD align=left noWrap><select name="payment" id="payment">
                              <option value="支票" <?php if($payment=="支票") echo "selected";?>>支票</option>
                              <option value="匯款" <?php if($payment=="匯款") echo "selected";?>>匯款</option>
                              </select></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="23%">票期：</TD>
                            <TD align=left noWrap>
                              <select name="piaoqi" id="piaoqi">
                                <?php
                      foreach($piaoqi_array as $k=>$v){
					  ?>
                                <option value="<?php echo $v;?>" <?php if($piaoqi==$v) echo "selected";?>><?php echo $v;?></option>
                                <?php
					  }
					  ?>
                              </select>
                            </TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="23%">結帳方式：</TD>
                            <TD align=left noWrap>
                              <select name="paytype" id="paytype">
                                <?php
                      foreach($paytype_array as $k=>$v){
					  ?>
                                <option value="<?php echo $v;?>" <?php if($paytype==$v) echo "selected";?>><?php echo $v;?></option>
                                <?php
					  }
					  ?>
                              </select>
                            </TD>
                          </TR>
                          <?php
                    if ($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
					?>
                          <TR>
                            <TD noWrap align=right>合約編號：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','agreementno',$agreementno,"      maxLength=50 size=50 ")?>*</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="23%">合約日期：</TD>
                            <TD align=left noWrap>From
                              <INPUT   id=start_date size=10 value="<?php echo $start_date?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=start_date />
                              To
                              <INPUT    id=end_date size=10 value="<?php echo $end_date?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=end_date />
                              *</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="23%">PM：</TD>
                            <TD colspan="4" align=left noWrap>
                              <select name="PM" id="PM">
                                <?php
                      $Sql      = "select * from `{$INFO[DBPrefix]}operater` order by lastlogin desc ";
					  $Query    = $DB->query($Sql);
					  while ($Rs=$DB->fetch_array($Query)) {
					  ?>
                                <option value="<?php echo $Rs['opid'];?>" <?php if($PM==$Rs['opid']) echo "selected";?>><?php echo $Rs['truename'];?></option>
                                <?php
					  }
					  ?>
                              </select>
                            </TD>
                          </TR>
                          <TR>
                            <TD align=right >狀態：</TD>
                            <TD>
                              <select name="state" id="state">
                                <?php
                      foreach($provider_state as $k=>$v){
					  ?>
                                <option value="<?php echo $k;?>" <?php if($state==$k) echo "selected";?>><?php echo $v;?></option>
                                <?php
					  }
					  ?>
                              </select>
                            </TD>
                          </TR>
                          
                          <TR>
                            <TD width="23%" align=right valign="top" noWrap><?php echo $Admin_Product[Detail_intro]?>：</TD>
                            <TD align=left valign="top" noWrap>
                              <?php
						
						//echo OtherPach."/".Resources."/ckeditor/";;
						$CKEditor = new CKEditor();
						$CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						
						$CKEditor->config['width'] = 700;
						$CKEditor->config['height'] = 200;
						//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
						echo $code = $CKEditor->editor("FCKeditor1", $provider_content);

					   ?>
                              
                              *</TD>
                          </TR>
                          <?php
					}
					?>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                          </TR>
      </TBODY></TABLE>
                      
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0 id="table2">
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="36%">&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                          </TR>
                          <?php
                    if ($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
					?>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','provider_type',$provider_type,$Add=array("個人","公司"))?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="36%">品牌名稱：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','brandname',$brandname,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          
                          <TR>
                            <TD noWrap align=right>公司電話：</TD>
                            <TD width="64%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','company_tel',$company_tel,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>公司傳真：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','fax',$fax,"      maxLength=50 size=50  ")?></TD>
                          </TR>
                          <?php
					}
					  ?>
                          
                          <TR>
                            <TD noWrap align=right>公司地址：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','provider_addr',$provider_addr,"      maxLength=255 size=50 ")?></TD>
                          </TR>
                          <?php
                    if ($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
					?>
                          <TR>
                            <TD align=right valign="top" noWrap>公司主要接收信：</TD>
                            <TD align=left valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','receive_mail1',$receive_mail1,"      maxLength=50 size=50 ")?><BR> <?php echo $FUNCTIONS->Input_Box('text','receive_mail2',$receive_mail2,"      maxLength=50 size=50 ")?><BR> <?php echo $FUNCTIONS->Input_Box('text','receive_mail3',$receive_mail3,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>公司網址：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','websit',$websit,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          
                          <tr>
                            <td nowrap="nowrap" align="right">物流設置：</td>
                            <td align="left" nowrap="nowrap">常溫：<?php echo $FUNCTIONS->Input_Box('text','yunfei',$yunfei,"      maxLength=10 size=10 ")?>,消費滿<?php echo $FUNCTIONS->Input_Box('text','mianyunfei',$mianyunfei,"      maxLength=10 size=10 ")?>元免運費<br />
                              冷藏：<?php echo $FUNCTIONS->Input_Box('text','yunfei1',$yunfei1,"      maxLength=10 size=10 ")?>,消費滿<?php echo $FUNCTIONS->Input_Box('text','mianyunfei1',$mianyunfei1,"      maxLength=10 size=10 ")?>元免運費<br />
                              冷凍：<?php echo $FUNCTIONS->Input_Box('text','yunfei2',$yunfei2,"      maxLength=10 size=10 ")?>,消費滿<?php echo $FUNCTIONS->Input_Box('text','mianyunfei2',$mianyunfei2,"      maxLength=10 size=10 ")?>元免運費</td>
                          </tr>
                          <tr>
                            <td nowrap="nowrap" align="right">&nbsp;</td>
                            <td align="left" nowrap="nowrap">&nbsp;</td>
                          </tr>
                          <?php
					}
					?>
                        </TBODY>
                      </TABLE>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0 id="table3">
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="36%">&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                          </TR>
                          <?php
                    if ($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
					?>
                          <TR>
                            <TD noWrap align=right width="36%">對帳窗口聯繫人：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','account_lxr',$account_lxr,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>對帳窗口電話：</TD>
                            <TD width="64%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','account_tel',$account_tel,"      maxLength=50 size=50 ")?></TD>
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
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','invoice_num',$invoice_num,"      maxLength=50 size=50 ")?> </TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>發票地址：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','invoice_addr',$invoice_addr,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>往來銀行/分行：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','bank',$bank,"      maxLength=50 size=50 ")?>  銀行代號<?php echo $FUNCTIONS->Input_Box('text','bankno',$bankno,"      maxLength=10 size=10 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>帳號：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','bankuser',$bankuser,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>戶名：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','bankname',$bankname,"      maxLength=50 size=50 ")?></TD>
                          </TR>
                          <?php
					}
					?>
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
function showProvider(no){
	$("#table1").css("display","none");
	$("#table2").css("display","none");
	$("#table3").css("display","none");
	$("#table" + no).css("display","block");
}
showProvider(1);
</script>
</BODY>
</HTML>
