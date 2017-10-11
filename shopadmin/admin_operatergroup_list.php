<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Operater.php";


$Where    = $_GET['skey']!=""  && trim(urldecode($_GET['skey']))!=$Admin_Operater[PleaseInputAcc] ?  " where groupname like '%".trim(urldecode($_GET['skey']))."%'" : $Where ;
$Sql      = "select * from `{$INFO[DBPrefix]}operatergroup` ".$Where."  ";

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
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man];//管理員管理?>--&gt;管理員組</TITLE>

</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toPower(id){
	var checkvalue;
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}

	if (checkvalue!=false){
		document.adminForm.action = "admin_privilege.php?opid="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_operatergroup.php?Action=Modi&opid="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_operatergroup_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}


</SCRIPT>

<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man];//管理員管理?>--&gt;<?php echo $JsMenu[User_List]?></SPAN>
                      </TD>
              </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=middle>
                    <!--BUTTON_BEGIN-->
                    <TABLE>
                      <TBODY>
                        <TR>
                          <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toPower(0);"><IMG src="images/<?php echo $INFO[IS]?>/fb-adminlevel.gif" width="31" height="29" border=0>&nbsp;<?php echo $Admin_Operater[Popedom];//权限?></a></TD>
                          </TR>
                        </TBODY>
                      </TABLE>
                    <!--BUTTON_END-->
                    
                    </TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_operatergroup.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增管理員組</a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
            <TD align=left colSpan=2 height=31>
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width=500 border=0>
                <TBODY>
                  <TR>
                    <TD width=210 height=31>
                      <INPUT  name='skey'  size="30" id='skey'> 
                      <div id="skeytips" class="tips" align="left">&nbsp;請輸入要搜尋的管理員組名稱</div>
                      </TD>
                    <TD align=middle width=8 height=31>			    </TD>
                    <TD class=p9black vAlign=center width=282 height=31> <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle"> 
                      </TD>
                    </TR>
                  </TBODY>
                </TABLE>
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
                              <TD width="25" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="40"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Operater[NumDot];//序号?></TD>
                              <TD width="157" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style="padding-left:20px"><i class="icon-group" style="font-size:14px;margin-right:5px"></i>管理員組名稱<br></TD>
                              <TD width="290" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>管理員數</TD>
                              <TD width="353" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>收郵件</TD>
                              </TR>
                            <?php               
					$i=0;
				    $j=1;
					while ($Rs=$DB->fetch_array($Query)) {

					
					?><TBODY>
                            <TR class=row0>
                              <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['opid']?>' name=cid[]> </TD>
                              <TD height=26 align="center" noWrap>                        
                                <?php echo $j?>                        </TD>
                              <TD height=26 align="left" noWrap style="padding-left:20px">
                                <A href="javascript:toEdit('<?php echo $Rs['opid']?>',0);"><?php echo $Rs['groupname']?></A>                      </TD>
                              <TD align="left" noWrap>
                                <?php
                      $Sql_o      = "select count(*) as counts from `{$INFO[DBPrefix]}operater` where groupid='" . $Rs['opid'] . "' order by lastlogin desc ";
					   $Query_o    = $DB->query($Sql_o);
					   while ($Rs_o=$DB->fetch_array($Query_o)) {
						   echo  $Rs_o['counts'] . "人";
					   }
					  ?>
                                </TD>
                              <TD align="left">
                              <?php
                              $maillist    =  $Rs['maillist'];
							  $maillist_array = explode(",",$maillist);
							  if(is_array($maillist_array)){
								  foreach($maillist_array as $k=>$v){
									  $Sql_m      = "select * from `{$INFO[DBPrefix]}sendtype` where sendtype_id='" . $v . "'";
									  $Query_m    = $DB->query($Sql_m);
									  $Rs_m=$DB->fetch_array($Query_m);
									  echo $Rs_m['sendname'] . " ／ ";
								  }
							  }
							  ?>
                              &nbsp;</TD>
                              </TR>
                            </TBODY>
                            <?php
					$j++;
					$i++;
					}
					?>
                            <?php  if ($Num==0){ ?>
                            <TR align="center">
                              <TD height=14 colspan="5"><?php echo $Basic_Command['NullDate']?></TD>
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
