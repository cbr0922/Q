<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=UTF-8");
include("product.class.php");
$PRODUCT = new PRODUCT();
//print_r($_GET);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$Yes = "images/".$INFO[IS]."/publish_g.png";
$No  = "images/".$INFO[IS]."/publish_x.png";

if ($_GET[action]=='update' && intval($_GET[Id])>0 && $_GET[Value]!=''){

	switch ($_GET[Type]){
		case "updatePricedesc":
			$Sql    = "update  `{$INFO[DBPrefix]}goods` set pricedesc='".$Char_class->qj2bj($_GET[Value])."' where gid=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				echo  $Char_class->qj2bj($_GET[Value]);
			}
			break;

		case "updateStorage":
			$Sql    = "update  `{$INFO[DBPrefix]}goods` set storage='".$Char_class->qj2bj($_GET[Value])."' where gid=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				echo  $Char_class->qj2bj($_GET[Value]);
			}
			break;

		case "updateSort":
			$Sql    = "update  `{$INFO[DBPrefix]}goods` set goodorder='".$Char_class->qj2bj($_GET[Value])."' where gid=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				echo  $Char_class->qj2bj($_GET[Value]);
			}
			break;

		case "updateBn":
			$Sql    = "update  `{$INFO[DBPrefix]}goods` set bn='".$Char_class->qj2bj($_GET[Value])."' where gid=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				echo  $Char_class->qj2bj($_GET[Value]);
			}
			break;


		case "updateIfPub":
			$ifpub_pic    = $Char_class->qj2bj($_GET[Value])==1  ? $No : $Yes ;
			$ifpub_value  = $Char_class->qj2bj($_GET[Value])==1  ? 0 : 1 ;
			$Sql    = "update  `{$INFO[DBPrefix]}goods` set ifpub='".intval($ifpub_value)."' where gid=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				$Show = "<a onClick=ChangeIfPubInnerHtml('".$_GET[Element]."','".$ifpub_value."','".intval($_GET[Id])."') ><img src='".$ifpub_pic."' border='0' /></a>";
				echo $Show ;
			}
			break;

		case "updateifRmb":
			$ifrmd_pic    = $Char_class->qj2bj($_GET[Value])==1  ? $No : $Yes ;
			$ifrmd_value  = $Char_class->qj2bj($_GET[Value])==1  ? 0 : 1 ;
			$Sql    = "update  `{$INFO[DBPrefix]}goods` set ifrecommend='".intval($ifrmd_value)."' where gid=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				$Show = "<a onClick=ChangeifRmbInnerHtml('".$_GET[Element]."','".$ifrmd_value."','".intval($_GET[Id])."') ><img src='".$ifrmd_pic."' border='0' /></a>";
				echo $Show ;
			}
			break;
		case "updateifSpecial":
			
			$ifspec_pic    = $Char_class->qj2bj($_GET[Value])==1  ? $No : $Yes ;
			$ifspec_value  = $Char_class->qj2bj($_GET[Value])==1  ? 0 : 1 ;
			$Sql    = "update  `{$INFO[DBPrefix]}goods` set ifspecial='".intval($ifspec_value)."' where gid=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				$Show = "<a onClick=ChangeifSpecialInnerHtml('".$_GET[Element]."','".$ifspec_value."','".intval($_GET[Id])."') ><img src='".$ifspec_pic."' border='0' /></a>";
				echo $Show ;
			}
			break;

		case "updateifHot":
			$ifhot_pic    = $Char_class->qj2bj($_GET[Value])==1  ? $No : $Yes ;
			$ifhot_value  = $Char_class->qj2bj($_GET[Value])==1  ? 0 : 1 ;
			$Sql    = "update  `{$INFO[DBPrefix]}goods` set ifhot='".intval($ifhot_value)."' where gid=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				$Show = "<a onClick=ChangeifHotInnerHtml('".$_GET[Element]."','".$ifhot_value."','".intval($_GET[Id])."') ><img src='".$ifhot_pic."' border='0' /></a>";
				echo $Show ;
			}
			break;

		case "updateBcatname":
			include RootDocumentShare."/cache/Productclass_show.php";
			$Show  = $Char_class->get_page_select("bid",intval($_GET[Value]),"  class='trans-input' onblur=ChangeBcatnameActionInnerHtml('".$_GET[Element]."',this.value,".intval($_GET[Id]).");");
			echo $Show;
			break;


		case "updateBcatnameAction":
		//分類
			$class_banner = array();
			$list = 0;
			$PRODUCT->getTopBidList(intval($_GET[Value]));
			$bid_array[0] = $class_banner;
			$extendbid = json_encode($bid_array);
			$Sql    = "update  `{$INFO[DBPrefix]}goods` set bid='".intval($_GET[Value])."',extendbid='" .$extendbid. "' where gid=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				$Sql      = "select bc.catname from  `{$INFO[DBPrefix]}bclass`  bc  where bc.bid='".intval($_GET[Value])."' limit 0,1 ";
				$Query    = $DB->query($Sql);
				$Num      = $DB->num_rows($Query);
				if ($Num>0){
					$Result = $DB->fetch_array($Query);
					$Bcatname = $Result['catname'];
				}

				if (intval($_GET[Value])==0){
					$Bcatname  = "├─/";
				}
				$Show = "<a onClick=ChangeBcatnameInnerHtml('".$_GET[Element]."','".$_GET[Value]."','".intval($_GET[Id])."') >".$Bcatname."</a>";
				echo $Show;
			}
			break;


		default :
			echo 0;
			break;
	}
}


?>