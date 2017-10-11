<?php
include_once "Check_Admin.php";
include      "../language/".$INFO['IS']."/Visit_Pack.php";
//$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()) ;
$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y",time())."-01-01" ;
$Endtime = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time()) ;

$Sql     = " select count(visit_ip_id) as totalvisit,visit_ip from `{$INFO[DBPrefix]}visit_ip` where visit_idate >='".$Begtime."' and visit_idate <= '".$Endtime."' and visit_ip!=''  group by visit_ip  order by totalvisit desc ";
$Query   = $DB->query($Sql);
$Num     = $DB->num_rows($Query);

?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<LINK id=css href="../css/calendar.css" type='text/css' rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--><?php echo $Visit_Packs[VisIpTotal];//來訪IP排名?>
</TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<SCRIPT src="../js/common.js"  language="javascript"></SCRIPT>
<SCRIPT src="../js/calendar.js"   language="javascript"></SCRIPT>
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
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--><?php echo $Visit_Packs[VisIpTotal];//來訪IP排名?></SPAN></TD>
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

					   <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0>
                           <TBODY>
                             <TR align=middle class=row1>
                               <TD width="100"  align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>&nbsp;</TD>
                               <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Visit_Packs[UserIp];//IP地址?></TD>
                               <TD width="80"  colspan="2" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Visit_Packs[UserVisitNum];//访问次数?></TD>
                              </TR>
	             			<?php
	             			if ($Num>0){
	             				$i=1;
	             				$TotalNum = 0;
	             				while ($Result = $DB->fetch_array($Query)){
						    ?>   
                             <TR class=row0>
                               <TD width="100" height=26 align=center>&nbsp;</TD>
                               <TD height=26 align="left" noWrap><?php echo $Result['visit_ip'];?></TD>
                               <TD height=26 colspan="2" align="center" noWrap><?php echo $Result['totalvisit'];?></TD>
                               </TR>
   						    <?php
   						    $TotalNum = $TotalNum+$Result['totalvisit'];
   						    $i++;
	             				}
						    ?>
							  <TR align="right">
							   <TD height=26 colspan="4" class="p9orange"><br>
							     <br>
							     <?php echo $Visit_Packs[VisitTotal_Say] ;//参与统计的资料总数：?>：<?php echo $TotalNum?></TD>
							   </TR>
   						    <?php
	             			}else{
						    ?>
							 <TR class=row1>
                               <TD height=26 colspan="4" align=center class="p9orange"><?php echo $Visit_Packs[NoVisit_Say];//没有参与统计的资料?></TD>
                               </TR>
   						    <?php
	             			}
						    ?>
					     </TABLE>
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
