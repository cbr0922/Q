<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

/**
 * 用于页面上产品分类的调用
 *
 * <{ insert script="../product/insert_product_class_list.php" name='ShowInsertProductList' bid='1' }>
 */
function smarty_insert_ShowInsertProductList($arr){
	global $DB,$INFO,$FUNCTIONS,$PageNav,$tpl,$Good;
	//$tpl->clear_all_assign();

	$bid = $arr[bid];
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($bid);
	$Next_ArrayClass  = explode(",",$Next_ArrayClass);
	$Array_class      = array_unique($Next_ArrayClass);
	foreach ($Array_class as $k=>$v){
		$Add .= $v!="" ? " or g.bid=".$v." " : "";
	}

	$Query   = $DB->query("select bid from  `{$INFO[DBPrefix]}bclass` where catiffb=1 and bid=".intval($bid)." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ( $Num>0 ){


		$Query = $DB->query("select catname,bid from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Bname  =  $Result['catname'];
			$Bname_url  = "<a href=".$INFO[site_url]."/product/product_class_detail.php?bid=".intval($bid).">".$Bname."</a>";
			$Egg  = $FUNCTIONS->father_Nav_banner(intval($Result['top_id']));

			if ($Egg!=""){
				$Egg  = explode(",",str_replace("||",",",$Egg));
				krsort($Egg);
				reset ($Egg);
				foreach ($Egg as $k=>$v){
					$Father_Nav .= $v!="" ? $v." - " : "";
				}
			}

		}
		$Nav_Product_class  = $Father_Nav.$Bname_url;
		$tpl->assign("Nav_Product_class",  $Nav_Product_class); //产品种类导航条

		include_once ("PageNav.class.php");
		$Sql        = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum ,g.js_begtime,g.js_endtime,g.ifjs   from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb=1 and g.ifpub=1 and  g.ifjs!=1 and (g.bid=".$bid." ".$Add.")   order by g.goodorder asc,g.idate desc  ";
		$PageNav    = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
		$Num        = $PageNav->iTotal;

		$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条

		if ($Num>0){
			$arrRecords = $PageNav->ReadList();
			$i=0;
			$j=1;
			while ( $InsertProduct = $DB->fetch_array($arrRecords)){
				if ((intval($InsertProduct['ifjs'])==1 && $InsertProduct['js_begtime']<=date("Y-m-d",time()) && $InsertProduct['js_endtime']>=date("Y-m-d",time())) || intval($InsertProduct['ifjs'])!=1 ){
					$tpl->assign("InsertProduct_gid".$j,         intval($InsertProduct['gid'])); //最新商品ID
					$tpl->assign("InsertProduct_goodsname".$j,   trim($InsertProduct['goodsname'])."".$FUNCTIONS->Storage($InsertProduct['ifalarm'],$InsertProduct['storage'],$InsertProduct['alarmnum'])); //商品一名称
					$tpl->assign("InsertProduct_price".$j,       $InsertProduct['price']);     //商品价格
					$tpl->assign("InsertProduct_pricedesc".$j,   $InsertProduct['pricedesc']); //商品价格
					$tpl->assign("InsertProduct_bn".$j,          $InsertProduct['bn'] );        //商品编号
					$tpl->assign("InsertProduct_img".$j,         trim($InsertProduct['smallimg']));  //商品图片
					$tpl->assign("InsertProduct_intro".$j,       nl2br($InsertProduct['intro']));     //商品内容

					$i++;
					$j++;
				}
			}
		}

		$tpl->assign($Good);
	}

}

?>