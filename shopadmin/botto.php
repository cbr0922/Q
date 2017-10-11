<?php require_once "../Classes/version.php"; ?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
		<tr>
          <TD align=center>
		  <span class="p9black">
<br />
Copyright © 2004-<?php echo date("Y",time());?> SmartShop, Powered by <a href="http://www.SmartShop.com.tw"  target="_blank"> <font class="p9orange"><b>SmartShop Team   Version<?php echo $_VERSION->RELEASE;?></b></font></a> , All Rights Reserved </span><span><BR>
          <BR>	      </TD>
		 </TR>
  </TBODY>
</TABLE>
<script>
function do_report_bug(){
	url = encodeURIComponent( window.location.href );
	url = "../UnitTest/index.php?url=" + url;
	window.parent.parent.location.href = url;
}
</script>
