<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');

$Where    =  "";
$Where    = $_GET['skey']!="" ?  " and goodsname like '%".$_GET['skey']."%'" : $Where ;

$s = $Where." and  gid!=".$Gid." ";
$Sql      = "select * from `{$INFO[DBPrefix]}goods`  where ifchange=1 ".$s." order by goodorder ";
$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

?>
        <FORM name=adminForm action="admin_goods_ajax_changegoodssave.php" method=post id="selectlinkgoodsform">
					<INPUT type=hidden name=actchange id="actchange" value="Save">
					<INPUT type=hidden name='Goodsname' id='Goodsname' value="<?php echo $Goodsname?>" >
					<INPUT type=hidden name='gid' id='gid' value="<?php echo $Gid?>" >
					 <INPUT type=hidden value=0  name=boxchecked>
             <TABLE class=listtable cellSpacing=0 cellPadding=0 align="center" width="95%" border=0>
                    <TBODY>
                    <TR align=middle>
                      <TD width="10%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
					   </TD>
                      <TD width="10%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductSmPic];//缩图?>
					  </TD>
                      <TD width="15%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>
                      <TD width="50%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductName];//名称?>					  </TD>
                      <TD  height=26 colspan="8" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductPrice];//价格?>					  </TD>
                      </TR>
					<?php
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

						$Yes = "images/".$INFO[IS]."/publish_g.png";
						$No  = "images/".$INFO[IS]."/publish_x.png";
						$ifpub_pic  = $Rs['ifpub']==1  ? $Yes : $No ;
						$ifrmd_pic  = $Rs['ifrecommend']==1  ? $Yes : $No ;
						$ifspec_pic = $Rs['ifspecial']==1  ? $Yes : $No ;
						$ifhot_pic  = $Rs['ifhot']==1  ? $Yes : $No ;

					?>
                    <TR class=row0>
                      <TD align=middle  height=20>
					  <INPUT id='cb<?php echo $i?>'  type=checkbox value='<?php echo $Rs['gid']?>' name=cid[]>
					  </TD>
					  <TD align=left  height=20><IMG onMouseOver="MM_showHideLayers('imgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('imgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/icon-viewpic.gif" width=18>
                        <DIV class=shadow id=imgLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 63px; POSITION: absolute; HEIGHT: 67px"   border="1"><IMG src="../<?php echo $INFO['good_pic_path']?>/<?php echo $Rs['smallimg']?>" ></DIV>
					  </TD>
                      <TD height=20 align="left" noWrap><?php echo $Rs['bn']?> &nbsp;</TD>
                      <TD  height=20 align=left nowrap> <?php echo $Rs['goodsname']?></TD>
                      <TD height=20 colspan="8" align=left nowrap><?php echo $Rs['pricedesc']?><INPUT id='price<?php echo $i?>'  type=hidden value='<?php echo $Rs['pricedesc']?>' name="price<?php echo $Rs['gid']?>"></TD>
                      </TR>
					<?php
					$i++;
					}
					?>
					 </TABLE>
					</FORM>