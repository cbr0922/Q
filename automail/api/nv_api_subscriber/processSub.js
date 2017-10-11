
function processSub() {
	var params="";
	for (var i = 0; i < processSub.arguments.length; i++)  {                   
		params= params+processSub.arguments[i]+"&";
	}
	var finalUrl=url+"api/processSub.php";
	$.ajax({
        type: "POST",
        url: finalUrl,
		data: params,
        success: function (response) {
        },
        error: function (xhr, status) {
            alert('Unknown error ' + status);
        }
    });	
}

