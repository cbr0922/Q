<?php
error_reporting(7);
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$Goods_id  = $FUNCTIONS->Value_Manage($_GET['goods_id'],$_POST['Goods_id'],'back','');  //判断是否有正常的ID进入
$Goods_id  =intval($Goods_id);

	
$Sql =   "select g.* from `{$INFO[DBPrefix]}groupdetail` g inner join `{$INFO[DBPrefix]}groupclass` b on ( g.bid=b.bid ) where  g.gdid=".$Goods_id." limit 0,1";
$Query   = $DB->query($Sql);
$Num   = $DB->num_rows($Query);

if ( $Num==0 ) //如果不存在资料
$FUNCTIONS->header_location("product_class_second_groupon.php?bid=1");

if ($Num>0){
	$Result_goods = $DB->fetch_array($Query);
	$groupname = $Result_goods['groupname'];
	$View_num  = $Result_goods['view_num'];
	$Bid       = $Result_goods['bid'];
	$groupbn       = $Result_goods['groupbn'];
	$Intro     = nl2br($Result_goods['intro']);
	$Price     = $Result_goods['price'];
	$groupprice = $Result_goods['groupprice'];
	$Point     = intval($Result_goods['grouppoint']);
	$Body      = $Result_goods['content'];
	$Smallimg  = $Result_goods['groupSimg'];
	$Middleimg = $Result_goods['groupMimg'];
	$Gimg      = $Result_goods['groupBimg'];
	$goodslist  = $Result_goods['goodslist'];
	$poscode  = $Result_goods['poscode'];
	$salename = $Result_goods['salename'];

	if (trim($Intro) !="") {
		$Meta_desc = trim($Intro) ;
	}

	$Video_url = trim($Result_goods['groupVidio']);
	if (intval($sale_subject) >0){
	
		$Query_s = $DB->query("select subject_name,subject_content,salecount  from  `{$INFO[DBPrefix]}groupsubject`  where subject_id='".$sale_subject."'  limit 0,1");
	
		$Rs_s    = $DB->fetch_array($Query_s);
		$tpl->assign("Subject_name",          $Rs_s['subject_name']);              //主题名字
		$tpl->assign("Subject_content",       $Rs_s['subject_content']);           //主题内容
		$tpl->assign("salecount",          $Rs_s['salecount']);
		$tpl->assign("sale_subject",          $sale_subject);
	}
	$order_Sql = "select count(*) as sumcount from `{$INFO[DBPrefix]}order_group` where gdid='" . intval($Goods_id) . "'";
		$order_Query = $DB->query($order_Sql);
		$order_Rs = $DB->fetch_array($order_Query);
	//数据库变量
	$tpl->assign("salenum",   intval($order_Rs['sumcount'])); 
	$tpl->assign("Goods_id",   $Goods_id);    //商品id
	$tpl->assign("groupname",   $groupname); 
	$tpl->assign("View_num",   $View_num); 
	$tpl->assign("Bid",   $Bid); 
	$tpl->assign("groupbn",   $groupbn); 
	$tpl->assign("Intro",   $Intro); 
	$tpl->assign("Price",   $Price); 
	$tpl->assign("groupprice",   $groupprice); 
	$tpl->assign("Point",   $Point); 
	$tpl->assign("Body",   $Body); 
	$tpl->assign("Smallimg",   $Smallimg); 
	$tpl->assign("Middleimg",   $Middleimg); 
	$tpl->assign("Gimg",   $Gimg); 
	$tpl->assign("goodslist",   $goodslist); 
	$tpl->assign("poscode",   $poscode); 
	$tpl->assign("salename",   $salename); 
}
// 增加浏览次数1
$View_num= $View_num+1;
$DB->query("update `{$INFO[DBPrefix]}groupdetail` set view_num='".$View_num."' where gdid=".intval($Goods_id));
//$DB->query("update `{$INFO[DBPrefix]}goods` set view_num=view_num+1  where gid=".intval($Goods_id));

//多圖
$Sql_pic    = "select goodpic_name,goodpic_title from `{$INFO[DBPrefix]}group_pic` where good_id=".intval($Goods_id);
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
$class_banner = array();
$list = 0;
function getBanner($bid){
	global $DB,$INFO,$class_banner,$list,$Bcontent;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$class_banner[$list]['bid'] = $Result['bid'];
		$class_banner[$list]['catname'] = $Result['catname'];
		$class_banner[$list]['banner'] = $Result['banner'];
		$list++;
		if ($Result['top_id']>0)
			getBanner($Result['top_id']);
		else
			$Bcontent = $Result['catcontent'];
	}
}
if (intval($Bid)>0){
	getBanner($Bid);
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
	$tpl->assign("class_banner",     $class_banner);
	$tpl->assign("Bcontent",  $Bcontent);
	$tpl->assign("goodBid",  $class_banner[0][bid]);
}
$tpl->assign("banner",  $banner);

/**
 * 这里将输出影音位置
 */
if ( $Video_url!=""){

	$Array_Video = explode(".",$Video_url);
	$MaxNum_Video = intval(count($Array_Video)-1);
	$EMBED = "
	<EMBED id=mePlay style=\"BORDER-RIGHT: #666666 1px solid; BORDER-TOP: #666666 1px solid; BORDER-LEFT: #666666 1px solid; BORDER-BOTTOM: #666666 1px solid\" codeBase=http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,5,0803 src=\"" . $Video_url ."\"  type=application/x-oleobject classid=\"CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95\" standby=\"Loading Windows Media Player components...\" loop=\"2\">
	</EMBED>
	";
	$EMBED2 = "
	<object id=\"video2\" classid=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\" width=300 height=300> 
	<param name=\"_ExtentX\" value=\"11906\"> 
	<param name=\"_ExtentY\" value=\"8996\"> 
	<param name=\"AUTOSTART\" value=\"-1\"> 
	<param name=\"SHUFFLE\" value=\"0\"> 
	<param name=\"PREFETCH\" value=\"0\"> 
	<param name=\"NOLABELS\" value=\"0\"> 
	<param name=\"SRC\" value=\"{$Video_url}\"> 
	<param name=\"CONTROLS\" value=\"ImageWindow\"> 
	<param name=\"CONSOLE\" value=\"Clip1\"> 
	<param name=\"LOOP\" value=\"0\"> 
	<param name=\"NUMLOOP\" value=\"0\"> 
	<param name=\"CENTER\" value=\"0\"> 
	<param name=\"MAINTAINASPECT\" value=\"0\"> 
	<param name=\"BACKGROUNDCOLOR\" value=\"#000000\"> 
	<embed src=\"4.rpm\" type=\"audio/x-pn-realaudio-plugin\" console=\"Clip1\" controls=\"ImageWindow\" width=300 height=300 autostart=\"false\"></embed> 
	</object> 
	<object id=\"video1\" classid=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\" width=300 height=60> 
	<param name=\"_ExtentX\" value=\"11906\"> 
	<param name=\"_ExtentY\" value=\"1588\"> 
	<param name=\"AUTOSTART\" value=\"-1\"> 
	<param name=\"SHUFFLE\" value=\"0\"> 
	<param name=\"PREFETCH\" value=\"0\"> 
	<param name=\"NOLABELS\" value=\"0\"> 
	<param name=\"CONTROLS\" value=\"ControlPanel,StatusBar\"> 
	<param name=\"CONSOLE\" value=\"Clip1\"> 
	<param name=\"LOOP\" value=\"0\"> 
	<param name=\"NUMLOOP\" value=\"0\"> 
	<param name=\"CENTER\" value=\"0\"> 
	<param name=\"MAINTAINASPECT\" value=\"0\"> 
	<param name=\"BACKGROUNDCOLOR\" value=\"#000000\"> 
	<embed type=\"audio/x-pn-realaudio-plugin\" console=\"Clip1\" controls=\"ControlPanel,StatusBar\" width=300 height=60 autostart=\"false\"></embed> 
	</object>
	";
	$EMBED2 = "
	<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width=200 ><param name=movie value=\"" . $Video_url . "\"><param name=quality value=medium><PARAM NAME=wmode VALUE=transparent></object>
	";
	$EMBED3 = "<script type='text/javascript'>
	var swf_width=300
	var swf_height=300
	var texts='" . $Goodsname . "'
	var files='" . $Video_url . "'
	document.write('<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"'+ swf_width +'\" height=\"'+ swf_height +'\">');
	document.write('<param name=\"movie\" value=\"vcastr.swf\"><param name=\"quality\" value=\"high\">');
	document.write('<param name=\"menu\" value=\"false\"><param name=wmode value=\"opaque\">');
	document.write('<param name=\"FlashVars\" value=\"vcastr_file='+files+'&vcastr_title='+texts+'\">');
	document.write('<embed src=\"vcastr.swf\" wmode=\"opaque\" FlashVars=\"vcastr_file='+files+'&vcastr_title='+texts+'& menu=\"false\" quality=\"high\" width=\"'+ swf_width +'\" height=\"'+ swf_height +'\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />'); document.write('</object>'); 
	</script>
	";
	switch ($Array_Video[$MaxNum_Video]){
		case "wmv":
			$PlayFile = $EMBED;
			$PlayIcon = "02.gif";
			break;
		case "mp3":
			$PlayFile = $EMBED;
			$PlayIcon = "01.gif";
			break;
		case "rm":
			$PlayFile = $EMBED2;
			$PlayIcon = "03.gif";
			break;
		case "rmvb":
			$PlayFile = $EMBED2;
			$PlayIcon = "03.gif";
			break;
		case "swf":
			$PlayFile = $EMBED2;
			$PlayIcon = "04.gif";
			break;
		case "avi":
			$PlayFile = $EMBED;
			$PlayIcon = "04.gif";
			break;
		case "asf":
			$PlayFile = $EMBED;
			$PlayIcon = "04.gif";
			break;
        case "flv":
			$PlayFile = $EMBED3;
			$PlayIcon = "04.gif";
			break;
		default:
			$PlayIcon = "play_tw.gif";
			break;

	}

	//台湾的项目就是指定了一个图片
	$PlayIcon = "play_tw.gif";
/*
	$VideoUrl  = "\n <script language=javascript>function MM_openVideoWindow(theURL,winName,features)  {  window.open(theURL,winName,features); }</script> \n\n";
	$VideoUrl .= "<a href='###' onClick=\"MM_openVideoWindow('".$INFO[site_url]."/product/".$PlayFile."?id=".$Goods_id."','PlayVideo','width=580,height=480')\"><img src=\"".$INFO[site_url]."/templates/".$templates."/images/".$PlayIcon."\" alt=\"Play Video\" border=\"0\" /></a>";
    */
	$tpl->assign("VideoUrl",    $PlayFile);        //影音的地址
}
	
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

//買越多
$Sql      = "select * from `{$INFO[DBPrefix]}group_saleoffe` where gdid='" . intval($Goods_id) . "' order by mincount asc ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$i = 0;
while ($Rs=$DB->fetch_array($Query)) {
	$saleoffeprice_array[$i]['mincount'] = $Rs['mincount'];
	$saleoffeprice_array[$i]['maxcount'] = $Rs['maxcount'];
	$saleoffeprice_array[$i]['memberprice'] = $Rs['memberprice'];
	$saleoffeprice_array[$i]['grouppoint'] = $Rs['grouppoint'];
	$i++;
}
$tpl->assign("saleoffeprice_array",     $saleoffeprice_array);
$tpl->assign("saleoffeprice_Num",     $Num);

$Query   = $DB->query("select title,info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=10 limit 0,1");
$Num     = $DB->num_rows($Query);
if ($Num>0){
	$Result_Article = $DB->fetch_array($Query);
	$Content = $Result_Article['info_content'];
	$Title = $Result_Article['title'];
}
$tpl->assign("info_content",     $Content);
$tpl->display("goods_detail_groupon.html");
?>