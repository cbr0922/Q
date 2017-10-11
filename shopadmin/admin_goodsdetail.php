<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
include_once Resources."/ckeditor/ckeditor.php";

$goods_id = intval($_GET['goods_id']);
if ($_GET['detail_id']!="" && $_GET['Action']=='Modi'){
	$detail_id = intval($_GET['detail_id']);
	$Action_value = "Update";
	$Action_say  = "修改商品詳細資料"; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}goods_detail` where detail_id=".intval($detail_id)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$detail_id =  $Result['detail_id'];
		$goods_id = $gid  =  $Result['gid'];
		$detail_name  =  $Result['detail_name'];
		$detail_bn  =  $Result['detail_bn'];
		$detail_price  =  $Result['detail_price'];
		$detail_pricedes  =  $Result['detail_pricedes'];
		$detail_des  =  $Result['detail_des'];
		$detail_pic  =  $Result['detail_pic'];
		$storage  =  $Result['storage'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增商品詳細資料" ; //插入
}

$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($goods_id)." limit 0,1");
$Num_goods   = $DB->num_rows($Query_goods);
if ($Num_goods>0){
	$Result_goods= $DB->fetch_array($Query_goods);
	$Goodsname  =  $Result_goods['goodsname'];
}

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<head>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say;?>--&gt;<?php echo $Goodsname;?></title>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" 
background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
  <TABLE class=9pv height=15 cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD width="2%" bgColor=#e7e7e7 height=15>&nbsp;</TD>
    <TD class=p9black vAlign=bottom width="98%" bgColor=#e7e7e7 height=15>
	<?php  include_once "Order_state.php";?></TD></TR>
	</TBODY>
	</TABLE>
    <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
    <TBODY>
     <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD></TR>
	</TBODY>
	</TABLE>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>
	function checkform(){
		//form1.action="admin_pcat_act.php?action=add";
		form1.submit();
	}

</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <FORM name=form1 action='admin_goodsdetail_save.php' method=post encType=multipart/form-data>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="detail_id" value="<?php echo $detail_id?>">
  <input type="hidden" name="goods_id" value="<?php echo $goods_id?>">
  <input type="hidden" name="old_pic" value="<?php echo $detail_pic?>">
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif"  width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319>&nbsp;</TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say;?>--&gt;<?php echo $Goodsname;?></SPAN></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom">
							<a href="admin_goodsdetail_list.php?goods_id=<?php echo $goods_id;?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
							
							</TD></TR></TBODY></TABLE>
							
						</TD></TR></TBODY></TABLE>
    </TD>
        </TR>
			</TBODY>
		  </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD vAlign=top bgColor=#ffffff height=300>
                  <TABLE class=allborder cellSpacing=0 cellPadding=2   width="100%" align=center bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="18%">&nbsp;</TD>
                      <TD noWrap align=right>&nbsp;</TD></TR>
                    <TR>
                      <TD noWrap align=right width="18%">
                        <DIV align=right>商品名稱：</DIV></TD>
                      <TD noWrap align=right>
                        <DIV align=left><?php echo $FUNCTIONS->Input_Box('text','detail_name',$detail_name,"      maxLength=50 size=40 ")?></DIV>					  </TD>
					  </TR>
			
					 
                    <TR>
                      <TD noWrap align=right width="18%">型號 ： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','detail_bn',$detail_bn,"      maxLength=50 size=40 ")?></TD></TR>
                    <TR>
                      <TD noWrap align=right>原價 ： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','detail_price',$detail_price,"      maxLength=10 size=10 ")?></TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>網購價格： </TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','detail_pricedes',$detail_pricedes,"      maxLength=10 size=10 ")?></TD>
                    </TR>
                    <TR>
                      <TD align=right noWrap>庫存：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','storage',$storage,"      maxLength=10 size=10 ")?></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>商品說明： </TD>
                      <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('textarea','detail_des',$detail_des," cols=80 rows=6  ")?></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>商品圖片： </TD>
                      <TD noWrap align=left><INPUT  id="img"  type="file" size="40" name="img" ></TD>
                    </TR>
					<?php if (is_file("../".$INFO['good_pic_path']."/".$detail_pic)){?>
                    
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD>
					  <div id="Mid_img">
					  &nbsp;<img src="<?php echo "../".$INFO['good_pic_path']."/".$detail_pic?>">
					  <a href="admin_goodsdetail_save.php?Action=DelPic&goods_id=<?php echo $goods_id?>&detail_id=<?php echo $detail_id?>" onClick="return confirm('<?php echo $Admin_Product[Del_Pic]?>?')"><font color="#FF0000"><?php echo $Basic_Command['Del']?></font></a>					  </div>                      </TD>
                      </TR>
					<?php } ?>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
                      </FORM>
					  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
