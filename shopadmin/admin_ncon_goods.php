<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$id         = $FUNCTIONS->Value_Manage($_GET['id'],$_POST['id'],'back','');
$Goodsname   = str_replace(" ","+",trim($FUNCTIONS->Value_Manage($_GET['Goodsname'],$_POST['Goodsname'],'back','')));

$Sql         = "select gl.* ,g.goodsname,g.bn,g.price as gprice from `{$INFO[DBPrefix]}news_link` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.gid=g.gid) where gl.nid=".intval($id)." order by gl.idate desc ";
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);

//删除资料!
if ($_POST['act']=="Del"){
	$GoodLinkIdArray = $_POST['good_link_id'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}news_link` where link_id=".intval($GoodLinkIdArray[$i]));
		}
	}
	$FUNCTIONS->sorry_back("admin_ncon_goods.php?id=$id&Goodsname=".trim($Goodsname)."","");
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META http-equiv=ever content=no-cache>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2600.0" name=GENERATOR><title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[AboutProduct];//相关商品?>  </title>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" onLoad="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
<SCRIPT language=javascript>

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Num)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){  //您是否确认删除选定的记录
			document.adminForm.action = "";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}

function toSave(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Num)?>','<?php echo $Basic_Command['No_Select']?>');
	  if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Save_Select'];?>')){ //是否保存
			document.adminForm.action = "admin_linkgoods.php";
			document.adminForm.act.value="Save";
			document.adminForm.submit();
		}
	}
}
</SCRIPT>

<script language="javascript">
function cha(gid,Goodsname)
{
	w = 700;
	h = 680;
	resize = 'yes';
	//l = Math.ceil( (window.screen.width  - w) / 2 );
	//t = Math.ceil( (window.screen.height - h) / 5 * 2 );

	//var option = "scrollbars=1,location=0,menubar=0,status=0,toolbar=0,resizable=" + resize	+ ",height=" + h + ",width=" + w + ",left=" + l + ",top=" + t;
	var option = "scrollbars=0,location=0,menubar=0,status=0,toolbar=0,resizable=" + resize	+ ",height=" + h + ",width=" + w ;
	window.open('admin_ncon_goods_list.php?gid=' + gid + '&Goodsname=' + Goodsname,0,option);

}

</SCRIPT>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[AboutProduct];//相关商品?>  </SPAN></TD>
              </TR></TBODY></TABLE></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_ncon.php?Action=Modi&news_id=<?php echo $id?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"   border=0>&nbsp;<?php echo $Basic_Command['Return'];//返回?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE><TBODY>
                              <TR>
                                <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toSave();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                                </TR>
                              </TBODY>
                              </TABLE><!--BUTTON_END-->
                            </TD>
                          </TR>
                        </TBODY>
                      </TABLE>
                    </TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE><TBODY>
                              <TR>
                                <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"  border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD>
                                </TR>
                              </TBODY>
                              </TABLE>
                            <!--BUTTON_END-->
                            </TD>
                          </TR>
                        </TBODY>
                      </TABLE>
                    </TD>
                  </TR>
                </TBODY>
              </TABLE>
            </TD>
          </TR>
        </TBODY>
        </TABLE>
      

                      <TABLE class=allborder cellSpacing=0 cellPadding=0
                  width="100%" bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="12%">&nbsp;</TD>
                            <TD>&nbsp;</TD></TR>
                          <TR>
                            <TD align=right noWrap> 新聞：</TD>
                            
                            <TD nowrap><?php echo trim(($Goodsname));?> </TD>
                            </TR>
                          <TR>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD nowrap><TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=middle noWrap class="link_buttom" style="border:1px solid #cccccc;">
                                    <a href="javascript: cha('<?php echo $id?>','<? //$Goodsname?>');"><IMG  src="images/<?php echo $INFO[IS]?>/fb-relatedpro.gif" border="0" align="absmiddle" />
                                      &nbsp;<?php echo $Admin_Product[PleaseSelectAboutPrduct];//请选择相关商品?></a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE></TD>
                            </TR>
                          
                          <TR>
                            <TD noWrap align=right width="12%"> <?php echo $Admin_Product[AboutProductNum];//相關商品件數?>：</TD>
                            <TD><?php echo  $Num ?></TD>
                            </TR>
                          <TR align="center">
                            <TD colspan="2" valign="top" noWrap>&nbsp;					    </TD>
                            </TR>
                          <TR align="center">
                            <TD colspan="2" valign="top" noWrap>
                              <!--  start    -->
                              
                              <TABLE class=allborder cellSpacing=1 cellPadding=0  width="95%" bgColor=#666666 border=0>
                                
                                <FORM name=adminForm action="" method=post>
                                  <INPUT type=hidden name=act >
                                  <INPUT type=hidden value=0  name=boxchecked>
                                  <INPUT type=hidden  name='id' value="<?php echo $id?>">
                                  <INPUT type=hidden  name='Goodsname' value="<?php echo $Goodsname?>">
                                  <TBODY>
                                    <TR bgColor=#e7e7e7 height=25>
                                      <TD noWrap align=left width="10%"    height=17 bgColor=#e7e7e7><INPUT onClick="checkAll('<?php echo intval($Num)?>');" type=checkbox value=checkbox   name=toggle>
                                        &nbsp;<?php echo $Basic_Command['SNo_say'];//序号?></TD>
                                      <TD noWrap align=center width="12%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[Bn];//貨號?></TD>
                                      <TD noWrap align=middle width="36%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[ProductName];//商品名称?></TD>
                                      <TD noWrap align=center  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[ProductPrice];//商品价格?></TD>
                                      </TR>
                                    <?php
	$i=0;
	$j=1;
	while ($Result    = $DB->fetch_array($Query)) {
	?>
                                    <TR valign="top" bgColor=#f7f7f7>
                                      <TD align="left" valign="middle" class=unnamed1><INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo intval($Result['link_id'])?>' name='good_link_id[]'>&nbsp;<?php echo $j?><INPUT type=hidden value="<?php echo $Result['link_id']?>" name="Allid[]"><INPUT type=hidden value="<?php echo $Result['gid']?>" name="gid[]"></TD>
                                      <TD align="center" valign="middle" class=unnamed1>&nbsp;<?php echo $Result['bn']?></TD>
                                      <TD valign="middle" class=unnamed1>&nbsp;<?php echo $Result['goodsname']?></TD>
                                      <TD align="center" valign="middle" class=unnamed1>&nbsp;<?php echo $Result['gprice']?></TD>
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
</div>
 <div align="center">
     <?php include_once "botto.php";?>
 </div>
</BODY></HTML>
