<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Sql      = "select * from `{$INFO[DBPrefix]}transportation` order by transport_id ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num>0){
	$limit = 20;
	$Nav->total_result = $Num;
	$Nav->execute($Sql,$limit);
	$Nums     = $Num<$limit ? $Num : $limit ;
}else{
	$FUNCTIONS->sorry_back('admin_ttype.php','');
}
?>
<HTML>
<head>
<META http-equiv=ever content=no-cache>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2600.0" name=GENERATOR>
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Send_Type]?></title>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
        /*****************************************************
         * 滑鼠hover變顏色
         ******************************************************/
$(document).ready(function() {
$("#orderedlist tbody tr").hover(function() {
		$(this).addClass("blue");
	}, function() {
		$(this).removeClass("blue");
	});
});
</script>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected(<?php echo $Nums?>,'<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_ttype.php?Action=Modi&transport_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_ttype_save.php";
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
                    <TD class=p12black noWrap>
                      <SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Send_Type]?></SPAN></TD>
              </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=middle>&nbsp;                  </TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_ttype.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[AddCarriageType];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                  <TD align=middle>&nbsp;                  </TD>
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
                      <INPUT type=hidden value=0  name=boxchecked> 
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                        
                          <TBODY>
                            <TR align=middle>
                              <TD width="80" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle></TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $JsMenu[Send_Type];//配送方式?></TD>
                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[CarriagePrice];//運費?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>狀態</TD>
                               <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>貨運類型</TD>
                              <TD width="633" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>金流</TD>
                              </TR>
                            <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Nav->sql_result)) {


					?><TBODY>
                            <TR class=row0>
                              <TD width=80 height=20 align=center>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['transport_id']?>' name=cid[]></TD>
                              <TD height=20 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['transport_id']?>',0);">
                                  <?php echo $Rs['transport_name']?>
                                </A></TD>
                              <TD height=20 align=center nowrap><?php echo $Rs['transport_price']?></TD>
                              <TD align=center nowrap>
                                <?php 
					 // echo $month;
					  //$type_array = explode(",",$Rs['type']);
					  if($Rs['type']==1) echo "開啟";
					  if($Rs['type']==2) echo " 關閉";
					  ?>
                                </TD>
                                 <TD align=center nowrap>
                      <?php
                      switch($Rs['ttype']){
							case 0:
								echo "常溫";
								break; 
							case 1:
								echo "冷藏";
								break; 
							case 2:
								echo "冷凍";
								break;  
					  }
					  ?>
                      </TD>
                              <TD align=left>
                                <?php
					  if ($Rs['payment']!=""){
                      $Psql = "select * from `{$INFO[DBPrefix]}paymethod` as p where p.mid in (" . $Rs['payment'] . ") order by p.mid";
					  $PQuery    = $DB->query($Psql);
					  while ($PRs=$DB->fetch_array($PQuery)) {
						  if ($PRs['ifopen']==1)
						  	echo "<span class='red_small'><i class='icon-check'></i></span> ";
						  else
						    echo "<i class='icon-check-empty'></i> ";
						  echo $PRs['methodname'];
						  
						  echo  "&nbsp;&nbsp;";
					  }
					  }
					  ?>  
                                </TD>
                              </TR></TBODY>
                            <?php
					$i++;
					}
					?>
                            <TR>
                              <TD align=middle width=80 height=14>&nbsp;</TD>
                              <TD width=114 height=14>&nbsp;</TD>
                              <TD width=94 height=14>&nbsp;</TD>
                              <TD width=94>&nbsp;</TD>
                              <TD width=87>&nbsp;</TD>
                              <TD>&nbsp;</TD>
                              </TR>
                        </TABLE>
                        </FORM>
                      </TD>
                    </TR>
                </TABLE>
              
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                      </TD>
                    </TR>
      </TABLE></TD></TR></TABLE></TD>
    </TR>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
