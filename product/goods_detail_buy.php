<?php
error_reporting(7);
include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");
@header("Content-type: text/html; charset=utf-8");

$Goods_id  = $FUNCTIONS->Value_Manage($_GET['goods_id'],$_POST['Goods_id'],'back','');  //判断是否有正常的ID进入
$Goods_id  =intval($Goods_id);

/**
瀏覽等級
**/

$ifview = 0;
$viewlevel_sql = "select * from `{$INFO[DBPrefix]}goods_userlevel` as gu inner join `{$INFO[DBPrefix]}user_level` as ul on gu.levelid=ul.level_id where gu.gid='" . intval($Goods_id) . "'";
$Query_viewlevel = $DB->query($viewlevel_sql);
$viewlevel = array();
$v = 0;
while ($Result_viewlevel=$DB->fetch_array($Query_viewlevel)){
	$viewlevel[$v] = $Result_viewlevel['level_name'];
	if (intval($_SESSION['user_level'])>0 && intval($Result_viewlevel['level_id'])==intval($_SESSION['user_level'])){
		$ifview = 1;
	}
	$v++;
}

$viewlevel_string = "";
if (count($viewlevel)>0)
	$viewlevel_string = "僅允許" . implode(" ",$viewlevel) . "查看商品詳細信息";
	
if ($viewlevel_string != "" && $ifview == 0){
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('" . $viewlevel_string . "');location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";exit;	
}

//0113浏览过的商品
	$goodscount = max($_COOKIE['viewgoods'])+1;
	$flag = 0;
	if (isset($_COOKIE['viewgoods'])){
		foreach($_COOKIE['viewgoods'] as $k=>$v){
			if ($v == intval($Goods_id)){
				$flag = 1;
			}
		}
	}
	if ($flag == 0){
		setcookie("viewgoods[" . $goodscount . "]", intval($Goods_id),time()+3600*24,"/");
	}
/**
結束
**/

$Sql =   "select b.attr,g.goodsname,g.brand_id,g.view_num,g.video_url,g.nocarriage,g.keywords,g.pricedesc,g.bn,g.ifgl,g.bid,g.unit,g.intro,g.price,g.point,g.body,g.middleimg,g.smallimg,g.bigimg,g.gimg,g.goodattr,g.good_color,g.good_size,g.ifrecommend,g.ifspecial,g.ifalarm,g.storage,g.alarmnum,g.ifbonus,g.ifhot,g.provider_id,g.ifjs,g.js_begtime,g.js_endtime,g.js_price,g.js_totalnum,p.provider_name,br.brandname,br.logopic,g.if_monthprice,g.cap_des,g.ifxygoods,g.xycount,g.sale_name,g.ifsaleoff,g.timesale_starttime,g.timesale_endtime,g.iftimesale,g.saleoff_starttime,g.saleoff_endtime,g.saleoffprice,g.jscount,g.sale_subject,g.ifsales,g.trans_type,g.trans_special,g.goodsno,g.salename_color,g.alarmcontent  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)   where  b.catiffb=1 and g.ifpub=1  and g.ifxy!=1 and g.ifpresent!=1 and g.gid=".$Goods_id." and g.ifchange!=1 limit 0,1";
$Query   = $DB->query($Sql);
$Num   = $DB->num_rows($Query);

if ( $Num==0 ) //如果不存在资料
$FUNCTIONS->header_location("product_class_index.php?bid=1");

if ($Num>0){
	$Result_goods = $DB->fetch_array($Query);
	if ($Result_goods['attr']!=""){
		$attrI        =  $Result_goods['attr'];
		$goods_attrI  =  $Result_goods['goodattr'];
		$Attr         =  explode(',',$attrI);
		$Goods_Attr   =  explode(',',$goods_attrI);
		$Attr_num=  count($Attr);
	}else{
		$Attr_num=0;
	}

	//循环多属性部分
	if (is_array($Attr) && intval($Attr_num)>0 ){
		$AttrArray = array();
		$ProductArray = array();
		$j = 0;
		for($i=0;$i<$Attr_num;$i++){
			if ($Goods_Attr[$i]!=""){
				$AttrArray[$j]        = $Attr[$i];
				$ProductArray[$j]     = $Goods_Attr[$i];
				$ProductAttrArray[$j] = array($Attr[$i]=>$Goods_Attr[$i]);
				$j++;
			}
		}
	}
	$tpl->assign("ProductAttrArray",      $ProductAttrArray);    //循环多属性部分数组


	$Goodsname = $Result_goods['goodsname']."".$FUNCTIONS->Storage($Result_goods['ifalarm'],$Result_goods['storage'],$Result_goods['alarmnum']);
	$NoCarriage= $Result_goods['nocarriage'];
	if (trim($Result_goods['brandname'])!=""){
		if ($INFO['staticState'] ==	'open'){
			$Brand     =  "<a href='".$INFO[site_url]."/HTML_C/brand_list_".intval($Result_goods['brand_id'])."_0.html'>".$Result_goods['brandname']."</a>";
		}else{
			$Brand     =  "<a href='".$INFO[site_url]."/brand/brand_product_list.php?BrandID=".$Result_goods['brand_id']."'>".$Result_goods['brandname']."</a>";
		}
		$Brand_id  = $Result_goods['brand_id'];
	}

	$View_num  = $Result_goods['view_num'];
	$Bn        = $Result_goods['bn'];
    $Keywords  = trim($Result_goods['keywords']);
	$Ifgl      = $Result_goods['ifgl'];
	$Bid       = $Result_goods['bid'];
	$Unit      = $Result_goods['unit'];
	//$Intro     = $Char_class->cut_str($Result_goods['intro'],200,0,'UTF-8');
	$Intro     = nl2br($Result_goods['intro']);
	$Price     = $Result_goods['price'];
	$Pricedesc = $Result_goods['pricedesc'];
	$Point     = intval($Result_goods['point']);
	$Body      = $Result_goods['body'];
	$Smallimg  = $Result_goods['smallimg'];
	$Middleimg = $Result_goods['middleimg'];
	$Gimg      = $Result_goods['gimg'];
	$Bigimg    = $Result_goods['bigimg'];
	$Alarmnum  = $Result_goods['alarmnum'];
	$if_monthprice  = $Result_goods['if_monthprice'];
	$goodsno  = $Result_goods['goodsno'];
	//echo $Result_goods['saleoff_starttime'] . "|" . time();
	
	

	if (trim($Intro) !="") {
		$Meta_desc = trim($Intro) ;
	}elseif (trim($INFO['meta_desc']) !="") {
		$Meta_desc = trim($INFO['meta_desc']);
	}elseif (trim($INFO['meta_keyword']) !="") {
		$Meta_desc = trim($INFO['meta_keyword']);
	}

	if (trim($Keywords) !="") {
		$Keywords = trim($Keywords) ;
	}elseif (trim($INFO['meta_keyword']) !="") {
		$Keywords = trim($INFO['meta_keyword']);
	}elseif (trim($INFO['meta_desc']) !="") {
		$Keywords = trim($INFO['meta_desc']);
	}
	$ifsaleoff  = $Result_goods['ifsaleoff'];
	if ($ifsaleoff==1){
		if ($Result_goods['saleoff_starttime']!=""){
			$saleoff_startdate  = date("Y-m-d H:i",$Result_goods['saleoff_starttime']);
			if (time()<$Result_goods['saleoff_starttime'] && $Result_goods['saleoff_starttime']!=""){
				$havebuytime = $Result_goods['saleoff_starttime']-time();
			}
		}
		if ($Result_goods['saleoff_starttime']!=""){
			$saleoff_enddate  = date("Y-m-d H:i",$Result_goods['saleoff_endtime']);
			
			if (time()>$Result_goods['saleoff_endtime'] && $Result_goods['saleoff_endtime']!=""){
				$nobuytime = 1;	
			}
		}
	}
	if ($Result_goods['iftimesale']==1 && $Result_goods['timesale_starttime']<=time() && $Result_goods['timesale_endtime']>=time()){
		$Pricedesc  = $Result_goods['saleoffprice'];
		$saleoff_startdate  = date("Y-m-d H:i",$Result_goods['timesale_starttime']);
		$saleoff_enddate  = date("Y-m-d H:i",$Result_goods['timesale_endtime']);
		$iftimesale  = $Result_goods['iftimesale'];
	}



	$Video_url = trim($Result_goods['video_url']);

	$Storage   = intval($Result_goods['storage']);
	$Ifalarm   = intval($Result_goods['ifalarm']);
	$Provider_name  = $Result_goods['provider_name'];
	$Ifrecommend  = intval($Result_goods['ifrecommend']);
	$Ifspecial = intval($Result_goods['ifspecial']);
	$Ifhot     = intval($Result_goods['ifhot']);
	$Ifbonus   = intval($Result_goods['ifbonus']);
	$cap_des   = $Result_goods['cap_des'];
	$ifxygoods   = $Result_goods['ifxygoods'];
	$ifchange   = $Result_goods['ifchange'];
	$xycount   = $Result_goods['xycount'];
	//$sale_name   = $Result_goods['sale_name'];
	$sale_name = $Result_goods['salename_color']==""?$Result_goods['sale_name']:"<font color='" . $Result_goods['salename_color'] . "'>" . $Result_goods['sale_name'] . "</font>";
	$sale_subject   = intval($Result_goods['sale_subject']);
	$ifsales   = intval($Result_goods['ifsales']);
	$trans_type   = intval($Result_goods['trans_type']);
	$trans_special   = intval($Result_goods['trans_special']);
	$alarmcontent   = $Result_goods['alarmcontent'];
	
	if (intval($sale_subject) >0){
	
		$Query_s = $DB->query("select subject_name,subject_content,salecount  from  `{$INFO[DBPrefix]}sale_subject`  where subject_id='".$sale_subject."'  limit 0,1");
	
		$Rs_s    = $DB->fetch_array($Query_s);
		$tpl->assign("Subject_name",          $Rs_s['subject_name']);              //主题名字
		$tpl->assign("Subject_content",       $Rs_s['subject_content']);           //主题内容
		$tpl->assign("salecount",          $Rs_s['salecount']);
		$tpl->assign("sale_subject",          $sale_subject);
	}

	if ((intval($Result_goods['ifjs'])==1 && !($Result_goods['js_begtime']<=date("Y-m-d",time()) && $Result_goods['js_endtime']>=date("Y-m-d",time())))){
		//echo $Good[AlertZeroExDate]; //【【集殺商品已過期】】
		//echo "<br>  <a href='javascript:window.history.back(-1);'>Back</a>";
		//exit;
		$ifjsover = 1;
		
	}

	/**
     *  获得产品颜色下拉菜单内变量
     */

	if (trim($Result_goods['good_color'])!=""){
		$Good_color_array    =  explode(',',trim($Result_goods['good_color']));

		if (is_array($Good_color_array)){
			foreach($Good_color_array as $k=>$v )
			{
				$Good_Color_Option .= "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Color_Option = "<option value='".$v."'>".$v."</option>\n";
		}
	}else{
		$Good_Color_Option = "";
	}

	$tpl->assign("Good_Color_Option", $Good_Color_Option);

	/**
     *  获得产品尺寸下拉菜单内变量
     */

	if (trim($Result_goods['good_size'])!=""){
		$Good_size_array    =  explode(',',trim($Result_goods['good_size']));

		if (is_array($Good_size_array)){
			foreach($Good_size_array as $k=>$v )
			{
				$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";
		}
	}else{
		$Good_Size_Option = "";
	}


	$tpl->assign("Good_Size_Option", $Good_Size_Option);

	$goodattrI   =  "0,".$Result_goods['goodattr'];
	$goodAttr    =  explode(',',$goodattrI);

	//集杀
	$Ifjs       =  intval($Result_goods['ifjs']);
	if ($Ifjs==1){
		$begtime    =  trim($Result_goods['js_begtime']);
		$endtime    =  trim($Result_goods['js_endtime']);
		$Js_price   =  explode("||",trim($Result_goods['js_price']));
		$Js_totalnum=  intval($Result_goods['js_totalnum']);
		$Js_count   =  explode("||",trim($Result_goods['jscount']));

		$TotalNum = 0;
		foreach ($Js_price as $k=>$v){
			$TotalNum = $TotalNum+$v;
		}

		$i = 0;
		foreach ($Js_price as $k=>$v){
			if (intval($v)>0){
				$Js_open[$i]['js_num']       =  $v;
				$Js_open[$i]['js_percent']   =  round(intval($v)/intval($TotalNum)*100,1);
				$Js_open[$i]['js_height']    =  intval($v)/intval($TotalNum);
			}
			$i++;
		}

		$tpl->assign("js_num1",   $Js_open[0]['js_num']);
		$tpl->assign("js_num2",   $Js_open[1]['js_num']);
		$tpl->assign("js_num3",   $Js_open[2]['js_num']);
		$tpl->assign("js_num4",   $Js_open[3]['js_num']);
		$tpl->assign("js_num5",   $Js_open[4]['js_num']);
		$tpl->assign("js_count1",   $Js_count[0]);
		$tpl->assign("js_count2",   $Js_count[1]);
		$tpl->assign("js_count3",   $Js_count[2]);
		$tpl->assign("js_count4",   $Js_count[3]);
		$tpl->assign("js_count5",   $Js_count[4]);

		$tpl->assign("js_percent1",   $Js_open[0]['js_percent']);
		$tpl->assign("js_percent2",   $Js_open[1]['js_percent']);
		$tpl->assign("js_percent3",   $Js_open[2]['js_percent']);
		$tpl->assign("js_percent4",   $Js_open[3]['js_percent']);
		$tpl->assign("js_percent5",   $Js_open[4]['js_percent']);

		$tpl->assign("js_height1",   $Js_open[0]['js_height']);
		$tpl->assign("js_height2",   $Js_open[1]['js_height']);
		$tpl->assign("js_height3",   $Js_open[2]['js_height']);
		$tpl->assign("js_height4",   $Js_open[3]['js_height']);
		$tpl->assign("js_height5",   $Js_open[4]['js_height']);

		$tpl->assign("Js_totalnum",   $Js_totalnum);
		$tpl->assign("Js_begtime",   $begtime);
		$tpl->assign("Js_endtime",   $endtime);

	}//over js

}



$CurrentTime = date("Y-m-d",time());
if ($CurrentTime>=$begtime && $CurrentTime<=$endtime){
	$DoAction = "Yes";
}else{
	$DoAction = "No";
}
$tpl->assign("DoAction",   $DoAction);




// 增加浏览次数1
$View_num= $View_num+1;
$DB->query("update `{$INFO[DBPrefix]}goods` set view_num='".$View_num."' where gid=".intval($Goods_id));
//$DB->query("update `{$INFO[DBPrefix]}goods` set view_num=view_num+1  where gid=".intval($Goods_id));

//数据库变量
$tpl->assign("Goods_id",   $Goods_id);    //商品id
$tpl->assign("Goodsname",  $Goodsname);   //商品名称]
$tpl->assign("sale_name",  $sale_name);   //商品名称]
$tpl->assign("base64Goodsname",  base64_encode($Goodsname));   //URL处理过的商品名称

$tpl->assign("NoCarriage", $NoCarriage);   //商品名称
$tpl->assign("Brand",      $Brand);       //商品品牌
$tpl->assign("Brand_id",   $Brand_id);    //商品品牌ID

$tpl->assign("View_num",   $View_num);    //浏览次数
$tpl->assign("Bn",         $Bn);          //编号
$tpl->assign("Bid",        $Bid);         //类别ID
$tpl->assign("Unit",       $Unit);        //产品单位
$tpl->assign("Intro",      $Intro);       //简单介绍
$tpl->assign("Price",      $Price);       //价格
$tpl->assign("Pricedesc",  $Pricedesc);   //优惠价格
$tpl->assign("Point",      $Point);       //积分点
$tpl->assign("Body",       $Body);        //主题介绍
$tpl->assign("goodAttr",   $goodAttr);    //多级别属性数组
$tpl->assign("Attr",       $Attr);        //多级别属性数组
$tpl->assign("Attr_num",   $Attr_num);    //多级别属性数组个数
$tpl->assign("Smallimg",   $Smallimg);    //产品略缩小图
$tpl->assign("Middleimg",  $Middleimg);   //产品略中等图
$tpl->assign("Keywords",   $Keywords);
$tpl->assign("Bigimg",     $Bigimg);
$tpl->assign("Gimg",       $Gimg);
$tpl->assign("Ifrecommend",$Ifrecommend);    //推荐产品开关
$tpl->assign("Ifspecial",  $Ifspecial);      //是否特价
$tpl->assign("Ifhot",      $Ifhot);          //是否热卖
$tpl->assign("Ifbonus",    $Ifbonus);        //是否红利
$tpl->assign("Provider_name", $Provider_name);    //供应商名字
$tpl->assign("Url",         $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);    //URL
$tpl->assign("Alarmnum",    $Alarmnum);        //库存警告数字
$tpl->assign("Storage",     $Storage);        //库存
$tpl->assign("Meta_desc",   $Meta_desc);        //库存
$tpl->assign("if_monthprice",   $if_monthprice);        //库存
$tpl->assign("cap_des",   $cap_des); 
$tpl->assign("xycount",   $xycount); 
$tpl->assign("ifchange",   $ifchange);
$tpl->assign("ifsaleoff",   $ifsaleoff);
$tpl->assign("saleoff_startdate",   $saleoff_startdate);
$tpl->assign("saleoff_enddate",   $saleoff_enddate);
$tpl->assign("havebuytime",   intval($havebuytime));
$tpl->assign("nobuytime",   $nobuytime);
$tpl->assign("Ifalarm",   $Ifalarm);
$tpl->assign("iftimesale",   $iftimesale);
$tpl->assign("ifsales",   $ifsales); 
$tpl->assign("goodsno",   $goodsno); 
$tpl->assign("alarmcontent",   $alarmcontent); 

//多圖
$Sql_pic    = "select goodpic_name,goodpic_title from `{$INFO[DBPrefix]}good_pic` where good_id=".intval($Goods_id);
$Query_pic  = $DB->query($Sql_pic);
$Num_pic    = $DB->num_rows($Query_pic);
$Goodpic[0]['pic'] =   $Middleimg;
$Goodpic[0]['title'] =   '';
$i = 1;
if ($Num_pic>0){
	while ($Result_pic = $DB->fetch_array($Query_pic))  {
		$Goodpic[$i]['pic'] =   $Result_pic['goodpic_name'];
		$Goodpic[$i]['title'] =   $Result_pic['goodpic_title'];
		$i++;
	}
}
//特殊配送方式
if ($trans_type==1){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}transportation_special` where trid=".intval($trans_special)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$name      =  $Result['name'];
		$type     =  $Result['type'];
		$content   =  $Result['content'];
		$image   =  $Result['image'];
		$tpl->assign("trans_image",   $image); 
	}	
}

//print_r($Goodpic);
$tpl->assign("Goodpic",   $Goodpic); 
//*3層導覽
if (intval($Bid)>0){
	

	$Query = $DB->query("select catname,bid,catcontent,top_id from `{$INFO[DBPrefix]}bclass` where bid=".intval($Bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$Bname      =  $Result['catname'];
		$Bcontent   =  $Result['catcontent'];
		$top_id   =  $Result['top_id'];
		$firstbid   =  $Result['bid'];
		if ($top_id==0){
			$tpl->assign("goodBname",     $Bname);     //产品大类名称
			$tpl->assign("goodBid",     $firstbid);     //产品大类名称
			$tpl->assign("goodBcontent",  $Bcontent);  //产品HTML编辑器的内容
		}else{
			$tpl->assign("goodcBname",     $Bname);     //产品大类名称
			$tpl->assign("goodcBid",     $firstbid);     //产品大类名称
			$tpl->assign("goodBcontent",  $Bcontent);  //产品HTML编辑器的内容
			$Query = $DB->query("select catname,bid,catcontent,top_id  from `{$INFO[DBPrefix]}bclass` where bid=".intval($top_id)." limit 0,1 ");
			$Num   = $DB->num_rows($Query);
			if ($Num>0){
				$Result     =  $DB->fetch_array($Query);
				$Bname      =  $Result['catname'];
				$top_id   =  $Result['top_id'];
				$firstbid   =  $Result['bid'];
				$tpl->assign("goodBname",     $Bname);     //产品大类名称
				$tpl->assign("goodBid",     $firstbid);     //产品大类名称
				if($top_id>0){
					$Query = $DB->query("select catname,bid,catcontent,top_id  from `{$INFO[DBPrefix]}bclass` where bid=".intval($top_id)." limit 0,1 ");
					$Num   = $DB->num_rows($Query);
					if ($Num>0){
						$Result     =  $DB->fetch_array($Query);
						$Bname      =  $Result['catname'];
						//$top_id   =  $Result['top_id'];
						$firstbid   =  $Result['bid'];
						$tpl->assign("goodoBname",     $Bname);     //产品大类名称
						$tpl->assign("goodoBid",     $firstbid);     //产品大类名称
					}
				}
			}
		}
	}
}
/**
商品分類屬性
**/
$class_sql = "select ac.*,a.attributename from `{$INFO[DBPrefix]}attributeclass` as ac left join `{$INFO[DBPrefix]}attribute` as a on ac.attrid=a.attrid where cid='" . intval($firstbid) . "'";
$Query_class    = $DB->query($class_sql);
$i = 0;
while($Rs_class=$DB->fetch_array($Query_class)){
	$goods_sql = "select * from `{$INFO[DBPrefix]}attributegoods` as ag inner join `{$INFO[DBPrefix]}attributevalue` as av on ag.valueid=av.valueid where ag.gid='" . intval($Goods_id) . "' and av.attrid='" . $Rs_class['attrid'] . "'";
	$Query_goods    = $DB->query($goods_sql);
	$Num   = $DB->num_rows($Query_goods);
	if ($Num>0){
		$attr_goods[$i]['attributename']=$Rs_class['attributename'];
		$attr_goods[$i]['attrid']=$Rs_class['attrid'];
		$ig = 0;
		while($Rs_goods=$DB->fetch_array($Query_goods)){
			$attr_goods[$i]['value'][$ig]['valueid']=$Rs_goods['valueid'];
			$attr_goods[$i]['value'][$ig]['value']=$Rs_goods['value'];
			$ig++;
		}
	
		$i++;
	}
}
//print_r($attr_goods);
$tpl->assign("attr_goods",     $attr_goods);
/**
TAG
**/
$tag_sql = "select * from `{$INFO[DBPrefix]}goods_tag` as at inner join `{$INFO[DBPrefix]}tag` as t on at.tagid = t.tagid where at.gid='" . intval($Goods_id) . "'";
$Query_tag= $DB->query($tag_sql);
$ig = 0;
while($Rs_tag=$DB->fetch_array($Query_tag)){
  $tag_goods[$ig]['tagid']=$Rs_tag['tagid'];
  $tag_goods[$ig]['tagname']=$Rs_tag['tagname'];
  $ig++;
}
 $tpl->assign("tag_goods",$tag_goods);

//print_r($attr_goods);
/**
 * 这里将输出影音位置
 */
if ( $Video_url!=""){

	$Array_Video = explode(".",$Video_url);
	$MaxNum_Video = intval(count($Array_Video)-1);

	switch ($Array_Video[$MaxNum_Video]){
		case "wmv":
			$PlayFile = "play_ms.php";
			$PlayIcon = "02.gif";
			break;
		case "mp3":
			$PlayFile = "play_ms.php";
			$PlayIcon = "01.gif";
			break;
		case "rm":
			$PlayFile = "play_real.php";
			$PlayIcon = "03.gif";
			break;
		case "rmvb":
			$PlayFile = "play_real.php";
			$PlayIcon = "03.gif";
			break;
		case "swf":
			$PlayFile = "play_flash.php";
			$PlayIcon = "04.gif";
			break;
		case "avi":
			$PlayFile = "play_ms.php";
			$PlayIcon = "04.gif";
			break;
		case "asf":
			$PlayFile = "play_ms.php";
			$PlayIcon = "04.gif";
			break;
		default:
			$PlayIcon = "play_tw.gif";
			break;

	}

	//台湾的项目就是指定了一个图片
	$PlayIcon = "play_tw.gif";

	$VideoUrl  = "\n <script language=javascript>function MM_openVideoWindow(theURL,winName,features)  {  window.open(theURL,winName,features); }</script> \n\n";
	$VideoUrl .= "<a href='###' onClick=\"MM_openVideoWindow('".$INFO[site_url]."/product/".$PlayFile."?id=".$Goods_id."','PlayVideo','width=580,height=480')\"><img src=\"".$INFO[site_url]."/templates/".$templates."/images/".$PlayIcon."\" alt=\"Play Video\" border=\"0\" /></a>";
	$tpl->assign("VideoUrl",    $VideoUrl );        //影音的地址
}
/**
 * 当警告开关开启的时候，判断是否库存已经小于或等于零，如果为真，将抛出一个缺货的声明变量。
 */
	$tpl->assign("AlertZeroStorage_1",     "<a href='waitbuy.php?gid=" . $Goods_id . "'>" . "<img src='" . $INFO['site_url']."/templates/".$templates . "/images/but5.gif' border=0>" . "</a><br /><div style='margin-top:10px;'><a href='waitbuy.php?gid=" . $Goods_id . "'>" . $Good[AlertZeroStorageText] . "</a></div>");        //库存
//}elseif($Ifalarm==0 && $Storage <= 0){
	$tpl->assign("AlertZeroStorage_0",     "<br /><div >【目前已無庫存，您可以預買，出貨日期會另行通知】</div>"); 






// 相关产品

if (intval($Ifgl)==1){ //判断是否是指定了产品内容，如果没有就把本类产品资料都调出来
	$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc,gl.s_gid from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}good_link` gl  on (g.gid=gl.s_gid) where g.ifpub=1 and g.ifpresent!=1 and gl.p_gid=".$Goods_id;}else{
		$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.pricedesc,g.intro from `{$INFO[DBPrefix]}goods` g where g.bid=".$Bid." and g.gid!=".$Goods_id." and g.ifpub=1 and g.ifpresent!=1 and g.ifchange!=1 and g.ifxy!=1 order by g.idate desc limit 0,8 ";
	}

	$Query = $DB->query($Sql);
	$i=1;
	$j=0;
	$abProductArray = array();

	while ($Rs =  $DB->fetch_array($Query)){
		$abProductArray[$j]['autonum']    = $i;
		$abProductArray[$j]['Bgcolor']    = $i%2==0 ?  "#FAFAFA" : 'white';
		$abProductArray[$j]['goodsname']  = $Rs['goodsname'];
		$abProductArray[$j]['gid']        = $Rs['gid'];
		$abProductArray[$j]['price']      = $Rs['price'];
		$abProductArray[$j]['pricedesc']  = $Rs['pricedesc'];
		$abProductArray[$j]['smallimg']   = $Rs['smallimg'];
		$abProductArray[$j]['middleimg']  = $Rs['middleimg'];
		$abProductArray[$j]['intro']      = nl2br($Rs['intro']);
		$i++;
		$j++;
	}
	$tpl->assign("abProductArray",      $abProductArray);    //相关产品数组


	//产品价格体系
	if (!($Result_goods['iftimesale']==1 && $Result_goods['timesale_starttime']<=time() && $Result_goods['timesale_endtime']>=time()) && $ifxygoods != 1 && !($Result_goods['ifsaleoff']==1 && $Result_goods['saleoff_starttime']<=time() && $Result_goods['saleoff_endtime']>=time())){
		$Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u ";
		$Query_level = $DB->query($Sql_level);
		$Num_level =   $DB->num_rows($Query_level);
		if ($Num_level>0){
			$i=0;
			
			while ($Result_level=$DB->fetch_array($Query_level))
			{
				if (intval($Result_level['pricerate'])>0){
					$PriceArray[$i]['level_name']  =   $Result_level['level_name'];
					$PriceArray[$i]['m_price']     =   round($Result_level['pricerate']*0.01*$Pricedesc,0);
					$i++;
				}
				
			}
			
		}
	}
	$tpl->assign("PriceArray",      $PriceArray);    //产品价格体系数组
	



	$Sql_Up =   "select g.gid,b.attr,g.goodsname,g.brand,g.brand_id,g.view_num,g.video_url,g.nocarriage,g.keywords,g.pricedesc,g.bn,g.ifgl,g.bid,g.unit,g.intro,g.price,g.point,g.body,g.middleimg,g.smallimg,g.bigimg,g.gimg,g.goodattr,g.good_color,g.good_size,g.ifrecommend,g.ifspecial,g.ifalarm,g.storage,g.alarmnum,g.ifbonus,g.ifhot,g.provider_id,g.ifjs,g.js_begtime,g.js_endtime,g.js_price,g.js_totalnum,p.provider_name,br.brandname,br.logopic from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)   where  b.catiffb='1' and g.ifpub='1' and g.bid='".$Bid."' and g.gid<'".$Goods_id."' limit 0,1";
	$Query_Up   = $DB->query($Sql_Up);
	$Num_Up     = $DB->num_rows($Query_Up);

	if ( $Num_Up>0 ) {
		$HaveUp    = "Yes";
		$Rs_Up    = $DB->fetch_array($Query_Up);
		$HaveUpId = $Rs_Up[gid];
	}

	$tpl->assign("HaveUp",$HaveUp);
	$tpl->assign("HaveUpId",$HaveUpId);




	$Sql_Next = "select g.gid,b.attr,g.goodsname,g.brand,g.brand_id,g.view_num,g.video_url,g.nocarriage,g.keywords,g.pricedesc,g.bn,g.ifgl,g.bid,g.unit,g.intro,g.price,g.point,g.body,g.middleimg,g.smallimg,g.bigimg,g.gimg,g.goodattr,g.good_color,g.good_size,g.ifrecommend,g.ifspecial,g.ifalarm,g.storage,g.alarmnum,g.ifbonus,g.ifhot,g.provider_id,g.ifjs,g.js_begtime,g.js_endtime,g.js_price,g.js_totalnum,p.provider_name,br.brandname,br.logopic from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)   where  b.catiffb='1' and g.ifpub='1' and g.bid='".$Bid."' and g.gid>'".$Goods_id."' limit 0,1";
	$Query_Next   = $DB->query($Sql_Next);
	$Num_Next   = $DB->num_rows($Query_Next);

	if ( $Num_Next>0 ) {
		$HaveNext    = "Yes";
		$Rs_Next    = $DB->fetch_array($Query_Next);
		$HaveNextId = $Rs_Next[gid];
	}

	$tpl->assign("HaveNext",$HaveNext);
	$tpl->assign("HaveNextId",$HaveNextId);

	$tpl->assign($Good);
	$tpl->assign($Email_Pack);
	$tpl->assign("Session_userlevel",$_SESSION['userlevelname']); //登陆后用户等级
	
	//echo $_SESSION['userlevelname'];
	//print_r($PriceArray);
	//分期付款價格
	$Sql_M    = "select * from `{$INFO[DBPrefix]}month_price` where goods_id=".intval($Goods_id)." group by month  order by month ";
	$Query_M  = $DB->query($Sql_M);
	$Num_M   = $DB->num_rows($Query_M);
	$j = 0;
	if ( $Num_M>0 ) {
		while ($Result_M =  $DB->fetch_array($Query_M)){
		if (intval($Result_M['month_price']) > 0){
			$monthpriceArray[$j]['month']    = $Result_M['month'];
			$monthpriceArray[$j]['month_price']  = $Result_M['month_price'];
			$monthpriceArray[$j]['total']        = $Result_M['month'] * $Result_M['month_price'];
			$j++;
		}
	}
	}
//echo $if_monthprice;
//print_r($monthpriceArray);
	$tpl->assign("monthpriceArray",$monthpriceArray);
	$tpl->assign("monthpriceNum",$Num_M);
	
	$Sql      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($Goods_id);
	$Query    = $DB->query($Sql);
	$Nums      = $DB->num_rows($Query);
	$i = 0;
	while($Result =  $DB->fetch_array($Query)){
		$storage_array[$i]['i'] = $i;
		$storage_array[$i]['color'] = $Result['color'];
		$storage_array[$i]['size'] = $Result['size'];
		$storage_array[$i]['storage'] = $Result['storage'];
		$i++;
	}
	$tpl->assign("storage_array",$storage_array);
	//print_r($storage_array);
	
	$Sql      = "select *  from `{$INFO[DBPrefix]}goodcolor` where good_id=" . intval($Goods_id);
	$Query    = $DB->query($Sql);
	$Nums      = $DB->num_rows($Query);
	$i = 0;
	while($Result =  $DB->fetch_array($Query)){
		$goodcolor_array[$i]['i'] = $i;
		$goodcolor_array[$i]['color'] = $Result['color'];
		$goodcolor_array[$i]['pic1'] = $Result['pic1'];
		$goodcolor_array[$i]['pic2'] = $Result['pic2'];
		$i++;
	}
	$tpl->assign("goodcolor_array",$goodcolor_array);

	
$Query   = $DB->query("select info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=4 limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num>0){
 $Result_Article = $DB->fetch_array($Query);
 $Content = $Result_Article['info_content'];
 }
 $tpl->assign("YunSong",      $Content); 
 $Query   = $DB->query("select info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=5 limit 0,1");
$Num   = $DB->num_rows($Query);
if ($Num>0){
 $Result_Article = $DB->fetch_array($Query);
 $Content = $Result_Article['info_content'];
 }
 $tpl->assign("FuKuan",      $Content); 
 
 /*
 商品規格
 */
 $Sql      = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . intval($goods_id) . "' order by detail_id desc ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$i = 0;
while ( $Detail_Rs = $DB->fetch_array($Query)){
	$goods_detail[$i]['detail_bn'] = $Detail_Rs['detail_bn'];
	$goods_detail[$i]['detail_name'] = $Detail_Rs['detail_name'];
	$goods_detail[$i]['detail_des'] = $Detail_Rs['detail_des'];
	$goods_detail[$i]['detail_price'] = $Detail_Rs['detail_price'];
	$goods_detail[$i]['detail_pricedes'] = $Detail_Rs['detail_pricedes'];
	$goods_detail[$i]['storage'] = $Detail_Rs['storage'];
	$goods_detail[$i]['detail_id'] = $Detail_Rs['detail_id'];
	$goods_detail[$i]['detail_pic'] = $Detail_Rs['detail_pic'];
	
	//产品价格体系

	$Sql_level   = "select u.* from `{$INFO[DBPrefix]}user_level` u ";
	$Query_level = $DB->query($Sql_level);
	$Num_level =   $DB->num_rows($Query_level);
	if ($Num_level>0){
		$j=0;
		while ($Result_level=$DB->fetch_array($Query_level))
		{
			if (intval($Result_level['pricerate'])>0){
				$goods_detail[$i][memberprice][$j]['level_name']  =   $Result_level['level_name'];
				$goods_detail[$i][memberprice][$j]['m_price']     =    round($Result_level['pricerate']*0.01*$Detail_Rs['detail_pricedes'],0);
				$j++;
			}
			
		}
	}
	
	$i++;
}
 $tpl->assign("goods_detail",      $goods_detail); 
 $tpl->assign("detail_num",      $Num); 
 
 $Query = $DB->query("select brandname,logopic,brand_id,brandcontent from `{$INFO[DBPrefix]}brand` where brand_id=".intval($Brand_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$brandname    =  trim($Result['brandname']);
		$logopic      =  trim($Result['logopic']);
		$brand_id     =  intval($Result['brand_id']);
		$brandcontent =  $Result['brandcontent'];
		}
		$tpl->assign("brandcontent",      $brandcontent); 




//超值任選
$xygoods_array = array();
$i = 0;
if ($ifxygoods == 1){
	$Sql         = "select gl.* ,g.goodsname,g.bn,g.smallimg,g.good_color,g.good_size,g.storage from `{$INFO[DBPrefix]}goods_xy` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.xygid=g.gid) where gl.gid=".intval($Goods_id)." order by gl.idate desc ";
	$Query       = $DB->query($Sql);
	$Num         = $DB->num_rows($Query);
	while ($Result=$DB->fetch_array($Query)){
		$Good_Color_Option = "";
		$Good_Size_Option = "";
		$xygoods_array[$i]['gid'] = $Result['xygid'];
		$xygoods_array[$i]['goodsname'] = $Result['goodsname'];
		$xygoods_array[$i]['bn'] = $Result['bn'];
		$xygoods_array[$i]['smallimg'] = $Result['smallimg'];
		$xygoods_array[$i]['good_color'] = $Result['good_color'];
		$xygoods_array[$i]['good_size'] = $Result['good_size'];
		$xygoods_array[$i]['storage'] = $Result['storage'];
		if (trim($Result['good_color'])!=""){
			$Good_color_array    =  explode(',',trim($Result['good_color']));
	
			if (is_array($Good_color_array)){
				foreach($Good_color_array as $k=>$v )
				{
					$Good_Color_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Color_Option = "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Color_Option = "";
		}
		$xygoods_array[$i]['Good_Color_Option'] = $Good_Color_Option;
		
		if (trim($Result['good_size'])!=""){
			$Good_size_array    =  explode(',',trim($Result['good_size']));
	
			if (is_array($Good_size_array)){
				foreach($Good_size_array as $k=>$v )
				{
					$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Size_Option = "";
		}
		$xygoods_array[$i]['Good_Size_Option'] = $Good_Size_Option;
		$i++;
	}
}

$tpl->assign("xygoods_array",     $xygoods_array);

//加购
$change_array = array();
$Good_color_array = array();
$Good_size_array = array();
$Good_Color_Option = "";
$Good_Size_Option = "";
$i = 0;
$Sql         = "select gl.* ,g.goodsname,g.bn,g.smallimg,g.good_color,g.good_size,g.ifchange from `{$INFO[DBPrefix]}goods_change` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.changegid=g.gid) where gl.gid=".intval($Goods_id)." and g.ifchange=1 order by gl.idate desc ";
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);
while ($Result=$DB->fetch_array($Query)){
		$change_array[$i]['gid'] = $Result['changegid'];
		$change_array[$i]['goodsname'] = $Result['goodsname'];
		$change_array[$i]['bn'] = $Result['bn'];
		$change_array[$i]['smallimg'] = $Result['smallimg'];
		$change_array[$i]['good_color'] = $Result['good_color'];
		$change_array[$i]['good_size'] = $Result['good_size'];
		$change_array[$i]['ifchange'] = $Result['ifchange'];
		$change_array[$i]['price'] = $Result['price'];
		if (trim($Result['good_color'])!=""){
			$Good_color_array    =  explode(',',trim($Result['good_color']));
	
			if (is_array($Good_color_array)){
				foreach($Good_color_array as $k=>$v )
				{
					$Good_Color_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Color_Option = "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Color_Option = "";
		}
		$change_array[$i]['Good_Color_Option'] = $Good_Color_Option;
		
		if (trim($Result['good_size'])!=""){
			$Good_size_array    =  explode(',',trim($Result['good_size']));
	
			if (is_array($Good_size_array)){
				foreach($Good_size_array as $k=>$v )
				{
					$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Size_Option = "";
		}
		$change_array[$i]['Good_Size_Option'] = $Good_Size_Option;
		$i++;
}

$tpl->assign("change_array",     $change_array);
$tpl->assign("goodstopid",     $top_id);

//買越多
$Sql      = "select * from `{$INFO[DBPrefix]}goods_saleoffe` where gid='" . intval($Goods_id) . "' order by mincount asc ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$i = 0;
while ($Rs=$DB->fetch_array($Query)) {
	$saleoffeprice_array[$i]['mincount'] = $Rs['mincount'];
	$saleoffeprice_array[$i]['maxcount'] = $Rs['maxcount'];
	$saleoffeprice_array[$i]['price'] = $Rs['price'];
	$i++;
}

$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='8' or info_id='12' limit 0,2");

while ($Result  = $DB->fetch_array($Query)){
  if ($Result[info_id]==8){
	$tpl->assign("Content_tr",        $Result[info_content]);      
  }
  if ($Result[info_id]==12){
	$tpl->assign("Content_ser",        $Result[info_content]);      
  }
}

$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='13' or info_id='13' limit 0,2");

while ($Result  = $DB->fetch_array($Query)){
	$tpl->assign("Content_bnaner",        $Result[info_content]);      
}

$tpl->assign("saleoffeprice_array",     $saleoffeprice_array);
	if ($Ifjs==0 && $ifxygoods==0 || $ifjsover==1){
		$tpl->display("goods_detail_buy.html");
	}elseif($Ifjs==1){
		$tpl->display("goods_detail_js.html");
	}elseif($ifxygoods==1){
		$tpl->display("goods_detail_xygoods.html");
	}

?>