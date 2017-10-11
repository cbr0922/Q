<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

$objClass = "9pv";
$Where    = '';
$Nav      = new buildNav($DB,$objClass);


if (isset($_GET['type'])) {
	$Where = urldecode($_GET['type']);
	$Where = str_replace('wodedanyinhao',"'",$Where);

}else{
	$Where    = (trim($_GET['skey'])!="") ?  " where k_type_name like '%".urldecode(trim($_GET['skey']))."%'" : "" ;
}

$Sql      = "select * from `{$INFO[DBPrefix]}kefu_type` ".$Where." order by k_type_id  ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query    = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD><TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;問題類別管理</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&k_type_id="+catid;
	}
	
	if (checkvalue!=false){
		
		document.adminForm.action = "admin_kefu_type.php?Action=Modi&k_type_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}
function toNew(id,catid){
	    document.adminForm.action = "admin_kefu_type.php";
		document.adminForm.submit();
	
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){   //您是否确认删除选定的记录
			document.adminForm.action = "admin_kefu_type_save.php";
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
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $KeFu_Pack['Back_Nav_title_thr'];//綫上客服-->問題類別列表?></SPAN>				</TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toNew();"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $KeFu_Pack['Back_AddType'];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                                <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->					  </TD>
                          </TR>
                        </TBODY>
                      </TABLE>				  </TD>                  
                  </TR>
                </TBODY>
              </TABLE>
              </TD>
            </TR>
          </TBODY>
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
                          <?php
					 $Where = str_replace('\'','wodedanyinhao',$Where);
					 if(!isset($_GET['offset'])){
					 	$offset = 0;
					 }else {
					 	$offset = $_GET['offset'];
					 }
					 ?>
                          <INPUT type=hidden value="<?php echo urlencode($Where)?>"  name='type'>
                          <INPUT type=hidden value="<?php echo $offset?>"  name='offset'>
                          <TBODY>
                            <TR align=middle>
                              <TD width="49" height=26 align=left noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle></TD>
                              <TD width="61"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['No'];//編號?></TD>
                              <TD width="113"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['Status'];//狀態?>(ON/OFF)</TD>
                              <TD width="161" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>分類</TD>
                              <TD width="598"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $KeFu_Pack['Back_Type_Name'];//問題類別名稱?></TD>
                              
                              
                              </TR>
                            <?php               

					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                            <TR class=row0>
                              <TD align=left width=49 height=26>
                                <?php 
					  if (intval($Rs['k_type_id'])==1||intval($Rs['k_type_id'])==2||intval($Rs['k_type_id'])==3||intval($Rs['k_type_id'])==4||intval($Rs['k_type_id'])==5||intval($Rs['k_type_id'])==6||intval($Rs['k_type_id'])==7||intval($Rs['k_type_id'])==8||intval($Rs['k_type_id'])==9||intval($Rs['k_type_id'])==10||intval($Rs['k_type_id'])==11||intval($Rs['k_type_id'])==12||intval($Rs['k_type_id'])==13||intval($Rs['k_type_id'])==14||intval($Rs['k_type_id'])==15||intval($Rs['k_type_id'])==16){					    
					   $Disabled = "disabled='disabled'";
					  }else{
					   $Disabled = "";
					  }					  
					  ?>
                                <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs['k_type_id']?>' name="cid[]" <?php echo $Disabled  ?>></TD>
                              <TD height=26 align="center" noWrap><?php echo  $i+1;?></TD>
                              <TD height=26 align="center" noWrap><?php echo $Rs['status']?'ON':'<font color="Red">OFF</fond>';?></TD>
                              <TD align="left" noWrap>
                              <?php 
		  $s_Sql = "select * from `{$INFO[DBPrefix]}kefu_type` where k_type_id='" . $Rs['typegroup'] . "'";
		  $Query_s    = $DB->query($s_Sql);
		  $Rs_s=$DB->fetch_array($Query_s);
		  echo $Rs_s['k_type_name'];
		  ?>
                              </TD>
                              <TD height=26 align="left" noWrap>
                                <?php
                        if ($Rs['checked']==1) {
                        	$checked = "<font color='Red'>(".$KeFu_Pack['Back_NeiDing'].")</fond>";
                        }else {
                        	$checked = '';
                        }
                        ?>
                                <A href="javascript:toEdit('<?php echo $Rs['k_type_id']?>',0);"> <?php echo $Rs['k_type_name'].$checked?>&nbsp; </A></TD>
                              
                              </TR>
                            <?php
					$i++;
					}

					?>
                            <?php  if ($Num==0){ ?>
                            <TR align="center">
                              <TD height=14 colspan="5"><?php echo $KeFu_Pack['nodata'];//"無相關資料?></TD>
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
