<?php
include_once "Check_Admin.php";

/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

if($_POST['Action']=="save"){
	$Sql      = "select * from `{$INFO[DBPrefix]}track` order by trid asc";
	$Query    = $DB->query($Sql);
	while ($Rs=$DB->fetch_array($Query)) {
		$u_Sql = "update `{$INFO[DBPrefix]}track` set trackcode='" . $_POST['trackcode' . $Rs['trid']] . "' , trackcode2='" . $_POST['trackcode2' . $Rs['trid']] . "' where trid='" . $Rs['trid'] . "'";
		$DB->query($u_Sql);
	}
	echo "<script language=javascript>location.href='admin_track.php';</script>";
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Basic_Info]?></TITLE>
</HEAD>
<BODY onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		form1.submit();
	}


</SCRIPT>
<div id="contain_out">
  
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
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Basic_Info]?></SPAN></TD>
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
                    <FORM name='form1' action='admin_track.php' method='post' id="theform">
                    <input type="hidden" name="Action" value="save">
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                        <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD width="20%" align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                          </TR>
                        <?php
                        $Sql      = "select * from `{$INFO[DBPrefix]}track` order by trid asc";
						$Query    = $DB->query($Sql);
						while ($Rs=$DB->fetch_array($Query)) {
						?>
                          <TR>
                            <TD noWrap align=right width="16%"><?php echo $Rs['trackname'];?></TD>
                            <TD  align=left noWrap>
                            <?php
                            switch($Rs['inputtype']){
								case 0:
							?>
                            <input type="text" name="trackcode<?php echo $Rs['trid'];?>" id="trackcode" value="<?php echo $Rs['trackcode'];?>" />
							<?php
									break;
								case 1:
							?>
                            <textarea name="trackcode<?php echo $Rs['trid'];?>" cols="40" rows="5"><?php echo $Rs['trackcode'];?></textarea>
							<?php
									break;
								case 2:
							?>
                            <input type="text" name="trackcode<?php echo $Rs['trid'];?>" id="trackcode" value="<?php echo $Rs['trackcode'];?>" />
                            ID
                            <input type="text" name="trackcode2<?php echo $Rs['trid'];?>" id="trackcode2" value="<?php echo $Rs['trackcode2'];?>" />
							<?php
									break;
							}
							?>
                            </TD>
                            <TD  align=left noWrap><?php echo $Rs['remark'];?></TD>
                            </TR>
						<?php
						}
						?>
						
                           <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                          </TR>
                          </TBODY>
                  </TABLE></FORM></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
    </TR>
  
</div>
                      <div align="center"><?php include_once "botto.php";?></div>

</BODY>
</HTML>