<?php
include_once "Check_Admin.php";
include_once "../language/".$INFO['IS']."/Desktop_Pack.php";
include_once "../language/".$INFO['IS']."/JsMenu.php";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
		if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
	var i,p,v,obj,args=MM_showHideLayers.arguments;
	for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
	if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
	obj.visibility=v; }
}
//-->
</script>
<LINK href="css/css.css" type=text/css rel=stylesheet><ul class="desktop_tips">
            <li><A href="provider_order_list.php?action=search&shipstate=6"><i class="icon-warning-sign" style="font-size:24px;color:#C00;"></i><br>所有退貨商品 </a></li>
            <li><A href="provider_order_list.php?action=search&shipstate=4"><i class="icon-exchange" style="font-size:24px;color:#F90;"></i><br>所有換貨商品</A></li>
            <li><a href="provider_goods_list.php"><i class="icon-archive" style="font-size:24px;color:#393;"></i><br>所有商品</a></li>
            <li><A href="provider_order_list.php"><i class="icon-shopping-cart" style="font-size:24px;color:#039;"></i><br>
            訂單管理</a></li>
            <li><A href="provider_psw.php"><i class="icon-key" style="font-size:24px;color:#93C;"></i><br>
              修改密碼</a></li>
           </ul><table width="93%"  border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#ccc" class="p9black">
        <tr>
          <td height="19" align="center" bgcolor="#ECECEC">公告</td>
          </tr>
        <tr>
          <td bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="3" class="p9black">
            <?php
              $Sql      = "select * from `{$INFO[DBPrefix]}provider_news` where provider_nfb=1 order by provider_nidate desc ";
			  $Query    = $DB->query($Sql);
			  $Num      = $DB->num_rows($Query);
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

					?>
            <tr align="center">
              <td width="50%" align="left" nowrap="nowrap"><a href="javascript:DisplayContent(q<?php echo $i;?>,<?php echo $Num;?>)"><b><?php echo $Rs['provider_ntitle']?></b></a>[<?php echo date("Y/m/d",$Rs['provider_nidate'])?>] </td>
            </tr>
              <tr>
                      <td style='display:none;' nid='q'; id='q<?php echo $i;?>' bgcolor="#FFFFFF"><?php echo $Rs['provider_ncontent']?> </td>
            </tr>
            <?php
					$i++;
					}
					?>
            </table></td>
          </tr>
      </table>
 <script>
function DisplayContent(obj,num)
{
	nid=obj.nid;
	if (obj.style.display!="none"){
		obj.style.display='none';}
	else {
			for(i=0;i<num;i++)
			{
				n=nid + i+"";
				if(document.getElementById(n)== null)
					break;
				document.getElementById(n).style.display="none";
			}
		obj.style.display='block';
	}
}
</script>
