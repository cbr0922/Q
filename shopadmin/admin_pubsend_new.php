<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";

$Query = $DB->query(" delete from `{$INFO[DBPrefix]}falsemail` where no='0' and sendtime=''");

$Mgroup        = $_POST['Mgroup_id'];
$Num_Mgroup    = count($Mgroup);
$mail_list     = array();

//这里将所有将出现的内容的EMAIL都排列出来
$Pub_id        = intval($_POST['pub_id']);
if($INFO['nuevo.ifopen']==true){
	include_once("../modules/apmail/nuevomailer.class.php");
	$nuevo = new NuevoMailer;
	$send_array = array($user_id);
	$idCampaign = $nuevo->setEdm($Pub_id,$_POST['Mgroup_id']);
	if($idCampaign!=false){
		$nuevo->queryMail($idCampaign,"admin_publication_list.php");
		exit;	
	}else{
		echo "ERROR";exit;	
	}
}

$Query_time = $DB->query(" update `{$INFO[DBPrefix]}publication` set publication_end_time='".time()."' where publication_id=".intval($Pub_id));
$mi = 0;
$i = 0;
for ($i=0;$i<$Num_Mgroup;$i++){
	//郵件組中的郵件列表
	$Sql      = "select * from `{$INFO[DBPrefix]}mail_group` where mgroup_id='" . intval($Mgroup[$i]) . "' order by mgroup_id asc ";
	$Query    = $DB->query($Sql);
	$Rs=$DB->fetch_array($Query);
	if($Rs['searchlist']=="All"){
		 $Sql = "select * from `{$INFO[DBPrefix]}user` ";
	}elseif($Rs['searchlist']=="noDing"){
		$Sql = "select * from `{$INFO[DBPrefix]}user` where dianzibao=0";
	}else{
		$Sql      = "select m.* from `{$INFO[DBPrefix]}mail_group_list` as m where group_id='" . intval($Mgroup[$i]) . "' order by m.email asc ";
	}
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		while ($Rs_sub = $DB->fetch_array($Query)){
			$mail_list[$i] = $Rs_sub['email'];
			$i++;
		}
	}
}
$DB->free_result($Query);
unset ($Query);
unset ($Rs);

if(trim($_POST['maillist'])!=""){
	$othermail = explode(",",trim($_POST['maillist']));
	$mail_list = array_merge($mail_list,$othermail);
}

$i=0;
$mail_a = array();
foreach($mail_list as $k=>$v){
	if(trim($v)!=""){
		$mail_a[$i] = $v;
		$i++;
	}
}



$mail_list = $mail_a;

if (is_array($mail_list)){
$pos = in_array("all",$mail_list);
if ($pos >0){  //如果用户资料中包括ALL 那么系统将把所有会员的资料哪出来用于发送邮件!!
	$ui = 0;
	$mail_list = array();
	$Query = $DB->query(" select email from `{$INFO[DBPrefix]}user` where email!='' ");
	while ($Rs = $DB->fetch_array($Query)){
		if ($FUNCTIONS->validate_email($Rs['email'])){
			$mail_list[$ui] = str_replace("\r","",str_replace("\n","",str_replace("\\","",str_replace("\"","",$Rs['email']))));
			$ui++;
		}
	}
	$DB->free_result($Query);
}

$mail_list = array_unique($mail_list);

unset ($Query);
unset ($Rs);
}

$persendnum = 100;

$totalnum = count($mail_list);
$sendtimes = ceil($totalnum/$persendnum);
if ($sendtimes==0){
	$sendtimes = 1;
}

$sql = "select max(no) as maxno from `{$INFO[DBPrefix]}falsemail`";
$Query = $DB->query($sql);
$Result= $DB->fetch_array($Query);
$maxno = intval($Result[maxno])+1;

foreach($mail_list as $v){
	$Query = $DB->query(" insert into `{$INFO[DBPrefix]}falsemail` (mail,pid,no,sendtime) values ('" . str_replace("\r","",str_replace("\n","",str_replace("\\","",str_replace("\"","",$v)))) . "','0','0','')");
}
?>
<HTML  xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Mail_Pack[SendEmail];//发送邮件?></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center   border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
      <?php  include_once "Order_state.php";?>
  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD></TR>
  </TBODY>
  </TABLE>


<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>

  
   <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif"   width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Mail_Pack[SendEmail];//发送邮件?>
                </SPAN></TD>
              </TR>
			  </TBODY>
			 </TABLE>
		  </TD>
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
							<a href="admin_publication_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Return']; ?></a>&nbsp; 
							</TD>
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
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD vAlign=top bgColor=#ffffff height=300>
                  <form name="form1" method="post" action="admin_pubsend_act.php">
          <input type="hidden" name="email_str" value="<?php echo "'',".$Mgroup_list?>">
          <input type="hidden" name="email_content" value='<?php echo base64_encode($publication_content)?>'>
          <input type="hidden" name="email_single" value="">
          <input type="hidden" name="email_title" value='<?php echo base64_encode($publication_title)?>'>
          <input type="hidden" name="send_num" value="">
   	      <input type="hidden" name="pub_id" value="<?php echo $Pub_id?>"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="allborder" >
        
          <tr>
            <td align="right" noWrap>&nbsp;</td>
            <td height="40">總共需要發送<?=$totalnum?>封郵件</td>
          </tr>
          <tr> 
            <td width="20%" align="right" noWrap><?php echo $Mail_Pack[SendPer];//发送进度?>：</td>
            <td height="40">
              <table width="400" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
                 <tr>
				 <td width=100% bgcolor=#FFFFFF><div align=center><div id="per" style="position:absolute;font-size:7pt"></div></div>
				 <table id="used"  >
				 <tr>
                   <td height="12" background="./images/<?php echo $INFO[IS]?>/gird-per.gif"></td>
				   </tr>
				   </table>				</td>
                 </tr>
                </table>				</td>
          </tr>
          <tr> 
            <td height="25" align="right" width="20%"><div id='Onsend'><?php echo $Mail_Pack[SendZhong];//正在发送?></div></td>
            <td height="25"> 
              <div id="emailing"></div>            </td>
          </tr>
          <tr id="tr1"> 
            <td height="50" valign="top" width="20%" align="right">已發送的郵件</td>
            <td height="50" valign="top"> 
              <textarea name="error_email" id="error_email" cols="50"  rows="20"></textarea>
              <input type="hidden" name="mailno" id="mailno" value="1">          
              </tr>
        
        <tr style="DISPLAY:none"> 
          <td height="50" valign="top" width="20%"> <iframe name="send_act1" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe>          </td>
          <td height="50" valign="top"> <iframe name="send_act2" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe>          </td>
          <td height="50" valign="top"> <iframe name="send_act3" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe>          </td>
          <td height="50" valign="top"> <iframe name="send_act4" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe>          </td>
          <td height="50" valign="top"> <iframe name="send_act5" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe>          </td>
        </tr>
      </table></form>
				  
				  </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>

   </TBODY>
</TABLE>


                      <div align="center"><?php include_once "botto.php";?></div>
<script language="javascript">
function GetO(){
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
var mailarray = new Array();
<?php 
/*
for($i=0;$i<count($mailstr);$i++){
	echo "mailarray[" . $i . "]=\"" . str_replace("\r","",str_replace("\n","",str_replace("\\","",str_replace("\"","",$mailstr[$i])))) . "\";\n";
}
*/
?>
var ajax = GetO();
function getSendmail() { 

	var url = "admin_ajax_sendmail.php";
	var result ;
	var mailno;
	mailno = 1;
	mailno = document.getElementById("mailno").value;
	
	
	var per = document.getElementById("per");
	var used = document.getElementById("used");
	//alert(mailno);
	url += "?mailno=" + mailno + "&persendnum=<?php echo $persendnum?>&pub_id=<?php echo $_POST["pub_id"]?>&maxno=<?php echo $maxno;?>&date=<?php echo time();?>";
	//alert(mailarray[mailno-1]);
	ajax.open("GET", url, true); 
	var result ;
	ajax.setRequestHeader("If-Modified-Since","0");
    ajax.onreadystatechange = function() { 
		 if (ajax.readyState == 4 && ajax.status == 200) { 
		 		result = ajax.responseText; 
				//alert(result);
				
				document.getElementById("error_email").innerText += result;
				a = Math.round(parseFloat((mailno)*<?=number_format(100.00/$sendtimes,4)?>),2);
				if(a==0)
					a =1;
				per.innerHTML=a+"%";
				used.width = a + "%";
				
				document.getElementById("mailno").value = Math.round(parseFloat(mailno),2) + 1;
				mailno = document.getElementById("mailno").value;
				
				if (Math.round(parseFloat(mailno),2) <= <?php echo $sendtimes;?>){
					//alert(result);
					//getSendmail();
					setTimeout("getSendmail()",120000);
				}
				else{
					per.innerHTML= "100%";
					used.width = "100%";
					document.getElementById("emailing").innerHTML = "<img src=images/big5/OK.gif border=0 >&nbsp;&nbsp;全部完成";
				}
		 }else if (ajax.readyState == 4 && ajax.status != 200) {
			alert("服務器中斷，將在10分鐘后自動連接繼續發送，請耐心等待");
			setTimeout("getSendmail()",60000*10); 
		}
	}
	ajax.send(null);
	
	
	
}
getSendmail();
</script>
</BODY>
</HTML>