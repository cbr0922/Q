<?php
class CreateBrandIndex {
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
			$this->CreatePageForBrandIndex($autoNum,intval($this->pageNavNum),intval($this->perPageNum));
		}

	}

	function InitCreate($classType){
		global $FUNCTIONS,$INFO,$DB,$Good;

		$this->classType = trim($classType);

		switch (trim($this->classType)){
			case "Brand":
				$this->Sql = "select b.brand_id ,b.brandname,b.brandcontent,b.logopic from `{$INFO[DBPrefix]}brand` b    order by b.brand_id  desc ";
				break;
			default:
				echo '0';
				return true;
				break;

		}

		include_once (RootDocumentAdmin."/inc/PageNav.class.php");

		$this->perPageNum         =  10 ;
		$this->PageNav            =  new PageItem($this->Sql,$this->perPageNum);
		$this->PageNav->HtmlUrl   = "HTML_C/brand_index_";
		$this->pageNavNum         = $this->PageNav->iTotal;
		if ($this->pageNavNum<=0){
			$this->NoData();
			return true;
		}else{
			$this->totalPageNum       = ceil($this->pageNavNum/$this->perPageNum);
			$this->LoopCreateHtml();
		}

	}


	function CreatePageForBrandIndex($autoNum,$pageNavNum,$perPageNum){
		global $DB,$tpl_HTML,$INFO,$Html_Smarty,$FUNCTIONS,$PageNavClass_Pack,$PageNav,$Good,$StaticHtml_Pack,$Brand_Pack;
		global $templates,$doc_root,$OtherPach;

		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();

		$tpl_HTML->assign($Brand_Pack);

		if ($this->pageNavNum>0){
			$_Sql = "";
			$StartPage = intval($autoNum)<=0 ? 0 : $autoNum*$perPageNum+1 ;
			$EndPage   = intval($autoNum)<=0 ? $perPageNum : ($autoNum+1)*$perPageNum ;
			$_Sql      = $this->Sql." limit ".$StartPage.",".$EndPage;
			$arrRecords = $DB->query($_Sql);

			$i=0;
			$j=1;
			while ( $ResultBrand = $DB->fetch_array($arrRecords) ){
				$tpl_HTML->assign("brand_id".$j,      intval($ResultBrand['brand_id']));
				$tpl_HTML->assign("brandname".$j,     trim($ResultBrand['brandname']));
				$tpl_HTML->assign("brandcontent".$j,  $ResultBrand['brandcontent']);
				$tpl_HTML->assign("logopic".$j,       $ResultBrand['logopic']);

				$i++;
				$j++;
			}

		}




		$PageNavmyPageItem  = "<a href=\"brand_index_0.html\">".$PageNavClass_Pack['First_Page']."</a> ";
		if ($autoNum>0){
			$PageNavmyPageItem .= "<a href=\"brand_index_".($autoNum-1).".html\">".$PageNavClass_Pack['Pre_Page']."</a> ";
		}

		if ($autoNum<$this->totalPageNum-1){
			$PageNavmyPageItem .= "<a href=\"brand_index_".($autoNum+1).".html\">".$PageNavClass_Pack['Next_Page']."</a> ";
		}
		$PageNavmyPageItem .= "<a href=\"brand_index_".($this->totalPageNum-1).".html\">".$PageNavClass_Pack['Last_Page']."</a> ";
		$PageNavmyPageItem .= $this->SelectItem($autoNum);
		$PageNavmyPageItem .= " ".$PageNavClass_Pack['TheNo'].($autoNum+1).$PageNavClass_Pack['PageAndTol'].$this->totalPageNum.$PageNavClass_Pack['Page'] ;

		$tpl_HTML->assign("BrandPageItem",         $PageNavmyPageItem);     //商品翻页条

		/**
         *  得到静态资料
         */
		$content = "";
		$content = $tpl_HTML->fetch("brand_index.html");

		$content = preg_replace($patterns,$replacements , $content);
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/brand_index_{$autoNum}.html", $content);
		if ($resultHtml){
			echo "HTML_C/brand_index_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/brand_index_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";

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
			$SelectItem .= "<OPTION VALUE='brand_index_".$j.".html' $extra>".$i."</OPTION>";
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
		$content = $tpl_HTML->fetch("brand_index.html");
		$content = preg_replace($patterns,$replacements , $content);				
		
		
		
		
		$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/brand_index_0.html", $content);
		if ($resultHtml){
			echo "HTML_C/brand_index_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/brand_index_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";
		}
	}
}

?>
