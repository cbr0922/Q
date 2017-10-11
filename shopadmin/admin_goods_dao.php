<?php
@ob_start();
include_once "Check_Admin.php";
//include Classes . "/global.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
include("product.class.php");
$PRODUCT = new PRODUCT();

$file = fopen ("godos.csv","r");

$j=0;
while(! feof($file)){
	//print_r(fgetcsv($file,10240,",","\""));
	$data = __fgetcsv($file);
	
	if($data[4]!=""){
	//print_r($data);exit;
	//$Goods_query = $DB->query("select g.gid from `{$INFO[DBPrefix]}goods` g where bn='".trim($data[7])."'  order by gid desc ");
	//$Num   = $DB->num_rows($Goods_query);
//	if ($Num<=0){
//分類
	$class_banner = array();
	$list = 0;
	$bid_array = array();
	$PRODUCT->getTopBidList(intval($data[3]));
	$bid_array[0] = $class_banner;
	
	$extendbid = json_encode($bid_array);

		
		
		if($ttype==1){
			$iftransabroad=0;
		}else{
			$iftransabroad=1;
		}
	$goodsno = num(intval($j)+1);
		
		

	    $db_string = $DB->compile_db_insert_string( array (

						'bid'                => trim($data[3]),			
						
				  'bn'                => trim($data[1]),
				  'goodsname'          => str_replace("\"","'",trim($data[4])),
				  'intro'              => str_replace("<br>","",trim($data[7])),
				  'body'              => $body,
					'extendbid'              => $extendbid,
				  'cap_des'          => trim($data[8]),
				
				 
				  'smallimg'           => trim($data[0].".jpg"),
					'bigimg'             => trim($data[0].".jpg"),
					'gimg'               => trim($data[0].".jpg"),
					'middleimg'          => trim($data[0].".jpg"),
				 
				  'price'          => intval($data[5]),
				  'pricedesc'          => intval($data[5]),
				  
				  'idate'          => $ptime,
				 
				  'ifpub'              => 0,
			'checkstate'              => 0,
			'ttype'              => 0,
			'freetran'              => 1,
			
			
			'goodsno'=>$goodsno,
			'iftransabroad'=>1,
			'ifmood'=>1
					)      );
	$Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result=$DB->query($Sql);
	//$gid = mysql_insert_id();
	
	
	}
	
		$j++;			
//	}
}

fclose($file);
//exit;
@header("location:admin_goods_list.php");
function big52utf8($big5str) {

$blen = strlen($big5str);
$utf8str = "";

for($i=0; $i<$blen; $i++) {

$sbit = ord(substr($big5str, $i, 1));
//echo $sbit;
//echo "<br>";
if ($sbit < 129) {
$utf8str.=substr($big5str,$i,1);
}elseif ($sbit > 128 && $sbit < 255) {
$new_word = iconv("BIG5", "UTF-8", substr($big5str,$i,2));
$utf8str.=($new_word=="")?"?":$new_word;
$i++;
}
}

return $utf8str;

}

function __fgetcsv(& $handle, $length = null, $d = ',', $e = '"') { 
$d = preg_quote($d); 
$e = preg_quote($e); 
$_line = ""; 
$eof=false; 
while ($eof != true) { 
$_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length)); 
$itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy); 
if ($itemcnt % 2 == 0) 
$eof = true; 
} 
$_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line)); 
$_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/'; 
preg_match_all($_csv_pattern, $_csv_line, $_csv_matches); 
$_csv_data = $_csv_matches[1]; 
for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++) { 
$_csv_data[$_csv_i] = preg_replace('/^' . $e . '(.*)' . $e . '$/s', '$1' , $_csv_data[$_csv_i]); 
$_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]); 
} 
return empty ($_line) ? false : $_csv_data; 
} 
function num($no){
	return str_repeat("0",6-strlen($no)) . $no;
}
?>