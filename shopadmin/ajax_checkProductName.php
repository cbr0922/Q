<?php
include("../configs.inc.php");
include(Classes . "/global.php");
@header("Content-type: text/html; charset=UTF-8");

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";


/**
 *  php版的javascript同名函数  
 **/  

function unescape($str) {
	$str = rawurldecode($str);
	preg_match_all("/(?:%u.{4})|.+/",$str,$r);
	$ar = $r[0];
	foreach($ar as $k=>$v) {
		if(substr($v,0,2) == "%u" && strlen($v) == 6)
		$ar[$k] = iconv("UCS-2","UTF-8",pack("H4",substr($v,-4)));
	}
	return join("",$ar);
}

//$sKY= unescape($_GET['key']);
//$sKY = iconv('UCS-2', 'UTF-8', $_GET['key']);
$sKY = trim($_GET['key']);

$Sql  = "select g.goodsname,g.gid,g.idate,g.bn from `{$INFO[DBPrefix]}goods` g  where  ( g.goodsname LIKE '%".$sKY."%' or g.bn LIKE '%".$sKY."%')  order by g.idate desc ";


$Query  = $DB->query($Sql);
$Num    = $DB->num_rows($Query);
if ($Num>0){

	while ($Rs = $DB->fetch_array($Query)){
		echo $Rs[goodsname]."\t\n";
		//echo $Sql;
	}
}else{
	echo "\t".$Basic_Command['NullDate']."\n";
	//echo "unesape:".unescape($UserName)."\nerror:".$Sql;
}
if (intval($Num)<3){
	echo "\t".$Admin_Product[ThisKey]."\n \t".$Admin_Product[ThisKey]."\n";
}
?>