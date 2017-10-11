<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Where    =  "";
$Where      = intval($_GET['bid'])!="" ? " and bid=".intval($_GET['bid'])." " : ""  ;
$Add        = "";
$AddBidtype =  "";
$ot_class_array = array();

if (intval($_GET[top_id])!=0 ){
	if (!is_array($op_class_array)){
		$op_class_array = array();
	}else{
		$ot_class_array = $op_class_array;
		foreach($ot_class_array as $k=>$v){
			$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($v));
			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$ot_class_array  = array_merge($Next_ArrayClass,$ot_class_array);	
		}
	}
		if ((in_array(intval($_GET[top_id]),$ot_class_array) && $_SESSION['LOGINADMIN_TYPE']==1) || count($op_class_array)==0 || $_SESSION['LOGINADMIN_TYPE']!=1){
			$S_Sql            = " and ( bid='".intval($_GET[top_id])."'  ";
			$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$Array_class      = array_unique($Next_ArrayClass);
			foreach ($Array_class as $k=>$v){
				$Add .= trim($v)!="" && intval($v)>0 ? " or bid='".$v."' " : "";
			}
		   $AddBidtype =$S_Sql . $Add . " )";	
		}
	if (AddBidtype!=""){
		//$AddBidtype = " and g.bid in (" . implode(",",$class_array) . ")";
	}else{
		$AddBidtype = " and 1<>1";	
	}
	
}elseif($_SESSION['LOGINADMIN_TYPE']==1){
	$_GET['Action']="Search";
	$class_array = array();
	$i = 0;
	foreach($op_class_array as $k=>$v){
		$class_array[$i] = $v;
		$i++;
		$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($v));
		if ($Next_ArrayClass!=0){
			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$Next_ArrayClass      = array_unique($Next_ArrayClass);
			if (is_array($Next_ArrayClass)){
				foreach($Next_ArrayClass as $kk=>$vv){
					if($vv!=0){
						$class_array[$i] = 	$vv;
						$i++;
					}
				}
			}
		}
	}
	
	if (count($class_array)>0){
		$AddBidtype = " and g.bid in (" . implode(",",$class_array) . ")";
	}//else{
	//	$AddBidtype = " and 1<>1";	
	//}
}
$Where    = $_GET['skey']!="" ?  " and goodsname like '%".$_GET['skey']."%'" : $Where ;
$Sql      = "select * from `{$INFO[DBPrefix]}goods` where 1=1 " . $AddBidtype .$Where." order by goodorder ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 12  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<script language="javascript">
$(function() {
           $("#checkAll").click(function() {
                $('input[name="cids[]"]').attr("checked",this.checked); 
            });
            var $subBox = $("input[name='cids[]']");
            $subBox.click(function(){
                $("#checkAll").attr("checked",$subBox.length == $("input[name='cids[]']:checked").length ? true : false);
            });
        });
</script>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td width="5%"><INPUT  type=checkbox value=checkbox   name="checkAll" id="checkAll">&nbsp;</td>
    <td width="62%" align="left">商品名稱</td>
    <td width="17%">價格</td>
    <td width="16%">成本價</td>
  </tr>
  <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

					?>
  <tr>
    <td><INPUT id='cb<?php echo $i?>'  type=checkbox value='<?php echo $Rs['gid']?>' name=cids[]> </td>
    <td align="left"><?php echo $Rs['goodsname']?></td>
    <td><?php echo $Rs['pricedesc']?></td>
    <td><?php echo $Rs['cost']?></td>
  </tr>
  <?php
					$i++;
					}
					?>
</table>
<?php if ($Num>0){ ?>
			<table width="95%"    border=0 align="center" cellpadding=0 cellspacing=0 class=p9gray>
              <tbody>
                <tr>
                  <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav(1,"admin_redgreensubject_ajax_goods_list.php","goodslinklist")?> </td>
                </tr>
                
</table>
<?php } ?>
