<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
$brand_id = $_GET['brand_id'];
if (!empty($_GET['copypid'])){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand_class` where bid=".intval($_GET['copypid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Copy_id =  $Result['bid'];
		$top_id  =  $Result['top_id'];
		$Catcontent  =  $Result['catcontent'];
		$Catmenucolor =  $Result['catmenucolor'];
		$attrI   =  "0,".$Result['attr'];
		$Attr    =  explode(',',$attrI);
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}
if ($_GET['bid']!="" && $_GET['Action']=='Modi'){
	$Bid = intval($_GET['bid']);
	$Action_value = "Update";
	$Action_say  = "修改品牌下屬分類" ; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}brand_class` where bid=".intval($Bid)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Catname =  $Result['catname'];
		$Catname_en =  $Result['catname_en'];
		$brand_id =  $Result['brand_id'];
		$Catord  =  $Result['catord'];
		$top_id  =  $Result['top_id'];
		
		$pic1    =  $Result['pic1'];
		$pic2    =  $Result['pic2'];
		$banner    =  $Result['banner'];
		$banner2    =  $Result['banner2'];
		$ifhome    =  $Result['ifhome'];
		$Catiffb =  $Result['catiffb'];
		$Catcontent  =  $Result['catcontent'];
		$Catmenucolor =  $Result['catmenucolor'];
		$attrI   =  "0,".$Result['attr'];
		$Attr    =  explode(',',$attrI);
		$meta_des    =  $Result['meta_des'];
		$meta_key    =  $Result['meta_key'];
		
		$link    =  $Result['link'];
		
		$language     =  $Result['language'];
		
		$url     =  $Result['url'];
		
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$brand_id = intval($_GET['brand_id']);
	$Action_say   = "新增品牌下屬分類"; //插入
}

if ($_GET['bid']!="" && $_GET['Action']=='Insert'){
	$Bid = intval($_GET['bid']);
	$Sql = "select * from `{$INFO[DBPrefix]}brand_class` where bid=".intval($Bid)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Catname =  $Result['catname'];
		$Catord  =  $Result['catord'];
		$top_id  =  $Result['top_id'];
		$Catiffb =  $Result['catiffb'];
		$Catcontent  =  $Result['catcontent'];
		$Catmenucolor =  $Result['catmenucolor'];
		$attrI   =  "0,".$Result['attr'];
		$Attr    =  explode(',',$attrI);
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Brand_Man] ;//品牌管理?>--&gt;<?php echo $Action_say?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		if (chkblank(form1.catname.value) || form1.catname.value.length>50){
			alert('<?php echo $Admin_Product[PleaseInputPrductClassName]?>'); //請輸入商品類別名稱
			form1.catname.focus();
			return;
		}
		if (form1.bid.value != "" && form1.bid.value == form1.top_id.value){
			alert('<?php echo $Admin_Product[BadClassOp] ?>');  //你不能將分類自身作爲它的下級子分類！
			form1.top_id.focus();
			return;
		}
		//form1.action="admin_pcat_act.php?action=add";
		form1.submit();
	}
	function changecat(bid){
		//form1.action="admin_pcat.php?Action=Modi&bid="+bid;
		//form1.action="admin_pcat.php?Action=Insert&bid="+bid;
		//form1.submit();
		location.href="admin_brand_class.php?Action=Insert&bid="+bid;
	}
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
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
				if ($_GET['bid']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_brand_class_save.php?act=autosave&bid=<?php echo $_GET['bid'];?>',
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
  <FORM name=form1 action='admin_brand_class_save.php' method=post enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="brand_id" value="<?php echo $brand_id?>">   
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Brand_Man] ;//品牌管理?>--&gt;<?php echo $Action_say?></SPAN></TD>
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
                                    <a href="admin_brand_class_list.php?brand_id=<?php echo $brand_id;?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                      <TABLE class=allborder cellSpacing=0 cellPadding=2   width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD></TR>
                          <TR>
                            <TD align=right \><?php echo $Basic_Command['Iffb'];//是否发布?>：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('radio','catiffb',$Catiffb,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
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
                            <TD align=right \>是否精選：</TD>
                            <TD><input type="radio" name="ifhome" value="1" <?php if ($ifhome == 1) echo "checked";?>>
                              是
                              <input name="ifhome" type="radio" value="0" <?php if ($ifhome == 0) echo "checked";?>>
                            否 　<a href="#" class="easyui-tooltip" title="手機版可切換成只出現精選分類"><img src="images/tip.png" width="16" height="16" border="0"></a></TD>
                          </TR>
                          <TR>
                            <TD noWrap align=right width="18%">                        <?php echo $Admin_Product[PrductClassName] ;//类别名称?>：</TD>
                            <TD noWrap align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','catname',$Catname,"      maxLength=50 size=40 ")?>				  </TD>
                            </TR>
                           
							<TR>
                            <TD align=right \>指定連結：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('text','url',$url,"      maxLength=255 size=60 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              <?php echo $Admin_Product[UpPrductClassName];//父级类别名称?>：                        </TD>
                            <TD noWrap align=left>
                              <input type="hidden" name="bid" value="<?php echo $Bid?>">
                              <?php
								$return = "";
								$Char_class->getBrandClassSelect(0,0,$brand_id,$top_id);
								?>
                              <select name="top_id">
                              <option value="0">請選擇</option>
                              	<?php echo $return;?>
                              </select>
                            </TD></TR>
  
                          <TR>
                            <TD noWrap align=right width="18%">
                              <?php echo $Basic_Command['DisplayOrderby'];//显示顺序：?>：</TD>
                            <TD noWrap align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','catord',$Catord,"      maxLength=10 size=10 ")?>                        </TD></TR>
                          <TR>
                            <TD align=right \>選單圖片：</TD>
                            <TD><INPUT  id="img_menu"  type="file" size="40" name="img_menu" ><INPUT type=hidden   name='old_img_menu'  value="<?php echo $pic1?>"></TD>
                            </TR>
                          <?php if (is_file("../".$INFO['good_pic_path']."/".$pic1)){?>
                          <TR>
                            <TD align=right \>&nbsp;</TD>
                            <TD><img src="<?php echo "../".$INFO['good_pic_path']."/".$pic1?>"><a href="admin_pcat_save.php?id=<?php echo $Bid;?>&pic=<?php echo $pic1;?>&type=pic1&Action=delPic">刪除圖片</a></TD>
                            </TR>
                          <?php
					}
					?>
                          <TR>
                            <TD align=right \>選單滑鼠圖片：</TD>
                            <TD><INPUT  id="img_menu_m"  type="file" size="40" name="img_menu_m" ><INPUT type=hidden   name='old_img_menu_m'  value="<?php echo $pic2?>"></TD>
                            </TR>
                          <?php if (is_file("../".$INFO['good_pic_path']."/".$pic2)){?>
                          <TR>
                            <TD align=right \>&nbsp;</TD>
                            <TD><img src="<?php echo "../".$INFO['good_pic_path']."/".$pic2?>"><a href="admin_pcat_save.php?id=<?php echo $Bid;?>&pic=<?php echo $pic2;?>&type=pic2&Action=delPic">刪除圖片</a></TD>
                            </TR>
                          <?php
					}
					?>
                          <TR>
                            <TD align=right \>Banner：</TD>
                            <TD><INPUT  id="img_banner"  type="file" size="40" name="img_banner" ><INPUT type=hidden   name='old_img_banner'  value="<?php echo $banner?>"></TD>
                            </TR>
                          <?php if (is_file("../".$INFO['good_pic_path']."/".$banner)){?>
                          <TR>
                            <TD align=right \>&nbsp;</TD>
                            <TD><img src="<?php echo "../".$INFO['good_pic_path']."/".$banner?>"><a href="admin_pcat_save.php?id=<?php echo $Bid;?>&pic=<?php echo $banner;?>&type=banner&Action=delPic">刪除圖片</a></TD>
                            </TR>
                          <?php
					}
					?>
                          <TR>
                            <TD align=right \>Banner2：</TD>
                            <TD><INPUT  id="img_banner2"  type="file" size="40" name="img_banner2" ><INPUT type=hidden   name='old_img_banner2'  value="<?php echo $banner2?>"></TD>
                            </TR>
                          <?php if (is_file("../".$INFO['good_pic_path']."/".$banner2)){?>
                          <TR>
                            <TD align=right \>&nbsp;</TD>
                            <TD><img src="<?php echo "../".$INFO['good_pic_path']."/".$banner2?>"><a href="admin_pcat_save.php?id=<?php echo $Bid;?>&pic=<?php echo $banner2;?>&type=banner2&Action=delPic">刪除圖片</a></TD>
                            </TR>
                          <?php
					}
					?>
                          <TR>
                            <TD align=right \>Banner2連結：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('text','link',$link,"      maxLength=255 size=60 ")?></TD>
                            </TR>
                          <TR>
                            <TD align=right \>類別屬性：</TD>
                            <TD>
                              <?php
					  $class_sql = "select * from `{$INFO[DBPrefix]}brand_attributeclass` where cid='" . intval($Bid) . "'";
					  $Query_class    = $DB->query($class_sql);
					  $ic = 0;
					  $attr_class = array();
					  while($Rs_class=$DB->fetch_array($Query_class)){
					  	$attr_class[$ic]=$Rs_class['attrid'];
						$ic++;
					  }
					  $attr_sql = "select * from `{$INFO[DBPrefix]}attribute` order by attrid desc";
					  $Query_attr    = $DB->query($attr_sql);
						$Num_attr      = $DB->num_rows($Query_attr);
						if ($Num_attr>0){
							while ($Rs_attr=$DB->fetch_array($Query_attr)) {
					  ?>
                              <input type="checkbox" name="attribute[]" id="attribute" value="<?php echo $Rs_attr['attrid'];?>" <?php if (in_array($Rs_attr['attrid'],$attr_class))  echo "checked";?>><?php echo $Rs_attr['attributename'];?>
                              <?php
							}
						}
					  ?>					  </TD>
                            </TR>
                          <TR>
                            <TD align=right \>&nbsp;</TD>
                            <TD></TD>
                            </TR>
                            <TR>
                              <TD noWrap align=right>meta description：</TD>
                              <TD width="83%"><span id="yui_3_2_0_1_13115562779589743" lang="ZH-CN" xml:lang="ZH-CN"><?php echo $FUNCTIONS->Input_Box('textarea','meta_des',$meta_des," cols=80 rows=3  ")?></span></TD>
                              </TR>
                          <TR>
                            <TD align=right \>meta keyword：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('textarea','meta_key',$meta_key," cols=80 rows=3  ")?></TD>
                            </TR>

                          <TR>
                            <TD noWrap align=right><?php echo $Admin_Product[Detail_intro];//详细描述?>：</TD>
                            <TD noWrap align=left>
                            <div  class="editorwidth">
                            <textarea name="FCKeditor1" id="redactor" cols="30" rows="10" ><?php echo $Catcontent;?></textarea>
                            </div>
                            </TD>
                            </TR>
                          <?php for($i=2;$i<=$INFO['b_attr'];$i++) { ?>
                          <?php } ?>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD colspan="2" align=center noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD>
                            </TR>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE>                      </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>