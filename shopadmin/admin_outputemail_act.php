<?php
@set_time_limit(1200);
@header("Pragma: no-cache");
include_once "Check_Admin.php";

if ($_POST['Action']=='Output'){
	$Emailtype = intval($_POST['emailtype'])==0 ? "" : " where user_level=".intval($_POST['emailtype']) ;
	$Query = $DB->query(" select email from `{$INFO[DBPrefix]}user` ".$Emailtype." order by user_id desc " );
	$Email_String = "";
	while ($Rs = $DB->fetch_array($Query)){
		$Email_String .= $Rs['email'].",";
	}
	$Outfile =  RootDocumentShare."/Email.txt";
	$fo = @fopen ($Outfile,"w");
	@fputs($fo,$Email_String);
	@fclose($fo);
	@ob_start();
	@ob_implicit_flush(0);
	@header("Content-Type: text/x-delimtext; name=\"Email.txt\"");
	@header("Content-disposition: attachment; filename=\"Email.txt\"");
	echo $Email_String;
}


?>