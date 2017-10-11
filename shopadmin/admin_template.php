<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
include_once Classes . "/pagenav_ex.php";
$Build_nav = new NavFunction();
$AddrArray = explode("/",$_SERVER['PHP_SELF']);
$Sub_host = "";
foreach ($AddrArray as $k=>$v){
	if ($v!="shopadmin"){
		$Sub_host .= $v."/";
	}elseif($v=="shopadmin"){
		break;
	}
}
?>
<HTML>
<head>
<META http-equiv=ever content=no-cache>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2600.0" name=GENERATOR>
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Templates_Man]?>--&gt;<?php echo $JsMenu[Templates_List]?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
	function checkform(){		
		form1.submit();
	}
</SCRIPT>

<div id="contain_out">
  <FORM name=form1 action='' method=post >
    <?php  include_once "Order_state.php";?>
  <input type="hidden" name="Action" value="Modi">
  <input type="hidden" name="link_pic_path" value="<?php echo $INFO['link_pic_path']?>">
  <input type="hidden" name="good_pic_path" value="<?php echo $INFO['good_pic_path']?>">
  <input type="hidden" name="site_url" value="<?php echo $INFO['site_url']?>">
  <input type="hidden" name="site_shopadmin" value="<?php echo $INFO['site_shopadmin'];?>">
  <input type="hidden" name="site_name" value="<?php echo $INFO['site_name'];?>">  
  <input type="hidden" name="site_title" value="<?php echo $INFO['site_title'];?>">  
  <input type="hidden" name="DBhostname" value="<?php echo $INFO['DBhostname'];?>">
  <input type="hidden" name="DBuserName" value="<?php echo $INFO['DBuserName'];?>">
  <input type="hidden" name="DBpassword" value="<?php echo $INFO['DBpassword'];?>">
  <input type="hidden" name="DBname" value="<?php echo $INFO['DBname'];?>">
  <input type="hidden" name="DBPrefix" value="<?php echo $INFO['DBPrefix'];?>">
  <input type="hidden" name="absolutePath" value="<?php echo $INFO['absolutePath'];?>">  
  <input type="hidden" name="buy_product_max_num" value="<?php echo $INFO['buy_product_max_num'];?>">  
  

  <input type="hidden" name="admin_IS" value="<?php echo $INFO['admin_IS'];?>">    
  <input type="hidden" name="IS" value="<?php echo $INFO['IS'];?>">  
  <input type="hidden" name="addr" value="<?php echo $INFO['addr'];?>">  
  <input type="hidden" name="b_attr" value="<?php echo $INFO['b_attr'];?>">  
  <input type="hidden" name="chartset" value="<?php echo $INFO['chartset'];?>">  
  <input type="hidden" name="city" value="<?php echo $INFO['city'];?>">  
  <input type="hidden" name="company_name" value="<?php echo $INFO['company_name'];?>">  
  
  <input type="hidden" name="content" value="<?php echo $INFO['content'];?>">  
  <input type="hidden" name="email" value="<?php echo $INFO['email'];?>">  
  <input type="hidden" name="fax" value="<?php echo $INFO['fax'];?>">  
  <input type="hidden" name="lxr" value="<?php echo $INFO['lxr'];?>">  
  <input type="hidden" name="meta_desc" value="<?php echo $INFO['meta_desc'];?>">  
  <input type="hidden" name="meta_keyword" value="<?php echo $INFO['meta_keyword'];?>">  
  
  
  <input type="hidden" name="other_tel" value="<?php echo $INFO['other_tel'];?>">  
  <input type="hidden" name="post" value="<?php echo $INFO['post'];?>">  
  <input type="hidden" name="sex" value="<?php echo $INFO['sex'];?>">  
  <input type="hidden" name="tel" value="<?php echo $INFO['tel'];?>">  
  <input type="hidden" name="Firstpay" value="<?php echo $INFO['Firstpay'];?>">  
  <input type="hidden" name="PayStartprice" value="<?php echo $INFO['PayStartprice'];?>">  
  <input type="hidden" name="PayEndprice" value="<?php echo $INFO['PayEndprice'];?>">  
  <input type="hidden" name="Paytype" value="<?php echo $INFO['Paytype'];?>">  
  <input type="hidden" name="Sub_Host" value="<?php echo $INFO['Sub_host'];?>">


  <input type="hidden" name="Shop_I" value="<?php echo $INFO['Shop_I'];?>">
  <input type="hidden" name="Shop_II" value="<?php echo $INFO['Shop_II'];?>">
  <input type="hidden" name="Shop_Code" value="<?php echo $INFO['Shop_Code'];?>">
  <input type="hidden" name="Tw_OnePay" value="<?php echo $INFO['Tw_OnePay'];?>">
  <input type="hidden" name="duration" value="<?php echo $INFO['duration'];?>">
  <input type="hidden" name="MinBuyMoney" value="<?php echo $INFO['MinBuyMoney'];?>">

  <input type="hidden" name="SetupDiscuz" value="<?php echo $INFO['SetupDiscuz'];?>">
  <input type="hidden" name="ZhiFuBaoType" value="<?php echo $INFO['ZhiFuBaoType'];?>">
  <input type="hidden" name="ZhiFuBaoKey" value="<?php echo $INFO['ZhiFuBaoKey'];?>">
  <input type="hidden" name="ZhiFuBaoID" value="<?php echo $INFO['ZhiFuBaoID'];?>">
  <input type="hidden" name="ZhiFuBao" value="<?php echo $INFO['ZhiFuBao'];?>">
  <input type="hidden" name="zfb_open" value="<?php echo $INFO['zfb_open'];?>">
  <input type="hidden" name="staticState" value="<?php echo $INFO['staticState'];?>">
  <input type="hidden" name="OpenDesktopMenu" value="<?php echo $INFO['OpenDesktopMenu'];?>">
  <input type="hidden" name="MaxProductNumForList" value="<?php echo $INFO['MaxProductNumForList'];?>">
  <input type="hidden" name="MaxNewProductNum" value="<?php echo $INFO['MaxNewProductNum'];?>">


  <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="44%">
              <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange> <?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Templates_Man]?>--&gt;<?php echo $JsMenu[Templates_List]?></SPAN></TD>
                    </TR>
                  </TBODY>
                </TABLE></TD>
            <TD align=center width="44%">&nbsp;</TD>
            <TD width="8%" align="right" >			  
              <!--BUTTON_BEGIN-->
              <TABLE>
                <TBODY>
                  <TR>
                    <TD vAlign=bottom noWrap class="link_buttom">
                      <a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              <!--BUTTON_END-->		  </TD>	  
            </TR>
          </TBODY>
        </TABLE>
                      <TABLE class=allborder cellSpacing=8 cellPadding=8  width="100%" align=center border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=center>
                              <br>
                              <?php
					    $dir = "../templates";
					    $dh  = opendir($dir);
					    while (false !== ($filename = readdir($dh))) {
					    	$files[] = $filename;
					    }
					    array_shift ($files);
					    array_shift ($files);
					    $templates = array();
					    foreach ($files as $k=>$v){
					    	$pos = strpos($v, ".");
					    	if ($pos === false) {
					    		$templates[] = $v;
					    	}
					    }
					    $vi = 1;
					    foreach ($templates as $k =>$v) {
					    	if (trim($v)!=trim($INFO[templates])){
					    		$Temp_array[$vi] = $v;
					    		$vi++;
					    	}
					    }

					    $Temp_array[0] = $INFO[templates];  //将每次被选择的摸板都放到数组的第一
					    ksort($Temp_array);

					    //print_r($Temp_array);
					    //建立临时表
					    $CreateDBTable_sql = " CREATE  TEMPORARY  TABLE  templates  (  name  VARCHAR(50)  NOT  NULL )  ";
					    $DB->query($CreateDBTable_sql);

					    //将数据插入临时表
					    foreach ($Temp_array as $k =>$v) {
					    	$InsertSql = " insert into templates (name) values ('".$v."')";
					    	$DB->query($InsertSql);
					    }
					    $Sql = " select name from  templates ";
					    $Query = $DB->query($Sql);
					    $Num      = $DB->num_rows($Query);
					    if ($Num>0){
					    	echo $Build_nav->row_nav_return($DB,$Sql,3," width=\"90%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=9pv",$Td_css,$Font_css,9,6,"templates",$INFO['templates']);
					    }
					   ?>	<br></TD>
                            </TR>
                          </TBODY>
                         </TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
<?php

if ( $_POST['Action']=="Modi" ){




	//$Ex_Function->save_config( $new = array("IS","admin_IS","chartset","site_name","lxr","sex","Firstpay","email","city","addr","other_tel","tel","fax","post","site_title","company_name","content","site_url","site_shopadmin","b_attr","meta_desc","meta_keyword","good_pic_path","link_pic_path","templates","buy_product_max_num","DBhostname","DBuserName","DBpassword","DBname","DBPrefix","absolutePath","PayStartprice","PayEndprice","Paytype"),"conf.global") ;


	$Ex_Function->save_config( $new = array("IS","admin_IS","chartset","site_name","lxr","sex","email","city","addr","other_tel","tel","fax","post","site_title","company_name","content","site_url","Firstpay","site_shopadmin","b_attr","meta_desc","meta_keyword","good_pic_path","link_pic_path","templates","buy_product_max_num","DBhostname","DBuserName","DBpassword","DBname","DBPrefix","absolutePath","PayStartprice","PayEndprice","Paytype","Need_invoice","invoice","ATM","ATM_SECTION","Sub_Host","Shop_I","Shop_II","Shop_Code","Tw_OnePay","duration","MinBuyMoney","SetupDiscuz","ZhiFuBaoType","ZhiFuBaoKey","ZhiFuBaoID","ZhiFuBao","zfb_open","OpenDesktopMenu","staticState","MaxProductNumForList","MaxNewProductNum"),"conf.global") ;
	$FUNCTIONS->setLog("更換模板");




	// $Ex_Function->save_config( $new = array("IS","chartset","site_name","lxr","sex","email","city","addr","other_tel","tel","fax","post","site_title","company_name","content","site_url","site_shopadmin","b_attr","meta_desc","meta_keyword")) ;  //数组的名称需要对应修改的字段名称！
	echo " <script language=javascript> alert('".$Basic_Command['Back_System_Sucuess']."'); location.href='admin_template.php'; </script>";
}
?>
