<?php
include_once "Check_Admin.php";

$order_FILE_NAME =  trim($_GET[Order_serial])."_order.doc";
$downFile=@fopen(RootDocumentShare."/order_doc/".$order_FILE_NAME , "rb");
@ob_start();
@ob_implicit_flush(0);
@header("Content-type: text/html; charset=utf-8");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ".filesize(RootDocumentShare."/order_doc/".$order_FILE_NAME));
Header("Content-Disposition: attachment; filename=".$order_FILE_NAME);
echo fread($downFile,filesize(RootDocumentShare."/order_doc/".$order_FILE_NAME));
echo fread($downFile,filesize(RootDocumentShare."/order_doc/".$order_FILE_NAME));
fclose($downFile);
@ob_end_flush();
echo "<script language=javascript>window.close();</script>";
exit;
?>