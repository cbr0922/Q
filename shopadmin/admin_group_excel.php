<?php
@ob_start();
//session_start();
include "Check_Admin.php";
include      "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include_once 'crypt.class.php';
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
if ($_POST[Action]=='Excel' && intval($_POST[mgroup_id])) {
	$mgroup_id =  intval($_POST[mgroup_id]);
	$Sql      = "select * from `{$INFO[DBPrefix]}mail_group` where mgroup_id='" . $mgroup_id . "' order by mgroup_id asc ";
	$Query    = $DB->query($Sql);
	$Rs=$DB->fetch_array($Query);
	$file_string = "";
	$file_string .= $Admin_Member[UserName].",".$Admin_Member[Email].",".$Admin_Member[TrueName].",".$Admin_Member[Sex].",".$Admin_Member[Born].",".$Admin_Member[Area].",".$Admin_Member[Address].",".$Admin_Member[Mobile].",".$Admin_Member[Phone]."\n";

	$file_string = iconv("UTF-8",$ToEncode,$file_string);
	if($Rs['searchlist']=="All"){
		 $Sql = "select * from `{$INFO[DBPrefix]}user` ";
	}elseif($Rs['searchlist']=="noDing"){
		$Sql = "select * from `{$INFO[DBPrefix]}user` where dianzibao=0";
	}else{
		$Sql      = "select m.*,u.* from `{$INFO[DBPrefix]}mail_group_list` as m left join `{$INFO[DBPrefix]}user` as u on u.user_id=m.user_id where group_id='" . intval($mgroup_id) . "' order by m.email asc ";
	}
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);


	if ($Num>0){
		while ($Rs_sub = $DB->fetch_array($Query)){
			$file_temp    = "";
			$Sex          = intval($Rs_sub[sex])==0 ?  $Admin_Member[Sex_men]  : $Admin_Member[Sex_women] ;
			$file_temp    = $Rs_sub[username].",".$Rs_sub[email].",".$Rs_sub[true_name].",".$Sex.",".$Rs_sub[born_date].",".$Rs_sub[area].",".$Rs_sub[addr].",".MD5Crypt::Decrypt ($Rs_sub[other_tel], $INFO['mcrypt']).",".MD5Crypt::Decrypt ($Rs_sub[tel],$INFO['tcrypt'])."\n";
			$file_string .= iconv("UTF-8",$ToEncode,$file_temp);
		}
	}


	echo $file_string;
	$Creatfile = iconv("UTF-8",$ToEncode,$mgroup_name)."_".date("Y-m-d");

	/**
	 * 这个部分是写一个本地文件，在目前这里是没有用的。临时保留
	 * 
	if ( $fh = fopen( $Creatfile.'.csv', 'w' ) )
	{
	fputs ($fh, $file_string, strlen($file_string) );
	fclose($fh);
	@chmod ($Creatfile.'.csv',0777);
	}
	*/

	@ob_implicit_flush(0);
	//@header("Content-Type: text/html;  charset=UTF-8; name=\"$Creatfile.csv\"");
	@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".csv\"");
	@header("Content-disposition: attachment;filename=\"".$Creatfile.".csv\"");
	@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".csv\"");
	//@header("location:".$Creatfile.'.csv');
}
?>
