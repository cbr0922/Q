<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("../../configs.inc.php");
include_once RootDocument."/Classes/orderClass.php";
$orderClass = new orderClass;
$token = $_GET['token'];
$op = $_GET['op'];
$fanid = $_GET['fanid'];
$num = intval($_GET['num']);
if ($num==0)
	$num = 12;
$return_array=array();

if($token == $INFO['token']){
	switch ($op){
		case "index";
			$shopid = checkFanid($fanid);
			if ($fanid == ""){
				$return_array['type'] = "FanIdErrorException";
				$return_array['message'] = "Null FanId";
			}elseif($shopid ==0){
				$return_array['type'] = "FanIdErrorException";
				$return_array['message'] = "FanId Not Found";	
			}
			$Query = $DB->query("select * from `{$INFO[DBPrefix]}shopinfo` where sid=".intval($shopid)." and state=1 limit 0,1");
			$Num   = $DB->num_rows($Query);
			
			if ($Num>0){
			  $Result= $DB->fetch_array($Query);
			  $return_array['Name']       =  $Result['shopname'];
			  $return_array['LogoImagePath']     =  $INFO['site_url'] . "/UploadFile/ShopPic/" . $Result['shoppic'];
			}
			
			$i = 0;
			$class_Sql = "select * from `{$INFO[DBPrefix]}shopgoodsclass` where top_id=0 and shopid='" . $shopid . "'";
			$class_Query  = $DB->query($class_Sql);
			$class_Num   = $DB->num_rows($class_Query);
			if ($class_Num>0){
				while($class_Rs =  $DB->fetch_array($class_Query)){
					$return_array['Cates'][$i]['Name']	= fomatstring($class_Rs['classname']);
					$return_array['Cates'][$i]['Id']	= $class_Rs['sgcid'];
					$i++;
				}
			}else{
				$return_array['Cates']=array();
			}
			

		break;
		case "hot";
			$shopid = checkFanid($fanid);
			if ($fanid == ""){
				$return_array['type'] = "FanIdErrorException";
				$return_array['message'] = "Null FanId";
			}elseif($shopid ==0){
				$return_array['type'] = "FanIdErrorException";
				$return_array['message'] = "FanId Not Found";	
			}
			
			$Sql = "select g.view_num,g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage,g.salename_color  from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}shopbclass` b on ( g.bid=b.bid) where g.ifpub=1 and g.ifbonus!=1 and g.ifhot=1 and g.ifpresent!=1 and g.ifxy!=1 and b.catiffb=1 and g.ifchange!=1 and shopid='" . $shopid . "' order by RAND() limit 0,4";
			$Query =    $DB->query($Sql);
			$Num   = $DB->num_rows($Query);
			$i=0;
			$num=0;
			while ($Hot_Rs=$DB->fetch_array($Query)) {
				$return_array[$i][Id] = $Hot_Rs['gid'];
				$return_array[$i][Title] = fomatstring($Hot_Rs['goodsname']);
				$return_array[$i][Image] = $INFO['site_url'] . "/UploadFile/GoodPic/" . $Hot_Rs['smallimg'];
				$return_array[$i][Price] = $Hot_Rs['pricedesc'];
				$return_array[$i][Desc] = fomatstring($Hot_Rs['intro']);
				if ($Hot_Rs['iftimesale']==1 && $Hot_Rs['timesale_starttime']<=time() && $Hot_Rs['timesale_endtime']>=time()){
					$return_array[$i][Price]  = $Hot_Rs['saleoffprice'];
				}
				$i++;
			}
		break;
		case "indexprods";
			$shopid = checkFanid($fanid);
			if ($fanid == ""){
				$return_array['type'] = "FanIdErrorException";
				$return_array['message'] = "Null FanId";
			}elseif($shopid ==0){
				$return_array['type'] = "FanIdErrorException";
				$return_array['message'] = "FanId Not Found";	
			}
			$Sql = "select g.view_num,g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.pricedesc,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage,g.salename_color  from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}shopbclass` b on ( g.bid=b.bid) where g.ifpub=1 and g.ifbonus!=1 and g.ifrecommend=1 and g.ifpresent!=1 and g.ifxy!=1 and b.catiffb=1 and g.ifchange!=1 and shopid='" . $shopid . "' limit 0," . $num;
			$Query =    $DB->query($Sql);
			$Num   = $DB->num_rows($Query);
			$i=0;
			$num=0;
			while ($Hot_Rs=$DB->fetch_array($Query)) {
				$return_array[$i][Id] = $Hot_Rs['gid'];
				$return_array[$i][Title] = fomatstring($Hot_Rs['goodsname']);
				$return_array[$i][Image] = $INFO['site_url'] . "/UploadFile/GoodPic/" . $Hot_Rs['smallimg'];
				$return_array[$i][Price] = $Hot_Rs['pricedesc'];
				$return_array[$i][Desc] = fomatstring($Hot_Rs['intro']);
				$i++;
			}
		break;
		case "proddetail";
			if(intval($_GET['id'])>0){
				$Sql =   "select b.attr,g.goodsname,g.brand_id,g.view_num,g.video_url,g.nocarriage,g.keywords,g.pricedesc,g.bn,g.ifgl,g.bid,g.unit,g.intro,g.price,g.point,g.body,g.middleimg,g.smallimg,g.bigimg,g.gimg,g.goodattr,g.good_color,g.good_size,g.ifrecommend,g.ifspecial,g.ifalarm,g.storage,g.alarmnum,g.ifbonus,g.ifhot,g.provider_id,g.ifjs,g.js_begtime,g.js_endtime,g.js_price,g.js_totalnum,p.provider_name,br.brandname,br.logopic,g.if_monthprice,g.cap_des,g.ifxygoods,g.xycount,g.sale_name,g.ifsaleoff,g.timesale_starttime,g.timesale_endtime,g.iftimesale,g.saleoff_starttime,g.saleoff_endtime,g.saleoffprice,g.jscount,g.sale_subject,g.ifsales,g.trans_type,g.trans_special,g.goodsno,g.salename_color,g.memberprice,g.combipoint,g.iftogether,g.alarmcontent,g.if_monthprice  from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}shopbclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)   where  b.catiffb=1 and g.ifpub=1  and g.ifxy!=1 and g.ifpresent!=1 and g.gid=" . intval($_GET['id']) . " and g.ifchange!=1 limit 0,1";
				$Query   = $DB->query($Sql);
				$Num     = $DB->num_rows($Query);
				if ($Num>0){
					$return_array['Id'] = intval($_GET['id']);
					$Result_goods = $DB->fetch_array($Query);
					$return_array['Image'] = $INFO['site_url'] . "/UploadFile/GoodPic/" . $Result_goods['middleimg'];
					$return_array['Title'] = fomatstring($Result_goods['goodsname']);
					$return_array['Desc'] = fomatstring($Result_goods['body']);
					$return_array['SubDesc'] = fomatstring($Result_goods['intro']);
					$return_array['SpecDesc'] = fomatstring($Result_goods['alarmcontent']);
					$return_array['Price'] = $Result_goods['pricedesc'];
					$return_array['Count'] = $Result_goods['storage'];
					$Query   = $DB->query("select title,info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=10 limit 0,1");
					$Num     = $DB->num_rows($Query);
					if ($Num>0){
						$Result_Article = $DB->fetch_array($Query);
						$Content = fomatstring($Result_Article['info_content']);
						$Title = fomatstring($Result_Article['title']);
					}
					$return_array['ServiceDesc'] = $Content;
					$Sql_pic    = "select goodpic_name,goodpic_title from `{$INFO[DBPrefix]}good_pic` where good_id=".intval($_GET['id']);
					$Query_pic  = $DB->query($Sql_pic);
					$Num_pic    = $DB->num_rows($Query_pic);
					$i = 0;
					while ($Result_pic = $DB->fetch_array($Query_pic))  {
						$return_array['Images'][$i] =   $Result_pic['goodpic_name'];
						$i++;
					}
				}else{
					$return_array['type'] = "IdNullException";
					$return_array['message'] = "Id Not Found";		
				}
			}else{
				$return_array['type'] = "IdNullException";
				$return_array['message'] = "Null Id";		
			}
		break;
		case "cateprods";
			$cateid = intval($_GET['cateid']);
			$pnum = intval($_GET['pnum']);
			$pindex = intval($_GET['pindex']);
			if($cateid == 0 ){
				$return_array['type'] = "CateIdErrorException";
				$return_array['message'] = "Null CateId";	
			}else{
				if($pnum == 0 ){
					$return_array['type'] = "Product Number NullException";
					$return_array['message'] = "Null Product Number";	
				}else{
					if($pindex == 0 ){
						$return_array['type'] = "Page Index NullException";
						$return_array['message'] = "Null Page Index";	
					}else{
						$Query = $DB->query("select * from `{$INFO[DBPrefix]}shopgoodsclass` where sgcid=".intval($cateid)." limit 0,1 ");
						$Num   = $DB->num_rows($Query);
						if ($Num>0){
							
							$start =( $pindex-1)*$pnum;
							$end = $pindex*$pnum+1;
							 $Sql        = "select g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.ifalarm,g.storage,g.alarmnum,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.salename_color   from `{$INFO[DBPrefix]}goods` g where g.shopclass=".$cateid." and g.ifpub=1   order by g.goodorder asc,g.idate desc  limit " . $start . "," . $end;
							$Query  = $DB->query($Sql);
							 $Num   = $DB->num_rows($Query);
							$i = 0;
							if ($Num >0){
								while ( $Rs = $DB->fetch_array($Query)){
									$return_array[$i]['Id'] = $Rs['gid'];
									$return_array[$i]['Image'] = $INFO['site_url'] . "/UploadFile/GoodPic/" . $Rs['smallimg'];
									$return_array[$i]['Title'] = fomatstring($Rs['goodsname']);
									$return_array[$i]['Desc'] = fomatstring($Rs['intro']);
									$return_array[$i]['Price'] = $Rs['pricedesc'];
									$i++;
								}
							}
						}else{
							$return_array['type'] = "CateIdErrorException";
							$return_array['message'] = "CateId Not Found";	
						}
					}
				}	
			}
		break;
		case "cateprodcount";
			$cateid = intval($_GET['cateid']);
			if($cateid == 0 ){
				$return_array['type'] = "CateIdErrorException";
				$return_array['message'] = "Null CateId";	
			}else{
				$Query = $DB->query("select * from `{$INFO[DBPrefix]}shopbclass` where bid=".intval($cateid)." limit 0,1 ");
				$Num   = $DB->num_rows($Query);
				if ($Num>0){
					$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class($cateid);
					$Next_ArrayClass  = explode(",",$Next_ArrayClass);
					$Array_class      = array_unique($Next_ArrayClass);
					
					foreach ($Array_class as $k=>$v){
						$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
						$Add2 .= trim($v)!="" && intval($v)>0 ? " or gc.bid=".$v." " : "";
					}
					$gid_array = array();
					$extendsql = "select gc.gid from `{$INFO[DBPrefix]}goods_class` as gc where gc.bid ='" . intval($cateid) . "' " . $Add2 . "";
					$extend_query  = $DB->query($extendsql);
					$ei = 0;
					while($extend_rs = $DB->fetch_array($extend_query)){
						$gid_array[$ei] = $extend_rs['gid'];
						$ei++;
					}
					if (is_array($gid_array) && count($gid_array)>0){
						$gid_str = implode(",",$gid_array);
						$gid_sql_str = " or g.gid in (" . $gid_str . ")";
					}
					$Sql        = "select count(*) as counts   from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}shopbclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' and g.ifbonus!='1' and g.shopid=0 and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1  and (g.bid=".$cateid." ".$Add." " . $gid_sql_str . ")   ";
					$Query  = $DB->query($Sql);
					$Rs = $DB->fetch_array($Query);
					echo $Rs['counts'];
					exit;
				}else{
					$return_array['type'] = "CateIdErrorException";
					$return_array['message'] = "CateId Not Found";	
				}
			}
		break;
		case "orderlite";
			$orderid = $_GET['orderid'];
			if($orderid != ""){
				$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_serial='".$orderid."'  limit 0,1");
				$Num       = $DB->num_rows($Query);
				if ( $Num <= 0 ){
					$return_array['type'] = "OrderdNullException";
					$return_array['message'] = "OrderId Not Found";
				}else{
					$Result    = $DB->fetch_array($Query);
					$return_array['OrderId']	 = $Result['order_serial'];
					$return_array['MallgicOrderId']	 = $Result['order_id'];
					$return_array['DealDate']	 = date("Y/m/d",$Result['createtime']);
					$return_array['ShipDate']	 = $Result['sendtime'];
					$return_array['Total']	 = $Result['discount_totalPrices']+$Result['transport_price'];
					$return_array['Status'] = $orderClass->getOrderState(intval($Result['transport_state']),3);
				}
			}else{
				$return_array['type'] = "OrderdNullException";
				$return_array['message'] = "Null OrderId";		
			}
		break;
		case "orderdetail";
			$orderid = $_GET['orderid'];
			if($orderid != ""){
				$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_serial='".$orderid."'  limit 0,1");
				$Num       = $DB->num_rows($Query);
				if ( $Num <= 0 ){
					$return_array['type'] = "OrderdNullException";
					$return_array['message'] = "OrderId Not Found";
				}else{
					$Result    = $DB->fetch_array($Query);
					$return_array['OrderId']	 = $Result['order_serial'];
					$return_array['MallgicOrderId']	 = $Result['order_id'];
					$return_array['DealDate']	 = date("Y/m/d",$Result['createtime']);
					$return_array['ShipDate']	 = $Result['sendtime'];
					$return_array['Total']	 = $Result['discount_totalPrices'];
					$return_array['OrderStatus'] = $orderClass->getOrderState(intval($Result['order_state']),1) . "," .$orderClass->getOrderState(intval($Result['pay_state']),2) . "," .$orderClass->getOrderState(intval($Result['transport_state']),3);
					$return_array['PaymentWay']	 = fomatstring($Result['paymentname']);
					$return_array['ShipFee']	 = $Result['transport_price'];
					$return_array['RecName']	 = fomatstring($Result['receiver_name']);
					$return_array['RecAddr']	 = fomatstring($Result['receiver_address']);
					$return_array['Email']	 = fomatstring($Result['receiver_email']);
					$return_array['Mobile']	 = fomatstring($Result['receiver_mobile']);
					$return_array['Tel']	 =fomatstring( $Result['receiver_tele']);
					$return_array['InvoiceTitle']	 = fomatstring($Result['invoiceform']);
					$return_array['InvoiceNo']	 = $Result['invoice_code'];
					$return_array['CompanyNo']	 = $Result['invoice_num'];
					$return_array['IsInvoice']	 = intval($Result['ifinvoice'])==0?false:true;
					$detail_Sql = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . intval($Result['order_id']) . "'";
					$detail_Query = $DB->query($detail_Sql);
					$i = 0;
					while($detail_Rs = $DB->fetch_array($detail_Query)){
						$return_array['OrderProdDetails'][$i]['Name'] = fomatstring($detail_Rs['goodsname']);
						$return_array['OrderProdDetails'][$i]['Qvt'] = $detail_Rs['goodscount'];
						$return_array['OrderProdDetails'][$i]['ShipDate'] = $Result['sendtime'];
						$return_array['OrderProdDetails'][$i]['ShipWay'] = fomatstring($Result['deliveryname']);
						$return_array['OrderProdDetails'][$i]['ShipUrl'] = "";
						$return_array['OrderProdDetails'][$i]['ShipNum'] = $Result['piaocode'];
						$i++;
					}
					
				}
			}else{
				$return_array['type'] = "OrderdNullException";
				$return_array['message'] = "Null OrderId";		
			}
		break;
		case "ordercancel";
			$orderid = $_GET['orderid'];
			if($orderid != ""){
				$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_serial='".$orderid."'  limit 0,1");
				$Num       = $DB->num_rows($Query);
				if ( $Num <= 0 ){
					$return_array['type'] = "OrderdNullException";
					$return_array['message'] = "OrderId Not Found";
				}else{
					$Result    = $DB->fetch_array($Query);
					$id = $Result['order_id'];
					$Pay_point      = $Result['bonuspoint']+$Result['totalbonuspoint'];
					$DB->query(" insert into `{$INFO[DBPrefix]}order_userback` (user_id,order_id,userback_type,user_say,userback_idate) values ('0','".$id."','1','','".time()."')");
					$DB->query("update `{$INFO[DBPrefix]}order_table` set order_state=2 where order_id=".intval($id));
					$DB->query("update `{$INFO[DBPrefix]}order_detail` set detail_order_state=2 where order_id=".intval($id));
					echo "true";
					exit;
				}
			}
		break;
		
		default:
			echo "Success";exit;
	}
}elseif($token==""){
	$return_array['type'] = "TokenErrorException";
	$return_array['message'] = "Null Token";
}else{
	$return_array['type'] = "TokenErrorException";
	$return_array['message'] = "Token Error";
}


echo JSON($return_array);

function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) arrayRecursive($array[$key], $function, $apply_to_keys_also);
        else $array[$key] = $function($value);

        if ($apply_to_keys_also && is_string($key)) { $new_key = $function($key); if ($new_key != $key) { $array[$new_key] = $array[$key]; unset($array[$key]); } }
    }
}
function JSON($array) {
    arrayRecursive($array, 'urlencode', true);
    $json = jsonEncode($array); // Лђеп $json = php_json_encode($array);
    return urldecode($json);
}

function jsonEncode($var) {
    if (function_exists('json_encode')) {
        return json_encode($var);
    } else {
        switch (gettype($var)) {
            case 'boolean':
                return $var ? 'true' : 'false'; // Lowercase necessary!
            case 'integer':
            case 'double':
                return $var;
            case 'resource':
            case 'string':
                return '"'. str_replace(array("\r", "\n", "<", ">", "&"),
                    array('\r', '\n', '\x3c', '\x3e', '\x26'),
                    addslashes($var)) .'"';
            case 'array':
                // Arrays in JSON can't be associative. If the array is empty or if it
                // has sequential whole number keys starting with 0, it's not associative
                // so we can go ahead and convert it as an array.
                if (empty ($var) || array_keys($var) === range(0, sizeof($var) - 1)) {
                    $output = array();
                    foreach ($var as $v) {
                        $output[] = jsonEncode($v);
                    }
                    return '[ '. implode(', ', $output) .' ]';
                }
                // Otherwise, fall through to convert the array as an object.
            case 'object':
                $output = array();
                foreach ($var as $k => $v) {
                    $output[] = jsonEncode(strval($k)) .': '. jsonEncode($v);
                }
                return '{ '. implode(', ', $output) .' }';
            default:
                return 'null';
        }
    }
}

function checkFanid($fanid){
	global $DB,$INFO;
	$sid = 0;
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}shopinfo` where fanid=".intval($fanid)." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if($Num>0){
		$Result= $DB->fetch_array($Query);	
		$sid = intval($Result['sid']);
	}
	return $sid;
}

function fomatstring($str){
	$str = str_replace("\"","",$str);
	return $str;
}

?>
