<?php
error_reporting(0);
session_start();

include("../../configs.inc.php");
include("global.php");
/**
 *  装载产品语言包
 */
include RootDocument."/language/".$INFO['IS']."/Good.php";

@header("Content-type: text/html; charset=utf-8");

if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
	@header("location:".$INFO[site_url]."/member/login_windows.php");
}

$Goods_id       = $FUNCTIONS->Value_Manage($_GET['goods_id'],$_POST['Goods_id'],'back','');  //判断是否有正常的ID进入
$Collection_id  = $FUNCTIONS->Value_Manage($_GET['collection_id'],$_POST['Collection_id'],'back','');  //判断是否有正常的ID进入


$Query   = $DB->query("select * from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid )  where  b.catiffb=1 and g.ifpub=1 and g.gid=".$Goods_id." limit 0,1");
$Num   = $DB->num_rows($Query);

if ( $Num==0 ) //如果不存在资料
$FUNCTIONS->header_location("index.php");

if ($Num>0){
	$Result_goods = $DB->fetch_array($Query);


	if ($Result_goods['attr']!=""){
		$attrI   =  "0,".$Result_goods['attr'];
		$Attr    =  explode(',',$attrI);
		$Attr_num=  count($Attr);
	}else{
		$Attr_num=0;
	}

	$Goodsname = $Result_goods['goodsname'];
	$Brand     = $Result_goods['brand'];
	$View_num  = $Result_goods['view_num'];
	$Bn        = $Result_goods['bn'];
	$Ifgl      = $Result_goods['ifgl'];
	$Bid       = $Result_goods['bid'];
	$Unit      = $Result_goods['unit'];
	$Intro     = $Result_goods['intro'];
	$Price     = $Result_goods['price'];
	$Point     = $Result_goods['point'];
	$Bonusnum  = $Result_goods['bonusnum'];
	$Body      = $Result_goods['body'];
	$Smallimg  = $Result_goods['smallimg'];
	$Middleimg  = $Result_goods['middleimg'];

	$goodattrI   =  "0,".$Result_goods['goodattr'];
	$goodAttr    =  explode(',',$goodattrI);
}




//数据库变量
$tpl->assign("Goods_id",   $Goods_id);    //商品id
$tpl->assign("Goodsname",  $Goodsname);   //商品名称
$tpl->assign("Brand",      $Brand);       //商品品牌
$tpl->assign("View_num",   $View_num);    //浏览次数
$tpl->assign("Bn",         $Bn);          //编号
$tpl->assign("Bid",        $Bid);         //类别ID
$tpl->assign("Unit",       $Unit);        //产品单位
$tpl->assign("Intro",      $Intro);       //简单介绍
$tpl->assign("Price",      $Price);       //价格
$tpl->assign("Point",      $Point);       //积分点
$tpl->assign("Bonusnum",   $Bonusnum);       //积分点
$tpl->assign("Body",       $Body);        //主题介绍
$tpl->assign("goodAttr",   $goodAttr);    //多级别属性数组
$tpl->assign("Attr",       $Attr);        //多级别属性数组
$tpl->assign("Attr_num",   $Attr_num);    //多级别属性数组个数
$tpl->assign("Smallimg",   $Smallimg);    //产品略缩小图
$tpl->assign("Middleimg",   $Middleimg);    //产品略缩小图

//循环多属性部分
if (is_array($Attr)){
	$AttrArray = array();
	$ProductArray = array();
	for($i=1;$i<$Attr_num;$i++){
		$AttrArray[]        = $Attr[$i];
		$ProductArray[]     = $goodAttr[$i];
		$ProductAttrArray[] = array($Attr[$i]=>$goodAttr[$i]);
	}
}

$tpl->assign("ProductAttrArray",      $ProductAttrArray);    //循环多属性部分数组


//获得用户本身积分

$Sql_user    = " select member_point from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id']." limit 0,1");
$Query_user  =  $DB->query($Sql_user);
$Num_user    = $DB->num_rows($Query_user);
if ($Num_user>0){
	$Rs_user = $DB->fetch_array($Query_user);
	$Member_point = intval($Rs_user['member_point']);
	$tpl->assign("Member_point",    $Member_point);   //用户点数
}


$tpl->assign("Collection_id",     $Collection_id);   //当前使用的红利收藏表ID
$tpl->assign("Goods_id",          $Goods_id);   //当前使用的商品ID





//当有提交行为的时候，系统将完成的操作是减去用户积分，并将此积分数字写入红利表中，并将红利申请表中的askfor 设置为1（默认是0，1是已经申请，2是系统取消，3是已经确认）
//处理次序是：当用户对一商品多次兑现的时候，往bonuscoll_goods表中插入新记录！ 记录包括（产品ID，收藏ID，每次商品减少的价格，提出疑问）并更新用户本身积分。

$bonusnums= $Member_point - $Bonusnum;
$Username = !empty($_SESSION['true_name']) ? $_SESSION['true_name'] : $_SESSION['username'];

if ($_POST[Action]=='Insert' && $bonusnums>=0){
	//首先更新会员表的积分情况,
	$DB->query("update `{$INFO[DBPrefix]}user` set member_point=".intval($bonusnums)." where user_id=".intval($_SESSION['user_id'])." ");

	//然后插入一条新记录到已提交红利表中，记录本产品提交状态，并记录所消耗的积分。
	$DB->query("insert into  `{$INFO[DBPrefix]}bonuscoll_goods` (username,user_id,goods_id,collection_id,bonusnum,askfor,isay,goodsname,idate) values ('".$Username."','".$_SESSION['user_id']."','".$Goods_id."','".$Collection_id."','".$Bonusnum."','1','".$_POST[isay]."','".$Goodsname."','".time()."')");

	echo "
   <script language=javascript>
	   alert('".$Good[HaveGetBonusShenQing]."'); 
    window.opener.location.href=window.opener.location.href
    window.close();	
   </script>
   
   ";
	exit;

	$FUNCTIONS->sorry_back("close",$Good[HaveGetBonusShenQing]);//已成功確認需求紅利商品申請！
}



$tpl->assign($Good);

$tpl->display("change_into.html");

?>
