<?php

/**
 * 这里是针对台湾在线支付做的程序
 *
 */

//名称
$TwPayOne_Pack[one]           = "信用卡";
$TwPayOne_Pack[two]           = "虛擬帳號轉帳";
$TwPayOne_Pack[three]         = "玉山eCoin";
$TwPayOne_Pack[four]          = "玉山WebATM";
$TwPayOne_Pack[five]          = "網路郵局";
$TwPayOne_Pack[six]           = "全國繳費網WebATM";
$TwPayOne_Pack[serven]        = "全國繳費網活期帳戶";
$TwPayOne_Pack[eight]         = "便利商店";
$TwPayOne_Pack[nine]         = "7-11 ibon";

//说明
$TwPayOne_Pack[one_content]           = "信用卡詳細說明";
$TwPayOne_Pack[two_content]           = "虛擬帳號轉帳詳細說明";
$TwPayOne_Pack[three_content]         = "玉山eCoin詳細說明";
$TwPayOne_Pack[four_content]          = "玉山WebATM詳細說明";
$TwPayOne_Pack[five_content]          = "網路郵局詳細說明";
$TwPayOne_Pack[six_content]           = "全國繳費網WebATM詳細說明";
$TwPayOne_Pack[serven_content]        = "全國繳費網活期帳戶詳細說明";
$TwPayOne_Pack[eight_content]         = "便利商店詳細說明";
$TwPayOne_Pack[nine_content]         = "7-11 ibon詳細說明";

$TwPayTwo_Pack[one]             = "信用卡";
$TwPayTwo_Pack[two]             = "WebATM";
$TwPayTwo_Pack[three]           = "虛擬帳號";
$TwPayTwo_Pack[four]            = "超商代收";
$TwPayTwo_Pack[five]            = "貨到付款";

$TwPayTwo_Pack[one_content]             = "信用卡詳細說明";
$TwPayTwo_Pack[two_content]             = "WebAtm詳細說明";
$TwPayTwo_Pack[three_content]           = "虛擬帳號詳細說明";
$TwPayTwo_Pack[four_content]            = "超商代收詳細說明";
$TwPayTwo_Pack[five_content]            = "貨到付款詳細說明";


$TwPay_Pack[commer_account_number]             = "商家帳號";
$TwPay_Pack[commer_account_passwd]             = "商家密碼";
$TwPay_Pack[TWli]             = "台灣里";



$TwPayThree_Pack[one]             = "信用卡一次付清";
$TwPayThree_Pack[two]             = "中國信託信用卡分期付款(分3期)";
$TwPayThree_Pack[two_2]             = "中國信託信用卡分期付款(分6期)";
$TwPayThree_Pack[three]           = "網路ATM";

$TwPayThree[one] = "zI";
$TwPayThree[two] = "zII";
$TwPayThree[two_2] = "zII_2";
$TwPayThree[three] = "zIII";

$TwPayThree_Pack[one_content]             = "適用VISA、Master、JCB";
$TwPayThree_Pack[two_content]             = "僅適用中國信託信用卡，聯名卡除外";
$TwPayThree_Pack[two_2_content]             = "僅適用中國信託信用卡，聯名卡除外";
$TwPayThree_Pack[three_content]           = "網路ATM";

$TwPayFour_Pack[one]             = "聯合信用卡(分3期)";
$TwPayFour_Pack[two]             = "聯合信用卡(分6期)";
$TwPayFour_Pack[three]             = "聯合信用卡(一次付清)";
$TwPayFour_Pack[one_content]             = "";
$TwPayFour_Pack[two_content]             = "";
$TwPayFour_Pack[three_content]             = "";

$TwPayFour[one]             = "fI";
$TwPayFour[two]             = "fII";
$TwPayFour[three]             = "fIII";

$TwPaySeven[one]             = "gI";
$TwPaySeven[two]             = "gII";
$TwPaySeven[two_2]             = "gII_2";
$TwPaySeven[two_3]             = "gII_3";
$TwPaySeven[three]             = "gIII";
$TwPaySeven[three_2]             = "gIII_2";

$TwPaySeven_Pack[one]             = "WEBATM";
$TwPaySeven_Pack[two]             = "線上刷卡";
$TwPaySeven_Pack[two_2]             = "華南線上刷卡(分3期)";
$TwPaySeven_Pack[two_3]             = "華南線上刷卡(分6期)";
$TwPaySeven_Pack[three]             = "台新虛擬帳號";
$TwPaySeven_Pack[three_2]             = "線上刷卡付款";
$TwPaySeven_Pack[one_content]             = "";
$TwPaySeven_Pack[two_content]             = "";
$TwPaySeven_Pack[two_2_content]             = "";
$TwPaySeven_Pack[two_3_content]             = "";
$TwPaySeven_Pack[three_content]             = "";
$TwPaySeven_Pack[three_2_content]             = "";

/**
 * 这里是针对台湾在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */
function TwPayOne_Function($Input) {
	global $TwPayOne_Pack;
	switch ($Input)
	{
		case "I":
			return  $TwPayOne_Pack[one];
			break;
		case "II":
			return  $TwPayOne_Pack[two];
			break;
		case "III":
			return  $TwPayOne_Pack[three];
			break;
		case "IV":
			return  $TwPayOne_Pack[four];
			break;
		case "V":
			return  $TwPayOne_Pack[five];
			break;
		case "VI":
			return  $TwPayOne_Pack[six];
			break;
		case "VII":
			return  $TwPayOne_Pack[serven];
			break;
		case "VIII":
			return  $TwPayOne_Pack[eight];
			break;
		case "VIIII":
			return  $TwPayOne_Pack[nine];
			break;
		default    :
			return "";
		    break;
	}
}


/**
 * 这里是针对台湾在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */

function TwPayOne_paymentFunction($Input) {
	global $TwPayOne_Pack;
	switch ($Input)
	{
		case "I":
			return  $TwPayOne_Pack[one_content];
			break;
		case "II":
			return  $TwPayOne_Pack[two_content];
			break;
		case "III":
			return  $TwPayOne_Pack[three_content];
			break;
		case "IV":
			return  $TwPayOne_Pack[four_content];
			break;
		case "V":
			return  $TwPayOne_Pack[five_content];
			break;
		case "VI":
			return  $TwPayOne_Pack[six_content];
			break;
		case "VII":
			return  $TwPayOne_Pack[serven_content];
			break;
		case "VIII":
			return  $TwPayOne_Pack[eight_content];
			break;
		case "VIIII":
			return  $TwPayOne_Pack[nine_content];
			break;
		default    :
			return "";
		    break;
	}



}

/**
 * 这里是针对台湾在线支付做的程序
 *
 * @param unknown_type $Input
 * @return unknown 支付类型的数字值
 */
function TwPayOne_paymethod_Function($Input) {
	global $TwPayOne_Pack;
	switch ($Input)
	{
		case "I":
			return  "1";
			break;
		case "II":
			return  "2";
			break;
		case "III":
			return  "3";
			break;
		case "IV":
			return  "4";
			break;
		case "V":
			return  "5";
			break;
		case "VI":
			return  "6";
			break;
		case "VII":
			return  "7";
			break;
		case "VIII":
			return  "8";
			break;
		case "VIIII":
			return  "9";
			break;
		default    :
			return "0";
		    break;
	}
}


/**
 * 这里是针对台湾在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */
function TwPayOne_Function_String($Input) {
	global $TwPayOne_Pack;
	switch ($Input)
	{
		case "I":
			return  "信用卡說明";
			break;
		case "II":
			return  "虛擬帳號轉帳說明";
			break;
		case "III":
			return  "玉山eCoin說明";
			break;
		case "IV":
			return  "玉山WebATM說明";
			break;
		case "V":
			return  "網路郵局說明";
			break;
		case "VI":
			return  "全國繳費網WebATM說明";
			break;
		case "VII":
			return  "全國繳費網活期帳戶說明";
			break;
		case "VIII":
			return  "便利商店說明";
			break;
		default    :
			return "";
		    break;
	}
}

/**
 * 这里是针对台湾在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */
function TwPayTwo_Function($Input) {
	global $TwPayTwo_Pack;
	switch ($Input)
	{
		case "I":
			return  $TwPayTwo_Pack[one];
			break;
		case "II":
			return  $TwPayTwo_Pack[two];
			break;
		case "III":
			return  $TwPayTwo_Pack[three];
			break;
		case "IV":
			return  $TwPayTwo_Pack[four];
			break;
		case "V":
			return  $TwPayTwo_Pack[five];
			break;

		default    :
			return "";
		    break;
	}
}

/**
 * 这里是针对PAYNOW在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */

function TwPayTwo_paymentFunction($Input) {
	global $TwPayTwo_Pack;
	switch ($Input)
	{
		case "I":
			return  $TwPayTwo_Pack[one_content];
			break;
		case "II":
			return  $TwPayTwo_Pack[two_content];
			break;
		case "III":
			return  $TwPayTwo_Pack[three_content];
			break;
		case "IV":
			return  $TwPayTwo_Pack[four_content];
			break;
		case "V":
			return  $TwPayTwo_Pack[five_content];
			break;
		default    :
			return "";
		    break;
	}



}

/**
 * 这里是针对Paynow在线支付做的程序
 *
 * @param unknown_type $Input
 * @return unknown 支付类型的数字值
 */
function TwPayTwo_paymethod_Function($Input) {
	global $TwPayTwo_Pack;
	switch ($Input)
	{
		case "I":
			return  "1";
			break;
		case "II":
			return  "2";
			break;
		case "III":
			return  "3";
			break;
		case "IV":
			return  "4";
			break;
		case "V":
			return  "5";
			break;
		default    :
			return "0";
		    break;
	}
}


/**
 * 这里是针对台湾在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */
function TwPayThree_Function($Input) {
	global $TwPayThree_Pack;
//	print_r( $TwPayThree_Pack);echo $Input;
	switch ($Input)
	{
		case "zI":
			return  $TwPayThree_Pack[one];
			break;
		case "zII":
			return  $TwPayThree_Pack[two];
			break;
		case "zII_2":
			return  $TwPayThree_Pack[two_2];
			break;
		case "zIII":
			return  $TwPayThree_Pack[three];
			break;

		default    :
			return "";
		    break;
	}
}

/**
 * 这里是针对PAYNOW在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */

function TwPayThree_paymentFunction($Input) {
	global $TwPayThree_Pack;
	switch ($Input)
	{
		case "zI":
			return  $TwPayThree_Pack[one_content];
			break;
		case "zII":
			return  $TwPayThree_Pack[two_content];
			break;
		case "zII_2":
			return  $TwPayThree_Pack[two_2_content];
			break;
		case "zIII":
			return  $TwPayThree_Pack[three_content];
			break;
		default    :
			return "";
		    break;
	}



}

/**
 * 这里是针对Paynow在线支付做的程序
 *
 * @param unknown_type $Input
 * @return unknown 支付类型的数字值
 */
function TwPayThree_paymethod_Function($Input) {
	global $TwPayThree_Pack;
	switch ($Input)
	{
		case "zI":
			return  "11";
			break;
		case "zII":
			return  "12";
			break;
		case "zIII":
			return  "13";
			break;
		default    :
			return "0";
		    break;
	}
}

/**
 * 这里是针对台湾在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */
function TwPayFour_Function($Input) {
	global $TwPayFour_Pack;
//	print_r( $TwPayThree_Pack);echo $Input;
	switch ($Input)
	{
		case "fI":
			return  $TwPayFour_Pack[one];
			break;
		case "fII":
			return  $TwPayFour_Pack[two];
			break;
		case "fIII":
			return  $TwPayFour_Pack[three];
			break;
		default    :
			return "";
		    break;
	}
}

/**
 * 这里是针对PAYNOW在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */

function TwPayFour_paymentFunction($Input) {
	global $TwPayFour_Pack;
	switch ($Input)
	{
		case "fI":
			return  $TwPayFour_Pack[one_content];
			break;
		case "fII":
			return  $TwPayFour_Pack[two_content];
			break;
		case "fIII":
			return  $TwPayFour_Pack[three_content];
			break;
		default    :
			return "";
		    break;
	}



}

/**
 * 这里是针对Paynow在线支付做的程序
 *
 * @param unknown_type $Input
 * @return unknown 支付类型的数字值
 */
function TwPayFour_paymethod_Function($Input) {
	global $TwPayFour_Pack;
	switch ($Input)
	{
		case "fI":
			return  "21";
			break;
		case "fII":
			return  "22";
			break;
		case "fIII":
			return  "23";
			break;
		default    :
			return "21";
		    break;
	}
}

function TwPaySeven_Function($Input) {
	global $TwPaySeven_Pack;
//	print_r( $TwPayThree_Pack);echo $Input;
	switch ($Input)
	{
		case "gI":
			return  $TwPaySeven_Pack[one];
			break;
		case "gII":
			return  $TwPaySeven_Pack[two];
			break;
		case "gII_2":
			return  $TwPaySeven_Pack[two_2];
			break;
		case "gII_3":
			return  $TwPaySeven_Pack[two_3];
			break;
		case "gIII":
			return  $TwPaySeven_Pack[three];
			break;
		case "gIII_2":
			return  $TwPaySeven_Pack[three_2];
			break;
		default    :
			return "";
		    break;
	}
}

/**
 * 这里是针对PAYNOW在线支付做的程序
 *
 * @param unknown_type $Input
 * @return string 关于付款方式的中文说明
 */

function TwPaySeven_paymentFunction($Input) {
	global $TwPaySeven_Pack;
	switch ($Input)
	{
		case "gI":
			return  $TwPaySeven_Pack[one_content];
			break;
		case "gII":
			return  $TwPaySeven_Pack[two_content];
			break;
		case "gII_2":
			return  $TwPaySeven_Pack[two_2_content];
			break;
		case "gII_3":
			return  $TwPaySeven_Pack[two_3_content];
			break;
		case "gIII":
			return  $TwPaySeven_Pack[three_content];
			break;
		case "gIII_2":
			return  $TwPaySeven_Pack[three_2_content];
			break;
		default    :
			return "";
		    break;
	}



}

/**
 * 这里是针对Paynow在线支付做的程序
 *
 * @param unknown_type $Input
 * @return unknown 支付类型的数字值
 */
function TwPaySeven_paymethod_Function($Input) {
	global $TwPaySeven_Pack;
	switch ($Input)
	{
		case "gI":
			return  "71";
			break;
		case "gII":
			return  "72";
			break;
		case "gII_2":
			return  "722";
			break;
		case "gII_3":
			return  "723";
			break;
		case "gIII":
			return  "73";
			break;
		case "gIII_2":
			return  "732";
			break;
		default    :
			return "71";
		    break;
	}
}
?>
