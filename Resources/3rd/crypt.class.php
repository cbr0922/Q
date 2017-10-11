<?php
header ( "Content-Type: text/html; charset=utf-8" );

/**
* Copyright (c) 2011 - 01 XatuDream
* XatuDream All Rights Reserved.
* Support:185390516.qzone.qq.com
* QQ:185390516
* Author:Lau Version:1.01
* Date:2010-08-12 09:28:32
*/
//! defined ( 'WORKSPACE' ) && exit ( "Access Denied !" );
class MD5Crypt {
/**
* Enter description here ...
* @param unknown_type $str
* @return string
*/
public final static function mdsha($str) {
$code = substr ( md5 ( $str ), 10 );
$code .= substr ( sha1 ( $str ), 0, 28 );
$code .= substr ( md5 ( $str ), 0, 22 );
$code .= substr ( sha1 ( $str ), 16 ) . md5 ( $str );
return self::chkToken () ? $code : null;
}
/**
* Enter description here ...
* @param unknown_type $param
*/
private final static function chkToken() {
return true;
}
/**
* Enter description here ...
* @param unknown_type $txt
* @param unknown_type $encrypt_key
* @return Ambigous <string, boolean>
*/
private final static function keyED($txt, $encrypt_key) {
$encrypt_key = md5 ( $encrypt_key );
$ctr = 0;
$tmp = "";
for($i = 0; $i < strlen ( $txt ); $i ++) {
if ($ctr == strlen ( $encrypt_key ))
$ctr = 0;
$tmp .= substr ( $txt, $i, 1 ) ^ substr ( $encrypt_key, $ctr, 1 );
$ctr ++;
}
return $tmp;
}
/**
* Enter description here ...
* @param unknown_type $txt
* @param unknown_type $key
* @return string
*/
public final static function Encrypt($txt, $key) {
srand ( ( double ) microtime () * 1000000 );
$encrypt_key = md5 ( rand ( 0, 32000 ) );
$ctr = 0;
$tmp = "";
for($i = 0; $i < strlen ( $txt ); $i ++) {
if ($ctr == strlen ( $encrypt_key ))
$ctr = 0;
$tmp .= substr ( $encrypt_key, $ctr, 1 ) . (substr ( $txt, $i, 1 ) ^ substr ( $encrypt_key, $ctr, 1 ));
$ctr ++;
}
$_code = md5 ( $encrypt_key ) . base64_encode ( self::keyED ( $tmp, $key ) ) . md5 ( $encrypt_key . $key );
return self::chkToken () ? $_code : null;
}
/**
* Enter description here ...
* @param unknown_type $txt
* @param unknown_type $key
* @return Ambigous <string, boolean>
*/
public final static function Decrypt($txt, $key) {
$txt = self::keyED ( base64_decode ( substr ( $txt, 32, - 32 ) ), $key );
$tmp = "";
for($i = 0; $i < strlen ( $txt ); $i ++) {
$md5 = substr ( $txt, $i, 1 );
$i ++;
$tmp .= (substr ( $txt, $i, 1 ) ^ $md5);
}
return self::chkToken () ? $tmp : null;
}
/**
* Enter description here ...
* @var unknown_type
*/
private static $_key = 'lau';
}
?>