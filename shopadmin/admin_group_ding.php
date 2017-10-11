<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
if ($_GET['group_id']!="" && $_GET['Action']=='Modi'){
	$Group_id        = intval($FUNCTIONS->Value_Manage($_GET['group_id'],$_POST['group_id'],'back',''));

	$Query = $DB->query("select mgroup_name from `{$INFO[DBPrefix]}mail_group` where mgroup_id=".intval($Group_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$mgroup_name = $Result['mgroup_name'];
	}

	$Query = $DB->query("select user_id,email from `{$INFO[DBPrefix]}mail_group_list` where group_id=".intval($Group_id)." order by user_id desc");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		while ($Result = $DB->fetch_array($Query)){
			if (trim($Result['email'])!=""){
				$Mgroup_Array_temp[] = trim($Result['email']);
			}
		}
		$Mgroup_Array_list = array_filter($Mgroup_Array_temp);
		$Mgroup_Array_list = array_unique($Mgroup_Array_list);
	}

	$Query = $DB->query("select u.user_id,u.email from `{$INFO[DBPrefix]}user` u order by u.user_id desc");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		while($Result= $DB->fetch_array($Query)){
			$User_Mail_temp[] = $Result['email'];
		}
	}
	$User_Mail_Array = array_filter($User_Mail_temp);
	$User_Mail_Array = array_unique($User_Mail_Array);

	unset ($Query);
	unset ($Num);
	unset ($Result);
	$Action_value = "Update";
	$Action_say  = $Mail_Pack[ModiEmailGroup];
}else{
	$Action_value = "Insert";
	$Action_say   = $Mail_Pack[AddEmailGroup];
	$Sql  = "select u.user_id,u.email from `{$INFO[DBPrefix]}user` u order by u.user_id desc";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);
	if ($Num>0){
		while ($Result = $DB->fetch_array($Query)){
			$User_Mail_temp[] = trim($Result['email']);
		}
	}
	$User_Mail_Array = array_filter($User_Mail_temp);
	$User_Mail_Array = array_unique($User_Mail_Array);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Action_say?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  <?php echo  $Onload ?> >
<SCRIPT language=JavaScript>
<!-- Begin -->
function addone(fbox,tbox,email) {
	if(email.value == ''){
		alert("未輸入新增的Email");
		document.formGround.email1.focus();
		return;
	}
	var i=0,j=0;
	var no = new Option();
	no.value = email.value;
	no.text = email.value;

	for(var x=0; x<fbox.options.length; x++){
		if(fbox.options[x].value == no.value){
			i = 1;
			break;
		}
	}
	if(i == 0){
		alert("無Email可新增");
		return;
	}

	for(var x=0; x<tbox.options.length; x++){
		if(tbox.options[x].value == no.value){
			j = 1;
			alert("Email已新增");
			break;
		}
	}
	if(j == 0){
		tbox.options[tbox.options.length] = no;
	}
}
function add(fbox,tbox,all) {
	var j;
	if(all==1){
		del(tbox,fbox,1);
	}
	for(var i=0; i<fbox.options.length; i++) {
		j=0;
		if(fbox.options[i].selected && fbox.options[i].value != "" || all==1) {
			var no = new Option();
			no.value = fbox.options[i].value;
			no.text = fbox.options[i].text;

			for(var x=0; x<tbox.options.length; x++){
				if(tbox.options[x].value == no.value){
					j = 1;
					break;
   			}
			}
			if(j == 0){
				tbox.options[tbox.options.length] = no;
			}
		}
	}
}

function delone(fbox,email) {
	if(email.value == ''){
		alert("未輸入刪除的Email");
		document.formGround.email2.focus();
		return;
	}
	var j=0;
	for(var i=0; i<fbox.options.length; i++) {
		if(fbox.options[i].value == email.value && fbox.options[i].value != "") {
			fbox.options[i].value = "";
			fbox.options[i].text = "";
			j=1;
			break;
		}
	}
	if(j == 0){
		alert("無Email可刪除");
	}else {
		BumpUp(fbox);
	}
}
function del(fbox,tbox,all) {
	for(var i=0; i<fbox.options.length; i++) {
		if(fbox.options[i].selected && fbox.options[i].value != "" || all==1) {
			fbox.options[i].value = "";
			fbox.options[i].text = "";
		}
	}
	BumpUp(fbox);
}

function BumpUp(box)  {
	for(var i=0; i<box.options.length; i++) {
		if(box.options[i].value == "")  {
			for(var j=i; j<box.options.length-1; j++)  {
				box.options[j].value = box.options[j+1].value;
				box.options[j].text = box.options[j+1].text;
			}
			var ln = i;
			break;
		}
	}
	if(ln < box.options.length)  {
	box.options.length -= 1;
	BumpUp(box);
	}
}

function dosubmit(outbox){
	if (formGround.name.value == "")
	{
		alert('<?php echo $Mail_Pack[PleaseInputEmailGroupName];?>'); //"请输入邮件组名称！"
		document.formGround.name.focus();
		return ;
	}
	formGround.outcus.value = "";
	for(var i=0; i<outbox.options.length; i++) {
		var no = new Option();
		no.value = outbox.options[i].value;
		no.text = outbox.options[i].text;
		if(i>0){
			formGround.outcus.value = formGround.outcus.value+","+no.value;
		}else{
			formGround.outcus.value = no.value;
		}
	}
	formGround.submit();
}
<!--// End -->
</SCRIPT>
<?php include_once "head.php";?>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>
  <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
    <TBODY>
      <TR>
        <TD width="50%">
          <TABLE cellSpacing=0 cellPadding=0 border=0>
            <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                <TD width="271" noWrap class=p12black><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[Shop_Pager];//网店会刊?>--&gt;<?php echo $Action_say?>
                  </SPAN></TD>
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
                              <TD vAlign=bottom noWrap>
                                <a href="admin_group_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
              <TD align=middle>
                <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                  <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                            <TR>
                              <TD vAlign=bottom noWrap ><a href="javascript:dosubmit(formGround.list2);"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->

                        </TD></TR></TBODY></TABLE>

                </TD></TR></TBODY></TABLE></TD>
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



                  <FORM name='formGround' action='admin_group_ding_save.php' method='post' >
                    <input type="hidden" name="Action" value="<?php echo $Action_value?>">
                    <input type="hidden" name="Group_id" value="<?php echo $Group_id?>">
                    <input type="hidden" name="Auto" value="2">
                    <INPUT type=hidden  name=outcus>
                    <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" bgColor=#f7f7f7 border=0>
                      <TBODY>
                        <TR>
                          <TD noWrap align=right width="78" height=28><?php echo $Mail_Pack[EmailGroupName];//组名称?>：</TD>
                          <TD height=28><INPUT value='<?php echo $mgroup_name?>' name='name' ID='name'>
                            <div id="nametips" class="tips"><?php echo $Mail_Pack[WhatIsNewEmailGroup]?></div>
                          </TD>
                          <TD height=28>&nbsp;</TD>
                          <TD height=28>&nbsp;</TD>
                        </TR>
                        <TR>
                          <TD noWrap align=right width="78"><?php echo $Mail_Pack[EmailList];//邮件列表?></TD>
                          <TD align=middle height=240>
                            <FIELDSET>
                              <LEGEND class=table_label  align=top><?php echo $Mail_Pack[CanSelectEmail];//可选邮件?></LEGEND>
                              <TABLE cellSpacing=0 cellPadding=0 border=0>
                                <TBODY>
                                  <TR class=InputFrameLine>
                                    <TD height=240>
                                      <SELECT style="WIDTH: 300px"   multiple size=16 name='list1' >
																				<?php foreach($User_Mail_Array as $k => $v ){ ?>
                                        <OPTION  value='<?php echo $v;?>'><?php echo $v;?></OPTION><br>
                                        <? } ?>
                                      </SELECT>
																		</TD>
                                  </TR>
                                </TBODY>
                              </TABLE>
                            </FIELDSET></TD>
                          <TD align=center>
														<button value="true"  type="button" name="B0" id="valuesubmit" onclick=add(this.form.list1,this.form.list2,1) class="submit">全<?php echo $Basic_Command['Add'];?> >>> </button>
														<BR>
                            <BR>
                            <button value="true"  type="button" name="B1" id="valuesubmit" onclick=add(this.form.list1,this.form.list2,0) class="submit"><?php echo $Basic_Command['Add'];?> >>> </button>
                            <!--INPUT name=B1 type=button  onclick=add(this.form.list1,this.form.list2) value=" <?php echo $Basic_Command['Add'];?> >>> "-->
                            <BR>
                            <BR>
                            <button value="true"  type="button" name="B2" id="valuesubmit" onclick=del(this.form.list2,this.form.list1,0) class="submit"> <<< <?php echo $Basic_Command['Del'];?></button>
                            <!--INPUT name=B2 type=button onclick=del(this.form.list2,this.form.list1) value=" <<< <?php echo $Basic_Command['Del'];?> "-->
														<BR>
                            <BR>
														<button value="true"  type="button" name="B3" id="valuesubmit" onclick=del(this.form.list2,this.form.list1,1) class="submit"> <<< 全<?php echo $Basic_Command['Del'];?></button>
													</TD>
                          <TD align=middle height=240>
                            <FIELDSET>
                              <LEGEND class=table_label  align=top><?php echo $Mail_Pack[SelectedEmail];//已选邮件?></LEGEND>
                              <TABLE cellSpacing=0 cellPadding=0 border=0>
                                <TBODY>
                                  <TR class=InputFrameLine>
                                    <TD height=240>
                                      <SELECT style="WIDTH: 300px"  multiple size=16 name=list2>?>
																				<?php foreach($Mgroup_Array_list as $k => $v ){ ?>
																				<OPTION  value='<?php echo $v;?>'><?php echo $v;?></OPTION>
																				<?php } ?>
                                      </SELECT>
                                    </TD>
                                  </TR>
                                </TBODY>
                              </TABLE>
                            </FIELDSET></TD>
                        </TR>
                        <TR>
                          <TD noWrap align=right width="100">自行輸入會員mail</TD>
                          <TD align=middle height=50><INPUT name='email1' style="WIDTH: 300px"></TD>
                          <TD align=center><button value="true"  type="button" name="B4" id="valuesubmit" onclick=addone(this.form.list1,this.form.list2,this.form.email1) class="submit"><?php echo $Basic_Command['Add'];?> >>> </button>
													<!--INPUT name=B1 type=button  onclick=add(this.form.list1,this.form.list2) value=" <?php echo $Basic_Command['Add'];?> >>> "-->
													<BR>
													<BR>
													<button value="true"  type="button" name="B5" id="valuesubmit" onclick=delone(this.form.list2,this.form.email2) class="submit"> <<< <?php echo $Basic_Command['Del'];?></button>
													<!--INPUT name=B2 type=button onclick=del(this.form.list2,this.form.list1) value=" <<< <?php echo $Basic_Command['Del'];?> "-->
													</TD>
                          <TD align=middle height=50><INPUT name='email2' style="WIDTH: 300px"></TD>
                        </TR>
												<TR>
													<TD noWrap align=right width="78">&nbsp;</TD>
													<TD>&nbsp; </TD>
													<TD>&nbsp;</TD>
													<TD>&nbsp;</TD>
												</TR>
                      </TBODY>
                    </TABLE>

                  </FORM>


</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></div>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
