<?php
include_once "Check_Admin.php";


	$Info_id = 4;
	$Action_value = "Update";
	$Query = $DB->query("select info_content from `{$INFO[DBPrefix]}admin_info` where info_id=".intval($Info_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Info_content     =  $Result['info_content'];

	}else{
		echo "<script language=javascript>window.history.back();</script>";
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Tools];//工具?>--&gt;Google Analytics</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){

    }
</SCRIPT>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="231" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;Google Analytics</SPAN></TD>
                  </TR>
                </TBODY>
              </TABLE></TD>
            <TD align=center width="50%">&nbsp;</TD>
            
            <TD align="right">
              <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                <TBODY>
                  <TR>
                    <TD align=middle width=79><!--BUTTON_BEGIN-->
                      <TABLE>
                        <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom">
                              <a href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
            <TD align="right" >&nbsp;</TD>	
          </TR>
        </TBODY>
  </TABLE><FORM name="form1" action='admin_otherinfo_save.php' method="post" >
                        <input type="hidden" name="Action" value="<?php echo $Action_value?>">
                        <input type="hidden" name="Info_id" value="<?php echo $Info_id?>">	
                        <TABLE class=allborder cellSpacing=0 cellPadding=2 
                  width="100%" align=center bgColor=#f7f7f7 border=0>
                          <TBODY>
                            <TR align="center">
                              <TD colspan="3" valign="top" noWrap>					   </TD>
                            </TR>
                            <TR>
                              <TD width="18%" align=right noWrap>&nbsp;</TD>
                              <TD colspan="2" align=right noWrap>&nbsp;</TD>
                            </TR>
                            <TR>
                              <TD align=right noWrap>_uacct= </TD>
                              <TD colspan="2" align=left noWrap>
                                <input name="uacct" type="text" id="uacct" size="20" maxlength="30"  onMouseOut="javascript:ChangeUacctInnerHtml(this.value);" value="<?php echo $Info_content;?>"> 
                                * 將申請好的ID填入即可 </TD>
                            </TR>
                            <TR>
                              <TD align=right noWrap>&nbsp;</TD>
                              <TD colspan="2" align=right noWrap>&nbsp;</TD>
                            </TR>
                            <TR style="display:none" id="CreateCodeSayTable">
                              <TD align=right noWrap>Google Analytics Code: </TD>
                              <TD colspan="2" align=right noWrap>&nbsp;</TD>
                            </TR>
                            <TR style="display:none" id="CreateCodeTable">
                              <TD align=right noWrap>&nbsp;</TD>
                              <TD colspan="2" align=left><textarea name="content" cols="80" rows="10" id="content"></textarea></TD>
                            </TR>
                            <TR>
                              <TD align=right noWrap>&nbsp;</TD>
                              <TD colspan="2" align=right noWrap>&nbsp;</TD>
                            </TR>
                            <TR>
                              <TD align=right noWrap>&nbsp;</TD>
                              <TD colspan="2" align=right noWrap>&nbsp;</TD>
                            </TR>
                            <TR>
                              <TD align=right noWrap>&nbsp;</TD>
                              <TD colspan="2" align=right noWrap>&nbsp;</TD>
                            </TR>
                            <TR>
                              <TD align=right noWrap>&nbsp;</TD>
                              <TD colspan="2" align=right noWrap>&nbsp;</TD>
                            </TR>
                            <TR>
                              <TD align=right noWrap>&nbsp;</TD>
                              <TD colspan="2" align=right noWrap>&nbsp;</TD>
                            </TR>
                          </TBODY></TABLE>
                        </FORM>

</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
<?php
/**
 *   引入AJAX
 */
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
echo $InitAjax;
?>
<script language="javascript">
/**
* ajax 帐户资料
*/
ChangeUacctInnerHtml("<?php echo $Info_content;?>");
function ChangeUacctInnerHtml(value){
	var txt;
	txt = "<"+"script type=\"text/javascript\">  var _gaq = _gaq || [];  _gaq.push(['_setAccount', '" + value + "']);  _gaq.push(['_trackPageview']);  (function() {    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);  })();</"+"script>";
	var show = document.getElementById("content");
 	var url = "./admin_Google_Analytics_save.php?action=Google_AnalyticsSave&uacct="+value;
	AjaxGetRequest(url)
	document.form1.content.value=txt;
}


function AjaxGetRequest(url){
 	if (typeof(url) == 'undefined'){
 		    return false;
 	}
 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
 	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
 	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
		  CreateCodeSayTable.style.display = "";
		  CreateCodeTable.style.display = "";
		}
	}	
     ajax.send(null);
 }		
	
</script>
