<?php

include_once "Check_Admin.php";

include_once Classes . "/pagenav_stard.php";

$objClass = "9pv";

$Nav      = new buildNav($DB,$objClass);

/**

 *  装载语言包

 */

include "../language/".$INFO['IS']."/Admin_Login_Log_Pack.php";

include_once Classes . "/Time.class.php";

$TimeClass = new TimeClass;



if($_GET['admin']!=""){

	$subsql .= " and admin like '%" . $_GET['admin'] . "%'";	

}

if($_GET['ip']!=""){

	$subsql .= " and ip like '%" . $_GET['ip'] . "%'";	

}

if($_GET['skey']!=""){

	$subsql .= " and content like '%" . $_GET['skey'] . "%'";	

}

if($_GET['begtime']!=""){

	$begtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['begtime'],"-");

	$subsql .= " and logtime >= '" . $begtimeunix . "'";		

}

if($_GET['endtime']!=""){

	$endtimeunix  = $TimeClass->ForYMDGetUnixTime($_GET['endtime'],"-")+60*60*24;

	$subsql .= " and logtime <= '" . $endtimeunix . "'";		

}



$Sql      = "select * from `{$INFO[DBPrefix]}adminlog` where 1=1 " . $subsql . " order by id desc ";

$Query    = $DB->query($Sql);

$Num      = $DB->num_rows($Query);

if ($Num>0){

  $limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;

  $Nav->total_result=$Num;

  $Nav->execute($Sql,$limit);

  $Query = $Nav->sql_result;

  $Nums     = $Num<$limit ? $Num : $limit ;

 }

 

if (($_SESSION['LOGINADMIN_TYPE']==1 && $_SESSION['sa_type']!=2)){

	$FUNCTIONS->header_location('index.php');	

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man];//管理員管理?>--&gt;操作日誌</TITLE>

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<SCRIPT language=javascript>

function toDel(){

	var checkvalue;

	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');

	if (checkvalue!=false){

		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){

			document.adminForm.action = "admin_log_save.php";

			document.adminForm.act.value="Del";

			document.adminForm.submit();

		}

	}

}

</SCRIPT>

<div id="contain_out">

  <TBODY>

  <TR>

    <TD vAlign=top width="100%" height=302><?php  include_once "Order_state.php";?>

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

                <TBODY>

                  <TR>

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>

                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[User_Man];//管理員管理?>--&gt;操作日誌</SPAN>

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

                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

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

            <TD height="46" colSpan=2 align=left valign="middle" class="p9black">帳號：

              <INPUT value='<?php echo $_GET['admin'];?>'   size='10'  name='admin'>

              IP：

              <INPUT value='<?php echo $_GET['ip'];?>'   size='10'  name='ip'>

              關鍵字：

              <INPUT value='<?php echo $_GET['skey'];?>'   size='10'  name='skey'>

              From

              <INPUT   id=begtime size=10 value="<?php echo $_GET['begtime']?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />

              To

              <INPUT    id=endtime size=10 value="<?php echo $_GET['endtime']?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />

              <input  type='image' src="images/<?php echo $INFO[IS]?>/t_go.gif" border='0' name='imageField' />

              </TD>

            <TD class=p9black align=right width=273><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>  

              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>

              </TD>

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

                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">

                        <FORM name=adminForm action="" method=post>

                          <INPUT type=hidden name=act>

                          <INPUT type=hidden value=0  name=boxchecked> 

                          <TBODY>

                            <TR align=middle>

                              <TD width="5%" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>

                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle></TD>

                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Basic_Command['SNo_say'];//序号?></TD>

                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Login_Log_Pack[LoginUser];//帳號?><br></TD>

                              <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> 操作</TD>

                              <TD height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Login_Log_Pack[LoginIP];//IP?></TD>

                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Admin_Login_Log_Pack[LoginTime];//时间?></TD>

                              </TR>

                            <?php               

					$i=0;

				    $j=1;

					while ($Rs=$DB->fetch_array($Query)) {



					

					?><TBODY>

                              <TR class=row0>

                                <TD align=center height=26>

                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['id']?>' name=cid[]></TD>

                                <TD height=26 align="center" noWrap>                        

                                <?php echo $j?> </TD>

                                <TD height=26 align="left" noWrap>

                                <?php echo $Rs['admin']?>&nbsp;</TD>

                                <TD height=26 align="left" noWrap><?php echo $Rs['content']?></TD>

                                <TD height=26 align="center" noWrap><?php echo $Rs['ip']  ?>                      </TD>

                                <TD height=26 align="center" noWrap><?php echo date("Y-m-d H:i a",$Rs['logtime'])?></TD>

                              </TR></TBODY>

                          <?php

					$j++;

					$i++;

					}

					?>

                          <?php  if ($Num==0){ ?>

                          <TR align="center">

                            <TD height=14 colspan="6"><?php echo $Basic_Command['NullDate']?></TD>

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

</div>

 <div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>

