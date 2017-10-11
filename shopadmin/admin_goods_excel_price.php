<?php
@ob_start();
include_once "Check_Admin.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
$i=0;
$handle = fopen ($_FILES['cvsEXCEL']['tmp_name'],"r");
$pm_array = array();
$p = 0;
while ($datastr = fgets ($handle, 1024)) {
	//$datastr = ($datastr);
	//$data = explode(",",$datastr);
	//echo $datastr;
	//if ($i>0){
	$bn = trim(substr($datastr,0,20));
	$price = intval(substr($datastr,20,8));
	$pricedesc = intval(substr($datastr,28,8));
	$storages = intval(substr($datastr,36,8));
	$color = "";
	$sql = "select * from `{$INFO[DBPrefix]}goods_detail` where detail_bn = '" . $bn . "'";
	$Query_goods    = $DB->query($sql);
	$Num_trans      = $DB->num_rows($Query_goods);
	if($Num_trans>0){
		$Rs = $DB->fetch_array($Query_goods);
		$gid = $Rs['gid'];
		$org_price = $Rs['detail_price'];
		$org_pricedesc = $Rs['detail_pricedes'];
		$gujima = $Rs['gujima'];
		$db_string = $DB->compile_db_insert_string( array (
			'bn'          => $bn,
			'gujima'          => $gujima,
			'color'         => "",
			'org_price'               => $org_price,
			'org_pricedesc'            => $org_pricedesc,
			'new_price'         => $price,
			'new_pricedesc'             => $pricedesc,
			'storages'             => $storages,
			'pubtime'              => time(),
			'state'              => 0,
			'gid'            => $gid,
		)      );
		 $Result =  $DB->query("update `{$INFO[DBPrefix]}goods_detail` set detail_price='" . $price . "',detail_pricedes='" . $pricedesc . "' where detail_id='". $Rs['detail_id'] . "'") ;
		 $Sql="INSERT INTO `{$INFO[DBPrefix]}goods_price_cach` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Insert=$DB->query($Sql);
	}else{
		$sql = "select * from `{$INFO[DBPrefix]}attributeno` where goodsno = '" . $bn . "'";
		$Query_goods    = $DB->query($sql);
		$Num_trans      = $DB->num_rows($Query_goods);
		if ($Num_trans > 0){
			$Rs = $DB->fetch_array($Query_goods);
			$gid = $Rs['gid'];
			$color = $Rs['color'];
			$gujima = $Rs['gujima'];
			$sql = "select * from `{$INFO[DBPrefix]}goods` where gid = '" . $gid . "'";
		}else{
			$sql = "select * from `{$INFO[DBPrefix]}goods` where bn = '" . $bn . "'";
		}
		$Query_goods    = $DB->query($sql);
		$Num_trans      = $DB->num_rows($Query_goods);
		if ($Num_trans > 0){
			$Rs = $DB->fetch_array($Query_goods);
			$gid = $Rs['gid'];
			$org_price = $Rs['price'];
			$org_pricedesc = $Rs['pricedesc'];
			$gujima = $Rs['gujima'];
		}
		$db_string = $DB->compile_db_insert_string( array (
			'bn'          => $bn,
			'gujima'          => $gujima,
			'color'         => $color,
			'org_price'               => $org_price,
			'org_pricedesc'            => $org_pricedesc,
			'new_price'         => $price,
			'new_pricedesc'             => $pricedesc,
			'storages'             => $storages,
			'pubtime'              => time(),
			'state'              => 0,
			'gid'            => $gid,
			)      );
			 $Result =  $DB->query("update `{$INFO[DBPrefix]}goods` set price='" . $price . "',pricedesc='" . $pricedesc . "' where gid='". $gid . "'") ;
			 $Sql="INSERT INTO `{$INFO[DBPrefix]}goods_price_cach` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result_Insert=$DB->query($Sql);
			
			//$pm = explode(",",$Rs['pm']);
			//$pm_array = array_merge($pm_array, $pm); 
			//$pm_array = array_unique($pm_array);
	//}
	}
	$i++;
}
fclose ($handle);//exit;
/*
include "SMTP.Class.inc.php";
include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
$Array =  array();
$pmstr = array();
$j =0;
foreach($pm_array as $k=>$v){
	if(intval($v)>0)
		$pmstr[$j]="opid='" . intval($v) . "'";
	$j++;
}

if($j>0){
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
}
*/
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
?>