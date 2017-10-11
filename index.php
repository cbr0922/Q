<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include (dirname(__FILE__)."/configs.inc.php");
include (Classes."/global.php");
include "./language/".$INFO['IS']."/Good.php";
if($_GET['hometype']=="groupon"){
	$FUNCTIONS->header_location("index_groupon.php");
	exit;
}

include("product.class.php");
$PRODUCT = new PRODUCT();

$New_productarray = $PRODUCT->getNewProduct();   //得到最新商品
$Recommendation_productarray = $PRODUCT->getRecProduct();   //得到推荐商品
$tpl->assign("New_productarray",  $New_productarray);
$tpl->assign("Recommendation_productarray",  $Recommendation_productarray);

/**
 * 这里是主页的标签公告ID,及高度
 */
$tpl->assign("index_iframe_height",  $INFO[index_iframe_height]);
$tpl->assign("index_iframe_id",  $INFO[index_iframe_id]);

/**
 *主页面LOGO的尺寸
 */
$tpl->assign("logo_width",  $INFO["logo_width"]);
$tpl->assign("logo_height", $INFO["logo_height"]);

/**
 * 如当前商店设定的模板不存在，将出现提醒信息，并要求用户请稍候访问……"
 */
function writableCell( $folder ) {
	global $TEMPLATES;
	if (!is_writable( "$folder" )){
		$Tpl_err = '';
		$Tpl_err = '<table width=80% align=center border=1><tr>';
		$Tpl_err.= '<td width=350>' . $folder . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		$Tpl_err.= '<td align=left>';
		$Tpl_err.= "<b><font color='red'>".$TEMPLATES[NoTemplates]."</font></b></td>";
		$Tpl_err.= '</tr></table>';
		echo  $Tpl_err;
	}
}

writableCell( "templates/".$templates."/templates_c");

$INFO['MaxNewProductNum'] = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 10;

$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=6 and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') order by RAND()";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$adv_array = array();
$i = 0;
while($Result = $DB->fetch_array($Query)){
	$adv_array[$i]['img'] = $Result['adv_content'];
	$adv_array[$i]['url'] = $Result['adv_left_url']==""?"#":$INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$Result['adv_id']  . "&url=" .urlencode($Result['adv_left_url']);
	$adv_array[$i]['title'] = $Result['adv_title'];
	$DB->query("update `{$INFO[DBPrefix]}advertising` set point_num=point_num+1 where adv_id=".intval($Result['adv_id']));
	$i++;
}

$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("album_radio",               intval($INFO['album_radio']));                    //首頁顯示相簿开关
$tpl->assign("Pop_radio",               	intval($INFO['pop_radio']));                    	//彈出广告开关
$tpl->assign("Pop_adv_tag",               $INFO['pop_adv_tag']);                    				//彈出广告tag


$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=3 and adv_tag='banner_right' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') order by orderby limit 0,6";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);
$adv_array = array();
$i = 0;
for($hang=0;$hang<3;$hang++){
	for($lie=0;$lie<2;$lie++){
		$Result = $DB->fetch_array($Query);
		//if($Result['adv_content']!=""){
			$adv_array[$hang]['adv'][$lie]['img'] = $Result['adv_content'];
			$adv_array[$hang]['adv'][$lie]['url'] = $Result['adv_left_url']==""?"#":$INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$Result['adv_id']  . "&url=" .urlencode($Result['adv_left_url']);
			$adv_array[$hang]['adv'][$lie]['title'] = $Result['adv_title'];
			$DB->query("update `{$INFO[DBPrefix]}advertising` set point_num=point_num+1 where adv_id=".intval($Result['adv_id']));
		//}
	}
}
$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='16'  limit 0,1");

while ($Result  = $DB->fetch_array($Query)){
	$tpl->assign("Content_16",        $Result[info_content]);
}

// 首頁自由編輯區
$info_id = '23';
$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='23' ");
while ($Result  = $DB->fetch_array($Query)){
  if ($Result[info_id]==23){
	$tpl->assign("index_freezone1",        $Result[info_content]);
  }
}
$info_id = '25';
$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where  info_id='25' ");
while ($Result  = $DB->fetch_array($Query)){
  if ($Result[info_id]==25){
	$tpl->assign("index_freezone2",        $Result[info_content]);
  }
}

//熱門快搜
$class_sql = "select a.attrid,a.attributename from `{$INFO[DBPrefix]}attribute` as a";
	$Query_class    = $DB->query($class_sql);
	$ic = 0;
	$attr_class = array();
	while($Rs_class=$DB->fetch_array($Query_class)){
		$attr_class[$ic]['attrid']=$Rs_class['attrid'];
		$attr_class[$ic]['bid']=$bid;
		$attr_class[$ic]['attributename']=$Rs_class['attributename'];
		$Sql_value      = "select * from `{$INFO[DBPrefix]}attributevalue` as v inner join `{$INFO[DBPrefix]}attribute` as a on a.attrid=v.attrid where v.attrid='" . intval($Rs_class['attrid']) . "' order by valueid desc ";
		$Query_value     = $DB->query($Sql_value );
		$iv = 0;
		while ($Rs_value =$DB->fetch_array($Query_value)) {
			$attr_class[$ic]['value'][$iv]['valueid'] = $Rs_value['valueid'];
			$attr_class[$ic]['value'][$iv]['value'] = $Rs_value['value'];
			if($_GET['valueid'] == $Rs_value['valueid']){
				$valuename = $Rs_value['value'];
				$valuecontent = $Rs_value['content'];
			}
			$iv++;
		}
		$ic++;
	}
	//print_r($attr_class);
	$tpl->assign("attr_array",  $attr_class);


//print_r($adv_array);
$tpl->assign("adv_r_array",     $adv_array);

$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=9 and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') and adv_tag='index_top1' order by orderby";
$Query = $DB->query($Sql);
$Result = $DB->fetch_array($Query);
$Adv_1_index = $Result['adv_left_img'];
$Adv_1_index_title = $Result['adv_title'];

$tpl->assign("Adv_1_index",       $Adv_1_index);
$tpl->assign("Adv_1_index_title",       $Adv_1_index_title);
$Adv_1_index_url = $Result['adv_left_url'];
$tpl->assign("Adv_1_index_url",       $Adv_1_index_url);

$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=9 and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') and adv_tag='index_top2' order by orderby";
$Query = $DB->query($Sql);
$Result = $DB->fetch_array($Query);
$Adv_2_index = $Result['adv_left_img'];
$Adv_2_index_title = $Result['adv_title'];
$tpl->assign("Adv_2_index",       $Adv_2_index);
$tpl->assign("Adv_2_index_title",       $Adv_2_index_title);
$Adv_2_index_url = $Result['adv_left_url'];
$tpl->assign("Adv_2_index_url",       $Adv_2_index_url);

$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=9 and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') and adv_tag='index_medium1' order by orderby";
$Query = $DB->query($Sql);
$Result = $DB->fetch_array($Query);
$Adv_3_index = $Result['adv_left_img'];
$Adv_3_index_title = $Result['adv_title'];
$tpl->assign("Adv_3_index",       $Adv_3_index);
$tpl->assign("Adv_3_index_title",       $Adv_3_index_title);
$Adv_3_index_url = $Result['adv_left_url'];
$tpl->assign("Adv_3_index_url",       $Adv_3_index_url);

$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=9 and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') and adv_tag='index_medium2' order by orderby";
$Query = $DB->query($Sql);
$Result = $DB->fetch_array($Query);
$Adv_4_index = $Result['adv_left_img'];
$Adv_4_index_title = $Result['adv_title'];
$tpl->assign("Adv_4_index",       $Adv_4_index);
$tpl->assign("Adv_4_index_title",       $Adv_4_index_title);
$Adv_4_index_url = $Result['adv_left_url'];
$tpl->assign("Adv_4_index_url",       $Adv_4_index_url);

$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=9 and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') and adv_tag='index_medium3' order by orderby";
$Query = $DB->query($Sql);
$Result = $DB->fetch_array($Query);
$Adv_5_index = $Result['adv_left_img'];
$Adv_5_index_title = $Result['adv_title'];
$tpl->assign("Adv_5_index",       $Adv_5_index);
$tpl->assign("Adv_5_index_title",       $Adv_5_index_title);
$Adv_5_index_url = $Result['adv_left_url'];
$tpl->assign("Adv_5_index_url",       $Adv_5_index_url);

//萬用格廣告
		$indexAdv_array = array();
		$i = 0;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}index_banner` where tag='index' order by bannerorder asc");
		$Num   = $DB->num_rows($Query);
		while($Result= $DB->fetch_array($Query)){
			$indexAdv_array[$i] = $Result;
			$indexAdv_array[$i]['col'] = 12/$Result['bannercount'];
			for($j=1;$j<=$Result['bannercount'];$j++){
				$Query_adv = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='adv_home" . $Result['ib_id'] . "_" . $j . "' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') limit 0,1");
				$Num_adv   = $DB->num_rows($Query_adv);
				$Result_adv= $DB->fetch_array($Query_adv);
				if($Result_adv['adv_type']!=21)
					$indexAdv_array[$i]['adv'][$j-1] = $Result_adv;
				else{
					$indexAdv_array[$i]['adv'][$j-1]['adv_type'] = $Result_adv['adv_type'];
					$z = 0;
					$Query_adv = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='adv_home" . $Result['ib_id'] . "_" . $j . "' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "')");
					while($Result_adv= $DB->fetch_array($Query_adv)){
						$indexAdv_array[$i]['adv'][$j-1]['img'][$z] = $Result_adv;
						$z++;
					}
					//print_r($indexAdv_array[$i]['adv'][$j-1]['img']);
				}
			}
			$i++;
		}
		//print_r($indexAdv_array);
$tpl->assign("indexAdv_array",$indexAdv_array);
$tpl->assign($Good);
/* FB像素ViewContent事件 */
$track_id = '5';
$Sql_track = "SELECT * FROM `{$INFO[DBPrefix]}track`  where trid='".intval($track_id)."' limit 0,1";
$Query   = $DB->query($Sql_track);
while ($track_array  = $DB->fetch_array($Query)){

  if ($track_array[trid]==$track_id && trim($track_array[trackcode])!="" ){
	$track_Js = "<script>fbq('track', 'ViewContent');</script>";
  }
	else $track_Js="";
	$tpl->assign("ViewContent_js",   $track_Js);
}

$tpl->display("index.html");



?>
