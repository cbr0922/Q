<?php

include_once "Check_Admin.php";

include_once "pagenav_stard.php";

/**

 *  装载客户服务语言包

 */

include "../language/".$INFO['IS']."/KeFu_Pack.php";



$objClass = "9pv";

$Where    = '';

$Nav      = new buildNav($DB,$objClass);





if (isset($_GET['type'])) {

	$Where = urldecode($_GET['type']);

	$Where = str_replace('wodedanyinhao',"'",$Where);



}else{

	$Where    = (trim($_GET['skey'])!="") ?  " where k_chuli_name like '%".urldecode(trim($_GET['skey']))."%'" : "" ;

}



$Sql      = "select * from `{$INFO[DBPrefix]}kefu_chuli` ".$Where." order by k_chuli_id  ";



$Query    = $DB->query($Sql);

$Num      = $DB->num_rows($Query);

if ($Num>0){

	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;

	$Nav->total_result=$Num;

	$Nav->execute($Sql,$limit);

	$Query    = $Nav->sql_result;

	$Nums     = $Num<$limit ? $Num : $limit ;

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $KeFu_Pack['Back_Nav_title_fou'];//綫上客服-->處理情況列表?></TITLE>

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<SCRIPT language=javascript>



function toEdit(id,catid){

	var checkvalue;

	var catvalue = "";

	

	if (id == 0) {

		checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');

	}else{

		checkvalue = id;

	}

		

	if (catid != 0) {

		catvalue = "&k_chuli_id="+catid;

	}

	

	if (checkvalue!=false){

		

		document.adminForm.action = "admin_kefu_chuli.php?Action=Modi&k_chuli_id="+checkvalue;

		document.adminForm.act.value="edit";

		document.adminForm.submit();

	}

}

function toNew(id,catid){

	    document.adminForm.action = "admin_kefu_chuli.php";

		document.adminForm.submit();

	

}



function toDel(){

	var checkvalue;

	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');

	if (checkvalue!=false){

		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){   //您是否确认删除选定的记录

			document.adminForm.action = "admin_kefu_chuli_save.php";

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

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>

                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $KeFu_Pack['Back_Nav_title_fou'];//綫上客服-->處理情況列表?></SPAN>

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

                            <TD vAlign=bottom noWrap class="link_buttom"><a  href="javascript:toNew();"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $KeFu_Pack['Back_AddChuli'];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

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

                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->

                            </TD>

                          </TR>

                        </TBODY>

                      </TABLE>

                    </TD>                  

                  </TR>

                </TBODY>

              </TABLE>

              </TD>

            </TR>

          </TBODY>

        </TABLE>

      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">

        <TBODY>

          <TR>

            <TD vAlign=top height=210><table width="100%" border=0 cellpadding=0 cellspacing=0>

              <tbody>

                <tr>

                  <td valign=top height=210><table cellspacing=0 cellpadding=0 width="100%" border=0>

                    <tbody>

                      <tr>

                        <td bgcolor=#ffffff>

                        <form name=adminForm action="" method=post>

                        <input type=hidden name=act />

                        <input type=hidden value=0  name=boxchecked />

                        <table class=listtable cellspacing=0 cellpadding=0 width="100%" border=0 id="orderedlist">

                            <?php

					 $Where = str_replace('\'','wodedanyinhao',$Where);

					 if(!isset($_GET['offset'])){

					 	$offset = 0;

					 }else {

					 	$offset = $_GET['offset'];

					 }

					 ?>

                            <input type=hidden value="<?php echo urlencode($Where)?>"  name='type' />

                            <input type=hidden value="<?php echo $offset?>"  name='offset' />

                            <tbody>

                              <tr align=middle>

                                <td class=p9black nowrap align=left  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26><input onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle /></td>

                                <td width="160"  height=26 align="center" nowrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['No'];//編號?></td>

                                <td width="156"  height=26 align="center" nowrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['Status'];//狀態?>(ON/OFF)</td>

                                <td width="698"  height=26 align="left" nowrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $KeFu_Pack['Back_ChuliName'];//處理情況名稱?></td>

                                </tr>

                              <?php               



					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

					?>

                              <tr class=row0>

                                <td align=left width=51 height=26><input id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['k_chuli_id']?>' name=cid[] /></td>

                                <td height=26 align="center" nowrap><?php echo  $i+1;?></td>

                                <td height=26 align="center" nowrap><?php echo $Rs['status']?'on':'<font color="Red">off</fond>';?></td>

                                <td height=26 nowrap><?php

                        if ($Rs['checked']==1) {

                        	$checked = "<font color='Red'>(".$KeFu_Pack['Back_NeiDing'].")</fond>";

                        }else {

                        	$checked = '';

                        }

                        ?>

                                  <a href="javascript:toEdit('<?php echo $Rs['k_chuli_id']?>',0);"> <?php echo $Rs['k_chuli_name'].$checked?>&nbsp; </a></td>

                                </tr>

                              <?php

					$i++;

					}



					?>

                              <?php  if ($Num==0){ ?>

                              <tr align="center">

                                <td height=14 colspan="6"><?php echo $KeFu_Pack['nodata'];//"無相關資料?></td>

                                </tr>

                              <?php } ?>

                              </tbody>

                           

                        </table> </form></td>

                      </tr>

                      </tbody>

                    </table>

                    <?php  if ($Num>0){ ?>

                    <table class=p9gray cellspacing=0 cellpadding=0 width="100%"    border=0>

                      <tbody>

                        <tr>

                          <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav()?></td>

                          </tr>

                        </tbody>

                      </table>

                    <?php } ?></td>

                  </tr>

                </tbody>

              </table></TD>

            </TR>

        </TABLE>

 </div>

<div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>

