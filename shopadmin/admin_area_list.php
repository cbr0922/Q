<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

if(intval($_GET['top_id'])>0){
	$Query_goods = $DB->query("select * from `{$INFO[DBPrefix]}area` where area_id=".intval($_GET['top_id'])." limit 0,1");
	$Num_goods   = $DB->num_rows($Query_goods);
	if ($Num_goods>0){
		$Result_goods= $DB->fetch_array($Query_goods);
		$top_areaname =  $Result_goods['areaname'];
		$top_areatype  =  $Result_goods['areatype'];
		$top_top_id  =  $Result_goods['top_id'];
		$top_zip  =  $Result_goods['zip'];
		$areatype = ($Result_goods['areatype'] + 1);
	}else{
		$areatype = 1;
	}
}else{
	$areatype = 1;
}
$Sql      = "select * from `{$INFO[DBPrefix]}area` where top_id='" . intval($_GET['top_id']) . "' ";

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
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;地區管理--&gt;地區列表<?php if (intval($top_id) > 0){?>--&gt;<?php echo $top_areaname;}?></TITLE>
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
		document.adminForm.action = "admin_area.php?Action=Modi&top_id=<?php echo $_GET['top_id'];?>&area_id="+checkvalue;
		document.adminForm.Action.value="Modi";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_area_save.php?top_id=<?php echo $_GET['top_id'];?>";
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
                    <TD class=p9orange>
                      <?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<a href="admin_area_list.php">地區管</a>理--&gt;地區列表<?php if (intval($top_id) > 0){?>--&gt;<?php echo $top_areaname;}?>
                      </TD>
                    </TR>
                  </TBODY>
                </TABLE>
              
              </TD>
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
                                  <TD vAlign=bottom noWrap class="link_buttom"><A href="admin_area.php?top_id=<?php echo $_GET['top_id'];?>&areatype=<?php echo $areatype;?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增同級地區</A></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><A href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></A></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><A href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></A></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle></TD>
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
                    <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden name=Action>
                          <INPUT type=hidden value=0  name=boxchecked> 
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        
                          <TBODY>
                            <TR align=middle>
                              <TD width="10" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="52" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>序號</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 地區名稱</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>郵編號碼</TD>
                              <?php 
					  if($areatype != 3){
					  ?>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>設置</TD>
                              <?php
					  }
					  ?>
                              </TR>
                            <?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {


					?><TBODY>
                            <TR class=row0>
                              <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['area_id']?>' name=area_id[] <?php if($Rs['area_id']==1) echo "disabled";?>> </TD>
                              <TD align="center" noWrap><?php echo $j?></TD>
                              <TD height=26 align="left" noWrap><a href="admin_area.php?Action=Modi&area_id=<?php echo $Rs['area_id']?>&top_id=<?php echo $Rs['top_id']?>"><?php echo $Rs['areaname']?></a></TD>
                              <TD align="left" noWrap><?php echo $Rs['zip']?>&nbsp;</TD>
                              <?php 
					  if($areatype != 3){
					  ?>
                              <TD align="left" noWrap><a href="admin_area.php?top_id=<?php echo $Rs['area_id']?>&areatype=<?php echo $Rs['areatype']+1?>">新增下級地區</a> <a href="admin_area_list.php?top_id=<?php echo $Rs['area_id']?>&areatype=<?php echo $Rs['areatype']+1?>">下級地區列表</a></TD>
                              <?php
					  }
					  ?>
                              </TR></TBODY>
                            <?php
					$j++;
					$i++;
					}
					?>
                          
                        </TABLE></FORM>
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
    </TD>
    </TR>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
