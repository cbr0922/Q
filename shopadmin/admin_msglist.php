<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Sql      = "select * from `{$INFO[DBPrefix]}sendmsg` ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num>0){
	$limit = 20;
	$Nav->total_result = $Num;
	$Nav->execute($Sql,$limit);
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<HTML>
<META http-equiv=ever content=no-cache><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2600.0" name=GENERATOR>
<TITLE>設置-->簡訊管理-->簡訊內容</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toEdit(id,catid){
	var checkvalue;	
	if (id == 0) {
		checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_msg.php?sendtype_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}
</SCRIPT>

<div id="contain_out">
  <?php  include_once "Order_state.php";?>
  <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
<TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange>設置--&gt;簡訊管理--&gt;簡訊內容</SPAN></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              
              </TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD align="right">
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                                    <TD vAlign=bottom noWrap class="link_buttom">
                            <a href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                          <TBODY>
                            <TR align=middle>
                              <TD width="126" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle></TD>
                              <TD width="509"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
                                <?php echo $Mail_Pack[MailType] ;//邮件类型?></TD>
                              <TD width="411"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <?php echo $Basic_Command['IfCloseOrOpen'];//是否關閉?></TD>
                              </TR>
                            <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Nav->sql_result)) {


					?><TBODY>
                            <TR class=row0>
                              <TD align=center height=20>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['sendtype_id']?>' name=cid[]></TD>
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['sendtype_id']?>',0);"><?php echo $FUNCTIONS->sendtype(trim($Rs['sendtype']));?></A></TD>
                              <TD height=26 align=center nowrap>
                                <?php echo  $Status =  intval($Rs['sendstatus'])==0 ? $Basic_Command['Close']   : $Basic_Command['Open']; ?></TD>
                              </TR></TBODY>
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
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                      </TD>
                    </TR>
      </TABLE></TD></TR></TABLE>
</div>
</BODY></HTML>
