<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include('adminVerify.php');
include('../inc/dbFunctions.php');
$obj 		= new db_class();
include('../inc/stringFormat.php');
include('./includes/auxFunctions.php');
include('./includes/languages.php');
$groupName 	 =	$obj->getSetting("groupName", $idGroup);
$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
include('header.php');
showMessageBox();

@$mySQL=my_stripslashes($_POST['mySQL']);
?>
<span class="title"><?php echo QUICKVIEW_1; ?></span>
<br><br>
<form name="quickSQL" method="post" action="quickSQL.php">
<textarea name="mySQL" id="mySQL" class="textarea" rows=10 cols=100><?php echo $mySQL?></textarea>
<input type="submit" class="submit" value="Process"/>
</form>
<?php
if ($mySQL) {
$rows="";
$affrows="";
$pNOW       = date("Y-m-d H:i:s", strtotime("+$pTimeOffsetFromServer hours"));
$mySQL  = str_replace("##now##", "'".$pNOW."'", $mySQL);
$result = $obj->query($mySQL);

if (stripos($mySQL, "SELECT")!==false || stripos($mySQL, "SHOW")!==false || stripos($mySQL, "DESCRIBE")!==false || stripos($mySQL, "EXPLAIN")!==false ) {
$rows 	= $obj->num_rows($result);
$fieldCount  = $obj->field_count();
}
if (stripos($mySQL, "INSERT")!==false || stripos($mySQL, "UPDATE")!==false || stripos($mySQL, "DELETE")!==false || stripos($mySQL, "DROP")!==false ) {
$affrows 	= $obj->affected_rows($result);
}

echo $mySQL.'<br /><br />';
if ($affrows) {echo "Result: ".$affrows;}

if ($rows) {
?>
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
				<?php IF ($COUNTER==0) {
					echo "<a href='editSubscriber.php?idEmail=".$row[$i]."'>";
				} ?>
				<?php echo $row[$i]?>
				<?php IF ($COUNTER==0) {
					echo '</a>';
				}	?>
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
}
include('footer.php');
?>