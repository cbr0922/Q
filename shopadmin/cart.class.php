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
	var $okmap;
	var $discountsaleoff;
	var $discountprice;
	
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
		$this->okmap = array();
		$this->discountsaleoff = array();
		$this->discountprice = array();
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
		$this->okmap = array();
		$this->discountsaleoff = array();
		$this->discountprice = array();
	}
	/**
	新增購物車商品
	$goositems = array("gid","provider_id","Js_price","goodsname","detail_id","detail_bn","detail_name","detail_des","storage","unit","good_color","good_size","smallimg","pricedesc","price","member_price","point","count","trans_type","special_trans_money","trans_special","iftransabroad")
	**/
	function addItems($goodsitems = array(),$pkey = ""){
		//額滿禮處理
		if ($goodsitems['ifpresent'] == 1){
			$goodsitems['trans_type'] = "present";
		}
		
		if ($pkey != ""){
			$key = $pkey;
		}else{ 
			if($goodsitems['trans_type']==1){
				$key = $goodsitems['trans_special'];	
			}else if($goodsitems['transtype']==1 || $goodsitems['transtype']==2){
				$key = "T" . $goodsitems['transtype'];
			}else{
				$key = $goodsitems['trans_type'];
			}
			if (intval($goodsitems['iftogether'])==1){
				$key = $key . "P0";
			}elseif(intval($goodsitems['provider_id'])>0){
				$key = $key . "P" . $goodsitems['provider_id'];
			}elseif(intval($goodsitems['shopid'])>0){
				$key = $key . "S" . $goodsitems['shopid'];
			}
		}
		if($gkey = $this->ifexistItems($goodsitems)){
			$this->changeItemsCount($key,$gkey,$goodsitems['count']);
			return $key;
		}else{
			$gkey = $goodsitems['gid'] . "_" . time() . "_" . rand(0,10000);
			$this->goodsGroup[$key][$gkey]['gkey'] = $gkey;
			$this->goodsGroup[$key][$gkey]['gid'] = $goodsitems['gid'];
			$this->goodsGroup[$key][$gkey]['bn'] = $goodsitems['bn'];
			$this->goodsGroup[$key][$gkey]['provider_id'] = $goodsitems['provider_id'];   //供應商
			$this->goodsGroup[$key][$gkey]['Js_price'] = intval($goodsitems['Js_price']);
			$this->goodsGroup[$key][$gkey]['goodsname'] = $goodsitems['goodsname'];
			$this->goodsGroup[$key][$gkey]['detail_id'] = $goodsitems['detail_id'];
			$this->goodsGroup[$key][$gkey]['detail_bn'] = $goodsitems['detail_bn'];
			$this->goodsGroup[$key][$gkey]['detail_name'] = $goodsitems['detail_name'];
			$this->goodsGroup[$key][$gkey]['detail_des'] = $goodsitems['detail_des'];
			$this->goodsGroup[$key][$gkey]['storage'] = $goodsitems['storage'];
			$this->goodsGroup[$key][$gkey]['detail_storage'] = $goodsitems['detail_storage'];
			$this->goodsGroup[$key][$gkey]['unit'] = $goodsitems['unit'];
			$this->goodsGroup[$key][$gkey]['good_color'] = $goodsitems['good_color'];
			$this->goodsGroup[$key][$gkey]['good_size'] = $goodsitems['good_size'];
			$this->goodsGroup[$key][$gkey]['color_size_storage'] = $goodsitems['color_size_storage'];
			$this->goodsGroup[$key][$gkey]['smallimg'] = $goodsitems['smallimg'];
			$this->goodsGroup[$key][$gkey]['temp_price'] = $goodsitems['temp_price'];
			$this->goodsGroup[$key][$gkey]['price'] = $goodsitems['price'];
			$this->goodsGroup[$key][$gkey]['point'] = $goodsitems['point'];
			$this->goodsGroup[$key][$gkey]['count'] = $goodsitems['count'];
			$this->goodsGroup[$key][$gkey]['trans_type'] = $goodsitems['trans_type'];
			$this->goodsGroup[$key][$gkey]['special_trans_money'] = $goodsitems['special_trans_money'];
			$this->goodsGroup[$key][$gkey]['trans_special'] = $goodsitems['trans_special'];
			$this->goodsGroup[$key][$gkey]['iftransabroad'] = intval($goodsitems['iftransabroad']);
			$this->goodsGroup[$key][$gkey]['ifpresent'] = intval($goodsitems['ifpresent']);
			$this->goodsGroup[$key][$gkey]['ifxygoods'] =intval( $goodsitems['ifxygoods']);
			$this->goodsGroup[$key][$gkey]['xygoods'] = $goodsitems['xygoods'];//超值任選商品ID數組
			$this->goodsGroup[$key][$gkey]['xygoods_color'] = $goodsitems['xygoods_color'];//超值任選商品顏色數組
			$this->goodsGroup[$key][$gkey]['xygoods_size'] = $goodsitems['xygoods_size'];//超值任選商品尺寸數組
			$this->goodsGroup[$key][$gkey]['xygoods_des'] = $goodsitems['xygoods_des'];
			$this->goodsGroup[$key][$gkey]['ifchange'] = intval($goodsitems['ifchange']);//是否是加購的商品
			$this->goodsGroup[$key][$gkey]['changegid'] = $goodsitems['changegid'];//是加購的商品的話，加購商品的主商品
			$this->goodsGroup[$key][$gkey]['present_money'] = $goodsitems['present_money'];
			$this->goodsGroup[$key][$gkey]['ifjs'] = intval($goodsitems['ifjs']);
			$this->goodsGroup[$key][$gkey]['ifbonus'] = intval($goodsitems['ifbonus']);
			$this->goodsGroup[$key][$gkey]['bonuspoint'] = $goodsitems['bonuspoint'];
			$this->goodsGroup[$key][$gkey]['Js_price'] = $goodsitems['Js_price'];
			$this->goodsGroup[$key][$gkey]['ifadd'] = intval($goodsitems['ifadd']);
			$this->goodsGroup[$key][$gkey]['addmoney'] = $goodsitems['addmoney'];
			$this->goodsGroup[$key][$gkey]['oeid'] = $goodsitems['oeid'];
			$this->goodsGroup[$key][$gkey]['ifsale'] = intval($goodsitems['ifsale']);
			$this->goodsGroup[$key][$gkey]['sale_price'] = intval($goodsitems['sale_price']);
			$this->goodsGroup[$key][$gkey]['sale_subject'] = intval($goodsitems['sale_subject']);
			$this->goodsGroup[$key][$gkey]['org_price'] = intval($goodsitems['org_price']);
			$this->goodsGroup[$key][$gkey]['iftimesale'] = intval($goodsitems['iftimesale']);
			$this->goodsGroup[$key][$gkey]['ifalarm'] = intval($goodsitems['ifalarm']);
			$this->goodsGroup[$key][$gkey]['maxstorage'] = intval($goodsitems['maxstorage']);
			$this->goodsGroup[$key][$gkey]['transtype'] = $goodsitems['transtype'];
			$this->goodsGroup[$key][$gkey]['ifmood'] = intval($goodsitems['ifmood']);
			$this->goodsGroup[$key][$gkey]['addtransmoney'] = $goodsitems['addtransmoney'];
			$this->goodsGroup[$key][$gkey]['transtypemonty'] = $goodsitems['transtypemonty'];
			$this->goodsGroup[$key][$gkey]['nosaleoff'] = $goodsitems['nosaleoff'];
			if ($this->goodsGroup[$key][$gkey]['ifbonus'] ==1 || $this->goodsGroup[$key][$gkey]['ifpresent']==1){
				$this->goodsGroup[$key][$gkey]['price'] = 0;
				$this->goodsGroup[$key][$gkey]['org_price'] = 0;
				$this->goodsGroup[$key][$gkey]['temp_price'] = 0;
			}
			$this->goodsGroup[$key][$gkey]['ifinstall'] = intval($goodsitems['ifinstall']);
			$this->goodsGroup[$key][$gkey]['memberprice'] = intval($goodsitems['memberprice']);
			$this->goodsGroup[$key][$gkey]['combipoint'] = intval($goodsitems['combipoint']);
			$this->goodsGroup[$key][$gkey]['memberorprice'] = intval($goodsitems['memberorprice']);
			$this->goodsGroup[$key][$gkey]['iftogether'] = intval($goodsitems['iftogether']);
			$this->goodsGroup[$key][$gkey]['dsid'] = intval($goodsitems['dsid']);
			$this->goodsGroup[$key][$gkey]['dsprice'] = intval($goodsitems['dsprice']);
			$this->goodsGroup[$key][$gkey]['weight'] = ($goodsitems['weight']);
			$this->goodsGroup[$key][$gkey]['shopid'] = ($goodsitems['shopid']);
			$this->goodsGroup[$key][$gkey]['rebateinfo'] = ($goodsitems['rebateinfo']);
			$this->goodsGroup[$key][$gkey]['costinfo'] = ($goodsitems['rebateinfo']);
			$this->goodsGroup[$key][$gkey]['rebate'] = ($goodsitems['rebate']);
			$this->goodsGroup[$key][$gkey]['costrebate'] = ($goodsitems['costrebate']);
			$this->goodsGroup[$key][$gkey]['cost'] = ($goodsitems['cost']);
			$this->goodsGroup[$key][$gkey]['salecost'] = ($goodsitems['salecost']);
			$this->goodsGroup[$key][$gkey]['month'] = ($goodsitems['month']);
			$this->goodsGroup[$key][$gkey]['ifmore'] = 0;
			$this->goodsGroup[$key][$gkey]['ifds'] = 0;
			$this->goodsGroup[$key][$gkey]['ifneworold'] = $goodsitems['ifneworold'];
			$this->goodsGroup[$key][$gkey]['packgid'] = intval($goodsitems['packgid']);
			$this->goodsGroup[$key][$gkey]['ifpack'] = intval($goodsitems['ifpack']);
			//$this->setSaleoff($key,$gkey);
			$this->setMaxStorage($key,$gkey);
			//$this->setTotal();
			return $key;
		}
	}
	/**
	是否存在
	**/
	function ifexistItems($goodsitems){
		if (is_array($this->goodsGroup)){
			foreach($this->goodsGroup as $keys=>$values){
				if (is_array($values))
					foreach($values as $k=>$v){
						if ($v['gid'] == $goodsitems['gid'] && $v['detail_id'] == $goodsitems['detail_id'] && $v['good_color'] == $goodsitems['good_color'] && $v['good_size'] == $goodsitems['good_size'] && $v['ifadd'] == $goodsitems['ifadd'] && $v['xygoods'] == $goodsitems['xygoods'] && $v['xygoods_color'] == $goodsitems['xygoods_color'] && $v['xygoods_size'] == $goodsitems['xygoods_size'] && $v['packgid'] == $goodsitems['packgid']){
							return $v['gkey'];
						}
					}
			}
		}
		return false;
	}
	/**
	更改購買數量
	**/
	function changeItemsCount($key,$gkey,$count){
		if ($this->goodsGroup[$key][$gkey]['ifpresent'] == 1 || $this->goodsGroup[$key][$gkey]['ifxygoods'] == 1 || $this->goodsGroup[$key][$gkey]['ifchange'] == 1 || $this->goodsGroup[$key][$gkey]['ifbonus'] == 1 || $this->goodsGroup[$key][$gkey]['ifadd'] == 1){
			$this->goodsGroup[$key][$gkey]['count'] = 1;
			return;
		}
		$gcount = intval($this->goodsGroup[$key][$gkey]['count']) + intval($count);
		$isok = 0;
		//判斷商品庫存
		if ($this->goodsGroup[$key][$gkey]['storage'] >= $gcount){
			$isok = 1;
			//判斷商品屬性庫存
			if ($this->goodsGroup[$key][$gkey]['good_color'] != "" || $this->goodsGroup[$key][$gkey]['good_size'] != ""){
				if($this->goodsGroup[$key][$gkey]['color_size_storage'] >= $gcount){
					$isok = 1;	
				}else{
					$isok = 0;	
				}
			}
			if ($isok == 1){
				//詳細商品庫存
				if(intval($this->goodsGroup[$key][$gkey]['detail_id']) > 0){
					if ($this->goodsGroup[$key][$gkey]['detail_storage'] >= $gcount){
						$isok = 1;
					}else{
						$isok = 0;	
					}
				}
			}
		}
		if($isok == 1){
			$this->goodsGroup[$key][$gkey]['count'] = $gcount;
			//$this->setTotal();
		}
		
		$present_array=array();
		$i = 0;
		$if_present = $this->goodsGroup[$key][$gkey]['ifpresent'];
		if ($if_present!=1){
			if (is_array($this->goodsGroup[$key])){
			foreach($this->goodsGroup[$key] as $k=>$v){
				if ($v['ifpresent']==1){
					$present_array[$i]['key'] = $key;
					$present_array[$i]['gkey'] = $v['gkey'];
					$present_array[$i]['present_money'] = $v['present_money'];
					$i++;
				}
			}
			}
			$this->setTotal($key);
			if (is_array($present_array)){
			foreach($present_array as $k=>$v){
				if ($this->discount_totalPrices < $v['present_money']){
					$this->deleItems($v['key'],$v['gkey']);
				}
			}
			}
		}
		$this->setSaleoff($key,$gkey);
		
	}
	/**
	允許購買的最大庫存
	**/
	function setMaxStorage($key,$gkey){
		$max = -1;
		if ($this->goodsGroup[$key][$gkey]['ifxygoods'] == 1 || $this->goodsGroup[$key][$gkey]['ifchange'] == 1 || $this->goodsGroup[$key][$gkey]['ifadd'] == 1){
			$this->goodsGroup[$key][$gkey]['maxstorage'] = 1;
			return;
		}
		if ($this->goodsGroup[$key][$gkey]['detail_id'] > 0){
			$max = $this->goodsGroup[$key][$gkey]['detail_storage'];	
		}
		if ($this->goodsGroup[$key][$gkey]['good_color'] != "" || $this->goodsGroup[$key][$gkey]['good_size'] != ""){
			if ($max == -1 || ($this->goodsGroup[$key][$gkey]['color_size_storage'] < $max)){
				$max = $this->goodsGroup[$key][$gkey]['color_size_storage'];
			}
		}
		if ($max == -1 || ($this->goodsGroup[$key][$gkey]['storage'] < $max)){
			$max = $this->goodsGroup[$key][$gkey]['storage'];	
		}
		if ($max == -1){
			$max = 0;
			
		}
		if ($max<=0){
			
			//$this->deleItems($key,$gkey);
			if ($this->goodsGroup[$key][$gkey]['ifalarm']==0){
				//$this->goodsGroup[$key][$gkey]['count'] = 1;
				$max = 100;
			}
			else
				$this->goodsGroup[$key][$gkey]['count'] = 0;
		}
		//echo $max;exit;
		$this->goodsGroup[$key][$gkey]['maxstorage'] = $max;
	}
	/**
	刪除購物車中某商品
	**/
	function deleItems($key,$gkey){
		$present_array=array();
		$i = 0;
		$if_present = $this->goodsGroup[$key][$gkey]['ifpresent'];
		$ifpack = $this->goodsGroup[$key][$gkey]['ifpack'];
		$j = 0;
		foreach($this->goodsGroup[$key] as $k=>$v){
			//echo $v['changegid'];
			if ($v['changegid'] == $this->goodsGroup[$key][$gkey]['gid']){
				unset($this->goodsGroup[$key][$k]);
			}
			if ($v['packgid'] == $this->goodsGroup[$key][$gkey]['gid']&&$ifpack==1){
				unset($this->goodsGroup[$key][$k]);
			}
			if ($v['ifpresent']==1){
				$present_array[$i]['key'] = $key;
				$present_array[$i]['gkey'] = $v['gkey'];
				$present_array[$i]['present_money'] = $v['present_money'];
				$i++;
			}
			//if ($v['gkey'] == $gkey){
				//$offset = $j;	
			//}
			$j++;
		}
		//print_r($this->goodsGroup[$key]);
		//echo "a";
		//exit;
		//array_splice($this->goodsGroup[$key],$offset,1); 
		unset($this->goodsGroup[$key][$gkey]);
		/*
		$j = 0;
		foreach($this->goodsGroup as $k=>$v){
			if ($k == $key){
				$offset = $j;	
			}
			$j++;
		}
		
		unset($this->goodsGroup[$key][$gkey]);
		*/
		if (count($this->goodsGroup[$key])==0)
			unset($this->goodsGroup[$key]);
			//array_splice($this->goodsGroup,$offset,1); 
		if ($if_present!=1){
			$this->setTotal($key);
			foreach($present_array as $k=>$v){
				if ($this->discount_totalPrices < $v['present_money']){
					$this->deleItems($v['key'],$v['gkey']);
				}
			}
		}
		$this->setCheckTicket($key);
	}
	
	/**
	購物車信息統計
	**/
	function setTotal($key){
		global $INFO;
		$nosaletotal = 0; //不參加全館折扣的總計
		$saletotal = 0;//參加全館折扣的總計
		$linshitotal = 0;//總的商品金額
		$this->totalCount = 0;	
		$this->totalPrices = 0;
		$price_array = array();
		$total_array = array();
		$count_array = array();
		$buytype_array = array();
		$buycount_array = array();
		$buyprice_array = array();
		//$this->totalbonuspoint = 0;
		//foreach($this->goodsGroup as $keys=>$values){
		//全館折扣
		if (intval($INFO['allsaleoff'])>0 && intval($INFO['allsaleoff'])<100 && date("Y-m-d",time())>=$INFO['allsaleoff_begintime'] && date("Y-m-d",time())<=$INFO['allsaleoff_endtime']){
			$rate = round(intval($INFO['allsaleoff'])/100,2);
		}else{
			$rate = 1;	
		}
		$this->changeSalePrice($key);
		if (is_array($this->goodsGroup[$key])){
			$i = 0;
			foreach($this->goodsGroup[$key] as $k=>$v){
				if($v['packgid']==0){
					//print_r($this->discountsubject);
					//print_r($v);
					foreach($this->discountsubject as $kk=>$vv){
						if($v['dsid']==$vv['id'] && $v['ifds']==1){
							  $this->goodsGroup[$key][$k]['price'] = $v['price'] = $this->goodsGroup[$key][$k]['dsprice'];//exit;
							
							$count_array[$vv['id']] += $v['count'];
							$total_array[$vv['id']] += $v['count']*$v['price'];
							$mianyunfei_array[$vv['id']] = $vv['mianyunfei'];
							$min_money_array[$vv['id']] = $vv['min_money'];
							$min_count_array[$vv['id']] = $vv['min_count'];
							for($z=0;$z<$v['count'];$z++){
								$price_array[$vv['id']][$i] = $v['price'];
								$i++;
							}
							//$i++;
						}
					}
					//exit;
					//print_r($total_array);
					//print_r($count_array);
					$this->totalCount = intval($this->totalCount) + intval($v['count']);
					/*
					if($v['dsid']==0 || $v['ifds']==0){
						$this->totalPrices = intval($this->totalPrices) + intval($v['count']) * intval($v['price']);
					}
					*/
					if($v['dsid']==0 || $v['ifds']==0){
						$total = intval($v['count']) * intval($v['price']);
						$linshitotal += $total;
						if(intval($v['ifmore'])==0 && $v['ifsale']==0 && trim($v['rebateinfo'])=="" && $v['ifneworold']==0&&$v['ifadd']==0){
							$saletotal += $total;
						}else{
							$nosaletotal += $total;
						}
					}
					/*
					if ($v['ifbonus'] == 1){
						$this->totalbonuspoint = intval($this->totalbonuspoint) + intval($v['count']) * intval($v['bonuspoint']);
					}
					*/
				}
				
			}
			$this->totalPrices = $linshitotal;
			//$this->totalPrices = round($saletotal*$rate,0) + $nosaletotal;
			if(is_array($count_array)){
				foreach($count_array as $kk=>$vv){
					if($total_array[$kk]>=$min_money_array[$kk] || $count_array[$kk]>=$min_count_array[$kk]){
						$total_price += $total_array[$kk];
						//echo $total_array[$kk];echo "}}";
					}
				}
				$this->totalPrices += $total_price;
			}
			//exit;
		}
		//}
		$this->discount_totalPrices = $total_price + round($saletotal*$rate,0) + $nosaletotal;
		//$this->setAllSaleOff($key);
		//$this->setTicketMoney();
		//$this->setBonusMoney();
	}
	/**
	計算紅利
	**/
	function setTotalbonuspoint(){
		$this->totalbonuspoint = 0;
		if(is_array($this->goodsGroup)){
			foreach($this->goodsGroup as $keys=>$values){
				if (is_array($this->goodsGroup[$keys])){
					foreach($this->goodsGroup[$keys] as $k=>$v){
						if ($v['ifbonus'] == 1){
							$this->totalbonuspoint = intval($this->totalbonuspoint) + intval($v['count']) * intval($v['bonuspoint']);
						}
					}
				}
			}
		}
	}
	/**
	計算紅利
	**/
	function setGroupbonuspoint($key){
		$grouppoint;
		//if(is_array($this->goodsGroup)){
			//foreach($this->goodsGroup as $keys=>$values){
				if (is_array($this->goodsGroup[$key])){
					foreach($this->goodsGroup[$key] as $k=>$v){
						if ($v['ifbonus'] == 1){
							$grouppoint = intval($grouppoint) + intval($v['count']) * intval($v['bonuspoint']);
						}
					}
				}
				return intval($grouppoint);
			//}
		//}
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
	設置折價券
	**/
	function setTicket($tickets){
		$this->tickets['id'] = $tickets['id'];
		$this->tickets['money'] = $tickets['money'];
		$this->tickets['moneytype'] = $tickets['moneytype'];
		$this->tickets['type'] = $tickets['type'];
		$this->tickets['ticketcode'] = $tickets['ticketcode'];
		$this->tickets['goods_ids'] = $tickets['goods_ids'];
		$this->tickets['discount_money'] = 0;
		$this->setTicketMoney();
	}
	/**
	設置折價券優惠金額
	**/
	function setTicketMoney(){
		$discount_totalPrices = $this->discount_totalPrices;
		//優惠金額
		if ($this->tickets['money'] > 0){
			if ($this->tickets['moneytype']==0){
				$this->tickets['discount_money'] = $this->tickets['money']; 
				$this->discount_totalPrices = intval($this->discount_totalPrices - $this->tickets['money']);
			}else{
				$this->tickets['discount_money'] = intval($this->discount_totalPrices * (1-$this->tickets['money'])); 
				$this->discount_totalPrices = intval($this->discount_totalPrices * $this->tickets['money']);
			}
			if($this->discount_totalPrices < 0){
				$this->discount_totalPrices = 0;
			}
			   //優惠金額，比如300或500*0.5（500為總金額）
		}
	}
	/**
	检查是否有符合折价券的商品
	**/
	function setCheckTicket($key){
		$ifhave = 0;
		if ($this->tickets['goods_ids']!=""){
			$goods_ids_array = explode(",",$this->tickets['goods_ids']);
			if (!is_array($goods_ids_array)){
				return;	
			}
			if (is_array($this->goodsGroup[$key])){
				foreach($this->goodsGroup[$key] as $k=>$v){
					if(in_array($v['gid'],$goods_ids_array) && $v['count'] > 0){
						$ifhave = 1;	
					}
				}
			}
			if ($ifhave == 0){
				echo "<script language='javascript'>alert('您使用的折價券是針對特定商品的，您並沒有購買相關商品，所以不能使用。');</script>";
				$this->setTicket(array());
			}
		}
		
	}
	/**
	設置紅利
	**/
	function setBonus($bonuspoint,$key){
		$this->bonus['point'] = intval($bonuspoint);
		$this->setBonusMoney($key);
	}
	/**
	設置紅利后金額
	**/
	function setBonusMoney($key){
		if (intval($this->bonus['point']) > 0){
			$this->discount_totalPrices = intval($this->discount_totalPrices - $this->bonus['point']);
		}
		$goods = $this->goodsGroup[$key];
		if (is_array($goods)){
			foreach($goods as $keys=>$values){
				if ($values['ifpresent']==1 && $this->discount_totalPrices<$values['present_money']){
					$this->deleItems($key,$values['gkey']);
				}
			}
		}
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
					if($v['ifmood'] == 0)
						$iffree = 0;
						
					 $weight += $v['weight']*$v['count'];
				}	
			}
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
				//$this->transname = "當購買滿" . $this->sys_trans[0]['FreeTransMoney'] . "免運費。";
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
	更改
	**/
	function changeItems($key,$gkey,$valuekey,$value){
		$this->goodsGroup[$key][$gkey][$valuekey] = $value;
		$present_array=array();
		$i = 0;
		$if_present = $this->goodsGroup[$key][$gkey]['ifpresent'];
		$ifpack = $this->goodsGroup[$key][$gkey]['ifpack'];
		if ($if_present!=1){
			foreach($this->goodsGroup[$key] as $k=>$v){
				if ($v['ifpresent']==1){
					$present_array[$i]['key'] = $key;
					$present_array[$i]['gkey'] = $v['gkey'];
					$present_array[$i]['present_money'] = $v['present_money'];
					$i++;
				}
				//echo $v['packgid'];
				if ($v['packgid']==$this->goodsGroup[$key][$gkey]['gid'] && $ifpack==1){
					$this->goodsGroup[$key][$v['gkey']][$valuekey] = $value;
				}
			}
			$this->setTotal($key);
			foreach($present_array as $k=>$v){
				if ($this->discount_totalPrices < $v['present_money']){
					$this->deleItems($v['key'],$v['gkey']);
				}
			}
		}
		//$this->setCheckTicket($key);
		//$this->setSaleoff($key,$gkey);
		//$this->setTotal();
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
	
	function clearGoods($key){
		
		foreach($this->goodsGroup[$key] as $k=>$v){
			//$this->deleItems($key,$k);
			unset($this->goodsGroup[$key][$k]);
		}
		
		$this->goodsGroup[$key] = array();
		unset($this->goodsGroup[$key]);
	}
	
	function clearAddGoods($key,$gkey){
		if($this->goodsGroup[$key][$gkey]['ifadd']==0){
			if (is_array($this->goodsGroup[$key])){
				foreach($this->goodsGroup[$key] as $k=>$v){
				  if ($v['ifadd']==1){
					  $this->deleItems($key,$v['gkey']);
				  }
				}
			}
		}
	}
	
	/**
	設置多見折扣
	**/
	function setSaleSu($saleid,$salecount){
		$flag = 0;
		if(is_array($this->salesubject)){
			foreach($this->salesubject as $k=>$v){
				if ($v['id'] == $saleid)
					$flag = 1;
			}	
		}
		if ($flag == 0 ){
			$count = count($this->salesubject);
			$this->salesubject[$count][id] = $saleid;
			$this->salesubject[$count]['count'] = $salecount;
		}
	}
	
	/**
	更改多件折扣商品的價格
	**/
	function changeSalePrice($key){
		global $DB,$FUNCTIONS;
		global $INFO,$FUNCTIONS;
		if($this->goodsGroup[$key][$gkey]['ifneworold']==1)
			return;
		$sale_goods_array = array();
		$get_goods_array = array();
		if (is_array($this->goodsGroup[$key])){
			foreach($this->goodsGroup[$key] as $keys=>$values){
				//echo $values['sale_subject'];
				if ($values['sale_subject'] > 0 && $values['ifsale'] == 1){
					$sale_goods_array[$values['sale_subject']] = intval($sale_goods_array[$values['sale_subject']])+$values['count'];
				}
			}
		}
		foreach($sale_goods_array as $k=>$v){
			if (is_array($this->salesubject)){
			foreach($this->salesubject as $kk=>$vv){
				if ($vv['id'] == $k && $vv['count']<=$v){
					$get_goods_array[$k] = 1;
				}
			}
			}
		}
		//print_r($get_goods_array);
		//print_r($sale_goods_array);
		//print_r($this->salesubject);
		if (is_array($this->goodsGroup[$key])){
			foreach($this->goodsGroup[$key] as $keys=>$values){
				if ($values['sale_subject'] > 0 && $values['ifsale'] == 1 && $get_goods_array[$values['sale_subject']] == 1 && $values['dsid']==0 && $values['ifds']==0){
					$this->goodsGroup[$key][$keys]['price'] = $this->goodsGroup[$key][$keys]['sale_price'];
					$this->goodsGroup[$key][$keys]['nosaleoff'] = 1;
				}elseif ($values['ifsale'] == 1){
					$this->goodsGroup[$key][$keys]['price'] = $this->goodsGroup[$key][$keys]['org_price'];	
					$this->goodsGroup[$key][$keys]['nosaleoff'] = 0;
				}
			}
		}
	}
	
	/**
	設置活動主題
	**/
	function setDiscountInfo($saleid,$min_count,$min_money,$mianyunfei,$money,$buytype,$buycount,$buyprice){
		$flag = 0;
		if(is_array($this->discountsubject)){
			foreach($this->discountsubject as $k=>$v){
				if ($v['id'] == $saleid)
					$flag = 1;
			}	
		}
		if ($flag == 0 ){
			$count = count($this->discountsubject);
			$this->discountsubject[$count][id] = $saleid;
			$this->discountsubject[$count]['min_count'] = $min_count;
			$this->discountsubject[$count]['min_money'] = $min_money;
			$this->discountsubject[$count]['mianyunfei'] = $mianyunfei;
			$this->discountsubject[$count]['money'] = $money;
			$this->discountsubject[$count]['buytype'] = $buytype;
			$this->discountsubject[$count]['buycount'] = $buycount;
			$this->discountsubject[$count]['buyprice'] = $buyprice;
		}
	}
	
	/**
	更改活動商品的價格
	**/
	function changeDiscountPrice($key){
		global $DB,$FUNCTIONS;
		global $INFO,$FUNCTIONS;
		if($this->goodsGroup[$key][$gkey]['ifneworold']==1)
			return;
		$sale_goods_array = array();
		$total_goods_array = array();
		$key_goods_array = array();
		$get_goods_array = array();
		if (is_array($this->goodsGroup[$key])){
			foreach($this->goodsGroup[$key] as $keys=>$values){
				//echo $values['sale_subject'];
				if ($values['dsid'] > 0){
					$sale_goods_array[$values['dsid']] = intval($sale_goods_array[$values['dsid']])+$values['count'];
					$total_goods_array[$values['dsid']] = intval($total_goods_array[$values['dsid']])+$values['count']*$values['dsprice'];
				}
				$key_goods_array[$values['dsid']]  = $key;
			}
		}
		foreach($sale_goods_array as $k=>$v){
			if (is_array($this->discountsubject)){
				foreach($this->discountsubject as $kk=>$vv){
					if ($vv['id'] == $k && ($vv['min_count']<=$v && $vv['min_count']>0 || $vv['min_money']<=$total_goods_array[$k])){
						$get_goods_array[$k] = 1;
						$this->discountsaleoff[$key][$kk] = $vv['money'];
					}
					if ($vv['id'] == $k && $vv['mianyunfei']<=$total_goods_array[$k] && $vv['mianyunfei']>0){
						$this->manyunfei[$key_goods_array[$k]] = 1;	
						$this->manyunfei_money[$key_goods_array[$k]] = $vv['mianyunfei'];
					}else{
						$this->manyunfei[$key_goods_array[$k]] = 0;		
					}
					//if ($vv['id'] == $k ){
					//	$this->discountsaleoff[$key_goods_array[$k]] = $vv['money'];
					//}
				}
			}
		}
		//print_r($this->discountsaleoff);
		//print_r($sale_goods_array);
		//print_r($this->salesubject);
		if (is_array($this->goodsGroup[$key])){
			$i = 0;
			foreach($this->goodsGroup[$key] as $keys=>$values){
				if ($values['dsid'] > 0 && $get_goods_array[$values['dsid']] == 1){
					$this->goodsGroup[$key][$keys]['price'] = $this->goodsGroup[$key][$keys]['dsprice'];
					//$this->discountprice[$key][$values['dsid']][$i] = $this->goodsGroup[$key][$keys]['dsprice'];
					$this->goodsGroup[$key][$keys]['nosaleoff'] = 1;
					$this->goodsGroup[$key][$keys]['ifds'] = 1;
				}elseif ($values['dsid'] > 0){
					$this->goodsGroup[$key][$keys]['price'] = $this->goodsGroup[$key][$keys]['org_price'];	
					$this->goodsGroup[$key][$keys]['nosaleoff'] = 0;
					$this->goodsGroup[$key][$keys]['ifds'] = 0;
				}
				$i++;
			}
		}
	}
	
	function getdiscount($key){
		$discountmoney = 0;
		//$this->discount_totalPrices = 0;	
		$this->combipoint = 0;	
		/*
		if (is_array($this->goodsGroup[$key])){
			foreach($this->goodsGroup[$key] as $k=>$v){
				if ($v['memberorprice'] == 1){
					$this->discount_totalPrices = intval($this->discount_totalPrices) + intval($v['count']) * intval($v['price']);
				}else if ($v['memberorprice'] == 2){
					$this->discount_totalPrices = intval($this->discount_totalPrices) + intval($v['count']) * intval($v['memberprice']);
					$this->combipoint = intval($this->combipoint) + intval($v['count']) * intval($v['combipoint']);
				}else{
					$this->discount_totalPrices = intval($this->discount_totalPrices) + intval($v['count']) * intval($v['price']);	
				}
			}
		}
		*/
		foreach($this->discountsaleoff[$key] as $k=>$v){
			$discountmoney += 	$v;
		}
		//echo $discountmoney;
		$this->discount_totalPrices -= $discountmoney;
		//$this->setAllSaleOff($key);
		$this->setTicketMoney();
		$this->setBonusMoney($key);
	}
	
	//全館打折
	/*
	function setAllSaleOff($key){
		global $INFO;
		
		if (intval($INFO['allsaleoff'])==0 || intval($INFO['allsaleoff'])>=100 ){
			return false;	
		}elseif (date("Y-m-d",time())>=$INFO['allsaleoff_begintime'] && date("Y-m-d",time())<=$INFO['allsaleoff_endtime']){
			foreach($this->goodsGroup[$key] as $keys=>$values){
				if($value['ifmore']==0 && ($value['ifds']==0 || $value['dsid']==0) && $value['ifsale']==0)
			}
			 $this->discount_totalPrices = round(round($this->discount_totalPrices/100,2) * intval($INFO['allsaleoff']),0);
			$this->saleoffinfo = "[全館折扣x " . round(intval($INFO['allsaleoff'])/100,2) . "]";
		}else{
			return false;	
		}
	}
	*/	
	
	//買越多
	function setSaleoff($key,$gkey){
		global $DB,$FUNCTIONS;
		global $INFO,$FUNCTIONS;
		$value = $this->goodsGroup[$key][$gkey];
		if(($value['dsid']>0 && $value['ifds']==1)||($value['sale_subject']>0 && $value['ifsale']==1) || $value['iftimesale']==1){
			return $this->goodsGroup[$key][$gkey]['price'];
		}
		 $this->goodsGroup[$key][$gkey]['ifneworold'];
		if (intval($INFO['allsaleoff'])>0 && intval($INFO['allsaleoff'])<100 && date("Y-m-d",time())>=$INFO['allsaleoff_begintime'] && date("Y-m-d",time())<=$INFO['allsaleoff_endtime']){
			$ifallsaleoff = 1;
		}else{
			$ifallsaleoff = 0;	
		}
		if($this->goodsGroup[$key][$gkey]['ifneworold']==1){
			$Query = $DB->query("select g.gid,g.goodsname,g.unit,g.provider_id,g.good_color,g.good_size,g.nocarriage,g.smallimg,g.price,g.pricedesc,g.point ,g.ifjs,g.js_begtime,g.js_endtime,g.storage,g.if_monthprice,g.ifpresent,g.trans_special_money,g.trans_special,g.iftransabroad,g.trans_type,g.ifxygoods,g.ifchange,g.ifbonus,g.bonusnum,g.ifalarm,g.addmoney,g.ifadd,g.addprice,g.oeid,g.timesale_starttime,g.timesale_endtime,g.iftimesale,g.saleoffprice,g.ifsales,g.sale_price,g.sale_subject,g.ifalarm,g.transtype,g.ifmood,g.addtransmoney,g.transtypemonty,g.memberprice,g.combipoint,g.bn,g.iftogether,g.weight,g.shopid,g.cost,g.salecost,g.bid,g.if_monthprice,g.month,g.bonus_statetime,g.bonus_endtime,newuser_starttime,newuser_endtime,newuser_price,g.olduser_price  from `{$INFO[DBPrefix]}goods` g where gid=".intval($this->goodsGroup[$key][$gkey]['gid'])." and g.ifchange!=1 and g.ifpub=1 limit 0,1 ");
			 $Num   = $DB->num_rows($Query);
			if ($Num>0) {
				$Rs=$DB->fetch_array($Query);
				if(intval($_SESSION['user_id'])>0)
						$ifnew = $FUNCTIONS->getSaleOrder(intval($_SESSION['user_id']));
					if($Rs['newuser_starttime']<=time() && $Rs['newuser_endtime']>=time() && ($Rs['newuser_price']>0 || $Rs['olduser_price']>0)){ 
						if($ifnew==1 && $Rs['newuser_price']>0){
							$this->goodsGroup[$key][$gkey]['price']  = $Rs['newuser_price'];	
							$this->goodsGroup[$key][$gkey]['org_price']  = $Rs['newuser_price'];	
						}elseif($Rs['olduser_price']>0 && intval($_SESSION['user_id'])>0){
							$this->goodsGroup[$key][$gkey]['price']  = $Rs['olduser_price'];	
							$this->goodsGroup[$key][$gkey]['org_price']  = $Rs['olduser_price'];		
						}
					}
			}
			return $this->goodsGroup[$key][$gkey]['price'];
		}
		if (intval($this->goodsGroup[$key][$gkey]['detail_id']) == 0 && $this->goodsGroup[$key][$gkey]['nosaleoff']==0){
			$Sql      = "select * from `{$INFO[DBPrefix]}goods_saleoffe` where gid='" . intval($this->goodsGroup[$key][$gkey]['gid']) . "' and mincount<=" . intval($this->goodsGroup[$key][$gkey]['count']) . " and (maxcount>=" . intval($this->goodsGroup[$key][$gkey]['count']) . " or maxcount=0) order by soid desc limit 0,1";
			$Query    = $DB->query($Sql);
			$Num      = $DB->num_rows($Query);
			if ($Num>0){
				
				$Result= $DB->fetch_array($Query);
				$this->goodsGroup[$key][$gkey]['price'] = $Result['price'];
				$this->goodsGroup[$key][$gkey]['rebateinfo'] = "";
				$this->goodsGroup[$key][$gkey]['costinfo'] = "";
				$this->goodsGroup[$key][$gkey]['ifmore'] = 1;
			}else{
				if ($this->goodsGroup[$key][$gkey]['ifbonus']==0 && $this->goodsGroup[$key][$gkey]['ifpresent']==0 && $this->goodsGroup[$key][$gkey]['Js_price']==0 && $this->goodsGroup[$key][$gkey]['iftimesale']==0 && $this->goodsGroup[$key][$gkey]['ifchange']==0 && $this->goodsGroup[$key][$gkey]['ifsale']==0 && $this->goodsGroup[$key][$gkey]['ifadd']==0 && $this->goodsGroup[$key][$gkey]['rebate']==0 && $ifallsaleoff==0 && $this->goodsGroup[$key][$gkey]['ifneworold']==0){
					 $MemberPiceReturn = $FUNCTIONS->MemberLevelPrice($_SESSION['user_level'],intval($this->goodsGroup[$key][$gkey]['gid']),intval($this->goodsGroup[$key][$gkey]['detail_id']));
					$this->goodsGroup[$key][$gkey]['price']       = $MemberPiceReturn>0 ? $MemberPiceReturn : $this->goodsGroup[$key][$gkey]['org_price'] ;
				}else{
					//$rebate_array = $FUNCTIONS->getTopClass(intval($Rs['bid']));
					//$goods_array['rebate'] = $rebate_array[0];
					//$goods_array['costrebate'] = $rebate_array[1];
					//echo intval($this->goodsGroup[$key][$gkey]['shopid']);
					if ($this->goodsGroup[$key][$gkey]['rebate']>0 && intval($this->goodsGroup[$key][$gkey]['shopid'])==0){
						//echo "ccccccccccccccc";
						//$this->goodsGroup[$key][$gkey]['rebateinfo'] = "[館別折扣x"  . round(intval($this->goodsGroup[$key][$gkey]['rebate'])/100,2) . "]";
						if ($this->goodsGroup[$key][$gkey]['costrebate']>0 && intval($this->goodsGroup[$key][$gkey]['shopid'])==0){
							$this->goodsGroup[$key][$gkey]['costinfo'] = "館別促銷折扣成本x"  . round(intval($this->goodsGroup[$key][$gkey]['costrebate'])/100,2) . "";
						}
					}
					$this->goodsGroup[$key][$gkey]['price'] = $this->goodsGroup[$key][$gkey]['org_price'];
				}
				$this->goodsGroup[$key][$gkey]['ifmore'] = 0;
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