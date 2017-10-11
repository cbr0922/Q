<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";

$Action_value = "Insert";
$Action_say   = $Mail_Pack[AddEmailGroup];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
<?php include_once "head.php";?>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  <?php echo  $Onload ?> >

<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" 
background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                    <TD width="271" noWrap class=p12black><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Action_say?>
                      </SPAN></TD>
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
                            <a href="admin_group_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:document.getElementById('form1').submit();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                            
                          </TD></TR></TBODY></TABLE>
                    
                  </TD></TR></TBODY></TABLE>
            </TD>
          </TR>
        </TBODY>
  </TABLE><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff height=300>
                      
                      <FORM name='form1' id='form1' action='admin_group_save.php' method='post' >
                        <INPUT type='hidden'  name='act' value="save"> 
                        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" bgcolor="#f7f7f7" class="allborder">
                          <tr>
                            <td colspan="2" align="right">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="right">郵件組名稱：</td>
                            <td width="81%"><input type="text" name="mgroup_name" id="mgroup_name" /></td>
                          </tr>
                          <tr>
                            <td align="right">&nbsp;</td>
                            <td>====生成條件====</td>
                          </tr>
                          <tr>
                            <td align="right">會員級別：</td>
                            <td><input name="checkLevel" type="checkbox"  value="1" <?php if ($_POST[checkLevel]==1) echo " checked ";?>>
							<?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}user_level` order by level_id asc ",'user_level','level_id','level_name',intval($user_level));?></td>
                          </tr>
                          <tr>
                            <td width="10%" align="right"><?php echo $Mail_Pack[BornDate];?>：</td>
                            <td><input name="checkYear" type="checkbox"  value="1" <?php if ($_POST[checkYear]==1) echo " checked ";?>>
                              <?php
                              $Born_year = "\n";
                              $Born_year .= " <SELECT name='Year' class=\"inputstyle\">";
                              for ($i=date("Y",time())-60;$i<=date("Y",time())-1;$i++){
                              	$Born_year .= "<option value=".$i." ";
                              	if (intval($_POST[Year])==$i){
                              		$Born_year .= " selected=\"selected\" ";
                              	}
                              	$Born_year .= " > ".$i."</option>\n";
                              }
                              $Born_year .= " </SELECT> ";
                              echo 		$Born_year;
?>年&nbsp;&nbsp;&nbsp;&nbsp;
                              <input name="checkMonth" value="1" type="checkbox"  <?php if ($_POST[checkMonth]==1) echo " checked ";?>>
                              <?php
                              $Born_month = "\n";
                              $Born_month .= " <SELECT name='Month' class=\"inputstyle\">";
                              for ($i=1;$i<=12;$i++){
                              	$Born_month .= "<option value=".$i."" ;
                              	if (intval($_POST[Month])==$i){
                              		$Born_month .= " selected=\"selected\" ";
                              	}
                              	$Born_month .= " >".$i."</option>";
                              }
                              $Born_month .=" </SELECT> ";
                              echo $Born_month;

?>&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="right"><?php echo $Mail_Pack[SexIs]?>：</td>
                            <td><input name="checkSex" value="1" type="checkbox"  <?php if ($_POST[checkSex]==1) echo " checked ";?>>
                              <INPUT type=radio  value='0' name='Sex' <?php if ($_POST[Sex]==0 ) echo " checked "?> >
                              <?php echo $Mail_Pack[Men]?>
                              <INPUT type=radio  value='1' name='Sex' <?php if ($_POST[Sex]==1 ) echo " checked "?> >
                              <?php echo $Mail_Pack[Women]?></td>
                          </tr>
                          <tr>
                            <td align="right"><?php echo $Mail_Pack[AreaName]?>：</td>
                            <td>
                              <input name="checkArea" type="checkbox"  value="1"  <?php if ($_POST[checkArea]==1) echo " checked ";?>>
                              
                              <?php if ( VersionArea == "gb" ) {?>
                              <select name="province" onChange='updateMenus(this)'  class="trans-input">
                              </select> <select name="city" id="city"  class="trans-input"></select>
                              &nbsp;
                              <input name="othercity" type="text" size="30" value="<?php echo $zip ?>"  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'">	
                              <?php } elseif (VersionArea == "big5" ) { ?>							   
                              
                              <select id="county" name="county"></select>
                              <select id="province" name="province"></select>
                              <select id="city" name="city"></select>
                              <?php } ?>							</td>
                          </tr>
                          <tr>
                            <td align="right">經銷商：</td>
                            <td><input name="checkcompany" type="checkbox" id="checkcompany"  value="1"  <?php if ($_POST[checkcompany]==1) echo " checked ";?>>
                              <?php
							$Sql      = "select u.*  from `{$INFO[DBPrefix]}company` u order by u.id desc";
							$Query    = $DB->query($Sql);
							$Num      = $DB->num_rows($Query);
							while ($Rs=$DB->fetch_array($Query)) {
								$company .="<option value=".$Rs['id']." ";
								$company .= " >".$Rs['companyname']."</option>\n";
							}
							?>
                              <select name="company">
                                <?php echo $company;?>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td align="right">電子報 ：</td>
                            <td><input name="checkdianzibao" type="checkbox" id="checkdianzibao"  value="1"  <?php if ($_POST[checkdianzibao]==1) echo " checked ";?> />
                              <input name="dianzibao" type="radio" value="1" <?php if($_POST['dianzibao']==1) echo "checked";?>  />
                              訂閱
                              <input type="radio" name="dianzibao" value="0" <?php if($_POST['dianzibao']==0) echo "checked";?>/>
                              未訂閱</td>
                          </tr>
                          <tr>
                            <td align="right">註冊時間：</td>
                            <td><input name="checkreg" type="checkbox" id="checkreg"  value="1"  <?php if ($_POST[checkreg]==1) echo " checked ";?> />
                            <INPUT   id="begtime" size=10 value="<?php echo $begtime?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name="begtime" />~<INPUT    id="endtime" size=10 value="<?php echo $endtime?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name="endtime" />
                            </td>
                          </tr>
                          <tr>
                            <td align="right">消費期限：</td>
                            <td><input name="checkordertime" type="checkbox" id="checkordertime"  value="1"  <?php if ($_POST[checkordertime]==1) echo " checked ";?> />
                              <input   id="order_begtime" size="10" value="<?php echo $order_begtime?>"    onclick="showcalendar(event, this)" onfocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name="order_begtime" />
                              ~
                            <input    id="order_endtime" size="10" value="<?php echo $order_endtime?>"      onclick="showcalendar(event, this)" onfocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name="order_endtime" /></td>
                          </tr>
                          <tr>
                            <td align="right">消費金額範圍：</td>
                            <td><input name="checkmoney" type="checkbox" id="checkmoney"  value="1"  <?php if ($_POST[checkmoney]==1) echo " checked ";?> />
                            <input name="minmoney" type="text" id="minmoney" size="6" />
                            元~
                            <input name="maxmoney" type="text" id="maxmoney" size="6" />
元</td>
                          </tr>
                        </table>
                      </form>
                      <br>
                  </TD></TR></TBODY></TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
                      <script src="../js/area.js" type="text/javascript" charset="utf-8"></script> 
                      <script language="javascript">
iniArea("",1,"<?php echo trim($_GET[county])?>","<?php echo trim($_POST[province])?>","<?php echo trim($_POST[city])?>");
</script>
</BODY>
</HTML>
 
