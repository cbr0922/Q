<?php

define("version.mod.einvoice","1.0.3");$GLOBALS['ErrMap']=array("ER001"=>"訂單編號欄位不可空白！","ER002"=>"訂單狀態欄位不可空白！","ER003"=>"訂單日期欄位不可空白！","ER004"=>"訂單日期格式錯誤！","ER005"=>"預計出貨日期欄位不可空白！","ER006"=>"預計出貨日期格式錯誤！","ER009"=>"稅率別欄位不可空白！","ER010"=>"訂單金額 (未稅 )格式錯誤！","ER011"=>"ER011訂單稅額格式錯誤！","ER012"=>"訂單金額 (含稅 )欄位不可空白！","ER013"=>"訂單金額 (含稅 )格式錯誤！","ER014"=>"賣方統一編號欄位不可空白！","ER020"=>"紅利點數折扣金額格式錯誤！","ER021"=>"索取紙本發票欄位不可空白！","ER022"=>"訂單主檔欄位數錯誤！","ER023"=>"訂單含稅金額與明細加總不同！","ER024"=>"已有訂單資料，無法新增！","ER025"=>"沒有訂單資料，無法刪除！","ER026"=>"訂單資料已被刪除，不重覆！","ER027"=>"沒有訂單資料 沒有訂單資料 ,無法修單！","ER028"=>"訂單資料已被刪除，無法修！","ER029"=>"系統發生錯誤，匯入正式檔失敗！","ER030"=>"發票區間內號碼已被取用完畢，請設定新的再上傳訂單！","ER065"=>"紙本發票的會員地址空白！","ER060"=>"買方統一編號檢查碼驗證錯誤！","ER999"=>"");$GLOBALS['States']=array('new'=>"新進資料",'submitting'=>"提交中",'submit_ok'=>"提交成功",'submit_err'=>"提交失敗",'submit_timeout'=>"提交逾時",'invoicing'=>"開發票中",'invoice_ok'=>"發票完成",'invoice_err'=>"發票失敗",'invoice_timeout'=>"發票逾期",'done'=>"完成",'timeout'=>"逾期",'err'=>"異常",);$GLOBALS['DictLogs']=array("time"=>"紀錄時間","msg"=>"訊息內容","ref_file"=>"參考檔案","cron_code"=>"紀錄序號",);$GLOBALS['DictResponse']=array("field01"=>"訂單編號","field02"=>"訂單狀態","field03"=>"備註",);$GLOBALS['DictStatus']=array("field01"=>"訂單編號","field02"=>"出貨日期","field03"=>"訂單狀態","field04"=>"發票號碼","field05"=>"發票日期","field06"=>"發票狀態","field07"=>"二聯或三聯","field08"=>"電子/捐贈/紙本","field09"=>"稅率別","field10"=>"發票未稅金額","field11"=>"發票稅額","field12"=>"發票含稅金額","field13"=>"買方統一編號","field14"=>"異動日期",);$GLOBALS['DictReqest']=array("field01"=>"主檔代號","field02"=>"訂單編號","field03"=>"訂單狀態","field04"=>"訂單日期","field05"=>"發票日期","field06"=>"稅率別","field07"=>"訂單金額(未稅)","field08"=>"訂單稅額","field09"=>"訂單金額(含稅)","field10"=>"賣方統一編號","field11"=>"賣方廠編","field12"=>"買方統一編號","field13"=>"買受人公司名稱","field14"=>"會員編號","field15"=>"會員姓名","field16"=>"會員郵遞區號","field17"=>"會員地址","field18"=>"會員電話","field19"=>"會員行動電話","field20"=>"會員電子郵件","field21"=>"紅利點數折扣金額","field22"=>"索取紙本發票","field23"=>"發票捐贈註記","field24"=>"訂單註記","field25"=>"付款方式","field26"=>"相關號碼1(出貨單號)","field27"=>"相關號碼2","field28"=>"相關號碼3","field29"=>"主檔備註",);class
OrderResponse
extends
EInvoice{public$t="";public$u="";public$v="";public$s=NULL;public
function
parse($D){$w=explode("|",$D);if(count($w)==3){$this->field01=$w[0];$this->field02=$w[1];$this->field03=$w[2];$this->errors=parse_return_message($this->field03);$this->order_sn=$this->field01;}var_dump($this);}public
function
update(){$n=matDB::getDB();$N=$this->order_sn;$m['field01']=$this->field01;$m['field02']=$this->field02;$m['field03']=$this->field03;$n->AutoExecute('einvoice_response',$m,'UPDATE'," order_sn='$N' ");}public
function
save(){$n=matDB::getDB();$m['order_sn']=$this->order_sn;$m['field01']=$this->field01;$m['field02']=$this->field02;$m['field03']=$this->field03;$n->AutoExecute('einvoice_response',$m,'INSERT');}}class
OrderStatus
extends
EInvoice{public$t="";public$u="";public$v="";public$ga="";public$ha="";public$ia="";public$ja="";public$ka="";public$la="";public$ma="";public$na="";public$oa="";public$pa="";public$qa="";public$s=NULL;public
function
parse($D){$w=explode("|",$D);if(count($w)==14){$this->field01=$w[0];$this->field02=$w[1];$this->field03=$w[2];$this->field04=$w[3];$this->field05=$w[4];$this->field06=$w[5];$this->field07=$w[6];$this->field08=$w[7];$this->field09=$w[8];$this->field10=$w[9];$this->field11=$w[10];$this->field12=$w[11];$this->field13=$w[12];$this->field14=$w[13];$this->order_sn=$this->field01;$this->errors=parse_return_message($this->field14);}}public
function
save(){$n=matDB::getDB();$m['order_sn']=$this->order_sn;$m['field01']=$this->field01;$m['field02']=$this->field02;$m['field03']=$this->field03;$m['field04']=$this->field04;$m['field05']=$this->field05;$m['field06']=$this->field06;$m['field07']=$this->field07;$m['field08']=$this->field08;$m['field09']=$this->field09;$m['field10']=$this->field10;$m['field11']=$this->field11;$m['field12']=$this->field12;$m['field13']=$this->field13;$m['field14']=$this->field14;$n->AutoExecute('einvoice_status',$m,'INSERT');}public
function
update(){$n=matDB::getDB();$N=$this->order_sn;$m['field01']=$this->field01;$m['field02']=$this->field02;$m['field03']=$this->field03;$m['field04']=$this->field04;$m['field05']=$this->field05;$m['field06']=$this->field06;$m['field07']=$this->field07;$m['field08']=$this->field08;$m['field09']=$this->field09;$m['field10']=$this->field10;$m['field11']=$this->field11;$m['field12']=$this->field12;$m['field13']=$this->field13;$m['field14']=$this->field14;$n->AutoExecute('einvoice_status',$m,'UPDATE'," order_sn='$N' ");}}class
EInvoice{var$order_sn=NULL;var$_info=NULL;var$_request=NULL;var$_response=NULL;var$_status=NULL;var$_log=NULL;static
function
lists_by_state($V){$S=array();$n=matDB::getDB();$P="SELECT er.* FROM einvoice_request AS er"." LEFT JOIN einvoice_info AS ei ON er.order_sn = ei.order_sn "." WHERE ei.state='$V' "." LIMIT 500 ";$ua=$n->GetAll($P);foreach((array)$ua
as$U){$L=new
EInvoice();$L->order_sn=$U['order_sn'];$L->load();array_push($S,$L);}return$S;}public
function
setState($V){$n=matDB::getDB();$N=$this->order_sn;$m['state']=$V;$n->AutoExecute('einvoice_info',$m,'UPDATE'," order_sn='$N' ");}public
function
setTime($va,$Y){$n=matDB::getDB();switch($va){case"request":{$k="submit_time";}break;case"response":{$k="response_time";}break;case"status":{$k="status_time";}break;default:$k="update_time";break;}if(empty($k))return;$N=$this->order_sn;$m[$k]=$Y;$m["update_time"]=$Y;$n->AutoExecute('einvoice_info',$m,'UPDATE'," order_sn='$N' ");}public
function
load(){$n=matDB::getDB();$N=$this->order_sn;$a=matConf::getConf('info.DBPrefix');$R=$n->GetRow("SELECT * FROM einvoice_info WHERE order_sn='$N' LIMIT 1");if($R)$this->_info=$R;$R=$n->GetAll("SELECT * FROM einvoice_log WHERE order_sn='$N' ORDER BY `time` ASC LIMIT 999");if($R)$this->_log=$R;$R=$n->GetRow("SELECT * FROM einvoice_request WHERE order_sn='$N' LIMIT 1");if($R)$this->_request=$R;$R=$n->GetRow("SELECT * FROM einvoice_response WHERE order_sn='$N' LIMIT 1");if($R)$this->_response=$R;$R=$n->GetRow("SELECT * FROM einvoice_status WHERE order_sn='$N' LIMIT 1");if($R)$this->_status=$R;$R=$n->GetRow("SELECT * FROM ".$a."order_table WHERE order_serial='$N' LIMIT 1");if($R){$this->_order=$R;$M=$R['order_id'];$R=$n->GetAll("SELECT * FROM ".$a."order_detail WHERE order_id='$M' LIMIT 1");if($R)$this->_order_detail=$R;}}public
function
genReqData(){if($this->_request!=NULL){$T=$this->_request['field01'];$T.="|".$this->_request['field02'];$T.="|".$this->_request['field03'];$T.="|".$this->_request['field04'];$T.="|".$this->_request['field05'];$T.="|".$this->_request['field06'];$T.="|".$this->_request['field07'];$T.="|".$this->_request['field08'];$T.="|".$this->_request['field09'];$T.="|".$this->_request['field10'];$T.="|".$this->_request['field11'];$T.="|".$this->_request['field12'];$T.="|".$this->_request['field13'];$T.="|".$this->_request['field14'];$T.="|".$this->_request['field15'];$T.="|".$this->_request['field16'];$T.="|".$this->_request['field17'];$T.="|".$this->_request['field18'];$T.="|".$this->_request['field19'];$T.="|".$this->_request['field20'];$T.="|".$this->_request['field21'];$T.="|".$this->_request['field22'];$T.="|".$this->_request['field23'];$T.="|".$this->_request['field24'];$T.="|".$this->_request['field25'];$T.="|".$this->_request['field26'];$T.="|".$this->_request['field27'];$T.="|".$this->_request['field28'];$T.="|".$this->_request['field29'];return$T;}else
return"";}}class
Charity{var$data_path=NULL;var$data=NULL;function
__construct($O=NULL){$this->data_path=dirname(__FILE__)."/charities.csv";if($O!=NULL)$this->data_path=$O;$g=file_exists($this->data_path);if($g){$l=file_get_contents($this->data_path);$E=explode("\n",$l);$e=array();foreach($E
as$D){$C=explode(",",$D);if(count($C)==3){$_=trim($C[0]);$j=trim($C[1]);$H=trim($C[2]);array_push($e,array($j,$_,$H));}}if(count($e)>0)$this->data=$e;}}function
info($Z=0){if($this->data==NULL){echo"無資料";return;}$J=count($this->data);echo"共 $J 筆資料";if($Z)print_r($this->data);}function
listCodes(){$S=array();foreach($this->data
as$B)array_push($S,$B[0]);return$S;}function
get($j){$R=NULL;foreach($this->data
as$B){if($j==$B[0])$R=array("code"=>$B[0],"fullname"=>$B[1],"name"=>$B[2]);}return$R;}function
lists(){$S=array();foreach($this->data
as$B){$R=array("code"=>$B[0],"fullname"=>$B[1],"name"=>$B[2]);array_push($S,$R);}return$S;}function
print_options(){$S="";$q=array();$K=matConf::getConf('mod.einvoice.donate.list');foreach((array)explode(",",$K)as$B){if(intval(trim($B))>0)array_push($q,trim($B));}foreach((array)$this->data
as$B){$j=trim($B[0]);$_=trim($B[1]);$H=trim($B[2]);if(count($q)>0){if(!in_array($j,$q))continue;}if(!empty($H))$I=$_."(".$H.")";else$I=$_;$W=sprintf("<option value=\"%s\">%s</option>",$j,$I);$S=$S.$W."\n";}return$S;}}function
parse_return_message($l){$W=trim($l);$e=explode('！',$W);$f=array();foreach((array)$e
as$B){if(!empty($B)){$r=substr($B,0,5);if(!in_array($r,$f))array_push($f,$r);}}return$f;}function
do_log($X=NULL,$N,$G,$Q=NULL){$n=matDB::getDB();$F=array();$F['time']=$X?$X:time();$F['order_sn']=$N;$F['msg']=$G;$F['ref_file']=$Q?basename($Q):"";$F['cron_code']=isset($_REQUEST['cron_code'])?$_REQUEST['cron_code']:"";$n->AutoExecute('einvoice_log',$F,'INSERT');}function
check_ftp_list(){$z=new
FTPAgent();$z->host=$ra;$z->user=$sa;$z->pass=$ta;$z->enter();$y=$z->list_files("/Upload/");print_r($y);$y=$z->list_files("/UploadBackup/");print_r($y);$y=$z->list_files("/Download/");print_r($y);$y=$z->list_files("/DownloadBackup/");print_r($y);$z->leave();}function
get_ref_file($x){$c=matConf::getConf('mod.einvoice.TEMP_DIR');$d=matConf::getConf('mod.einvoice.UPLOADED_DIR');$b=matConf::getConf('mod.einvoice.DONE_DIR');$p=array($c,$d,$b,);foreach($p
as$o){$O=path_join($o,$x);if(file_exists($O)){$S=file_get_contents($O);return$S;}}return
NULL;}function
detect_encrypt_number($W){$i=0;for($A=0;$A<strlen($W);$A++){$j=ord($W[$A]);if((65<=$j&&$j<=90)||(97<=$j&&$j<=122))$i+=1;}return($i>5);}function
get_order_id_by_order_sn($N){$n=matDB::getDB();$a=matConf::getConf('info.DBPrefix');$P=" SELECT * "." FROM ".$a."order_table "." WHERE order_serial='".$N."' "." LIMIT 1 ";$R=$n->GetRow($P);if($R){$M=$R['order_id'];return$M;}else
return
NULL;}function
get_paytime_by_order_sn($N){$a=matConf::getConf('info.DBPrefix');$n=matDB::getDB();$S=NULL;$M=get_order_id_by_order_sn($N);if($M!=NULL){$h=check_schema($a."order_action","actiontime");if($h){$P=" SELECT * "." FROM ".$a."order_action "." WHERE order_id='".$M."' "." AND ( state_type=2 AND ( state_value=1 ) ) "." ORDER BY actiontime ASC "." LIMIT 1 ";$R=$n->GetRow($P);if($R)$S=$R['actiontime'];}else;}return$S;}function
get_sendtime_by_order_sn($N){$a=matConf::getConf('info.DBPrefix');$n=matDB::getDB();$S=NULL;$M=get_order_id_by_order_sn($N);if($M!=NULL){$h=check_schema($a."order_action","actiontime");if($h){$P=" SELECT * "." FROM ".$a."order_action "." WHERE order_id='".$M."' "." AND ( state_type=3 AND ( state_value=1 ) ) "." ORDER BY actiontime ASC "." LIMIT 1 ";$R=$n->GetRow($P);if($R)$S=$R['actiontime'];}else{$P=" SELECT * "." FROM ".$a."order_table"." WHERE order_id='".$M."' "." LIMIT 1 ";$U=$n->GetRow($P);$S=$U['sendtime'];}}return$S;}function
get_recvtime_by_order_sn($N){$a=matConf::getConf('info.DBPrefix');$n=matDB::getDB();$S=NULL;$M=get_order_id_by_order_sn($N);if($M!=NULL){$h=check_schema($a."order_action","actiontime");if($h){$P=" SELECT * "." FROM ".$a."order_action "." WHERE order_id='".$M."' "." AND ( state_type=3 AND ( state_value=2 OR state_value=18 ) ) "." ORDER BY actiontime ASC "." LIMIT 1 ";$R=$n->GetRow($P);if($R)$S=$R['actiontime'];}else;}return$S;}function
mark_use_einvoice($N){$a=matConf::getConf('info.DBPrefix');$n=matDB::getDB();$m=array();$m['ifeinvoice']="1";$n->AutoExecute($a."order_table",$m,'UPDATE'," order_serial='$N' ");do_log(NULL,$N,"標記為使用電子發票");}?>
