/*
 * Copyright (c) 2011-2012 Carl Asman http://www.edlin.org/
 * Version: 0.25 2012-07-19
 * 
 * TableKit ported to jQuery
 * (part of a project I have done for a client of mine)
 * 
 * You can reach me at www.edlin.org
 * if you want to contact me regarding potential projects.
 * 
 * jqTableKit's aim is to provide the same functionality, in the same way
 * as TableKit, but using jQuery instead of prototype
 * 
 * The original TableKit is Copyright Andrew Tetlaw & Millstream Web Software
 * http://www.millstream.com.au/view/code/tablekit/
 * 
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
 * BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
 * ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * By nuevoMailer.com: it has been improved and extended with more date formats.
 * 
 */
(function($){var debugLog=function(msg){console.log(msg);}
var formatKey=function(data,typeIndex){switch(typeIndex){case 0:return formatDateIso(data);case 1:if(data){var pattern=/^(?:sun|mon|tue|wed|thu|fri|sat)\,\s\d{1,2}\s(?:jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s\d{4}(?:\s\d{2}\:\d{2}(?:\:\d{2})?(?:\sGMT(?:[+-]\d{4})?)?)?/i;if(!data.match(pattern)){return 0;}
return new Date(data);}else{return 0;}
case 2:return formatDateEu(data);case 3:return formatDateAu(data);case 4:return formatDateUS(data);case 5:return formatDateDE(data);case 6:return formatDateEU2(data);case 7:return formatDateHU(data);case 8:return formatDateJP(data);case 9:var d=new Date();var ds=d.getMonth()+"/"+d.getDate()+"/"+d.getFullYear()+" ";return new Date(ds+data);case 10:return data?parseFloat(data.replace(/[^-\d\.]/g,'')):0;case 11:return formatDatasize(data);case 12:data=parseFloat(data.replace(/^.*?([-+]?[\d]*\.?[\d]+(?:[eE][-+]?[\d]+)?).*$/,"$1"));return isNaN(data)?0:data;case 13:return data;case 14:return data.toUpperCase();default:return 0;}};var formatDateEu=function(v){var pattern=/^\d{0,2}\/\d{0,2}\/\d{4}\s?(?:\d{1,2}\:\d{2}(?:\:\d{2})?\s?)?/i;if(!v.match(pattern)){return 0;}
var r=v.match(/(\d{0,2})\/(\d{0,2})\/(\d{4})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?)?/i);var yr_num=r[3];var mo_num=parseInt(r[2],10)-1;var day_num=r[1];var hr_num=r[4]?r[4]:0;if(r[7]){var chr=parseInt(r[4],10);if(r[7].toLowerCase().indexOf('p')!==-1){hr_num=chr<12?chr+12:chr;}else if(r[7].toLowerCase().indexOf('a')!==-1){hr_num=chr<12?chr:0;}}
var min_num=r[5]?r[5]:0;var sec_num=r[6]?r[6]:0;return new Date(yr_num,mo_num,day_num,hr_num,min_num,sec_num,0).valueOf();}
var formatDateUS=function(v){var pattern=/^\d{0,2}\/\d{0,2}\/\d{0,4}\s?(?:\d{1,2}\:\d{2}(?:\:\d{2})?\s?[a|p]?m?)?/i;if(!v.match(pattern)){return 0;}
var r=v.match(/^(\d{0,2})\/(\d{0,2})\/(\d{0,4})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?([a|p]?m?))?/i);var yr_num=r[3];var mo_num=parseInt(r[1],10)-1;var day_num=r[2];var hr_num=r[4]?r[4]:0;if(r[7]){var chr=parseInt(r[4],10);if(r[7].toLowerCase().indexOf('p')!==-1){hr_num=chr<12?chr+12:chr;}else if(r[7].toLowerCase().indexOf('a')!==-1){hr_num=chr<12?chr:0;}}
var min_num=r[5]?r[5]:0;var sec_num=r[6]?r[6]:0;return new Date(yr_num,mo_num,day_num,hr_num,min_num,sec_num,0).valueOf();}
var formatDateDE=function(v){var pattern=/^\d{0,2}\.\d{0,2}\.\d{4}\s?(?:\d{1,2}\:\d{2}(?:\:\d{2})?\s?)?/i;if(!v.match(pattern)){return 0;}
var r=v.match(/(\d{0,2})\.(\d{0,2})\.(\d{4})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?)?/i);var yr_num=r[3];var mo_num=parseInt(r[2],10)-1;var day_num=r[1];var hr_num=r[4]?r[4]:0;if(r[7]){var chr=parseInt(r[4],10);if(r[7].toLowerCase().indexOf('p')!==-1){hr_num=chr<12?chr+12:chr;}else if(r[7].toLowerCase().indexOf('a')!==-1){hr_num=chr<12?chr:0;}}
var min_num=r[5]?r[5]:0;var sec_num=r[6]?r[6]:0;return new Date(yr_num,mo_num,day_num,hr_num,min_num,sec_num,0).valueOf();}
var formatDateEU2=function(v){var pattern=/^\d{0,2}\-\d{0,2}\-\d{4}\s?(?:\d{1,2}\:\d{2}(?:\:\d{2})?\s?)?/i;if(!v.match(pattern)){return 0;}
var r=v.match(/(\d{0,2})\-(\d{0,2})\-(\d{4})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?)?/i);var yr_num=r[3];var mo_num=parseInt(r[2],10)-1;var day_num=r[1];var hr_num=r[4]?r[4]:0;if(r[7]){var chr=parseInt(r[4],10);if(r[7].toLowerCase().indexOf('p')!==-1){hr_num=chr<12?chr+12:chr;}else if(r[7].toLowerCase().indexOf('a')!==-1){hr_num=chr<12?chr:0;}}
var min_num=r[5]?r[5]:0;var sec_num=r[6]?r[6]:0;return new Date(yr_num,mo_num,day_num,hr_num,min_num,sec_num,0).valueOf();}
var formatDateHU=function(v){var pattern=/^(\d{4})\.(\d{0,2})\.(\d{0,2})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?)?/;if(!v.match(pattern)){return 0;}
var r=v.match(/^(\d{4})\.(\d{0,2})\.(\d{0,2})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?)?/);var yr_num=r[1];var mo_num=parseInt(r[2],10)-1;var day_num=r[3];var hr_num=r[4]?r[4]:0;if(r[7]){var chr=parseInt(r[4],10);if(r[7].toLowerCase().indexOf('p')!==-1){hr_num=chr<12?chr+12:chr;}else if(r[7].toLowerCase().indexOf('a')!==-1){hr_num=chr<12?chr:0;}}
var min_num=r[5]?r[5]:0;var sec_num=r[6]?r[6]:0;return new Date(yr_num,mo_num,day_num,hr_num,min_num,sec_num,0).valueOf();}
var formatDateJP=function(v){var pattern=/^(\d{4})\/(\d{0,2})\/(\d{0,2})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?)?/;if(!v.match(pattern)){return 0;}
var r=v.match(/^(\d{4})\/(\d{0,2})\/(\d{0,2})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?)?/);var yr_num=r[1];var mo_num=parseInt(r[2],10)-1;var day_num=r[3];var hr_num=r[4]?r[4]:0;if(r[7]){var chr=parseInt(r[4],10);if(r[7].toLowerCase().indexOf('p')!==-1){hr_num=chr<12?chr+12:chr;}else if(r[7].toLowerCase().indexOf('a')!==-1){hr_num=chr<12?chr:0;}}
var min_num=r[5]?r[5]:0;var sec_num=r[6]?r[6]:0;return new Date(yr_num,mo_num,day_num,hr_num,min_num,sec_num,0).valueOf();}
var formatDateIso=function(v){var pattern=/^(\d{4})\-(\d{0,2})\-(\d{0,2})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?)?/;if(!v.match(pattern)){return 0;}
var r=v.match(/^(\d{4})\-(\d{0,2})\-(\d{0,2})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?)?/);var yr_num=r[1];var mo_num=parseInt(r[2],10)-1;var day_num=r[3];var hr_num=r[4]?r[4]:0;if(r[7]){var chr=parseInt(r[4],10);if(r[7].toLowerCase().indexOf('p')!==-1){hr_num=chr<12?chr+12:chr;}else if(r[7].toLowerCase().indexOf('a')!==-1){hr_num=chr<12?chr:0;}}
var min_num=r[5]?r[5]:0;var sec_num=r[6]?r[6]:0;return new Date(yr_num,mo_num,day_num,hr_num,min_num,sec_num,0).valueOf();}
var formatDateAu=function(v){var pattern=/^\d{2}\/\d{2}\/\d{4}\s?(?:\d{1,2}\:\d{2}(?:\:\d{2})?\s?[a|p]?m?)?/i;if(!v.match(pattern)){return 0;}
var r=v.match(/^(\d{2})\/(\d{2})\/(\d{4})\s?(?:(\d{1,2})\:(\d{2})(?:\:(\d{2}))?\s?([a|p]?m?))?/i);var yr_num=r[3];var mo_num=parseInt(r[2],10)-1;var day_num=r[1];var hr_num=r[4]?r[4]:0;if(r[7]){var chr=parseInt(r[4],10);if(r[7].toLowerCase().indexOf('p')!==-1){hr_num=chr<12?chr+12:chr;}else if(r[7].toLowerCase().indexOf('a')!==-1){hr_num=chr<12?chr:0;}}
var min_num=r[5]?r[5]:0;var sec_num=r[6]?r[6]:0;return new Date(yr_num,mo_num,day_num,hr_num,min_num,sec_num,0).valueOf();}
var formatDatasize=function(v){var r=v.match(/^([-+]?[\d]*\.?[\d]+([eE][-+]?[\d]+)?)\s?([k|m|g|t]?b)?/i);var b=r[1]?Number(r[1]).valueOf():0;var m=r[3]?r[3].substr(0,1).toLowerCase():'';var result=b;switch(m){case'k':result=b*1024;break;case'm':result=b*1024*1024;break;case'g':result=b*1024*1024*1024;break;case't':result=b*1024*1024*1024*1024;break;}
return result;}
var functionSortTmp=function(a,b,index){var sortKey='sortKey'+index;if($(a).data(sortKey)<$(b).data(sortKey)){return-1;}
if($(a).data(sortKey)>$(b).data(sortKey)){return 1;}
return 0;};var detectType=function(data){var typeIndex=-1;for(var i=0;i<sortLength;i++){switch(sortTypes[i]){case'date-iso':if(data.match(typePatterns['date-iso'])){return i;}
break;case'date':if(data.match(typePatterns['date'])){return i;}
break;case'date-eu':if(data.match(typePatterns['date-eu'])){return i;}
break;case'date-au':if(data.match(typePatterns['date-au'])){return i;}
break;case'date-us':if(data.match(typePatterns['date-us'])){return i;}
break;case'date-de':if(data.match(typePatterns['date-de'])){return i;}
break;case'date-eu2':if(data.match(typePatterns['date-eu2'])){return i;}
break;case'date-hu':if(data.match(typePatterns['date-hu'])){return i;}
break;case'date-jp':if(data.match(typePatterns['date-jp'])){return i;}
break;case'time':if(data.match(typePatterns['time'])){return i;}
break;case'currency':if(data.match(typePatterns['currency'])){return i;}
break;case'datasize':if(data.match(typePatterns['datasize'])){return i;}
break;case'number':if(data.match(typePatterns['number'])){return i;}
break;default:}}
if(typeIndex<0){return 9;}
return typeIndex;}
var sortTypes=['date-iso','date','date-eu','date-au','date-us','date-de','date-eu2','date-hu','date-jp','time','currency','datasize','number','casesensitivetext','text'];var typePatterns={'date-iso':/[\d]{4}-[\d]{2}-[\d]{2}(?:T[\d]{2}\:[\d]{2}(?:\:[\d]{2}(?:\.[\d]+)?)?(Z|([-+][\d]{2}:[\d]{2})?)?)?/,'date':/^(?:sun|mon|tue|wed|thu|fri|sat)\,\s\d{1,2}\s(?:jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s\d{4}(?:\s\d{2}\:\d{2}(?:\:\d{2})?(?:\sGMT(?:[+-]\d{4})?)?)?/i,'date-eu':/^\d{1,2}-\d{1,2}-\d{4}/i,'date-au':/^\d{2}\/\d{2}\/\d{4}\s?(?:\d{1,2}\:\d{2}(?:\:\d{2})?\s?[a|p]?m?)?/i,'date-us':/^\d{0,2}\/\d{0,2}\/\d{0,4}\s?(?:\d{1,2}\:\d{2}(?:\:\d{2})?\s?[a|p]?m?)?/i,'date-de':/^\d{0,2}.\d{0,2}.\d{4}/i,'date-eu2':/^\d{0,2}-\d{0,2}-\d{4}/i,'date-hu':/^\d{4}.\d{0,2}.\d{0,2}/i,'date-jp':/^\d{4}\/\d{0,2}\/\d{0,2}/i,'time':/^\d{1,2}\:\d{2}(?:\:\d{2})?(?:\s[a|p]m)?$/i,'currency':/(^[$¥£€¤])|([€]$)/,'datasize':/^[-+]?[\d]*\.?[\d]+(?:[eE][-+]?[\d]+)?\s?[k|m|g|t]b$/i,'number':/^[-+]?[\d]*\.?[\d]+(?:[eE][-+]?[\d]+)?$/};var sortLength=sortTypes.length;var isInDragArea=function(elem,pX){if((elem.offset().left+elem.outerWidth(true)-pX)>10){return false;}
return true;}
var resizeChangeCursor=function(elem,pX){if(isInDragArea(elem,pX)){elem.addClass("resize-handle-active");return;};elem.removeClass("resize-handle-active");};var resizeHideDiv=function(){if(resizeDiv){$('.resize-handle').remove();resizeDiv=null;}}
var sortingEnabled=true;var resizeDiv=null;var methods={init:function(options){var settings={'stripe':true,'rowEvenClass':'roweven','rowOddClass':'rowodd','minWidth':10};return this.each(function(){if(options){$.extend(settings,options);}
var optionSortable=$(this).hasClass('sortable');var optionResizable=$(this).hasClass('resizable');var headerTypeIndexes=[];var headerRows=$(this).children('thead').children('tr');var ignoreFirstRow=false;if(0==headerRows.length){ignoreFirstRow=true;headerRows=$(this).find('> tbody > tr:first, > tr:first');}
var headCols=headerRows.find('> td, > th');if(optionResizable){headCols.bind('mousemove.jqTableKit_resize',function(e){resizeChangeCursor($(this),e.pageX);});headCols.bind('mousedown.jqTableKit',function(e){if(!isInDragArea($(this),e.pageX)){return;}
var downElement=$(this);var pX=e.pageX;if(null===resizeDiv){resizeDiv=$('<div />');var closestTable=downElement.closest("table");resizeDiv.addClass('resize-handle').css('top',downElement.offset().top).css('left',pX+'px').css('height',closestTable.height());$('body').append(resizeDiv);downElement.addClass("resize-handle-active");if($.browser.msie){$('*').attr('unselectable','on');}else{$('*').addClass('jqTableKitNoneselectable');}}
sortingEnabled=false;headCols.unbind('mousemove.jqTableKit_resize');$(document).bind('mousemove.jqTableKit_moveresizediv',function(f){resizeDiv.css('left',f.pageX+'px');});$(document).bind('mouseup.jqTableKit',function(e){resizeHideDiv();headCols.removeClass("resize-handle-active");var cellWidth=downElement.outerWidth(true);var change=downElement.offset().left+cellWidth-e.pageX;change=Math.max(settings['minWidth'],downElement.width()-change);downElement.width(change);$(document).unbind('mouseup.jqTableKit');$(document).unbind('mousemove.jqTableKit_moveresizediv');headCols.bind('mousemove.jqTableKit_resize',function(e){resizeChangeCursor($(this),e.pageX);});if($.browser.msie){$('*').attr('unselectable','off');}else{$('*').removeClass('jqTableKitNoneselectable');}
sortingEnabled=true;});});}
if(!optionSortable){return;}
$.each(headerRows,function(index,row){var headerTypeIndexesCnt=0;var matchIndex=-1;var headerCols=$(row).find('> td, > th');$.each(headerCols,function(indexHeaderCol,headerCol){if(!$(headerCol).hasClass("nosort")){$(headerCol).addClass("sortcol");for(var i=0;i<sortLength;++i){if(sortTypes[i]==headerCol.id){matchIndex=i;break;}}
if(matchIndex<0){for(var i=0;i<sortLength;++i){if($(headerCol).hasClass(sortTypes[i])){matchIndex=i;break;}}}
headerTypeIndexes[headerTypeIndexesCnt++]=matchIndex;matchIndex=-1;}else{headerTypeIndexesCnt++}});});var rows=$(this).children('tbody').children('tr');var analyzeFirstRowContent=true;$.each(rows,function(index,row){var cols=$(row).children('td');var colIndex=0;if(index>0||!ignoreFirstRow){$.each(cols,function(indexCol,col){if(analyzeFirstRowContent){if(headerTypeIndexes[colIndex]<0&&!$(col).hasClass("nosort")){headerTypeIndexes[colIndex]=detectType($(col).text());}}
$(row).data('sortKey'+colIndex,formatKey($(col).text(),headerTypeIndexes[colIndex]));colIndex++;});analyzeFirstRowContent=false;}});var preSortAsc=null;var preSortDesc=null;var colIndex=0;$.each(headCols,function(index,headCol){if(!$(headCol).hasClass('nosort')){if($(headCol).hasClass("sortfirstasc")){preSortAsc=$(headCol);}else{if($(headCol).hasClass("sortfirstdesc")){preSortDesc=$(headCol);}}
$(headCol).bind('click.jqTableKit',function(){if(!sortingEnabled){return;}
var sortAsc=true;if($(headCol).hasClass('sortasc')){cssClassToAdd='sortdesc';sortAsc=false;}else{cssClassToAdd='sortasc';}
headerRows.find('> td, > th').removeClass("sortdesc").removeClass("sortasc");$(headCol).addClass(cssClassToAdd);var closestTable=$(this).closest("table");var trs=closestTable.find('tr');if(settings['stripe']){trs.filter(":odd").removeClass(settings['rowOddClass']);trs.filter(":even").removeClass(settings['rowEvenClass']);}
if(sortAsc){closestTable.jqTableKit('sort',ignoreFirstRow,function(a,b){return functionSortTmp(a,b,index);});}else{closestTable.jqTableKit('sort',ignoreFirstRow,function(a,b){return functionSortTmp(b,a,index);});}
if(settings['stripe']){trs=closestTable.find('tr');trs.filter(":odd").addClass(settings['rowOddClass']);trs.filter(":even").addClass(settings['rowEvenClass']);trs.filter(":first").removeClass(settings['rowEvenClass']);}});};});if(settings['stripe']){$(this).find('tr').filter(":odd").addClass(settings['rowOddClass']);$(this).find('tr').filter(":even").addClass(settings['rowEvenClass']);$(this).find('tr').filter(":first").removeClass(settings['rowEvenClass']);};if(preSortAsc){preSortAsc.removeClass('sortasc').removeClass('sortdesc').addClass('sortdesc').click();}else{if(preSortDesc){preSortDesc.removeClass('sortasc').removeClass('sortdesc').addClass('sortasc').click();}}});},destroy:function(){return this.each(function(){$('th').unbind('.jqTableKit_resize');$('td').unbind('.jqTableKit_resize');$('th').unbind('.jqTableKit');$('td').unbind('.jqTableKit');var rows=$(this).children('tbody').children('tr');var processedOne=false;var numberOfCols=0;$.each(rows,function(index,row){if(!processedOne){var aCol=$(row).find('> td, > th');numberOfCols=aCol.length;}
for(var i=0;i<numberOfCols;i++){$(row).removeData('sortKey'+i);};});});},sort:function(ignoreFirstRow,sortMethod){return this.each(function(){if("table"!=this.nodeName.toLowerCase()){return;}
var rows;if(ignoreFirstRow){rows=$(this).children('tbody').children('tr').not(':first');}else{rows=$(this).children('tbody').children('tr');}
rows.sort(sortMethod);$(this).children('tbody').append(rows);});},unitTestHelper:function(){var unitTest={};unitTest['formatKey']=formatKey;unitTest['detectType']=detectType;return unitTest;}};$.fn.jqTableKit=function(method){if(methods[method]){return methods[method].apply(this,Array.prototype.slice.call(arguments,1));}else if(typeof method==='object'||!method){return methods.init.apply(this,arguments);}else{$.error('Method '+method+' does not exist on jQuery.jqTableKit');}};})(jQuery);jQuery(document).ready(function(){setTimeout(function(){jQuery('table').jqTableKit();},100);});