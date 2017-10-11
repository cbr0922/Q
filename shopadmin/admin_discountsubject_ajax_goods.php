<?php
include_once "Check_Admin.php";
 $dsid = $_GET['dsid'];
 include RootDocumentShare."/cache/Productclass_show.php";
?>
<script>
var optionslink = {
		success:       function(msg){
						if (msg=="1"){
							showpage("admin_discountsubject_ajax_goods_xlist.php","dsid=<?php echo $dsid;?>" ,"xlist");
								//closeWin();
								//showtajaxfun('goodslink');
							}

					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
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
          <td><FORM name=dellinkform action="admin_discountsubject_ajax_goods_save.php" method=post id="dellinkform">
              <INPUT type=hidden name=actlink id=actlink value="Del">
              <INPUT type=hidden value=0  name=boxchecked>
              <INPUT type=hidden  name='dsid' value="<?php echo $dsid?>">
              <div id="xlist">&nbsp;</div>
            </FORM></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="show2">
        <tr>
          <td><FORM name=optForm method=get action="admin_discountsubject_ajax_goods_list.php" id="linkgoodsform">
              <input type="hidden" name="Action" value="Search">
              <INPUT type=hidden name='dsid' value="<?php echo $dsid?>" >              <INPUT type=hidden id='bid' name='bid' value="" >
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
                <TR>
                  <TD align=center colSpan=2 height=31><TABLE width="100%" border=0 align="left" cellPadding=0 cellSpacing=0 class=p12black>
                      <TBODY>
                        <TR>
                          <TD width="455"   height=31 align=left nowrap>
						  <?php echo  $Char_class->get_page_select("top_id",$_GET[top_id],"  class=\"trans-input\" ");?>
						  <?php echo $Admin_Product[PleaseInputPrductName];//請輸入商品名稱?>&nbsp;
                            <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"   name='skey'></TD>
                          <TD width="183"  height=31 align="left" vAlign=center class=p9black><a href="#"><img src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 id="linksearch"></a></TD>
                          <TD width="409" align="right" vAlign=center class=p9black><input type="button" name="detailsave" id="detailsave" value="保存" />
                            <input type="button" name="cPic" id="cPic" value="返回" onclick="closeWin();" /></TD>
                        </TR>
                      </TBODY>
                    </TABLE></TD>
                </TR>
              </TABLE>
            </FORM>
            <FORM name=optForm method=post action="admin_discountsubject_ajax_goods_excel.php" id="excelgoodsform" enctype="multipart/form-data">
              <INPUT type=hidden name='dsid' value="<?php echo $dsid?>" >
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
                <TR>
                  <TD align=center colSpan=2 height=31><TABLE width="100%" border=0 align="left" cellPadding=0 cellSpacing=0 class=p12black>
                      <TBODY>
                        <TR>
                          <TD width="287"   height=31 align=center nowrap><input type="file" name="cvsEXCEL" id="cvsEXCEL" /></TD>
                          <TD width="89"  height=31 align="center" vAlign=center class=p9black>&nbsp;&nbsp;
                          <input type="button" name="button2" id="btn_excel" value="匯入" /></TD>
                          <TD width="433" align="right" vAlign=center class=p9black>&nbsp;</TD>
                        </TR>
                      </TBODY>
                    </TABLE></TD>
                </TR>
              </TABLE>
            </FORM>
            </td>
        </tr>
        <tr>
          <td><FORM name=selectlinkgoodsform action="admin_discountsubject_ajax_goods_save.php" method=post id="selectlinkgoodsform">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><INPUT type=hidden name=act id="act" value="Save">
                    <INPUT type=hidden name='dsid' id='dsid' value="<?php echo $dsid?>" >
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
            $("#top_id").val($("#bid").val());
					},
		type:      'get',
		dataType:  'html',
		clearForm: true
	};
var options1 = {
		success:       function(msg){
						if (msg=="1"){
								showpage("admin_discountsubject_ajax_goods_xlist.php","dsid=<?php echo $dsid;?>","xlist");
								$('#show1').css('display','block');
								$('#show2').css('display','none');
							}

					},
		type:      'post',
		dataType:  'html',
		clearForm: true
	};
$("#top_id").change(function(){
  $("#bid").val($("#top_id").val());
});

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
showpage("admin_discountsubject_ajax_goods_list.php","dsid=<?php echo $dsid;?>","goodslinklist");showpage("admin_discountsubject_ajax_goods_xlist.php","dsid=<?php echo $dsid;?>","xlist");
$('#show1').css('display','none');
</script>
