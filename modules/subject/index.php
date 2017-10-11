<?php
@header("Content-type: text/html; charset=utf-8");
include("../../configs.inc.php");
include RootDocument."/language/".$INFO['IS']."/Good.php";
include("global.php");

$Sql_sub   = " select subject_name,subject_id from `{$INFO[DBPrefix]}subject` where subject_open=1 order by subject_num desc ";
$Query_sub = $DB->query($Sql_sub);
$Array_sub = array();
$sub_i = 0;
while ($Rs_sub = $DB->fetch_array($Query_sub) ){
	$Array_sub[$sub_i][subject_id]    =  $Rs_sub['subject_id'];
	$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
	$sub_i++;
}

$tpl->assign("Array_sub",       $Array_sub);           //主题类别名称循环

/*
+--------------------------------------------------------------------------------------------------------------
+                      这里是主题产品的内容                                                                  --
+--------------------------------------------------------------------------------------------------------------
*/

$Subject_id = intval($_GET['subject_id'])>0 ? intval($_GET['subject_id']) : intval($INFO[subject_id]);



$Query = $DB->query("select subject_name,subject_content  from  `{$INFO[DBPrefix]}subject`  where subject_id='".$Subject_id."'  limit 0,1");

while ($Rs    = $DB->fetch_array($Query) ){
	$tpl->assign("Subject_name",          $Rs['subject_name']);              //主题名字
	$tpl->assign("Subject_content",       $Rs['subject_content']);           //主题内容
}

include("PageNav.class.php");

//$Sql     = "select b.attr,g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.goodattr,s.subject_name,s.subject_content from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid ) inner join `{$INFO[DBPrefix]}subject` s on (g.subject_id=s.subject_id)  where s.subject_open=1 and  b.catiffb=1 and g.ifpub=1 and s.subject_id='".$Subject_id."'  order by g.idate desc";

$Sql = "select * from `{$INFO[DBPrefix]}goods` g  inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid ) where (g.subject_id like '".$Subject_id."' or  g.subject_id like '%,".$Subject_id.",%' or g.subject_id like '%,".$Subject_id."' or g.subject_id like '".$Subject_id.",%') and b.catiffb=1 and g.ifpub=1 and g.ifjs!=1 and g.ifbonus!=1  order by idate desc ";

$PageNav = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;
if ($Num>0){
	$i=0;
	$j=1;
	while ( $ProNav = $DB->fetch_array($arrRecords)){

		$Sql_level   = "select  m.m_price,u.level_name,m.m_price  from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (u.level_id=m.m_level_id) where m.m_goods_id=".intval($ProNav['gid']);
		$Query_level = $DB->query($Sql_level);
		$k=0;
		$ProNav_array_level = array();
		while ($Result_level=$DB->fetch_array($Query_level)){
			if (intval($Result_level['m_price'])!=0){
				$ProNav_array_level[$k]['level_name'] = $Result_level['level_name'];
				$ProNav_array_level[$k]['m_price']    = $Result_level['m_price'];
				$k++;
			}
		}
		$tpl->assign("ProNav_array_level".$j, $ProNav_array_level);       //会员价格数组

		$tpl->assign("ProNav_gid".$j,  intval($ProNav['gid'])); //商品ID

		$tpl->assign("ProNav_goodsname".$j,   $ProNav['goodsname']); //商品名称
		$tpl->assign("ProNav_price".$j,       $ProNav['price']);     //商品价格
		$tpl->assign("ProNav_pricedesc".$j,   $ProNav['pricedesc']); //商品价格
		$tpl->assign("ProNav_bn".$j,          $ProNav['bn']);        //商品编号
		$tpl->assign("ProNav_img".$j,         $ProNav['smallimg']);  //商品图片
		$tpl->assign("ProNav_intro".$j,       $ProNav['intro']);     //商品内容


		if ($ProNav['attr']!=""){
			$attrI        =  $ProNav['attr'];
			$goods_attrI  =  $ProNav['goodattr'];
			$Attr         =  explode(',',$attrI);
			$Goods_Attr   =  explode(',',$goods_attrI);
			$Attr_num=  count($Attr);
		}else{
			$Attr_num=0;
		}
		if (is_array($Attr) && intval($Attr_num)>0 ){
			$AttrArray = array();
			$ProductArray = array();
			for($k=0;$k<$Attr_num;$k++){
				$tpl->assign("ProductAttrArray_".$j."_attriName_".$k,     $Attr[$k]);    //循环多属性部分数组
				$tpl->assign("ProductAttrArray_".$j."_attriValue_".$k,    $Goods_Attr[$k]);    //循环多属性部分数组
			}
		}



		$i++;
		$j++;
	}
}
$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条






$tpl->assign($Good);
$tpl->display("subject_index.html");

?>
