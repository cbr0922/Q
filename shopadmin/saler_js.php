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

	
	['<i class="icon-female" style="font-size:15px;margin-right:3px;text-shadow:0 -1px 0 rgba(0,0,0,0.5), 0 1px 0 rgba(255,255,255,1);"></i>','&nbsp;經銷商管理&nbsp;&nbsp;',null,null,'經銷商管理',  //功能
		
		['','經銷商資料','saler_info.php',null,'經銷商資料' //商品管理
			
			
		],
	
	],
	    ['<i class="icon-bar-chart" style="font-size:15px;margin-right:3px;text-shadow:0 -1px 0 rgba(0,0,0,0.5), 0 1px 0 rgba(255,255,255,1);"></i>','&nbsp;業績管理&nbsp;&nbsp;',null,null,'業績管理',  //订单管理
			['','業績查詢','saler_search.php',null,'業績查詢' //订单管理
			
		],	
				
     ],	
];

</SCRIPT>
<TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0 style="background:linear-gradient(#616161, #5c5c5c, #424242) no-repeat;">
  <TBODY>
  <TR>
    <TD width="23%" height="35" align=middle>&nbsp;</TD>
    <TD width="57%" height=28>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="11%" valign="top">&nbsp;</TD>
          <TD vAlign=baseline align=middle width="86%">
            <TABLE class=menudottedline cellSpacing=0 cellPadding=0 width="100%"  align=center border=0>
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
     <TD align=middle width="8%">&nbsp;</TD>
     <TD width="12%" align=middle nowrap="nowrap" class="top_preview_text" style="padding-right:10px"><div>
	 <A onClick="javascript:if (confirm('<?php echo $JsMenu[Logout_Alert]?>')) return window.location='<?php echo $INFO['site_shopadmin']?>/login.php?Action=Logout';else return false;"  href="<?php echo $INFO['site_shopadmin']?>/login.php?Action=Logout"><i class="icon-off" style="font-size:14px;margin-right:7px"></i><?php echo $JsMenu[Logout]?></A></div>
	  <A id=linkact_htc></A></TD>
  </TR></TBODY></TABLE>
