<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


$Gid        = $_POST['gid']; //产品ID

if ($_POST['action'] == 'add'){
    $Sql      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($Gid) . " and color='".$_POST['color']."' and size='".$_POST['size']."'";
	$Query    = $DB->query($Sql);
	$Nums      = $DB->num_rows($Query);
	if ($Nums >0){
		$Sql = "update `{$INFO[DBPrefix]}storage` set storage='".intval($_POST['storage'])."' where color='".$_POST['color']."' and size='".$_POST['size']."' and goods_id=".$Gid." ";
	}else{
		$Sql = "insert into `{$INFO[DBPrefix]}storage` (color,size,goods_id,storage) values('".$_POST['color']."','".$_POST['size']."',".$Gid."," . intval($_POST['storage']) . ")";
	}
	$Result = $DB->query($Sql);
	$FUNCTIONS->sorry_back("admin_goods_attribstorage.php?Action=Modi&gid=".intval($Gid),"");
	exit;
}


$CNum       = count($_POST['storage_id']);
$storage_id = $_POST['storage_id']; //这个来判断MEMBER_PRICE表中的唯一ID。
$storage    = $_POST['storage'];    // 这个就是对应MEMBER_PRICE表中的唯一ID的值


if ($CNum>0){

	for ($i=0;$i<$CNum;$i++){
	
		$Sql = "update `{$INFO[DBPrefix]}storage` set storage=".intval($storage[$i])." where storage_id=".intval($storage_id[$i]) . " and goods_id=".$Gid." ";
		$Result = $DB->query($Sql);
	}

}
if ($Result){
	$FUNCTIONS->sorry_back("admin_goods_attribstorage.php?Action=Modi&gid=".intval($Gid),"");
}else{
	$FUNCTIONS->sorry_back("admin_goods_attribstorage.php?gid=".intval($Gid),$Basic_Command['Back_System_Error']);
}
?>