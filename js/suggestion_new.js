
var coflag=0;
var nflag=0;
var l1=null;
var commonkey=document.searchbanner.skey;
var keywordvalue=document.searchbanner.skey.value;
var a=null;
var oResult=null;
var ka=true;
var X=true;
var ca=null;
var Ea=false;
var ma=null;
var mousein=0;
var hintwords;
var hintlength =0;
var imgID = document.images["suggimg2"];
//var imgID = document.getElementById("suggimg2");
//var requestDomain="http://127.0.0.1/";
var requestDomain="http://"+window.location.host;
if(keywordvalue=="")
{
	keywordvalue=" "
}
document.searchbanner.skey.autocomplete="off";
document.searchbanner.skey.onfocus=lc;
document.searchbanner.skey.onblur=Wb;
window.onresize=Mb; 

function onoff() {
//alert(document.images["suggimg2"].name);
	if((document.getElementById("sugmaindivname") != "undefined")&&(document.searchbanner.skey.value != "")) {
//alert(document.images["suggimg2"].name);
		if(document.images["suggimg2"].src == requestDomain+"images/suggest_down.gif") {
			document.images["suggimg2"].src = requestDomain+"images/suggest_up.gif";
			document.getElementById("sugmaindivname").style.visibility="visible";
		}
		else if(document.images["suggimg2"].src == requestDomain+"images/suggest_up.gif") {
			document.images["suggimg2"].src = requestDomain+"images/suggest_down.gif";
			document.getElementById("sugmaindivname").style.visibility="hidden";
		}
	}else if(!document.getElementById("sugmaindivname")){
	}
}


function kc()
{
	a=document.searchbanner.skey;
	a.autocomplete="off";
	var oResult	= document.createElement('div');
	oResult.id= 'sugmaindivname';//suggestion main div name
	rightandleft=1;
	topandbottom=1;
	oResult.style.zIndex="2000";
	oResult.style.paddingRight="0";
	oResult.style.paddingLeft="0";
	oResult.style.paddingTop="0";
	oResult.style.paddingBottom="0";
	oResult.style.visibility="hidden";
	uda(oResult);
	oResult.style.position="absolute";
	oResult.style.backgroundColor="white";
	document.body.appendChild(oResult);
}

function Mb()
{
	if(GetObjValue('sugmaindivname'))
	{
		uda(document.getElementById("sugmaindivname"));
		}
}

function Wb()
{
	if(GetObjValue('sugmaindivname'))
	{
		document.getElementById("sugmaindivname").style.visibility="hidden";
		}
}

function lc()
{
	if(Ea==false)
	{
		kc();
		Ea=true;
		}
}

function Xb(h)
{
	if(window.event)h=window.event;
	if(h)
		onlyNum(h);
		if(h.keyCode==38 || h.keyCode==40)
		{
			h.cancelBubble=true;
			h.returnValue=false;
			return false
		}
}

var x02="%7B%D9%95aYQd";
var ascInit="1";
//eval(c01(x02));
te01(ascInit);
//te01();

//function te01()
function te01(ascInit)
{
	testnetb = new Date();
	begintime=testnetb.getTime();
	//document.f.tag.value="n";
	var keywordrand=Math.floor((Math.random())*10000);
	daend = new Date();
	endtime=daend.getTime();
	xiewenxiu=endtime-begintime;
	if(xiewenxiu<500)
	{
		//eval(c01(x03));
//		setTimeout("everytenms()",10);
setTimeout("everytenms(ascInit)",10);
		if (document.attachEvent) {
			document.onkeydown=Xb;
  		}

	  	if(document.addEventListener){
	  		document.addEventListener('keydown',onlyNum,false);
		}

	}
	else {}
}
//function everytenms()
//ascInit = "0";
function everytenms(ascInit)
{
//alert(document.images[imgID.name].src);
	var qnowvalue=document.searchbanner.skey.value;

	if(qnowvalue=="")
	{
		qnowvalue==" "
	}
	if(keywordvalue==qnowvalue || anum1=="1" || qnowvalue=="请输入查询词")
	{}
	else if(qnowvalue=="" || anum=="1")
	{
		if(GetObjValue("sugmaindivname"))
		{
			document.getElementById("sugmaindivname").style.visibility="hidden";
		}
                if(document.images["suggimg2"].src == requestDomain+"images/suggest_up.gif") {
                        document.images["suggimg2"].src = requestDomain+"images/suggest_down.gif";
                }
		keywordvalue=qnowvalue;
		}
		else 
			{
				test = "";
				newresult=getContent(qnowvalue,ascInit,test);
        if(qnowvalue!="") {
                if(document.images["suggimg2"].src == requestDomain+"images/suggest_down.gif") {
                        document.images["suggimg2"].src = requestDomain+"images/suggest_up.gif";
                }
        }		
				document.searchbanner.sid.value="";
				keywordvalue=qnowvalue; 
				keynum=0;
			}
	var agt = navigator.userAgent.toLowerCase();
	var is_ie5 = (agt.indexOf("msie 5") != -1);
	if(is_ie5){}
	else 
		{
			setTimeout("everytenms(ascInit)",10);
		}
		return true;
}

function keyfun()
{
	document.getElementById("suggestspan1").style.backgroundColor='#3366cc';
}

function getContent(keyword1,ascInit,test)
{
	if(keyword1!="")
	{
		if(l1&&l1.readyState!=0)
		{
			l1.abort()
			}
		var xmlhttp=null;
		try
		{
			xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{try
			{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(sc){xmlhttp=null}
		}
		if(!xmlhttp&&typeof XMLHttpRequest!="undefined"){
			xmlhttp=new XMLHttpRequest();
			xmlhttp.overrideMimeType('text/xml');
		}

		l1=xmlhttp;
		if (window.RegExp && window.encodeURIComponent) 
		{
			var newStrComment = encodeURIComponent(keyword1);
		} else 
			{
				var newStrComment =keyword1;
			}
		keyword = escape(keyword1);

		//url="http://"+window.location.host+"/suggest/suggest.jsp?key="+keyword+"&asc="+ascInit;
		//url="http://127.0.0.1/ajax.php?key="+keyword;
		//url="http://"+window.location.host+"/ajax.php?key="+keyword+"&asc="+ascInit;
        url="http://"+window.location.host+"/ajax.php?key="+keyword+"&asc="+ascInit;
 
		alert(url);
		l1.open("GET", url, true);

		l1.onreadystatechange=function()
		{	
		   if(l1.readyState == 4 )
			{
			   ee=l1.responseText;
							
				
				if(test!="test")
				{
					var everydata=ee.split("\n");
					var everydatal=everydata.length;
					var data;
					//alert(everydatal);
					if(everydatal<=3)
					{
						data="";
					}else 
					{
						nflag=1;

data = "<div id=sugmaindivname2 class=\"inputmenu\" style=\"solid #b2b2b2;\" onmousedown=\"sugmaindivname2.style.visibility='visible'\" style=\"javascript:this.css.visibility='visible';\"> <div class=\"menubar1\"><span><a href=\"javascript:void(null);\"  onclick=\"sugmaindivname2.style.visibility='hidden'\"><font color=#363837>关闭本功能</font></a> </span><font color=#363837>关键字自动匹配功能</font></div><ul id=\"ula\">";

						if(everydatal-1>11) {
							noweverydatal=11;
						}
						else {
							noweverydatal=everydatal-1;
						}
						hintwords=new Array();
						hintlength=0;
						for(i=0;i<everydatal;i++)
//						for(i=0;i<noweverydatal;i++)
						{
							var neweveryword=everydata[i].split("\t");
							//alert(neweveryword[1]);

							if(neweveryword[1] !="")
							{
								result="首歌曲";
								if(neweveryword[1]!=null)
								{
									hintwords[hintlength] = neweveryword[0];
									hintlength = hintlength+1;
								}
							}
							else{result="";}
							if(i<everydatal-1 && neweveryword[1]!=null)
							{
								//j=i-1;
								j = i-1;
								newword=neweveryword[0].replace("'","\\'");
								//biaohong = document.searchbanner.skey.value;
								biaohong = keyword1.toLowerCase();
								if(neweveryword[0].substr(0,biaohong.length)==biaohong) {
								data+="<li id=\"keyword"+j+"\" style=\"cursor:hand;\"onmouseover=this.style.backgroundColor=\"#C1ECFF\" onmouseover=\"javascript:mousein = 1;\" onmouseout=this.style.backgroundColor=\"white\" onmouseout=\"javascript:mousein = 0;	keynum="+j+";realkeynum="+j+";\"onmousedown=\"cc('"+newword+"')\">"+"<span id=\"td"+j+"_2\">"+neweveryword[1]+" "+result+"</span><td id=\"td"+j+"_1\"><strong>&nbsp;"+biaohong+"</strong>"+neweveryword[0].substring(biaohong.length)+"</td></li>";
} 
else {
feibiaohonglen = neweveryword[0].length - biaohong.length;

								data+="<li id=\"keyword"+j+"\" style=\"cursor:hand;\"onmouseover=this.style.backgroundColor=\"#C1ECFF\" onmouseover=\"javascript:mousein = 1;\" onmouseout=this.style.backgroundColor=\"white\" onmouseout=\"javascript:mousein = 0;    keynum="+j+";realkeynum="+j+";\" onmousedown=\"cc('"+newword+"')\">"+"<span id=\"td"+j+"_2\">"+neweveryword[1]+" "+result+"</span>&nbsp;<td id=\"td"+j+"_1\">"+neweveryword[0].substring(0,feibiaohonglen)+"<strong>"+biaohong+"</strong></td>"+"</li>";
}

								/*data+="<div  id=\"keyword"+j+"\">"+
								"<table class=\"f12\" width=98% border=0 cellpadding=0 cellspacing=0 height=\"20\" onMouseOver=\"mon(this,"+j+","+noweverydatal+")\" onMouseOut=\"mout(this,"+j+")\"  onmousedown=\"cc('"+newword+"')\" >"+
								"<tr><td align=left class=\"f12\"  id=\"td"+j+"_1\" style=\"padding-left:2px\">"
								+neweveryword[0]+
								"</td><td  class=\"rst f12\" align=right id=\"td"+j+"_2\">"
								+neweveryword[1]+
								" "+result+"</td></tr></table></div>";*/
								data=data.replace("undefined","");}
						}
var feiasc;
//var haha="apple";
if(ascInit=="1") feiasc="0";
else feiasc = "1";
data+="<li class=\"menubar2\"><a href=\"#\"  onmouseover=this.style.backgroundColor=\"#f4f4f4\" onmouseout=this.style.backgroundColor=\"white\"  onmousedown=\"javascript:getContent('"+keyword1+"','"+feiasc+"',test)\">倒序排列</a></li></ul></div>";
//data+="<li class=\"menubar2\"><a href=\"#\" onmousedown=\"alert('test!')\">倒序排列</a></li></ul></div>";
						}
						if(GetObjValue("sugmaindivname"))
						{
							if(data=="")
							{
								document.getElementById("sugmaindivname").style.visibility="hidden";
							}else 
							{
								document.getElementById("sugmaindivname").style.visibility="visible";
							}
							document.getElementById("sugmaindivname").innerHTML=data;
						}
					}
				}
				else {ee="";}
			};
			l1.send(null);
			return keyword;
		}
		else {return keyword;}
}

function cc(num)
{
	document.searchbanner.skey.value=num;
	if(GetObjValue("sugmaindivname"))
	{
		document.getElementById("sugmaindivname").style.visibility="hidden";
	}
	anum="1";
	document.searchbanner.skey.focus();
	document.searchbanner.sid.value="suggest";
	document.searchbanner.submit();
}

function c01(str)
{
	str=unescape(str);
	var c=String.fromCharCode(str.charCodeAt(0)-str.length);
	for(var i=1;i<str.length;i++)
	{
		c+=String.fromCharCode(str.charCodeAt(i)-c.charCodeAt(i-1));
	}
	return c;
}

function cckeydown(num)
{
	if(GetObjValue("sugmaindivname"))
	{
		document.getElementById("sugmaindivname").style.visibility="hidden";
	}
	anum="1";
	document.searchbanner.skey.focus();
}

function uda(oResult)
{
	if(oResult)
	{
		a=document.searchbanner.skey;
		oResult.style.left=zb(a)+"px";
		oResult.style.top=Yb(a)+a.offsetHeight+"px";
		oResult.style.width=Ta(a)+"px"
	}
}

function zb(s)
{
	return kb(s,"offsetLeft")+3
}

function Yb(s)
{
	return kb(s,"offsetTop")
}

function kb(s,na)
{
	var wb=0;
	while(s)
	{
		wb+=s[na];
		s=s.offsetParent
	}
	return wb
}

function Ta(a)
{
	if(navigator&&navigator.userAgent.toLowerCase().indexOf("msie")==-1)
	{
		return a.offsetWidth
	}
	else
	{
		return a.offsetWidth
		}
}

var keynum=0;
var anum="0";
var anum1="0";
var realkeynum;
function onlyNum(event)
{
	pointer = -1;
	if(mousein==1)
		return;
	if(event.keyCode==40) // key =
	{
		pointer++;
		if(keynum!=-1)
		{
			t="keyword"+keynum;
			numt="td"+keynum+"_1";
			numt2="td"+keynum+"_2";
			t1="keyword"+keynum;
		}
		else 
		{
			minkeynum=keynum+1;
			numt="td"+keynum+"_1";
			numt2="td"+keynum+"_2";
			t="keyword"+keynum;
			t1="keyword"+minkeynum;
		}
		if(GetObjValue(t1))
		{
			GetObjValue(t).style.backgroundColor='C1ECFF';
			//GetObjValue(t).childNodes[0].style.backgroundColor='f4f4f4';
			document.searchbanner.sid.value="suggest";
			GetObjValue(numt).style.color='#FFFFFF';
			//GetObjValue(numt2).style.color='#FFFFFF';
			document.searchbanner.skey.value=hintwords[keynum];
			anum1="1";
			if(keynum>0)
			{
				var lastkeynum=keynum-1;
				var lastt="keyword"+lastkeynum;
				var lastnumt="td"+lastkeynum+"_1";
				var lastnumt2="td"+lastkeynum+"_2";
				GetObjValue(lastt).style.backgroundColor='white';
				//GetObjValue(lastt).childNodes[0].style.backgroundColor='white';
				GetObjValue(lastnumt).style.color='';
				GetObjValue(lastnumt2).style.color='';
			}
			if(pointer >= 5) {
				//setInternal(GetObjValue("ula").scrollBy(0,10));
				document.getElementById("ula").scrollBy(0,10);
				//window.scrollBy(0,10);
			}
			realkeynum=keynum;keynum++;	
			}
			else 
			{
				if(realkeynum==""){realkeynum=0;}
			}
	}
	if(event.keyCode==38)   //key= &
	{
		pointer--;
		if(realkeynum!=0)
		{
			realkeynum=realkeynum-1;
			var upt="keyword"+realkeynum;
			var numupt="td"+realkeynum+"_1";
			var numupt2="td"+realkeynum+"_2";
			if(GetObjValue(upt))
			{
				if(realkeynum<9)
				{
					var nextkeynum=realkeynum+1;
					var nextt="keyword"+nextkeynum;
					var numnextt="td"+nextkeynum+"_1";
					var numnextt2="td"+nextkeynum+"_2";
					GetObjValue(nextt).style.backgroundColor='white';
					//GetObjValue(nextt).childNodes[0].style.backgroundColor='white';
					GetObjValue(numnextt).style.color='';
					GetObjValue(numnextt2).style.color='';
					if(GetObjValue(numnextt)){}
				}
				GetObjValue(upt).style.backgroundColor='C1ECFF';
				//GetObjValue(upt).childNodes[0].style.backgroundColor='f4f4f4';
				document.searchbanner.sid.value="suggest";
				GetObjValue(numupt).style.color='#ffffff';
				//GetObjValue(numupt2).style.color='#ffffff';
				document.searchbanner.skey.value=hintwords[realkeynum];
				anum1="1";keynum--;
			 }
			}
		}
		if(event.keyCode==13)    //key=
		{
			if(GetObjValue("sugmaindivname"))
			{
				var sugmaindivid=document.getElementById("sugmaindivname").style.visibility;
				if (document.getElementById("sugmaindivname").style.visibility=="visible" && coflag==1)
				{
					//document.f.tag.value="k";
				} 
				else if(nflag==0 && document.getElementById("sugmaindivname").style.visibility=="hidden")
				{
					//document.f.tag.value="u";
				}else 
					{
						//document.f.tag.value="n";
					}
					document.getElementById("sugmaindivname").style.visibility="hidden";
			 }else 
			 	{
			 		var sugmaindivid="hidden";
			 	}
			 if(sugmaindivid=="hidden" || realkeynum==null )
			 {}
			 else 
			 	{
			 		var upt="keyword"+realkeynum;
			 		cckeydown(hintwords[realkeynum]);
			 	}
		}
		if(event.keyCode!=13 && event.keyCode!=38 && event.keyCode!=40)
		{anum="0";anum1="0";}
}


function GetObjValue(objName)
{
	if(document.getElementById)
	{
		return eval('document.getElementById("' + objName + '")');
	}else
	{
		return eval('document.all.' + objName);
		}
}

function mon(tbl,tdline,noweverydatal)
{
	for(i=1;i<noweverydatal-1;i++)
	{
		j=i-1;
		var somet="keyword"+j;
		GetObjValue(somet).childNodes[0].style.backgroundColor='white';
		document.getElementById("td"+j+"_1").style.color = '';
		document.getElementById("td"+j+"_2").style.color = '';
	}
	var everyt="keyword"+tdline;
	if(GetObjValue(everyt))
	{
		GetObjValue(everyt).childNodes[0].style.backgroundColor="#4780DE";
	}
	else 
	{
		tbl.bgColor = "#73b945";
	}
	document.getElementById("td"+tdline+"_1").style.color = '#FFFFFF';
	document.getElementById("td"+tdline+"_2").style.color = '#FFFFFF';
	mousein = 1;
}

function mout(tbl,tdline)
{
	var everyt="keyword"+tdline;
	if(GetObjValue(everyt))
	{
		GetObjValue(everyt).childNodes[0].style.backgroundColor='white';
	}
	else 
	{
		tbl.bgColor = "#f5f5f5";
	}
	document.getElementById("td"+tdline+"_1").style.color = '';
	document.getElementById("td"+tdline+"_2").style.color = '';
	mousein = 0;
	keynum=tdline;
	realkeynum=tdline;
}
