<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
if ($_GET['contact_id']!="" && $_GET['Action']=='View'){
	$contact_id = intval($_GET['contact_id']);
	$Action_value = "";
	$Action_say  =  $Basic_Command['View'] ; //查看
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}contact` where contact_id=".intval($contact_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Rs= $DB->fetch_array($Query);		$contact_id			=	 $Rs['contact_id'];
		$companyname    =  $Rs['companyname'];
		$companyname1   =  $Rs['companyname1'];
		$address        =  $Rs['address'];
		$title          =  $Rs['title'];
		$lxr            =  $Rs['lxr'];
		$zc             =  $Rs['zc'];
		$fax            =  $Rs['fax'];
		$tel_one        =  $Rs['tel_one'];
		$tel_two        =  $Rs['tel_two'];
		$mobile         =  $Rs['mobile'];
		$email          =  $Rs['email'];
		$url            =  $Rs['url'];
		$content        =  $Rs['content'];
		$hz1        		=  $Rs['hz1'];
		$state        	=  $Rs['state'];


	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Hz];//合作提案?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?><SCRIPT language=javascript>

function checkform(){

			document.adminForm.action = "admin_contact_save.php";

			document.adminForm.submit();

}

</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Hz];//合作提案?>--&gt;<?php echo $Action_say?></SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>

                          <TR>														<TD align=middle width=79><!--BUTTON_BEGIN-->															<TABLE><TBODY>																<TR>																	<TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>																</TR></TBODY></TABLE><!--BUTTON_END--></TD>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                                    <TD vAlign=bottom noWrap class="link_buttom">
                                <a href="admin_contact_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD>
                          </TR>



                        </TBODY></TABLE></TD>

              </TR></TBODY></TABLE></TD></TR>
    </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>					<FORM name=adminForm action="" method=post>
						<INPUT type=hidden name="act" value="Update">
						<INPUT type=hidden name="cid" value=<?php echo $contact_id;?>>

                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="25%">&nbsp;</TD>
                            <TD colspan="3" align=right noWrap>&nbsp;</TD></TR>

                          <TR>
                            <TD noWrap align=right>公司名稱：</TD>
                            <TD align=left noWrap><?php echo $companyname1;?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_Lxr]?>：</TD>
                            <TD width="17%" align=left noWrap><?php echo $companyname;?></TD>
                            <TD width="11%" align=right noWrap>&nbsp;</TD>
                            <TD width="47%" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>抬頭：</TD>
                            <TD width="17%" align=left noWrap><?php echo $title;?></TD>
                            <TD width="11%" align=right noWrap>&nbsp;</TD>
                            <TD width="47%" align=left noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_Tel]?>：</TD>
                            <TD align=left noWrap><?php echo $tel_one;?>-<?php echo $tel_two;?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD rowspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right noWrap>手機：</TD>
                            <TD align=left noWrap><?php echo $mobile;?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Email]?>：</TD>
                            <TD align=left noWrap><?php echo $email?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>網址：</TD>
                            <TD align=left noWrap><?php echo $url?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo  $Admin_sys_Pack[Sys_Hzfs]?>：</TD>
                            <TD align=left noWrap><?php echo $hz1;?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Admin_sys_Pack[Sys_Bz]?>：</TD>
                            <TD colspan="2" align=left><?php echo nl2br($content)?></TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>處理狀態：</TD>
                            <TD align=left noWrap>															<select name="status">                      					<option value="0" <?php if($state==0) echo "selected";?>>等待處理</option>                      					<option value="1" <?php if($state==1) echo "selected";?>>處理中</option>                      					<option value="2" <?php if($state==2) echo "selected";?>>已處理</option>                    					</select>														</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                    </TBODY>								</FORM>							</TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>