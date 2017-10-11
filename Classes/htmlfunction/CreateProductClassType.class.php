<?php
class CreateProductClassType {
	var $classType;
	var $Sql;
	var $perPageNum;
	var $PageNav;
	var $HtmlUrl ;
	var $pageNavNum;
	var $totalPageNum;
	var $Nav_Product_class;
	var $SysNum_NewProduct;


	function LoopCreateHtml(){
		for ($autoNum=0;$autoNum<$this->totalPageNum;$autoNum++){
			$this->CreatePageForProductClass($this->Sql,$this->bid,$autoNum,intval($this->pageNavNum),intval($this->perPageNum));
		}

	}

	function InitCreate($classType){
		global $FUNCTIONS,$INFO,$DB,$Good;
		$this->classType = trim($classType);
		
		switch (trim($this->classType)){
			case "NewProduct":
				$this->Nav_Product_class = $Good[Title_NewProduct_say];//"最新商品";
				$this->Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and  g.ifpub='1'  and g.ifbonus!='1'  and g.ifjs!=1  order by g.gid desc";
				$SysNum_NewProduct  = intval($INFO['MaxNewProductNum'])>0 ? intval($INFO['MaxNewProductNum']) : 10 ;
				break;
			case "Recommend":
				$this->Nav_Product_class = $Good[Title_TProduct_say];//"推荐商品";
				$this->Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' and g.ifrecommend='1'  and g.ifbonus!='1'   and g.ifjs!=1  order by g.goodorder asc,g.idate desc ";
				break;
			case "Special":
				$this->Nav_Product_class = $Good[Title_SpeProduct_say];//"特价商品";
				$this->Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro ,g.ifalarm,g.storage,g.alarmnum from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' and g.ifspecial='1'   and g.ifbonus!='1'  and g.ifjs!=1  order by g.goodorder asc,g.idate desc ";
				break;
			case "Hot":
				$this->Nav_Product_class = $Good[Title_HotProduct_say];//"热卖商品";
				$this->Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid )  where  b.catiffb='1' and g.ifhot='1'   and g.ifbonus!='1'   and g.ifjs!=1  order by g.goodorder asc,g.idate desc ";
				break;

			default:
				$this->classType = "NewProduct";
				$this->Nav_Product_class = $Good[Title_NewProduct_say];//"最新商品";
				$this->Sql = "select  g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where b.catiffb='1' and g.ifpub='1'  and g.ifbonus!='1'  and g.ifjs!=1  order by g.goodorder asc,g.idate desc";
				$SysNum_NewProduct  = intval($INFO['MaxNewProductNum'])>0 ? intval($INFO['MaxNewProductNum']) : 10 ;
				break;

		}

		/**
		 * 装载翻页类，注意使用include_once函数
		 */
		include_once (RootDocumentAdmin."/inc/PageNav.class.php");
		//$this->perPageNum         =  intval($INFO['MaxProductNumForList'])>0 ?  intval($INFO['MaxProductNumForList']) : 10 ;
		//这里如果是最新产品。将生成2个值用于限制翻页函数。

		if ($this->classType=="NewProduct"){
			$this->perPageNum  = $Limit     = $SysNum_NewProduct ;
			$pType             = "NewProduct";
		}else{
			$this->perPageNum  =  intval($INFO['MaxProductNumForList'])>0 ?  intval($INFO['MaxProductNumForList']) : 10 ;
			unset($Limit);
			unset($SysNum_NewProduct);
			unset($pType );
		}

		$this->PageNav            =  new PageItem($this->Sql,$this->perPageNum);
		$this->PageNav->HtmlUrl   = "HTML_C/product_list_{$this->classType}";

		if ($this->classType=="NewProduct"){
			$this->pageNavNum   =  $SysNum_NewProduct ;
		}else{
			$this->pageNavNum   = $this->PageNav->iTotal;
		}


		$this->totalPageNum       = ceil($this->pageNavNum/$this->perPageNum);
		$this->LoopCreateHtml();
	}


	function CreatePageForProductClass($Sql,$bid,$autoNum,$pageNavNum,$perPageNum){
		global $DB,$tpl_HTML,$INFO,$Html_Smarty,$FUNCTIONS,$PageNavClass_Pack,$PageNav,$Good,$StaticHtml_Pack;
		global $templates,$doc_root,$OtherPach;

		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();


		if ($this->pageNavNum>0){
			$_Sql = "";
			$StartPage = intval($autoNum)<=0 ? 0 : $autoNum*$perPageNum+1 ;
			$EndPage   = intval($autoNum)<=0 ? $perPageNum : ($autoNum+1)*$perPageNum ;
			$_Sql = $Sql." limit ".$StartPage.",".$EndPage;
			$arrRecords = $DB->query($_Sql);
			$i=0;
			$j=1;
			$Sql_level = "";
			$ProNav = array();
			$ProNav_array_level = array();


			while ( $ProNav = $DB->fetch_array($arrRecords)){


				$ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;

				$Sql_level   = "select m.m_price,u.level_name,m.m_price from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[$i]['gid'];
				$Query_level = $DB->query($Sql_level);
				$v=0;
				while ($Result_level=$DB->fetch_array($Query_level)){
					if (intval($Result_level['m_price'])!=0){
						$ProNav_array_level[$v]['level_name'] = $Result_level['level_name'];
						$ProNav_array_level[$v]['m_price']    = $Result_level['m_price'];
						$v++;
					}
				}


				$tpl_HTML->assign("ProNav_array_level".$j, $ProNav_array_level);       //商品会员价格数组
				$ProNav_Rs[$i]['goodsname']  = $ProNav['goodsname']."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']);
				$ProNav_Rs[$i]['price']      = $ProNav['price'] ;
				$ProNav_Rs[$i]['pricedesc']  = $ProNav['pricedesc'] ;
				$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
				$ProNav_Rs[$i]['smallimg']   = $ProNav['smallimg'] ;
				$ProNav_Rs[$i]['intro']      = $ProNav['intro'];

				$tpl_HTML->assign("ProNav_gid".$j,         $ProNav_Rs[$i]['gid']);
				$tpl_HTML->assign("ProNav_goodsname".$j,   $ProNav_Rs[$i]['goodsname']);
				$tpl_HTML->assign("ProNav_price".$j,       $ProNav_Rs[$i]['price']);
				$tpl_HTML->assign("ProNav_pricedesc".$j,   $ProNav_Rs[$i]['pricedesc']);
				$tpl_HTML->assign("ProNav_bn".$j,          $ProNav_Rs[$i]['bn']);
				$tpl_HTML->assign("ProNav_img".$j,         $ProNav_Rs[$i]['smallimg']);
				$tpl_HTML->assign("ProNav_intro".$j,       $ProNav_Rs[$i]['intro']);


				if ($ProNav_Rs[$i]['attr']!=""){
					$attrI        =  $ProNav_Rs[$i]['attr'];
					$goods_attrI  =  $ProNav_Rs[$i]['goodattr'];
					$Attr         =  explode(',',$attrI);
					$Goods_Attr   =  explode(',',$goods_attrI);
					$Attr_num=  count($Attr);
				}else{
					$Attr_num=0;
				}
				if (is_array($Attr) && intval($Attr_num)>0 ){
					$AttrArray = array();
					$ProductArray = array();
					for($k=0;$k<$Attr_num;$k++){
						$tpl_HTML->assign("ProductAttrArray".$j."_attriName_".$k,     $Attr[$k]);    //循环多属性部分数组
						$tpl_HTML->assign("ProductAttrArray".$j."_attriValue_".$k,    $Goods_Attr[$k]);    //循环多属性部分数组
					}
				}

				$i++;
				$j++;
			}
		}
		unset($i);
		unset($j);



		$PageNavmyPageItem  = "<a href=\"product_list_{$this->classType}_0.html\">".$PageNavClass_Pack['First_Page']."</a> ";
		if ($autoNum>0){
			$PageNavmyPageItem .= "<a href=\"product_list_{$this->classType}_".($autoNum-1).".html\">".$PageNavClass_Pack['Pre_Page']."</a> ";
		}

		if ($autoNum<$this->totalPageNum-1){
			$PageNavmyPageItem .= "<a href=\"product_list_{$this->classType}_".($autoNum+1).".html\">".$PageNavClass_Pack['Next_Page']."</a> ";
		}
		$PageNavmyPageItem .= "<a href=\"product_list_{$this->classType}_".($this->totalPageNum-1).".html\">".$PageNavClass_Pack['Last_Page']."</a> ";
		$PageNavmyPageItem .= $this->SelectItem($autoNum);
		$PageNavmyPageItem .= " ".$PageNavClass_Pack['TheNo'].($autoNum+1).$PageNavClass_Pack['PageAndTol'].$this->totalPageNum.$PageNavClass_Pack['Page'] ;

		$tpl_HTML->assign("ProductPageItem",       $PageNavmyPageItem);     //商品翻页条
		$tpl_HTML->assign("Nav_Product_class",     $this->Nav_Product_class ); //分类名称
		/**
         *  得到静态资料 
         */
		$content = "";
		$content = $tpl_HTML->fetch("product_list_detail.html");
		$content = preg_replace($patterns,$replacements , $content);
		$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/product_list_{$this->classType}_{$autoNum}.html", $content);
		if ($resultHtml){
			echo "HTML_C/product_list_{$this->classType}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/product_list_{$this->classType}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";

		}

	}
	function SelectItem($autoNum)
	{
		global $PageNavClass_Pack;
		$SelectItem = "\n";
		$SelectItem .= $PageNavClass_Pack['TurnTo']."\n<SELECT name='topage' size='1' onchange='window.location=this.value'>\n";
		for($i = 1,$j=0;$i <= $this->totalPageNum;$i++,$j++) {
			if ($autoNum == $j){
				$extra = "selected";
			}else{
				$extra = "";
			}
			$SelectItem .= "<OPTION VALUE='product_list_".$this->classType."_".$j.".html' $extra>".$i."</OPTION>";
		}
		$SelectItem .= "</SELECT>\n";
		$SelectItem .= $PageNavClass_Pack['Page']."\n";
		return $SelectItem;
	}
}


?>
