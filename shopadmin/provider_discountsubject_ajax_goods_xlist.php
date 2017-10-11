<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Sql      = "select g.goodsname,dg.price,dg.dgid,dg.cost,dg.ifcheck from `{$INFO[DBPrefix]}discountgoods` as dg inner join  `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid where g.provider_id=".$_SESSION['sa_id']." order by ifcheck";
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
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="allborder">
  <tr>
    <td width="7%">&nbsp;</td>
    <td width="43%" align="left">商品名稱</td>
    <td width="15%">成本價</td>
    <td width="20%">狀態</td>
  </tr>
  <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

					?>
  <tr>
    <td><INPUT id='dgid<?php echo $i?>'  type=checkbox value='<?php echo $Rs['dgid']?>' name=dgid[]></td>
    <td align="left"><?php echo $Rs['goodsname']?></td>
    <td><INPUT id='zk_cost<?php echo $Rs['dgid']?>' name="zk_cost<?php echo $Rs['dgid']?>" value="<?php echo $Rs['cost']?>" type="text" size="10"></td>
    <td><?php echo $Rs['ifcheck']==0?"未確認":"已確認"?></td>
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
                  <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav(1,"provider_discountsubject_ajax_goods_xlist.php","xlist")?> </td>
                </tr>
                
</table>
<?php } ?>