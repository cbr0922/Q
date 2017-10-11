<?php
error_reporting(7);

class TimeClass    
{         
	
	//获得指定月的天数
	function getMonthLastDay($month,$year)
	{
		$nextMonth = (($month+1)>12) ? 1 : ($month+1);
		$year      = ($nextMonth>12) ? ($year+1) : $year;
		$lastDay   = date('d',mktime(0,0,0,$nextMonth,0,$year));
		return $lastDay;
	}	

   //一般是计算页面执行时间的！！
   //例如：  $time_start=getmicrotime(); //放在头部
   //		 $time_end = getmicrotime();
   // 		 $alltime=($time_end-$time_start);   本页执行所需时间效率：".$alltime."秒 就这样获得了！
   function getmicrotime()
   { 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
   } 
    
    //获得指定unix时间戳记
	function getSpetime($type,$value)
	{
       	$Year      = date("Y",time());
		$Month     = date("m",time());
		$Day       = date("d",time());
		$Hour      = date("H",time());
		$Minute    = date("i",time());
		$Second    = date("s",time());


        switch ($type){
		  case "Year":
            $Year=$Year+(intval($value));
		  break;
		  
		  case "Month":
            $Month=$Month+(intval($value));
		  break;

		  case "Day":
            $Day=$Day+(intval($value));
		  break;

		  case "Hour":
            $Hour=$Hour+(intval($value));
		  break;

		  case "Minute":
            $Minute=$Minute+(intval($value));
		  break;

		  case "Second":
            $Second=$Second+(intval($value));
		  break;
		}	
	     if (intval($Year) < 1970 && substr(PHP_OS, 0, 3) == 'WIN'){
           $Year = 1970;
		 }
	   $lastDay   = mktime($Hour,$Minute,$Second,$Month,$Day,$Year);

	return $lastDay;
	}
	
	//根据UNIX时间戳记获得其日子相差的日期，返回值是Y-m-d格式
	function ForGetDate($type,$value,$ReturnType)
	{
		$Year      = date("Y",time());
		$Month     = date("m",time());
		$Day       = date("d",time());

        switch ($type){
		  case "Year":
            $Year=$Year+(intval($value));
		  break;
		  
		  case "Month":
            $Month=$Month+(intval($value));
		  break;

		  case "Day":
            $Day=$Day+(intval($value));
		  break;

		  case "Hour":
            $Hour=$Hour+(intval($value));
		  break;

		  case "Minute":
            $Minute=$Minute+(intval($value));
		  break;

		  case "Second":
            $Second=$Second+(intval($value));
		  break;
		}	
        if (intval($Year) < 1970 && substr(PHP_OS, 0, 3) == 'WIN'){
           $Year = 1970;
		 }
		$lastDay   = date($ReturnType,mktime($Hour,$Minute,$Second,$Month,$Day,$Year));

		return $lastDay;
	}	


   //根据值获得UNIX时间戳记/
    function ForGetUnixTime($Year,$Month,$Day,$Hour,$Minute,$Second)
	{
        if (intval($Year) < 1970 && substr(PHP_OS, 0, 3) == 'WIN'){
           $Year = 1970;
		 }
		$lastDay   = mktime($Hour,$Minute,$Second,$Month,$Day,$Year);

		return $lastDay;
	}	
  //根据传入的时间格式获得UNIX时间戳记/
	function ForYMDGetUnixTime($Date,$forex)
	{
		$Array_Date = explode($forex,$Date);


        if (intval($Array_Date[0]) < 1970 && substr(PHP_OS, 0, 3) == 'WIN'){
           $Year = 1970;
		 }else{
		   $Year      = intval($Array_Date[0]);
		 }
		$Month     = intval($Array_Date[1]);
		$Day       = intval($Array_Date[2]);

		$lastDay    = mktime(intval($Hour),intval($Minute),intval($Second),$Month,$Day,$Year);

		return $lastDay;
	}	
}/*类结束标识*/


?>