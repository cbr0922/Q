<?php
@session_start();
include_once Classes . "/global.php";
@header("Content-type: text/html; charset=utf-8");
include_once "../language/".$INFO['IS']."/JsMenu.php";
?>

<script language="JavaScript">

<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
	if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
		document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
		else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

//设定点击是否出等待提示
var clickWait = "YES";
// -->
</script>

<script type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
		if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function SS_showHideLayers() { //v6.0
	var i,p,v,obj,args=SS_showHideLayers.arguments;
	for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
	if (obj.style) { oriobj=obj;obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v;}
	obj.visibility=v;
	document.getElementById('iframe_mask').style.visibility=v;
	}
}
//-->
</script>
<script language=javascript src='../js/smartshops.js'></script>
<SCRIPT language=JavaScript src="../js/JSCookMenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../js/theme.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript type=text/javascript>

// open
function openwin(url) {
	window.showModalDialog(url, "", "dialogHeight:400px; dialogWidth:506px; resizable:no;help:no; status:no;center:yes;scroll:no;");
}

function openqus(url) {
	window.open(url, 'shopquestion', 'width=820,height=500,resizable=1,scrollbars=1,status=no,toolbar=no,location=no,menu=no');
}


var myMenu =
[


	['<img src="./images/<?php echo $INFO[IS]?>/menu-config.gif" />','&nbsp;<?php echo $JsMenu[SYS_Info]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[SYS_Info]?>', //系统信息
	    ['<img src="./images/<?php echo $INFO[IS]?>/aboutus-24.gif" />', '<?php echo $JsMenu[About_Us_I];?>', 'admin_otherinfo.php?info_id=1&Action=Modi', null, '<?php echo $JsMenu[About_Us_I];?>'], //关于我们
	    ['<img src="./images/<?php echo $INFO[IS]?>/problem-24.gif" />', '<?php echo $JsMenu[About_Us_II];?>', 'admin_otherinfo.php?info_id=2&Action=Modi', null, '<?php echo $JsMenu[About_Us_II];?>'], //常见问题
	    ['<img src="./images/<?php echo $INFO[IS]?>/secrity-24.gif" />', '<?php echo $JsMenu[About_Us_III];?>', 'admin_otherinfo.php?info_id=3&Action=Modi', null, '<?php echo $JsMenu[About_Us_III];?>'], //安全交易
		['<img src="./images/<?php echo $INFO[IS]?>/trade-24.gif" />', '<?php echo $JsMenu[About_Us_IV];?>', 'admin_otherinfo.php?info_id=4&Action=Modi', null, '<?php echo $JsMenu[About_Us_IV];?>'], //购买流程
		['<img src="./images/<?php echo $INFO[IS]?>/buy-24.gif" />', '<?php echo $JsMenu[About_Us_V];?>', 'admin_otherinfo.php?info_id=5&Action=Modi', null, '<?php echo $JsMenu[About_Us_V];?>'], //如何付款
		['<img src="./images/<?php echo $INFO[IS]?>/contactus-24.gif" />', '<?php echo $JsMenu[About_Us_VI];?>', 'admin_otherinfo.php?info_id=6&Action=Modi', null, '<?php echo $JsMenu[About_Us_VI];?>'], //联络我们
		['<img src="images/<?php echo $INFO[IS]?>/icon-copr.gif" />', '<?php echo $JsMenu[Hz] ?>', 'admin_otherinfo.php?info_id=8&Action=Modi', null, '<?php echo $JsMenu[Hz] ?>'], //合作提案
		['<img src="./images/<?php echo $INFO[IS]?>/icon-about-0.gif" />', '<?php echo $JsMenu[About_Us_VII];?>', 'admin_otherinfo.php?info_id=7&Action=Modi', null, '<?php echo $JsMenu[About_Us_VII];?>'], //版权信息
	],


	['<img class="seq1" src="./images/<?php echo $INFO[IS]?>/menu-config.gif" />','&nbsp;<?php echo $JsMenu[Set]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Set];//设置?>',
		['<img src="images/<?php echo $INFO[IS]?>/program.gif" />', '<?php echo $JsMenu[Sys_Set];//系统设置?>', null, null, '<?php echo $JsMenu[Sys_Set]?>',
			['<img src="images/<?php echo $INFO[IS]?>/icon-basicsetup-0.gif" />', '<?php echo $JsMenu[Basic_Info];//基本信息?>', 'admin_<?php echo VersionArea ?>_sys.php', null, '<?php echo $JsMenu[Basic_Info]?>'],
			['<img src="images/<?php echo $INFO[IS]?>/icon-indexcon-0.gif" />','<?php echo $JsMenu[Index_Set];//首页设置?>','admin_indexseting.php',null,'<?php echo $JsMenu[Index_Set]?>'],
			['<img src="images/<?php echo $INFO[IS]?>/icon-passwdmodi-0.gif" />', '<?php echo $JsMenu[Change_Pass];//修改密码?>', 'admin_psw.php', null, '<?php echo $JsMenu[Change_Pass]?>'],
			['<img src="images/<?php echo $INFO[IS]?>/icon-send-0.gif" />', '<?php echo $JsMenu[Send_Type];//配送方式?>', 'admin_ttype_list.php', null, '<?php echo $JsMenu[Send_Type]?>'],
			['<img src="images/<?php echo $INFO[IS]?>/icon-time.gif" />', '<?php echo $JsMenu[HomeSend_TimeType];//宅配時間?>', 'admin_timetype_list.php', null, '<?php echo $JsMenu[HomeSend_TimeType]?>'],
			['<img src="images/ac0001-24.gif" />', '<?php echo $JsMenu[Pay_Type];//付款方式?>', 'admin_ptype_list.php', null, '<?php echo $JsMenu[Pay_Type]?>'],
			['<img src="./images/<?php echo $INFO[IS]?>/icon-memberagree-0.gif" />', '<?php echo $JsMenu[Member_Agree]?>','admin_member_xy.php?info_id=8&Action=Modi',null,'<?php echo $JsMenu[Member_Agree]?>'], //会员协议

		],

		['<img src="images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Templates_Man];//模板管理?>',null,null,'<?php echo $JsMenu[Templates_Man]?>',
			['<img src="images/<?php echo $INFO[IS]?>/icon-templet.gif" />','<?php echo $JsMenu[Templates_List];//模板列表?>','admin_template.php',null,'<?php echo $JsMenu[Templates_List]?>'],
		],

		['<img src="images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[User_Man];//用户管理?>',null,null,'<?php echo $JsMenu[User_Man]?>',
			['<img src="images/<?php echo $INFO[IS]?>/icon-managerlist.gif" />','<?php echo $JsMenu[User_List];//用户列表?>','admin_operater_list.php',null,'<?php echo $JsMenu[User_List]?>'],
			['<img src="images/<?php echo $INFO[IS]?>/icon-manageradd.gif" />','<?php echo $JsMenu[User_Add];//用户添加?>','admin_operater.php',null,'<?php echo $JsMenu[User_Add]?>'],
		],

		['<img src="images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Email_Man];//邮件管理?>',null,null,'<?php echo $JsMenu[Email_Man]?>',
			['<img src="images/<?php echo $INFO[IS]?>/icon-mailsetup-0.gif" />','<?php echo $JsMenu[Email_Set];//邮件设置?>','admin_mailbasic.php',null,'<?php echo $JsMenu[Email_Set]?>'],
			['<img src="images/<?php echo $INFO[IS]?>/icon-mailsendsetup-0.gif" />','<?php echo $JsMenu[Email_Send];//发送设置?>','admin_mailset_list.php',null,'<?php echo $JsMenu[Email_Send]?>'],
		],
	],




	['<img src="./images/<?php echo $INFO[IS]?>/menu-config.gif" />','&nbsp;<?php echo $JsMenu[Article_Man]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Article_Man]?>', //文章管理

	        ['<img src="./images/<?php echo $INFO[IS]?>/icon-infoclass-0.gif" />', '<?php echo $JsMenu[Article_Class]?>', 'admin_ncat_list.php', null, '<?php echo $JsMenu[Article_Class]?>'], //文章类别
		    ['<img src="./images/<?php echo $INFO[IS]?>/icon-infoclassadd-0.gif" />', '<?php echo $JsMenu[Article_Class_Add]?>', 'admin_ncat.php', null, '<?php echo $JsMenu[Article_Class_Add]?>'], //添加文章类别
		     _cmSplit,
		 	['<img src="./images/<?php echo $INFO[IS]?>/icon-infolist-0.gif" />', '<?php echo $JsMenu[Article_List]?>', 'admin_ncon_list.php', null, '<?php echo $JsMenu[Article_List]?>'], //文章列表
			['<img src="./images/<?php echo $INFO[IS]?>/icon-infoadd-0.gif" />', '<?php echo $JsMenu[Article_Add]?>', 'admin_ncon.php', null, '<?php echo $JsMenu[Article_Add]?>'], //添加文章

	],



	['<img src="./images/<?php echo $INFO[IS]?>/menu-config.gif" />','&nbsp;<?php echo $JsMenu[Product_Man]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Product_Man]?>', //商品管理

			['<img src="images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Product_Man]?>',null,null,'<?php echo $JsMenu[Product_Man]?>', //商品管理



			['<img src="./images/<?php echo $INFO[IS]?>/icon-goodscon-0.gif" />','<?php echo $JsMenu[Product_List]?>','admin_goods_list.php',null,'<?php echo $JsMenu[Product_List]?>'], //全部商品列表
			['<img src="./images/<?php echo $INFO[IS]?>/icon-goodsadd-0.gif" />','<?php echo $JsMenu[Product_Add]?>','admin_goods.php',null,'<?php echo $JsMenu[Product_Add]?>'], //添加商品
			['<img src="./images/<?php echo $INFO[IS]?>/excel_out_icon.gif" />','<?php echo $JsMenu[Product_Excel]?>','admin_goods_excel_manager.php',null,'<?php echo $JsMenu[Product_Excel]?>'], //EXCEL
			['<img src="./images/watermark.gif"  />','<?php echo $JsMenu[Product_WaterMark]?>','admin_goods_watermark_manager.php',null,'<?php echo $JsMenu[Product_WaterMark]?>'], //商品水印

			],


			['<img src="images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Product_Class_Name]?>',null,null,'<?php echo $JsMenu[Product_Class_Name]?>', //商品类别
			['<img src="./images/<?php echo $INFO[IS]?>/icon-goodslist-0.gif" />','<?php echo $JsMenu[Product_Class_List]?>','admin_pcat_list.php',null,'<?php echo $JsMenu[Product_Class_List]?>'], //商品类别列表
            ['<img src="./images/<?php echo $INFO[IS]?>/icon-goodslistadd-0.gif" />','<?php echo $JsMenu[Product_Class_Add]?>','admin_pcat.php',null,'<?php echo $JsMenu[Product_Class_Add]?>'], //添加商品类别
			],


		['<img src="images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Brand_Man]?>',null,null,'<?php echo $JsMenu[Brand_Man]?>', //品牌管理
			['<img src="./images/<?php echo $INFO[IS]?>/icon-brandlist.gif" />','<?php echo $JsMenu[Brand_List]?>','admin_brand_list.php',null,'<?php echo $JsMenu[Brand_List]?>'], //商品品牌列表
			['<img src="./images/<?php echo $INFO[IS]?>/icon-brandadd.gif" />','<?php echo $JsMenu[Brand_Add]?>','admin_brand.php',null,'<?php echo $JsMenu[Brand_Add]?>'], //新增品牌名稱
		],

		['<img src="images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Subject]?>',null,null,'<?php echo $JsMenu[Subject]?>', //主题类别
			['<img src="./images/<?php echo $INFO[IS]?>/icon-subjectlist-24.gif" />','<?php echo $JsMenu[Subject_Man]?>','admin_subject_list.php',null,'<?php echo $JsMenu[Subject_Man]?>'], //主题类别管理
            ['<img src="./images/<?php echo $INFO[IS]?>/icon-subjectadd-24.gif" />','<?php echo $JsMenu[Subject_Add]?>','admin_subject.php',null,'<?php echo $JsMenu[Subject_Add]?>'], //添加主题类别
			_cmSplit,
   	    	['<img src="./images/<?php echo $INFO[IS]?>/icon-subjectgoodlist-24.gif" />','<?php echo $JsMenu[Subject_List]?>','admin_SubjectProject_list.php',null,'<?php echo $JsMenu[Subject_List]?>'], //主题商品列表
		],

			_cmSplit,
			['<img src="./images/<?php echo $INFO[IS]?>/icon-bonusproductlist_24.gif" />','<?php echo $JsMenu[Bonus_List]?>','admin_bonus_list.php',null,'<?php echo $JsMenu[Bonus_List]?>'], //红利商品列表
		    _cmSplit,
		    ['<img src="./images/<?php echo $INFO[IS]?>/icon-goodsdiscuss-0.gif" />','<?php echo $JsMenu[Product_Comment]?>','admin_comment_list.php',null,'<?php echo $JsMenu[Product_Comment]?>'], //商品评论


	],


		['<img src="./images/<?php echo $INFO[IS]?>/menu-config.gif" />','&nbsp;<?php echo $JsMenu[Order_Man]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Order_Man]?>', //订单管理

			['<img src="./images/<?php echo $INFO[IS]?>/icon-order-0.gif" />','<?php echo $JsMenu[Order_List]?>','admin_order_list.php',null,'<?php echo $JsMenu[Order_List]?>'], //订单列表
			['<img src="./images/<?php echo $INFO[IS]?>/icon-order-finish.gif" />','<?php echo $JsMenu[Order_Records_List]?>','admin_order_list.php?State=AllPigeonhole',null,'<?php echo $JsMenu[Order_Records_List]?>'], //已归档列表
		_cmSplit,
			['<img src="./images/<?php echo $INFO[IS]?>/icon-bonuslist.gif" />','<?php echo $JsMenu[Bonus_History_List]?>','admin_bonushistory_list.php',null,'<?php echo $JsMenu[Bonus_History_List]?>'], //红利兑换单

	],


	['<img src="./images/<?php echo $INFO[IS]?>/menu-config.gif" />','&nbsp;<?php echo $JsMenu[Functions]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Functions]?>',  //功能

		    ['<img src="./images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Customer_Service_Man]?>',null,null,'<?php echo $JsMenu[Customer_Service_Man]?>', //客服管理
	      ['<img src="./images/<?php echo $INFO[IS]?>/icon-onlineservice.gif" />', '<?php echo $JsMenu[Customer_Online]?>', 'admin_kefu_list.php', null, '<?php echo $JsMenu[Customer_Online]?>'], //線上客服
	      ['<img src="./images/<?php echo $INFO[IS]?>/icon-onlinekind.gif" />', '<?php echo $JsMenu[onlinekind]?>', 'admin_kefu_type_list.php', null, '<?php echo $JsMenu[onlinekind]?>'], //問題類別管理
	      ['<img src="./images/<?php echo $INFO[IS]?>/icon-onlineservice_status.gif" />', '<?php echo $JsMenu[onlineservice_status]?>', 'admin_kefu_chuli_list.php', null, '<?php echo $JsMenu[onlineservice_status]?>'], //處理情況管理
	      ['<img src="./images/<?php echo $INFO[IS]?>/icon-onlineservice_temlate.gif" />', '<?php echo $JsMenu[onlineservice_temlate]?>', 'admin_kefu_tem_list.php', null, '<?php echo $JsMenu[onlineservice_temlate]?>'], //回覆樣板管理

     	],


		['<img src="images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Poll_Man]?>',null,null,'<?php echo $JsMenu[Poll_Man]?>', //投票管理
			['<img src="./images/<?php echo $INFO[IS]?>/icon-votecount.gif" />','<?php echo $JsMenu[Poll_List]?>','admin_poll_list.php',null,'<?php echo $JsMenu[Poll_List]?>'], //投票管理列表
			_cmSplit,
            ['<img src="./images/<?php echo $INFO[IS]?>/icont-voteadd.gif" />','<?php echo $JsMenu[Poll_Add] ?>','admin_poll_create.php',null,'<?php echo $JsMenu[Poll_Add] ?>'], //添加投票
		],

		['<img src="images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Member_Man]?>',null,null,'<?php echo $JsMenu[Member_Man]?>', //会员管理
			['<img src="./images/<?php echo $INFO[IS]?>/icon-memberlist-0.gif" />','<?php echo $JsMenu[Member_List]?>','admin_member_list.php',null,'<?php echo $JsMenu[Member_List]?>'], //会员列表
			['<img src="./images/<?php echo $INFO[IS]?>/icon-memberadd_24.gif" />', '<?php echo $JsMenu[Member_Add]?>','admin_member.php',null,'<?php echo $JsMenu[Member_Add] ?>'],//会员等级
			['<img src="./images/<?php echo $INFO[IS]?>/icon-memberlevel-0.gif" />', '<?php echo $JsMenu[Member_Level]?>','admin_level_list.php',null,'<?php echo $JsMenu[Member_Level]?>'],//会员等级
		],

		['<img src="./images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Hz]?>',null,null,'<?php echo $JsMenu[Hz]?>', //合作提案
			['<img src="images/<?php echo $INFO[IS]?>/icon-copr.gif" />', '<?php echo $JsMenu[Hz_List]?>', 'admin_contact_list.php', null, '<?php echo $JsMenu[Hz_List]?>'], //合作提案
		],
	],
	['<img src="./images/<?php echo $INFO[IS]?>/menu-config.gif" />','&nbsp;<?php echo $JsMenu[Tools]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Tools]?>', //工具
		['<img src="./images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Advertis_Man]?>',null,null,'<?php echo $JsMenu[Advertis_Man]?>', //广告管理
			['<img src="./images/<?php echo $INFO[IS]?>/icon-banner-0.gif" />','<?php echo $JsMenu[Advertis_List]?>',"admin_adv_list.php",null,'<?php echo $JsMenu[Advertis_List]?>'], //广告列表
			['<img src="./images/<?php echo $INFO[IS]?>/icon-banneradd-0.gif" />','<?php echo $JsMenu[Advertis_Add]?>',"admin_adv.php",null,'<?php echo $JsMenu[Advertis_Add]?>'], //添加广告
		],
		['<img src="./images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Link_Friend]?>',null,null,'<?php echo $JsMenu[Link_Friend]?>', //友情链接
			['<img src="images/<?php echo $INFO[IS]?>/icon-linksetup-0.gif" />', '<?php echo $JsMenu[Link_Friend_List]?>', 'admin_link_list.php', null, '<?php echo $JsMenu[Link_Friend_List]?>'], //链接列表
			['<img src="images/<?php echo $INFO[IS]?>/icon-linkadd-0.gif" />', '<?php echo $JsMenu[Link_Friend_Add]?>', 'admin_link.php', null, '<?php echo $JsMenu[Link_Friend_Add]?>'], //添加链接
		],


		['<img src="./images/<?php echo $INFO[IS]?>/program.gif" />','<?php echo $JsMenu[Shop_Pager]?>',null,null,'<?php echo $JsMenu[Shop_Pager]?>', //网店会刊
			['<img src="./images/<?php echo $INFO[IS]?>/icon-maillistsetup-0.gif" />', '<?php echo $JsMenu[Email_Group_Set]?>', 'admin_group_list.php', null, '<?php echo $JsMenu[Email_Group_Set]?>'], //邮件组设定
			['<img src="./images/<?php echo $INFO[IS]?>/icon-mailmaglist-0.gif" />', '<?php echo $JsMenu[Shop_Pager_List]?>', 'admin_publication_list.php', null, '<?php echo $JsMenu[Shop_Pager_List]?>'], //会刊列表
			['<img src="./images/<?php echo $INFO[IS]?>/icon-mailoutputlist-0.gif" />', '<?php echo $JsMenu[Email_Exprot_List]?>', 'admin_outputemail.php', null, '<?php echo $JsMenu[Email_Exprot_List]?>'], //导出邮件列表
		],



		['<img src="./images/<?php echo $INFO[IS]?>/program.gif" />', '<?php echo $JsMenu[TjFx]?>', null, null, '<?php echo $JsMenu[TjFx]?>', //统计分析
			['<img src="./images/<?php echo $INFO[IS]?>/icon-salecount-0.gif" />', '<?php echo $JsMenu[Visit] ?>', 'Visit.php', null, '<?php echo $JsMenu[Visit] ?>'], //访问统计
			['<img src="./images/<?php echo $INFO[IS]?>/icon-packcount-0.gif" />', '<?php echo $JsMenu[Sale]?>', 'SaleMap.php', null, '<?php echo $JsMenu[Sale]?>'], //销售统计
//			['<img src="./images/<?php echo $INFO[IS]?>/icon-filter-0.gif" />', '过滤设置', 'Cache.php', null, '过滤设置'],
		],
		['<img src="./images/<?php echo $INFO[IS]?>/program.gif" />', '<?php echo $JsMenu[System_Tools]?>', null, null, '<?php echo $JsMenu[System_Tools]?>',
			['<img src="images/<?php echo $INFO[IS]?>/icon-databackup-0.gif" />', '<?php echo $JsMenu[DataBackup]?>', 'admin_databack.php', null, '<?php echo $JsMenu[DataBackup]?>'], //数据库备份
			['<img src="images/<?php echo $INFO[IS]?>/icon-datarecover-0.gif" />', '<?php echo $JsMenu[DataResume]?>', 'admin_dataresume.php', null, '<?php echo $JsMenu[DataResume]?>'], //数据库恢复
		],
		['<img src="./images/ie22.gif" />','<?php echo $JsMenu[StaticHtml]?>','admin_static_html.php',null,'<?php echo $JsMenu[StaticHtml]?>'], //静态化页面
		['<img src="./images/google.gif" />','Google Analytics','admin_Google_Analytics.php',null,'Google Analytics'], //Google Analytics（分析）

	],




	['<img src="./images/<?php echo $INFO[IS]?>/menu-config.gif" />','&nbsp;<?php echo $JsMenu[Provider_Man]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Provider_Man]?>', //供货商管理
	    ['<img src="./images/<?php echo $INFO[IS]?>/icon-providerlist_24.gif" />', '<?php echo $JsMenu[Provider_List];?>', 'admin_provider_list.php', null, '<?php echo $JsMenu[Provider_List];?>'], //供货商列表
	    ['<img src="./images/<?php echo $INFO[IS]?>/icon-addprovider_24.gif" />', '<?php echo $JsMenu[Provider_Add];?>', 'admin_provider.php', null, '<?php echo $JsMenu[Provider_Add];?>'], //添加供货商
	    ['<img src="./images/<?php echo $INFO[IS]?>/icon-gginfolist-24.gif" />', '<?php echo $JsMenu[Provider_News_List]?>', 'provider_ncon_list.php', null, '<?php echo $JsMenu[Provider_News_List]?>'], //公告列表
		['<img src="./images/<?php echo $INFO[IS]?>/icon-gginfoadd-24.gif" />', '<?php echo $JsMenu[Provider_News_Add]?>', 'provider_ncon.php', null, '<?php echo $JsMenu[Provider_News_Add]?>'], //添加公告

	],

	['<img src="./images/<?php echo $INFO[IS]?>/menu-config.gif" />','&nbsp;<?php echo $JsMenu[Help]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Help]?>', //帮助
	    ['<img src="./images/<?php echo $INFO[IS]?>/icon-gotohome-0.gif" />', '<?php echo $JsMenu[Visit_Home];?>', 'http://www.shopnc.net', null, '<?php echo $JsMenu[Visit_Home];?>'], //访问主页
	    ['<img src="./images/<?php echo $INFO[IS]?>/icon-questions-0.gif" />', '<?php echo $JsMenu[Problem];?>', 'http://www.shopnc.net/bbs', null, '<?php echo $JsMenu[Problem];?>'], //在线答疑
	],
];

</SCRIPT>

<TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD align="left" width="10%" background=./images/<?php echo $INFO[IS]?>/topbg.gif>
    </TD>
    <TD width="60%" background=./images/<?php echo $INFO[IS]?>/topbg.gif height=28>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%"  background=./images/<?php echo $INFO[IS]?>/menubg.gif border=0>
        <TBODY>
        <TR>
          <TD width="11%" valign="top"><IMG src="./images/<?php echo $INFO[IS]?>/menuleft.gif"   width=42 height=26></TD>
          <TD vAlign=baseline align=middle width="86%">
            <TABLE class=menudottedline cellSpacing=0 cellPadding=0 width="100%"  align=center border=0>
              <TBODY>
              <TR>
                <TD class=menubackgr align=middle>


</TD>
			    <TD class=menubackgr align=middle>
				  <DIV id=myMenuID></DIV>

                  <SCRIPT language=JavaScript type=text/javascript>
			       	cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
				</SCRIPT>

				</TD>
              </TR>
			 </TBODY>
			</TABLE>
		   </TD>
          <TD align=right width="3%">
		  <IMG height=26  src="./images/<?php echo $INFO[IS]?>/menuright.gif" width=39>
		  </TD>
		 </TR>
		</TBODY>
	   </TABLE>
	 </TD>
     <TD width="5%" height=28 align=middle nowrap="nowrap" background=./images/<?php echo $INFO[IS]?>/topbg.gif>
	 <A  href="<?php echo $INFO['site_url']?>/" target=_blank><IMG height=20 src="./images/<?php echo $INFO[IS]?>/icon-ordersearch-1.gif" width=20 border=0><?php echo $JsMenu[View_Index]?></A>	 </TD>
     <TD width="5%" height="28" align=middle nowrap="nowrap" background=./images/<?php echo $INFO[IS]?>/topbg.gif>
	 <A onClick="javascript:if (confirm('<?php echo $JsMenu[Logout_Alert]?>')) return window.location='<?php echo $INFO['site_shopadmin']?>/login.php?Action=Logout';else return false;"  href="<?php echo $INFO['site_shopadmin']?>/login.php?Action=Logout">
	  <IMG height=16  src="./images/<?php echo $INFO[IS]?>/icon-logout.gif" width=16 border=0><?php echo $JsMenu[Logout]?></A>
	  <A id=linkact_htc></A>	 </TD>
   </TR>
   </TBODY>
  </TABLE>
