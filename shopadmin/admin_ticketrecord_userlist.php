<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
$Query = $DB->query("select * from `{$INFO[DBPrefix]}ticketpubrecord` as p inner join`{$INFO[DBPrefix]}ticket` as t on p.ticketid=t.ticketid where p.recordid=".intval($_GET['id'])." limit 0,1");
$Num   = $DB->num_rows($Query);

if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$canmove = $Result['canmove'];
	$ticketname = $Result['ticketname'];
}
if($canmove==1){
	 $Sql      = "select * from `{$INFO[DBPrefix]}ticketpubrecord` as t inner join `{$INFO[DBPrefix]}ticketcode` as c on c.pid=t.recordid inner join `{$INFO[DBPrefix]}user` as u on c.ownid=u.user_id where t.recordid ='" . $_GET['id'] . "' order by u.user_id desc";
}else{
	$Sql      = "select * from `{$INFO[DBPrefix]}userticket` as t inner join `{$INFO[DBPrefix]}user` as u on t.userid=u.user_id where t.recordid ='" . $_GET['id'] . "' order by u.user_id desc";
}


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
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>行銷工具--&gt;電子折價券管理--&gt;電子折價券發放名單</TITLE>
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
</HEAD>
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
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange>行銷工具--&gt;電子折價券管理--&gt;電子折價券發放名單</SPAN>				</TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">&nbsp;</TD>
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
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD width="30" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                              <?php
								if($canmove==0){
								?>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>優惠券使用次數</TD>
                              <?php
								}
								?>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[UserName];//帳號?></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[TrueName];//姓名?> </TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Sex];//性别?> </TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Area];//地區?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Email] ;//电子邮件?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[PerNum];//积分?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[UserLevel];//会员等级?></TD>
                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Admin_Member[RegTime] ;//注册时间?></TD>
                              <?php
								if($canmove==1){
								?>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>折讓號碼</TD>
                              <?php
								}
								?>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><i class="icon-trash" style="font-size:14px"></i> 刪除</TD>
                              </TR>
                            <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {


					?>
                            <TR class=row0>
                              <TD width=30 height=26 align=center>
                                <?php echo $Rs['user_id']?></TD>
                                <?php
								if($canmove==0){
								?>
                              <TD align="left" noWrap><?php echo $Rs['count']?></TD>
                              <?php
								}
								?>
                              <TD height=26 align="left" noWrap>
                                <?php echo $Rs['username']?>                       </TD>
                              <TD height=26 align="left" noWrap>
                                <?php echo $Rs['true_name']?>                      </TD>
                              <TD height=26 align="center" noWrap>
                                <?php echo  $FUNCTIONS->Sextype($Rs['sex']) ?>                      </TD>
                              <TD height=26 align="center" noWrap>
                                <?php echo $Rs['city'].$Rs['canton']?>&nbsp; </TD>
                              <TD height=26 align="center" noWrap>
                                <?php echo $Rs['email']?>                      </TD>
                              <TD height=26 align="center" noWrap>
                                <?php echo $Rs['member_point']?>                      </TD>
                              <TD height=26 align="center" noWrap>
                                <?php echo $Rs['level_name']//$FUNCTIONS->Level_name($Rs['member_point'])?>
                                &nbsp; </TD>
                              <TD height=26 align=center nowrap><?php echo $Rs['reg_date']?></TD>
                              <?php
								if($canmove==1){
								?>
                              <TD align=center nowrap><span class="p9black"><?php echo $Rs['ticketcode'];
								  echo $Rs['userid']>0?"[已使用]":"[未使用]";
								  ?></span></TD>
                            	<?php
								}
								?>
                              <TD align=center nowrap><span class="p9black"><a href="admin_ticketrecord_save.php?act=del&id=<?php echo $Rs['id']?>&ticketid=<?php echo $Rs['ticketid']?>&recordid=<?php echo $Rs['recordid']?>&codeid=<?php echo $Rs['codeid']?>"><i class="icon-trash" style="font-size:14px"></i> 刪除</a></span></TD>
                              </TR>
                            <?php
					$i++;
					}
					?>
                            <TR>
                              <TD width=104 height=14 align=middle>&nbsp;</TD>
                              <TD width=100>&nbsp;</TD>
                              <TD width=100 height=14>&nbsp;</TD>
                              <TD width=71 height=14>&nbsp;</TD>
                              <TD width=71 height=14>&nbsp;</TD>
                              <TD width=80 height=14>&nbsp;</TD>
                              <TD width=143 height=14>&nbsp;</TD>
                              <TD width=80 height=14>&nbsp;</TD>
                              <TD width=78 height=14>&nbsp;</TD>
                              <TD width=197 height=14>&nbsp;</TD>
                              <TD width=197>&nbsp;</TD>
                              <TD width=197>&nbsp;</TD>
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
        </TR></TABLE>
</div>
<script language="javascript" src="../js/modi_bigarea1.js"></script>
<script language="javascript">
initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")
initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")
</script>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
