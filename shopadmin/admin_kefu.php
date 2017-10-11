<?php
include_once "Check_Admin.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_GET['kid']!="" && $_GET['Action']=='Modi'){
	$Kid = intval($_GET['kid']);
	$Action_value = "Post";
	$Action_say  = $KeFu_Pack['Back_Nav_title_two'];//'客服-->回復'
	$Query = $DB->query("select k.*,g.* from `{$INFO[DBPrefix]}kefu` k left join `{$INFO[DBPrefix]}goods` as g on k.marketno=g.goodsno where kid=".intval($Kid)." limit 0,1");
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
		$gid           =  $Result['gid'];
		$goodsname           =  $Result['goodsname'];
		$type_chuli_array    =  explode("-",$Result['type_chuli']);

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
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT type="text/javascript">
function checkform(){

	if (chkblank(form1.select_type.value)){
		form1.select_type.focus();
		alert('<?php echo $KeFu_Pack['Back_Js_Alert_one']?>');  //请输入問題類別名称
		return;
	}
	if (chkblank(form1.chuli.value)){
		form1.chuli.focus();
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
	$.ajax({
				url: url,
				type:'get',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				    setMessage(msg);
					//$('#classcount').attr("value",counts+1);
					//$(msg).appendTo('#extclass')
				}
	});

	/*
	xmlHttp.open("GET", url, true);
	xmlHttp.onreadystatechange = callback;
	xmlHttp.send(null);
	*/
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
	var messageArea = document.getElementById("k_tem_con").innerHTML;
	//alert(messageArea);
	var fontColor = "red";

	//document.all ? insertAtCaret(document.form1.k_tem_con, message) : form1.k_tem_con.value += message;
	document.getElementById("k_tem_con").innerHTML = messageArea  + "\n\n"+ message;
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
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $Action_say?></SPAN></TD>
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
                            <a href="admin_kefu_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];;//保存?></a></TD>                </TR></TBODY></TABLE><!--BUTTON_END-->

                          </TD></TR></TBODY></TABLE>

                  </TD></TR></TBODY></TABLE>
            </TD>
          </TR>
        </TBODY>
</TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%"><SPAN  class=p9orange><br />
                            <i class="icon-long-arrow-right" style="font-size:14px; margin-right:5px; margin-left:5px; color:#ff6600;"></i> <?php echo $KeFu_Pack['Back_UserSubmitContent'];//用戶提交内容?>：</span></TD>
                            <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>
                          <?php if($order_serial!=""){?>
                          <TR>
                            <TD noWrap align=right>訂單編號：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $order_serial?></TD>
                            </TR>
                          <?php
					}
					if($marketno!=""){
					?>
                          <TR>
                            <TD noWrap align=right>賣場編號：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $marketno?></TD>
                            </TR>
                          <?php }
						  if($marketno!=""){
						  ?>
                          <TR>
                            <TD noWrap align=right>商品：</TD>
                            <TD colspan="2" align=left noWrap><a href="admin_goods.php?Action=Modi&gid=<?php echo $gid?>"><?php echo $goodsname?></a></TD>
                          </TR>
                          <?php }?>
                          <TR>
                            <TD noWrap align=right width="18%"><?php echo $KeFu_Pack['Back_UserTime'];//張貼的時間?>：</TD>
                            <TD colspan="2" align=left noWrap><?php echo getdate_kefu($lastdate)?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%"><?php echo $KeFu_Pack['Back_UserName'];//張貼者帳號?>：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $username?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%"><?php echo $KeFu_Pack['Back_UserRealName'];//張貼者姓名?>：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $realname?></TD>
                            </TR>
                          <TR>
                            <TD align=right ><?php echo $KeFu_Pack['email'];?>：</TD>
                            <TD colspan="2"><?php echo $email?></TD>
                            </TR>
                          <TR>
                            <TD align=right ><?php echo $KeFu_Pack['Back_SearchNo'];//查詢問題編號?>：</TD>
                            <TD colspan="2"><?php echo $kid?></TD>
                            </TR>
                          <TR>
                            <TD align=right ><?php echo $KeFu_Pack['Back_Type_Do'];//問題類別 - 處理情況?>：</TD>
                            <TD colspan="2"><?php echo $type_chuli_name?></TD>
                            </TR>
                          <TR>
                            <TD align=right ><?php echo $KeFu_Pack['Back_UserContent'];//張貼者的內容?>：</TD>
                            <TD colspan="2" width="82%"><?php echo nl2br($k_kefu_con)?></TD>
                            </TR>
                          <TR>
                            <TD colspan="3"><hr width="95%" size="1"></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%"><SPAN  class=p9orange><i class="icon-long-arrow-left" style="font-size:14px; margin-right:5px; margin-left:5px; color:#ff6600;"></i> <?php echo $KeFu_Pack['Back_UserRContent'];//回復的内容?>：</span></TD>
                            <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>
                          <TR>
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
                          $kefu_post_con<br><br>
                          <a href='admin_kefu_save.php?Action=DelPost&kid=$Kid&k_post_id=$value[k_post_id]'><SPAN  class=p9orange>{$KeFu_Pack['Del_Report']}</span>
                          </TD>
                        </TR>
EOF;
						$ifcheck = $value[ifcheck]==1?"已審核":"未審核";
						$ifcheckbut = $value[ifcheck]==1?"撤銷審核":"通過審核";
						$Query_p = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($value[provider_id])." limit 0,1");
						$Result_p= $DB->fetch_array($Query_p);
						if ($value[provider_id]>0){
						$kefu_post_list .= "
						<TR>
                          <TD noWrap align=right width=\"18%\" bgColor=\"#ffffff\" >供應商
                          </TD>
                          <TD colspan=\"2\" align=left width=\"82%\" bgColor=\"#ffffff\" >" . $Result_p['provider_name'] . "
                          </TD>
                        </TR>
						<TR>
                          <TD noWrap align=right width=\"18%\" bgColor=\"#ffffff\" >審核
                          </TD>
                          <TD colspan=\"2\" align=left width=\"82%\" bgColor=\"#ffffff\" >".$ifcheck."<br>
						  <a href=\"admin_kefu_save.php?Action=check&state=".$value[ifcheck]."&k_post_id=".$value[k_post_id]."&kid=".$Kid."\">".$ifcheckbut."</a>
                          </TD>
                        </TR>
						";
						}
		$i++;
}

}
echo $kefu_post_list;
                    ?>
                            <TR>
                              <TD colspan="3"><hr width="95%" size="1"></TD>
                              </TR>
                          <TR>
                            <TD noWrap align=right width="18%"><SPAN  class=p9orange><i class="icon-pencil" style="font-size:14px; margin-right:5px; margin-left:5px; color:#ff6600;"></i> <?php echo $KeFu_Pack['Submit_Report']?>：</span></TD>
                            <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>
                          <TR>
                            <FORM name=form1 action="admin_kefu_save.php" method=post>
                              <input type="hidden" name="Action" value="Post">
                              <input type="hidden" name="kid" value="<?php echo $Kid?>">
                              <?php
                    if ($_GET['type']) {
                    	$type = str_replace('+','',$_GET['type']);
                    	$offset = intval($_GET['offset']);
                    }else {
                    	$type = $_POST['type'];
                    	$offset = intval($_POST['offset']);
                    }
                    ?>
                              <input type="hidden" name="type" value="<?php echo $type?>">
                              <input type="hidden" name="offset" value="<?php echo $offset?>">
                              <TR>
                                <TD noWrap align=right width="18%">問題類別：</TD>
                                <TD colspan="2" align="left" noWrap>
                                  <select name="select_type" class="trans-input">
                                    <option value=""><?php echo $Basic_Command['Please_Select'] ;?></option>
                                    <?php
                    $Query_linshi = '';
                    $kefu_type = array();
                    $kefu_chuli = array();
                    $Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_type` order by checked Desc";
                    $Query_linshi = $DB->query($Sql_linshi);
                    while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
                    	//$kefu_type[] = $Rs_linshi;
						$Add_Bclass = $Rs_linshi['k_type_id']==$type_chuli_array[0] ? " selected=\"selected\" " :  "" ;
						echo "<option value=".$Rs_linshi['k_type_id'].$Add_Bclass.">".$Rs_linshi['k_type_name']."</option>";
                    }
					  ?>
                                    </select>
                                  </TD></TR>
                                <TR>
                                  <TD noWrap align=right width="18%">處理情況：</TD>
                                  <TD colspan="2" align="left" noWrap>
                                    <select name="chuli" class="trans-input">
                                      <option value=""><?php echo $Basic_Command['Please_Select'] ;?></option>
                                      <?php
                     $Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_chuli` order by checked Desc";
                    $Query_linshi = $DB->query($Sql_linshi);
                    while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
                    	$Add_Bclass = $Rs_linshi['k_chuli_id']==$_GET['chuli'] ? " selected=\"selected\" " :  "" ;
						echo "<option value=".$Rs_linshi['k_chuli_id'].$Add_Bclass.">".$Rs_linshi['k_chuli_name']."</option>";
                    }

					?>
                                      </select>
                                    </TD></TR>
                                  <TR>
                                    <TD noWrap align=right width="18%"><?php echo $KeFu_Pack['Back_ThemeUse'];//樣版使用?>：</TD>
                                    <TD colspan="2" align="left" noWrap>

                                      <select name="kefu_tem" id="kefu_tem"  class="trans-input">
                                        <?
					  $Query_linshi = '';
					  $kefu_tem = array();
					  $Sql_linshi = " select k_tem_id , status , k_tem_name from `{$INFO[DBPrefix]}kefu_tem`";
					  $Query_linshi = $DB->query($Sql_linshi);
					  while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
					  	$kefu_tem[] = $Rs_linshi;
					  }

					  foreach ($kefu_tem as $v) {

					  	echo "<option value=".$v['k_tem_id'].">".$v['k_tem_name']."</option>";
					  }

					  ?></select>
                                      <button value="true"  type="button" name="button" id="button"  onClick="javascript:gettem();" class="submit"><?php echo $Basic_Command['Insert'];?></button>
                                      <!--input type="button" value="<?php echo $Basic_Command['Insert']?>" onClick="javascript:gettem();"--><?php echo $KeFu_Pack['Back_ThemeSay']?>
                                    </TD></TR>

                                    <TR>
                                      <TD align=right ><?php echo $KeFu_Pack['Back_ViewReportSetting'];//查看回覆的情況設定?>：</TD>
                                      <TD colspan="2" width="82%">
                                        <select name="iflogin" class="trans-input">
                                          <option value="0"><?php echo $KeFu_Pack['Back_ViewReportNoNeedLogin'];//客戶 - 不需登入即可查看此主題?></option>
                                          <option value="1"><?php echo $KeFu_Pack['Back_ViewReportNeedLogin'];//客戶 - 需登入即可查看此主題?></option>
                                        </select>
                                      </TD>
                                      </TR>
                                      <TR>
                                        <TD align=right ><?php echo $KeFu_Pack['Back_ThemeContent'];//樣版的內容?>：</TD>
                                        <TD colspan="2" width="82%"><?php echo $FUNCTIONS->Input_Box('textarea','k_tem_con',''," id=k_tem_con cols=80 rows=6")?></TD>
                                        </TR>

                                </form>


                            <TR>
                              <TD noWrap align=right>&nbsp;</TD>
                              <TD colspan="2" align=right noWrap>&nbsp;</TD>
                              </TR>
                          <TR>
                            <TD colspan="3" align=center noWrap><table width="95%" border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td class="p12black"><br>
                                  <i class="icon-list-alt" style="font-size:14px; margin-right:5px; margin-left:5px; color:#666;"></i> 操作記錄</td>
                                </tr>
                              <tr>
                                <td align="center">
                                  <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F7F7F7"   class="allborder">
                                    <tr>
                                      <td width="13%">時間</td>
                                      <td width="57%">操作人</td>
                                      <td width="30%">說明</td>
                                      </tr>
                                    <?php
                $Sql_action = "select * from `{$INFO[DBPrefix]}kefu_log` where kefu_id='" . $Kid . "' order by pubtime asc";
				$Query_action    = $DB->query($Sql_action);
				while($Rs_action=$DB->fetch_array($Query_action)){
				?>
                                    <tr>
                                      <td><?php echo date("Y-m-d H:i:s",$Rs_action['pubtime']);?></td>
                                      <td>
                                        <?php
                if($Rs_action['usertype']==-1){
					$Sql_U = "select username as uname from `{$INFO[DBPrefix]}user` where user_id='" . $Rs_action['user_id'] . "'";
					$usertitle = "[會員]";
				}elseif($Rs_action['usertype']==0){
					$Sql_U = "select sa as uname from `{$INFO[DBPrefix]}administrator` where sa_id='".$Rs_action['user_id']."' limit 0,1";
					$usertitle = "[高級管理員]";
				}elseif($Rs_action['usertype']==1){
					$Sql_U = "select username as uname from `{$INFO[DBPrefix]}operater` where opid='".$Rs_action['user_id']."' limit 0,1";
					$usertitle = "[一般管理員]";
				}elseif($Rs_action['usertype']==2){
					$Sql_U = "select provider_name as uname from `{$INFO[DBPrefix]}provider` where provider_id='".$Rs_action['user_id']."' limit 0,1";
					$usertitle = "[供應商]";
				}
				$Query_U    = $DB->query($Sql_U);
				$Rs_U=$DB->fetch_array($Query_U);
				echo $Rs_U['uname'].$usertitle;
				?>
                                        </td>
                                      <td><?php echo $Rs_action['content'];?></td>
                                      </tr>
                                    <?php
				}
				?>
                              </table></td></tr></table>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>

</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
