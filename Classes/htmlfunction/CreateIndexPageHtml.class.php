<?php
class CreateIndexPageHtml {

	function CreateIndexPageHtml_Action(){
		global $DB,$FUNCTIONS,$INFO,$Html_Smarty,$Char_class,$tpl_HTML,$StaticHtml_Pack,$Bottom_Pack;
		global $Good,$templates,$doc_root,$OtherPach;

		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();

		/**
         * 这里是主页的标签公告ID,及高度
         */
		$tpl_HTML->assign("index_iframe_height",  $INFO[index_iframe_height]);
		$tpl_HTML->assign("index_iframe_id",  $INFO[index_iframe_id]);

		/**
         * 主页面LOGO的尺寸
         */ 
		$tpl_HTML->assign("logo_width",  $INFO["logo_width"]);
		$tpl_HTML->assign("logo_height", $INFO["logo_height"]);



		/**
         * 如当前商店设定的模板不存在，将出现提醒信息，并要求用户请稍候访问……"
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
         * 最新商品
         */

		$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.pricedesc,g.intro,g.alarmnum,g.storage,g.ifalarm from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where b.catiffb='1' and g.ifpub='1' and g.ifjs!='1' and g.ifbonus!='1'  order by g.gid  desc limit 0,".intval($INFO['MaxNewProductNum'])."";
		$Query =    $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$i=0;
		$j=1;
		while ( $NewPro = $DB->fetch_array($Query)){
			$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$NewPro['gid'];
			$Query_level = $DB->query($Sql_level);
			$v=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$NewRs_productarray_level[$v]['level_name'] = $Result_level['level_name'];
					$NewRs_productarray_level[$v]['m_price']    = $Result_level['m_price'];
					$v++;
				}
			}
			$tpl_HTML->assign("NewRs_productarray_level".$j,  $NewRs_productarray_level);   //最新商品会员价格数组

			$tpl_HTML->assign("New_product_gid".$j,             $NewPro['gid']); //最新商品ID
			$tpl_HTML->assign("NewRs_product_goodsname".$j,     $NewPro['goodsname']."".$FUNCTIONS->Storage($NewPro['ifalarm'],$NewPro['storage'],$NewPro['alarmnum'])); //最新商品一名称
			$tpl_HTML->assign("NewRs_product_price".$j,         $NewPro['price']);     //最新商品价格
			$tpl_HTML->assign("NewRs_product_price".$j."_desc", $NewPro['pricedesc']); //最新商品优惠价格
			$tpl_HTML->assign("NewRs_product_bn".$j,            $NewPro['bn']);        //最新商品编号
			$tpl_HTML->assign("NewRs_product_img".$j,           trim($NewPro['smallimg']));  //最新商品图片
			$tpl_HTML->assign("NewRs_product_middleimg".$j,     $New_product[$i]['middleimg']);
			$tpl_HTML->assign("NewRs_product_bigimg".$j,        trim($NewPro['bigimg']));
			$tpl_HTML->assign("NewRs_product_gimg".$j,          trim($NewPro['gimg']));
			$tpl_HTML->assign("NewRs_product_intro".$j,         $Char_class->cut_str($NewPro['intro'],150,0,'UTF-8'));     //最新商品内容

			$i++;
			$j++;
		}

		unset($i);
		unset($j);





		/**
         * 推荐商品
         */
		$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.pricedesc,g.intro ,g.alarmnum,g.storage,g.ifalarm from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid) where b.catiffb=1 and g.ifpub=1 and g.ifrecommend=1 and g.ifjs!=1  and g.ifbonus!=1 order by g.goodorder asc,g.idate desc limit  0,".intval($INFO['MaxProductNumForList'])."";

		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$i=0;
		$j=1;
		$Sql_level = "";
		$Recommendation_productarray_level = array();

		while ( $RecPro = $DB->fetch_array($Query)){
			$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$RecPro['gid'];
			$Query_level = $DB->query($Sql_level);
			$v=0;
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$Recommendation_productarray_level[$v]['level_name'] = $Result_level['level_name'];
					$Recommendation_productarray_level[$v]['m_price']    = $Result_level['m_price'];
					$v++;
				}
			}
			$tpl_HTML->assign("Recommendation_productarray_level".$j,  $Recommendation_productarray_level);   //推荐商品一会员价格数组
			$tpl_HTML->assign("Recommendation_product_gid".$j,         $RecPro['gid']); //推荐商品一ID
			$tpl_HTML->assign("Recommendation_product_goodsname".$j,   $RecPro['goodsname']."".$FUNCTIONS->Storage($RecPro['ifalarm'],$RecPro['storage'],$RecPro['alarmnum'])); //推荐商品一名称
			$tpl_HTML->assign("Recommendation_product_price".$j,       $RecPro['price']);     //推荐商品一价格
			$tpl_HTML->assign("Recommendation_product_price".$j."_desc",  $RecPro['pricedesc']); //推荐商品一优惠价格
			$tpl_HTML->assign("Recommendation_product_bn".$j,          $RecPro['bn']);        //推荐商品一编号
			$tpl_HTML->assign("Recommendation_product_img".$j,         $RecPro['smallimg']);  //推荐商品一图片
			$tpl_HTML->assign("Recommendation_product_middleimg".$j,   trim($RecPro['middleimg']));
			$tpl_HTML->assign("Recommendation_product_bigimg".$j,      trim($RecPro['bigimg']));
			$tpl_HTML->assign("Recommendation_product_gimg".$j,        trim($RecPro['gimg']));
			$tpl_HTML->assign("Recommendation_product_intro".$j,       $Char_class->cut_str($RecPro['intro'],150,0,'UTF-8'));     //推荐商品一内容

			$i++;
			$j++;

		}
		unset($i);
		unset($j);

		/**
         * 特价商品
         */

		$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid  ) where  b.catiffb=1 and g.ifpub=1 and g.ifspecial=1 and g.ifbonus!=1 order by g.goodorder asc,g.idate desc limit  0,".intval($INFO['MaxProductNumForList'])."";
		$Query =    $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$i=0;
		$j=1;
		$Sql_level = "";
		$SpecialOffer_productarray_level = array();

		while ( $SpecPro = $DB->fetch_array($Query)){
			if ((intval($SpecPro['ifjs'])==1 && $SpecPro['js_begtime']<=date("Y-m-d",time()) && $SpecPro['js_endtime']>=date("Y-m-d",time())) || intval($SpecPro['ifjs'])!=1 ){

				$Sql_level   = "select u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$SpecPro['gid'];
				$Query_level = $DB->query($Sql_level);
				$v=0;
				while ($Result_level=$DB->fetch_array($Query_level)){
					if (intval($Result_level['m_price'])!=0){
						$SpecialOffer_productarray_level[$v]['level_name'] = $Result_level['level_name'];
						$SpecialOffer_productarray_level[$v]['m_price']    = $Result_level['m_price'];
						$v++;
					}
				}

				$tpl_HTML->assign("SpecialOffer_productarray_level".$j,  $SpecialOffer_productarray_level);     //特价商品会员价格数组

				$tpl_HTML->assign("SpecialOffer_product_gid".$j,         $SpecPro['gid']); //特价商品ID
				$tpl_HTML->assign("SpecialOffer_product_goodsname".$j,   $SpecPro['goodsname']."".$FUNCTIONS->Storage($SpecPro['ifalarm'],$SpecPro['storage'],$SpecPro['alarmnum'])); //特价商品一名称
				$tpl_HTML->assign("SpecialOffer_product_pricedesc".$j,   $SpecPro['pricedesc']); //特价商品特惠价格
				$tpl_HTML->assign("SpecialOffer_product_price".$j,       $SpecPro['price']);     //特价商品价格
				$tpl_HTML->assign("SpecialOffer_product_bn".$j,          $SpecPro['bn']);        //特价商品编号
				$tpl_HTML->assign("SpecialOffer_product_img".$j,         trim($SpecPro['smallimg']));  //特价商品图片
				$tpl_HTML->assign("SpecialOffer_product_middleimg".$j,   trim($SpecPro['middleimg']));
				$tpl_HTML->assign("SpecialOffer_product_bigimg".$j,      trim($SpecPro['bigimg']));
				$tpl_HTML->assign("SpecialOffer_product_gimg".$j,        trim($SpecPro['gimg']));
				$tpl_HTML->assign("SpecialOffer_product_intro".$j,       $Char_class->cut_str($SpecPro['intro'],150,0,'UTF-8'));     //特价商品内容

				$i++;
				$j++;
			}
		}

		unset($i);
		unset($j);

		unset($Sql);
		unset($Num);
		unset($Query);


		$tpl_HTML->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
		$tpl_HTML->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关

		$tpl_HTML->assign($Good);
		$tpl_HTML->assign($INFO);



		/*-------------------------------------------------------------这个是INDEX。HTML结束-------------------------------------------*/


		/**
         *  得到静态资料 
         */
		$content = $tpl_HTML->fetch("index.html");
		$content = preg_replace($patterns,$replacements , $content);
		$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/index.html", $content);
		if ($resultHtml){
			echo "HTML_C/index.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/index.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";
		}
	}
}


?>
