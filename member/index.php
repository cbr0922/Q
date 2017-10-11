<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
require "check_member.php";
include("../configs.inc.php");
include("global.php");
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";

$info_id = 11; //**會員首頁說明
$Query   = $DB->query("select info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=".$info_id." limit 0,1");
$Num   = $DB->num_rows($Query);

if ( $Num==0 )
$FUNCTIONS->header_location("../index.php");

if ($Num>0){
	$Result = $DB->fetch_array($Query);
	$Content = $Result['info_content'];
}

$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}user` where user_id=".$_SESSION['user_id']." limit 0,1");
$Num    = $DB->num_rows($Query);
if ($Num==0){
	$FUNCTIONS->sorry_back("back","");
}
$Result = $DB->fetch_array($Query);
$tpl->assign("True_name",         $Result['true_name']); //真实姓名
$tpl->assign("en_firstname",         $Result['en_firstname']); //英文姓
$tpl->assign("en_secondname",         $Result['en_secondname']); //英文名
$tpl->assign("membercode",              $Result['memberno']);
$tpl->assign("userlevelname",              $_SESSION['userlevelname']);
 $point =$FUNCTIONS->Userpoint(intval($_SESSION['user_id']),1);
$d = date('d',time());
$y = intval(date('Y',time()));
$m = intval(date('m',time()))+1;
$overtime = gmmktime(0,0,0,$m,$d,$y);
$P_Sql= "select b.* from `{$INFO[DBPrefix]}bonuspoint` as b where b.endtime>='" . time() . "' and b.endtime<='" . ($overtime+24*60*60) . "' and b.saleorlevel=1 and  b.user_id='" . $_SESSION['user_id'] . "' and (b.usestate=0 or b.usestate=1) ";
$P_Query = $DB->query($P_Sql);
$PNum   = $DB->num_rows($P_Query);
$totalpoint = 0;
$OrderList = array();
$i = 0;
if($PNum>0){
	while($Rs = $DB->fetch_array($P_Query)){
		
		$OrderList[$i]['point'] = $Rs['point'];
		$OrderList[$i]['id'] = $Rs['id'];
		$OrderList[$i]['addtime']   = date("Y-m-d",$Rs['addtime']);
		$OrderList[$i]['endtime']   = date("Y-m-d",$Rs['endtime']);
		$OrderList[$i]['type']   = intval($Rs['type']);
		$OrderList[$i]['usestate']   = intval($Rs['usestate']);
		$OrderList[$i]['orderid']   = intval($Rs['orderid']);
		switch (intval($Rs['usestate'])){
			case 0:
				$totalpoint+=$Rs['point'];
				break;
			case 1:
				$u_sql = "select sum(usepoint) as usepoint from `{$INFO[DBPrefix]}bonusbuydetail` where combipoint_id=" . $Rs['id'];
				$u_Query =  $DB->query($u_sql);
				$u_Rs = $DB->fetch_array($u_Query);
				$usepoint = intval($u_Rs['usepoint']);
				$totalpoint=$totalpoint + $Rs['point']-$usepoint;

				break;
			
		}
		$OrderList[$i]['typename'] = $Rs['typename'];
		$OrderList[$i]['content'] = $Rs['content'];
		$i++;
	}
}


$Sql = "select count(g.gid) as gcount from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid)  inner join  `{$INFO[DBPrefix]}collection_goods` c on (c.gid=g.gid)  where  b.catiffb=1 and g.ifpub=1 and c.user_id=".intval($_SESSION['user_id'])." order by c.cidate desc ";
$Query =  $DB->query($Sql);
$Rs = $DB->fetch_array($Query);
$goodscount = $Rs['gcount'];

$Sql = "select sum(ut.count) as count,ut.ticketid,t.money,t.ticketname,t.use_starttime,t.use_endtime,t.moneytype from `{$INFO[DBPrefix]}userticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where  ut.userid=".intval($_SESSION['user_id'])." group by ut.ticketid";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
$ticketcount = 0;
while ( $Rs = $DB->fetch_array($Query)){
		$ticketcount+=intval($Rs['count']);
}
$Sql = "select ut.ticketcode,ut.ticketid,ut.userid,ut.usetime,t.money,t.ticketname,t.use_starttime,t.use_endtime,t.moneytype from `{$INFO[DBPrefix]}ticketcode` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where  ut.ownid=".intval($_SESSION['user_id'])."  and (ut.usetime='' or ut.usetime is null)";
$Query =  $DB->query($Sql);
$Num   =  $DB->num_rows($Query);
$ticketcount+=intval($Num);
$tpl->assign("ticketcount",               $ticketcount);

$tpl->assign("totalticket",     $ticketcount);
$tpl->assign("totalpoint",     $totalpoint);
$tpl->assign("point",     $point);
$tpl->assign("goodscount",     $goodscount);

$tpl->assign("OrderList",     $OrderList);
$tpl->assign("PNum",     $PNum);
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关
$tpl->assign("Content",        $Content); //**會員首頁說明
$tpl->assign($MemberLanguage_Pack);
$tpl->display("member_index.html");
?>
