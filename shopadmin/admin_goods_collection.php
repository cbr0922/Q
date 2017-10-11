<?php
include_once "Check_Admin.php";
//include_once Resources."/FCKeditor/fckeditor.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
if ($_GET['gc_id']!="" && $_GET['Action']=='Modi'){
	$gc_id = intval($_GET['gc_id']);
	$Action_value = "Update";
	$Action_say  = $Admin_Product[Product_Collection_Modi]; //修改
	$Query = $DB->query("select gc_name,gc_pic,gc_id,gc_string,gc_link,tag,ifshop  from `{$INFO[DBPrefix]}goodscollection` where gc_id=".intval($gc_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$gc_name    =  trim($Result['gc_name']);
		$gc_pic     =  trim($Result['gc_pic']);
		$gc_id      =  intval($Result['gc_id']);
		$gc_string  =  trim($Result['gc_string']);
		$gc_link      =  $Result['gc_link'];
		$tag      =  $Result['tag'];
		$ifshop      =  $Result['ifshop'];
		$Ifpub      =  $Result['ifpub'];

		$pos = strpos($gc_string,",");

    if ($pos === false ){
     $gc_strings = trim($gc_string);
	   $sqladd     = " where gid = '".$gc_strings."' ";
		 $gc_string_array = explode(",",$gc_string);
    }else{
	   $gc_string_array = explode(",",$gc_string);
	   $gc_string_array = array_unique($gc_string_array);
	   $gc_strings = implode(",",$gc_string_array);
	   $gc_string_array = explode(",",$gc_string);
	   $sqladd     = " where ";

	     // if ( intval($v) > 0 ){
		 //    $sqladd  .= " gid ='".intval($v)."'   or";
		/// }

	  // $sqladd  = substr($sqladd,0,-3);

    }
    $GCString = "";
		$GCarray = array();
       //echo $Sql = " select gid,goodsname from `{$INFO[DBPrefix]}goods`  $sqladd ";
		$i = 0;
		foreach ( $gc_string_array as $k => $v ){
		 $QueryProductList = $DB->query(" select gid,goodsname from `{$INFO[DBPrefix]}goods`  where gid='" . $v . "' and checkstate=2");
		 $Nums   = $DB->num_rows($QueryProductList);
		 if ($Nums>0){
			 $Rss =  $DB->fetch_array($QueryProductList);
			 $GCString  .= $Rss[gid].",";
			 $GCarray[$i][gid] = $Rss[gid];
			 $GCarray[$i][goodsname] = $Rss[goodsname];
			 $i++;
		 }
		}
		$GCString  = substr($GCString,0,-1);

	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say  = $Admin_Product[Product_Collection_Add]; //添加
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Man] ;//品牌管理?>--&gt;<?php echo $Action_say?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		if (form1.gc_name.value == ""){
			alert('<?php echo $Admin_Product[PleaseInputProduct_Collection_Name];?>');  //請輸入聚合商品組名稱！
			form1.gc_name.focus();
			return;
		}
		if (form1.gc_string.value == ""){
			alert('<?php echo $Admin_Product[PleaseInputProduct_Collection_String];?>');  //請輸入商品組商品ID！
			form1.gc_string.focus();
			return;
		}

		form1.submit();
	}

</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_goods_collection_save.php' method='post' enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="gc_id" value="<?php echo $gc_id?>">
  <input type="hidden" name="old_pic" value="<?php echo $gc_pic?>">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Man] ;//品牌管理?>--&gt;<?php echo $Action_say?></SPAN>
                    </TD>
                  </TR>
                </TBODY>
              </TABLE>

            </TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
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
                                    <TD vAlign=bottom noWrap class="link_buttom">
                            <a href="admin_goods_collection_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->							</TD></TR></TBODY></TABLE>				</TD>
                  </TR>
                </TBODY>
              </TABLE></TD></TR>
      </TBODY>
        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>

                          <TR>
                            <TD noWrap align=right><?php echo $Admin_Product[Product_Collection_Name];//聚合商品組名稱：?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','gc_name',$gc_name,"      maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>類型：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','ifshop',$ifshop,$Add=array("商店街","商城"))?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>標籤名：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','tag',$tag,"      maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Product[Product_Collection_Pic];//聚合商品組圖片 ?>：</TD>
                            <TD width="38%" align=left noWrap><input name="ima" type="file" id='ima' >					  </TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" rowspan="2" align=left noWrap>

                              <?php echo $FUNCTIONS->ImgTypeReturn($INFO['logo_pic_path'],$gc_pic,'','');?><?php if ($gc_pic!="") { ?>&nbsp;&nbsp;<a href="admin_goods_collection_save.php?Action=DelPic&gc_id=<?php echo $gc_id?>" onClick="return confirm('<?php echo $Admin_Product[Del_Pic]?>?')"><?php echo $Basic_Command['Del']?></a><?php } ?>					  </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>URL：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','gc_link',$gc_link,"      maxLength=255 size=70 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Admin_Product[Product_Collection_String] ; //組商品ID ?>：</TD>
                            <TD align=left noWrap>
                            <i class="icon-warning-sign" style="font-size:16px;color:#C00"></i> <?php echo $Admin_Product[Product_Collection_String_Intro] ;?><br />
                              <?php echo $FUNCTIONS->Input_Box('textarea','gc_string',$GCString,"      cols='60' rows='3' ")?>
                             </TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Admin_Product[Product_Collection_GoodsList]; //本組商品列表" ?>：</TD>
                            <TD colspan="2" align=left noWrap>

                              <table width="90%" border="0" cellpadding="0" cellspacing="0" class="listtable">
                                <tr>
                                  <td width="10%"><?php echo $Admin_Product[ProductID];?></td>
                                  <td width="90%"><?php echo $Admin_Product[ProductName];?></td>
                                  </tr>
                                <?php
					        if (is_array($GCarray) && count($GCarray)>0){
					        $i = 0;
					  		//while ($Rs = $DB->fetch_array($QueryProductList)){
							foreach ($GCarray as $k=>$v){
					   ?>

                                <tr>
                                  <td><?php echo $v[gid]?></td>
                                  <td><a href="admin_goods.php?Action=Modi&gid=<?php echo $v[gid]?>" target="_blank"><?php echo $v[goodsname]?></a></td>
                                  </tr>
                                <?php
						     $i++;
						    }
						}
						 ?>
                                </table>

                              </TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right></TD>
                            <TD align=left noWrap>
                              <?php if ($i>0) { ?>
                              <a href="admin_collection_xml.php?Action=XML&gc_id=<?php echo $gc_id;?>" target="_blank" class="input02"><?php echo $Admin_Product[ExportXML];?></a>
                              <?php } ?>
                              </TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          </TBODY></TABLE>
                  <label></label>
  </FORM>

</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
