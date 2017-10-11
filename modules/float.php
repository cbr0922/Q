<?php

error_reporting(7);

@header("Content-type: text/html; charset=utf-8");



include( dirname(__FILE__)."/../../configs.inc.php");

include( RootDocument."/Classes/global.php");







//if ( intval($INFO['float_adv_id'])!=0 && intval($INFO['float_radio'])==1 ) {

	//---------------<!-- Float Start -->-----------------------------------------------



	$Sql = " select adv_id,adv_left_url,adv_right_url,adv_width,adv_height,adv_content,point_num,adv_left_img,adv_right_img from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type=1  and adv_id='".intval($INFO['float_adv_id'])."' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "') order by adv_idate desc limit 0,1 ";

	$Query = $DB->query($Sql);

	$Num   = $DB->num_rows($Query);

	if ( $Num > 0 ){

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

			$float_left  = $FUNCTIONS->ImgTypeReturn($INFO['advs_pic_path'],$adv_left_img,$adv_height,$adv_width);

		}



		if (trim($adv_right_img)!=""){

			$float_right = $FUNCTIONS->ImgTypeReturn($INFO['advs_pic_path'],$adv_right_img,$adv_height,$adv_width);

		}



		/*

		本来是想如果只放上去一边的话，2边都出现一样的。现在屏蔽了这个。就等于只出现指定的了

		if (trim($adv_left_img)!="" && trim($adv_right_img)==""){

		$float_right = $float_left;

		}



		if (trim($adv_right_img)!="" && trim($adv_left_img)==""){

		$float_left = $float_right;

		}

		*/

		$float_left  = "<a href='" . $INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$adv_id  . "&url=".urlencode($adv_url_left)."' target='_blank'>".$float_left."</a>";

		$float_right = "<a href='" . $INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$adv_id  . "&url=".urlencode($adv_url_right)."' target='_blank'>".$float_right."</a>";





		$tpl->assign("float_left",  $float_left);   //float_left

		$tpl->assign("float_right", $float_right);  //float_right



		//$point_num=intval($point_num+1);

		//$Query = $DB->query(" update advertising set point_num=".$point_num." where adv_id=".intval($adv_id));

		unset ($Query);

		unset ($Result);

		unset ($adv_width);

		unset ($adv_height);

	}









	$tpl->display("float.html");



	//---------------<!-- Float End -->-----------------------------------------------

//}

?>