<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<form method="get" name="recordsPerPage" action="">
<?php echo LISTNEWSLETTERSUBSCRIBERS_11; ?>&nbsp;
<select class="select" id="records" name="records" onChange="reloadPage();">
<option value="5"<?php if ($rowsPerPage==5) {echo " selected";}?>>5</option>
<option value="50"<?php if ($rowsPerPage==50) {echo " selected";}?>>50</option>
<option value="100"<?php if ($rowsPerPage==100) {echo " selected";}?>>100</option>
<option value="200"<?php if ($rowsPerPage==200) {echo " selected";}?>>200</option>
<option value="500"<?php if ($rowsPerPage==500) {echo " selected";}?>>500</option>
<option value="1000"<?php if ($rowsPerPage==1000) {echo " selected";}?>>1000</option>
</select> <?php echo LISTNEWSLETTERSUBSCRIBERS_13; ?>
</form>