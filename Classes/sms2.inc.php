<?php

/*
 * The following code is borrow from 
 *   http://php.net/manual/en/function.parse-ini-string.php
 * , for servers which not support parse_ini_string() 
 */
if(!function_exists('parse_ini_string')){
	function parse_ini_string($str, $ProcessSections=false){
		$lines  = explode("\n", $str);
		$return = Array();
		$inSect = false;
		foreach($lines as $line){
			$line = trim($line);
			if(!$line || $line[0] == "#" || $line[0] == ";")
				continue;
			if($line[0] == "[" && $endIdx = strpos($line, "]")){
				$inSect = substr($line, 1, $endIdx-1);
				continue;
			}
			if(!strpos($line, '=')) // (We don't use "=== false" because value 0 is not valid as well)
				continue;

			$tmp = explode("=", $line, 2);
			if($ProcessSections && $inSect)
				$return[$inSect][trim($tmp[0])] = ltrim($tmp[1]);
			else
				$return[trim($tmp[0])] = ltrim($tmp[1]);
		}
		return $return;
	}
}

/* 
 * Author: Chun-Yu Lee (Mat) <matlinuxer2@gmail.com>
 */

class SocketHttpRequest
{
    var $sHostAdd;
    var $sUri;
    var $iPort;  
    var $sRequestHeader; 
    var $sResponse;
   
    function HttpRequest($sUrl)
    {
        $sPatternUrlPart = '/http:\/\/([a-z-\.0-9]+)(:(\d+)){0,1}(.*)/i';
        $arMatchUrlPart = array();
        preg_match($sPatternUrlPart, $sUrl, $arMatchUrlPart);
       
        $this->sHostAdd = gethostbyname($arMatchUrlPart[1]);
        if (empty($arMatchUrlPart[4]))
        {
            $this->sUri = '/';
        }
        else
        {
            $this->sUri = $arMatchUrlPart[4];
        }
        if (empty($arMatchUrlPart[3]))
        {
            $this->iPort = 9600;
        }
        else
        {
            $this->iPort = $arMatchUrlPart[3];
        }
       
        $this->addRequestHeader('Host: '.$arMatchUrlPart[1]);
        $this->addRequestHeader('Connection: Close');

    }
   
    function addRequestHeader($sHeader)
    {
        $this->sRequestHeader .= trim($sHeader)."\r\n";
    }
   
    function sendRequest($sMethod = 'GET', $sPostData = '')
    {
        $sRequest = $sMethod." ".$this->sUri." HTTP/1.1\r\n";
        $sRequest .= $this->sRequestHeader;
        if ($sMethod == 'POST')
        {
            $sRequest .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $sRequest .= "Content-Length: ".strlen($sPostData)."\r\n";
            $sRequest .= "\r\n";
            $sRequest .= $sPostData."\r\n";
        }
        $sRequest .= "\r\n";
       
        $sockHttp = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$sockHttp)
        {
            die('socket_create() failed!');
        }
       
        $resSockHttp = socket_connect($sockHttp, $this->sHostAdd, $this->iPort);
        if (!$resSockHttp)
        {
            die('socket_connect() failed!');
        }
       
        socket_write($sockHttp, $sRequest, strlen($sRequest));
       
        $this->sResponse = '';
        while ($sRead = socket_read($sockHttp, 4096))
        {
            $this->sResponse .= $sRead;
        }
       
        socket_close($sockHttp);
    }
   
    function getResponse()
    {
        return $this->sResponse;
    }
   
    function getResponseBody()
    {
        $sPatternSeperate = '/\r\n\r\n/';
        $arMatchResponsePart = preg_split($sPatternSeperate, $this->sResponse, 2);
        return $arMatchResponsePart[1];
    }
}

$ret_code_ref_array = array(
	"*" => "系統發生錯誤，請聯絡三竹資訊窗口人員",
	"a" => "簡訊發送功能暫時停止服務，請稍候再試",
	"b" => "簡訊發送功能暫時停止服務，請稍候再試",
	"c" => "請輸入帳號",
	"d" => "請輸入密碼",
	"e" => "帳號、密碼錯誤",
	"f" => "帳號已過期",
	"h" => "帳號已被停用",
	"k" => "無效的連線位址",
	"m" => "必須變更密碼，在變更密碼前，無法使用簡訊發送服務",
	"n" => "密碼已逾期，在變更密碼前，將無法使用簡訊發送服務",
	"p" => "沒有權限使用外部Http程式",
	"r" => "系統暫停服務，請稍後再試",
	"s" => "帳務處理失敗，無法發送簡訊",
	"t" => "簡訊已過期",
	"u" => "簡訊內容不得為空白",
	"v" => "無效的手機號碼",
	"0" => "預約傳送中",
	"1" => "已送達業者",
	"2" => "已送達業者",
	"3" => "已送達業者",
	"4" => "已送達手機",
	"5" => "內容有錯誤",
	"6" => "門號有錯誤",
	"7" => "簡訊已停用",
	"8" => "逾時無送達",
	"9" => "預約已取消"
);

class sms2{

   var $ret_msg;
   var $server_ip;
   var $server_port;
   var $server_user_acc;
   var $server_user_pwd;
   
   function sms2(){  }

   /* 函式說明：建立連線與認證
    * $server_ip:伺服器IP, $server_port:伺服器Port, $TimeOut:連線timeout時間
    * $user_acc:帳號, $user_pwd:密碼
    * return -1：網路連線失敗, 0：連線與認證成功, 1:連線成功，認證失敗
    */
   function create_conn($server_ip, $server_port, $TimeOut, $user_acc, $user_pwd){ 
	   $this->server_ip = $server_ip;
	   $this->server_port = $server_port;
	   $this->server_user_acc = $user_acc;
	   $this->server_user_pwd = $user_pwd;
	   return 0; 
   }  

   /* 函式說明：傳送文字簡訊
    * $tel:接收門號, 簡訊內容
    * return ret_code
    */
   function send_text( $mobile_number, $message){
	   $ret_code = -1;

	   // Use external table reference
	   global $ret_code_ref_array;
	   //SpLmGetfee22512ab90

	   $URL = "http://".$this->server_ip.":".$this->server_port."/SmSendGet.asp"
		   ."?username=".$this->server_user_acc
		   ."&password=".$this->server_user_pwd
		   ."&dstaddr=".$mobile_number
		   ."&DestName=DearCustomer"
		   ."&dlvtime="
		   ."&vldtime="
		   ."&smbody=".$message;

	   $SendGet = new SocketHttpRequest();  // 建立物件
	   $SendGet->HttpRequest( $URL );         // 呼叫成員方法
	   $SendGet->sendRequest(); //發送
	   $resp_text = $SendGet->getResponseBody(); //取回傳值

	   $ini_str = iconv( "Big5", "UTF-8", $resp_text );
	   $ret_ary =  parse_ini_string ( $ini_str );
	   if( array_key_exists( "statuscode", $ret_ary ) ){
		   $statuscode = $ret_ary['statuscode'];
		   if( $statuscode == "0" 
				   || $statuscode == "1" 
				   || $statuscode == "2" 
				   || $statuscode == "3" 
				   || $statuscode == "4" ) {
			   $msgid = $ret_ary['msgid'];
			   $AccountPoint= $ret_ary['AccountPoint'];

			   // 傳送成功
			   $ret_code = 0;
			   $this->ret_msg = "傳送成功"; 
		   }
		   else{
			   //$Error = iconv( "Big5", "UTF-8", $ret_ary['Error'] );
			   $ret_code_str = $ret_code_ref_array[ $statuscode ];

			   // 傳送不成功
			   $ret_code = -1;
			   $this->ret_msg = $ret_code_str; 
		   }
	   }
	   else{
		   //throw new Exception('SMS return format mismatch');
		   // 傳送系統不吻合
		   $ret_code = -1;
		   $this->ret_msg = "傳送系統不吻合"; 
	   }

	   return $ret_code;
   }


   /* 函式說明：傳送WapPush簡訊
    * $tel:接收門號, 簡訊內容
    * return ret_code
    */
   function send_wappush( $mobile_number, $wap_title, $wap_url){

   }

   /* 函式說明：查詢text發訊結果
    * $messageid:訊息ID
    * return ret_code
    */
   function query_text( $messageid){

   }


   /* 函式說明：查詢wappush發訊結果
    * $messageid:訊息ID
    * return ret_code
    */
   function query_wappush( $messageid){

   }

   /* 函式說明：接收回傳的訊息
    * return ret_code
    */
   function recv_msg(){

   }   

   /* 回傳ret_content的值 */
   function get_ret_msg(){
      return $this->ret_msg;
   }

   /* 回傳send_tel的值 */
   function get_send_tel(){

   }
  
   /* 關閉連線 */
   function close_conn(){

   }
}
?>