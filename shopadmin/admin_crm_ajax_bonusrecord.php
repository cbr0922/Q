<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
$Sql      = "select * from `{$INFO[DBPrefix]}ticket` order by ticketid desc";

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

<script language="javascript">
function toExprot(){
	form2.submit();
}
</script><form name="form2" method="post" action="admin_group_excel.php" target='_blank'  >
<input type="hidden" name="Action" value="Excel">
</form>
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
		document.adminForm.action = "admin_ticket.php?Action=Modi&ticketid="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_ticket_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}
</SCRIPT>
<div id="contain_out">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">&nbsp;</TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79>
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_ticket.php">&nbsp;新增贈點活動</a></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                  	
                  </TR>
                </TBODY>
              </TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=form1 id=form1 method=get action="">        
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=right colSpan=2 height=31>
              
              </TD>
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
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 
                  width="100%" border=0 id="orderedlist">
                          <TBODY>
                            <TR align=middle>
                              <TD width="21" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="p9orange">贈點活動名稱</span></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>發放點數</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>發放時間 </TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>操作</TD>
<TBODY>
                              <TR class=row0>
                                <TD width=21 height=26 align=center>&nbsp;</TD>
                                <TD height=26 align="left" noWrap>VIP會員回餽贈點</TD>
                                <TD align="center" noWrap>100</TD>
                                <TD height=26 align="center" noWrap>2014-08-01(按下才產生)</TD>
                                <TD align="center" noWrap><div class="link_box" style="width:100px;float:left"><a href="#">發放點數</a></div><div class="link_box" style="width:100px;float:left"><a href="#">查詢發放名單</a></div></TD>
                              </TR></TBODY>

                          <TR>
                            <TD height=14 align=middle>&nbsp;</TD>
                            <TD width=153 height=14>&nbsp;</TD>
                            <TD width=107>&nbsp;</TD>
                            <TD width=136 height=14>&nbsp;</TD>
                            <TD width=296>&nbsp;</TD>
                            </TR>
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
        </TR></TABLE></div>
<script language="javascript" src="../js/modi_bigarea1.js"></script>
<script language="javascript">
initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")
initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")
</script>