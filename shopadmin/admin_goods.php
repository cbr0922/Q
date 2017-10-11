<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
function num($no){
	return str_repeat("0",6-strlen($no)) . $no;
}
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
		$en_name    =  trim($Result['en_name']);
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
		$ttype  =intval($Result['ttype']);
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
		$Bg_img     =  $Result['bigimg'];
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
		$component    =  trim($Result['component']);
		$capability    =  trim($Result['capability']);
		$cap_des    =  trim($Result['cap_des']);
		$goodsno = trim($Result['goodsno']);
		$salename_color = trim($Result['salename_color']);
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
		$ifmood    =  intval($Result['ifmood']);
		$addtransmoney    =  trim($Result['addtransmoney']);
		$transtypemonty    =  trim($Result['transtypemonty']);
		$memberprice    =  trim($Result['memberprice']);
		$combipoint    =  trim($Result['combipoint']);
		$iftogether    =  trim($Result['iftogether']);
		$ifbelate    =  $Result['ifbelate'];
		$guojima    =  trim($Result['guojima']);
		$xinghao  =trim($Result['xinghao']);
		$weight  =trim($Result['weight']);
		$shopclass  =trim($Result['shopclass']);
		$shopid  =trim($Result['shopid']);
		$salecost  =trim($Result['salecost']);
		$month  =trim($Result['month']);
		$ifappoint  =trim($Result['ifappoint']);
		$appoint_send  =trim($Result['appoint_send']);
		$appoint_sendtype  =trim($Result['appoint_sendtype']);
		$chandi  =trim($Result['chandi']);
		$ERP  =trim($Result['ERP']);
		$subjectcontent  =trim($Result['subjectcontent']);
		$ifpack    =  intval($Result['ifpack']);
		$ifshui    =  intval($Result['ifshui']);
		$saleoffcount    =  intval($Result['saleoffcount']);
		$ifgoodspresent    =  intval($Result['ifgoodspresent']);
		$freetran    =  intval($Result['freetran']);
		$presentcount    =  intval($Result['presentcount']);
		$brand_bid    =  intval($Result['brand_bid']);
		$orgno    =  trim($Result['orgno']);
		$pm    =  trim($Result['pm']);
		$department    =  trim($Result['department']);
		$brandbids    =  trim($Result['brandbids']);
		$salecontent    =  trim($Result['salecontent']);
		$salestartdate    =  trim($Result['salestartdate']);
		$saleenddate    =  trim($Result['saleenddate']);
		
		if ($Result['pubstarttime']!=""){
			 $pubstarttime    =  date("Y-m-d",trim($Result['pubstarttime']));
			$pubstart_h    =  date("H",trim($Result['pubstarttime']));
			$pubstart_i    =  date("i",trim($Result['pubstarttime']));
		}
		if ($Result['pubendtime']!=""){
			$pubendtime    =  date("Y-m-d",trim($Result['pubendtime']));
			$pubend_h    =  date("H",trim($Result['pubendtime']));
			$pubend_i    =  date("i",trim($Result['pubendtime']));
		}
		if ($Result['saleoff_starttime']==""){
			$saleoff_startdate    =  date("Y-m-d",trim(time()));
			$start_h    =  0;
			$start_i    =  0;
		}else{
			$saleoff_startdate    =  date("Y-m-d",trim($Result['saleoff_starttime']));
			$start_h    =  date("H",trim($Result['saleoff_starttime']));
			$start_i    =  date("i",trim($Result['saleoff_starttime']));
		}
		if ($Result['saleoff_endtime']==""){
			$saleoff_enddate    =  date("Y-m-d",trim(time()+60*60*24*7));
			$end_h    =  23;
			$end_i    =  59;
		}else{
			$saleoff_enddate    =  date("Y-m-d",trim($Result['saleoff_endtime']));
			$end_h    =  date("H",trim($Result['saleoff_endtime']));
			$end_i    =  date("i",trim($Result['saleoff_endtime']));
		}
		if ($Result['appoint_starttime']!=""){
			$appoint_startdate    =  date("Y-m-d",trim($Result['appoint_starttime']));
			$appoint_start_h    =  date("H",trim($Result['appoint_starttime']));
			$appoint_start_i    =  date("i",trim($Result['appoint_starttime']));
		}
		if ($Result['appoint_endtime']!=""){
			$appoint_enddate    =  date("Y-m-d",trim($Result['appoint_endtime']));
			$appoint_end_h    =  date("H",trim($Result['appoint_endtime']));
			$appoint_end_i    =  date("i",trim($Result['appoint_endtime']));
		}
		if ($Result['timesale_starttime']==0){
			$timesale_startdate    =  date("Y-m-d",trim(time()));
			$timesalestart_h    =  0;
			$timesalestart_i    =  0;
		}else{
			$timesale_startdate    =  date("Y-m-d",trim($Result['timesale_starttime']));
			$timesalestart_h    =  date("H",trim($Result['timesale_starttime']));
			$timesalestart_i    =  date("i",trim($Result['timesale_starttime']));
		}
		if ($Result['timesale_endtime']==0){
			$timesale_enddate    =  date("Y-m-d",trim(time()+60*60*24*7));
			$timesaleend_h    =  23;
			$timesaleend_i    =  59;
		}else{
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
		echo "<script language=javascript>alert('".$Admin_Product[NoYourPriProduct]."');window.close();</script>";
		exit;
	}
}else{
	if($_SESSION['LOGINADMIN_TYPE']==1 && ($_SESSION['sa_type']==0 || $_SESSION['sa_type']==1)){
		$FUNCTIONS->sorry_back("Error.php","");
		exit;
	}
	$Query_b = $DB->query("select max(goodsno) as maxno from `{$INFO[DBPrefix]}goods`");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = $Result_b['maxno'];
	$goodsno = num(intval($maxno)+1);
	$Action_value = "newInsert";
	$Action_say   = $Admin_Product[CreateProduct] ; //新建产品
	$OptionSelect = "select_type_onchange";
	$iftransabroad=1;
	$trans_type=0;
	$transtype    = 3;
	$ifmood    =  1;
	$iftogether    =  1;
}
if ($_POST[doaction]=='copy'){
	if($_SESSION['LOGINADMIN_TYPE']==1 && ($_SESSION['sa_type']==0 || $_SESSION['sa_type']==1)){
		$FUNCTIONS->sorry_back("Error.php","");
		exit;
	}
	$Action_value = "Copy";
	$Action_say   = $Admin_Product[CloneProduct]; //复制商品
}
include RootDocumentShare."/cache/Productclass_show.php";
if (intval($_SESSION['LOGINADMIN_TYPE'])==2){
	$Provider_id = intval($_SESSION['sa_id']);
	$Add_Input = "<input type='hidden' name='provider_ip' value='".$Provider_id."'>";
	$Provider_name = trim($_SESSION['Admin_Sa'])."&nbsp;[".$_SESSION['Provider_thenum']."]";
}
$Sql_right      = "select * from `{$INFO[DBPrefix]}vright`";
$Query_right    = $DB->query($Sql_right);
while($Rs_right = $DB->fetch_array($Query_right)){
	$right_array[$Rs_right['id']] = $Rs_right['ifopen'];
}
$class_array = array();
	$i = 0;
if (is_array($op_class_array)){
	foreach($op_class_array as $k=>$v){
		$class_array[$i] = $v;
		$i++;
		$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($v));
		if ($Next_ArrayClass!=0){
			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$Next_ArrayClass      = array_unique($Next_ArrayClass);
			if (is_array($Next_ArrayClass)){
				foreach($Next_ArrayClass as $kk=>$vv){
					$class_array[$i] = 	$vv;
					$i++;
				}
			}
		}
	}
}
if(($_SESSION['LOGINADMIN_TYPE']==1 && !in_array($Bid,$class_array) && intval($_GET['gid'])>0) && count($class_array)>0){
	echo "<script language=javascript>alert('您對此商品沒有管理權限');history.back(-1);</script>";
	exit;
}
//print_r($right_array);
//echo $goodattrI;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say?>
</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"><!--編輯器會有bug onLoad="addMouseEvent();"-->
<div id="fullBg"></div>
<div id="msg">
<div id="close"></div>
<div id="ctt"></div>
</div>
<script src="../js/jquery.min.1.9.1.js"></script>
<?php include_once "head.php";?>
<link href="../css/uploadfile.css" rel="stylesheet">
<script src="../js/jquery.uploadfile.min.js"></script>

	<!-- Redactor is here -->
	<link rel="stylesheet" href="../Resources/redactor-js-master/redactor/redactor.css" />
	<script src="../Resources/redactor-js-master/redactor/redactor.js"></script>
   <!-- Plugin -->
          <script src="/Resources/redactor-js-master/redactor/plugins/source.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/table.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fullscreen.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontsize.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontfamily.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontcolor.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/inlinestyle.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/video.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/properties.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/textdirection.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/imagemanager.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/alignment/alignment.js"></script>
          <link rel="stylesheet" href="../Resources/redactor-js-master/redactor/plugins/alignment/alignment.css" />
    <!--/ Plugin -->
    <?php if ($_GET['Action']=='Modi' || $_POST[doaction]=='copy'){?>
	<script type="text/javascript">
	$(document).ready(
		function()
		{
			$('#redactor').redactor({
				imageUpload: '../Resources/redactor-js-master/demo/scripts/image_upload.php',
				imageManagerJson: '../Resources/redactor-js-master/demo/scripts/image_json.php',
				plugins: ['source','imagemanager', 'video','fontsize','fontcolor','alignment','fontfamily','table','textdirection','properties','inlinestyle','fullscreen'],
				imagePosition: true,
                imageResizable: true,
				<?php
				if ($_GET['gid']!="" && $_GET['Action']=='Modi' && $_POST['doaction']!='copy'){
				?>
				autosave: 'admin_goods_save.php?act=autosave1&gid=<?php echo $_GET['gid'];?>',
				callbacks: {
					autosave: function(json)
					{
						 console.log(json);
					}
				}
				<?php
				}
				?>
			});
			$('#redactor1').redactor({
				imageUpload: '../Resources/redactor-js-master/demo/scripts/image_upload.php',
				imageManagerJson: '../Resources/redactor-js-master/demo/scripts/image_json.php',
				plugins: ['source','imagemanager', 'video','fontsize','fontcolor','alignment','fontfamily','table','textdirection','properties','inlinestyle','fullscreen'],
				imagePosition: true,
                imageResizable: true,
				<?php
				if ($_GET['gid']!="" && $_GET['Action']=='Modi' && $_POST['doaction']!='copy'){
				?>
				autosave: 'admin_goods_save.php?act=autosave2&gid=<?php echo $_GET['gid'];?>',
				callbacks: {
					autosave: function(json)
					{
						 console.log(json);
					}
				}
				<?php
				}
				?>
			});
			$('#redactor2').redactor({
				imageUpload: '../Resources/redactor-js-master/demo/scripts/image_upload.php',
				imageManagerJson: '../Resources/redactor-js-master/demo/scripts/image_json.php',
				plugins: ['source','imagemanager', 'video','fontsize','fontcolor','alignment','fontfamily','table','textdirection','properties','inlinestyle','fullscreen'],
				imagePosition: true,
                imageResizable: true,
				<?php
				if ($_GET['gid']!="" && $_GET['Action']=='Modi' && $_POST['doaction']!='copy'){
				?>
				autosave: 'admin_goods_save.php?act=autosave3&gid=<?php echo $_GET['gid'];?>',
				callbacks: {
					autosave: function(json)
					{
						 console.log(json);
					}
				}
				<?php
				}
				?>
			});
		}
	);
	</script>
	<?php }?>
<SCRIPT language=javascript>
function toFirstCheck(){
	document.adminForm.action = "admin_goods_save.php";
	document.adminForm.act.value="FirstCheck";
	document.adminForm.submit();
}
function toSecondCheck(){
	document.adminForm.action = "admin_goods_save.php";
	document.adminForm.act.value="SecondCheck";
	document.adminForm.submit();
}
function toCheck(){
	document.adminForm.action = "admin_goods_save.php";
	document.adminForm.act.value="Check";
	document.adminForm.submit();
}
function toNoCheck(){
	showWin('url','admin_goods_nocheck.php','',300,200);
}
	function checkform(){
		if (chkblank(form_goodsbase.goodsname.value)){
			alert('<?php echo $Admin_Product[PleaseInputPrductName]?>'); //请输入商品名称
			form_goodsbase.goodsname.focus();
			return;
		}
		if (chkblank(form_goodsbase.bn.value) || form_goodsbase.bn.value.length>50){
			alert('<?php echo $Admin_Product[PleaseInputPrductBn]?>');  //请输入商品货号
			form_goodsbase.bn.focus();
			return;
		}
		if (checkbn()==false){
			form_goodsbase.bn.focus();
			return;
		}
		if (form_goodsbase.bid.value==0){
			alert('<?php echo $Admin_Product[PleaseSelectPrductClassName]?>'); //请选择商品所属类别
			form_goodsbase.bid.focus();
			return;
		}
		var pricedesc;
		pricedesc=isnum(form_goodsbase.pricedesc.value);
		if (pricedesc<=0){
			alert('請輸入正確的網路價格'); //请输入正确的商品价格
			form_goodsbase.pricedesc.value="";
			form_goodsbase.pricedesc.focus();
			return;
		}
		<?php if ($_GET['Action']=='Modi' || $_POST[doaction]=='copy'){?>

		if (chkblank(form_goodsbase.brand_id.value) || form_goodsbase.brand_id.value.length==0){
			alert('<?php echo $Admin_Product[PleaseInputPrductBand]?>'); //请输入商品品牌
			form_goodsbase.brand.focus();
			return;
		}
/*		if (chkblank(form_goodsbase.chandi.value)){
			alert('請填寫產地'); //请输入商品名称
			form_goodsbase.chandi.focus();
			return;
		}
		if (chkblank(form_goodsbase.weight.value)){
			alert('請填寫重量'); //请输入商品名称
			form_goodsbase.weight.focus();
			return;
		}
		if (chkblank(form_goodsbase.unit.value)){
			alert('請填寫計量單位'); //请输入商品名称
			form_goodsbase.unit.focus();
			return;
		}*/

/*
		var tmp_price;
		tmp_price=ismoney(form_goodsbase.price.value);
		if (tmp_price<=0){
			alert('<?php echo $Admin_Product[PleaseInputRightPrductPrice]?>'); //请输入正确的商品价格
			form_goodsbase.price.value="";
			form_goodsbase.price.focus();
			return;
		}*/

		<?php
		if($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
		?>
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
				return;
			}
		}
		<?php
		}
		?>
		if(document.getElementsByName("iftimesale")[0].checked==true){
			//alert("a");
	//if ($('#refundtypes').attr("value")==""){
		saleoffprice=isnum(form_goodsbase.saleoffprice.value);
		//alert(saleoffprice);
			if (saleoffprice<=0){
				alert('請輸入正確的促銷價格'); //请输入正确的商品价格
				form_goodsbase.saleoffprice.value="";
				form_goodsbase.saleoffprice.focus();
				return;
			}
		}
		//alert(document.getElementsByName("iftimesale")[0].checked);
		if(document.getElementsByName("ifsales")[0].checked==true){
			//alert("a");
	//if ($('#refundtypes').attr("value")==""){
		sale_price=isnum(form_goodsbase.sale_price.value);
		//alert(saleoffprice);
			if (sale_price<=0){
				alert('請輸入正確的折扣價格'); //请输入正确的商品价格
				form_goodsbase.sale_price.value="";
				form_goodsbase.sale_price.focus();
				return;
			}
		}
		if(document.getElementsByName("ifbonus")[0].checked==true){
			//alert("a");
	//if ($('#refundtypes').attr("value")==""){
		bonusnum=isnum(form_goodsbase.bonusnum.value);
		//alert(saleoffprice);
			if (bonusnum<=0){
				alert('請輸入正確的點數'); //请输入正确的商品价格
				form_goodsbase.bonusnum.value="";
				form_goodsbase.bonusnum.focus();
				return;
			}
		}
		if(document.getElementsByName("ifadd")[0].checked==true){
			//alert("a");
	//if ($('#refundtypes').attr("value")==""){
		addmoney=isnum(form_goodsbase.addmoney.value);
		//alert(saleoffprice);
			if (addmoney<=0){
				alert('請輸入正確的加購金額'); //请输入正确的商品价格
				form_goodsbase.addmoney.value="";
				form_goodsbase.addmoney.focus();
				return;
			}
			addprice=isnum(form_goodsbase.addprice.value);
		//alert(saleoffprice);
			if (addprice<=0){
				alert('請輸入正確的加購價格'); //请输入正确的商品价格
				form_goodsbase.addprice.value="";
				form_goodsbase.addprice.focus();
				return;
			}
		}
		if(document.getElementsByName("ifpresent")[0].checked==true){
			//alert("a");
	//if ($('#refundtypes').attr("value")==""){
		present_money=isnum(form_goodsbase.present_money.value);
		//alert(saleoffprice);
			if (present_money<=0){
				alert('請輸入正確的額滿禮金額'); //请输入正确的商品价格
				form_goodsbase.present_money.value="";
				form_goodsbase.present_money.focus();
				return;
			}
			present_endmoney=isnum(form_goodsbase.present_endmoney.value);
		//alert(saleoffprice);
			if (present_endmoney<=0){
				alert('請輸入正確的額滿禮金額'); //请输入正确的商品价格
				form_goodsbase.present_endmoney.value="";
				form_goodsbase.present_endmoney.focus();
				return;
			}
		}
		<?php }?>
		form_goodsbase.action="admin_goods_save.php";
		form_goodsbase.submit();
	}
	function changecat(){
		form_goodsbase.action="admin_goods.php";
		//save();
		form_goodsbase.submit();
	}
	var gid = "" ;
	function msgpicmore(Action_value,gid){
		if (Action_value != 'Update'){
			alert('<?php echo $Admin_Product[PleaseSaveProductPic] ?>') ;  //请先保存商品后，再上传多图！
		}else{
			location.href= "admin_goods_pic.php?good_id=" + gid ;
		}
	}
	function goodcolor(Action_value,gid){
		if (Action_value != 'Update'){
			alert('<?php echo $Admin_Product[PleaseSaveProductPic] ?>') ;  //请先保存商品后，再上传多图！
		}else{
			location.href= "admin_goodcolor_pic.php?good_id=" + gid ;
		}
	}
	function goodsdetail(Action_value,gid){
		if (Action_value != 'Update'){
			alert('<?php echo $Admin_Product[PleaseSaveProductPic] ?>') ;  //请先保存商品后，再上传多图！
		}else{
			location.href= "admin_goodsdetail_list.php?goods_id=" + gid ;
		}
	}
	function upIndex(Action_value,gid){
		if (Action_value == 'Update'){
	  	location.href= "admin_goods_up.php?good_id=" + gid ;
		//alert('本功能将在以后开放,届时您可以将本站的产品资料提交到行业总站上！');
		return ;
		}
	}
	function MoreAttrib(gid){
	  	location.href= "admin_goods_attrib.php?good_id=" + gid ;
	}
	function AttribStorage(gid){
	  	location.href= "admin_goods_attribstorage.php?gid=" + gid ;
	}
	function memberprice(Action_value,gid)
	{
		if (Action_value != 'Update'){
			alert('<?php echo $Admin_Product[PleaseSaveProductToPrice]?>') ;  //请先保存商品后，再设定会员价格！
		}else{
			location.href= "admin_memberprice.php?gid=" + gid ;
		}
	}
	function monthprice(Action_value,gid)
	{
		if (Action_value != 'Update'){
			alert('<?php echo $Admin_Product[PleaseSaveProductToPrice]?>') ;  //请先保存商品后，再设定会员价格！
		}else{
			location.href= "admin_monthprice.php?gid=" + gid ;
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
		var goodsid = form_goodsbase.gid.value;
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
<link id="jquiCSS" rel="stylesheet" href="../css/jquery-ui-1.9.2.custom.min.css" type="text/css" media="all">
<link id="jquiCSS" rel="stylesheet" href="../css/evol.colorpicker.css" type="text/css" media="all">
<style type="text/css">
.picture_pic img{
	width: expression(this.width > 250 ? 250: true);
    max-width: 250px;
    height: expression(this.height > 250 ? 250: true);
    max-height:250px;
	opacity:1;
}
</style>
<script type="text/javascript" src="../js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="../js/evol.colorpicker.js"></script>
<FORM name="adminForm" id="adminForm" action='' method=post>
<INPUT type=hidden name="act" id="act">
<INPUT type=hidden name="cid[]" id="cid" value="<?php echo $Gid?>">
<INPUT type=hidden name="nocheckreason" id="nocheckreason">
<INPUT type=hidden name=Where>
<input type=hidden name=doaction >
<INPUT type=hidden value=0  name=boxchecked>
<INPUT type=hidden value="<?php echo ($_GET['url']);?>"  name=url>
<INPUT id='cb'  type="checkbox" value='<?php echo $Gid?>' name=cid[] style="display:none" checked="checked" />
</FORM>
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
            <TD width="50%" height="49">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say?></SPAN></TD>
                    </TR>
                  </TBODY>
              </TABLE></TD>
            <TD align=right width="50%"><TABLE border=0 align="right" cellPadding=0 cellSpacing=0 id="showsave">
              <TBODY>
                <TR>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE><TBODY>
                              <TR>
                                <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                              </TR></TBODY></TABLE><!--BUTTON_END--></TD>
                          <?php
                            if ($_GET['Action'] == "Modi"){
								if($Pricedesc>0 && $Bid>0){
							  if (($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==0) || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==1)){
							?>
                          <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                            <tbody>
                              <tr>
                                <td align=middle width=79><!--BUTTON_BEGIN-->
                                  <table>
                                    <tbody>
                                      <tr>
                                        <td valign=bottom nowrap class="link_buttom"><a href="javascript:toFirstCheck();"><img src="images/<?php echo $INFO[IS]?>/icon_check.gif"   border=0>&nbsp;初審</a></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  <!--BUTTON_END--></td>
                                </tr>
                              </tbody>
                            </table></td>
                          <?php }
                          if (($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==1)){?>
                          <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                            <tbody>
                              <tr>
                                <td align=middle width=79><!--BUTTON_BEGIN-->
                                  <table>
                                    <tbody>
                                      <tr>
                                        <td valign=bottom nowrap class="link_buttom"><a href="javascript:toSecondCheck();"><img src="images/<?php echo $INFO[IS]?>/icon_pre_check.gif"   border=0>&nbsp;複審</a></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  <!--BUTTON_END--></td>
                                </tr>
                              </tbody>
                            </table></td>
                          <?php }
                          if (($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2) || $_SESSION['LOGINADMIN_TYPE']==0){?>
                          <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                            <tbody>
                              <tr>
                                <td align=middle width=79><!--BUTTON_BEGIN-->
                                  <table>
                                    <tbody>
                                      <tr>
                                        <td valign=bottom nowrap class="link_buttom"><a href="javascript:toCheck();"><img src="images/<?php echo $INFO[IS]?>/icon_pre_check.gif"   border=0>&nbsp;審核</a></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  <!--BUTTON_END--></td>
                                </tr>
                              </tbody>
                            </table></td>
                          <?php }}?>
                          <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                            <tbody>
                              <tr>
                                <td align=middle width=79><!--BUTTON_BEGIN-->
                                  <table>
                                    <tbody>
                                      <tr>
                                        <td valign=bottom nowrap class="link_buttom"><a href="javascript:toNoCheck();"><img src="images/<?php echo $INFO[IS]?>/icon_reject_check.gif"   border=0>&nbsp;退審</a></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  <!--BUTTON_END--></td>
                                </tr>
                              </tbody>
                            </table></td>
                          <?php
							}
							?>
                          </TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      <table border="0" cellspacing="0" style="margin-bottom:10px">
        <tr>
          <td valign="top">
          <div class="order_button">
            <ul>
             <li><a href="#" onClick="showtable('showtable_base');getMoreAttrib('<?php echo $Bid?>');
 getAttriClass('<?php echo $Bid?>');">基本資料</a></li>
            <?php if ($_GET['Action']=='Modi' || $_POST[doaction]=='copy'){?>
             <li><a href="#" onClick="showtable('showtable_cuxiao');">促銷</a></li>
             <li><a href="#" onClick="showtable('showtable_tag');"> TAG</a></li>
             <?php if($INFO['Paytype']=="0"){?>
             	<li><a href="#" onClick="showtable('showtable_peisong');"> 配送設置</a></li>
             <?php }?>
             <?php }?>
             <?php if ($_GET['Action']=='Modi'){?><li><a href="#" onClick="showtajaxfun('goodssaleoffe');">買越多促銷</a></li>
             <li><a href="#" onClick="showtajaxfun('attrib');">商品多屬性</a></li>
             <li><a href="#" onClick="showtajaxfun('goodsdetail');">詳細資料</a></li>
             <li><a href="#" onClick="showtajaxfun('memberprice');">商品會員價格</a></li>
             <li><a href="#" onClick="showtajaxfun('morepic');">多圖上傳</a></li>
             <li><a href="#" onClick="showtajaxfun('goodslink');">相關商品</a> </li>
             <li><a href="#" onClick="showtajaxfun('books');">相關文章</a> </li>
             <?php if($ifxygoods == "1"){?><li><a href="#" onClick="showtajaxfun('xygoods');">超值任選商品</a> </li><?php }?>
             <?php if($ifpack == "1"){?><li><a href="#" onClick="showtajaxfun('packgoods');">組合商品</a></li><?php }?>
             <?php if($right_array[3]==1){?><li><a href="#" onClick="showtajaxfun('changegoods');">加購商品</a></li><?php }?>
             <li><a href="#" onClick="showtajaxfun('presentgoods');">商品贈品</a></li>
             <li><a href="#" onClick="showtajaxfun('godos_action');">操作日誌</a></li>
              <li><a href="#" onClick="showtajaxfun('pricec_cache');">價格日誌</a></li>
             <?php }?>
            </ul>
           </div>
            </td>
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
                      <FORM name='form_goodsbase' action='admin_goods_save.php' method=post  encType=multipart/form-data>
                        <input type="hidden" name="Action"    value="<?php echo $Action_value?>">
                        <INPUT type=hidden   name=act         value="<?php echo $_POST['act'];?>" >
                        <INPUT type=hidden   name=Where       value="<?php echo $_POST['Where'];?>" >
                        <!--INPUT type=hidden   name='Attr_num'  value="<?php echo $Attr_num?>"-->
                        <INPUT type=hidden   name='gid'       value="<?php echo $Gid?>">
                        <INPUT type=hidden   name='smallimg'  value="<?php echo $Sm_img?>">
                        <INPUT type=hidden   name='bigimg'    value="<?php echo $Bg_img?>">
                        <INPUT type=hidden   name='gimg'      value="<?php echo $G_img?>">
                        <INPUT type=hidden   name='middleimg' value="<?php echo $Mid_img?>">
                        <INPUT type=hidden   name='ifshop' value="<?php echo $_GET['ifshop']?>">
                        <INPUT type=hidden   name='nocheckreason' value="">
                        <INPUT type=hidden value="<?php echo ($_GET['url']);?>"  name=url>
                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" bgColor=#f7f7f7 border=0 id="showtable_base">
                          <TBODY>
                            <TR>
                              <TD width="17%" align=right noWrap>&nbsp;</TD>
                              <TD colspan="3">&nbsp;</TD></TR>
                            <TR>
                              <TD width="17%" align=right noWrap><span class="p9orange">* <?php echo $Admin_Product[ProductName];//商品名称：?>：</span></TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('text','goodsname',$Goodsname,"  id='goodsname'   maxLength=255 size=75 ")?></TD>

							  <?php if (is_file("../".$INFO['good_pic_path']."/".$Mid_img)){?>
                              <TD colspan="2" rowspan="12" align="center"><div id="Mid_img" class="picture_pic">
                                  &nbsp;<img src="<?php echo "../".$INFO['good_pic_path']."/".$Mid_img?>">
                                  <br />
                                <a href="javascript:delpic('Mid_img','<?php echo $Gid;?>','<?php echo $Del_img; ?>')" onClick="return confirm('<?php echo $Admin_Product['Del_Pic']?>')"><font color="#FF0000"><i class="icon-trash" style="font-size:14px;margin-right:5px;margin-left:10px"></i><?php echo $Basic_Command['Del']?></font></a> </div></TD>

							<?php } ?>

                              </TR>
                              <?php if ($_GET['Action']=='Modi' || $_POST[doaction]=='copy'){?>

														<!--TR>
															<TD width="17%" align=right noWrap>英文名稱：</TD>
															<TD><?php echo $FUNCTIONS->Input_Box('text','en_name',$en_name,"  id='en_name'   maxLength=255 size=75 ");?></TD>
														</TR-->
								<TD align="right">英文商品名稱：</TD>
								<TD align="left"><?php echo $FUNCTIONS->Input_Box('text','ERP',$ERP,"  id='ERP'   maxLength=255 size=75 ")?></TD>

                            <TR>
                              <TD width="17%" align=right noWrap>廣告標語：</TD>
                              <TD><div style="width:340px;float:left"><?php echo $FUNCTIONS->Input_Box('text','sale_name',$sale_name,"  id='sale_name'   maxLength=50 size=40 ")?></div>
                                <div style="float:left;width:auto;line-height:25px"> 顏色：</div><div style="float:left;width:auto">
                                <?php echo $FUNCTIONS->Input_Box('text','salename_color',$salename_color,"    maxLength=10 size=10   ")?></div></TD>
                              </TR>
                            <TR <?php if ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==0){ echo "style='display:none;'"; }?>>
                              <TD width="17%" align=right noWrap><?php echo $Admin_Product[ifpub];//是否发布：?>：</TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('radio','ifpub',$Ifpub,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                              <TR >
                      <TD noWrap align=right width="17%">上架時間：</TD>
                      <TD><?php echo $FUNCTIONS->Input_Box('text','pubstarttime',$pubstarttime," id=pubstarttime   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?>
                        <select name="pubstart_h">
                          <?php
			for($i=0;$i<=23;$i++){
            ?>
                          <option value="<?php echo $i;?>" <?php if($pubstart_h==$i) echo "selected";?>><?php echo $i;?></option>
                          <?php
			}
			?>
                          </select>
                        時
                        <select name="pubstart_i">
                          <?php
			for($i=0;$i<=59;$i++){
            ?>
                          <option value="<?php echo $i;?>" <?php if($pubstart_i==$i) echo "selected";?>><?php echo $i;?></option>
                          <?php
			}
			?>
                          </select>
                        分
                        TO
                        <?php echo $FUNCTIONS->Input_Box('text','pubendtime',$pubendtime ," id='pubendtime' onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?>
                        <select name="pubend_h">
                          <?php
			for($i=0;$i<=23;$i++){
            ?>
                          <option value="<?php echo $i;?>" <?php if($pubend_h==$i) echo "selected";?>><?php echo $i;?></option>
                          <?php
			}
			?>
                          </select>
                        時
                        <select name="pubend_i">
                          <?php
			for($i=0;$i<=59;$i++){
            ?>
                          <option value="<?php echo $i;?>" <?php if($pubend_i==$i) echo "selected";?>><?php echo $i;?></option>
                          <?php
			}
			?>
                          </select>
                        分<a href="#" class="easyui-tooltip" title="若不指定上架時間請保持空白，<br />上架商品必須是已審核，但已審核過了上架時間亦會下架"><img src="images/tip.png" width="16" height="16" border="0"></a>
                      </TD>
                      </TR>
                      <TR>
                          <TD width="17%" align=right noWrap>優惠內容：</TD>
                          <TD><textarea name="salecontent" cols="60" rows="5"><?php echo $salecontent?></textarea></TD>
                        </TR>
                       <TR >
                      <TD noWrap align=right width="17%">優惠時間：</TD>
                      <TD>
                       <INPUT   id="salestartdate" size=10 value="<?php echo $salestartdate?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name="salestartdate" />
                        TO
                        <INPUT   id="saleenddate" size=10 value="<?php echo $saleenddate?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name="saleenddate" />
                      </TD>
                      </TR>
                            <TR <?php if ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==0){ echo "style='display:none;'"; }?>>
                              <TD width="17%" align=right noWrap>統倉商品：</TD>
                              <TD><?php
					  if($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
					 	 echo $FUNCTIONS->Input_Box('radio','iftogether',$iftogether,$Add=array($Basic_Command['Yes'],$Basic_Command['No']));
					  }else{
						  echo $iftogether==1?"是":"否";
					  }
					  ?></TD>
                              </TR>
															<TR>
																<TD align=right \>18禁：</TD>
																<TD><?php echo $FUNCTIONS->Input_Box('radio','ifbelate',$ifbelate,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
															</TR>
                            <TR>
                      <TD width="17%" align=right noWrap>應稅/免稅：</TD>
                      <TD><?php echo $FUNCTIONS->Input_Box('radio','ifshui',$ifshui,$Add=array("未稅","應稅"))?></TD>
                      </TR>
                            <TR>
                              <TD width="17%" align=right noWrap><label for="price"><?php echo $Admin_Product[ProductSPrice];//市場價格?>：</label></TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('text','price',$Price,"  id='price'   maxLength=\"20\" size=\"20\" ")?>
                                <a href="#" class="easyui-tooltip" title="<?php echo $Admin_Product[WhatisPrice]?>"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                              </TR>
                              <?php
							}
						  ?>
                            <TR>
                              <TD width="17%" align=right noWrap><span class="p9orange">* <label for="price"><?php echo $Admin_Product[ProductNetPrice];//網購價?>：</label></span></TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('text','pricedesc',$Pricedesc,"  id='pricedesc'   maxLength=\"20\" onchange=\"getpmoney();\" size=\"20\"  ")?>
                                <a href="#" class="easyui-tooltip" title="<?php echo $Admin_Product[WhatisPricedesc]?>"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                              </TR>
                              <?php if ($_GET['Action']=='Modi' || $_POST[doaction]=='copy'){?>
                            <TR>
                              <TD width="17%" align=right noWrap><label for="price">成本價：</label></TD>
                              <TD><?php
					  echo $FUNCTIONS->Input_Box('text','cost',$cost,"  id='cost'   maxLength=\"20\" size=\"20\" onchange=\"getpmoney();\"  ");
					  ?></TD>
                              </TR>
                            <TR>
                              <TD width="17%" align=right noWrap>毛利：</TD>
                              <TD><?php echo $Pricedesc-$cost;?></TD>
                              </TR>
                            <TR>
                              <TD width="17%" align=right noWrap><?php echo $Admin_Product[Mid_img];//商品缩略图?>：</TD>
                              <TD><INPUT  id="img"  type="file" size="40" name="img" >
                                &nbsp;&nbsp;&nbsp;&nbsp;*<span id="yui_3_2_0_1_13115562779589750" lang="ZH-CN" xml:lang="ZH-CN">圖片格式只能是</span><span lang="EN-US" xml:lang="EN-US">jpg</span><span lang="ZH-CN" xml:lang="ZH-CN">或</span><span lang="EN-US" xml:lang="EN-US">png</span><span lang="ZH-CN" xml:lang="ZH-CN">檔案，需小於</span><span lang="EN-US" xml:lang="EN-US">2MB </span></TD>
                              </TR>
                                                        <?php if (is_file("../".$INFO['good_pic_path']."/".$Mid_img)){?>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>
                                                      </TD>
                              </TR>
                            <?php } ?>
                            <TR>
                              <TD noWrap align=right><?php echo $Admin_Product[View];//查看?>：</TD>
                              <TD colspan="3">
                                <?php echo $FUNCTIONS->Input_Box('text','view_num',intval($View_num),"      maxLength=40 size=10 ")?> <?php echo $Admin_Product[Per];//次?>					  </TD>
                              </TR>
                              <?php } ?>
                            <?php
                    if ($_GET['ifshop']==1){
					?>
                            <TR>
                              <TD noWrap align=right><span class="p9orange">* <?php echo $Admin_Product[PrductClassName];//商品類別名稱?>：</span></TD>
                              <TD colspan="3"><?php
					  $goodsclass = "<select name='bid' id='bid'   class=\"trans-input\" >";
					$Sql_bclass    = "select bid,catname,pic1,pic2 from `{$INFO[DBPrefix]}shopbclass` where top_id=0 order by catord  asc  ";
					$query_bclass  = $DB->query($Sql_bclass);
					$num_bclass    = $DB->num_rows($query_bclass);
					while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
						$goodsclass .= "<option value='" . $Rs_bclass['bid'] . "'";
						if ($Bid==$Rs_bclass['bid'])
							$goodsclass .= " selected";
						$goodsclass .= ">├─" . $Rs_bclass['catname'] . "</option>";
						$Sql_bclass_2    = "select bid,catname from `{$INFO[DBPrefix]}shopbclass` where top_id='" . $Rs_bclass['bid'] . "' order by catord  asc  ";
						$query_bclass_2  = $DB->query($Sql_bclass_2);
						$num_bclass_2    = $DB->num_rows($query_bclass_2);
						while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
							$goodsclass .= "<option value='" . $Rs_bclass_2['bid'] . "'";
							if ($Bid==$Rs_bclass_2['bid'])
								$goodsclass .= " selected";
							$goodsclass .= ">│├─" . $Rs_bclass_2['catname'] . "</option>";
						}
					}
					$goodsclass .= "</select>";
					echo $goodsclass;
										  ?></TD>
                              </TR>
                            <?php
                    if ($shopid>0){
					?>
                            <TR>
                              <TD noWrap align=right>商店商品分類：</TD>
                              <TD colspan="3"><?php
					  $goodsclass = "<select name='shopclass' id='shopclass'   class=\"trans-input\" >";
					$Sql_bclass    = "select * from `{$INFO[DBPrefix]}shopgoodsclass` where top_id=0 and shopid='" . $shopid . "' ";
					$query_bclass  = $DB->query($Sql_bclass);
					$num_bclass    = $DB->num_rows($query_bclass);
					while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
						$goodsclass .= "<option value='" . $Rs_bclass['sgcid'] . "'";
						if ($shopclass==$Rs_bclass['sgcid'])
							$goodsclass .= " selected";
						$goodsclass .= ">├─" . $Rs_bclass['classname'] . "</option>";
						$Sql_bclass_2    = "select * from `{$INFO[DBPrefix]}shopgoodsclass` where top_id='" . $Rs_bclass['sgcid'] . "'   ";
						$query_bclass_2  = $DB->query($Sql_bclass_2);
						$num_bclass_2    = $DB->num_rows($query_bclass_2);
						while($Rs_bclass_2 =  $DB->fetch_array($query_bclass_2)){
							$goodsclass .= "<option value='" . $Rs_bclass_2['sgcid'] . "'";
							if ($shopclass==$Rs_bclass_2['sgcid'])
								$goodsclass .= " selected";
							$goodsclass .= ">│├─" . $Rs_bclass_2['classname'] . "</option>";
						}
					}
					$goodsclass .= "</select>";
					echo $goodsclass;
										  ?></TD>
                              </TR>
                            <?php
					}
					?>
                            <TR>
                              <TD noWrap align=right>所屬商店：</TD>
                              <TD colspan="3">
                                <?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}shopinfo`  ","shopid","sid","shopname",intval($shopid));  ?>                      </TD>
                              </TR>
                            <?php
					}else{
					?>
                            <TR>
                              <TD noWrap align=right><span class="p9orange">* <?php echo $Admin_Product[PrductClassName];//商品類別名稱?>：</span></TD>
                              <TD colspan="3"><?php echo $Char_class->get_page_select("bid",$Bid,"  class=\"trans-input\" onchange='getMoreAttrib(this.options[this.selectedIndex].value);getAttriClass(this.options[this.selectedIndex].value);'");//$Char_class->get_page_select("bid",$Bid," class='inputstyle' onchange=changecat(".$Bid.")");// $FUNCTIONS->$OptionSelect("select * from bclass order by top_id asc ",'bid','bid','catname',$Bid)?></TD>
                              </TR>
                              <?php if ($_GET['Action']=='Modi' || $_POST[doaction]=='copy'){?>
                            <TR>
                              <TD align=right valign="top" noWrap>擴展分類：</TD>
                              <TD colSpan=3><input name="btn_class" type="button" id="btn_class" value="新增一個擴展分類">
                                <?php
						if ($_GET['Action'] == "Modi"){
							$c_sql = "select * from `{$INFO[DBPrefix]}goods_class` where gid='" . intval($_GET['gid']) . "'";
							$c_query = $DB->query($c_sql);
							$i = 1;
							while($c_row= $DB->fetch_array($c_query)){
						?>
                                <div>
                                  <?php
							echo $Char_class->get_page_select("bid" . intval($i),$c_row['bid'],"  class=\"trans-input\" ");
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
                              </TR>
                              <?php
					    }
						}
							  ?>
                              <?php if ($_GET['Action']=='Modi' || $_POST[doaction]=='copy'){?>
                            <tr id="moreAttrib_td" style="Z-INDEX: 10;">
                              <TD width="17%" align="right" noWrap>擴充欄位：</TD>
                              <td colspan="3">
                                <div id="show_moreAttrib_td" align="left"></div>						  </td>
                            </tr>
                            <TR>
                              <TD width="17%" align="right" >類別屬性：</TD>
                              <TD colspan="3" align=left id="showattribute"></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD colSpan=3>
                                  </TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right><?php echo $Admin_Product[Provider_name];//供貨商?>：</TD>
                              <TD colSpan=3><?php echo  $DisplayProviderState =  intval($_SESSION['LOGINADMIN_TYPE'])==2 ? $Provider_name  : $FUNCTIONS->select_type("select provider_name,provider_id from `{$INFO[DBPrefix]}provider` order by providerno  ","provider_id","provider_id","provider_name",intval($Provider_id));  ?></TD>
                              </TR>

                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[ProductBand];//商品品牌：?>：</TD>
                              <TD colSpan=3><?php echo $FUNCTIONS->select_type("select brandname,brand_id from `{$INFO[DBPrefix]}brand` order by orderby asc,brand_id asc  ","brand_id","brand_id","brandname",intval($Brand_id)," onChange='changeBrandClass(this.value);$(\"#showbrandclass\").html(\"\");$(\"#extbrandclass\").html(\"\");$(\"#brandclasscount\").val(1);'");  ?><?php //echo  // $FUNCTIONS->Input_Box('text','brand',$Brand,"      maxLength=40 size=40 ") ?>
                              <select name="brand_bid" id="brand_bid">
                              	<option value="0">請選擇</option>
                              </select>
                              </TD>
                              </TR>
							<?php if ($_GET['Action']=='Modi' || $_POST[doaction]=='copy'){?>
                            <TR>
                              <TD align=right valign="top" noWrap>擴展品牌分類：</TD>
                              <TD colSpan=3><input name="btn_brandclass" type="button" id="btn_brandclass" value="新增一個擴展分類">
                               <div id="showbrandclass">
                                <?php
						if ($_GET['Action'] == "Modi"){
							//echo $brandbids;
							$brandbids_array = (array)json_decode($brandbids,true);
							$i = 1;
							//print_r($brandbids_array);
							foreach($brandbids_array as $k=>$v){
								$brand_id_array = (array)$v[0];
								if($brand_id_array['bid']!=$brand_bid){
						?>
                                <div>
                                <select name="brand_bid<?php echo $i?>" id="brand_bid<?php echo $i?>">
                                 <option value="0">請選擇</option>
                                  <?php
							$return = "";
							$Char_class->getBrandClassSelect(0,0,$Brand_id,$brand_id_array['bid']);
								echo $return;
							?>
                                </select>
                              
                                  </div>
                                <?php
								}
								$i++;
							}
						}else{
							$i = 1;
						}
						?>
							   
						</div>
                                <div id="extbrandclass"></div>
                                
                                <input type="hidden" value="<?=$i?>" name="brandclasscount" id="brandclasscount"></TD>
                              </TR>
                              <?php
					    	}
						
							  ?>
                            <TR>
                              <TD noWrap align=right width="17%"></TD>
                              <TD colSpan=3>
                              <!--div id="goodsnametips" class="tips"><?php echo $Admin_Product[PleaseInputPrductName]?></div-->					  </TD></TR>
                              <TD noWrap align=right width="17%">優惠說明：</TD>
                              <TD colSpan=3><?php echo $FUNCTIONS->Input_Box('text','subjectcontent',$subjectcontent,"  id='subjectcontent'   maxLength=255 size=75 ")?>
                              <div class="tips">開放上架時填寫</div>					  </TD></TR>


                            <TR>
                              <TD noWrap align=right width="17%">賣場編號：</TD>
                              <TD width="35%"><!--div id="bntips" class="tips"><?php echo $Admin_Product[PleaseInputPrductBn]?></div-->					  <?php echo $goodsno;?></TD>
                              <TD width="9%" align="right">&nbsp;</TD>
                              <TD width="39%" align="left">&nbsp;</TD>
                              </TR>


                          <TR>
                              <TD noWrap align=right width="17%"><span class="p9orange">* <?php echo $Admin_Product[Bn];//货号：?>：</span></TD>
                              <TD nowrap="nowrap"><?php echo $FUNCTIONS->Input_Box('text','bn',$Bn,"  id='bn'    maxLength=30 size=20 ")?></TD>

                              </TR>
                           <?php
								 }else{
							  ?>
                            <TR>
                              <TD noWrap align=right width="17%"><span class="p9orange">* <?php echo $Admin_Product[Bn];//货号：?>：</span></TD>
                              <TD nowrap="nowrap"><?php echo $FUNCTIONS->Input_Box('text','bn',$Bn,"  id='bn'    maxLength=30 size=20 ")?></TD>

                              </TR>
                              <?php
								}?>
                              <?php if ($_GET['Action']=='Modi' || $_POST[doaction]=='copy'){?>
                            <TR>
                              <TD noWrap align=right width="17%">國際碼：</TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('text','guojima',$guojima," id='guojima'   maxLength=50 size=20 ")?></TD>
							  <TD align="right">產地：</TD>
                              <TD align="left"><?php echo $FUNCTIONS->Input_Box('text','chandi',$chandi,"  id='chandi'   maxLength=255 size=20 ")?></TD>

                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%">型號：</TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('text','xinghao',$xinghao,"  id='xinghao'   maxLength=50 size=20 ")?></TD>
                              <TD align="right">合作代號：</TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('text','oeid',$oeid,"  id='bn' maxLength=30 size=20 ")?> <a href="#" class="easyui-tooltip" title="通路王合作代號"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%">重量：</TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('text','weight',$weight,"  id='weight'   maxLength=50 size=20 ")?></TD>
                              <TD align="right"><?php echo $Admin_Product[Unit];//计量单位：?>：</TD>
                              <TD><?php echo $FUNCTIONS->Input_Box('text','unit',$Unit,"    maxLength=\"20\" size=\"20\" ")?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD colSpan=3><span class="p9orange"> 單位KG </span>註：1公斤(Kg)＝1000公克 ( g )  舉例50克 請填寫【 0.05 】 即可。 <a href="#" class="easyui-tooltip" title="適用於海外運費計算"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"><span class="p9orange">原出廠碼：</span></TD>
                              <TD nowrap="nowrap"><?php echo $FUNCTIONS->Input_Box('text','orgno',$orgno,"  id='orgno'    maxLength=30 size=20 ")?></TD>
                              </TR>
                              <TD noWrap align=right width="17%"></TD>
                              <TD colSpan=3></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"></TD>
                              <TD colSpan=3 id="pmoney"> </TD>
                              </TR>
                            <!--TR>
                      <TD noWrap align=right width="17%">會員價：</TD>
                      <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','memberprice',$memberprice,"  id='memberprice'   maxLength=\"20\" size=\"20\"  ")?>					  </TD>
 				    </TR>
                    <TR>
                      <TD noWrap align=right width="17%">折抵紅利點數：</TD>
                      <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','combipoint',$combipoint,"  id='combipoint'   maxLength=\"20\" size=\"20\"  ")?>					  </TD>
 				    </TR-->
                            <TR>
                              <TD noWrap align=right>部門：</TD>
                              <TD colSpan=3><?php echo $FUNCTIONS->Input_Box('text','department',$department,"  id='department'   maxLength=\"20\" size=\"20\"  ")?>&nbsp;</TD>
                            </TR>
                            <TR>
                              <TD noWrap align=right>PM：</TD>
                              <TD colSpan=3>
							<?php
								$pm_Sql      = "select * from `{$INFO[DBPrefix]}operater`  order by lastlogin desc ";
								$pm_Query    = $DB->query($pm_Sql);
								$pm_array = explode(",",$pm);
								while ($pm_Rs=$DB->fetch_array($pm_Query)) {
								?>
								<input type="checkbox" name="pm[]" value=" <?php echo $pm_Rs['opid'];?>" <?php if(in_array($pm_Rs['opid'],$pm_array)) echo "checked";?>> <?php echo $pm_Rs['truename'];?>
								<?php
								}
							?>
                           </TD>
                            </TR>
                            <TR>
                              <TD noWrap align=right>分期付款：</TD>
                              <TD colSpan=3><?php
					  if($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
					  echo $FUNCTIONS->Input_Box('radio','if_monthprice',$if_monthprice,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'showmonth')","viewtr(0,'showmonth')"));
					  }else{
						 	echo $if_monthprice==1?"是":"否";
						 }
					  ?></TD>
                              </TR>
                            <?php $DISPLAYmonth =  $if_monthprice==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR id="showmonth" <?php echo $DISPLAYmonth;?>>
                              <TD noWrap align=right>分期數：</TD>
                              <TD colSpan=3>
                                <?php
					 // echo $month;
					  $month_array = explode(",",$month);
					  if($_SESSION['LOGINADMIN_TYPE']==0 || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2)){
					  ?>
                                <input name="month[]" type="checkbox" id="month" value="3" <?php if(in_array(3,$month_array)) echo "checked";?> />
                                分3期
                                <input name="month[]" type="checkbox" id="month" value="6" <?php if(in_array(6,$month_array)) echo "checked";?> />
                                分6期
                                <?php
					  }else{
						 if(in_array(3,$month_array)) echo "分3期";
						 if(in_array(6,$month_array)) echo "&nbsp;分6期";
					  }
					?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"></TD>
                              <TD colSpan=3></TD></TR>
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[AlarmStorage];//库存警告：?>：</TD>
                              <TD colSpan=3>
																<input type="radio" name="ifalarm" id="ifalarm" value="1" <?php if($Ifalarm==1) echo "checked";?> />貨到通知
                              	<input type="radio" name="ifalarm" id="ifalarm" value="0" <?php if($Ifalarm==0) echo "checked";?> />開放預購
                              	<input type="radio" name="ifalarm" id="ifalarm" value="2" <?php if($Ifalarm==2) echo "checked";?> />貨已售完不再進貨
																<input type="radio" name="ifalarm" id="ifalarm" value="3" <?php if($Ifalarm==3) echo "checked";?> />來電洽詢
                              </TD>
                              </TR>
                            <?php $DISPLAYalarm =  $Ifalarm==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR id=alarmshow <?php echo $DISPLAYalarm;?>>
                              <TD width="17%" align=right noWrap><b><?php echo $Admin_Product[Alarmnum];//警告数量：?></b>：</TD>
                              <TD colSpan=3><?php echo $FUNCTIONS->Input_Box('text','alarmnum',$Alarmnum,"      maxLength=20 size=20 ")?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="17%"><?php echo $Admin_Product[StorageNum];//库存数量：?>：</TD>
                              <TD colspan="3"><div class="link_box" style="width:100px;text-align:center"><a href="javascript:void(0);" onclick="showWin('url','admin_goods_ajax_changestorage.php?gid=<?php echo $Gid?>','',750,450);"><span class="link_box" style="width:100px">
                                <?php
					  if ($_GET['Action'] == "Modi"){
						  echo $Storage;
					 ?>
                                </span>設置</a>
                                <?php
					  }else{
					 	 echo $FUNCTIONS->Input_Box('text','storage',$Storage,"      maxLength=40 size=40 ");
					  }
					  ?></div>					  </TD>
                              </TR>
                            <TR>
                              <TD align=right><?php echo $Admin_Product[GlProduct];//指定相关产品：?>：</TD>
                              <TD colspan="3">
                                <?php echo $FUNCTIONS->Input_Box('radio','ifgl',$Ifgl,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?> <?php echo $Admin_Product[GlProduct_content] ;//（ 如果关闭指定相关产品选项，系统将默认本类产品为相关产品！）?> </TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right>瀏覽等級：</TD>
                              <TD colspan="3">
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
                                <input type="checkbox" name="userlevel[]" id="userlevel" value="<?php echo $Rs_level['level_id'];?>" <?php if (in_array($Rs_level['level_id'],$level_goods))  echo "checked";?>>
                                <?php echo $Rs_level['level_name'];?>
                                <?php
					   }
					  ?>	*<span id="yui_3_2_0_1_13115562779589743" lang="ZH-CN" xml:lang="ZH-CN">注意：若是任何人都可以瀏覽的商品請勿勾選。</span></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right></TD>
                              <TD colspan="3"></TD></TR>
                            <TR>
                              <TD align=right valign="top" noWrap><?php echo $Admin_Product[Easy_intro];//简单描述：?> <a href="#" class="easyui-tooltip" title="簡介欄位請勿使用半形 ＂"><i class="icon-warning-sign" style="font-size:16px;color:#C00"></i></a>：</TD>
                              <TD colSpan=3><?php echo $FUNCTIONS->Input_Box('textarea','intro',$Intro," cols=72 rows=6  ")?></TD>
                              </TR>
                            <TR  id=alarmshowcontent>
                              <TD width="17%" align=right noWrap valign="top">KeyWords <a href="#" class="easyui-tooltip" title="除了給搜尋引擎看以外，可在站內被搜尋，<br />例如某些不想出現在頁面上的關鍵字便可key在這欄位"><i class="icon-warning-sign" style="font-size:16px;color:#C00"></i></a>：</TD>
                              <TD colSpan=3><?php echo $FUNCTIONS->Input_Box('textarea','keywords',$Keywords," cols=72 rows=4  ")?></TD>
                              </TR>
                            <TR>
                              <TD align=right noWrap valign="top">商品介紹：</TD>
                              <TD colSpan=3>
                               <div  class="editorwidth">
                              <textarea name="FCKeditor1" id="redactor" cols="30" rows="10"><?php echo $Body;?></textarea>
                              </div>
                              </TD>
                            </TR>
                            <TR>
                              <TD align=right valign="top" noWrap>商品規格：</TD>
                              <TD colSpan=3>
                               <div  class="editorwidth">
                              <textarea name="cap_des" id="redactor1" cols="30" rows="10"><?php echo $cap_des;?></textarea>
                              </div>
                              </TD>
                              </TR>
                            <TR>
                              <TD width="17%" align=right valign="top" noWrap><?php echo $Admin_Product[AlarmContent];//使用規則：?>(保留欄位不一定有用)：</TD>
                              <TD colSpan=3>
                               <div  class="editorwidth">
                              <textarea name="alarmcontent" id="redactor2" cols="30" rows="10"><?php echo $AlarmContent;?></textarea>
                              </div>
                              </TD>
                              </TR>
                              <?php
								}
							?>
                            <TR>
                              <TD width="17%" align=right valign="top" noWrap>&nbsp;</TD>
                              <TD colspan="3" align=left valign="top" noWrap>&nbsp;</TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD colspan="3">&nbsp;</TD>
                              </TR>
                            </TBODY>
                          </TABLE>
                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" bgColor=#f7f7f7 border=0  id="showtable_cuxiao" style="display:none">
                          <TBODY>
                            <TR>
                              <TD noWrap align=right width="23%">&nbsp;</TD>
                              <TD width="77%">&nbsp;</TD></TR>
                            <TR>
                              <TD noWrap align=right width="23%"><?php echo $Admin_Product[ifrecommend];//是否推荐：?>：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifrecommend',$Ifrecommend,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="23%"><?php echo $Admin_Product[ifspecial];//是否特价：?>：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('radio','ifspecial',$Ifspecial,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?> </TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="23%"><?php echo $Admin_Product[ifhot];//是否热卖：?>：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('radio','ifhot',$Ifhot,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="23%">是否組合商品：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('radio','ifpack',$ifpack,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                             <TR>
                              <TD noWrap align=right width="23%">是否商品贈品：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('radio','ifgoodspresent',$ifgoodspresent,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="23%"><label for="price">促銷成本價：</label></TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','salecost',$salecost,"  id='salecost'   maxLength=\"10\" size=\"10\"  ")?>
                                用於商品多件折扣、超值任選、買越多越便宜、滿額加購、商品加購，如果不設定請填寫0
                                </TD>
                              </TR>
                              <tr>
                              <TD noWrap align=right width="23%">贈品數量：</TD>
                              <TD colSpan=2><?php echo $presentcount;?><input type="hidden" value="1" name="presentcount" /></TD></TR>
                            <tr>
                              <TD noWrap align=right width="23%">紅利點數：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','point',$Point,"      maxLength=\"10\" size=\"10\" ")?></TD></TR>
                            <TR>
                              <TD noWrap align=right width="23%"><?php echo $Admin_Product[BonusProduct];//红利商品：?>：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio_bonus','ifbonus',$Ifbonus,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                            <?php
                    if ($right_array[1] == 1){
					?>
                     <?php
					}
					$DISPLAYbonus =  $Ifbonus==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR bgcolor="#FFFFCC" id=bonusshow <?php echo $DISPLAYbonus;?>>
                              <TD width="17%" align=right noWrap bgcolor="#FFFFFF"><?php echo $Admin_Product[BonusnumNum];//所需积分：?>：</TD>
                              <TD colspan="2" bgcolor="#FFFFFF"><?php echo $FUNCTIONS->Input_Box('text','bonusnum',$Bonusnum,"      maxLength=10 size=10 ")?>&nbsp;&nbsp;&nbsp;
                                <font color="#FF0000"><?php echo $Admin_Product[Bonusnum_content] ?></font></TD>
                              </TR>
                            <?php
                    if ($right_array[5] == 1){
					?>
                            <!--TR>
                              <TD noWrap align=right width="23%">超值任選不同商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifxygoods',$ifxygoods,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("xyfun(1,'ifxy')","xyfun(0,'ifxy')"))?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="23%">任選商品數量：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','xycount',$xycount,"      maxLength=\"10\" size=\"10\" ")?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="23%">屬於超值任選商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifxy',$ifxy,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("xyfun(1,'ifxygoods')","xyfun(0,'ifxygoods')"))?></TD>
                              </TR-->
                            <?php
					}
					if ($right_array[3] == 1){
					?>
                            <TR>
                              <TD noWrap align=right width="23%">是否是加購商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifchange',$ifchange,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right width="23%">是否是滿額加購商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifadd',$ifadd,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'addshow')","viewtr(0,'addshow')"))?></TD>
                              </TR>
                            <?php
					$DISPLAYadd =  $ifadd==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR bgcolor="#FFFFFF" <?php echo $DISPLAYadd;?> id="addshow">
                              <TD noWrap align=right width="23%">額滿加購金額：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','addmoney',$addmoney,"      maxLength=10 size=10 ")?>
                                加購價格：<?php echo $FUNCTIONS->Input_Box('text','addprice',$addprice,"      maxLength=10 size=10 ")?>
                                </TD>
                              </TR>
                            <?php
					}
					if ($right_array[4] == 1){
					?>
                            <TR>
                              <TD noWrap align=right width="23%">額滿禮商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifpresent',$ifpresent,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'presentshow')","viewtr(0,'presentshow')"))?></TD>
                              </TR>
                            <?php $DISPLAYpresent =  $ifpresent==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\"";
					?>
                            <TR bgcolor="#FFFFFF" <?php echo $DISPLAYpresent;?> id="presentshow">
                              <TD noWrap align=right width="23%">額滿禮金額：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','present_money',$present_money,"      maxLength=10 size=10 ")?>~~<?php echo $FUNCTIONS->Input_Box('text','present_endmoney',$present_endmoney,"      maxLength=10 size=10 ")?></TD>
                              </TR>
                            <?php
					}
					?>
                            <!--TR>
                              <TD noWrap align=right width="23%"><?php echo $Admin_Product[JsProduct]?><集殺商品>：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('radio_js','ifjs',$Ifjs,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?>					  </TD>
                              </TR>
                            <?php $DISPLAYjs =  $Ifjs==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR bgcolor="#FFFFFF" id=jsshow1  <?php echo $DISPLAYjs;?>>
                              <TD align=right noWrap><?php echo $Admin_Product[JsTime];?><集殺時效>：</TD>
                              <TD colspan="2">
                                <?php echo $FUNCTIONS->Input_Box('text','begtime',$begtime," id=begtime   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?>
                                &nbsp;&nbsp; To&nbsp;&nbsp;        <?php echo $FUNCTIONS->Input_Box('text','endtime',$endtime," id=endtime     onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"      maxLength=12 size=12 ")?>
                                &nbsp;					  </TD>
                              </TR>
                            <TR bgcolor="#FFFFFF" id=jsshow2  <?php echo $DISPLAYjs;?>>
                              <TD align=right noWrap><?php echo $Admin_Product[JsPriceAndBegPrice]?><起購價集殺價>：</TD>
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
                              <TD align=right noWrap><?php echo $Admin_Product[Js_totalnum]?><已累计件數>：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('text','Js_totalnum',$Js_totalnum,"      maxLength=10 size=10 ")?></TD>
                              </TR-->
                            <?php
					if ($right_array[2] == 1){
					?>
                            <TR>
                              <TD noWrap align=right>同商品多件折扣：</TD>
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
                              <TD noWrap align=right>整點促銷商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifsaleoff',$ifsaleoff,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'saleoffshow')","viewtr(0,'saleoffshow')"))?>（未到促銷時間段不能進行購買活動）</TD>
                              </TR>
                            <?php $DISPLAYsaleoff =  $ifsaleoff==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR id="saleoffshow" <?php echo $DISPLAYsaleoff;?>>
                              <TD noWrap align=right>促銷時間：</TD>
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
                              <TD noWrap align=right>整點促銷商品：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','iftimesale',$iftimesale,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'timesaleshow')","viewtr(0,'timesaleshow')"))?>（在促銷期間商品售價為促銷價格）</TD>
                              </TR>
                            <?php $DISPLAYtimesale =  $iftimesale==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR id="timesaleshow" <?php echo $DISPLAYtimesale;?>>
                              <TD noWrap align=right>促銷時間：</TD>
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

                                促銷價格<?php echo $FUNCTIONS->Input_Box('text','saleoffprice',$saleoffprice,"      maxLength=10 size=10 ")?>限購數量<?php echo $FUNCTIONS->Input_Box('text','saleoffcount',$saleoffcount,"      maxLength=10 size=10 ")?>
                                </TD>
                              </TR>
                            <?php
					}
					?>
                            <TR>
                              <TD noWrap align=right>商品預購：</TD>
                              <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','ifappoint',$ifappoint,$Add=array($Basic_Command['Yes'],$Basic_Command['No']),$Event = array("viewtr(1,'appointshow')","viewtr(0,'appointshow')"))?></TD>
                              </TR>
                            <?php $DISPLAYsaleoff =  $ifappoint==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                            <TR id="appointshow" <?php echo $DISPLAYsaleoff;?>>
                              <TD noWrap align=right></TD>
                              <TD colspan="2">預購時間：<?php echo $FUNCTIONS->Input_Box('text','appoint_startdate',$appoint_startdate," id=appoint_startdate   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?><select name="appoint_start_h">
                                <?php
			for($i=0;$i<=23;$i++){
            ?>
                                <option value="<?php echo $i;?>" <?php if($appoint_start_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
			}
			?>
                                </select>
                                時
                                <select name="appoint_start_i">
                                  <?php
			for($i=0;$i<=59;$i++){
            ?>
                                  <option value="<?php echo $i;?>" <?php if($appoint_start_i==$i) echo "selected";?>><?php echo $i;?></option>
                                  <?php
			}
			?>
                                  </select>
                                分
                                &nbsp;&nbsp; To&nbsp;&nbsp;        <?php echo $FUNCTIONS->Input_Box('text','appoint_enddate',$appoint_enddate," id=appoint_enddate     onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"      maxLength=12 size=12 ")?>
                                <select name="appoint_end_h">
                                  <?php
			for($i=0;$i<=23;$i++){
            ?>
                                  <option value="<?php echo $i;?>" <?php if($appoint_end_h==$i) echo "selected";?>><?php echo $i;?></option>
                                  <?php
			}
			?>
                                  </select>
                                時
                                <select name="appoint_end_i">
                                  <?php
			for($i=0;$i<=59;$i++){
            ?>
                                  <option value="<?php echo $i;?>" <?php if($appoint_end_i==$i) echo "selected";?>><?php echo $i;?></option>
                                  <?php
			}
			?>
                                  </select>
                                分<br />
                                預購出貨時間：<br /><input type="radio" id="appoint_sendtype" name="appoint_sendtype" value="1" <?php if($appoint_sendtype==1 || appoint_sendtype==0) echo "checked";?> />下單後<?php echo $FUNCTIONS->Input_Box('text','appoint_send1',$appoint_send," id=appoint_send1       maxLength=12 size=12 ")?>天才出貨<br />
                                <input type="radio" id="appoint_sendtype" name="appoint_sendtype" value="2" <?php if($appoint_sendtype==2) echo "checked";?> />活動截止後<?php echo $FUNCTIONS->Input_Box('text','appoint_send2',$appoint_send," id=appoint_send2       maxLength=12 size=12 ")?>天才出貨<br />
                                <input type="radio" id="appoint_sendtype" name="appoint_sendtype" value="3" <?php if($appoint_sendtype==3) echo "checked";?> />廠商自行與客戶聯絡

                                </TD>
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
                              <TD noWrap align=right width="33%">&nbsp;</TD>
                              <TD width="67%">&nbsp;</TD>
                              </TR>
                            <TR>
                              <TD align=right noWrap width="33%">超商配送：</TD>
                              <TD colSpan=2 width="67%"><?php echo $FUNCTIONS->Input_Box('radio','trans_type',$trans_type,$Add=array("不允許","允許"),"")?></TD>
                              </TR>

                            <TR id="transabroadshow">
                              <TD align=right noWrap>是否支持海外配送：</TD>
                              <TD colSpan=2 noWrap width="67%"><?php echo $FUNCTIONS->Input_Box('radio','iftransabroad',$iftransabroad,$Add=array("允許","不允許"))?></TD>
                              </TR>

                            <!--TR id="yunsongshow">
                              <TD align=right noWrap>貨運寄送類：</TD>
                              <TD colSpan=2>
                                <input type="radio" value="1" <?php if($transtype == 1 || $_GET['Action']=='') echo "checked";?> name="transtype" onclick="viewtr(1,'transmiddleshow');viewtr(1,'transaddshow');"  />中小型物件
                                <input type="radio" value="2" <?php if($transtype == 2) echo "checked";?> name="transtype" onclick="viewtr(0,'transmiddleshow');viewtr(0,'transaddshow');"  />大型物件
                                <input type="radio" value="3" <?php if($transtype == 3) echo "checked";?> name="transtype"  onclick="viewtr(0,'transmiddleshow');viewtr(0,'transaddshow');" />其他
                                </TD>
                              </TR-->

                       <TR id="yunsongshow">
                      <TD width="17%" align=right noWrap>貨運寄送類型：</TD>
                      <TD colSpan=2>
                      <input type="radio" value="0" <?php if($ttype == 0 || $_GET['Action']=='') echo "checked";?> name="ttype"  />常溫
                      <input type="radio" value="1" <?php if($ttype == 1) echo "checked";?> name="ttype" />低溫
                      <input type="radio" value="2" <?php if($ttype == 2) echo "checked";?> name="ttype"  />冷凍
                      </TD>
                      </TR>
                            <TR id="transmiddleshow" style="display:none">
                              <TD align=right noWrap>每件運費：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','transtypemonty',$transtypemonty,"      maxLength=10 size=10 ")?></TD>
                              </TR>
                            <TR id="transaddshow" style="display:none">
                              <TD align=right noWrap>運費加價：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text','addtransmoney',$addtransmoney,"      maxLength=10 size=10 ")?>（非台灣地區運費基本價）</TD>
                              </TR>
                            <TR>
                              <TD align=right noWrap>是否滿額免運費：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('radio','ifmood',$ifmood,$Add=array("是","不是"))?></TD>
                              </TR>
<TR>
                              <TD align=right noWrap>免運費商品：</TD>
                              <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('radio','freetran',$freetran,$Add=array("是","不是"))?></TD>
                              </TR>
                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                            </TBODY>
                          </TABLE>

                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" bgColor=#f7f7f7 border=0  id="showtable_tag" style="display:none">
                          <TBODY>
                            <TR>
                              <TD noWrap align=right width="17%">&nbsp;</TD>
                              <TD width="83%">&nbsp;</TD>
                              </TR>
                            <TR>
                              <TD width="23%" align=right noWrap>TAG：</TD>
                              <TD colSpan=2 width="77%"><?php
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
 function checkbn() {
	 var status,gid;
	 if($('input[name="Action"]').val()=="Copy"){
		 gid=0;
	 }else {
	 	 gid=<?php echo intval($_GET['gid'])?>;
	 }
	 $.ajax({
				 url: "admin_goods_checkbn.php",
				 data: "&gid="+gid+"&bn="+$('#bn').val(),
				 type:'get',
				 dataType:"html",
				 async:false,
				 success: function(msg){
				 	//alert(msg);
					if (msg=="0"){
 						alert('貨號不可重複!');
						$('#bn').val('');
						//$('#bn').attr("value")
						status=false;
 					}else {
 						status=true;
 					}
				 }
			 });
	 return status;
 }

 function delpic(Element,gid,value){
 	var url    = "./admin_goods_save.php?Action=delPic&gid="+gid+"&picName="+value;
 	var show = document.getElementById(Element);
	<?php if($_POST['doaction']!='copy'){
	?>
 	AjaxGetRequestInnerHtml(url,show);
	 <?php }else{?>
	 show.innerHTML = "";
	 document.getElementById("gimg").value = "";
	 document.getElementById("smallimg").value = "";
	 document.getElementById("bigimg").value = "";
	 document.getElementById("middleimg").value = "";
	 <?php
	 }?>
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

 getMoreAttrib('<?php echo $Bid?>');
 getAttriClass('<?php echo $Bid?>');
 function getMoreAttrib(bid){
 	if (typeof(bid) == 'undefined'){
 		    return false;
 	}

	$.ajax({
				url: "admin_goods_getmoreattrib.php",
				data: 'bid=' + bid + "&goodattrI=<?php echo base64_encode($goodattrI)?>",
				type:'get',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				    //$('#showsize').html(msg);
					$('#show_moreAttrib_td').html(msg);
				}
			});


 	}
 function getAttriClass(bid){
 	if (typeof(bid) == 'undefined'){
 		    return false;
 	}
	$.ajax({
				url: "admin_goods_attributeclass.php",
				data: 'bid=' + bid + "&gid=<?php echo intval($_GET['gid'])?>",
				type:'get',
				dataType:"html",
				success: function(msg){
			//	alert(msg);
				    //$('#showsize').html(msg);
					$('#showattribute').html(msg);
				}
			});


 	}
<?php
 if ($transtype == 1){
 ?>
 viewtr(1,'transmiddleshow');
 viewtr(1,'transaddshow');
 <?php
 }else{
?>
 viewtr(0,'transmiddleshow');
 viewtr(0,'transaddshow');

<?php
}
 ?>
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
					$(msg).appendTo('#extclass');
				}
			});
		});
	$('#btn_brandclass').click(function() {
		var brandcounts = parseInt($('#brandclasscount').attr("value"));
	//alert(brandcounts);
			$.ajax({
				url: "admin_goods_ajax_brandclass.php",
				data: 'count=' + brandcounts + "&brand_id=" + $('#brand_id').val(),
				type:'get',
				dataType:"html",
				success: function(msg){
				
				    //$('#showsize').html(msg);
					$('#brandclasscount').attr("value",brandcounts+1);
					msg = "<div><select name=\"brand_bid"+brandcounts+"\" id=\"brand_bid"+brandcounts+"\"><option value=\"0\">請選擇</option>"+msg+"</select></div>";
					$(msg).appendTo('#extbrandclass');
				}
			});
		});
	<?php if ($_GET['Action']=='Modi'){?>
	changeBrandClass(<?php echo $Brand_id;?>,<?php echo $brand_bid;?>);
	<?php }?>


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
		case "packgoods":
			ajaxurl = "admin_goods_ajax_packgoods.php";
			break;
		case "godos_action":
			ajaxurl = "admin_goods_ajax_action.php";
			break;
		case "presentgoods":
			ajaxurl = "admin_goods_ajax_presentgoods.php";
			break;
		case "books":
			ajaxurl = "admin_goods_ajax_books.php";
			break;
		case "pricec_cache":
			ajaxurl = "admin_goods_ajax_cache.php";
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
function changeBrandClass(brand_id,brand_bid){
	$.ajax({
				url: "admin_goods_ajax_brandclass.php",
				data: 'brand_bid=' + brand_bid + '&brand_id=' + brand_id,
				type:'get',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				    $('#brand_bid').html(msg);
					

				}
	});
}
<? if ($INFO['Paytype']=="0"){?>

viewtr(<?php echo $trans_type;?>,'transspecialshow');
viewtr(<?php echo 1-$trans_type;?>,'transabroadshow');
viewtr(<?php echo 1-$trans_type;?>,'yunsongshow');
<? }?>

<? if ($_GET['Action']=='Modi'){?>

xyfun(<?php echo $ifxygoods;?>,'ifxy');
xyfun(<?php echo $ifxy;?>,'ifxygoods');

<? }?>
function getpmoney(){
	$('#pmoney').html($('#pricedesc').attr("value")-$('#cost').attr("value"));
}
function showpage(url,data,div){
$.ajax({
				url: url,
				data: data,
				type:'get',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				if(div!="")
				    $('#'+div).html(msg);
				else
					$('#msg').html(msg);
					//$('#classcount').attr("value",counts+1);
					//$(msg).appendTo('#extclass')
				}
	});
}
</script>


</BODY></HTML>
