<?php
/*
* 分页显示类
* PageNav.class.php  v 1.0.0
* 编程：大白菜芯<php_netproject@yahoo.com.cn>
* 更新：2004-02-02
* 说明：
* 1. 配合MYSQL数据库使用
* 2. 类没有提供连接数据库的功能，需在外部建立数据库连接。
* */
/*
* 使用方法：
* $sql = "select * from news limit 0,10";
* $Pagenav = new PageItem($sql);
* echo $Pagenav->myPageItem();
* $arrRecords = $Pagenav->ReadList();
* */
if (!defined("__PAGEITEM__")) {
	define("__PAGEITEM__", 1);
} else {
	exit(3);
}

class PageItem {
	//var $iDefaultRecords = 10; // 默认每页显示记录数，如果没有设置，就使用默认值
	var $iMaxRecord; //每页记录数
	var $iTotal; //记录总数
	var $sqlRecord; // 获取记录的SQL查询
	var $iPages; //总页数
	var $CPages; //当前页数
	var $Limit;  //最终截止数字
	var $pType; //传递过来的产品类型

	/*  * 构造函数 －－ 初始化变量  * 参数：SQL查询语句，将忽略LIMIT语句  * */

	function PageItem($sql,$num)
	{
		global $DB,$Limit;
		$this->iDefaultRecords = $num>0 ? $num : 10;
		$this->SetMaxRecord($this->iDefaultRecords);
		/*  *  解析SQL语句       * */
		if ($sql != "") {
			list($sql) = spliti("LIMIT", $sql); // 去除LIMIT语句
			$this->sqlRecord = trim($sql);
			list(, $sql) = spliti("FROM", $sql);
			$sql = trim($sql);

			if(preg_match ("/\bGROUP\b \bBY\b/i", $sql))
			{
				if(preg_match ("/\bHAVING\b/i", $sql)) list(,$field) = spliti("HAVING",$sql);

				list($field) = spliti(' ',trim($field));
				if ($Limit>0){
					$this->iTotal = $Limit;
				}else{
					$this->iTotal = $this->CountRecord("SELECT $field,COUNT(DISTINCT $field) AS cnt FROM " . $sql,2);
                }
			}else  {
				if ($Limit>0){
					$this->iTotal = $Limit;
				}else{
					$this->iTotal = $this->CountRecord("SELECT COUNT(*) AS cnt FROM " . $sql,1);
				}
			}
		}

		$this->iPages = ceil($this->iTotal / $this->iMaxRecord);
		$this->CPages = intval($_REQUEST['page']);
		if ($this->CPages <= 0) $this->CPages = 1;
		if ($this->CPages > $this->iPages) $this->CPages = $this->iPages;
	}

	function SetMaxRecord($cnt)
	{
        global $Limit,$pType;
         
	     if (intval($Limit)>0 && $Limit<=$cnt && $pType=='NewProduct'){
			 $this->iMaxRecord = $Limit;
		 }else{
			 $this->iMaxRecord = $cnt  ;
		 }
	}

	/* * 统计匹配的记录总数  * */
	function CountRecord($sql,$type)
	{
		global $DB;
		if($type == 1)
		{
			if (($records = $DB->query($sql)) && ($record =  $DB->fetch_assoc($records))) {
				return $record['cnt'];
			} else return 0;
		}
		elseif($type == 2)
		{
			if($records =  $DB->query($sql))
			return $DB->affected_rows();
		}
	}
	/*  * 读取记录 * */
	function ReadList()
	{
		global $DB;
		$StartPage = intval($this->CPages-1)<=0 ? 0 : $this->CPages-1 ;
		$this->sqlRecord.=" LIMIT ".$StartPage*$this->iMaxRecord.",".$this->iMaxRecord;
		//$this->sqlRecord.=" LIMIT ".($this->CPages-1)*$this->iMaxRecord.",".$this->iMaxRecord;

		$records = $DB->query($this->sqlRecord);
		$num     = $DB->num_rows($records);

		if($num>0){
			return $records;
		}else{
			return 0;
		}
	}

	function LinktoPage($page, $msg,$stype="")
	{
		$link = $this->PageUrl($page);
		return "<li " . $stype . "><A href=\"$link\">$msg</A></li>\n";
	}
	function PageUrl($page)
	{
		global $INFO;
		
		//$phpself = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
		//$phpself = "http://127.0.0.1" . $_SERVER['PHP_SELF'];
		$phpself = $INFO['site_url'] . $_SERVER['PHP_SELF'];
		$querystring = $_SERVER['QUERY_STRING'];
		$querystring = preg_replace("/page=[0-9]*&?/i", "", $querystring);
		$link = $phpself . "?page=$page&" . $querystring;
		return $link;
	}

	/*  * 显示当前页及总页数     * */
	function PageNav()
	{
		global $PageNavClass_Pack;
		//$PageNavClass_Pack['TheNo']       = "第";
		//$PageNavClass_Pack['PageAndTol']  = "页/共";
		//$PageNavClass_Pack['Page']        = "页";
		return "<div class='page_all'>" .$PageNavClass_Pack['TheNo'].$this->CPages."/".$this->iPages."".$PageNavClass_Pack['Page']. "</div>";
	}

	/* * 显示翻页按钮，包括首页、下页、上页、未页   * */

	function PageButton($type=""){
		global $PageNavClass_Pack;
		// $PageNavClass_Pack['First_Page'] = "首页";
		// $PageNavClass_Pack['Last_Page'] = "最后页";
		// $PageNavClass_Pack['Next_Page'] = "下一页";
		// $PageNavClass_Pack['Pre_Page'] = "前一页";
		//$PageNavClass_Pack['First_Page']  = "首页";
		// $PageNavClass_Pack['Last_Page']   = "最后页";
		// $PageNavClass_Pack['Next_Page']   = "下一页";
		// $PageNavClass_Pack['Pre_Page']    = "前一页";
		if($type=""){
		$Pagebutton="<ul class='pagination pagination-simple ".$type."'>";
		if ($this->CPages > 1) {
			$Pagebutton .= $this->LinktoPage(1, "" , " class='pagination pagination-simple'");/*最前一頁*/
			$Pagebutton .= "   ";
			$Pagebutton .= $this->LinktoPage($this->CPages-1, "prev");/*往前一頁*/
		} else {
			$Pagebutton .= ""."   ". "<li><a href='#'>prev</a></i>";
		}
		
		if ($this->CPages > 5) {
			 $fgp = ($this->CPages-5 > 0) ? $this->CPages-5 : 1;
			 $egp = $this->CPages+4;
			 if ($egp > $this->iPages) {
				 $egp = $this->iPages;
				 $fgp = ($this->iPages-9 > 0) ? $this->iPages-9 : 1;
			 }
		 } else {
			 $fgp = 1;
			 $egp = ($this->iPages >= 10) ? 10 : $this->iPages;
		 }
		
		for($i=$fgp;$i<=$egp;$i++){
			$Pagebutton .= "  ";
			if($i == $this->CPages){
				$Pagebutton .= "<li class='active'><a href='#'>" . $i . "</a></li>";	
			}else{
				$Pagebutton .= "" .$this->LinktoPage($i, $i. "" , "style='margin-left:5px'");	
			}
		}

		if ($this->CPages < $this->iPages) {
			$Pagebutton .= "  ";
			$Pagebutton .= $this->LinktoPage($this->CPages + 1, "next" , "style='margin-left:5px'");/*往後一頁*/
			$Pagebutton .= "   ";
			$Pagebutton .= $this->LinktoPage($this->iPages, ""); /*最後一頁*/
		} else {
			$Pagebutton .= "  "."<li style='margin-left:5px'><a href='#'>next</a></i>"."   "."";
		}
		$f = $this->iMaxRecord * ($this->CPages-1) + 1;
		$e = $this->iMaxRecord * ($this->CPages);
		$e = $e>$this->iTotal?$this->iTotal:$e;
		return "<p align='right' class='page_p'>"."共 " . $this->iTotal . " 筆，第 " . $f . "-" . $e .  " 筆"."</p>" . $Pagebutton . "</ul>";
		}
		else{
		if ($this->CPages > 5) {
			 $fgp = ($this->CPages-5 > 0) ? $this->CPages-5 : 1;
			 $egp = $this->CPages+4;
			 if ($egp > $this->iPages) {
				 $egp = $this->iPages;
				 $fgp = ($this->iPages-9 > 0) ? $this->iPages-9 : 1;
			 }
		 } else {
			 $fgp = 1;
			 $egp = ($this->iPages >= 10) ? 10 : $this->iPages;
		 }
		for($i=$fgp;$i<=$egp;$i++){
			if($i == $this->CPages){
				$Pagetop .= "<li class='active'>" . $i . " 頁</li>";	
			}
		}
			
			$Pagetop="<ul class='pagination pagination-simple'>";
		if ($this->CPages > 1) {
			$Pagetop .= $this->LinktoPage(1, "" , " class='pagination pagination-simple'");/*最前一頁*/
			$Pagetop .= "   ";
			$Pagetop .= $this->LinktoPage($this->CPages-1, "prev");/*往前一頁*/
		} else {
			$Pagetop .= ""."   ". "<li><a href='#'>prev</a></i>";
		}
		if ($this->CPages < $this->iPages) {
			$Pagetop .= "  ";
			$Pagetop .= $this->LinktoPage($this->CPages + 1, "next" , "style='margin-left:5px'");/*往後一頁*/
			$Pagetop .= "   ";
			$Pagetop .= $this->LinktoPage($this->iPages, ""); /*最後一頁*/
		} else {
			$Pagetop .= "  "."<li style='margin-left:5px'><a href='#'>next</a></i>"."   "."";
		}
		}
	}
	/*
	* 显示跳转页选择框
	* */

	function SelectItem()
	{

		global $PageNavClass_Pack;
		$SelectItem = "<div class='page_go'>";
		$SelectItem .= $PageNavClass_Pack['TurnTo']." "."<SELECT name='topage' size='1' onchange='window.location=this.value'>\n";
		for($i = 1;$i <= $this->iPages;$i++) {
			if ($this->CPages == $i)
			$extra = "selected";
			else
			$extra = "";
			$SelectItem .= "<OPTION VALUE='" . $this->PageUrl($i) . "' $extra>$i</OPTION>";
		}
		$SelectItem .= "</SELECT>\n".$PageNavClass_Pack['Page']."</div>";
		return $SelectItem;
	}

	/*
	* 一次性显示所有按钮组件
	* */
	function myPageItem()
	{
		$myPageItem = "";
		$myPageItem .= $this->PageButton();
		return $myPageItem;
	}
} // 类结束

?>   
 
