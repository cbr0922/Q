<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');

$Where    =  "";
$Where    = $_GET['skey']!="" ?  " where goodsname like '%".$_GET['skey']."%'" : $Where ;

if (strpos($Where,"where")){
	$s = $Where." and  gid!=".$Gid." ";
}else{
	$s = $Where." where  gid!=".$Gid." ";
}
$Sql      = "select * from `{$INFO[DBPrefix]}goods` ".$s." order by goodorder ";

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
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<META http-equiv=ever content=no-cache>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK href="../css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2600.0" name=GENERATOR><title></title>
</HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" onLoad="addMouseEvent();">
<SCRIPT language=JavaScript>

<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->
</SCRIPT>

<script language=javascript src='../js/smartshop.js'></script>
<script language=javascript src='../js/smartshops.js'></script>
<SCRIPT language=JavaScript>

function checkform()
	{		
	var checkvalue;

	 checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
 
	 if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Save_Select']?>')){ //您是否確認保存選定的記錄？!
			document.adminForm.action = "AdminLinkGoodsList.php";
			document.adminForm.act.value="Save";
			document.adminForm.submit();
		}
	}
		
	}
	
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
<A id=linkact_htc></A>
<TABLE width="700" height=9 border=0 align="center" cellPadding=0 cellSpacing=0 background=images/<?php echo $INFO[IS]?>/menubo.gif>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=100%>
    </TD>
  </TR>
 </TBODY>
</TABLE>
<TABLE cellSpacing=0 cellPadding=0 width="700" align=center border=0>
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=8  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=8></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" ></TD>
    <TD vAlign=top width="98%">
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>

        <FORM name=optForm method=get action="AdminLinkGoodsList.php">        
		<input type="hidden" name="Action" value="Search">		 
		<INPUT type=hidden name='Goodsname' value="<?php echo $Goodsname?>" >
		<INPUT type=hidden name='gid' value="<?php echo $Gid?>" >
        <TR>
          <TD align=center colSpan=2 height=31>
            <TABLE width="90%" border=0 align="left" cellPadding=0 cellSpacing=0 class=p12black>
              <TBODY>
              <TR>
                <TD   height=31 align=center nowrap><?php echo $Admin_Product[PleaseInputPrductName];//請輸入商品名稱?>&nbsp;
                 <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"   name='skey'>
				</TD>
                <TD width="155"  height=31 align="left" vAlign=center class=p9black>
		        &nbsp;&nbsp;&nbsp; <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle"> 
                </TD>
        	    <TD width="61" vAlign=center class=p9black>
				<!--BUTTON_BEGIN-->

<?php if ($Ie_Type != "mozilla") { ?>
                        <TABLE class=fbottonnew  link="javascript:checkform();"><TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Select'] ;//選定?>&nbsp; </TD>
							</TR>
							</TBODY>
				        </TABLE>

    <?php } else {?> 
                        <TABLE><TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap>
							<a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Select'] ;//選定?></a>&nbsp; </TD>
							</TR>
							</TBODY>
				        </TABLE>
    <?php } ?>							
				<!--BUTTON_END-->				
				</TD>
        	    <TD width="39" vAlign=center class=p9black>&nbsp;</TD>
              </TR>
		       </TBODY>
		     </TABLE></TD>
           <TD class=p9black align=center  height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示?> 
  		    <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit," class=p9black onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>
		  </TD>
		 </TR>
		 </FORM>
	</TABLE>
	
	
      <TABLE width="98%" border=0 align="center" cellPadding=0 cellSpacing=0>
        <TBODY>
        <TR>
          <TD vAlign=top>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD bgColor=#ffffff>
                  <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>

                    <FORM name=adminForm action="AdminLinkGoodsList.php" method=post>
					<INPUT type=hidden name=act >
					<INPUT type=hidden name='Goodsname' value="<?php echo $Goodsname?>" >
					<INPUT type=hidden name='gid' value="<?php echo $Gid?>" >
					 <INPUT type=hidden value=0  name=boxchecked> 
                    <TBODY>
                    <TR align=middle>
                      <TD width="10%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
					  <INPUT onclick=checkAll(<?php echo intval($Nums)?>); type=checkbox value=checkbox   name=toggle> </TD>
                      <TD width="10%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductSmPic];//缩图?>
					  </TD>
                      <TD width="15%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>
                      <TD width="50%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductName];//名称?>					  </TD>
                      <TD  height=26 colspan="8" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductPrice];//价格?>					  </TD>
                      </TR>
					<?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

						$Yes = "images/".$INFO[IS]."/publish_g.png";
						$No  = "images/".$INFO[IS]."/publish_x.png";
						$ifpub_pic  = $Rs['ifpub']==1  ? $Yes : $No ;
						$ifrmd_pic  = $Rs['ifrecommend']==1  ? $Yes : $No ;
						$ifspec_pic = $Rs['ifspecial']==1  ? $Yes : $No ;
						$ifhot_pic  = $Rs['ifhot']==1  ? $Yes : $No ;

					?>
                    <TR class=row0>
                      <TD align=middle  height=20>
					  <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['gid']?>' name=cid[]> 
					  </TD>
					  <TD align=left  height=20><IMG onMouseOver="MM_showHideLayers('imgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('imgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/icon-viewpic.gif" width=18>
                        <DIV class=shadow id=imgLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 63px; POSITION: absolute; HEIGHT: 67px"   border="1"><IMG src="../<?php echo $INFO['good_pic_path']?>/<?php echo $Rs['smallimg']?>" ></DIV>
					  </TD>
                      <TD height=20 align="left" noWrap><?php echo $Rs['bn']?> &nbsp;</TD>
                      <TD  height=20 align=left nowrap> <?php echo $Rs['goodsname']?></TD> 
                      <TD height=20 colspan="8" align=left nowrap><?php echo $Rs['price']?></TD>
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
			<?php
            if ($Num>0){
			?>
            <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>

              <TBODY>
              <TR>
                <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
				<?php echo $Nav->pagenav()?>
				</TD>
              </TR>
			<?php
            }
			?>

	 	 </TABLE>
		</TD>
        </TR>
	  </TABLE>	</TD>
    <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif>&nbsp;</TD>
  </TR>
    <TR>
    <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
<TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif" width=9></TD></TR></TBODY></TABLE>
</BODY></HTML>
<?php
if ($_POST['act']=='Save'){

	$cid      = $_POST['cid'];
	$cid_num  = count($cid);
	$zk_price = $_POST['zk_price'];
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select good_link_id from `{$INFO[DBPrefix]}good_link` where p_gid='$Gid' and s_gid='$cid[$i]' order by idate desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num<1){
			$Result =  $DB->query(" insert into `{$INFO[DBPrefix]}good_link` (p_gid,s_gid,link_type,zk_price,idate) values('".intval($Gid)."','".intval($cid[$i])."','0','".doubleval($zk_price[$i])."','".time()."')");
		}
	}

	if ($Result) {
		echo "
			<script language=javascript>
        	window.opener.location.href=window.opener.location.href
           	window.close();	
	        </script>

			";
		exit;
	}
}
?>
