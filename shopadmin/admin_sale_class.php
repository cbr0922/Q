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

$starttime  = $_GET['starttime']!="" ? $_GET['starttime'] : date("Y-m-d",time()-7*24*60*60);
$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time());

function GetClassify($top_id){
	global $DB,$INFO,$FUNCTIONS,$starttime,$endtime;
	$Sql = "SELECT `bid`,`top_id`,`catname` FROM `{$INFO[DBPrefix]}bclass` WHERE `top_id`=".$top_id;
	$Query    = $DB->query($Sql);
	$Num      = $DB->num_rows($Query);
	$Results;
	$catname;$market;
	if ($Num>0){
		while($value = $DB->fetch_array($Query)){
			$catname[$value['bid']] = urlencode($value['catname']);
			$market[$value['bid']] = 0;
		}		
	}
	//-----------------
	$Sql = "SELECT SUM(`hadsend`) `totalcount`,SUM(`hadsend`*d.`price`) `totalprice` FROM `ntssi_order_table` t JOIN `ntssi_order_detail` d ON t.`order_id` = d.`order_id` JOIN `ntssi_goods` g ON d.`gid` = g.`gid` WHERE FROM_UNIXTIME(`createtime`,'%y-%m-%d') BETWEEN DATE_FORMAT('".$starttime."','%y-%m-%d') AND DATE_FORMAT('".$endtime."','%y-%m-%d') AND pay_state=1 AND order_state=4";
	$Total = 0;
	if(is_array($market)){
		foreach($market as $key=>$value){
			$s = $Sql;
			$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($key));
			$Next_ArrayClass  = array_filter(explode(",",$Next_ArrayClass));
			$Array_class      = array_unique($Next_ArrayClass);
			//print_r($Array_class);
			$Where="";
			if (count($Array_class)>0){
				$top_ids = intval($key).",".implode(",",$Array_class);
				$Where .= " AND g.bid in (".$top_ids.")";
			}else{
				$top_ids = intval($key);
				$Where .= " AND g.`bid`=".$top_ids;
			}
			$s .= $Where;
			//echo $s;
			$Query    = $DB->query($s);
			$Num      = $DB->num_rows($Query);
	
			if ($Num>0){
				while($v = $DB->fetch_array($Query)){				
					if( isset($_GET['type']) ){
						if( $_GET['type'] == 0 ){
							$market[$key] = $v['totalprice'] == NULL ? 0 : $v['totalprice'];
						}else if( $_GET['type'] == 1 ){
							$market[$key] = $v['totalcount'] == NULL ? 0 : $v['totalcount'];
						}						
					}
					else{
						$market[$key] = $v['totalprice'] == NULL ? 0 : $v['totalprice'];
					}
					$Total += $market[$key];
				}			
			}
		}
	}

	if( isset($_GET['sort']) ){
		if( $_GET['sort'] == 0 )
			arsort($market);
		else if( $_GET['sort'] == 1 )
			asort($market);
	}else{
		arsort($market);
	}
	if(is_array($market)){
		foreach($market as $key=>$value){
			$Field['catname'] = $catname[$key];
			if($Total>0)
				$Field['value'] = number_format(round($value/$Total,3)*100,1);
			else
				$Field['value'] =0;
			$Results["b".$key] = $Field;
		}
	}
	return $Results;
}

$classify1['b0']=GetClassify(0);
$classify2;
$classify3;
if(is_array($classify1['b0'])){
	foreach($classify1['b0'] as $key=>$value){
		$classify2[$key]=GetClassify(substr($key,1));
		if(is_array($classify2[$key])){
			foreach($classify2[$key] as $key1=>$value1){
				$classify3[$key1]=GetClassify(substr($key1,1));
			}
		}
	}
}

$json_classify1 = urldecode(json_encode($classify1));
$json_classify2 = urldecode(json_encode($classify2));
$json_classify3 = urldecode(json_encode($classify3));

//echo $json_classify1;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE>行銷工具--&gt;統計分析--&gt; 熱門商品統計</TITLE>

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>
<script language="javascript" src="../js/jquery-1.7.2.min.js"></script>
<script src="progressbar/jquery-ui.js"></script>
<script language="javascript">

function showAssort(bid,n){	
	//var g=jQuery.parseJSON(<?php echo $json_classify1; ?>);
	
	var obj;
	switch(n){
		case 1:
			obj = <?php echo $json_classify1; ?>;
			break;
		case 2:
			obj = <?php echo $json_classify2; ?>;
			break;
		case 3:
			obj = <?php echo $json_classify3; ?>;
			break;
	}
	
	$(function(){
		//alert(obj[bid]);
		//解析数组
		if(obj[bid] != null){
        	$.each(obj[bid], function(i, ifield) {
				$("<li/>").addClass("ui-widget-content").attr("bid", i).text(ifield.catname).append($("<span/>").addClass("ui-li-value").text(ifield.value+"%")).appendTo("#selectable-"+n);
        	});
        }
	});
}
</script>
<?php echo "<script type='text/javascript'>showAssort('b0',1);</script>";?>

<script language="javascript">

function toExprot(){

	form2.submit();

}

</script>

<form name="form2" method="get" action="" target='_blank'  >

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

</SCRIPT>
<style>
.ui-title {padding: 8px; font-size: 16px; width: 300px; background:#545454;color: #fff;}
.ui-title .ui-li-value {float: right; margin-right: 15px;}
.ui-scroll {width: 316px;background:#eee;overflow-y:scroll;height: 245px;}

.selectable .ui-selected { background: #7CAC62; color: #fff; }
.selectable :hover{background:#7CAC62;}
.selectable {list-style-type: none; margin: 0; padding: 0; width: 20%;height: 245px;}
.selectable li { padding:8px; font-size: 18px; height: 18px; }
.ui-widget-content {background: #fff; border-bottom: 1px solid #F3F3F6; color: #000; width:280px;}
.ui-li-value {float: right;}
</style>
<script>
	$(function() {
        $("#selectable-1").disableSelection();
        $("#selectable-1 li").live("click",function(){		
            $(this).addClass("ui-selected");
            $('#selectable-1 li').removeClass("ui-selected");
            var str = $(this).html();
			$("#ui-title2").text(str.substr(0,str.indexOf("<"))).append($("<span/>").addClass("ui-li-value").text("佔比(%)"));
			$("#ui-title3").text("次要品類").append($("<span/>").addClass("ui-li-value").text("佔比(%)"));
			$("#selectable-2").empty();
			$("#selectable-3").empty();
			showAssort($(this).attr("bid"),2);
     	});
     	
        $("#selectable-2").disableSelection();
        $("#selectable-2 li").live("click",function(){
            $("#selectable-2 li").removeClass("ui-selected");
            $(this).addClass("ui-selected");
            var str = $(this).html();
			$("#ui-title3").text(str.substr(0,str.indexOf("<"))).append($("<span/>").addClass("ui-li-value").text("佔比(%)"));
			$("#selectable-3").empty();
            showAssort($(this).attr("bid"),3);
        });
	});
</script>
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

                    <TD class=p12black noWrap><SPAN class=p9orange>行銷工具--&gt; 統計分析

                    --&gt; 品類銷售統計</SPAN></TD>

                </TR></TBODY></TABLE></TD>

            <TD align=right width="50%">&nbsp;</TD>

          </TR>

        </TBODY>

  </TABLE>

  

  <table class="allborder" style="margin-bottom:10px" width="100%">

  <tr>

  <td>

<TABLE class=p12black cellSpacing=0 cellPadding=0 width="85%"   align=center border=0 >

        <FORM name=form1 id=form1 method=get action="">

          <TR>

            <TD height=19 colspan="3" align=right>		 </TD>

            </TR>

          <TR>

            <TD width="160" height="30" align=left class=p9black>日期：</TD>

            <TD height="1" colspan="2" align=left class=p9black>起始日期

              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=begtime size=10  onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value='<?php echo $starttime;?>' name='starttime'>

              - 結束日期

              <input  onMouseOver="this.className='box2'" onMouseOut="this.className='box1'"  id=endtime size=10   onClick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  value='<?php echo $endtime;?>' name='endtime'>

            </TD>

            </TR>

           <TR>

             <TD height="30" align=left class=p9black>查詢種類：</TD>

             <TD height="0" colspan="2" align=left class=p9black>
             	<input type="radio" name="type" value="0" checked="checked" />銷售金額
				<input type="radio" name="type" value="1" <?php if($_GET['type'] == 1) echo "checked";?>/>銷售數量
			</TD>

            </TR>

           <TR>
            <TD height="30" align=left class=p9black>排序：</TD>
             <TD height="0" colspan="2" align=left class=p9black>
             	<input type="radio" name="sort" value="0" checked="checked" />由高至低
				<input type="radio" name="sort" value="1" <?php if($_GET['sort'] == 1) echo "checked";?>/>由低至高
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

<TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">


          <TR>

            <TD vAlign=top height=210><table class="p12black" cellspacing="0" cellpadding="0" width="85%"   align="center" border="0" >

              <form name="form1" id="form2" method="get" action="">
              	<tr>
                  <td height="19" colspan="4" align="left" class="p9black">&nbsp;</td>
                </tr>
                <tr>
                  <td height="19" colspan="4" align="left" class="p9black">&nbsp;</td>
                </tr>
	<tr>
		<td>
<div class="ui-title" id="ui-title1">品類名稱<span class="ui-li-value">佔比(%)</span></div>
<div class="ui-scroll">
      <ol id="selectable-1" class="selectable">
      
      </ol>
</div>
		</td>
		<td>
<div class="ui-title" id="ui-title2">主要品類<span class="ui-li-value">佔比(%)</span></div>
<div class="ui-scroll">
      <ol id="selectable-2" class="selectable">
      
      </ol>
</div>
		</td>
		<td>
<div class="ui-title" id="ui-title3">次要品類<span class="ui-li-value">佔比(%)</span></div>
<div class="ui-scroll">
      <ol id="selectable-3" class="selectable">
      
      </ol>
</div>
		</td>
	</tr>
				<tr>
                  <td height="19" colspan="4" align="left" class="p9black">&nbsp;</td>
                </tr>
                <tr>
                  <td height="19" colspan="4" align="left" class="p9black">&nbsp;</td>
                </tr>
              </form>

            </table></TD>

        </TR></TABLE>

</div>

<script language="javascript" src="../js/modi_bigarea1.js"></script>

<script language="javascript">

initCounty2(document.getElementById("province"), "<?php echo trim($_GET[province])?>")

initZone2(document.getElementById("province"), document.getElementById("city"), document.getElementById("othercity"), "<?php echo trim($_GET[city])?>")

</script>

<div align="center"><?php include_once "botto.php";?></div>

</BODY></HTML>

