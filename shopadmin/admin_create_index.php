<?php
include ("../configs.inc.php");
include Classes . "/global.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/Good.php";



include Classes . "/Html_Smarty.php";

$tpl_HTML->template_dir   = Templates."/".$templates;                    //设置模板目录
$tpl_HTML->compile_dir    = Templates."/".$templates."/templates_c";     //设置编译目录

/*-------------------------------------------------------------这个是INDEX。HTML开始------------------------------------------*/

/**
 *这里是主页的标签公告ID,及高度
 */
$tpl_HTML->assign("index_iframe_height",  $INFO[index_iframe_height]);
$tpl_HTML->assign("index_iframe_id",  $INFO[index_iframe_id]);

/**
 *主页面LOGO的尺寸
 */ 
$tpl_HTML->assign("logo_width",  $INFO["logo_width"]);
$tpl_HTML->assign("logo_height", $INFO["logo_height"]);



/**
 *如当前商店设定的模板不存在，将出现提醒信息，并要求用户请稍候访问……"
 */
function writableCell( $folder ) {
	global $TEMPLATES;
	if (!is_writable( "$folder" )){
		$tpl_HTML_err = '';
		$tpl_HTML_err = '<table width=80% align=center border=1><tr>';
		$tpl_HTML_err.= '<td width=350>' . $folder . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		$tpl_HTML_err.= '<td align=left>';
		$tpl_HTML_err.= "<b><font color='red'>".$TEMPLATES[NoTemplates]."</font></b></td>";
		$tpl_HTML_err.= '</tr></table>';
		echo  $tpl_HTML_err;
	}
}

writableCell($tpl_HTML->compile_dir);

/**
 *最新商品
 */

$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.pricedesc,g.intro,g.alarmnum,g.storage,g.ifalarm from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where b.catiffb=1 and g.ifpub=1 and g.ifjs!=1 and g.ifbonus!=1  order by idate desc limit 0,8";
$Query =    $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i=0;
while ( $NewPro = $DB->fetch_array($Query)){
	$New_product[$i]['gid']        = $NewPro['gid'] ;

	$New_product[$i]['goodsname']  = $NewPro['goodsname']."".$FUNCTIONS->Storage($NewPro['ifalarm'],$NewPro['storage'],$NewPro['alarmnum']);
	$New_product[$i]['price']      = $NewPro['price'] ;
	$New_product[$i]['pricedesc']  = $NewPro['pricedesc'] ;
	$New_product[$i]['bn']         = $NewPro['bn'] ;
	$New_product[$i]['smallimg']   = trim($NewPro['smallimg']) ;
	$New_product[$i]['intro']      = $Char_class->cut_str($NewPro['intro'],150,0,'UTF-8');
	$i++;
}


/**
 * 下边将输出产品资料
 */

if ($Num>0){
	//第一个产品的资料
	if (intval($New_product[0]['gid'])> 0 ){
		$tpl_HTML->assign("New_product_gid1",  $New_product[0]['gid']); //最新商品一ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$New_product[0]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$NewRs_productarray_level1[$j]['level_name'] = $Result_level['level_name'];
				$NewRs_productarray_level1[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("NewRs_productarray_level1",  $NewRs_productarray_level1);   //最新商品一会员价格数组
		$tpl_HTML->assign("NewRs_product_goodsname1",   $New_product[0]['goodsname']); //最新商品一名称
		$tpl_HTML->assign("NewRs_product_price1",       $New_product[0]['price']);     //最新商品一价格
		$tpl_HTML->assign("NewRs_product_price1_desc",  $New_product[0]['pricedesc']); //最新商品一优惠价格
		$tpl_HTML->assign("NewRs_product_bn1",          $New_product[0]['bn']);        //最新商品一编号
		$tpl_HTML->assign("NewRs_product_img1",         $New_product[0]['smallimg']);  //最新商品一图片
		$tpl_HTML->assign("NewRs_product_intro1",       $New_product[0]['intro']);     //最新商品一内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第二个产品的资料
	if (intval($New_product[1]['gid'])!=0){
		$tpl_HTML->assign("New_product_gid2",  $New_product[1]['gid']); //最新商品ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$New_product[1]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$NewRs_productarray_level2[$j]['level_name'] = $Result_level['level_name'];
				$NewRs_productarray_level2[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("NewRs_productarray_level2",       $NewRs_productarray_level2);   //最新商品二会员价格数组
		$tpl_HTML->assign("NewRs_product_goodsname2",   $New_product[1]['goodsname']); //最新商品二名称
		$tpl_HTML->assign("NewRs_product_price2",       $New_product[1]['price']);     //最新商品二价格
		$tpl_HTML->assign("NewRs_product_price2_desc",  $New_product[1]['pricedesc']); //最新商品二优惠价格
		$tpl_HTML->assign("NewRs_product_bn2",          $New_product[1]['bn']);        //最新商品二编号
		$tpl_HTML->assign("NewRs_product_img2",         $New_product[1]['smallimg']);  //最新商品二图片
		$tpl_HTML->assign("NewRs_product_intro2",       $New_product[1]['intro']);     //最新商品二内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第三个产品的资料
	if (intval($New_product[2]['gid'])!=0){
		$tpl_HTML->assign("New_product_gid3",  $New_product[2]['gid']); //最新商品ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$New_product[2]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$NewRs_productarray_level3[$j]['level_name'] = $Result_level['level_name'];
				$NewRs_productarray_level3[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("NewRs_productarray_level3",       $NewRs_productarray_level3);   //最新商品三会员价格数组
		$tpl_HTML->assign("NewRs_product_goodsname3",   $New_product[2]['goodsname']); //最新商品三名称
		$tpl_HTML->assign("NewRs_product_price3",       $New_product[2]['price']);     //最新商品三价格
		$tpl_HTML->assign("NewRs_product_price3_desc",  $New_product[2]['pricedesc']); //最新商品三优惠价格
		$tpl_HTML->assign("NewRs_product_bn3",          $New_product[2]['bn']);        //最新商品三编号
		$tpl_HTML->assign("NewRs_product_img3",         $New_product[2]['smallimg']);  //最新商品三图片
		$tpl_HTML->assign("NewRs_product_intro3",       $New_product[2]['intro']);     //最新商品三内容
	}
	unset($Sql_level);
	unset($Query_level);

	//第四个产品的资料
	if (intval($New_product[3]['gid'])!=0){
		$tpl_HTML->assign("New_product_gid4",  $New_product[3]['gid']); //最新商品四ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$New_product[3]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$NewRs_productarray_level4[$j]['level_name'] = $Result_level['level_name'];
				$NewRs_productarray_level4[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("NewRs_productarray_level4",       $NewRs_productarray_level4);   //最新商品四会员价格数组
		$tpl_HTML->assign("NewRs_product_goodsname4",   $New_product[3]['goodsname']); //最新商品四名称
		$tpl_HTML->assign("NewRs_product_price4",       $New_product[3]['price']);     //最新商品四价格
		$tpl_HTML->assign("NewRs_product_price4_desc",  $New_product[3]['pricedesc']); //最新商品四优惠价格
		$tpl_HTML->assign("NewRs_product_bn4",          $New_product[3]['bn']);        //最新商品四编号
		$tpl_HTML->assign("NewRs_product_img4",         $New_product[3]['smallimg']);  //最新商品四图片
		$tpl_HTML->assign("NewRs_product_intro4",       $New_product[3]['intro']);     //最新商品四内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第五个产品的资料
	if (intval($New_product[4]['gid'])!=0){
		$tpl_HTML->assign("New_product_gid5",  $New_product[4]['gid']); //最新商品四ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$New_product[4]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$NewRs_productarray_level5[$j]['level_name'] = $Result_level['level_name'];
				$NewRs_productarray_level5[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("NewRs_productarray_level5",       $NewRs_productarray_level5);   //最新商品四会员价格数组
		$tpl_HTML->assign("NewRs_product_goodsname5",   $New_product[4]['goodsname']); //最新商品四名称
		$tpl_HTML->assign("NewRs_product_price5",       $New_product[4]['price']);     //最新商品四价格
		$tpl_HTML->assign("NewRs_product_price5_desc",  $New_product[4]['pricedesc']); //最新商品四优惠价格
		$tpl_HTML->assign("NewRs_product_bn5",          $New_product[4]['bn']);        //最新商品四编号
		$tpl_HTML->assign("NewRs_product_img5",         $New_product[4]['smallimg']);  //最新商品四图片
		$tpl_HTML->assign("NewRs_product_intro5",       $New_product[4]['intro']);     //最新商品四内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第六个产品的资料
	if (intval($New_product[5]['gid'])!=0){
		$tpl_HTML->assign("New_product_gid6",  $New_product[5]['gid']); //最新商品四ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$New_product[5]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$NewRs_productarray_level6[$j]['level_name'] = $Result_level['level_name'];
				$NewRs_productarray_level6[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("NewRs_productarray_level6",       $NewRs_productarray_level6);   //最新商品四会员价格数组
		$tpl_HTML->assign("NewRs_product_goodsname6",   $New_product[5]['goodsname']); //最新商品四名称
		$tpl_HTML->assign("NewRs_product_price6",       $New_product[5]['price']);     //最新商品四价格
		$tpl_HTML->assign("NewRs_product_price6_desc",  $New_product[5]['pricedesc']); //最新商品四优惠价格
		$tpl_HTML->assign("NewRs_product_bn6",          $New_product[5]['bn']);        //最新商品四编号
		$tpl_HTML->assign("NewRs_product_img6",         $New_product[5]['smallimg']);  //最新商品四图片
		$tpl_HTML->assign("NewRs_product_intro6",       $New_product[5]['intro']);     //最新商品四内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第七个产品的资料
	if (intval($New_product[6]['gid'])!=0){
		$tpl_HTML->assign("New_product_gid7",  $New_product[6]['gid']); //最新商品四ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$New_product[6]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$NewRs_productarray_level7[$j]['level_name'] = $Result_level['level_name'];
				$NewRs_productarray_level7[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("NewRs_productarray_level7",       $NewRs_productarray_level7);   //最新商品四会员价格数组
		$tpl_HTML->assign("NewRs_product_goodsname7",   $New_product[6]['goodsname']); //最新商品四名称
		$tpl_HTML->assign("NewRs_product_price7",       $New_product[6]['price']);     //最新商品四价格
		$tpl_HTML->assign("NewRs_product_price7_desc",  $New_product[6]['pricedesc']); //最新商品四优惠价格
		$tpl_HTML->assign("NewRs_product_bn7",          $New_product[6]['bn']);        //最新商品四编号
		$tpl_HTML->assign("NewRs_product_img7",         $New_product[6]['smallimg']);  //最新商品四图片
		$tpl_HTML->assign("NewRs_product_intro7",       $New_product[6]['intro']);     //最新商品四内容
	}
	unset($Sql_level);
	unset($Query_level);

	//第八个产品的资料
	if (intval($New_product[7]['gid'])!=0){
		$tpl_HTML->assign("New_product_gid8",  $New_product[7]['gid']); //最新商品四ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$New_product[7]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$NewRs_productarray_level8[$j]['level_name'] = $Result_level['level_name'];
				$NewRs_productarray_level8[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("NewRs_productarray_level8",       $NewRs_productarray_level8);   //最新商品四会员价格数组
		$tpl_HTML->assign("NewRs_product_goodsname8",   $New_product[7]['goodsname']); //最新商品四名称
		$tpl_HTML->assign("NewRs_product_price8",       $New_product[7]['price']);     //最新商品四价格
		$tpl_HTML->assign("NewRs_product_price8_desc",  $New_product[7]['pricedesc']); //最新商品四优惠价格
		$tpl_HTML->assign("NewRs_product_bn8",          $New_product[7]['bn']);        //最新商品四编号
		$tpl_HTML->assign("NewRs_product_img8",         $New_product[7]['smallimg']);  //最新商品四图片
		$tpl_HTML->assign("NewRs_product_intro8",       $New_product[7]['intro']);     //最新商品四内容
	}
	unset($Sql_level);
	unset($Query_level);

}



unset($Sql);
unset($Query);
//$tpl_HTML->assign("New_product", $New_product); //最新商品




/**
 * 推荐商品
 */
$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.pricedesc,g.intro ,g.alarmnum,g.storage,g.ifalarm from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid) where b.catiffb=1 and g.ifpub=1 and g.ifrecommend=1 and g.ifjs!=1  and g.ifbonus!=1 order by g.idate desc ";

$Query =    $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i=0;
while ( $RecPro = $DB->fetch_array($Query)){
	$Recommendation_product[$i]['gid']        = $RecPro['gid'] ;
	$Recommendation_product[$i]['goodsname']  = $RecPro['goodsname']."".$FUNCTIONS->Storage($RecPro['ifalarm'],$RecPro['storage'],$RecPro['alarmnum']);
	$Recommendation_product[$i]['price']      = $RecPro['price'] ;
	$Recommendation_product[$i]['pricedesc'] = $RecPro['pricedesc'] ;
	$Recommendation_product[$i]['bn']         = $RecPro['bn'] ;
	$Recommendation_product[$i]['smallimg']   = $RecPro['smallimg'] ;
	$Recommendation_product[$i]['intro']      = $Char_class->cut_str($RecPro['intro'],150,0,'UTF-8');
	$i++;
}


/**
 * 下边将输出产品资料
 */

if ($Num>0){
	//第一个产品的资料
	if (intval($Recommendation_product[0]['gid'])> 0 ){
		$tpl_HTML->assign("Recommendation_product_gid1",  $Recommendation_product[0]['gid']); //推荐商品一ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$Recommendation_product[0]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$Recommendation_productarray_level1[$j]['level_name'] = $Result_level['level_name'];
				$Recommendation_productarray_level1[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		unset($Sql_level);
		unset($Query_level);
		$tpl_HTML->assign("Recommendation_productarray_level1",  $Recommendation_productarray_level1);   //推荐商品一会员价格数组
		$tpl_HTML->assign("Recommendation_product_goodsname1",   $Recommendation_product[0]['goodsname']); //推荐商品一名称
		$tpl_HTML->assign("Recommendation_product_price1",       $Recommendation_product[0]['price']);     //推荐商品一价格
		$tpl_HTML->assign("Recommendation_product_price1_desc",  $Recommendation_product[0]['pricedesc']); //推荐商品一优惠价格
		$tpl_HTML->assign("Recommendation_product_bn1",          $Recommendation_product[0]['bn']);        //推荐商品一编号
		$tpl_HTML->assign("Recommendation_product_img1",         $Recommendation_product[0]['smallimg']);  //推荐商品一图片
		$tpl_HTML->assign("Recommendation_product_intro1",       $Recommendation_product[0]['intro']);     //推荐商品一内容
	}


	//第二个产品的资料
	if (intval($Recommendation_product[1]['gid'])> 0 ){
		$tpl_HTML->assign("Recommendation_product_gid2",  $Recommendation_product[1]['gid']); //推荐商品二ID
		$Sql_level   = " select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$Recommendation_product[1]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$Recommendation_productarray_level2[$j]['level_name'] = $Result_level['level_name'];
				$Recommendation_productarray_level2[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		//echo $Recommendation_product[1]['goodsname'];
		$tpl_HTML->assign("Recommendation_productarray_level2",  $Recommendation_productarray_level2);   //推荐商品二会员价格数组
		$tpl_HTML->assign("Recommendation_product_goodsname2",   $Recommendation_product[1]['goodsname']); //推荐商品二名称
		$tpl_HTML->assign("Recommendation_product_price2",       $Recommendation_product[1]['price']);     //推荐商品二价格
		$tpl_HTML->assign("Recommendation_product_price2_desc",  $Recommendation_product[1]['pricedesc']); //推荐商品二优惠价格
		$tpl_HTML->assign("Recommendation_product_bn2",          $Recommendation_product[1]['bn']);        //推荐商品二编号
		$tpl_HTML->assign("Recommendation_product_img2",         $Recommendation_product[1]['smallimg']);  //推荐商品二图片
		$tpl_HTML->assign("Recommendation_product_intro2",       $Recommendation_product[1]['intro']);     //推荐商品二内容
	}
	unset($Sql_level);
	unset($Query_level);

	//第三个产品的资料
	if (intval($Recommendation_product[2]['gid'])> 0 ){
		$tpl_HTML->assign("Recommendation_product_gid3",  $Recommendation_product[2]['gid']); //推荐商品三ID
		$Sql_level   = " select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$Recommendation_product[2]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$Recommendation_productarray_level3[$j]['level_name'] = $Result_level['level_name'];
				$Recommendation_productarray_level3[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("Recommendation_productarray_level3",  $Recommendation_productarray_level3);   //推荐商品三会员价格数组
		$tpl_HTML->assign("Recommendation_product_goodsname3",   $Recommendation_product[2]['goodsname']); //推荐商品三名称
		$tpl_HTML->assign("Recommendation_product_price3",       $Recommendation_product[2]['price']);     //推荐商品三价格
		$tpl_HTML->assign("Recommendation_product_price3_desc",  $Recommendation_product[2]['pricedesc']); //推荐商品三优惠价格
		$tpl_HTML->assign("Recommendation_product_bn3",          $Recommendation_product[2]['bn']);        //推荐商品三编号
		$tpl_HTML->assign("Recommendation_product_img3",         $Recommendation_product[2]['smallimg']);  //推荐商品三图片
		$tpl_HTML->assign("Recommendation_product_intro3",       $Recommendation_product[2]['intro']);     //推荐商品三内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第四个产品的资料
	if (intval($Recommendation_product[3]['gid'])> 0 ){
		$tpl_HTML->assign("Recommendation_product_gid4",  $Recommendation_product[3]['gid']); //推荐商品四ID
		$Sql_level   = " select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$Recommendation_product[3]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$Recommendation_productarray_level4[$j]['level_name'] = $Result_level['level_name'];
				$Recommendation_productarray_level4[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("Recommendation_productarray_level4",  $Recommendation_productarray_level4);   //推荐商品四会员价格数组
		$tpl_HTML->assign("Recommendation_product_goodsname4",   $Recommendation_product[3]['goodsname']); //推荐商品四名称
		$tpl_HTML->assign("Recommendation_product_price4",       $Recommendation_product[3]['price']);     //推荐商品四价格
		$tpl_HTML->assign("Recommendation_product_price4_desc",  $Recommendation_product[3]['pricedesc']); //推荐商品四优惠价格
		$tpl_HTML->assign("Recommendation_product_bn4",          $Recommendation_product[3]['bn']);        //推荐商品四编号
		$tpl_HTML->assign("Recommendation_product_img4",         $Recommendation_product[3]['smallimg']);  //推荐商品四图片
		$tpl_HTML->assign("Recommendation_product_intro4",       $Recommendation_product[3]['intro']);     //推荐商品四内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第五个产品的资料
	if (intval($Recommendation_product[4]['gid'])> 0 ){
		$tpl_HTML->assign("Recommendation_product_gid5",  $Recommendation_product[4]['gid']); //推荐商品四ID
		$Sql_level   = " select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$Recommendation_product[4]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$Recommendation_productarray_level5[$j]['level_name'] = $Result_level['level_name'];
				$Recommendation_productarray_level5[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("Recommendation_productarray_level5",  $Recommendation_productarray_level5);   //推荐商品四会员价格数组
		$tpl_HTML->assign("Recommendation_product_goodsname5",   $Recommendation_product[4]['goodsname']); //推荐商品四名称
		$tpl_HTML->assign("Recommendation_product_price5",       $Recommendation_product[4]['price']);     //推荐商品四价格
		$tpl_HTML->assign("Recommendation_product_price5_desc",  $Recommendation_product[4]['pricedesc']); //推荐商品四优惠价格
		$tpl_HTML->assign("Recommendation_product_bn5",          $Recommendation_product[4]['bn']);        //推荐商品四编号
		$tpl_HTML->assign("Recommendation_product_img5",         $Recommendation_product[4]['smallimg']);  //推荐商品四图片
		$tpl_HTML->assign("Recommendation_product_intro5",       $Recommendation_product[4]['intro']);     //推荐商品四内容
	}
	unset($Sql_level);
	unset($Query_level);

	//第六个产品的资料
	if (intval($Recommendation_product[5]['gid'])> 0 ){
		$tpl_HTML->assign("Recommendation_product_gid6",  $Recommendation_product[5]['gid']); //推荐商品四ID
		$Sql_level   = " select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$Recommendation_product[5]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$Recommendation_productarray_level6[$j]['level_name'] = $Result_level['level_name'];
				$Recommendation_productarray_level6[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("Recommendation_productarray_level6",  $Recommendation_productarray_level6);   //推荐商品四会员价格数组
		$tpl_HTML->assign("Recommendation_product_goodsname6",   $Recommendation_product[5]['goodsname']); //推荐商品四名称
		$tpl_HTML->assign("Recommendation_product_price6",       $Recommendation_product[5]['price']);     //推荐商品四价格
		$tpl_HTML->assign("Recommendation_product_price6_desc",  $Recommendation_product[5]['pricedesc']); //推荐商品四优惠价格
		$tpl_HTML->assign("Recommendation_product_bn6",          $Recommendation_product[5]['bn']);        //推荐商品四编号
		$tpl_HTML->assign("Recommendation_product_img6",         $Recommendation_product[5]['smallimg']);  //推荐商品四图片
		$tpl_HTML->assign("Recommendation_product_intro6",       $Recommendation_product[5]['intro']);     //推荐商品四内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第七个产品的资料
	if (intval($Recommendation_product[6]['gid'])> 0 ){
		$tpl_HTML->assign("Recommendation_product_gid7",  $Recommendation_product[6]['gid']); //推荐商品四ID
		$Sql_level   = " select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$Recommendation_product[6]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$Recommendation_productarray_level7[$j]['level_name'] = $Result_level['level_name'];
				$Recommendation_productarray_level7[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("Recommendation_productarray_level7",  $Recommendation_productarray_level7);   //推荐商品四会员价格数组
		$tpl_HTML->assign("Recommendation_product_goodsname7",   $Recommendation_product[6]['goodsname']); //推荐商品四名称
		$tpl_HTML->assign("Recommendation_product_price7",       $Recommendation_product[6]['price']);     //推荐商品四价格
		$tpl_HTML->assign("Recommendation_product_price7_desc",  $Recommendation_product[6]['pricedesc']); //推荐商品四优惠价格
		$tpl_HTML->assign("Recommendation_product_bn7",          $Recommendation_product[6]['bn']);        //推荐商品四编号
		$tpl_HTML->assign("Recommendation_product_img7",         $Recommendation_product[6]['smallimg']);  //推荐商品四图片
		$tpl_HTML->assign("Recommendation_product_intro7",       $Recommendation_product[6]['intro']);     //推荐商品四内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第八个产品的资料
	if (intval($Recommendation_product[7]['gid'])> 0 ){
		$tpl_HTML->assign("Recommendation_product_gid8",  $Recommendation_product[7]['gid']); //推荐商品四ID
		$Sql_level   = " select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$Recommendation_product[7]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$Recommendation_productarray_level8[$j]['level_name'] = $Result_level['level_name'];
				$Recommendation_productarray_level8[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("Recommendation_productarray_level8",  $Recommendation_productarray_level8);   //推荐商品四会员价格数组
		$tpl_HTML->assign("Recommendation_product_goodsname8",   $Recommendation_product[7]['goodsname']); //推荐商品四名称
		$tpl_HTML->assign("Recommendation_product_price8",       $Recommendation_product[7]['price']);     //推荐商品四价格
		$tpl_HTML->assign("Recommendation_product_price8_desc",  $Recommendation_product[7]['pricedesc']); //推荐商品四优惠价格
		$tpl_HTML->assign("Recommendation_product_bn8",          $Recommendation_product[7]['bn']);        //推荐商品四编号
		$tpl_HTML->assign("Recommendation_product_img8",         $Recommendation_product[7]['smallimg']);  //推荐商品四图片
		$tpl_HTML->assign("Recommendation_product_intro8",       $Recommendation_product[7]['intro']);     //推荐商品四内容
	}
	unset($Sql_level);
	unset($Query_level);


}
unset($Sql);
unset($Query);
//$tpl_HTML->assign("Recommendation_product", $Recommendation_product); //推荐商品

/**
 特价商品
 */

$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid  ) where  b.catiffb=1 and g.ifpub=1 and g.ifspecial=1 and g.ifbonus!=1 order by g.idate desc ";
$Query =    $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i=0;
while ( $SpecPro = $DB->fetch_array($Query)){
	$SpecialOffer_product[$i]['gid']        = $SpecPro['gid'] ;
	$SpecialOffer_product[$i]['goodsname']  = $SpecPro['goodsname']."".$FUNCTIONS->Storage($SpecPro['ifalarm'],$SpecPro['storage'],$SpecPro['alarmnum']);
	$SpecialOffer_product[$i]['price']      = $SpecPro['price'] ;
	$SpecialOffer_product[$i]['pricedesc']  = $SpecPro['pricedesc'] ;
	$SpecialOffer_product[$i]['bn']         = $SpecPro['bn'] ;
	$SpecialOffer_product[$i]['smallimg']   = trim($SpecPro['smallimg']) ;
	$SpecialOffer_product[$i]['intro']      = $Char_class->cut_str($SpecPro['intro'],150,0,'UTF-8');
	$i++;
}

//下边将输出产品资料

if ($Num>0){

	//第一个产品的资料

	if (intval($SpecialOffer_product[0]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid1",  $SpecialOffer_product[0]['gid']); //特价商品一ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[0]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level1[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level1[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level1",  $SpecialOffer_productarray_level1);     //特价商品一会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname1",   $SpecialOffer_product[0]['goodsname']); //特价商品一名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc1",   $SpecialOffer_product[0]['pricedesc']); //特价商品一特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price1",       $SpecialOffer_product[0]['price']);     //特价商品一价格
		$tpl_HTML->assign("SpecialOffer_product_bn1",          $SpecialOffer_product[0]['bn']);        //特价商品一编号
		$tpl_HTML->assign("SpecialOffer_product_img1",         $SpecialOffer_product[0]['smallimg']);  //特价商品一图片
		$tpl_HTML->assign("SpecialOffer_product_intro1",       $SpecialOffer_product[0]['intro']);     //特价商品一内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第二个产品的资料

	if (intval($SpecialOffer_product[1]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid2",  $SpecialOffer_product[1]['gid']); //特价商品二ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[1]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level2[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level2[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level2",  $SpecialOffer_productarray_level2);     //特价商品二会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname2",   $SpecialOffer_product[1]['goodsname']); //特价商品二名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc2",   $SpecialOffer_product[1]['pricedesc']); //特价商品二特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price2",       $SpecialOffer_product[1]['price']);     //特价商品二价格
		$tpl_HTML->assign("SpecialOffer_product_bn2",          $SpecialOffer_product[1]['bn']);        //特价商品二编号
		$tpl_HTML->assign("SpecialOffer_product_img2",         $SpecialOffer_product[1]['smallimg']);  //特价商品二图片
		$tpl_HTML->assign("SpecialOffer_product_intro2",       $SpecialOffer_product[1]['intro']);     //特价商品二内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第三个产品的资料

	if (intval($SpecialOffer_product[2]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid3",  $SpecialOffer_product[2]['gid']); //特价商品三ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[2]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level3[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level3[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level3",  $SpecialOffer_productarray_level3);     //特价商品三会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname3",   $SpecialOffer_product[2]['goodsname']); //特价商品三名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc3",   $SpecialOffer_product[2]['pricedesc']); //特价商品三特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price3",       $SpecialOffer_product[2]['price']);     //特价商品三价格
		$tpl_HTML->assign("SpecialOffer_product_bn3",          $SpecialOffer_product[2]['bn']);        //特价商品三编号
		$tpl_HTML->assign("SpecialOffer_product_img3",         $SpecialOffer_product[2]['smallimg']);  //特价商品三图片
		$tpl_HTML->assign("SpecialOffer_product_intro3",       $SpecialOffer_product[2]['intro']);     //特价商品三内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第四个产品的资料

	if (intval($SpecialOffer_product[3]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid4",  $SpecialOffer_product[3]['gid']); //特价商品四ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[3]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level4[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level4[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level4",  $SpecialOffer_productarray_level4);     //特价商品四会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname4",   $SpecialOffer_product[3]['goodsname']); //特价商品四名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc4",   $SpecialOffer_product[3]['pricedesc']); //特价商品四特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price4",       $SpecialOffer_product[3]['price']);     //特价商品四价格
		$tpl_HTML->assign("SpecialOffer_product_bn4",          $SpecialOffer_product[3]['bn']);        //特价商品四编号
		$tpl_HTML->assign("SpecialOffer_product_img4",         $SpecialOffer_product[3]['smallimg']);  //特价商品四图片
		$tpl_HTML->assign("SpecialOffer_product_intro4",       $SpecialOffer_product[3]['intro']);     //特价商品四内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第五个产品的资料

	if (intval($SpecialOffer_product[4]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid5",  $SpecialOffer_product[4]['gid']); //特价商品五ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[4]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level5[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level5[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level5",  $SpecialOffer_productarray_level5);     //特价商品五会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname5",   $SpecialOffer_product[4]['goodsname']); //特价商品五名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc5",   $SpecialOffer_product[4]['pricedesc']); //特价商品五特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price5",       $SpecialOffer_product[4]['price']);     //特价商品五价格
		$tpl_HTML->assign("SpecialOffer_product_bn5",          $SpecialOffer_product[4]['bn']);        //特价商品五编号
		$tpl_HTML->assign("SpecialOffer_product_img5",         $SpecialOffer_product[4]['smallimg']);  //特价商品五图片
		$tpl_HTML->assign("SpecialOffer_product_intro5",       $SpecialOffer_product[4]['intro']);     //特价商品五内容
	}
	unset($Sql_level);
	unset($Query_level);

	//第六个产品的资料

	if (intval($SpecialOffer_product[5]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid6",  $SpecialOffer_product[5]['gid']); //特价商品六ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[5]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level6[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level6[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level6",  $SpecialOffer_productarray_level6);     //特价商品六会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname6",   $SpecialOffer_product[5]['goodsname']); //特价商品六名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc6",   $SpecialOffer_product[5]['pricedesc']); //特价商品六特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price6",       $SpecialOffer_product[5]['price']);     //特价商品六价格
		$tpl_HTML->assign("SpecialOffer_product_bn6",          $SpecialOffer_product[5]['bn']);        //特价商品六编号
		$tpl_HTML->assign("SpecialOffer_product_img6",         $SpecialOffer_product[5]['smallimg']);  //特价商品六图片
		$tpl_HTML->assign("SpecialOffer_product_intro6",       $SpecialOffer_product[5]['intro']);     //特价商品六内容
	}
	unset($Sql_level);
	unset($Query_level);

	//第七个产品的资料

	if (intval($SpecialOffer_product[6]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid7",  $SpecialOffer_product[6]['gid']); //特价商品七ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[6]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level7[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level7[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level7",  $SpecialOffer_productarray_level7);     //特价商品七会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname7",   $SpecialOffer_product[6]['goodsname']); //特价商品七名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc7",   $SpecialOffer_product[6]['pricedesc']); //特价商品七特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price7",       $SpecialOffer_product[6]['price']);     //特价商品七价格
		$tpl_HTML->assign("SpecialOffer_product_bn7",          $SpecialOffer_product[6]['bn']);        //特价商品七编号
		$tpl_HTML->assign("SpecialOffer_product_img7",         $SpecialOffer_product[6]['smallimg']);  //特价商品七图片
		$tpl_HTML->assign("SpecialOffer_product_intro7",       $SpecialOffer_product[6]['intro']);     //特价商品七内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第八个产品的资料

	if (intval($SpecialOffer_product[7]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid8",  $SpecialOffer_product[7]['gid']); //特价商品八ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[7]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level8[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level8[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level8",  $SpecialOffer_productarray_level8);     //特价商品八会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname8",   $SpecialOffer_product[7]['goodsname']); //特价商品八名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc8",   $SpecialOffer_product[7]['pricedesc']); //特价商品八特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price8",       $SpecialOffer_product[7]['price']);     //特价商品八价格
		$tpl_HTML->assign("SpecialOffer_product_bn8",          $SpecialOffer_product[7]['bn']);        //特价商品八编号
		$tpl_HTML->assign("SpecialOffer_product_img8",         $SpecialOffer_product[7]['smallimg']);  //特价商品八图片
		$tpl_HTML->assign("SpecialOffer_product_intro8",       $SpecialOffer_product[7]['intro']);     //特价商品八内容
	}
	unset($Sql_level);
	unset($Query_level);


	//第九个产品的资料

	if (intval($SpecialOffer_product[8]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid9",  $SpecialOffer_product[8]['gid']); //特价商品九ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[8]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level9[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level9[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level9",  $SpecialOffer_productarray_level9);     //特价商品九会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname9",   $SpecialOffer_product[8]['goodsname']); //特价商品九名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc9",   $SpecialOffer_product[8]['pricedesc']); //特价商品九特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price9",       $SpecialOffer_product[8]['price']);     //特价商品九价格
		$tpl_HTML->assign("SpecialOffer_product_bn9",          $SpecialOffer_product[8]['bn']);        //特价商品九编号
		$tpl_HTML->assign("SpecialOffer_product_img9",         $SpecialOffer_product[8]['smallimg']);  //特价商品九图片
		$tpl_HTML->assign("SpecialOffer_product_intro9",       $SpecialOffer_product[8]['intro']);     //特价商品九内容
	}
	unset($Sql_level);
	unset($Query_level);

	//第十个产品的资料

	if (intval($SpecialOffer_product[9]['gid'])> 0 ){
		$tpl_HTML->assign("SpecialOffer_product_gid10",  $SpecialOffer_product[9]['gid']); //特价商品十ID
		$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecialOffer_product[9]['gid'];
		$Query_level = $DB->query($Sql_level);
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$SpecialOffer_productarray_level10[$j]['level_name'] = $Result_level['level_name'];
				$SpecialOffer_productarray_level10[$j]['m_price']    = $Result_level['m_price'];
				$j++;
			}
		}
		$tpl_HTML->assign("SpecialOffer_productarray_level10",  $SpecialOffer_productarray_level10);     //特价商品十会员价格数组
		$tpl_HTML->assign("SpecialOffer_product_goodsname10",   $SpecialOffer_product[9]['goodsname']); //特价商品十名称
		$tpl_HTML->assign("SpecialOffer_product_pricedesc10",   $SpecialOffer_product[9]['pricedesc']); //特价商品十特惠价格
		$tpl_HTML->assign("SpecialOffer_product_price10",       $SpecialOffer_product[9]['price']);     //特价商品十价格
		$tpl_HTML->assign("SpecialOffer_product_bn10",          $SpecialOffer_product[9]['bn']);        //特价商品十编号
		$tpl_HTML->assign("SpecialOffer_product_img10",         $SpecialOffer_product[9]['smallimg']);  //特价商品十图片
		$tpl_HTML->assign("SpecialOffer_product_intro10",       $SpecialOffer_product[9]['intro']);     //特价商品十内容
	}
	unset($Sql_level);
	unset($Query_level);


}


unset($Sql);
unset($Num);
unset($Query);


$tpl_HTML->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl_HTML->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关

$tpl_HTML->assign($Good);
$tpl_HTML->assign($INFO);



/*-------------------------------------------------------------这个是INDEX。HTML结束-------------------------------------------*/

$tpl_HTML->assign("template_dir",  $INFO['site_url']."/templates/".$templates); //摸板路径
$tpl_HTML->assign("Site_Url",      $INFO['site_url']); //主站URL
$tpl_HTML->assign("LanguageIs",    $INFO['IS']); //语言包类型
$tpl_HTML->assign("advs_pic_path", $INFO['advs_pic_path']); //广告图片路径
$tpl_HTML->assign("good_pic_path", $INFO['good_pic_path']); //产品图片路径
$tpl_HTML->assign("HtmlTitle",     $INFO['site_title']);     //TITLE内容
$tpl_HTML->assign("Meta_keyword",  $INFO['meta_keyword']);   //META内容
$tpl_HTML->assign("Meta_desc",     $INFO['meta_desc']);      //META内容
$tpl_HTML->assign("RootDocument",         $doc_root);
$tpl_HTML->assign("RootDocumentShare",    $doc_root."/".ConfigDir);
$tpl_HTML->assign("RootDocumentAdmin",    $doc_root."/".ShopAdmin);
$tpl_HTML->assign("OtherPach",            $OtherPach);





$content = $tpl_HTML->fetch("index.html");


$Html_Smarty = new Html_Smarty;
$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/index.html", $content);
//$date= $tpl_HTML->display("admin_order_print.html");

//$tpl_HTML->display("index.html");

//$fp=fopen("order_doc/order.html","w+");
//fwrite($fp,$data);
//fclose($fp);

if ($resultHtml){
	echo "Ok!";
}else{
	echo "Bad!";
}
?>
