<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");


$Gid        = $_POST['gid']; //产品ID
$month   = $_POST['month'];
$CNum       = count($_POST['m_price_id']);
$m_price_id = $_POST['m_price_id']; //这个来判断MEMBER_PRICE表中的唯一ID。
$month_price    = $_POST['month_price'];    // 这个就是对应MEMBER_PRICE表中的唯一ID的值


if ($CNum>0){

	for ($i=0;$i<$CNum;$i++){

		switch (intval($m_price_id[$i])){
			case 0:
				$Sql = "insert into `{$INFO[DBPrefix]}month_price` (goods_id,month,month_price) values(".$Gid.",".$month[$i].",".intval($month_price[$i]).")";
				break;
			default:
				$Sql = "update `{$INFO[DBPrefix]}month_price` set goods_id=".$Gid." , month=".$month[$i]." , month_price=".intval($month_price[$i])." where m_price_id=".intval($m_price_id[$i]);
				break;
		}
		$Result = $DB->query($Sql);
	}

}
if ($Result){
	$FUNCTIONS->sorry_back("admin_goods.php?Action=Modi&gid=".intval($Gid),"");
}else{
	$FUNCTIONS->sorry_back("admin_monthprice.php?gid=".intval($Gid),$Basic_Command['Back_System_Error']);
}
?>