<?php
include_once(dirname(__FILE__) ."/../configs.inc.php");
include_once(RootDocumentAdmin."/inc/global.php");

$INFO['shopnc_wp_path']	= '/blog';
include_once( dirname(__FILE__) . '/..'.$INFO['shopnc_wp_path'].'/wp-config.php' );
//--------------------wordpress会员登录-------------------------
if($_POST['Action']=="Login" && trim($_POST['username'])!="" && trim($_POST['passwd'])!="") {
	$user_login = '';
	$user_pass = '';
	$using_cookie = FALSE;
	//
	//	if ( !isset( $_REQUEST['redirect_to'] ) || is_user_logged_in() )
	//	$redirect_to = 'wp-admin/';
	//	else
	//	$redirect_to = $_REQUEST['redirect_to'];

	$user_login = trim($_POST['username']);
	$user_login = sanitize_user( $user_login );
	$user_pass  = trim($_POST['passwd']);
	do_action_ref_array('wp_authenticate', array(&$user_login, &$user_pass));
	if ( $user_login && $user_pass && empty( $errors ) ) {
		$user = new WP_User(0, $user_login);

		// If the user can't edit posts, send them to their profile.
		if ( !$user->has_cap('edit_posts') && ( empty( $redirect_to ) || $redirect_to == 'wp-admin/' ) )
		$redirect_to = get_option('siteurl') . '/wp-admin/profile.php';

		if ( wp_login($user_login, $user_pass, $using_cookie) ) {
			if ( !$using_cookie )
			wp_setcookie($user_login, $user_pass, false, '', '', $rememberme);
			do_action('wp_login', $user_login);
		} else {
			if ( $using_cookie )
			$errors['expiredsession'] = __('Your session has expired.');
		}
	}
}

//----------------------------wordpress会员注册-----------------------
if($_POST['Action']=='Insert') {
	include_once( dirname(__FILE__) .  '/../'.$INFO['shopnc_wp_path'].'/wp-includes/registration.php');
	$user_nickname	= trim($_POST['nickname']);
	$user_username	= trim($_POST['username']);
	$user_name		= empty($user_nickname)?$user_username:$user_nickname;
	$user_login = sanitize_user( $user_name );
	$user_email = apply_filters( 'user_registration_email', trim($_POST['email']) );
	$user_pass	= trim($_POST['password']);
	$user_id = wp_create_user( $user_login, $user_pass, $user_email );
	do_action_ref_array('wp_authenticate', array(&$user_login, &$user_pass));
	if ( $user_login && $user_pass && empty( $errors ) ) {
		$user = new WP_User(0, $user_login);

		// If the user can't edit posts, send them to their profile.
		if ( !$user->has_cap('edit_posts') && ( empty( $redirect_to ) || $redirect_to == 'wp-admin/' ) )
		$redirect_to = get_option('siteurl') . '/wp-admin/profile.php';

		if ( wp_login($user_login, $user_pass, $using_cookie) ) {
			if ( !$using_cookie )
			wp_setcookie($user_login, $user_pass, false, '', '', $rememberme);
			do_action('wp_login', $user_login);
		} else {
			if ( $using_cookie )
			$errors['expiredsession'] = __('Your session has expired.');
		}
	}
}

//-----------------------------wordpress登录页面转向-----------------------
if($action == 'login' or (isset($action) and $action == '') or $_GET['loggedout'] == 'true') {
	header("Location:../member/login_windows.php");
	exit();
}

//----------------------------wordpress注册页面转向--------------------------
if($action == 'register') {
	header("Location:../member/reg_rule.php");
	exit();
}

//----------------------------wordpress会员退出-------------------------
if(isset($action) and $action == 'logout') {
	header("Location:../member/member_login.php?Action=Logout");
	exit();
}
if($_GET['Action']=="Logout" ) {
	wp_clearcookie();
}

//-----------------------shopnc会员修改同时wirdoress会员修改----------------------
if($_POST['Action']=='Update') {
	$user_sessionname	= $_SESSION['username'];
	$user_username		= trim($_POST['username']);
	$username 			= '';
	$username			= empty($user_sessionname)?$user_username:$user_sessionname;
	$Sql = "UPDATE `{$INFO[DBPrefix]}users` SET user_email='".trim($_POST['email'])."' WHERE user_login='".$username."'";
	$DB->query($Sql);
}

//------------------------shopnc会员修改密码同时修改wordpress会员密码
if ($_POST['Action']=='ChangePwd') {
	$Sql = "UPDATE `{$INFO[DBPrefix]}users` SET user_pass='".$New_pw."' WHERE user_login='".$_SESSION['username']."'";
	$DB->query($Sql);
}
//-------------------------wordpress会员修改同时修改shopnc会员资料-------------------
if($_POST['from'] == 'profile') {
	$ps = '';
	if(!empty($_POST['pass1']) and !empty($_POST['pass2']) and trim($_POST['pass2']) == trim($_POST['pass1'])) {
		$ps			= ",password='".md5(trim($_POST['pass2']))."'";
		$adminps	= md5(trim($_POST['pass2']));
	}
	
	$Sql = '';
	if($_POST['action'] == 'update') {
		if($_POST['user_id'] ==1) {
			$Sql = "UPDATE `{$INFO[DBPrefix]}administrator` SET pw='".$adminps."' WHERE sa_id='".$_SESSION['sa_id']."'";
		} else {
			$user_info = $DB->fetch_array($DB->query("select * from `{$INFO[DBPrefix]}users` where ID='".$_POST['user_id']."'"));
			$Sql = "UPDATE `{$INFO[DBPrefix]}user` SET email='".trim( $_POST['email'] )."'".$ps." WHERE username='".$user_info['user_login']."'";
			$DB->query($Sql);
		}
	} else {
		$Sql = "UPDATE `{$INFO[DBPrefix]}user` SET email='".trim( $_POST['email'] )."'".$ps." WHERE user_id='".$_SESSION['user_id']."'";
	}
	$DB->query($Sql);
}

//-----------------------shopnc管理员修改密码---------------
if ($_POST['Action']=='Modi') {
	$Sql = "UPDATE `{$INFO[DBPrefix]}users` SET user_pass='".$New_pw."' WHERE user_login='".$_SESSION['Admin_Sa']."'";
	$DB->query($Sql);
}

//-----------------------wordpress添加用户，同时在shopnc里加入改用户-------------------------
if($action == 'add-user' || $_POST['action'] == 'adduser') {
	$db_string = $DB->compile_db_insert_string( array (
	'username'          => $FUNCTIONS->smartshophtmlspecialchars($_POST['user_login']),
	'password'          => md5($FUNCTIONS->smartshophtmlspecialchars($_POST['pass1'])),
	'true_name'         => $FUNCTIONS->smartshophtmlspecialchars($_POST['user_login']),
	'email'             => $FUNCTIONS->smartshophtmlspecialchars($_POST['email']),
	'reg_date'          => date("Y/m/d",time()),
	'reg_ip'            => $FUNCTIONS->getip(),
	'user_level'        => intval($_POST['user_level']),
	'user_state'        => intval($_POST['user_state']),
	'vloid'             => intval($_POST['vloid']),
	'sex'               => '0',
	'member_point'      => intval($FUNCTIONS->smartshophtmlspecialchars($_POST['member_point']))

	)      );

	$Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);
}

//----------------------------wordpress删除用户，同时shopnc里删除用户-------------
if($action == 'dodelete') {
	if(is_array($_POST['users'])) {
		foreach($_POST['users'] as $shopuser_id) {
		$user_info = $DB->fetch_array($DB->query("select * from `{$INFO[DBPrefix]}users` where ID='".$shopuser_id."'"));
		$Sql = "delete from `{$INFO[DBPrefix]}user` WHERE username='".$user_info['user_login']."'";
		$DB->query($Sql);
		}
	} else {
		$user_info = $DB->fetch_array($DB->query("select * from `{$INFO[DBPrefix]}users` where ID='".$id."'"));
		$Sql = "delete from `{$INFO[DBPrefix]}user` WHERE username='".$user_info['user_login']."'";
		$DB->query($Sql);
	}
}

if($_POST['act'] == 'Del') {
	for ($i=0;$i<$Num_bid;$i++){
	$user_info = $DB->fetch_array($DB->query("select * from `{$INFO[DBPrefix]}user`  where user_id='".intval($Array_bid[$i])."'"));
	$Sql = "delete from `{$INFO[DBPrefix]}users` WHERE user_login='".$user_info['username']."'";
	$DB->query($Sql);	
	}
}
?>