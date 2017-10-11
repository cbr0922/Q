<?php
include_once "Check_Admin.php";
$rgid  = $FUNCTIONS->Value_Manage($_GET['rgid'],$_POST['rgid'],'back','');
if ($_POST['act']=='Save'){

	$cid      = $_POST['cids'];
	$cid_num  = count($cid);
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select * from `{$INFO[DBPrefix]}subject_redgoods` where rgid='$rgid' and gid='$cid[$i]' limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num<1){
			$Sql_g    = "select * from `{$INFO[DBPrefix]}goods` where gid='$cid[$i]' limit 0,1";
			$Query_g  = $DB->query($Sql_g);
			$Rs_g=$DB->fetch_array($Query_g);
			$Result =  $DB->query("insert into `{$INFO[DBPrefix]}subject_redgoods` (rgid,gid,cost) values('".intval($rgid)."','".intval($cid[$i])."','" .$Rs_g['cost']  . "')");
		}
	}

	//if ($Result) {
		echo "1";
		exit;
	//}
}
//删除资料!
if ($_POST['actlink']=="Del"){
	$GoodLinkIdArray = $_POST['dgid'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}subject_redgoods` where red_id=".intval($GoodLinkIdArray[$i]));
		}
	}
	echo "1";
	exit;
}
if ($_POST['actlink']=="Check"){
	$GoodLinkIdArray = $_POST['dgid'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$DelQuery = $DB->query("update `{$INFO[DBPrefix]}subject_redgoods` set ifcheck=1 where red_id=".intval($GoodLinkIdArray[$i]));
		}
	}
	echo "1";
	exit;
}
if ($_POST['actlink']=="Save"){
	$Allid        = $_POST['dgid'];

	if (count($Allid)>0) {                   //如果确实有资料提交!
		for ($i=0;$i<count($Allid);$i++){           //将提交的所有资料的ID做大循环
			$Update_sql = " update `{$INFO[DBPrefix]}subject_redgoods` set cost='".intval($_POST['zk_cost' . intval($Allid[$i])])."' where red_id='".intval($Allid[$i])."' ";
			$DB->query($Update_sql);  //首先将本条记录更新
		}
	}
	echo "1";
	exit;
}
?>