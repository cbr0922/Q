<?php 

include("../configs.inc.php");
include("global.php");

$app_id     = $INFO['mod.login.qq.app_id'];
$app_secret = $INFO['mod.login.qq.app_key'];
$my_url     = $INFO['site_url']."/member/login_QQ.php";
$home_url   = $INFO['site_url'];

session_start();
$code = $_REQUEST["code"];

if(empty($code)) {

  $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
  $scope = "get_user_info,get_info";
  $format = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=%s&redirect_uri=%s&state=%s&scope=%s";
  $dialog_url = sprintf($format, $app_id, urlencode($my_url), $_SESSION['state'], $scope);
        
   echo("<script> top.location.href='" . $dialog_url . "'</script>");

}

if($_REQUEST['state'] == $_SESSION['state']) {

   $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
     . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
     . "&client_secret=" . $app_secret . "&code=" . $code;

  $response = file_get_contents($token_url);
  $params = null;
  parse_str($response, $params);

  
  $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
    . $params['access_token'];

     
     
    $str  = file_get_contents($graph_url);
    if (strpos($str, "callback") !== false){
        $lpos = strpos($str, "(");
        $rpos = strrpos($str, ")");
        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
    }
    $cb = json_decode($str);
    if (isset($cb->error)){
        echo "<h3>error:</h3>" . $cb->error;
        echo "<h3>msg  :</h3>" . $cb->error_description;
        exit;
    }

    $qq_openid = $cb-> openid;


  $Query_old = $DB->query("select  qq_id from `{$INFO[DBPrefix]}user` where qq_id='".$qq_openid."' limit 0,1");
  $Num_old   = $DB->num_rows($Query_old);
  if ($Num_old>0){
          //echo "已經有用 QQ 登入過了";
          //$FUNCTIONS->sorry_back('back',$MemberLanguage_Pack[SorryIsHadUserName]); //"對不起，帳號發生重複！請重新選擇輸入帳號！";
          echo ""; // do nothing
  }
  else{
	  $graph_url = sprintf( "https://graph.qq.com/user/get_info?access_token=%s&oauth_consumer_key=%s&openid=%s", $params['access_token'], $app_id, $qq_openid );
	  $user = json_decode(file_get_contents($graph_url));
	  if($user->ret == 0){
		  $username  = $user->data->name;
		  $truename  = $user->data->nick;
		  $nickname  = $user->data->nick;
		  $email     = $user->data->email;

		  $born_date = implode( '-', array( $user->data->birth_year, $user->data->birth_month, $user->data->birth_day ) );

		  $loc = explode( ' ', $user->data->location );
		  if( count( $loc ) == 3 ){
			  if( $loc[1] == "台湾" ){
				  $country = "台灣";
				  $city    = $loc[2];
			  }
			  else{
				  $country = $loc[0];
				  $canton  = $loc[1];
				  $city    = $loc[2];
			  }
		  }

		  if( $user->data->sex == 1 ){
			  $sex = 0; // 性別男
		  }
		  else if( $user->data->sex == 2 ){
			  $sex = 1; // 性別女
		  }
		  else{
			  $sex = 0; // 性別未填，預設為男
		  }
	  }

	  if( $username == "" ){
		  $username = "QQ_".date( "Ymd", time() ).str_pad( rand( 1,9999), 5, "0", STR_PAD_LEFT );
	  }

	  if( defined( $country ) && $country !="中国" ){ 
		  $county = $country; 
	  }
	  else{
		  $county = "中國大陸";
	  }
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
		  $userlevel = 2;

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
			  $userlevel = 2;
		  }
	  }

          $db_string = $DB->compile_db_insert_string( array (
      	    'username'          => $username,
      	    'true_name'         => $truename,
      	    'email'             => $email,
      	    'user_level'        => $userlevel,
	    'companyid'         => $companyid,
      	    'vloid'             => 1,
      	    'user_state'        => 0,
      	    'reg_date'          => date("Y-m-d",time()),
      	    'reg_ip'            => $FUNCTIONS->getip(),
	    'memberno'          => trim($memberno),
	    'recommendno'       => trim($u_recommendno),
      	    'qq_id'             => $qq_openid,
            'born_date'         => $born_date,
            'nickname'          => $nickname,
            'city'              => $city,
            'canton'            => $canton,
            'Country'           => $country,
      	    )      
          );

          $Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
          $Result_Insert=$DB->query($Sql);
  }

      $Sql  = "select u.user_id,u.true_name,u.username,u.points,u.toppoints,u.user_level,u.vloid,v.volname,l.level_name,u.isold,u.islogin from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}forum_vol` v on ( v.vloid=u.vloid ) left join `{$INFO[DBPrefix]}user_level` l on (u.user_level=l.level_id) where u.qq_id='".$qq_openid."' and user_state!=1 limit 0,1";

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

 //   print_r($_SESSION);
 //   exit();
      echo("<script> top.location.href='" . $home_url . "'</script>");
}
else {
  echo("The state does not match. You may be a victim of CSRF.");
}

?>
