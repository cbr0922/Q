// JavaScript Document
// JavaScript Document
(function($){


//$.fn.selInit = function(){return $(this).html("<option>請選擇</option>");};
$.area2 = function(area,co){
		$("#county2").selInit();
        $("#province2").selInit();
        $("#city2").selInit();

        $.each(area,function(p){$("#county2").append("<option value='"+p+"'>"+p+"</option>");});
		
        $("#county2").change(function(co){
                $("#province2").selInit();
                $("#city2").selInit();
				//alert($("#county option:selected").attr("value"));
				getzip2();
                $.each(area,function(p,x){
                        if($("#county2 option:selected").text() == p)
                        {
                                $.each(x,function(c,cx){
                                        $("#province2").append("<option value='"+c+"'>"+c+"</option>");
                                });
                                
                                $("#province2").change(function(){
                                        $("#city2").selInit();
										getzip2();
                                        $.each(x,function(c,cx){
                                                if($("#province2 option:selected").text() == c)
                                                        {
                                                                $.each(cx.split(","),function(){
                                                                        $("#city2").append("<option value='"+this+"'>"+this+"</option>");
                                                                });
																$("#city2").change(function(){
																		getzip2();
																});
                                                        }
                                        });
                                });
                        }
                });
        });
};

})(jQuery);

function getzip2(){
$.ajax({
																			url: "../zip.php",
																			data: "country=" + $("#county2 option:selected").text() + "&province=" + $("#province2 option:selected").text() + "&city=" + $("#city2 option:selected").text(),
																			type:'post',
																			dataType:"html",
																			success: function(msg){
																				//alert($("#county option:selected").text());
																				//if (msg!="")
																					$("#othercity2").attr("value",msg);
																					//alert(msg);
																				
																			}
																		});	
}

