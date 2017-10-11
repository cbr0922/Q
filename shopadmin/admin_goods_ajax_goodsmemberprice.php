<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);
$gid        = trim($FUNCTIONS->Value_Manage($_GET['gid'],'','back',''));
$detail_id=0;
/**
	 * 这里是当供应商进入的时候。只能修改自己的产品资料。
	 */
if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$Provider_string = " and g.provider_id=".intval($_SESSION['sa_id'])." ";
}else{
	$Provider_string = "";
}
$G_Sql      = "select g.goodsname,g.pricedesc,g.price,g.bn from `{$INFO[DBPrefix]}goods` g where g.gid='".intval($gid)."' ".$Provider_strings." limit 0,1";
$G_Query    = $DB->query($G_Sql);
$G_Num      = $DB->num_rows($G_Query);
if ($G_Num>0){
	$G_result    =  $DB->fetch_array($G_Query);
	$G_goodsname = $G_result['goodsname'];
	$detail_bn = $G_result['bn'];
	$price = $G_result['price'];
	$pricedes = $G_result['pricedesc'];
}else{
	$FUNCTIONS->sorry_back('back','');
}

$DB->free_result($G_Query);

//$Sql      = "select *  from user_level ul left join member_price mp on (ul.level_id=mp.m_level_id) order by ul.level_num desc";
$Sql      = "select *  from `{$INFO[DBPrefix]}user_level` u order by u.level_num asc";
$Query    = $DB->query($Sql);
$Nums      = $DB->num_rows($Query);
?>
<script type="text/javascript">
        /*****************************************************
         * 滑鼠hover變顏色
         ******************************************************/
$(document).ready(function() {
$("#orderedlist tbody tr").hover(function() {
		$(this).addClass("blue");
	}, function() {
		$(this).removeClass("blue");
	});
});
</script>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>

                    <FORM name=form1 id="memberpriceform" action="admin_goods_ajax_goodsmemberprice_save.php" method=post>
					<INPUT type=hidden name=act>
					<INPUT type=hidden name=gid value="<?php echo $gid?>">
					<INPUT type=hidden name=detail_id value="<?php echo $detail_id?>">
					 <INPUT type=hidden value=0  name=boxchecked>
 <TABLE class=allborder cellSpacing=0 cellPadding=0 width="100%" bgColor=#f7f7f7 border=0  >
                    <TBODY>
                    <TR>
                      <TD align=right valign="middle" noWrap>&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD align=right valign="middle" noWrap>
                     <TABLE class=listtable cellSpacing=0 cellPadding=0 bgColor="#fff" width="100%" border=0 id="orderedlist">
                    <TBODY>
                    <TR align=middle>
                      <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26><?php echo $Basic_Command['SNo_say'];//序号?></TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[UserLevel];//会员等级?></TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1>規格</TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>買越多</TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Admin_Product[MemberPrice];//会员价格?></TD>
                      </TR>
					<?php
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {
						$Sql_s      = "select *  from `{$INFO[DBPrefix]}goods_saleoffe` where gid='" . $gid . "'";
						$Query_s     = $DB->query($Sql_s);
						$Nums_s      = $DB->num_rows($Query_s);
						if($Nums_s>0){
							while ($Rs_s=$DB->fetch_array($Query_s)) {
					?>
                    <TR class=row0>
                      <TD align=middle width=91 height=20>
                      <?php echo $i+1?>
					  </TD>
                      <TD height=20 align="left" noWrap>
                        <?php echo $Rs['level_name']?>                      </TD>
                      <TD height=20 align="left" noWrap>                 </TD>
                      <TD align=left nowrap><?php echo $Rs_s['mincount'] . "件~" . $Rs_s['maxcount'] . "件"?></TD>
                      <TD height=20 align=left nowrap>
					  <?php
					  $Sql_M    = "select * from `{$INFO[DBPrefix]}member_price` where m_level_id=".intval($Rs['level_id'])." and m_saleoffid='" . $Rs_s['soid'] . "' and m_goods_id='" . intval($gid) . "' limit 0,1";
					  $Query_M  = $DB->query($Sql_M);
					  $Result_M = $DB->fetch_array($Query_M);
					  $Nums_M      = $DB->num_rows($Query_M);
					  if($Nums_M>0)
					  	$price = $Result_M['m_price'];
					  else
					  	$price = intval($Rs['pricerate']/100*$Rs_s['price']);
					  ?>
					  <input type="hidden" name="m_price_id[]" value="<?php echo $Result_M['m_price_id']?>">
					  <input type="hidden" name="level_id[]" value="<?php echo $Rs['level_id']?>">
                      <input type="hidden" name="detail_id[]" value="<?php echo $Rs_d['detail_id']?>">
                      <input type="hidden" name="soid[]" value="<?php echo $Rs_s['soid']?>">
					  <?php echo $FUNCTIONS->Input_Box('text','m_price[]',intval($price),"      maxLength=10 size=10 ")?>
					  <?php $DB->free_result($Query_M);  ?>					  </TD>
                      </TR>
                    <?
					$i++;
							}
						}else{
							$Sql_d      = "select *  from `{$INFO[DBPrefix]}goods_detail` where gid='" . $gid . "'";
							$Query_d     = $DB->query($Sql_d);
							$Nums_d      = $DB->num_rows($Query_d);
							if($Nums_d>0){
								while ($Rs_d=$DB->fetch_array($Query_d)) {

					?>
                    <TR class=row0>
                      <TD align=middle width=91 height=20>
                      <?php echo $i+1?>
					  </TD>
                      <TD height=20 align="left" noWrap>
                        <?php echo $Rs['level_name']?>                      </TD>
                      <TD height=20 align="left" noWrap><?php echo $Rs_d['detail_name']?>                 </TD>
                      <TD align=left nowrap>&nbsp;</TD>
                      <TD height=20 align=left nowrap>
					  <?php
					  $Sql_M    = "select * from `{$INFO[DBPrefix]}member_price` where m_level_id=".intval($Rs['level_id'])." and m_detail_id='" . $Rs_d['detail_id'] . "' and m_goods_id='" . intval($gid) . "' limit 0,1";
					  $Query_M  = $DB->query($Sql_M);
					  $Result_M = $DB->fetch_array($Query_M);
					  $Nums_M      = $DB->num_rows($Query_M);
					  if($Nums_M>0)
					  	$price = $Result_M['m_price'];
					  else
					  	$price = intval($Rs['pricerate']/100*$Rs_d['detail_pricedes']);
					  ?>
					  <input type="hidden" name="m_price_id[]" value="<?php echo $Result_M['m_price_id']?>">
					  <input type="hidden" name="level_id[]" value="<?php echo $Rs['level_id']?>">
                      <input type="hidden" name="detail_id[]" value="<?php echo $Rs_d['detail_id']?>">
					  <?php echo $FUNCTIONS->Input_Box('text','m_price[]',intval($price),"      maxLength=10 size=10 ")?>
					  <?php $DB->free_result($Query_M);  ?>					  </TD>
                      </TR>
                      <?php
					  $i++;
							}
						}else{
					  ?>
                    <TR class=row0>
                      <TD align=middle width=91 height=20>
                      <?php echo $i+1?>
					  </TD>
                      <TD height=20 align="left" noWrap>
                        <?php echo $Rs['level_name']?>                      </TD>
                      <TD height=20 align="left" noWrap>&nbsp;</TD>
                      <TD align=left nowrap>&nbsp;</TD>
                      <TD height=20 align=left nowrap>
					  <?php
					  $Sql_M    = "select * from `{$INFO[DBPrefix]}member_price` where m_level_id=".intval($Rs['level_id'])." and m_detail_id=0 and m_goods_id='" . intval($gid) . "' limit 0,1";
					  $Query_M  = $DB->query($Sql_M);
					  $Result_M = $DB->fetch_array($Query_M);
					  $Nums_M      = $DB->num_rows($Query_M);
					  if($Nums_M>0)
					  	$price = $Result_M['m_price'];
					  else
					  	$price = intval($Rs['pricerate']/100*$pricedes);
					  ?>
					  <input type="hidden" name="m_price_id[]" value="<?php echo $Result_M['m_price_id']?>">
					  <input type="hidden" name="level_id[]" value="<?php echo $Rs['level_id']?>">
					  <?php echo $FUNCTIONS->Input_Box('text','m_price[]',intval($price),"      maxLength=10 size=10 ")?>
					  <?php $DB->free_result($Query_M);  ?>					  </TD>
                      </TR>
					<?php
					$i++;
					}
						}
					}
					?> 
                    <TR class=row0>
                      <TD height=43 colspan="5" align=center><input type="button" name="memberpricesave" id="memberpricesave" value="儲存所有價格" /></TD>
                      </TR>
                    </TBODY>
                    </TABLE>
 </TR>
                    </TBODY>
                    </TABLE>
 </FORM>
 
					
                 
<script>
$(document).ready(function() {
	var options = {
		success:       function(msg){
						alert("儲存成功");
						showtajaxfun('memberprice');
					},
		type:      'post',
		dataType:  'json',
		clearForm: true
	};
	$("#memberpricesave").click(function(){$('#memberpriceform').ajaxSubmit(options);});
						   });
</script>