<?php
@ob_start();
//session_start();
include "Check_Admin.php";
@header("Pragma: no-cache");
@header("Content-type: text/html; charset=utf-8");

switch ($INFO['admin_IS']){
	case "gb":
		$ToEncode = "GB2312";
		break;
	case "en":
		$ToEncode = "GB2312";
		break;
	case "big5":
		$ToEncode = "BIG5";
		break;
	default:
		$ToEncode = "GB2312";
		break;
}

$ToEncode = "UTF-8";
    /**
     * Filter函数:过滤字符
     * 返回处理完的内容
     */
    function Filter($Argv){
        $Argv=trim($Argv);
        $Search=array("<",">","\"");
        $Replace=array("","","'");
        return str_replace($Search,$Replace,$Argv);
    }
/**
 * 这里是处理输出EXCEL时候用的。
 * 用户名,E-mail地址,真实姓名,性别,出生日期,地区名称,联系地址,移动电话,固定电话
 */
if ($_GET[Action]=='XML' && intval($_GET[gc_id])) {

    $XML_String =  "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    //$XML_String .= "<DocumentROOT date="2007-08-03" time="10:49:54">";
	$Query = $DB->query("select gc_name,gc_pic,gc_id,gc_string  from `{$INFO[DBPrefix]}goodscollection` where gc_id=".intval($gc_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$gc_name    =  trim($Result['gc_name']);
		$gc_pic     =  trim($Result['gc_pic']);
		$gc_id      =  intval($Result['gc_id']);
		$gc_string  =  trim($Result['gc_string']);


		$pos = strpos($gc_string,",");

        if ($pos === false ){
           $gc_strings = trim($gc_string);
		   $sqladd     = " where gid = '".$gc_strings."' ";
	    }else{
		   $gc_string_array = explode(",",$gc_string);
		   $gc_string_array = array_unique($gc_string_array);
		   $gc_strings = implode(",",$gc_string_array);
		   $gc_string_array = explode(",",$gc_string);
		   $sqladd     = " where ";
		   foreach ( $gc_string_array as $k => $v ){
		      if ( intval($v) > 0 ){
			     $sqladd  .= " gid ='".intval($v)."'   or";
			 }
		   }
		   $sqladd  = substr($sqladd,0,-3);

        }
		$GCName = iconv("UTF-8",$ToEncode,Filter($gc_name));
        $XML_String .= "<date_".date("Ymd",time()).">\n";
		$XML_String .= "<CollectionName>".$GCName."</CollectionName>\n";
        $XML_String .= "<CollectionPic>".$INFO['site_url']."/LogoPic/".Filter($gc_pic)."</CollectionPic>\n";

		$QueryProductList = $DB->query(" select gid,goodsname,bigimg,smallimg,gimg,middleimg,price,unit,pricedesc from `{$INFO[DBPrefix]}goods`  $sqladd ");

        $i = 1;
        while ($Rs = $DB->fetch_array($QueryProductList)){

			 $GoodsName = iconv("UTF-8",$ToEncode,Filter($Rs[goodsname]));
             $Unit      = iconv("UTF-8",$ToEncode,Filter($Rs[unit]));
             $XML_String .="<product_".$i.">\n";
             $XML_String .="    <gid>".intval($Rs[gid])."</gid>\n";
			 $XML_String .="    <goodsname>".$GoodsName."</goodsname>\n";
			 $XML_String .="    <price>".trim($Rs[price])."</price>\n";
             $XML_String .="    <pricedesc>".trim($Rs[pricedesc])."</pricedesc>\n";
			 $XML_String .="    <unit>".$Unit."</unit>\n";
             $XML_String .="	<bigimg>".$INFO['site_url']."/GoodPic/$Rs[bigimg]</bigimg>\n";
			 $XML_String .="	<gimg>".$INFO['site_url']."/GoodPic/$Rs[gimg]</gimg>\n";
			 $XML_String .="	<smallimg>".$INFO['site_url']."/GoodPic/$Rs[smallimg]</smallimg>\n";
             $XML_String .="	<middleimg>".$INFO['site_url']."/GoodPic/$Rs[middleimg]</middleimg>\n";
             $XML_String .="	<url>".$INFO['site_url']."/product/goods_detail.php?goods_id=$Rs[gid]</url>\n";
             $XML_String .="</product_".$i.">\n";

         $i++;
        }


	}

	$XML_String .= "</date_".date("Ymd",time()).">\n";

	echo $XML_String;
	$Creatfile = "date_".date("Y-m-d");


	/**
	 * 这个部分是写一个本地文件，在目前这里是没有用的。临时保留
	 *
	if ( $fh = fopen( $Creatfile.'.csv', 'w' ) )
	{
	fputs ($fh, $file_string, strlen($file_string) );
	fclose($fh);
	@chmod ($Creatfile.'.csv',0777);
	}
	*/

	@ob_implicit_flush(0);
	//@header("Content-Type: text/html;  charset=UTF-8; name=\"$Creatfile.csv\"");
	@header("Content-Type: text/x-delimtext;  name=\"".$Creatfile.".xml\"");
	@header("Content-disposition: attachment;filename=\"".$Creatfile.".xml\"");
	//@header("Content-Type: application/ms-excel;  name=\"".$Creatfile.".xml\"");
	//@header("location:".$Creatfile.'.csv');

}
//echo "<script language=javascript>window.close();</script>";
?>