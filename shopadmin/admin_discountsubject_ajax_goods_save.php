<?php
include_once "Check_Admin.php";
$dsid  = $FUNCTIONS->Value_Manage($_GET['dsid'],$_POST['dsid'],'back','');
if ($_POST['act']=='Save'){

	$cid      = $_POST['cids'];
	$cid_num  = count($cid);
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select * from `{$INFO[DBPrefix]}discountgoods` where dsid='$dsid' and gid='$cid[$i]' limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num<1){
			$Sql_g    = "select * from `{$INFO[DBPrefix]}goods` where gid='$cid[$i]' limit 0,1";
			$Query_g  = $DB->query($Sql_g);
			$Rs_g=$DB->fetch_array($Query_g);
			$Result =  $DB->query("insert into `{$INFO[DBPrefix]}discountgoods` (dsid,gid,price,cost) values('".intval($dsid)."','".intval($cid[$i])."','" .$Rs_g['pricedesc']  . "','" .$Rs_g['cost']  . "')");
		}
	}

	//if ($Result) {
		echo "1";
		exit;
	//}
}
//ɾ������!
if ($_POST['actlink']=="Del"){
	$GoodLinkIdArray = $_POST['dgid'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}discountgoods` where dgid=".intval($GoodLinkIdArray[$i]));
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
			$DelQuery = $DB->query("update `{$INFO[DBPrefix]}discountgoods` set ifcheck=1 where dgid=".intval($GoodLinkIdArray[$i]));
		}
	}
	echo "1";
	exit;
}
if ($_POST['actlink']=="Save"){
	$Allid        = $_POST['dgid'];

	if (count($Allid)>0) {                   //���ȷʵ�������ύ!
		for ($i=0;$i<count($Allid);$i++){           //���ύ���������ϵ�ID����ѭ��
			$Update_sql = " update `{$INFO[DBPrefix]}discountgoods` set price='".intval($_POST['zk_price' . intval($Allid[$i])])."',cost='".intval($_POST['zk_cost' . intval($Allid[$i])])."' where dgid='".intval($Allid[$i])."' ";
			$DB->query($Update_sql);  //���Ƚ�������¼����
		}
	}
	echo "1";
	exit;
}
?>