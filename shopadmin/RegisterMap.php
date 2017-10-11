<?php
include_once "Check_Admin.php";
include_once Classes . "/GD_Drive.php";
include_once Classes . "/Time.class.php";
include_once Classes . "/SaleMap.class.php";
include      "../language/".$INFO['IS']."/Visit_Pack.php";


if ($_POST['action']=="search"){
	$current_year  = $_POST['selyear'];
	$current_month = $_POST['selmonth']>9 ? $_POST['selmonth'] : "0".$_POST['selmonth'] ;
}else{
	$current_year  = date("Y",time());
	$current_month = date("m",time());
}
$current_day   = date("d",time());

switch (intval($_POST['Daysize'])){
	case 1:
		$Daysize  = 1;
		$GDwidth  = "500";
		$GDheight = "600";
		break;
	case 2:
		$Daysize  = 2;
		$GDwidth  = "550";
		$GDheight = "600";
		break;
	case 3:
		$Daysize  = 3;
		$GDwidth  = "480";
		$GDheight = "640";
		break;
	case 4:
		$Daysize  = 4;
		$GDwidth  = "600";
		$GDheight = "800";
		break;
	case 5:
		$Daysize  = 5;
		$GDwidth  = "800";
		$GDheight = "1024";
		break;
	default:
		$Daysize  = 2;
		$GDwidth  = "500";
		$GDheight = "600";
		break;
}

$TimeClass = new TimeClass;
$DayNum = $TimeClass->getMonthLastDay($current_month,$current_year); //获得当前月天数

// (整个流程应该循环的数字,必须的SQL语句,)

//------------------------------------开始获得年中日分析
$Sql = " select count(user_id) as totaluser ,DATE_FORMAT(reg_date,'%d') as uday from `{$INFO[DBPrefix]}user` where DATE_FORMAT(reg_date,'%Y')='".$current_year."' and DATE_FORMAT(reg_date,'%m')='".$current_month."'   group by  uday  order by reg_date desc ";
$Query = $DB->query($Sql);
$tmp_date = array();
while ($Result = $DB->fetch_array($Query)) {
	$tmp_date[intval($Result['uday'])] = $Result['totaluser'];
}
$date = array();
for ($i = 1;$i<=$DayNum;$i++){
	if ($tmp_date == null) {
		$date[$i] = 0;
	}
	else {
		foreach ($tmp_date as $key => $value) {
			if ($i == $key) {
				$date[$i] = $value;
				break;
			}
			else {
				$date[$i] = 0;
			}
		}
	}
}
$date_value = "";
foreach ($date as $key => $value) {
	$date_value .= $key."day\\t".$value."\\n";
	$date_num += $value;
}



//------------------------------------开始获得年中月份的总资料


$Sql = " select count(user_id) as totaluser ,DATE_FORMAT(reg_date,'%m') as umonth from `{$INFO[DBPrefix]}user` where DATE_FORMAT(reg_date,'%Y')='".$current_year."'  group by  umonth  order by reg_date desc ";
$Query = $DB->query($Sql);
$tmp_m = array();
while ($Result = $DB->fetch_array($Query)) {
	$tmp_m[intval($Result['umonth'])] = $Result['totaluser'];
}
$month_array = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
$month_value = array();
for ($i = 1;$i<=12;$i++){
	if ($tmp_m == null) {
		$month_value[$i]['num'] = 0;
		$month_value[$i]['month'] = $month_array[$i-1];
	}
	else {
		foreach ($tmp_m as $key => $value) {
			if ($i == $key) {
				$month_value[$i]['num'] = $value;
				$month_value[$i]['month'] = $month_array[$i-1];
				break;
			}
			else {
				$month_value[$i]['num'] = 0;
				$month_value[$i]['month'] = $month_array[$i-1];
			}
		}
	}
}

$month_str = "";
foreach ($month_value as $value) {
	$month_str .= $value['month']."\\t".$value['num']."\\n";
	$month_num += $value['num'];
}


?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--><?php echo $Visit_Packs[RegisterMap];//注册情况分析?> </TITLE></HEAD>
<script language="javascript" src="../js/TitleI.js"></script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
<?php  include $Js_Top ;  ?>
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
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
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD></TR>
  </TBODY>
  </TABLE>



<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>

  
   <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1  src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
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
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"      width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--><?php echo $Visit_Packs[RegisterMap];//注册情况分析?> </SPAN></TD>
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
    <!--BUTTON_END-->						</TD>
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
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD vAlign=top bgColor=#ffffff height=300>
                  <TABLE  width="96%" height="300" border=0 align=center cellPadding=2 cellSpacing=0 bgColor=#f7f7f7 class=allborder>
                    <TBODY>
                    <TR>
                      <TD valign="top" noWrap>
					  <script language="javascript">	
					  function changecat(){
					  	form1.submit();
					  }
                       </script>
                       <script language="JavaScript">
						function setGraphType(type) {
							return;
						}
						</script>
					  <FORM method=POST action="" name="form1">
					  <table width="98%"  border="0" align="center" class="p9black">
                        <tr>
                          <td colspan="2" align="center">
						  
						  <input type="hidden" name="action" value="search">
						  <SELECT name='selyear'>
						  <?php for ($i=2002;$i<=intval(date("Y",time()));$i++) { ?>
						  <OPTION value='<?php echo  $i; ?>' <?php if ($i==$current_year){  echo "  selected ";} ?>><?php echo $i?></OPTION>
						  <?php } ?>
						  </SELECT>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;
						  <SELECT name='selmonth'>
						  <?php for ($j=1;$j<=12;$j++) { ?>
						  <OPTION value='<?php echo $j;?>' <?php if ($j==$current_month){  echo "  selected ";} ?>><?php echo $j;?></OPTION>						  
  						  <?php } ?>
						  </SELECT>
						  &nbsp;<?php echo $Visit_Packs[Month];//月?>&nbsp;						  
						  
		
						  <INPUT class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"  type=submit value=<?php echo $Visit_Packs[ReSearch] ;//重新查询?>> 
                          <INPUT type=hidden name=type> &nbsp;
			             </td>
                        </tr>
                        <tr>
                          <td align="center">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                      </table>
					    </FORM>		
                        <table width="98%"  border="0" align="center" >
             			  <tr>
             			  	<td align="center">
								<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="800px" height="375px" id="UrchinGraph" align="middle">
									<param name="allowScriptAccess" value="sameDomain" />
									<param name="movie" value="images/shopnc.swf" />
									<param name="quality" value="high" />
									<param name="bgcolor" value="#ffffff" />
									<param name="wmode" value="transparent" />
									<embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" name="UrchinGraph" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="275px" width="600px" flashvars="ntitle=<?php echo $Visit_Packs[RegDate];//注册日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[RegUserNum];//注册会员总数?>&cnames=&datatype=20&rtitle=<?php echo $current_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $current_month?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $Visit_Packs[Day_FX];//注册情况按日分析?>&fsize=1&gtypes=bar|line&uloc= cn|$|0|2&total=<?php echo $date_num;?>&xdata=<?php echo $date_value?>"  >
									<param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[RegDate];//注册日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[RegUserNum];//注册会员总数?>&cnames=&datatype=20&rtitle=<?php echo $current_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $current_month?><?php echo $Visit_Packs[Month];//月?>&nbsp;<?php echo $Visit_Packs[Day_FX];//注册情况按日分析?>&fsize=1&gtypes=bar|line&uloc= cn|$|0|2&total=<?php echo $date_num;?>&xdata=<?php echo $date_value?>" />
								</object>
             			  	</td>
             			  </tr> 
                          <tr>
                            <td align="center">&nbsp;</td>
                          </tr>
                         <tr>
                         	<td align="center">
								<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="800px" height="375px" id="UrchinGraph" align="middle">
									<param name="allowScriptAccess" value="sameDomain" />
									<param name="movie" value="images/shopnc.swf" />
									<param name="quality" value="high" />
									<param name="bgcolor" value="#ffffff" />
									<param name="wmode" value="transparent" />
									<embed src="images/shopnc.swf" quality="high" bgcolor="#ffffff" name="UrchinGraph" wmode="transparent" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" height="275px" width="600px" flashvars="ntitle=<?php echo $Visit_Packs[RegDate];//注册日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[RegUserNum];//注册会员总数?>&cnames=&datatype=20&rtitle=<?php echo $current_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $Visit_Packs[Month_FX] ;//注册情况按月分析?>&fsize=1&gtypes=bar|line&uloc= cn|$|0|2&total=<?php echo $month_num;?>&xdata=<?php echo $month_str?>"  >
									<param name="FlashVars" value="ntitle=<?php echo $Visit_Packs[RegDate];//注册日期?>&toggle=<?php echo $Visit_Packs[AllShow];//全部显示?>|<?php echo $Visit_Packs[AllHidden];//全部隐藏?>&vtitle=<?php echo $Visit_Packs[RegUserNum];//注册会员总数?>&cnames=&datatype=20&rtitle=<?php echo $current_year?>&nbsp;<?php echo $Visit_Packs[Year];//年?>&nbsp;<?php echo $Visit_Packs[Month_FX] ;//注册情况按月分析?>&fsize=1&gtypes=bar|line&uloc= cn|$|0|2&total=<?php echo $month_num;?>&xdata=<?php echo $month_str?>" />
								</object>
                         	</td>
                         </tr>
                          <tr>
                            <td align="center">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="center">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="center">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="center">&nbsp;</td>
                          </tr>
                        </table></TD>
                      </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif><IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>
  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
