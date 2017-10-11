<?php

define("version.mod.cron","1.0.1");function
curl_call($x,$q,$n=array(),$k=array()){$f=curl_init($x);curl_setopt($f,CURLOPT_HTTPHEADER,$n);switch($q){case"get":{curl_setopt($f,CURLOPT_RETURNTRANSFER,true);}break;case"post":{curl_setopt($f,CURLOPT_RETURNTRANSFER,true);curl_setopt($f,CURLOPT_POST,true);$g=$k;curl_setopt($f,CURLOPT_POSTFIELDS,$g);}break;case"put":{curl_setopt($f,CURLOPT_RETURNTRANSFER,true);curl_setopt($f,CURLOPT_CUSTOMREQUEST,"PUT");$t=http_build_query($k);if(strpos($x,"?")===false)$x=$x."?".$t;else$x=$x."&".$t;curl_setopt($f,CURLOPT_URL,$x);}break;case"delete":{curl_setopt($f,CURLOPT_RETURNTRANSFER,true);curl_setopt($f,CURLOPT_CUSTOMREQUEST,"DELETE");$g=$k;curl_setopt($f,CURLOPT_POSTFIELDS,$g);}break;}$h=curl_exec($f);if($h===false){echo'Curl error: '.curl_error($f)."<br/>";$o=curl_getinfo($f);curl_close($f);return
false;}curl_close($f);$v=$h;return$v;}function
get_cron_code($w=NULL){$v=date('c',(time()-time()%60))."__".strval($w);$v=base64_encode($v);return$v;}function
get_timestamp_from_cron_code($d){$r=base64_decode($d);$b=explode("__",$r);if(count($b)==2){$_=$b[0];$A=strtotime($_);}return$A;}function
get_path_from_cron_code($d){$a=matConf::getConf('mod.cron.LOGS_DIR');$A=get_timestamp_from_cron_code($d);$_=date('c',$A);$z=parse_time($A);$i=$z['week_yrs'];$j=$z['week_nth']."_".$z['week_beg']."_".$z['week_end'];$l=$_."_".$d.".log";$s=path_join($a,$i,$j,$l);return$s;}function
get_cron_log_file($d){$s=get_path_from_cron_code($d);if(file_exists($s)){$v=file_get_contents($s);return$v;}return
NULL;}/**
 * http://stackoverflow.com/questions/321494/calculate-when-a-cron-job-will-be-executed-then-next-time
 * @version 
*/function
parse_crontab($m='* * * * *',$y=false){$y=is_string($y)?strtotime($y):time();$y=explode(' ',date('i G j n w',$y));foreach($y
as$p=>$K)$y[$p]=intval($K);$e=explode(' ',$m);foreach($e
as$p=>&$K){$u=array('/^\*$/','/^(\d+)$/','/^(\d+)\-(\d+)$/','/^\*\/(\d+)$/');$B=$y[$p];$c=array("true","$B === $1","($1 <= $B && $B <= $2)","$B % $1 === 0");$K=explode(',',$K);foreach($K
as&$L)$L=preg_replace($u,$c,$L);$K='('.implode(' || ',$K).')';}$e=implode(' && ',$e);return
eval("return {$e};");}function
parse_time($A){$b=strptime(strval($A),"%s");$G=$b["tm_sec"];$E=$b["tm_min"];$C=$b["tm_hour"];$D=$b["tm_mday"];$F=$b["tm_mon"];$J=$b["tm_year"];$H=$b["tm_wday"];$I=$b["tm_yday"];$O=(($I-$I%7)/7)+1;$P=mktime(0,0,0,0,1,$J+1900);$M=mktime(0,0,0,$F+1,$D-$H,$J+1900);if($M<=$P)$M=$P;$Q=mktime(0,0,0,0,1,$J+1900+1)-1;$N=mktime(0,0,0,$F+1,$D-$H+7,$J+1900)-1;if($N>=$Q)$N=$Q;$b['week_yrs']=str_pad($J+1900,4,"0",STR_PAD_LEFT);$b['week_nth']=str_pad($O,2,"0",STR_PAD_LEFT);$b['week_beg']=strftime("%F",$M);$b['week_end']=strftime("%F",$N);return$b;}?>
