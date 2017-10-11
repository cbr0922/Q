<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Login_Log_Pack.php";

$Sql      = "select * from `{$INFO[DBPrefix]}login_log`  order by logintime desc ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
  $limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
  $Nav->total_result=$Num;
  $Nav->execute($Sql,$limit);
  $Query = $Nav->sql_result;
  $Nums     = $Num<$limit ? $Num : $limit ;
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man];//管理員管理?>--&gt;<?php echo $JsMenu[User_LoginLog]?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_login_log_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}
</SCRIPT>
<div id="contain_out">
  <TBODY>
  <TR>
    <TD vAlign=top width="100%" height=302><?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%"><table width="90%" border=0 cellpadding=0 cellspacing=0>
              <tbody>
                <tr>
                  <td width=38><img height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></td>
                  <td class=p12black nowrap><span class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man];//管理員管理?>--&gt;<?php echo $JsMenu[User_LoginLog]?></span></td>
                </tr>
              </tbody>
            </table></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  </TR>
                </TBODY>
              </TABLE>					
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=optForm method=get action="">        
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=right colSpan=2 height=31>
              
              </TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>  
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
              </TD>
            </TR>
          </FORM>
        </TABLE>	
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD class=p9black noWrap align=center  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle></TD>
                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Basic_Command['SNo_say'];//序号?></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Login_Log_Pack[LoginUser];//帳號?><br></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Login_Log_Pack[LoginType];//類型?></TD>
                              <TD height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Login_Log_Pack[LoginIP];//IP?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Admin_Login_Log_Pack[LoginTime];//时间?></TD>
                              </TR>
                            <?php               
					$i=0;
				    $j=1;
					while ($Rs=$DB->fetch_array($Query)) {

					
					?><TBODY>
                            <TR class=row0>
                              <TD align=center height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['log_id']?>' name=cid[]></TD>
                              <TD height=26 align="center" noWrap>                        
                                <?php echo $j?> </TD>
                              <TD height=26 align="left" noWrap>
                                <?php echo $Rs['loginuser']?></TD>
                              <TD height=26 align="left" noWrap><?php echo $Rs['logintype']?></TD>
                              <TD height=26 align="center" noWrap><?php echo $Rs['loginip']  ?>                      </TD>
                              <TD height=26 align="center" noWrap><?php echo date("Y-m-d H:i a",$Rs['logintime'])?></TD>
                              </TR></TBODY>
                            <?php
					$j++;
					$i++;
					}
					?>
                            <TR>
                              <TD width=5% height=14 align=middle>&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              <TD height=14>&nbsp;</TD>
                              <TD height=14 colspan="2">&nbsp;</TD>
                              </TR>
                            <?php  if ($Num==0){ ?>
                            <TR align="center">
                              <TD height=14 colspan="6"><?php echo $Basic_Command['NullDate']?></TD>
                              </TR>
                            <?php } ?>	
                          </FORM>
                        </TABLE>
                      </TD>
                    </TR>
                </TABLE>
              <?php  if ($Num>0){ ?>
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                      </TD>
                    </TR>
                </TABLE>
              <?php } ?>	
              </TD>
            </TR>
          </TABLE>
</div>
 <div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>
