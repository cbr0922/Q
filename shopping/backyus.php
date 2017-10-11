<?php
include("../configs.inc.php");
$req = 'cmd=_notify-validate';
if(is_array($_POST)){
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
}
if(is_array($_GET)){

foreach ($_GET as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
}
$Creatfile ="log/".date("Ymd")."-" . time() . "-ys";
$fh = fopen( $Creatfile.'.txt', 'w+' );
@chmod ($Creatfile.'.txt',0777);
fputs ($fh, $req, strlen($req) );
fclose($fh);
$data = $_POST['data'];
 //$data = urldecode("20151021%2C%E9%8A%80%E8%A1%8C%2C3%2C95672102819646%2C1%2C20151021163532%2C016394001858901041021CR%2B00000000000115%2B00000000000410%EF%BC%A1%EF%BC%B4%EF%BC%AD%E8%B7%A8%E8%A1%8C%E8%BD%89102819646+00003822000045154010535795672B82201041021163532");
$v = $data;
//$data_array = explode("\r\n",$data);
//foreach($data_array as $k=>$v){
//foreach ($_POST as $k => $v) {
	$field = explode(",",$v);	
	if(trim($field[3]!="")){
		$sql = "select * from `{$INFO[DBPrefix]}order_table` where atm like '" . $field[3] . "%'";
	  $Query_goods    = $DB->query($sql);
	   $Num_trans      = $DB->num_rows($Query_goods);
	  if ($Num_trans > 0){
		  $Rs_d=$DB->fetch_array($Query_goods);
		  $atm = $field[3];
		  $ruzhang = $field[4];
		  $paytime = substr($field[5],0,8);
		 // $year = intval(substr($paytime,0,4))+1911;
		  //$paytime = $year . substr($paytime,4);//exit;
		 
		  	$Result = $DB->query("update `{$INFO[DBPrefix]}order_table` set pay_state='1',paytime='" . $paytime . "',ruzhang='" . $ruzhang . "' where atm = '" . $Rs_d['atm'] . "'");	
		 	 $Result = $DB->query("update `{$INFO[DBPrefix]}order_detail` set detail_pay_state='1' where order_id = '" . $Rs_d['order_id'] . "'");
		 
	  }
	}
//}
echo "OK";exit;
?>