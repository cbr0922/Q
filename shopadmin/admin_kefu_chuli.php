<?php
include_once "Check_Admin.php";

/**

 *  装载客户服务语言包

 */

include "../language/".$INFO['IS']."/KeFu_Pack.php";



if ($_GET['k_chuli_id']!="" && $_GET['Action']=='Modi'){

	$k_chuli_id = intval($_GET['k_chuli_id']);

	$Action_value = "Update";

	$Action_say  = $KeFu_Pack['Back_Nav_title'].$KeFu_Pack['Back_ModiChuli']; //修改處理情況

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}kefu_chuli` where k_chuli_id=".intval($k_chuli_id)." limit 0,1");

	$Num   = $DB->num_rows($Query);



	if ($Num>0){

		$Result= $DB->fetch_array($Query);

		$k_chuli_name            =  $Result['k_chuli_name'];

		$status                 =  $Result['status'];

		$checked                =  $Result['checked'];

		$ifclose                =  $Result['ifclose'];



	}else{

		echo "<script language=javascript>javascript:window.history.back();</script>";

		exit;

	}



}else{

	$Action_value = "Insert";

	$Action_say   = $KeFu_Pack['Back_Nav_title'].$KeFu_Pack['Back_AddChuli']; //添加處理情況

	$status  = 1;

}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $Action_say?></TITLE></HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<? //include_once "Order_state.php";?>

<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>

	function checkform(){



		if (chkblank(form1.k_chuli_name.value)){

			form1.k_chuli_name.focus();

			alert('<?php echo $KeFu_Pack['Js_input_chuliname'];?>');  //请输入處理情況名稱			

			return;

		}

	

		form1.submit();

	}

		function checkform1(){



		if (chkblank(form1.k_chuli_name.value)){

			form1.k_chuli_name.focus();

			alert('<?php echo $KeFu_Pack['Js_input_chuliname'];?>');  //请输入處理情況名稱			

			return;

		}

	    document.form1.ifgo_on.value=1;

		form1.submit();

	}



</SCRIPT>

<div id="contain_out">

<?php  include_once "Order_state.php";?>

<FORM name=form1 action='admin_kefu_chuli_save.php' method=post >

  <input type="hidden" name="Action" value="<?php echo $Action_value?>">

  <input type="hidden" name="k_chuli_id" value="<?php echo $k_chuli_id?>">

  

  <?php

  if ($_GET['type']) {

  	$type = str_replace('+','',$_GET['type']);

  	$offset = intval($_GET['offset']);

  }else {

  	$type = $_POST['type'];

  	$offset = intval($_POST['offset']);

  }

  ?>

  <input type="hidden" name="type" value="<?php echo $type?>">

  <input type="hidden" name="offset" value="<?php echo $offset?>">

  <input type="hidden" name="ifgo_on" value="0">

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

                <TBODY>

                  <TR>

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 

                  width=32></TD>

                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $Action_say?></SPAN></TD>

                  </TR></TBODY></TABLE></TD>

            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>

              <TBODY>

                <TR>

                  <TD align=middle>

                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>

                      <TBODY>

                        <TR>

                          <TD align=middle width=79><!--BUTTON_BEGIN-->

                            <TABLE >

                              <TBODY>

                                <TR>

                                  <TD vAlign=bottom noWrap class="link_buttom">

                            <a href=<?php echo (isset($_GET['type'])||isset($_POST['type']))?'admin_kefu_chuli_list.php?type='.$type.'&offset='.$offset:"admin_kefu_chuli_list.php"?>><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

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

                    

                    </TD>

                  <TD align=middle>

                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>

                      <TBODY>

                        <TR>

                          <TD align=middle width=79><!--BUTTON_BEGIN-->

                            <TABLE>

                              <TBODY>

                                <TR>

                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform1();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save-add.gif" border=0 >&nbsp;<?php echo $KeFu_Pack['Back_SaveGoAdd'];//保存后繼續新增?></a></TD>

                                  </TR></TBODY></TABLE><!--BUTTON_END-->

                            

                            </TD></TR></TBODY></TABLE>

                    

                    </TD></TR></TBODY></TABLE>

              </TD>

            </TR>

          </TBODY>

        </TABLE><TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>

                        <TBODY>

                          <TR>

                            <TD noWrap align=right width="18%">&nbsp;</TD>

                            <TD colspan="2" align=right noWrap>&nbsp;</TD></TR>

                          

                          <TR>

                            <TD noWrap align=right width="18%"><?php echo $KeFu_Pack['Back_ChuliName'];//處理情況名稱?>：</TD>

                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','k_chuli_name',$k_chuli_name,"      maxLength=50 size=50 ")?></TD>

                            </TR>

                          

                          <TR>

                            <TD align=right ><?php echo $Basic_Command['Iffb'];//是否发布?>：</TD>

                            <TD colspan="2"><?php echo $FUNCTIONS->Input_Box('radio','status',$status,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right width="18%"><?php echo $KeFu_Pack['Back_IfNeiDing'];//是否内定?>：</TD>

                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','checked',$checked,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right width="18%"><?php echo $Basic_Command['IfCloseOrOpen'];//是否關閉?>：</TD>

                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio','ifclose',$ifclose,$Add=array($Basic_Command['Yes'],$Basic_Command['No']))?></TD>

                            </TR>

                          

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD colspan="2" align=right noWrap>&nbsp;</TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD colspan="2" align=right noWrap>&nbsp;</TD>

                            </TR>

                </TBODY></TABLE>



                     </FORM>

</div>

<div align="center"><?php include_once "botto.php";?></div>

</BODY>

</HTML>

