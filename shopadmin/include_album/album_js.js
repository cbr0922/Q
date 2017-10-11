function on_mou(i){
	eval("pic"+i+".src='http://images.sohu.com/cs/sms/alumni3/images/album/bb0"+i+".gif'")
}

function out_mou(i){
	eval("pic"+i+".src='http://images.sohu.com/cs/sms/alumni3/images/album/b0"+i+".gif'")
}

function on_mou2(url){
	document.getElementById("pp1").src = url;
	var img = new Image();
	img.src = url;
	if (img.width>100)
		document.getElementById("pp1").style.width = 100;
	else if (img.height>100) 
		document.getElementById("pp1").style.height = 100;
	else {
		document.getElementById("pp1").style.width = img.width;
		document.getElementById("pp1").style.height = img.height;
	}
}

function showImgSize(imgsize,areasize) {
	var new_w = new_h = 0;
	img = imgsize.split("x");
	ori_w = parseInt(img[0]);
	ori_h = parseInt(img[1]);

	area = areasize.split("x");
	area_w = parseInt(area[0]);
	area_h = parseInt(area[1]);

	if(ori_w > ori_h) {
		new_w = ori_w>area_w?area_w:ori_w;
		new_h = ori_h*new_w/ori_w;
		zoomsize = new_w/ori_w *100;
	}
	else {
		new_h = ori_h>area_h?area_h:ori_h;
		new_w = ori_w*new_h/ori_h;
		zoomsize = new_h/ori_h *100;
	}
	return "width=" + new_w + " height=" + new_h + " zoomsize=" + zoomsize;	
}

function delpic(classuuid,picid,userid) {
	if(curUserID.length<2)
		alert("删除图片请先登录!");
	else if(curUserID!=userid && !isClassMaster)
		alert("您不是班级管理员或图片上传人,无法删除图片!");
	else if(confirm('确认删除此图片吗?')) {
		window.location="/album/classalbum_delpic.jsp?classuuid="+classuuid+"&picid="+picid+"&met=del";
	}
}

function upgrade() {
	if(!(isClassMember || isClassGuest))
		alert("您目前处于本班待批准成员或友人状态, 无法为相册升级!");
	else if(!isVipUser)
		alert("您不是星级会员!");
	else if(confirm('确认相册升级?')) 
		window.location="classalbum_upgrade.jsp?classuuid="+classuuid;
}
///////////////////////////////////////////////////////////
function ShowSendIMGMenu(doc)
{
	if(typeof(doc.DIVMenu)=="undefined")
	{
		doc.DIVMenu = null;
		doc.DIVMinWidth = DIVMinWidth;
		doc.DIVMinHeight = DIVMinHeight;
		doc.DIVCuteCheckImgSrc = DIVCuteCheckImgSrc;
	}
	for(var i = 0; i<doc.images.length; ++i)
	{
		var img = doc.images[i];
		if(img.id=="photoShow")
		{
		if (img.onmouseover != DIVMouseOver)
		{
			if(typeof(img.orig_onmouseover)=="undefined")
			{
				img.orig_onmouseover = img.onmouseover;
				img.orig_onmouseout = img.onmouseout;
			}
			img.onmouseover = DIVMouseOver;
			img.onmouseout = DIVMouseOut;
		}
		}
	}
}

function ShowSendIMGMenu2()
{
	var objs = doc.all.tags("INPUT");
	for(var j = 0; j < objs.length; ++j)
	{
		var img = objs[j];
		if (img.onmouseover != DIVMouseOver)
		{
			if(typeof(img.type)=="string" && img.type.toLowerCase()=="image"&&img.id=="photoShow")
			{
				if(typeof(img.orig_onmouseover)=="undefined")
				{
					img.orig_onmouseover = img.onmouseover;
					img.orig_onmouseout = img.onmouseout;
				}
				img.onmouseover = DIVMouseOver;
				img.onmouseout = DIVMouseOut;
			}
		}
	}
}

function DIVMouseOver()
{
	if(this.orig_onmouseover)
	{
		this.orig_onmouseover();
	}
	var doc = this.document;
	if(!doc.DIVMenu)
	{
		doc.DIVMenu = doc.createElement("A");
		doc.DIVMenu.id = "DIVSendIMG";
		doc.DIVMenu.style.color = "#ff6600";
		doc.DIVMenu.style.fontFamily = "宋体";
		doc.DIVMenu.style.fontSize  = "9pt";
		doc.DIVMenu.style.border = "1 solid #fed070";
		doc.DIVMenu.style.backgroundColor = "#e9ffd8";
		doc.DIVMenu.innerHTML = "发送此图到彩信手机";
		doc.DIVMenu.style.padding = "5px";
		//doc.DIVMenu.target = "_blank";
		//doc.DIVMenu.target = "_parent ";
		doc.DIVMenu.style.position = "absolute";
		doc.DIVMenu.style.visibility = "hidden";
		doc.DIVMenu.onmouseout = function()
		{
			this.style.visibility = "hidden";
		}
	}
	doc.body.insertAdjacentElement("BeforeEnd", doc.DIVMenu);
	if(this.width>=doc.DIVMinWidth&&this.height>=doc.DIVMinHeight)
	{
		var x = 0, y = 0;
		for(var obj = this; obj; obj = obj.offsetParent)
		{
			x += parseInt(obj.offsetLeft);
			y += parseInt(obj.offsetTop);
		}	
		doc.DIVMenu.href = "javascript:sendmms(\""+photoShow.src+"\");";
		//doc.DIVMenu.target = "_parent ";
		doc.DIVMenu.style.left = x; //Math.max(x, stgcl(doc));
		doc.DIVMenu.style.top = y; //Math.max(y, stgct(doc));
		doc.DIVMenu.style.visibility = "";
	}
}
function DIVMouseOut()
{
	var doc = this.document;
	if(doc.DIVMenu)
	{
		if(this.orig_onmouseout)
		{
			this.orig_onmouseout();
		}
		var e = this.document.parentWindow.event;
		if(e.toElement&&e.toElement.id == "DIVSendIMG")	//鼠标移动到DIVMenu触发,防止NewDIVMenu消失
		{
			return ;
		}
		doc.DIVMenu.style.visibility = "hidden";
	}
}
function DIVCalcSrc(img,DIVCuteCheckImgSrc)
{
	var ext = "";
	var href = "";
	for(var obj=img; obj&&DIVCuteCheckImgSrc; obj = obj.parentElement)
	{
		if(obj.tagName=="A")
		{
			href = obj.href;
			ext = getextension(href).toLowerCase();
			break;
		}
	}
	return ext==".jpg"||ext==".jpeg"||ext==".jpe"||ext==".gif"||ext==".png" ? href : img.src;
}

function getextension(s)
{
	var n=s.lastIndexOf('.');
	return n<0 ? "" : s.substring(n,s.length);
}

function stgcl(doc)
{
	if (doc == null)
		return 0;
	if (doc.body == null)
		return 0;
	//if (typeof(doc.documentElement) == 'undefined' || doc.documentElement == null)
	//return doc.body.scrollLeft;
	return doc.documentElement.scrollLeft;
}

function stgct(doc)
{
	if (doc == null)
		return 0;
	if (doc.body == null)
		return 0;
	//if (typeof(doc.documentElement) == 'undefined' || doc.documentElement == null)
	//return doc.body.scrollTop;
	return doc.documentElement.scrollTop;
}