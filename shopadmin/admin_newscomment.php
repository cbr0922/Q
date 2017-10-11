<?php

include_once "Check_Admin.php";

include "../language/".$INFO['IS']."/Admin_Product_Pack.php";



$Comment_id  = $FUNCTIONS->Value_Manage($_GET['comment_id'],$_POST['comment_id'],'back','');

/**

 * 这里是当供应商进入的时候。只能修改自己产品的评论资料。

 */

if (intval($_SESSION[LOGINADMIN_TYPE])==2){

	$Provider_string = " and g.provider_id=".intval($_SESSION['sa_id'])." ";

}else{

	$Provider_string = "";

}

$Sql         = "select gc.* ,g.ntitle from `{$INFO[DBPrefix]}news_comment` gc  inner join `{$INFO[DBPrefix]}news` g on (gc.nid=g.news_id) where comment_id=".intval($Comment_id)."  ".$Provider_string." limit 0,1 ";

//$Query       = $DB->query("select * from good_comment where comment_id=".intval($Comment_id)." limit 0,1");

$Query       = $DB->query($Sql);

$Num         = $DB->num_rows($Query);



if ($Num>0){

	$Result    = $DB->fetch_array($Query);

	$GoodsName = $Result['ntitle'];

	$CoIdate   = $Result['comment_idate'];

	$CoContent = nl2br($Result['comment_content']);

	$CoAnswer  = $Result['comment_answer'];

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE>文章管理--&gt;文章評論</TITLE></HEAD>

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

				if ($_GET['comment_id']!="" ){

				?>

				autosave: 'admin_newscomment_save.php?act=autosave&comment_id=<?php echo $_GET['comment_id'];?>',

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

<SCRIPT language=javascript>

	function checkform(){

		document.form1.action="admin_newscomment_save.php";

		document.form1.submit();

	}	

</SCRIPT>

<div id="contain_out"><?php  include_once "Order_state.php";?>

  <FORM name=form1 action='' method=post  >

  <input type="hidden" name="Action" value="Update">

  <INPUT type=hidden  name='comment_id' value="<?php echo $Comment_id?>"> 

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE cellSpacing=0 cellPadding=0 border=0>

                <TBODY>

                  <TR>

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>

                    <TD class=p12black><SPAN  class=p9orange>文章管理--&gt;回覆評論</SPAN></TD>

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

                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_comment_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"   border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

                    <TD align=middle>

                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>

                        <TBODY>

                          <TR>

                            <TD align=middle width=79><!--BUTTON_BEGIN-->

                              <TABLE><TBODY>

                                <TR>

                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

                    </TR>  

                  </TBODY>

                </TABLE>

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

                      <TABLE class=allborder cellSpacing=0 cellPadding=2 

                  width="100%" bgColor=#f7f7f7 border=0>

                        <TBODY>

                          <TR>

                            <TD noWrap align=right width="12%">&nbsp;</TD>

                            <TD>&nbsp;</TD></TR>

                          <TR>

                            <TD noWrap align=right> <?php echo $Admin_Product[ProductName];//商品名称?>：</TD>

                            <TD><?php echo  $GoodsName?> </TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right width="12%"> <?php echo $Admin_Product[Comment_Time];//评论时间?>：</TD>

                            <TD><?php echo date("Y-m-d H: i a ",$CoIdate)?>

                              </TD></TR>

                          <TR>

                            <TD noWrap align=right width="12%"> <?php echo $Admin_Product[Comment_User]; //评论内容?>：</TD>

                            <TD><?php echo $CoContent;?></TD></TR>

                          <TR>

                            <TD align=right valign="top" noWrap><?php echo $Admin_Product[Comment_System]?>：</TD>

                            <TD>&nbsp;</TD>

                            </TR>

                          <TR>

                            <TD align=right valign="top" noWrap>&nbsp;</TD>

                            <TD align=left valign="top" noWrap>
							<div class="editorwidth">
							<textarea name="FCKeditor1" id="redactor" cols="30" rows="10" ><?php echo $CoAnswer;?></textarea>
                            </div>

							</TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right width="12%">&nbsp;</TD>

                            <TD>&nbsp; 

            </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>

</FORM>

</div>

 <div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>

