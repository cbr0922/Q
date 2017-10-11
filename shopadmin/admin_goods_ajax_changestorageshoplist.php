<?php
include_once "Check_Admin.php";
$gid = intval($_GET['gid']);
$s_Sql = "select s.*,d.order_serial,d.receiver_name,d.user_id as uid,u.true_name from `{$INFO[DBPrefix]}storagelog` as s left join `{$INFO[DBPrefix]}order_table` as d on s.order_id=d.order_id left join `{$INFO[DBPrefix]}storage` as st on st.storage_id=s.storage_id  left  join  `{$INFO[DBPrefix]}user` u on (u.user_id=d.user_id) where s.gid='" . $gid . "' and auto=1 order by datetime desc limit 0,20";
$s_Query    = $DB->query($s_Sql);
?><br>
<table width="580" border="0" cellspacing="1" cellpadding="2" bgcolor="#CCCCCC">
          <tr>
            <td>時間</td>
            <td>類型</td>
            <td>異動</td>
            <td>庫存</td>
            <td>訂單號</td>
            <td>訂購人</td>
            <td>會員ID</td>
          </tr>
          <?php
          while ($s_Rs=$DB->fetch_array($s_Query)) {
		  ?>
          <tr>
            <td bgcolor="#FFFFFF"><?php echo date("Y-m-d H:s:i",$s_Rs['datetime']);?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['storagetype']==0? "增加": "減少";?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['storagetype']==0? "+": "-";echo $s_Rs['changes']?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['counts']?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['order_serial']?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['true_name']?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['uid']?></td>
          </tr>
          <?php
		  $i++;
		  }
		  ?>
        </table>
<br>
<div align="center"><a href="javascript:void(0);" onclick="closeWin();">[返回]</a></div>
