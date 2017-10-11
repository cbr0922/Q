function trim(sstr)
{
	var astr="";
	var dstr="";
	var flag=0;
	for (i=0;i<sstr.length;i++)
	{
		if ((sstr.charAt(i)!=' ')||(flag!=0))
		{
			dstr+=sstr.charAt(i);
			flag=1;
		}
	}
	flag=0;
	for (i=dstr.length-1;i>=0;i--)
	{
		if ((dstr.charAt(i)!=' ')||(flag!=0))
		{
			astr+=dstr.charAt(i);
			flag=1;
		}
	}
	dstr="";
	for (i=astr.length-1;i>=0;i--) dstr+=astr.charAt(i);
	return dstr;
}

 function checkEmail(emailStr) {
   if (emailStr.length == 0) {
       return true;
   }
   var emailPat=/^(.+)@(.+)$/;
   var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]";
   var validChars="\[^\\s" + specialChars + "\]";
   var quotedUser="(\"[^\"]*\")";
   var ipDomainPat=/^(\d{1,3})[.](\d{1,3})[.](\d{1,3})[.](\d{1,3})$/;
   var atom=validChars + '+';
   var word="(" + atom + "|" + quotedUser + ")";
   var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
   var domainPat=new RegExp("^" + atom + "(\\." + atom + ")*$");
   var matchArray=emailStr.match(emailPat);
   if (matchArray == null) {
       return false;
   }
   var user=matchArray[1];
   var domain=matchArray[2];
   if (user.match(userPat) == null) {
       return false;
   }
   var IPArray = domain.match(ipDomainPat);
   if (IPArray != null) {
       for (var i = 1; i <= 4; i++) {
          if (IPArray[i] > 255) {
             return false;
          }
       }
       return true;
   }
   var domainArray=domain.match(domainPat);
   if (domainArray == null) {
       return false;
   }
   var atomPat=new RegExp(atom,"g");
   var domArr=domain.match(atomPat);
   var len=domArr.length;
   if ((domArr[domArr.length-1].length < 2) ||
       (domArr[domArr.length-1].length > 3)) {
       return false;
   }
   if (len < 2) {
       return false;
   }
   return true;
} 


<!--society message-->
var flag = false;
function setStatusAll(){
    var obj = document.forms[0];
	if(obj.recordid == null) return;

	if(flag == false){
		if(obj.recordid.length != null){
			for(i = 0 ; i < obj.recordid.length;i++){
				obj.recordid[i].checked = true;
				flag =true;
			}
		}else{
		    obj.recordid.checked = true;
		    flag = true;
		}
	}else{
		if(obj.recordid.length != null){
			for(i = 0 ; i < obj.recordid.length;i++){
				obj.recordid[i].checked = false;
				flag =false;
			}
		}else{
		    obj.recordid.checked = false;
		    flag = false;
		}
	}
}

function checkChoose(){
	var f = document.forms[0];
	var count = 0;
	if(f.recordid==null){
		//alert("当前没有消息，无法删除。");
		return 1;
	}
	if(f.recordid.length!=null){	
		for(var i=0;i<f.recordid.length;i++)
		{
			if(f.recordid[i].checked==true)
				count++;
		}
		if(count == 0){
			//alert("没有选择消息，无法删除。");
			return 2;
		}else{
			return 0;
		}
	}else {
		if(f.recordid != null){
			if(f.recordid.checked){
				return 0;
			}else{
				//alert("没有选择消息，无法删除。");
				return 2;
			}
		}else{
			//alert("当前没有消息，无法删除");
			return 1;
		}
	}
}

function delMessage(){
	var f = document.forms[0];
	if(checkChoose()==0){
		if(confirm("确定要删除所选信息吗？")){
			f.operate.value="delete";
			f.submit();
		}
	}else if(checkChoose()==1){
		alert("当前没有消息，无法删除");
		return;
	}else if(checkChoose()==2){
		alert("没有选择消息，无法删除。");
		return;
	}
}

function setMessageReaded(){
	var f = document.forms[0];
	var readed = f.readed.value;
	if(readed == 0){
		f.operate.value="readed";		
	}else if(readed==1){
		f.operate.value="unread";
	}
	if(checkChoose()==0){
		//if(confirm("确定要标记所选信息吗？")){
			f.submit();
		//}
	}else if(checkChoose()==1){
		alert("当前没有消息，无法标记");
		return;
	}else if(checkChoose()==2){
		alert("没有选择消息，无法标记。");
		return;
	}
}
function saveMessage(){
	var f = document.forms[0];
	if(checkChoose()==0){
		//if(confirm("确定要保存所选信息吗？")){
			f.operate.value="save";
			f.submit();
		//}
	}else if(checkChoose()==1){
		alert("当前没有消息，无法保存");
		return;
	}else if(checkChoose()==2){
		alert("没有选择消息，无法保存。");
		return;
	}
}

function replyMessage(){
	var f = document.forms[0];
	f.action = "MessageSend.do";
	f.operate.value="ready_back";
	f.submit();
}
function forwardMessage(){
	var f = document.forms[0];
	f.action = "MessageSend.do";
	f.operate.value="ready_add";
	f.submit();
}

function deleteShowMessage(){
	var f = document.forms[0];
	f.operate.value = "exec";
	if(confirm("确定要删除所选信息吗？")){
		f.submit();
	}
}

function saveShowMessage(){
	var f = document.forms[0];
	f.operate.value = "allow";
	f.submit();
}

<!--society message-->