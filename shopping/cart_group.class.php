<?php
class Cart{
	var $totalCount;    //商品總數量
	var $totalPrices;   //商品總金額
	var $discount_totalPrices;   //優惠后的商品總金額
	var $transmoney;     //運費
	var $sys_trans_type; //系統設置運費方式：自定義配送，配送公式（小於多少運費多少）
	var $sys_trans;      //系統配送設置
	var $goodsGroup;    //商品分組（商品按不同運輸方式分組：一般配送，多種特殊配送）
	var $get_key;
	var $nomal_trans_type;//一般配送方式類型
	var $transname;
	var $transname_content;
	var $transname_area;
	var $transname_area2;
	var $manyunfei;
	var $transname_id;
	var $totalGrouppoinit;
	var $store;
	var $totalBuypoint;
	
	/**
	初始化類
	**/
	function Cart(){
		$this->totalCount = 0;	
		$this->totalPrices = 0;
		$this->discount_totalPrices = 0;
		$this->transmoney = 0;
		$this->sys_trans_type = 0;
		$this->nomal_trans_type = 0;
		$this->get_key = 0;
		$this->sys_trans = array();
		$this->goodsGroup = array();
		$this->transname = "";
		$this->transname_area = "";
		$this->transname_area2 = "";
		$this->transname_content = "";
		$this->manyunfei = array();
		$this->transname_id = 0;
		$this->totalGrouppoinit = 0;
		$this->store = array();
		$this->totalBuypoint = 0;	
	}
	/**
	重置
	**/
	function resetCart(){
		$this->totalCount = 0;	
		$this->totalPrices = 0;
		$this->discount_totalPrices = 0;
		$this->transmoney = 0;
		$this->sys_trans_type = 0;
		$this->nomal_trans_type = 0;
		$this->get_key = 0;
		$this->sys_trans = array();
		$this->transname = "";
		$this->transname_area = "";
		$this->transname_area2 = "";
		$this->transname_content = "";
		$this->manyunfei = array();
		$this->transname_id = 0;
		$this->totalGrouppoinit = 0;
		$this->store = array();
		$this->totalBuypoint = 0;	
	}
	/**
	新增購物車商品

	**/
	function addItems($goodsitems = array(),$pkey = ""){
		//額滿禮處理
		if ($pkey != ""){
			$key = $pkey;
		}else{ 
			$key = 0 ;
		}
		if($gkey = $this->ifexistItems($goodsitems)){
			$this->changeItemsCount($key,$gkey,$goodsitems['count']);
		}else{
			$gkey = $goodsitems['gid'] . "_" . time();
			$this->goodsGroup[$key][$gkey]['gkey'] = $gkey;
			$this->goodsGroup[$key][$gkey]['gid'] = $goodsitems['gid'];
			$this->goodsGroup[$key][$gkey]['bn'] = $goodsitems['bn'];
			$this->goodsGroup[$key][$gkey]['goodsname'] = $goodsitems['goodsname'];
			$this->goodsGroup[$key][$gkey]['storage'] = $goodsitems['storage'];
			$this->goodsGroup[$key][$gkey]['price'] = $goodsitems['price'];
			$this->goodsGroup[$key][$gkey]['temp_price'] = $goodsitems['temp_price'];
			$this->goodsGroup[$key][$gkey]['smallimg'] = $goodsitems['smallimg'];
			$this->goodsGroup[$key][$gkey]['grouppoint'] = $goodsitems['grouppoint'];
			$this->goodsGroup[$key][$gkey]['count'] = $goodsitems['count'];
			$this->goodsGroup[$key][$gkey]['subject'] = $goodsitems['subject'];
			$this->goodsGroup[$key][$gkey]['subjectcontent'] = $goodsitems['subjectcontent'];
			$this->goodsGroup[$key][$gkey]['weight'] = $goodsitems['weight'];
			$this->goodsGroup[$key][$gkey]['temp_grouppoint'] = $goodsitems['temp_grouppoint'];
			$this->goodsGroup[$key][$gkey]['grouppoint'] = $goodsitems['grouppoint'];
			$this->goodsGroup[$key][$gkey]['subject_grouppoint'] = $goodsitems['subject_grouppoint'];
			$this->goodsGroup[$key][$gkey]['subject_price'] = $goodsitems['subject_price'];
			$this->goodsGroup[$key][$gkey]['goodslist'] = $goodsitems['goodslist'];
			$this->goodsGroup[$key][$gkey]['ifnotrans'] = 0;
			$this->goodsGroup[$key][$gkey]['buytype'] = intval($goodsitems['buytype']);
			$this->goodsGroup[$key][$gkey]['size'] = $goodsitems['size'];
			$this->goodsGroup[$key][$gkey]['color'] = $goodsitems['color'];
			$this->goodsGroup[$key][$gkey]['memberprice'] = intval($goodsitems['memberprice']);
			$this->goodsGroup[$key][$gkey]['cost'] = intval($goodsitems['cost']);
			$this->goodsGroup[$key][$gkey]['provider_id'] = intval($goodsitems['provider_id']);
			$this->goodsGroup[$key][$gkey]['ifbuymore'] = 0;//是否買越多
		}
		$this->setGroup($key);
	}
	/**
	是否存在
	**/
	function ifexistItems($goodsitems){
		if (is_array($this->goodsGroup)){
			foreach($this->goodsGroup as $keys=>$values){
				if (is_array($values))
					foreach($values as $k=>$v){
						if ($v['gid'] == $goodsitems['gid'] && $v['subject'] == $goodsitems['subject'])
							return $v['gkey'];
					}
			}
		}
		return false;
	}
	/**
	更改購買數量
	**/
	function changeItemsCount($key,$gkey,$count){
		global $INFO;
		if (intval($INFO['buy_product_max_num'])<(intval($this->goodsGroup[$key][$gkey]['count']) + intval($count)))
			$gcount = intval($INFO['buy_product_max_num']);
		else
			$gcount = intval($this->goodsGroup[$key][$gkey]['count']) + intval($count);
		$isok = 0;
		//判斷商品庫存
		if ($this->goodsGroup[$key][$gkey]['storage'] >= $gcount){
			$isok = 1;
		}
		if($isok == 1){
			$this->goodsGroup[$key][$gkey]['count'] = $gcount;
		}
		$this->setGroup($key);
	}
	
	/**
	刪除購物車中某商品
	**/
	function deleItems($key,$gkey){
		$present_array=array();
		$i = 0;
		$j = 0;
		unset($this->goodsGroup[$key][$gkey]);
		if (count($this->goodsGroup[$key])==0)
			unset($this->goodsGroup[$key]);
		$this->setGroup($key);
	}
	
	/**
	購物車信息統計
	**/
	function setTotal($key){
		$this->totalCount = 0;	
		$this->totalPrices = 0;
		$this->totalGrouppoinit = 0;
		if (is_array($this->goodsGroup[$key])){
			foreach($this->goodsGroup[$key] as $k=>$v){
				$this->totalCount = intval($this->totalCount) + intval($v['count']);
				if (intval($v['buytype'])==0)
					$this->totalPrices = intval($this->totalPrices) + intval($v['count']) * intval($v['price']);
				if (intval($v['buytype'])==1){
					$this->totalPrices = intval($this->totalPrices) + intval($v['count']) * intval($v['memberprice']);
					$this->totalGrouppoinit = intval($this->totalGrouppoinit) + intval($v['count']) * intval($v['grouppoint']);
				}
			}
		}
		$this->discount_totalPrices = $this->totalPrices;
	}
	
	/**
	得到購物車中所有的商品
	**/
	function getCartGoods(){
		return $this->goodsGroup;
	}
	/**
	得到購物車中某組商品
	**/
	function getCartGroup($key){
		return $this->goodsGroup[$key];
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
	function setTransMoney($man_trans_type,$key,$man_nomal_type = 0,$trans_money = 0,$trans_permoney=0,$ifabroad=0){
		/*
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
		if($man_trans_type !=3 &&  $man_trans_type !=4 ){
			
			if(is_array($this->goodsGroup[$key])){
				foreach($this->goodsGroup[$key] as $k=>$v){
					if ($v['ifnotrans']==1)
						$weight += $v['weight']*$v['count'];
				}	
			}
		}

		if($this->sys_trans_type == 1){
			if ($this->discount_totalPrices <= $this->sys_trans[1]['PayStartprice']){
				$this->transmoney = $this->sys_trans[1]['TransMoney'];
				$this->transname = "當購買商品總金額小於" . $this->sys_trans[1]['PayStartprice'] . "元，須加收運費" . $this->sys_trans[1]['TransMoney'] . "元。";
				$this->ifnotrans = 1;
			}
			else{
				$this->transmoney = 0;
				
				$this->ifnotrans = 1;
			}
		}else if($this->sys_trans_type == 0){
			//echo $iffree;exit;
			if ($this->sys_trans[0]['FreeTransMoney'] > 0 && $this->discount_totalPrices >= $this->sys_trans[0]['FreeTransMoney'] && $iffree==1){
				$this->transmoney = 0;
				$this->ifnotrans = 1;
				$this->transname = "當購買滿" . $this->sys_trans[0]['FreeTransMoney'] . "免運費。";
				$this->ifnotrans = 1;
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
		*/
		$this->transmoney = 0;
		$this->transname ="";
	}
	
	/**
	更改
	**/
	function changeItems($key,$gkey,$valuekey,$value){
		$this->goodsGroup[$key][$gkey][$valuekey] = $value;
		$this->setGroup($key);
	}
	
	
	function clearGoods($key){
		foreach($this->goodsGroup[$key] as $k=>$v){
			unset($this->goodsGroup[$key][$k]);
		}
		$this->goodsGroup[$key] = array();
		unset($this->goodsGroup[$key]);
		$this->setGroup($key);
	}
	
	/**
	團購專區
	**/
	function setGroup($key){
		global $INFO,$DB;
		$Sql_sub   = " select * from `{$INFO[DBPrefix]}groupsubject` where subject_open=1";
		$Query_sub = $DB->query($Sql_sub);
		$Array_sub = array();
		$sub_i = 0;
		while ($Rs_sub = $DB->fetch_array($Query_sub) ){
			$groupsubject[$Rs_sub['gsid']][dsid]    =  $Rs_sub['gsid'];
			$groupsubject[$Rs_sub['gsid']][subject_name]  =  $Rs_sub['subject_name'];
			$groupsubject[$Rs_sub['gsid']][min_money]  =  $Rs_sub['min_money'];
			$groupsubject[$Rs_sub['gsid']][min_count]  =  $Rs_sub['min_count'];
			$groupsubject[$Rs_sub['gsid']][grouppoint]  =  $Rs_sub['grouppoint'];
			$groupsubject[$Rs_sub['gsid']][mianyunfei]  =  $Rs_sub['manyunfei'];
			$sub_i++;
		}

		$subject_array  = array();
		if (is_array($this->goodsGroup[$key])){
		foreach($this->goodsGroup[$key] as $k=>$v){
			if(intval($v['subject'])>0){
				$subject_array[$v['subject']]['totalprice'] += $v['count']*$v['subject_price'];
				$subject_array[$v['subject']]['totalcount'] += $v['count'];
				$subject_array[k]['ifsubject'] = 0;
			}
		}
		foreach($subject_array as $k=>$v){
			if(($groupsubject[$k]['min_money']>0 && $v['totalprice']>$groupsubject[$k]['min_money']) || ($groupsubject[$k]['min_count']>0 && $v['totalcount']>$groupsubject[$k]['min_count'])){
				$subject_array[k]['ifsubject'] = 1;
				foreach($this->goodsGroup[$key] as $kk=>$vv){
					if ($vv['subject'] == $k){
						if ($vv['buytype']==0){
							$this->goodsGroup[$key][$kk]['price'] = $vv['subject_price'];
							$this->goodsGroup[$key][$kk]['grouppoint'] = $vv['subject_grouppoint'];
						}else{
							$this->goodsGroup[$key][$kk]['price'] = $vv['memberprice'];
							$this->goodsGroup[$key][$kk]['grouppoint'] = $vv['grouppoint'];
						}
						$this->goodsGroup[$key][$kk]['subjectcontent'] = $groupsubject[$k]['subject_name'];
						if ($groupsubject[$k]['mianyunfei']>0 && $groupsubject[$k]['mianyunfei']<=$v['totalprice']){
							$this->goodsGroup[$key][$kk]['ifnotrans'] = 1;
						}else{
							$this->goodsGroup[$key][$kk]['ifnotrans'] = 0;	
						}
					}
				}
			}else{
				foreach($this->goodsGroup[$key] as $kk=>$vv){
					if ($vv['subject'] == $k){
						if ($vv['buytype']==0){
							$this->goodsGroup[$key][$kk]['price'] = $vv['temp_price'];
							$this->goodsGroup[$key][$kk]['grouppoint'] = $vv['subject_grouppoint'];
						}else{
							$this->goodsGroup[$key][$kk]['price'] = $vv['memberprice'];
							$this->goodsGroup[$key][$kk]['grouppoint'] = $vv['grouppoint'];
						}
						$this->goodsGroup[$key][$kk]['subjectcontent'] = "";
						$this->goodsGroup[$key][$kk]['ifnotrans'] = 0;	
					}
				}
				
			}
		}
		}
	}
	
	//買越多
	function setSaleoff($key,$gkey){
		global $DB,$FUNCTIONS;
		global $INFO,$FUNCTIONS;
		if (intval($this->goodsGroup[$key][$gkey]['subject']) == 0){
			$Sql      = "select * from `{$INFO[DBPrefix]}group_saleoffe` where gdid='" . intval($this->goodsGroup[$key][$gkey]['gid']) . "' and mincount<=" . intval($this->goodsGroup[$key][$gkey]['count']) . " and (maxcount>=" . intval($this->goodsGroup[$key][$gkey]['count']) . " or maxcount=0) order by soid desc limit 0,1";
			$Query    = $DB->query($Sql);
			$Num      = $DB->num_rows($Query);
			if ($Num>0){
				$Result= $DB->fetch_array($Query);
				/*
				$this->goodsGroup[$key][$gkey]['price'] = round($Result['saleoff']/100*$this->goodsGroup[$key][$gkey]['temp_price'],0);
				$this->goodsGroup[$key][$gkey]['grouppoint'] = $this->goodsGroup[$key][$gkey]['temp_price'] - round($Result['saleoff']/100*$this->goodsGroup[$key][$gkey]['temp_price'],0)+$this->goodsGroup[$key][$gkey]['temp_grouppoint'];
				*/
				$this->goodsGroup[$key][$gkey]['price'] = $Result['memberprice'];
				$this->goodsGroup[$key][$gkey]['grouppoint'] = $Result['grouppoint'];
				$this->goodsGroup[$key][$gkey]['ifbuymore'] = 1;
				$this->goodsGroup[$key][$gkey]['buytype'] = 0;
			}else{
				$this->goodsGroup[$key][$gkey]['price'] = $this->goodsGroup[$key][$gkey]['temp_price'];
				$this->goodsGroup[$key][$gkey]['grouppoint'] = $this->goodsGroup[$key][$gkey]['temp_grouppoint'];
				$this->goodsGroup[$key][$gkey]['ifbuymore'] = 0;
			}
		}
		return $this->goodsGroup[$key][$gkey]['price'];
	}
	
	/**
	設置門市取貨
	**/
	function setStore($key,$store_array){
		$this->store[$key] = $store_array;
		return;
	}
	
	/**
	設置購物金
	**/
	function setBuypoint($buypoint){
		$this->totalBuypoint = intval($buypoint);
	}
}


?>