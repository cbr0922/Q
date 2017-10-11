<?php
/**
 * 調用接口
 *作用：便你調用的函數，傳入相關的值，就可以把數據提供OEYA數據的接口地址。
 *
 * ============================================================================
 * @author:     haiqing <lihaiqing_1982@hotmail.com>
 * @version:    1.0
 * ---------------------------------------------
 * $Author: haiqing $
 * $Date: 2008-06-20 18:37:40 +0800  $
 * $Id: ltfront.php 1370 2006-09-04 10:37:40Z 
*/
function oeyaapi($user_id,$user_name,$order_code,$pro_code,$pro_name,$price,$pro_cnt,$count,$back_code,$email,$tel,$other){
	
   $cookie=isset($_COOKIE['OEYA']) ? $_COOKIE['OEYA'] : '';
   if ($cookie=='') { 
      //如果cookie為空或不存在，說明cookie已經過期或沒有產生過這個cookie
       echo(""); 
   } 
   else { 
   
	   $manu_code = "pHTTi%2FOqijjI9g";                               //OEYA為廠商分配的廠商編號(加密後的值)，請修改
	   $host='http://www.ichannels.com.tw/manu_order_code.php';
	   $url = "&a_id=".urlencode($cookie);
	   $url .= "&manu_code=".$manu_code;                                //OEYA為廠商分配的廠商編號
	   $url .= "&user_id=".urlencode($user_id)."(".urlencode($user_name).")";
	   $url .= "&order_code=".urlencode($order_code);                   //用戶訂單號
	   $url .= "&count=".urlencode($count);                             //商品總價
	   $url .= "&pro_code=".urlencode($pro_code);                       //商品編號
	   $url .= "&pro_name=".urlencode($pro_name);                      //商品名稱
	   $url .= "&price=".urlencode($price);                             //商品價格
	   $url .= "&pro_cnt=".urlencode($pro_cnt);                         //商品數量
	   $url .= "&back_code=".urlencode($back_code);                     //廠商合作項目代號
	   $url .= "&email=".urlencode($email);                             //購買人郵件
	   $url .= "&tel=".urlencode($tel);                                 //購買人電話
	   $url .= "&other=".urlencode($other);                             //預留欄位
       if (function_exists('curl_init')) {
            // Use CURL if installed...
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, $host);
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, ' oeya api (curl) ' . phpversion());
            $result = curl_exec($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
 
        } else {
            // Non-CURL based version...
            $context =
            array('http' =>
                    array('method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded'."\r\n".
                                    'User-Agent: oeya api (non-curl) '.phpversion()."\r\n".
                                    'Content-length: ' . strlen($url),
                        'content' => $url));
            $contextid = stream_context_create($context);
            $sock = fopen($host, 'r', false, $contextid);
            if ($sock) {
                $result = '';
                while (!feof($sock)) {
                    $result .= fgets($sock, 4096);
                }
                fclose($sock);
            }
			
        }
       echo( $result);

   }


}

?>