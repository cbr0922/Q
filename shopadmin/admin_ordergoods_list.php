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

include_once "Time.class.php";
$TimeClass = new TimeClass;

$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time());
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());

$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;

if (intval($_GET[top_id])!=0 ){
	
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
	$class_array  = explode(",",$Next_ArrayClass);
		
	if (count($class_array)>0){
		$AddBidtype = " and g.bid in (" . implode(",",$class_array) . "," . intval($_GET[top_id]) . ")";
	}else{
		$AddBidtype = " and 1<>1";	
	}
	
}

$Where    = $_GET['skey']!="" ?  " and ( g.goodsname like '%".trim(urldecode($_GET['skey']))."%' ) "  : $Where ;
$Where2    = $_GET['skey']!="" ?  " and ( od.goodsname like '%".trim(urldecode($_GET['skey']))."%' )"  : $Where2 ;

$Sql      = "select g.*,bc.catname,o.order_id from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}goods` g on od.gid=g.gid  left join `{$INFO[DBPrefix]}bclass`  bc  on (g.bid=bc.bid) inner join `{$INFO[DBPrefix]}order_table` o on o.order_id=od.order_id where o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' " . $Where . $AddBidtype;
$Sql      = $Sql." group by g.gid order by g.idate desc , g.goodorder desc ";

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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/suggestion.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK href="css/css.css" type=text/css rel=stylesheet />
<title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_List];//全部商品列表?></title>
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
<?php include_once "head.php";?>
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
function su(){
	formExcel.submit();
}
</script>

<form name="formExcel" method="post" action="admin_goods_excel.php"  >
<input type="hidden" name="Action" value="Excel">
</form>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD><TABLE width="80%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
                <TR>
                  <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                  <TD class=p12black noWrap><SPAN class=p9orange>訂單管理--&gt;商品即時庫存統計
                    </SPAN></TD>
                </TR>
              </TBODY>
              </TABLE></TD>
          </TR>
        </TBODY>
  </TABLE>
      
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        
        <FORM name=searchbanner method=get action="">        
          <input type="hidden" name="Action" value="Search">
          
          <TR>
            <TD align=right height=31>
              <TABLE class=p9black cellSpacing=0 cellPadding=0 width=100% border=0 style="margin-bottom:5px">
                <TBODY>
                  <TR>
                    <TD height=31 align=left nowrap>From
                      <INPUT   id=begtime size=10 value="<?php echo $begtime?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />
                      To
                      <INPUT    id=endtime size=10 value="<?php echo $endtime?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />
                      <?php echo  $Char_class->get_page_select("top_id",$_GET[top_id],"  class=\"trans-input\" ");?>
                      <INPUT  name='skey'  size="20">
                      <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField onClick="return search_img();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="input02" id="show_Content" style="display:none">&nbsp;</div></TD>
                    <TD width="14%" align="center" vAlign=center nowrap class=p9black><?php echo $Basic_Command['PerPageDisplay'];//每页显示?> 
                      <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.searchbanner.submit(); ",$Array=array('2','10','15','20','30','50','100'))?></TD>
                  </TR>
                </TBODY>
              </TABLE>
            </TD>
          </TR>
        </FORM>
  </TABLE>
      
      
      <TABLE cellSpacing=0 cellPadding=0 width="100%" class="allborder">
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
                              <TD width="15%"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>
                              <TD width="10%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <?php echo $Admin_Product[PrductClassName];//商品類別名稱?>					  </TD>
                              <TD width="25%" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductName];//商品名稱?>&nbsp;</TD>
                              <TD width="10%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductSmPic];//缩图?><BR></TD>
                              <TD width="8%"  height=26 align="center"  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductNetPrice];//網購價?></TD>
                              
                              <TD width="8%"  height=26 align="right" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[storage];//库存?><BR></TD>
                              <TD width="12%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>未確認訂單數量</TD>
                              <TD width="12%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>已確認數量</TD>
                              <TD width="12%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>已付款數量</TD>
                              <TD width="12%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>已完成訂單的出貨數量</TD>
                              
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

					?><TBODY>
                              <TR class=row0>
                                <TD height=26 align="center" noWrap>
                                  <div id='bn<?php echo $i;?>'><?php echo $Rs['bn']?></div>					  </TD>
                                <TD height=26 align=left nowrap>
                                  <div id='Bcatname<?php echo $i;?>'><?php echo $Broot = $Rs['catname']!="" ? $Rs['catname'] : "├─/";?></div>                      </TD>
                                <TD height=26 align=left nowrap><?php echo $Rs['goodsname']?>&nbsp;</TD>
                                <TD align=middle height=26><IMG onMouseOver="MM_showHideLayers('imgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('imgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/icon-viewpic.gif" width=18>
                                  <DIV class=shadow id=imgLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 63px; POSITION: absolute; HEIGHT: 67px"   border="1"><IMG src="../<?php echo $INFO['good_pic_path']?>/<?php echo $Rs['smallimg']?>" ></DIV>					  </TD>
                                <TD align=center height=26>
                                  <div id='pd<?php echo $i;?>'><?php echo $Rs['pricedesc']?></div>
                                </TD>
                                
                                <?php
					   $Sql_s      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($Rs['gid']);
						$Query_s    = $DB->query($Sql_s);
						$Nums      = $DB->num_rows($Query_s);
					  ?>
                                <TD align=right height=26>
                                  <div id='kc<?php echo $i;?>'><a onClick="ChangeStorageInnerHtml('kc<?php echo $i;?>','<?php echo $Rs['storage']?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Rs['storage']?></a><?php if ((trim($Rs['good_color'])!="" || trim($Rs['good_size'])!="") && $Nums>0){?><!--IMG onMouseOver="MM_showHideLayers('storageLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('storageLayer<?php echo $i?>','','hide')" height=18 src="images/nd0051-16.gif" width=18--><?php }?></div>
                                  <DIV class=shadow id=storageLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 150px; POSITION: absolute; HEIGHT: 67px"   border="1"><table cellpadding="0" cellspacing="0" border="0" width="150">
                                    <tr class=row0><td align="center">顏色</td><td align="center">尺寸</td><td align="center">詳細庫存</td></tr>
                                    <?php 
					 
						if (trim($Rs['good_color'])!=""){
							$Good_color_array    =  explode(',',trim($Rs['good_color']));
					
							if (!is_array($Good_color_array)){
								$Good_color_array = array("");
							}
						}else {
							$Good_color_array = array("");
						}
						if (trim($Rs['good_size'])!=""){
							$Good_size_array    =  explode(',',trim($Rs['good_size']));
					
							if (!is_array($Good_color_array)){
								$Good_size_array = array("");
							}
						}else {
							$Good_size_array = array("");
						}
						while ($Rs_s=$DB->fetch_array($Query_s)) {
						if ((in_array($Rs_s['color'],$Good_color_array) || trim($Rs_s['color']) == "") && (in_array($Rs_s['size'],$Good_size_array) || trim($Rs_s['size'])=="")){s
					  ?>
                                    <tr class=row0><td align="center"><?php echo $Rs_s['color']?>&nbsp;</td><td align="center"><?php echo $Rs_s['size']?>&nbsp;</td><td align="center"><?php echo $Rs_s['storage']?>&nbsp;</td></tr>
                                    <?php
					  }
					  }
					  ?>
                                    </table></DIV>
                                </TD>
                                <TD align=center>
                                  <?php
                      $sql1 = "select sum(od.goodscount) as goodscount from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}order_table` o on o.order_id=od.order_id where od.gid='" . $Rs['gid'] . "' and od.detail_order_state=0 and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix'" . $Where2;
					  $Query1    = $DB->query($sql1);
					  $Rs1=$DB->fetch_array($Query1);
					  echo intval($Rs1['goodscount']);
					  ?>
                                </TD>
                                <TD align=center>
                                  <?php
                     // $sql2 = "select sum(goodscount) as goodscount from `{$INFO[DBPrefix]}order_detail` where gid='" . $Rs['gid'] . "' and detail_order_state=1 and detail_pay_state<>1";
					   $sql2 = "select sum(od.goodscount) as goodscount from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}order_table` o on o.order_id=od.order_id where od.gid='" . $Rs['gid'] . "' and od.detail_order_state=1 and od.detail_pay_state<>1 and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix'" . $Where2;
					  $Query2    = $DB->query($sql2);
					  $Rs2=$DB->fetch_array($Query2);
					  echo intval($Rs2['goodscount']);
					  ?>
                                </TD>
                                <TD align=center>
                                  <?php
                      //$sql3 = "select sum(goodscount) as goodscount from `{$INFO[DBPrefix]}order_detail` where gid='" . $Rs['gid'] . "' and detail_pay_state=1";
					   $sql3 = "select sum(od.hadsend) as goodscount from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}order_table` o on o.order_id=od.order_id where od.gid='" . $Rs['gid'] . "' and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' and od.detail_pay_state=1" . $Where2;
					  $Query3    = $DB->query($sql3);
					  $Rs3=$DB->fetch_array($Query3);
					  echo intval($Rs3['goodscount']);
					  ?>
                                </TD>
                                <TD align=center>
                                  <?php
                      //$sql3 = "select sum(goodscount) as goodscount from `{$INFO[DBPrefix]}order_detail` where gid='" . $Rs['gid'] . "' and detail_pay_state=1";
					   $sql3 = "select sum(od.hadsend) as goodscount from `{$INFO[DBPrefix]}order_detail` as od inner join `{$INFO[DBPrefix]}order_table` o on o.order_id=od.order_id where od.gid='" . $Rs['gid'] . "' and o.createtime>='$begtimeunix' and o.createtime<='$endtimeunix' and od.detail_order_state=4" . $Where2;
					  $Query3    = $DB->query($sql3);
					  $Rs3=$DB->fetch_array($Query3);
					  echo intval($Rs3['goodscount']);
					  ?>
                                </TD>
                                
                              </TR></TBODY>
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
                  </TR></TABLE>
              <?php
            }
			?>
              
              
            </TD></TR>
  </TABLE>
</div>
 <div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>




