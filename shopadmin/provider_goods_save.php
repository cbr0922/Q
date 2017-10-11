<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

/**
 *  装载语言包

include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
 */

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
function num($no){
	return str_repeat("0",6-strlen($no)) . $no;
}
$postedValue = $postedValue!="" ? $postedValue : $postArray ;




if ($_POST['Action']=='Insert' ) {
	$Query_b = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($_POST['bid'])." limit 0,1");
	$Result_b= $DB->fetch_array($Query_b);
	$gain    =  $Result_b['gain'];
	if($_POST['cost'] > $_POST['pricedesc']){
		$FUNCTIONS->sorry_back('','低於成本',"provider_goods_list.php");
	}
	/*
	if ($FUNCTIONS->controlGoodsNum() == false) {
		$FUNCTIONS->sorry_back('',$Basic_Command['Pub_Error'],"provider_goods_list.php");
	}
	*/
	$arr_select = array();
	for ($i=1;$i<intval($_POST['Attr_num']);$i++ ){
		$arr_select[$i]=$_POST["goodattr".$i];
	}
	$arr_char = implode(",",$arr_select);
	$Query_b = $DB->query("select max(goodsno) as maxno from `{$INFO[DBPrefix]}goods`");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = $Result_b['maxno'];
	$goodsno = num(intval($maxno)+1);
	//将得到的字符串由数组中得到STRING值。
	
	
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($_SESSION['sa_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	$Result= $DB->fetch_array($Query);
	$providerno           =  $Result['providerno'];
	if (intval($providerno)<=0){
		$providerno = "000000";		
	}
	$Query_b = $DB->query("select bn from `{$INFO[DBPrefix]}goods` where bn like '" . $providerno . "%' order by bn desc limit 0,1");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = substr($Result_b['bn'],6);
	$bn = num(intval($maxno)+1);
	
	
	$bn = $providerno.$bn;

	$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['img']['name'],$_FILES['img']['tmp_name'],$ArrayPic,"../".$INFO['good_pic_path']);

	$db_string = $DB->compile_db_insert_string( array (
	'ifpub'              => 0,
	'bid'                => intval($_POST['bid']),
	'goodsname'          => trim($_POST['goodsname']),
	'bn'                 => trim($bn),
	'brand'              => trim($_POST['brand']),
	'brand_id'           => intval($_POST['brand_id']),
	'intro'              => $_POST['intro'],
	'keywords'           => $_POST['keywords'],
	'unit'               => trim($_POST['unit']),
	'pricedesc'          => intval($_POST['pricedesc']),
	'price'              => intval($_POST['price']),
	'point'              => intval($_POST['point']),
	'alarmnum'           => intval($_POST['alarmnum']),
	'alarmcontent'       => trim($_POST['alarmcontent']),
	//'storage'            => intval($_POST['storage']),
	'ifbonus'            => intval($_POST['ifbonus']),
	'ifalarm'            => intval($_POST['ifalarm']),
	'nocarriage'         => intval($_POST['nocarriage']),
	'ifrecommend'        => intval($_POST['ifrecommend']),
	'ifspecial'          => intval($_POST['ifspecial']),
	'subject_id'         => trim(@implode(",",$_POST['subject_id'])),
	'ifhot'              => intval($_POST['ifhot']),
	'ifjs'               => intval($_POST['ifjs']),
	'video_url'          => trim($_POST['video_url']),
	'ifgl'               => intval($_POST['ifgl']),
	'provider_id'        => intval($_SESSION['sa_id']),
	'smallimg'           => $File_NewName[1],
	'bigimg'             => $File_NewName[2],
	'gimg'               => $File_NewName[0],
	'middleimg'          => $File_NewName[3],
	'body'               => $postedValue,
	'goodattr'           => trim($arr_char),
	'bonusnum'           => intval($_POST['bonusnum']),
	'cost'           => intval($_POST['cost']),
	'idate'              => time(),
	'ifpresent'      =>  intval($_POST['ifpresent']),
	'present_money'      =>  intval($_POST['present_money']),
	'en_name'              => trim($_POST['en_name']),
	//'component'              => trim($_POST['component']),
	//'capability'              => trim($_POST['capability']),
	'cap_des'              => trim($_POST['cap_des']),
	'trans_type'              => intval(trim($_POST['trans_type'])),
	'iftransabroad'              => intval(trim($_POST['iftransabroad'])),
	'trans_special'              => intval(trim($_POST['trans_special'])),
	'trans_special_money'              => intval(trim($_POST['trans_special_money'])),
	'ifxygoods'              => intval(trim($_POST['ifxygoods'])),
	'ifxy'              => intval(trim($_POST['ifxy'])),
	//'ifxysale'              => intval(trim($_POST['ifxysale'])),
	'ifchange'              => intval(trim($_POST['ifchange'])),
	'xycount'              => intval(trim($_POST['xycount'])),
	'sale_name'              => trim($_POST['sale_name']),
		'sale_subject'              => intval(trim($_POST['sale_subject'])),
	'sale_price'              => intval(trim($_POST['sale_price'])),
	'ifsales'              => intval(trim($_POST['ifsales'])),
	'ifsaleoff'              => intval(trim($_POST['ifsaleoff'])),
	'saleoff_starttime'      => $saleoff_startdate,
	'saleoff_endtime'      => $saleoff_enddate,
	'ifadd'              => intval(trim($_POST['ifadd'])),
	'addmoney'              => intval(trim($_POST['addmoney'])),
	'addprice'              => intval(trim($_POST['addprice'])),
	'oeid'              => trim($_POST['oeid']),
	'saleoffprice'              => intval(trim($_POST['saleoffprice'])),
	'iftimesale'              => intval(trim($_POST['iftimesale'])),
	'view_num'              => intval(trim($_POST['view_num'])),
	'timesale_starttime'      => $timesale_starttime,
	'timesale_endtime'      => $timesale_endtime,
	'jscount'           => $Js_count,
	'present_endmoney'      =>  intval($_POST['present_endmoney']),
	'transtype'              => intval($_POST['transtype']),
	'ifmood'              => intval($_POST['ifmood']),
	'addtransmoney'              => intval($_POST['addtransmoney']),
	'transtypemonty'              => intval($_POST['transtypemonty']),
	'goodsno'                =>$goodsno,
	'salename_color'              => trim($_POST['salename_color']),
	'memberprice'              => intval(trim($_POST['memberprice'])),
	'combipoint'              => intval(trim($_POST['combipoint'])),
	'guojima'              => trim($_POST['guojima']),
	'xinghao'              => trim($_POST['xinghao']),
	'weight'              => trim($_POST['weight']),
	'salecost'              => intval(trim($_POST['salecost'])),
	'chandi'              => trim($_POST['chandi']),
	'ERP'              => trim($_POST['ERP']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	
	$gid = mysql_insert_id();
	$FUNCTIONS->setStorage(intval($_POST['storage']),0,$gid,0,"","","",0);
	
	/**
	
	會員價格
	
	**/
	/*
	
	$Sql      = "select * from `{$INFO[DBPrefix]}user_level`";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if($Num>0){
		$dSql = "delete from `{$INFO[DBPrefix]}member_price` where m_goods_id='" . $gid . "'";	
		$DB->query($dSql);
		while ($Rs=$DB->fetch_array($Query)) {
			if ($Rs['pricerate']>0){
				
				$Sql = "insert into `{$INFO[DBPrefix]}member_price` (m_goods_id,m_level_id,m_price,m_detail_id) values(".$gid.",".$Rs['level_id'].",".intval($Rs['pricerate']*0.01*intval($_POST['pricedesc'])).",0)";
				$Result = $DB->query($Sql);
			}
		}
	}
	*/

	
	/**
	
	類別屬性值
	
	**/
	
	if (is_array($_POST['attribute'])){
		foreach($_POST['attribute'] as $v => $k){
			$sql = "insert into `{$INFO[DBPrefix]}attributegoods` (valueid,gid) values ('" . intval($k) . "','" . $gid . "')";
			$DB->query($sql);
			$sql_u = "update `{$INFO[DBPrefix]}attributevalue` set count=count+1 where valueid='" . intval($k) . "'";
			$DB->query($sql_u);
		}
	}
	
	/**
	
	會員級別 
	
	**/
	
	if (is_array($_POST['userlevel'])){
		foreach($_POST['userlevel'] as $v => $k){
			$sql = "insert into `{$INFO[DBPrefix]}goods_userlevel` (levelid,gid) values ('" . intval($k) . "','" . $gid . "')";
			$DB->query($sql);
		}
	}
	
	/**
	
	TAG 
	
	**/
	
	if (is_array($_POST['tags'])){
		foreach($_POST['tags'] as $v => $k){
			$sql = "insert into `{$INFO[DBPrefix]}goods_tag` (tagid,gid) values ('" . intval($k) . "','" . $gid . "')";
			$DB->query($sql);
			$sql_u = "update `{$INFO[DBPrefix]}tag` set goodscount=goodscount+1,count=count+1 where tagid='" . intval($k) . "'";
			$DB->query($sql_u);
		}
	}

	if ($Result_Insert)
	{
		//擴展類
		for($i=1;$i<=intval($_POST['classcount']);$i++){
			if (intval($_POST['bid' . $i]) > 0){
				$sql_p = "insert into `{$INFO[DBPrefix]}goods_class` (gid,bid) values ('".intval($gid)."','" .intval($_POST['bid' . $i])  . "')";
				$Result_Insert=$DB->query($sql_p);
			}
		}
		$FUNCTIONS->setLog("新增商品");
		$FUNCTIONS->header_location('provider_goods_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}





if ($_POST['Action']=='Copy' ) {
	/*
	if ($FUNCTIONS->controlGoodsNum() == false) {
		$FUNCTIONS->sorry_back('',$Basic_Command['Pub_Error'],"provider_goods_list.php");
	}
	*/
	$arr_select = array();
	for ($i=1;$i<intval($_POST['Attr_num']);$i++ ){
		$arr_select[$i]=$_POST["goodattr".$i];
	}
	$arr_char = implode(",",$arr_select);
	/*
	$Query_b = $DB->query("select max(goodsno) as maxno from `{$INFO[DBPrefix]}goods`");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = $Result_b['maxno'];
	$goodsno = num(intval($maxno)+1);
	*/
	$Query_b = $DB->query("select max(goodsno) as maxno from `{$INFO[DBPrefix]}goods`");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = $Result_b['maxno'];
	$goodsno = num(intval($maxno)+1);
	
	//将得到的字符串由数组中得到STRING值。
	
	
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($_SESSION['sa_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	$Result= $DB->fetch_array($Query);
	$providerno           =  $Result['providerno'];
	if (intval($providerno)<=0){
		$providerno = "000000";		
	}
	$Query_b = $DB->query("select bn from `{$INFO[DBPrefix]}goods` where bn like '" . $providerno . "%' order by bn desc limit 0,1");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = substr($Result_b['bn'],6);
	$bn = num(intval($maxno)+1);
	
	
	$bn = $providerno.$bn;
	
	//将得到的字符串由数组中得到STRING值。

	//$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['img']['name'],$_FILES['img']['tmp_name'],trim($_POST['img']),$_POST[Thumbimg],$_POST[Markimg],"../".$INFO['good_pic_path']); 拷贝产品的时候。将不负责图片的拷贝

	$db_string = $DB->compile_db_insert_string( array (
	'bid'                => intval($_POST['bid']),
	'ifpub'              =>0,
	'goodsname'          => trim($_POST['goodsname']),
	'bn'                 => $bn,
	'brand'              => trim($_POST['brand']),
	'brand_id'           => intval($_POST['brand_id']),
	'intro'              => $_POST['intro'],
	'keywords'           => $_POST['keywords'],
	'unit'               => trim($_POST['unit']),
	'pricedesc'          => trim($_POST['pricedesc']),
	'price'              => trim($_POST['price']),
	'point'              => intval($_POST['point']),
	'alarmnum'           => intval($_POST['alarmnum']),
	'alarmcontent'       => trim($_POST['alarmcontent']),
	//'storage'            => intval($_POST['storage']),
	'ifbonus'            => intval($_POST['ifbonus']),
	'nocarriage'         => intval($_POST['nocarriage']),
	'ifalarm'            => intval($_POST['ifalarm']),
	'ifrecommend'        => intval($_POST['ifrecommend']),
	'ifspecial'          => intval($_POST['ifspecial']),
	'subject_id'         => trim(@implode(",",$_POST['subject_id'])),
	'ifhot'              => intval($_POST['ifhot']),
	'ifjs'               => intval($_POST['ifjs']),
	'ifgl'               => intval($_POST['ifgl']),
	'js_begtime'         => $js_begtime,
	'js_endtime'         => $js_endtime,
	'js_price'           => $Js_price,
	'js_totalnum'        => intval($_POST['Js_totalnum']),
	'video_url'          => trim($_POST['video_url']),
	'provider_id'        => intval($_SESSION['sa_id']),
	'body'               =>  $postedValue,
	'goodattr'           => trim($arr_char),
	'bonusnum'           => intval($_POST['bonusnum']),
	'idate'              => time(),
	'cost'           	 => intval($_POST['cost']),
	'if_monthprice'      =>  intval($_POST['if_monthprice']),
	'en_name'              => trim($_POST['en_name']),
	//'component'              => trim($_POST['component']),
	//'capability'              => trim($_POST['capability']),
	'cap_des'              => trim($_POST['cap_des']),
	'trans_type'              => intval(trim($_POST['trans_type'])),
	'iftransabroad'              => intval(trim($_POST['iftransabroad'])),
	'trans_special'              => intval(trim($_POST['trans_special'])),
	'trans_special_money'              => intval(trim($_POST['trans_special_money'])),
	'ifxygoods'              => intval(trim($_POST['ifxygoods'])),
	'ifxy'              => intval(trim($_POST['ifxy'])),
	//'ifxysale'              => intval(trim($_POST['ifxysale'])),
	'ifchange'              => intval(trim($_POST['ifchange'])),
	'xycount'              => intval(trim($_POST['xycount'])),
	'sale_name'              => trim($_POST['sale_name']),
		'sale_subject'              => intval(trim($_POST['sale_subject'])),
	'sale_price'              => intval(trim($_POST['sale_price'])),
	'ifsales'              => intval(trim($_POST['ifsales'])),
	'ifsaleoff'              => intval(trim($_POST['ifsaleoff'])),
	'saleoff_starttime'      => $saleoff_startdate,
	'saleoff_endtime'      => $saleoff_enddate,
	'ifadd'              => intval(trim($_POST['ifadd'])),
	'addmoney'              => intval(trim($_POST['addmoney'])),
	'addprice'              => intval(trim($_POST['addprice'])),
	'oeid'              => trim($_POST['oeid']),
	'saleoffprice'              => intval(trim($_POST['saleoffprice'])),
	'iftimesale'              => intval(trim($_POST['iftimesale'])),
	'timesale_starttime'      => $timesale_starttime,
	'timesale_endtime'      => $timesale_endtime,
	'view_num'              => intval(trim($_POST['view_num'])),
	'jscount'           => $Js_count,
	'present_endmoney'      =>  intval($_POST['present_endmoney']),
	'transtype'              => intval($_POST['transtype']),
	'ifmood'              => intval($_POST['ifmood']),
	'addtransmoney'              => intval($_POST['addtransmoney']),
	'transtypemonty'              => intval($_POST['transtypemonty']),
	'goodsno'                =>$goodsno,
	'salename_color'              => trim($_POST['salename_color']),
	'memberprice'              => intval(trim($_POST['memberprice'])),
	'combipoint'              => intval(trim($_POST['combipoint'])),
	'guojima'              => trim($_POST['guojima']),
	'xinghao'              => trim($_POST['xinghao']),
	'weight'              => trim($_POST['weight']),
	'salecost'              => intval(trim($_POST['salecost'])),
	'chandi'              => trim($_POST['chandi']),
	'ERP'              => trim($_POST['ERP']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	
	

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("複製商品");
		$FUNCTIONS->header_location('provider_goods_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}









if ($_POST['Action']=='Update' ) {
	$Query_b = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($_POST['bid'])." limit 0,1");
	$Result_b= $DB->fetch_array($Query_b);
	$gain    =  $Result_b['gain'];
	if($_POST['cost'] > $_POST['pricedesc']){
		$FUNCTIONS->sorry_back('','低於成本',"provider_goods_list.php");
	}
	$arr_select = array();
	for ($i=1;$i<intval($_POST['Attr_num']);$i++ ){
		$arr_select[$i]=$_POST["goodattr".$i];
	}
	$arr_char = implode(",",$arr_select);
	//将得到的字符串由数组中得到STRING值。

	$ArrayPic = array(trim($_POST['gimg']),trim($_POST['smallimg']),$_POST['bigimg'],$_POST['middleimg']);
	if ($_FILES['img']['name']!=""){
		$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['img']['name'],$_FILES['img']['tmp_name'],$ArrayPic,"../".$INFO['good_pic_path']);
	}else{
		$File_NewName = $ArrayPic;
	}
//exit;

	$db_string = $DB->compile_db_update_string( array (
	'bid'                => intval($_POST['bid']),
	'goodsname'          => trim($_POST['goodsname']),
	'bn'                 => trim($_POST['bn']),
	'brand'              => trim($_POST['brand']),
	'brand_id'           => intval($_POST['brand_id']),
	'intro'              => $_POST['intro'],
	'keywords'           => $_POST['keywords'],
	'unit'               => trim($_POST['unit']),
	'pricedesc'          => intval($_POST['pricedesc']),
	'price'              => intval($_POST['price']),
	'point'              => intval($_POST['point']),
	'alarmnum'           => intval($_POST['alarmnum']),
	'alarmcontent'       => trim($_POST['alarmcontent']),
	//'storage'            => intval($_POST['storage']),
	'ifbonus'            => intval($_POST['ifbonus']),
	'ifalarm'            => intval($_POST['ifalarm']),
	'ifpub'              => 0,
	'ifrecommend'        => intval($_POST['ifrecommend']),
	'nocarriage'         => intval($_POST['nocarriage']),
	'subject_id'         => trim(@implode(",",$_POST['subject_id'])),
	'video_url'          => trim($_POST['video_url']),
	'ifspecial'          => intval($_POST['ifspecial']),
	'ifhot'              => intval($_POST['ifhot']),
	'ifjs'               => intval($_POST['ifjs']),
	'ifgl'               => intval($_POST['ifgl']),
	'js_totalnum'        => intval($_POST['Js_totalnum']),
	'provider_id'        => intval($_SESSION['sa_id']),
	'smallimg'           => $File_NewName[1],
	'bigimg'             => $File_NewName[2],
	'gimg'               => $File_NewName[0],
	'middleimg'          => $File_NewName[3],
	'body'               =>  $postedValue,
	'goodattr'           => trim($arr_char),
	'bonusnum'           => intval($_POST['bonusnum']),
	'cost'           => intval($_POST['cost']),
	'idate'              => time(),
	'if_monthprice'      =>  intval($_POST['if_monthprice']),
	'ifpresent'      =>  intval($_POST['ifpresent']),
	'present_money'      =>  intval($_POST['present_money']),
	'en_name'              => trim($_POST['en_name']),
	//'component'              => trim($_POST['component']),
	//'capability'              => trim($_POST['capability']),
	'cap_des'              => trim($_POST['cap_des']),
	'trans_type'              => intval(trim($_POST['trans_type'])),
	'iftransabroad'              => intval(trim($_POST['iftransabroad'])),
	'trans_special'              => intval(trim($_POST['trans_special'])),
	'trans_special_money'              => intval(trim($_POST['trans_special_money'])),
	'ifxygoods'              => intval(trim($_POST['ifxygoods'])),
	'ifxy'              => intval(trim($_POST['ifxy'])),
	//'ifxysale'              => intval(trim($_POST['ifxysale'])),
	'ifchange'              => intval(trim($_POST['ifchange'])),
	'xycount'              => intval(trim($_POST['xycount'])),
	'sale_name'              => trim($_POST['sale_name']),
		'sale_subject'              => intval(trim($_POST['sale_subject'])),
	'sale_price'              => intval(trim($_POST['sale_price'])),
	'ifsales'              => intval(trim($_POST['ifsales'])),
	'ifsaleoff'              => intval(trim($_POST['ifsaleoff'])),
	'saleoff_starttime'      => $saleoff_startdate,
	'saleoff_endtime'      => $saleoff_enddate,
	'ifadd'              => intval(trim($_POST['ifadd'])),
	'addmoney'              => intval(trim($_POST['addmoney'])),
	'addprice'              => intval(trim($_POST['addprice'])),
	'oeid'              => trim($_POST['oeid']),
	'saleoffprice'              => intval(trim($_POST['saleoffprice'])),
	'iftimesale'              => intval(trim($_POST['iftimesale'])),
	'timesale_starttime'      => $timesale_starttime,
	'timesale_endtime'      => $timesale_endtime,
	'view_num'              => intval(trim($_POST['view_num'])),
	'jscount'           => $Js_count,
	'present_endmoney'      =>  intval($_POST['present_endmoney']),
	'transtype'              => intval($_POST['transtype']),
	'ifmood'              => intval($_POST['ifmood']),
	'addtransmoney'              => intval($_POST['addtransmoney']),
	'transtypemonty'              => intval($_POST['transtypemonty']),
	'salename_color'              => trim($_POST['salename_color']),
	'memberprice'              => intval(trim($_POST['memberprice'])),
	'combipoint'              => intval(trim($_POST['combipoint'])),
	'checkstate' =>0,
	'guojima'              => trim($_POST['guojima']),
	'xinghao'              => trim($_POST['xinghao']),
	'weight'              => trim($_POST['weight']),
	'salecost'              => intval(trim($_POST['salecost'])),
	'chandi'              => trim($_POST['chandi']),
	'ERP'              => trim($_POST['ERP']),
	)      );



	$Sql = "UPDATE `{$INFO[DBPrefix]}goods` SET $db_string WHERE gid=".intval($_POST['gid']);
	$Result_Insert = $DB->query($Sql);
	
	/**
	
	會員價格
	
	**/
	/*
	$Sql      = "select * from `{$INFO[DBPrefix]}user_level`";
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	if($Num>0){
		while ($Rs=$DB->fetch_array($Query)) {
			$Sql = "update `{$INFO[DBPrefix]}member_price` set m_price='" . intval($Rs['pricerate']*0.01*intval($_POST['pricedesc'])) . "' where  m_goods_id=".intval($_POST['gid'])." and m_level_id=".$Rs['level_id']." and detail_id=0";
			$Result = $DB->query($Sql);
		}
	}
	*/
	
	/**
	
	類別屬性值
	
	**/
	$attr_sql = "select * from `{$INFO[DBPrefix]}attributegoods` where gid='" . intval($_POST['gid']) . "'";
	$Query_attr    = $DB->query($attr_sql);
	$ic=0;
	$Num_attr   = $DB->num_rows($Query_attr);
	while($Rs_arrt=$DB->fetch_array($Query_attr)){
			$attr_class[$ic]=$Rs_arrt['valueid'];
			$ic++;
	}
	if (is_array($attr_class)){
		foreach($attr_class as $v=>$k){
			if (is_array($_POST['attribute'])){
				if (!in_array($k,$_POST['attribute'])){
					$sql = "delete from `{$INFO[DBPrefix]}attributegoods` where valueid='" . intval($k) . "' and gid='" . intval($_POST['gid']) . "'";
					$DB->query($sql);
					$sql_u = "update `{$INFO[DBPrefix]}attributevalue` set count=count-1 where valueid='" . intval($k) . "'";
					$DB->query($sql_u);
				}
			}
		}
	}
	
	if (is_array($_POST['attribute'])){
		foreach($_POST['attribute'] as $v => $k){
			
			$attr_sql = "select * from `{$INFO[DBPrefix]}attributegoods` where valueid='" . intval($k) . "' and gid='" . intval($_POST['gid']) . "'";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr      = $DB->num_rows($Query_attr);
			if ($Num_attr<=0){
				$sql = "insert into `{$INFO[DBPrefix]}attributegoods` (valueid,gid) values ('" . intval($k) . "','" . intval($_POST['gid']) . "')";
				$DB->query($sql);
				$sql_u = "update `{$INFO[DBPrefix]}attributevalue` set count=count+1 where valueid='" . intval($k) . "'";
				$DB->query($sql_u);
			}
		}
	}else{
				$sql = "delete from `{$INFO[DBPrefix]}attributegoods` where gid='" . intval($_POST['gid']) . "'";
				$DB->query($sql);
				$sql_u = "update `{$INFO[DBPrefix]}attributevalue` set count=count-" . $Num_attr . " where valueid='" . intval($k) . "'";
				$DB->query($sql_u);
			}
	
	/**
	
	會員級別 
	
	**/
	$level_goods = array();
	$attr_sql = "select * from `{$INFO[DBPrefix]}goods_userlevel` where gid='" . intval($_POST['gid']) . "'";
	$Query_attr    = $DB->query($attr_sql);
	$ic=0;
	while($Rs_arrt=$DB->fetch_array($Query_attr)){
			$level_goods[$ic]=$Rs_arrt['levelid'];
			$ic++;
	}
	if (is_array($level_goods)){
		foreach($level_goods as $v=>$k){
			if (is_array($_POST['userlevel'])){
				if (!in_array($k,$_POST['userlevel'])){
					$sql = "delete from `{$INFO[DBPrefix]}goods_userlevel` where levelid='" . intval($k) . "' and gid='" . intval($_POST['gid']) . "'";
					$DB->query($sql);
				}
			}
		}
	}
	
	if (is_array($_POST['userlevel'])){
		foreach($_POST['userlevel'] as $v => $k){
			
			$attr_sql = "select * from `{$INFO[DBPrefix]}goods_userlevel` where levelid='" . intval($k) . "' and gid='" . intval($_POST['gid']) . "'";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr      = $DB->num_rows($Query_attr);
			if ($Num_attr<=0){
				$sql = "insert into `{$INFO[DBPrefix]}goods_userlevel` (levelid,gid) values ('" . intval($k) . "','" . intval($_POST['gid']) . "')";
				$DB->query($sql);
			}
		}
	}else{
				$sql = "delete from `{$INFO[DBPrefix]}goods_userlevel` where gid='" . intval($_POST['gid']) . "'";
					$DB->query($sql);
	}
			
	/**
	
	TAG 
	
	**/
	$tag_goods = array();
	$attr_sql = "select * from `{$INFO[DBPrefix]}goods_tag` where gid='" . intval($_POST['gid']) . "'";
	$Query_attr    = $DB->query($attr_sql);
	$ic=0;
	$Num_attr   = $DB->num_rows($Query_attr);
	while($Rs_arrt=$DB->fetch_array($Query_attr)){
			$tag_goods[$ic]=$Rs_arrt['tagid'];
			$ic++;
	}
	if (is_array($tag_goods)){
		foreach($tag_goods as $v=>$k){
			if (is_array($_POST['tags'])){
				if (!in_array($k,$_POST['tags'])){
					$sql = "delete from `{$INFO[DBPrefix]}goods_tag` where tagid='" . intval($k) . "' and gid='" . intval($_POST['gid']) . "'";
					$DB->query($sql);
					$sql_u = "update `{$INFO[DBPrefix]}tag` set goodscount=goodscount-1,count=count-1 where tagid='" . intval($k) . "'";
					$DB->query($sql_u);
				}
			}
		}
	}
	
	if (is_array($_POST['tags'])){
		foreach($_POST['tags'] as $v => $k){
			$attr_sql = "select * from `{$INFO[DBPrefix]}goods_tag` where tagid='" . intval($k) . "' and gid='" . intval($_POST['gid']) . "'";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr      = $DB->num_rows($Query_attr);
			if ($Num_attr<=0){
				$sql = "insert into `{$INFO[DBPrefix]}goods_tag` (tagid,gid) values ('" . intval($k) . "','" . intval($_POST['gid']) . "')";
				$DB->query($sql);
				$sql_u = "update `{$INFO[DBPrefix]}tag` set goodscount=goodscount+1,count=count+1 where tagid='" . intval($k) . "'";
				$DB->query($sql_u);
			}
		}
	}else{
			$sql = "delete from `{$INFO[DBPrefix]}goods_tag` where gid='" . intval($_POST['gid']) . "'";
			$DB->query($sql);
			$sql_u = "update `{$INFO[DBPrefix]}tag` set goodscount=goodscount-" . $Num_attr . ",count=count-" . $Num_attr . " where tagid='" . intval($k) . "'";
			$DB->query($sql_u);
	}



			$Go = "provider_goods_list.php";


	if ($Result_Insert)
	{
		//擴展類
		$sql_p = "delete from `{$INFO[DBPrefix]}goods_class` where gid='" . intval($_POST['gid'])  . "'";
		$DB->query($sql_p);
		for($i=1;$i<=intval($_POST['classcount']);$i++){
			if (intval($_POST['bid' . $i]) > 0){
				$sql_p = "insert into `{$INFO[DBPrefix]}goods_class` (gid,bid) values ('".intval($gid)."','" .intval($_POST['bid' . $i])  . "')";
				$Result_Insert=$DB->query($sql_p);
			}
		}
		$FUNCTIONS->setLog("編輯商品");
		if ($_POST['url']!="")
			$FUNCTIONS->header_location("http://" . $_POST['url']);
		else
			$FUNCTIONS->header_location($Go);
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}



if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Sql = "select middleimg,smallimg,bigimg,gimg from  `{$INFO[DBPrefix]}goods`  where gid=".intval($Array_bid[$i])." limit 0,1";
		$Query =  $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Rs  = $DB->fetch_array($Query);
			/**
			TAG
			**/
			$attr_sql = "select * from `{$INFO[DBPrefix]}goods_tag` where gid='" . intval($Array_bid[$i]) . "'";
			$Query_attr    = $DB->query($attr_sql);
			$Num_attr   = $DB->num_rows($Query_attr);
			$sql_u = "update `{$INFO[DBPrefix]}tag` set goodscount=goodscount-" . $Num_attr . ",count=count-" . $Num_attr . " where tagid='" . intval($Array_bid[$i]) . "'";
			$DB->query($sql_u);
			@unlink("../".$INFO['good_pic_path']."/".$Rs[middleimg]);
			@unlink("../".$INFO['good_pic_path']."/".$Rs[smallimg]);
			@unlink("../".$INFO['good_pic_path']."/".$Rs[bigimg]);
			@unlink("../".$INFO['good_pic_path']."/".$Rs[gimg]);
		}

		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods`  where gid=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除商品");
	$FUNCTIONS->header_location('provider_goods_list.php');

}



if ($_GET['Action']=='delPic' && intval($_GET['gid'])>0  && $_GET['picName']!='' ) {


	$Sql = "select middleimg,smallimg,bigimg,gimg from  `{$INFO[DBPrefix]}goods`  where gid=".intval($_GET['gid'])." limit 0,1";
	$Query =  $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Rs  = $DB->fetch_array($Query);
		@unlink("../".$INFO['good_pic_path']."/".$_GET['picName']);
		@unlink("../".$INFO['good_pic_path']."/".$Rs[middleimg]);
		@unlink("../".$INFO['good_pic_path']."/".$Rs[smallimg]);
		@unlink("../".$INFO['good_pic_path']."/".$Rs[bigimg]);
		@unlink("../".$INFO['good_pic_path']."/".$Rs[gimg]);
	}

	$DB->query("update `{$INFO[DBPrefix]}goods` set  middleimg='' ,  smallimg='' , bigimg='' ,gimg='' where gid=".intval($_GET['gid'])." limit 1");
	$FUNCTIONS->setLog("刪除商品圖片");


}






?>