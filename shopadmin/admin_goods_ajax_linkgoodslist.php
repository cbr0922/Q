<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');
$Where      = intval($_GET['top_id'])!=0 ? " and bid=".intval($_GET['top_id'])." " : ""  ;
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
		if ((in_array(intval($_GET[top_id]),$ot_class_array) && $_SESSION['LOGINADMIN_TYPE']==1) || $_SESSION['LOGINADMIN_TYPE']!=1){
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

}/*elseif($_SESSION['LOGINADMIN_TYPE']==1){
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
		$AddBidtype = " and bid in (" . implode(",",$class_array) . ")";
	}else{
		$AddBidtype = " and 1<>1";
	}
}*/
//$Where    =  "";
$Where    = $_GET['skey']!="" ?  " and goodsname like '%".$_GET['skey']."%'" : $Where ;

$s = $Where." and  gid!=".$Gid." ";

if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$Provider_string = " where provider_id=".intval($_SESSION['sa_id'])." ";
}else{
	$Provider_string = " where 1=1";
}
$Sql      = "select * from `{$INFO[DBPrefix]}goods` " . $Provider_string .$s.$AddBidtype." order by goodorder ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>


                  <TABLE   width="95%" border=0 align="center" cellPadding=0 cellSpacing=0 class=listtable id="selectlinkgoodsform">

                    <TBODY>
                    <TR align=middle>
                      <TD width="10%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
				      </TD>
                      <TD width="10%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductSmPic];//缩图?>
					  </TD>
                      <TD width="15%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>
                      <TD width="50%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductName];//名称?>					  </TD>
                      <TD  height=26 colspan="8" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductPrice];//价格?>					  </TD>
                      </TR>
					<?php
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

						$Yes = "images/".$INFO[IS]."/publish_g.png";
						$No  = "images/".$INFO[IS]."/publish_x.png";
						$ifpub_pic  = $Rs['ifpub']==1  ? $Yes : $No ;
						$ifrmd_pic  = $Rs['ifrecommend']==1  ? $Yes : $No ;
						$ifspec_pic = $Rs['ifspecial']==1  ? $Yes : $No ;
						$ifhot_pic  = $Rs['ifhot']==1  ? $Yes : $No ;

					?>
                    <TR class=row0>
                      <TD align=middle  height=20>
					  <INPUT id='cb<?php echo $i?>'  type=checkbox value='<?php echo $Rs['gid']?>' name=cid[]>
					  </TD>
					  <TD align=left  height=20><IMG onMouseOver="MM_showHideLayers('imgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('imgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/icon-viewpic.gif" width=18>
                        <DIV class=shadow id=imgLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 63px; POSITION: absolute; HEIGHT: 67px"   border="1"><IMG src="../<?php echo $INFO['good_pic_path']?>/<?php echo $Rs['smallimg']?>" ></DIV>
					  </TD>
                      <TD height=20 align="left" noWrap><?php echo $Rs['bn']?> &nbsp;</TD>
                      <TD  height=20 align=left nowrap> <?php echo $Rs['goodsname']?></TD>
                      <TD height=20 colspan="8" align=left nowrap><?php echo $Rs['price']?></TD>
                      </TR>
					<?php
					$i++;
					}
					?>
</TABLE>
                     <?php if ($Num>0){ ?>
			<table width="95%"    border=0 align="center" cellpadding=0 cellspacing=0 class=p9gray>
              <tbody>
                <tr>
                  <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav(1,"admin_goods_ajax_linkgoodslist.php","goodslinklist")?> </td>
                </tr>

</table><?php } ?>
