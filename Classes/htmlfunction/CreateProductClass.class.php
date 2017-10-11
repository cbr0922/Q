<?php
class CreateProductClass {
	var $bid;
	var $Sql;
	var $perPageNum;
	var $PageNav;
	var $HtmlUrl ;
	var $pageNavNum;
	var $totalPageNum;


	function LoopCreateHtml(){

		for ($autoNum=0;$autoNum<$this->totalPageNum;$autoNum++){
			$this->CreatePageForProductClass($this->Sql,$this->bid,$autoNum,intval($this->pageNavNum),intval($this->perPageNum));
		}

	}

	function InitCreate($bid){

		global $FUNCTIONS,$INFO,$DB,$StaticHtml_Pack,$tpl_HTML;
		$this->bid  = $bid;
		$this->Add = "";

		$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($this->bid);

		if ($Next_ArrayClass){

			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$Array_class      = array_unique($Next_ArrayClass);
			foreach ($Array_class as $k=>$v){
				$this->Add .= $v!="" && intval($v)>0  ? " or g.bid=".$v." " : "";
			}
		}

		include_once (RootDocumentAdmin."/inc/PageNav.class.php");
		$this->Sql  = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum  from `{$INFO[DBPrefix]}bclass` b left join `{$INFO[DBPrefix]}goods` g  on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' and  g.ifjs!='1'  and g.ifbonus!='1'  and (g.bid='".intval($this->bid)."' ".$this->Add.")    order by g.goodorder asc,g.idate desc ";

		$this->perPageNum         =  $INFO['MaxProductNumForList']!="" ?  $INFO['MaxProductNumForList'] : 10 ;
		$this->PageNav            =  new PageItem($this->Sql,$this->perPageNum);
		$this->PageNav->HtmlUrl   = "HTML_C/product_class_{$this->bid}";
		$this->pageNavNum         = $this->PageNav->iTotal;

		if ($this->pageNavNum<=0){
			$this->NoData();
			return true;
		}else{
			$this->totalPageNum       = ceil($this->pageNavNum/$this->perPageNum);
			$this->LoopCreateHtml();
		}
	}


	function CreatePageForProductClass($Sql,$bid,$autoNum,$pageNavNum,$perPageNum){
		global $DB,$tpl_HTML,$INFO,$Html_Smarty,$FUNCTIONS,$PageNavClass_Pack,$PageNav,$Good,$StaticHtml_Pack;
		global $templates,$doc_root,$OtherPach;

		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();
		//获得大类资料
		$Query = $DB->query("select catname,bid,catcontent from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result     =  $DB->fetch_array($Query);
			$Bname      =  $Result['catname'];
			$Bcontent   =  $Result['catcontent'];
			$Bname_url  = "<a href=".$INFO[site_url]."/product/product_class_detail.php?bid=".intval($bid).">".$Bname."</a>";

			$tpl_HTML->assign("Bid",       trim($Result['bid']));     //产品大类ID
			$tpl_HTML->assign("Bname",     trim($Result['catname']));     //产品大类名称
			$tpl_HTML->assign("Bcontent",  trim($Result['catcontent']));  //产品HTML编辑器的内容

		}


		//轮播变量
		$Sql_top = "";
		$Sql_top = "select g.gid,g.goodsname,g.pricedesc,g.price,g.bn,g.smallimg,g.middleimg,g.intro,g.ifalarm,g.storage,g.alarmnum,b.bid,b.catname,b.catcontent,b.top_id as btop_id  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb=1 and g.ifpub=1 and g.ifrecommend=1 and g.ifjs!=1  and g.ifbonus!=1 and (g.bid='".intval($bid)."' ".$this->Add.")  order by g.gid desc  limit 0,5 ";
		$Query_top  = $DB->query($Sql_top);
		$i=0;
		$j=1;
		$Num_top = $DB->num_rows($Query_top);
		while ( $Rs_top = $DB->fetch_array($Query_top)){

			$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id='".intval($Rs_top['gid'])."'";
			$Query_level = $DB->query($Sql_level);
			$v=0;
			$ProTop_array_level = array();
			while ($Result_level=$DB->fetch_array($Query_level)){
				if (intval($Result_level['m_price'])!=0){
					$ProTop_array_level[$v]['level_name'] = $Result_level['level_name'];
					$ProTop_array_level[$v]['m_price']    = $Result_level['m_price'];
					$v++;
				}
			}

			$tpl_HTML->assign("ProTop_array_level".$j, $ProTop_array_level);       //商品会员价格数组
			$tpl_HTML->assign("ProTop_gid".$j,         intval($Rs_top['gid'])); //轮播商品ID
			$tpl_HTML->assign("ProTop_goodsname".$j,   trim($Rs_top['goodsname'])."".$FUNCTIONS->Storage($Rs_top['ifalarm'],$Rs_top['storage'],$Rs_top['alarmnum'])); //轮播名称
			$tpl_HTML->assign("ProTop_pricedesc".$j,   trim($Rs_top['pricedesc'])); //轮播特别优惠价格
			$tpl_HTML->assign("ProTop_price".$j,       $Rs_top['price']);     //轮播价格
			$tpl_HTML->assign("ProTop_bn".$j,          $Rs_top['bn']);        //轮播编号
			$tpl_HTML->assign("ProTop_img".$j,         trim($Rs_top['middleimg'])); //轮播图片
			$tpl_HTML->assign("ProTop_intro".$j,       nl2br($Rs_top['intro']));     //轮播内容

			$i++;
			$j++;

		}
		unset($i);
		unset($j);
		if ($pageNavNum>0){
			//$arrRecords = $PageNav->ReadList($Sql,$autoNum,$perPageNum);
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
				$tpl_HTML->assign("ProNav_gid".$j,         intval($ProNav['gid'])); //最新商品ID
				$tpl_HTML->assign("ProNav_goodsname".$j,   trim($ProNav['goodsname'])."".$FUNCTIONS->Storage($ProNav['ifalarm'],$ProNav['storage'],$ProNav['alarmnum'])); //商品名称
				$tpl_HTML->assign("ProNav_price".$j,       $ProNav['price']);     //商品价格
				$tpl_HTML->assign("ProNav_pricedesc".$j,   $ProNav['pricedesc']); //商品价格
				$tpl_HTML->assign("ProNav_bn".$j,          $ProNav['bn']);        //商品编号
				$tpl_HTML->assign("ProNav_img".$j,         trim($ProNav['smallimg']));  //商品图片
				$tpl_HTML->assign("ProNav_intro".$j,       nl2br($ProNav['intro']));     //商品内容

				$i++;
				$j++;
			}
		}
		unset($i);
		unset($j);
		//$tpl_HTML->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条

		$PageNavmyPageItem  = "<a href=\"product_class_{$this->bid}_0.html\">".$PageNavClass_Pack['First_Page']."</a> ";
		if ($autoNum>0){
			$PageNavmyPageItem .= "<a href=\"product_class_{$this->bid}_".($autoNum-1).".html\">".$PageNavClass_Pack['Pre_Page']."</a> ";
		}

		if ($autoNum<$this->totalPageNum-1){
			$PageNavmyPageItem .= "<a href=\"product_class_{$this->bid}_".($autoNum+1).".html\">".$PageNavClass_Pack['Next_Page']."</a> ";
		}
		$PageNavmyPageItem .= "<a href=\"product_class_{$this->bid}_".($this->totalPageNum-1).".html\">".$PageNavClass_Pack['Last_Page']."</a> ";
		$PageNavmyPageItem .= $this->SelectItem($autoNum);
		$PageNavmyPageItem .= " ".$PageNavClass_Pack['TheNo'].($autoNum+1).$PageNavClass_Pack['PageAndTol'].$this->totalPageNum.$PageNavClass_Pack['Page'] ;

		$tpl_HTML->assign("ProductPageItem",       $PageNavmyPageItem);     //商品翻页条

		/**
         *  得到静态资料 
         */

		$content = "";

		// 清除指定模板资源的编译文件
		//$tpl_HTML->clear_compiled_tpl("product_class_detail.html");


		$content = $tpl_HTML->fetch("product_class_detail.html");

		$content = preg_replace($patterns,$replacements , $content);
		//$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/product_class_{$this->bid}_{$autoNum}.html", $content);

		if ($resultHtml){
			echo "HTML_C/product_class_{$this->bid}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/product_class_{$this->bid}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";

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
			$SelectItem .= "<OPTION VALUE='product_class_".$this->bid."_".$j.".html' $extra>".$i."</OPTION>";
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

		//获得大类资料
		$Query = $DB->query("select catname,bid,catcontent from `{$INFO[DBPrefix]}bclass` where bid=".intval($this->bid)." limit 0,1 ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result     =  $DB->fetch_array($Query);
			$Bname      =  $Result['catname'];
			$Bcontent   =  $Result['catcontent'];
			$tpl_HTML->assign("Bid",       trim($Result['bid']));     //产品大类ID
			$tpl_HTML->assign("Bname",     trim($Result['catname']));     //产品大类名称
			$tpl_HTML->assign("Bcontent",  trim($Result['catcontent']));  //产品HTML编辑器的内容

		}		
		
		$tpl_HTML->assign("ProductPageItem",       $StaticHtml_Pack[NodataPleaseBack]);     

		/**
         *  得到静态资料 
         */
		$content = "";
		$content = $tpl_HTML->fetch("product_class_detail.html");
		$content = preg_replace($patterns,$replacements , $content);		

		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/product_class_{$this->bid}_0.html", $content);
		if ($resultHtml){
			echo "HTML_C/product_class_{$this->bid}_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/product_class_{$this->bid}_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";
		}
	}
}

?>
