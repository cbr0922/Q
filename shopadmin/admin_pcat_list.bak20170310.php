<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/StaticHtml_Pack.php";

$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` order by top_id asc");
$Num  = $DB->num_rows($Query);
if ($Num<=0){
	$FUNCTIONS->sorry_back('admin_pcat.php','');
}else{
	/*
	$node_cache = " \$node_cache = array(";
	$i=0;
	while ($Result = $DB->fetch_array($Query)){
	$node_cache .= "array('id'  =>{$Result[bid]},'name'=>{$Result[catname]},'parentId'=>{$Result[top_id]},'iffb'=>{$Result[catiffb]},)";
	$i++;
	}
	$node_cache .= ");";
	}

	echo $node_cache;
	*/
}
$DB->free_result($Query);

/**
 *   引入AJAX
 */
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Class_List];//商品类别列表?></TITLE>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
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
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif" 
  width=778></TD></TR></TBODY></TABLE>
<SCRIPT language=javascript>
function toCreate(){
		if (confirm('<?php echo $Admin_Product[CreateFClassList]?>')){  //生成前台類別列表
			document.adminForm.action = "admin_create_productclassshow.php?Action=Index";
			document.adminForm.submit();
		}
}

function toCreateback(){
		if (confirm('<?php echo $Admin_Product[CreateBClassList]?>')){ //生成後台類別列表
			document.adminForm.action = "admin_create_productclassshow.php";
			document.adminForm.submit();
		}
}
function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Num?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_pcat_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}

	function checkform(){			
		
		save();
		//form1.action="admin_otherinfo_act.php";
		form1.submit();
	}

</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name="adminForm" action='admin_pcat.php' method="post" id="adminForm">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="80%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Product_Class_List];//商品类别列表?></SPAN></TD>
                    </TR>
                  </TBODY>
                </TABLE></TD>
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
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_pcat.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[CreateProductClass];//新增?></a></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_pcat_excel.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;匯出Excel</a></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toCreateback();"><IMG src="images/<?php echo $INFO[IS]?>/fb-edit.gif" border=0>&nbsp;<?php echo $Admin_Product[CreateBClassList];//生成后台类别表?></a></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  
                  
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toCreate();"><IMG src="images/<?php echo $INFO[IS]?>/fb-edit.gif" border=0>&nbsp;<?php echo $Admin_Product[CreateFClassList];//生成前台类别表?></a></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  
                  <TD align=middle><TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                      <TR>
                        <TD align=middle width=79><!--BUTTON_BEGIN-->
                          <TABLE>
                            <TBODY>
                              <TR>
                                <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG   src="images/<?php echo $INFO[IS]?>/fb-delete.gif"  border=0>&nbsp;
                                  <?php echo $Basic_Command['Del'];//删除?></a></TD>
                                </TR>
                              </TBODY>
                            </TABLE>
                          <!--BUTTON_END--></TD>
                        </TR>
                      </TBODY>
                    </TABLE></TD>
                  </TR></TBODY></TABLE>
              </TD>
          </TR></TBODY></TABLE>
          <table width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder"><tr><td>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <INPUT type=hidden name=act>
                        <INPUT  type=hidden value=0 name=boxchecked> 
                        <TR align=middle>
                          <TD class=p9black align=middle width=31  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                            <INPUT onclick=checkAll('<?php echo $Num?>'); type=checkbox value=checkbox   name=toggle> </TD>
                          <TD width="77" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black" align="left">分類ID</TD>
                          <TD width="311" height=26 background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black" align="left">
                            <?php echo $JsMenu[Product_Class_List];//商品類別列表?></TD>
                          <TD width="434" background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black" align="left"><?php echo $Basic_Command['Modi'];//修改?></TD>
                          <TD width="206" height=26 background="images/<?php echo $INFO[IS]?>/bartop.gif" class="p9black" align="center">語言版本</TD>
                          </TR>
                        
                        
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

					?><TBODY>
                          <TR class=row0>
                            <TD align=middle width=31 height=20>
                              <INPUT id='cb<?php echo $i?>' onClick="isChecked(this);" type="checkbox" value="<?php echo $val['id']?>" name="bid[]"> </TD>
                            <TD width="77" align="left" valign="middle" noWrap><?php echo $val['id']?></TD>
                            <TD width="311" height=26 align="left" valign="middle" noWrap>
                              <A href="admin_goods_list.php?bid=<?php echo $val['id']?>"><?php echo $item.$last.$val['name']?></A>				  </TD>
                            <TD width="434" height=26 align="left" valign="middle" noWrap>
                              <a href="admin_pcat.php?Action=Modi&bid=<?php echo $val['id']?>"><img src="images/fb-edit22.gif" border="0" /></A></TD>
                            <TD width="206" align="center" valign="middle" noWrap><?php echo $val['language']?></TD>
                            </TR></TBODY>
                        <?php
					$item = '';
					$i++;
					}
					?>					
                        <TR>
                          </TR>
                        </TABLE>
                      
                      
                      
                      <br />
                      <br />
                      
                      <table width="100%" border="0" cellpadding="3" cellspacing="3" class="input02"  style="display:none" id="CreateHtmlTable">
                        <tr>
                          <td width="20%" align="center" valign="top" >Making.....</td>
                          <td height="25" valign="top" ><div id="show_Content" height="30" class="p9orange">&nbsp;</div>
                            <div id="showTextarea"><textarea name="show_Content_textarea" cols="120" rows="10" id="show_Content_textarea"></textarea></div></td>
                          </tr>
                        </table>
</td>
                          </tr>
                        </table>
	</FORM></div>
<?php include_once "botto.php";?>
</BODY></HTML>
	
<?php echo $InitAjax;?>
 <script language="javascript">

 function AjaxGetRequest(url,show){
 	if (typeof(url) == 'undefined'){
 		    return false;
 	}

 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
 	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
 	ajax.onreadystatechange = function() {
 		//alert ('d');
 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		    show.innerHTML = "<?php echo $StaticHtml_Pack[PleaseWait] ;?>&nbsp;&nbsp;<img src='images/gif018.gif' />";
 		    showTextarea.innerHTML = "<div id='show_Content_textarea'></div>";
 		    if (ajax.readyState == 4 && ajax.status == 200) {
 		    	  //alert (ajax.responseText);        		      
 		    	  //show.innerHTML = ajax.responseText;
 		    	  //document.show_Content_textarea.value=ajax.responseText;
 		    	  //alert(Math.abs(ajax.responseText));
 		    	  if (Math.abs(ajax.responseText)==0){
 		    	  	show.innerHTML = '<?php echo $Basic_Command[NullDate];?>';
 		    	  }else{
 		    	  showTextarea.innerHTML = "<textarea name='show_Content_textarea' cols='120' rows='10' id='show_Content_textarea'></textarea>";
 		    	  document.getElementById("show_Content_textarea").value = ajax.responseText;
 		    	  show.innerHTML = '<?php echo $StaticHtml_Pack[HaveCreatedHtml]?>';
 		    	  }
 		    	        	}
 		    	        	        		      
 		    }

 		    ajax.send(null);
 		    }
</script>

