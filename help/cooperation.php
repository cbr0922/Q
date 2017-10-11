<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include("../configs.inc.php");
include ("global.php");
include (RootDocument."/language/".$INFO['IS']."/Contact_Pack.php");
include_once RootDocument."/language/".$INFO['IS']."/Bottom_Pack.php";
include RootDocument."/language/".$INFO['IS']."/Admin_sys_Pack.php";

if($_POST[action]=='insert'){

	/*if ($_SESSION['Code_Contact']!=trim($_POST[inputcode])){
		$FUNCTIONS->sorry_back("cooperation.php",$Contact_Pack[theCodeError]);
	}*/
	
	include("securimage.php"); 
	$img=new Securimage(); 
	$valid=$img->check($_POST['inputcode']);
	if($valid==false) { 
		$FUNCTIONS->sorry_back("back","驗證碼錯誤");
	}

	$Sql = " insert into `{$INFO[DBPrefix]}contact` (companyname,companyname1,address,title ,lxr,zc,fax,tel_one,tel_two,mobile,email,url,hz1,content,idate) values ('".strip_tags($_POST[companyname])."','".strip_tags($_POST[companyname1])."','".strip_tags($_POST[address])."','".strip_tags($_POST[title])."','".strip_tags($_POST[lxr])."','".strip_tags($_POST[zc])."','".strip_tags($_POST[fax])."','".strip_tags($_POST[tel_one])."','".strip_tags($_POST[tel_two])."','".strip_tags($_POST[mobile])."','".strip_tags($_POST[email])."','".strip_tags($_POST[url])."','廠商合作','".$_POST[content]."','".time()."')";
	$DB->query($Sql);
	//$Query = $DB->query("select * from `{$INFO[DBPrefix]}sysmail` where id=2");
	//$Result= $DB->fetch_array($Query);
	//if ($Result['mail']!=""){
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
                      <TD align=left noWrap>" . $_POST[companyname1] ."</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
					<TR>
                      <TD noWrap align=right>公司名稱：</TD>
                      <TD align=left noWrap>" . $_POST[companyname] ."</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
					<TR>
                      <TD noWrap align=right>職稱：</TD>
                      <TD align=left noWrap>" . $_POST[title] ."</TD>
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
					</TR>
					<TR>
                    <TD align=right noWrap>手機號碼：</TD>
                      <TD align=left noWrap>" . $_POST[mobile] ."</TD>
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
                      <TD align=left noWrap>廠商合作</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD align=right valign=\"top\" noWrap>" . $Admin_sys_Pack[Sys_Bz] ."：</TD>
                      <TD colspan=\"2\" align=left>" . nl2br($_POST[content]) ."</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>


                    </TBODY></TABLE>";
					
		$Query = $DB->query("select o.email from `{$INFO[DBPrefix]}operater` as o inner join `{$INFO[DBPrefix]}operatergroup` as og on o.groupid=og.opid where og.maillist like '%,30' or og.maillist like '%,30,%' or og.maillist like '30,%' or og.maillist='30'");
		$Result= $DB->fetch_array($Query);
		while($Result= $DB->fetch_array($Query)){
			if ($Result['email']!=""){
				$sysmail .= "," . $Result['email'];	
			}
		}
		$Sql   = "select * from `{$INFO[DBPrefix]}administrator` as og where og.maillist like '%,30' or og.maillist like '%,30,%' or og.maillist like '30,%' or og.maillist='30'";
		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			$Result = $DB->fetch_array($Query);
			$sysmail .= "," . $Result['email'];	
		}
		$Array =  array("sendcontent"=>$body,"mailsubject"=>$INFO['site_name'] . "廠商合作");
		include "SMTP.Class.inc.php";
		include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
		$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
		$SMTP->MailForsmartshop($cmail . $sysmail,"",30,$Array);
	//}
	if ($INFO['staticState']=='open'){
		$FUNCTIONS->sorry_back($INFO[site_url]."/HTML_C/help_8.html",$Contact_Pack[Insert_Ok]);
	}else{
		$FUNCTIONS->sorry_back("cooperation.php",$Contact_Pack[Insert_Ok]);
	}


}


//$info_id = intval($FUNCTIONS->Value_Manage($_GET['info_id'],'','back',''));
$info_id = 19;
$Query   = $DB->query("select info_content, title from `{$INFO[DBPrefix]}admin_info` where  info_id=".$info_id." limit 0,1");
$Num   = $DB->num_rows($Query);

if ($Num>0){
	$Result_Article = $DB->fetch_array($Query);
	$Content = $Result_Article['info_content'];
	$Title = $Result_Article['title'];
}

$Sql      = "select * from `{$INFO[DBPrefix]}contact_type` order by orderby  ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$type_array = array();
$i = 0;
while ($Rs=$DB->fetch_array($Query)) {
	$type_array[$i]['type_id'] = $Rs['type_id'];
	$type_array[$i]['type'] = $Rs['type'];
	$i++;
}
$tpl->assign("type_array",          $type_array); 
$tpl->assign("Title",          $Title);         //标题
$tpl->assign("Content",        $Content);       //新闻内容
$tpl->assign("sid",        time()); 

$tpl->assign($Contact_Pack);

$tpl->display("cooperation.html");
?>
