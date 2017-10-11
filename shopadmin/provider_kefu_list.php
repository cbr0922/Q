<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";

/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";


$objClass = "9pv";
$Where    = '';
$Nav      = new buildNav($DB,$objClass);
if (isset($_GET['where'])) {
	//$Where = $_GET['where'];
	//$Where = " and " . str_replace('wodedanyinhao',"'",$Where);
	//echo $Where;
}else {
	if (isset($_GET['type_chuli'])||isset($_GET['status'])||isset($_GET['ifinclude'])) {
		if (isset($_GET['type_chuli'])) {
			$type_chuli = $_GET['type_chuli'];
			$Where .= " AND  type_chuli like '%".$_GET['type_chuli']."%'  ";
		}

		if (isset($_GET['status'])&&$_GET['status']!='') {
			$Where .= " AND  status =".$_GET['status']."";
		}

		if (isset($_GET['ifinclude'])){
			$search_type = ($_GET['search_type']);
			$Where .= ' AND  '.$_GET['search_type']." like '%".$_GET['search_content']."%'";
		}
		//$Where = substr($Where,0,strlen($Where)-4);
	}
}
if ($Where=='') {
	$Where = " and status = 0";
}


if ($_GET[Action2]=="Search"){
	$Where = " and ".$_GET[search_type]." like '%".trim(urldecode($_GET[search_content]))."%'";
	$Sql    = "select * from `{$INFO[DBPrefix]}kefu` where provider_id='" . intval($_SESSION['sa_id']) . "' ".$Where." order by lastdate DESC";
}else{
	
	$Sql    = "select * from `{$INFO[DBPrefix]}kefu` where provider_id='" . intval($_SESSION['sa_id']) . "' ".$Where." order by lastdate DESC";
}

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK id=css href="css/calendar.css" type='text/css' rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Order_Man];//定单管理?>--&gt;<?php echo $JsMenu[Order_List];//定单管理?>
</TITLE>
<script language="javascript" src="../js/TitleI.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
        /*****************************************************
         * 滑鼠hover變顏色
         ******************************************************/
$(document).ready(function() {
$("#orderedlist tbody tr").hover(function() {
		$(this).addClass("blue");
	}, function() {
		$(this).removeClass("blue");
	});
});
</script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
</HEAD>
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
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
	<? 

	include "desktop_title.php";

	?></TD>
  </TR>
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
		catvalue = "&kefu_id="+catid;
	}
	
	if (checkvalue!=false){
		
		document.adminForm.action = "provider_kefu.php?Action=Modi&kid="+checkvalue;
		document.adminForm.act.value="post";
		document.adminForm.submit();
	}
}


function toEdit1(id,catid){
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
		
		document.adminForm.action = "admin_member.php?Action=Modi&user_id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

	function changecat(){
		document.optForm1.action="provider_kefu_list.php";
		//save();
		document.optForm1.submit();
	}
function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){   //您是否确认删除选定的记录
			document.adminForm.action = "admin_kefu_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}


</SCRIPT>
<SCRIPT language=JavaScript>
<!--
function MM_findObj(n, d) { //v4.0
  var p,i,x;
  if(!d) d=document;
  if((p=n.indexOf("?"))>0 && parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n];
  for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n);
  return x;
}

function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3)
    if ((obj=MM_findObj(args[i]))!=null) {
	  v=args[i+2];
      if (obj.style) {
	    obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
      obj.visibility=v;
	}
}
//-->
</SCRIPT>
<div id="contain_out">
  <?
    include "Order_state.php";
	?>
<TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%"><TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
                <TR>
                  <TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                  <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $KeFu_Pack['Back_Nav_title_one'];//綫上客服-->綫上客服?></SPAN></TD>
                </TR>
              </TBODY>
              </TABLE></TD>
            <TD align=right width="50%">&nbsp;</TD>
          </TR>
    </TBODY>
  </TABLE>
        <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
          <TR>
            <TD align=left colSpan=2 height=31><TABLE class=p12black cellSpacing=0 cellPadding=0 width='100%' align="left" border=0>
              <FORM name=optForm1 method=get action="">
                <input type="hidden" name="Action" value="FSearch">
                <input type="hidden" name="skey" value="">
                <TR align="left">
                  <TD  align="left"></td>
                  <TD  align="left"><?php echo $KeFu_Pack['Back_Type_Do'];//問題類別-處理情況?>：
                    <select name="type_chuli" onChange="javascript:changecat();"   class="trans-input">
                      <option value=""><?php echo $Basic_Command['Please_Select'] ;?></option>
                      <?php
                    $Query_linshi = '';

                    $kefu_type = array();
                    $kefu_chuli = array();

                    $Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_type` order by checked Desc";
                    $Query_linshi = $DB->query($Sql_linshi);
                    while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
                    	$kefu_type[] = $Rs_linshi;
                    }
                    $Sql_linshi = " select * from `{$INFO[DBPrefix]}kefu_chuli` order by checked Desc";
                    $Query_linshi = $DB->query($Sql_linshi);
                    while ($Rs_linshi = $DB->fetch_array($Query_linshi)){
                    	$kefu_chuli[] = $Rs_linshi;
                    }

                    foreach ($kefu_type as $v) {
                    	foreach ($kefu_chuli as $a) {
                    		$Add_Bclass = $v['k_type_id'].'-'.$a['k_chuli_id']==$type_chuli ? " selected=\"selected\" " :  "" ;
                    		echo "<option value=".$v['k_type_id'].'-'.$a['k_chuli_id'].$Add_Bclass.">".$v['k_type_name'].'-'.$a['k_chuli_name']."</option>";
                    	}
                    }

					  ?>
                      </select>
                    -
                    <select name="status" onChange="javascript:changecat();"  class="trans-input">
                      <option value=""><?php echo $Basic_Command['Please_Select'] ;?></option>
                      <option value="0" <?php echo $_GET['status']==0 ? 'selected=\"selected\"':''?>> <?php echo $KeFu_Pack['Wait_Report'];//等待回覆?></option>
                      <option value="1" <?php echo $_GET['status']==1 ? 'selected=\"selected\"':''?>> <?php echo $KeFu_Pack['Already_Report'];//已經回覆?></option>
                      <option value="2" <?php echo $_GET['status']==2 ? 'selected=\"selected\"':''?>> <?php echo $KeFu_Pack['Close_Report'];//關閉問題?></option>
                      <option value="3" <?php echo $_GET['status']==3 ? 'selected=\"selected\"':''?>> 等待審核</option>
                      </select></td>
                  </tr>
                </form>
              </table></TD>
            <TD class=p9black align=left width=474 height=31><table class="p12black" cellspacing="0" cellpadding="0" width="67%" border="0">
              <form action="" method="get" name="optForm" id="optForm">
                <input type="hidden" name="Action2" value="Search" />
                <tbody>
                  <tr>
                    <td height="31" align="left"><input  name='search_content' class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'" size="20" />
                      &nbsp;
                      <select name="search_type"  class="trans-input">
                        <option value="title" ><?php echo $KeFu_Pack['title'];//簡單標題?></option>
                        <option value="serialnum" ><?php echo $KeFu_Pack['No'];//單號?></option>
                        <option value="username" ><?php echo $KeFu_Pack['access_no'];//帳號?></option>
                        <option value="realname" ><?php echo $KeFu_Pack['name'];//姓名?></option>
                        <option value="email" ><?php echo $KeFu_Pack['email'];//Email?></option>
                      </select>
                      <input type="image" src="images/<?php echo $INFO[IS]?>/t_go.gif" border="0" name="imageField" align="absmiddle" /></td>
                  </tr>
                </tbody>
              </form>
              </table></TD>
            <TD class=p9black align=right width=200></TD>
          </TR>
  </TABLE>
        <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
          <TBODY>
            <TR>
              <TD vAlign=top height=210><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff><TABLE class=listtable cellSpacing=0 cellPadding=0  width="100%" border=0 id="orderedlist">
                      <FORM name=adminForm action="" method=post>
                        <INPUT type=hidden name=act>
                        <INPUT type=hidden value=0  name=boxchecked>
                        <?php
					 $Where = str_replace('\'','wodedanyinhao',$Where);
					 if(!isset($_GET['offset'])){
					 	$offset = 0;
					 }else {
					 	$offset = $_GET['offset'];
					 }
					 ?>
                        <INPUT type=hidden value="<?php echo urlencode($Where)?>"  name='where'>
                        <INPUT type=hidden value="<?php echo $offset?>"  name='offset'>
                        <TBODY>
                          <TR align=middle>
                            <TD width="40" height=26 align=left noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>&nbsp;&nbsp;
                              <INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle></TD>
                            <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['Back_Type_Do'];//問題類別-處理情況?></TD>
                            <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['access_no'];//帳號?>(<?php echo $KeFu_Pack['name'];//姓名?>)</TD>
                            <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1><?php echo $KeFu_Pack['Back_Zhuti'];//主题?></TD>
                            <TD height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['LastUpdateTime'];//最後發表的時間?></TD>
                            <TD height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $KeFu_Pack['Status'];//狀態?></TD>
                            </TR>
                          <?php              

					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                          <TR class=row0>
                            <TD align=left width=40 height=20>&nbsp;&nbsp;
                              <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['kid']?>' name=cid[]></TD>
                            <TD height=20 align="center" noWrap><?php echo $Rs['type_chuli_name']?>&nbsp;</TD>
                            <TD height=20 align="center" noWrap><?php echo $Rs['username']?>&nbsp;</TD>
                            <TD height=20 align=center nowrap><A href="javascript:toEdit('<?php echo $Rs['kid']?>',0);"> <?php echo $Rs['title']?>&nbsp; </A></TD>
                            <TD height=20 align=center nowrap><?php echo date("Y/m/d",$Rs['lastdate'])?>&nbsp;</TD>
                            <TD height=20 align="center" nowrap><?php
                      if ($Rs['status']==0) {
                      	echo "<font color=red>".$KeFu_Pack[Wait_Report]."</font>"; //等待回覆
                      }
                      if ($Rs['status']==1) {
                      	echo $KeFu_Pack['Already_Report'];//"已經回覆";
                      }
                      if ($Rs['status']==2) {
                      	echo "<font color=Gray>".$KeFu_Pack['Close_Report']."</font>"; //關閉問題
                      }
					  if ($Rs['status']==3) {
                      	echo "<font color=Gray>等待審核</font>"; //關閉問題
                      }
                      ?></TD>
                            </TR>
                          <?php
					$i++;
					}

					?>
                          <TR>
                            <TD width=51 height=14 nowrap>&nbsp;</TD>
                            <TD width=81 height=14 nowrap>&nbsp;</TD>
                            <TD align=middle width=90 height=14>&nbsp;</TD>
                            <TD width=291 height=14 nowrap>&nbsp;</TD>
                            <TD width=92 height=14>&nbsp;</TD>
                            <TD width=98 height=14>&nbsp;</TD>
                            </TR>
                          <?php  if ($Num==0){ ?>
                          <TR align="center">
                            <TD height=14 colspan="6"><?php echo $KeFu_Pack['nodata'];//"無相關資料?></TD>
                            </TR>
                          <?php } ?>
                          </FORM>
                      </TABLE></TD>
                  </TR>
                </TABLE>
                <?php  if ($Num>0){ ?>
                <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                  <TBODY>
                    <TR>
                      <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav()?></TD>
                    </TR>
                </TABLE>
                <?php } ?></TD>
            </TR>
        </TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
