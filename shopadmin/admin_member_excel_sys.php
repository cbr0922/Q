<?php
include_once "Check_Admin.php";

/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>客戶關係--&gt;會員管理--&gt;會員匯入/匯出欄位設定</TITLE>
</HEAD>
<BODY onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		form1.submit();
	}


</SCRIPT>
<SCRIPT language=javascript>
function checkform()
{
	form1.submit();
}
function CheckAll(form){
	for (var i=0;i<form.elements.length;i++){ 
         var e = form.elements[i];
         if (e.name != 'chkall')   e.checked = form.toggle.checked;
	}
}
</SCRIPT>
<div id="contain_out">
  <FORM name='form1' action='' method='post' id="theform">
    <?php  include_once "Order_state.php";?>
  <input type="hidden" name="Action" value="Modi">
  <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>客戶關係--&gt;會員管理--&gt;會員匯入/匯出欄位設定</SPAN></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
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
                            <a  href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save']?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->							</TD></TR></TBODY></TABLE>				</TD>
                    </TR>
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
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD width="16%" height="48" align=right noWrap>&nbsp;</TD>
                            <TD colspan="3" align=left valign="bottom" noWrap><input onclick=CheckAll(this.form); type=checkbox value=checkbox name=toggle />
選擇全部 </TD></TR>
                          <TR>
                            <TD align=right valign="top" noWrap><i class="icon-download-alt" style="font-size:14px"></i> 會員匯出欄位設定：</TD>
                            <TD colspan="3" align=left valign="top" style="padding-right:90px">
                              <?php
					  $member_excel_out = explode(",",$INFO['member_excel_out']);
                       foreach($member_field as $k=>$v){
						?>
                              <input type="checkbox" name="member_excel_out[]" <?php if(in_array($k,$member_excel_out)) echo "checked";?> value="<?php echo $k;?>"><?php echo $v;?>
                              <?php   
					   }
					   ?>
                              </TD>
                          </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>&nbsp;</TD>
                            <TD colspan="3" align=left valign="top" style="padding-right:90px">&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD align=right valign="top" noWrap><i class="icon-upload-alt" style="font-size:14px"></i> 會員匯入欄位設定：</TD>
                            <TD colspan="3" align=left valign="top" style="padding-right:90px">
                              <?php
					  $member_excel_in = explode(",",$INFO['member_excel_in']);
                       foreach($member_field as $k=>$v){
						?>
                              <input type="checkbox" name="member_excel_in[]" <?php if(in_array($k,$member_excel_in)) echo "checked";?> value="<?php echo $k;?>"><?php echo $v;?>
                              <?php   
					   }
					   ?>
                              </TD>
                            </TR>
                          <TR>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD>
                            </TR>
                          </TBODY>
                  </TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
    </TR>
  </FORM>
</div>
                      <div align="center"><?php include_once "botto.php";?></div>

</BODY>
</HTML>

<?php

if ( $_POST['Action']=="Modi" ){
	
	//if(is_array($_POST['goods_excel_out']))
	//	$goods_excel_out = implode("|",$_POST['goods_excel_out']);
	//if(is_array($_POST['goods_excel_in']))
	//	$goods_excel_out = implode("|",$_POST['goods_excel_in']);
	
	$Ex_Function->save_config( $new = array("goods_excel_out","goods_excel_in","member_excel_in","member_excel_out"),"conf.global") ;
	$FUNCTIONS->setLog("會員匯入匯出欄位設定");

	// $Ex_Function->save_config( $new = array("IS","chartset","site_name","lxr","sex","email","city","addr","other_tel","tel","fax","post","site_title","company_name","content","site_url","site_shopadmin","b_attr","meta_desc","meta_keyword")) ;  //数组的名称需要对应修改的字段名称！
	echo " <script language=javascript> alert('".$Basic_Command[Back_System_Sucuess]."'); location.href='admin_member_excel_sys.php'; </script>";
}
?>
