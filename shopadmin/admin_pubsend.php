<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$Pub_id        = intval($FUNCTIONS->Value_Manage($_GET['pub_id'],$_POST['pub_id'],'back',''));
//***************************************************************************************************************************
include_once Classes . "/SMTP.Class.inc.php";
include_once RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
//***************************************************************************************************************************
$Query = $DB->query(" select publication_title,publication_start_time,publication_end_time,publication_content  from `{$INFO[DBPrefix]}publication` where publication_id=".intval($Pub_id)." limit 0,1"
);
$Num   = $DB->num_rows($Query);
if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$publication_title        =  $Result['publication_title'];
	$publication_start_time   =  $Result['publication_start_time'];
	$publication_end_time     =  $Result['publication_end_time'];
	$publication_content      =  $Result['publication_content'];
	$publication_alreadyread  =  $Result['publication_alreadyread'];
	$Query_time = $DB->query(" update `{$INFO[DBPrefix]}publication` set publication_end_time='".time()."' where publication_id=".intval($Pub_id));
	//$DB->free_result($Query_time);
}else{
	echo "<script language=javascript>javascript:window.history.back();</script>";
	exit;
}
$DB->free_result($Query);
unset ($Query);
unset ($Num);
unset ($Result);
//------------------------------------以上考虑在这里读出资料,是为了避免在发送资料的时候,更多的读数据库----------------------------


$Mgroup        = $_POST['Mgroup_id'];
$Num_Mgroup    = count($Mgroup);

//这里将所有将出现的内容的EMAIL都排列出来
for ($i=0;$i<$Num_Mgroup;$i++){
	$Query = $DB->query(" select mgroup_id,mgroup_list from `{$INFO[DBPrefix]}mail_group` where mgroup_id=".intval($Mgroup[$i])." limit 0,1" );
	while ($Rs = $DB->fetch_array($Query)){
		$Mgroup_list .=$Rs[mgroup_list];
	}
	$Mgroup_list .=",";
}
$DB->free_result($Query);
unset ($Query);
unset ($Rs);





//$pos = strpos($Mgroup_list,"all");
$pos = strpos($Mgroup_list,"all");
if ($pos === false) {

}else{  //如果用户资料中包括ALL 那么系统将把所有会员的资料哪出来用于发送邮件!!
	$Mgroup_list = '';
	$Query = $DB->query(" select email from `{$INFO[DBPrefix]}user` where email!='' ");
	while ($Rs = $DB->fetch_array($Query)){
		if ($FUNCTIONS->validate_email($Rs['email'])){
			$Mgroup_list .= $Rs['email'].",";
		}
	}
	$DB->free_result($Query);
}

unset ($Query);
unset ($Rs);

$Mgroup_list = substr($Mgroup_list,0,strlen($Mgroup_list)-1); //将最后出现的逗号去掉!

$Array_list =  array("mailsubject"=>trim($publication_title),"mailbody"=>trim($publication_content));
//$SMTP->MailForsmartshop(trim($Mgroup_list),"","GroupSend",$Array_list);


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
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="allborder" >
        <form name="form1" method="post" action="admin_pubsend_act.php">
          <input type="hidden" name="email_str" value="<?php echo "'',".$Mgroup_list?>">
          <input type="hidden" name="email_content" value='<?php echo base64_encode($publication_content)?>'>
          <input type="hidden" name="email_single" value="">
          <input type="hidden" name="email_title" value='<?php echo base64_encode($publication_title)?>'>
          <input type="hidden" name="send_num" value="">
   	      <input type="hidden" name="pub_id" value="<?php echo $Pub_id?>">
          <tr> 
            <td width="20%" align="right" noWrap><?php echo $Mail_Pack[SendPer];//发送进度?>：</td>
            <td height="40">
              <table width="400" border="0" cellspacing="1" cellpadding="2" bgcolor="#000000">
                 <tr>
				 <td width=100% bgcolor=#FFFFFF><div align=center><div id=per style="position:absolute;font-size:7pt"></div></div>
				 <table id="used"  >
				 <tr>
                   <td height="12" background="./images/<?php echo $INFO[IS]?>/gird-per.gif"></td>
				   </tr>
				   </table>
				</td>
                 </tr>
                </table>
				</td>
          </tr>
          <tr> 
            <td height="25" align="right" width="20%"><div id='Onsend'><?php echo $Mail_Pack[SendZhong];//正在发送?></div></td>
            <td height="25"> 
              <div id="emailing"></div>
            </td>
          </tr>
          <tr id="tr1" style="DISPLAY: none"> 
            <td height="50" valign="top" width="20%" align="right"> <?php  echo $Mail_Pack[ErrorSendMail] ;//未成功发送的邮件?></td>
            <td height="50" valign="top"> 
              <textarea name="error_email" cols="60" wrap="VIRTUAL" rows="20"></textarea>
          </tr>
        </form>
        <tr style="DISPLAY:none"> 
          <td height="50" valign="top" width="20%"> <iframe name="send_act1" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe> 
          </td>
          <td height="50" valign="top"> <iframe name="send_act2" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe> 
          </td>
          <td height="50" valign="top"> <iframe name="send_act3" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe> 
          </td>
          <td height="50" valign="top"> <iframe name="send_act4" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe> 
          </td>
          <td height="50" valign="top"> <iframe name="send_act5" src="about:blank" scrolling="no" FrameBorder=0 width=100% height=100%></iframe> 
          </td>
        </tr>
      </table>
				  
				  </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>

   </TBODY>
</TABLE>

<script language="JavaScript">

var stremail = document.form1.email_str.value;	//str emial
arrItems = stremail.split(",");			//email group array
var tmp_num = arrItems.length ;			//array length

var arr_recsts = new Array();			//status array
var n = 1;								//email tags
var arr_framemail = new Array();
var time_out;
var finish = 0;							//finish email number
arr_recsts[1] = 0;
arr_recsts[2] = 0;
arr_recsts[3] = 0;
arr_recsts[4] = 0;
arr_recsts[5] = 0;

initSend();

function initSend()
{
	for (var i = 1; i <= 5; i++)
	{
		// go on send, if a iframe successful send a email ,but not complete
		if (arr_recsts[i] == 0 && n < tmp_num)
		{
			document.form1.email_single.value = arrItems[n];
			//alert(document.form1.email_single.value);
			document.form1.send_num.value = i ;
			document.form1.action = "admin_pubsend_act.php";
			document.form1.target = "send_act" + i;
			arr_framemail[i] = n ;		//record the current of email
			//window.status += "recsts="+ i ;
			document.form1.submit();
			arr_recsts[i] = 1;
			n++;
		}
		if(arr_recsts[i]>0)
		{
			arr_recsts[i]++;
		}


		if (arr_recsts[i] > 5)
		{
			//	form1.error_email.value += arrItems[arr_framemail[i]] + "\n";
			progress(i);
		}

	}


	if (n >= tmp_num && checkfinish())
	{
		clearTimeout(time_out);
		if (form1.error_email.value != "")
		{
			tr1.style.display="";
		}
		per.innerHTML = 100+"%";
		Onsend.style.display="none";
		emailing.innerHTML = "<?php echo "<img src=images/".$INFO[IS]."/OK.gif border=0 >&nbsp;&nbsp;".$Mail_Pack[AllOver]?>"    //全部完成!!

	}
	else
	{
		time_out = setTimeout("initSend()",1000);
	}

}

function checkfinish()
{
	for(var j=1; j<=5; j++)
	{
		if(arr_recsts[j] > 0 && arr_recsts[j] < 10) return false;

	}
	return true;
}

function progress(num)
{
	finish++;
	var nowpre = Math.floor((finish / (tmp_num - 1)) * 100) ;
	window.status += " now=" + nowpre +"|";

	used.width = nowpre + "%";
	per.innerHTML=nowpre+"%";

	var mailid = arr_framemail[num];
	window.status += " m=" +mailid + "|";
	emailing.innerHTML = "&nbsp;&nbsp;" + arrItems[mailid] + " ...";
	arr_recsts[num] = 0 ;
}
</script>

                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
