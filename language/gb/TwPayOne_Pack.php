<?

/**
 * 这里是针对台湾在线支付做的程序
 *
 */

/*
//名称
$TwPayOne_Pack[one]           = "信用卡";
$TwPayOne_Pack[two]           = "虛擬帳號轉帳";
$TwPayOne_Pack[three]         = "玉山eCoin";
$TwPayOne_Pack[four]          = "玉山WebATM";
$TwPayOne_Pack[five]          = "網路郵局";
$TwPayOne_Pack[six]           = "全國繳費網WebATM";
$TwPayOne_Pack[serven]        = "全國繳費網活期帳戶";
$TwPayOne_Pack[eight]         = "便利商店";

//说明
$TwPayOne_Pack[one_content]           = "信用卡詳細說明";
$TwPayOne_Pack[two_content]           = "虛擬帳號轉帳詳細說明";
$TwPayOne_Pack[three_content]         = "玉山eCoin詳細說明";
$TwPayOne_Pack[four_content]          = "玉山WebATM詳細說明";
$TwPayOne_Pack[five_content]          = "網路郵局詳細說明";
$TwPayOne_Pack[six_content]           = "全國繳費網WebATM詳細說明";
$TwPayOne_Pack[serven_content]        = "全國繳費網活期帳戶詳細說明";
$TwPayOne_Pack[eight_content]         = "便利商店詳細說明";
*/

$TwPayOne_Pack[one]            = "支付宝";
$TwPayOne_Pack[one_content]           = "支付宝说明";

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
?>
