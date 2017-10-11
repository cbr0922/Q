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
include('header.php');
showMessageBox();

(isset($_GET['flag']))?$fSQL = "SELECT * from ".$idGroup."_subscribers  WHERE idGroup=$idGroup AND emailisBanned=-1":$fSQL="";
(isset($_POST['mySQL']))?$mySQL = $_POST['mySQL']:$mySQL=$fSQL;

$mySQL=my_stripslashes($mySQL);
$result = $obj->query($mySQL);
$rows 	= $obj->num_rows($result);
$fieldCount  = $obj->field_count();
?>
<span class="title"><?php echo QUICKVIEW_1; ?></span>
<br><br>

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
include('footer.php');
?>