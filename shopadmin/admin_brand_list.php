<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Where    = $_GET['skey']!="" && trim($_GET['skey'])!=$Admin_Product[PleaseInputPrductBand] ? " where brandname like '%".trim(urldecode($_GET['skey']))."%'" : $Where ;
$Sql      = "select * from `{$INFO[DBPrefix]}brand` ".$Where." order by brand_id desc ";

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Brand_Man] ;//品牌管理?>--&gt;<?php echo $JsMenu[Brand_List]?></TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>
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
		document.adminForm.action = "admin_brand.php?Action=Modi&brand_id="+checkvalue;
		document.adminForm.Action.value="Modi";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_brand_save.php";
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
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black><SPAN  class=p9orange>
                      <?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $JsMenu[Brand_Man] ;//品牌管理?>--&gt;<?php echo $JsMenu[Brand_List]?></SPAN>
                      </TD>
                    </TR>
                  </TBODY>
                </TABLE>
              
              </TD>
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
                                  <TD vAlign=bottom noWrap class="link_buttom"><A href="admin_brand.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[AddBrandName];//新增?></A></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><A href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></A></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><A href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></A></TD>
                          </TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle></TD>
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
            <TD align=left colSpan=2 height=31>
              <TABLE class=p12black cellSpacing=0 cellPadding=0 width=500 border=0>
                <TBODY>
                  <TR>
                    <TD align=right width=302 height=31>
                      <INPUT  id='skey' name='skey'  onfocus=this.select()  onclick="if(this.value=='<?php echo $Admin_Product[PleaseInputPrductBand] ?>')this.value=''"  onmouseover=this.focus() value="<?php echo $Admin_Product[PleaseInputPrductBand] ?>" size="40">				</TD>
                    <TD height=31 align=left>
                      <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name='imageField' >                </TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
            <TD class=p9black align=right width=400 height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示?>  
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
              </TD>
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
                      <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action="" method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden name=Action>
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD width="45" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="62"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Basic_Command['SNo_say'];//序号?></TD>
                              <TD width="334" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
                                <?php echo $Admin_Product[Brand_Name] ;//品牌名稱?></TD>
                              <TD width="159" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>
                                <?php echo $Admin_Product[Brand_Logo];//品牌LOGO?></TD>
                              <TD width="140" height="26" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>點擊次數 </TD>
                              <TD width="121" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>語言版本</TD>
                              <TD width="110" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>顯示順序</TD>
                              <TD width="110" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>權重</TD>
                              <TD width="103" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>開啟</TD>
                              <TD width="103" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>下屬分類</TD>
                              </TR>
                            <?php               
					$i=0;
					$j=1;
					while ($Rs=$DB->fetch_array($Query)) {
					?><TBODY>
                              <TR class=row0>
                                <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['brand_id']?>' name=cid[]> </TD>
                                <TD height=26 align="center" noWrap>                        
                                <?php echo $j?>                        </TD>
                                <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['brand_id']?>',0);"><?php echo $Rs['brandname']?></A>                      </TD>
                                <TD height=26 align="center" noWrap><IMG onMouseOver="MM_showHideLayers('imgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('imgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/icon-viewpic.gif" width=18>
                                  <DIV class=shadow id=imgLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 63px; POSITION: absolute; HEIGHT: 67px"   border="1"><IMG src="../<?php echo $INFO['logo_pic_path']. "/" . $Rs['logopic']?>" ></DIV>
                                </TD>
                                
                                <TD height=26 align="center" noWrap><?php echo $Rs['viewcount']?>                     </TD>
                                <TD height=26 align="center" noWrap><?php echo $Rs['language']?></TD>
                                <TD height=26 align="center" noWrap><?php echo $Rs['orderby']?></TD>
                                <TD height=26 align="center" noWrap><input name="ratio" value="<?php echo $Rs['ratio']?>" size="5" onChange="setRatio(<?php echo $Rs['brand_id']?>,this.value);">                  </TD>
                                <TD height=26 align="center" noWrap><?php
						{  if ($Rs['bdiffb']==1)
						  	echo "<span class='red_small'><i class='icon-check'></i></span> ";
						  else
						    echo "<i class='icon-check-empty'></i> ";

					  }
					  ?>   </TD>
                                <TD align="center" noWrap><a href="admin_brand_class_list.php?brand_id=<?php echo $Rs['brand_id']?>">下屬分類</a> <a href="admin_brand_class.php?brand_id=<?php echo $Rs['brand_id']?>">新增分類</a></TD>
                              </TR>
                            </TBODY>
                          <?php
					$j++;
					$i++;
					}
					?>
                          <?php  if ($Num==0){ ?>
                          <TR align="center">
                            <TD height=14 colspan="9"><?php echo $Basic_Command['NullDate']?></TD>
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
</div>
<script language="javascript">
function setRatio(brand_id,ratio){
	$.ajax({
				url: "admin_brand_save.php",
				data: 'Action=updateratio&brand_id=' + brand_id + "&ratio=" + ratio,
				type:'post',
				dataType:"html",
				success: function(msg){
			
					
				}
			});
}							
</script>
<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
