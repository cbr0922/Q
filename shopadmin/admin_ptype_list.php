<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$Sql      = "select * from `{$INFO[DBPrefix]}pay_type` order by pay_id ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num>0){
	$limit = 20;
	$Nav->total_result = $Num;
	$Nav->execute($Sql,$limit);
	$Query    = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<HTML>
<head>
<META http-equiv=ever content=no-cache>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2600.0" name=GENERATOR>
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Pay_Type];//付款方式?></title>

<style type="text/css">
<!--
.style1 {color: #0000FF}
-->
</style>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
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
		checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command[No_Select]?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_ptype.php?Action=Modi&pay_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command[No_Select]?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command[Del_Select]?>')){
			document.adminForm.action = "admin_ptype_save.php";
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
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 
      src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" 
  width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=302></TD>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
                <TR>
                  <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                  <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Pay_Type];//付款方式?></SPAN></TD>
                </TR>
              </TBODY>
            </TABLE></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_ptype.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[AddPayType];//新增?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
					</TR>
				 </TBODY>
				</TABLE>
</TD>
		  </TR>
		  </TBODY>
		</TABLE>

      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>

        <TR>
          <TD align=right height=31>&nbsp;            		  </TD>
          </TR>
		
	</TABLE>	
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
        <TR>
          <TD vAlign=top height=210>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD bgColor=#ffffff>
                  <TABLE class=listtable cellSpacing=0 cellPadding=0 
                  width="100%" border=0>
                    <FORM name=adminForm action="" method=post>
					<INPUT type=hidden name=act>
					 <INPUT type=hidden value=0  name=boxchecked> 
                    <TBODY>
                    <TR align=middle>
                      <TD width="10%" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
					  <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
					  <?php echo $JsMenu[Pay_Type];//付款方式?></TD>
                      </TR>
					<?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {


					?>
                    <TR class=row0>
                      <TD align=center height=20>
					  <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs[pay_id]?>' name=cid[]> </TD>
                      <TD height=20 align="left" noWrap>
                        <A href="javascript:toEdit('<?php echo $Rs[pay_id]?>',0);">
                        <?php echo $Rs['pay_name']?>
                        </A></TD>
                      </TR>
					<?php
					$i++;
					}
					?>
                    <TR>
                      <TD align=middle width=149 height=14>&nbsp;</TD>
                      <TD width=427 height=14>&nbsp;</TD>
                      </TR>
					 </FORM>
					 </TABLE>
					 </TD>
				    </TR>
			    </TABLE>
            
			<?php if ($Num>0){ ?>
            <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>

              <TBODY>
              <TR>
                <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
				<?php echo $Nav->pagenav()?>
				</TD>
              </TR>
			<?php } ?>

  </TABLE></TD></TR></TABLE></TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=302>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></TBODY></TABLE>

</BODY></HTML>
