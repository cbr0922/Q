<?php
/*
* 分页显示类
* PageNav.class.php　v 1.0.0
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
	var $pagebarnum;
	/*  * 构造函数 －－ 初始化变量  * 参数：SQL查询语句，将忽略LIMIT语句  * */
	function PageItem($sql,$num)
	{
		global $DB,$Limit;
		$this->pagebarnum = 10;
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
				if(preg_match ("/\bHAVING\b/i", $sql)) {
					list(,$field) = spliti("HAVING",$sql);
					list($field) = spliti(' ',trim($field));
					if ($Limit>0){
						$this->iTotal = $Limit;
					}else{
						$this->iTotal = $this->CountRecord("SELECT $field,COUNT(DISTINCT $field) AS cnt FROM " . $sql,2);
					}
				}else{
					if ($Limit>0){
						$this->iTotal = $Limit;
					}else{
						$this->iTotal = $this->CountRecord("SELECT COUNT(*) AS cnt FROM " . $sql,1);
					}	
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
		$this->CPages = $_REQUEST['page'];
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
	function LinktoPage($page, $msg)
	{
		$link = $this->PageUrl($page);
		return "<font color=\"#666666\"><A href=\"$link\" class='nav2'  >$msg</A></font>\n";
	}
	function LinktoPage2($page, $msg)
	{
		$link = $this->PageUrl($page);
		return "<font color=\"#666666\"><A href=\"$link\"  >$msg</A></font>\n";
	}
	function PageUrl($page)
	{
		global $INFO;
		$phpself = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
		//$phpself = "http://127.0.0.1" . $_SERVER['PHP_SELF'];
		//$phpself = $INFO['site_url'] . $_SERVER['PHP_SELF'];
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
		return  $PageNavClass_Pack['TheNo'].$this->CPages.$PageNavClass_Pack['PageAndTol'].$this->iPages."".$PageNavClass_Pack['Page'];
	}
	/* * 显示翻页按钮，包括首页、下页、上页、未页   * */
	function PageButton()
	{
		global $PageNavClass_Pack;
		// $PageNavClass_Pack['First_Page'] = "首页";
		// $PageNavClass_Pack['Last_Page'] = "最后页";
		// $PageNavClass_Pack['Next_Page'] = "下一页";
		// $PageNavClass_Pack['Pre_Page'] = "前一页";
		//$PageNavClass_Pack['First_Page']  = "首页";
		// $PageNavClass_Pack['Last_Page']   = "最后页";
		// $PageNavClass_Pack['Next_Page']   = "下一页";
		// $PageNavClass_Pack['Pre_Page']    = "前一页";
		
		$return="";
		if ($this->CPages>1){
			$return.=$this->LinktoPage2($this->CPages-1,"<img src=\"../templates/top-do/images/icon-prev2.gif\" width=\"4\" height=\"8\" border=\"0\" style=\"margin-right:10px;\">");	
			//$return.=$this->LinktoPage(1,"<<");	
		}else{
			$return.="<img src=\"../templates/top-do/images/icon-prev2.gif\" width=\"4\" height=\"8\" border=\"0\" style=\"margin-right:0px;\">";
		}
		
		$plus=ceil($this->pagebarnum/2);
	  if($this->pagebarnum-$plus+$this->CPages>$this->iPages)$plus=($this->pagebarnum-$this->iPages+$this->CPages);
	  $begin=$this->CPages-$plus+1;
	  $begin=($begin>=1)?$begin:1;
	  for($i=$begin;$i<$begin+$this->pagebarnum;$i++)
	  {
	   if($i<=$this->iPages){
		if($i!=$this->CPages)
			$return.=$this->LinktoPage($i,$i);
		else 
			$return.='<span class="nav2-cur">'.$i.'</span>';
	   }else{
		break;
	   }
	   $return.="\n";
	  }
	  if ($this->CPages<$this->iPages){
			$return.=$this->LinktoPage2($this->CPages+1,"<img src=\"../templates/top-do/images/icon-next2.gif\" width=\"4\" height=\"8\" border=\"0\" style=\"margin-left:10px;\">");	
			//$return.=$this->LinktoPage($this->iPages,">>");	
		}else{
			$return.=	"<img src=\"../templates/top-do/images/icon-next2.gif\" width=\"4\" height=\"8\" border=\"0\" style=\"margin-left:0px;\">";
		}
		
		/*
		if ($this->CPages > 1) {
			$Pagebutton .= $this->LinktoPage(1, $PageNavClass_Pack['First_Page']);
			$Pagebutton .= " | ";
			$Pagebutton .= $this->LinktoPage($this->CPages-1, $PageNavClass_Pack['Pre_Page']);
		} else {
			$Pagebutton .= $PageNavClass_Pack['First_Page']." | ". $PageNavClass_Pack['Pre_Page'];
		}
		if ($this->CPages < $this->iPages) {
			$Pagebutton .= " | ";
			$Pagebutton .= $this->LinktoPage($this->CPages + 1, $PageNavClass_Pack['Next_Page']);
			$Pagebutton .= " | ";
			$Pagebutton .= $this->LinktoPage($this->iPages, $PageNavClass_Pack['Last_Page']);
		} else {
			$Pagebutton .= " | ".$PageNavClass_Pack['Next_Page']." | ".$PageNavClass_Pack['Last_Page'];
		}
		*/
		return $return;
	}
	/*
	* 显示跳转页选择框
	* */
	function SelectItem()
	{
		global $PageNavClass_Pack;
		$SelectItem = " ";
		$SelectItem .= $PageNavClass_Pack['TurnTo']."<SELECT name='topage' size='1' onchange='window.location=this.value'>\n";
		for($i = 1;$i <= $this->iPages;$i++) {
			if ($this->CPages == $i)
			$extra = "selected";
			else
			$extra = "";
			$SelectItem .= "<OPTION VALUE='" . $this->PageUrl($i) . "' $extra>$i</OPTION>";
		}
		$SelectItem .= "</SELECT>\n";
		return $SelectItem;
	}
	/*
	* 一次性显示所有按钮组件
	* */
	function myPageItem()
	{
		$myPageItem = "";
		$myPageItem .= $this->PageButton();
		if ($this->iPages>1)
		return $myPageItem;
	}
} // 类结束
?>
