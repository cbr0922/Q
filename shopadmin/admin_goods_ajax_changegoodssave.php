<?php
include_once "Check_Admin.php";
$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');
//print_r($_POST);
if ($_POST['actchange']=='Save'){

	$cid      = $_POST['cid'];
	$price      = $_POST['price'];
	$cid_num  = count($cid);
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select changeid from `{$INFO[DBPrefix]}goods_change` where gid='$Gid' and changeid='$cid[$i]' order by idate desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num<1){
			$Result =  $DB->query(" insert into `{$INFO[DBPrefix]}goods_change` (gid,changegid,price,idate) values('".intval($Gid)."','".intval($cid[$i])."','".intval($_POST['price'.$cid[$i]])."','".time()."')");
		}
	}

	//if ($Result) {
		echo "1";
		exit;
	//}
}
//É¾³ý×ÊÁÏ!
if ($_POST['actchangec']=="Del"){
	$GoodLinkIdArray = $_POST['changeid'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}goods_change` where changeid=".intval($GoodLinkIdArray[$i]));
		}
	}
	echo "1";
	exit;
}

if ($_POST['actchangec']=="update"){
	$cid      = $_POST['changeid'];
	$cid_num  = count($cid);
	$flag = 0;
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select changeid from `{$INFO[DBPrefix]}goods_change` where gid='$Gid' and changeid='$cid[$i]' order by idate desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num==1 && intval($_POST['price' . $cid[$i]])>0 && intval($_POST['price' . $cid[$i]])==$_POST['price' . $cid[$i]]){
			$Result =  $DB->query(" update `{$INFO[DBPrefix]}goods_change` set price='".intval($_POST['price' . $cid[$i]])."' where gid='$Gid' and changeid='$cid[$i]' ");
		}else{
			$flag = 1;	
		}
	}
	if($flag==1)
		echo 2;
	else
		echo "1";
	exit;
}
?>