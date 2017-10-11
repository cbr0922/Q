<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Where    =  "";
$Where    = $_GET['skey']!="" ?  " and goodsname like '%".$_GET['skey']."%'" : $Where ;
$Sql      = "select * from `{$INFO[DBPrefix]}goods` where provider_id=".$_SESSION['sa_id']." ".$Where." order by goodorder ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 12  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="7%">&nbsp;</td>
    <td width="59%" align="left">商品名稱</td>
    <td>成本價</td>
  </tr>
  <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

					?>
  <tr>
    <td><INPUT id='cb<?php echo $i?>'  type=checkbox value='<?php echo $Rs['gid']?>' name=cid[]> </td>
    <td align="left"><?php echo $Rs['goodsname']?></td>
    <td><?php echo $Rs['cost']?></td>
  </tr>
  <?php
					$i++;
					}
					?>
</table>
<?php if ($Num>0){ ?>
			<table class=p9gray cellspacing=0 cellpadding=0 width="100%"    border=0>
              <tbody>
                <tr>
                  <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav(1,"provider_discountsubject_ajax_goods_list.php","goodslinklist")?> </td>
                </tr>
                
</table>
<?php } ?>
