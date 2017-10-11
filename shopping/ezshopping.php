<?php
require_once( '../Classes/cart.class.php' );
session_start();
include("../configs.inc.php");
include("global.php");
include_once "../language/".$INFO['IS']."/Cart.php";
include_once "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

require "check_member.php";

$payform = $payFunction->CreatePay(69,$pay_array);
echo $payform;
?>
