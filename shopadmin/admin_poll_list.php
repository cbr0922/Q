<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Poll_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

//$Where    = $_GET['skey']!=""  && trim(urldecode($_GET['skey']))!=$TPL_TAGS["ttag_1400"] ?  " where username like '%".trim(urldecode($_GET['skey']))."%'" : $Where ;
//$Sql      = "select * from poll ".$Where." order by lastlogin desc ";

$Sql      = "select * from `{$INFO[DBPrefix]}poll`  order by poll_id desc ";
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
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Poll_Man];//投票管理?>--&gt;<?php echo $JsMenu[Poll_List];//投票管理列表?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected(<?php echo intval($Nums)?>,'<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_poll_edit.php?Action=Modi&Poll_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected(<?php echo intval($Nums)?>,'<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_poll_save.php";
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
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Poll_Man];//投票管理?>--&gt;<?php echo $JsMenu[Poll_List];//投票管理列表?></SPAN>
                    </TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_poll_create.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $JsMenu[Poll_Add];//添加投票?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
            <TD align=right colSpan=2 height=31>
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width=500 border=0>
                <TBODY>
                  <TR>
                    <TD height=31 align=right>&nbsp;</TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>  
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=p9black onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
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
                              <TD width="5%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="10%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Basic_Command['SNo_say'];//序号?></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Poll_Pack[PoolTitle_say];//调查题目?><br></TD>
                              <TD width="10%" height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Basic_Command['IfCloseOrOpen'];//是否關閉?></TD>
                              <TD width="30%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Basic_Command['Idate_say'];//時間?></TD>
                              </TR>
                            <?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {


					?>
                            <TR class=row0>
                              <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['poll_id']?>' name=cid[]> </TD>
                              <TD height=26 align="left" noWrap>                        
                                <?php echo $j?>                        </TD>
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['poll_id']?>',0);"><?php echo $Rs['title']?></A>                      </TD>
                              <TD height=26 align="center" noWrap><?php if (intval($Rs['open'])==1){ echo $Basic_Command['Open'];}else{ echo $Basic_Command['Close'];}?>                      </TD>
                              <TD height=26 align="center" noWrap><?php echo date("Y-m-d H:i a",$Rs['idate'])?></TD>
                              </TR>
                            <?php
					$j++;
					$i++;
					}
					?>
                            <TR>
                              <TD align=middle width=104 height=14>&nbsp;</TD>
                              <TD width=212 height=14>&nbsp;</TD>
                              <TD width=265 height=14>&nbsp;</TD>
                              <TD width=20% height=14>&nbsp;</TD>
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
