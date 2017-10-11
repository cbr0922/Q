// JavaScript Document
(function($){


$.fn.selInit = function(){return $(this).html("<option>請選擇</option>");};
$.area = function(area,co){
		$("#county").selInit();
        $("#province").selInit();
        $("#city").selInit();

        $.each(area,function(p){$("#county").append("<option value='"+p+"'>"+p+"</option>");});
		//alert(co);
        $("#county").change(function(ob){
                $("#province").selInit();
                $("#city").selInit();
				//alert($("#county option:selected").attr("value"));
				getzip();
				//alert(co);
				if(co=="1")
					 getTransport();
                $.each(area,function(p,x){
                        if($("#county option:selected").text() == p)
                        {
                                $.each(x,function(c,cx){
                                        $("#province").append("<option value='"+c+"'>"+c+"</option>");
                                });
                                
                                $("#province").change(function(){
                                        $("#city").selInit();
										getzip();
                                        $.each(x,function(c,cx){
                                                if($("#province option:selected").text() == c)
                                                        {
                                                                $.each(cx.split(","),function(){
                                                                        $("#city").append("<option value='"+this+"'>"+this+"</option>");
                                                                });
																$("#city").change(function(){
																		getzip();
																});
                                                        }
                                        });
                                });
                        }
                });
        });
};

})(jQuery);

function getzip(){
$.ajax({
																			url: "../zip.php",
																			data: "country=" + $("#county option:selected").text() + "&province=" + $("#province option:selected").text() + "&city=" + $("#city option:selected").text(),
																			type:'post',
																			dataType:"html",
																			success: function(msg){
																				//alert($("#county option:selected").text());
																				//if (msg!="")
																					$("#othercity").attr("value",msg);
																					//alert(msg);
																				
																			}
																		});	
}

