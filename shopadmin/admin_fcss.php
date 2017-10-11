<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Forum_Pack.php";
$handle  = @fopen("../forum/forum_css.css",rb);
if (!$handle){
	copy("../modules/forum/forum_css.orig.css","../modules/forum/forum_css.css");
}
$String = "";
if ($handle){
	while (!feof ($handle)) {
		$buffer = fgets($handle, 1000);
		$String .= $buffer."";
	}
}
fclose ($handle);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Forum_Man];//论坛管理?>--&gt;<?php echo $JsMenu[Forum_Css_Man];?> </TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<?php  include_once "Order_state.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		document.form1.Action.value="Modi";
		form1.submit();
		
	}
	function resume(){
		document.form1.Action.value="Resume";
		form1.submit();
	}

</SCRIPT>
<div id="contain_out">
  <FORM name=form1 action='' method=post >
  <input type="hidden" name="Action" value="">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Forum_Man];//论坛管理?>--&gt;<?php echo $JsMenu[Forum_Css_Man];?> </SPAN></TD>
                  </TR></TBODY></TABLE>
              
              </TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          
                          <?php if ($Ie_Type != "mozilla") { ?>
                          <?php } else {?> 
                          <TR>
                            <TD align=middle width=79>					  
                              <!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                                <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save']?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->					    </TD>
                            </TR>
                          <?php } ?>			
                          </TBODY></TABLE>
                      
                      </TD>						
                    
                    </TR>
                  
                  
                  </TBODY></TABLE></TD></TR>
          </TBODY>
        </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top height=262>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff >
                      <br>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="19%">&nbsp;</TD>
                            <TD colspan="5" align=right noWrap>&nbsp;</TD></TR>
                          
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="3" align=left noWrap>&nbsp;</TD>
                            <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><input type="radio" name="css_type" value="red"></TD>
                            <TD width="18%" align=left noWrap><?php echo $Forum_Pack[red] ?></TD>
                            <TD width="3%" align=right noWrap ><input type="radio" name="css_type" value="yellow"></TD>
                            <TD width="51%" align=left noWrap><?php echo $Forum_Pack[yellow] ?></TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><input type="radio" name="css_type" value="orange"></TD>
                            <TD align=left noWrap><?php echo $Forum_Pack[orange] ?></TD>
                            <TD align=right noWrap><input type="radio" name="css_type" value="green"></TD>
                            <TD align=left noWrap><?php echo $Forum_Pack[green] ?></TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><input type="radio" name="css_type" value="blue"></TD>
                            <TD align=left noWrap><?php echo $Forum_Pack[blue] ?></TD>
                            <TD align=right noWrap><input type="radio" name="css_type" value="purple"></TD>
                            <TD align=left noWrap><?php echo $Forum_Pack[purple] ?></TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><input type="radio" name="css_type" value="powder"></TD>
                            <TD colspan="3" align=left noWrap><?php echo $Forum_Pack[powder] ?></TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="3" align=left noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><input name="free_check" type="checkbox" id="free_check" value="1"></TD>
                            <TD colspan="3" align=left noWrap><?php echo $Forum_Pack[ZDYcss] ?></TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="3" align=left noWrap>
                              <p>
                                <textarea name="fcss" cols="80" rows="20" class="p9orange"><?php echo $String?></textarea>
                              </p>
                              <p>&nbsp; </p></TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            
                            </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>

<?php

if ( $_POST['Action']=="Modi" ){

	if ($_POST[free_check]==1){
		$String_post = strip_tags($_POST[fcss]);
		$handle  = @fopen("../modules/forum/forum_css.css",wb);
		fputs ($handle, $String_post, strlen($String_post) );
		fclose($handle);
		echo " <script language=javascript> alert('".$Basic_Command['Back_System_Sucuess']."'); location.href='admin_fcss.php'; </script>";
	}else{
		switch (trim($_POST[css_type])){
			case "red" :
				$SOpen = "../modules/forum/forum_red.css";
				break;
			case "orange" :
				$SOpen = "../modules/forum/forum_orange.css";
				break;
			case "blue" :
				$SOpen = "../modules/forum/forum_blue.css";
				break;
			case "powder" :
				$SOpen = "../modules/forum/forum_powder.css";
				break;
			case "yellow" :
				$SOpen = "../modules/forum/forum_yellow.css";
				break;
			case "green" :
				$SOpen = "../modules/forum/forum_green.css";
				break;
			case "purple" :
				$SOpen = "../forum/forum_purple.css";
				break;
			default:
				$SOpen = "../forum/forum_orange.css";
				break;
		}
		@unlink("../modules/forum/forum_css.css");
		@copy($SOpen,"../modules/forum/forum_css.css");
		echo " <script language=javascript> alert('".$Basic_Command['Back_System_Sucuess']."'); location.href='admin_fcss.php'; </script>";

	}

}

if ( $_POST['Action']=="Resume" ){
	unlink("../modules/forum/forum_css.css");
	copy("../modules/forum/forum_css.orig.css","../forum/forum_css.css");
	echo " <script language=javascript> alert('".$Basic_Command['Back_System_Sucuess']."'); location.href='admin_fcss.php'; </script>";
}
?>
