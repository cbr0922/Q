<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_GET['id']!="" && $_GET['Action']=='Modi'){
	$id = intval($_GET['id']);
	$Action_value = "Update";
	$Action_say  = "修改教學類別" ;//修改問題類別
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}studytype` where id=".intval($id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$name            =  $Result['name'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增教學類別"; ///添加問題類別
	$status  = 1;
}

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;教學資料</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" 
background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center   border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title_kefu.php";?>
	  </TD></TR></TBODY></TABLE>
      <? //include "Order_state.php";?>


  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD>
  </TR>
  </TBODY>
  </TABLE>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.name.value)){
			form1.name.focus();
			alert('請填寫教學類別');  //请选择問題類別名稱			
			return;
		}
	
		form1.submit();
	}
	function checkform1(){

		if (chkblank(form1.name.value)){
			form1.name.focus();
			alert('請填寫教學類別');  //请选择問題類別名稱			
			return;
		}
		form1.submit();
	}

</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <FORM name=form1 action='admin_studytype_save.php' method=post >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="id" value="<?php echo $id?>">
  
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 
      src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" 
  width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319>&nbsp;</TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;教學資料</SPAN></TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%">
          <?php if ($Ie_Type != "mozilla") { ?>
		    <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="admin_studytype_list.php">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew border=0 link="javascript:checkform();">
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap ><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?>&nbsp; </TD>
                </TR></TBODY></TABLE><!--BUTTON_END-->
							
							
							
						</TR></TBODY></TABLE>
							
						</TD></TR></TBODY></TABLE>
		  <?php } else {?> 
		    <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<a href="admin_studytype_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap ><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD>
                </TR></TBODY></TABLE><!--BUTTON_END-->
							
							</td>
							
						</TR></TBODY></TABLE>
							
						</TD></TR></TBODY></TABLE>
<?php } ?>

		  </TD></TR>
			</TBODY>
		  </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD vAlign=top bgColor=#ffffff height=300>
                  <TABLE class=allborder cellSpacing=0 cellPadding=0 width="100%" align=center bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="18%">&nbsp;</TD>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>
                    
                    <TR>
                      <TD noWrap align=right width="18%">類別名稱：</TD>
                      <TD height="25" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','name',$name,"      maxLength=50 size=50 ")?></TD>
                    </TR>
					 
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD colspan="2" align=right noWrap>&nbsp;</TD>
                    </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
                     </FORM>
					  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
