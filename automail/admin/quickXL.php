<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj=new db_class();
include('../inc/stringFormat.php');
include('./includes/languages.php');
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
$groupDateTimeFormat 	=	$obj->getSetting("groupDateTimeFormat", $idGroup);
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
$groupGlobalCharset =	$obj->getSetting("groupGlobalCharset", $idGroup);
$today = myDatenow();
include('headerXL.php');

$mySQL=my_stripslashes($_POST['mySQL']);
$result = $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
$fieldCount  = $obj->field_count();

?>
<?php
if ($rows==0) {
	echo '<div>No results.</div>';
	}
	else { ?>
	<table border=1 cellpadding=4 cellspacing=2>
		<TR>
			<?php	//Loop through each Field, printing out the Field Names
			 while ($finfo = $obj->fetch_field($result)) {?>
			<TD>
				<b><?php echo $finfo->name; ?></b>
			</TD>
	    	<?php
			}	//for?>
		</TR>
    	<?php //Loop through rows, displaying each field
    	while ($row = $obj->fetch_array($result)){
    	$COUNTER=0;?>
   		<TR>
    		<?php for ($i=0; $i<$fieldCount; $i++) {?>
			<TD VALIGN=TOP>
				<?php echo $row[$i]?>
			</TD>
    		<?php
			$COUNTER=$COUNTER+1;
    		} //for?>
		</TR>
		<?php
}		?>



	</table>
<?php
}
$obj->closeDb();
