<?php
include_once "Check_Admin.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/Admin_Operater.php";

$Opid = $FUNCTIONS->Value_Manage($_GET['opid'],$_POST['opid'],'back','');

//更新权限
if ( $Opid!="" && $_POST['action']=='Update'){
	$Cid = $_POST['cid'];
	if (count($Cid)>0){
		$String="";
		foreach ($Cid as $k=>$v){
			$String .= "%".$v;
		}
		//$Sql = "update operater set privilege='".trim($String)."' where opid=".intval($Opid);
		$DB->query("update `{$INFO[DBPrefix]}operatergroup` set privilege='".trim($String)."' where opid=".intval($Opid));
		$DB->query("update `{$INFO[DBPrefix]}operater` set privilege='".trim($String)."' where groupid=".intval($Opid));
	}
	$FUNCTIONS->sorry_back("admin_operatergroup_list.php?opid=".intval($Opid),"");
}

//读取权限

$Query = $DB->query("select * from `{$INFO[DBPrefix]}operatergroup` where opid=".intval($Opid)." limit 0,1");
$Num   = $DB->num_rows($Query);

if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$groupname    =  $Result['groupname'];
	$privilege   =  trim($Result['privilege']);

}else{
	$FUNCTIONS->sorry_back("admin_operatergroup_list.php?opid=".intval($Opid),"");
}
$ArrayPrivilege = explode("%",$privilege);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man]?>--&gt;<?php echo $JsMenu[Administrators_Popedom_Set];//权限设定?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
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
<div id="contain_out">
   <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319><?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man]?>--&gt;<?php echo $JsMenu[Administrators_Popedom_Set];//权限设定?></SPAN></TD>
              </TR></TBODY></TABLE></TD>
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
                                  <TD vAlign=bottom noWrap class="link_buttom">
                            <a href="admin_operatergroup_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
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
                      
                      <FORM name='form1' action='admin_privilege.php' method='post'>
                        <TABLE class=allborder height=221 cellSpacing=0 cellPadding=0 width="100%"  bgColor=#f7f7f7 border=0>
                          <INPUT type=hidden value=power name=act> 
                          <INPUT type=hidden value="Update" name=action>    
                          <INPUT type=hidden value='<?php echo $Opid?>' name=opid> 
                          <TBODY>
                            <TR>
                              <TD class=p9black background='images/<?php echo $INFO[IS]?>/bartop.gif'  height=26>&nbsp;&nbsp; 
                                <INPUT onclick=CheckAll(this.form); type=checkbox value=checkbox name=toggle> <?php echo $Admin_Operater[Check_All];//选中所有?></TD>
                              </TR>
                            <TR>
                              <TD align=middle>
                                <br />
                                <TABLE cellSpacing=0 cellPadding=0 width="98%" border=0>
                                  <TBODY>
                                    <TR>
                                      <TD align="left" vAlign=top class=p9black>
                                        <?php
                  $sql = "select * from `{$INFO[DBPrefix]}menu_right` where level=0 and mrid<>130 and ifhave=1";
				  $Query = $DB->query($sql);
				  while($Result= $DB->fetch_array($Query)){
					  echo "<b>" . $Result['title'] . "</b><br>";
					   $sql_1 = "select * from `{$INFO[DBPrefix]}menu_right` where level='" . $Result['mrid'] . "' and ifhave=1";
				  	   $Query_1 = $DB->query($sql_1);
				?>
                                        <TABLE>
                                          <TBODY>
                                            <tr>
                                              <?php
				 $subl = "";
					   while($Result_1= $DB->fetch_array($Query_1)){
						  
						   $sql_2 = "select * from `{$INFO[DBPrefix]}menu_right` where level='" . $Result_1['mrid'] . "' and ifhave=1";
				  	       $Query_2 = $DB->query($sql_2);
						   $Num_2   = $DB->num_rows($Query_2);
						   $check = "";
						   $check = "<INPUT id=cb" . $Result_1['mrid'] . " type=checkbox value=" . $Result_1['mrid'] . " name=cid[] " . RPrivilegeChecked($ArrayPrivilege,$Result_1['mrid']) . ">";
						   if ($Num_2<=0){
						   		
								$subl .= "<tr><td class=p9black vAlign=top>" . $check . $Result_1['title'] . "</td></tr>";
						   }else{
						   	echo "<td class=p9black vAlign=top>" . $check . $Result_1['title'] . "";
							echo "<table>";
							while($Result_2= $DB->fetch_array($Query_2)){
								echo "<tr><td class=p9black vAlign=top><INPUT id=cb" . $Result_2['mrid'] . " type=checkbox value=" . $Result_2['mrid'] . " name=cid[] " . RPrivilegeChecked($ArrayPrivilege,$Result_2['mrid']) . ">" . $Result_2['title'] . "</td></tr>";
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
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
