<?php
//手機版類
class Mobile{
	function Mobile(){
		global $tpl,$PRODUCT,$INFO,$Article;
		include_once('cart.class.php' );
		//購物車
		if(!isset($_SESSION['cart'])) {
			//$_SESSION['cart'] = new Cart;
			$cart = new Cart;
		}
		if (!method_exists($cart,"getCart")){
		 //  $_SESSION['cart'] = new Cart;
		   $cart = new Cart;
		}
		$items_array = $cart->getCart();
		$Cart_item = array();
		$i = 0;
		$MemberPice_total = 0;
		$num = 0;
		if (is_array($items_array)){
			foreach($items_array as $k=>$v){
				$j = 0;
				$Cart_item[$i]['key'] = intval($k);
				if (is_array($v)){
					foreach($v as $kk=>$vv){
						if ($vv['packgid']==0){
							$Cart_item[$i]['goods'][$j] = $vv;
							$Cart_item[$i]['goods'][$j]['total'] = $vv['count'] * $vv['price'];
							$MemberPice_total+=$Cart_item[$i]['goods'][$j]['total'];
							$num+=$vv['count'];
							$j++;
						}
					}
				}
				$i++;
			}
		}
		$tpl->assign("CountItem",       $num);
		$MemberState =  !empty($_SESSION['user_id']) ? 1 : 0 ;
		$tpl->assign("Session_truename", $_SESSION['true_name']); //登陆后用户名
		$tpl->assign("Session_username", $_SESSION['username']);
		$tpl->assign("Session_userlevel",$_SESSION['userlevelname']); //登陆后用户等级
		$tpl->assign("MemberState", $MemberState); //用户状态
		if(!isset($PRODUCT)){
			include_once("product.class.php");
			$PRODUCTs = new PRODUCT;
		}
		$productclass_array = $PRODUCTs->getProductClass(0,intval($INFO['mobile_showproductclass']));
		if(!isset($Article)){
			include_once("article.class.php");
			$Articles = new Article_Class;
		}
		$articleclass_array = $Articles->ArticleClass_Array(0);
		$cur_array = explode("/",$_SERVER["PHP_SELF"]);
		$cur_page = $cur_array[count($cur_array)-1];
		$TagAdv_array = $this->getAdv(91,0,"",0,$cur_page);
		//print_r($TagAdv_array);
		foreach($TagAdv_array as $k=>$v){
			$tpl->assign("TagAdv_" . $v['adv_tag'],     $v['img']);
		}
		//Google Analytics
		$Query   = $DB->query("select info_id , info_content from `{$INFO[DBPrefix]}admin_info` where info_id='4' limit 0,1");
		$Result  = $DB->fetch_array($Query);
		if (trim($Result[info_content])!="" ){
				$google_Js = "
			<!------------------ google-analytics  Start --------------------------------------------------->
			<script type=\"text/javascript\">
			  var _gaq = _gaq || [];
			  _gaq.push(['_setAccount', '" . $Result[info_content] . "']);
			  _gaq.push(['_trackPageview']);
			  (function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
			</script>
			<!------------------ google-analytics  End  --------------------------------------------------->
			";
		
			$tpl->assign("googleAnalytics_js",   $google_Js);      
		}
		$tpl->assign("ProductListAll",     $productclass_array);
		$tpl->assign("Ncat",     $articleclass_array);
		$tpl->assign("en_url_From",     base64_encode($cur_page));
		$tpl->assign("copyright",     $INFO['mobile_copyright']);
	}
	/**
	獲得廣告列表
	**/
	function getAdv($advtype,$count=0,$tagname = "",$ifrand=0,$position=""){
		global $DB,$INFO;
		$Sql = " select * from `{$INFO[DBPrefix]}advertising` where adv_display = 1 and adv_type='" . intval($advtype) . "' and (start_time='' or start_time<='" . time() . "') and (end_time='' or end_time>='" . time() . "')";
		if($tagname!="")
			$Sql .= " and adv_tag='" . $tagname . "'";
		if($position!="")
			$Sql .= " and (position like '%" . $position . "%' or position='')";
		if($ifrand==1)
			$Sql .= " order by rand()";
		else
			$Sql .= " order by orderby";
		if($count>0)
			$Sql .= " limit 0," . intval($count);
		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);
		$adv_array = array();
		$i = 0;
		while($Result = $DB->fetch_array($Query)){
			$adv_array[$i]['adv_tag'] = $Result['adv_tag'];
			$adv_array[$i]['img'] = $Result['adv_content'];
			$adv_array[$i]['url'] = $Result['adv_left_url']==""?"#":$INFO['site_url'] ."/modules/advertising/clickadv.php?advid=" .$Result['adv_id']  . "&url=" .urlencode($Result['adv_left_url']);
			$adv_array[$i]['img'] = "<a href='" . $adv_array[$i]['url'] . "' target='_blank'>" . $adv_array[$i]['img'] . "</a>";
			$adv_array[$i]['title'] = $Result['adv_title'];
			$DB->query("update `{$INFO[DBPrefix]}advertising` set point_num=point_num+1 where adv_id=".intval($Result['adv_id']));  
			$i++;
		}
		return $adv_array;
	}
}
?>