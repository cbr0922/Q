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
$Sql      = "select * from `{$INFO[DBPrefix]}ticketpubrecord` as r inner join `{$INFO[DBPrefix]}ticket` as t on r.ticketid=t.ticketid where r.ticketid='" . $_GET['ticketid'] . "' order by r.recordid desc";

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
<TITLE>行銷工具--&gt;電子折價券管理--&gt;發放電子折價券</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
<form name="form2" method="post" action="admin_group_excel.php" target='_blank'  >
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
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange>行銷工具--&gt;電子折價券管理--&gt;發放電子折價券</SPAN>
                    </TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">&nbsp;</TD>
          </TR>
        </TBODY>
  </TABLE>
  
  <table class="allborder" style="margin-bottom:10px" width="100%">
  <tr>
  <td>
<TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0 >
        <FORM name=form1 id=form1 method=post action="admin_ticketrecord_save.php">        
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=right height=22>		 </TD>
            <TD align=right height=22></TD>
            <TD align=right height=22></TD>
            <TD class=p9black align=right width=244 height=22><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
            </TR>
          <TR>
            <TD width="160" height="30" align=left class=p9black><input name="type" type="radio" value="1" checked>
              按會員級別發放：</TD>
            <TD colspan="2" align=left class=p9black>會員級別<?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}user_level` order by level_id asc ",'user_level','level_id','level_name',intval($user_level)); //$FUNCTIONS->Level_name($member_point)?></TD>
            <TD height=1 align=left class=p9black>&nbsp;</TD>
            </TR>
          <TR>
            <TD height="30" align=left class=p9black><input type="radio" name="type" value="2">
              按會員發放：</TD>
            <TD colspan="2" align=left class=p9black>帳號
              <input  name='username' id="username"  value='<?php echo $_GET[username]?>' size="10"></TD>
            <TD height=0 align=left class=p9black>&nbsp;</TD>
            </TR>
           <TR>
          <TD height="30" align=left class=p9black><input type="radio" name="type" value="5">
            按會員生日月份發放：</TD>
          <TD colspan="2" align=left class=p9black>月份
            <select name="month">
            <?php
            for($k=1;$i<=12;$i++){
			?>
            <option value="<?php echo $i;?>"><?php echo $i;?></option>
            <?php	
			}
			?>
            </select>
            </TD>
          <TD height=0 align=left class=p9black>&nbsp;</TD>
          </TR>
          <TR>
            <TD height="30" align=left class=p9black><input type="radio" name="type" value="3">
              按商品發放：</TD>
            <TD colspan="2" align=left class=p9black><input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=begtime size=10  onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $pub_starttime;?>' name='goods_starttime'>
              -
              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=endtime size=10   onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $pub_endtime;?>' name='goods_endtime'>
              購買過的商品ID
              <input  name='goodsid' id="goodsid"  value='<?php echo $_GET[goodsid]?>' size="10"></TD>
            <TD height=0 align=left class=p9black>&nbsp;</TD>
            </TR>
          <TR>
            <TD height="30" align=left class=p9black><input type="radio" name="type" value="4">
              按訂單金額發放：</TD>
            <TD colspan="2" align=left class=p9black><input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=order_starttime size=10  onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $pub_starttime;?>' name='order_starttime'>
              -
              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=order_endtime size=10   onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $pub_endtime;?>' name='order_endtime'>
              商品總金額
              <input  name='ordermoney' id="ordermoney"  value='<?php echo $_GET[ordermoney]?>' size="10"></TD>
            <TD height=0 align=left class=p9black>&nbsp;</TD>
            </TR>
          <TR>
            <TD height="30" align=left class=p9black><input type="radio" name="type" value="6">
              按會員分組發放：</TD>
            <TD height="30" align=left class=p9black>
            <?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}mail_group` where mgroup_id>1 order by mgroup_id asc ",'mgroup_id','mgroup_id','mgroup_name',intval($mgroup_id));?>
            </TD>
            <TD align=left class=p9black>&nbsp;</TD>
            <TD height=30 align=left class=p9black>&nbsp;</TD>
          </TR>
          <TR>
            <TD height="30" align=left class=p9black style="padding-left:24px">可使用次數：</TD>
            <TD width="104" height="30" align=left class=p9black>
              <input name="count" type="text" id="count" size="10">
              <input name="ticketid" type="hidden" id="ticketid" value="<?php echo $_GET['ticketid']?>">
              </span></TD>
            <TD width="227" align=left class=p9black><i class="icon-warning-sign" style="font-size:16px;color:#C00"></i> 請務必輸入使用次數</TD>
            <TD height=30 align=left class=p9black>&nbsp;</TD>
            </TR>
          <TR>
            <TD height="30" align=left valign="top" style="padding-left:20px"><button type="submit" name="button" id="button" value="確定發放" class="submit">確定發放</button></TD>
            <TD height="30" align=left valign="top" style="padding-left:20px">&nbsp;</TD>
            <TD height="30" align=left valign="top" style="padding-left:20px">&nbsp;</TD>
            <TD height="30" align=left valign="top" style="padding-left:20px">&nbsp;</TD>
            </TR>
          </FORM>
        </TABLE>
        
        </td>
    </tr>
        </table>
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
                              <TD width="36" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="p9orange">折價券名稱</span></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>發放條件</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>使用次數</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>發放日期</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><i class="icon-user" style="font-size:16px;"></i> 瀏覽發放人員名單</TD>
                              </TR>
                            <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {


					?>
                            <TR class=row0>
                              <TD width=36 height=26 align=center>
                                <?php echo $Rs['recordid']?></TD>
                              <TD height=26 align="left" noWrap>
                                <?php echo $Rs['ticketname']?></TD>
                              <TD height=26 align="left" noWrap>
                                <?php echo $Rs['content']?>                        </TD>
                              <TD align="center" noWrap><?php echo $Rs['count']?></TD>
                              <TD height=26 align="center" noWrap><?php echo date("Y-m-d",$Rs['pubtime'])?>   </TD>
                              <TD align="center" noWrap><div class="link_box" style="width:40px"><a href="admin_ticketrecord_userlist.php?id=<?php echo $Rs['recordid']?>">瀏覽</a></div></TD>
                              </TR>
                            <?php
					$i++;
					}
					?>
                            <TR>
                              <TD height=14 align=middle>&nbsp;</TD>
                              <TD width=204 height=14>&nbsp;</TD>
                              <TD width=322 height=14>&nbsp;</TD>
                              <TD width=159>&nbsp;</TD>
                              <TD width=159 height=14>&nbsp;</TD>
                              <TD width=185>&nbsp;</TD>
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
