<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
if($_GET['user_id']!="")
	$subsql = " and bp.user_id='" . intval($_GET['user_id']) . "'";
if($_GET['username']!=""){
	$subsql .= " and (u.username like '%" . $_GET['username'] . "%' or u.true_name like '%" . $_GET['username'] . "%')";
}
if(intval($_GET['state'])>=0 && $_GET['state']!=""){
	$subsql .= " and bp.usestate ='" . intval($_GET['state']) . "'";
}
if(intval($_GET['timestate'])==0 && $_GET['timestate']!=""){
	$subsql .= " and bp.addtime <='" . time() . "' and bp.endtime >='" . time() . "'";
}
if(intval($_GET['timestate'])==1 && $_GET['timestate']!=""){
	$subsql .= " and (bp.addtime >='" . time() . "' or bp.endtime <='" . time() . "')";
}
if(intval($_GET['type'])>=0 && $_GET['type']!=""){
	$subsql .= " and bp.type ='" . intval($_GET['type']) . "'";
}
if($_GET['order_serial']!=""){
	$subsql .= " and o.order_serial like '%" . $_GET['order_serial'] . "%' ";
}
$Sql      = "select bp.*,bt.typename,u.username,u.true_name from `{$INFO[DBPrefix]}bonuspoint` as bp inner join `{$INFO[DBPrefix]}bonustype` as bt on bp.type=bt.typeid inner join `{$INFO[DBPrefix]}user` as u on bp.user_id=u.user_id left join `{$INFO[DBPrefix]}order_table` as o on o.order_id=bp.orderid where bp.saleorlevel=1  " . $subsql . " order by bp.id desc";

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
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>客戶關係--&gt;紅利管理--&gt;紅利記錄</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
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
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_ticketcode_save.php?Action=Modi&ticketid="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_ticketcode_save.php";
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
                    <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange>客戶關係--&gt;紅利管理--&gt;紅利記錄</SPAN>
                    </TD>
                </TR></TBODY></TABLE></TD>
            
          </TR>
        </TBODY>
  </TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        
          <TR>
            <TD align=left colSpan=2 height=31 class="p9black">
            <FORM name=form1 id=form1 method=get action="">        
          <input type="hidden" name="Action" value="Search">
            會員帳號：
              <INPUT value='<?php echo $_GET['username'];?>'   size='15'  name='username'>
              訂單編號：
              <INPUT value='<?php echo $_GET['order_serial'];?>'   size='10'  name='order_serial'>
              交易類型：
              <select name="type" id="type">
                <option value="-1">請選擇</option>
                <?php
            $t_sql = "select * from `{$INFO[DBPrefix]}bonustype`";
			$t_Query    = $DB->query($t_sql);
			while ($t_Rs=$DB->fetch_array($t_Query)) {
				?>
                <option value="<?php echo $t_Rs['typeid'];?>"  <?php if($_GET['type'] == $t_Rs['typeid']) echo "selected";?>><?php echo $t_Rs['typename'];?></option>
                <?php
			}
			?>
              </select>
              使用狀態：
              <select name="state" id="state">
                <option value="-1" <?php if($_GET['state'] == "-1") echo "selected";?>>請選擇</option>
                <option value="0" <?php if($_GET['state'] == "0") echo "selected";?>>未使用</option>
                <option value="1" <?php if($_GET['state'] == "1") echo "selected";?>>部份使用</option>
                <option value="2" <?php if($_GET['state'] == "2") echo "selected";?>>已使用</option>
              </select>
              <select name="timestate" id="timestate">
                <option value="-1" <?php if($_GET['timestate'] == "-1") echo "selected";?>>請選擇</option>
                <option value="0" <?php if($_GET['timestate'] == "0") echo "selected";?>>未過期</option>
                <option value="1" <?php if($_GET['timestate'] == "1") echo "selected";?>>已過期</option>
              </select><input  type='image' src="images/<?php echo $INFO[IS]?>/t_go.gif" border='0' name='imageField' /></FORM></TD>
            <TD width=124 height=31 align=right nowrap class=p9black><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.form1.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
          </TR>
        
        
          <TR>
            <TD align=left colSpan=2 height=31 class="p9black">
            <FORM name=form1 id=form1 method=post action="admin_bonusrecord_save.php">        
            會員帳號：
              <INPUT value=''   size='30'  name='username'>
              紅利：
              <select name="type" id="type">
                <option value="0">增加</option>
                <option value="1">減少</option>
              </select>
              <INPUT value=''   size='10'  name='bonus'>
              <input type="submit" name="button" id="button" value="確定">
             </FORM>
             </TD>
            <TD class=p9black align=right height=31>&nbsp;</TD>
          </TR>
          <TR>
            <TD align=left colSpan=2 height=31 class="p9black">
            <FORM name=form1 id=form1 method=post action="admin_bonusrecord_save.php">
            會員分組：
              <?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}mail_group` where mgroup_id>1 order by mgroup_id asc ",'mgroup_id','mgroup_id','mgroup_name',intval($mgroup_id));?>
              紅利：
              <select name="type" id="type">
                <option value="0">增加</option>
                <option value="1">減少</option>
              </select>
              <INPUT value=''   size='10'  name='bonus'>
              <input type="submit" name="button" id="button" value="確定">
              </FORM>
              </TD>
            <TD class=p9black align=right height=31>&nbsp;</TD>
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
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD width="54" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>內容</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>紅利</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>交易類型</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>使用狀態</TD>
                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>使用期限</TD>
                            </TR>
                            <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {
						$order_serial = "";
						$ticketcode = "";
						$ticketname = "";
						$typename = "";
						$state = "";
						$usestatename = "";
		
		if ($Rs['endtime']<time())
				$state = "[已過期]";
			switch (intval($Rs['usestate'])){
			case 0:
				$usestatename = "未使用" . $state;
				break;
			case 1:
				$u_sql = "select * from `{$INFO[DBPrefix]}bonusbuydetail` where combipoint_id=" . $Rs['id'];
				$u_Query =  $DB->query($u_sql);
				$u_Rs = $DB->fetch_array($u_Query);
				$usepoint = intval($u_Rs['usepoint']);
				
				$usestatename = "部份使用(" . $usepoint . "紅利)" . $state;
				break;
			case 2:
				$usestatename = "已使用";
				break;
		}

					?><TBODY>
                              <TR class=row0>
                                <TD width=54 height=26 align=center>
                                  <?php echo $Rs['id']?></TD>
                                <TD align="left" noWrap>
                                  <?php
					  echo $Rs['username'] . "(" . $Rs['true_name'] . ")";
					  ?>
                                </TD>
                                <TD height=26 align="left" noWrap>
                                  <a href="admin_order.php?Action=Modi&order_id=<?php echo $Rs['orderid']?>"><?php echo $Rs['content'];?></a>
                                </TD>
                                <TD align="left" noWrap><?php echo $Rs['point'];?></TD>
                                <TD align="left" noWrap><?php echo $Rs['typename'];?></TD>
                                <TD align="left" noWrap><?php echo $usestatename;?></TD>
                                <TD align="left" noWrap><?php echo date("Y-m-d",$Rs['addtime'])?>--<?php echo date("Y-m-d",$Rs['endtime'])?></TD>
                              </TR></TBODY>
                          <?php
					$i++;
					}
					?>
                          <TR>
                            <TD height=14 align=middle>&nbsp;</TD>
                            <TD width=1021>&nbsp;</TD>
                            <TD width=1021 height=14>&nbsp;</TD>
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
        </TR></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
