<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");

include ( dirname(__FILE__)."/../../configs.inc.php");
include ("global.php");
include RootDocument."/language/".$INFO['IS']."/Good.php";
include ("PageNav.class.php");

$Sql     = "select g.gid,g.goodsname,g.price,g.bn,g.smallimg,g.intro,g.bonusnum,g.point from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid ) where  b.catiffb=1 and g.ifpub=1 and g.ifbonus=1 and g.ifjs!=1 order by idate desc";
$PageNav = new PageItem($Sql,8);
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;
if ( $Num >0 ) {
 $i=0;
 while ( $ProNav = $DB->fetch_array($arrRecords)){
 $ProNav_Rs[$i]['gid']        = intval($ProNav['gid']) ;
 $ProNav_Rs[$i]['goodsname']  = $ProNav['goodsname'] ;
 $ProNav_Rs[$i]['price']      = $ProNav['price'] ;
 $ProNav_Rs[$i]['bn']         = $ProNav['bn'] ;
 $ProNav_Rs[$i]['smallimg']   = $ProNav['smallimg'] ;
 $ProNav_Rs[$i]['intro']      = $ProNav['intro'];
 $ProNav_Rs[$i]['bonusnum']   = $ProNav['bonusnum'];
 $ProNav_Rs[$i]['point']      = $ProNav['point'];
 $i++;
 }
}
$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条


//下边将输出产品资料

if ($Num>0){
//第一个产品的资料
 if (intval($ProNav_Rs[0]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid1",  $ProNav_Rs[0]['gid']); //最新商品一ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[0]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level1[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level1[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level1", $ProNav_array_level1);       //商品一会员价格数组
   $tpl->assign("ProNav_goodsname1",   $ProNav_Rs[0]['goodsname']); //商品一名称
   $tpl->assign("ProNav_price1",       $ProNav_Rs[0]['price']);     //商品一价格
   $tpl->assign("ProNav_bn1",          $ProNav_Rs[0]['bn']);        //商品一编号
   $tpl->assign("ProNav_img1",         $ProNav_Rs[0]['smallimg']);  //商品一图片
   $tpl->assign("ProNav_intro1",       $ProNav_Rs[0]['intro']);     //商品一内容	
   $tpl->assign("ProNav_bonusnum1",    $ProNav_Rs[0]['bonusnum']);  //商品一所需要积分	
   $tpl->assign("ProNav_point1",       $ProNav_Rs[0]['point']);     //商品一积分	
  }
  unset($Sql_level);
  unset($Query_level);



//第二个产品的资料
 if (intval($ProNav_Rs[1]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid2",  $ProNav_Rs[1]['gid']); //最新商品二ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[1]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level2[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level2[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level2", $ProNav_array_level2);       //商品二会员价格数组
   $tpl->assign("ProNav_goodsname2",   $ProNav_Rs[1]['goodsname']); //商品二名称
   $tpl->assign("ProNav_price2",       $ProNav_Rs[1]['price']);     //商品二价格
   $tpl->assign("ProNav_bn2",          $ProNav_Rs[1]['bn']);        //商品二编号
   $tpl->assign("ProNav_img2",         $ProNav_Rs[1]['smallimg']);  //商品二图片
   $tpl->assign("ProNav_intro2",       $ProNav_Rs[1]['intro']);     //商品二内容	
   $tpl->assign("ProNav_bonusnum2",    $ProNav_Rs[1]['bonusnum']);  //商品二所需要积分	
   $tpl->assign("ProNav_point2",       $ProNav_Rs[1]['point']);     //商品二积分	
  }
  unset($Sql_level);
  unset($Query_level);
  

//第三个产品的资料
 if (intval($ProNav_Rs[2]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid3",  $ProNav_Rs[2]['gid']); //最新商品三ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[2]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level3[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level3[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level3", $ProNav_array_level3);       //商品三会员价格数组
   $tpl->assign("ProNav_goodsname3",   $ProNav_Rs[2]['goodsname']); //商品三名称
   $tpl->assign("ProNav_price3",       $ProNav_Rs[2]['price']);     //商品三价格
   $tpl->assign("ProNav_bn3",          $ProNav_Rs[2]['bn']);        //商品三编号
   $tpl->assign("ProNav_img3",         $ProNav_Rs[2]['smallimg']);  //商品三图片
   $tpl->assign("ProNav_intro3",       $ProNav_Rs[2]['intro']);     //商品三内容	
   $tpl->assign("ProNav_bonusnum3",    $ProNav_Rs[2]['bonusnum']);  //商品三所需要积分	
   $tpl->assign("ProNav_point3",       $ProNav_Rs[2]['point']);     //商品三积分	   
  }
  unset($Sql_level);
  unset($Query_level);  
  


//第四个产品的资料
 if (intval($ProNav_Rs[3]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid4",  $ProNav_Rs[3]['gid']); //最新商品四ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[3]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level4[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level4[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level4", $ProNav_array_level4);       //商品四会员价格数组
   $tpl->assign("ProNav_goodsname4",   $ProNav_Rs[3]['goodsname']); //商品四名称
   $tpl->assign("ProNav_price4",       $ProNav_Rs[3]['price']);     //商品四价格
   $tpl->assign("ProNav_bn4",          $ProNav_Rs[3]['bn']);        //商品四编号
   $tpl->assign("ProNav_img4",         $ProNav_Rs[3]['smallimg']);  //商品四图片
   $tpl->assign("ProNav_intro4",       $ProNav_Rs[3]['intro']);     //商品四内容		
   $tpl->assign("ProNav_bonusnum4",    $ProNav_Rs[3]['bonusnum']);  //商品四所需要积分	
   $tpl->assign("ProNav_point4",       $ProNav_Rs[3]['point']);     //商品四积分	   
  }
  unset($Sql_level);
  unset($Query_level);    

//第五个产品的资料
 if (intval($ProNav_Rs[4]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid5",  $ProNav_Rs[4]['gid']); //最新商品五ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[4]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level5[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level5[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level5", $ProNav_array_level5);       //商品五会员价格数组
   $tpl->assign("ProNav_goodsname5",   $ProNav_Rs[4]['goodsname']); //商品五名称
   $tpl->assign("ProNav_price5",       $ProNav_Rs[4]['price']);     //商品五价格
   $tpl->assign("ProNav_bn5",          $ProNav_Rs[4]['bn']);        //商品五编号
   $tpl->assign("ProNav_img5",         $ProNav_Rs[4]['smallimg']);  //商品五图片
   $tpl->assign("ProNav_intro5",       $ProNav_Rs[4]['intro']);     //商品五内容		
   $tpl->assign("ProNav_bonusnum5",    $ProNav_Rs[4]['bonusnum']);  //商品五所需要积分	
   $tpl->assign("ProNav_point5",       $ProNav_Rs[4]['point']);     //商品五积分	   
  }
  unset($Sql_level);
  unset($Query_level);   

  

//第六个产品的资料
 if (intval($ProNav_Rs[5]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid6",  $ProNav_Rs[5]['gid']); //最新商品六ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[5]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level6[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level6[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level6", $ProNav_array_level6);       //商品六会员价格数组
   $tpl->assign("ProNav_goodsname6",   $ProNav_Rs[5]['goodsname']); //商品六名称
   $tpl->assign("ProNav_price6",       $ProNav_Rs[5]['price']);     //商品六价格
   $tpl->assign("ProNav_bn6",          $ProNav_Rs[5]['bn']);        //商品六编号
   $tpl->assign("ProNav_img6",         $ProNav_Rs[5]['smallimg']);  //商品六图片
   $tpl->assign("ProNav_intro6",       $ProNav_Rs[5]['intro']);     //商品六内容		
   $tpl->assign("ProNav_bonusnum6",    $ProNav_Rs[5]['bonusnum']);  //商品六所需要积分	
   $tpl->assign("ProNav_point6",       $ProNav_Rs[5]['point']);     //商品六积分	   
  }
  unset($Sql_level);
  unset($Query_level);  
  

//第七个产品的资料
 if (intval($ProNav_Rs[6]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid7",  $ProNav_Rs[6]['gid']); //最新商品七ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[6]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level7[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level7[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level7", $ProNav_array_level7);       //商品七会员价格数组
   $tpl->assign("ProNav_goodsname7",   $ProNav_Rs[6]['goodsname']); //商品七名称
   $tpl->assign("ProNav_price7",       $ProNav_Rs[6]['price']);     //商品七价格
   $tpl->assign("ProNav_bn7",          $ProNav_Rs[6]['bn']);        //商品七编号
   $tpl->assign("ProNav_img7",         $ProNav_Rs[6]['smallimg']);  //商品七图片
   $tpl->assign("ProNav_intro7",       $ProNav_Rs[6]['intro']);     //商品七内容	
   $tpl->assign("ProNav_bonusnum7",    $ProNav_Rs[6]['bonusnum']);  //商品七所需要积分	
   $tpl->assign("ProNav_point7",       $ProNav_Rs[6]['point']);     //商品七积分	   
  }
  unset($Sql_level);
  unset($Query_level);  


//第八个产品的资料
 if (intval($ProNav_Rs[7]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid8",  $ProNav_Rs[7]['gid']); //最新商品八ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[7]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level8[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level8[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level8", $ProNav_array_level8);       //商品八会员价格数组
   $tpl->assign("ProNav_goodsname8",   $ProNav_Rs[7]['goodsname']); //商品八名称
   $tpl->assign("ProNav_price8",       $ProNav_Rs[7]['price']);     //商品八价格
   $tpl->assign("ProNav_bn8",          $ProNav_Rs[7]['bn']);        //商品八编号
   $tpl->assign("ProNav_img8",         $ProNav_Rs[7]['smallimg']);  //商品八图片
   $tpl->assign("ProNav_intro8",       $ProNav_Rs[7]['intro']);     //商品八内容	
   $tpl->assign("ProNav_bonusnum8",    $ProNav_Rs[7]['bonusnum']);  //商品八所需要积分	
   $tpl->assign("ProNav_point8",       $ProNav_Rs[7]['point']);     //商品八积分	   
  }
  unset($Sql_level);
  unset($Query_level);  



//第九个产品的资料
 if (intval($ProNav_Rs[8]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid9",  $ProNav_Rs[8]['gid']); //最新商品九ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[8]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level9[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level9[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level9", $ProNav_array_level9);       //商品九会员价格数组
   $tpl->assign("ProNav_goodsname9",   $ProNav_Rs[8]['goodsname']); //商品九名称
   $tpl->assign("ProNav_price9",       $ProNav_Rs[8]['price']);     //商品九价格
   $tpl->assign("ProNav_bn9",          $ProNav_Rs[8]['bn']);        //商品九编号
   $tpl->assign("ProNav_img9",         $ProNav_Rs[8]['smallimg']);  //商品九图片
   $tpl->assign("ProNav_intro9",       $ProNav_Rs[8]['intro']);     //商品九内容		
   $tpl->assign("ProNav_bonusnum9",    $ProNav_Rs[8]['bonusnum']);  //商品九所需要积分	
   $tpl->assign("ProNav_point9",       $ProNav_Rs[8]['point']);     //商品九积分	   
  }
  unset($Sql_level);
  unset($Query_level);    



//第十个产品的资料
 if (intval($ProNav_Rs[9]['gid'])> 0 ){       
  $tpl->assign("ProNav_gid10",  $ProNav_Rs[9]['gid']); //最新商品十ID
  $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".$ProNav_Rs[9]['gid'];   
	$Query_level = $DB->query($Sql_level);
	 $j=0;
	 while ($Result_level=$DB->fetch_array($Query_level)){
	 	 if (intval($Result_level['m_price'])!=0){
	    $ProNav_array_level10[$j]['level_name'] = $Result_level['level_name'];
      $ProNav_array_level10[$j]['m_price']    = $Result_level['m_price'];      
      $j++;
     }
	 }	
   $tpl->assign("ProNav_array_level10", $ProNav_array_level9);       //商品十会员价格数组
   $tpl->assign("ProNav_goodsname10",   $ProNav_Rs[9]['goodsname']); //商品十名称
   $tpl->assign("ProNav_price10",       $ProNav_Rs[9]['price']);     //商品十价格
   $tpl->assign("ProNav_bn10",          $ProNav_Rs[9]['bn']);        //商品十编号
   $tpl->assign("ProNav_img10",         $ProNav_Rs[9]['smallimg']);  //商品十图片
   $tpl->assign("ProNav_intro10",       $ProNav_Rs[9]['intro']);     //商品十内容		
   $tpl->assign("ProNav_bonusnum10",    $ProNav_Rs[9]['bonusnum']);  //商品十所需要积分	
   $tpl->assign("ProNav_point10",       $ProNav_Rs[9]['point']);     //商品十积分	   
  }
  unset($Sql_level);
  unset($Query_level);   

} 


$tpl->assign($Good);

$tpl->display("bonus_index.html");
?>




