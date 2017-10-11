<?php
include_once "Check_Admin.php";
$DocumentPath =  RootDocument;
include "../language/".$INFO['IS']."/Desktop_Pack.php";
$Sql   = " select ou.user_say,ou.sys_say,ou.userback_alread  from `{$INFO[DBPrefix]}order_userback` ou inner join `{$INFO[DBPrefix]}order_table` ot on (ou.order_id=ot.order_id) order by ou.userback_id desc ";
$Query = $DB->query($Sql);
$i=0;
$j=0;
while ($Rs    = $DB->fetch_array($Query)){
	if ( intval($Rs['userback_alread'])==0 ){
		$i++;   //会员反馈未查看信息
	}
	if ( $Rs['sys_say']=="" ){
		$j++;  //尚未回复反馈
	}
}
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
unset ($Rs);

//----------------------已有客户服务---------------------------------
$Sql   = "select count(*) as counts  from `{$INFO[DBPrefix]}kefu` where status=0 order by lastdate DESC";
$Query = $DB->query($Sql);
$Rs    = $DB->fetch_array($Query);
$KeFus = $Rs['counts'];
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
unset ($Rs);


//----------------------已有商品---------------------------------
$Sql   = " select count(gid) as counts  from `{$INFO[DBPrefix]}goods` order by gid desc ";
$Query = $DB->query($Sql);
$Rs    = $DB->fetch_array($Query);
$Goods = $Rs['counts'];
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
unset ($Rs);
//----------------------商品库存报警---------------------------------
$Sql    = " select gid ,ifalarm,alarmnum,storage from `{$INFO[DBPrefix]}goods` where ifalarm=1  and alarmnum>=storage  ";
$Query  = $DB->query($Sql);
$PNum   = $DB->num_rows($Query);
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
//----------------------商品评论---------------------------------
$Sql      = "select count(*) as counts from `{$INFO[DBPrefix]}good_comment` gc  inner join `{$INFO[DBPrefix]}goods` g on (gc.good_id=g.gid) where gc.already_read=0 order by gc.comment_idate desc ";
$Query    = $DB->query($Sql);
$Rs       = $DB->fetch_array($Query);
$PLNum    = $Rs['counts'];
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
//----------------------尚未處裡紅利兌換單x個---------------------------------
$Sql      = "select count(*) as counts from `{$INFO[DBPrefix]}bonuscoll_goods`  where sidate='' order by idate desc ";
$Query    = $DB->query($Sql);
$Rs       = $DB->fetch_array($Query);
$BonusNum = $Rs['counts'];
$DB->free_result($Query);
unset ($Sql);
unset ($Query);

//----------------------目前會員人數---------------------------------
$Sql      = "select count(*) as counts  from `{$INFO[DBPrefix]}user` order by user_id desc";
$Query    = $DB->query($Sql);
$Rs       = $DB->fetch_array($Query);
$MemberNum = $Rs['counts'];
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
$Sql      = "select count(*) as counts  from `{$INFO[DBPrefix]}provider` where state=0 order by provider_id desc";
$Query    = $DB->query($Sql);
$Rs       = $DB->fetch_array($Query);
$newPrider = $Rs['counts'];
$DB->free_result($Query);
unset ($Sql);
unset ($Query);
//----------------------定单跟踪总数---------------------------------
$Order007Sql = "select count(*) as havetotal from `{$INFO[DBPrefix]}order_table` ot where  ot.order007_status=1 and  ot.order007_begtime <= '".date("Y-m-d",time())."' ";
$Order007Query  = $DB->query($Order007Sql);
$Order007Result = $DB->fetch_array($Order007Query);
$HavetotalOrder007  = $Order007Result[havetotal];

$Order007Sql = "select count(*) as havetotal from `{$INFO[DBPrefix]}order_table` ot where  date_format(flightdate,'%Y-%m-%d')='" . date("Y-m-d",time()) . "'";
$Order007Query  = $DB->query($Order007Sql);
$Order007Result = $DB->fetch_array($Order007Query);
$todaycount  = $Order007Result[havetotal];
//----------------------積分過期提醒-------------------------
$d = date('d',time())+9;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$overtime = gmmktime(0,0,0,$m,$d,$y);
$P_Sql= "select count(*) as sumcount from `{$INFO[DBPrefix]}bonuspoint` as b where b.endtime>='" . $overtime . "' and b.endtime<='" . ($overtime+60*60*24) . "' and b.saleorlevel=1 and b.usestate=0 group by b.user_id";
$P_Query = $DB->query($P_Sql);
$P_Result = $DB->fetch_array($P_Query);
$MemberPointC = intval($P_Result['sumcount']);
$M_Num = 0;
//是否提醒過了
if ($MemberPointC>0){
	$M_Sql = "select count(*) as sumcount from `{$INFO[DBPrefix]}bonusalert` where alertdate='" . date("Y-m-d",time()) . "'";	
	$M_Query = $DB->query($M_Sql);
	$M_Result = $DB->fetch_array($M_Query);
	$M_Num = intval($M_Result['sumcount']);
}
//----------------------當日訂單數---------------------------------
$ZSql = "select count(*) as zcount from `{$INFO[DBPrefix]}order_table` ot where order_year='" . date("Y") . "' and order_month='" . date("m") . "' and order_day='" . date("d") . "' ";
$ZQuery  = $DB->query($ZSql);
$ZResult = $DB->fetch_array($ZQuery);
$zcount  = $ZResult[zcount];
$visit_Sql = "select * from `{$INFO[DBPrefix]}count` where visitdate='" . date("Y-m-d",time()) . "'";
$visit_Query = $DB->query($visit_Sql);
$visit_Num   = $DB->num_rows($visit_Query);
$ZSql = "select count(*) as zcount from `{$INFO[DBPrefix]}order_table` ot where order_state=2 ";
$ZQuery  = $DB->query($ZSql);
$ZResult = $DB->fetch_array($ZQuery);
$shenqingquxiao  = $ZResult[zcount];
$ZSql = "select count(*) as zcount from `{$INFO[DBPrefix]}order_table` ot where transport_state=5 ";
$ZQuery  = $DB->query($ZSql);
$ZResult = $DB->fetch_array($ZQuery);
$shenqingtuikuan  = $ZResult[zcount];
/**
 *   引入AJAX
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
 */
function File_Str($string)
{
	return str_replace('//','/',str_replace('\\','/',$string));
}
$Findfile = array();
 function Findfile_Auto($sfp,$sfc,$sft,$sff,$sfb)
{
	global $Findfile;
	$Ex1 = "conf.global.php|setindex.php|Setup.language.php|Smtp.config.php|config.Smarty.php|conf.global.example.php";
	$Ex2 = "count.php|countNum.php|Forumclass_show.php|Newsclass_show.php|Productclass_show.php|ProductclassIndex_show.php|RegisterMap.day.inc|RegisterMap.month.inc|SaleMap.day.inc|SaleMap.month.inc|setwatermark.php|upIndexClass.php|countNum.php|Groupclass_show.php";
	$Ex1_array = explode("|",$Ex1);
	$Ex2_array = explode("|",$Ex2);
	//echo $sfp.'<br>'.$sfc.'<br>'.$sft.'<br>'.$sff.'<br>'.$sfb;
	if(($h_d = @opendir($sfp)) == NULL) return false;
	while(false !== ($Filename = @readdir($h_d)))
	{
		$ifdel = 0;
		if($Filename == '.' || $Filename == '..') continue;
		$Filepath = File_Str($sfp.'/'.$Filename);
		if(is_dir($Filepath) && $sfb) Findfile_Auto($Filepath,$sfc,$sft,$sff,$sfb);
		if(!eregi($sft,$Filename)) continue;
		if ($sfp=="../Config"){
			if(!in_array($Filename,$Ex1_array)){
				$Findfile[count($Findfile)] = $Filepath;
				$ifdel = 1;
				//echo $Filepath . "|";
			}
		}else if ($sfp=="../Config/cache"){
			if(!in_array($Filename,$Ex2_array)){
				$Findfile[count($Findfile)] = $Filepath;
				$ifdel = 1;
				//echo $Filepath . "||";
			}
		}else{
			if (trim($Filepath)!=""){
				$Findfile[count($Findfile)] = $Filepath;
				$ifdel = 1;
			}
			//echo $Filepath . "|||";
		}
		if(is_file($Filepath) && $ifdel==1){
			@chmod($Filepath,0777);
			@unlink($Filepath);
		}
		ob_flush();
		flush();
		
	}
	@closedir($h_d);
	return true;
}
$ScanF = "UploadFile|Config";
$Scan_array = explode("|",$ScanF);
foreach($Scan_array as $k=>$v){
	Findfile_Auto("../" . $v,"","\.php|\.exe|\.html|\.htm|\.js|\.as",1,1);
}
if (count($Findfile)>0){
	include_once Classes . "/SMTP.Class.inc.php";
	include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$SMTP =  new smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
	$Array_list =  array("mailsubject"=>$INFO['site_url'] . "發現病毒/木馬","mailbody"=>"相關文件：" . implode("<br>\r\n",$Findfile) . "<p>已被清除，為保安全請您再檢查一遍各個文件夾。</p>");
	$SMTP->MailForsmartshop("kevin.c@esit.com.tw,pigangel_yang@yahoo.com.cn","","GroupSend",$Array_list);
}
include_once Classes . "/orderClass.php";
$orderClass = new orderClass;
//更改訂單狀態
/*
$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where (pay_state=6 or pay_state=1 or pay_state=7) and (transport_state=1 or transport_state=8 or transport_state=7 or transport_state=9) and createtime<='" . (time()-60*60*24*17) . "' and order_state<>4";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	//echo $Rs_order['order_id'] . "|";
	$orderClass->setOrderState(4,1,$Rs_order['order_id'],"完成交易(系統自動)");
	$d_Sql = "select * from `{$INFO[DBPrefix]}order_detail` where detail_pay_state=1 and (detail_transport_state=1 or detail_transport_state=8) and order_id='" . $Rs_order['order_id'] . "'";
	$Query_d  = $DB->query($d_Sql);
	while($Rs_d=$DB->fetch_array($Query_d)){
		$u_Sql = "update `{$INFO[DBPrefix]}goods` set gid='" . $Rs_d['gid'] ."' where salenum=salenum+1";
		$DB->query($u_Sql);
	}
}
$Sql_order = "select ot.* from `{$INFO[DBPrefix]}order_table` as ot inner join `{$INFO[DBPrefix]}order_action` as oa on oa.state_type=3 and ot.transport_state=oa.state_value where  (ot.transport_state=1 or ot.transport_state=9) and oa.actiontime<='" . (time()-60*60*24*10) . "'";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	//echo $Rs_order['order_id'] . "|";
	$orderClass->setOrderState(2,3,$Rs_order['order_id'],"訂單到貨(系統自動)");
}
$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where (pay_state<>6 and pay_state<>1 and pay_state<>7) and (transport_state<>1 and transport_state<>8 and transport_state<>7 and transport_state<>9) and createtime<='" . (time()-60*60*24*10) . "' and order_state<>3 and order_state<>5";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	//echo $Rs_order['order_id'] . "|";
	$orderClass->setOrderState(5,1,$Rs_order['order_id'],"訂單失效(系統自動)");
}
//供應商更改狀態
$Sql_p   = "select * from `{$INFO[DBPrefix]}provider` where state=2 and end_date<'" . date("Y-m-d",time()) . "'";
$Query_p = $DB->query($Sql_p);
$Num_p   = $DB->num_rows($Query_p);
while($Rs_p=$DB->fetch_array($Query_p)){
	$update_Sql_g = "update `{$INFO[DBPrefix]}goods` set ifpub=0 where provider_id='" . $Rs_p['provider_id'] . "'";
	$DB->query($update_Sql_g);
}
$update_Sql_p = "update `{$INFO[DBPrefix]}provider` set state=4 where (state=2 or state=3) and end_date<'" . date("Y-m-d",time()) . "'";
$DB->query($update_Sql_p);
$d = date('d',time())-30;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$overtime = date("Y-m-d",gmmktime(0,0,0,$m,$d,$y));
$update_Sql_p = "update `{$INFO[DBPrefix]}provider` set state=5 where (state=2 or state=3 or state=4) and end_date<'" . $overtime . "'";
$DB->query($update_Sql_p);
$d = date('d',time())+30;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$overtime = date("Y-m-d",gmmktime(0,0,0,$m,$d,$y));
$update_Sql_p = "update `{$INFO[DBPrefix]}provider` set state=3 where state=2 and start_date<='" . date("Y-m-d",time()) . "' and end_date<='" . $overtime . "'";
$DB->query($update_Sql_p);
*/
//客服郵件地址
$Sql      = "select * from `{$INFO[DBPrefix]}operater` where  groupid =8 and status =1";
$Query    = $DB->query($Sql);
$operater_array = array();
$j = 0;
while($Rs_o=$DB->fetch_array($Query)){
	$operater_array[$j] = $Rs_o['email'];
	$j++;
}
$operater_str = implode(",",$operater_array);
//提醒郵件
//繳款提醒
$mailtype = array(2=>"繳款提醒信",3=>"訂單取消通信",4=>"貨到通知信",5=>"到店取貨通知信",6=>"提醒通知信",7=>"已取貨通知信",8=>"訂單失效通知信",10=>"出貨通知信",11=>"延遲出貨提醒信",12=>"延遲出貨罰款通知信");
/*
//第三天未付款
$d = date('d',time())-3;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$overtime = gmmktime(0,0,0,$m,$d+1,$y);
$starttime = gmmktime(0,0,0,$m,$d,$y);
$Sql_order = "select ot.* from `{$INFO[DBPrefix]}order_table` as ot left join `{$INFO[DBPrefix]}ordermail` as om on ot.order_id=om.order_id and om.mailtype=2 and om.days=3 where (ot.order_state=0 or ot.order_state=1) and (ot.pay_state=0 or ot.pay_state=2 or ot.pay_state=3) and ot.createtime>='" . starttime . "' and ot.createtime<='" . $overtime . "' group by ot.order_id";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	
	if($Rs_order['omid'] == NULL || intval($Rs_order['omid'])==0){
		$Order_serial     = $Rs_order['order_serial'];
		$User_id     = $Rs_order['user_id'];
		$order_id     = $Rs_order['order_id'];
		$Receiver_name    = $Rs_order['receiver_name'];
		$Receiver_email   = $Rs_order['receiver_email'];
		$Receiver_address = $Rs_order['receiver_address'];
		$receiver_mobile = $Rs_order['receiver_mobile'];
		$True_name        = $Rs_order['true_name'];
		$Order_state      = $Rs_order['order_state'];
		$ATM              = $Rs_order['atm'];
		$Pay_Content      = $Rs_order['paycontent'];
		$Pay_Name         = $Rs_order['paymentname'];
		$Pay_Deliver      = $Rs_order['deliveryname'];
		$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
		$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
		$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_idate"=>$Pay_Idate);
		$SMTP->MailForsmartshop(trim($Receiver_email),"",19,$Array);
		$iSql = "insert into `{$INFO[DBPrefix]}ordermail` (order_id,mailtype,sendtime,days)values('" . $order_id . "','2','" . time() . "',3)  ";
		$DB->query($iSql);
	}
}
//第六天未付款
$d = date('d',time())-6;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$overtime = gmmktime(0,0,0,$m,$d+1,$y);
$starttime = gmmktime(0,0,0,$m,$d,$y);
$Sql_order = "select ot.* from `{$INFO[DBPrefix]}order_table` as ot left join `{$INFO[DBPrefix]}ordermail` as om on ot.order_id=om.order_id and om.mailtype=2 and om.days=6 where (ot.order_state=0 or ot.order_state=1) and (ot.pay_state=0 or ot.pay_state=2 or ot.pay_state=3) and ot.createtime>='" . starttime . "' and ot.createtime<='" . $overtime . "' group by ot.order_id";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	if($Rs_order['omid'] == NULL || intval($Rs_order['omid'])==0){
		$Order_serial     = $Rs_order['order_serial'];
		$User_id     = $Rs_order['user_id'];
		$order_id     = $Rs_order['order_id'];
		$Receiver_name    = $Rs_order['receiver_name'];
		$Receiver_email   = $Rs_order['receiver_email'];
		$Receiver_address = $Rs_order['receiver_address'];
		$receiver_mobile = $Rs_order['receiver_mobile'];
		$True_name        = $Rs_order['true_name'];
		$Order_state      = $Rs_order['order_state'];
		$ATM              = $Rs_order['atm'];
		$Pay_Content      = $Rs_order['paycontent'];
		$Pay_Name         = $Rs_order['paymentname'];
		$Pay_Deliver      = $Rs_order['deliveryname'];
		$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
		$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
		$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_idate"=>$Pay_Idate);
		$SMTP->MailForsmartshop(trim($Receiver_email),"",19,$Array);
		$iSql = "insert into `{$INFO[DBPrefix]}ordermail` (order_id,mailtype,sendtime,days)values('" . $order_id . "','2','" . time() . "',6)  ";
		$DB->query($iSql);
	}
}
//第八天未付款
$d = date('d',time())-8;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$overtime = gmmktime(0,0,0,$m,$d+1,$y);
$starttime = gmmktime(0,0,0,$m,$d,$y);
$Sql_order = "select ot.* from `{$INFO[DBPrefix]}order_table` as ot left join `{$INFO[DBPrefix]}ordermail` as om on ot.order_id=om.order_id and om.mailtype=3 and om.days=8 where (ot.order_state=0 or ot.order_state=1) and (ot.pay_state=0 or ot.pay_state=2 or ot.pay_state=3) and ot.createtime>='" . starttime . "' and ot.createtime<='" . $overtime . "' group by ot.order_id";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	if($Rs_order['omid'] == NULL || intval($Rs_order['omid'])==0){
		$Order_serial     = $Rs_order['order_serial'];
		$User_id     = $Rs_order['user_id'];
		$order_id     = $Rs_order['order_id'];
		$Receiver_name    = $Rs_order['receiver_name'];
		$Receiver_email   = $Rs_order['receiver_email'];
		$Receiver_address = $Rs_order['receiver_address'];
		$receiver_mobile = $Rs_order['receiver_mobile'];
		$True_name        = $Rs_order['true_name'];
		$Order_state      = $Rs_order['order_state'];
		$ATM              = $Rs_order['atm'];
		$Pay_Content      = $Rs_order['paycontent'];
		$Pay_Name         = $Rs_order['paymentname'];
		$Pay_Deliver      = $Rs_order['deliveryname'];
		$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
		$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
		$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_idate"=>$Pay_Idate);
		$SMTP->MailForsmartshop(trim($Receiver_email),"",10,$Array);
		$iSql = "insert into `{$INFO[DBPrefix]}ordermail` (order_id,mailtype,sendtime,days)values('" . $order_id . "','3','" . time() . "',8)  ";
		$DB->query($iSql);
	}
}
//貨已到店
//第4天
$d = date('d',time())-4;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$overtime = gmmktime(0,0,0,$m,$d+1,$y);
$starttime = gmmktime(0,0,0,$m,$d,$y);
$Sql_order = "select ot.* from `{$INFO[DBPrefix]}order_table` as ot inner join `{$INFO[DBPrefix]}order_action` as oa on ot.order_id=oa.order_id left join `{$INFO[DBPrefix]}ordermail` as om on ot.order_id=om.order_id and om.mailtype=5 and om.days=4 where ot.transport_state=16  and oa.actiontime >='" . $starttime . "' and oa.actiontime<='" . $overtime . "' group by ot.order_id";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	if($Rs_order['omid'] == NULL || intval($Rs_order['omid'])==0){
		$Order_serial     = $Rs_order['order_serial'];
		$User_id     = $Rs_order['user_id'];
		$order_id     = $Rs_order['order_id'];
		$Receiver_name    = $Rs_order['receiver_name'];
		$Receiver_email   = $Rs_order['receiver_email'];
		$Receiver_address = $Rs_order['receiver_address'];
		$receiver_mobile = $Rs_order['receiver_mobile'];
		$True_name        = $Rs_order['true_name'];
		$Order_state      = $Rs_order['order_state'];
		$ATM              = $Rs_order['atm'];
		$Pay_Content      = $Rs_order['paycontent'];
		$Pay_Name         = $Rs_order['paymentname'];
		$Pay_Deliver      = $Rs_order['deliveryname'];
		$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
		$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
		$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_idate"=>$Pay_Idate);
		$SMTP->MailForsmartshop(trim($Receiver_email),"",21,$Array);
		$iSql = "insert into `{$INFO[DBPrefix]}ordermail` (order_id,mailtype,sendtime,days)values('" . $order_id . "','5','" . time() . "',4)  ";
		$DB->query($iSql);
	}
}
//第6天
$d = date('d',time())-6;
$y = intval(date('Y',time()));
$m = intval(date('m',time()));
$overtime = gmmktime(0,0,0,$m,$d+1,$y);
$starttime = gmmktime(0,0,0,$m,$d,$y);
$Sql_order = "select ot.* from `{$INFO[DBPrefix]}order_table` as ot inner join `{$INFO[DBPrefix]}order_action` as oa on ot.order_id=oa.order_id left join `{$INFO[DBPrefix]}ordermail` as om on ot.order_id=om.order_id and om.mailtype=5 and om.days=6 where ot.transport_state=16  and oa.actiontime >='" . $starttime . "' and oa.actiontime<='" . $overtime . "' group by ot.order_id";
$Query_order  = $DB->query($Sql_order);
while($Rs_order=$DB->fetch_array($Query_order)){
	if($Rs_order['omid'] == NULL || intval($Rs_order['omid'])==0){
		$Order_serial     = $Rs_order['order_serial'];
		$User_id     = $Rs_order['user_id'];
		$order_id     = $Rs_order['order_id'];
		$Receiver_name    = $Rs_order['receiver_name'];
		$Receiver_email   = $Rs_order['receiver_email'];
		$Receiver_address = $Rs_order['receiver_address'];
		$receiver_mobile = $Rs_order['receiver_mobile'];
		$True_name        = $Rs_order['true_name'];
		$Order_state      = $Rs_order['order_state'];
		$ATM              = $Rs_order['atm'];
		$Pay_Content      = $Rs_order['paycontent'];
		$Pay_Name         = $Rs_order['paymentname'];
		$Pay_Deliver      = $Rs_order['deliveryname'];
		$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
		$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
		$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_idate"=>$Pay_Idate);
		$SMTP->MailForsmartshop(trim($Receiver_email),"",21,$Array);
		$iSql = "insert into `{$INFO[DBPrefix]}ordermail` (order_id,mailtype,sendtime,days)values('" . $order_id . "','5','" . time() . "',6)  ";
		$DB->query($iSql);
	}
}
*/
/*
//出貨提醒
for($i=4;$i<=7;$i++){
	$d = date('d',time())-$i;
	$y = intval(date('Y',time()));
	$m = intval(date('m',time()));
	$overtime = gmmktime(0,0,0,$m,$d+1,$y);
	$starttime = gmmktime(0,0,0,$m,$d,$y);
	$Sql_order = "select ot.* from `{$INFO[DBPrefix]}order_table` as ot inner join `{$INFO[DBPrefix]}order_action` as oa on ot.order_id=oa.order_id and oa.state_value=1 and oa.state_type=2 inner join `{$INFO[DBPrefix]}provider` as p on ot.provider_id=p.provider_id where ot.transport_state=0  and oa.actiontime >='" . $starttime . "' and oa.actiontime<='" . $overtime . "' group by ot.order_id";
	$Query_order  = $DB->query($Sql_order);
	
	while($Rs_order=$DB->fetch_array($Query_order)){
		$M_Sql = "select * from `{$INFO[DBPrefix]}ordermail` where om.order_id = '" . intval($Rs_order['order_id']) . "' and om.mailtype=11 and om.days=" . $i . "";
		$Query_M  = $DB->query($M_Sql);
		$Rs_M=$DB->fetch_array($Query_M);
		if(intval($Rs_M['omid'])==0){
			$Order_serial     = $Rs_order['order_serial'];
			$User_id     = $Rs_order['user_id'];
			$order_id     = $Rs_order['order_id'];
			$Receiver_name    = $Rs_order['receiver_name'];
			$Receiver_email   = $Rs_order['receiver_email'];
			$Receiver_address = $Rs_order['receiver_address'];
			$receiver_mobile = $Rs_order['receiver_mobile'];
			$True_name        = $Rs_order['true_name'];
			$Order_state      = $Rs_order['order_state'];
			$ATM              = $Rs_order['atm'];
			$Pay_Content      = $Rs_order['paycontent'];
			$Pay_Name         = $Rs_order['paymentname'];
			$Pay_Deliver      = $Rs_order['deliveryname'];
			$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
			$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
			
			$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_idate"=>$Pay_Idate);
			//$SMTP->MailForsmartshop($Rs_order['receive_mail1'] . "," . $Rs_order['receive_mail2'] . "," . $Rs_order['receive_mail3'] . "," . $operater_str,"",24,$Array);
			$iSql = "insert into `{$INFO[DBPrefix]}ordermail` (order_id,mailtype,sendtime,days)values('" . $order_id . "','11','" . time() . "'," . $i . ")  ";
			$DB->query($iSql);
			
		}
		//$i++;
	}
	
}
*/
/*
//延遲出貨罰款通知信
for($j=8;$j<=20;$j++){
	$d = date('d',time())-$j;
	$y = intval(date('Y',time()));
	$m = intval(date('m',time()));
	$overtime = gmmktime(0,0,0,$m,$d+1,$y);
	$starttime = gmmktime(0,0,0,$m,$d,$y);
	$Sql_order = "select ot.* from `{$INFO[DBPrefix]}order_table` as ot inner join `{$INFO[DBPrefix]}order_action` as oa on ot.order_id=oa.order_id and oa.state_value=1 and oa.state_type=2 inner join `{$INFO[DBPrefix]}provider` as p on ot.provider_id=p.provider_id left join `{$INFO[DBPrefix]}ordermail` as om on ot.order_id=om.order_id and om.mailtype=12 and om.days=" . $j . " where ot.transport_state=0  and oa.actiontime >='" . $starttime . "' and oa.actiontime<='" . $overtime . "' group by ot.order_id";
	$Query_order  = $DB->query($Sql_order);
	//echo $j."a|";
	//echo $PNum   = $DB->num_rows($Query_order) . "$";
	
	while($Rs_order=$DB->fetch_array($Query_order)){
		//echo $j."a|";
		if($Rs_order['omid'] == NULL || intval($Rs_order['omid'])==0){
			$Order_serial     = $Rs_order['order_serial'];
			$User_id     = $Rs_order['user_id'];
			$order_id     = $Rs_order['order_id'];
			$Receiver_name    = $Rs_order['receiver_name'];
			$Receiver_email   = $Rs_order['receiver_email'];
			$Receiver_address = $Rs_order['receiver_address'];
			$receiver_mobile = $Rs_order['receiver_mobile'];
			$True_name        = $Rs_order['true_name'];
			$Order_state      = $Rs_order['order_state'];
			$ATM              = $Rs_order['atm'];
			$Pay_Content      = $Rs_order['paycontent'];
			$Pay_Name         = $Rs_order['paymentname'];
			$Pay_Deliver      = $Rs_order['deliveryname'];
			$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
			$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
			
			$Array =  array("Order_id"=>$order_id,"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_idate"=>$Pay_Idate);
			$SMTP->MailForsmartshop($Rs_order['receive_mail1'] . "," . $Rs_order['receive_mail2'] . "," . $Rs_order['receive_mail3'] . "," . $operater_str,"",25,$Array);
			$iSql = "insert into `{$INFO[DBPrefix]}ordermail` (order_id,mailtype,sendtime,days)values('" . $order_id . "','12','" . time() . "'," . $j . ")  ";
			$DB->query($iSql);
			
		}
		
		//$i++;
	}
	
}
*/
//echo "d";
//exit;
//下架商品
$sql = "update `{$INFO[DBPrefix]}groupdetail` set ifpub=0 where (saleoff_starttime>'" . time() . "' or saleoff_endtime<'" . time() . "') and ifsaleoff=1";
$DB->query($sql);
$sql = "update `{$INFO[DBPrefix]}goods` set ifpub=0 where ((pubstarttime>'" . time() . "' and pubstarttime<>'') or (pubendtime<'" . time() . "' and pubendtime<>'')) and ifpub=1";
$DB->query($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title><?php echo $INFO[site_name]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../js/jquery/SlidePanel20/jquery.slidepanel.css"><!--右側slide樣式-->
</head>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<!--右側slide plug-in-->
<script language="javascript" type="text/javascript" src="../js/jquery/jquery-2.0.3.min.js"></script>
<script language="javascript" src="../js/jquery/SlidePanel20/jquery.slidepanel.js"></script><!--右側slide plug-in-->
<div id="sidebutton" class="desktop_tips">
	<a href="external.html" data-slidepanel="panel"><i class="icon-external-link" style="font-size:20px;color:#999;"></i></a>
<script type="text/javascript">
      $(document).ready(function(){
          $('[data-slidepanel]').slidepanel({
              orientation: 'right',
              mode: 'Overlay'
          });
      });
</script>			
</div>
<!--右側slide plug-in end-->
  
    <div id="contain_out"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#fafafa; border:#CCC 1px solid; margin-top:10px">
  <tr>
    <td width="15%" rowspan="2" valign="top" style="border-right:#CCC 1px solid;"><div id="desktop_menu">
  <div id="desktop_menu_01"><i class="icon-user" style="font-size:14px;margin-right:10px"></i><?php echo $Desktop_Pack[LoginInfo]?></div>
  <TABLE width="100%" border=0 align="center" cellPadding=2 cellSpacing=0 class="9pv">
              <TBODY>
            <TD class=p9orange align=right height=22><span class="p9orange"><i class="icon-key"></i> <?php echo $Desktop_Pack[LoginUser]?><?php echo $_SESSION['Admin_Sa'];?></span>&nbsp;</TD>
              </TR>
              <TR>
                <TD class=p9orange align=right height=20><?php echo $Desktop_Pack[LoginTime]?>&nbsp;<?php echo date("Y-m-d H: ia ",$_SESSION['Admin_Logintime'])?> </TD></TR>
			 
		  </TABLE>
  <div id="desktop_menu_04"><i class="icon-gear" style="font-size:14px;margin-right:10px"></i><?php echo $Desktop_Pack[NoOpShiWu]?></div>
  <div style="clear:both"></div>
  <ul class="desktop_menu">
   <li <?php if ($M_Num<=0 && $MemberPointC>0){?>style="text-indent:0px;"<?php }?>><span style="color:#F63;font-size:14px"><?php echo $MemberPointC;?></span> <span style="color:#666;"> 位會員紅利點數將要過期</span><?php if ($M_Num<=0 && $MemberPointC>0){?><a style="width:90px;" href="admin_pointalert_send.php">[送出提醒]</a><?php }?></li>
   <!--li><A href="admin_provider_list.php?state=0">新申請的供應商 <span style="color:#F63;font-size:14px"><?php echo $newPrider;?></span> <?php echo $Basic_Command['Ge_say']?></A></li>
   <li><A href="admin_order_list.php?Order_Tracks=Show"><?php echo $Desktop_Pack[NoOpOrder_Track];?> <span style="color:#F63;font-size:14px"><?php echo $HavetotalOrder007;?></span> <?php echo $Basic_Command['Ge_say']?></A></li-->
   <li><A href="admin_order_list.php?State=NoOp"><?php echo $Desktop_Pack[NoOpOrder]?>&nbsp;<span style="color:#F63;font-size:14px"><?php echo $FUNCTIONS->NoCL();?></span> <?php echo $Basic_Command['Ge_say']?></A></li>
   <li><A href="admin_order_list.php?State=Cancel">尚未處理申請取消之訂單&nbsp;<span style="color:#F63;font-size:14px"><?php echo $shenqingquxiao;?></span> <?php echo $Basic_Command['Ge_say']?></A></li>
    <!--li><A href="admin_order_list.php?State=Back">尚未處理申請退款之訂單&nbsp;<span style="color:#F63;font-size:14px"><?php echo $shenqingtuikuan;?></span> <?php echo $Basic_Command['Ge_say']?></A></li-->  
   <li><A href="admin_kefu_list.php?status=0"><?php echo $Desktop_Pack[NoOpKeFu]?>&nbsp;<span style="color:#F63;font-size:14px"><?php echo $KeFus?></span> <?php echo $Basic_Command['Ge_say']?></A></li>
   <!--li><A href="admin_comment_list.php?State=Noreplay"><?php echo $Desktop_Pack[NoOpComment]?> <span style="color:#F63;font-size:14px"><?php echo $PLNum?></span> <?php echo $Basic_Command['Ge_say']?></A></li-->
   <!--li><A href="admin_order_list.php?State=NoView"><?php echo $Desktop_Pack[NoOpNoView]?> <span style="color:#F63;font-size:14px"><?php echo $i?></span>  <?php echo $Basic_Command['Ge_say']?></A></li-->
   <!--li><A href="admin_order_list.php?State=Noreplay"><?php echo $Desktop_Pack[NoOpNoreplay]?> <span style="color:#F63;font-size:14px"><?php echo $j?></span> <?php echo $Basic_Command['Ge_say']?></A></li-->
  </ul><div style="clear:both"></div>
  <div id="desktop_menu_02"><i class="icon-briefcase" style="font-size:14px;margin-right:10px"></i><?php echo $Desktop_Pack[CurrentCacheDir];//当前摸板缓存目录?></div>
<TABLE width="100%" border=0 align="center" cellPadding=2 cellSpacing=0 class="9pv">
             <TD  align=right height=20 class="p9orange"><SPAN  class=p9orange>&nbsp;
                  <?php echo $INFO['templates']."/templates_c"?></span></TD>
               </TR>
               <TR>
                <TD  align=right height=20 class="p9orange"><?php echo $Desktop_Pack[CacheFile] ?> <!--缓存文件-->
	<?php
	$dir = "../templates/".$INFO['templates']."/templates_c";
	$dh  = opendir($dir);
	while (false !== ($filename = readdir($dh))) {
		$files[] = $filename;
	}
	array_shift ($files);
	array_shift ($files);
	$templates_c = array();
	$Templates_c_num = 0;
	foreach ($files as $k=>$v){
		if ( $v!='.' && $v!='..' ) {
			if (!is_dir($dir."/".$v)){
				//$templates_c[] = $v ;
				//unlink($dir."/".$v);
				$Templates_c_num++;
			}
		}
	}
	  ?>
				<div id="Templates_CacheNum"><?php echo $Templates_c_num ?><?php echo "&nbsp;".$Basic_Command['Ge_say']?></div>			</TD></TR>
            <TR>
                <TD  align=right height=20 class="p9orange">
				 <a href="javascript:DelCache()"><i class="icon-trash" style="font-size:13px"></i> <?php echo $Desktop_Pack[ClearCacheFile];//清除缓存文件?></a>               </TD>
		  </TABLE>
  <div id="desktop_menu_03"><i class="icon-upload-alt" style="font-size:14px;margin-right:10px"></i><?php echo $Desktop_Pack[SpacesUseSta]?></div>
  <TABLE width="100%" border=0 align="center" cellPadding=2 cellSpacing=0 class="9pv">
              <TBODY>
            <TD class=p9orange align=right height=20>&nbsp;<?php echo $Desktop_Pack[HaveUseSpaces]?><?php  echo      $FUNCTIONS->get_real_size($FUNCTIONS->dirsize($DocumentPath));?></TD></TR>
		  </TABLE>
</div></td>
    <td width="85%" height="136"><ul class="desktop_tips">
            <li><A href="admin_goods_list.php?alarm_recsts=DO"><div class="desktop_amount_box_r"><?php echo $PNum?></div><i class="icon-warning-sign" style="font-size:24px;color:#999;"></i><br><?php echo $Desktop_Pack[ProductStoAlert]?></A></li>
            <li><a href="admin_goods_list.php"><div class="desktop_amount_box_o"><?php echo $Goods?></div><i class="icon-archive" style="font-size:24px;color:#999;"></i><br><?php echo $Desktop_Pack[HaveProduct]?></a></li>
            <li><A href="admin_member_list.php"><div class="desktop_amount_box_g"><?php echo $MemberNum?></div><i class="icon-group" style="font-size:24px;color:#999;"></i><br><?php echo $Desktop_Pack[CurrentMenberNum]?></a></li>
            <li><A href="admin_order_list.php?State=today&begtime=<?php echo date("Y-m-d");?>&endtime=<?php echo date("Y-m-d");?>"><div class="desktop_amount_box_b"><?php echo $zcount;?></div><i class="icon-shopping-cart" style="font-size:24px;color:#999;"></i><br>今日新進訂單</a></li>
            <li><A href="admin_order_list.php?State=todayTrans"><div class="desktop_amount_box_p"><?php echo $todaycount;?></div><i class="icon-bell" style="font-size:24px;color:#999;"></i><br>當日提貨訂單</a></li>
           </ul>
      <div class="clearfix"></div></td>
  </tr>
  <tr>
    <td valign="top"><?php  include_once "desktop_right.php";?></td>
  </tr>
</table>
<div id="desktop_body_out"></div>
</div>      
<div style="clear:both"></div>
<?php include_once "botto.php";?>
</BODY></HTML>
<?php //echo $InitAjax;?>
 <script language="javascript">
 function DelCache(){
 	var url    = "./DelCache.php";
 	//var show   = document.getElementById("Templates_CacheNum");
	$.ajax({
				url: url,
				type:'get',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				    $('#Templates_CacheNum').html(msg);
					//$('#classcount').attr("value",counts+1);
					//$(msg).appendTo('#extclass')
				}
	});
 	//AjaxDelCache(url,show)
 }

 
</script>
