<?php
header("Content-Type:application/vnd.ms-excel;charset:<?php echo $groupGlobalCharset;?>");
header("Content-type:application/x-msexcel;charset:<?php echo $groupGlobalCharset;?>");
header("Content-Disposition:attachment; filename=report.xls");
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr bgcolor="#6B78B4">
<td colspan=20><span style="COLOR:#fff; FONT-SIZE:16pt"><?php  echo  $groupName;?>&nbsp;&nbsp; - &nbsp;&nbsp;<?php echo addOffset($today, $pTimeOffsetFromServer, $groupDateTimeFormat)?></span>
</td>
</tr>
</tbody>
</table>
