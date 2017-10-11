<?php
include_once "Check_Admin.php";
if ($_GET['action']=="get"){
	$gid = intval($_GET['gid']);
	$detail_id = intval($_GET['detail_id']);
	$goodstype = intval($_GET['goodstype']);
	$color = $_GET['color'];
	$size = $_GET['size'];

	if ($goodstype==1){
		$goods_array['sales'] = 0;
		$goods_Sql = "select * from `{$INFO[DBPrefix]}goods` where gid='" . $gid . "'";
		$goods_Query =  $DB->query($goods_Sql);
		$goods_Num   =  $DB->num_rows($goods_Query );
		if ($goods_Num>0){
			$goods_Rs = $DB->fetch_array($goods_Query);
			$goods_array['storage'] = intval($goods_Rs['storage']);
			$goods_array['sales'] += intval($goods_Rs['sales']);
		}
		$size_Sql = "select * from `{$INFO[DBPrefix]}storage` where goods_id='" . $gid . "' and (color!='' or size!='')";
		$size_Query =  $DB->query($size_Sql);
		$size_Num   =  $DB->num_rows($size_Query );
		if ($size_Num>0){
			while ($size_Rs = $DB->fetch_array($size_Query)) {
				$goods_array['storage'] = intval($size_Rs['storage']);
				$goods_array['sales'] += intval($size_Rs['sales']);
			}
		}
		$detail_Sql   =  "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $gid . "'";
		$detail_Query =  $DB->query($detail_Sql);
		$detail_Num   =  $DB->num_rows($detail_Query );
		if ($detail_Num>0){
			while ($detail_Rs = $DB->fetch_array($detail_Query)) {
				$goods_array['storage'] = intval($detail_Rs['storage']);
				$goods_array['sales'] += intval($detail_Rs['sales']);
			}
		}
		echo json_encode($goods_array);
	}elseif ($goodstype==2){
		if ($size!="" || $color!=""){
			$size_Sql = "select * from `{$INFO[DBPrefix]}storage` where size='" . $size . "' and color='" . $color . "' and goods_id='" . $gid . "'";
			$size_Query =  $DB->query($size_Sql);
			$size_Num   =  $DB->num_rows($size_Query );
			if ($size_Num>0){
				$size_Rs = $DB->fetch_array($size_Query);
				$goods_array['storage'] = intval($size_Rs['storage']);
				$goods_array['sales'] = intval($size_Rs['sales']);
				echo json_encode($goods_array);
			}
		}else{
			$goods_array['storage'] = 0;
			$goods_array['sales'] = 0;
			echo json_encode($goods_array);
		}
	}elseif ($goodstype==3){
		if ($detail_id>0){
			$detail_Sql   =  "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $gid . "' and detail_id='" . $detail_id . "'";
			$detail_Query =  $DB->query($detail_Sql);
			$detail_Num   =  $DB->num_rows($detail_Query );
			if ($detail_Num>0){
				$detail_Rs = $DB->fetch_array($detail_Query);
				$goods_array['storage'] = intval($detail_Rs['storage']);
				$goods_array['sales'] = intval($detail_Rs['sales']);
				echo json_encode($goods_array);
			}
		}
	}else{
		$goods_array['storage'] = 0;
		$goods_array['sales'] = 0;
		echo json_encode($goods_array);
	}
}elseif($_POST['action']=="save"){
	//print_r($_POST);
	$gid = intval($_POST['gid']);
	$detail_id = intval($_POST['detail_id']);
	$count = intval($_POST['count']);
	$sales = intval($_POST['sales']);
	$storagetype = intval($_POST['storagetype']);
	$goodstype = intval($_POST['goodstype']);
	$color = $_POST['color'];
	$size = $_POST['size'];
	$content = $_POST['content'];
	if ($goodstype==1){
		$detail_id=0;
		$size="";
		$color="";
	}elseif ($goodstype==2){
		$detail_id=0;
	}elseif ($goodstype==3){
		$size="";
		$color="";
	}
	$FUNCTIONS->setStorage($count,$storagetype,$gid,$detail_id,$size,$color,$content,$sales);
	echo 1;
}

?>
