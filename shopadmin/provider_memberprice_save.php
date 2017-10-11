<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


$Gid        = $_POST['gid']; //产品ID
$level_id   = $_POST['level_id'];
$CNum       = count($_POST['m_price_id']);
$m_price_id = $_POST['m_price_id']; //这个来判断MEMBER_PRICE表中的唯一ID。
$m_price    = $_POST['m_price'];    // 这个就是对应MEMBER_PRICE表中的唯一ID的值


if ($CNum>0){

	for ($i=0;$i<$CNum;$i++){

		switch (intval($m_price_id[$i])){
			case 0:
				$Sql = "insert into `{$INFO[DBPrefix]}member_price` (m_goods_id,m_level_id,m_price) values(".$Gid.",".$level_id[$i].",".intval($m_price[$i]).")";
				break;
			default:
				$Sql = "update `{$INFO[DBPrefix]}member_price` set m_goods_id=".$Gid." , m_level_id=".$level_id[$i]." , m_price=".intval($m_price[$i])." where m_price_id=".intval($m_price_id[$i]);
				break;
		}
		$Result = $DB->query($Sql);
	}

}
if ($Result){
	$FUNCTIONS->sorry_back("provider_memberprice.php?gid=".intval($Gid),"");
}else{
	$FUNCTIONS->sorry_back("provider_memberprice.php?gid=".intval($Gid),$Basic_Command['Back_System_Error']);
}
?>