<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";

$top_id = intval($_GET['top_id']);
if ($_GET['store_id']!="" && $_GET['Action']=='Modi'){
	$store_id = intval($_GET['store_id']);
	$Action_value = "Update";
	$Action_say  = "修改門市資料"; //修改商品類別
	 $Sql = "select * from `{$INFO[DBPrefix]}store` where store_id=".intval($store_id)." limit 0,1";
	$Query = $DB->query($Sql);
	 $Num   = $DB->num_rows($Query);

	if ($Num>0){
		
		$Result= $DB->fetch_array($Query);
		$store_name =  $Result['store_name'];
		$store_code  =  $Result['store_code'];
		$province  =  $Result['province'];
		$city  =  $Result['city'];
		$address  =  $Result['address'];
		$tel  =  $Result['tel'];
		$map  =  $Result['map'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增門市信息" ; //插入
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;門市管理</title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		//form1.action="admin_pcat_act.php?action=add";
		form1.submit();
	}
	
</SCRIPT>

<div id="contain_out">
  <FORM name=form1 action='admin_store_save.php' method=post enctype="multipart/form-data">
    <?php  include_once "Order_state.php";?>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="store_id" value="<?php echo $store_id?>">
  <input type="hidden" name="top_id" value="<?php echo $top_id?>">
  <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;門市管理</SPAN></TD>
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
                            <a href="admin_store_list.php?top_id=<?php echo $top_id;?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                      <TABLE class=allborder cellSpacing=0 cellPadding=2   width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              門市名稱：</TD>
                            <TD noWrap align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','store_name',$store_name,"      maxLength=50 size=40 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%">門市代號：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','store_code',$store_code,"      maxLength=10 size=10 ")?>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>門市電話：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','tel',$tel,"      maxLength=20 size=20 ")?></TD>
                            </TR>
                          <TR>
                            <TD align=right noWrap>門市地區：</TD>
                            <TD align=left noWrap>
                              <select id="province" name="province" onChange="changecity(this.value,'');">
                                <?php
                      $a_Sql      = "select * from `{$INFO[DBPrefix]}area` where top_id='1' ";
					  $a_Query    = $DB->query($a_Sql);
					  while ($a_Rs=$DB->fetch_array($a_Query)) {
					  ?>
                                <option value="<?php echo $a_Rs['areaname']?>" <?php if($province==$a_Rs['areaname'])echo "selected";?>><?php echo $a_Rs['areaname']?></option>
                                <?php
					  }
					  ?>
                                </select>
                              <span id="showcity"></span>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>門市地址：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('text','address',$address,"      maxLength=50 size=40 ")?></TD>
                            </TR>
                          <TR>
                            <TD align=right \>地圖：</TD>
                            <TD><INPUT  id="map"  type="file" size="40" name="map" ><INPUT type=hidden   name='old_map'  value="<?php echo $map?>"></TD>
                            </TR>
                          <?php if (is_file("../".$INFO['good_pic_path']."/".$map)){?>
                          <TR>
                            <TD align=right \>&nbsp;</TD>
                            <TD><img src="<?php echo "../".$INFO['good_pic_path']."/".$map?>"><a href="admin_store_save.php?id=<?php echo $store_id;?>&pic=<?php echo $map;?>&type=pic1&Action=delPic">刪除圖片</a></TD>
                            </TR>
                          <?php
					}
					?>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD>
                            </TR>
                          </TBODY>
                  </TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
           
<script language="javascript">
function changecity(province,city){
	$.ajax({
				url: "admin_store_ajax_city.php",
				data: "city=" + encodeURIComponent(city) + "&province=" + encodeURIComponent(province),
				type:'get',
				dataType:"html",
				success: function(msg){
					//alert(msg);
				    $('#showcity').html(msg);
				}
	});
}
changecity('<?php echo $province;?>','<?php echo $city;?>');
</script>
</BODY>
</HTML>
