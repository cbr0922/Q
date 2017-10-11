<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Article_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

if (intval($_GET['ncid'])!=""){
	$Egg = $FUNCTIONS->Sun_ncon_class(intval($_GET['ncid']));
	$Egg = $FUNCTIONS->Single_char($Egg);
	$Num_array = count($Egg);
	$Other_top="";
	if ($Num_array>0){
		for ($i=0;$i<$Num_array;$i++){
			$Other_top .=" or n.top_id=".intval($Egg[$i])." ";
		}
	}

	$Where = " where n.top_id=".intval($_GET['ncid'])." ".$Other_top."";
}
$Where    = trim($_GET['skey'])!=""  && urldecode($_GET['skey'])!=$Article_Pack[PleaseInputArticleTitle]  ?  " where n.ntitle like '%".trim(urldecode($_GET['skey']))."%' " : $Where ;
$Sql      = "select n.*,nc.ncid,nc.ncname,nc.top_id as nc_topid from `{$INFO[DBPrefix]}news` n  left join `{$INFO[DBPrefix]}nclass`  nc on (n.top_id=nc.ncid) ".$Where." order by n.nidate desc";
//$Sql      = "select * from `{$INFO[DBPrefix]}news`   ".$Where." order by  nord ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query    = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
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
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Article_Man];//文章管理?>--&gt;<?php echo $JsMenu[Article_List];//文章列表?>  </title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toComment(id){
	var checkvalue;
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo $Nums?>');
	}else{
		checkvalue = id;
	}
	
	if (checkvalue!=false){
		document.adminForm.action = "admin_comment_list.php?goodsid="+checkvalue;
		document.adminForm.submit();
	}
}

function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_ncon.php?Action=Modi&news_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){   //您是否确认删除选定的记录
			document.adminForm.action = "admin_ncon_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}

function toSave(){
		if (confirm('<?php echo $Article_Pack[YesOrNoModiOrderByNum]?>')){  //您是否確認保存修改顯示順序
			document.adminForm.action = "admin_ncon_list_save.php";
			document.adminForm.act.value="Save";
			document.adminForm.submit();
		}
}
</SCRIPT>

<div id="contain_out">
<?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Article_Man];//文章管理?>--&gt;<?php echo $JsMenu[Article_List];//文章列表?>  </SPAN>				</TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_ncon.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Article_Pack[AddArticle];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <!--TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:toSave();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; 
							</TD>
						  </TR>
						  </TBODY>
						</TABLE><!--BUTTON_END
						</TD>
					</TR>
					</TBODY>
					</TABLE>
					</TD-->
                  </TR>
                </TBODY>
              </TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name="optForm" method="get" action="">        
          <input type="hidden" name="Action" value="Search" id="Action">
          <TR>
            <TD align=left colSpan=2 height=31>
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width=500 border=0>
                <TBODY>
                  <TR>
                    <TD height=31 align=left>
                      <?php echo  $Char_class->get_page_select("ncid",$_GET['ncid'],"  class=\"trans-input\" ");?>
                      <INPUT  id='skey' name='skey'  onfocus="this.select()"  onclick="if(this.value=='<?php echo $Article_Pack[PleaseInputArticleTitle] ?>')this.value=''"  onmouseover=this.focus() value="<?php echo $Article_Pack[PleaseInputArticleTitle] ?>" size="40" /> 
                      <!--div id="skeytips" class="tips" align="left">&nbsp;<?php echo $Article_Pack[PleaseInputArticleTitle] ?></div -->
                      <?php //$FUNCTIONS->select_type_muliti("select * from bclass order by top_id asc ",'bid','bid','catname','')?>			     <INPUT type="image" src="images/<?php echo $INFO[IS]?>/t_go.gif" border="0" name="imageField" id="imageField" align="absmiddle" />                </TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示 ?><?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
              </TD>
            </TR>
          </FORM>
        </TABLE>
      
      
      <TABLE cellSpacing=0 cellPadding=0 width="100%" class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
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
                              <TD width="41" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle></TD>
                              <TD width="159"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Article_Pack[Article_Class_list];//类别名称?><BR></TD>
                              <TD width="429"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Article_Pack[Article_Title_Name] ;//新闻标题?></TD>
                              <TD width="108" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $Basic_Command['Iffb'];//是否发布：?></TD>
                              <TD width="98"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Article_Pack[Article_Author_Name] ;//作者?><BR></TD>
                              <TD width="86" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>點閱數</TD>
                              <TD width="91" height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Basic_Command['DisplayOrderby'];//显示顺序：?>          </TD>
                              <TD width="86" height=26 noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Basic_Command['FbTime'];//发布时间?></TD>
                              </TR>
                            <?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

						$Yes = "images/".$INFO[IS]."/publish_g.png";
						$No  = "images/".$INFO[IS]."/publish_x.png";
						$niffb_pic  = $Rs['niffb']==1  ? $Yes : $No ;


					?><tbody>
                              <TR>
                                <TD align=center height=20>
                                  <INPUT id='cb<?php echo $i?>'  onclick="isChecked(this);" type="checkbox" value='<?php echo $Rs['news_id']?>' name="cid[]" />
                                <INPUT type=hidden value='<?php echo $Rs['news_id']?>' name=Ci[]>					  </TD>
                                <TD height=26 align="left" noWrap>
                                  
                                  
                                  <?php	
					   /*
					   $Egg  = $FUNCTIONS->father_news_class(intval($Rs['top_id']));
					   $Egg  = str_replace(" ","<-",$Egg);
					   echo $Egg  = $Egg!="" ? "   ".$Egg."  "  : "" ;
					   */
						?>
                                <div id='Ncname<?php echo $i;?>'><a onClick="ChangeNcnameInnerHtml('Ncname<?php echo $i;?>','<?php echo trim($Rs['top_id'])?>',<?php echo intval($Rs['ncid'])?>)" ><?php echo $nroot = $Rs['ncname']!="" ? $Rs['ncname'] : "├─/";?></a></div>						</TD>
                                <TD height=26 align="left" noWrap><INPUT id="newsidArr[]" type="hidden" value='<?php echo $Rs['news_id']?>' name="newsidArr[]" />
                                <A href="javascript:toEdit('<?php echo $Rs['news_id']?>',0);"> <?php echo $Rs['ntitle']?>&nbsp; </A></TD>
                                <TD height=26 align="center" noWrap>
                                  <div id='nIffb<?php echo $i;?>'><a onClick="ChangeNIffbInnerHtml('nIffb<?php echo $i;?>','<?php echo $Rs['niffb']?>',<?php echo intval($Rs['news_id'])?>)" ><img src="<?php echo $niffb_pic?>" border="0" /></a></div>
                                </TD>
                                <TD height=26 align=center nowrap>
                                <div id='Nauthor<?php echo $i;?>'><a onClick="ChangeNauthorInnerHtml('Nauthor<?php echo $i;?>','<?php echo trim($Rs['author'])?>',<?php echo intval($Rs['news_id'])?>)" ><?php echo $Rs['author']?></a>&nbsp;</div>                     </TD>
                                <TD height=26 align=center nowrap><?php echo $Rs['view']?></TD>
                                <TD height=26 align=center nowrap>
                                  <div id='nsort<?php echo $i;?>'><a onClick="ChangeNSortInnerHtml('nsort<?php echo $i;?>','<?php echo $Rs['nord']?>',<?php echo intval($Rs['news_id'])?>)" ><?php echo $Rs['nord']?></a></div>	
                                <!--INPUT  id="order[]" size='5' value='<?php //echo $Rs['nord']?>' name="order[]" /--></TD>
                                <TD height=26 align=middle nowrap><?php echo date("Y/m/d",$Rs['nidate'])?>&nbsp;</TD>
                              </TR></tbody>
                          <?php
					$i++;
					}
					?>
                          </FORM>
                        </TABLE>
                      </TD>
                    </TR>
                </TABLE>
              <?php if ($Num>0){ ?>
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=right background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
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
<!-------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
/**
 *   引入AJAX
 */
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
echo $InitAjax;
?>
<script language="javascript">
/**
* ajax 改变值状态 新闻顺序
*/

function ChangeNSortInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT type='text'  size='6' value="+OldValue+" onblur=changeNSortInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}

function changeNSortInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateNews.php?Type=updateNSort&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'Sort');
}


/**
* ajax 改变值状态 作者
*/

function ChangeNauthorInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT type='text'  size='10' value="+OldValue+" onblur=changeNauthorInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}

function changeNauthorInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateNews.php?Type=updateNauthor&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'Nauthor');
}

/**
* ajax 改变值状态 所归属的新闻类
*/

function ChangeNcnameInnerHtml(Element,OldValue,Id){

	var url    = "./ajax_updateNews.php?Type=updateNcname&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ViewNcname');
}

function ChangeNcanmeActionInnerHtml(Element,Value,Id){
	var url    = "./ajax_updateNews.php?Type=updateNcnameAction&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;

	AjaxGetRequestInnerHtml(url,Id,Element,'ActionNcname');
}

/**
* ajax 改变值状态 产品是否发布
*/

function ChangeNIffbInnerHtml(Element,Value,Id){
	var url    = "./ajax_updateNews.php?Type=updateNIffb&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;

	// alert(url);
	AjaxGetRequestInnerHtml(url,Id,Element,'NIffb');
}


function AjaxGetRequestInnerHtml(url,Id,Element,Type){

	if (typeof(url) == 'undefined'){
		    return false;
	}
	if (typeof(Id) == 'undefined'){
		    return false;
	}
	if (typeof(Element) == 'undefined'){
		    return false;
	}
	if (typeof(Type) == 'undefined'){
		    return false;
	}


	var ajax = InitAjax();
	ajax.open("GET", url, true);
	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
	ajax.onreadystatechange = function() {
		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
		    if (ajax.readyState == 4 && ajax.status == 200) {

		    	if (Type=='Sort'){
		    		txt = "<a onclick=ChangeNSortInnerHtml('"+Element+"','"+ajax.responseText+"','"+Id+"')>"+ajax.responseText+"</a>";
		    	}
		    	if (Type=='Nauthor'){
		    		txt = ajax.responseText;
		    	}
		    	if (Type=='ViewNcname'){
		    		txt = ajax.responseText;
		    	}
		    	if (Type=='ActionNcname'){
		    		txt = ajax.responseText;
		    	}
		    	if (Type=='NIffb'){
		    		txt = ajax.responseText;
		    	}
		    	// alert(txt);
		    	var show   = document.getElementById(Element);
		    	show.innerHTML =  txt; 		        		      

		    }
		    }
		    ajax.send(null);
		    }


</script>
