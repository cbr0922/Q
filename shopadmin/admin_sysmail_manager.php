<?php
include_once "Check_Admin.php";
/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";
if ($_POST['Action'] == "Modi"){
	if(is_array($_POST['mail'])){
		$mail = implode(",",$_POST['mail']);	
	}
	$db_string = $DB->compile_db_update_string( array (
		'email'                => trim($_POST['email']),
		'maillist'                => $mail,
		));
		$Sql = "UPDATE `{$INFO[DBPrefix]}administrator` SET $db_string where sa_id='". $_SESSION['sa_id'] ."'";
	$Result_Insert = $DB->query($Sql);
	echo "<script language=javascript>location.href='admin_sysmail_manager.php';</script>";
}
$Sql   = "select * from `{$INFO[DBPrefix]}administrator` where sa_id='". $_SESSION['sa_id'] ."'  limit 0,1";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result = $DB->fetch_array($Query);
	$email = $Result['email'];
	$maillist = $Result['maillist'];
	$maillist_array = explode(",",$maillist);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;郵件發送郵箱設置</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
  <FORM name='form11' action='' method='post' id="theform">
  <input type="hidden" name="Action" value="Modi">
  <!--按鈕-->
  <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0 style="margin-top:10px;margin-bottom:5px">
    <TBODY>
      <TR>
        <TD width="50%"><TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
            <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;郵件管理--&gt;郵件發送郵箱設置</SPAN></TD>
              </TR>
            </TBODY>
          </TABLE></TD>
        <TD align=right width="50%">&nbsp;</TD>
      </TR>
    </TBODY>
  </TABLE>
  <!--按鈕-->

<form action="" method="post"><input type="hidden" name="Action" value="Modi">
      <table class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
          <tr>
            <td width="18%" height="38" align="right" valign="bottom">Email：</td>
            <td width="82%" valign="bottom"><input name="email" id="email" value="<?php echo $email;?>" /></td>
          <tr>
            <td align="right">欲接收郵件：</td>
            <td><?php
                            $Sql_m      = "select * from `{$INFO[DBPrefix]}sendtype` where ifadmin=1";
							$Query_m    = $DB->query($Sql_m);
							while ($Rs_m=$DB->fetch_array($Query_m)) {
							?>
              <input type="checkbox" name="mail[]" value="<?php echo $Rs_m['sendtype_id'];?>" <?php if(in_array($Rs_m['sendtype_id'],$maillist_array) ) echo "checked";?> /><?php echo $Rs_m['sendname'];?>
                            <?php
							}
							?></td>
          <tr>
            <td>&nbsp;</td>
            <td><button name="Submit" type="submit" value="保存" size="20" />
              保存
              </button><p></p></td>
        </table>
        </form>

</div>
<div align="center">
  <?php include_once "botto.php";?>
</div>
</BODY>
</HTML>
