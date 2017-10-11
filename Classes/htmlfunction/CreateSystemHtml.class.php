<?php
class CreateSystemHtml {
	var $Sql;
	var $info_id;

	function CreateSystemHtml_Action($info_id){
		global $DB,$FUNCTIONS,$INFO,$Html_Smarty,$tpl_HTML,$StaticHtml_Pack,$Bottom_Pack,$Contact_Pack;
		global $Good,$templates,$doc_root,$OtherPach;

		//清除所有已经存在的页面变量
		$tpl_HTML->clear_all_assign();
		include "../language/".$INFO['IS']."/Contact_Pack.php";
		include "../language/".$INFO['IS']."/Bottom_Pack.php";
		$tpl_HTML->assign($Contact_Pack);
		$tpl_HTML->assign($Bottom_Pack);

		$this->info_id =  $info_id;
		$this->Sql = "select info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=".$this->info_id." limit 0,1";
		$Query     = $DB->query($this->Sql);
		$Num       = $DB->num_rows($Query);

		if ( $Num==0 ){ //如果不存在资料
			echo "0";
			return true;
		}

		if ($Num>0){
			$Result_Article = $DB->fetch_array($Query);
			$Content = $Result_Article['info_content'];
		}

		switch ($this->info_id){
			case 0:
				echo "0";
				return true;
				break;
			case 1:
				$Title =  $Bottom_Pack[Gywm];  //关于我们
				$TargetHtml = "Aboutour.html";
				break;
			case 2:
				$Title =  $Bottom_Pack[Cjwt]; //常见问题
				$TargetHtml = "Aboutour.html";
				break;
			case 3:
				$Title =  $Bottom_Pack[Aqjy]; //安全交易
				$TargetHtml = "Aboutour.html";
				break;
			case 4:
				$Title =  $Bottom_Pack[Gmlc]; //购买流程
				$TargetHtml = "Aboutour.html";
				break;
			case 5:
				$Title =  $Bottom_Pack[Rhfk]; //如何付款
				$TargetHtml = "Aboutour.html";
				break;
			case 6:
				$Title =  $Bottom_Pack[Lxwm]; //联系我们
				$TargetHtml = "Aboutour.html";
				break;
			case 7:
				$Title =  "";
				$TargetHtml = "Aboutour.html";
				break;
			case 8:
				$tpl_HTML->assign($Contact_Pack);
				$Title =  $Bottom_Pack[Tahz];//合作提案
				$TargetHtml = "contact.html";
				break;
			default:
				echo "0";
				break;
		}

		$tpl_HTML->assign("Title",          $Title);         //标题
		$tpl_HTML->assign("Content",        $Content);       //新闻内容

		/**
         *  得到静态资料 
         */
		$Htmlcontent = $tpl_HTML->fetch($TargetHtml);

		$Htmlcontent = preg_replace($patterns,$replacements , $Htmlcontent);
		if (!is_object($Html_Smarty)){
			$Html_Smarty = new Html_Smarty;
		}

		$resultHtml = $Html_Smarty->MakeHtmlFile(RootDocument."/HTML_C/help_{$this->info_id}.html", $Htmlcontent);
		if ($resultHtml){
			echo "HTML_C/help_{$this->info_id}.html \t\t        {$StaticHtml_Pack[CreateHtml_Sucuess]}\n";
		}else{
			echo "HTML_C/help_{$this->info_id}.html \t\t        {$StaticHtml_Pack[CreateHtml_Fail]}\n";
		}
	}
}


?>
