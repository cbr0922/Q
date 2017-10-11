<?php
class CreateBrandProductList {
	var $brandid;
	var $Sql;
	var $perPageNum;
	var $PageNav;
	var $HtmlUrl ;
	var $pageNavNum;
	var $totalPageNum;


	function LoopCreateHtml(){

		for ($autoNum=0;$autoNum<$this->totalPageNum;$autoNum++){
			$this->CreatePageForBrandProductClass($this->Sql,$this->brandid,$autoNum,intval($this->pageNavNum),intval($this->perPageNum));
		}

	}

	function InitCreate($brandid){

		global $FUNCTIONS,$INFO,$DB,$StaticHtml_Pack,$tpl_HTML;
		$this->brandid = intval($brandid);
		$this->Add = "";

		include_once (RootDocumentAdmin."/inc/PageNav.class.php");


		$this->Sql = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum,br.brand_id,br.brandname,br.brandcontent,br.logopic from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass`  b on (g.bid=b.bid ) inner join   `{$INFO[DBPrefix]}brand`  br on (br.brand_id=g.brand_id)  where  b.catiffb=1 and g.ifpub=1 and g.ifjs!=1 and g.ifbonus!=1  and br.brand_id='".intval($this->brandid)."' order by g.goodorder asc,g.idate desc ";


		$this->perPageNum         =  $INFO['MaxProductNumForList']!="" ?  $INFO['MaxProductNumForList'] : 10 ;
		$this->PageNav            =  new PageItem($this->Sql,$this->perPageNum);
		$this->PageNav->HtmlUrl   = "HTML_C/brand_list_{$this->brandid}";
		$this->pageNavNum         = $this->PageNav->iTotal;

		if ($this->pageNavNum<=0){
			$this->NoData();
			return true;
		}else{
			$this->totalPageNum       = ceil($this->pageNavNum/$this->perPageNum);
			$this->LoopCreateHtml();
		}
	}


	function CreatePageForBrandProductClass($Sql,$bid,$autoNum,$pageNavNum,$perPageNum){
		global $DB,$tpl_HTML,$INFO,$Html_Smarty,$FUNCTIONS,$PageNavClass_Pack,$PageNav,$Good,$StaticHtml_Pack;
		global $templates,$doc_root,$OtherPach;

		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();

		if ($pageNavNum>0){

			$_Sql = "";
			$StartPage = intval($autoNum)<=0 ? 0 : $autoNum*$perPageNum+1 ;
			$EndPage   = intval($autoNum)<=0 ? $perPageNum : ($autoNum+1)*$perPageNum ;
			$_Sql = $this->Sql." limit ".$StartPage.",".$EndPage;
			$arrRecords = $DB->query($_Sql);

			$i=0;
			$j=1;
			$ProNav = array();
			while ( $ProNav = $DB->fetch_array($arrRecords)){
				$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".intval($ProNav['gid']);
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
				$tpl_HTML->assign("ProNav_gid".$j,  intval($ProNav['gid'])); //最新商品ID
				$tpl_HTML->assign("ProNav_goodsname".$j,   trim($ProNav['goodsname'])."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum'])); //商品名称
				$tpl_HTML->assign("ProNav_price".$j,       $ProNav['price']);     //商品价格
				$tpl_HTML->assign("ProNav_pricedesc".$j,   $ProNav['pricedesc']); //商品价格
				$tpl_HTML->assign("ProNav_bn".$j,          $ProNav['bn']);        //商品编号
				$tpl_HTML->assign("ProNav_img".$j,         trim($ProNav['smallimg']));  //商品图片
				$tpl_HTML->assign("ProNav_intro".$j,       nl2br($ProNav['intro']));     //商品内容


	            $tpl_HTML->assign("The_brand_id",       $ProNav['brand_id']);
				$tpl_HTML->assign("The_brandname",      $ProNav['brandname']);
				$tpl_HTML->assign("The_brandcontent",   $ProNav['brandcontent']);
				$tpl_HTML->assign("The_logopic",        $ProNav['logopic']);



				$i++;
				$j++;
			}
		}
        unset($i);
        unset($j);
		//$tpl_HTML->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条

		$PageNavmyPageItem  = "<a href=\"brand_list_{$this->brandid}_0.html\">".$PageNavClass_Pack['First_Page']."</a> ";
		if ($autoNum>0){
			$PageNavmyPageItem .= "<a href=\"brand_list_{$this->brandid}_".($autoNum-1).".html\">".$PageNavClass_Pack['Pre_Page']."</a> ";
		}

		if ($autoNum<$this->totalPageNum-1){
			$PageNavmyPageItem .= "<a href=\"brand_list_{$this->brandid}_".($autoNum+1).".html\">".$PageNavClass_Pack['Next_Page']."</a> ";
		}

		$PageNavmyPageItem .= "<a href=\"brand_list_{$this->brandid}_".($this->totalPageNum-1).".html\">".$PageNavClass_Pack['Last_Page']."</a> ";
		$PageNavmyPageItem .= $this->SelectItem($autoNum);
		$PageNavmyPageItem .= " ".$PageNavClass_Pack['TheNo'].($autoNum+1).$PageNavClass_Pack['PageAndTol'].$this->totalPageNum.$PageNavClass_Pack['Page'] ;

		$tpl_HTML->assign("ProductPageItem",       $PageNavmyPageItem);     //商品翻页条

		/**
         *  得到静态资料
         */

		$content = "";

		// 清除指定模板资源的编译文件
		//$tpl_HTML->clear_compiled_tpl("product_class_detail.html");


		$content = $tpl_HTML->fetch("brand_product_list.html");

		$content = preg_replace($patterns,$replacements , $content);
		//$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/brand_list_{$this->brandid}_{$autoNum}.html", $content);

		if ($resultHtml){
			echo "HTML_C/brand_list_{$this->brandid}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/brand_list_{$this->brandid}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";

		}

		return 1;

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
			$SelectItem .= "<OPTION VALUE='brand_list_".$this->brandid."_".$j.".html' $extra>".$i."</OPTION>";
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

		$tpl_HTML->assign($Brand_Pack);
	
		$tpl_HTML->assign("ProductPageItem",       $StaticHtml_Pack[NodataPleaseBack]);     

		/**
         *  得到静态资料 
         */
		$content = "";
		$content = $tpl_HTML->fetch("brand_product_list.html");
		$content = preg_replace($patterns,$replacements , $content);				
		
		
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/brand_list_{$this->brandid}_0.html",$content);
		if ($resultHtml){
			echo "HTML_C/brand_list_{$this->brandid}_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/brand_list_{$this->brandid}_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";
		}
	}
}

?>
