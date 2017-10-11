<?php
include_once "Check_Admin.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/Admin_Operater.php";

//更新权限
if ( $_POST['action']=='Update'){
	$DB->query("update `{$INFO[DBPrefix]}menu_right` set ifhave='0' ");
	$Cid = $_POST['cid'];
	if (count($Cid)>0){
		foreach ($Cid as $k=>$v){
			$DB->query("update `{$INFO[DBPrefix]}menu_right` set ifhave='1' where mrid=".intval($v));
		}
	}
	$FUNCTIONS->sorry_back("admin_set_menu.php","設置成功");
}



?>
<HTML  xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man]?>--&gt;<?php echo $JsMenu[Administrators_Popedom_Set];//权限设定?></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>

<SCRIPT language=javascript>
function checkform()
{
	form1.submit();
}
function CheckAll(form){
	for (var i=0;i<form.elements.length;i++){ 
         var e = form.elements[i];
         if (e.name != 'chkall')   e.checked = form.toggle.checked;
	}
}
</SCRIPT>

<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center   border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	</TD>
	</TR>
	</TBODY>
  </TABLE>
      <?php  include_once "Order_state.php";?>
  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD></TR>
  </TBODY>
  </TABLE>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
   <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif"   width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man]?>--&gt;設置後臺菜單</SPAN></TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>&nbsp;</TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->
							
							</TD></TR></TBODY></TABLE>
							
					  </TD></TR></TBODY></TABLE>
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
				<!-- TABLE START  -->
				
<FORM name='form1' action='admin_set_menu.php' method='post'>
   <TABLE class=allborder height=221 cellSpacing=0 cellPadding=0 width="100%"  bgColor=#f7f7f7 border=0>
   <INPUT type=hidden value=power name=act> 
   <INPUT type=hidden value="Update" name=action>    
        <TBODY>
        <TR>
          <TD class=p9black background='images/<?php echo $INFO[IS]?>/bartop.gif'  height=26>&nbsp;&nbsp; 
		  <INPUT onclick=CheckAll(this.form); type=checkbox value=checkbox name=toggle> <?php echo $Admin_Operater[Check_All];//选中所有?></TD>
		</TR>
        <TR>
          <TD align=middle>
            <TABLE cellSpacing=0 cellPadding=0 width="98%" border=0>
              <TBODY>
              <TR>
                <TD align="left" vAlign=top class=p9black>
                  <?php
                  $sql = "select * from `{$INFO[DBPrefix]}menu_right` where level=0 and ifshow=1";
				  $Query = $DB->query($sql);
				  while($Result= $DB->fetch_array($Query)){
					  
					   echo "<INPUT id=cb" . $Result_1['mrid'] . " type=checkbox value=" . $Result['mrid'] . " name=cid[] ";
					   if($Result['ifhave']==1)
					   		echo " checked";
					   echo "><b>" . $Result['title'] . "</b><br>";
					   $sql_1 = "select * from `{$INFO[DBPrefix]}menu_right` where level='" . $Result['mrid'] . "' and ifshow=1";
				  	   $Query_1 = $DB->query($sql_1);
				?>
                <TABLE>
                 <TBODY>
                 <tr>
                <?php
				 $subl = "";
					   while($Result_1= $DB->fetch_array($Query_1)){
						  
						   $sql_2 = "select * from `{$INFO[DBPrefix]}menu_right` where level='" . $Result_1['mrid'] . "' and ifshow=1";
				  	       $Query_2 = $DB->query($sql_2);
						   $Num_2   = $DB->num_rows($Query_2);
						   $check = "";
						   $check = "<INPUT id=cb" . $Result_1['mrid'] . " type=checkbox value=" . $Result_1['mrid'] . " name=cid[] ";
						   if($Result_1['ifhave']==1)
					   			$check .=  " checked";
						   $check .=  ">";
						   if ($Num_2<=0){
						   		
								$subl .= "<tr><td class=p9black vAlign=top>" . $check . $Result_1['title'] . "</td></tr>";
						   }else{
						   	echo "<td class=p9black vAlign=top>" . $check . $Result_1['title'] . "";
							echo "<table>";
							while($Result_2= $DB->fetch_array($Query_2)){
								echo "<tr><td class=p9black vAlign=top><INPUT id=cb" . $Result_2['mrid'] . " type=checkbox value=" . $Result_2['mrid'] . " name=cid[] ";
								if($Result_2['ifhave']==1)
					   				echo " checked";
								echo ">" . $Result_2['title'] . "</td></tr>";
							}
							echo "</table>";
							echo "</td>";
						   }
					   }
					   if ($subl!=""){
						   echo "<td vAlign=top><table>" . $subl . "</table></td>";
						}
			    ?>
                </tr>
                </TBODY>
                </TABLE>
                <?php
					  echo "<hr>";
				  }
				  ?>
                  
				  				  
				  
				  </TD>
              </TR>
			  </TBODY>
			 </TABLE>
			</TD>
		   </TR>

		  </TBODY>
		 </TABLE>
	</FORM>


				<!-- TABLE END  -->	
				</TD>
              </TR>
			  </TBODY>
			</TABLE>
		   </TD>
		  </TR>
		 </TBODY>
		</TABLE>
	   </TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                      <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
					  </TBODY>
                      </TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
