<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include( dirname(__FILE__)."/"."../configs.inc.php" );
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Email_Pack.php");


//print_r($_POST);


if ($_GET[action]=='sendto' && intval($_GET[Articleid])>0){

	$ValueId = $FUNCTIONS->Value_Manage($_GET['Articleid'],$_POST['Articleid'],'back','');
	$tpl->assign("ActionType",  "Article");
	$tpl->assign("ValueId",  $ValueId);

}


if ($_GET[action]=='sendto' && intval($_GET[gid])>0){

	$ValueId = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');
	$tpl->assign("ActionType",  "Good");
	$tpl->assign("ValueId",  $ValueId);

}


if ($_POST[ActionType]=='Article'){
	/*if (trim($_POST['inputcode'])!=$_SESSION['Code_Reg']){
		$FUNCTIONS->sorry_back("back","驗證碼錯誤"); //驗證碼錯誤
	}*/
	include("securimage.php");
	$img=new Securimage();
	$valid=$img->check($_POST['inputcode']);
	if($valid==false) {
	$FUNCTIONS->sorry_back("back","驗證碼錯誤");
	  }

	include (RootDocument."/language/".$INFO['IS']."/Article_Pack.php");
	$tpl->assign($Article_Pack);

	$Articleid = $FUNCTIONS->Value_Manage('',$_POST['ValueId'],'back','');
	$Query   = $DB->query("select nc.ncid,nc.ncname,n.ntitle,n.nbody,n.ntitle_color,n.nimg,n.nidate from `{$INFO[DBPrefix]}news` n inner join  `{$INFO[DBPrefix]}nclass` nc on ( n.top_id=nc.ncid &&  nc.ncatiffb=1 && n.niffb=1 && n.news_id=".intval($Articleid).") limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ( $Num==0 ) {//如果不存在资料
		$FUNCTIONS->sorry_back("back","");
	}
	if ($Num>0){
		$Result_Article = $DB->fetch_array($Query);
		$Ncateid        = $Result_Article['ncid'];
		$Nclass_name    = $Result_Article['ncname'];
		$Ntitle         = $Result_Article['ntitle'];
		$Nbody          = $Result_Article['nbody'];
	}

	$Emailcontent  = $Email_Pack[YourFriend_Content_I]."".$_POST[yourname]."".$Email_Pack[YourFriend_Content_II]."<a href='".$INFO[site_url]."' target='_blank'>".$INFO[site_name]."</a>".$Email_Pack[YourFriend_Content_III]."<BR><BR>";
	$Emailcontent .= $Email_Pack[YourFriend_Content_IV]."<a href='".$INFO[site_url]."/article/article{$Articleid}'>".$INFO[site_url]."/article/article{$Articleid}</a><BR><BR>";
	$Emailcontent .= $_POST[emailcontent]."<BR><BR><BR><BR>".$Nbody;
	include "SMTP.Class.inc.php";
	include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
	$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
	$Array =  array("mailsubject"=>trim($Ntitle),"mailbody"=>$Emailcontent);

	//print_r($Array);
	$SMTP->MailForsmartshop(trim($_POST[toemail]),"",'GroupSend',$Array);

	$FUNCTIONS->sorry_back($INFO[site_url]."/article/article".$Articleid,$Email_Pack[YourFriend_Content_V]);

}

if ($_POST[ActionType]=='Good'){
	/*if (trim($_POST['inputcode'])!=$_SESSION['Code_Reg']){
		$FUNCTIONS->sorry_back("back","驗證碼錯誤"); //驗證碼錯誤
	}*/

	include("securimage.php");
	$img=new Securimage();
	$valid=$img->check($_POST['inputcode']);
	if($valid==false) {
	 	$FUNCTIONS->sorry_back("back","驗證碼錯誤");
	}

	include RootDocument."/language/".$INFO['IS']."/Good.php";
	$tpl->assign($Good);

	$Gid = $FUNCTIONS->Value_Manage('',$_POST['ValueId'],'back','');
	$Sql =   "select b.attr,g.goodsname,g.brand,g.brand_id,g.view_num,g.video_url,g.nocarriage,g.keywords,g.pricedesc,g.bn,g.ifgl,g.bid,g.unit,g.intro,g.price,g.point,g.body,g.middleimg,g.smallimg,g.bigimg,g.gimg,g.goodattr,g.good_color,g.good_size,g.ifrecommend,g.ifspecial,g.ifalarm,g.storage,g.alarmnum,g.ifbonus,g.ifhot,g.provider_id,g.ifjs,g.js_begtime,g.js_endtime,g.js_price,g.js_totalnum,p.provider_name,br.brandname,br.logopic from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)   where  b.catiffb=1 and g.ifpub=1 and g.gid=".intval($Gid)." limit 0,1";
	$Query   = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ( $Num==0 ) {//如果不存在资料
		$FUNCTIONS->sorry_back("back","");
	}
	if ($Num>0){
		$Result_goods = $DB->fetch_array($Query);
		$Goodsname = $Result_goods['goodsname']."".$FUNCTIONS->Storage($Result_goods['ifalarm'],$Result_goods['storage'],$Result_goods['alarmnum']);
		$Brand     = "<a href='".$INFO[site_url]."/brand/brand_product_list.php?BrandID=".$Result_goods['brand_id']."'>".$Result_goods['brandname']."</a>";
		$Brand_id  = $Result_goods['brand_id'];
		$View_num  = $Result_goods['view_num'];
		$Bn        = $Result_goods['bn'];
		$Ifgl      = $Result_goods['ifgl'];
		$Bid       = $Result_goods['bid'];
		$Unit      = $Result_goods['unit'];
		$Intro     = $Result_goods['intro'];
		$Price     = $Result_goods['price'];
		$Pricedesc = $Result_goods['pricedesc'];
		$Point     = intval($Result_goods['point']);
		$Body      = $Result_goods['body'];
		$Smallimg  = $Result_goods['smallimg'];
		$Middleimg = $Result_goods['middleimg'];
		$Gimg      = $Result_goods['gimg'];
		$Bigimg    = $Result_goods['bigimg'];
		$Alarmnum  = $Result_goods['alarmnum'];
		$Keywords  = $Result_goods['keywords'];
		$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($_SESSION['user_id'])."' limit 0,1";
		$Query  = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		$recommendno = $Rs['memberno'];
		$recommendno = $recommendno == "" ? "" : "_" . $recommendno;
		//$tpl->assign("recommendno",   $recommendno);

		$Emailcontent  = $Email_Pack[YourFriend_good_Content_I]."".$_POST[yourname]."".$Email_Pack[YourFriend_good_Content_II]."<a href='".$INFO[site_url]."' target='_blank'>".$INFO[site_name]."</a>".$Email_Pack[YourFriend_good_Content_III]."<BR><BR>";
		$Emailcontent .= $Email_Pack[YourFriend_good_Content_IV]."<a href='".$INFO[site_url]."/product/detail".$Gid.$recommendno . "'>".$INFO[site_url]."/product/detail".$Gid.$recommendno . "</a><BR><BR>";
		$Emailcontent .= $_POST[emailcontent]."<BR><BR><BR><BR>";

		$Emailcontent .= "<a href='".$INFO[site_url]."/product/detail".$Gid.$recommendno . "' target='_blank'><img src='".$INFO[site_url]."/UploadFile/GoodPic/".$Middleimg."'  border='0' align='left'></a>";
		$Emailcontent .= $Good[Product_Name]."：".$Goodsname."<br /><br />";
        $Emailcontent .= $Good[Intro_name]."：".$Intro."<br /><br />";
		$Emailcontent .= $Good[Pricedesc_say]."：".$Pricedesc."<br /><br />";


		include "SMTP.Class.inc.php";
		include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
		$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
		$Array =  array("mailsubject"=>trim($Result_goods['goodsname']),"mailbody"=>$Emailcontent);

		$SMTP->MailForsmartshop(trim($_POST[toemail]),"",'GroupSend',$Array);
		$FUNCTIONS->sorry_back($INFO[site_url]."/product/detail".$Gid,$Email_Pack[YourFriend_good_Content_V]);
	}
}




$tpl->assign($Email_Pack);
$tpl->assign("sid",        time());
$tpl->display("mailtofriend.html");
?>
