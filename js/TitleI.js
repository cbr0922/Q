var isIE = navigator.userAgent.toLowerCase().indexOf('ie');



//getElementsByTagNameS
function getElementsByTagNames(list,obj) {
	if (!obj) var obj = document;
	var tagNames = list.split(',');
	var resultArray = new Array();
	for (var i=0;i<tagNames.length;i++) {
		var tags = obj.getElementsByTagName(tagNames[i]);
		for (var j=0;j<tags.length;j++) {
			resultArray.push(tags[j]);
		}
	}
	var testNode = resultArray[0];
	if (!testNode) return [];
	if (testNode.sourceIndex) {
		resultArray.sort(function (a,b) {
				return a.sourceIndex - b.sourceIndex;
		});
	}
	else if (testNode.compareDocumentPosition) {
		resultArray.sort(function (a,b) {
				return 3 - (a.compareDocumentPosition(b) & 6);
		});
	}
	return resultArray;
}

function findPosX(obj)
{
	var curleft = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	}
	else if (obj.x)
		curleft += obj.x;
	return curleft;
}
function findPosY(obj)
{
	var curtop = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if (obj.y)
		curtop += obj.y;
	return curtop;
}

function addMouseEvent() {
	//为输入框添加焦点效果
	var inputs=getElementsByTagNames('input,textarea');
	for (i=0;i<inputs.length;i++) {
		var inputtype = inputs[i].type.toLowerCase();
		
		if(inputtype == 'checkbox' || inputtype == 'radio') continue;
		inputs[i].onfocus=function() {
			this.className='focus';
			var temptips=document.getElementById(this.id+'tips');
			if (temptips) {
				temptips.style.display='block';
				//temptips.style.width=this.offsetWidth-10+'px';
				temptips.style.top=findPosY(this)-temptips.offsetHeight-3+'px';
				temptips.style.left=findPosX(this)+3+'px';
				//alert(findPosY(this));
			}
		}
		inputs[i].onblur=function() {
			this.className='';
			var temptips=document.getElementById(this.id+'tips');
			if (temptips) {
				myTimeout = window.setTimeout(function() {temptips.style.display='none';}, 200); 
				//temptips.style.display='none';
			}
		}
	}
	//为信息列表添加选中效果
	var articleList=document.getElementById('articlelist');
	if (articleList) {
		var articleRows=articleList.getElementsByTagName('tr');
		for(i=0;i<articleRows.length;i++){
			articleRows[i].onclick=function() {
				var tempCheckbox=this.getElementsByTagName('input')[0];
				if (tempCheckbox) {
					if(this.className==''){
						this.className='selected';
						tempCheckbox.checked=true;
					} else {
						this.className='';
						tempCheckbox.checked=false;
					}
				}
			}
		}
	}
	//为IE下的Checkbox和Radio去处背景色
	if (isIE>-1) {
		minputs=document.getElementsByTagName('input');
		for(i=0;i<minputs.length;i++){
			if(minputs[i].type!='text'&&minputs[i].type!='file'){
				minputs[i].style.backgroundColor='transparent';
				minputs[i].style.backgroundImage='none';
				minputs[i].style.border='none';
			}
		}
	}
}
