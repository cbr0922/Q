/*
 * jQuery Autocomplete plugin 1.1
 *
 * Copyright (c) 2009 Jorn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id: jquery.autocomplete.js 15 2009-08-22 10:30:27Z joern.zaefferer $
 */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}(';(3($){$.2e.1u({19:3(b,d){5 c=W b=="1B";d=$.1u({},$.M.1T,{Y:c?b:P,y:c?P:b,1J:c?$.M.1T.1J:10,X:d&&!d.1D?10:48},d);d.1y=d.1y||3(a){6 a};d.1v=d.1v||d.1R;6 A.I(3(){1M $.M(A,d)})},L:3(a){6 A.11("L",a)},1k:3(a){6 A.14("1k",[a])},2b:3(){6 A.14("2b")},28:3(a){6 A.14("28",[a])},24:3(){6 A.14("24")}});$.M=3(o,r){5 t={2Y:38,2S:40,2N:46,2I:9,2E:13,2B:27,2x:3I,2v:33,2p:34,2n:8};5 u=$(o).3r("19","3o").Q(r.2Q);5 p;5 m="";5 n=$.M.3c(r);5 s=0;5 k;5 h={1F:C};5 l=$.M.32(r,o,1Z,h);5 j;$.1Y.2X&&$(o.2U).11("45.19",3(){4(j){j=C;6 C}});u.11(($.1Y.2X?"43":"42")+".19",3(a){s=1;k=a.2M;3V(a.2M){O t.2Y:a.1d();4(l.N()){l.30()}w{12(0,D)}R;O t.2S:a.1d();4(l.N()){l.2D()}w{12(0,D)}R;O t.2v:a.1d();4(l.N()){l.2C()}w{12(0,D)}R;O t.2p:a.1d();4(l.N()){l.2A()}w{12(0,D)}R;O r.17&&$.1c(r.S)==","&&t.2x:O t.2I:O t.2E:4(1Z()){a.1d();j=D;6 C}R;O t.2B:l.Z();R;3J:1P(p);p=1O(12,r.1J);R}}).2t(3(){s++}).3E(3(){s=0;4(!h.1F){2r()}}).2q(3(){4(s++>1&&!l.N()){12(0,D)}}).11("1k",3(){5 c=(1r.7>1)?1r[1]:P;3 1N(q,a){5 b;4(a&&a.7){16(5 i=0;i<a.7;i++){4(a[i].L.J()==q.J()){b=a[i];R}}}4(W c=="3")c(b);w u.14("L",b&&[b.y,b.F])}$.I(15(u.K()),3(i,a){21(a,1N,1N)})}).11("2b",3(){n.1o()}).11("28",3(){$.1u(r,1r[1]);4("y"2h 1r[1])n.1e()}).11("24",3(){l.1p();u.1p();$(o.2U).1p(".19")});3 1Z(){5 e=l.2g();4(!e)6 C;5 v=e.L;m=v;4(r.17){5 b=15(u.K());4(b.7>1){5 f=r.S.7;5 c=$(o).18().1I;5 d,1H=0;$.I(b,3(i,a){1H+=a.7;4(c<=1H){d=i;6 C}1H+=f});b[d]=v;v=b.3f(r.S)}v+=r.S}u.K(v);1l();u.14("L",[e.y,e.F]);6 D}3 12(b,c){4(k==t.2N){l.Z();6}5 a=u.K();4(!c&&a==m)6;m=a;a=1m(a);4(a.7>=r.29){u.Q(r.26);4(!r.1s)a=a.J();21(a,3a,1l)}w{1q();l.Z()}};3 15(b){4(!b)6[""];4(!r.17)6[$.1c(b)];6 $.4h(b.23(r.S),3(a){6 $.1c(b).7?$.1c(a):P})}3 1m(a){4(!r.17)6 a;5 c=15(a);4(c.7==1)6 c[0];5 b=$(o).18().1I;4(b==a.7){c=15(a)}w{c=15(a.22(a.37(b),""))}6 c[c.7-1]}3 1G(q,a){4(r.1G&&(1m(u.K()).J()==q.J())&&k!=t.2n){u.K(u.K()+a.37(1m(m).7));$(o).18(m.7,m.7+a.7)}};3 2r(){1P(p);p=1O(1l,4g)};3 1l(){5 c=l.N();l.Z();1P(p);1q();4(r.36){u.1k(3(a){4(!a){4(r.17){5 b=15(u.K()).1n(0,-1);u.K(b.3f(r.S)+(b.7?r.S:""))}w{u.K("");u.14("L",P)}}})}};3 3a(q,a){4(a&&a.7&&s){1q();l.35(a,q);1G(q,a[0].F);l.20()}w{1l()}};3 21(f,d,g){4(!r.1s)f=f.J();5 e=n.31(f);4(e&&e.7){d(f,e)}w 4((W r.Y=="1B")&&(r.Y.7>0)){5 c={4f:+1M 4e()};$.I(r.2Z,3(a,b){c[a]=W b=="3"?b():b});$.4d({4c:"4b",4a:"19"+o.49,2V:r.2V,Y:r.Y,y:$.1u({q:1m(f),47:r.X},c),44:3(a){5 b=r.1A&&r.1A(a)||1A(a);n.1i(f,b);d(f,b)}})}w{l.2T();g(f)}};3 1A(c){5 d=[];5 b=c.23("\\n");16(5 i=0;i<b.7;i++){5 a=$.1c(b[i]);4(a){a=a.23("|");d[d.7]={y:a,F:a[0],L:r.1z&&r.1z(a,a[0])||a[0]}}}6 d};3 1q(){u.1h(r.26)}};$.M.1T={2Q:"41",2P:"3Z",26:"3Y",29:1,1J:3W,1s:C,1f:D,1w:C,1g:10,X:3U,36:C,2Z:{},1X:D,1R:3(a){6 a[0]},1v:P,1G:C,E:0,17:C,S:", ",1y:3(b,a){6 b.22(1M 3T("(?![^&;]+;)(?!<[^<>]*)("+a.22(/([\\^\\$\\(\\)\\[\\]\\{\\}\\*\\.\\+\\?\\|\\\\])/2K,"\\\\$1")+")(?![^<>]*>)(?![^&;]+;)","2K"),"<2J>$1</2J>")},1D:D,1E:3S};$.M.3c=3(g){5 h={};5 j=0;3 1f(s,a){4(!g.1s)s=s.J();5 i=s.2H(a);4(g.1w=="3R"){i=s.J().1k("\\\\b"+a.J())}4(i==-1)6 C;6 i==0||g.1w};3 1i(q,a){4(j>g.1g){1o()}4(!h[q]){j++}h[q]=a}3 1e(){4(!g.y)6 C;5 f={},2G=0;4(!g.Y)g.1g=1;f[""]=[];16(5 i=0,2F=g.y.7;i<2F;i++){5 c=g.y[i];c=(W c=="1B")?[c]:c;5 d=g.1v(c,i+1,g.y.7);4(d===C)1V;5 e=d.3Q(0).J();4(!f[e])f[e]=[];5 b={F:d,y:c,L:g.1z&&g.1z(c)||d};f[e].1U(b);4(2G++<g.X){f[""].1U(b)}};$.I(f,3(i,a){g.1g++;1i(i,a)})}1O(1e,25);3 1o(){h={};j=0}6{1o:1o,1i:1i,1e:1e,31:3(q){4(!g.1g||!j)6 P;4(!g.Y&&g.1w){5 a=[];16(5 k 2h h){4(k.7>0){5 c=h[k];$.I(c,3(i,x){4(1f(x.F,q)){a.1U(x)}})}}6 a}w 4(h[q]){6 h[q]}w 4(g.1f){16(5 i=q.7-1;i>=g.29;i--){5 c=h[q.3O(0,i)];4(c){5 a=[];$.I(c,3(i,x){4(1f(x.F,q)){a[a.7]=x}});6 a}}}6 P}}};$.M.32=3(e,g,f,k){5 h={H:"3N"};5 j,z=-1,y,1t="",1S=D,G,B;3 2y(){4(!1S)6;G=$("<3M/>").Z().Q(e.2P).T("3L","3K").1Q(1K.2w);B=$("<3H/>").1Q(G).3G(3(a){4(U(a).2u&&U(a).2u.3F()==\'2s\'){z=$("1L",B).1h(h.H).3D(U(a));$(U(a)).Q(h.H)}}).2q(3(a){$(U(a)).Q(h.H);f();g.2t();6 C}).3C(3(){k.1F=D}).3B(3(){k.1F=C});4(e.E>0)G.T("E",e.E);1S=C}3 U(a){5 b=a.U;3A(b&&b.3z!="2s")b=b.3y;4(!b)6[];6 b}3 V(b){j.1n(z,z+1).1h(h.H);2o(b);5 a=j.1n(z,z+1).Q(h.H);4(e.1D){5 c=0;j.1n(0,z).I(3(){c+=A.1a});4((c+a[0].1a-B.1b())>B[0].3x){B.1b(c+a[0].1a-B.3w())}w 4(c<B.1b()){B.1b(c)}}};3 2o(a){z+=a;4(z<0){z=j.1j()-1}w 4(z>=j.1j()){z=0}}3 2m(a){6 e.X&&e.X<a?e.X:a}3 2l(){B.2z();5 b=2m(y.7);16(5 i=0;i<b;i++){4(!y[i])1V;5 a=e.1R(y[i].y,i+1,b,y[i].F,1t);4(a===C)1V;5 c=$("<1L/>").3v(e.1y(a,1t)).Q(i%2==0?"3u":"3P").1Q(B)[0];$.y(c,"2k",y[i])}j=B.3t("1L");4(e.1X){j.1n(0,1).Q(h.H);z=0}4($.2e.2W)B.2W()}6{35:3(d,q){2y();y=d;1t=q;2l()},2D:3(){V(1)},30:3(){V(-1)},2C:3(){4(z!=0&&z-8<0){V(-z)}w{V(-8)}},2A:3(){4(z!=j.1j()-1&&z+8>j.1j()){V(j.1j()-1-z)}w{V(8)}},Z:3(){G&&G.Z();j&&j.1h(h.H);z=-1},N:3(){6 G&&G.3s(":N")},3q:3(){6 A.N()&&(j.2j("."+h.H)[0]||e.1X&&j[0])},20:3(){5 a=$(g).3p();G.T({E:W e.E=="1B"||e.E>0?e.E:$(g).E(),2i:a.2i+g.1a,1W:a.1W}).20();4(e.1D){B.1b(0);B.T({2L:e.1E,3n:\'3X\'});4($.1Y.3m&&W 1K.2w.3l.2L==="1x"){5 c=0;j.I(3(){c+=A.1a});5 b=c>e.1E;B.T(\'3k\',b?e.1E:c);4(!b){j.E(B.E()-2R(j.T("2O-1W"))-2R(j.T("2O-3j")))}}}},2g:3(){5 a=j&&j.2j("."+h.H).1h(h.H);6 a&&a.7&&$.y(a[0],"2k")},2T:3(){B&&B.2z()},1p:3(){G&&G.3i()}}};$.2e.18=3(b,f){4(b!==1x){6 A.I(3(){4(A.2d){5 a=A.2d();4(f===1x||b==f){a.4n("2c",b);a.3h()}w{a.4m(D);a.4l("2c",b);a.4k("2c",f);a.3h()}}w 4(A.3g){A.3g(b,f)}w 4(A.1C){A.1C=b;A.3e=f}})}5 c=A[0];4(c.2d){5 e=1K.18.4j(),3d=c.F,2a="<->",2f=e.3b.7;e.3b=2a;5 d=c.F.2H(2a);c.F=3d;A.18(d,d+2f);6{1I:d,39:d+2f}}w 4(c.1C!==1x){6{1I:c.1C,39:c.3e}}}})(4i);',62,272,'|||function|if|var|return|length|||||||||||||||||||||||||else||data|active|this|list|false|true|width|value|element|ACTIVE|each|toLowerCase|val|result|Autocompleter|visible|case|null|addClass|break|multipleSeparator|css|target|moveSelect|typeof|max|url|hide||bind|onChange||trigger|trimWords|for|multiple|selection|autocomplete|offsetHeight|scrollTop|trim|preventDefault|populate|matchSubset|cacheLength|removeClass|add|size|search|hideResultsNow|lastWord|slice|flush|unbind|stopLoading|arguments|matchCase|term|extend|formatMatch|matchContains|undefined|highlight|formatResult|parse|string|selectionStart|scroll|scrollHeight|mouseDownOnSelect|autoFill|progress|start|delay|document|li|new|findValueCallback|setTimeout|clearTimeout|appendTo|formatItem|needsInit|defaults|push|continue|left|selectFirst|browser|selectCurrent|show|request|replace|split|unautocomplete||loadingClass||setOptions|minChars|teststring|flushCache|character|createTextRange|fn|textLength|selected|in|top|filter|ac_data|fillList|limitNumberOfItems|BACKSPACE|movePosition|PAGEDOWN|click|hideResults|LI|focus|nodeName|PAGEUP|body|COMMA|init|empty|pageDown|ESC|pageUp|next|RETURN|ol|nullData|indexOf|TAB|strong|gi|maxHeight|keyCode|DEL|padding|resultsClass|inputClass|parseInt|DOWN|emptyList|form|dataType|bgiframe|opera|UP|extraParams|prev|load|Select|||display|mustMatch|substring||end|receiveData|text|Cache|orig|selectionEnd|join|setSelectionRange|select|remove|right|height|style|msie|overflow|off|offset|current|attr|is|find|ac_even|html|innerHeight|clientHeight|parentNode|tagName|while|mouseup|mousedown|index|blur|toUpperCase|mouseover|ul|188|default|absolute|position|div|ac_over|substr|ac_odd|charAt|word|180|RegExp|100|switch|400|auto|ac_loading|ac_results||ac_input|keydown|keypress|success|submit||limit|150|name|port|abort|mode|ajax|Date|timestamp|200|map|jQuery|createRange|moveEnd|moveStart|collapse|move'.split('|'),0,{})) 


/*
http://www.json.org json2.js
*/
var JSON;JSON||(JSON={});
(function(){function k(a){return a<10?"0"+a:a}function n(a){o.lastIndex=0;return o.test(a)?'"'+a.replace(o,function(c){var d=q[c];return typeof d==="string"?d:"\\u"+("0000"+c.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+a+'"'}function l(a,c){var d,f,j=g,e,b=c[a];if(b&&typeof b==="object"&&typeof b.toJSON==="function")b=b.toJSON(a);if(typeof h==="function")b=h.call(c,a,b);switch(typeof b){case "string":return n(b);case "number":return isFinite(b)?String(b):"null";case "boolean":case "null":return String(b);case "object":if(!b)return"null";
g+=m;e=[];if(Object.prototype.toString.apply(b)==="[object Array]"){f=b.length;for(a=0;a<f;a+=1)e[a]=l(a,b)||"null";c=e.length===0?"[]":g?"[\n"+g+e.join(",\n"+g)+"\n"+j+"]":"["+e.join(",")+"]";g=j;return c}if(h&&typeof h==="object"){f=h.length;for(a=0;a<f;a+=1)if(typeof h[a]==="string"){d=h[a];if(c=l(d,b))e.push(n(d)+(g?": ":":")+c)}}else for(d in b)if(Object.prototype.hasOwnProperty.call(b,d))if(c=l(d,b))e.push(n(d)+(g?": ":":")+c);c=e.length===0?"{}":g?"{\n"+g+e.join(",\n"+g)+"\n"+j+"}":"{"+e.join(",")+
"}";g=j;return c}}if(typeof Date.prototype.toJSON!=="function"){Date.prototype.toJSON=function(){return isFinite(this.valueOf())?this.getUTCFullYear()+"-"+k(this.getUTCMonth()+1)+"-"+k(this.getUTCDate())+"T"+k(this.getUTCHours())+":"+k(this.getUTCMinutes())+":"+k(this.getUTCSeconds())+"Z":null};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(){return this.valueOf()}}var p=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
o=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,g,m,q={"\u0008":"\\b","\t":"\\t","\n":"\\n","\u000c":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},h;if(typeof JSON.stringify!=="function")JSON.stringify=function(a,c,d){var f;m=g="";if(typeof d==="number")for(f=0;f<d;f+=1)m+=" ";else if(typeof d==="string")m=d;if((h=c)&&typeof c!=="function"&&(typeof c!=="object"||typeof c.length!=="number"))throw new Error("JSON.stringify");return l("",
{"":a})};if(typeof JSON.parse!=="function")JSON.parse=function(a,c){function d(f,j){var e,b,i=f[j];if(i&&typeof i==="object")for(e in i)if(Object.prototype.hasOwnProperty.call(i,e)){b=d(i,e);if(b!==undefined)i[e]=b;else delete i[e]}return c.call(f,j,i)}a=String(a);p.lastIndex=0;if(p.test(a))a=a.replace(p,function(f){return"\\u"+("0000"+f.charCodeAt(0).toString(16)).slice(-4)});if(/^[\],:{}\s]*$/.test(a.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
"]").replace(/(?:^|:|,)(?:\s*\[)+/g,""))){a=eval("("+a+")");return typeof c==="function"?d({"":a},""):a}throw new SyntaxError("JSON.parse");}})();

/*
 * jQuery UI for UTV
 *
 * Author: Rex Ho.
 * Date: 2010/03/18
 * Depends:
 *	jquery-1.4.2.js
 */
var momoj=jQuery.noConflict();
(function($) {

/*
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
*/
$.fn.cookie = function(name, value, settings) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        settings = settings || {};
        if (value === null) {
            value = '';
            settings.expires = -1;
        }
        var expires = '';
        if (settings.expires && (typeof settings.expires == 'number' || settings.expires.toUTCString)) {
            var date;
            if (typeof settings.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (settings.expires * 24 * 60 * 60 * 1000));
            } else {
                date = settings.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize settings.path and settings.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = settings.path ? '; path=' + (settings.path) : '';
        var domain = settings.domain ? '; domain=' + (settings.domain) : '';
        var secure = settings.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
}

// timer, return a timer object, so you can stop, reset again and again
$.fn.timer = function (interval, callback){
	var interval = interval || 100;
	if (!callback)
		return false;
	
	var _timer = function (interval, callback) {
		this.stop = function () {
			clearInterval(self.id);
		};
		
		this.internalCallback = function () {
			callback(self);
		};
		
		this.reset = function (val) {
			if (self.id)
				clearInterval(self.id);
			
			var val = val || 100;
			this.id = setInterval(this.internalCallback, this.interval);
		};
		
		this.interval = interval;
		this.id = setInterval(this.internalCallback, this.interval);

		var self = this;
	};
	return new _timer(interval, callback);
};

$.fn.HashTables = function(){

  var _HashTables = function(){
    this.items=new Array();
    this.keyArray=new Array();
    this.itemsCount=0;
    this.add = function(key,value){
      if(!this.containsKey(key)){
        this.items[key]=value;
        this.itemsCount++;
        this.keyArray.push(key);
      }else{
        //throw "key '"+key+"' allready exists."
        this.items[key]=value;
      }
    }
    this.get=function(key){
      if(this.containsKey(key))
        return this.items[key];
      else
        return typeof(this.items[key]);
    }
    this.remove = function(key){
      if(this.containsKey(key)){
        delete this.keyArray[key];
        delete this.items[key];
        this.itemsCount--;
      }else{
        throw "key '"+key+"' does not exists."
      }
    }
    this.containsKey= function(key){
      return typeof(this.items[key])!="undefined";
    }
    this.containsValue = function containsValue(value){
      for (var item in this.items){
        if(this.items[item]==value)
          return true;
      }
      return false;
    }
    this.contains = function(keyOrValue){
      return this.containsKey(keyOrValue) || this.containsValue(keyOrValue);
    }
    this.clear = function(){
      this.items=new Array();
      this.keyArray=new Array();
      itemsCount=0;
    }
    this.size = function(){
      return this.itemsCount;
    }
    this.isEmpty = function(){
      return this.size()==0;
    } 
    this.getItems = function(key){
      return this.items[key];
    }
  }
  return new _HashTables();
}

// tab change
$.fn.TabDelay = function(settings){
  var container=$(this);
  // if container is array, scan it
  if ( container.length >1 ) {
    container.each(function(){
      $(this).TabDelay(settings);
    });
    return false;
  }
  var Timer=null;
  var _TabIndex=0;  
  var _defaultSettings = {        
    StartTab:1,       // deafult show tab 1
    RollerSpeed :0 ,  // roller speed seconds
    SetTab:0,         // for set some tab
    Start:0,          // start roller tab
    Stop:0            // stop roller tab
  }; 
  var _settings = $.extend(_defaultSettings, settings);
  
  var _TabDelay=function(){
    
    if (_settings.SetTab>0){
      _settings.SetTab--;
      _changeTab(container.attr('id'),_settings.SetTab);
    } 
    if (_settings.Start>0) {
      container.data('Roller',1);
      _StartRoller();
    } 
    if (_settings.Stop>0) {
      container.data('Roller',0);
      _StopRoller();
    }
    
    if ( !container.data('TabDelayInit')) {
      var $TabMenuList=$('div.TabMenu ul li',this);
      // bind every menu events
      $TabMenuList.each(function(i){
        // give tabid, tabindex to show tab when mouse over
        $(this)
          .bind('mouseover',{tabid:container.attr('id'),index:i},_setTab)
          .bind('mouseout',{tabid:container.attr('id'),index:i},_outTab)
        ;
      });
      
      var $TabContentList=$('div.TabContentD',container);
      $TabContentList.each(function(i){
        // in ECM, mayBe has DEL class name, 
        // so must remove
        if ($(this).hasClass('DEL'))
          $(this).removeClass('TabContentD');
          
        // give tabid, tabindex to show tab when mouse over
        $(this)
          .bind('mouseover',{tabid:container.attr('id'),index:i},_setTab)
          .bind('mouseout',{tabid:container.attr('id'),index:i},_outTab)
        ;
      });
      $TabContentList=$('div.TabContentD',container);
      // show tab --
      // if _settings.StartTab = 0, that mean random show start tab
      
      if ( _settings.StartTab == 0) {
        _settings.StartTab = Math.floor(Math.random()*$TabContentList.length)+1;
        //alert(container.attr('id')+':'+$TabContentList.length);
      }
      _settings.StartTab--;
      // show default tab when onload 
      _changeTab(container.attr('id'),_settings.StartTab);

      // roller tab if ( _settings.RollerSpeed > 0 )
      if ( _settings.RollerSpeed > 0 ){
        _RollerTab();
      }
      /*
      $('img',container).each(function(){
        var _img=$(this);
        var _src=_img.attr('src')+'?r='+Math.random();
        _img.attr('src',_src);
      });
      */
      container.data('TabDelayInit',1);
    }
  }
  
  var _setTab = function(e){
    _changeTab(e.data.tabid,e.data.index);
    //if (Timer) Timer.stop();
    _StopRoller();
  }

  var _outTab = function(e){
    //if (Timer) Timer.reset();
    _StartRoller();
  }
  
  var _StartRoller = function(){
    if (container.data('Roller')==0) return;
    var _Timer=jQuery('body').data('MomoTabTimer_'+container.attr('id'));
    if (_Timer) _Timer.reset();
  }

  var _StopRoller = function(){
    var _Timer=jQuery('body').data('MomoTabTimer_'+container.attr('id'));
    if (_Timer) _Timer.stop();
  }  
  
  var _changeTab = function(tabid,tabindex){
    var MainTab=$('#'+tabid);//get MiainTab
    // get TabContentD list to change class
    var $MenuList=$('div.TabMenu ul li',MainTab);
    $MenuList.each(function(i){
      var oa=$('a',this);
      $(this).removeClass('First-Element');
      if (oa) $(oa).addClass('First-Element');  
      
      if ( i===tabindex) { 
        $(this).addClass('selected');
        if (oa) $(oa).addClass('selected');
      }
      else {
        $(this).removeClass('selected');
        if (oa) $(oa).removeClass('selected');        
      }
    });
    // get TabContentD list to change class
    var $ContentList=$('div.TabContentD',MainTab);
    $ContentList.each(function(i){
      $(this).removeClass('First-Element');
      if ( i===tabindex) $(this).addClass('selected');
      else $(this).removeClass('selected');
    });

    // record tabindex where roller staring use
    _TabIndex=tabindex;    
  }

  // roller tab
  var _RollerTab = function(){
    var _TabLen=$('div.TabContentD',container).length;
    var _RLS=_settings.RollerSpeed*1000;
    
    // if the container has timer, stop it, and destory it;
    var _oldTimerObj=jQuery('body').data('MomoTabTimer_'+container.attr('id'));
    if (_oldTimerObj) {
      _oldTimerObj.stop();
    }
    
    Timer=container.timer(_RLS,function() {
      _TabIndex++;
      if (_TabIndex >= _TabLen) _TabIndex=0;
      _changeTab(container.attr('id'),_TabIndex);
    });

    jQuery('body').data('MomoTabTimer_'+container.attr('id'),Timer);
    
  }  
  
  return this.each(_TabDelay);
};

// roller v h
$.fn.Roller = function(settings){
  var container=$(this);
  // if container is array, scan it
  if ( container.length >1 ) {
    container.each(function(){
      $(this).Roller(settings);
    });
    return false;
  }
  var Timer=null;
  var _defaultSettings = {        
    Pos : 12,         // how px move per time
    Delay : 0,        // pause sec per roller
    Speed : 200,      // roller speed
    PausePx: 0,      // move this px, and delay $Delay secs
    Direction: 'V',   // V:vertical or H:horizontal
    RotateWay: 'P'   // Positive: up or left, Negative:down or right
  };    
  var _settings = $.extend(_defaultSettings, settings);
  _settings.Delay *= 1000;

  
  container.data('Roller',1);
  var _Content1=null;
  if ( _settings.Direction == 'V' ){
    _Content1=container.children('.TabContent');
  }else{
    _Content1=$('.TabContent > .TabContentD',container);
    //var _ddw=$('dl>dd',_Content1).length*$('dl>dd',_Content1).innerWidth();
    var _ddw=0;
    $('dl>dd',_Content1).each(function(){
      _ddw+=$(this).innerWidth();
    });
    _Content1.css({
      position: 'relative',
      width: _ddw+'px',
      float: 'left'
    });
    $('.TabContent',container).css({width:_ddw*2+100});
  }
  
  //var _defaultHeight=parseInt(_Content1.css('height'));
  var _Content2=_Content1.clone().appendTo(_Content1.parent());
  setTimeout(function(){
    $('img',_Content2).each(function(){
      var img=$(this);
      if (img.attr('src').indexOf('/ecm/img/cmm/blank.png')>-1){
        img.attr('src',img.attr('org'));
      }
    });
  },500);
  
  var _moveAttr='top';
  var _moveWay=-1;
  var _changeWay=-1;
  var _boxHW=0;
  if ( _settings.Direction == 'H' ){
    _moveAttr='left';
    _boxHW=parseInt(_Content1.width());
  } else {
    _boxHW=parseInt(_Content1.height());
  }
  if ( _settings.RotateWay == 'N' ){
    _moveWay=1;
    _changeWay=1;
  }
  
  var _Content2DP=0;
  // set _content2 default position by _settings.Direction, and _settings.RotateWay
  if (_settings.RotateWay == 'N') {
    _Content2DP=-2*_boxHW;
  }
  _Content1.css(_moveAttr,'0px');
  _Content2.css(_moveAttr,_Content2DP+'px');

  // if there is change direction
  var dirP=$('.ScrollP',container.parent().parent());
  var dirN=$('.ScrollN',container.parent().parent());
  
  var _Roller = function() {
    container
      .bind('mouseover',_mover)
      .bind('mouseout',_mout);
    ;
    
    if ( $(dirP).hasClass('ScrollP'))
      dirP.bind('click',{dir:'P'},_dclick)
          .bind('mouseover',{dir:'P'},_dmover)
          .bind('mouseout',{dir:'P'},_dmout);

    if ( $(dirN).hasClass('ScrollN'))
      dirN.bind('click',{dir:'N'},_dclick)
          .bind('mouseover',{dir:'N'},_dmover)
          .bind('mouseout',{dir:'N'},_dmout);
      
    var _C1Pos=0;
    var _C2Pos=0;
    var _mvpx=0;

    var _oldTimerObj=jQuery('body').data('MomoRollTimer_'+container.attr('id'));
    if (_oldTimerObj) {
      _oldTimerObj.stop();
    }
    
    Timer=container.timer(_settings.Speed,function(){
      if(container.data('Roller')==0) return;
      // if has pause and delay > 0, when change way, 
      // _changeWay, _moveWay will not be the same
      if ( _changeWay != _moveWay ){
        if (_mvpx >0) _mvpx = _settings.PausePx - _mvpx;
        _moveWay=_changeWay;
        if ( (_C1Pos*_moveWay) ===_boxHW) {
          _C1Pos=-1*_boxHW*_moveWay;
          _Content1.css(_moveAttr,_C1Pos+'px');
        }
        if (_C1Pos===0){
          _Content2.css(_moveAttr,_Content2DP+'px');
        }        
      }

      // move block
      _C1Pos=parseInt(_Content1.css(_moveAttr));
      _C1Pos += _settings.Pos*_moveWay;
      _C2Pos=parseInt(_Content2.css(_moveAttr));
      _C2Pos += _settings.Pos*_moveWay;
      _Content1.css(_moveAttr,_C1Pos+'px');
      _Content2.css(_moveAttr,_C2Pos+'px');
      _mvpx += _settings.Pos;
      
      if ( (_C1Pos*_moveWay) ===_boxHW) {
        _C1Pos=-1*_boxHW*_moveWay;
        _Content1.css(_moveAttr,_C1Pos+'px');
      }
      if (_C1Pos===0){
        _Content2.css(_moveAttr,_Content2DP+'px');
      }
      // when roller need pause, _settings.Delay > 0
      if ( _settings.Delay >0 &&  _mvpx === _settings.PausePx ) {
        Timer.stop();
        _moveWay=_changeWay;
        _mvpx=0;
        if(container.data('Roller')==0) return;
        var tid=setTimeout(
          function(){
            //Timer.reset();
            clearTimeout(tid)
            if(container.data('Roller')==0) return;
            Timer.reset();
          }
          ,_settings.Delay);
      }
    });
  
    jQuery('body').data('MomoRollTimer_'+container.attr('id'),Timer);
    //alert(container.attr('id'));
  }
  
  var _mover = function(){
    var _Timer=jQuery('body').data('MomoRollTimer_'+container.attr('id'));
    if(_Timer){
      container.data('Roller',0);
      _Timer.stop();
    }
  }
  
  var _mout = function(){
    var _Timer=jQuery('body').data('MomoRollTimer_'+container.attr('id'));
    if(_Timer){
      container.data('Roller',1);
      _Timer.reset();
    }
  }

  var _dmover = function(e){
    if ( e.data.dir == 'P' ){
      $('>:first-child',dirP).addClass('O');
      $('>:last-child',dirP).removeClass('O');
    } else {
      $('>:first-child',dirN).addClass('O');
      $('>:last-child',dirN).removeClass('O');
    }    
  }

  var _dmout = function(e){
    if ( e.data.dir == 'P' ){
      $('>:first-child',dirP).removeClass('O');
      $('>:last-child',dirP).addClass('O');
    } else {
      $('>:first-child',dirN).removeClass('O');
      $('>:last-child',dirN).addClass('O');
    }    
  }  

  // change roller way  
  // no pause delay, change way right now.
  var _dclick = function(e){
    if ( e.data.dir == 'P' ){
      if (_settings.Delay>0) _changeWay=-1;
      else _changeWay=_moveWay=-1;
      _Content2DP=0;
    } else {
      if (_settings.Delay>0) _changeWay=1;
      else _changeWay=_moveWay=1;
      _Content2DP=-2*_boxHW;
    }
  }  
  
  return this.each(_Roller);
};

// nav menu
$.fn.DDMenu = function(settings){
  var container=$(this);
  if ( container.length >1 ) {
    container.each(function(){
      $(this).DDMenu(settings);
    });
    return false;
  } 
  var _defaultSettings = {        
    STab:0,
    delay: 100,
    maxLen: 100,
    maxWidth:981,
    subMenu:''
  };
  var _settings = $.extend(_defaultSettings, settings);
  //alert('URL:'+document.location);
  
  // 如果是館首頁，則由 href 決定 FTOOTH,l_code, 如果是其它頁，則由 cookie 決定
  var _l_code='';
  var _FTOOTH='';
  if (document.location.href.match('LgrpCategory.jsp')){
    _FTOOTH=get_form(document.location.href,'FTOOTH');
    _l_code=get_form(document.location.href,'l_code');
    if( _FTOOTH==''){
      _FTOOTH=$().cookie('FTOOTH');
    }
  }else{
    _FTOOTH=$().cookie('FTOOTH');
    _l_code=$().cookie('l_code');
  }
  if(_FTOOTH!='' && (document.location.href.indexOf('/goods/')>-1||document.location.href.indexOf('/category/')>-1)){
    // 決定館別
    var _tooth_code='C'+_FTOOTH+'00000000';
    var _tab=0;
    $('>li.UTVMUHead',container).each(function(){
      _tab++;
      // 館主頁麵包屑
      if(_l_code==_FTOOTH+'99900000' && $(this).attr('id')==_tooth_code ){
          var _toothName=$(">a",this).html();
          $('#NavCategoryName').html(' &gt; '+_toothName);
      }      
      if($(this).attr('id')==_tooth_code){
        _settings.subMenu=$('.UTVMUSub',this);
        _settings.STab=_tab;
        // insert subMenu Main.js not
        $('#bt_0_043_01').addClass('C'+_FTOOTH);
        $('#bt_0_043_01 .MainSubMenu').addClass('C'+_FTOOTH);
        
        /*
        從直向 menu 產出橫向 menu
        直向會因為筆數問題而換行，換行的技巧是用 css float:left 的方式，
        所以第二排以後是穿插在第一排之後的順序，如果是兩排直的，在 li 中的第一、第二
        分別是第一排第一個第二排第一個，在 li 的第三、第四個是在第一排跟第二排的第二個
        以此類推！因為橫向 Menu 是直接取出li來置入，故看起來的順序會跟直向的第一排第二排
        的順序不太一樣！所以要改變取出方式！2010.10.06 by MHHO
        */
        var _subMainMenu=$('#bt_0_043_01 .MainSubMenu .Menu');
        var _liA=$('li',_settings.subMenu); //館元素
        var _liLen=_liA.length; // 館數量
        var _liCols=Math.floor(_liLen/_settings.maxLen); // 根據 maxLen 算出有幾欄
        var _liMod=_liLen%_settings.maxLen;
        if(_liMod>0){
          _liCols++; // 如果有餘數 就要多一欄
        }else{
          _liMod=_settings.maxLen;
        }
        var _liANew=new Array();
        var _liRows=0;
        var _liEl=0;
        for(var j=0;j<_settings.maxLen;j++){ // 進行
          _liRows++;
          for(var i=0;i<_liCols;i++){
            if(_liRows==1) _liANew[i]=new Array();
            _liANew[i][_liANew[i].length]=_liA[_liEl];
            _liEl++;
          }
          if(_liRows==_liMod) _liCols--;
        }
        var _liANewS=new Array();
        for(var i=0;i<_liANew.length;i++){
          for(var j=0;j<_liANew[i].length;j++){
            _liANewS[_liANewS.length]=_liANew[i][j];
          }
        }
        //$('li',_settings.subMenu).each(function(){
        $(_liANewS).each(function(){
          var _li=$(this);
          var _a=$('a',_li);
          var _span=$('span',_li);
          var _imgContent='';
          var _img=$('img',_li);
          if(_img.length==1){
            _imgContent='<img src="'+_img.attr('src')+'" class="'+_img.attr('class')+'">';
          }
          
          var _html=_li.html().replace('amp;','');
          var _l_code_sub='l_code='+_l_code;
          if (_html.indexOf(_l_code_sub)>-1){
            _subMainMenu.append('<li><a class="main" href="'+_a.attr('href')+'"><span>'+_span.text()+'</span>'+_imgContent+'</a></li>');
            var _cateName=_span.text();
            $('#NavCategoryName').html(' &gt; '+_cateName);
            $('#NavCategoryName').addClass('NavT'+_FTOOTH);
          }else{
            _subMainMenu.append('<li><a href="'+_a.attr('href')+'"><span>'+_span.text()+'</span>'+_imgContent+'</a></li>');
          }
         
          var _liChild=$('li:last-child a',_subMainMenu);
          _liChild.css('width',$('img',_liChild).width()+$('span',_liChild).outerWidth()+2);

        });
        $('#bt_0_043_01 .MainSubMenu').DDMenuSub();
        
      }
    });
  }
  
  var _MenuF = function(){
    var tabindex=0;
    //alert($('>li',container).length);
    $('>li.UTVMUHead',container).each(function(){
      tabindex++;
      
      if (_settings.STab === tabindex){
        $(this).addClass('BGH');
        $(this).addClass('BGHNow');
        //return true;
      }

      // cal sub ul width
      $('> ul',$(this)).each(function(){
        var _liA=$(this).children();
        var _liLen=_liA.length;
        // adj ul width for float:right
        var _addWidth=Math.floor(_liLen/_settings.maxLen);
        var _mode=_liLen%_settings.maxLen;
        if (_mode>0){
          _addWidth++;
        }else{
          _mode=_settings.maxLen;
        }
        var _width=$(this).width();
        
        $(this).css('width',_width+(_width-12)*(_addWidth-1)+'px');
        //var _tab=_addWidth+1;
        // add RightLi class
        var _liEl=0;
        var _liRows=0;
        for(var j=0;j<_settings.maxLen;j++){ // 進行
          _liRows++;
          for(var i=0;i<_addWidth;i++){
            var _li=$(_liA[_liEl]);
            //Right Li
            if(i>0) _li.addClass('RightLi'); 
            // LastRightLi
            if(i==(_addWidth)-1) _li.addClass('RightLastLi'); 
            if(_liRows==_settings.maxLen) _li.addClass('RowLastLi'); 
            _liEl++;
          }
          if(_liRows==_mode){
            $(_liA[_liEl-1]).addClass('RowLastLi');
            _addWidth--;
          }
        }        
      });
      /*
      計算直向 Menu xy pos
      2010.10.06 改版
      當直向 menu 的 X位置+ 自己本身的寬度抄
      */
      var x=0;y=0;
      var POS=$(this).position();
      x=POS.left;
      y=POS.top+$(this).height();
      $(this).hover(
          function(){
            $(this).addClass('BGH');
            $(this).addClass($(this).attr('id'));
            var _ul=$('> ul',$(this));
            _ul.addClass('DMover').css({left:x,top:y});
            var _width=_ul.css('width');
            _width=_width.replace('px','');
            _width=_width-0;
            var _xpos=0;
            if(_width+x > _settings.maxWidth){
              _xpos=_settings.maxWidth-_width;
              _ul.css('left',_xpos);
            }
          },
          function(){
            if(!$(this).hasClass('BGHNow'))
              $(this).removeClass('BGH');
              $(this).removeClass($(this).attr('id'));
            $('> ul',$(this)).removeClass('DMover');
          }
        );
      $('>ul>li',$(this)).hover(
        function(){
          $(this).addClass('BGH')
          //$(this).parent().addeClass('DMover');
        },
        function(){
          $(this).removeClass('BGH');
          //$(this).parent().removeClass('DMover');
        }
      );    
    });
    
    
  }
  return this.each(_MenuF);
};

// muti line nav menu
$.fn.DDMenuSub = function(settings){
  var container=$(this);
  if ( container.length >1 ) {
    container.each(function(){
      $(this).DDMenuSub(settings);
    });
    return false;
  }
  var _defaultSettings = {        
  };
  var _settings = $.extend(_defaultSettings, settings);
  // get li start px
  var _leftPX=$('ul.Menu',container).css('margin-left');
  _leftPX=_leftPX.replace('px','');

  var _line=0;
  $('ul.Menu li',container).each(function(){
    var _li=$(this);
    if( _li.position().left == _leftPX || _li.position().left==0){
      $('a',_li).addClass('first');
      _line++;
    }
    $('a',_li).hover(
      function(){$(this).addClass('selected')},
      function(){$(this).removeClass('selected')}
    );
  });
  
  if(_line>=1){
    // get div height
    var _height=container.css('height');
    _height=_height.replace('px','');
    _height=_height*_line;
    container.css('height',_height);
    $('#bt_0_043_01').css('height',_height);
    var _b107=$('#bt_0_layout_b107').css('height').replace('px','');
    _b107-=0;_b107+=_height;
    $('#bt_0_layout_b107').css('height',_b107);
    var _top4=$('#Top4').css('height').replace('px','');
    _top4-=0;_top4+=_height;
    $('#Top4').css('height',_top4);
  }
}

$.fn.showLPIC = function(settings){
  var container=$(this);
  // if container is array, scan it
  if ( container.length >1 ) {
    container.each(function(){
      $(this).showLPIC(settings);
    });
    return false;
  }
  var _defaultSettings = {
    PicDIV:"showLPICpop",
    Width:"110px",
    Height:"110px"
  };    
  var _settings = $.extend(_defaultSettings, settings);
  var _picDIV=$("#"+_settings.PicDIV);
  if(_picDIV.attr('id')===undefined){
    // show img div not exists,create it
    var _div='<div id="'+_settings.PicDIV+'" style="position:absolute;display:none;z-index:10000;width:'+_settings.Width+';height:'+_settings.Height+';border=0px;"><img src="/ecm/img/cmm/blank.png" style="width:'+_settings.Width+';height:'+_settings.Height+';border=0px;"/></div>';
    jQuery('body').append(_div);
  }
  _picDIV=$("#"+_settings.PicDIV);
  //alert('show LPIC:'+_picDIV.attr('id'));
  var _ShowLPIC=function(){
    container.hover(
      function(){
        var _pic=$("img",_picDIV);
        var _img=$(this).attr('src').replace('_X','_L');
        _img=_img.replace('_M','_L');
        _pic.attr('src',_img);
        _picDIV.show();
      },
      function(){
        _picDIV.hide();
        
      }
    )
    .mousemove(function(event){
      if (event.pageX===undefined) {
        var doc = document.documentElement, body = document.body;
        event.pageX=event.clientX+(doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
        event.pageY=event.clientY+(doc && doc.scrollTop  || body && body.scrollTop  || 0) - (doc && doc.clientTop  || body && body.clientTop  || 0);
      }
      var _x=event.pageX+10;
      var _y=event.pageY+10;
      _x=_x+"px";
      _y=_y+"px";
      _picDIV.css({left:_x,top:_y});
    });
  }
  
  return this.each(_ShowLPIC);
  
}

// adj BT css 
// usage: $().btCSS({newline:'mm-new-line-5,5',lastline:'mm-last-line,5',adjbt:1})
// newline: mm-new-line-5(class name for lastest elements of every row ),5(elements for per line)
// lastline: mm-last-line(calss name for lastest line ),5(elements for per line)
$.fn.btCSS=function(settings){
  var container=$(this);
  // if container is array, scan it
  if ( container.length >1 ) {
    container.each(function(){
      $(this).btCSS(settings);
    });
    return false;
  }  
  var _defaultSettings = {        
    newline: 'undefined',
    lastline: 'undefined',
    lastitem: 'undefined'
  };
  var _settings = $.extend(_defaultSettings, settings);
  
  var _btcss = function(){
    // init something
    if (!container.data('BTCSSInit')){
      container.data('newline',_settings.newline);
      container.data('lastline',_settings.lastline);
      container.data('lastitem',_settings.lastitem);
      container.data('BTCSSInit',1);
    }
    if (_settings.newline!='undefined'){
      container.data('newline',_settings.newline);
    }    
    if (_settings.lastline!='undefined'){
      container.data('lastline',_settings.lastline);
    }
    if (_settings.lastitem!='undefined'){
      container.data('lastitem',_settings.lastitem);
    }  
    // do adjbt
    if (_settings.adjbt){
      //new line
      if (container.data('newline') != 'undefined'){
        var _Anewline=container.data('newline').split(',');
        
        var i=0;
        container.children().each(function(){
          i++;
          if (i%_Anewline[1] ==0)
            $(this).addClass(_Anewline[0]);
          else
            $(this).removeClass(_Anewline[0]);
          
        })
      }
      
      //last line
      if (container.data('lastline') != 'undefined'){
        var _lastline=0;
        var _line=0
        _line=parseInt(container.children().length % container.data('lastline').split(',')[1]);
        _lastline=parseInt(container.children().length/container.data('lastline').split(',')[1]);
        if (_line>0)
          _lastline++;
        _line=1;
        var _Alastline=container.data('lastline').split(',');
        var i=0;
        container.children().each(function(){
          i++;
          if (_line==_lastline)
            $(this).addClass(_Alastline[0]);
          else
            $(this).removeClass(_Alastline[0]);
          if (i%_Alastline[1] ==0)
            _line++;
        })        
      }
      
      // last item
      if(container.data('lastitem') != 'undefined'){
        var i=0;
        var _len=container.children().length;
        container.children().each(function(){
          i++;
          if(i==_len){
            $(this).addClass(container.data('lastitem'));
          }else{
            $(this).removeClass(container.data('lastitem'));
          }
        });
      }
    }
  }
  return this.each(_btcss);
}

// first for 3c LCategory
$.fn.ShowPrdPrc=function(settings){
  var container=$(this);
  // if container is array, scan it
  if ( container.length >1 ) {
    container.each(function(){
      $(this).ShowPrdPrc(settings);
    });
    return false;
  } 
  var _defaultSettings = {        
  };
  var _settings = $.extend(_defaultSettings, settings);
  
  var _ShowPrdPrc=function(){
    
    // init
    if (!container.data('ShowPrdPrcInit')){
    //alert('init hi:'+container.attr('class'));
      var _b=jQuery('body');
      // for div animate effect 0 -> div.width
      _b.data('SPDPwidth','0');
      _b.data('SPDPheight','0');
      // record last show div, if the next is show, last hidden now
      // if SPDP1 = '', setTimeout(,1000) will fail
      _b.data('SPDP1','');
      
      container
        .hover(function(){
          var _classA=container.attr('class').split(' ');
          // get class name has SDPD...... the product detail price
          for( var i=0;i<_classA.length;i++){
            // get class name has SDPD...... the product detail price
            if (_classA[i].match(/^SPDP/)){
              // record the SPDP name in container
              container.data('SPDP',_classA[i]);
              var _SPDP=$('#'+container.data('SPDP'));
              // store self width, and height
              container.data('height',_SPDP.css('height'));
              
              container.data('width',_SPDP.css('width'));
              
              //SPDP show and hidden
              $('#'+container.data('SPDP'))
                .show()
                .hover(
                  function(){
                    // disable setTimeout hidden
                    _b.data('SPDP1','');
                  },
                  function(){
                    //alert($(this).attr('id'));
                    $(this).hide();
                  })
                ;
              break;
            }
          }

          if (_b.data('SPDP1') !=''){
            $('#'+_b.data('SPDP1')).hide();
          }
          // record last show div name
          _b.data('SPDP1',container.data('SPDP'));
          
        },function(){
          setTimeout("if (jQuery('body').data('SPDP1') == '"+container.data('SPDP')+"'){jQuery('#"+container.data('SPDP')+"').hide();}",1000);
        })
        .mousemove(function(event){
          if (event.pageX===undefined) {
            var doc = document.documentElement, body = document.body;
            event.pageX=event.clientX+(doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
            event.pageY=event.clientY+(doc && doc.scrollTop  || body && body.scrollTop  || 0) - (doc && doc.clientTop  || body && body.clientTop  || 0);
          }
          var _x=event.pageX+10;
          var _y=event.pageY-10;
          _x=_x+"px";
          _y=_y+"px";
          $('#'+container.data('SPDP')).css({left:_x,top:_y});
        });        
      ;
      container.data('ShowPrdPrcInit',1);
    }
    
  }
  return this.each(_ShowPrdPrc);
}

$.fn.ShowPrdPrc1=function(settings){
  var container=$(this);
  // if container is array, scan it
  if ( container.length >1 ) {
    container.each(function(){
      $(this).ShowPrdPrc(settings);
    });
    return false;
  } 
  var _defaultSettings = {        
  };
  var _settings = $.extend(_defaultSettings, settings);
  
  var _ShowPrdPrc=function(){
    
    // init
    if (!container.data('ShowPrdPrcInit')){
    //alert('init hi:'+container.attr('class'));
      var _b=jQuery('body');
      // for div animate effect 0 -> div.width
      _b.data('SPDPwidth','0');
      _b.data('SPDPheight','0');
      // record last show div, if the next is show, last hidden now
      // if SPDP1 = '', setTimeout(,1000) will fail
      _b.data('SPDP1','');
      
      container
        .hover(function(){
          var _classA=container.attr('class').split(' ');
          
          // get class name has SDPD...... the product detail price
          for( var i=0;i<_classA.length;i++){
            // get class name has SDPD...... the product detail price
            if (_classA[i].match(/^SPDP/)){
              // record the SPDP name in container
              container.data('SPDP',_classA[i]);
              var _SPDP=$('#'+container.data('SPDP'));
              // store self width, and height
              container.data('height',_SPDP.css('height'));
              
              container.data('width',_SPDP.css('width'));
              
              //SPDP show and hidden
              $('#'+container.data('SPDP'))
              /*
                .css({width:_b.data('SPDPwidth'),height:_b.data('SPDPheight')})
                .animate({
                   width:container.data('width'),
                   height:container.data('height')},
                  'fast',
                  function(){
                    _b.data('SPDPwidth',container.data('width'));
                    _b.data('SPDPheight',container.data('height'));
                  }
                )
                */
                .show()
                .hover(
                  function(){
                    // disable setTimeout hidden
                    _b.data('SPDP1','');
                  },
                  function(){
                    //alert($(this).attr('id'));
                    $(this).hide();
                    _b.data('SPDPwidth','0');
                    _b.data('SPDPheight','0');
                  })
                ;
              break;
            }
          }

          if (_b.data('SPDP1') !=''){
            $('#'+_b.data('SPDP1')).hide();
          }
          // record last show div name
          _b.data('SPDP1',container.data('SPDP'));
          
        },function(){
          setTimeout("if (jQuery('body').data('SPDP1') == '"+container.data('SPDP')+"'){jQuery('#"+container.data('SPDP')+"').hide();jQuery('body').data('SPDPwidth','0');jQuery('body').data('SPDPheight','0');}",1000);
        })
        .mousemove(function(event){
          if (event.pageX===undefined) {
            var doc = document.documentElement, body = document.body;
            event.pageX=event.clientX+(doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
            event.pageY=event.clientY+(doc && doc.scrollTop  || body && body.scrollTop  || 0) - (doc && doc.clientTop  || body && body.clientTop  || 0);
          }
          var _x=event.pageX+10;
          var _y=event.pageY-10;
          _x=_x+"px";
          _y=_y+"px";
          $('#'+container.data('SPDP')).css({left:_x,top:_y});
        });        
      ;
      container.data('ShowPrdPrcInit',1);
    }
    
  }
  return this.each(_ShowPrdPrc);
}

// random show items 
$.fn.BTShowR = function(settings){
  var container=$(this);
  // if container is array, scan it
  if ( container.length >1 ) {
    container.each(function(){
      $(this).BTShowR(settings);
    });
    return false;
  }
  var _defaultSettings = {        
    SRCNT:0,
    LastEl:''
  }; 
  var _settings = $.extend(_defaultSettings, settings);
  

  var _BTShowR=function(){
    if(_settings.SRCNT<=0){
      return;
    }
    var _ChildList=$('.BTSRC',container).children();
    var _DelCNT=0;
    var _d=0;
    var _DelA=new Array();
    _ChildList.each(function(){
      _d++;
      if($(this).hasClass('DEL'))
        _DelA[_DelA.length]=_d-1;

    });

    _DelCNT=_DelA.length;
    if (_settings.SRCNT>=_ChildList.length-_DelCNT){
      return;
    }
    //Math.floor(Math.random()*$TabMenuList.length)
    //Random show items
    var _HideA=new Array();
    // random get hide index
    for(var i=0;i<_ChildList.length-_settings.SRCNT-_DelCNT;i++){
      while(1){
        var _HideIndex=Math.floor(Math.random()*_ChildList.length);
        for(var j=0;j<_HideA.length;j++){
          if (_HideA[j]==_HideIndex){
            _HideIndex=-1;
          }
        }
        for(var j=0;j<_DelA.length;j++){
          if (_DelA[j]==_HideIndex){
            _HideIndex=-1;
          }
        }        
        if (_HideIndex>-1){
          _HideA[_HideA.length]=_HideIndex;
          break;
        }
      }
    }
    // hide the items
    var _show=0;
    for(var i=0;i<_ChildList.length;i++){
      var _hide=0;
      for(var j=0;j<_HideA.length;j++){
        if (i==_HideA[j]){
          _hide=1;
          break;
        }
      }
      if(_hide){
        $(_ChildList[i]).hide();
      }else{
        _show++;
        if (_show==_settings.SRCNT && _settings.LastEl !='')
          $(_ChildList[i]).addClass(_settings.LastEl);
      }
        //$(_ChildList[i]).remove();
      //else
      //  $(_ChildList[i]).show();
    }
  }
  
  return this.each(_BTShowR);
}

$.fn.LazyImg = function(settings){
  var container=$(this);
  // if container is array, scan it
  if ( container.length >1 ) {
    container.each(function(){
      $(this).LazyImg(settings);
    });
    return false;
  }
  var _defaultSettings = {        
  }; 
  var _settings = $.extend(_defaultSettings, settings);
  var imgs=0;
  $('img',container).each(function(){
    var img=momoj(this);
    if (img.attr('src').indexOf('/ecm/img/cmm/blank.png')>-1){
      imgs++;
      img.attr('src',img.attr('org'));
    }  
  });
}

// for keyword search
$.fn.KeywordSearch = function(settings){
  var container=$(this);
  // if container is array, scan it
  if ( container.length >1 ) {
    container.each(function(){
      $(this).KeywordSearch(settings);
    });
    return false;
  }
  var _defaultSettings = {
    URL:"/keywords.php"
  };
  var _settings = $.extend(_defaultSettings, settings);
  
  var _kwSearch = function(){
    if (!container.data('ShowPrdPrcInit')){
      // record init txt word
      container.data('defaultWord',container.val());
      container
        .bind('focus',function(){
          if(container.val()==container.data('defaultWord')) container.val('');
        })
        .bind('blur',function(){
          if(container.val()=='') container.val(container.data('defaultWord'));
        })
        .autocomplete(
          _settings.URL,
          {
            delay: 100,
            minChars: 1,
            matchSubset: 0,
            matchContains: 0,
            cacheLength: 10,
            onItemSelect: function(){},
            onFindValue: function(){},
            formatItem: function(_row){
                          return "<table width=95%><tr><td align=left>" + _row[0] + "</td><td align=right>"  + _row[1] + "</td></tr></table>";
                        },
            selectFirst: false,
            autoFill: false,
            scroll: false,
            max : 15
          }
        );

      ;
      container.data('kwSearch',1);
    }
  }
  return this.each(_kwSearch);
}

$.fn.MoMoChkLogin = function(){
  /*
  cookie information for login:
  loginUser top_CardMem.gif
  cardUser  top_WebMem.gif
  */
  var _loginUser=$().cookie('loginUser');
  var _cardUser=$().cookie('cardUser');
  var _imga1=$('#bt_0_003_01 .CL1 a');
  var _img1=$('#bt_0_003_01 .CL1 img');
  var _imga2=$('#bt_0_003_01 .CL4 a');
  var _img2=$('#bt_0_003_01 .CL4 img');
  var _txt=$('#bt_0_003_01 .loginTxt');

  if (_cardUser==null || _cardUser=='null') _cardUser='';
  if (_loginUser==null || _loginUser=='null') _loginUser='';

  if (_cardUser !='' || _loginUser !=''){
    _imga1.attr('href',_imga1.attr('outsrc'));
    _img1.attr('src',_img1.attr('outimg'));
    _imga2.attr('href','#');
    if (_cardUser!=''){
      _img2.attr('src',_img2.attr('cardimg'));
      _cardUser=_cardUser.replace(/\*/g,";");
      _txt.html(_cardUser);
      _txt.css('color','#666666');
    }else if(_loginUser !=''){
      _img2.attr('src',_img2.attr('webimg'));
      _loginUser=_loginUser.replace(/\*/g,";");
      _txt.html(_loginUser);
      _txt.css('color','#EC0A8F');
    } 
  }else{
    _imga1.attr('href',_imga1.attr('insrc'));
    _img1.attr('src',_img1.attr('inimg'));
    _imga2.attr('href',_imga2.attr('joinsrc'));
    _img2.attr('src',_img2.attr('joinimg'));
    _txt.text('');
  }

}

$.fn.MoMoTRVChkLogin = function(){
  var _loginUser=$().cookie('loginUser');
  var _pst13=$('#bt_0_051_01 .PST1,.PST3');
  var _pst24=$('#bt_0_051_01 .PST2,.PST4');
  var _span=$('#bt_0_051_01 .PST2 p span');
  
  if (_loginUser==null || _loginUser=='null') _loginUser='';
  // momo travel
  if (_loginUser != '') {
    _loginUser = _loginUser.replace(/\*/g, '');
    _pst13.hide();
    _pst24.show();
    _span.html(_loginUser);
  }
}

$.fn.MoMoTRVCtgSrh = function(){
  var _selM = $('#bt_0_052_01 #p_mgrpCode');
  $.ajax({
    type:"POST",
    url:"/search/TravelSearch.jsp?srhctg=1615700000",
    dataType:"text",
    success:function(cateStr){
      if(cateStr.indexOf('<html>') >= 0) return;
      _selM.find('option').remove();
      _selM.append('<option value="0">(請選擇)</option>');
      var _cateA = $.trim(cateStr).split(',');
      for(var i = 0; i < _cateA.length; i++) {
        var _ctgA = _cateA[i].split('=');
        _selM.append('<option value="'+$.trim(_ctgA[0])+'">'+$.trim(_ctgA[1])+'</option>');
      }
    }
  });
  
  _selM.change(function(){
    var _val = momoj(this).val();
    if (typeof(_val)=='undefined' || _val=="" || _val=="0") return;
    var _sel = momoj('#bt_0_052_01 #p_sgrpCode');
    _sel.find('option').remove();
    _sel.append('<option value="0">(請選擇)</option>');
    $.ajax({
      type:"POST",
      url:"/search/TravelSearch.jsp?srhctg="+_val,
      dataType:"text",
      success:function(cateStr){
        var _cateA = $.trim(cateStr).split(',');
        for(var i = 0; i < _cateA.length; i++) {
          var _ctgA = _cateA[i].split('=');
          _sel.append('<option value="'+$.trim(_ctgA[0])+'">'+$.trim(_ctgA[1])+'</option>');
        }
      }
    });
  });
}

// for Brwose Produce History by HHWU
$.fn.history = function(settings) {
  var _defaultSettings = {
        showItem : 4,
        arrowUpImage : '/ecm/img/cmm/browseHistory/watermark_arrowup.gif',
        arrowDnImage : '/ecm/img/cmm/browseHistory/watermark_arrowdown.gif',
        baseUrl: '',
        offsetTop: 10,
        offsetLeft: 0.1,
        arrowDnId: 'arrowDown',
        arrowUpId: 'arrowUp',
        imageHeight : 60,
        elementWidth: 64
	};
	$.extend(_defaultSettings, settings || {});
	var _BrowHist=$().cookie("Browsehist");
    var aryCodeList = new Array();
    if ( !(_BrowHist =='null' || _BrowHist ==null ) ){
        aryCodeList = $().cookie("Browsehist").split(",");
    } else {
        return this;
    }

	var clickIdx = 0;
	var upId = _defaultSettings.arrowUpId;
	var dnId = _defaultSettings.arrowDnId;
    var thisObj = this;
    var baseObj = $('img' , thisObj);
    thisObj.css({'width': _defaultSettings.elementWidth});

	var htmlList= [
	'<span id="list">',
		'<div id="'+ upId +'">',
			'<img src="' + _defaultSettings.baseUrl +_defaultSettings.arrowUpImage+'">',
		'</div>',
		'<span id="listItem"></span>',
		'<div id="'+ dnId +'">',
			'<img src="'+ _defaultSettings.baseUrl +_defaultSettings.arrowDnImage+'">',
		'</div>',
	'</span>'
	].join('');

	baseObj.after(htmlList);

	var listObj = $("#list");
	var items;
  
	var numberOfItems = (aryCodeList.length < _defaultSettings.showItem) ? aryCodeList.length : _defaultSettings.showItem ;
    var displayZoneHeight = _defaultSettings.imageHeight * numberOfItems;

    if ($.browser.msie && parseInt($.browser.version) <=7 ) {
        var temp = thisObj.position();
        listObj.css({'position': 'absolute', 'width': _defaultSettings.elementWidth, 'top': temp.top + baseObj.height() + 'px', 'left': temp.left + 'px'} ).hide();
    } else {
        listObj.css({'position': 'absolute', 'width': _defaultSettings.elementWidth, 'display':'block','float':'left'} ).hide();
    }

	var listItemObj = $("#listItem").css({'position':'absolute','overflow-y': 'hidden', 'height' : displayZoneHeight + 'px'});
    var objDn = $('#'+dnId);

	thisObj.mouseenter(function() {
		clickIdx=0;
		listItemObj.empty();
    $.each(aryCodeList, function(idx, i_code) {
      //var suffix = i_code.substring( i_code.length - 2, i_code.length);
      var suffix = i_code;
      for(var i=0;i<10-i_code.length;i++){
        suffix='0'+suffix;
      }
      suffix=suffix.substring(0,4)+'/'+suffix.substring(4,7)+'/'+suffix.substring(7,10);
			var html = ['<div class="item">',
			'<span>',
			'<a href="'+ _defaultSettings.baseUrl +'/goods/GoodsDetail.jsp?i_code=' + i_code + '" target="_blank">',
			'<img height="60" style="border-left-width:2px; border-right-width:2px;border-color:transparent;" src="'+ _defaultSettings.baseUrl +'/goodsimg/'+ suffix+'/'+ i_code+'_S.jpg">',
			'</a>',
			'</span>',
			'</div>'].join('');
			listItemObj.append($(html).css({'overflow': 'hidden','height': '60px','width': _defaultSettings.elementWidth}));
        });
        listObj.show();
		items = $("#listItem .item");

        objDn.css({
            'position': 'relative',
            'top': numberOfItems * _defaultSettings.imageHeight +'px'
        });
	}).mouseleave(function() {
		listObj.hide();
	});

	$('#'+ upId).click( function() {
		if (clickIdx >= aryCodeList.length - numberOfItems ) return;
		items.each(function() {
            var obj = $(this);
            var tmp_css = obj.css('top');
            var tmp = (tmp_css == 'auto') ? 0 : parseInt(tmp_css);
            obj.css({'position': 'relative', 'top': tmp - _defaultSettings.imageHeight + "px"});
		});
        clickIdx++;
	});
	
    objDn.click( function() {
		if (clickIdx <= ((numberOfItems == _defaultSettings.showItem)? 0 : numberOfItems) ) return;
		items.each(function(idx) {
            $(this).css({'position': 'relative',"top": parseInt($(this).css("top")) + _defaultSettings.imageHeight + "px"});
		});
        clickIdx--;
	})					

	return this;
};

// for SiteMap Produce History by HHWU
$.fn.iframeShow = function(settings) {

    //FIXME, cache and effect
    var _defaultSettings = {
        //event: 'click',
        zindex: 9000,
        url: null,
        width: 650,
        height: 700
    };
    var rightTop = { x : 0, y : 0 }
    $.extend(_defaultSettings , settings || {} );

    var displayLayer = [
    '<div id="showFrame" style="position: absolute">',
        '<iframe id="mapFrame" allowtransparency="true" frameborder="0" style="background-color:transparent"></iframe>',
    '</div>',
    ].join('');		

    $(displayLayer)
        .css({
            'z-index': _defaultSettings.zindex,
            'height': _defaultSettings.height,
            'width' : _defaultSettings.width
        }).hide().appendTo($(this));

    $('#mapFrame').css({
        'position': 'absolute',
        'border': "0px none",
        'height': _defaultSettings.height,
        'width' : _defaultSettings.width
    });
    var thisObj = $(this);
    var offsetObj = thisObj.position();
    var displayObj = $("#showFrame");

    _defaultSettings.url = "/activity/090202105137/main.html";

    rightTop.x = (offsetObj.left + thisObj.width()) - _defaultSettings.width + 8;
    rightTop.y = offsetObj.top + thisObj.height();

    $('iframe#mapFrame').attr('src', _defaultSettings.url).load(function(){
        // Specialized for momoshop
        $(this).contents().find('img[onclick]').parent().parent().click(function(event) {
            event.stopPropagation();
            event.stopImmediatePropagation();
            displayObj.slideToggle();
        });
    }).css({'background-color': 'transparent'});
    displayObj.css({
        'background-color': 'transparent',
        'top':  rightTop.y + 'px',
        'left': rightTop.x + 'px'
    });

    this.bind('click' , function() {
        displayObj.slideToggle();
    });
		
    return this;

};

// ajax for goods price by goods code
$.fn.getGoodsPrice = function(settings){
  var gds=$(this);
  var _defaultSettings = {
    URL:"/ajax/getGoodsPri.jsp",
    GoodsCode:""
  };
  var _goodsPrice='';
  var _settings = $.extend(_defaultSettings, settings);
  if(_settings.GoodsCode=='') return '';
  $.ajax({
    url:_settings.URL,
    type:'POST',
    data:{goodsCode:_settings.GoodsCode},
    dataType:'html',
    async:false,
    success:function(content){
      _goodsPrice=content;
    }
    
  });
  //alert('goods price:'+_goodsPrice);
  return _goodsPrice;
}

// ajax for goods price by goods code
$.fn.getGoodsProInfo = function(settings){
  var gds=$(this);
  var _defaultSettings = {
    URL:"/ajax/getGoodsProInfo.jsp",
    GoodsCode:""
  };
  var _goodsInfo='';
  var _settings = $.extend(_defaultSettings, settings);
  if(_settings.GoodsCode=='') return '';
  $.ajax({
    url:_settings.URL,
    type:'POST',
    data:{goodsCode:_settings.GoodsCode},
    dataType:'html',
    async:false,
    success:function(content){
      _goodsInfo=content;
    }
    
  });
  //alert('goods price:'+_goodsInfo);
  return _goodsInfo;
}

// for DgrpCategory bt_9_002 goods price
$.fn.fixGoodsPrice = function(settings){
  var container=$(this);
  var _defaultSettings = {
    elsLimit:0,                     //if elsLimit eq 0, do not check
    mainDiv:'',
    failHide:true,
    succShow:true,
    proImg:true,                    //是否變更 promote type image
    proImgTag:'.content div:first'  //變更 promote img 的位置
  };
  var _settings = $.extend(_defaultSettings, settings);
  
  // if elments len ne elsLimit 
  if(_settings.elsLimit>0 && container.length < _settings.elsLimit){
    if (_settings.failHide) $('#'+_settings.mainDiv).hide();
    return;
  }
  var _goodsCode='';
  container.each(function(){
    var _class=$(this).attr('class');
    var _classA=_class.split(' ');
    var _gc='0__';
    for(var i=0;i<_classA.length;i++){
      if(_classA[i].match(/^GDS-/) ){
        _gc=_classA[i].replace(/^GDS-/,'')+'__';
        //_goodsCode+=_gc+'__';
        break;
      }
    }
    _goodsCode+=_gc;
  });
  _goodsCode=_goodsCode.replace(/__$/,'');
  if(_settings.elsLimit >0 && _goodsCode.split('__').length < _settings.elsLimit){
    if (_settings.failHide) $('#'+_settings.mainDiv).hide();
    return;  
  }
  var _goodsPrice=$().getGoodsProInfo({GoodsCode:_goodsCode});
  _goodsPrice=_goodsPrice.replace(/\n/g,'');
  _goodsPrice=$.trim(_goodsPrice);
  var _goodsPriceA=_goodsPrice.split('__');
  if(_settings.elsLimit>0 && _goodsPriceA.length < _settings.elsLimit){
    if (_settings.failHide) $('#'+_settings.mainDiv).hide();
    return;
  }
  var _els=0;
  var _elOKs=0;
  container.each(function(){
    var _el=$(this);
    var _elTop='';
    var _classELA=_el.attr('class').split(' ');
    for(var _elClassi=0;_elClassi<_classELA.length;_elClassi++){
      if(_classELA[_elClassi].match(/^bt_/)){
        _elTop=$('#'+_classELA[_elClassi]);
        break;
      }
    }    
    if(_goodsPriceA[_els].split('-')[0]==0){
      _el.html('&nbsp;');
      //if (_settings.failHide) $('#'+_settings.mainDiv).hide();
      _elTop.hide();
    }else{
      _elOKs++;
      _el.text(_goodsPriceA[_els].split('-')[0]);
      if(_settings.proImg){
        var _imgstatus=_goodsPriceA[_els].split('-')[1];
        if(typeof _imgstatus=='undefined') _imgstatus='0000';
        if(_imgstatus.length != 4) _imgstatus='0000';
        var _imgHtml='';
        // 快速到貨,TV, 折價券, 促銷
        if (_imgstatus.substr(0,1)=='1') _imgHtml+='<img src="/ecm/img/de/9/bt_9_002/first.gif"/>';
        if (_imgstatus.substr(1,1)=='1') _imgHtml+='<img src="/ecm/img/de/9/bt_9_002/tvb.gif"/>';
        if (_imgstatus.substr(2,1)=='1') _imgHtml+='<img src="/ecm/img/de/9/bt_9_002/couponb.gif"/>';
        if (_imgstatus.substr(3,1)=='1') _imgHtml+='<img src="/ecm/img/de/9/bt_9_002/saleb.gif"/>';
        var _imgTag=$(_settings.proImgTag,_elTop);
        _imgTag.empty().append(_imgHtml);
      }
    }
    _els++;
  });
  if (_settings.failHide && _elOKs < _settings.elsLimit){
    $('#'+_settings.mainDiv).hide();
    return;
  }
  $('#'+_settings.mainDiv).show();
}

// for cancel browser refresh
$.fn.cancelF5 = function(){
	window.focus();
	$(window.document).keydown(function(event) {
		_event = $.browser.msie ? window.event : event;
		if (_event.keyCode == '116') { // 禁 F5
			_event.keyCode = 0;
			return false;
		}
		if (_event.ctrlKey && _event.keyCode == '82') { //禁 Ctrl+R
			return false;
		}
		if (_event.shiftKey && _event.keyCode == '121') { //禁 shift+F10
			return false;
		}
	});
}

// for ghost shopping car
$.fn.shoppingCart = function(settings){
  var _ssl_domain_url='';
  if(typeof _SSL_DOMAIN_URL=='string')
    _ssl_domain_url=_SSL_DOMAIN_URL;
    
  var _defaultSettings = {
    shopCartUrl:_ssl_domain_url+"/order/Cart.jsp?"
  };
  var _settings = $.extend(_defaultSettings, settings);
  
  var cartURL= new Array(
    "cart_name=shopcart",
    "cart_name=first",
    "cart_name=superstore",
    "cart_name=matsusei1"
  );

  var container=$('#ShoppingCar');
  if(container.length==0){// if not exists, create first
    $('body').append('<div id="ShoppingCar" style="width:183px;position:absolute;z-index:10000;"></div>');  
    container=$('#ShoppingCar');
    var shopCar = [
    '<div class="title" style="width:181px;">',
      '<img class="shopCart" src="/ecm/img/cmm/shopcar/carcar_03.gif" style="width:127px;border:0px;"/>',
      '<img class="opcl" src="/ecm/img/cmm/shopcar/carcar_04.gif" style="width:54px;cursor:pointer;border:0px;"/>',
    '</div>',
    '<div class="content" style="width:181px;overflow:hidden;background:transparent url(/ecm/img/cmm/shopcar/carcar_06.gif) repeat-y">',
    '</div>',
    '<div class="bottom" style="width:181px;">',
      '<img src="/ecm/img/cmm/shopcar/carcar_08.gif" style="width:181px;height:6px;border:0px;"/>',    
    '</div>'
    ].join('');
    container.html(shopCar);
  }

  if(!container.data('cartOpen'))
    container.data('cartOpen',1);
  
  if(!container.data('initCart')) {
    container.data('initCart',1);
    if (typeof shopCart=="object"){
      var _carts=0;
      for(var i=0;i<shopCart.length;i++){
        if (shopCart[i][1] > 0){
          _carts++;
        }
      }
      if (_carts){
        // bind window onscroll
        $(window).scroll(function(){_cartMove()});
        $(window).resize(function(){_cartMove()});
      }
    }
    //$('.title .shopCart',container)
    //  .click(function(){document.location.href=_settings.shopCartUrl});
    $('.title .opcl',container)
      .click(function(){
        if(container.data('cartOpen')){
          _cartClose();
        } else {
          _cartOpen();
        }
      });
  }
  
  // set cart information
  var _cartSet = function(){
    var _carts=0;
    if (typeof shopCart=="object"){
      //gshopcart[0]=new Array(cartName,products,money)
      for(var i=0;i<shopCart.length;i++){
        if (shopCart[i][1] > 0){
          _carts++;
          if(_carts==1)
            $('.content',container).empty().append('<ul style="width:181px;"></ul>')
          
          var _table=$('.content ul',container);
          //var _carturl=_settings.shopCartUrl+cartURL[i];
          var _carturl='javascript:momoj().MomoLogin({GoCart:true,LoginSuccess:function(){location.href=\''+_settings.shopCartUrl+cartURL[i]+'\'}});';
          var _tr=[
            '<li style="height:24px;">',
              '<p style="text-align:left;width:50px;line-height:24px;color:#003399;font-size:12px;margin-left:4px;float:left;">'+shopCart[i][0]+'</p>',
              '<p style="width:22px;float:left;line-height:20px;"><a href="'+_carturl+'" style="color:#FF0000;font-size:12px;text-decoration:underline;">('+shopCart[i][1]+')</a></p>',
              '<p style="width:65px;text-align:right;float:left;color:#FF0000;font-family:Arial;font-size:15px;font-weight:bold;line-height:24px;">'+shopCart[i][2]+'元</p>',
              '<p style="width:37px;float:left;"><a href="'+_carturl+'"><img src="/ecm/img/cmm/shopcar/cound2.gif" /></a></p>',
            '</li>'
          ].join('');
          _table.append(_tr);
        }
      }
    }
    
    if(_carts==0){
      container.hide();
      $(window).unbind('scroll','_cartMove');
      $(window).unbind('resize','_cartMove');
    }else{
      container.show();
    }
  }
  
  var _cartMove = function(){
    var _ctleft=$(window).width()+$(window).scrollLeft()-container.width();
    var _cttop=$(window).height()+$(window).scrollTop()-container.height();
    container.css({'left':_ctleft,'top':_cttop});
  }
  var _cartOpen = function(){
    $('.title .shopCart',container).attr('src','/ecm/img/cmm/shopcar/carcar_03.gif');
    $('.title .opcl',container).attr('src','/ecm/img/cmm/shopcar/carcar_04.gif');
    $('.content',container).show();
    $('.bottom',container).show();  
    _cartMove();
    container.data('cartOpen',1)
  }
  
  var _cartClose = function(){
    $('.title .shopCart',container).attr('src','/ecm/img/cmm/shopcar/carcarclose_03.gif');
    $('.title .opcl',container).attr('src','/ecm/img/cmm/shopcar/carcarclose_04.gif');
    $('.content',container).hide();
    $('.bottom',container).hide();
    _cartMove();
    container.data('cartOpen',0)
  }
  
  return {
    open: function(){
      _cartSet();
      _cartOpen();
      return container;
    },
    close: function(){
      _cartClose();
      return container;
    }
  }
}

$.fn.shoppingCartForTop = function(settings){
  var container=$(this);
  var _defaultSettings = {
    shopCartUrl:"/order/Cart.jsp?",
    URL:"/order/GhostCart.jsp"
  };
  var _settings = $.extend(_defaultSettings, settings);
  
  var cartURL= new Array(
    "cart_name=shopcart",
    "cart_name=first",
    "cart_name=superstore",
    "cart_name=matsusei1"
  );
  var _shopCart='';
  container.hover(
    function(){
      _show();
    },
    function(){
      _hide();
    }
  );
  var _createCart=function(){
    _shopCart=$('#ShoppingCarTop');
    if(_shopCart.length==0){// if not exists, create first
      var _shopCartLayout=[
        '<div style="width:188px;height:29px;line-height:29px;color:#666666;font-family:Arial,Helvetica,sans-serif;overflow:hidden;margin:1px;text-align:center;background:transparent url(/ecm/img/cmm/shopcar/cart_tit_bg.gif) no-repeat;">',
        '您有： <span class="prdAmt" style="color:#FF0000;">0</span> 個商品',
        '</div>'
        ].join('');      
        $('body').append('<div id="ShoppingCarTop" style="display:none;border:4px solid rgb(255,204,204);position:absolute;width:190px;background-color:rgb(255,240,240);z-index:10000;"></div>');  
      _shopCart=$('#ShoppingCarTop');
      _shopCart.html(_shopCartLayout);

      if (typeof shopCart!="object"){
        // use ajax get cart list
        $.ajax({
          url:_settings.URL,
          type:'GET',
          data:{cid:"memfu",oid:"cart",ctype:"B",mdiv:"1000100000-bt_0_003_01"},
          dataType:'html',
          async:false,
          cache: false,
          success:function(content){
            momoj('body').append(content);
          }
        });
      }
      var _prds=0;
      //var _bold='font-weight:bold;';
      //var _font_weight=_bold;
      for(var i=0;i<shopCart.length;i++){
        if (shopCart[i][1] > 0){
          var _cartPrds=shopCart[i][1]-0;
          _prds+=_cartPrds;
          var _line_height=(shopCart[i][0].length>4)?"":"line-height:29px;";
          //_font_weight=(_font_weight=="")?_bold:"";
          var _carturl='javascript:momoj().MomoLogin({GoCart:true,LoginSuccess:function(){location.href=\''+_settings.shopCartUrl+cartURL[i]+'\'}});';
          var _tr=[
            '<div style="width:188px;height:29px;color:#666666;margin:1px;" class="fw">',
              '<p style="width:54px;'+_line_height+';height:29px;float:left;color:#666666;font-size:12px;padding-left:4px;text-align:left;"><a href="'+_carturl+'" style="color:#666666;font-size:12px;">'+shopCart[i][0]+'</a></p>',
              '<p style="width:16px;line-height:29px;float:left;"><a href="'+_carturl+'" style="color:#0099CC;font-size:12px;">('+shopCart[i][1]+')</a></p>',
              '<p style="width:65px;line-height:29px;float:left;text-align:right;color:#666666;font-family:Arial;font-size:12px;"><a href="'+_carturl+'" style="color:#666666;font-size:12px;">'+shopCart[i][2]+'元</a></p>',
              '<p style="width:40px;margin:4px 0pt 0pt 4px;float:left;"><a href="'+_carturl+'"><img src="/ecm/img/cmm/shopcar/cart_btn_2.gif" /></a></p>',
            '</div>'
          ].join('');
          _shopCart.append(_tr);
        }
      }
      if(_prds>0){
        $('.prdAmt',_shopCart).text(_prds);
      }
      $('.fw',_shopCart).hover(
          function(){
            $(this).css({'font-weight':'bold'});
          },
          function(){
            $(this).css({'font-weight':'normal'});
          }
      );
      _shopCart.hover(
        function(){
          _show();
        },
        function(){
          _hide();
        }
      );
    }
  }
  
  var _show=function(){
    _createCart();
    var _bodyBaseLeft=momoj('#BodyBase').position().left; // 當螢幕超過這個 1024 寬度時，要用這個修正 left
    var _pos=container.position();
    var _height=container.height();
    var _x=_pos.left+_bodyBaseLeft;
    var _y=_pos.top+_height;
    _shopCart.css({left:_x,top:_y});
    _shopCart.show();
  }

  var _hide=function(){
    _shopCart.hide();
  }

  return container;
}

// 遮罩圖層
$.fn.LayerMask = function(settings){
  var container=$('#MoMoLM');
  if(container.length==0)// if not exists, create first
    $('body').append('<div id="MoMoLM"></div><div id="MoMoLMContent"></div>');
  container=$('#MoMoLM');
  var _content=$('#MoMoLMContent');
  var _defaultSettings = {
    bgColor:'#EEEEEE',  
    opacity:'0.5',
    contentWidth:'600px',
    contentHeight:'500px',
    contentBGColor:'#FFFFFF'
  };    
  var _settings = $.extend(_defaultSettings, settings);
  var _MaxZindex=1;
  $('div').each(function(){
    //alert('zindex:'+$(this).css('z-index')+';'+typeof $(this).css('z-index') );
    var _zindex=$(this).css('z-index');
    if(typeof _zindex=='number' && _zindex>_MaxZindex){
      _MaxZindex=_zindex;
    }else if(typeof _zindex=='string'){
      if(_zindex=='auto' || _zindex=='undefined') _zindex=1;
      _zindex=_zindex-1+1;
      if(_zindex>_MaxZindex) _MaxZindex=_zindex;
    }
  });
  _MaxZindex+=1;
  var _LMHeight=$(document).height();
  var _LMWidth=$(document).width();

  container.css({
    height:_LMHeight,
    width:_LMWidth,
    'z-index':_MaxZindex,
    display:'none',
    position:'absolute',
    'background-color':_settings.bgColor,
    top:'0px',
    left:'0px',
    opacity:_settings.opacity
  });
  // set default width and height
  _content.css({
    width:_settings.contentWidth,
    height:_settings.contentHeight,
    'z-index':_MaxZindex+1,
    display:'none',
    'background-color':_settings.contentBGColor
  });
    
  var _showContent = function(){
    container.show();
    _content.show();
    // get content width, height and set to screen center
    var _ctWidth=_content.width();
    var _ctHeight=_content.height();
    var _ctTop=($(window).height()-_ctHeight)/2+$(document).scrollTop();
    _ctTop=(_ctTop<0)?1:_ctTop;
    var _ctLeft=($(window).width()-_ctWidth)/2+$(document).scrollLeft();
    _ctLeft=(_ctLeft<0)?1:_ctLeft;
    _content.css({
      top:_ctTop,
      left:_ctLeft
    });
  }
  var _close = function(){
    container.hide();
    _content.hide();
  }
  
  return {
    open: function(){
      _showContent();
      return container;
    },
    close: function(){
      _close();
      return container;
    }
  }
}

// for momo login
$.fn.MomoLogin = function(settings){
  var _defaultSettings = {
    GoCart: false,
    LoginSuccess:'',
    LoginCancel:''
  };    
  var _settings = $.extend(_defaultSettings, settings);  
  //$().LayerMask().open();
  $.ajax({
    dataType: 'html',
    cache: false,
    type: "GET",
    data:{cid:"memfu",oid:"login",ctype:"B",mdiv:"1000100000-bt_0_003_01"},
    url: '/ajax/LoginAjax.jsp',
    success: function(msg){
      if(typeof msg == 'string'){
        if (msg.indexOf('status:1')>-1){
          if ($.isFunction(_settings.LoginSuccess)) {
            _settings.LoginSuccess.call(this);
          }
          return;
        }
      }
      
      $().LayerMask({contentWidth:'604px'}).open();
      $('#MoMoLMContent').empty();
      $('#MoMoLMContent').html(msg);
      //$('#CaptchaImg').attr('src','/servlet/MyCaptchaServlet');
      $('#MoMoLMContent').css('height','auto');
      if(_settings.GoCart) 
        $('#ajaxLogin .Bottom').show();
      
      $('#ajaxLogin').data('LoginSuccess',_settings.LoginSuccess);
      $('#ajaxLogin').data('LoginCancel',_settings.LoginCancel);
        
      /*
      $('#MoMoLMContent').css('height',$('#ajaxLogin').height()+4);
      $('#ajaxLogin').unbind('resize').bind('resize',function(){
        $('#MoMoLMContent').css('height',$('#ajaxLogin').height()+4);
      });
      */
    },
    error: function(xhr){
      alert('網站主機忙錄中，請稍後再試！謝謝！');
    }
  });
}

$.extend({
  ajaxTool: function(settings){ // for /ajax/ajaxTool.jsp
    var _defaultSettings = {
      URL:"/ajax/ajaxTool.jsp",
      data:"",
      rData:{rtnCode:600,rtnMsg:"server error",rtnData:{}}
    };
    var _settings = $.extend(_defaultSettings, settings);
    if(typeof _settings.data!="object") return _settings.rData;
    if(typeof _settings.data.flag!="number") return _settings.rData;
    var _flag = _settings.data.flag;
    delete _settings.data.flag;
    var _dataObj={
      flag:_flag,
      data:_settings.data
    };
    var _data=JSON.stringify(_dataObj);

    $.ajax({
      url:_settings.URL,
      type:'POST',
      data:{data:_data},
      dataType:'json',
      async:false,
      success:function(content){
        $.extend(_settings.rData, content || {});
      },
      error:function(err){
        _settings.rData.retCode=601;
      }
    });
    //if(_settings.rData.retCode!=200)
    //  throw Error('something error');
      
    return _settings.rData;
  },
  runMethod:function(settings){
    var _defaultSettings = {
      run:"",
      js:"",
      pa:{}
    };
    var _settings = $.extend(_defaultSettings, settings);
    if (_settings.run=="") return;
    if (_settings.js=="") return;
    var _function="$."+_settings.run;
    var _pa=JSON.stringify(_settings.pa);
    if (eval ("typeof "+_function+"==\"function\"") ){
      eval(_function+".call("+_pa+")")
    } else {
      $.getScript(_settings.js,function(){
        eval(_function+".call("+_pa+")")
      });
    }
  },
  str2Unicode:function(str){
    if(typeof str!='string') return '';
    
    var rtnStr="";
    for(var i=0;i<str.length;i++){
      var _charCode=str.charCodeAt(i);
      if (_charCode<126){
        _charCode=str.substr(i,1);
      }else{
        _charCode='&#'+_charCode+';';
      }
      rtnStr+=_charCode;
    }
    return rtnStr;
    
  }
  

});


})(jQuery);

function ShowMore(l_code){ 
  var _s=0;
  momoj('#bt_cate_top li').each(function(){
    //alert(momoj(this).attr('id')+';class:'+momoj(this).attr('class')+';S:'+_s);
    if(momoj(this).attr('id')==l_code){
      _s=1;
      return true;
    }
    if(_s==1 && momoj(this).hasClass('More')){
      momoj(this).hide();
    }
    if(_s==1 && momoj(this).hasClass('cateS')){
      momoj(this).removeClass('MoreHide');
    }
    if(_s==1 && momoj(this).hasClass('cateM')){
      _s=0;
      return false;
    }
  });
}

function get_form(url,varname){
	var pa=new Array();
	var vara=new Array();
	pa=url.split('?');
	
	if (typeof pa[1] =='undefined'){
    return '';
	}
  
	pa=pa[1].split('&');
	
  var i=0;
  for (i=0;i<pa.length;i++){
		vara=pa[i].split('=');
		if (vara[0]==varname){
			return vara[1];
		}
  }
	return '';
}
