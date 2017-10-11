<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');

$Where    =  "";
$Where    = $_GET['skey']!="" ?  " and ntitle like '%".$_GET['skey']."%'" : $Where ;


$Sql      = "select * from `{$INFO[DBPrefix]}news` where 1=1 " . $Where ."  ";

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


                  <TABLE   width="95%" border=0 align="center" cellPadding=0 cellSpacing=0 class=listtable id="selectlinkgoodsform">

                    <TBODY>
                    <TR align=middle>
                      <TD width="3%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                      </TD>
                      <TD width="97%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>文章標題</TD>
                      </TR>
					<?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {
					?>
                    <TR class=row0>
                      <TD align=middle  height=20>
					  <INPUT id='cb<?php echo $i?>'  type=checkbox value='<?php echo $Rs['news_id']?>' name=cid[]> 
					  </TD>
					  <TD  height=20 align=left nowrap> <?php echo $Rs['ntitle']?></TD> 
                      </TR>
					<?php
					$i++;
					}
					?>
</TABLE>
                     <?php if ($Num>0){ ?>
			<table width="95%"    border=0 align="center" cellpadding=0 cellspacing=0 class=p9gray>
              <tbody>
                <tr>
                  <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav(1,"admin_goods_ajax_booksgoodslist.php")?> </td>
                </tr>
                
</table><?php } ?>
