<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
include      "../language/".$INFO['IS']."/Mail_Pack.php";

$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
$Sql      = "select * from `{$INFO[DBPrefix]}ticketpubrecord` as r inner join `{$INFO[DBPrefix]}ticket` as t on r.ticketid=t.ticketid where r.ticketid='" . $_GET['ticketid'] . "' order by r.recordid desc";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);
if ($Num>0){
	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;
	$Nav->total_result=$Num;
	$Nav->execute($Sql,$limit);
	$Query = $Nav->sql_result;
	$Nums     = $Num<$limit ? $Num : $limit ;
}	
	//echo "[".((strtotime($_POST['goods_endtime']) - strtotime($_POST['goods_starttime'])) / 31536000) ."]";
	
	/*echo $_POST['goods_starttime'];
	echo $_POST['goods_endtime'];
	echo "[".((strtotime($_POST['goods_endtime']) - strtotime($_POST['goods_starttime'])) / 31536000) ."]";
	
	echo $_POST['gender'];
	echo $_POST['month'];
	echo $_POST['goods_starttime'];
	echo $_POST['goods_endtime'];
	echo $_POST['gender'];
	echo $_POST['month'];
	echo $_POST['age_start'];
	echo $_POST['age_end'];
	
	echo $_POST['county'];
	echo $_POST['province'];
	echo $_POST['city'];
	echo $_POST['age_end'];
	echo $_POST['ifallarea'];
	echo $_POST['companyid'];
	echo $_POST['type_level'];*/
	//echo $INFO[DBPrefix];
	$Sql      = "select u.*  from `{$INFO[DBPrefix]}user` u left join `{$INFO[DBPrefix]}user_level` l on (u.user_level = l.level_id)";
	/************************************
	 * 
	 * 	gender
	 * 
	 ************************************/
	 
	$SqlWhere = "";
	$gender = $_POST['gender'];
	if( isset($gender) ){
		if( $gender == "male"){
			$SqlWhere.=" sex=0";
		}
		else if( $gender == "female"){
			$SqlWhere.=" sex=1";
		}
	}
	
	/************************************
	 * 
	 * 	month
	 * 
	 ************************************/
	 
	$month = $_POST['month'];
	if( isset($month) ){
		if( $month != 0){
			if($SqlWhere != "")
				$SqlWhere.=" AND";
				
			$SqlWhere.=" month(born_date)=".$month;
		}
	}
	
	/************************************
	 * 
	 *	age_start ~ age_end
	 * 
	 ************************************/
	 
	$time = getdate();
	//echo $time[year];
	$age_start = $_POST['age_start'];
	$age_end = $_POST['age_end'];
	if( ($age_start != "") && ($age_end != "") ){
		if($SqlWhere != "")
			$SqlWhere.=" AND";
		
		$SqlWhere.=" year(born_date) BETWEEN '".($time[year] - $age_end)."' AND '".($time[year] - $age_start)."'";
	}
	
	/************************************
	 * 
	 * 	goods_starttime ~ goods_endtime
	 * 
	 ************************************/
	 
	//$goods_starttime  = $_POST['goods_starttime']!="" ? $_POST['goods_starttime'] : date("Y-m-d",time()-7*24*60*60);
	//$goods_endtime  = $_POST['goods_endtime']!="" ? $_POST['goods_endtime'] : date("Y-m-d",time());
	$goods_starttime  = $_POST['goods_starttime'];
	$goods_endtime  = $_POST['goods_endtime'];
	if( ($goods_starttime != "") && ($goods_endtime != "") ){
		if($SqlWhere != "")
			$SqlWhere.=" AND";
		
		$SqlWhere.=" reg_date BETWEEN '".$goods_starttime."' AND '".$goods_endtime."'";
	}

	/************************************
	 * 
	 * 	county
	 * 
	 ************************************/
	 
	$county = $_POST['county'];
	$province = $_POST['province'];
	$city = $_POST['city'];
	$ifallarea = $_GET['ifallarea'];
	
	if( $county != "" && $ifallarea != 1){
		if($SqlWhere != "")
			$SqlWhere.=" AND";
			
		$SqlWhere.=" Country='".$county."'";
		
		if($province != ""){	
			$SqlWhere.=" AND canton='".$province."'";
		}
		
		if($city != ""){	
			$SqlWhere.=" AND city='".$city."'";
		}
	}
	/*$post_county = $_POST['county'];
	$post_city = $_POST['city'];
	$post_zone = $_POST['zone'];
	$ifallarea = $_GET['ifallarea'];
	if( $post_city != "" && $ifallarea != 1){
		if($SqlWhere != "")
			$SqlWhere.=" AND";
		
		$Sql_type = "select u.areaname from `ntssi_area` u where area_id=".$post_county;
		$Query_c_type    = $DB->query($Sql_type);
		$Num_area_type      = $DB->num_rows($Query_c_type);
		$verbuf = "";
		while ($Rs=$DB->fetch_array($Query_c_type)) {
			$post_county = $Rs['areaname'];
			$SqlWhere.=" Country='".$post_county."'";
		}
		
    if($post_city != "0" && $post_city != ""){		
  		$Sql_type = "select u.areaname from `ntssi_area` u where area_id=".$post_city;
  		$Query_c_type    = $DB->query($Sql_type);
  		$Num_area_type      = $DB->num_rows($Query_c_type);
  		$verbuf = "";
  		while ($Rs=$DB->fetch_array($Query_c_type)) {
  			$post_city = $Rs['areaname'];
  			$SqlWhere.=" AND canton='".$post_city."'";
  		}
    }
		
		if($post_zone != "0" && $post_zone != ""){
  		$Sql_type = "select u.areaname from `ntssi_area` u where area_id=".$post_zone;
  		$Query_c_type    = $DB->query($Sql_type);
  		$Num_area_type      = $DB->num_rows($Query_c_type);
  		$verbuf = "";
  		while ($Rs=$DB->fetch_array($Query_c_type)) {
  			$post_zone = $Rs['areaname'];
  			$SqlWhere.=" AND city='".$post_zone."'";
  		}   
    }
	}*/
	
	/************************************
	 * 
	 * 	user_level
	 * 
	 ************************************/
	 
	$user_level = $_POST['type_level'];
	if( $user_level != 0){
		if($SqlWhere != "")
			$SqlWhere.=" AND";
				
		$SqlWhere.=" user_level=".$user_level;
	}

	
	/************************************
	 * 
	 * 	companyid
	 * 
	 ************************************/
	
	$companyid = $_POST['companyid'];
	if( $companyid != 0){
		if($SqlWhere != "")
			$SqlWhere.=" AND";
				
		$SqlWhere.=" companyid=".$companyid;
	}
	
	/************************************
	 * 
	 * 	type
	 * 
	 ************************************/
	 $type = $_POST['type'];
	if( $type != "all" && $type != "none" && $type != ""){
		if($SqlWhere != "")
			$SqlWhere.=" AND";
				
		$SqlWhere.=" dianzibao='".$type."'";
	}

	if($SqlWhere != "")
		$SqlWhere = " where ".$SqlWhere;
	
	$Sql = $Sql.$SqlWhere." order by u.reg_date desc";
	//echo $Sql;
	$Query_c    = $DB->query($Sql);
	$Num_area      = $DB->num_rows($Query_c);
	$dbCount = 0;
	$dbArray;
	while ($Rs=$DB->fetch_array($Query_c)) {
		$dbArray[$dbCount] = $Rs['reg_date'];
		$dbCount++;
		//echo $Rs['reg_date'] ."|||";
	}
	
	$x_side;
	$x_count = 0;
	$y_side;
	$y_count = 0;
  
  $show_year = 0;
  $show_month = 0;
  
  if( $dbArray[0] == "" && $dbArray[$dbCount-1] == ""){
    $x_side[$x_count] = $time[year]; 
    $y_side[$x_count] = 0;
    $x_count++;
  }
  else{
  	if((strtotime($dbArray[0]) - strtotime($dbArray[$dbCount-1]))/ (60*60*24*365) >= 1){ //跨年
  		$x_side[$x_count] = date("Y",strtotime($dbArray[0])); 
  		$x_count++;
  		$y_count++;
  		for( $j=1; $j<$dbCount; $j++){
  			if( $x_side[$x_count-1] != date("Y",strtotime($dbArray[$j])) ){
  				$x_side[$x_count] = date("Y",strtotime($dbArray[$j]));
  				
  				$y_side[$x_count-1] = $y_count;
  
  				$y_count = 0;
  				$x_count++;
  			}
  			
  			$y_count++;
  			//$y_side[$x_count-1]++;
  		}
  		$y_side[$x_count-1] = $y_count;
  	}
  	else if((strtotime($dbArray[0]) - strtotime($dbArray[$dbCount-1]))/ (60*60*24*30) >= 1){ //跨月
  		$x_count = 12;
  		$x_side[0] = "Jan";
  		$x_side[1] = "Feb";
  		$x_side[2] = "Mar";
  		$x_side[3] = "Apr";
  		$x_side[4] = "May";
  		$x_side[5] = "Jun";
  		$x_side[6] = "Jul";
  		$x_side[7] = "Aug";
  		$x_side[8] = "Sep";
  		$x_side[9] = "Oct";
  		$x_side[10] = "Nov";
  		$x_side[11] = "Dec";
  		
  		for($j=0; $j<$x_count;$j++){
  			$y_side[$j] = 0;
  		}
  		
      $show_year = (date("Y",strtotime($dbArray[0])) + 0 );
  		for( $j=0; $j<$dbCount; $j++){
  			$y_count = (date("m",strtotime($dbArray[$j])) + 0 );
  			$y_side[$y_count - 1]++;
  		}
  	}
  	else{ //月內
  		$x_count = 30;
  		for($j=0; $j<$x_count;$j++){
  			$x_side[$j] = ($j+1);
  		}
  		
  		for($j=0; $j<$x_count;$j++){
  			$y_side[$j] = 0;
  		}
  		
      $show_year = (date("Y",strtotime($dbArray[0])) + 0 );
      $show_month = (date("m",strtotime($dbArray[0])) + 0 );
  		for( $j=0; $j<$dbCount; $j++){
  			$y_count = (date("d",strtotime($dbArray[$j])) + 0 );
  			$y_side[$y_count - 1]++;
  			//echo (date("d",strtotime($dbArray[$j])) + 0 ) . "||";
  		}
  	}
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<TITLE>行銷工具--&gt;進階客戶關係管理</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php include_once "head.php";?>


<script language="javascript">
function toExprot(){
	form2.submit();
}
</script>



<form name="form2" method="post" action="admin_crm_member_report_excel.php" target='_blank'  >
	<input type="hidden" name="Action" value="Excel">
</form>

<SCRIPT language=javascript>	

function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}
	else{
		checkvalue = id;
	}
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_ticket.php?Action=Modi&ticketid="+checkvalue;
		document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}
function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_ticket_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}

function toExecl(){
		document.form1.action = "admin_crm_member_report_excel.php";
		document.form1.submit();
}
function toMailGroup(){
		document.form1.action = "admin_crm_member_report_save.php";
		document.form1.target = "_blank";
		document.form1.submit();
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
							<TD width=38 height="49"><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>						
							<TD class=p12black noWrap>
							<SPAN class=p9orange>行銷工具--&gt; 進階客戶關係管理 --&gt; 註冊會員統計</SPAN>
						</TD>
					</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD align=right width="50%">&nbsp;</TD>
		</TR>

	</TBODY>
	</TABLE>

	<table class="allborder" style="margin-bottom:10px" width="100%">
		<tr>
		<td>
		<TABLE class=p12black cellSpacing=0 cellPadding=0 width="85%"   align=center border=0 >		
			<FORM name=form2 id=form2 method=post action="">        			
				<input type="hidden" name="Action" value="Search">
						<TR>
							<TD height=19 colspan="3" align=right></TD>			
						</TR>

		<TR>			
			<TD width="160" height="30" align=left class=p9black>註冊日期：</TD>			
			<TD height="1" colspan="2" align=left class=p9black>起始日期				
			<input  onMouseOver="this.className='box2'" id=begtime size=10 
						onMouseOut="this.className='box1'"
						onClick="showcalendar(event, this)" 
						onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" 
						value='<?php echo $goods_starttime;?>' 
						name='goods_starttime'>						- 結束日期
								<input  onMouseOver="this.className='box2'" id=endtime size=10 
						onMouseOut="this.className='box1'"
						onClick="showcalendar(event, this)" 
						onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  
						value='<?php echo $goods_endtime;?>' 
						name='goods_endtime'>
			</TD>		
		</TR>

		<TR>
			<TD height="30" align=left class=p9black>性別：</TD>
			<TD height="0" colspan="2" align=left class=p9black>
			<input type="radio" value="none" name="gender" checked="checked" /> 不限
			<input type="radio" value="male" name="gender" <?php if($_POST['gender'] == "male") echo "checked";?> /> 男
			<input type="radio" value="female" name="gender" <?php if($_POST['gender'] == "female") echo "checked";?> /> 女
			</TD>
		</TR>


		<TR>
			<TD height="30" align=left class=p9black>生日月份：</TD>
			<TD height="0" colspan="2" align=left class=p9black>月份
				<select name="month">        
        <option value="0">請選擇</option>
				<?php for($k=1;$k<=12;$k++){  ?>					
					<option value="<?php echo $k;?>" <?php if($_POST['month'] == $k) echo "selected";?>><?php echo $k;?></option>
				<?php }  ?>
				</select>
			</TD>
		</TR>


		<TR>
			<TD height="30" align=left class=p9black>年齡：</TD>
			<TD height="0" colspan="2" align=left class=p9black>
				<input name="age_start" value="<?php echo $_POST['age_start'];?>" size="4"> 歲 -
				<input name="age_end" value="<?php echo $_POST['age_end'];?>" size="4"> 歲
			</TD>		</TR>
					
					
		<TR>
			<TD height="30" align=left class=p9black>地區：</TD>
			<TD height="0" colspan="2" align=left class=p9black>
				<input name="othercity" id="othercity" size="5" style="display:none">
				 <select id="county" name="county">

                    </select>

                    <select id="province" name="province">

                    </select>

                    <select id="city" name="city">

                    </select>

				<input name="ifallarea" type="checkbox" id="ifallarea" 
					value="1" <?php if($_GET['ifallarea']=="1") echo "checked";?> />					不限地區
			</TD>
		</TR>
							
		<TR>
			<TD height="30" align=left class=p9black>經銷商：</TD>
			<TD height="0" colspan="2" align=left class=p9black>
				<?php
					$company = "";
					$Sql      = "select u.*  from `{$INFO[DBPrefix]}saler` u order by u.id desc";
					$Query_c    = $DB->query($Sql);
					$Num_area      = $DB->num_rows($Query_c);
					while ($Rs=$DB->fetch_array($Query_c)) {
						$company .="<option value=".$Rs['id']." ";
						
						if ($_GET['companyid'] == $Rs['id'])
							$company .= " selected";
						$company .= " >".$Rs['name']."</option>\n";
					}				
					?>				
					<select name="companyid">					
			<option value="0">請選擇經銷商</option><?php echo $company;?>				
			</select>
			</TD>		
		</TR>
		
		<TR>
			<TD height="30" align=left class=p9black>會員等級：</TD>
			<TD height="30" colspan="2" align=left class=p9black>	
				<?php
					$company = "";
					$Sql      = "select u.*  from `{$INFO[DBPrefix]}user_level` u order by u.level_id desc";
					$Query_c    = $DB->query($Sql);
					$Num_area      = $DB->num_rows($Query_c);

					while ($Rs=$DB->fetch_array($Query_c)) {
						$company .="<option value=".$Rs['level_id']." ";
						
						if ($_GET['type_level'] == $Rs['level_id'])
							$company .= " selected";

						$company .= " >".$Rs['level_name']."</option>\n";
					}
				?>	  
				<select name="type_level">
					<option value="0">請選擇會員等級</option>
					<?php echo $company;?>
				</select>
			</TD>		</TR>

		<TR>
			<TD height="30" align=left class=p9black>訂閱電子報：</TD>		<TD height="30" colspan="2" align=left class=p9black>
				<input type="radio" name="type" value="all" checked="checked" />						所有會員				
				<input type="radio" name="type" value="1" <?php if($_POST['type'] == "1") echo "checked";?>/> 
							是				<input type="radio" name="type" value="0" <?php if($_POST['type'] == "0") echo "checked";?>/> 
							否				<!--<input type="radio" name="type" value="none"/> 
							非會員訂閱-->
			</TD>		
		</TR>
			
			
			
		<TR>			
			<TD height="30" align=left class=p9black>&nbsp;</TD>			
			<TD height="30" colspan="2" align=left class=p9black>
				<input type="submit" value="送出結果" />
			</TD>		
		</TR>

		<TR>
			<TD height="19" colspan="3" align=left class=p9black>&nbsp;</TD>	
		</TR>
		
	</FORM>		
	</TABLE>		
	</td>		
	</tr>	
	</table>


	<TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">	<TBODY>
		<TR>
		<TD vAlign=top height=210>
		<table class="p12black" cellspacing="0" cellpadding="0" width="85%"   align="center" border="0" >
		<form name="form1" id="form2" method="post" action=""><!--admin_ticketrecord_save-->
			<input type="hidden" name="Action2" value="Search" />
			<tr>				<td height="19" colspan="5" align="right"></td>			</tr>
			
			<input type="hidden" name="post_goods_starttime" id="post_goods_starttime" value="<?php echo $_POST['goods_starttime']?>">
			<input type="hidden" name="post_goods_endtime" id="post_goods_endtime" value="<?php echo $_POST['goods_endtime']?>">
			<input type="hidden" name="post_gender" id="post_gender" value="<?php echo $_POST['gender']?>">
			<input type="hidden" name="post_month" id="post_month" value="<?php echo $_POST['month']?>">
			<input type="hidden" name="post_ifallarea" id="post_ifallarea" value="<?php echo $ifallarea ?>">
			<input type="hidden" name="post_age_start" id="post_age_start" value="<?php 
																						if( $_POST['age_start'] != "" )
																							echo ($time[year] - $age_start);
																					?>">
			<input type="hidden" name="post_age_end" id="post_age_end" value="<?php 
																					if( $_POST['age_end'] != "" )
																						echo ($time[year] - $age_end); 
																				?>">
			<input type="hidden" name="post_county" id="post_county" value="<?php echo $_POST['county']?>">
			<input type="hidden" name="post_province" id="post_province" value="<?php echo $_POST['province']?>">
			<input type="hidden" name="post_city" id="post_city" value="<?php echo $_POST['city']?>">
			<input type="hidden" name="post_companyid" id="post_companyid" value="<?php echo $_POST['companyid']?>">
			<input type="hidden" name="post_type_level" id="post_type_level" value="<?php echo $_POST['type_level']?>">
			<input type="hidden" name="post_dianzibao" id="post_dianzibao" value="<?php echo $_POST['type']?>">
			
			<tr>
				<td width="96" align="left" class="p9black">
					<input type="submit" value="匯出execl表" onclick="javascript:toExecl()"/>
					<input type="submit" value="保存郵件組" onclick="javascript:toMailGroup()"/>
				</td>
				<!--<td width="74" height="1" align="left" class="p9black"><input type="submit" value="儲存名單" /></td>-->
				<td width="532" align="left" class="p9black"><i class="icon-warning-sign" style="font-size:16px;color:#C00">
					</i> 匯出會員欄位請至 " <a href="admin_member_excel_sys.php">會員匯入匯出欄位設定</a> "
				</td>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black></TD>
			</tr>
						<tr>				<td width="142" height="30" align="left" class="p9black">會員人數：</td>				
						<td width="93" height="1" align="left" class="p9black"><?php echo $dbCount  ?>人</td>			</tr>
			<!--<tr>
				<td height="30" align="left" class="p9black">圖表：</td>
				<td height="0" colspan="4" align="left" class="p9black">只要長條圖和折線圖，Y軸人數，X軸日期(按月或其他可能切換)</td>
			</tr>-->
			
			<tr>
				<td height="0" colspan="4" align="left" class="p9black">
					<canvas id="born" height="350" width="560" style="width: 400px; height: 250px;"></canvas>
				</td>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black></TD>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black>
					註冊日期：
					<?php
					if( ( $_POST['goods_starttime'] != "" ) && ( $_POST['goods_endtime'] != "" ) ){
						echo $_POST['goods_starttime']." ~ ". $_POST['goods_endtime'];
					}
					else{
						/*if( $show_year != 0)
							echo $show_year;              
						if( $show_month != 0)
							echo "/".$show_month;*/
						echo "no data";	
					}
					
					?>
				</TD>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black>
					性別：
					<?php
					if( isset($_POST['gender']) ){
						echo $_POST['gender'];
					}
					else{
            			echo "no data";
          			}
					?>
				</TD>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black>
					生日月份：
					<?php
					if( $_POST['month'] != 0 ){
						echo $_POST['month'];
					}
					else{
						echo "no data";
					}
					?>
				</TD>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black>
					年齡：
					<?php
					if( ( $_POST['age_start'] != "" ) && ($_POST['age_end'] != "" ) ){
						echo $_POST['age_start']." ~ ". $_POST['age_end'];
					}
					else{
						echo "no data";
					}
					?>
				</TD>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black>
					地區：
					<?php
					if( ($county != "") && ($ifallarea != 1)){
						$msg = $county;
						if( $province != "" ){
						  $msg .= ", ".$province;
						}
						if( $city != "" ){
						  $msg .= ", ".$city;
						}
						echo $msg;
					}
					else{
						echo "no data";
					}
					?>
				</TD>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black>
					經銷商：
					<?php
					if( $_POST['companyid'] != 0 ){
						echo $_POST['companyid'];
					}
					else{
						echo "no data";
					}
					?>
				</TD>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black>
					會員等級：
					<?php
					if( $_POST['type_level'] != 0 ){
						echo $_POST['type_level'];
					}
					else{
						echo "no data";
					}
					?>
				</TD>
			</tr>
			
			<tr>
				<TD height="30" align=left class=p9black>
					訂閱電子報：
					<?php

					if( $_POST['type'] == "0"){
						echo 'no';
					}
					else if( $_POST['type'] =="1"){
						echo 'yes';
					}
					else {
						echo "no data";
					}
					?>
				</TD>
			</tr>
						
			<tr>				
				<td height="30" align="left" class="p9black">&nbsp;</td>
				<td height="0" colspan="4" align="left" class="p9black">&nbsp;</td>			
			</tr>
			<tr></tr>
			<tr>
				<td height="19" colspan="5" align="left" class="p9black">&nbsp;</td>
				</tr>		
				</form>		
				</table>
		</TD>		
		</TR>
	</TABLE>
	
</div>

<script src="include/Chart.js"></script>
<script language="javascript" src="../js/area.js" charset="utf-8"></script>
<script language="javascript" src="../js/modi_bigarea1.js"></script>
<script language="javascript">
	
	var test = {
		labels : [	<?php for($j=0;$j<$x_count;$j++){
							echo "\"".$x_side[$j]."\",";
						}?>],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
                strokeColor : "rgba(151,187,205,1)",
				data : [<?php for($j=0;$j<$x_count;$j++){
							echo "\"".$y_side[$j]."\",";
						}?>]
			}
		]
	}

	new Chart(document.getElementById("born").getContext("2d")).Bar(test);
	
	/*var county = document.getElementById("county");
	toChangeCity( county );*/
	iniArea("",1,"","","");
	//initCounty2(document.getElementById("province_new"), "<?php echo trim($_GET[province_new])?>")
	//initZone2(document.getElementById("province_new"), document.getElementById("city_new"), document.getElementById("othercity"), "<?php echo trim($_GET[city_new])?>")
</script>

<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
