<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include_once "Time.class.php";
$TimeClass = new TimeClass;

if ($_GET['act'] == "del"){
	
	if($_GET['codeid']>0){
		$sql = "select u.username,t.ticketcode from `{$INFO[DBPrefix]}ticketcode` as t inner join `{$INFO[DBPrefix]}user` as u on t.ownid=u.user_id where t.codeid='" . $_GET['codeid'] . "'";
		$Query =  $DB->query($sql);
		$Rs = $DB->fetch_array($Query);
		 $sql_d = "delete from `{$INFO[DBPrefix]}ticketcode` where codeid='" . $_GET['codeid'] . "' and ticketid='" . $_GET['ticketid'] . "'";
		$DB->query($sql_d);
		
	}else{
		$sql = "select u.username from `{$INFO[DBPrefix]}userticket` as t inner join `{$INFO[DBPrefix]}user` as u on t.userid=u.user_id where t.id='" . $_GET['id'] . "'";
		$Query =  $DB->query($sql);
		$Rs = $DB->fetch_array($Query);
		$sql_d = "delete from `{$INFO[DBPrefix]}userticket` where id='" . $_GET['id'] . "' and ticketid='" . $_GET['ticketid'] . "'";
		$DB->query($sql_d);
	}
	
	$sql_u = "update `{$INFO[DBPrefix]}ticketpubrecord` set content=CONCAT(content,'<br>" . date("Y-m-d H:s") . " 刪除發放給 " . $Rs['username'] . " 的折價券" . $Rs['ticketcode'] . "') where recordid='" . $_GET['recordid'] . "'";
	$DB->query($sql_u);
	$FUNCTIONS->header_location('admin_ticketrecord_list.php?ticketid=' . $_GET['ticketid']);
	exit;	
}
//沒有使用MAIL系統
if($INFO['nuevo.ifopen']!=true){
	include "SMTP.Class.inc.php";
	include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
}
	include "sms2.inc.php";
	include "sendmsg.php";
							
	$sendmsg = new SendMsg;


	switch ($_POST['type']){
	    case "1":
			$content = "按會員級別發放:會員級別" . $_POST['user_level'];
			if (intval($_POST['user_level']) == 0){
				$FUNCTIONS->sorry_back('back',"請選擇會員級別");
			}
			$sql_user = "select * from `{$INFO[DBPrefix]}user` where user_level='" . intval($_POST['user_level']) . "'";
			break;
		case "2":
			$content = "按會員發放:帳號" . $_POST['username'];
			if ($_POST['username'] == ""){
				$FUNCTIONS->sorry_back('back',"請填寫帳號");
			}
			$sql_user = "select * from `{$INFO[DBPrefix]}user` where username='" . $_POST['username'] . "'";
			break;
		case "3":
			$content = "按商品發放:" . $_POST['goods_starttime'] . "--" . $_POST['goods_endtime'] . " 購買過的商品ID" . $_POST['goodsid'];
			if ($_POST['goodsid'] == ""){
				$FUNCTIONS->sorry_back('back',"請填寫商品ID");
			}
			

			$sql_user = "select u.* from `{$INFO[DBPrefix]}order_detail` as d inner join `{$INFO[DBPrefix]}order_table` as o on d.order_id=o.order_id inner join `{$INFO[DBPrefix]}user` as u on o.user_id=u.user_id where d.gid='" . $_POST['goodsid'] . "' ";
			if ($_POST['goods_starttime']!="" && $_POST['goods_endtime']!=""){
				$begtimeunix  = $TimeClass->ForYMDGetUnixTime($_POST['goods_starttime'],"-");
				$endtimeunix  = $TimeClass->ForYMDGetUnixTime($_POST['goods_endtime'],"-");
				$sql_user .=  " and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' ";
			}
			 $sql_user .= "group by u.user_id";
			break;
		case "4":
			$content = "按訂單金額發放:" . $_POST['order_starttime'] . "--" . $_POST['order_endtime'] . " 訂單總金額" . $_POST['ordermoney'];
			if (intval($_POST['ordermoney']) <=0){
				$FUNCTIONS->sorry_back('back',"請填寫訂單總金額");
			}
			$sql_user = "select u.* from `{$INFO[DBPrefix]}order_table` as o inner join `{$INFO[DBPrefix]}user` as u on o.user_id=u.user_id  ";
			if ($_POST['order_starttime']!="" && $_POST['order_endtime']!=""){
			$begtimeunix  = $TimeClass->ForYMDGetUnixTime($_POST['order_starttime'],"-");
			$endtimeunix  = $TimeClass->ForYMDGetUnixTime($_POST['order_endtime'],"-") + 24*60*60;
				$sql_user .=  " where o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' and o.order_state=4";
			}
			//$sql_user .= " group by o.user_id having sum(o.totalprice)>" .$_POST['ordermoney']. " ";
			$sql_user .= " and o.totalprice>=" .$_POST['ordermoney']. " ";
			break;
		case "5":
			$content = "按會員生日月份發放:" . $_POST['month'] . "月";
			if (intval($_POST['month']) <=0){
				$FUNCTIONS->sorry_back('back',"請填寫月份");
			}
			$sql_user = "select u.* from  `{$INFO[DBPrefix]}user` as u  ";
			  if ($_POST['month']!="" ){
				$sql_user .=  " where month(u.born_date)='" . intval($_POST['month']) . "' ";
			}
			break;
		case "6":
			
			$Sql      = "select * from `{$INFO[DBPrefix]}mail_group` where mgroup_id='" . intval($_POST['mgroup_id']) . "' order by mgroup_id asc ";
			$Query    = $DB->query($Sql);
			$Rs=$DB->fetch_array($Query);
			if($Rs['searchlist']=="All"){
				 $sql_user = "select * from `{$INFO[DBPrefix]}user` ";
			}elseif($Rs['searchlist']=="noDing"){
				$sql_user = "select * from `{$INFO[DBPrefix]}user` where dianzibao=0";
			}else{
				$sql_user      = "select m.* from `{$INFO[DBPrefix]}mail_group_list` as m where group_id='" . intval($_POST['mgroup_id']) . "' order by m.user_id asc ";
			}
			$content = "按會員分組發放:" . $Rs['mgroup_name'];
			break;
		default:
			$FUNCTIONS->sorry_back('back',"請選擇發放方式");
	}
	//echo $sql_user;exit;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}ticket` where ticketid=".intval($_POST['ticketid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$canmove = $Result['canmove'];
		$ticketname = $Result['ticketname'];
	}

	$db_string = $DB->compile_db_insert_string( array (
	'type'          => $_POST['type'],
	'ticketid'          => ($_POST['ticketid']),
	'count'          => intval($_POST['count']),
	'content'          => $content,
	'pubtime'          => time(),
	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}ticketpubrecord` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	$Result_Insert=$DB->query($Sql);
	$send_array = array();
	$ti = 0;
	if ($Result_Insert)
	{
		$Query_user    = $DB->query($sql_user);
		$sql_record = "select * from `{$INFO[DBPrefix]}ticketpubrecord` where ticketid='" . $_POST['ticketid'] . "' and type='" . $_POST['type'] . "' and count='" . intval($_POST['count']) . "' order by recordid desc ";
		$Query_record    = $DB->query($sql_record);
		$Rs_record=$DB->fetch_array($Query_record);
		$recordid = intval($Rs_record['recordid']);
		
		while ($Rs_user=$DB->fetch_array($Query_user)) {
			if($canmove==1){
				
				for($icount=0;$icount<intval($_POST['count']);$icount++){
					$db_string = $DB->compile_db_insert_string( array (
					'ticketcode'          => intval($_POST['ticketid']) . "0" . randstr(8),
					'ticketid'          => intval($_POST['ticketid']),
					'ownid'          => intval($Rs_user['user_id']),
					'pid'          => intval($recordid),
					)      );

					$Sql_i="INSERT INTO `{$INFO[DBPrefix]}ticketcode` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
					$Result_Insert=$DB->query($Sql_i);
				}
				
			}else{
				$send_array[$ti] = intval($Rs_user['user_id']);
				$ti++;
				$db_string = $DB->compile_db_insert_string( array (
				'ticketid'          => ($_POST['ticketid']),
				'userid'          => intval($Rs_user['user_id']),
				'count'          => intval($_POST['count']),
				'recordid'          => $recordid,
				)      );

				$Sql_i="INSERT INTO `{$INFO[DBPrefix]}userticket` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
				$Result_Insert=$DB->query($Sql_i);
			}
			//沒有使用MAIL系統
			if($INFO['nuevo.ifopen']!=true){
				$user_Sql = "select * from `{$INFO[DBPrefix]}user` where user_id=".intval($Rs_user['user_id'])." limit 0,1";
				$user_Query = $DB->query($user_Sql);
				$user_Num   = $DB->num_rows($user_Query);
				
	
				if ($Rs_user['true_name'] !=""){
					$user_Result= $DB->fetch_array($user_Query);
					
					$Array =  array("username"=>trim($user_Result['true_name']),"ticketname"=>trim($ticketname));
					$SMTP->MailForsmartshop(trim($user_Result['email']),"",12,$Array);
					
					$sendmsg->send(trim($user_Result['other_tel']),$Array,12);
				}
			}
		}
		$FUNCTIONS->setLog("發放折價券");
		//MAIL系統串接
		if($INFO['nuevo.ifopen']==true){
			include_once("../modules/apmail/nuevomailer.class.php");
			$nuevo = new NuevoMailer;
			$idCampaign = $nuevo->setTicket(intval($_POST["ticketid"]),$send_array);
			$nuevo->queryMail($idCampaign,"admin_ticketrecord_list.php?ticketid=" . $_POST['ticketid']);
			exit;
		}
		$FUNCTIONS->header_location('admin_ticketrecord_list.php?ticketid=' . $_POST['ticketid']);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}
function randstr($len=6) {
	$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	// characters to build the password from
	mt_srand((double)microtime()*1000000*getmypid());
	// seed the random number generater (must be done)
	$password='';
	while(strlen($password)<$len)
	$password.=substr($chars,(mt_rand()%strlen($chars)),1);
	return $password;
}

?>
