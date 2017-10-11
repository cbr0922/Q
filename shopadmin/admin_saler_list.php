<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

$Where    = trim($_GET['skey'])!=""  && urldecode(trim($_GET['skey']))!=trim($Basic_Command['InputKeyWord']) ?  " where name like '%".urldecode(trim($_GET['skey']))."%' " : "" ;
$Sql      = "select * from `{$INFO[DBPrefix]}saler` ".$Where." order by pubtime  ";

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
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>經銷商管理--&gt;經銷商列表  </TITLE>
<script type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
//-->
</script>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
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
		catvalue = "&id="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_saler.php?Action=Modi&id="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){   //您是否确认删除选定的记录
			document.adminForm.action = "admin_saler_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}
</SCRIPT>
<div id="contain_out">
      <?php  include_once "Order_state.php";?>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Provider_Man]?>--&gt;經銷商列表  </SPAN>
                    </TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_saler.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;新增經銷商</a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->
                          </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </TD>                  
                </TR>
              </TBODY>
              </TABLE>
            </TD>
          </TR>
        </TBODY>
  </TABLE>
      
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=optForm method=get action="">        
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=left height=31>
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width=254 border=0>
                <TBODY>
                  <TR>
                    <TD align=right width=214 height=31>
                      <INPUT  name='skey'    onfocus=this.select()  onclick="if(this.value=='<?php echo $Basic_Command['InputKeyWord'];?>')this.value=''"  onmouseover=this.focus() value="<?php echo $Basic_Command['InputKeyWord']?>" size="30"> </TD>
                    <TD class=p9black vAlign=center width=40 height=31>&nbsp; <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle">                </TD>
                  </TR>
                </TBODY>
              </TABLE>		 </TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示 ?><?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
          </TR>
          <TR>
            <TD align=left colSpan=2 height=31><table width="100%" border="0" cellpadding="0" cellspacing="0" class="allborder" style="margin-top:10px;margin-bottom:10px">
              <tr>
                <td width="56" height="87" align="center" valign="top" style="padding-top:8px"><i class="icon-warning-sign" style="font-size:16px;margin-right:4px; color:#06F"></i></td>
                <td>"經銷商連結方式"
                  <div id="tip01tips" class="tips_note">1. 於網址後加上?saler=經銷商帳號<br>
                    eg. http://www.smartshop.com.tw/index.php<span class="p9orange">?saler=12345 </span><br>
                    2. 若網址已有動態參數，則加上&amp;saler=經銷商帳號<br>
                    eg. http://www.smartshop.com.tw/product/goods_detail.php?goods_id=13<span class="p9orange">&amp;saler=12345</span></div>
                </td>
              </tr>
              </table></TD>
          </TR>
        </FORM>
  </TABLE>
      
      
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=131>
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
                              <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26>
                                <INPUT onclick=checkAll(<?php echo $Nums?>); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="51"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <?php echo $Basic_Command['SNo_say'] ?></TD>
                              <TD width="259"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
                                經銷商名稱</TD>
                              <TD width="121" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>開通密碼</TD>
                              <TD width="128" height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style="padding-left:5px;">
                                <i class="icon-phone" style="font-size:14px;margin-right:4px"></i><?php echo $Admin_Member[Phone];//聯絡電話：?></TD>
                              <TD width="200" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style="padding-left:5px;">
                               <i class="icon-envelope" style="font-size:14px;margin-right:4px"></i><?php echo $Admin_Member[Email];//電子信箱?></TD>
                              <TD width="162" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><i class="icon-user" style="font-size:14px;margin-right:4px"></i>查看會員列表</TD>
                              </TR>
                            <?php               
					$i=0;
					while ($Rs=$DB->fetch_array($Query)) {
					?>
                            <TR class=row0>
                              <TD align=middle width=40 height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['id']?>' name=cid[]>					   
                                </TD>
                              <TD height=26 align="left" noWrap><?php echo  $i+1;?>&nbsp;</TD>
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['id']?>',0);"> <?php echo $Rs['name']?>&nbsp; </A></TD>
                              <TD align=center nowrap><?php echo $Rs['openpwd']?>&nbsp;</TD>
                              <TD height=26 align=left nowrap style="padding-left:5px;"><?php echo $Rs['tel']?>&nbsp;</TD>
                              <TD height=26 align=left nowrap style="padding-left:5px;"><a href="mailto:<?php echo $Rs['email']?>"><?php echo $Rs['email']?>&nbsp;</a></TD>
                              <TD align=center nowrap><div class="link_box" style="width:90px"><a href="admin_member_list.php?companyid=<?php echo intval($Rs['id']);?>">查看所屬會員</a></div></TD>
                              </TR>
                            <?php
					$i++;
					}
					?>
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
              <?php } ?>  
            </TD>
          </TR>
  </TABLE>
</div>
<div align="center"><?php include_once "botto.php";?></div></BODY></HTML>
