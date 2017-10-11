<?php
include_once "Check_Admin.php";

/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

if ($_POST['Action'] == "Modi"){
	
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bonus`");
	$Result= $DB->fetch_array($Query);
	$Num      = $DB->num_rows($Query);
	if ($Num>0){
		$db_string = $DB->compile_db_update_string( array (
		'rebate'                => intval($_POST['rebate']),
		));
		$Sql = "UPDATE `{$INFO[DBPrefix]}bonus` SET $db_string ";
	}else{
		$db_string = $DB->compile_db_insert_string( array (
		'rebate'                => intval($_POST['rebate']),
		));
		$Sql = "insert into `{$INFO[DBPrefix]}bonus` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	}
	$Result_Insert = $DB->query($Sql);
}else{
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bonus`");
	$Result= $DB->fetch_array($Query);
	$rebate  =  $Result['rebate'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;紅利點數折抵設定</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<div id="contain_out">
  <FORM name='form11' action='' method='post' id="theform">
    <?php  include_once "Order_state.php";?>
  <input type="hidden" name="Action" value="Modi">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;紅利點數折抵設定</SPAN></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:form11.action='admin_bonus_manager.php';form11.submit();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save']?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->							</TD></TR></TBODY></TABLE>				</TD>
                    </TR>
                </TBODY></TABLE></TD></TR>
          </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD align=right noWrap> </TD></TR>
                          <TR>
                            <TD noWrap align=right>折抵總金額最大百分比：</TD>
                            <TD width="82%" align=left noWrap>
                            <?php echo $FUNCTIONS->Input_Box('text','rebate',$rebate,"  id=\"rebate\" class=\"box_no_pic1\" onmouseover=\"this.className=box_no_pic2\" onMouseOut=\"this.className=box_no_pic1\" maxLength=30 size=30 ")?> %					  </TD>
                            </TR>
                          
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
      </TBODY></TABLE>

  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
