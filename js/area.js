var ifmobile = 0;

function iniArea(ElementId,ifshowcity,txtcountry,txtprovince,txtcity){

	$.ajax({

		url: "../area_select.php",

		data: "level=0&sel=" + encodeURIComponent(txtcountry),

		type:'get',

		dataType:"html",

		success: function(msg){

			$('#county' + ElementId).html(msg);

			if(ifmobile==1)

				$('#county' + ElementId).selectmenu('refresh');

			province(ElementId,ifshowcity,txtprovince,txtcity);

		}



	});

	$('#county' + ElementId).change(function(){province(ElementId,ifshowcity,"","");});

	$('#province' + ElementId).change(function(){city(ElementId,"");});

	$('#city' + ElementId).change(function(){getzip(ElementId);});

}

function province(ElementId,ifshowcity,txtprovince,txtcity){



    var val = $("#county" + ElementId).find("option:selected").text();

	//alert(val);

    var state = "province";

    $.ajax({

        type: "get",

		dataType:"html",

        url: "../area_select.php",

        data: "areaname=" + encodeURIComponent(val) + "&level=1&sel=" + encodeURIComponent(txtprovince),

        success: function(msg){

			if(msg!=0){

				$("#province" + ElementId).css("display","");

           	 	$("#province" + ElementId).html(msg);

				if(ifmobile==1)

					$('#province' + ElementId).selectmenu('refresh');

				if(ifshowcity==1)

					city(ElementId,txtcity);

				//else

					//getTransport();

			}else{

				$("#province" + ElementId).empty();

				$("#province" + ElementId).css("display","none");

				if(ifshowcity==1){

					$("#city" + ElementId).empty();

					$("#city" + ElementId).css("display","none");

					getzip(ElementId);

				}//else

					//getTransport();

			}

        },

    });

}

function city(ElementId,txtcity){

    var val = $("#province" + ElementId).find("option:selected").text();

    var state = "city";

    $.ajax({

        type: "get",

       url: "../area_select.php",

        data: "areaname=" + encodeURIComponent(val) + "&level=2&sel=" + encodeURIComponent(txtcity),

        success: function(msg){

			if(msg!=0){

				$("#city" + ElementId).css("display","");

           		 $("#city" + ElementId).html(msg);

				 if(ifmobile==1)

					 $('#city' + ElementId).selectmenu('refresh');

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

		data: "country=" + $("#county" + ElementId).find("option:selected").text() + "&province=" + $("#province" + ElementId).find("option:selected").text() + "&city=" + $("#city" + ElementId).find("option:selected").text(),

		type:'post',

		dataType:"html",

		success: function(msg){

			$("#othercity" + ElementId).val(msg);



		}

	});

}
