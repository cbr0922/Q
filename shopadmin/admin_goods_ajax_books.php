<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$Gid         = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');

$Sql         = "select gl.* ,g.ntitle from `{$INFO[DBPrefix]}goods_books` gl  inner join `{$INFO[DBPrefix]}news`  g on (gl.nid=g.news_id) where gl.gid=".intval($Gid)." order by gl.ord desc ";
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);
?>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<script>
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
</script>
<script>
var optionsbooks = {
		success:       function(msg){
						if (msg=="1"){
								closeWin();
								showtajaxfun('books');
							}
					},
		type:      'post',
		dataType:  'json',
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

                      <TD width="84%" nowrap><TABLE border="0" id="selectlink">
                        <TBODY>
                          <TR>
                            <TD vAlign=middle noWrap class="link_buttom">
							  <a href="javascript:showWin('url','admin_goods_ajax_booksgoods.php?gid=<?=intval($Gid)?>','',750,450);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-relatedpro.gif" border="0" align="absmiddle" class="fbottonnew" />
	                          請選擇文章</a></TD>
                          </TR>
                        </TBODY>
                      </TABLE></TD>
                    </TR>
                   
                    

                    <TR>
                      <TD noWrap align=right width="12%"> 文章數量：</TD>
                      <TD><?php echo  $Num ?></TD>
                    </TR>
                      <TR align="center">
                      <TD valign="top" noWrap></TD>
                      <TD align="left" valign="top" noWrap><input type="button" name="button" id="delbooks" value="刪除" onclick=' $("#actbooks").attr("value","Del");$("#delbooksform").ajaxSubmit(optionsbooks);' />
                        </TD>
                      </TR>
                    <TR align="center">
                      <TD colspan="2" valign="top" noWrap>
<!--  start    -->
 <FORM name=adminForm action="admin_goods_ajax_booksgoodssave.php" method=post id="delbooksform">
   <INPUT type=hidden name=actbooks id=actbooks value="Del">
   <INPUT type=hidden value=0  name=boxchecked>
   <INPUT type=hidden  name='gid' value="<?php echo $Gid?>">
   <INPUT type=hidden  name='Goodsname' value="<?php echo $Goodsname?>">
<TABLE class=allborder cellSpacing=1 cellPadding=0  width="95%" bgColor=#666666 border=0>


    <TBODY>
     <TR bgColor=#e7e7e7 height=25>
       <TD noWrap align=left width="3%"    height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Basic_Command['SNo_say'];//序号?></TD>
       <TD noWrap align=middle width="97%"  height=17 bgColor=#e7e7e7>&nbsp;文章標題</TD>
       </TR>
	<?php
	$i=0;
	$j=1;
	while ($Result    = $DB->fetch_array($Query)) {
	?>
     <TR valign="top" bgColor=#f7f7f7>
 	   <TD align="left" valign="middle" class=unnamed1><INPUT id='cb<?php echo $i?>' type=checkbox value='<?php echo intval($Result['gbid'])?>' name='gbid[]'></TD>
       <TD valign="middle" class=unnamed1>&nbsp;<?php echo $Result['ntitle']?></TD>
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
