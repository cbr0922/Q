<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

$postArray = ( $_POST['FCKeditor1']);
if (is_array($postArray))
{
	foreach ( $postArray as $sForm => $value )
	{
		$postedValue =( $value);
	}
}
$postedValue = $postedValue!="" ? $postedValue : $postArray ;
if (trim($_POST['saleoff_startdate'])!=''){
	$date_array = explode("-",trim($_POST['saleoff_startdate']));
	$saleoff_startdate = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['start_h']),intval($_POST['start_i']),0);
}
if (trim($_POST['saleoff_enddate'])!=''){
	$date_array = explode("-",trim($_POST['saleoff_enddate']));
	$saleoff_enddate = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['end_h']),intval($_POST['end_i']),0);
}
if ($_POST['Action']=='Insert' ) {
	$arr_select =  array();
	$img_menu   = $FUNCTIONS->Upload_File($_FILES['img_menu']['name'],$_FILES['img_menu']['tmp_name'],'',"../" . $INFO['good_pic_path']);
	$img_menu_m   = $FUNCTIONS->Upload_File($_FILES['img_menu_m']['name'],$_FILES['img_menu_m']['tmp_name'],'',"../" . $INFO['good_pic_path']);
	$img_banner   = $FUNCTIONS->Upload_File($_FILES['img_banner']['name'],$_FILES['img_banner']['tmp_name'],'',"../" . $INFO['good_pic_path']);
	$img_banner2   = $FUNCTIONS->Upload_File($_FILES['img_banner2']['name'],$_FILES['img_banner2']['tmp_name'],'',"../" . $INFO['good_pic_path']);
	for ($i=1;$i<=$INFO['b_attr'];$i++ ){
		if ($_POST["attr".$i]!='') {
			$arr_select[$i]=$_POST["attr".$i];
		}
	}
	$arr_char = implode(",",$arr_select);
	if(is_array($_POST['brandlist']))
		$brandlist = implode(",",$_POST['brandlist']);
	else
		$brandlist = $_POST['brandlist'];

		$top_bid = explode(" ",intval($_POST['top_id'])." ".$FUNCTIONS->father_class_topid(intval($_POST['top_id'])));
		array_pop($top_bid);
		$top_bid = array_unique($top_bid);
		$bid_str = "b.bid in ('".implode("','",$top_bid)."')";

		$Query_b   = $DB->query("select * from `{$INFO[DBPrefix]}bclass` b where ".$bid_str." and b.catiffb='0'");
		$Num_b   = $DB->num_rows($Query_b);
		if ( $Num_b > 0 ) {
			$catiffb = 0;
		}else {
			$catiffb = intval($_POST['catiffb']);
		}

	$db_string = $DB->compile_db_insert_string( array (
	'top_id'           => intval($_POST['top_id']),
	'catname'          => trim($_POST['catname']),
	'catord'           => intval($_POST['catord']),
	'catiffb'          => $catiffb,
	'catiffb2'          => $catiffb,
	'catmenucolor'     => trim($_POST['catmenucolor']),
	'gain'			   => intval($_POST['gain']),
	'catcontent'       =>  $postedValue,
	'pic1'       =>  $img_menu,
	'pic2'       =>  $img_menu_m,
	'banner'       =>  $img_banner,
	'banner2'       =>  $img_banner2,
	'meta_des'     => trim($_POST['meta_des']),
	'meta_key'     => trim($_POST['meta_key']),
	'attr'             => trim($arr_char),
	'ifhome'			   => intval($_POST['ifhome']),
	'nclass'			   => intval($_POST['nclass']),
	'link'     => trim($_POST['link']),
	'rebate'			   => intval($_POST['rebate']),
	'costrebate'			   => intval($_POST['costrebate']),
	'manyunfei'			   => intval($_POST['manyunfei']),
	'subject_id'			   => intval($_POST['subject_id']),
	'subject_id2'			   => intval($_POST['subject_id2']),
	'type'			   => intval($_POST['type']),
	'saleoff_starttime'      => $saleoff_startdate,
	'saleoff_endtime'      => $saleoff_enddate,
	'language'        => $_POST['language'],
	'brandlist'               => trim($brandlist),
	'url'               => trim($_POST['url']),
	)      );
	$Sql="INSERT INTO `{$INFO[DBPrefix]}bclass` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	$classid = mysql_insert_id();

	/**

	類別屬性

	**/

	if (is_array($_POST['attribute'])){
		foreach($_POST['attribute'] as $v => $k){
			$sql = "insert into `{$INFO[DBPrefix]}attributeclass` (attrid,cid) values ('" . intval($k) . "','" . $classid . "')";
			$DB->query($sql);
		}
	}

	/**

	會員級別

	**/

	if (is_array($_POST['userlevel'])){
		foreach($_POST['userlevel'] as $v => $k){
			$sql = "insert into `{$INFO[DBPrefix]}bclass_userlevel` (levelid,bid) values ('" . intval($k) . "','" . $classid . "')";
			$DB->query($sql);
		}
	}

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增商品類別");
		$FUNCTIONS->header_location('admin_create_productclassshow.php?top_id=' . $_POST['top_id']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}

if ($_POST['Action']=='Update' ) {
	if ($_FILES['img_menu']['name']!=""){
	 	$img_menu   = $FUNCTIONS->Upload_File($_FILES['img_menu']['name'],$_FILES['img_menu']['tmp_name'],$_POST['old_img_menu'],"../" . $INFO['good_pic_path']);
	}else{
		$img_menu   = $_POST['old_img_menu'];
	}
	if ($_FILES['img_menu_m']['name']!=""){
		$img_menu_m   = $FUNCTIONS->Upload_File($_FILES['img_menu_m']['name'],$_FILES['img_menu_m']['tmp_name'],$_POST['old_img_menu_m'],"../" . $INFO['good_pic_path']);
	}else{
		$img_menu_m   = $_POST['old_img_menu_m'];
	}
	if ($_FILES['img_banner']['name']!=""){
		$img_banner   = $FUNCTIONS->Upload_File($_FILES['img_banner']['name'],$_FILES['img_banner']['tmp_name'],$_POST['old_img_banner'],"../" . $INFO['good_pic_path']);
	}else{
		$img_banner   = $_POST['old_img_banner'];
	}
	if ($_FILES['img_banner2']['name']!=""){
		$img_banner2   = $FUNCTIONS->Upload_File($_FILES['img_banner2']['name'],$_FILES['img_banner2']['tmp_name'],$_POST['old_img_banner2'],"../" . $INFO['good_pic_path']);
	}else{
		$img_banner2   = $_POST['old_img_banner2'];
	}
	$arr_select =  array();
	for ($i=1;$i<=$INFO['b_attr'];$i++ ){
		if ($_POST["attr".$i]!='') {
			$arr_select[$i]=$_POST["attr".$i];
		}
	}
	$arr_char = implode(",",$arr_select);
	if(is_array($_POST['brandlist']))
		$brandlist = implode(",",$_POST['brandlist']);
	else
		$brandlist = $_POST['brandlist'];
/*
	$top_bid = explode(" ",intval($_POST['top_id'])." ".$FUNCTIONS->father_class_topid(intval($_POST['top_id'])));
	array_pop($top_bid);
	$top_bid = array_unique($top_bid);
	$bid_str = "b.bid in ('".implode("','",$top_bid)."')";
	$Query_b   = $DB->query("select * from `{$INFO[DBPrefix]}bclass` b where ".$bid_str." and b.catiffb='0'");
	$Num_b   = $DB->num_rows($Query_b);
	if ( $Num_b > 0 ) {
		$catiffb = 0;
	}else {
		$catiffb = intval($_POST['catiffb']);
		$DB->query("UPDATE `{$INFO[DBPrefix]}bclass` SET `catiffb`=".$catiffb.", `catiffb2`=".$catiffb." WHERE bid=".intval($_POST['bid']));
	}

	if($catiffb==1){
		$FUNCTIONS->pcon_class(intval($_POST['bid']));
	}else {
		$sub_bid = explode(",",$FUNCTIONS->Sun_pcon_class(intval($_POST['bid'])));
		array_pop($sub_bid);
		$sub_bid = array_unique($sub_bid);
		$sub_bid_str = " b.bid in ('".implode("','",$sub_bid)."')";

		$Query_b2   = $DB->query("select * from `{$INFO[DBPrefix]}bclass` b where ".$sub_bid_str);
		while ( $ClassRow = $DB->fetch_array($Query_b2)){
			$DB->query("UPDATE `{$INFO[DBPrefix]}bclass` SET `catiffb`=0 WHERE bid=".intval($ClassRow['bid']));
		}
	}
*/
	$db_string = $DB->compile_db_update_string( array (
	'top_id'           => intval($_POST['top_id']),
	'catname'          => trim($_POST['catname']),
	'catord'           => intval($_POST['catord']),
	'catmenucolor'     => trim($_POST['catmenucolor']),
	'catiffb'          => $catiffb,
	'catcontent'       =>  $postedValue,
	'pic1'       =>  $img_menu,
	'pic2'       =>  $img_menu_m,
	'banner'       =>  $img_banner,
	'banner2'       =>  $img_banner2,
	'gain'			   => intval(trim($_POST['gain'])),
	'attr'             => trim($arr_char),
	'meta_des'     => trim($_POST['meta_des']),
	'meta_key'     => trim($_POST['meta_key']),
	'ifhome'			   => intval($_POST['ifhome']),
	'nclass'			   => intval($_POST['nclass']),
	'link'     => trim($_POST['link']),
	'rebate'			   => intval($_POST['rebate']),
	'costrebate'			   => intval($_POST['costrebate']),
	'manyunfei'			   => intval($_POST['manyunfei']),
	'subject_id'			   => intval($_POST['subject_id']),
	'subject_id2'			   => intval($_POST['subject_id2']),
	'type'			   => intval($_POST['type']),
	'saleoff_starttime'      => $saleoff_startdate,
	'saleoff_endtime'      => $saleoff_enddate,
	'language'        => $_POST['language'],
	'brandlist'               => trim($brandlist),
	'url'               => trim($_POST['url']),
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}bclass` SET $db_string WHERE bid=".intval($_POST['bid']);
	$Result_Insert = $DB->query($Sql);

	/**

	類別屬性

	**/

	$attr_sql = "select * from `{$INFO[DBPrefix]}attributeclass` where cid='" . intval($_POST['bid']) . "' order by attrid desc";
	$Query_attr    = $DB->query($attr_sql);
	$ic=0;
	while($Rs_arrt=$DB->fetch_array($Query_attr)){
			$attr_class[$ic]=$Rs_arrt['attrid'];
			$ic++;
	}
	if (is_array($attr_class)){
		foreach($attr_class as $v=>$k){
			if (is_array($_POST['attribute'])){
				if (!in_array($k,$_POST['attribute'])){
					$sql = "delete from `{$INFO[DBPrefix]}attributeclass` where attrid='" . intval($k) . "' and cid='" . intval($_POST['bid']) . "'";
					$DB->query($sql);
				}
			}else{
				$sql = "delete from `{$INFO[DBPrefix]}attributeclass` where cid='" . intval($_POST['bid']) . "'";
				$DB->query($sql);
			}
		}
	}

	if (is_array($_POST['attribute'])){
		foreach($_POST['attribute'] as $v => $k){

			$attr_sql = "select * from `{$INFO[DBPrefix]}attributeclass` where attrid='" . intval($k) . "' and cid='" . intval($_POST['bid']) . "' order by attrid desc";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr      = $DB->num_rows($Query_attr);
			if ($Num_attr<=0){
				$sql = "insert into `{$INFO[DBPrefix]}attributeclass` (attrid,cid) values ('" . intval($k) . "','" . intval($_POST['bid']) . "')";
				$DB->query($sql);
			}
		}
	}else{
				$sql = "delete from `{$INFO[DBPrefix]}attributeclass` where cid='" . intval($_POST['bid']) . "'";
					$DB->query($sql);
			}

	/**

	會員級別

	**/
	$level_goods = array();
	$attr_sql = "select * from `{$INFO[DBPrefix]}bclass_userlevel` where bid='" . intval($_POST['bid']) . "'";
	$Query_attr    = $DB->query($attr_sql);
	$ic=0;
	while($Rs_arrt=$DB->fetch_array($Query_attr)){
			$level_goods[$ic]=$Rs_arrt['levelid'];
			$ic++;
	}
	if (is_array($level_goods)){
		foreach($level_goods as $v=>$k){
			if (is_array($_POST['userlevel'])){
				if (!in_array($k,$_POST['userlevel'])){
					$sql = "delete from `{$INFO[DBPrefix]}bclass_userlevel` where levelid='" . intval($k) . "' and bid='" . intval($_POST['bid']) . "'";
					$DB->query($sql);
				}
			}
		}
	}

	if (is_array($_POST['userlevel'])){
		foreach($_POST['userlevel'] as $v => $k){

			$attr_sql = "select * from `{$INFO[DBPrefix]}bclass_userlevel` where levelid='" . intval($k) . "' and bid='" . intval($_POST['bid']) . "'";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr      = $DB->num_rows($Query_attr);
			if ($Num_attr<=0){
				$sql = "insert into `{$INFO[DBPrefix]}bclass_userlevel` (levelid,bid) values ('" . intval($k) . "','" . intval($_POST['bid']) . "')";
				$DB->query($sql);
			}
		}
	}else{
				$sql = "delete from `{$INFO[DBPrefix]}bclass_userlevel` where bid='" . intval($_POST['bid']) . "'";
					$DB->query($sql);
	}

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯商品類別");
		$FUNCTIONS->header_location('admin_create_productclassshow.php?top_id=' . $_POST['top_id']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}
//print_r($_POST);
if ($_POST['act']=='Del' ) {
	$Array_bid =  $_POST['bid'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}bclass` where bid=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除商品類別");
		$FUNCTIONS->header_location('admin_create_productclassshow.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}
if ($_GET['Action']=='delPic' && intval($_GET['id'])>0  && $_GET['pic']!='' ) {
	$Sql = "select " . $_GET['type'] . " from  `{$INFO[DBPrefix]}bclass`  where bid=".intval($_GET['id'])." limit 0,1";
	$Query =  $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Rs  = $DB->fetch_array($Query);
		@unlink("../".$INFO['good_pic_path']."/".$_GET['pic']);
	}
	$DB->query("update `{$INFO[DBPrefix]}bclass` set  " . $_GET['type'] . "='' where bid=".intval($_GET['id'])." limit 1");
	$FUNCTIONS->setLog("刪除商品分類圖片");
	$FUNCTIONS->header_location('admin_pcat.php?bid=' . $_GET['id'] . '&Action=Modi' );
}
if($_POST['act']=="sort"){
	$list = $_POST['list'];
	$list_array = explode(",",$list);
	$i = 0;
	foreach($list_array as $k=>$v){
		echo $Sql = "update `{$INFO[DBPrefix]}bclass` set catord='" .$i. "' where bid='" . $v . "'";
		$Query =  $DB->query($Sql);
		$i++;
	}
}
//autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($_GET['Bid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}bclass` SET catcontent= '" . $_POST['FCKeditor1'] . "' WHERE bid=".intval($_GET['Bid']);
		$Result = $DB->query($Sql);
		$array = array(
			'error' => false,
			'message' => '已自動保存'
		);
	}else{
		$array = array(
			'error' => true,
			'message' => '自動保存失敗'
		);
	}

    echo stripslashes(json_encode($array));

}
?>
