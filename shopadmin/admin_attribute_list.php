<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
$Sql      = "select * from `{$INFO[DBPrefix]}attribute` order by attrid asc ";

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
<TITLE>商品類別屬性管理--&gt;類別屬性列表</TITLE>
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
		document.adminForm.action = "admin_attribute.php?Action=Modi&attrid="+checkvalue;
		document.adminForm.Action.value="Modi";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_attribute_save.php";
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
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange>
                      商品類別屬性管理--&gt;類別屬性列表</SPAN>
                      </TD>
                    </TR>
                  </TBODY>
                </TABLE>
              
              </TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><A href="admin_attribute.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增類別屬性</A></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden name=Action>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD width="20" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="45" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>序號</TD>
                              <TD width="724"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 屬性名稱</TD>
                              <TD width="172" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>屬性值管理</TD>
                              </TR>
                            <?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {


					?><TBODY>
                              <TR class=row0>
                                <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'   onclick=isChecked(this); type=checkbox value='<?php echo $Rs['attrid']?>' name=attrid[] <?php if($Rs['attrid']<=3) echo "disabled";?>> </TD>
                                <TD align="center" noWrap><?php echo $j?></TD>
                                <TD height=26 align="left" noWrap><a href="admin_attribute.php?Action=Modi&attrid=<?php echo $Rs['attrid']?>"><?php echo $Rs['attributename']?></a></TD>
                                <TD height=26 align="center" noWrap><div class="link_box" style="width:95px;float:left"><a href="admin_attributevalue.php?attrid=<?php echo $Rs['attrid']?>"><i class="icon-plus-sign"></i> 新增屬性值</a></div> <div class="link_box" style="width:95px;float:left"><a href="admin_attributevalue_list.php?attrid=<?php echo $Rs['attrid']?>"><i class="icon-list"></i> 屬性值列表</a></div></TD>
                              </TR></TBODY>
                          <?php
					$j++;
					$i++;
					}
					?>
                          <?php  if ($Num==0){ ?>
                          <TR align="center">
                            <TD height=14 colspan="4"><?php echo $Basic_Command['NullDate']?></TD>
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
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23 class=p9gray>
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
