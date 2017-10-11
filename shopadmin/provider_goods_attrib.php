<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Good.php";
include_once Resources."/ckeditor/ckeditor.php";

$Gid = intval($_REQUEST['good_id'])==0 ? $Gid : intval($_REQUEST['good_id']);

if ($_POST[Action]=='Up'){
	$Sql  = " update `{$INFO[DBPrefix]}goods` set good_color='".strip_tags($_POST['good_color'])."',good_size='".strip_tags($_POST['good_size'])."' where gid=".intval($_POST[gid]);
	$DB->query($Sql);
}
$Query = $DB->query("select goodsname,good_color,good_size from `{$INFO[DBPrefix]}goods` where gid=".intval($Gid)." limit 0,1");
$Num   = $DB->num_rows($Query);

if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$Goodsname        =  $Result['goodsname'];
	$good_color       =  $Result['good_color'];
	$good_size        =  $Result['good_size'];

}else{
	echo "<script language=javascript>javascript:window.history.back();</script>";
	exit;
}
?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $Good[MultiAttrib];?></title>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?></TD>
  </TR></TBODY></TABLE>
<SCRIPT language=javascript>
	function checkform(){
		//document.form1.action = "";
		document.form1.submit();		
	}
</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>

  <FORM name='form1' action='' method=post >
  <input type="hidden" name="Action" value="Up">
  <INPUT type=hidden  name='gid' value="<?php echo $Gid?>">   
   <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Good[MultiAttrib]; //商品多属性?></SPAN>
				</TD>
				</TR>
				</TBODY>
			 </TABLE></TD>
          <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:location.href='provider_goods.php?Action=Modi&gid=<?php echo $Gid?>';"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"   border=0>&nbsp;<?php echo $Basic_Command['Cancel'] ;//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE><TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
          </TD>
		  </TR>
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
                  <TABLE class=allborder cellSpacing=0 cellPadding=2 
                  width="100%" bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD colSpan=2>&nbsp;</TD></TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD colSpan=2>&nbsp;</TD>
                    </TR>			
   												
                    <TR>
                      <TD noWrap align=right width="12%"><?php echo $Good[Product_Name];//商品名称：?>：</TD>
                      <TD colSpan=2><?php echo $Goodsname;?>
					 </TD></TR>		 
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD colSpan=2>&nbsp;</TD>
					 </TR>
                  					
				    <TR>
                      <TD width="12%" align=right valign="top" noWrap><?php echo $Good[Product_Color];//商品颜色：?>：</TD>
                      <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('textarea','good_color',$good_color," cols=80 rows=6")?></TD></TR>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD colSpan=2>&nbsp;</TD>
 				    </TR>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD colSpan=2>&nbsp;</TD></TR>
					<TR>
                      <TD width="12%" align=right valign="top" noWrap><?php echo $Good[Product_Size];//商品尺寸：?>：</TD>
                      <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('textarea','good_size',$good_size," cols=80 rows=6")?></TD>
                      </TR>                    
					<TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD colspan="2">&nbsp;</TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD colspan="2">&nbsp;</TD>
                      </TR>				
                    <TR>
                      <TD width="12%" align=right valign="top" noWrap>&nbsp;</TD>
                      <TD>&nbsp;</TD>
					</TR>

                      <TD width="12%" align=right valign="top" noWrap>&nbsp;</TD>
                      <TD colSpan=2>&nbsp;</TD></TR>
                    <TR>					  

                    <TR>
                      <TD align=right valign="top" noWrap>&nbsp;</TD>
                      <TD colspan="2" align=left valign="top" noWrap>&nbsp;					  </TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD colSpan=2>&nbsp; 
            </TD></TR></TABLE></TD></TR></TABLE></TD></TR></TABLE></TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></FORM></TBODY></TABLE>

 <div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
