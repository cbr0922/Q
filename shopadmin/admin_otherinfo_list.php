<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

$objClass = "9pv";
$Where    = '';
$Nav      = new buildNav($DB,$objClass);


if (isset($_GET['where'])) {
	$Where = urldecode($_GET['where']);
	$Where = str_replace('wodedanyinhao',"'",$Where);

}else{
	$Where    = (trim($_GET['skey'])!="") ?  " where title like '%".urldecode(trim($_GET['skey']))."%'" : "" ;
}

$Sql      = "select * from `{$INFO[DBPrefix]}admin_info` ".$Where." order by info_id  ";

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
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD><TITLE>網站資訊管理</TITLE>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
        /*****************************************************
         * 滑鼠hover變顏色
         ******************************************************/
$(document).ready(function() {
$("#orderedlist tbody tr").hover(function() {
		$(this).addClass("blue");
	}, function() {
		$(this).removeClass("blue");
	});
});
</script>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>

function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&info_id="+catid;
	}
	
	if (checkvalue!=false){
		
		document.adminForm.action = "admin_otherinfo.php?Action=Modi&info_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}
function toNew(id,catid){
	    document.adminForm.action = "admin_otherinfo.php?Action=add";
		document.adminForm.submit();
	
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){   //您是否确认删除选定的记录
			document.adminForm.action = "admin_otherinfo_save.php";
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
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange>系統資訊-->網站資訊管理</SPAN>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toNew();"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增網站資料</a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            </TD>
                          </TR>
                        </TBODY>
                      </TABLE>
                    </TD>                  
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
                    <TD bgColor=#ffffff class=p9black><table width="221" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="38" height="38" align="right"><i class="icon-warning-sign" style="font-size:14px;margin-right:4px; color:#06F"></i></td>
                        <td width="183" class=p9black> 1~18為系統保留項目</td>
                        </tr>
                      </table>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          
                          <TBODY>
                            <TR align=middle>
                              <TD width="64" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle></TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['No'];//編號?></TD>
                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>標題名稱</TD>
                              <TD width="84" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>訪問ID</TD>
                              <TD width="169" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>路徑名稱</TD>
                              <TD width="67" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>語言</TD>
                              </TR>
                            <?php               

					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?><TBODY>
                              <TR class=row0>
                                <TD align=center width=64 height=26>
                                  <?php 
					  if (intval($Rs['info_id'])<=23){					    
					   $Disabled = "disabled='disabled'";
					  }else{
					   $Disabled = "";
					  }					  
					  ?>
                                <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs['info_id']?>' name="cid[]" <?php echo $Disabled  ?>></TD>
                                <TD height=26 align="left" noWrap><?php echo  $i+1;?></TD>
                                <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['info_id']?>',0);"> <?php echo $Rs['title']?>&nbsp; </A></TD>
                                <TD align="center" noWrap><?php echo $Rs['top_id']==0?$Rs['info_id']:$Rs['top_id']?></TD>
                                <TD align="left" noWrap><?php echo $Rs['path']?></TD>
                                <TD align="center" noWrap><?php echo $Rs['language']?></TD>
                              </TR></TBODY>
                          <?php
					$i++;
					}

					?>
                          <TR>
                            <TD width=64 height=14 nowrap>&nbsp;</TD>
                            <TD align=middle width=73 height=14>&nbsp;</TD>
                            <TD width=626 height=14>&nbsp;</TD>
                            </TR>
                          <?php  if ($Num==0){ ?>
                          <TR align="center">
                            <TD height=14 colspan="3"><?php echo $KeFu_Pack['nodata'];//"無相關資料?></TD>
                            </TR>
                          <?php } ?>	
                          </FORM>
                        </TABLE>
                     </TD></TR>
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
