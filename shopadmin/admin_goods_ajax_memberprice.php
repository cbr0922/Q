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
$detail_id=intval($_GET['detail_id']);
/**
	 * 这里是当供应商进入的时候。只能修改自己的产品资料。
	 */
if (intval($_SESSION[LOGINADMIN_TYPE])==2){
	$Provider_string = " and g.provider_id=".intval($_SESSION['sa_id'])." ";
}else{
	$Provider_string = "";
}
$G_Sql      = "select g.goodsname,g.pricedesc,g.price,g.bn,gd.detail_name,gd.detail_bn,gd.detail_price,gd.detail_pricedes from `{$INFO[DBPrefix]}goods_detail` gd left join `{$INFO[DBPrefix]}goods` g on gd.gid=g.gid where gd.gid='".intval($gid)."' and gd.detail_id='" . $detail_id . "' ".$Provider_strings." limit 0,1";
$G_Query    = $DB->query($G_Sql);
$G_Num      = $DB->num_rows($G_Query);
if ($G_Num>0){
	$G_result    =  $DB->fetch_array($G_Query);
	$G_goodsname = $G_result['goodsname'];
	$detail_bn = $G_result['detail_bn'];
	$detail_name = $G_result['detail_name'];
	$detail_price = $G_result['detail_price'];
	$detail_pricedes = $G_result['detail_pricedes'];
}else{
	$FUNCTIONS->sorry_back('back','');
}

$DB->free_result($G_Query);

//$Sql      = "select *  from user_level ul left join member_price mp on (ul.level_id=mp.m_level_id) order by ul.level_num desc";
$Sql      = "select *  from `{$INFO[DBPrefix]}user_level` u order by u.level_num asc";
$Query    = $DB->query($Sql);
$Nums      = $DB->num_rows($Query);
?>
<div style="height:400px;overflow:auto">
<TABLE class=listtable cellSpacing=0 cellPadding=0    width="100%" border=0>
                    <FORM name=form1 id="memberpriceform" action="admin_goods_ajax_memberprice_save.php" method=post>
					<INPUT type=hidden name=act>
					<INPUT type=hidden name=gid value="<?php echo $gid?>">
					<INPUT type=hidden name=detail_id value="<?php echo $detail_id?>">
					 <INPUT type=hidden value=0  name=boxchecked>
                    <TBODY>
                    <TR align=middle>
                      <TD class=p9black noWrap align=middle  background=images/<?php echo $INFO[IS]?>/bartop.gif height=26><?php echo $Basic_Command['SNo_say'];//序号?></TD>
                      <TD  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[UserLevel];//会员等级?></TD>
                      <TD align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $Admin_Member[PerNum];//積分點數?></TD>
                      <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Admin_Product[MemberPrice];//会员价格?></TD>
                      </TR>
					<?php
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {


					?>
                    <TR class=row0>
                      <TD align=middle width=91 height=20>
                      <?php echo $Rs['level_id']?>
					  </TD>
                      <TD height=20 align="left" noWrap>
                        <?php echo $Rs['level_name']?>                      </TD>
                      <TD height=20 align="left" noWrap><?php echo $Rs['level_num']?></TD>
                      <TD height=20 align=center nowrap>
					  <?php
					  $Sql_M    = "select * from `{$INFO[DBPrefix]}member_price` where m_level_id=".intval($Rs['level_id'])." and m_detail_id=".intval($detail_id)." limit 0,1";
					  $Query_M  = $DB->query($Sql_M);
					  $Result_M = $DB->fetch_array($Query_M);
					  ?>
					  <input type="hidden" name="m_price_id[]" value="<?php echo $Result_M['m_price_id']?>">
					  <input type="hidden" name="level_id[]" value="<?php echo $Rs['level_id']?>">
					  <?php echo $FUNCTIONS->Input_Box('text','m_price[]',intval($Result_M['m_price']),"      maxLength=10 size=10 ")?>
					  <?php $DB->free_result($Query_M);  ?>					  </TD>
                      </TR>
					<?php
					$i++;
					}
					?> </TBODY>
                   
  </FORM>
 
					 </TABLE>
                  <p align="center">
                    <input type="button" name="memberpricesave" id="memberpricesave" value="保存" />
                    <input type="button" name="cPic" id="cPic" value="返回" onclick="closeWin();" />
                  </p>
</div>
<script>
$(document).ready(function() {
	var options = {
		success:       function(msg){
						if (msg==1){
							closeWin();
						}else{
							alert(msg);
						}
					},
		type:      'post',
		dataType:  'json',
		clearForm: true
	};
	$("#memberpricesave").click(function(){$('#memberpriceform').ajaxSubmit(options);});
						   });
</script>