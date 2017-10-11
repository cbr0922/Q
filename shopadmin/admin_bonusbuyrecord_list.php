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

$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()-7*60*60*24);

$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());

$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");

$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;

//$Sql      = "select bp.*,bt.typename from `{$INFO[DBPrefix]}bonuspoint` as bp inner join `{$INFO[DBPrefix]}bonustype` as bt on bp.type=bt.typeid where saleorlevel=1 order by bp.id desc";

if($_GET['user_id']!="")

	$subsql = " and bd.user_id='" . intval($_GET['user_id']) . "'";

if($_GET['begtime']!=""){

	$subsql .= " and bd.usetime>='" . $begtimeunix . "'";

}

if($_GET['endtime']!=""){

	$subsql .= " and bd.usetime<='" . $endtimeunix . "'";

}

if($_GET['username']!=""){

	$subsql .= " and (u.username like '%" . $_GET['username'] . "%' or u.true_name like '%" . $_GET['username'] . "%')";

}

if($_GET['order_serial']!=""){

	$subsql .= " and o.order_serial like '%" . $_GET['order_serial'] . "%' ";

}

$Sql = "select bd.*,u.username,u.true_name  from `{$INFO[DBPrefix]}bonusbuydetail` as bd inner join `{$INFO[DBPrefix]}user` as u on bd.user_id=u.user_id inner join `{$INFO[DBPrefix]}order_table` as o on o.order_id=bd.orderid where 1=1 " . $subsql . " order by bd.id desc";

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

<html xmlns="http://www.w3.org/1999/xhtml">

<HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE>客戶關係--&gt;紅利管理--&gt;紅利消費紀錄</TITLE>

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

            <TD width="50%"><TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

              <TBODY>

                <TR>

                  <TD width=38 height="40"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>

                  <TD class=p12black noWrap><SPAN class=p9orange>客戶關係--&gt;紅利管理--&gt;紅利消費紀錄</SPAN></TD>

                </TR>

              </TBODY>

              </TABLE></TD>

            </TBODY>

        </TABLE>

        <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>

          <FORM name=form1 id=form1 method=get action="">        

            <input type="hidden" name="Action" value="Search">

            <TR>

              <TD align=left colSpan=2 height=41 class="p9black">會員帳號：

                <INPUT value='<?php echo $_GET['username'];?>'   size='15'  name='username'>

                訂單編號：

                <INPUT value='<?php echo $_GET['order_serial'];?>'   size='10'  name='order_serial'>

                時間：From

                <INPUT   id=begtime3 size=10 value="<?php echo $begtime?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />

                To

                <INPUT    id=endtime3 size=10 value="<?php echo $endtime?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />

                <input  type='image' src="images/<?php echo $INFO[IS]?>/t_go.gif" border='0' name='imageField' /></TD>

              <TD class=p9black align=right width=199 height=31><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>

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

                                <TD width="54" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>

                                <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員</TD>

                                <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>內容</TD>

                                <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>紅利</TD>

                                <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>使用日期</TD>

                                </TR>

                              

                              <?php               

					$i=0;



					while ($Rs=$DB->fetch_array($Query)) {

					?><TBODY>

                                <TR class=row0>

                                  <TD width=54 height=26 align=center>

                                  <?php echo $Rs['id']?></TD>

                                  <TD align="left" noWrap>

                                    <?php

					  echo $Rs['username'] . "(" . $Rs['true_name'] . ")";

					  ?>

                                  </TD>

                                  <TD height=26 align="left" noWrap>

                                    <a href="admin_order.php?Action=Modi&order_id=<?php echo $Rs['orderid']?>"><?php echo $Rs['content'];?></a>

                                  </TD>

                                  <TD align="left" noWrap><?php echo $Rs['usepoint'];?></TD>

                                  <TD align="left" noWrap><?php echo date("Y-m-d",$Rs['usetime'])?></TD>

                                </TR></TBODY>

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

                    

                    </table><?php } ?>

                </TD>

              </TR>

            </TBODY>

          </TABLE>



</div>

<div align="center">

  <?php include_once "botto.php";?>

</div>

</BODY>

</HTML>

