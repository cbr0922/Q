<?php
Class Ajax
{

	function InitAjax() {

		$Init  = "";
		$Init .= <<<EOD
<SCRIPT LANGUAGE=JAVASCRIPT>
function InitAjax()
{
  var ajax=false;
  try {
    ajax = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      ajax = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      ajax = false;
    }
  }
  if (!ajax && typeof XMLHttpRequest!='undefined') {
    ajax = new XMLHttpRequest();
  }
  return ajax;
}
</SCRIPT>
EOD;

		return $Init;
	}




	function GetLicense (){
		/**
    *  这里是获得服务器返回资料！
    */
		$Server  = $_SERVER[HTTP_HOST]=='127.0.0.1' ? "localhost" : $_SERVER[HTTP_HOST] ;
		$DateIs  = date("Y-m-d",time());
		$Script  = "";
		$Script .="
	<script language=\"JavaScript\">

	function GetResult(hostname,TheDate)
	{

      // if (hostname!='localhost' && hostname!='127.0.0.1'){

		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			http_request = new XMLHttpRequest();
		} else if (window.ActiveXObject) { // IE
			http_request = new ActiveXObject(\"Microsoft.XMLHTTP\");
		}
		var linkurl=\"http://yesddcs.com/s.php?serverhost=\"+hostname+\"&DateIs=\"+TheDate;

		http_request.open('GET',linkurl,false);
		http_request.send(null);

		//通过XMLHTTP返回数据,开始构建Div.
		//var returntxt=unescape(http_request.responseText)
		var returntxt=http_request.responseText;
		BuildArrib(returntxt);
		//}
	}

	function BuildArrib(txt)
	{
		ajax_license.style.display='';
		document.getElementById('ajax_license').innerHTML = txt;
	}
</script>
<div id='ajax_license' style='display:none'>&nbsp;111</div>

<script language=javascript>
GetResult('$Server','$DateIs');
</script>
";
		return $Script;
	}





	function PostLicense(){
		global $OtherPach;
		$date = date("Y-m-d",time());
		$serverhost = $_SERVER[HTTP_HOST].$OtherPach;
		$PostLicense = "
<table border=0>
<form name=\"license_form\" method=\"post\" action=\"\">
<input type=hidden name=\"DateIs\" value=\"$date\">
<input type=hidden name=\"serverhost\" value=\"$serverhost\">

<tr style=\"DISPLAY:none\">
          <td height=\"0\" valign=\"top\" width=\"0\">
		  <iframe name=\"send_act\" src=\"about:blank\" scrolling=\"no\" FrameBorder=0 width=100% height=100%></iframe>
          </td>

</form>
</table>
<script language=javascript>
function initSend()
	{
				document.license_form.action = \"http://yesddcs.com/s.php\";
				document.license_form.target = \"send_act\";
				document.license_form.submit();
	}

initSend();
</script>
";
		return $PostLicense;
	}


}
