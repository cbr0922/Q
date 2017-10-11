<?php
@header("Content-type: text/html; charset=utf-8");
include_once "../language/".$INFO['IS']."/Desktop_Pack.php";
include_once "../language/".$INFO['IS']."/Order_Pack_Txt.php";

$Order007Sql = "select count(*) as havetotal from `{$INFO[DBPrefix]}order_table` ot where  ot.order007_status=1 and  ot.order007_begtime <= '".date("Y-m-d",time())."' ";
$Order007Query  = $DB->query($Order007Sql);
$Order007Result = $DB->fetch_array($Order007Query);
$HavetotalOrder007  = $Order007Result[havetotal];
//ot.order_state,ot.order007_begtime,ot.order007_status,ot.order007_content

?>
<TABLE width="100%" height=30 border=0 align="center" cellPadding=0 cellSpacing=0 style="border: 1px solid #CCCCCC;margin-top:10px;">
  <TBODY>
  <TR>
    <TD width="2%" bgColor=#f7f7f7 height=28>&nbsp;</TD>
    <TD width="20%" height=28 vAlign=middle nowrap="nowrap" bgColor=#f7f7f7 class=p9orange><a href="admin_psw.php" title="修改密碼"><i class="icon-key"></i><?php echo $Desktop_Pack[LoginUser]?>：<?php echo $_SESSION['Admin_Sa'];?></a><span class="p9black">
	<?php if ($_SESSION['LOGINADMIN_TYPE']  != 2) {  ?> | <A href="<?php echo $INFO['site_shopadmin']?>/admin_order_list.php?State=NoOp"><?php echo $Desktop_Pack[NoOpOrder]?> <SPAN class=p9orange><?php echo $FUNCTIONS->NoCL();?></SPAN> <?php echo $Basic_Command['Ge_say']?></A> | <?php } ?></span>	</TD>
    <TD class=p9black vAlign=middle width="26%" bgColor=#f7f7f7>
   <?php if ($_SESSION['LOGINADMIN_TYPE']  != 2) {  ?> 
    <marquee scrollamount=4 scrolldelay=0 behavior=sroll onMouseOver='this.stop();' onMouseOut='this.start()'><a href="admin_order_list.php?Order_Tracks=Show" class="trans-input"><?php if ($HavetotalOrder007>0) { echo $Order_Pack[to_start_order_tracks_first].$HavetotalOrder007.$Order_Pack[to_start_order_tracks_sec]; }?></a></marquee>
   <?php } ?>
    </TD>
    <TD width="52%" align="right" vAlign=middle bgColor=#f7f7f7 class=p9black><?php  include_once "desktop_title.php";?></TD>
  </TR>
  </TBODY>
</TABLE>
