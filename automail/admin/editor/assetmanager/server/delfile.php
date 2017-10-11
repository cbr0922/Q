<?php
session_start();
if (!isset($_SESSION['idAdmin']) and !isset($_SESSION['adminName']) )
{
  echo("No authorization.");
  die();
  exit();
}
include_once(dirname(dirname(__FILE__)) . "/config.php");

$root=WEBSITEROOT_LOCALPATH;
$file = $root . $_POST["file"];

if(file_exists ($file)) {
	unlink($file);
} else {

}

?>