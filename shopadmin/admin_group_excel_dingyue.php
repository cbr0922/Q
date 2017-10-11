<?php
@ob_start();
//session_start();
include "Check_Admin.php";
include      "../language/".$INFO['IS']."/Admin_Member_Pack.php";
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
	$file_string  = "";
	$file_string .=$Admin_Member[Email]."\n";

	$file_string = iconv("UTF-8",$ToEncode,$file_string);
	$Sql = " select mgroup_list,mgroup_name from `{$INFO[DBPrefix]}mail_group` where mgroup_id=".intval($mgroup_id)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);


	if ($Num>0){
		$Rs = $DB->fetch_array($Query);
		$mgroup_list = $Rs[mgroup_list];
		$mgroup_name = $Rs[mgroup_name];


		$Array_list = explode(",",trim($mgroup_list));
			foreach ($Array_list as $k =>$v ){
					$file_temp    = $v."\n";
					/*
					if($k=="tel")
						$file_string .= iconv("UTF-8",$ToEncode,MD5Crypt::Decrypt ($file_temp), $INFO['tcrypt']);
					elseif($k=="other_tel")
						$file_string .= iconv("UTF-8",$ToEncode,MD5Crypt::Decrypt ($file_temp), $INFO['tcrypt']);
					else
					*/
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
