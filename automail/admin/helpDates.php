<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/languages.php');
$datefield = $_REQUEST['datefield'];
?>

<html>
<head>
<title><?php echo HELPDATES_1.' - nuevoMailer';?></title>
<link href="./includes/site.css" rel=stylesheet type=text/css>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $obj->getSetting("groupGlobalCharset", $idGroup); ?>">

<script language="JavaScript">
function enterDate() {
var datefield = '<?php echo $datefield?>';
 var t = '';
for (i=0;i < document.dateOptions.elements.length; i++){
  if (document.dateOptions.elements[i].checked) {
   t += (t == '' ? '' : ',') + document.dateOptions.selectedDate[i].value;
  }
 }
 window.opener.document.getElementById(datefield).value = t;
}
</script>
</head>
<style>
body {
	background: #ffffff;
	margin:10px;
}
</style>
<body>
<span class="title"><?php echo HELPDATES_1?></span><br>
<?php echo $datefield;?>
<br><br><br>
	<form action="" method="post" name="dateOptions" id="dateOptions">
	<b><?php //echo pdatabase;?></b>
	<br>
	<input type="radio" name="selectedDate" onClick="enterDate()" value=">'2005-08-11'"><font class="dbfield">>'2005-08-11'</font>&nbsp;&nbsp;(after and incl. Aug 11th)</input><br>
	<input type="radio" name="selectedDate" onClick="enterDate()" value="<'2005-12-01'"><font class="dbfield"><'2005-12-01'</font>&nbsp;&nbsp;(before and excl. Dec 1st)</input><br>
	<input type="radio" name="selectedDate" onClick="enterDate()" value="BETWEEN '2005-10-20' AND '2005-10-23'"><font class="dbfield">BETWEEN '2005-10-20' AND '2005-10-23'</font>&nbsp;&nbsp;(from Oct 20th until Oct 22nd incl.)</input><br>
	<input type="radio" name="selectedDate" onClick="enterDate()" value="BETWEEN '2005-11-11' AND '2005-11-12'"><font class="dbfield">BETWEEN '2005-11-11' AND '2005-11-12'</font>&nbsp;&nbsp;(only on Nov 11th)</input><br><br>
	</form>
  <p align=center><input class=submit type = 'button' name = 'button' value = '<?php echo HELPDATES_2;?>' onclick = "window.close()"></p>

</body>
</html>