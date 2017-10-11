<?php
include_once "SMTP.Class.inc.php";
include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
include_once "sms2.inc.php";
include_once "sendmsg.php";
$sendmsg = new SendMsg;

class orderClass{

	/**
	得到訂單狀態
	**/
	function getOrderState($state_value,$state_type){
		global $DB,$INFO;
		$Sql = "select * from `{$INFO[DBPrefix]}order_state` where state_value = '" . intval($state_value) . "' and state_type='" . intval($state_type) . "'";
		$Query  = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		return $Rs['order_state_name'];
	}

	/**
	設置訂單狀態
	**/
	function setOrderState($state_value,$state_type,$order_id,$remark,$ifadmin=1,$ifdetailstate=0){
		global $DB,$INFO,$SMTP,$sendmsg,$FUNCTIONS;
		$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where order_id = '" . intval($order_id) . "'";
		$Query_order  = $DB->query($Sql_order);
		$Rs_order=$DB->fetch_array($Query_order);
		$Order_serial     = $Rs_order['order_serial'];
		$User_id     = $Rs_order['user_id'];
		$Receiver_name    = $Rs_order['receiver_name'];
		$Receiver_email   = $Rs_order['receiver_email'];
		$Receiver_address = $Rs_order['receiver_address'];
		$receiver_mobile = $Rs_order['receiver_mobile'];
		$True_name        = $Rs_order['true_name'];
		$Order_state      = $Rs_order['order_state'];
		$ATM              = $Rs_order['atm'];
		$Pay_Content      = $Rs_order['paycontent'];
		$Pay_Name         = $Rs_order['paymentname'];
		$Pay_Deliver      = $Rs_order['deliveryname'];
		$totalprice       = $Rs_order['totalprice'];
		$sendpoint       = $Rs_order['sendpoint'];
		$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
		$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
		$recommendno = $Rs_order['recommendno'];
		$MallgicOrderId = $Rs_order['MallgicOrderId'];
		$discount_totalPrices = $Rs_order['discount_totalPrices'];
		$ifgroup = $Rs_order['ifgroup'];
		$buyGroup = $Rs_order['buyGroup'];
		$totalGrouppoint = $Rs_order['totalGrouppoint'];
		$backprice = $Rs_order['backprice'];
		$buyPoint = $Rs_order['buyPoint'];
		$giveGroup = $Rs_order['giveGroup'];
		switch ($state_type){
			case "1":
				$order_state = $Rs_order['order_state'];
				$state_name = "order_state";
				break;
			case "2":
				$order_state = $Rs_order['pay_state'];
				$state_name = "pay_state";
				break;
			case "3":
				$order_state = $Rs_order['transport_state'];
				$state_name = "transport_state";
				break;
		}
		$order_state_array = array($Rs_order['order_state'],$Rs_order['pay_state'],$Rs_order['transport_state']);
		//echo $state_value;
		$flag = $this->ifPreviousState($state_value,$state_type,$order_state_array);
		//echo $flag;exit;
		if ($flag==1){
			if($ifadmin==1){
				$user_id = $_SESSION['sa_id'];
				$usertype = $_SESSION['LOGINADMIN_TYPE'];
			}else{
				$user_id = $_SESSION['user_id'];
				$usertype = -1;
				if ($user_id!=$User_id){
					return 0;
				}
			}
			$Sql_insert = "insert into `{$INFO[DBPrefix]}order_action` (order_id,state_value,user_id,usertype,remark,actiontime,state_type,order_detail_id) values ('" . $order_id . "','" . $state_value . "','" . $user_id . "','" . $usertype . "','" . $remark . "','" . time() . "','" . $state_type . "',0)";
			$DB->query($Sql_insert);
			$Sql_update_o = "update `{$INFO[DBPrefix]}order_table` set " . $state_name . "='" . $state_value . "' where order_id='" . $order_id . "'";
			$DB->query($Sql_update_o);
			if ($ifdetailstate===0){

				$Sql_detail = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . $order_id . "'";
				$Query_detail  = $DB->query($Sql_detail);
				while($Rs_detail=$DB->fetch_array($Query_detail)){
					if ($state_value==1&&$state_type==3){
						$h = -1;
					}else{
						$h = -2;
					}
					$this->setOrderDetailState($state_value,$state_type,$order_id,$Rs_detail['order_detail_id'],$remark,$h,0,$ifadmin);
				}
			}else{
				$Sql_detail = "select count(*) as statecount from `{$INFO[DBPrefix]}order_detail` where order_id='" . $order_id . "' and  detail_" . $state_name . "='" . $state_value . "'";
				$Query_detail  = $DB->query($Sql_detail);
				$Rs_detail=$DB->fetch_array($Query_detail);
				$statecount = $Rs_detail['statecount'];
				$Sql_detail = "select count(*) as allcount from `{$INFO[DBPrefix]}order_detail` where order_id='" . $order_id . "'";
				$Query_detail  = $DB->query($Sql_detail);
				$Rs_detail=$DB->fetch_array($Query_detail);
				$allcount = $Rs_detail['allcount'];
				if ($statecount==$allcount){
					$state_value = $state_value;
				}elseif ($statecount<$allcount){
					$Sql = "select * from `{$INFO[DBPrefix]}order_state` where state_value = '" . intval($state_value) . "' and state_type='" . intval($state_type) . "'";
					$Query  = $DB->query($Sql);
					$Rs=$DB->fetch_array($Query);
					if ($Rs['partstate']>0 && $Rs['partstate']!=10000){
						$state_value = $Rs['partstate'];
					}else{
						$partstate = $Rs['partstate'];
					}
				}

			}

			if (($state_value==3 && $state_type==1) || ($state_value==6 && $state_type==2)){
				//取消訂單返回紅利
				$FUNCTIONS->AddBonuspoint(intval($User_id),intval($Pay_point),2,"訂單" . $Order_serial,1,$order_id);
				$FUNCTIONS->AddGrouppoint(intval($User_id),intval($totalGrouppoint),"訂單" . $Order_serial,$order_id);
				$FUNCTIONS->AddBuypoint(intval($User_id),intval($buyPoint),0,"訂單取消" . $Order_serial . "退還購物金",$order_id,$user_id,$usertype,2);
			}
			if ($state_value==4 && $state_type==1){
				$Sql_detail = "select sum(point*hadsend) as thepoint from `{$INFO[DBPrefix]}order_detail` where order_id='" . $order_id . "' and packgid=0 group by order_id";
				$Query_detail  = $DB->query($Sql_detail);
				$Rs_detail=$DB->fetch_array($Query_detail);
				$ThePoint = $Rs_detail['thepoint'];
				$total_Grouppoing = 0;
				if ($ifgroup==1){
					$total_Grouppoing = $giveGroup;
				}
				/*
				//實際訂單金額
				$Sql_detail = "select * from `{$INFO[DBPrefix]}order_detail` where order_id='" . $order_id . "' and detail_pay_state='5'";
				$Query_detail  = $DB->query($Sql_detail);
				$Rs_detail=$DB->fetch_array($Query_detail);
				$order_count=$DB->num_rows($Query_detail);
				if ($order_count>0){
					//因為退款返回紅利
					$FUNCTIONS->AddBonuspoint(intval($User_id),intval($Pay_point),2,"訂單" . $Order_serial,1,$order_id);
					$backPrice = 0;
					while($Rs_detail=$DB->fetch_array($Query_detail)){
						$backPrice = $backPrice+$Rs_detail['price']*$Rs_detail['goodscount'];
					}
					$discount_totalPrices = $totalprice-$backPrice;
				}
				*/

				//付款后得到積點
				$FUNCTIONS->AddBonuspoint(intval($User_id),intval($ThePoint),1,"訂單" . $Order_serial,1,$order_id);
				$FUNCTIONS->AddBonuspoint(intval($User_id),intval($discount_totalPrices-$backprice),1,"訂單" . $Order_serial,2,$order_id);
				$FUNCTIONS->AddBonuspoint(intval($User_id),intval($sendpoint),1,"訂單" . $Order_serial . "參加促銷活動贈送積點",1,$order_id);
				$FUNCTIONS->AddGrouppoint(intval($User_id),intval($total_Grouppoing),"訂單" . $Order_serial . "參加優惠活動贈送購物金",$order_id);
				//付款后升級
				$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}user` where user_id='".intval($User_id)."'  limit 0,1");
				$Num_old   = $DB->num_rows($Query_old);

				if ($Num_old>0 ){
					$Result= $DB->fetch_array($Query_old);
					$user_level = $Result['user_level'];

					$levelSql = "select * from `{$INFO[DBPrefix]}user_level` where vip_money<='" . ($discount_totalPrices-$backprice). "' and level_id<>'" . $user_level . "' and vip_money>0 order by vip_money desc limit 0,1";
					$levelQuery    = $DB->query($levelSql);
					$levelRs = $DB->fetch_array($levelQuery);
					$Num_level   = $DB->num_rows($levelQuery);
					if($Num_level>0){
						$ifuplevel = $levelRs['level_id'];
						$vip_yearmoney = $levelRs['vip_yearmoney'];
					}
					$u_sql = "select sum(discount_totalPrices-backprice) as discount_totalPrices from `{$INFO[DBPrefix]}order_table` where user_id='" . trim($User_id) . "' and pay_state=1 and order_state=4 and createtime>='" . (time()-60*60*24*365) . "' and createtime<='" . time() . "'";
					$Query_u=$DB->query($u_sql);
					$Rs_u = $DB->fetch_array($Query_u);
					 $levelSql = "select * from `{$INFO[DBPrefix]}user_level` where vip_yearmoney<='" . $Rs_u['discount_totalPrices'] . "' and level_id<>'" . $user_level . "' and vip_yearmoney>0 order by vip_yearmoney desc limit 0,1";
					$levelQuery    = $DB->query($levelSql);
					$levelRs = $DB->fetch_array($levelQuery);
					$Num_level   = $DB->num_rows($levelQuery);
					if($Num_level>0){
						if(($ifuplevel>0 && $vip_yearmoney<$levelRs['vip_yearmoney']) || $ifuplevel==0)
							 $ifuplevel = $levelRs['level_id'];
					}

					//echo $ifuplevel;exit;
					if($ifuplevel>0){
						$uSql = "update `{$INFO[DBPrefix]}user` set user_level='" . $ifuplevel . "',ifhandlevel=0  where user_id='".intval($User_id)."'";
						$DB->query($uSql);
					}
				}
				if ($recommendno!=""){
					$u_sql = "select * from `{$INFO[DBPrefix]}user` where memberno='" . trim($recommendno) . "'";
					$Query_u=$DB->query($u_sql);
					$Rs_u = $DB->fetch_array($Query_u);
					$ruserid = $Rs_u['user_id'];
					if ($discount_totalPrices>0 && intval($INFO['recommendBuy'])>0){
						$rorderpoint = intval($discount_totalPrices/intval($INFO['recommendBuy']))*intval($INFO['recommendBuyPoint']);
						$FUNCTIONS->AddBonuspoint(intval($ruserid),intval($rorderpoint),5,"訂單" . $Order_serial,1,$order_id);
					}
				}
				if (($discount_totalPrices-$backprice)>0 && intval($INFO['ordermoney'])<=($discount_totalPrices-$backprice) && intval($INFO['ordermoney'])>0){
					$orderpoint = intval(($discount_totalPrices-$backprice)/intval($INFO['ordermoney']))*intval($INFO['orderpoint']);
					$FUNCTIONS->AddBonuspoint(intval($User_id),intval($orderpoint),3,"訂單" . $Order_serial,1,$order_id);
				}
			}
			//掛單號
			if((($state_value==1 && $state_type==3) || ($state_value==8 && $state_type==3)) && $_POST['piaocode']!=""){
				$Subsql = "piaocode='" . $_POST['piaocode'] . "',sendtime='" . $_POST['sendtime'] . "',sendname='" . $_POST['sendname'] . "',";
			}
			//申請退貨
			if(($state_value==5 && $state_type==3) || ($state_value==2 && $state_type==1)){
				$Subsql = "return_bank='" . $_POST['bank'] . "',return_bankcode='" . $_POST['jikuan'] . "',return_account='" . $_POST['account'] . "',return_acountname='" . $_POST['accountname'] . "',return_certcode='" . $_POST['certcode'] . "',";
			}
			if($partstate==10000)
				$u_Sql = "update  `{$INFO[DBPrefix]}order_table` set " . $Subsql . "order_state='6'," . $state_name . "='" . $state_value . "' where order_id='" . $order_id . "'"	;
			else{
				//if($state_value==1 && $state_type==1)
				//	$sub_Sql = ",transport_state=12";
				 $u_Sql = "update  `{$INFO[DBPrefix]}order_table` set " . $Subsql . "" . $state_name . "='" . $state_value . "'" . $sub_Sql . " where order_id='" . $order_id . "'"	;
			}
			$DB->query($u_Sql);
			/*if ($state_value==18 && $state_type==3){
				//清空收藏商品
				$Sql = "select g.smallimg,g.goodsname,g.intro,g.price,g.pricedesc,g.gid,c.collection_id,g.bn from `{$INFO[DBPrefix]}goods` g  inner join `{$INFO[DBPrefix]}collection_goods` c on (c.gid=g.gid)  where c.user_id='".intval($User_id)."' and g.extendbid like '%\"6\"%' order by c.cidate desc ";

				$Query = $DB->query($Sql);
				$Num   = $DB->num_rows($Query);
				while ($Rs=$DB->fetch_array($Query)) {
					$d_Sql = "delete from `{$INFO[DBPrefix]}collection_goods` where collection_id='" . $Rs['collection_id'] . "'";
					$DB->query($d_Sql);
				}
			}*/
			//交易完成上傳
			if ($state_value==4 && $state_type==1 && $INFO['mod.einvoice.type']=="allpay"){
				//echo "aaa";exit;
				//串接發票
				include_once "allpay.class.php";
				$allpay = new allPay;
				$allpay->postInvo($Rs_order);
			}
			if(($Rs_order['order_state']==1 && $state_value==1 && $state_type==2) || ($Rs_order['pay_state']==1 && $state_value==1 && $state_type==1) && $INFO['mod.einvoice.type']=="pay2go"){
				//echo "aaa";exit;
				//串接發票
				include_once "pay2go.class.php";
				$pay2go = new pay2go;
				$pay2go->postInvo($Rs_order);
			}

		}
		return $flag;
	}

	/**
	設置詳細訂單狀態
	**/
	function setOrderDetailState($state_value,$state_type,$order_id,$detail_id,$remark,$hadsend=-1,$ifsingle=0,$ifadmin=1,$backPrice=-1,$backBouns=-1){

		global $DB,$INFO,$FUNCTIONS,$_POST;
		$Sql = "select * from `{$INFO[DBPrefix]}order_state` where state_value = '" . intval($state_value) . "' and state_type='" . intval($state_type) . "'";
		$Query  = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		$partstate = $Rs['partstate'];

		$Sql_had = "";
		$Sql_order = "select od.*,ot.* from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}order_table` as ot on od.order_id=ot.order_id where od.order_id = '" . intval($order_id) . "' and od.order_detail_id='" . intval($detail_id) . "'";
		$Query_order  = $DB->query($Sql_order);
		$Rs_order=$DB->fetch_array($Query_order);
		$User_id     = $Rs_order['user_id'];
		$goodscount     = $Rs_order['goodscount'];
		$gid      = $Rs_order['gid'];
		$good_color       = $Rs_order['good_color'];
		$good_size       = $Rs_order['good_size'];
		$return_bank       = $Rs_order['return_bank'];
		$return_bankcode       = $Rs_order['return_bankcode'];
		$return_account       = $Rs_order['return_account'];
		$return_acountname       = $Rs_order['return_acountname'];
		$return_certcode       = $Rs_order['return_certcode'];
		if($Rs_order['xygoods']!="")
			$xygoods       = explode(",",$Rs_order['xygoods']);
		else
			$xygoods       = array();
		 $detail_ids       = $Rs_order['detail_id'];
		switch ($state_type){
			case "1":
				$order_state = $Rs_order['detail_order_state'];
				$state_name = "detail_order_state";
				break;
			case "2":
				$order_state = $Rs_order['detail_pay_state'];
				$state_name = "detail_pay_state";
				break;
			case "3":
				$order_state = $Rs_order['detail_transport_state'];
				$state_name = "detail_transport_state";
				break;
		}
		$order_state_array = array($Rs_order['detail_order_state'],$Rs_order['detail_pay_state'],$Rs_order['detail_transport_state']);
		 $flag = $this->ifPreviousState($state_value,$state_type,$order_state_array);
		 if($ifadmin==1){
					$user_id = $_SESSION['sa_id'];
					$usertype = $_SESSION['LOGINADMIN_TYPE'];
				}else{
					$user_id = $_SESSION['user_id'];
					$usertype = -1;
					if ($user_id!=$User_id){
						return 0;
					}
				}

		if ($flag==1){
			if ($state_type==2&&$state_value==4 && ($backPrice>=0 || $backBouns>=0)){

				if ($backPrice>0){
				}elseif($backPrice==-1){
					$backPrice = $Rs_order['price']*$Rs_order['goodscount'];
				}
				if ($backPrice>0){
					$remark .= "<br>退還" . $backPrice . "元商品款";
				}
				if ($backBouns>0){
					$remark .= "<br>退還" . $backBouns . "點積點";
				}
				$iSql = "insert into `{$INFO[DBPrefix]}buypointrefund`(bank,bankcode,account,acountname,certcode,u_id,state,refundtime,point,remark,refundtype,order_id,backbouns)values('" . $return_bank . "','" . $return_bankcode . "','" . $return_account . "','" . $return_acountname . "','" . $return_certcode . "','" . $User_id . "',0,'" . time() . "','" . $backPrice . "','" . $remark . "','" . intval($_POST['refundtype']) . "','" . $order_id . "','" . $backBouns . "')";
				$DB->query($iSql);

				//更新訂單
				//$back_Sql = "update `{$INFO[DBPrefix]}order_table` set totalprice=totalprice-" . intval($backPrice) . ",discount_totalPrices=discount_totalPrices-" . intval($backPrice) . ",bonuspoint=bonuspoint-" . intval($backBouns) . ",refundtype='" . intval($_POST['refundtype']) . "' where order_id='" . $order_id . "'";


				//print_r($_POST);exit;
				/*
				if(intval($_POST['refundtype'])==0){

					$FUNCTIONS->AddBuypoint(intval($User_id),intval($backPrice),0,"退貨" . $Order_serial . "退還購物金",$order_id,$user_id,$usertype,3);
				}
				*/

			}
			if ($ifsingle==1){

				$Sql_insert = "insert into `{$INFO[DBPrefix]}order_action` (order_id,state_value,user_id,usertype,remark,actiontime,state_type,order_detail_id,backprice) values ('" . $order_id . "','" . $state_value . "','" . $user_id . "','" . $usertype . "','" . $remark . "','" . time() . "','" . $state_type . "','" . $detail_id . "','" . $backPrice . "')";
				$DB->query($Sql_insert);
			}
			if (($state_value==1&&$state_type==3)||($state_value==8&&$state_type==3)){
				if ($hadsend==-1){
					$Sql_had = ",hadsend=goodscount";
				}elseif ($hadsend==-2){
				}else{
					$Sql_had = ",hadsend='" . $hadsend . "'";
				}
			}
			if($state_value==20&&$state_type==3){
				$Sql_had = ",hadsend=0";
			}
			if($state_value==4&&$state_type==1){
				$us_Sql = "update `{$INFO[DBPrefix]}goods` set salenum=salenum+1 where gid='" . $gid ."'";
				$DB->query($us_Sql);
			}
			//if($state_value==1 && $state_type==1)
			//		$sub_Sql = ",transport_state=12";
			$Sql_update_o = "update `{$INFO[DBPrefix]}order_detail` set " . $state_name . "='" . $state_value . "'" . $Sql_had . "" . $sub_Sql . " where order_detail_id='" . $detail_id . "' or (packgid='" . $gid . "')";
			$DB->query($Sql_update_o);
			if($partstate==10000){
				$u_Sql = "update  `{$INFO[DBPrefix]}order_detail` set detail_order_state='6' where order_detail_id='" . $detail_id . "'"	;
				$DB->query($u_Sql);
			}
			if (($state_value==3&&$state_type==1)||($state_value==6&&$state_type==3)){
				$FUNCTIONS->setStorage(intval($goodscount),"0",intval($gid),intval($detail_ids),$good_size,$good_color,"",1,$order_id,$xygoods);
			}



		}
		return $flag;

	}


	/**
	判斷狀態是否可以被更改
	**/
	function ifPreviousState($state_value,$state_type,$order_state){
		global $DB,$INFO;

		//print_r($order_state);
		$Sql = "select * from `{$INFO[DBPrefix]}order_state` where state_value = '" . intval($state_value) . "' and state_type='" . intval($state_type) . "'";
		$Query  = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		//print_r($Rs);
		for($i=1;$i<=3;$i++){
			$Sql_state = "select * from `{$INFO[DBPrefix]}order_state` where state_value = '" . intval($order_state[$i-1]) . "' and state_type='" . $i . "'";
			$Query_state  = $DB->query($Sql_state);
			$Rs_state=$DB->fetch_array($Query_state);
			$order_state_id_array[$i] = $Rs_state['order_state_id'];
		}
		$flag = 0;
		$flag_array = array();
		//print_r($order_state_id_array);
		if ($Rs['order_state_id']>0){
			$previous_state = $Rs['previous_state'];
			if ($previous_state!=""){
				$previous_state_array = explode("||",$previous_state);
				if(is_array($previous_state_array)){
					foreach($previous_state_array as $k=>$v){
						$previous_state_d_array = explode(",",$v);
						foreach($previous_state_d_array as $kk=>$vv){
							//echo "v".$k."s".$order_state_id_array[$k+1]."a".$vv;
							if ($order_state_id_array[$k+1]==$vv||trim($vv)=="")
								$flag_array[$k] = 1;
						}
					}
				}
				//print_r($flag_array);
				if (intval($flag_array[0])==1&&$flag_array[1]==1&&$flag_array[2]==1)
					$flag = 1;
				else
					$flag=0;
			}else{
				$flag = 1;
			}
		}
		//echo $flag;
		return $flag;
	}

	/**
	發送郵件、短消息
	**/
	function postMail($order_id,$state_value,$state_type){
		include_once 'crypt.class.php';
		global $DB,$INFO,$SMTP,$sendmsg;
		switch ($state_type){
			case "1":
				$order_state = $Rs_order['order_state'];
				$state_name = "order_state";
				break;
			case "2":
				$order_state = $Rs_order['pay_state'];
				$state_name = "pay_state";
				break;
			case "3":
				$order_state = $Rs_order['transport_state'];
				$state_name = "transport_state";
				break;
		}
		//客服郵件地址
		$Sql      = "select * from `{$INFO[DBPrefix]}operater` where  groupid =8 and status =1";
		$Query    = $DB->query($Sql);
		$operater_array = array();
		$j = 0;
		while($Rs_o=$DB->fetch_array($Query)){
			$operater_array[$j] = $Rs_o['email'];
			$j++;
		}
		$operater_str = implode(",",$operater_array);
		$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where order_id = '" . intval($order_id) . "' and " . $state_name . "='" . intval($state_value) . "' ";
		$Query_order  = $DB->query($Sql_order);
		$Num   = $DB->num_rows($Query_order);
		if ($Num>0){
			$Rs_order=$DB->fetch_array($Query_order);
			$Order_serial     = $Rs_order['order_serial'];
			$User_id     = $Rs_order['user_id'];
			$Receiver_name    = $Rs_order['receiver_name'];
			$Receiver_email   = $Rs_order['receiver_email'];
			$Receiver_address = $Rs_order['receiver_address'];
			$receiver_mobile = MD5Crypt::Decrypt ($Rs_order['receiver_mobile'], $INFO['mcrypt']);
			$True_name        = $Rs_order['true_name'];
			$Order_state      = $Rs_order['order_state'];
			$ATM              = $Rs_order['atm'];
			$Pay_Content      = $Rs_order['paycontent'];
			$Pay_Name         = $Rs_order['paymentname'];
			$Pay_Deliver      = $Rs_order['deliveryname'];
			$Pay_Idate        = date("Y-m-d H:i a",$Rs_order['createtime']);
			$Pay_point      = $Rs_order['bonuspoint']+$Rs_order['totalbonuspoint'];
			$Sql = "SELECT  * FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($User_id)."' limit 0,1";
			$Query  = $DB->query($Sql);
			$Rs=$DB->fetch_array($Query);
			$caddress = str_replace("請選擇","",$Rs[Country].$Rs[canton].$Rs[city]);//地址
			$ctel = $Rs[tel].",".$Rs['other_tel'];//电话
			$username = $Rs['username'];
			$recommendno = $Rs['recommendno'];
			$companyid = $Rs['companyid'];
			$True_name = $Rs['true_name'];
			$useremail = $Rs['email'];
			if($User_id==0){
				$True_name = $Receiver_name;
				$useremail = $Receiver_email;
			}
			$Sql = "select * from `{$INFO[DBPrefix]}order_state` where state_value = '" . intval($state_value) . "' and state_type='" . intval($state_type) . "'";
			$Query  = $DB->query($Sql);
			$Rs=$DB->fetch_array($Query);
			$mailno = intval($Rs['mailno']);

			$Array =  array("Order_id"=>$order_id,"username"=>trim($username),"truename"=>trim($True_name),"receiver_name"=>trim($Receiver_name),"orderid"=>trim($Order_serial),"receiver_address"=>$Receiver_address,"ATM"=>$ATM,"pay_content"=>$Pay_Content,"pay_name"=>$Pay_Name,"pay_deliver"=>$Pay_Deliver,"pay_idate"=>$Pay_Idate);
			if ($Rs['order_state_id']==35 || $Rs['order_state_id']==36 || $Rs['order_state_id']==37)
				$useremail = $useremail . "," . $operater_str;
			//echo $Receiver_email;
			$SMTP->MailForsmartshop(trim($useremail),"",$mailno,$Array);
			$sendmsg->send(trim($receiver_mobile),$Array,$mailno);
		}
	}

	/**
	得到會員前臺能進行操作的按鈕
	**/
	function getUserOp($order_id,$ifphone=""){
		global $DB,$INFO;

		$button_array = array();

		$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where order_id = '" . intval($order_id) . "'";
		$Query_order  = $DB->query($Sql_order);
		$Rs_order=$DB->fetch_array($Query_order);
		$order_state_array = array($Rs_order['order_state'],$Rs_order['pay_state'],$Rs_order['transport_state']);
		$order_time = $Rs_order['createtime']+7*24*60*60;
		$op_array = array(array("申請取消","order_showact.php?state_value=2&state_type=1&order_id=" . $order_id . "&optype=1"),array("重新付款","../shopping/payorder.php?orderno=" . $Rs_order['order_serial']),array("申請換貨","order_showact.php?state_value=3&state_type=3&order_id=" . $order_id . "&optype=1"),array("申請退貨","order_showact.php?state_value=5&state_type=3&order_id=" . $order_id . "&optype=1"));//array("申請退貨","javascript:changeOrderState(5,3," . $order_id . ")"array("申請退貨","javascript:location.href='kefu_add.php?order_serial=" . $Rs_order['order_serial'] . "&type=back';")
		if($ifphone==1){
			$op_array[1] = array("重新付款","payorder.php?orderno=" . $Rs_order['order_serial']);
		}

		if(($Rs_order['order_state']==0||$Rs_order['order_state']==1)&&($Rs_order['transport_state']==0 )){
			$button_array[count($button_array)] = $op_array[0];
		} //申請取消
		/*if(($Rs_order['pay_state']==0||$Rs_order['pay_state']==2)&&($Rs_order['order_state']==0||$Rs_order['order_state']==1)){
			$button_array[count($button_array)] = $op_array[1];
		}*/ //重新付款
		/*if($Rs_order['order_state']==1 && $Rs_order['pay_state']==1 && ($Rs_order['transport_state']==2||$Rs_order['transport_state']==1||$Rs_order['transport_state']==18) ){
			$button_array[count($button_array)] = $op_array[2];
		} //申請換貨 */
		/*if($Rs_order['order_state']==1 && $Rs_order['pay_state']==1 && ($Rs_order['transport_state']==2||$Rs_order['transport_state']==1||$Rs_order['transport_state']==18) ){
			$button_array[count($button_array)] = $op_array[3];
		} //申請退貨*/
		return $button_array;
	}
	/**
	得到會員前臺能進行操作的按鈕
	**/
	function getUserDetailOp($order_id,$detail_id){
		global $DB,$INFO;

		$button_array = array();

		 $Sql_order = "select od.*,ot.createtime from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}order_table` as ot on od.order_id=ot.order_id where od.order_id = '" . intval($order_id) . "' and od.order_detail_id='" . $detail_id . "'";
		$Query_order  = $DB->query($Sql_order);
		$Rs_order=$DB->fetch_array($Query_order);
		$order_state_array = array($Rs_order['detail_order_state'],$Rs_order['detail_pay_state'],$Rs_order['detail_transport_state']);
		//print_r($order_state_array);
		$order_time = $Rs_order['createtime']+7*24*60*60;
		$op_array = array(array("申請換貨","order_showact.php?state_value=3&state_type=3&order_id=" . $order_id . "&optype=1"),array("申請退貨","order_showact.php?state_value=5&state_type=3&order_id=" . $order_id . "&optype=1"));



		if($Rs_order['detail_transport_state']==1 && time()<=$order_time){
			$button_array[count($button_array)] = $op_array[0];
		}
		if(($Rs_order['order_state']==0||$Rs_order['order_state']==1)&&$Rs_order['detail_transport_state']==1 && time()<=$order_time && $Rs_order['ifbonus']==0){
			$button_array[count($button_array)] = $op_array[1];
		}
		return $button_array;
	}

	/**
	審核操作
	**/
	function checkStateAction($state_action_id,$op){
		global $DB,$INFO;
		$Sql = "update `{$INFO[DBPrefix]}order_action` set ifcheck='" . $op . "' where action_id='" . $state_action_id . "'";
		$Query  = $DB->query($Sql);
		return $Query;
	}
}
?>
