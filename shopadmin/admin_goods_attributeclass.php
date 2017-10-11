<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
$Bid = $_GET['bid'];
$gid = $_GET['gid'];
$attr_goods = array();

if (intval($Bid)>0){
$Sql = "select * from ntssi_bclass where bid='" . intval($Bid) . "'";
$Query =    $DB->query($Sql);
while ( $NewPro = $DB->fetch_array($Query)){
	if ($NewPro['top_id']>0){
		$Bid = $NewPro['top_id'];
		$Sql_1 = "select * from ntssi_bclass where bid='" . $NewPro['top_id'] . "'";
		$Query_1 =    $DB->query($Sql_1);
		$NewPro_1 = $DB->fetch_array($Query_1);
		if ($NewPro_1['top_id']>0){	
			$Bid = $NewPro_1['top_id'];
		}
	}
}

					  if (intval($gid)>0){
						  $goods_sql = "select * from `{$INFO[DBPrefix]}attributegoods` where gid='" . intval($gid) . "'";
						  $Query_goods    = $DB->query($goods_sql);
						  $ig = 0;
						  while($Rs_goods=$DB->fetch_array($Query_goods)){
							$attr_goods[$ig]=$Rs_goods['valueid'];
							$ig++;
						  }
					  }
					  //print_r($attr_goods);
					  
?>
<style>
.figure_img>figure{margin:0 !important}
</style>
<table width="95%"  height="11" border="0" cellpadding="2" cellspacing="0" style="font-size:11px">
					<?php
					$class_sql = "select ac.*,a.attributename from `{$INFO[DBPrefix]}attributeclass` as ac left join `{$INFO[DBPrefix]}attribute` as a on ac.attrid=a.attrid where cid='" . intval($Bid) . "'";
					  $Query_class    = $DB->query($class_sql);
					  while($Rs_class=$DB->fetch_array($Query_class)){
					
					?>
					
					<TR>
                      <TD width="100" align=right  bgcolor="#FFFF99"><?php echo $Rs_class['attributename'];?>：</TD>
                      <TD bgcolor="#FFFF99" >
					  <?php
					  
					  
					  $Sql      = "select * from `{$INFO[DBPrefix]}attributevalue` where attrid='" . intval($Rs_class['attrid']) . "' order by valueid desc ";
					  $Query    = $DB->query($Sql);
					  while ($Rs=$DB->fetch_array($Query)) {
					  ?>
					  	 <input style="float:left" type="checkbox" name="attribute[]" id="attribute" value="<?php echo $Rs['valueid'];?>"
						<?php if (in_array($Rs['valueid'],$attr_goods))  echo "checked";?>
						>
						<?php
  						if ($Rs_class['attributename']=="顏色") {
    					echo '<div class="figure_img" style="width:20px;height:20px;margin:3px;border-radius: 100%;float: left;">';
    					echo $Rs["content"];
  						echo '</div>';
							}else{
								echo '<div style="float: left;">';
  							echo $Rs['value'];
								echo '</div>';
							}
						?>&nbsp; 
					  <?php
					  }
					  ?>
					  </TD>
                    </TR>
					<?php
					  }
					?>
					</table>
 <?php
}
 ?>
