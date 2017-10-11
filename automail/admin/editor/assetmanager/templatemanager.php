<?php
session_start();
if (!isset($_SESSION['idAdmin']) and !isset($_SESSION['adminName']) )
{
  echo("No authorization.");
  exit();
}
include('../../includes/languages.php');   
$sPath	=	"../../../assets";
$bReturnAbsolute=true;
$sMsg = "";
$sBase0	=	$sPath;	$sName0="assets";
$currFolder	=	$sPath;
$upAllowed=true;
$ffilter="";	// filter here
$sUploadedFile="";
$MaxFileSize = 500000000;
$AllowedTypes = "|html|htm|";
//$AllowedTypes = "*";

$bReadOnly0=false;
$bReadOnly1=false;
$bReadOnly2=false;
$bReadOnly3=false;
$sBase1="";	$sName1="";
$sBase2="";	$sName2="";
$sBase3=""; $sName3="";


function isTypeAllowed($sFileName)
  {
  global $AllowedTypes;
  if($AllowedTypes=="*") return true;

  //if( ereg($AllowedTypes,getExt($sFileName)) )
  if((strpos($AllowedTypes,'|'.getExt($sFileName).'|')!==false)&&(substr_count($sFileName,'.')==1))
    return true;
  else
    return false;
  }

if(isset($_FILES["File1"])) {
 //echo ($_FILES["File1"]['name']); die();
  if(isset($_POST["inpCurrFolder2"]))$currFolder=$_POST['inpCurrFolder2'];
  if(isset($_REQUEST["inpFilter"]))$ffilter=$_REQUEST["inpFilter"];

  if($MaxFileSize && ($_FILES['File1']['size'] > $MaxFileSize))
    {
    $sMsg = "The file exceeds the maximum size allowed.";
    }
  else if(!isTypeAllowed($_FILES['File1']['name']))
    {
    $sMsg = "The File Type is not allowed.";
    }	//we remove the ext, we 64encode and add .html extension
  else if (move_uploaded_file($_FILES['File1']['tmp_name'], $currFolder."/".basename(base64_encode(str_ireplace('.'.getExt($_FILES['File1']['name']), '', $_FILES['File1']['name'])).'.html')))
    {
    $sMsg = "";
    $sUploadedFile=$_FILES['File1']['name'];
    @chmod($currFolder."/".basename($_FILES['File1']['name']), 0644);
    }
  else
    {
    $sMsg = "Upload failed.";
    }
  }
else
  {
  if(isset($_POST["inpCurrFolder"]))$currFolder=$_POST['inpCurrFolder'];
  if(isset($_REQUEST["ffilter"]))$ffilter=$_REQUEST["ffilter"];
  }

if(isset($_POST["inpFileToDelete"]) && $upAllowed==true)
  {
  $filename=pathinfo($_POST["inpFileToDelete"]);
  $filename=$filename['basename'];
  if($filename!="")
    unlink($currFolder . "/" . $filename);
  $sMsg = "";
  }


/*** Permission ***/
$bWriteFolderAdmin=false;
if($sBase0!="")
  {
  if(strtolower($currFolder)!=str_replace(strtolower($sBase0),"",strtolower($currFolder)) AND $bReadOnly0==true) $bWriteFolderAdmin=true;
  }
if($sBase1!="")
  {
  if(strtolower($currFolder)!=str_replace(strtolower($sBase1),"",strtolower($currFolder)) AND $bReadOnly1==true) $bWriteFolderAdmin=true;
  }
if($sBase2!="")
  {
  if(strtolower($currFolder)!=str_replace(strtolower($sBase2),"",strtolower($currFolder)) AND $bReadOnly2==true) $bWriteFolderAdmin=true;
  }
if($sBase3!="")
  {
  if(strtolower($currFolder)!=str_replace(strtolower($sBase3),"",strtolower($currFolder)) AND $bReadOnly3==true) $bWriteFolderAdmin=true;
  }
$sFolderAdmin="";
if($bWriteFolderAdmin)$sFolderAdmin="style='display:none'";
/*** /Permission ***/


Function writeFolderSelections()
  {
  global $sBase0;
  global $sBase1;
  global $sBase2;
  global $sBase3;
  global $sName0;
  global $sName1;
  global $sName2;
  global $sName3;
  global $currFolder;

  echo "<select name='selCurrFolder' id='selCurrFolder' onchange='changeFolder()' class='inpSel'>";
  recursive($sBase0,$sBase0,$sName0);
  if($sBase1!="")recursive($sBase1,$sBase1,$sName1);
  if($sBase2!="")recursive($sBase2,$sBase2,$sName2);
  if($sBase3!="")recursive($sBase3,$sBase3,$sName3);
  echo "</select>";
  }

Function recursive($sPath,$sPath_base,$sName)
  {
  global $sBase0;
  global $sBase1;
  global $sBase2;
  global $sBase3;
  global $currFolder;

  if($sPath==$sBase0 ||$sPath==$sBase1 ||$sPath==$sBase2 ||$sPath==$sBase3)
    {
    if($currFolder==$sPath)
      echo "<option value='$sPath' selected>$sName</option>";
    else
      echo "<option value='$sPath'>$sName</option>";
    }

  $oItem=opendir($sPath);
  $aItem = array();
  while($sItem=readdir($oItem))
  {
  $aItem[] = $sItem;
  }
  sort($aItem);
  for ($i=0; $i<count($aItem); $i++)
  //while($sItem=readdir($oItem))
    {

    $sItem = $aItem[$i];

    if($sItem=="."||$sItem=="..")
      {
      }
    else
      {
      $sCurrent=$sPath."/".$sItem;
      $fIsDirectory=is_dir($sCurrent);

      $sDisplayed=str_replace($sBase0,"",$sCurrent);
      if($sBase1<>"") $sDisplayed=str_replace($sBase1,"",$sDisplayed);
      if($sBase2<>"") $sDisplayed=str_replace($sBase2,"",$sDisplayed);
      if($sBase3<>"") $sDisplayed=str_replace($sBase3,"",$sDisplayed);
      $sDisplayed=$sName.$sDisplayed;

      if($fIsDirectory)
        {
        if($currFolder==$sCurrent)
          echo "<option value='$sCurrent' selected>$sDisplayed</option>";
        else
          echo "<option value='$sCurrent'>$sDisplayed</option>";

        recursive($sCurrent,$sPath,$sName);
        }
      }
    }
  closedir($oItem);
  }

function getExt($sFileName)//ffilter
  {
  $sExt="";
  $sTmp=$sFileName;
  while($sTmp!="")
    {
    $sTmp=strstr($sTmp,".");
    if($sTmp!="")
      {
      $sTmp=substr($sTmp,1);
      $sExt=$sTmp;
      }
    }
  return strtolower($sExt);
  }

function writeFileSelections()
  {
  global $sFolderAdmin;
  global $ffilter;
  global $sUploadedFile;
  global $sBaseRoot0;
  global $sBaseRoot1;
  global $sBaseRoot2;
  global $sBaseRoot3;
  global $currFolder;
  global $bWriteFolderAdmin;

  $nIndex=0;
  $bFileFound=false;
  $iSelected="";

  echo "<div style='overflow:auto;height:277px;width:100%;margin-top:3px;margin-bottom:2px;'>";
  echo "<table  style='border:#cfcfcf 1px solid' cellpadding=1 cellspacing=0 width=100% height=100% >"; //files list table
  $sColor = "#e7e7e7";

  $oItem=opendir($currFolder);
  while($sItem=readdir($oItem))
  {
  $aItem[] = $sItem;
  }
  sort($aItem);
  #while($sItem=readdir($oItem))
  for ($i=0; $i<count($aItem); $i++)
    {
    $sItem = $aItem[$i];

    if($sItem=="."||$sItem=="..")
      {
      }
    else
      {
      $sCurrent=$currFolder."/".$sItem;
      $fIsDirectory=is_dir($sCurrent);


      if(!$fIsDirectory)
        {

        //ffilter ~~~~~~~~~~
        $bDisplay=false;
        $sExt=getExt($sItem);
        if ($sExt=="html" || $sExt=="htm") {
			$bDisplay=true;
		}
        else  {
          $bDisplay=false;
        }
        //~~~~~~~~~~~~~~~~~~

        if($bDisplay)    {
          $nIndex=$nIndex+1;
          $bFileFound=true;
          if($sBaseRoot0=="") {
            $sCurrent_virtual=$sCurrent;
          } else {
            $sCurrent_virtual=str_replace($sBaseRoot0,"",$sCurrent);
          }
          if($sBaseRoot1!="")$sCurrent_virtual=str_replace($sBaseRoot1,"",$sCurrent_virtual);
          if($sBaseRoot2!="")$sCurrent_virtual=str_replace($sBaseRoot2,"",$sCurrent_virtual);
          if($sBaseRoot3!="")$sCurrent_virtual=str_replace($sBaseRoot3,"",$sCurrent_virtual);

          if($sColor=="#EFEFF5")
            $sColor = "";
          else
            $sColor = "#EFEFF5";

          //icons
          $sIcon="ico_unknown.gif";
          if($sExt=="asp")$sIcon="ico_asp.gif";
          if($sExt=="bmp")$sIcon="ico_bmp.gif";
          if($sExt=="css")$sIcon="ico_css.gif";
          if($sExt=="doc")$sIcon="ico_doc.gif";
          if($sExt=="exe")$sIcon="ico_exe.gif";
          if($sExt=="gif")$sIcon="ico_gif.gif";
          if($sExt=="htm")$sIcon="ico_htm.gif";
          if($sExt=="html")$sIcon="ico_htm.gif";
          if($sExt=="jpg")$sIcon="ico_jpg.gif";
          if($sExt=="js")$sIcon="ico_js.gif";
          if($sExt=="mdb")$sIcon="ico_mdb.gif";
          if($sExt=="mov")$sIcon="ico_mov.gif";
          if($sExt=="mp3")$sIcon="ico_mp3.gif";
          if($sExt=="pdf")$sIcon="ico_pdf.gif";
          if($sExt=="png")$sIcon="ico_png.gif";
          if($sExt=="ppt")$sIcon="ico_ppt.gif";
          if($sExt=="mid")$sIcon="ico_sound.gif";
          if($sExt=="wav")$sIcon="ico_sound.gif";
          if($sExt=="wma")$sIcon="ico_sound.gif";
          if($sExt=="swf")$sIcon="ico_swf.gif";
          if($sExt=="txt")$sIcon="ico_txt.gif";
          if($sExt=="vbs")$sIcon="ico_vbs.gif";
          if($sExt=="avi")$sIcon="ico_video.gif";
          if($sExt=="wmv")$sIcon="ico_video.gif";
          if($sExt=="mpeg")$sIcon="ico_video.gif";
          if($sExt=="mpg")$sIcon="ico_video.gif";
          if($sExt=="xls")$sIcon="ico_xls.gif";
          if($sExt=="zip")$sIcon="ico_zip.gif";

          $sTmp1=strtolower($sItem);
          $sTmp2=strtolower($sUploadedFile);
          if($sTmp1==$sTmp2)
            {
            $sColorResult="yellow";
            $iSelected=$nIndex;
            }
          else
            {
            $sColorResult=$sColor;
            }

          echo "<tr style='background:".$sColorResult."'>";
          echo "<td valign=top><img src='images/".$sIcon."'></td>";
          echo '<td valign=top width="70%" style="border-bottom:#cfcfcf 0px solid"><div style="word-wrap:break-word;width:200px;"><u id="idFile'.$nIndex.'" style="cursor:pointer;" onclick="selectFile('.$nIndex.')">'.base64_decode(str_ireplace($sExt, '',$sItem)).'</u></div></td>';
          echo "<td style='border-bottom:#cfcfcf 0px solid'><img style='cursor:pointer;' onclick=\"downloadFile(".$nIndex.")\" src='download.gif'></td>";
          echo "<input type=hidden name=inpFile".$nIndex." id=inpFile".$nIndex." value=\"".$sCurrent_virtual."\">";
		  //echo "<input type=text value=\"".($nIndex)."\">";
          echo "<td style='border-bottom:#cfcfcf 0px solid' valign=top align=right nowrap>".round(filesize($sCurrent)/1024,1)." kb&nbsp;</td>";
          echo "<td style='border-bottom:#cfcfcf 0px solid' valign=top nowrap onclick=\"deleteFile(".$nIndex.")\"><u style='font-size:10px;cursor:pointer;color:crimson' ".$sFolderAdmin.">";
          if(!$bWriteFolderAdmin)
            {
            echo '<img src="../../images/drop.png" width="10" height="10">';
            }
          echo "</u></td>";



          echo "</tr>";
          }
        }
      }
    }

  if($bFileFound==false)
    echo "<tr><td colspan=4 height=100% align=center><script>document.write(getTxt('Empty...'))</script></td></tr></table></div>";
  else
    echo "<tr><td colspan=4 height=100% ></td></tr></table></div>";
  	echo "<input type=hidden name=inpUploadedFile id=inpUploadedFile value='".$iSelected."'>";
  	echo "<input type=hidden name=inpNumOfFiles id=inpNumOfFiles value='".$nIndex."'>";

  closedir($oItem);
  }
?>
<base target="_self">
<html>
<head>
<title><?php echo FILEMANAGER_6;?></title>
<meta http-equiv="Content-Type" content="text-html; charset=utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<link href="style.css" rel="stylesheet" type="text/css">
<script src="../../scripts/prototype.js" type="text/javascript"></script>
<?php

$sLang="en-US";
if(isset($_REQUEST["lang"]))
  {
  $sLang=$_REQUEST["lang"];
  if($sLang=="")$sLang="en-US";
  }
?>
<script>
var bReturnAbsolute=<?php  if($bReturnAbsolute){echo "true";} else{echo "false";} ?>;
var activeModalWin;
function getTxt(str) {
return str;
}
function getAction()
  {
  //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  //Clean previous ffilter=...
  sQueryString=window.location.search.substring(1)
  sQueryString=sQueryString.replace(/ffilter=media/,"")
  sQueryString=sQueryString.replace(/ffilter=image/,"")
  sQueryString=sQueryString.replace(/ffilter=flash/,"")
  sQueryString=sQueryString.replace(/ffilter=/,"")
  if(sQueryString.substring(sQueryString.length-1)=="&")
    sQueryString=sQueryString.substring(0,sQueryString.length-1)

  if(sQueryString.indexOf("=")==-1)
    {//no querystring
    sAction="templatemanager.php?ffilter="+document.getElementById("selFilter").value;
    }
  else
    {
    sAction="templatemanager.php?"+sQueryString+"&ffilter="+document.getElementById("selFilter").value
    }
  //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  return sAction;
  }

function applyFilter()//ffilter
  {
  var Form1 = document.forms.Form1;

  Form1.elements.inpCurrFolder.value=document.getElementById("selCurrFolder").value;
  Form1.elements.inpFileToDelete.value="";

  Form1.action=getAction()
  Form1.submit()
  }
function refreshAfterDelete(sDestination)
  {
  var Form1 = document.forms.Form1;

  Form1.elements.inpCurrFolder.value=sDestination;
  Form1.elements.inpFileToDelete.value="";

  Form1.action=getAction()
  Form1.submit();
  }
function changeFolder()
  {
  var Form1 = document.forms.Form1;

  Form1.elements.inpCurrFolder.value=document.getElementById("selCurrFolder").value;
  Form1.elements.inpFileToDelete.value="";

  Form1.action=getAction();
  Form1.submit();
  }

function upload()
  {
  var Form2 = document.forms.Form2;

  if(Form2.elements.File1.value == "")return;

  var sFile=Form2.elements.File1.value.substring(Form2.elements.File1.value.lastIndexOf("\\")+1);
  for(var i=0;i<document.getElementById("inpNumOfFiles").value;i++)
    {
    if(sFile==document.getElementById("idFile"+(i*1+1)).innerHTML)
      {
      if(confirm(getTxt("File already exists. Do you want to replace it?"))!=true)return;
      }
    }

  Form2.elements.inpCurrFolder2.value=document.getElementById("selCurrFolder").value;
  document.getElementById("idUploadStatus").innerHTML=getTxt("Uploading...")

  Form2.action=getAction()
  Form2.submit();
  }
function newFolder()
  {

    var currentPath = window.location.href;
    currentPath = currentPath.substring(0, currentPath.lastIndexOf("/"));
    parent.modalDialogShow(currentPath + "/foldernew.php", 250, 170, window);
  }
function deleteFolder()
  {
  var selCurrFolder = document.getElementById("selCurrFolder");

  if(selCurrFolder.value.toLowerCase()==document.getElementById("inpAssetBaseFolder0").value.toLowerCase() ||
  selCurrFolder.value.toLowerCase()==document.getElementById("inpAssetBaseFolder1").value.toLowerCase() ||
  selCurrFolder.value.toLowerCase()==document.getElementById("inpAssetBaseFolder2").value.toLowerCase() ||
  selCurrFolder.value.toLowerCase()==document.getElementById("inpAssetBaseFolder3").value.toLowerCase())
    {
    alert(getTxt("Cannot delete Asset Base Folder."));
    return;
    }

  var currentPath = window.location.href;
  currentPath = currentPath.substring(0, currentPath.lastIndexOf("/"));
  parent.modalDialogShow(currentPath + "/folderdel.php", 250, 170, window);

  }
function downloadFile(index)
  {
  sFile_RelativePath = document.getElementById("inpFile"+index).value;
  window.open(sFile_RelativePath)
  }
function selectFile(index)   {
	var path= document.URL.replace("/admin/editor/assetmanager/templatemanager.php","");
	path= path.replace("?ffilter=","");

	sFile_RelativePath = document.getElementById("inpFile"+index).value;
  	$fileName			= sFile_RelativePath.replace("../../../assets/","");
  	//alert($fileName);
	//This will make an Absolute Path
  	if(bReturnAbsolute) {
       //	sFile_RelativePath = window.location.protocol + "//" + window.location.host.replace(/:80/,"") + sFile_RelativePath
	sFile_RelativePath 	= path+"/assets/"+$fileName;
	sFile_RelativePath	= sFile_RelativePath.replace(/:80/,"");
	// this hack works for spaces in file names. But I dont need it since I encode the file names.
	//sFile_RelativePath	= sFile_RelativePath.replace(/ /g, '%20');

    }
	document.getElementById("inpSource").value=encodeURI(sFile_RelativePath);
	var arrTmp = sFile_RelativePath.split(".");
  	var sFile_Extension = arrTmp[arrTmp.length-1]
  	var sHTML="";
  	if (sFile_Extension.toUpperCase()=="HTML" || sFile_Extension.toUpperCase()=="HTM") {
		sHTML = "<iframe id='pframe' name='pframe' width='100%' height='100%' src="+encodeURI(sFile_RelativePath)+"></iframe>" 
	}
  	//Files (Hyperlinks)
  	else    {
    	sHTML = "<br><br><br><br><br><br>Not Available"
   	}
	//alert(document.getElementById("inpSource").value);
   	document.getElementById("idPreview").innerHTML = sHTML;
}
  
  
  function appendFile()   {
	var url="../../grabUrl.php";
	//var params =  "resource="+$F('inpSource'); //old: fails when spaces int he file name
	//improved to hanfdle spaces but fails with Greek. If change to decodeURI, works with Greek but not with space..
	//alert($F('inpSource'));
	var params =  "resource="+encodeURI($F('inpSource'));	
	//alert(params);
	new Ajax.Request(url, {onCreate:showLoader("loader"),onComplete: showResponse, onException:showException, onFailure:showFailure, asynchronous:false, method: 'get', evalScripts:false, parameters:params});
	function showResponse(req) {pframeFullContent=req.responseText;}
	function showException(req) {alert("Exception problem, please try again."); alert(req.responseText); }
	function showFailure(req) {alert("Request failed, please try again.");}
	function showLoader(div){$(div).show();$(div).innerHTML='<img id="bigLoader" src="../../images/waitSmall.gif">';}
	var obj=parent.oUtil.obj;
	obj.putHTML(pframeFullContent);	//added decodeURI
 }
  
function deleteFile(index)
  {
  if (confirm(getTxt("Delete this file ?")) == true)
    {
    sFile_RelativePath = document.getElementById("inpFile"+index).value;

    var Form1 = document.getElementById("Form1");
    Form1.elements.inpCurrFolder.value=document.getElementById("selCurrFolder").value;
    Form1.elements.inpFileToDelete.value=sFile_RelativePath;

    Form1.action=getAction()
    Form1.submit();
    }
  }
bOk=false;
function doOk()   {
	if ($F('inpSource').blank()) {
	//if ($('inpSource').val()=="") {
		alert("<?php echo FILEMANAGER_8;?>");
		return false;
	}
  	appendFile();	
    bOk=true;
    if(self.closeWin) self.closeWin(); else parent.box.close();
  }
function doUnload()
  {
  if(navigator.appName.indexOf('Microsoft')!=-1)
    if(!bOk)window.returnValue="";
  else
    if(!bOk)(opener?opener:openerWin).setAssetValue("");
  }

</script>
</head>
<body onunload="doUnload()" onload="/*loadTxt();*/this.focus();if(document.getElementById('inpUploadedFile').value!='')selectFile(document.getElementById('inpUploadedFile').value);" style="overflow:hidden;margin:0px;">

<input type="hidden" name="inpAssetBaseFolder0" id="inpAssetBaseFolder0" value="<?php  echo $sBase0 ?>">
<input type="hidden" name="inpAssetBaseFolder1" id="inpAssetBaseFolder1" value="<?php  echo $sBase1 ?>">
<input type="hidden" name="inpAssetBaseFolder2" id="inpAssetBaseFolder2" value="<?php  echo $sBase2 ?>">
<input type="hidden" name="inpAssetBaseFolder3" id="inpAssetBaseFolder3" value="<?php  echo $sBase3 ?>">

<table width="100%" height="100%" align=center style="" cellpadding=0 cellspacing=0 border=0 >
<tr>
<td valign=top style="height:100%;padding-top:5px;padding-left:5px;padding-right:5px;padding-bottom:0px;">
    <!--ffilter-->
    <form method=post name="Form1" id="Form1">
        <input type="hidden" name="inpFileToDelete">
        <input type="hidden" name="inpCurrFolder">
    </form>
		<input type="hidden" name='selCurrFolder' id='selCurrFolder' value="<?php echo $currFolder;?>">
		<input type="hidden" name=selFilter id=selFilter value="">

    <table width=100% border="0" width="100%" height="100%">
    <!--tr>
    <td>
        <table cellpadding="2" cellspacing="2" border="0" width="100%">

        <tr>
        <td valign=center nowrap><?php // writeFolderSelections(); ?>&nbsp;</td>
        <td nowrap>
          <span onclick="newFolder()" style="cursor:pointer;"><u><span name="txtLang" id="txtLang" <?php //echo $sFolderAdmin;?>><?php //echo FILEMANAGER_11?></span></u></span>&nbsp;
          <span onclick="deleteFolder()" style="cursor:pointer;"><u><span name="txtLang" id="txtLang" <?php //echo $sFolderAdmin;?>><?php //echo FILEMANAGER_12?></span></u></span>
        </td>
        <td  width=100% align="right" >
        <?php
        //ffilter~~~~~~~~~
          $sHTMLFilter = "<select style='display:none;' name=selFilter id=selFilter onchange='applyFilter()' class='inpSel'>"; //ffilter
          $sAll="";
          $sMedia="";
          $sImage="";
          $sFlash="";
		  $sHTM="";
          if($ffilter=="") $sAll="selected";
          if($ffilter=="media") $sMedia="selected";
          if($ffilter=="image") $sImage="selected";
          if($ffilter=="flash") $sFlash="selected";
		  if($ffilter=="HTML") $sHTM="selected";
          $sHTMLFilter = $sHTMLFilter." <option name=optLang id=optLang value='' ".$sAll."></option>";
          $sHTMLFilter = $sHTMLFilter." <option name=optLang id=optLang value='HTML' ".$sHTM.">HTML</option>";
		  $sHTMLFilter = $sHTMLFilter." <option name=optLang id=optLang value='media' ".$sMedia."></option>";
          $sHTMLFilter = $sHTMLFilter." <option name=optLang id=optLang value='image' ".$sImage."></option>";
          $sHTMLFilter = $sHTMLFilter." <option name=optLang id=optLang value='flash' ".$sFlash."></option>";
          $sHTMLFilter = $sHTMLFilter."</select>";
          //echo $sHTMLFilter;
        //~~~~~~~~~
        ?>

        </td>
        </tr>
        </table>
    </td>
    </tr-->
    <tr>
    <td valign=top align="left">

        <table width=100% cellpadding=0 cellspacing=0 border="0">
        <tr>
        <td>
			<div id="idPreview" style="text-align:center;width:500px;height:350px;border:#d7d7d7 2px solid;border-bottom: #d7d7d7 1px solid;background:#ffffff;margin-right:2px;margin-bottom:2px;"></div>
			
			<div <?php echo $sFolderAdmin;?>>
			<div style="height:12"><font color=red><?php  echo $sMsg ?></font><span style="font-weight:bold" id=idUploadStatus></span></div>
			<?php If ($upAllowed==true) {?>
			<form enctype="multipart/form-data" method="post" runat="server" name="Form2" id="Form2">
			<input type="hidden" name="inpCurrFolder2" ID="inpCurrFolder2">
			<!--ffilter-->
			<input type="hidden" name="inpFilter" ID="inpFilter" value="<?php  echo $ffilter ?>">
			<span><?php echo FILEMANAGER_9?></span>: <input type="file" id="File1" name="File1" class="inpTxt">&nbsp;
			<input name="btnUpload" id="btnUpload" type="button" value=" <?php echo FILEMANAGER_10?> " onclick="upload()" >
			</form>
			<?php } else {echo '<font color=red>UPLOADING FILES IS NOT ALLOWED IN DEMO</FONT>';}?>
			</div>
  	 </td>
     <td valign=top width="100%">
          <?php  writeFileSelections(); ?>
		  <div style="margin:5px;" align=left><strong>URL</strong>:&nbsp;<input type="text" id="inpSource" name="inpSource" style="border:#cfcfcf 1px solid;width:100%" class="inpTxt"></div>
		  <div style="margin:5px;"><input name="btnOk" id="btnOk" type="button" value="<?php echo FILEMANAGER_7;?>" onclick="doOk()" class="inpBtn" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'">&nbsp;<span id="loader"></span></div>
     </td>
     </tr>
     </table>

    </td>
    </tr>
    </table>
</td>
</tr>
</table>

</body>
</html>