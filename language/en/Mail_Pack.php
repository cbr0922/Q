<?php
//後台設置郵件發送部分
$Mail_Pack[MailSetTxt_I]   = "<b>Instruction:In mail template, you can set a variable (Example: @XXXX@),While mail send out the variable will replace with the actual value:</b>";
$Mail_Pack[MailSetTxt_II]  = "@shopname@ Shop name <br>@username@ account<br>@passwd@ Password<br>@truename@ Member name";
$Mail_Pack[MailSetTxt_III] = "@shopname@ Shop name <br>@username@ account<br>@newpass@ New password <br>@site_url@ shop Url";
$Mail_Pack[MailSetTxt_IV]  = "@shopname@ Shop name <br>@question@ Customer message content<br>@reply@ Shop admin reply content<br>@truename@ Member name";
$Mail_Pack[MailSetTxt_V]   = "@shopname@ Shop name <br>@question@ Customer comment content<br>@reply@ Shop admin reply content<br>@truename@ Member name<br>@goodsname@ Recommend Product name<br>@goodslink@ Recommend  product link<br>@site_url@ shop Url";
$Mail_Pack[MailSetTxt_VI]  = "@shopname@ Shop name <br>@truename@ Receiver name<br>@CreateHtml@ Order list <br>@receiver_name@ Receiver name<br>@orderid@ Order code<br>@orderamount@ Order amount<br>@receiver_address@ Delivery address";
$Mail_Pack[MailSetTxt_VII] = "@shopname@ Shop name <br>@truename@ Receiver name<br>@CreateHtml@ Order list <br>@receiver_name@ Receiver name<br>@orderid@ Order code<br>@receiver_address@ Delivery address<br>@reply_title@ Reply information<br>@reply_result@ Reply content";
$Mail_Pack[MailSetTxt_VIII]= "@shopname@ Shop name <br>@truename@ Receiver name<br>@CreateHtml@ Order list <br>@receiver_name@ Receiver name<br>@orderid@ Order code<br>@receiver_address@ Delivery address<br>@ATM@ ATM Transfer account<br>@pay_name@ Payment method<br>@pay_content@ Payment methodInstruction<br>@pay_deliver@ Delivery method";
$Mail_Pack[MailSetTxt_IX]  = "@shopname@ Shop name <br>@truename@ Receiver name<br>@CreateHtml@ Order list <br>@receiver_name@ Receiver name@orderid@ Order code<br>@receiver_address@ receiver address <br>@orderinfo@ Order content";

$Mail_Pack[MailType] = "Email categories";
$Mail_Pack[MailTitle] = "Email title";
$Mail_Pack[MailContent] = "Email content";
$Mail_Pack[PleaseInputMailTitle] = "Please insert Email title";

$Mail_Pack[MailSetTitle_I] = "MemberRegister";
$Mail_Pack[MailSetTitle_II] = "Edit password";
$Mail_Pack[MailSetTitle_III] = "Recover Password";
$Mail_Pack[MailSetTitle_IV] = "Reply message";
$Mail_Pack[MailSetTitle_V]= " Reply product recommend";
$Mail_Pack[MailSetTitle_VI] = "Front page order generated";
$Mail_Pack[MailSetTitle_VII] = "Front page payment success";
$Mail_Pack[MailSetTitle_VIII] = "Control panel order payment";
$Mail_Pack[MailSetTitle_IX] = "Control panel order submit";
$Mail_Pack[MailSetTitle_X] = "Control panel order cancel";
$Mail_Pack[MailSetTitle_XI]= "Control panel order delivery";



//admin_mailbasic.php
$Mail_Pack[MailSendMode] = "Email send mode";
$Mail_Pack[SMTPServerName] = "SMTP server name";
$Mail_Pack[SMTPEmailUName] = "SMTP server user name";
$Mail_Pack[SMTPEmailUPass] = "SMTP server Password";
$Mail_Pack[SMTPEmail] = "SMTP mail box";
$Mail_Pack[SMTPServerIfCheck] = "Need varify identity before send mail?";
$Mail_Pack[SMTPServerPort] = "SMTP server port";
$Mail_Pack[SMTPCodeMode] = "Email code mode";
$Mail_Pack[SMTPEmailFormat] = "Email format";
$Mail_Pack[Utf8Code] = "Internation code(utf-8)";
$Mail_Pack[BkEmailCode] = "Control panel mail local code";
$Mail_Pack[MdEmailCode] = "Receiver mail local code";

$Mail_Pack[WhatIsSMTPServerName] = "Need help from server supplier<br>Example:smtp.tom.com";
$Mail_Pack[WhatIsSMTPEmailUName] = "Email username";
$Mail_Pack[WhatIsSMTPEmailUPass] = "Email Password";
$Mail_Pack[WhatIsSMTPServerPort] = "Any question, you can get support from your server supplier<br>For normal case is <b>25</b>";

//admin_mailset.php
$Mail_Pack[ToMailSubjectSendIntro] = "Email title：將作為郵件Subject發送";


//admin_group_list.php
$Mail_Pack[PleaseInputEmailGroupName] = "Insert Email group name";
$Mail_Pack[SendEmail] = "Send Email";
$Mail_Pack[AutoCreateMemberEmailList] = "Auto generate member Email list";
$Mail_Pack[EmailGroupName] = "Email group name";
$Mail_Pack[ModiEmailGroup] = "Edit Email group setting";
$Mail_Pack[AddEmailGroup] = "Add Email group";

$Mail_Pack[BornDate] = "Birth date";
$Mail_Pack[SexIs] = "Sex";
$Mail_Pack[Men] = "Male";
$Mail_Pack[Women] = "Female";
$Mail_Pack[AreaName] = "Location";

$Mail_Pack[SelectMember] = "Select member";
$Mail_Pack[CanSelectMember] = "可選Member";
$Mail_Pack[HadSelectMember] = "Selected member";
$Mail_Pack[AllMember] = "All member";

$Mail_Pack[PleaseInputEmailBaoName] = "Insert E-paper title";
$Mail_Pack[PleaseSelectSendEmailGroup] = "Select receiver group";
$Mail_Pack[EmailBaoName] = "E-paper title";
$Mail_Pack[LastModiTime] = "Last edit date";
$Mail_Pack[LastSendTime] = "Last sent date";

$Mail_Pack[ModiEmailBaoName] = "Edit E-paper";
$Mail_Pack[AddEmailBaoName] = "Add E-papaer";
$Mail_Pack[EmailBaoContent] = "E-paper content";

$Mail_Pack[SelectSendEmailGroup] = "Select E-paper receiver group";
$Mail_Pack[SendEmailGroup] = "E-paper receiver group";

$Mail_Pack[SendPer] = 	"Send progress";
$Mail_Pack[SendZhong] = 	"Sending";
$Mail_Pack[AllOver] = 	"Complete";
$Mail_Pack[ExportMailList] = 	"Export Email list";
$Mail_Pack[ErrorSendMail] = 	"Failed Email address";


//admin_inputmail.php
$Mail_Pack[InputMailList] = 	"Import Email List";
$Mail_Pack[InputMailList_Content] = 	"Email info file (CSV format)";


//admin_group.php

$Mail_Pack[SearchNewMailMember] = ">>Valid member<<";
$Mail_Pack[WhatIsNewEmailGroup] = 	"<b>Purpose</b>：Email group created to mail group of user in one time。<b>Instruction</b>：First select birth date、sex、location as search request，then, choose you select subject，for get you requested email address.";
$Mail_Pack[CanSelectEmail] = "Optional Email";
$Mail_Pack[EmailList] = "Email List";
$Mail_Pack[SelectedEmail] = "Selected Email";
$Mail_Pack[DingyueEmail] = "Sign up E-paper";
$Mail_Pack[TheEmailNum]  = "Number of E-paper";

?>
