<?php
include_once "Check_Admin.php";

//装载语言包
include "../language/".$INFO['IS']."/GoodsScript.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";


$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` order by top_id asc");
$Num  = $DB->num_rows($Query);
if ($Num<=0){
	$FUNCTIONS->sorry_back('admin_pcat.php','');
}
$DB->free_result($Query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Script];//商品调用?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
	function checkform(){
		var product_type = document.getElementById("product_type").value;
		var num = document.getElementById("goods_num").value;
		var show = document.getElementById("show_img");
		if(show.checked){
			var show_value = 1;
		}
		else{
			var show_value = 0;
		}
		var arrange_type = document.getElementById("arrange").value;
		var is_show = document.getElementById("is_show");
		if(is_show.checked){
			var is_show_value = 1;
		}
		else{
			var is_show_value = 0;
		}
		var show_num = document.getElementById("show_num").value;
		var condition = "";
		if(product_type == "new"){
			condition = "condition=new&num="+num;
		}
		if(product_type == "hot"){
			condition = "condition=hot&num="+num;
		}
		if(product_type == "type"){
			var goods_type = document.getElementById("goods_type").value;
			condition = "condition=type&bid="+goods_type+"&num="+num;
		}
		if(product_type == "full"){
			condition = "condition=full&num="+num;
		}
		if(arrange_type == "h"){
			var arrange = "h";
		}
		if(arrange_type == "v"){
			var arrange = "v";
		}
		if(num ==""|| num<=0){
			alert("<?php echo $GoodScript[PleaseInputNum];//请输入显示的记录数?>");
			document.getElementById("goods_num").focus();
			//return false;
		}else {
		var js = '\<script src\=\"<?php echo $INFO['site_url']?>/goods_create_script.php?'+condition+'&show='+show_value+'&arrange='+arrange+'&is_show='+is_show_value+'&show_num='+show_num+'\"\>\<\/script\>';
		document.getElementById("create_js").value=js;
		}
		
	}
	function selectType(value){
		if(value == "type"){
			document.getElementById("type").style.display="";
		}
		else{
			document.getElementById("type").style.display="none";
		}
	}
	function changeArrange(value){
		if(value == "h"){
			document.getElementById("showh").style.display="";
			document.getElementById("showNum").style.display="";
		}
		if(value == "v"){
			document.getElementById("showh").style.display="none";
			document.getElementById("showNum").style.display="none";
		}
	}
	function isShow(){
		var is_show = document.getElementById("is_show");
		if(is_show.checked){
			document.getElementById("show_num").disabled=false;
		}
		else{
			document.getElementById("show_num").disabled=true;
			document.getElementById("show_num").value=0;
		}
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
                    <TD class=p12black><SPAN class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Script];//商品调用?></SPAN></TD>
              </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <!--TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<a href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END</TD></TR></TBODY></TABLE></TD-->
                  
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE >
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom">
                                    <a href="#" onClick="javascript:return checkform();">
                                      <IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Admin_Product['Create_Js_Code'];//生成?></a></TD>
                                  </TR>
                                </TBODY>
                              </TABLE><!--BUTTON_END-->
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

              <FORM name="form1" action='admin_goods_script.php' method="post" ><table class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center border=0 bgColor=#f7f7f7>
                          <tbody>
                            <tr>
                              <td align="right" width="30%">&nbsp;</td>
                              <td>&nbsp;</td>
                              </tr>
                            <tr>
                              <td align="right"><?php echo $GoodScript[SelectType]//请选择商品类型?>：</td>
                              <td><select name="product_type" id="product_type" onchange="selectType(this.value)">
                                <option value="new"><?php echo $GoodScript[NewGoods]//最新商品?></option>
                                <option value="hot"><?php echo $GoodScript[HotGoods]//热卖商品?></option>
                                <option value="type"><?php echo $GoodScript[GoodsType]//商品分类?></option>
                                <option value="full"><?php echo $GoodScript[FullGoods]//全部商品?></option>
                              </select></td>
                            </tr>
                            <tr id="type" style="display:none">
                              <td align="right"><?php echo $GoodScript[SelectGoodsType]//请选择商品分类 ?></td>
                              <td>
                                <select name="goods_type">
                                  <?php
									if (is_file(RootDocumentShare."/cache/Productclass_show.php")  && strlen(trim(file_get_contents(RootDocumentShare."/cache/Productclass_show.php")))>25 ){
										include RootDocumentShare."/cache/Productclass_show.php";
									}else{
									    $BackUrl = "admin_pcat_list.php";
										include "admin_create_productclassshow.php";
										exit;
									}										
									$i=0;
									$last = "├─";
									$Productclassshow =  $Char_class->get_page_children($id,$node,$depth=0);
									foreach($Productclassshow as $key=>$val) {
										$item = str_repeat(" │ ",$val['depth']);

								?>
                                  <option value="<?php echo $val['id']?>"><?php echo $item.$last.$val['name']?></option>
                                  <?php
								$item = '';
								$i++;
								}
								?>		
                                  </select>
                                </td>
                              </tr>
                            <tr>
                              <td align="right" width="30%"><?php echo $GoodScript[GoodsNum]//显示商品的数量?>：</td>
                              <td><input type="text" name="goods_num" id="goods_num"/><input type="checkbox" name="show_img" id="show_img" value="1"/><?php echo $GoodScript[IsShowImg]//是否显示图片?></td>
                              </tr>
                            <tr>
                              <td align="right" width="30%"><?php echo $GoodScript[Arrange] //选择商品排列方式?>：</td>
                              <td>
                                <select name="arrange" onChange="changeArrange(this.value)">
                                  <option value="v"><?php echo $GoodScript[V]//竖排 ?></option>
                                  <option value="h"><?php echo $GoodScript[H]//横排 ?></option>
                                  </select>
                                <span id="showh" style="display:none">
                                  <input type="checkbox" name="is_show" onClick="isShow()"><?php echo $GoodScript[IsMoreH]//是否多行显示?>
                                  </span>
                                </td>
                              </tr>
                            <tr id="showNum" style="display:none">
                              <td align="right" width="30%"><?php echo $GoodScript[ShowNum]//每行显示的商品数量?>：</td>
                              <td><input type="text" name="show_num" id="show_num" value="0" disabled/></td>
                              </tr>
                            <tr>
                              <td align="right" width="30%"><?php echo $GoodScript[JsCode]//生成的js代码?>：</td>
                              <td><p>
                                <textarea name="create_js" id="create_js" rows="10" cols="90"></textarea>
                              </p>
                                <p>&nbsp; </p></td>
                              </tr>
                            </tbody>
                          </table>	
                </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>

