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
	echo $_POST['count'];
    echo $_POST['item_id'];
    echo $_POST['buymoney'];

    echo $_POST['recommendmember'];
	echo $_POST['bonusrecord'];
	echo $_POST['state'];
	echo $_POST['ticketrecord'];
	 */

    //echo $INFO[DBPrefix];


	/************************************
	 *
	 * 	goods_starttime ~ goods_endtime
	 *
	 ************************************/

	$goods_starttime  = $_POST['goods_starttime']!="" ? $_POST['goods_starttime'] : date("Y-m-d",time()-7*24*60*60);
	$goods_endtime  = $_POST['goods_endtime']!="" ? $_POST['goods_endtime'] : date("Y-m-d",time());

	/*if( ($goods_starttime != "") && ($goods_endtime != "") ){
		if($SqlWhere != "")
			$SqlWhere.=" AND";

		$SqlWhere.=" reg_date BETWEEN '".$goods_starttime."' AND '".$goods_endtime."'";
	}
    */


 	$Sql = "select u.* from `{$INFO[DBPrefix]}user` u";


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
	 * 	login_count
	 *
	 ************************************/

	$login_count = $_POST['login_count'];
	if( isset($login_count) ){
		if( $login_count != 0){
			$Sql.=" JOIN (SELECT `user_id` FROM `{$INFO[DBPrefix]}user_log` WHERE `logintime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `user_id` HAVING COUNT(*) >= '".$login_count."') l ON (u.`user_id`=l.`user_id`)";
		}
	}

	/************************************
	 *
	 *	buymoney_start ~ buymoney_end
	 *
	 ************************************/

	$buymoney_start = $_POST['buymoney_start'];
	$buymoney_end = $_POST['buymoney_end'];

	if($buymoney_start != "" && $buymoney_end != ""){
		if($buymoney_start == 0 && $buymoney_end == 0){
			$Sql.=" JOIN (SELECT a.`user_id` FROM `{$INFO[DBPrefix]}user` a LEFT JOIN ";
			$Sql.="(SELECT `user_id` FROM `{$INFO[DBPrefix]}order_table` WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND order_state=4 GROUP BY `user_id` HAVING SUM(`totalprice`) > 0) b ON (a.`user_id`=b.`user_id`) where b.`user_id` is null) t ON (u.`user_id`=t.`user_id`)";
		}else{
			$Sql.=" JOIN (SELECT `user_id`,SUM(`totalprice`) as totalprice FROM `{$INFO[DBPrefix]}order_table` WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND order_state=4 GROUP BY `user_id` HAVING SUM(`totalprice`) BETWEEN '".$buymoney_start."' AND '".$buymoney_end."') t ON (u.`user_id`=t.`user_id`)";
		}
	}

	/************************************
	 *
	 * 	item_id
	 *
	 ************************************/

	$item_id = $_POST['item_id'];
	if( isset($item_id) ){
		if( $item_id != ""){			$subSql = " AND  d.`gid` IN (" . implode(",",array_filter(explode(",",$item_id))) . ")";
			$Sql.=" JOIN (SELECT `user_id`,t.`order_id` FROM `{$INFO[DBPrefix]}order_table` t,`{$INFO[DBPrefix]}order_detail` d WHERE `createtime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND t.`order_id` = d.`order_id` AND t.`order_state` = 4 ".$subSql." GROUP BY `user_id`) o ON (u.`user_id`=o.`user_id`)";
		}

	}

	/************************************
	 *
	 * 	recommendmember
	 *
	 ************************************/

	$recommendmember = $_POST['recommendmember'];
	if( isset($recommendmember) ){
		if( $recommendmember != 0){
			$Sql.=" JOIN (SELECT `user_id`,`recommendno` FROM `{$INFO[DBPrefix]}user` WHERE `recommendno`!='') r ON (u.`user_id`=r.`user_id`)";
		}
	}


	/************************************
	 *
	 *	bonusbuydetail_start ~ bonusbuydetail_end
	 *
	 ************************************/

	$bonusrecord_start = $_POST['bonusrecord_start'];
	$bonusrecord_end = $_POST['bonusrecord_end'];
	if( ($bonusrecord_start != "") && ($bonusrecord_end != "") ){
		$Sql.=" JOIN (SELECT a.`user_id` , (a.`point` - IFNull(b.`point`,0)) AS point FROM ";
		$Sql.="(SELECT SUM(`point`) AS point ,`user_id` FROM `{$INFO[DBPrefix]}bonuspoint` WHERE UNIX_TIMESTAMP('".$goods_starttime."') <= `addtime` AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') >= `addtime` AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') <= `endtime` AND `saleorlevel`=1 GROUP BY `user_id`) a LEFT JOIN ";
		$Sql.="(SELECT SUM(`usepoint`) AS point,bd.`user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` bd INNER JOIN  `{$INFO[DBPrefix]}bonuspoint` bp ON bd.`combipoint_id`=bp.`id` WHERE UNIX_TIMESTAMP('".$goods_starttime." 23:59:59') >= bp.`addtime` AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') <= bp.`endtime` AND bd.`usetime` <= UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') AND bp.`saleorlevel`=1 GROUP BY bd.`user_id`) b ";
		$Sql.="ON a.`user_id`=b.`user_id` WHERE (a.`point` - IFNull(b.`point`,0)) BETWEEN '".$bonusrecord_start."' AND '".$bonusrecord_end."') p ON (u.`user_id`=p.`user_id`)";
	}

	/************************************
	 *
	 * 	bonusbuydetail
	 *
	 ************************************/

	 $bonusbuydetail = $_POST['bonusbuydetail'];
 	if( isset($bonusbuydetail) && $bonusbuydetail != "" ){
 		if( $bonusbuydetail == 0){
 			$Sql.=" JOIN (SELECT a.`user_id` FROM `{$INFO[DBPrefix]}user` a LEFT JOIN ";
 			$Sql.="(SELECT `user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` WHERE `usetime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `user_id`) b ON (a.`user_id`=b.`user_id`) where b.`user_id` is null) b ON (u.`user_id`=b.`user_id`)";
 		}elseif( $bonusbuydetail == 1){
 			$Sql.=" JOIN (SELECT `user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` WHERE `usetime` BETWEEN UNIX_TIMESTAMP('".$goods_starttime."') AND UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `user_id`) b ON (u.`user_id`=b.`user_id`)";
 		}
 	}

	/************************************
	 *
	 * 	ticketrecord
	 *
	 ************************************/

	$ticketrecord = $_POST['ticketrecord'];
	if( isset($ticketrecord) ){
		if( $ticketrecord != 0){
			$Sql.=" JOIN (SELECT `userid` FROM `{$INFO[DBPrefix]}use_ticket` WHERE `usetime` >= UNIX_TIMESTAMP('".$goods_starttime."') AND `usetime` <= UNIX_TIMESTAMP('".$goods_endtime." 23:59:59') GROUP BY `userid`) i ON (u.`user_id`=i.`userid`)";
		}
	}


	if($SqlWhere != "")
		$SqlWhere = " WHERE ".$SqlWhere;

	$Sql = $Sql.$SqlWhere;//." order by u.reg_date desc";
	//echo $Sql;
	$Query_c = $DB->query($Sql);
	$Num_area = $DB->num_rows($Query_c);
	$dbCount = 0;
	$dbArray;
	while ($Rs=$DB->fetch_array($Query_c)) {
		//$dbArray[$dbCount] = $Rs['user_id'];
		$dbCount++;
	}


	/*$x_side;
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
  }*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
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
<form name="form2" method="post"="admin_crm_member_advantage_excel.php" target='_blank'  >
<input type="hidden" name="Action" value="Excel">
</form>
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
		document.form1.action = "admin_crm_member_advantage_excel.php";
		document.form1.submit();
}
function toMailGroup(){
		document.form1.action = "admin_crm_member_advantage_save.php";
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
                    <TD class=p12black noWrap><SPAN class=p9orange>行銷工具--&gt; 進階客戶關係管理 --&gt; 會員活耀度統計</SPAN></TD>
                </TR>
                </TBODY>
                </TABLE></TD>
            <TD align=right width="50%">&nbsp;</TD>
          </TR>
        </TBODY>
  </TABLE>

  <table class="allborder" style="margin-bottom:10px" width="100%">
  <tr>
  <td>

<TABLE class=p12black cellSpacing=0 cellPadding=0 width="85%" align=center border=0 >
	<TBODY>
        <FORM name=form2 id=form2 method=post action="">
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD height=19 colspan="3" align=right></TD>
            </TR>
          <TR>
            <TD width="160" height="30" align=left class=p9black>統計日期：</TD>
            <TD height="1" colspan="2" align=left class=p9black>起始日期
              <input  onMouseOver="this.className='box2'" id=begtime size=10
                      onMouseOut="this.className='box1'"
                       onClick="showcalendar(event, this)"
                       onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"
                       value='<?php echo $goods_starttime;?>'
                        name='goods_starttime'/> - 結束日期
                            <input  onMouseOver="this.className='box2'" id=endtime size=10
                        onMouseOut="this.className='box1'"
                        onClick="showcalendar(event, this)"
                        onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"
                        value='<?php echo $goods_endtime;?>'
                        name='goods_endtime'/>
            </TD>
            </TR>
             <TR>
            <TD height="30" align=left class=p9black>性別：</TD>
            <TD height="0" colspan="2" align=left class=p9black>
              <input type="radio" value="none" name="gender" checked="checked" /> 不限
              <input type="radio" value="male" name="gender" <?php if($_POST['gender'] == "male") echo "checked";?>/>男
              <input type="radio" value="female" name="gender" <?php if($_POST['gender'] == "female") echo "checked";?>/>女</TD>
            </TR>
          <TR>
            <TD height="30" align=left class=p9black>登入次數：</TD>
            <TD height="0" colspan="2" align=left class=p9black>
		    <input name="login_count" onkeyup="value=value.replace(/[^0-9]/g,'')" value="<?php echo $_POST['login_count'];?>"  size="4" />次以上(含)</TD>


            </TR>
            <TR>
               <TD height="30" align=left class=p9black>購買金額：</TD>
               <TD height="0" colspan="2" align=left class=p9black>
				   <input name="buymoney_start" size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" value='<?php echo $_POST['buymoney_start'];?>' /> 元 -
				   <input name="buymoney_end" size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" value='<?php echo $_POST['buymoney_end'];?>' /> 元

              </TD>
            </TR>
           <TR>

             <TD height="30" align=left class=p9black>購買某一商品：</TD>
             <TD height="0" colspan="2" align=left class=p9black>
              ID=<input name="item_id" size="40" onkeyup="value=value.replace(/[^0-9,]/g,'')" value='<?php echo $_POST['item_id'];?>'/> 請輸入商品的商品ID值，並用半形逗號（,）分隔。
              </TD>
            </TR>
          <TR>

             <TD height="30" align=left class=p9black>有推薦成功之會員：</TD>
            <TD height="0" colspan="2" align=left class=p9black>
				<input name="recommendmember" type=checkbox id="recommendmember" value="1" <?php if($_POST['recommendmember'] == "1") echo "checked";?>/></TD>
            </TR>
          <TR>
            <TD height="30" align=left class=p9black>有紅利點數之會員：</TD>
            <TD height="0" colspan="2" align=left class=p9black>
			<input name="bonusrecord_start" size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" value='<?php echo $_POST['bonusrecord_start'];?>' />點-
            <input name="bonusrecord_end" size="4" onkeyup="value=value.replace(/[^0-9]/g,'')" value='<?php echo $_POST['bonusrecord_end'];?>' /> 點</TD>

            </TR>
          <TR>
            <TD height="30" align=left class=p9black>有使用過紅利點數之會員：</TD>
            <TD height="30" colspan="2" align=left class=p9black>
				<input name="bonusbuydetail" type=checkbox id="bonusbuydetail" value="1" <?php if($_POST['bonusbuydetail'] == "1") echo "checked";?>/>
				</TD>

            </TR>
						<TR>
	            <TD height="30" align=left class=p9black>有使用過折價券之會員：</TD>
							<TD height="30" colspan="2" align=left class=p9black>
								<input type="radio" value="" name="bonusbuydetail" checked="checked" /> 不限
								<input type="radio" value="0" name="bonusbuydetail" <?php if($_POST['bonusbuydetail'] == "0") echo "checked";?>/>未使用
								<input type="radio" value="1" name="bonusbuydetail" <?php if($_POST['bonusbuydetail'] == "1") echo "checked";?>/>使用</TD>
						</TR>
          <TR>
            <TD height="30" align=left class=p9black>&nbsp;</TD>
            <TD height="30" colspan="2" align=left class=p9black><input type="submit" value="送出結果" /></TD>
            </TR>
          <TR>

			<TD height="19" colspan="3" align=left class=p9black>&nbsp;</TD>
            </TR>
          </FORM>
          </TBODY>
          </TABLE>
        </table>
<TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
          <TR>
            <TD vAlign=top height=210>
            <table class="p12black" cellspacing="0" cellpadding="0" width="85%"   align="center" border="0" >
              <form name="form1" id="form1" method="post" action="">
                <input type="hidden" name="Action2" value="Search" />
                <tr><td height="19" colspan="5" align="right"></td>  </tr>

            <input type="hidden" name="post_goods_starttime" id="post_goods_starttime" value="<?php echo $_POST['goods_starttime']?>">
			<input type="hidden" name="post_goods_endtime" id="post_goods_endtime" value="<?php echo $_POST['goods_endtime']?>">
			<input type="hidden" name="post_gender" id="post_gender" value="<?php echo $_POST['gender']?>">
			<input type="hidden" name="post_login_count" id="post_login_count" value="<?php echo $_POST['login_count']?>">
			<input type="hidden" name="post_buymoney_start" id="post_buymoney_start" value="<?php echo $_POST['buymoney_start']?>">
			<input type="hidden" name="post_buymoney_end" id="post_buymoney_end" value="<?php echo $_POST['buymoney_end']?>">
			<input type="hidden" name="post_item_id" id="post_item_id" value="<?php echo $_POST['item_id']?>">
			<input type="hidden" name="post_recommendmember" id="post_recommendmember" value="<?php echo $_POST['recommendmember']?>">
			<input type="hidden" name="post_bonusrecord_start" id="post_bonusrecord_start" value="<?php echo $_POST['bonusrecord_start']?>">
			<input type="hidden" name="post_bonusrecord_end" id="post_bonusrecord_end" value="<?php echo $_POST['bonusrecord_end']?>">
			<input type="hidden" name="post_bonusrecord" id="post_bonusrecord" value="<?php echo $_POST['bonusbuydetail']?>">
			<input type="hidden" name="post_ticketrecord" id="post_ticketrecord" value="<?php echo $_POST['ticketrecord']?>">

			<tr>
				<td width="96" align="left" class="p9black">
					<input type="submit" value="匯出execl表" onclick="javascript:toExecl()"/>
					<input type="button" value="保存郵件組" onclick="javascript:toMailGroup()"/>
				</td>
				<!--<td width="74" height="1" align="left" class="p9black"><input type="submit" value="儲存名單" /></td>-->
				<td width="532" align="left" class="p9black"><i class="icon-warning-sign" style="font-size:16px;color:#C00">
					</i> 匯出會員欄位請至 " <a href="admin_member_excel_sys.php">會員匯入匯出欄位設定</a> "
				</td>
				</tr>

				<tr>
					<TD height="30" align=left class=p9black></TD>
				</tr>

                <tr>
                  <td width="142" height="30" align="left" class="p9black">會員人數：</td>
                  <td width="93" height="1" align="left" class="p9black"><?php echo $dbCount ."人"?></td>
                </tr>
                <!--<tr>
				<td height="30" align="left" class="p9black">圖表：</td>
				<td height="0" colspan="4" align="left" class="p9black">只要長條圖和折線圖，Y軸人數，X軸日期(按月或其他可能切換)</td>
			</tr>
                <tr>                    <td height="0" colspan="4" align="left" class="p9black">
					<canvas id="born" height="350" width="560" style="width: 400px; height: 250px;">
					</canvas>
                </tr>-->
                <tr>
                  <td height="19" colspan="5" align="left" class="p9black">&nbsp;</td>
                </tr>
             <tr>
                   <TD height="30" align=left class=p9black>
					統計日期：
					<?php
					if( ( $_POST['goods_starttime'] != "" ) && ( $_POST['goods_endtime'] != "" ) ){
						echo $_POST['goods_starttime']." ~ ". $_POST['goods_endtime'];
					}
					else{
						if( $show_year != 0)
					echo $show_year;

					if( $show_month != 0)
						echo "/".$show_month;
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
					登入次數：
					<?php
					if( $_POST['login_count'] != 0 ){
						echo $_POST['login_count'];
					}
					else{
						echo "no data";
					}
					?>
				</TD>
			</tr>
			<tr>
				<TD height="30" align=left class=p9black>
					購買金額：
					<?php
					if( ( $_POST['buymoney_start'] != "" ) && ($_POST['buymoney_end'] != "" ) ){
						echo $_POST['buymoney_start']." ~ ". $_POST['buymoney_end'];
					}
					else{
						echo "no data";
					}
					?>

				</TD>
			</tr>
			<tr>
				<TD height="30" align=left class=p9black>
					購買商品：
					<?php
					if( $_POST['item_id'] != "" ){
						echo $_POST['item_id'];
					}
					else{
						echo "no data";
					}
					?>
				</TD>
			</tr>
			<tr>
				<TD height="30" align=left class=p9black>
					推薦成功之會員：
					<?php
					if( $_POST['recommendmember'] == "0"){
						echo 'no';
					}
					else if( $_POST['recommendmember'] =="1"){
						echo 'yes';
					}
					else {
						echo "no data";
					}
					?>
				</TD>
			</tr>

			<tr>
				<TD height="30" align=left class=p9black>
					紅利點數之會員：
					<?php
					if( ( $_POST['bonusrecord_start'] != "" ) && ($_POST['bonusrecord_end'] != "" ) ){
						echo $_POST['bonusrecord_start']." ~ ". $_POST['bonusrecord_end'];
					}
					else{
						echo "no data";
					}
					?>
				</TD>
			</tr>

			<tr>
				<TD height="30" align=left class=p9black>
					有使用過紅利點數之會員：
					<?php

					if( $_POST['bonusbuydetail'] == "0"){
						echo 'no';
					}
					else if( $_POST['bonusbuydetail'] =="1"){
						echo 'yes';
					}
					else {
						echo "no data";
					}
					?>
				</TD>
			</tr>
			<tr>
				<TD height="30" align=left class=p9black>
					有使用過折價卷之會員：
					<?php

					if( $_POST['ticketrecord'] == "0"){
						echo 'no';
					}
					else if( $_POST['ticketrecord'] =="1"){
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

<!--<script src="include/Chart.js"></script>
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
</script>-->

<div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
