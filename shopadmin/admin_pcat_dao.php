<?php
@ob_start();
include_once "Check_Admin.php";
//include Classes . "/global.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");

$i=0;
$file = fopen ("brand.csv","r");

while(! feof($file)){
	//print_r(fgetcsv($file,10240,",","\""));
	$data = __fgetcsv($file,20240,",","\"");
	
	//$Goods_query = $DB->query("select g.gid from `{$INFO[DBPrefix]}goods` g where bn='".trim($data[7])."'  order by gid desc ");
	//$Num   = $DB->num_rows($Goods_query);
	if (trim($data[0])>0){
		
	    $db_string = $DB->compile_db_insert_string( array (
						'bid'                => trim($data[0]),			
						'catname'                => trim($data[1]),			
				  
				  
					)      );
	$Sql="INSERT INTO `{$INFO[DBPrefix]}bclass` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
					$Result=$DB->query($Sql);
					
	}
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

?>