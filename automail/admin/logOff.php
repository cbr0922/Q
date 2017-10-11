<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

session_start();
$_SESSION = array();

if (isset($_COOKIE[session_name()])) {      //same as session_id()
    //echo 'it is';
    //die;
    setcookie(session_name(), '', time()-42000, '/');
}

session_destroy();
header("Location: index.php");
?>