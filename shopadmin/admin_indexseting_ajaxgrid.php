<?php
include_once "Check_Admin.php";
//print_r($_GET);
$indexadv_grid = intval($_GET['indexadv_grid']);
$db_string = $DB->compile_db_update_string( array (
		'bannercount'             => intval($_GET['indexadv_grid']),
)      );
		 $Sql = "UPDATE `{$INFO[DBPrefix]}index_banner` SET $db_string WHERE ib_id='".trim($_GET['ib_id']) ."'";
		$Result_Insert=$DB->query($Sql);
for($i=1;$i<=$indexadv_grid;$i++){
?>
<ul>

                              <li style="border: #E8E8E8 dashed 2px; float:left; display:block; width:240px; height:auto; margin-left:10px; position:relative;overflow: hidden;    min-height: 100px;">
                              <div style="width: 100%;float: left; position: absolute;  left: 0;  font-size: 14px;z-index: 1;"><a href="javascript:void(0);" onclick="showWin('url','admin_indexseting_ajax_adv.php?tag=adv_home<?php echo intval($_GET['ib_id']);?>_<?php echo $i;?>','',850,450);"><input value="編輯" type="button" /></a></div>
                                <?php
								//echo "select * from `{$INFO[DBPrefix]}advertising` where adv_tag='adv_home" . intval($_GET['ib_id']) . "_" . $i . "' order by adv_id desc limit 0,1";
                                $Query = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='adv_home" . intval($_GET['ib_id']) . "_" . $i . "' order by adv_id desc limit 0,1");
								$Num   = $DB->num_rows($Query);
								if($Num>0){
									$Result= $DB->fetch_array($Query);
									if($Result['adv_type']==21 || $Result['adv_type']==22)
										echo "<img src='../UploadFile/AdvPic/" . $Result['adv_left_img'] . "' width=240>";
									elseif($Result['adv_type']==24)
										$content = "";
									else
										echo $Result['adv_content'];
								}else{
								?>
                                格子<?php echo $i;?>
                                <?php
                                }
								?>
                               
                                <?php
                                if($Result['adv_type']==24){
									echo $Result['adv_content'] . "<br>";	
								?>
                                
                                <?php
								}
								?>
                                </li>
                               
                            </ul>
<?php
}
?>