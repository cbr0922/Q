<?php

include_once "Check_Admin.php";

/**

 *  装载语言包

 */

include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

include "../language/".$INFO['IS']."/StaticHtml_Pack.php";



include_once "pagenav_stard.php";

include RootDocumentShare."/cache/Productclass_show.php";

$objClass = "9pv";

$Nav      = new buildNav($DB,$objClass);





$Where    = $_GET['skey']!="" ?  " and ( g.goodsname like '%".trim(urldecode($_GET['skey']))."%' ) "  : $Where ;

$Where2    = $_GET['skey']!="" ?  " and ( od.goodsname like '%".trim(urldecode($_GET['skey']))."%' )"  : $Where2 ;



$Sql      = "select g.*,bc.* from `{$INFO[DBPrefix]}goods` g left join `{$INFO[DBPrefix]}bclass`  bc  on (g.bid=bc.bid)  where 1=1 ";


if($_GET['provider'] != "na" && isset($_GET['provider'])){
	if($_GET['provider'] == "yes"){
		$Where .= " AND provider_id=".intval($_GET['provider_id']);
	}else if($_GET['provider'] == "no"){
		if($_GET['provider_id'] == 0 && isset($_GET['provider_id'])){
			$Where .= " AND provider_id>".intval($_GET['provider_id']);
		}else{
			$Where .= " AND provider_id=".intval($_GET['provider_id']);
		}
		if($_GET['item_id']!="" && isset($_GET['item_id'])){
			$Where .= " AND `gid`=".intval($_GET['item_id']);
		}
	}
}

if($_GET['top_id'] != 0 && isset($_GET['top_id'])){
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
	$Next_ArrayClass  = array_filter(explode(",",$Next_ArrayClass));
	$Array_class      = array_unique($Next_ArrayClass);
	//print_r($Array_class);
	if (count($Array_class)>0){
		$top_ids = intval($_GET['top_id']).",".implode(",",$Array_class);
		$Where .= " AND g.bid in (".$top_ids.")";
	}else{
		$top_ids = intval($_GET['top_id']);
		$Where .= " AND g.`bid`=".$top_ids;
	}
}

$Sql      = $Sql.$Where." group by g.gid order by g.idate desc , g.goodorder desc ";

$Query    = $DB->query($Sql);

$Num      = $DB->num_rows($Query);

if ($Num>0){

	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;

	$Nav->total_result=$Num;

	$Nav->execute($Sql,$limit);

	$Query = $Nav->sql_result;

	$Nums     = $Num<$limit ? $Num : $limit ;

}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>訂單管理--&gt;商品庫存統計</title>

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<SCRIPT src="include/common.js"  language="javascript"></SCRIPT>

<SCRIPT src="include/calendar.js"   language="javascript"></SCRIPT>

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

<?php  include_once "Order_state.php";?>

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

<div id="contain_out">

<form name="formExcel" method="post" action="admin_goods_excel.php"  >

<input type="hidden" name="Action" value="Excel">

</form>

<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>

  <TBODY>

  <TR>

    <TD vAlign=top width="100%" height=302>

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD><TABLE width="80%" border=0 cellPadding=0 cellSpacing=0>

              <TBODY>

                <TR>

                  <TD width=38 height="43"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>

                  <TD class=p12black noWrap><SPAN class=p9orange>訂單管理--&gt;商品庫存統計

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

              <TABLE class=p9black cellSpacing=0 cellPadding=0 width=100% border=0>

                <TBODY>

                  <TR>

                    <TD height=39 align=left nowrap>

                      <div class="input02" id="show_Content" style="display:none">&nbsp;</div>

                      <table width="330" border="0" cellspacing="0" cellpadding="0">

                        <tr>
						  <TD width="30" align=left class=p9black>關鍵字：</TD>
                          <td width="211" align="left"><INPUT  name='skey' size="30"></td>
                        </tr>

                      </table></TD>

                    </TR>



                  </TBODY>

                            <TR>
<TD height=39 align=left nowrap>
<table width="330" border="0" cellspacing="0" cellpadding="0">
<TR>
            <TD height="30" align=left class=p9black>商品：</TD>

            <TD height="0" colspan="2" align=left class=p9black>
            	<input type="radio" name="provider" value="na" checked="checked" />全部
				<input type="radio" name="provider" value="yes" <?php if($_GET['provider'] == "yes") echo "checked";?>/>統倉
				<input type="radio" name="provider" value="no" <?php if($_GET['provider'] == "no") echo "checked";?>/>非統倉　供應商

                <select name="provider_id">
                	<?php
					$provider = "";
					$Sql_s      = "SELECT `provider_id`,`provider_name` FROM `{$INFO[DBPrefix]}provider` ORDER BY provider_id desc";
					$Query_s    = $DB->query($Sql_s);
					$Num_s      = $DB->num_rows($Query_s);

					while ($Rs_s=$DB->fetch_array($Query_s)) {
						$provider .="<option value=".$Rs_s['provider_id']." ";

						if ($_GET['provider_id'] == $Rs_s['provider_id'])
							$provider .= " selected";

						$provider .= " >".$Rs_s['provider_name']."</option>\n";
					}
					?>
                	<option value="0">請選擇供應商</option>
                	<?php echo $provider;?>
             	</select>

                輸入商品ID或產品編號<input size="20" name="item_id" onkeyup="value=value.replace(/[^0-9]/g,'')" value='<?php echo $_GET['item_id'];?>' />
           	</TD>

          </TR>
</table>
</TD>
</TR>


<TR>
<TD height=39 align=left nowrap>
<table width="300" border="0" cellspacing="0" cellpadding="0">
          <TR>

            <TD height="30" align=left class=p9black>商品分類：</TD>

            <TD height="0" colspan="2" align=left class=p9black>
            	<?php echo $Char_class->get_page_select("top_id",$_GET['top_id'],"  class=\"trans-input\" ");?>
            </TD>
            <td width="40" align="center"><input type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField onClick="return search_img();"></td>

            <td width="79" align="center"><input type=button  name=imageField2 value="匯出" onClick="location.href='admin_ordergoods_excel.php?<?php echo $_SERVER['QUERY_STRING']?>'" /></td>

            </TR>
</table>
</TD>
<TD width="14%" align="right" vAlign=center nowrap class=p9black><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>

                      <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.searchbanner.submit(); ",$Array=array('2','10','15','20','30','50','100'))?></TD>
          </TR>

                </TABLE>
</TD>
            </TR>

          </FORM>

        </TABLE>





      <TABLE cellSpacing=0 cellPadding=0 width="100%" class=allborder>

        <TBODY>

          <TR>

            <TD vAlign=top height=210><TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">

                        <FORM name=adminForm action='' method=post>

                          <INPUT type=hidden name=act>

                          <INPUT type=hidden name=Where>

                          <input type=hidden name=doaction >

                          <INPUT type=hidden value=0  name=boxchecked>

                          <TBODY>

                            <TR align=middle>

                              <TD width="13%"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>

                              <TD width="30%" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductName];//商品名稱?>&nbsp;</TD>

                              <TD width="13%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>尺寸</TD>

                              <TD width="13%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>顏色</TD>

                              <TD width="13%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>規格</TD>
                              <TD width="13%" align="right" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> 預計<?php echo $Admin_Product[storage];//库存?>量 </TD>

                              <TD width="21%"  height=26 align="right" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>目前<?php echo $Admin_Product[storage];//库存?>量<BR></TD>

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

						$sales	= $Rs['sales'];

						$size_Sql = "select * from `{$INFO[DBPrefix]}storage` where goods_id='" . $Rs['gid'] . "' and (color!='' or size!='')";
						$size_Query =  $DB->query($size_Sql);
						$size_Num   =  $DB->num_rows($size_Query );
						if ($size_Num>0){
							while ($size_Rs = $DB->fetch_array($size_Query)) {
								$sales += intval($size_Rs['sales']);
							}
						}
						$detail_Sql   =  "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $Rs['gid'] . "'";
						$detail_Query =  $DB->query($detail_Sql);
						$detail_Num   =  $DB->num_rows($detail_Query );
						if ($detail_Num>0){
							while ($detail_Rs = $DB->fetch_array($detail_Query)) {
								$sales += intval($detail_Rs['sales']);
							}
						}

					?>

                            <TR class=row0>

                              <TD height=26 align="center" noWrap>

                                <div id='bn<?php echo $i;?>'><?php echo $Rs['bn']?></div>					  </TD>

                              <TD height=26 align=left nowrap><?php echo $Rs['goodsname']?>&nbsp;</TD>

                              <TD align=center>&nbsp;</TD>

                              <TD align=center>&nbsp;</TD>

                              <TD align=center>&nbsp;</TD>
                              <TD align=right><?php echo $sales?></TD>

                              <TD align=right height=26>

                                <?php
																if($Rs['storage']=='') echo 0;
																else echo $Rs['storage'];
																?>

                                </TD>

                              </TR>

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

					$Sql_s = "select * from `{$INFO[DBPrefix]}storage` where goods_id='" . $Rs['gid'] . "' and (color!='' or size!='')";

					$Query_s    = $DB->query($Sql_s);

					while ($Rs_s=$DB->fetch_array($Query_s)) {

						if ((in_array($Rs_s['color'],$Good_color_array) || trim($Rs_s['color']) == "") && (in_array($Rs_s['size'],$Good_size_array) || trim($Rs_s['size'])=="")){

							$goods_Sql = "select * from `{$INFO[DBPrefix]}attributeno` where gid='" . $Rs['gid'] . "' and size='" . $Rs_s['size'] . "' and color='" . $Rs_s['color'] . "'";

	$goods_Query =  $DB->query($goods_Sql);

	$goods_Num   =  $DB->num_rows($goods_Query );

	if ($goods_Num>0){

		$goods_Rs = $DB->fetch_array($goods_Query);

		$goodsno = ($goods_Rs['goodsno']);

	}

					?>

                            <TR class=row0>

                              <TD height=26 align="center" noWrap><?php echo $goodsno?></TD>

                              <TD height=26 align=left nowrap></TD>

                              <TD align=center><?php echo $Rs_s['size'];?></TD>

                              <TD align=center><?php echo $Rs_s['color'];?></TD>

                              <TD align=center>&nbsp;</TD>
                              <TD align=right><?php echo $Rs_s['sales']?></TD>

                              <TD align=right height=26>

                                <?php echo $Rs_s['storage']?>

                                </TD>

                              </TR>

                            <?php

						}

					}

					$Sql_s = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $Rs['gid'] . "' ";

					$Query_s    = $DB->query($Sql_s);

					while ($Rs_s=$DB->fetch_array($Query_s)) {

					?>

                            <TR class=row0>

                              <TD height=26 align="center" noWrap><?php echo $Rs_s['detail_bn']?></TD>

                              <TD height=26 align=left nowrap></TD>

                              <TD align=center></TD>

                              <TD align=center></TD>

                              <TD align=center><?php echo $Rs_s['detail_name']?></TD>
                              <TD align=right><?php echo $Rs_s['sales']?></TD>

                              <TD align=right height=26>

                                <?php echo $Rs_s['storage']?>

                                </TD>

                              </TR>



                            <?php

					}

					$i++;

					}

					?>

                          </FORM>

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

    </TD>

  </TR>

</TBODY></TABLE>

</div>

<div align="center"><?php include_once "botto.php";?></div>

</BODY>

</HTML>
