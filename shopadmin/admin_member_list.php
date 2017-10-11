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
*/
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
}
/*
if ($_GET[allcity]!=1 && $_GET[province]!=""){
	$Area_string = "  city='".trim($_GET[province])."' ";
	if ($_GET[city]!=""){
		$Area_string .= "  and canton='".trim($_GET[city])."'  ";
	}
	$Create_Sql = $FUNCTIONS->CreateSql($Create_Sql,$Area_string);
}
*/
if ($_GET['ifallarea']!="1"){
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
}
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

$Sql      = "select u.* ,l.level_name  from `{$INFO[DBPrefix]}user` u  left join `{$INFO[DBPrefix]}user_level` l on (u.user_level = l.level_id) ".$Create_Sql."  order by u.user_id desc";


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
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;<?php echo $JsMenu[Member_List];//会员列表?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
<script src="../js/area.js" type="text/javascript" charset="utf-8"></script>
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
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}
function toDao(){
			document.form1.action = "admin_member_excel_out.php";
			document.form1.submit();
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
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;<?php echo $JsMenu[Member_List];//会员列表?></SPAN>
                    </TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
                <TR>

                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79>
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_member.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php  echo $JsMenu[Member_Add];//新增会员?></a></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79>
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDao()"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;導出會員</a></TD>
                          </TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>

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
            <TD align=left colSpan=2 height=31>
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width=100% border=0>
                <TBODY>
                  <TR>
                    <TD height=29 align=left class="p9black" style="padding-left:10px;padding-top:5px">生日月份： <?php
                              $Born_year = "\n";
                              $Born_year .= " <SELECT name='Year' class=\"inputstyle\">";
							  $Born_year .= "<option value=''>請選擇</option>";
                              for ($i=date("Y",time())-60;$i<=date("Y",time())-1;$i++){
                              	$Born_year .= "<option value=".$i." ";
                              	if (intval($_GET[Year])==$i){
                              		$Born_year .= " selected=\"selected\" ";
                              	}
                              	$Born_year .= " > ".$i."</option>\n";
                              }
                              $Born_year .= " </SELECT> ";
                              //echo 		$Born_year;
							  $Born_month = "\n";
                              $Born_month .= " <SELECT name='Month' class=\"inputstyle\">";
							  $Born_month .= "<option value=''>請選擇</option>";
                              for ($i=1;$i<=12;$i++){
                              	$Born_month .= "<option value=".$i."" ;
                              	if (intval($_GET[Month])==$i){
                              		$Born_month .= " selected=\"selected\" ";
                              	}
                              	$Born_month .= " >".$i."</option>";
                              }
                              $Born_month .=" </SELECT> ";
                              echo $Born_month;
							   $Born_day = "\n";
                              $Born_day .= " <SELECT name='Day' class=\"inputstyle\">";
							  $Born_day .= "<option value=''>請選擇</option>";
                              for ($i=1;$i<=31;$i++){
                              	$Born_day .= "<option value=".$i."" ;
                              	if (intval($_GET[Day])==$i){
                              		$Born_day .= " selected=\"selected\" ";
                              	}
                              	$Born_day .= " >".$i."</option>";
                              }
                              $Born_day .=" </SELECT> ";
                              //echo $Born_day;
?>&nbsp;&nbsp;<?php echo $Mail_Pack[SexIs]?>：
                      <INPUT type=radio  value='0' name='Sex' <?php if ($_GET[Sex]==0 ) echo " checked "?> >
                      <?php echo $Mail_Pack[Men]?>
                      <INPUT type=radio  value='1' name='Sex' <?php if ($_GET[Sex]==1 ) echo " checked "?> >
                      <?php echo $Mail_Pack[Women]?>
                      <INPUT type=radio  value='' name='Sex' <?php if ($_GET[Sex]=="" ) echo " checked "?> >
                      不限&nbsp;&nbsp;</TD>
                  </TR>
                  <TR>

                <TD height=29 align=left class="9pv" style="padding-left:10px"><?php echo $Mail_Pack[AreaName]?>：

                    <input name="othercity" id="othercity" size="5" style="display:none">

                    <select id="county" name="county">

                    </select>

                    <select id="province" name="province">

                    </select>

                    <select id="city" name="city">

                    </select>

                    <input name="ifallarea" type="checkbox" id="ifallarea" value="1" <?php if($_GET['ifallarea']=="1") echo "checked";?> />

                    不限地區

                    註冊時間：From<INPUT   id=begtime3 size=10 value="<?php echo $begtime?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />

         To

        <INPUT    id=endtime3 size=10 value="<?php echo $endtime?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />

        <span class="9pv" style="padding-left:10px;padding-bottom:5px">

        <input type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle" onclick="document.all.form1.action='';" />

        </span></TD>

              </TR>
                  <TR>
                    <TD height=29 align=left class="9pv" style="padding-left:10px;padding-bottom:5px"><!--購買總金額：
                    <input name="startmoney" type="text" id="startmoney" size="5" value="<?php echo intval($_GET['startmoney']);?>">
元 TO
<input name="endmoney" type="text" id="endmoney" size="5" value="<?php echo intval($_GET['endmoney']);?>">
元-->
                      <?php
							$Sql      = "select u.*  from `{$INFO[DBPrefix]}saler` u order by u.id desc";
$Query_c    = $DB->query($Sql);
$Num_area      = $DB->num_rows($Query_c);
while ($Rs=$DB->fetch_array($Query_c)) {
	$company .="<option value=".$Rs['id']." ";
	if ($_GET['companyid'] == $Rs['id'])
		$company .= " selected";
	$company .= " >".$Rs['name']."</option>\n";
}
							?>
                      <select name="companyid">
                        <option value="0">請選擇經銷商</option>
                        <?php echo $company;?>
                      </select>
                      <select name="keyname" id="keyname">
                        <option selected  value="username" <?php if ($_GET[keyname]=='username' ) echo " selected "?>>帳號</option>
                        <option   value="memberno" <?php if ($_GET[keyname]=='memberno' ) echo " selected "?>>會員編號</option>
                        <option value="true_name" <?php if ($_GET[keyname]=='true_name' ) echo " selected "?>>姓名</option>
                        <option value="email" <?php if ($_GET[keyname]=='email' ) echo " selected "?>>Email</option>
                      </select>
                      <input  name='skey'  value='<?php echo $_GET[skey]?>' size="20">
                      &nbsp;
                      <input type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle" onClick="document.all.form1.action='';">
                    </TD>
                  </TR>
                </TBODY>
              </TABLE>
            </TD>
            <TD width=19% height=31 align=right valign="bottom" class=p9black style="padding:10px"><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.form1.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
          </TR>
        </FORM>
  </TABLE>

      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
        <form name="ExportExcel" action="admin_member_excel.php" method="post"  enctype="multipart/form-data" >
            <TR>
              <TD height=31 align=left>  會員導入：
                <input type="file" name="cvsEXCEL"  ID='cvsEXCEL' /><button name="Submit" type="submit" value="導入" size="20" class="button03" />導入</button>
              </TD>
          </TR>
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
                            <TD height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                              <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle></TD>
                            <TD align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                            <TD width="80" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>會員編號</TD>
														<TD width="60" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>註冊方式</TD>
                            <TD width="100"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[UserName];//帳號?></TD>
                            <TD width="81" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[TrueName];//姓名?> </TD>
                            <TD width="51" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Sex];//性别?> </TD>
                            <TD width="100" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Area];//地區?></TD>
                            <!--TD width="163" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[Email] ;//电子邮件?></TD-->
                            <TD width="78" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>推薦人</TD>
                            <TD width="78" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>推薦人業績</TD>
                            <TD width="78" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[UserLevel];//会员等级?></TD>
                            <TD width="78" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>編寫郵件</TD>
                            <TD width="78" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>編寫簡訊</TD>
                            <TD width="78" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>密碼</TD>
                            <TD width="78" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>客服</TD>
                            <TD width="100"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Admin_Member[RegTime] ;//注册时间?></TD>
                          </TR>
                          <?php
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {
						if($Rs['facebook_id']!=''){
							$login = "facebook";
							if(strrpos($Rs['facebook_id'],"@")){
								$login = "google";
							}
						}elseif ($Rs['yahoo_gid']!='') {
							$login = "yahoo";
						}else {
							$login = "一般註冊";
						}

					?><tbody>
                            <TR class=row0>
                              <TD width=104 height=26 align=center>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['user_id']?>' name=cid[]></TD>
                              <TD width=30 height=26 align=center>
                                <?php echo $i?></TD>
                              <TD align="left" noWrap><?php echo $Rs['memberno']?></TD>
															<TD align="left" noWrap><?php echo $login;?></TD>
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['user_id']?>',0);">
                                <?php echo $Rs['username']?>                        </A></TD>
                              <TD height=26 align="left" noWrap>
                                <?php echo $Rs['true_name']?>                      </TD>
                              <TD height=26 align="center" noWrap>
                                <?php echo  $FUNCTIONS->Sextype($Rs['sex']) ?>                      </TD>
                              <TD height=26 align="left" noWrap>
                                <?php echo $Rs['Country'].$Rs['canton'].$Rs['city']?>&nbsp; </TD>
                              <!--TD height=26 align="left" noWrap style="padding-left:5px">
                                <!?php echo $Rs['email']?></TD-->
                              <TD align="center" noWrap><div class="link_box"><a href="admin_recommendmember.php?user_id=<?php echo $Rs['user_id']?>">推薦人</a></div></TD>
                              <TD align="center" noWrap><div class="link_box"><a href="admin_recommendpoint.php?user_id=<?php echo $Rs['user_id']?>">推薦人業績</a></div></TD>
                              <TD height=26 align="center" noWrap>
                                <?php echo $Rs['level_name']//$FUNCTIONS->Level_name($Rs['member_point'])?>
                                &nbsp; </TD>
                              <TD align=center nowrap><div class="link_box"><a href="admin_writemail.php?uid=<?php echo $Rs['user_id']?>">寫郵件</a></div></TD>
                              <TD align=center nowrap><div class="link_box"><a href="admin_writesms.php?uid=<?php echo $Rs['user_id']?>">寫簡訊</a></div></TD>
                              <TD align=center nowrap><div class="link_box"><a href="admin_member_save.php?Action=resetpwd&user_id=<?php echo $Rs['user_id']?>">重置</a></div></TD>
                              <TD align=center nowrap><div class="link_box"><a href="admin_kefu_list.php?username=<?php echo $Rs['username']?>">客服紀錄</a></div></TD>
                              <TD height=26 align=center nowrap><?php echo $Rs['reg_date']?></TD>
                            </TR></tbody>
                        <?php
					$i++;
					}
					?>
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

        </TABLE></TD></TR></TABLE>
    </div>
<script language="javascript">
iniArea("",1,"<?php echo trim($_GET['county']); ?>","<?php echo trim($_GET['province']); ?>","<?php echo trim($_GET['city']); ?>");
</script>

<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
