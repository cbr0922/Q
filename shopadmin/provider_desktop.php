<?php
include_once "Check_Admin.php";
include_once "../language/".$INFO['IS']."/Desktop_Pack.php";

$Sql   = " select user_say,sys_say  from `{$INFO[DBPrefix]}order_userback`  where provider_id=".$_SESSION['sa_id']." order by userback_id desc ";
$Query = $DB->query($Sql);
$i=0;
$j=0;
while ($Rs    = $DB->fetch_array($Query)){
	if ( $Rs['userback_alread']==0 ){
		$i++;   //会员反馈未查看信息
	}
	if ( $Rs['sys_say']=="" ){
		$j++;  //尚未回复反馈
	}
}
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
unset ($Rs);



//----------------------已有商品---------------------------------
$Sql   = " select count(gid) as counts  from `{$INFO[DBPrefix]}goods` where provider_id=".$_SESSION['sa_id']." and iftogether=0 order by gid desc ";
$Query = $DB->query($Sql);
$Rs    = $DB->fetch_array($Query);
$Goods = $Rs['counts'];
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
unset ($Rs);

//----------------------商品库存报警---------------------------------
$Sql    = " select gid ,ifalarm,alarmnum,storage from `{$INFO[DBPrefix]}goods` where ifalarm=1 and provider_id=".$_SESSION['sa_id']."  and alarmnum>=storage   and iftogether=0";
$Query  = $DB->query($Sql);
$PNum   = $DB->num_rows($Query);
$DB->free_result($Query);
unset ($Sql);
unset ($Query);

//----------------------商品评论---------------------------------
$Sql      = "select count(*) as counts from `{$INFO[DBPrefix]}good_comment` gc  inner join `{$INFO[DBPrefix]}goods` g on (gc.good_id=g.gid) where gc.already_read=0 and g.provider_id=".$_SESSION['sa_id']." and g.iftogether=0 order by gc.comment_idate desc ";
$Query    = $DB->query($Sql);
$Rs       = $DB->fetch_array($Query);
$PLNum    = $Rs['counts'];
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
//----------------------定单跟踪总数---------------------------------
$Order007Sql = "select count(*) as havetotal from `{$INFO[DBPrefix]}order_table` ot where  ot.order007_status=1 and  ot.order007_begtime <= '".date("Y-m-d",time())."' and ot.provider_id=".$_SESSION['sa_id']." and ot.iftogether=0";
$Order007Query  = $DB->query($Order007Sql);
$Order007Result = $DB->fetch_array($Order007Query);
$HavetotalOrder007  = $Order007Result[havetotal];
?>
<HTML>
<META http-equiv=ever content=no-cache>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>供應商 --> 桌面</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?> 
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=./images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="./images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<div id="desktop_body_out">
 <div id="desktop_provider_center">
  <div id="desktop_body_left">
   <div id="desktop_menu">
    <div id="desktop_menu_01"><i class="icon-user" style="font-size:14px;margin-right:10px"></i><?php echo $Desktop_Pack[LoginInfo]?></div>
  <TABLE width="100%" border=0 align="center" cellPadding=2 cellSpacing=0 class="9pv">
              <TBODY>
          <TD class=p9orange align=right height=22><span class="p9orange"><a href="provider_psw.php" title="修改密碼"><i class="icon-key"></i> <?php echo $Desktop_Pack[LoginUser]?><?php echo $_SESSION['Admin_Sa'];?></a></span>&nbsp;</TD>
              </TR>
              <TR>
                <TD class=p9orange align=right height=20><?php echo $Desktop_Pack[LoginTime]?>&nbsp;<?php echo date("Y-m-d H: ia ",$_SESSION['Admin_Logintime'])?> </TD></TR>
		</TABLE>
            <div id="desktop_menu_04"><i class="icon-gear" style="font-size:14px;margin-right:10px"></i><?php echo $Desktop_Pack[NoOpShiWu]?></div>
            <div style="clear:both"></div>
  <ul class="desktop_menu">
   <li><A href="provider_comment_list.php?State=Noreplay"><?php echo $Desktop_Pack[NoOpComment]?> <?php echo $PLNum?> <?php echo $Basic_Command['Ge_say']?></A></li>
   <li><A href="provider_order_list.php?Order_Tracks=Show"><?php echo $Desktop_Pack[NoOpOrder_Track];?> <?php echo $HavetotalOrder007;?> <?php echo $Basic_Command['Ge_say']?></A></li></ul><div style="clear:both"></div>
   <div id="desktop_menu_02"><i class="icon-barcode" style="font-size:14px;margin-right:10px"></i><?php echo $Desktop_Pack[ProductInfo]?></div>
   <ul class="desktop_menu">
   <li><?php echo $Desktop_Pack[HaveProduct]?> <?php echo $Goods?> <?php echo $Basic_Command['Ge_say']?></li>
   <li><A  href="provider_goods_list.php?alarm_recsts=DO"><?php echo $Desktop_Pack[ProductStoAlert]?> <?php echo $PNum?> <?php echo $Basic_Command['Ge_say']?></A></li></ul>
    </div></div>
    <div id="desktop_body_right"><div id="desktop"><div style="clear:both"></div><?php  include_once "provider_desktop_right.php";?></div>
    <div style="clear:both"></div></div>
   
  </div><div style="clear:both"></div>
 </div>
<div style="clear:both"></div>
<?php include_once "botto.php";?>	
</div></BODY></HTML>
