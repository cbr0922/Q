<?php
include_once "Check_Admin.php";
 $rgid = $_GET['rgid'];
 include RootDocumentShare."/cache/Productclass_show.php";
?>
<script>
var optionslink = {
		success:       function(msg){
						if (msg=="1"){
							showpage("admin_redgreensubject_ajax_green_xlist.php","rgid=<?php echo $rgid;?>" ,"xlist");
								//closeWin();
								//showtajaxfun('goodslink');
							}
						
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
</script>
<script language="javascript">
$(function() {
           $("#checkAll").click(function() {
										 alert("aaa");
                $('input[name="cid[]"]').attr("checked",this.checked); 
            });
            var $subBox = $("input[name='cid[]']");
            $subBox.click(function(){
                $("#checkAll").attr("checked",$subBox.length == $("input[name='cid[]']:checked").length ? true : false);
            });
        });
</script>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left"><a href="javascript:void(0);" onClick="$('#show1').css('display','none');$('#show2').css('display','block');">新增商品</a> | <a href="javascript:void(0);" onClick="$('#show2').css('display','none');$('#show1').css('display','block');">主題商品列表</a></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="show1">
        <tr>
          <td width="700px" align="left"><input type="button" name="button" id="oklink" value="確認" onclick=' $("#actlink").attr("value","Check");$("#dellinkform").ajaxSubmit(optionslink);' />
            <input type="button" name="button" id="dellink" value="刪除" onclick=' $("#actlink").attr("value","Del");$("#dellinkform").ajaxSubmit(optionslink);' />
            <input type="button" name="savebut" id="savebut" value="保存" onclick=' $("#actlink").attr("value","Save");$("#dellinkform").ajaxSubmit(optionslink);' />
            <input type="button" name="cPic2" id="cPic2" value="返回" onclick="closeWin();" /></td>
        </tr>
        <tr>
          <td><FORM name=adminForm action="admin_redgreensubject_ajax_green_save.php" method=post id="dellinkform">
              <INPUT type=hidden name=actlink id=actlink value="Del">
              <INPUT type=hidden value=0  name=boxchecked>
              <INPUT type=hidden  name='rgid' value="<?php echo $rgid?>">
              <div id="xlist">&nbsp;</div>
            </FORM></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="show2">
        <tr>
          <td><FORM name=optForm method=get action="admin_redgreensubject_ajax_goods_list.php" id="linkgoodsform">
              <input type="hidden" name="Action" value="Search">
              <INPUT type=hidden name='rgid' value="<?php echo $rgid?>" >
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
                <TR>
                  <TD align=center colSpan=2 height=31><TABLE width="100%" border=0 align="left" cellPadding=0 cellSpacing=0 class=p12black>
                      <TBODY>
                        <TR>
                          <TD width="462"   height=31 align=left nowrap><?php echo $Admin_Product[PleaseInputPrductName];//請輸入商品名稱?>&nbsp;
                          <?php echo  $Char_class->get_page_select("top_id",$_GET[top_id],"  class=\"trans-input\" ");?>
                            <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"   name='skey'></TD>
                          <TD width="166"  height=31 align="left" vAlign=center class=p9black><a href="#"><img src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 id="linksearch"></a></TD>
                          <TD width="435" align="right" vAlign=center class=p9black><input type="button" name="detailsave" id="detailsave" value="保存" />
                            <input type="button" name="cPic" id="cPic" value="返回" onclick="closeWin();" /></TD>
                        </TR>
                      </TBODY>
                    </TABLE></TD>
                </TR>
              </TABLE>
            </FORM>
            
            </td>
        </tr>
        <tr>
          <td><FORM name=adminForm action="admin_redgreensubject_ajax_green_save.php" method=post id="selectlinkgoodsform">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><INPUT type=hidden name=act id="act" value="Save">
                    <INPUT type=hidden name='rgid' id='rgid' value="<?php echo $rgid?>" >
                    <INPUT type=hidden value=0  name=boxchecked></td>
                </tr>
                <tr>
                  <td id="goodslinklist"></td>
                </tr>
              </table>
            </form></td>
        </tr>
      </table></td>
  </tr>
</table>
<script>
$(document).ready(function() {
var options = {
		success:       function(msg){
						$("#goodslinklist").html(msg);
					},
		type:      'get',
		dataType:  'html',
		clearForm: true
	};
var options1 = {
		success:       function(msg){
			//alert(msg);
						if (msg=="1"){
								showpage("admin_redgreensubject_ajax_green_xlist.php","rgid=<?php echo $rgid;?>","xlist");
								$('#show1').css('display','block');
								$('#show2').css('display','none');
							}
						
					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
	

$("#linksearch").click(function(){
					   //$("#linkgoodsform").attr("action","admin_goods_ajax_linkgoodslist.php");
					  // $("#act").attr("value","Search");
					  // alert($("#linkgoodsform").attr("action"));
					   $("#linkgoodsform").ajaxSubmit(options);
								});
$("#detailsave").click(function(){$("#selectlinkgoodsform").ajaxSubmit(options1);});
$("#btn_excel").click(function(){$("#excelgoodsform").ajaxSubmit(options1);});

});
function showpage(url,data,div){
$.ajax({
				url: url,
				data: data,
				type:'get',
				dataType:"html",
				success: function(msg){
				    $('#' + div).html(msg);
				}
	});
}
showpage("admin_redgreensubject_ajax_goods_list.php","rgid=<?php echo $rgid;?>","goodslinklist");
showpage("admin_redgreensubject_ajax_green_xlist.php","rgid=<?php echo $rgid;?>","xlist");
$('#show1').css('display','none');
</script>
