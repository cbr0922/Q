<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

if($_GET['act']=="csv"){
	if(!empty($_FILES['emailfile']['name'])){
		$userfile_1      = $_FILES["emailfile"]["tmp_name"]; //上傳圖檔(暫存位置)
		$userfile_name_1 = $_FILES["emailfile"]["name"];//上傳後的圖檔名稱
		$size_1          = $_FILES["emailfile"]["size"];
		$type_1          = $_FILES["emailfile"]["type"];
		$abc_1           = explode(".",$userfile_name_1);//將上傳的圖檔名稱以逗點拆開
				
		if($size_1 < '1'){
			$FUNCTIONS->sorry_back('back',"請選擇要上傳的CSV");
			exit;
		}
		if(!(strtolower($abc_1[1]) == "csv")){
			$FUNCTIONS->sorry_back('back',"請上傳CSV文件");
			exit;
		}
		if($size_1 > 20000*1024){
			$FUNCTIONS->sorry_back('back',"文件大小不能超過2M");
			exit;
		}
		
		$picname=time();
		$userpic_1      = $picname."." . $abc_1[1];//更改上傳的圖檔名稱
		$upfile_1       = "../UserFiles/csv" . $userpic_1; //檔案上傳後的位置+檔案
		$picpath_1      = $userpic_1; //存入資料庫的路徑+檔案名稱
		if(is_uploaded_file($userfile_1)){
			copy($userfile_1,$upfile_1);
			unlink($userfile_1);//刪除上傳圖檔(暫存位置)
			$handle = fopen($upfile_1,"r");
			while (!feof ($handle)) {
				$buffer = fgets($handle, 4096);
    			$content_array = explode(",",$buffer);
				if ($content_array[0] != ""){
					$v =trim($content_array[0]); 
					$insertsql    = "insert into `{$INFO[DBPrefix]}email` (email) values ('" . $v . "')";
					$DB->query($insertsql);
				}
				
			}
			
		}	
		
	}
	header("location:admin_email_list.php");
		exit;
	
}

$Sql      = "select * from `{$INFO[DBPrefix]}email` order by email asc";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;手機號</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?>
<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>
<form name="form2" method="post" action="admin_group_excel.php" target='_blank'  >
<input type="hidden" name="Action" value="Excel">
</form>
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
<SCRIPT language=javascript>


function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_company.php?Action=Modi&user_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_company_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}


</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
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
                <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;手機號</SPAN>
				</TD>
              </TR></TBODY></TABLE></TD>
          <TD align=right width="50%">&nbsp;</TD>
		  </TR>
		  </TBODY>
		</TABLE>
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=form1 id=form1 method=post action="admin_email_list.php?act=csv" enctype="multipart/form-data">        
        <TR>
          <TD align=left colSpan=2 height=31>email:
            <input type="file" name="emailfile">
            <input type="submit" name="Submit" value="導入">
            *只可以導入csv文件</TD>
          </TR>
        <TR>
          <TD width="897" height=31 align=right>		 </TD>
           <TD class=p9black align=right width=272 height=31><?php echo $Basic_Command['PerPageDisplay'];//每頁顯示?>
  		    <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
		 </TR>
		 </FORM>
	</TABLE>	
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
        <TR>
          <TD vAlign=top height=210>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD bgColor=#ffffff>
                  <TABLE class=listtable cellSpacing=0 cellPadding=0 
                  width="100%" border=0>
                    <FORM name=adminForm action="" method=post>
					<INPUT type=hidden name=act>
					 <INPUT type=hidden value=0  name=boxchecked> 
                    <TBODY>
                    <TR align=middle>
                      <TD width="20" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
					  <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle></TD>
                      <TD width="43" align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>ID</TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><span class="p9orange">email</span></TD>
                      </TR>
					<?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {


					?>
                    <TR class=row0>
                      <TD width=20 height=26 align=center>
					  <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['id']?>' name=cid[]></TD>
                      <TD width=43 height=26 align=center>
                        <?php echo $Rs['id']?></TD>
                      <TD height=26 align="left" noWrap>
                        <A href="javascript:toEdit('<?php echo $Rs['id']?>',0);">
                        <?php echo $Rs['email']?>                        </A></TD>
						</TR>
					<?php
					$i++;
					}
					?>
                    <TR>
                      <TD height=14 colspan="2" align=middle>&nbsp;</TD>
                      <TD width=624 height=14>&nbsp;</TD>
                      </TR>
					 </FORM>
					 </TABLE>					 </TD>
				    </TR>
			    </TABLE>
            
			<?php if ($Num>0){ ?>
			<table class=p9gray cellspacing=0 cellpadding=0 width="100%"    border=0>
              <tbody>
                <tr>
                  <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav()?> </td>
                </tr>
                <?php } ?>
                </table></TD>
        </TR></TABLE></TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=302>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></TBODY></TABLE>
<script language="javascript" src="../js/modi_bigarea1.js"></script>
<script language="javascript">
initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")
initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")
</script>

                      <div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
