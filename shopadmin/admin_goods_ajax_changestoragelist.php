<?php
include_once "Check_Admin.php";
$gid = intval($_GET['gid']);
$s_Sql = "select s.*,d.detail_name,st.color,st.size from `{$INFO[DBPrefix]}storagelog` as s left join `{$INFO[DBPrefix]}goods_detail` as d on s.detail_id=d.detail_id left join `{$INFO[DBPrefix]}storage` as st on st.storage_id=s.storage_id where s.gid='" . $gid . "' and auto=0 order by datetime desc limit 0,20";
$s_Query    = $DB->query($s_Sql);
?><br>
<table width="580" border="0" cellspacing="1" cellpadding="2" bgcolor="#CCCCCC">
          <tr>
            <td>時間</td>
            <td>類型</td>
            <td>異動</td>
            <td>庫存</td>
            <td>詳細資料</td>
            <td>尺寸</td>
            <td>顏色</td>
            <td>備註</td>
            <td>操作者</td>
          </tr>
          <?php
          while ($s_Rs=$DB->fetch_array($s_Query)) {
		  ?>
          <tr>
            <td bgcolor="#FFFFFF"><?php echo date("Y-m-d H:s:i",$s_Rs['datetime']);?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['storagetype']==0? "增加": "減少";?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['storagetype']==0? "+": "-";echo $s_Rs['changes']?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['counts']?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['detail_name']?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['size']?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['color']?></td>
            <td bgcolor="#FFFFFF"><?php echo $s_Rs['content']?></td>
            <td bgcolor="#FFFFFF">
            <?php
			//echo $s_Rs['usert_ype'];
			$usertitle = "";
                if($s_Rs['user_type']==-1){
					$Sql_U = "select username as uname from `{$INFO[DBPrefix]}user` where user_id='" . $s_Rs['user_id'] . "'";	
					$usertitle = "[會員]";
				}elseif($s_Rs['user_type']==0){
					$Sql_U = "select sa as uname from `{$INFO[DBPrefix]}administrator` where sa_id='".$s_Rs['user_id']."' limit 0,1";
					$usertitle = "[高級管理員]";
				}elseif($s_Rs['user_type']==1){
					$Sql_U = "select username as uname,type from `{$INFO[DBPrefix]}operater` where opid='".$s_Rs['user_id']."' limit 0,1";
					$usertitle = "[一般管理員]";
				}elseif($s_Rs['user_type']==2){
					$Sql_U = "select provider_name as uname from `{$INFO[DBPrefix]}provider` where provider_id='".$s_Rs['user_id']."' limit 0,1";
					$usertitle = "[供應商]";
				}elseif($s_Rs['user_type']==-1){
					$Sql_U = "select username as uname from `{$INFO[DBPrefix]}user` where user_id='" . $s_Rs['user_id'] . "'";	
					$usertitle = "[會員]";
				}
				$Query_U    = $DB->query($Sql_U);
				$Rs_U=$DB->fetch_array($Query_U);
				//echo $Rs_U['type'];
				if ($usertitle==""){
					if (intval($Rs_U['type'])==1)
						$usertitle = "[PM]";
					else
						$usertitle = "[PM助理]";
				}
				echo $Rs_U['uname'].$usertitle;
				?>
            </td>
          </tr>
          <?php
		  $i++;
		  }
		  ?>
        </table>
<br>
<div align="center"><a href="javascript:void(0);" onclick="closeWin();">[返回]</a></div>
