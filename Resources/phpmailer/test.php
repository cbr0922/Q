<?php
fsockopen("ssl://imap.gmail.com","993", $errno,  $errstr,  10);
echo "error：".$errno.$errstr;
?>