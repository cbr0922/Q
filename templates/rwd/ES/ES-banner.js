$(function() {
    var $brandCarousel = $('.brand-carousel'),
        $btnCarousel = $('.btn-carousel'),
        $brandBtn = $('.brand-btn'),
        $brandBtnLi = $brandBtn.find('li');

    //轮播图变量
    var $brandImg = $('.brand-img'),
        $brandImgLi = $brandImg.find('li');

    // 当前显示索引空定时器
    var step = 0,
        timer = null,
        interval = 3000; //设置每张循环时间

    //点击按钮实现切换图片
    $brandBtn.live('click', function(event) {
        var _this = event.target;
        var index = $(_this).index();
        step = index;
        $(_this).addClass('select').siblings('li').removeClass('select');
        $brandImgLi.eq(index).fadeIn().siblings('li').fadeOut();
    });

    //设置定时器
    timer = setInterval(autoChangeImg, interval);

    function autoChangeImg() {
        step++;
        if (step >= $brandImgLi.length) {
            step = 0;
        }
        $brandBtnLi.eq(step).addClass('select').siblings('li').removeClass('select');
        $brandImgLi.eq(step).fadeIn().siblings('li').fadeOut();
    }

    //划过停止轮播
    $('.brand-btn-wrap .stoped').click(function() {
        $(this).hide();
        $('.opened').show();
        clearInterval(timer);
    });
    $('.brand-btn-wrap .opened').click(function() {
        $(this).hide();
        $('.stoped').show();
        timer = setInterval(autoChangeImg, interval);
    });
});