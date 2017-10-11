<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");

$saleid = intval($_GET['saleid']);

$Query = $DB->query("select *  from  `{$INFO[DBPrefix]}groupsubject`  where gsid='".$saleid."' and subject_open=1 limit 0,1");
$Num      = $DB->num_rows($Query);
if ($Num<=0)
	exit;
	
while ($Rs    = $DB->fetch_array($Query) ){
	$template = $Rs['template'];
	$tpl->assign("Subject_name",          $Rs['subject_name']);              //主题名字
	$tpl->assign("Subject_content",       $Rs['subject_content']);           //主题内容
	$tpl->assign("start_date",          $Rs['start_date']);
	$tpl->assign("end_date",          $Rs['end_date']);
	$tpl->assign("min_money",          $Rs['min_money']);
	$tpl->assign("min_count",          $Rs['min_count']);
	$tpl->assign("mianyunfei",          $Rs['manyunfei']);
	$tpl->assign("grouppoint",          $Rs['grouppoint']);
	$tpl->assign("buygrouppoint",          $Rs['buygrouppoint']);
	$manyunfei = $Rs['manyunfei'];
	$min_money = $Rs['min_money'];
	$min_count = $Rs['min_count'];
}

$viewProductArray = array();
$total_price = 0;
$total_count = 0;
	if (isset($_COOKIE['groupgoods'])){
		$acount = count($_COOKIE['groupgoods'][$saleid]);
		$J = 0;
		//echo $_GET['bid'];
		//for($i=0;$i<$acount;$i++){
			//print_r($_COOKIE);
		if (is_array($_COOKIE['groupgoods'][$saleid] )){
			$Next_ArrayClass  = $FUNCTIONS->Sun_groupcon_class(intval($_GET['bid']));
			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$Array_class      = array_unique($Next_ArrayClass);
			
			foreach ($Array_class as $k=>$v){
				$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
			}

		foreach($_COOKIE['groupgoods'][$saleid] as $k=>$v){
				if (intval($_COOKIE['groupgoods'][$saleid][$k]) > 0){
					$Sql = "select g.groupname, g.groupbn, g.groupSimg,dg.*,g.gdid,g.memberprice,g.grouppoint from `{$INFO[DBPrefix]}groupgoods` as dg inner join `{$INFO[DBPrefix]}groupdetail` as g on dg.gid=g.gdid where g.gdid='" . $_COOKIE['groupgoods'][$saleid][$k] . "' and dg.gsid='" . $saleid . "' and g.ifpub=1 and ( g.bid='" . intval($_GET['bid']) . "' " . $Add . ")  order by g.pubtime desc ";
					$Query = $DB->query($Sql);
					$Num      = $DB->num_rows($Query);
					if ($Num>0){
					$Rs =  $DB->fetch_array($Query);
					$viewProductArray[$J][gid] = $Rs['gdid'];
					$viewProductArray[$J]['key'] = $k;
					$viewProductArray[$J]['buytype'] = intval($_COOKIE['groupgoods_buytype'][$saleid][$k]);
					$viewProductArray[$J][groupname] = $Rs['groupname'];
					$viewProductArray[$J][groupbn] = $Rs['groupbn'];
					$viewProductArray[$J][price] = $Rs['price'];
					$viewProductArray[$J][grouppoint] = $Rs['grouppoint'];
					$viewProductArray[$J][smallimg] = $Rs['groupSimg'];
					$viewProductArray[$J][pricedesc] = $Rs['pricedesc'];
					$viewProductArray[$J][grouppoint] = $Rs['grouppoint'];
					$viewProductArray[$J][memberprice] = $Rs['memberprice'];
					$viewProductArray[$J]['count'] = $_COOKIE['groupgoods_count'][$saleid][$k];
					$viewProductArray[$J]['color'] = $_COOKIE['groupgoods_color'][$saleid][$k];
					$viewProductArray[$J]['size'] = $_COOKIE['groupgoods_size'][$saleid][$k];
					if(intval($_COOKIE['groupgoods_buytype'][$saleid][$k]) == 0){
						$total_price += $Rs['price']*$viewProductArray[$J]['count'];
					}else{
						$total_price += $Rs['memberprice']*$viewProductArray[$J]['count'];
						$total_point += $Rs['grouppoint']*$viewProductArray[$J]['count'];
					}
					$total_count += $viewProductArray[$J]['count']; 
					$J++;
					}
				}
		}
		}
	}
	$mygrouppoint =$FUNCTIONS->Grouppoint(intval($_SESSION['user_id']));
		//print_r($viewProductArray);										   
$tpl->assign("mygrouppoint",            $mygrouppoint); 
	$haveyunfei = ($manyunfei - $total_price)>0?($manyunfei - $total_price):0;
	$havemoney = ($min_money - $total_price)>0?($min_money - $total_price):0;
	$havecount = ($min_count - $totatotal_countl_price)>0?($min_count - $total_count):0;
	$tpl->assign("SaleProductArray",      $viewProductArray); 
	$tpl->assign("havecount",      $total_count); 
	$tpl->assign("havecounts",      $havecount); 
	$tpl->assign("havemoney",      $havemoney); 
	$tpl->assign("total_price",      $total_price); 
	$tpl->assign("haveyunfei",      $haveyunfei); 
	$tpl->assign("total_point",      $total_point); 
	$tpl->assign("sesstion_uid",      $_SESSION['user_id']); 
	$tpl->assign("times",      time()); 
	//print_r($_COOKIE['groupgoods']);
$tpl->display("shopping_group2.html");
?>