<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

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



if ($_POST['Action']=='Insert' ) {

	$db_string = $DB->compile_db_insert_string( array (
	'top_id'           => intval($_POST['top_id']),
	'catname'         => trim($_POST['catname']),
	'catord'           => intval($_POST['catord']),
	'catiffb'           => intval($_POST['catiffb']),
	'attr'             => trim($_POST['attr']),
	'intro'            => trim($_POST['intro']),
	'content'          => $postedValue,


	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}forum_class` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增論壇分類");
		$FUNCTIONS->header_location('admin_create_forumclassshow.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {
	$All    = $_POST['all'];
	$Attr   = $_POST['attr'];
	$Attrid = $_POST['attrid'];


	$Attr_String="";
	//这里匹配的目的就是把为了传递过来的数据能够匹配上！
	for ($i=0;$i<count($All);$i++){
		for ($j=0;$j<count($Attrid);$j++){
			if ($Attrid[$j]==$All[$i]){
				$Attr_String   .=$Attr[$i].",";
			}
		}
	}
	$Attr_String = substr($Attr_String,0,intval(strlen($Attr_String)-1));

	$Attrid_String="";
	if (is_array($Attrid)){
		foreach ($Attrid as $k=>$v){
			$Attrid_String.=$v.",";
		}
	}else{
		$Attrid_String.=$Attrid.",";
	}
	$Attrid_String = substr($Attrid_String,0,intval(strlen($Attrid_String)-1));

	if (is_array($Attrid)){
		foreach ($Attrid as $k=>$v){
			if (intval($v)==intval($_POST['attr_name_id'])){
				$MemberlistisHave = 1;
			}
		}
	}elseif ($Attrid==intval($_POST['attr_name_id'])) {
		$MemberlistisHave = 1;
	}





	//当为新建立资料的时候，同时更新USER表资料。
	if (trim($_POST['attr_name_id'])!="" && trim($_POST['attr_name']) && intval($MemberlistisHave)!=1 ){
		$Attr_name_id = intval(strip_tags($_POST['attr_name_id']));
		$Attr_name    = trim(strip_tags($_POST['attr_name']));

		$Query = $DB->query("select forum_attr  from `{$INFO[DBPrefix]}user`  where user_id='".$Attr_name_id."' limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Rs = $DB->fetch_array($Query);
			if (trim($Rs['forum_attr'])!=""){
				$Forum_array = explode(",",$Rs['forum_attr']);
				foreach ($Forum_array as $k=>$v){
					if (intval($v)==intval($_POST['bid'])){
						$Alhave = 1;
					}
				}
			}else{
				$forum_attr_string = $_POST['bid'];
			}

			if (intval($Alhave)!=1){
				$forum_attr_string = $Rs['forum_attr'].",".$_POST['bid'];
			}else{
				$forum_attr_string = $Rs['forum_attr'];
			}


			$DB->query("update `{$INFO[DBPrefix]}user` set forum_attr='".$forum_attr_string."' , vloid=2 where user_id=".intval($Attr_name_id));
			//增加相关字串
			$Attrid_String .= ",".intval($Attr_name_id);
			$Attr_String   .= ",".$Attr_name;
		}
	}

	$db_string = $DB->compile_db_update_string( array (
	'top_id'           => intval($_POST['top_id']),
	'catname'          => trim($_POST['catname']),
	'catord'           => intval($_POST['catord']),
	'catiffb'          => intval($_POST['catiffb']),
	'attr'             => trim($Attr_String),
	'attr_id'          => trim($Attrid_String),
	'intro'            => trim($_POST['intro']),
	'content'          => $postedValue,
	)      );

	$Sql = "UPDATE `{$INFO[DBPrefix]}forum_class` SET $db_string WHERE bid=".intval($_POST['bid']);

	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("編輯論壇分類");
		$FUNCTIONS->header_location('admin_create_forumclassshow.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['bid'];
	$Num_bid  = count($Array_bid);
	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}forum_class` where bid=".intval($Array_bid[$i]));
	}
	if ($Result)
	{
		$FUNCTIONS->setLog("刪除論壇分類");
		$FUNCTIONS->header_location('admin_create_forumclassshow.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
}


if ($_GET['ACT']=='DelAttr' && !empty($_GET[bid])  && $_GET['Action']=='Modi' ) {

	$Query = $DB->query(" select attr_id,attr from `{$INFO[DBPrefix]}forum_class` where bid=".intval($_GET[bid])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Rs  = $DB->fetch_array($Query);
		$Attr_id = $Rs[attr_id];
		$Attr    = $Rs[attr];
		if (intval($_GET[attrid])!=0){
			$Attr_id_string = str_replace(trim($_GET[attrid]),"",$Attr_id);
			$Attr_id_string = str_replace(",,",",",$Attr_id_string);
		}else{
			$Attr_id_string = $Attr_id;
		}

		if (trim($_GET[attrname])!=""){
			$Attr_name_string = str_replace(trim($_GET[attrname]),"",$Attr);
			$Attr_name_string = str_replace(",,",",",$Attr_name_string);
		}else{
			$Attr_name_string = $Attr;
		}

		$DB->query("update `{$INFO[DBPrefix]}forum_class` set attr_id='".$Attr_id_string."' ,attr='".$Attr_name_string."' where bid=".intval($_GET[bid]));
		unset($Query);
		unset($Num);
		unset($Rs);

		if (intval($_GET[attrid])>0){
			$Query = $DB->query("select forum_attr from `{$INFO[DBPrefix]}user` where user_id=".intval($_GET[attrid])." limit 0,1");
			$Num   = $DB->num_rows($Query);
			if ($Num>0){
				$Rs  = $DB->fetch_array($Query);
				$Forum_attr = $Rs[forum_attr];
				$Forum_attr_string = str_replace(trim(intval($_GET[bid])),"",$Forum_attr);
				$Forum_attr_string = str_replace(",,",",",$Forum_attr_string);
				$DB->query("update `{$INFO[DBPrefix]}user` set forum_attr='".$Forum_attr_string."' where user_id=".intval($_GET[attrid]));
			}
		}
	}
	$FUNCTIONS->setLog("編輯論壇版主");
	$FUNCTIONS->sorry_back("admin_fcat.php?Action=Modi&bid=".intval($_GET[bid]),"");
}


?>