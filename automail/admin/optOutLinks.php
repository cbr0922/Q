<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

include('adminVerify.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
$obj 		= new db_class();
include('./includes/languages.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $obj->getSetting("groupGlobalCharset", $idGroup); ?>">
<link href="./editor/style/editor.css" rel="stylesheet" type="text/css">
<title><?php echo fixJSstring(INSERTULINK_1); ?></title>

    <script>
        var sLangDir = parent.oUtil.langDir;
        document.write("<scr" + "ipt src='./editor/common/language/" + sLangDir + "/weblink.js'></scr" + "ipt>");
    </script>




    <script src="./editor/common/jquery-1.7.min.js" type="text/javascript"></script>

    <!--Slider-->
    <link href="./editor/common/fd-slider/css/skin.css" rel="stylesheet" type="text/css" />
    <script src="./editor/common/fd-slider/js/fd-slider.js" type="text/javascript"></script>

    <!--MiniColors-->
    <script src="./editor/common/spectrum/spectrum.js" type="text/javascript"></script>
    <link href="./editor/common/spectrum/spectrum.css" rel="stylesheet" type="text/css" />

    <script src="./editor/common/common.js" type="text/javascript"></script>

	<script>
	   	document.write("<li"+"nk href=\"" + parent.oUtil.protocol + "//fonts.googleapis.com/css?family=Arvo\" rel=\"stylesheet\" type=\"text/css\" />");
    </script>

    <link href="./editor/style/awesome.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
        #inpURL, #inpTitle {
	        border:1px inset #ddd;
	        font-size:12px;
	        -moz-border-radius:3px;
	        -webkit-border-radius:3px;
	        padding-left:7px;
            }
        .item {width:200px;min-height:50px;display:-moz-inline-stack;display:inline-block;vertical-align:top;zoom:1; *display: inline; _height: 50px;
            background:#fff;border:#fff 7px solid;
            box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.24);
            -moz-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.24);
            -webkit-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.24);
            margin-left:-5px;margin-right:25px;margin-top:10px;margin-bottom:10px;
            border:#fff 1px solid;padding:5px;
            }
        .item2 {width:250px;min-height:20px;display:-moz-inline-stack;display:inline-block;vertical-align:top;zoom:1; *display: inline; _height: 20px;
            cursor:pointer;text-align:center;
            padding-top:10px;
            }
	</style>

    <script src="./editor/common/common.js" type="text/javascript"></script>

    <script language="javascript" type="text/javascript">

        jQuery(document).ready(function ($) {
            renderStyles();

            I_RealtimeLink();
            parent.oUtil.onSelectionChanged = new Function("I_RealtimeLink()");

            /*  ASSET MANAGER HERE */
            if (parent.oUtil.obj.fileBrowser == "") {	/*change spot*/
                $("#frameFiles").attr("src", parent.oUtil.obj.fileBrowser);

                if (parent.oUtil.obj.enableCssButtons == false) {
                    $("#div0").css("display", "none");
                    $("#tab0").css("margin-left", "22px"); 
					$("#tab0").css("display", "none");
                }
            }
            else {
                $("#tab0").css("display", "none");
                $("#div0").css("display", "none");
            }

            /* CSS BUTTONS HERE */
            if (parent.oUtil.obj.enableCssButtons == true) {
                tabClick(1);
                $("#tab1").css("display", "none");
                $("#div1").css("display", "none");
            }
            else {
                $("#tab1").css("display", "none");
                $("#div1").css("display", "none");
            }

            if (parent.oUtil.obj.fileBrowser == "" && parent.oUtil.obj.enableCssButtons == false) {
                $("#divButtons").css("margin-top", "25px");
            }

            /* LIGHTBOX HERE */
            if (parent.oUtil.obj.enableLightbox == false) {
                $("#divLightbox").css("visibility", "hidden");
            }

        //    loadTxt(); /* change spot: commented it. language */
        });

        function fileclick(src) {
            document.getElementById("inpURL").value = src;
        }

        function I_ApplyStyle(sClass) {
            var oEditor = parent.oUtil.oEditor;

            var obj = parent.oUtil.obj;
            obj.setFocus();

            var oSel;
            var oEl;
            if (isIE) {
                var oSel = oEditor.document.selection.createRange();
                if (oSel.parentElement) oEl = GetElement(oSel.parentElement(), "A");
                else oEl = GetElement(oSel.item(0), "A");
            }
            else {
                oSel = oEditor.getSelection();
                oEl = GetElement(parent.getSelectedElement(oSel), "A");
            }

            if (oEl) {
                if (oEl.nodeName == "A") {
                    if (sClass == "") {
                        oEl.removeAttribute("class");
                    } else {
                        oEl.className = sClass;
                    }
                }
            }
        }

        function I_RealtimeLink() {
            if (parent.oUtil + '' == 'undefined') return;

            var oEditor = parent.oUtil.oEditor;

            var obj = parent.oUtil.obj;
            //obj.setFocus();

            /* Protocol */
            document.getElementById('selPro').options[0].checked = true;

            /* Source */
            document.getElementById('inpURL').value = "";

            /* Target */
            document.getElementsByName('rdoTarget')[0].checked = true;

            /* Title */
            document.getElementById('inpTitle').value = "";

            /* Name */
            document.getElementById('inpName').value = "";
			// document.getElementById('btnLink').value = getTxt("insert");
            document.getElementById('btnLink').value = '<?php echo fixJSstring(INSERTULINK_12); ?>';
			document.getElementById("btnCancel").value = '<?php echo fixJSstring(INSERTULINK_11); ?>';

            var oSel;
            var oEl;
            if (isIE) {
                oSel = oEditor.document.selection.createRange();
                if (oSel.parentElement) oEl = GetElement(oSel.parentElement(), "A");
                else oEl = GetElement(oSel.item(0), "A");
            }
            else {
                if (!oEditor.getSelection()) return;
                oSel = oEditor.getSelection();
                oEl = GetElement(parent.getSelectedElement(oSel), "A");
            }

            if (oEl) {
                if (oEl.nodeName == "A") {

                    if (isIE) {
                        var sType = oEditor.document.selection.type;
                        if (sType != "Control") {
                            try {
                                var range = oEditor.document.body.createTextRange();
                                range.moveToElementText(oEl);
                                range.select();
                                parent.oUtil.obj.bookmarkSelection();
                            } catch (e) { return; }
                        }
                    }
                    else {
                        /*var range = oEditor.document.createRange();
                        range.selectNode(oEl);
                        oSel.removeAllRanges();
                        oSel.addRange(range);*/
                        var range = oEditor.document.createRange();
                        range.selectNodeContents(oEl);
                        oSel.addRange(range);
                    }

					var href = oEl.getAttribute("HREF");
					if(!href) href="";
					/* Protocol */
					if(href.match(/^https:\/\//gi)) {
						document.getElementById('selPro').selectedIndex = 2; //https:
						href=href.replace(/^https:\/\//gi, "");
					} else if(href.match(/^mailto:/gi)) {
						document.getElementById('selPro').selectedIndex = 1; //mailto
						href=href.replace(/^mailto:/gi, "");
					} else if(href.match(/^http:\/\//gi)) {
						document.getElementById('selPro').selectedIndex = 0; //http
						href=href.replace(/^http:\/\//gi, "");
					} else {
						document.getElementById('selPro').selectedIndex = 3; //http
					}

                    /* Source */
                    document.getElementById('inpURL').value = href;

                    /* Target */
                    if (oEl.target == "_blank") document.getElementsByName('rdoTarget')[1].checked = true;
                    if (oEl.rel == "lightbox") document.getElementsByName('rdoTarget')[2].checked = true;

                    /* Title */
                    if (oEl.getAttribute("TITLE") != null) document.getElementById('inpTitle').value = oEl.getAttribute("TITLE");

                    /* Name */
                    if (oEl.getAttribute("name") != null) document.getElementById('inpName').value = oEl.getAttribute("name");


                    //document.getElementById('btnLink').value = getTxt("change");
					//document.getElementById('btnLink').value = '<?php echo INSERTULINK_14; ?>';
					document.getElementById("btnCancel").value = '<?php echo fixJSstring(INSERTULINK_11); ?>';

                }
            }
        }

        function I_InsertLink() {
            if (isIE) {
                I_InsertLinkMsft();
            } else if (navigator.userAgent.indexOf('Safari') != -1) {
                I_InsertLinkSaf();
            } else {
                I_InsertLinkMoz();
            }
            return true;
        }

        function I_InsertLinkMsft() {
            var sPro = document.getElementById('selPro').value;

            var sUrl = document.getElementById('inpURL').value;
            if(sUrl!="") {
            	if(sUrl.match(/^https:\/\//gi)) {
            		sUrl=sUrl.replace(/^https:\/\//gi, "");
            	} else if(sUrl.match(/^http:\/\//gi)) {
				    sUrl=sUrl.replace(/^http:\/\//gi, "");
            	} else if(sUrl.match(/^mailto:\/\//gi)) {
				    sUrl=sUrl.replace(/^mailto:\/\//gi, "");
            	}

            	if(sUrl.substring(0, 1)!="/" && sUrl.substring(0, 1)!="/" && sUrl.substring(0, 1)!="#") sUrl = sPro + sUrl;
            }

            var sTitle = document.getElementById('inpTitle').value;
            var sTarget = I_GetRadioValue("rdoTarget");

            parent.oUtil.obj.setFocus();
            var obj = parent.oUtil.obj;

            var oEditor = parent.oUtil.oEditor;

            obj.saveForUndo(); /*undo/redo*/

            var oEl;
            var oSel = oEditor.document.selection.createRange();

            //get A element
            if (oSel.parentElement) oEl = GetElement(oSel.parentElement(), "A");
            else oEl = GetElement(oSel.item(0), "A");

            if (sUrl == "" && oEl) { oEditor.document.execCommand("unlink", false, null); return; }

            /* If no (text) selection, then build selection using the typed URL */
            if (oSel.parentElement) if (oSel.text == "") {
                var oSelTmp = oSel.duplicate();
                oSel.text = sUrl;
                oSel.setEndPoint("StartToStart", oSelTmp);
                oSel.select();
            }

            oSel.execCommand("CreateLink", false, sUrl==""?"#":sUrl);

            //get A element
            if (oSel.parentElement) oEl = GetElement(oSel.parentElement(), "A");
            else oEl = GetElement(oSel.item(0), "A");

            if (oEl) {
                /* Target */
                if (sUrl=="") oEl.removeAttribute("href", 0);
                if (sTarget == "") { oEl.removeAttribute("target", 0); oEl.removeAttribute("rel", 0); };
                if (sTarget == "_blank") { oEl.target = "_blank"; oEl.removeAttribute("rel", 0); };
                if (sTarget == "lightbox") { oEl.removeAttribute("target", 0); oEl.rel = "lightbox"; };

                if (document.getElementById('inpTitle').value != "") oEl.title = document.getElementById('inpTitle').value;
                else oEl.removeAttribute("title", 0);

                if (document.getElementById('inpName').value != "") {
                	oEl.name = document.getElementById('inpName').value;
                	oEl.NAME = document.getElementById('inpName').value;
                } else {
                	oEl.removeAttribute("name", 0);
                	oEl.removeAttribute("NAME", 0);
                }

                if ($("#hidStyle").val() != "") oEl.className = $("#hidStyle").val();

                //document.getElementById('btnLink').value = getTxt("change");
				//document.getElementById('btnLink').value = '<?php echo INSERTULINK_14; ?>';
            }
        }

        function I_InsertLinkMoz() {
        	var sPro = document.getElementById('selPro').value;

            var sUrl = document.getElementById('inpURL').value;	 //alert(sUrl);
            if(sUrl!="") {
            	if(sUrl.match(/^https:\/\//gi)) {
            		sUrl=sUrl.replace(/^https:\/\//gi, "");
            	} else if(sUrl.match(/^http:\/\//gi)) {
				    sUrl=sUrl.replace(/^http:\/\//gi, "");
            	} else if(sUrl.match(/^mailto:\/\//gi)) {
				    sUrl=sUrl.replace(/^mailto:\/\//gi, "");
            	}

            	if(sUrl.substring(0, 1)!="/" && sUrl.substring(0, 1)!="/" && sUrl.substring(0, 1)!="#") sUrl = sPro + sUrl;
            }

            var sTitle = document.getElementById('inpTitle').value;
            var sTarget = I_GetRadioValue("rdoTarget");
            var sName = document.getElementById('inpName').value;

            parent.oUtil.obj.setFocus();
            var obj = parent.oUtil.obj;

            var oEditor = parent.oUtil.oEditor;

            obj.saveForUndo(); /*undo/redo*/


            var oSel;
            var oEl;
            oSel = oEditor.getSelection();
            oEl = GetElement(parent.getSelectedElement(oSel), "A");

            if (sUrl == "" && oEl) { oEditor.document.execCommand("unlink", false, null); return; }

            var range = oSel.getRangeAt(0);

            /* If an image is selected, set no border */
            var oImg = range.startContainer.childNodes[0];
            if (oImg) {
                if (oImg.tagName == "IMG") oImg.style.border = "none";
            }

            /* If no (text) selection, then build selection using the typed URL */
            var emptySel = false;
            if (range.toString() == "") {
                if (range.startContainer.nodeType == 1) {/* Control Selection (1=Node.ELEMENT_NODE) */
                    if (range.startContainer.childNodes[range.startOffset].nodeType != 3) {//3=Node.TEXT_NODE
                        if (range.startContainer.childNodes[range.startOffset].nodeName == "BR") emptySel = true;
                        else emptySel = false;
                    }
                    else emptySel = true;
                }
                else emptySel = true; /* Text Selection */

                if (emptySel ) {	/*change spot: important  && sUrl == "" */
                    var node = oEditor.document.createTextNode(sUrl);
                    range.insertNode(node);
                    oEditor.document.designMode = "on";

                    range = oEditor.document.createRange();
                    range.setStart(node, 0);
                    range.setEnd(node, sUrl.length);

                    oSel = oEditor.getSelection();
                    oSel.removeAllRanges();
                    oSel.addRange(range);
                }
            }

            var isSelInMidText = (range.startContainer.nodeType == 3) && (range.startOffset > 0);  //3=Node.TEXT_NODE

            oEditor.document.execCommand("CreateLink", false, sUrl==""?"#":sUrl);

            oSel = oEditor.getSelection();
            range = oSel.getRangeAt(0);

            //get A element
            if (range.startContainer.nodeType == 3) {//3=Node.TEXT_NODE

                var node = (emptySel || !isSelInMidText ? range.startContainer.parentNode : range.startContainer.nextSibling); //A node
                range = oEditor.document.createRange();
                range.selectNode(node); //error di chrome
                oSel = oEditor.getSelection();
                oSel.removeAllRanges();
                oSel.addRange(range);
            }


			if(range.startContainer.tagName == "A") {
				oEl = range.startContainer;
			} else {
				oEl = range.startContainer.childNodes[range.startOffset];
			}

            if (oEl) {// if image (control) is selected
                /*
                if (sTarget == "") { oEl.removeAttribute("target", 0); oEl.removeAttribute("rel", 0); };
                if (sTarget == "_blank") { oEl.target = "_blank"; oEl.removeAttribute("rel", 0); };
                if (sTarget == "lightbox") { oEl.removeAttribute("target", 0); oEl.rel = "lightbox"; };*/

                if(sUrl=="") oEl.removeAttribute("href", 0);

                if (sTarget == "") {
                    oEl.setAttribute("target", "");
                    oEl.setAttribute("rel", "");
                    oEl.removeAttribute("target", 0);
                    oEl.removeAttribute("rel", 0);
                };
                if (sTarget == "_blank") {
                    oEl.setAttribute("target", "_blank");
                    oEl.setAttribute("rel", "");
                };
                if (sTarget == "lightbox") {
                    oEl.setAttribute("target", "");
                    oEl.setAttribute("rel", "lightbox");
                };

                if (document.getElementById('inpTitle').value != "") oEl.title = document.getElementById('inpTitle').value;
                else oEl.removeAttribute("title", 0);

                if (document.getElementById('inpName').value != "") oEl.name = document.getElementById('inpName').value;
                else oEl.removeAttribute("name", 0);

                if ($("#hidStyle").val() != "") oEl.className = $("#hidStyle").val();

                //document.getElementById('btnLink').value = getTxt("change");
				//document.getElementById('btnLink').value = '<?php echo INSERTULINK_14; ?>';
            }


            //I_RealtimeLink();
        }

        function I_InsertLinkSaf() {
        	var sPro = document.getElementById('selPro').value;

            var sUrl = document.getElementById('inpURL').value;
            if(sUrl!="") {
            	if(sUrl.match(/^https:\/\//gi)) {
            		sUrl=sUrl.replace(/^https:\/\//gi, "");
            	} else if(sUrl.match(/^http:\/\//gi)) {
				    sUrl=sUrl.replace(/^http:\/\//gi, "");
            	} else if(sUrl.match(/^mailto:\/\//gi)) {
				    sUrl=sUrl.replace(/^mailto:\/\//gi, "");
            	}

            	if(sUrl.substring(0, 1)!="/" && sUrl.substring(0, 1)!="/" && sUrl.substring(0, 1)!="#") sUrl = sPro + sUrl;
            }

            var sTitle = document.getElementById('inpTitle').value;
            var sTarget = I_GetRadioValue("rdoTarget");

            parent.oUtil.obj.setFocus();
            var obj = parent.oUtil.obj;

            var oEditor = parent.oUtil.oEditor;

            obj.saveForUndo(); /*undo/redo*/

            var oSel;
            var oEl;
            oSel = oEditor.getSelection();
            oEl = GetElement(parent.getSelectedElement(oSel), "A");

            if (sUrl == "" && oEl) { oEditor.document.execCommand("unlink", false, null); return; }

            var range = oSel.getRangeAt(0);

            /* If an image is selected, set no border */

            var oImg = parent.getSelectedElement(oSel);
            if (oImg) {
                if (oImg.tagName == "IMG") oImg.style.border = "none";
            }

            /* If no (text) selection, then build selection using the typed URL */
            var emptySel = false;
            if (range.toString() == "") {
                if (range.startContainer.nodeType == 1) {/* Control Selection (1=Node.ELEMENT_NODE) */
                    if (range.startContainer.childNodes[range.startOffset].nodeType != 3) {//3=Node.TEXT_NODE
                        if (range.startContainer.childNodes[range.startOffset].nodeName == "BR") emptySel = true;
                        else emptySel = false;
                    }
                    else emptySel = true;
                }
                else emptySel = true; /* Text Selection */

                if (emptySel) {
                    var node = oEditor.document.createTextNode(sUrl);
                    range.insertNode(node);
                    oEditor.document.designMode = "on";

                    range = oEditor.document.createRange();
                    range.setStart(node, 0);
                    range.setEnd(node, sUrl.length);

                    oSel = oEditor.getSelection();
                    oSel.removeAllRanges();
                    oSel.addRange(range);
                }
            }

            if (oImg && oImg.tagName == "IMG") {


                    var newA = oEditor.document.createElement("A");
                    newA.href = sUrl;
                    range.selectNode(oImg);
                    range.surroundContents(newA);

                    oImg.style.border="none";

                    range.selectNodeContents(oImg);
                    range.setEndAfter(oImg);

                    oSel.removeAllRanges();
                    oSel.addRange(range);

            }
            else {
                oEditor.document.execCommand("CreateLink", false, (sUrl==""?"#":sUrl)); //TDK Jalan kalau image ter-select
            }

            oSel = oEditor.getSelection();
            oEl = GetElement(parent.getSelectedElement(oSel), "A");

            if (oEl && oEl.nodeType == 1) {

                if (oEl && oEl.tagName == "A") {

                    if(sUrl!="") {
                    	oEl.href = sUrl;
                    } else {
                    	oEl.removeAttribute("href", 0);
                    }

                    /* Target */
                    if (sTarget == "") { oEl.removeAttribute("target", 0); oEl.removeAttribute("rel", 0); };
                    if (sTarget == "_blank") { oEl.target = "_blank"; oEl.removeAttribute("rel", 0); };
                    if (sTarget == "lightbox") { oEl.removeAttribute("target", 0); oEl.rel = "lightbox"; };

                    if (document.getElementById('inpTitle').value != "") oEl.title = document.getElementById('inpTitle').value;
                    else oEl.removeAttribute("title", 0);

                    if (document.getElementById('inpName').value != "") oEl.name = document.getElementById('inpName').value;
                    else oEl.removeAttribute("name", 0);

                    if ($("#hidStyle").val() != "") oEl.className = $("#hidStyle").val();

                    //document.getElementById('btnLink').value = getTxt("change");
					//document.getElementById('btnLink').value = '<?php echo INSERTULINK_14; ?>';
                }
            }
        }

        function renderStyles() {
            for (var i = 0; i <= 18; i++) {
                $("#block" + i).css("background", "");
                if (i >= 7) $("#block" + i).css("height", "35px");
                if (i >= 13) $("#block" + i).css("height", "33px");
                $("#block" + i).click(function () {
                    for (var j = 0; j <= 18; j++) {
                        $("#block" + j).css("background", "");
                    }
                    $(this).css("background", "#e9ed03");
                    $("#hidStyle").val($(this).children(0).attr('class'));
                });
            }
            $("#block0").css("background", "#e9ed03");
        }

        var storeBgColor, storeColor;
        function over(me, hover) {
            storeBgColor = me.style.backgroundColor;
            if (!hover) me.style.backgroundColor = '#c90000';
            else me.style.backgroundColor = hover;
            storeColor = me.style.color;
            me.style.color = '#fff';
        }
        function out(me) {
            me.style.backgroundColor = storeBgColor;
            me.style.color = storeColor;
        }

        function tabClick(n) { /*change spot*/
            if (n == 0) {
                $("#div0").css("display", "block");
                $("#tab0").css("background", "#fcfcfc");
				$("#tab0").css("display", "none");
                $("#div1").css("display", "none");
                $("#tab1").css("background", "#ccc");
				$("#tab1").css("display", "none");
            }
            if (n == 1) {
                $("#div0").css("display", "none");
                $("#tab0").css("background", "#ccc"); 
				$("#tab0").css("display", "none");
                $("#div1").css("display", "block");
                $("#tab1").css("background", "#fcfcfc");$("#tab1").css("display", "none");
            }
        }


    </script>

</head>
<body style="margin-top:12px;margin-left:10px">

<input id="hidStyle" type="hidden" value="" />

<div id="tab1" onclick="tabClick(1)" style="float:left;font-family:Arvo;font-size:11px;text-shadow:1px 1px 1px rgba(255,255,255,0.6);color:#000;letter-spacing:1px;cursor:pointer;width:100px;min-height:15px;text-align:center;margin-bottom:-8px;padding:3px;background:#ccc;margin-left:22px;margin-top:7px">
    STYLES
</div>
<div id="tab0" onclick="tabClick(0)" style="float:left;font-family:Arvo;font-size:11px;text-shadow:1px 1px 1px rgba(255,255,255,0.6);color:#000;letter-spacing:1px;cursor:pointer;width:100px;min-height:15px;text-align:center;margin-bottom:-8px;padding:3px;background:#fcfcfc;margin-left:5px;margin-top:7px">
    MY FILES
</div>

<div style="clear:left"></div>

<table cellpadding="0" cellspacing="0" style="margin-left:7px;">
<tr>
<td>

    <div id="div0" style="color:#000;margin-right:20px;padding:0px;padding-right:0px;width:295px;height:410px;border-top:none;background:#fcfcfc;display:none;">
        <iframe id="frameFiles" frameBorder="0" src="" style="width:100%;height:410px;margin-bottom:7px;"></iframe>
    </div>

    <div id="div1" style="color:#000;margin-right:20px;padding:0px;padding-right:0px;width:295px;height:410px;overflow:auto;border-top:none;background:#fcfcfc;display:none;">

       <div style="margin:20px">

            <div id="block0" class="item2" onclick="I_ApplyStyle('')" style="height:25px;margin-bottom:5px">
			    <a class="" id="lnkNormalLink" href="javascript:void(0)">Normal Link &raquo;</a>
            </div><br />

            <div id="block13" class="item2" onclick="I_ApplyStyle('small awesome')" style="height:42px">
			    <a class="small awesome">Small Button &raquo;</a>
            </div><br />

            <div id="block14" class="item2" onclick="I_ApplyStyle('small blue awesome')" style="height:42px">
			    <a class="small blue awesome">Small Button &raquo;</a>
            </div><br />

            <div id="block15" class="item2" onclick="I_ApplyStyle('small magenta awesome')" style="height:42px">
			    <a class="small magenta awesome">Small Button &raquo;</a>
            </div><br />

            <div id="block16" class="item2" onclick="I_ApplyStyle('small red awesome')" style="height:42px">
			    <a class="small red awesome">Small Button &raquo;</a>
            </div><br />

            <div id="block17" class="item2" onclick="I_ApplyStyle('small orange awesome')" style="height:42px">
			    <a class="small orange awesome">Small Button &raquo;</a>
            </div><br />

            <div id="block18" class="item2" onclick="I_ApplyStyle('small yellow awesome')" style="height:42px">
			    <a class="small yellow awesome">Small Button &raquo;</a>
            </div><br />

            <!--<div style="margin-top:10px;margin-bottom:10px;border-top:#fff 1px solid;border-bottom:#ccc 1px solid;"></div>-->

            <div id="block7" class="item2" onclick="I_ApplyStyle('medium awesome')" style="height:42px">
			    <a class="medium awesome">Medium Button &raquo;</a>
            </div><br />

            <div id="block8" class="item2" onclick="I_ApplyStyle('medium blue awesome')" style="height:42px">
			    <a class="medium blue awesome">Medium Button &raquo;</a>
            </div><br />

            <div id="block9" class="item2" onclick="I_ApplyStyle('medium magenta awesome')" style="height:42px">
			    <a class="medium magenta awesome">Medium Button &raquo;</a>
            </div><br />

            <div id="block10" class="item2" onclick="I_ApplyStyle('medium red awesome')" style="height:42px">
			    <a class="medium red awesome">Medium Button &raquo;</a>
            </div><br />

            <div id="block11" class="item2" onclick="I_ApplyStyle('medium orange awesome')" style="height:42px">
			    <a class="medium orange awesome">Medium Button &raquo;</a>
            </div><br />

            <div id="block12" class="item2" onclick="I_ApplyStyle('medium yellow awesome')" style="height:42px">
			    <a class="medium yellow awesome">Medium Button &raquo;</a>
            </div><br />

            <!--<div style="margin-top:10px;margin-bottom:10px;border-top:#fff 1px solid;border-bottom:#ccc 1px solid;"></div>-->

            <div id="block1" class="item2" onclick="I_ApplyStyle('large awesome')" style="height:42px">
			    <a class="large awesome">Large Button &raquo;</a>
            </div><br />

			<div id="block2" class="item2" onclick="I_ApplyStyle('large blue awesome')" style="height:42px">
                <a class="large blue awesome">Large Button &raquo;</a>
            </div><br />

            <div id="block3" class="item2" onclick="I_ApplyStyle('large magenta awesome')" style="height:42px">
			    <a class="large magenta awesome">Large Button &raquo;</a>
            </div><br />

            <div id="block4" class="item2" onclick="I_ApplyStyle('large red awesome')" style="height:42px">
			    <a class="large red awesome">Large Button &raquo;</a>
            </div><br />

            <div id="block5" class="item2" onclick="I_ApplyStyle('large orange awesome')" style="height:42px">
			    <a class="large orange awesome">Large Button &raquo;</a>
            </div><br />

            <div id="block6" class="item2" onclick="I_ApplyStyle('large yellow awesome')" style="height:42px">
			    <a class="large yellow awesome">Large Button &raquo;</a>
            </div><br />

        </div>

    </div>

</td>
<td style="font-family:Arvo;letter-spacing:1px;text-shadow:1px 1px 1px rgba(255,255,255,0.6);color:#000;" valign="top">

    <div style="margin-bottom:15px;margin-top:10px">
		<?php echo fixJSstring(INSERTULINK_2); ?>
        <span id="lblProtocol" style="display:none"></span>
        <select name="selPro" id="selPro" style="margin-top:5px;width:235px;height:23px;display:none">
        	<option value="http://">http://</option>
        	<option value="mailto:">mailto:</option>
        	<option value="https://">https://</option>
        	<option value="" selected="selected">none</option>
        </select>
    </div>

    <div style="margin-bottom:15px;">
        <span id="lblUrl" style="display:none"></span>
        <!--input name="inpURL" id="inpURL" type="text" value="" style="margin-top:5px;width:235px;height:23px;" /-->
		<select ID="inpURL" NAME="inpURL">
			<option value=""><?php echo fixJSstring(INSERTULINK_10); ?></option>
			<option value='optoutlink1'><?php echo fixJSstring(INSERTULINK_4); ?></option>
			<option value='optoutlink2'><?php echo fixJSstring(INSERTULINK_5); ?></option>
			<option value='optoutlink3'><?php echo fixJSstring(INSERTULINK_6); ?></option>
		</select>

    </div>

	<div style="margin-bottom:0px;">
        <span id="lblName" style="display:none;">NAME:</span>
        <input name="inpName" id="inpName" type="text" value=""  style="margin-top:5px;width:235px;height:23px;display:none" />
    </div>

    <div style="margin-bottom:1px;">
        <span id="lblTitle" style="display:none;">TITLE:</span>
        <input name="inpTitle" id="inpTitle" type="text" value=""  style="margin-top:5px;width:235px;height:23px;display:none" />
    </div>

    <div style="margin-top:1px;display:none;">
        <input id="rdoTarget1" type="radio" name="rdoTarget" value="" checked="checked" /><label for="rdoTarget1" id="lblTarget1">Open in Page</label><br />
        <input id="rdoTarget2" type="radio" name="rdoTarget" value="_blank" /><label for="rdoTarget2" id="lblTarget2">Open in a New Window</label>
        <div id="divLightbox">
            <input id="rdoTarget3" type="radio" name="rdoTarget" value="lightbox" /><label for="rdoTarget3" id="lblTarget3">Open in a Lightbox</label>
        </div>
    </div>
	<div><strong><?php echo fixJSstring(INSERTULINK_4); ?></strong></div>
	<div style="margin-bottom:15px;margin-left:20px;margin-top:5px;"><?php echo fixJSstring(INSERTULINK_7); ?></div>
	<div><strong><?php echo fixJSstring(INSERTULINK_5); ?></strong></div>
	<div style="margin-bottom:15px;margin-left:20px;margin-top:5px;"><?php echo fixJSstring(INSERTULINK_8); ?></div>
	<div><strong><?php echo fixJSstring(INSERTULINK_6); ?></strong></div>
	<div style="margin-bottom:15px;margin-left:20px;margin-top:5px;"><?php echo fixJSstring(INSERTULINK_9); ?></div>

    <div id="divButtons" style="margin-top:10px;">
        <input type="button" name="btnCancel" id="btnCancel" value="<?php echo fixJSstring(INSERTULINK_11); ?>" onclick="I_Close()" class="inpBtn" style="width:120px;height:33px" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'" />
        <input type="button" name="btnLink" id="btnLink" value="<?php echo fixJSstring(INSERTULINK_12); ?>" onclick="I_InsertLink();I_Close();" class="inpBtn" style="width:120px;height:33px" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'" />
    </div>
</td>
</tr>
</table>



</body>
</html>

