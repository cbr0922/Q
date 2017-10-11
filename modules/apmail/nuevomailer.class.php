<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
class NuevoMailer{
	function setSubscribers($act,$info_array){
		
		global $DB,$INFO;
		if($info_array['email']!=""){
			$subscribers['email'] = 	$info_array['email'];
		}
		$subscribers['lastName'] = 	$info_array['realname'];
		$subscribers['address'] = 	$info_array['address'];
		$subscribers['city'] = 	$info_array['city'];
		$subscribers['country'] = 	$info_array['Country'];
		$subscribers['subPhone1'] = 	$info_array['phone'];
		$subscribers['subMobile'] = 	$info_array['mobile'];
		
		//switch($act){
		//	case "insert":	
				$result = $this->existSubscribers($info_array['email']);
				
				if($result>0 && $act=="insert"){
					$info_array['user_id'] = $result;
					$this->setSubscribers("update",$info_array);
					return 1;
				}
				$subscribers['dateSubscribed'] = 	date("Y-m-d");
				$subscribers['idGroup'] = 	1;
				//$subscribers['idEmail'] = 	$info_array['user_id'];
				if($act=="insert"){
					$db_string = $DB->compile_db_insert_string($subscribers);
					$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "subscribers` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
					$Result_Update = $DB->query($Sql);
					$info_array['user_id'] = $DB->insert_id();
				}else{
					$db_string = $DB->compile_db_update_string($subscribers);	
					$Sql = "UPDATE `" . $INFO['nuevo.DBPrefix'] . "subscribers` SET $db_string WHERE idEmail=".$info_array['user_id'];
					$Result_Update = $DB->query($Sql);
				}
				/**
				訂閱電子報
				**/
				//if($info_array['dianzibao']==1)
				//	$this->setLists($info_array['user_id'],1);
		//		break;
		//	case "update":
		//		$db_string = $DB->compile_db_update_string($subscribers);
		//		$Sql = "UPDATE `" . $INFO['nuevo.DBPrefix'] . "subscribers` SET $db_string WHERE idEmail=".$info_array['user_id'];
		//		$Result_Update = $DB->query($Sql);
				
		//		break;
		//}
		//if($act == "update" || $act == "Change"){
			/**
			訂閱電子報
			**/
			$ifhave = $this->existLists($info_array['user_id'],1);
			if($ifhave==1){
				if($info_array['dianzibao']==0){
					$this->deleLists($info_array['user_id'],1);
				}
			}else{
				if($info_array['dianzibao']==1 && $this->existLists($info_array['user_id'],1)){
					$this->setLists($info_array['user_id'],1);
				}	
			}
		//}
		return true;
	}
	/**
	會員是否存在
	**/
	function existSubscribers($email){
		global $DB,$INFO;
		 $Sql = "select idEmail from `" . $INFO['nuevo.DBPrefix'] . "subscribers`  where email='" . $email . "' limit 0,1";
		$Query  = $DB->query($Sql);
		 $Num    = $DB->num_rows($Query);
		if ($Num>0){
			$Rs=$DB->fetch_array($Query);
			return $Rs['idEmail'];
		}else{
			return false;
		}
	}
	/**
	刪除會員
	**/
	function deleSubscribers($idEmail){
		global $DB,$INFO;
		if(is_array($idEmail)){
			foreach($idEmail as $k=>$v){
				$Sql = "delete from `" . $INFO['nuevo.DBPrefix'] . "subscribers` where idEmail='" . $v . "'";
				$Query  = $DB->query($Sql);	
				$this->deleLists($v);
			}
		}else{
			$Sql = "delete from `" . $INFO['nuevo.DBPrefix'] . "subscribers` where idEmail='" . $idEmail . "'";
			$Query  = $DB->query($Sql);
			$this->deleLists($idEmail);
		}
		return 1;
	}
	/**
	會員分訂閱組
	**/
	function setLists($idEmail,$idList){
		global $DB,$INFO;
		//echo $this->existLists($idEmail,$idList);exit;
		if($this->existLists($idEmail,$idList)<=0){
			$Sql = "select idEmail from `" . $INFO['nuevo.DBPrefix'] . "subscribers`  where idEmail='" . $idEmail . "' and soft_bounces=0 and hard_bounces=0 limit 0,1";
			$Query  = $DB->query($Sql);
			$Num    = $DB->num_rows($Query);
			if ($Num>0){
				$db_string = $DB->compile_db_insert_string(
				   array(
					'idGroup' =>1,
					'idEmail' =>$idEmail,
					'idList'  =>$idList
					)
				);
				$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "listRecipients` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
				$Result_Update = $DB->query($Sql);
			}
		}
	}
	/**
	會員是否存在某訂閱組
	**/
	function existLists($idEmail,$idList){
		global $DB,$INFO;
		 $ListSql = "select * from `" . $INFO['nuevo.DBPrefix'] . "listRecipients` where idList=" . $idList . " and idEmail='" . $idEmail . "'";
		$ListQuery = $DB->query($ListSql);
		$ListNum   = $DB->num_rows($ListQuery);
		return $ListNum;
	}
	/**
	會員取消某訂閱組
	**/
	function deleLists($idEmail,$idList=""){
		global $DB,$INFO;
		if($idList>0)
			$subSql = " and idList=" . $idList . " ";
		$DelSql = "delete from `" . $INFO['nuevo.DBPrefix'] . "listRecipients` where idEmail='" . $idEmail . "'" . $subSql;	
		$DB->query($DelSql);
		return 1;
	}
	
	/**
	非會員訂閱電子報
	**/
	function dingYue($email){
		global $DB,$INFO;
		$result = $this->existSubscribers($email);
		if($result>0){
			$ifhave = $this->existLists($result,1);
			if($ifhave==true)
				$this->deleLists($result,1);
			else
				$this->setLists($result,1);
		}else{
			$db_string = $DB->compile_db_insert_string(
				array(
					  'email'=>$email,
					  'lastName'=>"非會員訂閱者",
					  'idGroup'=>1
				)
			);
			 $Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "subscribers` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result_Update = $DB->query($Sql);
			$Insert_id = $DB->insert_id();
			$this->setLists($Insert_id,1);
		}
		return 1;
	}
	
	/**
	新增新聞郵件
	**/
	function addNewsletters($title,$body,$gid=0,$ltype=""){
		global $DB,$INFO;
		$result = $this->getNewsletters($gid,$ltype);
		if($result==false){
			$mail_array = array(
					  'name'=>$title,
					  'body'=>$body,
					  'idGroup'=>1,
					  'dateCreated' =>date("Y-m-d"),
					  'charset'=>'utf-8',
					  'html'=>-1
				);
			if($ltype=="goods")
				$mail_array['gid'] = $gid;
			if($ltype=="ticket")
				$mail_array['ticketid'] = $gid;
			if($ltype=="bonus")
				$mail_array['bonuspoint'] = $gid;
			if($ltype=="edm")
				$mail_array['pubid'] = $gid;
			$db_string = $DB->compile_db_insert_string($mail_array);
			$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "newsletters` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result_Update = $DB->query($Sql);
			$Insert_id = $DB->insert_id();
			return $Insert_id;
		}else{
			if($ltype=="")
				$field = "idNewsletter";
			elseif($ltype=="goods")
				$field = "gid";
			elseif($ltype=="ticket")
				$field = "ticketid";
			elseif($ltype=="bonus")
				$field = "bonuspoint";
			elseif($ltype=="edm")
				$field = "pubid";
			$mail_array = array(
					  'name'=>$title,
					  'body'=>$body,
					  'idGroup'=>1,
					  'dateCreated' =>date("Y-m-d"),
					  'charset'=>'utf-8',
					  'html'=>-1
				);
			$db_string = $DB->compile_db_update_string($mail_array);
			$Sql = "UPDATE `" . $INFO['nuevo.DBPrefix'] . "newsletters` SET $db_string WHERE " . $field . "=".intval($gid);
			$Result_Update = $DB->query($Sql);
			return $result['id'];	
		}
	}
	
	/**
	新增專案
	**/
	function addCampaigns($name,$idLetter,$idList){
		global $DB,$INFO;		
		$listName = $this->getLists($idList);
		$htmlNewsletterName = $this->getLists($idLetter);
		$db_string = $DB->compile_db_insert_string(
			array(
				  'campaignName'=>$name,
				  'joins'=>'y',
				  'idList'=>$idList,
				  'listName'=>'列表 ' . $idList . ': ' . $listName,
				  'mLists'=>$idList,
				  'idHtmlNewsletter'=>$idLetter,
				  'htmlNewsletterName'=>$htmlNewsletterName['name'],
				  'dateCreated'=>date("Y-m-d"),
				  'idGroup'=>1,
				  'type'=>1,
				  'confirmed'=>1,
				  'prefers'=>3,
			)
		);
		$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "campaigns` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Update = $DB->query($Sql);
		$Insert_id = $DB->insert_id();
		return $Insert_id;
	}
	/**
	得到訂閱列表信息
	**/
	function getLists($idList){
		global $DB,$INFO;
		$Sql = "select * from `" . $INFO['nuevo.DBPrefix'] . "lists` where idList='" . intval($idList) . "'";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num>0){
			$Rs=$DB->fetch_array($Query);
			return $Rs['listName'];
		}else{
			return false;
		}
	}
	/**
	得到新聞信息
	**/
	function getNewsletters($idNewsletter,$type=""){
		global $DB,$INFO;
		if($type=="")
			$field = "idNewsletter";
		elseif($type=="goods")
			$field = "gid";
		elseif($type=="ticket")
			$field = "ticketid";
		elseif($type=="bonus")
			$field = "bonuspoint";
		elseif($type=="edm")
			$field = "pubid";
		 $Sql = "select * from `" . $INFO['nuevo.DBPrefix'] . "newsletters` where " . $field . "='" . intval($idNewsletter) . "'";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num>0){
			$Rs=$DB->fetch_array($Query);
			return array('name'=>$Rs['name'],'id'=>$Rs['idNewsletter']);
		}else{
			return false;
		}
	}
	
	/**
	新增訂閱列表
	**/
	function addLists($name){
		global $DB,$INFO;
		$db_string = $DB->compile_db_insert_string(
			array(
				  'listName'=>$name,
				  'listDescription'=>'自動生成訂閱列表',
				  'idGroup'=>1,
				  'dateCreated' =>date("Y-m-d"),
				  'createdBy'=>1
			)
		);
		$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "lists` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Update = $DB->query($Sql);
		$Insert_id = $DB->insert_id();
		return $Insert_id;
	}
	/**
	設定貨到通知
	**/
	function setWaitbuy($gid,$user_array){
		global $DB,$INFO;
		$Query = $DB->query(" select goodsname from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)." limit 0,1"
);
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$goodsname        =  $Result['goodsname'];
		}
		$mailbody = $this->getMailbody(20,array('goodsname'=>$goodsname));
		$idList = $this->addLists("貨到通知_" . $goodsname . "_" . date('YmdHis'));
		$idLetter = $this->addNewsletters($mailbody['title'],$mailbody['body'],intval($gid),"goods");
		$idCampaign = $this->addCampaigns("貨到通知_" . $goodsname . "_" . date('YmdHis'),$idLetter,$idList);
		foreach($user_array as $k=>$v){
			$Query = $DB->query(" select u.* from `{$INFO[DBPrefix]}waitbuy` as w inner join `{$INFO[DBPrefix]}user` as u on w.user_id=u.user_id where w.id='" . $v . "' order by w.id ");
			$Rs = $DB->fetch_array($Query);
			$result = $this->existSubscribers($Rs['email']);
			if($result == false){
				$subscribers['email'] = $Rs['email'];
				$subscribers['lastName'] = $Rs['true_name'];
				//$subscribers['subMobile'] = $Rs['other_tel'];
				//$subscribers['subPhone1'] = $Rs['tel'];
				//$subscribers['idEmail'] = $Rs['user_id'];
				$db_string = $DB->compile_db_insert_string($subscribers);
				$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "subscribers` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
				$Result_Update = $DB->query($Sql);
				$result = $Rs['user_id'];
			}
			
			$this->setLists($result,$idList);
			$DB->query(" delete from `{$INFO[DBPrefix]}waitbuy` where user_id='" . $result . "' ");
		}
		return $idCampaign;
		
	}
	/**
	得到郵件內容
	**/
	function getMailbody($mailid,$replace_array){
		global $DB,$INFO;
		$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}sendtype` where sendtype_id=".intval($mailid)." and sendstatus=1 limit 0,1");
		$Num    = $DB->num_rows($Query);
		if ( $Num>0 ) {
			$Rs= $DB->fetch_array($Query);
			$title = str_replace ("@shopname@",$INFO['site_name'],$Rs['sendtitle']);
			$mailBody = $Rs['sendcontent'];
			$mailtext = str_replace ("@shopname@",trim($INFO['site_name']),$mailBody);
			$mailtext = str_replace ("@site_url@",trim($INFO['site_url']),$mailtext);
			foreach($replace_array as $k=>$v){
				$title = str_replace ("@" . $k . "@",$v,$title);
				$mailtext = str_replace ("@" . $k . "@",$v,$mailtext);
			}
			return array('title'=>$title,'body'=>$mailtext);
		}else{
			return false;	
		}
			
	}
	/**
	管理員登陸
	**/
	function checkLogin($padminName,$padminPassword){
		global $DB,$INFO;
		$Query  = $DB->query(" select idAdmin, adminName, idGroup, adminPassword from `" . $INFO['nuevo.DBPrefix'] . "admins` WHERE active=-1 and idAdmin=1 limit 0,1");
		$Num    = $DB->num_rows($Query);
		if ( $Num>0 && !($_SESSION['LOGINADMIN_session_id'] == '' || empty($_SESSION['LOGINADMIN_session_id']))) {
			$Rs= $DB->fetch_array($Query);
			if (!isset($_SESSION['idAdmin']))
			{
				$_SESSION['idAdmin'] = $Rs['idAdmin'];
			}
			if (!isset($_SESSION['adminName']))
			{
				$_SESSION['adminName'] = $Rs['adminName'];
			}
			if (!isset($_SESSION['idGroup']))
			{
				$_SESSION['idGroupL'] = $Rs['idGroup'];
			}
			 $_SESSION['adminLang']="chinese";  
			$mySQL2="UPDATE " . $INFO['nuevo.DBPrefix'] . "admins SET adminLastLogin='".date("Y-m-d H:i:s")."' WHERE idAdmin=".$_SESSION['idAdmin'];
			$DB->query($mySQL2);
		}
	}
	/**
	修改管理員密碼
	**/
	function modiAdmin($padminPassword){
		global $DB,$INFO;
		$mySQL2="UPDATE " . $INFO['nuevo.DBPrefix'] . "admins SET adminPassword='".$padminPassword."' WHERE idAdmin=1";
		$DB->query($mySQL2);
	}
	
	/**
	設定折價券通知
	**/
	function setTicket($ticketid,$user_array){
		global $DB,$INFO;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}ticket` where ticketid=".intval($ticketid)." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$ticketname        =  $Result['ticketname'];
		}
		$mailbody = $this->getMailbody(12,array('ticketname'=>$ticketname));
		$idList = $this->addLists("折價券通知_" . $ticketname . "_" . date('YmdHis'));
		$idLetter = $this->addNewsletters($mailbody['title'],$mailbody['body'],intval($ticketid),"ticket");
		$idCampaign = $this->addCampaigns("折價券通知_" . $ticketname . "_" . date('YmdHis'),$idLetter,$idList);
		foreach($user_array as $k=>$v){
			$Query = $DB->query(" select u.* from `{$INFO[DBPrefix]}user` as u where u.user_id='" . $v . "' ");
			$Rs = $DB->fetch_array($Query);
			$result = $this->existSubscribers($Rs['email']);
			if($result == false){
				$subscribers['email'] = $Rs['email'];
				$subscribers['lastName'] = $Rs['true_name'];
				//$subscribers['subMobile'] = $Rs['other_tel'];
				//$subscribers['subPhone1'] = $Rs['tel'];
				//$subscribers['idEmail'] = $Rs['user_id'];
				$db_string = $DB->compile_db_insert_string($subscribers);
				$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "subscribers` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
				$Result_Update = $DB->query($Sql);
				$result = $Rs['user_id'];
			}
			
			$this->setLists($result,$idList);
		}
		return $idCampaign;
		
	}
	/**
	設定紅利通知
	**/
	function setBonus($bonuspoint,$user_array){
		global $DB,$INFO;
		$mailbody = $this->getMailbody(28,array('bonuspoint'=>$bonuspoint));
		$idList = $this->addLists("紅利發送通知_" . date('YmdHis'));
		 $idLetter = $this->addNewsletters($mailbody['title'],$mailbody['body'],$bonuspoint,"bonus");
		$idCampaign = $this->addCampaigns("紅利發送通知_" . date('YmdHis'),$idLetter,$idList);
		foreach($user_array as $k=>$v){
			$Query = $DB->query(" select u.* from `{$INFO[DBPrefix]}user` as u where u.user_id='" . $v . "'  ");
			$Rs = $DB->fetch_array($Query);
			$result = $this->existSubscribers($Rs['email']);
			if($result == false){
				$subscribers['email'] = $Rs['email'];
				$subscribers['lastName'] = $Rs['true_name'];
				//$subscribers['subMobile'] = $Rs['other_tel'];
				//$subscribers['subPhone1'] = $Rs['tel'];
				//$subscribers['idEmail'] = $Rs['user_id'];
				$db_string = $DB->compile_db_insert_string($subscribers);
				$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "subscribers` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
				$Result_Update = $DB->query($Sql);
				$result = $Rs['user_id'];
			}
			
			$this->setLists($result,$idList);
		}
		return $idCampaign;
		
	}
	/**
	設定EDM發送
	**/
	function setEdm($Pub_id,$group_array){
		global $DB,$INFO;
		$Query = $DB->query(" select publication_title,publication_start_time,publication_end_time,publication_content  from `{$INFO[DBPrefix]}publication` where publication_id=".intval($Pub_id)." limit 0,1"
);
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$mailbody      =  $Result['publication_content'];
			$title        =  $Result['publication_title'];
		}else{
			return false;	
		}
		$idList = $this->addLists("發送EDM_" . $title . "_" . date('YmdHis'));
		$idLetter = $this->addNewsletters($title,$mailbody,$Pub_id,"edm");
		$idCampaign = $this->addCampaigns("發送EDM_" . $title . date('YmdHis'),$idLetter,$idList);
		if(is_array($group_array)){
			foreach($group_array as $k=>$v){
				//$Sql      = "select u.* from `{$INFO[DBPrefix]}mail_group_list` as m left join `{$INFO[DBPrefix]}user` as u on u.user_id=m.user_id where group_id='" . intval($v) . "' order by m.email asc ";
				$Sql      = "select * from `{$INFO[DBPrefix]}mail_group`where mgroup_id='" . intval($v) . "' order by mgroup_id asc ";
				$Query    = $DB->query($Sql);
				$Rs=$DB->fetch_array($Query);
				if($Rs['searchlist']=="All"){
					 $Sql = "select * from `{$INFO[DBPrefix]}user` ";
				}elseif($Rs['searchlist']=="noDing"){
					$Sql = "select * from `{$INFO[DBPrefix]}user` where dianzibao=0";
				}else{
					$Sql      = "select m.* from `{$INFO[DBPrefix]}mail_group_list` as m left join `{$INFO[DBPrefix]}user` as u on u.user_id=m.user_id where group_id='" . intval($v) . "' order by m.email asc ";
				}
				$Query = $DB->query($Sql);
				 $Num   = $DB->num_rows($Query);
				
				while($Rs_s = $DB->fetch_array($Query)){
				//print_r($Rs);
					 $result = $this->existSubscribers($Rs_s['email']);
					
					if($result == false){
						$subscribers['email'] = $Rs_s['email'];
						$subscribers['lastName'] = $Rs_s['true_name'];
						//$subscribers['subMobile'] = $Rs_s['other_tel'];
						//$subscribers['subPhone1'] = $Rs_s['tel'];
						//$subscribers['idEmail'] = $Rs['user_id'];
						$db_string = $DB->compile_db_insert_string($subscribers);
						$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "subscribers` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
						$Result_Update = $DB->query($Sql);
						$result = $this->existSubscribers($Rs_s['email']);
						$result = $Rs['user_id'];
					}
					
					$this->setLists($result,$idList);
				}
			}
		}
		return $idCampaign;
		
	}
	/**
	新增任務調度
	**/
	function setTasks($idCampaign){
		global $DB,$INFO;
		$TimeOffset = 0;
		$Sql = "select groupTimeOffsetFromServer from `" . $INFO['nuevo.DBPrefix'] . "groupSettings` limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num>0){
			$Rs=$DB->fetch_array($Query);
			$TimeOffset = $Rs['groupTimeOffsetFromServer'];
		}
		//$activeDate = date("Y-m-d") . " " . $INFO['automail_hour'] . ":" . $INFO['automail_minute'] . ":00";
		$d = date('d',time());
		$y = intval(date('Y',time()));
		$m = intval(date('m',time()));
		$activeDate = date("Y-m-d H:i:s",mktime($INFO['automail_hour']-$TimeOffset,$INFO['automail_minute'],0,$m,$d,$y));
		$curDate = date("Y-m-d H:i:s",mktime(date("H")-$TimeOffset,date("i"),0,$m,$d,$y));
		if($curDate>$activeDate){
			$d = date('d',time())+1;
			$y = intval(date('Y',time()));
			$m = intval(date('m',time()));
			$overtime = mktime($INFO['automail_hour']-$TimeOffset,$INFO['automail_minute'],0,$m,$d,$y);
			$activeDate = date("Y-m-d H:i:s",$overtime);
		}
		$db_string = $DB->compile_db_insert_string(
			array(
				  'idCampaign'=>$idCampaign,
				  'idAdmin'=>1,
				  'idGroup'=>1,
				  'dateTaskCreated' =>$curDate,
				  'activationDateTime'=>$activeDate,
				  'numberOfMessagesToSend'=>100,
				  'repeatEveryXseconds'=>600,
				  'taskCompleted'=>0,
				  'repeatDetailsMemo'=>"10 / 分",
				  'taskRecurring'=>0,
				  'reactivateAfterXSeconds'=>0,
				  'pLog'=>"",
				  'reactivateDetailsMemo'=>"",
				  'timesExecuted'=>0,
				  'taskCounter'=>0
			)
		);
		$Sql="INSERT INTO `" . $INFO['nuevo.DBPrefix'] . "tasks` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
		$Result_Update = $DB->query($Sql);
		$Insert_id = $DB->insert_id();
		return $Insert_id;
	}
	/**
	執行任務
	**/
	function queryMail($idCampaign,$url){
		global $DB,$INFO,$FUNCTIONS;
		//echo $idCampaign;exit;
		if(intval($INFO['ifautomail'])==1){
			$this->setTasks($idCampaign);
			if($url=="back")
				echo "<script>setTimeout('history.back(-1)',500);</script>";
			else
				$FUNCTIONS->header_location($url);
		}else{
			echo "
				<form method='post' action='../automail/admin/campaignSend_jjh.php?idCampaign=" . $idCampaign . "' target='_blank' id='sendform'></form>
				<script>document.getElementById('sendform').submit();setTimeout('location.href=\"" . $url . "\"',500);</script>";
				
		}
	}
	
}
?>