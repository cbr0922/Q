<?php

require_once(dirname(__FILE__)."/../../configs.inc.php");error_reporting(-1);require_once("mat.php");require_once("libcron.php");SMSP_load_mods();function
show_menu(){$G=basename(__FILE__);$e=array(array("cron 模組首頁",$G.""),array("安裝設定",$G."?action=setup"),array("紀錄列表",$G."?action=errlist"),array("紀錄追蹤",$G."?action=trace"),);global$_GET;$ca=isset($_GET['action'])?$_GET['action']:"";$N="";foreach($e
as$z){$P="";$R=str_replace("?action=","",$z[1]);$R=str_replace($G,"",$R);if($R==$ca)$P="font-weight: bold;";$N.=sprintf("<li><a href=\"%s\" style=\"$P\">%s</a></li>",$z[1],$z[0]);}$ka=<<<EOD
<style>
ul
{
list-style-type:none;
margin:10;
padding:0;
}
li
{
display:inline;
margin-left: 30px;
padding:10px;
background-color: dddddd;
}

ul li a:link    {text-decoration:none;     color:black;}
ul li a:visited {text-decoration:none;     color:black;}
ul li a:hover   {text-decoration:underline;color:black;}
ul li a:active  {text-decoration:underline;color:black;}
</style>
<ul>
%s
</ul>
<hr/>
EOD;
$ja=sprintf($ka,$N);echo$ja;}function
chk_apikey($la){$F=matConf::getConf('mod.cron.apikey');$ma="cron";$va=get_info_conf_from_file("site_userc");$M=get_info_conf_from_file("site_url");$M=strtolower($M);$M=str_replace("/","",$M);$M=str_replace("http:","",$M);$M=str_replace("www.","",$M);$k=substr(md5($ma.$va.$M),0,3);for($w=0;$w<15;$w+=3){if(substr($la,0,$w+3)!=substr($k,0,$w+3))exit();if(!defined("def_".$F))define("def_".$F,$w);$k.=substr(md5($k),0,3);}}function
do_setup($m=false){$g=matConf::getConf('mod.cron.TEMP_DIR');$d=matConf::getConf('mod.cron.LOGS_DIR');$a=matConf::getConf('mod.cron.CRONTAB_PATH');$f="<em style='color:white; background-color:green;' >OK</em>";$c="<em style='color:white; background-color:red;'   >FAILED</em>";$x=array();array_push($x,array("item"=>"執行使用者","content"=>get_current_user(),"status"=>""));array_push($x,array("item"=>"網站頂層目錄","content"=>realpath(RootDocument),"status"=>""));if($m)prepare_dir($g);$O=is_writable($g)?"$f":"$c";array_push($x,array("item"=>"暫存目錄寫入權限","content"=>"<網站頂層目錄>".str_replace(realpath(RootDocument),"",realpath($g)),"status"=>$O));if($m)prepare_dir($d);$O=is_writable($d)?"$f":"$c";array_push($x,array("item"=>"暫存紀錄檔目錄寫入權限","content"=>"<網站頂層目錄>".str_replace(realpath(RootDocument),"",realpath($d)),"status"=>$O));if($m){if(!file_exists($a))file_put_contents($a,"");}$O=file_exists($a)?"$f":"$c";array_push($x,array("item"=>"定期排程指定工作資料設定檔","content"=>"<網站頂層目錄>".str_replace(realpath(RootDocument),"",realpath($a)),"status"=>$O));print_table_multi($x);echo"<hr/>";$pa=<<<EOD
安裝完成後，請記得於主機之定期排程設定中，加上下列設定:<br/>
<pre>
* * * * * php %s
</pre>
EOD;
$oa=sprintf($pa,__FILE__);echo$oa;}function
do_process(){global$INFO;global$a;global$b;$n=array();$na=explode("\n",file_get_contents($a));foreach($na
as$B){$B=trim($B);if(empty($B))continue;if(preg_match("~^\\s*#.*~",$B)>0)continue;$I=preg_split('/\s+/',$B);print_r($I);if(count($I)>=5){$l=array();$l["timespec"]=implode(" ",array_slice($I,0,5));$qa=strpos($B,$I[5]);$l["command"]=substr($B,$qa);array_push($n,$l);}}foreach($n
as$l){$da=$b-($b%60);$Q=date('c',$da);$ua=substr(md5($l['command']),0,7);$p=get_cron_code($ua);$ba=$l['timespec'];if(strpos($l['command'],"?")===false)$q=$l['command']."?cron_code=$p";else$q=$l['command']."&cron_code=$p";$q=str_replace("SITE_URL",$INFO['site_url'],$q);$E=get_path_from_cron_code($p);if(file_exists($E))continue;if(parse_crontab($ba,$Q)){prepare_dir(dirname($E));file_put_contents($E,"");echo"存取網頁: $q <br/>\n";$o=curl_call($q,"get");if($o===false)$o="Error. time=$Q , cron_code=$p, command=$q ";file_put_contents($E,$o);}}}function
do_errlist(){$G=basename(__FILE__);$d=matConf::getConf("mod.cron.LOGS_DIR");if(!file_exists($d)){echo"紀錄檔暫存目錄不存在，請先進行安裝動作。";return;}$Z=array();$s=array();$ga=scandir($d);foreach($ga
as$t){if($t=="."||$t=="..")continue;$r=path_join($d,$t);array_push($Z,$t);$ha=scandir($r);foreach($ha
as$u){if($u=="."||$u=="..")continue;$ea=path_join($r,$u);$h=explode("_",$u);if(count($h)===3){$Y=$t;$ya=$h[0];$wa=$h[1];$xa=$h[2];array_push($s,array($Y,$ya,$wa,$xa));}}}$X=isset($_REQUEST['year'])?$_REQUEST['year']:NULL;$W=isset($_REQUEST['week'])?$_REQUEST['week']:NULL;if(empty($X)&&empty($W)){$j=count($s);if($j>0){$A=$s[$j-1];$X=$A[0];$W=$A[1]."_".$A[2]."_".$A[3];}}$H="<option value='' >--請選擇--</option>";foreach($Z
as$Y){$L="";if($Y==$X)$L="selected";$H.=sprintf("<option value=\"%s\" $L >%s</option>",$Y,$Y);}$_=json_encode($s);$y=<<<EOD
<script>
yw_data = %s ;
cur_week = "%s";

function refresh_week(){

	cur_year = document.getElementById('the_year').value;
	//cur_week = document.getElementById('the_week').value;

	opt_str = "<option value='' >--請選擇--</option>";

	for( i=0; i< yw_data.length; i++ ){

		item = yw_data[i];
		
		selected_txt = "";

		if ( item[0] == cur_year ){
			value = item[1] + "_" + item[2] + "_" + item[3];
			txt = "第 "+item[1] + " 週 ( " + item[2] + " ~ " + item[3] + " )";

			if ( value == cur_week ){ 
				selected_txt = "selected";
			}

			opt_str += "<option value='"+value+"' "+ selected_txt +" >"+txt+"</option>";
		}
	}

	document.getElementById('the_week').innerHTML = opt_str;
}

window.onload = function(){
	refresh_week();
}

</script>
		<form action="" method="get">
		<input type="hidden" name="action" value="errlist" />
		年份:     <select id="the_year" name="year" value="%s" onchange="javascript:refresh_week();" > %s </select>
		時段(週): <select id="the_week" name="week" value="%s"></select>
		<br/>
		<input type="submit" value="提交"/>
		</form>
EOD;

echo
sprintf($y,$_,$W,$X,$H,$W);if(empty($X)||empty($W)){echo"<span>無資料。(可能未選擇年份或週數，或是系統內無資料)";return;}$D=array();$fa=path_join($d,$X,$W);$ta=scandir($fa);foreach($ta
as$v){if($v=="."||$v=="..")continue;$h=explode('_',$v);$i=explode('_',$W);$K=explode("__",base64_decode($J['cron_code']));$J['year']=$X;$J['week_nth']=$i[0];$J['week_beg']=$i[1];$J['week_end']=$i[2];$J['time']=$h[0];$J['cron_code']=substr($h[1],0,-4);$J['seed']=$K[1];$C=sprintf("<a href=\"%s\" >[詳細資料]</a>",$G."?action=trace&cron_code=".$J['cron_code']);array_push($D,array("year"=>$J['year'],"week_nth"=>$J['week_nth'],"time"=>$J['time'],"seed"=>$J['seed'],"link"=>$C,));}print_table_multi($D);}function
do_trace(){$p=isset($_REQUEST['cron_code'])?$_REQUEST['cron_code']:NULL;if(!empty($p)){$ia=get_cron_log_file($p);$y="<textarea readonly wrap='off' style='width:100%; height:100%;'>".$ia."</textarea>";echo$y;return;}else{$y=<<<EOD
			<form action="" method="get">
			紀錄序號: <input type="text" name="cron_code" />
			<br/>
			<input type="hidden" name="action" value="trace" />
			<input type="submit" value="提交"/>
			</form>
EOD;

echo$y;return;}echo"<hr/>","<hr/>";}function
do_readme(){$S=get_version("libMat");$U=get_version("mod.cron");$T="無";if($S!=NULL)$T=$S['full'];$V="無";if($U!=NULL)$V=$U['full'];$sa=<<<EOD
定時排程模組版本: %s <br/>
libMat 版本: %s <br/>
EOD;
$ra=sprintf($sa,$V,$T);echo$ra;}$b=time();$g=dirname(__FILE__)."/../../UploadFile/cron";$d=path_join($g,"logs");$a=path_join($g,"crontab");matConf::setConf('mod.cron.TEMP_DIR',$g);matConf::setConf('mod.cron.LOGS_DIR',$d);matConf::setConf('mod.cron.CRONTAB_PATH',$a);$F=matConf::getConf('mod.cron.apikey');chk_apikey($F);if(isCLI())$_GET['action']="process";$aa=isset($_GET['action'])?$_GET['action']:"";switch($aa){case"process":{$F=matConf::getConf('mod.cron.apikey');if(!defined("def_".$F))echo"ERROR: API KEY MISMATCH!!";do_process();}break;case"setup":{$F=matConf::getConf('mod.cron.apikey');if(!defined("def_".$F))echo"ERROR: API KEY MISMATCH!!";$m=(isset($_REQUEST['confirm'])&&$_REQUEST['confirm']=="yes")?true:false;show_menu();if(!$m){echo"模式: 唯讀模式(只作檢查，不更動到系統)","<br/>";$G=basename(__FILE__);echo
sprintf("<br/><a href=\"%s\" >確認執行安裝...</a><br/><span>(注意！將會更動檔案及目錄，和資料庫的欄位)</span><br/><br/>",$G."?action=setup&confirm=yes");}else{echo"模式: 安裝模式","<br/>";}do_setup($m);}break;case"errlist":{$F=matConf::getConf('mod.cron.apikey');if(!defined("def_".$F))echo"ERROR: API KEY MISMATCH!!";show_menu();do_errlist();}break;case"trace":{$F=matConf::getConf('mod.cron.apikey');if(!defined("def_".$F))echo"ERROR: API KEY MISMATCH!!";show_menu();do_trace();}break;default:{show_menu();do_readme();}break;}?>
