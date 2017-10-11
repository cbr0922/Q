<?php
@ob_start();
//session_start();
include_once "Check_Admin.php";
//include_once Classes . "/global.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";


@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");

switch ($INFO['admin_IS']){
	case "gb":
		$ToEncode = "GB2312";
		break;
	case "en":
		$ToEncode = "GB2312";
		break;
	case "big5":
		$ToEncode = "BIG5";
		break;
	default:
		$ToEncode = "GB2312";
		break;
}

/**
 * 这里是处理输出EXCEL时候用的。
 * 用户名,E-mail地址,真实姓名,性别,出生日期,地区名称,联系地址,移动电话,固定电话
 */

$mgroup_id =  16;
$file_string = "";
$file_string .= $Admin_Member[UserName].",".$Admin_Member[Email].",".$Admin_Member[TrueName].",".$Admin_Member[Sex].",".$Admin_Member[Born].",".$Admin_Member[Area].",".$Admin_Member[Address].",".$Admin_Member[Mobile].",".$Admin_Member[Phone]."\n";

$file_string = iconv("UTF-8",$ToEncode,$file_string);
$Sql = " select mgroup_list,mgroup_name from `{$INFO[DBPrefix]}mail_group` where mgroup_id=".intval($mgroup_id)." limit 0,1";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);


if ($Num>0){
	$Rs = $DB->fetch_array($Query);
	$mgroup_list = $Rs[mgroup_list];
	$mgroup_name = $Rs[mgroup_name];


	if (trim($mgroup_list)=='all'){
		$Sql_sub = "select username,email,true_name,sex,born_date,CONCAT(city,' ',canton) as area,addr,other_tel,tel from `{$INFO[DBPrefix]}user` ";
		$Query_sub = $DB->query($Sql_sub);
		$Num_sub   = $DB->num_rows($Query_sub);


		while ($Rs_sub = $DB->fetch_array($Query_sub)){
			$file_temp    = "";
			$Sex          = intval($Rs_sub[sex])==0 ? $Admin_Member[Sex_men]  : $Admin_Member[Sex_women] ;
			$file_temp    = $Rs_sub[username].",".$Rs_sub[email].",".$Rs_sub[true_name].",".$Sex.",".$Rs_sub[born_date].",".$Rs_sub[area].",".$Rs_sub[addr].",".$Rs_sub[other_tel].",".$Rs_sub[tel]."\n";
			$file_string .= iconv("UTF-8",$ToEncode,$file_temp);
		}

	}else{
		$Array_list = explode(",",trim($mgroup_list));
		foreach ($Array_list as $k =>$v ){
			$Sql_sub = "select username,email,true_name,sex,born_date,CONCAT(city,' ',canton) as area,addr,other_tel,tel from `{$INFO[DBPrefix]}user` where email='".trim($v)."' limit 0,1";
			$Query_sub = $DB->query($Sql_sub);
			$Num_sub   = $DB->num_rows($Query_sub);
			if ($Num_sub>0){
				$file_temp    = "";
				$Rs_sub = $DB->fetch_array($Query_sub);
				$Sex = intval($Rs_sub[sex])==0 ? $Admin_Member[Sex_men]  : $Admin_Member[Sex_women] ;
				$file_temp    = $Rs_sub[username].",".$Rs_sub[email].",".$Rs_sub[true_name].",".$Sex.",".$Rs_sub[born_date].",".$Rs_sub[area].",".$Rs_sub[addr].",".$Rs_sub[other_tel].",".$Rs_sub[tel]."\n";
				$file_string .= iconv("UTF-8",$ToEncode,$file_temp);
			}
		}
	}
}


echo $file_string;
$Creatfile = iconv("UTF-8",$ToEncode,$mgroup_name)."_".date("Y-m-d");


@ob_implicit_flush(0);
//@header("Content-Type: text/html;  charset=UTF-8; name=\"$Creatfile.csv\"");
@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");
//@header("location:".$Creatfile.'.csv');

?>