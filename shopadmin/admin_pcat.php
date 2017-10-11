<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
if (!empty($_GET['copypid'])){
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($_GET['copypid'])." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Copy_id =  $Result['bid'];
		$Top_id  =  $Result['top_id'];
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
	$Action_say  = $Admin_Product[ModiProductClass] ; //修改商品類別
	$Sql = "select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($Bid)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Catname =  $Result['catname'];
		$Catord  =  $Result['catord'];
		$Top_id  =  $Result['top_id'];
		$gain    =  $Result['gain'];
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
		$nclass    =  $Result['nclass'];
		$rebate    =  $Result['rebate'];
		$costrebate    =  $Result['costrebate'];
		$link    =  $Result['link'];
		$manyunfei    =  $Result['manyunfei'];
		$subject_id    =  $Result['subject_id'];
		$subject_id2    =  $Result['subject_id2'];
		$saleoff_starttime    =  $Result['saleoff_starttime'];
		$saleoff_endtime    =  $Result['saleoff_endtime'];
		$language     =  $Result['language'];
		echo $brandlist     =  $Result['brandlist'];
		$url     =  $Result['url'];
		if ($Result['saleoff_starttime']!=""){
			$saleoff_startdate    =  date("Y-m-d",trim($Result['saleoff_starttime']));
			$start_h    =  date("H",trim($Result['saleoff_starttime']));
			$start_i    =  date("i",trim($Result['saleoff_starttime']));
		}
		if ($Result['saleoff_endtime']!=""){
			$saleoff_enddate    =  date("Y-m-d",trim($Result['saleoff_endtime']));
			$end_h    =  date("H",trim($Result['saleoff_endtime']));
			$end_i    =  date("i",trim($Result['saleoff_endtime']));
		}

		
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$Action_say   = $Admin_Product[CreateProductClass] ; //插入
	$Top_id  =  intval($_GET['top_id']);

	
}

if ($_GET['bid']!="" && $_GET['Action']=='Insert'){
	$Bid = intval($_GET['bid']);
	$Sql = "select * from `{$INFO[DBPrefix]}bclass` where bid=".intval($Bid)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Catname =  $Result['catname'];
		$Catord  =  $Result['catord'];
		$Top_id  =  $Result['top_id'];
		$Catiffb =  $Result['catiffb'];
		$Catcontent  =  $Result['catcontent'];
		$Catmenucolor =  $Result['catmenucolor'];
		$attrI   =  "0,".$Result['attr'];
		$Attr    =  explode(',',$attrI);
	}
}

if (is_file(RootDocumentShare."/cache/Productclass_show.php")  && strlen(trim(file_get_contents(RootDocumentShare."/cache/Productclass_show.php")))>25 ){
	include RootDocumentShare."/cache/Productclass_show.php";
}else{
	$BackUrl = "admin_pcat.php";
	include "admin_create_productclassshow.php";
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
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
				if ($_GET['bid']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_pcat_save.php?act=autosave&Bid=<?php echo $_GET['bid'];?>',
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
		location.href="admin_pcat.php?Action=Insert&bid="+bid;
	}
</SCRIPT>

<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_pcat_save.php' method=post enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Action_say?></SPAN></TD>
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
                                    <a href="admin_pcat_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD>
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
                            <TD>
															<input type="radio" name="catiffb" value="1" <?php if ($Catiffb == 1) echo "checked";?> <?php if ($hide == 1) //echo "disabled";?>>是
	                            <input type="radio" name="catiffb" value="0" <?php if ($Catiffb == 0) echo "checked";?> <?php if ($hide == 1) //echo "disabled";?>>否 　
															<a href="#" class="easyui-tooltip" title="父級類別未發布將無法修改"><img src="images/tip.png" width="16" height="16" border="0"></a>
														</TD>
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
                            <TD noWrap align=right>本館品牌：</TD>
                            <TD align=left>
                                <?php
								$class_array = explode(",",$brandlist);
					  $Query_class = $DB->query("select * from `{$INFO[DBPrefix]}brand` order by brand_id asc");
					  while ($Result_class = $DB->fetch_array($Query_class)){
					  ?>
                                <input type="checkbox" name="brandlist[]" value="<?php echo $Result_class['brand_id'];?>" <?php if (in_array($Result_class['brand_id'],$class_array)) echo "checked";?>><?php echo $Result_class['brandname'];?>
                                <?php
					  }
					  ?>
                               </TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              <?php echo $Admin_Product[UpPrductClassName];//父级类别名称?>：                        </TD>
                            <TD noWrap align=left>
                              <input type="hidden" name="bid" value="<?php echo $Bid?>">                        <?php echo  $Char_class->get_page_select("top_id",$Top_id,"   class=\"trans-input\"  ");?></TD></TR>
                          <?php if ($Action_value == "Insert") {?>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Product[CopyPrductClassAttrib];//拷贝类别属性：?>：</TD>
                            <TD noWrap align=left><?php echo $Char_class->get_page_select("copypid",$Bid,"  class=\"trans-input\" onchange='changecat(this.value)'");?></TD>
                            </TR>
                          <?php } ?>
                          <TR>
                            <TD noWrap align=right width="18%">
                              對應文章分類：</TD>
                            <TD noWrap align=left>
                              <select name="nclass">
                                <option value="">請選擇</option>
                                <?php
                        $nclass_Sql = "select * from `{$INFO[DBPrefix]}nclass` ";
						$Query_nclass    = $DB->query($nclass_Sql);
						while ($Rs_nclass=$DB->fetch_array($Query_nclass)) {
						?>
                                <option value="<?php echo $Rs_nclass['ncid'];?>" <?php if($nclass==$Rs_nclass['ncid']) echo "selected";?>><?php echo $Rs_nclass['ncname'];?></option>
                                <?php
						}
						?>
                                </select>                        				  </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>對應活動主題：</TD>
                            <TD noWrap align=left>
                              <select name="subject_id">
                                <option value="">請選擇</option>
                                <?php
                        $nclass_Sql = "select * from `{$INFO[DBPrefix]}discountsubject`";
						$Query_nclass    = $DB->query($nclass_Sql);
						while ($Rs_nclass=$DB->fetch_array($Query_nclass)) {
						?>
                                <option value="<?php echo $Rs_nclass['dsid'];?>" <?php if(intval($subject_id)==$Rs_nclass['dsid']) echo "selected";?>><?php echo $Rs_nclass['subject_name'];?></option>
                                <?php
						}
						?>
                                </select>
                              </TD>
                            </TR>
                         <!-- <TR>
                            <TD noWrap align=right>對應折扣主題：</TD>
                            <TD noWrap align=left>
                              <select name="subject_id2">
                                <option value="">請選擇</option>
                                <?php
                        $nclass_Sql = "select * from `{$INFO[DBPrefix]}sale_subject`";
						$Query_nclass    = $DB->query($nclass_Sql);
						while ($Rs_nclass=$DB->fetch_array($Query_nclass)) {
						?>
                                <option value="<?php echo $Rs_nclass['subject_id'];?>" <?php if(intval($subject_id2)==$Rs_nclass['subject_id']) echo "selected";?>><?php echo $Rs_nclass['subject_name'];?></option>
                                <?php
						}
						?>
                                </select>
                              注：對應的活動主題，折扣主題只能選其一
                              </TD>
                            </TR>-->
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
                            <TD align=right \>毛利：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('text','gain',$gain,"      maxLength=3 size=3 ")?>%</TD>
                            </TR>
                          <TR>
                            <TD align=right \>促銷時間：</TD>
                            <TD>
                            <?php echo $FUNCTIONS->Input_Box('text','saleoff_startdate',$saleoff_startdate," id=saleoff_startdate   onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"    maxLength=12 size=12 ")?><select name="start_h">
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
                                分
                                &nbsp;&nbsp; To&nbsp;&nbsp;        <?php echo $FUNCTIONS->Input_Box('text','saleoff_enddate',$saleoff_enddate," id=saleoff_enddate     onclick=\"showcalendar(event, this)\" onfocus=\"showcalendar(event,this);if(this.value=='0000-00-00')this.value=''\"      maxLength=12 size=12 ")?>
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
                                分　<a href="#" class="easyui-tooltip" title="無促銷時間請留空白"><img src="images/tip.png" width="16" height="16" border="0"></a>
                            </TD>
                          </TR>
                          <TR>
                            <TD align=right \>促銷折扣：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('text','rebate',$rebate,"      maxLength=3 size=3 ")?>%</TD>
                            </TR>
                          <TR>
                            <TD align=right \>促銷成本百分比：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('text','costrebate',$costrebate,"      maxLength=3 size=3 ")?>%</TD>
                            </TR>
                          <TR>
                            <TD align=right \>類別屬性：</TD>
                            <TD>
                              <?php
					  $class_sql = "select * from `{$INFO[DBPrefix]}attributeclass` where cid='" . intval($Bid) . "'";
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
                            <TD noWrap align=right>滿額免運費：</TD>
                            <TD noWrap align=left>滿<?php echo $FUNCTIONS->Input_Box('text','manyunfei',$manyunfei,"      maxLength=11 size=6 ")?>元免運費（本類不需要滿額免運費請設置為0，否則至少設置為1）</TD>
                            </TR>
                          <TR>
                            <TD align=right \>瀏覽等級：</TD>
                            <TD><?php
					  $level_goods = array();
					  $goods_sql = "select * from `{$INFO[DBPrefix]}bclass_userlevel` where bid='" . intval($Bid) . "'";
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
                              <input type="checkbox" name="userlevel[]" id="userlevel" value="<?php echo $Rs_level['level_id'];?>" <?php if (in_array($Rs_level['level_id'],$level_goods))  echo "checked";?> />
                              <?php echo $Rs_level['level_name'];?>
                              <?php
					   }
					  ?>
*<span id="yui_3_2_0_1_" lang="ZH-CN" xml:lang="ZH-CN">注意：若是任何人都可以瀏覽的商品類別請勿勾選。</span></TD>
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
                            <div class="editorwidth">
                            <textarea name="FCKeditor1" id="redactor" cols="30" rows="10" ><?php echo $Catcontent;?></textarea>
                            </div>
                            </TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD noWrap align=left>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%"><?php echo $Admin_Product[Attrib] ;//属性?>(1)<?php echo $Admin_Product[Say];//说明：?>：</TD>
                            <TD noWrap align=left>
                              <?php echo $FUNCTIONS->Input_Box('text',"attr1",$Attr[1],"      maxLength=50 size=40 ")?>
                              <div id="attr1tips" class="tips_big"><?php echo $Admin_Product[MuliAttribIntro];?></div></TD>
                            </TR>
                          <?php for($i=2;$i<=$INFO['b_attr'];$i++) { ?>
                          <TR>
                            <TD noWrap align=right width="18%"><?php echo $Admin_Product[Attrib] ;//属性?>(<?php echo $i?>)<?php echo $Admin_Product[Say];//说明：?>：</TD>
                            <TD noWrap align=left>
                              <?php echo $FUNCTIONS->Input_Box('text',"attr".$i,$Attr[$i],"      maxLength=50 size=40 ")?>					   </TD>
                            </TR>
                          <?php } ?>

                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD></TR>
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
