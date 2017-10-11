<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$group_id = intval($_GET['group_id']);
$Sql = "select tg.* from `{$INFO[DBPrefix]}transgroup` as tg where tg.group_id=".intval($group_id)." limit 0,1";
$Query = $DB->query($Sql);
$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$group_id  =  $Result['group_id'];
		$trans_id  =  $Result['trans_id'];
		$groupname  =  $Result['groupname'];
		$money  =  $Result['money'];
		$content  =  $Result['content'];
	}

$Sql = "select * from `{$INFO[DBPrefix]}transgroup_price` as tp where tp.group_id='" . $group_id . "'";

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
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="./css/theme.css" type=text/css rel=stylesheet>
<LINK href="./css/css.css" type=text/css rel=stylesheet>
<LINK href="./css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;地區運費管理</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
 <TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center   border=0>
 <TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
	<?php  include_once "desktop_title.php";?></TD>
  </TR>
  </TBODY>
 </TABLE>
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
		document.adminForm.action = "admin_areagroup_price.php?Action=Modi&group_id<?php echo $group_id;?>=&tp_id"+checkvalue;
		document.adminForm.Action.value="Modi";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_areagroup_price_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}


</SCRIPT>
<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif height=302></TD>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black><SPAN  class=p9orange>
				<?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;地區運費管理-&gt;<?php echo $groupname;?></SPAN>
				</TD>
				</TR>
				</TBODY>
			 </TABLE>
			 
			 </TD>
          <TD align=right width="50%">
		  <?php if ($Ie_Type != "mozilla") { ?>
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="admin_areagroup_price.php?group_id=<?php echo $group_id;?>">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增地區重量運費&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="javascript:toEdit(0);">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="javascript:toDel();">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle></TD>
					</TR>
				 </TBODY>
				</TABLE>
          <?php } else {?> 
            <TABLE cellSpacing=0 cellPadding=0 border=0>
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
                            <TD vAlign=bottom noWrap><A href="admin_areagroup_price.php?group_id=<?php echo $group_id;?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增地區重量運費</A>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><A href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></A>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><A href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></A>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle></TD>
					</TR>
				 </TBODY>
				</TABLE>
          <?php } ?>					
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
                  <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
                    <FORM name=adminForm action="" method=post>
					<INPUT type=hidden name=act>
					<INPUT type=hidden name=Action>
                    <INPUT type=hidden name=group_id value="<?php echo $group_id;?>">
					 <INPUT type=hidden value=0  name=boxchecked> 
                    <TBODY>
                    <TR align=middle>
                      <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
					  <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                      <TD width="46" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>序號</TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>i起始重量（&gt;=）</TD>
                      <TD width="410" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>結束重量（&lt;=）</TD>
                      <TD width="326" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>運費</TD>
                      </TR>
					<?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {


					?>
                    <TR class=row0>
                      <TD align=middle height=26>
					  <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['tp_id']?>' name=group_id[]> </TD>
                      <TD align="center" noWrap><?php echo $j?></TD>
                      <TD height=26 align="left" noWrap><a href="admin_areagroup_price.php?Action=Modi&group_id=<?php echo $Rs['group_id']?>&tp_id=<?php echo $Rs['tp_id']?>"><?php echo $Rs['startweight']?></a></TD>
                      <TD align="center" noWrap><a href="admin_areagroup_price.php?Action=Modi&group_id=<?php echo $Rs['group_id']?>&tp_id=<?php echo $Rs['tp_id']?>"><?php echo $Rs['endweight']?></a></TD>
                      <TD align="center" noWrap><a href="admin_areagroup_price.php?Action=Modi&group_id=<?php echo $Rs['group_id']?>&tp_id=<?php echo $Rs['tp_id']?>"><?php echo $Rs['price']?></a></TD>
                      </TR>
					<?php
					$j++;
					$i++;
					}
					?>
                    <TR>
                      <TD align=middle width=22 height=14>&nbsp;</TD>
                      <TD width=46>&nbsp;</TD>
                      <TD width=299 height=14>&nbsp;</TD>
                      <TD>&nbsp;</TD>
                      <TD>&nbsp;</TD>
                      </TR>
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
	  </TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=302>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></TBODY></TABLE>
 <div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>
