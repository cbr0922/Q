<?php
include_once "Check_Admin.php";
$subject_id = intval($_GET['subject_id']);
?>
<table width="485" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                          <td colspan="6">額滿
                            <input name="s_money" type="text" id="s_money" size="5" />
                            元，折扣
                            <input name="s_saleoff" type="text" id="s_saleoff" size="5" />
                            %
                            <input type="button" name="button" id="button" value="儲存" onclick="saveSaleoff();" /></td>
                          </tr>
                          <?php
                          $Sql_s      = "select * from `{$INFO[DBPrefix]}discountsubject_sale` where dsid='" . $subject_id . "'";

$Query_s    = $DB->query($Sql_s);
$Num_s      = $DB->num_rows($Query_s);
if ($Num_s>0){
						  ?>
                        <tr>
                          <td width="33">序號</td>
                          <td width="99">額滿金額</td>
                          <td width="110">折扣</td>
                          <td width="49">修改 </td>
                          <td width="174">删除</td>
                        </tr>
                        <?php
						$i = 1;
                        while($Rs_s=$DB->fetch_array($Query_s)){
						?>
                        <tr>
                          <td><?php echo $i;?></td>
                          <td><?php echo $FUNCTIONS->Input_Box('text','money'.$Rs_s['sid'],$Rs_s['money']," maxLength=20 size=6 ")?></td>
                          <td><?php echo $FUNCTIONS->Input_Box('text','saleoff'.$Rs_s['sid'],$Rs_s['saleoff']," maxLength=20 size=6 ")?></td>
                          <td><input type="button" name="button2" id="button2" value="修改" onclick="UpdateSaleoff(<?php echo $Rs_s['sid'];?>);" /></td>
                          <td><input type="button" name="button3" id="button3" value="刪除" onclick="DelSaleoff(<?php echo $Rs_s['sid'];?>);" /></td>
                        </tr>
                        <?php
						$i++;
}
}
						?>
                      </table>