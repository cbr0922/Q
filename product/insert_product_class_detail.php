<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

/**
 * 用于页面上产品分类的调用 [这个是带分页面的。暂时不用]
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
		$Sql        = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb=1 and g.ifpub=1 and  g.ifjs!=1 and (g.bid=".$bid." ".$Add.")   order by g.goodorder asc,g.idate desc  limit 0,10 ";
		$Query      = $DB->query($Sql);
		$Num        = $DB->num_rows($Query);
		if ($Num>0){
			$i=0;
			while ( $InsertProduct = $DB->fetch_array($Query)){
				$InsertProduct_Rs[$i]['gid']        = intval($InsertProduct['gid']) ;
				$InsertProduct_Rs[$i]['goodsname']  = trim($InsertProduct['goodsname'])."".$FUNCTIONS->Storage($InsertProduct['ifalarm'],$InsertProduct['storage'],$InsertProduct['alarmnum']);
				$InsertProduct_Rs[$i]['price']      = $InsertProduct['price'] ;
				$InsertProduct_Rs[$i]['pricedesc']  = $InsertProduct['pricedesc'] ;
				$InsertProduct_Rs[$i]['bn']         = $InsertProduct['bn'] ;
				$InsertProduct_Rs[$i]['smallimg']   = trim($InsertProduct['smallimg']) ;
				$InsertProduct_Rs[$i]['intro']      = nl2br($InsertProduct['intro']);
				$i++;
			}

			//下边将输出产品资料

			//第一个产品的资料
			if (intval($InsertProduct_Rs[0]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid1",         $InsertProduct_Rs[0]['gid']); //最新商品一ID
				$tpl->assign("InsertProduct_goodsname1",   $InsertProduct_Rs[0]['goodsname']); //商品一名称
				$tpl->assign("InsertProduct_price1",       $InsertProduct_Rs[0]['price']);     //商品一价格
				$tpl->assign("InsertProduct_pricedesc1",   $InsertProduct_Rs[0]['pricedesc']); //商品一价格
				$tpl->assign("InsertProduct_bn1",          $InsertProduct_Rs[0]['bn']);        //商品一编号
				$tpl->assign("InsertProduct_img1",         $InsertProduct_Rs[0]['smallimg']);  //商品一图片
				$tpl->assign("InsertProduct_intro1",       $InsertProduct_Rs[0]['intro']);     //商品一内容
			}
			//第二个产品的资料
			if (intval($InsertProduct_Rs[1]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid2",         $InsertProduct_Rs[1]['gid']); //最新商品二ID
				$tpl->assign("InsertProduct_goodsname2",   $InsertProduct_Rs[1]['goodsname']); //商品二名称
				$tpl->assign("InsertProduct_price2",       $InsertProduct_Rs[1]['price']);     //商品二价格
				$tpl->assign("InsertProduct_pricedesc2",   $InsertProduct_Rs[1]['pricedesc']); //商品二价格
				$tpl->assign("InsertProduct_bn2",          $InsertProduct_Rs[1]['bn']);        //商品二编号
				$tpl->assign("InsertProduct_img2",         $InsertProduct_Rs[1]['smallimg']);  //商品二图片
				$tpl->assign("InsertProduct_intro2",       $InsertProduct_Rs[1]['intro']);     //商品二内容
			}
			//第三个产品的资料
			if (intval($InsertProduct_Rs[2]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid3",         $InsertProduct_Rs[2]['gid']); //最新商品三ID
				$tpl->assign("InsertProduct_goodsname3",   $InsertProduct_Rs[2]['goodsname']); //商品三名称
				$tpl->assign("InsertProduct_price3",       $InsertProduct_Rs[2]['price']);     //商品三价格
				$tpl->assign("InsertProduct_pricedesc3",   $InsertProduct_Rs[2]['pricedesc']); //商品三价格
				$tpl->assign("InsertProduct_bn3",          $InsertProduct_Rs[2]['bn']);        //商品三编号
				$tpl->assign("InsertProduct_img3",         $InsertProduct_Rs[2]['smallimg']);  //商品三图片
				$tpl->assign("InsertProduct_intro3",       $InsertProduct_Rs[2]['intro']);     //商品三内容
			}
			//第四个产品的资料
			if (intval($InsertProduct_Rs[3]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid4",         $InsertProduct_Rs[3]['gid']); //最新商品四ID
				$tpl->assign("InsertProduct_goodsname4",   $InsertProduct_Rs[3]['goodsname']); //商品四名称
				$tpl->assign("InsertProduct_price4",       $InsertProduct_Rs[3]['price']);     //商品四价格
				$tpl->assign("InsertProduct_pricedesc4",   $InsertProduct_Rs[3]['pricedesc']); //商品四价格
				$tpl->assign("InsertProduct_bn4",          $InsertProduct_Rs[3]['bn']);        //商品四编号
				$tpl->assign("InsertProduct_img4",         $InsertProduct_Rs[3]['smallimg']);  //商品四图片
				$tpl->assign("InsertProduct_intro4",       $InsertProduct_Rs[3]['intro']);     //商品四内容
			}
			//第五个产品的资料
			if (intval($InsertProduct_Rs[4]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid5",         $InsertProduct_Rs[4]['gid']); //最新商品五ID
				$tpl->assign("InsertProduct_goodsname5",   $InsertProduct_Rs[4]['goodsname']); //商品五名称
				$tpl->assign("InsertProduct_price5",       $InsertProduct_Rs[4]['price']);     //商品五价格
				$tpl->assign("InsertProduct_pricedesc5",   $InsertProduct_Rs[4]['pricedesc']); //商品五价格
				$tpl->assign("InsertProduct_bn5",          $InsertProduct_Rs[4]['bn']);        //商品五编号
				$tpl->assign("InsertProduct_img5",         $InsertProduct_Rs[4]['smallimg']);  //商品五图片
				$tpl->assign("InsertProduct_intro5",       $InsertProduct_Rs[4]['intro']);     //商品五内容
			}
			//第六个产品的资料
			if (intval($InsertProduct_Rs[5]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid6",         $InsertProduct_Rs[5]['gid']); //最新商品六ID
				$tpl->assign("InsertProduct_goodsname6",   $InsertProduct_Rs[5]['goodsname']); //商品六名称
				$tpl->assign("InsertProduct_price6",       $InsertProduct_Rs[5]['price']);     //商品六价格
				$tpl->assign("InsertProduct_pricedesc6",   $InsertProduct_Rs[5]['pricedesc']); //商品六价格
				$tpl->assign("InsertProduct_bn6",          $InsertProduct_Rs[5]['bn']);        //商品六编号
				$tpl->assign("InsertProduct_img6",         $InsertProduct_Rs[5]['smallimg']);  //商品六图片
				$tpl->assign("InsertProduct_intro6",       $InsertProduct_Rs[5]['intro']);     //商品六内容
			}
			//第七个产品的资料
			if (intval($InsertProduct_Rs[6]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid7",         $InsertProduct_Rs[6]['gid']); //最新商品七ID
				$tpl->assign("InsertProduct_goodsname7",   $InsertProduct_Rs[6]['goodsname']); //商品七名称
				$tpl->assign("InsertProduct_price7",       $InsertProduct_Rs[6]['price']);     //商品七价格
				$tpl->assign("InsertProduct_pricedesc7",   $InsertProduct_Rs[6]['pricedesc']); //商品七价格
				$tpl->assign("InsertProduct_bn7",          $InsertProduct_Rs[6]['bn']);        //商品七编号
				$tpl->assign("InsertProduct_img7",         $InsertProduct_Rs[6]['smallimg']);  //商品七图片
				$tpl->assign("InsertProduct_intro7",       $InsertProduct_Rs[6]['intro']);     //商品七内容
			}
			//第八个产品的资料
			if (intval($InsertProduct_Rs[7]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid8",         $InsertProduct_Rs[7]['gid']); //最新商品八ID
				$tpl->assign("InsertProduct_goodsname8",   $InsertProduct_Rs[7]['goodsname']); //商品八名称
				$tpl->assign("InsertProduct_price8",       $InsertProduct_Rs[7]['price']);     //商品八价格
				$tpl->assign("InsertProduct_pricedesc8",   $InsertProduct_Rs[7]['pricedesc']); //商品八价格
				$tpl->assign("InsertProduct_bn8",          $InsertProduct_Rs[7]['bn']);        //商品八编号
				$tpl->assign("InsertProduct_img8",         $InsertProduct_Rs[7]['smallimg']);  //商品八图片
				$tpl->assign("InsertProduct_intro8",       $InsertProduct_Rs[7]['intro']);     //商品八内容
			}

			//第九个产品的资料
			if (intval($InsertProduct_Rs[8]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid9",         $InsertProduct_Rs[8]['gid']); //最新商品九ID
				$tpl->assign("InsertProduct_goodsname9",   $InsertProduct_Rs[8]['goodsname']); //商品九名称
				$tpl->assign("InsertProduct_price9",       $InsertProduct_Rs[8]['price']);     //商品九价格
				$tpl->assign("InsertProduct_pricedesc9",   $InsertProduct_Rs[8]['pricedesc']); //商品九价格
				$tpl->assign("InsertProduct_bn9",          $InsertProduct_Rs[8]['bn']);        //商品九编号
				$tpl->assign("InsertProduct_img9",         $InsertProduct_Rs[8]['smallimg']);  //商品九图片
				$tpl->assign("InsertProduct_intro9",       $InsertProduct_Rs[8]['intro']);     //商品九内容
			}
			//第十个产品的资料
			if (intval($InsertProduct_Rs[9]['gid'])> 0 ){
				$tpl->assign("InsertProduct_gid10",         $InsertProduct_Rs[9]['gid']); //最新商品十ID
				$tpl->assign("InsertProduct_goodsname10",   $InsertProduct_Rs[9]['goodsname']); //商品十名称
				$tpl->assign("InsertProduct_price10",       $InsertProduct_Rs[9]['price']);     //商品十价格
				$tpl->assign("InsertProduct_pricedesc10",   $InsertProduct_Rs[9]['pricedesc']); //商品十价格
				$tpl->assign("InsertProduct_bn10",          $InsertProduct_Rs[9]['bn']);        //商品十编号
				$tpl->assign("InsertProduct_img10",         $InsertProduct_Rs[9]['smallimg']);  //商品十图片
				$tpl->assign("InsertProduct_intro10",       $InsertProduct_Rs[9]['intro']);     //商品十内容
			}
		}

		$tpl->assign($Good);
	}

}

?>