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
		$Sql = "select " . $subSql . " from `{$INFO[DBPrefix]}goods` g where g.gid='" . $gid . "' and g.ifpub=1 ";
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
		global $DB,$INFO;
		$checkshop = $this->CheckIfShopProduct($gid);  //查看是否為商店街商品
		if ($checkshop['shopid']>0)
			$bname = "shopbclass";
		else
			$bname = "bclass";
		if($goodstype == "")
			$subSql = "  and g.ifxy!=1 and g.ifpresent!=1 and g.ifchange!=1";
		elseif($goodstype == "other")
			$subSql = "  and g.ifxy!=1 and g.ifpresent!=1 and g.ifchange!=1";
			
		$Sql = "select g.*,p.provider_name,br.brandname,br.logopic,b.attr from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}" . $bname . "` b on ( g.bid=b.bid ) left join `{$INFO[DBPrefix]}brand` br on (g.brand_id=br.brand_id) left join `{$INFO[DBPrefix]}provider` p  on (p.provider_id=g.provider_id)  where g.gid='" . $gid . "' and  b.catiffb=1 and g.ifpub=1 " . $subSql;
		$Query   = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		if($Num<=0){
			return 0;	
		}else{
			$Result_goods = $DB->fetch_array($Query);
			$attr_array = $this->getClassAttr($Result_goods);//商品分類中的擴展屬性
			
			$saleoff_array = $this->checkSaleOff($Result_goods);//整點促銷1
			$Result_goods['saleoff'] = $saleoff_array;
			$timesale_array = $this->checkTimeSale($Result_goods);//整點促銷2
			$Result_goods['timesale'] = $timesale_array;
			$appoint_array = $this->checkAppoint($Result_goods);//預購商品
			$Result_goods['appoint'] = $appoint_array;
			if($_SESSION['user_id']>0){
				$member_price = $this->getMemberPrice($_SESSION['user_level'],$gid,0);
				$Result_goods['levelprice'] = $member_price;
			}
			if($Result_goods['if_monthprice']==1){
				$month = $Result_goods['month'];
				$month_array = explode(",",$month); //分期
				$i = 0;
				foreach($month_array as $k=>$v){
					$month_price_array[$i]['name'] = "分" . $v . "期";
					if($member_price>0)
						$month_price_array[$i]['price'] = round($member_price/$v,0);
					else
						$month_price_array[$i]['price'] = round($Result_goods['pricedesc']/$v,0);
					$i++;
				}
				$Result_goods['month_array'] = $month_price_array;
			}
			$Result_goods['color'] = $this->getProductColor($Result_goods);//商品顏色
			$Result_goods['size'] = $this->getProductSize($Result_goods);//商品尺寸
			if($Result_goods['ifsales']==1){
				$Result_goods['salesubject'] = $this->getSaleSubject($Result_goods['sale_subject']);//活動主題
			}
			$Result_goods['discountsubject'] = $this->getDiscountSubject($gid);//折扣主題
			$Result_goods['changegoods'] = $this->getChangeProduct($gid);//加購商品
			$Result_goods['buymore'] = $this->getBuyMore($gid);//買越多
			$Result_goods['productdetail'] = $this->getProductDetail($gid);//詳細規格
			$Result_goods['JS'] = $this->checkJS($Result_goods);//擊殺
			$Result_goods['XY'] = $this->getXyProduct($gid);//超值任選
			$Result_goods['ClassField'] = $this->getClassAttr($Result_goods);//分類上的擴展字段
			$Result_goods['Attr'] = $this->getAttributeClass($Result_goods['bid'],$gid);//產品分類屬性
			
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
	function getProductList($bid=0,$type="",$search_array=array(),$orderby = array(),$showcount=0,$ifpage=0,$ifshowcolor=0,$ifshowmemberprice=0,$ifshowsale=0){
		global $DB,$INFO;
		include_once("PageNav.class.php");
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
		if($bid>0){
			$Next_Array  = $this->Sun_pcon_class($bid); //子分類ID數組
			foreach ($Next_Array as $k=>$v){
				$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid=".$v." " : "";
				$Add2 .= trim($v)!="" && intval($v)>0 ? " or gc.bid=".$v." " : "";
			}
			
			$gid_array = $this->getExtendClass($bid,$Add2);   //得到擴展分類對應的商品ID
			if (is_array($gid_array) && count($gid_array)>0){
				$gid_str = implode(",",$gid_array);
				$gid_sql_str = " or g.gid in (" . $gid_str . ")";
			}
			$bidSql = " and (g.bid=".$bid." ".$Add." " . $gid_sql_str . ") ";
		}
		$result_array = array();
		if($type == ""){
			$subSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1";		
		}else{
			$nomalSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1";
			switch($type){
				case "bonus":
					$subSql = " and g.ifbonus='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1";	
					break;
				case "xy":
					$subSql = " and g.ifbonus!='1' and g.ifxy=1 and ifchange!=1 and g.ifpresent!=1";	
					break;
				case "present":
					$subSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent=1";	
					break;
				case "change":
					$subSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange=1 and g.ifpresent!=1";	
					break;
				case "js":
					$subSql = " and g.ifbonus!='1' and g.ifxy!=1 and ifchange!=1 and g.ifpresent!=1 and g.ifjs=1";	
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
			}
		}
		if (count($search_array)>0){
			foreach($search_array as $k=>$v){
				switch($k){
					case "key":
						$searchSql = " and ( g.goodsname like '%".trim($v)."%' or  g.intro like '%".trim($v)."%' or  g.bn like '%".trim($v)."%' ) ";
						break;
				}
			}
		}
		if($showcount>0)
			$limitSql = " limit 0," . $showcount . " ";
		if(count($orderby)==0)
			$orderSql = " order by g.goodorder asc,g.idate desc ";
		else{
			if ($orderby[0]=="price")
				$orderSql  = "  order by g.pricedesc ";
			elseif ($orderby[0]=="pubtime")
				$orderSql  = "  order by g.idate ";
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
		
		$Sql = "select g.*,b.* from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on ( g.bid=b.bid ) where b.catiffb='1' and g.ifpub='1' " . $searchSql . $subSql . $bidSql . $orderSql . " " . $limitSql;
		if($ifpage==1){
			$PageNav    = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
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
				$result_array['info'][$i] = $Result;
				$timesale_array = $this->checkTimeSale($Result);//整點促銷2
				$result_array['info'][$i]['timesale'] = $timesale_array;
				if($timesale_array['iftimesale']==1)
					$result_array['info'][$i]['pricedesc'] = $timesale_array['price'];
				if($ifshowcolor==1){
					$result_array['info'][$i]['color'] = $this->getProductColor($Result);//商品顏色
					$result_array['info'][$i]['size'] = $this->getProductSize($Result);//商品尺寸
				}
				if($ifshowsale==1){
					$saleoff_array = $this->checkSaleOff($Result);//整點促銷1
					$result_array['info'][$i]['saleoff'] = $saleoff_array;
				}
				if($_SESSION['user_id']>0 && $ifshowmemberprice==1){
					$member_price = $this->getMemberPrice($_SESSION['user_level'],$Result['gid'],0);
					$result_array['info'][$i]['levelprice'] = $member_price;
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
	function getProductPic($gid,$loop=1){
		global $DB,$INFO;
		$Sql_pic    = "select goodpic_name,goodpic_title from `{$INFO[DBPrefix]}good_pic` where good_id=".intval($gid);
		$Query_pic  = $DB->query($Sql_pic);
		$Num_pic    = $DB->num_rows($Query_pic);
		$i = $loop;
		if ($Num_pic>0){
			while ($Result_pic = $DB->fetch_array($Query_pic))  {
				$Goodpic[$i]['pic'] =   $Result_pic['goodpic_name'];
				$Goodpic[$i]['title'] =   $Result_pic['goodpic_title'];
				$i++;
			}
		}
		return $Goodpic;
	}
	
	/**
	得到商品顏色列表
	**/
	function getProductColor($array){
		if($array['good_color']!=""){
			$color_array = explode(",",$array['good_color']);
			$color_pic_array = explode(",",$array['good_color_pic']);
			$i = 0;
			foreach($color_array as $k=>$v){
				$result_array[$i]['color'] = $v;
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
			$Query_s = $DB->query("select * from  `{$INFO[DBPrefix]}sale_subject`  where subject_id='".$subject_id."'  limit 0,1");
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
		}
		return $result_array;
	}
	
	/**
	判斷整點促銷
	**/
	function checkSaleOff($saleoff_array){
		$result_array = array();
		$result_array['startdate']  = $saleoff_array['saleoff_starttime']==""?"":date("Y-m-d H:i",$saleoff_array['saleoff_starttime']);
		$result_array['enddate']  = $saleoff_array['saleoff_endtime']==""?"":date("Y-m-d H:i",$saleoff_array['saleoff_endtime']);
		if ($saleoff_array['ifsaleoff']==1 && $saleoff_array['saleoff_starttime']<=time() && $saleoff_array['saleoff_endtime']>=time()){
			$result_array['ifsaleoff']  = 1;
		}elseif ($result_array['ifsaleoff']==1){
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
		$result_array['startdate']  = $timesale_array['timesale_starttime']==""?"":date("Y-m-d H:i",$timesale_array['timesale_starttime']);
		$result_array['enddate']  = $timesale_array['timesale_endtime']==""?"":date("Y-m-d H:i",$timesale_array['timesale_endtime']);
		if ($timesale_array['iftimesale']==1 && $timesale_array['timesale_starttime']<=time() && $timesale_array['timesale_endtime']>=time()){
			$result_array['price']  = $timesale_array['saleoffprice'];//到促銷時間購買折扣價格
			$result_array['iftimesale']  = 1;
		}else{
			$result_array['iftimesale']  = 0;	
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
	function getLikeAttribute($key){
		global $DB,$INFO;
		$Sql      = "select * from `{$INFO[DBPrefix]}attributevalue` as v inner join `{$INFO[DBPrefix]}attribute` as a on a.attrid=v.attrid  where v.value like '%" . trim($key) . "%' order by valueid desc ";
		$Query    = $DB->query($Sql);
		$Num      = $DB->num_rows($Query);
		$attrvalue_array = array();
		$i = 0;
		while ($Rs=$DB->fetch_array($Query)) {
			$attrvalue_array[$i]['value'] = $Rs['value'];
			$attrvalue_array[$i]['attrid'] = $Rs['attrid'];
			$attrvalue_array[$i]['valueid'] = $Rs['valueid'];
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
	相關商品
	**/
	function getProductLink($gid,$bid,$ifgl=1){
		global $DB,$INFO;
		if (intval($ifgl)==1){ //判断是否是指定了产品内容，如果没有就把本类产品资料都调出来
			$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.intro,g.pricedesc,gl.s_gid from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}good_link` gl  on (g.gid=gl.s_gid) where g.ifpub=1 and g.ifpresent!=1 and gl.p_gid=".$gid;
		}else{
			$Sql   = "select g.gid,g.goodsname,g.price,g.smallimg,g.middleimg,g.pricedesc,g.intro from `{$INFO[DBPrefix]}goods` g where g.bid=".$bid." and g.gid!=".$gid." and g.ifpub=1 and g.ifpresent!=1 and g.ifchange!=1 and g.ifxy!=1 order by g.idate desc limit 0,8 ";
		}
		$Query = $DB->query($Sql);
		$i=1;
		$j=0;
		$abProductArray = array();
		while ($Rs =  $DB->fetch_array($Query)){
			$abProductArray[$j]['autonum']    = $i;
			$abProductArray[$j]['Bgcolor']    = $i%2==0 ?  "#FAFAFA" : 'white';
			$abProductArray[$j]['goodsname']  = $Rs['goodsname'];
			$abProductArray[$j]['gid']        = $Rs['gid'];
			$abProductArray[$j]['price']      = $Rs['price'];
			$abProductArray[$j]['pricedesc']  = $Rs['pricedesc'];
			$abProductArray[$j]['smallimg']   = $Rs['smallimg'];
			$abProductArray[$j]['middleimg']  = $Rs['middleimg'];
			$abProductArray[$j]['intro']      = nl2br($Rs['intro']);
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
		global $DB,$INFO;
		if($type=="down"){
			$flag = ">";
			$orderby = "asc";
		}else{
			$flag = "<";
			$orderby = "desc";
		}
		$Sql_Up =   "select g.gid,g.goodsname from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}" . $bname . "` b on ( g.bid=b.bid ) where  b.catiffb='1' and g.ifpub='1' and g.ifxy!=1 and g.ifpresent!=1 and g.ifchange!=1 and g.bid='" . $bid  ."' and g.gid" . $flag . "'" . $gid . "'  order by gid " . $orderby . " limit 0,1";
		$Query_Up   = $DB->query($Sql_Up);
		$Num_Up     = $DB->num_rows($Query_Up);
		if ( $Num_Up>0 ) {
			$Rs_Up    = $DB->fetch_array($Query_Up);
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
			if($_SESSION['user_id']>0){
				$member_price = $this->getMemberPrice($_SESSION['user_level'],$gid,$Detail_Rs['detail_id']);
			}
			$result_array['info'][$i]['member_price'] = intval($member_price)==0?$Detail_Rs['detail_price']:$member_price;
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
		$Sql         = "select gl.* ,g.goodsname,g.bn,g.smallimg,g.good_color,g.good_size,g.storage,g.pricedesc from `{$INFO[DBPrefix]}goods_xy` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.xygid=g.gid) where gl.gid=".intval($gid)." and g.ifpub=1 order by gl.idate desc ";
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
		$Sql         = "select gl.* ,g.goodsname,g.bn,g.smallimg,g.good_color,g.good_size,g.ifchange from `{$INFO[DBPrefix]}goods_change` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.changegid=g.gid) where gl.gid=".intval($gid)." and g.ifchange=1 and g.ifpub=1 order by gl.idate desc ";
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
			$result_array['info'][$i] = $Rs;
			$i++;
		}
		return $result_array;
	}
	
	/**
	折扣促銷主題
	**/
	function getDiscountSubject($gid){
		global $DB,$INFO;
		$Sql      = "select * from `{$INFO[DBPrefix]}discountgoods` as dg inner join `{$INFO[DBPrefix]}discountsubject` as d on dg.dsid=d.dsid where dg.gid='" . intval($gid) . "' order by d.dsid ";
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
	function checkStorage($gid,$detail_id=0,$color="",$size=""){
		global $DB,$INFO;
		if ($gid>0){
			$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)."  limit 0,1");
			$Num   = $DB->num_rows($Query);
			if ($Num>0){
				$Result= $DB->fetch_array($Query);
				if ($Result['storage']>0){
					if ($detail_id>0){
						$Sql_d = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $gid . "' and detail_id='" . $detail_id . "'";
						$Query_d    = $DB->query($Sql_d);
						$Nums_d      = $DB->num_rows($Query_d);	
						if ($Nums_d>0){
							$Rs_d=$DB->fetch_array($Query_d);
							return $Rs_d['storage'];	
						}else{
							return "0";	
						}
					}elseif ($size!="" || $color!=""){
						$Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid) . " and size='" . $size . "' and color = '" . $color . "'";
						$Query_s    = $DB->query($Sql_s);
						$Nums_s      = $DB->num_rows($Query_s);	
						if ($Nums_s>0){
							$Rs_s=$DB->fetch_array($Query_s);
							return $Rs_s['storage'];	
						}else{
							return "0";	
						}
					}else{
						return $Result['storage'];	
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
	function getProductClass($bid){
		global $DB,$INFO;
		$result_array = array();
		$Sql_bclass    = "select * from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . intval($bid) . "' order by catord,bid  asc    ";
		$query_bclass  = $DB->query($Sql_bclass);
		$num_bclass    = $DB->num_rows($query_bclass);
		$i=0;
		if ($num_bclass>0){
			while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
				$result_array[$i] = $Rs_bclass;
				$j=0;
				$Sql_bclass_2    = "select * from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $Rs_bclass['bid'] . "' order by catord,bid  asc  ";
				$query_bclass_2  = $DB->query($Sql_bclass_2);
				$num_bclass_2    = $DB->num_rows($query_bclass_2);
				while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
					$result_array[$i]['sub'][$j] = $Rs_bclass_2;
					$z=0;
					$Sql_bclass_3    = "select bid,catname,pic1,pic2,manyunfei,subject_id,subject_id2 from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id='" . $Rs_bclass_2['bid'] . "' order by catord,bid  asc  ";
					$query_bclass_3  = $DB->query($Sql_bclass_3);
					$num_bclass_3    = $DB->num_rows($query_bclass_3);
					while($Rs_bclass_3 =  $DB->fetch_array($query_bclass_3)){
						$result_array[$i]['sub'][$j]['sub'][$z] = $Rs_bclass_3;
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
				$bid_array[count($bid_array)] = $Rs['bid'];
				$this->Sun_pcon_class($Rs['bid'],$bid_array);
			}
			return  $bid_array;
		}else{
			return $bid_array;
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
}
?>