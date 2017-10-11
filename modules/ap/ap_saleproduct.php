<?php
@header("Content-type: text/html; charset=utf-8");
include( "../../configs.inc.php");
include_once ("Char.class.php");
$Char_Class = new Char_class();
$sql = "TRUNCATE TABLE ntssi_salegoodslist;";
			$DB->query($sql);
$y = date("Y",time());
$m = date("m",time());
$d = date("d",time());
$starttime = mktime(0,0,0,$m-1,1,$y);
$endtime = mktime(0,0,0,$m,1,$y);
$Sql = "select g.view_num,g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage, sum(o.goodscount) as goodscount_all  from `{$INFO[DBPrefix]}order_detail` o inner join `{$INFO[DBPrefix]}order_table` oo on oo.order_id=oo.order_id inner join `{$INFO[DBPrefix]}goods` g on g.gid=o.gid left join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid) where g.ifpub=1 and g.checkstate=2 and g.ifbonus!=1 and g.ifpresent!=1 and g.ifgoodspresent!=1 and g.ifxygoods!=1 and g.ifxy!=1 and g.ifchange!=1 and b.catiffb=1  and oo.createtime>='$starttime' and oo.createtime<='$endtime' " . $where . " group by o.gid order by goodscount_all desc,g.gid asc limit 0,10";
$Query =    $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$i=0;
$num=0;
while ($View_Rs=$DB->fetch_array($Query)) {
	
	$sale_name = $View_Rs['salename_color']==""?$View_Rs['sale_name']:"<font color='" . $View_Rs['salename_color'] . "'>" . $View_Rs['sale_name'] . "</font>";
	$pricedesc = $View_Rs['pricedesc'];
	$smallimg = $View_Rs['smallimg'];
	if($View_Rs['smallimg']==""){
		$smallimg = $View_Rs['middleimg'];	
	}
	if ($View_Rs['iftimesale']==1 && $View_Rs['timesale_starttime']<=time() && $View_Rs['timesale_endtime']>=time()){
		$pricedesc  = $View_Rs['saleoffprice'];
	}
	
	//$price1 = $FUNCTIONS->getBuyMorePrice($View_Rs['gid']);
	//if($price1>0)
	//	$pricedesc = $price1;
	$db_string = $DB->compile_db_insert_string( array (
	'gid'            => $View_Rs['gid'],
	'goodsname'             => $View_Rs['goodsname'],
	'intro'             => $Char_class->cut_str($View_Rs['intro'],14,0,'UTF-8'),
	'viewnum'            => $View_Rs['viewnum'],
	'sale_name'            => $sale_name,
	'pricedesc'            => $pricedesc,
	'smallimg'            => $smallimg,
	'price'         =>  $View_Rs['price'],
	'pubdate'             => date("Y-m-d"),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}salegoodslist` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	$i++;
}
$Query_b   = $DB->query("select * from  `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id=0 order by catord,bid  asc");
while ( $ClassRow = $DB->fetch_array($Query_b)){
	$bid = $ClassRow['bid'];
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($bid);
	$Next_ArrayClass  = explode(",",$Next_ArrayClass);
	$Array_class      = array_unique($Next_ArrayClass);
	$Addall = "";
	foreach ($Array_class as $k=>$v){
		$Addall .= trim($v)!="" && intval($v)>0 ? " or g.bid='".intval($v)."' " : "";
	}
	$where = " and (g.bid='" . $bid . "' " . $Addall . ")";	
	$Sql = "select g.view_num,g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage, sum(o.goodscount) as goodscount_all  from `{$INFO[DBPrefix]}order_detail` o inner join `{$INFO[DBPrefix]}order_table` oo on oo.order_id=oo.order_id inner join `{$INFO[DBPrefix]}goods` g on g.gid=o.gid left join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid) where g.ifpub=1 and g.checkstate=2 and g.ifbonus!=1 and g.ifpresent!=1 and g.ifgoodspresent!=1 and g.ifxygoods!=1 and g.ifxy!=1 and g.ifchange!=1 and b.catiffb=1  and oo.createtime>='$starttime' and oo.createtime<='$endtime' " . $where . " group by o.gid order by goodscount_all desc,g.gid asc limit 0,10";
	$Query =    $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	$i=0;
	$num=0;
	while ($View_Rs=$DB->fetch_array($Query)) {
		
		$sale_name = $View_Rs['salename_color']==""?$View_Rs['sale_name']:"<font color='" . $View_Rs['salename_color'] . "'>" . $View_Rs['sale_name'] . "</font>";
		$pricedesc = $View_Rs['pricedesc'];
		$smallimg = $View_Rs['smallimg'];
		if($View_Rs['smallimg']==""){
			$smallimg = $View_Rs['middleimg'];	
		}
		if ($View_Rs['iftimesale']==1 && $View_Rs['timesale_starttime']<=time() && $View_Rs['timesale_endtime']>=time()){
			$pricedesc  = $View_Rs['saleoffprice'];
		}
		
		//$price1 = $FUNCTIONS->getBuyMorePrice($View_Rs['gid']);
		//if($price1>0)
		//	$pricedesc = $price1;
		$db_string = $DB->compile_db_insert_string( array (
		'gid'            => $View_Rs['gid'],
		'goodsname'             => $View_Rs['goodsname'],
		'intro'             => $Char_class->cut_str($View_Rs['intro'],14,0,'UTF-8'),
		'viewnum'            => $View_Rs['viewnum'],
		'sale_name'            => $sale_name,
		'pricedesc'            => $pricedesc,
		'smallimg'            => $smallimg,
		'price'         =>  $View_Rs['price'],
		'pubdate'             => date("Y-m-d"),
		'bid'                =>$bid,
		)      );
	
		$Sql="INSERT INTO `{$INFO[DBPrefix]}salegoodslist` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
		$i++;
	}
}
?>