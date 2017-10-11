<?php
@ob_start();
include_once "Check_Admin.php";
set_time_limit(0);
//include Classes . "/global.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
$dsid  = $FUNCTIONS->Value_Manage($_GET['dsid'],$_POST['dsid'],'back','');
$i=0;
$handle = fopen ($_FILES['cvsEXCEL']['tmp_name'],"r");
while ($datastr = fgets ($handle, 10240)) {
	 $datastr = ($datastr);
	$data = explode(",",$datastr);
	if ($i>0){
		$sql = "select * from  `{$INFO[DBPrefix]}goods` where bn = '" . trim($data[0]) . "'";
		$Query_goods    = $DB->query($sql);
		$Num_goods      = $DB->num_rows($Query_goods);
		if($Num_goods>0){
			$Rs = $DB->fetch_array($Query_goods);
			$gid = 	$Rs['gid'];
			 $sql = "select * from `{$INFO[DBPrefix]}discountgoods` where gid = '" . $gid . "' and dsid='" . $dsid . "'";
			$Query_goods    = $DB->query($sql);
			$Num_trans      = $DB->num_rows($Query_goods);
			if ($Num_trans <= 0){
				$Result =  $DB->query("insert into `{$INFO[DBPrefix]}discountgoods` (dsid,gid,price,cost,ifcheck) values('".intval($dsid)."','".intval($gid)."','" .$Rs['pricedesc']  . "','" .$data[1]  . "','" . intval($data[2]) . "')");
			}
		}
	}
	$i++;
}
echo 1;exit;
?>