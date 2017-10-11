<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
class USERS{
	
	/**
	根據會員ID得到會員編號
	**/
	function getRecommendno($user_id){
		global $DB,$INFO;
		$Sql = "SELECT memberno FROM `{$INFO[DBPrefix]}user`  where user_id='".intval($user_id)."' limit 0,1";
		$Query  = $DB->query($Sql);
		$Rs=$DB->fetch_array($Query);
		return $Rs['memberno'];
	}
	
	/**
	判斷是否登錄
	**/
	function checkLogin($return_type=0){
		if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
			if($return_type==0)
				@header("location:login_windows.php");
			else
				return 0;
		}else{
			return 1;	
		}
	}
	
	
	/**
	判斷是否存在
	**/
	function checkExist($type,$value){
		global $DB,$INFO;
		switch($type){
			case "username":	
				$subsql = " username='".trim($value)."'";
				break;
			case "email":	
				$subsql = " email='".trim($value)."'";
				break;
			case "memberno":	
				$subsql = " memberno='".trim($value)."'";
				break;
			default:
				$subsql = " username='".trim($value)."'";
		}
		if($_SESSION['user_id']>0)
			$subsql .= " and user_id<>'".intval($_SESSION['user_id'])."'";
		$Sql = "select username from `{$INFO[DBPrefix]}user`  where " . $subsql . " limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num>0){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	會員登錄
	**/
	function UserLogin($username,$password){
		global $DB,$INFO,$MemberLanguage_Pack,$FUNCTIONS;
		$Sql  = "select u.*,l.level_name from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.username='".trim($username)."' and u.password='".md5(trim($password))."' and u.user_state!=1 limit 0,1";
		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result = $DB->fetch_array($Query);
			
			$_SESSION['user_id']      = $Result['user_id'];
			$_SESSION['username']     = $Result['username'];
			$_SESSION['true_name']    = $Result['true_name'];
			$_SESSION['user_level']   = $Result['user_level'];
			if (intval($Result['user_level'])!=0){
				$_SESSION['userlevelname']  = $Result['level_name'];
			}else{
				$_SESSION['userlevelname']  = $MemberLanguage_Pack[Member_say];
			}
			$_SESSION['YesPass'] = "YesPass";
			
			$this->LoginBonus($Result['user_id'],$Result['username']);//登入送紅利
			//echo "aaa";
			$this->UpLevel($Result['user_id'],$Result['user_level'],$Result['ifhandlevel']);
			//登陸日誌
			$IP = $FUNCTIONS->getip();
			$DB->query( "insert into  `{$INFO[DBPrefix]}user_log` (user_id,ip,logintime) values('".$_SESSION['user_id']."','".$IP."','".time()."')");
			//購物車處理
			
			 $Sql = "UPDATE `{$INFO[DBPrefix]}shopping` SET user_id='" . $_SESSION['user_id'] . "' where user_id='' and session_id='" . $session_id . "'";
			$Result_Update = $DB->query($Sql);
			return 1;
		}else{
			$_SESSION['user_id']     = '';
			$_SESSION['username']    = '';
			$_SESSION['true_name']    = '';
			$_SESSION['user_level']  = '';
			$_SESSION['userlevelname'] ='';
			return 0;
		}
	}
	/**
	登錄送紅利
	**/
	function LoginBonus($user_id,$username=""){
		global $DB,$INFO,$FUNCTIONS;
		$d = intval(date('d',time()));
		$y = intval(date('Y',time()));
		$m = intval(date('m',time()));
		$overtime = gmmktime(0,0,0,$m,$d,$y);
		$B_Sql = "select * from `{$INFO[DBPrefix]}bonuspoint` where `type`=8 and user_id='" . intval($user_id) . "' and addtime>='" . $overtime . "' and addtime<='" . ($overtime+60*60*24) . "'";
		$B_Query = $DB->query($B_Sql);
		$B_Num   = $DB->num_rows($B_Query);
		if ($B_Num<=0)
			$FUNCTIONS->AddBonuspoint(intval($user_id),intval($INFO['loginpoint']),8,"會員登入" . trim($username),1,0);	
	}
	
	/**
	降級
	**/
	function UpLevel($user_id,$user_level,$ifhandlevel=0){
		global $DB,$INFO;
		if($ifhandlevel==0 ){
			 $levelSql = "select * from `{$INFO[DBPrefix]}user_level` where level_id='" . intval($user_level) . "' order by level_num desc";
			$levelQuery    = $DB->query($levelSql);
			$levelRs = $DB->fetch_array($levelQuery);
			$vip_days = $levelRs['vip_days']; //vip_days天沒消費降級為註冊會員級別
			if($vip_days>0){
				 $u_sql = "select count(*) as buycount from `{$INFO[DBPrefix]}order_table` where user_id='" . $user_id . "' and pay_state=1 and createtime>='" . (time()-intval($vip_days*60*60*24)) . "' and createtime<='" . time() . "'";	
				$Query_u=$DB->query($u_sql);
				$Rs_u = $DB->fetch_array($Query_u);
				if($Rs_u['buycount']<=0 ){
					 $uSql = "update `{$INFO[DBPrefix]}user` set user_level='" . intval($INFO['reg_userlevel']) . "',ifhandlevel=0  where user_id='".intval($user_id)."'";
					$DB->query($uSql);
					$levelSql = "select * from `{$INFO[DBPrefix]}user_level` where level_id='" . intval($INFO['reg_userlevel']) . "' order by level_num desc";
					$levelQuery    = $DB->query($levelSql);
					$levelRs = $DB->fetch_array($levelQuery);
					$_SESSION['user_level']   = $levelRs['level_id'];
					$_SESSION['userlevelname']  = $levelRs['level_name'];
				}
			}
		}
	}
	/**
	得到會員信息
	**/
	function getUserInfo($user_id,$type=0){
		global $DB,$INFO;
		include_once 'crypt.class.php';
		if($type==1)
			$where = " memberno='" . $user_id . "'";
		elseif($type==2)
			$where = " email='" . $user_id . "'";
		else
			$where = " user_id='" . $user_id . "'";
		$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}user` where " . $where . " limit 0,1");
		$Num    = $DB->num_rows($Query);
		if ($Num>0){
			$Result = $DB->fetch_array($Query);
			$return_array = $Result;
			$return_array['tel'] = MD5Crypt::Decrypt ($Result['tel'], $INFO['tcrypt'] );
			$return_array['other_tel'] = MD5Crypt::Decrypt ($Result['other_tel'], $INFO['mcrypt'] );
			$dateb = explode("-",$Result['born_date']);
			for ($i=date("Y",time())-60;$i<=date("Y",time())-1;$i++){
				$Born_year .= "<option value=".$i." ";
				if (intval(substr($Result['born_date'],0,4))==$i){
					$Born_year .= " selected ";
				}
				$Born_year .= " > ".$i."(民國" . ($i-1911) . "年)</option>\n";
			}
			$return_array['year'] = $Born_year;
			$Born_month .= " <SELECT name=bmonth class=\"small_choice\">";
			for ($i=1;$i<=12;$i++){
				$Born_month .= "<option value=".$i."" ;
				if ($dateb[1]==$i){
					$Born_month .= " selected ";
				}
				$Born_month .= " >".$i."月</option>";
			}
			$Born_month .=" </SELECT> ";
			$return_array['month'] = $Born_month;
			$Born_day .= " <SELECT name=bday class=\"small_choice\">";
			for ($j=1;$j<=31;$j++){
				$Born_day .= "<option value=".$j."" ;
				if ($dateb[2]==$j){
					$Born_day .= " selected ";
				}
				$Born_day .= " >".$j."日</option>";
			}
			$Born_day .=" </SELECT> ";
			$return_array['day'] = $Born_day;
		}
		return $return_array;
	}
	
	/**
	修改會員信息
	**/
	function setUserInfo($user_array,$user_id=0){
		global $DB,$INFO,$FUNCTIONS;	
		include_once 'crypt.class.php';
		
		$data_array = array (
		'true_name'         => trim($user_array['truename']),
		'sex'               => trim($user_array['sex']),
		'born_date'         => trim($user_array['byear']."-".$user_array['bmonth']."-".$user_array['bday']),
		'addr'              => trim($user_array['address']),
		'city'              => $user_array['city'],
		'canton'            => $user_array['province'],
		'Country'            => $user_array['county'],
		'zip'               => trim($user_array['othercity']),
		'post'              => trim($user_array['post']),
		'tel'               => MD5Crypt::Encrypt ( trim($user_array['phone']), $INFO['tcrypt']),
		'other_tel'         => MD5Crypt::Encrypt ( trim($user_array['mobile']), $INFO['mcrypt']),
		'dianzibao'         => intval($user_array['dianzibao'])
		);
		if($user_id>0){
			$db_string = $DB->compile_db_update_string($data_array );
			$Sql = "UPDATE `{$INFO[DBPrefix]}user` SET $db_string WHERE user_id=".intval($user_id);
			$Result_Update = $DB->query($Sql);
		}else{
			//註冊
			
			$companyid =  0;
			$userlevel = intval($INFO['reg_userlevel']);
			if($this->checkExist("email",$user_array['email'])==true){
				return -1;
			}
			if(trim($user_array['password'])!=trim($user_array['passwd2'])){
				return -2;	
			}
			//根據會員公司判斷會員級別，註冊紅利等
			if ($_POST['companypassword'] !=""){
				$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where openpwd='" . $_POST['companypassword'] . "' and ifpub=1 and (startdate<='" . date("Y-m-d") . "' or startdate='') and (enddate>='" . date("Y-m-d") . "' or enddate='') limit 0,1");
			}elseif($_SESSION['saler']!=""){
				$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where login='" . $_SESSION['saler'] . "' and ifpub=1 and (startdate<='" . date("Y-m-d") . "' or startdate='') and (enddate>='" . date("Y-m-d") . "' or enddate='') limit 0,1");
			}
			if ($Query_old !="")
				$Num_old   = $DB->num_rows($Query_old);
			if ($Num_old>0){
				$Result = $DB->fetch_array($Query_old);
				$companyid = intval($Result['id']);
				$userlevel = $Result['userlevel'];
				$givebouns  = intval($Result['givebouns']);
			}
			//會員編號
			$Query_country = $DB->query("select membercode from `{$INFO[DBPrefix]}area` where areaname='" . $_POST['county'] . "' and top_id=0");
			$Rs_country = $DB->fetch_array($Query_country);
			$firstcode = $Rs_country['membercode'];
			$memberno = $FUNCTIONS->setMemberCode($firstcode); 
			//推薦人
			if ($_POST['u_recommendno']!="")
				$u_recommendno = $_POST['u_recommendno'];
			else
				$u_recommendno = $_COOKIE['u_recommendno'];
			if($u_recommendno!=""){
				$Query_old = $DB->query("select  memberno from `{$INFO[DBPrefix]}user` where memberno='".trim($u_recommendno)."' limit 0,1");
				$Num_old   = $DB->num_rows($Query_old);
				if ($Num_old<=0){
					$FUNCTIONS->sorry_back('back',"您填寫的推薦人並不存在"); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
				}
			}
			$data_array['username']	= trim($user_array['email']);
			$data_array['password']	= md5(trim($user_array['password']));
			$data_array['memberno']	= trim($memberno);
			$data_array['recommendno']= trim($u_recommendno);
			$data_array['companyid']	= trim($companyid);
			$data_array['user_level']	= trim($userlevel);
			$data_array['email']	= trim($user_array['email']);
			$data_array['reg_date']	= date("Y-m-d",time());
			$data_array['reg_ip']	= $FUNCTIONS->getip();
			$db_string = $DB->compile_db_insert_string($data_array);
			 $Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			 $Result_Insert = $DB->query($Sql);
			 $Insert_id = $DB->insert_id();
			 //註冊成功
			 if ($Result_Insert){
				$FUNCTIONS->AddBonuspoint(intval($Insert_id),intval($INFO['regpoint']),6,"會員註冊" . trim($user_array['email']),1,0);
				if ($givebouns>0){
					$FUNCTIONS->AddBonuspoint(intval($Insert_id),intval($givebouns),7,"會員註冊經銷商分發積分" . trim($user_array['email']),1,0);	
				}
				if (trim($u_recommendno)!=""){
					$u_sql = "select * from `{$INFO[DBPrefix]}user` where memberno='" . trim($u_recommendno) . "'";
					$Query_u=$DB->query($u_sql);
					$Rs_u = $DB->fetch_array($Query_u);
					$ruserid = $Rs_u['user_id'];
					$FUNCTIONS->AddBonuspoint(intval($ruserid),intval($INFO['recommendPoint']),4,"推薦會員" . trim($user_array['email']),1,0);
				}
				$Array =  array("username"=>trim($_POST['email']),"truename"=>trim($_POST['truename']),"password"=>trim($_POST['password']));
				include "SMTP.Class.inc.php";
				include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
				$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
				$SMTP->MailForsmartshop($user_array['email'],"",1,$Array);
				
				include "sms2.inc.php";
				include "sendmsg.php";
				
				$sendmsg = new SendMsg;
				$sendmsg->send(trim($_POST['mobile']),$Array,1);
			}
		}
		
		if ($Result_Update)
		{
			return 1;
		}else{
			return 0;
		}
	}
	/**
	紅利記錄
	**/
	function getBonusList($user_id){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");
		$Sql      = "select bp.*,bt.typename from `{$INFO[DBPrefix]}bonuspoint` as bp inner join `{$INFO[DBPrefix]}bonustype` as bt on bp.type=bt.typeid where saleorlevel=1 and bp.user_id=".intval($user_id)." order by bp.id desc";
		$PageNav    = new PageItem($Sql,10);
		$Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$OrderList[$i]['point'] = $Rs['point'];
				$OrderList[$i]['id'] = $Rs['id'];
				$OrderList[$i]['addtime']   = date("Y-m-d",$Rs['addtime']);
				$OrderList[$i]['endtime']   = date("Y-m-d",$Rs['endtime']);
				$OrderList[$i]['type']   = trim($Rs['typename']);
				$OrderList[$i]['usestate']   = intval($Rs['usestate']);
				$OrderList[$i]['orderid']   = intval($Rs['orderid']);
				
				$OrderList[$i]['typename'] = $Rs['typename'];
				$OrderList[$i]['content'] = $Rs['content'];
						
				if ($Rs['endtime']<time())
						$OrderList[$i]['state'] = "[已過期]";
				switch (intval($Rs['usestate'])){
					case 0:
						$OrderList[$i]['usestatename'] = "未使用" . $OrderList[$i]['state'];
						break;
					case 1:
						$u_sql = "select * from `{$INFO[DBPrefix]}bonusbuydetail` where combipoint_id=" . $Rs['id'];
						$u_Query =  $DB->query($u_sql);
						$u_Rs = $DB->fetch_array($u_Query);
						$usepoint = intval($u_Rs['usepoint']);
						
						$OrderList[$i]['usestatename'] = "部份使用(" . $usepoint . "紅利)" . $OrderList[$i]['state'];
						break;
					case 2:
						$OrderList[$i]['usestatename'] = "已使用";
						break;
				}
				$OrderList[$i]['endtime']     = date("Y-m-d",$Rs['endtime']);
				$i++;
			}
			$result_array['info'] = $OrderList;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;
	}
	/**
	紅利記錄
	**/
	function getBonusUseList($user_id){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");
		$Sql      = "select b.*,o.order_serial from `{$INFO[DBPrefix]}bonusbuydetail` as b left join `{$INFO[DBPrefix]}order_table` as o on b.orderid=o.order_id where b.user_id=".intval($_SESSION['user_id'])." order by id desc";
		$PageNav    = new PageItem($Sql,10);
		$Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$OrderList[$i]['id'] = $Rs['id'];
				$OrderList[$i]['content'] = $Rs['content'];
				$OrderList[$i]['usepoint'] = $Rs['usepoint'];
				$OrderList[$i]['usetime']     = date("Y-m-d",$Rs['usetime']);
				$OrderList[$i]['orderid']   = intval($Rs['orderid']);
				$OrderList[$i]['order_serial']   = ($Rs['order_serial']);
				$i++;
			}
			$result_array['info'] = $OrderList;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;
	}
	/**
	推薦人
	**/
	function getRecommendUser($memberno){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");
		$Sql      = "select * from `{$INFO[DBPrefix]}user` where recommendno='".$memberno."' order by user_id desc";
		$PageNav    = new PageItem($Sql,10);
		$Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$User_array[$i]['id'] = $i+1;
				$User_array[$i]['username'] = $Rs['username'];
				$User_array[$i]['true_name'] = $Rs['true_name'];
				$User_array[$i]['reg_date'] = $Rs['reg_date'];
				$p_sql = "select sum(point) as sumpoint from `{$INFO[DBPrefix]}bonuspoint` where type=4 and user_id='" . intval($_SESSION['user_id']) . "' and content='推薦會員" . $Rs['username'] . "'";
				$Query_p=$DB->query($p_sql);
				$Rs_p = $DB->fetch_array($Query_p);
				$User_array[$i]['point'] = intval($Rs_p['sumpoint']);
				$i++;
			}
			$result_array['info'] = $User_array;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;

	}
	/**
	推薦業績
	**/
	function getRecommendPoint($memberno){
		global $DB,$INFO;
		include_once Classes . "/Time.class.php";
		$TimeClass = new TimeClass;
		include_once("PageNav.class_phone.php");
		if ($_GET['begtime']!=""){
			$begtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['begtime'],"-");
			$subsql = " and ot.createtime>='" . $begtimeunix . "'";
		}
		if ($_GET['endtime']!=""){
			$endtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['endtime'],"-")+60*60*24;	
			$subsql = " and ot.createtime<='" . $endtimeunix . "'";
		}
		 $Sql      = "select u.username,u.true_name,u.reg_date,sum(bp.point)as sumpoint from `{$INFO[DBPrefix]}order_table` as ot inner join `{$INFO[DBPrefix]}bonuspoint` as bp on ot.order_id=bp.orderid inner join `{$INFO[DBPrefix]}user` as u on u.user_id=ot.user_id where ot.recommendno='".$memberno."' " . $subsql . " and ot.pay_state=1 and ot.order_state=4 and bp.type=5 group by ot.user_id order by ot.order_id desc";
		$PageNav    = new PageItem($Sql,10);
		$Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$User_array[$i]['id'] = $i+1;
				$User_array[$i]['username'] = $Rs['username'];
				$User_array[$i]['true_name'] = $Rs['true_name'];
				$User_array[$i]['reg_date'] = $Rs['reg_date'];
				$User_array[$i]['point'] = intval($Rs['sumpoint']);
				$i++;
			}
			$result_array['info'] = $User_array;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;

	}
	/**
	 追蹤商品清單
	**/
	function getCollectionProduct($user_id){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");
		$Sql = "select g.smallimg,g.goodsname,g.intro,g.price,g.pricedesc,g.gid,c.collection_id from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid)  inner join  `{$INFO[DBPrefix]}collection_goods` c on (c.gid=g.gid)  where  b.catiffb=1 and g.ifpub=1 and (g.pubstarttime='' or g.pubstarttime<='" . time() . "') and (g.pubendtime='' or g.pubendtime>='" . time() . "') and c.user_id=".intval($user_id)." order by c.cidate desc ";
		$PageNav    = new PageItem($Sql,12);
		$Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$goods_array[$i]['smallimg'] = $Rs['smallimg'];
				$goods_array[$i]['goodsname'] = $Rs['goodsname'];
				$goods_array[$i]['intro'] = $Rs['intro'];
				$goods_array[$i]['price'] = $Rs['price'];
				$goods_array[$i]['pricedesc'] = $Rs['pricedesc'];
				$goods_array[$i]['gid'] = $Rs['gid'];
				$goods_array[$i]['collection_id'] = $Rs['collection_id'];
				$i++;
			}
			$result_array['info'] = $goods_array;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;

	}
	/**
	 追蹤商品操作
	**/
	function setCollectionProduct($user_id,$Action,$id){
		global $DB,$INFO,$FUNCTIONS;

		if ($Action=='Insert'){
			$Query   = $DB->query("select * from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (  g.bid=b.bid )  where b.catiffb=1 and g.ifpub=1 and g.gid=".intval($id)."  limit 0,1");
			$Num   = $DB->num_rows($Query);

			if ( $Num==0 ){   //如果不存在资料
				$FUNCTIONS->header_location("index.php");
			}

			$Querys   = $DB->query("select * from `{$INFO[DBPrefix]}collection_goods` where gid=".$id." and user_id=".$user_id."  limit 0,1");
			$Nums   = $DB->num_rows($Querys);

			if ( $Nums==0 ) {  //如果不存在资料,就将执行插入操作
				$DB->query("insert into  `{$INFO[DBPrefix]}collection_goods` (gid,user_id) values('".intval($id)."','".$user_id."')");
			}
		}

		if ($Action=='Del'){
			$DB->query("delete from `{$INFO[DBPrefix]}collection_goods` where collection_id=".intval($id));
		}
		$FUNCTIONS->header_location("../mobile/member_recommendproduct.php");

	}

	/**
	修改密碼
	**/
	function ChangePwd(){
		global $DB,$INFO;
		if($this->checkLogin(1)==0	){
			return 0;
		}else{
			$user_array = $this->getUserInfo(intval($_SESSION['user_id']));
			$Old_pw =  md5(trim($_POST['old_pwd']));
			$New_pw =  md5(trim($_POST['f_pwd']));
			if ($user_array['password']!=$Old_pw){
				return -1;
			}else{
				$db_string = $DB->compile_db_update_string( array (
				'password'         => trim($New_pw),	
				));
				$Sql = "UPDATE `{$INFO[DBPrefix]}user` SET $db_string WHERE user_id=".intval($_SESSION['user_id']);
				$Result_Update = $DB->query($Sql);
				if ($Result_Update)
				{
					//發信
					$Array =  array("f_pwd"=>trim($_POST['f_pwd']),"truename"=>trim($user_array['true_name']),"username"=>trim($user_array['username']));
					
					include_once "SMTP.Class.inc.php";
					include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
					$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
					$SMTP->MailForsmartshop(trim($user_array['email']),"",2,$Array);
					//發短信
					include_once "sms2.inc.php";
					include_once "sendmsg.php";
					$sendmsg = new SendMsg;
					$sendmsg->send(trim($user_array['other_tel']),$Array,2);
					return 1;
				}else{
					return -2;
				}
			}
		}
	}
	/**
	得到客服列表
	**/
	function getKefuList($user_id){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");
		$Sql   = " select k.*,g.*,o.order_id from `{$INFO[DBPrefix]}kefu` as k  left join `{$INFO[DBPrefix]}goods` as g on k.marketno=g.goodsno left join `{$INFO[DBPrefix]}order_table` as o on o.order_serial=k.order_serial where k.user_id='".$user_id."' order by k.lastdate desc ";
		$PageNav    = new PageItem($Sql,10);
		$Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$type_chuli_name = $Rs[type_chuli_name];
				$type_chuli_name_array = explode("-",$type_chuli_name);
				$kefu[$i][title]      = $Rs[title];
				$kefu[$i][k_kefu_con] = $Rs[k_kefu_con];
				$kefu[$i][kid]        = $Rs[kid];
				$kefu[$i][type_chuli_name]        = $type_chuli_name_array[0];
				$kefu[$i]['gid']        = $Rs['gid'];
				$kefu[$i]['goodsname']        = $Rs['goodsname'];
				$kefu[$i]['marketno']        = $Rs['marketno'];
				$kefu[$i]['order_serial']        = $Rs['order_serial'];
				$kefu[$i]['order_id']        = $Rs['order_id'];
				$Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_posts` where kid = '" . $Rs[kid] . "' and (ifcheck=1 or provider_id=0) and username='客服回覆' order by postdate desc limit 0,1";
				$Query_linshi = $DB->query($Sql_linshi);
				$kefu_posts = array();
				$Rs_linshi = $DB->fetch_array($Query_linshi);
					$kefu[$i][k_post_con] = $Rs_linshi['k_post_con'];
				$i++;
			}
			$result_array['info'] = $kefu;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;
	}
	/**
	得到客服類別
	**/
	function getKefuType(){
		global $DB,$INFO;
		$s_Sql = "select * from `{$INFO[DBPrefix]}kefu_type` where typegroup<>0 and k_type_name<>'' and status=1 order by typegroup";
		$Query_s    = $DB->query($s_Sql);
		$i = 0;
		while ($Rs_s=$DB->fetch_array($Query_s)) {
			$kefu_type[$i] = $Rs_s;
			$i++;
		}
		return $kefu_type;
	}
	
	/**
	客服留言
	**/
	function setKefu(){
		global $DB,$INFO,$FUNCTIONS;
		include_once "../language/".$INFO['IS']."/KeFu_Pack.php";
		$Sql_linshi           = "select k_type_name,k_type_content from `{$INFO[DBPrefix]}kefu_type` where k_type_id = '".$_POST[kefu_type]."' limit 0,1 ";
		$Query_linshi         = $DB->query($Sql_linshi);
		$type_name_linshi_num = $DB->num_rows($Query_linshi);
	
		if ($type_name_linshi_num>0){
			$type_name_linshi = $DB->fetch_array($Query_linshi);
			$type_name_linshi_content = $type_name_linshi['k_type_content'];
		}
	
		$Sql_linshi = "select k_chuli_id , k_chuli_name from `{$INFO[DBPrefix]}kefu_chuli` order by checked Desc;";
		$Query_linshi = $DB->query($Sql_linshi);
		$chuli_name_linshi = $DB->fetch_array($Query_linshi);
	
		$type_chuli = $_POST['kefu_type'].'-'.$chuli_name_linshi['k_chuli_id'];
		$type_chuli_name = $type_name_linshi['k_type_name'].'-'.$chuli_name_linshi['k_chuli_name'];
		$timeforserialnum = time();
		$serialnum = substr(md5($timeforserialnum),0,14);
		if ($_POST['order_serial']!=""){
			$order_Sql = "select * from `{$INFO[DBPrefix]}order_table` where order_serial='" . $_POST['order_serial'] . "'";
			$Query_order         = $DB->query($order_Sql);
			$order_num = $DB->num_rows($Query_order);
			if ($order_num<=0){
				$Result_say = "訂單並不存在！";	
			}else{
				$Rs_order = $DB->fetch_array($Query_order);
				$provider_id = $Rs_order['provider_id'];	
			}
		}
		if ($_POST['marketno']!=""){
			$order_Sql = "select * from `{$INFO[DBPrefix]}goods` where goodsno='" . $_POST['marketno'] . "'";
			$Query_order         = $DB->query($order_Sql);
			$order_num = $DB->num_rows($Query_order);
			if ($order_num<=0){
				$Result_say = "賣場並不存在！";	
			}else{
				$Rs_order = $DB->fetch_array($Query_order);
				$provider_id = $Rs_order['provider_id'];	
			}
		}
		if ($Result_say!=""){
			$FUNCTIONS->sorry_back("back",$Result_say);
		}
		$Query = $DB->query("select email from `{$INFO[DBPrefix]}user` where user_id=".intval($_SESSION['user_id'])." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result = $DB->fetch_array($Query);
			$email = $Result[email];//用户email
		}
		$db_string = $DB->compile_db_insert_string( array (
		'serialnum'                 => trim($serialnum),
		'type_chuli'                => trim($type_chuli),
		'type_chuli_name'           => trim($type_chuli_name),
		'user_id'                   => trim($_SESSION['user_id']),
		'username'                  => $_SESSION['username'],
		'realname'                  => $_SESSION['true_name'],
		'email'                     => $email,
		'title'                     => trim($_POST['title']),
		'lastdate'                  => time(),
		'k_kefu_con'                => $_POST['k_kefu_con'],
		'order_serial'                => $_POST['order_serial'],
		'marketno'                => $_POST['marketno'],
		'provider_id'                => $provider_id,
		)      );
	
	
		$Sql="INSERT INTO `{$INFO[DBPrefix]}kefu` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	
		$Result_Insert=$DB->query($Sql);
		$kefu_id = mysql_insert_id();
		if ($Result_Insert){
			$FUNCTIONS->setKefuLog($kefu_id,"客戶詢問",0);
			$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=2");
			$Rs = $DB->fetch_array($Query);
			$sysmail = $Rs['mail'];
			$Emailcontent = "留言主旨:" . trim($_POST['title']) . "<br>留言類別:" . $type_name_linshi['k_type_name'] . "<br>問題內容:" . trim($_POST['k_kefu_con']);
			$Array  = array("mailsubject"=>"客服留言：".trim($_POST['title']),"mailbody"=>$Emailcontent);
			include_once "SMTP.Class.inc.php";
			include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
			$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
			$SMTP->MailForsmartshop($sysmail,"",'GroupSend',$Array);
			return 1;
		}else {
			return 0;
		}
	}
	/**
	忘記密碼
	**/
	function forgetPwd($email){
		global $DB,$INFO,$FUNCTIONS;
		$ifexist = $this->checkExist("email",$email);
		if($ifexist==1){
			$userinfo = $this->getUserInfo($email,2);
			$One   = rand(1,10);
			$Two   = rand(11,80);
			$Three = rand(10,40);
			$Four  = rand(5,9);
			$Five  = rand(6,16);
			$Six   = rand(100,1000);
			$NewPassword = $One.$Two.$Three.$Four.$Five.$Six;
			$DB->query("update `{$INFO[DBPrefix]}user` set password='".md5($NewPassword)."' where user_id=".intval($userinfo['user_id']));
			$Array = array("username"=>trim($email),"truename"=>trim($userinfo['true_name']),"f_pwd"=>$NewPassword);
			include "../Classes/SMTP.Class.inc.php";
			include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
			$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
			$SMTP->MailForsmartshop(trim($email),"",3,$Array);
			include "../Classes/sms2.inc.php";
			include "../Classes/sendmsg.php";
					
			$sendmsg = new SendMsg;
			$sendmsg->send(trim($userinfo['other_tel']),$Array,3);
		}else{
			return 0;	
		}
	}
	/**
	得到會員折價券列表
	**/
	function getTicket($user_id){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");
		 $Sql = "select sum(ut.count) as count,ut.ticketid,t.money,t.ticketname,t.use_starttime,t.use_endtime,t.moneytype from `{$INFO[DBPrefix]}userticket` as ut inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where  ut.userid=".intval($user_id)." group by ut.ticketid";
		$PageNav    = new PageItem($Sql,10);
		 $Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$ticket_array[$i] = $Rs;
				$i++;
			}
			$result_array['info'] = $ticket_array;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;
	}
	/**
	得到會員使用的通用折價券記錄
	**/
	function getGeneralTicket($user_id){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");	
		$Sql      = "select ot.ticketmoney,ut.*,ut.ordercode,ot.order_id,t.moneytype,t.money from `{$INFO[DBPrefix]}use_ticket` as ut inner join `{$INFO[DBPrefix]}order_table` as ot on ot.order_serial=ut.ordercode inner join `{$INFO[DBPrefix]}ticketcode` as tc on tc.ticketcode=ut.ticketcode inner join `{$INFO[DBPrefix]}ticket` as t on ut.ticketid=t.ticketid where ut.userid=".intval($user_id)." order by ut.useid desc";
		$PageNav    = new PageItem($Sql,10);
		 $Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$ticket_array[$i] = $Rs;
				$ticket_array[$i]['usetime']     = date("Y-m-d",$Rs['usetime']);
				$i++;
			}
			$result_array['info'] = $ticket_array;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;
	}
	/**
	折價券試用情況
	**/
	function getTicketUseInfo($user_id,$ticketid){
		global $DB,$INFO;
		include_once("PageNav.class_phone.php");	
		$Sql      = "select ot.ticketmoney,ut.*,ut.ordercode,ot.order_id,t.money from `{$INFO[DBPrefix]}use_ticket` as ut inner join `{$INFO[DBPrefix]}order_table` as ot on ot.order_serial=ut.ordercode inner join `{$INFO[DBPrefix]}ticket` as t on t.ticketid=ot.ticketid where ut.ticketid='" . intval($ticketid) . "' and ut.userid=".intval($user_id)." order by ut.useid desc";
		$PageNav    = new PageItem($Sql,10);
		 $Num        = $PageNav->iTotal;
		if ($Num>0){
			$Query = $PageNav->ReadList();
			$i=0;
			while ($Rs = $DB->fetch_array($Query)) {
				$ticket_array[$i] = $Rs;
				$ticket_array[$i]['usetime']     = date("Y-m-d",$Rs['usetime']);
				$i++;
			}
			$result_array['info'] = $ticket_array;
			$result_array['page'] = $PageNav->myPageItem();
		}
		return $result_array;
	}
}
?>