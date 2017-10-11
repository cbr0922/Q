<?php
@ob_start();
include "Check_Admin.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
require_once '../Resources/phpexcel/PHPExcel.php'; 
include_once 'crypt.class.php';
include_once "Time.class.php";
$TimeClass = new TimeClass;

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
							 
$objActSheet->setTitle('紅利記錄');
$FUNCTIONS->setLog("紅利記錄");

$starttime = $_POST['starttime']!=''?$_POST['starttime']:date("Y-m-d",time());
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($starttime,"-");

//$objActSheet->setCellValue('A1', '會員');  
$objActSheet->setCellValue('A1', '當期(月)發出紅利點數'); 
$objActSheet->setCellValue('B1', '當期(月)減少紅利點數'); 
$objActSheet->setCellValue('C1', '當期(月)使用紅利點數'); 
$objActSheet->setCellValue('D1', '目前紅利點數餘額'); 

echo iconv("UTF-8","big5",$file_string);
//$Cid  = $_POST['cid'];

//$Cid_num   = count($Cid);
$row=$i+2;
/*for ($i=0;$i<$Cid_num;$i++){
	$row=$i+2;
	$Sql = "select  u.* from `{$INFO[DBPrefix]}user` u where u.user_id=".intval($Cid[$i]);
	$Query    = $DB->query($Sql);
	$Rs = $DB->fetch_array($Query);
	$user = $Rs['username'] . "(" . $Rs['true_name'] . ")";*/

	//當期(月)發出紅利點
	//$Sql = "select sum(c.point) as sumpoint from `{$INFO[DBPrefix]}bonuspoint` as c where c.user_id=".intval($Cid[$i])." and FROM_UNIXTIME(`addtime`,'%Y-%m')='" . date("Y-m",time()) . "' and c.saleorlevel=1";
	$Sql = "select sum(c.point) as sumpoint from `{$INFO[DBPrefix]}bonuspoint` as c where FROM_UNIXTIME(`addtime`,'%Y-%m')='" . date("Y-m",$begtimeunix) . "' and c.saleorlevel=1";
	$Query    = $DB->query($Sql);
	$Rs = $DB->fetch_array($Query);
	$summonthpoint = intval($Rs['sumpoint']);

	//上月紅利點數餘額{
	$sql = "select sum(c.point) as sumpoint from `{$INFO[DBPrefix]}bonuspoint` as c where FROM_UNIXTIME(`addtime`,'%Y-%m')<'" . date("Y-m",$begtimeunix) . "' and endtime>'" . $begtimeunix . "' and c.saleorlevel=1";
	$Query =  $DB->query($sql);
	$Rs = $DB->fetch_array($Query);
	$sumpoint = intval($Rs['sumpoint']);

	$sql = "select sum(usepoint) as usepoint from `{$INFO[DBPrefix]}bonusbuydetail` where FROM_UNIXTIME(`usetime`,'%Y-%m')<'" . date("Y-m",$begtimeunix) . "'";
	$Query =  $DB->query($sql);
	$Rs = $DB->fetch_array($Query);
	$usepoint = intval($Rs['usepoint']);
	//}	
	//$summonthpoint = intval($summonthpoint)+intval($sumpoint)-intval($usepoint);

	//當期(月)減少紅利點數
	$Sql = "select sum(c.point) as subpoint from `{$INFO[DBPrefix]}bonuspoint` as c where FROM_UNIXTIME(`endtime`,'%Y-%m')='" . date("Y-m",$begtimeunix) . "' and endtime<'" . $begtimeunix . "' and c.saleorlevel=1 and c.usestate=0";
	$Query    = $DB->query($Sql);
	$Rs = $DB->fetch_array($Query);
	$submonthpoint = intval($Rs['subpoint']);

	//當期(月)使用紅利點數
	//$sql = "select sum(usepoint) as usepoint from `{$INFO[DBPrefix]}bonusbuydetail` where user_id=".intval($Cid[$i])." and FROM_UNIXTIME(`usetime`,'%Y-%m')='" . date("Y-m",time()) . "'";
	$sql = "select sum(usepoint) as usepoint from `{$INFO[DBPrefix]}bonusbuydetail` where FROM_UNIXTIME(`usetime`,'%Y-%m')='" . date("Y-m",$begtimeunix) . "'";
	$Query =  $DB->query($sql);
	$Rs = $DB->fetch_array($Query);
	$usemonthpoint = intval($Rs['usepoint']);

	//目前紅利點數餘額
	//$point =$FUNCTIONS->Userpoint(intval($Cid[$i]),1);
	/*$sql = "select sum(c.point) as sumpoint from `{$INFO[DBPrefix]}bonuspoint` as c where endtime>'" . $begtimeunix . "' and c.saleorlevel=1";
	$Query =  $DB->query($sql);
	$Rs = $DB->fetch_array($Query);
	$sumpoint = intval($Rs['sumpoint']);

	$sql = "select sum(usepoint) as usepoint from `{$INFO[DBPrefix]}bonusbuydetail`";
	$Query =  $DB->query($sql);
	$Rs = $DB->fetch_array($Query);
	$usepoint = intval($Rs['usepoint']);
	
	$point = intval($sumpoint)-intval($usepoint);*/

	$point=intval($summonthpoint)-intval($submonthpoint)-intval($usemonthpoint)+(intval($sumpoint)-intval($usepoint));

	//$objActSheet->setCellValueExplicit('A' . $row ,$user ,PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit('A' . $row ,$summonthpoint ,PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit('B' . $row ,$submonthpoint ,PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit('C' . $row ,$usemonthpoint ,PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit('D' . $row ,$point ,PHPExcel_Cell_DataType::TYPE_STRING);
//}

$outputFileName = date("Y-m-d",$begtimeunix) . "_bouns.xls";
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
