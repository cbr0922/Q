function iniArea(ElementId,ifshowcity,txtcountry,txtprovince,txtcity){
	$.ajax({
		url: "../area_select.php",
		data: "level=0&select=" + encodeURIComponent(txtcountry),
		type:'get',
		dataType:"html",
		success: function(msg){
			$('#county' + ElementId).html(msg);
			province(ElementId,ifshowcity,txtprovince,txtcity);
		}
		
	});
	$('#county' + ElementId).change(function(){province(ElementId,ifshowcity,"","");});
	$('#province' + ElementId).change(function(){city(ElementId,"");});
	$('#city' + ElementId).change(function(){getzip(ElementId);});
}
function province(ElementId,ifshowcity,txtprovince,txtcity){
    var val = $("#county" + ElementId).val();
    var state = "province";
    $.ajax({
        type: "get",
		dataType:"html",
        url: "../area_select.php",
        data: "areaname=" + encodeURIComponent(val) + "&level=1&select=" + encodeURIComponent(txtprovince),
        success: function(msg){
			if(msg!=0){
				$("#province" + ElementId).css("display","");
           	 	$("#province" + ElementId).html(msg);
				if(ifshowcity==1)
					city(ElementId,txtcity);
				else
					getTransport();
			}else{
				$("#province" + ElementId).empty();	
				$("#province" + ElementId).css("display","none");
				if(ifshowcity==1){
					$("#city" + ElementId).empty();	
					$("#city" + ElementId).css("display","none");
					getzip(ElementId);
				}else
					getTransport();
			}
        },
    });
}
function city(ElementId,txtcity){
    var val = $("#province" + ElementId).val();
    var state = "city";
    $.ajax({
        type: "get",
       url: "../area_select.php",
        data: "areaname=" + encodeURIComponent(val) + "&level=2&select=" + encodeURIComponent(txtcity),
        success: function(msg){
			if(msg!=0){
				$("#city" + ElementId).css("display","");
           		 $("#city" + ElementId).html(msg);
				 getzip(ElementId);
			}else{
				$("#city" + ElementId).empty();	
				$("#city" + ElementId).css("display","none");	
				getzip(ElementId);
			}
        },
    });
}
function getzip(ElementId){
	$.ajax({
		url: "../zip.php",
		data: "country=" + $("#county" + ElementId).val() + "&province=" + $("#province" + ElementId).val() + "&city=" + $("#city" + ElementId).val(),
		type:'post',
		dataType:"html",
		success: function(msg){
			$("#othercity" + ElementId).attr("value",msg);
			
		}
	});	
}
