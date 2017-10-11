<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$Gid         = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');

$Sql         = "select gl.* ,g.goodsname,g.bn from `{$INFO[DBPrefix]}goods_change` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.changegid=g.gid) where gl.gid=".intval($Gid)." order by gl.idate desc ";
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);



?>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<script>
var optionsc = {
		success:       function(msg){
			//alert(msg);
						if (msg=="1"){
								closeWin();
								showtajaxfun('changegoods');
							}
						if (msg=="2"){
							alert('請填寫正確的價格');
								closeWin();
								showtajaxfun('changegoods');
							}
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};

</script>

                  <TABLE class=allborder cellSpacing=3 cellPadding=3
                  width="100%" bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD>&nbsp;</TD></TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>

                      <TD width="84%" nowrap><TABLE border="0">
                        <TBODY>
                          <TR>
                            <TD vAlign=middle noWrap class="link_buttom"><a href="javascript:showWin('url','admin_goods_ajax_changegoodslist.php?gid=<?=intval($Gid)?>','',750,450);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-relatedpro.gif" border="0" align="absmiddle" /> 請選擇加購商品</a></TD>
                          </TR>
                        </TBODY>
                      </TABLE></TD>
                    </TR>
                      <TR align="center">
                      <TD valign="top" noWrap><input type="button" name="button" id="dellink" value="刪除" onclick='$("#actchangec").attr("value","Del");$("#dellinkform").ajaxSubmit(optionsc);' /><input type="button" name="button" id="savechange" value="保存" onclick='$("#actchangec").attr("value","update");$("#dellinkform").ajaxSubmit(optionsc);' /></TD>
                      <TD valign="top" noWrap>&nbsp;</TD>
                      </TR>
                    <TR align="center">
                      <TD colspan="2" valign="top" noWrap>
<!--  start    -->
 <FORM name=adminForm action="admin_goods_ajax_changegoodssave.php" method=post id="dellinkform">
   <INPUT type=hidden name=actchangec id="actchangec" value="">
   <INPUT type=hidden value=0  name=boxchecked>
   <INPUT type=hidden  name='gid' value="<?php echo $Gid?>">
   <INPUT type=hidden  name='Goodsname' value="<?php echo $Goodsname?>">
<TABLE class=allborder cellSpacing=1 cellPadding=0  width="95%" bgColor=#666666 border=0>
    <TBODY>
     <TR bgColor=#e7e7e7 height=25>
       <TD noWrap align=left width="2%"    height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Basic_Command['SNo_say'];//序号?></TD>
       <TD noWrap align=center width="8%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[Bn];//貨號?></TD>
       <TD noWrap align=middle width="42%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[ProductName];//商品名称?></TD>
       <TD noWrap align=middle width="48%" bgColor=#e7e7e7>價格</TD>
	   </TR>
	<?php
	$i=0;
	$j=1;
	while ($Result    = $DB->fetch_array($Query)) {
	?>
     <TR valign="top" bgColor=#f7f7f7>
 	   <TD align="left" valign="middle" class=unnamed1><INPUT id='cb<?php echo $i?>' type=checkbox value='<?php echo intval($Result['changeid'])?>' name='changeid[]'>&nbsp;<?php echo $j?><INPUT type=hidden value="<?php echo $Result['changeid']?>" name="Allid[]"><INPUT type=hidden value="<?php echo $Result['changegid']?>" name="changegid[]"></TD>
       <TD align="center" valign="middle" class=unnamed1>&nbsp;<?php echo $Result['bn']?></TD>
	   <TD valign="middle" class=unnamed1>&nbsp;<?php echo $Result['goodsname']?></TD>
	   <TD valign="middle" class=unnamed1>&nbsp;<INPUT id='price<?php echo $i?>'  type=text value='<?php echo $Result['price']?>' name=price<?php echo $Result['changeid']?>></TD>
	   </TR>
	<?php
	$j++;
	$i++;
	}
	?>
  </TBODY>
</TABLE>
</FORM>
 <!--   end    -->					   </TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD>&nbsp;            </TD></TR></TBODY></TABLE>
