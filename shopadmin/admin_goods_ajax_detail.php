<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$goods_id = intval($_GET['goods_id']);

$Sql      = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . intval($goods_id) . "' order by detail_id desc ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);


$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($goods_id)." limit 0,1");
$Num_goods   = $DB->num_rows($Query_goods);
if ($Num_goods>0){
	$Result_goods= $DB->fetch_array($Query_goods);
	$Goodsname  =  $Result_goods['goodsname'];
}

?>
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
</script>
 <TABLE class=allborder cellSpacing=0 cellPadding=0 width="100%" bgColor=#f7f7f7 border=0  >
                    <TBODY>
                    <TR>
                      <TD align=right valign="middle" noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD align=right valign="middle" noWrap><a href="#" onclick="showWin('url','admin_goods_ajax_detailadd.php?goods_id=<?=intval($goods_id)?>','',750,450);">新增</a> | <a href="#" id="delgdetail">刪除</a></TD>
                    </TR>
               	 <tr>
                	<td>
                    <FORM name=adminForm id="mdetailform" action="admin_goods_ajax_detail_save.php" method=post>
					<INPUT type=hidden name=act id=act value="Del">
					<INPUT type=hidden name=Action>
					<INPUT type=hidden value=0  name=boxchecked  id=boxchecked> 
                    <TABLE class=listtable cellSpacing=0 cellPadding=0 bgColor=#ffffff width="100%" border=0 id="orderedlist">
                    <TBODY>
                    <TR align=middle>
                      <TD width="2%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
					  <!--INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle--> </TD>
                      <TD width="6%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>序號</TD>
                      <TD width="8%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 型號</TD>
                      <TD width="36%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>商品名稱</TD>
                      <TD width="19%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>描述</TD>
                      <TD width="10%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>原價</TD>
                      <TD width="10%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>售價</TD>
                      <TD width="9%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>成本</TD>
                      <TD width="9%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>庫存</TD>
                      <!--TD width="70" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員價格</TD-->
                    </TR>
					<?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {


					?>
                    <TR class=row0>
                      <TD height=26 align=middle>
					  <INPUT id='cb<?php echo $i?>'  type=checkbox value='<?php echo $Rs['detail_id']?>' name=detail_id[]></TD>
                      <TD align="center" noWrap><?php echo $j?></TD>
                      <TD height=26 align="left" noWrap><a href="#" onclick="showWin('url','admin_goods_ajax_detailadd.php?Action=Modi&detail_id=<?php echo $Rs['detail_id']?>&goods_id=<?=intval($goods_id)?>','',750,450);"><?php echo $Rs['detail_bn']?></a></TD>
                      <TD align="center" noWrap><a href="#" onclick="showWin('url','admin_goods_ajax_detailadd.php?Action=Modi&detail_id=<?php echo $Rs['detail_id']?>&goods_id=<?=intval($goods_id)?>','',750,450);"><?php echo $Rs['detail_name']?></a></TD>
                      <TD align="center" noWrap><?php echo $Rs['detail_des']?></TD>
                      <TD align="center" noWrap><?php echo $Rs['detail_price']?></TD>
                      <TD align="center" noWrap><?php echo $Rs['detail_pricedes']?></TD>
                      <TD align="center" noWrap><?php echo $Rs['detail_cost']?>&nbsp;</TD>
                      <TD align="center" noWrap><?php echo $Rs['storage']?></TD>
                      <!--TD align="center" noWrap><span class="p9black"><a href="#" onclick="showWin('url','admin_goods_ajax_memberprice.php?gid=<?php echo $Rs['gid'];?>&detail_id=<?php echo $Rs['detail_id']?>&goods_id=<?=intval($goods_id)?>','',750,450);">設定</a></span></TD-->
                    </TR>
					<?php
					$j++;
					$i++;
					}
					?>
                    
          <?php  if ($Num==0){ ?>
                    <TR align="center">
                      <TD height=14 colspan="10"><?php echo $Basic_Command['NullDate']?></TD>
                      </TR>
		   <?php } ?>	
					 </TABLE>
                     </FORM>
                  </Td>
                     </TR>
					<TR>
                      <TD align=right valign="middle" noWrap>&nbsp;</TD>
                    </TR>
                    </TBODY>
</Table>
<script language="javascript">
var options1 = {
		success: function(msg){showtajaxfun("goodsdetail");},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
$("#delgdetail").click(function(){
	if (confirm("您確認要刪除這些商品？")){
		$("#mdetailform").attr("action","admin_goods_ajax_detail_save.php");
		//$("#act").attr("value","Del");
		//alert($("#mdetailform").attr("action"));
		$('#mdetailform').ajaxSubmit(options1);
	}
					   
});
</script>
