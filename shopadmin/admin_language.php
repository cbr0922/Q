<?php
include_once "Check_Admin.php";

/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";



if ($_GET['id']!="" && $_GET['Action']=='Modi'){

	$id = intval($_GET['id']);

	$Action_value = "Update";

	$Action_say  = "修改語言設置" ;//修改問題類別

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}languageset` where lid=".intval($id)." limit 0,1");

	$Num   = $DB->num_rows($Query);

	if ($Num>0){

		$Result= $DB->fetch_array($Query);

		$languagename    =  $Result['languagename'];

		$code            =  $Result['code'];

		$template        =  $Result['template'];

		$homenews_ncid   =  $Result['homenews_ncid'];

    $Title           =  $Result['title'];

    $Description     =  $Result['description'];

    $Keywords        =  $Result['keywords'];
	}else{

		echo "<script language=javascript>javascript:window.history.back();</script>";

		exit;

	}

}else{

	$Action_value = "Insert";

	$Action_say   = "新增語言設置"; ///添加問題類別

	$status  = 1;

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

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE>設置-->語言設置管理</TITLE>

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>

	function checkform(){



		if (chkblank(form1.code.value)){

			form1.code.focus();

			alert('請填寫編號');  //请选择問題類別名稱			

			return;

		}

	

		form1.submit();

	}

	function checkform1(){



		if (chkblank(form1.code.value)){

			form1.code.focus();

			alert('請填寫編號');  //请选择問題類別名稱			

			return;

		}

		form1.submit();

	}



</SCRIPT>



<div id="contain_out">

  <FORM name=form1 action='admin_language_save.php' method=post >

  <? include "Order_state.php";?>

  <input type="hidden" name="Action" value="<?php echo $Action_value?>">

  <input type="hidden" name="id" value="<?php echo $id?>">

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

                <TBODY>

                  <TR>

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>

                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set];//设置?>--&gt;語言設置管理</SPAN></TD>

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

                            <a href="admin_language_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

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

                            

                            </td>

                          

                          </TR></TBODY></TABLE>

                    

                    </TD></TR></TBODY></TABLE>

              </TD>

            </TR>

          </TBODY>

        </TABLE>

                      <TABLE class=allborder cellSpacing=0 cellPadding=2 width="100%" align=center bgColor=#f7f7f7 border=0>

                        <TBODY>

                          <TR>

                            <TD noWrap align=right width="18%">&nbsp;</TD>

                            <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>

                          

                          <TR>

                            <TD noWrap align=right width="18%">語言：</TD>

                            <TD height="25" colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','languagename',$languagename,"      maxLength=100 size=50 ")?></TD>

                            </TR>

                          

                          <TR>

                            <TD noWrap align=right>首頁新聞焦點：</TD>

                            <TD colspan="2" align=left noWrap><?php echo  $Char_class->get_page_select("homenews_ncid",$homenews_ncid,"  class=\"trans-input\" ");?></TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right>編號：</TD>

                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','code',$code,"      maxLength=100 size=50 ")?></TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right>模板：</TD>

                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','template',$template,"      maxLength=100 size=50 ")?></TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right>網站標題：</TD>

                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','title',$Title,"      maxLength=100 size=50 ")?></TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right>meta_description：</TD>

                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('textarea','description',$Description," cols=100 rows=4  ")?></TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right>meta_keywords：</TD>

                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('textarea','keywords',$Keywords," cols=100 rows=4  ")?></TD>

                          </TR>

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD colspan="2" align=right noWrap>&nbsp;</TD>

                          </TR>

                          </TBODY>

                  </TABLE>

  </FORM>

</div>

<div align="center"><?php include_once "botto.php";?></div>

</BODY>

</HTML>

