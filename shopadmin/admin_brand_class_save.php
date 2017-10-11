<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
//print_r($_POST);exit;
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
	
	$db_string = $DB->compile_db_insert_string( array (
	'top_id'           => intval($_POST['top_id']),
	'catname'          => trim($_POST['catname']),
	'catord'           => intval($_POST['catord']),
	'catiffb'          => intval($_POST['catiffb']),
	'catmenucolor'     => trim($_POST['catmenucolor']),
	'catcontent'       =>  $postedValue,
	'pic1'       =>  $img_menu,
	'pic2'       =>  $img_menu_m,
	'banner'       =>  $img_banner,
	'banner2'       =>  $img_banner2,
	'meta_des'     => trim($_POST['meta_des']),
	'meta_key'     => trim($_POST['meta_key']),
	'attr'             => trim($arr_char),
	'ifhome'			   => intval($_POST['ifhome']),
	'link'     => trim($_POST['link']),


	'type'			   => intval($_POST['type']),

	'language'        => $_POST['language'],

	'url'               => trim($_POST['url']),
	'catname_en'               => trim($_POST['catname_en']),	
	'brand_id'			   => intval($_POST['brand_id']),
	)      );
	$Sql="INSERT INTO `{$INFO[DBPrefix]}brand_class` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	
	$classid = mysql_insert_id();
	
	/**
	
	類別屬性
	
	**/
	
	if (is_array($_POST['attribute'])){
		foreach($_POST['attribute'] as $v => $k){
			$sql = "insert into `{$INFO[DBPrefix]}brand_attributeclass` (attrid,cid) values ('" . intval($k) . "','" . $classid . "')";
			$DB->query($sql);
		}
	}

	
	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增商品品牌下屬類別");
		$FUNCTIONS->header_location('admin_brand_class_list.php?brand_id=' . $_POST['brand_id'].'&top_id='.$_POST['top_id']);
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
	$db_string = $DB->compile_db_update_string( array (
	'top_id'           => intval($_POST['top_id']),
	'catname'          => trim($_POST['catname']),
	'catord'           => intval($_POST['catord']),
	'catmenucolor'     => trim($_POST['catmenucolor']),
	'catiffb'          => intval($_POST['catiffb']),
	'catcontent'       =>  $postedValue,
	'pic1'       =>  $img_menu,
	'pic2'       =>  $img_menu_m,
	'banner'       =>  $img_banner,
	'banner2'       =>  $img_banner2,
	
	'attr'             => trim($arr_char),
	'meta_des'     => trim($_POST['meta_des']),
	'meta_key'     => trim($_POST['meta_key']),
	'ifhome'			   => intval($_POST['ifhome']),
	
	'link'     => trim($_POST['link']),
	
	'type'			   => intval($_POST['type']),

	'language'        => $_POST['language'],
	'catname_en'               => trim($_POST['catname_en']),	
	'url'               => trim($_POST['url']),
	)      );
	$Sql = "UPDATE `{$INFO[DBPrefix]}brand_class` SET $db_string WHERE bid=".intval($_POST['bid']);
	$Result_Insert = $DB->query($Sql);
	
	/**
	
	類別屬性
	
	**/
	
	$attr_sql = "select * from `{$INFO[DBPrefix]}brand_attributeclass` where cid='" . intval($_POST['bid']) . "' order by attrid desc";
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
					$sql = "delete from `{$INFO[DBPrefix]}brand_attributeclass` where attrid='" . intval($k) . "' and cid='" . intval($_POST['bid']) . "'";
					$DB->query($sql);
				}
			}else{
				$sql = "delete from `{$INFO[DBPrefix]}brand_attributeclass` where cid='" . intval($_POST['bid']) . "'";
				$DB->query($sql);	
			}
		}
	}
	
	if (is_array($_POST['attribute'])){
		foreach($_POST['attribute'] as $v => $k){
			
			$attr_sql = "select * from `{$INFO[DBPrefix]}brand_attributeclass` where attrid='" . intval($k) . "' and cid='" . intval($_POST['bid']) . "' order by attrid desc";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr      = $DB->num_rows($Query_attr);
			if ($Num_attr<=0){
				$sql = "insert into `{$INFO[DBPrefix]}brand_attributeclass` (attrid,cid) values ('" . intval($k) . "','" . intval($_POST['bid']) . "')";
				$DB->query($sql);
			}
		}
	}else{
				$sql = "delete from `{$INFO[DBPrefix]}brand_attributeclass` where cid='" . intval($_POST['bid']) . "'";
					$DB->query($sql);
			}
			
	
	
	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯商品品牌下屬類別");
		$FUNCTIONS->header_location('admin_brand_class_list.php?brand_id=' . $_POST['brand_id'].'&top_id='.$_POST['top_id']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}
//print_r($_POST);
if ($_POST['act']=='Del' ) {
	$Array_bid =  $_POST['bid'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}brand_class` where bid=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除商品品牌下屬類別");
		$FUNCTIONS->header_location('admin_brand_class_list.php?brand_id=' . $_POST['brand_id'].'&top_id='.$_POST['top_id']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}
if ($_GET['Action']=='delPic' && intval($_GET['id'])>0  && $_GET['pic']!='' ) {
	$Sql = "select " . $_GET['type'] . " from  `{$INFO[DBPrefix]}brand_class`  where bid=".intval($_GET['id'])." limit 0,1";
	$Query =  $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Rs  = $DB->fetch_array($Query);
		@unlink("../".$INFO['good_pic_path']."/".$_GET['pic']);
	}
	$DB->query("update `{$INFO[DBPrefix]}brand_class` set  " . $_GET['type'] . "='' where bid=".intval($_GET['id'])." limit 1");
	$FUNCTIONS->setLog("刪除商品品牌下屬分類圖片");
	$FUNCTIONS->header_location('admin_brand_class_list.php?brand_id=' . $_GET['brand_id'] . 'bid=' . $_GET['id'] . '&Action=Modi');
}
if($_POST['act']=="sort"){
	$list = $_POST['list'];
	$list_array = explode(",",$list);
	$i = 0;
	foreach($list_array as $k=>$v){		
		echo $Sql = "update `{$INFO[DBPrefix]}brand_class` set catord='" .$i. "' where bid='" . $v . "'";
		$Query =  $DB->query($Sql);
		$i++;
	}
}

// autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand_class` where bid=".intval($_GET['bid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}brand_class` SET catcontent = '" . $_POST['FCKeditor1'] . "' WHERE bid=".intval($_GET['bid']);
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
