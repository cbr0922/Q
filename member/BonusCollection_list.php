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
include "../language/".$INFO['IS']."/Good.php";

//装载翻页函数
include ("pagenav_stard.php");
//include_once "pagenav_stard.php";
$objClass  = "p9v";
$Nav       = new buildNav($DB,$objClass);
include ("pagenav_ex.php");
$Build_nav = new NavFunction();

/**
 * 获得红利点数字
 */
$Sql = "SELECT  member_point FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($_SESSION['user_id'])."' limit 0,1";
$Query  = $DB->query($Sql);
while ($Rs=$DB->fetch_array($Query)){
 
	$tpl->assign("Member_Point",$Rs[member_point]);
 	
}

$tpl->assign("BonusCollection_say", $MemberLanguage_Pack[BonusCollection_say]); // 红利商品收藏

$Sql = "select c.collection_id,g.bonusnum,g.smallimg,g.goodsname,g.intro,g.price,g.pricedesc,g.gid from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid)  inner join  `{$INFO[DBPrefix]}bonuscollection_goods` c on (c.gid=g.gid)  where   b.catiffb=1 and g.ifpub=1 and g.ifbonus=1 and c.user_id=".intval($_SESSION['user_id'])."  order by c.cidate desc ";
$BonusCollection_list = $Build_nav->row_nav_return($DB,$Sql,1,' cellSpacing=0 cellPadding=0 width=99% align=center border=0 ',' vAlign=top width=96% ','p9v',8,4,'BonusCollection_list','')		 ;

$tpl->assign($MemberLanguage_Pack);
$tpl->assign($Good);
$tpl->assign($Basic_Command);

$tpl->assign("BonusCollection_list",    $BonusCollection_list);  // 我的商品收藏总表
$tpl->display("BonusCollection_list.html");
?>