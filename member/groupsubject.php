<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");

$bid  =intval($_GET['bid']);

$class_banner = array();
$list = 0;
function getBanner($bid){
	global $DB,$INFO,$class_banner,$list,$Bcontent;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}groupclass` where bid=".intval($bid)." limit 0,1 ");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result     =  $DB->fetch_array($Query);
		$class_banner[$list]['bid'] = $Result['bid'];
		$class_banner[$list]['catname'] = $Result['catname'];
		$class_banner[$list]['banner'] = $Result['banner'];
		if($Result['catcontent']!="" && $Bcontent == "")
			$Bcontent = $Result['catcontent'];
		$list++;
		if ($Result['top_id']>0)
			getBanner($Result['top_id']);
		
	}
}

if (intval($bid)>0){
	getBanner($bid);
	$class_banner = array_reverse($class_banner);
	$banner = $class_banner[0][banner];
	$tpl->assign("class_banner",     $class_banner);
	$tpl->assign("Bcontent",  $Bcontent);
	$tpl->assign("top_Bid",  $class_banner[0][bid]);
}

$Next_ArrayClass  = $FUNCTIONS->Sun_groupcon_class($bid);
$Next_ArrayClass  = explode(",",$Next_ArrayClass);
$Array_class      = array_unique($Next_ArrayClass);

foreach ($Array_class as $k=>$v){
	$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
}

$Sql_sub   = " select * from `{$INFO[DBPrefix]}groupsubject` where subject_open=1";
$Query_sub = $DB->query($Sql_sub);
$Array_sub = array();
$sub_i = 0;
while ($Rs_sub = $DB->fetch_array($Query_sub) ){
	$Array_sub[$sub_i][dsid]    =  $Rs_sub['gsid'];
	$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
	$Array_sub[$sub_i][start_date]  =  $Rs_sub['start_date'];
	$Array_sub[$sub_i][end_date]  =  $Rs_sub['end_date'];
	$Array_sub[$sub_i][min_money]  =  $Rs_sub['min_money'];
	$Array_sub[$sub_i][min_count]  =  $Rs_sub['min_count'];
	$Array_sub[$sub_i][grouppoint]  =  $Rs_sub['grouppoint'];
	$Array_sub[$sub_i][mianyunfei]  =  $Rs_sub['manyunfei'];
	$sub_i++;
}

$tpl->assign("Array_sub",       $Array_sub);           //主题类别名称循环

$Subject_id = intval($_GET['subject_id'])>0 ? intval($_GET['subject_id']) : intval($INFO[subject_id]);

$Query = $DB->query("select *  from  `{$INFO[DBPrefix]}groupsubject`  where gsid='".$Subject_id."' and subject_open=1 limit 0,1");
$Num      = $DB->num_rows($Query);
if ($Num<=0)
	$FUNCTIONS->sorry_back('back',"團購專區關閉");
	
while ($Rs    = $DB->fetch_array($Query) ){
	$template = $Rs['template'];
	//print_r($Rs);
	$tpl->assign("Subject_name",          $Rs['subject_name']);              //主题名字
	$tpl->assign("Subject_content",       $Rs['subject_content']);           //主题内容
	$tpl->assign("start_date",          $Rs['start_date']);
	$tpl->assign("end_date",          $Rs['end_date']);
	$tpl->assign("min_money",          $Rs['min_money']);
	$tpl->assign("min_count",          $Rs['min_count']);
	$tpl->assign("mianyunfei",          $Rs['manyunfei']);
	$tpl->assign("grouppoint",          $Rs['grouppoint']);
	
}

include("PageNav.class.php");

$goods_array = array();
$goods_count_array = array();
if (is_array($_COOKIE['groupgoods'][$Subject_id])){
	$goods_id_array = $_COOKIE['groupgoods'][$Subject_id];
	$goods_count_array = $_COOKIE['groupgoods_count'][$Subject_id];
}


$Sql = "select g.groupname, g.groupbn, g.groupSimg,dg.grouppoint,dg.price as groupprice,g.gdid,g.price,g.goodslist from `{$INFO[DBPrefix]}groupgoods` as dg inner join `{$INFO[DBPrefix]}groupdetail` as g on dg.gid=g.gdid where dg.gsid='" . $Subject_id . "' and g.ifpub=1 and ( g.bid='" . $bid . "' " . $Add . ")  order by g.pubtime desc ";

$PageNav = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;
$sale_p_array = array();
if ($Num>0){
	$i=0;
	$j=1;
	while ( $ProNav = $DB->fetch_array($arrRecords)){
		$sale_p_array[$i]['count'] = 0;
		if (in_array(intval($ProNav['gdid']),$goods_id_array)){
			foreach($goods_id_array as $k=>$v){
				if (intval($ProNav['gdid']) == $v){
					$sale_p_array[$i]['count']        = $goods_count_array[$k] ;
					$sale_p_array[$i]['buykey']    = $k;
				}
			}
		}
		$goods_array = explode(",",$ProNav['goodslist']);
		if (count($goods_array)==1){
			//屬性
			$Gsql = "select good_color,good_size from `{$INFO[DBPrefix]}goods` where bn='" . $goods_array[0] . "'";
			$gQuery = $DB->query($Gsql);
			$gRs = $DB->fetch_array($gQuery);
			if (trim($gRs['good_color'])!=""){
				$Good_color_array    =  explode(',',trim($gRs['good_color']));
		
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
		
			$sale_p_array[$i]['color'] =  $Good_Color_Option;
			if (trim($gRs['good_size'])!=""){
				$Good_size_array    =  explode(',',trim($gRs['good_size']));
		
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
		
		
			$sale_p_array[$i]['size'] =  $Good_Size_Option;
		}
		$sale_p_array[$i][groupname] = $ProNav['groupname'];
		$sale_p_array[$i][groupbn] = $ProNav['groupbn'];
		$sale_p_array[$i][gdid] = $ProNav['gdid'];
		$sale_p_array[$i][groupprice] = $ProNav['groupprice'];
		$sale_p_array[$i][price] = $ProNav['price'];
		$sale_p_array[$i][grouppoint] = $ProNav['grouppoint'];
		$sale_p_array[$i][smallimg] = $ProNav['groupSimg'];
		$i++;
		$j++;
	}
	$tpl->assign("ProductPageItem",       $PageNav->myPageItem()); 
}
//print_r($sale_p_array);
$tpl->assign("sesstion_uid",      $_SESSION['user_id']); 
$tpl->assign("Session_truename", $_SESSION['true_name']);
if(intval($_SESSION['user_id']))
	$point =$FUNCTIONS->Grouppoint(intval($_SESSION['user_id']),1);
$tpl->assign("sumpoint",    intval($point)); 
    //商品翻页条
$tpl->assign("sale_p_array",       $sale_p_array);
$tpl->assign($Good);
if ($template=="")
	$template = "groupsubject.html";
$tpl->display($template);
?>