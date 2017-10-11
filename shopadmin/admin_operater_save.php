<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

if ($_POST['Action']=='Insert' ) {

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}operatergroup` where opid=".intval($_POST['groupid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$privilege    =  $Result['privilege'];
	}
	$db_string = $DB->compile_db_insert_string( array (
	'username'                => trim($_POST['username']),
	'userpass'                => password_hash(trim($_POST['userpass']), PASSWORD_BCRYPT),
    'privilege'               => '',
	'status'                  => intval($_POST['status']),
	'type'                  => intval($_POST['type']),
	'truename'                => trim($_POST['truename']),
	'lastlogin'               => time(),
	'email'                => trim($_POST['email']),
	'groupid'                  => intval($_POST['groupid']),
	'privilege'               =>$privilege,
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}operater` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	$opid = mysql_insert_id();

	if ($Result_Insert)
	{
		//擴展類
		for($i=1;$i<=intval($_POST['classcount']);$i++){
			if (intval($_POST['bid' . $i]) > 0){
				$sql_p = "insert into `{$INFO[DBPrefix]}operater_class` (opid,bid) values ('".intval($opid)."','" .intval($_POST['bid' . $i])  . "')";
				$Result_Insert=$DB->query($sql_p);
			}
		}
		$FUNCTIONS->setLog("新增管理員");
		$FUNCTIONS->header_location('admin_operater_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}




if ($_POST['Action']=='Update' ) {

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}operatergroup` where opid=".intval($_POST['groupid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$privilege    =  $Result['privilege'];
	}

	$db_string = $DB->compile_db_update_string( array (
	'username'                => trim($_POST['username']),
	//'userpass'                => md5(trim($_POST['userpass'])),
	'status'                  => intval($_POST['status']),
	'type'                  => intval($_POST['type']),
	'truename'                => trim($_POST['truename']),
	'lastlogin'               => time(),
	'email'                => trim($_POST['email']),
	'groupid'                  => intval($_POST['groupid']),
	'privilege'               =>$privilege,
	)      );
	if (trim($_POST['userpass'])!=""){
		$db_string .= ",userpass='" . password_hash(trim($_POST['userpass']), PASSWORD_BCRYPT) . "'";	
	}



	$Sql = "UPDATE `{$INFO[DBPrefix]}operater` SET $db_string WHERE opid=".intval($_POST['opid']);
	$Result_Insert = $DB->query($Sql);

	if ($Result_Insert)
	{
		//擴展類
		$sql_p = "delete from `{$INFO[DBPrefix]}operater_class` where opid='" . intval($_POST['opid'])  . "'";
		$DB->query($sql_p);
		$opid = intval($_POST['opid']);
		for($i=1;$i<=intval($_POST['classcount']);$i++){
			if (intval($_POST['bid' . $i]) > 0){
				$sql_p = "insert into `{$INFO[DBPrefix]}operater_class` (opid,bid) values ('".intval($opid)."','" .intval($_POST['bid' . $i])  . "')";
				$Result_Insert=$DB->query($sql_p);
			}
		}
		$FUNCTIONS->setLog("編輯管理員");
		$FUNCTIONS->header_location('admin_operater_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}operater`  where opid=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除管理員");
	$FUNCTIONS->header_location('admin_operater_list.php');

}

?>
