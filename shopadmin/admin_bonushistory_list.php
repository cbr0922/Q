<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Good.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";



include_once "pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);


$Where   =  trim($_GET['State'])=="Noop" ? " where bg.sidate=''  " : "";

$Sql      = "select bg.*,u.member_point from `{$INFO[DBPrefix]}bonuscoll_goods` bg  left join  `{$INFO[DBPrefix]}user` u on (bg.user_id=u.user_id )  ".$Where."   order by bg.idate desc ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}

if ($_POST['act']=='Del' ) {

	$Array_bid =  $_POST['cid'];
	$Num_bid  = count($Array_bid);

	for ($i=0;$i<$Num_bid;$i++){
		$Result =  $DB->query("delete  from `{$INFO[DBPrefix]}bonuscoll_goods`  where abc_id=".intval($Array_bid[$i]));
	}
	$FUNCTIONS->setLog("刪除紅利兌換");

	$FUNCTIONS->header_location('admin_bonushistory_list.php');

}
?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Bonus_History_List];//红利兑换单?></TITLE>
</HEAD>
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

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_bonushistory_list.php";
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
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=302></TD>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Bonus_History_List];//红利兑换单?></SPAN>				</TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%">
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                <?php if ($Ie_Type != "mozilla") { ?>
				  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="javascript:toDel();">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE>
				 <?php } else {?> 
				 <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE>
				 <?php } ?>
				</TD>
                <TD align=middle></TD>
					</TR>
				 </TBODY>
				</TABLE>
			</TD>
		  </TR>
		  </TBODY>
		</TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=optForm method=get action="">        
		<input type="hidden" name="Action" value="Search">
        <TR>
          <TD align=right colSpan=2 height=31>&nbsp;</TD>
           <TD class=p9black align=right height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>  
  		    <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=p9black onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
		 </TR>
		 </FORM>
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
					 <INPUT type=hidden value=0  name=boxchecked> 
                    <TBODY>
                    <TR align=middle>
                      <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
					  <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                      <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Basic_Command['SNo_say'];//序号?></TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Good[Product_Name];//商品名?></TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[NeedBonusNum];//商品所需積分?></TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[TrueName];//真實姓名?></TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Admin_Member[MemberBonusNum];//会员积分?></TD>
                      <TD height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Status];//状态?></TD>
                      <TD width="106" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Admin_Member[MemberSayToSystemTime];//提问时间?></TD>
                    </TR>
					<?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {

						switch ($Rs['askfor'])
						{
							case 1:
								$Askfor  = $Good[HaveGetBonusShenQing_PleaseWaiting];//已成功提交需求红利商品申请！等待系统审核中.....!
								break;
							case 2:
								$Askfor  = $Good[SystemCancelBonusShengQing];//系统取消了本条需求红利商品申请！
								break;
							case 3:
								$Askfor  = $Good[SystemPassBonusShengQing];//系统已接受本条需求红利商品申请！
								break;
							default:
								$Askfor  = $Good[HaveGetBonusShenQing_PleaseWaiting];//已成功提交需求红利商品申请！等待系统审核中.....!
								break;
						}
					  ?>
			
                    <TR class=row0>
                      <TD align=middle height=26>
					  <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['abc_id']?>' name=cid[]></TD>
                      <TD width="59" height=26 align="center" noWrap>                        
                        <?php echo $j?>                        </TD>
                      <TD height=26 align="left" noWrap><a href="admin_goods.php?Action=Modi&gid=<?php echo intval($Rs['goods_id'])?>"><?php echo $Rs['goodsname']?></a></TD>
                      <TD height="26" align="center" noWrap><?php echo $Rs['bonusnum']?></TD>
                      <TD height=26 align="center" noWrap><a href="admin_member.php?Action=Modi&user_id=<?php echo intval($Rs['user_id'])?>"><?php echo $Rs['username']?></a>&nbsp;[ID:<?php echo $Rs['user_id']?>]</TD>
                      <TD height="26" align="center" noWrap><?php echo $Rs['member_point']?>&nbsp;</TD>
                      <TD height=26 align="center" noWrap>
					  <A  href="javascript:temp=window.open('adminAffirmBonus.php?abc_id=<?php echo $Rs[abc_id]?>','shopcat','width=520,height=480,scrollbars=yes');temp.focus()"><?php echo $Askfor?></a>                      </TD>
                      <TD height=26 align="center" noWrap><?php echo date("Y-m-d H:i a",$Rs['idate'])?></TD>
                    </TR>
					<?php
					$j++;
					$i++;
					}
					?>
                    <TR>
                      <TD align=middle width=20 height=14>&nbsp;</TD>
                      <TD width=59 height=14>&nbsp;</TD>
                      <TD height=14>&nbsp;</TD>
                      <TD>&nbsp;</TD>
                      <TD width=212 height=14>&nbsp;</TD>
                      <TD height=14 colspan="3">&nbsp;</TD>
                    </TR>
          <?php  if ($Num==0){ ?>
                    <TR align="center">
                      <TD height=14 colspan="8"><?php echo $Basic_Command['NullDate']?></TD>
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
