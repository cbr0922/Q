<?php
include_once "Check_Admin.php";
//$_POST['action']="save";
if ($_GET['action']=="get"){
	$gid = intval($_GET['gid']);
	$color = $_GET['color'];
	$size = $_GET['size'];
	$goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where gid='" . $gid . "' and size='" . $size . "' and color='" . $color . "'";
	$goods_Query =  $DB->query($goods_Sql);
	$goods_Num   =  $DB->num_rows($goods_Query );
	if ($goods_Num>0){
		$goods_Rs = $DB->fetch_array($goods_Query);
		$storage_array['anid'] = ($goods_Rs['anid']);
		$storage_array['goodsno'] = ($goods_Rs['goodsno']);
		$storage_array['guojima'] = ($goods_Rs['guojima']);
		$storage_array['orgno'] = ($goods_Rs['orgno']);
		echo json_encode($storage_array);
	}
}elseif($_POST['action']=="save"){
	//print_r($_POST);
	$gid = intval($_POST['gid']);
	$anid = intval($_POST['anid']);
	$attribno = $_POST['attribno'];
	$color = $_POST['color'];
	$size = $_POST['size'];

	/*$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where bn='" . $attribno . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		echo "0";
		exit;
	}*/

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}attributeno` where anid!='".$anid."' and goodsno='" . $attribno . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		echo "0";
		exit;
	}

	/*$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods_detail` where detail_bn='" . $attribno . "' limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num){
		echo "0";
		exit;
	}*/

	$goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where gid='" . $gid . "' and size='" . $size . "' and color='" . $color . "'";
	$goods_Query =  $DB->query($goods_Sql);
	$goods_Num   =  $DB->num_rows($goods_Query );
	if ($goods_Num>0){
		$Sql = "update `{$INFO[DBPrefix]}attributeno` set goodsno='" . $attribno . "',guojima='" . $_POST['guojima'] . "',orgno='" . $_POST['orgno'] . "' where gid='" . $gid . "' and size='" . $size . "' and color='" . $color . "'";
	}else{
		$Sql = "insert into `{$INFO[DBPrefix]}attributeno`(gid,size,color,goodsno,guojima,orgno)values('" . $gid . "','" . $size . "','" . $color . "','" . $attribno . "','" . $_POST['guojima'] . "','" . $_POST['orgno'] . "')";
	}

	$DB->query($Sql);
	echo "1";
}
?>
