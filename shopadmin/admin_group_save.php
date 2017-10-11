<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
if($_POST['act']=="save"){
	$subSql = "";
	if($_POST['checkLevel']==1){
		$subSql .= " and u.user_level='".intval($_POST['user_level'])."'";
	}
	if($_POST['checkYear']==1){
		$subSql .= " and YEAR(u.born_date) ='".intval($_POST['Year'])."'";
	}
	if($_POST['checkMonth']==1){
		$subSql .= " and MONTH(u.born_date) ='".intval($_POST['Month'])."'";
	}
	if($_POST['checkSex']==1){
		$subSql .= " and u.sex='".intval($_POST['Sex'])."'";
	}
	if($_POST['checkArea']==1){
		if($_POST['county']!="" && $_POST['county']!="請選擇")
			$subSql .= " and u.Country='".trim($_POST['county'])."'";
		if($_POST['province']!="" && $_POST['province']!="請選擇")
			$subSql .= " and u.canton='".trim($_POST['province'])."'";
		if($_POST['city']!="" && $_POST['city']!="請選擇")
			$subSql .= " and u.city='".trim($_POST['city'])."'";
	}
	if($_POST['checkcompany']==1){
		$subSql .= " and u.companyid='".intval($_POST['company'])."'";
	}
	if($_POST['checkdianzibao']==1){
		$subSql .= " and u.dianzibao='".intval($_POST['dianzibao'])."'";
	}
	if($_POST['checkreg']==1){
		if(trim($_POST['begtime'])!="")
			$subSql .= " and u.reg_date>='".trim($_POST['begtime'])."'";
		if(trim($_POST['endtime'])!="")
			$subSql .= " and u.reg_date<='".trim($_POST['endtime'])."'";
	}
	if($_POST['checkordertime']==1){
		if($_POST['order_begtime']!=""){
			$begtimeunix  = $TimeClass->ForYMDGetUnixTime($_POST['order_begtime'],"-");
			$subSql .= " and o.createtime>='".$begtimeunix."'";
		}
		if($_POST['order_endtime']!=""){
			$endtimeunix  = $TimeClass->ForYMDGetUnixTime($_POST['order_endtime'],"-");
			$subSql .= " and o.createtime<='".$endtimeunix."'";
		}
	}
	if($_POST['checkmoney']==1){
		if(intval($_POST['minmoney'])>0){
			$subSql .= " and ot.total>='".intval($_POST['minmoney'])."'";
		}
		if(intval($_POST['maxmoney'])>0){
			if(intval($_POST['minmoney'])>0){
				$money_string .= " and ";
			}
			$subSql .= " and ot.total<='".intval($_POST['maxmoney'])."'";
		}
		if(intval($_POST['maxmoney'])>0 || intval($_POST['minmoney'])>0){
			$money_string = "having " . $money_string;
		}
	}
	if(is_array($_POST)){
		foreach($_POST as $k=>$v){
			$_POST[$k] = urlencode($v);
		}
	}
	$searchlist = json_encode($_POST);
	//print_r($searchlist);exit;
	$db_string = $DB->compile_db_insert_string( array (
		'mgroup_name'                 => urldecode($_POST['mgroup_name']),
		'auto'                => 0,
		'searchlist'           => trim($searchlist),
	)      );
	$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	$group_id = mysql_insert_id();

	$Sql      = "select u.* ,ot.total from `{$INFO[DBPrefix]}user` u  left join (select user_id,sum(discount_totalPrices) as total from `{$INFO[DBPrefix]}order_table` where pay_state=1 group by user_id) as ot on u.user_id=ot.user_id  where 1=1 ".$subSql."";
	$Query    = $DB->query($Sql);
	while ($Rs=$DB->fetch_array($Query)) {
		$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) VALUES ('" . $group_id . "','" . $Rs['user_id'] . "','" . $Rs['email'] . "')";
		$DB->query($Sql);
	}
	$FUNCTIONS->setLog("新建郵件組");
	$FUNCTIONS->header_location('admin_group_list.php');
}
if($_GET['act']=="fresh"){
	$group_id = intval($_GET['group_id']);
	$gSql = "select * from `{$INFO[DBPrefix]}mail_group` where mgroup_id='" . intval($_GET['group_id']) . "'";
	$gQuery    = $DB->query($gSql);
	$Result = $DB->fetch_array($gQuery);
	//print_r($Result);exit;
	if($Result['auto']==0 && $Result['searchlist']!=""){

		$searchlist = $Result['searchlist'];

		$search_array = json_decode($searchlist,TRUE);
		if(is_array($search_array)){
			foreach($search_array as $k=>$v){
				$search_array[$k] = urldecode($v);
			}
		}
		//print_r($search_array);
		//echo $search_array['user_level'] . "b";exit;
		$subSql = "";
		if($search_array['checkLevel']==1){
			$subSql .= " and u.user_level='".intval($search_array['user_level'])."'";
		}

		if($search_array['checkYear']==1){
			$subSql .= " and YEAR(u.born_date) ='".intval($search_array['Year'])."'";
		}
		if($search_array['checkMonth']==1){
			$subSql .= " and MONTH(u.born_date) ='".intval($search_array['Month'])."'";
		}
		if($search_array['checkSex']==1){
			$subSql .= " and u.sex='".intval($search_array['Sex'])."'";
		}
		if($search_array['checkArea']==1){
			//$search_array['county'] = urldecode($search_array['county']);
			if($search_array['county']!="" && $search_array['county']!="請選擇")
				$subSql .= " and u.Country='".trim($search_array['county'])."'";
			if($search_array['province']!="" && $search_array['province']!="請選擇")
				$subSql .= " and u.canton='".trim($search_array['province'])."'";
			if($search_array['city']!="" && $search_array['city']!="請選擇")
				$subSql .= " and u.city='".trim($search_array['city'])."'";
		}

		if($search_array['checkcompany']==1){
			$subSql .= " and u.companyid='".intval($search_array['company'])."'";
		}
		if($search_array['checkdianzibao']==1){
			$subSql .= " and u.dianzibao='".intval($search_array['dianzibao'])."'";
		}
		if($search_array['checkreg']==1){
			if(trim($search_array['begtime'])!="")
				$subSql .= " and u.reg_date>='".trim($search_array['begtime'])."'";
			if(trim($search_array['endtime'])!="")
				$subSql .= " and u.reg_date<='".trim($search_array['endtime'])."'";
		}
		if($search_array['checkordertime']==1){
			if($search_array['order_begtime']!=""){
				$begtimeunix  = $TimeClass->ForYMDGetUnixTime($search_array['order_begtime'],"-");
				$subSql .= " and o.createtime>='".$begtimeunix."'";
			}
			if($_POST['order_endtime']!=""){
				$endtimeunix  = $TimeClass->ForYMDGetUnixTime($search_array['order_endtime'],"-");
				$subSql .= " and o.createtime<='".$endtimeunix."'";
			}
		}
		if($search_array['checkmoney']==1){
			if(intval($search_array['minmoney'])>0){
				$subSql .= " and ot.total>='".intval($search_array['minmoney'])."'";
			}
			if(intval($search_array['maxmoney'])>0){
				if(intval($search_array['minmoney'])>0){
					$money_string .= " and ";
				}
				$subSql .= " and ot.total<='".intval($search_array['maxmoney'])."'";
			}
			if(intval($search_array['maxmoney'])>0 || intval($search_array['minmoney'])>0){
				$money_string = "having " . $money_string;
			}
		}

		 $Sql_f      = "select u.* ,ot.total from `{$INFO[DBPrefix]}user` u  left join (select user_id,sum(discount_totalPrices) as total from `{$INFO[DBPrefix]}order_table` where pay_state=1 group by user_id) as ot on u.user_id=ot.user_id  where 1=1 ".$subSql."";
	}elseif($Result['auto']==1){
		$Sql_f      =" select * from `{$INFO[DBPrefix]}user` where user_level='" . $Result['userlevel'] . "'";
	}elseif($Result['auto']==2){
		$Sql_f      =" select * from `{$INFO[DBPrefix]}user` where dianzibao='1'";
	}

	if($Sql_f!=""){
		$D_sql = "delete from `{$INFO[DBPrefix]}mail_group_list` where group_id='" . $group_id . "' and user_id>0";
		$DB->query($D_sql);
		//echo $Sql_f;exit;
		$Query    = $DB->query($Sql_f);
		while ($Rs=$DB->fetch_array($Query)) {
			$Sql="INSERT INTO `{$INFO[DBPrefix]}mail_group_list` (group_id,user_id,email) VALUES ('" . $group_id . "','" . $Rs['user_id'] . "','" . $Rs['email'] . "')";
			$DB->query($Sql);
		}

		$FUNCTIONS->setLog("刷新郵件組");
	}
	$FUNCTIONS->header_location('admin_group_list.php');
}
if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}mail_group` where mgroup_id=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}mail_group_list` where group_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除郵件組");
	$FUNCTIONS->header_location('admin_group_list.php');

}
?>
