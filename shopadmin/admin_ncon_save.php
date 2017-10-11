<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

if ( version_compare( phpversion(), '4.1.0' ) == -1 ){
	// prior to 4.1.0, use HTTP_POST_VARS
	$postArray = $HTTP_POST_VARS['FCKeditor1'];
}else{
	// 4.1.0 or later, use $_POST
	$postArray = $_POST['FCKeditor1'];
}

if (is_array($postArray))
{
	foreach ( $postArray as $sForm => $value )
	{
		$postedValue = $value;
	}
}
$postedValue = $postedValue!="" ? $postedValue : $postArray ;

if (trim($_POST['pubstarttime'])!=''){
	$date_array = explode("-",trim($_POST['pubstarttime']));
	$pubstarttime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['pubstart_h']),intval($_POST['pubstart_i']),0);
}
if (trim($_POST['pubendtime'])!=''){
	$date_array = explode("-",trim($_POST['pubendtime']));
	$pubendtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['pubend_h']),intval($_POST['pubend_i']),0);
}

if ($_POST['Action']=='Insert' ) {
	$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],'',"../" . $INFO['news_pic_path']);
	$Nimg1  = $FUNCTIONS->Upload_File($_FILES['nimg1']['name'],$_FILES['nimg1']['tmp_name'],'',"../" . $INFO['news_pic_path']);
	$Spath = "../".$INFO['news_pic_path'] ."/";
	include (Classes."/imgthumb.class.php");
	$GDImg = new ThumbHandler();

	$GDImg->setSrcImg($Spath .$Nimg);
	$GDImg->setDstImg($Spath."small/".$Nimg);
	$GDImg->setSrcImg($Spath .$Nimg1);
	$GDImg->setDstImg($Spath."small/".$Nimg1);

	$GDImg->createImg(intval($INFO['article_small']),intval($INFO['article_small']));



	$db_string = $DB->compile_db_insert_string( array (
	'top_id'            => intval($_POST['top_id']),
	'ntitle'            => trim($_POST['ntitle']),
	'nltitle'           => trim($_POST['nltitle']),
	'ntitle_color'      => trim($_POST['ntitle_color']),
	'nltitle_color'     => trim($_POST['nltitle_color']),
	'author'            => trim($_POST['author']),
	'url'               => trim($_POST['url']),
	'url_on'            => intval($_POST['url_on']),
	'nord'              => intval($_POST['nord']),
	'niffb'             => intval($_POST['niffb']),
	'brief'             => $_POST['brief'],
	'keywords'          => $_POST['keywords'],
	'nimg'              => $Nimg,
	'nimg1'             => $Nimg1,
	'nbody'             => $postedValue,
	'nidate'            => time(),
	'pubdate'             => $_POST['pubdate'],
	'smallimg'              => "small/".$Nimg,
	'smallimg1'             => "small/".$Nimg1,
	'pubstarttime'              => $pubstarttime,
	'pubendtime'              => $pubendtime,

	)      );

	//print_r ( "(".$db_string['FIELD_VALUES'].")");
	//exit;
	$Sql="INSERT INTO `{$INFO[DBPrefix]}news` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);

	$news_id = mysql_insert_id();

	/**會員等級瀏覽

	?
		**/

	if (is_array($_POST['userlevel'])){
		foreach($_POST['userlevel'] as $v => $k){
			$sql = "insert into `{$INFO[DBPrefix]}news_userlevel` (levelid,nid) values ('" . intval($k) . "','" . $news_id . "')";
			$DB->query($sql);
		}
	}

	/**


	/**

	TAG

	**/

	if (is_array($_POST['tags'])){
		foreach($_POST['tags'] as $v => $k){
			$sql = "insert into `{$INFO[DBPrefix]}article_tag` (tagid,news_id) values ('" . intval($k) . "','" . $news_id . "')";
			$DB->query($sql);
			$sql_u = "update `{$INFO[DBPrefix]}tag` set articlecount=articlecount+1,count=count+1 where tagid='" . intval($k) . "'";
			$DB->query($sql_u);
		}
	}

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增文章");
		$FUNCTIONS->header_location('admin_ncon_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	$Nimg   = $FUNCTIONS->Upload_File($_FILES['nimg']['name'],$_FILES['nimg']['tmp_name'],$_POST['Old_Nimg'],"../" . $INFO['news_pic_path']);
	$Nimg1  = $FUNCTIONS->Upload_File($_FILES['nimg1']['name'],$_FILES['nimg1']['tmp_name'],$_POST['Old_Nimg1'],"../" . $INFO['news_pic_path']);

	if($Nimg!="" || $Nimg1!=""){
		$Spath = "../".$INFO['news_pic_path'] ."/";
		include (Classes."/imgthumb.class.php");
		$GDImg = new ThumbHandler();
	}

	if ($Nimg!=""){
		$GDImg->setSrcImg($Spath .$Nimg);
		$GDImg->setDstImg($Spath."small/".$Nimg);
		$GDImg->createImg(intval($INFO['article_small']),intval($INFO['article_small']));
	}

	if ($Nimg1!=""){
		$GDImg->setSrcImg($Spath .$Nimg1);
		$GDImg->setDstImg($Spath."small/".$Nimg1);
		$GDImg->createImg(intval($INFO['article_small']),intval($INFO['article_small']));
	}

	$db_string = $DB->compile_db_update_string( array (
	'top_id'            => intval($_POST['top_id']),
	'ntitle'            => trim($_POST['ntitle']),
	'nltitle'           => trim($_POST['nltitle']),
	'ntitle_color'      => trim($_POST['ntitle_color']),
	'nltitle_color'     => trim($_POST['nltitle_color']),
	'author'            => trim($_POST['author']),
	'url'               => trim($_POST['url']),
	'url_on'            => intval($_POST['url_on']),
	'nord'              => intval($_POST['nord']),
	'niffb'             => intval($_POST['niffb']),
	'brief'             => $_POST['brief'],
	'keywords'          => $_POST['keywords'],
	'nimg'              => $Nimg,
	'nimg1'             => $Nimg1,
	'nbody'             => $postedValue,
	'nidate'            => time(),
	'pubdate'             => $_POST['pubdate'],
	'smallimg'              => "small/".$Nimg,
	'smallimg1'             => "small/".$Nimg1,
	'pubstarttime'              => $pubstarttime,
	'pubendtime'              => $pubendtime,
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}news` SET $db_string WHERE news_id=".intval($_POST['News_id']);

	$Result_Insert = $DB->query($Sql);
	/**

	?

	**/
		$level_goods = array();
	$attr_sql = "select * from `{$INFO[DBPrefix]}news_userlevel` where nid='" . intval($_POST['News_id']) . "'";
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
					$sql = "delete from `{$INFO[DBPrefix]}news_userlevel` where levelid='" . intval($k) . "' and nid='" . intval($_POST['News_id']) . "'";
					$DB->query($sql);
				}
			}
		}
	}

	if (is_array($_POST['userlevel'])){
		foreach($_POST['userlevel'] as $v => $k){

			$attr_sql = "select * from `{$INFO[DBPrefix]}news_userlevel` where levelid='" . intval($k) . "' and nid='" . intval($_POST['News_id']) . "'";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr      = $DB->num_rows($Query_attr);
			if ($Num_attr<=0){
				$sql = "insert into `{$INFO[DBPrefix]}news_userlevel` (levelid,nid) values ('" . intval($k) . "','" . intval($_POST['News_id']) . "')";
				$DB->query($sql);
			}
		}
	}else{
				$sql = "delete from `{$INFO[DBPrefix]}news_userlevel` where nid='" . intval($_POST['News_id']) . "'";
					$DB->query($sql);
	}


	/**

	TAG

	**/
	$tag_goods = array();
	$attr_sql = "select * from `{$INFO[DBPrefix]}article_tag` where news_id='" . intval($_POST['News_id']) . "'";
	$Query_attr    = $DB->query($attr_sql);
	$ic=0;
	$Num_attr   = $DB->num_rows($Query_attr);
	while($Rs_arrt=$DB->fetch_array($Query_attr)){
			$tag_goods[$ic]=$Rs_arrt['tagid'];
			$ic++;
	}
	if (is_array($tag_goods)){
		foreach($tag_goods as $v=>$k){
			if (is_array($_POST['tags'])){
				if (!in_array($k,$_POST['tags'])){
					$sql = "delete from `{$INFO[DBPrefix]}article_tag` where tagid='" . intval($k) . "' and news_id='" . intval($_POST['News_id']) . "'";
					$DB->query($sql);
					$sql_u = "update `{$INFO[DBPrefix]}tag` set articlecount=articlecount-1,count=count-1 where tagid='" . intval($k) . "'";
					$DB->query($sql_u);
				}
			}
		}
	}

	if (is_array($_POST['tags'])){
		foreach($_POST['tags'] as $v => $k){
			$attr_sql = "select * from `{$INFO[DBPrefix]}article_tag` where tagid='" . intval($k) . "' and news_id='" . intval($_POST['News_id']) . "'";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr      = $DB->num_rows($Query_attr);
			if ($Num_attr<=0){
				$sql = "insert into `{$INFO[DBPrefix]}article_tag` (tagid,news_id) values ('" . intval($k) . "','" . intval($_POST['News_id']) . "')";
				$DB->query($sql);
				$sql_u = "update `{$INFO[DBPrefix]}tag` set articlecount=articlecount+1,count=count+1 where tagid='" . intval($k) . "'";
				$DB->query($sql_u);
			}
		}
	}else{
			$sql = "delete from `{$INFO[DBPrefix]}article_tag` where news_id='" . intval($_POST['News_id']) . "'";
			$DB->query($sql);
			$sql_u = "update `{$INFO[DBPrefix]}tag` set articlecount=articlecount-" . $Num_attr . ",count=count-" . $Num_attr . " where tagid='" . intval($k) . "'";
			$DB->query($sql_u);
	}

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯文章");
		$FUNCTIONS->header_location('admin_ncon_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}news` where news_id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->setLog("刪除文章");
		$FUNCTIONS->header_location('admin_ncon_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}


if ($_GET['Action']=='DelPic' ) {
	$News_id= intval($_GET['news_id']);
	if ( $News_id>0 ) {
		$Sql =   " select news_id,nimg,smallimg from `{$INFO[DBPrefix]}news`  where news_id='".$News_id."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);
		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Nimg   =  $Result[nimg];
			$smallimg   =  $Result[smallimg];
			$DB->query("update `{$INFO[DBPrefix]}news` set nimg='',smallimg='' where news_id='".$News_id."'");
			@unlink ("../".$INFO['news_pic_path'] . "/" . $Nimg);
			@unlink ("../".$INFO['news_pic_path'] . "/" . $smallimg);
		}
	}
	$FUNCTIONS->setLog("刪除文章圖片");
	$FUNCTIONS->header_location("admin_ncon.php?Action=Modi&news_id=".intval($News_id));
}

if ($_GET['Action']=='DelPic1' ) {
	$News_id= intval($_GET['news_id']);
	if ( $News_id>0 ) {
		$Sql =   " select news_id,nimg1,smallimg1 from `{$INFO[DBPrefix]}news`  where news_id='".$News_id."' limit 0,1";
		$Query = $DB->query($Sql);
		$Num = $DB->num_rows($Query);
		if ($Num>0){
			$Result =  $DB->fetch_array($Query);
			$Nimg1   =  $Result[nimg1];
			$smallimg1   =  $Result[smallimg1];
			$DB->query("update `{$INFO[DBPrefix]}news` set nimg1='',smallimg1='' where news_id='".$News_id."'");
			@unlink ("../".$INFO['news_pic_path'] . "/" . $Nimg1);
			@unlink ("../".$INFO['news_pic_path'] . "/" . $smallimg1);
		}
	}
	$FUNCTIONS->setLog("刪除文章圖片");
	$FUNCTIONS->header_location("admin_ncon.php?Action=Modi&news_id=".intval($News_id));
}

//autosave
if ($_GET['act']=='autosave' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}news` where news_id=".intval($_GET['News_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}news` SET nbody= '" . $_POST['FCKeditor1'] . "' WHERE news_id=".intval($_GET['News_id']);
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
