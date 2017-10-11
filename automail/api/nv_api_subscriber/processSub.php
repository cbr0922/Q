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

include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
$myDay  = myDatenow();
$pIP    = $_SERVER['REMOTE_ADDR'];	

//email always required
(isset($_REQUEST['email']))?$subemail = dbQuotes(dbProtect(trim($_REQUEST['email']),500)):$subemail="";
if (!$subemail || !strstr($subemail,"@") || !strstr($subemail,".")) {die;}  //stop, there is no email
if (isset($_REQUEST['name'])){$subname = dbQuotes(dbProtect(trim($_REQUEST['name']),100));$sqlName="name='".$subname."', ";} else {$subname="";$sqlName="";}
if (isset($_REQUEST['lastname'])){$subLastName = dbQuotes(dbProtect(trim($_REQUEST['lastname']),100));$sqlLastName="lastName='".$subLastName."', ";} else {$subLastName="";$sqlLastName="";}
if (isset($_REQUEST['subcompany'])){$subCompany = dbQuotes(dbProtect(trim($_REQUEST['subcompany']),200));$sqlCompany="subCompany='".$subCompany."', ";} else {$subCompany="";$sqlCompany="";}
if (isset($_REQUEST['subphone1'])){$subPhone1 = dbQuotes(dbProtect(trim($_REQUEST['subphone1']),100)); $sqlPhone1 = "subPhone1='".$subPhone1."', ";} else {$subPhone1="";$sqlPhone1="";}
if (isset($_REQUEST['subphone2'])){$subPhone2 = dbQuotes(dbProtect(trim($_REQUEST['subphone2']),100));$sqlPhone2 = "subPhone2='".$subPhone2."', ";} else {$subPhone2="";$sqlPhone2="";}
if (isset($_REQUEST['submobile'])){$subMobile = dbQuotes(dbProtect(trim($_REQUEST['submobile']),100));$sqlMobile = "subMobile='".$subMobile."', ";} else {$subMobile="";$sqlMobile="";}
if (isset($_REQUEST['address'])){$subAddress = dbQuotes(dbProtect(trim($_REQUEST['address']),100));$sqlAddress="address='".$subAddress."', ";} else {$subAddress="";$sqlAddress="";}
if (isset($_REQUEST['city'])){$subCity = dbQuotes(dbProtect(trim($_REQUEST['city']),100));$sqlCity = "city='".$subCity."', ";} else {$subCity="";$sqlCity="";}
if (isset($_REQUEST['zip'])){$subZip = dbQuotes(dbProtect(trim($_REQUEST['zip']),20));$sqlZip="zip='".$subZip."', ";} else {$subZip="";$sqlZip="";}
if (isset($_REQUEST['statecode'])){$subState = dbQuotes(dbProtect(trim($_REQUEST['statecode']),10));$sqlState = "state='".$subState."',  ";} else {$subState="";$sqlState="";}
if (isset($_REQUEST['countrycode'])){$subCountry = dbQuotes(dbProtect(trim($_REQUEST['countrycode']),10)); $sqlCountry = "country='".$subCountry."', ";} else {$subCountry="";$sqlCountry="";}
if (isset($_REQUEST['subbirthday'])){$subBirthDay = dbQuotes(dbProtect(trim($_REQUEST['subbirthday']),5));$sqlDay="subBirthDay='".$subBirthDay."', ";} else {$subBirthDay="";$sqlDay="";}
if (isset($_REQUEST['subbirthmonth'])){$subBirthMonth = dbQuotes(dbProtect(trim($_REQUEST['subbirthmonth']),5)); $sqlMonth="subBirthMonth='".$subBirthMonth."', ";} else {$subBirthMonth="";$sqlMonth="";}
if (isset($_REQUEST['subbirthyear'])){$subBirthYear = dbQuotes(dbProtect(trim($_REQUEST['subbirthyear']),5));$sqlYear="subBirthYear='".$subBirthYear."', ";} else {$subBirthYear="";$sqlYear="";}
if (isset($_REQUEST['password'])){$subPassword = dbQuotes(dbProtect(trim($_REQUEST['password']),50));} else {$subPassword="";}
if (!$subPassword) {$subPassword=rand(1, 15000);}
$sqlPassword="subPassword='".$subPassword."', ";
if (isset($_REQUEST['pcustomsubfield1'])){$subCustomSubField1 = dbQuotes(dbProtect(trim($_REQUEST['pcustomsubfield1']),200));$sqlCustom1 = "customSubField1='".$subCustomSubField1."', ";} else {$subCustomSubField1="";$sqlCustom1="";}
if (isset($_REQUEST['pcustomsubfield2'])){$subCustomSubField2 = dbQuotes(dbProtect(trim($_REQUEST['pcustomsubfield2']),200));$sqlCustom2 = "customSubField2='".$subCustomSubField2."', ";} else {$subCustomSubField2="";$sqlCustom2="";}
if (isset($_REQUEST['pcustomsubfield3'])){$subCustomSubField3 = dbQuotes(dbProtect(trim($_REQUEST['pcustomsubfield3']),200));$sqlCustom3 = "customSubField3='".$subCustomSubField3."', ";} else {$subCustomSubField3="";$sqlCustom3="";}
if (isset($_REQUEST['pcustomsubfield4'])){$subCustomSubField4 = dbQuotes(dbProtect(trim($_REQUEST['pcustomsubfield4']),200));$sqlCustom4 = "customSubField4='".$subCustomSubField4."', ";} else {$subCustomSubField4="";$sqlCustom4="";}
if (isset($_REQUEST['pcustomsubfield5'])){$subCustomSubField5 = dbQuotes(dbProtect(trim($_REQUEST['pcustomsubfield5']),200));$sqlCustom5 = "customSubField5='".$subCustomSubField5."', ";} else {$subCustomSubField5="";$sqlCustom5="";}
if (isset($_REQUEST['prefers'])){$subPrefers = dbQuotes(dbProtect(trim($_REQUEST['prefers']),5));} else {$subPrefers="-1";}
if (isset($_REQUEST['updateaccount'])){$updateAccount=dbQuotes(dbProtect(trim($_REQUEST['updateaccount']),5));} else {$updateAccount="0";}
if (isset($_REQUEST['confirmed'])){$pverstatus=dbQuotes(dbProtect(trim($_REQUEST['confirmed']),5));} else {$pverstatus="-1";}
$sqlConfirmed = "confirmed=".$pverstatus.", ";
if (isset($_REQUEST['clearoptout'])){$clearoptout=dbQuotes(dbProtect(trim($_REQUEST['clearoptout']),5));} else {$clearoptout="0";}

// ACTION: ADD OR REMOVE
if (isset($_REQUEST['action'])){$action=dbQuotes(dbProtect(trim($_REQUEST['action']),10));}else {$action="";}
if(!$action) {die;}

$listsTicked=0;
if (isset($_REQUEST['idlist'])) {
  if (!empty($_REQUEST['idlist'])) {
    $lists          = explode(",", $_REQUEST['idlist']);
    $listsTicked    = sizeof($lists);
 }   
}

// CHECK IF ALREADY IS A SUBSCRIBERS AND GET ID
$last=0;
$mySQL1="SELECT idEmail, email, name, prefersHtml FROM ".$idGroup."_subscribers WHERE email='".$subemail."'"; 
$result	= $obj->query($mySQL1);
$row = $obj->fetch_array($result);
if ($row['idEmail']) {$last = $row['idEmail'];}             // HE IS ALREADY A SUBSCRIBER

if ($action=="add") {  //adding subscriber
if ($last) {
    if ($updateAccount==-1) {                               //  UPDATE HIS ACCOUNT
    $sqlDateLastUpdated = "dateLastUpdated='".$myDay."', ";    
    $mySQL2="UPDATE ".$idGroup."_subscribers set $sqlName $sqlLastName $sqlCompany $sqlAddress $sqlZip $sqlState $sqlCountry  $sqlCity  $sqlPhone1 $sqlPhone2 
        $sqlMobile $sqlPassword $sqlConfirmed $sqlDateLastUpdated $sqlCustom1 $sqlCustom2 $sqlCustom3 $sqlCustom4 $sqlCustom5 $sqlDay $sqlMonth $sqlYear
        email='".$subemail."' WHERE idEmail=".$last;
    $obj->query($mySQL2);
    }
}
else {		                                            //  NEW SUBSCRIBER  
    $mySQL2="INSERT INTO ".$idGroup."_subscribers (email, name, lastName, subCompany, address, zip, state, country, city, subPhone1, subPhone2, subMobile, 
    subPassword, prefersHtml, confirmed, dateSubscribed, customSubField1, customSubField2, customSubField3, customSubField4, customSubField5, 
    ipSubscribed, subBirthDay, subBirthMonth, subBirthYear, idGroup) VALUES 
    ('".$subemail."', '".$subname."', '".$subLastName."', '".$subCompany."', '".$subAddress."',  '".$subZip."', '".$subState."', '".$subCountry."', '".$subCity."', '".$subPhone1."', '".$subPhone2."', '".$subMobile."', 
    '".$subPassword."', ".$subPrefers.", ".$pverstatus.", '".$myDay."', '".$subCustomSubField1."', '".$subCustomSubField2."', 
    '".$subCustomSubField3."',  '".$subCustomSubField4."', '".$subCustomSubField5."', '".$pIP."', '".$subBirthDay."', '".$subBirthMonth."', '".$subBirthYear."', ".$idGroup.")";
    $obj->query($mySQL2);
    $last =  $obj->insert_id();
    $sub["idEmail"] = $last;

	//update admin stats
    $mySQLa="UPDATE ".$idGroup."_adminStatistics set newSubscribers=newSubscribers+1";
	$obj->query($mySQLa);

}
if ($clearoptout==-1) {             // REMOVE FROM OPT-OUTS TABLE
    $mySQL5="DELETE FROM ".$idGroup."_optOutReasons WHERE subscriberEmail='".$subemail."' AND idGroup=$idGroup";
    $obj->query($mySQL5);
    //echo "reset";
}
if ($listsTicked) {                 // ADD TO LISTS
    for ($z=0; $z<$listsTicked; $z++)  {
        $zList = trim(dbQuotes(dbProtect($lists[$z],10)));
        //## update also his list assignments
        $mySQL2="SELECT idEmail FROM ".$idGroup."_listRecipients WHERE idEmail=".$last." AND idList=".$zList;
        $result = $obj->query($mySQL2);
        if ($obj->num_rows($result)!=1) {
            $mySQL3="INSERT INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$last.", ".$zList.", ".$idGroup.")";
            $obj->query($mySQL3);
        }
    }
}


}
elseif ($action=="remove") {
    if ($last) {
        if ($listsTicked) {                 // REMOVE FROM LISTS
            for ($z=0; $z<$listsTicked; $z++)  {
                $zList = trim(dbQuotes(dbProtect($lists[$z],10)));
                //## update also his list assignments
                $mySQL2="DELETE FROM ".$idGroup."_listRecipients WHERE idEmail=".$last." AND idList=".$zList;
                $result = $obj->query($mySQL2);
            }
        }
        else {                          //complete remove
            $mySQL="DELETE FROM ".$idGroup."_subscribers WHERE idEmail=".$last;
            $obj->query($mySQL);
            $mySQL="DELETE FROM ".$idGroup."_listRecipients WHERE idEmail=".$last;
            $obj->query($mySQL);

           $mySQL2="REPLACE into ".$idGroup."_optOutReasons (subscriberEmail, idGroup, optOutType, dateOptedOut, idCampaign) VALUES ('".$subemail."', ".$idGroup.", 'g', '".$myDay."', 0)";
           $obj->query($mySQL2);
        	//update admin stats
			$mySQLa="UPDATE ".$idGroup."_adminStatistics set minusSubscribers=minusSubscribers-1";
    		$obj->query($mySQLa);

			
		
		}  
    }

}

?>