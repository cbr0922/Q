function isDateString(sDate)
{	var iaMonthDays = [31,28,31,30,31,30,31,31,30,31,30,31]
	var iaDate = new Array(3)
	var year, month, day

	if (arguments.length != 1) return false
	iaDate = sDate.toString().split("-")
	if (iaDate.length != 3) return false
	if (iaDate[1].length > 2 || iaDate[2].length > 2) return false

	year = parseFloat(iaDate[0])
	month = parseFloat(iaDate[1])
	day=parseFloat(iaDate[2])

	if (year < 1900 || year > 2100) return false
	if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) iaMonthDays[1]=29;
	if (month < 1 || month > 12) return false
	if (day < 1 || day > iaMonthDays[month - 1]) return false
	return true
}

function stringToDate(sDate, bIgnore)
{	var bValidDate, year, month, day
	var iaDate = new Array(3)
	
	if (bIgnore) bValidDate = true
	else bValidDate = isDateString(sDate)
	
	if (bValidDate)
	{  iaDate = sDate.toString().split("-")
		year = parseFloat(iaDate[0])
		month = parseFloat(iaDate[1]) - 1
		day=parseFloat(iaDate[2])
		return (new Date(year,month,day))
	}
	//else return (new Date(1900,1,1))
    else return false;
}

function searchsubmit(errorone,errortwo,errorthree){

if (!stringToDate(document.optForm.begtime.value)){
alert(errorone); //"第一个发布日期是不正确的日期格式！"
document.optForm.begtime.focus();
return false;
}

if (!stringToDate(document.optForm.endtime.value)){
alert(errortwo); //"第二个发布日期是不正确的日期格式！"
document.optForm.endtime.focus();
return false;
}


if (stringToDate(document.optForm.endtime.value,true)<stringToDate(document.optForm.begtime.value,true))
{
alert(errorthree); //"第二个发布日期不能早于第一个发布日期!"
document.optForm.endtime.focus();return false;
}




document.optForm.submit();
} 