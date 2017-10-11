<?php
Class SendMsg{
	function getMsg($Array,$MailSendType){
		global $DB,$INFO,$mail_type;
		if ($MailSendType=="GroupSend" && is_array($Array)){
			$title       = $Array[mailsubject];
			$mailtext    = $Array[mailbody];
		}
		if (is_integer($MailSendType)) {
			$Query  = $DB->query(" select en_firstname,en_secondname from `{$INFO[DBPrefix]}user` where username='".trim($Array['username'])."' limit 0,1");
			$Num    = $DB->num_rows($Query);
			if ( $Num>0 ) {
				$Rs= $DB->fetch_array($Query);
				$en_truename = $Rs['en_firstname'] . " " . $Rs['en_secondname'];
			}

			$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}sendmsg` where sendtype_id=".intval($MailSendType)." and sendstatus=1 limit 0,1");
			$Num    = $DB->num_rows($Query);

			if ( $Num>0 ) {
				$Rs= $DB->fetch_array($Query);
				$title = str_replace ("@shopname@",$INFO['site_name'],$Rs['sendtitle']);
				/**
				 * 首先把内容都放到$mailBody中，然后逐步替换$mailBody中的相关内容，并最终赋予$mailtext
				 */
				$mailBody = $Rs['sendcontent'];

				/**
                  * 获得相关变量 ，这里初始化为空
                  */
				$mailtext = " ";
				/**
                 * 获得基本信息相关变量
                 */
				$mailtext = str_replace ("@shopname@",trim($INFO['site_name']),$mailBody);
				$mailtext = str_replace ("@site_url@",trim($INFO['site_url']),$mailtext);
				if (trim($Array['truename'])!=""){
					$mailtext = str_replace ("@truename@",trim($Array['truename']),$mailtext);
				}else if (trim($Array['receiver_name'])!=""){
					$mailtext = str_replace ("@truename@",trim($Array['receiver_name']),$mailtext);
				}else{
					$mailtext = str_replace ("@truename@",trim($en_truename),$mailtext);
				}
				$mailtext = str_replace ("@username@",trim($Array['username']),$mailtext);
				$mailtext = str_replace ("@passwd@",trim($Array['password']),$mailtext);
				$mailtext = str_replace ("@newpass@",trim($Array['f_pwd']),$mailtext);
				/**
                 * 获得用户交互信息相关变量
                 */
				$mailtext = str_replace ("@question@",trim($Array['question']),$mailtext);
				$mailtext = str_replace ("@reply@",trim($Array['reply']),$mailtext);
				$mailtext = str_replace ("@reply_title@",trim($Array['reply_title']),$mailtext);
				$mailtext = str_replace ("@reply_result@",trim($Array['reply_result']),$mailtext);
				/**
                 * 获得定单信息相关变量
                 */
				$mailtext = str_replace ("@receiver_name@",trim($Array['receiver_name']),$mailtext);
				$mailtext = str_replace ("@ATM@",trim($Array['ATM']),$mailtext);
				$mailtext = str_replace ("@pay_name@",trim($Array['pay_name']),$mailtext);
				$mailtext = str_replace ("@pay_content@",trim($Array['pay_content']),$mailtext);
				$mailtext = str_replace ("@pay_deliver@",trim($Array['pay_deliver']),$mailtext);
				$mailtext = str_replace ("@orderid@",trim($Array['orderid']),$mailtext);
				$mailtext = str_replace ("@orderinfo@",trim($Array['orderinfo']),$mailtext);
				$mailtext = str_replace ("@orderamount@",trim($Array['orderamount']),$mailtext);
				$mailtext = str_replace ("@receiver_address@",trim($Array['receiver_address']),$mailtext);

				/**
                 * 获得产品信息相关变量
                 */
				$mailtext = str_replace ("@goodsname@",trim($Array['goodsname']),$mailtext);
				$mailtext = str_replace ("@goodslink@",trim($Array['goodslink']),$mailtext);
				$mailtext = str_replace ("@authnum@",trim($Array['authnum']),$mailtext);
			}
		}
		return $mailtext;
	}

	function send($mobile,$Array,$sendtype){
		global $DB,$INFO,$mail_type;
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}msgconfig` limit 0,1");
		$Num   = $DB->num_rows($Query);

		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$ip           =  $Result['ip'];
			$port           =  $Result['port'];
			$user           =  $Result['user'];
			$password           =  $Result['password'];
		}

		if ($ip!="" || $user!=""){
			$server_ip = $ip;
			$server_port = $port;
			$TimeOut=10;

			$user_acc  = $user;
			$user_pwd  = $password;
			$mobile_number= $mobile;
			@$message= $this->getMsg($Array,$sendtype);
			if($message!=""){
				/*建立連線*/
				$mysms = new sms2();
				@$ret_code = $mysms->create_conn($server_ip, $server_port, $TimeOut, $user_acc, $user_pwd);
				@$ret_msg = $mysms->get_ret_msg();
				if($ret_code==0){
					//echo "連線OK<br>\n";
					$ret_code = $mysms->send_text($mobile_number, iconv("UTF-8","big5",$message));
					$ret_msg = $mysms->get_ret_msg();

					 if($ret_code==0){
						// echo "簡訊傳送OK<br>";
						// echo "ret_code=".$ret_code."<br>\n";
						// echo "ret_msg=".$ret_msg."<br>\n";
					  }else{
						// echo "簡訊傳送失敗"."<br>\n";
						// echo "ret_code=".$ret_code."<br>\n";
						// echo "ret_msg=".$ret_msg."<br>\n";//exit;
						/*關閉連線*/
						$mysms->close_conn();
						return $mobile_number;
					  }


				}else {
					$mysms->close_conn();
					return $mobile_number;
					 // echo "連線失敗"."<br>\n";
					 // echo "ret_code=".$ret_code."<br>\n";
					 // echo "ret_msg=".$ret_msg."<br>\n";//exit;

				}
			}
		}


		/*關閉連線*/
		//$mysms->close_conn();
		//exit;
	}
}
?>
