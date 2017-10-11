<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_GET['kid']!="" && $_GET['Action']=='Modi'){
	$Kid = intval($_GET['kid']);
	$Action_value = "Post";
	$Action_say  = $KeFu_Pack['Back_Nav_title_two'];//'客服-->回復'
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}kefu` where kid=".intval($Kid)." and provider_id='" . intval($_SESSION['sa_id']) . "' limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$serialnum            =  $Result['serialnum'];
		$type_chuli           =  $Result['type_chuli'];
		$type_chuli_name      =  $Result['type_chuli_name'];
		$username             =  $Result['username'];
		$realname             =  $Result['realname'];
		$email                =  $Result['email'];
		$postnum              =  $Result['postnum'];
		$title                =  $Result['title'];
		$lastdate             =  $Result['lastdate'];
		$status               =  $Result['status'];
		$k_kefu_con           =  $Result['k_kefu_con'];
		$order_serial           =  $Result['order_serial'];
		$marketno           =  $Result['marketno'];

	}else{
		echo "<script language=javascript>javascript:window.close();</script>";
		exit;
	}

}
function getdate_kefu($value)
{

	$return_date = getdate($value);
	$return_date['mon']=strlen($return_date['mon'])==1?'0'.$return_date['mon']:$return_date['mon'];
	$return_date['mday']=strlen($return_date['mday'])==1?'0'.$return_date['mday']:$return_date['mday'];
	$return_date = $return_date['year'].'/'.$return_date['mon'].'/'.$return_date['mday'].'  '.$return_date['hours'].':'.$return_date['minutes']; 	return $return_date;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK id=css href="css/calendar.css" type='text/css' rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Order_List];//定单管理?>
</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
 <TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center   border=0>
 <TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
	<? 

	include "desktop_title.php";

	?></TD>
  </TR>
  </TBODY>
 </TABLE>
<SCRIPT type="text/javascript">
function checkform(){

	if (chkblank(form1.type_chuli.value)){
		form1.type_chuli.focus();
		alert('<?php echo $KeFu_Pack['Back_Js_Alert_one']?>');  //请输入問題類別名称
		return;
	}

	form1.submit();
}
function checkform1(){

	if (chkblank(form1.provider_name.value)){
		form1.provider_name.focus();
		alert('<?php echo $KeFu_Pack['Back_Js_Alert_two']?>');  //请输入樣版使用名称
		return;
	}
	document.form1.ifgo_on.value=1;
	form1.submit();
}
function changecat(){
	form1.action="";
	//save();
	form1.submit();
}

var xmlHttp;

function createXMLHttpRequest() {

	if (window.ActiveXObject)
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");

	}
	else if ( window.XMLHttpRequest)
	{
		xmlHttp = new XMLHttpRequest();
	}

}

function gettem() {
	createXMLHttpRequest();
	var kefu_tem = document.getElementById("kefu_tem");
	var url = "admin_kefu_ajax.php?kefu_tem=" + kefu_tem.value;
	xmlHttp.open("GET", url, true);
	xmlHttp.onreadystatechange = callback;
	xmlHttp.send(null);
}


function callback() {
	if(xmlHttp.readyState == 4){
		if(xmlHttp.status == 200) {
			var mes = xmlHttp.responseXML.getElementsByTagName("message")[0].firstChild.nodeValue;
			setMessage(mes);
		}
	}
}

function setMessage(message) {
	var messageArea = form1.k_tem_con.value;
	var fontColor = "red";
	document.all ? insertAtCaret(document.form1.k_tem_con, message) : form1.k_tem_con.value += message;
	//  setfocus();
	//document.form1.k_tem_con.innerHTML = messageArea + "\n\n" + message ;
}
function insertAtCaret(textEl, text){
	if (textEl.createTextRange && textEl.caretPos){
		var caretPos = textEl.caretPos;
		caretPos.text += caretPos.text.charAt(caretPos.text.length - 2) == ' ' ? text + ' ' : text;
	} else if(textEl) {
		textEl.value += text;
	} else {
		textEl.value = text;
	}
}
</SCRIPT>
<div id="contain_out">
  <?
    include "Order_state.php";
	?>
  <table class=p9black cellspacing=0 cellpadding=0 width="100%" border=0>
              <tbody>
                <tr>
                  <td width="50%"><table width="90%" border=0 cellpadding=0 cellspacing=0>
                    <tbody>
                      <tr>
                        <td width=38><img height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32 /></td>
                        <td class=p12black nowrap><span  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $Action_say?></span></td>
                      </tr>
                    </tbody>
                  </table></td>
                  <td align=right width="50%"><table cellspacing=0 cellpadding=0 border=0>
                    <tbody>
                      <tr>
                        <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                          <tbody>
                            <tr>
                              <td align=middle width=79><!--BUTTON_BEGIN-->
                                <table>
                                  <tbody>
                                    <tr>
                                      <td valign=bottom nowrap><a href=<?php echo (isset($_GET['where'])||isset($_POST['where']))?'admin_kefu_list.php?where='.$where.'&offset='.$offset:"provider_kefu_list.php"?>><img  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0 />&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <!--BUTTON_END--></td>
                            </tr>
                          </tbody>
                        </table></td>
                        <td align=middle><table height=33 cellspacing=0 cellpadding=0 width=79 border=0>
                          <tbody>
                            <tr>
                              <td align=middle width=79><!--BUTTON_BEGIN-->
                                <table>
                                  <tbody>
                                    <tr>
                                      <td valign=bottom nowrap ><a href="javascript:checkform();"><img src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 />&nbsp;<?php echo $Basic_Command['Save'];;//保存?></a>&nbsp; </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <!--BUTTON_END--></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
    </tbody>
            </table><table class=allborder cellspacing=0 cellpadding=2  width="100%" align=center bgcolor=#f7f7f7 border=0>
                <tbody>
                  <tr>
                    <td colspan="2"><table cellspacing=0 cellpadding=0 width="100%" border=0>
                      <tbody>
                        <tr>
                          <td valign=top bgcolor=#ffffff height=300><table class=allborder cellspacing=0 cellpadding=2  width="100%" align=center bgcolor=#f7f7f7 border=0>
                            <tbody>
                              <tr>
                                <td nowrap align=right width="18%"><span  class=p9orange><?php echo $KeFu_Pack['Back_UserSubmitContent'];//用戶提交内容?>：</span></td>
                                <td colspan="2" align=right nowrap>&nbsp;</td>
                              </tr>
                              <?php if($order_serial!=""){?>
                              <tr>
                                <td nowrap align=right>訂單編號：</td>
                                <td colspan="2" align=left nowrap><?php echo $order_serial?></td>
                              </tr>
                              <?php
					}
					if($marketno!=""){
					?>
                              <tr>
                                <td nowrap align=right>賣場編號：</td>
                                <td colspan="2" align=left nowrap><?php echo $marketno?></td>
                              </tr>
                              <?php }?>
                              <tr>
                                <td nowrap align=right width="18%"><?php echo $KeFu_Pack['Back_UserTime'];//張貼的時間?>：</td>
                                <td colspan="2" align=left nowrap><?php echo getdate_kefu($lastdate)?></td>
                              </tr>
                              <tr>
                                <td nowrap align=right width="18%"><?php echo $KeFu_Pack['Back_UserName'];//張貼者帳號?>：</td>
                                <td colspan="2" align=left nowrap><?php echo $username?></td>
                              </tr>
                              <tr>
                                <td nowrap align=right width="18%"><?php echo $KeFu_Pack['Back_UserRealName'];//張貼者姓名?>：</td>
                                <td colspan="2" align=left nowrap><?php echo $realname?></td>
                              </tr>
                              <tr>
                                <td align=right ><?php echo $KeFu_Pack['email'];?>：</td>
                                <td colspan="2"><?php echo $email?></td>
                              </tr>
                              <tr>
                                <td align=right ><?php echo $KeFu_Pack['Back_SearchNo'];//查詢問題編號?>：</td>
                                <td colspan="2"><?php echo $serialnum?></td>
                              </tr>
                              <tr>
                                <td align=right ><?php echo $KeFu_Pack['Back_Type_Do'];//問題類別 - 處理情況?>：</td>
                                <td colspan="2"><?php echo $type_chuli_name?></td>
                              </tr>
                              <tr>
                                <td align=right ><?php echo $KeFu_Pack['Back_UserContent'];//張貼者的內容?>：</td>
                                <td colspan="2" width="82%"><?php echo nl2br($k_kefu_con)?></td>
                              </tr>
                              <tr>
                                <td colspan="3"><hr width="95%" size="1" /></td>
                              </tr>
                              <tr>
                                <td nowrap align=right width="18%"><span  class=p9orange><?php echo $KeFu_Pack['Back_UserRContent'];//回復的内容?>：</span></td>
                                <td colspan="2" align=right nowrap>&nbsp;</td>
                              </tr>
                              <tr>
                                <?php
                    if ($postnum==0) {
                    	$kefu_post_list = <<<EOF
                    <TR>
                    <TD colspan="3" align=center noWrap>{$KeFu_Pack[No_Report]} </TD>
					</TR>
EOF;
}else {
	$Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_posts` where kid = $_GET[kid]";
	$Query_linshi = $DB->query($Sql_linshi);
	$kefu_posts = array();
	while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
		$kefu_posts[] = $Rs_linshi;
	}
	$i = 1;
	foreach ($kefu_posts as $value) {
		$kefu_post_date = getdate_kefu($value['postdate']);
		$kefu_post_con  = nl2br($value[k_post_con]);
		$kefu_post_list .= <<<EOF
                        <TR>
                          <TD noWrap align=right width="18%" bgColor="#ffffff" >{$KeFu_Pack['Report']} # {$i}<br>
                          $value[username] <br>
                          $kefu_post_date
                          </TD>
                          <TD colspan="2" align=left width="82%" bgColor="#ffffff" ><b>$value[k_post_title]</b> <br>
                          $kefu_post_con
                          </TD>
                        </TR>
						
						
EOF;
						$ifcheck = $value[ifcheck]==1?"已審核":"未審核";
						$ifcheckbut = $value[ifcheck]==1?"撤銷審核":"通過審核";
						if ($value[provider_id]>0){
						$kefu_post_list .= "
						<TR>
                          <TD noWrap align=right width=\"18%\" bgColor=\"#ffffff\" >審核
                          </TD>
                          <TD colspan=\"2\" align=left width=\"82%\" bgColor=\"#ffffff\" >".$ifcheck."
                          </TD>
                        </TR>
						";
						}
		$i++;
}

}
echo $kefu_post_list;
                    ?>
                              </tr>
                              <tr>
                                <td colspan="3"><hr width="95%" size="1" /></td>
                              </tr>
                              <tr>
                                <td nowrap align=right width="18%"><span  class=p9orange><?php echo $KeFu_Pack['Submit_Report']?>：</span></td>
                                <td colspan="2" align=right nowrap>&nbsp;</td>
                              </tr>
                              <tr>
                                <form name=form1 action="provider_kefu_save.php" method=post>
                                  <input type="hidden" name="Action" value="Post" />
                                  <input type="hidden" name="kid" value="<?php echo $Kid?>" />
                                  <?php
                    if ($_GET['where']) {
                    	$where = str_replace('+','',$_GET['where']);
                    	$offset = intval($_GET['offset']);
                    }else {
                    	$where = $_POST['where'];
                    	$offset = intval($_POST['offset']);
                    }
                    ?>
                                  <input type="hidden" name="where" value="<?php echo $where?>" />
                                  <input type="hidden" name="offset" value="<?php echo $offset?>" />
                                </form>
                              </tr>
                              <tr>
                                <td nowrap align=right width="18%"><?php echo $KeFu_Pack['Back_Type_Do'];//問題類別-處理情況?>：</td>
                                <td colspan="2" align="left" nowrap><select name="type_chuli" class="trans-input">
                                  <?
					  $Query_linshi = '';

					  $kefu_type = array();
					  $kefu_chuli = array();

					  $Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_type`";
					  $Query_linshi = $DB->query($Sql_linshi);
					  while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
					  	$kefu_type[] = $Rs_linshi;
					  }
					  $Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_chuli`";
					  $Query_linshi = $DB->query($Sql_linshi);
					  while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
					  	$kefu_chuli[] = $Rs_linshi;
					  }

					  foreach ($kefu_type as $v) {
					  	foreach ($kefu_chuli as $a) {
					  		$Add_Bclass = $v['k_type_id'].'-'.$a['k_chuli_id']==$type_chuli ? " selected=\"selected\" " :  "" ;
					  		echo "<option value=".$v['k_type_id'].'-'.$a['k_chuli_id'].$Add_Bclass.">".$v['k_type_name'].'-'.$a['k_chuli_name']."</option>";
					  	}
					  }

					  ?>
                                </select></td>
                              </tr>
                            </tbody>
                            <tr>
                              <td align=right ><?php echo $KeFu_Pack['Back_ViewReportSetting'];//查看回覆的情況設定?>：</td>
                              <td colspan="2" width="82%"><select name="iflogin" class="trans-input">
                                <option value="0"><?php echo $KeFu_Pack['Back_ViewReportNoNeedLogin'];//客戶 - 不需登入即可查看此主題?></option>
                                <option value="1"><?php echo $KeFu_Pack['Back_ViewReportNeedLogin'];//客戶 - 需登入即可查看此主題?></option>
                              </select></td>
                            </tr>
                            <tr>
                              <td align=right ><?php echo $KeFu_Pack['Back_ThemeContent'];//樣版的內容?>：</td>
                              <td colspan="2" width="82%"><?php echo $FUNCTIONS->Input_Box('textarea','k_tem_con',''," id=message cols=80 rows=6")?></td>
                            </tr>
                          </table></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  <tr>
                    <td nowrap align=right>&nbsp;</td>
                    <td colspan="2" align=right nowrap>&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap align=right>&nbsp;</td>
                    <td colspan="2" align=right nowrap>&nbsp;</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                    <td colspan="2"><table cellspacing=0 cellpadding=0 width="100%" border=0>
                      <tbody>
                        <tr>
                          <td valign=top bgcolor=#ffffff height=300><table class=allborder cellspacing=2 cellpadding=2  width="100%" align=center bgcolor=#f7f7f7 border=0>
                            <tbody>
                              <tr>
                                <form name=form1 action="provider_kefu_save.php" method=post>
                                </form>
                              </tr>
                            </tbody>
                          </table></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                </tbody>
              </table>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
