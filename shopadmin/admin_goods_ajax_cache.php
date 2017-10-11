<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Good.php";
$Gid = intval($_REQUEST['goods_id'])==0 ? $Gid : intval($_REQUEST['goods_id']);

?>
                  <br />

                  <TABLE class=allborder cellSpacing=0 cellPadding=2 
                  width="100%" bgColor=#f7f7f7 border=0>
                
                    <TBODY>
                    <TR>
                      <TD noWrap align=center width="12%"><strong>審核時間</strong></TD>
                      <TD width="11%" align="center" ><strong>匯入時間</strong></TD>
                       <TD width="11%" align="center" ><strong>貨號</strong></TD>
                      <TD width="15%" align="center" ><strong>原免稅價</strong></TD>
                      <TD width="21%" align="center" ><strong>原促銷價</strong></TD>
                      <TD width="21%" align="center" ><strong>新免稅價</strong></TD>
                      <TD width="20%" align="center" ><strong>新促銷價</strong></TD>
                      <TD width="20%" align="center" ><strong>安全庫存量</strong></TD>
                      </TR>
   				<?php
                $Sql_action = "select oa.* from `{$INFO[DBPrefix]}goods_price_cach` as oa where oa.gid='" . $Gid . "' order by oa.pubtime desc";
				$Query_action    = $DB->query($Sql_action);
				while($Rs_action=$DB->fetch_array($Query_action)){
				?>						
				    <TR>
                      <TD width="12%" align=center valign="top" noWrap><?php echo $Rs_action['checktime'];?></TD>
                      <TD align="center" ><?php echo date("Y-m-d H:i:s",$Rs_action['pubtime']);?></TD>
                      <TD >
                      <?php echo $Rs_action['bn'];?>
                      </TD>
                      <TD >
                      <?php echo $Rs_action['org_price'];?>
                      </TD>
                      <TD align="center" ><?php echo $Rs_action['org_pricedesc'];?></TD>
                      <TD align="center" ><?php echo $Rs_action['new_price'];?></TD>
                      <TD align="center" >
                      <?php echo $Rs_action['new_pricedesc'];?>
                      </TD>
                      <TD align="center" >
                      <?php echo $Rs_action['storages'];?>
                      </TD>
                      </TR>
                      <?php
				}
				?>
					</TBODY>
            </TABLE>
