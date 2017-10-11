<?php
@ob_start();
include_once "Check_Admin.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");
$i=0;
$handle = fopen ($_FILES['cvsEXCEL']['tmp_name'],"r");
while ($datastr = fgets ($handle, 1024)) {
	$datastr = ($datastr);
	$data = explode(",",$datastr);
	//echo $i;
	if ($i>0){
		$sql = "select * from `{$INFO[DBPrefix]}goods` where bn = '" . $data[0] . "'";
		$Query_goods    = $DB->query($sql);
		$Num_trans      = $DB->num_rows($Query_goods);
		if ($Num_trans > 0){
			$Rs = $DB->fetch_array($Query_goods);
			$Result = $DB->query("update `{$INFO[DBPrefix]}goods` set storage='" . $data[1] . "' where bn = '" . $data[0] . "'");	
		}else{
				$goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where goodsno='" . trim($data[0]) . "' ";
				$goods_Query =  $DB->query($goods_Sql);
				 $goods_Num   =  $DB->num_rows($goods_Query );
				if ($goods_Num>0){
					$Rs_g = $DB->fetch_array($goods_Query);
					$size_Sql = "select * from `{$INFO[DBPrefix]}storage` where size='" . trim($Rs_g['size'])  . "' and color='" . trim($Rs_g['color']) . "' and goods_id='" . trim($Rs_g['gid']) . "'";
					$size_Query =  $DB->query($size_Sql);
					$size_Num   =  $DB->num_rows($size_Query );
					if ($size_Num>0){
						$update_Sql = "update `{$INFO[DBPrefix]}storage` set storage='" . $data[1] . "' where size='" . trim($Rs_g['size']) . "' and color='" . trim($Rs_g['color']) . "' and goods_id='" . trim($Rs_g['gid']) . "'";
						$Result = $DB->query($update_Sql);
					}else{
						$update_Sql = "insert into `{$INFO[DBPrefix]}storage` (color,size,goods_id,storage) values ('" . trim($Rs_g['color']) . "','" . trim($Rs_g['size']) . "','" . trim($Rs_g['gid']) . "','" . $data[1] ."')";
						$Result = $DB->query($update_Sql);
					}
					$size_Sql = "select sum(storage) as totalstorage from `{$INFO[DBPrefix]}storage` where goods_id='" .  trim($Rs_g['gid']) . "'";
					$size_Query =  $DB->query($size_Sql);
					$size_Num   =  $DB->num_rows($size_Query );
					if ($size_Num>0){
						$size_Rs = $DB->fetch_array($size_Query);
						$update_Sql_g = "update `{$INFO[DBPrefix]}goods` set storage='" . $size_Rs['totalstorage'] . "' where gid='" . trim($Rs_g['gid']) . "'";
						$DB->query($update_Sql_g);
					}
					
				}
			
			
			
		}
	}
	$i++;
}
fclose ($handle);
@header("location:admin_goods_list.php");

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