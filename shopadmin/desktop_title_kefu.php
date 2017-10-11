<?php
include_once "Check_Admin.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="css/css.css" rel="stylesheet" type="text/css">
<link href="css/theme.css" rel="stylesheet" type="text/css">

<?php  if ($_SESSION[LOGINADMIN_TYPE]==2){
$Desktop_Self   = "provider_desktop.php";
$Ncon_list = "provider_ncon_list.php";
$Ncon      = "provider_ncon.php";
//$Atr        =  $PROG_TAGS["ptag_1851"].$PROG_TAGS["ptag_379"];
//$Atr_list   =  $PROG_TAGS["ptag_1851"].$TPL_TAGS["ttag_2006"];

}else{
$Desktop_Self   = "desktop.php";
$Ncon_list = "admin_ncon_list.php";
$Ncon      = "admin_ncon.php";
//$Atr        =  $PROG_TAGS['ptag_29'];
//$Atr_list   =  $PROG_TAGS["ptag_28"];
}

?>
<TABLE cellSpacing=2 cellPadding=2 border=0>
        <TBODY>
        <TR>
          <?php if (intval($_SESSION[LOGINADMIN_TYPE])!=2) { ?>	
		  <TD class=allorange noWrap><A href="./admin_kefu_type_list.php">
		  <IMG height=20 src="images/<?php echo $INFO[IS]?>/icon-onlinekind.gif" width=20 border=0>
		  <?php echo $KeFu_Pack['Back_Type'];//問題類別?> </A></TD>
          <TD class=allorange noWrap><A href="./admin_kefu_chuli_list.php">
		  <IMG height=20 src="images/<?php echo $INFO[IS]?>/icon-onlineservice_status.gif" width=20 border=0>
		  <?php echo $KeFu_Pack['Back_Chuli'];//處理情況?> </A></TD>
          <TD class=allorange noWrap><A href="./admin_kefu_tem_list.php">
		  <IMG height=20 src="images/<?php echo $INFO[IS]?>/icon-onlineservice_temlate.gif" width=20 border=0> <?php echo $KeFu_Pack['Back_HuiFu'] ;//回覆樣版?> </A></TD>
		  <?php } ?> 
          </TR>
	    </TBODY>
</TABLE>
