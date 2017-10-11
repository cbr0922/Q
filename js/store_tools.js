<!--
  var menu_store_check_loc=false,menu_store_mouseover_fg=false;
  function menu_store_onmouseover(self,id) {
    var obj,first_run;

    if (!$(id)) {    
      first_run=true;
      if (!html_end_js_var) return;
      var obj=document.body.appendChild(document.createElement("DIV"));
      obj.setAttribute('id', id);
      obj.style.backgroundColor = "#FFFFFF";
      obj.style.position="absolute";      
      obj.style.zIndex = 99;
      obj.style.float="left";      

      obj.onmouseover=function(){
        menu_store_check_loc=true; 
        menu_store_mouseover_fg=true;
      }
      obj.onmouseout=function(){
        menu_store_check_loc=false;
        menu_store_mouseover_fg=false;
      }
    } 
    else {
      obj = $(id);            
    }    
    menu_store_check_loc=true;

    if (obj.style.visibility == "hidden" || first_run) {
      menu_store_data_detail(self,obj);
    }
    else {
      menu_store_data_detail(self,obj);
    }
  }

  function menu_store_data_detail(self,obj) {
    var jswleft,jswtop,difn=0;
    if (menu_store_mouseover_fg) return;
    ihtmls=menu_store_tag_array[self.id];
    obj.innerHTML=ihtmls;

    //obj.style.overflow="hidden";
    obj.style.visibility = "";        
    var self_os=getOffset(self);
    jswleft=self_os.left + self_os.width / 2 - 70;
    //jswtop=self_os.top + self_os.height - 8;
    if (self_os.height < 20) difn=6;
    else if (self_os.height > 25) difn=-8;
    jswtop=self_os.top + self_os.height + difn;

    obj.style.left=jswleft+"px";
    obj.style.top=jswtop+"px";
  }
  
  function menu_store_onmouseout(self,id) {  	
    menu_store_check_loc=false;
    Args_setTimeout(menu_store_close_jswin, 300, self, $(id));
  }
    
  function menu_store_close_jswin(self,obj) {   	
    if (obj && !menu_store_check_loc) {  	
      obj.style.visibility = "hidden";
    }   
  }

//============

  //function completeCallback(mq, ok_obj, custom) {
  var goldsup_timedo = null;
  function goldsup_completeCallback(response) {
    var custom = response.custom;
    var mq = response.main;
/*
    if(custom == 'up') return mq.run({direction:'down', custom:'down'});
    if(custom == 'down') return mq.run({direction:'up', custom:'up'});
    if(custom == 'horizontal_left') 
       return mq.run({direction:'right', custom:'horizontal_right'});
    if(custom == 'horizontal_right') 
      return mq.run({direction:'left', custom:'horizontal_left'});

    if(custom == 'horizontal2') {
        var tmpsrc = $('ad1').src;
        $('ad1').src = $('ad2').src;
        //mq.reset();
        mq.align('left');
        $('ad2').src = tmpsrc;
        mq.run();
    }
*/
    goldsup_timedo = Args_setTimeout(goldsup_marquee_mv, 10000, custom.dir);
  }

  goldsup_timedo = Args_setTimeout(goldsup_marquee_mv, 10000, 'left');
  function goldsup_marquee_mv(direc) {
    
    if(typeof(gold_sup_array) == 'undefined' || gold_sup_array.length <= 0) return;
    //alert(goldsup_now_location);
    
    if(goldsup_timedo) {
      clearTimeout(goldsup_timedo);
      goldsup_timedo = null;
    }
    if (direc == "left") {
      if (goldsup_now_location == (gold_sup_array.length - 1)) { goldsup_now_location=0; goldsup_pre_location=(gold_sup_array.length - 1); }
      else { ++goldsup_now_location; goldsup_pre_location=goldsup_now_location-1; }

      $('id_gold_sup_td1').innerHTML=gold_sup_array[goldsup_pre_location];
      $('id_gold_sup_td2').innerHTML=gold_sup_array[goldsup_now_location];
      goldsup_mar_obj.stop();
      goldsup_mar_obj.align('left');
      
      goldsup_mar_obj.run({direction:'left', custom: {dir:direc}});
    }
    else if (direc == "right") {
      if (goldsup_now_location == 0) { goldsup_now_location=(gold_sup_array.length - 1); goldsup_pre_location=0; }
      else { --goldsup_now_location; goldsup_pre_location=goldsup_now_location+1; }

      $('id_gold_sup_td1').innerHTML=gold_sup_array[goldsup_now_location];
      $('id_gold_sup_td2').innerHTML=gold_sup_array[goldsup_pre_location];
      goldsup_mar_obj.stop();      
      goldsup_mar_obj.align('right');
      goldsup_mar_obj.run({direction:'right', custom: {dir:direc}});
    }
  }  

//============

  // 開店說明會跑馬燈
  var bossmsg_timedo = null;
  function bossmsg_completeCallback(response) {   // 每跑完 maxMove 長度呼叫一次
    var custom = response.custom;
    var bseq = custom.bseq;

    if (bseq == boss_msg_ary.length) {
      bseq = 0;
      bossmsg_mar_obj.align('top');
    }
    bossmsg_timedo = Args_setTimeout(bossmsg_marquee_mv, 2000, custom.dir, bseq);  // 停留時間
  }

  bossmsg_timedo = Args_setTimeout(bossmsg_marquee_mv, 2000, 'up', 0);  // 第一次執行，2秒後開始輪播

  function bossmsg_marquee_mv(direc, bseq) {
    if(typeof(boss_msg_str) == 'undefined' || boss_msg_str.length <= 0) return;

    if (bossmsg_timedo) {
      clearTimeout(bossmsg_timedo);
      bossmsg_timedo = null;
    }

    $('id_bossmsg_marquee').innerHTML = boss_msg_str;
    bossmsg_mar_obj.stop();
    //bossmsg_mar_obj.align(direc);
    bossmsg_mar_obj.run({direction:direc, custom: {dir:direc, bseq:++bseq}});
  }

  // 媒體報導跑馬燈
  var mediamsg_timedo = null;
  function mediamsg_completeCallback(response) {
    var custom = response.custom;
    var mseq = custom.mseq;

    if (mseq == media_msg_ary.length) {
      mseq = 0;
      mediamsg_mar_obj.align('top');
    }
    mediamsg_timedo = Args_setTimeout(mediamsg_marquee_mv, 4000, custom.dir, mseq);  // 停留時間
  }

  mediamsg_timedo = Args_setTimeout(mediamsg_marquee_mv, 4000, 'up', 0);  // 第一次執行，4秒後開始輪播

  function mediamsg_marquee_mv(direc, mseq) {
    if(typeof(media_msg_str) == 'undefined' || media_msg_str.length <= 0) return;

    if(mediamsg_timedo) {
      clearTimeout(mediamsg_timedo);
      mediamsg_timedo = null;
    }

    $('id_mediamsg_marquee').innerHTML=media_msg_str;
    mediamsg_mar_obj.stop();
    //mediamsg_mar_obj.align(direc);
    mediamsg_mar_obj.run({direction:direc, custom: {dir:direc, mseq: ++mseq}});
  }

  function index_ad_bigimg_onmouseover(chn,idata,classon,classoff) {
    var class_name_on,class_name_off;
    if (typeof(classon) == "undefined") class_name_on="top_ad_font_con_tit_on";
    else class_name_on=classon;
    if (typeof(classoff) == "undefined") class_name_off="top_ad_font_con_tit_off";
    else class_name_off=classoff;
    if ($('id_index_adbimg_'+index_ad_bimg_loct)) {
      $('id_index_adbimg_'+index_ad_bimg_loct).className=class_name_off;
    }
    index_ad_bimg_loct=chn;
    $('id_index_adbimg_'+chn).className=class_name_on;
    $("id_index_ad_bimg").innerHTML=idata;
  }

  function index_ad_bigimg_onmouseout(chn,classon,classoff) {
    var class_name_on,class_name_off;
    if (typeof(classon) == "undefined") class_name_on="top_ad_font_con_tit_on";
    else class_name_on=classon;
    if (typeof(classoff) == "undefined") class_name_off="top_ad_font_con_tit_off";
    else class_name_off=classoff;
    
    if (index_ad_bimg_loct == chn) return;
    $('id_index_adbimg_'+chn).className=class_name_off;
  }

//============

  function index_big_exh_list_onmouseover(chn) {
    var cssname="con-tit-left-m"+(index_bigexh_loct+1)+"off-v3";
    var cssname_2="con-in-sty"+(chn+1);
    $('id_bigexh_tit_'+index_bigexh_loct).className=cssname;
    index_bigexh_loct=chn;
    var cssname="con-tit-left-m"+(chn+1)+"on-v3";
    $('id_bigexh_tit_'+chn).className=cssname;
    $("id_big_exh_list_area").className=cssname_2;
    $("id_big_exh_list_area").innerHTML=$('id_bigexh_data_'+chn).outerHTML;
  }

  function index_big_exh_list_onmouseout(chn) {
    if (index_bigexh_loct == chn) return;
    var cssname="con-tit-left-m"+(chn+1)+"off-v3";
    $('id_bigexh_tit_'+chn).className=cssname;
  }

//-->