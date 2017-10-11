<?php
class CreateArticleClassHtml {
	var $Sql;
	var $ncid;
	var $perPageNum;
	var $PageNav;
	var $HtmlUrl ;
	var $pageNavNum;
	var $totalPageNum;



	function LoopCreateHtml(){
		for ($autoNum=0;$autoNum<$this->totalPageNum;$autoNum++){
			$this->CreateArticleClassHtml_Action($this->Sql,$autoNum,intval($this->pageNavNum),intval($this->perPageNum));
		}

	}

	function InitCreate($ncid){
		global $FUNCTIONS,$INFO,$DB;
		$this->ncid = $ncid;

		include_once (RootDocumentAdmin."/inc/PageNav.class.php");
		$this->Sql        = $Sql =  "select * from `{$INFO[DBPrefix]}news` n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where  nc.ncatiffb=1 and n.niffb=1 and nc.ncid='".$this->ncid."' order by n.nidate desc ";
		$this->perPageNum         =  10;
		$this->PageNav            =  new PageItem($this->Sql,$this->perPageNum);
		$this->PageNav->HtmlUrl   = "HTML_C/article_list_{$this->ncid}";
		$this->pageNavNum         = $this->PageNav->iTotal;
		$this->totalPageNum       = ceil($this->pageNavNum/$this->perPageNum);
		$this->LoopCreateHtml();
	}

	function CreateArticleClassHtml_Action($Sql,$autoNum,$pageNavNum,$perPageNum){
		global $DB,$tpl_HTML,$INFO,$Html_Smarty,$FUNCTIONS,$PageNavClass_Pack,$PageNav,$Good,$StaticHtml_Pack,$Article_Pack;
		global $templates,$doc_root,$OtherPach;

		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();

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
			while ( $NewNav = $DB->fetch_array($arrRecords)){
				$Nltitle        =  $NewNav['nltitle'];
				$Nltitle_first  =  $NewNav['nltitle_color']!="" ? "<font color=".$NewNav['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
				$Nltitle_s      =  $NewNav['url_on']==0 ? "<a href=\"".$INFO['site_url']."/HTML_C/article_".intval($NewNav[news_id]).".html\">".$Nltitle_first."</a>" :  "<a href='".$NewNav['url']."' target='_blank'>".$Nltitle_first."</a>";
				$AllNltitle[$i] = $Nltitle_s;
				$AllNltime[$i]  = $NewNav['nidate'];
				$NcatName       = $Ncid >0 ?  $NewNav['ncname'] : "" ;

				$tpl_HTML->assign("article_title".$j, $AllNltitle[$i]);
				$tpl_HTML->assign("ntitle".$j, $AllNltitle[$i]);
				$tpl_HTML->assign("nidate".$j, date("Y-m-d",$AllNltime[$i]));

				$i++;
				$j++;
			}
			unset($i);
			unset($j);

			$PageNavmyPageItem  = "<a href=\"article_list_{$this->ncid}_0.html\">".$PageNavClass_Pack['First_Page']."</a> ";
			if ($autoNum>0){
				$PageNavmyPageItem .= "<a href=\"article_list_{$this->ncid}_".($autoNum-1).".html\">".$PageNavClass_Pack['Pre_Page']."</a> ";
			}

			if ($autoNum<$this->totalPageNum-1){
				$PageNavmyPageItem .= "<a href=\"article_list_{$this->ncid}_".($autoNum+1).".html\">".$PageNavClass_Pack['Next_Page']."</a> ";
			}
			$PageNavmyPageItem .= "<a href=\"article_list_{$this->ncid}_".($this->totalPageNum-1).".html\">".$PageNavClass_Pack['Last_Page']."</a> ";
			$PageNavmyPageItem .= $this->SelectItem($autoNum);
			$PageNavmyPageItem .= " ".$PageNavClass_Pack['TheNo'].($autoNum+1).$PageNavClass_Pack['PageAndTol'].$this->totalPageNum.$PageNavClass_Pack['Page'] ;

			$tpl_HTML->assign("ArticlePageItem",       $PageNavmyPageItem);     //商品翻页条


		}


		$tpl_HTML->assign("NcatName",       $NcatName);
		$tpl_HTML->assign($Article_Pack);

		/**
         *  得到静态资料
         */
		$Htmlcontent = $tpl_HTML->fetch("article_index.html");

		$Htmlcontent = preg_replace($patterns,$replacements , $Htmlcontent);
		$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/article_list_{$this->ncid}_{$autoNum}.html", $Htmlcontent);
		if ($resultHtml){
			echo "HTML_C/article_list_{$this->ncid}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/article_list_{$this->ncid}_{$autoNum}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";
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
			$SelectItem .= "<OPTION VALUE='article_list_".$this->ncid."_".$j.".html' $extra>".$i."</OPTION>";
		}
		$SelectItem .= "</SELECT>\n";
		$SelectItem .= $PageNavClass_Pack['Page']."\n";
		
		return $SelectItem;
	}
}


?>
