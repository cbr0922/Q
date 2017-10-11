<?php
include("../configs.inc.php");
include("global.php");

//$content=file_get_contents('https://tw.rter.info/capi.php');
//$currency=json_decode($content);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://tw.rter.info/capi.php');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec($ch);
curl_close($ch);
$currency=json_decode($content);
$exrate;
foreach($currency as $k=>$child){
	if($k=="USD" || $k=="USDTWD" || $k=="USDCNY" || $k=="USDJPY"){
		$exrate[$k] = $child->Exrate;
	}
}
//print_r($exrate);
echo json_encode($exrate);
?>
