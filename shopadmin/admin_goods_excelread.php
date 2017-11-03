<?php
@ob_start();
ini_set("memory_limit", "100M");  
include_once "Check_Admin.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
include("product.class.php");
$PRODUCT = new PRODUCT();
require_once( 'PHPExcel.php' ); 
$PHPExcel = new PHPExcel(); 
$filePath = $_FILES['cvsEXCEL']['tmp_name'];
$PHPReader = new PHPExcel_Reader_Excel2007(); 
if(!$PHPReader->canRead($filePath)){ 
	$PHPReader = new PHPExcel_Reader_Excel5(); 
	if(!$PHPReader->canRead($filePath)){ 
		echo 'no Excel'; 
	return ; 
	} 
} 
$PHPExcel = $PHPReader->load($filePath); 
/**读取excel文件中的第一个工作表*/ 
$currentSheet = $PHPExcel->getSheet(0); 
/**取得最大的列号*/ 
$allColumn = $currentSheet->getHighestColumn(); 
$end_index     = PHPExcel_Cell::columnIndexFromString($allColumn);
/**取得一共有多少行*/ 
$allRow = $currentSheet->getHighestRow(); 
/**从第二行开始输出，因为excel表中第一行为列名*/ 
$gid = 0;
$pm_array = array();
$p = 0;
$bn_array = array();
$bnc_array = array();
$bnd_array = array();
$row = 0;
$rowc = 0;
$rowd = 0;
$error_array = array();
$erow = 0;
echo "<br>以下為貨號重複，未匯入成功：<br><br><table border=1 cellspacing=0 cellpadding=5><tr style='color: #fff;background: #bb0a0a;font-weight: bold;'><td>列數</td><td>重複的貨號</td></tr>";
for($currentRow = 2;$currentRow <= $allRow;$currentRow++){ 
/**从第A列开始输出*/ 
	$data = array();
	
	for ($column = 0; $column < $end_index; $column++) {  
		$col_name = PHPExcel_Cell::stringFromColumnIndex($column);
		$address = $col_name.$currentRow;
		$data[$column] = $currentSheet->getCell($address)->getValue();/**ord()将字符转为十进制数*/ 
	}
	$ifop = 1;
	if($data[getindex("I")]!=""){
		$mybn = "";
		if($data[getindex("S")]!=""){
			$mybn = $data[getindex("S")];
			if(in_array($mybn,$bn_array)){
				$error_array[$erow] = "<tr><td>".$currentRow . "</td><td>" . $mybn . "</td></tr>";
				$erow++;
				$ifop = 0;
			}else{
				$bn_array[$row] = $mybn;
				$row++;
			}
		}elseif($data[getindex("AE")]!=""){
			$mybn = $data[getindex("AE")];
			if(in_array($mybn,$bnd_array)){
				$error_array[$erow] = "<tr><td>".$currentRow . "</td><td>" . $mybn . "</td></tr>";
				$erow++;
				$ifop = 0;
			}else{
				$bnd_array[$rowd] = $mybn;
				$rowd++;
			}
		}else{
			$mybn = $data[getindex("I")];
			if(in_array($mybn,$bnc_array)){
				$error_array[$erow] = "<tr><td>".$currentRow . "</td><td>" . $mybn . "</td></tr>";
				$erow++;
				$ifop = 0;
			}else{
				$bnc_array[$rowd] = $mybn;
				$rowd++;
			}
		}
		
	}
	if($ifop==1){
		$sql = "select * from `{$INFO[DBPrefix]}goods` where bn = '" . trim($data[getindex("I")]) . "'";
		$Query_goods    = $DB->query($sql);
		$Num_trans      = $DB->num_rows($Query_goods);
		if ($Num_trans > 0){
			$Rs = $DB->fetch_array($Query_goods);
			$gid = $Rs['gid'];
		}else{
			$gid = 0;	
		}
		$brandbids = "";
		$extendbid = "";
		if(intval($data[getindex("x")])==0)
				$data[getindex("x")] =intval($data[getindex("w")]);
			//匯入屬性
			if(trim($data[19])!=""){

			   $goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where goodsno='" . trim($data[18]) . "' ";
			  $goods_Query =  $DB->query($goods_Sql);
			  $goods_Num   =  $DB->num_rows($goods_Query );
			  $good_color_array = array();
			  $good_color_pic_array = array();

				  $good_color  =  $Rs['good_color'];
				  $good_color_pic  =  $Rs['good_color_pic'];
				  if($good_color=="")
					  $count_c = 0;
				  else{
					  $good_color_array = explode(",",$good_color);
					  $good_color_pic_array = explode(",",$good_color_pic);
					  $count_c = count($good_color_array);

				  }
				  if(trim($data[19])!=""){
					  if(in_array(trim($data[19]),$good_color_array)){
						  $good_color_pic_array[array_search(trim($data[19]),$good_color_array)] = trim($data[20]);
					  }else{
						  $good_color_array[$count_c] = trim($data[19]);
						  $good_color_pic_array[$count_c] = trim($data[20]);
					  }
				  }

				  $Result = $DB->query("update `{$INFO[DBPrefix]}goods` set good_color='" . implode(",",$good_color_array) . "',good_color_pic='" . implode(",",$good_color_pic_array) . "' where gid = '" . $gid . "'"); 
			if($goods_Num<=0){

				  $update_Sql = "insert into `{$INFO[DBPrefix]}attributeno`(gid,color,goodsno,guojima,orgno)values('" . $gid . "','" . trim($data[19]) . "','" . trim($data[18]) . "','" . trim($data[getindex("j")]) . "','" . trim($data[getindex("k")]) . "')";	
				  $DB->query($update_Sql);
				  $update_Sql = "insert into `{$INFO[DBPrefix]}storage`(goods_id,color,sales)values('" . $gid . "','" . trim($data[19]) . "','" . trim($data[getindex("ab")]) . "')";
				   $DB->query($update_Sql);
				  //$FUNCTIONS->setStorage(trim($data[getindex("x")]),0,$gid,0,trim($data[15]),trim($data[14]),"商品匯入",0,0,array(),1);
			  }else{

					$update_Sql = "update `{$INFO[DBPrefix]}attributeno` set guojima='" . trim($data[getindex("j")]) . "',orgno='" . trim($data[getindex("k")]) . "' where goodsno='" . trim($data[18]) . "'";	
					$DB->query($update_Sql);
					$update_Sql = "update `{$INFO[DBPrefix]}storage` set sales='" . trim($data[getindex("ab")]) . "' where color='" . trim($data[19]) . "' and goods_id='" . $gid . "'"; 
					 $DB->query($update_Sql);

			  }

			//匯入詳細規格
			}elseif($data[getindex("ae")]!=""){
				 $goods_Sql = "select * from `{$INFO[DBPrefix]}goods_detail` where detail_bn='" . trim($data[getindex("ae")]) . "' ";
				$goods_Query =  $DB->query($goods_Sql);
				$goods_Num   =  $DB->num_rows($goods_Query );
				if($goods_Num>0){

					 $update_Sql = "update `{$INFO[DBPrefix]}goods_detail` set detail_name='" . trim($data[getindex("af")]) . "',guojima='" . trim($data[getindex("j")]) . "',orgno='" . trim($data[getindex("k")]) . "',sales='" . trim($data[getindex("ab")]) . "',detail_pricedes='" . intval($data[getindex("x")]) . "',detail_price='" . intval($data[getindex("w")]) . "',detail_pic='" . trim($data[getindex("n")]) . "' where detail_bn='" . trim($data[getindex("ae")]) . "'";
					$DB->query($update_Sql);

				}else{
					 $update_Sql = "insert into `{$INFO[DBPrefix]}goods_detail`(gid,detail_bn,detail_name,sales,guojima,orgno,detail_pricedes,detail_price,detail_pic)values('" . $gid . "','" . trim($data[getindex("ae")]) . "','" . trim($data[getindex("af")]) . "','" . trim($data[getindex("ab")]) . "','" . trim($data[getindex("j")]) . "','" . trim($data[getindex("k")]) . "','" . intval($data[getindex("x")]) . "','" . intval($data[getindex("w")]) . "','" . trim($data[getindex("n")]) . "')";	
					$DB->query($update_Sql);

				}
			}else{
				//分類
				$bid_array = array();
					if(intval($data[getindex("a")])>0){
						$class_banner = array();
						$list = 0;
						$PRODUCT->getTopBidList(intval($data[getindex("a")]));
						$bid_array[0] = $class_banner;
						//print_r($class_banner);exit;
						//擴展類
						if(intval($data[getindex("b")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBidList(intval($data[getindex("b")]));
							$bid_array[1] = $class_banner;
						}
						if(intval($data[getindex("c")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBidList(intval($data[getindex("c")]));
							$bid_array[2] = $class_banner;
						}
						if(intval($data[getindex("d")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBidList(intval($data[getindex("d")]));
							$bid_array[3] = $class_banner;
						}
						if(intval($data[getindex("e")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBidList(intval($data[getindex("e")]));
							$bid_array[4] = $class_banner;
						}
						if(intval($data[getindex("f")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBidList(intval($data[getindex("f")]));
							$bid_array[5] = $class_banner;
						}
						$extendbid = json_encode($bid_array);
					}
					$brandbid_array = array();
					if(intval($data[getindex("h")])>0){
						$class_banner = array();
						$list = 0;
						$PRODUCT->getTopBrandBidList(intval($data[getindex("h")]),0);
						$brandbid_array[0] = $class_banner;
						//print_r($class_banner);exit;
						//擴展類
						if(intval($data[getindex("aj")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBrandBidList(intval($data[getindex("aj")]),0);
							$brandbid_array[1] = $class_banner;
						}
						if(intval($data[getindex("ak")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBrandBidList(intval($data[getindex("ak")]),0);
							$brandbid_array[2] = $class_banner;
						}
						if(intval($data[getindex("al")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBrandBidList(intval($data[getindex("al")]),0);
							$brandbid_array[3] = $class_banner;
						}
						if(intval($data[getindex("am")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBrandBidList(intval($data[getindex("am")]),0);
							$brandbid_array[4] = $class_banner;
						}
						if(intval($data[getindex("an")])>0){
							$class_banner = array();
							$list = 0;
							$PRODUCT->getTopBrandBidList(intval($data[getindex("an")]),0);
							$brandbid_array[5] = $class_banner;
						}

						$brandbids = json_encode($brandbid_array);
						//echo $brandbids;exit;
					}
			
		
			//echo excelTime($data[getindex("ah")],false);exit;

			
			if(trim($data[getindex("ah")])!=""){
				
				$salestartdate = excelTime($data[getindex("ah")],false);;
			}else{
				$salestartdate = "";
			}
			if(trim($data[getindex("ai")])!=""){
				
				$saleenddate = excelTime($data[getindex("ai")],false);;
			}else{
				$saleenddate = "";
			}
			$sql_data = array (
					'bid'                => intval($data[getindex("a")]),

					'brand_id'           => intval($data[getindex("g")]),
					'brand_bid'           => intval($data[getindex("h")]),
					'bn'                 => trim($data[getindex("I")]),
					'guojima'           => trim($data[getindex("j")]),
					'orgno'           => trim($data[getindex("k")]),
					'ERP'           => trim($data[getindex("l")]),
					'goodsname'          => trim($data[getindex("m")]),
					'bigimg'              => trim($data[getindex("n")]),
					'gimg'              => trim($data[getindex("n")]),
					'middleimg'              => trim($data[getindex("o")]),
					'smallimg'              => trim($data[getindex("p")]),
					'sale_name'              => trim($data[getindex("r")]),
					'price'              => intval($data[getindex("w")]),
					'pricedesc'          => intval($data[getindex("x")]),
					'cost'           => 0,
					'body'               =>  $data[getindex("y")],
					'cap_des'              => trim($data[getindex("z")]),
					'alarmcontent'              => $data[getindex("aa")],
					//'storage'           => intval($data[getindex("ab")]),
					'department'           => ($data[getindex("ac")]),		
					'pm'           => ($data[getindex("ad")]),	
					'extendbid'           => $extendbid,	
					'brandbids'           => $brandbids,	
					'salecontent'           => ($data[getindex("ag")]),	
					'salestartdate'           => $salestartdate,	
					'saleenddate'           => $saleenddate,	
					//'brand_bid'              => intval($data[getindex("aj")]),
					'idate'              => time(),
					'ifpub'=>1,
					'checkstate'=>2
					  );
		//print_r($sql_data);exit;
		if(trim($data[19])=="" && trim($data[getindex("ae")])==""){
			$sql_data['sales'] = intval($data[getindex("ab")]);
		}
		if($gid<=0 && trim($data[getindex("I")])!="" && trim($data[getindex("m")])!=""){
			$Query_b = $DB->query("select max(goodsno) as maxno from `{$INFO[DBPrefix]}goods`");
			$Result_b= $DB->fetch_array($Query_b);
			$maxno = $Result_b['maxno'];
			$goodsno = num(intval($maxno)+1);
			$sql_data['goodsno'] = $goodsno;

			$db_string = $DB->compile_db_insert_string($sql_data);

			$Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result=$DB->query($Sql);
			$gid = mysql_insert_id();


		}else{
			if(trim($data[19])==""){
			 	$db_string = $DB->compile_db_update_string($sql_data);
				 $Sql = "UPDATE `{$INFO[DBPrefix]}goods` SET $db_string  WHERE gid=".intval($gid);
				$Result=$DB->query($Sql);

			}
		}
		if(intval($data[getindex("b")])>0 || intval($data[getindex("c")])>0||intval($data[getindex("d")])>0||intval($data[getindex("e")])>0||intval($data[getindex("f")])>0){
			//擴展分類
			$sql_p = "delete from `{$INFO[DBPrefix]}goods_class` where gid='" . intval($gid)  . "'";
			$DB->query($sql_p);
		}
		 if(intval($data[getindex("b")])>0){
			$sql_p = "insert into `{$INFO[DBPrefix]}goods_class` (gid,bid) values ('".intval($gid)."','" .intval($data[getindex("b")])  . "')";	
			 $Result_Insert=$DB->query($sql_p);
		 }
		 if(intval($data[getindex("c")])>0){
			 $sql_p = "insert into `{$INFO[DBPrefix]}goods_class` (gid,bid) values ('".intval($gid)."','" .intval($data[getindex("c")])  . "')";
			 $Result_Insert=$DB->query($sql_p);
		 }
		  if(intval($data[getindex("d")])>0){
			 $sql_p = "insert into `{$INFO[DBPrefix]}goods_class` (gid,bid) values ('".intval($gid)."','" .intval($data[getindex("d")])  . "')";
			 $Result_Insert=$DB->query($sql_p);
		 }
		 if(intval($data[getindex("e")])>0){
			 $sql_p = "insert into `{$INFO[DBPrefix]}goods_class` (gid,bid) values ('".intval($gid)."','" .intval($data[getindex("e")])  . "')";
			 $Result_Insert=$DB->query($sql_p);
		 }
		 if(intval($data[getindex("f")])>0){
			 $sql_p = "insert into `{$INFO[DBPrefix]}goods_class` (gid,bid) values ('".intval($gid)."','" .intval($data[getindex("f")])  . "')";
			 $Result_Insert=$DB->query($sql_p);
		 }

		
			//tag
			if(trim($data[getindex("v")])!=""){
				$sql_p = "delete from `{$INFO[DBPrefix]}attributegoods` where gid='" . intval($gid)  . "'";
				$DB->query($sql_p);
				$tag_array = explode(",",trim($data[getindex("v")]));
				$count_tag = count($tag_array);
				for($j=0;$j<$count_tag;$j++){
					if(trim($tag_array[$j])!=""){
						$db_string = $DB->compile_db_insert_string( array (
						'gid'                => intval($gid),
						'valueid'                => trim($tag_array[$j]),

						)      );


						$Sql="INSERT INTO `{$INFO[DBPrefix]}attributegoods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
						$Result_Insert=$DB->query($Sql);
					}
				}
			}
		}
		//多图

		if(trim($data[getindex("q")])!=""){
			$sql_p = "delete from `{$INFO[DBPrefix]}good_pic` where good_id='" . intval($gid)  . "' and color='" . trim($data[getindex("t")]) . "' and detail_name='" . trim($data[getindex("af")]) . "'";
			$DB->query($sql_p);
			$pic_array = explode(",",trim($data[getindex("q")]));
			$count_pic = count($pic_array);
			for($j=0;$j<$count_pic;$j++){
				if(trim($pic_array[$j])!=""){
					$db_string = $DB->compile_db_insert_string( array (
					'good_id'                => intval($gid),
					'color'                => trim($data[getindex("t")]),
					'goodpic_name'          =>  trim($pic_array[$j]),
					'bigpic'          => trim($pic_array[$j]),
					'middleimg'          => trim($pic_array[$j]),
					'detail_name'          => trim($data[getindex("af")]),	
					)      );


					$Sql="INSERT INTO `{$INFO[DBPrefix]}good_pic` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
					$Result_Insert=$DB->query($Sql);
				}
			}
		}
		$pm = explode(",",$data[getindex("ad")]);
		$pm_array = array_merge($pm_array, $pm); 
		$pm_array = array_unique($pm_array);
	}
	
		
}
include "SMTP.Class.inc.php";
include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
$Array =  array();
$j =0;
foreach($pm_array as $k=>$v){
	if(intval($v)>0)
		$pmstr[$j]="opid='" . intval($v) . "'";
	$j++;
}
 $Sql      = "select * from `{$INFO[DBPrefix]}operater` where  status=1 and (" . implode(" or ",$pmstr) . ")";

$Query    = $DB->query($Sql);
$operater_array = array();
$j = 0;
while($Rs_o=$DB->fetch_array($Query)){
	$operater_array[$j] = $Rs_o['email'];
	$j++;
}
$operater_str = implode(",",$operater_array);
$SMTP->MailForsmartshop($operater_str,"",38,$Array);
function getindex($name){
	return PHPExcel_Cell::columnIndexFromString($name)-1;	
}
function num($no){
	if(strlen($no)<6)
		return str_repeat("0",6-strlen($no)) . $no;
}
function excelTime($date, $time = false) {
    if(function_exists('GregorianToJD')){
        if (is_numeric( $date )) {
        $jd = GregorianToJD( 1, 1, 1970 );
        $gregorian = JDToGregorian( $jd + intval ( $date ) - 25569 );
        $date = explode( '/', $gregorian );
        $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )
        ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )
        ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )
        . ($time ? " 00:00:00" : '');
        return $date_str;
        }
    }else{
        $date=$date>25568?$date+1:25569;
        /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
        $ofs=(70 * 365 + 17+2) * 86400;
        $date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');
    }
  return $date;
}
//@header("location:admin_goods_list.php");
if($erow>0){
	//echo implode("<br>",$error_array);
	echo implode("",$error_array);
	echo "</table><br><br><a href='admin_goods_list.php'>返回商品列表</a>";
}else{
	$FUNCTIONS->sorry_back('admin_goods_list.php',"匯入成功");
}
?>