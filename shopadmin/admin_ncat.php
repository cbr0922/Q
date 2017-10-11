<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Article_Pack.php";
$templatedata = array(1=>"1. 左產品分類右文章列表",2=>"2. 左產品分類右圖文簡介的文章列表",3=>"3. 左文章標題右文章內容",4=>"4. 左文章分類右文章列表",5=>"5. 左文章分類右圖文簡介的文章列表",6=>"6. 問與答",7=>"7. 全版規則格子圖文簡介列表",8=>"8. 全版文章內容",9=>"9. 全牆面文章列表");
if ($_GET['ncid']!="" && $_GET['Action']=='Modi'){
	$nCid = intval($_GET['ncid']);
	if(is_array($_GET['ncid']))
		$nCid = $_GET['ncid'][0];
	$Action_value = "Update";
	$Action_say  = $Article_Pack[ModiArticle_Class]; //修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}nclass` where ncid=".intval($nCid)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$nCatname =  $Result['ncname'];
		$nCatord  =  $Result['ncatord'];
		$Top_id   =   $Result['top_id'];
		$nCatiffb =  $Result['ncatiffb'];
		$Ncimg    =  $Result['ncimg'];
		$classid   =  $Result['classid'];
		$templatetype   =  $Result['templatetype'];
		$template   =  $Result['template'];
		$ifcomment   =  $Result['ifcomment'];
		$meta_des =  $Result['meta_des'];
		$meta_key =  $Result['meta_key'];
		$language     =  $Result['language'];
		$path     =  $Result['path'];
		$brand_id     =  $Result['brand_id'];
	}else{
		$FUNCTIONS->header_location('admin_create_newsclassshow.php');
		//echo "<script language=javascript>javascript:window.close();< / script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = $Article_Pack[AddArticle_Class]; //插入
}
include ConfigDir."/cache/Newsclass_show.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Article_Man];//文章管理?>--&gt;<?php echo $Action_say?> </TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		if (chkblank(form1.ncname.value) || form1.ncname.value.length>50){
			form1.ncname.focus();
			alert('<?php echo $Article_Pack[PleaseInputArticle_ClassName]?>');
			return;
		}
		if (form1.ncid.value != "" && form1.ncid.value == form1.top_id.value){
			form1.top_id.focus();
			alert('<?php echo $Article_Pack[PleaseInputArticle_ClassToSub]?>');
			return;
		}
		//form1.action="admin_pcat_act.php?action=add";
		form1.submit();
	}

</SCRIPT>

<div id="contain_out"><?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_ncat_save.php' method=post enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="Old_Ncimg" value="<?php echo $Ncimg?>">
  <input type="hidden" name="ncid" value="<?php echo $nCid?>">
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"
                  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Article_Man];//文章管理?>--&gt;<?php echo $Action_say?> </SPAN></TD>
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
                            <a  href="admin_ncat_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->

                            </TD></TR></TBODY></TABLE>

                    </TD></TR></TBODY></TABLE>
              </TD>
            </TR>

          </TBODY>

        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=3 width="100%" align=center bgColor=#f7f7f7 border=0>
                        <TBODY>
                          <TR>
                            <TD noWrap align=right width="18%">&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD></TR>
                          <TR>
                            <TD align=right><?php echo $Basic_Command['Iffb'];//是否发布?>：</TD>
                            <TD>
                              <?php echo $FUNCTIONS->Input_Box('radio','ncatiffb',$nCatiffb,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?>					  </TD>
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
                            <TD noWrap align=right width="18%">
                              <?php echo $Article_Pack[Article_Class_Name];//类别名称?>：</TD>
                            <TD noWrap align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','ncname',$nCatname,"      maxLength=50 size=40 ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right width="18%">
                              <?php echo $Article_Pack[Article_Class_FatherName];//父级类别名称?>：</TD>
                            <TD noWrap align=left>
                                <?php echo  $Char_class->get_page_select("top_id",$Top_id,"  class=\"trans-input\" ");?>
                            </TD>
                            </TR>
                          <TR>
                            <TD align=right width="18%">
                              <?php echo $Basic_Command['DisplayOrderby'];//显示顺序：?>：</TD>
                            <TD align=left>
                                <?php echo $FUNCTIONS->Input_Box('text','ncatord',$nCatord,"      maxLength=10 size=10 ")?>
                            </TD></TR>
                          <TR>
                            <TD align=right>對應館別：</TD>
                            <TD><select name="classid">
                              <option value="0">請選擇館別</option>
                              <?php
					  $Query_class = $DB->query("select * from `{$INFO[DBPrefix]}bclass` where top_id=0 order by top_id asc");
					  while ($Result_class = $DB->fetch_array($Query_class)){
					  ?>

                              <option value="<?php echo $Result_class['bid'];?>" <?php if ($Result_class['bid']==$classid) echo "selected";?>><?php echo $Result_class['catname'];?></option>
                              <?php
					  }
					  ?>
                              </select></TD>
                            </TR>
                           <TR>
                            <TD align=right width="18%">
                              品牌：</TD>
                            <TD align=left>
                                <?php echo $FUNCTIONS->select_type("select brandname,brand_id from `{$INFO[DBPrefix]}brand` order by orderby asc,brand_id asc  ","brand_id","brand_id","brandname",intval($brand_id)," ");  ?>
                            </TD></TR> 
                           <TR>
                            <TD align=right>目錄名稱：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('text','path',$path,"      maxLength=50 size=40 ")?>&nbsp;默認為article，填寫形式為our字母,比如填寫ourService訪問網址為http://cms.ddcs.com.tw/ourService/index.php</TD>
                          </TR>
                          
                          <!--TR>
                            <TD align=right>模板類型：</TD>
                            <TD>
                              <select name="templatetype">
                                <?php
                      foreach($templatedata as $k=>$v){
					  ?>
                                <option value="<?php echo $k;?>" <?php if ($templatetype == $k) echo "selected";?>><?php echo $v;?></option>
                                <?php
					  }
					  ?>
                                </select>
                              </TD>
                            </TR-->
														<TR align="center">
															<TD align="right" valign="middle" noWrap>模板文件：</TD>
															<TD colspan="2" align="left" valign="middle" noWrap><?php echo $FUNCTIONS->Input_Box('text','template',$template," maxLength=255 size=20  ")?>&nbsp;默認為article_classindex1.html</TD>
														</TR>
                          <TR>
                            <TD align=right>評論：</TD>
                            <TD><?php echo $FUNCTIONS->Input_Box('radio','ifcomment',$ifcomment,$Add=array("開啟","關閉"))?></TD>
                            </TR>
                          <TR>
                            <TD align=right><?php echo $Basic_Command['UploadFile'];//上传文件?>：</TD>
                            <TD><input name="ncimg" type="file" id="ncimg"  size="40" /> <a href="#" title="<?php echo $Article_Pack[UploadIntro_ncat]?>"  class="easyui-tooltip"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </TD>
                            </TR>
                          <?php if ($_GET['Action']=='Modi' && $Ncimg!="") {?>
                          <TR>
                            <TD align=right>&nbsp;</TD>
                            <TD><img src="../<?php echo $INFO['news_pic_path']. "/" . $Ncimg?>"><a href="admin_ncat_save.php?id=<?php echo $nCid;?>&pic=<?php echo $Ncimg;?>&type=pic1&Action=delPic">刪除圖片</a></TD>
                            </TR>
                          <?php } ?>



                          <TR>
                            <TD noWrap align=right width="18%">meta description：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('textarea','meta_des',$meta_des," cols=80 rows=6  ")?></TD></TR>
                          <TR>
                            <TD noWrap align=right>meta key：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('textarea','meta_key',$meta_key," cols=80 rows=6  ")?></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>&nbsp;</TD>
                            <TD noWrap align=right>&nbsp;</TD>
                            </TR>
                    </TBODY></TABLE>

                     </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
