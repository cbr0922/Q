<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
 include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
if($_GET['user_id']!=""){
	//$subsql = " and bd.user_id='" . intval($_GET['user_id']) . "'";
	$u_sql = "select * from `{$INFO[DBPrefix]}user` where user_id='" . intval($_GET['user_id']) . "'";
	$Query_u=$DB->query($u_sql);
	$Rs_u = $DB->fetch_array($Query_u);
	$memberno = $Rs_u['memberno'];

}
$Sql      = "select u.username,u.true_name,u.reg_date from `{$INFO[DBPrefix]}user` as u where u.recommendno='".$memberno."' " . $subsql . " ";

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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>會員管理--&gt;推薦人記錄</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
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
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_ticketcode_save.php?Action=Modi&ticketid="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_ticketcode_save.php";
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
                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange>會員管理--&gt;推薦人記錄</SPAN>
                    </TD>
                </TR></TBODY></TABLE></TD>
            
          </TR>
        </TBODY>
  </TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=form1 id=form1 method=get action="">        
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=left colSpan=2 height=31>&nbsp;</TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.form1.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
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
                              <TD width="5%" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                              <TD width="25%" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員</TD>
                              <TD width="30%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員姓名</TD>
                              <TD width="40%" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>註冊日期</TD>
                              </TR>
                            <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {
					?>
                            <TR class=row0>
                              <TD width=54 height=26 align=center>
                                <?php echo $Rs['id']?></TD>
                              <TD align="left" noWrap>
                                <?php
					  echo $Rs['username'];
					  ?>
                                </TD>
                              <TD height=26 align="left" noWrap>
                                <?php
					  echo $Rs['true_name'];
					  ?>
                                </TD>
                              <TD align="left" noWrap><?php echo $Rs['reg_date'];?></TD>
                              </TR>
                            <?php
					$i++;
					}
					?>
                            </FORM>
                        </TABLE>					 </TD>
                  </TR>
              </TABLE>
              
              <?php if ($Num>0){ ?>
              <table class=p9gray cellspacing=0 cellpadding=0 width="100%"    border=0>
                <tbody>
                  <tr>
                    <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav()?> </td>
                  </tr>
                  <?php } ?>
              </table></TD>
        </TR></TABLE>
</div>
<script language="javascript" src="../js/modi_bigarea1.js"></script>
<script language="javascript">
initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")
initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")
</script>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
