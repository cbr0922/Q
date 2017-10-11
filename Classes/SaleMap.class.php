<?php
//=======================================================================
// File:	SaleMap.PHP
// Description:	Run all the example script in current directory
// Created: 	2005-4-30
// Author:	tyler.wu (php_netproject@yahoo.com.cn)
// Copyright (C) 2005 tyler.wu
//========================================================================


class SaleMap {
    

//获得统计资料 
//(参与计算的天数，SQL，日期（日）的字段，总数字，年，月，日)
function SaleMap_DayAndMonth($DayNum,$Sql,$fields_unit,$fields_totalprices,$year,$month,$day){
global $DB;

//获得一个全部数值为0的数组
for($i=1;$i<=$DayNum;$i++){  
$Tmp_array[$i] = 0;
}

$Query = $DB->query($Sql);

//获得具备资料的数据
$i=0;
$Detail = array();
while($Rs = $DB->fetch_array($Query)){ 
  $Detail[$i][$fields_unit]         = intval($Rs[$fields_unit]);  //将拥有数值的天数写入树组中！！
  $Detail[$i][$fields_totalprices]  = $Rs[$fields_totalprices];
$i++;
}

//根据月份的资料重新排列数组
$i=0;
while($i<count($Detail)){
//在这里，根据树组中数据，获得在大排行中的位置。从而将有数值的数据成功插入大树组中！！
$k	= ($Detail[$i][$fields_unit]);  
$Tmp_array[$k] = $Detail[$i][$fields_totalprices];
$i++;
}

foreach($Tmp_array as $k=>$v){
$Tmp_String .= $v.",";
}

$Tmp_String = $Tmp_String.$year.",".$month.",".$day;

$DB->free_result($Query);

unset ($Query);
unset ($Rs);
unset ($Detail);
unset ($Tmp_array);
//$Tmp_String = substr($Tmp_String,0,strlen($Tmp_String)-1);
return $Tmp_String; //返回最终将要获得的字符串

}




}?>