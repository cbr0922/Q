function CheckLodop(){
   var oldVersion=LODOP.Version;
       newVerion="5.0.2.4";	
   if (oldVersion==null){
	document.write("<h3><font color='#FF00FF'>列印控制項未安裝!點擊這裏<a href='install_lodopEN.exe'>執行安裝</a>,安裝後請刷新頁面。</font></h3>");
	if (navigator.appName=="Netscape")
	document.write("<h3><font color='#FF00FF'>（Firefox瀏覽器用戶需先點擊這裏<a href='npActiveXFirefox4x.xpi'>安裝運行環境</a>）</font></h3>");
   } 
}

