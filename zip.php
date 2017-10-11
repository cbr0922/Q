<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include (dirname(__FILE__)."/configs.inc.php");
include ("global.php");
if ($_POST['country']!=""){
	$countryResult = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id=0 and  areaname='" . $_POST['country'] . "'");
	$countrynum_row = $DB->num_rows($countryResult);
	if ($countrynum_row > 0){
		$countryRow =  $DB->fetch_array($countryResult);
		$zip = $countryRow['zip'];
		$countryid = $countryRow['area_id'];
		if ($_POST['province']!=""){
			$provinceResult = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id='" . $countryid . "' and  areaname='" . $_POST['province'] . "'");
			$provincenum_row = $DB->num_rows($provinceResult);	
			if ($provincenum_row > 0){
				$provinceRow =  $DB->fetch_array($provinceResult);
				$zip = $provinceRow['zip'];
				$provinceid = $provinceRow['area_id'];
				if ($_POST['city']!=""){
					$cityResult = $DB->query("select * from `{$INFO[DBPrefix]}area` where top_id='" . $provinceid . "' and  areaname='" . $_POST['city'] . "'");
					$citynum_row = $DB->num_rows($cityResult);	
					if ($citynum_row > 0){
						$cityRow =  $DB->fetch_array($cityResult);
						$zip = $cityRow['zip'];
					}
				}
			}
		}
	}
}
echo $zip;
?>