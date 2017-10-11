<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Adv_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Search_key = $Adv_Pack[PleaseInputAdvName];

$Where    = $_GET['skey']!=""  && trim(urldecode($_GET['skey']))!=$Search_key ?  " and (adv_title like '%".trim(urldecode($_GET['skey']))."%' or adv_tag like '%".trim(urldecode($_GET['skey']))."%')" : $Where ;
$Sql      = "select * from `{$INFO[DBPrefix]}advertising` where ifhome=0 and adv_type not like '11%' ".$Where." order by adv_display desc ,adv_id desc ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 100  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query  = $Nav->sql_result;
	$Nums   = $Num<$limit ? $Num : $limit ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Advertis_Man];//广告管理?>--&gt;<?php echo $JsMenu[Advertis_List];//广告列表?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
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
		document.adminForm.action = "admin_adv.php?Action=Modi&Adv_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected(<?php echo intval($Nums)?>,'<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_adv_save.php";
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
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Advertis_Man];//广告管理?>--&gt;<?php echo $JsMenu[Advertis_List];//广告列表?></SPAN>
                    </TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=middle></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_adv.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Adv_Pack[AdvAdd];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                          <TD align=middle width=79 class="link_buttom"><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle></TD>
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
                    <TD height=31 align=left><input  name='skey'  onFocus=this.select()  onClick="if(this.value=='<?php echo $Search_key?>')this.value=''"  onMouseOver=this.focus() value='<?php echo $Search_key?>' size="30">			    
                      <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle">                </TD>
                  </TR>
                </TBODY>
              </TABLE>
            </TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示?><?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
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
                              <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                                <INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="166"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
                                <?php echo $Adv_Pack[AdvTitle];//广告标题?></TD>
                              <TD width="76" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
                                <?php echo $Adv_Pack[AdvTag];?><!--标签--></TD>
                              <TD width="66" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
                                <?php echo $Adv_Pack[AdvType];//广告类型?></TD>
                              <TD width="55" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>狀態</TD>
                              <TD width="208" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>起迄時間</TD>
                              <TD width="140" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>購買公司</TD>
                              <TD width="69" height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>點擊次數</TD>
                              <TD width="65" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
                                <?php echo $Adv_Pack[VisitNum];//访问次数?></TD>
                              <TD width="69" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>點擊率</TD>
                              <TD width="68" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>排序</TD>
                              <TD width="60" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>語言版本</TD>
                            </TR>
                            <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {


					?>
                            <TR class=row0>
                              <TD align=middle width=41 height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['adv_id']?>' name=cid[]> </TD>
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['adv_id']?>',0);">
                                  <?php echo $Rs['adv_title']?>                        </A></TD>
                              <TD height=26 align="left" noWrap><?php echo $Rs['adv_tag']?> &nbsp;</TD>
                              <TD height=26 align="left" noWrap>
                                <?php echo $FUNCTIONS->adv_type_f(intval($Rs['adv_type']))?>                      </TD>
                              <TD align="center" noWrap><?php if ($Rs['adv_display']==1) echo "<font color=#ff0000><i class=icon-check></i></font>開啟"; else echo "關閉" ;?></TD>
                              <TD align="center" noWrap><?php if ($Rs['start_time']!="") echo date("Y-m-d h:i",$Rs['start_time']); if ($Rs['end_time']!="")  echo " 至 ".  date("Y-m-d h:i",$Rs['end_time'])?>
                              &nbsp;</TD>
                              <TD align="left" noWrap><?php echo $Rs['company']?>&nbsp;</TD>
                              <TD height=26 align="center" noWrap><?php echo $Rs['click_count']?>&nbsp;</TD>
                              <TD height=26 align="right" noWrap><?php echo $Rs['point_num']?>&nbsp;</TD>
                              <TD align="right" noWrap>
                                <?php if ($Rs['point_num']>0) echo round($Rs['click_count']/$Rs['point_num']*100,2) . "%"; else echo 0;?>                      </TD>
                              <TD align="center" noWrap><?php echo $Rs['orderby']?>&nbsp;</TD>
                              <TD align="center" noWrap><?php echo $Rs['language']?></TD>
                            </TR>
                            <?php
					$i++;
					}
					?>
                        </FORM>
                      </TABLE>
                    </TD>
                  </TR>
              </TABLE>
              
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                
                <TBODY>
                  <TR>
                    <TD height=23 align=middle vAlign=center background=images/<?php echo $INFO[IS]?>/03_content_backgr.png>
                      <?php echo $Nav->pagenav()?>				</TD>
                  </TR>
                  
                  
        </TABLE></TD></TR></TABLE>
</div>
 <div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
