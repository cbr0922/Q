/*
Copyright by Audi 2006
http://audi.tw
http://www.carousel.com.tw
*/
 
var FloatTop = 216 + document.getElementById('floater').clientHeight;//document.documentElement.clientHeight;//scrollTop;//372;   //chang by salen
var currentX = currentY = 0; 
var whichIt = null; 
var lastScrollX = 0; lastScrollY = 0;
var layerWidth,layerHeight;

var scrollSpeed=30  

function init(){
	
	layerHeight=document.getElementById('floater').clientHeight; //31 -> 96
	layerWidth=document.getElementById('floater').clientWidth; //75
	
	bHeight=document.body.clientHeight; //624
	bWidth=document.body.clientWidth;	//1003
	

	newY= FloatTop - layerHeight;
	newX = document.body.clientWidth - document.getElementById('floater').clientWidth;//bWidth - layerWidth;
	document.getElementById('floater').style.top= newY + "px";	
	//document.getElementById('floater').style.left= (newX) + "px";
	document.getElementById('floater').style.visibility="visible";
	
	window.setInterval('heartBeat()',1);
	
	heartBeat();
	
}

function heartBeat() {

	
	var X = document.body.clientWidth - document.getElementById('floater').clientWidth;// - 2;//salen 右边留点距离
	document.getElementById('floater').style.left= X + "px";
	
	//diffY = window.document.body.scrollTop; 
	diffY = document.documentElement.scrollTop+document.body.scrollTop;//document.documentElement.scrollTop;  
	//网友提示：由于document.documentElement.scrollTop和document.body.scrollTop在标准模式或者是奇怪模式下都只有一个会返回有效的值，所以都加上也不会有问题
	
	//diffX = 0; 
	if (document.getElementById('floater').style.visibility!='hidden'){
		if(diffY != lastScrollY){
			percent = 1 * (diffY - lastScrollY) / scrollSpeed;
			if(percent > 0) percent = Math.ceil(percent);
			else percent = Math.floor(percent);
			newY=parseInt(document.getElementById('floater').style.top);
			newY+=percent;
			document.getElementById('floater').style.top = newY + "px";
			lastScrollY += percent;
		}
	}
} 

 // end salen

addLoadEvent(init);