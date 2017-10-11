<?php
include "Check_Admin.php";
include      "../language/".$INFO['IS']."/Visit_Pack.php";
//include_once('./htmlfunction/Create3DStat.php');
//$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y",time())."-".date("m",time())."-01" ;
$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y",time())."-01-01" ;
$Endtime = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time()) ;

$Sql     = " select ie,os,screen from `{$INFO[DBPrefix]}visit_ip` where visit_idate >='".$Begtime."' and visit_idate <= '".$Endtime."' order by visit_idate desc ";
$Query   = $DB->query($Sql);
$Num     = $DB->num_rows($Query);
$TotalNum     = $Num;
?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK id=css href="../css/calendar.css" type='text/css' rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--><?php echo $Visit_Packs[ClientTotal]?>
</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
<script language="javascript">
function setGraphType(type) {
	return;
}
</script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  >
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" 
background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center 
  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
      <?php  include_once "Order_state.php";?>
  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD></TR></TBODY></TABLE>


<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>

  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif"   width=9></TD></TR>
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
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--><?php echo $Visit_Packs[ClientTotal]?></SPAN></TD>
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
                            <TD vAlign=bottom noWrap class="link_buttom">
							<a href="Visit.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Return'];//返回?></a>&nbsp; 
							</TD>
							</TR>
						  </TBODY>
					    </TABLE>
    <!--BUTTON_END--></TD>
                    </TR></TBODY></TABLE>
							
					  </TD></TR></TBODY></TABLE></TD></TR>
		  </TBODY>
	    </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD height="300" vAlign=top bgColor=#ffffff>
                  <TABLE class=9pv cellSpacing=0 cellPadding=2 
                  width="100%" align=center border=0>
                    <TBODY>
                    <TR align="center">
                      <TD height="300" valign="top">
					   <FORM name=form1 method=get>
					     <br>
					     <span class="p9black"><?php echo $Visit_Packs[VisFrom];//从?>&nbsp; 
					     <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=begtime size=10  onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $Begtime;?>' name='begtime'> 
						 
					    &nbsp;<?php echo $Visit_Packs[VisTo];//至?>&nbsp; 
					   <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=endtime size=10   onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $Endtime;?>' name='endtime'>
					 					 
					   <INPUT class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'" type=submit value=<?php echo $Visit_Packs[ReSearch] ;//重新查询?>>
					   </span>
					    </FORM>
						<br>
						<?php
						if ($Num>0){
							$ie_i = 0 ;
							$ie_j = 0 ;
							$ie_k = 0 ;
							$ie_l = 0 ;
							$ie_o = 0 ;
							$ie_f = 0 ;

							$os_i = 0 ;
							$os_j = 0 ;
							$os_k = 0 ;
							$os_l = 0 ;
							$os_q = 0 ;
							$os_o = 0 ;

							$scr_i = 0 ;
							$scr_j = 0 ;
							$scr_k = 0 ;
							$scr_l = 0 ;
							$scr_q = 0 ;
							$scr_o = 0 ;

							while ($Result = $DB->fetch_array($Query)){

								$ie_i = $Result['ie']=="msie 5.0" ? $ie_i+1 : $ie_i ;
								$ie_j = $Result['ie']=="msie 5.5" ? $ie_j+1 : $ie_j ;
								$ie_k = $Result['ie']=="msie 6"   ? $ie_k+1 : $ie_k ;
								$ie_l = $Result['ie']=="netscape" ? $ie_l+1:  $ie_l ;
								$ie_f = $Result['ie']=="mozilla"  ? $ie_f+1:  $ie_f ;
								$ie_7 = $Result['ie']=="msie 7"   ? $ie_7+1:  $ie_7 ;
								$ie_o = $Result['ie']!="netscape" && $Result['ie']!="msie 5.0" && $Result['ie']!="msie 5.5" && $Result['ie']!="msie 6"  && $Result['ie']!="msie 7" && $Result['ie']!="mozilla" ? $ie_o+1 : $ie_o ;

								$os_i = $Result['os']=="windows 98"     ? $os_i+1 : $os_i ;
								$os_j = $Result['os']=="windows me"     ? $os_j+1 : $os_j ;
								$os_k = $Result['os']=="windows nt 5.0" ? $os_k+1 : $os_k ;
								$os_l = $Result['os']=="windows nt 5.1" ? $os_l+1 : $os_l ;
								$os_q = $Result['os']=="macintosh"      ? $os_q+1 : $os_q ;
								$os_o = $Result['os']!="macintosh" && $Result['os']!="windows nt 5.1"  &&  $Result['os']!="windows nt 5.0" && $Result['os']!="windows 98" && $Result['os']!="windows me"   ? $os_o+1 : $os_o ;

								$scr_i = $Result['screen']=="1"         ? $scr_i+1 : $scr_i ;
								$scr_j = $Result['screen']=="2"         ? $scr_j+1 : $scr_j ;
								$scr_k = $Result['screen']=="3"         ? $scr_k+1 : $scr_k ;
								$scr_l = $Result['screen']=="4"         ? $scr_l+1 : $scr_l ;
								$scr_q = $Result['screen']=="5"         ? $scr_q+1 : $scr_q ;
								$scr_o = $Result['screen']!="5" &&  $Result['screen']!="4"  && $Result['screen']!="3"  && $Result['screen']!="2"  && $Result['screen']!="1"  ? $scr_o+1 : $scr_o ;

							}
							$ie_total   = ($ie_i+$ie_j+$ie_k+$ie_l+$ie_f+$ie_o+$ie_7);
							$os_total   = ($os_i+$os_j+$os_k+$os_l+$os_q+$os_o);
							$scr_total  = ($scr_i+$scr_j+$scr_k+$scr_l+$scr_q+$scr_o);

						?>
						
						 <table  cellSpacing=0 cellPadding=0   width="100%" border=0>
						 	<tbody>
						 		<tr>
						 			<td align="center">
		
										<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="800px" height="375px" id="UrchinGraph" align="middle">
											<param name="allowScriptAccess" value="sameDomain" />
											<param name="movie" value="images/shopnc.swf" />
											<param name="quality" value="high" />
											<param name="bgcolor" value="#ffffff" />
											<param name="wmode" value="transparent" />
											<embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" name="UrchinGraph" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="275px" width="600px" flashvars="ntitle=<?php echo $Visit_Packs[BrowerType];//浏览器类型?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UseNum];//使用数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[IeLiuLanQi];//浏览器?><?php echo $Visit_Packs[VisitDetail] ;//统计结果?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo $ie_i+$ie_j+$ie_k+$ie_l+$ie_l+$ie_f+$ie_o;?>&xdata=IE 5.0\t<?php echo $ie_i;?>\nInternet Explorer 5.5\t<?php echo $ie_j;?>\nnIE 6.0\t<?php echo $ie_k;?>\nIE 7.0\t<?php echo $ie_l?>\nNetscape Navigator\t<?php echo $ie_l?>\nMozilla\t<?php echo $ie_f?>\nother\t<?php echo $ie_o?>\n"  >
											<param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[BrowerType];//浏览器类型?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UseNum];//使用数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[IeLiuLanQi];//浏览器?><?php echo $Visit_Packs[VisitDetail] ;//统计结果?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo $ie_i+$ie_j+$ie_k+$ie_l+$ie_l+$ie_f+$ie_o;?>&xdata=IE 5.0\t<?php echo $ie_i;?>\nIE 5.5\t<?php echo $ie_j;?>\nIE 6.0\t<?php echo $ie_k;?>\nIE 7.0\t<?php echo $ie_l?>\nNetscape Navigator\t<?php echo $ie_l?>\nMozilla\t<?php echo $ie_f?>\nother\t<?php echo $ie_o?>\n" />
										</object>
						 			</td>
						 		</tr>
						 		<tr>
						 			<td align="center">
		
										<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="800px" height="375px" id="UrchinGraph" align="middle">
											<param name="allowScriptAccess" value="sameDomain" />
											<param name="movie" value="images/shopnc.swf" />
											<param name="quality" value="high" />
											<param name="bgcolor" value="#ffffff" />
											<param name="wmode" value="transparent" />
											<embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" name="UrchinGraph" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="275px" width="600px" flashvars="ntitle=<?php echo $Visit_Packs[Jxd] ;//分辨率?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UseNum];//使用数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[Jxd] ;//分辨率?><?php echo $Visit_Packs[VisitDetail] ;//统计结果?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo $scr_i+$scr_j+$scr_k+$scr_l+$scr_q+$scr_o;?>&xdata=640 x 480\t<?php echo $scr_i;?>\n800 x 600\t<?php echo $scr_j;?>\n1024 x 768\t<?php echo $scr_k;?>\n1152 x 864\t<?php echo $scr_l?>\n1280 x 1024\t<?php echo $scr_q?>\nother\t<?php echo $scr_o?>\n">
											<param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[Jxd] ;//分辨率?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UseNum];//使用数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[Jxd] ;//分辨率?><?php echo $Visit_Packs[VisitDetail] ;//统计结果?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo $scr_i+$scr_j+$scr_k+$scr_l+$scr_q+$scr_o;?>&xdata=640 x 480\t<?php echo $scr_i;?>\n800 x 600\t<?php echo $scr_j;?>\n1024 x 768\t<?php echo $scr_k;?>\n1152 x 864\t<?php echo $scr_l?>\n1280 x 1024\t<?php echo $scr_q?>\nother\t<?php echo $scr_o?>\n" />
										</object>
						 			</td>
						 		</tr>
						 		<tr>
						 			<td align="center">		
										<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="800px" height="375px" id="UrchinGraph" align="middle">
											<param name="allowScriptAccess" value="sameDomain" />
											<param name="movie" value="images/shopnc.swf" />
											<param name="quality" value="high" />
											<param name="bgcolor" value="#ffffff" />
											<param name="wmode" value="transparent" />
											<embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" name="UrchinGraph" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="275px" width="600px" flashvars="ntitle=<?php echo $Visit_Packs[Jxd] ;//分辨率?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UseNum];//使用数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[Jxd] ;//分辨率?><?php echo $Visit_Packs[VisitDetail] ;//统计结果?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo $scr_i+$scr_j+$scr_k+$scr_l+$scr_q+$scr_o;?>&xdata=640 x 480\t<?php echo $scr_i;?>\n800 x 600\t<?php echo $scr_j;?>\n1024 x 768\t<?php echo $scr_k;?>\n1152 x 864\t<?php echo $scr_l?>\n1280 x 1024\t<?php echo $scr_q?>\nother\t<?php echo $scr_o?>\n">
											<param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[OS];//操作系统?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UseNum];//使用数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[OS];//操作系统?><?php echo $Visit_Packs[VisitDetail] ;//统计结果?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo $os_i+$os_j+$os_k+$os_l+$os_q+$os_o;?>&xdata=Windows 98\t<?php echo $os_i;?>\nWindows ME\t<?php echo $os_j;?>\nWindows 2000\t<?php echo $os_k;?>\nWindows XP\t<?php echo $os_l?>\nMacintosh\t<?php echo $os_q?>\nother\t<?php echo $os_o?>\n" />
										</object>
						 			</td>
						 		</tr>
						 	</tbody>
						 </table>
						
<!--					   <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
                           <TBODY>
                             <TR align=middle class=row1>
                               <TD width="229"  align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Visit_Packs[IeLiuLanQi];//浏览器?></TD>
                               <TD width="165" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Visit_Packs[VisitDetail] ;//统计结果?></TD>
                               <TD  colspan="2" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Visit_Packs[PicSay];//图释?> </TD>
                               </TR>
                             <TR class=row0>
                               <TD height=20 align=left>  &nbsp; Internet Explorer 5.0 </TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_i;?></TD>
                               <TD width="246" height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $ie_is = (round(($ie_i/$ie_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_is;?></TD>
                             </TR>
                             <TR class=row0>
                               <TD height=20 align=left>&nbsp;&nbsp;Internet Explorer 5.5</TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_j;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $ie_js = (round(($ie_j/$ie_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_js;?></TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 align=left>&nbsp;Internet Explorer 6.0</TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_k;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $ie_ks = (round(($ie_k/$ie_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_ks;?></TD>
                             </TR>

                             <TR class=row0>
                               <TD height=20 align=left>&nbsp;&nbsp;Internet Explorer 7.0</TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_l;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $ie_7s = (round(($ie_7/$ie_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_7s;?></TD>
                             </TR>
							 
                             <TR class=row1>
                               <TD height=20 align=left>&nbsp;Netscape Navigator</TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_l;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $ie_ls = (round(($ie_l/$ie_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_ls;?></TD>
                             </TR>
                             <TR class=row0>
                               <TD height=20 align=left>&nbsp;&nbsp;Mozilla</TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_f;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $ie_fs = (round(($ie_f/$ie_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_fs;?></TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 align=left>&nbsp;&nbsp;<?php echo $Basic_Command['Other'];//"其他?></TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_o;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $ie_os = (round(($ie_o/$ie_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $ie_os;?></TD>
                             </TR>
					     </TABLE>
						 <br>
						 <br>-->
<!--						 <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
                           <TBODY>
                             <TR align=middle class=row0>
                               <TD width="229" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Visit_Packs[Jxd] ;//分辨率?></TD>
                               <TD width="165" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Visit_Packs[VisitDetail] ;//统计结果?></TD>
                               <TD height="26" colspan="2" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Visit_Packs[PicSay];//图释?> </TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 align=left>&nbsp; 640 x 480 </TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_i;?></TD>
                               <TD width="246" height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $scr_is = (round(($scr_i/$scr_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_is;?></TD>
                             </TR>
                             <TR class=row0>
                               <TD height=20 align=left>&nbsp;&nbsp;800 x 600 </TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_j;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $scr_js = (round(($scr_j/$scr_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_js;?></TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 align=left>&nbsp; 1024 x 768 </TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_k;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $scr_ks = (round(($scr_k/$scr_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_ks;?></TD>
                             </TR>
                             <TR class=row0>
                               <TD height=20 align=left>&nbsp; 1152 x 864</TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_l;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $scr_ls = (round(($scr_l/$scr_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_ls;?></TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 align=left>&nbsp; 1280 x 1024</TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_q;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $scr_qs = (round(($scr_q/$scr_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_qs;?></TD>
                             </TR>
                             <TR class=row0>
                               <TD height=20 align=left>&nbsp;&nbsp;<?php echo $Basic_Command['Other'];?></TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_o;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $scr_os = (round(($scr_o/$scr_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $scr_os;?></TD>
                             </TR>
                                </TABLE>						 
						 <br>
						 <br>-->
<!-- 						 <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
                           <TBODY>
                             <TR align=middle class=row0>
                               <TD width="229" height=26 align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Visit_Packs[OS];//操作系统?> </TD>
                               <TD width="165" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Visit_Packs[VisitDetail] ;//统计结果?></TD>
                               <TD height="26" colspan="2" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Visit_Packs[PicSay];//图释?>  </TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 align=left>&nbsp;&nbsp;Windows 98</TD>
                               <TD height=20 align="center" noWrap><?php echo $os_i;?></TD>
                               <TD width="246" height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $os_is = (round(($os_i/$os_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $os_is;?></TD>
                             </TR>
                             <TR class=row0>
                               <TD height=20 align=left>&nbsp;&nbsp;Windows ME </TD>
                               <TD height=20 align="center" noWrap><?php echo $os_j;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $os_js = (round(($os_j/$os_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $os_js;?></TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 align=left>&nbsp;&nbsp;Windows 2000</TD>
                               <TD height=20 align="center" noWrap><?php echo $os_k;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $os_ks = (round(($os_k/$os_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $os_ks;?></TD>
                             </TR>
                             <TR class=row0>
                               <TD height=20 align=left>&nbsp;&nbsp;Windows XP</TD>
                               <TD height=20 align="center" noWrap><?php echo $os_l;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $os_ls = (round(($os_l/$os_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $os_ls;?></TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 align=left> &nbsp;&nbsp;Macintosh</TD>
                               <TD height=20 align="center" noWrap><?php echo $os_q;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $os_qs = (round(($os_q/$os_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $os_qs;?></TD>
                             </TR>
                             <TR class=row0>
                               <TD height=20 align=left>&nbsp;&nbsp;<?php echo $Basic_Command['Other'];?></TD>
                               <TD height=20 align="center" noWrap><?php echo $os_o;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $os_os = (round(($os_o/$os_total),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $os_os;?></TD>
                             </TR>
                          </TABLE>	-->					 
						 <table width="100%"  border="0" align="center" class="p9orange">
                           <tr>
                             <td align="center">&nbsp;</td>
                           </tr>
                           <tr>
                             <td align="center"><?php echo $Visit_Packs[VisitTotal_Say] ;//参与统计的资料总数?>：<?php echo $TotalNum?></td>
                           </tr>
                         </table>
						  <?php
							}else{
						  ?>
						  <table width="100%"  border="0" align="center" class="p9orange">
                           <tr>
                             <td align="center">&nbsp;</td>
                           </tr>
                           <tr>
                             <td align="center"><?php echo $Visit_Packs[NoVisit_Say];//没有参与统计的资料?></td>
                           </tr>
                         </table>
						 <?php } ?>
						 </TD>
                      </TR>
                    </TBODY></TABLE></TD>
              </TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
              
  </TBODY>
</TABLE><br>
<br>
<br>

                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
