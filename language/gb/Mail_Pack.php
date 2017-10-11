<?php
//后台设置邮件发送部分
$Mail_Pack[MailSetTxt_I]   = "<b>说明:在邮件模版中,设定了邮件内容变量(形如@XXXX@),在发出邮件时,系统会用实际值替换本变量,本模板的变量定义如下:</b>";
$Mail_Pack[MailSetTxt_II]  = "@shopname@ 商店名<br>@username@ 账号<br>@passwd@ 密码<br>@truename@ 会员姓名";
$Mail_Pack[MailSetTxt_III] = "@shopname@ 商店名<br>@username@ 账号<br>@newpass@ 新密码<br>@site_url@ 商店网络地址";
$Mail_Pack[MailSetTxt_IV]  = "@shopname@ 商店名<br>@question@ 顾客留言内容<br>@reply@ 店主回复内容<br>@truename@ 会员姓名";
$Mail_Pack[MailSetTxt_V]   = "@shopname@ 商店名<br>@question@ 顾客评论内容<br>@reply@ 店主回复内容<br>@truename@ 会员姓名<br>@goodsname@ 评论商品名称<br>@goodslink@ 评论商品连结<br>@site_url@ 商店网络地址";
$Mail_Pack[MailSetTxt_VI]  = "@shopname@ 商店名<br>@truename@ 订购人姓名<br>@CreateHtml@ 订单表<br>@receiver_name@ 收货人姓名<br>@orderid@ 订单号<br>@orderamount@ 订单总额<br>@receiver_address@ 送货地址";
$Mail_Pack[MailSetTxt_VII] = "@shopname@ 商店名<br>@truename@ 订购人姓名<br>@CreateHtml@ 订单表<br>@receiver_name@ 收货人姓名<br>@orderid@ 订单号<br>@receiver_address@ 送货地址<br>@reply_title@ 回复信息<br>@reply_result@ 回复内容";
$Mail_Pack[MailSetTxt_VIII]= "@shopname@ 商店名<br>@truename@ 订购人姓名<br>@CreateHtml@ 订单表<br>@receiver_name@ 收货人姓名<br>@orderid@ 订单号<br>@receiver_address@ 送货地址<br>@ATM@ ATM 转帐账号<br>@pay_name@ 付款方式<br>@pay_content@ 付款方式说明<br>@pay_deliver@ 配送方式";
$Mail_Pack[MailSetTxt_IX]  = "@shopname@ 商店名<br>@truename@ 订购人姓名<br>@CreateHtml@ 订单表<br>@receiver_name@ 收货人姓名@orderid@ 订单号<br>@receiver_address@ 送货地址<br>@orderinfo@ 订单内容";

$Mail_Pack[MailType] = "邮件类型";
$Mail_Pack[MailTitle] = "邮件标题";
$Mail_Pack[MailContent] = "邮件内容主体";
$Mail_Pack[PleaseInputMailTitle] = "请输入邮件标题";

$Mail_Pack[MailSetTitle_I] = "会员注册";
$Mail_Pack[MailSetTitle_II] = "密码修改";
$Mail_Pack[MailSetTitle_III] = "会员密码修复";
$Mail_Pack[MailSetTitle_IV] = "留言回复";
$Mail_Pack[MailSetTitle_V]= "商品评论回复";
$Mail_Pack[MailSetTitle_VI] = "前台订单生成";
$Mail_Pack[MailSetTitle_VII] = "前台订单付款成功";
$Mail_Pack[MailSetTitle_VIII] = "后台订单收款";
$Mail_Pack[MailSetTitle_IX] = "后台订单确认";
$Mail_Pack[MailSetTitle_X] = "后台订单取消";
$Mail_Pack[MailSetTitle_XI]= "后台订单出货";



//admin_mailbasic.php
$Mail_Pack[MailSendMode] = "邮件发送模式";
$Mail_Pack[SMTPServerName] = "SMTP服务器名称";
$Mail_Pack[SMTPEmailUName] = "SMTP邮箱账户名称";
$Mail_Pack[SMTPEmailUPass] = "SMTP邮箱账户密码";
$Mail_Pack[SMTPEmail] = "SMTP邮箱";
$Mail_Pack[SMTPServerIfCheck] = "SMTP发信是否需要身份验证";
$Mail_Pack[SMTPServerPort] = "SMTP服务器埠";
$Mail_Pack[SMTPCodeMode] = "邮件编码方式";
$Mail_Pack[SMTPEmailFormat] = "邮件格式";
$Mail_Pack[Utf8Code] = "国际化编码(utf-8)";
$Mail_Pack[BkEmailCode] = "后台邮件本地编码";
$Mail_Pack[MdEmailCode] = "目的邮件本地编码";

$Mail_Pack[WhatIsSMTPServerName] = "如有疑问请询问邮件服务提供商<br>例:smtp.tom.com";
$Mail_Pack[WhatIsSMTPEmailUName] = "即邮箱登入账户名称";
$Mail_Pack[WhatIsSMTPEmailUPass] = "即邮箱登入的密码";
$Mail_Pack[WhatIsSMTPServerPort] = "如有疑问请询问邮件服务提供商<br>惯例使用<b>25</b>";

//admin_mailset.php
$Mail_Pack[ToMailSubjectSendIntro] = "邮件标题：将作为邮件主题发送";


//admin_group_list.php
$Mail_Pack[PleaseInputEmailGroupName] = "请输入邮件组名称";
$Mail_Pack[SendEmail] = "发送邮件";
$Mail_Pack[AutoCreateMemberEmailList] = "自动生成会员邮件列表";
$Mail_Pack[EmailGroupName] = "邮件组名称";
$Mail_Pack[ModiEmailGroup] = "修改邮件组设定";
$Mail_Pack[AddEmailGroup] = "新增邮件组";

$Mail_Pack[BornDate] = "出生日期";
$Mail_Pack[SexIs] = "性别";
$Mail_Pack[Men] = "男";
$Mail_Pack[Women] = "女";
$Mail_Pack[AreaName] = "地区名称";

$Mail_Pack[SelectMember] = "选择会员";
$Mail_Pack[CanSelectMember] = "可选会员";
$Mail_Pack[HadSelectMember] = "已选会员";
$Mail_Pack[AllMember] = "全部会员";

$Mail_Pack[PleaseInputEmailBaoName] = "请输入电子报标题";
$Mail_Pack[PleaseSelectSendEmailGroup] = "请选择接收邮件组";
$Mail_Pack[EmailBaoName] = "电子报标题";
$Mail_Pack[LastModiTime] = "最后修改日期";
$Mail_Pack[LastSendTime] = "最后发送日期";

$Mail_Pack[ModiEmailBaoName] = "编辑电子报";
$Mail_Pack[AddEmailBaoName] = "新增电子报";
$Mail_Pack[EmailBaoContent] = "电子报内容";

$Mail_Pack[SelectSendEmailGroup] = "选择电子报接收组";
$Mail_Pack[SendEmailGroup] = "电子报接收组";

$Mail_Pack[SendPer] = 	"发送进度";
$Mail_Pack[SendZhong] = 	"正在发送";
$Mail_Pack[AllOver] = 	"全部完成";
$Mail_Pack[ExportMailList] = 	"导出邮件列表";
$Mail_Pack[ErrorSendMail] = 	"未曾成功发送到的邮箱地址";


//admin_inputmail.php
$Mail_Pack[InputMailList] = 	"导入邮件列表";
$Mail_Pack[InputMailList_Content] = 	"邮件数据文件(CSV逗号分隔格式)";


//admin_group.php

$Mail_Pack[SearchNewMailMember] = ">>搜索符合条件的会员<<";
$Mail_Pack[WhatIsNewEmailGroup] = 	"<b>用途</b>：邮件组的建立，可以便于管理者向指定人群发送邮件数据。<b>使用方法</b>：首先勾选出生日期、性别、地区名称前的复选框来确定使用该搜索条件，然后选择搜索的具体条件，便可以得到您所需要的邮件地址数据，并列在下方的可选会员处";

$Mail_Pack[CanSelectEmail] = "可选邮件";
$Mail_Pack[EmailList] = "邮件列表";
$Mail_Pack[SelectedEmail] = "已选邮件";
$Mail_Pack[DingyueEmail] = "订阅电子报";

?>
