<?php



@header("Content-type: text/html; charset=utf-8");



include_once "../language/".$INFO['IS']."/JsMenu.php";



include_once "../language/".$INFO['IS']."/Desktop_Pack.php";



?>







<?php  if ($_SESSION[LOGINADMIN_TYPE]==2){



$Desktop_Self   = "provider_desktop.php";



$Ncon_list      = "provider_ncon_list.php";



$Ncon           = "provider_ncon.php";



$Atr            =  $JsMenu[Provider_News_Add];



$Atr_list       =  $JsMenu[Provider_News_List];



$AddProductFile = "provider_goods.php";



$AddProductListFile = "provider_goods_list.php";



$AddCommentListFile = "provider_comment_list.php";



}else{



$Desktop_Self   = "desktop.php";



$Ncon_list      = "admin_member_list.php";



$Ncon           = "admin_order_list.php";



$Atr            =  $JsMenu[Order_List];



$Atr_list       =  $JsMenu[Member_List];



$AddProductFile = "admin_goods.php";



$AddProductListFile = "admin_goods_list.php";



$AddCommentListFile = "admin_comment_list.php";



}







?>



<TABLE cellSpacing=2 cellPadding=2 border=0>



        <TBODY>



        <TR>



          <TD width="80" noWrap class=allorange><A href="<?php echo $INFO['site_shopadmin']?>/<?php echo $AddProductListFile;?>">



		  <i class="icon-archive" style="font-size:14px;text-shadow:0 -1px 0 rgba(0,0,0,0.2), 0 1px 0 rgba(255,255,255,1);margin-right:3px"></i> <?php echo $JsMenu[TheProduct_List]?> </A></TD>

          <TD width="96" noWrap class=allorange><a href="admin_order_list.php?State=todayTrans"><i class="icon-bell" style="font-size:14px;text-shadow:0 -1px 0 rgba(0,0,0,0.2), 0 1px 0 rgba(255,255,255,1);margin-right:3px"></i> 當日提貨訂單</a></TD>



          <?php if (intval($_SESSION[LOGINADMIN_TYPE])!=2) { ?>	



		  <TD width="90" noWrap class=allorange><A href="<?php echo $INFO['site_shopadmin']?>/<?php echo $Ncon;?>">



		  <i class="icon-list-alt" style="font-size:14px;text-shadow:0 -1px 0 rgba(0,0,0,0.2), 0 1px 0 rgba(255,255,255,1);margin-right:3px"></i>  <?php echo $Atr?> </A></TD>



          <TD width="85" noWrap class=allorange><A href="<?php echo $INFO['site_shopadmin']?>/<?php echo $Ncon_list;?>">



		  <i class="icon-user" style="font-size:14px;margin-right:3px"></i> <?php echo $Atr_list?> </A></TD>



          <TD width="93" noWrap class=allorange><A href="<?php echo $INFO['site_shopadmin']?>/admin_adv_list.php">



		  <i class="icon-bullhorn" style="font-size:14px;text-shadow:0 -1px 0 rgba(0,0,0,0.2), 0 1px 0 rgba(255,255,255,1);margin-right:3px"></i> 廣告列表 </A></TD><?php } ?> 

          <TD width="103" noWrap class=allorange><A href="<?php echo $INFO['site_shopadmin']?>/<?php echo $Desktop_Self;?>">



		  <i class="icon-desktop" style="font-size:14px;text-shadow:0 -1px 0 rgba(0,0,0,0.2), 0 1px 0 rgba(255,255,255,1);margin-right:3px"></i> <?php echo $Basic_Command['GoToDesktop']?> </A></TD>



		 </TR>



	    </TBODY>



</TABLE>