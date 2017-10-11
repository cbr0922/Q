<?php
include "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include "../language/".$INFO['IS']."/Order_Pack_Txt.php";


$Username = trim($_GET[username]);
$Type     = trim($_REQUEST[type]);

$EchoValue= "";

switch ($Type){
	case "checkusername":
		if ($Username!=''){
			$Sql   = "select count(*) as haveuser from `{$INFO[DBPrefix]}user` where username='".trim($GetValue)."' limit 0,1";
			$Query = $DB->query($Sql);
			$Result = $DB->fetch_array($Query);
			if (intval($Result[haveuser])>0){
				$EchoValue = "<font color=red>".$Admin_Member[Ajax_Userhave]."</font>";
			}else{
				$EchoValue = "<font color=green>".$Admin_Member[Ajax_UserPASS]."</font>";
			}
		}
		unset($Sql);
		unset($Query);
		unset($Result);
		break;

	case "order007status":
		$Sql = "update  `{$INFO[DBPrefix]}order_table` set order007_status='".intval($_GET[status])."' where order_id=".intval($_GET[Order_id]);
		$DB->query($Sql);
		if (intval($_GET[status])==0){
			$EchoValue = "<font color=red>".$Order_Pack[Close_to_start_order_tracks]."</font>" ;
		}else{
			$EchoValue = "<font color=green>".$Order_Pack[Open_to_start_order_tracks]."</font>" ;
		}
		unset($Sql);
		unset($Succed);
		break;

	case "order007time":
		$Sql = "update  `{$INFO[DBPrefix]}order_table` set order007_begtime='".$FUNCTIONS->smartshophtmlspecialchars(trim($_GET[begtime]))."' where order_id=".intval($_GET[Order_id]);
		$Succed = $DB->query($Sql);
		if ($Succed){
			$EchoValue = "<font color=green>".$Order_Pack[to_start_order_time_Change]."</font>";
		}
		unset($Sql);
		unset($Succed);
		break;


	case "order007content":
		$Sql = "update  `{$INFO[DBPrefix]}order_table` set order007_content='".$FUNCTIONS->smartshophtmlspecialchars(trim($_POST[content]))."' where order_id=".intval($_POST[Order_id]);
		$Succed = $DB->query($Sql);
		if ($Succed){
			$EchoValue = "<font color=green>".$Order_Pack[to_start_order_content_Change]."</font>" ;
		}
		unset($Sql);
		unset($Succed);
		break;

	default:
		$EchoValue = "";
		break;
}



echo $EchoValue;
?>
