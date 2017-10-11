<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Sql      = "select g.goodsname,dg.green_id,dg.cost,g.pricedesc from `{$INFO[DBPrefix]}subject_greengoods` as dg inner join  `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid where dg.rgid='" . intval($_GET['rgid']) . "' order by g.gid";
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
    <td width="5%">&nbsp;</td>
    <td width="49%" align="left">商品名稱</td>
    <td width="31%">價格</td>
    <td width="15%">成本價</td>
  </tr>
  <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

					?>
  <tr>
    <td><INPUT id='green_id<?php echo $i?>'  type=checkbox value='<?php echo $Rs['green_id']?>' name=dgid[]></td>
    <td align="left"><?php echo $Rs['goodsname']?></td>
    <td><?php echo $Rs['pricedesc']?>&nbsp;</td>
    <td><INPUT id='zk_cost<?php echo $Rs['green_id']?>' name="zk_cost<?php echo $Rs['green_id']?>" value="<?php echo $Rs['cost']?>" type="text" size="10"></td>
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
                  <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav(1,"admin_redgreensubject_ajax_green_xlist.php","xlist")?> </td>
                </tr>
                
</table>
<?php } ?>
