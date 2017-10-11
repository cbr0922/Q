<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";


if ($_GET['brand_id']!="" && $_GET['Action']=='Modi'){
	$brand_id = intval($_GET['brand_id']);
	$Action_value = "Update";
	$Action_say  = $Admin_Product[ModiBrandName]; //修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}brand` where brand_id=".intval($brand_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$brandname    =  trim($Result['brandname']);
		$brandname_en    =  trim($Result['brandname_en']);
		$logopic      =  trim($Result['logopic']);
		$brand_id     =  intval($Result['brand_id']);
		$brandcontent =  $Result['brandcontent'];
		$content =  $Result['content'];
		$classid =  $Result['classid'];	
		$brandpic =  $Result['brandpic'];	
		$meta_des =  $Result['meta_des'];
		$meta_key =  $Result['meta_key'];	
		$viewcount =  intval($Result['viewcount']);
		$orderby =  $Result['orderby'];
		$language     =  $Result['language'];		
		$bdiffb  =  $Result['bdiffb'];	
		$title1  =  $Result['title1'];
		$title2  =  $Result['title2'];
		$ratio  =  $Result['ratio'];
		$goodlist  =  $Result['goodlist'];
		$ifshowgoods  =  $Result['ifshowgoods'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say  = $Admin_Product[AddBrandName]; //添加
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Brand_Man] ;//品牌管理?>--&gt;<?php echo $Action_say?></TITLE>
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
				if ($_GET['brand_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_brand_save.php?act=autosave1&brand_id=<?php echo $_GET['brand_id'];?>',
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
				if ($_GET['brand_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_brand_save.php?act=autosave2&brand_id=<?php echo $_GET['brand_id'];?>',
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
		if (form1.brandname.value == ""){
			alert('<?php echo $Admin_Product[PleaseInputPrductBand];?>');  //请输入品牌名稱！
			form1.brandname.focus();
			return;			
		}
		form1.submit();
	}
	
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_brand_save.php' method='post' enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="brand_id" value="<?php echo $brand_id?>">
  <input type="hidden" name="old_pic" value="<?php echo $logopic?>">  
<input type="hidden" name="old_brandpic" value="<?php echo $brandpic?>"> 
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Brand_Man] ;//品牌管理?>--&gt;<?php echo $Action_say?></SPAN>
                    </TD>
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
                            <a href="admin_brand_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->							</TD></TR></TBODY></TABLE>				</TD>
                  </TR>			  
                </TBODY>
              </TABLE></TD></TR>
        </TBODY>
</TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD colspan="4" align=right noWrap>&nbsp;</TD></TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Basic_Command['Iffb'];//是否发布：?>：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','bdiffb',$bdiffb,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
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
                            <TD noWrap align=right><?php echo $Admin_Product[Brand_Name];//品牌名稱：?>：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','brandname',$brandname,"      maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                           <TR>
                            <TD noWrap align=right>英文名稱：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','brandname_en',$brandname_en,"      maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right noWrap>點擊次數： </TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','viewcount',$viewcount,"      maxLength=10 size=10 ")?></TD>
                            <TD rowspan="2" align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" rowspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                           <TR>
                            <TD align=right noWrap>權重：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','ratio',$ratio,"      maxLength=40 size=10 ")?></TD>
                            </TR> 
                          <TR>
                            <TD align=right noWrap>排序：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','orderby',$orderby,"      maxLength=40 size=10 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>所屬館別：</TD>
                            <TD align=left>
                                <?php
								$class_array = explode(",",$classid);
					  $Query_class = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where top_id=0 order by top_id asc");
					  while ($Result_class = $DB->fetch_array($Query_class)){
					  ?>
                                <input type="checkbox" name="classid[]" value="<?php echo $Result_class['bid'];?>" <?php if (in_array($Result_class['bid'],$class_array)) echo "checked";?>><?php echo $Result_class['catname'];?>
                                <?php
					  }
					  ?>
                               </TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right> <?php echo $Admin_Product[Brand_Logo];//品牌LOGO?>：</TD>
                            <TD width="38%" align=left noWrap><input name="ima" type="file" id='ima' ></TD>
                            <TD width="8%" align=right noWrap>&nbsp;</TD>
                            <TD width="9%" colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          
                          <?php if ($logopic!="") { ?>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" align=left noWrap class="p9orange">
                              
                              <?php echo $FUNCTIONS->ImgTypeReturn($INFO['logo_pic_path'],$logopic,'','');?>&nbsp;&nbsp;<a href="admin_brand_save.php?Action=DelPic&brand_id=<?php echo $brand_id?>" onClick="return confirm('<?php echo $Admin_Product[Del_Pic]?>?')"><i class="icon-trash" style="font-size:14px;margin-right:5px;margin-left:10px"></i><?php echo $Basic_Command['Del']?></a>				  </TD>
                            </TR>
                          
                          <?php } ?>
                          <TR>
                            <TD noWrap align=right>品牌圖片：</TD>
                            <TD align=left noWrap><input name="pic" type="file" id='pic' ></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <?php if ($brandpic!="") { ?>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD colspan="4" align=left noWrap class="p9orange">
                              
                              <?php echo $FUNCTIONS->ImgTypeReturn($INFO['logo_pic_path'],$brandpic,'','');?>&nbsp;&nbsp;<a href="admin_brand_save.php?Action=DelBrandPic&brand_id=<?php echo $brand_id?>" onClick="return confirm('<?php echo $Admin_Product[Del_Pic]?>?')"><i class="icon-trash" style="font-size:14px;margin-right:5px;margin-left:10px"></i><?php echo $Basic_Command['Del']?></a>				  </TD>
                            </TR>
                          
                          <?php } ?>	
						  <TR>
                            <TD noWrap align=right>是否顯示暢銷排行：</TD>
                            <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','ifshowgoods',$ifshowgoods,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>
                          </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>暢銷排行：</TD>
                            <TD align=left noWrap>
                             
							<textarea name="goodlist" id="goodlist" cols="30" rows="10" ><?php echo $goodlist;?></textarea>
                            
                            </TD>
							  </tr>
                          <TR>
                            <TD align=right valign="top" noWrap>meta description：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('textarea','meta_des',$meta_des," cols=80 rows=3  ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>meta key：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('textarea','meta_key',$meta_key," cols=80 rows=3  ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>標題1：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','title1',$title1,"      maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD align=right valign="top" noWrap><?php echo $Admin_Product[BrandIntro]?>：</TD>
                            <TD align=left noWrap>
                             <div  class="editorwidth">
							<textarea name="FCKeditor1" id="redactor" cols="30" rows="10" ><?php echo $brandcontent;?></textarea>
                            </div>
							</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>標題2：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','title2',$title2,"      maxLength=40 size=40 ")?></TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>品牌介紹：</TD>
                            <TD align=left noWrap>
                             <div  class="editorwidth">
							<textarea name="content" id="redactor1" cols="30" rows="10" ><?php echo $content;?></textarea>
                            </div>
                            </TD>
							  </tr>
                         
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD align=right noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>
  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
