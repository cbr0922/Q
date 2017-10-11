<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<script type="text/javascript" language="javascript">
function checkEntries() {
	if ($.trim($('#listName').val()).length == 0 ) {
		$('#listName').val('');
		$("#listnameerror").show();
		return false;
	}
}
</script>
<form action="listExec.php" method="post" name="newList[]" id="newList" onsubmit="return checkEntries();">
  <table cellspacing="0" cellpadding="4" border=0>
  <tr>
	  <td><?php echo LISTS_4; ?>:</td>
	  <td><input name="listName" id="listName" type="text" value="" class=fieldbox11 size=60></td>
	  <td valign=top rowspan=5><a href="#" onclick="show_hide_div('addNewListForm','cross1'); hideAllMessages(); return false;"><img alt="" border="0" src="./images/closeRound.gif" title="<?php echo LISTS_27; ?>"></a></td>
  </tr>
  <tr>
	  <td valign=top><?php echo LISTS_5; ?>:</td>
	  <td><TEXTAREA NAME="listDescription" COLS="70" ROWS=5 class=textarea></TEXTAREA></td>
  </tr>
  <tr>
	  <td><?php echo LISTS_24; ?>:</td>
	  <td><input name="isPublic" type="checkbox" value="-1"></td>
  </tr>
  <tr>
	  <td colspan=2 align=center><input type="submit" name="Submit" class="submit" value=" <?php echo LISTS_33; ?> "></td>
  </tr>
  <tr>
	  <td colspan=2 align=center><span id="listnameerror" class="errormessage" style="display:none;"><img alt="" src="./images/warning.png">&nbsp;<?php echo fixJSstring(LISTS_29)?></span></td>
  </tr>

  </table>
 </form>
