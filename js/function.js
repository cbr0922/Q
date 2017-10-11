//----------------------------------------------------------------------------------------------------------------------------
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
//----------------------------------------------------------------------------------------------------------------------------

//去掉空格
function Trim(str){
if(str.charAt(0) == " "){
str = str.slice(1);
str = Trim(str); 
}
if (str.length > 5000){
	alert('你输入的内容太多了吧：） 系统默认是5000个字符。');
return false;
}
return str;
}
//判断是否是空
function isEmpty(pObj,errMsg){
var obj = eval(pObj);
if( obj == null || Trim(obj.value) == ""){
if (errMsg == null || errMsg =="")
alert("Data Error");//"请输入正确数据!"
else
alert(errMsg); 
obj.focus(); 
return false;
}
return true;
}
	  


//判断是否是数字
function isNumber(pObj,errMsg){
 var obj = eval(pObj);
 strRef = "1234567890";
 if(!isEmpty(pObj,errMsg))return false;
 for (i=0;i<obj.value.length;i++) {
   tempChar= obj.value.substring(i,i+1);
   if (strRef.indexOf(tempChar,0)==-1) 
	 {
      if (errMsg == null || errMsg =="")
      alert("Must is number");//必须是数字！
      else
      alert(errMsg);
      if(obj.type=="text") 
      obj.focus(); 
      return false; 
     }
  }
 return true;
}


// 判断必须单选一个
function chkRadio(o){
for (i=0;i<o.length;i++){
if (o[i].checked) 
	return true;
}
return false;
} 


function checkbox(theform,checkname,func_limit) 
{ 


   var FunctionsClass=""; 
   var FunctionsNum=0;
   for(var i=0;i<document.theform.checkname.length;i++) { 
        
      if (document.theform.checkname[i].checked)  { 	     
         FunctionsClass=FunctionsClass+document.theform.checkname[i].value+","; 
		 FunctionsNum++;   
       } 
   } 
  
   if (FunctionsClass == ''){
    alert('请在《关注的功能分类》中请选择一个条件！');	
    return false;
   }
   
   if (FunctionsNum>func_limit){
   alert('对不起，您最多只能选择'+func_limit+'个《关注的功能分类》条件！');
   //news.func_name.focus();
   return false;
   }

/*
   下边是往页面回写用，分割的。被选中的字符串。
   FunctionsClass=FunctionsClass.substring(0,FunctionsClass.length-1); 
   document.news.FunctionsClassChar.value=FunctionsClass; 
 */
}

//判断是否是正确的EMAIL格式
function mail_check (email){
  if ( (email.match(/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/)) ||
(!email.match(/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/)))
       {
        return(false);
       }
     else
         {
          return(true);
         }
}


/*
if (!isEmpty(theform.email,'请输入E-MAIL地址！')) return false;
	
var re = new RegExp("^([A-Za-z0-9_|-]+[.]*[A-Za-z0-9_|-]+)+@[A-Za-z0-9|-]+([.][A-Za-z0-9|-]+)*[.][A-Za-z0-9]+$","ig");
if (!re.test(theform.email.value))
	{
    alert("E-MAIL输入不正确！");
	theform.email.focus();
	return false;
	}
*/
/*************************************************************白菜的*********************************/
function checklogin (theform,Badname,Badnamelength,Badpass)
{

if (!isEmpty(theform.username,Badname)) return false; //'请输入会员名称'

if (3 > theform.username.value.length  || 100 < theform.username.value.length){
 alert(Badnamelength);  //'会员名称不能小于3位或大于12位'
 theform.username.focus();
 return false;
}

/*
var tmp;
tmp=theform.username.value.match(/^[a-zA-Z]\w{0,15}$/);  //帳號限以英文字母,數字,或「_」符號組成,第一個字必須是英文字母
if (!tmp){
    alert("无效的用户名格式！第一个字必须是英文字母... ");
    theform.username.focus();
    return false;
  }
*/

if (!isEmpty(theform.passwd,Badpass)) return false;//'请输入会员密码'
}

/*************************************************************Davy********************************/

/*这个就放到页面上去用吧
function checkUser(){
	var checkOK = "abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ0123456789_ ";
	var checkStr = form1.username.value;
	var allValid = true;
	for (i = 0;  i < checkStr.length;  i++){
		ch = checkStr.charAt(i);
		for (j = 0;  j < checkOK.length;  j++)
		if (ch == checkOK.charAt(j))
			break;
		if (j == checkOK.length){
			allValid = false;
			break;
		}
	}
	if (!allValid){
		alert("用户名只能由字母、数字、下划线组成！");
		form1.username.select();
		return (false);
	}
	return (true);	
}
*/



function checkforget (theform,Badname,Bademail)
{

 if (!isEmpty(theform.username,Badname)) return false; //'请输入会员名称'
 if (!isEmpty(theform.email,Bademail)) return false; //'输入Email地址

 if (!mail_check(theform.email.value)){
    alert(Bademail);
    theform.email.focus();
    return false;
  }

}

function checkSerial (theform,Badname,Bademail)
{

 if (!isEmpty(theform.Serial,Badname)) return false; 
 if (!isEmpty(theform.email,Bademail)) return false; 

 if (!mail_check(theform.email.value)){
    alert(Bademail);
    theform.email.focus();
    return false;
  }

}


//--------------------------------------------------------------------------------以下不用了----------------------------




//----------------------新用户注册------------------------------

function checkregformuser(theform,Badname,Badnamelength,Badpass,Badpasslength,Passdiffer,Badmail,Badturename,Badsex,Badborndate){

if (!isEmpty(theform.username,Badname)) return false; //'请输入会员名称'

if (3 > theform.username.value.length  || 12 < theform.username.value.length){
 alert(Badnamelength);//'会员名称不能小于3位或大于12位'
 theform.username.focus();
 return false;
}


/*
var tmp;
tmp=theform.username.value.match(/^[a-zA-Z@.-_]\w{0,15}$/);  //帳號限以英文字母,數字,或「_」符號組成,第一個字必須是英文字母
if (!tmp){
    alert("无效的用户名格式！");
    theform.username.focus();
    return false;
  }
*/
if (!isEmpty(theform.pw1,Badpass)) return false; //'请输入会员密码'

if (16 < theform.pw1.value.length || 6 > theform.pw1.value.length) {
   alert(Badpasslength); //'密码不能小于6位或大于16位'
   return false;
   }

if (theform.pw1.value!=theform.pw2.value){
   alert(Passdiffer); //'请正确输入确认密码！'
   theform.pw2.value='';
   theform.pw2.focus();
   return false;
   }


if (!isEmpty(theform.email,Badmail)) return false;
if (!mail_check(theform.email.value)){
    alert(Badmail);
    theform.email.focus();
    return false;
  }


if (!isEmpty(theform.truename,Badturename)) return false;


   var chkRadio;
   var strchoice=''; 
   var check_box='';

   chkRadio=theform.sex;
   for(var i=0;i<chkRadio.length;i++) { 
      if (chkRadio[i].checked)  { 
         strchoice=strchoice+chkRadio[i].value+","; 
       }  
   } 
    if (strchoice==''){
     alert(Badsex);
    return false;
   }

if (!isEmpty(theform.byear,Badborndate)) return false;
}


//--------------------------------------------------------------------------------以上不用了----------------------------

//----------------------用户资料修改------------------------------

function updateuser(theform,Badmail,Badname,Badborndate){
	hiddenerror();
	if (chkblank(theform.email.value)) showerror("error_email");
	if (chkblank(theform.truename.value)) showerror("error_truename");
	if (chkblank(theform.byear.value)) showerror("error_byear");
	if (chkblank(theform.county.value)) showerror("error_county");
	if (chkblank(theform.province.value)) showerror("error_county");
	if (chkblank(theform.address.value)) showerror("error_address");
	
	if (!isEmpty(theform.email,Badmail)) return false; //请输入EMAIL地址
	if (!mail_check(theform.email.value)){
		alert(Badmail);
		
		theform.email.focus();
		return false;
	  }
	if (!isEmpty(theform.truename,Badname)) {return false;} //请输入真实姓名
	if (!isEmpty(theform.byear,Badborndate)) {return false;} //请输入出生日期
	if (!isEmpty(theform.county,"請選擇地區")) {return false;}
	if (!isEmpty(theform.province,"請選擇地區")) {return false;}
	if (!isEmpty(theform.address,"請填寫聯繫地址")) {return false;}
	if (chkblank(theform.phone.value) && chkblank(theform.mobile.value)){
		alert("行動電話與市話，請至少填寫一項。");
		theform.phone.focus();
		return false;
	}
}

function showerror(obj){
	document.getElementById(obj).style.color='#FF0000';
	document.getElementById(obj).innerHTML = "*必填";
}
function hiddenerror(){
	var len = document.getElementsByTagName("span").length;
	var obj = document.getElementsByTagName("span");
	for(i=0;i<len;i++){
		obj[i].innerHTML = "";
	}
}


//----------------------用户评论------------------------------
function CheckComments(Pleasecomment,CommentLength)
{
if (!isEmpty(formcomment.content,Pleasecomment)) return false; //'请输入您的提问！'

if (6 > formcomment.content.value.length  || 200 < formcomment.content.value.length){
 alert(CommentLength); //'您的提问内容不符合字数要求！'
 formcomment.content.focus();
 return false;
}

}

//----------------------用户取消定单说明------------------------------
function checkCancelOrder(theform,errorone,errortwo)
{
if (!isEmpty(theform.isay,errorone)) return false;

if (2 > theform.isay.value.length  || 200 < theform.isay.value.length){
 alert(errortwo);
 theform.isay.focus();
 return false;
}

}


//----------------------shop-cart订单情况------------------------------
function checkPayfirst(theform,oneerror,twoerror,threerror,firsterror)
{
   var chkRadio;
   var strchoice=''; 
   var check_box='';
   var ifFirstpayValue = 0;

  
   
   chkRadio =  theform.ifFirstpay;
      if (chkRadio[0].checked)  { 
          ifFirstpayValue = 0 ; 
      }
	  
	  if (chkRadio[1].checked){
	      ifFirstpayValue = 1 ; 
	  } 





   var chkRadio;
   var strchoice=''; 
   var check_box='';
   
   chkRadio =  theform.ifFirstpay;

    for(var i=0;i<chkRadio.length;i++) { 
       if (chkRadio[i].checked)  { 
          strchoice = strchoice+chkRadio[i].value+","; 
        }  
    } 


    if (strchoice==''){
     alert(firsterror); //'请选择付款方式'
    return false;
   }




   var chkRadio;
   var strchoice=''; 
   var check_box='';
   
   chkRadio =  theform.deliveryname;

    for(var i=0;i<chkRadio.length;i++) { 
       if (chkRadio[i].checked)  { 
          strchoice = strchoice+chkRadio[i].value+","; 
        }  
    } 


    if (strchoice==''){
     alert(oneerror); //'请选择配送方式'
    return false;
   }


if (ifFirstpayValue==1)
{
   var chkRadio;
   var strchoice=''; 
   var check_box='';   
    chkRadio=theform.paymentname;
     for(var i=0;i<chkRadio.length;i++) { 
       if (chkRadio[i].checked)  { 
         strchoice=strchoice+chkRadio[i].value+","; 
       }  
     } 
       if (strchoice==''){
        alert(twoerror);     //'请选择付款方式'
        return false;
       }
 }

var Marginvalue = payfirst.Margin.value;
if (Marginvalue<0 && payfirst.ViewState.value == "yes")
{
	alert(threerror);
	return false;
}

}



















