<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

/* This customization will send a specific one-time-welcome-newsletter depending on the list(s) the subscriber is assigned.
	To define these list-newsletter pairs replace with your own values (IDs) in the function pickNewsletter, right below
*/	


function pickNewsletter($idList) {	   
	switch ($idList) {
		case  "11":		// the list ID
		return "14";	// the newsletter ID	
		//break;
		case  "5":
		return "5";
		//break;
		/*case  "X":
		return "Z";
		//break;*/
		default:
		return "0";
	}  
}
function sendLast($idEmail, $mailData, $idGroup) {
    $obj 		= new db_class();
    $groupEncryptionPassword    = $obj->getSetting("groupEncryptionPassword", $idGroup);
    $mailData["idCampaign"]="";
    //$groupIdWelcomeNewsletter    = $obj->getSetting("groupIdWelcomeNewsletter", $idGroup);
	
	//get subscriber data from id
		        $mySQL2="SELECT * FROM ".$idGroup."_subscribers WHERE idEmail=$idEmail";
		        $subsResult = $obj->query($mySQL2);
		        $rowSub = $obj->fetch_array($subsResult);
		        $sub["idEmail"]         = $rowSub['idEmail'];
		        $pidEmail               = $sub["idEmail"];
		        $sub["email"]           = $rowSub['email'];
		        $sub["email2"]          = myEncrypt($rowSub['email'], $groupEncryptionPassword);
		        $sub["name"]            =  $rowSub['name'];
		        $sub["lastName"]        =  $rowSub['lastName'];
		        $sub["subCompany"]      =  $rowSub['subCompany'];
		        $sub["address"]         =  $rowSub['address'];
		        $sub["city"]            =  $rowSub['city'];
		        $sub["state"]           =  $rowSub['state'];
		        $sub["zip"]             =  $rowSub['zip'];
		        $sub["country"]         =  $rowSub['country'];
		        $sub["subPhone1"]       =  $rowSub['subPhone1'];
		        $sub["subPhone2"]       =  $rowSub['subPhone2'];
		        $sub["subMobile"]       =  $rowSub['subMobile'];
		        $sub["subPassword"]     =  $rowSub['subPassword'];
		        $sub["prefersHtml"]     =  $rowSub['prefersHtml'];
		        $sub["dateSubscribed"]  =  $rowSub['dateSubscribed'];
		        $sub["subBirthDay"]     =  $rowSub['subBirthDay'];
		        $sub["subBirthMonth"]   =  $rowSub['subBirthMonth'];
		        $sub["subBirthYear"]    =  $rowSub['subBirthYear'];
		        $sub["dateLastUpdated"] =  $rowSub['dateLastUpdated'];
		        $sub["dateLastEmailed"] =  $rowSub['dateLastEmailed'];
		        $sub["customSubField1"] =  $rowSub['customSubField1'];
		        $sub["customSubField2"] =  $rowSub['customSubField2'];
		        $sub["customSubField3"] =  $rowSub['customSubField3'];
		        $sub["customSubField4"] =  $rowSub['customSubField4'];
		        $sub["customSubField5"] =  $rowSub['customSubField5'];

		        if ($sub["name"] || $sub["lastName"]) {
		            $pfullName = $sub["name"].' '.$sub["lastName"];}
		        else {$pfullName=$sub["email"];}

	//$mySQL4="SELECT idList from ".$idGroup."_listRecipients WHERE idList in (SELECT idList from ".$idGroup."_lists WHERE isPublic=-1) AND idEmail=".$idEmail;
	$mySQL4="SELECT idList from ".$idGroup."_listRecipients WHERE idEmail=".$idEmail;
	$checked = $obj->query($mySQL4);
   	if ($obj->num_rows($checked)>0) {
		while ($row = $obj->fetch_array($checked)){
				$zList   = $row['idList'];
				$groupIdWelcomeNewsletter = pickNewsletter($zList);
				//echo $groupIdWelcomeNewsletter."<br>";
		
		        
			if (!empty($groupIdWelcomeNewsletter)) {
		        //get the newsletter
		      	$mySQL="Select idNewsletter, name, body, attachments, inlineImages, html, charset from ".$idGroup."_newsletters WHERE idNewsletter=".$groupIdWelcomeNewsletter;
			    $result	= $obj->query($mySQL);
		        $row = $obj->fetch_array($result);
		        if ($row["idNewsletter"]) {
		    	    $pLastsubject		= $row["name"];
		    	    $pLastbody			= $row["body"];
		    	    $pattachment		= $row["attachments"];
		    	    $pinlineimages 		= $row["inlineImages"];
		            $pLastsubject	    = str_ireplace("#subname#",    $sub["name"], $pLastsubject);
		            $pLastsubject	    = str_ireplace("#sublastname#",    $sub["lastName"], $pLastsubject);
		      		$pLastbody	        = subscriberTags($pLastbody, $sub, $mailData);
		  	        if ($row["html"]==-1) {
		    		    $flag="h";
		                $mailData["idHtmlNewsletter"]=$row["idNewsletter"];
		                $mailData["idTextNewsletter"]=0;
		                $pLastbody	= otherHtmlTags($pLastbody, $sub, $mailData);
		    	    }
		            else {
		    		    $flag="t";
		                $mailData["idTextNewsletter"]=$row["idNewsletter"];
		                $mailData["idHtmlNewsletter"]=0;
		                $pLastbody = otherTextTags($pLastbody, $sub, $mailData);
		    	    }
		    	    $pcharset 		= $row["charset"];
		    	    sendMail($idGroup, $sub["email"], $pfullName, $pLastsubject, $pLastbody, $pLastbody, $pattachment, $pcharset, $flag);

        		}
			}
    	}
	}
}
?>