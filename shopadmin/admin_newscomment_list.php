<?php
include_once "Check_Admin.php";
include_once "pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);


$Where    = $_GET['skey']!=""  && trim(urldecode($_GET['skey']))!=$Admin_Product[PleaseInputPrductName] ?  " where g.ntitle like '%".trim(urldecode($_GET['skey']))."%'" : '' ;


switch (trim($_GET['State'])){
	case "NoView":
		break;
	case "Noreplay":
		$Sql      = "select gc.* ,g.ntitle from `{$INFO[DBPrefix]}news_comment` gc  inner join `{$INFO[DBPrefix]}news` g on (gc.nid=g.news_id) where gc.already_read=0 ";
		$Sql      = Add_LOGINADMIN_TYPE($Sql); //这里是判断是否存在WHERE,以防止语法错误,同时根据是否是供应商条件,对SQL语句重新处理一下!
		$Sql      .= " order by gc.comment_idate desc ";
		break;
	default :
		$Sql      = "select gc.* ,g.ntitle from `{$INFO[DBPrefix]}news_comment` gc  inner join `{$INFO[DBPrefix]}news` g on (gc.nid=g.news_id) ".$Where." ";
		$Sql      = Add_LOGINADMIN_TYPE($Sql); //这里是判断是否存在WHERE,以防止语法错误,同时根据是否是供应商条件,对SQL语句重新处理一下!
		$Sql      .= " order by gc.comment_idate desc ";
		break;

}


$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
$limit    = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
if ($Num>0){
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>文章管理--&gt;文章評論</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_newscomment_save.php";
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
<?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN 
                  class=p9orange>文章管理--&gt;文章評論</SPAN></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    </TR>
              </TBODY></TABLE></TD></TR></TBODY></TABLE>
      
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=optForm method=get action="">        
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=left colSpan=2 height=31>
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width=500 border=0>
                <TBODY>
                  <TR>
                    <TD height=31 align=left>
                      <INPUT  name='skey'  onfocus=this.select()  onclick="if(this.value=='<?php echo $Admin_Product[PleaseInputPrductName]?>')this.value=''"  onmouseover=this.focus() value='請輸入文章標題' size="40">
                      &nbsp;&nbsp;		        
                      <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle">                </TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
              </TD>
            </TR>
          </FORM>
        </TABLE>
      
      
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD class=p9black noWrap align=left  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                                <INPUT onclick=checkAll(<?php echo intval($Nums)?>); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>
                              <TD width="510"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>文章標題</TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Comment_User];//評論內容?></TD>
                              <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Comment_System];//系统回复?></TD>
                              <TD width="127"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><NOBR><?php echo $Admin_Product[Comment_Time];//评论时间?></NOBR></TD>
                              <TD width="137"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><i class="icon-pencil" style="font-size:14px;margin-right:4px"></i><?php echo $Basic_Command['Edit'] ?></TD>
                              </TR>
                            <?php 
					if ($Num>0){

						$i=0;
						while ($Rs=$DB->fetch_array($Query)) {
							$Viewpic = $Rs['already_read']==0 ?  "service_noreply" : "service_reply";
					?><TBODY>
                            <TR class=row0>
                              <TD width=23 height=25 align=left nowrap>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['comment_id']?>' name=cid[]> 
                                <INPUT type=hidden value='<?php echo $Rs['gid']?>' name=gid>					  </TD>
                              <TD width=30 height=25 align="left" noWrap><?php echo $Rs['comment_id']?></TD>
                              <TD height=25 align=left>
                              <INPUT type=hidden value='<?php echo $Rs['gid']?>' name=gidArr[]><?php echo $Rs['ntitle']?>					  </TD>
                              <TD width=121 height=25><IMG onMouseOver="MM_showHideLayers('aimgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('aimgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/viewtext.gif" width=18>
                                <DIV class=shadow id=aimgLayer<?php echo $i?> style="BORDER-RIGHT: #cccccc 1px solid; BORDER-TOP: #cccccc 1px solid; Z-INDEX: 3; VISIBILITY: hidden; BORDER-LEFT: #cccccc 1px solid; WIDTH: 120px; BORDER-BOTTOM: #cccccc 1px solid; POSITION: absolute; HEIGHT: 80px; BACKGROUND-COLOR: #ffffff";><?php echo nl2br($Rs['comment_content']);?></DIV>
                                </TD>
                              <TD width=98 height=25><IMG onMouseOver="MM_showHideLayers('qimgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('qimgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/<?php echo $Viewpic?>.gif" width=18>
                              <DIV class=shadow id=qimgLayer<?php echo $i?> style="BORDER-RIGHT: #cccccc 1px solid; BORDER-TOP: #cccccc 1px solid; Z-INDEX: 3; VISIBILITY: hidden; BORDER-LEFT: #cccccc 1px solid; WIDTH: 120px; BORDER-BOTTOM: #cccccc 1px solid; POSITION: absolute; HEIGHT: 80px; BACKGROUND-COLOR: #ffffff";><?php echo nl2br($Rs['comment_answer']);?></DIV>                        </TD>
                              <TD height=25 align=center nowrap><?php echo date("Y-m-d H: i a ",$Rs['comment_idate']);?></TD>
                              <TD height=25 align=center><div class="link_box" style="width:80px"><A 
                        href="admin_newscomment.php?comment_id=<?php echo $Rs['comment_id']?>"><?php echo $Admin_Product[Comment_System];//回复?></A></div></TD>
                              </TR></TBODY>
                            <?php
							$i++;
						}
					?>
                            
                            <?php  }else{ ?>  
                            <TR align="center">
                              <TD height=14 colspan="7"><span class="style1"><?php echo $Basic_Command['NullDate'];//无?></span></TD>
                              </TR>
                            <?php } ?>  
                          </FORM>
                        </TABLE>
                      </TD>
                    </TR>
                </TABLE>
              <?php if ($Num>0){ ?>
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                      </TD>
                    </TR>
                </TABLE>
              <?php  } ?>  
              </TD></TR>
        </TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
