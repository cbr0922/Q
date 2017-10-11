<?php

include_once "Check_Admin.php";

include_once Classes . "/pagenav_stard.php";

$objClass = "9pv";

$Nav      = new buildNav($DB,$objClass);



/**

 *  装载语言包

 */

include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

include "../language/".$INFO['IS']."/Admin_Member_Pack.php";



if ( trim($_GET['skey'])!=""  && urldecode(trim($_GET['skey']))!=trim($Basic_Command['InputKeyWord']))

	if ($_GET['provider_type']=='PM'){

		$Sql      = "select * from `{$INFO[DBPrefix]}operater` where truename ='" . urldecode(trim($_GET['skey'])) .  "' order by lastlogin desc ";

		$Query    = $DB->query($Sql);

		$Num      = $DB->num_rows($Query);

		$Rs=$DB->fetch_array($Query);

		$opid=$Rs['opid'];

		$Where    = " and ".trim($_GET[provider_type])." = '"  . $opid . "' ";

	}else{

		$Where    = " and ".trim($_GET[provider_type])." like '%".urldecode(trim($_GET['skey']))."%' ";

	}

if ( trim($_GET['state'])!=""  )

	$Where    .= " and state='" . trim($_GET['state']) . "' ";

$Sql      = "select * from `{$INFO[DBPrefix]}provider` where 1=1 ".$Where." order by providerno desc  ";



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

<TITLE><?php echo $JsMenu[Provider_Man]?>--&gt;<?php echo  $JsMenu[Provider_List];//供應商列表?>  </TITLE>

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<?php  include_once "Order_state.php";?>

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

		catvalue = "&provider_id="+catid;

	}

	

	if (checkvalue!=false){

		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;

		document.adminForm.action = "admin_provider.php?Action=Modi&provider_id="+checkvalue;

		document.adminForm.act.value="edit";

		document.adminForm.submit();

	}

}



function toDel(){

	var checkvalue;

	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');

	if (checkvalue!=false){

		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){   //您是否确认删除选定的记录

			document.adminForm.action = "admin_provider_save.php";

			document.adminForm.act.value="Del";

			document.adminForm.submit();

		}

	}

}



function toExcel(id){

	var checkvalue;

	//checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');

	//if (checkvalue!=false){

		//if (confirm('<?php echo $Basic_Command['Print_Select']?>')){

		    document.adminForm.method = "post";	

		    document.adminForm.action = "admin_provider_excel.php";

			//document.adminForm.Order_id.value="Order_id";	

			//document.adminForm.target="_blank";

			document.adminForm.submit();

		//}

	//}

}

</SCRIPT>

<div id="contain_out">

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

                <TBODY>

                  <TR>

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>

                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Provider_Man]?>--&gt;<?php echo  $JsMenu[Provider_List];//供應商列表?>  </SPAN>

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

                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_provider.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[AddProviderData]; //添加供应商?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

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

                  <TD align=middle valign="top">

                    

                    <TABLE>

                      <TBODY>

                        <TR>

                          <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toExcel(0);"><IMG  src="images/<?php echo $INFO[IS]?>/excel_icon_out.gif"  border=0>&nbsp;導出EXCEL&nbsp;</a> </TD>

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

      

      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>

        <FORM name=optForm method=get action="">        

          <input type="hidden" name="Action" value="Search">

          <TR>

            <TD align=left colSpan=2 height=31>

              <TABLE class=p12black cellSpacing=0 cellPadding=0 width=536 border=0>

                <TBODY>

                  <TR>

                    <TD align=right width=210 height=31>

                      <INPUT  name='skey'    onfocus=this.select()  onclick="if(this.value=='<?php echo $Basic_Command['InputKeyWord'];?>')this.value=''"  onmouseover=this.focus() value="<?php echo $Basic_Command['InputKeyWord']?>" size="30"> </TD>

                    <TD width=102 height=31 align=center nowrap>

                      <select name="provider_type">

                        <option value="provider_name">廠商名稱</option>

                        <option value="providerno">廠編</option>

                        <option value="PM">PM</option>

                        </select></TD>

                    <TD class=p9black vAlign=center width=78><select name="state" id="state">

                      <option value="" <?php if($_GET['state']=="") echo "selected";?>>請選擇</option>

                      <?php

                      foreach($provider_state as $k=>$v){

					  ?>

                      <option value="<?php echo $k;?>" <?php if($_GET['state']==$k && $_GET['state']!="") echo "selected";?>><?php echo $v;?></option>

                      <?php

					  }

					  ?>

                      </select></TD>

                    <TD class=p9black vAlign=center width=146 height=31>&nbsp; <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle"> 

                      </TD>

                    </TR>

                  </TBODY>

                </TABLE>

              

              </TD>

            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示 ?><?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>

              </TD>

            </TR>

          </FORM>

        </TABLE>

      <table><form name="ExportExcel" action="admin_provider_excelput.php" method="post"  enctype="multipart/form-data" >

        

        <TR>

          <TD height=31 align=left style="padding-left:10px" class=p9black>導入：

            <input type="file" name="cvsEXCEL"  ID='cvsEXCEL' /> 

            <button name="Submit" type="submit" class="button03" value="導入" size="20"/>

            導入</button></TD>

          </TR>

        </form></table>

      

      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">

        <TBODY>

          <TR>

            <TD vAlign=top height=131>

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

                              <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>

                                <INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle> </TD>

                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>

                                廠編</TD>

                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>

                                <i class="icon-female" style="font-size:14px;margin-right:4px"></i>廠商名稱</TD>

                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>

                                帳號</TD>

                              <TD width="68" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>品牌名稱&nbsp;</TD>

                              <TD width="70" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>窗口&nbsp;</TD>

                              <TD width="88" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>

                                <i class="icon-phone" style="font-size:14px;margin-right:4px"></i><?php echo $Admin_Member[Phone];//聯絡電話：?></TD>

                              <TD width="91" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>

                                <i class="icon-envelope" style="font-size:14px;margin-right:4px"></i><?php echo $Admin_Member[Email];//電子信箱?></TD>

                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>商品數&nbsp;</TD>

                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>合作模式&nbsp;</TD>

                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>票期&nbsp;</TD>

                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>PM&nbsp;</TD>

                              <TD height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>

                                加入日</TD>

                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>合約天數&nbsp;</TD>

                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>狀態&nbsp;</TD>

                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>寄送開通信</TD>

                              

                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><i class="icon-print" style="font-size:14px;margin-right:4px"></i>列印</TD>

                              </TR>

                            <?php               

					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

					?>

                            <TR class=row0>

                              <TD align=middle width=29 height=26>

                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['provider_id']?>' name=cid[]>					   

                                </TD>

                              <TD height=26 align="left" noWrap><?php echo  $Rs['providerno'];?></TD>

                              <TD height=26 align="left" noWrap>

                                <A href="javascript:toEdit('<?php echo $Rs['provider_id']?>',0);"> <?php echo $Rs['provider_name']?>&nbsp; </A></TD>

                              <TD height=26 align=left nowrap><?php echo $Rs['provider_thenum']?>&nbsp;</TD>

                              <TD align=left nowrap><?php echo $Rs['brandname']?></TD>

                              <TD align=left nowrap><?php echo $Rs['provider_lxr']?></TD>

                              <TD height=26 align=left nowrap><?php echo $Rs['provider_tel']?>&nbsp;</TD>

                              <TD height=26 align=left nowrap><?php echo $Rs['provider_email']?>&nbsp;</TD>

                              <TD align=center nowrap>

                                <?php

                       $g_Sql = "select count(*) as c from `{$INFO[DBPrefix]}goods` where provider_id='" . $Rs['provider_id'] . "'";

					   $g_Query    = $DB->query($g_Sql);

					   $g_Rs=$DB->fetch_array($g_Query);

					   echo $g_Rs['c'];

					   ?>                       </TD>

                              <TD align=center nowrap><?php echo $Rs['mode']?></TD>

                              <TD align=center nowrap><?php echo $Rs['piaoqi']?></TD>

                              <TD align=center nowrap>

                                <?php

                       $o_Sql      = "select * from `{$INFO[DBPrefix]}operater` where opid='" . $Rs['PM'] . "' order by lastlogin desc ";

					   $o_Query    = $DB->query($o_Sql);

					   $o_Rs=$DB->fetch_array($o_Query);

					   echo $o_Rs['truename'];

					   ?>

                                </TD>

                              <TD height=26 align=center nowrap><?php echo date("Y/m/d",$Rs['provider_idate'])?>&nbsp;</TD>

                              <TD align=center nowrap>

                                <?php

                       $startdate=strtotime(date("Y-m-d",time()));

						$enddate=strtotime($Rs['end_date']);

						$date = round(($enddate-$startdate)/3600/24);

						if ($date>0)

							echo ($date) . "天";

					   ?>

                                

                                </TD>

                              <TD align=center nowrap><?php echo $provider_state[$Rs['state']]?></TD>

                              <TD align=center nowrap><div class="link_box" style="width:40px"><a href="admin_provider_save.php?Action=send&Provider_id=<?php echo $Rs['provider_id']?>">寄送</a></div></TD>

                              

                              <TD align=center nowrap><div class="link_box" style="width:40px"><a href="javascript:temp=window.open('admin_provider_print.php?provider_id=<?php echo $Rs['provider_id']?>','provider','width=510,height=500,resizable=1,scrollbars=1,status=no,toolbar=no,location=no,menu=no');temp.focus()">列印</a></div></TD>

                              </TR>

                            <?php

					$i++;

					}

					?>

                            <TR>

                              <TD align=middle width=29 height=14>&nbsp;</TD>

                              <TD width=70 height=14 nowrap>&nbsp;</TD>

                              <TD width=92 height=14>&nbsp;</TD>

                              <TD width=51 height=14>&nbsp;</TD>

                              <TD height=14 colspan="4">&nbsp;</TD>

                              <TD width=52 height=14>&nbsp;</TD>

                              <TD width=68>&nbsp;</TD>

                              <TD width=45>&nbsp;</TD>

                              <TD width=70>&nbsp;</TD>

                              <TD width=72>&nbsp;</TD>

                              <TD width=82>&nbsp;</TD>

                              <TD width=66>&nbsp;</TD>

                              <TD width=75>&nbsp;</TD>

                              <TD width=75>&nbsp;</TD>

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

                  </TABLE>

              <?php } ?>  

              </TD>

            </TR>

          </TABLE>

 </div>

<div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>

