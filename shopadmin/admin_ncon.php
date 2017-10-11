<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Article_Pack.php";
if ($_GET['news_id']!="" && $_GET['Action']=='Modi'){
	$News_id = intval($_GET['news_id']);
	$Action_value = "Update";
	$Action_say  = $Article_Pack[ModiArticle] ; //修改文章
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}news` where news_id=".intval($News_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Top_id         =  $Result['top_id'];
		$nTitle         =  $Result['ntitle'];
		$nTitle_color   =  $Result['ntitle_color'];
		$nlTitle        =  $Result['nltitle'];
		$nlTitle_color  =  $Result['nltitle_color'];
		$Author         =  $Result['author'];
		$Url            =  $Result['url'];
		$Url_on         =  $Result['url_on'];
		$Nord           =  $Result['nord'];
		$Niffb          =  $Result['niffb'];
		$Brief          =  $Result['brief'];
		$Keywords       =  $Result['keywords'];
		$Nbody          =  $Result['nbody'];
		$Nimg           =  $Result['nimg'];
		$smallimg       =  $Result['smallimg'];
		$Nimg1          =  $Result['nimg1'];
		$smallimg1      =  $Result['smallimg1'];
		$pubdate        =  $Result['pubdate'];
		if ($Result['pubstarttime']!=""){			 $pubstarttime    =  date("Y-m-d",trim($Result['pubstarttime']));			$pubstart_h    =  date("H",trim($Result['pubstarttime']));			$pubstart_i    =  date("i",trim($Result['pubstarttime']));		}		if ($Result['pubendtime']!=""){			$pubendtime    =  date("Y-m-d",trim($Result['pubendtime']));			$pubend_h    =  date("H",trim($Result['pubendtime']));			$pubend_i    =  date("i",trim($Result['pubendtime']));		}

	}else{
		echo "<script language=javascript>javascript:window.history.back(-1);</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   =$Article_Pack[AddArticle]; //文章添加
}

if (is_file(RootDocumentShare."/cache/Newsclass_show.php")  && strlen(trim(file_get_contents(RootDocumentShare."/cache/Newsclass_show.php")))>25 ){
	include RootDocumentShare."/cache/Newsclass_show.php";
}else{
	$BackUrl = "admin_ncon.php";
	include "admin_create_newsclassshow.php";
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Article_Man];//文章管理?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
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
				if ($_GET['news_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_ncon_save.php?act=autosave&News_id=<?php echo $_GET['news_id'];?>',
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
<SCRIPT language=JavaScript>
function select_color(type)
{
	var str;
	var arr_str=new Array();
	str=window.showModalDialog("color_select.html","","dialogWidth:28;dialogHeight:20");
	if (!str) return false;
	arr_str=str.split("||");

	if (type==1){
	 document.form1.ntitle_color.value=arr_str[0];
	 document.form1.ntitle_color.value=arr_str[1];
	}else if (type==2){
	 document.form1.nltitle_color.value=arr_str[0];
	 document.form1.nltitle_color.value=arr_str[1];
	}

	return true;
}
</SCRIPT>

<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.top_id.value)){
			form1.top_id.focus();
			alert('<?php echo $Article_Pack[PleaseSelectArticle_ClassName]?>');  //请选择文章类别
			return;
		}

		if (chkblank(form1.ntitle.value) || form1.ntitle.value.length>50){
			form1.ntitle.focus();
			alert('<?php echo $Article_Pack[PleaseInputArticleTitle]?>');  //请输入文章标题
			return;
		}

		if (chkblank(form1.nltitle.value) || form1.nltitle.value.length>50){
			form1.nltitle.focus();
			alert('<?php echo $Article_Pack[PleaseInputArticle_Ltitle]?>');  //请输入小标题
			return;
		}
       /*
		if (chkblank(form1.author.value) || form1.author.value.length>30){
			form1.author.focus();
			alert('<?php echo $INFO['admin_ncon_author']?>');
			return;
 		}


	    if (chkblank(form1.brief.value) || form1.brief.value.length>1000){
			form1.brief.focus();
			alert('<?php echo $Article_Pack[PleaseInputArticle_Intro]?>');  //請輸入內容簡要
			return;
 		}

	    if (chkblank(form1.Body.value) || form1.Body.value.length>1000){
			form1.Body.focus();
			alert('<?php echo $INFO['admin_ncon_Body']?>');
			return;
 		}
      */

		//form1.action="admin_pcat_act.php?action=add";
		form1.submit();
	}

</SCRIPT>
<link id="jquiCSS" rel="stylesheet" href="../css/jquery-ui-1.9.2.custom.min.css" type="text/css" media="all">
<link id="jquiCSS" rel="stylesheet" href="../css/evol.colorpicker.css" type="text/css" media="all">
<script type="text/javascript" src="../js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="../js/evol.colorpicker.js"></script>
<div id="contain_out"><?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_ncon_save.php' method=post enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="Old_Nimg" value="<?php echo $Nimg?>">
	<input type="hidden" name="Old_Nimg1" value="<?php echo $Nimg1?>">
  <input type="hidden" name="News_id" value="<?php echo $News_id?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%" height="47">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Article_Man];//文章管理?>--&gt;<?php echo $Action_say?></SPAN></TD>
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
                            <a href="admin_ncon_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0><?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                                  </TR></TBODY></TABLE><!--BUTTON_END-->

                            </TD></TR></TBODY></TABLE>

                    </TD></TR></TBODY></TABLE>
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
                      <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=left noWrap>                        </TD>
                            <TD colspan="4" align=left noWrap><table width="100" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td class="link_buttom"><?php if ($_GET['Action']=='Modi'){?>
                                  <a href="admin_ncon_goods.php?id=<?php echo $News_id?>&Goodsname=<?php echo urlencode($nTitle);?>"><img src="images/<?php echo $INFO[IS]?>/fb-relatedpro.gif" width="32" height="32" border="0"> 相關商品</a>
                                  <?php }?></td>
                                </tr>
                              </table></TD>
                            </TR>
                          <TR>
                            <TD align=right noWrap><?php echo $Basic_Command['Iffb'];//是否发布：?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','niffb',$Niffb,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>日期：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','pubdate',$pubdate," id=pubdate   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?></TD>
													</TR>
													<TR >
														<TD noWrap align=right width="17%">上架時間：</TD>
														<TD colspan="3"><?php echo $FUNCTIONS->Input_Box('text','pubstarttime',$pubstarttime," id=pubstarttime   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?>
															<select name="pubstart_h">
																<?php for($i=0;$i<=23;$i++){ ?>
																	<option value="<?php echo $i;?>" <?php if($pubstart_h==$i) echo "selected";?>><?php echo $i;?></option>
																	<?php	} ?>
																</select>時
																<select name="pubstart_i">
																	<?php for($i=0;$i<=59;$i++){ ?>
																		<option value="<?php echo $i;?>"
																			<?php if($pubstart_i==$i) echo "selected";?>><?php echo $i;?></option>
																		<?php } ?>
																	</select>分 TO
																	<?php echo $FUNCTIONS->Input_Box('text','pubendtime',$pubendtime ," id='pubendtime' onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?>
																	<select name="pubend_h">
																		<?php for($i=0;$i<=23;$i++){ ?>
																			<option value="<?php echo $i;?>"
																				<?php if($pubend_h==$i) echo "selected";?>><?php echo $i;?></option>
																			<?php } ?>
																		</select>	時
																		<select name="pubend_i">
																			<?php for($i=0;$i<=59;$i++){ ?>
																				<option value="<?php echo $i;?>" <?php if($pubend_i==$i) echo "selected";?>><?php echo $i;?></option>
																				<?php } ?>
																			</select>
																			分
																		</TD>
																	</TR>
                          <TR>
                            <TD noWrap align=right width="18%"><?php echo $Article_Pack[Article_Class_Name];//文章類別名稱?>：</TD>
                            <TD colspan="4" align=left noWrap>
                              <?php echo  $Char_class->get_page_select("top_id",$Top_id,"  class=\"trans-input\" ");?>                      </TD>
                            </TR>

                          <TR>
                            <TD width="18%" align=right noWrap><?php echo $Article_Pack[Article_Title_Name];//新闻标题?>：</TD>
                            <TD width="32%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','ntitle',$nTitle,"      maxLength=50 size=50 ")?></TD>
                            <TD width="8%" align=right noWrap>&nbsp;&nbsp;&nbsp;<?php echo $Article_Pack[Article_Titlecolor] ;//标题颜色：?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','ntitle_color',$nTitle_color,"    maxLength=10 size=10  ")?>&nbsp;&nbsp;</TD>
														<!--INPUT onclick='select_color(1);' type=button value=<?php echo $Basic_Command['Please_Select'];?> name=button-->
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Article_Pack[Article_Ltitle];//小标题：?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','nltitle',$nlTitle,"      maxLength=50 size=50  ")?></TD>
                            <TD align=right noWrap>&nbsp;&nbsp;&nbsp;<?php echo $Article_Pack[Article_Titlecolor];//标题颜色：?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','nltitle_color',$nlTitle_color,"      maxLength=10 size=10 ")?>&nbsp;&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Article_Pack[Article_Author_Name];//作者：?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','author',$Author," maxLength=20 size=20 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>URL：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','url',$Url," maxLength=200 size=50 ")?>
                             <a href="#" class="easyui-tooltip" title="<?php echo $Article_Pack[OpenUrlIntro];?>"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                            <TD align=right noWrap><?php echo $Article_Pack[Article_OpenUrl];//启用：?>：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','url_on',$Url_on,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?><a href="#" class="easyui-tooltip" title="若須指定連結網址時選擇是，一般使用否即可"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>
                              <?php echo $Basic_Command['DisplayOrderby'];//显示顺序：?>：</TD>
                            <TD colspan="4" align=left noWrap>
                              <?php echo $FUNCTIONS->Input_Box('text','nord',$Nord,"      maxLength=10 size=10 ")?></TD></TR>
                          <TR>
                            <TD align=right \></TD>
                            <TD colspan="4"></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>瀏覽等級：</TD>
                            <TD colspan="4">

                              <?php
					  $level_goods = array();
					  $goods_sql = "select * from `{$INFO[DBPrefix]}news_userlevel` where nid='" . intval($news_id) . "'";
						  $Query_goods    = $DB->query($goods_sql);
						  $ig = 0;
						  while($Rs_goods=$DB->fetch_array($Query_goods)){
							$level_goods[$ig]=$Rs_goods['levelid'];
							$ig++;
						  }
					  $Sql_level      = "select * from `{$INFO[DBPrefix]}user_level` order by level_id ";
					  $Query_level    = $DB->query($Sql_level);
					   while($Rs_level=$DB->fetch_array($Query_level)){
					   ?>
                              <input type="checkbox" name="userlevel[]" id="userlevel" value="<?php echo $Rs_level['level_id'];?>" <?php if (in_array($Rs_level['level_id'],$level_goods))  echo "checked";?>><?php echo $Rs_level['level_name'];?>
                              <?php
					   }
					  ?><a href="#" class="easyui-tooltip" title="若都不限制時，請勿勾選"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                            </TR>


                          <TR>
                            <TD noWrap align=right><?php echo $Basic_Command['UploadFile'];//上传文件?>：</TD>
                            <TD colspan="4" align=left noWrap><input name="nimg" type="file" id="nimg"  size="40">
                              <a href="#" class="easyui-tooltip" title="<?php echo $Article_Pack[UploadIntro_All];?>"><img src="images/tip.png" width="16" height="16" border="0"></a>					  </TD>
                            </TR>
                          <?php if ($_GET['Action']=='Modi' && $Nimg!="") {?>
                          <TR>
                            <TD noWrap align=right><?php echo $Basic_Command['DisplayPic'];//图片显示?>：</TD>
                            <TD colspan="4" align=left noWrap>
                              <?php echo $FUNCTIONS->ImgTypeReturn("".$INFO['news_pic_path'] . "",$smallimg,'','');?>
                              &nbsp;&nbsp;<a href="admin_ncon_save.php?Action=DelPic&news_id=<?php echo $News_id?>" onClick="return confirm('<?php echo $Article_Pack['Del_Pic']?>')"><?php echo $Basic_Command['Del']?></a></TD>
                            </TR>
                          <?php } ?>
													<TR>
														<TD noWrap align=right><?php echo $Basic_Command['UploadFile'];//上传文件?>1：</TD>
														<TD colspan="4" align=left noWrap><input name="nimg1" type="file" id="nimg1"  size="40">
															<a href="#" class="easyui-tooltip" title="<?php echo $Article_Pack[UploadIntro_All];?>"><img src="images/tip.png" width="16" height="16" border="0"></a>					  </TD>
														</TR>
													<?php if ($_GET['Action']=='Modi' && $Nimg1!="") {?>
													<TR>
														<TD noWrap align=right><?php echo $Basic_Command['DisplayPic'];//图片显示?>：</TD>
														<TD colspan="4" align=left noWrap>
															<?php echo $FUNCTIONS->ImgTypeReturn("".$INFO['news_pic_path'] . "",$smallimg1,'','');?>
															&nbsp;&nbsp;<a href="admin_ncon_save.php?Action=DelPic1&news_id=<?php echo $News_id?>" onClick="return confirm('<?php echo $Article_Pack['Del_Pic']?>')"><?php echo $Basic_Command['Del']?></a></TD>
														</TR>
													<?php } ?>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Article_Pack[Article_Intro] ;//内容简要?> <a href="#" class="easyui-tooltip" title="簡介欄位請勿使用半形 ＂"><i class="icon-warning-sign" style="font-size:16px;color:#C00"></i></a> ：</TD>
                            <TD colspan="4" align=left valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('textarea','brief',$Brief,"cols=80 rows=8")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD width="18%" align=right valign="top" noWrap><?php echo $Article_Pack[Article_Body];//内容?>：</TD>
                            <TD colspan="4" align=left valign="top" noWrap>
                             <div  class="editorwidth">
                            <textarea name="FCKeditor1" id="redactor" cols="30" rows="10" ><?php echo $Nbody;?></textarea>
                            </div>
                            </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>TAG：</TD>
                            <TD colSpan=4><?php
					  $tag_goods = array();
					  $tag_sql = "select * from `{$INFO[DBPrefix]}article_tag` where news_id='" . intval($news_id) . "'";
						  $Query_tag= $DB->query($tag_sql);
						  $ig = 0;
						  while($Rs_tag=$DB->fetch_array($Query_tag)){
							$tag_goods[$ig]=$Rs_tag['tagid'];
							$ig++;
						  }
					  $Sql_tag      = "select * from `{$INFO[DBPrefix]}tag` order by tagid ";
					  $Query_tags    = $DB->query($Sql_tag);
					   while($Rs_tags=$DB->fetch_array($Query_tags)){
					   ?>
                              <input type="checkbox" name="tags[]" id="tags" value="<?php echo $Rs_tags['tagid'];?>" <?php if (in_array($Rs_tags['tagid'],$tag_goods))  echo "checked";?>><?php echo $Rs_tags['tagname']?>
                              <?php
					   }
					  ?></TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>Keywords：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('textarea','keywords',$Keywords,"cols=80 rows=8      ")?>
                            <p>&nbsp;</p></TD>
                            </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>

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
	$('#ntitle_color').colorpicker({showOn:'focus'});
	$('#nltitle_color').colorpicker({showOn:'focus'});
});
</script>
</BODY>
</HTML>
