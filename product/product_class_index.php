<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");


$bid  = $FUNCTIONS->Value_Manage($_GET['bid'],'','back','');
$bid  =intval($bid);

$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($bid);
$Next_ArrayClass  = explode(",",$Next_ArrayClass);
$Array_class      = array_unique($Next_ArrayClass);

foreach ($Array_class as $k=>$v){
	$Addall .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
	$Addall2 .= trim($v)!="" && intval($v)>0 ? " or gc.bid=".$v." " : "";
}
		
		$gid_array = array();
		$gid_sql_str = "";
		$gid_str = "";
		$extendsql = "select gc.gid from `{$INFO[DBPrefix]}goods_class` as gc where gc.bid ='" . intval($bid) . "' " . $Addall2 . "";
		$extend_query  = $DB->query($extendsql);
		$ei = 0;
		while($extend_rs = $DB->fetch_array($extend_query)){
			$gid_array[$ei] = $extend_rs['gid'];
			$ei++;
		}
		if (is_array($gid_array) && count($gid_array)>0){
			$gid_str = implode(",",$gid_array);
			$gid_sql_str_all = " or g.gid in (" . $gid_str . ")";
		}

$Query   = $DB->query("select bid from  `{$INFO[DBPrefix]}bclass` where catiffb=1 and bid=".intval($bid)." limit 0,1");
$Num   = $DB->num_rows($Query);
if ( $Num==0 ){ //如果不存在资料
	$FUNCTIONS->header_location("index.php");
}
$DB->free_result($Query);

$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result     =  $DB->fetch_array($Query);
	$Bname      =  $Result['catname'];
	$Bcontent   =  $Result['catcontent'];
	$Meta_desc   =  $Result['meta_des'];
	$Meta_keyword   =  $Result['meta_key'];
	$banner   =  $Result['banner'];
	$banner2   =  $Result['banner2'];
	$nclass   =  $Result['nclass'];
	$Bname_url  = "<a href=".$INFO[site_url]."/product/product_class_detail.php?bid=".intval($bid).">".$Bname."</a>";
	$Egg  = $FUNCTIONS->father_Nav_banner(intval($Result['top_id']));

	if ($Egg!=""){
		$Egg  = explode(",",str_replace("||",",",$Egg));
		krsort($Egg);
		reset ($Egg);
		foreach ($Egg as $k=>$v){
			$Father_Nav .= $v!="" ? $v." - " : "";
		}
	}

}
//本周特價
$Pic_News     = "select ns.news_id,ns.ntitle,ns.nimg,ns.brief,ns.news_id,ns.nltitle,ns.nltitle_color,ns.url_on,ns.url from `{$INFO[DBPrefix]}nclass` nc inner join  `{$INFO[DBPrefix]}news` ns on ( nc.ncid = ns.top_id ) where  nc.ncid='" . $nclass . "' and  nc.ncatiffb='1' and  ns.niffb='1' order by ns.nord asc, ns.nidate desc  limit 0,5";
    $Query_Pic_News   = $DB->query($Pic_News);
    $i=0;
	while( $Result_Pic_news = $DB->fetch_array($Query_Pic_News) )
	{
	   $Nltitle        =  $Result_Pic_news['nltitle'];
	   $Nltitle_first  =  $Result_Pic_news['nltitle_color']!="" ? "<font color=".$Result_Pic_news['nltitle_color'].">".$Nltitle."</font>" : $Nltitle ;
	   $Nltitle_s =  $Result_Pic_news['url_on']==0 ? "<a href='".$INFO['site_url']."/article/article.php?articleid=".intval($Result_Pic_news['news_id'])."&type=print' class=job2>".$Nltitle_first."</a>" :  "<a href='".$Result_Pic_news['url']."'>".$Nltitle_first."</a>";
       $Pic_News1[$i]['news_id']	= $Result_Pic_news['news_id'];
	   $Pic_News1[$i]['title']		= $Result_Pic_news['ntitle'];
	   $Pic_News1[$i]['ltitle']		= $Nltitle_s;
	   $Pic_News1[$i]['brief']		= $Result_Pic_news['brief'];
	   $Pic_News1[$i]['nimg']		= $Result_Pic_news['nimg'];
      $i++;
    }
	//print_r($Pic_News1);
$tpl->assign('pic_news',$Pic_News1);

$Nav_Product_class  = $Father_Nav.$Bname_url;
$tpl->assign("Nav_Product_class",  $Nav_Product_class); //产品种类导航条

$tpl->assign("Bname",     $Bname);     //产品大类名称
$tpl->assign("Bcontent",  $Bcontent);  //产品HTML编辑器的内容
$tpl->assign("Meta_desc",  $Meta_desc);
$tpl->assign("Meta_keyword",  $Meta_keyword);
$tpl->assign("banner",  $banner);
$tpl->assign("banner2",  $banner2);
$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where top_id=".intval($bid)." and catiffb=1 order by catord,bid  asc  limit 0,5 ");
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$j = 0;
	while($Result     =  $DB->fetch_array($Query)){
		$Add = "";
		$Add2 = "";
		$Next_ArrayClass= "";
		$Array_class= array();
		$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($Result['bid']);
		$Next_ArrayClass  = explode(",",$Next_ArrayClass);
		$Array_class      = array_unique($Next_ArrayClass);
		
		foreach ($Array_class as $k=>$v){
			$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
			$Add2 .= trim($v)!="" && intval($v)>0 ? " or gc.bid=".$v." " : "";
		}
		
		/*
		$gid_array = array();
		$gid_sql_str = "";
		$gid_str = "";
		$extendsql = "select gc.gid from `{$INFO[DBPrefix]}goods_class` as gc where gc.bid ='" . intval($bid) . "' " . $Add2 . "";
		$extend_query  = $DB->query($extendsql);
		$ei = 0;
		while($extend_rs = $DB->fetch_array($extend_query)){
			$gid_array[$ei] = $extend_rs['gid'];
			$ei++;
		}
		if (is_array($gid_array) && count($gid_array)>0){
			$gid_str = implode(",",$gid_array);
			$gid_sql_str = " or g.gid in (" . $gid_str . ")";
		}
		*/
		$Sql_G = "select g.view_num,g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.salename_color  from `{$INFO[DBPrefix]}goods` g where g.ifpub=1 and g.ifbonus!=1 and g.ifpresent!=1 and g.ifxygoods!=1 and g.ifxy!=1 and g.ifchange!=1 and g.shopid=0  and (g.bid='" . $Result['bid'] . "' ".$Add." " . $gid_sql_str . ") and g.ifrecommend=1 order by rand() limit 0,4";
		$Query_G =    $DB->query($Sql_G);
		$Num_G   = $DB->num_rows($Query_G);
		$View_product[$j]['goodscount'] = $Num_G;
		$View_product[$j]['banner2'] = $Result['banner2'];
		$View_product[$j]['link'] = $Result['link'];
		$i=0;
		$num=0;
		while ($View_Rs=$DB->fetch_array($Query_G)) {
			$View_product[$j]['goods'][$i][gid] = $View_Rs['gid'];
			$View_product[$j]['goods'][$i][goodsname] = $View_Rs['goodsname'];
			$View_product[$j]['goods'][$i][viewnum] = $View_Rs['viewnum'];
			$View_product[$j]['goods'][$i][intro] = $View_Rs['intro'];
			//$View_product[$j]['goods'][$i][sale_name] = $View_Rs['sale_name'];
			$View_product[$j]['goods'][$i][sale_name] = $View_Rs['salename_color']==""?$View_Rs['sale_name']:"<font color='" . $View_Rs['salename_color'] . "'>" . $View_Rs['sale_name'] . "</font>";
			$View_product[$j]['goods'][$i][pricedesc] = $View_Rs['pricedesc'];
			$View_product[$j]['goods'][$i][smallimg] = $View_Rs['smallimg'];
			if ($View_Rs['iftimesale']==1 && $View_Rs['timesale_starttime']<=time() && $View_Rs['timesale_endtime']>=time()){
				$View_product[$j]['goods'][$i][pricedesc]  = $View_Rs['saleoffprice'];
			}
			$View_product[$j]['goods'][$i][ifsaleoff] = $View_Rs['ifsaleoff'];
			if ($View_Rs['saleoff_starttime']!=""){
				$View_product[$j]['goods'][$i][startdate] = date("Y-m-d H:i",$View_Rs['saleoff_starttime']);
			}
			if ($View_Rs['saleoff_endtime']!=""){
				$View_product[$j]['goods'][$i][enddate] = date("Y-m-d H:i",$View_Rs['saleoff_endtime']);
			}
			$i++;
		}
		$View_product[$j]['catname'] = $Result['catname'];
		$View_product[$j]['bid'] = $Result['bid'];
		$j++;
	}
}
$tpl->assign("View_product",  $View_product);




$tpl->assign("getbid",  $bid);
$tpl->assign($Good);
$tpl->display("product_class_index.html");
?>