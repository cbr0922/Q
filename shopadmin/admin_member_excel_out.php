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

$row = 1;
$i = 0;
foreach($member_excel_out as $k=>$v){
	$objActSheet->setCellValue(getCellName($i,$row), $member_field[$v]);
	$i++;
}

if (intval($_GET['companyid']) > 0){
	$Date_string = " companyid ='".intval($_GET[companyid])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql("",$Date_string);
}
if ($_GET['Month']){
	$Date_string = " MONTH(born_date) ='".intval($_GET[Month])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql("",$Date_string);
}
if ($_GET['Sex']!=""){
	$Sex_string = " sex='".intval($_GET[Sex])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Sex_string);
}



if ($_GET['ifallarea']!="1"){
if($_GET[county]!="" && $_GET[county]!="請選擇"){
	$Area_string = "   Country='".trim($_GET[county])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
if($_GET[province]!="" && $_GET[province]!="請選擇"){
	$Area_string = "   canton='".trim($_GET[province])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
if($_GET[city]!="" && $_GET[city]!="請選擇"){
	$Area_string = "   city='".trim($_GET[city])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
}
if ($_GET['skey']){
	$key_string = "u.username like '%".trim(urldecode($_GET['skey']))."%'";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$key_string);
}
$Sql      = "select u.* ,l.level_name from `{$INFO[DBPrefix]}user` u  left join `{$INFO[DBPrefix]}user_level` l on (u.user_level = l.level_id) ".$Create_Sql." order by u.user_id desc";
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
					$point =$FUNCTIONS->Userpoint(intval($Rs_sub['user_id']),1);
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

ob_end_clean();
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
