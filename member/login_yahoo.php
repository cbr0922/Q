<?php
@session_start();
@header("Content-type: text/html; charset=utf-8");

include("../configs.inc.php");
if(!class_exists("OAuthException")){
require_once "OAuth.php";       //oauth library
}
include("global.php");

$key      = $INFO['mod.login.yahoo.key'];
$secret   = $INFO['mod.login.yahoo.secret'];
$home_url = $INFO['site_url'];
$base_url = $INFO['site_url']."/member/login_yahoo.php";

$request_token_endpoint = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
$authorize_endpoint = 'https://api.login.yahoo.com/oauth/v2/request_auth';
$oauth_access_token_endpoint = 'https://api.login.yahoo.com/oauth/v2/get_token';

/*
*
* common.php
*
*/

function run_curl($url, $method = 'GET', $headers = null, $postvals = null, $noheader = false ){
    $ch = curl_init($url);
    
    if ($method == 'GET'){
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    } else {
        $options = array(
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postvals,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => 3
        );
        curl_setopt_array($ch, $options);
    }
    
    $response = curl_exec($ch);

    if( $noheader ){
	    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	    $header = substr($response, 0, $header_size);
	    $body = substr($response, $header_size );
	    $response = $body;
    }
    
    curl_close($ch);

    return $response;
}


/*
*
* Leave if loginned
*
*/
if ( intval($_SESSION['user_id']) > 0 ){
	$FUNCTIONS->header_location( $home_url );
}   
/*
*
* begin.php
*
*/
if ( empty( $_GET['oauth_verifier'] ) )
{

	//initialize consumer
	$consumer = new OAuthConsumer($key, $secret, NULL);

	//prepare to get request token
	$sig_method = new OAuthSignatureMethod_HMAC_SHA1();
	$parsed = parse_url($request_token_endpoint);
	$params = array('oauth_callback' => $base_url);

	//sign request and get request token
	$req_req = OAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $request_token_endpoint, $params);
	$req_req->sign_request($sig_method, $consumer, NULL);
	$req_token = run_curl($req_req->to_url(), 'GET');

	//if fetching request token was successful we should have oauth_token and oauth_token_secret
	parse_str($req_token, $tokens);//print_r($tokens);
	$oauth_token = $tokens['oauth_token'];
	$oauth_token_secret = $tokens['oauth_token_secret'];

	//store key and token details in cookie to pass to complete stage
	setcookie("requestToken", "key=$key&token=$oauth_token&token_secret=$oauth_token_secret");

	//build authentication url following sign-in and redirect user
	$auth_url = $authorize_endpoint . "?oauth_token=$oauth_token";
	$FUNCTIONS->header_location( $auth_url );
}
/*
*
* end.php
*
*/

else{
	//get request token params from cookie and parse values
	//$request_cookie = $_COOKIE["requestToken"];
	parse_str($_COOKIE["requestToken"],$request_cookie);

	//create required consumer variables
	$test_consumer = new OAuthConsumer($key, $secret, NULL);
	$req_token = new OAuthConsumer($request_cookie["amp;token"], $request_cookie["amp;token_secret"], NULL);
	$sig_method = new OAuthSignatureMethod_HMAC_SHA1();

	//exchange authenticated request token for access token
	$params = array('oauth_verifier' => $_GET['oauth_verifier']);
	$acc_req = OAuthRequest::from_consumer_and_token($test_consumer, $req_token, "GET", $oauth_access_token_endpoint, $params);
	$acc_req->sign_request($sig_method, $test_consumer, $req_token);
	$access_ret = run_curl($acc_req->to_url(), 'GET');

	//if access token fetch succeeded, we should have oauth_token and oauth_token_secret
	//parse and generate access consumer from values
	$access_token = array();
	parse_str($access_ret, $access_token);
	$gid = $access_token['xoauth_yahoo_guid'];
	//print_r($access_token);
	$access_consumer = new OAuthConsumer($access_token['oauth_token'], $access_token['oauth_token_secret'], NULL);

	//build update PUT request payload
	$yquery = "select * from social.profile where guid=me";

	$url = sprintf("https://query.yahooapis.com/v1/yql?q=%s&format=json",
		urlencode($yquery)
	);

	$params = array('oauth_session_handle' => $access_token[oauth_session_handle]);
	//build and sign request
	$request = OAuthRequest::from_consumer_and_token($test_consumer, $access_consumer, 'PUT', $url, array());
	$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $test_consumer, $access_consumer);

	//define request headers
	$headers = array("Accept: application/json");
	$headers[] = $request->to_header();
	$headers[] = "Content-type: application/json";

	//json encode request payload and make PUT request
	$content = "";
	$resp = run_curl($url, 'PUT', $headers, $content, true );

	$jsonObject = json_decode($resp);

	$yahoo_gid = $gid ? $gid : -1 ;
	$yahoo_name = $jsonObject->query->results->profile->familyName.$jsonObject->query->results->profile->givenName;
	$yahoo_email = $jsonObject->query->results->profile->emails->handle;
	$yahoo_gender = $jsonObject->query->results->profile->gender;

	// 這裡將 yahoo_email 更換為 primary 的那一個
	$emailitems = $jsonObject->query->results->profile->emails;
	foreach( $emailitems as $item ){
		if( isset($item->primary) ) {
			$yahoo_email = $item->handle;
		}
	}

	echo $yahoo_gid."/".$yahoo_name."/".$yahoo_email."/".$yahoo_gender;

	//echo "$yahoo_name 你好，你的 email 是 $yahoo_email, 識別碼是 $yahoo_gid ";
	//echo $yahoo_gid;
	if($yahoo_gid==-1){
		echo "連接失敗，請聯繫管理員";exit;
	}


	/*$Query_old = $DB->query("select  yahoo_gid from `{$INFO[DBPrefix]}user` where yahoo_gid='".$yahoo_gid."' limit 0,1");
	$Num_old   = $DB->num_rows($Query_old);
	if ($Num_old>0){
		//echo "已經有用 yahoo 登入過了";
		//$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[SorryIsHadUserName]); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
		echo ""; // do nothing
	}
	else{
		$county = "台灣";
		$Query_country = $DB->query("select membercode from `{$INFO[DBPrefix]}area` where areaname='" . $county . "' and top_id=0");
		$Rs_country = $DB->fetch_array($Query_country);
		$firstcode = $Rs_country['membercode'];
		$memberno = $FUNCTIONS->setMemberCode($firstcode); 

		{
			if ($_POST['u_recommendno']!=""){
				$u_recommendno = $_POST['u_recommendno'];
			}
			else{
				$u_recommendno = $_COOKIE['u_recommendno'];
			}

			$companyid = 0;
			$userlevel = intval($INFO['reg_userlevel']);

			if ($_POST['companypassword'] !=""){
				$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where openpwd='" . $_POST['companypassword'] . "' limit 0,1");
			}elseif($_SESSION['saler']!=""){
				$Query_old = $DB->query("select  * from `{$INFO[DBPrefix]}saler` where login='" . $_SESSION['saler'] . "' limit 0,1");
			}

			if ($Query_old !=""){
				$Num_old   = $DB->num_rows($Query_old);
			}

			if ($Num_old>0){
				$Result = $DB->fetch_array($Query_old);
				$companyid = intval($Result['id']);
				$userlevel = $Result['userlevel'];
				$givebouns  = intval($Result['givebouns']);
			}

			if($userlevel==0){
				$userlevel = intval($INFO['reg_userlevel']);
			}
		}

		$db_string = $DB->compile_db_insert_string( array (
		    'username'          => $yahoo_gid."@yahoo",
		    'true_name'         => $yahoo_name,
		    'email'             => $yahoo_email,
		    'user_level'        => $userlevel,
		    'companyid'         => $companyid,
		    'vloid'             => 1,
		    'user_state'        => 0,
		    'reg_date'          => date("Y-m-d",time()),
		    'reg_ip'            => $FUNCTIONS->getip(),
		    'memberno'          => trim($memberno),
		    'recommendno'       => trim($u_recommendno),
		    'yahoo_gid'       => $yahoo_gid,
		    )      
		);

		$Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

		$Result_Insert=$DB->query($Sql);
		$Insert_id = $DB->insert_id();

	    if ($Result_Insert){
	      $FUNCTIONS->AddBonuspoint(intval($Insert_id),intval($INFO['regpoint']),6,"會員註冊",1,0);
	    }
	}

	$Sql  = "select u.user_id,u.true_name,u.username,u.points,u.toppoints,u.user_level,u.vloid,v.volname,l.level_name,u.isold,u.islogin from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}forum_vol` v on ( v.vloid=u.vloid ) left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.yahoo_gid='".$yahoo_gid."' and user_state!=1 limit 0,1";

	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result = $DB->fetch_array($Query);
		$_SESSION['Member_Volid'] = $Rs['vloid'];
		$_SESSION['user_id']      = $Result['user_id'];
		$_SESSION['username']      = $Result['username'];
		$_SESSION['true_name']    = $Result['true_name'];
		$_SESSION['user_level']   = $Result['user_level'];
		if (intval($Result['user_level'])!=0){
			$_SESSION['userlevelname']  = $Result['level_name'];
		}else{
			$_SESSION['userlevelname']  = $MemberLanguage_Pack[Member_say];
		}
		$_SESSION['YesPass'] = "YesPass";
		//每日登陸送紅利
		$d = date('d',time());
		$y = intval(date('Y',time()));
		$m = intval(date('m',time()));
		$overtime = mktime(0,0,0,$m,$d,$y);
		$B_Sql = "select * from `{$INFO[DBPrefix]}bonuspoint` where `type`=8 and user_id='" . intval($Result['user_id']) . "' and addtime>='" . $overtime . "' and addtime<='" . ($overtime+60*60*24) . "'";
		$B_Query = $DB->query($B_Sql);
		$B_Num   = $DB->num_rows($B_Query);
		if ($B_Num<=0)
			$FUNCTIONS->AddBonuspoint(intval($Result['user_id']),intval($INFO['loginpoint']),8,"會員登陸" . trim($Result['username']),1,0);
			
		$updatesql = "update `{$INFO[DBPrefix]}user` set islogin=1 where user_id='" . $Result['user_id'] . "'";
		$DB->query($updatesql);
		if($Result['isold']==1 && $Result['islogin']==0){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script>alert('請檢查您的資料');</script>";	
		}
	}

	$FUNCTIONS->header_location( $home_url );*/
}

?>
