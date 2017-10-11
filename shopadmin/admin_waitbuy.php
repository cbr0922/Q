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

$Sql      = "select w.*,u.username,u.email,g.goodsname from `{$INFO[DBPrefix]}waitbuy` as w inner join `{$INFO[DBPrefix]}user` as u on w.user_id=u.user_id inner join  `{$INFO[DBPrefix]}goods` as g on w.gid=g.gid where w.gid='" . $_GET['gid'] . "' order by id  ";

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
<HEAD><TITLE></TITLE>
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
		catvalue = "&k_type_id="+catid;
	}
	
	if (checkvalue!=false){
		
		document.adminForm.action = "admin_domain.php?Action=Modi&id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}
function toNew(id,catid){
	    document.adminForm.action = "admin_waitbuy_send.php";
		document.adminForm.submit();
	
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){   //您是否确认删除选定的记录
			document.adminForm.action = "admin_domain_save.php";
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
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;商品管理-->到貨通知</SPAN>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toNew();"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;發送郵件</a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
            <TD vAlign=top height=210><TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <INPUT type=hidden value="<?php echo $_GET['gid']?>"  name="gid"> 
                          
                          <TBODY>
                            <TR align=middle>
                              <TD width="5%" height=26 align=left noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle></TD>
                              <TD width="40"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['No'];//編號?></TD>
                              <TD width="362"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員名</TD>
                              <TD width="362" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>Email</TD>
                              <TD width="362" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>時間</TD>
                              
                              
                              </TR>
                            <?php               

					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?><TBODY>
                            <TR class=row0>
                              <TD align=left width=5% height=28>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['id']?>' name=cid[]></TD>
                              <TD height=28 align="center" noWrap><?php echo  $i+1;?></TD>
                              <TD height=28 align="center" noWrap>
                                <?php echo $Rs['username']?></TD>
                              <TD align="center" noWrap><?php echo $Rs['email']?></TD>
                              <TD align="center" noWrap><?php echo date("Y-m-d H:i:s",$Rs['pubtime'])?></TD>
                              
                              </TR></TBODY>
                            <?php
					$i++;
					}

					?>
                            <?php  if ($Num==0){ ?>
                            <TR align="center">
                              <TD height=14 colspan="5"><?php echo $KeFu_Pack['nodata'];//"無相關資料?></TD>
                              </TR>
                            <?php } ?>	
                          </FORM>
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
