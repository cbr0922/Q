<?php
@ob_start();
include_once "Check_Admin.php";
//include Classes . "/global.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
$FUNCTIONS->setLog("匯入物流配送");
$i=0;
$handle = fopen ($_FILES['cvsEXCEL']['tmp_name'],"r");
while ($datastr = fgets ($handle, 1024)) {
	$datastr = big52utf8($datastr);
	$data = explode(",",$datastr);
	//編號、訂單編號、 託運單號、物流單位、出貨日期
		$sql = "select * from `{$INFO[DBPrefix]}order_table` where order_serial = '" . $data[1] . "'";
		$Query_goods    = $DB->query($sql);
		$Num_trans      = $DB->num_rows($Query_goods);
		if ($Num_trans > 0){
			$Result = $DB->query("update `{$INFO[DBPrefix]}order_table` set piaocode='" . $data[2] . "',sendname='" . $data[3] . "',sendtime='" . $data[4] . "' where order_serial = '" . $data[1] . "'");	
	}
	$i++;
}

fclose ($handle);
//exit;
@header("location:admin_order_list.php");
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
?>