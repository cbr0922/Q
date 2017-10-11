<?php
class Cart{

	var $totalCount;    //商品總數量
	var $totalPrices;   //商品總金額
	var $discount_totalPrices;   //優惠后的商品總金額
	var $tickets;       //折價券
	var $bonus;         //紅利
	var $present;       //額滿禮
	var $transmoney;     //運費
	var $sys_trans_type; //系統設置運費方式：自定義配送，配送公式（小於多少運費多少）
	var $sys_trans;      //系統配送設置
	var $goodsGroup;    //商品分組（商品按不同運輸方式分組：一般配送，多種特殊配送）
	var $special_trans_type;//是否特殊配送方式
	var $get_key;
	var $nomal_trans_type;//一般配送方式類型
	var $invoice;//發票
	var $totalbonuspoint; //兌換的紅利總點
	var $salesubject; //多件折扣數
	var $transname;
	var $transname_content;
	var $transname_area;
	var $transname_area2;
	var $ifnotrans;
	var $saleoffinfo;
	var $combipoint;
	var $manyunfei;
	var $transname_id;
	var $store;
	var $totalBuypoint;
	var $cvsnum;
	var $cvsname;
	var $cvscate;
	var $okmap;
	var $discountsaleoff;
	var $discountprice;
	var $cart_array;
	var $flightstyle;
	var $flightid;
	var $flightno;
	var $flightdate;
	var $Departure;

	/**
	初始化類
	**/
	function Cart(){
		$this->totalCount = 0;
		$this->totalPrices = 0;
		$this->discount_totalPrices = 0;
		$this->tickets = array();
		$this->bonus = array();
		$this->transmoney = 0;
		$this->sys_trans_type = 0;
		$this->special_trans_type = 0;
		$this->nomal_trans_type = 0;
		$this->get_key = 0;
		$this->sys_trans = array();
		$this->goodsGroup = array();
		$this->invoice = array();
		$this->transname = "";
		$this->transname_area = "";
		$this->transname_area2 = "";
		$this->ifnotrans = 0;
		$this->saleoffinfo = "";
		$this->transname_content = "";
		$this->manyunfei = array();
		$this->transname_id = 0;
		$this->store = array();
		$this->totalBuypoint = 0;
		$this->cvsnum = "";
		$this->cvsname = "";
		$this->cvscate = "";
		$this->okmap = array();
		$this->discountsaleoff = array();
		$this->discountprice = array();
		$this->cart_array = array();
		$this->flightstyle = "";
		$this->flightid = "";
		$this->flightno = "";
		$this->flightdate = "";
		$this->Departure = "";
	}
	/**
	重置
	**/
	function resetCart(){
		$this->totalCount = 0;
		$this->totalPrices = 0;
		$this->discount_totalPrices = 0;
		$this->tickets = array();
		$this->bonus = array();
		$this->transmoney = 0;
		$this->sys_trans_type = 0;
		$this->special_trans_type = 0;
		$this->nomal_trans_type = 0;
		$this->get_key = 0;
		$this->sys_trans = array();
		$this->transname = "";
		$this->transname_area = "";
		$this->transname_area2 = "";
		$this->ifnotrans = 0;
		$this->saleoffinfo = "";
		$this->transname_content = "";
		$this->manyunfei = array();
		$this->transname_id = 0;
		$this->store = array();
		$this->totalBuypoint = 0;
		$this->cvsnum = "";
		$this->cvsname = "";
		$this->cvscate = "";
		$this->okmap = array();
		$this->discountsaleoff = array();
		$this->discountprice = array();
		$this->cart_array = array();
		$this->flightstyle = "";
		$this->flightid = "";
		$this->flightno = "";
		$this->flightdate = "";
		$this->Departure = "";
	}
	/**
	將商品放入購物車
	**/
	function getShoppingGoods($gid,$info,$goods_type = "",$key="",$phone=""){
		global $DB;
		global $INFO,$FUNCTIONS;
		if (intval($_SESSION['user_id'])>0){
			$this->setTotal($key);
			$member_point = $FUNCTIONS->Userpoint(intval($_SESSION['user_id']),1);
			$point = $member_point-$this->totalbonuspoint-$this->bonus['point'];
		}
		$goods_array = array();
		switch($goods_type){
			case "changegoods":
				$substr = " and g.ifchange=1";
				break;
			case "presentgoods":
				$substr = " and g.ifpresent=1";
				break;
			case "addgoods":
				$substr = " and g.ifadd=1";
				break;
			case "goodspresent":
				$substr = " and g.ifgoodspresent=1";
				break;
			case "bonusgoods":
				$substr = " and g.ifbonus=1";
				break;
			default:
				$substr = " and g.ifpresent!=1 and g.ifchange!=1 and g.ifgoodspresent!=1";
		}
		 $Query = $DB->query("select g.gid,g.ifxygoods,g.ifpack,g.ifbonus,g.bonusnum,g.addmoney,g.present_money,g.addprice,g.ttype,g.provider_id,g.iftogether,g.trans_type from `{$INFO[DBPrefix]}goods` g where gid=".intval($gid)." and g.ifpub=1 " . $substr . " limit 0,1 ");
		 $Num   = $DB->num_rows($Query);
		if ($Num>0) {
			$goods_array = array();
			$Rs = $DB->fetch_array($Query);
			if ($Rs['ifbonus']==1){
				//紅利商品判斷
			   if(intval($_SESSION['user_id']) <= 0){
				   if($phone=="phone")
				   		$url = "login.php";
					else
						$url = "../member/login_windows.php";
				  echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('請您登入後再兌換紅利商品');location.href='" . $url . "';</script>";exit;
			   }
			   if ($point<$Rs['bonusnum']){
				  echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您的紅利積點不夠兌換此商品，您目前可以兌換" . $point . "點紅利商品');history.back(-1)</script>";exit;
			   }
		   }
		   if(intval($info['ifadd'])==1){
			   //額滿加購
			   $total = $this->setTotal($key);
			   if(intval($Rs['addmoney'])>$total){
					echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您還需要買" . (intval($Rs['addmoney'])-$total) . "才可以兌換此額滿加購商品');history.back(-1)</script>";exit;
			   }
			}
			if(intval($info['ifpresent'])==1){
			   //額滿禮商品
			   $flag = true;
				foreach($this->cart_array[$key] as $k => $v){
					if ($v['ifpresent'] == 1){
						$flag = false;
					}
				}
				if ($flag == false){
					echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('每人只能選購一件額滿禮商品');location.href='shopping3.php?key=" . $_GET['key'] . "';</script>";exit;
				}
			   $total = $this->setTotal($key);
			   if(intval($Rs['present_money'])>$total){
					echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您還需要買" . (intval($Rs['present_money'])-$total) . "才可以兌換此額滿禮商品');history.back(-1)</script>";exit;
			   }
			}

			$goods_array['ifinstall'] = intval($info['ifinstall']);//是否分期
			$goods_array['gid'] = intval($gid);//商品ID
			$goods_array['saleid'] = intval($info['saleid']);
			$goods_array['type'] = $info['type'];
			$goods_array['good_size'] = $info['good_size'];
			$goods_array['good_color'] = $info['good_color'];
			$goods_array['count'] = intval($info['count'])>0?intval($info['count']):1;
			$goods_array['detail_id'] = intval($info['detail_id']);
			$goods_array['xygoods'] = $info['xygid'];
			$goods_array['ifadd'] = intval($info['ifadd']);
			$goods_array['ifpresent'] = intval($info['ifpresent']);
			$goods_array['ifchange'] = intval($info['ifchange']);
			$goods_array['ifpack'] = intval($Rs['ifpack']);
			$goods_array['changegid'] = intval($info['changegid']);
			$goods_array['ttype'] = intval($Rs['ttype']);
			$goods_array['trans_type'] = intval($Rs['trans_type']);
			$goods_array['provider_id'] = intval($Rs['provider_id']);
			$goods_array['iftogether'] = intval($Rs['iftogether']);
			 $goods_array['pregid'] = intval($info['pregid']);
			$goods_array['packgid'] = intval($info['packgid']);
			$goods_array['ifgoodspresent'] = intval($info['ifgoodspresent']);
			$goods_array['rgid'] = intval($info['rgid']);
			$goods_array['redgreen_type'] = intval($info['redgreen_type']);//1紅標2綠標
			//echo $info['greengid'];exit;
			$Query = $DB->query("select g.gid,g.goodsname,g.bn,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.ifxygoods,g.ifchange,g.iftogether,g.ifmood,g.ttype  from `{$INFO[DBPrefix]}goods_present` as gc inner join `{$INFO[DBPrefix]}goods` g on g.gid=gc.pregid where gc.gid='" . $gid . "' and g.ifgoodspresent=1 ");
			$Num   = $DB->num_rows($Query);
			if ($Num>0) {
				$pack_array = array();
				$present_array = array();
				$z = 0;
				while($Rs=$DB->fetch_array($Query)){
					$present_array[$z] = $Rs;
					$pack_array[$z] = $Rs['gid'];
					$z++;
				}
				$goods_array['goodspresentgid'] = implode(",",$pack_array);
			}
			if(intval($info['rgid'])>0 && $info['greengid']!=""){
				$goods_array['redgreen_type'] = 1;
			}
			$goods_array['present_subjectid'] = intval($info['present_subjectid']);
			if($info['ifgoodspresent']==1){
				$goods_array['xygoods_size'] = $info['xygoods_size'];
				$goods_array['xygoods_color'] = $info['xygoods_color'];
			}
			//超值任選
			if(is_array($info['xygid']) && $Rs['ifxygoods']==1){
				foreach($info['xygid'] as $xyk=>$xyv){
					$Query_xy = $DB->query("select g.gid,g.goodsname,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type  from `{$INFO[DBPrefix]}goods` g where gid=".intval($xyv)." limit 0,1 ");
					$Num_xy   = $DB->num_rows($Query_xy);
					if ($Num_xy>0){
						$Rs_xy=$DB->fetch_array($Query_xy);
						$goods_array['xygoods_des'] .= "<br>" . $Rs_xy['goodsname'];
						if ($info['xycolor'.$xyv]!=""){
								$goods_array['xygoods_des'] .= "<br>顏色：" . $_GET['xycolor'.$xyv];
								$goods_array['good_color'] .= $_GET['xycolor'.$xyv] . ",";
						}
						if ($info['xyxize'.$xyv]!=""){
								$goods_array['xygoods_des'] .= "<br>尺寸：" . $_GET['xyxize'.$xyv];
								$goods_array['good_size'] .= $_GET['xyxize'.$xyv] . ",";
						}
					}
				}
			}
			/*
			if(is_array($_GET['changegid'])) {
				foreach($_GET['changegid'] as $k=>$v){
					$goods_array['changegid'] .= $v . ",";
				}
			}
			*/
			$key = $this->setCart($goods_array,$key);

			//商品加購
			if($goods_type==""){
				if(is_array($_GET['changegid']) && $goods_type==""){
					$z = 0;
					foreach($_GET['changegid'] as $k=>$v){

						$changegoods['good_color'] = $_GET['changecolor' . $v];
						$changegoods['good_size'] = $_GET['changesize' . $v];
						 $changegoods['count'] = intval($info['count']);
						$changegoods['ifchange'] = 1;
						$changegoods['changegid'] = intval($gid);
						$changegoods['gid'] = intval($v);
						$this->getShoppingGoods($v,$changegoods,"changegoods",$key);
						$z++;
					}
				}
				$info['greengid'] = explode("|",$info['greengid']);
				//print_r($info['greengid']);exit;
			//紅綠活動
				if(intval($info['rgid'])>0 && is_array($info['greengid'])){
					$z = 0;
					foreach($info['greengid'] as $k=>$v){
						$changegoods['good_color'] = $_GET['changecolor' . $v];
						$changegoods['good_size'] = $_GET['changesize' . $v];
						$changegoods['count'] = intval($info['count']);
						$changegoods['redgreen_type'] = 2;
						$changegoods['rgid'] = intval($info['rgid']);
						$changegoods['gid'] = intval($v);
						$this->getShoppingGoods($v,$changegoods,"redgreengoods",$key);
						$z++;
					}
				}
			//組合商品
				$Sql         = "select gl.* ,g.gid,g.goodsname,g.bn,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.pricedesc as price,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.ifxygoods,g.ifchange,g.iftogether,g.ifmood,g.ttype from `{$INFO[DBPrefix]}goods_pack` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.packgid=g.gid) where gl.gid=".intval($gid)." and g.ifpub=1 order by gl.idate desc ";
				$Query       = $DB->query($Sql);
				$Num         = $DB->num_rows($Query);
				while ($Rs=$DB->fetch_array($Query)){
					$pack_array = array();
					$pack_array['packgid'] = $gid;
					$this->getShoppingGoods($Rs['gid'],$pack_array,"packgoods",$key);
				}
			//商品贈品
			/*
				$Query = $DB->query("select g.gid,g.goodsname,g.bn,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.ifxygoods,g.ifchange,g.iftogether,g.ifmood,g.ttype  from `{$INFO[DBPrefix]}goods_present` as gc inner join `{$INFO[DBPrefix]}goods` g on g.gid=gc.pregid where gc.gid='" . $gid . "' and g.ifgoodspresent=1 ");
				 $Num   = $DB->num_rows($Query);
				if ($Num>0) {
					while($Rs=$DB->fetch_array($Query)){
						$pack_array = array();
						 $pack_array['pregid'] = $gid;
						 $pack_array['count'] = intval($info['count']);
						$pack_array['ifgoodspresent'] = 1;
						$pack_array['xygoods_color'] = $goods_array['good_color'];
						$pack_array['xygoods_size'] = $goods_array['good_size'];
						$this->getShoppingGoods($Rs['gid'],$pack_array,"goodspresent",$key);
					}
				}*/
				foreach($present_array as $pk=>$pv){
						$pack_array = array();
						$pack_array['pregid'] = $gid;
						$pack_array['count'] = intval($info['count']);
						$pack_array['ifgoodspresent'] = 1;
						$pack_array['xygoods_color'] = $pv['good_color'];
						$pack_array['xygoods_size'] = $pv['good_size'];
						$this->getShoppingGoods($pv['gid'],$pack_array,"goodspresent",$key);
				}
			}
			
			
			//全館贈品
			if($INFO['allsaleoff_present']!="" && $goods_type==""){
				$Query = $DB->query("select g.gid,g.goodsname,g.bn,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.ifxygoods,g.ifchange,g.iftogether,g.ifmood,g.ttype  from `{$INFO[DBPrefix]}goods` g where g.bn='" . $INFO['allsaleoff_present'] . "' and g.ifgoodspresent=1 ");
				$Num   = $DB->num_rows($Query);
				if ($Num>0) {
					$Rs=$DB->fetch_array($Query);
					$pack_array = array();
					$pack_array['pregid'] = 0;
					$pack_array['ifgoodspresent'] = 1;
					$this->getShoppingGoods($Rs['gid'],$pack_array,"goodspresent",$key);
				}
			}
		}
	}
	/**
	將購物車信息存放到數據庫中
	**/
	function setCart($goodsitems,$pkey = ""){
		global $DB;
		global $INFO,$FUNCTIONS,$session_id;

		//額滿禮處理
		if ($goodsitems['ifpresent'] == 1){
			$goodsitems['trans_type'] = "present";
		}
		if ($pkey != ""){
			$key = $pkey;
		}else{
			//結構如下：商店ID_供應商ID_溫層配送
			$key = intval($goodsitems['shopid']);
			if(intval($goodsitems['provider_id'])>0 && intval($goodsitems['iftogether'])==0){
				$key .= "_" . intval($goodsitems['provider_id']);
			}else{
				$key .= "_" . "0";
			}

			$key .= "_" . intval($goodsitems['ttype']);
			$key .= "_" . intval($goodsitems['trans_type']);
		}
		if($gkey = $this->existCart($goodsitems)){
			$this->changeItemsCount($key,$gkey,$goodsitems['count'],1);
			return $key;
		}else{
			 $gkey = $goodsitems['gid'] . "_" . time() . "_" . rand(0,10000);
			$db_string = $DB->compile_db_insert_string( array (
				'user_id'          => $_SESSION['user_id'],
				'gid'          => $goodsitems['gid'],
				'saleid'          => $goodsitems['saleid'],
				'gtype'          => $goodsitems['type'],
				'good_size'          => $goodsitems['good_size'],
				'good_color'          => $goodsitems['good_color'],
				'gcount'          => $goodsitems['count'],
				'detail_id'          => $goodsitems['detail_id'],
				'xygid'          => $goodsitems['xygid'],
				'ifinstall'          => $goodsitems['ifinstall'],
				'changegid'          => $goodsitems['changegid'],
				'pkey'          => $key,
				'gkey'          => $gkey,
				'ifpack'          => $goodsitems['ifpack'],
				'packgid'          => $goodsitems['packgid'],
				'ifadd'          => $goodsitems['ifadd'],
				'xygoods'          => $goodsitems['xygoods'],
				'xygoods_color'          => $goodsitems['xygoods_color'],
				'xygoods_size'          => $goodsitems['xygoods_size'],
				'redgreen_type'          => $goodsitems['redgreen_type'],
				'rgid'          => $goodsitems['rgid'],
				'ifgoodspresent'          => $goodsitems['ifgoodspresent'],
				'pregid'          => $goodsitems['pregid'],
				'ifpresent'          => $goodsitems['ifpresent'],
				'session_id'   => $session_id,
				'present_subjectid'          => $goodsitems['present_subjectid'],
				'goodspresentgid'         => $goodsitems['goodspresentgid'],
			));
			  $Sql="INSERT INTO `{$INFO[DBPrefix]}shopping` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result_Insert=$DB->query($Sql);
			//赠品
			$this->goodspresent($key,$gkey,$goodsitems);
			return $key;
		}
	}
	/**
	得到數據庫中的購物車
	**/
	function getCart($key=""){
		global $DB;
		global $INFO,$FUNCTIONS,$session_id;
		include_once("product.class.php");
		$PRODUCT = new PRODUCT;
		if(intval($_SESSION['user_id'])>0){
			$subSql = " and (s.user_id='" . intval($_SESSION['user_id']) . "'";
			$subSql .= " or s.session_id='" . $session_id . "')";
		}else{
			$subSql .= " and s.session_id='" . $session_id . "'";// and g.ifbelate=0
		}
		if($key!="")
			$subSql .= " and s.pkey='" . $key . "'";

		  $Sql = "select g.*,s.* from `{$INFO[DBPrefix]}shopping` as s inner join `{$INFO[DBPrefix]}goods` as g on s.gid=g.gid where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') " . $subSql . " order by pregid asc";
		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$cart_array = array();
		$nostorage = array();
		$del_array = array();
		$zn = 0;
		$cur_array = explode("/",$_SERVER["PHP_SELF"]);
		$cur_page = $cur_array[count($cur_array)-1];
		if ($Num>0) {
			while($Rs = $DB->fetch_array($Query)){
				if($Num==1 && $Rs['ifpresent']==1){
					$this->deleteCartData($Rs['key'],$Rs['gkey']);
				}else{
				//echo $Rs['bonusnum'];
				//echo $Rs['gid']."+";
				 $storage = $PRODUCT->checkStorage($Rs['gid'],$Rs['detail_id'],$Rs['good_color'],$Rs['good_size'],1);
				//echo "|";
				if($storage<=0 && ($cur_page=="shopping.php" || $cur_page=="shopping2.php"||$cur_page=="shopping3.php"||$cur_page=="shopping4.php")){
					$nostorage[$z] = $Rs['goodsname'];
					$del_array[$z] = array($Rs['pkey'],$Rs['gkey']);
					//$this->deleItems($Rs['pkey'],$Rs['gkey']);
					//$delSql = "delete from `{$INFO[DBPrefix]}shopping` where shopping_id='" . $Rs['shopping_id'] . "'";
					//$DB->query($delSql);
					$zn++;
				}else{
					$cart_array[$Rs['pkey']][$Rs['gkey']]=$Rs;
					$cart_array[$Rs['pkey']][$Rs['gkey']]['org_price'] = $Rs['price'];
					$cart_array[$Rs['pkey']][$Rs['gkey']]['gkey'] = $Rs['gkey']; //購物車商品鍵值
					 $cart_array[$Rs['pkey']][$Rs['gkey']]['storage'] = $storage; //庫存
					$cart_array[$Rs['pkey']][$Rs['gkey']]['good_color'] = $Rs['good_color'];//顏色
					$cart_array[$Rs['pkey']][$Rs['gkey']]['good_size'] = $Rs['good_size'];//尺寸
					$cart_array[$Rs['pkey']][$Rs['gkey']]['temp_price'] = $Rs['price'];//市場價格
					$cart_array[$Rs['pkey']][$Rs['gkey']]['detail_id'] = $Rs['detail_id'];//詳細規格
					if($Rs['good_color']!="" || $Rs['good_size']!=""){
					  $goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where color='" . trim($Rs['good_color']) . "' and size='" . trim($Rs['good_size']) . "' and gid='" . $Rs['gid'] . "'";
						$goods_Query =  $DB->query($goods_Sql);
						$goods_Num   =  $DB->num_rows($goods_Query );
					  if($goods_Num>0){
					   $goods_Result=$DB->fetch_array($goods_Query);
					   $cart_array[$Rs['pkey']][$Rs['gkey']]['bn'] = $goods_Result['goodsno'];
					  }
					 }
					//紅利商品
					if ($Rs['ifbonus']==1){
						$cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_name'] = "紅利兌換[" . $Rs['bonusnum'] . "點]";
						$cart_array[$Rs['pkey']][$Rs['gkey']]['price'] = 0;
					}elseif ($Rs['ifpresent']==1){
						//額滿禮
						$cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_name'] = "額滿禮";
						$cart_array[$Rs['pkey']][$Rs['gkey']]['price'] = 0;
					}elseif ($Rs['ifgoodspresent']==1){
						//贈品
						$cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_name'] = "贈品";
						$cart_array[$Rs['pkey']][$Rs['gkey']]['price'] = 0;
					}elseif ($Rs['ifchange']==1){
						//加購
						$Sql_c         = "select gl.* ,g.goodsname,g.bn,g.smallimg,g.good_color,g.good_size,g.ifchange,g.ifalarm from `{$INFO[DBPrefix]}goods_change` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.changegid=g.gid) where gl.gid=".intval($Rs['changegid'])." and g.ifchange=1 and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and gl.changegid=".intval($Rs['gid'])." order by gl.idate desc ";
						$Query_c       = $DB->query($Sql_c);
						$Num_c         = $DB->num_rows($Query_c);
						if($Num_c>0){
							$Result_c=$DB->fetch_array($Query_c);
							$cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_name'] = "加購";
							$cart_array[$Rs['pkey']][$Rs['gkey']]['price'] = $Result_c['price'];
						}
					}else{
						//活動>整點促銷（在折扣後金額跟全館折扣一起折）>買越多（第一步金額錯誤，要到第二步金額才會正常）>館別折扣>全館折扣>會員折扣>網購價
						//設定價格
						if($Rs['ifadd']==1){
							$cart_array[$Rs['pkey']][$Rs['gkey']]['price'] = $Rs['addprice'];
						}elseif (intval($Rs['detail_id']) > 0){
							//設定詳細規格
							$Detail_Sql      = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . intval($Rs['gid']) . "' and detail_id='" . intval($Rs['detail_id']) . "' order by detail_id desc ";
							$Detail_Query    = $DB->query($Detail_Sql);
							$Detail_Num   = $DB->num_rows($Detail_Query);
							if ($Detail_Num > 0){
								$Detail_Rs = $DB->fetch_array($Detail_Query);
								//print_r($Detail_Rs);
								$cart_array[$Rs['pkey']][$Rs['gkey']]['bn'] = $Detail_Rs['detail_bn'];
								$cart_array[$Rs['pkey']][$Rs['gkey']]['detail_name'] = $Detail_Rs['detail_name'];
								$cart_array[$Rs['pkey']][$Rs['gkey']]['detail_des'] = $Detail_Rs['detail_des'];
								$cart_array[$Rs['pkey']][$Rs['gkey']]['temp_price'] = $Detail_Rs['detail_price'];
								$cart_array[$Rs['pkey']][$Rs['gkey']]['price'] = $Detail_Rs['detail_pricedes'];
								$cart_array[$Rs['pkey']][$Rs['gkey']]['cost'] = $Detail_Rs['detail_cost'];
							}
					   }else{
							$cart_array[$Rs['pkey']][$Rs['gkey']]['price'] = $Rs['pricedesc'];
					   }
					}
					if($cart_array[$Rs['pkey']][$Rs['gkey']]['storage']<$Rs['gcount']){
						$cart_array[$Rs['pkey']][$Rs['gkey']]['count'] = $cart_array[$Rs['pkey']][$Rs['gkey']]['storage'];
					}
					elseif(intval($INFO['buy_product_max_num'])<$Rs['gcount'] && intval($INFO['buy_product_max_num'])>0){
						$cart_array[$Rs['pkey']][$Rs['gkey']]['count'] = intval($INFO['buy_product_max_num']);
					}
					else{
						$cart_array[$Rs['pkey']][$Rs['gkey']]['count'] = $Rs['gcount'];
					}
					$cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_state'] = 0;
					$cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_type'] = "";
					//$cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_name'] = "";
					if($Rs['ifbonus']==1 || $Rs['ifpresent']==1 || $Rs['Js_price']==1 || $Rs['ifchange']==1 || $Rs['ifadd']==1 || $Rs['ifgoodspresent']==1)
						$cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_state'] = -1;

				}
			}
			}
			//print_r($cart_array);


		}
		$ifshan = 1;
		$this->cart_array = $cart_array;
		if($key!=""){
			$this->setCartPrice($key);
		}else{
			foreach($this->cart_array as $keys=>$vv){
				$this->setCartPrice($keys);
				foreach($vv as $k=>$v){
					if($v['ifgoodspresent']!=1){
						$ifshan =0;	
					}
				}
				if($ifshan ==1){
					$this->clearGoods($keys);	
					unset($this->cart_array[$keys]);	
				}
			}
		}
		if($zn>0){
			foreach($del_array as $dek=>$dev){
				//$del_array[$z] = array($Rs['pkey'],$Rs['gkey']);
				$this->deleItems($dev[0],$dev[1]);
			}
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('您購買的商品：" . implode(",",$nostorage) . ",已售完!');location.href='shopping.php'</script>";exit;
		}
		//print_r($this->cart_array);
		if($key!=""){
			return $this->cart_array[$key];
		}else{
			return $this->cart_array;
		}
	}

	/**
	設定價格
	**/
	function setCartPrice($key){
		global $DB;
		global $INFO,$FUNCTIONS,$session_id;
		$cart_array = $this->cart_array[$key];
		$discount_array = array();
		$saleoff_array = array();
		$saleoff_count_array = array();
		$red_array = array();
		$gi = 0;
		//刪除所有活動贈品
		$hidecount = 0;
		foreach($cart_array as $k=>$v){
			if($v['present_subjectid']>0 && $v['ifgoodspresent']==1){
				//$this->deleItems($key,$v['gkey']);
				unset($this->cart_array[$key][$v['gkey']]);
				$this->deleteCartData($key,$v['gkey']);
			}
		}
		foreach($cart_array as $k=>$v){
			//新會員促銷價格
			if(intval($_SESSION['user_id'])>0 && $v['promotion_state']==0)
				$ifnew = $FUNCTIONS->getSaleOrder(intval($_SESSION['user_id']));
			if($v['newuser_starttime']<=time() && $v['newuser_endtime']>=time() && ($v['newuser_price']>0 || $v['olduser_price']>0)){
				if($ifnew==1 && $v['newuser_price']>0){
					$this->cart_array[$key][$k]['price'] = $v['newuser_price'];
					$v['promotion_state'] = $this->cart_array[$key][$k]['promotion_state'] = 1;
					$this->cart_array[$key][$k]['promotion_type'] = "newmember";
					$this->cart_array[$key][$k]['promotion_name'] = "新會員促銷價格：" . $v['newuser_price'];
				}
			}
			//設定紅綠活動

			if($v['rgid']>0 && $v['redgreen_type']==1){
				$Sql      = "select g.subject_name,dg.red_id,dg.cost,g.saleoff,g.rgid from `{$INFO[DBPrefix]}subject_redgoods` as dg inner join  `{$INFO[DBPrefix]}subject_redgreen` as g on dg.rgid=g.rgid where dg.gid='" . $v['gid'] . "' and g.rgid='" . $v['rgid'] . "' and g.start_date<='" . date("Y-m-d") . "' and g.end_date>='" . date("Y-m-d") . "' and g.subject_open=1 order by g.rgid limit 0,1";
				$Query    = $DB->query($Sql);
				$Num      = $DB->num_rows($Query);
				if ($Num>0){
					$Result= $DB->fetch_array($Query);
					$red_array[$gi]['rgid']	 = $v['rgid'];
					$red_array[$gi]['red_gid']	 = $v['gid'];
					$red_array[$gi]['saleoff']	 = $Result['saleoff'];
					$red_array[$gi]['subject_name']	 = $Result['subject_name'];
				}
				$gi++;
			}



			//設定活動主題
			$Sql_ds   = "select d.* from `{$INFO[DBPrefix]}discountgoods` as dg inner join `{$INFO[DBPrefix]}discountsubject` as d on dg.dsid=d.dsid where dg.gid='" . $v['gid'] . "' and dg.ifcheck=1 and d.subject_open=1 and d.start_date<='" . date("Y-m-d",time()) . "' and d.end_date>='" . date("Y-m-d",time()) . "'";
			$Query_ds = $DB->query($Sql_ds);
			while($Rs_ds =  $DB->fetch_array($Query_ds)){
				if($Rs_ds['dsid']!=$discount_array[$Rs_ds['dsid']]['dsid']){
					//購物車中涉及到的活動主題
					$discount_array[$Rs_ds['dsid']]['dsid'] = $Rs_ds['dsid'];
					$discount_array[$Rs_ds['dsid']]['min_count'] = $Rs_ds['min_count'];
					$discount_array[$Rs_ds['dsid']]['min_money'] = $Rs_ds['min_money'];
					$discount_array[$Rs_ds['dsid']]['mianyunfei'] = $Rs_ds['mianyunfei'];
					$discount_array[$Rs_ds['dsid']]['presentgid'] = $Rs_ds['presentgid'];
						$discount_array[$Rs_ds['dsid']]['level1money'] = $Rs_ds['level1money'];
						$discount_array[$Rs_ds['dsid']]['level2money_min'] = $Rs_ds['level2money_min'];
						$discount_array[$Rs_ds['dsid']]['level2money_max'] = $Rs_ds['level2money_max'];
						$discount_array[$Rs_ds['dsid']]['level2gid'] = $Rs_ds['level2gid'];
						$discount_array[$Rs_ds['dsid']]['level3money'] = $Rs_ds['level3money'];
						$discount_array[$Rs_ds['dsid']]['level3gid'] = $Rs_ds['level3gid'];
					$discount_array[$Rs_ds['dsid']]['saleoff'] = $Rs_ds['saleoff'];
					$discount_array[$Rs_ds['dsid']]['buytype'] = $Rs_ds['buytype'];
					$discount_array[$Rs_ds['dsid']]['buycount'] = $Rs_ds['buycount'];
					$discount_array[$Rs_ds['dsid']]['buyprice'] = $Rs_ds['buyprice'];
					$discount_array[$Rs_ds['dsid']]['subject_name'] = $Rs_ds['subject_name'];
					$discount_array[$Rs_ds['dsid']]['cart_count'] = 0;
					$discount_array[$Rs_ds['dsid']]['cart_total'] = 0;
					$discount_array[$Rs_ds['dsid']]['cart_gidlist'] = $v['gkey'];
				}else{
					$discount_array[$Rs_ds['dsid']]['cart_gidlist'] .= "," . $v['gkey'];//購物車中加入此活動主題的商品KEY
				}
				$discount_array[$Rs_ds['dsid']]['cart_count'] += $v['count'];//購物車中加入此活動主題的累計數量
				$discount_array[$Rs_ds['dsid']]['cart_total'] += $v['count']*$v['price'];//購物車中加入此活動主題的累計金額
			}
			//設置多件折扣
			if($v['ifsales']==1 && $v['sale_subject']>0){
				if($v['sale_subject']!=$saleoff_array[$v['sale_subject']]['subject_id']){
					$Sql_sub   = " select * from `{$INFO[DBPrefix]}sale_subject` where subject_open=1 and subject_id='" . $v['sale_subject'] . "' order by subject_num desc ";
					$Query_sub = $DB->query($Sql_sub);
					$Num_sub   = $DB->num_rows($Query_sub);
					if($Num_sub>0){
						$Result_sub= $DB->fetch_array($Query_sub);
						$saleoff_array[$v['sale_subject']]['subject_id'] = $Result_sub['subject_id'];
						$saleoff_array[$v['sale_subject']]['salecount'] = $Result_sub['salecount'];
						$saleoff_array[$v['sale_subject']]['subject_name'] = $Result_sub['subject_name'];
						$saleoff_array[$v['sale_subject']]['cart_gidlist'] = $v['gkey'];
					}
				}else{
					$saleoff_array[$v['sale_subject']]['cart_gidlist'] .= "," . $v['gkey'];//購物車中加入此活動主題的商品KEY
				}
				$saleoff_array[$v['sale_subject']]['cart_count'] += $v['count'];
			}
			//print_r($saleoff_array);
			//買越多的數量計算
			$saleoff_count_array[$v['gid']] += $v['count'];
		}
		//多件折扣
		foreach($saleoff_array as $sk=>$sv){
			$saleoff_gid_array = explode(",",$sv['cart_gidlist']);
			foreach($saleoff_gid_array as $gk=>$gv){
				if(($this->cart_array[$key][$gv]['promotion_state']>2 || $this->cart_array[$key][$gv]['promotion_state']==0) && $this->cart_array[$key][$gv]['ifsales']==1 && $this->cart_array[$key][$gv]['sale_subject']>0 && $sv['cart_count']>=$sv['salecount']){
					  $this->cart_array[$key][$gv]['price'] = $this->cart_array[$key][$gv]['sale_price'];
					  $this->cart_array[$key][$gv]['promotion_state'] = 2;
					  $this->cart_array[$key][$gv]['promotion_type'] = "saleoff";
					  $this->cart_array[$key][$gv]['promotion_name'] = $sv['subject_name'];

				  }
			}
		}
		//判斷購物車中滿足活動主題條件的商品
		//紅綠商品

		foreach($red_array as $rk=>$rv){
			foreach($cart_array as $k=>$v){
				if($v['rgid']==$rv['rgid'] && $v['redgreen_type']==2 && ($this->cart_array[$key][$k]['promotion_state']>3 || $this->cart_array[$key][$k]['promotion_state']==0)){

					$this->cart_array[$key][$k]['price'] = round($this->cart_array[$key][$k]['price']*$rv['saleoff']/100,0);
					  $this->cart_array[$key][$k]['promotion_state'] = 3;
					  $this->cart_array[$key][$k]['promotion_type'] = "redgreen";
					  $this->cart_array[$key][$k]['promotion_name'] = $rv['subject_name'];
				}elseif($v['rgid']==$rv['rgid'] && $v['redgreen_type']==1 && ($this->cart_array[$key][$k]['promotion_state']>3 || $this->cart_array[$key][$k]['promotion_state']==0)){

					//$this->cart_array[$key][$k]['price'] = round($this->cart_array[$key][$k]['price']*$rv['saleoff']/100,0);
					  $this->cart_array[$key][$k]['promotion_state'] = 3;
					  $this->cart_array[$key][$k]['promotion_type'] = "redgreen";
					  $this->cart_array[$key][$k]['promotion_name'] = $rv['subject_name'];
				}
			}
		}

		//print_r($discount_array);
		foreach($discount_array as $dk=>$dv){
			$dicount_gid_array = explode(",",$dv['cart_gidlist']);
			if(($dv['cart_count']>=$dv['min_count'] || $dv['cart_total']>=$dv['min_money'])  && $dv['cart_count']>=$dv['buycount']){
				if($dv['buytype']==0){
				  //折扣，在網絡價格基礎上折扣
				  foreach($dicount_gid_array as $gk=>$gv){
					  if($this->cart_array[$key][$gv]['promotion_state']>4 || $this->cart_array[$key][$gv]['promotion_state']==0){
						  $this->cart_array[$key][$gv]['dsid'] = $dv['dsid'];
						  $this->cart_array[$key][$gv]['price'] = round($this->cart_array[$key][$gv]['price']*($dv['buyprice']/100),0);
						  $this->cart_array[$key][$gv]['promotion_state'] = 4;
						  $this->cart_array[$key][$gv]['promotion_type'] = "discount";
						  $this->cart_array[$key][$gv]['promotion_name'] = $dv['subject_name'];
						 if($dv['mianyunfei']>0 && $dv['cart_total']>=$dv['mianyunfei']){
						  	 $this->manyunfei[$key] = 1;
						   	$this->manyunfei_money[$key] = $dv['mianyunfei'];
						  }
						  $dstotal += $this->cart_array[$key][$gv]['price'] * $this->cart_array[$key][$gv]['count'];
					  }
				  }
				}elseif($dv['buytype']==1){
					//折扣，多件XX元
				  foreach($dicount_gid_array as $gk=>$gv){
					  if($this->cart_array[$key][$gv]['promotion_state']>4 || $this->cart_array[$key][$gv]['promotion_state']==0){
						  $this->cart_array[$key][$gv]['dsid'] = $dv['dsid'];
						  $this->cart_array[$key][$gv]['price'] = round($dv['buyprice']/$dv['buycount'],0);
						  $this->cart_array[$key][$gv]['promotion_state'] = 4;
						  $this->cart_array[$key][$gv]['promotion_type'] = "discount";
						  $this->cart_array[$key][$gv]['promotion_name'] = $dv['subject_name'];
						  if($dv['mianyunfei']>0&& $dv['cart_total']>=$dv['mianyunfei']){
						  	 $this->manyunfei[$key] = 1;
						   	$this->manyunfei_money[$key] = $dv['mianyunfei'];
						  }
						   $dstotal += $this->cart_array[$key][$gv]['price'] * $this->cart_array[$key][$gv]['count'];
					  }
				  }
				}

			}
			if($dv['buytype']==2){
				//額滿折扣設置
				$Sql_s      = "select * from `{$INFO[DBPrefix]}discountsubject_sale` where dsid='" . $dv['dsid'] . "' and money<='" . $dv['cart_total'] . "' order by money desc limit 0,1";
				$Query_s    = $DB->query($Sql_s);
				$Num_s      = $DB->num_rows($Query_s);
				if ($Num_s>0){
					$Rs_s=$DB->fetch_array($Query_s);
					foreach($dicount_gid_array as $gk=>$gv){
						//echo $Rs_s['saleoff'];exit;
						  if($this->cart_array[$key][$gv]['promotion_state']>4 || $this->cart_array[$key][$gv]['promotion_state']==0){
						  	  $this->cart_array[$key][$gv]['price'] = round($this->cart_array[$key][$gv]['price']*($Rs_s['saleoff']/100),0);
							  $this->cart_array[$key][$gv]['promotion_state'] = 4;
							  $this->cart_array[$key][$gv]['promotion_type'] = "discount";
							  $this->cart_array[$key][$gv]['promotion_name'] = $dv['subject_name'];
							  //$this->cart_array[$key][$gv]['cost'] = $v['salecost'];
							   $this->cart_array[$key][$gv]['cost'] = $this->cart_array[$key][$gv]['dscost'];
							   if($dv['mianyunfei']>0&& $dv['cart_total']>=$dv['mianyunfei']){
							    $this->manyunfei[$key] = 1;
								 $this->manyunfei_money[$key] = $dv['mianyunfei'];
							   }
						  }
				  	}
				}
			}
			//活動贈品
				$pregid = 0;
				//echo $dv['level2money_min'];

				if($dstotal<=$dv['level1money'] && $dv['presentgid']>0){
					 $pregid = $dv['presentgid'];
				}elseif($dstotal>=$dv['level2money_min'] && $dstotal<=$dv['level2money_max'] && $dv['level2gid']>0){
					 $pregid = $dv['level2gid'];
				}elseif($dstotal>=$dv['level3money'] && $dv['level3gid']>0){
					$pregid = $dv['level3gid'];
				}
				//echo $pregid;
				if($pregid>0){
					$pre_array = array();
					$pre_array['count'] = 1;
					$pre_array['present_subjectid'] = $dv['dsid'];
					$pre_array['ifgoodspresent'] = 1;
					include_once("product.class.php");
					$PRODUCT = new PRODUCT;
					$storage = $PRODUCT->checkStorage($pregid,0,"","",1);
					if($storage>0)
						$this->getShoppingGoods($pregid,$pre_array,"goodspresent",$key);
					if(intval($_SESSION['user_id'])>0){
						$subSql = " and (s.user_id='" . intval($_SESSION['user_id']) . "'";
						$subSql .= " or s.session_id='" . $session_id . "')";
					}else{
						$subSql .= " and s.session_id='" . $session_id . "'";
					}
					if($key!="")
						$subSql .= " and s.pkey='" . $key . "'";
					 $Sql = "select g.*,s.* from `{$INFO[DBPrefix]}shopping` as s inner join `{$INFO[DBPrefix]}goods` as g on s.gid=g.gid where   g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.gid='" . $pregid . "' and s.present_subjectid='" . $dv['dsid'] . "' " . $subSql . " order by pregid asc";
					$Query = $DB->query($Sql);
					$Num   = $DB->num_rows($Query);
					if ($Num>0) {
						while($Rs = $DB->fetch_array($Query)){
							$this->cart_array[$Rs['pkey']][$Rs['gkey']]=$Rs;
							$this->cart_array[$Rs['pkey']][$Rs['gkey']]['count']=1;
							$this->cart_array[$Rs['pkey']][$Rs['gkey']]['price']=0;
							$this->cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_state']=-1;
							$this->cart_array[$Rs['pkey']][$Rs['gkey']]['promotion_name']=$dv['subject_name'] . "贈品";
						}
					}
				}
		}
		/*
		發票設置
		*/
		$this->invoice['ifinvoice'] = intval($INFO['Need_invoice']);
		if ($this->invoice['ifinvoice'] == 1){
			$this->invoice['invoice'] = intval($INFO['invoice']);
			if ($this->invoice['invoice'] > 0){
				$needinvoice = 1;
			}
		}
		$cart_array = $this->cart_array[$key];
		//print_r($cart_array);exit;
		foreach($cart_array as $k=>$v){
			if($v['gid']>0){
				//整點促銷
				if(($v['promotion_state']==0 || $v['promotion_state']>5) && $v['iftimesale']==1 && $v['timesale_starttime']<=time() && $v['timesale_endtime']>=time()){
					 $this->cart_array[$key][$k]['price'] = $v['saleoffprice'];
					$v['promotion_state'] = $this->cart_array[$key][$k]['promotion_state'] = 5;
					$this->cart_array[$key][$k]['promotion_type'] = "timesale";
					$this->cart_array[$key][$k]['promotion_name'] = "整點促銷：" . date("Y-m-d H:i:s",$v['timesale_starttime']) . "~" . date("Y-m-d H:i:s",$v['timesale_endtime']);
				}
				//買越多
				//計算同商品數量
				$allcount = intval($saleoff_count_array[$v['gid']]);
				if(intval($_SESSION['user_level'])>0){
					$subsql = " and m.m_level_id='" . intval($_SESSION['user_level']) . "'";
				}
				$Sql_sale      = "select m.m_price as price,s.mincount,s.maxcount from `{$INFO[DBPrefix]}goods_saleoffe` as s left join `{$INFO[DBPrefix]}member_price` as m on s.soid=m.m_saleoffid where s.gid='" . intval($v['gid']) . "' and m.m_detail_id='" . intval($v['detail_id']) . "' and s.mincount<=" . $allcount . " and (s.maxcount>=" . $allcount . " or s.maxcount=0) " . $subsql . " order by m.m_price desc limit 0,1";
				$Query_sale    = $DB->query($Sql_sale);
				 $Num_sale      = $DB->num_rows($Query_sale);
				if ($Num_sale>0 && ($v['promotion_state']==0 || $v['promotion_state']>6)){
					$Result_sale= $DB->fetch_array($Query_sale);
					 $this->cart_array[$key][$k]['price'] = $Result_sale['price'];
					$v['promotion_state'] = $this->cart_array[$key][$k]['promotion_state'] = 6;
					$this->cart_array[$key][$k]['promotion_type'] = "saleoff";
					if($Result_sale['maxcount']==0)
						$this->cart_array[$key][$k]['promotion_name'] = "買越多：超過" . $Result_sale['mincount'] . "件" . $Result_sale['price'] . "元";
					else
						$this->cart_array[$key][$k]['promotion_name'] = "買越多:" . $Result_sale['mincount'] . "件~" . $Result_sale['maxcount'] . "件" . $Result_sale['price'] . "元";
				}

				//館別折扣
				if($v['promotion_state']==0 || $v['promotion_state']>7){
					$rebate_array = $FUNCTIONS->getTopClass(intval($v['bid']));
					if($rebate_array[0]>0){
						$this->cart_array[$key][$k]['price'] = round(round($v['price']/100,2)*$rebate_array[0],0);
						$v['promotion_state'] = $this->cart_array[$key][$k]['promotion_state'] = 7;
						$this->cart_array[$key][$k]['promotion_type'] = "class_saleoff";
						$this->cart_array[$key][$k]['promotion_name'] = "館別折扣" .$rebate_array[0] . "%" ;
					}
				}

				//全館折扣

				if(($v['promotion_state']==0 || $v['promotion_state']>8) && intval($INFO['allsaleoff'])>0 && intval($INFO['allsaleoff'])<100 && date("Y-m-d",time())>=$INFO['allsaleoff_begintime'] && date("Y-m-d",time())<=$INFO['allsaleoff_endtime']){
					$this->cart_array[$key][$k]['price'] = round(intval($INFO['allsaleoff'])/100 * $v['price'],0);
					$v['promotion_state'] = $this->cart_array[$key][$k]['promotion_state'] = 8;
					$this->cart_array[$key][$k]['promotion_type'] = "site_saleoff";
					$this->cart_array[$key][$k]['promotion_name'] = "全館折扣" . $INFO['allsaleoff'] . "%" ;
				}
				//會員價
				$memberprice = $FUNCTIONS->MemberLevelPrice($_SESSION['user_level'],intval($v['gid']),intval($v['detail_id']));
				if(($v['promotion_state']==0 || $v['promotion_state']>9) && intval($_SESSION['user_level'])>0 && $memberprice>0){
					$this->cart_array[$key][$k]['price'] = $memberprice;
					$v['promotion_state'] = $this->cart_array[$key][$k]['promotion_state'] = 9;
					$this->cart_array[$key][$k]['promotion_type'] = "member_saleoff";
					$this->cart_array[$key][$k]['promotion_name'] = "";
				}

				if($needinvoice==1){
					$this->cart_array[$key][$k]['price'] = 	intval($this->cart_array[$key][$k]['price']*(1+$this->invoice['invoice']/100));
				}
			}
		}
	}


	/**
	某商品是否存在於購物車
	**/
	function existCart($cart_array){
		global $DB;
		global $INFO,$FUNCTIONS,$session_id;
		if(intval($_SESSION['user_id'])>0){
			$subSql = " and (user_id='" . intval($_SESSION['user_id']) . "'";
			$subSql .= " or session_id='" . $session_id . "')";
		}else{
			$subSql .= "  and session_id='" . $session_id . "'";
		}
		 $Sql = "select * from `{$INFO[DBPrefix]}shopping` where gid='" . $cart_array['gid'] . "' and good_size='" . $cart_array['good_size'] . "' and good_color='" . $cart_array['good_color'] . "' and ifadd='" . $cart_array['ifadd'] . "' and xygoods='" . $cart_array['xygoods'] . "' and xygoods_color='" . $cart_array['xygoods_color'] . "' and xygoods_size='" . $cart_array['xygoods_size'] . "' and detail_id='" . $cart_array['detail_id'] . "' and packgid='" . $cart_array['packgid'] . "' and rgid='" . $cart_array['rgid'] . "' and redgreen_type='" . $cart_array['redgreen_type'] . "'  " . $subSql . "";
		$Query = $DB->query($Sql);
		 $Num   = $DB->num_rows($Query);
		if($Num>0){
			$Rs = $DB->fetch_array($Query);
			return $Rs['gkey'];
		}else{
			return false;
		}
	}
	/**
	刪除某商品信息
	**/
	function deleItems($key,$gkey){
		$present_array=array();
		$i = 0;
		$if_present = $this->cart_array[$key][$gkey]['ifpresent'];
		$ifgoodspresent = $this->cart_array[$key][$gkey]['ifgoodspresent'];
		$ifpack = $this->cart_array[$key][$gkey]['ifpack'];
		$j = 0;
		//echo $this->cart_array[$key][$gkey]['gid'];

		foreach($this->cart_array[$key] as $k=>$v){
			//echo $v['ifpresent'] ."+".$v['gid']  . "|";
			//主商品刪除，加購商品也要刪除
			if ($v['changegid'] == $this->cart_array[$key][$gkey]['gid']){
				unset($this->cart_array[$key][$k]);
				$this->deleItems($key,$k);
			}
			//主商品刪除，組合子商品也要刪除
			if ($v['packgid'] == $this->cart_array[$key][$gkey]['gid']&&$ifpack==1){
				unset($this->cart_array[$key][$k]);
				$this->deleteCartData($key,$k);
			}
			//主商品刪除，贈品刪除
			if ($v['pregid'] == $this->cart_array[$key][$gkey]['gid']&&$v['xygoods_color'] == $this->cart_array[$key][$gkey]['good_color']&&$v['xygoods_size'] == $this->cart_array[$key][$gkey]['good_size']&&$v['ifgoodspresent']==1){
				unset($this->cart_array[$key][$k]);
				$this->deleteCartData($key,$k);
			}
			//主商品刪除，綠標刪除
			if ($v['redgreen_type']>0 && $v['rgid']== $this->cart_array[$key][$gkey]['rgid']){
				unset($this->cart_array[$key][$k]);
				$this->deleteCartData($key,$k);
			}
			//如果有額滿禮，判斷商品刪除后是否滿足額滿禮條件
			if ($v['ifpresent']==1){
				unset($this->cart_array[$key][$k]);
				$this->deleteCartData($key,$k);
				//exit;
				//$present_array[$i]['key'] = $key;
				//$present_array[$i]['gkey'] = $v['gkey'];
				//$present_array[$i]['present_money'] = $v['present_money'];
				//$i++;
			}
			if ($v['ifadd']==1){
				unset($this->cart_array[$key][$k]);
				$this->deleteCartData($key,$k);
			}
			if($v['pregid']==0&&$v['ifgoodspresent']==1){
				$goodspresent = $k;
			}
			$j++;
		}
		//exit;
		$goodspresentgid = $this->cart_array[$key][$gkey]['goodspresentgid'];
		unset($this->cart_array[$key][$gkey]);
		$this->deleteCartData($key,$gkey);
		//echo $goodspresent . "|" .count($this->cart_array[$key]) ;exit;
		if (count($this->cart_array[$key])==0 || ($goodspresent!=""&&count($this->cart_array[$key])==1)){
			unset($this->cart_array[$key]);
			$this->deleteCartData($key,$goodspresent);
		}
		if ($if_present!=1){
			//$this->setTotal($key);
			foreach($present_array as $k=>$v){
				if ($this->discount_totalPrices < $v['present_money']){
					$this->deleItems($v['key'],$v['gkey']);
				}
			}
		}
		//赠品
		if(ifgoodspresent==0)
			$this->goodspresent($key,$gkey,array(),$goodspresentgid);
	}
	/**
	清空購物車
	**/
	function clearGoods($key=""){
		if($key!=""){
			foreach($this->cart_array[$key] as $k=>$v){
				$this->deleItems($key,$k);
				unset($this->cart_array[$key][$k]);
			}
			$this->cart_array[$key] = array();
			unset($this->cart_array[$key]);
		}else{
			foreach($this->cart_array as $k=>$v){
				foreach($v as $kk=>$vv){
					$this->deleItems($k,$kk);
					unset($this->cart_array[$k][$kk]);
				}
				$this->cart_array[$k] = array();
				unset($this->cart_array[$k]);
			}
		}

	}
	/**
	清楚額滿加購商品
	**/
	function clearAddGoods($key,$gkey){
		if($this->cart_array[$key][$gkey]['ifadd']==0){
			if (is_array($this->cart_array[$key])){
				foreach($this->cart_array[$key] as $k=>$v){
				  if ($v['ifadd']==1){
					  $this->deleItems($key,$v['gkey']);
				  }
				}
			}
		}
	}

	/**
	更改商品數量
	**/
	function changeItemsCount($key,$gkey,$value,$type=0){
		global $DB;
		global $INFO,$FUNCTIONS;
		//紅利商品的處理
		if($if_bonus==1){
			$this->setTotal($key);
			$member_point = $FUNCTIONS->Userpoint(intval($_SESSION['user_id']),1);
			$point = $member_point-$this->totalbonuspoint-$this->bonus['point'];
			if($this->cart_array[$key][$gkey]['count']<$value){
				$wantbonus = ($value-$this->cart_array[$key][$gkey]['count'])*$this->cart_array[$key][$gkey]['bonusnum'];
				if($wantbonus>$point){
					return 0;
				}
			}
		}
		if($this->cart_array[$key][$gkey]['ifgoodspresent']==1)
			return 0;
		$this->cart_array[$key][$gkey]['count'] = $value;
		//更改數據庫里面的數量
		$count = $this->changCartData($key,$gkey,array('gcount'=>$value),$type);
		$present_array=array();
		$i = 0;
		$if_present = $this->cart_array[$key][$gkey]['ifpresent'];
		$if_bonus = $this->cart_array[$key][$gkey]['ifbonus'];
		$ifpack = $this->cart_array[$key][$gkey]['ifpack'];

		if ($if_present!=1){
			foreach($this->cart_array[$key] as $k=>$v){
				if ($v['ifpresent']==1){
					$present_array[$i]['key'] = $key;
					$present_array[$i]['gkey'] = $v['gkey'];
					$present_array[$i]['present_money'] = $v['present_money'];
					$i++;
				}
				$a=explode("_",$gkey);
				$b=explode("_",$v['gkey']);
				if ($v['packgid']==$this->cart_array[$key][$gkey]['gid'] && $ifpack==1&&$a[1]==$b[1]){
					$this->cart_array[$key][$v['gkey']]['count'] = $value;
					$this->changCartData($key,$k,array('gcount'=>$value));
				}
				if ($v['pregid']==$this->cart_array[$key][$gkey]['gid'] &&$v['ifgoodspresent']==1&&$type=1&&$a[1]==$b[1]){
					 $this->cart_array[$key][$v['gkey']]['count'] = $value;
					$this->changCartData($key,$k,array('gcount'=>$count),0);
				}
			}//exit;
			foreach($present_array as $k=>$v){
				if ($this->discount_totalPrices < $v['present_money']){
					$this->deleItems($v['key'],$v['gkey']);
				}
			}
			//赠品
			$this->goodspresent($key,$gkey);
		}
		return 1;
	}
	//更新赠品
	function goodspresent($key,$gkey,$goodsitems=array(),$delgoodspresentgid=""){
		//echo $delgoodspresentgid;
		global $DB;
		global $INFO,$FUNCTIONS,$session_id;
		if($goodsitems['goodspresentgid']!=""){
			$goodspresentgid = $goodsitems['goodspresentgid'];
		}elseif($delgoodspresentgid!=""){
			$goodspresentgid = $delgoodspresentgid;
		}else{
			$goodspresentgid = $this->cart_array[$key][$gkey]['goodspresentgid'];
		}
		$goodspresentgid_array = explode(",",$goodspresentgid);
		$goodspresentcount_array = array();
		foreach($this->cart_array[$key] as $k=>$v){
			//echo $v['goodspresentgid'];
			//echo $v['gid']; echo "|";
			$thisgoodspresentgid_array = explode(",",$v['goodspresentgid']);
			if($v['goodspresentgid']!=""){
				foreach($thisgoodspresentgid_array as $pk=>$pv){
					//echo $v['count'];

					//echo "|";
					$goodspresentcount_array[$pv] = intval($goodspresentcount_array[$pv]) + $v['count'];
				}
			}

		}//exit;
		if($goodsitems['goodspresentgid']!=""){
			foreach($goodspresentgid_array as $pk=>$pv){
				$goodspresentcount_array[$pv] = intval($goodspresentcount_array[$pv]) + $goodsitems['count'];
			}
		}


		foreach($goodspresentgid_array as $pk=>$pv){
			foreach($this->cart_array[$key] as $k=>$v){
				if($pv==$v['gid'] && $pv>0){
					if($goodspresentcount_array[$pv]>0){
						$this->cart_array[$key][$v['gkey']]['count'] = $goodspresentcount_array[$pv];
						$this->changCartData($key,$v['gkey'],array('gcount'=>$goodspresentcount_array[$pv]),0);
					}else{
						$this->cart_array[$key][$v['gkey']]['count'] = 0;
						$this->deleItems($v['key'],$v['gkey']);
					}
				}
			}
		}

	}
	//更新數據
	function changCartData($key,$gkey,$value_array,$type=0){
		global $DB;
		global $INFO,$FUNCTIONS,$session_id;
		if(intval($_SESSION['user_id'])>0){
			$subSql = " and (user_id='" . intval($_SESSION['user_id']) . "'";
			$subSql .= " or session_id='" . $session_id . "')";
		}else{
			$subSql .= "  and session_id='" . $session_id . "'";
		}
		$db_string = $DB->compile_db_update_string($value_array);

		$Sql = "select * from `{$INFO[DBPrefix]}shopping` WHERE pkey='" . $key . "' and gkey='" . $gkey . "' ";
		$Result_Update = $DB->query($Sql);
		$Result= $DB->fetch_array($Result_Update);
		$gcount= $Result['gcount'];

		if($type==1){
			if(($gcount+$value_array['gcount'])>intval($INFO['buy_product_max_num']) && $type==1){
				$db_string = "gcount=" . intval($INFO['buy_product_max_num']);
				$gcount = intval($INFO['buy_product_max_num']);
			}else {
				$db_string = "gcount=gcount+" . $value_array['gcount'];
				$gcount += $value_array['gcount'];
			}
		}

		$Sql = "UPDATE `{$INFO[DBPrefix]}shopping` SET $db_string WHERE pkey='" . $key . "' and gkey='" . $gkey . "' " . $subSql;
		$Result_Update = $DB->query($Sql);

		$Sql = "select * from `{$INFO[DBPrefix]}shopping` WHERE pkey='" . $key . "' and gkey='" . $gkey . "' ";
		$Result_Update = $DB->query($Sql);
		$Result= $DB->fetch_array($Result_Update);
		return $Result['gcount'];
		//return $Result_Update;
	}
	//從數據庫中刪除
	function deleteCartData($key="",$gkey=""){
  global $DB;
  global $INFO,$FUNCTIONS,$session_id;
  if($key!="")
   $subSql .= " and pkey='" . $key . "'";
  if($gkey!="")
   $subSql .= " and gkey='" . $gkey . "'";
  if(intval($_SESSION['user_id'])>0){
   $subSql .= " and (user_id='" . intval($_SESSION['user_id']) . "'";
   $subSql .= " or session_id='" . $session_id . "')";
  }else{
   $subSql .= "  and session_id='" . $session_id . "'";
  }
   $Sql = "delete from `{$INFO[DBPrefix]}shopping` where 1=1" . $subSql;
   $Result_Update = $DB->query($Sql);
  return $Result_Update;

 }
	/**
	購物車商品總金額
	**/
	function setTotal($key){
	  $total = 0;
	  $this->totalbonuspoint = 0;
	  if (is_array($this->cart_array[$key])){

		foreach($this->cart_array[$key] as $kk=>$vv){
			if($vv['ifgoodspresent']==1){
						$gpcount =0;
						foreach($this->goodsGroup[$key] as $ks=>$vs){
							if($vs['gid']==$vv['pregid']&& $vv['xygoods_color']==$vs['good_color'] && $vv['xygoods_size']==$vs['good_size'])
								$gpcount = $vs['count'];
						}
						$this->goodsGroup[$key][$k]['count'] = $gpcount;
					}
			 if (intval($vv['packgid'])==0){
			  		$total+=$vv['price']*$vv['count'];
			 	 //計算使用紅利
				  if ($v['ifbonus'] == 1){
				   $this->totalbonuspoint = intval($this->totalbonuspoint) + intval($v['count']) * intval($v['bonuspoint']);
				  }
			 }
		}

  }

	  $this->discount_totalPrices = $total;
	  //折扣后價格，指使用紅利折價券后
	  if($this->bonus['point']>0){
	   $this->discount_totalPrices =  $total-$this->bonus['point'];
	  }elseif($this->tickets['id']!=0 && $this->tickets['money']>0){
	   if($this->tickets['moneytype']==0){
		$this->tickets['discount_money'] = $this->tickets['money'];
		$this->discount_totalPrices =  $total-$this->tickets['money'];
	   }else{
		$this->tickets['discount_money'] = round($total*$this->tickets['money'],0);
		$this->discount_totalPrices =  $total-$this->tickets['discount_money'];
   }
   $this->discount_totalPrices = round($this->discount_totalPrices,0);
  }

  return $this->totalPrices = $total;
 }

	/**
	检查是否有符合折价券的商品
	**/
	function setCheckTicket($goods_ids,$key){
		$ifhave = 0;
		if ($goods_ids!=""){
			$goods_ids_array = explode(",",$goods_ids);
			if (!is_array($goods_ids_array)){
				return;
			}
			if (is_array($this->cart_array[$key])){
				foreach($this->cart_array[$key] as $k=>$v){
					if(in_array($v['gid'],$goods_ids_array) && $v['count'] > 0){
						$ifhave = 1;
					}
				}
			}
			if ($ifhave == 0){
				return 0;
			}
		}
		return 1;
	}
	/**
	設置折價券
	**/
	function setTicket($ticketid,$key,$ticketcode=""){
		global $DB;
		global $INFO,$FUNCTIONS;
		$MemberPice_total = $this->setTotal($key);
		//會員發放折價券
		if($ticketid==0){
			$ticket = array();
		}elseif($ticketid>0 && $ticketcode==""){
			$Sql = "select t.* from `{$INFO[DBPrefix]}userticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where ut.userid=".intval($_SESSION['user_id'])." and t.use_starttime<='" . date('Y-m-d',time()) . "' and t.use_endtime>='" . date('Y-m-d',time()) . "' and ut.ticketid='" . $ticketid . "' group by ut.ticketid having sum(ut.count)>0";
			$Query =  $DB->query($Sql);
			$Num   =  $DB->num_rows($Query);
			if ($Num>0){
				$Rs = $DB->fetch_array($Query);
				if($Rs['ordertotal']>$MemberPice_total){
					return array(0,$Rs['ordertotal']);
				}
				if($this->setCheckTicket($Rs['goods_ids'],$key)==false)
					return array(-3);
				$ticket['id'] = intval($ticketid);
				$ticket['money'] = $Rs['money'];
				$ticket['moneytype'] = $Rs['moneytype'];
				$ticket['type'] = $Rs['type'];
				$ticket['ticketcode'] = "";
				$ticket['goods_ids'] = $Rs['goods_ids'];
				$ticket['canmove'] = $Rs['canmove'];

			}else{
				return array(-2);
			}
		}elseif($ticketid==-1 || $ticketcode!=""){
			//通用折價券
			$Sql = "select t.money,t.moneytype,t.type,t.goods_ids,t.ordertotal,t.canmove,tc.* from `{$INFO[DBPrefix]}ticketcode` as tc inner join `{$INFO[DBPrefix]}ticket` as t on tc.ticketid=t.ticketid where tc.ticketcode='" . $ticketcode . "' and t.use_starttime<='" . date('Y-m-d',time()) . "' and t.use_endtime>='" . date('Y-m-d',time()) . "'";
			$Query =  $DB->query($Sql);
			$Num   =  $DB->num_rows($Query);
			if ($Num>0){
				$Rs = $DB->fetch_array($Query);
				if($Rs['ordertotal']>$MemberPice_total){
					return array(0,$Rs['ordertotal']);
				}
				if($Rs['userid']>0 && ($Rs['canmove']==1 ||$Rs['type']==0) ){
					return array(-1);
				}
				
				if (intval($_SESSION['user_id']) > 0){
					//每張通用折價券只能使用一次
					$use_sql = "select * from `{$INFO[DBPrefix]}use_ticket` where userid='" . intval($_SESSION['user_id']) . "' and ticketid='" . $Rs['ticketid'] . "' and ticketcode='" . $ticketcode . "'";
					$user_Query =  $DB->query($use_sql);
					$user_Num   =  $DB->num_rows($user_Query);
					if ($user_Num > 0){
						return array(-1);
					}
				}
				if($this->setCheckTicket($Rs['goods_ids'],$key)==false)
					return array(-3);
				$ticket['id'] = $Rs['ticketid'];
				$ticket['canmove'] = $Rs['canmove'];
				$ticket['money'] = $Rs['money'];
				$ticket['moneytype'] = $Rs['moneytype'];
				$ticket['type'] = $Rs['type'];
				if($Rs['canmove']==1 || $ticketid==-1)
					$ticket['type'] = 1;
				$ticket['ticketcode'] = $Rs['ticketcode'];
				$ticket['goods_ids'] = $Rs['goods_ids'];
				
			}else{
				return array(-2);;
			}
		}
		$this->tickets = $ticket;
		//print_r($ticket);exit;
		$this->bonus['point'] = 0;//紅利清0
		return array(1);
	}
	/**
	設置紅利
	**/
	function setBonus($bonuspoint,$key){
		global $DB;
		global $INFO,$FUNCTIONS;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}bonus`");
		$Result= $DB->fetch_array($Query);
		$rebate  =  $Result['rebate'];//紅利折抵比例
		$member_point = $FUNCTIONS->Userpoint(intval($_SESSION['user_id']),1);  //紅利點數
		$member_point-= $this->totalbonuspoint;//減去已經兌換的紅利點數
		$MemberPice_total = $this->setTotal($key);
		//最多可以使用的折抵紅利點數
		 $MaxbonusPoint = round($MemberPice_total * $rebate/100,0)>$member_point?$member_point:round($MemberPice_total * $rebate/100,0);
		if(intval($bonuspoint) > $MaxbonusPoint || $member_point<0 || intval($bonuspoint) > $member_point){
			return array(0,$MaxbonusPoint-$this->totalbonuspoint);
		}
		$this->bonus['point'] = intval($bonuspoint);
		$this->tickets = array();//折價券清0
		//判斷額滿禮
		$goods = $this->cart_array[$key];
		if (is_array($goods)){
			foreach($goods as $keys=>$values){
				if ($values['ifpresent']==1 && $this->discount_totalPrices<$values['present_money']){
					$this->deleItems($key,$values['gkey']);
				}
			}
		}
		return array(1);
	}
	/**
	計算紅利
	**/
	function setGroupbonuspoint($key){
		$grouppoint=0;
		//if(is_array($this->goodsGroup)){
			//foreach($this->goodsGroup as $keys=>$values){
				if (is_array($this->cart_array[$key])){
					foreach($this->cart_array[$key] as $k=>$v){
						if ($v['ifbonus'] == 1){
							 $grouppoint = intval($grouppoint) + intval($v['count']) * intval($v['bonusnum']);
						}
					}
				}
				return intval($grouppoint);
			//}
		//}
	}
	/**
	設置購物金
	**/
	function setBuypoint($buypoint,$key){
		global $DB;
		global $INFO,$FUNCTIONS;
		$myBuyPoint = $FUNCTIONS->Buypoint(intval($_SESSION['user_id']));
		$this->setTotal($key);
		if(intval($buypoint) > $myBuyPoint){
			return 0;
		}
		if(intval($buypoint) < $this->discount_totalPrices+$this->transmoney){
			return -1;
		}
		$this->totalBuypoint = intval($buypoint);
		return 1;

	}

	/**
	設置系統運費
	**/
	function setIniTrans($trans_type,$transinfo){
		$this->sys_trans_type = $trans_type;
		$this->sys_trans[0]['FreeTransMoney'] = $transinfo[PayFreetrans];
		$this->sys_trans[1]['PayStartprice'] = $transinfo[PayStartprice];
		$this->sys_trans[1]['TransMoney'] = $transinfo[PayEndprice];
	}

	/**
	設置運費
	**/
	function setTransMoney($man_trans_type,$key,$man_nomal_type = 0,$trans_money = 0,$trans_permoney=0,$mianyunfei=0,$ifabroad=0){
		$this->special_trans_type = $man_trans_type;
		$this->nomal_trans_type = $man_nomal_type;
		$weight = 0;
		if($man_trans_type ==3){
			return;
		}
		//echo $this->manyunfei[$key];
		if ($this->manyunfei[$key]==1){
			$this->transmoney = 0;
			$this->transname = "參加促銷活動【當購買滿" . $this->manyunfei_money[$key] . "免運費。】";
			return;
		}
		$iffree = 1;
		$freetran = 1;
		$mianyuntotal = 0;
		if($man_trans_type !=3 &&  $man_trans_type !=4 ){

			if(is_array($this->cart_array[$key])){
				foreach($this->cart_array[$key] as $k=>$v){
					//echo $v['ifmood'];
					if($v['ifmood'] == 0 && $v['freetran'] == 0)
						$iffree = 0;
					if($v['freetran'] == 1){
						$mianyuntotal+= $v['price']*$v['count'];
					}else{
						$freetran = 0;	
					}
					 $weight += $v['weight']*$v['count'];
				}
			}
		}
		if ($freetran==1){
			$this->transmoney = 0;
			return;
		}
		if(substr($key,0,1)=="M"){
				$m_key_value = explode("_",$key);
				$bid = substr($m_key_value[0],1);
				if ($this->discount_totalPrices>=$manyunfei){
					$this->transmoney = 0;
					return;
				}
		}

		if($this->sys_trans_type == 1){
			if (($this->discount_totalPrices-$mianyuntotal) <= $this->sys_trans[1]['PayStartprice']){
				$this->transmoney = $this->sys_trans[1]['TransMoney'];
				//$this->transname = "當購買商品總金額小於" . $this->sys_trans[1]['PayStartprice'] . "元，須加收運費" . $this->sys_trans[1]['TransMoney'] . "元。";
				$this->ifnotrans = 1;
			}
			else{
				$this->transmoney = 0;

				$this->ifnotrans = 1;
			}
		}else if($this->sys_trans_type == 0){
			//echo $iffree;exit;
			if ($this->sys_trans[0]['FreeTransMoney'] > 0 && ($this->discount_totalPrices-$mianyuntotal) >= $this->sys_trans[0]['FreeTransMoney'] && $iffree==1 && $mianyunfei ==0){
				$this->transmoney = 0;
				$this->ifnotrans = 1;
				//$this->transname = "當購買滿" . $this->sys_trans[0]['FreeTransMoney'] . "免運費。";
				$this->ifnotrans = 1;
			}elseif ($mianyunfei > 0 && ($this->discount_totalPrices-$mianyuntotal) >= $mianyunfei && $iffree==1){
				$this->transmoney = 0;
				$this->ifnotrans = 1;
				//$this->transname = "當購買滿" . $mianyunfei . "免運費。";
			}else{
				if ($man_trans_type == 0){
					if ($weight<=1)
						$this->transmoney = $trans_money;
					else
						$this->transmoney = $trans_money + ceil($weight-1)*$trans_permoney;
				}else if ($man_trans_type == 1){
					$this->transmoney = $this->getTransSpecial($key);
				}else if($man_trans_type == 2){
					$this->sys_trans_type = 3;
					$this->transmoney = $this->getTransType($key,$ifabroad);
				}

			}
		}
	}
	/**
	特殊配送方式運費
	**/
	function getTransSpecial($key){
		$transtotal = 0;
		$goods = $this->goodsGroup[$key];
		//print_r($this->goodsGroup[$key]);
		if (is_array($goods)){
			foreach($goods as $keys=>$values){
				if ($values['ifpresent']!=1){
					$transtotal = $transtotal + intval($values['special_trans_money']);
				}
			}
		}
		return $transtotal;
	}
	/**
	中型貨物配送方式運費
	**/
	function getTransType($key,$ifabroad = 0){
		$transtotal = 0;
		$goods = $this->goodsGroup[$key];
		if (is_array($goods)){
			foreach($goods as $keys=>$values){
				$transtotal = $transtotal + intval($values['transtypemonty'])*$values['count'];
				if ($ifabroad == 1){
					$transtotal = $transtotal + $values['addtransmoney'];
				}
			}
		}
		$this->transmoney = $transtotal;
		return $transtotal;
	}
	/**
	設置海外運費
	**/
	function setabroad($man_trans_type,$key,$free){

		if ($man_trans_type==2){
			$goods = $this->goodsGroup[$key];
			if (is_array($goods)){
				foreach($goods as $keys=>$values){
					$this->transmoney  = $this->transmoney  + intval($values['addtransmoney']);
				}
			}
		}elseif ($man_trans_type>=0){
			$this->transmoney  = $free;
		}
	}
	/**
	設置門市取貨
	**/
	function setStore($key,$store_array){
		$this->store[$key] = $store_array;
		return;
	}
	/**
	設置發票
	**/
	function setinvoice($ifinvoice,$invoice){
		$this->invoice['ifinvoice'] = intval($ifinvoice);
		if ($this->invoice['ifinvoice'] == 1){
			$this->invoice['invoice'] = intval($invoice);
			if ($this->invoice['invoice'] > 0){
				$this->totalPrices = intval($this->totalPrices*(1+$this->invoice['invoice']/100));
				$this->discount_totalPrices = intval($this->discount_totalPrices*(1+$this->invoice['invoice']/100));
			}
		}
	}
}


?>
