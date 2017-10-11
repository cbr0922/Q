<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("../../configs.inc.php");
include ("global.php");
$_GET['ncid']=19;

$laguage = "cn";

if ($_GET['id']!=""){

	$id = intval($_GET['id']);

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}photo` where id=".intval($id)." limit 0,1");

	$Num   = $DB->num_rows($Query);



	if ($Num>0){

		$Result= $DB->fetch_array($Query);

		$name            =  $Result['name'];

		$classid = $Result['classid'];

		$type = $Result['type'];

		$content = $Result['content'];

		$pubdate = $Result['pubdate'];

		$film = $Result['film'];

		$tpl->assign("content",      $content); 

		$tpl->assign("classid",      $classid); 

		$tpl->assign("type",      $type); 

		$tpl->assign("film",      $film); 

		$Sql_i      = "select * from `{$INFO[DBPrefix]}image` where pid='" . $id . "' order by iid asc";

		$Query_i    = $DB->query($Sql_i);

		$image_array = array();

		$i = 0;

		while($Rs_i=$DB->fetch_array($Query_i)){

			$image_array[$i]['pic'] =$Rs_i['pic'];

			$image_array[$i]['content'] =$Rs_i['content'];

			$i++;	

		}

		$tpl->assign("image_array",      $image_array); 

	}else{

		echo "<script language=javascript>javascript:location.href='photo.php?id=1';</script>";

		exit;

	}



}



$Query = $DB->query("select * from `{$INFO[DBPrefix]}photoclass` where id=".intval($classid)." limit 0,1");

	$Num   = $DB->num_rows($Query);



	if ($Num>0){

		$Result= $DB->fetch_array($Query);

		$classname            =  $Result['name'];

	}



$Sql      = "select * from `{$INFO[DBPrefix]}photo` where classid='" . $classid . "' and language='" . $laguage . "' order by id  ";



$Query    = $DB->query($Sql);

$Num      = $DB->num_rows($Query);

$i = 0;

while ($Rs=$DB->fetch_array($Query)) {

	$photo_array[$i]['id'] = $Rs['id'];

	$photo_array[$i]['name'] = $Rs['name'];

	

	$i++;

}






$tpl->assign("image_array",      $image_array); 
$tpl->assign("photoclass_array",      $photoclass_array); 
$tpl->assign("photo_id",      $_GET['id']); 
$tpl->assign("photo_name",      $name); 
$tpl->assign("photo_array",      $photo_array); 
$tpl->display("photo.html");
 ?>
