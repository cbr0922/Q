// JavaScript Document
(function() {
	var _func_timer;
	var _cnt = 1;
	
	// 引数用
	var _max_num;
	var _fade;
	var _timer;
	
	jQuery.fn.fadeSlideShow = function(config){
		config = jQuery.extend({
			max: "0",
			fade:"fast",
			timer:5000
		},config);
		_max_num = config.max;
		_fade = config.fade;
		_timer = config.timer;
		_func_timer = setTimeout(loopFunc,_timer);
		jQuery('#visual_img_ li').slice(1,_max_num).css('display','none').end();
		jQuery('#visual_img_ li:first-child').css('z-index','10');
		jQuery('#visual_nav_ a').click(fadeImages);
	};
	
	function loopFunc(){
		clearTimeout(_func_timer);
		jQuery('#visual_nav_ a').unbind('click',fadeImages);
		jQuery('#visual_nav_ a').click(unbindFadeImages);
		// 表示されていたものの番号
		var before_num = _cnt;
		_cnt++;
		if(_cnt > jQuery("#visual_nav_ li").length){
			_cnt = 1;
		}
		
		jQuery('#visual_img_ li:nth-child('+before_num+')').css('z-index','1');
		jQuery('#visual_img_ li:nth-child('+_cnt+')').css('z-index','10');
		
		//var off_src = jQuery('#visual_nav_ li:nth-child('+before_num+') a img').attr('src').replace('_on.jpg','.jpg');
		//jQuery('#visual_nav_ li:nth-child('+before_num+') a img').attr('src',off_src);
		//var on_src = jQuery('#visual_nav_ li:nth-child('+_cnt+') a img').attr('src').replace('.jpg','_on.jpg');
		//jQuery('#visual_nav_ li:nth-child('+_cnt+') a img').attr('src',on_src);
		
		jQuery('#visual_img_ li:nth-child('+_cnt+')').fadeIn(_fade,function(){
			jQuery('#visual_img_ li:nth-child('+before_num+')').hide();
			jQuery('#visual_nav_ a').unbind('click',unbindFadeImages);
			jQuery('#visual_nav_ a').click(fadeImages);
			_func_timer = setTimeout(loopFunc,_timer);
		});
		
		return false;
	}
	
	function fadeImages(e){
		clearTimeout(_func_timer);
		jQuery('#visual_nav_ a').unbind('click',fadeImages);
		jQuery('#visual_nav_ a').click(unbindFadeImages);
		var this_parent = jQuery(this).parent();
		if(jQuery("#visual_nav_ li").index(this_parent)!=(_cnt-1)){
			
			// 表示されていたものの番号
			var before_num = _cnt;
			// 何番目のサムネイルをクリックしたかを取得
			_cnt = (jQuery("#visual_nav_ li").index(this_parent) + 1);
			
			jQuery('#visual_img_ li:nth-child('+before_num+')').css('z-index','1');
			jQuery('#visual_img_ li:nth-child('+_cnt+')').css('z-index','10');
			
			//var off_src = jQuery('#visual_nav_ li:nth-child('+before_num+') a img').attr('src').replace('_on.jpg','.jpg');
			//jQuery('#visual_nav_ li:nth-child('+before_num+') a img').attr('src',off_src);
		//	var on_src = jQuery('#visual_nav_ li:nth-child('+_cnt+') a img').attr('src').replace('.jpg','_on.jpg');
			//jQuery('#visual_nav_ li:nth-child('+_cnt+') a img').attr('src',on_src);
			
			jQuery('#visual_img_ li:nth-child('+_cnt+')').fadeIn(_fade,function(){
				jQuery('#visual_img_ li:nth-child('+before_num+')').hide();
				jQuery('#visual_nav_ a').unbind('click',unbindFadeImages);
				jQuery('#visual_nav_ a').click(fadeImages);
				_func_timer = setTimeout(loopFunc,_timer);
			});
			
		}else{
			jQuery('#visual_nav_ a').unbind('click',unbindFadeImages);
			jQuery('#visual_nav_ a').click(fadeImages);
			_func_timer = setTimeout(loopFunc,_timer);
		}
		return false;
	}
	function unbindFadeImages(e){
		return false;
	}
})(jQuery);