<?php
//MyOrder.php
$Order_Pack[OrderSerial_say]        = "订单编号";
$Order_Pack[Items_xj_say]           = "小计";
$Order_Pack[OrderCreatetime_say]        = "下单日期";

$Order_Pack[OrderState_say]        = "订单状态";
$Order_Pack[OrderTotalPrice_say]        = "订单总金额";
$Order_Pack[OrderTotalNum_say]           = "订单总笔数";
$Order_Pack[OrderOperate]        = "状态/操作";
$Order_Pack[TalkHistory_say]        = "订单问答";
$Order_Pack[CancelOrder]        = "订单取消";


//function ->OrderStateName
$Order_Pack[OrderState_say_one]        = "未确认";
$Order_Pack[OrderState_say_two]        = "已确认";
$Order_Pack[OrderState_say_three]        = "部分发货";
$Order_Pack[OrderState_say_four]        = "已发货";
$Order_Pack[OrderState_say_five]        = "已归档";
$Order_Pack[OrderState_say_six]        = "已取消";




$Order_Pack[PayMoney]        = "到款";
$Order_Pack[SendProduct]     = "发货";
$Order_Pack[BackDoc]         = "归档";


//function ->OrderPayStateName
$Order_Pack[OrderPayState_say]            = "支付状态";
$Order_Pack[OrderPayState_say_one]        = "未付款";
$Order_Pack[OrderPayState_say_two]        = "已付款";
$Order_Pack[OrderPayState_say_three]        = "支付失败";
$Order_Pack[OrderPayState_say_four]        = "等待付款";


//ViewAnser.php

$Order_Pack[Member_say]        = "会员留言";
$Order_Pack[System_say]        = "系统回复";

//CancelOrder.php
$Order_Pack[NoTheOrder]        = "不存在的订单!";
$Order_Pack[BadOrderStatu]        = "订单状态不正确!";
$Order_Pack[TheOrderHadCancel]        = "本订单已取消!";
$Order_Pack[The_Order_CancelInformation_Had_SendTo_System]        = "订单取消信息已发送给系统.";
$Order_Pack[Cancel_why]        = "请您提交取消定单的理由!";
$Order_Pack[Saytoolang]        = "你提交的内容过长!";
$Order_Pack[ShuoMIng]        = "说明";
$Order_Pack[Submit_why]        = "请您提交取消定单的理由";
$Order_Pack[Con]               = "内容";

//Collection_list.php
$Order_Pack[MyCollection_say]        = "我的追踪清单";
$Order_Pack[Saytoolang]        = "你提交的内容过长!";



//admin_order_print.php
$Order_Pack[Buyer_info]            = "订购人信息";
$Order_Pack[OrderInfo_say]            = "订单信息";
$Order_Pack[AlsendProductNum]        = "已发货数量";
$Order_Pack[NeedsendProductNum]        = "需发货数量";
$Order_Pack[Order_DownPrint]        = "下载WORD檔";

 
 //admin_order.php
$Order_Pack[Order_Js_one]  =  "是否取消订单操作?  \\n\\n操作对象要求:只有当订单状态为<未确定>或<确定>并且<未交付>的时候。\\n\\n本次操作对已经归档的数据无效！ ";
$Order_Pack[Order_Js_two]  = "是否操作到款? \\n\\n操作对象要求:当订单状态不为<已取消>及不为<未确定>的时候。\\n\\n本次操作对已经归档的数据无效！ ";
$Order_Pack[Order_Js_three]  = "当订单为未取消或已确认，且为已付款时";
$Order_Pack[Order_Js_four] = "是否操作归档?\\n\\n数据将保存入库，同时本定单全部内容将无法再重新编辑！";

$Order_Pack[ReturnOrderState] = "重新启动订单状态";
$Order_Pack[CancelOrder] = "取消订单";

//admin.order.more.php
$Order_Pack[Send_Date]            = "出货日期";
$Order_Pack[Order_Form]            = "出 货 单";


//admin.order.list.php
$Order_Pack[S_dgr]            = "订购人";
$Order_Pack[S_Szje]            = "＜（总金额）";
$Order_Pack[S_Lzje]            = "＞（总金额）";
$Order_Pack[S_Gzje]            = "＝（总金额）";
$Order_Pack[ShouHuoren]        = "收货人";
$Order_Pack[MemberCancelOrder] = "会员取消订单";

$Order_Pack[DateError_i] = "第一个发布日期是不正确的日期格式！";
$Order_Pack[DateError_ii] = "第二个发布日期是不正确的日期格式！";
$Order_Pack[DateError_iii] = "第二个发布日期不能早于第一个发布日期！";
$Order_Pack[HadSendProductNum]        = "已出货数量";
$Order_Pack[NeedSendProductNum]        = "需出货数量";


//admin_userback.php
$Order_Pack[OrderLy]        = "订单留言";
$Order_Pack[OrderHFNR]        = "回复内容";

//adminAffirmBonus.php
//$Order_Pack[OrderLy]        = "发问内容";

//MailCreateOrder.class.php
$Order_Pack[MailCContent]        = "查看及修改我的收货人信息，准确的个人信息可以确保您及时收到商品！"; 
$Order_Pack[Order_ProductInfo]   = "商品信息";


//CancelNomemberOrder.php
$Order_Pack[CancelOrderInfoSendedSystem] = "订单取消信息已发送给系统！";

?>
