$(function () {
$('#nav_cathd').naviDropDown({
 dropDownWidth: '479px'
});
$(".SubMenuA").css('display','none');
$(".SubMenuB").css('display','none');
$(".SubMenuC").css('display','none');
/*$(".SubMenuA>li>a").mouseover(function(){	 	  	   
	   $(this).parent('li').addClass("l2_menuBS");	              
 });
 $(".SubMenuA>li>a").mouseout(function(){	
 	 if($(this).parent('li').next().css('display')=='none')  	   
	   $(this).parent('li').removeClass("l2_menuBS");	              
 });*/
/*
A�ĤG�h�G���r#000�B�ƹL#cc2e56�����u�B���U#cc2e56�����u
B�ĤT�h�G���r#434343�B�ƹL#d94b5b�����u�B���U#d94b5b�����u
C�ĥ|�h�G���r#535353�B�ƹL#535353�����u

*/
$(".SubMenuA>li>a").mouseout(function(){	
   if($(this).parent('li').next().css('display')=='none') 
 	   	{$(this).css('color','#000');
	 	$(this).css('text-decoration','none');}
 });
  $(".SubMenuB>li>a").mouseout(function(){	
   if($(this).parent('li').next().css('display')=='none') {
 	   	$(this).css('color','#434343');
	 	$(this).css('text-decoration','none');	 	   	  	}   	 
	   	            
 });
   $(".SubMenuC>li>a").mouseout(function(){	 	  
	 	$(this).css('text-decoration','none');
        $(this).css('color','#535353');		
	   	            
 });
$(".SubMenuA>li>a").mouseover(function(){	
    $(this).css('color','#cc2e56');
	$(this).css('text-decoration','underline');	 	   	  	   	 	   	            
 });
   $(".SubMenuB>li>a").mouseover(function(){	
 	$(this).css('color','#d94b5b');
	$(this).css('text-decoration','underline');	 	   	  	   	 	   	            
 });
   $(".SubMenuC>li>a").mouseover(function(){	
	$(this).css('text-decoration','underline');	 
    $(this).css('color','#535353');		
 });
 $("#l2_menu2 a").click(function(){	
	 	 if(    $(this).parent().next().attr('class')=='SubMenuA'
	 	     || $(this).parent().next().attr('class')=='SubMenuB'
	 	     || $(this).parent().next().attr('class')=='SubMenuC'	 	     
	 	    )
	 	 {
	 	 if($(this).parent().next().css('display')=='none')
	 	 {	   
		     if($(this).parent().next().attr('class')=='SubMenuA')/* �P�_�p�G�OA,����������A,�A���}�J�I*/
			 {			
			 $("#l2_menu2>ol>li").css('background-position','-500px -62px');			
			 $('ul.SubMenuA').css('display', 'none');
			 }	
			 
             if($(this).parent().next().attr('class')=='SubMenuB')
			 {	 	 		 
			  $(".SubMenuA>li").css('background-position','-500px -3px');	 	
			  $(".SubMenuA>li>a").css('color','#000'); 
              $(".SubMenuA>li>a").css('text-decoration','none');			
			  $('ul.SubMenuB').css('display', 'none'); 			
			 }	
             if($(this).parent().next().attr('class')=='SubMenuC')
			 {		         
              $(".SubMenuB>li").css('background-position','-500px -3px');	 	
			  $(".SubMenuB>li>a").css('color','#434343'); 
              $(".SubMenuB>li>a").css('text-decoration','none');
			  $('ul.SubMenuC').css('display', 'none');
			 }
		    
           $(this).parent().next().css('display','');
	 	   if($(this).parent().next('').attr('class')=='SubMenuA'){
		      $(this).parent().css('background-position','-500px -96px');
			  $(this).css('text-decoration','underline');	
			  }
	 	   else if ($(this).parents().next().attr('class')=='SubMenuB')
	 	   	{                                                               /*�ĤG�h*/
	 	   		$(this).parent().css('background-position','-500px -38px'); 	
	 	   		$(this).css('color','#cc2e56');
	 	   	    $(this).css('text-decoration','underline');	 	   	  	   	 
	 	   	}	  
	 	   else
	 	   	{	 	   		 	   		 
			 $(this).parent().css('background-position','-890px -38px'); 	/*�ĤT�h*/
	 	   	 $(this).css('color','#d94b5b');
	 	   	 $(this).css('text-decoration','underline');	 	   	  	   	 
	 	   	}
	 	 }  
	 	 else
	 	 {	
	 	 	 $(this).parent().next().css('display','none');			 
	 	 	 if($(this).parent().next().attr('class')=='SubMenuA') 
			    {$(this).parent().css('background-position','-898px -62px');}
	 	 	 else if ($(this).parents().next().attr('class')=='SubMenuB')
	 	 	 {
	 	 	 		  $(this).parent().css('background-position','-898px -3px');/*�ĤG�h*/
	 	 	 		  $(this).css('color','#000');
	 	   	          $(this).css('text-decoration','none');	 	   	  	   	 
	 	   }
            else
	 	   	{	 	   		 	   		 
			 $(this).parent().css('background-position','-890px -3px'); 	
	 	   	 $(this).css('color','#434343');
	 	   	 $(this).css('text-decoration','underline');	 	   	  	   	 
	 	   	}		   
	 	 }
	 	}	 
	 });

//var  $LBU = $('#l2_banner ul'), 
//		 $LBUI = $LBU.find('li'),
	     //fadeOutSpeed = 1000,	  
		 //fadeInSpeed = 1000,		
		 //dZI = 10;			 	
//$(".btn>span").mouseover(function(){	 		   	   
//	   $LBUI.eq($(this).index()).stop().fadeTo(fadeInSpeed, 1).css('zIndex', dZI).siblings().stop().fadeTo(fadeOutSpeed, 0).css('zIndex', dZI - 1);
//	   $(this).addClass('on').siblings().removeClass('on');		   	   
// }); 	 
// $(".btn>span").eq(0).mouseover();	 


});