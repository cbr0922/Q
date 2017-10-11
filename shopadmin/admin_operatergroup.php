<?php
include_once "Check_Admin.php";
/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_Operater.php";

if ($_GET['opid']!="" && $_GET['Action']=='Modi'){
	$opid = intval($_GET['opid']);
	$Action_value = "Update";
	$Action_say  = "編輯管理員組"; //修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}operatergroup` where opid=".intval($opid)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$groupname    =  $Result['groupname'];
		$maillist    =  $Result['maillist'];
		$maillist_array = explode(",",$maillist);
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增管理員組";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man]?>--&gt;<?php echo $Action_say?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" 
background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>
	function checkform(){
		if (form1.groupname.value == ""){
			alert('請輸入管理員組名稱');  //请输入用户名！
			form1.groupname.focus();
			return;			
		}
		
		form1.submit();
	}
	
</SCRIPT>

<div id="contain_out">
  <FORM name=form1 action='admin_operatergroup_save.php' method='post' >
    <?php  include_once "Order_state.php";?>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="opid" value="<?php echo $opid?>">
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
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man]?>--&gt;<?php echo $Action_say?></SPAN></TD>
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
                            <a href="admin_operatergroup_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>
                          
                          <TR>
                            <TD noWrap align=right>管理組名稱：</TD>
                            <TD width="38%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','groupname',$groupname,"   class='box_no_pic1'  onmouseover=\"this.className='box_no_pic2'\" onMouseOut=\"this.className='box_no_pic1'\"    maxLength=20 size=20 ")?></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD width="18%" colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>收郵件：</TD>
                            <TD colspan="4" align=left >
                            <?php
                            $Sql_m      = "select * from `{$INFO[DBPrefix]}sendtype` where ifadmin=1";
							$Query_m    = $DB->query($Sql_m);
							while ($Rs_m=$DB->fetch_array($Query_m)) {
							?>
                            <input type="checkbox" name="mail[]" value="<?php echo $Rs_m['sendtype_id'];?>" <?php if(in_array($Rs_m['sendtype_id'],$maillist_array) ) echo "checked";?> /><?php echo $Rs_m['sendname'];?>
                            <?php
							}
							?>
                            &nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>
  </FORM>
</div>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
<script language="javascript">
function getMoreAttrib(a){
		//alert(a);
	}
$(document).ready(function() {
	$('#btn_class').click(function() {
	var counts = parseInt($('#classcount').attr("value"));
	//alert(counts);
			$.ajax({
				url: "admin_goods_ajaxclass.php",
				data: 'count=' + counts,
				type:'get',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				    //$('#showsize').html(msg);
					$('#classcount').attr("value",counts+1);
					$(msg).appendTo('#extclass')
				}
			});
		});
	
	
	
})
</script>
