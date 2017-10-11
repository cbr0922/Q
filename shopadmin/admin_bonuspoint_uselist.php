<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : "";
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : "";


/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

if ($_GET['Action']=='Search') {
	if ($_GET['username']!=""){
		$subsql .= " and u.username like '%" . $_GET['username'] . "%'";	
	}
	if ($_GET['orderno']!=""){
		$subsql .= " and br.ordercode like '%" . $_GET['orderno'] . "%'";	
	}
	if ($begtime!=""){
		$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
		$subsql .= " and br.rebatetime>='$begtimeunix'";	
	}
	if ($endtime!=""){
		$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
		$subsql .= " and br.rebatetime<='$endtimeunix'";	
	}
}

$Sql      = "select * from `{$INFO[DBPrefix]}bonus_record` as br inner join `{$INFO[DBPrefix]}order_table` as ot on ot.order_serial=br.ordercode inner join `{$INFO[DBPrefix]}user`  as u on u.user_id=br.userid where  1=1  " . $subsql . "  order by br.recordid desc";

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
<HTML  xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<LINK id=css href="../css/calendar.css" type='text/css' rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;紅利點數使用記錄</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
<SCRIPT  src="../js/DateString.js"  language="javascript"></SCRIPT>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
<form name="form2" method="post" action="admin_group_excel.php" target='_blank'  >
<input type="hidden" name="Action" value="Excel">
</form>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
 <TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center   border=0>
 <TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
	<?php  include_once "desktop_title.php";?></TD>
  </TR>
  </TBODY>
 </TABLE>
       <?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD>
  </TR>
  </TBODY>
</TABLE>
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

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=302></TD>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Set]?>--&gt;紅利點數使用記錄</SPAN></TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%">&nbsp;</TD>
		  </TR>
		  </TBODY>
		</TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=form1 id=form1 method=get action="">        
		<input type="hidden" name="Action" value="Search">
        <TR>
          <TD align=left colSpan=2 height=31>用戶名
            <input type="text" name="username" id="username" value="<?php echo $_GET['username']?>">
            訂單編號
            <input type="text" name="orderno" id="orderno" value="<?php echo $_GET['orderno']?>">
            使用時間From
            <INPUT   id=begtime size=10 value="<?php echo $begtime?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />
To
<INPUT    id=endtime size=10 value="<?php echo $endtime?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />
<input  type='image' src="images/<?php echo $INFO[IS]?>/t_go.gif" border='0' name='imageField' /></TD>
          </TR>
        <TR>
          <TD align=right height=31>
            
		 </TD>
           <TD class=p9black align=right height=31><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
  		    <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
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
                  width="100%" border=0>
                    <FORM name=adminForm action="" method=post>
					<INPUT type=hidden name=act>
					 <INPUT type=hidden value=0  name=boxchecked> 
                    <TBODY>
                    <TR align=middle>
                      <TD width="43" height="26" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="p9orange">使用人</span></TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>使用紅利點數</TD>
					  
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>使用時間</TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>訂單號</TD>
                    </TR>
					<?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {


					?>
                    <TR class=row0>
                      <TD width=43 height=26 align=center>
                        <?php echo $Rs['recordid']?></TD>
                      <TD height=26 align="left" noWrap>
						 <?php echo $Rs['username']?>                        </TD>
                      <TD height=26 align="center" noWrap><?php echo $Rs['point']?></TD>
                     
                      <TD align="center" noWrap><?php echo date("Y-m-d",$Rs['rebatetime'])?></TD>
                      <TD align="center" noWrap><a href="admin_order.php?Action=Modi&order_id=<?php echo $Rs['order_id']?>"><?php echo $Rs['ordercode']?></a></TD>
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
        </TR></TABLE></TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=302>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></TBODY></TABLE>
<script language="javascript" src="../js/modi_bigarea1.js"></script>
<script language="javascript">
initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")
initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")
</script>

                      <div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
