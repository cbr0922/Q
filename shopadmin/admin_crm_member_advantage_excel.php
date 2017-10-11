<?php
@ob_start();
ini_set("memory_limit", "100M");
include "Check_Admin.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
include_once 'crypt.class.php';
require_once '../Resources/phpexcel/PHPExcel.php';

$objExcel = new PHPExcel();
$objExcel->setActiveSheetIndex(0);
$objActSheet = $objExcel->getActiveSheet();
$objExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
$objActSheet->setTitle('會員匯出');
$FUNCTIONS->setLog("會員匯出");

$member_excel_out = explode(",",$INFO['member_excel_out']);

//$objActSheet->setCellValueExplicit('A5',$_GET['post_county'],PHPExcel_Cell_DataType::TYPE_STRING);
$row = 1;
$i = 0;
foreach($member_excel_out as $k=>$v){
	$objActSheet->setCellValue(getCellName($i,$row), $member_field[$v]);
	$i++;
}

$Sql = "select u.* from `{$INFO[DBPrefix]}user` u";

$goods_starttime = $_POST['post_goods_starttime'];
$goods_endtime = $_POST['post_goods_endtime']." 23:59:59";

if ( ($_POST['post_goods_starttime'] != "") && ($_POST['post_goods_endtime'] != "")  ){

}
if ($_POST['post_gender']!="" && $_POST['post_gender']!="none"){
	if( $_POST['post_gender'] == "male"){
		$Sex_string = " sex='".intval("0")."' ";
	}
	else if( $_POST['post_gender'] == "female"){
		$Sex_string = " sex='".intval("1")."' ";
	}
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Sex_string);
}
if ($_POST['post_login_count'] != 0 && isset($_POST['post_login_count'])){
	$login_count = $_POST['post_login_count'];
	$Sql.=" JOIN (SELECT `user_id` FROM `{$INFO[DBPrefix]}user_log` WHERE `logintime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `user_id` HAVING COUNT(*) >= '".$login_count."') l ON (u.`user_id`=l.`user_id`)";
}
if ( ($_POST['post_buymoney_start'] != "") && ($_POST['post_buymoney_end'] != "")  ){
	$buymoney_start = $_POST['post_buymoney_start'];
	$buymoney_end = $_POST['post_buymoney_end'];
	if($buymoney_start == 0 && $buymoney_end == 0){
		$Sql.=" JOIN (SELECT a.`user_id` FROM `{$INFO[DBPrefix]}user` a LEFT JOIN ";
		$Sql.="(SELECT `user_id` FROM `{$INFO[DBPrefix]}order_table` WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND order_state=4 GROUP BY `user_id` HAVING SUM(`totalprice`) > 0) b ON (a.`user_id`=b.`user_id`) where b.`user_id` is null) t ON (u.`user_id`=t.`user_id`)";
	}else{
		$Sql.=" JOIN (SELECT `user_id`,SUM(`totalprice`) as totalprice FROM `{$INFO[DBPrefix]}order_table` WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND order_state=4 GROUP BY `user_id` HAVING SUM(`totalprice`) BETWEEN '".$buymoney_start."' AND '".$buymoney_end."') t ON (u.`user_id`=t.`user_id`)";
	}
}
if ($_POST['post_item_id'] != "" && isset($_POST['post_item_id'])){
	$item_id = $_POST['post_item_id'];
	$subSql = " AND  d.`gid` IN (" . implode(",",array_filter(explode(",",$item_id))) . ")";
	$Sql.=" JOIN (SELECT `user_id`,t.`order_id` FROM `{$INFO[DBPrefix]}order_table` t,`{$INFO[DBPrefix]}order_detail` d WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND t.`order_id` = d.`order_id` AND t.`order_state` = 4 ".$subSql." GROUP BY `user_id`) o ON (u.`user_id`=o.`user_id`)";
}
if ($_POST['post_recommendmember'] != 0 ){
	$recommendmember = $_POST['post_recommendmember'];
	$Sql.=" JOIN (SELECT `user_id`,`recommendno` FROM `{$INFO[DBPrefix]}user` WHERE `recommendno`!='') r ON (u.`user_id`=r.`user_id`)";

}
if ( ($_POST['post_bonusrecord_start'] != "") && ($_POST['post_bonusrecord_end'] != "")  ){
	$bonusrecord_start = $_POST['post_bonusrecord_start'];
	$bonusrecord_end = $_POST['post_bonusrecord_end'];
	$Sql.=" JOIN (SELECT a.`user_id` , (a.`point` - IFNull(b.`point`,0)) AS point FROM ";
	$Sql.="(SELECT SUM(`point`) AS point ,`user_id` FROM `{$INFO[DBPrefix]}bonuspoint` WHERE UNIX_TIMESTAMP('".$goods_starttime."') <= `addtime` AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') >= `addtime` AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') <= `endtime` AND `saleorlevel`=1 GROUP BY `user_id`) a LEFT JOIN ";
	$Sql.="(SELECT SUM(`usepoint`) AS point,bd.`user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` bd INNER JOIN `{$INFO[DBPrefix]}bonuspoint` bp ON bd.`combipoint_id`=bp.`id` WHERE UNIX_TIMESTAMP('".$goods_starttime." 23:59:59') >= bp.`addtime` AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') <= bp.`endtime` AND bd.`usetime` <= UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND bp.`saleorlevel`=1 GROUP BY bd.`user_id`) b ";
	$Sql.="ON a.`user_id`=b.`user_id` WHERE (a.`point` - IFNull(b.`point`,0)) BETWEEN '".$bonusrecord_start."' AND '".$bonusrecord_end."') p ON (u.`user_id`=p.`user_id`)";
}
if ($_POST['post_bonusrecord'] != "" ){
	$bonusbuydetail = $_POST['post_bonusrecord'];
	if( $bonusbuydetail == 0){
		$Sql.=" JOIN (SELECT a.`user_id` FROM `{$INFO[DBPrefix]}user` a LEFT JOIN ";
		$Sql.="(SELECT `user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` WHERE `usetime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `user_id`) b ON (a.`user_id`=b.`user_id`) where b.`user_id` is null) b ON (u.`user_id`=b.`user_id`)";
	}elseif( $bonusbuydetail == 1){
		$Sql.=" JOIN (SELECT `user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` WHERE `usetime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `user_id`) b ON (u.`user_id`=b.`user_id`)";
	}
}
if ($_POST['post_ticketrecord'] != 0 ){
	$ticketrecord = $_POST['post_ticketrecord'];
	$Sql.=" JOIN (SELECT `userid` FROM `{$INFO[DBPrefix]}use_ticket` WHERE `usetime` >= UNIX_TIMESTAMP('".$goods_starttime."') AND `usetime` <= UNIX_TIMESTAMP('".$goods_endtime."') GROUP BY `userid`) i ON (u.`user_id`=i.`userid`)";
}

$Sql = $Sql.$Create_Sql." order by u.user_id desc";

$Query_sub = $DB->query($Sql);
$Num_sub   = $DB->num_rows($Query_sub);

while ($Rs_sub = $DB->fetch_array($Query_sub)){
	$row++;
	$i = 0;
	foreach($member_excel_out as $k=>$v){
		//if(array_key_exists($v,$Rs_sub)){
			switch(trim($v)){
				case "ordertotal":
					$order_Sql = "select count(*) as counts,sum(discount_totalPrices) as totals from `{$INFO[DBPrefix]}order_table` where user_id='" . $Rs_sub['user_id'] . "'";
					$order_Query    = $DB->query($order_Sql);
					$order_Rs = $DB->fetch_array($order_Query);
					$objActSheet->setCellValueExplicit(getCellName($i,$row), intval($order_Rs['totals']),PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "provider_id":
					$Pro_Query = $DB->query("select p.provider_name from `{$INFO[DBPrefix]}provider` p  where provider_id=".intval($Rs_sub[provider_id])." limit 0,1");
					$Pro_Num   = $DB->num_rows($Pro_Query);
					if ($Pro_Num>0){
						$Pro_Rs   = $DB->fetch_array($Pro_Query);
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $Pro_Rs[provider_name],PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "companyid":
					$saler_Sql = "select name from `{$INFO[DBPrefix]}saler` where id='" . $Rs_sub['companyid'] . "'";
					$saler_Query    = $DB->query($saler_Sql);
					$saler_Num   = $DB->num_rows($saler_Query);
					if ($saler_Num>0){
						$saler_Rs = $DB->fetch_array($saler_Query);
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $saler_Rs[name],PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "sex":
					$sex = $Rs_sub[sex] == 0 ? "男" : "女";
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $sex,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "tel":
					$objActSheet->setCellValueExplicit(getCellName($i,$row), MD5Crypt::Decrypt ($Rs_sub[tel], $INFO['tcrypt']),PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "other_tel":
					$objActSheet->setCellValueExplicit(getCellName($i,$row), MD5Crypt::Decrypt ($Rs_sub[other_tel], $INFO['mcrypt']),PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "user_state":
					$user_state = $Rs_sub[user_state] == 0 ? "正常"  : "關閉";
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $user_state,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "bouns":
					if(($_POST['post_bonusrecord_start'] != "") && ($_POST['post_bonusrecord_end'] != "")){
						$bonusrecord_start = $_POST['post_bonusrecord_start'];
						$bonusrecord_end = $_POST['post_bonusrecord_end'];
						$point =$FUNCTIONS->NowUserpoint(intval($Rs_sub['user_id']),$goods_starttime,$goods_endtime,$bonusrecord_start,$bonusrecord_end);
					}else {
						$point =$FUNCTIONS->Userpoint(intval($Rs_sub['user_id']),1);
					}
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $point,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "buypoint":
					$point =$FUNCTIONS->Buypoint(intval($Rs_sub['user_id']));
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $point,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "grouppoint":
					$point =$FUNCTIONS->Grouppoint(intval($Rs_sub['user_id']));
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $point,PHPExcel_Cell_DataType::TYPE_STRING);
					break;

				default:
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $Rs_sub[$v],PHPExcel_Cell_DataType::TYPE_STRING);
			}
//		}
		$i++;
	}
}

//$objActSheet->setCellValueExplicit( getCellName($i,$row), $Sql,PHPExcel_Cell_DataType::TYPE_STRING);

function getCellName($order,$id){
	$string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$len = strlen($string);
	$level = floor($order/26);
	$ge = ($order%26);
	$name = "";
	if($level ==0)
		$name = substr($string,$order,1);
	else
		$name = substr($string,($level-1),1) . substr($string,($ge),1);
	return $name . $id;
}

$outputFileName = "member.xls";
$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');


header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header('Content-Disposition:inline;filename="'.$outputFileName.'"');
header("Content-Transfer-Encoding: binary");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
Header("Content-type: application/octet-stream");
Header("Content-Disposition: inline; filename=".$outputFileName."");
Header("Pragma:public");

$objWriter->save('php://output');
exit;

?>
