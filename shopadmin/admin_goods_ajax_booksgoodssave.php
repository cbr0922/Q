<?php
include_once "Check_Admin.php";
$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');
if ($_POST['act']=='Save'){

	$cid      = $_POST['cid'];
	$cid_num  = count($cid);
	for ($i=0;$i<$cid_num;$i++){
		$Sql    = "select gbid from `{$INFO[DBPrefix]}goods_books` where gid='$Gid' and nid='$cid[$i]' order by ord desc  limit 0,1";
		$Query  = $DB->query($Sql);
		$Num    = $DB->num_rows($Query);
		if ($Num<1){
			$Result =  $DB->query(" insert into `{$INFO[DBPrefix]}goods_books` (gid,nid,idate) values('".intval($Gid)."','".intval($cid[$i])."','".time()."')");
		}
	}

	//if ($Result) {
		echo "1";
		exit;
	//}
}
//ɾ������!
if ($_POST['actbooks']=="Del"){
	$GoodLinkIdArray = $_POST['gbid'];
	$GoodLinkIdNum   = count($GoodLinkIdArray);
	if ($GoodLinkIdNum>0){
		for ($i=0;$i<$GoodLinkIdNum;$i++){
			$DelQuery = $DB->query("delete from `{$INFO[DBPrefix]}goods_books` where gbid=".intval($GoodLinkIdArray[$i]));
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