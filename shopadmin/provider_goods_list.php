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

if (intval($_GET[top_id])!=0){
	$S_Sql            = " and ( g.bid='".intval($_GET[top_id])."'  ";
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
	$Next_ArrayClass  = explode(",",$Next_ArrayClass);
	$Array_class      = array_unique($Next_ArrayClass);
	foreach ($Array_class as $k=>$v){
		$Add .= trim($v)!="" && intval($v)>0 ? " or g.bid='".$v."' " : "";
	}
   $AddBidtype =$S_Sql . $Add . " )";
}

$Brand_search       = intval($_GET['brand_id'])!=0 ? " and  g.brand_id=".intval($_GET['brand_id'])." " : ""  ;
$Provider_search    = intval($_GET['provider_id'])!=0 ? " and  g.provider_id=".intval($_GET['provider_id'])." " : ""  ;



//这里是判断是否是1，0。
if (trim($_GET[typeis])!=""){
	$Typeis = " and ".$_GET[typeis]."=".intval($_GET[typeradio]);
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

$Sql      = "select g.*,bc.catname from `{$INFO[DBPrefix]}goods` g  left join `{$INFO[DBPrefix]}bclass`  bc  on (g.bid=bc.bid) where 1=1".$Where." ".$Brand_search." ".$Provider_search." ";

$Sql      = $_GET['alarm_recsts']=='DO' ? " select g.* from `{$INFO[DBPrefix]}goods` g   left join `{$INFO[DBPrefix]}bclass`  bc  on (g.bid=bc.bid) where g.ifalarm=1 and g.alarmnum>=storage  " : $Sql ;


$Sql = Add_LOGINADMIN_TYPE($Sql); //这里是判断是否存在WHERE,以防止语法错误,同时根据是否是供应商条件,对SQL语句重新处理一下!


$Sql      = $Sql." order by g.idate desc , g.goodorder desc ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}


include RootDocumentShare."/cache/Productclass_show.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<link href="css/suggestion.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK href="css/theme.css" type=text/css rel=stylesheet />
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_List];//全部商品列表?></title>
<script language="javascript" src="../js/TitleI.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
/*****************************************************
         * 滑鼠hover變顏色
         ******************************************************/
$(document).ready(function() {
$("#orderedlist tbody tr").hover(function() {
		$(this).addClass("blue");
	}, function() {
		$(this).removeClass("blue");
	});
});
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3)
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
</script>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >

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
}
	  </script>

<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
 <TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center   border=0>
 <TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
	<?php  include_once "desktop_title.php";?>
	</TD>
  </TR>
  </TBODY>
 </TABLE>
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
		document.adminForm.action = "provider_goods.php?Action=Modi&gid="+checkvalue;
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
		document.adminForm.action = "provider_goods.php?Action=Modi&gid="+checkvalue;
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
			document.adminForm.action = "provider_goods_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}

function toSave(){
		if (confirm('<?php echo $Admin_Product[IfConfigSaveModi] ?>')){ //您是否確認保存修改
			document.adminForm.action = "provider_goods_list_save.php";
			document.adminForm.act.value="Save";
			document.adminForm.Where.value="Goods";
			document.adminForm.submit();
		}

}
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

//-->
</SCRIPT>
<script language="javascript">
function su(){
	formExcel.submit();
}
</script>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
<form name="formExcel" method="post" action="admin_goods_excel.php"  >
<input type="hidden" name="Action" value="Excel">
</form>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="53%"><TABLE width="53%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
                <TR>
                  <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                  <TD width="506" noWrap class=p12black><SPAN class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_List];//全部商品列表?>
                    </SPAN></TD>
                  </TR>
                </TBODY>
              </TABLE></TD>
            <TD width="47%" align="right"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>

                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="provider_goods.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[CreateProduct];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>


                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toCopy(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-productcopy.gif"  border=0>&nbsp;<?php echo $Admin_Product[CloneProduct] ;//复制商品?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

                  <!-- TD align=middle>
				    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
				      <TBODY>
				        <TR>
				          <TD align=middle width=79><!--BUTTON_BEGIN
				            <TABLE class=fbottonnew link="javascript:su();">
				              <TBODY>
				                <TR>
	                        <TD vAlign=bottom noWrap><IMG  src="images/<?//=$INFO[IS]?>/excel_icon.gif"  border=0>&nbsp;<?//=$PROG_TAGS["ptag_236"];//导出?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_EN</TD></TR></TBODY></TABLE></TD -->

                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

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

                  </TR>
                </TBODY>
              </TABLE></TD>
            </TR>
          <TR>
            <TD colspan="2" align="right">
              </TD>
            </TR>
          </TBODY>
        </TABLE>

      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>

        <FORM name=searchbanner method=get action="">
          <input type="hidden" name="Action" value="Search">

          <TR>
            <TD align=right height=31>
              <TABLE class=p9black cellSpacing=0 cellPadding=0 width=100% border=0>
                <TBODY>
                  <TR>
                    <TD height=31 colspan="3" align=left class="allborder">

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
                          </option>
                        </select>
                      <input name="typeradio" type="radio" value="1" <?php if (intval($_GET[typeradio])==1 and  $_GET['typeis']!=""  ) { echo " checked "; }?>><?php echo $Basic_Command['Open']?><input type="radio" name="typeradio" value="0" <?php if (intval($_GET[typeradio])==0 and  $_GET['typeis']!=""  ) { echo " checked "; }?>><?php echo $Basic_Command['Close']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  $Char_class->get_page_select("top_id",$_GET[top_id],"  class=\"trans-input\" ");?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Admin_Product[ProductBand];//商品品牌：?>：<?php echo $FUNCTIONS->select_type("select brandname,brand_id from `{$INFO[DBPrefix]}brand` order by brand_id asc  ","brand_id","brand_id","brandname",intval($_GET['brand_id']));  ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <!--
					<?php echo $Admin_Product[Provider_name]?>：<?php echo $FUNCTIONS->select_type("select provider_name,provider_id from `{$INFO[DBPrefix]}provider` order by provider_id asc  ","provider_id","provider_id","provider_name",intval($_GET['provider_id']));  ?>
					-->



                      </TD>
                    </TR>
                  <TR>
                    <TD width="20%" height=31 align=left nowrap>
											搜尋類型：<select name="keytype" id="keytype">

												<option value="0" <?php if($_GET['keytype']==0 ) echo "selected";?>>商品名稱</option>

												<option value="1" <?php if($_GET['keytype']==1 ) echo "selected";?>>商品ID(以,分隔)</option>

											</select>
										<INPUT  name='skey' size="30" height="40" value="<?php echo $_GET['skey']?>"><!--IMG class=sarrow id=suggimg2 style="CURSOR: pointer" onclick=onoff()  src="images/suggest_down.gif" name=suggimg2 //搜索匹配相关--><div class="input02" id="show_Content" style="display:none">&nbsp;</div></TD>
                    <TD width="67%" align=left nowrap><input type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField onclick="return search_img();" /></TD>
                    <TD width="13%" align="center" vAlign=center nowrap class=p9black><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>
                      <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.searchbanner.submit(); ",$Array=array('2','10','15','20','30','50','100'))?></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
          </TR>
          </FORM>
        </TABLE>


      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action='' method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden name=Where>
                          <input type=hidden name=doaction >
                          <INPUT type=hidden value=0  name=boxchecked>
                          <TBODY>
                            <TR align=middle>
                              <TD width="3%" height=26 align=left noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo intval($Nums)?>'); type=checkbox value=checkbox   name=toggle></TD>
															<TD width="5%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>商品ID</TD>
                              <TD width="8%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <?php echo $Admin_Product[PrductClassName];//商品類別名稱?>					  </TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductName];//商品名稱?>&nbsp;</TD>
                              <TD width="5%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductSmPic];//缩图?><BR></TD>
                              <TD width="12%"  height=26 align="center"  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductNetPrice];//網購價?></TD>
                              <TD width="5%" background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>成本</TD>
                              <?php if ($_SESSION['LOGINADMIN_TYPE']  != 2) {  ?> <!--  这里是因为供应商不能修改发布，推荐，特价，热卖等资料。-->
                              <TD width="5%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[pub];//发布?><BR></TD>
                              <TD width="5%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[recommend];//推荐?><BR></TD>
                              <TD width="5%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[special];//特价?><BR></TD>
                              <TD width="5%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[hot];//热卖?><BR></TD>
                              <?php } ?>
                              <TD width="8%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><span id="yui_3_2_0_3_1305162769688206">上架狀態</span></TD>
                              <TD width="8%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><span id="yui_3_2_0_3_1305162769688213">預覽</span></TD>

                              <TD width="8%"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[storage];//库存?><BR></TD>
                              <TD width="8%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>到貨通知</TD>
                              <TD width="8%" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[goodorder];//排序?></TD>
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

					?>
                            <TR class=row0>
                              <TD align=left height=26><INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['gid']?>' name=cid[]>
                                <!--INPUT type=hidden value='<?php echo $Rs['gid']?>' name=Ci[]--></TD>
															<TD align=left height=26><?php echo $Rs['gid']?></TD>
                              <TD height=26 align="left" noWrap>
                                <div id='bn<?php echo $i;?>'><a onClick="ChangeBnInnerHtml('bn<?php echo $i;?>','<?php echo $Rs['bn']?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Rs['bn']?></a></div>					  </TD>
                              <TD height=26 align=left nowrap>
                                <!--<div id='Bcatname<?php echo $i;?>'><a onClick="ChangeBcatnameInnerHtml('Bcatname<?php echo $i;?>','<?php echo trim($Rs['bid'])?>',<?php echo intval($Rs['gid'])?>)" > <?php echo $Broot = $Rs['catname']!="" ? $Rs['catname'] : "├─/";?></a></div>	-->
                                <?php echo $Broot = $Rs['catname']!="" ? $Rs['catname'] : "├─/";?>                      </TD>
                              <TD height=26 align=left nowrap><A href="javascript:toEdit('<?php echo $Rs['gid']?>',0)"><?php echo $Rs['goodsname']?></A>&nbsp;</TD>
                              <TD align=middle height=26><IMG onMouseOver="MM_showHideLayers('imgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('imgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/icon-viewpic.gif" width=18>
                                <DIV class=shadow id=imgLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 63px; POSITION: absolute; HEIGHT: 67px"   border="1"><IMG src="../<?php echo $INFO['good_pic_path']?>/<?php echo $Rs['smallimg']?>" ></DIV>					  </TD>
                              <TD align=center height=26>
                                <!--
					  <div id='pd<?php echo $i;?>'><a onClick="ChangePriceDescInnerHtml('pd<?php echo $i;?>','<?php echo $Rs['pricedesc']?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Rs['pricedesc']?></a></div>
					  -->
                                <?php echo $Rs['pricedesc']?>					  </TD>
                              <TD align=middle><?php echo $Rs['cost']?>	</TD>
                              <?php if ($_SESSION['LOGINADMIN_TYPE']  != 2) {  ?> <!--  这里是因为供应商不能修改发布，推荐，特价，热卖等资料-->
                              <TD align=middle height=26>
                                <div id='ifPub<?php echo $i;?>'><a onClick="ChangeIfPubInnerHtml('ifPub<?php echo $i;?>','<?php echo $Rs['ifpub']?>',<?php echo intval($Rs['gid'])?>)" ><img src="<?php echo $ifpub_pic?>" border="0" /></a></div>                      </TD>
                              <TD align=middle height=26>
                                <div id='ifRmb<?php echo $i;?>'><a onClick="ChangeifRmbInnerHtml('ifRmb<?php echo $i;?>','<?php echo $Rs['ifrecommend']?>',<?php echo intval($Rs['gid'])?>)" ><img src="<?php echo $ifrmd_pic?>" border="0" /></a></div>                      </TD>
                              <TD height=26 align=middle>
                                <div id='ifSpecial<?php echo $i;?>'><a onClick="ChangeifSpecialInnerHtml('ifSpecial<?php echo $i;?>','<?php echo $Rs['ifspecial']?>',<?php echo intval($Rs['gid'])?>)" ><img src="<?php echo $ifspec_pic?>" border="0" /></a></div>                      </TD>
                              <TD height=26 align=middle>
                                <div id='ifHot<?php echo $i;?>'><a onClick="ChangeifHotInnerHtml('ifHot<?php echo $i;?>','<?php echo $Rs['ifhot']?>',<?php echo intval($Rs['gid'])?>)" ><img src="<?php echo $ifhot_pic?>" border="0" /></a></div>					  </TD>
                              <?php } ?>
                              <TD align=center> <?php
					  if($Rs['checkstate']==0)
					  	echo "未審核";
					  elseif($Rs['checkstate']==1)
					  	echo "初審";
					  elseif($Rs['checkstate']==2 && $Rs['ifpub']==0)
					  	echo "複審";
					  elseif($Rs['checkstate']==2 && $Rs['ifpub']==1)
					  	echo "上架";
					  elseif($Rs['checkstate']==3)
					  	echo "退審<br>（原因：" . $Rs['nocheckreason'] . "）";
					  ?></TD>
                              <TD align=center><div class="link_box" style="width:40px"><a href="admin_goods_detail.php?goods_id=<?php echo $Rs['gid']?>" target="_blank">預覽</a></div></TD>

                              <TD align=center height=26>
                                <!--
					  <div id='kc<?php echo $i;?>'>
					  <a onClick="ChangeStorageInnerHtml('kc<?php echo $i;?>','<?php echo $Rs['storage']?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Rs['storage']?></a></div>
					  -->
                                <?php echo $Rs['storage']?>					  </TD>

                              <?php
                                $Sql_w      = "select COUNT(u.user_id) user_id from `{$INFO[DBPrefix]}waitbuy` w RIGHT JOIN `{$INFO[DBPrefix]}user` u ON w.user_id=u.user_id where gid=" . intval($Rs['gid']);
                                $Query_w    = $DB->query($Sql_w);
                                $Rs_w=$DB->fetch_array($Query_w);
                              ?>
                              <TD align=center nowrap="nowrap"><div class="link_box" style="width:80px"><a href="admin_waitbuy.php?gid=<?php echo $Rs['gid']?>"><?php echo "到貨通知 ".$Rs_w[user_id];?></a></div></TD>
                              <TD height=26 align=center>
                                <!--
					  <div id='sort<?php echo $i;?>'><a onClick="ChangeSortInnerHtml('sort<?php echo $i;?>','<?php echo $Rs['goodorder']?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Rs['goodorder']?></a></div>
	                  -->
                                <?php echo $Rs['goodorder']?>	                  </TD>
                            </TR>
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
