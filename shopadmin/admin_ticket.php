<?php
include_once "Check_Admin.php";
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

if ($_GET['ticketid']!="" && $_GET['Action']=='Modi'){
	$id = intval($_GET['ticketid']);
	$Action_value = "Update";
	$UserNameAction = " disabled ";
	$Action_say  = "修改折價券"; //修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}ticket` where ticketid=".intval($id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$ticketname       =  $Result['ticketname'];
		$ticketcode       =  $Result['ticketcode'];
		$money      =  $Result['money'];
		$pub_starttime     =  $Result['pub_starttime'];
		$pub_endtime     =  $Result['pub_endtime'];
		$use_starttime     =  $Result['use_starttime'];
		$use_endtime     =  $Result['use_endtime'];
		$ticketid       =  $Result['ticketid'];
		$type       =  $Result['type'];
		$moneytype       =  $Result['moneytype'];
		$goods_ids       =  $Result['goods_ids'];
		$ordertotal       =  $Result['ordertotal'];
		$canmove       =  $Result['canmove'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$UserNameAction = "  ";
	$Action_say   = "新增折價券"; //添加
	$reg_date     = date("Y-m-d",time());
	$reg_ip       = $FUNCTIONS->getip();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>行銷工具--&gt;電子折價券管理--&gt;<?php echo $Action_say;?></TITLE>
</HEAD>
<script language="javascript" src="../js/function.js"></script>
<?php if ( VersionArea == "gb" ) {
 	$Onload =  " onLoad=\"createMenus('".$city."','".$canton."','','')\"  ";
 }else{
 	$Onload =  " onload=\"addMouseEvent();\"";
 }
 ?>
<SCRIPT language=javascript>
	function checkform(){
		if (chkblank(form1.ticketname.value) || form1.ticketname.value.length>20){
			alert('請輸入折價券名稱'); //請輸入帳號！!
			form1.ticketname.focus();
			return;
		}
		if(form1.moneytype0.checked==true && form1.money.value<1){
			alert('金額折抵不能小於1元'); //請輸入帳號！!
			form1.ticketname.focus();
			return;
		}
		form1.submit();
}
</script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  <?php echo  $Onload ?> >
<?php include_once "head.php";?>
<div id="contain_out">
  <?php include_once "Order_state.php";?>
  <FORM name=form1 action='admin_ticket_save.php' method=post >
    <input type="hidden" name="Action" value="<?php echo $Action_value?>">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
      <TBODY>
        <TR>
          <TD width="50%"><table width="90%" border=0 cellpadding=0 cellspacing=0>
              <tbody>
                <tr>
                  <td width=38><img height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32 /></td>
                  <td class=p12black nowrap><span  class=p9orange>行銷工具--&gt;電子折價券管理--&gt;<?php echo $Action_say;?></span></td>
                </tr>
              </tbody>
            </table></TD>
          <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  <TD align=middle><TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD>
                                </TR>
                              </TBODY>
                            </TABLE>
                            <!--BUTTON_END--></TD>
                        </TR>
                      </TBODY>
                    </TABLE></TD>
                  <TD align=middle><TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap  class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                                </TR>
                              </TBODY>
                            </TABLE>
                            <!--BUTTON_END--></TD>
                        </TR>
                      </TBODY>
                    </TABLE></TD>
                </TR>
              </TBODY>
            </TABLE></TD>
        </TR>
      </TBODY>
    </TABLE>
    <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
      <TBODY>
        <TR>
          <TD noWrap align=right width="18%">&nbsp;</TD>
          <TD align=right noWrap>&nbsp;</TD>
        </TR>
        <TR>
          <TD noWrap align=right> 折價券名稱： </TD>
          <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','ticketname',$ticketname,"  maxLength=20 size=20  ")?></TD>
        </TR>
        <?php if ($_GET['ticketid']!="" && $_GET['Action']=='Modi'){  ?>
      <input type="hidden" name="ticketid" value="<?php echo $ticketid?>">
      <?php  } ?>
      <TR>
        <TD noWrap align=right>適用商品：</TD>
        <TD align=left noWrap><textarea name="goods_ids" id="goods_ids" cols="45" rows="5"><?php echo $goods_ids?></textarea>
          請填寫商品ID，多個商品ID用半形逗號“,”分隔</TD>
      </TR>
      <TR>
        <TD height="6" align=right valign="top" noWrap>抵用金額/百分比：</TD>
        <TD align=left valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','money',$money," maxLength=20 size=20 ")?>舉例：打八折請輸入0.2</TD>
      </TR>
      <TR>
        <TD height="6" align=right valign="top" noWrap>&nbsp;</TD>
        <TD align=left valign="top" noWrap><i class="icon-warning-sign" style="font-size:16px;color:#C00"></i> 折使用%百分比折扣無法限制購買商品，訂單將全部一起折扣!</TD>
      </TR>
      <TR>
        <TD height="11" align=right valign="top" noWrap>折抵門檻：</TD>
        <TD align=left valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','ordertotal',$ordertotal,"  maxLength=20 size=10  ")?></TD>
      </TR>

      <TR>
        <TD noWrap align=right>轉讓形式：</TD>
        <TD align=left noWrap><input name="canmove" id="canmove" type="radio" value="0" <?php if ($canmove== 0){?>checked="checked"<?php }?> />
不可轉讓
  <input type="radio" name="canmove" id="canmove" value="1" <?php if ($canmove == 1){?>checked="checked"<?php }?>/>
可轉讓</TD>
      </TR>
      <TR>
        <TD noWrap align=right>折價券類類別：</TD>
        <TD align=left noWrap><input name="moneytype" id="moneytype0" type="radio" value="0" <?php if ($moneytype== 0){?>checked="checked"<?php }?> />
          金額抵用
          <input type="radio" name="moneytype" id="moneytype1" value="1" <?php if ($moneytype == 1){?>checked="checked"<?php }?>/>
          百分比抵用</TD>
      </TR>
      <TR>
        <TD noWrap align=right>折價券形式：</TD>
        <TD align=left noWrap><input name="type" type="radio" value="0" <?php if ($type == 0){?>checked="checked"<?php }?> />
          電子折價券（發放給站內會員）
          <input type="radio" name="type" value="1" <?php if ($type == 1){?>checked="checked"<?php }?> />
          通用折價券（離線）</TD>
      </TR>
      <TR>
        <TD noWrap align=right>發放日期                        ： </TD>
        <TD align=left noWrap><INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=begtime size=10  onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $pub_starttime;?>' name='pub_starttime'>
          --
          <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=endtime size=10   onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $pub_endtime;?>' name='pub_endtime'></TD>
      </TR>
      <TR>
        <TD noWrap align=right>使用日期：</TD>
        <TD align=left noWrap><INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=begtime size=10  onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $use_starttime;?>' name='use_starttime'>
          --
          <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=endtime size=10   onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $use_endtime;?>' name='use_endtime'></TD>
      </TR>
      <TR>
        <TD colspan="2" align=right noWrap>&nbsp;</TD>
      </TR>
    </TABLE>
  </FORM>
</div>
<div align="center">
  <?php include_once "botto.php";?>
</div>
</BODY>
</HTML>
