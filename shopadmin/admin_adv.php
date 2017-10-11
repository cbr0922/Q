<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Adv_Pack.php";
if ($_GET['Adv_id']!="" && $_GET['Action']=='Modi'){
	$Adv_id = intval($_GET['Adv_id']);
	$Action_value = "Update";
	$Action_say  = $Adv_Pack[AdvWeiModi]; //广告位修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_id=".intval($Adv_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$adv_title       =  $Result['adv_title'];
		$adv_type        =  $Result['adv_type'];
		$Title_color     =  $Result['title_color'];
		$adv_left_url    =  trim($Result['adv_left_url']);
		$adv_right_url   =  trim($Result['adv_right_url']);
		$adv_width       =  $Result['adv_width'];
		$adv_height      =  $Result['adv_height'];
		$adv_content     =  $Result['adv_content'];		
		$adv_banner      =  $Result['adv_banner'];
		$point_num       =  $Result['point_num'];
		$adv_display     =  $Result['adv_display'];
		$adv_left_img    =  trim($Result['adv_left_img']);
		$adv_right_img   =  trim($Result['adv_right_img']);
		$adv_tag         =  trim($Result['adv_tag']);
		$company         =  trim($Result['company']);
		$orderby         =  trim($Result['orderby']);
		$ifallclass         =  trim($Result['ifallclass']);
		$position         =  trim($Result['position']);
		$language     =  $Result['language'];
		/*
		$begtime         =  trim($Result['start_time']);
		$endtime         =  trim($Result['end_time']);
		*/
		if ($Result['start_time']!=""){
			$begtime    =  date("Y-m-d",trim($Result['start_time']));
			$start_h    =  date("H",trim($Result['start_time']));
			$start_i    =  date("i",trim($Result['start_time']));
		}
		if ($Result['end_time']!=""){
			$endtime    =  date("Y-m-d",trim($Result['end_time']));
			$end_h    =  date("H",trim($Result['end_time']));
			$end_i    =  date("i",trim($Result['end_time']));
		}
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = $Adv_Pack[AdvAdd]; //添加广告
	$begtime         = $endtime         =   date("Y-m-d",time());
}


include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK id=css href="css/calendar.css" type='text/css' rel=stylesheet>
<TITLE><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Advertis_Man];//广告管理?>--&gt;<?php echo $Action_say?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<script type="text/javascript" src="../Resources/redactor-js-master/lib/jquery-1.9.0.min.js"></script>

	<!-- Redactor is here -->
	<link rel="stylesheet" href="../Resources/redactor-js-master/redactor/redactor.css" />
	<script src="../Resources/redactor-js-master/redactor/redactor.js"></script>
   <!-- Plugin -->
          <script src="/Resources/redactor-js-master/redactor/plugins/source.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/table.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fullscreen.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontsize.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontfamily.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontcolor.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/inlinestyle.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/video.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/properties.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/textdirection.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/imagemanager.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/alignment/alignment.js"></script>
          <link rel="stylesheet" href="../Resources/redactor-js-master/redactor/plugins/alignment/alignment.css" />
    <!--/ Plugin -->
    
	<script type="text/javascript">
	$(document).ready(
		function()
		{
			$('#redactor').redactor({
				imageUpload: '../Resources/redactor-js-master/demo/scripts/image_upload.php',
				imageManagerJson: '../Resources/redactor-js-master/demo/scripts/image_json.php',
				plugins: ['source','imagemanager', 'video','fontsize','fontcolor','alignment','fontfamily','table','textdirection','properties','inlinestyle','fullscreen'],
				imagePosition: true,
                imageResizable: true,
				<?php
				if ($_GET['Adv_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_adv_save.php?act=autosave1&Adv_id=<?php echo $_GET['Adv_id'];?>',
				callbacks: {
					autosave: function(json)
					{
						 console.log(json);
					}
				}
				<?php
				}
				?>
			});
			$('#redactor1').redactor({
				imageUpload: '../Resources/redactor-js-master/demo/scripts/image_upload.php',
				imageManagerJson: '../Resources/redactor-js-master/demo/scripts/image_json.php',
				plugins: ['source','imagemanager', 'video','fontsize','fontcolor','alignment','fontfamily','table','textdirection','properties','inlinestyle','fullscreen'],
				imagePosition: true,
                imageResizable: true,
				<?php
				if ($_GET['Adv_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_adv_save.php?act=autosave2&Adv_id=<?php echo $_GET['Adv_id'];?>',
				callbacks: {
					autosave: function(json)
					{
						 console.log(json);
					}
				}
				<?php
				}
				?>
			});
		}
	);
	</script>
<?php  include_once "Order_state.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.adv_title.value) || form1.adv_title.value.length>100 || form1.adv_title.value.length<2){
			form1.adv_title.focus();
			alert('<?php echo $Adv_Pack[PleaseInputAdvName]?>'); //"請輸入廣告標題名稱";
			return;
		}

 		  if (chkblank(form1.adv_tag.value) || form1.adv_tag.value.length>20 || form1.adv_tag.value.length<1){
			 form1.adv_tag.focus();
			 alert('<?php echo $Adv_Pack[PleaseInputAdvTag]?>'); //"請輸入標簽";
			 return;
		  }

		if (form1.ima.value != "")
		{
		  if (isnum(form1.adv_width.value)<0){
			alert('<?php echo $Adv_Pack[PleaseInputAdvWidth]?>'); //"请输入广告条宽度"
			form1.adv_width.value="";
			form1.adv_width.focus();
			return;
		  }

		  if (isnum(form1.adv_height.value)<0){
			alert('<?php echo $Adv_Pack[PleaseInputAdvHeight]?>');  //"请输入广告条高度"
			form1.adv_height.value="";
			form1.adv_height.focus();
			return;
		  }
		}
		form1.submit();
	}
		function view(obj_zero,obj_one,obj_two,obj_three,obj_four,obj_five,obj_six,obj_seven,obj_eight,obj_nine,a)
	{
		if (a == 1 || a == 2){
		    obj_zero.style.display="";
			obj_one.style.display="";
			obj_two.style.display="";
			obj_three.style.display="";
			obj_four.style.display="";
			obj_five.style.display="";
			obj_six.style.display="";
			obj_seven.style.display="";
			obj_eight.style.display="";
            obj_nine.style.display="";
		}
		if (a == 3 ) {
            obj_zero.style.display="none";
			obj_one.style.display="";
		    obj_two.style.display="none";
		    obj_three.style.display="none";
		    obj_four.style.display="none";
		    obj_five.style.display="none";
		    obj_six.style.display="none";
			obj_seven.style.display="none";
			obj_eight.style.display="none";
            obj_nine.style.display="none";
		}
		 if ( a == 4 ){
            obj_zero.style.display="none";
			obj_one.style.display="";
		    obj_two.style.display="none";
		    obj_three.style.display="none";
		    obj_four.style.display="none";
		    obj_five.style.display="none";
		    obj_six.style.display="none";
			obj_seven.style.display="none";
			obj_eight.style.display="none";
            obj_nine.style.display="none";
		}
		if (a == 5 ) {
            obj_zero.style.display="none";
			obj_one.style.display="none";
		    obj_two.style.display="none";
		    obj_three.style.display="none";
		    obj_four.style.display="none";
		    obj_five.style.display="none";
		    obj_six.style.display="none";
			obj_seven.style.display="none";
			obj_eight.style.display="none";
            obj_nine.style.display="none";
		}
	}

</SCRIPT>
<SCRIPT language=JavaScript>
function select_color(type)
{
	var str;
	var arr_str=new Array();
	str=window.showModalDialog("color_select.html","","dialogWidth:28;dialogHeight:20");
	if (!str) return false;
	arr_str=str.split("||");

	if (type==1){
	 document.form1.title_color.value=arr_str[0];
	 document.form1.title_color.value=arr_str[1];
	}

	return true;
}
</SCRIPT>
<link id="jquiCSS" rel="stylesheet" href="../css/jquery-ui-1.9.2.custom.min.css" type="text/css" media="all">
<link id="jquiCSS" rel="stylesheet" href="../css/evol.colorpicker.css" type="text/css" media="all">
<script type="text/javascript" src="../js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="../js/evol.colorpicker.js"></script>
<div id="contain_out">
  <FORM name=form1 action='admin_adv_save.php' method='post' enctype="multipart/form-data" >
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="Adv_id" value="<?php echo $Adv_id?>">
  <input type="hidden" name="adv_left_img_old" value="<?php echo $adv_left_img?>">
  <input type="hidden" name="adv_right_img_old" value="<?php echo $adv_right_img?>">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Advertis_Man];//广告管理?>--&gt;<?php echo $Action_say?></SPAN></TD>
                  </TR></TBODY></TABLE></TD>
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
                            <a href="admin_adv_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?>&nbsp;</a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE border=0 link="javascript:checkform();">
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->							</TD></TR></TBODY></TABLE>				</TD>
                  </TR>
                </TBODY>
              </TABLE></TD></TR>
        </TBODY>
    </TABLE><table width="100%" border="0" cellpadding="0" cellspacing="0" class="allborder" style="margin-top:10px;margin-bottom:10px">
                        <tr>
                          <td width="56" height="87" align="center" valign="top" style="padding-top:8px"><img src="images/<?php echo $INFO[IS]?>/note01.gif" width="24" height="24"></td>
                          <td>"廣告使用說明"
                            <div id="tip01tips" class="tips_note">1. 浮動、耳朵廣告開啟後必須至&quot;系統設置-&gt;首頁設置&quot;中另行開啟。<br>
                              2. 標籤廣告需依照系統初始設定時所規劃之標籤填寫才能正常顯示。<br>
                              3. 並非所有專案皆有下列各項廣告。</div></td>
      </tr>
                        </table>
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="166">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>
                                                    <TR>
                            <TD noWrap align=right><?php echo $Basic_Command['Iffb'] ;//是否发布?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','adv_display',$adv_display,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                            </TR>
                            <TR align="center">
                              <td align="right">語言版本：</td>
                              <TD align="left" valign="top" noWrap>
                              <select name="language">
                        <option value="">請選語言</option>
                        <?php
                            $Sql_t      = "select * from `{$INFO[DBPrefix]}languageset` order by lid ";
							$Query_t    = $DB->query($Sql_t);
							$Num_t      = $DB->num_rows($Query_t);
							while ($Rs_t=$DB->fetch_array($Query_t)) {
							?>
                        <option value="<?php echo $Rs_t['code'];?>" <?php if($Rs_t['code'] == $language) echo "selected";?>><?php echo $Rs_t['languagename'];?></option>
                        <?
							}
							?>
                        </select>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Adv_Pack[AdvTitle];//广告标题?>：</TD>
                            <TD align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','adv_title',$adv_title," maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>
                              <?php echo $Adv_Pack[AdvTitleColor];//标题颜色：?>：                              <!--<INPUT onclick='select_color(1);' type=button value=<?php echo $Basic_Command['Please_Select'];?> name=button>--></TD>
                            <TD width="623" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','title_color',$Title_color,"      maxLength=10 size=10  ")?></TD>
                            <TD width="1" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>起迄時間：</TD>
                            <TD colspan="4" align=left><INPUT   id=begtime3 size=10 value="<?php echo $begtime?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime />
                              <select name="start_h">
                                <?php
			for($i=0;$i<=23;$i++){
            ?>
                                <option value="<?php echo $i;?>" <?php if($start_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
			}
			?>
                                </select>
                              時
                              <select name="start_i">
                                <?php
			for($i=0;$i<=59;$i++){
            ?>
                                <option value="<?php echo $i;?>" <?php if($start_i==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
			}
			?>
                                </select>
                              分 至
                              <INPUT    id=endtime3 size=10 value="<?php echo $endtime?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime />
                              <select name="end_h">
                                <?php
			for($i=0;$i<=23;$i++){
            ?>
                                <option value="<?php echo $i;?>" <?php if($end_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
			}
			?>
                                </select>
                              時
                              <select name="end_i">
                                <?php
			for($i=0;$i<=59;$i++){
            ?>
                                <option value="<?php echo $i;?>" <?php if($end_i==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
			}
			?>
                                </select>
                              分 <a href="#" class="easyui-tooltip" title="若不限制時間請將日期去掉"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>購買公司：</TD>
                            <TD colspan="4" align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','company',$company,"     maxLength=255 size=60 ")?> </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>排序：</TD>
                            <TD colspan="4" align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','orderby',$orderby,"     maxLength=10 size=10 ")?> </TD>
                            </TR>

                          <TR>
                            <TD noWrap align=right><?php echo $Adv_Pack[AdvTag] ;?><!--标签-->：</TD>
                            <TD colspan="4" align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','adv_tag',$adv_tag,"   onchange=\"CheckTag(this.value,".intval($Adv_id).")\"     maxLength=20 size=20 ")?> <div id='show_Tag'>&nbsp;</div></TD>
                            </TR>
                            <TR>
                            <TD align=right valign="top" noWrap>放置頁面名稱：</TD>
                            <TD colspan="4" align=left><?php echo $FUNCTIONS->Input_Box('text','position',$position,"     maxLength=100 size=30 ")?><br />
                              不填寫則默認將在所有頁面顯示，可填寫完整頁面名稱或部分名稱（比如填寫member，含有member名稱的頁面或者路徑都會顯示），模板上調用方式&lt;{$TagAdv_标签名称}&gt;</TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right></TD>
                            <TD colspan="4" align=left noWrap></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><label for="adv_type"><?php echo $Adv_Pack[AdvType];//广告类型?></label>：</TD>
                            <TD colspan="4" align=left>
                              <input id="adv_type" name="adv_type" type="radio" value="1" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,1)' <?php if ($adv_type==1) { echo " checked ";} ?>><?php echo $Adv_Pack[FloatAdv]?>
                              <div id="adv_typetips" class="tips"><?php echo $Adv_Pack[WhatIsFloatAdv];?></div>
                              <input id="adv_type" name="adv_type" type="radio" value="2" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,2)' <?php if ($adv_type==2) { echo " checked ";} ?>><?php echo $Adv_Pack[EarAdv]?>
                              <input id="adv_type" name="adv_type" type="radio" value="3" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,3)' <?php if ($adv_type==3) { echo " checked ";} ?>><?php echo $Adv_Pack[TagAdv]?>
                              <input id="adv_type" name="adv_type" type="radio" value="4" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,4)' <?php if ($adv_type==4) { echo " checked ";} ?>><?php echo $Adv_Pack[HourseAdv]?>
                              <input id="adv_type" name="adv_type" type="radio" value="5" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,4)' <?php if ($adv_type==5) { echo " checked ";} ?>><?php echo $Adv_Pack[BoardAdv]?>
                              <input id="adv_type" name="adv_type" type="radio" value="6" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,3)' <?php if ($adv_type==6) { echo " checked ";} ?>>Banner
                              <input id="adv_type" name="adv_type" type="radio" value="7" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,3)' <?php if ($adv_type==7) { echo " checked ";} ?>>
                              Banner1
                              <input id="adv_type" name="adv_type" type="radio" value="8" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,3)' <?php if ($adv_type==8) { echo " checked ";} ?>>
                              熱門活動
                             <input id="adv_type" name="adv_type" type="radio" value="9" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,1)' <?php if ($adv_type==9) { echo " checked ";} ?>>首頁標籤RWD
                             <input id="adv_type" name="adv_type" type="radio" value="10" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,1)' <?php if ($adv_type==10) { echo " checked ";} ?>>首頁全版RWD
                              <!-- <input id="adv_type" name="adv_type" type="radio" value="11" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,1)' <?php if ($adv_type==11) { echo " checked ";} ?>>團購<?php echo $Adv_Pack[FloatAdv]?>
                              <div id="adv_typetips" class="tips"><?php echo $Adv_Pack[WhatIsFloatAdv];?></div>
                              <input id="adv_type" name="adv_type" type="radio" value="12" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,2)' <?php if ($adv_type==12) { echo " checked ";} ?>>團購<?php echo $Adv_Pack[EarAdv]?>
                              <input id="adv_type" name="adv_type" type="radio" value="13" onclick='view(advshow0,advshow1,advshow2,advshow3,advshow4,advshow5,advshow6,advshow7,advshow8,advshow9,4)' <?php if ($adv_type==7) { echo " checked ";} ?>>團購熱門關鍵字 -->
                              </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Adv_Pack[VisitNum];//访问次数?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','point_num',$point_num,"      maxLength=40 size=40 ")?></TD>
                            </TR>

                          <!--?php $DISPLAY =  ($adv_type==3 || $adv_type==4 || $adv_type==5) ? "style=\"DISPLAY: none\""  : "style=\"DISPLAY: display\""; ?-->
                          <TR id="advshow7" <?php echo $DISPLAY;?>>
                            <TD noWrap align=right> <?php echo $Adv_Pack[AdvWidth];//宽度?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','adv_width',$adv_width,"      maxLength=40 size=40 ")?>
                              &nbsp;                        <?php echo $Adv_Pack[PicPix] ?></TD>
                            </TR>
                          <TR id="advshow8" <?php echo $DISPLAY;?>>
                            <TD noWrap align=right> <?php echo $Adv_Pack[AdvHeight];//高度?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','adv_height',$adv_height,"      maxLength=40 size=40 ")?>
                              &nbsp;                        <?php echo $Adv_Pack[PicPix] ?></TD>
                            </TR>
                          <TR id="advshow0" <?php echo $DISPLAY;?>>
                            <TD noWrap align=right><strong>首頁RWD / [LEFT] </strong></TD>
                            <TD colspan="4" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR bgcolor="#FFFFFF" id="advshow1" <?php echo $DISPLAY;?>>
                            <TD align=right noWrap><?php echo $Adv_Pack[AdvUrl];//链接地址?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','adv_left_url',$adv_left_url,"      maxLength=200 size=60 ")?>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right noWrap>是否全館BANNER：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','ifallclass',$ifallclass,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                            </TR>
                          <TR bgcolor="#FFFFFF" id="advshow2" <?php echo $DISPLAY;?>>
                            <TD align=right noWrap><?php echo $Adv_Pack[AdvUploadFile];//上传文件：?>：</TD>
                            <TD colspan="4" align=left noWrap><input name="ima" type="file"  id='ima'>
                              <div id="imatips" class="tips_big"><?php echo $Adv_Pack[AdvUploadIntro]?></div>                      </TD>
                            </TR>
                          <TR bgcolor="#FFFFFF" id="advshow3" <?php echo $DISPLAY;?>>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="4" align=left noWrap class="adv_rwd_img"><?php echo $FUNCTIONS->ImgTypeReturn($INFO['advs_pic_path'],$adv_left_img,$adv_height,$adv_width);?><?php if ($adv_left_img!="") { ?>&nbsp;&nbsp;<a href="admin_adv_save.php?Action=DelPic&Type=LeftPic&adv_id=<?php echo $Adv_id?>" onClick="return confirm('<?php echo $Adv_Pack[AdvDoyouDel]?>?')"><?php echo $Basic_Command['Del']?></a><?php } ?></TD>
                            </TR>
                          <TR id="advshow9" <?php echo $DISPLAY;?>>
                            <TD noWrap align=right><strong>[RIGHT]</strong></TD>
                            <TD colspan="4" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR id="advshow4" <?php echo $DISPLAY;?>>
                            <TD align=right noWrap><?php echo $Adv_Pack[AdvUrl];//链接地址?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','adv_right_url',$adv_right_url,"      maxLength=200 size=60 ")?>&nbsp;</TD>
                            </TR>
                          <TR id="advshow5" <?php echo $DISPLAY;?>>
                            <TD align=right noWrap><?php echo $Adv_Pack[AdvUploadFile];//上传文件：?>：</TD>
                            <TD colspan="4" align=left noWrap><input name="ima1" type="file" id='ima1'>
                              <div id="ima1tips" class="tips_big"><?php echo $Adv_Pack[AdvUploadIntro]?></div>                         </TD>
                            </TR>
                          <TR id="advshow6" <?php echo $DISPLAY;?>>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="4" align=left noWrap><?php echo  $FUNCTIONS->ImgTypeReturn($INFO['advs_pic_path'],$adv_right_img,$adv_height,$adv_width);?><?php if ($adv_right_img!="") { ?>&nbsp;&nbsp;<a href="admin_adv_save.php?Action=DelPic&Type=RightPic&adv_id=<?php echo $Adv_id?>" onClick="return confirm('<?php echo $Adv_Pack[AdvDoyouDel]?>?')"><?php echo $Basic_Command['Del']?></a><?php } ?></TD>
                            </TR>

                          <TR>
                            <TD noWrap align=right> <?php echo $Adv_Pack[AdvContent];//广告内容?>：</TD>
                            <TD colspan="4" align=left noWrap>
                            <div  class="editorwidth">
                            <textarea name="FCKeditor1" id="redactor" cols="30" rows="10"><?php echo $adv_content;?></textarea>
                            </div><br /></TD>
                            </TR>
                          
														<TR>
															<TD noWrap align=right> 小螢幕banner：</TD>
															<TD colspan="4" align=left noWrap><div  class="editorwidth">
                                                            <textarea name="adv_banner" id="redactor1" cols="30" rows="10"><?php echo $adv_banner;?></textarea>
																</div></TD>
															
														</TR>
														
                          </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
<script language="javascript">
$(document).ready(function() {
	$('.css').on('click', function(evt){
        $('#jquiCSS').attr('href','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/'+this.innerHTML+'/jquery-ui.css');
        $('.css').removeClass('sel');
        $(this).addClass('sel');
    });
	$('#title_color').colorpicker({showOn:'focus'});
						   });
</script>
</BODY>
</HTML>
<?php echo $InitAjax;?>
 <script language="javascript">
 function CheckTag(Tag,adv_id){
 	var url = "Check_AdvTag.php?adv_id="+adv_id+"&tag="+Tag;
 	var show = document.getElementById("show_Tag");
 	//AjaxGetRequest(url,show)
 }
 function AjaxGetRequest(url,show){
 	if (typeof(url) == 'undefined'){
 		    return false;
 	}

 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
 	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
 	ajax.onreadystatechange = function() {
 		        	if (ajax.readyState == 4 && ajax.status == 200) {
 		        	show.innerHTML = ajax.responseText;
 		        	      		          }
        	}
 		        	ajax.send(null);
 	}
</script>
