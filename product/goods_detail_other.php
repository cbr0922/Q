<?php
error_reporting(7);
include("../configs.inc.php");
include("global.php");
include "../language/".$INFO['IS']."/Good.php";
include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");
@header("Content-type: text/html; charset=utf-8");

$Goods_id  = $FUNCTIONS->Value_Manage($_GET['goods_id'],$_POST['Goods_id'],'back','');  //判断是否有正常的ID进入

$Sql =   "select b.attr,g.goodsname,g.brand_id,g.view_num,g.video_url,g.nocarriage,g.keywords,g.pricedesc,g.bn,g.ifgl,g.bid,g.unit,g.intro,g.price,g.point,g.body,g.middleimg,g.smallimg,g.bigimg,g.gimg,g.goodattr,g.good_color,g.good_size,g.ifrecommend,g.ifspecial,g.ifalarm,g.storage,g.alarmnum,g.ifbonus,g.ifhot,g.provider_id,g.ifjs,g.js_begtime,g.js_endtime,g.js_price,g.js_totalnum,p.provider_name,br.brandname,br.logopic,g.if_monthprice,g.cap_des,g.ifxygoods,g.xycount,g.sale_name from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)   where  b.catiffb=1 and g.ifpub=1 and g.gid='".intval($Goods_id)."' limit 0,1";
$Query   = $DB->query($Sql);
$Num   = $DB->num_rows($Query);

if ( $Num==0 ) //如果不存在资料
$FUNCTIONS->header_location("index.php");

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
		for($i=0;$i<$Attr_num;$i++){
			$AttrArray[$i]        = $Attr[$i];
			$ProductArray[$i]     = $Goods_Attr[$i];
			$ProductAttrArray[$i] = array($Attr[$i]=>$Goods_Attr[$i]);
		}
	}
	$tpl->assign("ProductAttrArray",      $ProductAttrArray);    //循环多属性部分数组




	//print_r ($ProductAttrArray);


	$Goodsname = $Result_goods['goodsname']."".$FUNCTIONS->Storage($Result_goods['ifalarm'],$Result_goods['storage'],$Result_goods['alarmnum']);
	$NoCarriage= $Result_goods['nocarriage'];
	if (trim($Result_goods['brandname'])!=""){
		if ($INFO['staticState'] ==	'open'){
			$Brand     =  "<a href='".$INFO[site_url]."/HTML_C/brand_list_".intval($Result_goods['brand_id'])."_0.html'>".$Result_goods['brandname']."</a>";
		}else{
			$Brand     =  "<a href='".$INFO[site_url]."/brand/".$Result_goods['brand_id']."'>".$Result_goods['brandname']."</a>";
		}
		$Brand_id  = $Result_goods['brand_id'];
	}

	$View_num  = $Result_goods['view_num'];
	$Bn        = $Result_goods['bn'];
    $Keywords  = trim($Result_goods['keywords']);
	$Ifgl      = $Result_goods['ifgl'];
	$Bid       = $Result_goods['bid'];
	$Unit      = $Result_goods['unit'];
	$Intro     = $Char_class->cut_str($Result_goods['intro'],200,0,'UTF-8');
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
	$sale_name = $Result_goods['salename_color']==""?$Result_goods['sale_name']:"<font color='" . $Result_goods['salename_color'] . "'>" . $Result_goods['sale_name'] . "</font>";

	if ((intval($Result_goods['ifjs'])==1 && ($Result_goods['js_begtime']>=date("Y-m-d",time()) || $Result_goods['js_endtime']<=date("Y-m-d",time())))){
		echo $Good[AlertZeroExDate]; //【【集殺商品已過期】】
		echo "<br>  <a href='javascript:window.history.back(-1);'>Back</a>";
		exit;
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



/*  这里是当有用户发表评论的时候发生的操作!!!
if ($_POST['action']='SubmitComment' && !empty($_POST['Goods_id'])){
$Result = $DB->query(" insert into `{$INFO[DBPrefix]}good_comment` (good_id,comment_content,comment_idate) values ('".intval($_POST['Goods_id'])."','".$_POST['content']."','".time()."')" );
if ($Result){
if ($INFO['staticState']=='open'){

//$FUNCTIONS->sorry_back($_POST['Url'],"");
$FUNCTIONS->header_location("../CreateHtml/admin_create_goods_detail.php?action=CreateOneProductHtml&gid={$Goods_id}&url=".base64_encode($_POST['Url']));
}
$FUNCTIONS->sorry_back($_POST['Url'],"");
}
}
*/



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
if ($Ifalarm>0 && $Storage <= 0){
	$tpl->assign("AlertZeroStorage",     $Good[AlertZeroStorageText]);        //库存
}





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



	$tpl->assign("viewProductArray",      $viewProductArray);
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

if (intval($Ifgl)==1){ //判断是否是指定了产品内容，如果没有就把本类产品资料都调出来
	$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc,gl.s_gid from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}good_link` gl  on (g.gid=gl.s_gid) where g.ifpub=1 and g.ifpresent!=1 and gl.p_gid=".intval($Goods_id);}else{
		$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.pricedesc,g.intro from `{$INFO[DBPrefix]}goods` g where g.bid=".$Bid." and g.gid!='".intval($Goods_id)."' and g.ifpub=1 and g.ifpresent!=1 and g.ifchange!=1 and g.ifxy!=1 order by g.idate desc limit 0,8 ";
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
$tpl->assign("Goodpic",   $Goodpic);
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

$tpl->assign("goodstopid",     $top_id);

$tpl->display("goods_detail_other.html");

?>
