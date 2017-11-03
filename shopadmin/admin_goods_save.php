<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;
include("product.class.php");
$PRODUCT = new PRODUCT();
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
	if(strlen($no)<6)
		return str_repeat("0",6-strlen($no)) . $no;
}
$postedValue = $postedValue!="" ? $postedValue : $postArray ;

$Js_price = trim($_POST['oneprice'])."||".trim($_POST['twoprice'])."||".trim($_POST['threeprice'])."||".trim($_POST['fourprice'])."||".trim($_POST['fiveprice']);
$Js_count = trim($_POST['onecount'])."||".trim($_POST['twocount'])."||".trim($_POST['threecount'])."||".trim($_POST['fourcount'])."||".trim($_POST['fivecount']);

 $js_begtime  = trim($_POST['begtime'])!=''  ?  trim($_POST['begtime'])  :  '0000-00-00' ;
 $js_endtime  = trim($_POST['endtime'])!=''  ?  trim($_POST['endtime'])  :  '0000-00-00' ;

if (trim($_POST['saleoff_startdate'])!=''){
	$date_array = explode("-",trim($_POST['saleoff_startdate']));
	$saleoff_startdate = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['start_h']),intval($_POST['start_i']),0);
}
if (trim($_POST['saleoff_enddate'])!=''){
	$date_array = explode("-",trim($_POST['saleoff_enddate']));
	$saleoff_enddate = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['end_h']),intval($_POST['end_i']),0);
}

if (trim($_POST['appoint_startdate'])!=''){
	$date_array = explode("-",trim($_POST['appoint_startdate']));
	$appoint_startdate = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['appoint_start_h']),intval($_POST['appoint_start_i']),0);
}
if (trim($_POST['appoint_enddate'])!=''){
	$date_array = explode("-",trim($_POST['appoint_enddate']));
	$appoint_enddate = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['appoint_end_h']),intval($_POST['appoint_end_i']),0);
}

if (trim($_POST['timesale_starttime'])!=''){
	$date_array = explode("-",trim($_POST['timesale_starttime']));
	$timesale_starttime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['timesalestart_h']),intval($_POST['timesalestart_i']),0);
}
if (trim($_POST['timesale_endtime'])!=''){
	$date_array = explode("-",trim($_POST['timesale_endtime']));
	$timesale_endtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['timesaleend_h']),intval($_POST['timesaleend_i']),0);
}
if (trim($_POST['pubstarttime'])!=''){
	$date_array = explode("-",trim($_POST['pubstarttime']));
	$pubstarttime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['pubstart_h']),intval($_POST['pubstart_i']),0);
}
if (trim($_POST['pubendtime'])!=''){
	$date_array = explode("-",trim($_POST['pubendtime']));
	$pubendtime = $TimeClass->ForGetUnixTime($date_array[0],$date_array[1],$date_array[2],intval($_POST['pubend_h']),intval($_POST['pubend_i']),0);
}
if ($_POST['Action']=='newInsert' ) {
	$Query_b = $DB->query("select max(goodsno) as maxno from `{$INFO[DBPrefix]}goods`");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = $Result_b['maxno'];
	$goodsno = num(intval($maxno)+1);
	//分類
	$class_banner = array();
	$list = 0;
	$PRODUCT->getTopBidList(intval($_POST['bid']));
	$bid_array[0] = $class_banner;
	
	$extendbid = json_encode($bid_array);

	$db_string = $DB->compile_db_insert_string( array (
	'bid'                => intval($_POST['bid']),
	'pricedesc'          => intval($_POST['pricedesc']),
	'goodsname'          => trim($_POST['goodsname']),
	'bn'                 => trim($_POST['bn']),
	'idate'              => time(),
	'transtype'              => intval($_POST['transtype']),
	'ifmood'              => 1,
	'ifrecommend'              => 0,	
	'goodsno'                =>$goodsno,
	'iftogether'              => intval(trim($_POST['iftogether'])),
	'ttype'              => intval($_POST['ttype']),
	'extendbid'              => $extendbid,
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
	$gid = mysql_insert_id();	

	if ($Result_Insert)
	{
		
		$FUNCTIONS->setLog("新增商品");
		
        $FUNCTIONS->header_location("admin_goods.php?Action=Modi&gid=".$gid."&url=admin_goods_list.php");

	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}
if ($_POST['Action']=='Insert' ) {
	$Query_b = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($_POST['bid'])." limit 0,1");
	$Result_b= $DB->fetch_array($Query_b);
	$gain    =  $Result_b['gain'];
	if($_POST['cost'] >0){
	if($_POST['cost'] > $_POST['pricedesc']){
		$FUNCTIONS->sorry_back('','低於成本',"admin_goods_list.php");
	}
	}
	/*
	if ($FUNCTIONS->controlGoodsNum() == false) {
		$FUNCTIONS->sorry_back('',$Basic_Command['Pub_Error'],"admin_goods_list.php");
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

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($_POST['provider_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	$Result= $DB->fetch_array($Query);
	$providerno           =  $Result['providerno'];
	if (intval($providerno)<=0){
		$providerno = "000000";
	}
	$Query_b = $DB->query("select bn from `{$INFO[DBPrefix]}goods` where bn like '" . $providerno . "%' and shopid=0 order by gid desc limit 0,1");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = substr($Result_b['bn'],6);
	$bn = num(intval($maxno)+1);
	if(intval($_POST['if_monthprice'])==1){
		if(is_array($_POST['month'])){
			$month = implode(",",$_POST['month']);
		}
	}


	$bn = $providerno.$bn;
	//将得到的字符串由数组中得到STRING值。

	$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['img']['name'],$_FILES['img']['tmp_name'],$ArrayPic,"../".$INFO['good_pic_path']);

	if ($_POST['ifshop']==1)
		$ifpub = intval($_POST['ifpub']);
	else
		$ifpub = 0;
	//------------篩選---------------
	//分類
	$class_banner = array();
	$list = 0;
	$PRODUCT->getTopBidList(intval($_POST['bid']));
	$bid_array[0] = $class_banner;
	//擴展類
	for($i=1;$i<=intval($_POST['classcount']);$i++){
		if (intval($_POST['bid' . $i]) > 0){
			$class_banner = array();
			$list = 0;
			$PRODUCT->getTopBidList(intval($_POST['bid' . $i]));
			$bid_array[$i] = $class_banner;
		}
	}
	$extendbid = json_encode($bid_array);
	$attributeclass= json_encode($_POST['attribute']);
	//分類
	$class_banner = array();
	$list = 0;
	$PRODUCT->getTopBrandBidList(intval($_POST['brand_bid']),0);
	$brandbid_array[0] = $class_banner;
	//擴展類
	for($i=1;$i<=intval($_POST['brandclasscount']);$i++){
		if (intval($_POST['brand_bid' . $i]) > 0){
			$class_banner = array();
			$list = 0;
			$PRODUCT->getTopBrandBidList(intval($_POST['brand_bid' . $i]),0);
			$brandbid_array[$i] = $class_banner;
		}
	}
	$brandbids = json_encode($brandbid_array);
	$salecost=intval(trim($_POST['salecost']))==0?$cost:intval(trim($_POST['salecost']));
	//------------END篩選---------------
	$db_string = $DB->compile_db_insert_string( array (
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
	'ifpub'              => $ifpub,
	'nocarriage'         => intval($_POST['nocarriage']),
	'ifrecommend'        => intval($_POST['ifrecommend']),
	'ifspecial'          => intval($_POST['ifspecial']),
	'subject_id'         => trim(@implode(",",$_POST['subject_id'])),
	'ifhot'              => intval($_POST['ifhot']),
	'ifjs'               => intval($_POST['ifjs']),
	'video_url'          => trim($_POST['video_url']),
	'ifgl'               => intval($_POST['ifgl']),
	'js_begtime'         => $js_begtime,
	'js_endtime'         => $js_endtime,
	'js_price'           => $Js_price,
	'js_totalnum'        => intval($_POST['Js_totalnum']),
	'provider_id'        => intval($_POST['provider_id']),
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
	'iftogether'              => intval(trim($_POST['iftogether'])),
	'ifbelate'      					=>intval($_POST['ifbelate']),
	'guojima'              => trim($_POST['guojima']),
	'xinghao'              => trim($_POST['xinghao']),
	'weight'              => trim($_POST['weight']),
	'shopclass'              => intval(trim($_POST['shopclass'])),
	'shopid'              => intval(trim($_POST['shopid'])),
	'salecost'              => $salecost,
	'if_monthprice'      =>  intval($_POST['if_monthprice']),
	'month'           => $month,
	'ifappoint'              => intval(trim($_POST['ifappoint'])),
	'appoint_send'              => intval(trim($_POST['appoint_send'.intval(trim($_POST['appoint_sendtype']))])),
	'appoint_sendtype'              => intval(trim($_POST['appoint_sendtype'])),
	'appoint_starttime'      => $appoint_startdate,
	'appoint_endtime'      => $appoint_enddate,
	'chandi'              => trim($_POST['chandi']),
	'ERP'              => trim($_POST['ERP']),
	'subjectcontent'              => trim($_POST['subjectcontent']),
	'ifpack'              => intval(trim($_POST['ifpack'])),
	'ttype'              => intval($_POST['ttype']),
	'saleoffcount'              => intval($_POST['saleoffcount']),
	'pubstarttime'              => $pubstarttime,
	'pubendtime'              => $pubendtime,
	'ifgoodspresent'              => intval($_POST['ifgoodspresent']),
	'extendbid'              => $extendbid,
	'attributeclass'              => $attributeclass,
	'ifshui'              => intval($_POST['ifshui']),
	'freetran'              => intval($_POST['freetran']),
	'presentcount'              => intval($_POST['presentcount']),
	'brand_bid'              => intval($_POST['brand_bid']),
	'brandbids'              => $brandbids,	
	'orgno'              => trim($_POST['orgno']),
	'department'              => trim($_POST['department']),
	'salestartdate'              => trim($_POST['salestartdate']),
	'saleenddate'              => trim($_POST['saleenddate']),
	'salecontent'              => trim($_POST['salecontent']),
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
		if ($_POST['ifshop']==1){
			$FUNCTIONS->header_location('admin_shopgoods_list.php');
		}
		else{
      $sql_v = "goodsname='".trim($_POST['goodsname'])."' AND ";
      $sql_v .= "bid=".intval($_POST['bid'])." AND ";
      $sql_v .= "bn='".trim($_POST['bn'])."' AND ";
      $sql_v .= "pricedesc=".intval($_POST['pricedesc']);
      $Query = $DB->query("select gid from `{$INFO[DBPrefix]}goods` where ".$sql_v." limit 0,1");

      $focus_good;
    	$Num_attr   = $DB->num_rows($Query);
    	while($Rs_arrt=$DB->fetch_array($Query)){
    			$focus_good=$Rs_arrt['gid'];
    	}

      $FUNCTIONS->header_location("admin_goods.php?Action=Modi&gid=".$focus_good."&url=admin_goods_list.php");
    }
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}





if ($_POST['Action']=='Copy' ) {
	$ArrayPic = array(trim($_POST['gimg']),trim($_POST['smallimg']),$_POST['bigimg'],$_POST['middleimg']);
	if ($_FILES['img']['name']!=""){
		$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['img']['name'],$_FILES['img']['tmp_name'],$ArrayPic,"../".$INFO['good_pic_path']);
	}else{
		$File_NewName = $ArrayPic;
	}
	/*
	if ($FUNCTIONS->controlGoodsNum() == false) {
		$FUNCTIONS->sorry_back('',$Basic_Command['Pub_Error'],"admin_goods_list.php");
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

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($_POST['provider_id'])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	$Result= $DB->fetch_array($Query);
	$providerno           =  $Result['providerno'];
	if (intval($providerno)<=0){
		$providerno = "000000";
	}
	$Query_b = $DB->query("select bn from `{$INFO[DBPrefix]}goods` where bn like '" . $providerno . "%' and shopid=0 order by gid desc limit 0,1");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = substr($Result_b['bn'],6);
	$bn = num(intval($maxno)+1);
	if(intval($_POST['if_monthprice'])==1){
		if(is_array($_POST['month'])){
			$month = implode(",",$_POST['month']);
		}
	}



	$bn = $providerno.$bn;

	//------------篩選---------------
	//分類
	$class_banner = array();
	$list = 0;
	$PRODUCT->getTopBidList(intval($_POST['bid']));
	$bid_array[0] = $class_banner;
	//擴展類
	for($i=1;$i<=intval($_POST['classcount']);$i++){
		if (intval($_POST['bid' . $i]) > 0){
			$class_banner = array();
			$list = 0;
			$PRODUCT->getTopBidList(intval($_POST['bid' . $i]));
			$bid_array[$i] = $class_banner;
		}
	}
	$extendbid = json_encode($bid_array);
	$attributeclass= json_encode($_POST['attribute']);
	//分類
	$class_banner = array();
	$list = 0;
	$PRODUCT->getTopBrandBidList(intval($_POST['brand_bid']),0);
	$brandbid_array[0] = $class_banner;
	//擴展類
	for($i=1;$i<=intval($_POST['brandclasscount']);$i++){
		if (intval($_POST['brand_bid' . $i]) > 0){
			$class_banner = array();
			$list = 0;
			$PRODUCT->getTopBrandBidList(intval($_POST['brand_bid' . $i]),0);
			$brandbid_array[$i] = $class_banner;
		}
	}
	$brandbids = json_encode($brandbid_array);
	$salecost=intval(trim($_POST['salecost']))==0?$cost:intval(trim($_POST['salecost']));
	if(is_array($_POST['pm']))
		$pm = implode(",",$_POST['pm']);
	//------------END篩選---------------

	//将得到的字符串由数组中得到STRING值。

	//$File_NewName = $FUNCTIONS->Upload_File_GD ($_FILES['img']['name'],$_FILES['img']['tmp_name'],trim($_POST['img']),$_POST[Thumbimg],$_POST[Markimg],"../".$INFO['good_pic_path']); 拷贝产品的时候。将不负责图片的拷贝

	$db_string = $DB->compile_db_insert_string( array (
	'bid'                => intval($_POST['bid']),
	'goodsname'          => trim($_POST['goodsname']),
	'bn'                 => trim($_POST['bn']),
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
	'ifpub'              => 0,
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
	'provider_id'        => intval($_POST['provider_id']),
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
	'iftogether'              => intval(trim($_POST['iftogether'])),
	'ifbelate'      					=>intval($_POST['ifbelate']),
	'guojima'              => trim($_POST['guojima']),
	'xinghao'              => trim($_POST['xinghao']),
	'weight'              => trim($_POST['weight']),
	'shopclass'              => intval(trim($_POST['shopclass'])),
	'shopid'              => intval(trim($_POST['shopid'])),
	'salecost'              => $salecost,
	'month'                =>$month,
	'ifappoint'              => intval(trim($_POST['ifappoint'])),
	'appoint_send'              => intval(trim($_POST['appoint_send'.intval(trim($_POST['appoint_sendtype']))])),
	'appoint_sendtype'              => intval(trim($_POST['appoint_sendtype'])),
	'appoint_starttime'      => $appoint_startdate,
	'appoint_endtime'      => $appoint_enddate,
	'chandi'              => trim($_POST['chandi']),
	'ERP'              => trim($_POST['ERP']),
	'subjectcontent'              => trim($_POST['subjectcontent']),
	'ifpack'              => intval(trim($_POST['ifpack'])),
	'ttype'              => intval($_POST['ttype']),
	'saleoffcount'              => intval($_POST['saleoffcount']),
	'pubstarttime'              => $pubstarttime,
	'pubendtime'              => $pubendtime,
	'ifgoodspresent'              => intval($_POST['ifgoodspresent']),
	'extendbid'              => $extendbid,
	'attributeclass'              => $attributeclass,
	'ifshui'              => intval($_POST['ifshui']),
	'smallimg'           => $File_NewName[1],
	'bigimg'             => $File_NewName[2],
	'gimg'               => $File_NewName[0],
	'middleimg'          => $File_NewName[3],
	'freetran'              => intval($_POST['freetran']),
	'presentcount'              => intval($_POST['presentcount']),
	'brand_bid'              => intval($_POST['brand_bid']),
	'brandbids'              => $brandbids,	
	'orgno'              => trim($_POST['orgno']),
	'pm'              => $pm,		
	'department'              => trim($_POST['department']),
	'salestartdate'              => trim($_POST['salestartdate']),
	'saleenddate'              => trim($_POST['saleenddate']),
	'salecontent'              => trim($_POST['salecontent']),
	)      );


	$Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);



	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("複製商品");
		if ($_POST['shopid']>0)
			$FUNCTIONS->header_location('admin_shopgoods_list.php');
		else
			$FUNCTIONS->header_location('admin_goods_list.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}









if ($_POST['Action']=='Update' ) {
	//print_r($_POST);
	//echo intval(trim($_POST['appoint_send']));exit;
	$Query_b = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($_POST['bid'])." limit 0,1");
	$Result_b= $DB->fetch_array($Query_b);
	$gain    =  $Result_b['gain'];
	if($_POST['cost'] >0){
	if($_POST['cost'] > $_POST['pricedesc']){
		$FUNCTIONS->sorry_back('','低於成本',"admin_goods_list.php");
	}
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
	if(intval($_POST['if_monthprice'])==1){
		if(is_array($_POST['month'])){
			$month = implode(",",$_POST['month']);
		}
	}

//exit;

	//if ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==0){
		//$ifpub = 0;
	//}else{
	//	$ifpub = intval($_POST['ifpub']);
	//}
	if ($_POST['ifshop']==1)
		$ifpub = intval($_POST['ifpub']);
	else
		$ifpub = 0;

	$salecost=intval(trim($_POST['salecost']))==0?$cost:intval(trim($_POST['salecost']));
	//------------篩選---------------
	//分類
	$class_banner = array();
	$list = 0;
	$PRODUCT->getTopBidList(intval($_POST['bid']));
	$bid_array[0] = $class_banner;
	//print_r($class_banner);exit;
	//擴展類
	for($i=1;$i<=intval($_POST['classcount']);$i++){
		if (intval($_POST['bid' . $i]) > 0){
			$class_banner = array();
			$list = 0;
			$PRODUCT->getTopBidList(intval($_POST['bid' . $i]));
			$bid_array[$i] = $class_banner;
		}
	}
	$extendbid = json_encode($bid_array);
	//分類
	$class_banner = array();
	$list = 0;
	$PRODUCT->getTopBrandBidList(intval($_POST['brand_bid']),0);
	$brandbid_array[0] = $class_banner;
	//擴展類
	for($i=1;$i<=intval($_POST['brandclasscount']);$i++){
		if (intval($_POST['brand_bid' . $i]) > 0){
			$class_banner = array();
			$list = 0;
			$PRODUCT->getTopBrandBidList(intval($_POST['brand_bid' . $i]),0);
			$brandbid_array[$i] = $class_banner;
		}
	}
	$brandbids = json_encode($brandbid_array);
	
	$attributeclass= json_encode($_POST['attribute']);
	if(is_array($_POST['pm']))
		$pm = implode(",",$_POST['pm']);
	//------------END篩選---------------

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
	'ifpub'              => $ifpub,
	'ifrecommend'        => intval($_POST['ifrecommend']),
	'nocarriage'         => intval($_POST['nocarriage']),
	'subject_id'         => trim(@implode(",",$_POST['subject_id'])),
	'video_url'          => trim($_POST['video_url']),
	'ifspecial'          => intval($_POST['ifspecial']),
	'ifhot'              => intval($_POST['ifhot']),
	'ifjs'               => intval($_POST['ifjs']),
	'ifgl'               => intval($_POST['ifgl']),
	'js_begtime'         => $js_begtime,
	'js_endtime'         => $js_endtime,
	'js_price'           => $Js_price,
	'js_totalnum'        => intval($_POST['Js_totalnum']),
	'provider_id'        => intval($_POST['provider_id']),
	'smallimg'           => $File_NewName[1],
	'bigimg'             => $File_NewName[2],
	'gimg'               => $File_NewName[0],
	'middleimg'          => $File_NewName[3],
	'body'               =>  $postedValue,
	'goodattr'           => trim($arr_char),
	'bonusnum'           => intval($_POST['bonusnum']),
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
	'ifbelate'      					=>intval($_POST['ifbelate']),
	'guojima'              => trim($_POST['guojima']),
	'xinghao'              => trim($_POST['xinghao']),
	'weight'              => trim($_POST['weight']),
	'shopclass'              => intval(trim($_POST['shopclass'])),
	'shopid'              => intval(trim($_POST['shopid'])),
	'salecost'              => $salecost,
	'ifappoint'              => intval(trim($_POST['ifappoint'])),
	'appoint_send'              => intval(trim($_POST['appoint_send'.intval(trim($_POST['appoint_sendtype']))])),
	'appoint_sendtype'              => intval(trim($_POST['appoint_sendtype'])),
	'appoint_starttime'      => $appoint_startdate,
	'appoint_endtime'      => $appoint_enddate,
	'chandi'              => trim($_POST['chandi']),
	'ERP'              => trim($_POST['ERP']),
	'subjectcontent'              => trim($_POST['subjectcontent']),
	'ifpack'              => intval(trim($_POST['ifpack'])),
	'ttype'              => intval($_POST['ttype']),
	'saleoffcount'              => intval($_POST['saleoffcount']),
	'if_monthprice'      =>  intval($_POST['if_monthprice']),
	'month'                =>$month,
	'pubstarttime'              => $pubstarttime,
	'pubendtime'              => $pubendtime,
	'ifgoodspresent'              => intval($_POST['ifgoodspresent']),
	'extendbid'              => $extendbid,
	'attributeclass'              => $attributeclass,
	'ifshui'              => intval($_POST['ifshui']),
	'freetran'              => intval($_POST['freetran']),
	'presentcount'              => intval($_POST['presentcount']),
	'brand_bid'              => intval($_POST['brand_bid']),
	'brandbids'              => $brandbids,	
	'pm'              => $pm,		
		'orgno'              => trim($_POST['orgno']),
	'department'              => trim($_POST['department']),
	'salestartdate'              => trim($_POST['salestartdate']),
	'saleenddate'              => trim($_POST['saleenddate']),
	'salecontent'              => trim($_POST['salecontent']),
	)      );

	if($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
		$sqls = ",iftogether='" . intval(trim($_POST['iftogether'])) . "',if_monthprice='" . intval(trim($_POST['if_monthprice'])) . "',month='" . $month . "',cost='" . intval(trim($_POST['cost'])) . "'";
	}
	$field_array = $FUNCTIONS->checkGoodsField(floatval($_POST['gid']),array("body"=>$postedValue,"subject_id"=>trim(@implode(",",$_POST['subject_id'])),"js_begtime"=>$js_begtime,"js_endtime"=>$js_endtime,"js_price"=>$Js_price,"smallimg"=>$File_NewName[1],"saleoff_starttime"=>$saleoff_startdate,"saleoff_endtime"=>$saleoff_enddate,"timesale_starttime"=>$timesale_starttime,"timesale_endtime"=>$timesale_endtime,"jscount"=>$Js_count,"month"=>$month),2);
	$FUNCTIONS->setGoodsAction(floatval($_POST['gid']),2,"編輯商品",$field_array);

	$Sql = "UPDATE `{$INFO[DBPrefix]}goods` SET $db_string " . $sqls . " WHERE gid=".intval($_POST['gid']);
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


	$ACT = trim($_POST['Where']);

	switch ($ACT){
		case "Goods":
			$Go = "admin_goods_list.php";
			if ($_POST['ifshop']==1)
				$Go = "admin_shopgoods_list.php";
			else
				$Go = "admin_goods_list.php";
			break;
		case "Bouns":
			$Go = "admin_bonus_list.php";
			break;
		case "Subject":
			$Go = "admin_SubjectProject_list.php";
			break;
		default:
			$Go = "admin_goods_list.php";
			if ($_POST['ifshop']==1)
				$Go = "admin_shopgoods_list.php";
			else
				$Go = "admin_goods_list.php";
			break;
	}


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
			$FUNCTIONS->header_location(urldecode($_POST['url']));
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
		$Sql = "select middleimg,smallimg,bigimg,gimg,shopid from  `{$INFO[DBPrefix]}goods`  where gid=".intval($Array_bid[$i])." limit 0,1";
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
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}attributeno`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}storage`  where goods_id=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_class`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}good_pic`  where good_id=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}attributegoods`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_change`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_detail`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_pack`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_present`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_price_cach`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_saleoffe`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_tag`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}good_comment`  where good_id=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}good_link`  where p_gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_xy`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_userlevel`  where gid=".intval($Array_bid[$i]));
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}goods_books`  where gid=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除商品");
	if ($_POST['url']!="")
			$FUNCTIONS->header_location($_POST['url']);

	if ($Rs['shopid']>0)
		$FUNCTIONS->header_location('admin_shopgoods_list.php');
	else
		$FUNCTIONS->header_location('admin_goods_list.php');

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
		$path_array = explode(".",$Rs[gimg]);
		$path_array = explode("_",$path_array[0]);
		@unlink("../".$INFO['good_pic_path']."/".$path_array[0]."_mark.jpg");
		@unlink("../".$INFO['good_pic_path']."/".$path_array[0]."_.jpg");
		@unlink("../".$INFO['good_pic_path']."/".$path_array[0].".jpg");
	}

	$DB->query("update `{$INFO[DBPrefix]}goods` set  middleimg='' ,  smallimg='' , bigimg='' ,gimg='' where gid=".intval($_GET['gid'])." limit 1");
	$FUNCTIONS->setLog("刪除商品圖片");


}


if ($_POST['act']=='FirstCheck' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);
	//print_r($Array_bid);exit;

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("update `{$INFO[DBPrefix]}goods` set checkstate=1 where gid=".intval($Array_bid[$i]) . " and (checkstate=0 or checkstate=3)") ;
	}
	$FUNCTIONS->setLog("初審商品");
	if ($_POST['url']!="")
			$FUNCTIONS->header_location($_POST['url']);

}

if ($_POST['act']=='SecondCheck' || $_POST['act']=='Check') {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("update `{$INFO[DBPrefix]}goods` set checkstate=2,ifpub=1 where gid=".intval($Array_bid[$i]) . " and (checkstate=1 or checkstate=2 or checkstate=0 or checkstate=3)") ;
	}
	$FUNCTIONS->setLog("複審商品");
	if ($_POST['url']!="")
			$FUNCTIONS->header_location($_POST['url']);

}

if ($_POST['act']=='nocheck' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("update `{$INFO[DBPrefix]}goods` set checkstate=3,ifpub=0,nocheckreason='" . $_POST['nocheckreason'] . "' where gid=".intval($Array_bid[$i]) . " and (checkstate=1 or checkstate=2 or checkstate=0)") ;
	}
	$FUNCTIONS->setLog("退審商品");
	if ($_POST['url']!="")
			$FUNCTIONS->header_location($_POST['url']);

}
if ($_GET['act']=='autosave1' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($_GET['gid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}goods` SET body = '" . $_POST['FCKeditor1'] . "' WHERE gid=".intval($_GET['gid']);
		$Result = $DB->query($Sql);
		$array = array(
			'error' => false,
			'message' => '已自動保存'
		);
	}else{
		$array = array(
			'error' => true,
			'message' => '自動保存失敗'
		);
	}

    echo stripslashes(json_encode($array));
	
}
if ($_GET['act']=='autosave2' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($_GET['gid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}goods` SET cap_des = '" . $_POST['cap_des'] . "' WHERE gid=".intval($_GET['gid']);
		$Result = $DB->query($Sql);
		$array = array(
			'error' => false,
			'message' => '已自動保存'
		);
	}else{
		$array = array(
			'error' => true,
			'message' => '自動保存失敗'
		);
	}

    echo stripslashes(json_encode($array));
	
}
if ($_GET['act']=='autosave3' ) {
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($_GET['gid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Sql = "UPDATE `{$INFO[DBPrefix]}goods` SET alarmcontent = '" . $_POST['alarmcontent'] . "' WHERE gid=".intval($_GET['gid']);
		$Result = $DB->query($Sql);
		$array = array(
			'error' => false,
			'message' => '已自動保存'
		);
	}else{
		$array = array(
			'error' => true,
			'message' => '自動保存失敗'
		);
	}

    echo stripslashes(json_encode($array));
	
}
if ($_POST['act']=='Checkprice' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);
	//print_r($Array_bid);exit;

	for ($i=0;$i<$Num_bid;$i++){
		$sql = "select * from `{$INFO[DBPrefix]}goods` where gid = '" . intval($Array_bid[$i]) . "'";
		$Query_goods    = $DB->query($sql);
		$Num_trans      = $DB->num_rows($Query_goods);
		if ($Num_trans > 0){
			$Rs = $DB->fetch_array($Query_goods);
			$goods_Sql = "select * from `{$INFO[DBPrefix]}goods_price_cach` where gid='" . $Rs['gid'] . "' and state=0 and org_price='" . $Rs['price'] . "' and org_pricedesc='" . $Rs['pricedesc'] . "' order by pubtime limit 0,1";
			$goods_Query =  $DB->query($goods_Sql);
			 $goods_Num   =  $DB->num_rows($goods_Query );
			if($goods_Num>0){
				$goods_Rs = $DB->fetch_array($goods_Query);
				$Result =  $DB->query("update `{$INFO[DBPrefix]}goods` set price='" . $goods_Rs['new_price'] . "',pricedesc='" . $goods_Rs['new_pricedesc'] . "' where gid='".intval($Array_bid[$i]) . "'") ;
				$Result =  $DB->query("update `{$INFO[DBPrefix]}goods_price_cach` set state=1,checktime='" . date("Y-m-d H:i:s") . "' where pcid='".intval($goods_Rs['pcid']) . "'") ;
			}
		}
		
	}
	$FUNCTIONS->setLog("審核商品價格");
	if ($_POST['url']!="")
			$FUNCTIONS->header_location($_POST['url']);

}
?>
