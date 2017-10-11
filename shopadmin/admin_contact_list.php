<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";

$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
$Sql      = "select * from `{$INFO[DBPrefix]}contact`  order by idate desc ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Hz]?>--&gt;<?php echo $JsMenu[Hz_List]?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>function toDel(){	var checkvalue;	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');	if (checkvalue!=false){		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){			document.adminForm.action = "admin_contact_save.php";			document.adminForm.act.value="Del";			document.adminForm.submit();		}	}}function toEdit(id,catid){	var checkvalue;	var catvalue = "";	if (id == 0) {		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');	}else{		checkvalue = id;	}	if (checkvalue!=false){		document.adminForm.action = "admin_contact.php?Action=View&contact_id="+checkvalue;		document.adminForm.act.value="View";		document.adminForm.submit();	}}</SCRIPT>
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
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Hz]?>--&gt;<?php echo $JsMenu[Hz_List]?></SPAN>
                    </TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <?php if ($Ie_Type != "mozilla") { ?>
                  <?php } else {?>
                  <TR>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle></TD>
                  </TR>
                  <?php } ?>
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
            <TD align=right colSpan=2 height=31>&nbsp;            </TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=p9black onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
            </TD>
          </TR>
        </FORM>
  </TABLE>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden value=0  name=boxchecked>
                          <TBODY>
                            <TR align=middle>
                              <TD class=p9black noWrap align=center  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                                <INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="222"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><i class="icon-user" style="font-size:14px;margin-right:5px; margin-left:5px; color:#666;"></i> <?php echo $Admin_sys_Pack[Sys_Lxr];//联系人?></TD>
                              <TD height="26" colspan="2" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><i class="icon-comment" style="font-size:14px; margin-right:5px; margin-left:5px; color:#666;"></i> <?php echo $Admin_sys_Pack[Sys_Hzfs];//合作方式?> </TD>
															<TD height="26" colspan="2" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 處理狀態 </TD>

                              <TD width="285" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><i class="icon-time" style="font-size:14px; margin-right:5px; margin-left:5px; color:#666;"></i> <?php echo $Basic_Command['Idate_say'];//时间?></TD>
                              </TR>
                            <?php
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {
						switch ($Rs['state']) {							case '0':								$state="等待處理";								break;							case '1':								$state="處理中";								break;							case '2':								$state="已處理";								break;						}

					?>
                            <TR class=row0>
                              <TD align=center height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['contact_id']?>' name=cid[]> </TD>
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['contact_id']?>',0);"><?php echo $Rs['companyname']?></a></TD>
                              <TD height=26 colspan="2" align="left" noWrap><?php echo $Rs['hz1'] ?></TD>															<TD height=26 colspan="2" align="left" noWrap><?php echo $state ?></TD>
                              <TD height=26 align="left" noWrap><?php echo date("Y-m-d H:i a",$Rs['idate'])?></TD>
                              </TR>
                            <?php
					$j++;
					$i++;
					}
					?>
                            <TR>
                              <TD align=middle width=79 height=14>&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              <TD width=372 height=14>&nbsp;</TD>
                              <TD height=14 colspan="2">&nbsp;</TD>
                              </TR>
                            <?php  if ($Num==0){ ?>
                            <TR align="center">
                              <TD height=14 colspan="5"><?php echo $Basic_Command['NullDate']?></TD>
                              </TR>
                            <?php } ?>
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
              </TABLE>
              <?php } ?>
            </TD>
          </TR>
  </TABLE>
</div>
 <div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>