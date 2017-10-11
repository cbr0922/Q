<?php
@ob_start();
ini_set("memory_limit", "100M");  
include_once "Check_Admin.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
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
for($currentRow = 2;$currentRow <= $allRow;$currentRow++){ 
/**从第A列开始输出*/ 
	$data = array();
	for ($column = 0; $column < $end_index; $column++) {  
		$col_name = PHPExcel_Cell::stringFromColumnIndex($column);
		$address = $col_name.$currentRow;
		$data[$column] = $currentSheet->getCell($address)->getValue();/**ord()将字符转为十进制数*/ 
	}
	
	 $sql = "select * from `{$INFO[DBPrefix]}goods` where bn = '" . trim($data[getindex("I")]) . "'";
	$Query_goods    = $DB->query($sql);
	$Num_trans      = $DB->num_rows($Query_goods);
	if ($Num_trans > 0){
		$Rs = $DB->fetch_array($Query_goods);
		$gid = $Rs['gid'];
	}else{
		$gid = 0;	
	}
		//匯入屬性
		if(trim($data[14])!="" || trim($data[15])!=""){
		  
		   $goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where goodsno='" . trim($data[13]) . "' ";
		  $goods_Query =  $DB->query($goods_Sql);
		  $goods_Num   =  $DB->num_rows($goods_Query );
		  $good_color_array = array();
		  $good_size_array = array();
		  if($goods_Num<=0){
			  $good_color  =  $Rs['good_color'];
			  $good_size  =  $Rs['good_size'];
			  if($good_color=="")
				  $count_c = 0;
			  else{
				  $good_color_array = explode(",",$good_color);
				  $count_c = count($good_color_array);
			  }
			  if($good_size=="")
				  $count_s = 0;
			  else{
				  $good_size_array = explode(",",$good_size);
				  $count_s = count($good_size_array);
			  }
			  if(trim($data[15])!=""){
				  if(in_array(trim($data[15]),$good_size_array)){
					  
				  }else{
					  $good_size_array[$count_s] = trim($data[15]);	
				  }
			  }
			  if(trim($data[14])!=""){
				  if(in_array(trim($data[14]),$good_color_array)){
					  
				  }else{
					  $good_color_array[$count_c] = trim($data[14]);	
				  }
			  }
			  $Result = $DB->query("update `{$INFO[DBPrefix]}goods` set good_color='" . implode(",",$good_color_array) . "',good_size='" . implode(",",$good_size_array) . "' where gid = '" . $gid . "'");	
			  $update_Sql = "insert into `{$INFO[DBPrefix]}attributeno`(gid,size,color,goodsno)values('" . $gid . "','" . trim($data[15]) . "','" . trim($data[14]) . "','" . trim($data[13]) . "')";	
			  $DB->query($update_Sql);
			  $update_Sql = "insert into `{$INFO[DBPrefix]}storage`(goods_id,size,color,storage)values('" . $gid . "','" . trim($data[15]) . "','" . trim($data[14]) . "','" . trim($data[getindex("x")]) . "')";
			   $DB->query($update_Sql);
			  //$FUNCTIONS->setStorage(trim($data[getindex("x")]),0,$gid,0,trim($data[15]),trim($data[14]),"商品匯入",0,0,array(),1);
		  }else{
				$update_Sql = "update `{$INFO[DBPrefix]}storage` set storage='" . trim($data[getindex("x")]) . "' where size='" . trim($data[15]) . "' and color='" . trim($data[14]) . "' and goods_id='" . $gid . "'"; 
				 $DB->query($update_Sql);
		  }
		  
		
	  	}
		//echo excelTime($data[getindex("bc")]);
		if( $data[getindex("bc")]!=""){
			//print_r($data);
			 $data[getindex("bc")] = strtotime( excelTime($data[getindex("bc")],true));
			
		}
		if( $data[getindex("bd")]!="")
			 $data[getindex("bd")] = strtotime( excelTime($data[getindex("bd")],true));
		if( $data[getindex("Az")]!="")
			 $data[getindex("Az")] = strtotime( excelTime($data[getindex("Az")],true));	
		if( $data[getindex("ba")]!="")
			 $data[getindex("ba")] = strtotime( excelTime($data[getindex("ba")],true));	
			
		$sql_data = array (
				'bid'                => intval($data[getindex("b")]),
				//'provider_id'        => intval($data[2]),
				'brand_id'           => intval($data[getindex("H")]),
				'bn'                 => trim($data[getindex("I")]),
				//'goodsno'                =>trim($data[getindex("J")]),
				'goodsname'          => trim($data[getindex("L")]),
				'sale_name'              => trim($data[getindex("M")]),
				'price'              => intval($data[getindex("R")]),
				'pricedesc'          => intval($data[getindex("s")]),
				'cost'           => intval($data[getindex("t")]),
				'unit'               => trim($data[getindex("u")]),
				'ifalarm'            => intval($data[getindex("v")]),
				'alarmnum'           => intval($data[getindex("w")]),
				'storage'           => intval($data[getindex("x")]),
				'view_num'           => intval($data[getindex("y")]),
				'guojima'           => trim($data[getindex("z")]),
				'xinghao'           => trim($data[getindex("Aa")]),
				'weight'              => ($data[getindex("Ab")]),
				'keywords'           => $data[getindex("Ad")],
				'intro'              => $data[getindex("Ac")],
				'alarmcontent'              => $data[getindex("ah")],
				'mixcontent'              => $data[getindex("Ae")],
				'cap_des'              => trim($data[getindex("Ag")]),
				'body'               =>  $data[getindex("Af")],
				//'ifrecommend'        => intval($data[getindex("A")]),
				'ifspecial'          => intval($data[getindex("Ai")]),
				'ifhot'              => intval($data[getindex("Aj")]),
				'ifpack'              => intval($data[getindex("Ak")]),
				'ifgoodspresent'              => intval($data[getindex("Al")]),
				//'ifmix'              => intval($data[getindex("Am")]),
				'salecost'              => intval($data[getindex("An")]),
				'point'              => intval($data[getindex("Ao")]),
				'ifbonus'            => intval($data[getindex("Ap")]),
				'bonusnum'           => intval($data[getindex("Aq")]),
				'ifchange'              => intval($data[getindex("Ar")]),
				'ifadd'              => intval($data[getindex("As")]),
				'addmoney'              => intval($data[getindex("At")]),
				'addprice'              => intval($data[getindex("Au")]),
				'ifpresent'      =>  intval($data[getindex("Av")]),
				'present_money'      =>  intval($data[getindex("Aw")]),
				'present_endmoney'      =>  intval($data[getindex("Ax")]),
				'ifsaleoff'              => intval($data[getindex("Ay")]),
				'saleoff_starttime'      => $data[getindex("Az")],
				'saleoff_endtime'      => $data[getindex("bA")],
				'iftimesale'              => intval($data[getindex("bb")]),
				'timesale_starttime'      => $data[getindex("bc")],
				'timesale_endtime'      => $data[getindex("bd")],
				'saleoffprice'              => intval($data[getindex("be")]),
				'saleoffcount'              => intval($data[getindex("bf")]),
				'trans_type'              => intval($data[getindex("bg")]),
				'iftransabroad'              => intval($data[getindex("bh")]),
				'ttype'              => intval($data[getindex("bi")]),
				'ifmood'              => intval($data[getindex("bj")]),
				'idate'              => time(),
				  );
	//print_r($sql_data);exit;
	if($gid<=0){
		$Query_b = $DB->query("select max(goodsno) as maxno from `{$INFO[DBPrefix]}goods`");
		$Result_b= $DB->fetch_array($Query_b);
		$maxno = $Result_b['maxno'];
		$goodsno = num(intval($maxno)+1);
		$sql_data['goodsno'] = $goodsno;
		$db_string = $DB->compile_db_insert_string($sql_data);
		 $Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result=$DB->query($Sql);
		 $gid = mysql_insert_id();
		 //擴展分類
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
		 if(intval($data[getindex("g")])>0){
			 $sql_p = "insert into `{$INFO[DBPrefix]}goods_class` (gid,bid) values ('".intval($gid)."','" .intval($data[getindex("g")])  . "')";
			 $Result_Insert=$DB->query($sql_p);
		 }
		 //組合商品
		if(trim($data[getindex("k")])!=""){
			$sql = "select * from `{$INFO[DBPrefix]}goods` where bn = '" . $data[getindex("k")] . "'";
			$Query_goods    = $DB->query($sql);
			$Num_trans      = $DB->num_rows($Query_goods);
			if ($Num_trans > 0){
				$Rs = $DB->fetch_array($Query_goods);
				$topgid = $Rs['gid'];
				 $Sql = " insert into `{$INFO[DBPrefix]}goods_pack` (gid,packgid,cost,idate,count,color,size) values('".intval($topgid)."','".intval($gid)."','".intval($data[getindex("t")])."','".time()."','" . intval($data[getindex("q")]) . "','" . ($data[getindex("o")]) . "','" . ($data[getindex("p")]) . "')";
				$Result =  $DB->query($Sql);
			}
		}
	}else{
		if((trim($data[14])=="" && trim($data[15])=="")||trim($data[getindex("k")])!=""){
			$db_string = $DB->compile_db_update_string($sql_data);
			 $Sql = "UPDATE `{$INFO[DBPrefix]}goods` SET $db_string  WHERE gid=".intval($gid);
			$Result=$DB->query($Sql);
			//擴展分類
			$sql_p = "delete from `{$INFO[DBPrefix]}goods_class` where gid='" . intval($gid)  . "'";
			$DB->query($sql_p);
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
			 if(intval($data[getindex("g")])>0){
				 $sql_p = "insert into `{$INFO[DBPrefix]}goods_class` (gid,bid) values ('".intval($gid)."','" .intval($data[getindex("g")])  . "')";
				 $Result_Insert=$DB->query($sql_p);
			 }
		}
		 //組合商品
		if(trim($data[getindex("k")])!=""){
			$sql = "select * from `{$INFO[DBPrefix]}goods` where bn = '" . $data[getindex("k")] . "'";
			$Query_goods    = $DB->query($sql);
			$Num_trans      = $DB->num_rows($Query_goods);
			if ($Num_trans > 0){
				$Rs = $DB->fetch_array($Query_goods);
				$topgid = $Rs['gid'];
				 $Sql = " insert into `{$INFO[DBPrefix]}goods_pack` (gid,packgid,cost,idate,count,color,size) values('".intval($topgid)."','".intval($gid)."','".intval($data[getindex("t")])."','".time()."','" . intval($data[getindex("q")]) . "','" . ($data[getindex("o")]) . "','" . ($data[getindex("p")]) . "')";
				$Result =  $DB->query($Sql);
			}
		}
	}
		 
	
		
} 
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
@header("location:admin_goods_list.php");
?>