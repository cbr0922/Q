<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<script type="text/javascript" language="javascript">
function $Npro(field){var element =  document.getElementById(field);return element;return false;}
function $VNpro(field){var element =  document.getElementById(field).value;return element;return false;}
function goToItem() {
	if ($VNpro("goToItem")!=0) 	{
		document.location.href='newsletterArchive.php?idNewsletter='+$VNpro("goToItem");
	}
}
</script>
<?php
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('../inc/languages.php');

(isset($_GET['fb']))?$fb = dbQuotes(dbProtect($_GET['fb'],1)):$fb=0;
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupName              =	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset     =	$obj->getSetting("groupGlobalCharset", $idGroup);
$groupContactEmail      =	$obj->getSetting("groupContactEmail", $idGroup);
$groupSite              =	$obj->getSetting("groupSite", $idGroup);
$groupScriptUrl			=	$obj->getSetting("groupScriptUrl", $idGroup);
(isset($_GET['idNewsletter']))?$pidnewsletter = dbQuotes(dbProtect($_GET['idNewsletter'],7)):$pidnewsletter="";
//echo 'here: '.$fb;

// select latest one or a specific one
if ($pidnewsletter) {
	$mySQL2="SELECT idNewsletter, name, body, html, dateCreated, dateSent, charset FROM ".$idGroup."_newsletters WHERE isPublic=-1 and idNewsletter=$pidnewsletter";
} else {
	$mySQL2="SELECT idNewsletter, name, body, html, dateCreated, dateSent, charset FROM ".$idGroup."_newsletters WHERE idNewsletter=(SELECT Max(idNewsletter) from ".$idGroup."_newsletters where  isPublic=-1)";
}
$result2 = $obj->query($mySQL2);
$rows 	= $obj->num_rows($result2);
if ($rows){
	$row 			= $obj->fetch_array($result2);
	$pidnewsletter 	= $row['idNewsletter'];
	//$pidHtmlnewsletter = $pidnewsletter;
	$pname			= $row['name'];
	$pbody			= $row['body'];
	$phtml			= $row['html'];
	$dateCreated	= addOffset($row['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat);
	$dateSent		= addOffset($row['dateSent'], $pTimeOffsetFromServer, $groupDateTimeFormat);
	(strtotime($dateSent)>strtotime($dateCreated))?$titleDate=$dateSent:$titleDate=$dateCreated;	
	$pcharset		= $row['charset'];
}
if (!isset($pcharset)) {
	$pcharset=$groupGlobalCharset;
}
?>
<div style="margin-left:200px;border:#000000 0px solid;background:#fff;padding:10px">
	<span style=" FONT-FAMILY: Arial, helvetica; FONT-SIZE: 20px; FONT-WEIGHT: bold; color:#565656;"><?php echo $groupName .' - '.ARCHIVE_3;?></span>
</div>
<div style="margin-left:200px;margin-top:0px;border:#000000 0px solid;background:#fff;padding:10px">
	<span style=" FONT-FAMILY: Arial, helvetica; FONT-SIZE: 14px; FONT-WEIGHT: bold; color:#565656;"><?php echo ARCHIVE_1 ?></span>:&nbsp;
	<?php
		//select all newsletters, to be used for the drop-down menu
        $mySQL="SELECT idNewsletter, name, dateCreated, dateSent FROM ".$idGroup."_newsletters WHERE  isPublic=-1 order by idNewsletter desc";
        $resultALL  = $obj->query($mySQL);
        $rowsALL 	= $obj->num_rows($resultALL);
   		if (!$rowsALL){
			echo ARCHIVE_4;die;
		} else { ?>
			<select name="goToItem" id="goToItem" onchange="goToItem();">
			<option value="0"><?php echo ARCHIVE_2 ?></option>
			<?php 	while ($rowALL = $obj->fetch_array($resultALL)){ 
				(strtotime($rowALL['dateSent'])>strtotime($rowALL['dateCreated']))?$showThisDate=$rowALL['dateSent']:$showThisDate=$rowALL['dateCreated'];	
				$showThisDate= addOffset($showThisDate, $pTimeOffsetFromServer, $groupDateTimeFormat);   
		
		
				?>
				<option value="<?php echo $rowALL['idNewsletter']?>"  <?php if ($rowALL['idNewsletter']==$pidnewsletter) {echo ' selected';} ?>   ><?php echo $rowALL['name']?> (<?php echo $showThisDate?>)</option>
			<?php } ?>
			</select>
        <?php } ?>
</div>

			<?php
               if ($phtml==-1) {
                    $exclude = array("<a href=\"friendforwardlink\">", "<a href=\"optOutlink1\">", "<a href=\"optOutlink2\">", "<a href=\"optOutlink3\">", "<a href=\"ratelink1\">", "<a href=\"ratelink2\">", "<a href=\"ratelink3\">", "<a href=\"ratelink4\">", "<a href=\"ratelink5\">", "<a href=\"webpagelink\">", "<a href=\"optOutlink3\">", "<a href=\"subconfirmationlink\">");
                    $pbody = str_ireplace($exclude, "", $pbody);
					$pbody		= str_ireplace('nvhide=""', 'style="display:none"', $pbody);

					// FB render
					if ((stripos($pbody, "#fblikefb#"))!=0) {	// there is an FB like button in the newsletter body.

						$pbody		= str_ireplace("#fblikefb#", "", $pbody);	//no rendering at this point.
						$fb=1;
						// FB render: prepare string
						$urlToLike=$groupScriptUrl.'/subscriber/newsletterArchive.php?idNewsletter='.$pidnewsletter;
						$fbStr='<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="'.$urlToLike.'" show_faces="false" width="450"></fb:like>';
					}
                }
                else {
			        $pbody	= str_ireplace("#companyname#", $groupName, $pbody);
                    $pbody	= str_ireplace("#contactemail#", $groupContactEmail, $pbody);
                    $pbody	= str_ireplace("#companysite#", $groupSite, $pbody);
			        $pattern = '/(#)(.*?)(#)/'; //removes smartTags
			        $pbody = preg_replace($pattern, "", $pbody);
				    $pbody = "<PRE>".$pbody."</PRE>";
			    }
//echo 'here2: '.$fb;			 ?>
<div style="margin-left:200px;margin-top:40px;">
	<span style=" FONT-FAMILY: Tahoma, Arial, helvetica; FONT-SIZE: 16px; FONT-WEIGHT: bold; color:#565656;"><?php echo $pname.'&nbsp;&nbsp;</span><span>('.$titleDate.')' ?></span>
	<?php if ($fb==1) {?>
		<div id="fblayer" style="padding:10px; display:inline; position:absolute; top:0px; right:0px; border:#000 1px solid; background:#ffffe0;"><?php echo $fbStr;?></div>
		<div style="margin-top:10px;"><?php echo $fbStr ?></div>
	<?php } ?>
</div>

<div style="margin-left:200px;margin-top:5px;">
	<?php echo $pbody; ?>
</div>

<?php if (@$pdemomode) { ?>
	<div align="center" style="margin-top:50px; border-top:#888 1px solid">
		<span style="color:#000;font-size:12px;font-family:Verdana, Arial">This is a demonstration of nuevoMailer.</span>
		<br><a target="blank" href="http://www.nuevomailer.com?demo"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">Click here to learn more.</span></a>
		<div style="margin-top:12px"><span style="color:#000;font-size:12px;font-family:Verdana, Arial">&copy; <?php echo date('Y')?> - DesignerFreeSolutions.com</span></div>
	</div>
<?php   } ?>
