<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()-7*24*60*60);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
if($_GET['user_id']!="")
	$subsql = " and bp.user_id='" . intval($_GET['user_id']) . "'";
if($_GET['username']!=""){
	$subsql .= " and (u.username like '%" . $_GET['username'] . "%' or u.true_name like '%" . $_GET['username'] . "%')";
}
if(intval($_GET['timestate'])==0 && $_GET['timestate']!=""){
	$subsql .= " and bp.addtime <='" . time() . "' and bp.endtime >='" . time() . "'";
}
if(intval($_GET['timestate'])==1 && $_GET['timestate']!=""){
	$subsql .= " and (bp.addtime >='" . time() . "' or bp.endtime <='" . time() . "')";
}
if($_GET['order_serial']!=""){
	$subsql .= " and o.order_serial like '%" . $_GET['order_serial'] . "%' ";
}
if($_GET['buypointtype']!=""){
	$subsql .= " and bp.buypointtype ='" . intval($_GET['buypointtype']) . "' ";
}
$Sql      = "select bp.*,u.username,u.true_name,u.memberno,bpt.typename from `{$INFO[DBPrefix]}buypoint` as bp inner join `{$INFO[DBPrefix]}user` as u on bp.user_id=u.user_id left join `{$INFO[DBPrefix]}order_table` as o on o.order_id=bp.orderid left join `{$INFO[DBPrefix]}buypointtype` as bpt on bpt.id=bp.buypointtype where 1=1 " . $subsql . "  and bp.addtime>='$begtimeunix' and bp.addtime<='$endtimeunix' order by bp.id desc";

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
<TITLE>會員管理--&gt;購物管理--&gt;購物記錄</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
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


<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif height=302></TD>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black noWrap><SPAN class=p9orange>會員管理--&gt;帳上餘額管理--&gt;帳上餘額記錄</SPAN>
				</TD>
              </TR></TBODY></TABLE></TD>
          
		  </TR>
		  </TBODY>
		</TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=form1 id=form1 method=get action="">        
		<input type="hidden" name="Action" value="Search">
        <TR>
          <TD align=left colSpan=2 height=31 class="p9black">
          時間：
            From
        <INPUT   id=begtime3 size=10 value=<?php echo $begtime?>    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />
         To
        <INPUT    id=endtime3 size=10 value=<?php echo $endtime?>      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />
          會員帳號：
            <INPUT value='<?php echo $_GET['username'];?>'   size='15'  name='username'>
            訂單編號：
            <INPUT value='<?php echo $_GET['order_serial'];?>'   size='10'  name='order_serial'>
            項目：
            <?php
            $Sql_t      = "select * from `{$INFO[DBPrefix]}buypointtype`";
			$Query_t =  $DB->query($Sql_t);
			?>
            <select name="buypointtype" id="buypointtype">
            <option value="">所有的</option>
            <?php
            while ($Rs_t = $DB->fetch_array($Query_t)) {
			?>
              <option value="<?php echo $Rs_t['id'];?>" <?php if($_GET['buypointtype']==$Rs_t['id']) echo "selected";?>><?php echo $Rs_t['typename'];?></option>
             <?php
			}
			 ?>
            </select>
            <input  type='image' src="images/<?php echo $INFO[IS]?>/t_go.gif" border='0' name='imageField' />
            <input type="button" name="button2" id="button2" value="匯出" onClick="location.href='admin_buypoint_excel.php?begtime=<?php echo $begtime?>&endtime=<?php echo $endtime?>&username=<?php echo $_GET['username'];?>&order_serial=<?php echo $_GET['order_serial'];?>';"></TD>
           <TD class=p9black align=right width=147 height=31><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
  		    <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.form1.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
		 </TR>
         </FORM>
         <FORM name=form1 id=form1 method=post action="admin_buypoint_save.php" onSubmit="return checkpointform(this);">        
        <TR>
          <TD align=left colSpan=2 height=31 class="p9black">會員編號：
            <INPUT value=''   size='15'  name='username'>
            帳上餘額：
            <select name="type" id="type">
              <option value="0">增加</option>
             <option value="1">減少</option>
            </select>
            <INPUT value=''   size='10'  name='bonus'>
            點，原因
            <textarea name="content" id="content" cols="30" rows="1"></textarea> <input type="submit" name="button" id="button" value="確定" class="button03"></TD>
          <TD class=p9black align=right height=31>&nbsp;</TD>
        </TR>
		 </FORM>
         <script language="javascript">
		 function checkpointform(myform){
			 if(myform.username.value==""){
						alert("請填寫會員帳號");
						return false;
					}
				 if(myform.content.value==""){
						alert("請填寫原因");
						return false;
					}
			}
		 </script>
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
                      <TD width="54" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員編號</TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員</TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>姓名</TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>原因</TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>帳上餘額</TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>日期</TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>管理員</TD>
                      </TR>
					<?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {
						$order_serial = "";
						$ticketcode = "";
						$ticketname = "";
						$typename = "";
						$state = "";
						$usestatename = "";
		
		if ($Rs['endtime']<time())
				$state = "[已過期]";
			switch (intval($Rs['usestate'])){
			case 0:
				$usestatename = "未使用" . $state;
				break;
			case 1:
				$u_sql = "select sum(usepoint) as usepoint from `{$INFO[DBPrefix]}grouppointbuydetail` where grouppoint_id =" . $Rs['id'];
				$u_Query =  $DB->query($u_sql);
				$u_Rs = $DB->fetch_array($u_Query);
				$usepoint = intval($u_Rs['usepoint']);
				
				$usestatename = "部份使用(" . $usepoint . "團購金)" . $state;
				break;
			case 2:
				$usestatename = "已使用";
				break;
		}

					?>
                    <TR class=row0>
                      <TD width=54 height=26 align=center>
                        <?php echo $Rs['id']?></TD>
                      <TD align="left" noWrap><?php
					  echo $Rs['memberno'] ;
					  ?></TD>
                      <TD align="left" noWrap>
                      <?php
					  echo $Rs['username'] ;
					  ?>
                      </TD>
                      <TD align="left" noWrap>
                      <?php
					  echo $Rs['true_name'];
					  ?>
                      </TD>
                      <TD height=26 align="left" noWrap>
                       <a href="admin_order.php?Action=Modi&order_id=<?php echo $Rs['orderid']?>">[<?php echo $Rs['typename'];?>]<?php echo $Rs['content'];?></a>
                        </TD>
                      <TD align="left" noWrap><?php echo $Rs['type']==0?"+":"-";?><?php echo $Rs['point'];?></TD>
                      <TD align="left" noWrap><?php echo date("Y-m-d",$Rs['addtime'])?></TD>
                      <TD align="left" noWrap>
                      <?php
                if($Rs['sa_type']==0){
					$Sql_U = "select sa as uname from `{$INFO[DBPrefix]}administrator` where sa_id='".$Rs['sa_id']."' limit 0,1";
					$usertitle = "[高級管理員]";
					$Query_U    = $DB->query($Sql_U);
					$Rs_U=$DB->fetch_array($Query_U);
				echo $Rs_U['uname'].$usertitle;
				}elseif($Rs['sa_type']==1){
					$Sql_U = "select username as uname from `{$INFO[DBPrefix]}operater` where opid='".$Rs['sa_id']."' limit 0,1";
					$usertitle = "[一般管理員]";
					$Query_U    = $DB->query($Sql_U);
					$Rs_U=$DB->fetch_array($Query_U);
				echo $Rs_U['uname'].$usertitle;
				}
				
				
					  ?>
                      </TD>
                      </TR>
					<?php
					$i++;
					}
					?>
                    <TR>
                      <TD height=14 align=middle>&nbsp;</TD>
                      <TD width=1021>&nbsp;</TD>
                      <TD width=1021>&nbsp;</TD>
                      <TD width=1021>&nbsp;</TD>
                      <TD width=1021 height=14>&nbsp;</TD>
                      <TD width=1021>&nbsp;</TD>
                      <TD width=1021>&nbsp;</TD>
                      <TD width=1021>&nbsp;</TD>
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
        </TR></TABLE></TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=302>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></TBODY></TABLE>

                      <div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
