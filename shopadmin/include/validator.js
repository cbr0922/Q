<script language="JavaScript">  

//提交表单输入有效性检测

function Form_Validator(theForm){
  
if (theForm.address){
	if (theForm.address.value == ""){
	    alert("请输入联系地址");
		theForm.address.focus();
	    return (false);
	}
}
//--></script>