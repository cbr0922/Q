<?php
class CreateArticleHtml {
	var $Sql;
	var $news_id;

	function CreateArticleHtml_Action($news_id){
		global $DB,$FUNCTIONS,$INFO,$Html_Smarty,$tpl_HTML,$StaticHtml_Pack,$Bottom_Pack,$Contact_Pack,$Article_Pack;
		global $Good,$templates,$doc_root,$OtherPach;

		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();

		$this->news_id =  $news_id;
		$this->Sql = "select nc.ncid,nc.ncname,n.ntitle,n.nbody,n.ntitle_color,n.nimg,n.nidate from `{$INFO[DBPrefix]}news` n inner join  `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where   nc.ncatiffb=1 && n.niffb=1 && n.news_id='".intval($this->news_id)."' limit 0,1";
		$Query     = $DB->query($this->Sql);
		$Num       = $DB->num_rows($Query);
		if ($Num>0){
			$Result_Article = $DB->fetch_array($Query);
			$Ncateid        = $Result_Article['ncid'];
			$Nclass_name    = $Result_Article['ncname'];
			$Ntitle         = $Result_Article['ntitle'];
			$Nbody          = $Result_Article['nbody'];
			$Ntitle_first   = $Result_Article['ntitle_color']!="" ? "<font color=".$Result_news['ntitle_color'].">".$Ntitle."</font>" : $Ntitle ;
			//$Img            = $Result_Article['nimg']!="" ?  "<img src=".$Result_Article['nimg']."><br><br>" : "<br><br>";
			$Img            = trim($Result_Article['nimg'])!="" ?  $FUNCTIONS->ImgTypeReturn(RootDocument."/newspic",trim($Result_Article['nimg']),'','')   : "";
			$Idate          = date("Y-m-d",$Result_Article['nidate']);

			$Queryall   = $DB->query("select n.news_id,nc.ncid,nc.ncname,n.ntitle,n.nbody,n.ntitle_color,n.nimg,n.nidate from `{$INFO[DBPrefix]}news`  n inner join `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid ) where   nc.ncatiffb=1 && n.niffb=1 && n.top_id='".$Ncateid."'  order by n.nidate desc limit 0,9");
			$Numall     = $DB->num_rows($Queryall);
			$i=0;
			while ($Rsall = $DB->fetch_array($Queryall)){
				$Ncatearray[$i]['titleid'] = $Rsall['news_id'];
				$Ncatearray[$i]['title']   = $Rsall['ntitle_color']!="" ? "<font color=".$Rsall['ntitle_color'].">".$Rsall['ntitle']."</font>" : $Rsall['ntitle'];
				$i++;
			}
			$tpl_HTML->assign("Ncatearray",      $Ncatearray);         //新闻大类数组
		}

		$tpl_HTML->assign("Ncateid",      $Ncateid);         //新闻大类ID
		$tpl_HTML->assign("Nclass_name",  $Nclass_name);     //新闻大类名称
		$tpl_HTML->assign("Ntitle",       $Ntitle);          //新闻标题
		$tpl_HTML->assign("Nbody",        $Nbody);           //新闻内容
		$tpl_HTML->assign("Ntitle_first", $Ntitle_first);    //新闻标题，带颜色控制的！
		$tpl_HTML->assign("Img",          $Img);             //新闻图片
		$tpl_HTML->assign("Idate",        $Idate);           //新闻日期
		$tpl_HTML->assign("Articleid",    intval($this->news_id));

		$tpl_HTML->assign("Back_say",     $Article_Pack[Back_say]);            //返回
		$tpl_HTML->assign($Article_Pack);




		/**
         *  得到静态资料 
         */
		$Htmlcontent = $tpl_HTML->fetch("article.html");

		$Htmlcontent = preg_replace($patterns,$replacements , $Htmlcontent);
		$Html_Smarty = new Html_Smarty;
		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/article_{$this->news_id}.html", $Htmlcontent);
		if ($resultHtml){
			echo "HTML_C/article_{$this->news_id}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/article_{$this->news_id}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";
		}
	}
}


?>
