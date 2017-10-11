$(function() {
	var items = $('.overlapblackbg, .slideLeft');
	var wsmenucontent = $('.wsmenucontent');
	
	var menuopen = function() {
	$(items).removeClass('menuclose').addClass('menuopen');
						}
	var menuclose = function() { 
	$(items).removeClass('menuopen').addClass('menuclose');
	}

	$('#navToggle').click(function(){
		if (wsmenucontent.hasClass('menuopen')) {$(menuclose)}
		else {$(menuopen)}
	});
	wsmenucontent.click(function(){
		if (wsmenucontent.hasClass('menuopen')) {$(menuclose)}
	});
	
	$('#navToggle,.overlapblackbg').on('click', function(){
	$('.wsmenucontainer').toggleClass( "mrginleft" );
	});

	$('.wsmenu-list li').has('.wsmenu-submenu, .wsmenu-submenu-sub, .wsmenu-submenu-sub-sub').prepend('<span class="wsmenu-click"><i class="wsmenu-arrow fa fa-angle-down"></i></span>');
	
	$('.wsmenu-list li').has('.megamenu').prepend('<span class="wsmenu-click"><i class="wsmenu-arrow fa fa-angle-down"></i></span>');
		
	$('.wsmenu-mobile').click(function(){
		$('.wsmenu-list').slideToggle('slow');
	});
	$('.wsmenu-click').click(function(){
	$(this).siblings('.wsmenu-submenu').slideToggle('slow');
	$(this).children('.wsmenu-arrow').toggleClass('wsmenu-rotate');
	$(this).siblings('.wsmenu-submenu-sub').slideToggle('slow');
	$(this).siblings('.wsmenu-submenu-sub-sub').slideToggle('slow');
	$(this).siblings('.megamenu').slideToggle('slow');
		
	});

});

$(function() {
	var items2 = $('.overlapblackbg2, .slideLeft2');
	var wsmenu2content2 = $('.wsmenu2content2');
	
	var menuopen2 = function() {
	$(items2).removeClass('menuclose2').addClass('menuopen2');
						}
	var menuclose2 = function() { 
	$(items2).removeClass('menuopen2').addClass('menuclose2');
	}

	$('#navToggle2').click(function(){
		if (wsmenu2content2.hasClass('menuopen2')) {$(menuclose2)}
		else {$(menuopen2)}
	});
	wsmenu2content2.click(function(){
		if (wsmenu2content2.hasClass('menuopen2')) {$(menuclose2)}
	});
	
	$('#navToggle2,.overlapblackbg2').on('click', function(){
	$('.wsmenu2container2').toggleClass( "mrginleft2" );
	});

	$('.wsmenu2-list2 li').has('.wsmenu2-submenu2, .wsmenu2-submenu2-sub, .wsmenu2-submenu2-sub-sub').prepend('<span class="wsmenu2-click2"><i class="wsmenu2-arrow fa fa-angle-down"></i></span>');
	
	$('.wsmenu2-list2 li').has('.megamenu2').prepend('<span class="wsmenu2-click2"><i class="wsmenu2-arrow fa fa-angle-down"></i></span>');
		
	$('.wsmenu2-mobile').click(function(){
		$('.wsmenu2-list2').slideToggle('slow');
	});
	$('.wsmenu2-click2').click(function(){
	$(this).siblings('.wsmenu2-submenu2').slideToggle('slow');
	$(this).children('.wsmenu2-arrow').toggleClass('wsmenu2-rotate2');
	$(this).siblings('.wsmenu2-submenu2-sub').slideToggle('slow');
	$(this).siblings('.wsmenu2-submenu2-sub-sub').slideToggle('slow');
	$(this).siblings('.megamenu2').slideToggle('slow');
		
	});

});