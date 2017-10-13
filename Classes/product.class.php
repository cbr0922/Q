<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
class PRODUCT{
	/**
	得到某商品簡單信息
	**/
	function getSimpleProductInfo($gid,$fieldname=""){
		global $DB,$INFO;

		if($fieldname!="")
			$subSql = " g." . $fieldname;
		else
			$subSql = " g.*";
		$Sql = "select " . $subSql . " from `{$INFO[DBPrefix]}goods` g where g.gid='" . $gid . "' and g.ifpub=1  and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "')";
		$Query   = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		if($Num<=0){
			return 0;
		}else{
			$Result_goods = $DB->fetch_array($Query);
		}
		return $Result_goods;
	}
	/**
	得到某商品詳細信息
	**/
	function getProductInfo($gid,$goodstype = ""){
		global $DB,$INFO,$FUNCTIONS;
		$checkshop = $this->CheckIfShopProduct($gid);  //查看是否為商店街商品
		if ($checkshop['shopid']>0)
			$bname = "shopbclass";
		else
			$bname = "bclass";
		if($goodstype == "")
			$subSql = "  and g.ifxy!=1 and g.ifpresent!=1 and g.ifchange!=1 and g.ifgoodspresent!=1 and g.ifbonus!='1'";
		elseif($goodstype == "other")
			$subSql = "  and g.ifxy!=1 and g.ifpresent!=1 and g.ifchange!=1";
		if($goodstype == "admin" && $_SESSION['LOGINADMIN_session_id']!="" && $_SESSION['sa_id']>0 && $_SESSION['Admin_Sa']!="")
			$subSql = "  ";
		else
			$subSql .= "  and  b.catiffb=1 and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') " . $subSql;

		$Sql = "select g.*,p.provider_name,br.brandname,br.brandcontent,br.logopic,b.attr from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}" . $bname . "` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)  where g.gid='" . $gid . "' " . $subSql;
		$Query   = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		if($Num<=0){
			return 0;
		}else{
			$Result_goods = $DB->fetch_array($Query);

			if($Result_goods['salestartdate']<=date("Y-m-d") && $Result_goods['saleenddate']>=date("Y-m-d")){
				$Result_goods['salecontent'] = $FUNCTIONS->strUrlEncode($Result_goods['salecontent']);
			}else{
				$Result_goods['salecontent'] = "";
			}
			$Result_goods['goodsname1'] = $FUNCTIONS->strUrlEncode($Result_goods['goodsname']);
			$Result_goods['brandname1'] = $FUNCTIONS->strUrlEncode($Result_goods['brandname']);

			$Result_goods['showstorage'] = $FUNCTIONS->Storage($Result_goods['ifalarm'],$Result_goods['storage'],$Result_goods['alarmnum']);

			$attr_array = $this->getClassAttr($Result_goods);//商品分類中的擴展屬性

			$saleoff_array = $this->checkSaleOff($Result_goods);//整點促銷1
			$Result_goods['saleoff'] = $saleoff_array;
			$timesale_array = $this->checkTimeSale($Result_goods);//整點促銷2
			if(count($timesale_array)==0)
				$Result_goods['iftimesale'] = 0;
			$Result_goods['timesale'] = $timesale_array;
			$appoint_array = $this->checkAppoint($Result_goods);//預購商品
			$Result_goods['appoint'] = $appoint_array;
			if($_SESSION['user_id']>0){
				$member_price = $this->getMemberPrice($_SESSION['user_level'],$gid,0);
				$Result_goods['levelprice'] = number_format($member_price);
				$Result_goods['level_name'] = $_SESSION['userlevelname'];
			}
			if($Result_goods['if_monthprice']==1){
				$month = $Result_goods['month'];
				$month_array = explode(",",$month); //分期
				$i = 0;
				foreach($month_array as $k=>$v){
					$month_price_array[$i]['name'] = "分" . $v . "期";
					if($member_price>0)
						$month_price_array[$i]['price'] = number_format($member_price/$v,0);
					else
						$month_price_array[$i]['price'] = number_format($Result_goods['pricedesc']/$v,0);
					$i++;
				}
				$Result_goods['month_array'] = $month_price_array;
			}
			$Result_goods['price'] = number_format($Result_goods['price']);
			$Result_goods['pricedesc'] = number_format($Result_goods['pricedesc']);
			$Result_goods['color'] = $this->getProductColor($Result_goods,$gid);//商品顏色
			$Result_goods['size'] = $this->getProductSize($Result_goods);//商品尺寸
			if($Result_goods['ifsales']==1){
				$Result_goods['salesubject'] = $this->getSaleSubject($Result_goods['sale_subject']);//活動主題
			}
			$Result_goods['discountsubject'] = $this->getDiscountSubject($gid);//折扣主題
			$Result_goods['redgreensubject'] = $this->getRedGreenSubject($gid);
			$Result_goods['changegoods'] = $this->getChangeProduct($gid);//加購商品
			$Result_goods['redgreengoods'] = $this->getRedGreenProduct($gid);//加購商品
			$Result_goods['packgoods'] = $this->getPackProduct($gid);//加購商品
			$Result_goods['buymore'] = $this->getBuyMore($gid);//買越多
			$Result_goods['productdetail'] = $this->getProductDetail($gid);//詳細規格
			$Result_goods['JS'] = $this->checkJS($Result_goods);//擊殺
			$Result_goods['XY'] = $this->getXyProduct($gid);//超值任選
			$Result_goods['ClassField'] = $this->getClassAttr($Result_goods);//分類上的擴展字段
			$Result_goods['Attr'] = $this->getAttributeClass($Result_goods['bid'],$gid);//產品分類屬性
			$Result_goods['attrs'] = $this->getAttributeGoods($Result_goods['gid']);

			if($_SESSION['user_id']>0){
				$collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$gid. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
				$collection_Query    = $DB->query($collection_sql);
				$collection_Num   = $DB->num_rows($collection_Query);
				if($collection_Num>0){
					$Result_goods['heartColor'] = 1;
				}
			}else{
				$Result_goods['heartColor'] = 0;
			}

		}
		$Result_goods['bname'] = $bname;
		return $Result_goods;
	}

	/**
	是否為商店街商品
	**/
	function CheckIfShopProduct($gid){
		global $DB,$INFO;
		$result = array();
		$gSql = "select g.shopid,s.shopname from `{$INFO[DBPrefix]}goods` as g inner join `{$INFO[DBPrefix]}shopinfo` as s on g.shopid=s.sid  where g.gid=".$gid." ";
		$gQuery   = $DB->query($gSql);
		$Result_g = $DB->fetch_array($gQuery);
		$result['shopid'] = intval($Result_g[shopid]);
		$result['shopname'] = trim($Result_g[shopname]);
		return $result;
	}

	/**
	得到商品列表
	**/
	function getProductList($bid=0,$type="",$search_array=array(),$orderby = array(),$showcount=0,$ifpage=0,$ifshowcolor=0,$ifshowmemberprice=0,$ifshowsale=0,$pagetype="1"){
		global $DB,$INFO,$FUNCTIONS;

		if($ifpage==1){
			if($type=="phone"){
				include_once("PageNav.class_phone.php");
				$type = $_GET['type'];
			}elseif($pagetype==1)
				include_once("PageNav_ajax.class.php");
			else
				include_once("PageNav.class.php");
		}

		$goods_array = array();
		$goods_color_array = array();
		$goods_size_array = array();
		$goods_count_array = array();
		if (is_array($_COOKIE['mangoods'][$bid])){
			$goods_array = $_COOKIE['mangoods'][$bid];
			$goods_color_array = $_COOKIE['mangoods_color'][$bid];
			$goods_size_array = $_COOKIE['mangoods_size'][$bid];
			$goods_count_array = $_COOKIE['mangoods_count'][$bid];
		}
		/*
		if($bid>0){
			if($_GET['SearchSub']==1 || $_GET['Action']!="Search" ){
				$this->Sun_pcon_class($bid); //子分類ID數組
				$Next_Array  = $this->bid_array;
				if(is_array($Next_Array)){
					foreach ($Next_Array as $k=>$v){
						$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
						$Add2 .= trim($v)!="" && intval($v)>0 ? " or gc.bid=".$v." " : "";
					}
				}
			}
			$gid_array = $this->getExtendClass($bid,$Add2);   //得到擴展分類對應的商品ID
			if (is_array($gid_array) && count($gid_array)>0){
				$gid_str = implode(",",$gid_array);
				$gid_sql_str = " or g.gid in (" . $gid_str . ")";
			}
			$bidSql = " and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ") ";
		}
		*/
		if(intval($_GET['brand_id'])>0){

				$bidSql = " and g.brand_id='".intval($_GET['brand_id'])."'";

		}
		$result_array = array();
		if($type == ""){
			$subSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 and g.ifgoodspresent!=1";
		}else{
			$nomalSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 and g.ifgoodspresent!=1";
			switch($type){
				case "bonus":
					$subSql = " and g.ifbonus='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 and g.ifgoodspresent!=1";
					break;
				case "xy":
					$subSql = " and g.ifbonus!='1' and g.ifxy=1 and ifchange!=1 and g.ifpresent!=1 and g.ifgoodspresent!=1";
					break;
				case "present":
					$subSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent=1 and g.ifgoodspresent!=1";
					break;
				case "change":
					$subSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange=1 and g.ifpresent!=1" and g.ifgoodspresent!=1;
					break;
				case "js":
					$subSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 and g.ifjs=1 and g.ifgoodspresent!=1";
					break;
				case "recommend":
					$subSql = " and g.ifrecommend " . $nomalSql;
					break;
				case "hot":
					$subSql = " and g.ifhot " . $nomalSql;
					break;
				case "special":
					$subSql = " and g.ifspecial " . $nomalSql;
					break;
				case "new":
					$subSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 and g.ifgoodspresent!=1";
					break;
				case "attr":
					$subSql = "  and ag.valueid='" . intval($_GET['valueid']) . "'  ";
					$linkSql = " inner join `{$INFO[DBPrefix]}attributegoods` as ag on ag.gid=g.gid";
					break;
				default:
					$subSql =$nomalSql;
			}
		}
		if (count($_GET)>0){
			foreach($_GET as $k=>$v){
				switch($k){
					case "skey":
						if($v!=""){
							$v = $FUNCTIONS->strShiftSpace($v,1);
							$searchSql .= " and ( g.goodsname like '%".trim($v)."%' or  g.intro like '%".trim($v)."%' or  g.bn like '%".trim($v)."%' or  g.keywords like '%".$_GET['skey']."%' ) ";
						}
					break;
					case "gprice_start":
						if($v>0)
							$searchSql .= " and g.pricedesc >= '".intval($v)."'";
						break;
					case "gprice_end":
						if($v>0)
							$searchSql .= " and g.pricedesc <= '".intval($v)."'";
						break;
					case "gprice":
						$priceSql;
						if($v>0){
							$gprice_array = explode("-",$v);
							foreach($gprice_array as $k=>$value){
								$array = explode("_",$value);
								if(count($array) == 2){
									$priceSql[] = " g.pricedesc >= '".intval($array[0])."' and g.pricedesc <= '".intval($array[1])."' ";
								}else {
									if($array[0] == 999){
										$priceSql[] = " g.pricedesc <= '".intval($array[0])."' ";
									}else {
										$priceSql[] = " g.pricedesc >= '".intval($array[0])."' ";
									}
								}
							}
							$searchSql .= " and (" . implode("or",$priceSql) . ")";
						}
						break;
					case "Brand_id":
						if($v>0)
							$searchSql .= " and g.brand_id = '".intval($v)."'";
						break;
					case "attr2":
							$color_array = explode("-",$v);
							foreach($color_array as $k=>$value){
								$colorsql[$k] .= " (g.good_color = '".$value."' or g.good_color like '%,".$value."' or g.good_color like '".$value.",%' or g.good_color like '%,".$value.",%')";
							}
							$searchSql .= " and (" . implode("or",$colorsql) . ")";
						break;
					case "attr3":
							$size_array = explode("-",$v);
							foreach($size_array as $k=>$value){
								$sizesql[$k] .= " (g.good_size = '".$value."' or g.good_size like '%,".$value."' or g.good_size like '".$value.",%' or g.good_size like '%,".$value.",%')";
							}
							$searchSql .= " and (" . implode("or",$sizesql) . ")";
						break;
					case "bid":
							$cate_array = explode("-",$v);
							foreach($cate_array as $k=>$value){
								if($value>0)
									$catesql[$k] .= " g.extendbid like '%\"".$value."\"%' or g.bid='" . $value . "'";
							}
							if(count($catesql)>0)
							$searchSql .= " and (" . implode("or",$catesql) . ")";
						break;
					case "brand_class":
							$cate_array = explode("-",$v);
							foreach($cate_array as $k=>$value){
								if($value>0)
									$catesql[$k] .= " g.brandbids like '%\"".$value."\"%' or g.brand_bid='" . $value . "'";
							}
							if(count($catesql)>0)
							$searchSql .= " and (" . implode("or",$catesql) . ")";
						break;
				}
				if(substr($k,0,4)=="attr" && $k!="attr2" && $k!="attr3"){
					$attrsql = array();
					$asql = array();
					$attr_array = explode("-",$v);
					foreach($attr_array as $k=>$value){
						$attrsql[$k] = " v.value = '".$value."' ";
					}
					$attrsql_str = implode(" or ",$attrsql);
					if($attrsql_str!=""){
						$attrvalues = array();
						$Sql_a      = "select * from `{$INFO[DBPrefix]}attributevalue` as v  where " . $attrsql_str . " order by valueid desc ";
						$Query_a    = $DB->query($Sql_a);
						$Num_a      = $DB->num_rows($Query_a);
						$j = 0;
						while ($Rs_a=$DB->fetch_array($Query_a)) {
							$attrvalues[$j] = $Rs_a['valueid'];
							$asql[$j] = "g.attributeclass like '%\"" . $Rs_a['valueid'] . "\"%'";
							$j++;
						}
						if($j>0)
							$subSql .= "  and (" . implode(" or ",$asql) . ")";
					}
				}
			}
		}
		if($type == "new"){
			//if($showcount==0)
			//	$showcount = 10;
			$orderby[0]="pubtime";
			$orderby[1]="0";
		}
		//print_r($showcount);
		if($showcount>0)
			$limitSql = " limit 0," . $showcount . " ";
		if($orderby[0]=="")
			$orderSql = " order by g.goodorder desc,g.storage desc,g.ifalarm asc,g.idate desc ";
		else{
			if ($orderby[0]=="price")
				$orderSql  = "  order by g.pricedesc ";
			elseif ($orderby[0]=="pubtime")
				$orderSql  = "  order by g.gid ";
			elseif ($orderby[0]=="visit")
				$orderSql  = "  order by g.view_num ";
			if(trim($orderSql) !=""){
				if ($orderby[1]=="1" ){
					$orderSql        .= " asc";
				}else{
					$orderSql        .= " desc";
				}
			}
		}

	 	  $Sql = "select g.*,br.brandname from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on ( g.brand_id=br.brand_id ) " . $linkSql . " where b.catiffb='1' and g.ifpub='1' and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') " . $searchSql . $subSql . $bidSql . $orderSql . " " . $limitSql;
		if($ifpage==1){
			$PageNav    = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
			//$PageNav    = new PageItem($Sql,2);
			$Num        = $PageNav->iTotal;
		}else{
			$Query  = $DB->query($Sql);
			$Num   = $DB->num_rows($Query);
		}
		
		$result_array['count'] = $Num;
		if ($Num>0){
			if($ifpage==1){
				$Query = $PageNav->ReadList();
			}
			$i = 0;
			while ( $Result = $DB->fetch_array($Query)){
				if (in_array(intval($Result['gid']),$goods_array)){
					foreach($goods_array as $k=>$v){
						if (intval($Result['gid']) == $v){
							$Result[$i]['count']        = $goods_count_array[$k] ;
							$Result[$i]['colors']        = $goods_color_array[$k] ;
							$Result[$i]['sizes']        = $goods_size_array[$k] ;
							$Result[$i]['buykey']    = $k;
						}
					}
				}
				if($Result['salename_color']!=""){
					$Result['sale_name'] = "<font color='" . $Result['salename_color'] . "'>" . $Result['sale_name'] . "</font>";
				}

				$Result['showstorage'] = $FUNCTIONS->Storage($Result['ifalarm'],$Result['storage'],$Result['alarmnum']);

				$result_array['info'][$i] = $Result;
				$result_array['info'][$i]['goodsname1'] = $FUNCTIONS->strUrlEncode($Result['goodsname']);

				if($ifshowcolor==1){
					$result_array['info'][$i]['color'] = $this->getProductColor($Result);//商品顏色
					$result_array['info'][$i]['size'] = $this->getProductSize($Result);//商品尺寸
				}
				$result_array['info'][$i]['attr'] = $this->getAttributeGoods($Result['gid']);
				$result_array['info'][$i]['pricedesc'] = number_format($Result['pricedesc']);
				$result_array['info'][$i]['price'] = number_format($Result['price']);
				if($ifshowsale==1){
					$saleoff_array = $this->checkSaleOff($Result);//整點促銷1
					$result_array['info'][$i]['saleoff'] = $saleoff_array;
				}
				$timesale_array = $this->checkTimeSale($Result);//整點促銷2
				$result_array['info'][$i]['timesale'] = $timesale_array;
				if($timesale_array['iftimesale']==1)
					$result_array['info'][$i]['pricedesc'] = $timesale_array['price'];
				$appoint_array = $this->checkAppoint($Result);//預購商品
				$result_array['info'][$i]['ifappoint'] = $appoint_array['ifappoint'] == 1 ? 1 : 0;
				$result_array['info'][$i]['productdetail'] = $this->getProductDetail($Result['gid']);//詳細規格
				if($_SESSION['user_id']>0 && $ifshowmemberprice==1){
					$member_price = $this->getMemberPrice($_SESSION['user_level'],$Result['gid'],0);
					$result_array['info'][$i]['levelprice'] = number_format($member_price);
				}

				$pic_array = $this->getProductPic($Result['gid']);
				$result_array['info'][$i]['smallimg2'] = $pic_array[1]['pic'];
				//print_r($result_array['info'][$i]['color']);
				if($Result['good_color']!=""){
					foreach($result_array['info'][$i]['color'] as $k=>$v){
						foreach($pic_array as $k1=>$v1){
							if($pic_array[$k1]['color'] == $result_array['info'][$i]['color'][$k]['color']){
								$pic_array[$k1]['sort'] = $result_array['info'][$i]['color'][$k]['sort'];
							}
						}
					}
				}
				$result_array['info'][$i]['pic_array'] = $pic_array;
				//print_r($result_array['info'][$i]['pic_array']);

				if($_SESSION['user_id']>0){
					$collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$Result['gid']. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
					$collection_Query    = $DB->query($collection_sql);
					$collection_Num   = $DB->num_rows($collection_Query);
					if($collection_Num>0){
						$result_array['info'][$i]['heartColor'] = 1;
					}
				}else{
					$result_array['info'][$i]['heartColor'] = 0;
				}

				$i++;
			}
			if($ifpage==1){
				$result_array['page'] = $PageNav->myPageItem();
			}
		}
		return $result_array;

	}

	/**
	得到商品分類列表
	**/
	function getClassList($bid,$ifshowproduct=0){
		global $DB,$INFO;
		$result_array = array();
		$Query  = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where top_id=".intval($bid)." ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$i = 0;
			while($Rs=$DB->fetch_array($Query)){
				$result_array[$i] = $Rs;
				if ($ifshowproduct==1){
					$product_array = $this->getProductList($Rs['bid'],"ifrecommend",array(),4,0,0,0,1);
					$result_array[$i]['product'] = $product_array['info'];
					$result_array[$i]['count'] = $product_array['count'];
				}
				$i++;
			}
		}
		return $result_array;
	}

	/**
	得到商品圖片
	**/
	function getProductPic($gid,$loop=1,$type=0){
		global $DB,$INFO;
		$Sql_pic    = "select middleimg,bigpic,goodpic_title,color,detail_name from `{$INFO[DBPrefix]}good_pic` where goodpic_name<>'' and type='" . $type  ."' and good_id='".intval($gid)."' order by orderby asc";
		$Query_pic  = $DB->query($Sql_pic);
		$Num_pic    = $DB->num_rows($Query_pic);
		$i = $loop;
		if ($Num_pic>0){
			while ($Result_pic = $DB->fetch_array($Query_pic))  {
				$Goodpic[$i]['pic'] =   $Result_pic['bigpic'];
				$Goodpic[$i]['title'] =   $Result_pic['goodpic_title'];
				$Goodpic[$i]['bigpic'] =   $Result_pic['bigpic'];
				$Goodpic[$i]['color'] =   $Result_pic['color'];
				$Goodpic[$i]['detail_name'] =   $Result_pic['detail_name'];
				$i++;
			}
		}
		return $Goodpic;
	}

	/**
	得到商品顏色列表
	**/
	function getProductColor($array,$Goods_id=0){
		global $DB,$INFO;
		if($array['good_color']!=""){
			$color_array = explode(",",$array['good_color']);
			$color_pic_array = explode(",",$array['good_color_pic']);
			$i = 0;
			foreach($color_array as $k=>$v){

				if($Goods_id>0){
					$goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where gid='" . $Goods_id . "' and color='" . $v . "'";
					$goods_Query =  $DB->query($goods_Sql);
					$goods_Num   =  $DB->num_rows($goods_Query );
					if ($goods_Num>0){
						$goods_Rs = $DB->fetch_array($goods_Query);
						$result_array[$i]['bn'] = $goods_Rs['goodsno'];
						$result_array[$i]['guojima'] = $goods_Rs['guojima'];
						$result_array[$i]['orgno'] = $goods_Rs['orgno'];
					}
				}

				$result_array[$i]['sort'] = "color".$i;
				$result_array[$i]['color'] = $v;
				if(substr($color_pic_array[$k],0,1)=="#")
					$result_array[$i]['colorcode'] = $color_pic_array[$k];
				else
					$result_array[$i]['pic'] = $color_pic_array[$k];
				$i++;
			}

		}
		return $result_array;
	}

	/**
	得到商品尺寸列表
	**/
	function getProductSize($array){
		if($array['good_size']!=""){
			$size_array = explode(",",$array['good_size']);
			$i = 0;
			foreach($size_array as $k=>$v){
				$result_array[$i]['size'] = $v;
				$i++;
			}
		}
		return $result_array;
	}

	/**
	得到商品屬性顏色圖片
	**/
	function getProductColorPic(){

	}

	/**
	得到折扣主題活動信息
	**/
	function getSaleSubject($subject_id){
		global $DB,$INFO;
		$result = array();
		if (intval($subject_id)>0){
			$Query_s = $DB->query("select * from  `{$INFO[DBPrefix]}sale_subject`  where subject_id='".$subject_id."' and subject_open=1  limit 0,1");
			$Rs_s    = $DB->fetch_array($Query_s);
			$result = $Rs_s;
		}
		return $result;
	}

	/**
	商品分類擴展屬性
	**/
	function getClassAttr($attr_array){
		if ($attr_array['attr']!=""){
			$attrI        =  $attr_array['attr'];
			$goods_attrI  =  $attr_array['goodattr'];
			$Attr         =  explode(',',$attrI);
			$Goods_Attr   =  explode(',',$goods_attrI);
			$Attr_num=  count($Attr);
		}else{
			$Attr_num=0;
		}
		if (is_array($Attr) && intval($Attr_num)>0 ){
			$AttrArray = array();
			$ProductArray = array();
			$j = 0;
			for($i=0;$i<$Attr_num;$i++){
				if ($Goods_Attr[$i]!=""){
					$AttrArray[$j]        = $Attr[$i];
					$ProductArray[$j]     = $Goods_Attr[$i];
					$ProductAttrArray[$j] = array($Attr[$i]=>$Goods_Attr[$i]);
					$ProductAttrArray[$j]['Fieldname'] = $Attr[$i];
					$ProductAttrArray[$j]['Fieldvalue'] = $Goods_Attr[$i];
					$j++;
				}
			}
		}
		return $ProductAttrArray;
	}

	/**
	判斷商品預購
	**/
	function checkAppoint($appoint_array){
		$result_array = array();
		$result_array['startdate']  = $appoint_array['appoint_starttime']==""?"":date("Y-m-d H:i",$appoint_array['appoint_starttime']);
		$result_array['enddate']  = $appoint_array['appoint_endtime']==""?"":date("Y-m-d H:i",$appoint_array['appoint_endtime']);
		if($appoint_array['appoint_sendtype']==1 || $appoint_array['appoint_sendtype']==0){
			$result_array['sendtime']  = "下單後" . $appoint_array['appoint_send'] . "天才出貨";
		}elseif($appoint_array['appoint_sendtype']==2){
			$result_array['sendtime']  = "活動截止後" . $appoint_array['appoint_send'] . "天才出貨";
		}elseif($appoint_array['appoint_sendtype']==3){
			$result_array['sendtime']  = "廠商自行與客戶聯絡";
		}
		if ($appoint_array['ifappoint']==1 && $appoint_array['appoint_starttime']<=time() && $appoint_array['appoint_endtime']>=time()){
			$result_array['ifappoint']  = 1;
		}elseif ($appoint_array['ifappoint']==1 && $appoint_array['appoint_endtime']<time()){
			$result_array['ifappoint']  = 2;
		}elseif ($appoint_array['ifappoint']==1 && $appoint_array['appoint_starttime']>time()){
			$result_array['ifappoint']  = 2;
		}
		return $result_array;
	}

	/**
	判斷整點促銷
	**/
	function checkSaleOff($saleoff_array){
		$result_array = array();
		$result_array['startdate']  = $saleoff_array['saleoff_starttime']==""?"":date("Y/m/d H:i",$saleoff_array['saleoff_starttime']);
		$result_array['enddate']  = $saleoff_array['saleoff_endtime']==""?"":date("Y/m/d H:i",$saleoff_array['saleoff_endtime']);
		if ($saleoff_array['ifsaleoff']==1 && $saleoff_array['saleoff_starttime']<=time() && $saleoff_array['saleoff_endtime']>=time()){
			$result_array['ifsaleoff']  = 1;
		}elseif ($saleoff_array['ifsaleoff']==1){
			$result_array['ifsaleoff']  = 0;
			if ($saleoff_array['saleoff_starttime']!=""){
				if (time()<$saleoff_array['saleoff_starttime']){
					$result_array['havebuytime'] = $saleoff_array['saleoff_starttime']-time();//還有多少秒到促銷時間可以購買
				}
			}
			if ($saleoff_array['saleoff_endtime']!=""){
				if (time()>$saleoff_array['saleoff_endtime']){
					$result_array['nobuytime'] = 1;	//促銷過期
				}
			}
		}
		return $result_array;
	}

	/**
	判斷整點促銷2
	**/
	function checkTimeSale($timesale_array){
		$result_array = array();
		$result_array['startdate']  = $timesale_array['timesale_starttime']==""?"":date("Y/m/d H:i",$timesale_array['timesale_starttime']);
		$result_array['enddate']  = $timesale_array['timesale_endtime']==""?"":date("Y/m/d H:i",$timesale_array['timesale_endtime']);
		if ($timesale_array['iftimesale']==1 && $timesale_array['timesale_starttime']<=time() && $timesale_array['timesale_endtime']>=time()){
			$result_array['price']  = number_format($timesale_array['saleoffprice']);//到促銷時間購買折扣價格
			$result_array['iftimesale']  = 1;
		}else{
			$result_array['iftimesale']  = 0;
			return array();
		}
		if ($timesale_array['timesale_starttime']!=""){
				if (time()<$timesale_array['timesale_starttime']){
					$result_array['havebuytime'] = $timesale_array['timesale_starttime']-time();//還有多少秒到促銷時間可以購買
				}
			}
			if ($timesale_array['timesale_endtime']!=""){
				if (time()>$timesale_array['timesale_endtime']){
					$result_array['nobuytime'] = 1;	//促銷過期
				}
			}
		return $result_array;
	}

	/**
	判斷擊殺
	**/
	function checkJS($js_array){
		$result_array = array();
		$cur_date = date("Y-m-d",time());
		$result_array['ifjs'] =  intval($js_array['ifjs']);
		$result_array['js_begtime']    =  trim($js_array['js_begtime']);
		$result_array['js_endtime']   =  trim($js_array['js_endtime']);
		$result_array['js_price']   =  explode("||",trim($js_array['js_price']));
		$result_array['js_totalnum'] =  intval($js_array['js_totalnum']);
		$result_array['jscount']   =  explode("||",trim($js_array['jscount']));
		if(intval($js_array['ifjs'])==1){
			if (!($js_array['js_begtime']<=$cur_date && $js_array['js_endtime']>=$cur_date)){
				$result_array['ifjsover'] = 1;
				$result_array['DoAction'] = "No";
			}else{
				$result_array['DoAction'] = "Yes";
			}
			$TotalNum = 0;
			foreach ($result_array['js_price'] as $k=>$v){
				$TotalNum = $TotalNum+$v;
			}
			$i = 0;
			foreach ($result_array['js_price'] as $k=>$v){
				if (intval($v)>0){
					$result_array['Js_open'][$i]['js_num']       =  $v;
					$result_array['Js_open'][$i]['js_count']       =  $result_array['jscount'][$k];
					$result_array['Js_open'][$i]['js_percent']   =  round(intval($v)/intval($TotalNum)*100,1);
					$result_array['Js_open'][$i]['js_height']    =  intval($v)/intval($TotalNum);
				}
				$i++;
			}
		}
		return $result_array;
	}

	/**
	瀏覽次數
	**/
	function setProductView($gid){
		global $DB,$INFO;
		$DB->query("update `{$INFO[DBPrefix]}goods` set view_num=view_num+1 where gid=".intval($gid));
	}

	/**
	商品配送特殊配送方式
	**/
	function getProductSpecialTrans($trans_special){
		global $DB,$INFO;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}transportation_special` where trid=".intval($trans_special)." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result = $DB->fetch_array($Query);
		}
		return $Result;
	}

	/**
	商品分類
	**/
	function getBanner($bid){
		global $DB,$INFO,$class_banner,$list,$Bcontent;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result     =  $DB->fetch_array($Query);
			$class_banner[$list]['bid'] = $Result['bid'];
			$class_banner[$list]['catname'] = $Result['catname'];
			$class_banner[$list]['banner'] = $Result['banner'];
			$list++;
			if ($Result['top_id']>0)
				$this->getBanner($Result['top_id']);
			else
				$Bcontent = $Result['catcontent'];
		}
	}
	/**
	商品分類
	**/
	function getTopBidList($bid){
		global $DB,$INFO,$class_banner,$list,$Bcontent;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result     =  $DB->fetch_array($Query);
			$class_banner[$list]['bid'] = $Result['bid'];
			$list++;
			if ($Result['top_id']>0)
				$this->getTopBidList($Result['top_id']);
		}
	}
	/**
	商品分類
	**/
	function getTopBrandBidList($bid){
		global $DB,$INFO,$class_banner,$list,$Bcontent;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand_class` where bid=".intval($bid)." limit 0,1 ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result     =  $DB->fetch_array($Query);
			$class_banner[$list]['bid'] = $Result['bid'];
			$class_banner[$list]['catname'] = $Result['catname'];
			$list++;
			if ($Result['top_id']>0)
				$this->getTopBrandBidList($Result['top_id']);
		}
	}

	/**
	商品分類屬性
	**/
	function getAttributeClass($bid,$gid){
		global $DB,$INFO;
		$class_sql = "select ac.*,a.attributename from `{$INFO[DBPrefix]}attributeclass` as ac left join `{$INFO[DBPrefix]}attribute` as a on ac.attrid=a.attrid where cid='" . intval($bid) . "'";
		$Query_class    = $DB->query($class_sql);
		$i = 0;
		while($Rs_class=$DB->fetch_array($Query_class)){
			$goods_sql = "select * from `{$INFO[DBPrefix]}attributegoods` as ag inner join `{$INFO[DBPrefix]}attributevalue` as av on ag.valueid=av.valueid where ag.gid='" . intval($gid) . "' and av.attrid='" . $Rs_class['attrid'] . "'";
			$Query_goods    = $DB->query($goods_sql);
			$Num   = $DB->num_rows($Query_goods);
			if ($Num>0){
				$attr_goods[$i]['attributename']=$Rs_class['attributename'];
				$attr_goods[$i]['attrid']=$Rs_class['attrid'];
				$ig = 0;
				while($Rs_goods=$DB->fetch_array($Query_goods)){
					$attr_goods[$i]['value'][$ig]['valueid']=$Rs_goods['valueid'];
					$attr_goods[$i]['value'][$ig]['value']=$Rs_goods['value'];
					$ig++;
				}

				$i++;
			}
		}
		return $attr_goods;
	}

	/**
	相關屬性
	**/
	function getLikeAttribute($bid,$new_array=array()){
		global $DB,$INFO;
		//if($key!="")
			//$subSql = "where v.value like '%" . trim($key) . "%'";
		$attrvalue_array = array();
		$Sql = "select ac.*,a.attributename from `{$INFO[DBPrefix]}attributeclass` as ac left join `{$INFO[DBPrefix]}attribute` as a on ac.attrid=a.attrid where cid='" . intval($bid) . "' order by a.sort asc";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		$i = 0;
		while ($Rs=$DB->fetch_array($Query)) {
			$attrvalue_array[$i]['attributename'] = $Rs['attributename'];
			$attrvalue_array[$i]['attrid'] = $Rs['attrid'];
			$j = 0;
			$Sql_a      = "select * from `{$INFO[DBPrefix]}attributevalue` as v  where v.attrid='" . $Rs['attrid'] . "' order by value asc ";
			$Query_a    = $DB->query($Sql_a);
			$Num_a      = $DB->num_rows($Query_a);
			$attrvalue_array[$i]['num'] = intval($Num_a);
			while ($Rs_a=$DB->fetch_array($Query_a)) {
				$attrvalue_array[$i]['v'][$j]['content'] = $Rs_a['content'];
				$attrvalue_array[$i]['v'][$j]['value'] = $Rs_a['value'];
				$attrvalue_array[$i]['v'][$j]['attrid'] = $Rs_a['attrid'];
				$attrvalue_array[$i]['v'][$j]['valueid'] = $Rs_a['valueid'];
				if(is_array($new_array)){
					if(in_array($Rs_a['valueid'],$new_array)){
						$attrvalue_array[$i]['v'][$j]['selected'] = 1;
					}
				}
				$j++;
			}
			$i++;

		}
		return $attrvalue_array;
	}

	function getLikeAttributeGoods($goods_array){
		global $DB,$INFO;
		$attrvalue_array = array();

		$goods_str = "(".implode(",",$goods_array).")";
		if(implode(",",$goods_array)!=""){
		$Sql = "select a.attrid,a.attributename from `{$INFO[DBPrefix]}attributegoods` as ag right join `{$INFO[DBPrefix]}attributevalue` as av on ag.valueid=av.valueid right join `{$INFO[DBPrefix]}attribute` as a on av.attrid=a.attrid where ag.gid IN" . $goods_str . " group by a.attrid order by a.attrid asc";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		$i = 0;
		while ($Rs=$DB->fetch_array($Query)) {
			$attrvalue_array[$i]['attributename'] = $Rs['attributename'];
			$attrvalue_array[$i]['attrid'] = $Rs['attrid'];
			$attrvalue_array[$i]['valueid'] = $Rs['valueid'];
			$j = 0;
			$Sql_a      = "select * from `{$INFO[DBPrefix]}attributevalue` as av left join `{$INFO[DBPrefix]}attributegoods` as ag on av.valueid=ag.valueid where av.attrid='" . $Rs['attrid'] . "' and ag.gid IN" . $goods_str . " group by av.valueid order by av.valueid desc ";
			$Query_a    = $DB->query($Sql_a);
			$Num_a      = $DB->num_rows($Query_a);
			while ($Rs_a=$DB->fetch_array($Query_a)) {
				$attrvalue_array[$i]['v'][$j]['value'] = $Rs_a['value'];
				$attrvalue_array[$i]['v'][$j]['attrid'] = $Rs_a['attrid'];
				$attrvalue_array[$i]['v'][$j]['valueid'] = $Rs_a['valueid'];
				$j++;
			}
			$i++;

		}
		return $attrvalue_array;
		}
	}
	function getAttributeGoods($gid){
		global $DB,$INFO;
		$attrvalue_array = array();

		$Sql = "select av.value from `{$INFO[DBPrefix]}attributegoods` as ag right join `{$INFO[DBPrefix]}attributevalue` as av on ag.valueid=av.valueid  where ag.gid ='" . $gid . "' and av.attrid=6";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		$i = 0;
		while ($Rs=$DB->fetch_array($Query)) {
			$attrvalue_array[$i]['value'] = $Rs['value'];
			$i++;

		}
		return $attrvalue_array;

	}
	/**
	商品TAG
	**/
	function getProductTag($gid){
		global $DB,$INFO;
		$tag_sql = "select * from `{$INFO[DBPrefix]}goods_tag` as at inner join `{$INFO[DBPrefix]}tag` as t on at.tagid = t.tagid where at.gid='" . intval($gid) . "'";
		$Query_tag= $DB->query($tag_sql);
		$ig = 0;
		while($Rs_tag=$DB->fetch_array($Query_tag)){
		  $tag_goods[$ig]['tagid']=$Rs_tag['tagid'];
		  $tag_goods[$ig]['tagname']=$Rs_tag['tagname'];
		  $ig++;
		}
		return $tag_goods;
	}

	/**
	最新商品
	**/
	function getNewProduct(){
		global $DB,$INFO,$FUNCTIONS;
		$MaxNewProductNum = intval($INFO['MaxNewProductNum'])>0 ?  intval($INFO['MaxNewProductNum']) : 12;
		$Sql = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.pricedesc,g.salename_color,g.intro,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.ifappoint,g.appoint_starttime,g.appoint_endtime,g.brand_id,ifnew from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid) where b.catiffb=1 and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.shopid=0 and g.ifbonus!=1 and g.ifpresent!=1 and g.ifxy!=1 and b.catiffb=1 and ifchange!=1 and g.ifgoodspresent!=1  order by g.gid desc  limit 0,".$MaxNewProductNum;
		$Query =    $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$j=0;
		$NewRs_productarray_level = array();
		while ( $NewPro = $DB->fetch_array($Query)){
					$New_productarray[$j][gid] = $NewPro['gid'];
					$New_productarray[$j][ifnew] = $NewPro['ifnew'];
					$New_productarray[$j][countj] = $j+1;
					$New_productarray[$j][goodsname] = $NewPro['goodsname'];
					$New_productarray[$j][showstorage] = $FUNCTIONS->Storage($NewPro['ifalarm'],$NewPro['storage'],$NewPro['alarmnum']);
					$New_productarray[$j][price] = number_format($NewPro['price']);
					$New_productarray[$j][pricedesc] = number_format($NewPro['pricedesc']);
					$New_productarray[$j][bn] = $NewPro['bn'];
					$New_productarray[$j][sale_name] = $NewPro['salename_color']==""?$NewPro['sale_name']:"<font color='" . $NewPro['salename_color'] . "'>" . $NewPro['sale_name'] . "</font>";
					//$New_productarray[$j][sale_name] = $NewPro['sale_name'];
					$New_productarray[$j][smallimg] = $NewPro['smallimg'];
					$New_productarray[$j]['attr'] = $this->getAttributeGoods($NewPro['gid']);

					if ($NewPro['ifappoint']==1 && $NewPro['appoint_starttime']<=time() && $NewPro['appoint_endtime']>=time()){
				    	$New_productarray[$j]['ifappoint']  = 1;
					}
					if ($NewPro['iftimesale']==1 && $NewPro['timesale_starttime']<=time() && $NewPro['timesale_endtime']>=time()){
						$New_productarray[$j][pricedesc]  = number_format($NewPro['saleoffprice']);
						$New_productarray[$j]['ifsaleoff']  = 1;
					}
					if ($NewPro['ifsaleoff']==1 && $NewPro['saleoff_starttime']<=time() && $NewPro['saleoff_endtime']>=time()){
						$New_productarray[$j]['ifsaleoff']  = 1;
					}

					if($_SESSION['user_id']>0){
						$collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$NewPro['gid']. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
						$collection_Query    = $DB->query($collection_sql);
						$collection_Num   = $DB->num_rows($collection_Query);
						if($collection_Num>0){
							$New_productarray[$j]['heartColor'] = 1;
						}
					}else{
						$New_productarray[$j]['heartColor'] = 0;
					}

					$New_productarray[$j]['productdetail'] = $this->getProductDetail($NewPro['gid']);//詳細規格

					$New_br = $NewPro['brand_id'];
					$New_Sql = "select brandname,brand_id from `{$INFO[DBPrefix]}brand` where brand_id=".$New_br;
					$New_Query   = $DB->query($New_Sql);
					$h = 0;
					while ( $New_Result = $DB->fetch_array($New_Query)){
						$New_productarray[$j]['br'][$h]['brand_id']    =  $New_Result['brand_id'];
						$New_productarray[$j]['br'][$h]['brandname']   =  $New_Result['brandname'];
						$h++;
					}
					$j++;
		}
		return $New_productarray;
	}

	/**
	推荐商品
	**/
	function getRecProduct(){
		global $DB,$INFO,$FUNCTIONS;
		$Sql = "select g.gid,g.goodsname,g.ERP,g.price,g.bn,g.smallimg,g.pricedesc,g.salename_color,g.intro ,g.alarmnum,g.storage,g.ifalarm,g.middleimg,g.bigimg,g.gimg,g.js_begtime,g.js_endtime,g.ifjs,b.bid,g.sale_name,g.ifxygoods,g.iftimesale,g.timesale_starttime,g.timesale_endtime,g.saleoffprice,g.ifalarm,g.storage,g.ifsaleoff,g.saleoff_starttime,g.saleoff_endtime,g.ifappoint,g.appoint_starttime,g.appoint_endtime,g.brand_id  from `{$INFO[DBPrefix]}bclass` b left join `{$INFO[DBPrefix]}goods` g on ( g.bid=b.bid) where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifrecommend=1 and g.ifbonus!=1 and g.ifpresent!=1 and g.ifxy!=1 and ifchange!=1 and g.ifxy!=1 and b.catiffb=1 and g.ifgoodspresent!=1 order by g.goodorder asc,g.idate desc limit 0,12";

		$Query =    $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$j=0;
		while ( $RecPro = $DB->fetch_array($Query)){
				$Recommendation_productarray[$j][gid] = $RecPro['gid'];
				$Recommendation_productarray[$j][ERP] = $RecPro['ERP'];
				$Recommendation_productarray[$j][countj] = $j+1;
				$Recommendation_productarray[$j][goodsname] = $RecPro['goodsname']."<br>".$FUNCTIONS->Storage($RecPro['ifalarm'],$RecPro['storage'],$RecPro['alarmnum']);
				$Recommendation_productarray[$j][price] = number_format($RecPro['price']);
				$Recommendation_productarray[$j][sale_name] = $RecPro['salename_color']==""?$RecPro['sale_name']:"<font color='" . $RecPro['salename_color'] . "'>" . $RecPro['sale_name'] . "</font>";
				$Recommendation_productarray[$j][pricedesc] = number_format($RecPro['pricedesc']);
				$Recommendation_productarray[$j][bn] = $RecPro['bn'];
				$Recommendation_productarray[$j][smallimg] = $RecPro['smallimg'];

				if ($RecPro['ifappoint']==1 && $RecPro['appoint_starttime']<=time() && $RecPro['appoint_endtime']>=time()){
				$Recommendation_productarray[$j]['ifappoint']  = 1;
				}
				if ($RecPro['iftimesale']==1 && $RecPro['timesale_starttime']<=time() && $RecPro['timesale_endtime']>=time()){
					$Recommendation_productarray[$j][pricedesc]  = number_format($RecPro['saleoffprice']);
					$Recommendation_productarray[$j]['ifsaleoff']  = 1;
				}
				if ($RecPro['ifsaleoff']==1 && $RecPro['saleoff_starttime']<=time() && $RecPro['saleoff_endtime']>=time()){
							$Recommendation_productarray[$j]['ifsaleoff']  = 1;
				}
				if($_SESSION['user_id']>0){
					$collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$RecPro['gid']. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
					$collection_Query    = $DB->query($collection_sql);
					$collection_Num   = $DB->num_rows($collection_Query);
					if($collection_Num>0){
						$Recommendation_productarray[$j]['heartColor'] = 1;
					}
				}else{
					$Recommendation_productarray[$j]['heartColor'] = 0;
				}

				$Recommendation_productarray[$j]['productdetail'] = $this->getProductDetail($RecPro['gid']);//詳細規格

				$Rec_br = $RecPro['brand_id'];
					$Rec_Sql = "select brandname,brand_id from `{$INFO[DBPrefix]}brand` where brand_id=".$Rec_br;
					$Rec_Query   = $DB->query($Rec_Sql);
					$h = 0;
					while ( $Rec_Result = $DB->fetch_array($Rec_Query)){
						$Recommendation_productarray[$j]['br'][$h]['brand_id']    =  $Rec_Result['brand_id'];
						$Recommendation_productarray[$j]['br'][$h]['brandname']   =  $Rec_Result['brandname'];
						$h++;
					}
				$j++;
		}
		return $Recommendation_productarray;
	}

	/**
	相關商品
	**/
	function getProductLink($gid,$bid,$ifgl=1){
		global $DB,$INFO,$FUNCTIONS;
		if (intval($ifgl)==1){ //判断是否是指定了产品内容，如果没有就把本类产品资料都调出来
			$Sql   = "select g.ERP,g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc,gl.s_gid,g.sale_name,g.storage,g.brand_id from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}good_link` gl  on (g.gid=gl.s_gid) where g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifpresent!=1 and g.ifbonus!=1 and gl.p_gid=".$gid;
		}else{
			$Sql   = "select g.ERP,g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.pricedesc,g.intro,g.sale_name,g.storage,g.brand_id from `{$INFO[DBPrefix]}goods` g where g.bid=".$bid." and g.gid!=".$gid." and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifpresent!=1 and g.ifchange!=1 and g.ifxy!=1 and g.ifgoodspresent!=1 and g.ifbonus!=1 order by g.idate desc limit 0,5 ";
		}
		$Query = $DB->query($Sql);
		$i=1;
		$j=0;
		$abProductArray = array();
		while ($Rs =  $DB->fetch_array($Query)){
			$abProductArray[$j]['autonum']    = $i;
			$abProductArray[$j]['Bgcolor']    = $i%2==0 ?  "#FAFAFA" : 'white';
			$abProductArray[$j]['goodsname']  = $Rs['goodsname'];
			$abProductArray[$j]['sale_name']  = $Rs['sale_name'];
			$abProductArray[$j]['goodsname1'] = $FUNCTIONS->strUrlEncode($Rs['goodsname']);
			$abProductArray[$j]['gid']        = $Rs['gid'];
			$abProductArray[$j]['price']      = number_format($Rs['price']);
			$abProductArray[$j]['pricedesc']  = number_format($Rs['pricedesc']);
			$abProductArray[$j]['smallimg']   = $Rs['smallimg'];
			$abProductArray[$j]['middleimg']  = $Rs['middleimg'];
			$abProductArray[$j]['storage']  = $Rs['storage'];
			$abProductArray[$j]['intro']      = nl2br($Rs['intro']);
			$abProductArray[$j]['ERP']        = $Rs['ERP'];

			if($_SESSION['user_id']>0){
				$collection_sql = "select * from `{$INFO[DBPrefix]}collection_goods` as c where c.gid ='" .$Rs['gid']. "' and c.user_id=".intval($_SESSION['user_id'])." order by c.gid desc limit 0,1";
				$collection_Query    = $DB->query($collection_sql);
				$collection_Num   = $DB->num_rows($collection_Query);
				if($collection_Num>0){
					$abProductArray[$j]['heartColor'] = 1;
				}
			}else{
				$abProductArray[$j]['heartColor'] = 0;
			}
			$link_br = $Rs['brand_id'];
			$link_Sql = "select brandname,brand_id from `{$INFO[DBPrefix]}brand` where brand_id=".$link_br;
			$link_Query   = $DB->query($link_Sql);
			$h = 0;
			while ( $link_Result = $DB->fetch_array($link_Query)){
				$abProductArray[$j]['br'][$h]['brand_id']    =  $link_Result['brand_id'];
				$abProductArray[$j]['br'][$h]['brandname']   =  $link_Result['brandname'];
				$h++;
			}
			$i++;
			$j++;
		}
		return $abProductArray;
	}

	/**
	產品會員價格
	**/
	function getMemberPrice($level,$goods_id,$detail_id = 0){
		global $DB,$INFO;
		$Sql_M    = "select * from `{$INFO[DBPrefix]}member_price` where m_level_id=".intval($level)." and m_detail_id='" . $detail_id . "' and m_goods_id='" . intval($goods_id) . "' limit 0,1";
		$Query_M  = $DB->query($Sql_M);
		$Result_M = $DB->fetch_array($Query_M);
		$Nums_M      = $DB->num_rows($Query_M);
		if($Nums_M>0){
		   $price = $Result_M['m_price'];
		   return $price;
		}
		$Sql = " select pricerate from `{$INFO[DBPrefix]}user_level` where level_id=".intval($level)."  limit 0,1";
		$QueryMemberPrice = $DB->query($Sql);
		$NumMemberPrice   = $DB->num_rows($QueryMemberPrice);
		$ifxygoods = 0;
		if ($NumMemberPrice>0){
			$ResultMemberPrice= $DB->fetch_array($QueryMemberPrice);
			$pricerate = $ResultMemberPrice['pricerate'];
			if (intval($detail_id)>0){
				$gSql = " select detail_pricedes from `{$INFO[DBPrefix]}goods_detail` where gid=".intval($goods_id)." and detail_id='". intval($detail_id) ."'  limit 0,1";
				$Queryg = $DB->query($gSql);
				$Numg   = $DB->num_rows($Queryg);
				if ($Numg>0){
					$Resultg= $DB->fetch_array($Queryg);
					$pricedesc = $Resultg['detail_pricedes'];
				}
			}else{
				$gSql = " select pricedesc,ifxygoods from `{$INFO[DBPrefix]}goods` where gid=".intval($goods_id)."  limit 0,1";
				$Queryg = $DB->query($gSql);
				$Numg   = $DB->num_rows($Queryg);
				if ($Numg>0){
					$Resultg= $DB->fetch_array($Queryg);
					$pricedesc = $Resultg['pricedesc'];
					$ifxygoods = $Resultg['ifxygoods'];
				}
			}
			if ($pricerate>0 && $ifxygoods==0)
				$MemberLevelPrice = round($pricerate*0.01*$pricedesc,0);
			else
				$MemberLevelPrice = $pricedesc;

			return $MemberLevelPrice;
		}else{
			return '0';
		}
	}

	/**
	前/后一商品
	**/
	function getUporDown($gid,$bid,$bname,$type="up"){
		global $DB,$INFO,$FUNCTIONS;
		if($type=="down"){
			$flag = ">";
			$orderby = "asc";
		}else{
			$flag = "<";
			$orderby = "desc";
		}
		$Sql_Up =   "select g.gid,g.goodsname from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}" . $bname . "` b on ( g.bid=b.bid ) where  b.catiffb='1' and g.ifpub='1' and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifxy!=1 and g.ifpresent!=1 and g.ifchange!=1 and g.bid='" . $bid  ."' and g.gid" . $flag . "'" . $gid . "'  order by gid " . $orderby . " limit 0,1";
		$Query_Up   = $DB->query($Sql_Up);
		$Num_Up     = $DB->num_rows($Query_Up);
		if ( $Num_Up>0 ) {
			$Rs_Up    = $DB->fetch_array($Query_Up);
			$Rs_Up['goodsname1'] = $FUNCTIONS->strUrlEncode($Rs_Up['goodsname']);
			return $Rs_Up;
		}
	}

	/**
	商品詳細規格
	**/
	function getProductDetail($gid){
		global $DB,$INFO;
		$result_array = array();
		$Sql      = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . intval($gid) . "' order by detail_id desc ";

		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		$result_array['count'] = $Num;
		$i = 0;
		while ( $Detail_Rs = $DB->fetch_array($Query)){
			$result_array['info'][$i] = $Detail_Rs;
			$result_array['info'][$i]['sort'] = "detail".$Detail_Rs['detail_id'];
			if($_SESSION['user_id']>0){
				$member_price = $this->getMemberPrice($_SESSION['user_level'],$gid,$Detail_Rs['detail_id']);
			}
			$result_array['info'][$i]['member_price'] = intval($member_price)==0?$Detail_Rs['detail_pricedes']:$member_price;			$result_array['info'][$i]['member_price'] = number_format($result_array['info'][$i]['member_price']);
			$i++;
		}
		return $result_array;
	}

	/**
	超值任選商品
	**/
	function getXyProduct($gid){
		global $DB,$INFO;
		$xygoods_array = array();
		$i = 0;
		$Sql         = "select gl.* ,g.goodsname,g.bn,g.smallimg,g.good_color,g.good_size,g.storage,g.pricedesc from `{$INFO[DBPrefix]}goods_xy` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.xygid=g.gid) where gl.gid=".intval($gid)." and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') order by gl.idate desc ";
		$Query       = $DB->query($Sql);
		$Num         = $DB->num_rows($Query);
		while ($Result=$DB->fetch_array($Query)){
			$xygoods_array[$i] = $Result;
			$xygoods_array[$i]['color'] = $this->getProductColor($Result);//商品顏色
			$xygoods_array[$i]['size'] = $this->getProductSize($Result);//商品尺寸
			$i++;
		}
		return $xygoods_array;
	}

	/**
	加購商品
	**/
	function getChangeProduct($gid){
		global $DB,$INFO;
		$change_array = array();
		$i = 0;
		$Sql         = "select gl.* ,g.goodsname,g.bn,g.smallimg,g.good_color,g.good_size,g.ifchange,g.ifalarm,g.pricedesc from `{$INFO[DBPrefix]}goods_change` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.changegid=g.gid) where gl.gid=".intval($gid)." and g.ifchange=1 and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') order by gl.idate desc ";
		$Query       = $DB->query($Sql);
		$Num         = $DB->num_rows($Query);
		$change_array['count'] = $Num;
		while ($Result=$DB->fetch_array($Query)){
			$change_array['info'][$i] = $Result;
			$change_array['info'][$i]['price'] = number_format($Result['price']);
			$change_array['info'][$i]['pricedesc'] = number_format($Result['pricedesc']);
			$change_array['info'][$i]['color'] = $this->getProductColor($Result);//商品顏色
			$change_array['info'][$i]['size'] = $this->getProductSize($Result);//商品尺寸
			$i++;
		}
		return $change_array;
	}
	/**
	贈品
	**/
	function getPresentProduct($gid){
		global $DB,$INFO;
		$change_array = array();
		$i = 0;
		$Sql         = "select g.* from `{$INFO[DBPrefix]}goods_present` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.pregid=g.gid) where gl.gid=".intval($gid)." and g.ifgoodspresent=1 and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') order by gl.idate desc ";
		$Query       = $DB->query($Sql);
		$Num         = $DB->num_rows($Query);
		$change_array['count'] = $Num;
		while ($Result=$DB->fetch_array($Query)){
			$change_array['info'][$i] = $Result;
			$change_array['info'][$i]['pubstarttime'] = date("Y-m-d",$Result['pubstarttime']);
			$change_array['info'][$i]['pubendtime'] = date("Y-m-d",$Result['pubendtime']);
			//$change_array['info'][$i]['color'] = $this->getProductColor($Result);//商品顏色
			//$change_array['info'][$i]['size'] = $this->getProductSize($Result);//商品尺寸
			$i++;
		}
		return $change_array;
	}

	/**
	紅綠標商品
	**/
	function getRedGreenProduct($gid){
		global $DB,$INFO;
		//找出紅標的活動
		$Sql      = "select g.subject_name,dg.red_id,dg.cost,g.saleoff,g.rgid from `{$INFO[DBPrefix]}subject_redgoods` as dg inner join  `{$INFO[DBPrefix]}subject_redgreen` as g on dg.rgid=g.rgid where dg.gid='" . $gid . "' and g.start_date<='" . date("Y-m-d") . "' and g.end_date>='" . date("Y-m-d") . "' and g.subject_open=1 order by g.rgid limit 0,1";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		$redgreen_array = array();
		$i = 0;
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			//綠標商品
			$green_Sql      = "select dg.green_id,dg.cost,g.* from `{$INFO[DBPrefix]}subject_greengoods` as dg inner join  `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid where dg.rgid='" . intval($Result['rgid']) . "' order by g.gid";
			$green_Query    = $DB->query($green_Sql);
			$green_Num      = $DB->num_rows($green_Query);
			if ($green_Num>0){
				$redgreen_array['rgid'] = $Result['rgid'];
				$redgreen_array['subject_name'] = $Result['subject_name'];
				$redgreen_array['saleoff'] = $Result['saleoff'];
				$j = 0;

				while($green_Result= $DB->fetch_array($green_Query)){
					$redgreen_array['green'][$j]['gid'] = $green_Result['gid'];
					$redgreen_array['green'][$j]['pricedesc'] = $green_Result['pricedesc'];
					$redgreen_array['green'][$j]['sale_name'] = $green_Result['sale_name'];
					$redgreen_array['green'][$j]['goodsname'] = $green_Result['goodsname'];
					$redgreen_array['green'][$j]['smallimg'] = $green_Result['smallimg'];
					$redgreen_array['green'][$j]['good_color'] = $green_Result['good_color'];
					$redgreen_array['green'][$j]['good_size'] = $green_Result['good_size'];
					$redgreen_array['green'][$j]['saleprice'] = round($green_Result['pricedesc']*$Result['saleoff']/100,0);
					$redgreen_array['green'][$j]['color'] = $this->getProductColor($green_Result);//商品顏色
					$redgreen_array['green'][$j]['size'] = $this->getProductSize($green_Result);//商品尺寸
					$j++;
				}
				$redgreen_array['ifhave'] = 1;
			}
		}

		return $redgreen_array;
	}

	/**
	組合商品
	**/
	function getPackProduct($gid){
		global $DB,$INFO;
		$change_array = array();
		$i = 0;
		$Sql         = "select gl.* ,g.goodsname,g.bn,g.smallimg,g.good_color,g.good_size,g.ifchange,g.pricedesc as price from `{$INFO[DBPrefix]}goods_pack` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.packgid=g.gid) where gl.gid=".intval($gid)." and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') order by gl.idate desc ";
		$Query       = $DB->query($Sql);
		$Num         = $DB->num_rows($Query);
		$change_array['count'] = $Num;
		while ($Result=$DB->fetch_array($Query)){
			$change_array['info'][$i] = $Result;
			$change_array['info'][$i]['color'] = $this->getProductColor($Result);//商品顏色
			$change_array['info'][$i]['size'] = $this->getProductSize($Result);//商品尺寸
			$i++;
		}
		return $change_array;
	}

	/**
	買越多
	**/
	function getBuyMore($gid){
		global $DB,$INFO;
		$result_array = array();
		$Sql      = "select * from `{$INFO[DBPrefix]}goods_saleoffe` where gid='" . intval($gid) . "' order by mincount asc ";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		$i = 0;
		$result_array['count'] = $Num;
		while ($Rs=$DB->fetch_array($Query)) {
			$result_array['info'][$i] = $Rs;			$result_array['info'][$i]['price'] = number_format($Rs['price']);
			$i++;
		}
		return $result_array;
	}
/**
	折扣促銷主題
	**/
	function getRedGreenSubject($gid){
		global $DB,$INFO;
		$Sql      = "select * from `{$INFO[DBPrefix]}subject_redgoods` as dg inner join `{$INFO[DBPrefix]}subject_redgreen` as d on dg.rgid=d.rgid where dg.gid='" . intval($gid) . "' and d.subject_open=1 and d.start_date<='" . date("Y-m-d",time()) . "' and d.end_date>='" . date("Y-m-d",time()) . "' order by d.rgid ";
		$Query    = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$discount_array = array();
		$discount_array['count'] = $Num;
		$i = 0;
		while ($Result=$DB->fetch_array($Query)){
			$discount_array['info'][$i] = $Result;
			$i++;
		}
		$Sql      = "select * from `{$INFO[DBPrefix]}subject_greengoods` as dg inner join `{$INFO[DBPrefix]}subject_redgreen` as d on dg.rgid=d.rgid where dg.gid='" . intval($gid) . "' and d.subject_open=1 and d.start_date<='" . date("Y-m-d",time()) . "' and d.end_date>='" . date("Y-m-d",time()) . "' order by d.rgid ";
		$Query    = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$discount_array['count'] = $Num+$discount_array['count'];
		while ($Result=$DB->fetch_array($Query)){
			$discount_array['info'][$i] = $Result;
			$i++;
		}
		//print_r($discount_array);
		return $discount_array;
	}
	/**
	折扣促銷主題
	**/
	function getDiscountSubject($gid){
		global $DB,$INFO;
		$Sql      = "select * from `{$INFO[DBPrefix]}discountgoods` as dg inner join `{$INFO[DBPrefix]}discountsubject` as d on dg.dsid=d.dsid where dg.gid='" . intval($gid) . "' and d.subject_open=1 and d.start_date<='" . date("Y-m-d",time()) . "' and d.end_date>='" . date("Y-m-d",time()) . "' order by d.dsid ";
		$Query    = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$discount_array = array();
		$discount_array['count'] = $Num;
		$i = 0;
		while ($Result=$DB->fetch_array($Query)){
			$discount_array['info'][$i] = $Result;
			$i++;
		}
		return $discount_array;
	}

	/**
	得到商品庫存
	**/
	function checkStorage($gid,$detail_id=0,$color="",$size="",$ifshowopen=0){
		global $DB,$INFO;
		if ($gid>0){
			$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)."  limit 0,1");
			$Num   = $DB->num_rows($Query);
			if ($Num>0){
				$Result= $DB->fetch_array($Query);
				if ($Result['iftimesale']==1 && $Result['timesale_starttime']<=time() && $Result['timesale_endtime']>=time() && $Result['saleoffcount']>0){
					$storage = $Result['saleoffcount'];
				}else{

					$storage = $Result['storage'];
				}
				if($Result['ifalarm']==0 && $ifshowopen==1)
						return intval($INFO['buy_product_max_num']);
				/*整點促銷限制購買數量*/
				if($Result['ifpack']==1){

					$storage = "a";
					$Sql_p         = "select gl.* ,g.goodsname,g.bn,g.storage from `{$INFO[DBPrefix]}goods_pack` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.packgid=g.gid) where gl.gid=".intval($gid)." order by gl.idate desc ";
					$Query_p    = $DB->query($Sql_p);
					$Nums_p      = $DB->num_rows($Query_p);
					while($Rs_p=$DB->fetch_array($Query_p)){
						if($storage=="a" || $Rs_p['storage']<$storage)
							$storage = $Rs_p['storage'];
					}
					return $storage;
					exit;
				}
				if ($Result['storage']>0){
					if ($detail_id>0){
						$Sql_d = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $gid . "' and detail_id='" . $detail_id . "'";
						$Query_d    = $DB->query($Sql_d);
						$Nums_d      = $DB->num_rows($Query_d);
						if ($Nums_d>0){
							$Rs_d=$DB->fetch_array($Query_d);
							if($Rs_d['storage']<$storage)
								return $Rs_d['storage'];
							else
								return $storage;
						}else{
							return "0";
						}
					}elseif ($size!="" || $color!=""){
						$Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid) . " and size='" . $size . "' and color = '" . $color . "'";
						$Query_s    = $DB->query($Sql_s);
						$Nums_s      = $DB->num_rows($Query_s);
						if ($Nums_s>0){
							$Rs_s=$DB->fetch_array($Query_s);
							if($Rs_s['storage']<$storage)
								return $Rs_s['storage'];
							else
								return $storage;
						}else{
							return "0";
						}
					}else{
						return $storage;
					}
				}else{
					return "0";
				}
			}else{
				return "0";
			}

		}
		return "0";
	}

	/**
	得到下屬所有分類
	**/
	function getProductClass($bid,$ifrec=0,$ifsimple=1){
		global $DB,$INFO;
		$result_array = array();
		if($ifrec==1)
			$subsql = " and ifhome=1";
		$Sql_bclass    = "select * from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . intval($bid) . "'" . $subsql . " order by catord  asc    ";
		$query_bclass  = $DB->query($Sql_bclass);
		$num_bclass    = $DB->num_rows($query_bclass);
		$i=0;
		if ($num_bclass>0){
			while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
				if($ifsimple==1){
					$result_array[$i]['classname'] = $Rs_bclass['catname'];
					$result_array[$i]['catname'] = $Rs_bclass['catname'];
					$result_array[$i]['picture'] = $Rs_bclass['banner'];
					$result_array[$i]['bid'] = $Rs_bclass['bid'];
				}else{
					$result_array[$i] = $Rs_bclass;
				}
				$j=0;
				$Sql_bclass_2    = "select * from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $Rs_bclass['bid'] . "'" . $subsql . " order by catord  asc  ";
				$query_bclass_2  = $DB->query($Sql_bclass_2);
				$num_bclass_2    = $DB->num_rows($query_bclass_2);
				$result_array[$i]['num'] = $num_bclass_2;
				while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
					if($ifsimple==1){
						$result_array[$i]['sub'][$j]['classname'] = $Rs_bclass_2['catname'];
						$result_array[$i]['sub'][$j]['catname'] = $Rs_bclass_2['catname'];
						$result_array[$i]['sub'][$j]['picture'] = $Rs_bclass_2['banner'];
						$result_array[$i]['sub'][$j]['bid'] = $Rs_bclass_2['bid'];
					}else{
						$result_array[$i]['sub'][$j] = $Rs_bclass_2;
					}
					$z=0;
					$Sql_bclass_3    = "select bid,catname,pic1,pic2,manyunfei,subject_id,subject_id2 from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $Rs_bclass_2['bid'] . "'" . $subsql . " order by catord  asc  ";
					$query_bclass_3  = $DB->query($Sql_bclass_3);
					$num_bclass_3    = $DB->num_rows($query_bclass_3);
					$result_array[$i]['sub'][$j]['num'] = $num_bclass_3;
					while($Rs_bclass_3 =  $DB->fetch_array($query_bclass_3)){
						if($ifsimple==1){
							$result_array[$i]['sub'][$j]['sub'][$z]['classname'] = $Rs_bclass_3['catname'];
							$result_array[$i]['sub'][$j]['sub'][$z]['catname'] = $Rs_bclass_3['catname'];
							$result_array[$i]['sub'][$j]['sub'][$z]['picture'] = $Rs_bclass_3['banner'];
							$result_array[$i]['sub'][$j]['sub'][$z]['bid'] = $Rs_bclass_3['bid'];
						}else{
							$result_array[$i]['sub'][$j]['sub'][$z] = $Rs_bclass_3;
						}
						$z++;
					}
					$j++;
				}

				$i++;
			}
		}
		return $result_array;
	}

	/**
	得到品牌下屬所有分類
	**/
	function getBrandProductClass($brand_id,$ifrec=0,$ifsimple=1){
		global $DB,$INFO;
		$result_array = array();
		if($ifrec==1)
			$subsql = " and ifhome=1";
		$Sql_bclass    = "select * from `{$INFO[DBPrefix]}brand_class` where catiffb=1 and brand_id='" . intval($brand_id) . "' and top_id=0 " . $subsql . " order by catord  asc,bid asc ";
		$query_bclass  = $DB->query($Sql_bclass);
		$num_bclass    = $DB->num_rows($query_bclass);
		$i=0;
		if ($num_bclass>0){
			while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
				if($ifsimple==1){
					$result_array[$i]['classname'] = $Rs_bclass['catname'];
					$result_array[$i]['catname'] = $Rs_bclass['catname'];
					$result_array[$i]['picture'] = $Rs_bclass['banner'];
					$result_array[$i]['bid'] = $Rs_bclass['bid'];
					$result_array[$i]['url'] = $Rs_bclass['url'];
				}else{
					$result_array[$i] = $Rs_bclass;
				}
				$Sqlc = "select count(*) as totalcount from `{$INFO[DBPrefix]}goods` where ifpub='1' and (brandbids like '%\"".$Rs_bclass['bid']."\"%' or bid='" . $Rs_bclass['bid'] . "')";
				$Queryc  = $DB->query($Sqlc);
				$Resultc = $DB->fetch_array($Queryc);
				$result_array[$i]['totalcount'] = $Resultc['totalcount'];
				if($result_array[$i]['url'] != ''){
					$result_array[$i]['totalcount'] = 1;
				}
				$j=0;
				$Sql_bclass_2    = "select * from `{$INFO[DBPrefix]}brand_class` where catiffb=1 and top_id='" . $Rs_bclass['bid'] . "'" . $subsql . " order by catord  asc,bid asc";
				$query_bclass_2  = $DB->query($Sql_bclass_2);
				$num_bclass_2    = $DB->num_rows($query_bclass_2);
				$result_array[$i]['num'] = $num_bclass_2;
				while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
					if($ifsimple==1){
						$result_array[$i]['sub'][$j]['classname'] = $Rs_bclass_2['catname'];
						$result_array[$i]['sub'][$j]['catname'] = $Rs_bclass_2['catname'];
						$result_array[$i]['sub'][$j]['picture'] = $Rs_bclass_2['banner'];
						$result_array[$i]['sub'][$j]['bid'] = $Rs_bclass_2['bid'];
						$result_array[$i]['sub'][$j]['url'] = $Rs_bclass_2['url'];
					}else{
						$result_array[$i]['sub'][$j] = $Rs_bclass_2;
					}
					$Sqlc = "select count(*) as totalcount from `{$INFO[DBPrefix]}goods` where ifpub='1' and (brandbids like '%\"".$Rs_bclass_2['bid']."\"%' or bid='" . $Rs_bclass_2['bid'] . "')";
					$Queryc  = $DB->query($Sqlc);
					$Resultc = $DB->fetch_array($Queryc);
					$result_array[$i]['sub'][$j]['totalcount'] = $Resultc['totalcount'];
					if($result_array[$i]['sub'][$j]['url'] != ''){
						$result_array[$i]['totalcount'] = 1;
						$result_array[$i]['sub'][$j]['totalcount'] = 1;
					}
					$z=0;
					$Sql_bclass_3    = "select * from `{$INFO[DBPrefix]}brand_class` where catiffb=1 and top_id='" . $Rs_bclass_2['bid'] . "'" . $subsql . " order by catord  asc,bid asc";
					$query_bclass_3  = $DB->query($Sql_bclass_3);
					$num_bclass_3    = $DB->num_rows($query_bclass_3);
					$result_array[$i]['sub'][$j]['num'] = $num_bclass_3;
					while($Rs_bclass_3 =  $DB->fetch_array($query_bclass_3)){
						if($ifsimple==1){
							$result_array[$i]['sub'][$j]['sub'][$z]['classname'] = $Rs_bclass_3['catname'];
							$result_array[$i]['sub'][$j]['sub'][$z]['catname'] = $Rs_bclass_3['catname'];
							$result_array[$i]['sub'][$j]['sub'][$z]['picture'] = $Rs_bclass_3['banner'];
							$result_array[$i]['sub'][$j]['sub'][$z]['bid'] = $Rs_bclass_3['bid'];
							$result_array[$i]['sub'][$j]['sub'][$z]['url'] = $Rs_bclass_3['url'];
						}else{
							$result_array[$i]['sub'][$j]['sub'][$z] = $Rs_bclass_3;
						}
						$Sqlc = "select count(*) as totalcount from `{$INFO[DBPrefix]}goods` where ifpub='1' and (brandbids like '%\"".$Rs_bclass_3['bid']."\"%' or bid='" . $Rs_bclass_3['bid'] . "')";
						$Queryc  = $DB->query($Sqlc);
						$Resultc = $DB->fetch_array($Queryc);
						$result_array[$i]['sub'][$j]['sub'][$z]['totalcount'] = $Resultc['totalcount'];
						if($result_array[$i]['sub'][$j]['sub'][$z]['url'] != ''){
							$result_array[$i]['totalcount'] = 1;
							$result_array[$i]['sub'][$j]['totalcount'] = 1;
							$result_array[$i]['sub'][$j]['sub'][$z]['totalcount'] = 1;
						}
						$z++;
					}
					$j++;
				}

				$i++;
			}
		}
		return $result_array;
	}

	/**
	商品頂分類ID
	**/
	function getTopBid($bid,$levlel_id=0){
		global $DB,$INFO;
		//echo $levlel_id;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($bid)." limit 0,1 ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result     =  $DB->fetch_array($Query);
			if ($Result['top_id']>0){
				$this->getTopBid($Result['top_id'],$bid);
				return $Result['top_id'];
			}else{
				return $Result['bid'];
			}
		}else{
			return $levlel_id;
		}
	}

	/*获得产品下级子类的所有ID
	返回值是一个字符串
	*/
	function Sun_pcon_class($id,$bid_array = array())
	{
		global $DB,$INFO;
		$Query  = $DB->query("select bid from `{$INFO[DBPrefix]}bclass` where top_id=".intval($id)." ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			while($Rs=$DB->fetch_array($Query)){
				$this->bid_array[count($this->bid_array)] = $Rs['bid'];
				$this->Sun_pcon_class($Rs['bid'],$bid_array);
			}
			//return  $bid_array;
		}else{
			//return $bid_array;
		}

	}

	/**
	得到某分類信息
	**/
	function getClassInfo($bid){
		global $DB,$INFO;
		$Query   = $DB->query("select * from  `{$INFO[DBPrefix]}bclass` where catiffb=1 and bid=".intval($bid)." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ( $Num==0 ){ //如果不存在资料
			return 0;
		}else{
			$Rs=$DB->fetch_array($Query);
			if($Rs['saleoff_starttime']!="")
				$Rs['saleoff_starttime'] = date("Y-m-d H:i:s",$Rs['saleoff_starttime']);
			if($Rs['saleoff_endtime']!="")
				$Rs['saleoff_endtime'] = date("Y-m-d H:i:s",$Rs['saleoff_endtime']);
			if($Rs['rebate']%10==0)
				$Rs['rebate'] = $Rs['rebate']/10;
			if($Rs['meta_des']=="")
				$Rs['meta_des'] = $INFO['meta_desc'];
			if($Rs['meta_key']=="")
				$Rs['meta_key'] = $INFO['meta_keyword'];
			return $Rs;
		}
	}

	/**
	得到擴展分類下的商品
	**/
	function getExtendClass($bid,$subsql = ""){
		global $DB,$INFO;
		$gid_array = array();
		$extendsql = "select gc.gid from `{$INFO[DBPrefix]}goods_class` as gc where gc.bid ='" . intval($bid) . "' " . $subsql . "";
		$extend_query  = $DB->query($extendsql);
		$ei = 0;
		while($extend_rs = $DB->fetch_array($extend_query)){
			$gid_array[$ei] = $extend_rs['gid'];
			$ei++;
		}
		return $gid_array;
	}
	////////////////////////////////////手機版////////////////////////////////////////////
	/**
	得到商品聚合商品列表
	$type=0 TAG名稱，1聚合ID
	**/
	function getProductCollection($tag,$type="0"){

		global $DB,$INFO,$Char_class;
		if($type==0)
			$subsql = " where tag = '" . $tag . "'";
		elseif($type==1)
			$subsql = " where gc_id = '" . $tag . "'";
		$Sql = "select * from `{$INFO[DBPrefix]}goodscollection` " . $subsql . " limit 0,1";
		$Query    = $DB->query($Sql);
		 $Num   = $DB->num_rows($Query);
		$return_array = array();
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$return_array['gc_name'] = $Result['gc_name'];
			$return_array['gc_pic'] = $Result['gc_pic'];
			$return_array['gc_id'] = $Result['gc_id'];
			$return_array['gc_link'] = $Result['gc_link'];
			$return_array['gc_string'] = $Result['gc_string'];
			if($Result['gc_string']!=""){
				$gc_arrays = explode(",",$Result['gc_string']);
				$j = 0;
				foreach($gc_arrays as $k=>$v){
					 $goods_sql = "select * from `{$INFO[DBPrefix]}goods` as g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where g.gid ='" .$v. "' and g.ifpub='1' and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and g.ifxy!=1 and g.ifpresent!=1 and g.ifchange!=1 and g.ifgoodspresent!=1 and b.catiffb=1 order by g.gid desc ";
					$goods_Query    = $DB->query($goods_sql);
					$goods_Num   = $DB->num_rows($goods_Query);
					if($goods_Num>0){
						$goods_Rs=$DB->fetch_array($goods_Query);
						$return_array['goods'][$j]['goodsname'] = $goods_Rs['goodsname'];
						$return_array['goods'][$j]['gid'] = $goods_Rs['gid'];
						$return_array['goods'][$j]['price'] = number_format($goods_Rs['price']);
						$return_array['goods'][$j]['pricedesc'] = number_format($goods_Rs['pricedesc']);
						$return_array['goods'][$j]['sale_name'] = $goods_Rs['sale_name'];
						$return_array['goods'][$j]['salename_color'] = $goods_Rs['salename_color'];
						$return_array['goods'][$j]['smallimg'] = $goods_Rs['smallimg'];
						$return_array['goods'][$j]['intro'] =$Char_class->cut_str($goods_Rs['intro'],23,0,'UTF-8');
						if ($goods_Rs['iftimesale']==1 && $goods_Rs['timesale_starttime']<=time() && $goods_Rs['timesale_endtime']>=time()){
							$return_array['goods'][$j]['pricedesc']  = $goods_Rs['saleoffprice'];
						}
						$j++;
					}
				}
			}
		}
		return $return_array;
	}
	/**
	瀏覽等級
	**/
	function ViewBidLevel($bid){
		global $DB,$INFO;
		$ifview = 0;
		$viewlevel_sql = "select * from `{$INFO[DBPrefix]}bclass_userlevel` as gu inner join `{$INFO[DBPrefix]}user_level` as ul on gu.levelid=ul.level_id where gu.bid='" . intval($bid) . "'";
		$Query_viewlevel = $DB->query($viewlevel_sql);
		$viewlevel = array();
		$v = 0;
		while ($Result_viewlevel=$DB->fetch_array($Query_viewlevel)){
			$viewlevel[$v] = $Result_viewlevel['level_name'];
			if (intval($_SESSION['user_level'])>0 && intval($Result_viewlevel['level_id'])==intval($_SESSION['user_level'])){
				$ifview = 1;
			}
			$v++;
		}

		$viewlevel_string = "";
		if (count($viewlevel)>0)
			$viewlevel_string = "僅允許" . implode(" ",$viewlevel) . "查看商品";

		if ($viewlevel_string != "" && $ifview == 0){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('" . $viewlevel_string . "');location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";exit;
		}
	}
	/**
	18禁瀏覽
	**/
	function ViewGidBelate($gid,$type=0){
		global $DB,$INFO,$FUNCTIONS;

		$sub_bid = explode(",","6,".$FUNCTIONS->Sun_pcon_class(6));
		array_pop($sub_bid);
		$sub_bid = array_unique($sub_bid);
		$sub_bid = array_values($sub_bid);

		$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$Ifbelate  =  $Result['ifbelate'];
			if (in_array($Result['bid'], $sub_bid)){
				$Ifbelate = 1;
			}
		}

		$Query = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$age = $Result['born_date'];
		}
		//$age = ($age != '' && $age != '--') ? round((time()-strtotime($age))/(24*60*60)/365.25,0) : 0;
		if($age != '' && $age != '--'){
			$age = round((time()-strtotime($age))/(24*60*60)/365.25,0);
			$age = $age != 0 ? $age : 1;
		}else {
			$age = 0;
		}

		if ($type==1 && $Ifbelate == 1 && (intval($_SESSION['user_id']) == 0 || (intval($_SESSION['user_id']) != 0 && intval($age) == 0))){
			return 1;
		}elseif ($type==1 && $Ifbelate == 1 && (intval($_SESSION['user_id']) == 0 || (intval($_SESSION['user_id']) != 0 && intval($age) < 18))) {
			return 2;
		}elseif ($type==1) {
			return 0;
		}

		if (intval($_SESSION['user_id']) == 0 && $Ifbelate == 1){
			$url = $_SERVER['HTTP_REFERER'] != "" ? $_SERVER['HTTP_REFERER'] : "../index.php";
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('請先註冊或登入才能觀看。');location.href='../member/login_windows.php?&Url=" . $url . "';</script>";exit;
		}

		if (intval($_SESSION['user_id']) != 0 && intval($age) == 0 && $Ifbelate == 1){
			$url = $_SERVER['HTTP_REFERER'] != "" ? "back" : "../index.php";
			$FUNCTIONS->sorry_back($url,"請先至會員資訊填寫生日。");
		}

		if (intval($_SESSION['user_id']) != 0 && intval($age) < 18 && $Ifbelate == 1){
			$url = $_SERVER['HTTP_REFERER'] != "" ? "back" : "../index.php";
			$FUNCTIONS->sorry_back($url,"未滿18歲不得觀看商品資料。");
			//echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('未滿18歲不得觀看商品資料。');location.href='" . $url . "';</script>";exit;
		}
	}
}
?>
