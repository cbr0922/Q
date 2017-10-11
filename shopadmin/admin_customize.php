<?php
include_once "Check_Admin.php";
include_once Resources."/ckeditor/ckeditor.php";
/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";


$Query = $DB->query("select * from `{$INFO[DBPrefix]}groupbuy_customize` where gcid=".intval($gcid)." limit 0,1");
$Num   = $DB->num_rows($Query);

if ($Num>0){
	$Result= $DB->fetch_array($Query);
	$budget            =  $Result['budget'];
	$other         =  $Result['other'];
	$needcount            =  $Result['needcount'];
	$arrivaldate            =  $Result['arrivaldate'];
	$username            =  $Result['username'];
	$company            =  $Result['company'];
	$phone            =  $Result['phone'];
	$mobile            =  $Result['mobile'];
	$email            =  $Result['email'];
	$remark            =  $Result['remark'];
	$addtime            =  $Result['addtime'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>商品管理--&gt;團購區管理</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<div id="contain_out">
  <? include "Order_state.php";?>
  <TBODY>
    <TR>
      <TD vAlign=top width="100%" height=319><TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
          <TBODY>
            <TR>
              <TD width="50%"><TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                  <TBODY>
                    <TR>
                      <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                      <TD class=p12black noWrap><SPAN  class=p9orange>商品管理--&gt;團購區管理</SPAN></TD>
                    </TR>
                  </TBODY>
                </TABLE></TD>
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
                                      <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_customize_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD>
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
        <TABLE class=allborder cellSpacing=0 cellPadding=3 width="100%" align=center bgColor=#f7f7f7 border=0>
          <TBODY>
            <TR>
              <TD noWrap align=right width="18%">&nbsp;</TD>
              <TD colspan="2" align=right noWrap>&nbsp;</TD>
            </TR>
            <TR>
              <TD width="18%" align=right noWrap>單盒預算：</TD>
              <TD height="12" colspan="2" align=left noWrap><?php echo $budget . $other;?></TD>
            </TR>
            <TR>
              <TD align="right" valign="middle" noWrap>禮盒需求數量：</TD>
              <TD colspan="2" align="left" valign="middle" noWrap><?php echo $needcount;?></TD>
            </TR>
            <TR>
              <TD align="right" valign="middle" noWrap>商品預計到貨時間：</TD>
              <TD colspan="2" align="left" valign="middle" noWrap><?php echo $arrivaldate;?></TD>
            </TR>
            <TR>
              <TD noWrap align=right> 姓名：</TD>
              <TD colspan="2" align=left noWrap><?php echo $username;?></TD>
            </TR>
            <TR>
              <TD noWrap align=right> 公司名稱：</TD>
              <TD colspan="2" align=left noWrap><?php echo $company;?></TD>
            </TR>
            <TR>
              <TD noWrap align=right> 市內電話：</TD>
              <TD colspan="2" align=left noWrap><?php echo $phone;?></TD>
            </TR>
            <TR>
              <TD noWrap align=right> 手機號碼：</TD>
              <TD colspan="2" align=left noWrap><?php echo $mobile;?></TD>
            </TR>
            <TR>
              <TD noWrap align=right> 電子信箱：</TD>
              <TD colspan="2" align=left noWrap><?php echo $email;?></TD>
            </TR>
            <TR>
              <TD noWrap align=right> 備註：</TD>
              <TD colspan="2" align=left noWrap><?php echo $remark;?></TD>
            </TR>
            <TR>
              <TD noWrap align=right> 提交時間：</TD>
              <TD colspan="2" align=left noWrap><?php echo date("Y-m-d H:i:s",$addtime);?></TD>
            </TR>
            <TR>
              <TD noWrap align=right>&nbsp;</TD>
              <TD colspan="2" align=left noWrap>&nbsp;</TD>
            </TR>
          </TBODY>
        </TABLE>
</div>
<div align="center">
  <?php include_once "botto.php";?>
</div>
</BODY>
</HTML>
