<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
/**
 * 基本废除的。
*/

if (is_file("configs.inc.php")){
	include("./configs.inc.php");
}
else if (is_file("../configs.inc.php")){
	include("../configs.inc.php");
}

include RootDocument."/language/".$INFO['IS']."/Good.php";
include("global.php");


function insert_ShowProduct($arr){
	global $DB,$INFO,$FUNCTIONS,$PageNav,$tpl,$Good;
	$tpl->clear_all_assign();

	$bid = $arr[bid];
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($bid);
	$Next_ArrayClass  = explode(",",$Next_ArrayClass);
	$Array_class      = array_unique($Next_ArrayClass);
	foreach ($Array_class as $k=>$v){
		$Add .= $v!="" ? " or g.bid=".$v." " : "";
	}

	$Query   = $DB->query("select bid from  `{$INFO[DBPrefix]}bclass` where catiffb=1 and bid=".intval($bid)." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ( $Num==0 ){ //如果不存在资料
		$FUNCTIONS->header_location("index.php");
	}
	$DB->free_result($Query);

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



	//轮播变量
	$Sql_top = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.middleimg,g.intro,g.ifalarm,g.storage,g.alarmnum from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb=1 and g.ifpub=1 and g.ifrecommend=1 and g.ifjs!=1  and g.ifbonus!=1 and (g.bid=".$bid." ".$Add.")  order by g.goodorder asc,g.idate desc limit 0,2 ";
	$Query_top  = $DB->query($Sql_top);
	$i=0;
	while ( $Rs_top = $DB->fetch_array($Query_top)){
		$ProTop_Rs[$i]['gid']        = intval($Rs_top['gid']) ;
		$ProTop_Rs[$i]['goodsname']  = trim($Rs_top['goodsname'])."".$FUNCTIONS->Storage($Rs_top['ifalarm'],$Rs_top['storage'],$Rs_top['alarmnum']);
		$ProTop_Rs[$i]['price']      = $Rs_top['price'] ;
		$ProTop_Rs[$i]['bn']         = $Rs_top['bn'] ;
		$ProTop_Rs[$i]['smallimg']   = trim($Rs_top['smallimg']) ;
		$ProTop_Rs[$i]['middleimg']  = trim($Rs_top['middleimg']) ;
		$ProTop_Rs[$i]['intro']      = nl2br($Rs_top['intro']);
		$ProTop_Rs[$i]['pricedesc']  = trim($Rs_top['pricedesc']) ;

		$i++;
	}

	//print_r ($ProTop_Rs);
	//第一个轮播产品的资料

	if (intval($ProTop_Rs[0]['gid'])> 0 ){
		$tpl->assign("ProTop_gid1",  intval($ProTop_Rs[0]['gid'])); //轮播商品一ID
		$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProTop_Rs[0]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$ProTop_array_level1[$j]['level_name'] = $Result_level['level_name'];
				$ProTop_array_level1[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}

		$tpl->assign("ProTop_array_level1", $ProTop_array_level1);       //轮播一会员价格数组
		$tpl->assign("ProTop_goodsname1",   $ProTop_Rs[0]['goodsname']); //轮播一名称
		$tpl->assign("ProTop_pricedesc1",   $ProTop_Rs[0]['pricedesc']);     //轮播一特别优惠价格
		$tpl->assign("ProTop_price1",       $ProTop_Rs[0]['price']);     //轮播一价格
		$tpl->assign("ProTop_bn1",          $ProTop_Rs[0]['bn']);        //轮播一编号
		$tpl->assign("ProTop_img1",         $ProTop_Rs[0]['middleimg']);  //轮播一图片
		$tpl->assign("ProTop_intro1",       $ProTop_Rs[0]['intro']);     //轮播一内容
	}


	//第二个轮播产品的资料

	if (intval($ProTop_Rs[1]['gid'])> 0 ){
		$tpl->assign("ProTop_gid2",  intval($ProTop_Rs[1]['gid'])); //轮播商品二ID
		$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProTop_Rs[1]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$ProTop_array_level2[$j]['level_name'] = $Result_level['level_name'];
				$ProTop_array_level2[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl->assign("ProTop_array_level2", $ProTop_array_level2);       //轮播二会员价格数组
		$tpl->assign("ProTop_goodsname2",   $ProTop_Rs[1]['goodsname']); //轮播二名称
		$tpl->assign("ProTop_pricedesc2",   $ProTop_Rs[1]['pricedesc']);     //轮播一特别优惠价格
		$tpl->assign("ProTop_price2",       $ProTop_Rs[1]['price']);     //轮播二价格
		$tpl->assign("ProTop_bn2",          $ProTop_Rs[1]['bn']);        //轮播二编号
		$tpl->assign("ProTop_img2",         $ProTop_Rs[1]['middleimg']);  //轮播二图片
		$tpl->assign("ProTop_intro2",       $ProTop_Rs[1]['intro']);     //轮播二内容
	}


	include_once ("PageNav.class.php");
	$Sql        = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb=1 and g.ifpub=1 and  g.ifjs!=1 and (g.bid=".$bid." ".$Add.")  order by g.goodorder asc,g.idate desc  ";
	$PageNav    = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
	$Num        = $PageNav->iTotal;




	$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条

	if ($Num>0){
		$arrRecords = $PageNav->ReadList();
		$i=0;
		while ( $ProNav = $DB->fetch_array($arrRecords)){
			$ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;
			$ProNav_Rs[$i]['goodsname']  = trim($ProNav['goodsname'])."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']);
			$ProNav_Rs[$i]['price']      = $ProNav['price'] ;
			$ProNav_Rs[$i]['pricedesc']  = $ProNav['pricedesc'] ;
			$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
			$ProNav_Rs[$i]['smallimg']   = trim($ProNav['smallimg']) ;
			$ProNav_Rs[$i]['intro']      = nl2br($ProNav['intro']);
			$i++;
		}

		//下边将输出产品资料


		//第一个产品的资料
		if (intval($ProNav_Rs[0]['gid'])> 0 ){
			$tpl->assign("ProNav_gid1",  $ProNav_Rs[0]['gid']); //最新商品一ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[0]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level1[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level1[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level1", $ProNav_array_level1);       //商品一会员价格数组
			$tpl->assign("ProNav_goodsname1",   $ProNav_Rs[0]['goodsname']); //商品一名称
			$tpl->assign("ProNav_price1",       $ProNav_Rs[0]['price']);     //商品一价格
			$tpl->assign("ProNav_pricedesc1",   $ProNav_Rs[0]['pricedesc']); //商品一价格
			$tpl->assign("ProNav_bn1",          $ProNav_Rs[0]['bn']);        //商品一编号
			$tpl->assign("ProNav_img1",         $ProNav_Rs[0]['smallimg']);  //商品一图片
			$tpl->assign("ProNav_intro1",       $ProNav_Rs[0]['intro']);     //商品一内容
		}
		unset($Sql_level);
		unset($Query_level);



		//第二个产品的资料
		if (intval($ProNav_Rs[1]['gid'])> 0 ){
			$tpl->assign("ProNav_gid2",  $ProNav_Rs[1]['gid']); //最新商品二ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[1]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level2[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level2[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level2", $ProNav_array_level2);       //商品二会员价格数组
			$tpl->assign("ProNav_goodsname2",   $ProNav_Rs[1]['goodsname']); //商品二名称
			$tpl->assign("ProNav_price2",       $ProNav_Rs[1]['price']);     //商品二价格
			$tpl->assign("ProNav_pricedesc2",   $ProNav_Rs[1]['pricedesc']); //商品二价格
			$tpl->assign("ProNav_bn2",          $ProNav_Rs[1]['bn']);        //商品二编号
			$tpl->assign("ProNav_img2",         $ProNav_Rs[1]['smallimg']);  //商品二图片
			$tpl->assign("ProNav_intro2",       $ProNav_Rs[1]['intro']);     //商品二内容
		}
		unset($Sql_level);
		unset($Query_level);


		//第三个产品的资料
		if (intval($ProNav_Rs[2]['gid'])> 0 ){
			$tpl->assign("ProNav_gid3",  $ProNav_Rs[2]['gid']); //最新商品三ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[2]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level3[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level3[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level3", $ProNav_array_level3);       //商品三会员价格数组
			$tpl->assign("ProNav_goodsname3",   $ProNav_Rs[2]['goodsname']); //商品三名称
			$tpl->assign("ProNav_price3",       $ProNav_Rs[2]['price']);     //商品三价格
			$tpl->assign("ProNav_pricedesc3",   $ProNav_Rs[2]['pricedesc']); //商品三价格
			$tpl->assign("ProNav_bn3",          $ProNav_Rs[2]['bn']);        //商品三编号
			$tpl->assign("ProNav_img3",         $ProNav_Rs[2]['smallimg']);  //商品三图片
			$tpl->assign("ProNav_intro3",       $ProNav_Rs[2]['intro']);     //商品三内容
		}
		unset($Sql_level);
		unset($Query_level);



		//第四个产品的资料
		if (intval($ProNav_Rs[3]['gid'])> 0 ){
			$tpl->assign("ProNav_gid4",  $ProNav_Rs[3]['gid']); //最新商品四ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[3]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level4[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level4[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level4", $ProNav_array_level4);       //商品四会员价格数组
			$tpl->assign("ProNav_goodsname4",   $ProNav_Rs[3]['goodsname']); //商品四名称
			$tpl->assign("ProNav_price4",       $ProNav_Rs[3]['price']);     //商品四价格
			$tpl->assign("ProNav_pricedesc4",   $ProNav_Rs[3]['pricedesc']); //商品四价格
			$tpl->assign("ProNav_bn4",          $ProNav_Rs[3]['bn']);        //商品四编号
			$tpl->assign("ProNav_img4",         $ProNav_Rs[3]['smallimg']);  //商品四图片
			$tpl->assign("ProNav_intro4",       $ProNav_Rs[3]['intro']);     //商品四内容
		}
		unset($Sql_level);
		unset($Query_level);

		//第五个产品的资料
		if (intval($ProNav_Rs[4]['gid'])> 0 ){
			$tpl->assign("ProNav_gid5",  $ProNav_Rs[4]['gid']); //最新商品五ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[4]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level5[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level5[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level5", $ProNav_array_level5);       //商品五会员价格数组
			$tpl->assign("ProNav_goodsname5",   $ProNav_Rs[4]['goodsname']); //商品五名称
			$tpl->assign("ProNav_price5",       $ProNav_Rs[4]['price']);     //商品五价格
			$tpl->assign("ProNav_pricedesc5",   $ProNav_Rs[4]['pricedesc']); //商品五价格
			$tpl->assign("ProNav_bn5",          $ProNav_Rs[4]['bn']);        //商品五编号
			$tpl->assign("ProNav_img5",         $ProNav_Rs[4]['smallimg']);  //商品五图片
			$tpl->assign("ProNav_intro5",       $ProNav_Rs[4]['intro']);     //商品五内容
		}
		unset($Sql_level);
		unset($Query_level);



		//第六个产品的资料
		if (intval($ProNav_Rs[5]['gid'])> 0 ){
			$tpl->assign("ProNav_gid6",  $ProNav_Rs[5]['gid']); //最新商品六ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[5]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level6[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level6[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level6", $ProNav_array_level6);       //商品六会员价格数组
			$tpl->assign("ProNav_goodsname6",   $ProNav_Rs[5]['goodsname']); //商品六名称
			$tpl->assign("ProNav_price6",       $ProNav_Rs[5]['price']);     //商品六价格
			$tpl->assign("ProNav_pricedesc6",   $ProNav_Rs[5]['pricedesc']); //商品六价格
			$tpl->assign("ProNav_bn6",          $ProNav_Rs[5]['bn']);        //商品六编号
			$tpl->assign("ProNav_img6",         $ProNav_Rs[5]['smallimg']);  //商品六图片
			$tpl->assign("ProNav_intro6",       $ProNav_Rs[5]['intro']);     //商品六内容
		}
		unset($Sql_level);
		unset($Query_level);


		//第七个产品的资料
		if (intval($ProNav_Rs[6]['gid'])> 0 ){
			$tpl->assign("ProNav_gid7",  $ProNav_Rs[6]['gid']); //最新商品七ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[6]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level7[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level7[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level7", $ProNav_array_level7);       //商品七会员价格数组
			$tpl->assign("ProNav_goodsname7",   $ProNav_Rs[6]['goodsname']); //商品七名称
			$tpl->assign("ProNav_price7",       $ProNav_Rs[6]['price']);     //商品七价格
			$tpl->assign("ProNav_pricedesc7",   $ProNav_Rs[6]['pricedesc']); //商品七价格
			$tpl->assign("ProNav_bn7",          $ProNav_Rs[6]['bn']);        //商品七编号
			$tpl->assign("ProNav_img7",         $ProNav_Rs[6]['smallimg']);  //商品七图片
			$tpl->assign("ProNav_intro7",       $ProNav_Rs[6]['intro']);     //商品七内容
		}
		unset($Sql_level);
		unset($Query_level);


		//第八个产品的资料
		if (intval($ProNav_Rs[7]['gid'])> 0 ){
			$tpl->assign("ProNav_gid8",  $ProNav_Rs[7]['gid']); //最新商品八ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[7]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level8[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level8[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level8", $ProNav_array_level8);       //商品八会员价格数组
			$tpl->assign("ProNav_goodsname8",   $ProNav_Rs[7]['goodsname']); //商品八名称
			$tpl->assign("ProNav_price8",       $ProNav_Rs[7]['price']);     //商品八价格
			$tpl->assign("ProNav_pricedesc8",   $ProNav_Rs[7]['pricedesc']); //商品八价格
			$tpl->assign("ProNav_bn8",          $ProNav_Rs[7]['bn']);        //商品八编号
			$tpl->assign("ProNav_img8",         $ProNav_Rs[7]['smallimg']);  //商品八图片
			$tpl->assign("ProNav_intro8",       $ProNav_Rs[7]['intro']);     //商品八内容
		}
		unset($Sql_level);
		unset($Query_level);



		//第九个产品的资料
		if (intval($ProNav_Rs[8]['gid'])> 0 ){
			$tpl->assign("ProNav_gid9",  $ProNav_Rs[8]['gid']); //最新商品九ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[8]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level9[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level9[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level9", $ProNav_array_level9);       //商品九会员价格数组
			$tpl->assign("ProNav_goodsname9",   $ProNav_Rs[8]['goodsname']); //商品九名称
			$tpl->assign("ProNav_price9",       $ProNav_Rs[8]['price']);     //商品九价格
			$tpl->assign("ProNav_pricedesc9",   $ProNav_Rs[8]['pricedesc']); //商品九价格
			$tpl->assign("ProNav_bn9",          $ProNav_Rs[8]['bn']);        //商品九编号
			$tpl->assign("ProNav_img9",         $ProNav_Rs[8]['smallimg']);  //商品九图片
			$tpl->assign("ProNav_intro9",       $ProNav_Rs[8]['intro']);     //商品九内容
		}
		unset($Sql_level);
		unset($Query_level);



		//第十个产品的资料
		if (intval($ProNav_Rs[9]['gid'])> 0 ){
			$tpl->assign("ProNav_gid10",  $ProNav_Rs[9]['gid']); //最新商品十ID
			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[9]['gid'];
			$Query_level = $DB->query($Sql_level);
			$j=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProNav_array_level10[$j]['level_name'] = $Result_level['level_name'];
					$ProNav_array_level10[$j]['m_price']    = $Result_level['m_price'];
					$j++;
				}
			}
			$tpl->assign("ProNav_array_level10", $ProNav_array_level9);       //商品十会员价格数组
			$tpl->assign("ProNav_goodsname10",   $ProNav_Rs[9]['goodsname']); //商品十名称
			$tpl->assign("ProNav_price10",       $ProNav_Rs[9]['price']);     //商品十价格
			$tpl->assign("ProNav_pricedesc10",   $ProNav_Rs[9]['pricedesc']); //商品十价格
			$tpl->assign("ProNav_bn10",          $ProNav_Rs[9]['bn']);        //商品十编号
			$tpl->assign("ProNav_img10",         $ProNav_Rs[9]['smallimg']);  //商品十图片
			$tpl->assign("ProNav_intro10",       $ProNav_Rs[9]['intro']);     //商品十内容
		}
		unset($Sql_level);
		unset($Query_level);

	}

	$tpl->assign($Good);

}

//显示浏览过的商品

	$viewProductArray = array();
	if (isset($_COOKIE['viewgoods'])){
		for($i=0;$i<5;$i++){
				$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc from `{$INFO[DBPrefix]}goods` as g where g.ifpub=1 and g.gid=".$_COOKIE['viewgoods'][count($_COOKIE['viewgoods'])-($i+1)];
				$Query = $DB->query($Sql);
				$Rs =  $DB->fetch_array($Query);
				$viewProductArray[$i][gid] = $Rs['gid'];
				$viewProductArray[$i][goodsname] = $Rs['goodsname'];
				$viewProductArray[$i][price] = $Rs['price'];
				$viewProductArray[$i][smallimg] = $Rs['smallimg'];
				$viewProductArray[$i][pricedesc] = $Rs['pricedesc'];
		}
	}

	
	$tpl->assign("abProductArray",      $viewProductArray); 

$FileSring_Array = explode("/",$_SERVER[PHP_SELF]);
rsort($FileSring_Array);
$FileName   = reset($FileSring_Array);
$ExpandName = strrchr($FileName,".");
$Tpl_Html   = str_replace($ExpandName,".html",$FileName);


$tpl->display($Tpl_Html);
?>
