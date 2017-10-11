<?php
include_once "Check_Admin.php";

/**
 *  装载客户服务语言包
 */
include "../language/".$INFO['IS']."/KeFu_Pack.php";

if ($_GET['id']!=""){

	$id = intval($_GET['id']);

	$Query = $DB->query("select * from `{$INFO[DBPrefix]}photo` where id=".intval($id)." limit 0,1");

	$Num   = $DB->num_rows($Query);



	if ($Num>0){

		$Result= $DB->fetch_array($Query);

		$name            =  $Result['name'];

	}else{

		echo "<script language=javascript>javascript:window.history.back();</script>";

		exit;

	}



}

if ($_POST['Action']=="save"){

	//print_r($_POST);exit;

	$FileName1="";

	$BigFile="";



	$BigFile=strtolower($_FILES['flpic']['name']);



	//上傳圖片

   if ($BigFile != "")

   {



	  $LastTime=$LastDay=date("mdHi");

      srand((double)microtime()*1000);

      $RandS=Rand();

      $FileName1=$LastTime.$RandS.strstr($BigFile,'.');

	  $bigimg= CopyF($_FILES['flpic']['tmp_name'],$BigFile,"../UploadFile/photo_img/".$_GET['id']."/images",$FileName1,"");

	  $db_string = $DB->compile_db_insert_string( array (

		'pid'       => trim($_GET['id']),

		'pic'       => trim($bigimg),

		'content'       => trim($_POST['content']),

		'title'       => trim($_POST['title']),

		'url'       => trim($_POST['url']),

		)      );

	  $Sql="INSERT INTO `{$INFO[DBPrefix]}image` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

	  $Result_Insert = $DB->query($Sql);

	  echo "<script language='javascript'>location.href='admin_image.php?id=" . $_GET['id'] . "';</script>";

   }

   	//更新標題與排序
   	$iid  = $_POST['iid'];
   	$title  = $_POST['title1'];
   	$sort  = $_POST['sort'];
   	$content  = $_POST['content1'];
		$url  = $_POST['url'];
	$iid_num   = count($iid);
	for ($i=0;$i<$iid_num;$i++){
		$Sql = "UPDATE `{$INFO[DBPrefix]}image` SET title='".$title[$i]."',sort='".intval($sort[$i])."',content='".$content[$i]."',url='".$url[$i]."' WHERE iid=".intval($iid[$i]);
		$Result = $DB->query($Sql);
	}
}

if($_GET['Action']=="del"){

	$FileName=$_GET['FileName'];

	$Result =  $DB->query("delete from `{$INFO[DBPrefix]}image` where iid=".intval($_GET['iid']));

	if (file_exists("../UploadFile/photo_img/".$_GET['id']."/images/".$FileName)){

		unlink("../UploadFile/photo_img/".$_GET['id']."/images/".$FileName);

		makexml($_GET['id']);

	}

	echo "<script language='javascript'>location.href='admin_image.php?id=" . $_GET['id']."';</script>";

}



function CopyF($Fpath,$Fpath_name,$UpLinkP,$FileName,$linkpath){

   $Fpath_name=strtolower($Fpath_name);

   if (strstr($Fpath_name,'.')!=".jpg" && strstr($Fpath_name,'.')!=".gif" && strstr($Fpath_name,'.')!=".png" && strstr($Fpath_name,'.')!=".swf"){

	  senderror("只能上傳jpg,gif,png,swf格式的檔");

      exit;

   }

   if (filesize($Fpath)/1024>500){

	  senderror("最大只能上傳500k");

      exit;

   }





   while (file_exists($UpLinkP."/".$FileName)){

       srand((double)microtime()*1000);

       $RandS=Rand();

       $FileName=$LastTime.$RandS.strstr($Fpath_name,'.');

       //echo $FileName;

    }



    //上傳

   if (copy($Fpath,$UpLinkP."/".$FileName)){

      //sendinfo("上傳完?<br>檔案名：".$FileName. "<br>文件大小：".round(filesize($Fpath)/1024)."KB",$linkpath);

	  if ($linkpath !=""){

		  makexml($_GET['id']);

	      echo "<script language='javascript'>alert('上傳完畢')</script>";

	  }

   }

   else{

      exit;

   }

   if ($linkpath !="")

      echo "<script language='javascript'>location.href='" .$linkpath. "';</script>";

   return $FileName;

}

function sysSortArray($ArrayData,$KeyName1,$SortOrder1 = "SORT_ASC",$SortType1 = "SORT_REGULAR") {

	if(!is_array($ArrayData)) {

	return $ArrayData;

	}

	// Get args number.

	$ArgCount = func_num_args();

	// Get keys to sort by and put them to SortRule array.

	for($I = 1;$I < $ArgCount;$I ++) {

		$Arg = func_get_arg($I);

		if(!eregi("SORT",$Arg)) {

			$KeyNameList[] = $Arg;

			$SortRule[]    = '$'.$Arg;

		}

		else {

			$SortRule[]    = $Arg;

		}

	}



	// Get the values according to the keys and put them to array.

	foreach($ArrayData AS $Key => $Info) {

		foreach($KeyNameList AS $KeyName) {

			${$KeyName}[$Key] = $Info[$KeyName];

		}

	}



	// Create the eval string and eval it.

	$EvalString = 'array_multisort('.join(",",$SortRule).',$ArrayData);';

	eval ($EvalString);

	return $ArrayData;

}



function makexml($id){

	 $Creatfile ="../UploadFile/photo_img/".$id."/monoslideshow.xml";

	$xmlstr = "

<?xml version=\"1.0\" encoding=\"utf-8\"?>



<!--

	Monoslideshow configuration file

	Please visit http://www.monoslideshow.com for more info

-->



<slideshow>



	<preferences



		backgroundColor = \"000000\"

		showLoadingIcon = \"false\"

		showImageInfo = \"always\"

		viewport = \"5,40,645,370\"

		imageTransition = \"fadeInOut\"

		kenBurnsMode = \"random\"

		kenBurnsVariationPercent = \"20\"

		imageInfoAlpha = \"0\"

		imageInfoAlign = \"topRight\"

		imageInfoTransitionTime = \"0.2\"

		imageInfoWidth = \"380\"

		imageInfoMaxSize = \"false\"

		imageInfoPadding = \"0\"

		imageInfoTextAlign = \"right\"

		imageInfoTitleFont = \"helvetica\"

		imageInfoDescriptionFont = \"unibody\"

		imageInfoDescriptionSize = \"8\"

		imageInfoDescriptionMargin = \"-2\"

		thumbnailWindowAlwaysOn = \"true\"

		thumbnailWindowAlpha = \"0\"

		thumbnailWindowShadowSize = \"0\"

		thumbnailWindowShadowAlpha = \"0\"

		thumbnailWindowLineWidth = \"0\"

		thumbnailWindowAlign = \"bottomLeft\"

		thumbnailWindowRows = \"1\"

		thumbnailWindowColumns = \"11\"

		thumbnailWindowAutoSize = \"false\"

		thumbnailWindowPadding = \"5\"

		thumbnailWindowIconMargin = \"4\"

		thumbnailWindowIconSize = \"10\"

		thumbnailWindowIconRollOverColor = \"FF4B1F\"

		thumbnailWindowInfoFont = \"unibody\"

		thumbnailWindowInfoSize = \"8\"

		thumbnailWidth = \"39\"

		thumbnailHeight = \"28\"

		thumbnailRoundedCorners = \"8\"

		thumbnailSpacing = \"4\"

		thumbnailHoverDistance = \"5\"

		thumbnailHoverShadowSize = \"5\"

		albumWindowAlpha = \"100\"

		albumWindowShadowSize = \"0\"

		albumWindowShadowAlpha = \"0\"

		albumWindowLineWidth = \"0\"

		albumWindowRoundedCorners = \"0\"

		albumWindowPadding = \"10\"

		albumWindowIconMargin = \"10\"

		albumWindowIconSize = \"10\"

		albumWindowIconRollOverColor = \"FF4B1F\"

		albumWindowInfoFont = \"unibody\"

		albumWindowInfoSize = \"8\"

		albumRoundedCorners = \"5\"

		albumSpacing = \"4\"

		albumShadowSize = \"0\"

		albumShadowAlpha = \"0\"

		albumInfoWidth = \"180\"

		albumInfoMargin = \"4\"

		albumInfoPadding = \"4\"

		albumInfoRoundedCorners = \"4\"

		albumInfoColor = \"444444\"

		albumInfoTitleFont = \"helvetica\"

		albumInfoTitleColor = \"FF4B1F\"

		albumInfoTitleContainsNumber = \"false\"

		albumInfoDescriptionFont = \"unibody\"

		albumInfoDescriptionSize = \"8\"

		albumInfoDescriptionColor = \"999999\"

		controlAlign = \"topLeft\"

		controlRoundedCorners = \"0\"

		controlAlpha = \"100\"

		controlLineWidth = \"0\"

		controlShadowSize = \"0\"

		controlIconRollOverColor = \"FF4B1F\"

		controlPadding = \"4\"

		controlIconSize = \"10\"

		controlIconSpacing = \"10\"

		autoPause = \"false\"

		markFile = \"\"

		markAlign = \"topLeft\"

		markMarginX = \"285\"

		markMarginY = \"275\"



	/>





	";

		$handle=opendir('../UploadFile/photo_img'."/".$id."/images");

		$file_array = array();

		$i = 0;

        while ($file = readdir($handle)) {

		   $filel=strtolower($file);

		   if(substr($filel,-3,3)=="gif" || substr($filel,-3,3)=="jpg" || substr($filel,-3,3)=="png" || substr($filel,-3,3)=="bmp")

		   {

			  $filetime = filemtime("../UploadFile/photo_img/".$id."/images/" . $file);

			  $filesize = round(filesize("../UploadFile/photo_img/".$id."/images/" . $file)/1024);

			  $file_array[$i]['name'] = $file;

			  $file_array[$i]['time'] = $filetime;

			  $file_array[$i]['size'] = $filesize;

			  $i++;

		   }

        }

	$xmlstr .= "<album thumbnail=\"". $file_array[0]['name'] . "\" title=\"\" description=\"\" imagePath=\"images\" thumbnailPath=\"images\">";

	if(is_array($file_array)){

		foreach($file_array as $k=>$v){

			$xmlstr .= "<img src=\"" . $v['name'] . "\" title=\"\" description=\"\"/>";

		}

	}



	$xmlstr .= "</album>



</slideshow>";

	if ( $fh = fopen( $Creatfile, 'w+' ) )

	{

		fputs ($fh, $xmlstr, strlen($xmlstr) );

		fclose($fh);

		@chmod ($Creatfile,0777);

	}

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
.picture03 img{
	width: expression(this.width > 165 ? 165: true);
    max-width: 165px;
    height: expression(this.height > 165 ? 165: true);
    max-height:165px;
	opacity:1;
}
</style>
<TITLE>相簿管理--&gt;圖片管理</TITLE></HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">

<?php include_once "head.php";?>

<SCRIPT language=javascript src="../js/function.js" type=text/javascript></SCRIPT>

<SCRIPT language=javascript>

	function checkform(){

		form1.submit();

	}

	function checkform1(){



		form1.submit();

	}



</SCRIPT>



<div id="contain_out"><? include "Order_state.php";?>

  <FORM name=form1 action='admin_image.php?id=<?php echo $_GET['id'];?>'  method="post" enctype="multipart/form-data">

  <input type="hidden" name="Action" value="save">

  <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

                <TBODY>

                  <TR>

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>

                    <TD class=p12black noWrap><SPAN  class=p9orange>相簿管理--&gt;圖片管理</SPAN></TD>

                </TR></TBODY></TABLE></TD>

            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>

              <TBODY>

                <TR>

                  <TD align=middle>

                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>

                      <TBODY>

                        <TR>

                          <TD align=middle width=79><!--BUTTON_BEGIN-->

                            <TABLE>

                              <TBODY>

                                <TR>

                                  <TD vAlign=bottom noWrap class="link_buttom">

                            <a href="admin_photo_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>

                  <TD align=middle>

                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>

                      <TBODY>

                        <TR>

                          <TD align=middle width=79><!--BUTTON_BEGIN-->

                            <TABLE>

                              <TBODY>

                                <TR>

                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD>

                                  </TR></TBODY></TABLE><!--BUTTON_END-->



                            </td>



                          </TR></TBODY></TABLE>



                    </TD></TR></TBODY></TABLE>

              </TD>

            </TR>

          </TBODY>

        </TABLE>



                      <TABLE class=allborder cellSpacing=0 cellPadding=2 width="100%" align=center bgColor=#f7f7f7 border=0>

                        <TBODY>

                          <TR>

                            <TD width="18%" height="39" align=right noWrap>&nbsp;</TD>

                            <TD colspan="2" align=left noWrap><?php echo $name;?></TD></TR>

                          <TR>

                            <TD noWrap align=right width="18%">標題名稱：</TD>

                            <TD height="25" colspan="2" align=left noWrap><input type="title" name="title"></TD>

                            </TR>


                          <TR>

                            <TD noWrap align=right width="18%">上傳圖片：</TD>

                            <TD height="25" colspan="2" align=left noWrap><input type="file" name="flpic"></TD>

                            </TR>

                            <TD noWrap align=right>說明：</TD>

                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('textarea','content','',"cols=80 rows=8      ")?></TD>

                            </TR>

                          <TR>

                            <TD noWrap align=right>&nbsp;</TD>

                            <TD colspan="2" align=left noWrap>&nbsp;</TD>

                            </TR>

                          </TBODY>

                        </TABLE>



                      <table width="100%" border="0" cellpadding="00" cellspacing="0" class="p9black">

                        <tr>

                          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="00">



                            <?

		$col=7;

		$row=1;

		$pagesize = 24;

		$curpage = intval($_GET['page']);

		if ($curpage == 0)

			$curpage = 1;

		$f = $pagesize * ($curpage-1);



		$handle=opendir('../UploadFile/photo_img'."/".$_GET['id']."/images");

		$file_array = array();

		$i = 0;

		$Sql      = "select * from `{$INFO[DBPrefix]}image` where pid='" . $_GET['id'] . "' order by sort asc";

		$Query    = $DB->query($Sql);

        while ($rs=$DB->fetch_array($Query)) {

			$file = $rs['pic'];

		   $filel=strtolower($file);

		   if(substr($filel,-3,3)=="gif" || substr($filel,-3,3)=="jpg" || substr($filel,-3,3)=="png" || substr($filel,-3,3)=="bmp")

		   {

			  $filetime = filemtime("../UploadFile/photo_img/".$_GET['id']."/images/" . $file);

			  $filesize = round(filesize("../UploadFile/photo_img/".$_GET['id']."/images/" . $file)/1024);

			  $file_array[$i]['name'] = $file;

			  $file_array[$i]['time'] = $filetime;

			  $file_array[$i]['size'] = $filesize;

			  $file_array[$i]['iid'] = $rs['iid'];

			  $file_array[$i]['content'] = $rs['content'];

			  $file_array[$i]['title'] = $rs['title'];

				$file_array[$i]['url'] = $rs['url'];

			  $file_array[$i]['sort'] = $rs['sort'];

			  $i++;

		   }

        }



		$count = count($file_array);

		$pagecount = ceil($count/$pagesize);

		//$file_array = $count>0?sysSortArray($file_array,"time","SORT_DESC"):$file_array;

		$l = $count>$pagesize?$f + $pagesize:$count;

		?>

                            <tr>

                              <td height="25" class="p9black">圖片列表：</td>

                            </tr>

                            <tr><td class="p9black">

                              <?php

      for($z=1;$z<=$pagecount;$z++){

	  ?>

                              <?php if($z==$curpage) echo "[<b>";?><a href="admin_image.php?page=<?php echo $z;?>"><?php echo $z;?></a><?php if($z==$curpage) echo "</b>]";?>&nbsp;

                              <?php

	  }

	  ?>

                              </td></tr>

                            <tr>

                              <td height="25"><table width="100%" border="0" cellpadding="00" cellspacing="5">

                                <?php

		for($j=$f;$j<$l;$j++){

			if ($row==1)

			      echo "<tr>";

			  echo '<td width="165"><table width="165" border="0" cellpadding="5" cellspacing="1" style="border: solid 1px #ccc" >';

			  echo '<tr>';

			  echo '<input type="hidden" name="iid[]" value="' . $file_array[$j]['iid'] . '">';

			  echo '</tr><tr>';

			  echo '<td height="165" align="center" bgcolor="#FFFFFF" class="picture03"><img src="' . $INFO['site_url'] . '/UploadFile/photo_img/'.$_GET['id'].'/images/' .$file_array[$j]['name']. '" class="picture03"></td>';

			  echo '</tr><tr>';

			  echo '<td align="left" bgcolor="#FFFFFF" class="p9black">標題：<input name="title1[]" value="' .$file_array[$j]['title']. '" size="23"></td>';

			  echo '</tr><tr>';

				echo '<td align="left" bgcolor="#FFFFFF" class="p9black">網址：<input name="url[]" value="' .$file_array[$j]['url']. '" size="23"></td>';

				echo '</tr><tr>';

			  echo '<td align="left" bgcolor="#FFFFFF" class="p9black">排序：<input name="sort[]" value="' .$file_array[$j]['sort']. '" size="2"></td>';

			  echo '</tr><tr>';

			  echo '<td align="center" bgcolor="#FFFFFF" class="p9black">' .$file_array[$j]['name']. '</td>';

			  echo '</tr><tr>';

			  echo '<td align="center" bgcolor="#FFFFFF" class="p9black"><textarea name="content1[]" cols="23" rows="4">'.$file_array[$j]['content'].'</textarea></td></tr><tr>';

			  echo '<td align="center" bgcolor="#FFFFFF" class="p9black">文件大小：' .$file_array[$j]['size']. 'kb</td>';

			  echo '<tr><td align="center" bgcolor="#FFFFFF" class="p9black">' .date("Y-m-d H:i",$file_array[$j]['time']). '</td></tr>';

			  echo '</tr><tr><td align="center" style="background-color:#ebebeb"><a href="admin_image.php?FileName=' .$file_array[$j]['name']. '&Action=del&id='. $_GET['id'] .'&iid=' . $file_array[$j]['iid'] . '" class="p9black">刪除</a></td>';

			  echo '</tr></table></td>';

			  $row++;

			  if ($row==$col)

			  {

			      echo "</tr>";

				  $row=1;

			  }

		}

        closedir($handle);

		?>

                                </table></td>

                            </tr>

                            <tr><td class="p9black">

                              <?php

      for($z=1;$z<=$pagecount;$z++){

	  ?>

                              <?php if($z==$curpage) echo "[<b>";?><a href="admin_image.php?page=<?php echo $z;?>"><?php echo $z;?></a><?php if($z==$curpage) echo "</b>]";?>&nbsp;

                              <?php

	  }

	  ?>

                              </td></tr>

                            </table></td>

                        </tr>



    </table>

  </FORM>

</div>

<div align="center"><?php include_once "botto.php";?></div>

</BODY>

</HTML>
