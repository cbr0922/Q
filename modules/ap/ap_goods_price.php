<?php
@ob_start();
include( dirname(__FILE__)."/../../configs.inc.php");
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
$ftp_server    = "60.251.8.164";
$ftp_user_name = "IT_admin";
$ftp_user_pass = "T17888";
$remote_file     = "ECSalePrice/";
$download_dir = "ECSalePrice/";

$conn_id = ftp_connect($ftp_server);
if( $conn_id === FALSE ){
	die( "FTP連線失敗" );
}

$login_result = ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass);
if( $login_result === FALSE ){
	die( "FTP 登入失敗" );
}
ftp_pasv($conn_id, 1);


$filelist=ftp_nlist($conn_id,$remote_file); 
//print_r($filelist);exit;
foreach($filelist as $file){ 
	$path = explode('/',$file);
	//echo $download_dir . $path[1];
	$ret = ftp_get($conn_id, $download_dir . $path[1] , $file, FTP_BINARY);
	if ( $ret ) {
		echo "下載檔案成功: $file ->$download_dir" . $path[1] ."\n";
		ftp_delete($conn_id,$file);
		$i=0;
		$handle = fopen ($download_dir . $path[1],"r");
		$pm_array = array();
		$p = 0;
		
		while ($datastr = fgets ($handle, 1024)) {
			$gid = 0;
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
		fclose ($handle);
	}else{
			echo "下載檔案失敗: $file ->$download_dir". $path[1] ."\n";
	}
	
}


//exit;
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