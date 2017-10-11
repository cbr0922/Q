<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Sql = "select tg.*,tp.* from `{$INFO[DBPrefix]}transgroup` as tg left join `{$INFO[DBPrefix]}transportation` as tp on tg.trans_id=tp.transport_id ";

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
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;地區運費管理</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		document.adminForm.action = "admin_areagroup.php?Action=Modi&group_id="+checkvalue;
		document.adminForm.Action.value="Modi";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_areagroup_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>                      
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%"><table cellspacing=0 cellpadding=0 border=0>
              <tbody>
                <tr>
                  <td width=38><img height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32 /></td>
                  <td class=p12black><span  class=p9orange> <?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;地區運費管理</span></td>
                </tr>
              </tbody>
            </table></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><A href="admin_areagroup.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增地區運費</A></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><A href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></A></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><A href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></A></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle></TD>
                </TR>
              </TBODY>
              </TABLE>
            </TD>
          </TR>
        </TBODY>
</TABLE>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                    <FORM name=adminForm action="" method=post>
                      <INPUT type=hidden name=act>
                      <INPUT type=hidden name=Action>
                      <INPUT type=hidden value=0  name=boxchecked> 
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        
                          <TBODY>
                            <TR align=middle>
                              <TD width="4%" height=26 align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="5%" align="center" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>序號</TD>
                              <TD width="11%"  height=26 align="left" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>地區運費組名</TD>
                              <TD width="8%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>貨運方式</TD>
                              <TD width="8%" align="center" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>運費</TD>
                              <TD width="102" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>免運費額</TD>
                              <TD width="8%" align="center" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>續重</TD>
                              <TD width="56%" align="left" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>運達地區</TD>
                              </TR>
                            <?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {


					?><TBODY>
                            <TR class=row0>
                              <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['group_id']?>' name=group_id[]> </TD>
                              <TD align="center"><?php echo $j?></TD>
                              <TD height=26 align="left"><a href="admin_areagroup.php?Action=Modi&group_id=<?php echo $Rs['group_id']?>"><?php echo $Rs['groupname']?></a></TD>
                              <TD align="left"><?php echo $Rs['transport_name']?></TD>
                              <TD align="center"><?php echo $Rs['money']?></TD>
                              <TD align="center" noWrap><?php echo $Rs['mianyunfei']?></TD>
                              <TD align="center"><?php echo $Rs['permoney']?></TD>
                              <TD align="left" >
                                <?php
					  $tag_sql = "select at.*,a.* from `{$INFO[DBPrefix]}areatrans` at left join `{$INFO[DBPrefix]}area` as a on at.area_id=a.area_id where at.group_id='" . intval($Rs['group_id']) . "'";
						  $Query_tag= $DB->query($tag_sql);
						  while($Rs_tag=$DB->fetch_array($Query_tag)){
						  	echo $Rs_tag['areaname'] . "&nbsp;&nbsp; ";
						  }
					  ?>					  </TD>
                              </TR></TBODY>
                            <?php
					$j++;
					$i++;
					}
					?>
                            <TR>
                              <TD align=middle height=14>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            <?php  if ($Num==0){ ?>
                            <TR align="center">
                              <TD height=14 colspan="8"><?php echo $Basic_Command['NullDate']?></TD>
                              </TR>
                            <?php } ?>	
                        </TABLE>
                        </FORM>
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
    </TD>
    </TR>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
