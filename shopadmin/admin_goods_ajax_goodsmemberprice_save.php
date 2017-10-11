<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


$Gid        = $_POST['gid']; //产品ID
$detail_id        = 0;
$level_id   = $_POST['level_id'];
$CNum       = count($_POST['m_price_id']);
$m_price_id = $_POST['m_price_id']; //这个来判断MEMBER_PRICE表中的唯一ID。
$m_price    = $_POST['m_price'];    
$detail_id    = $_POST['detail_id']; 
$soid    = $_POST['soid']; 

if ($CNum>0){

	for ($i=0;$i<$CNum;$i++){

		switch (intval($m_price_id[$i])){
			case 0:
				$Sql = "insert into `{$INFO[DBPrefix]}member_price` (m_goods_id,m_level_id,m_price,m_detail_id,m_saleoffid) values(".$Gid.",".$level_id[$i].",".intval($m_price[$i]).",".intval($detail_id[$i]).",".intval($soid[$i]).")";
				break;
			default:
				$Sql = "update `{$INFO[DBPrefix]}member_price` set m_goods_id=".$Gid." , m_level_id=".$level_id[$i]." , m_price=".intval($m_price[$i])." where m_price_id=".intval($m_price_id[$i]);
				break;
		}
		$Result = $DB->query($Sql);
	}

}
if ($Result){
	echo "1";
}else{
	echo $Basic_Command['Back_System_Error'];
}
?>