<?php
$menutype = "category";//all左側菜單顯示所有分類級別，category只顯示當前館別分類（即從第二級別分類開始顯示）
echo $bid;
echo $top_bid = $PRODUCT->getTopBid($bid);   //得到對應的第一級分類ID
if($menutype == "all")
	$showbid = 0;
else
	$showbid = $top_bid;
$class_array = $PRODUCT->getProductClass($showbid);  //分類，最多顯示3層
$tpl->assign("ProductListAll",  $class_array);
?>

