<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("../../configs.inc.php");
include ("global.php");
$_GET['ncid']=19;
	$cid = intval($_GET['cid']);
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}photoclass` where id=".intval($cid)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$name            =  $Result['name'];
	}else{
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}photoclass` limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$name            =  $Result['name'];
			$cid = intval($Result['id']);
		}else{
			echo "<script language=javascript>javascript:location.href='index.php';</script>";
			exit;
		}
	}


$Sql      = "select * from `{$INFO[DBPrefix]}photo` where classid='" . intval($cid) . "' order by id  ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$i = 0;
while ($Rs=$DB->fetch_array($Query)) {
	$file_array = array();
	$photo_array[$i]['id'] = $Rs['id'];
	$photo_array[$i]['name'] = $Rs['name'];
	$photo_array[$i]['videoid'] = $Rs['videoid'];
	$handle=opendir( RootDocument.'/UploadFile/photo_img'."/".$Rs['id']."/images");
		$file_array = array();
		$j = 0;
        while ($file = readdir($handle)) {
		   $filel=strtolower($file);
		   if(substr($filel,-3,3)=="gif" || substr($filel,-3,3)=="jpg" || substr($filel,-3,3)=="png" || substr($filel,-3,3)=="bmp")
		   {
			  $filetime = filemtime( RootDocument."/UploadFile/photo_img/".$Rs['id']."/images/" . $file);
			  $filesize = round(filesize( RootDocument."/UploadFile/photo_img/".$Rs['id']."/images/" . $file)/1024);
			  $file_array[$j]['name'] = $file;
			  $file_array[$j]['time'] = $filetime;
			  $file_array[$j]['size'] = $filesize;
			  $j++;
		   }
        }
		$count = count($file_array);
		$file_array = $count>0?sysSortArray($file_array,"time","SORT_ASC"):$file_array;
		$photo_array[$i]['image'] = $file_array[0]['name'];
		
	$i++;
}

function sysSortArray($ArrayData,$KeyName1,$SortOrder1 = "SORT_ASC",$SortType1 = "SORT_REGULAR") { 
	if(!is_array($ArrayData)) { 
	return $ArrayData; 
	} 
	// Get args number. 
	$ArgCount = func_num_args(); 
	// Get keys to sort by and put them to SortRule array. 
	for($I = 1;$I < $ArgCount;$I ++) { 
		$Arg = func_get_arg($I); 
		if(!eregi("SORT",$Arg)) { 
			$KeyNameList[] = $Arg; 
			$SortRule[]    = '$'.$Arg; 
		} 
		else { 
			$SortRule[]    = $Arg; 
		} 
	} 
	
	// Get the values according to the keys and put them to array. 
	foreach($ArrayData AS $Key => $Info) { 
		foreach($KeyNameList AS $KeyName) {
			${$KeyName}[$Key] = $Info[$KeyName]; 
		} 
	} 
	
	// Create the eval string and eval it. 
	$EvalString = 'array_multisort('.join(",",$SortRule).',$ArrayData);'; 
	eval ($EvalString); 
	return $ArrayData; 
} 

$Sql      = "select * from `{$INFO[DBPrefix]}photoclass`  order by id  ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$i = 0;
while ($Rs=$DB->fetch_array($Query)) {
	$photoclass_array[$i]['name'] = $Rs['name'];
	$photoclass_array[$i]['id'] = $Rs['id'];
	$i++;
}

$tpl->assign("classid",      $_GET['cid']); 
$tpl->assign("photo_name",      $name); 
$tpl->assign("videoid",   $videoid); 
$tpl->assign("photo_array",      $photo_array); 
$tpl->assign("photoclass_array",      $photoclass_array); 
$tpl->display("photoindex.html");
 ?>
