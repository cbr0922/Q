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
//删除资料!
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

	if (count($Goodlinkid)>0) {                   //如果确实有资料提交!
		for ($i=0;$i<count($Allid);$i++){           //将提交的所有资料的ID做大循环
			foreach($Goodlinkid as $k => $v){         //循环提交的将要被操作的资料
				if ($v == $Allid[$i]){                    //如果提交的记录中ID与大循环ID相同的时候
					$link_type = $_POST["link_type".$i];      //这里就能获得提交记录中的类型的值了

					$Update_sql = " update `{$INFO[DBPrefix]}good_link` set zk_price='".intval($Zk_price[$i])."' , link_type='".intval($link_type)."' where good_link_id='".intval($Allid[$i])."' ";
					$DB->query($Update_sql);  //首先将本条记录更新


					switch ($link_type){  // 根据值来判断是交叉还是提升销售
						case 0:   //如果就是默认的提升销售

						break;
						case 1:  //如果是交叉销售,这里就要首先SCAN good_link 表 判断是插入还是UPDATE操作了!!
						$Query = $DB->query("select good_link_id from `{$INFO[DBPrefix]}good_link` where s_gid=".intval($Gid)." and p_gid=".intval($S_gid[$i])." order by good_link_id desc limit 0,1"); //查看有没有主是被选产品,次是本类产品记录!
						$Num   = $DB->num_rows($Query);
						if ($Num==0){  // 如果没有记录,就插入一条记录到数据库
							$DB->query(" insert into `{$INFO[DBPrefix]}good_link` (p_gid,s_gid,link_type) values('".intval($S_gid[$i])."','".intval($Gid)."','1')");
						}
						break;

					}
					// 注意,这里应该对提交类型还要做对应的处理,现在还没有


					break 1;
				}
			}
		}
	}
	echo "1";
	exit;
}
?>