<?php
@header("Content-type: text/html; charset=utf-8");
function SendMail($email_to,$cc,$email_subject,$email_message,$charset,$mailsend){

	global $INFO,$FUNCTIONS;
	$charset    = "UTF-8";       //$INFO['IS'];
	$adminemail = $email_from = $INFO['email'];
	
	/**
	 * 装载字符串处理类，将全角转换为半角。以防止ICON转换的时候出现问题
	 */

	if (!is_object($Char_Class)){
		include_once ("Char.class.php");
		$Char_Class = new Char_class();
	}
	$s_email_subject = $email_subject;
	$s_email_message = $email_message;

	
	//提前把头部拿出来，以便于内部做ICONV转换
	$forHotmailSubject = $email_subject = iconv("UTF-8",$charset,str_replace("\r", '', str_replace("\n", '', $Char_Class->qj2bj($email_subject))));

	$email_subject     = '=?'.$charset.'?B?'.base64_encode($email_subject).'?=';
	//$email_message   = '<meta http-equiv="Content-Type" content="text/html; charset=$charset" \nContent-Transfer-Encoding:base64\n>'.$email_message;
	//也是为了提前拿出来用的
    $forHotmailMessage = $email_message = iconv("UTF-8",$charset,str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $Char_Class->qj2bj($email_message)))))));
   
    $forHotmailMessage = chunk_split(base64_encode($forHotmailMessage));

	$email_message     = chunk_split(base64_encode($email_message));
    
	
	
	if($email_from) {
		#$email_from = "=?".$charset."?B?".base64_encode($INFO[site_name])."?=<".$adminemail."";
		$forHotmailFrom = $email_from = $adminemail;
	} else {
		$forHotmailFrom = iconv("UTF-8",$charset,$INFO[site_name]);
		$email_from = "=?".$charset."?B?".base64_encode(iconv("UTF-8",$charset,$INFO[site_name]))."?=<".$adminemail.">";
	}
    

	if($mailsend == 1 && function_exists('mail')) {

	    $headers = strpos($email_to, 'hotmail') ?  "From: $forHotmailFrom\nMIME-Version: 1.0\nContent-type: text/html ; charset=$charset\nContent-Transfer-Encoding: base64\n" :  "From: $email_from\nMIME-Version: 1.0\nContent-type: text/html ; charset=$charset\nContent-Transfer-Encoding: base64\n";
		
		//strpos($email_to, ',') ? '' : @mail($email_to, $email_subject, $email_message, $headers);
		strpos($email_to, 'hotmail') ? "hotmail:".@mail($email_to, $forHotmailSubject, $forHotmailMessage, $headers) : "mail:".@mail($email_to, $email_subject, $email_message, $headers);
		
	} elseif($mailsend == 2) {
		global $smtpserver,$smtpuser, $smtppass,$smtpusermail,$smtpserverport,$auth,$sel_mail_enc;
		require_once( "class.phpmailer.php" );
		if ($email_to!=""){
			$mails = new PHPMailer(true);
			// $mails->SMTPDebug  = 2;
			$mails->CharSet    = 'utf-8';
			$mails->Host       = $smtpserver;
			$mails->SMTPAuth   = true;  
			$mails->Port       = $smtpserverport;                    // set the SMTP port for the GMAIL server
			$mails->Username   = $smtpuser; // SMTP account username
			$mails->Password   = $smtppass;        // SMTP account password
			$mails->From       = $smtpusermail;
			$mails->FromName   = "UTV";
			$email_array = explode(",",$email_to);
			print_r($email_array);
		    foreach($email_array as $k=>$to){
				echo $to;
				if (trim($to)!="")
					$mails->AddAddress($to);
			}
			$mails->Subject  = $s_email_subject;
			$mails->WordWrap   = 80; // set word wrap
			$mails->MsgHTML($s_email_message);
			$mails->IsHTML(true); // send as HTML
			$mails->IsSMTP();
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
		exit;




	} elseif($mailsend == 3) {

		ini_set('SMTP', $smtpserver);
		ini_set('smtp_port', $smtpserverport);
		ini_set('sendmail_from', $email_from);
        $headers = "";
		foreach(explode(',', $email_to) as $touser) {
			$touser = trim($touser);
			if($touser) {
	            $headers = strpos($touser, 'hotmail') ?  "From: $forHotmailFrom\nMIME-Version: 1.0\nContent-type: text/html ; charset=$charset\nContent-Transfer-Encoding: base64\n" :  "From: $email_from\nMIME-Version: 1.0\nContent-type: text/html ; charset=$charset\nContent-Transfer-Encoding: base64\n";
				strpos($touser, 'hotmail') ?  @mail($touser, $forHotmailSubject, $forHotmailMessage, $headers) : @mail($touser, $email_subject, $email_message, $headers);
			}
		}
		//exit;

	}
}

function smartshoperrorlog($ErrorType,$message,$halt){
	echo $message;
	return $message;
}

?>
