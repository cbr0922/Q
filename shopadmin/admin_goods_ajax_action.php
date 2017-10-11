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
                      <TD noWrap align=center width="12%"><strong>時間</strong></TD>
                      <TD width="9%" align="center" ><strong>操作類型</strong></TD>
                      <TD width="62%" align="center" ><strong>變更內容</strong></TD>
                      <TD width="17%" align="center" ><strong>操作人</strong></TD>
                      </TR>
   				<?php
                $Sql_action = "select oa.* from `{$INFO[DBPrefix]}goods_action` as oa where oa.gid='" . $Gid . "' order by oa.actiontime desc";
				$Query_action    = $DB->query($Sql_action);
				while($Rs_action=$DB->fetch_array($Query_action)){
				?>						
				    <TR>
                      <TD width="12%" align=center valign="top" noWrap><?php echo date("Y-m-d H:i:s",$Rs_action['actiontime']);?></TD>
                      <TD align="center" ><?php echo $Rs_action['remark'];?></TD>
                      <TD >
                      <?php
                      $field_array = explode(",",$Rs_action['action_field']);
					  $value_array = explode(",",$Rs_action['action_value']);
					  foreach($field_array as $k=>$v){
						  echo "<font color='red'>" . $v . "</font>(" . str_replace("=>","<font color='blue'><b>=></b></font>",$value_array[$k])  . ")";  
					  }
					  ?>
                      </TD>
                      <TD align="center" >
                      <?php
                if($Rs_action['usertype']==0){
					$Sql_U = "select sa as uname from `{$INFO[DBPrefix]}administrator` where sa_id='".$Rs_action['user_id']."' limit 0,1";
					$usertitle = "[高級管理員]";
				}elseif($Rs_action['usertype']==1){
					$Sql_U = "select username as uname from `{$INFO[DBPrefix]}operater` where opid='".$Rs_action['user_id']."' limit 0,1";
					$usertitle = "[一般管理員]";
				}elseif($Rs_action['usertype']==2){
					$Sql_U = "select provider_name as uname from `{$INFO[DBPrefix]}provider` where provider_id='".$Rs_action['user_id']."' limit 0,1";
					$usertitle = "[供應商]";
				}
				$Query_U    = $DB->query($Sql_U);
				$Rs_U=$DB->fetch_array($Query_U);
				echo $Rs_U['uname'].$usertitle;
				?>
                      </TD>
                      </TR>
                      <?php
				}
				?>
					</TBODY>
            </TABLE>
