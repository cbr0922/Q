<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Addpic' ) {
	//$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['photo']['name'],$_FILES['photo']['tmp_name'],$ArrayPic,"../".$INFO['good_pic_path'],"big,middle");

	if(is_array($_POST['files'])){
		foreach($_POST['files'] as $k=>$v){
			$file1 = explode("/",$v);
			$file2 = explode(".",$v);
			$db_string = $DB->compile_db_insert_string( array (
			'good_id'                => intval($_POST['good_id']),
			'goodpic_title'          => trim($_POST['goodpic_title']),
			'color'          => trim($_POST['color']),
			'detail_name'          => trim($_POST['detail_name']),
			'goodpic_name'       => $file1[0] . "/small/" . $file1[1],
			'middleimg'       => $file1[0] . "/middle/" . $file1[1],
			'bigpic'       => $v,
			)      );
		  $Sql="INSERT INTO `{$INFO[DBPrefix]}good_pic` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		  $Result_Insert = $DB->query($Sql);
		}
	}


	if ($Result_Insert)
	{
		echo "1";
	}else{
		echo $photo;
	}

}

if ($_POST['Action']=='DelPic' ) {

	@unlink ("../".$INFO['good_pic_path']."/".trim($_POST['GoodpicName']));

	$Sql = "select bigpic,middleimg from  `{$INFO[DBPrefix]}good_pic`  where goodpic_id=".intval($_POST['Delid'])." limit 0,1";
	$Query =  $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Rs  = $DB->fetch_array($Query);
		@unlink("../".$INFO['good_pic_path']."/".$Rs[bigpic]);
		@unlink("../".$INFO['good_pic_path']."/".$Rs[middleimg]);
	}
	$DB->query("delete from `{$INFO[DBPrefix]}good_pic` where goodpic_id=".intval($_POST['Delid']));
	echo "1";
}
if ($_POST['act']=='changeorder' ) {



	$DB->query("update `{$INFO[DBPrefix]}good_pic` set orderby='".intval($_POST['orderby'])."' where goodpic_id=".intval($_POST['id']));
	echo "1";
}

?>
