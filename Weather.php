<!DOCTYPE html>
<html>
<body>

<?php
$url="http://opendata.cwb.gov.tw/opendataapi?dataid=F-C0032-001&authorizationkey=CWB-89832A39-998D-479A-A3E0-46DDE06F9109";
$xml=simplexml_load_file($url);
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
$id=0;
$area = $xml->dataset->location[$id]->locationName;
$weather = $xml->dataset->location[$id]->weatherElement[0]->time[0]->parameter;
$thermometer = $xml->dataset->location[$id]->weatherElement[1]->time[0]->parameter;

echo $area . "<br>";
echo $weather->parameterName . " " . $weather->parameterValue . "<br>";
echo $thermometer->parameterName . " " . $thermometer->parameterUnit . "<br>";

?>

</body>
</html>