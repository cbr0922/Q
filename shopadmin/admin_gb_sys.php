<?php
include_once "Check_Admin.php";


/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
//include "../language/".$INFO['IS']."/TwPayOne_Pack.php";
//include "../language/".$INFO['IS']."/Comment_Pack.php";
include_once "../language/".$INFO['IS']."/Desktop_Pack.php";
include "../language/".$INFO['IS']."/StaticHtml_Pack.php";

$AddrArray = explode("/",$_SERVER['PHP_SELF']);
$Sub_host = "";
foreach ($AddrArray as $k=>$v){
	if ($v!="shopadmin"){
		$Sub_host .= $v."/";
	}elseif($v=="shopadmin"){
		break;
	}
}

function Checked($INputArray,$Value){

	$Array = explode(",",$INputArray);
	foreach ($Array as $k=>$v){
		if ($v==$Value){
			return   "checked";
			break 1;
		}
	}

}

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Basic_Info]?></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>

<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
      <?php  include_once "Order_state.php";?>
  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD>
	</TR>
  </TBODY>
  </TABLE>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<script language="javascript">
function view(obj,a)	{
	if(a == 1){
		obj.style.display="";
	}else{
		obj.style.display="none";
	}
}


function Zfb_openJs(obj,a)
{
	if(a == 1){
		obj.style.display="";
	}else{
		obj.style.display="none";
	}
}

</script>

<SCRIPT language=javascript>
	function checkform(){
		form1.submit();
	}


</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <FORM name='form1' action='' method='post' id="theform">
  <input type="hidden" name="Action" value="Modi">
  <input type="hidden" name="link_pic_path" value="<?php echo $INFO['link_pic_path']?>">
  <input type="hidden" name="good_pic_path" value="<?php echo $INFO['good_pic_path']?>">
  <input type="hidden" name="site_url" value="<?php echo $INFO['site_url']?>">
  <input type="hidden" name="site_shopadmin" value="<?php echo $INFO['site_shopadmin'];?>">
  <input type="hidden" name="DBhostname" value="<?php echo $INFO['DBhostname'];?>">
  <input type="hidden" name="DBuserName" value="<?php echo $INFO['DBuserName'];?>">
  <input type="hidden" name="DBpassword" value="<?php echo $INFO['DBpassword'];?>">
  <input type="hidden" name="DBname" value="<?php echo $INFO['DBname'];?>">
  <input type="hidden" name="DBPrefix" value="<?php echo $INFO['DBPrefix'];?>">
  <input type="hidden" name="absolutePath" value="<?php echo $INFO['absolutePath'];?>">
  <input type="hidden" name="templates" value="<?php echo $INFO['templates'];?>">
  <input type="hidden" name="Sub_Host" value="<?php echo $Sub_host;?>">
  <input type="hidden" name="DiscuzSetupTablePre" value="<?php echo $INFO['DiscuzSetupTablePre'];?>">
  <input type="hidden" name="SetupDiscuz" value="<?php echo $INFO['SetupDiscuz'];?>">



  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1
      src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Basic_Info]?></SPAN></TD>
              </TR>
			  </TBODY>
			 </TABLE>
		  </TD>
          <TD align=right width="50%">
            <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
			  <?php if ($Ie_Type != "mozilla") { ?>
              <TR>
                <!--TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN
                        <TABLE class=fbottonnew link="javascript:window.history.back(-1);">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END</TD></TR></TBODY></TABLE></TD-->

                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew border=0 link="javascript:checkform();">
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap ><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save']?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->

							</TD></TR></TBODY></TABLE>
				</TD>
				</TR>
			   <?php } else {?>
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
							<a  href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END</TD></TR></TBODY></TABLE></TD-->

                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
						 <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap ><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save']?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->

							</TD></TR></TBODY></TABLE>
				</TD>
				</TR>
			   <?php } ?>




			  </TBODY></TABLE></TD></TR>
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
                  <TABLE class=allborder cellSpacing=0 cellPadding=2  width="96%" align=center bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="20%">&nbsp;</TD>
                      <TD colspan="3" align=right noWrap> </TD></TR>
                    <TR>
                      <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_SiteName]?>：</TD>
                      <TD width="33%" align=left noWrap>



					  <?php echo $FUNCTIONS->Input_Box('text','site_name',$INFO['site_name'],"     maxLength=100 size=30 ")?>					  </TD>
                      <TD width="12%" align=right noWrap><?php echo $Admin_sys_Pack[Sys_ShopTitle]?>：</TD>
                      <TD width="35%" align=left noWrap>

					  <?php echo $FUNCTIONS->Input_Box('text','site_title',$INFO['site_title'],"     maxLength='100' size='30'  ")?></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_NetName]?>：</TD>
                      <TD align=left noWrap>
					  <?php echo $FUNCTIONS->Input_Box('text','site_urls',$INFO['site_url'],"          maxLength='100' size='50'  disabled='disabled' ")?>                      </TD>
                      <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_ProductClassAttribNum]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','b_attr',$INFO['b_attr'],"  maxLength=10 size=10  ")?></TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right>Manage Path：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','site_shopadmins',$INFO['site_shopadmin'],"   maxLength='100' size='50'   disabled='disabled' ")?></TD>
                      <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_MaxNewProductNum]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','MaxNewProductNum',$INFO['MaxNewProductNum']," maxLength=10 size=10")?></TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_CompanyName]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','company_name',$INFO['company_name'],"          maxLength=200 size=30  ")?></TD>
                      <TD align=right noWrap><?php echo $Admin_sys_Pack[MaxProductNumForList]?>：</TD>
                      <TD align=left noWrap><div id='b_attrtips' class="tips"><?php echo $Admin_sys_Pack[Sys_ProductClassAttribNum_intro]?></div>
                        <?php echo $FUNCTIONS->Input_Box('text','MaxProductNumForList',$INFO['MaxProductNumForList'],"  maxLength=3 size=3 ")?></TD>
                      </TR>
			         <TR>
                      <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Lxr] ?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','lxr',$INFO['tel'],"          maxLength=30 size=30  ")?>					  </TD>
                      <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_MinBuyMoney];?>：</TD>
                      <TD align=left noWrap><div id='MaxNewProductNumtips' class="tips"><?php echo $Admin_sys_Pack[Sys_MaxNewProductNum_intro]?></div>
                        <?php echo $FUNCTIONS->Input_Box('text','MinBuyMoney',$INFO['MinBuyMoney'],"  maxLength=10 size=10  ")?></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Sex]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio_strand','sex',$INFO['sex'],$add = array($Admin_sys_Pack[Sys_Men],$Admin_sys_Pack[Sys_Women]))?></TD>
                      <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_MaxProductNum];?>：</TD>
                      <TD align=left noWrap><div id='MaxProductNumForListtips' class="tips"><?php echo  $Admin_sys_Pack[Sys_MaxProductNum_intro]?></div>
                        <?php echo $FUNCTIONS->Input_Box('text','buy_product_max_num',$INFO['buy_product_max_num'],"  maxLength=6 size=6 ")?></TD>
                    </TR>

                    <TR>
                      <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Email]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','email',$INFO['email'],"   maxLength=30 size=30  ")?></TD>

                      <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_PInvoice];?>：</TD>
                      <TD align=left noWrap><input type="radio" name="Need_invoice" value="1" <?php if (intval($INFO['Need_invoice'])==1){ echo " checked ";} ?> >
                        <?php echo $Basic_Command['Yes'] ?>
                        <input type="radio" name="Need_invoice" value="0" <?php if (intval($INFO['Need_invoice'])==0){ echo " checked ";} ?> >
                        <?php echo $Basic_Command['No'] ?> </TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_City]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','city',$INFO['city'],"          maxLength=30 size=30  ")?></TD>
                      <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_Flanguage] ?>：</TD>
                      <TD valign="middle" noWrap><select name="IS" id="IS" class="trans-input">
                        <option value="gb"   <?php if ($INFO['IS']=='gb') { echo " selected=\"selected\" ";}?>><?php echo $ARR_LANGNAME['zh']?></option>
                        <option value="big5" <?php if ($INFO['IS']=='big5') { echo " selected=\"selected\" ";}?>><?php echo $ARR_LANGNAME['big5']?></option>
                        <option value="en"   <?php if ($INFO['IS']=='en') { echo " selected=\"selected\" ";}?>><?php echo $ARR_LANGNAME['en']?></option>
                      </select></TD>
                    </TR>

                    <TR>
                      <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Address]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','addr',$INFO['addr'],"          maxLength=200 size=50  ")?></TD>
                      <TD align=right noWrap><!--最大商品购买数字-->
                        <?php echo $Admin_sys_Pack[Sys_Blanguage]?>：</TD>
                      <TD align=left noWrap><div id='buy_product_max_numtips' class="tips"><?php echo $Admin_sys_Pack[WhatIs_buy_product_max_num];?></div>
                        <select name="admin_IS"  id="admin_IS" class="trans-input">
                          <option value="gb"   <?php if ($INFO['admin_IS']=='gb') { echo " selected=\"selected\" ";}?>><?php echo $ARR_LANGNAME['zh']?></option>
                          <option value="big5" <?php if ($INFO['admin_IS']=='big5') { echo " selected=\"selected\" ";}?>><?php echo $ARR_LANGNAME['big5']?></option>
                        <option value="en"   <?php if ($INFO['admin_IS']=='en') { echo " selected=\"selected\" ";}?>><?php echo $ARR_LANGNAME['en']?></option>
                        </select></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_Fax]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','fax',$INFO['fax'],"          maxLength=30 size=30  ")?></TD>
                      <TD align=right noWrap><?php echo $Desktop_Pack[OpenStaticState];?>：</TD>
                      <TD align=left noWrap><input type="radio" name="staticState" value="open" <?php if (trim($INFO['staticState'])=='open'){ echo " checked ";} ?>  />
                        <?php echo $Basic_Command['Yes'] ?> &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="staticState" value="close" <?php if (trim($INFO['staticState'])=='close'){ echo " checked ";} ?>  />
                        <?php echo $Basic_Command['No'] ?></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Mobile]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','other_tel',$INFO['other_tel'],"          maxLength=30 size=30  ")?></TD>
                      <TD align=right noWrap><?php echo $Admin_sys_Pack[OpenDesktopMenu];?>：</TD>
                      <TD align=left noWrap><select name="OpenDesktopMenu" id="OpenDesktopMenu" class="trans-input">
                        <option value="2"   <?php if (intval($INFO['OpenDesktopMenu'])=='2') { echo " selected=\"selected\" ";}?>><?php echo $Basic_Command['Please_Select']?></option>
                        <option value="0" <?php if (intval($INFO['OpenDesktopMenu'])=='0') { echo " selected=\"selected\" ";}?>><?php echo $Desktop_Pack[QuickDoor]?></option>
                        <option value="1"   <?php if (intval($INFO['OpenDesktopMenu'])=='1') { echo " selected=\"selected\" ";}?>><?php echo $Desktop_Pack[favorite_navigation]?></option>
                        <option value="3"   <?php if (intval($INFO['OpenDesktopMenu'])=='3') { echo " selected=\"selected\" ";}?>><?php echo $StaticHtml_Pack[staticStateIntro] ?></option>
                      </select></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_Code]?>： </TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','post',$INFO['post'],"          maxLength=30 size=30  ")?></TD>
                      <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_Stati]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','duration',$INFO['duration'],"   maxLength=10 size=10  ")?> <?php echo $Admin_sys_Pack[Sys_Second]?></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_Tel]?>：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','tel',$INFO['tel'],"          maxLength=30 size=30  ")?></TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD colspan="3" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_SendType] ;//配送方式?>：</TD>
                      <TD colspan="3" align=left noWrap>
                        <input type="radio" name="Paytype" value="0" <?php if (intval($INFO['Paytype'])==0){ echo " checked ";} ?> onclick=view(Paytypeshow,0)>                        <?php echo $Admin_sys_Pack[Sys_ZDYSendType]?>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="Paytype" value="1" <?php if (intval($INFO['Paytype'])==1){ echo " checked ";} ?> onclick=view(Paytypeshow,1)>                        <?php echo $Admin_sys_Pack[Sys_SendProgr]?></TD>
                      </TR>
					<?php $DISPLAYPaytype =  intval($INFO['Paytype'])==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                    <TR id=Paytypeshow <?php echo $DISPLAYPaytype;?>>
                      <TD noWrap align=right>&nbsp; </TD>
                      <TD colspan="3" align=left noWrap bgcolor="#FFFFCC"><?php echo $Admin_sys_Pack[Sys_SendProgr_I]?><!--當金額小于-->
                        <input  id="PayStartprice" name="PayStartprice" type="text"  size="8" maxlength="16" value="<?php echo $INFO['PayStartprice']?>">
						<div id='PayStartpricetips' class="tips"><?php echo $Admin_sys_Pack[Comment_PayStartprice]?></div>
                        <?php echo $Admin_sys_Pack[Sys_Yuan] ?><!--元-->，<?php echo $Admin_sys_Pack[Sys_SendProgr_II];?><!--须加收运费--><input name="PayEndprice" type="text"  size="8" maxlength="16" value="<?php echo $INFO['PayEndprice']?>">
                        <?php echo $Admin_sys_Pack[Sys_Yuan] ?><!--元-->&nbsp;</TD>
                     </TR>

                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD colspan="3" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right><?php echo  $Admin_sys_Pack[openZFB_pay]?><!--启动支付宝-->：</TD>
                      <TD colspan="3" align=left noWrap>
					     <input type="radio" name="zfb_open" value="1" <?php if (intval($INFO['zfb_open'])==1){ echo " checked ";} ?> onclick=Zfb_openJs(Zfbshow,1)>                         <?php echo $Basic_Command['Yes'] ?>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="zfb_open" value="0" <?php if (intval($INFO['zfb_open'])==0){ echo " checked ";} ?> onclick=Zfb_openJs(Zfbshow,0)>                         <?php echo $Basic_Command['No'] ?>                     </TD>
                    </TR>
					<?php $DISPLAYZfb =  intval($INFO['zfb_open'])==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                    <TR id='Zfbshow' <?php echo $DISPLAYZfb;?> >
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD colspan="3" align=left noWrap bgcolor="#FFFFCC">
					  <table width="60%" border="0" cellpadding="0" cellspacing="0" class="listtable">
					    <tr>
                          <td><?php echo $Admin_sys_Pack[ZFB_JyType]?>：</td>
                          <td>
						      <select name="ZhiFuBaoType" class="trans-input">
						       <option value="trade_create_by_buyer" <?php if ($INFO[ZhiFuBaoType]=="trade_create_by_buyer") { echo " selected=\"selected\" " ;}?>><?php echo $Admin_sys_Pack[ZFB_SwJy]?></option>
							   <option value="create_digital_goods_trade_p" <?php if ($INFO[ZhiFuBaoType]=="create_digital_goods_trade_p") { echo " selected=\"selected\" " ;}?>><?php echo $Admin_sys_Pack[ZFB_XnJy]?></option>
							   <option value="create_donate_trade_p" <?php if ($INFO[ZhiFuBaoType]=="create_donate_trade_p") { echo " selected=\"selected\" " ; }?>><?php echo $Admin_sys_Pack[ZFB_JzJy]?></option>
						      </select>						 </td>
                        </tr>

                        <tr>
                          <td width="16%"><?php echo $Admin_sys_Pack[ZFB_username];?>：</td>
                          <td width="84%"><?php echo $FUNCTIONS->Input_Box('text','ZhiFuBao',$INFO['ZhiFuBao'],"  maxLength=40 size=40 ")?>						  </td>
                        </tr>
                        <tr>
                          <td><?php echo $Admin_sys_Pack[ZFB_ID] ?>：</td>
                          <td><?php echo $FUNCTIONS->Input_Box('text','ZhiFuBaoID',$INFO['ZhiFuBaoID'],"  maxLength=40 size=40 ")?></td>
                        </tr>
                        <tr>
                          <td><?php echo $Admin_sys_Pack[ZFB_Key] ?>：</td>
                          <td><?php echo $FUNCTIONS->Input_Box('text','ZhiFuBaoKey',$INFO['ZhiFuBaoKey'],"  maxLength=40 size=40 ")?></td>
                        </tr>
                      </table></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD colspan="3" align=left noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right> META_DESCRIPTION： </TD>
                      <TD colspan="3" align=left noWrap>
					  <?php echo $FUNCTIONS->Input_Box('textarea','meta_desc',$INFO['meta_desc']," cols=120 rows=5 ")?>
					  <div id="meta_desctips" class="tips_big"><?php echo $Admin_sys_Pack[META_DESCRIPTION]?></div>					  </TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right> META_KEYWORDS： </TD>
                      <TD colspan="3" align=left noWrap>
					  <?php echo $FUNCTIONS->Input_Box('textarea','meta_keyword',$INFO['meta_keyword']," cols=120 rows=5     ")?>
					  <div id="meta_keywordtips" class="tips_big"><?php echo $Admin_sys_Pack[META_KEYWORDS]?></div>					  </TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                      <TD align=right noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR>
                    </TBODY></TABLE>
				</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif>
					  <IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
  </FORM>
					  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>

<?php

if ( $_POST['Action']=="Modi" ){

	$_POST[Tw_OnePay] = intval($_POST[zfb_open])==0 ? "" : "I";

	$Ex_Function->save_config( $new = array("IS","admin_IS","chartset","site_name","lxr","sex","email","city","addr","other_tel","tel","fax","post","site_title","company_name","content","site_url","Firstpay","site_shopadmin","b_attr","meta_desc","meta_keyword","good_pic_path","link_pic_path","templates","buy_product_max_num","DBhostname","DBuserName","DBpassword","DBname","DBPrefix","absolutePath","PayStartprice","PayEndprice","Paytype","Need_invoice","invoice","ATM","ATM_SECTION","Sub_Host","Shop_I","Shop_II","Shop_Code","Tw_OnePay","duration","MinBuyMoney","SetupDiscuz","DiscuzSetupTablePre","ZhiFuBaoType","ZhiFuBaoKey","ZhiFuBaoID","ZhiFuBao","zfb_open","OpenDesktopMenu","staticState","MaxProductNumForList","MaxNewProductNum"),"conf.global") ;

	// $Ex_Function->save_config( $new = array("IS","chartset","site_name","lxr","sex","email","city","addr","other_tel","tel","fax","post","site_title","company_name","content","site_url","site_shopadmin","b_attr","meta_desc","meta_keyword")) ;  //数组的名称需要对应修改的字段名称！
	echo " <script language=javascript> alert('".$Basic_Command[Back_System_Sucuess]."'); location.href='admin_gb_sys.php'; </script>";
}
?>
