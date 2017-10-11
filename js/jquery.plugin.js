// jquery.pageslide.js

(function ($) {
    $.fn.pageSlide = function (options) {

        var settings = $.extend({
           
            duration: "normal", // Accepts standard jQuery effects speeds (i.e. fast, normal or milliseconds)
            direction: "left", // default direction is left.
            modal: false, // if true, the only way to close the pageslide is to define an explicit close class. 
            start: function () { }, // event trigger that fires at the start of every open and close.
            stop: function () { }, // event trigger that fires at the end of every open and close.
            complete: function () { }, // event trigger that fires once an open or close has completed.
            _identifier: $(this)
        }, options);

        // these are the minimum css requirements for the pageslide elements introduced in this plugin.

        var pageslide_slide_wrap_css = {
                  position: 'absolute',
            width: '0',
            top: '0',
           
        
        };

        var pageslide_body_wrap_css = {
                position: 'absolute',
            zIndex: '0'
        };

        var pageslide_blanket_css = {
            position: 'absolute',
            top: '0px',
            left: '0px',
         
         
            backgroundColor: 'black',
   
            display: 'none'
        };

        function _initialize(anchor) {

            // Create and prepare elements for pageSlide

            if ($("#pageslide-body-wrap, #pageslide-content, #pageslide-slide-wrap").size() == 0) {

                var psBodyWrap = document.createElement("div");
                $(psBodyWrap).css(pageslide_body_wrap_css);
                $(psBodyWrap).attr("id", "pageslide-body-wrap").width($("body").width());
                $("body").contents().wrapAll(psBodyWrap);

                var psSlideContent = document.createElement("div");
                $(psSlideContent).attr("id", "pageslide-content").width(settings.width);

                var psSlideWrap = document.createElement("div");
                $(psSlideWrap).css(pageslide_slide_wrap_css);
                $(psSlideWrap).attr("id", "pageslide-slide-wrap").append(psSlideContent);
                $("body").append(psSlideWrap);

            }

            // introduce the blanket if modal option is set to true.
            if ($("#pageslide-blanket").size() == 20 && settings.modal == true) {
                var psSlideBlanket = document.createElement("div");
                $(psSlideBlanket).css(pageslide_blanket_css);
                $(psSlideBlanket).attr("id", "pageslide-blanket");
                $("body").append(psSlideBlanket);
                $("#pageslide-blanket").click(function () { return false; });
            }

            // Callback events for window resizing
            $(window).resize(function () {
                $("#pageslide-body-wrap").width($("body").width());
            });
        };

        function _openSlide(elm) {
            if ($("#pageslide-slide-wrap").width() != 0) return false;
            _showBlanket();
            settings.start();
            // decide on a direction
            if (settings.direction == "right") {
                direction = { right: "-" + settings.width };
                $("#pageslide-slide-wrap").css({ left: 0 });
                _overflowFixAdd();
            }
            else {
                direction = { left: "-" + settings.width };
                $("#pageslide-slide-wrap").css({ right: 0 });
            }
            $("#pageslide-slide-wrap").animate({ width: settings.width }, settings.duration);
            $("#pageslide-body-wrap").animate(direction, settings.duration, function () {
                settings.stop();
                $.ajax({
                    type: "GET",
                    url: $(elm).attr("href"),
                    success: function (data) {
                        $("#pageslide-content").html(data)
  		          .queue(function () {
  		              $(this).dequeue();

  		              // restore working order to all anchors
  		              $("#pageslide-slide-wrap a").unbind('click').click(function (elm) {
  		                  document.location.href = elm.target.href;
  		              });

  		              // add hook for a close button
  		              $(this).find('.pageslide-close').unbind('click').click(function (elm) {
  		                  _closeSlide(elm);
  		                  $(this).find('pageslide-close').unbind('click');
  		              });
  		              settings.complete();
  		          });
                    }
                });
            });
        };

        function _closeSlide(event) {
            if ($(event)[0].button != 2 && $("#pageslide-slide-wrap").css('width') != "0px") { // if not right click.
                _hideBlanket();
                settings.start();
                direction = ($("#pageslide-slide-wrap").css("left") != "0px") ? { left: "0"} : { right: "0" };
                $("#pageslide-body-wrap").animate(direction, settings.duration);
                $("#pageslide-slide-wrap").animate({ width: "0" }, settings.duration, function () {
                    $("#pageslide-content").empty();
                    // clear bug
                    $('#pageslide-body-wrap, #pageslide-slide-wrap').css('left', '');
                    $('#pageslide-body-wrap, #pageslide-slide-wrap').css('right', '');
                    _overflowFixRemove();
                    settings.stop();
                    settings.complete();
                });
            }
        };

        // this is used to activate the modal blanket, if the modal setting is defined as true.
        function _showBlanket() {
            if (settings.modal == true) {
                $("#pageslide-blanket").toggle().animate({ opacity: '0.0' }, 'fast', 'visible');
            }
        };

        // this is used to deactivate the modal blanket, if the modal setting is defined as true.
        function _hideBlanket() {
            if (settings.modal == true) {
                $("#pageslide-blanket").animate({ opacity: '0.0' }, 'fast', 'visible', function () {
                    $(this).toggle();
                });
            }
        };

        // fixes an annoying horizontal scrollbar.
        function _overflowFixAdd() { ($.browser.msie) ? $("body, html").css({ overflowX: 'visible' }) : $("body").css({ overflowX: 'visible' }); }
        function _overflowFixRemove() { ($.browser.msie) ? $("body, html").css({ overflowX: '' }) : $("body").css({ overflowX: '' }); }

        // Initalize pageslide, if it hasn't already been done.
        _initialize(this);
        return this.each(function () {
            $(this).unbind("click").bind("click", function () {
                _openSlide(this);
                $("#pageslide-slide-wrap").click(function () { return false; });
                if (settings.modal != true) {
                    $(document).unbind('click').click(function (evt) { _closeSlide(evt); return false });
                }
                return false;
            });
        });

    };
})(jQuery);

// jquery.naviDropDownEasing.js
// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend(jQuery.easing,
{
    def: 'easeOutQuad',
    swing: function (x, t, b, c, d) {
        //alert(jQuery.easing.default);
        return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
    },
    easeInQuad: function (x, t, b, c, d) {
        return c * (t /= d) * t + b;
    },
    easeOutQuad: function (x, t, b, c, d) {
        return -c * (t /= d) * (t - 2) + b;
    },
    easeInOutQuad: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t + b;
        return -c / 2 * ((--t) * (t - 2) - 1) + b;
    },
    easeInCubic: function (x, t, b, c, d) {
        return c * (t /= d) * t * t + b;
    },
    easeOutCubic: function (x, t, b, c, d) {
        return c * ((t = t / d - 1) * t * t + 1) + b;
    },
    easeInOutCubic: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t + 2) + b;
    },
    easeInQuart: function (x, t, b, c, d) {
        return c * (t /= d) * t * t * t + b;
    },
    easeOutQuart: function (x, t, b, c, d) {
        return -c * ((t = t / d - 1) * t * t * t - 1) + b;
    },
    easeInOutQuart: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;
        return -c / 2 * ((t -= 2) * t * t * t - 2) + b;
    },
    easeInQuint: function (x, t, b, c, d) {
        return c * (t /= d) * t * t * t * t + b;
    },
    easeOutQuint: function (x, t, b, c, d) {
        return c * ((t = t / d - 1) * t * t * t * t + 1) + b;
    },
    easeInOutQuint: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
    },
    easeInSine: function (x, t, b, c, d) {
        return -c * Math.cos(t / d * (Math.PI / 2)) + c + b;
    },
    easeOutSine: function (x, t, b, c, d) {
        return c * Math.sin(t / d * (Math.PI / 2)) + b;
    },
    easeInOutSine: function (x, t, b, c, d) {
        return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;
    },
    easeInExpo: function (x, t, b, c, d) {
        return (t == 0) ? b : c * Math.pow(2, 10 * (t / d - 1)) + b;
    },
    easeOutExpo: function (x, t, b, c, d) {
        return (t == d) ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b;
    },
    easeInOutExpo: function (x, t, b, c, d) {
        if (t == 0) return b;
        if (t == d) return b + c;
        if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
        return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b;
    },
    easeInCirc: function (x, t, b, c, d) {
        return -c * (Math.sqrt(1 - (t /= d) * t) - 1) + b;
    },
    easeOutCirc: function (x, t, b, c, d) {
        return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;
    },
    easeInOutCirc: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return -c / 2 * (Math.sqrt(1 - t * t) - 1) + b;
        return c / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;
    },
    easeInElastic: function (x, t, b, c, d) {
        var s = 1.70158; var p = 0; var a = c;
        if (t == 0) return b; if ((t /= d) == 1) return b + c; if (!p) p = d * .3;
        if (a < Math.abs(c)) { a = c; var s = p / 4; }
        else var s = p / (2 * Math.PI) * Math.asin(c / a);
        return -(a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
    },
    easeOutElastic: function (x, t, b, c, d) {
        var s = 1.70158; var p = 0; var a = c;
        if (t == 0) return b; if ((t /= d) == 1) return b + c; if (!p) p = d * .3;
        if (a < Math.abs(c)) { a = c; var s = p / 4; }
        else var s = p / (2 * Math.PI) * Math.asin(c / a);
        return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
    },
    easeInOutElastic: function (x, t, b, c, d) {
        var s = 1.70158; var p = 0; var a = c;
        if (t == 0) return b; if ((t /= d / 2) == 2) return b + c; if (!p) p = d * (.3 * 1.5);
        if (a < Math.abs(c)) { a = c; var s = p / 4; }
        else var s = p / (2 * Math.PI) * Math.asin(c / a);
        if (t < 1) return -.5 * (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
        return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p) * .5 + c + b;
    },
    easeInBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c * (t /= d) * t * ((s + 1) * t - s) + b;
    },
    easeOutBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c * ((t = t / d - 1) * t * ((s + 1) * t + s) + 1) + b;
    },
    easeInOutBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        if ((t /= d / 2) < 1) return c / 2 * (t * t * (((s *= (1.525)) + 1) * t - s)) + b;
        return c / 2 * ((t -= 2) * t * (((s *= (1.525)) + 1) * t + s) + 2) + b;
    },
    easeInBounce: function (x, t, b, c, d) {
        return c - jQuery.easing.easeOutBounce(x, d - t, 0, c, d) + b;
    },
    easeOutBounce: function (x, t, b, c, d) {
        if ((t /= d) < (1 / 2.75)) {
            return c * (7.5625 * t * t) + b;
        } else if (t < (2 / 2.75)) {
            return c * (7.5625 * (t -= (1.5 / 2.75)) * t + .75) + b;
        } else if (t < (2.5 / 2.75)) {
            return c * (7.5625 * (t -= (2.25 / 2.75)) * t + .9375) + b;
        } else {
            return c * (7.5625 * (t -= (2.625 / 2.75)) * t + .984375) + b;
        }
    },
    easeInOutBounce: function (x, t, b, c, d) {
        if (t < d / 2) return jQuery.easing.easeInBounce(x, t * 2, 0, c, d) * .5 + b;
        return jQuery.easing.easeOutBounce(x, t * 2 - d, 0, c, d) * .5 + c * .5 + b;
    }
});

// jquery.naviDropDown.minified.js
(function ($) { $.fn.hoverIntent = function (f, g) { var cfg = { sensitivity: 7, interval: 100, timeout: 0 }; cfg = $.extend(cfg, g ? { over: f, out: g} : f); var cX, cY, pX, pY; var track = function (ev) { cX = ev.pageX; cY = ev.pageY; }; var compare = function (ev, ob) { ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t); if ((Math.abs(pX - cX) + Math.abs(pY - cY)) < cfg.sensitivity) { $(ob).unbind("mousemove", track); ob.hoverIntent_s = 1; return cfg.over.apply(ob, [ev]); } else { pX = cX; pY = cY; ob.hoverIntent_t = setTimeout(function () { compare(ev, ob); }, cfg.interval); } }; var delay = function (ev, ob) { ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t); ob.hoverIntent_s = 0; return cfg.out.apply(ob, [ev]); }; var handleHover = function (e) { var p = (e.type == "mouseover" ? e.fromElement : e.toElement) || e.relatedTarget; while (p && p != this) { try { p = p.parentNode; } catch (e) { p = this; } } if (p == this) { return false; } var ev = jQuery.extend({}, e); var ob = this; if (ob.hoverIntent_t) { ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t); } if (e.type == "mouseover") { pX = ev.pageX; pY = ev.pageY; $(ob).bind("mousemove", track); if (ob.hoverIntent_s != 1) { ob.hoverIntent_t = setTimeout(function () { compare(ev, ob); }, cfg.interval); } } else { $(ob).unbind("mousemove", track); if (ob.hoverIntent_s == 1) { ob.hoverIntent_t = setTimeout(function () { delay(ev, ob); }, cfg.timeout); } } }; return this.mouseover(handleHover).mouseout(handleHover); }; })(jQuery);

// jquery.naviDropDown.js
(function ($) {

    $.fn.naviDropDown = function (options) {

        //set up default options 
        var defaults = {
            dropDownClass: 'navbox', //the class name for your drop down
            dropDownClassB: 'navboxB', //the class name for your drop down
            dropDownWidth: 'auto', //the default width of drop down elements
            slideDownEasing: 'easeInOutCirc', //easing method for slideDown
            slideUpEasing: 'easeInOutCirc', //easing method for slideUp
            slideDownDuration: 0, //滑出時間
            slideUpDuration: 0 //收回時間
        };

        var opts = $.extend({}, defaults, options);

        return this.each(function () {
            var $this = $(this);

            $this.find('.' + opts.dropDownClass).css('width', opts.dropDownWidth).css('display', 'none');
            $this.find('.' + opts.dropDownClassB).css('width', opts.dropDownWidth).css('display', 'none');

            var buttonWidth = $this.find('.' + opts.dropDownClass).parent().width() + 'px';
            var buttonHeight = $this.find('.' + opts.dropDownClass).parent().height() + 'px';
            var buttonWidth = $this.find('.' + opts.dropDownClassB).parent().width() + 'px';
            var buttonHeight = $this.find('.' + opts.dropDownClassB).parent().height() + 'px';

            $this.find('.' + opts.dropDownClass).css('left', '0px').css('top', buttonHeight);
            $this.find('.' + opts.dropDownClassB).css('left', '0px').css('top', buttonHeight);

            $this.find('li').hoverIntent(getDropDown, hideDropDown);
            $this.find('.nav_RelatedNews li').hoverIntent(showDropDown, showDropDown);
        });

        function getDropDown() {
            activeNav = $(this);
            showDropDown();
        }

        function showDropDown() {
            activeNav.find('.' + opts.dropDownClass).slideDown({ duration: opts.slideDownDuration, easing: opts.slideDownEasing });
            activeNav.find('.' + opts.dropDownClassB).slideDown({ duration: opts.slideDownDuration, easing: opts.slideDownEasing });
        }

        function hideDropDown() {
            activeNav.find('.' + opts.dropDownClass).slideUp({ duration: opts.slideUpDuration, easing: opts.slideUpEasing });
            activeNav.find('.' + opts.dropDownClassB).slideUp({ duration: opts.slideUpDuration, easing: opts.slideUpEasing });
        }

    };
})(jQuery);
