<?php
class CreateProductSubjectClassType {
	var $Subject_id;
	var $Sql;
	var $perPageNum;
	var $PageNav;
	var $HtmlUrl ;
	var $pageNavNum;
	var $totalPageNum;
	var $Nav_Product_class;


	function LoopCreateHtml(){

		for ($autoNum=0;$autoNum<$this->totalPageNum;$autoNum++){
			$this->CreatePageForProductSujectClass($this->Sql,$autoNum,intval($this->pageNavNum),intval($this->perPageNum));
		}

	}

	function InitCreate($Subject_id){

		global $FUNCTIONS,$INFO,$DB,$Good;
		$this->Subject_id = $Subject_id;
		$this->Sql     = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,s.subject_name,s.subject_content from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid ) inner join `{$INFO[DBPrefix]}subject` s on (g.subject_id=s.subject_id)  where s.subject_open='1' and  b.catiffb='1' and g.ifpub='1' and s.subject_id='".$this->Subject_id."'  order by g.goodorder asc,g.idate desc ";
		/**
		 *  这里必须是include_once否则会不能正常生成多类资料
		 */
		include_once (RootDocumentAdmin."/inc/PageNav.class.php");

		$this->perPageNum         =  $INFO['MaxProductNumForList']!="" ?  $INFO['MaxProductNumForList'] : 10 ;
		$this->PageNav            =  new PageItem($this->Sql,$this->perPageNum);
		$this->PageNav->HtmlUrl   = "HTML_C/subject_product_{$this->Subject_id}";
		$this->pageNavNum         = $this->PageNav->iTotal;
		if ($this->pageNavNum<=0){
			$this->NoData();
			return true;
		}else{
			$this->totalPageNum       = ceil($this->pageNavNum/$this->perPageNum);
			$this->LoopCreateHtml();
		}
	}



	function CreatePageForProductSujectClass($Sql,$autoNum,$pageNavNum,$perPageNum){
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
			$ProNav = array();
			while ( $ProNav = $DB->fetch_array($arrRecords)){
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
				$tpl_HTML->assign("ProNav_array_level".$j, $ProNav_array_level);       //会员价格数组

				$tpl_HTML->assign("ProNav_gid".$j,  intval($ProNav['gid'])); //商品ID

				$tpl_HTML->assign("ProNav_goodsname".$j,   $ProNav['goodsname']); //商品名称
				$tpl_HTML->assign("ProNav_price".$j,       $ProNav['price']);     //商品价格
				$tpl_HTML->assign("ProNav_pricedesc".$j,   $ProNav['pricedesc']); //商品价格
				$tpl_HTML->assign("ProNav_bn".$j,          $ProNav['bn']);        //商品编号
				$tpl_HTML->assign("ProNav_img".$j,         $ProNav['smallimg']);  //商品图片
				$tpl_HTML->assign("ProNav_intro".$j,       $ProNav['intro']);     //商品内容
				$tpl_HTML->assign("Subject_name",          $ProNav['subject_name']);              //主题名字
				$tpl_HTML->assign("Subject_content",       $ProNav['subject_content']);           //主题内容

				if ($ProNav['attr']!=""){
					$attrI        =  $ProNav['attr'];
					$goods_attrI  =  $ProNav['goodattr'];
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
						$tpl_HTML->assign("ProductAttrArray_".$j."_attriName_".$k,     $Attr[$k]);    //循环多属性部分数组
						$tpl_HTML->assign("ProductAttrArray_".$j."_attriValue_".$k,    $Goods_Attr[$k]);    //循环多属性部分数组
					}
				}
				$i++;
				$j++;
			}
		}
		unset($i);
		unset($j);


		$PageNavmyPageItem  = "<a href=\"subject_product_{$this->Subject_id}_0.html\">".$PageNavClass_Pack['First_Page']."</a> ";
		if ($autoNum>0){
			$PageNavmyPageItem .= "<a href=\"subject_product_{$this->Subject_id}_".($autoNum-1).".html\">".$PageNavClass_Pack['Pre_Page']."</a> ";
		}

		if ($autoNum<$this->totalPageNum-1){
			$PageNavmyPageItem .= "<a href=\"subject_product_{$this->Subject_id}_".($autoNum+1).".html\">".$PageNavClass_Pack['Next_Page']."</a> ";
		}
		$PageNavmyPageItem .= "<a href=\"subject_product_{$this->Subject_id}_".($this->totalPageNum-1).".html\">".$PageNavClass_Pack['Last_Page']."</a> ";
		$PageNavmyPageItem .= $this->SelectItem($autoNum);
		$PageNavmyPageItem .= " ".$PageNavClass_Pack['TheNo'].($autoNum+1).$PageNavClass_Pack['PageAndTol'].$this->totalPageNum.$PageNavClass_Pack['Page'] ;

		$tpl_HTML->assign("ProductPageItem",       $PageNavmyPageItem);     //商品翻页条
		$tpl_HTML->assign("Array_sub",       $this->AllSubjectClassName());           //主题类别名称循环

		/**
         *  得到静态资料 
         */
		$content = "";
		$content = $tpl_HTML->fetch("subject_index.html");
		$content = preg_replace($patterns,$replacements , $content);
		$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/subject_product_{$this->Subject_id}_{$autoNum}.html", $content);
		if ($resultHtml){
			echo "HTML_C/subject_product_{$this->Subject_id}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/subject_product_{$this->Subject_id}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";

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
			$SelectItem .= "<OPTION VALUE='subject_product_".$this->Subject_id."_".$j.".html' $extra>".$i."</OPTION>";
		}
		$SelectItem .= "</SELECT>\n";
		$SelectItem .= $PageNavClass_Pack['Page']."\n";
		return $SelectItem;
	}

	function AllSubjectClassName(){
		global $DB,$INFO;
		$Sql_sub   = " select subject_name,subject_id from `{$INFO[DBPrefix]}subject` where subject_open=1 order by subject_num desc ";
		$Query_sub = $DB->query($Sql_sub);
		$Array_sub = array();
		$sub_i = 0;
		while ($Rs_sub = $DB->fetch_array($Query_sub) ){
			$Array_sub[$sub_i][subject_id]    =  $Rs_sub['subject_id'];
			$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
			$sub_i++;

		}
		return  $Array_sub;
		//$tpl_HTML->assign("Array_sub",       $Array_sub);           //主题类别名称循环

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
		$content = $tpl_HTML->fetch("subject_index.html");
		$content = preg_replace($patterns,$replacements , $content);				
		
		$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/subject_product_{$this->Subject_id}_0.html", $content);
		if ($resultHtml){
			echo "HTML_C/subject_product_{$this->Subject_id}_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/subject_product_{$this->Subject_id}_0.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";
		}
	}

}
?>
