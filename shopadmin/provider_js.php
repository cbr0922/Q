<?php 
@session_start();
include_once "Check_Admin.php";
include_once "../language/".$INFO['IS']."/JsMenu.php";
@header("Content-type: text/html; charset=utf-8");
?>
<SCRIPT language=JavaScript>

<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->
</SCRIPT>


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

	
	['<i class="icon-user" style="font-size:15px;margin-right:3px;text-shadow:0 -1px 0 rgba(0,0,0,0.5), 0 1px 0 rgba(255,255,255,1);"></i>','&nbsp;<?php echo $JsMenu[Functions]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Functions]?>',  //功能
		/*
		['<img src="images/<?php echo $INFO[IS]?>/program.gif" />', '<?php echo $JsMenu[Provider_News_Man]?>', null, null, '<?php echo $JsMenu[Provider_News_Man]?>', //公告管理
			['<img src="./images/<?php echo $INFO[IS]?>/icon-infolist-0.gif" />', '<?php echo $JsMenu[Provider_News_List]?>', 'provider_ncon_list.php', null, '<?php echo $JsMenu[Provider_News_List]?>'], //公告列表
			['<img src="./images/<?php echo $INFO[IS]?>/icon-infoadd-0.gif" />', '<?php echo $JsMenu[Provider_News_Add]?>', 'provider_ncon.php', null, '<?php echo $JsMenu[Provider_News_Add]?>'], //公告添加
		],
		
		*/
		['', '供應商資料', "provider_info.php", null, '供應商資料' ],
																																			   
		['','<?php echo $JsMenu[Product_Man]?>',null,null,'<?php echo $JsMenu[Product_Man]?>', //商品管理
			['<img src="./images/<?php echo $INFO[IS]?>/icon-goodscon-0.gif" />','<?php echo $JsMenu[Product_List]?>','provider_goods_list.php',null,'<?php echo $JsMenu[Product_List]?>'], //全部商品列表
			['<img src="./images/<?php echo $INFO[IS]?>/icon-goodsadd-0.gif" />','<?php echo $JsMenu[Product_Add]?>','provider_goods.php',null,'<?php echo $JsMenu[Product_Add]?>'], //商品添加
			['<img src="./images/<?php echo $INFO[IS]?>/icon-goodsadd-0.gif" />','商品匯出','provider_goods_excel.php?Action=OutputExcel&outtype=text',null,'商品匯出'], 
			_cmSplit,
			['<img src="./images/<?php echo $INFO[IS]?>/icon-goodsdiscuss-0.gif" />','<?php echo $JsMenu[Product_Comment]?>','provider_comment_list.php',null,'<?php echo $JsMenu[Product_Comment]?>'], //商品评论
		],
		['','促銷活動',null,null,'促銷活動', //商品管理
			['<img src="./images/<?php echo $INFO[IS]?>/icon-goodscon-0.gif" />','促銷活動列表','provider_discountsubject_list.php',null,'null'], //全部商品列表
		],
		['','問答中心',null,null,'問答中心', //商品管理
			['<img src="./images/<?php echo $INFO[IS]?>/icon-goodscon-0.gif" />','問答列表','provider_kefu_list.php',null,'null'], //全部商品列表
		],
		
	
	],
	    ['<i class="icon-list-alt" style="font-size:15px;margin-right:3px;text-shadow:0 -1px 0 rgba(0,0,0,0.5), 0 1px 0 rgba(255,255,255,1);"></i>','&nbsp;<?php echo $JsMenu[Order_Man]?>&nbsp;&nbsp;',null,null,'<?php echo $JsMenu[Order_Man]?>',  //订单管理
		['','<?php echo $JsMenu[Order_Man]?>',null,null,'<?php echo $JsMenu[Order_Man]?>', //订单管理
			['<img src="./images/<?php echo $INFO[IS]?>/icon-order-0.gif" />','<?php echo $JsMenu[Order_List]?>','provider_order_list.php',null,'<?php echo $JsMenu[Order_List]?>'], //订单列表
		],	
		 ['','銷售對帳','provider_statistic_provider.php',null,'銷售對帳'],  //订单管理
		
				
     ],	
];

</SCRIPT>
<TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0 style="background:linear-gradient(#616161, #5c5c5c, #424242) no-repeat;">
  <TBODY>
  <TR>
    <TD align=middle width="8%">&nbsp;</TD>
    <TD width="74%" height=28>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="11%" valign="top">&nbsp;</TD>
          <TD vAlign=baseline align=middle width="86%">
            <TABLE class=menudottedline cellSpacing=0 cellPadding=0 width="88%"  align=center border=0>
              <TBODY>
              <TR>
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
          <TD align=right width="3%">&nbsp;</TD>
		 </TR>
		</TBODY>
	   </TABLE>
	 </TD>
     <TD align=middle width="8%" height=35 class="top_preview_text"><div>
	 <A  href="<?php echo $INFO['site_url']?>" target=_blank><i class="icon-home" style="font-size:14px;margin-right:7px"></i><?php echo  $JsMenu[View_Index]?></A></div>
	 </TD>
     <TD width="10%" align=middle nowrap="nowrap" class="top_preview_text" style="padding-right:10px">
	 <div><A onClick="javascript:if (confirm('<?php echo $JsMenu[Logout_Alert]?>')) return window.location='<?php echo $INFO['site_shopadmin']?>/login.php?Action=Logout';else return false;"  href="<?php echo $INFO['site_shopadmin']?>/login.php?Action=Logout">
	  <i class="icon-off" style="font-size:14px;margin-right:7px"></i><?php echo $JsMenu[Logout]?></A></div>
	  <A id=linkact_htc></A></TD>
  </TR></TBODY></TABLE>
