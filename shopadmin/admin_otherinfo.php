<?php
include "Check_Admin.php";


if ($_GET['info_id']!="" && $_GET['Action']=='Modi'){
	$Info_id = intval($_GET['info_id']);
	$Action_value = "Update";
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}admin_info` where info_id=".intval($Info_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$Info_content     =  $Result['info_content'];		
		$language     =  $Result['language'];
		$title     =  $Result['title'];
		$top_id     =  $Result['top_id'];
		$path     =  $Result['path'];
	}else{
		echo "<script language=javascript>javascript:window.close();</script>";
		exit;
	}

}else{
	$Action_value = "Insert";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[SYS_Info]?>--&gt;<?php echo $Title?></TITLE>

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
				if ($_GET['info_id']!="" && $_GET['Action']=='Modi'){
				?>
				autosave: 'admin_otherinfo_save.php?act=autosave&info_id=<?php echo $_GET['info_id'];?>',
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
		form1.action="admin_otherinfo_save.php";
		form1.submit();
    }
	function changecat(){
		form2.action="admin_otherinfo.php";
		//save();
		form2.submit();
	}
</SCRIPT>

<div id="contain_out">
  <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="46%">
              <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange> <?php echo $JsMenu[SYS_Info]?>--&gt;<?php echo $title?></SPAN></TD>
                    </TR>
                  </TBODY>
              </TABLE></TD>
            <TD align=center width="46%">&nbsp;</TD>
            <TD width="8%" align="right" >
              
              <TABLE>
                <TBODY>
                  <TR>
                    <TD vAlign=bottom noWrap class="link_buttom">
                      <a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>
                    </TR>
                  </TBODY>
                </TABLE>		     </TD>
            </TR>
          </TBODY>
        </TABLE>

              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=top bgColor=#ffffff height=300>
                      <FORM name=form1 action='admin_otherinfo_save.php' method=post >
                        <input type="hidden" name="Action" value="<?php echo $Action_value?>">
                        <input type="hidden" name="Info_id" value="<?php echo $Info_id?>">
                        <TABLE class=allborder cellSpacing=0 cellPadding=2
                  width="100%" align=center bgColor=#f7f7f7 border=0>
                          <TBODY>
                            <TR align="center">
                              <td width="15%" align="right"><br />
                              標題名稱：</td>
                              <td width="85%" align="left"><br />                                <?php echo $FUNCTIONS->Input_Box('text','title',$title,"      maxLength=100 size=50 ")?> </td>
                              </TR>
                               <TR align="center">
                              <td width="15%" height="31" align="right">路徑名稱：</td>
                              <td width="85%" align="left"><?php echo $FUNCTIONS->Input_Box('text','path',$path,"      maxLength=100 size=50 ")?>     填寫形式為字母,比如填寫About網址為http://www.smartshop.com.tw/About</td>
                              </TR>
                            <TR align="center">
                              <td height="32" align="right">語言版本：</td>
                              <TD align="left" valign="middle" noWrap>
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
                            <TR align="center">
                              <td height="32" align="right">對應資訊：</td>
                              <TD align="left" valign="middle" noWrap><select name="top_id">
                        <option value="">請選資訊</option>
                        <?php
                            $Sql_t      = "select * from `{$INFO[DBPrefix]}admin_info` where top_id=0 order by info_id ";
							$Query_t    = $DB->query($Sql_t);
							$Num_t      = $DB->num_rows($Query_t);
							while ($Rs_t=$DB->fetch_array($Query_t)) {
							?>
                        <option value="<?php echo $Rs_t['info_id'];?>" <?php if($Rs_t['info_id'] == $top_id) echo "selected";?>><?php echo $Rs_t['title'];?></option>
                        <?
							}
							?>
                        </select>
                              (繁體不用選擇，其他語言或版本必須選擇。)</TD>
                            </TR>
                            <TR align="center">
                              <td align="right">
                                內容：                    </td>
                              <TD align="left" valign="top"> 
                              <div  class="editorwidth">
							<textarea name="FCKeditor1" id="redactor" cols="30" rows="10" ><?php echo $Info_content;?></textarea>
                                </div>
                                <br></TD>
                              </TR>
                          </TBODY></TABLE>
                        
                      </FORM>
                    </TD></TR></TBODY></TABLE>

</div>
<div align="center"><?php include_once "botto.php";?></div>

</BODY>
</HTML>
