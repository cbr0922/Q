<?php

require_once(dirname(__FILE__)."/../../configs.inc.php");error_reporting(-1);require_once("crypt.class.php");require_once("mat.php");require_once("libeinvoice.php");SMSP_load_mods();function
show_menu(){$F=basename(__FILE__);$aa=array(array("einvoice 模組首頁",$F.""),array("安裝設定",$F."?action=setup"),array("監控列表",$F."?action=errlist"),array("訂單追蹤",$F."?action=trace"),);global$_GET;$ka=isset($_GET['action'])?$_GET['action']:"";$qb="";foreach($aa
as$Fa){$rb="";$vb=str_replace("?action=","",$Fa[1]);$vb=str_replace($F,"",$vb);if($vb==$ka)$rb="font-weight: bold;";$qb.=sprintf("<li><a href=\"%s\" style=\"$rb\">%s</a></li>",$Fa[1],$Fa[0]);}$Aa=<<<EOD
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
$_a=sprintf($Aa,$qb);echo$_a;}function
scan_and_mark_orders(){$a=matConf::getConf('info.DBPrefix');$i=matConf::getConf('mod.einvoice.begindate');$p=matDB::getDB();$h=strtotime($i);$J="SELECT * FROM `".$a."order_table` AS ot "." WHERE createtime >= $h"."   AND ot.pay_state=1 "."   AND ot.ifeinvoice IS NULL "." LIMIT 100 ";$O=$p->GetAll($J);foreach((array)$O
as$N)mark_use_einvoice($N['order_serial']);}function
new_info(){$_=matConf::getConf('mod.einvoice.invdate');$a=matConf::getConf('info.DBPrefix');$p=matDB::getDB();$J="SELECT * FROM `".$a."order_table` AS ot "." LEFT JOIN einvoice_info AS ei ON ot.order_serial = ei.order_sn "." WHERE ot.ifeinvoice=1 "." AND ei.order_sn IS NULL "." LIMIT 100 ";$O=$p->GetAll($J);foreach((array)$O
as$N){$I=$N['order_serial'];$y=array();$y["order_sn"]=$I;$y["mark_time"]=time();$y["update_time"]=time();$p->AutoExecute('einvoice_info',$y,'INSERT');do_log(NULL,$I,"新建項目狀態資訊");}$J="SELECT * FROM `".$a."order_table` AS ot "." LEFT JOIN einvoice_info AS ei ON ot.order_serial = ei.order_sn "." WHERE ot.ifeinvoice=1 "." AND ei.order_sn IS NOT NULL "." AND ei.state    IS NULL "." LIMIT 100 ";$O=$p->GetAll($J);foreach((array)$O
as$N){$I=$N['order_serial'];$fa=false;$o=false;$y=array();$y["order_time"]=$N['createtime'];$y["pay_time"]=get_paytime_by_order_sn($I);$y["ship_time"]=get_sendtime_by_order_sn($I);$y["arrival_time"]=get_recvtime_by_order_sn($I);$y["mark_time"]=$N['mark_time'];$y["update_time"]=time();switch($_){case"1":if($y["pay_time"]!=NULL)$o=true;break;case"2":if($y["ship_time"]!=NULL)$o=true;break;case"3":if($y["arrival_time"]!=NULL)$o=true;break;case"4":if($y["order_time"]!=NULL)$o=true;break;case"0":default:if($y["mark_time"]!=NULL)$o=true;break;}if($o){$y["state"]="new";do_log(NULL,$I,"時間參數的條件已滿足");$fa=true;}$g=array("pay_time","ship_time","arrival_time","order_time");foreach($g
as$Ga){if($N[$Ga]!=$y[$Ga])$fa=true;}print_r($y);if($fa){$p->AutoExecute("einvoice_info",$y,'UPDATE'," order_sn='$I' ");do_log(NULL,$I,"更新時間參數");}}}function
prepare_request(){$a=matConf::getConf('info.DBPrefix');$P=matConf::getConf('mod.einvoice.UBNo');$p=matDB::getDB();$J="SELECT * FROM `".$a."order_table` AS ot "." LEFT JOIN einvoice_info AS ei ON ot.order_serial = ei.order_sn "." WHERE ot.ifeinvoice=1 "." AND ei.order_sn IS NOT NULL "." AND ei.state = 'new' "." LIMIT 100 ";$O=$p->GetAll($J);foreach((array)$O
as$N){$wb=$N["user_id"];$X=get_user_by_id($wb);$L['order_sn']=$N['order_serial'];$L['field01']="M";$L['field02']=$N['order_serial'];$L['field03']="0";{$tb=$N['createtime'];$Gb=intval(date('Y',$tb));$Qa=intval(date('m',$tb));$ma=intval(date('d',$tb));}$L['field04']=sprintf("%04d/%02d/%02d",$Gb,$Qa,$ma);{$_=matConf::getConf('mod.einvoice.invdate');$Ba=intval(matConf::getConf('mod.einvoice.invdateadd'));$I=$N['order_serial'];switch($_){case"1":{$W=$N['pay_time'];}break;case"2":{$W=$N['ship_time'];}break;case"3":{$W=$N['arrival_time'];}break;case"4":{$W=$N['order_time'];}break;case"0":default:{$W=$N['mark_time'];}break;}$W+=$Ba*24*60*60;$Hb=intval(date('Y',$W));$Ra=intval(date('m',$W));$na=intval(date('d',$W));}$L['field05']=sprintf("%04d/%02d/%02d",$Hb,$Ra,$na);$L['field06']="1";$L['field07']="";$L['field08']="";$L['field09']=sprintf("%.5f",$N['discount_totalPrices']);$L['field10']=$P;$L['field11']="";$L['field12']=$N['invoice_num'];$L['field13']=$N['invoiceform'];{$Sa=$X['user_id'];if(isset($X["username"]))$Sa=$X["username"];if(isset($X["memberno"]))$Sa=$X["memberno"];}$L['field14']=$Sa;$L['field15']=$X["true_name"];$L['field16']=$X['zip'];{$ca=$X['canton'].$X['city'].$X['addr'];}$L['field17']=$ca;{$ub=$X["tel"];$Wa=$X["other_tel"];if(detect_encrypt_number($ub))$ub=MD5Crypt::Decrypt($ub,matConf::getConf('info.tcrypt'));if(detect_encrypt_number($Wa))$Wa=MD5Crypt::Decrypt($Wa,matConf::getConf('info.mcrypt'));}$L['field18']=$ub;$L['field19']=$Wa;$L['field20']=$X["email"];$L['field21']="";{switch($N['invoice_print']){case"yes":{$xb="Y";}break;case"no":{$xb="N";}break;default:$xb="N";}}$L['field22']=$xb;{}$L['field23']=strval($N['invoice_donate']);$L['field24']="";$L['field25']="";$L['field26']="";$L['field27']="";$L['field28']="";$L['field29']="";$p->AutoExecute('einvoice_request',$L,'INSERT');do_log(NULL,$I,"產生訂單檔內容");}}function
upload_request(){$e=matConf::getConf('mod.einvoice.TEMP_DIR');$f=matConf::getConf('mod.einvoice.UPLOADED_DIR');$P=matConf::getConf('mod.einvoice.UBNo');$u=matConf::getConf('mod.einvoice.ftp.host');$v=matConf::getConf('mod.einvoice.ftp.username');$w=matConf::getConf('mod.einvoice.ftp.password');$p=matDB::getDB();$V=time();$la=date('d',$V);$Fb=date('Y',$V);$Pa=date('m',$V);$ya=date('H',$V);$x=date('i',$V);$ib=date('s',$V);$wa=$P."-O-".$Fb.$Pa.$la."-".$ya.$x.$ib.".txt";$C=path_join($e,$wa);$K=path_join("/Upload/",$wa);$Na=path_join($f,$wa);$m=array();$gb=EInvoice::lists_by_state("new");foreach((array)$gb
as$L){if($L->_request!=NULL){$A=$L->genReqData();echo"\n$A\n";array_push($m,$A);}}if(count($m)<=0){echo"無資料上傳<br/>\n";return;}$ha=strval(count($m));array_push($m,$ha);$l=implode("\r\n",$m);file_put_contents($C,$l);$t=new
FTPAgent();$t->host=$u;$t->user=$v;$t->pass=$w;$t->enter();$M=$t->upload($C,$K);if($M==0){$n=time();foreach((array)$gb
as$L){$L->setState("submitting");$L->setTime("request",$n);do_log(NULL,$L->order_sn,"訂單檔上傳成功",$C);do_log(NULL,$L->order_sn,"[狀態 -> submitting ] 進入等待訂單回覆檔");}FileAgent::move($C,$Na);}else{$n=time();foreach((array)$gb
as$L){$L->setState("err");$L->setTime("request",$n);do_log(NULL,$L->order_sn,"訂單檔上傳失敗",$C);}}$t->leave();}function
download_files(){$e=matConf::getConf('mod.einvoice.TEMP_DIR');$P=matConf::getConf('mod.einvoice.UBNo');$u=matConf::getConf('mod.einvoice.ftp.host');$v=matConf::getConf('mod.einvoice.ftp.username');$w=matConf::getConf('mod.einvoice.ftp.password');$t=new
FTPAgent();$t->host=$u;$t->user=$v;$t->pass=$w;$t->enter();$fb=$t->list_files("/Download/");foreach($fb
as$K){$s=basename($K);$Xa=$P."-O-";$Ya=$P."-InvStatus-";if(substr($s,0,strlen($Xa))!=$Xa&&substr($s,0,strlen($Ya))!=$Ya)continue;$C=path_join($e,$s);$eb=path_join("/DownloadBackup",$s);$M=$t->download($K,$C);if($M==0)$t->move($K,$eb);else
do_log(NULL,"system","下載檔案失敗: $K ","");}$t->leave();}function
process_files(){$e=matConf::getConf('mod.einvoice.TEMP_DIR');$f=matConf::getConf('mod.einvoice.UPLOADED_DIR');$b=matConf::getConf('mod.einvoice.DONE_DIR');$P=matConf::getConf('mod.einvoice.UBNo');$Oa=FileAgent::list_files($e);foreach($Oa
as$C){$s=basename($C);$Ma=path_join($b,$s);$g=explode("-",$s);if(count($g)!=4&&count($g)!=6)continue;if($g[0]!=$P)continue;if(count($g)==6&&$g[1]=="O"){process_file_response($C);FileAgent::move($C,$Ma);$xa=$g[0]."-".$g[1]."-".$g[2]."-".$g[3].".txt";$Za=path_join($e,$xa);$ab=path_join($f,$xa);if(is_file($Za))FileAgent::move($Za,$ab);}if(count($g)==4&&$g[1]=="O");if(count($g)==4&&$g[1]=="InvStatus"){process_file_status($C);FileAgent::move($C,$Ma);}}}function
process_file_response($C){$e=matConf::getConf('mod.einvoice.TEMP_DIR');$l=file_get_contents($C);$B=explode("\r\n",$l);$Ja=$B[count($B)-2];$ob=explode("|",$Ja);if(trim($ob[2])!=""){echo"訂單回覆檔回報錯誤: $C\n";print_r($ob);}else{for($x=0;$x<count($B)-2;$x++){$A=$B[$x];$H=new
OrderResponse();$H->parse($A);$H->load();if($H->_response!=NULL){$H->update();do_log(NULL,$H->order_sn,"更新訂單回覆檔內容",$C);}else{$H->save();do_log(NULL,$H->order_sn,"新增訂單回覆檔內容",$C);}$n=time();$H->setTime("response",$n);if(trim($H->field03)==""){$H->setState("submit_ok");do_log(NULL,$H->order_sn,"訂單回覆檔回覆 OK ",$C);}else{$H->setState("submit_err");do_log(NULL,$H->order_sn,"訂單回覆檔回覆 Err ",$C);$sa=parse_return_message($Va->field03);foreach($sa
as$ra){$ta=$GLOBALS['ErrMap'][$ra];do_log(NULL,$H->order_sn,"回覆檔錯誤碼: $ra , $ta",$C);}}}}}function
process_file_status($C){$e=matConf::getConf('mod.einvoice.TEMP_DIR');$l=file_get_contents($C);$B=explode("\r\n",$l);print_r($B);for($x=0;$x<count($B)-1;$x++){$A=$B[$x];$H=new
OrderStatus();$H->parse($A);$H->load();if($H->_status!=NULL){$H->update();do_log(NULL,$H->order_sn,"更新訂單狀態檔內容",$C);}else{$H->save();do_log(NULL,$H->order_sn,"新增訂單狀態檔內容",$C);}$n=time();$H->setTime("status",$n);if(trim($H->field06)!="異常"){$H->setState("invoice_ok");do_log(NULL,$H->order_sn,"訂單狀態檔回覆 OK ",$C);}else{$H->setState("invoice_err");do_log(NULL,$H->order_sn,"訂單狀態檔狀態 Err ",$C);}}}function
check_info(){$n=time();$a=matConf::getConf('info.DBPrefix');$p=matDB::getDB();for($x=0;$x<2;$x++){$r=EInvoice::lists_by_state("submitting");foreach((array)$r
as$q){$q->load();$sb=$q->_info["submit_time"];$hb=$q->_info["response_time"];$pb=$q->_info["status_time"];if($n>$sb+(7+3)*60){$q->setState("submit_timeout");do_log(NULL,$q->order_sn,"[狀態 -> submit_timeout ] 訂單檔上傳後，等待回覆檔超過時間。");}}$r=EInvoice::lists_by_state("submit_ok");foreach((array)$r
as$q){$q->load();$sb=$q->_info["submit_time"];$hb=$q->_info["response_time"];$pb=$q->_info["status_time"];if(!empty($hb)&&empty($pb)){$q->setState("invoicing");do_log(NULL,$q->order_sn,"[狀態 -> invoicing ] 進入等待發票開立");}}$r=EInvoice::lists_by_state("invoicing");foreach((array)$r
as$q){$q->load();$sb=$q->_info["submit_time"];$hb=$q->_info["response_time"];$pb=$q->_info["status_time"];$Ea=intval(strtotime(trim($q->_request['field05'])));if($n>$Ea+60*60*24+60*60*1.5){$q->setState("invoice_timeout");do_log(NULL,$q->order_sn,"[狀態 -> invoice_timeout ] 超過發票開立等待時間");}}$r=EInvoice::lists_by_state("invoice_ok");foreach((array)$r
as$q){$q->load();print_r($q);$sb=$q->_info["submit_time"];$hb=$q->_info["response_time"];$pb=$q->_info["status_time"];$I=$q->order_sn;$oa=array();$oa['invoice_code']=$q->_status["field04"];$oa['invoice_date']=$q->_status["field05"];$M=$p->AutoExecute("einvoice_info",$oa,'UPDATE'," order_sn='$I' ");$M=$p->AutoExecute($a."order_table",$oa,'UPDATE'," order_serial='$I' ");if($M){$q->setState("done");do_log(NULL,$q->order_sn,"[狀態 -> done ] 發票開立完成");}}}}function
chk_apikey($Ha){$E=matConf::getConf('mod.einvoice.apikey');$Ia="einvoice";$jb=get_info_conf_from_file("site_userc");$R=get_info_conf_from_file("site_url");$R=strtolower($R);$R=str_replace("/","",$R);$R=str_replace("http:","",$R);$R=str_replace("www.","",$R);$ia=substr(md5($Ia.$jb.$R),0,3);for($x=0;$x<15;$x+=3){if(substr($Ha,0,$x+3)!=substr($ia,0,$x+3))exit();if(!defined("def_".$E))define("def_".$E,$x);$ia.=substr(md5($ia),0,3);}}function
do_setup($k=false){$a=matConf::getConf('info.DBPrefix');$e=matConf::getConf('mod.einvoice.TEMP_DIR');$f=matConf::getConf('mod.einvoice.UPLOADED_DIR');$b=matConf::getConf('mod.einvoice.DONE_DIR');$P=matConf::getConf('mod.einvoice.UBNo');$u=matConf::getConf('mod.einvoice.ftp.host');$v=matConf::getConf('mod.einvoice.ftp.username');$w=matConf::getConf('mod.einvoice.ftp.password');$i=matConf::getConf('mod.einvoice.begindate');$_=matConf::getConf('mod.einvoice.invdate');$Ca=matConf::getConf('mod.einvoice.invdateadd');$Da=matConf::getConf('mod.einvoice.donate.list');$d="<em style='color:white; background-color:green;' >OK</em>";$c="<em style='color:white; background-color:red;'   >FAILED</em>";$y=array();$Y=get_version("libMat");$Ab="1.0.5";$Z="無";if($Y!=NULL)$Z=$Y['full'];$nb=$d;if(!version_compare($Y['full'],$Ab,">=")){$Z.="<em style='color:red;'> (須 $Ab 以上)</em>";$nb=$c;}array_push($y,array("item"=>"libMat 版本","content"=>$Z,"status"=>$nb));$yb=get_version("mod.cron");$zb="1.0.0";$_b="無";if($yb!=NULL)$_b=$yb['full'];$mb=$d;if(!version_compare($yb['full'],$zb,">=")){$_b.="<em style='color:red;'> (須 $zb 以上)</em>";$mb=$c;}array_push($y,array("item"=>"cron 模組版本","content"=>$_b,"status"=>$mb));if($nb==$c||$mb==$c){print_table_multi($y);echo"<em style='color:red;'>相依性不滿足。請安裝需求函式庫、套件</em>";return;}array_push($y,array("item"=>"統一編號","content"=>$P,"status"=>""));array_push($y,array("item"=>"金財通 FTP 主機","content"=>$u,"status"=>""));array_push($y,array("item"=>"金財通 FTP 帳號","content"=>$v,"status"=>""));array_push($y,array("item"=>"金財通 FTP 密碼","content"=>$w,"status"=>""));{$t=new
FTPAgent();$t->host=$u;$t->user=$v;$t->pass=$w;$M=$t->enter();if($M==0){$l="";$kb="$d";}else{if($M==-1){$l="FTP connection failed. ( network problem )";$kb="$c";}else
if($M==-2){$l="Login failed. ( account mismatch )";$kb="$c";}else;}array_push($y,array("item"=>"金財通 FTP Connect","content"=>$l,"status"=>$kb));}{switch($_){case"1":{$T="付款日";}break;case"2":{$T="出貨日";}break;case"3":{$T="到貨日";}break;case"4":{$T="訂單日";}break;case"0":default:{$T="發票標記日";}break;}if($Ca==0)$lb="當天";else$lb="隔".strval($Ca)."天";$h=strtotime($i);$ea=strftime("%Y年%m月%d日起，開始導入電子發票處理",$h);array_push($y,array("item"=>"電子發票起用日期","content"=>$i." (".$ea.")","status"=>""));array_push($y,array("item"=>"發票日期選法設定","content"=>$_." (".$T.")","status"=>""));array_push($y,array("item"=>"發票日期調整天數","content"=>$Ca." (".$lb.")","status"=>""));array_push($y,array("item"=>"發票日期設定結果","content"=>$T.$lb,"status"=>""));}array_push($y,array("item"=>"捐贈單位","content"=>$Da,"status"=>""));array_push($y,array("item"=>"執行使用者","content"=>get_current_user(),"status"=>""));$p=matDB::getDB();if(!check_schema("einvoice_log","mark_time")){setup_schema("einvoice_info","order_sn","  varchar(100)         ","NULL","訂單編號");setup_schema("einvoice_info","state","  varchar(100)         ","NULL","電子發票狀態");setup_schema("einvoice_info","order_time","  varchar(100)         ","NULL","訂單產生時間");setup_schema("einvoice_info","mark_time","  varchar(100)         ","NULL","標記時間");setup_schema("einvoice_info","pay_time","  varchar(100)         ","NULL","付款時間");setup_schema("einvoice_info","ship_time","  varchar(100)         ","NULL","出貨時間");setup_schema("einvoice_info","arrival_time","  varchar(100)         ","NULL","到貨時間");setup_schema("einvoice_info","submit_time","  varchar(100)         ","NULL","電子發票上傳時間");setup_schema("einvoice_info","response_time","  varchar(100)         ","NULL","電子發票回覆時間");setup_schema("einvoice_info","status_time","  varchar(100)         ","NULL","電子發票狀態時間");setup_schema("einvoice_info","update_time","  varchar(100)         ","NULL","電子發票狀態更新時間");setup_schema("einvoice_info","invoice_code","  varchar(100)         ","NULL","發票號碼");setup_schema("einvoice_info","invoice_date","  varchar(100)         ","NULL","發票日期");setup_schema("einvoice_log","time","  varchar(100)         ","NULL","時間");setup_schema("einvoice_log","order_sn","  varchar(100)         ","NULL","訂單編號");setup_schema("einvoice_log","msg","  varchar(100)         ","''","紀錄訊息");setup_schema("einvoice_log","ref_file","  varchar(100)         ","NULL","參考檔案");setup_schema("einvoice_log","cron_code","  varchar(100)         ","NULL","紀錄序號");setup_schema("einvoice_request","order_sn","  varchar(100)         ","NULL","訂單編號");setup_schema("einvoice_request","field01","  varchar(100)         ","NULL","主檔代號");setup_schema("einvoice_request","field02","  varchar(100)         ","NULL","訂單編號");setup_schema("einvoice_request","field03","  varchar(100)         ","NULL","訂單狀態");setup_schema("einvoice_request","field04","  varchar(100)         ","NULL","訂單日期");setup_schema("einvoice_request","field05","  varchar(100)         ","NULL","發票日期");setup_schema("einvoice_request","field06","  varchar(100)         ","NULL","稅率別");setup_schema("einvoice_request","field07","  varchar(100)         ","NULL","訂單金額(未稅)");setup_schema("einvoice_request","field08","  varchar(100)         ","NULL","訂單稅額");setup_schema("einvoice_request","field09","  varchar(100)         ","NULL","訂單金額(含稅)");setup_schema("einvoice_request","field10","  varchar(100)         ","NULL","賣方統一編號");setup_schema("einvoice_request","field11","  varchar(100)         ","NULL","賣方廠編");setup_schema("einvoice_request","field12","  varchar(100)         ","NULL","買方統一編號");setup_schema("einvoice_request","field13","  varchar(100)         ","NULL","買受人公司名稱");setup_schema("einvoice_request","field14","  varchar(100)         ","NULL","會員編號");setup_schema("einvoice_request","field15","  varchar(100)         ","NULL","會員姓名");setup_schema("einvoice_request","field16","  varchar(100)         ","NULL","會員郵遞區號");setup_schema("einvoice_request","field17","  varchar(100)         ","NULL","會員地址");setup_schema("einvoice_request","field18","  varchar(100)         ","NULL","會員電話");setup_schema("einvoice_request","field19","  varchar(100)         ","NULL","會員行動電話");setup_schema("einvoice_request","field20","  varchar(100)         ","NULL","會員電子郵件");setup_schema("einvoice_request","field21","  varchar(100)         ","NULL","紅利點數折扣金額");setup_schema("einvoice_request","field22","  varchar(100)         ","NULL","索取紙本發票");setup_schema("einvoice_request","field23","  varchar(100)         ","NULL","發票捐贈註記");setup_schema("einvoice_request","field24","  varchar(100)         ","NULL","訂單註記");setup_schema("einvoice_request","field25","  varchar(100)         ","NULL","付款方式");setup_schema("einvoice_request","field26","  varchar(100)         ","NULL","相關號碼1(出貨單號)");setup_schema("einvoice_request","field27","  varchar(100)         ","NULL","相關號碼2");setup_schema("einvoice_request","field28","  varchar(100)         ","NULL","相關號碼3");setup_schema("einvoice_request","field29","  varchar(100)         ","NULL","主檔備註");setup_schema("einvoice_response","order_sn","  varchar(100)         ","NULL","訂單編號");setup_schema("einvoice_response","field01","  varchar(100)         ","NULL","訂單編號");setup_schema("einvoice_response","field02","  varchar(100)         ","NULL","訂單狀態");setup_schema("einvoice_response","field03","  varchar(100)         ","NULL","備註");setup_schema("einvoice_status","order_sn","  varchar(100)         ","NULL","訂單編號");setup_schema("einvoice_status","field01","  varchar(100)         ","NULL","訂單編號");setup_schema("einvoice_status","field02","  varchar(100)         ","NULL","出貨日期");setup_schema("einvoice_status","field03","  varchar(100)         ","NULL","訂單狀態");setup_schema("einvoice_status","field04","  varchar(100)         ","NULL","發票號碼");setup_schema("einvoice_status","field05","  varchar(100)         ","NULL","發票日期");setup_schema("einvoice_status","field06","  varchar(100)         ","NULL","發票狀態");setup_schema("einvoice_status","field07","  varchar(100)         ","NULL","二聯或三聯");setup_schema("einvoice_status","field08","  varchar(100)         ","NULL","電子/捐贈/紙本");setup_schema("einvoice_status","field09","  varchar(100)         ","NULL","稅率別");setup_schema("einvoice_status","field10","  varchar(100)         ","NULL","發票未稅金額");setup_schema("einvoice_status","field11","  varchar(100)         ","NULL","發票稅額");setup_schema("einvoice_status","field12","  varchar(100)         ","NULL","發票含稅金額");setup_schema("einvoice_status","field13","  varchar(100)         ","NULL","買方統一編號");setup_schema("einvoice_status","field14","  varchar(100)         ","NULL","異動日期");}if(!check_schema($a."order_table","ifeinvoice")){setup_schema($a."order_table","ifeinvoice","  varchar(100)         ","NULL","是否採用電子發票");setup_schema($a."order_table","invoice_print","  varchar(100)         ","NULL","是否索取紙本");setup_schema($a."order_table","invoice_donate","  varchar(100)         ","NULL","發票捐贈");setup_schema($a."order_table","invoice_return","  varchar(100)         ","NULL","電子發票回傳訊息");}if($k){repair_table_encoding("einvoice_info","utf8","utf8_general_ci");repair_table_encoding("einvoice_log","utf8","utf8_general_ci");repair_table_encoding("einvoice_request","utf8","utf8_general_ci");repair_table_encoding("einvoice_response","utf8","utf8_general_ci");repair_table_encoding("einvoice_status","utf8","utf8_general_ci");}$j=true;$j=$j&&check_table_encoding("einvoice_info","utf8_general_ci");$j=$j&&check_table_encoding("einvoice_log","utf8_general_ci");$j=$j&&check_table_encoding("einvoice_request","utf8_general_ci");$j=$j&&check_table_encoding("einvoice_response","utf8_general_ci");$j=$j&&check_table_encoding("einvoice_status","utf8_general_ci");$kb=$j?$d:$c;array_push($y,array("item"=>"資料庫欄位文字編號","content"=>"(是否符合 utf8, utf8_general_ci 之設定)","status"=>$kb));array_push($y,array("item"=>"網站頂層目錄","content"=>realpath(RootDocument),"status"=>""));if($k)prepare_dir($e);$U=is_writable($e)?"$d":"$c";array_push($y,array("item"=>"暫存目錄寫入權限","content"=>"<網站頂層目錄>".str_replace(realpath(RootDocument),"",realpath($e)),"status"=>$U));if($k)prepare_dir($b);$U=is_writable($b)?"$d":"$c";array_push($y,array("item"=>"暫存目錄DONE寫入權限","content"=>"<網站頂層目錄>".str_replace(realpath(RootDocument),"",realpath($b)),"status"=>$U));if($k)prepare_dir($f);$U=is_writable($f)?"$d":"$c";array_push($y,array("item"=>"暫存目錄UPLOAD寫入權限","content"=>"<網站頂層目錄>".str_replace(realpath(RootDocument),"",realpath($f)),"status"=>$U));print_table_multi($y);echo"<hr/>";$Ta=<<<EOD
安裝完成後，請記得新增一行定時排程設定於 SITE_ROOT/UploadFile/cron/crontab。範例如下:<br/>
<pre>
*/20 * * * * SITE_URL/modules/einvoice/einvoice.php?action=process
</pre>
EOD;

echo$Ta;}function
do_process(){echo"==== process begin ===="."<br/>\n","stage: 將符合開立發票條件的，標上註記"."<br/>\n";$za=path_join(dirname(__FILE__),"scan_and_mark_orders.php");if(file_exists($za)){echo"  >>> 使用自訂修改的標記程式 $za <<< <br/>\n";include($za);}else{echo"  >>> 系統內建的標記程式 <<< <br/>\n";scan_and_mark_orders();}echo"stage: 掃瞄標上註記的訂單，建立基本資料，並更新時間參數"."<br/>\n";new_info();echo"stage: 從金財通的 FTP 下載資料檔案至本地端目錄"."<br/>\n";download_files();echo"stage: 處理本地端目錄下的資料檔案，處理完的檔案會安置至備份目錄"."<br/>\n";process_files();echo"stage: 更新處理狀態"."<br/>\n";check_info();echo"stage: 將標上標記的，進行上傳處理"."<br/>\n";prepare_request();upload_request();echo"==== process end ===="."<br/>\n";}function
do_errlist(){$F=basename(__FILE__);$da=isset($_REQUEST['beg'])?$_REQUEST['beg']:NULL;$pa=isset($_REQUEST['end'])?$_REQUEST['end']:NULL;$S=isset($_REQUEST['state'])?$_REQUEST['state']:NULL;if(empty($da))$da=strftime("%Y%m%d",time()-60*60*24*30);$h=strtotime($da);if(empty($pa))$pa=strftime("%Y%m%d",$h+60*60*24*30);$qa=strtotime($pa)+60*60*24;$Ua="";foreach(array("all")as$G){$Q="";if($S==$G)$Q="selected";$Ua.=sprintf("<option value=\"%s\" $Q >%s</option>",$G,$G);}foreach(array_keys($GLOBALS['States'])as$G){$Q="";if($S==$G)$Q="selected";$Ua.=sprintf("<option value=\"%s\" $Q >%s</option>",$G,$G);}$z=<<<EOD
		<form action="" method="get">
		<input type="hidden" name="action" value="errlist" />
		開始時間: <input type="text" name="beg" value="{$da}" />
		結束時間: <input type="text" name="end" value="{$pa}" />
		處理狀態: <select name="state" value="{$S}">
				<option value=""                >預設(*err,*timeout,*ing)</option>
				$Ua
			  </select>
		<br/>
		<input type="submit" value="提交"/>
		</form>
EOD;

echo$z;if($S=="default"||$S=="")$ja="( state LIKE '%timeout%' OR state LIKE '%err%' OR state LIKE '%ing%' OR state IS NULL )";else
if($S=="all")$ja="( state LIKE '%%' )";else$ja="( state LIKE '".$S."' )";$a=matConf::getConf('info.DBPrefix');$J="SELECT ot.* , ei.* "." FROM {$a}order_table AS ot "." LEFT JOIN einvoice_info AS ei ON ot.order_serial=ei.order_sn "." WHERE ot.createtime >= $h AND ot.createtime <= $qa "." AND $ja "." AND ei.order_sn IS NOT NULL "." ORDER BY ot.createtime DESC "." LIMIT 1000 "."";$p=matDB::getDB();$O=$p->GetAll($J);$La=array();foreach($O
as$N){$Ka=sprintf("<a href=\"%s\" >[詳細資料]</a>",$F."?action=trace&order_sn=".$N['order_serial']);array_push($La,array("order_serial"=>$N['order_serial']."<br/>".$Ka,"createtime"=>strftime("%Y-%m-%d %H:%M",$N['createtime']),"invoiceform"=>$N['invoiceform'],"invoice_num"=>$N['invoice_num'],"ifinvoice"=>$N['ifinvoice'],"code"=>$N['invoice_code'],"print"=>$N['invoice_print'],"donate"=>$N['invoice_donate'],"state"=>$N['state']));}print_table_multi($La);}function
do_trace(){$I=isset($_REQUEST['order_sn'])?$_REQUEST['order_sn']:NULL;$db=isset($_REQUEST['ref_file'])?$_REQUEST['ref_file']:NULL;if(!empty($db)){$ua=get_ref_file($db);$z="<textarea readonly wrap='off' style='width:100%'>".$ua."</textarea>";echo$z;return;}if(empty($I)){$z=<<<EOD
			<form action="" method="get">
			訂單編號: <input type="text" name="order_sn" />
			<br/>
			<input type="hidden" name="action" value="trace" />
			<input type="submit" value="提交"/>
			</form>
EOD;

echo$z;return;}$q=new
EInvoice();$q->order_sn=$I;$q->load();echo"<hr/>"." 訂單資訊: <br/>";if($q->_order!=NULL){$va=array("order_id","user_id","order_serial","receiver_name","receiver_email","receiver_tele","receiver_mobile","ifinvoice","invoiceform","invoice_num","totalprice","pay_state","sendtime","createtime","invoice_code","invoice_date","invoice_donate","invoice_print","ifeinvoice");print_table_90($q->_order,$va,NULL,true);if(isset($q->_order_detail)){echo"<br/>"." 訂單內容清單: <br/>";print_table_multi($q->_order_detail);}}else
echo"<em>無</em></br>";echo"<hr/>"." 電子發票處理資訊: <br/>";if($q->_info!=NULL){print_table_90($q->_info,NULL,NULL,true);echo"<br/>"." 電子發票處理過程: <br/>";if($q->_log!=NULL){$D=$q->_log;for($x=0;$x<count($D);$x++){$D[$x]['ref_file']=sprintf("<a href=\"?action=trace&ref_file=%s\">%s</a>",$D[$x]['ref_file'],$D[$x]['ref_file']);$D[$x]['cron_code']=sprintf("<a href=\"../../modules/cron/cron.php?action=trace&cron_code=%s\">%s</a>",$D[$x]['cron_code'],$D[$x]['cron_code']);}print_table_multi($D,array("time","msg","ref_file","cron_code"),$GLOBALS['DictLogs'],true);}}else
echo"<em>無</em></br>";echo"<hr/>"."電子發票上傳檔資料: <br/>";if($q->_request!=NULL)print_table_90($q->_request,NULL,$GLOBALS['DictReqest']);else
echo"<em>無</em></br>";echo"<hr/>"."電子發票回應檔資料: <br/>";if($q->_response!=NULL)print_table_90($q->_response,NULL,$GLOBALS['DictResponse']);else
echo"<em>無</em></br>";echo"<hr/>"."電子發票開立狀態檔資料: <br/>";if($q->_status!=NULL)print_table_90($q->_status,NULL,$GLOBALS['DictStatus']);else
echo"<em>無</em></br>";echo"<hr/>";}function
do_readme(){$Y=get_version("libMat");$Bb=get_version("mod.cron");$Db=get_version("mod.einvoice");$Z="無";if($Y!=NULL)$Z=$Y['full'];$Cb="無";if($Bb!=NULL)$Cb=$Bb['full'];$Eb="無";if($Db!=NULL)$Eb=$Db['full'];$cb=<<<EOD

電子發票模組版本: %s <br/>
libMat 版本: %s <br/>
定時排程模組版本: %s <br/>

EOD;
$bb=sprintf($cb,$Eb,$Z,$Cb);echo$bb;}$E=matConf::getConf('mod.einvoice.apikey');$P=matConf::getConf('mod.einvoice.UBNo');$i=matConf::getConf('mod.einvoice.begindate');$u=matConf::getConf('mod.einvoice.ftp.host');$v=matConf::getConf('mod.einvoice.ftp.username');$w=matConf::getConf('mod.einvoice.ftp.password');$_=matConf::getConf('mod.einvoice.invdate');$Ba=matConf::getConf('mod.einvoice.invdateadd');$Da=matConf::getConf('mod.einvoice.donate.list');$e=dirname(__FILE__)."/../../UploadFile/einvoice";$b=path_join($e,"done");$f=path_join($e,"uploaded");matConf::setConf('mod.einvoice.TEMP_DIR',$e);matConf::setConf('mod.einvoice.DONE_DIR',$b);matConf::setConf('mod.einvoice.UPLOADED_DIR',$f);$ga=(matConf::getConf('mod.einvoice.chk_admin')=="yes");$E=matConf::getConf('mod.einvoice.apikey');chk_apikey($E);$ba=isset($_GET['action'])?$_GET['action']:"";switch($ba){case"process":{$E=matConf::getConf('mod.einvoice.apikey');if(!defined("def_".$E))echo"ERROR: API KEY MISMATCH!!";do_process();}break;case"setup":{if($ga&&(!chk_admin()))die("請先登入後台管理員");$E=matConf::getConf('mod.einvoice.apikey');if(!defined("def_".$E))echo"ERROR: API KEY MISMATCH!!";$k=(isset($_REQUEST['confirm'])&&$_REQUEST['confirm']=="yes")?true:false;show_menu();if(!$k){echo"模式: 唯讀模式(只作檢查，不更動到系統)","<br/>";$F=basename(__FILE__);echo
sprintf("<br/><a href=\"%s\" >確認執行安裝...</a><br/><span>(注意！將會更動檔案及目錄，和資料庫的欄位)</span><br/><br/>",$F."?action=setup&confirm=yes");}else{echo"模式: 安裝模式","<br/>";}do_setup($k);}break;case"errlist":{if($ga&&(!chk_admin()))die("請先登入後台管理員");$E=matConf::getConf('mod.einvoice.apikey');if(!defined("def_".$E))echo"ERROR: API KEY MISMATCH!!";show_menu();do_errlist();}break;case"trace":{if($ga&&(!chk_admin()))die("請先登入後台管理員");$E=matConf::getConf('mod.einvoice.apikey');if(!defined("def_".$E))echo"ERROR: API KEY MISMATCH!!";show_menu();do_trace();}break;default:{if($ga&&(!chk_admin()))die("請先登入後台管理員");show_menu();do_readme();}break;}?>