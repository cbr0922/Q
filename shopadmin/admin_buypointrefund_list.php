<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

include_once Classes . "/Time.class.php";
$TimeClass = new TimeClass;

$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()-7*24*60*60);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());
$begtimeunix  = $TimeClass->ForYMDGetUnixTime($begtime,"-");
$endtimeunix  = $TimeClass->ForYMDGetUnixTime($endtime,"-")+60*60*24;
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
if($_GET['user_id']!="")
	$subsql = " and bp.user_id='" . intval($_GET['user_id']) . "'";
if($_GET['username']!=""){
	$subsql .= " and (u.username like '%" . $_GET['username'] . "%' or u.true_name like '%" . $_GET['username'] . "%')";
}
if($_GET['state']!=""){
	$subsql .= " and bp.state='" . intval($_GET['state']) . "' ";
}

$Sql      = "select bp.*,u.username,u.true_name,u.memberno,o.order_serial from `{$INFO[DBPrefix]}buypointrefund` as bp inner join `{$INFO[DBPrefix]}user` as u on bp.u_id=u.user_id left join `{$INFO[DBPrefix]}order_table` as o on o.order_id=bp.order_id where 1=1 " . $subsql . "  and bp.refundtime>='$begtimeunix' and bp.refundtime<='$endtimeunix' order by bp.refundtime desc";

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
<TITLE>訂單管理--&gt;退款記錄管理</TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width="100%" height=302><TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38 height="48"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange>訂單管理--&gt;退款記錄管理</SPAN>
                      </TD>
                </TR></TBODY></TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=form1 id=form1 method=get action="">
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=left colSpan=2 height=41 class="p9black">
              申請時間：
              From
              <INPUT   id=begtime3 size=10 value=<?php echo $begtime?>    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />
              To
              <INPUT    id=endtime3 size=10 value=<?php echo $endtime?>      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />
              會員帳號：
              <INPUT value='<?php echo $_GET['username'];?>'   size='15'  name='username'>
              狀態：
              <select name="state" id="state">
                <option value="0">申請</option>
                <option value="1">退款成功</option>
                <option value="2">申請失敗</option>
                </select>

              <input  type='image' src="images/<?php echo $INFO[IS]?>/t_go.gif" border='0' name='imageField' /></TD>
            <TD class=p9black align=right width=147 height=31><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.form1.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
            </TR>
          </FORM>
        <script language="javascript">
		 function checkpointform(myform){
			 if(myform.username.value==""){
						alert("請填寫會員帳號");
						return false;
					}
				 if(myform.content.value==""){
						alert("請填寫原因");
						return false;
					}
			}
		 </script>
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
                              <TD width="54" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>訂單號</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員編號</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>姓名</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="head">申請退款額</span></TD>
                              <!--<TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>帳上餘額</TD>-->
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>退款方式</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>轉帳銀行</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="head">銀行代號</span></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="head">帳號</span></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="head">戶名</span></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="head">狀態</span></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="head">備註</span></TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>操作</TD>
                              </TR>
                            <?php
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {
					?>
                            <TR class=row0>
                              <TD width=54 height=26 align=center>
                                <?php echo $Rs['br_id']?></TD>
                              <TD align="left" noWrap><a href="admin_order.php?order_id=<?php echo $Rs['order_id'];?>"><?php echo $Rs['order_serial'];?></a>&nbsp;</TD>
                              <TD align="left" noWrap><?php
					  echo $Rs['memberno'] ;
					  ?></TD>
                              <TD align="left" noWrap>
                                <?php
					  echo $Rs['username'] ;
					  ?>
                                </TD>
                              <TD align="left" noWrap>
                                <?php
					  echo $Rs['true_name'];
					  ?>
                                </TD>
                              <TD height=26 align="left" noWrap>
                                <?php
					  echo $Rs['point'];
					  ?>
                                </TD>
                              <!--<TD align="left" noWrap>
                                <--?php
					  echo $FUNCTIONS->Buypoint(intval($Rs['u_id']),1);
					  ?>
                                </TD>-->
                              <TD align="left" noWrap>
                                <?php
                      if($Rs['refundtype']==0)
					  	echo "帳上餘額";
					  elseif($Rs['refundtype']==1)
					  	echo "銀行帳戶";
					  elseif($Rs['refundtype']==2)
					  	echo "退刷";
					  ?>
                                </TD>
                              <TD align="left" noWrap>
                                <?php if($Rs['state']==0){?>
                                <input name="bank" type="text" id="bank<?php echo $Rs['br_id']?>" value="<?php echo $Rs['bank'];?>" size="10">
                                <?php
					  }else{
					  	echo $Rs['bank'];
					  }
					  ?></TD>
                              <TD align="left" noWrap>
                                <?php if($Rs['state']==0){?>
                                <input name="bankcode" type="text" id="bankcode<?php echo $Rs['br_id']?>" value="<?php echo $Rs['bankcode'];?>" size="5">
                                <?php
					  }else{
					  echo $Rs['bankcode'];
					  }
					  ?>
                                </TD>
                              <TD align="left" noWrap>
                                <?php if($Rs['state']==0){?>
                                <input name="account" type="text" id="account<?php echo $Rs['br_id']?>" value="<?php echo $Rs['account'];?>" size="10">
                                <?php
					  }else{
					  echo $Rs['account'];
					  }
					  ?></TD>
                              <TD align="left" noWrap>
                                <?php if($Rs['state']==0){?>
                                <input name="acountname" type="text" id="acountname<?php echo $Rs['br_id']?>" value="<?php echo $Rs['acountname'];?>" size="3">
                                <?php
					  }else{
					  echo $Rs['acountname'];
					  }
					  ?></TD>
                              <TD align="left" noWrap>
                                <?php
                      if($Rs['state']==0)
					  	echo "申請";
					  elseif($Rs['state']==1)
					  	echo "退款成功";
					  elseif($Rs['state']==2)
					  	echo "申請失敗";
					  ?>
                                </TD>
                              <TD align="left" noWrap><?php if($Rs['state']==0){?><textarea name="remark<?php echo $Rs['br_id']?>" id="remark<?php echo $Rs['br_id']?>" cols="15" rows="2"><?php echo $Rs['remark'];?></textarea><?php }else{echo $Rs['remark'];}?></TD>
                              <TD align="left" noWrap><?php if($Rs['state']==0){?><input type="button" name="button" id="button" value="退款成功" onClick="checkRefund(<?php echo $Rs['br_id']?>,1);">
                                <input type="button" name="button2" id="button2" value="申請失敗" onClick="checkRefund(<?php echo $Rs['br_id']?>,2);"><?php }?></TD>
                              </TR>
                            <?php
					$i++;
					}
					?>
                            <TR>
                              <TD height=14 align=middle>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021 height=14>&nbsp;</TD>
                              <!--<TD width=1021>&nbsp;</TD>-->
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              <TD width=1021>&nbsp;</TD>
                              </TR>
                            </FORM>
                        </TABLE>					 </TD>
                    </TR>
                  </TABLE>

              <?php if ($Num>0){ ?>
              <table class=p9gray cellspacing=0 cellpadding=0 width="100%"    border=0>
                <tbody>
                  <tr>
                    <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav()?> </td>
                    </tr>
                  <?php } ?>
                  </table></TD>
        </TR></TABLE></TD>
    </TR>
  <TR>
</TABLE>
<script language="javascript">
function checkRefund(id,type){
	var ajaxurl = "admin_buypointrefund_save.php";
	$.ajax({
				url: ajaxurl,
				data: 'br_id=' + id + '&type=' + type + '&remark=' + $('#remark' + id).val() + '&bank=' + $('#bank' + id).attr('value') + '&bankcode=' + $('#bankcode' + id).attr('value') + '&account=' + $('#account' + id).attr('value') + '&acountname=' + $('#acountname' + id).attr('value'),
				type:'post',
				dataType:"html",
				success: function(msg){
				//alert(msg);
				    window.location.reload(true);
					//$('#classcount').attr("value",counts+1);
					//$(msg).appendTo('#extclass')
				}
	});
}
</script>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
