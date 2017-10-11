<?php
@ob_start();
ini_set("memory_limit", "100M");  
include_once "Check_Admin.php";
//include Classes . "/global.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");

switch ($INFO['admin_IS']){
	case "gb":
		$ToEncode = "GB2312";
		break;
	case "en":
		$ToEncode = "GB2312";
		break;
	case "big5":
		$ToEncode = "BIG5";
		break;
	default:
		$ToEncode = "GB2312";
		break;
}


if ($_GET[Action]=='OutputExcel') {
	
require_once( 'PHPExcel.php' ); 
$objExcel = new PHPExcel();  
$objExcel->setActiveSheetIndex(0); 
$objActSheet = $objExcel->getActiveSheet(); 

$objExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
							 
$objActSheet->setTitle('訂單匯出');

	/**
 *  装载语言包
 */

	$file_string = "";
	$file_string = "編號,商品類別名稱,擴展分類,供應商名稱,商品品牌,主題類別,商品名稱,促銷廣告標語,瀏覽等級,貨號,賣場編號,市場價,網購價格,成本價,會員價,會員積分,計量單位,庫存警告,警告數量,庫存數量,商品影音資料,簡單描述,使用規則,KeyWords,成份規格,詳細描述,是否發佈,是否推薦,是否特價,是否熱賣,積分點數,紅利商品,紅利商品兌換積分,超值任選商品,任選商品數量,屬於超值任選商品,是否是加購商品,是否是滿額加購商品,額滿加購金額(最小),額滿加購金額(最大),額滿加購價格,額滿禮商品,額滿禮金額,集殺商品,集殺時效(開始),集殺時效(結束),起購價&集殺價I,件數I,起購價&集殺價II,件數II,起購價&集殺價III,件數III,起購價&集殺價IV,件數IV,起購價&集殺價V,件數V,集殺已累計件數,多件折扣,折扣主題,折扣價格,整點促銷商品（未到促銷時間段不能進行購買活動）,促銷時間(開始),促銷時間(結束),整點促銷商品（在促銷期間商品售價為促銷價格）,促銷時間(開始),促銷時間(結束),整點促銷價格,配送方式,特殊配送方式,特殊配送費用,是否支持海外配送,貨運寄送類,中小型物件每件運費,中小型物件運費加價,是否滿額免運費,查看次數,國際碼,型號,統倉商品,重量";
	
	
	if ($_GET[outtype]=='database'){
		$file_string = "編號,商品類別,供應商,商品品牌,主題類別,商品名稱,促銷廣告標語,貨號,賣場編號,市場價,網購價格,成本價,會員價,會員價積分,計量單位,庫存警告,警告數量,庫存數量,商品影音資料,簡單描述,使用規則,KeyWords,成份規格,詳細描述,是否發佈,是否推薦,是否特價,是否熱賣,積分點數,紅利商品,紅利商品兌換積分,超值任選商品,任選商品數量,屬於超值任選商品,是否是加購商品,是否是滿額加購商品,額滿加購金額,額滿加購價格,額滿加購金額(最小),額滿加購金額(最大),額滿禮金額,集殺商品,集殺時效(開始),集殺時效(結束),起購價&集殺價I,件數I,起購價&集殺價II,件數II,起購價&集殺價III,件數III,起購價&集殺價IV,件數IV,起購價&集殺價V,件數V,集殺已累計件數,多件折扣,折扣主題,折扣價格,整點促銷商品（未到促銷時間段不能進行購買活動）,促銷時間(開始),促銷時間(結束),整點促銷商品（在促銷期間商品售價為促銷價格）,促銷時間(開始),促銷時間(結束),整點促銷價格,配送方式,特殊配送方式,特殊配送費用,是否支持海外配送,貨運寄送類,中小型物件每件運費,中小型物件運費加價,是否滿額免運費,查看次數,國際碼,型號,統倉商品,重量";
	}
	//$file_string = str_replace("：","",$file_string);
	//$file_string = iconv("UTF-8",$ToEncode,$file_string);
	$string_array = explode(",",$file_string);
	foreach($string_array as $k=>$v){
		$objActSheet->setCellValue('' . getC($k) . '1', $v);	
	}
	
	$Sql_sub = "select * from `{$INFO[DBPrefix]}goods` where provider_id='".intval($_SESSION['sa_id'])."'";
	$Query_sub = $DB->query($Sql_sub);
	$Num_sub   = $DB->num_rows($Query_sub);


	/**
     * 这里是处理输出EXCEL时候用的。
     * 商品类别名称,擴展類別名稱，供貨商,商品品牌,主题类别,免運費件數,商品名称,货号,商品价格,特惠價格,计量单位,积分点数,是否库存警告,警告数量,库存数量,红利商品,
     * 所需积分,集殺商品,集殺時效[start],集殺時效[end],起購價集殺價[1],起購價集殺價[2],起購價集殺價[3],起購價集殺價[4],起購價集殺價[5],已累计件數
     * ,查看,是否发布,是否推荐,是否特价,是否热卖
     */

	$i = 0;
	$FUNCTIONS->setLog("商品匯出");
	while ($Rs_sub = $DB->fetch_array($Query_sub)){

		$file_temp    = "";

		/**
		 * 这里想根据数据库的原始字段输出数据，输出的数据，可以直接用于数据库资料更新！
		 */
		if ($_GET[outtype]=='database'){
			//$file_string .= "編號,商品類別名稱,供應商名稱,商品品牌,主題類別,商品名稱,促銷廣告標語,貨號,賣場編號,市場價,網購價格,成本價,會員價,會員積分,計量單位,庫存警告,警告數量,庫存數量,商品影音資料,簡單描述,使用規則,KeyWords,成份規格,詳細描述,是否發佈,是否推薦,是否特價,是否熱賣,積分點數,紅利商品,紅利商品兌換積分,超值任選商品,任選商品數量,屬於超值任選商品,是否是加購商品,是否是滿額加購商品,額滿加購金額,額滿加購價格,額滿禮商品,額滿禮金額,集殺商品,集殺時效(開始),集殺時效(結束),起購價&集殺價I,件數I,起購價&集殺價II,件數II,起購價&集殺價III,,件數III,起購價&集殺價IV,件數IV,起購價&集殺價V,件數V,集殺已累計件數,多件折扣,折扣主題,折扣價格,整點促銷商品（未到促銷時間段不能進行購買活動）,促銷時間(開始),促銷時間(結束),整點促銷商品（在促銷期間商品售價為促銷價格）,促銷時間(開始),促銷時間(結束),配送方式,特殊配送方式,特殊配送費用,是否支持海外配送,貨運寄送類,中小型物件每件運費,中小型物件運費加價,是否滿額免運費,查看次數";

			$js_price     = explode("||",$Rs_sub[js_price]);
			$jscount     = explode("||",$Rs_sub[jscount]);
			
			
			$file_temp    = $Rs_sub[gid].",".$Rs_sub[bid].",".$Rs_sub[provider_id].",".$Rs_sub[brand_id].",".$Rs_sub[subject_id].",".formatstr($Rs_sub[goodsname]).",".formatstr($Rs_sub[sale_name]).",";
			$file_temp    .= $Rs_sub[bn]."," . $Rs_sub[goodsno].",".$Rs_sub[price].",".$Rs_sub[pricedesc].",".$Rs_sub[cost].",".$Rs_sub[memberprice].",".$Rs_sub[combipoint].",";
			$file_temp    .= $Rs_sub[unit].",".$Rs_sub[ifalarm].",".$Rs_sub[alarmnum].",".$Rs_sub[storage].",";
			$file_temp    .= formatstr($Rs_sub[video_url]).",".formatstr($Rs_sub[intro]).",".formatstr($Rs_sub[alarmcontent]).",".formatstr($Rs_sub[keywords]).",".formatstr($Rs_sub[cap_des]).",".formatstr($Rs_sub[body]).",";
			$file_temp    .= $Rs_sub[ifpub].",".$Rs_sub[ifrecommend].",".$Rs_sub[ifspecial].",".$Rs_sub[ifhot].",".$Rs_sub[point].",".$Rs_sub[ifbonus].",".$Rs_sub[bonusnum].",";
			$file_temp    .= $Rs_sub[ifxygoods].",".$Rs_sub[xycount].",".$Rs_sub[ifxy].",".$Rs_sub[ifchange].",".$Rs_sub[ifadd].",".$Rs_sub[addmoney].",".$Rs_sub[addprice].",".$Rs_sub[ifpresent].",".$Rs_sub[present_money].",".$Rs_sub[present_endmoney].",";
			$file_temp    .= $Rs_sub[ifjs].",".$Rs_sub[js_begtime].",".$Rs_sub[js_endtime].",".$js_price[0].",".$jscount[0].",".$js_price[1].",".$jscount[1].",".$js_price[2].",".$jscount[2].",".$js_price[3].",".$jscount[3].",".$js_price[4].",".$jscount[4].",".$Rs_sub[js_totalnum].",";
			$file_temp    .= $Rs_sub[ifsales].",".$Rs_sub[sale_subject].",".$Rs_sub[sale_price].",".$Rs_sub[ifsaleoff].",".$Rs_sub[saleoff_starttime].",".$Rs_sub[saleoff_endtime].",".$Rs_sub[iftimesale].",".$Rs_sub[timesale_starttime].",".$Rs_sub[timesale_endtime].",".$Rs_sub[saleoffprice].",";
			$file_temp    .= $Rs_sub[trans_type].",".$Rs_sub[trans_special].",".$Rs_sub[trans_special_money].",".$Rs_sub[iftransabroad].",".$Rs_sub[transtype].",".$Rs_sub[transtypemonty].",".$Rs_sub[addtransmoney].",".$Rs_sub[ifmood].",".$Rs_sub[view_num].",".$Rs_sub[guojima].",".$Rs_sub[xinghao].",".$Rs_sub[iftogether].",".$Rs_sub[weight]."\n";
			$file_temp_array = explode(",",$file_temp);
			foreach($string_array as $k=>$v){
				$objActSheet->setCellValue('' . getC($k) . ($i+2), $file_temp_array[$k]);	
			}

		} elseif ($_GET[outtype]=='text'){

			/**
		     * 获得大类的名字
		     */
			$Bid_Query = $DB->query("select b.catname from `{$INFO[DBPrefix]}bclass` b  where bid=".intval($Rs_sub[bid])." limit 0,1");
			$Bid_Num   = $DB->num_rows($Bid_Query);
			if ($Bid_Num>0){
				$Bid_Rs   = $DB->fetch_array($Bid_Query);
				$B_Catname= iconv("UTF-8",$ToEncode,$Bid_Rs[catname]);
			}
			/**
		     * 获得供货商的名字
		     */
			$Pro_Query = $DB->query("select p.provider_name from `{$INFO[DBPrefix]}provider` p  where provider_id=".intval($Rs_sub[provider_id])." limit 0,1");
			$Pro_Num   = $DB->num_rows($Pro_Query);
			if ($Pro_Num>0){
				$Pro_Rs   = $DB->fetch_array($Pro_Query);
				$Pro_name = iconv("UTF-8",$ToEncode,$Pro_Rs[provider_name]);
			}

			/**
		     * 获得商品品牌的名字
		     */
			$Brand_Query   = $DB->query("select b.brandname,b.brand_id from `{$INFO[DBPrefix]}brand` b  where brand_id=".intval($Rs_sub[brand_id])." limit 0,1");
			$Brand_Num     = $DB->num_rows($Brand_Query);
			if ($Brand_Num>0){
				$Brand_Rs   = $DB->fetch_array($Brand_Query);
				$Brand_name = iconv("UTF-8",$ToEncode,$Brand_Rs[brandname]);
			}
			/**
			*獲得擴展類
			*
			*/
			$extClass_Query = $DB->query("select gc.*,b.catname from `{$INFO[DBPrefix]}goods_class` gc inner join `{$INFO[DBPrefix]}bclass` as b on b.bid=gc.bid  where gc.gid='" . intval($Rs_sub[gid]) . "'");
			$extClass_Num     = $DB->num_rows($extClass_Query);
			$extClass_array = array();
			$eci = 0;
			if ($extClass_Num>0){
				while($extClass_Rs   = $DB->fetch_array($Brand_Query)){
					$extClass_array[$eci] = iconv("UTF-8",$ToEncode,$extClass_Rs[catname]);
					$eci++;
				}
				
			}
			$extClass_str = implode("|",$extClass_array);
			
			/**
			*流覽等級
			*/
			$level_goods = array();
			$goods_sql = "select gu.*,ul.level_name from `{$INFO[DBPrefix]}goods_userlevel` as gu inner join `{$INFO[DBPrefix]}user_level` as ul on gu.levelid=ul.level_id where gid='" . intval($Rs_sub[gid]) . "'";
			$Query_goods    = $DB->query($goods_sql);
			$ig = 0;
			while($Rs_goods=$DB->fetch_array($Query_goods)){
				$level_goods[$ig]=iconv("UTF-8",$ToEncode,$Rs_goods['level_name']);
				$ig++;
			}
			$level_goods_str = implode("|",$level_goods);
			/**
			*特殊運送方式
			*
			*/
			$Sql_trans      = "select * from `{$INFO[DBPrefix]}transportation_special` where trid='" . $Rs_sub[trans_special] . "' order by trid ";
			$Query_trans    = $DB->query($Sql_trans);
			$Num_trans      = $DB->num_rows($Query_trans);
			$Rs_trans=$DB->fetch_array($Query_trans);
			$trans_special = iconv("UTF-8",$ToEncode,$Rs_trans['name']);

			/**
		     * 获得主题类别的名字
		     */
			$Subject_Query   = $DB->query("select s.subject_name,s.subject_id from `{$INFO[DBPrefix]}subject` s where subject_id=".intval($Rs_sub[subject_id])." limit 0,1");
			$Subject_Num     = $DB->num_rows($Subject_Query);
			if ($Subject_Num>0){
				$Subject_Rs   = $DB->fetch_array($Subject_Query);
				$Subject_name = iconv("UTF-8",$ToEncode,$Subject_Rs[subject_name]);
			}
			/**
			折扣主題
			**/
			$Sql_sub1   = " select subject_name,subject_id,subject_open from `{$INFO[DBPrefix]}sale_subject` where subject_id='" . $Rs_sub[sale_subject] . "' order by subject_num desc ";
			$Query_sub1 = $DB->query($Sql_sub1);
			$Rs_sub1 = $DB->fetch_array($Query_sub1);
			$sale_subject = $Rs_sub1['subject_name'];
			$sale_subject = iconv("UTF-8",$ToEncode,$sale_subject);

			$Ifalarm_temp          = intval($Rs_sub[ifalarm])     ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$Ifbonus_temp          = intval($Rs_sub[ifbonus])     ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$Ifjs_temp             = intval($Rs_sub[ifjs])        ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$Ifpub_temp            = intval($Rs_sub[ifpub])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$Ifrecommend_temp      = intval($Rs_sub[ifrecommend]) ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$Ifspecial_temp        = intval($Rs_sub[ifspecial])   ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$Ifhot_temp            = intval($Rs_sub[ifhot])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$ifxygoods_temp            = intval($Rs_sub[ifxygoods])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$ifxy_temp            = intval($Rs_sub[ifxy])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$ifchange_temp            = intval($Rs_sub[ifchange])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$ifpresent_temp            = intval($Rs_sub[ifpresent])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$trans_type_temp            = intval($Rs_sub[trans_type])       ==0 ? "一般配送方式"  : "特殊配送方式";
			$iftransabroad_temp            = intval($Rs_sub[iftransabroad])       ==0 ? "不允許"  : "允許";
			$ifadd_temp            = intval($Rs_sub[ifadd])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$ifsales_temp            = intval($Rs_sub[ifsales])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$ifsaleoff_temp            = intval($Rs_sub[ifsaleoff])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$iftimesale_temp            = intval($Rs_sub[iftimesale])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$ifmood_temp            = intval($Rs_sub[ifmood])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$iftogether_temp            = intval($Rs_sub[iftogether])       ==0 ? $Basic_Command['No']  : $Basic_Command['Yes'];
			$transtype_array=array(1=>"中小型物件",2=>"大型物件",3=>"其他");
			
			$Ifalarm          = $Ifalarm_temp;
			$Ifbonus          = $Ifbonus_temp;
			$Ifjs             = $Ifjs_temp;
			$Ifpub            = $Ifpub_temp;
			$Ifrecommend      = $Ifrecommend_temp;
			$Ifspecial        = $Ifspecial_temp;
			$Ifhot            = $Ifhot_temp;
			$ifxygoods            = $ifxygoods_temp;
			$ifxy            = $ifxy_temp;
			$ifchange            = $ifchange_temp;
			$ifpresent            = $ifpresent_temp;
			$trans_type            = $trans_type_temp;
			$iftransabroad            = $iftransabroad_temp;
			$ifadd            = $ifadd_temp;
			$ifsales            = $ifsales_temp;
			$ifsaleoff            = $ifsaleoff_temp;
			$iftimesale            = $iftimesale_temp;
			$ifmood            = $ifmood_temp;
			
			$js_price     = explode("||",$Rs_sub[js_price]);
			$jscount     = explode("||",$Rs_sub[jscount]);
			
	//$file_string .= "編號,商品類別名稱,擴展分類,供應商名稱,商品品牌,主題類別,商品名稱,促銷廣告標語,瀏覽等級,貨號,賣場編號,市場價,網購價格,成本價,會員價,會員積分,計量單位,庫存警告,警告數量,庫存數量,商品影音資料,簡單描述,使用規則,KeyWords,成份規格,詳細描述,是否發佈,是否推薦,是否特價,是否熱賣,積分點數,紅利商品,紅利商品兌換積分,超值任選商品,任選商品數量,屬於超值任選商品,是否是加購商品,是否是滿額加購商品,額滿加購金額,額滿加購價格,額滿禮商品,額滿禮金額,集殺商品,集殺時效(開始),集殺時效(結束),起購價&集殺價I,件數I,起購價&集殺價II,件數II,起購價&集殺價III,,件數III,起購價&集殺價IV,件數IV,起購價&集殺價V,件數V,集殺已累計件數,多件折扣,折扣主題,折扣價格,整點促銷商品（未到促銷時間段不能進行購買活動）,促銷時間(開始),促銷時間(結束),整點促銷商品（在促銷期間商品售價為促銷價格）,促銷時間(開始),促銷時間(結束),配送方式,特殊配送方式,特殊配送費用,是否支持海外配送,貨運寄送類,中小型物件每件運費,中小型物件運費加價,是否滿額免運費,查看次數";
			if ($Rs_sub[saleoff_starttime]!=""){
				$saleoff_starttime = date("Y-m-d H:i:s",$Rs_sub[saleoff_starttime])	;
			}
			if ($Rs_sub[saleoff_endtime]!=""){
				$saleoff_endtime = date("Y-m-d H:i:s",$Rs_sub[saleoff_endtime])	;
			}
			if ($Rs_sub[timesale_starttime]!=""){
				$timesale_starttime = date("Y-m-d H:i:s",$Rs_sub[timesale_starttime])	;
			}
			if ($Rs_sub[timesale_endtime]!=""){
				$timesale_endtime = date("Y-m-d H:i:s",$Rs_sub[timesale_endtime]);	
			}
			$file_temp    = $Rs_sub[gid].",". $B_Catname . "," . $extClass_str .",".$Pro_name.",".$Brand_name.",".$Subject_name.",".formatstr($Rs_sub[goodsname]).",".formatstr($Rs_sub[sale_name])."," . $level_goods_str . ",";
			$file_temp    .= $Rs_sub[bn]."," . $Rs_sub[goodsno].",".$Rs_sub[price].",".$Rs_sub[pricedesc].",".$Rs_sub[cost].",".$Rs_sub[memberprice].",".$Rs_sub[combipoint].",";
			$file_temp    .= $Rs_sub[unit].",".$Ifalarm.",".$Rs_sub[alarmnum].",".$Rs_sub[storage].",";
			$file_temp    .= formatstr($Rs_sub[video_url]).",".formatstr($Rs_sub[intro]).",".formatstr($Rs_sub[alarmcontent]).",".formatstr($Rs_sub[keywords]).",".formatstr($Rs_sub[cap_des]).",".formatstr($Rs_sub[body]).",";
			$file_temp    .= $Ifpub.",".$Ifrecommend.",".$Ifspecial.",".$Ifhot.",".$Rs_sub[point].",".$Ifbonus.",".$Rs_sub[bonusnum].",";
			$file_temp    .= $ifxygoods.",".$Rs_sub[xycount].",".$ifxy.",".$ifchange.",".$ifadd.",".$Rs_sub[addmoney].",".$Rs_sub[addprice].",".$Rs_sub[ifpresent].",".$Rs_sub[present_money].",".$Rs_sub[present_endmoney].",";
			$file_temp    .= $Ifjs.",".$Rs_sub[js_begtime].",".$Rs_sub[js_endtime].",".$js_price[0].",".$jscount[0].",".$js_price[1].",".$jscount[1].",".$js_price[2].",".$jscount[2].",".$js_price[3].",".$jscount[3].",".$js_price[4].",".$jscount[4].",".$Rs_sub[js_totalnum].",";
			$file_temp    .= $ifsales.",".$sale_subject.",".$Rs_sub[sale_price].",".$ifsaleoff.",".$saleoff_starttime.",".$saleoff_endtime.",".$iftimesale.",".$timesale_starttime.",".$timesale_endtime.",".$Rs_sub[saleoffprice].",";
			$file_temp    .= $trans_type.",".$trans_special.",".$Rs_sub[trans_special_money].",".$iftransabroad.",".$transtype_array[$Rs_sub[transtype]].",".$Rs_sub[transtypemonty].",".$Rs_sub[addtransmoney].",".$ifmood.",".$Rs_sub[view_num].",".$Rs_sub[guojima].",".$Rs_sub[xinghao] . ",".$iftogether_temp.",".$Rs_sub[weight]."\n";
			
			//$file_string .= $file_temp ;
			$file_temp_array = explode(",",$file_temp);
			foreach($string_array as $k=>$v){
				$objActSheet->setCellValue('' . getC($k) . ($i+2), $file_temp_array[$k]);	
			}
			

			//$file_temp    =  $Rs_sub[gid]. $B_Catname.",". $extClass_str . "," . $Pro_name.",".$Brand_name.",".$Subject_name.",".$Rs_sub[nocarriage].",".iconv("UTF-8",$ToEncode,$Rs_sub[goodsname])."," . iconv("UTF-8",$ToEncode,$Rs_sub[sale_name]) .",".$Rs_sub[bn].",".$Rs_sub[price].",".$Rs_sub[pricedesc].",".iconv("UTF-8",$ToEncode,$Rs_sub[unit]).",".$Rs_sub[point].",".$Ifalarm.",".$Rs_sub[alarmnum].",".$Rs_sub[storage].",".$Ifbonus.",".$Rs_sub[bonusnum].",".$Ifjs.",".$Rs_sub[js_begtime].",".$Rs_sub[js_endtime].",".$js_price[0].",".$js_price[1].",".$js_price[2].",".$js_price[3].",".$js_price[4].",".$Rs_sub[js_totalnum].",".$Rs_sub[view_num].",".$Ifpub.",".$Ifrecommend.",".$Ifspecial.",".$Ifhot.",".$level_goods_str.",".$ifxygoods.",".$Rs_sub[xycount].",".$ifxy.",".$ifchange.",".$ifpresent.",".$Rs_sub['present_money'].",".$trans_type.",".$iftransabroad.",".$trans_special."[" . $Rs_sub[trans_special_money] . "]"."\n";
			//$file_string .= $file_temp ;
		}
		$i++;

	}



	//echo $file_string;


	//$Creatfile = iconv("UTF-8",$ToEncode,"All_product")."_".date("Y-m-d");
	$Creatfile = "All_product".date("Y-m-d",time());

	/**
	 * 这个部分是写一个本地文件，在目前这里是没有用的。临时保留
	 * 
	if ( $fh = fopen( $Creatfile.'.csv', 'w' ) )
	{
	fputs ($fh, $file_string, strlen($file_string) );
	fclose($fh);
	@chmod ($Creatfile.'.csv',0777);
	}
	*/
	
	
	$outputFileName = $Creatfile . ".xls";
$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');

	
header("Content-Type: application/force-download");    
header("Content-Type: application/octet-stream");    
header("Content-Type: application/download");    
header('Content-Disposition:inline;filename="'.$outputFileName.'"');    
header("Content-Transfer-Encoding: binary");    
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");    
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");    
header("Pragma: no-cache");    
$objWriter->save('php://output');   
	//@header("location:".$Creatfile.'.csv');
	exit;
}

if ($_GET[Action]=='InputExcel') {
	/**
	 * 获得已有产品货号数组
	 */
	 /*
	$Goods_query = $DB->query("select g.bn from `{$INFO[DBPrefix]}goods` g  order by gid desc ");
	while ($Goods_rs = $DB->fetch_array($Goods_query)){
		$Goods_array[] = $Goods_rs[bn];
	}
	*/
	/**
	// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
	// of $_FILES.

	$uploaddir = '../UserFiles/csv/';
	$uploadfile = $uploaddir . basename($_FILES['cvsEXCEL']['name']);

	echo '<pre>';
	if (move_uploaded_file($_FILES['cvsEXCEL']['tmp_name'], $uploadfile)) {
		echo "File is valid, and was successfully uploaded.\n";
	} else {
		echo "Possible file upload attack!\n";
	}
    */

	$i=0;
	$handle = fopen ($_FILES['cvsEXCEL']['tmp_name'],"r");
	$Query_b = $DB->query("select max(goodsno) as maxno from `{$INFO[DBPrefix]}goods`");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = $Result_b['maxno'];
	while ($datastr = fgets ($handle, 1024000)) {
		//$datastr = big52utf8($datastr);
		$data = explode(",",$datastr);
		if ($i>0 && $data[5]!="" && $data[5]!="0"){
			
			//$TrueHave = $Char_class->StrposHave($Goods_array,$date[8]);
			//$TrueHave = RPrivilegeChecked ($Goods_array,$data[7]);
			//if ($TrueHave==0){
				
				$Query_b = $DB->query("select max(goodsno) as maxno from `{$INFO[DBPrefix]}goods`");
	$Result_b= $DB->fetch_array($Query_b);
	$maxno = $Result_b['maxno'];
	$goodsno = num(intval($maxno)+1);

				if (intval($data[7])>0){
					
					
					
					$Goods_query = $DB->query("select g.gid from `{$INFO[DBPrefix]}goods` g where bn='".trim($data[7])."'  order by gid desc ");
					$Num   = $DB->num_rows($Goods_query);
					if ($Num<=0){
						$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($data[2])." limit 0,1");
					$Num   = $DB->num_rows($Query);
					$Result= $DB->fetch_array($Query);
					$providerno           =  $Result['providerno'];
					if (intval($providerno)<=0){
		$providerno = "000000";		
	}
					$Query_b = $DB->query("select bn from `{$INFO[DBPrefix]}goods` where bn like '" . $providerno . "%'  order by gid desc  limit 0,1");
					$Result_b= $DB->fetch_array($Query_b);
					$maxno = substr($Result_b['bn'],6);
					$bn = num(intval($maxno)+1);
					
					
					
					
					$bn = $providerno.$bn;
						$db_string = $DB->compile_db_insert_string( array (
					'bid'                => intval($data[1]),
				  'provider_id'        => intval($data[2]),
				  'brand_id'           => intval($data[3]),
				  'subject_id'         => trim($data[4]),
				  'goodsname'          => trim($data[5]),
				  'sale_name'              => trim($data[6]),
				  'bn'                 => trim($bn),
				  'goodsno'                =>$goodsno,
				  'price'              => intval(str_replace("$","",$data[9])),
				  'pricedesc'          => intval(str_replace("$","",$data[10])),
				  'cost'           => intval($data[11]),
				  'memberprice'              => intval($data[12]),
				  'combipoint'              => intval($data[13]),
				  'unit'               => trim($data[14]),
				  'ifalarm'            => intval($data[15]),
				  'alarmnum'           => intval($data[16]),
				  'video_url'          => trim($data[18]),
				  'intro'              => $data[19],
				  'alarmcontent'       => trim($data[20]),
				  'keywords'           => $data[21],
				  'cap_des'              => trim($data[22]),
				  'body'               =>  $data[23],
				  'ifpub'              => intval($data[24]),
				  'ifrecommend'        => intval($data[25]),
				  'ifspecial'          => intval($data[26]),
				  'ifhot'              => intval($data[27]),
				  'point'              => intval($data[28]),
				  'ifbonus'            => intval($data[29]),
				  'bonusnum'           => intval($data[30]),
				  'ifxygoods'              => intval($data[31]),
				  'xycount'              => intval($data[32]),
				  'ifxy'              => intval($data[33]),
				  'ifchange'              => intval($data[34]),
				  'ifadd'              => intval($data[35]),
				  'addmoney'              => intval($data[36]),
				  'addprice'              => intval($data[37]),
				  'ifpresent'      =>  intval($data[38]),
				  'present_money'      =>  intval($data[39]),
				  'present_endmoney'      =>  intval($data[40]),
				  'ifjs'               => intval($data[41]),
				  'js_begtime'         => $data[42],
				  'js_endtime'         => $data[43],
				  'js_price'           => $data[44] . "||" . $data[46] . "||" . $data[48] . "||" . $data[50] . "||" . $data[52],
				  'jscount'           => $data[45] . "||" . $data[47] . "||" . $data[49] . "||" . $data[51] . "||" . $data[53],
				  'js_totalnum'        => intval($data[54]),
				  'ifsales'              => intval($data[55]),
				  'sale_subject'              => intval($data[56]),
				  'sale_price'              => intval($data[57]),
				  'ifsaleoff'              => intval($data[58]),
				  'saleoff_starttime'      => $data[59],
				  'saleoff_endtime'      => $data[60],
				  'iftimesale'              => intval($data[61]),
				  'timesale_starttime'      => $data[62],
				  'timesale_endtime'      => $data[63],
				  'saleoffprice'              => intval($data[64]),
				  'trans_type'              => intval($data[65]),
				  'trans_special'              => intval($data[66]),
				  'trans_special_money'              => intval($data[67]),
				  'iftransabroad'              => intval($data[68]),
				  'transtype'              => intval($data[69]),
				  'transtypemonty'              => intval($data[70]),
				  'addtransmoney'              => intval($data[71]),
				  'ifmood'              => intval($data[72]),
				  'view_num'              => intval($data[73]),
				  'guojima'              => ($data[74]),
				  'xinghao'              => ($data[75]),
				  'idate'              => time(),
				  'iftogether'              => intval($data[76]),
				  'weight'              => ($data[77]),
					)      );
				
				
					 $Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
					$Result=$DB->query($Sql);
					$gid = mysql_insert_id();
	$FUNCTIONS->setStorage(intval($data[17]),0,$gid,0,"","","",0);
					
					}else{
						
					}
					
				}else{
					
					
					$Query = $DB->query("select * from `{$INFO[DBPrefix]}provider` where provider_id=".intval($data[2])." limit 0,1");
					$Num   = $DB->num_rows($Query);
					$Result= $DB->fetch_array($Query);
					$providerno           =  $Result['providerno'];
					if (intval($providerno)<=0){
						$providerno = "000000";		
					}
					$Query_b = $DB->query("select bn from `{$INFO[DBPrefix]}goods` where bn like '" . $providerno . "%'  order by gid desc  limit 0,1");
					$Result_b= $DB->fetch_array($Query_b);
					$maxno = substr($Result_b['bn'],6);
					$bn = num(intval($maxno)+1);
					
					
					$bn = $providerno.$bn;
					
					$db_string = $DB->compile_db_insert_string( array (
					'bid'                => intval($data[1]),
				  'provider_id'        => intval($data[2]),
				  'brand_id'           => intval($data[3]),
				  'subject_id'         => trim($data[4]),
				  'goodsname'          => trim($data[5]),
				  'sale_name'              => trim($data[6]),
				  'bn'                 => trim($bn),
				  'goodsno'                =>$goodsno,
				  'price'              => intval(str_replace("$","",$data[9])),
				  'pricedesc'          => intval(str_replace("$","",$data[10])),
				  'cost'           => intval($data[11]),
				  'memberprice'              => intval($data[12]),
				  'combipoint'              => intval($data[13]),
				  'unit'               => trim($data[14]),
				  'ifalarm'            => intval($data[15]),
				  'alarmnum'           => intval($data[16]),
				  'video_url'          => trim($data[18]),
				  'intro'              => $data[19],
				  'alarmcontent'       => trim($data[20]),
				  'keywords'           => $data[21],
				  'cap_des'              => trim($data[22]),
				  'body'               =>  $data[23],
				  'ifpub'              => intval($data[24]),
				  'ifrecommend'        => intval($data[25]),
				  'ifspecial'          => intval($data[26]),
				  'ifhot'              => intval($data[27]),
				  'point'              => intval($data[28]),
				  'ifbonus'            => intval($data[29]),
				  'bonusnum'           => intval($data[30]),
				  'ifxygoods'              => intval($data[31]),
				  'xycount'              => intval($data[32]),
				  'ifxy'              => intval($data[33]),
				  'ifchange'              => intval($data[34]),
				  'ifadd'              => intval($data[35]),
				  'addmoney'              => intval($data[36]),
				  'addprice'              => intval($data[37]),
				  'ifpresent'      =>  intval($data[38]),
				  'present_money'      =>  intval($data[39]),
				  'present_endmoney'      =>  intval($data[40]),
				  'ifjs'               => intval($data[41]),
				  'js_begtime'         => $data[42],
				  'js_endtime'         => $data[43],
				  'js_price'           => $data[44] . "||" . $data[46] . "||" . $data[48] . "||" . $data[50] . "||" . $data[52],
				  'jscount'           => $data[45] . "||" . $data[47] . "||" . $data[49] . "||" . $data[51] . "||" . $data[53],
				  'js_totalnum'        => intval($data[54]),
				  'ifsales'              => intval($data[55]),
				  'sale_subject'              => intval($data[56]),
				  'sale_price'              => intval($data[57]),
				  'ifsaleoff'              => intval($data[58]),
				  'saleoff_starttime'      => $data[59],
				  'saleoff_endtime'      => $data[60],
				  'iftimesale'              => intval($data[61]),
				  'timesale_starttime'      => $data[62],
				  'timesale_endtime'      => $data[63],
				  'saleoffprice'              => intval($data[64]),
				  'trans_type'              => intval($data[65]),
				  'trans_special'              => intval($data[66]),
				  'trans_special_money'              => intval($data[67]),
				  'iftransabroad'              => intval($data[68]),
				  'transtype'              => intval($data[69]),
				  'transtypemonty'              => intval($data[70]),
				  'addtransmoney'              => intval($data[71]),
				  'ifmood'              => intval($data[72]),
				  'view_num'              => intval($data[73]),
				  'idate'              => time(),
				  'guojima'              => ($data[74]),
				  'xinghao'              => ($data[75]),
				  'iftogether'              => intval($data[76]),
				  'weight'              => ($data[77]),
					)      );
				
				
					 $Sql="INSERT INTO `{$INFO[DBPrefix]}goods` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
					$Result=$DB->query($Sql);
					$gid = mysql_insert_id();
	$FUNCTIONS->setStorage(intval($data[17]),0,$gid,0,"","","",0);
				}
				
					}
		$i++;
	}
	fclose ($handle);
	if($Result){
		$FUNCTIONS->setLog("商品導入");
		$FUNCTIONS->sorry_back("admin_goods_excel_manager.php","successfully uploaded");
	}else{
		$FUNCTIONS->sorry_back("admin_goods_excel_manager.php","");
	}
	@header("location:admin_goods_excel_manager.php");
}
function big52utf8($big5str) {

$blen = strlen($big5str);
$utf8str = "";

for($i=0; $i<$blen; $i++) {

$sbit = ord(substr($big5str, $i, 1));
//echo $sbit;
//echo "<br>";
if ($sbit < 129) {
$utf8str.=substr($big5str,$i,1);
}elseif ($sbit > 128 && $sbit < 255) {
$new_word = iconv("BIG5", "UTF-8", substr($big5str,$i,2));
$utf8str.=($new_word=="")?"?":$new_word;
$i++;
}
}

return $utf8str;

}
function num($no){
	return str_repeat("0",6-strlen($no)) . $no;
}
function formatstr($str){
	$str = str_replace(",","，",$str);
	$str = str_replace("\"","“",$str);
	$str = str_replace("\r"," ",$str);
	$str = str_replace("\n"," ",$str);
	return $str;
}
function getC($no){
	$start = 65;
	$end = 90;
	 $len = intval($no/26);
	if($len>0){
		$result = chr($start+$len-1);
		$result .= chr($no%26+$start);
	}else{
		$result = chr($start+$no);	
	}
	//echo $result;
	return $result;
}
?>
