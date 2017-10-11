<LINK href="../css/theme.css" type=text/css rel=stylesheet><!--主選單樣式-->
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet><!--font icon-->
<LINK href="../css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet><!--font icon-->
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<LINK id=css href="../css/calendar.css" type='text/css' rel=stylesheet>
<link rel="stylesheet" type="text/css" href="../js/css/easyui.css">
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../Resources/redactor-js-master/lib/jquery-1.9.0.min.js"></script>

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
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js" language="javascript"></SCRIPT>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<script language="javascript" type="text/javascript" src="../js/show_dialog.js"></script>
<script type="text/javascript" src="../js/alter.js"></script>
<script language="javascript" src="../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../js/jquery/jquery.sticky.js"></script><!--頭部位置固定-->
<SCRIPT src="../js/calendar.js" language="javascript"></SCRIPT>
<script>
    $(window).load(function(){
      $("#header").sticky({ topSpacing: 0 });
    });
</script>
<SCRIPT language=JavaScript>
function select_color(type)
{
	var str;
	var arr_str=new Array();

	str=window.showModalDialog("color_select.html","","dialogWidth:28;dialogHeight:20");
	alert("選擇顏色");
	//if (!str) return false;
	if (!str) {
		str = window.returnValue;
	}
	arr_str=str.split("||");

	if (type==1){
	 document.form_goodsbase.salename_color.value=arr_str[0];
	 document.form_goodsbase.salename_color.value=arr_str[1];
	}

	return true;
}
</SCRIPT>
<style type="text/css">
body{
padding: 0;
margin:0px;
}
#header {
width:100%;
z-index:1000;
}
#fullBg{
width:100%;
height:100%;
background-color: Black;
display:none;
z-index:30;
position:fixed;
left:0px;
top:0px;
filter:Alpha(Opacity=30);
/* IE */
-moz-opacity:0.4;
/* Moz + FF */
opacity: 0.4;
}
#msg{
        position:fixed;
        overflow: auto;
        z-index:40;
        display:none;
        background-color:#FFFFFF;
        border:4px solid #00CCCC;
}
#msg #close{
height:30px;
text-align:right;
padding-top:8px;
padding-right:15px;
}
#msg #ctt{
text-align:center;
font-size:12px;
padding-bottom:15px;
}
#cPic{
cursor:pointer;
}
</style>
<div id="header">
  <?php  include $Js_Top ;  ?>
<div style="width:100%; height:9px; background-image: url(./images/<?php echo $INFO[IS]?>/menubo.png);background-repeat: repeat-x;">
</div>
</div>
