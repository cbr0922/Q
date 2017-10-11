<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Forum_Pack.php";
$Query = $DB->query("select * from `{$INFO[DBPrefix]}forum_class` order by top_id asc");
$Num  = $DB->num_rows($Query);
if ($Num<=0){
	$FUNCTIONS->sorry_back('admin_fcat.php',$INFO['no_bid']);
	exit;
}
$DB->free_result($Query);
//include "Char.class.php";
//$Char_class  = new Char_class;
//include "admin_create_forumclassshow.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Forum_Man];//论坛管理?>--&gt;<?php echo $JsMenu[Forum_Class_List]?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Num?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_fcat_save.php";
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
  <FORM name=adminForm action='admin_fcat.php' method=post>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Forum_Man];//论坛管理?>--&gt;<?php echo $JsMenu[Forum_Class_List]?></SPAN></TD>
                  </TR>
                </TBODY>
              </TABLE></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_fcat.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Forum_Pack[AddForumClass];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE>				</TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                                    <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG   src="images/<?php echo $INFO[IS]?>/fb-delete.gif"  border=0>&nbsp;
                                      <?php echo $Basic_Command['Del'];//删除?></a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE>
                              <!--BUTTON_END--></TD>
                          </TR>
                        </TBODY>
                      </TABLE>				</TD>
                  </TR>
                  <?php } ?>	
                </TBODY>
              </TABLE>
            </TD>
          </TR>
        </TBODY>
    </TABLE>
      
      
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="allborder">
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff height=300>
                      
                      
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                        <INPUT type=hidden name=act>
                        <INPUT  type=hidden value=0 name=boxchecked> 
                        <TBODY>
                          <TR align=middle>
                            <TD class=p9black align=middle width=67  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                              <INPUT onclick=checkAll('<?php echo $Num?>'); type=checkbox value=checkbox   name=toggle> </TD>
                            <TD width="33%" height=26 align="left" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                              <?php echo $JsMenu[Forum_Class_List]?></TD>
                            <TD width="34%" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>&nbsp;</TD>
                            <TD width="28%" height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>&nbsp;</TD>
                          </TR>
                          
                          
                          <?php
					include RootDocumentShare."/cache/Forumclass_show.php";
					$i=0;
					$last = "├─";
					$Forumclassshow =  $Char_class->get_page_children($id,$node,$depth=0);
					foreach($Forumclassshow as $key=>$val) {
						$item = str_repeat(" │ ",$val['depth']);

					?>	
                          <TR class=row0>
                            <TD align=middle width=67 height=26>
                              <INPUT id='cb<?php echo $i?>' onclick=isChecked(this); type=checkbox value=<?php echo $val['id']?> name=bid[]></TD>
                            <TD width="33%" height=26 align="left" noWrap>
                              <a href="admin_fcat.php?Action=Modi&bid=<?php echo $val['id']?>"><?php echo $item.$last.$val['name']?></A><?php if ($val['iffb']==0){ ?>  [ <font color="#FF0000"><?php echo $Basic_Command['NoIffb'];?></font> ]<?php } ?>					  </TD>
                            <TD width="34%" height=26 align="left" noWrap>&nbsp;</TD>
                            <TD width="28%" height="26" noWrap><div class="link_box" style="width:60px;text-align:center"><a href="admin_fcat.php?Action=Modi&bid=<?php echo $val['id']?>"><?php echo $Basic_Command['Edit']?></a></div></TD>
                          </TR>
                          <?php
					$item = '';
					$i++;
					}
					?>					
                          
                        </TBODY>
                      </TABLE>
                    </TD>
                  </TR>
                </TBODY>
              </TABLE>
</FORM>
</div>
	<?php include_once "botto.php";?>

    </BODY></HTML>
