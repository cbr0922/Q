<?php
include_once "Check_Admin.php";
$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');
if ($_POST['act']=='Save'){

	$cid      = $_POST['cid'];
	$cid_num  = count($cid);
	$zk_price = $_POST['zk_price'];
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select good_link_id from `{$INFO[DBPrefix]}good_link` where p_gid='$Gid' and s_gid='$cid[$i]' order by idate desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num<1){
			$Result =  $DB->query(" insert into `{$INFO[DBPrefix]}good_link` (p_gid,s_gid,link_type,zk_price,idate) values('".intval($Gid)."','".intval($cid[$i])."','0','".doubleval($zk_price[$i])."','".time()."')");
		}
	}

	//if ($Result) {
		echo "1";
		exit;
	//}
}
//ɾ������!
if ($_POST['actlink']=="Del"){
	$GoodLinkIdArray = $_POST['good_link_id'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$Query = $DB->query("select * from `{$INFO[DBPrefix]}good_link` where good_link_id='" . intval($GoodLinkIdArray[$i]) . "' order by good_link_id desc limit 0,1"); 
			$Result    = $DB->fetch_array($Query);
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}good_link` where good_link_id=".intval($GoodLinkIdArray[$i]));
			if(intval($Result['link_type'])==1){
				$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}good_link` where s_gid=".intval($Result['p_gid'])." and p_gid=".intval($Result['s_gid']));
			}
		}
	}
	echo "1";
	exit;
}
if ($_POST['actlink']=="Save"){
	$Goodlinkid   = $_POST['good_link_id'];
	$S_gid        = $_POST['S_gid'];
	$Allid        = $_POST['Allid'];
	$Zk_price     = $_POST['zk_price'];

	if (count($Goodlinkid)>0) {                   //���ȷʵ�������ύ!
		for ($i=0;$i<count($Allid);$i++){           //���ύ���������ϵ�ID����ѭ��
			foreach($Goodlinkid as $k => $v){         //ѭ���ύ�Ľ�Ҫ������������
				if ($v == $Allid[$i]){                    //����ύ�ļ�¼��ID���ѭ��ID��ͬ��ʱ��
					$link_type = $_POST["link_type".$i];      //������ܻ���ύ��¼�е����͵�ֵ��

					$Update_sql = " update `{$INFO[DBPrefix]}good_link` set zk_price='".intval($Zk_price[$i])."' , link_type='".intval($link_type)."' where good_link_id='".intval($Allid[$i])."' ";
					$DB->query($Update_sql);  //���Ƚ�������¼����


					switch ($link_type){  // ����ֵ���ж��ǽ��滹����������
						case 0:   //�������Ĭ�ϵ���������

						break;
						case 1:  //����ǽ�������,�����Ҫ����SCAN good_link �� �ж��ǲ��뻹��UPDATE������!!
						$Query = $DB->query("select good_link_id from `{$INFO[DBPrefix]}good_link` where s_gid=".intval($Gid)." and p_gid=".intval($S_gid[$i])." order by good_link_id desc limit 0,1"); //�鿴��û�����Ǳ�ѡ��Ʒ,���Ǳ����Ʒ��¼!
						$Num   = $DB->num_rows($Query);
						if ($Num==0){  // ���û�м�¼,�Ͳ���һ����¼�����ݿ�
							$DB->query(" insert into `{$INFO[DBPrefix]}good_link` (p_gid,s_gid,link_type) values('".intval($S_gid[$i])."','".intval($Gid)."','1')");
						}
						break;

					}
					// ע��,����Ӧ�ö��ύ���ͻ�Ҫ����Ӧ�Ĵ���,���ڻ�û��


					break 1;
				}
			}
		}
	}
	echo "1";
	exit;
}
?>