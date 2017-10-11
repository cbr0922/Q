<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<html>
<head>
<title>My Links</title>
<script>
function insertLink(url,title,target)
  {
    var oName=window.opener.oUtil.oName;
    eval("window.opener."+oName).insertLink(url,title,target);
  }

</script>
<style>
BODY {background: #fff;font-family:Verdana, Arial, Helvetica;font-size: x-small;margin:3 3 3 3;}
</style>
</head>
<body>
<h2>My Internal Links</h2>

<ul>
<li>Page1.htm <span onclick="insertLink('Page1.htm','title 1 here')" style="text-decoration:underline;cursor:pointer;color:blue;">Insert Link</span>
<br>
Using: insertLink("Page1.htm","title 1 here")<br><br>
</li>
<li>Page2.htm <span onclick="insertLink('Page2.htm','title 2 here','_blank')" style="text-decoration:underline;cursor:pointer;color:blue;">Insert Link</span>
<br>
Using: insertLink("Page2.htm","title 2 here","_blank")<br><br>
</li>
<li>Page3.htm <span onclick="insertLink('Page3.htm')" style="text-decoration:underline;cursor:pointer;color:blue;">Insert Link</span>
<br>
Using: insertLink("Page3.htm")<br><br>
</li>
</ul>
<br>
<font size=1>In this page you can add and quickly insert internal (to your website) links that you use regularly.
<br>Open this page to add such links.</font>
</body>
</html>
