<?php
error_reporting(7);
session_start();
@header("Content-type: text/html; charset=utf-8");
include( dirname( __FILE__ )."/"."../configs.inc.php");
include ("global.php");

$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where info_id='36' limit 0,1");
$Result  = $DB->fetch_array($Query);
if ($Result[info_id]==36){
  $weather_array = json_decode($Result[info_content]);
}
//echo date("H",time())/3 ."*". $weather_array->time/3;exit;
if(date("H",time())/3 != $weather_array->time/3){
  $url="http://opendata.cwb.gov.tw/opendataapi?dataid=F-D0047-005&authorizationkey=CWB-89832A39-998D-479A-A3E0-46DDE06F9109";
  //$xml=simplexml_load_file($url);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_FAILONERROR,1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $sXML = curl_exec($ch);
  curl_close($ch);
  $xml = simplexml_load_string($sXML);

  //0 : 臺北市 1 : 新北市 2 : 桃園市 3 : 臺中市 4 : 臺南市 5 : 高雄市 6 : 基隆市 7 : 新竹縣 8 : 新竹市 9 : 苗栗縣 10 : 彰化縣 11 : 南投縣 12 : 雲林縣 13 : 嘉義縣 14 : 嘉義市 15 : 屏東縣 16 : 宜蘭縣 17 : 花蓮縣 18 : 臺東縣 19 : 澎湖縣 20 : 金門縣 21 : 連江縣
  //0 : Wx 1 : MaxT 2 : MinT 3 : CI 4 : PoP
  //1:晴天, 2:多雲, 3:陰天, 4:陰陣雨|雨天, 5:多雲時陰, 6:陰時多雲, 7:多雲時晴, 8:晴時多雲, 9:10:11:, 12:多雲短暫雨|多雲時陰短暫陣雨|多雲時陰陣雨|陣雨, 13:多雲午後短暫陣雨, 18:多雲午後短暫雷陣雨, 24:晴午後短暫陣雨, 26:陰時多雲短暫陣雨|陰短暫陣雨, 36:雷雨, 61:下雪,
  /*
  $i=0;
  foreach($xml->dataset->location as $child){
    echo $i++ . " : " . $child->locationName . " ";
  }
  echo "<br>";
  $i=0;
  foreach($xml->dataset->location->weatherElement as $child){
    echo $i++ . " : " . $child->elementName . " ";
  }
  echo "<br>";
  */
  $id=1;
  $area = "桃園國際機場";//$xml->dataset->location[$id]->locationName;
  $weather = $xml->dataset->locations->location[$id]->weatherElement[6]->time[0];
  $thermometer = $xml->dataset->locations->location[$id]->weatherElement[0]->time[0]->elementValue;
  $status = $weather->elementValue->value;
  $status_num = $weather->parameter->parameterValue;
  $temperature = $thermometer->value;
  $Unit = $thermometer->measures;
  //echo $area . "<br>";
  //echo $weather->parameterName . " " . $weather->parameterValue . "<br>";
  //echo $thermometer->parameterName . " " . $thermometer->parameterUnit . "<br>";

  $weather_array = array();
  $weather_array['time'] = date("H",time());
  $weather_array['Area'] = urlencode(trim($area));
  $weather_array['Status'] = urlencode(trim($status));
  $weather_array['Status_num'] = trim($status_num);
  $weather_array['Temperature'] = trim($temperature);
  $weather_array['Unit'] = urlencode(trim($Unit));
//print_r($weather_array);
  $Sql = "UPDATE `{$INFO[DBPrefix]}admin_info` SET info_content='".json_encode($weather_array). "' WHERE info_id='36'";
	$DB->query($Sql);

}else {
  $area = urldecode($weather_array->Area);
  $status = urldecode($weather_array->Status);
  $status_num = $weather_array->Status_num;
  $temperature = $weather_array->Temperature;
  $Unit = urldecode($weather_array->Unit);
}
$tpl->assign("Area",  $area);
$tpl->assign("Status",  $status);
$tpl->assign("Status_num",  $status_num);
$tpl->assign("Temperature",  $temperature);
$tpl->assign("Unit",  $Unit);
$tpl->display("Weather.html");
?>
