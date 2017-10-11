<?php 
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include_once "Check_Admin.php";

$Creatfile= RootDocumentShare."/Smtp.config";



$file_string = "";
$file_string .= "<?php\n\n\n";

$file_string .= " \$smtpserver     = \"".$_POST['smtpserver']."\";\n";
$file_string .= " \$smtpuser       = \"".$_POST['smtpuser']."\";\n";
$file_string .= " \$smtppass       = \"".$_POST['smtppass']."\";\n";
$file_string .= " \$smtpusermail   = \"".$_POST['smtpusermail']."\";\n";
$file_string .= " \$smtpserverport = \"".$_POST['smtpserverport']."\";\n";
$file_string .= " \$auth           = ".$_POST['auth'].";\n";
$file_string .= " \$mailtype       = \"".$_POST['mailtype']."\";\n";
$file_string .= " \$sel_mail_enc   = \"".$_POST['sel_mail_enc']."\";\n";
$file_string .= " \$sel_mail_lang  = \"".$_POST['sel_mail_lang']."\";\n";
$file_string .= " \$mail_type      = \"".$_POST['mail_type']."\";\n";
$file_string .= " \$ssl      = \"".$_POST['ssl']."\";\n";
$file_string .= " \$sel_mail_tar_lang      = \"".$_POST['sel_mail_tar_lang']."\";\n";


$file_string .= "\n\n\n\n?>";

if ($_POST['Action']=='Modi'){
	$FUNCTIONS->setLog("ÐÞ¸Äà]¼þÔO¶¨");

	if ( $fh = fopen( $Creatfile.'.php', 'wb' ) )
	{
		fputs ($fh, $file_string, strlen($file_string) );
		fclose($fh);
		@chmod ($Creatfile,0777);
	}
	$FUNCTIONS->sorry_back("admin_modi_ok.php","");
}
$FUNCTIONS->sorry_back("back","");
?> 