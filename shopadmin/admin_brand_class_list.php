<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
$brand_id = $_GET['brand_id'];

if(intval($_GET['top_id'])>0){
	$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}brand_class` where bid=".intval($_GET['top_id'])." limit 0,1");
	$Num_goods   = $DB->num_rows($Query_goods);
	if ($Num_goods>0){
		$Result_goods= $DB->fetch_array($Query_goods);
		$top_areaname =  $Result_goods['catname'];
		
	}
}
$Sql      = "select * from `{$INFO[DBPrefix]}brand_class` where brand_id='" . $brand_id . "' and top_id='" . intval($_GET['top_id']) . "' order by catord asc";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
$Query_b = $DB->query("select * from `{$INFO[DBPrefix]}brand` where brand_id=".intval($brand_id)." limit 0,1");
$Result_b = $DB->fetch_array($Query_b);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Brand_Man] ;//品牌管理?>--&gt;<?php echo $JsMenu[Product_Class_List];//商品类别列表?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
  #sortable li span { position: absolute; margin-left: -1.3em; }
  </style>
<script>
  $( function() {
    $( "#sortable" ).sortable({
                stop: function(even, ui) {
                   // $.cookie('sort', $('#sortable').sortable("toArray").join(","), { expire: 7 });
					mlists = $('#sortable').sortable("toArray").join(",");
					$.ajax({
							url: "admin_pcat_save.php",
							data: "act=sort&list=" + mlists,
							type:'post',
							dataType:"html",
							success: function(msg){
								//alert(msg);
							}
					});
                }
            });
    $( "#sortable" ).disableSelection();
  } );
  </script>
<SCRIPT language=javascript>

function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		document.adminForm.action = "admin_brand_class.php?Action=Modi&top_id=<?php echo $_GET['top_id'];?>&bid="+checkvalue;
		document.adminForm.Action.value="Modi";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_brand_class_save.php?top_id=<?php echo $_GET['top_id'];?>";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}


</SCRIPT>
<div id="contain_out">
 
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p9orange>
                      <?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<a href="admin_brand_list.php"><?php echo $JsMenu[Brand_Man] ;//品牌管理?></a>--&gt;<a href="admin_brand_class_list.php?brand_id=<?php echo $brand_id;?>&top_id=0">商品品牌【<b><?php echo $Result_b['brandname'];?></b>】下屬分類</a><?php if (intval($top_id) > 0){?>--&gt;<?php echo $top_areaname;}?>
                      </TD>
                    </TR>
                  </TBODY>
                </TABLE>
              
              </TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
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
                                  <TD vAlign=bottom noWrap class="link_buttom"><A href="admin_brand_class.php?brand_id=<?php echo $_GET['brand_id'];?>&top_id=<?php echo $_GET['top_id'];?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增同級分類</A></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><A href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></A></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><A href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></A></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle></TD>
                  </TR>
                </TBODY>
              </TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                    <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden name=Action>
                          <INPUT type=hidden value=0  name=boxchecked>
							  
                     <!--ul id="sortable">
			  <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 1</li>
			  <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 2</li>
			  <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 3</li>
			  <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 4</li>
			  <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 5</li>
			  <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 6</li>
			  <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 7</li>
			</ul-->
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        
                          <thead>
                            <TR align=middle>
                              <TD width="10" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="52" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>序號</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>ID</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 分類名稱</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>商品數量</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>設置</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>編輯</TD>
                              </TR>
								</thead>
                           <TBODY id="sortable">
                            <?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {


					?>
                            <TR class=row0 id="<?php echo $Rs['bid']?>">
                              <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['bid']?>' name=bid[] > </TD>
                              <TD align="center" noWrap><?php echo $j?></TD>
                              <TD align="left" noWrap><?php echo $Rs['bid']?></TD>
                              <TD height=26 align="left" noWrap><A href="admin_goods_list.php?brand_bid=<?php echo $Rs['bid']?>"><?php echo $Rs['catname']?></a></TD>
                              <TD align="left" noWrap>
                              <?php
								$Sqlc = "select count(*) as totalcount from `{$INFO[DBPrefix]}goods` where brandbids like '%\"".$Rs['bid']."\"%' or bid='" . $Rs['bid'] . "'";
						        $Queryc  = $DB->query($Sqlc);
						        $Resultc = $DB->fetch_array($Queryc);
						        echo intval($Resultc['totalcount']);
							  ?>
							  </TD>
                              <TD align="left" noWrap><a href="admin_brand_class.php?Action=Modi&brand_id=<?php echo $_GET['brand_id'];?>&top_id=<?php echo $Rs['bid']?>">新增下級分類</a> <a href="admin_brand_class_list.php?brand_id=<?php echo $_GET['brand_id'];?>&top_id=<?php echo $Rs['bid']?>">下級分類列表</a></TD>
                              <TD align="left" noWrap><a href="admin_brand_class.php?Action=Modi&brand_id=<?php echo $_GET['brand_id'];?>&bid=<?php echo $Rs['bid']?>">編輯</a></TD>
                              </TR>
                            <?php
					$j++;
					$i++;
					}
					?>
                          </TBODY>
                        </TABLE></FORM>
                      </TD>
                    </TR>
                </TABLE>
              <?php  if ($Num>0){ ?>
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                      </TD>
                    </TR>
                </TABLE>
              <?php } ?>	
              </TD>
            </TR>
        </TABLE>
    </TD>
    </TR>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
