<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$goods_id = intval($_GET['goods_id']);

$Sql      = "select * from `{$INFO[DBPrefix]}goods_saleoffe` where gid='" . intval($goods_id) . "' order by soid desc ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);


$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($goods_id)." limit 0,1");
$Num_goods   = $DB->num_rows($Query_goods);
if ($Num_goods>0){
	$Result_goods= $DB->fetch_array($Query_goods);
	$Goodsname  =  $Result_goods['goodsname'];
}

?>
<!--script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script-->
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
 <TABLE class=allborder cellSpacing=0 cellPadding=0 width="100%" bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD align=right valign="middle" noWrap><a href="#" onclick="showWin('url','admin_goods_ajax_saleoffeadd.php?goods_id=<?=intval($goods_id)?>','',350,250);">新增</a> | <a href="#" id="delgsaleoffe">刪除</a></TD>
                    </TR>
               	 <tr>
                	<td>
                    <FORM name=saleform id="saleform" action="" method=post>
					<INPUT type=hidden name="acts" id="acts">
					<INPUT type=hidden name=Action>
					 <INPUT type=hidden value=0  name=boxchecked>
                    <TABLE class=listtable cellSpacing=0 cellPadding=0 bgColor=#ffffff width="100%" border=0 id="orderedlist">
                    <TBODY>
                    <TR align=middle>
                      <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26 width="20">
					  <!--INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle--> </TD>
                      <TD width="52" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>序號</TD>
                      <TD width="100"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 購買最小數量</TD>
                      <TD width="300" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>購買最大數量</TD>
                      <TD width="70" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>售價</TD>
                      <?php
                      $Sql_l      = "select *  from `{$INFO[DBPrefix]}user_level` u order by u.level_id asc";
						$Query_l    = $DB->query($Sql_l);
						$Nums_l      = $DB->num_rows($Query_l);
						while ($Rs_l=$DB->fetch_array($Query_l)) {
					  ?>
                      <TD width="70" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Rs_l['level_name']?>價格</TD>
                      <?php
						}
					  ?>
                    </TR>
					<?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {


					?>
                    <TR class=row0>
                      <TD width="20" height=26 align=middle>
					  <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['soid']?>' name=soid[]> </TD>
                      <TD align="center" noWrap><?php echo $j?></TD>
                      <TD height=26 align="center" noWrap><a href="#" onclick="showWin('url','admin_goods_ajax_saleoffeadd.php?Action=Modi&soid=<?php echo $Rs['soid']?>&goods_id=<?=intval($goods_id)?>','',350,250);"><?php echo $Rs['mincount']?></a></TD>
                      <TD align="center" noWrap><?php echo $Rs['maxcount']?></TD>
                      <TD align="center" noWrap><?php echo $Rs['price']?></TD>
                      <?php
                       $Sql_l      = "select u.*,mp.*  from `{$INFO[DBPrefix]}user_level` u left join `{$INFO[DBPrefix]}member_price` as mp on mp.m_level_id=u.level_id where mp.m_saleoffid='" . $Rs['soid'] . "' order by u.level_id asc";
						$Query_l    = $DB->query($Sql_l);
						$Nums_l      = $DB->num_rows($Query_l);
						while ($Rs_l=$DB->fetch_array($Query_l)) {
					  ?>
                      <TD width="70" align="center" noWrap >
                      <?php
                      //$Sql_M    = "select * from `{$INFO[DBPrefix]}member_price` where m_level_id=".intval($Rs_l['level_id'])." limit 0,1";
					  //$Query_M  = $DB->query($Sql_M);
					  //$Result_M = $DB->fetch_array($Query_M);
					 
					  echo $Rs_l['m_price'];
					  ?>
                      </TD>
                      <?php
						}
					  ?>
                    </TR>
					<?php
					$j++;
					$i++;
					}
					?>
                    
          <?php  if ($Num==0){ ?>
                    <TR align="center">
                      <TD height=14 colspan="5"><?php echo $Basic_Command['NullDate']?></TD>
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
		success: function(msg){showtajaxfun("goodssaleoffe");},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
$("#delgsaleoffe").click(function(){
	if (confirm("您確認要刪除這些價格？")){
		$("#saleform").attr("action","admin_goods_ajax_saleoffe_save.php");
		$("#acts").attr("value","Del");
		//alert($("#act").attr("value"));
		$('#saleform').ajaxSubmit(options1);
	}
					   
});
</script>
