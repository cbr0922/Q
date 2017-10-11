<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$Gid         = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');
$Goodsname   = str_replace(" ","+",trim($FUNCTIONS->Value_Manage($_GET['Goodsname'],$_POST['Goodsname'],'back','')));

$Sql         = "select gl.* ,g.goodsname,g.bn,g.price as gprice from `{$INFO[DBPrefix]}good_link` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.s_gid=g.gid) where gl.p_gid=".intval($Gid)." order by gl.idate desc ";
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);

//删除资料!
if ($_POST['act']=="Del"){
	$GoodLinkIdArray = $_POST['good_link_id'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}good_link` where good_link_id=".intval($GoodLinkIdArray[$i]));
		}
	}
	$FUNCTIONS->sorry_back("admin_linkgoods.php?gid=$Gid&Goodsname=".trim($Goodsname)."","");
}

//更新资料!
if ($_POST['act']=="Save"){
	$Goodlinkid   = $_POST['good_link_id'];
	$S_gid        = $_POST['S_gid'];
	$Allid        = $_POST['Allid'];
	$Zk_price     = $_POST['zk_price'];

	if (count($Goodlinkid)>0) {                   //如果确实有资料提交!
		for ($i=0;$i<count($Allid);$i++){           //将提交的所有资料的ID做大循环
			foreach($Goodlinkid as $k => $v){         //循环提交的将要被操作的资料
				if ($v == $Allid[$i]){                    //如果提交的记录中ID与大循环ID相同的时候
					$link_type = $_POST["link_type".$i];      //这里就能获得提交记录中的类型的值了

					$Update_sql = " update `{$INFO[DBPrefix]}good_link` set zk_price='".intval($Zk_price[$i])."' , link_type='".intval($link_type)."' where good_link_id='".intval($Allid[$i])."' ";
					$DB->query($Update_sql);  //首先将本条记录更新


					switch ($link_type){  // 根据值来判断是交叉还是提升销售
						case 0:   //如果就是默认的提升销售

						break;
						case 1:  //如果是交叉销售,这里就要首先SCAN good_link 表 判断是插入还是UPDATE操作了!!
						$Query = $DB->query("select good_link_id from `{$INFO[DBPrefix]}good_link` where s_gid=".intval($Gid)." and p_gid=".intval($S_gid[$i])." order by good_link_id desc limit 0,1"); //查看有没有主是被选产品,次是本类产品记录!
						$Num   = $DB->num_rows($Query);
						if ($Num==0){  // 如果没有记录,就插入一条记录到数据库
							$DB->query(" insert into `{$INFO[DBPrefix]}good_link` (p_gid,s_gid,link_type) values('".intval($S_gid[$i])."','".intval($Gid)."','1')");
						}
						break;

					}
					// 注意,这里应该对提交类型还要做对应的处理,现在还没有


					break 1;
				}
			}
		}
	}
	$FUNCTIONS->sorry_back("admin_linkgoods.php?gid=$Gid&Goodsname=".trim($Goodsname)."","");
}
?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<head>
<META http-equiv=ever content=no-cache>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2600.0" name=GENERATOR><title><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[AboutProduct];//相关商品?>  </title>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" onLoad="addMouseEvent();">
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
	<?php  include_once "desktop_title.php";?></TD>
  </TR>
  </TBODY>
 </TABLE>

<SCRIPT language=javascript>

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Num)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){  //您是否确认删除选定的记录
			document.adminForm.action = "";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}

function toSave(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Num)?>','<?php echo $Basic_Command['No_Select']?>');
	  if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Save_Select'];?>')){ //是否保存
			document.adminForm.action = "admin_linkgoods.php";
			document.adminForm.act.value="Save";
			document.adminForm.submit();
		}
	}
}
</SCRIPT>

<script language="javascript">
function cha(gid,Goodsname)
{
	w = 700;
	h = 680;
	resize = 'yes';
	//l = Math.ceil( (window.screen.width  - w) / 2 );
	//t = Math.ceil( (window.screen.height - h) / 5 * 2 );

	//var option = "scrollbars=1,location=0,menubar=0,status=0,toolbar=0,resizable=" + resize	+ ",height=" + h + ",width=" + w + ",left=" + l + ",top=" + t;
	var option = "scrollbars=0,location=0,menubar=0,status=0,toolbar=0,resizable=" + resize	+ ",height=" + h + ",width=" + w ;
	window.open('AdminLinkGoodsList.php?gid=' + gid + '&Goodsname=' + Goodsname,0,option);

}

</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>

   <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                <TD class=p12black><SPAN class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;<?php echo $Admin_Product[AboutProduct];//相关商品?>  </SPAN></TD>
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
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew link="admin_goods.php?Action=Modi&gid=<?php echo $Gid?>">
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"   border=0>&nbsp;<?php echo $Basic_Command['Return'];//返回?>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew
                          link="javascript:toSave();"><TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?>&nbsp; </TD>
							</TR>
							</TBODY>
					     </TABLE><!--BUTTON_END-->
					   </TD>
					  </TR>
					 </TBODY>
				  </TABLE>
				 </TD>
				 <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE class=fbottonnew  link="javascript:toDel();"><TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"  border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?>&nbsp; </TD>
							</TR>
							</TBODY>
					     </TABLE>
						 <!--BUTTON_END-->
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
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><a href="admin_goods.php?Action=Modi&gid=<?php echo $Gid?>"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif"   border=0>&nbsp;<?php echo $Basic_Command['Return'];//返回?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE><TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:toSave();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD>
							</TR>
							</TBODY>
					     </TABLE><!--BUTTON_END-->
					   </TD>
					  </TR>
					 </TBODY>
				  </TABLE>
				 </TD>
				 <TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN-->
                        <TABLE><TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"  border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a>&nbsp; </TD>
							</TR>
							</TBODY>
					     </TABLE>
						 <!--BUTTON_END-->
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


      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD vAlign=top bgColor=#ffffff height=300>
                  <TABLE class=allborder cellSpacing=3 cellPadding=3
                  width="100%" bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD colspan="2">&nbsp;</TD></TR>
                    <TR>
                      <TD noWrap align=right> <?php echo $Admin_Product[ProductName];//商品名称?>：</TD>

                      <TD width="4%" nowrap><?php echo trim(base64_decode($Goodsname));?> </TD>
		              <TD width="84%" nowrap><TABLE border="1" bordercolor="#CCCCCC" class=fbottonnew link="javascript: cha('<?php echo $Gid?>','<? //$Goodsname?>');">
                        <TBODY>
                          <TR>
                            <TD vAlign=middle noWrap>
							<IMG  src="images/<?php echo $INFO[IS]?>/fb-relatedpro.gif" border="0" align="absmiddle" class="fbottonnew" />
							<span class="style2">&nbsp;<?php echo $Admin_Product[PleaseSelectAboutPrduct];//请选择相关商品?></span></TD>
                          </TR>
                        </TBODY>
                      </TABLE></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right width="12%"><?php echo $Admin_Product[MCJS_Title];//名词解释?>：</TD>
                      <TD colspan="2"><span class="unnamed1 style3"><?php echo $Admin_Product[MCJS_Title_one];//提升销售 （单向相关）?></span>：
					  <?php echo $Admin_Product[MCJS_Title_One_Content_I];?>(<?php echo  base64_decode($Goodsname)?>)<?php echo $Admin_Product[MCJS_Title_One_Content_II];?>
					  </TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD colspan="2"><span class="unnamed1 style3"><?php echo $Admin_Product[MCJS_Title_two];//交叉销售 （交叉相关）?></span>：
					  <?php echo $Admin_Product[MCJS_Title_Two_Content_I];?>(<?php echo  base64_decode($Goodsname)?>)<?php echo $Admin_Product[MCJS_Title_Two_Content_II];?>
					  </TD>
                    </TR>

                    <TR>
                      <TD noWrap align=right width="12%"> <?php echo $Admin_Product[AboutProductNum];//相關商品件數?>：</TD>
                      <TD colspan="2"><?php echo  $Num ?></TD>
                    </TR>
                      <TR align="center">
                      <TD colspan="3" valign="top" noWrap>&nbsp;					    </TD>
                      </TR>
                    <TR align="center">
                      <TD colspan="3" valign="top" noWrap>
<!--  start    -->

<TABLE class=allborder cellSpacing=1 cellPadding=0  width="95%" bgColor=#666666 border=0>

 <FORM name=adminForm action="" method=post>
   <INPUT type=hidden name=act >
   <INPUT type=hidden value=0  name=boxchecked>
   <INPUT type=hidden  name='gid' value="<?php echo $Gid?>">
   <INPUT type=hidden  name='Goodsname' value="<?php echo $Goodsname?>">
    <TBODY>
     <TR bgColor=#e7e7e7 height=25>
       <TD noWrap align=left width="10%"    height=17 bgColor=#e7e7e7><INPUT onClick="checkAll('<?php echo intval($Num)?>');" type=checkbox value=checkbox   name=toggle>
	   &nbsp;<?php echo $Basic_Command['SNo_say'];//序号?></TD>
       <TD noWrap align=center width="12%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[Bn];//貨號?></TD>
       <TD noWrap align=middle width="36%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[ProductName];//商品名称?></TD>
       <TD noWrap align=center  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[ProductPrice];//商品价格?></TD>
       <TD noWrap align=center  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[AboutType] ;//相关类型?>&nbsp;&nbsp;&nbsp;</TD>
	   </TR>
	<?php
	$i=0;
	$j=1;
	while ($Result    = $DB->fetch_array($Query)) {
	?>
     <TR valign="top" bgColor=#f7f7f7>
 	   <TD align="left" valign="middle" class=unnamed1><INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo intval($Result['good_link_id'])?>' name='good_link_id[]'>&nbsp;<?php echo $j?><INPUT type=hidden value="<?php echo $Result['good_link_id']?>" name="Allid[]"><INPUT type=hidden value="<?php echo $Result['s_gid']?>" name="S_gid[]"></TD>
       <TD align="center" valign="middle" class=unnamed1>&nbsp;<?php echo $Result['bn']?></TD>
	   <TD valign="middle" class=unnamed1>&nbsp;<?php echo $Result['goodsname']?></TD>
	   <TD align="center" valign="middle" class=unnamed1>&nbsp;<?php echo $Result['gprice']?></TD>
	   <TD align="center" valign="middle" nowrap class=unnamed1>&nbsp;<?php echo $FUNCTIONS->Input_Box("radio_strand","link_type".$i,intval($Result['link_type']),$add=array($Admin_Product[MCJS_Up_Sale],$Admin_Product[MCJS_Cross_Sale]));?>       </TD>
	   </TR>
	<?php
	$j++;
	$i++;
	}
	?>
  </TBODY>
</FORM>
</TABLE>

 <!--   end    -->					   </TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD colspan="2">&nbsp;            </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
  <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR>

	</TBODY></TABLE>

 <div align="center">
     <?php include_once "botto.php";?>
 </div>
</BODY></HTML>
