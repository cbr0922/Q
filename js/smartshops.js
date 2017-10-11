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
	arrweek[1]="Wed";
	arrweek[2]="Tue";
	arrweek[3]="Wen";
	arrweek[4]="Thu";
	arrweek[5]="Fri";
	arrweek[6]="Sat";
	
	strdt=dtnow.getYear() +"-" + (dtnow.getMonth()+1) +"-" + dtnow.getDate() +  arrweek[dtnow.getDay()];
	
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
		if (parseInt(strnum,10)!=strnum){
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

/* Digital Check */
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

/**
* Toggles the check state of a group of boxes
*
* Checkboxes must have an id attribute in the form cb0, cb1...
* @param The number of box to 'check'
*/
function checkAll( n ) {
	var f = document.adminForm;
	var c = f.toggle.checked;
	var n2 = 0;
	for (i=0; i < n; i++) {
		cb = eval( 'f.cb' + i );
		if (cb) {
			cb.checked = c;
			n2++;
		}
	}
	if (c) {
		document.adminForm.boxchecked.value = n2;
	} else {
		document.adminForm.boxchecked.value = 0;
	}
}

/**
*/
function listItemTask( id, task ) {
	var f = document.adminForm;
	cb = eval( 'f.' + id );
	if (cb) {
		cb.checked = true;
		submitbutton(task);
	}
	return false;
}

function isChecked(obj){
	if (obj.checked == true){
		document.adminForm.boxchecked.value++;
	}
	else {
		document.adminForm.boxchecked.value--;
	}
}

function isSelected( n ,text) {
	if (document.adminForm.boxchecked.value==0){
		alert(text);
		return false;
	}else{
		for (i=0; i < n; i++) {
			cb = eval('document.adminForm.cb'+i);
			if (cb.checked==true) {
				return cb.value;
			}
		}
	}
}

//Goods maintainment list,change the Status
function changes(typ,obj,forms){
	id = obj.parentElement.parentElement.rowIndex - 1;
	//alert(id);
	var f = document.adminForm;
	cbtext = eval( 'f.' + typ + '_id');
	cbimg = eval('f.' + typ + '_img');
	if (cbtext[id].value==1) {
		cbtext[id].value=0;
		cbimg[id].src = "images/" + forms + "/publish_x.png";
	}else{
		cbtext[id].value=1;
		cbimg[id].src = "images/" + forms + "/publish_g.png";
	}
}


//-----order list go up------

function goUp(obj,cellnum){
	curRow = obj.parentElement.parentElement;
	var curLn = curRow.rowIndex;
	var lastLn = curRow.rowIndex - 1;

	if (curLn!=1){
		lastRow = obj.parentElement.parentElement.parentElement.rows[lastLn];
		for(var i=0;i<cellnum;i++){
			tmpHtml = curRow.cells[i].innerHTML;
			curRow.cells[i].innerHTML = lastRow.cells[i].innerHTML;
			lastRow.cells[i].innerHTML = tmpHtml;
		}
	}else{
		alert("Cannot move up");
	}
}

//-----order list go down------

function goDown(obj,cellnum){
	totalRows = obj.parentElement.parentElement.parentElement.rows.length - 2;
	curRow = obj.parentElement.parentElement;
	var curLn = curRow.rowIndex;
	var lastLn = curRow.rowIndex + 1;

	if (totalRows != curLn) {
		lastRow = obj.parentElement.parentElement.parentElement.rows[lastLn]
		for(var i=0;i<cellnum;i++){
			tmpHtml = curRow.cells[i].innerHTML;
			curRow.cells[i].innerHTML = lastRow.cells[i].innerHTML;
			lastRow.cells[i].innerHTML = tmpHtml;
		}
	}else{
		alert("Cannot Move Down!");
	}
}
