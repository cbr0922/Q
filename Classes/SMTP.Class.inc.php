<?php
@header("Content-type: text/html; charset=utf-8");
class SMSP_smtp
{
	/* Public Variables */

	var $smtp_port;
	var $time_out;
	var $host_name;
	var $log_file;
	var $relay_host;
	var $debug;
	var $auth;
	var $user;
	var $pass;
	/* Private Variables */
	var $sock;
	/* Constractor */
	function SMSP_smtp($relay_host = "", $smtp_port = 25,$auth = false,$user="",$pass="")
	{
		$this->debug = FALSE;
		$this->smtp_port = $smtp_port;
		$this->relay_host = $relay_host;
		$this->time_out = 30; //is used in fsockopen()
		$this->auth = $auth;//auth
		$this->user = $user;
		$this->pass = $pass;
		$this->host_name = "localhost"; //is used in HELO command
		$this->log_file = "";
		$this->sock = FALSE;
	}

	/**
    *初始化电子邮件类
    */
	function Mailto($email_to,$cc,$email_subject,$email_message,$sel_mail_tar_lang,$mailsend,$tmpname,$filename){
		global $INFO;
		
		//include_once "sendmail.inc.php";
		//return SendMail($to,$cc,$email_subject,$email_message,$sel_mail_tar_lang,$mailsend);
		global $smtpserver,$smtpuser, $smtppass,$smtpusermail,$smtpserverport,$auth,$sel_mail_enc;
		date_default_timezone_set('Etc/UTC');
		include_once 'PHPMailerAutoload.php';
		
		if ($email_to!=""){


			$mails = new PHPMailer(true);
			if($mailsend==2)
				$mails->isSMTP();
			else
				$mails->isSendmail();
			// $mails->SMTPDebug  = 2;
			$mails->CharSet    = 'utf-8';
			$mails->Host       = $smtpserver;
		 	$mails->SMTPDebug       = 0;
			$mails->SMTPAuth   = $auth;
			$mails->SMTPSecure   = $ssl;
			$mails->Port       = $smtpserverport;                    // set the SMTP port for the GMAIL server
		    $mails->Username   = $smtpuser; // SMTP account username
		    $mails->Password   = $smtppass;        // SMTP account password

			$mails->From       = $smtpusermail;
			$mails->FromName   = $INFO['site_name'];

			$email_array = explode(",",$email_to);
			$email_array = array_unique($email_array);
			//$mails->AddAddress($smtpusermail);
		    foreach($email_array as $k=>$to){
				if (trim($to)!="")
					$mails->AddBCC($to);
			}
			$mails->Subject  = $email_subject;

			$mails->WordWrap   = 80; // set word wrap
			$mails->MsgHTML($email_message);
			$mails->IsHTML(true); // send as HTML

			if ($tmpname !=""){
				//夾帶附件檔
				$mails->AddAttachment($tmpname,$filename);
			}

			if($mailsend==2)
				$mails->IsSMTP();
			else
				$mails->IsMail();

			try {
				$mails->Send();
			}

			catch (phpmailerException $e) {
				return $e->errorMessage();
			}
			catch (Exception $e) {
				return $e->getMessage();
			}

		}

	}

	function MailForsmartshop($to,$cc,$MailSendType,$Array){

		global $DB,$INFO,$mail_type;
		global $smtpserver,$smtpuser, $smtppass,$smtpusermail,$smtpserverport,$auth,$sel_mail_enc;
		//echo $MailSendType;
		/**
		 * 这里是后台群发部分的代码
		 */
		if ($MailSendType=="GroupSend" && is_array($Array)){
			$title       = $Array[mailsubject];
			$mailtext    = $Array[mailbody];
		}

		$tmpname		 = $Array[tmpname] != '' ? $Array[tmpname] : '';
		$filename	   = $Array[filename] != '' ? $Array[filename] : '';

		/**
		 * 下边是后台设置的一些固定项目的发送
		 */
		if (is_integer($MailSendType)) {
			$en_truename='';
			$Query  = $DB->query(" select en_firstname,en_secondname from `{$INFO[DBPrefix]}user` where username='".trim($Array['username'])."' limit 0,1");
			$Num    = $DB->num_rows($Query);
			if ( $Num>0 ) {
				$Rs= $DB->fetch_array($Query);
				$en_truename = $Rs['en_firstname'] . " " . $Rs['en_secondname'];
			}

			$Query  = $DB->query(" select * from `{$INFO[DBPrefix]}sendtype` where sendtype_id=".intval($MailSendType)." and sendstatus=1 limit 0,1");
			$Num    = $DB->num_rows($Query);
			if ( $Num>0 ) {
				$Rs= $DB->fetch_array($Query);
				$title = str_replace ("@shopname@",$INFO['site_name'],$Rs['sendtitle']);
				$title = str_replace ("@goodsname@",trim($Array['goodsname']),$title);
				$title = str_replace ("@provider_name@",trim($Array['provider_name']),$title);
				//$title = str_replace ("@truename@",trim($Array['receiver_name']),$title);
				$title = str_replace ("@username@",trim($Array['username']),$title);
				if (trim($Array['truename'])!=""){
					$title = str_replace ("@truename@",trim($Array['truename']),$title);
				}else if (trim($Array['receiver_name'])!=""){
					$title = str_replace ("@truename@",trim($Array['receiver_name']),$title);
				}else{
					$title = str_replace ("@truename@",trim($en_truename),$title);
				}
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
				$mailtext = str_replace ("@ticketname@",trim($Array['ticketname']),$mailtext);
				$mailtext = str_replace ("@bonuspoint@",trim($Array['bonuspoint']),$mailtext);
				$mailtext = str_replace ("@provider_name@",trim($Array['provider_name']),$mailtext);
				$mailtext = str_replace ("@provider_thenum@",trim($Array['provider_thenum']),$mailtext);
				$mailtext = str_replace ("@provider_loginpassword@",trim($Array['provider_loginpassword']),$mailtext);
				$mailtext = str_replace ("@k_kefu_con@",trim($Array['k_kefu_con']),$mailtext);
				$mailtext = str_replace ("@k_post_con@",trim($Array['k_post_con']),$mailtext);
				$mailtext = str_replace ("@recommendno@",trim($Array['recommendno']),$mailtext);
				$mailtext = str_replace ("@sendcontent@",trim($Array['sendcontent']),$mailtext);
				$mailtext = str_replace ("@checkno@",trim($Array['checkno']),$mailtext);
				$mailtext = str_replace ("@authnum@",trim($Array['authnum']),$mailtext);
				$mailtext = str_replace ("@ticketcode@",trim($Array['ticketcode']),$mailtext);
				$mailtext = str_replace ("@ticketid@",trim($Array['ticketid']),$mailtext);
				$mailtext = str_replace ("@ticketname@",trim($Array['ticketname']),$mailtext);
				$mailtext = str_replace ("@money@",trim($Array['money']),$mailtext);
			}else{
				return ;
			}
		}

		include_once  RootDocumentShare."/Smtp.config.php";
		/**
         *   如果定单是带HTML 定单信息的资料！
         */


		if (intval($Array['Order_id'])>0){

			include_once ("MailCreateOrder.class.php");
			$OrderHtml = CreateMailForOrder(intval($Array['Order_id']));
			$mailtext  = str_replace ("@CreateHtml@",$OrderHtml,$mailtext);
			/*
			$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=1");
			$Rs = $DB->fetch_array($Query);
			$sysmail = "," . $Rs['mail'];
			*/

			$Query = $DB->query("select o.email from `{$INFO[DBPrefix]}operater` as o inner join `{$INFO[DBPrefix]}operatergroup` as og on o.groupid=og.opid where o.status=1 and ( og.maillist like '%," . $MailSendType . "' or og.maillist like '%," . $MailSendType . ",%' or og.maillist like '" . $MailSendType . ",%' or og.maillist='" . $MailSendType . "')");
			while($Result= $DB->fetch_array($Query)){
				if ($Result['email']!=""){
					$sysmail .= "," . $Result['email'];
				}
			}
			$Sql   = "select * from `{$INFO[DBPrefix]}administrator` as og where og.maillist like '%," . $MailSendType . "' or og.maillist like '%," . $MailSendType . ",%' or og.maillist like '" . $MailSendType . ",%' or og.maillist='" . $MailSendType . "'";
			$Query = $DB->query($Sql);
			$Num   = $DB->num_rows($Query);
			if ($Num>0){
				$Result = $DB->fetch_array($Query);
				$sysmail .= "," . $Result['email'];
			}
		}
		//echo $mailtext;
		//exit;

		/**
         * 检测是否使用本地编码,因为对邮件都做了UTF-8处理。所以邮件将都以UTF-8格式发送
         * 其中Mailto 函数的最后值是对邮件类型的判断。
         * 1=》普通邮件，使用SENDMAIL发送
         * 2=》SMTP发信
         * 3=》发送多封用逗号分割的邮件
         */
        $MailtoUser  = trim($cc)!=""  ? $to.",".$cc :  $to ;
		//if ($sel_mail_enc='UTF-8'){
//echo $mail_type;
			$mailsubject  = $title;
			$mailbody     = $mailtext;
			if ($mail_type=="smtp"){
				return $this->Mailto($MailtoUser . $sysmail,$cc,$mailsubject,$mailbody,$sel_mail_tar_lang,2,$tmpname,$filename);
			}elseif ($mail_type=="mailto"){
				if(strpos($MailtoUser, ',')){
					return $this->Mailto($MailtoUser . $sysmail,$cc,$mailsubject,$mailbody,$sel_mail_tar_lang,3,$tmpname,$filename);
				}else{
					return $this->Mailto($MailtoUser . $sysmail,$cc,$mailsubject,$mailbody,$sel_mail_tar_lang,1,$tmpname,$filename);
				}
			}
		//}
	}
}

?>
