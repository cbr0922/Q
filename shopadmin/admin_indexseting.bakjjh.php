<?php

include "Check_Admin.php";

include RootDocumentShare."/setindex.php";

include Classes . "/ajax.class.php";

$Ajax      = new Ajax();

$InitAjax  = $Ajax->InitAjax();

include "../language/".$INFO['IS']."/Admin_indexseting_Pack.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Index_Set]?></TITLE></HEAD>

<SCRIPT language=JavaScript>var NewWin = null; function WinOpen(url) {if(!NewWin || NewWin.closed) {NewWin=LoadWin(url,'win_poll',320,240);}else{NewWin.focus();}} function LoadWin(url, name, width, height) {var str='scrollbars,resizable,location,height='+height+',innerHeight='+height+',width='+width+',innerWidth='+width; if(window.screen) {var ah=screen.availHeight-30; var aw=screen.availWidth-10; var xc=(aw-width)/2; var yc=(ah-height)/2; str +=',left='+xc+',screenX='+xc; str +=',top='+yc+',screenY='+yc;} return window.open(url,name,str); }</SCRIPT>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<SCRIPT language=javascript>

function InitAjax()



{



  var ajax=false;



  try {



    ajax = new ActiveXObject("Msxml2.XMLHTTP");



  } catch (e) {



    try {



      ajax = new ActiveXObject("Microsoft.XMLHTTP");



    } catch (E) {



      ajax = false;



    }



  }



  if (!ajax && typeof XMLHttpRequest!='undefined') {



    ajax = new XMLHttpRequest();



  }



  return ajax;



}



</SCRIPT>



<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center  border=0><TBODY>

  <TR>

    <TD width=0%>&nbsp; </TD>

    <TD width="16%">&nbsp;</TD>

    <TD align=right width="84%">

      <?php  include_once "desktop_title.php";?>

	  </TD></TR></TBODY></TABLE>

<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>

	function view(obj,a)

	{

		if(a == 1){

			obj.style.display="";

		}else{

			obj.style.display="none";

		}

	}

	function checkform(){

/*

		if (chkblank(form1.site_name.value) || form1.site_name.value.length>30){

			form1.site_name.focus();

			alert('<?php echo $INFO['N_site_name']?>');

			return;

		}

		

*/

		form1.submit();

	}

	

	function viewCount(obj,a)

	{

	   	if(a == 'no'){

		    countStyleDisplay.innerHTML = "";			

		}

		if(a == 0){

			//obj.style.display="";

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style00/0.gif'><IMG SRC='../Resources/count/style00/1.gif'><IMG SRC='../Resources/count/style00/2.gif'><IMG SRC='../Resources/count/style00/3.gif'><IMG SRC='../Resources/count/style00/4.gif'><IMG SRC='../Resources/count/style00/5.gif'><IMG SRC='../Resources/count/style00/6.gif'><IMG SRC='../Resources/count/style00/7.gif'><IMG SRC='../Resources/count/style00/8.gif'><IMG SRC='../Resources/count/style00/9.gif'>";		

		}

	   	if(a == 1){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style01/0.gif'><IMG SRC='../Resources/count/style01/1.gif'><IMG SRC='../Resources/count/style01/2.gif'><IMG SRC='../Resources/count/style01/3.gif'><IMG SRC='../Resources/count/style01/4.gif'><IMG SRC='../Resources/count/style01/5.gif'><IMG SRC='../Resources/count/style01/6.gif'><IMG SRC='../Resources/count/style01/7.gif'><IMG SRC='../Resources/count/style01/8.gif'><IMG SRC='../Resources/count/style01/9.gif'>";			

		}

	   	if(a == 2){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style02/0.gif'><IMG SRC='../Resources/count/style02/1.gif'><IMG SRC='../Resources/count/style02/2.gif'><IMG SRC='../Resources/count/style02/3.gif'><IMG SRC='../Resources/count/style02/4.gif'><IMG SRC='../Resources/count/style02/5.gif'><IMG SRC='../Resources/count/style02/6.gif'><IMG SRC='../Resources/count/style02/7.gif'><IMG SRC='../Resources/count/style02/8.gif'><IMG SRC='../Resources/count/style02/9.gif'>";			

		}

	   	if(a == 3){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style03/0.gif'><IMG SRC='../Resources/count/style03/1.gif'><IMG SRC='../Resources/count/style03/2.gif'><IMG SRC='../Resources/count/style03/3.gif'><IMG SRC='../Resources/count/style03/4.gif'><IMG SRC='../Resources/count/style03/5.gif'><IMG SRC='../Resources/count/style03/6.gif'><IMG SRC='../Resources/count/style03/7.gif'><IMG SRC='../Resources/count/style03/8.gif'><IMG SRC='../Resources/count/style03/9.gif'>";		

		}

	   	if(a == 4){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style04/0.gif'><IMG SRC='../Resources/count/style04/1.gif'><IMG SRC='../Resources/count/style04/2.gif'><IMG SRC='../Resources/count/style04/3.gif'><IMG SRC='../Resources/count/style04/4.gif'><IMG SRC='../Resources/count/style04/5.gif'><IMG SRC='../Resources/count/style04/6.gif'><IMG SRC='../Resources/count/style04/7.gif'><IMG SRC='../Resources/count/style04/8.gif'><IMG SRC='../Resources/count/style04/9.gif'>";			

		}

	   	if(a == 5){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style05/0.gif'><IMG SRC='../Resources/count/style05/1.gif'><IMG SRC='../Resources/count/style05/2.gif'><IMG SRC='../Resources/count/style05/3.gif'><IMG SRC='../Resources/count/style05/4.gif'><IMG SRC='../Resources/count/style05/5.gif'><IMG SRC='../Resources/count/style05/6.gif'><IMG SRC='../Resources/count/style05/7.gif'><IMG SRC='../Resources/count/style05/8.gif'><IMG SRC='../Resources/count/style05/9.gif'>";			

		}

	   	if(a == 6){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style06/0.gif'><IMG SRC='../Resources/count/style06/1.gif'><IMG SRC='../Resources/count/style06/2.gif'><IMG SRC='../Resources/count/style06/3.gif'><IMG SRC='../Resources/count/style06/4.gif'><IMG SRC='../Resources/count/style06/5.gif'><IMG SRC='../Resources/count/style06/6.gif'><IMG SRC='../Resources/count/style06/7.gif'><IMG SRC='../Resources/count/style06/8.gif'><IMG SRC='../Resources/count/style06/9.gif'>";			

		}

	   	if(a == 7){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style07/0.gif'><IMG SRC='../Resources/count/style07/1.gif'><IMG SRC='../Resources/count/style07/2.gif'><IMG SRC='../Resources/count/style07/3.gif'><IMG SRC='../Resources/count/style07/4.gif'><IMG SRC='../Resources/count/style07/5.gif'><IMG SRC='../Resources/count/style07/6.gif'><IMG SRC='../Resources/count/style07/7.gif'><IMG SRC='../Resources/count/style07/8.gif'><IMG SRC='../Resources/count/style07/9.gif'>";		

		}

	   	if(a == 8){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style08/0.gif'><IMG SRC='../Resources/count/style08/1.gif'><IMG SRC='../Resources/count/style08/2.gif'><IMG SRC='../Resources/count/style08/3.gif'><IMG SRC='../Resources/count/style08/4.gif'><IMG SRC='../Resources/count/style08/5.gif'><IMG SRC='../Resources/count/style08/6.gif'><IMG SRC='../Resources/count/style08/7.gif'><IMG SRC='../Resources/count/style08/8.gif'><IMG SRC='../Resources/count/style08/9.gif'>";			

		}

	   	if(a == 9){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style09/0.gif'><IMG SRC='../Resources/count/style09/1.gif'><IMG SRC='../Resources/count/style09/2.gif'><IMG SRC='../Resources/count/style09/3.gif'><IMG SRC='../Resources/count/style09/4.gif'><IMG SRC='../Resources/count/style09/5.gif'><IMG SRC='../Resources/count/style09/6.gif'><IMG SRC='../Resources/count/style09/7.gif'><IMG SRC='../Resources/count/style09/8.gif'><IMG SRC='../Resources/count/style09/9.gif'>";			

		}	

	   	if(a == 10){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style10/0.gif'><IMG SRC='../Resources/count/style10/1.gif'><IMG SRC='../Resources/count/style10/2.gif'><IMG SRC='../Resources/count/style10/3.gif'><IMG SRC='../Resources/count/style10/4.gif'><IMG SRC='../Resources/count/style10/5.gif'><IMG SRC='../Resources/count/style10/6.gif'><IMG SRC='../Resources/count/style10/7.gif'><IMG SRC='../Resources/count/style10/8.gif'><IMG SRC='../Resources/count/style10/9.gif'>";			

		}	

	   	if(a == 11){

			countStyleDisplay.innerHTML = "<IMG SRC='../Resources/count/style11/0.gif'><IMG SRC='../Resources/count/style11/1.gif'><IMG SRC='../Resources/count/style11/2.gif'><IMG SRC='../Resources/count/style11/3.gif'><IMG SRC='../Resources/count/style11/4.gif'><IMG SRC='../Resources/count/style11/5.gif'><IMG SRC='../Resources/count/style11/6.gif'><IMG SRC='../Resources/count/style11/7.gif'><IMG SRC='../Resources/count/style11/8.gif'><IMG SRC='../Resources/count/style11/9.gif'>";			

		}																				

		

	}	

</SCRIPT>

<div id="contain_out">

  <FORM name=form1 action='' method='post'  enctype="multipart/form-data" id="theform">

    <?php  include_once "Order_state.php";?>

<input type="hidden" name="Action" value="Modi">

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

                <TBODY>

                  <TR>

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>

                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Index_Set]?></SPAN></TD>

                    </TR>

                  </TBODY>

                </TABLE>

              </TD>

            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>

              <TBODY>

                <TR>

                  <!--TD align=middle>

                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>

                    <TBODY>

                    <TR>

                      <TD align=middle width=79><!--BUTTON_BEGIN

                        <TABLE>

                          <TBODY>

                          <TR>

                            <TD vAlign=bottom noWrap>

							<a href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END</TD></TR></TBODY></TABLE></TD-->

                  

                  <TD align=middle>

                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>

                      <TBODY>

                        <TR>

                          <TD align=middle width=79><!--BUTTON_BEGIN-->

                            <TABLE>

                              <TBODY>

                                <TR>

                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save']?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->

                            

                            </TD></TR></TBODY></TABLE>

                    

                    </TD>

                  

                  

                  

                  

                  

                  </TR></TBODY></TABLE>

              </TD>

            </TR>

          </TBODY>

        </TABLE>

      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD vAlign=top height=262>

              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>

                <TBODY>

                  <TR>

                    <TD vAlign=top bgColor=#ffffff height=300>

                      <TABLE class=allborder cellSpacing=0 cellPadding=5  width="100%" align=center bgColor=#f7f7f7 border=0>

                        <TBODY>

                          <TR>

                            <TD noWrap align=right width="18%">&nbsp;</TD>

                            <TD colspan="3" align=right noWrap>&nbsp;</TD></TR>
                          <TR>
                            <TD align=right noWrap bgcolor="#FFFFFF">頭部樣式：</TD>
                            <TD colspan="3" align=left noWrap bgcolor="#FFFFFF"><table width="835" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px">
                              <tbody>
                                <tr>
                                  <td width="129" valign="top"><select name="headerStyle"  class="trans-input" onchange="viewCount(headerStyle,this.value);">
                                    <option value="0" <?php if ($INFO['headerStyle']=='0') { echo " selected=\"selected\" ";}  ?>>樣式一</option>
                                    <option value="1" <?php if ($INFO['headerStyle']=='1') { echo " selected=\"selected\" ";}  ?>>樣式二</option>
                                    <option value="2" <?php if ($INFO['headerStyle']=='2') { echo " selected=\"selected\" ";}  ?>>樣式三</option>
                                  </select></td>
                                  <td width="706"><span id="menuStyleDisplay2">
                                    <?php if ($INFO['menuStyle']!=''){	?>
                                    <img src="images/menuStyle/<?php echo $INFO['menuStyle'];?>.png" />
                                    <?php  }?>
                                  </span></td>
                                </tr>
                              </tbody>
                            </table></TD>
                          </TR>

                          

                          <TR>

                            <TD align=right valign="top" noWrap>商品分類選單樣式：</TD>

                            <TD colspan="3" align=left noWrap><table width="835" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px">

                              <tbody>

                                <tr>

                                  <td width="129" valign="top"><select name="menuStyle"  class="trans-input" onchange="viewCount(menuStyle,this.value);">


                                    <option value="0" <?php if ($INFO['menuStyle']=='0') { echo " selected=\"selected\" ";}  ?>>階層樣式</option>

                                    <option value="1" <?php if ($INFO['menuStyle']=='1') { echo " selected=\"selected\" ";}  ?>>樣式二</option>

                                    <option value="2" <?php if ($INFO['menuStyle']=='2') { echo " selected=\"selected\" ";}  ?>>展開樣式</option>

                                  </select></td>

                                  <td width="706"><span id="menuStyleDisplay">

                              <?php if ($INFO['menuStyle']!=''){	?>

                              <img src="images/menuStyle/<?php echo $INFO['menuStyle'];?>.png">

                              <?php  }?>

                            </span></td>

                                </tr>

                              </tbody>

                            </table>

                            </TD>

                          </TR>

                          <TR bgcolor="#FFFFFF">

                            <TD align=right valign="top" noWrap>首頁自由編輯區一：</TD>

                            <TD colspan="3" align=left noWrap><input name="free_info1" type="radio" value="1" <?php if ($INFO['free_info1']==1){ echo " checked ";}?> />

                              <?php echo $Basic_Command['Yes']?>

                              <input name="free_info1" type="radio" value="0" <?php if ($INFO['free_info1']==0){ echo " checked ";}?> />

                            <?php echo $Basic_Command['No']?> &nbsp;&nbsp;&nbsp;&nbsp;<a href="admin_otherinfo.php?Action=Modi&amp;info_id=23"><i class="icon-edit"></i> 前往編輯</a></TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right>首頁二則廣告： </TD>

                            <TD colspan="3" align=left noWrap><input name="two_adv" type="radio" value="1" <?php if ($INFO['two_adv']==1){ echo " checked ";}?> />

                              <?php echo $Basic_Command['Yes']?>

                              <input name="two_adv" type="radio" value="0" <?php if ($INFO['two_adv']==0){ echo " checked ";}?> />

                            <?php echo $Basic_Command['No']?>  &nbsp;&nbsp;&nbsp;&nbsp;<a href="admin_adv_list.php"><i class="icon-edit"></i> 前往編輯</a> index_top1、index_top2</TD>

                          </TR>

                          <TR bgcolor="#FFFFFF">

                            <TD align=right noWrap>首頁三則廣告：</TD>

                            <TD colspan="3" align=left noWrap><input name="three_adv" type="radio" value="1" <?php if ($INFO['three_adv']==1){ echo " checked ";}?> />

                              <?php echo $Basic_Command['Yes']?>

                              <input name="three_adv" type="radio" value="0" <?php if ($INFO['three_adv']==0){ echo " checked ";}?> />

                            <?php echo $Basic_Command['No']?>  &nbsp;&nbsp;&nbsp;&nbsp;<a href="admin_adv_list.php"><i class="icon-edit"></i> 前往編輯</a> index_medium1、index_medium2、index_medium3</TD>

                          </TR>

                          <TR bgcolor="#FFFFFF">

                            <TD align=right noWrap bgcolor="#F7F7F7">首頁最新商品：</TD>

                            <TD colspan="3" align=left noWrap bgcolor="#F7F7F7"><input name="new_pd" type="radio" value="1" <?php if ($INFO['new_pd']==1){ echo " checked ";}?> />

                              <?php echo $Basic_Command['Yes']?>

                              <input name="new_pd" type="radio" value="0" <?php if ($INFO['new_pd']==0){ echo " checked ";}?> />

                            <?php echo $Basic_Command['No']?></TD>

                          </TR>
                          <TR bgcolor="#FFFFFF">
                            <TD align=right noWrap bgcolor="#F7F7F7">首頁自由編輯區二：</TD>
                            <TD colspan="3" align=left noWrap bgcolor="#F7F7F7"><input name="free_info2" type="radio" value="1" <?php if ($INFO['free_info2']==1){ echo " checked ";}?> />
                              <?php echo $Basic_Command['Yes']?>
                              <input name="free_info2" type="radio" value="0" <?php if ($INFO['free_info2']==0){ echo " checked ";}?> />
                            <?php echo $Basic_Command['No']?> &nbsp;&nbsp;&nbsp;&nbsp;<a href="admin_otherinfo.php?Action=Modi&amp;info_id=25"><i class="icon-edit"></i> 前往編輯</a></TD>
                          </TR>

                          <TR bgcolor="#FFFFFF">

                            <TD align=right valign="top" noWrap>首頁推薦商品：</TD>

                            <TD colspan="3" align=left valign="top" noWrap><input name="rm_pd" type="radio" value="1" <?php if ($INFO['rm_pd']==1){ echo " checked ";}?> />

                              <?php echo $Basic_Command['Yes']?>

                              <input name="rm_pd" type="radio" value="0" <?php if ($INFO['rm_pd']==0){ echo " checked ";}?> />

                              <?php echo $Basic_Command['No']?>

                            

                            

                            </TD>

                          </TR>

                          <TR>

                            <TD align=right valign="top" noWrap>首頁聚合商品：</TD>

                            <TD colspan="3" align=left valign="top" noWrap><input name="jh_pd" type="radio" value="1" <?php if ($INFO['jh_pd']==1){ echo " checked ";}?> />

                              <?php echo $Basic_Command['Yes']?>

                              <input name="jh_pd" type="radio" value="0" <?php if ($INFO['jh_pd']==0){ echo " checked ";}?> />

                            <?php echo $Basic_Command['No']?></TD>

                          </TR>

                          <TR bgcolor="#FFFFFF">

                            <TD noWrap align=right><?php echo $Admin_Indexset[IndexPageIfOpenFloatAdv]?>：<!--首页是否开启浮动广告：--></TD>

                            <TD colspan="3" align=left noWrap>

                              <input type="radio" name="float_radio" value="1" <?php if ($INFO['float_radio']==1){ echo " checked ";}?> onclick=view(float_diplay,1) ><?php echo $Basic_Command['Yes']?>

                              <input type="radio" name="float_radio" value="0" <?php if ($INFO['float_radio']==0){ echo " checked ";}?> onclick=view(float_diplay,0) ><?php echo $Basic_Command['No']?></TD>

                          </TR>

                          <? $float_diplay =  $INFO['float_radio']==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>

                          <TR bgcolor="#FFFFCC" id=float_diplay <?php echo $float_diplay;?>>

                            <TD align=right noWrap><?php echo $Admin_Indexset[FloatAdv] ?>：<!--浮动广告--></TD>

                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}advertising` where adv_display=1 AND adv_type=1 order by adv_id desc ","float_adv_id","adv_id","adv_title",$INFO['float_adv_id'])?></TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right><?php echo $Admin_Indexset[IndexPageIfOpenEarAdv] ;//首页是否开启耳朵广告：?>：</TD>

                            <TD colspan="3" align=left noWrap>

                              <input type="radio" name="ear_radio" value="1" <?php if ($INFO['ear_radio']==1){ echo " checked ";}?> onclick=view(ear_diplay,1) ><?php echo $Basic_Command['Yes']?>

                              <input type="radio" name="ear_radio" value="0" <?php if ($INFO['ear_radio']==0){ echo " checked ";}?> onclick=view(ear_diplay,0) ><?php echo $Basic_Command['No']?>					  </TD>

                          </TR>

                          <? $ear_diplay =  $INFO['ear_radio']==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>

                          <TR bgcolor="#FFFFCC" id=ear_diplay <?php echo $ear_diplay;?>>

                            <TD align=right noWrap><?php echo $Admin_Indexset[EarAdv]  ?>：<!--耳朵广告--></TD>

                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}advertising` where adv_display=1 AND adv_type=2 order by adv_id desc ","ear_adv_id","adv_id","adv_title",$INFO['ear_adv_id'])?></TD>

                          </TR>

                          <TR bgcolor="#FFF">

                            <TD align=right noWrap>主形象廣告樣式：</TD>

                            <TD colspan="3" align=left noWrap>

                             <input name="banner_type" type="radio" value="1" <?php if ($INFO['banner_type']==1){ echo " checked ";}?> >全版輪播廣告

                             <input name="banner_type" type="radio" value="0" <?php if ($INFO['banner_type']==0){ echo " checked ";}?> >輪播廣告  &nbsp;&nbsp;&nbsp;&nbsp; <a href="admin_adv_list.php"><i class="icon-edit"></i> 前往編輯</a></TD>

                          </TR>

                          <TR bgcolor="#F7F7F7">

                            <TD align=right noWrap>外盒模式：</TD>

                            <TD colspan="3" align=left noWrap>

                             <input name="boxed" type="radio" value="1" <?php if ($INFO['boxed']==1){ echo " checked ";}?> ><?php echo $Basic_Command['Yes']?>

                             <input name="boxed" type="radio" value="0" <?php if ($INFO['boxed']==0){ echo " checked ";}?>  ><?php echo $Basic_Command['No']?>

                            </TD>

                          </TR>

                          <TR bgcolor="#FFF">

                            <TD align=right noWrap>主色：</TD>

                            <TD colspan="3" align=left noWrap>

                            <input name="main_color" type="radio" value="1" <?php if ($INFO['main_color']==1){ echo " checked ";}?> >白色

                             <input name="main_color" type="radio" value="0" <?php if ($INFO['main_color']==0){ echo " checked ";}?>  >黑色

                            

                            </TD>

                          </TR>

                          <TR>

                            <TD align=right noWrap>工具選單顏色：</TD>

                            <TD colspan="3" align=left noWrap><input name="toolbar_color" type="radio" value="1" <?php if ($INFO['toolbar_color']==1){ echo " checked ";}?> />

                              白色

                                <input name="toolbar_color" type="radio" value="0" <?php if ($INFO['toolbar_color']==0){ echo " checked ";}?> />

                            黑色 </TD>

                          </TR>

                          <TR bgcolor="#FFF">

                            <TD align=right noWrap>價格顏色：</TD>

                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','price_color',$INFO[price_color],"    maxLength=10 size=10   ")?></TD>

                          </TR>

                          <TR>

                            <TD align=right noWrap>特別色：</TD>

                            <TD colspan="3" align=left noWrap>

                             <input name="css_id" type="radio" value="0" <?php if ($INFO['css_id']==0){ echo " checked ";}?> >

                             <img src="images/color_schemes/2.png" width="30" height="30" alt=""/>

<input name="css_id" type="radio" value="1" <?php if ($INFO['css_id']==1){ echo " checked ";}?>  >

                             <img src="images/color_schemes/1.png" width="30" height="30" alt=""/>

                             <input name="css_id" type="radio" value="2" <?php if ($INFO['css_id']==2){ echo " checked ";}?>  >

<img src="images/color_schemes/3.png" width="30" height="30" alt=""/>

<input name="css_id" type="radio" value="3" <?php if ($INFO['css_id']==3){ echo " checked ";}?>  >

<img src="images/color_schemes/4.png" width="30" height="30" alt=""/>

<input name="css_id" type="radio" value="4" <?php if ($INFO['css_id']==4){ echo " checked ";}?>  >

<img src="images/color_schemes/5.png" width="30" height="30" alt=""/>

<input name="css_id" type="radio" value="5" <?php if ($INFO['css_id']==5){ echo " checked ";}?>  >

<img src="images/color_schemes/6.png" width="30" height="30" alt=""/>

<input name="css_id" type="radio" value="6" <?php if ($INFO['css_id']==6){ echo " checked ";}?>  >

<img src="images/color_schemes/7.png" width="30" height="30" alt=""/>

<input name="css_id" type="radio" value="7" <?php if ($INFO['css_id']==7){ echo " checked ";}?>  >

<img src="images/color_schemes/8.png" width="30" height="30" alt=""/>

<input name="css_id" type="radio" value="8" <?php if ($INFO['css_id']==8){ echo " checked ";}?>  >

<img src="images/color_schemes/9.png" width="30" height="30" alt=""/>

<input name="css_id" type="radio" value="9" <?php if ($INFO['css_id']==9){ echo " checked ";}?>  >

<img src="images/color_schemes/10.png" width="30" height="30" alt=""/></TD>

                          </TR>

                          <TR>

                            <TD align=right noWrap>使用底紋：</TD>

                            <TD colspan="3" align=left noWrap>

                            <input name="use_pattern" type="radio" value="1" <?php if ($INFO['use_pattern']==1){ echo " checked ";}?> ><?php echo $Basic_Command['Yes']?>

                             <input name="use_pattern" type="radio" value="0" <?php if ($INFO['use_pattern']==0){ echo " checked ";}?>  ><?php echo $Basic_Command['No']?>

                            

                            </TD>

                          </TR>

                          <TR>

                            <TD align=right noWrap>底紋：</TD>

                            <TD colspan="3" align=left noWrap>

<input name="pattern_id" type="radio" value="1" <?php if ($INFO['pattern_id']==1){ echo " checked ";}?>  >

<img src="images/patterns/pattern1.png" width="30" height="30" alt=""/>

<input name="pattern_id" type="radio" value="2" <?php if ($INFO['pattern_id']==2){ echo " checked ";}?>  >

<img src="images/patterns/pattern2.png" width="30" height="30" alt=""/>

<input name="pattern_id" type="radio" value="3" <?php if ($INFO['pattern_id']==3){ echo " checked ";}?>  >

<img src="images/patterns/pattern3.png" width="30" height="30" alt=""/>

<input name="pattern_id" type="radio" value="4" <?php if ($INFO['pattern_id']==4){ echo " checked ";}?>  >

<img src="images/patterns/pattern4.png" width="30" height="30" alt=""/>

<input name="pattern_id" type="radio" value="5" <?php if ($INFO['pattern_id']==5){ echo " checked ";}?>  >

<img src="images/patterns/pattern5.png" width="30" height="30" alt=""/>

<input name="pattern_id" type="radio" value="6" <?php if ($INFO['pattern_id']==6){ echo " checked ";}?>  >

<img src="images/patterns/pattern6.png" width="30" height="30" alt=""/>

<input name="pattern_id" type="radio" value="7" <?php if ($INFO['pattern_id']==7){ echo " checked ";}?>  >

<img src="images/patterns/pattern7.png" width="30" height="30" alt=""/>

<input name="pattern_id" type="radio" value="8" <?php if ($INFO['pattern_id']==8){ echo " checked ";}?>  >

<img src="images/patterns/pattern8.png" width="30" height="30" alt=""/>

<input name="pattern_id" type="radio" value="9" <?php if ($INFO['pattern_id']==9){ echo " checked ";}?>  >

<img src="images/patterns/pattern9.png" width="30" height="30" alt=""/>

<input name="pattern_id" type="radio" value="10" <?php if ($INFO['pattern_id']==10){ echo " checked ";}?> >

<img src="images/patterns/pattern10.png" width="30" height="30" alt=""/>

                            </TD>

                          </TR>

                          

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD align=left noWrap>&nbsp;</TD>

                            <TD align=left noWrap>&nbsp;</TD>

                            <TD align=left noWrap>&nbsp;</TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right>Shop LOGO：</TD>

                            <TD width="27%" align=left noWrap><input type="file" name="shop_logo"  id="shop_logo"   />

                              <a href="#" class="easyui-tooltip" title="<?php echo $Admin_Indexset[Comment_shop_logo];?>"><img src="images/tip.png" width="16" height="16" border="0"></a>

                            </TD>

                            <TD width="3%" align=left noWrap>&nbsp;</TD>

                            <TD width="52%" align=left noWrap>

                              <?php echo $Admin_Indexset[Width]?>：

                              <?php echo $FUNCTIONS->Input_Box('text','logo_width',$INFO[logo_width],"    maxLength=10 size=10 ")?>

                              &nbsp;&nbsp;&nbsp;

                              <?php echo $Admin_Indexset[Height]?>：

                              <?php echo $FUNCTIONS->Input_Box('text','logo_height',$INFO[logo_height],"     maxLength=10 size=10 ")?>						</TD>

                          </TR>

                          <TR>

                            <TD align=right noWrap>&nbsp;</TD>

                            <TD colspan="3" align=left noWrap>

                              <?php if ($INFO['shop_logo']!=""){?>

                              <img src="<?php echo "../UploadFile/LogoPic/" . $INFO['shop_logo'];?>">

                              <?php } ?>					  </TD>

                          </TR>

                          <TR>

                            <TD align=right noWrap>使用背景圖片：</TD>

                            <TD colspan="3" align=left noWrap>

                            <input name="use_background" type="radio" value="1" <?php if ($INFO['use_background']==1){ echo " checked ";}?> ><?php echo $Basic_Command['Yes']?>

                             <input name="use_background" type="radio" value="0" <?php if ($INFO['use_background']==0){ echo " checked ";}?>  ><?php echo $Basic_Command['No']?>

                            </TD>

                          </TR>

                          <TR>

                            <TD align=right noWrap>背景圖片：</TD>

                            <TD colspan="3" align=left noWrap><input type="file" name="shop_background"  id="shop_background"   /></TD>

                          </TR>

                          <TR>

                            <TD rowspan="2" align=right noWrap>&nbsp;</TD>

                            <TD colspan="3" align=left noWrap>

                            <?php if ($INFO['shop_background']!=""){?>

                              <img src="<?php echo "../UploadFile/LogoPic/" . $INFO['shop_background'];?>" width="200px">

                              <?php } ?>					  

                            </TD>

                          </TR>

                          <TR>

                            <TD colspan="3" align=left noWrap>&nbsp;</TD>

                          </TR>

                          <!--TR>

                            <TD noWrap align=right>Forum  LOGO：</TD>

                            <TD width="27%" align=left noWrap><input type="file" name="forum_logo"  id="forum_logo" />

                              <a href="#" class="easyui-tooltip" title="<?php echo $Admin_Indexset[Comment_forum_logo];?>"><img src="images/tip.png" width="16" height="16" border="0"></a>

                            </TD>

                            <TD width="3%" align=left noWrap>&nbsp;</TD>

                            <TD width="52%" align=left noWrap><?php echo $Admin_Indexset[Width]?>：

                              <?php echo $FUNCTIONS->Input_Box('text','forum_logo_width',$INFO[forum_logo_width],"      maxLength=10 size=10 ")?>

                              &nbsp;&nbsp;&nbsp;

                              <?php echo $Admin_Indexset[Height]?>：

                              <?php echo $FUNCTIONS->Input_Box('text','forum_logo_height',$INFO[forum_logo_height],"      maxLength=10 size=10 ")?></TD>

                          </TR-->

                          <!--TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD colspan="3" align=left noWrap>

                              <? if (is_file("../templates/".$INFO['templates']."/images/forum_logo.jpg")){?>

                              <img src="<?php echo "../templates/".$INFO['templates']."/images/forum_logo.jpg";?>" width="200px">

                              <? } ?>					  </TD>

                          </TR-->

                          <TR>

                            <TD noWrap align=right>Facebook崁入：</TD>

                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->Input_Box('textarea','fb_pluging',$INFO[fb_pluging]," cols=72 rows=4  ")?><br />

                            <div style="margin:5px 0 5px 0">取得 <a href="https://developers.facebook.com/docs/plugins/page-plugin" target="_blank"><span style="font-size:18px;color:#282f8b;"><i class="icon-facebook-sign"></i> page-plugin</span></a></div></TD>

                          </TR>

                          

                          <!--TR>

                            <TD align=right valign="middle" noWrap><?php echo $Admin_Indexset[IndexCount] ?>：</TD>

                            <TD colspan="3" align=left valign="middle" noWrap>

                              <select name="countStyle"  class="trans-input" onChange="viewCount(countStyle,this.value);">

                                <option value="no"> -- <?php echo $Basic_Command['Please_Select'];?> -- </option>

                                <option value="00" <?php if ($INFO['countStyle']=='00') { echo " selected=\"selected\" ";}  ?>>00</option>

                                <option value="01" <?php if ($INFO['countStyle']=='01') { echo " selected=\"selected\" ";}  ?>>01</option>

                                <option value="02" <?php if ($INFO['countStyle']=='02') { echo " selected=\"selected\" ";}  ?>>02</option>

                                <option value="03" <?php if ($INFO['countStyle']=='03') { echo " selected=\"selected\" ";}  ?>>03</option>

                                <option value="04" <?php if ($INFO['countStyle']=='04') { echo " selected=\"selected\" ";}  ?>>04</option>

                                <option value="05" <?php if ($INFO['countStyle']=='05') { echo " selected=\"selected\" ";}  ?>>05</option>

                                <option value="06" <?php if ($INFO['countStyle']=='06') { echo " selected=\"selected\" ";}  ?>>06</option>

                                <option value="07" <?php if ($INFO['countStyle']=='07') { echo " selected=\"selected\" ";}  ?>>07</option>

                                <option value="08" <?php if ($INFO['countStyle']=='08') { echo " selected=\"selected\" ";}  ?>>08</option> 

                                <option value="09" <?php if ($INFO['countStyle']=='09') { echo " selected=\"selected\" ";}  ?>>09</option>	

                                <option value="10" <?php if ($INFO['countStyle']=='10') { echo " selected=\"selected\" ";}  ?>>10</option>	

                                <option value="11" <?php if ($INFO['countStyle']=='11') { echo " selected=\"selected\" ";}  ?>>11</option>						

                              </select>

                              <span id='countStyleDisplay'>

                                <?php if ($INFO['countStyle']!=''){	?>

                                <IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/0.gif'><IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/1.gif'><IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/2.gif'><IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/3.gif'><IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/4.gif'><IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/5.gif'><IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/6.gif'><IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/7.gif'><IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/8.gif'><IMG SRC='../Resources/count/style<?php echo $INFO['countStyle'];?>/9.gif'>

                                <?php  }?>

                              </span>					  </TD>

                          </TR>

                          <TR>

                            <TD align=right valign="middle" noWrap><?php echo $Admin_Indexset[ModiCountNum] ;?>：</TD>

                            <TD colspan="3" align=left noWrap>

                              <?php 

                      $handle = fopen (RootDocumentShare."/cache/countNum.php", "rb");

                        while (!feof ($handle)) {

                         $buffer = fgets($handle, 4096);                         

                      }

                      fclose ($handle);						

					  ?>

                              <input name="countnum" value="<?php echo intval($buffer);?>"  type="text" />

                              <input name="hidden_countnum" value="<?php echo intval($buffer);?>"   type="hidden" />

                              </TD>

                            </TR-->

                          <TR>

                            <TD align=right valign="middle" noWrap>列表圖片顯示模式：</TD>

                            <TD colspan="3" align=left noWrap><input name="pic_fix" type="radio" value="1" <?php if ($INFO['pic_fix']==1){ echo " checked ";}?> />

                              寬高修正在方形範圍內

                              <input name="pic_fix" type="radio" value="0" <?php if ($INFO['pic_fix']==0){ echo " checked ";}?> />

                            不修正</TD>

                          </TR>

                          <TR>

                            <TD align=right valign="middle" noWrap>首頁最新消息：</TD>

                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->select_type("select ncid,ncname,ncatiffb  from `{$INFO[DBPrefix]}nclass` where ncatiffb=1 order by ncid asc ","IndexNewClassId","ncid","ncname",$INFO['IndexNewClassId'])?>	 </TD>

                          </TR>
                          <TR>
                            <TD align=right valign="middle" noWrap>首頁部落格文章：</TD>
                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->select_type("select ncid,ncname,ncatiffb  from `{$INFO[DBPrefix]}nclass` where ncatiffb=1 order by ncid asc ","IndexNewClassId1","ncid","ncname",$INFO['IndexNewClassId1'])?></TD>
                          </TR>

                          <TR>

                            <TD align=right valign="middle" noWrap><?php echo  $Admin_Indexset[IndexIframe]?>：</TD>

                            <TD colspan="3" align=left noWrap><?php echo $InitAjax ;

                      //echo $indexIframeSQL   = "select adv_tag,adv_title from `{$INFO[DBPrefix]}advertising` where adv_display=1 AND adv_type=3  order by adv_id desc ";

					  ?>

                              <select name="index_iframe_id" onchange='getIndex_iframe_id(this.value);'>

                                <option value=""> -- <?php echo $Basic_Command['Please_Select'];?> -- </option>

                                <?php //echo $FUNCTIONS->select_type("select adv_tag,adv_title from `{$INFO[DBPrefix]}advertising` where adv_display=1 AND adv_type=3  order by adv_id desc ","index_iframe_id","adv_tag","adv_title",$INFO['index_iframe_id'])?>

                                <?php 

					  $indexIframeSQL   = "select adv_tag,adv_title from `{$INFO[DBPrefix]}advertising` where adv_display=1 AND adv_type=3  order by adv_id desc ";

					  $indexIframeQuery = $DB->query($indexIframeSQL);

					  while ($indexIframeRs = $DB->fetch_array($indexIframeQuery)){

					   ?>

                                <option value="<?php echo $indexIframeRs['adv_tag']?>" <?php if ($INFO['index_iframe_id']==$indexIframeRs['adv_tag']) { echo " selected=\"selected\" ";}?>><?php echo $indexIframeRs['adv_title']?></option>					

                                

                                <?php

					      }

					   ?>

                              </select>

                              &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Admin_Indexset[Height]?>：

                              <?php echo $FUNCTIONS->Input_Box('text','index_iframe_height',$INFO[index_iframe_height],"      maxLength=10 size=10 ")?></TD>

                          </TR>

                          <TR  id="iframe" style="Z-INDEX: 10; display: none">

                            <TD colspan="4" align=center noWrap><div id="show_ifidcontent"></div></TD>

                          </TR>

                          

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD colspan="3" align=left noWrap>&nbsp;</TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right><b><?php echo $JsMenu[Poll_Man];//投票管理?></b></TD>

                            <TD colspan="3" align=left noWrap>&nbsp;</TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right><?php echo $Admin_Indexset[IndexPagePollTitle];?><!--首页调查条目-->：</TD>

                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}poll` where open=1 order by poll_id desc ","Poll_id","poll_id","title",$INFO['Poll_id'])?></TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD colspan="3" align=left noWrap>&nbsp;</TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right><b><?php echo $JsMenu[Subject_Man]?></b></TD>

                            <TD colspan="3" align=left noWrap>&nbsp;</TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right><?php echo  $Admin_Indexset[Default_DisplayClass] ?>：</TD>

                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}subject` where subject_open=1 order by subject_id desc ","subject_id","subject_id","subject_name",$INFO['subject_id'])?></TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD colspan="3" align=left noWrap>&nbsp;</TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD colspan="3" align=left noWrap>&nbsp;</TD>

                          </TR>

                        </TBODY>

                  </TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>

    </TR>

  </FORM>

</div>

<div align="center"><?php include_once "botto.php";?></div>

</BODY>

</HTML>

<?php

if ( $_POST['Action']=="Modi" ){



    if (intval($_POST[hidden_countnum])!=intval($_POST[countnum])){

	

	$file= RootDocumentShare."/countNum.php";	//记录数据的文件

	

	if(!file_exists($file))		//判断是否存在文件

     {

	    chmod("../".ConfigDir,0777);	//修改文件夹属性

     }

	

	if(!is_writeable($file))	//判断文件是否可写

	 {

		chmod($file,0777);		//修改文件属性

	 }

	 

	 $fo2=fopen($file,'w+');		//以可写方式打开文件

     fputs($fo2,intval($_POST[countnum]));

	 fclose($fo2);

	

	}

	if (trim($_FILES['shop_logo']['name'])!=""){

		$shop_logo   = $FUNCTIONS->Upload_File($_FILES['shop_logo']['name'],$_FILES['shop_logo']['tmp_name'],"","../UploadFile/LogoPic/");

		$_POST['shop_logo'] = $shop_logo;

		//@rename ("../templates/".$INFO['templates']."/images/logo.jpg","../templates/".$INFO['templates']."/images/logo_bak.jpg");

		//@unlink ("../templates/".$INFO['templates']."/images/logo.jpg");

		//@copy ("../templates/".$INFO[templates]."/images/".$shop_logo,"../templates/".$INFO['templates']."/images/logo.jpg");

	}else{

		$_POST['shop_logo'] = $INFO['shop_logo'];	

	}



	if (trim($_FILES['shop_background']['name'])!=""){

		$shop_background   = $FUNCTIONS->Upload_File($_FILES['shop_background']['name'],$_FILES['shop_background']['tmp_name'],"","../UploadFile/LogoPic/");

		$_POST['shop_background'] = $shop_background;

	}else{

		$_POST['shop_background'] = $INFO['shop_background'];	

	}



	if (trim($_FILES['forum_logo']['name'])!=""){

		$forum_logo   = $FUNCTIONS->Upload_File($_FILES['forum_logo']['name'],$_FILES['forum_logo']['tmp_name'],"","../templates/".$INFO[templates]."/images");

		@rename ("../templates/".$INFO['templates']."/images/forum_logo.jpg","../templates/".$INFO['templates']."/images/forum_logo_bak.jpg");

		@unlink ("../templates/".$INFO['templates']."/images/forum_logo.jpg");

		@copy ("../templates/".$INFO[templates]."/images/".$forum_logo,"../templates/".$INFO['templates']."/images/forum_logo.jpg");

	}

	if (trim($_FILES['cart_logo']['name'])!=""){

		$cart_logo   = $FUNCTIONS->Upload_File($_FILES['cart_logo']['name'],$_FILES['cart_logo']['tmp_name'],"","../templates/".$INFO[templates]."/images");

		@rename ("../templates/".$INFO['templates']."/images/cart_logo.jpg","../templates/".$INFO['templates']."/images/cart_logo_bak.jpg");

		@unlink ("../templates/".$INFO['templates']."/images/cart_logo.jpg");

		@copy ("../templates/".$INFO[templates]."/images/".$cart_logo,"../templates/".$INFO['templates']."/images/cart_logo.jpg");

	}

	$Ex_Function->save_config( $new = array("Poll_id","ear_adv_id","three_adv","two_adv","pop_adv_id","float_adv_id","float_radio","pop_radio","ear_radio","banner_type","boxed","main_color","css_id","use_pattern","pattern_id","shop_background","use_background","logo_width","logo_height","index_iframe_id","index_iframe_height","forum_logo_width","forum_logo_height","cart_logo_width","cart_logo_height","subject_id","IndexNewClassId","IndexNewClassId1","countStyle","shop_logo","menuStyle","headerStyle","price_color","new_pd","rm_pd","jh_pd","free_info1","free_info2","fb_pluging","pic_fix","toolbar_color"),"setindex") ;

	$FUNCTIONS->setLog("編輯首頁設置");

	echo " <script language=javascript>alert('".$Basic_Command[Back_System_Sucuess]."'); location.href='admin_indexseting.php'; </script>";

	//@header("location:admin_indexseting.php");

}

?>

 <script language="javascript">

 getIndex_iframe_id('<?php echo $INFO['index_iframe_id']?>');

 function getIndex_iframe_id(ifid){

 	//alert(ifid);

 	if (typeof(ifid) == 'undefined'){

 		    return false;

 	}

 	var url = "admin_indexiframe.php?adv_tag="+ifid;

 	var show = document.getElementById("show_ifidcontent");

 	var ajax = InitAjax();

 	ajax.open("GET", url, true);

 	ajax.onreadystatechange = function() {

 		//alert ('d');

 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层

 		            	if (ajax.readyState == 4 && ajax.status == 200) {

 		            		        		if (ajax.responseText!="")

 		            		        		        		document.all.iframe.style.display="";

 		            		        		        		      

