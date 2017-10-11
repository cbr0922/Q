<?php
include "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Core.php";

include_once "pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

if ($_POST['act'] == "insert") {
	$db_string = $DB->compile_db_insert_string(array(
	'type' => $_POST['sel_core_type'],
	'account' => $_POST['txt_core_accout'],
	));
	$Sql="INSERT INTO `{$INFO[DBPrefix]}offhand` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
	$Result_Insert=$DB->query($Sql);

	if ($Result_Insert)
	{
		$FUNCTIONS->setLog("新增即時通信");
		$FUNCTIONS->header_location('admin_core.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}	
}

if ($_POST['act']=='del' ) {

	$Array_ofid =  $_POST['cid'];
	$Num_ofid  = count($Array_ofid);
	for ($i=0;$i<$Num_ofid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}offhand`  where of_id=".intval($Array_ofid[$i]));
		
	}
	$FUNCTIONS->setLog("刪除即時通信");
	$FUNCTIONS->header_location('admin_core.php');

}

if ($_POST['act'] == 'update') {
	$db_string = $DB->compile_db_update_string(array(
	'type' => $_POST['sel_update_type'],
	'account' => $_POST['txt_update_accout'],
	));
	$Sql = "update `{$INFO[DBPrefix]}offhand` SET $db_string WHERE of_id=".intval($_POST['of_id']);
	$Result_Update = $DB->query($Sql);
	if ($Result_Update)
	{
		$FUNCTIONS->setLog("編輯即時通信");
		$FUNCTIONS->header_location('admin_core.php');
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}	
}

$Sql = "select * from `{$INFO[DBPrefix]}offhand` order by type";
$Query = $DB->query($Sql);
$Num = $DB->num_rows($Query);
if ($Num>0) {
	$limit = 10;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $JsMenu[Set];//设置?>--&gt;<?php echo $JsMenu[core];//即时通信整合?></title>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<script language="javascript">
function checkInsertForm(){
	if(chkblank(document.getElementById('txt_core_accout').value)||document.getElementById('txt_core_accout').value.length == 0){
		alert('<?php echo $Core[pleaseInputAccount];//请输入帐号 ?>');
		document.getElementById('txt_core_accout').focus();
		return false;
	}
}

function checkUpdateForm(){
	if(chkblank(document.getElementById('txt_update_accout').value)||document.getElementById('txt_update_accout').value.length == 0){
		alert('<?php echo $Core[pleaseInputAccount];//请输入帐号 ?>');
		document.getElementById('txt_update_accout').focus();
		return false;
	}
}
function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_core.php";
			document.adminForm.act.value="del";
			document.adminForm.submit();
		}
	}
}
function updateCore(id,type,account){
	document.getElementById('of_id').value = id;
	var sel = document.getElementById('sel_update_type');
	for(i=0;i<sel.length;i++){
		if(sel.options[i].value == type){
			sel.options[i].selected = true;
			break;
		}
	}
	document.getElementById('txt_update_accout').value = account;
	document.getElementById('core_update_form').style.display="";
}
</script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<?php include $Js_Top ; ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
 <TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center   border=0>
 <TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
	<?php  include_once "desktop_title.php";?></TD>
  </TR>
  </TBODY>
 </TABLE>
       <?php  include_once "Order_state.php";?>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD>
  </TR>
  </TBODY>
</TABLE>


<table cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
	<tbody>
		<tr>
    		<td width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></td>
   			<td width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></td>
   			<td width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></td>
		</tr>
		<tr>
			<td width="1%" background="images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;"" height=302></td>
			<td vAlign=top width="100%" height=302>
				<table class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        			<tbody>
        				<tr>
       					  <td width="100%">
            					<table width="100%" border=0 cellPadding=0 cellSpacing=0>
              						<tbody>
              							<tr>
               								<td width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></td>
                							<td width="354" noWrap class=p12black><SPAN class=p9orange><?php echo $JsMenu[Set];//设置?>--&gt;<?php echo $JsMenu[core];//即时通信整合?></SPAN></td>
											<td width="440" align="right"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></td>
              							</tr>
									</tbody>
									
								</table>
							</td>
       					</tr>
		  			</tbody>
				</table>
				<table width="100%">
					<tr>
						<td width="50%" align="center" valign="top">
							<form action="admin_core.php" name="adminForm1" method="POST" onSubmit="return checkInsertForm();">
								<input type="hidden" name="act" value="insert"/>
								<table class=listtable cellSpacing=0 cellPadding=0  width="60%" border=0>
									<tbody>
										<tr>
											<td colspan="2"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>&nbsp;&nbsp;&nbsp;<?php echo $Core[add_core]//添加即时通信?> </td>	
										</tr>
										<tr>
											<td align="right"><?php echo $Core[core_type]//类型?>：</td>
											<td>
												<select name="sel_core_type">
													<option value="1"><?php echo $Core[core_type_qq]//QQ ?></option>
													<option value="2"><?php echo $Core[core_type_msn]//MSN ?></option>
													<option value="3"><?php echo $Core[core_type_skype]//Skype ?></option>
												</select>
											</td>
										</tr>
										<tr>
											<td align="right"><?php echo $Core[core_account]//帐户?>：</td>
											<td>
												<input type="text" name="txt_core_accout" id="txt_core_accout"/>
											</td>
										</tr>
										<tr>
											<td colspan="2" align="center"><input type="submit" value="<?php echo $Core[core_save];//保存 ?>"/></td>
										</tr>
									</tbody>
								</table>
							</form>
							<br>
							<div id="core_update_form" style="display:none">
								<form action="admin_core.php" method="POST" onSubmit="return checkUpdateForm();">
									<input type="hidden" name="act" value="update"/>
									<input type="hidden" name="of_id" id="of_id">
									<table class=listtable cellSpacing=0 cellPadding=0  width="60%" border=0>
										<tbody>
											<tr>
												<td colspan="2"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>&nbsp;&nbsp;&nbsp;<?php echo $Core[update_core]//修改即时通信?> </td>	
											</tr>
											<tr>
												<td align="right"><?php echo $Core[core_type]//类型?>：</td>
												<td>
													<select name="sel_update_type" id="sel_update_type">
														<option value="1"><?php echo $Core[core_type_qq]//QQ ?></option>
														<option value="2"><?php echo $Core[core_type_msn]//MSN ?></option>
														<option value="3"><?php echo $Core[core_type_skype]//Skype ?></option>
													</select>
												</td>
											</tr>
											<tr>
												<td align="right"><?php echo $Core[core_account]//帐户?>：</td>
												<td>
													<input type="text" name="txt_update_accout" id="txt_update_accout"/>
												</td>
											</tr>
											<tr>
												<td colspan="2" align="center"><input type="submit" value="<?php echo $Core[core_update];//修改 ?>"/></td>
											</tr>
										</tbody>
									</table>
								</form>
							</div>
						</td>
						<td width="50%" align="center" valign="top">
							<form name="adminForm" action="" method="POST">
							<INPUT type=hidden value=0  name=boxchecked>
							<INPUT type=hidden name=act>
							<table class=listtable cellSpacing=0 cellPadding=0  width="90%" border=0>
								<tbody>
									<tr align=middle>
										<td width="3%" height=26 align=left noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
					  <INPUT onclick=checkAll('<?php echo intval($Nums)?>'); type=checkbox value=checkbox   name=toggle></td>
										<td  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Core[core_type]//类型?></td>
										<td  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Core[core_account]//帐户?></td>
										<td  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Core[core_operate]//操作?></td>
									</tr>
									<?php 
										if ($Num >0) {
										$i = 0;
										while ($Rs = $DB->fetch_array($Query)) {
											if ($Rs['type']==1) {
												$type = $Core[core_type_qq];
											}
											if ($Rs['type']==2) {
												$type = $Core[core_type_msn];
											}
											if ($Rs['type']==3) {
												$type = $Core[core_type_skype];
											}
									?>
									<tr>
										<td align="center"><INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['of_id']?>' name=cid[]> </td>
										<td align="center"><?php echo $type ?></td>
										<td align="center"><?php echo $Rs['account'] ?></td>
										<td align="center"><a href="#" onClick="updateCore('<?php echo $Rs['of_id']?>','<?php echo $Rs['type'] ?>','<?php echo $Rs['account'] ?>');"><?php echo $Core[core_update];//修改 ?></a></td>
									</tr>
									<?php
											$i++;
										}
									?>
									<tr>
									
									</tr>
									<tr>
						                <td vAlign=center colspan="4" align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
										<?php echo $Nav->pagenav()?>
										</td>
						            </tr>
						            <?php } ?>
								</tbody>
							</table>
							</form>
						</td>
					</tr>
				</table>
				</td>
            	<td width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=302>&nbsp;</td>
			</tr>
			<tr>
    			<td width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></td>
    			<td width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></td>
    			<td width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></td>
			</tr>
		</tbody>		
</table>

<div align="center">
<?php include_once "botto.php";?></div>
</BODY>
</HTML>
