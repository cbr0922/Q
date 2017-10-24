<?php
@ob_start();
ini_set("memory_limit", "100M");  
include "Check_Admin.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
require_once 'PHPExcel.php'; 

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
$objActSheet->setTitle('商品匯出');
$FUNCTIONS->setLog("商品匯出");

$goods_excel_out = explode(",",$INFO['goods_excel_out']);

$row = 1;
$i = 0;
foreach($goods_excel_out as $k=>$v){
	$objActSheet->setCellValue(getCellName($i,$row), $goods_field[$v]);
	$i++;
}

$Where      = intval($_GET['bid'])!="" ? " and g.bid=".intval($_GET['bid'])." " : ""  ;
$Add        = "";
$AddBidtype =  "";
$ot_class_array = array();
if (intval($_GET[top_id])!=0 ){
	if (!is_array($op_class_array)){
		$op_class_array = array();
	}else{
		$ot_class_array = $op_class_array;
		foreach($ot_class_array as $k=>$v){
			$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($v));
			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$ot_class_array  = array_merge($Next_ArrayClass,$ot_class_array);	
		}
	}
		if ((in_array(intval($_GET[top_id]),$ot_class_array) && $_SESSION['LOGINADMIN_TYPE']==1) || $_SESSION['LOGINADMIN_TYPE']!=1){
			$S_Sql            = " and ( g.bid='".intval($_GET[top_id])."'  ";
			$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$Array_class      = array_unique($Next_ArrayClass);
			foreach ($Array_class as $k=>$v){
				$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid='".$v."' " : "";
			}
		   $AddBidtype =$S_Sql . $Add . " )";	
		}
	if (AddBidtype!=""){
	}else{
		$AddBidtype = " and 1<>1";	
	}
	
}elseif(($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']!=2)){
	$_GET['Action']="Search";
	$class_array = array();
	$i = 0;
	foreach($op_class_array as $k=>$v){
		$class_array[$i] = $v;
		$i++;
		$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($v));
		if ($Next_ArrayClass!=0){
			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$Next_ArrayClass      = array_unique($Next_ArrayClass);
			if (is_array($Next_ArrayClass)){
				foreach($Next_ArrayClass as $kk=>$vv){
					if($vv!=0){
						$class_array[$i] = 	$vv;
						$i++;
					}
				}
			}
		}
	}
	
	if (count($class_array)>0){
		$AddBidtype = " and g.bid in (" . implode(",",$class_array) . ")";
	}else{
		$AddBidtype = " and 1<>1";	
	}
}
$Brand_search       = intval($_GET['brand_id'])!=0 ? " and  g.brand_id=".intval($_GET['brand_id'])." " : ""  ;

$Provider_search    = trim($_GET['provider_name'])!='' ? " and  p.provider_name like '%".trim($_GET['provider_name'])."%' " : ""  ;

//这里是判断是否是1，0。
if (trim($_GET[typeis])!=""){
	$Typeis = " and ".$_GET[typeis]."=".intval($_GET[typeradio]);
}
if ($_GET['checkstate']!=""){
	 	$checkSql = " and g.checkstate='" . intval($_GET['checkstate']) . "'";  
	 }
if ($_GET['iftogether']!=""){
	$iftogether = $_GET['iftogether'];
	if ($_GET['iftogether']=="-1")
		$iftogether = 0;
	 	$checkSql .= " and g.iftogether='" . intval($iftogether) . "'";  
   }   
$Where    = $_GET['Action']=="Search" ?  " and ( g.goodsname like '%".trim(urldecode($_GET['skey']))."%' or g.bn like '%".trim(urldecode($_GET['skey']))."%' ) ".$Typeis." ".$AddBidtype." " : $Where ;

$Value    = $_GET['Action']=="Search" ? trim(urldecode($_GET['skey']))   : $Admin_Product[PleaseInputPrductName]  ; //請輸入商品名稱
$Sql_sub = "select * from `{$INFO[DBPrefix]}goods` as g where g.shopid=0 ".$Where." ".$Brand_search." ".$Provider_search." " . $checkSql;
$Query_sub = $DB->query($Sql_sub);
$Num_sub   = $DB->num_rows($Query_sub);

while ($Rs_sub = $DB->fetch_array($Query_sub)){
	$row++;
	$i = 0;
	foreach($goods_excel_out as $k=>$v){
		if(array_key_exists($v,$Rs_sub)){
			switch($v){
				case "bid":
					$Bid_Query = $DB->query("select b.catname from `{$INFO[DBPrefix]}bclass` b  where bid=".intval($Rs_sub[bid])." limit 0,1");
					$Bid_Num   = $DB->num_rows($Bid_Query);
					if ($Bid_Num>0){
						$Bid_Rs   = $DB->fetch_array($Bid_Query);
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $Bid_Rs[catname],PHPExcel_Cell_DataType::TYPE_STRING);
					}
					
					break;
				case "provider_id":
					$Pro_Query = $DB->query("select p.provider_name from `{$INFO[DBPrefix]}provider` p  where provider_id=".intval($Rs_sub[provider_id])." limit 0,1");
					$Pro_Num   = $DB->num_rows($Pro_Query);
					if ($Pro_Num>0){
						$Pro_Rs   = $DB->fetch_array($Pro_Query);
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $Pro_Rs[provider_name],PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "brand":
				case "brand_id":
					$Brand_Query   = $DB->query("select b.brandname,b.brand_id from `{$INFO[DBPrefix]}brand` b  where brand_id=".intval($Rs_sub[brand_id])." limit 0,1");
					$Brand_Num     = $DB->num_rows($Brand_Query);
					if ($Brand_Num>0){
						$Brand_Rs   = $DB->fetch_array($Brand_Query);
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $Brand_Rs[brandname],PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "trans_special":
					$Sql_trans      = "select * from `{$INFO[DBPrefix]}transportation_special` where trid='" . $Rs_sub[trans_special] . "' order by trid ";
					$Query_trans    = $DB->query($Sql_trans);
					$Num_trans      = $DB->num_rows($Query_trans);
					if ($Num_trans>0){
						$Rs_trans=$DB->fetch_array($Query_trans);
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $Rs_trans[name],PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "subject_id":
					$Subject_Query   = $DB->query("select s.subject_name,s.subject_id from `{$INFO[DBPrefix]}subject` s where subject_id=".intval($Rs_sub[subject_id])." limit 0,1");
					$Subject_Num     = $DB->num_rows($Subject_Query);
					if ($Subject_Num>0){
						$Subject_Rs   = $DB->fetch_array($Subject_Query);
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $Subject_Rs[subject_name],PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "sale_subject":
					$Sql_sub1   = " select subject_name,subject_id,subject_open from `{$INFO[DBPrefix]}sale_subject` where subject_id='" . $Rs_sub[sale_subject] . "' order by subject_num desc ";
					$Query_sub1 = $DB->query($Sql_sub1);
					$sub1_Num     = $DB->num_rows($Query_sub1);
					if ($sub1_Num>0){
						$Rs_sub1 = $DB->fetch_array($Query_sub1);
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $Rs_sub1[subject_name],PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "ifalarm":
					$ifalarm = $Rs_sub[ifalarm] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifalarm,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifbonus":
					$ifbonus = $Rs_sub[ifbonus] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifbonus,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifjs":
					$ifjs = $Rs_sub[ifjs] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifjs,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifpub":
					$ifpub = $Rs_sub[ifpub] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifpub,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifrecommend":
					$ifrecommend = $Rs_sub[ifrecommend] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifrecommend,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifspecial":
					$ifspecial = $Rs_sub[ifspecial] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifspecial,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifhot":
					$ifhot = $Rs_sub[ifhot] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifhot,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifxygoods":
					$ifxygoods = $Rs_sub[ifxygoods] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifxygoods,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifxy":
					$ifxy = $Rs_sub[ifxy] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifxy,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifchange":
					$ifchange = $Rs_sub[ifchange] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifchange,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifpresent":
					$ifpresent = $Rs_sub[ifpresent] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifpresent,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "trans_type":
					$trans_type = $Rs_sub[ifpresent] == 0 ? "一般配送方式"  : "特殊配送方式";
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $trans_type,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "iftransabroad":
					$iftransabroad = $Rs_sub[ifpresent] == 0 ? "不允許"  : "允許";
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $iftransabroad,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifadd":
					$ifadd = $Rs_sub[ifadd] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifadd,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifsales":
					$ifsales = $Rs_sub[ifsales] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifsales,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifsaleoff":
					$ifsaleoff = $Rs_sub[ifsaleoff] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifsaleoff,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "iftimesale":
					$iftimesale = $Rs_sub[iftimesale] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $iftimesale,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "ifmood":
					$ifmood = $Rs_sub[ifmood] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $ifmood,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "iftogether":
					$iftogether = $Rs_sub[iftogether] == 0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $iftogether,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "transtype":
					$transtype_array=array(1=>"中小型物件",2=>"大型物件",3=>"其他");
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $transtype_array[$Rs_sub[transtype]],PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "saleoff_starttime":
					if ($Rs_sub[saleoff_starttime]!=""){
						$saleoff_starttime = date("Y-m-d H:i:s",$Rs_sub[saleoff_starttime])	;
					}
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $saleoff_starttime,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "saleoff_endtime":
					if ($Rs_sub[saleoff_endtime]!=""){
						$saleoff_endtime = date("Y-m-d H:i:s",$Rs_sub[saleoff_endtime])	;
					}
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $saleoff_endtime,PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				case "timesale_starttime":
					if ($Rs_sub[timesale_starttime]!=""){
						$timesale_starttime = date("Y-m-d H:i:s",$Rs_sub[timesale_starttime])	;
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $timesale_starttime,PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "timesale_starttime":
					if ($Rs_sub[timesale_starttime]!=""){
						$timesale_starttime = date("Y-m-d H:i:s",$Rs_sub[timesale_starttime])	;
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $timesale_starttime,PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "timesale_endtime":
					if ($Rs_sub[timesale_endtime]!=""){
						$timesale_endtime = date("Y-m-d H:i:s",$Rs_sub[timesale_starttime])	;
						$objActSheet->setCellValueExplicit(getCellName($i,$row), $timesale_endtime,PHPExcel_Cell_DataType::TYPE_STRING);
					}
					break;
				case "checkstate":
					$checkstate_array=array(0=>"未審核",1=>"初審",2=>"複審");
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $checkstate_array[$Rs_sub[checkstate]],PHPExcel_Cell_DataType::TYPE_STRING);
					break;
				default:
					$objActSheet->setCellValueExplicit(getCellName($i,$row), $Rs_sub[$v],PHPExcel_Cell_DataType::TYPE_STRING);
			}	
		}
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

$outputFileName = "goods.xls";
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