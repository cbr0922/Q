// JScript source code

//check if the str is only contains blank
function chkblank(str){
	if (str.replace(/ */,"")=="")
		return true;
	else
		return false;
}
//trim blank
function trimblank(str){
	return str.replace(/ */,"");
}

//check if the str is too long
function chklength(str,intlen){
	if (str.length>intlen)
		return true;
	else
		return false;
}

//get current data and display it
function getcurdt(){
	var strdt;
	var dtnow=new Date();
	var arrweek=new Array(7);
	arrweek[0]="Sun";
	arrweek[1]="Wen";
	arrweek[2]="Tue";
	arrweek[3]="Wed";
	arrweek[4]="Thu";
	arrweek[5]="Fri";
	arrweek[6]="Sat";
	
	strdt=dtnow.getYear() +"-" + (dtnow.getMonth()+1) +"-" + dtnow.getDate() + "," + arrweek[dtnow.getDay()];
	
	return strdt; 
	
}

//check if the string given is a number
function isnum(strnum){
	if (strnum==""){
		return(-1);
	}
	else
	{
		if (isNaN(parseInt(strnum,10))){
			return(-1);
		}
		else
		{
			return(parseInt(strnum,10));
		}
	}
}

//check if the string given is money format and translate it to 0.00
function ismoney(strnum){

	var rst;
	if (strnum==""){
		return(-1);
	}
	else
	{
		if (isNaN(parseFloat(strnum))){
			return(-1);
		}
		else
		{
			rst=Math.round(parseFloat(strnum)*Math.pow(10,2))/Math.pow(10,2);

			return(rst);
		}
	}
}

//check if the string given is date format
function isdate(str){
	var re=/^(\d{4})-(\d{1,2})-(\d{1,2})$/;
	if(!re.test(str))
		return false;
		
	var r=str.match(re);
	var d=new Date(r[1],r[2]-1,r[3]);
	return d.getFullYear()==r[1]&&d.getMonth()==r[2]-1&&d.getDate()==r[3];
}

function chgtitle(strtitle){
	document.title=strtitle;
}

function navto(strlnkpg){
	//alert(strlnkpg);
	location.href=strlnkpg+"&page=" +selpg.value;
}	


function pressnumber(){
	if((window.event.keyCode>95 && window.event.keyCode<106) 
		|| (window.event.keyCode>47 && window.event.keyCode<59) 
		|| window.event.keyCode == 8
		|| window.event.keyCode == 46
		|| window.event.keyCode == 37
		|| window.event.keyCode == 39) {
	}
	else {
		window.event.returnValue =0;
		return false;
	}
}