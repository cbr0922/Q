// jfade.js
$(function($) {
	$.fn.jfade = function(settings) {
   
	var defaults = {
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".4",
		timing: "500"
	};
	var settings = $.extend(defaults, settings);
	settings.element = $(this);
			
	//set opacity to start
	$(settings.element).css("opacity",settings.start_opacity);
	//mouse over
	$(settings.element).hover(
	
		//mouse in
		function () {												  
			$(this).stop().animate({opacity: settings.high_opacity}, settings.timing); //100% opacity for hovered object
			$(this).siblings().stop().animate({opacity: settings.low_opacity}, settings.timing); //dimmed opacity for other objects
		},
		
		//mouse out
		function () {
			$(this).stop().animate({opacity: settings.start_opacity}, settings.timing); //return hovered object to start opacity
			$(this).siblings().stop().animate({opacity: settings.start_opacity}, settings.timing); // return other objects to start opacity
		}
	);
	return this;
	}
	
});
 
// 原banner.js

$().ready(function() {

		$('.jfade_image').jfade();

		$('.portfolio').jfade({

			start_opacity: ".4",

			high_opacity: "1",

			low_opacity: ".2",

			timing: "500"

		});

		$('.button').jfade({

			start_opacity: "1",

			high_opacity: "1",

			low_opacity: ".4",

			timing: "500"

		});

		$('.text').jfade({

			start_opacity: "1",

			high_opacity: "1",

			low_opacity: ".7",

			timing: "500"

		});

		$('.links').jfade({

			start_opacity: ".9",

			high_opacity: "1",

			low_opacity: ".2",

			timing: "500"

		});

	});  
//
	$(function(){
		// 先取得必要的元素並用 jQuery 包裝
		// 再來取得 $slides 的高度及設定動畫時間
		var $block = $('#abgneBlock'),
		    $blockA = $('#ban_meun'),
			$slides = $('#player ul.list', $block),			
			_height = $slides.find('li').height(),
			$li = $('li', $slides),
			_animateSpeed = 400, 
			timer, _speed = 4000;
		
		// 先移到最後一張
		//$slides.css({
		//	top: _height * ($li.length - 1) * -1
		//});
		
		// 產生 li 選項
		//var _str = '';
		//for(var i=0, j=$li.length;i<j;i++){
			// 每一個 li 都有自己的 className = playerControl_號碼
		//	_str += '<li class="playerControl_' + (i+1) + '">' + (i+1) + '</li>';
		//}

		// 產生 ul 並把 li 選項加到其中
		// 加上 mouseover 事件
		var $controlLi = $('#abgneBlock ul.playerControl li');		   
		$controlLi.mouseover(function(){
			clearTimeout(timer);

            // 定義焦點,雖已隱藏,但輪播時需要
			var $this = $(this);
			$this.addClass('current').siblings('.current').removeClass('current');
			// 移動位置到相對應的號碼
			$slides.stop().animate({
				top: _height * ($li.length - Math.abs($this.index()-$li.length+1) - 1) * -1
			}, _animateSpeed, function(){
				if(!_isOver) timer = setTimeout(moveNext, _speed);
			});

			return false;
		}).eq(0).mouseover();
		
		// 當滑鼠移到 $block 時則停止輪播
		// 移出時則繼續輪播
		var _isOver = false;
		$blockA.mouseenter(function(){
			clearTimeout(timer);
			_isOver = true;
		}).mouseleave(function(){
			_isOver = false;
			timer = setTimeout(moveNext, _speed);			
		});
 
        $block.mouseenter(function(){

			clearTimeout(timer);

			_isOver = true;

		}).mouseleave(function(){

			_isOver = false;

			timer = setTimeout(moveNext, _speed);
        });
		// 用來控制移動的函式
		function moveNext(){
			var _now = $controlLi.filter('.current').index();
			$controlLi.eq((_now+1) % $controlLi.length).mouseover();
		}


	});