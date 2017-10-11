<?php
include_once "Check_Admin.php";
/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_Operater.php";

if ($_GET['opid']!="" && $_GET['Action']=='Modi'){
	$opid = intval($_GET['opid']);
	$Action_value = "Update";
	$Action_say  = $Admin_Operater[Administrators_Modi]; //修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}operater` where opid=".intval($opid)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$username    =  $Result['username'];
		$userpass    =  $Result['userpass'];
		$truename    =  $Result['truename'];
		$status      =  $Result['status'];
		$type1      =  $Result['type'];
		$lastlogin   =  $Result['lastlogin'];
		$email   =  $Result['email'];
		$groupid   =  $Result['groupid'];

	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = $Admin_Operater[Administrators_Add];
}
include RootDocumentShare."/cache/Productclass_show.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man]?>--&gt;<?php echo $Action_say?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		if (form1.username.value == ""){
			alert('<?php echo $Admin_Operater[PleaseInputUserName];?>');  //请输入用户名！
			form1.username.focus();
			return;			
		}
		<?php
		if ($_GET['Action']!='Modi'){
		?>
		if (form1.userpass.value.length<6){
			alert('<?php echo $Admin_Operater[PleaseInputMaxPass]?>'); //请输入６位以上密码！
			form1.userpass.value = ""
			form1.userpass2.value = ""
			form1.userpass.focus();
			return;			
		}

		if (form1.userpass.value == ""){
			alert('<?php echo $Admin_Operater[PleaseInputPw]?>');  //请输入密码！
			form1.userpass.value = ""
			form1.userpass2.value = ""
			form1.userpass.focus();
			return;			
		}
		
		if (form1.userpass2.value != form1.userpass.value){
			alert('<?php echo $Admin_Operater[TowPwDiff]?>'); //"两次输入的密码不一致！"
			form1.userpass.value = ""
			form1.userpass2.value = ""
			form1.userpass.focus();
			return;			
		}
		<?php
		}
		?>
	   if (form1.truename.value == ""){
			alert('<?php echo $Admin_Operater[PleaseInputTrueName]?>');//"请输入姓名！"
			form1.truename.focus();
			return;			
		}
		form1.submit();
	}
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_operater_save.php' method='post' >
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
                            <a href="admin_operater_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                            <TD noWrap align=right>管理員組：</TD>
                            <TD align=left noWrap><select name="groupid" id="groupid">
                              <?php
                      $Sql      = "select * from `{$INFO[DBPrefix]}operatergroup` ";
					  $Query    = $DB->query($Sql);
					  while ($Rs=$DB->fetch_array($Query)) {
					  ?>
                              <option value="<?php echo $Rs['opid'];?>" <?php if($groupid==$Rs['opid']) echo "selected";?>><?php echo $Rs['groupname'];?></option>
                              <?php
					  }
					  ?>
                              </select></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Admin_Operater[UserName];//用户名：?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','username',$username,"   class='box_no_pic1'  onmouseover=\"this.className='box_no_pic2'\" onMouseOut=\"this.className='box_no_pic1'\"    maxLength=20 size=20 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Operater[Password] ;//密码?>：</TD>
                            <TD width="38%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('password','userpass','',"   class='box_no_pic1'  onmouseover=\"this.className='box_no_pic2'\" onMouseOut=\"this.className='box_no_pic1'\"      maxLength=40 size=40 ")?></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Operater[ConfigPass];//确认密码?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('password','userpass2','',"   class='box_no_pic1'  onmouseover=\"this.className='box_no_pic2'\" onMouseOut=\"this.className='box_no_pic1'\"       maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>email：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','email',$email,"   class='box_no_pic1'  onmouseover=\"this.className='box_no_pic2'\" onMouseOut=\"this.className='box_no_pic1'\"       maxLength=40 size=40 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Operater[TrueName];//姓名?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','truename',$truename,"  class='box_no_pic1'  onmouseover=\"this.className='box_no_pic2'\" onMouseOut=\"this.className='box_no_pic1'\"      maxLength=40 size=40 ")?></TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>管理員類型：</TD>
                            <TD colspan="2" align=left noWrap>
                              <input type="radio" name="type" id="type" value="0" <?php if($type1==0) echo "checked";?>>PM助理 <input type="radio" name="type" id="type" value="1" <?php if($type1==1) echo "checked";?>>PM <input type="radio" name="type" id="type" value="2" <?php if($type1==2) echo "checked";?>>
                              一般
                              <div style="line-height:35px;">
<i class="icon-warning-sign red_big"></i><span class="red_small">一般管理員角色適用一般網站，商品可一次審核上架，PM助理/PM則適用於供應商版本。</span></div></TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD height="28" align=right noWrap> <?php echo $Admin_Operater[Status];//状态?>：</TD>
                            <TD align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('radio','status',intval($status),$add=array($Basic_Command['Open'],$Basic_Command['Close']))?>					  
                              </TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>管理商品類別：</TD>
                            <TD align=left noWrap><input name="btn_class" type="button" id="btn_class" value="新增一個商品類別">
                              <?php
						if ($_GET['Action'] == "Modi"){
							$c_sql = "select * from `{$INFO[DBPrefix]}operater_class` where opid='" . intval($_GET['opid']) . "'";
							$c_query = $DB->query($c_sql);
							$i = 1;
							while($c_row= $DB->fetch_array($c_query)){
						?>
                              <div>
                                <?php
							echo $Char_class->get_page_select("bid" . intval($i),$c_row['bid'],"  class=\"trans-input\" onchange='getMoreAttrib(this.options[this.selectedIndex].value);'");
							?>
                                </div>
                              <?php
								$i++;
							}
						}else{
							$i = 1;
						}
						?> <a href="#" class="easyui-tooltip" title="若不限制分類權限請勿選擇"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              <div id="extclass"></div><input type="hidden" value="<?=$i?>" name="classcount" id="classcount">
                              </TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
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
