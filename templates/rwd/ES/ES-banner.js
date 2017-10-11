$(function() {
    var $brandCarousel = $('.brand-carousel'),
        $btnCarousel = $('.btn-carousel'),
        $brandBtn = $('.brand-btn'),
        $brandBtnLi = $brandBtn.find('li');

    //�ֲ�ͼ����
    var $brandImg = $('.brand-img'),
        $brandImgLi = $brandImg.find('li');

    // ��ǰ��ʾ�����ն�ʱ��
    var step = 0,
        timer = null,
        interval = 3000; //����ÿ��ѭ��ʱ��

    //�����ťʵ���л�ͼƬ
    $brandBtn.live('click', function(event) {
        var _this = event.target;
        var index = $(_this).index();
        step = index;
        $(_this).addClass('select').siblings('li').removeClass('select');
        $brandImgLi.eq(index).fadeIn().siblings('li').fadeOut();
    });

    //���ö�ʱ��
    timer = setInterval(autoChangeImg, interval);

    function autoChangeImg() {
        step++;
        if (step >= $brandImgLi.length) {
            step = 0;
        }
        $brandBtnLi.eq(step).addClass('select').siblings('li').removeClass('select');
        $brandImgLi.eq(step).fadeIn().siblings('li').fadeOut();
    }

    //����ֹͣ�ֲ�
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