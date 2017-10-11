<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Sql      = "select * from `{$INFO[DBPrefix]}subject` order by subject_id ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num>0){
	$limit = 20;
	$Nav->total_result = $Num;
	$Nav->execute($Sql,$limit);
	$Query_subject = $Nav->sql_result;
	$Nums              = $Num<$limit ? $Num : $limit ;
}else{
	$Query_subject     = $Query;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Subject];?>--&gt;<?php echo $JsMenu[Subject_Man]?></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
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
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
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
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_subject.php?Action=Modi&subject_id="+checkvalue;
		//document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_subject_save.php";
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
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Subject];?>--&gt;<?php echo $JsMenu[Subject_Man]?></SPAN></TD>
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
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_subject.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[AddSubjectName];//新增?></a></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  
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
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $JsMenu[Subject];//主题类别?></TD>
                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Basic_Command['Iffb'];//是否發佈?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>&nbsp;</TD>
                              </TR>
                            <?php   

					$i=0;
					while ($Rs=$DB->fetch_array($Query_subject)) {
					?>
                            <TR class=row0>
                              <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['subject_id']?>' name=cid[]></TD>
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['subject_id']?>',0);">
                                <?php echo $Rs['subject_name']?>                        </A>&nbsp;</TD>
                              <TD height=26 align=center nowrap><?php echo $Display = intval($Rs['subject_open'])==1 ? $Basic_Command['Open'] : $Basic_Command['Close'] ; ?></TD>
                              <TD height=26 align=center nowrap>&nbsp;</TD>
                              </TR>
                            <?php
					$i++;
					}
					?>
                            <TR>
                              <TD align=middle width=149 height=14>&nbsp;</TD>
                              <TD width=427 height=14>&nbsp;</TD>
                              <TD width=372 height=14 colspan="2">&nbsp;</TD>
                              </TR>
                          </FORM>
                        </TABLE>
                      </TD>
                    </TR>
                </TABLE>
              <?php
       if ($Num>0){
	   ?>     
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                      </TD>
                    </TR>
                </TABLE>
              <?php 
       }
	  ?>		
      </TD></TR></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
