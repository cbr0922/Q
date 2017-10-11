<?php
include("../configs.inc.php");
include(Classes . "/global.php");
@header("Content-type: text/html; charset=UTF-8");

/**
 *  装载语言包
 */

$Yes = "images/".$INFO[IS]."/publish_g.png";
$No  = "images/".$INFO[IS]."/publish_x.png";

if ($_GET[action]=='update' && intval($_GET[Id])>0 && $_GET[Value]!=''){

	switch ($_GET[Type]){
		case "updateNSort":
			$Sql    = "update  `{$INFO[DBPrefix]}news` set nord='".$Char_class->qj2bj($_GET[Value])."' where news_id=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				echo  $Char_class->qj2bj($_GET[Value]);
			}
			break;

		case "updateNauthor":
			$Sql    = "update  `{$INFO[DBPrefix]}news` set author='".trim($_GET[Value])."' where news_id=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				$Show = "<a onClick=ChangeNSortInnerHtml('".$_GET[Element]."','".$_GET[Value]."','".intval($_GET[Id])."') >".trim($_GET[Value])."</a>";
				echo $Show;
			}
			break;

		case "updateNcname":
			include RootDocumentShare."/Newsclass_show.php";
			$Show  = $Char_class->get_page_select("top_id",intval($_GET[Value]),"  class='trans-input' onblur=ChangeNcanmeActionInnerHtml('".$_GET[Element]."',this.value,".intval($_GET[Id]).");");
			echo $Show;
			break;

		case "updateNcnameAction":
			$Sql    = "update  `{$INFO[DBPrefix]}news` set top_id='".intval($_GET[Value])."' where news_id=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				$Sql      = "select nc.ncname from  `{$INFO[DBPrefix]}nclass`  nc  where nc.ncid='".intval($_GET[Value])."' limit 0,1 ";
				$Query    = $DB->query($Sql);
				$Num      = $DB->num_rows($Query);
				if ($Num>0){
					$Result = $DB->fetch_array($Query);
					$Ncname = $Result['ncname'];
				}

				if (intval($_GET[Value])==0){
					$Ncname  = "├─/";
				}
				$Show = "<a onClick=ChangeNcnameInnerHtml('".$_GET[Element]."','".$_GET[Value]."','".intval($_GET[Id])."') >".$Ncname."</a>";
				echo $Show;
			}
			break;

		case "updateNIffb":
			$Niffb_pic    = $Char_class->qj2bj($_GET[Value])==1  ? $No : $Yes ;
			$Niffb_value  = $Char_class->qj2bj($_GET[Value])==1  ? 0 : 1 ;
			$Sql    = "update  `{$INFO[DBPrefix]}news` set niffb='".intval($Niffb_value)."' where news_id=".intval($_GET[Id]);
			$Result = $DB->query($Sql);
			if ($Result){
				$Show = "<a onClick=ChangeNIffbInnerHtml('".$_GET[Element]."','".$Niffb_value."','".intval($_GET[Id])."') ><img src='".$Niffb_pic."' border='0' /></a>";
				echo $Show ;
			}
			break;

		default :
			echo 0;
			break;
	}
}


?>