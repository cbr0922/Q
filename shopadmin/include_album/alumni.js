//////////////////////// effect func below ////////////////////////

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function UrlConv(url)
{
	//if(url.href.indexOf("backdoor.jsp")>0)
		//url="http://www.chinaren.com";
	url = url + "";
	var m_url = url;
	var outstring = "";
	var x1 = 0;
	for(x1 = 0 ; x1 < (m_url.length) ; x1++)
	{
		chr = m_url.substr(x1,1);
		if(chr=='&')
		{
			outstring += "%26";
			continue;
		}
		if(chr=='?')
		{
			outstring += "%3F";
			continue;
		}
		if(chr==':')
		{
			outstring += "%3A";
			continue;
		}
		outstring += chr;
	}
	return outstring;
}

function showerror(erricon,errmsg) {
        top.document.location = "http://alumni.chinaren.com/error.jsp?erricon=" + erricon + "&errmsg=" + errmsg + "&url=" + UrlConv(top.window.location);
}

function ChkLogin() {
	if(document.FM.username.value.length<3 || document.FM.username.value.length>32) {
		alert('请输入用户名');
		return false;
	}
	if(document.FM.password.value.length<2 || document.FM.password.value.length>32) {
		alert('请输入密码');
		return false;
	}
	if(document.FM.us[1].checked) {
		document.ts.cn.value=document.FM.username.value;
		document.ts.pw.value=document.FM.password.value;
		document.ts.submit();
		return false;
	}
	if (document.FM.us[2]) {
		if (document.FM.us[2].checked) {
			if(document.FM.username.value.length != 11 || document.FM.username.value<13000000000 || document.FM.username.value>13999999999) {
				alert("请输入正确的11位手机号码!");
				document.FM.username.focus();
				return false;           
			}       
			document.ts.cn.value=document.FM.username.value;
			document.ts.pw.value=document.FM.password.value;
			document.ts.action = "http://sms.sohu.com/sms_login_chinaren.php";
			document.ts.submit();
			return false;
		}
	}
	return true;
}

function ChkLoginUrs() {
	if(document.FM.uid.value.length<3 || document.FM.uid.value.length>32) {
		alert('请输入用户名');
		return false;
	}
	if(document.FM.pwd.value.length<2 || document.FM.pwd.value.length>32) {
		alert('请输入密码');
		return false;
	}
/*
	if(document.FM.us[1].checked) {
		document.ts.cn.value=document.FM.uid.value;
		document.ts.pw.value=document.FM.password.value;
		document.ts.submit();
		return false;
	}
	if (document.FM.us[2]) {
		if (document.FM.us[2].checked) {
			if(document.FM.uid.value.length != 11 || document.FM.uid.value<13000000000 || document.FM.uid.value>13999999999) {
				alert("请输入正确的11位手机号码!");
				document.FM.uid.focus();
				return false;           
			}       
			document.ts.cn.value=document.FM.uid.value;
			document.ts.pw.value=document.FM.password.value;
			document.ts.action = "http://sms.sohu.com/sms_login_chinaren.php";
			document.ts.submit();
			return false;
		}
	}
*/
	return true;
}

function trim(str) {
	regExp1 = /^ */;
	regExp2 = / *$/;
	return str.replace(regExp1,'').replace(regExp2,'');
}


//////////////////////// emotion func below ////////////////////////

var emotion_shortcut = ":) #_# 8*) :D :-> :P B_) B_I ^_* :$ :| :( :.( :_( >:( :V *_* :^ :? :! =:| :% :O :X |-) :Z :9 :T :-* *_/ :#| :69 //shuang //qiang //ku //zan //heart //break //F //W //mail //strong //weak //share //phone //mobile //kiss //V //sun //moon //star (!) //TV //clock //gift //cash //coffee //rice //watermelon //tomato //pill //pig //football //shit";
var e_arr = emotion_shortcut.split(" ");

var Emotion_Num = e_arr.length;

var EmotionArray = new Array(Emotion_Num);

for (i=0; i<Emotion_Num; i++) {
	var idx = e_arr[i];
	EmotionArray[idx] = i;
}

var abs_path    =       "<img src=http://images.sohu.com/product/alumni/htmledit/emotion3/";
var suffix      =       ".gif border=0>";

function getEmotion(idx) {
        document.write(abs_path + EmotionArray[idx] + suffix);
}

function doStr(src) {
	//src = src.replace(/script/i, "noscript");
	src = src.replace(/frame/ig, "noframe");
	var quote = /(:\)|\#_\#|8\*\)|:->|:D|:P|B_\)|B_I|\^_\*|:\$|:\||:\(|:\.\(|:_\(|>:\(|:V|\*_\*|:\^|:\?|:\!|\=:\||:%|:O|:X|\|-\)|:Z|:9|:T|:-\*|\*_\/|:#\||:69|\/\/shuang|\/\/qiang|\/\/ku|\/\/zan|\/\/heart|\/\/break|\/\/F|\/\/W|\/\/mail|\/\/strong|\/\/weak|\/\/share|\/\/phone|\/\/mobile|\/\/kiss|\/\/V|\/\/sun|\/\/moon|\/\/star|\(\!\)|\/\/TV|\/\/clock|\/\/gift|\/\/cash|\/\/coffee|\/\/rice|\/\/watermelon|\/\/tomato|\/\/pill|\/\/pig|\/\/football|\/\/shit)/g;
	var src = src.replace(quote, "<script" + ">getEmotion('$1')</" + "script>");
	document.write(src);
}

function doFlatTxt(src, classuuid) {
	src = src.replace(/script/ig, "noscript");
	if (src.indexOf("求学网")!=-1 || src.toLowerCase().indexOf("qiuxue")!=-1) {
		document.write("校友录提示: 该条留言是广告, 已被系统过滤!");
	} else {
		if (src.indexOf("手机留言:")==0) {
                        src = src.replace("手机留言:", "<a href=javascript:sms_sub_1(1,1,\"" + classuuid + "\") class=cla2>手机留言:</a>");
                }
		var quote = /(:\)|\#_\#|8\*\)|:->|:D|:P|B_\)|B_I|\^_\*|:\$|:\||:\(|:\.\(|:_\(|>:\(|:V|\*_\*|:\^|:\?|:\!|\=:\||:%|:O|:X|\|-\)|:Z|:9|:T|:-\*|\*_\/|:#\||:69|\/\/shuang|\/\/qiang|\/\/ku|\/\/zan|\/\/heart|\/\/break|\/\/F|\/\/W|\/\/mail|\/\/strong|\/\/weak|\/\/share|\/\/phone|\/\/mobile|\/\/kiss|\/\/V|\/\/sun|\/\/moon|\/\/star|\(\!\)|\/\/TV|\/\/clock|\/\/gift|\/\/cash|\/\/coffee|\/\/rice|\/\/watermelon|\/\/tomato|\/\/pill|\/\/pig|\/\/football|\/\/shit)/g;
		var src = src.replace(quote, "<script" + ">getEmotion('$1')</" + "script>");
		document.write(src);
	}
}
//////////////////////// Image to Symbol ////////////////////////

var imgToSymbol = new Array(Emotion_Num);

for (var i in EmotionArray) {
	var idx = "<IMG src=\"http://images.sohu.com/product/alumni/htmledit/emotion3/" + EmotionArray[i] + ".gif\" border=0>";
	imgToSymbol[idx] = i;
}

function imgToSym(src) {
        var quote = /<IMG src=\"http:\/\/images\.sohu\.com\/product\/alumni\/htmledit\/emotion3\/([0-9][0-9]?)\.gif\" border=0>/g;
        var src = src.replace(quote, function ($1) {return imgToSymbol[$1];});
        return src;
}

//////////////////////// show func below ////////////////////////

function showSohuHead() {
	document.write("<style type=text/css>");
	document.write(".ui_top{color:#000000;font-size:12px;line-height:18px;}");
	document.write(".ui_top a:link{color:#000000;text-decoration:none;}");
	document.write(".ui_top a:visited{color:#000000;text-decoration:none;}");
	document.write(".ui_top a:hover {color:#000000;text-decoration:underline;}");
	document.write("");
	document.write(".up1 {color:656565;line-height:18px;font-size:12px}");
	document.write(".up1 td{color:#656565;line-height:18px;font-size:12px}");
	document.write(".up1 A:link {color:#656565;text-decoration:none;line-height:18px;font-size:12px}");
	document.write(".up1 A:Visited {color:#656565;text-decoration:none;}");
	document.write(".up1 A:Hover {color:#656565;TEXT-DECORATION: underline;}");
	document.write("</style>");
	document.write("<img src=http://images.sohu.com/ccc.gif width=1 height=5><br>");
	document.write("<table width=760 border=0 cellpadding=0 cellspacing=0>");
	document.write("<tr>");
	document.write("<td width=148><img src=http://images.sohu.com/uiue/sohu_logo/sohu_logo.gif width=145 height=21 border=0></td>");
	document.write("<td align=right class=ui_top valign=bottom><a href=http://www.sohu.com/>首页</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://news.sohu.com/>新闻</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://sports.sohu.com/>体育</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://auto.sohu.com/>汽车</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://women.sohu.com/>女人</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://yule.sohu.com/>娱乐</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://business.sohu.com/>财经</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://it.sohu.com/>IT</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://house.sohu.com/>房产</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://sms.sohu.com/>短信</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://mms.sohu.com/>彩信</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://alumni.chinaren.com/ >校友录</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://login.mail.sohu.com/>邮件</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://so.sohu.com/>搜索</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://club.sohu.com/>社区</a><font style='font-size:8px'>&nbsp;</font>-<font style='font-size:8px'>&nbsp;</font><a href=http://goto.sohu.com/goto.php3?code=eachnet-sh2004-daohang>拍卖</a>");
	document.write("</td></tr>");
	document.write("<tr><td height=3 colspan=2><img src=http://images.sohu.com/ccc.gif width=1 height=1></td></tr>");
	document.write("</table>");
}

function showAlumniBanner(imghead, imgbody) {
	showXmas();
}

function general(imghead, imgbody) {
	document.write("<table width=760 height=3 border=0 cellpadding=0 cellspacing=0>");
	document.write("  <tr> ");
	document.write("    <td><img src=http://images.sohu.com/cs/sms/alumni3/images/c.gif width=1 height=1></td>");
	document.write("  </tr>");
	document.write("</table>");
	document.write("<table width=760 border=0 cellspacing=0 cellpadding=0>");
	document.write("  <tr>");
	document.write("    <td><img src=http://images.sohu.com/cs/sms/alumni3/images/" + imghead +" width=133 height=6></td>");
	document.write("  </tr>");
	document.write("</table>");
	document.write("<table width=760 border=0 cellspacing=0 cellpadding=0>");
	document.write("  <tr> ");
	if (imgbody=="cr_a2.gif")
		document.write("    <td width=141><a href=http://alumni.chinaren.com><img src=http://images.sohu.com/cs/sms/alumni3/images/" + imgbody + " width=141 height=32 border=0></a></td>");
	else 
		document.write("    <td width=141><img src=http://images.sohu.com/cs/sms/alumni3/images/" + imgbody + " width=141 height=32></td>");
	document.write("    <td width=72 background=http://images.sohu.com/cs/sms/alumni3/images/cr_a31.gif>&nbsp;</td>");
	document.write("    <td width=108><a href='http://roster.chinaren.com' onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image35','','http://images.sohu.com/cs/sms/alumni3/images/mc001.gif',1)\"><img src=http://images.sohu.com/cs/sms/alumni3/images/mc01.gif name=Image35 width=108 height=32 border=0></a></td>");
	document.write("    <td width=98><a href='http://alumni.chinaren.com/vip/vip_index.jsp' onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image32','','http://images.sohu.com/cs/sms/alumni3/images/cr_a41c.gif',1)\"><img src=http://images.sohu.com/cs/sms/alumni3/images/cr_a41.gif name=Image32 width=98 height=32 border=0></a></td>");
	document.write("    <td width=105><a href='http://alumni.chinaren.com/mobile/sms_alumni.jsp' onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image33','','http://images.sohu.com/cs/sms/alumni3/images/cr_a42c.gif',1)\"><img src=http://images.sohu.com/cs/sms/alumni3/images/cr_a42.gif name=Image33 width=105 height=32 border=0></a></td>");
	document.write("    <td width=108><a href='http://alumni.chinaren.com/fee/class_mates_adv.jsp' onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image34','','http://images.sohu.com/cs/sms/alumni3/images/cr_a43c.gif',1)\"><img src=http://images.sohu.com/cs/sms/alumni3/images/cr_a43.gif name=Image34 width=108 height=32 border=0></a></td>");
	document.write("    <td width=102 background=http://images.sohu.com/cs/sms/alumni3/images/cr_a31.gif>&nbsp;</td>");
	document.write("    <td width=134 valign=top background=http://images.sohu.com/cs/sms/alumni3/images/cr_a32.gif>");
	document.write("      <table width=120 height=26 border=0 cellpadding=0 cellspacing=0>");
	document.write("        <tr> ");
	document.write("          <td><a href=javascript:login() class=cnn><img src=http://images.sohu.com/cs/sms/alumni3/images/cr_a22.gif width=16 height=16 border=0></a></td>");
	document.write("          <td class=cnn  ><a href=javascript:login() class=cnn><font color=#FFFFFF>登录</font></a></td>");
	document.write("          <td width=12><img src=http://images.sohu.com/cs/sms/alumni3/images/cr_a23.gif width=2 height=16></td>");
	document.write("          <td><a href=javascript:logout() class=cnn><img src=http://images.sohu.com/cs/sms/alumni3/images/cr_a22.gif width=16 height=16 border=0></a></td>");
	document.write("          <td class=cnn  ><a href=javascript:logout() class=cnn><font color=#FFFFFF>注销</font></a></td>");
	document.write("        </tr>");
	document.write("      </table>");
	document.write("    </td>");
	document.write("  </tr>");
	document.write("</table>");
}

function showNewYear() {
	document.write("<table width=760 border=0 cellpadding=0 cellspacing=0>");
	document.write("<tr>");
	document.write("<td><a href=http://alumni.chinaren.com><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_1.jpg border=0></a></td>");
	document.write("<td valign=bottom><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_2.gif><a href=http://roster.chinaren.com><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_3.gif border=0></a><a href=http://alumni.chinaren.com/vip/vip_index.jsp><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_4.gif border=0></a><a href=http://alumni.chinaren.com/mobile/sms_alumni.jsp><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_5.gif border=0></a><a href=http://alumni.chinaren.com/fee/class_mates_adv.jsp><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_6.gif border=0></a><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_7.gif><a href=javascript:login()><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_8.gif border=0></a><a href=javascript:logout()><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_9.gif border=0></a><img src=http://images.sohu.com/cs/sms/alumni3/images/alu_newyear_10.gif></td>");
	document.write("</tr>");
	document.write("</table>");
}

function showXmas() {
	document.write("<table width=760 border='0' align='center' cellpadding='0' cellspacing='0'>");
	document.write("  <tr>");
	document.write("    <td><a href='http://alumni.chinaren.com/' target='_blank'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_01.gif' width='145' height='60' border='0'></a></td>");
	document.write("    <td valign='bottom'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_02.gif' width='37' height='45'></td>");
	document.write("    <td valign='bottom'><a href='http://game.chinaren.com/' target='_blank'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_03.gif' width='84' height='45' border='0'></a></td>");
	document.write("    <td valign='bottom'><a href='http://roster.chinaren.com/' target='_blank'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_04.gif' width='87' height='45' border='0'></a></td>");
	document.write("    <td valign='bottom'><a href='http://alumni.chinaren.com/vip/vip_index.jsp' target='_blank'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_05.gif' width='76' height='45' border='0'></a></td>");
	document.write("    <td valign='bottom'><a href='http://alumni.chinaren.com/mobile/sms_alumni.jsp' target='_blank'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_06.gif' width='89' height='45' border='0'></a></td>");
	document.write("    <td valign='bottom'><a href='http://alumni.chinaren.com/fee/class_mates_adv.jsp' target='_blank'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_07.gif' width='85' height='45' border='0'></a></td>");
	document.write("    <td valign='bottom'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_08.gif' width='66' height='45'></td>");
	document.write("    <td valign='bottom'><a href='javascript: login()'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_09.gif' width='38' height='45' border='0'></a></td>");
	document.write("    <td valign='bottom'><a href='javascript: logout()'><img src='http://images.sohu.com/cs/sms/alumni3/images/xmas_10.gif' width='53' height='45' border='0'></a></td>");
	document.write("  </tr>");
	document.write("</table>");
}

function spring() {
	document.write("<table width=760 border=0 cellpadding=0 cellspacing=0>");
	document.write("<tr>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=12 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=117 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=16 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=10 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=31 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=100 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=94 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=103 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=113 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=71 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=32 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=4 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=30 height=1></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=27 height=1></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td colspan=4><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_01.gif width=155 height=14></td>");
	document.write("<td colspan=10></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td rowspan=3><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_03.gif width=12 height=46></td>");
	document.write("<td rowspan=3><a href=http://alumni.chinaren.com/ target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_04.gif width=117 height=46 border=0></a></td>");
	document.write("<td rowspan=3><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_05.gif width=16 height=46></td>");
	document.write("<td colspan=2 rowspan=3><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_06.gif width=41 height=46></td>");
	document.write("<td rowspan=3><a href=http://roster.chinaren.com/ target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_07.gif width=100 height=46 border=0></a></td>");
	document.write("<td rowspan=3><a href=http://alumni.chinaren.com/vip/vip_index.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_08.gif width=94 height=46 border=0></a></td>");
	document.write("<td rowspan=3><a href=http://alumni.chinaren.com/mobile/sms_alumni.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_09.gif width=103 height=46 border=0></a></td>");
	document.write("<td rowspan=3><a href=http://alumni.chinaren.com/fee/class_mates_adv.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_10.gif width=113 height=46 border=0></a></td>");
	document.write("<td colspan=5><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_11.gif width=164 height=13></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td rowspan=2><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_12.gif width=71 height=33></td>");
	document.write("<td><a href=javascript:login()><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_13.gif width=32 height=14 border=0></a></td>");
	document.write("<td rowspan=2><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_14.gif width=4 height=33></td>");
	document.write("<td><a href=javascript:logout()><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_15.gif width=30 height=14 border=0></a></td>");
	document.write("<td rowspan=2><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_16.gif width=27 height=33></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_17.gif width=32 height=19></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/spring_18.gif width=30 height=19></td>");
	document.write("</tr>");
	document.write("</table>");
}

function summer() {
	document.write("<table width=760 border=0 cellpadding=0 cellspacing=0>");
	document.write("<tr>");
	document.write("<td width=118 height=1></td>");
	document.write("<td width=39 height=1></td>");
	document.write("<td width=32 height=1></td>");
	document.write("<td width=81 height=1></td>");
	document.write("<td width=86 height=1></td>");
	document.write("<td width=76 height=1></td>");
	document.write("<td width=95 height=1></td>");
	document.write("<td width=107 height=1></td>");
	document.write("<td width=32 height=1></td>");
	document.write("<td width=4 height=1></td>");
	document.write("<td width=31 height=1></td>");
	document.write("<td width=59 height=1></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td rowspan=6><a href=http://alumni.chinaren.com target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_01.gif width=118 height=56 border=0></a></td>");
	document.write("<td rowspan=2><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_02.gif width=39 height=10></td>");
	document.write("<td colspan=9 width=544 height=9></td>");
	document.write("<td rowspan=6><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_04.gif width=59 height=56></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td colspan=2><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=113 height=1></td>");
	document.write("<td rowspan=5><a href=http://roster.chinaren.com/ target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_06.gif width=86 height=47 border=0></a></td>");
	document.write("<td colspan=6><img src=http://images.sohu.com/cs/sms/alumni3/images/spacer.gif width=345 height=1></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td colspan=2 rowspan=4><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_08.gif width=71 height=46></td>");
	document.write("<td rowspan=4> <a href=http://game.chinaren.com/game/index.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_09.gif width=81 height=46 border=0></a></td>");
	document.write("<td rowspan=4> <a href=http://alumni.chinaren.com/vip/vip_index.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_10.gif width=76 height=46 border=0></a></td>");
	document.write("<td rowspan=4> <a href=http://alumni.chinaren.com/mobile/sms_alumni.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_11.gif width=95 height=46 border=0></a></td>");
	document.write("<td rowspan=4> <a href=http://alumni.chinaren.com/fee/class_mates_adv.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_12.gif width=107 height=46 border=0></a></td>");
	document.write("<td colspan=3><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_13.gif width=67 height=10></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td colspan=2><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_14.gif width=36 height=1></td>");
	document.write("<td rowspan=2> <a href='javascript:logout()'><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_15.gif width=31 height=16 border=0></a></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td> <a href='javascript:login()'><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_16.gif width=32 height=15 border=0></a></td>");
	document.write("<td rowspan=2><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_17.gif width=4 height=35></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_18.gif width=32 height=20></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/summer_19.gif width=31 height=20></td>");
	document.write("</tr>");
	document.write("</table>");
}

/*
function autumn() {
	document.write("<table width=760 border=0 cellpadding=0 cellspacing=0>");
	document.write("<tr>");
	document.write("<td rowspan=2> <a href=http://alumni.chinaren.com/ target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_1.jpg width=157 height=60 border=0></a></td>");
	document.write("<td colspan=15 width=603 height=14>");
	document.write("</td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_3.jpg width=51 height=46></td>");
	document.write("<td><a href=http://game.chinaren.com/game/index.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_4.jpg width=69 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_5.jpg width=24 height=46></td>");
	document.write("<td><a href=http://roster.chinaren.com/ target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_6.jpg width=60 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_7.jpg width=27 height=46></td>");
	document.write("<td><a href=http://alumni.chinaren.com/vip/vip_index.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_8.jpg width=44 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_9.jpg width=27 height=46></td>");
	document.write("<td><a href=http://alumni.chinaren.com/mobile/sms_alumni.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_10.jpg width=59 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_11.jpg width=23 height=46></td>");
	document.write("<td><a href=http://alumni.chinaren.com/fee/class_mates_adv.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_12.jpg width=59 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_13.jpg width=26 height=46></td>");
	document.write("<td> <a href='javascript:login()'><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_14.jpg width=27 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_15.jpg width=8 height=46></td>");
	document.write("<td><a href='javascript:logout()'><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_16.jpg width=30 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn_17.jpg width=69 height=46></td>");
	document.write("</tr>");
	document.write("</table>");
}
*/

function autumn() {
	document.write("<table width=760 border=0 cellpadding=0 cellspacing=0>");
	document.write("<tr>");
	document.write("<td rowspan=2> <a href=http://alumni.chinaren.com/ target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_1.jpg width=158 height=60 border=0></a></td>");
	document.write("<td colspan=11 width=602 height=14></td>");
	document.write("</tr>");
	document.write("<tr>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_3.jpg width=21 height=46></td>");
	document.write("<td><a href=http://game.chinaren.com/game/index.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_4.jpg width=89 height=46 border=0></a></td>");
	document.write("<td><a href=http://roster.chinaren.com/ target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_5.jpg width=84 height=46 border=0></a></td>");
	document.write("<td><a href=http://alumni.chinaren.com/vip/vip_index.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_6.jpg width=78 height=46 border=0></a></td>");
	document.write("<td><a href=http://alumni.chinaren.com/mobile/sms_alumni.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_7.jpg width=89 height=46 border=0></a></td>");
	document.write("<td><a href=http://alumni.chinaren.com/fee/class_mates_adv.jsp target=_blank><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_8.jpg width=86 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_9.jpg width=20 height=46></td>");
	document.write("<td><a href='javascript:login()'><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_10.jpg width=31 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_11.jpg width=4 height=46></td>");
	document.write("<td><a href='javascript:logout()'><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_12.jpg width=28 height=46 border=0></a></td>");
	document.write("<td><img src=http://images.sohu.com/cs/sms/alumni3/images/autumn2_13.jpg width=72 height=46></td>");
	document.write("</tr>");
	document.write("</table>");
}

function showCopyRight() {
	document.write("<table width=760 border=0 cellspacing=0 cellpadding=0>");
	document.write("  <tr>");
	document.write("    <td height=30><hr width=760 size=1 noshade></td>");
	document.write("  </tr>");
	document.write("  <tr>");
	document.write("    <td align=center class=cla1><a href=http://alumni.chinaren.com class=cla1>校友录首页</a> - <a href=http://profile.chinaren.com/zhs/register.jsp?group=alumni class=cla1>注册新用户</a> - <a href=mailto:alumni@contact.sohu.com class=cla1>联系校友录</a> - <a href=http://help.sohu.com/help_2.php?fatherid=3 class=cla1 target=_blank>帮助信息</a> - <a href=http://crm.chinaren.com class=cla1>客服论坛</a><br>");
	document.write("      Copyright &copy; 2005 Sohu.com Inc. All rights reserved. 搜狐公司 版权所有</td>");
	document.write("  </tr>");
	document.write("</table>");
	document.write("<a href=http://www.hd315.gov.cn/beian/view.asp?bianhao=0102000111600001 target=_blank><img src=http://images.sohu.com/biaoshi.gif border=0></a>");

	document.write("<script src=http://nielsen.js.sohu.com/nnselect.js></" + "script>");
	document.write("<noscript><img src='http://ping.nnselect.com/ping.gif?c=119' height='1' width='1'></noscript>");

	//document.write("<script src=http://images.sohu.com/product/alumni3/nielsen.js></" + "script>");
}

function showMap() {
	document.write("<table width=394 border=0 cellspacing=0 cellpadding=0>");
	document.write("  <tr> ");
	//document.write("    <td colspan=3><img src=http://images.sohu.com/cs/sms/alumni3/images/cr_b4.gif width=394 height=270 border=0 usemap=#Map></td>");
	document.write("    <td colspan=3> ");
	document.write("    <object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' width='400' height='320' id='map' align='middle'>");
	document.write("    <param name='movie' value='http://images.sohu.com/product/alumni3/map_sohu.swf'>");
	document.write("    <param name='quality' value='high'>");
	document.write("    <param name='bgcolor' value='#fffeef'>");
	document.write("    <embed src='http://images.sohu.com/product/alumni3/map_sohu.swf' quality='high' bgcolor='#fffeef' width='400' height='320' name='map' align='middle' allowScriptAccess='sameDomain' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'>");
	document.write("    </object>");
	document.write("    </td> ");
	document.write("  </tr>");
	document.write("  <tr bgcolor=FFFFEF> ");
	document.write("    <td width=20> ");
	document.write("    <td width=250 height=105 valign=top> <table width=168 border=0 cellpadding=4 cellspacing=1 bgcolor=C6C7BD>");
	document.write("        <tr> ");
	document.write("          <td bgcolor=#FFFFFF class=cr2><font color=#000000>其他地区</font><br> ");
	document.write("            <a href=/school/110/0/6/1.shtml class=cr2>日　本</a>　　<a href=/school/109/0/6/1.shtml class=cr2>韩　国</a>　　<a href=/school/108/0/6/1.shtml class=cr2>新加坡</a><br> ");
	document.write("            <a href=/school/107/0/6/1.shtml class=cr2>加拿大</a>　　<a href=/school/106/0/6/1.shtml class=cr2>美　国</a>　　<a href=/school/105/0/6/1.shtml class=cr2>亚　洲</a><br> ");
	document.write("            <a href=/school/104/0/6/1.shtml class=cr2>非　洲</a>　　<a href=/school/103/0/6/1.shtml class=cr2>澳　洲</a>　　<a href=/school/102/0/6/1.shtml class=cr2>南美洲</a><br> ");
	document.write("            <a href=/school/101/0/6/1.shtml class=cr2>欧　洲</a>　　<a href=/school/100/0/6/1.shtml class=cr2>东南亚</a></td>");
	document.write("        </tr>");
	document.write("      </table></td>");
	document.write("    <td width=124 valign=top><img src=http://images.sohu.com/cs/sms/alumni3/images/cr_b41.gif width=41 height=54></td>");
	document.write("  </tr>");
	document.write("</table>");
	
	document.write("<map name=Map>");
	document.write("  <area shape=rect coords='316,36,361,47' href=/school/16/0/6/1.shtml>");
	document.write("  <area shape=rect coords='319,66,349,79' href=/school/6/0/6/1.shtml>");
	document.write("  <area shape=rect coords='302,88,332,100' href=/school/5/0/6/1.shtml>");
	document.write("  <area shape=rect coords='268,94,296,108' href=/school/1/0/6/1.shtml>");
	document.write("  <area shape=rect coords='297,111,329,124' href=/school/2/0/6/1.shtml>");
	document.write("  <area shape=rect coords='265,111,295,123' href=/school/3/0/6/1.shtml>");
	document.write("  <area shape=rect coords='282,126,311,139' href=/school/13/0/6/1.shtml>");
	document.write("  <area shape=rect coords='243,126,273,139' href=/school/4/0/6/1.shtml>");
	document.write("  <area shape=rect coords='202,99,248,111' href=/school/15/0/6/1.shtml>");
	document.write("  <area shape=rect coords='159,101,190,115' href=/school/29/0/6/1.shtml>");
	document.write("  <area shape=rect coords='82,88,116,102' href=/school/32/0/6/1.shtml>");
	document.write("  <area shape=rect coords='148,131,179,143' href=/school/30/0/6/1.shtml>");
	document.write("  <area shape=rect coords='191,131,222,144' href=/school/31/0/6/1.shtml>");
	document.write("  <area shape=rect coords='92,162,121,175' href=/school/27/0/6/1.shtml>");
	document.write("  <area shape=rect coords='180,166,208,177' href=/school/22/0/6/1.shtml>");
	document.write("  <area shape=rect coords='183,217,210,231' href=/school/26/0/6/1.shtml>");
	document.write("  <area shape=rect coords='216,247,244,261' href=/school/21/0/6/1.shtml>");
	document.write("  <area shape=rect coords='258,239,286,254' href=/school/34/0/6/1.shtml>");
	document.write("  <area shape=rect coords='294,231,324,243' href=/school/33/0/6/1.shtml>");
	document.write("  <area shape=rect coords='326,219,360,231' href=/school/24/0/6/1.shtml>");
	document.write("  <area shape=rect coords='260,222,289,234' href=/school/19/0/6/1.shtml>");
	document.write("  <area shape=rect coords='226,222,253,235' href=/school/20/0/6/1.shtml>");
	document.write("  <area shape=rect coords='292,202,324,217' href=/school/11/0/6/1.shtml>");
	document.write("  <area shape=rect coords='246,200,277,213' href=/school/18/0/6/1.shtml>");
	document.write("  <area shape=rect coords='215,201,242,213' href=/school/25/0/6/1.shtml>");
	document.write("  <area shape=rect coords='207,183,239,199' href=/school/23/0/6/1.shtml>");
	document.write("  <area shape=rect coords='312,183,343,197' href=/school/9/0/6/1.shtml>");
	document.write("  <area shape=rect coords='276,185,306,197' href=/school/12/0/6/1.shtml>");
	document.write("  <area shape=rect coords='254,173,255,174' href=#>");
	document.write("  <area shape=rect coords='249,168,280,183' href=/school/17/0/6/1.shtml>");
	document.write("  <area shape=rect coords='283,163,315,176' href=/school/10/0/6/1.shtml>");
	document.write("  <area shape=rect coords='320,164,349,176' href=/school/7/0/6/1.shtml>");
	document.write("  <area shape=rect coords='294,145,324,158' href=/school/8/0/6/1.shtml>");
	document.write("  <area shape=rect coords='259,145,287,159' href=/school/14/0/6/1.shtml>");
	document.write("  <area shape=rect coords='240,152,241,154' href=#>");
	document.write("  <area shape=rect coords='225,147,253,158' href=/school/28/0/6/1.shtml>");
	document.write("</map>");
}

function showProvincesList() {
	document.write("<select name=prov class=input2 style='width: 80px'>");
	document.write("<option VALUE=0 >未知地区</option>");
	document.write("<option VALUE=1 selected>北京市</option>");
	document.write("<option VALUE=2>天津市</option>");
	document.write("<option VALUE=3>河北省</option>");
	document.write("<option VALUE=4>山西省</option>");
	document.write("<option VALUE=5>辽宁省</option>");
	document.write("<option VALUE=6>吉林省</option>");
	document.write("<option VALUE=7>上海市</option>");
	document.write("<option VALUE=8>江苏省</option>");
	document.write("<option VALUE=9>浙江省</option>");
	document.write("<option VALUE=10>安徽省</option>");
	document.write("<option VALUE=11>福建省</option>");
	document.write("<option VALUE=12>江西省</option>");
	document.write("<option VALUE=13>山东省</option>");
	document.write("<option VALUE=14>河南省</option>");
	document.write("<option VALUE=15>内蒙古自治区</option>");
	document.write("<option VALUE=16>黑龙江省</option>");
	document.write("<option VALUE=17>湖北省</option>");
	document.write("<option VALUE=18>湖南省</option>");
	document.write("<option VALUE=19>广东省</option>");
	document.write("<option VALUE=20>广西壮族自治区</option>");
	document.write("<option VALUE=21>海南省</option>");
	document.write("<option VALUE=22>四川省</option>");
	document.write("<option VALUE=23>重庆市</option>");
	document.write("<option VALUE=24>台湾省</option>");
	document.write("<option VALUE=25>贵州省</option>");
	document.write("<option VALUE=26>云南省</option>");
	document.write("<option VALUE=27>西藏自治区</option>");
	document.write("<option VALUE=28>陕西省</option>");
	document.write("<option VALUE=29>甘肃省</option>");
	document.write("<option VALUE=30>青海省</option>");
	document.write("<option VALUE=31>宁夏回族自治区</option>");
	document.write("<option VALUE=32>新疆维吾尔族自治区</option>");
	document.write("<option VALUE=33>香港特别行政区</option>");
	document.write("<option VALUE=34>澳门特别行政区</option>");

	document.write("<option VALUE=100>东南亚</option>");
	document.write("<option VALUE=101>欧　洲</option>");
	document.write("<option VALUE=102>南美洲</option>");
	document.write("<option VALUE=103>澳　洲</option>");
	document.write("<option VALUE=104>非　洲</option>");
	document.write("<option VALUE=105>亚　洲</option>");
	document.write("<option VALUE=106>美　国</option>");
	document.write("<option VALUE=107>加拿大</option>");
	document.write("<option VALUE=108>新加坡</option>");
	document.write("<option VALUE=109>韩　国</option>");
	document.write("<option VALUE=110>日　本</option>");

	document.write("</select>");
}

function showHello() {
	var welcomestring;
	var d = new Date();	
	h = d.getHours();
	if(h<6)
		welcomestring="凌晨好";
	else if (h<9)
		welcomestring="早上好";
	else if (h<12)
		welcomestring="上午好";
	else if (h<14)
		welcomestring="中午好";
	else if (h<17)
		welcomestring="下午好";
	else if (h<19)
		welcomestring="傍晚好";
	else if (h<22)
		welcomestring="晚上好";
	else
		welcomestring="夜里好";
	document.write(welcomestring);
}

function login() {
	var theHost = top.window.location.host;
	var theUrl = top.window.location.href;
	if (theHost.indexOf("alumni.sohu.com")>-1)
		theUrl = theUrl.replace(/alumni.sohu.com/gi, "alumni.chinaren.com");
	top.window.location = "http://profile.chinaren.com/zhs/login.jsp?group=alumni&url=" + UrlConv(theUrl);
	//top.document.location = "http://profile.chinaren.com/zhs/login.jsp?group=alumni&url=" + UrlConv(top.window.location);
}

function logout() {
	top.document.location = "http://profile.chinaren.com/zhs/backdoor.jsp?group=alumni&url=" + UrlConv(top.window.location);
}

function logintourl(url) {
	top.document.location = "http://profile.chinaren.com/zhs/login.jsp?group=alumni&url=" + url;
}

function lr1(vv) {
	window.open(vv,"","top=3000, height=3000, width=0,height=0,scrollbars=no,resizable=no,center:yes");
}

function lr2(vv) {
	window.open(vv,"","width=333,height=360,scrollbars=no,resizable=no,center:yes");
}

function alert_inv()
{
	alert("您不是本班成员，或者您没有登录！");
}

function isEmail(email)
{
        invalidChars = " /;,:{}[]|*%$#!()`<>?";
        if (email == "")
        {
                return false;
        }
        for (i=0; i< invalidChars.length; i++)
        {
                badChar = invalidChars.charAt(i)
                if (email.indexOf(badChar,0) > -1)  {
                        return false;
                }
        }
        atPos = email.indexOf("@",1)
        if (atPos == -1)  {   return false;  }
        if (email.indexOf("@", atPos+1) != -1) {   return false;  }
        periodPos = email.indexOf(".",atPos)
        if(periodPos == -1) {
                return false;  // and at least one "." after the "@"
        }
        if ( atPos +2 > periodPos)  {
                return false;  // and at least one character between "@" and "."
        }
        if ( periodPos +3 > email.length)  {   return false;  }
        return true;
}

function getsmspwd(obj) {
	obj.value = trim(obj.value);
	if(obj.value.length != 11)
	{
		alert("请输入手机号码，或手机号码位数不对！");
		obj.focus();
		return;
	}

	window.open("http://register.mail.sohu.com/reg/SendPass.jsp?mobile=" + obj.value,"","width=200,height=200");
}

function GetCookie(sName) {
	var aCookie = document.cookie.split("; ");
	for (var i=0; i < aCookie.length; i++) {
		var aCrumb = aCookie[i].split("=");
		if (sName == aCrumb[0]) 
			return unescape(aCrumb[1]);
	}
	return "";
}

function loginTip(tip) {
	var theHost = top.window.location.host;
	var theUrl = top.window.location.href;
	if (theHost.indexOf("alumni.sohu.com")>-1)
		theUrl = theUrl.replace(/alumni.sohu.com/gi, "alumni.chinaren.com");
	top.document.location = "http://profile.chinaren.com/zhs/loginTip.jsp?group=alumni&url=" + UrlConv(theUrl) + "&tip=" + encodeURI(tip);
}

