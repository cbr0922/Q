<?php
include_once "Check_Admin.php";
include_once Classes . "/pagenav_stard.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Sql      = "SELECT * FROM `ntssi_discountsubject` ORDER BY dsid desc";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

if ($Num>0){
	$limit = 20;
	$Nav->total_result = $Num;
	$Nav->execute($Sql,$limit);
	$Query_subject = $Nav->sql_result;
	$Nums              = $Num<$limit ? $Num : $limit ;
}else{
	$Query_subject     = $Query;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;活動主題--&gt;活動主題管理</TITLE></head>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
<div id="fullBg"></div>
<div id="msg">
<div id="close"></div>
<div id="ctt"></div>
</div>
<?php include_once "head.php";?>
<SCRIPT language=javascript>
function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){
		//document.adminForm.action = "admin_goods.php?goodsid="+checkvalue + catvalue;
		document.adminForm.action = "admin_discountsubject.php?Action=Modi&subject_id="+checkvalue;
		//document.adminForm.act.value="edit";
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo $Nums?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_discountsubject_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
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
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;活動主題--&gt;活動主題管理</SPAN></TD>
              </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
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
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_discountsubject.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[AddSubjectName];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  
                </TR>
              </TBODY>
              </TABLE>
            </TD>
          </TR>
    </TBODY>
        </TABLE>
      <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                    <FORM name=adminForm id=adminForm action="" method=post>
                      <INPUT type=hidden name=act>
                      <INPUT type=hidden value=0  name=boxchecked> 
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                          <TBODY>
                            <TR align=middle>
                              <TD width="10%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo $Nums?>'); type=checkbox value=checkbox   name=toggle> </TD>
                              <TD width="30%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $JsMenu[Subject];//主题类别?></TD>
                              <TD width="20%" align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>活動時間</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>最小購買金額</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>最小購買數量</TD>
                              
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>銷售總金額</TD>
                              
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>折扣後總金額</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>訂單筆數</TD>
                              
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>客單價</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>成本</TD>
                              
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>獲利</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>免運費</TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>贈紅利</TD>
                              <TD  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Basic_Command['Iffb'];//是否發佈?></TD>
                              <TD align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>活動商品管理</TD>
                            </TR>
                            <?php   

					$i=0;
					while ($Rs=$DB->fetch_array($Query_subject)) {
						//if($Rs['dsid']==22){
						$sql_s="SELECT `ntssi_discountgoods`.`gid`,`ntssi_order_detail`.`order_id`,`ntssi_discountgoods`.`price`,`ntssi_discountgoods`.`cost`,`hadsend`,FROM_UNIXTIME(`createtime`,'%Y-%m-%d') FROM `ntssi_discountgoods` JOIN `ntssi_order_detail` ON `ntssi_discountgoods`.`gid`=`ntssi_order_detail`.`gid` JOIN `ntssi_order_table` ON `ntssi_order_detail`.`order_id`=`ntssi_order_table`.`order_id` WHERE FROM_UNIXTIME(`createtime`,'%Y-%m-%d') BETWEEN '".$Rs['start_date']."' AND '".$Rs['end_date']."' AND `ntssi_discountgoods`.`dsid`=".$Rs['dsid']." AND `hadsend`>0 ORDER BY `ntssi_order_detail`.`order_id`,`ntssi_discountgoods`.`price`";
						//echo $sql_s;
						$Query_s    = $DB->query($sql_s);
						$Num_s      = $DB->num_rows($Query_s);						
							
						$totalprice= array();
						$totalcount=0;						
						$tempprice = array();
						$count;
						$cost=0;
						if ($Num_s>0){													
							while($Rs_s = $DB->fetch_array($Query_s)){
								$hadsend=$Rs_s['hadsend'];								
								$buycount=$Rs['buycount'];
								$buyprice=$Rs['buyprice'];
								$price;		
								
								$cost+=$Rs_s['cost']*$hadsend;
													
								if($Rs['min_count']>0){							
									if($buyprice<100){
										$price=($Rs_s['price']*$buycount*intval($hadsend/$buycount)*($buyprice/100));
									}else{
										$price=(intval($hadsend/$buycount)*$buyprice);										
									}								
									$price+=($Rs_s['price']*($hadsend%$buycount));
									
									//echo $price.'<br/>';
									if(array_key_exists($Rs_s['order_id'],$totalprice)){
										$tempprice[$Rs_s['order_id']]+=$Rs_s['price']*$hadsend;
										$totalprice[$Rs_s['order_id']]+=$price;
									}else{
										$totalcount++;
										$tempprice[$Rs_s['order_id']]=$Rs_s['price']*$hadsend;							
										$totalprice[$Rs_s['order_id']]=$price;
									}															
								}else{												
									if(array_key_exists($Rs_s['order_id'],$totalprice)){
										$count[$Rs_s['order_id']]+=$hadsend;
										
										if($count[$Rs_s['order_id']]%$buycount==0){
											$tempprice[$Rs_s['order_id']]+=$Rs_s['price']*$hadsend;
											
											if($buyprice<100){
												$price=($tempprice[$Rs_s['order_id']]*($buyprice/100));
											}else{
												$price=(intval($count[$Rs_s['order_id']]/$buycount)*$buyprice);
											}
										}else{
											$temp=$hadsend-$count[$Rs_s['order_id']]%$buycount;								
											$tempprice[$Rs_s['order_id']]+=$Rs_s['price']*($hadsend-($count[$Rs_s['order_id']]%$buycount));
											$tempprice['price']=$Rs_s['price']*($count[$Rs_s['order_id']]%$buycount);
											
											//echo $price.' '.$totalprice[$Rs_s['order_id']].'<br/>';
											if($temp>0){
												if($buyprice<100){
													$price=($tempprice[$Rs_s['order_id']]*($buyprice/100));
												}else{
													$price=(intval($count[$Rs_s['order_id']]/$buycount)*$buyprice);
												}
												$price+=$tempprice['price'];
											}else if($temp==0){
												$price=$totalprice[$Rs_s['order_id']]+$tempprice['price'];
											}else{
												$price=$totalprice[$Rs_s['order_id']]+($Rs_s['price']*$hadsend);
											}
											$tempprice[$Rs_s['order_id']]+=$tempprice['price'];											
																					
											//$price=$tempprice[$Rs_s['order_id']];//+$tempprice['price'];
										}										
										$totalprice[$Rs_s['order_id']]=$price;
										//echo $price.'<br/>';
									}else{
										$totalcount++;
										$count[$Rs_s['order_id']]=$hadsend;
										$tempprice[$Rs_s['order_id']]=$Rs_s['price']*$hadsend;
										
										if($buyprice<100){
											$price=($Rs_s['price']*$buycount*intval($hadsend/$buycount)*($buyprice/100));
										}else{
											$price=(intval($hadsend/$buycount)*$buyprice);										
										}								
										$price+=($Rs_s['price']*($hadsend%$buycount));	
																			
										$totalprice[$Rs_s['order_id']]=$price;
										//echo $totalprice[$Rs_s['order_id']].'<br/>';
									}									
								}
								//echo $Rs_s['order_id'].' '.$totalprice[$Rs_s['order_id']].'<br/>';
							}
							//echo array_sum($totalprice).' '.$totalcount;							
						//}
					}
						
					?>
                            <TR class=row0>
                              <TD align=middle height=26>
                                <INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['dsid']?>' name=cid[]></TD>
                              <TD height=26 align="left" noWrap>
                                <A href="javascript:toEdit('<?php echo $Rs['dsid']?>',0);">
                                <?php echo $Rs['subject_name']?>                        </A>&nbsp;</TD>
                              <TD align=center nowrap><?php echo $Rs['start_date']?> 至 <?php echo $Rs['end_date']?></TD>
                              <TD align=center nowrap><?php echo $Rs['min_money']?></TD>
                              <TD align=center nowrap><?php echo $Rs['min_count']?></TD>
                              
                              <TD align=center nowrap><?php echo intval(array_sum($tempprice))?></TD>
                              
                              <TD align=center nowrap><?php echo intval(array_sum($totalprice))?></TD>
                              
                              <TD align=center nowrap><?php echo $totalcount?></TD>
                              
                              <TD align=center nowrap><?php echo $totalcount>0?intval(array_sum($totalprice)/$totalcount):0?></TD>
                              
                              <TD align=center nowrap><?php echo $cost?></TD>
                              
                              <TD align=center nowrap><?php echo intval(array_sum($totalprice))-$cost?></TD>
                              <TD align=center nowrap><?php echo $Rs['mianyunfei']?></TD>
                              <TD align=center nowrap><?php echo $Rs['point']?>點</TD>
                              <TD height=26 align=center nowrap><?php echo $Display = intval($Rs['subject_open'])==1 ? "<i class='icon-check red_small'></i> 已開啟" : "<i class='icon-check-empty black_big'></i> 關閉中" ; ?></TD>
                              <TD height=26 align=center nowrap><div class="link_box" style="width:55px"><a href="javascript:void(0);" onclick="showWin('url','admin_discountsubject_ajax_goods.php?dsid=<?php echo $Rs['dsid']?>','',750,500);"><i class="icon-edit"></i> 管理</a></div></TD>
                            </TR>
                            <?php
					unset($tempprice);
					unset($totalprice);
					$i++;
					}
					?>
                      </TABLE>
                      </FORM>
                    </TD>
                  </TR>
              </TABLE>
              <?php
       if ($Num>0){
	   ?>     
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                    </TD>
                  </TR>
              </TABLE>
              <?php 
       }
	  ?>		
  </TD></TR></TABLE>
</div>
 <div align="center"><?php include_once "botto.php";?></div>
</BODY></HTML>
