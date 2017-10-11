<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Product_Ex_Pack.php";
if ($_GET['group_id']!="" && $_GET['Action']=='Modi'){
	$group_id = intval($_GET['group_id']);
	$Action_value = "Update";
	$Action_say  = "修改地區運費"; //修改商品類別
	$Sql = "select tg.* from `{$INFO[DBPrefix]}transgroup` as tg where tg.group_id=".intval($group_id)." limit 0,1";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$group_id  =  $Result['group_id'];
		$trans_id  =  $Result['trans_id'];
		$groupname  =  $Result['groupname'];
		$money  =  $Result['money'];
		$content  =  $Result['content'];
		$permoney  =  $Result['permoney'];
		$mianyunfei  =  $Result['mianyunfei'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
	$Action_say   = "新增地區運費" ; //插入
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>地區運費管理--&gt;<?php echo $Action_say;?></title>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>
<SCRIPT language=javascript>
	function checkform(){
		//form1.action="admin_pcat_act.php?action=add";
		form1.submit();
	}
	
	function changecat(bid){
		//form1.action="admin_pcat.php?Action=Modi&bid="+bid;
		//form1.action="admin_pcat.php?Action=Insert&bid="+bid;		
		//form1.submit();
		location.href="admin_areagroup_save.php?Action=Insert&group_id="+bid;
	}
</SCRIPT>
<div id="contain_out">
<?php  include_once "Order_state.php";?>
  <FORM name=form1 action='admin_areagroup_save.php' method=post>
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
  <input type="hidden" name="group_id" value="<?php echo $group_id?>">
  <TBODY>
  <TR>    
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Set]?>--&gt;<?php echo $JsMenu[Sys_Set]?>--&gt;地區運費管理--&gt;<?php echo $Action_say;?></SPAN></TD>
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
                            <a href="admin_areagroup_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
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
                            <TD noWrap align=right width="18%"> 地區運費組名：</TD>
                            <TD noWrap align=left>
                              <?php echo $FUNCTIONS->Input_Box('text','groupname',$groupname,"  maxLength=50 size=40 ")?>				  </TD>
                            </TR>
                          
                          
                          <TR>
                            <TD noWrap align=right width="18%">貨運方式： </TD>
                            <TD noWrap align=left>
                              <select name="trans_id">
                                <?php
					  $Query_class = $DB->query("select * from `{$INFO[DBPrefix]}transportation` ");
					  while ($Result_class = $DB->fetch_array($Query_class)){
					  ?>
                                <option value="<?php echo $Result_class['transport_id'];?>" <?php if ($Result_class['transport_id']==$trans_id) echo "selected";?>><?php echo $Result_class['transport_name'];?></option>
                                <?php
					  }
					  ?>
                                </select>
                              </TD></TR>
                          <TR>
                            <TD noWrap align=right>運達地區：</TD>
                            <TD align=left>
                              <?php
					  $tag_goods = array();
					  $tag_sql = "select * from `{$INFO[DBPrefix]}areatrans` where group_id='" . intval($group_id) . "'";
						  $Query_tag= $DB->query($tag_sql);
						  $ig = 0;
						  while($Rs_tag=$DB->fetch_array($Query_tag)){
							$tag_goods[$ig]=$Rs_tag['area_id'];
							$ig++;
						  }
					  $Sql_tag      = "select * from `{$INFO[DBPrefix]}area` where top_id=0";
					  $Query_tags    = $DB->query($Sql_tag);
					   while($Rs_tags=$DB->fetch_array($Query_tags)){
					   ?>
                              <input type="checkbox" name="area_id[]" id="area_id" value="<?php echo $Rs_tags['area_id'];?>" <?php if (in_array($Rs_tags['area_id'],$tag_goods))  echo "checked";?>><?php echo $Rs_tags['areaname']?>
                              <?php
                       $Sql_tag1      = "select * from `{$INFO[DBPrefix]}area` where top_id='" . $Rs_tags['area_id'] . "'";
					  $Query_tags1    = $DB->query($Sql_tag1);
					   while($Rs_tags1=$DB->fetch_array($Query_tags1)){
						   ?>
                              <input type="checkbox" name="area_id[]" id="area_id" value="<?php echo $Rs_tags1['area_id'];?>" <?php if (in_array($Rs_tags1['area_id'],$tag_goods))  echo "checked";?>><?php echo $Rs_tags1['areaname']?>
                              <?php
					   }
					   ?>
                              <br>
                              <?php
					   }
					  ?>
                              </TD>
                            </TR>
                          
                            <TR>
                      <TD align=right noWrap>運費：</TD>
                      <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','money',$money,"      maxLength=10 size=10 ")?>,當購買<?php echo $FUNCTIONS->Input_Box('text','mianyunfei',$mianyunfei,"      maxLength=10 size=10 ")?>滿免運費</TD>
                    </TR>
                          <TR>
                            <TD align=right noWrap>續重（每公斤）：</TD>
                            <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','permoney',$permoney,"      maxLength=10 size=10 ")?></TD>
                            </TR>
                          <TR>
                            <TD align=right valign="top" noWrap>說明：</TD>
                            <TD noWrap align=left><?php echo $FUNCTIONS->Input_Box('textarea','content',$content," cols=80 rows=6  ")?>
                              <p><br>
                              </p></TD>
                            </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
    </TR>
    </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
