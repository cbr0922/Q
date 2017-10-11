<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Poll_Pack.php";
if ( $_GET[Action]=='Modi' && !empty($_GET[Poll_id])){
	$Action_value = "Update";
	$Poll_id = $FUNCTIONS->Value_Manage($_GET['Poll_id'],$_POST['Poll_id'],'back','');
	//$Query =  $DB->query("select * from poll p inner join poll_option po on (p.poll_id=po.poll_id) where p.poll_id='$Poll_id' ");
	$Sql   = "select * from `{$INFO[DBPrefix]}poll` p  where p.poll_id='$Poll_id' LIMIT 0,1";
	$Query =  $DB->query($Sql);
	$Rs    =  $DB->fetch_array($Query);
	$Poll_id = $Rs[poll_id];
	$Title           =  $Rs[title];
	$PollOptionNum   =  $Rs[polloptionnum];
	$open            =  $Rs[open];
}else{
	$FUNCTIONS->sorry_back("admin_poll_list.php","");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Poll_Man];//投票管理?>--&gt;<?php echo $Poll_Pack[EditPoll];//编辑投票?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<script language="javascript">
function toDel(subpoll_id){
	if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
		document.form1.action = "admin_poll_save.php?Action=DelSubpoll&subpoll_id="+subpoll_id;
		document.form1.Action.value="DelItem";
		//document.form1.Poll_id.value="<?php echo $Poll_id;?>";
		document.form1.submit();
	}
}
</script>
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>
	function checkform(){
		if (form1.title.value == ""){
			alert('<?php echo $Poll_Pack[PleaseInputPollTitle];?>');  //请输入调查题目！
			form1.title.focus();
			return;			
		}
		form1.submit();
	}


</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_poll_save.php' method='post' >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="Poll_id" value="<?php echo $Poll_id?>">
  <input type="hidden" name="PollOptionNum" value="<?php echo $PollOptionNum?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Poll_Man];//投票管理?>--&gt;<?php echo $Poll_Pack[EditPoll];//编辑投票?></SPAN>
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
                                  <TD vAlign=bottom noWrap class="link_buttom">
                            <a href="admin_poll_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
                          </TD></TR></TBODY></TABLE>
                    
                  </TD></TR></TBODY></TABLE>
            </TD>
          </TR>
        </TBODY>
    </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                      
                      <TR>
                        <TD colspan="2" align=right noWrap>&nbsp;</TD>
                        <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>
                      
                      <TR>
                        <TD colspan="2" align=right noWrap><?php echo $Poll_Pack[PleaseInputPollTitle];//请输入调查题目?>：</TD>
                        <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','Title',$Title,"      maxLength=40 size=40 ")?></TD>
                      </TR>
                      
                      <TR>
                        <TD colspan="2" align=right noWrap> <?php echo $Poll_Pack[PleaseInputPollStatus];?>：<!--请输入选项状态--></TD>
                        <TD colspan="2" align=left noWrap>
                          <?php echo $FUNCTIONS->Input_Box('radio','open',intval($open),$add=array($Basic_Command['Open'],$Basic_Command['Close']))?>					  
                        </TD>
                      </TR>
                      <TR>
                        <TD colspan="2" align=right noWrap>&nbsp;</TD>
                        <TD colspan="2" align=left noWrap>&nbsp;</TD>
                      </TR>
                      <?
$Querys =  $DB->query("select * from `{$INFO[DBPrefix]}poll` p inner join `{$INFO[DBPrefix]}poll_option` po on (p.poll_id=po.poll_id) where p.poll_id='$Poll_id' ");
$i=0;
while ($Result =  $DB->fetch_array($Querys)){
?>
                      <TR bgcolor="#FFFFCC">
                        <TD width="1%" align=right noWrap bgcolor="#F7F7F7">&nbsp;</TD>
                        <TD width="11%" align=right noWrap><?php echo $i+1;?>：</TD>
                        <TD width="40%" align=left noWrap bgcolor="#FFFFCC">
                          <input type="text" name="subtitle[]"   maxLength=200 size=60 value="<?php echo $Result['subtitle']?>">
                          &nbsp;
                          <input type="text" name="subtitleNum[]"     maxLength=5 size=5 value="<?php echo $Result['points']?>"><input type="hidden" name="subpoll_id[]"  value="<?php echo $Result['subpoll_id']?>"></TD>
                        <TD width="48%" align=left noWrap bgcolor="#FFFFCC">  <div class="link_box" style="width:60px;text-align:center"><a href="javascript:toDel('<?php echo $Result['subpoll_id']?>');"><?php echo $Basic_Command['Del']?></a></div></TD>
                      </TR>
                      <? 
$i++;
}
?>
                      <TR bgcolor="#FFFFCC">
                        <TD align=right noWrap bgcolor="#F7F7F7">&nbsp;</TD>
                        <TD align=right noWrap><?php echo $Poll_Pack[AddPollItem]?>：</TD>
                        <TD align=left noWrap>
                          <input type="text" name="addsubtitle"   maxLength=200 size=60 id="addsubtitle">
                          <div id="addsubtitletips" class="tips"><?php echo $Poll_Pack[WhatIsAddPollItem]?></div>
                          &nbsp;
                          <input type="text" name="addsubtitleNum"    maxLength=5 size=5></TD>
                        <TD align=left noWrap bgcolor="#FFFFCC">&nbsp;</TD>
                      </TR>
                      
                      <TR>
                        <TD colspan="2" align=right noWrap>&nbsp;</TD>
                        <TD colspan="2" align=left noWrap>&nbsp;</TD>
                      </TR>
                    </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
