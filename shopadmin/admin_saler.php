<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

if ($_GET['id']!="" && $_GET['Action']=='Modi'){
$id = intval($_GET['id']);
$Action_value = "Update";
$Action_say  = "修改經銷商"; //修改供应商
$Query = $DB->query("select * from `{$INFO[DBPrefix]}saler` where id=".intval($id)." limit 0,1");
$Num   = $DB->num_rows($Query);
  
  if ($Num>0){
  $Result= $DB->fetch_array($Query);
   $name            =  $Result['name'];
   $password          =  $Result['password'];
   $tel             =  $Result['tel'];
   $addr           =  $Result['addr'];
   $email   =  $Result['email'];
   $bankuser             =  $Result['bankuser'];
   $bankname            =  $Result['bankname'];
   $bank         =  $Result['bank'];
   $ifpub              =  $Result['ifpub'];   
   $login           =  $Result['login'];
	$pubtime           =  $Result['pubtime'];
	$salerset           =  $Result['salerset'];
	$openpwd           =  $Result['openpwd'];
   $userlevel           =  $Result['userlevel'];
	$givebouns           =  $Result['givebouns'];
	$contact           =  $Result['contact'];
	$company           =  $Result['company'];
	$partment           =  $Result['partment'];
  }else{
  echo "<script language=javascript>javascript:window.history.back();</script>";
  exit;
  }
}else{
$Action_value = "Insert";
$Action_say   = "新增經銷商"; //添加供应商
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;經銷商管理--&gt;<?php echo $Action_say?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.name.value)){
			form1.name.focus();
			alert('請輸入經銷商名稱');  //请选择供應商名稱			
			return;
		}

		if (chkblank(form1.login.value)){
			form1.login.focus();
			alert('請輸入經銷商帳號');  //请输入供應商帳號
			return;
		}
		
		if (chkblank(form1.password.value)){
			form1.password.focus();
			alert('請輸入登入密碼');  //请输入登入密碼
			return;
		}

			
		form1.submit();
	}

</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_saler_save.php' method=post enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="id" value="<?php echo $id?>">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say?></SPAN></TD>
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
                            <a href="admin_saler_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right width="18%">經銷商名稱：</TD>
                            <TD colspan="4" align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','name',$name,"      maxLength=50 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%">單位：</TD>
                            <TD colspan="4" align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','company',$company,"      maxLength=255 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%">部門：</TD>
                            <TD colspan="4" align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','partment',$partment,"      maxLength=255 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%">聯繫人：</TD>
                            <TD colspan="4" align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','contact',$contact,"      maxLength=255 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>經銷商帳號：</TD>
                            <TD width="38%" align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','login',$login,"      maxLength=50 size=50 ")?></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>登入密碼：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','password',$password,"      maxLength=50 size=50  ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>開通密碼：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','openpwd',$openpwd,"      maxLength=50 size=50  ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>會員級別：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}user_level` order by level_id asc ",'userlevel','level_id','level_name',intval($userlevel)); ?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>會員贈送積分：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','givebouns',$givebouns,"      maxLength=10 size=10  ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>傭金比例：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','salerset',$salerset,"      maxLength=50 size=5  ")?> % 當經銷商需特殊佣金比例時才需設定。</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>電話：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','tel',$tel,"      maxLength=20 size=20 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>地址：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','addr',$addr,"      maxLength=50 size=50 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>電子信箱：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','email',$email,"      maxLength=50 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%">銀行戶名：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','bankuser',$bankuser,"      maxLength=50 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD align=right >銀行名稱：</TD>
                            <TD colspan="4"><?php echo $FUNCTIONS->Input_Box('text','bankname',$bankname,"      maxLength=50 size=50 ")?></TD>
                            </TR>
                          <TR>
                            <TD width="18%" align=right valign="top" noWrap>銀行帳號：</TD>
                            <TD colspan="3" align=left valign="top" noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','bank',$bank,"      maxLength=50 size=50 ")?>
                              
                              </TD>
                            <TD width="9%" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>是否發佈：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','ifpub',$ifpub,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
