<?php
include "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Poll_Pack.php";
$Action_value = "Insert_one";

//如果第一次建立主题部分为真
if ($_POST[Action]=="Insert_one" && intval($_POST[PollOptionNum])>0 ){
	$Action_value = "Insert_two";
	$Title           =  trim($_POST[title]);
	$PollOptionNum   =  intval($_POST[PollOptionNum]);
	$open            =  intval($_POST[open]);
}

//确定建立本主题
if ($_POST[Action]=="Insert_two" && intval($_POST[PollOptionNum])>0 ){
	$Title           =  trim($_POST[Title]);
	$PollOptionNum   =  intval($_POST[PollOptionNum]);
	$open            =  intval($_POST[open]);
	$subtitle        =  $_POST[subtitle];
	$subtitleNum     =  $_POST[subtitleNum];

	//写数据库
	$Idate = time();
	$DB->query(" insert into `{$INFO[DBPrefix]}poll` (title,polloptionnum,open,idate) values ('".strip_tags($Title)."','".$PollOptionNum."','".$open."','".$Idate."')");
	$Query =  $DB->query("select poll_id from `{$INFO[DBPrefix]}poll` where idate='$Idate' and title='$Title' limit 0,1");
	$Rs    =  $DB->fetch_array($Query);
	$Poll_id = $Rs[poll_id];

	$Nums = count($subtitle);
	for($i=0;$i<$Nums;$i++){
		$subtitledetail    = trim(str_replace("'","",$subtitle[$i]));
		$subtitleNumdetail      = intval($subtitleNum[$i]);
		$Sql = "insert into `{$INFO[DBPrefix]}poll_option` (subtitle,points,poll_id) values ('$subtitledetail','$subtitleNumdetail','$Poll_id')";
		$DB->query($Sql);
	}
	$FUNCTIONS->setLog("新增線上投票");
	$FUNCTIONS->sorry_back("admin_poll_list.php","");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Poll_Man];//投票管理?>--&gt;<?php echo $JsMenu[Poll_Add];//添加投票?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		if (form1.title.value == ""){
			alert('<?php echo $Poll_Pack[PleaseInputPollTitle];?>');  //请输入调查题目！
			form1.title.focus();
			return;			
		}
	   if (form1.PollOptionNum.value == "" && "<? echo $_POST[Action] ?>"=="Insert_one"){
			alert('<?php echo $Poll_Pack[PleaseInputPollItemNum];?>');//"请输入选项数量
			form1.PollOptionNum.focus();
			return;			
		}
		form1.submit();
	}

	function checkformgo(){
	    //document.form1.action="admin_poll_save.php";
		document.form1.submit();
	}	
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='' method='post' >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="Title" value="<?php echo $Title?>">
  <input type="hidden" name="PollOptionNum" value="<?php echo $PollOptionNum?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Poll_Man];//投票管理?>--&gt;<?php echo $JsMenu[Poll_Add];//添加投票?></SPAN>
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
                                  <TD vAlign=bottom noWrap class="link_buttom">							
                                    <a href=<?php if ($Action_value == "Insert_one") {  echo "\"javascript:checkform();\""; } elseif ($Action_value == "Insert_two"){ echo "\"javascript:checkformgo();\"" ; }?>><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
                          </TD></TR></TBODY></TABLE>
                    
                  </TD></TR></TBODY></TABLE>
            </TD>
          </TR>
        </TBODY>
</TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                      
                      <TR>
                        <TD colspan="2" align=right noWrap>&nbsp;</TD>
                        <TD colspan="3" align=right noWrap>&nbsp;</TD></TR>
                      <?php  if ($Action_value == "Insert_one" ) { ?>			   
                      <TR>
                        <TD colspan="2" align=right noWrap><?php echo $Poll_Pack[PleaseInputPollTitle];//请输入调查题目?>：</TD>
                        <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','title','',"      maxLength=40 size=40 ")?></TD>
                        </TR>
                      
                      <TR>
                        <TD colspan="2" align=right noWrap><?php echo $Poll_Pack[PleaseInputPollItemNum];?>：<!--请输入选项数量--></TD>
                        <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','PollOptionNum','',"      maxLength=10 size=10 ")?>
                          <div id="PollOptionNumtips" class="tips"><?php echo $Poll_Pack[WhatIsPollNum]?></div>
                          
                          </TD>
                        </TR>
                      <TR>
                        <TD colspan="2" align=right noWrap> <?php echo $Poll_Pack[PleaseInputPollStatus];?><!--请输入选项状态-->：</TD>
                        <TD colspan="3" align=left noWrap>
                          <?php echo $FUNCTIONS->Input_Box('radio','open',intval($open),$add=array($Basic_Command['Open'],$Basic_Command['Close']))?>					  </TD>
                        </TR>
                      <?php } ?>			
                      <?php  if ($Action_value == "Insert_two" && $PollOptionNum > 0 ) { ?> 
                      <TR>
                        <TD colspan="2" align=right noWrap><?php echo $Poll_Pack[PoolTitle_say];//调查题目?>：</TD>
                        <TD colspan="3" align=left noWrap><?php echo $Title?></TD>
                        </TR>
                      
                      <TR>
                        <TD colspan="2" align=right noWrap><?php echo $Poll_Pack[PollItemNum];?>：<!--选项数量--></TD>
                        <TD colspan="3" align=left noWrap><?php echo $PollOptionNum?></TD>
                        </TR>
                      <TR>
                        <TD colspan="2" align=right noWrap> <?php echo $Poll_Pack[PollItemStatus];?><!--选项状态-->：</TD>
                        <TD colspan="3" align=left noWrap>
                          <?php echo $FUNCTIONS->Input_Box('radio','open',intval($open),$add=array($Basic_Command['Open'],$Basic_Command['Close']))?>					  </TD>
                        </TR>			
                      <TR bgcolor="#F7F7F7">
                        <TD colspan="2" align=right noWrap>&nbsp;</TD>
                        <TD colspan="3" align=left noWrap>&nbsp;</TD>
                        </TR>
                      <TR bgcolor="#999999">
                        <TD width="4%" align=right noWrap bgcolor="#F7F7F7"></TD>
                        <TD width="15%" align=right noWrap bgcolor="#0099FF">
                          <font color="#FFFFFF"> <?php echo $Poll_Pack[PleaseInputPollItemName]?>：</font></TD>
                        <TD width="49%" align=left noWrap bgcolor="#0099FF">
                          <font color="#FFFFFF"> <?php echo $Poll_Pack[PollItemName]?></font></TD>
                        <TD width="29%" align=left noWrap bgcolor="#0099FF">&nbsp;
                          <font color="#FFFFFF"> <?php echo $Poll_Pack[PollDetailNum]?></font></TD>
                        <TD width="3%" align=left noWrap bgcolor="#F7F7F7">&nbsp;</TD>
                        </TR>		
                      
                      <?php for ($i=0;$i<$PollOptionNum;$i++) { ?>
                      <TR bgcolor="#FFFFCC">
                        <TD align=right noWrap bgcolor="#F7F7F7">&nbsp;</TD>
                        <TD align=right noWrap><?php echo intval($i+1)?>
                          ：</TD>
                        <TD align=left noWrap>
                          <input type="text" name="subtitle[]"   maxLength=200 size=60 id="subtitle<?php echo $i;?>">
                          <div id="subtitle<?php echo $i ;?>tips" class="tips"><?php echo $Poll_Pack[WhatIsPollItemFormat]?></div>	
                          <div id="subtitleNum<?php echo $i ;?>tips" class="tips"><?php echo $Poll_Pack[WhatIsPollNum]?></div>	
                          &nbsp;&nbsp;</TD>
                        <TD align=left noWrap>&nbsp;
                          <input type="text" name="subtitleNum[]" maxLength=5 size=5 id="subtitleNum<?php echo $i;?>"></TD>
                        <TD align=left noWrap bgcolor="#F7F7F7">&nbsp;</TD>
                        </TR>
                      <?php } ?>		
                      <?php } ?> 				
                      <TR>
                        <TD colspan="2" align=right noWrap>&nbsp;</TD>
                        <TD colspan="3" align=left noWrap>&nbsp;</TD>
                        </TR>
                    </TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
