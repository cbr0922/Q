<?php
include_once "Check_Admin.php";
$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');
//print_r($_POST);
if ($_POST['actpresent']=='Save'){

	$cid      = $_POST['cid'];
	$price      = $_POST['price'];
	$cid_num  = count($cid);
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select prid from `{$INFO[DBPrefix]}goods_present` where gid='$Gid' and pregid='$cid[$i]' order by idate desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num<1){
			$Result =  $DB->query(" insert into `{$INFO[DBPrefix]}goods_present` (gid,pregid,idate,count) values('".intval($Gid)."','".intval($cid[$i])."','".time()."',1)");
		}
	}

	//if ($Result) {
		echo "1";
		exit;
	//}
}
//É¾³ý×ÊÁÏ!
if ($_POST['actpresent']=="Del"){
	$GoodLinkIdArray = $_POST['prid'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}goods_present` where prid=".intval($GoodLinkIdArray[$i]));
		}
	}
	echo "1";
	exit;
}
//print_r($_POST);
if ($_POST['actpresent']=="update"){
	$cid      = $_POST['prid'];
	$cid_num  = count($cid);
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select prid from `{$INFO[DBPrefix]}goods_present` where gid='$Gid' and prid='$cid[$i]' order by idate desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num==1){
			$Result =  $DB->query(" update `{$INFO[DBPrefix]}goods_present` set count='".intval($_POST['count' . $cid[$i]])."' where gid='$Gid' and prid='$cid[$i]' ");
		}
	}
	echo "1";
	exit;
}
?>