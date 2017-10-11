<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Operater.php";


$Where    = $_GET['skey']!=""  && trim(urldecode($_GET['skey']))!=$Admin_Operater[PleaseInputAcc] ?  " where username like '%".trim(urldecode($_GET['skey']))."%'" : $Where ;
$Sql      = "select * from `{$INFO[DBPrefix]}operater` ".$Where." order by lastlogin desc ";

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
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man];//管理員管理?>--&gt;<?php echo $JsMenu[User_List]?></TITLE>
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
		document.adminForm.action = "admin_operater.php?Action=Modi&opid="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_operater_save.php";
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
                    
                    
                    </TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_operater.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Operater[Administrators_Add];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                      <INPUT  name='skey'  size="30" id='skey' class="easyui-tooltip" title="<?php echo $Admin_Operater[Ao_WhatIsSkey]; ?>"> 
                    </TD>
                    <TD align=middle width=6 height=31>			    </TD>
                    <TD class=p9black vAlign=center width=284 height=31> <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle"> 
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
                              <TD width="20" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="47"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Operater[NumDot];//序号?></TD>
                              <TD width="101" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><i class="icon-user" style="font-size:14px;margin-right:4px"></i><?php echo $Admin_Operater[UserName];//帳號?><br></TD>
                              <TD width="103" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Operater[TrueName];//姓名?></TD>
                              <TD width="211" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>email</TD>
                              <TD width="110" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>管理員組</TD>
                              <TD width="107" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>管理員類型</TD>
                              <TD width="110" height="26" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Operater[Status];//状态?></TD>
                              <TD width="152" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Admin_Operater[LastLoginTime];//最后登陆时间?></TD>
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
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['opid']?>',0);"><?php echo $Rs['username']?></A>                      </TD>
                              <TD height=26 align="left" noWrap><?php echo $Rs['truename']?></TD>
                              <TD align="left" noWrap><?php echo $Rs['email']?>&nbsp;</TD>
                              <TD align="left" noWrap><?php
                      $Sql_g      = "select * from `{$INFO[DBPrefix]}operatergroup` where opid='" . $Rs['groupid'] . "'";
					  $Query_g    = $DB->query($Sql_g);
					  $Rs_g=$DB->fetch_array($Query_g);
					  echo $Rs_g['groupname'];
					  ?>&nbsp;</TD>
                              <TD align="left" noWrap><?php if (intval($Rs['type'])==1){ echo "PM";}elseif(intval($Rs['type'])==0){ echo "PM助理";}elseif(intval($Rs['type'])==2){ echo "一般";}?></TD>
                              <TD height=26 align="left" noWrap><?php if (intval($Rs['status'])==1){ echo "<i class='icon-check red_small'></i> 開啟中";}else{ echo "<i class='icon-check-empty'></i> 關閉中";}?>                      </TD>
                              <TD height=26 align="left" noWrap><?php echo date("Y-m-d H:i a",$Rs['lastlogin'])?></TD>
                            </TR></TBODY>
                            <?php
					$j++;
					$i++;
					}
					?>
                            <?php  if ($Num==0){ ?>
                            <TR align="center">
                              <TD height=14 colspan="9"><?php echo $Basic_Command['NullDate']?></TD>
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
 <div align="center">
   <?php include_once "botto.php";?>
 </div>
</BODY></HTML>
