<?php
class CreateProductHtml {
	var $Sql;
	var $Goods_id;

	function CreateProductHtml_Action($Goods_id){
		global $DB,$FUNCTIONS,$INFO,$Html_Smarty,$tpl_HTML,$Char_class,$StaticHtml_Pack;
		global $Good,$templates,$doc_root,$OtherPach;
		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();

		include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");
		$tpl_HTML->assign($Email_Pack);
		$tpl_HTML->assign($Good);

		$this->Sql =   "select b.attr,g.goodsname,g.brand_id,g.view_num,g.nocarriage,g.pricedesc,g.bn,g.ifgl,g.bid,g.unit,g.intro,g.price,g.point,g.body,g.middleimg,g.smallimg,g.goodattr,g.good_color,g.good_size,g.ifrecommend,g.ifspecial,g.ifalarm,g.storage,g.alarmnum,g.ifbonus,g.ifhot,g.provider_id,g.ifjs,g.js_begtime,g.js_endtime,g.js_price,g.js_totalnum,p.provider_name,br.brandname,br.logopic from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)   where  b.catiffb=1 and g.ifpub=1 and g.gid=".$Goods_id." limit 0,1";
		$Query   = $DB->query($this->Sql);
		$Num   = $DB->num_rows($Query);

		if ( $Num==0 ){ //如果不存在资料
			echo "0";//$Basic_Command['NullDate'];
			return true;
		}

		if ($Num>0){
			$Result_goods = $DB->fetch_array($Query);
			if ($Result_goods['attr']!=""){
				$attrI        =  $Result_goods['attr'];
				$goods_attrI  =  $Result_goods['goodattr'];
				$Attr         =  explode(',',$attrI);
				$Goods_Attr   =  explode(',',$goods_attrI);
				$Attr_num=  count($Attr);
			}else{
				$Attr_num=0;
			}

			//循环多属性部分
			if (is_array($Attr) && intval($Attr_num)>0 ){
				$AttrArray = array();
				$ProductArray = array();
				for($i=0;$i<$Attr_num;$i++){
					$AttrArray[$i]        = $Attr[$i];
					$ProductArray[$i]     = $Goods_Attr[$i];
					$ProductAttrArray[$i] = array($Attr[$i]=>$Goods_Attr[$i]);
				}
			}
			$tpl_HTML->assign("ProductAttrArray",      $ProductAttrArray);    //循环多属性部分数组




			//print_r ($ProductAttrArray);


			$Goodsname = $Result_goods['goodsname']."".$FUNCTIONS->Storage($Result_goods['ifalarm'],$Result_goods['storage'],$Result_goods['alarmnum']);
			$NoCarriage= $Result_goods['nocarriage'];
			if (trim($Result_goods['brandname'])!=""){
			  // $Brand     =  "<a href='".$INFO[site_url]."/brand/brand_product_list.php?BrandID=".$Result_goods['brand_id']."'>".$Result_goods['brandname']."</a>";		
			  $Brand     = "<a href='".$INFO[site_url]."/HTML_C/brand_list_".intval($Result_goods['brand_id'])."_0.html'>".$Result_goods['brandname']."</a>";
			}else{
			  $Brand     = "";
			}		
		
			$View_num  = $Result_goods['view_num'];
			$Bn        = $Result_goods['bn'];
			$Ifgl      = $Result_goods['ifgl'];
			$Bid       = $Result_goods['bid'];
			$Unit      = $Result_goods['unit'];
			$Intro     = $Char_class->cut_str($Result_goods['intro'],200,0,'UTF-8');
			$Price     = $Result_goods['price'];
			$Pricedesc = $Result_goods['pricedesc'];
			$Point     = $Result_goods['point'];
			$Body      = $Result_goods['body'];
			$Smallimg  = $Result_goods['smallimg'];
			$Middleimg = $Result_goods['middleimg'];
			$Alarmnum  = $Result_goods['alarmnum'];
			$Storage   = intval($Result_goods['storage']);
			$Ifalarm   = intval($Result_goods['ifalarm']);
			$Provider_name  = $Result_goods['provider_name'];
			$Ifrecommend  = intval($Result_goods['ifrecommend']);
			$Ifspecial = intval($Result_goods['ifspecial']);
			$Ifhot     = intval($Result_goods['ifhot']);
			$Ifbonus   = intval($Result_goods['ifbonus']);



			/**
             *  获得产品颜色下拉菜单内变量
             */

			if (trim($Result_goods['good_color'])!=""){
				$Good_color_array    =  explode(',',trim($Result_goods['good_color']));

				if (is_array($Good_color_array)){
					foreach($Good_color_array as $k=>$v )
					{
						$Good_Color_Option .= "<option value='".$v."'>".$v."</option>\n";
					}
				}else{
					$Good_Color_Option = "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Color_Option = "";
			}

			$tpl_HTML->assign("Good_Color_Option", $Good_Color_Option);

			/**
             *  获得产品尺寸下拉菜单内变量
             */

			if (trim($Result_goods['good_size'])!=""){
				$Good_size_array    =  explode(',',trim($Result_goods['good_size']));

				if (is_array($Good_size_array)){
					foreach($Good_size_array as $k=>$v )
					{
						$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";
					}
				}else{
					$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Size_Option = "";
			}


			$tpl_HTML->assign("Good_Size_Option", $Good_Size_Option);

			$goodattrI   =  "0,".$Result_goods['goodattr'];
			$goodAttr    =  explode(',',$goodattrI);





			//集杀
			$Ifjs       =  intval($Result_goods['ifjs']);
			if ($Ifjs==1){
				$begtime    =  trim($Result_goods['js_begtime']);
				$endtime    =  trim($Result_goods['js_endtime']);
				$Js_price   =  explode("||",trim($Result_goods['js_price']));
				$Js_totalnum=  intval($Result_goods['js_totalnum']);

				$TotalNum = 0;
				foreach ($Js_price as $k=>$v){
					$TotalNum = $TotalNum+$v;
				}

				$i = 0;
				foreach ($Js_price as $k=>$v){
					if (intval($v)>0){
						$Js_open[$i]['js_num']       =  $v;
						$Js_open[$i]['js_percent']   =  round(intval($v)/intval($TotalNum)*100,1);
						$Js_open[$i]['js_height']    =  intval($v)/intval($TotalNum);
					}
					$i++;
				}

				$tpl_HTML->assign("js_num1",   $Js_open[0]['js_num']);
				$tpl_HTML->assign("js_num2",   $Js_open[1]['js_num']);
				$tpl_HTML->assign("js_num3",   $Js_open[2]['js_num']);
				$tpl_HTML->assign("js_num4",   $Js_open[3]['js_num']);
				$tpl_HTML->assign("js_num5",   $Js_open[4]['js_num']);

				$tpl_HTML->assign("js_percent1",   $Js_open[0]['js_percent']);
				$tpl_HTML->assign("js_percent2",   $Js_open[1]['js_percent']);
				$tpl_HTML->assign("js_percent3",   $Js_open[2]['js_percent']);
				$tpl_HTML->assign("js_percent4",   $Js_open[3]['js_percent']);
				$tpl_HTML->assign("js_percent5",   $Js_open[4]['js_percent']);

				$tpl_HTML->assign("js_height1",   $Js_open[0]['js_height']);
				$tpl_HTML->assign("js_height2",   $Js_open[1]['js_height']);
				$tpl_HTML->assign("js_height3",   $Js_open[2]['js_height']);
				$tpl_HTML->assign("js_height4",   $Js_open[3]['js_height']);
				$tpl_HTML->assign("js_height5",   $Js_open[4]['js_height']);

				$tpl_HTML->assign("Js_totalnum",   $Js_totalnum);
				$tpl_HTML->assign("Js_begtime",   $begtime);
				$tpl_HTML->assign("Js_endtime",   $endtime);

			}

			//数据库变量
			$tpl_HTML->assign("Goods_id",   $Goods_id);    //商品id
			$tpl_HTML->assign("Goodsname",  $Goodsname);   //商品名称
			$tpl_HTML->assign("base64Goodsname",  base64_encode($Goodsname));   //URL处理过的商品名称

			$tpl_HTML->assign("NoCarriage", $NoCarriage);   //商品名称
			$tpl_HTML->assign("Brand",      $Brand);       //商品品牌
			$tpl_HTML->assign("View_num",   $View_num);    //浏览次数
			$tpl_HTML->assign("Bn",         $Bn);          //编号
			$tpl_HTML->assign("Bid",        $Bid);         //类别ID
			$tpl_HTML->assign("Unit",       $Unit);        //产品单位
			$tpl_HTML->assign("Intro",      $Intro);       //简单介绍
			$tpl_HTML->assign("Price",      $Price);       //价格
			$tpl_HTML->assign("Pricedesc",  $Pricedesc);   //优惠价格
			$tpl_HTML->assign("Point",      $Point);       //积分点
			$tpl_HTML->assign("Body",       $Body);        //主题介绍
			$tpl_HTML->assign("goodAttr",   $goodAttr);    //多级别属性数组
			$tpl_HTML->assign("Attr",       $Attr);        //多级别属性数组
			$tpl_HTML->assign("Attr_num",   $Attr_num);    //多级别属性数组个数
			$tpl_HTML->assign("Smallimg",   $Smallimg);    //产品略缩小图
			$tpl_HTML->assign("Middleimg",  $Middleimg);   //产品略中等图
			$tpl_HTML->assign("Ifrecommend",$Ifrecommend);    //推荐产品开关
			$tpl_HTML->assign("Ifspecial",  $Ifspecial);      //是否特价
			$tpl_HTML->assign("Ifhot",      $Ifhot);          //是否热卖
			$tpl_HTML->assign("Ifbonus",    $Ifbonus);        //是否红利
			$tpl_HTML->assign("Provider_name", $Provider_name);    //供应商名字
			$tpl_HTML->assign("Url",           $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);    //URL
			$tpl_HTML->assign("Alarmnum",    $Alarmnum);        //库存警告数字
			$tpl_HTML->assign("Storage",     $Storage);        //库存

			/**
             * 当警告开关开启的时候，判断是否库存已经小于或等于零，如果为真，将抛出一个缺货的声明变量。
             */
			if ($Ifalarm>0 && $Storage <= 0){
				$tpl_HTML->assign("AlertZeroStorage",     $Good[AlertZeroStorageText]);        //库存
			}


			//用户品论部分
			$Query = $DB->query(" select comment_idate,comment_content,comment_answer from `{$INFO[DBPrefix]}good_comment` where good_id=".$Goods_id." order by comment_idate desc limit 0,10 ");
			$CommentArray=array();
			$i = 0;
			while ($Rs =  $DB->fetch_array($Query)){
				$CommentArray[$i]['comment_idate']   = date("Y-m-d H:i a",$Rs['comment_idate']);
				$CommentArray[$i]['comment_content'] = nl2br($Rs['comment_content']);
				$CommentArray[$i]['comment_answer']  = isset($Rs['comment_answer']) ? nl2br($Rs['comment_answer']) : $PROG_TAGS["ptag_297"];//尚未回复评论
				$i++;
			}

			$tpl_HTML->assign("CommentArray",      $CommentArray);    //评论部分数组



			// 相关产品

			if (intval($Ifgl)==1){ //判断是否是指定了产品内容，如果没有就把本类产品资料都调出来
				$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc,gl.s_gid from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}good_link` gl  on (g.gid=gl.s_gid) where g.ifpub=1 and gl.p_gid=".$Goods_id;
			}else{
				$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.pricedesc,g.intro from `{$INFO[DBPrefix]}goods` g where g.bid=".$Bid." and g.gid!=".$Goods_id." and g.ifpub=1 order by g.idate desc limit 0,8 ";
			}

			$Query = $DB->query($Sql);
			$i=1;
			$j=0;
			$abProductArray = array();

			while ($Rs =  $DB->fetch_array($Query)){
				$abProductArray[$j]['autonum']    = $i;
				$abProductArray[$j]['Bgcolor']    = $i%2==0 ?  "#FAFAFA" : 'white';
				$abProductArray[$j]['goodsname']  = $Rs['goodsname'];
				$abProductArray[$j]['gid']        = $Rs['gid'];
				$abProductArray[$j]['price']      = $Rs['price'];
				$abProductArray[$j]['pricedesc']  = $Rs['pricedesc'];
				$abProductArray[$j]['smallimg']   = $Rs['smallimg'];
				$abProductArray[$j]['middleimg']  = $Rs['middleimg'];
				$abProductArray[$j]['intro']      = nl2br($Rs['intro']);
				$i++;
				$j++;
			}
			$tpl_HTML->assign("abProductArray",      $abProductArray);    //相关产品数组



			//产品价格体系

			$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$Goods_id;
			$Query_level = $DB->query($Sql_level);
			$Num_level =   $DB->num_rows($Query_level);
			if ($Num_level>0){
				$i=0;
				while ($Result_level=$DB->fetch_array($Query_level))
				{
					if (intval($Result_level['m_price'])!=0){
						$PriceArray[$i]['level_name']  =   $Result_level['level_name'];
						$PriceArray[$i]['m_price']     =   $Result_level['m_price'];
					}
					$i++;
				}
			}
			$tpl_HTML->assign("PriceArray",      $PriceArray);    //产品价格体系数组


			/**
             * 上一商品
             */
			$Sql_Up =   "select g.gid,b.attr,g.goodsname,g.brand,g.brand_id,g.view_num,g.video_url,g.nocarriage,g.keywords,g.pricedesc,g.bn,g.ifgl,g.bid,g.unit,g.intro,g.price,g.point,g.body,g.middleimg,g.smallimg,g.bigimg,g.gimg,g.goodattr,g.good_color,g.good_size,g.ifrecommend,g.ifspecial,g.ifalarm,g.storage,g.alarmnum,g.ifbonus,g.ifhot,g.provider_id,g.ifjs,g.js_begtime,g.js_endtime,g.js_price,g.js_totalnum,p.provider_name,br.brandname,br.logopic from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)   where  b.catiffb='1' and g.ifpub='1' and g.bid='".$Bid."' and g.gid<'".$Goods_id."' limit 0,1";
			$Query_Up   = $DB->query($Sql_Up);
			$Num_Up     = $DB->num_rows($Query_Up);

			if ( $Num_Up>0 ) {
				$HaveUp    = "Yes";
				$Rs_Up    = $DB->fetch_array($Query_Up);
				$HaveUpId = $Rs_Up[gid];
			}

			$tpl_HTML->assign("HaveUp",$HaveUp);
			$tpl_HTML->assign("HaveUpId",$HaveUpId);



			/**
  			 * 下一商品
  			 */
			$Sql_Next = "select g.gid,b.attr,g.goodsname,g.brand,g.brand_id,g.view_num,g.video_url,g.nocarriage,g.keywords,g.pricedesc,g.bn,g.ifgl,g.bid,g.unit,g.intro,g.price,g.point,g.body,g.middleimg,g.smallimg,g.bigimg,g.gimg,g.goodattr,g.good_color,g.good_size,g.ifrecommend,g.ifspecial,g.ifalarm,g.storage,g.alarmnum,g.ifbonus,g.ifhot,g.provider_id,g.ifjs,g.js_begtime,g.js_endtime,g.js_price,g.js_totalnum,p.provider_name,br.brandname,br.logopic from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)   where  b.catiffb='1' and g.ifpub='1' and g.bid='".$Bid."' and g.gid>'".$Goods_id."' limit 0,1";
			$Query_Next   = $DB->query($Sql_Next);
			$Num_Next   = $DB->num_rows($Query_Next);

			if ( $Num_Next>0 ) {
				$HaveNext    = "Yes";
				$Rs_Next    = $DB->fetch_array($Query_Next);
				$HaveNextId = $Rs_Next[gid];
			}

			$tpl_HTML->assign("HaveNext",$HaveNext);
			$tpl_HTML->assign("HaveNextId",$HaveNextId);











			if ($Ifjs==0){
				$tpl_HTML_page= "goods_detail.html";
			}elseif($Ifjs==1){
				$tpl_HTML_page= "goods_detail_js.html";
			}

			/**
             *  得到静态资料 
             */
			$content = $tpl_HTML->fetch($tpl_HTML_page);

			$content = preg_replace($patterns,$replacements , $content);
			$Html_Smarty = new Html_Smarty;
			$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/product_{$Goods_id}.html", $content);
			if ($resultHtml){
				if (trim($_GET['url'])!=""){
					$gid = intval($_GET[gid]);
					$FUNCTIONS->sorry_back("$INFO[site_url]/HTML_C/product_{$gid}.html","");
					//@header("location:$INFO[site_url]./HTML_C/product_{$gid}.html");
					exit;
				}
				echo "HTML_C/product_{$Goods_id}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
			}else{
				echo "HTML_C/product_{$Goods_id}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";

			}
		}
	}
}

?>
