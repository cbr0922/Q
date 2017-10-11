<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");

if (is_file("configs.inc.php")){
	include("./configs.inc.php");
}
else if (is_file("../configs.inc.php")){
	include("../configs.inc.php");
}
if (intval($INFO['siteOpen'])==0){
	echo "<Br><br><div align='center'>網站正在維護中...</div>";exit;
}
include (Classes."/global.php");
include (ConfigDir."/setindex.php");

/*include("product.class.php");
$PRODUCT = new PRODUCT();*/

$color_array = array("#c2fcef","#ddd6ff","#c3fdc1","#ffdabf","#fbc5e7","#c3d1fb","#fbc3c3","#b0daff","#fff08b");
$colorbg_array = array("#65cfb7","#beb1fc","#77d074","#ffb077","#f08aca","#859fee","#f78b8b","#7ab9f0","#dcc73d");

/***********************************************
 * search AutoComplete
 **********************************************/
$z=0;
$keysArray = explode(" ", $term);
$keysArrayCount = count($keysArray);


$sqlnews = "select g.*,b.* from `{$INFO[DBPrefix]}news` g inner join `{$INFO[DBPrefix]}nclass` b on ( g.top_id=b.ncid ) where b.ncatiffb='1' and g.niffb='1' and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "')";
if( $keysArrayCount != 0 ){
	$sqlnews = $sqlnews." AND";
	for( $z=0; $z<$keysArrayCount; $z++ ){
		if( $z ==0 ){
			$sqlnews = $sqlnews." g.nltitle LIKE '%".$keysArray[$z]. "%'";
		}
		else{
			$sqlnews = $sqlnews." AND g.nltitle LIKE '%".$keysArray[$z]. "%'";
		}
	}
}
$sqlnews = $sqlnews." order by viewnum  desc limit 10";

//echo $sqlnews;

$Query_news   = $DB->query($sqlnews);
$Num_news   = $DB->num_rows($Query_news );

//$result = "[{\"label\":\"".$product_array['info'][0]['goodsname']."\"}";
$result_news = "[{\"label\":\"".$term."\"}";
if ($Num>0){
		while($AutoCompleteRownews = $DB->fetch_array($Query)){

				$result_news = $result_news.",{\"value\":\"".trim($AutoCompleteRownews['news_id '])."\",\"label\":\"".trim($AutoCompleteRownews['nltitle'])."\"}";
		}
}
$result_news = $result_news."]";

/***********************************************
 **********************************************/
/*$result = "[{\"label\":\"".$term."\"}";
$count = count($product_array['info']);
for($i=0;$i<$count;$i++){
	$result = $result.",{\"label\":\"".$product_array['info'][$i]['goodsname']."\"}";
}

$result = $result."]";*/

echo $result_news;


?>  
