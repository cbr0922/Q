<?php
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
	function checkLogin(){
		if ($_SESSION['user_id']=="" || empty($_SESSION['user_id'])){
			@header("location:login_windows.php");
		}	
	}
	
	/**
	判斷是否存在
	**/
	function checkExist($type){
		global $DB,$INFO;
		switch($type){
			case "username":	
				$subsql = " username='".trim($_GET['username'])."'";
				break;
			case "email":	
				$subsql = " email='".trim($_GET['email'])."'";
				break;
			default:
				$subsql = " username='".trim($_GET['username'])."'";
		}
		$Sql = "select username from `{$INFO[DBPrefix]}user`  where " . $subsql . " limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num>0){
			return true;
		}else{
			return false;
		}
	}
}
?>