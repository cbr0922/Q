<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");

$Sql_sub   = " select subject_name,subject_id from `{$INFO[DBPrefix]}sale_subject` where subject_open=1 order by subject_num desc ";
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



$Query = $DB->query("select subject_name,subject_content,salecount  from  `{$INFO[DBPrefix]}sale_subject`  where subject_id='".$Subject_id."'  limit 0,1");

while ($Rs    = $DB->fetch_array($Query) ){
	$tpl->assign("Subject_name",          $Rs['subject_name']);              //主题名字
	$tpl->assign("Subject_content",       $Rs['subject_content']);           //主题内容
	$tpl->assign("salecount",          $Rs['salecount']);
}

include("PageNav.class.php");

//$Sql     = "select b.attr,g.gid,g.goodsname,g.price,g.pricedesc,g.bn,g.smallimg,g.intro,g.goodattr,s.subject_name,s.subject_content from `{$INFO[DBPrefix]}goods` g inner join `{$INFO[DBPrefix]}bclass` b on (g.bid=b.bid ) inner join `{$INFO[DBPrefix]}subject` s on (g.subject_id=s.subject_id)  where s.subject_open=1 and  b.catiffb=1 and g.ifpub=1 and s.subject_id='".$Subject_id."'  order by g.idate desc";

$Sql = "select * from `{$INFO[DBPrefix]}goods` where ifsales=1 and ifpub=1 and sale_subject='".$Subject_id."' order by idate desc ";

$PageNav = new PageItem($Sql,intval($INFO['MaxProductNumForList']));
$arrRecords = $PageNav->ReadList();
$Num     = $PageNav->iTotal;
$sale_p_array = array();
if ($Num>0){
	$i=0;
	$j=1;
	while ( $ProNav = $DB->fetch_array($arrRecords)){
		$Good_color_array = array();
		$Good_Color_Option= "";
		$Good_size_array = array();
		$Good_Size_Option = "";
		$sale_p_array[$i][goodsname] = $ProNav['goodsname'];
		$sale_p_array[$i][bn] = $ProNav['bn'];
		$sale_p_array[$i][gid] = $ProNav['gid'];
		$sale_p_array[$i][pricedesc] = $ProNav['pricedesc'];
		$sale_p_array[$i][price] = $ProNav['price'];
		$sale_p_array[$i][storage] = $ProNav['storage'];
		$sale_p_array[$i][smallimg] = $ProNav['smallimg'];
		$sale_p_array[$i][sale_price] = $ProNav['sale_price'];
		$sale_p_array[$i][ifsales] = $ProNav['ifsales'];
		
		/**
		 *  获得产品颜色下拉菜单内变量
		 */
	
		if (trim($ProNav['good_color'])!=""){
			$Good_color_array    =  explode(',',trim($ProNav['good_color']));
	
			if (is_array($Good_color_array)){
				foreach($Good_color_array as $k=>$v )
				{
					$Good_Color_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Color_Option = "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Color_Option = "";
		}
	
		$sale_p_array[$i]['color'] =  $Good_Color_Option;
	
		/**
		 *  获得产品尺寸下拉菜单内变量
		 */
	
		if (trim($ProNav['good_size'])!=""){
			$Good_size_array    =  explode(',',trim($ProNav['good_size']));
	
			if (is_array($Good_size_array)){
				foreach($Good_size_array as $k=>$v )
				{
					$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";
				}
			}else{
				$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Size_Option = "";
		}
	
	
		$sale_p_array[$i]['size'] =  $Good_Size_Option;
		
		/**
		属性库存
		**/
		$Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid);
		$Query_s    = $DB->query($Sql_s);
		$Nums_s      = $DB->num_rows($Query_s);
		$i_s = 0;
		while ($Rs_s=$DB->fetch_array($Query_s)) {
			$sale_p_array[$i]['storage'][$i_s]['color'] = $Rs_s['color'];
			$sale_p_array[$i]['storage'][$i_s]['size'] = $Rs_s['size'];
			$sale_p_array[$i]['storage'][$i_s]['storage'] = $Rs_s['storage'];
		}



		$i++;
		$j++;
	}
}
$tpl->assign("ProductPageItem",       $PageNav->myPageItem());     //商品翻页条

$tpl->assign("sale_p_array",       $sale_p_array);




$tpl->assign($Good);
$tpl->display("product_sale.html");

?>