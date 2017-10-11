<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
//include "../language/".$INFO['IS']."/MemberLanguage_Pack.php";
/*
if ($_GET['Year']){
	if ($_GET['Year']!="" && $_GET['Month']!="" && $_GET['Day']!=""){
		$birthday_start = $birthday_end = $_GET['Year'] . "-" . $_GET['Month'] . "-" . $_GET['Day'];
	}else if($_GET['Year']!="" && $_GET['Month']!="" && $_GET['Day']==""){
		$birthday_start = $_GET['Year'] . "-" . $_GET['Month'] . "-01";
		$birthday_end = $_GET['Year'] . "-" . $_GET['Month'] . "-31";
	}else if($_GET['Year']!="" && $_GET['Month']=="" && $_GET['Day']==""){
		$birthday_start = $_GET['Year'] . "-01-01";
		$birthday_end = $_GET['Year'] . "-12-31";
	}
	$Date_string = " born_date >='".$birthday_start."' and  born_date <='".$birthday_end."' ";
	$Create_Sql = $FUNCTIONS->CreateSql("",$Date_string);
}

if (intval($_GET['companyid']) > 0){
	$Date_string = " companyid ='".intval($_GET[companyid])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql("",$Date_string);
}
if ($_GET['Month']){
	$Date_string = " MONTH(born_date) ='".intval($_GET[Month])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql("",$Date_string);
}
if ($_GET['Sex']!=""){
	$Sex_string = " sex='".intval($_GET[Sex])."' ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Sex_string);
}*/
/*
if ($_GET[allcity]!=1 && $_GET[province]!=""){
	$Area_string = "  city='".trim($_GET[province])."' ";
	if ($_GET[city]!=""){
		$Area_string .= "  and canton='".trim($_GET[city])."'  ";
	}
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}

if($_GET[county]!="" && $_GET[county]!="請選擇"){
	$Area_string = "   Country='".trim($_GET[county])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
if($_GET[province]!="" && $_GET[province]!="請選擇"){
	$Area_string = "   canton='".trim($_GET[province])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
if($_GET[city]!="" && $_GET[city]!="請選擇"){
	$Area_string = "   city='".trim($_GET[city])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
if($_GET[begtime]!=""){
	$begin_string = "   reg_date>='".trim($_GET[begtime])."'  ";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$begin_string);
}
if($_GET[endtime]!=""){
	$end_string = "   reg_date<='".trim($_GET[endtime])."'  ";
	 $Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$end_string);
}
if ($_GET['skey']){
	if ($_GET['keyname']=='tel')
	$key_string = "(u.other_tel like '%".trim(urldecode($_GET['skey']))."%' or u.tel like '%".trim(urldecode($_GET['skey']))."%')";
	else
	$key_string = "u." . $_GET['keyname'] . " like '%".trim(urldecode($_GET['skey']))."%'";
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$key_string);
}*/
/*
$money_string = " total>=" . intval($_GET['startmoney']);
if (intval($_GET['startmoney'])>0){
	$moneys_string = " ot.total>=" . intval($_GET['startmoney']);
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$moneys_string);
}

if (intval($_GET['endmoney'])>0){
	$money_string .= " and total<=" . intval($_GET['endmoney']);
	//$moneys2_string = " and ot.total<=" . intval($_GET['endmoney']);
	//$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$moneys2_string);
}
*/

$utf_String = "
                 SET CHARACTER_SET_CLIENT = utf8,
                 CHARACTER_SET_CONNECTION = utf8,
                 CHARACTER_SET_DATABASE = utf8,
                 CHARACTER_SET_RESULTS = utf8,
                 CHARACTER_SET_SERVER = utf8,
                 COLLATION_CONNECTION = utf8_general_ci,
                 COLLATION_DATABASE = utf8_general_ci,
                 COLLATION_SERVER = utf8_general_ci,
                 AUTOCOMMIT=1
	       ";
		   $DB->query($utf_String);
//$Where    = $_GET['skey']!=""  && trim(urldecode($_GET['skey']))!=$Admin_Member[PleaseInputAcc] ? " where u.username like '%".trim(urldecode($_GET['skey']))."%'" : $Where ;
//$Sql      = "select u.* ,l.level_name,ot.total  from `{$INFO[DBPrefix]}user` u  left join `{$INFO[DBPrefix]}user_level` l on (u.user_level = l.level_id) left join (select user_id,sum(discount_totalPrices) as total from `{$INFO[DBPrefix]}order_table` where pay_state=1 group by user_id having " . $money_string . ") as ot on u.user_id=ot.user_id  ".$Create_Sql."  order by u.reg_date desc,total desc,u.memberno desc";

$Sql      = "select u.* ,b.*  from `{$INFO[DBPrefix]}baduser` b  inner join `{$INFO[DBPrefix]}user` u on (u.user_id = b.user_id) where count>=3 order by b.count desc,u.user_id desc";


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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>黑名單</title>
</head>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
<form name="form2" method="post" action="admin_group_excel.php" target='_blank'  >
<input type="hidden" name="Action" value="Excel">
</form>
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
		document.adminForm.action = "admin_member.php?Action=Modi&user_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_member_save.php";
			document.adminForm.act.value="badDel";
			document.adminForm.submit();
		}
	}
}
function toDao(){
			document.form1.action = "admin_member_excel_out.php";
			document.form1.submit();
}

</SCRIPT>
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;黑名單</SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>
                  
                  <!--	
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79>
                        <TABLE class=fbottonnew link="javascript:toExprot();">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG  src="images/<?//=$INFO[IS]?>/excel_icon.gif"  border=0>&nbsp;<?//=$PROG_TAGS["ptag_236"];//导出?>&nbsp; </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>		
							-->					
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
      <TABLE class=allborder cellSpacing=0 cellPadding=0 width="100%"   align=center border=0 style="margin-bottom:10px">
        <FORM name=form1 id=form1 method=get action="">        
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=left colSpan=2 height=31>&nbsp;</TD>
            <TD width=19% height=31 align=right valign="bottom" class=p9black style="padding:10px"><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.form1.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
            </TR>
          </FORM>
        </TABLE>	
      
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <form name="ExportExcel" action="admin_member_excel.php" method="post"  enctype="multipart/form-data" >
            </form>
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
                            <TD width="20" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                              <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle></TD>
                            <TD width="156" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                            <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員編號</TD>
                            <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[UserName];//帳號?></TD>
                            <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[TrueName];//姓名?> </TD>
                            <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Sex];//性别?> </TD>
                            <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Area];//地區?></TD>
                            <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Email] ;//电子邮件?></TD>
                            <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Admin_Member[RegTime] ;//注册时间?></TD>
                            </TR>
                          <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {


					?>
                          <TR class=row0>
                            <TD height=26 align=center>
                              <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['user_id']?>' name=cid[]></TD>
                            <TD  height=26 align=center>
                              <?php echo $i?></TD>
                            <TD align="left" noWrap><?php echo $Rs['memberno']?></TD>
                            <TD height=26 align="left" noWrap>
                              <A href="javascript:toEdit('<?php echo $Rs['user_id']?>',0);">
                                <?php echo $Rs['username']?>                        </A></TD>
                            <TD height=26 align="left" noWrap>
                              <?php echo $Rs['true_name']?>                      </TD>
                            <TD height=26 align="center" noWrap>
                              <?php echo  $FUNCTIONS->Sextype($Rs['sex']) ?>                      </TD>
                            <TD height=26 align="left" noWrap>
                              <?php echo $Rs['Country'].$Rs['canton'].$Rs['city']?>&nbsp; </TD>
                            <TD height=26 align="left" noWrap style="padding-left:5px">
                              <?php echo $Rs['email']?>                      </TD>
                            <TD height=26 align=center nowrap><?php echo $Rs['reg_date']?></TD>
                            </TR>
                          <?php
					$i++;
					}
					?>
                          <TR>
                            <TD height=14 colspan="2" align=middle>&nbsp;</TD>
                            <TD width=125>&nbsp;</TD>
                            <TD width=114 height=14>&nbsp;</TD>
                            <TD width=100 height=14>&nbsp;</TD>
                            <TD width=100 height=14>&nbsp;</TD>
                            <TD width=106 height=14>&nbsp;</TD>
                            <TD width=174 height=14>&nbsp;</TD>
                            <TD width=117 height=14>&nbsp;</TD>
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
    </TR>
  <TR>
    <TD width="100%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    </TR></TBODY></TABLE>
<script language="javascript">
iniArea("",1,"","","");
</script>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
