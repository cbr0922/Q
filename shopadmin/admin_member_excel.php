<?php
@ob_start();
include_once "Check_Admin.php";
set_time_limit(0);
//include Classes . "/global.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");

$i=0;
$handle = fopen ($_FILES['cvsEXCEL']['tmp_name'],"r");
while ($datastr = fgets ($handle, 10240)) {
	 $datastr = ($datastr);
	$data = explode(",",$datastr);
	if ($i>0){
		$pwd = rand(100000, 999999);
		$sql = "select * from `{$INFO[DBPrefix]}user` where username = '" . trim($data[1]) . "'";
		$Query_goods    = $DB->query($sql);
		$Num_trans      = $DB->num_rows($Query_goods);
		if ($Num_trans <= 0){
			$levelname = trim($data[13]);
			
			$levelsql = "select id from `{$INFO[DBPrefix]}saler` where name='" . trim($data[34]) . "'";
			$Query_level    = $DB->query($levelsql);
			$Rs_level=$DB->fetch_array($Query_level);
			
			$companyid = intval($Rs_level['id']);
			
			
			$levelid = 1;
			
			if (trim($data[12])=="男")
				$sex = 0;
			else
				$sex = 1;
			
			//序號,帳號,密碼,真實姓名,性別,身份證號碼,出生日期,聯絡地址,郵件,傳真,電話,移動電話,積分點,會員等級
			//id	loginid	username	password	email	logins	lastLogin	logoutTime	enabled	address	phone	mobile	sex	birthday	groupId	createTime	remark	postid	job	cityname	localname	verifycode	暱稱	codeId	權限	epaper	紅利	updateTime	個人圖	上一層推薦人	countryId	會員代號	countryCode	推薦人數	單位
			
			$db_string = $DB->compile_db_insert_string( array (
			'username'          => $FUNCTIONS->smartshophtmlspecialchars($data[1]),
			'password'          => md5($FUNCTIONS->smartshophtmlspecialchars($data[3])),
			'true_name'         => $FUNCTIONS->smartshophtmlspecialchars($data[2]),
			'sex'               => $FUNCTIONS->smartshophtmlspecialchars($sex),
			'born_date'         => $FUNCTIONS->smartshophtmlspecialchars($data[13]),
			'email'             => $FUNCTIONS->smartshophtmlspecialchars($data[4]),
			'addr'              => $FUNCTIONS->smartshophtmlspecialchars($data[9]),
			'city'              => $FUNCTIONS->smartshophtmlspecialchars($data[20]),
			'canton'            => $FUNCTIONS->smartshophtmlspecialchars($data[19]),
			'Country'            => $FUNCTIONS->smartshophtmlspecialchars("台灣"),
			'fax'               => "",
			'post'              => $FUNCTIONS->smartshophtmlspecialchars($data[17]),
			'tel'               => $FUNCTIONS->smartshophtmlspecialchars($data[10]),
			'other_tel'         => $FUNCTIONS->smartshophtmlspecialchars($data[11]),
			'reg_date'          => date("Y/m/d",$data[15]),
			'reg_ip'            => $FUNCTIONS->getip(),
			'user_level'        => $levelid,
			'user_state'        => 0,
			'vloid'             => $data[23],
			'certcode'             => "",
			'companyid'             => intval($companyid),
			'member_point'      => 0,
			'advance'           => 0,
			'schoolname'             => "",
			'chenghu'             => "",
			'dianzibao'             => 1,
			'nickname'             => $data[22],
			'pic'            => $data[28],
			'msn'             => "",
			'blog'             => "",
			'memberno'         => trim($data[31]),
			'recommendno'         => trim($data[29]),
			)      );
		
			 $Sql="INSERT INTO `{$INFO[DBPrefix]}user` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
			$Result_Insert=$DB->query($Sql);
			$Insert_id = $DB->insert_id();
			$FUNCTIONS->AddBonuspoint(intval($Insert_id),intval($data[26]),6,"會員原有紅利" . $data[26],1,0);

			//$Result = $DB->query("insert into `{$INFO[DBPrefix]}user` (username,password,user_state,true_name,email,tel,other_tel,fax,addr,user_level,born_date,sex,certcode,member_point) values('" . trim($data[1]) . "','" . (trim($data[2])) . "',0,'" . trim($data[3]) . "','" . trim($data[8]) . "','" . trim($data[10]) . "','" . trim($data[11]) . "','" . trim($data[9]) . "','" . trim($data[7]) . "','" . $levelid . "','" . trim($data[6]) . "','" . $sex . "','" . trim($data[5]) . "','" . intval(trim($data[12])) . "')");
		}
	}
	$i++;
}
//exit;
fclose ($handle);
@header("location:admin_member_list.php");
function big52utf8($big5str) {

$blen = strlen($big5str);
$utf8str = "";

for($i=0; $i<$blen; $i++) {

$sbit = ord(substr($big5str, $i, 1));
//echo $sbit;
//echo "<br>";
if ($sbit < 129) {
$utf8str.=substr($big5str,$i,1);
}elseif ($sbit > 128 && $sbit < 255) {
$new_word = iconv("BIG5", "UTF-8", substr($big5str,$i,2));
$utf8str.=($new_word=="")?"?":$new_word;
$i++;
}
}

return $utf8str;

}
?>