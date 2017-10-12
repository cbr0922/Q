<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/StaticHtml_Pack.php";
include_once "pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
// base64_decode("%u7CBE%u7DFB");
$Where      = intval($_GET['bid'])!="" ? " and g.bid=".intval($_GET['bid'])." " : ""  ;
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
		if ((in_array(intval($_GET[top_id]),$ot_class_array) && $_SESSION['LOGINADMIN_TYPE']==1) || count($op_class_array)==0 || $_SESSION['LOGINADMIN_TYPE']!=1){
			$S_Sql            = " and ( g.bid='".intval($_GET[top_id])."'  ";
			$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
			$Next_ArrayClass  = explode(",",$Next_ArrayClass);
			$Array_class      = array_unique($Next_ArrayClass);
			foreach ($Array_class as $k=>$v){
				$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid='".$v."' " : "";
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
		$AddBidtype = " and g.bid in (" . implode(",",$class_array) . ")";
	}//else{
	//	$AddBidtype = " and 1<>1";
	//}
}*/
//print_r($op_class_array);
//echo $AddBidtype ;
$Brand_search       = intval($_GET['brand_id'])!=0 ? " and  g.brand_id=".intval($_GET['brand_id'])." " : ""  ;
//$Provider_search    = intval($_GET['provider_id'])!=0 ? " and  g.provider_id=".intval($_GET['provider_id'])." " : ""  ;
$Provider_search    = trim($_GET['provider_name'])!='' ? " and  p.provider_name like '%".trim($_GET['provider_name'])."%' " : ""  ;
//这里是判断是否是1，0。
if (trim($_GET[typeis])!=""){
	$Typeis = " and ".$_GET[typeis]."=".intval($_GET[typeradio]);
}
if ($_GET['checkstate']!=""){
	 	$checkSql .= " and g.checkstate='" . intval($_GET['checkstate']) . "'";
	 }
if ($_GET['ttype']!=""){
	 	$checkSql .= " and g.ttype='" . intval($_GET['ttype']) . "'";
	 }
if ($_GET['brand_bid']>0){
	 	$checkSql .= " and g.brandbids like '%\"".$_GET['brand_bid']."\"%' ";
}
if ($_GET['iftogether']!=""){
	$iftogether = $_GET['iftogether'];
	if ($_GET['iftogether']=="-1")
		$iftogether = 0;
	 	$checkSql .= " and g.iftogether='" . intval($iftogether) . "'";
   }
if($_GET['Action']=="Search" && $_GET['keytype']==0){
 	$Where = " and ( g.goodsname like '%".trim(urldecode($_GET['skey']))."%' or g.bn like '%".trim(urldecode($_GET['skey']))."%' or g.guojima like '%" . trim(urldecode($_GET['skey'])) . "%' or g.goodsno like '%" . trim(urldecode($_GET['skey'])) . "%') ".$Typeis." ".$AddBidtype." ";
}elseif ($_GET['Action']=="Search" && $_GET['keytype']==1) {
 	if($_GET['skey']!=''){
 		$gid = explode(",",$_GET['skey']);
 		$Where = " and g.gid IN ('". implode("','",$gid). "') ".$Typeis ." ".$AddBidtype." ";
	}else{
 		$Where = $Typeis ." ".$AddBidtype." ";
 	}
}
$Value    = $_GET['Action']=="Search" ? trim(urldecode($_GET['skey']))   : $Admin_Product[PleaseInputPrductName]  ; //請輸入商品名稱
$Sql      = "select g.*,bc.catname from `{$INFO[DBPrefix]}goods` g  left join `{$INFO[DBPrefix]}bclass`  bc  on (g.bid=bc.bid) left join `{$INFO[DBPrefix]}provider` as p on g.provider_id=p.provider_id  where g.shopid=0 ".$Where." ".$Brand_search." ".$Provider_search." " . $checkSql ."";
$Sql      = $_GET['alarm_recsts']=='DO' ? " select g.* from `{$INFO[DBPrefix]}goods` g   left join `{$INFO[DBPrefix]}bclass`  bc  on (g.bid=bc.bid) where g.ifalarm=1 and g.alarmnum>=storage and g.shopid=0  " : $Sql ;
$Sql = Add_LOGINADMIN_TYPE($Sql); //这里是判断是否存在WHERE,以防止语法错误,同时根据是否是供应商条件,对SQL语句重新处理一下!
if ($_GET['order']=="bn"){
	$Sql      = $Sql." order by g.bn desc , g.goodorder desc ";
}elseif ($_GET['order']=="time"){
	$Sql      = $Sql." order by g.idate desc , g.goodorder desc ";
}else{
	$Sql      = $Sql." order by g.idate desc , g.goodorder desc ";
}
//echo $Sql;
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 50  ; //每頁商品數量
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
include RootDocumentShare."/cache/Productclass_show.php";
foreach ($_GET as $_get_name => $_get_value) {
		 if ($_get_name != "offset"&$_get_name != "order") {
				if ($_get_name=='skey'){
					$_get_vars .= "&$_get_name=".urlencode(trim($_get_value))."";
				}else{
					$_get_vars .= "&$_get_name=".trim($_get_value)."";
				}
				if (isset($_GET['Type'])){
					$_get_vars    .="&Type=".trim($_GET['Type']);
				}
		 }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_List];//全部商品列表?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
<div id="fullBg"></div>
<div id="msg">
<div id="close"></div>
<div id="ctt"></div>
</div>
<?php include_once "head.php";?>
<script language="javascript">
var type_string="";
var type_num=0;
function search_img(){
	if (searchbanner.skey.value=='<?php echo trim($Admin_Product[PleaseInputPrductName])?>'){
		alert('<?php echo $Admin_Product[PleaseInputPrductName]?>');  //请输入产品名称！
		return false;
	}
	if (searchbanner.typeis.value!=""){
		for(var i=0;i<document.searchbanner.typeradio.length;i++) {
			if (document.searchbanner.typeradio[i].checked)  {
				type_string=type_string+document.searchbanner.typeradio[i].value+",";
				type_num++;
			}
		}
		if (type_num == 0){
			alert('<?php echo $Admin_Product[PleaseSelectPrductStatus]?>!');//请选择商品状态
			return false;
		}
	}
	document.searchbanner.action = "";
}
	  </script>
<SCRIPT language=javascript>
function toComment(id){
	var checkvalue;
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
	if (checkvalue!=false){
		document.adminForm.action = "admin_comment_list.php?goodsid="+checkvalue;
		document.adminForm.submit();
	}
}
function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}

	if (checkvalue!=false){
		document.adminForm.action = "admin_goods.php?Action=Modi&gid="+checkvalue+"&url=<?php echo urlencode($_SERVER[ "PHP_SELF"]. "?".$_SERVER[ "QUERY_STRING"] );?>";
		document.adminForm.act.value="";
   	    document.adminForm.Where.value="Goods";
		document.adminForm.submit();
	}
}
function toCopy(id){
	var checkvalue;
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
	if (checkvalue!=false){
		document.adminForm.action = "admin_goods.php?Action=Modi&gid="+checkvalue;
		document.adminForm.act.value="";
		document.adminForm.doaction.value="copy";
   	    document.adminForm.Where.value="Goods";
		document.adminForm.submit();
	}
}
function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_goods_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}
function toFirstCheck(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('你確認初審這些商品嗎？')){
			document.adminForm.action = "admin_goods_save.php";
			document.adminForm.act.value="FirstCheck";
			document.adminForm.submit();
		}
	}
}
function toSecondCheck(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('你確認複審這些商品嗎？')){
			document.adminForm.action = "admin_goods_save.php";
			document.adminForm.act.value="SecondCheck";
			document.adminForm.submit();
		}
	}
}
function toCheck(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('你確認審核這些商品嗎？')){
			document.adminForm.action = "admin_goods_save.php";
			document.adminForm.act.value="Check";
			document.adminForm.submit();
		}
	}
}
function toCheckprice(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('你確認審核這些商品價格嗎？')){
			document.adminForm.action = "admin_goods_save.php";
			document.adminForm.act.value="Checkprice";
			document.adminForm.submit();
		}
	}
}
function toSave(){ alert("[deprecated] admin_goods_list_save.php"); }
</SCRIPT>
<SCRIPT language=JavaScript>
<!--
function MM_findObj(n, d) { //v4.0
  var p,i,x;
  if(!d) d=document;
  if((p=n.indexOf("?"))>0 && parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n];
  for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n);
  return x;
}
function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3)
    if ((obj=MM_findObj(args[i]))!=null) {
	  v=args[i+2];
      if (obj.style) {
	    obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
      obj.visibility=v;
	}
}
//-->
</SCRIPT>
<script language="javascript">
function toExcel(){
	document.searchbanner.action = "admin_goods_excel_out.php";
	document.searchbanner.submit();
}
function toExcelstorage(){
	document.searchbanner.action = "admin_goods_excelstorage.php";
	document.searchbanner.submit();
}
function toNoCheck(){
	showWin('url','admin_goods_nocheck.php','',300,200);
}
</script>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
  <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
<TBODY>
          <TR>
            <TD width="47%"><TABLE width="100%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
                <TR>
                  <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                  <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_List];//全部商品列表?>
                    </SPAN></TD>
                </TR>
              </TBODY>
              </TABLE></TD>
            <TD width="53%" align="right"><table cellspacing=0 cellpadding=0 border=0>
              <tbody>
                <tr>
                  <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                    <tbody>
                      <tr>
                        <td align=middle width=79><!--BUTTON_BEGIN-->
                          <table>
                            <tbody>
                              <tr>
                                <td valign=bottom nowrap class="link_buttom"><a href="admin_goods.php"><img  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[CreateProduct];//新增?></a></td>
                              </tr>
                            </tbody>
                          </table>
                          <!--BUTTON_END--></td>
                      </tr>
                    </tbody>
                    </table></td>
                  <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                    <tbody>
                      <tr>
                        <td align=middle width=79><!--BUTTON_BEGIN-->
                          <table>
                            <tbody>
                              <tr>
                                <td valign=bottom nowrap class="link_buttom"><a href="javascript:toEdit(0);"><img  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></td>
                              </tr>
                            </tbody>
                          </table>
                          <!--BUTTON_END--></td>
                      </tr>
                    </tbody>
                    </table></td>
                  <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                    <tbody>
                      <tr>
                        <td align=middle width=79><!--BUTTON_BEGIN-->
                          <table>
                            <tbody>
                              <tr>
                                <td valign=bottom nowrap class="link_buttom"><a href="javascript:toCopy(0);"><img  src="images/<?php echo $INFO[IS]?>/fb-productcopy.gif"  border=0>&nbsp;<?php echo $Admin_Product[CloneProduct] ;//复制商品?></a></td>
                              </tr>
                            </tbody>
                          </table>
                          <!--BUTTON_END--></td>
                      </tr>
                    </tbody>
                    </table></td>
                  <td align=middle><table>
                            <tbody>
                              <tr>
                                <td valign=bottom nowrap class="link_buttom"><a href="javascript:toExcel();"><img  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;匯出商品</a></td>
                              </tr>
                            </tbody>
                  </table></td>
                  <td align=middle><table>
                            <tbody>
                              <tr>
                                <td valign=bottom nowrap class="link_buttom"><a href="javascript:toExcelstorage();"><img  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;匯出庫存</a></td>
                              </tr>
                            </tbody>
                  </table></td>
                  <!-- TD align=middle>
				    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
				      <TBODY>
				        <TR>
				          <TD align=middle width=79><!--BUTTON_BEGIN
				            <TABLE class=fbottonnew link="javascript:su();">
				              <TBODY>
				                <TR>
	                        <TD vAlign=bottom noWrap><IMG  src="images/<?//=$INFO[IS]?>/excel_icon.gif"  border=0>&nbsp;<?//=$PROG_TAGS["ptag_236"];//导出?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_EN</TD></TR></TBODY></TABLE></TD -->
                  <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                    <tbody>
                      <tr>
                        <td align=middle width=79><!--BUTTON_BEGIN-->
                          <table>
                            <tbody>
                              <tr>
                                <td valign=bottom nowrap class="link_buttom"><a href="javascript:toDel();"><img src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></td>
                              </tr>
                            </tbody>
                          </table>
                          <!--BUTTON_END--></td>
                      </tr>
                    </tbody>
                    </table></td>
				  <?php if (($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==0) || ($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==1)){?>
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
                  <?php }
                  if (($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']==2) || $_SESSION['LOGINADMIN_TYPE']==0){?>
                  <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                    <tbody>
                      <tr>
                        <td align=middle width=79><!--BUTTON_BEGIN-->
                          <table>
                            <tbody>
                              <tr>
                                <td valign=bottom nowrap class="link_buttom"><!--<a href="javascript:toCheckprice();"><img src="images/<?php echo $INFO[IS]?>/icon_pre_check.gif"   border=0>&nbsp;審核價格</a>--><a href="../modules/ap/ap_goods_price.php"><img src="images/<?php echo $INFO[IS]?>/icon_pre_check.gif"   border=0>&nbsp;變價</a></td>
                              </tr>
                            </tbody>
                          </table>
                          <!--BUTTON_END--></td>
                      </tr>
                    </tbody>
                    </table></td>
                  <?php }?>
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
                  <!--TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:toSave();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END</TD></TR></TBODY></TABLE>
				  </TD-->
                </tr>
              </tbody>
              </table>
            </TD>
          </TR>
        </TBODY>
  </TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=searchbanner id=searchbanner method=get action="">
          <input type="hidden" name="Action" id="Action" value="Search">
          <TR>
            <TD align=right height=31>
              <TABLE class=allborder cellSpacing=0 cellPadding=0 width=100% border=0 style="margin-bottom:10px">
                <TBODY>
                  <TR>
                    <TD height=36 colspan="4" align=left class="9pv" style="padding-left:10px">
                      <select id="typeis" name="typeis"  class="trans-input">
                        <option value=""><?php echo $Admin_Product[PleaseSelectPrductStatus]?> <!--请选择商品状态 --> </option>
                        <option value="g.ifpub"  <?php if ($_GET[typeis]=='g.ifpub') { echo " selected=\"selected\" "; } ?> >
                          <?php echo $Admin_Product[ifpub];?></option>      <!--是否发布-->
                        <option value="g.ifrecommend"    <?php if ($_GET[typeis]=='g.ifrecommend') { echo " selected=\"selected\" "; } ?> >
                          <?php echo $Admin_Product[ifrecommend];?></option><!--是否推荐-->
                        <option value="g.ifspecial"      <?php if ($_GET[typeis]=='g.ifspecial') { echo " selected=\"selected\" "; } ?> >
                          <?php echo $Admin_Product[ifspecial];?></option> <!--是否特价-->
                        <option value="g.ifhot"          <?php if ($_GET[typeis]=='g.ifhot') { echo " selected=\"selected\" "; } ?> >
                          <?php echo $Admin_Product[ifhot];?></option>     <!--是否热卖-->
                        <option value="g.ifalarm"        <?php if ($_GET[typeis]=='g.ifalarm') { echo " selected=\"selected\" "; } ?> >
                          <?php echo $Admin_Product[ifalarm];?></option>   <!--是否库存警告-->
                        <option value="g.ifbonus"        <?php if ($_GET[typeis]=='g.ifbonus') { echo " selected=\"selected\" "; } ?> >
                          <?php echo $Admin_Product[ifbonus];?>            <!--是否红利商品-->
                        </option>
                        <option value="g.ifjs"        <?php if ($_GET[typeis]=='g.ifjs') { echo " selected=\"selected\" "; } ?> >
                          <?php echo $Admin_Product[ifjs];?>            <!--是否红利商品-->
                        <option value="g.ifchange"        <?php if ($_GET[typeis]=='g.ifchange') { echo " selected=\"selected\" "; } ?> >
                          是否加購商品
                        <option value="g.ifadd"        <?php if ($_GET[typeis]=='g.ifadd') { echo " selected=\"selected\" "; } ?> >
                          是否額滿加購商品
                        <option value="g.ifpresent"        <?php if ($_GET[typeis]=='g.ifpresent') { echo " selected=\"selected\" "; } ?> >
                          是否額滿禮
                        <option value="g.ifsales"        <?php if ($_GET[typeis]=='g.ifsales') { echo " selected=\"selected\" "; } ?> >
                          是否多件折扣
                        <option value="g.ifpack"        <?php if ($_GET[typeis]=='g.ifpack') { echo " selected=\"selected\" "; } ?> >
                          是否組合商品
                        <option value="g.ifgoodspresent"        <?php if ($_GET[typeis]=='g.ifgoodspresent') { echo " selected=\"selected\" "; } ?> >
                          是否贈品
                        <option value="g.ifsaleoff"        <?php if ($_GET[typeis]=='g.ifsaleoff') { echo " selected=\"selected\" "; } ?> >
                          是否整點促銷
                        <option value="g.iftimesale"        <?php if ($_GET[typeis]=='g.iftimesale') { echo " selected=\"selected\" "; } ?> >
                          是否整點促銷（促銷價格）
                        <option value="g.ifappoint"        <?php if ($_GET[typeis]=='g.ifappoint') { echo " selected=\"selected\" "; } ?> >
                          是否預購商品
                        </option>
                      </select>
                      <input name="typeradio" type="radio" value="1" <?php if (intval($_GET[typeradio])==1 and  $_GET['typeis']!=""  ) { echo " checked "; }?>><?php echo $Basic_Command['Open']?><input type="radio" name="typeradio" value="0" <?php if (intval($_GET[typeradio])==0 and  $_GET['typeis']!=""  ) { echo " checked "; }?>>
                      <?php echo $Basic_Command['Close']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $Char_class->get_page_select("top_id",$_GET[top_id],"  class=\"trans-input\" ");?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Admin_Product[ProductBand];//商品品牌：?>：<?php echo $FUNCTIONS->select_type("select brandname,brand_id from `{$INFO[DBPrefix]}brand` order by orderby asc, brand_id asc  ","brand_id","brand_id","brandname",intval($_GET['brand_id']));  ?></span></span>&nbsp;&nbsp;<?php echo $Admin_Product[Provider_name]?>：
                      <INPUT  name='provider_name'  size="30" height="40" value="<?php echo $_GET['provider_name']?>">
                    </TD>
                  </TR>
                  <TR>
                    <TD width="37%" height=31 align=left style="padding-left:10px;padding-bottom:5px">
                    溫層：
                      <select name="ttype" id="ttype">
                        <option <?php if($_GET['ttype']=="") echo "selected";?> value="">請選擇</option>
                        <option value="0" <?php if($_GET['ttype']==0 && $_GET['ttype']!="") echo "selected";?>>常溫</option>
                        <option value="1" <?php if($_GET['ttype']==1 ) echo "selected";?>>低溫</option>
                        <option value="2" <?php if($_GET['ttype']==2 ) echo "selected";?>>冷凍</option>
                      </select>
                    審核狀態：
                      <select name="checkstate" id="checkstate">
                        <option <?php if($_GET['checkstate']=="") echo "selected";?> value="">請選擇</option>
                        <option value="0" <?php if($_GET['checkstate']==0 && $_GET['checkstate']!="") echo "selected";?>>未審核</option>
                        <option value="1" <?php if($_GET['checkstate']==1 ) echo "selected";?>>初審</option>
                        <option value="2" <?php if($_GET['checkstate']==2 ) echo "selected";?>>複審</option>
                        <option value="3" <?php if($_GET['checkstate']==3 ) echo "selected";?>>退審</option>
                      </select>

                      <input name="iftogether" type="radio" value="1" <?php if (intval($_GET[iftogether])==1  ) { echo " checked "; }?>>統倉<input type="radio" name="iftogether" value="-1" <?php if (intval($_GET[iftogether])=="-1" ) { echo " checked "; }?>>非統倉</br>
											搜尋類型：<select name="keytype" id="keytype">
                        <option value="0" <?php if($_GET['keytype']==0 ) echo "selected";?>>商品名稱</option>
                        <option value="1" <?php if($_GET['keytype']==1 ) echo "selected";?>>商品ID(以,分隔)</option>
                      </select>
                    <INPUT  name='skey' id='skey' size="30" height="40" value="<?php echo $_GET['skey']?>" style="font-size:12px"><!--IMG class=sarrow id=suggimg2 style="CURSOR: pointer" onclick=onoff() src="images/suggest_down.gif" name=suggimg2 //搜索匹配相关代码 -->&nbsp;</TD>
                    <TD width="40%" align=left nowrap style="padding-bottom:5px"><input type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField onClick="return search_img();"></TD>
                    <TD width="19%" align="center" vAlign=center nowrap class=p9black><a href="admin_goods_list.php?order=bn<?php echo $_get_vars;?>">按貨號排序</a> &nbsp;|&nbsp; <a href="admin_goods_list.php?order=time<?php echo $_get_vars;?>">按更新時間排序</a></TD>
                    <TD width="4%" align="center" vAlign=center nowrap class=p9black><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>
                      <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.searchbanner.submit(); ",$Array=array('2','10','15','20','30','50','100'))?></TD>
                  </TR>
                </TBODY>
              </TABLE>
            </TD>
          </TR>
        </FORM>
  </TABLE>
      <form name="ExportExcel" action="admin_goods_excel_in.php" method="post"  enctype="multipart/form-data" >
    <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
            <TR>
              <TD width="37%" height=31 align=left style="padding-left:10px">商品庫存導入：
                <input type="file" name="cvsEXCEL"  ID='cvsEXCEL' />
                <button name="Submit" type="submit" value="導入" size="20"/>
              導入</button></TD>
              <TD width="63%" align=right style="padding-left:10px"><a href="javascript:changeOrderState(1,1);"></a></TD>
            </TR>
          </TBODY></TABLE>
      </form>
      <form name="ExportExcel" action="admin_goods_excel_price.php" method="post"  enctype="multipart/form-data" >
    <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
            <TR>
              <TD width="37%" height=31 align=left style="padding-left:10px">商品價格導入：
                <input type="file" name="cvsEXCEL"  ID='cvsEXCEL' />
                <button name="Submit" type="submit" value="導入" size="20"/>
              導入</button></TD>
              <TD width="63%" align=right style="padding-left:10px"><a href="javascript:changeOrderState(1,1);"></a></TD>
            </TR>
          </TBODY></TABLE>
      </form>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=104>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                        <FORM name="adminForm" id="adminForm" action='' method=post>
                          <INPUT type=hidden name="act" id="act">
                          <INPUT type=hidden name="nocheckreason" id="nocheckreason">
                          <INPUT type=hidden name=Where>
                          <input type=hidden name=doaction >
                          <INPUT type=hidden value=0  name=boxchecked>
                          <input type='hidden' name="url" value="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>">
                          <TBODY>
                            <TR align=middle>
                              <TD width="3%" height=26 align=left noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black>
                                <INPUT onclick=checkAll('<?php echo intval($Nums)?>'); type=checkbox value=checkbox   name=toggle></TD>
															<TD width="5%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black>商品ID</TD>
                              <TD width="8%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>
                              <TD width="5%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black>
                                <?php echo $Admin_Product[PrductClassName];//商品類別名稱?>					  </TD>
                              <TD width="24%" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[ProductName];//商品名稱?>&nbsp;</TD>
                              <TD width="3%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[ProductSmPic];//缩图?><BR></TD>
                              <TD width="5%"  height=26 align="center"  background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[ProductNetPrice];//網購價?></TD>
                              <!--TD width="5%" align="center"  background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black>成本</TD-->
                              <?php if ($_SESSION['LOGINADMIN_TYPE']  != 2) {  ?> <!--  这里是因为供应商不能修改发布，推荐，特价，热卖等资料。-->
                              <TD width="3%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[pub];//发布?><BR></TD>
                              <TD width="3%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[recommend];//推荐?><BR></TD>
                              <TD width="3%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[special];//特价?><BR></TD>
                              <TD width="3%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[hot];//热卖?><BR></TD>
                              <?php } ?>
                              <TD width="7%"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[storage];//库存?><BR></TD>
                              <TD width="4%" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black><?php echo $Admin_Product[goodorder];//排序?></TD>
                              <TD width="4%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black>預覽</TD>
                              <!--TD width="5%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>詳細資料</TD-->
                              <TD width="7%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black>到貨通知</TD>
                              <TD width="5%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black>審核狀態</TD>
                              <!--<TD width="5%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black>待審免稅價</TD>
                              <TD width="5%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif style="background-repeat:repeat-x" class=p9black>待審促銷價</TD>-->
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
					?><tbody>
                              <TR class=row0>
                                <TD align=left height=26><INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['gid']?>' name=cid[]>
                                <!--INPUT type=hidden value='<?php echo $Rs['gid']?>' name=Ci[]--></TD>
																<TD height=26 align="left" noWrap><?php echo $Rs['gid']?></TD>
                                <TD height=26 align="left" noWrap><?php echo $Rs['bn']?></TD>
                                <TD height=26 align=left nowrap>
                                <div id='Bcatname<?php echo $i;?>'><a onClick="ChangeBcatnameInnerHtml('Bcatname<?php echo $i;?>','<?php echo trim($Rs['bid'])?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Broot = $Rs['catname']!="" ? $Rs['catname'] : "├─/";?></a></div>                      </TD>
                                <TD height=26 align=left><A href="javascript:toEdit('<?php echo $Rs['gid']?>',0)"><?php echo $Rs['goodsname']?></A>&nbsp;</TD>
                                <TD align=middle height=26><IMG onMouseOver="MM_showHideLayers('imgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('imgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/icon-viewpic.gif" width=18>
                                <DIV class=shadow id=imgLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 135px; POSITION: absolute; HEIGHT: 135px"   border="1"><IMG src="../<?php echo $INFO['good_pic_path']?>/<?php echo $Rs['smallimg']?>" ></DIV>					  </TD>
                                <TD align=center height=26>
                                  <div id='pd<?php echo $i;?>'><?php echo $Rs['pricedesc']?></div>
                                <!--INPUT   class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  size=8 value='<?php //echo $Rs['pricedesc']?>' name=pricedesc[]--></TD>
                                <!--TD align=center><?php echo $Rs['cost']?></TD-->
                                <?php if ($_SESSION['LOGINADMIN_TYPE']  != 2) {  ?> <!--  这里是因为供应商不能修改发布，推荐，特价，热卖等资料-->
                                <TD align=middle height=26><img src="<?php echo $ifpub_pic?>" border="0" /></TD>
                                <TD align=middle height=26>
                                <div id='ifRmb<?php echo $i;?>'><a onClick="ChangeifRmbInnerHtml('ifRmb<?php echo $i;?>','<?php echo $Rs['ifrecommend']?>',<?php echo intval($Rs['gid'])?>)" ><img src="<?php echo $ifrmd_pic?>" border="0" /></a></div>                      </TD>
                                <TD height=26 align=middle>
                                <div id='ifSpecial<?php echo $i;?>'><a onClick="ChangeifSpecialInnerHtml('ifSpecial<?php echo $i;?>','<?php echo intval($Rs['ifspecial'])?>',<?php echo intval($Rs['gid'])?>)" ><img src="<?php echo $ifspec_pic?>" border="0" /></a></div>                      </TD>
                                <TD height=26 align=middle>
                                <div id='ifHot<?php echo $i;?>'><a onClick="ChangeifHotInnerHtml('ifHot<?php echo $i;?>','<?php echo $Rs['ifhot']?>',<?php echo intval($Rs['gid'])?>)" ><img src="<?php echo $ifhot_pic?>" border="0" /></a></div>					  </TD>
                                <?php }
					   $Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($Rs['gid']);
						$Query_s    = $DB->query($Sql_s);
						$Nums      = $DB->num_rows($Query_s);
					  ?>
                                <TD align=center height=26>
                                <div class="link_box" style="width:70px"> <a href="javascript:void(0);" onclick="showWin('url','admin_goods_ajax_changestorage.php?gid=<?php echo $Rs['gid']?>','',750,450);"><?php echo $Rs['storage']?> 設置</a></div></TD>
                                <TD height=26 align=center>
                                  <div id='sort<?php echo $i;?>'><a onClick="ChangeSortInnerHtml('sort<?php echo $i;?>','<?php echo $Rs['goodorder']?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Rs['goodorder']?></a></div>
                                <!--INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"    size=5 value='<?php echo $Rs['goodorder']?>' name=order[]--></TD>
                                <TD align=center nowrap><div style="width:40px"><a href="admin_goods_detail.php?goods_id=<?php echo $Rs['gid']?>" target="_blank"><i class="icon-external-link black_big"></i></a></div></TD>
                                <!--TD align=center nowrap><a href="admin_goodsdetail_list.php?goods_id=<?php echo intval($Rs['gid'])?>">查看</a></TD-->
								<?php
					   				$Sql_w      = "select COUNT(u.user_id) user_id from `{$INFO[DBPrefix]}waitbuy` w RIGHT JOIN `{$INFO[DBPrefix]}user` u ON w.user_id=u.user_id where gid=" . intval($Rs['gid']);
									$Query_w    = $DB->query($Sql_w);
									$Rs_w=$DB->fetch_array($Query_w);
					  			?>
                                <TD align=center nowrap><div class="link_box" style="width:80px"><a href="admin_waitbuy.php?gid=<?php echo $Rs['gid']?>"><?php echo "到貨通知 ".$Rs_w[user_id];?></a></div></TD>
                                <TD align=center nowrap>
                                <?php
					  if($Rs['checkstate']==0)
					  	echo "<font color=#FF0000>未審核</font>";
					  elseif($Rs['checkstate']==1)
					  	echo "<font color=#FF9900>已初審</font>";
					  elseif($Rs['checkstate']==2)
					  	echo "<a href='../product/detail" . $Rs['gid'] ."' target='_blank'><font color=#009900>已複審</font></a>";
					  elseif($Rs['checkstate']==3)
					  	echo "<font color=#003399><i class='icon-info-sign'></i> 已退審<br>（原因：" . $Rs['nocheckreason'] . "）</font>";
					  ?></TD>
                             <!--<TD align=center nowrap>
                             	<?php
								 $goods_Sql = "select * from `{$INFO[DBPrefix]}goods_price_cach` where gid='" . $Rs['gid'] . "' and state=0 and org_price='" . $Rs['price'] . "' and org_pricedesc='" . $Rs['pricedesc'] . "' order by pubtime limit 0,1";
								$goods_Query =  $DB->query($goods_Sql);
								 $goods_Num   =  $DB->num_rows($goods_Query );

									$goods_Rs = $DB->fetch_array($goods_Query);
									echo $goods_Rs['new_price'];

								 ?>
                             </TD>
                             <TD align=center nowrap><?php echo $goods_Rs['new_pricedesc'];?></TD>-->
                              </TR></tbody>
                          <?php
					$i++;
					}
					?>
                        </FORM>
                      </TABLE>
                    </TD>
                  </TR>
              </TABLE>
              <?php
            if ($Num>0){
			?>
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                    </TD>
                  </TR>
                  <?php
            }
			?>
              </TABLE>
            </TD></TR>
  </TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
<!------------------搜索中关键词匹配js代码-------------------->
<!--script language=javascript>
var coflag=0;
var nflag=0;
var l1=null;
var commonkey=document.searchbanner.skey;
var keywordvalue=document.searchbanner.skey.value;
var a=null;
var oResult=null;
var ka=true;
var X=true;
var ca=null;
var Ea=false;
var ma=null;
var mousein=0;
var hintwords;
var hintlength =0;
var imgID = document.images["suggimg2"];
//var imgID = document.getElementById("suggimg2");
//var requestDomain="http://127.0.0.1/";
var requestDomain="http://"+window.location.host;
if(keywordvalue=="")
{
	keywordvalue=" ";
}
document.searchbanner.skey.autocomplete="off";
document.searchbanner.skey.onfocus=lc;
document.searchbanner.skey.onblur=Wb;
window.onresize=Mb;
function onoff() {
//alert(document.images["suggimg2"].name);
	if((document.getElementById("sugmaindivname") != "undefined")&&(document.searchbanner.skey.value != "")) {
//alert(document.images["suggimg2"].name);
		if(document.images["suggimg2"].src == requestDomain+"images/suggest_down.gif") {
			document.images["suggimg2"].src = requestDomain+"images/suggest_up.gif";
			document.getElementById("sugmaindivname").style.visibility="visible";
		}
		else if(document.images["suggimg2"].src == requestDomain+"images/suggest_up.gif") {
			document.images["suggimg2"].src = requestDomain+"images/suggest_down.gif";
			document.getElementById("sugmaindivname").style.visibility="hidden";
		}
	}else if(!document.getElementById("sugmaindivname")){
	}
}
function kc()
{
	a=document.searchbanner.skey;
	a.autocomplete="off";
	var oResult	= document.createElement('div');
	oResult.id= 'sugmaindivname';//suggestion main div name
	rightandleft=1;
	topandbottom=1;
	oResult.style.zIndex="2000";
	oResult.style.paddingRight="0";
	oResult.style.paddingLeft="0";
	oResult.style.paddingTop="0";
	oResult.style.paddingBottom="0";
	oResult.style.visibility="hidden";
	uda(oResult);
	oResult.style.position="absolute";
	oResult.style.backgroundColor="white";
	document.body.appendChild(oResult);
}
function Mb()
{
	if(GetObjValue('sugmaindivname'))
	{
		uda(document.getElementById("sugmaindivname"));
		}
}
function Wb()
{
	if(GetObjValue('sugmaindivname'))
	{
		document.getElementById("sugmaindivname").style.visibility="hidden";
		}
}
function lc()
{
	if(Ea==false)
	{
		kc();
		Ea=true;
		}
}
function Xb(h)
{
	if(window.event)h=window.event;
	if(h)
		onlyNum(h);
		if(h.keyCode==38 || h.keyCode==40)
		{
			h.cancelBubble=true;
			h.returnValue=false;
			return false
		}
}
var x02="%7B%D9%95aYQd";
var ascInit="1";
//eval(c01(x02));
te01(ascInit);
//te01();
//function te01()
function te01(ascInit)
{
	testnetb = new Date();
	begintime=testnetb.getTime();
	//document.f.tag.value="n";
	var keywordrand=Math.floor((Math.random())*10000);
	daend = new Date();
	endtime=daend.getTime();
	xiewenxiu=endtime-begintime;
	if(xiewenxiu<500)
	{
		//eval(c01(x03));
//		setTimeout("everytenms()",10);
setTimeout("everytenms(ascInit)",10);
		if (document.attachEvent) {
			document.onkeydown=Xb;
  		}
	  	if(document.addEventListener){
	  		document.addEventListener('keydown',onlyNum,false);
		}
	}
	else {}
}
//function everytenms()
//ascInit = "0";
function everytenms(ascInit)
{
//alert(document.images[imgID.name].src);
	var qnowvalue=document.searchbanner.skey.value;
	if(qnowvalue=="")
	{
		qnowvalue==" "
	}
	if(keywordvalue==qnowvalue || anum1=="1" || qnowvalue=="请输入查询词")
	{}
	else if(qnowvalue=="" || anum=="1")
	{
		if(GetObjValue("sugmaindivname"))
		{
			document.getElementById("sugmaindivname").style.visibility="hidden";
		}
                if(document.images["suggimg2"].src == requestDomain+"images/suggest_up.gif") {
                        document.images["suggimg2"].src = requestDomain+"images/suggest_down.gif";
                }
		keywordvalue=qnowvalue;
		}
		else
			{
				test = "";
				newresult=getContent(qnowvalue,ascInit,test);
        if(qnowvalue!="") {
                if(document.images["suggimg2"].src == requestDomain+"images/suggest_down.gif") {
                        document.images["suggimg2"].src = requestDomain+"images/suggest_up.gif";
                }
        }
				keywordvalue=qnowvalue;
				keynum=0;
			}
	var agt = navigator.userAgent.toLowerCase();
	var is_ie5 = (agt.indexOf("msie 5") != -1);
	if(is_ie5){}
	else
		{
			setTimeout("everytenms(ascInit)",10);
		}
		return true;
}
String.prototype.trim = function()
{
   return this.replace(/(^\s+)|\s+$/g,"");
 }
function keyfun()
{
	document.getElementById("suggestspan1").style.backgroundColor='#3366cc';
}
function getContent(keyword1,ascInit,test)
{
	if(keyword1!="")
	{
		if(l1&&l1.readyState!=0)
		{
			l1.abort()
			}
		var xmlhttp=null;
		try
		{
			xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{try
			{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(sc){xmlhttp=null}
		}
		if(!xmlhttp&&typeof XMLHttpRequest!="undefined"){
			xmlhttp=new XMLHttpRequest();
			xmlhttp.overrideMimeType('text/xml');
		}
		l1=xmlhttp;
        var keyword_trim = keyword1.trim();
		if (window.RegExp && window.encodeURIComponent)
		{
			var newStrComment = encodeURIComponent(keyword_trim);
			var keyword       = encodeURIComponent(keyword_trim);
		} else {
				var newStrComment =keyword_trim;
				alert('is next');
				var keyword = escape(keyword_trim,'UTF-8');
		}
		//url="http://"+window.location.host+"/suggest/suggest.jsp?key="+keyword+"&asc="+ascInit;
		//url="http://127.0.0.1/ajax.php?key="+keyword;
		//url="http://"+window.location.host+"/ajax.php?key="+keyword+"&asc="+ascInit;
        url="http://"+window.location.host+"/<?php echo OtherPach."/".Resources."/".ShopAdmin ?>/ajax_checkProductName.php?key="+keyword;
		//alert(url);
		l1.open("GET", url, true);
		l1.onreadystatechange=function()
		{
		   if(l1.readyState == 4 )
			{
			   ee=l1.responseText;

				if(test!="test")
				{
					var everydata=ee.split("\n");
					var everydatal=everydata.length;
					var data;
					//alert(everydatal);
					if(everydatal<=3)
					{
						data="";
					}else
					{
						nflag=1;
data = "<div id=sugmaindivname2 class=\"inputmenu\" style=\"solid #b2b2b2;\" onmousedown=\"sugmaindivname2.style.visibility='visible'\" style=\"javascript:this.css.visibility='visible';\"> <div class=\"menubar1\"><span><a href=\"javascript:void(null);\"  onclick=\"sugmaindivname2.style.visibility='hidden'\"><font color=#363837><?php echo $Admin_Product[CloseFunction] ?></font></a> </span><font color=#363837><?php echo $Admin_Product[SkyAutoP] ?></font></div><ul id=\"ula\">";
						if(everydatal-1>11) {
							noweverydatal=11;
						}
						else {
							noweverydatal=everydatal-1;
						}
						hintwords=new Array();
						hintlength=0;
						for(i=0;i<everydatal;i++)
//						for(i=0;i<noweverydatal;i++)
						{
							var neweveryword=everydata[i].split("\t");
							//alert(neweveryword[1]);
							if(neweveryword[1] !="")
							{
								result="";
								if(neweveryword[1]!=null)
								{
									hintwords[hintlength] = neweveryword[0];
									hintlength = hintlength+1;
								}
							}
							else{result="";}
							if(i<everydatal-1 && neweveryword[1]!=null)
							{
								//j=i-1;
								j = i-1;
								newword=neweveryword[0].replace("'","\\'");
								//biaohong = document.searchbanner.skey.value;
								biaohong = keyword1.toLowerCase();
								if(neweveryword[0].substr(0,biaohong.length)==biaohong) {
								data+="<li id=\"keyword"+j+"\" style=\"cursor:hand;\"onmouseover=this.style.backgroundColor=\"#C1ECFF\" onmouseover=\"javascript:mousein = 1;\" onmouseout=this.style.backgroundColor=\"white\" onmouseout=\"javascript:mousein = 0;	keynum="+j+";realkeynum="+j+";\"onmousedown=\"cc('"+newword+"')\">"+"<span id=\"td"+j+"_2\">"+neweveryword[1]+" "+result+"</span><td id=\"td"+j+"_1\"><strong>&nbsp;"+biaohong+"</strong>"+neweveryword[0].substring(biaohong.length)+"</td></li>";
}
else {
feibiaohonglen = neweveryword[0].length - biaohong.length;
								data+="<li id=\"keyword"+j+"\" style=\"cursor:hand;\"onmouseover=this.style.backgroundColor=\"#C1ECFF\" onmouseover=\"javascript:mousein = 1;\" onmouseout=this.style.backgroundColor=\"white\" onmouseout=\"javascript:mousein = 0;    keynum="+j+";realkeynum="+j+";\" onmousedown=\"cc('"+newword+"')\">"+"<span id=\"td"+j+"_2\">"+neweveryword[1]+" "+result+"</span>&nbsp;<td id=\"td"+j+"_1\">"+neweveryword[0].substring(0,feibiaohonglen)+"<strong>"+biaohong+"</strong></td>"+"</li>";
}
								/*
								data+="<div  id=\"keyword"+j+"\">"+
								"<table class=\"f12\" width=98% border=0 cellpadding=0 cellspacing=0 height=\"20\" onMouseOver=\"mon(this,"+j+","+noweverydatal+")\" onMouseOut=\"mout(this,"+j+")\"  onmousedown=\"cc('"+newword+"')\" >"+
								"<tr><td align=left class=\"f12\"  id=\"td"+j+"_1\" style=\"padding-left:2px\">"
								+neweveryword[0]+
								"</td><td  class=\"rst f12\" align=right id=\"td"+j+"_2\">"
								+neweveryword[1]+
								" "+result+"</td></tr></table></div>";*/
								data=data.replace("undefined","");}
						}
var feiasc;
//var haha="apple";
if(ascInit=="1") feiasc="0";
else feiasc = "1";
						}
						if(GetObjValue("sugmaindivname"))
						{
							if(data=="")
							{
								document.getElementById("sugmaindivname").style.visibility="hidden";
							}else
							{
								document.getElementById("sugmaindivname").style.visibility="visible";
							}
							document.getElementById("sugmaindivname").innerHTML=data;
						}
					}
				}
				else {ee="";}
			};
			l1.send(null);
			return keyword;
		}
		else {return keyword;}
}
function cc(num)
{
	document.searchbanner.skey.value=num;
	if(GetObjValue("sugmaindivname"))
	{
		document.getElementById("sugmaindivname").style.visibility="hidden";
	}
	anum="1";
	document.searchbanner.skey.focus();
	document.searchbanner.submit();
}
function c01(str)
{
	str=unescape(str);
	var c=String.fromCharCode(str.charCodeAt(0)-str.length);
	for(var i=1;i<str.length;i++)
	{
		c+=String.fromCharCode(str.charCodeAt(i)-c.charCodeAt(i-1));
	}
	return c;
}
function cckeydown(num)
{
	if(GetObjValue("sugmaindivname"))
	{
		document.getElementById("sugmaindivname").style.visibility="hidden";
	}
	anum="1";
	document.searchbanner.skey.focus();
}
function uda(oResult)
{
	if(oResult)
	{
		a=document.searchbanner.skey;
		oResult.style.left=zb(a)+"px";
		oResult.style.top=Yb(a)+a.offsetHeight+"px";
		oResult.style.width=Ta(a)+"px"
	}
}
function zb(s)
{
	return kb(s,"offsetLeft")+3
}
function Yb(s)
{
	return kb(s,"offsetTop")
}
function kb(s,na)
{
	var wb=0;
	while(s)
	{
		wb+=s[na];
		s=s.offsetParent
	}
	return wb
}
function Ta(a)
{
	if(navigator&&navigator.userAgent.toLowerCase().indexOf("msie")==-1)
	{
		return a.offsetWidth
	}
	else
	{
		return a.offsetWidth
		}
}
var keynum=0;
var anum="0";
var anum1="0";
var realkeynum;
function onlyNum(event)
{
	pointer = -1;
	if(mousein==1)
		return;
	if(event.keyCode==40) // key =
	{
		pointer++;
		if(keynum!=-1)
		{
			t="keyword"+keynum;
			numt="td"+keynum+"_1";
			numt2="td"+keynum+"_2";
			t1="keyword"+keynum;
		}
		else
		{
			minkeynum=keynum+1;
			numt="td"+keynum+"_1";
			numt2="td"+keynum+"_2";
			t="keyword"+keynum;
			t1="keyword"+minkeynum;
		}
		if(GetObjValue(t1))
		{
			GetObjValue(t).style.backgroundColor='C1ECFF';
			GetObjValue(numt).style.color='#FFFFFF';
			document.searchbanner.skey.value=hintwords[keynum];
			anum1="1";
			if(keynum>0)
			{
				var lastkeynum=keynum-1;
				var lastt="keyword"+lastkeynum;
				var lastnumt="td"+lastkeynum+"_1";
				var lastnumt2="td"+lastkeynum+"_2";
				GetObjValue(lastt).style.backgroundColor='white';
				//GetObjValue(lastt).childNodes[0].style.backgroundColor='white';
				GetObjValue(lastnumt).style.color='';
				GetObjValue(lastnumt2).style.color='';
			}
			if(pointer >= 5) {
				document.getElementById("ula").scrollBy(0,10);
			}
			realkeynum=keynum;keynum++;
			}
			else
			{
				if(realkeynum==""){realkeynum=0;}
			}
	}
	if(event.keyCode==38)   //key= &
	{
		pointer--;
		if(realkeynum!=0)
		{
			realkeynum=realkeynum-1;
			var upt="keyword"+realkeynum;
			var numupt="td"+realkeynum+"_1";
			var numupt2="td"+realkeynum+"_2";
			if(GetObjValue(upt))
			{
				if(realkeynum<9)
				{
					var nextkeynum=realkeynum+1;
					var nextt="keyword"+nextkeynum;
					var numnextt="td"+nextkeynum+"_1";
					var numnextt2="td"+nextkeynum+"_2";
					GetObjValue(nextt).style.backgroundColor='white';
					GetObjValue(numnextt).style.color='';
					GetObjValue(numnextt2).style.color='';
					if(GetObjValue(numnextt)){}
				}
				GetObjValue(upt).style.backgroundColor='C1ECFF';
				GetObjValue(numupt).style.color='#ffffff';
				document.searchbanner.skey.value=hintwords[realkeynum];
				anum1="1";keynum--;
			 }
			}
		}
		if(event.keyCode==13)
		{
			if(GetObjValue("sugmaindivname"))
			{
				var sugmaindivid=document.getElementById("sugmaindivname").style.visibility;
				if (document.getElementById("sugmaindivname").style.visibility=="visible" && coflag==1)
				{
					//document.f.tag.value="k";
				}
				else if(nflag==0 && document.getElementById("sugmaindivname").style.visibility=="hidden")
				{
					//document.f.tag.value="u";
				}else
					{
						//document.f.tag.value="n";
					}
					document.getElementById("sugmaindivname").style.visibility="hidden";
			 }else
			 	{
			 		var sugmaindivid="hidden";
			 	}
			 if(sugmaindivid=="hidden" || realkeynum==null )
			 {}
			 else
			 	{
			 		var upt="keyword"+realkeynum;
			 		cckeydown(hintwords[realkeynum]);
			 	}
		}
		if(event.keyCode!=13 && event.keyCode!=38 && event.keyCode!=40)
		{anum="0";anum1="0";}
}

function GetObjValue(objName)
{
	if(document.getElementById)
	{
		return eval('document.getElementById("' + objName + '")');
	}else
	{
		return eval('document.all.' + objName);
		}
}
function mon(tbl,tdline,noweverydatal)
{
	for(i=1;i<noweverydatal-1;i++)
	{
		j=i-1;
		var somet="keyword"+j;
		GetObjValue(somet).childNodes[0].style.backgroundColor='white';
		document.getElementById("td"+j+"_1").style.color = '';
		document.getElementById("td"+j+"_2").style.color = '';
	}
	var everyt="keyword"+tdline;
	if(GetObjValue(everyt))
	{
		GetObjValue(everyt).childNodes[0].style.backgroundColor="#4780DE";
	}
	else
	{
		tbl.bgColor = "#73b945";
	}
	document.getElementById("td"+tdline+"_1").style.color = '#FFFFFF';
	document.getElementById("td"+tdline+"_2").style.color = '#FFFFFF';
	mousein = 1;
}
function mout(tbl,tdline)
{
	var everyt="keyword"+tdline;
	if(GetObjValue(everyt))
	{
		GetObjValue(everyt).childNodes[0].style.backgroundColor='white';
	}
	else
	{
		tbl.bgColor = "#f5f5f5";
	}
	document.getElementById("td"+tdline+"_1").style.color = '';
	document.getElementById("td"+tdline+"_2").style.color = '';
	mousein = 0;
	keynum=tdline;
	realkeynum=tdline;
}
</script//-->
<!------------------搜索中关键词匹配js代码-------------------->

<!-------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
/**
 *   引入AJAX
 */
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
echo $InitAjax;
?>
<script language="javascript" src="../js/autocomplete/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="../js/autocomplete/jquery-ui.css">
 <script language="javascript">
 function AjaxGetRequest(url,show){
 	if (typeof(url) == 'undefined'){
 		    return false;
 	}
 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
 	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
 	ajax.onreadystatechange = function() {
 		//alert ('d');
 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		        if (ajax.readyState == 4 && ajax.status == 200) {
 		        	    	show_Content.style.display="";
 		        	    	    	show.innerHTML = "<?php echo $StaticHtml_Pack[CreatedOneHtml].':' ;?>" + ajax.responseText;  //'<img src="images/ok_16.gif"  border="0" />';
 		        	    	    	    }

            }
 		    ajax.send(null);
 		    }

/*var availableTags = [
	"item1",
			  "item2",
			  "item3"
			];
			$( "#skey" ).autocomplete({
			  source: availableTags
			});*/
var urlx = "../include/search_goods_autocomplate.php";

$( "#skey" ).autocomplete({
	source: urlx,
	autoFocus: true,
	focus: function(event, ui) {
		//$(this).val(ui.item.label);
		return false;
	},
	select: function( event, ui ) {
		var value=ui.item.value;
		var label=ui.item.label;
		/*if( value!=label ){
			window.location="<{ $Site_Url }>/product/detail"+value;
		}*/
		//if( value!=label ){
			//alert(value + "," + label);
		//}
		$(this).val(ui.item.label);
		/*var newurl="shopadmin/admin_goods_list.php?";
		newurl+="skey="+value+"&";
		window.location=;*/
		document.getElementById("searchbanner").submit();

		return false;
	}
});
</script>

<script language="javascript">
/**
* ajax 改变值状态 产品网络价格
*/
function ChangePriceDescInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT name='pricedesc[]' type='text'  size='6' value="+OldValue+" onblur=changePriceDescInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}
function changePriceDescInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updatePricedesc&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'PriceDesc');
}

/**
* ajax 改变值状态 产品库存量
*/
function ChangeStorageInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT name='storage[]' type='text'  size='6' value="+OldValue+" onblur=changeStorageInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}
function changeStorageInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updateStorage&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'Storage');
}

/**
* ajax 改变值状态 产品顺序
*/
function ChangeSortInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT name='order[]' type='text'  size='4' value="+OldValue+" onblur=changeSortInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}
function changeSortInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updateSort&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'Sort');
}

/**
* ajax 改变值状态 产品货号
*/
function ChangeBnInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT name='bn[]' type='text'  size='8' value="+OldValue+" onblur=changeBnInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}
function changeBnInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updateBn&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'Bn');
}
/**
* ajax 改变值状态 产品是否发布
*/
function ChangeIfPubInnerHtml(Element,OldValue,Id){
	var url    = "./ajax_updateProduct.php?Type=updateIfPub&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ifPub');
}

/**
* ajax 改变值状态 产品是否推荐
*/
function ChangeifRmbInnerHtml(Element,OldValue,Id){
	var url    = "./ajax_updateProduct.php?Type=updateifRmb&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ifRmb');
}
/**
* ajax 改变值状态 产品是否特价
*/
function ChangeifSpecialInnerHtml(Element,OldValue,Id){
	var url    = "./ajax_updateProduct.php?Type=updateifSpecial&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ifSpecial');
}

/**
* ajax 改变值状态 产品是否热卖
*/
function ChangeifHotInnerHtml(Element,OldValue,Id){
	var url    = "./ajax_updateProduct.php?Type=updateifHot&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ifHot');
}


/**
* ajax 改变值状态 所归属的产品类
*/
function ChangeBcatnameInnerHtml(Element,OldValue,Id){
	var url    = "./ajax_updateProduct.php?Type=updateBcatname&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ViewBcatname');
}
function ChangeBcatnameActionInnerHtml(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updateBcatnameAction&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'BcatnameAction');
}

function AjaxGetRequestInnerHtml(url,Id,Element,Type){
	if (typeof(url) == 'undefined'){
		    return false;
	}
	if (typeof(Id) == 'undefined'){
		    return false;
	}
	if (typeof(Element) == 'undefined'){
		    return false;
	}
	if (typeof(Type) == 'undefined'){
		    return false;
	}

	var ajax = InitAjax();
	ajax.open("GET", url, true);
	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
	ajax.onreadystatechange = function() {
		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
		    if (ajax.readyState == 4 && ajax.status == 200) {
		    	if (Type=='PriceDesc'){
		    		txt = "<a onclick=ChangePriceDescInnerHtml('"+Element+"','"+ajax.responseText+"','"+Id+"')>"+ajax.responseText+"</a>";
		    	}
		    	if (Type=='Storage'){
		    		txt = "<a onclick=ChangeStorageInnerHtml('"+Element+"','"+ajax.responseText+"','"+Id+"')>"+ajax.responseText+"</a>";
		    	}
		    	if (Type=='Sort'){
		    		txt = "<a onclick=ChangeSortInnerHtml('"+Element+"','"+ajax.responseText+"','"+Id+"')>"+ajax.responseText+"</a>";
		    	}
		    	if (Type=='Bn'){
		    		txt = "<a onclick=ChangeBnInnerHtml('"+Element+"','"+ajax.responseText+"','"+Id+"')>"+ajax.responseText+"</a>";
		    	}
		    	if (Type=='ifPub'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='ifRmb'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='ifSpecial'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='ifHot'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='ViewBcatname'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='BcatnameAction'){
		    		txt =  ajax.responseText;
		    	}
			//	alert(txt);
		    	var show   = document.getElementById(Element);
		    	show.innerHTML =txt;
		    }
		    }
		    ajax.send(null);
		    }
</script>
