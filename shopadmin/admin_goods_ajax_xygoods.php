<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$Gid         = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');

$Sql         = "select gl.* ,g.goodsname,g.bn from `{$INFO[DBPrefix]}goods_xy` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.xygid=g.gid) where gl.gid=".intval($Gid)." order by gl.idate desc ";
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);



?>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
                  <TABLE class=allborder cellSpacing=3 cellPadding=3
                  width="100%" bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD>&nbsp;</TD></TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>

                      <TD width="84%" nowrap><TABLE border="0"  id="selectlink">
                        <TBODY>
                          <TR>
                            <TD vAlign=middle noWrap class="link_buttom"><a href="javascript:showWin('url','admin_goods_ajax_xygoodslist.php?gid=<?=intval($Gid)?>','',750,450);"><img src="images/<?php echo $INFO[IS]?>/fb-relatedpro.gif" width="32" height="32" border="0">&nbsp;請選擇超值任選的商品</a></TD>
                          </TR>
                        </TBODY>
                      </TABLE></TD>
                    </TR>
                      <TR align="center">
                      <TD valign="top" noWrap><input type="button" name="button" id="dellink" value="刪除" /></TD>
                      <TD valign="top" noWrap>&nbsp;</TD>
                      </TR>
                    <TR align="center">
                      <TD colspan="2" valign="top" noWrap>
<!--  start    -->

<TABLE cellSpacing=1 cellPadding=2  width="95%" bgColor=#666666 border=0>

 <FORM name=adminForm action="admin_goods_ajax_xygoodssave.php" method=post id="dellinkform">
   <INPUT type=hidden name=act value="Del">
   <INPUT type=hidden value=0  name=boxchecked>
   <INPUT type=hidden  name='gid' value="<?php echo $Gid?>">
   <INPUT type=hidden  name='Goodsname' value="<?php echo $Goodsname?>">
    <TBODY>
     <TR bgColor=#e7e7e7 height=25>
       <TD noWrap align=left width="6%"    height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Basic_Command['SNo_say'];//序号?></TD>
       <TD noWrap align=center width="22%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[Bn];//貨號?></TD>
       <TD noWrap align=left width="72%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[ProductName];//商品名称?></TD>
	   </TR>
	<?php
	$i=0;
	$j=1;
	while ($Result    = $DB->fetch_array($Query)) {
	?>
     <TR valign="top" bgColor=#f7f7f7>
 	   <TD align="left" valign="middle" class=unnamed1><INPUT id='cb<?php echo $i?>' type=checkbox value='<?php echo intval($Result['xyid'])?>' name='xyid[]'>&nbsp;<?php echo $j?><INPUT type=hidden value="<?php echo $Result['xyid']?>" name="Allid[]"><INPUT type=hidden value="<?php echo $Result['gid']?>" name="S_gid[]"></TD>
       <TD align="center" valign="middle" class=unnamed1>&nbsp;<?php echo $Result['bn']?></TD>
	   <TD align="left" valign="middle" class=unnamed1>&nbsp;<?php echo $Result['goodsname']?></TD>
	   </TR>
	<?php
	$j++;
	$i++;
	}
	?>
  </TBODY>
</FORM>
</TABLE>

 <!--   end    -->					   </TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD>&nbsp;            </TD></TR></TBODY></TABLE>
<script>
$(document).ready(function() {
var options = {
		success:       function(msg){
						if (msg=="1"){
								closeWin();
								showtajaxfun('xygoods');
							}
					},
		type:      'post',
		dataType:  'json',
		clearForm: true
	};
$("#dellink").click(function(){
					   //$("#linkgoodsform").attr("action","admin_goods_ajax_linkgoodslist.php");
					  // $("#act").attr("value","Search");
					   //alert($("#dellinkform").attr("action"));
					   $("#dellinkform").ajaxSubmit(options);
								});
 })
</script>