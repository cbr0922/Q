<?php
@header("Content-type: text/html; charset=utf-8");
include "Check_Admin.php";
include Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$gSql = "select * from `{$INFO[DBPrefix]}mail_group` where mgroup_id='" . intval($_GET['group_id']) . "'";
$gQuery    = $DB->query($gSql);
$Result = $DB->fetch_array($gQuery);
$mgroup_name = $Result['mgroup_name'];

if(intval($_GET['group_id'])==2){
	$Sql = "select u.email,u.username,u.memberno,u.true_name from `{$INFO[DBPrefix]}user` u ";
}elseif(intval($_GET['group_id'])==3){
	$Sql = "select u.email,u.username,u.memberno,u.true_name from `{$INFO[DBPrefix]}user` u where dianzibao=0";
}elseif(intval($_GET['group_id'])==5){
	$Sql = "select u.email,u.username,u.memberno,u.true_name from `{$INFO[DBPrefix]}user` u where dianzibao1=0";
}else{
	$Sql = "select m.*,u.username,u.memberno,u.true_name from `{$INFO[DBPrefix]}mail_group_list` as m left join `{$INFO[DBPrefix]}user` as u on u.user_id=m.user_id where group_id='" . intval($_GET['group_id']) . "' order by m.email asc ";
}

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query    = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Shop_Pager];//EDM管理?>--&gt;<?php echo $JsMenu[Email_Group_Set];//邮件组设定?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<div id="fullBg"></div>
<div id="msg">
<div id="close"></div>
<div id="ctt"></div>
</div>
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toEdit(id){
	var checkvalue;
	var catvalue = "";

	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}


	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_group.php?Action=Modi&group_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_group_edit.php";
			document.adminForm.act.value="Delete";
			document.adminForm.submit();
		}
	}
}


</SCRIPT>
<script language="javascript">
function su(mid,auto){
    if (auto==1 || auto==0){
	    document.form1.action="admin_group_excel.php";
		document.form1.mgroup_id.value=mid;
		form1.submit();
	}
	if (auto==2){
		document.form1.action="admin_group_excel_dingyue.php";
		document.form1.mgroup_id.value=mid;
		form1.submit();
	}
}
</script>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
<form name="form1" method="post" action="" target='excelOut'  >
<input type="hidden" name="Action" value="Excel">
<input type="hidden" name="mgroup_id" >
</form>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Shop_Pager];//EDM管理?>--&gt;<?php echo $JsMenu[Email_Group_Set];//邮件组设定?>--><?php echo $mgroup_name;?></SPAN>
                    </TD>
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
																	<TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:showWin('url','admin_group_edit.php?group_id=<?php echo intval($_GET['group_id']);?>','',300,200);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增會員Email</a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
										<TD align=middle>
											<TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
												<TBODY>
													<TR>
														<TD align=middle width=79><!--BUTTON_BEGIN-->
															<TABLE>
																<TBODY>
																	<TR>
																		<TD vAlign=bottom noWrap class="link_buttom"><a href="admin_group_ding.php?Action=Modi&group_id=<?php echo intval($_GET['group_id']);?>"><IMG src="images/<?php echo $INFO[IS]?>/fb-edit.gif" border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE>

										<TD align=middle>
											<TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
												<TBODY>
													<TR>
														<TD align=middle width=79><!--BUTTON_BEGIN-->
															<TABLE>
																<TBODY>
																	<TR>
																		<TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

									<TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                            <a href="admin_group_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>

                </TR>
              </TBODY>
              </TABLE>
            </TD>
          </TR>
        </TBODY>
  </TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=optForm method=get action="">
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=left colSpan=2 height=31>&nbsp;</TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示?><?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
            </TD>
          </TR>
        </FORM>
  </TABLE>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign="top">
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR valign="top">
                    <TD bgColor=#ffffff>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
													<INPUT type=hidden name=group_id value="<?php echo intval($_GET['group_id'])?>">
                          <INPUT type=hidden value=0  name=boxchecked>
                          <TR align=top>
														<TD width=20 class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                              <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                            <TD width="122"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> Email</TD>
														<!--TD width="50" height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>編輯Email</TD-->
                            <TD width="105" height="26" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>  會員編號<br></TD>
                            <TD width="205" height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>帳號</TD>
                            <TD width="173" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>真實姓名</TD>
                          </TR>
                          <?php
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                          <TR valign="top" class=row0>
														<TD align=middle width=20 height=20>
                              <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs['user_id']?>' name="cid[]"> </TD>
                            <TD height=20 align="left" valign="middle" noWrap>
                              <?php echo $Rs['email'] ?>
														</TD>
														<!--TD height=20 align="center" valign="middle" noWrap>
															<A href="javascript:showWin('url','admin_group_edit.php?Action=Modi&group_id=<?php echo intval($_GET['group_id']);?>&user_id=<?php echo $Rs['user_id'];?>','',300,200);">編輯</A>
														</TD-->
                            <TD height=20 align="left" valign="middle" noWrap>
                              <A href="admin_member.php?Action=Modi&user_id=<?php echo $Rs['user_id'];?>"><?php echo  $Rs['memberno']?></A>
                            </TD>
                            <TD height=20 align="left" valign="middle" noWrap><?php echo  $Rs['username']?>
                              </TD>
                            <TD align="center" valign="middle" noWrap style="padding-left:10px"><?php echo  $Rs['true_name']?></TD>
                          </TR>
                          <?php
					$i++;
					}
					?>
                          <TR>
                            <TD height=14 colspan="4" align=middle>&nbsp;</TD>
                          </TR>
                        </FORM>
                      </TABLE>
                    </TD>
                  </TR>
              </TABLE>
              <?php  if ($Num>0){ ?>
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                    </TD>
                  </TR>
                  <?php } ?>

        </TABLE></TD></TR></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
<iframe name="excelOut" src="about:blank" scrolling="no" FrameBorder="0" width="100%" height="1"></iframe>
</BODY></HTML>
