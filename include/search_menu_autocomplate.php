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
$i=0;
$keyArray = explode(" ", $term);
$keyArrayCount = count($keyArray);


//$term = "[多件折扣]INTEGRATE水豔絲絨光脣膏";
//$term="aa";

//$product_array = $PRODUCT->getProductList(0,"",array('skey'=>$term),array($_GET['orderby'],$_GET['ordertype']),0,1,1,0,1);

//echo $term;
//echo $product_array['info'][0]['goodsname'];




//~ $QueryWhere = "WHERE checkstate=2";
//~ 
//~ if( $keyArrayCount != 0 ){
	//~ $QueryWhere = $QueryWhere." AND";
	//~ for( $i=0; $i<$keyArrayCount; $i++ ){
		//~ if( $i ==0 ){
			//~ $QueryWhere = $QueryWhere." `goodsname` LIKE '%".$keyArray[$i]. "%'";
		//~ }
		//~ else{
			//~ $QueryWhere = $QueryWhere." AND `goodsname` LIKE '%".$keyArray[$i]. "%'";
		//~ }
	//~ }
//~ }
//~ 
//~ $sqlcmd = "select goodsname, checkstate, view_num from  `{$INFO[DBPrefix]}goods` ".$QueryWhere." order by view_num  desc limit 10";
$sqlcmd = "select g.*,b.*,br.brandname from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on ( g.brand_id=br.brand_id ) " . $linkSql . " where b.catiffb='1' and g.ifpub='1' and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "')";
if( $keyArrayCount != 0 ){
	$sqlcmd = $sqlcmd." AND";
	for( $i=0; $i<$keyArrayCount; $i++ ){
		if( $i ==0 ){
			$sqlcmd = $sqlcmd." g.goodsname LIKE '%".$keyArray[$i]. "%'";
		}
		else{
			$sqlcmd = $sqlcmd." AND g.goodsname LIKE '%".$keyArray[$i]. "%'";
		}
	}
}
$sqlcmd = $sqlcmd." and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 and g.ifgoodspresent!=1";

$sqlcmd = $sqlcmd." order by view_num  desc limit 10";

//echo $sqlcmd;

$Query   = $DB->query($sqlcmd);
$Num   = $DB->num_rows($Query);

//$result = "[{\"label\":\"".$product_array['info'][0]['goodsname']."\"}";
$result = "[{\"label\":\"".$term."\"}";
if ($Num>0){
		while($AutoCompleteRow = $DB->fetch_array($Query)){

				$result = $result.",{\"value\":\"".trim($AutoCompleteRow['gid'])."\",\"label\":\"".trim($AutoCompleteRow['goodsname'])."\"}";
		}
}
$result = $result."]";

/***********************************************
 **********************************************/
/*$result = "[{\"label\":\"".$term."\"}";
$count = count($product_array['info']);
for($i=0;$i<$count;$i++){
	$result = $result.",{\"label\":\"".$product_array['info'][$i]['goodsname']."\"}";
}

$result = $result."]";*/

echo $result;


?>  
