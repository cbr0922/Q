<?php
@ob_start();
include "Check_Admin.php";
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
require_once '../Resources/phpexcel/PHPExcel.php'; 
include_once 'crypt.class.php';
include_once("product.class.php");
$PRODUCT = new PRODUCT();
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
$string = "編號,商品類別,擴展分類1,擴展分類2,擴展分類3,擴展分類4,擴展分類5,商品品牌,貨號,賣場編號,組合母商品貨號,商品名稱,促銷廣告標語,屬性貨號,商品顏色,商品尺寸,組合子商品件數,建議市價,網購價格,成本價,計量單位,庫存警告,警告數量,庫存數量,瀏覽次數,國際碼,型號,重量,簡單描述,KeyWords,符合賣場促銷信息,商品介紹,商品規格,備註,是否特價,是否熱賣,是否組合商品,是否商品贈品,是否複合式賣場,促銷成本,紅利點數,紅利商品,紅利商品兌換紅利,是否是加購商品,是否是滿額加購商品,滿額加購金額,滿額加購價格,額滿禮商品,額滿加購金額(最小),額滿加購金額(最大),整點促銷商品（未到促銷時間段不能進行購買活動）,促銷時間(開始),促銷時間(結束)	,整點促銷商品（在促銷期間商品售價為促銷價格）,促銷時間(開始),促銷時間(結束),整點促銷價格,限購數量,是否超商配送	,是否支持海外配送,貨運寄送類型,是否滿額免運費";	
$string_array = explode(",",$string);
$objActSheet->setTitle(date("Y-m-d") . '訂單');
foreach($string_array as $k=>$v){
	$objActSheet->setCellValue(getC($k) . "1", $v);	
}

$Sql_sub = "select * from `{$INFO[DBPrefix]}goods`";
$Query_sub = $DB->query($Sql_sub);
$Num_sub   = $DB->num_rows($Query_sub);
$row = 2;
$FUNCTIONS->setLog("商品匯出");
while ($Rs_sub = $DB->fetch_array($Query_sub)){
	$f = 0;
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $row-1,PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['bid'],PHPExcel_Cell_DataType::TYPE_STRING);
	$Sql_class = "select * from `{$INFO[DBPrefix]}goods_class` where gid='" . $Rs_sub['gid'] . "'";
	$Query_class = $DB->query($Sql_class);
	$Num_class   = $DB->num_rows($Query_class);
	$c = 0;
	while ($Rs_class = $DB->fetch_array($Query_class)){
		if($c<5)
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_class['bid'],PHPExcel_Cell_DataType::TYPE_STRING);
		$c++;
	}
	$f = 7;
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['brand_id'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['bn'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['goodsno'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//母商品
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['goodsname'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['sale_name'],PHPExcel_Cell_DataType::TYPE_STRING);
	//屬性
	$objActSheet->setCellValueExplicit(getC($f++) . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['price'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['pricedesc'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['cost'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['unit'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifalarm'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['alarmnum'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['storage'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['view_num'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['guojima'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['xinghao'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['weight'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['intro'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['keywords'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['mixcontent'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['body'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['cap_des'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['alarmcontent'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifspecial'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifhot'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifpack'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifgoodspresent'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifmix'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['salecost'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['point'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifbonus'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['bonusnum'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifchange'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifadd'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['addmoney'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['addprice'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifpresent'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['present_money'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['present_endmoney'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifsaleoff'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['saleoff_starttime']),PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['saleoff_endtime']),PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['iftimesale'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['timesale_starttime']),PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['timesale_endtime']),PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['saleoffprice'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['saleoffcount'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['trans_type'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['iftransabroad'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ttype'],PHPExcel_Cell_DataType::TYPE_STRING);
	$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifmood'],PHPExcel_Cell_DataType::TYPE_STRING);
	//$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['idate']),PHPExcel_Cell_DataType::TYPE_STRING);
	//屬性
	if($Rs_sub['good_color']!="" || $Rs_sub['good_size']!=""){
		$goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where gid='" . trim($Rs_sub['gid']) . "' ";
		$goods_Query =  $DB->query($goods_Sql);
		$goods_Num   =  $DB->num_rows($goods_Query );
		while ($goods_Rs = $DB->fetch_array($goods_Query)){
			$row++;
			$f = 0;
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $row-1,PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['bid'],PHPExcel_Cell_DataType::TYPE_STRING);
			$Sql_class = "select * from `{$INFO[DBPrefix]}goods_class` where gid='" . $Rs_sub['gid'] . "'";
			$Query_class = $DB->query($Sql_class);
			$Num_class   = $DB->num_rows($Query_class);
			$c = 0;
			while ($Rs_class = $DB->fetch_array($Query_class)){
				if($c<5)
					$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_class['bid'],PHPExcel_Cell_DataType::TYPE_STRING);
				$c++;
			}
			$f = 7;
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['brand_id'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['bn'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['goodsno'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);//母商品
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['goodsname'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['sale_name'],PHPExcel_Cell_DataType::TYPE_STRING);
			//屬性
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['goodsno'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['color'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['size'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, "",PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['price'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['pricedesc'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['cost'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['unit'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifalarm'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['alarmnum'],PHPExcel_Cell_DataType::TYPE_STRING);
			
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $PRODUCT->checkStorage($Rs_sub['gid'],0,$goods_Rs['color'],$goods_Rs['size'],$checktype,1),PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['view_num'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['guojima'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['xinghao'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['weight'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['intro'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['keywords'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['mixcontent'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['body'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['cap_des'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['alarmcontent'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifspecial'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifhot'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifpack'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifgoodspresent'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifmix'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['salecost'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['point'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifbonus'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['bonusnum'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifchange'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifadd'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['addmoney'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['addprice'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifpresent'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['present_money'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['present_endmoney'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifsaleoff'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['saleoff_starttime']),PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['saleoff_endtime']),PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['iftimesale'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['timesale_starttime']),PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['timesale_endtime']),PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['saleoffprice'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['saleoffcount'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['trans_type'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['iftransabroad'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ttype'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['ifmood'],PHPExcel_Cell_DataType::TYPE_STRING);
			//$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$Rs_sub['idate']),PHPExcel_Cell_DataType::TYPE_STRING);
		}
	}
	//組合子商品
	$Sql_pack         = "select g.*,gl.color,gl.size,gl.count from `{$INFO[DBPrefix]}goods_pack` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.packgid=g.gid) where gl.gid=".intval($Rs_sub['gid'])." order by gl.idate desc ";
	$Query_pack       = $DB->query($Sql_pack);
	$Num_pack         = $DB->num_rows($Query_pack);
	while ($goods_Rs = $DB->fetch_array($Query_pack)){
		$row++;
		$f = 0;
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $row-1,PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['bid'],PHPExcel_Cell_DataType::TYPE_STRING);
		$Sql_class = "select * from `{$INFO[DBPrefix]}goods_class` where gid='" . $goods_Rs['gid'] . "'";
		$Query_class = $DB->query($Sql_class);
		$Num_class   = $DB->num_rows($Query_class);
		$c = 0;
		while ($Rs_class = $DB->fetch_array($Query_class)){
			if($c<5)
				$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_class['bid'],PHPExcel_Cell_DataType::TYPE_STRING);
			$c++;
		}
		$f = 7;
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['brand_id'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['bn'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['goodsno'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $Rs_sub['bn'],PHPExcel_Cell_DataType::TYPE_STRING);//母商品
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['goodsname'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['sale_name'],PHPExcel_Cell_DataType::TYPE_STRING);
		//屬性
		$a_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where gid='" . $goods_Rs['gid'] . "' and color='" . $goods_Rs['color'] . "' and size='" . $goods_Rs['size'] . "'";
		$a_Query =  $DB->query($a_Sql);
		$a_Num   =  $DB->num_rows($a_Query );
		$a_Rs = $DB->fetch_array($a_Query);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $a_Rs['goodsno'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['color'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['size'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['count'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['price'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['pricedesc'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['cost'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['unit'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifalarm'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['alarmnum'],PHPExcel_Cell_DataType::TYPE_STRING);
		
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $PRODUCT->checkStorage($goods_Rs['gid'],0,$goods_Rs['color'],$goods_Rs['size'],$checktype,1),PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['view_num'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['guojima'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['xinghao'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['weight'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['intro'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['keywords'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['mixcontent'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['body'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['cap_des'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['alarmcontent'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifspecial'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifhot'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifpack'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifgoodspresent'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifmix'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['salecost'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['point'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifbonus'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['bonusnum'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifchange'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifadd'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['addmoney'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['addprice'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifpresent'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['present_money'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['present_endmoney'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifsaleoff'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$goods_Rs['saleoff_starttime']),PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$goods_Rs['saleoff_endtime']),PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['iftimesale'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$goods_Rs['timesale_starttime']),PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$goods_Rs['timesale_endtime']),PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['saleoffprice'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['saleoffcount'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['trans_type'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['iftransabroad'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ttype'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objActSheet->setCellValueExplicit(getC($f++) . $row, $goods_Rs['ifmood'],PHPExcel_Cell_DataType::TYPE_STRING);
		//$objActSheet->setCellValueExplicit(getC($f++) . $row, date("Y/m/d",$goods_Rs['idate']),PHPExcel_Cell_DataType::TYPE_STRING);
	}
	$row++;
}



function formatstr($str){
	$str = str_replace(",","，",$str);
	$str = str_replace("\"","“",$str);
	$str = str_replace("\r"," ",$str);
	$str = str_replace("\n"," ",$str);	
	return $str;
}
function getC($no){
	$start = 65;
	$end = 90;
	 $len = intval($no/26);
	if($len>0){
		$result = chr($start+$len-1);
		$result .= chr($no%26+$start);
	}else{
		$result = chr($start+$no);	
	}
	//echo $result;
	return $result;
}

$outputFileName = date("Y-m-d") . "goods.xls";
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
