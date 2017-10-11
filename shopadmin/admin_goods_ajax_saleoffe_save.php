<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";




if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'gid'          => intval($_POST['goods_id']),
	'mincount'          => intval($_POST['mincount']),
	'maxcount'          => intval($_POST['maxcount']),
	'price'          => intval($_POST['price']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}goods_saleoffe` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	
	$detail_id = mysql_insert_id();
	
	/**
	
	會員價格
	
	**/
	
	$Sql      = "select * from `{$INFO[DBPrefix]}user_level`";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$dSql = "delete from `{$INFO[DBPrefix]}member_price` where m_goods_id='" . intval($_POST['goods_id']) . "' and m_saleoffid='" . $detail_id . "'";	
	$DB->query($dSql);
	if($Num>0){
		while ($Rs=$DB->fetch_array($Query)) {
			//if ($Rs['pricerate']>0){
				
				$Sql = "insert into `{$INFO[DBPrefix]}member_price` (m_goods_id,m_level_id,m_price,m_saleoffid) values(".intval($_POST['goods_id']).",".$Rs['level_id'].",".intval($_POST['price'.$Rs['level_id']]).",'" . $detail_id . "')";
				$Result = $DB->query($Sql);
			//}
		}
	}
	


	if ($Result_Insert)
	{
		echo "1";
	}else{
		echo $Basic_Command['Back_System_Error'];
	}

}




if ($_POST['Action']=='Update' ) {

	$db_string = $DB->compile_db_update_string( array (
	'mincount'          => intval($_POST['mincount']),
	'maxcount'          => intval($_POST['maxcount']),
	'price'          => intval($_POST['price']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}goods_saleoffe` SET $db_string WHERE soid=".intval($_POST['soid']);

	$Result_Insert = $DB->query($Sql);
	
	/**
	
	會員價格
	
	**/
	$Sql      = "select * from `{$INFO[DBPrefix]}user_level`";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$dSql = "delete from `{$INFO[DBPrefix]}member_price` where m_goods_id='" . intval($_POST['goods_id']) . "' and m_saleoffid='" . intval($_POST['soid']). "'";	
	$DB->query($dSql);
	if($Num>0){
		while ($Rs=$DB->fetch_array($Query)) {
			//if ($Rs['pricerate']>0){
				
				$Sql = "insert into `{$INFO[DBPrefix]}member_price` (m_goods_id,m_level_id,m_price,m_saleoffid) values(".intval($_POST['goods_id']).",".$Rs['level_id'].",".intval($_POST['price'.$Rs['level_id']]).",'" . intval($_POST['soid']) . "')";
				$Result = $DB->query($Sql);
			//}
		}
	}

	if ($Result_Insert)
	{
		echo "1";
	}else{
		echo $Basic_Command['Back_System_Error'];
	}

}


//print_r($_POST);
if ($_POST['acts']=='Del' ) {

	$Array_bid =  $_POST['soid'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_saleoffe` where soid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}member_price` where m_saleoffid=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		echo "1";
	}else{
		echo $Basic_Command['Back_System_Error'];
	}
}



?>