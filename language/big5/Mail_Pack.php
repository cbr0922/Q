<?php
//後台設置郵件發送部分
$Mail_Pack[MailSetTxt_I]   = "<b>說明:在郵件模版中,設定了郵件內容變數(形如@XXXX@),在發出郵件時,系統會用實際值替換本變數,本範本的變數定義如下:</b>";
$Mail_Pack[MailSetTxt_II]  = "@shopname@ 商店名<br>@username@ 帳號<br>@passwd@ 密碼<br>@truename@ 會員姓名";
$Mail_Pack[MailSetTxt_III] = "@shopname@ 商店名<br>@username@ 帳號<br>@newpass@ 新密碼<br>@site_url@ 商店網址";
$Mail_Pack[MailSetTxt_IV]  = "@shopname@ 商店名<br>@question@ 顧客留言內容<br>@reply@ 店主回覆內容<br>@truename@ 會員姓名";
$Mail_Pack[MailSetTxt_V]   = "@shopname@ 商店名<br>@question@ 顧客評論內容<br>@reply@ 店主回覆內容<br>@truename@ 會員姓名<br>@goodsname@ 評論商品名稱<br>@goodslink@ 評論商品連結<br>@site_url@ 商店網址";
$Mail_Pack[MailSetTxt_VI]  = "@shopname@ 商店名<br>@truename@ 訂購人姓名<br>@CreateHtml@ 訂單表<br>@receiver_name@ 收貨人姓名<br>@orderid@ 訂單號<br>@orderamount@ 訂單總額<br>@receiver_address@ 送貨地址";
$Mail_Pack[MailSetTxt_VII] = "@shopname@ 商店名<br>@truename@ 訂購人姓名<br>@CreateHtml@ 訂單表<br>@receiver_name@ 收貨人姓名<br>@orderid@ 訂單號<br>@receiver_address@ 送貨地址<br>@reply_title@ 回覆資訊<br>@reply_result@ 回覆內容";
$Mail_Pack[MailSetTxt_VIII]= "@shopname@ 商店名<br>@truename@ 訂購人姓名<br>@CreateHtml@ 訂單表<br>@receiver_name@ 收貨人姓名<br>@orderid@ 訂單號<br>@receiver_address@ 送貨地址<br>@ATM@ ATM 轉帳帳號<br>@pay_name@ 付款方式<br>@pay_content@ 付款方式說明<br>@pay_deliver@ 配送方式";
$Mail_Pack[MailSetTxt_IX]  = "@shopname@ 商店名<br>@truename@ 訂購人姓名<br>@CreateHtml@ 訂單表<br>@receiver_name@ 收貨人姓名@orderid@ 訂單號<br>@receiver_address@ 送貨地址<br>@orderinfo@ 訂單內容";

$Mail_Pack[MailType] = "郵件類型";
$Mail_Pack[MailTitle] = "郵件標題";
$Mail_Pack[MailContent] = "郵件內容主體";
$Mail_Pack[PleaseInputMailTitle] = "請輸入郵件標題";

$Mail_Pack[MailSetTitle_I] = "會員註冊";
$Mail_Pack[MailSetTitle_II] = "密碼修改";
$Mail_Pack[MailSetTitle_III] = "會員密碼修復";
$Mail_Pack[MailSetTitle_IV] = "留言回覆";
$Mail_Pack[MailSetTitle_V]= "商品評論回覆";
$Mail_Pack[MailSetTitle_VI] = "前台訂單生成";
$Mail_Pack[MailSetTitle_VII] = "前台訂單付款成功";
$Mail_Pack[MailSetTitle_VIII] = "後台訂單收款";
$Mail_Pack[MailSetTitle_IX] = "後台訂單確認";
$Mail_Pack[MailSetTitle_X] = "後台訂單取消";
$Mail_Pack[MailSetTitle_XI]= "後台訂單出貨";



//admin_mailbasic.php
$Mail_Pack[MailSendMode] = "郵件發送模式";
$Mail_Pack[SMTPServerName] = "SMTP伺服器名稱";
$Mail_Pack[SMTPEmailUName] = "SMTP郵箱帳戶名稱";
$Mail_Pack[SMTPEmailUPass] = "SMTP郵箱帳戶密碼";
$Mail_Pack[SMTPEmail] = "SMTP郵箱";
$Mail_Pack[SMTPServerIfCheck] = "SMTP發信是否需要身份驗證";
$Mail_Pack[SMTPServerPort] = "SMTP伺服器埠";
$Mail_Pack[SMTPCodeMode] = "郵件編碼方式";
$Mail_Pack[SMTPEmailFormat] = "郵件格式";
$Mail_Pack[Utf8Code] = "國際化編碼(utf-8)";
$Mail_Pack[BkEmailCode] = "後台郵件本地編碼";
$Mail_Pack[MdEmailCode] = "目的郵件本地編碼";

$Mail_Pack[WhatIsSMTPServerName] = "如有疑問請詢問郵件服務提供商<br>例:smtp.tom.com";
$Mail_Pack[WhatIsSMTPEmailUName] = "即郵箱登入帳戶名稱";
$Mail_Pack[WhatIsSMTPEmailUPass] = "即郵箱登入的密碼";
$Mail_Pack[WhatIsSMTPServerPort] = "如有疑問請詢問郵件服務提供商<br>慣例使用<b>25</b>";

//admin_mailset.php
$Mail_Pack[ToMailSubjectSendIntro] = "郵件標題：將作為郵件主題發送";


//admin_group_list.php
$Mail_Pack[PleaseInputEmailGroupName] = "請輸入郵件組名稱";
$Mail_Pack[SendEmail] = "發送郵件";
$Mail_Pack[AutoCreateMemberEmailList] = "自動生成會員郵件列表";
$Mail_Pack[EmailGroupName] = "郵件組名稱";
$Mail_Pack[ModiEmailGroup] = "修改郵件組設定";
$Mail_Pack[AddEmailGroup] = "新增郵件組";

$Mail_Pack[BornDate] = "出生日期";
$Mail_Pack[SexIs] = "性別";
$Mail_Pack[Men] = "男";
$Mail_Pack[Women] = "女";
$Mail_Pack[AreaName] = "地區名稱";

$Mail_Pack[SelectMember] = "選擇會員";
$Mail_Pack[CanSelectMember] = "可選會員";
$Mail_Pack[HadSelectMember] = "已選會員";
$Mail_Pack[AllMember] = "全部會員";

$Mail_Pack[PleaseInputEmailBaoName] = "請輸入電子報標題";
$Mail_Pack[PleaseSelectSendEmailGroup] = "請選擇接收郵件組";
$Mail_Pack[EmailBaoName] = "電子報標題";
$Mail_Pack[LastModiTime] = "最後修改日期";
$Mail_Pack[LastSendTime] = "最後發送日期";

$Mail_Pack[ModiEmailBaoName] = "編輯電子報";
$Mail_Pack[AddEmailBaoName] = "新增電子報";
$Mail_Pack[EmailBaoContent] = "電子報內容";

$Mail_Pack[SelectSendEmailGroup] = "選擇電子報接收組";
$Mail_Pack[SendEmailGroup] = "電子報接收組";

$Mail_Pack[SendPer] = 	"發送進度";
$Mail_Pack[SendZhong] = 	"正在發送";
$Mail_Pack[AllOver] = 	"全部完成";
$Mail_Pack[ExportMailList] = 	"導出郵件列表";
$Mail_Pack[ErrorSendMail] = 	"未曾成功發送到的郵箱地址";


//admin_inputmail.php
$Mail_Pack[InputMailList] = 	"導入郵件列表";
$Mail_Pack[InputMailList_Content] = 	"郵件資料檔(CSV逗號分隔格式)";


//admin_group.php

$Mail_Pack[SearchNewMailMember] = ">>搜索符合條件的會員<<";
$Mail_Pack[WhatIsNewEmailGroup] = 	"<b>用途</b>：郵件組的建立，可以便於管理者向指定人群發送郵件資料。<b>使用方法</b>：首先勾選出生日期、性別、地區名稱前的複選框來確定使用該搜索條件，然後選擇搜索的具體條件，便可以得到您所需要的郵件位址資料，並列在下方的可選會員處";
$Mail_Pack[CanSelectEmail] = "可選郵件";
$Mail_Pack[EmailList] = "郵件列表";
$Mail_Pack[SelectedEmail] = "已選郵件";
$Mail_Pack[DingyueEmail] = "訂閱電子報";
$Mail_Pack[TheEmailNum]  = "電子報數量";

?>
