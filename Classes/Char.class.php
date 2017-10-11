<?php
@set_time_limit(1200);
/*
+--------------------------------------------------------------------------
|   tyler.wu
|   ========================================
|
+--------------------------------------------------------------------------
*/


class Char_class {



	/*
	+------------------------------------------------------------------------------------------------------
	|   功能说明： 可以把一个字符串里的中英文分开。分开了，你就可以各自计算他们的长度了。
	|   调用部分 ：
	|   ========================================
	|   $str = "abcd大白菜芯";
	|   SplitStr($str);
	|   ========================================
	|   技术说明：
	|   chr(0xa1)-chr(0xff)是汉字的ASCII码范围。
	|   那句话的意思是，看看，当前字符是否属于汉字范围。因为汉字是双字节的，
	|	如果头一个字节的ASCII属于是汉字，那么当前字节加上后一字节的字符就是一个汉字了。
	+------------------------------------------------------------------------------------------------------
	*/

	function SplitStr($str)
	{
		$len    =    strlen ($str);
		$i        =    0;
		$outputcn    =    "";
		$outputen    =    "";
		while ($i<$len)
		{
			if (preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/",$str[$i]))
			{
				$outputcn    .=    $str[$i].$str[$i+1];
				$i    +=    2;
			}
			else
			{
				$outputen    .=    $str[$i];
				$i    +=    1;
			}
		}
		echo "原字符串为：".$str."<br>";
		if ($outputcn != "")
		echo "中文部分字符串：".$outputcn."<br>";
		if ($outputen != "")
		echo "英文部分字符串：".$outputen."<br>";
	}




	/*
	+------------------------------------------------------------------------------------------------------
	|   功能说明： 一个接一个地检查，是否它是在A-Za-z_0-9-及汉字的编码范围内。是的话$okeyflag=1.
	|   调用部分 ：
	|   ========================================
	|   if(!isLegalName($username)){
	|   die( "含有非法字符!");
	|   }
	|   ========================================
	+------------------------------------------------------------------------------------------------------
	*/

	function isLegalName($str){

		for($i=0;$i<strlen($str);$i++){
			$test=ord(substr($str,$i,1));
			if(($test<0x2D)||
			(($test>0x2D)&&($test<0x30))||
			(($test>0x39)&&($test<0x41))||
			(($test>0x5A)&&($test<0x5F))||
			(($test>0x5F)&&($test<0x61))||
			(($test>0x7A)&&($test<0xa0))){
				$OkeyFlag=1;
				break;
			}
			else $OkeyFlag=0;
			$i++;
		}

		if(!$OkeyFlag)return 1;
		return 0;
	}


	/*
	+------------------------------------------------------------------------------------------------------
	|   功能说明： 判断是否是中文字符串。如果不是。将获得一个为1的返回值
	|   调用部分 ：
	|   ========================================
	|  $username = "中3国";
	|
	|    if(isCn($username)==1){
	|     die( "含有非法字符!");
	|    }else{
	|     die( "不含有非法字符!");
	|    }
	|
	+------------------------------------------------------------------------------------------------------
	*/


	function isCn($str){

		for($i=0;$i<strlen($str);$i++){
			if (!preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/",$str[$i])) {
				$Code =1;
			}
		}
		return $Code;
	}



	function CheckValid($type,$value){

		if ($type=="") {
			$checkOK = "abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ0123456789_";
		}else{
			$checkOK = $type;
		}

		$checkStr = $value;
		$allValid = true;
		for ($i = 0;  $i < strlen($value);  $i++){
			$ch = $value[$i];
			for ($j = 0;  $j < strlen($checkOK);  $j++)
			if ($ch == $checkOK[$j])
			break;
			if ($j == strlen($checkOK)){
				$allValid = false;
				break;
			}
		}
		/*
		if (!allValid){
		alert("用户名只能由字母、数字、下划线组成！");
		f.nickname.focus();
		return false;
		}
		*/
		return $allValid;
	}
	//+--------------------------------------------------------------------------


	/*
	+------------------------------------------------------------------------------------------------------
	|   功能说明： 根据类表生成树型图.
	|   调用部分 ：
	|   ========================================
	|   $test_array = get_page_children($node_cache,0, $node_cache,0);
	|
	|    echo "\n<select name=select>\n";
	|
	|    $last = "├─";
	|    foreach($test_array as $key=>$val)
	|   {
	|      $item = str_repeat(" │ ",$val['depth']);
	|      echo "<option value=".$val['id'].">".$item.$last.$val['name']."</option>\n";
	|      $item = '';
	|    }
	|
	+------------------------------------------------------------------------------------------------------
	*/
	function  node_cache($inputnode){
		global $node_cache;
		if (!is_array($node_cache) || empty($node_cache)){
			$this->node_cache = $inputnode;
		}
	}

	function &get_page_children($id, $node,$depth=0)  //&
	{
		global $node_cache;

		if ( empty($node) )
		{
			$node = &$node_cache;  //&
		}
		$depth++;
		$tree_list = array();
		if (is_array($node_cache)){
			foreach ($node as $leap )
			{
				if ($leap['parentId'] == $id)
				{
					$leap['depth'] = $depth - 1;
					$tree_list[] = $leap;
					if ( $children = $this->get_page_children($leap['id'], $node,$depth))
					{
						$tree_list = array_merge($tree_list, $children);
					}
				}
			}
		}

		return $tree_list;
	}
	/*
	+------------------------------------------------------------------------------------------------------
	|   功能说明： 利用根据类表生成树型图的函数，生成一个下拉列表.
	|
	+------------------------------------------------------------------------------------------------------
	*/

	function get_page_select($Selectname,$Id,$Ex,$type=0,$norp="")
	{
		global $SelectFirst,$node_cache,$op_class_array,$FUNCTIONS;
		$Array = $this->get_page_children(0, $node_cache,0);
		$class_array = array();
		$i = 0;
		
		if (is_array($op_class_array)){
		foreach($op_class_array as $k=>$v){
			$class_array[$i] = $v;
			$i++;
			$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($v));
			if ($Next_ArrayClass!=0){
				$Next_ArrayClass  = explode(",",$Next_ArrayClass);
				$Next_ArrayClass      = array_unique($Next_ArrayClass);
				if (is_array($Next_ArrayClass)){
					foreach($Next_ArrayClass as $kk=>$vv){
						$class_array[$i] = 	$vv;
						$i++;
					}
				}
			}
		}
		}
		$Table = "";
		$Table .=" <select id='".$Selectname."' name='".$Selectname."' ".$Ex.">\n";
		$Table .=" <option value=0>├─".$SelectFirst[PleaseSelectFirst]."</option>\n";
		$last = "├─";
		//print_r($class_array);
		foreach($Array as $key=>$val)
		{
			if (($_SESSION['LOGINADMIN_TYPE']==1 && in_array($val['id'],$class_array)) || count($class_array)==0 ||  $_SESSION['LOGINADMIN_TYPE']!=1 || $type==1){
				$item = str_repeat(" │ ",$val['depth']);
				$Table .= "<option value='".$val['id']."'";
				if (intval($Id)==intval($val['id'])){
					$Table .= " selected ";
				}
				$Table .= ">".$item.$last.$val['name']."</option>\n";
				$item = '';
			}
		}
		$Table .= "</select>";
		return $Table;
	}
	
	function getBrandClassSelect($top_id,$level,$brand_id,$selected)
	{
		global $return,$DB,$INFO;
		if($top_id==0)
			$where_sql = " and brand_id='" . $brand_id . "'";
		$Sql = "select * from `{$INFO[DBPrefix]}brand_class` where top_id='" . $top_id . "'" . $where_sql;
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		if($level>0)
			$item = str_repeat(" │ ",$level);
		
		$level = $level+1;
		if($Num>0){
			while ($Rs=$DB->fetch_array($Query)) {
				$return .= "<option value='" . $Rs['bid'] . "'";
				if($selected == $Rs['bid']){
					$return .= " selected=selected ";
				}
				$return .=">" . $item . "├─" . $Rs['catname']."</option>\n";
				$this->getBrandClassSelect($Rs['bid'],$level,$brand_id,$selected);
			}
		}
		return $return;
	}

	//在前台搜索中用的
	function get_page_select_forsearch($Zero_say,$Selectname,$Id,$Ex)
	{
		global $node_cache;
		if ( empty ($node_cache) ){
			$node_cache = $this->node_cache;
		}

		$Array = $this->get_page_children(0,$node_cache,0);
		$Table = "";
		$Table .=" <select name='".$Selectname."' ".$Ex.">\n";
		
		$Table .=" <option value=0>".$Zero_say."</option>\n";


		$last = "├─";
		foreach($Array as $key=>$val)
		{
			$item = str_repeat(" │ ",$val['depth']);
			$Table .= "<option value='".$val['id']."'";
			if (intval($Id)==intval($val['id'])){
				$Table .= " selected ";
			}
			$Table .= ">".$item.$last.$val['name']."</option>\n";
			$item = '';
		}
		$Table .= "</select>";
		return $Table;
	}



	/***************************************************************************
	* * Date : Jul 16, 2005
	*  Copyright : TYLER.WU
	*  Mail : php_netproject@yahoo.com.cn
	*
	*  作用:截取中文字符.
	*
	*  cut_str(字符串, 截取长度, 开始长度, 编码);
	*  编码默认为 utf-8
	*  开始长度默认为 0
	***************************************************************************/

	function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
	{
		if($code == 'UTF-8')
		{
			$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string);

			if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
			return join('', array_slice($t_string[0], $start, $sublen));
		}
		else
		{
			$start = $start*2;
			$sublen = $sublen*2;
			$strlen = strlen($string);
			$tmpstr = '';
			for($i=0; $i<$strlen; $i++)
			{
				if($i>=$start && $i<($start+$sublen))
				{
					if(ord(substr($string, $i, 1))>129) $tmpstr.= substr($string, $i, 2);
					else $tmpstr.= substr($string, $i, 1);
				}
				if(ord(substr($string, $i, 1))>129) $i++;
			}
			if(strlen($tmpstr)<$strlen ) $tmpstr.= "...";
			return $tmpstr;
		}
	}

	/**
     *此函数因为切UTF-8不合适宜，已经被废除
     */
	function chgtitle($title,$length)
	{
		//$length = 46;
		if (strlen($title)>$length) {
			$temp = 0;
			for($i=0; $i<$length; $i++)
			if (ord($title[$i]) > 128)
			$temp++;
			if ($temp%2 == 0)
			$title = substr($title,0,$length)."...";
			else
			$title = substr($title,0,$length+1)."...";
		}
		return $title;
	}


	/**
 * $Mon Apr 24 04:22:49 CST 2006				
 * 来定义字符串中是否存在指定值
 */

	function StrposHave($string,$value){
		$pos = strpos($string,$value);
		if ($pos === false ){
			return 0;
		}else{
			return 1;
		}

	}
	//---------------------------------------------------------------------------------------------

	/**
 * 		
 * php中全角转换为半角函数
 */
	function qj2bj($string){
		$qj2bj = array(
		'１' => '1', '２' => '2', '３' => '3', '４' => '4', '５' => '5',
		'６' => '6', '７' => '7', '８' => '8', '９' => '9', '０' => '0',
		'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd', 'ｅ' => 'e',
		'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j',
		'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n', 'ｏ' => 'o',
		'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't',
		'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x', 'ｙ' => 'y',
		'ｚ' => 'z', 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D',
		'Ｅ' => 'E', 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I',
		'Ｊ' => 'J', 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N',
		'Ｏ' => 'O', 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S',
		'Ｔ' => 'T', 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X',
		'Ｙ' => 'Y', 'Ｚ' => 'Z', '　' => ' ', '，' => ',','。'     => '.',
		'？' => '?', '＜' => '<', '＞' => '>', '［' => '[', '］' => ']',
		'＊' => '*', '＆' => '&', '＾' => '^',     '％'=> '%', '＃' => '#',
		'＠' => '@', '！' => '!', '（' => '(',     '）' => ')','＋' => '+',
		'－' => '-', '｜' => '|', '：' => ':',     '；' => ';',
		'｛' => '{', '｝' => '}', '／' => '/',     '＂' => '"', '～'=>'~'
		);


		return strtr($string, $qj2bj);
	}

}

?>
