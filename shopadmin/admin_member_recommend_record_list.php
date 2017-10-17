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

$goods_starttime  = $_GET['goods_starttime']!="" ? $_GET['goods_starttime'] : date("Y-m-d",time()-7*24*60*60);
$goods_endtime  = $_GET['goods_endtime']!="" ? $_GET['goods_endtime'] : date("Y-m-d",time());

$Sql      = "select u.memberno,u.user_id,u.username,u.true_name,u.en_firstname,COUNT(t.order_serial) ordercount,SUM(t.totalprice) totalprice from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}order_table` t on u.`memberno`=t.`recommendno` WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND t.order_state!=3 AND t.pay_state!=2 GROUP BY u.user_id ORDER BY totalprice desc";


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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;會員統計--&gt;會員推薦消費紀錄列表</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
<script src="../js/area.js" type="text/javascript" charset="utf-8"></script>
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
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;會員統計--&gt;會員推薦消費紀錄列表</SPAN>
                    </TD>
                </TR></TBODY></TABLE></TD>
          </TR>
        </TBODY>
  </TABLE>
      <TABLE class=allborder cellSpacing=0 cellPadding=0 width="100%"   align=center border=0 style="margin-bottom:10px">
        <FORM name=form1 id=form1 method=get action="">
          <input type="hidden" name="Action" value="Search">
					<TR>
						<TD width="160" height="30" align=right class=p9black>統計日期：</TD>
						<TD height="1" colspan="2" align=left class=p9black>起始日期
							<input  onMouseOver="this.className='box2'" id=begtime size=10
											onMouseOut="this.className='box1'"
											 onClick="showcalendar(event, this)"
											 onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"
											 value='<?php echo $goods_starttime;?>'
												name='goods_starttime'/> - 結束日期
														<input  onMouseOver="this.className='box2'" id=endtime size=10
												onMouseOut="this.className='box1'"
												onClick="showcalendar(event, this)"
												onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"
												value='<?php echo $goods_endtime;?>'
												name='goods_endtime'/>
						</TD>
            <TD width=19% height=31 align=right valign="bottom" class=p9black style="padding:10px"><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.form1.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
          </TR>
					<TR>
						<TD height="30" align=right class=p9black>&nbsp;</TD>
						<TD height="30" colspan="2" align=left class=p9black>
							<input type="submit" value="送出結果" />
							<input type="button" value="匯出" onclick="javascript:location.href='admin_member_recommend_record_list_csv.php?<?php echo "goods_starttime=" . $goods_starttime . "&goods_endtime=" . $goods_endtime; ?>'"/>
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
                            <TD width="2%" height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員編號</TD>
														<TD width="5%" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>帳號</TD>
                            <TD width="3%" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>姓名</TD>
                            <TD width="3%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>推薦數量</TD>
														<TD width="3%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>推薦消費額</TD>
														<TD width="2%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>明細查詢</TD>
                          </TR>
                          <?php
												$i=0;
												while ($Rs=$DB->fetch_array($Query)) {
												?><tbody>
                            <TR class=row0>
                              <TD height="26" align="left" noWrap><?php echo $Rs['memberno']?></TD>
															<TD align="left" noWrap><?php echo $Rs['username']?></TD>
                              <TD align="left" noWrap><?php echo $Rs['true_name']?><?php echo $Rs['en_firstname'].$Rs['en_secondname']?></TD>
                              <TD align="center" noWrap><?php echo $Rs['ordercount']?></TD>
															<TD align="center" noWrap><?php echo $Rs['totalprice']?></TD>
															<TD align="center" noWrap><div class="link_box"><a href="admin_member_recommend_record.php?<?php echo "memberno=" . $Rs['memberno'] . "&goods_starttime=" . $goods_starttime . "&goods_endtime=" . $goods_endtime; ?>">明細查詢</a></div></TD>
                            </TR></tbody>
						          <?php
											$i++;
											}
											?>
                      </FORM>
                    </TABLE>
                  </TD>
                </TR>
            </TABLE>

            <?php if ($Num>0){ ?>
            <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
              <TBODY>
                <TR>
                  <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                    <?php echo $Nav->pagenav()?>
                  </TD>
                </TR>
                <?php } ?>

        </TABLE></TD></TR></TABLE>
    </div>
<script language="javascript">
iniArea("",1,"","","");
</script>

<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
