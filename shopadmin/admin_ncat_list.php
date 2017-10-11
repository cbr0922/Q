<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Article_Pack.php";
$Query = $DB->query("select * from `{$INFO[DBPrefix]}nclass` order by top_id asc");
$Num  = $DB->num_rows($Query);
if ($Num<=0){
	$FUNCTIONS->sorry_back('admin_ncat.php','');
}
$DB->free_result($Query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Article_Man];//文章管理?>--&gt;<?php echo $JsMenu[Article_Class_List];//文章类别列表?>  </TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toEdit(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Num?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		document.adminForm.action = "admin_ncat.php?Action=Modi&ncid="+checkvalue;
//		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected(<?php echo $Num?>,'<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_ncat_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}

	function checkform(){			
		
		save();
		//form1.action="admin_otherinfo_act.php";
		form1.submit();
	}
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=adminForm action='admin_pcat.php' method=post>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="341" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Article_Man];//文章管理?>--&gt;<?php echo $JsMenu[Article_Class_List];//文章类别列表?>  </SPAN></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_ncat.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Article_Pack[AddArticle_Class];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit();"><IMG src="images/<?php echo $INFO[IS]?>/fb-edit.gif" border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE>
                    </TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG   src="images/<?php echo $INFO[IS]?>/fb-delete.gif"  border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
              </TD>
            </TR>
          </TBODY></TABLE>
          <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <INPUT type=hidden name=act>
                        <INPUT  type=hidden value=0 name=boxchecked> 
                        <TBODY>
                          <TR align=middle>
                            <TD class=p9black align=middle width=54  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                              <INPUT onclick=checkAll(<?php echo $Num?>); type=checkbox value=checkbox   name=toggle> </TD>
                            <TD width="479" height=26 align="left" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                              <?php echo $Article_Pack[Article_Class_Name];//类别名称?></TD>
                            <TD width="480" align="left" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>編輯</TD>
                              <TD width="290" align="center" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>路徑名稱</TD>
                            <TD width="200" align="center" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>語言版本</TD>
                            </TR>
                          
                          
                          <?php

					if (is_file(RootDocumentShare."/cache/Newsclass_show.php")  && strlen(trim(file_get_contents(RootDocumentShare."/cache/Newsclass_show.php")))>25 ){
						include RootDocumentShare."/cache/Newsclass_show.php";
					}else{
					    $BackUrl = "admin_ncat_list.php";
						include "admin_create_newsclassshow.php";
						exit;
					}
					$i=0;
					$last = "├─";
					$Newsclassshow =  $Char_class->get_page_children($id,$node,$depth=0);
					foreach($Newsclassshow as $key=>$val) {
						$item = str_repeat(" │ ",$val['depth']);

					?><TBODY>				
                          <TR class=row0>
                            <TD align=middle width=54 height=20>
                              <INPUT id='cb<?php echo $i?>' name='ncid[]' onClick="isChecked(this);" type="checkbox" value="<?php echo $val['id']?>" > </TD>
                            <TD width="479" height=26 noWrap>
                              <A href="admin_ncon_list.php?ncid=<?php echo $val['id']?>"><?php echo $item.$last.$val['name'];?></A><?php if ($val['iffb']==0){ ?>  [ <font color="#FF0000"><?php echo $Basic_Command['NoIffb'];?></font> ]<?php } ?></TD>
                            <TD width="480" height=26 noWrap><a href="admin_ncat.php?Action=Modi&ncid=<?php echo $val['id']?>"><img src="images/fb-edit22.gif" border="0" /></A>&nbsp;</TD>
                              <TD noWrap><?php echo $val['path']?>&nbsp;</TD>
                            <TD align="center" noWrap><?php echo $val['language']?></TD>
                            </TR></TBODY>
                          <?php
					$item = '';
					$i++;
					}
					?>
                        </TABLE>
           </TD>
          </TR>
          </TBODY>
         </TABLE>
</FORM>

</div>
	<?php include_once "botto.php";?>

</BODY></HTML>
