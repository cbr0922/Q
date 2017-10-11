<?php 
//nuevoMailer v.3.60
//Copyright 2013 Panagiotis Chourmouziadis
//http://www.designerfreesolutions.com
/* 
	For CROSS-DOMAIN REQUESTS, uncomment this line: header("Access-Control-Allow-Origin 
	and change with the URL from which you are triggering the subscriber insertion/removal (the external URL: http://www.external-url.com).
	You may add several such lines for all domains where you want to trigger the api.
*/
//header("Access-Control-Allow-Origin: http://www.external-url.com");
?>
<?php
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupName               =	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset      =	$obj->getSetting("groupGlobalCharset", $idGroup);
$groupContactEmail      =	$obj->getSetting("groupContactEmail", $idGroup);
$groupSite              =	$obj->getSetting("groupSite", $idGroup);

?>
<b>Our newsletters</b>:
<?php
//select all newsletters, to be used for the drop-down menu
//In case you want to limit only to sent ones add this in the sql: AND sent=-1 
$mySQL="SELECT idNewsletter, name, dateCreated FROM ".$idGroup."_newsletters WHERE  isPublic=-1 ORDER by idNewsletter DESC";
$resultALL  = $obj->query($mySQL);
$rowsALL 	= $obj->num_rows($resultALL);
if (!$rowsALL){
    echo "No newsletters available";die;
} 
    else {?>
        <select name="idNewsletter" id="idNewsletter" onchange="loadNewsletter();return false;">
        <option value="0">Please select a newsletter</option>
        <?php 	while ($rowALL = $obj->fetch_array($resultALL)){ ?>
        <option value="<?php echo $rowALL['idNewsletter']?>"><?php echo $rowALL['name']?> (<?php echo addOffset($rowALL['dateCreated'], $pTimeOffsetFromServer, $groupDateTimeFormat);?>)</option>
        <?php } ?>
        </select>
<?php 
    } ?>     
