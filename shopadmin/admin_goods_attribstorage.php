<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
$gid        = trim($FUNCTIONS->Value_Manage($_GET['gid'],'','back',''));
/**
	 * 这里是当供应商进入的时候。只能修改自己的产品资料。
	 */
if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$Provider_string = " and g.provider_id=".intval($_SESSION['sa_id'])." ";
}else{
	$Provider_string = "";
}
$G_Sql      = "select g.goodsname,g.storage,good_color,good_size,g.bn from `{$INFO[DBPrefix]}goods` g where g.gid='".intval($gid)."' ".$Provider_strings." limit 0,1";
$G_Query    = $DB->query($G_Sql);
$G_Num      = $DB->num_rows($G_Query);
if ($G_Num>0){
	$G_result    =  $DB->fetch_array($G_Query);
	$G_goodsname = $G_result['goodsname'];
	$G_storage     = $G_result['storage'];
	$good_color = $G_result['good_color'];
	$good_size = $G_result['good_size'];
	$G_bn        = $G_result['bn'];
}else{
	$FUNCTIONS->sorry_back('back','');
}

$DB->free_result($G_Query);

$Sql      = "select *  from `{$INFO[DBPrefix]}storage` where goods_id=" . intval($gid);
$Query    = $DB->query($Sql);
$Nums      = $DB->num_rows($Query);
if ($Nums>0){
	$Nav->total_result=$Nums;
	$Nav->execute($Sql,$Nums);
}

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<META http-equiv=ever content=no-cache>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2600.0" name=GENERATOR>
<title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[SetMemberPrice];//会员价格设定?></title>
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
 <TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center   border=0>
 <TBODY>
  <TR>
    <TD align="right">
	  <?php  include_once "desktop_title.php";?></TD>
    </TR>
  </TBODY>
 </TABLE>
<SCRIPT language=javascript>


function checkform(){
 	form1.action="admin_goods_attribstorage_save.php";
	form1.submit();
}

/*
function toDel(){
	var checkvalue;
	checkvalue = isSelected(<?php echo $Nums?>,'<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_memberprice_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}
*/

</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1
      src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif"
  width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=302></TD>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black noWrap><SPAN
                  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;商品詳細庫存</SPAN></TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
					 <TD align=middle width=79>  <!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom">
							<a href="admin_goods.php?Action=Modi&gid=<?php echo $gid?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;回商品管理</a>&nbsp; </TD>
							</TR>
						  </TBODY>
						 </TABLE>
					 <!--BUTTON_END-->
					 </td>
                      <TD align=middle width=79>
					  <!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                </TR>
				 </TBODY>
				</TABLE>
        </TD>
		  </TR>
		  </TBODY>
		</TABLE>

      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>

        <TR>
          <TD align=right colSpan=2 height=31>&nbsp;            </TD>
           <TD class=p9black align=right width=400 height=31>

		  </TD>
		 </TR>

	</TABLE>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
        <TR>
          <TD vAlign=top height=210>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD bgColor=#ffffff>
                  <table width="100%"  border="0" cellspacing="2" bgcolor="#EBEBEB" class="p9black">
                    <tr>
                      <td colspan="6">&nbsp;</td>
                      </tr>
                    <tr>
                      <td width="20%" align="right">&nbsp;</td>
                      <td  align="left" nowrap><?php echo $Admin_Product[Bn];//貨號?>：<?php echo $G_bn?></td>
                      <td  align="left" nowrap><?php echo $Admin_Product[ProductName];//商品名称?>：<?php echo $G_goodsname?></td>
                      <td  align="left" nowrap>庫存量：<?php echo $G_storage?></td>
                      <td width="20%" align="right">&nbsp;</td>
                      </tr>
                    <tr height="30">
                      <td colspan="5" align="right" bgcolor="#EBEBEB"></td>
                    </tr>
                  </table>
<form action="admin_goods_attribstorage_save.php" method=post>
<input type="hidden" name="action" value="add">
<INPUT type=hidden name=gid value="<?php echo $gid?>">
                  <table width="100%" border="0">
                    <tr>
                      <td>商品顏色<select name="color"><?php if (trim($good_color)!=""){
		$Good_color_array    =  explode(',',trim($good_color));

		if (is_array($Good_color_array)){
			foreach($Good_color_array as $k=>$v )
			{
				$Good_Color_Option .= "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Color_Option = "<option value='".$v."'>".$v."</option>\n";
			$Good_color_array = array();
		}
	}else{
		$Good_Color_Option = "";
		$Good_color_array = array("");
	}
	echo $Good_Color_Option;
	?></select>商品尺寸<select name="size"><?php if (trim($good_size)!=""){
		$Good_size_array    =  explode(',',trim($good_size));

		if (is_array($Good_size_array)){
			foreach($Good_size_array as $k=>$v )
			{
				$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";
			}
		}else{
			$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";
			$Good_size_array = array("");
		}
	}else{
		$Good_Size_Option = "";
		$Good_size_array = array("");
	}
	echo $Good_Size_Option;
	?></select>庫存數量
                        <input name="storage" type="text" id="storage">
                        <input type="submit" name="Submit" value="新增"></td>
                    </tr>
                  </table>
				  </form>
                  <TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                    <FORM name=form1 action="admin_goods_attribstorage_save.php" method=post>
					<INPUT type=hidden name=act>
					<INPUT type=hidden name=gid value="<?php echo $gid?>">
					 <INPUT type=hidden value=0  name=boxchecked>
                    <TBODY>
                    <TR align=middle>
                      <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26><?php echo $Basic_Command['SNo_say'];//序号?></TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>商品顏色</TD>
                     
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>商品尺寸</TD>
                      <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>庫存數量</TD>
                      </TR>
					<?php
					$i=1;

					while ($Rs=$DB->fetch_array($Query)) {
					//print_r($Rs);
					if ((in_array($Rs['color'],$Good_color_array) || trim($Rs['color']) == "") && (in_array($Rs['size'],$Good_size_array) || trim($Rs['size'])=="")){

					?>
                    <TR class=row0>
                      <TD align=middle width=91 height=20>
                      <?php echo $i?>					  </TD>
                      <TD height=20 align="left" noWrap>
                        <?php echo $Rs['color']?>                      </TD>
                      <TD align=left nowrap><?php echo $Rs['size']?> </TD>
                      <TD height=20 align=center nowrap>
					 
					  <input type="hidden" name="storage_id[]" value="<?php echo $Rs['storage_id']?>">
					  <?php echo $FUNCTIONS->Input_Box('text','storage[]',intval($Rs['storage']),"      maxLength=10 size=10 ")?>
					  			  </TD>
                      </TR>
					<?php
					$i++;
					}
					}
					?>
                    <TR>
                      <TD align=middle width=91 height=14>&nbsp;</TD>
                      <TD width=245 height=14>&nbsp;</TD>
                      <TD width=202 align="left">&nbsp;</TD>
                      <TD width=202 height=14>&nbsp;</TD>
                      </TR>
					 </FORM>
					 </TABLE>
					 </TD>
				    </TR>
			    </TABLE>

            <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>

              <TBODY>
              <TR>
                <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
				<?php //echo $Nav->pagenav()?>
				</TD>
              </TR>


	    </TABLE></TD></TR></TABLE></TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=302>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></TBODY></TABLE>

</BODY></HTML>
