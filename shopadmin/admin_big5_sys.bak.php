<?php
include_once "Check_Admin.php";

/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/Admin_sys_Pack.php";
include "../language/".$INFO['IS']."/TwPayOne_Pack.php";

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;<?php echo $JsMenu[Basic_Info]?></TITLE>
</HEAD>
<BODY onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<script language="javascript">
function view(obj,a)
{
	if(a == 1){
		document.getElementById(obj).style.display="";
		document.getElementById("Paytypeshow2").style.display="none";
	}else{
		document.getElementById(obj).style.display="none";
	}
}
function view2(obj,a)
{
	if(a == 1){
		document.getElementById(obj).style.display="";
		
		document.getElementById("Paytypeshow").style.display="none";
	}else{
		document.getElementById(obj).style.display="none";
	}
}
</script>

<SCRIPT language=javascript>
	function checkform(){
		form1.submit();
	}


</SCRIPT>
<div id="contain_out">
  <FORM name='form1' action='' method='post' id="theform">
    <?php  include_once "Order_state.php";?>
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
  <input type="hidden" name="SetupDiscuz" value="<?php echo $INFO['SetupDiscuz'];?>">
  <input type="hidden" name="DiscuzSetupTablePre" value="<?php echo $INFO['DiscuzSetupTablePre'];?>">
  <input type="hidden" name="Sub_Host" value="<?php echo $Sub_host;?>">


  <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
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
                            <a  href="javascript:window.history.back(-1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save']?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->							</TD></TR></TBODY></TABLE>				</TD>
                    </TR>
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
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="16%">&nbsp;</TD>
                            <TD colspan="3" align=right noWrap> </TD></TR>
                          <TR>
                            <TD noWrap align=right>網站狀態：</TD>
                            <TD align=left noWrap><input type="radio" name="siteOpen" value="1" <?php if (intval($INFO['siteOpen'])==1){ echo " checked ";} ?> >
                              開啟
                              <input type="radio" name="siteOpen" value="0" <?php if (intval($INFO['siteOpen'])==0){ echo " checked ";} ?> >
                              關閉 </TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_SiteName]?>：</TD>
                            <TD width="41%" align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','site_name',$INFO['site_name']," maxLength=30 size=30 ")?> <a href="#" class="easyui-tooltip" title="<?php echo  $Admin_sys_Pack[Site_Name]?>"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              
                              <TD width="11%" align=right noWrap><?php echo $Admin_sys_Pack[Sys_ShopTitle]?>：</TD>
                            <TD width="32%" align=left noWrap>
                              
                              <?php echo $FUNCTIONS->Input_Box('text','site_title',$INFO['site_title']," maxLength=30 size=30  ")?> <a href="#" class="easyui-tooltip" title="<?php echo $Admin_sys_Pack[Site_Title]?>"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_NetName]?>：</TD>
                            <TD align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','site_urls',$INFO['site_url']," maxLength=200 size=50  disabled")?>                      </TD>
                            <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_ProductClassAttribNum]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','b_attr',$INFO['b_attr']," maxLength=10 size=10  ")?>
                              <a href="#" title="<?php echo  $Admin_sys_Pack[Sys_ProductClassAttribNum_intro]?>"  class="easyui-tooltip"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>Manage Path：</TD>
                            <TD align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','site_shopadmins',$INFO['site_shopadmin'],"  maxLength=200 size=50   disabled")?></TD>
                            <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_MaxNewProductNum]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','MaxNewProductNum',$INFO['MaxNewProductNum']," maxLength=10 size=10")?> <a href="#" title="<?php echo $Admin_sys_Pack[Sys_MaxNewProductNum_intro]?>"  class="easyui-tooltip"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_CompanyName]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','company_name',$INFO['company_name'],"  maxLength=30 size=30  ")?></TD>
                            <TD align=right noWrap><?php echo $Admin_sys_Pack[MaxProductNumForList]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','MaxProductNumForList',$INFO['MaxProductNumForList'],"  maxLength=10 size=10 ")?> <a href="#" id="pp" title="<?php echo $Admin_sys_Pack[MaxProductNumForList]?>"  class="easyui-tooltip"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Lxr] ?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','lxr',$INFO['lxr'],"          maxLength=30 size=30  ")?>					  </TD>
                            <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_MinBuyMoney];?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','MinBuyMoney',$INFO['MinBuyMoney'],"  maxLength=10 size=10  ")?>
                              <a href="#" title="<?php echo $Admin_sys_Pack[WhatIsMinBuyMoney]?>"  class="easyui-tooltip"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Sex]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio_strand','sex',$INFO['sex'],$add = array($Admin_sys_Pack[Sys_Men],$Admin_sys_Pack[Sys_Women]))?></TD>
                            <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_MaxProductNum];?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','buy_product_max_num',$INFO['buy_product_max_num']," maxLength=10 size=10 ")?> <a href="#" title="<?php echo $Admin_sys_Pack[Sys_MaxProductNum_intro]?>"  class="easyui-tooltip"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Email]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','email',$INFO['email'],"  maxLength=30 size=30  ")?></TD>
                            
                            <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_PInvoice];?>：</TD>
                            <TD align=left noWrap><input type="radio" name="Need_invoice" value="0" <?php if (intval($INFO['Need_invoice'])==0){ echo " checked ";} ?> >
                              <?php echo $Basic_Command['No'] ?>
                              <input type="radio" name="Need_invoice" value="1" <?php if (intval($INFO['Need_invoice'])==1){ echo " checked ";} ?> >
                              <?php echo $Basic_Command['Yes'] ?> </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_City]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','city',$INFO['city'],"          maxLength=30 size=30  ")?></TD>
                            <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_Blanguage]?>：</TD>
                            <TD valign="middle" noWrap><select name="IS">
                              <option value="gb"   <?php if ($INFO['IS']=='gb') { echo " selected ";}?>><?php echo $ARR_LANGNAME['zh']?></option>
                              <option value="big5" <?php if ($INFO['IS']=='big5') { echo " selected ";}?>><?php echo $ARR_LANGNAME['big5']?></option>
                              <option value="en"   <?php if ($INFO['IS']=='en') { echo " selected ";}?>><?php echo $ARR_LANGNAME['en']?></option>
                              </select></TD>
                            </TR>
                          
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Address]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','addr',$INFO['addr'],"          maxLength=30 size=50  ")?></TD>
                            <TD align=right noWrap><?php echo $Admin_sys_Pack[Sys_Flanguage] ?>：</TD>
                            <TD align=left noWrap><select name="admin_IS">
                              <option value="gb"   <?php if ($INFO['admin_IS']=='gb') { echo " selected ";}?>><?php echo $ARR_LANGNAME['zh']?></option>
                              <option value="big5" <?php if ($INFO['admin_IS']=='big5') { echo " selected ";}?>><?php echo $ARR_LANGNAME['big5']?></option>
                              <option value="en"   <?php if ($INFO['admin_IS']=='en') { echo " selected ";}?>><?php echo $ARR_LANGNAME['en']?></option>
                              </select></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_Fax]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','fax',$INFO['fax'],"          maxLength=30 size=30  ")?></TD>
                            <TD align=right noWrap>商品圖片縮略圖：</TD>
                            <TD align=left noWrap>小圖<?php echo $FUNCTIONS->Input_Box('text','product_small',$INFO['product_small'],"          maxLength=30 size=3  ")?>px | 中圖<?php echo $FUNCTIONS->Input_Box('text','product_midlle',$INFO['product_midlle'],"          maxLength=30 size=3  ")?>px | 大圖<?php echo $FUNCTIONS->Input_Box('text','product_big',$INFO['product_big'],"          maxLength=30 size=3  ")?>px</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Code]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','post',$INFO['post']," maxLength=30 size=30  ")?></TD>
                            <TD align=right noWrap>文章圖片縮略圖：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','article_small',$INFO['article_small'],"          maxLength=30 size=3  ")?>px</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Mobile]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','other_tel',$INFO['other_tel']," maxLength=30 size=30  ")?></TD>
                            <TD align=right noWrap>品牌圖片縮略圖：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','logo_small',$INFO['logo_small'],"          maxLength=30 size=3  ")?>px</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_sys_Pack[Sys_Tel]?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','tel',$INFO['tel'],"          maxLength=30 size=30  ")?></TD>
                            <!--商店驗證碼II--><TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>匯率：</TD>
                            <TD align=left noWrap>1台幣兌<?php echo $FUNCTIONS->Input_Box('text','NTrate',$INFO['NTrate'],"          maxLength=30 size=10  ")?>元人民幣</TD>
                            <!--商店代碼--><TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          
                          <TR>
                            <TD noWrap align=right><?php echo $Admin_sys_Pack[Sys_SendType] ;//配送方式?>：</TD>
                            <TD align=left noWrap>
                              <input type="radio" name="Paytype" id="Paytype" value="0" <?php if (intval($INFO['Paytype'])==0){ echo " checked ";} ?> onclick=view2("Paytypeshow2",1)>                        <?php echo $Admin_sys_Pack[Sys_ZDYSendType]?>
                              &nbsp;&nbsp;&nbsp;
                              <input type="radio" name="Paytype" id="Paytype" value="1" <?php if (intval($INFO['Paytype'])==1){ echo " checked ";} ?> onclick=view("Paytypeshow",1)>                        <?php echo $Admin_sys_Pack[Sys_SendProgr]?></TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          
                          <?php $DISPLAYPaytype =  intval($INFO['Paytype'])==0 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                          <TR id="Paytypeshow" <?php echo $DISPLAYPaytype;?>>
                            <TD noWrap align=right>&nbsp; </TD>
                            <TD align=left noWrap bgcolor="#FFFFCC"><?php echo $Admin_sys_Pack[Sys_SendProgr_I]?><!--當金額小于-->
                              <input name="PayStartprice" type="text" class="inputstyle" size="8" maxlength="16" value="<?php echo $INFO['PayStartprice']?>">
                              <?php echo $Admin_sys_Pack[Sys_Yuan] ?><!--元-->，<?php echo $Admin_sys_Pack[Sys_SendProgr_II];?><!--须加收运费--><input name="PayEndprice" type="text" class="inputstyle" size="8" maxlength="16" value="<?php echo $INFO['PayEndprice']?>">
                              <?php echo $Admin_sys_Pack[Sys_Yuan] ?><!--元-->&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <?php $DISPLAYPaytype =  intval($INFO['Paytype'])==1 ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?>
                          <TR id="Paytypeshow2" <?php echo $DISPLAYPaytype;?>>
                            <TD noWrap align=right>&nbsp; </TD>
                            <TD align=left noWrap bgcolor="#FFFFCC">當購買滿
                              <input name="PayFreetrans" type="text" class="inputstyle" size="8" maxlength="16" value="<?php echo $INFO['PayFreetrans']?>">
                              免運費</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          
                          <!-- TR id=Paytypeshow2 >
                      <TD noWrap align=right>團購運費：</TD>
                      <TD align=left noWrap bgcolor="#FFFFCC">當購買滿
                        <input name="Group_PayFreetrans" type="text" class="inputstyle" size="8" maxlength="16" value="<?php echo $INFO['Group_PayFreetrans']?>">
                        免運費</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                      <TD align=left noWrap>&nbsp;</TD>
                    </TR-->
                          
                          <TR>
                            <TD noWrap align=right>後台登入鎖定IP：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio_strand','checkip',$INFO['checkip'],$add = array("否","是"))?> * 開啟前請先確認已設定好您的IP </TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>註冊會員級別設定：</TD>
                            <TD colspan="3" align=left noWrap>
                            <?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}user_level` order by level_id asc ",'reg_userlevel','level_id','level_name',intval($INFO['reg_userlevel'])); //$FUNCTIONS->Level_name($member_point)?>
                            </TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>VIP會員級別設定：</TD>
                            <TD colspan="3" align=left valign="top" noWrap><?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}user_level` order by level_id asc ",'vip_userlevel','level_id','level_name',intval($INFO['vip_userlevel'])); //$FUNCTIONS->Level_name($member_point)?></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right>VIP會員：</TD>
                            <TD colspan="3" align=left valign="top" noWrap>一年內累積購買滿<?php echo $FUNCTIONS->Input_Box('text','vip_yearmoney',$INFO['vip_yearmoney'],"          maxLength=15 size=20  ")?>或單筆消費<?php echo $FUNCTIONS->Input_Box('text','vip_money',$INFO['vip_money'],"          maxLength=15 size=20  ")?>升級為VIP會員，若VIP會員於<?php echo $FUNCTIONS->Input_Box('text','vip_days',$INFO['vip_days'],"          maxLength=15 size=20  ")?>天內未消費</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right></TD>
                            <TD colspan="3" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD colspan="4" align=left noWrap style="padding-left:150px"><i class="icon-warning-sign" style="font-size:16px;color:#C00"></i> 下列無使用欄位請清空</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>通路王串接：</TD>
                            <TD colspan="3" align=left noWrap>合作項目代號<?php echo $FUNCTIONS->Input_Box('text','oeno',$INFO['oeno'],"          maxLength=30 size=30  ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>註冊贈送紅利：</TD>
                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','regpoint',$INFO['regpoint'],"          maxLength=30 size=10  ")?>點</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>每日登入送紅利：</TD>
                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','loginpoint',$INFO['loginpoint'],"          maxLength=30 size=10  ")?>點</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>額滿贈送紅利：</TD>
                            <TD colspan="3" align=left noWrap>滿<?php echo $FUNCTIONS->Input_Box('text','ordermoney',$INFO['ordermoney'],"          maxLength=30 size=10  ")?>元，送<?php echo $FUNCTIONS->Input_Box('text','orderpoint',$INFO['orderpoint'],"          maxLength=30 size=10  ")?>點</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>推薦加入會員贈送紅利：</TD>
                            <TD colspan="3" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','recommendPoint',$INFO['recommendPoint'],"          maxLength=30 size=10  ")?>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>推薦購買送紅利：</TD>
                            <TD colspan="3" align=left noWrap>推薦購買<?php echo $FUNCTIONS->Input_Box('text','recommendBuy',$INFO['recommendBuy'],"          maxLength=30 size=10  ")?>元，送<?php echo $FUNCTIONS->Input_Box('text','recommendBuyPoint',$INFO['recommendBuyPoint'],"          maxLength=30 size=10  ")?>點</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>全館折扣：</TD>
                            <TD colspan="3" align=left noWrap> From
                              <INPUT   id=allsaleoff_begintime size=10 value="<?php echo $INFO['allsaleoff_begintime']?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=allsaleoff_begintime />
                              To
                              <INPUT    id=allsaleoff_endtime size=10 value="<?php echo $INFO['allsaleoff_endtime']?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=allsaleoff_endtime />
                              <?php echo $FUNCTIONS->Input_Box('text','allsaleoff',$INFO['allsaleoff'],"          maxLength=30 size=10  ")?>%</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>配送日期：</TD>
                            <TD colspan="3" align=left noWrap><input type="checkbox" name="ifsenddate" id="ifsenddate" value="1" <?php if($INFO['ifsenddate']==1) echo "checked";?> />
                              開啟，
                              在下單日<?php echo $FUNCTIONS->Input_Box('text','senddate_day',$INFO['senddate_day'],"          maxLength=30 size=10  ")?>天後配送</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> META_DESCRIPTION： </TD>
                            <TD colspan="3" align=left valign="top" noWrap>
                              <?php echo $FUNCTIONS->Input_Box('textarea','meta_desc',$INFO['meta_desc']," cols=80 rows=5 ")?><a href="#" title="<?php echo  $Admin_sys_Pack[META_DESCRIPTION]?>"  class="easyui-tooltip"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> META_KEYWORDS： </TD>
                            <TD colspan="3" align=left valign="top" noWrap>
                              <?php echo $FUNCTIONS->Input_Box('textarea','meta_keyword',$INFO['meta_keyword']," cols=80 rows=5")?><a href="#" title="<?php echo  $Admin_sys_Pack[META_KEYWORDS]?>"  class="easyui-tooltip"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TD>
                            </TR>
                             <TR>
                      <TD align=right valign="top" noWrap>會員導出字段設置：</TD>
                      <TD colspan="3" align=left valign="top" >
                       <?php
					  $member_excel_out = explode(",",$INFO['member_excel_out']);
                       foreach($member_field as $k=>$v){
						?>
                        <input type="checkbox" name="member_excel_out[]" <?php if(in_array($k,$member_excel_out)) echo "checked";?> value="<?php echo $k;?>"><?php echo $v;?>
                        <?php   
					   }
					   ?>
                      </TD>
                    </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
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
	
	//if(is_array($_POST['goods_excel_out']))
	//	$goods_excel_out = implode("|",$_POST['goods_excel_out']);
	//if(is_array($_POST['goods_excel_in']))
	//	$goods_excel_out = implode("|",$_POST['goods_excel_in']);
	
	$Ex_Function->save_config( $new = array("IS","admin_IS","chartset","site_name","lxr","sex","email","city","addr","other_tel","tel","fax","post","site_title","company_name","content","site_url","Firstpay","site_shopadmin","b_attr","meta_desc","meta_keyword","good_pic_path","link_pic_path","templates","buy_product_max_num","DBhostname","DBuserName","DBpassword","DBname","DBPrefix","absolutePath","PayStartprice","PayEndprice","Paytype","Need_invoice","invoice","ATM","ATM_SECTION","Sub_Host","duration","MinBuyMoney","DiscuzSetupTablePre","SetupDiscuz","ZhiFuBaoType","ZhiFuBaoKey","ZhiFuBaoID","ZhiFuBao","zfb_open","OpenDesktopMenu","staticState","MaxProductNumForList","MaxNewProductNum","PayFreetrans","checkip","oeno","regpoint","ordermoney","orderpoint","siteOpen","allsaleoff_begintime","allsaleoff_endtime","allsaleoff","recommendPoint","recommendBuy","recommendBuyPoint","NTrate","loginpoint","shop_site_name","shop_meta_desc","shop_meta_keyword","Group_PayFreetrans","product_small","product_midlle","product_big","article_small","logo_small","senddate_day","ifsenddate","reg_userlevel","member_excel_out","vip_userlevel","vip_yearmoney","vip_money","vip_days"),"conf.global") ;
	$FUNCTIONS->setLog("編輯基本資訊");

	// $Ex_Function->save_config( $new = array("IS","chartset","site_name","lxr","sex","email","city","addr","other_tel","tel","fax","post","site_title","company_name","content","site_url","site_shopadmin","b_attr","meta_desc","meta_keyword")) ;  //数组的名称需要对应修改的字段名称！
	echo " <script language=javascript> alert('".$Basic_Command[Back_System_Sucuess]."'); location.href='admin_big5_sys.php'; </script>";
}
?>


<?php
/*
                    <!--TR>
                      <TD noWrap align=right><?php //echo $Admin_sys_Pack[Sys_AtmNum];?>：</TD>
                      <TD align=left noWrap><?php //echo $FUNCTIONS->Input_Box('text','ATM',$INFO['ATM'],"          maxLength=30 size=30  ")?></TD>
                      <TD align=right noWrap><?php //echo $Admin_sys_Pack[Sys_AtmQNum];?>：</TD>
                      <TD align=left noWrap><?php //echo $FUNCTIONS->Input_Box('text','ATM_SECTION',$INFO['ATM_SECTION'],"          maxLength=30 size=30  ")?></TD>
                    </TR-->

*/

?>
