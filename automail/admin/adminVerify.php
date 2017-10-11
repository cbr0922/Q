<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
session_start();
if (!isset($_SESSION['idAdmin']) OR !isset($_SESSION['adminName']) OR !isset($_SESSION['idGroupL']))
{
  header("Location: index.php");
  exit();
}
else {
@$sesIDAdmin = $_SESSION['idAdmin'];
}
?>