<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";




if ($_POST['Action']=='Insert' ) {
	if (trim($_POST['detail_name']) == ""){
		echo "請填寫商品名稱";
		exit;
	}

	/*$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where bn='" . $_POST['detail_bn'] . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		echo "0";
		exit;
	}

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}attributeno` where goodsno='" . $_POST['detail_bn'] . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		echo "0";
		exit;
	}*/

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods_detail` where detail_bn='" . $_POST['detail_bn'] . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		echo "0";
		exit;
	}

	$img   = $FUNCTIONS->Upload_File($_FILES['img']['name'],$_FILES['img']['tmp_name'],'',"../" . $INFO['good_pic_path']);
	$db_string = $DB->compile_db_insert_string( array (
	'gid'          => intval($_POST['goods_id']),
	'detail_name'          => trim($_POST['detail_name']),
	'detail_bn'          => trim($_POST['detail_bn']),
	'detail_price'          => intval($_POST['detail_price']),
	'detail_pricedes'          => intval($_POST['detail_pricedes']),
	'detail_cost'          => intval($_POST['detail_cost']),
	'detail_des'          => trim($_POST['detail_des']),
	'guojima'          => trim($_POST['guojima']),
	'orgno'          => trim($_POST['orgno']),
	'detail_pic'          => $img,
	//'storage'          => intval($_POST['storage']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}goods_detail` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	$detail_id = mysql_insert_id();
	$FUNCTIONS->setStorage(intval($_POST['storage']),0,intval($_POST['goods_id']),$detail_id,"","","",0);

	/**

	會員價格

	**/
	/**
	$Sql      = "select * from `{$INFO[DBPrefix]}user_level`";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$dSql = "delete from `{$INFO[DBPrefix]}member_price` where m_goods_id='" . intval($_POST['goods_id']) . "' and m_detail_id='" . $detail_id . "'";
	$DB->query($dSql);
	if($Num>0){
		while ($Rs=$DB->fetch_array($Query)) {
			if ($Rs['pricerate']>0){

				$Sql = "insert into `{$INFO[DBPrefix]}member_price` (m_goods_id,m_level_id,m_price,m_detail_id) values(".intval($_POST['goods_id']).",".$Rs['level_id'].",".intval($Rs['pricerate']*0.01*intval($_POST['detail_pricedes'])).",'" . $detail_id . "')";
				$Result = $DB->query($Sql);
			}
		}
	}
	**/


	if ($Result_Insert)
	{
		echo "1";
	}else{
		echo $Basic_Command['Back_System_Error'];
	}

}




if ($_POST['Action']=='Update' ) {
	/*$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where bn='" . $_POST['detail_bn'] . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		echo "0";
		exit;
	}

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}attributeno` where goodsno='" . $_POST['detail_bn'] . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		echo "0";
		exit;
	}*/

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods_detail` where detail_id!='".intval($_POST['detail_id'])."' and detail_bn='" . trim($_POST['detail_bn']) . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		echo "0";
		exit;
	}

	$img   = $FUNCTIONS->Upload_File($_FILES['img']['name'],$_FILES['img']['tmp_name'],$_POST['old_pic'],"../" . $INFO['good_pic_path']);
	$db_string = $DB->compile_db_update_string( array (
	'gid'          => intval($_POST['goods_id']),
	'detail_name'          => trim($_POST['detail_name']),
	'detail_bn'          => trim($_POST['detail_bn']),
	'detail_price'          => intval($_POST['detail_price']),
	'detail_pricedes'          => intval($_POST['detail_pricedes']),
	'detail_cost'          => intval($_POST['detail_cost']),
	'detail_des'          => trim($_POST['detail_des']),
	'detail_pic'          => $img,
	'guojima'          => trim($_POST['guojima']),
	'orgno'          => trim($_POST['orgno']),
	//'storage'          => intval($_POST['storage']),
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}goods_detail` SET $db_string WHERE detail_id=".intval($_POST['detail_id']);

	$Result_Insert = $DB->query($Sql);

	/**

	會員價格

	**/
	/**
	$Sql      = "select * from `{$INFO[DBPrefix]}user_level`";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if($Num>0){
		while ($Rs=$DB->fetch_array($Query)) {
			$Sql = "update `{$INFO[DBPrefix]}member_price` set m_price='" . intval($Rs['pricerate']*0.01*intval($_POST['detail_pricedes'])) . "' where  m_goods_id=".intval($_POST['goods_id'])." and m_level_id=".$Rs['level_id']." and m_detail_id='" . intval($_POST['detail_id']) . "'";
			$Result = $DB->query($Sql);
		}
	}
	**/

	if ($Result_Insert)
	{
		echo "1";
	}else{
		echo $Basic_Command['Back_System_Error'];
	}

}


//print_r($_POST);
if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['detail_id'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		//echo $Array_bid[$i];
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_detail` where detail_id=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		echo "1";
	}else{
		echo $Basic_Command['Back_System_Error'];
	}
}

if ($_GET['Action']=='DelPic' && isset($_GET['detail_id'])) {

	$detail_id  = intval($_GET['detail_id']);

	if ( $detail_id >0 ) {
		$Sql =   " select detail_pic from `{$INFO[DBPrefix]}goods_detail`  where detail_id='".$detail_id."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);
		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Img    =  $Result[detail_pic];
			$DB->query("update `{$INFO[DBPrefix]}goods_detail` set  detail_pic='' where detail_id='".$detail_id."'");
			@unlink ("../".$INFO['good_pic_path'] . "/" . $Img);
		}

	}

}


?>
