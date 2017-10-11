<?php
set_time_limit(0);
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

/*
***** PURPOSE OF THIS FILE *****
By running this file you synchronize your subscribers with the external data source.
In other words you import subscribers or update subscriber data according to the options of this data source.
It works in exactly the same way as doing it manually (from the admin panel).

***** HOW TO USE THIS FILE *****
You must create a cron job that hits the file in this way:
GET http://www.domain.com/nuevoMailer/admin/dataSourceSync.php?admin=admin_123_X
if the alias command GET is not recognized by your server replace GET with curl -L -s OR WGET
Change www.domain.com/nuevoMailer with your own url.
Replace also admin_123 with your actual username and password always separated with _
Replace X with the ID of the data source you want to sync. You can see this ID in the drop-down menu where you select a data source.

The cron job should run at least once a day. But it can also run several times per day.
*/

include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
$obj = new db_class();
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);
header('Content-type: text/html; charset='.$groupGlobalCharset.'');

@$admin=$_GET["admin"];
$admin=dbQuotes(dbProtect($admin, 250));
if (!$admin)	{
    $obj->closeDb();
 	die("Missing admin credentials.");
}
$posOf_             = stripos($admin, "_");
$lastPosOf_			= strrpos($admin, "_");
$length 			= $lastPosOf_-$posOf_;
$padminName 		= substr($admin,0,$posOf_);
$lenAdminName 		= strlen($padminName);
$padminPassword 	= substr($admin,$lenAdminName+1, $length-1);
$idDataSource		= substr($admin,$lastPosOf_+1);	//ok

$mySQL="SELECT idAdmin, adminName, idGroup, adminPassword FROM ".$idGroup."_admins WHERE adminName='".$padminName."' AND adminPassword='".$padminPassword."'";
$result = $obj->query($mySQL);
if ($obj->num_rows($result)!=1) {
    $obj->closeDb();
	die("Wrong admin credentials");
}
else { //do work

$myDay = myDatenow();
$sql=' AND idDataSource='.$idDataSource;
$myDS="SELECT  dataSourceFriendlyName, dbType, dbName, dbHost, dsnName, dbUserName, dbPassword, dbPort, tbName, idGroup, email, 
		name, lastName, subCompany, address, city, state, zip, country, subPhone1, subPhone2, subMobile, subPassword, subBirthDay, subBirthMonth, 
		 subBirthYear, customSQL, customSubField1, customSubField2, customSubField3, customSubField4, customSubField5, confirmed, prefersHtml, idList, 
		 excludeGlobalOpts, excludeListOpts, subUpdateDuplicates FROM ".$idGroup."_dataSources WHERE idGroup=$idGroup $sql";
$resultDS = $obj->query($myDS);
if ($obj->num_rows($resultDS)!=1) {
    $obj->closeDb();
	die("Wrong data source or data source does not exist.");
}
$dsValues = array();
while($message = $resultDS->fetch_assoc()){
   $dsValues[] = $message;
}
$obj->data_seek($resultDS,0);
$load 	  = $obj->fetch_array($resultDS);
	$dbType				= $load['dbType'];
	$dbName				= $load['dbName'];
	$dbHost				= $load['dbHost'];
	$dsnName			= $load['dsnName'];
	$dbUserName			= $load['dbUserName'];
	$dbPassword			= $load['dbPassword'];
	$tbName				= $load['tbName'];
	$name		   		= $load['name'];
	$email		   		= $load['email'];
	$lastName      		= $load['lastName'];
	$subCompany			= $load['subCompany'];
	$address			= $load['address'];
	$city		   		= $load['city'];
	$zip				= $load['zip'];
	$state		  		= $load['state'];
	$country			= $load['country'];
	$subPhone1			= $load['subPhone1'];
	$subPhone2			= $load['subPhone2'];
	$subMobile			= $load['subMobile'];
	$subPassword	    = $load['subPassword'];
	$subBirthDay		= $load['subBirthDay'];
	$subBirthMonth		= $load['subBirthMonth'];
	$subBirthYear		= $load['subBirthYear'];
	$customSubField1	= $load['customSubField1'];
	$customSubField2	= $load['customSubField2'];
	$customSubField3	= $load['customSubField3'];
	$customSubField4	= $load['customSubField4'];
	$customSubField5	= $load['customSubField5'];
	$customSQL			= $load['customSQL'];
	$confirmed          = $load['confirmed'];
	$prefersHtml        = $load['prefersHtml'];
	$subUpdateDuplicates 		= $load['subUpdateDuplicates'];
	$dataSourceFriendlyName		= $load['dataSourceFriendlyName'];
	$excludeGlobalOpts			= $load['excludeGlobalOpts'];
	$excludeListOpts          	= $load['excludeListOpts'];
	$idList						= $load['idList'];

$pAction        = ""; // To be determined
$custSQL = " ";
$pcustomSQL 	= my_stripslashes($customSQL);
if (!empty($pcustomSQL)) {
	$custSQL = " ".$pcustomSQL;
}

$justLists="";
$lists = explode(",", $idList);
@$listsTicked		= count($lists);

if ($listsTicked!=0) {
    for ($z=0; $z<$listsTicked; $z++)  {
    	if (!listDeleted($lists[$z], $idGroup)) {
        	$justLists .= $lists[$z].', ';
		}
    }
    $justLists = rtrim($justLists, ", ");
}

$extraUsql =", confirmed='".$confirmed."', prefersHtml='".$prefersHtml."', subUpdateDuplicates='".$subUpdateDuplicates."', excludeGlobalOpts='".$excludeGlobalOpts."', excludeListOpts='".$excludeListOpts."', idList='".$justLists."'";

$inserted=0;
$pduplicates=0;
$perrors=0;
$foundInGlobaloptOut=0;


// ####### SUBSCRIBERS SELECT SQL
$selectSQL="";
//print_r($dsValues);
foreach ($dsValues[0] as $key => $value) {
    if ($key!="saveDataSourceBtn" && $key!="dataSourceFriendlyName" && $key!="customSQL" && $key!="tbName" && $key!="confirmed" && $key!="prefersHtml" && $key!="subUpdateDuplicates" && $key!="excludeGlobalOpts" && $key!="excludeListOpts" && $key!="idDataSource" && $key!="dbUserName" && $key!="dbPassword" && $key!="idGroup" && $key!="dbType" && $key!="dbName" && $key!="dbHost" && $key!="idList" && $key!="updateDataSourceBtn" && $key!="action" && $key!="_" && $value) {
        //echo $key.'>'.$value.'<br />';
        //$selectSQL.= $tbName.'.'.$value.' as '.$key.', ';  //removed the table name for more flexibility
		$selectSQL.= $value.' as '.$key.', ';
     }
}
$selectSQL = 'SELECT distinct ' .rtrim($selectSQL,', ').' FROM '.$tbName.' '.$custSQL;
//die($selectSQL);

// #### SOURCE DB CONNECTIONS: mySQL connection
if ($dbType==1) {
	$conlink = mysqli_connect($dbHost, $dbUserName, $dbPassword, $dbName);
	if(!$conlink) {
    	die('<span class=errormessage>Unable to connect to '.$dbHost.'</span><br>');
	}
	$result	= mysqli_query($conlink, $selectSQL);
	if(!$result) {die('<span class=errormessage>'.$selectSQL.'<br /><br />'.EXTERNALDBIMPORTFORM_47.'</span><br>');	}
    $rows 	=  mysqli_num_rows($result);
    if (!$rows) {die('<span class=errormessage>'.EXTERNALDBIMPORTFORM_46.'</span><br>');};
    function my_fetch_array($r) {return mysqli_fetch_array($r, MYSQLI_BOTH);}
    function closeConn($conlink) {return mysqli_close($conlink);}
}

//####### MSSQL connection using mssql
if ($dbType==2) {
	$conlink = mssql_connect($dbHost, $dbUserName, $dbPassword);
	if(!$conlink) 	{
    	die('Type-2. Unable to connect to '.$dbHost.'<br>');
	}
	$db_selected = mssql_select_db ($dbName, $conlink);
	if(!$db_selected) 	{
    	die('Unable to select database '.$dbName.'<br>');
	}
	$result	= mssql_query($selectSQL, $conlink);
	if(!$result) {die('<span class=errormessage>'.$selectSQL.'<br /><br />'.EXTERNALDBIMPORTFORM_47.'</span><br>');	}
    $rows 	=  mssql_num_rows($result);
    if (!$rows) {die('<span class=errormessage>'.EXTERNALDBIMPORTFORM_46.'</span><br>');};
	//while ($row = mssql_fetch_array($result)){
    //mssql_close($conlink);
    function my_fetch_array($r) {return mssql_fetch_array($r, MSSQL_BOTH);}
    function closeConn($conlink) {return mssql_close($conlink);}

}
//####### MSSQL connection using sqlsrv
//####### MSSQL connection using sqlsrv
if ($dbType==6) {
    $connectionInfo = array("Database"=>$dbName,"UID"=>$dbUserName,"PWD"=>$dbPassword);
	$conlink = sqlsrv_connect($dbHost, $connectionInfo);
	if(!$conlink) 	{
		//die('Type-2. Unable to connect to '.$dbHost.'<br>');
 		die( print_r( sqlsrv_errors(), true));
	}
	$result	= sqlsrv_query($conlink, $selectSQL, array(),  array("Scrollable"=>'keyset'));	//updated in nuevoMailer v300
	if(!$result) {
		echo( print_r( sqlsrv_errors(), true))."<br>";
		die('<span class=errormessage>'.$selectSQL.'<br /><br />'.EXTERNALDBIMPORTFORM_47.'</span><br>');	}
    $rows 	=  sqlsrv_num_rows($result);
    //$rowsExist = sqlsrv_has_rows($result); //good to check if there are rows!
    //  if ($rowsExist === true)
    //     echo "\nthere are rows\n";
    //  else
    //     echo "\nno rows\n";
    if (!$rows) {
    	echo( print_r( sqlsrv_errors(), true))."<br>";
		die('<span class=errormessage>'.EXTERNALDBIMPORTFORM_46.'</span><br>');};
	//while ($row = sqlsrv_fetch_array($result)){
    //sqlsrv_close($conlink);
    function my_fetch_array($r) {return sqlsrv_fetch_array($r, SQLSRV_FETCH_BOTH);}
    function closeConn($conlink) {return sqlsrv_close($conlink);}
}


//####  SUBSCRIBERS INSERT SQL
$leftInsert="";
foreach ($dsValues[0] as $key => $value) {
    if ($value && $key!="dataSourceFriendlyName" && $key!="dsnName" && $key!="customSQL" && $key!="tbName" && $key!="confirmed" && $key!="prefersHtml" && $key!="excludeGlobalOpts" && $key!="excludeListOpts" && $key!="subUpdateDuplicates" && $key!="idDataSource" && $key!="dbUserName" && $key!="dbPassword" && $key!="idGroup" && $key!="dbType" && $key!="dbName" && $key!="dbHost" && $key!="idList" && $key!="updateDataSourceBtn" && $key!="action" && $key!="_") {
        $leftInsert.=	$key.', ';
    }
}
$leftInsert = rtrim($leftInsert,', '). ", confirmed, prefersHtml, dateSubscribed, idGroup";

$f=0;
$rightInsert="";
$rightInsertArr=array();
foreach ($dsValues[0] as $key => $value) {
    if ($value && $key!="dataSourceFriendlyName" && $key!="dsnName" && $key!="customSQL" && $key!="tbName" && $key!="confirmed" && $key!="prefersHtml" && $key!="excludeGlobalOpts" && $key!="excludeListOpts" && $key!="subUpdateDuplicates" && $key!="idDataSource" && $key!="dbUserName" && $key!="dbPassword" && $key!="idGroup" && $key!="dbType" && $key!="dbName" && $key!="dbHost" && $key!="idList" && $key!="updateDataSourceBtn" && $key!="action" && $key!="_") {
        $rightInsertArr[$f]=$key;
        // UPDATE PART
        $updateArr[$f]=$key;
    }
    $f=$f+1;
}

$updateSQL="";
while ($newValue = my_fetch_array($result)){  //reading from source
    foreach($rightInsertArr as $field) {
        $rightInsert .= '\''.dbQuotesArr(trim($newValue[$field])).'\', ';
        $updateSQL.= $field.'=\''.dbQuotesArr(trim($newValue[$field])).'\', ';
    }
    $updateSQL = "UPDATE ".$idGroup."_subscribers SET $updateSQL confirmed=$confirmed, prefersHtml=$prefersHtml WHERE idEmail=";
    $rightInsert = rtrim($rightInsert,', '). ", $confirmed, $prefersHtml, '".$myDay."', $idGroup";
    $insertSQL="INSERT INTO ".$idGroup."_subscribers (".$leftInsert.") VALUES (".$rightInsert.")";
	
	
if  ($newValue["email"]!="" && strstr($newValue["email"],"@") && strstr($newValue["email"],".")) {
		//check if exists
	    $mySQL1="SELECT idEmail, email FROM ".$idGroup."_subscribers where email='".dbQuotes($newValue["email"])."'";
		//ECHO $mySQL1;
        $result1 = $obj->query($mySQL1);
        $row = $obj->fetch_array($result1);
		if ($obj->num_rows($result1)==0) { //he does not exist so we insert him

			if ($excludeGlobalOpts==-1) {
				$insertSQL="INSERT INTO ".$idGroup."_subscribers (".$leftInsert.") SELECT ".$rightInsert." FROM dual
					WHERE NOT EXISTS (SELECT subscriberEmail FROM 1_optOutReasons WHERE optOutType='g' AND subscriberEmail='".trim(dbQuotes($newValue["email"]))."')";
			}
            //echo $insertSQL.'<br />';die;
			$obj->query($insertSQL);    //THIS IS THE MAIN INSERT SUBS QUERY
            $last =  $obj->insert_id();
			if ($last!=0) {$inserted++;} else {$foundInGlobaloptOut++;}
				if ($listsTicked!=0 && $last!=0) {
					for ($z=0; $z<$listsTicked; $z++)  {
						if (!listDeleted($lists[$z], $idGroup)) {
							$mySQL3="REPLACE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$last.", ".$lists[$z].", $idGroup)";
							$result3	= $obj->query($mySQL3);
						}
					} // for
				}	//listsTicked not 0
		}
        else {
            //he exists already, we get his email id
            $pduplicates=$pduplicates+1;
     		if ($subUpdateDuplicates==-1) {
		    	$nidemail	= $row["0"];
                $mySQL4=$updateSQL.$nidemail;
				//echo $mySQL4."<br>";
                $obj->query($mySQL4);
				//assign to lists
				 if ($listsTicked!=0) {
				    For ($z=0; $z<$listsTicked; $z++)  {
			    		if (!listDeleted($lists[$z], $idGroup)) {
						    $mySQL3="REPLACE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$nidemail.", ".$lists[$z].", $idGroup)";
							$obj->query($mySQL3);
						}
					 } //FOR
				} //lists ticked
			}	// $subUpdateDuplicates
		}	// he existed
}   // good email
else {
    $perrors=$perrors+1;
}
$rightInsert="";
$updateSQL="";
} // from while loop for the select query

//Clear list opt-outs
if ($excludeListOpts==-1 && $listsTicked!=0) {
	for ($z=0; $z<$listsTicked; $z++)  {
		if (!listDeleted($lists[$z], $idGroup)) {
	 		$mySQLd="DELETE FROM ".$idGroup."_listRecipients WHERE idList=".$lists[$z]." AND idEmail IN (SELECT idEmail FROM ".$idGroup."_subscribers WHERE email IN (SELECT subscriberEmail FROM ".$idGroup."_optOutReasons WHERE optOutType=".$lists[$z]."))";
			$obj->query($mySQLd);
		}
	}
}

$obj->closeDb();
closeConn($conlink);
?>
<?php echo EXTERNALDBIMPORTFORM_26?>
<br />
<?php
echo $inserted .EXTERNALDBIMPORTFORM_27.'<br />';
echo $pduplicates;
if ($subUpdateDuplicates==-1) {
	echo EXTERNALDBIMPORTFORM_48;
} else {
	echo EXTERNALDBIMPORTFORM_28;
}
echo '<br />'.$foundInGlobaloptOut  .' '.SUBSCRIBERSIMPORT_36;
echo '<br />'.$perrors .EXTERNALDBIMPORTFORM_49;

} //wrong admin credentials
?>