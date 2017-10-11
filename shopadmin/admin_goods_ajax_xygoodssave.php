<?php
include_once "Check_Admin.php";
$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');
if ($_POST['act']=='Save'){

	$cid      = $_POST['cid'];
	$cid_num  = count($cid);
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select xyid from `{$INFO[DBPrefix]}goods_xy` where gid='$Gid' and xygid='$cid[$i]' order by idate desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num<1){
			$Result =  $DB->query(" insert into `{$INFO[DBPrefix]}goods_xy` (gid,xygid,idate) values('".intval($Gid)."','".intval($cid[$i])."','".time()."')");
		}
	}

	//if ($Result) {
		echo "1";
		exit;
	//}
}
//É¾³ý×ÊÁÏ!
if ($_POST['act']=="Del"){
	$GoodLinkIdArray = $_POST['xyid'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}goods_xy` where xyid=".intval($GoodLinkIdArray[$i]));
		}
	}
	echo "1";
	exit;
}
?>