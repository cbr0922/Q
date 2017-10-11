<?php
//MyOrder.php
$Order_Pack[OrderSerial_say]        = "Order Code";
$Order_Pack[Items_xj_say]           = "小計";
$Order_Pack[OrderCreatetime_say]        = "Order date";

$Order_Pack[OrderState_say]        = "Order status";
$Order_Pack[OrderTotalPrice_say]        = "Order total amount";
$Order_Pack[OrderTotalNum_say]           = "Total order";
$Order_Pack[OrderOperate]        = "Status/Edit";
$Order_Pack[TalkHistory_say]        = "Order Q&A";
$Order_Pack[CancelOrder]        = "Cancel order";


//function ->OrderStateName
$Order_Pack[OrderState_say_one]        = "Unverified";
$Order_Pack[OrderState_say_two]        = "Verified";
$Order_Pack[OrderState_say_three]        = "Some delivered";
$Order_Pack[OrderState_say_four]        = "Delivered";
$Order_Pack[OrderState_say_five]        = "Saved";
$Order_Pack[OrderState_say_six]        = "Canceled";




$Order_Pack[PayMoney]        = "Payed";
$Order_Pack[SendProduct]     = "Sent products";
$Order_Pack[BackDoc]         = "Save";


//function ->OrderPayStateName
$Order_Pack[OrderPayState_say]            = "Payment status";
$Order_Pack[OrderPayState_say_one]        = "Not yet pay";
$Order_Pack[OrderPayState_say_two]        = "Payed";
$Order_Pack[OrderPayState_say_three]        = "Payment failed";
$Order_Pack[OrderPayState_say_four]        = "Payment pending";


//ViewAnser.php

$Order_Pack[Member_say]        = "Member messages";
$Order_Pack[System_say]        = "Auto replay";

//CancelOrder.php
$Order_Pack[NoTheOrder]        = "不存在的訂單!";
$Order_Pack[BadOrderStatu]        = "Invalid order status!";
$Order_Pack[TheOrderHadCancel]        = "Order canceled!";
$Order_Pack[The_Order_CancelInformation_Had_SendTo_System]        = "Order cancel notice already sent to system.";
$Order_Pack[Cancel_why]        = "Please insert order canceled reason!";
$Order_Pack[Saytoolang]        = "Content to long!";
$Order_Pack[ShuoMIng]        = "Instruction";
$Order_Pack[Submit_why]        = "Please insert order canceled reason";
$Order_Pack[Con]               = "Content";

//Collection_list.php
$Order_Pack[MyCollection_say]        = "My traded order";
$Order_Pack[Saytoolang]        = "Content is too long!";



//admin_order_print.php
$Order_Pack[Buyer_info]            = "Buyer info";
$Order_Pack[OrderInfo_say]            = "Order info";
$Order_Pack[AlsendProductNum]        = "Delivered products number";
$Order_Pack[NeedsendProductNum]        = "Post-deliver products number";
$Order_Pack[Order_DownPrint]        = "Download WORD file";

 
 //admin_order.php
$Order_Pack[Order_Js_one]  =  "是否 cancel訂單操作?  \\n\\n操作物件要求:只有當訂單Status爲<未Confirm>或<Confirm>並且<未交付>的時候。\\n\\n本次操作對已經歸檔的資料無效！ ";
$Order_Pack[Order_Js_two]  = "是否操作到款? \\n\\n操作物件要求:當訂單Status不爲<已 cancel>及不爲<未Confirm>的時候。\\n\\n本次操作對已經歸檔的資料無效！ ";
$Order_Pack[Order_Js_three]  = "當訂單為未 cancel或已Submit，且為已付款時";
$Order_Pack[Order_Js_four] = "Saved?\\n\\n資料將保存入庫，同時本定單全部 content將無法再重新編輯！";

$Order_Pack[ReturnOrderState] = "Reactive order";
$Order_Pack[CancelOrder] = "Cancel order";

//admin.order.more.php
$Order_Pack[Send_Date]            = "Delivery date";
$Order_Pack[Order_Form]            = "Delivery Order";


//admin.order.list.php
$Order_Pack[S_dgr]            = "Buyer";
$Order_Pack[S_Szje]            = "＜（total amount）";
$Order_Pack[S_Lzje]            = "＞（total amount）";
$Order_Pack[S_Gzje]            = "＝（total amount）";
$Order_Pack[ShouHuoren]        = "Receiver";
$Order_Pack[MemberCancelOrder] = "Member cancel order";

$Order_Pack[DateError_i] = "First unvalid date format！";
$Order_Pack[DateError_ii] = "Second unvalid date format！";
$Order_Pack[DateError_iii] = "Second date can not be early then first date！";
$Order_Pack[HadSendProductNum]        = "Delivered number";
$Order_Pack[NeedSendProductNum]        = "Post-delivery product number";


//admin_userback.php
$Order_Pack[OrderLy]        = "Order message";
$Order_Pack[OrderHFNR]        = "Reply";

//adminAffirmBonus.php
//$Order_Pack[OrderLy]        = "發問 content";

//MailCreateOrder.class.php
$Order_Pack[MailCContent]        = "View and edit receiver info，varify your personal info to make sure you receive the good on time！"; 
$Order_Pack[Order_ProductInfo]   = "Product's info";


//CancelNomemberOrder.php
$Order_Pack[CancelOrderInfoSendedSystem] = "Order cancel notice sent！";

?>
