<?php

function  File_Accept($FileName)
{

	$FileName = trim($FileName);
	$AcceptNum= "";

	switch ($FileName){
			//主功能：系统信息
		case "admin_otherinfo":
		$AcceptNum = '140'.intval($_GET['info_id']);//1401关于我们
		//1402常见问题
		//1403安全交易
		//1404购买流程
		//1405如何购买
		//1406联络我们
		//1407版权信息
		//1408合作提案
			break;
		//主功能：设置中相关文件

		case "admin_big5_sys":
			$AcceptNum= "1001"; //基本信息
			break;
		case "admin_indexseting":
			$AcceptNum= "1002"; //首页设置
			break;
		case "admin_psw":
			$AcceptNum= "1003"; //修改密码
			break;
		case "admin_ttype_list":
			$AcceptNum= "1004"; //配送方式
			break;
		case "admin_ptype_list":
			$AcceptNum= "1005"; //付款方式
			break;
		case "admin_timetype_list":
			$AcceptNum= "1006"; //宅配类型
			break;
		case "admin_timetype":
			$AcceptNum= "1006"; //宅配类型
			break;
		case "admin_twpay_manage":
			$AcceptNum= "1008"; //宅配类型
			break;
		case "admin_otherinfo":
			$AcceptNum= "1007"; //系统信息
			break;


			//主功能：设置中摸版管理
		case "admin_template":
			$AcceptNum= "1301"; //摸版
			break;

			//主功能：设置中用户管理

		case "admin_operater_list":
			$AcceptNum= "1101"; //管理員列表
			break;
		case "admin_operater":
			$AcceptNum= "1102"; //新增管理員
			break;
		case "admin_privilege":
			$AcceptNum= "1103"; //管理员权限设定
			break;
		case "admin_login_log":
			$AcceptNum= "1104"; //管理员权限设定
			break;


			//主功能：设置邮件管理

		case "admin_mailbasic":
			$AcceptNum= "1201"; //邮件设置
			break;
		case "admin_mailset_list":
			$AcceptNum= "1202"; //发送设置
			break;
		case "admin_core":
			$AcceptNum= "1501"; //发送设置
			break;
		

			//主功能：文章功能中相关文件

		case "admin_ncat_list":
			$AcceptNum= "2001"; //文章类别
			break;
		case "admin_ncat":
			$AcceptNum= "2002"; //文章类别添加
			break;
		case "admin_ncon_list":
			$AcceptNum= "2003"; //文章列表
			break;
		case "admin_ncon":
			$AcceptNum= "2004"; //文章添加
			break;



			//主功能：商品品牌设置中相关文件

		case "admin_brand_list":
			$AcceptNum= "2801"; //商品品牌列表
			break;
		case "admin_brand":
			$AcceptNum= "2802"; //新增品牌
			break;

			//主功能：商品设置中相关文件



		case "admin_pcat_list":
			$AcceptNum= "2101"; //商品类别
			break;
		case "admin_pcat":
			$AcceptNum= "2102"; //商品类别添加
			break;
		case "admin_goods_list":
			$AcceptNum= "2103"; //商品列表
			break;
		case "admin_goods":
			$AcceptNum= "2104"; //商品添加
			break;
		case "admin_goods_excel":
			$AcceptNum= "2105"; //导出商品Excel
			break;
		case "admin_goods_excel":
			$AcceptNum= "2105"; //导出商品Excel
			break;
		case "admin_comment_list":
			$AcceptNum= "2106"; //商品评论
			break;
		case "admin_cards_list":
			$AcceptNum= "2107"; //非实体商品
			break;
		case "admin_goods_excel_manager":
			$AcceptNum= "2108"; //非实体商品
			break;
		case "admin_goods_watermark_manager":
			$AcceptNum= "2109"; //非实体商品
			break;
		case "admin_goods_script":
			$AcceptNum= "2110"; //非实体商品
			break;
		case "admin_goods_collection_list":
			$AcceptNum= "2111"; //非实体商品
			break;
		case "admin_bonus_manager":
			$AcceptNum= "2112"; //非实体商品
			break;
		case "admin_bonuspoint_uselist":
			$AcceptNum= "2113"; //非实体商品
			break;




			//主功能：订单管理中相关文件

		case "admin_order_list":
			$AcceptNum= "2201"; //订单列表
			break;
		case "admin_order":
			$AcceptNum= "2202"; //订单
			break;


			//主功能：会员管理中相关文件

		case "admin_member_list":
			$AcceptNum= "2301"; //会员列表
			break;
		case "admin_member_xy":
			$AcceptNum= "2302"; //会员协议
			break;
		case "admin_level_list":
			$AcceptNum= "2303"; //会员等级
			break;
		case "admin_level":
			$AcceptNum= "2303"; //会员等级
			break;
		case "admin_member":
			$AcceptNum= "2304"; //新增会员
			break;
		case "admin_company_list":
			$AcceptNum= "2306"; //新增会员
			break;
		case "admin_email_list":
			$AcceptNum= "2307"; //新增会员
			break;
			/*
			case "admin_advance_list":
			$AcceptNum= "2305"; //会员预付款
			break;
			*/

			//主功能：合作提案相关文件

		case "admin_contact_list":
			$AcceptNum= "2901"; //合作提案列表
		case "admin_contact":
			$AcceptNum= "2901"; //合作提案列表

			break;


			//主功能：红利管理

		case "admin_bonus_list":
			$AcceptNum= "2401"; //红利商品列表
			break;
		case "admin_bonus":
			$AcceptNum= "2401"; //红利商品列表
			break;
		case "admin_bonushistory_list":
			$AcceptNum= "2402"; //红利兑换单
			break;



			//主功能：主题类别管理

		case "admin_subject_list":
			$AcceptNum= "2501"; //主题类别
			break;
		case "admin_subject":
			$AcceptNum= "2502"; //主题类别添加
			break;
		case "admin_SubjectProject_list":
			$AcceptNum= "2503"; //主题商品列表
			break;

			//主功能：投票管理

		case "admin_poll_list":
			$AcceptNum= "2601"; //投票管理
			break;
		case "admin_poll_create":
			$AcceptNum= "2602"; //新建立投票
			break;

			//主功能：论坛管理

		case "admin_fcat_list":
			$AcceptNum= "2701"; //论坛类别管理
			break;
		case "admin_fcss":
			$AcceptNum= "2702"; //论坛CSS
			break;
		case "admin_fcat":
			$AcceptNum= "2703"; //新建立论坛分类
			break;


			//主功能：广告管理中相关文件

		case "admin_adv_list":
			$AcceptNum= "3001"; //广告列表
			break;
		case "admin_adv":
			$AcceptNum= "3002"; //新增广告
			break;

			//主功能：友情连接管理中相关文件

		case "admin_link_list":
			$AcceptNum= "3101"; //链接列表
			break;
		case "admin_link":
			$AcceptNum= "3102"; //新增链接
			break;


			//主功能：网电会刊管理中相关文件

		case "admin_group_list":
			$AcceptNum= "3201"; //邮件组设定
			break;
		case "admin_publication_list":
			$AcceptNum= "3202"; //会刊列表
			break;
		case "admin_outputemail":
			$AcceptNum= "3203"; //导出邮件列表
			break;


			//主功能：客服留言中相关文件
		case "admin_kefu_list":
			$AcceptNum= "3501"; //客服留言
			break;
		case "admin_kefu":
			$AcceptNum= "3501"; //客服留言
			break;
		case "admin_kefu_type_list":
			$AcceptNum= "3502"; //客服留言
			break;
		case "admin_kefu_chuli_list":
			$AcceptNum= "3503"; //客服留言
			break;
		case "admin_kefu_tem_list":
			$AcceptNum= "3504"; //客服留言
			break;


		case "admin_kefu_type":
			$AcceptNum= "3502"; //客服留言
			break;
		case "admin_kefu_type_save":
			$AcceptNum= "3502"; //客服留言
			break;
		case "admin_kefu_chuli":
			$AcceptNum= "3503"; //客服留言
			break;
		case "admin_kefu_chuli_save":
			$AcceptNum= "3503"; //客服留言
			break;
		case "admin_kefu_tem":
			$AcceptNum= "3504"; //客服留言
			break;
		case "admin_kefu_tem_save":
			$AcceptNum= "3504"; //客服留言
			break;

			//主功能：统计管理中相关文件

		case "Visit":
			$AcceptNum= "3301"; //访问统计
			break;
		case "StreamTotal":
			$AcceptNum= "3301"; //访问统计
			break;
		case "RegisterMap":
			$AcceptNum= "3301"; //访问统计
			break;
		case "VisPageTotal":
			$AcceptNum= "3301"; //访问统计
			break;
		case "ClientTotal":
			$AcceptNum= "3301"; //访问统计
			break;
		case "VisIpTotal":
			$AcceptNum= "3301"; //访问统计
			break;
		case "VisUserTotal":
			$AcceptNum= "3301"; //访问统计
			break;




		case "SaleMap":
			$AcceptNum= "3302"; //销售统计
			break;
		case "SetLeach":
			$AcceptNum= "3303"; //过滤设置
			break;

			//主功能：系统工具管理中相关文件

		case "admin_updatesql":
			$AcceptNum= "3401"; //数据库更新
			break;
		case "admin_databack":
			$AcceptNum= "3402"; //数据库备份
			break;
		case "admin_dataresume":
			$AcceptNum= "3403"; //数据库恢复
			break;
		case "admin_ticket_list":
			$AcceptNum= "3601"; //数据库恢复
			break;
		case "admin_ticket":
			$AcceptNum= "3602"; //数据库恢复
			break;
		case "admin_Google_Analytics":
			$AcceptNum= "3604"; //数据库恢复
			break;
		case "admin_static_html":
			$AcceptNum= "3603"; //数据库恢复
			break;
			


			//主功能：供貨商管理中相关文件

		case "admin_provider_list":
			$AcceptNum= "4001"; //供貨商列表
			break;
		case "admin_provider":
			$AcceptNum= "4002"; //新增供貨商
			break;
		case "provider_ncon_list":
			$AcceptNum= "4003"; //公告列表
			break;
		case "provider_ncon":
			$AcceptNum= "4004"; //新增公告
			break;
		case "admin_service_list":
			$AcceptNum= "4005"; //新增公告
			break;
		case "admin_service":
			$AcceptNum= "4006"; //新增公告
			break;
		case "admin_shopcat_list":
			$AcceptNum= "4007"; //新增公告
			break;
		case "admin_shopcat":
			$AcceptNum= "4008"; //新增公告
			break;



			/*主功能：帮助管理中相关文件

			case "admin_updatesql":
			$AcceptNum= "41"; //访问主页
			break;
			case "admin_databack":
			$AcceptNum= "42"; //查看更新
			break;
			case "admin_dataresume":
			$AcceptNum= "43"; //常见问题
			break;
			case "admin_updatesql":
			$AcceptNum= "44"; //问题反馈
			break;
			case "admin_databack":
			$AcceptNum= "45"; //关于AutoShop
			break;
			*/

		default:
			$AcceptNum ="";
			break;

	}

	return $AcceptNum;
}



//本函数将返回一个CHECKED

function RPrivilegeChecked ($Array,$ID){
	foreach ($Array as $k=>$v){
		if ($v==$ID){
			return "checked";
		}
	}
}
?>