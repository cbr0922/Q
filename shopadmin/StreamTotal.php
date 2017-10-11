<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/Visit_Pack.php";
//include_once('./htmlfunction/Create3DStat.php');
//$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()) ;
$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y",time())."-01-01" ;
$Endtime = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time()) ;

$Sql     = " select visit_ip,user_id,visit_idate from `{$INFO[DBPrefix]}visit_ip` where visit_idate >='".$Begtime."' and visit_idate <= '".$Endtime."' order by visit_idate desc ";
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
<?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--><?php echo $Visit_Packs[StreamTotal];//访问量分析?>
</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
<script language="JavaScript">
function setGraphType(type) {
	return;
}
</script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
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
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--><?php echo $Visit_Packs[StreamTotal];//访问量分析?></SPAN></TD>
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
					   <br>
					   <FORM name=form1 method=get>
					     <br>
					     <span class="p9black"><?php echo $Visit_Packs[VisFrom];//从?>&nbsp; 
					     <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=begtime size=10  onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $Begtime;?>' name='begtime'> 
						 
					    &nbsp;<?php echo $Visit_Packs[VisTo];//至?>&nbsp; 
					   <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  id=endtime size=10   onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $Endtime;?>' name='endtime'>
					 					 
					   <INPUT class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'" type=submit value=<?php echo $Visit_Packs[ReSearch] ;//重新查询?>>
					   </span>

					   
					    </FORM>
						<br><Br>
                    <?php
                    if ($Num>0){
                    	$HaveMember = array();
                    	while ($Result = $DB->fetch_array($Query)){
                    		if ($Result['user_id']!=0){
                    			$HaveMember[] = $Result['user_id'];  //获得注册用户的数量
                    		}
                    	}
                    	unset ($Sql);
                    	unset ($Query);
                    	unset ($Num);
                    	unset ($Result);

                    	$i = 0 ;
                    	$j = 0 ;
                    	$k = 0 ;
                    	$l = 0 ;

                    	//$Sql     = " select sqrt(count(v.visit_ip)) as totalnum ,v.visit_ip,v.user_id from `{$INFO[DBPrefix]}visit_ip` v  inner join `{$INFO[DBPrefix]}visit_ip`  s  on (v.visit_ip=s.visit_ip) where v.visit_idate >='".$Begtime."' and v.visit_idate <= '".$Endtime."'  group by v.visit_ip  order by v.visit_idate desc ";

                    	$Sql  = "select distinct(visit_ip),count(visit_ip) as totalnum from `{$INFO[DBPrefix]}visit_ip` group by visit_ip order by totalnum desc ";

                    	$Query   = $DB->query($Sql);
                    	$Num     = $DB->num_rows($Query);
                    	if ($Num>0){
                    		while ($Result  = $DB->fetch_array($Query)){
                    			$totalnum  = $Result['totalnum'];
                    			if ( $totalnum > 5 and $totalnum<=10  )      { $i++ ; }
                    			if ( $totalnum>10 )                         { $j++ ; }
                    			if ( $totalnum==1 )                         { $k++ ; }
                    			if ( $totalnum > 1 and $totalnum <= 10 )    { $l++ ; }

                    		}

                    		unset ($Sql);
                    		unset ($Query);
                    		unset ($Num);
                    		unset ($totalnum);
                    		unset ($Result);

                    	}

                    	$Allnum = intval($i+$j+$k+$l)!=0 ? intval($i+$j+$k+$l) : 1 ;
						?>
						<table class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
							<tbody>
                               <tr>
                               	<td align="center" valign="top">
								<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="800px" height="375px" id="UrchinGraph" align="middle">
									<param name="allowScriptAccess" value="sameDomain" />
									<param name="movie" value="images/shopnc.swf" />
									<param name="quality" value="high" />
									<param name="bgcolor" value="#ffffff" />
									<param name="wmode" value="transparent" />
									<embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" name="UrchinGraph" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="275px" width="600px" flashvars="ntitle=<?php echo $Visit_Packs[User];//会员分类?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UserNum];//用户数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[RegUserTotal];//注册会员来访统计?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo $TotalNum;?>&xdata=<?php echo $Visit_Packs[CallInMemberNum];//来访的会员?>\t<?php echo count($HaveMember);?>\n<?php echo $Visit_Packs[NotCallInMemberNum];//未访问的会员数?>\t<?php echo $TotalNum-count($HaveMember);?>\n"  >
									<param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[User];//会员分类?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UserNum];//用户数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[RegUserTotal];//注册会员来访统计?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo $TotalNum;?>&xdata=<?php echo $Visit_Packs[CallInMemberNum];//来访的会员?>\t<?php echo count($HaveMember);?>\n<?php echo $Visit_Packs[NotCallInMemberNum];//未访问的会员数?>\t<?php echo $TotalNum-count($HaveMember);?>\n" />
								</object>
                               	</td>
                               
                               </tr>
                               <tr>
                               	<td align="center">
								<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="800" height="375" id="UrchinGraph" align="middle">
									<param name="allowScriptAccess" value="sameDomain" />
									<param name="movie" value="images/shopnc.swf" />
									<param name="quality" value="high" />
									<param name="bgcolor" value="#ffffff" />
									<param name="wmode" value="transparent" />
									<embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" name="UrchinGraph" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="275" width="600" flashvars="ntitle=<?php echo $Visit_Packs[User];//会员分类?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UserNum];//用户数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[RegUserTotal];//注册会员来访统计?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo count($HaveMember)+$j+$k+$l;?>&xdata=<?php echo $Visit_Packs[RegUser_I];//较有价值用户?>\t<?php echo count($HaveMember);?>\n<?php echo $Visit_Packs[RegUser_II];//极有价值用户?>\t<?php echo $j;?>\n<?php echo $Visit_Packs[RegUser_III];//首次到访用户?>\t<?php echo $k;?>\n<?php echo $Visit_Packs[RegUser_IV];//其它用户?>\t<?php echo $l;?>\n"  >
									<param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[User];//会员分类?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[UserNum];//用户数?>&cnames=&datatype=20&rtitle=<?php echo $Visit_Packs[RegUserTotal];//注册会员来访统计?>&fsize=1&gtypes=bar|hbar|pie&uloc= cn|$|0|2&total=<?php echo count($HaveMember)+$j+$k+$l;?>&xdata=<?php echo $Visit_Packs[RegUser_I];//较有价值用户?>\t<?php echo count($HaveMember);?>\n<?php echo $Visit_Packs[RegUser_II];//极有价值用户?>\t<?php echo $j;?>\n<?php echo $Visit_Packs[RegUser_III];//首次到访用户?>\t<?php echo $k;?>\n<?php echo $Visit_Packs[RegUser_IV];//其它用户?>\t<?php echo $l;?>\n" />
								</object>       
                               	</td>
                               </tr>
							</tbody>
						</table>					
					    <!--<TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
                           <TBODY>
                             <TR align=middle class=row0>
                               <TD width="83" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>&nbsp;
                               </TD>
                               <TD width="131"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Visit_Packs[VisitType];//客户性质?></TD>
                               <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>  <?php echo $Visit_Packs[VisitTJ];//到访总计?><br></TD>
                               <TD height="26" colspan="2" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>  <?php echo $Visit_Packs[PicSay];//图释?> </TD>
                               </TR>
                             <TR class=row1>
                               <TD height=20 colspan="2" align=left>  &nbsp;&nbsp;<?php echo $Visit_Packs[RegUser];//注册用户?> </TD>
                               <TD height=20 align="center" noWrap><?php echo $MemberNum=count($HaveMember);?></TD>
                               <TD width="246" height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $M_width = (round(($MemberNum/$TotalNum),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $M_width;?></TD>
                             </TR>
                            
                            <TR class=row0>
                               <TD height=20 colspan="2" align=left>&nbsp;&nbsp;<?php echo $Visit_Packs[RegUser_I];//较有价值用户?></TD>
                               <TD height=20 align="center" noWrap><?php echo $i;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $Mvp_width = (round(($i/$Allnum),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $Mvp_width;?></TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 colspan="2" align=left>&nbsp;&nbsp;<?php echo $Visit_Packs[RegUser_II];//极有价值用户?></TD>
                               <TD height=20 align="center" noWrap><?php echo $j;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $Mvvp_width = (round(($j/$Allnum),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $Mvvp_width;?></TD>
                             </TR>
                             <TR class=row0>
                               <TD height=20 colspan="2" align=left>&nbsp;&nbsp;<?php echo $Visit_Packs[RegUser_III];//首次到访用户?></TD>
                               <TD height=20 align="center" noWrap><?php echo $k;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $Mf_width = (round(($k/$Allnum),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $Mf_width;?></TD>
                             </TR>
                             <TR class=row1>
                               <TD height=20 colspan="2" align=left>&nbsp;&nbsp;<?php echo $Visit_Packs[RegUser_IV];//其它用户?></TD>
                               <TD height=20 align="center" noWrap><?php echo $l;?></TD>
                               <TD height=20 align="left" noWrap><img src="images/<?php echo $INFO[IS]?>/PollBar.gif" width="<?php echo $Mo_width = (round(($l/$Allnum),2)*100)."%"?>" height="22"></TD>
                               <TD height=20 align="center" noWrap><?php echo $Mo_width;?></TD>
                             </TR>

					     </TABLE>-->
						 <?php
			                    }
						 ?>
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
