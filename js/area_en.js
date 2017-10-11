<!--
function Link(name, value){
 this.name = name;
 this.value = value;
 this.title = new Array();
 this.url = new Array();
}

var names      = new Array ();
var temp       = new Array ();
var back       = new Array ();
var link       = new Link ();
var final_list = new Array ();

var menu = new Array (

"-Country-|*|-State-",

"United States|United States*|-Select-#"+
"Alabama|Alabama#"+
"Alaska|Alaska#"+
"Arizona|Arizona#"+
"Arkansas|Arkansas#"+
"California|California#"+
"Colorado|Colorado#"+
"Connecticut|Connecticut#"+
"Delaware|Delaware#"+
"District Of Columbia|District Of Columbia#"+
"Florida|Florida#"+
"Georgia|Georgia#"+
"Hawaii|Hawaii#"+
"Idaho|Idaho#"+
"Illinois|Illinois#"+
"Indiana|Indiana#"+
"Iowa|Iowa#"+
"Kansas|Kansas#"+
"Kentucky|Kentucky#"+
"Louisiana|Louisiana#"+
"Maine|Maine#"+
"Maryland|Maryland#"+
"Massachusetts|Massachusetts#"+
"Michigan|Michigan#"+
"Minnesota|Minnesota#"+
"Mississippi|Mississippi#"+
"Missouri|Missouri#"+
"Montana|Montana#"+
"Nebraska|Nebraska#"+
"Nevada|Nevada#"+
"New Hampshire|New Hampshire#"+
"New Jersey|New Jersey#"+
"New Mexico|New Mexico#"+
"New York|New York"+
"North Carolina|North Carolina#"+
"North Dakota|North Dakota#"+
"Ohio|Ohio#"+
"Oklahoma|Oklahoma#"+
"Oregon|Oregon#"+
"Pennsylvania|Pennsylvania#"+
"Rhode Island|Rhode Island#"+
"South Carolina|South Carolina#"+
"South Dakota|South Dakota#"+
"Tennessee|Tennessee#"+
"Texas|Texas#"+
"Utah|Utah#"+
"Vermont|Vermont#"+
"Virginia|Virginia#"+
"Washington|Washington#"+
"West Virginia|West Virginia#"+
"Wisconsin|Wisconsin#"+
"Wyoming|Wyoming#"+
"Puerto Rico|Puerto Rico#"+
"Virgin Island|Virgin Island#"+
"Northern Mariana Islands|Northern Mariana Islands#"+
"Guam|Guam#"+
"American Samoa|American Samoa",




"Canada|Canada*|-Select-#"+
"Alberta|Alberta#"+
"British Columbia|British Columbia#"+
"New Brunswick|New Brunswick#"+
"Newfoundland and Labrador|Newfoundland and Labrador#"+
"Nova Scotia|Nova Scotia#"+
"Nunavut|Nunavut#"+
"N·W·T·|N·W·T·#"+
"Ontario|Ontario#"+
"Prince Edward Island|Prince Edward Island#"+
"Quebec|Quebec#"+
"Saskatchewan|Saskatchewan#"+
"Yukon|Yukon",




"United Kingdom|United Kingdom*|-County-#"+
"Avon|Avon#"+
"Bedfordshire|Bedfordshire#"+
"Berkshire|Berkshire#"+
"Bristol|Bristol#"+
"Buckinghamshire|Buckinghamshire#"+
"Cambridgeshire|Cambridgeshire#"+
"Cheshire|Cheshire#"+
"Cleveland|Cleveland#"+
"Cornwall|Cornwall#"+
"Cumbria|Cumbria#"+
"Derbyshire|Derbyshire#"+
"Devon|Devon#"+
"Dorset|Dorset#"+
"Durham|Durham#"+
"East Riding of Yorkshire|East Riding of Yorkshire#"+
"East Sussex|East Sussex#"+
"Essex|Essex#"+
"Gloucestershire|Gloucestershire#"+
"Greater Manchester|Greater Manchester#"+
"Hampshire|Hampshire#"+
"Herefordshire|Herefordshire#"+
"Humberside|Humberside#"+
"Isle of Wight|Isle of Wight#"+
"Isles of Scilly|Isles of Scilly#"+
"Kent|Kent#"+
"Lancashire|Lancashire#"+
"Leicestershire|Leicestershire#"+
"Lincolnshire|Lincolnshire#"+
"London|London#"+
"Merseyside|Merseyside#"+
"Middlesex|Middlesex#"+
"Norfolk|Norfolk#"+
"North Yorkshire|North Yorkshire#"+
"Northamptonshire|Northamptonshire#"+
"Northumberland|Northumberland#"+
"Nottinghamshire|Nottinghamshire#"+
"Oxfordshire|Oxfordshire#"+
"Rutland|Rutland#"+
"Shropshire|Shropshire#"+
"Somerset|Somerset#"+
"South Yorkshire|South Yorkshire#"+
"Staffordshire|Staffordshire#"+
"Suffolk|Suffolk#"+
"Surrey|Surrey#"+
"Tyne and Wear|Tyne and Wear#"+
"Warwickshire|Warwickshire#"+
"West Midlands|West Midlands#"+
"West Sussex|West Sussex#"+
"West Yorkshire|West Yorkshire#"+
"Wiltshire|Wiltshire#"+
"Worcestershire|Worcestershire#"+
"|#"+
"-- Northern Ireland --|-- Northern Ireland --#"+
"Antrim|Antrim#"+
"Armagh|Armagh#"+
"Down|Down#"+
"Fermanagh|Fermanagh#"+
"Londonderry|Londonderry#"+
"Tyrone|Tyrone#"+
"|#"+
"-- Scotland --|-- Scotland --#"+
"Aberdeen City|Aberdeen City#"+
"Aberdeenshire|Aberdeenshire#"+
"Angus|Angus#"+
"Argyll and Bute|Argyll and Bute#"+
"Borders|Borders#"+
"Clackmannan|Clackmannan#"+
"Dumfries and Galloway|Dumfries and Galloway#"+
"Dundee (City of)|Dundee (City of)#"+
"East Ayrshire|East Ayrshire#"+
"East Dunbartonshire|East Dunbartonshire#"+
"East Lothian|East Lothian#"+
"East Renfrewshire|East Renfrewshire#"+
"Edinburgh (City of)|Edinburgh (City of)#"+
"Falkirk|Falkirk#"+
"Fife|Fife#"+
"Glasgow (City of)|Glasgow (City of)#"+
"Highland|Highland#"+
"Inverclyde|Inverclyde#"+
"Midlothian|Midlothian#"+
"Moray|Moray#"+
"North Ayrshire|North Ayrshire#"+
"North Lanarkshire|North Lanarkshire#"+
"Orkney|Orkney#"+
"Perthshire and Kinross|Perthshire and Kinross#"+
"Renfrewshire|Renfrewshire#"+
"Shetland|Shetland#"+
"South Ayrshire|South Ayrshire#"+
"South Lanarkshire|South Lanarkshire#"+
"Stirling|Stirling#"+
"West Dunbartonshire|West Dunbartonshire#"+
"West Lothian|West Lothian#"+
"Western Isles|Western Isles#"+
"|#"+
"-- Unitary Authorities of Wales --|-- Unitary Authorities of Wales --#"+
"Blaenau Gwent|Blaenau Gwent#"+
"Bridgend|Bridgend#"+
"Caerphilly|Caerphilly#"+
"Cardiff|Cardiff#"+
"Carmarthenshire|Carmarthenshire#"+
"Ceredigion|Ceredigion#"+
"Conwy|Conwy#"+
"Denbighshire|Denbighshire#"+
"Flintshire|Flintshire#"+
"Gwynedd|Gwynedd#"+
"Isle of Anglesey|Isle of Anglesey#"+
"Merthyr Tydfil|Merthyr Tydfil#"+
"Monmouthshire|Monmouthshire#"+
"Neath Port Talbot|Neath Port Talbot#"+
"Newport|Newport#"+
"Pembrokeshire|Pembrokeshire#"+
"Powys|Powys#"+
"Rhondda Cynon Taff|Rhondda Cynon Taff#"+
"Swansea|Swansea#"+
"Tofaen|Tofaen#"+
"The Vale of Glamorgan|The Vale of Glamorgan#"+
"Wrexham|Wrexham#"+
"|#"+
"-- UK Offshore Dependencies --|-- UK Offshore Dependencies --#"+
"Channel Islands|Channel Islands#"+
"Isle of Man|Isle of Man",




"China|China*|-请选择-#"+
"北京市|北京市#"+
"上海市|上海市#"+
"天津市|天津市#"+
"重庆市|重庆市#"+
"香港特区|香港特区#"+
"澳门特区|澳门特区#"+
"河北省|河北省#"+ 
"山西省|山西省#"+
"四川省|四川省#"+
"河南省|河南省#"+
"辽宁省|辽宁省#"+
"吉林省|吉林省#"+
"黑龙江省|黑龙江省#"+
"内蒙古区|内蒙古区#"+
"江苏省|江苏省#"+
"山东省|山东省#"+
"安徽省|安徽省#"+
"浙江省|浙江省#"+
"福建省|福建省#"+
"湖北省|湖北省#"+
"湖南省|湖南省#"+
"海南省|海南省#"+
"广东省|广东省#"+
"江西省|江西省#"+
"贵州省|贵州省#"+
"云南省|云南省#"+
"陕西省|陕西省#"+
"甘肃省|甘肃省#"+
"广西区|广西区#"+
"宁夏区|宁夏区#"+
"青海省|青海省#"+
"新疆区|新疆区#"+
"西藏区|西藏区#"+
"台湾省|台湾省",


"Other Country|Other Country*|-Select-#"+
"APO/FPO|APO/FPO#"+
"Afghanistan|Afghanistan#"+
"Albania|Albania#"+
"Algeria|Algeria#"+
"American Samoa|American Samoa#"+
"Andorra|Andorra#"+
"Angola|Angola#"+
"Anguilla|Anguilla#"+
"Antigua and Barbuda|Antigua and Barbuda#"+
"Argentina|Argentina#"+
"Armenia|Armenia#"+
"Aruba|Aruba#"+
"Australia|Australia#"+
"Austria|Austria#"+
"Azerbaijan Republic|Azerbaijan Republic#"+
"Bahamas|Bahamas#"+
"Bahrain|Bahrain#"+
"Bangladesh|Bangladesh#"+
"Barbados|Barbados#"+
"Belarus|Belarus#"+
"Belgium|Belgium#"+
"Belize|Belize#"+
"Benin|Benin#"+
"Bermuda|Bermuda#"+
"Bhutan|Bhutan#"+
"Bolivia|Bolivia#"+
"Bosnia and Herzegovina|Bosnia and Herzegovina#"+
"Botswana|Botswana#"+
"Brazil|Brazil#"+
"British Virgin Islands|British Virgin Islands#"+
"Brunei Darussalam|Brunei Darussalam#"+
"Bulgaria|Bulgaria#"+ 
"Burkina Faso|Burkina Faso#"+
"Burma|Burma#"+
"Burundi|Burundi#"+
"Cambodia|Cambodia#"+
"Cameroon|Cameroon#"+
"Cape Verde Islands|Cape Verde Islands#"+ 
"Cayman Islands|Cayman Islands#"+ 
"Central African Republic|Central African Republic#"+
"Chad|Chad#"+
"Chile|Chile#"+
"Colombia|Colombia#"+
"Comoros|Comoros#"+
"Congo, Democratic Republic of the|Congo, Democratic Republic of the#"+
"Congo, Republic of the|Congo, Republic of the#"+ 
"Cook Islands|Cook Islands#"+ 
"Costa Rica|Costa Rica#"+
"Cote d Ivoire (Ivory Coast)|Cote d Ivoire (Ivory Coast)#"+
"Croatia, Republic of|Croatia, Republic of#"+
"Cyprus|Cyprus#"+
"Czech Republic|Czech Republic#"+
"Denmark|Denmark#"+
"Djibouti|Djibouti#"+
"Dominica|Dominica#"+
"Dominican Republic|Dominican Republic#"+
"Ecuador|Ecuador#"+
"Egypt|Egypt#"+
"El Salvador|El Salvador#"+
"Equatorial Guinea|Equatorial Guinea#"+
"Eritrea|Eritrea#"+
"Estonia|Estonia#"+
"Ethiopia|Ethiopia#"+
"Falkland Islands (Islas Malvinas)|Falkland Islands (Islas Malvinas)#"+
"Fiji|Fiji#"+
"Finland|Finland#"+
"France|France#"+
"French Guiana|French Guiana#"+
"French Polynesia|French Polynesia#"+
"Gabon Republic|Gabon Republic#"+
"Gambia|Gambia#"+
"Georgia|Georgia#"+
"Germany|Germany#"+
"Ghana|Ghana#"+
"Gibraltar|Gibraltar#"+
"Greece|Greece#"+
"Greenland|Greenland#"+
"Grenada|Grenada#"+
"Guadeloupe|Guadeloupe#"+
"Guam|Guam#"+
"Guatemala|Guatemala#"+
"Guernsey|Guernsey#"+
"Guinea|Guinea#"+
"Guinea-Bissau|Guinea-Bissau#"+
"Guyana|Guyana#"+
"Haiti|Haiti#"+
"Honduras|Honduras#"+
"Hungary|Hungary#"+
"Iceland|Iceland#"+
"India|India#"+
"Indonesia|Indonesia#"+
"Ireland|Ireland#"+
"Israel|Israel#"+
"Italy|Italy#"+
"Jamaica|Jamaica#"+
"Jan Mayen|Jan Mayen#"+
"Japan|Japan#"+
"Jersey|Jersey#"+
"Jordan|Jordan#"+
"Kazakhstan|Kazakhstan#"+
"Kenya Coast Republic|Kenya Coast Republic#"+
"Kiribati|Kiribati#"+
"Korea, South|Korea, South#"+
"Kuwait|Kuwait#"+
"Kyrgyzstan|Kyrgyzstan#"+
"Laos|Laos#"+
"Latvia|Latvia#"+
"Lebanon|Lebanon#"+
"Liechtenstein|Liechtenstein#"+
"Lithuania|Lithuania#"+
"Luxembourg|Luxembourg#"+
"Macedonia|Macedonia#"+
"Madagascar|Madagascar#"+
"Malawi|Malawi#"+
"Malaysia|Malaysia#"+
"Maldives|Maldives#"+
"Mali|Mali#"+
"Malta|Malta#"+
"Marshall Islands|Marshall Islands#"+
"Martinique|Martinique#"+
"Mauritania|Mauritania#"+
"Mauritius|Mauritius#"+
"Mayotte|Mayotte#"+
"Mexico|Mexico#"+
"Micronesia|Micronesia#"+
"Moldova|Moldova#"+
"Monaco|Monaco#"+
"Mongolia|Mongolia#"+
"Montserrat|Montserrat#"+
"Morocco|Morocco#"+
"Mozambique|Mozambique#"+
"Namibia|Namibia#"+
"Nauru|Nauru#"+
"Nepal|Nepal#"+
"Netherlands|Netherlands#"+
"Netherlands Antilles|Netherlands Antilles#"+
"New Caledonia|New Caledonia#"+
"New Zealand|New Zealand#"+
"Nicaragua|Nicaragua#"+
"Niger|Niger#"+
"Nigeria|Nigeria#"+
"Niue|Niue#"+
"Norway|Norway#"+
"Oman|Oman#"+
"Pakistan|Pakistan#"+
"Palau|Palau#"+
"Panama|Panama#"+
"Papua New Guinea|Papua New Guinea#"+
"Paraguay|Paraguay#"+
"Peru|Peru#"+
"Philippines|Philippines#"+
"Poland|Poland#"+
"Portugal|Portugal#"+
"Puerto Rico|Puerto Rico#"+
"Qatar|Qatar#"+
"Romania|Romania#"+
"Russian Federation|Russian Federation#"+
"Rwanda|Rwanda#"+
"Saint Helena|Saint Helena#"+
"Saint Kitts-Nevis|Saint Kitts-Nevis#"+
"Saint Lucia|Saint Lucia#"+
"Saint Pierre and Miquelon|Saint Pierre and Miquelon#"+
"Saint Vincent and the Grenadines|Saint Vincent and the Grenadines#"+
"San Marino|San Marino#"+
"Saudi Arabia|Saudi Arabia#"+
"Senegal|Senegal#"+
"Seychelles|Seychelles#"+
"Sierra Leone|Sierra Leone#"+
"Singapore|Singapore#"+
"Slovakia|Slovakia#"+
"Slovenia|Slovenia#"+
"Solomon Islands|Solomon Islands#"+
"Somalia|Somalia#"+
"South Africa|South Africa#"+
"Spain|Spain#"+
"Sri Lanka|Sri Lanka#"+
"Suriname|Suriname#"+
"Svalbard|Svalbard#"+
"Swaziland|Swaziland#"+
"Sweden|Sweden#"+
"Switzerland|Switzerland#"+
"Syria|Syria#"+
"Tahiti|Tahiti#"+
"Taiwan|Taiwan#"+
"Tajikistan|Tajikistan#"+
"Tanzania|Tanzania#"+
"Thailand|Thailand#"+
"Togo|Togo#"+
"Tonga|Tonga#"+
"Trinidad and Tobago|Trinidad and Tobago#"+
"Tunisia|Tunisia#"+
"Turkey|Turkey#"+
"Turkmenistan|Turkmenistan#"+
"Turks and Caicos Islands|Turks and Caicos Islands#"+
"Tuvalu|Tuvalu#"+
"Uganda|Uganda#"+
"Ukraine|Ukraine#"+
"United Arab Emirates|United Arab Emirates#"+
"Uruguay|Uruguay#"+
"Uzbekistan|Uzbekistan#"+
"Vanuatu|Vanuatu#"+
"Vatican City State|Vatican City State#"+
"Venezuela|Venezuela#"+
"Vietnam|Vietnam#"+
"Virgin Islands (U.S.)|Virgin Islands (U.S.)#"+
"Wallis and Futuna|Wallis and Futuna#"+
"Western Sahara|Western Sahara#"+
"Western Samoa|Western Samoa#"+
"Yemen|Yemen#"+
"Yugoslavia|Yugoslavia#"+
"Zambia|Zambia#"+
"Zimbabwe|Zimbabwe"

);

function updateMenus ( what ) {
	//form1.othercity.value="";
   var n = document.form1.province.selectedIndex;
   document.form1.city.length = final_list[n].title.length;
   for (var x = 0; x < document.form1.city.length; x++)   {
      document.form1.city.options[x].text = final_list[n].title[x];
      document.form1.city.options[x].value = final_list[n].url[x];
   }
   document.form1.city.selectedIndex = 0;
}

function give_names (prov1,city1) {
 document.form1.province.length = names.length;
 var j;
 j = 0;
 for ( var i=0; i<names.length; i++ )
  document.form1.province.options[i].text = final_list[i].name;
 for ( var i=0; i<names.length; i++ ){
  document.form1.province.options[i].value = final_list[i].value;
  if(prov1==final_list[i].value){
	form1.province.selectedIndex=i;
	j = i;
  }
 }
 document.form1.city.length = final_list[j].title.length;

 for (var x=0; x<final_list[j+0].url.length; x++){
  document.form1.city.options[x].value = final_list[j+0].url[x];
  if(city1==final_list[j+0].url[x]){
	form1.city.selectedIndex = x;
	//changecity(document.form1.city.options[x].value);
  }
 }
 for (var x=0; x<final_list[j+0].title.length; x++){
  document.form1.city.options[x].text = final_list[j+0].title[x];
 }
}

function createMenus (prov1,city1,prov2,city2) {
 for ( var i=0; i < menu.length; i++ ) {
  names[i] = menu[i].split("*");
  link = new Link(names[i][0].split("|")[0],names[i][0].split("|")[1]);	
  temp[i] = names[i][1].split("#");
  final_list[i] = link;
  for (var x=0; x<temp[i].length; x++)  {
   back[x]  = temp[i][x].split("|");
   final_list[i].url[x] = back[x][0];
   final_list[i].title[x] = back[x][1];
  }
 }
give_names(prov1,city1);

 for ( var i=0; i < menu.length; i++ ) {
  names[i] = menu[i].split("*");
  link = new Link(names[i][0].split("|")[0],names[i][0].split("|")[1]);
  temp[i] = names[i][1].split("#");
  final_list[i] = link;
  for (var x=0; x<temp[i].length; x++)  {
   back[x]  = temp[i][x].split("|");
   final_list[i].url[x] = back[x][0];
   final_list[i].title[x] = back[x][1];
  }
 }
}

function changecity(v)
{
  //form1.othercity.value="";
}

//-->