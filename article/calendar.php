<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
include ("configs.inc.php");
include (RootDocumentAdmin."/inc/global.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<script type="text/javascript">
var $ = function (id) {
return "string" == typeof id ? document.getElementById(id) : id;
};
var Class = {
create: function() {
return function() {
this.initialize.apply(this, arguments);
}
}
}
var Extend = function(destination, source) {
for (var property in source) {
destination[property] = source[property];
}
return destination;
}
var Calendar = Class.create();
Calendar.prototype = {
initialize: function(container, options) {
this.Container = $(container);//容器(table結構)
this.Days = [];//日期對象清單
this.SetOptions(options);
this.Year = this.options.Year || new Date().getFullYear();
this.Month = this.options.Month || new Date().getMonth() + 1;
this.SelectDay = this.options.SelectDay ? new Date(this.options.SelectDay) : null;
this.onSelectDay = this.options.onSelectDay;
this.onToday = this.options.onToday;
this.onFinish = this.options.onFinish;
this.Draw();
},
//設置預設屬性
SetOptions: function(options) {
this.options = {//預設值
Year:			0,//顯示年
Month:			0,//顯示月
SelectDay:		null,//選擇日期
onSelectDay:	function(){},//在選擇日期觸發
onToday:		function(){},//在當天日期觸發
onFinish:		function(){}//日曆畫完後觸發
};
Extend(this.options, options || {});
},
//當前月
NowMonth: function() {
this.PreDraw(new Date());
},
//上一月
PreMonth: function() {
this.PreDraw(new Date(this.Year, this.Month - 2, 1));
},
//下一月
NextMonth: function() {
this.PreDraw(new Date(this.Year, this.Month, 1));
},
//上一年
PreYear: function() {
this.PreDraw(new Date(this.Year - 1, this.Month - 1, 1));
},
//下一年
NextYear: function() {
this.PreDraw(new Date(this.Year + 1, this.Month - 1, 1));
},
//根據日期畫日曆
PreDraw: function(date) {
//再設置屬性
this.Year = date.getFullYear(); this.Month = date.getMonth() + 1;
//重新畫日曆
this.Draw();
},
//畫日曆
Draw: function() {
//用來保存日期列表
var arr = [];
//用當月第一天在一周中的日期值作為當月離第一天的天數
for(var i = 1, firstDay = new Date(this.Year, this.Month - 1, 1).getDay(); i <= firstDay; i++){ arr.push(0); }
//用當月最後一天在一個月中的日期值作為當月的天數
for(var i = 1, monthDay = new Date(this.Year, this.Month, 0).getDate(); i <= monthDay; i++){ arr.push(i); }
//清空原來的日期物件清單
this.Days = [];
//插入日期
var frag = document.createDocumentFragment();
while(arr.length){
//每個星期插入一個tr
var row = document.createElement("tr");
//每個星期有7天
for(var i = 1; i <= 7; i++){
var cell = document.createElement("td"); cell.innerHTML = "&nbsp;";
if(arr.length){
var d = arr.shift();
if(d){
cell.innerHTML = d;
this.Days[d] = cell;
var on = new Date(this.Year, this.Month - 1, d);
//判斷是否今日
this.IsSame(on, new Date()) && this.onToday(cell);
//判斷是否選擇日期
this.SelectDay && this.IsSame(on, this.SelectDay) && this.onSelectDay(cell);
}
}
row.appendChild(cell);
}
frag.appendChild(row);
}
//先清空內容再插入(ie的table不能用innerHTML)
while(this.Container.hasChildNodes()){ this.Container.removeChild(this.Container.firstChild); }
this.Container.appendChild(frag);
//附加程式
this.onFinish();
},
//判斷是否同一日
IsSame: function(d1, d2) {
return (d1.getFullYear() == d2.getFullYear() && d1.getMonth() == d2.getMonth() && d1.getDate() == d2.getDate());
}
}
</script>
<style type="text/css">
.Calendar {
font-family:Verdana;
font-size:12px;
background-color:#e0ecf9;
text-align:center;
width:200px;
height:160px;
padding:10px;
line-height:1.5em;
}
.Calendar a{
color:#1e5494;
}
.Calendar table{
width:100%;
border:0;
}
.Calendar table thead{color:#acacac;}
.Calendar table td {
font-size: 11px;
padding:1px;
}
#idCalendarPre{
cursor:pointer;
float:left;
padding-right:5px;
}
#idCalendarNext{
cursor:pointer;
float:right;
padding-right:5px;
}
#idCalendar td.onToday {
font-weight:bold;
color:#C60;
}
#idCalendar td.onSelect {
font-weight:bold;
}
</style>
<div class="Calendar">
<div id="idCalendarPre">&lt;&lt;</div>
<div id="idCalendarNext">&gt;&gt;</div>
<span id="idCalendarYear"></span>年 <span id="idCalendarMonth"></span>月
<table cellspacing="0">
<thead>
<tr>
<td>日</td>
<td>一</td>
<td>二</td>
<td>三</td>
<td>四</td>
<td>五</td>
<td>六</td>
</tr>
</thead>
<tbody id="idCalendar">
</tbody>
</table>
</div>
<input id="idCalendarPreYear" type="button" value="上一年" />
<input id="idCalendarNow" type="button" value="當前月" />
<input id="idCalendarNextYear" type="button" value="下一年" />
<script language="JavaScript">
//var dateflag = new Array();
var flag;
<?php
$Query = $DB->query("select * from `{$INFO[DBPrefix]}news` where niffb=1");
$Num   = $DB->num_rows($Query);
$datearray = array();
while ($Rs=$DB->fetch_array($Query)) {
	$year = substr($Rs['nidate'],0,4);
	$month = substr($Rs['nidate'],5,2);
	$datearray[$year][$month][count($datearray[$year][$month])] = substr($Rs['pubdate'],8,2);
}
echo "var dateflag = new Array(" . count($datearray) . ");";;
foreach($datearray as $k=>$v){
	echo "dateflag[$k] = new Array(" . count($v) . ");";
	foreach($v as $kk=>$vv){
		array_unique($vv);
		$datearray[$k][$kk] = implode(",",$vv);
		echo "dateflag[" . $k . "][" . $kk . "] = [" . $datearray[$k][$kk] . "];\n";
	}
}
?>
var cale = new Calendar("idCalendar", {
SelectDay: new Date().setDate(10),
onSelectDay: function(o){ o.className = "onSelect"; },
onToday: function(o){ o.className = "onToday"; },
onFinish: function(){
$("idCalendarYear").innerHTML = this.Year; $("idCalendarMonth").innerHTML = this.Month;
//var flag = [10,15,20];
for (var key in dateflag){
    if (key == this.Year){
		for (var key2 in dateflag[key]){	
			if (key2 == this.Month){
				flag = dateflag[this.Year][this.Month];
				for(var i = 0, len = flag.length; i < len; i++){
				this.Days[flag[i]].innerHTML = "<a href='<?php echo $INFO[site_url];?>/article/article_c_list.php?date=" + this.Year+"-"+this.Month+"-"+flag[i]+ "' target='_parent'>" + flag[i] + "</a>";
				}
			}
		}
	}
}



}
});
$("idCalendarPre").onclick = function(){ cale.PreMonth(); }
$("idCalendarNext").onclick = function(){ cale.NextMonth(); }
$("idCalendarPreYear").onclick = function(){ cale.PreYear(); }
$("idCalendarNextYear").onclick = function(){ cale.NextYear(); }
$("idCalendarNow").onclick = function(){ cale.NowMonth(); }
</script>
</body>
</html>
