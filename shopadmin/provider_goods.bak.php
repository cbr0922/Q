<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
include_once Resources."/ckeditor/ckeditor.php";
if ($_POST['bid']!=""){
	//$Bid =  intval($_POST['bid'])!="" ? intval($_POST['bid'])!="" : intval($_GET['bid'])!="";
	$ChangeBid   =  intval($_POST['bid']);
	$ChangeAction_value = trim($_POST['Action']);
	$Gid         =  $_POST['gid'];
	$Action      =  'ChangeBid';
	if ($ChangeAction_value=='Insert'){
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($ChangeBid)." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			$Bid =  $Result['bid'];
			if ($Result['attr']!=""){
				$attrI   =  "0,".$Result['attr'];
				$Attr    =  explode(',',$attrI);
				$Attr_num=  count($Attr);
			}else{
				$Attr_num=0;
			}
		}else{
			echo "<script language=javascript>location.href='admin_create_productclassshow.php';</script>";
			exit;
		}
	}
}


if ((intval($_GET['gid'])!=0 && $_GET['Action']=='Modi') || ($Action=='ChangeBid' &&  $ChangeAction_value!='Insert') ){
	/**
	 * 这里是当供应商进入的时候。只能修改自己的产品资料。
	 */
	if (intval($_SESSION[LOGINADMIN_TYPE])==2){
		$Provider_string = " and provider_id=".intval($_SESSION['sa_id'])." ";
	}else{
		$Provider_string = "";
	}

	$Gid = intval($_GET['gid'])==0 ? $Gid : intval($_GET['gid']);
	$Action_value = "Update";
	$OptionSelect = "select_type_nochange";
	$Action_say  = $Admin_Product[ModiProduct] ; //修改商品
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($Gid)." ".$Provider_string." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Bid =  $ChangeBid!=0 ? $ChangeBid : $Result['bid'];
		$Goodsname  =  $Result['goodsname'];
		$good_color  =  $Result['good_color'];
		$good_size  =  $Result['good_size'];
		$Bn         =  $Result['bn'];
		$Brand      =  $Result['brand'];
		$Brand_id   =  $Result['brand_id'];
		$Intro      =  $Result['intro'];
		$Unit       =  $Result['unit'];
		$Pricedesc  =  $Result['pricedesc'];
		$Price      =  $Result['price'];
		$cost       =  $Result['cost'];
		$Point      =  $Result['point'];
		$Alarmnum   =  $Result['alarmnum'];
		$AlarmContent   =  $Result['alarmcontent'];
		$Storage    =  $Result['storage'];
		$Ifalarm    =  $Result['ifalarm'];
		$Ifpub      =  $Result['ifpub'];
		$Ifgl       =  $Result['ifgl'];
		$Nocarriage =  $Result['nocarriage'];
		$Ifrecommend = $Result['ifrecommend'];
		$Ifspecial  =  $Result['ifspecial'];
		$Subject_id =  $Result['subject_id'];
		$Ifhot      =  $Result['ifhot'];
		$Body       =  $Result['body'];
		$Idate      =  $Result['idate'];
		$Sm_img     =  $Result['smallimg'];
		$Mid_img    =  $Result['middleimg'];
		$Keywords   =  $Result['keywords'];
		//$Bg_img     =  $Result['bigimg'];
		$G_img      =  $Result['gimg'];
		$View_num   =  $Result['view_num'];
		$Ifbonus    =  intval($Result['ifbonus']);
		$Ifjs       =  intval($Result['ifjs']);
		$Bonusnum   =  $Result['bonusnum'];
		$Provider_id=  $Result['provider_id'];
		$goodattrI  =  "0,".$Result['goodattr'];
		$begtime    =  trim($Result['js_begtime']);
		$endtime    =  trim($Result['js_endtime']);
		$Js_price   =  explode("||",trim($Result['js_price']));
		$Js_count   =  explode("||",trim($Result['jscount']));
		$Js_totalnum=  intval($Result['js_totalnum']);
		$Video_url                =  $Result['video_url'];
		$Del_img_array            =  explode("_",$Mid_img);
		$Del_img                  =  $Del_img_array[0];
		$if_monthprice    =  trim($Result['if_monthprice']);
		$en_name    =  trim($Result['en_name']);
		$component    =  trim($Result['component']);
		$capability    =  trim($Result['capability']);
		$cap_des    =  trim($Result['cap_des']);
		$goodsno = trim($Result['goodsno']);
		$salename_color = trim($Result['salename_color']);
		$chandi  =trim($Result['chandi']);
		$ERP  =trim($Result['ERP']);
		
		$goodAttr   =  explode(',',$goodattrI);
		//产品独立属性
		$gattribs             =  trim($Result['gattribs']);
		$gattribs_content     =  trim($Result['gattribs_content']);
		$Gattribs_array    = explode("|",$gattribs);
		$G_content_arry    = explode("|",$gattribs_content);
		$Gattr_num = count($Gattribs_array);
		$ifpresent             =  trim($Result['ifpresent']);
		$present_money             =  trim($Result['present_money']);
		$trans_type = trim($Result['trans_type']);
		$iftransabroad = trim($Result['iftransabroad']);
		$trans_special = trim($Result['trans_special']);
		$trans_special_money    =  trim($Result['trans_special_money']);
		$ifxygoods    =  trim($Result['ifxygoods']);
		$ifxy    =  trim($Result['ifxy']);
		$ifxysale    =  trim($Result['ifxysale']);
		$ifchange    =  trim($Result['ifchange']);
		$xycount    =  trim($Result['xycount']);
		$sale_name    =  trim($Result['sale_name']);
		$sale_subject    =  trim($Result['sale_subject']);
		$sale_price    =  trim($Result['sale_price']);
		$ifsales    =  trim($Result['ifsales']);
		$ifsaleoff    =  trim($Result['ifsaleoff']);
		$ifadd    =  trim($Result['ifadd']);
		$addmoney    =  trim($Result['addmoney']);
		$addprice    =  trim($Result['addprice']);
		$oeid    =  trim($Result['oeid']);
		$saleoffprice    =  intval($Result['saleoffprice']);
		$iftimesale    =  trim($Result['iftimesale']);
		$present_endmoney             =  trim($Result['present_endmoney']);
		$transtype    =  trim($Result['transtype']);
		$ifmood    =  trim($Result['ifmood']);
		$addtransmoney    =  trim($Result['addtransmoney']);
		$transtypemonty    =  trim($Result['transtypemonty']);
		$memberprice    =  trim($Result['memberprice']);
		$combipoint    =  trim($Result['combipoint']);
		$weight  =trim($Result['weight']);
		$guojima    =  trim($Result['guojima']);
		$xinghao  =trim($Result['xinghao']);
		$salecost  =trim($Result['salecost']);
		
		if ($Result['saleoff_starttime']!=""){
			$saleoff_startdate    =  date("Y-m-d",trim($Result['saleoff_starttime']));
			$start_h    =  date("H",trim($Result['saleoff_starttime']));
			$start_i    =  date("i",trim($Result['saleoff_starttime']));
		}
		
		if ($Result['saleoff_endtime']!=""){
			$saleoff_enddate    =  date("Y-m-d",trim($Result['saleoff_endtime']));
			
			
			
			$end_h    =  date("H",trim($Result['saleoff_endtime']));
			$end_i    =  date("i",trim($Result['saleoff_endtime']));
		}
		
		if ($Result['timesale_starttime']!=""){
			 $timesale_startdate    =  date("Y-m-d",trim($Result['timesale_starttime']));
			$timesalestart_h    =  date("H",trim($Result['timesale_starttime']));
			$timesalestart_i    =  date("i",trim($Result['timesale_starttime']));
		}
		
		if ($Result['timesale_endtime']!=""){
			$timesale_enddate    =  date("Y-m-d",trim($Result['timesale_endtime']));
			
			
			
			$timesaleend_h    =  date("H",trim($Result['timesale_endtime']));
			$timesaleend_i    =  date("i",trim($Result['timesale_endtime']));
		}
		
		if (intval($Gattr_num)>0 && $gattribs!=""){
			for ($i=0;$i<intval($Gattr_num);$i++){
				$Add_Table .="<tr><td noWrap align=right width=\"12%\" >".$Gattribs_array[$i]."：</td><td>";
				$GoodSubArray = explode(",",$G_content_arry[$i]);
				if (intval(count($GoodSubArray))>1){
					$Add_Table .="<select name=sgoodattr".$i."><option value=''>".$Gattribs_array[$i]."</option>";
					foreach ($GoodSubArray as $k=>$v) {
						$Add_Table .="<option value=".$v.">".$v."</option>";
					}
					$Add_Table .="</select>";
				}else{
					$Add_Table .=$G_content_arry[$i];
				}
				$Add_Table .="</td></tr>";
			}
		}


		$Query = $DB->query("select attr from `{$INFO[DBPrefix]}bclass` where bid=".intval($Bid)." limit 0,1");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result= $DB->fetch_array($Query);
			if ($Result['attr']!=""){
				$attrI   =  "0,".$Result['attr'];
				$Attr    =  explode(',',$attrI);
				$Attr_num=  count($Attr);
			}else{
				$Attr_num=0;
			}
		}

	}else{
		echo "<script language=javascript>alert('商品不存在');window.close();</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$Action_say   = $Admin_Product[CreateProduct] ; //新建产品
	$OptionSelect = "select_type_onchange";
	$ifmood = 1;
}
if ($_POST[doaction]=='copy'){
	$Action_value = "Copy";
	$Action_say   = $Admin_Product[CloneProduct]; //复制商品
}

include RootDocumentShare."/cache/Productclass_show.php";
if (intval($_SESSION['LOGINADMIN_TYPE'])==2){
	$Provider_id = intval($_SESSION['sa_id']);
	$Add_Input = "<input type='hidden' name='provider_id' value='".$Provider_id."'>";
	$Provider_name = trim($_SESSION['Admin_Sa'])."&nbsp;[".$_SESSION['Provider_thenum']."]";
}
//echo $goodattrI;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK id=css href="css/calendar.css" type='text/css' rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say?>
</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.validate.js"></script>
<script language="javascript" type="text/javascript" src="../js/show_dialog.js"></script>
<script type="text/javascript" src="../js/alter.js"></script>

<style type="text/css">
body{
margin:0px;
}


#fullBg{
background-color: Black;
display:none;
z-index:30;
position:absolute;
left:0px;
top:0px;
filter:Alpha(Opacity=30);
/* IE */
-moz-opacity:0.4; 
/* Moz + FF */
opacity: 0.4; 
}
#msg{
	position:absolute;
	z-index:40;
	display:none;
	background-color:#FFFFFF;
	border:4px solid #00CCCC;
}
#msg #close{
height:30px;
text-align:right;
padding-top:8px;
padding-right:15px;
}
#msg #ctt{
text-align:center;
font-size:12px;
padding-bottom:15px;
}
#cPic{
cursor:pointer;
}


</style>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" onLoad="addMouseEvent();">
<div id="fullBg"></div>
<div id="msg">
<div id="close"></div>
<div id="ctt"></div>
</div>
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?></TD>
  </TR></TBODY></TABLE>
<SCRIPT language=javascript>
function select_color(type)
{
	var str;
	var arr_str=new Array();
	str=window.showModalDialog("color_select.html","","dialogWidth:28;dialogHeight:20");
	if (!str) return false;
	arr_str=str.split("||");
	
	if (type==1){
	 document.form_goodsbase.salename_color.value=arr_str[0];
	 document.form_goodsbase.salename_color.value=arr_str[1];
	}
	
	return true;
}
	function checkform(){
		if (chkblank(form_goodsbase.goodsname.value) || form_goodsbase.goodsname.value.length>50){
			alert('<?php echo $Admin_Product[PleaseInputPrductName]?>'); //请输入商品名称
			form_goodsbase.goodsname.focus();
			return;
		}
		//if (form_goodsbase.bid.value==0){
		//	alert('<?php echo $Admin_Product[PleaseSelectPrductClassName]?>'); //请选择商品所属类别
		//	form_goodsbase.bid.focus();
		//	return;
		//}
		//if (chkblank(form_goodsbase.brand_id.value) || form_goodsbase.brand_id.value.length==0){
		//	alert('<?php echo $Admin_Product[PleaseInputPrductBand]?>'); //请输入商品品牌
		//	form_goodsbase.brand.focus();
		//	return;
		//}
		if (chkblank(form_goodsbase.chandi.value)){
			alert('請填寫產地'); //请输入商品名称
			form_goodsbase.chandi.focus();
			return;
		}
		if (chkblank(form_goodsbase.weight.value)){
			alert('請填寫重量'); //请输入商品名称
			form_goodsbase.weight.focus();
			return;
		}/*
		if (chkblank(form_goodsbase.unit.value)){
			alert('請填寫計量單位'); //请输入商品名称
			form_goodsbase.unit.focus();
			return;
		}

		if (chkblank(form_goodsbase.bn.value) || form_goodsbase.bn.value.length>50){
			alert('<?php echo $Admin_Product[PleaseInputPrductBn]?>');  //请输入商品货号
			form_goodsbase.bn.focus();
			return;
		}
		*/
		var tmp_price;
		
		tmp_price=ismoney(form_goodsbase.price.value);
		if (tmp_price<=0){
			alert('<?php echo $Admin_Product[PleaseInputRightPrductPrice]?>'); //请输入正确的商品价格
			form_goodsbase.price.value="";
			form_goodsbase.price.focus();
			return;
		}
		
		var pricedesc;
		
		pricedesc=ismoney(form_goodsbase.pricedesc.value);
		if (pricedesc<=0){
			alert('請輸入正確的網路價格'); //请输入正确的商品价格
			form_goodsbase.pricedesc.value="";
			form_goodsbase.pricedesc.focus();
			return;
		}
		//alert(form_goodsbase.gain.value/100.00);
		if (form_goodsbase.cost.value<=0){
			/*
			if(tmp_price*(1-(form_goodsbase.gain.value/100.00))>pricedesc){
				alert('網絡價格低於成本，價格應該大於等於' + (tmp_price*(1-form_goodsbase.gain.value/100.00)));
				return;
			}
			*/
		}else{
			if (form_goodsbase.cost.value>pricedesc){
				alert('網路價格低於成本');
			}
		}
		
		form_goodsbase.action="provider_goods_save.php";
		form_goodsbase.submit();
	}
	
	function changecat(){
		form1.action="provider_goods.php";
		//save();
		form1.submit();
	}
	var gid = "" ;
	function msgpicmore(Action_value,gid){
		
		if (Action_value != 'Update'){
			alert('<?php echo $Admin_Product[PleaseSaveProductPic] ?>') ;  //请先保存商品后，再上传多图！
		}else{
			location.href= "provider_goods_pic.php?good_id=" + gid ;
		}
	}
	
	function upIndex(Action_value,gid){
		
		if (Action_value == 'Update'){
	  	location.href= "provider_goods_up.php?good_id=" + gid ;
		//alert('本功能将在以后开放,届时您可以将本站的产品资料提交到行业总站上！');
		return ;
		}
	}

	function MoreAttrib(gid){
	  	location.href= "provider_goods_attrib.php?good_id=" + gid ;
	}
		
		
	function memberprice(Action_value,gid)
	{
		if (Action_value != 'Update'){
			alert('<?php echo $Admin_Product[PleaseSaveProductToPrice]?>') ;  //请先保存商品后，再设定会员价格！
		}else{
			location.href= "provider_memberprice.php?gid=" + gid ;
		}
	}
	
	function linkgoods(Goodsname,gid)
	{
		//alert('本功能正在开发中!');
		
		if (gid == ""){
			alert('<?php echo $Admin_Product[PleaseSaveProductToProduct]?>') ;	//请先保存商品后，再设定相关商品！
		}else{
			location.href= "admin_linkgoods.php?gid=" + gid + "&Goodsname="+ Goodsname;
		}
		
	}
	function view(obj,a)
	{
		if(a == 1){
			obj.style.display="";
		}else{
			obj.style.display="none";
		}
	}
	
	function viewjs(a)
	{
		if(a == 1){
			jsshow1.style.display="";
			jsshow2.style.display="";
			jsshow3.style.display="";						
		}else{
			jsshow1.style.display="none";
			jsshow2.style.display="none";
			jsshow3.style.display="none";						
		}
	}	
	
	/*
	function toCard()
	{
		var goodsid = form1.gid.value;
		if(goodsid == ""){
			alert("请先保存商品后，再进行补货！");
		}else{
			location='admin_cards.php?goodsid='+goodsid;
		}
	}
	*/
	
	/**
     * 初始化一个xmlhttp对象
     */
function InitAjax()
{
  var ajax=false; 
  try { 
    ajax = new ActiveXObject("Msxml2.XMLHTTP"); 
  } catch (e) { 
    try { 
      ajax = new ActiveXObject("Microsoft.XMLHTTP"); 
    } catch (E) { 
      ajax = false; 
    } 
  }
  if (!ajax && typeof XMLHttpRequest!='undefined') { 
    ajax = new XMLHttpRequest(); 
  } 
  return ajax;
}
</SCRIPT>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<link id="jquiCSS" rel="stylesheet" href="../css/jquery-ui-1.9.2.custom.min.css" type="text/css" media="all">
<link id="jquiCSS" rel="stylesheet" href="../css/evol.colorpicker.css" type="text/css" media="all">
<script type="text/javascript" src="../js/jquery-ui-1.9.2.custom.min.js"></script> 
<script type="text/javascript" src="../js/evol.colorpicker.js"></script> 
<div id="contain_out">
<?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>

	<?php echo $Add_Input?>
   <TBODY>
  <TR>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say?></SPAN></TD>
                    </TR>
                  </TBODY>
                </TABLE></TD>
            <TD align=right width="50%"><table border="0" align="right" cellpadding="0" cellspacing="0" id="showsave">
              <tbody>
                <tr>
                  <td align="middle"><table height="33" cellspacing="0" cellpadding="0" width="79" border="0">
                    <tbody>
                      <tr>
                        <td align="middle" width="79"><!--BUTTON_BEGIN-->
                          <table>
                            <tbody>
                              <tr>
                                <td valign="bottom" nowrap="nowrap" class="link_buttom"><a href="javascript:checkform();"><img src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border="0" />&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></td>
                                </tr>
                              </tbody>
                            </table>
                          <!--BUTTON_END--></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                </tbody>
              </table></TD>
            </TR>
          </TBODY>
        </TABLE>
      <table border="0" cellspacing="0">
        <tr>
          <td> <a href="#" onClick="showtable('showtable_base');getMoreAttrib('<?php echo $Bid?>');
 getAttriClass('<?php echo $Bid?>');">基本資料</a> |  <a href="#" onClick="showtable('showtable_tag');">TAG</a> | <a href="#" onClick="showtable('showtable_peisong');"> 配送設置 </a> <? if ($_GET['Action']=='Modi'){?> <!--| <a href="#" onClick="showtajaxfun('memberprice');">商品會員價格</a--> | <a href="#" onClick="showtajaxfun('attrib');">商品多屬性</a> | <a href="#" onClick="showtajaxfun('goodsdetail');">詳細資料</a> | <!--a href="#" onClick="showtajaxfun('storage');">詳細庫存</a> |--> <a href="#" onClick="showtajaxfun('morepic');">多圖上傳</a> | <a href="#" onClick="showtajaxfun('goodslink');">相關商品</a> <? }?></td>
          </tr>
      </table>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top height=262>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff height=300 id="showtables">
                      <FORM name='form_goodsbase' id='form_goodsbase' action='provider_goods_save.php' method=post  encType=multipart/form-data>
                        <input type="hidden" name="Action"    value="<?php echo $Action_value?>">
                        <INPUT type=hidden   name=act         value="<?php echo $_POST['act'];?>" >
                        <INPUT type=hidden   name=Where       value="<?php echo $_POST['Where'];?>" >
                        <!--INPUT type=hidden   name='Attr_num'  value="<?php echo $Attr_num?>"-->
                        <INPUT type=hidden   name='gid'       value="<?php echo $Gid?>">
                        <INPUT type=hidden   name='smallimg'  value="<?php echo $Sm_img?>">
                        <INPUT type=hidden   name='bigimg'    value="<?php echo $Bg_img?>">
                        <INPUT type=hidden   name='gimg'      value="<?php echo $G_img?>">
                        <INPUT type=hidden   name='middleimg' value="<?php echo $Mid_img?>">
                        <INPUT type=hidden value="<?php echo $_GET['url'];?>"  name=url> 
                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" bgColor=#f7f7f7 border=0 id="showtable_base">
                          <TBODY>
                            <TR>
                              <TD noWrap align=right width="17%">&nbsp;</TD>
                              <TD width="83%">&nbsp;</TD></TR>
                            
                            <TR>
                              <TD noWrap align=right class="p9orange">* <?php echo $Admin_Product[ProductName];//商品名称：?>：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','goodsname',$Goodsname,"  id='goodsname'   maxLength=50 size=40 ")?></TD>
                              </TR>
                              <TR>
                                <TD noWrap align=right width="17%">促銷廣告標語：</TD>
                                <TD colSpan=2><div style="width:340px;float:left"><?php echo $FUNCTIONS->Input_Box('text','sale_name',$sale_name,"  id='sale_name'   maxLength=50 size=40 ")?></div>
                                 <div style="float:left;width:auto;line-height:25px"> 顏色：</div><div style="float:left;width:auto">
                              <?php echo $FUNCTIONS->Input_Box('text','salename_color',$salename_color,"    maxLength=10 size=10   ")?></div></TD></TR>
                            <?php
                    if (!$_POST[doaction]=='copy'){
					?>
                            <!--TR>
                              <TD noWrap align=right><?php echo $Admin_Product[View];//查看?>：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','view_num',intval($View_num),"      maxLength=40 size=10 ")?> <?php echo $Admin_Product[Per];//次?></TD>
                            </TR-->
                            <TR>
                              <TD noWrap align=right class="p9orange">* <?php echo $Admin_Product[PrductClassName];//商品類別名稱?>：</TD>
                              <TD><?php echo $Char_class->get_page_select("bid",$Bid,"  class=\"trans-input\" onchange='getMoreAttrib(this.options[this.selectedIndex].value);getAttriClass(this.options[this.selectedIndex].value);'");//$Char_class->get_page_select("bid",$Bid," class='inputstyle' onchange=changecat(".$Bid.")");// $FUNCTIONS->$OptionSelect("select * from bclass order by top_id asc ",'bid','bid','catname',$Bid)?></TD>
                              </TR>
                            <!--TR>
                      <TD noWrap align=right>擴展分類：</TD>
                      <TD colSpan=2><input name="btn_class" type="button" id="btn_class" value="新增一個擴展分類">
					    <?php
						if ($_GET['Action'] == "Modi"){
							$c_sql = "select * from `{$INFO[DBPrefix]}goods_class` where gid='" . intval($_GET['gid']) . "'";
							$c_query = $DB->query($c_sql);
							$i = 1;
							while($c_row= $DB->fetch_array($c_query)){
						?>
							<div>
							<?php
							echo $Char_class->get_page_select("bid" . intval($i),$c_row['bid'],"  class=\"trans-input\" onchange='getMoreAttrib(this.options[this.selectedIndex].value);'");
							?>
							</div>
						<?php
								$i++;
							}
						}else{
							$i = 1;
						}
						?>
                        <div id="extclass"></div><input type="hidden" value="<?=$i?>" name="classcount" id="classcount"></TD>
                    </TR-->
                            <!--tr id="moreAttrib_td" style="Z-INDEX: 10;">
                              <TD width="17%" align="right" noWrap>擴充欄位：</TD>
                              <td>
                                <div id="show_moreAttrib_td" align="left"></div>						  </td>
                            </tr>
                            <TR>
                              <TD width="17%" align="right" noWrap>類別屬性：</TD>
                              <TD align=left noWrap id="showattribute"></TD>
                              </TR>
                            <TR-->
                              <TD noWrap align=right><?php echo $Admin_Product[Provider_name];//供貨商?>：</TD>
                              <TD colSpan=2><?php echo  $DisplayProviderState =  intval($_SESSION['LOGINADMIN_TYPE'])==2 ? $Provider_name  : $FUNCTIONS->select_type("select provider_name,provider_id from `{$INFO[DBPrefix]}provider` order by providerno  ","provider_id","provider_id","provider_name",intval($Provider_id));  ?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[ProductBand];//商品品牌：?>：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->select_type("select brandname,brand_id from `{$INFO[DBPrefix]}brand` order by brand_id asc  ","brand_id","brand_id","brandname",intval($Brand_id));  ?><?php //echo  // $FUNCTIONS->Input_Box('text','brand',$Brand,"      maxLength=40 size=40 ") ?></TD>
                              </TR>
                            <!--TR>
                      <TD align=right><?php echo $Admin_Product[Subject_name];//主题类别：?>：</TD>
            <TD colspan="2"><?php echo $FUNCTIONS->select_muliti_type("select subject_name,subject_id from `{$INFO[DBPrefix]}subject` where subject_open=1 order by subject_num desc ","subject_id","subject_id","subject_name",$SubjectId_array = explode(",",$Subject_id));?> <label>
            </label>			</TD>
                    </TR-->
                            <!--TR>
                      <TD align=right><?php echo $Admin_Product[NocarriageNum];//免運費件數：?>：</TD>
                      <TD colspan="2">
					  <select name="nocarriage"  class="trans-input">
					  <option value="0"><?php echo $Basic_Command['Please_Select'];?></option>
					  <?php for ($i=1;$i<1001;$i++) { ?>
  					  <option value="<?php echo $i;?>" <?php if ($Nocarriage==$i) { echo " selected=\"selected\" ";}?>><?php echo $i;?></option>
					  <?php } ?>
					  </select>					  </TD>
                    </TR-->
                            
                            <TR>
                              <TD noWrap align=right width="17%">&nbsp;</TD>
                              <TD colSpan=2><!--div id="goodsnametips" class="tips"><?php echo $Admin_Product[PleaseInputPrductName]?></div-->					  </TD></TR>
                            
                            <?php
					}
					?>
                    <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[Bn];//货号：?>：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','bn',$Bn,"  id='bn'    maxLength=30 size=30  ")?>
                                <!--div id="bntips" class="tips"><?php echo $Admin_Product[PleaseInputPrductBn]?></div-->					  </TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%">賣場編號：</TD>
                              <TD colSpan=2><?php echo $goodsno;?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%">國際碼：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','guojima',$guojima,"  id='guojima'   maxLength=50 size=40 ")?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%">型號：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','xinghao',$xinghao,"  id='xinghao'   maxLength=50 size=40 ")?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%" class="p9orange">* 重量：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','weight',$weight,"  id='weight'   maxLength=50 size=40 ")?>KG 淨重（含外包裝）</TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right class="p9orange">* 產地：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','chandi',$chandi,"  id='chandi'   maxLength=255 size=40 ")?>*</TD>
                              </TR>
                            <!--TR>
                              <TD noWrap align=right>ERP：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','ERP',$ERP,"  id='ERP'   maxLength=255 size=40 ")?></TD>
                              </TR-->
                            <!--TR>
                      <TD noWrap align=right width="17%">合作項目代號：</TD>
                      <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','oeid',$oeid,"  id='bn'    maxLength=30 size=30 ")?>
					  </TD>
 				    </TR-->
                            
                            
                            <TR>
                              <TD noWrap align=right width="17%" class="p9orange">* <label for="price"><?php echo $Admin_Product[ProductSPrice];//市場價格?>：</label></TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','price',$Price,"  id='price'   maxLength=\"20\" size=\"20\" ")?>
                                <div id="pricetips" class="tips"><?php echo $Admin_Product[WhatisPrice]?></div>					  </TD></TR>
                            <TR>
                              <TD noWrap align=right width="17%" class="p9orange">* <label for="price"><?php echo $Admin_Product[ProductNetPrice];//網購價?>：</label></TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','pricedesc',$Pricedesc,"  id='pricedesc'   maxLength=\"20\" onchange=\"getpmoney();\" size=\"20\"  ")?>
                                <div id="pricedesctips" class="tips"><?php echo $Admin_Product[WhatisPricedesc]?></div>					  </TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"><label for="price">成本價：</label></TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','cost',$cost,"  id='cost'   maxLength=\"20\" size=\"20\" onchange=\"getpmoney();\"  ")?>
                                
                                </TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"><label for="price">毛利：</label></TD>
                              <TD colSpan=2 id="pmoney"><?php echo $Pricedesc-$cost;?>
                                
                                </TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"><label for="price">促銷成本價：</label></TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','salecost',$salecost,"  id='salecost'   maxLength=\"20\" size=\"20\" onchange=\"getpmoney();\"  ")?>用於商品多件折扣、超值任選、買越多越便宜、滿額加購、商品加購，如果不設定請填寫0
                                
                                </TD>
                              </TR>
                            
                            <!--TR>
                      <TD noWrap align=right width="17%">會員價：</TD>
                      <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','memberprice',$memberprice,"  id='memberprice'   maxLength=\"20\" size=\"20\"  ")?>
					  </TD>
 				    </TR>
                    <TR>
                      <TD noWrap align=right width="17%"><span id="yui_3_2_0_3_1305162769688219">搭配紅利點數</span>：</TD>
                      <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','combipoint',$combipoint,"  id='combipoint'   maxLength=\"20\" size=\"20\"  ")?>
					  </TD>
 				    </TR-->
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[Unit];//计量单位：?>：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','unit',$Unit,"    maxLength=\"20\" size=\"20\" ")?>                      </TD></TR>
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[AlarmStorage];//库存警告：?>：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('radio_alarm','ifalarm',$Ifalarm,$Add=array("貨到通知","開放預購"))?></TD>
                              </TR>
                            <?php $DISPLAYalarm =  $Ifalarm==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR bgcolor="#FFFFFF" id=alarmshow <?php echo $DISPLAYalarm;?>>
                              <TD width="17%" align=right noWrap><b><?php echo $Admin_Product[Alarmnum];//警告数量：?></b>：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','alarmnum',$Alarmnum,"      maxLength=40 size=40 ")?></TD>
                              </TR>
                            
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[StorageNum];//库存数量：?>：</TD>
                              <TD colspan="2"><?php 
					  if ($_GET['Action'] == "Modi"){
						  echo $Storage;
					 ?>
                                <a href="javascript:void(0);" onclick="showWin('url','admin_goods_ajax_changestorage.php?gid=<?php echo $Gid?>','',750,450);">設置</a>
                                <?php
					  }else{
					 	 echo $FUNCTIONS->Input_Box('text','storage',$Storage,"      maxLength=40 size=40 ");
					  }
					  ?>
                                </TD>
                              </TR>
                            
                            
                            <TR>
                              <TD align=right><?php echo $Admin_Product[GlProduct];//指定相关产品：?>：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('radio','ifgl',$Ifgl,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?> <?php echo $Admin_Product[GlProduct_content] ;//（ 如果关闭指定相关产品选项，系统将默认本类产品为相关产品！）?> </TD>
                              </TR>
                            <!--TR>
                      <TD noWrap align=right>瀏覽等級：</TD>
                      <TD width="83%">
					  
					  <?php
					  $level_goods = array();
					  $goods_sql = "select * from `{$INFO[DBPrefix]}goods_userlevel` where gid='" . intval($gid) . "'";
						  $Query_goods    = $DB->query($goods_sql);
						  $ig = 0;
						  while($Rs_goods=$DB->fetch_array($Query_goods)){
							$level_goods[$ig]=$Rs_goods['levelid'];
							$ig++;
						  }
					  $Sql_level      = "select * from `{$INFO[DBPrefix]}user_level` order by level_id ";
					  $Query_level    = $DB->query($Sql_level);
					   while($Rs_level=$DB->fetch_array($Query_level)){
					   ?>
					   <input type="checkbox" name="userlevel[]" id="userlevel" value="<?php echo $Rs_level['level_id'];?>" <?php if (in_array($Rs_level['level_id'],$level_goods))  echo "checked";?>><?php echo $Rs_level['level_name'];?>
					   <?php
					   }
					  ?>					  </TD>
                    </TR-->
                            <TR>
                              <TD noWrap align=right><?php echo $Admin_Product[Mid_img];//商品缩略图?>：</TD>
                              <TD><INPUT  id="img"  type="file" size="40" name="img" >
                                &nbsp;&nbsp;&nbsp;&nbsp;*<span id="yui_3_2_0_1_13115562779589750" lang="ZH-CN" xml:lang="ZH-CN">圖片格式只能是</span><span lang="EN-US" xml:lang="EN-US">500px*500px</span><span lang="ZH-CN" xml:lang="ZH-CN">的</span><span lang="EN-US" xml:lang="EN-US">jpg</span><span lang="ZH-CN" xml:lang="ZH-CN">或</span><span lang="EN-US" xml:lang="EN-US">gif</span><span lang="ZH-CN" xml:lang="ZH-CN">檔案，需小於</span><span lang="EN-US" xml:lang="EN-US">200KB</span>                        <div id="imgtips" class="tips"><?php echo $Admin_Product[UploadIntro] ?></div>					  </TD></TR>
                            <?php if (is_file("../".$INFO['good_pic_path']."/".$Mid_img)){?>
                            
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>
                                <div id="Mid_img">
                                  &nbsp;<img src="<?php echo "../".$INFO['good_pic_path']."/".$Mid_img?>">
                                  <a href="javascript:delpic('Mid_img','<?php echo $Gid;?>','<?php echo $Del_img; ?>')" onClick="return confirm('<?php echo $Admin_Product['Del_Pic']?>')"><font color="#FF0000"><?php echo $Basic_Command['Del']?></font></a>					  </div>                      </TD>
                              </TR>
                            <?php } ?>
                            <!--TR>
                              <TD noWrap align=right><!?php echo $Admin_Product[Video_Url];?>：</TD>
                              <TD colSpan=2><!?php echo $FUNCTIONS->Input_Box('text','video_url',$Video_url,"      maxLength=200 size=120 ")?>
                                <div id="video_urltips" class="tips_big">請輸入商品影音資料URL位址，如 http://tw.g-utv.com/movie/utv.flv</div>					 </TD>
                              </TR-->
                            <TR>
                              <TD align=right valign="top" noWrap><?php echo $Admin_Product[Easy_intro];//简单描述：?>：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('textarea','intro',$Intro," cols=100 rows=3  ")?></TD>
                              </TR>
                            <TR  id=alarmshowcontent>
                              <TD width="17%" align=right noWrap valign="top">KeyWords：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('textarea','keywords',$Keywords," cols=100 rows=3  ")?></TD>
                              </TR>


                            <TR>
                              <TD width="17%" align=right valign="top" noWrap>商品詳細介紹：</TD>
                              <TD colspan="2" align=left valign="top" noWrap>
                                <?php
						$CKEditor = new CKEditor();
					    $CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						
						$CKEditor->config['width'] = 800;
						//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
						echo $code = $CKEditor->editor("FCKeditor1", $Body);
  						    ?> </TD>
                              </TR>
                            <TR>
                              <TD width="17%" align=right valign="top" noWrap>商品規格&使用方法：</TD>
                              <TD colSpan=2><?php
                              
							  $CKEditor = new CKEditor();
					    $CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						
						$CKEditor->config['width'] = 800;
						//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
						echo $code = $CKEditor->editor("cap_des", $cap_des);
  						    ?></TD>
                              </TR>							  
                            <TR>
                              <TD align=right valign="top" noWrap><?php echo 注意事項//使用規則：?>：</TD>
                              <TD colSpan=2><?php
							  
							  $CKEditor = new CKEditor();
					  $CKEditor->returnOutput = true;
						$CKEditor->basePath = OtherPach."/".Resources."/ckeditor/";
						
						$CKEditor->config['width'] = 800;
						//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
						echo $code = $CKEditor->editor("alarmcontent", $AlarmContent);
  						    ?></TD>
                              </TR>							  
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            </TBODY>
                          </TABLE>
                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" bgColor=#f7f7f7 border=0  id="showtable_cuxiao" style="display:none">
                          <TBODY>
                            <TR>
                              <TD noWrap align=right width="17%">&nbsp;</TD>
                              <TD width="83%">&nbsp;</TD></TR>
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[ifrecommend];//是否推荐：?>：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifrecommend',$Ifrecommend,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[ifspecial];//是否特价：?>：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('radio','ifspecial',$Ifspecial,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?> </TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[ifhot];//是否热卖：?>：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('radio','ifhot',$Ifhot,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                            
                            <tr>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[Point];//积分点数：?>：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','point',$Point,"      maxLength=\"10\" size=\"10\" ")?></TD></TR>
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[BonusProduct];//红利商品：?>：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio_bonus','ifbonus',$Ifbonus,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                            <?php
                    if ($right_array[1] == 1){
					?>
                            <TR>
                              <TD noWrap align=right width="17%">超值任選商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifxygoods',$ifxygoods,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("xyfun(1,'ifxy')","xyfun(0,'ifxy')"))?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%">任選商品數量：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','xycount',$xycount,"      maxLength=\"10\" size=\"10\" ")?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%">屬於超值任選商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifxy',$ifxy,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("xyfun(1,'ifxygoods')","xyfun(0,'ifxygoods')"))?></TD>
                              </TR>
                            <?php
					}
					if ($right_array[3] == 1){
					?>
                            <TR>
                              <TD noWrap align=right width="17%">是否是加購商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifchange',$ifchange,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                            <?php 
					}
					$DISPLAYbonus =  $Ifbonus==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR bgcolor="#FFFFCC" id=bonusshow <?php echo $DISPLAYbonus;?>>
                              <TD width="17%" align=right noWrap bgcolor="#FFFFFF"><?php echo $Admin_Product[BonusnumNum];//所需积分：?>：</TD>
                              <TD colspan="2" bgcolor="#FFFFFF"><?php echo $FUNCTIONS->Input_Box('text','bonusnum',$Bonusnum,"      maxLength=40 size=40 ")?>&nbsp;&nbsp;&nbsp;
                                <font color="#FF0000"><?php echo $Admin_Product[Bonusnum_content] ?></font></TD>
                              </TR>
                            <?php
                    if ($right_array[5] == 1){
					?>
                            <TR>
                              <TD noWrap align=right width="17%">是否是滿額加購商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifadd',$ifadd,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'addshow')","viewtr(0,'addshow')"))?></TD>
                              </TR>
                            <?php 
					
					$DISPLAYadd =  $ifadd==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR bgcolor="#FFFFFF" <?php echo $DISPLAYadd;?> id="addshow">
                              <TD noWrap align=right width="17%">額滿加購金額：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','addmoney',$addmoney,"      maxLength=10 size=10 ")?>
                                加購價格：<?php echo $FUNCTIONS->Input_Box('text','addprice',$addprice,"      maxLength=10 size=10 ")?>
                                </TD>
                              </TR>
                            <?php
					}
					if ($right_array[4] == 1){
					?>
                            <TR>
                              <TD noWrap align=right width="17%">額滿禮商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifpresent',$ifpresent,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'presentshow')","viewtr(0,'presentshow')"))?></TD>
                              </TR>
                            <?php $DISPLAYpresent =  $ifpresent==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; 
					
					?>
                            <TR bgcolor="#FFFFFF" <?php echo $DISPLAYpresent;?> id="presentshow">
                              <TD noWrap align=right width="17%">額滿禮金額：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','present_money',$present_money,"      maxLength=10 size=10 ")?>~~<?php echo $FUNCTIONS->Input_Box('text','present_endmoney',$present_endmoney,"      maxLength=10 size=10 ")?></TD>
                              </TR>
                            <?php
					}
					?>
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[JsProduct]?><!--集殺商品-->：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('radio_js','ifjs',$Ifjs,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?>					  </TD>
                              </TR>
                            <?php $DISPLAYjs =  $Ifjs==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR bgcolor="#FFFFFF" id=jsshow1  <?php echo $DISPLAYjs;?>>
                              <TD align=right noWrap><?php echo $Admin_Product[JsTime];?><!--集殺時效-->：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('text','begtime',$begtime," id=begtime   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?>
                                &nbsp;&nbsp; To&nbsp;&nbsp;        <?php echo $FUNCTIONS->Input_Box('text','endtime',$endtime," id=endtime     onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"      maxLength=12 size=12 ")?>
                                &nbsp;					  </TD>
                              </TR>
                            <TR bgcolor="#FFFFFF" id=jsshow2  <?php echo $DISPLAYjs;?>>
                              <TD align=right noWrap><?php echo $Admin_Product[JsPriceAndBegPrice]?><!--起購價集殺價-->：</TD>
                              <TD colspan="2">
                                <strong>I</strong>:&nbsp;
                                <?php echo $FUNCTIONS->Input_Box('text','onecount',$Js_count[0]," id=onecount       maxLength=12 size=12 ")?>件<?php echo $FUNCTIONS->Input_Box('text','oneprice',$Js_price[0]," id=oneprice       maxLength=12 size=12 ")?>
                                <br /><strong>II</strong>:
                                <?php echo $FUNCTIONS->Input_Box('text','twocount',$Js_count[1]," id=twocount       maxLength=12 size=12 ")?>件<?php echo $FUNCTIONS->Input_Box('text','twoprice',$Js_price[1]," id=twoprice       maxLength=12 size=12 ")?><br /><strong>III</strong>:
                                <?php echo $FUNCTIONS->Input_Box('text','threecount',$Js_count[2]," id=threecount       maxLength=12 size=12 ")?>件<?php echo $FUNCTIONS->Input_Box('text','threeprice',$Js_price[2]," id=threeprice       maxLength=12 size=12 ")?><br /><strong>IV</strong>:
                                <?php echo $FUNCTIONS->Input_Box('text','fourcount',$Js_count[3]," id=fourcount       maxLength=12 size=12 ")?>件<?php echo $FUNCTIONS->Input_Box('text','fourprice',$Js_price[3]," id=fourprice       maxLength=12 size=12 ")?><br /><strong>V</strong>:
                                <?php echo $FUNCTIONS->Input_Box('text','fivecount',$Js_count[4]," id=fivecount       maxLength=12 size=12 ")?>件<?php echo $FUNCTIONS->Input_Box('text','fiveprice',$Js_price[4]," id=fiveprice       maxLength=12 size=12 ")?></TD>
                              </TR>
                            <TR bgcolor="#FFFFFF" id=jsshow3  <?php echo $DISPLAYjs;?>>
                              <TD align=right noWrap><?php echo $Admin_Product[Js_totalnum]?><!--已累计件數-->：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','Js_totalnum',$Js_totalnum,"      maxLength=10 size=10 ")?></TD>
                              </TR>
                            <?php
					if ($right_array[2] == 1){
					?>
                            <TR>
                              <TD noWrap align=right>多件折扣：</TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('radio','ifsales',$ifsales,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'saleshow')","viewtr(0,'saleshow')"))?></TD>
                              </TR>
                            <?php $DISPLAYsales =  $ifsales==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR <?php echo $DISPLAYsales;?> id="saleshow">
                              <TD noWrap align=right>
                                
                                </TD>
                              <TD><select name="sale_subject">
                                <?php
                      $Sql_sub   = " select subject_name,subject_id,subject_open from `{$INFO[DBPrefix]}sale_subject` order by subject_num desc ";
					  $Query_sub = $DB->query($Sql_sub);
					  while ($Rs_sub = $DB->fetch_array($Query_sub)){
					  ?>
                                <option value="<?php echo $Rs_sub['subject_id'];?>" <?php if($Rs_sub['subject_id']==$sale_subject) echo "selected";?>><?php echo $Rs_sub['subject_name'];?></option>
                                <?php
					  }
					  ?>
                                </select>
                                每件<?php echo $FUNCTIONS->Input_Box('text','sale_price',$sale_price,"      maxLength=10 size=10 ")?>元</TD>
                              </TR>
                            <?php
					}
					if ($right_array[6] == 1){
					?>
                            <TR>
                              <TD noWrap align=right width="17%">整點促銷商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifsaleoff',$ifsaleoff,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'saleoffshow')","viewtr(0,'saleoffshow')"))?>（未到促銷時間段不能進行購買活動）</TD>
                              </TR>
                            <?php $DISPLAYsaleoff =  $ifsaleoff==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR id="saleoffshow" <?php echo $DISPLAYsaleoff;?>>
                              <TD noWrap align=right width="17%">促銷時間：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','saleoff_startdate',$saleoff_startdate," id=saleoff_startdate   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?><select name="start_h">
                                <?php
			for($i=0;$i<=23;$i++){
            ?>
                                <option value="<?php echo $i;?>" <?php if($start_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
			}
			?>      
                                </select>
                                時
                                <select name="start_i">
                                  <?php
			for($i=0;$i<=59;$i++){
            ?>
                                  <option value="<?php echo $i;?>" <?php if($start_i==$i) echo "selected";?>><?php echo $i;?></option>
                                  <?php
			}
			?>      
                                  </select>
                                分
                                &nbsp;&nbsp; To&nbsp;&nbsp;        <?php echo $FUNCTIONS->Input_Box('text','saleoff_enddate',$saleoff_enddate," id=saleoff_enddate     onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"      maxLength=12 size=12 ")?>
                                <select name="end_h">
                                  <?php
			for($i=0;$i<=23;$i++){
            ?>
                                  <option value="<?php echo $i;?>" <?php if($end_h==$i) echo "selected";?>><?php echo $i;?></option>
                                  <?php
			}
			?>      
                                  </select>
                                時
                                <select name="end_i">
                                  <?php
			for($i=0;$i<=59;$i++){
            ?>
                                  <option value="<?php echo $i;?>" <?php if($end_i==$i) echo "selected";?>><?php echo $i;?></option>
                                  <?php
			}
			?>      
                                  </select>
                                分
                                
                                </TD>
                              </TR>
                            <?php
					}
					if ($right_array[7] == 1){
					?>
                            <TR>
                              <TD noWrap align=right width="17%">整點促銷商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','iftimesale',$iftimesale,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'timesaleshow')","viewtr(0,'timesaleshow')"))?>（在促銷期間商品售價為促銷價格）</TD>
                              </TR>
                            <?php $DISPLAYtimesale =  $iftimesale==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR id="timesaleshow" <?php echo $DISPLAYtimesale;?>>
                              <TD noWrap align=right width="17%">促銷時間：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','timesale_starttime',$timesale_startdate," id=timesale_starttime   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?><select name="timesalestart_h">
                                <?php
			for($i=0;$i<=23;$i++){
            ?>
                                <option value="<?php echo $i;?>" <?php if($timesalestart_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
			}
			?>      
                                </select>
                                時
                                <select name="timesalestart_i">
                                  <?php
			for($i=0;$i<=59;$i++){
            ?>
                                  <option value="<?php echo $i;?>" <?php if($timesalestart_i==$i) echo "selected";?>><?php echo $i;?></option>
                                  <?php
			}
			?>      
                                  </select>
                                分
                                &nbsp;&nbsp; To&nbsp;&nbsp;        <?php echo $FUNCTIONS->Input_Box('text','timesale_endtime',$timesale_enddate," id=timesale_endtime     onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"      maxLength=12 size=12 ")?>
                                <select name="timesaleend_h">
                                  <?php
			for($i=0;$i<=23;$i++){
            ?>
                                  <option value="<?php echo $i;?>" <?php if($timesaleend_h==$i) echo "selected";?>><?php echo $i;?></option>
                                  <?php
			}
			?>      
                                  </select>
                                時
                                <select name="timesaleend_i">
                                  <?php
			for($i=0;$i<=59;$i++){
            ?>
                                  <option value="<?php echo $i;?>" <?php if($timesaleend_i==$i) echo "selected";?>><?php echo $i;?></option>
                                  <?php
			}
			?>      
                                  </select>
                                分
                                
                                促銷價格<?php echo $FUNCTIONS->Input_Box('text','saleoffprice',$saleoffprice,"      maxLength=10 size=10 ")?>
                                </TD>
                              </TR>
                            <?php
					}
					?>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            </TBODY>
                          </TABLE>
                        
                        
                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" bgColor=#f7f7f7 border=0  id="showtable_cap" style="display:none">
                          <TBODY>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            </TBODY>
                          </TABLE>
                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" bgColor=#f7f7f7 border=0  id="showtable_peisong" style="display:none">
                          <TBODY>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            
                            <TR>
                              <TD width="17%" align=right noWrap>是否滿額免運費：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('radio','ifmood',$ifmood,$Add=array("是","不是"))?></TD>
                              </TR>
                            
                            <TR>
                              <TD noWrap align=right><input value="0" name="trans_type" type="hidden" /><input value="3" name="transtype"  type="hidden"/></TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            </TBODY>
                          </TABLE>
                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" bgColor=#f7f7f7 border=0  id="showtable_des" style="display:none">
                          <TBODY>
                            
                            
                            
                            
                            
                            <TR>
                              <TD align=right valign="middle" noWrap>&nbsp;</TD>
                              <TD colSpan=2>&nbsp;</TD>
                              </TR>
                            
                            
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                            <TD colSpan=2>&nbsp;            </TD></TR></TABLE>
                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" bgColor=#f7f7f7 border=0  id="showtable_tag" style="display:none">
                          <TBODY>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            <TR>
                              <TD width="17%" align=right noWrap>TAG：</TD>
                              <TD colSpan=2><?php
					  $tag_goods = array();
					  $tag_sql = "select * from `{$INFO[DBPrefix]}goods_tag` where gid='" . intval($gid) . "'";
						  $Query_tag= $DB->query($tag_sql);
						  $ig = 0;
						  while($Rs_tag=$DB->fetch_array($Query_tag)){
							$tag_goods[$ig]=$Rs_tag['tagid'];
							$ig++;
						  }
					  $Sql_tag      = "select * from `{$INFO[DBPrefix]}tag` order by tagid ";
					  $Query_tags    = $DB->query($Sql_tag);
					   while($Rs_tags=$DB->fetch_array($Query_tags)){
					   ?>
                                <input type="checkbox" name="tags[]" id="tags" value="<?php echo $Rs_tags['tagid'];?>" <?php if (in_array($Rs_tags['tagid'],$tag_goods))  echo "checked";?>><?php echo $Rs_tags['tagname']?>
                                <?php
					   }
					  ?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            </TBODY>
                          </TABLE></FORM>
                      <div id="showajax"></div>
                  </TD></TR></TABLE>
              
            </TD></TR></TABLE></TD>
    </TR>
</TBODY></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
<script language="javascript">
 function delpic(Element,gid,value){
 	var url    = "./provider_goods_save.php?Action=delPic&gid="+gid+"&picName="+value;
 	var show = document.getElementById(Element);
 	AjaxGetRequestInnerHtml(url,show);
 }
 function AjaxGetRequestInnerHtml(url,show){
 	if (typeof(url) == 'undefined'){
 		    return false;
 	}
 	if (typeof(show) == 'undefined'){
 		    return false;
 	}
 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
 	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
 	ajax.onreadystatechange = function() {
 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		    if (ajax.readyState == 4 && ajax.status == 200) {
 		    	show.innerHTML =  ajax.responseText;
 		    }
 		    }
 		    ajax.send(null);
 		    }
 </script>
 <script language="javascript">
 getMoreAttrib('<?php echo $Bid?>');
 getAttriClass('<?php echo $Bid?>');
 function getMoreAttrib(bid){
 	if (typeof(bid) == 'undefined'){
 		    return false;
 	}
 	var url = "admin_goods_getmoreattrib.php?bid="+ bid + "&goodattrI=<?php echo base64_encode($goodattrI)?>";
 	var show = document.getElementById("show_moreAttrib_td");
 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
 	ajax.onreadystatechange = function() {
 		//alert ('d');
 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		            	if (ajax.readyState == 4 && ajax.status == 200) {
 		            		        	
 		            		        		        		      show.innerHTML = ajax.responseText;
 		            		        		        		              		          }
 		            		        		        		              		                  		          //alert (ajax.responseText);
 		            		        		        		              		                  		                  	}
 		            		        		        		              		                  		                  	        	ajax.send(null);
 	}
 function getAttriClass(bid){
 	if (typeof(bid) == 'undefined'){
 		    return false;
 	}
 	var url = "admin_goods_attributeclass.php?bid="+ bid + "&gid=<?php echo intval($_GET['gid']);?>";
 	var show = document.getElementById("showattribute");
 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
 	ajax.onreadystatechange = function() {
 		//alert ('d');
 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		            	if (ajax.readyState == 4 && ajax.status == 200) {
 		            		        		        		      show.innerHTML = ajax.responseText;
 		            		        		        		              		          }
 		            		        		        		              		                  		          //alert (ajax.responseText);
 		            		        		        		              		                  		                  	}
 		            		        		        		              		                  		                  	        	ajax.send(null);
 	}
</script>
<script language="javascript">
$(document).ready(function() {
						   $('.css').on('click', function(evt){ 
        $('#jquiCSS').attr('href','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/'+this.innerHTML+'/jquery-ui.css');
        $('.css').removeClass('sel');
        $(this).addClass('sel');
    });

	 $('#salename_color').colorpicker({showOn:'focus'});
	$('#btn_class').click(function() {
	var counts = parseInt($('#classcount').attr("value"));
	//alert(counts);
			$.ajax({
				url: "admin_goods_ajaxclass.php",
				data: 'count=' + counts,
				type:'get',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				    //$('#showsize').html(msg);
					$('#classcount').attr("value",counts+1);
					$(msg).appendTo('#extclass')
				}
			});
		});
	
	
})
function viewtr(a,ele)
	{
		var show = document.getElementById(ele);
		if(a == 1){
			show.style.display="";
		}else{
			show.style.display="none";
		}
	}
function xyfun(a,ele)
	{
		var show = document.getElementsByName(ele);
		if(a == 1){
			show[1].checked = true;
		}
	}
function showtable(ele){
	var show = document.getElementById("showtables");
	var tables = show.getElementsByTagName("table");
	var l = tables.length;
	var i=0;
	for(i=0;i<l;i++){
		
		if (tables[i].id == ele ){
			tables[i].style.display = "block";
		}else{
			tables[i].style.display = "none";
		}
		
	}
	document.getElementById("showsave").style.display="block";
}
function showtajaxfun(name){
	showtable("");
	var ajaxurl = "";
	switch(name){
		case "memberprice":
			ajaxurl = "admin_goods_ajax_goodsmemberprice.php";
			break;
		case "goodsdetail":
			ajaxurl = "admin_goods_ajax_detail.php";
			break;
		case "storage":
			ajaxurl = "admin_goods_ajax_attribstorage.php";
			break;
		case "attrib":
			ajaxurl = "admin_goods_ajax_attrib.php";
			break;
		case "morepic":
			ajaxurl = "admin_goods_ajax_pic.php";
			break;	
		case "goodslink":
			ajaxurl = "admin_goods_ajax_link.php";
			break;	
			
		case "xygoods":
			ajaxurl = "admin_goods_ajax_xygoods.php";
			break;	
		case "changegoods":
			ajaxurl = "admin_goods_ajax_changegoods.php";
			break;	
		case "goodssaleoffe":
			ajaxurl = "admin_goods_ajax_saleoffe.php";
			break;		
	}
	document.getElementById("showsave").style.display="none";
	$.ajax({
				url: ajaxurl,
				data: 'goods_id=<?=$Gid?>&gid=<?=$Gid?>',
				type:'get',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				    $('#showajax').html(msg);
					//$('#classcount').attr("value",counts+1);
					//$(msg).appendTo('#extclass')
				}
	});
}

function getpmoney(){
	$('#pmoney').html($('#pricedesc').attr("value")-$('#cost').attr("value"));	
}
function showpage(url,data){
$.ajax({
				url: url,
				data: data,
				type:'get',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				    $('#msg').html(msg);
					//$('#classcount').attr("value",counts+1);
					//$(msg).appendTo('#extclass')
				}
	});
}
</script>
</BODY></HTML>
