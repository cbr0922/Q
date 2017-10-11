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
 
// ��banner.js

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
		// �����o���n�������å� jQuery �]��
		// �A�Ө��o $slides �����פγ]�w�ʵe�ɶ�
		var $block = $('#abgneBlock'),
		    $blockA = $('#ban_meun'),
			$slides = $('#player ul.list', $block),			
			_height = $slides.find('li').height(),
			$li = $('li', $slides),
			_animateSpeed = 400, 
			timer, _speed = 4000;
		
		// ������̫�@�i
		//$slides.css({
		//	top: _height * ($li.length - 1) * -1
		//});
		
		// ���� li �ﶵ
		//var _str = '';
		//for(var i=0, j=$li.length;i<j;i++){
			// �C�@�� li �����ۤv�� className = playerControl_���X
		//	_str += '<li class="playerControl_' + (i+1) + '">' + (i+1) + '</li>';
		//}

		// ���� ul �ç� li �ﶵ�[��䤤
		// �[�W mouseover �ƥ�
		var $controlLi = $('#abgneBlock ul.playerControl li');		   
		$controlLi.mouseover(function(){
			clearTimeout(timer);

            // �w�q�J�I,���w����,�������ɻݭn
			var $this = $(this);
			$this.addClass('current').siblings('.current').removeClass('current');
			// ���ʦ�m��۹��������X
			$slides.stop().animate({
				top: _height * ($li.length - Math.abs($this.index()-$li.length+1) - 1) * -1
			}, _animateSpeed, function(){
				if(!_isOver) timer = setTimeout(moveNext, _speed);
			});

			return false;
		}).eq(0).mouseover();
		
		// ��ƹ����� $block �ɫh�������
		// ���X�ɫh�~�����
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
		// �Ψӱ���ʪ��禡
		function moveNext(){
			var _now = $controlLi.filter('.current').index();
			$controlLi.eq((_now+1) % $controlLi.length).mouseover();
		}


	});