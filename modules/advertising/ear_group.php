<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include( dirname(__FILE__)."/../../configs.inc.php");
include( RootDocument."/".Classes."/global.php");
include (RootDocumentShare."/setindex.php");

//---------------<!-- Ear Start -->-----------------------------------------------

//
//if (intval($INFO['ear_radio'])==1 && intval($INFO['ear_adv_id'])>0){
	$Sql = "select adv_id,adv_left_url,adv_right_url,adv_width,adv_height,adv_content,point_num,adv_left_img,adv_right_img from `{$INFO[DBPrefix]}advertising` where adv_display=1 AND adv_type=12 and (adv_left_img!='' or adv_right_img!='') and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') limit 0,1";
	$Query = $DB->query($Sql);
	$Num = $DB->num_rows($Query);
	if ($Num>0){
		$Result = $DB->fetch_array($Query);
		$adv_id          =  $Result['adv_id'];
		$adv_url_left    =  trim($Result['adv_left_url']);
		$adv_url_right   =  trim($Result['adv_right_url']);
		$adv_width       =  $Result['adv_width'];
		$adv_height      =  $Result['adv_height'];
		$point_num       =  $Result['point_num'];
		$adv_left_img    =  trim($Result['adv_left_img']);
		$adv_right_img   =  trim($Result['adv_right_img']);

		if (trim($adv_left_img)!=""){
			//$Ear_object_left  = $INFO['site_url']."/".$INFO['advs_pic_path']."/".$Rs['adv_left_img'];
			$Ear_object_left  = $FUNCTIONS->ImgTypeReturn("./".$INFO['advs_pic_path'],$adv_left_img,$adv_height,$adv_width);
		}
		if (trim($adv_right_img)!=""){
			// $Ear_object_right = $INFO['site_url']."/".$INFO['advs_pic_path']."/".$Rs['adv_right_img'];
			$Ear_object_right = $FUNCTIONS->ImgTypeReturn("./".$INFO['advs_pic_path'],$adv_right_img,$adv_height,$adv_width);
		}


		/*
		本来是想如果只放上去一边的话，2边都出现一样的。现在屏蔽了这个。就等于只出现指定的了
		if (trim($adv_left_img)!="" && trim($adv_right_img)==""){
		$Ear_object_right = $Ear_object_left;
		}

		if (trim($adv_right_img)!="" && trim($adv_left_img)==""){
		$Ear_object_left = $Ear_object_right;
		}
		*/
		$Ear_object_left  = "<a href='" . $INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$adv_id  . "&url=".urlencode($adv_url_left)."' target='_blank'>".$Ear_object_left."</a>";
		$Ear_object_right = "<a href='" . $INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$adv_id  . "&url=".urlencode($adv_url_right)."' target='_blank'>".$Ear_object_right."</a>";


	
//}
//$tpl->assign("Ear_radio",             intval($INFO['ear_radio']));
$tpl->assign("Ear_object_left",       $Ear_object_left);
$tpl->assign("Ear_object_right",      $Ear_object_right);

unset($Sql);
unset($Num);
unset($Query);

$tpl->display("ear_group.html");
}
//---------------<!-- Ear End -->-----------------------------------------------

?>
