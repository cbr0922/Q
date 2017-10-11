<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

/**
 *  特價商品g.ifspecial=1
 */
	$Sql = "select *  from `{$INFO[DBPrefix]}groupdetail` g where g.ifpub=1 and g.ifrecommend=1  order by g.pubtime desc,g.groupbn desc limit 0,3";
	
	$Query =    $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$j=0;
	while ( $RecPro = $DB->fetch_array($Query)){
			$Recommendation_productarray[$j][gid] = $RecPro['gdid'];
			$Recommendation_productarray[$j][countj] = $j+1;
			$Recommendation_productarray[$j][groupname] = $RecPro['groupname'];
			$Recommendation_productarray[$j][groupprice] = $RecPro['groupprice'];
			$Recommendation_productarray[$j][grouppoint] = $RecPro['grouppoint'];
			$Recommendation_productarray[$j][groupbn] = $RecPro['groupbn'];
			$Recommendation_productarray[$j][smallimg] = $RecPro['groupSimg'];
			$j++;
	}

$tpl->assign("Recommendation_productarray",  $Recommendation_productarray);
$tpl->display("include_recgroup.html");
?>
