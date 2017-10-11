<?php

/*  PHP Paypal IPN Integration Class Demonstration File
 *  4.16.2005 - Micah Carrick, email@micahcarrick.com
 *
 *  This file demonstrates the usage of paypal.class.php, a class designed  
 *  to aid in the interfacing between your website, paypal, and the instant
 *  payment notification (IPN) interface.  This single file serves as 4 
 *  virtual pages depending on the "action" varialble passed in the URL. It's
 *  the processing page which processes form data being submitted to paypal, it
 *  is the page paypal returns a user to upon success, it's the page paypal
 *  returns a user to upon canceling an order, and finally, it's the page that
 *  handles the IPN request from Paypal.
 *
 *  I tried to comment this file, aswell as the acutall class file, as well as
 *  I possibly could.  Please email me with questions, comments, and suggestions.
 *  See the header of paypal.class.php for additional resources and information.
*/

// Setup class
require_once('paypal.class.php');  // include the class file
$p = new paypal_class;             // initiate an instance of the class
$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
//$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
            
// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'process';  

switch ($_GET['action']) {
    
   case 'process':      // Process and order...

      // There should be no output at this point.  To process the POST data,
      // the submit_paypal_post() function will output all the HTML tags which
      // contains a FORM which is submited instantaneously using the BODY onload
      // attribute.  In other words, don't echo or printf anything when you're
      // going to be calling the submit_paypal_post() function.
 
      // This is where you would have your form validation  and all that jazz.
      // You would take your POST vars and load them into the class like below,
      // only using the POST values instead of constant string expressions.
 
      // For example, after ensureing all the POST variables from your custom
      // order form are valid, you might have:
      //
      // $p->add_field('first_name', $_POST['first_name']);
      // $p->add_field('last_name', $_POST['last_name']);
      
      $p->add_field('business', 'YOUR PAYPAL (OR SANDBOX) EMAIL ADDRESS HERE!');
      $p->add_field('return', $this_script.'?action=success');
      $p->add_field('cancel_return', $this_script.'?action=cancel');
      $p->add_field('notify_url', $this_script.'?action=ipn');
      $p->add_field('item_name', 'Paypal Test Transaction');
      $p->add_field('amount', '1.99');

      $p->submit_paypal_post(); // submit the fields to paypal
      //$p->dump_fields();      // for debugging, output a table of all the fields
      break;
      
   case 'success':      // Order was successful...
   
      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST 
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.  
 /*
      echo "<html><head><title>Success</title></head><body><h3>Thank you for your order.</h3>";
      foreach ($_POST as $key => $value) { echo "$key: $value<br>"; }
      echo "</body></html>";
	  */
	  $db_string = $DB->compile_db_update_string( array (
			'pay_state'               => 1,
			'onlinepay'               => 2,
			));
			$Sql = "UPDATE `{$INFO[DBPrefix]}order_table` SET $db_string WHERE order_serial='".($_POST['txn_id'])."'";
			$Result = $DB->query($Sql);
			
$Query = $DB->query(" select ot.*,ttime.transtime_id,ttime.transtime_name from `{$INFO[DBPrefix]}order_table` ot left join `{$INFO[DBPrefix]}transtime` ttime on (ot.transtime_id=ttime.transtime_id) where ot.order_serial='".trim($_POST[txn_id])."' ");
$Num       = $DB->num_rows($Query);

if ( $Num <= 0 ){
	$Reprot ="error";
}

$Result    = $DB->fetch_array($Query);

switch (intval($Result['ifinvoice'])){
	case 0:
		$Ifinvoice   = $Cart[Two_piao];
		$Invoiceform = $Basic_Command['Null'];
		$TheOneNum   = $Basic_Command['Null'];
		break;
	case 1:
		$Ifinvoice   =  $Cart[Three_piao];
		$Invoiceform =  trim($Result['invoiceform']);
		$TheOneNum   =  "<font color=red>".trim($Result['invoice_num'])."</font>";

		break;
	case 2:
		$Ifinvoice   = $Cart[DontNeed_piao];
		$Invoiceform = $Basic_Command['Null'];
		$TheOneNum   = $Basic_Command['Null'];
		break;
}

/**
 * 数据变量
 */
$Order_id                             =  $Result['order_id'];
$tpl->assign("TheOneNum",             $TheOneNum); //統一編號
$tpl->assign("order_serial",          $Result['order_serial']);
$tpl->assign("createtime",            date("Y-m-d H:i a ",$Result['createtime']));
$tpl->assign("order_state",           $FUNCTIONS->OrderPayState(intval($Result['pay_state'])));
$tpl->assign("deliveryname",          $Result['paymentname']);
$tpl->assign("Product_totalprice",    trim($Result['totalprice']));
$tpl->assign("transport_price",       trim($Result['transport_price']));
$tpl->assign("paymentname",           trim($Result['deliveryname']));
$tpl->assign("Order_totalprice",      $Result['totalprice']+$Result['transport_price']);
$tpl->assign("invoiceform",           $Invoiceform);
$tpl->assign("atm",                   trim($Result['atm']));
$tpl->assign("receiver_name",         trim($Result['receiver_name']));
$tpl->assign("receiver_mobile",       trim($Result['receiver_mobile']));
$tpl->assign("receiver_email",        trim($Result['receiver_email']));
$tpl->assign("receiver_post",         trim($Result['receiver_post']));
$tpl->assign("receiver_tele",         trim($Result['receiver_tele']));
$tpl->assign("receiver_address",      trim($Result['receiver_address']));
$tpl->assign("receiver_memo",         trim($Result['receiver_memo']));
$tpl->assign("transport_content",     trim($Result['transport_content']));
$tpl->assign("transtime_name",        trim($Result['transtime_name']));
$tpl->assign("paycontent",            trim($Result['paycontent']));
$tpl->assign("Ifinvoice",             $Ifinvoice);
$discount_totalPrices  = $Result['discount_totalPrices'];
$Totalprice   = $Result['totalprice'];
$bonuspoint = $Result[bonuspoint];
$ticketcode = $Result[ticketcode];
if ($discount_totalPrices != $Totalprice){
	//$tickets = "[折價後價格：" . $discount_totalPrices . "]";
	//$tickets2 = "[折價後價格：" . ($discount_totalPrices+$Result[transport_price]) . "]";
	$tpl->assign("tickets",             $tickets);
	$tpl->assign("tickets2",             $tickets2);
}

$tot = $discount_totalPrices+$Result[transport_price];
if ($Result[monthcount] > 0){
	//$monthprice = " 每期價格".(intval($tot/$Result[monthcount]));
}
$tpl->assign("bonuspoint",             $Result[bonuspoint]);
$tpl->assign("monthprice",             $monthprice);
$tpl->assign("tot",             $tot);

//支付方式帐号
$tpl->assign("online_cardnum",             "[".$online_cardnum."]");


$order_detail = array();

$Query_detail = $DB->query(" select gid,goodsname,market_price,price,goodscount from `{$INFO[DBPrefix]}order_detail` where order_id=".intval($Order_id)." order by order_detail_id desc ");
$i = 0 ;
while ($Rs_detail = $DB->fetch_array($Query_detail)){
	$order_detail[$i][gid]          = $Rs_detail[gid];
	$order_detail[$i][goodsname]    = $Rs_detail[goodsname];
	$order_detail[$i][market_price] = $Rs_detail[market_price];
	$order_detail[$i][price]        = $Rs_detail[price];
	$order_detail[$i][goodscount]   = $Rs_detail[goodscount];
	//發送軟體序號
	if ($Reprot !="error"){
		$Sql_code      = "select * from `{$INFO[DBPrefix]}soft_code` where ifuse=0 and gid='" . $Rs_detail[gid] . "' order by code asc limit 0,1";
		$Query_code    = $DB->query($Sql_code);
		$Num_code      = $DB->num_rows($Query_code);
		if ($Num_code>0){
			$Result_code    = $DB->fetch_array($Query_code);
			
			$db_string = $DB->compile_db_update_string( array (
				'soft_code'          => trim($Result_code['code']),
				));
	
				$Sql = "UPDATE `{$INFO[DBPrefix]}order_detail` SET $db_string WHERE order_id='".intval($Order_id)."' and gid='" . $Rs_detail[gid] . "'";
				$DB->query($Sql);
				
				$db_string = $DB->compile_db_update_string( array (
				'ifuse'          => 1,
				));
	
				$Sql = "UPDATE `{$INFO[DBPrefix]}soft_code` SET $db_string WHERE code='".trim($Result_code['code'])."'";
				$DB->query($Sql);
	
	
			$Array =  array("truename"=>trim($_SESSION['true_name']),"orderid"=>trim($_POST['txn_id']),"soft_code"=>trim($Result_code['code']),"goods_name"=>trim($Rs_detail['goodsname']));
					include RootDocumentAdmin."/inc/SMTP.Class.inc.php";
					include RootDocumentShare."/Smtp.config.php";  //装入Smtp基本配置内容
					$SMTP =  new SMSP_smtp($smtpserver,$smtpserverport,$auth,$smtpuser,$smtppass);
					$SMTP->MailForsmartshop(trim($Result['receiver_email']),"",12,$Array);
		}
	}
	$i++;
}
$tpl->assign("order_detail",          $order_detail);
//echo $error;
$tpl->assign("order_error",          $error);


$tpl->assign($Cart);
$tpl->assign($Good);
$tpl->assign($Order_Pack);

$tpl->assign("Reprot",$Reprot);
$tpl->display("receivePay.html");
      
      // You could also simply re-direct them to another page, or your own 
      // order status page which presents the user with the status of their
      // order based on a database (which can be modified with the IPN code 
      // below).
      
      break;
      
   case 'cancel':       // Order was canceled...

      // The order was canceled before being completed.
 
      echo "<html><head><title>Canceled</title></head><body><h3>The order was canceled.</h3>";
      echo "</body></html>";
      
      break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
   
      // It's important to remember that paypal calling this script.  There
      // is no output here.  This is where you validate the IPN data and if it's
      // valid, update your database to signify that the user has payed.  If
      // you try and use an echo or printf function here it's not going to do you
      // a bit of good.  This is on the "backend".  That is why, by default, the
      // class logs all IPN data to a text file.
      
      if ($p->validate_ipn()) {
          
         // Payment has been recieved and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.
  
         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.
  
         // For this example, we'll just email ourselves ALL the data.
         $subject = 'Instant Payment Notification - Recieved Payment';
         $to = 'YOUR EMAIL ADDRESS HERE';    //  your email
         $body =  "An instant payment notification was successfully recieved\n";
         $body .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
         $body .= " at ".date('g:i A')."\n\nDetails:\n";
         
         foreach ($p->ipn_data as $key => $value) { $body .= "\n$key: $value"; }
         mail($to, $subject, $body);
      }
      break;
 }     

?>