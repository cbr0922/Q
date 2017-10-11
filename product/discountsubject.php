<?php
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include "../language/".$INFO['IS']."/Good.php";
include("global.php");

$Sql_sub   = " select * from `{$INFO[DBPrefix]}discountsubject` where subject_open=1";
$Query_sub = $DB->query($Sql_sub);
$Array_sub = array();
$sub_i = 0;
while ($Rs_sub = $DB->fetch_array($Query_sub) ){
	$Array_sub[$sub_i][dsid]    =  $Rs_sub['dsid'];
	$Array_sub[$sub_i][subject_name]  =  $Rs_sub['subject_name'];
	$Array_sub[$sub_i][start_date]  =  $Rs_sub['start_date'];
	$Array_sub[$sub_i][end_date]  =  $Rs_sub['end_date'];
	$Array_sub[$sub_i][min_money]  =  $Rs_sub['min_money'];
	$Array_sub[$sub_i][min_count]  =  $Rs_sub['min_count'];
	$Array_sub[$sub_i][mianyunfei]  =  $Rs_sub['mianyunfei'];
	$sub_i++;
}

$tpl->assign("Array_sub",       $Array_sub);           //主题类别名称循环

$Subject_id = intval($_GET['subject_id'])>0 ? intval($_GET['subject_id']) : intval($INFO[subject_id]);

$Query = $DB->query("select *  from  `{$INFO[DBPrefix]}discountsubject`  where dsid='".$Subject_id."' and subject_open=1 limit 0,1");
$Num      = $DB->num_rows($Query);
if ($Num<=0)
	$FUNCTIONS->sorry_back('back',"活動主題關閉");
	
while ($Rs    = $DB->fetch_array($Query) ){
	$template = $Rs['template'];
	$tpl->assign("Subject_name",          $Rs['subject_name']);              //主题名字
	$tpl->assign("Subject_content",       $Rs['subject_content']);           //主题内容
	$tpl->assign("start_date",          $Rs['start_date']);
	$tpl->assign("end_date",          $Rs['end_date']);
	$tpl->assign("min_money",          $Rs['min_money']);
	$tpl->assign("min_count",          $Rs['min_count']);
	$tpl->assign("mianyunfei",          $Rs['mianyunfei']);
	$tpl->assign("point",          $Rs['point']);
}

include("PageNav.class.php");

$Sql = "select g.*,dg.price as dgprice from `{$INFO[DBPrefix]}discountgoods` as dg inner join `{$INFO[DBPrefix]}goods` as g on dg.gid=g.gid where dg.dsid='" . $Subject_id . "' and g.ifchange!=1 and g.ifpub=1 and dg.ifcheck=1 order by idate desc ";

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
		$sale_p_array[$i][sale_price] = $ProNav['dgprice'];
		$sale_p_array[$i][pricedesc] = $ProNav['pricedesc'];
		$sale_p_array[$i][storage] = $ProNav['storage'];
		$sale_p_array[$i][smallimg] = $ProNav['smallimg'];
		
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
$tpl->display($template);
?>