<?php
include_once "Check_Admin.php";
@header("Content-type: text/html; charset=utf-8");

$goodattrI = str_replace(" ","+",trim($_GET['goodattrI']));
if ($_GET['bid']!=""){
	//$Bid =  intval($_POST['bid'])!="" ? intval($_POST['bid'])!="" : intval($_GET['bid'])!="";
	$ChangeBid   =  intval($_GET['bid']);



	$Query = $DB->query("select attr,bid,gain from `{$INFO[DBPrefix]}bclass` where bid=".intval($ChangeBid)." limit 0,1");
	$Num   = $DB->num_rows($Query);
	if ($Num>0){

		$Result= $DB->fetch_array($Query);
		$Bid =  $Result['bid'];
		$gain =  $Result['gain'];

		if ($Result['attr']!=""){
			$attrI   =  "0,".$Result['attr'];
			$Attr    =  explode(',',$attrI);
			$Attr_num=  count($Attr);
		}else{
			$Attr_num=0;
		}
	}
}

if (intval($Attr_num)>0){
	$goodAttr   =  explode(',',base64_decode($goodattrI));



?>
	<table width="95%" border="0" cellspacing="0" cellpadding="0" class=p9black>
<?
for ($i=1;$i<intval($Attr_num);$i++){
?>
		<TR bgcolor="#FFFF99">					
           <TD width="100" align=right  ><?php echo $Attr[$i]?></TD>
           <TD colSpan=2><?php echo $FUNCTIONS->Input_Box('text',"goodattr".$i,$goodAttr[$i],"  maxLength=50 size=40 ")?></TD>
        </TR>
<?
}
?>
	</table>
	
<?
}

?>
<INPUT type=hidden   name='Attr_num'  value="<?php echo $Attr_num?>">
<INPUT type=hidden   name='gain'  value="<?php echo $gain?>">
