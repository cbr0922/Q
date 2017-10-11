<?php
@session_start();
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");
include_once "../language/".$INFO['IS']."/JsMenu.php";
$ArrayPrivilege_s = explode("%",$_SESSION['privilege']);
//print_r($ArrayPrivilege);
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
<?php
$sql_r = "select * from `{$INFO[DBPrefix]}menu_right` where level=0 and ifshow=1 and ifhave=1";
$Query_r = $DB->query($sql_r);
while($Result= $DB->fetch_array($Query_r)){
	$sql_1 = "select * from `{$INFO[DBPrefix]}menu_right` where level='" . $Result['mrid'] . "' and ifshow=1 and ifhave=1";
	$Query_1 = $DB->query($sql_1);
	$Num_1   = $DB->num_rows($Query_1);
	$count1 = 0;
	while($Result_1= $DB->fetch_array($Query_1)){
		if ((in_array($Result_1['mrid'],$ArrayPrivilege_s) || $_SESSION['LOGINADMIN_TYPE'] == 0) && ($Result_1['admintype']==0 || ($Result_1['admintype']==1 && $INFO['mobile_state']==1))){
			//echo $Result_1['mrid'];
			$count1++;
		}
	}
	if ($count1 > 0){
		
	
?>
	['<i class="<?php echo $Result['pic'];?>" style="font-size:15px;margin-right:3px;text-shadow:0 -1px 0 rgba(0,0,0,0.5), 0 1px 0 rgba(255,255,255,1);"></i>','&nbsp;<?php echo $Result['title'];?>',null,null,'<?php echo $Result['title'];?>',
<?php
	$Query_1 = $DB->query($sql_1);
	while($Result_1= $DB->fetch_array($Query_1)){
		$sql_2 = "select * from `{$INFO[DBPrefix]}menu_right` where level='" . $Result_1['mrid'] . "' and ifshow=1 and ifhave=1";
		$Query_2 = $DB->query($sql_2);
		$Num_2   = $DB->num_rows($Query_2);
		$count2 = 0;
		while($Result_2= $DB->fetch_array($Query_2)){
			if (in_array($Result_2['mrid'],$ArrayPrivilege_s) || $_SESSION['LOGINADMIN_TYPE'] == 0){
				$count2++;
			}
		}
		if ($count2 > 0 || $Num_2==0){
			if ($Result_1['pageurl']=="null")
				$pathurl = "null";
			elseif($Result_1['admintype']==0)
				$pathurl = "'" . $INFO['site_shopadmin'] . "/" . $Result_1['pageurl'] . "'";
			elseif($Result_1['admintype']==1)
				$pathurl = "'" . $INFO['mobile_admin'] . "/" . $Result_1['pageurl'] . "'";
			if ((in_array($Result_1['mrid'],$ArrayPrivilege_s) || $_SESSION['LOGINADMIN_TYPE'] == 0) && ($Result_1['admintype']==0 || ($Result_1['admintype']==1 && $INFO['mobile_state']==1)) ){
		?>
			['<i class="<?php echo $Result1['pic'];?> top_menu_text" style="font-size:15px;margin-right:3px"></i>', '<?php echo $Result_1['title'];?>&nbsp;&nbsp;&nbsp;', <?php echo $pathurl;?>, null, '<?php echo $Result_1['title'];?>'
																																													
			<?php
			
			if ($Num_2>0){
				echo ",";
			}
			$Query_2 = $DB->query($sql_2);
			while($Result_2= $DB->fetch_array($Query_2)){
				if ((in_array($Result_2['mrid'],$ArrayPrivilege_s) || $_SESSION['LOGINADMIN_TYPE'] == 0) && ($Result_2['admintype']==0 || ($Result_2['admintype']==1 && $INFO['mobile_state']==1))){
					if($Result_2['admintype']==0)
						$pathurl2 = "'" . $INFO['site_shopadmin'] . "/" . $Result_2['pageurl'] . "'";
					elseif($Result_2['admintype']==1)
						$pathurl2 = "'" . $INFO['mobile_admin'] . "/" . $Result_2['pageurl'] . "'";
			?>
				['<img src="<?php echo $INFO['site_shopadmin'];?>/images/<?php echo $INFO[IS]?>/<?php echo $Result_2['pic'];?>" style="margin-left:4px" />', '<?php echo $Result_2['title'];?>', <?php echo $pathurl2;?>, null, '<?php echo $Result_2['title'];?>'],
			<?php
				}
			}
			
			?>																																						   		],
		<?php
			}
		}
	}
	?>
	],
	<?php
	}
	
}
?>
];

</SCRIPT>

<TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0 style="background:linear-gradient(#616161, #5c5c5c, #424242) no-repeat;">
  <TBODY>
  <TR>
    <TD width="9%" height="43" align="left">    </TD>
    <TD width="75%" height=43>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=baseline align=middle width="86%">
            <TABLE class=menudottedline cellSpacing=0 cellPadding=0 width="100%"  align=center border=0>
              <TBODY>
              <TR>
                <TD class=menubackgr align=middle></TD>
			    <TD class=menubackgr align=middle>
				  <DIV id=myMenuID></DIV>

                  <SCRIPT language=JavaScript type=text/javascript>
			       	cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
				</SCRIPT>				</TD>
              </TR>
			 </TBODY>
			</TABLE>		   </TD>
          </TR>
		</TBODY>
	   </TABLE>
	 </TD>
     <TD width="8%" align=middle nowrap="nowrap" class="top_preview_text"><div>
	 <A  href="<?php echo $INFO['site_url']?>/" target=_blank><i class="icon-home" style="font-size:14px;margin-right:7px"></i><?php echo $JsMenu[View_Index]?></A></div></TD>
     <TD width="8%" align=middle nowrap="nowrap" class="top_preview_text" style="padding-right:10px"><div>
	 <A onClick="javascript:if (confirm('<?php echo $JsMenu[Logout_Alert]?>')) return window.location='<?php echo $INFO['site_shopadmin']?>/login.php?Action=Logout';else return false;"  href="<?php echo $INFO['site_shopadmin']?>/login.php?Action=Logout">
	  <i class="icon-off" style="font-size:14px;margin-right:7px"></i><?php echo $JsMenu[Logout]?></A></div>
	  <A id=linkact_htc></A>	 </TD>
   </TR>
   </TBODY>
  </TABLE>
