<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Contact_Pack.php");
include_once RootDocument."/language/".$INFO['IS']."/Bottom_Pack.php";
include RootDocument."/language/".$INFO['IS']."/Admin_sys_Pack.php";

if($_POST[action]=='insert'){

	if ($_SESSION['Code_Contact']!=trim($_POST[inputcode])){
		$FUNCTIONS->sorry_back("contact.php",$Contact_Pack[theCodeError]);
	}

	$Sql = " insert into `{$INFO[DBPrefix]}contact` (companyname,address,lxr,zc,fax,tel_one,tel_two,email,url,hz1,hz2,hz3,content,idate) values ('".strip_tags($_POST[companyname])."','".strip_tags($_POST[address])."','".strip_tags($_POST[lxr])."','".strip_tags($_POST[zc])."','".strip_tags($_POST[fax])."','".strip_tags($_POST[tel_one])."','".strip_tags($_POST[tel_two])."','".strip_tags($_POST[email])."','".strip_tags($_POST[url])."','".intval($_POST[hz1])."','".intval($_POST[hz2])."','".intval($_POST[hz3])."','".$_POST[content]."','".time()."')";
	$DB->query($Sql);
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=2");
	$Result= $DB->fetch_array($Query);
	if ($Result['mail']!=""){
		$cmail = $Result['mail'];
		if (intval($_POST['hz1'])==1) {
				$hz = $Admin_sys_Pack[Sys_Spgy] . "&nbsp;&nbsp;";
			} 
            
			if (intval($_POST['hz2'])==1) { 
				$hz .=  $Admin_sys_Pack[Sys_Xssp] . "&nbsp;&nbsp;"; 
			} 
			if (intval($_POST['hz3'])==1) { 
				$hz .=  $Admin_sys_Pack[Sys_Qthzfa] . "&nbsp;&nbsp;";
			}
		$body = "<TABLE class=allborder cellSpacing=0 cellPadding=2  width=\"96%\" align=center bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width=\"25%\">&nbsp;</TD>
                      <TD colspan=\"3\" align=right noWrap>&nbsp;</TD></TR>

                    <TR>
                      <TD noWrap align=right>" . $Admin_sys_Pack[Sys_Lxr] ."：</TD>
                      <TD align=left noWrap>" . $_POST[companyname] ."</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD width=\"17%\" align=left noWrap>" . $_POST[address] ."</TD>
                      <TD width=\"11%\" align=right noWrap>&nbsp;</TD>
                      <TD width=\"47%\" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align=left noWrap>" . $_POST[lxr] ."</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>" . $_POST[zc] ."</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>" . $Admin_sys_Pack[Sys_Tel] ."：</TD>
                      <TD align=left noWrap>" . $_POST[tel_one] ."-" . $_POST[tel_two] ."</TD>
                    <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>" . $_POST[fax] ."</TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right>" . $Admin_sys_Pack[Sys_Email] ."：</TD>
                      <TD align=left noWrap>" . $_POST[email] ."</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align=left noWrap>" . $_POST[url] ."</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>" . $Admin_sys_Pack[Sys_Hzfs] ."：</TD>
                      <TD align=left noWrap>" . $hz . "</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD align=right valign=\"top\" noWrap>" . $Admin_sys_Pack[Sys_Bz] ."：</TD>
                      <TD colspan=\"2\" align=left>" . nl2br($_POST[content]) ."</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>


                    </TBODY></TABLE>";
					$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=2");
			$Rs = $DB->fetch_array($Query);
			$sysmail = "," . $Rs['mail'];
		$Array =  array("mailbody"=>$body,"mailsubject"=>$INFO['site_name'] . "聯繫我們");
		include "SMTP.Class.inc.php";
		include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
		$SMTP =  new smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
		$SMTP->MailForsmartshop($cmail . $sysmail,"","GroupSend",$Array);
	}
	if ($INFO['staticState']=='open'){
		$FUNCTIONS->sorry_back($INFO[site_url]."/HTML_C/help_8.html",$Contact_Pack[Insert_Ok]);
	}else{
		$FUNCTIONS->sorry_back("contact.php",$Contact_Pack[Insert_Ok]);
	}


}


//$info_id = intval($FUNCTIONS->Value_Manage($_GET['info_id'],'','back',''));
$info_id = 18;
$Query   = $DB->query("select info_content from `{$INFO[DBPrefix]}admin_info` where  info_id=".$info_id." limit 0,1");
$Num   = $DB->num_rows($Query);

if ($Num>0){
	$Result_Article = $DB->fetch_array($Query);
	$Content = $Result_Article['info_content'];
}

$Title =  $Bottom_Pack[Tahz]; //提案合作

$tpl->assign("Title",          $Title);         //标题
$tpl->assign("Content",        $Content);       //新闻内容
$tpl->assign("adv_array",     $adv_array);
$tpl->assign("Float_radio",               intval($INFO['float_radio']));                    //浮动广告开关
$tpl->assign("Ear_radio",                 intval($INFO['ear_radio']));                      //耳朵广告开关

$tpl->assign($Contact_Pack);
$tpl->assign("sid",time());
$tpl->display("partner.html");
?>
