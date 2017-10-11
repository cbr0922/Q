<?php
include_once "Check_Admin.php";
include_once "../language/".$INFO['IS']."/Desktop_Pack.php";
include_once "../language/".$INFO['IS']."/Desktop_Pack_Ex.php";
include_once "../language/".$INFO['IS']."/StaticHtml_Pack.php";
switch (intval($INFO[OpenDesktopMenu])){
	case 0:
		$MenuI_Display = "none";
		break;
	case 1:
		$MenuI_Display = "";
		break;
	default:
		$MenuI_Display = "none";
		break;
}

?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
		if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
	var i,p,v,obj,args=MM_showHideLayers.arguments;
	for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
	if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
	obj.visibility=v; }
}
//-->
</script>
<SCRIPT language="JavaScript">
function fmenu1(){
	if ( menu1.style.display == "none"){
		menu1.style.display = "block";
	}else{
		menu1.style.display = "none";
	}
}

function JsAFPH(obj,v){
	if (v==0){
		CreateHtml_AllForProductHtml_Table.style.display = "";
		obj.innerHTML = "<a href='javascript:JsAFPH(touchAFPH,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
	}
	if (v==1){
		CreateHtml_AllForProductHtml_Table.style.display = "none";
		obj.innerHTML = "<a href='javascript:JsAFPH(touchAFPH,0);'><img src='images/suggest_down.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a>";
	}
}
</script>   
<div id="desktop">
 <div class="desktop_title"><?php echo $Desktop_Pack[favorite_navigation];?></div>
  <ul class="desktop_buttom">
  <li><a href="<?php echo $INFO['site_shopadmin']?>/admin_order_list.php">
   <IMG src="./images/<?php echo $INFO[IS]?>/icon-order-48.gif" border=0><br><?php echo $JsMenu[Order_Op]?></a></li>
  <li><A href="admin_order_list.php?State=AllPigeonhole"><IMG src="./images/<?php echo $INFO[IS]?>/ac0010NEW-48.gif" border=0><br /><?php echo $JsMenu[Order_Records_List] ?></A></li>
  <li><A href="admin_bonuspoint_uselist.php"><IMG src="./images/<?php echo $INFO[IS]?>/ec0042-48new.gif" border=0><br />紅利點數折抵紀錄</A></li>
  <li><A href="<?php echo $INFO['site_shopadmin']?>/admin_pcat_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/ac0034-48.gif" border=0><br /><?php echo $JsMenu[Product_Class_List]?></A></li>
  <li><A href="<?php echo $INFO['site_shopadmin']?>/admin_goods_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/icon-goodscon-48.gif" border=0><br /><?php echo $JsMenu[TheProduct_List]?></A></li>
  <li><A href="<?php echo $INFO['site_shopadmin']?>/admin_goods.php"><IMG src="./images/<?php echo $INFO[IS]?>/icon-goodsadd-48.gif" border=0><br /><?php echo $JsMenu[Product_Add]?></A></li>
  <li><A href="<?php echo $INFO['site_shopadmin']?>/admin_ncat_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/ac0056-48.gif"  border=0><br /><?php echo $JsMenu[Article_Class]?></A></li>
  <li><A href="admin_ncon_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/nd0065-48.gif"  border="0"><br /><?php echo $JsMenu[Article_List]?></A></li>
  <li><A href="<?php echo $INFO['site_shopadmin']?>/admin_ncon.php"><IMG src="./images/<?php echo $INFO[IS]?>/ac0038-48.gif" border=0><br /><?php echo $JsMenu[Article_Add]?></A></li>
  <li><A href="admin_member_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/nd0071-48.gif" border=0><br /><?php echo $JsMenu[Member_List] ?></A></li>
  <li><A href="admin_poll_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/nd0045-48.gif"  border="0"><br /><?php echo $JsMenu[Poll_Man]?></A></li>
  <li><A  href="<?php echo $INFO['site_shopadmin']?>/admin_contact_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/wi0088-48.gif"  border=0><br /><?php echo $JsMenu[Hz]?></A></li>
  <li><A href="admin_adv_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/icon-banner-48.gif" border=0><br /><?php echo $JsMenu[Advertis_List] ?></A></li>
  <li><A href="<?php echo $INFO['site_shopadmin']?>/admin_link_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/wi0013-48.gif" border=0><br /><?php echo $JsMenu[Link_Friend]?></A></li>
  <li><A href="<?php echo $INFO['site_shopadmin']?>/admin_databack.php"><IMG src="./images/<?php echo $INFO[IS]?>/nd0055-48.gif" border=0><br /><?php echo $JsMenu[DataBackup]?></A></li>
  <li><A href="<?php echo $INFO['site_shopadmin']?>/admin_group_list.php"><IMG src="./images/<?php echo $INFO[IS]?>/ei0004-48.gif" border=0><br /><?php echo $JsMenu[Shop_Pager]?></A></li>
  <li><A href="productVisit.php"><IMG src="./images/<?php echo $INFO[IS]?>/ac0032-48.gif"  border=0><br /><?php echo $JsMenu[ProductVisit]?></A></li>
  <li><A href="SaleMap.php"><IMG src="./images/<?php echo $INFO[IS]?>/ac0033-48.gif"  border=0><br /><?php echo $JsMenu[Sale]?></A></li>
 </ul>

</div>
