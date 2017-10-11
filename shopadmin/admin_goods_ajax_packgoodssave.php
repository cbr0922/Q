<?php
include_once "Check_Admin.php";
$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');
//print_r($_POST);
if ($_POST['actpack']=='Save'){

	$cid      = $_POST['cid'];
	$cost      = $_POST['cost'];
	$cid_num  = count($cid);
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select packid from `{$INFO[DBPrefix]}goods_pack` where gid='$Gid' and packid='$cid[$i]' order by idate desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num<1){
			$Result =  $DB->query(" insert into `{$INFO[DBPrefix]}goods_pack` (gid,packgid,cost,idate,count) values('".intval($Gid)."','".intval($cid[$i])."','".intval($cost[$i])."','".time()."',1)");
		}
	}

	//if ($Result) {
		echo "1";
		exit;
	//}
}
//É¾³ý×ÊÁÏ!
if ($_POST['actpackc']=="Del"){
	$GoodLinkIdArray = $_POST['packid'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
		//	echo intval($GoodLinkIdArray[$i])."|";
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}goods_pack` where packid=".intval($GoodLinkIdArray[$i]));
		}
	}
	echo "1";
	exit;
}

if ($_POST['actpackc']=="update"){
	$cid      = $_POST['packid'];
	$cid_num  = count($cid);
	for ($i=0;$i<$cid_num;$i++){
		 $Sql    = "select packgid from `{$INFO[DBPrefix]}goods_pack` where gid='$Gid' and packid='$cid[$i]' order by idate desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num==1){
			
			$Result =  $DB->query(" update `{$INFO[DBPrefix]}goods_pack` set cost='".intval($_POST['cost' . $cid[$i]])."',count='".intval($_POST['count' . $cid[$i]])."' where gid='$Gid' and packid='$cid[$i]' ");
		}
	}
	echo "1";
	exit;
}
?>