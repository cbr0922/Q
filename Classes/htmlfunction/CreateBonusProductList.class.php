<?php
class CreateBonusProductList {
	var $classType;
	var $Sql;
	var $perPageNum;
	var $PageNav;
	var $HtmlUrl ;
	var $pageNavNum;
	var $totalPageNum;
	var $Nav_Product_class;


	function LoopCreateHtml(){
		for ($autoNum=0;$autoNum<$this->totalPageNum;$autoNum++){
			$this->CreatePageForBounsProductList($autoNum,intval($this->pageNavNum),intval($this->perPageNum));
		}

	}

	function InitCreate($classType){
		global $FUNCTIONS,$INFO,$DB,$Good;
		$this->classType = trim($classType);

		switch (trim($this->classType)){
			case "Bonus":
				$this->Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.bonusnum,g.point from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid ) where  b.catiffb=1 and g.ifpub=1 and g.ifbonus=1 and g.ifjs!=1 order by g.goodorder asc,g.idate desc";
				break;
			default:
				echo '0';
				return true;
				break;

		}

		include_once (RootDocumentAdmin."/inc/PageNav.class.php");

		$this->perPageNum         =  $INFO['MaxProductNumForList']!="" ?  $INFO['MaxProductNumForList'] : 10 ;
		$this->PageNav            =  new PageItem($this->Sql,$this->perPageNum);
		$this->PageNav->HtmlUrl   = "HTML_C/bonus_product_list_";
		$this->pageNavNum         = $this->PageNav->iTotal;
		if ($this->pageNavNum<=0){
			$this->NoData();
			return true;
		}else{
			$this->totalPageNum       = ceil($this->pageNavNum/$this->perPageNum);
			$this->LoopCreateHtml();
		}

	}


	function CreatePageForBounsProductList($autoNum,$pageNavNum,$perPageNum){
		global $DB,$tpl_HTML,$INFO,$Html_Smarty,$FUNCTIONS,$PageNavClass_Pack,$PageNav,$Good,$StaticHtml_Pack;
		global $templates,$doc_root,$OtherPach;

		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();


		if ($this->pageNavNum>0){
			$_Sql = "";
			$StartPage = intval($autoNum)<=0 ? 0 : $autoNum*$perPageNum+1 ;
			$EndPage   = intval($autoNum)<=0 ? $perPageNum : ($autoNum+1)*$perPageNum ;
			$_Sql      = $this->Sql." limit ".$StartPage.",".$EndPage;
			$arrRecords = $DB->query($_Sql);
			$i=0;
			$j=1;
			$ProNav = array();
			while ( $ProNav = $DB->fetch_array($arrRecords)){
				$ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;
				$ProNav_Rs[$i]['goodsname']  = trim($ProNav['goodsname'])."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']);
				$ProNav_Rs[$i]['price']      = $ProNav['price'] ;
				$ProNav_Rs[$i]['pricedesc']  = $ProNav['pricedesc'] ;
				$ProNav_Rs[$i]['bonusnum']   = $ProNav['bonusnum'];
				$ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
				$ProNav_Rs[$i]['smallimg']   = trim($ProNav['smallimg']) ;
				$ProNav_Rs[$i]['intro']      = nl2br($ProNav['intro']);
				$ProNav_Rs[$i]['point']      = $ProNav['point'];



				$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".intval($ProNav['gid']);
				$Query_level = $DB->query($Sql_level);
				$k=0;
				$ProNav_array_level = array();
				while ($Result_level=$DB->fetch_array($Query_level)){
					if (intval($Result_level['m_price'])!=0){
						$ProNav_array_level[$k]['level_name'] = $Result_level['level_name'];
						$ProNav_array_level[$k]['m_price']    = $Result_level['m_price'];
						$k++;
					}
				}

				$tpl_HTML->assign("ProNav_gid".$j,         intval($ProNav['gid']));
				$tpl_HTML->assign("ProNav_array_level".$j, $ProNav_array_level);       //商品会员价格数组
				$tpl_HTML->assign("ProNav_goodsname".$j,   trim($ProNav['goodsname'])."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']))."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum']); //商品名称
				$tpl_HTML->assign("ProNav_price".$j,       $ProNav['price']);     //商品价格
				$tpl_HTML->assign("ProNav_pricedesc".$j,   $ProNav['pricedesc']); //商品价格
				$tpl_HTML->assign("ProNav_bn".$j,          $ProNav['bn']);        //商品编号
				$tpl_HTML->assign("ProNav_img".$j,         trim($ProNav['smallimg']));  //商品图片
				$tpl_HTML->assign("ProNav_intro".$j,       nl2br($ProNav['intro']));     //商品内容
				$tpl_HTML->assign("ProNav_bonusnum".$j,    $ProNav['bonusnum']);  //商品所需要积分
				$tpl_HTML->assign("ProNav_point".$j,       $ProNav['point']);     //商品积分
				
				$i++;
				$j++;
			}

		}




		$PageNavmyPageItem  = "<a href=\"bonus_product_list_0.html\">".$PageNavClass_Pack['First_Page']."</a> ";
		if ($autoNum>0){
			$PageNavmyPageItem .= "<a href=\"bonus_product_list_".($autoNum-1).".html\">".$PageNavClass_Pack['Pre_Page']."</a> ";
		}

		if ($autoNum<$this->totalPageNum-1){
			$PageNavmyPageItem .= "<a href=\"bonus_product_list_".($autoNum+1).".html\">".$PageNavClass_Pack['Next_Page']."</a> ";
		}
		$PageNavmyPageItem .= "<a href=\"bonus_product_list_".($this->totalPageNum-1).".html\">".$PageNavClass_Pack['Last_Page']."</a> ";
		$PageNavmyPageItem .= $this->SelectItem($autoNum);
		$PageNavmyPageItem .= " ".$PageNavClass_Pack['TheNo'].($autoNum+1).$PageNavClass_Pack['PageAndTol'].$this->totalPageNum.$PageNavClass_Pack['Page'] ;

		$tpl_HTML->assign("ProductPageItem",       $PageNavmyPageItem);     //商品翻页条
		$tpl_HTML->assign("Nav_Product_class",     $this->Nav_Product_class ); //分类名称
		/**
         *  得到静态资料
         */
		$content = "";
		$content = $tpl_HTML->fetch("bonus_index.html");

		$content = preg_replace($patterns,$replacements , $content);
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/bonus_product_list_{$autoNum}.html", $content);
		if ($resultHtml){
			echo "HTML_C/bonus_product_list_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/bonus_product_list_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";

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
			$SelectItem .= "<OPTION VALUE='bonus_product_list_".$j.".html' $extra>".$i."</OPTION>";
		}
		$SelectItem .= "</SELECT>\n";
		$SelectItem .= $PageNavClass_Pack['Page']."\n";
		return $SelectItem;
	}
	function NoData(){
		global $DB,$tpl_HTML,$INFO,$Html_Smarty,$FUNCTIONS,$StaticHtml_Pack;
		global $templates,$doc_root,$OtherPach;
		
		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();

	
		$tpl_HTML->assign("ProductPageItem",       $StaticHtml_Pack[NodataPleaseBack]);     

		/**
         *  得到静态资料 
         */
		$content = "";
		$content = $tpl_HTML->fetch("bonus_index.html");
		$content = preg_replace($patterns,$replacements , $content);				
		
		
		$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/bonus_product_list_0.html", $content);
		if ($resultHtml){
			echo "HTML_C/bonus_product_list_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/bonus_product_list_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";
		}
	}
}

?>
