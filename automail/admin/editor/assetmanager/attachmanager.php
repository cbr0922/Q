<?php
session_start();
if (!isset($_SESSION['idAdmin']) and !isset($_SESSION['adminName']) )
{
  echo("No authorization.");
  exit();
}
include('../../includes/languages.php');   

$sPath	=	"../../../attachments";
$bReturnAbsolute=true;
$sMsg = "";
$sBase0		=	$sPath;
$currFolder	=	$sPath;
$upAllowed=true;
$ffilter="";
$sUploadedFile="";
$MaxFileSize = 500000000;
//$AllowedTypes = "|gif|jpg|png|wma|wmv|swf|doc|zip|pdf|txt|docx|xls|xlsx|jpeg";
$AllowedTypes = "*";
?>
<?php
function isTypeAllowed($sFileName)
  {
  global $AllowedTypes;
  if($AllowedTypes=="*") return true;
  if((strpos($AllowedTypes,'|'.getExt($sFileName).'|')!==false)&&(substr_count($sFileName,'.')==1))
    return true;
  else
    return false;
  }

if(isset($_FILES["File1"]))   {
  if(isset($_POST["inpCurrFolder2"]))$currFolder=$_POST['inpCurrFolder2'];
  if(isset($_REQUEST["inpFilter"]))$ffilter=$_REQUEST["inpFilter"];

  if($MaxFileSize && ($_FILES['File1']['size'] > $MaxFileSize))     {
    $sMsg = "The file exceeds the maximum size allowed.";
    }
  else if(!isTypeAllowed($_FILES['File1']['name']))     {
    $sMsg = "The File Type is not allowed.";
    }
  else if (move_uploaded_file($_FILES['File1']['tmp_name'], $currFolder."/".basename($_FILES['File1']['name'])))  {
    $sMsg = "";
    $sUploadedFile=$_FILES['File1']['name'];
    @chmod($currFolder."/".basename($_FILES['File1']['name']), 0644);
    }
  else   {
    $sMsg = "Upload failed.";
    }
  }
else  {
  if(isset($_POST["inpCurrFolder"]))$currFolder=$_POST['inpCurrFolder'];
  if(isset($_REQUEST["ffilter"]))$ffilter=$_REQUEST["ffilter"];
  }

if(isset($_POST["inpFileToDelete"]) && $upAllowed==true)  {
  $filename=pathinfo($_POST["inpFileToDelete"]);
  $filename=$filename['basename'];
  if($filename!="")
    unlink($currFolder . "/" . $filename);
  $sMsg = "";
  }

function getExt($sFileName)   {
  $sTmp=$sFileName;
  while($sTmp!="")     {
    $sTmp=strstr($sTmp,".");
    if($sTmp!="")       {
      $sTmp=substr($sTmp,1);
      $sExt=$sTmp;
      }
    }
  return strtolower($sExt);
  }

function writeFileSelections()  {
  //global $sFolderAdmin;
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

  echo "<div style='overflow:auto;height:222px;width:100%;margin-top:3px;margin-bottom:2px;'>";
  echo "<table border=0 cellpadding=2 cellspacing=0 width=100% height=100% >";
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
        if($ffilter=="flash")
          {
          if($sExt=="swf")$bDisplay=true;
          }
        else if($ffilter=="media")
          {
          if ($sExt=="avi" || $sExt=="wmv" || $sExt=="mpg" || $sExt=="mpeg" || $sExt=="wav" || $sExt=="wma" || $sExt=="mid" || $sExt=="mp3") $bDisplay=true;
          }
        else if($ffilter=="image")
          {
          if ($sExt=="gif" || $sExt=="jpg" || $sExt=="png") $bDisplay=true;
          }
        else //all
          {
          $bDisplay=true;
          }
        //~~~~~~~~~~~~~~~~~~

        if($bDisplay) {
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
          if($sExt=="docx")$sIcon="ico_doc.gif";
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
          if($sExt=="xlsx")$sIcon="ico_xls.gif";
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
          echo "<td><img src='images/".$sIcon."'></td><td><input type=checkbox id=chkFile".$nIndex." name=chkFile".$nIndex." onclick='checkFile()'></td>";
          echo "<td valign=top width=100% ><span id=\"idFile".$nIndex."\" style='cursor:pointer;' >".$sItem."</span>&nbsp;&nbsp;<img style='cursor:pointer;' onclick=\"downloadFile(".$nIndex.")\" src='download.gif'></td>";
          echo "<input type=hidden name=inpFile".$nIndex." id=inpFile".$nIndex." value=\"".$sCurrent_virtual."\">";
          echo "<td valign=top align=right nowrap>".round(filesize($sCurrent)/1024,1)." kb&nbsp;</td>";
          echo "<td valign=top nowrap onclick=\"deleteFile(".$nIndex.")\"><u style='font-size:10px;cursor:pointer;color:crimson'>";
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
  //panos
    echo "<tr><td colspan=5 height=100% align=center><script>document.write(getTxt('Empty...'))</script></td></tr></table></div>";
  else
    echo "<tr><td colspan=5  height=100% ></td></tr></table></div>";

  echo "<input type=hidden name=inpUploadedFile id=inpUploadedFile value='".$iSelected."'>";
  echo "<input type=hidden name=inpNumOfFiles id=inpNumOfFiles value='".$nIndex."'>";

  closedir($oItem);
  }
?>
<base target="_self">
<html>
<head>
<title><?php echo FILEMANAGER_2;?></title>
<meta http-equiv="Content-Type" content="text-html; charset=utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<link href="style.css" rel="stylesheet" type="text/css" />
<?php
$sLang="en-US";
if(isset($_REQUEST["lang"]))
  {
  $sLang=$_REQUEST["lang"];
  if($sLang=="")$sLang="en-US";
  }
?>
<script>
//  var sLang="<?php  echo $sLang ?>";
//  document.write("<scr"+"ipt src='language/"+sLang+"/asset.js'></scr"+"ipt>");
</script>
<script>//writeTitle()</script>
<script>
function getTxt(str) {
return str;
}
function checkFile() {
	var sResult="";
	for(var i=0;i<document.getElementById("inpNumOfFiles").value;i++) {
		if(document.getElementById("chkFile"+(i*1+1)).checked) {
			sResult+=","+document.getElementById("idFile"+(i*1+1)).innerHTML;
		}
	}
	parent.document.getElementById("attachments").value = (sResult.substring(1));
	}

var bReturnAbsolute=<?php  if($bReturnAbsolute){echo "true";} else{echo "false";} ?>; 
var activeModalWin;

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
    sAction="attachmanager.php?ffilter="+document.getElementById("selFilter").value;
    }
  else
    {
    sAction="attachmanager.php?"+sQueryString+"&ffilter="+document.getElementById("selFilter").value
    }
  //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  return sAction;
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

function downloadFile(index)   {
	
  sFile_RelativePath = document.getElementById("inpFile"+index).value;
  //alert(sFile_RelativePath);
  window.open(sFile_RelativePath)
}

function deleteFile(index)   {
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
    bOk=true;
    if(self.closeWin) self.closeWin(); else parent.box.close();
}

function doUnload()   {
  if(navigator.appName.indexOf('Microsoft')!=-1)
    if(!bOk)window.returnValue="";
  else
    if(!bOk)window.opener.setAssetValue("");
  }
</script>
</head>
<body onunload="doUnload()" style="overflow:hidden;margin:0px;">
<div style="padding: 10px 0px 0px 10px"><span style="FONT-SIZE: 14pt"><?php echo FILEMANAGER_4?></span></div>
<input style='display:none;' type="hidden" name='selCurrFolder' id='selCurrFolder' value="<?php  echo $sBase0 ?>">
<input style='display:none;' type="hidden" name='selFilter' id='selFilter' value="">
<input type="hidden" id="inpSource" name="inpSource" style="border:#cfcfcf 1px solid;width:295" class="inpTxt">
 <form method=post name="Form1" id="Form1">
     <input type="hidden" name="inpFileToDelete">
     <input type="hidden" name="inpCurrFolder">
 </form>

<table width="100%" align=center style="" cellpadding=4 cellspacing=0 border=0 >
    <tr>
	    <td valign=top align="center"><?php  writeFileSelections(); ?></td>
    </tr>
    <tr>
    	<td>
        	<?php If ($upAllowed==true) {?>
	        <form enctype="multipart/form-data" method="post" runat="server" name="Form2" id="Form2">
    		    <input type="hidden" name="inpCurrFolder2" ID="inpCurrFolder2">
		        <input type="hidden" name="inpFilter" ID="inpFilter" value="<?php  echo $ffilter ?>">
        		<span><?php echo FILEMANAGER_9?></span>: <input type="file" id="File1" name="File1" class="inpTxt">&nbsp;
        		<input name="btnUpload" id="btnUpload" type="button" value=" <?php echo FILEMANAGER_10?> " onclick="upload()" class="inpBtn" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'">
  			</form>
			<?php } else {echo '<font color=red>UPLOADING FILES IS NOT ALLOWED IN DEMO</FONT>';}?>
			<div style="height:12"><font color=red><?php  echo $sMsg ?></font><span style="font-weight:bold" id=idUploadStatus></span></div>
    	</td>
    </tr>
	<tr>
		<td valign=top align="right">
			<input name="btnOk" id="btnOk" type="button" value="Ok" onclick="doOk()" class="inpBtn" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'">
		</td>
	</tr>
</table>
</body>
</html>