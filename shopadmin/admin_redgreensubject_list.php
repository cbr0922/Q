<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Sql      = "select * from `{$INFO[DBPrefix]}subject_redgreen` order by rgid ";
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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>紅配綠活動列表</TITLE></head>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
<div id="fullBg"></div>
<div id="msg">
<div id="close"></div>
<div id="ctt"></div>
</div>
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
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_redgreensubject.php?Action=Modi&subject_id="+checkvalue;
		//document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_redgreensubject_save.php";
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
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;紅配綠活動--&gt;紅配綠活動管理</SPAN></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_redgreensubject.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[AddSubjectName];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  
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
                    <FORM name=adminForm id=adminForm action="" method=post>
                      <INPUT type=hidden name=act>
                      <INPUT type=hidden value=0  name=boxchecked> 
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                          <TBODY>
                            <TR align=middle>
                              <TD width="10%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="30%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $JsMenu[Subject];//主题类别?></TD>
                              <TD width="20%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>活動時間</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>綠標商品折扣</TD>
                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Basic_Command['Iffb'];//是否發佈?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>紅標商品</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>綠標商品</TD>
                            </TR>
                            <?php   

					$i=0;
					while ($Rs=$DB->fetch_array($Query_subject)) {
					?>
                            <TR class=row0>
                              <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['rgid']?>' name=cid[]></TD>
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['rgid']?>',0);">
                                <?php echo $Rs['subject_name']?>                        </A>&nbsp;</TD>
                              <TD align=center nowrap><?php echo $Rs['start_date']?>至<?php echo $Rs['end_date']?></TD>
                              <TD align=center nowrap><?php echo $Rs['saleoff']?>%</TD>
                              <TD height=26 align=center nowrap><?php echo $Display = intval($Rs['subject_open'])==1 ? $Basic_Command['Open'] : $Basic_Command['Close'] ; ?></TD>
                              <TD height=26 align=center nowrap><div class="link_box" style="width:40px"><a href="javascript:void(0);" onclick="showWin('url','admin_redgreensubject_ajax_red.php?rgid=<?php echo $Rs['rgid']?>','',750,500);">管理</a></div></TD>
                              <TD align=center nowrap><div class="link_box" style="width:40px"><a href="javascript:void(0);" onclick="showWin('url','admin_redgreensubject_ajax_green.php?rgid=<?php echo $Rs['rgid']?>','',750,500);">管理</a></div></TD>
                            </TR>
                            <?php
					$i++;
					}
					?>
                      </TABLE>
                      </FORM>
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
