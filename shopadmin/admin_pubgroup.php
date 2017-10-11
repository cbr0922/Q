<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$Pub_id        = intval($FUNCTIONS->Value_Manage($_GET['pub_id'],$_POST['pub_id'],'back',''));
$Query = $DB->query("select publication_title,publication_start_time,publication_end_time,publication_content from `{$INFO[DBPrefix]}publication` where publication_id=".intval($Pub_id)." limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$publication_title        =  $Result['publication_title'];
	$publication_start_time   =  $Result['publication_start_time'];
	$publication_end_time     =  $Result['publication_end_time'];
	$publication_content      =  nl2br($Result['publication_content']);
	$publication_alreadyread  =  $Result['publication_alreadyread'];
}else{
	echo "<script language=javascript>javascript:window.history.back();</script>";
	exit;
}
$DB->free_result($Query);
unset ($Query);
unset ($Num);
unset ($Result);

$Query  = $DB->query(" select mgroup_name,mgroup_id from `{$INFO[DBPrefix]}mail_group` order by mgroup_id  asc  ");
$Num    = $DB->num_rows($Query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<link href="../FCKeditor/editor/css/fck_editorarea.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Mail_Pack[SelectSendEmailGroup];//选择電子報接收组?></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" 
background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<SCRIPT language=javascript>
	function checkform(num)
	{
		var mark = 0 ;
		for (var i=0; i < num; i++)
		{
			cb = eval( 'document.form1.cb'+i );
			if (cb.checked == true)
			{
				mark = 1;
			}
		}
		if (mark == 0)
		{
			alert('<?php echo $Mail_Pack[PleaseSelectSendEmailGroup];?>'); //"请选择发送邮件组！"
			return;
		}
		document.form1.submit();
	}
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name='form1' action='admin_pubsend_new.php' method='post' >
  <input type="hidden" name="Action" value="Send">
  <input type="hidden" name="pub_id" value="<?php echo $Pub_id?>">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Mail_Pack[SelectSendEmailGroup];//选择電子報接收组?>
                      </SPAN></TD>
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
                            <a href="admin_publication_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform(<?php echo $Num?>);"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Mail_Pack[SendEmail]?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
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
                            <TD noWrap align=right> <?php echo $Mail_Pack[EmailBaoName];//会刊标题?>： </TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','publication_title',$publication_title,"      maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Mail_Pack[LastModiTime];//最后修改日期?>：</TD>
                            <TD width="38%" align=left noWrap><?php echo  $Last_moditime = $publication_start_time!="" ? date("Y-m-d H: i a",$publication_start_time) : date("Y-m-d H: ia",time()) ;?></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Mail_Pack[LastSendTime];//最后发送日期?>：</TD>
                            <TD align=left noWrap><?php echo  $Last_sendtime = $publication_end_time!="" ? date("Y-m-d H: i a",$publication_end_time) : "" ;?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          
                          <TR>
                            <TD align=right valign="top" noWrap> <?php echo $Mail_Pack[SendEmailGroup];//電子報接收組?>：</TD>
                            <TD colspan="4" rowspan="3" align=left noWrap>
                              
                              <TABLE class=allborder cellSpacing=0 cellPadding=2  width="50%" bgColor=#f7f7f7 border=0>
                                <TBODY>
                                  <?      
					 $i=0;
					 while ($Rs=$DB->fetch_array($Query)) {
					?>
                                  <TR>
                                    <TD noWrap><INPUT id='cb<?php echo $i?>' type=checkbox value='<?php echo $Rs['mgroup_id']?>'   name='Mgroup_id[]'> &nbsp;<?php echo $Rs['mgroup_name']?> </TD>
                                    </TR>
                                  <?
					$i++;
					 }
					?>	  
                                  <TR>
                                    <TD noWrap>                            送件地址 &nbsp;<textarea name="maillist" cols="60" rows="10" id="maillist"></textarea> </TD>
                                    </TR>
                                  </TBODY>
                                </TABLE>
                              
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD colspan="3" align=right noWrap bgcolor="#F7F7F7">&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Mail_Pack[EmailBaoContent];//会刊内容?>：</TD>
                            <TD align=left bgcolor="#FFFFFF"><?php echo $publication_content?></TD>
                            <TD colspan="3" align=right noWrap bgcolor="#F7F7F7">&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD colspan="3" align=right noWrap bgcolor="#F7F7F7">&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD colspan="3" align=right noWrap bgcolor="#F7F7F7">&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
