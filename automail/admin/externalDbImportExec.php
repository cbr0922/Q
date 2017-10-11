<?php
set_time_limit(0);
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify2.php');
include('../inc/dbFunctions.php');
include('../inc/stringFormat.php');
include('./includes/languages.php');
include('./includes/auxFunctions.php');
$obj 		= new db_class();
$groupName	= $obj->getSetting("groupName", $idGroup);
$groupGlobalCharset 	=	$obj->getSetting("groupGlobalCharset", $idGroup);

if (@$pdemomode) {
	forDemo2(DEMOMODE_1);
}
$myDay = myDatenow();
@$prefersHtml 		= $_POST['prefersHtml'];
if ($prefersHtml!=-1) {$prefersHtml=0;}
@$confirmed	= $_POST['confirmed'];
if ($confirmed!=-1) {$confirmed=0;}

@$excludeGlobalOpts = $_POST['excludeGlobalOpts'];
if ($excludeGlobalOpts!=-1) {$excludeGlobalOpts=0;}
@$excludeListOpts 	= $_POST['excludeListOpts'];
if ($excludeListOpts!=-1) {$excludeListOpts=0;}

@$psubUpdateDuplicates	= $_POST['subUpdateDuplicates'];
if ($psubUpdateDuplicates!=-1) {$psubUpdateDuplicates=0;}

$justLists="";
@$listsTicked		= count($_POST['idList']);
if ($listsTicked!=0) {
    for ($z=0; $z<$listsTicked; $z++)  {
        $justLists .= $_POST['idList'][$z].', ';
    }
    $justLists = rtrim($justLists, ", ");
}
$extraUsql =", confirmed='".$confirmed."', prefersHtml='".$prefersHtml."', subUpdateDuplicates='".$psubUpdateDuplicates."', excludeGlobalOpts='".$excludeGlobalOpts."', excludeListOpts='".$excludeListOpts."', idList='".$justLists."'";

$inserted=0;
$pduplicates=0;
$perrors=0;
$foundInGlobaloptOut=0;

$pdbType		= $_POST['dbType'];
$pdbName		= $_POST['dbName'];
$pdbHost		= $_POST['dbHost'];
$pdsnName		= $_POST['dsnName'];
$pdbUserName	= $_POST['dbUserName'];
$pdbPassword	= $_POST['dbPassword'];
$ptbName		= $_POST['tbName'];
$pAction        = $_POST['action'];
$custSQL = " ";
$pcustomSQL 	= my_stripslashes($_POST['customSQL']);
if (!empty($pcustomSQL)) {
	$custSQL = " ".$pcustomSQL;
}

//  #### UPDATING A DATA SOURCE
if ($pAction=="updateDataSource") {
    $updateDataSourceSQL ="";
    $uSQL = "";
    foreach ($_POST as $key => $value) {
        if ($key!="confirmed" && $key!="prefersHtml" && $key!="subUpdateDuplicates" && $key!="excludeGlobalOpts" && $key!="excludeListOpts" && $key!="idDataSource" && $key!="idList" && $key!="updateDataSourceBtn" && $key!="action" && $key!="_" ) {
            $updateDataSourceSQL.= $key.'=\''.$value.'\', ';
         }
    }
    $updateDataSourceSQL = rtrim($updateDataSourceSQL,', ');
    $uSQL='UPDATE '.$idGroup.'_dataSources set '.$updateDataSourceSQL.$extraUsql.' where idDataSource='.$_POST['idDataSource'];
    echo '<span class="okmessage"><img src="./images/doneOk.png">&nbsp;'.EXTERNALDBIMPORTFORM_40.'</span>';
    $obj->query($uSQL);
    return false;
}

// #### SAVING DATASOURCE
if ($pAction=="saveDataSource") {   //exclude idList, saveDataSourceBtn, action, _
    $insertSQL1 ="";
    $insertSQL2 ="";
    $sSQL = "";
    foreach ($_POST as $key => $value) {
        if ($key!="idDataSource" && $key!="idList" && $key!="saveDataSourceBtn" && $key!="action" && $key!="_" ) {
            $insertSQL1.=	$key.', ';
            $insertSQL2.=	"'".$value."'".', ';
        }
    }
	$insertSQL1 .= ' idList';
	$insertSQL2 .= '\''.$justLists.'\'';
    $sSQL='INSERT INTO '.$idGroup.'_dataSources ('.$insertSQL1.') VALUES ('.$insertSQL2.')';
    $obj->query($sSQL);
    return false;
}


// ####### SUBSCRIBERS SELECT SQL
$selectSQL="";
foreach ($_POST as $key => $value) {
    if ($key!="saveDataSourceBtn" && $key!="dataSourceFriendlyName" && $key!="customSQL" && $key!="tbName" && $key!="confirmed" && $key!="prefersHtml" && $key!="subUpdateDuplicates" && $key!="excludeGlobalOpts" && $key!="excludeListOpts" && $key!="idDataSource" && $key!="dbUserName" && $key!="dbPassword" && $key!="idGroup" && $key!="dbType" && $key!="dbName" && $key!="dbHost" && $key!="idList" && $key!="updateDataSourceBtn" && $key!="action" && $key!="_" && $value) {
        //echo $key.'>'.$value.'<br />';
        //$selectSQL.= $ptbName.'.'.$value.' as '.$key.', ';  //removed the table name for more flexibility
		$selectSQL.= $value.' as '.$key.', ';
     }
}
$selectSQL = 'SELECT distinct ' .rtrim($selectSQL,', ').' FROM '.$ptbName.' '.$custSQL;
//die($selectSQL);

// #### SOURCE DB CONNECTIONS: mySQL connection
if ($pdbType==1) {
	$conlink = mysqli_connect($pdbHost, $pdbUserName, $pdbPassword, $pdbName);
	if(!$conlink) {
    	die('<span class=errormessage>Unable to connect to '.$pdbHost.'</span><br>');
	}
	$result	= mysqli_query($conlink, $selectSQL);
	if(!$result) {die('<span class=errormessage>'.$selectSQL.'<br /><br />'.EXTERNALDBIMPORTFORM_47.'</span><br>');	}
    $rows 	=  mysqli_num_rows($result);
    if (!$rows) {die('<span class=errormessage>'.EXTERNALDBIMPORTFORM_46.'</span><br>');};
    function my_fetch_array($r) {return mysqli_fetch_array($r, MYSQLI_BOTH);}
    function closeConn($conlink) {return mysqli_close($conlink);}
}

//####### MSSQL connection using mssql
if ($pdbType==2) {
	$conlink = mssql_connect($pdbHost, $pdbUserName, $pdbPassword);
	if(!$conlink) 	{
    	die('Type-2. Unable to connect to '.$pdbHost.'<br>');
	}
	$db_selected = mssql_select_db ($pdbName, $conlink);
	if(!$db_selected) 	{
    	die('Unable to select database '.$pdbName.'<br>');
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
if ($pdbType==6) {
    $connectionInfo = array("Database"=>$pdbName,"UID"=>$pdbUserName,"PWD"=>$pdbPassword);
	$conlink = sqlsrv_connect($pdbHost, $connectionInfo);
	if(!$conlink) 	{
		//die('Type-2. Unable to connect to '.$pdbHost.'<br>');
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
/*
//##########PostGreSQL connection
if ($pdbType==7) {	//PostGreSQL connection
    //port=5432
    $conlink = pg_connect("host=$pdbHost  dbname=$pdbName user=$pdbUserName password=$pdbPassword");
	if(!$conlink) {die('<span class=errormessage>Unable to connect to '.$pdbHost.'</span><br>');}

	$result	= pg_query($conlink, $selectSQL);
	if(!$result) 	{die('<span class=errormessage>'.$selectSQL.'<br /><br />'.EXTERNALDBIMPORTFORM_47.'</span><br>');}

    $rows 	=  pg_num_rows($result);
    if (!$rows) {die('<span class=errormessage>'.EXTERNALDBIMPORTFORM_46.'</span><br>');};

	function closeConn($conlink) {return pg_close($conlink);}

	// function my_fetch_array($result) {return pg_fetch_array($result, PGSQL_ASSOC);}  //misses columns...
    //$limit=pg_num_rows($result);
    function my_fetch_array($result) {
        //return pg_fetch_array($result, $fetch_array_counter, PGSQL_ASSOC);
		return pg_fetch_assoc($result);
		//for($rownum=0;$rownum<3;$rownum++)  {
 		//	//return pg_fetch_array($result, $rownum);
		//	return pg_fetch_assoc($result, $rownum);
 		//}
    }
}
while ($newValue = my_fetch_array($result)){
	foreach ($newValue as $newValue) { echo $newValue.":::";}
	echo '<br>';
} */
if ($pAction=="testCount") {
     echo '<span class="okmessage">'.EXTERNALDBIMPORTFORM_42.':&nbsp;'.$rows.'&nbsp;'.EXTERNALDBIMPORTFORM_41.'</span>';
    return false;
}

//####  SUBSCRIBERS INSERT SQL
$leftInsert="";     //that's OK
foreach ($_POST as $key => $value) {
    if ($value && $key!="dataSourceFriendlyName" && $key!="dsnName" && $key!="customSQL" && $key!="tbName" && $key!="confirmed" && $key!="prefersHtml" && $key!="excludeGlobalOpts" && $key!="excludeListOpts" && $key!="subUpdateDuplicates" && $key!="idDataSource" && $key!="dbUserName" && $key!="dbPassword" && $key!="idGroup" && $key!="dbType" && $key!="dbName" && $key!="dbHost" && $key!="idList" && $key!="updateDataSourceBtn" && $key!="action" && $key!="_") {
        $leftInsert.=	$key.', ';
    }
}
$leftInsert = rtrim($leftInsert,', '). ", confirmed, prefersHtml, dateSubscribed, idGroup";

$f=0;
$rightInsert="";
foreach ($_POST as $key => $value) {
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
						$mySQL3="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$last.", ".$_POST['idList'][$z].", $idGroup)";
						$result3	= $obj->query($mySQL3);
					} // for
				}	//listsTicked not 0
		}
        else {
            //he exists already, we get his email id
            $pduplicates=$pduplicates+1;
     		if ($psubUpdateDuplicates==-1) {
		    	$nidemail	= $row["0"];
                $mySQL4=$updateSQL.$nidemail;
				//echo $mySQL4."<br>";
                $obj->query($mySQL4);
				//assign to lists
				 if ($listsTicked!=0) {
				    For ($z=0; $z<$listsTicked; $z++)  {
					    $mySQL3="INSERT IGNORE INTO ".$idGroup."_listRecipients (idEmail, idList, idGroup) VALUES (".$nidemail.", ".$_POST['idList'][$z].", $idGroup)";
						$obj->query($mySQL3);
					 } //FOR
				} //lists ticked
			}	// $psubUpdateDuplicates
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
 		$mySQLd="DELETE FROM ".$idGroup."_listRecipients WHERE idList=".$_POST['idList'][$z]." AND idEmail IN (SELECT idEmail FROM ".$idGroup."_subscribers WHERE email IN (SELECT subscriberEmail FROM ".$idGroup."_optOutReasons WHERE optOutType=".$_POST['idList'][$z]."))";
		$obj->query($mySQLd);
	   //	echo $mySQLd."<br>";
	}
}
$obj->closeDb();
closeConn($conlink);
?>

<br>
<span class="menu"><?php echo EXTERNALDBIMPORTFORM_26?></span>
<br />
<?php
echo $inserted .EXTERNALDBIMPORTFORM_27.'<br />';
echo $pduplicates;
if ($psubUpdateDuplicates==-1) {
	echo EXTERNALDBIMPORTFORM_48;
} else {
	echo EXTERNALDBIMPORTFORM_28;
}
echo '<br />'.$foundInGlobaloptOut  .' '.SUBSCRIBERSIMPORT_36;
echo '<br />'.$perrors .EXTERNALDBIMPORTFORM_49;
?>