<?php
@header("Content-type: text/html; charset=utf-8");
include "Check_Admin.php";
include Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Where    = $_GET['skey']!=""  && trim(urldecode($_GET['skey']))!=$Mail_Pack[PleaseInputEmailGroupName] ?  " where mgroup_name like '%".$_GET['skey']."%'" : $Where ;
$Sql      = "select * from `{$INFO[DBPrefix]}mail_group` ".$Where." order by mgroup_id asc ";

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
			document.adminForm.action = "admin_group_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}


</SCRIPT>
<script language="javascript">
function su(mid,auto){
   // if (auto==1 || auto==0){
	    document.form1.action="admin_group_excel.php";
		document.form1.mgroup_id.value=mid;
		form1.submit();
	//}
	//if (auto==2){
	//	document.form1.action="admin_group_excel_dingyue.php";
	//	document.form1.mgroup_id.value=mid;
	//	form1.submit();
	//}
}
</script>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
<form name="form1" method="post" action="" >
<input type="hidden" name="Action" value="Excel">
<input type="hidden" name="mgroup_id"  id="mgroup_id">
</form>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Shop_Pager];//EDM管理?>--&gt;<?php echo $JsMenu[Email_Group_Set];//邮件组设定?></SPAN>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_AutoCreateMemberlist.php"><IMG  src="images/<?php echo $INFO[IS]?>/email_automtic.gif"  border=0>&nbsp;<?php echo $Mail_Pack[AutoCreateMemberEmailList];//自动生成会员邮件列表?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

									<TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_group_ding.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Mail_Pack[AddEmailGroup];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

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
            <TD align=left colSpan=2 height=31>
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width=500 border=0>
                <TBODY>
                  <TR>
                    <TD height=31 align=left>
                      <INPUT  name='skey'  onfocus=this.select()  onclick="if(this.value=='<?php echo $Mail_Pack[PleaseInputEmailGroupName]?>')this.value=''"  onmouseover=this.focus() value='<?php echo $Mail_Pack[PleaseInputEmailGroupName]?>' size="30">			     &nbsp;&nbsp;
                      <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle">                </TD>
                  </TR>
                </TBODY>
              </TABLE>
            </TD>
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
                          <INPUT type=hidden value=0  name=boxchecked>
                          <TR align=top>
                            <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                              <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                            <TD width="122"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Basic_Command['SNo_say'];//序号?></TD>
                            <TD width="105" height="26" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>  <?php echo $Mail_Pack[EmailGroupName];//组名称?><br></TD>
														<TD width="50" height="26" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>編輯名稱</TD>
                            <TD width="205" height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Mail_Pack[TheEmailNum];?></TD>
                            <TD width="173" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>更新名單</TD>
                            <TD width="323" height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>&nbsp;					  </TD>
                          </TR>
                          <?php
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                          <TR valign="top" class=row0>
                            <TD align=middle width=84 height=20>
                              <?php if ($Rs['auto']==2){
					     $Disabled = 'disabled="disabled"';
					   }else{
					     $Disabled = '';
					   }
					  ?>
                              <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);"  <?php echo $Disabled ?> type="checkbox" value='<?php echo $Rs['mgroup_id']?>' name="cid[]"> </TD>

                            <TD height=20 align="left" valign="middle" noWrap>
                              <?php echo $i+1 ?>                        </TD>
                            <TD height=20 align="left" valign="middle" noWrap>
                              <A href="admin_group_mail.php?group_id=<?php echo $Rs['mgroup_id'];?>"><?php echo  $Rs['mgroup_name']?></A>
                            </TD>
														<TD height=20 align="center" valign="middle" noWrap>
															<A href="javascript:showWin('url','admin_group_name.php?mgroup_id=<?php echo $Rs['mgroup_id'];?>','',300,250);">編輯</A>
														</TD>
                            <TD height=20 align="right" valign="middle" noWrap>
                              <?php
							  if($Rs['searchlist']=="All"){
								  $gSql = "select count(*) as counts from `{$INFO[DBPrefix]}user` ";
							  }elseif($Rs['searchlist']=="noDing"){
								  $gSql = "select count(*) as counts from `{$INFO[DBPrefix]}user` where dianzibao=0";
							  }else{
								  $gSql = "select count(*) as counts from `{$INFO[DBPrefix]}mail_group_list` where group_id='" . $Rs['mgroup_id'] . "'";
							   }
								  $gQuery    = $DB->query($gSql);
									$Result = $DB->fetch_array($gQuery);
								echo $Result['counts'];

							  ?>
                              </TD>
                            <TD align="center" valign="middle" noWrap style="padding-left:10px"><a href="admin_group_save.php?group_id=<?php echo $Rs['mgroup_id'];?>&act=fresh">更新</a></TD>
                            <TD height=20 align="center" valign="middle" noWrap style="padding-left:10px">
                              <div class="link_box" style="width:80px"><a href="javascript:su('<?php echo $Rs['mgroup_id']?>','<?php echo $Rs['auto'];?>');">下載名單</a></div> <!--input name="button" type="button" class="search_btn" onClick="javascript:su('<?//=$Rs['mgroup_id']?>');"  value="導出EXCEL表"-->					  </TD>
                          </TR>
                          <?php
					$i++;
					}
					?>
                          <TR>
                            <TD height=32 colspan="6" align=middle>*按照新建郵件組條件進行更新</TD>
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
