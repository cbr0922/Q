<?php
include_once "Check_Admin.php";
$Sql = "select count(*),memberno from `{$INFO[DBPrefix]}user` group by memberno having count(*)>1";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
echo $Num . "筆重複";
while($Rs=$DB->fetch_array($Query)){
	$i = 0;
	$reSql = "select * from `{$INFO[DBPrefix]}user` where memberno='" . $Rs['memberno'] . "'";
	$reQuery    = $DB->query($reSql);
	while($reRs=$DB->fetch_array($reQuery)){
		if($i>0){
			//echo $reRs['Country'];
			$Query_country = $DB->query("select membercode from `{$INFO[DBPrefix]}area` where areaname='" . trim($reRs['Country']) . "' and top_id=0");
			$Rs_country = $DB->fetch_array($Query_country);
			$firstcode = $Rs_country['membercode'];
			if($firstcode=="")
				$firstcode="A";
			$memberno = $FUNCTIONS->setMemberCode($firstcode); 
			$uSql = "update `{$INFO[DBPrefix]}user` set memberno='" . $memberno . "' where user_id='" . $reRs['user_id'] . "'";
			$DB->query($uSql);
			echo "會員" . $reRs['user_id'] . "重複編號" . $Rs['memberno'] . "新編號" . $memberno . "完成<br>";
		}
		$i++;
	}
}
?>