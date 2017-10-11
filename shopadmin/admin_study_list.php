<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
include "Productclass_show.php";

if ($_POST['act']=='Del' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("delete from `{$INFO[DBPrefix]}study` where id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->header_location("admin_study_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}
if ($_POST['act']=='Checked' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("update  `{$INFO[DBPrefix]}study` set checked=1 where id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->header_location("admin_study_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}
if ($_POST['act']=='noChecked' ) {

	$Array_cid =  $_POST['cid'];
	$Num_cid  = count($Array_cid);

	for ($i=0;$i<$Num_cid;$i++){
		$Result =  $DB->query("update  `{$INFO[DBPrefix]}study` set checked=0 where id=".intval($Array_cid[$i]));
	}


	if ($Result)
	{
		$FUNCTIONS->header_location("admin_study_list.php");
	}else{
		$FUNCTIONS->sorry_back('back',$Basic_Command['Back_System_Error']);
	}

}

/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

$objClass = "9pv";
$Where    = '';
$Nav      = new buildNav($DB,$objClass);


if (isset($_GET['where'])) {
	$Where = urldecode($_GET['where']);
	$Where = str_replace('wodedanyinhao',"'",$Where);

}else{
	$Where    = (trim($_GET['skey'])!="") ?  " where s.title like '%".urldecode(trim($_GET['skey']))."%'" : "" ;
}

$Sql      = "select s.*,st.name as stname from `{$INFO[DBPrefix]}study` as s left join  `{$INFO[DBPrefix]}studytype` as st on s.type=st.id ".$Where." order by id  ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query    = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<HEAD><TITLE></TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD>
  </TR>
  </TBODY>
</TABLE>
 <TABLE height=24 cellSpacing=0 cellPadding=2 width="98%" align=center   border=0>
 <TBODY>
  
  </TBODY>
 </TABLE>
<SCRIPT language=javascript>

function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&k_type_id="+catid;
	}
	
	if (checkvalue!=false){
		
		document.adminForm.action = "admin_studytype.php?Action=Modi&id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}
function toNew(id,catid){
	    document.adminForm.action = "admin_studytype.php";
		document.adminForm.submit();
	
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){   //您是否确认删除选定的记录
			document.adminForm.action = "admin_study_list.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}

function tocheck(state){
	if (state == 0){
		say = "您是否確認取消審核選定的記錄";
		document.adminForm.act.value="noChecked";
	}else{
		say = "您是否確認通過審核選定的記錄";
		document.adminForm.act.value="Checked";
	}
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm(say)){   //您是否确认删除选定的记录
			document.adminForm.action = "admin_study_list.php";
			document.adminForm.submit();
		}
	}
}


</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=302></TD>
    <TD vAlign=top width="100%" height=302>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;教學資料-->教學列表</SPAN>
				</TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%">
       <?php if ($Ie_Type != "mozilla") { ?>
			  <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN--><!--BUTTON_END-->
                        <TABLE class=fbottonnew link="javascript:tocheck(1);">
                          <TBODY>
                            <TR>
                              <TD vAlign=bottom noWrap><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;通過審核&nbsp; </TD>
                            </TR>
                          </TBODY>
                        </TABLE></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="javascript:tocheck(0);">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;取消審核&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="javascript:toDel();">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->
					  </TD>
					  </TR>
					 </TBODY>
					</TABLE>
				  </TD>                  
					</TR>
				 </TBODY>
				</TABLE>
		<?php } else {?> 
			  <TABLE cellSpacing=0 cellPadding=0 border=0>
              <TBODY>
              <TR>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN--><!--BUTTON_END-->
                        <TABLE>
                          <TBODY>
                            <TR>
                              <TD vAlign=bottom noWrap><a href="javascript:tocheck(1);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;通過審核</a>&nbsp; </TD>
                            </TR>
                          </TBODY>
                        </TABLE></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:tocheck(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;取消審核</a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END-->
					  </TD>
					  </TR>
					 </TBODY>
					</TABLE>
				  </TD>                  
					</TR>
				 </TBODY>
				</TABLE>
       <?php } ?>			
			</TD>
		  </TR>
		  </TBODY>
		</TABLE>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
        <TR>
          <TD vAlign=top height=210>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD bgColor=#ffffff>
                  <TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                    <FORM name=adminForm action="" method=post>
					<INPUT type=hidden name=act>
					 <INPUT type=hidden value=0  name=boxchecked> 
					 
                    <TBODY>
                    <TR align=middle>
                      <TD width="5%" height=26 align=left noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
					  <INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle></TD>
                      <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['No'];//編號?></TD>
                      <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>標題</TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>發佈人</TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>類別</TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>發佈</TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>審核</TD>
                      <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>日期</TD>
   	                   
                      
                    </TR>
					<?php               

					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                    <TR class=row0>
                      <TD align=left width=40 height=26>
					  <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['id']?>' name=cid[]></TD>
                      <TD height=26 align="center" noWrap><?php echo  $i+1;?></TD>
                      <TD height=26 align="center" noWrap>
                        <?php echo $Rs['title']?>&nbsp; </TD>
                      <TD align="center" noWrap>
                      <?php
                      $Query = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".intval($Rs['user_id'])." limit 0,1");
	 				  $Result= $DB->fetch_array($Query);
					  echo $Result['username'];
					  ?>
                      </TD>
                      <TD align="center" noWrap><?php echo $Rs['stname']?></TD>
                      <TD align="center" noWrap><?php echo $Rs['ifpub']==1?"是":"否"?></TD>
                      <TD align="center" noWrap><?php echo $Rs['checked']==1?"通過":"未通過"?></TD>
                      <TD align="center" noWrap><?php echo date("Y-d-m",$Rs['pubdate'])?></TD>
                       
                    </TR>
					<?php
					$i++;
					}

					?>
                    <TR>
                      <TD width=51 height=14 nowrap>&nbsp;</TD>
                      <TD align=middle width=40 height=14>&nbsp;</TD>
                      <TD width=362 height=14>&nbsp;</TD>
                      <TD width=362>&nbsp;</TD>
                      <TD width=362>&nbsp;</TD>
                      <TD width=362>&nbsp;</TD>
                      <TD width=362>&nbsp;</TD>
                      <TD width=362>&nbsp;</TD>                      
					 </TR>
					<?php  if ($Num==0){ ?>
                    <TR align="center">
                      <TD height=14 colspan="8"><?php echo $KeFu_Pack['nodata'];//"無相關資料?></TD>
                      </TR>
		            <?php } ?>	
					 </FORM>
					 </TABLE>
					 </TD>
				    </TR>
			    </TABLE>
           <?php  if ($Num>0){ ?>
            <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
              <TBODY>
              <TR>
                <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
				<?php echo $Nav->pagenav()?>
				</TD>
              </TR>
		    </TABLE>
		   <?php } ?>	
			</TD>
		   </TR>
		  </TABLE>
		 </TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=302>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></TBODY></TABLE>
 <div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>
