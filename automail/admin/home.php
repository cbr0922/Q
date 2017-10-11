<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
include ('adminVerify.php');
include ('../inc/dbFunctions.php');
include ('../inc/stringFormat.php');
include ('./includes/auxFunctions.php');
include('./includes/languages.php');
$obj = new db_class();
include ('header.php');
showMessageBox();

$pTimeOffsetFromServer 	=	$obj->getSetting("groupTimeOffsetFromServer", $idGroup);
//get new subscribers for the admin that is logged in now.
$mySQL = "SELECT newSubscribers, minusSubscribers FROM ".$idGroup."_adminStatistics WHERE idAdmin=".$sesIDAdmin;
$result = $obj->query($mySQL);
$row = $obj->fetch_row($result);
$pnewsubscribers = $row[0];
$pminussubscribers = abs($row[1]);

//reset adminStatistics counter to 0
$mySQL1 = "UPDATE ".$idGroup."_adminStatistics SET newSubscribers=0, minusSubscribers=0 WHERE idAdmin=".$sesIDAdmin;
$obj->query($mySQL1);
//birthdays
$dayNow 	= date("j", strtotime("+$pTimeOffsetFromServer hours"));
$monthNow 	= date("n", strtotime("+$pTimeOffsetFromServer hours"));
$mySQLb 	= "SELECT count(*) as birthDays FROM ".$idGroup."_subscribers where idGroup=$idGroup AND subBirthDay='".$dayNow."' AND subBirthMonth='".$monthNow."'";
$birthdays 	= $obj->get_rows($mySQLb);
//ALL SUBSCRIBERS
$psubscribers 			= $obj->tableCount_condition($idGroup."_subscribers", " where idGroup=".$idGroup."");
//CONFIRMED
$pconfirmedsubscribers 	= $obj->tableCount_condition($idGroup."_subscribers", " where confirmed=-1 AND idGroup=".$idGroup."");
//UN-CONFIRMED
$punconfirmedsubscribers = $psubscribers-$pconfirmedsubscribers;
//SOFT & HARD BOUNCES
$psoft_subscribers = $obj->tableCount_condition($idGroup."_subscribers", " where soft_bounces>0 AND idGroup=".$idGroup."");
//HARD BOUNCES
$phard_subscribers = $obj->tableCount_condition($idGroup."_subscribers", " where hard_bounces>0 AND idGroup=".$idGroup."");
// SYNTAX INVALID EMAILS
$pinvalidSyntax_subscribers = $obj->tableCount_condition($idGroup."_subscribers", " where emailIsValid=0 AND idGroup=".$idGroup."");
// BANNED / INACTIVE
$bannedSubscribers 	= $obj->tableCount_condition($idGroup."_subscribers", " where emailIsBanned=-1 AND idGroup=".$idGroup."");
//NEWSLETTERS
$pnewsletters = $obj->tableCount_condition($idGroup."_newsletters", " where html=-1 AND idGroup=".$idGroup."");
$ptnewsletters = $obj->tableCount_condition($idGroup."_newsletters", " where html=0 AND idGroup=".$idGroup."");
$pallnewsletters = $ptnewsletters + $pnewsletters;
//ACTIVITY LOGS
$pmaillogs = $obj->tableCount_condition($idGroup."_campaigns", " where idGroup=".$idGroup."");
//not complete
$unfMaillogs = $obj->tableCount_condition($idGroup."_campaigns", " WHERE completed=-1 AND idGroup=".$idGroup."");
//LISTS
$plists =  $obj->tableCount_condition($idGroup."_lists", " WHERE idGroup=".$idGroup."");
?>
<table border="0" cellpadding=2 cellspacing=0 width="960px">
	<tr>
		<td valign=top><span class="title"><?php echo HOME_19 ?>&nbsp;<?php echo getadminname($sesIDAdmin,$idGroup);?>,</span></td>
		<td align=right valign=top><img src="./images/home.png" alt="" width="46" height="46"></td>
	</tr>
</table>
<div align="center">
<div style="BACKGROUND:#f7f7f7;width:620px;border: #CCC 1px solid; padding:15px; -moz-border-radius: 15px;border-radius:15px;">
	<!--START--><!-- the main central cell with the content-->
	<table cellpadding=0 cellspacing=0 style="BORDER-RIGHT: #FDB664 0px solid; BORDER-TOP: #FDB664 0px solid; BORDER-LEFT: #FDB664 0px solid; BORDER-BOTTOM: #FDB664 0px solid">
	<tr>
		<td valign="top">
			<table border=0 cellpadding=2 cellspacing=0>
			     <tbody>
			       <tr>
			         <td rowspan="6" valign="top"><img src="./images/subscribers.png" alt=""></td>
			          <td align="left"><span class="homeBig"><?php echo HOME_1;?></span>:</td>
			          <td align="right"><span class="homeSmall"><?php echo $psubscribers?></span></td>
			      </tr>
			       <tr>
			          <td align="left"><span class="homeSmall"><?php echo HOME_35; ?></span>:</td>
			          <td align="right"><span class="homeSmall"><?php echo $pconfirmedsubscribers?></span></td>
			      </tr>
			       <tr>
			          <td align="left"><span class="homeSmall"><?php echo HOME_36; ?></span>:</td>
			          <td align="right"><span class="homeSmall"><?php echo $punconfirmedsubscribers?></span></td>
			      </tr>
			       <tr>
			          <td align="left"><span class="homeSmall"><?php echo HOME_37;?></span>:</td>
			          <td align="right"><span class="homeSmall"><?php echo $psoft_subscribers?></span></td>
			      </tr>
			      <tr>
			          <td align="left"><span class="homeSmall"><?php echo HOME_38; ?></span>:</td>
			          <td align="right"><span class="homeSmall"><?php echo $phard_subscribers?></span></td>
			      </tr>
			       <tr>
			          <td align="left"><span class=homeSmall><?php echo ADMIN_HEADER_75;?></span>:</td>
			          <td align="right"><span class="homeSmall"><?php echo $bannedSubscribers?></span></td>
			      </tr>

			    <tr><td colspan=3>&nbsp;</td></tr>
			    <tr>
			        <td valign=top align=left rowspan=7><!--img src="./images/campaigns.png" alt=""><br /--><img src="./images/lists.png" width="50" height="63" alt=""></td>
			        <td valign=top align="left"><span class="homeBig"><?php echo HOME_30;?></span>:</td>
			        <td valign=top align="right"><span class="homeSmall"><?php echo $pmaillogs?></span></td>
			    </tr>
			    <tr>
			         <!--td valign=top rowspan=4 align=left>z</td-->
			         <td valign="top" align="left"><span class="homeBig"><?php echo HOME_18; ?></span>:</td>
			         <td valign="top" align="right"><span class="homeSmall"><?php echo $plists?></span></td>
			      </tr>
			      <tr>

			        <td valign=top align="left"><span class="homeBig"><?php echo HOME_5; ?></span>:</td>
			        <td valign=top align="right"><span class="homeSmall"><?php echo $pallnewsletters?></span></td>
			    </tr>
			    <tr>
			        <td valign=top align="left"><span class="homeSmall">HTML</span></td>
			        <td valign=top align="right"><span class="homeSmall"><?php echo $pnewsletters?></span></td>
			    </tr>
			    <tr>
			        <td valign=top align="left"><span class="homeSmall"><?php echo HOME_7; ?></span></td>
			        <td valign=top align="right"><span class="homeSmall"><?php echo $ptnewsletters?></span></td>
			    </tr>
			    </tbody>
			</table>
		</td>
		<td width="50"  style="BORDER-right: #FDB664 0px solid;">&nbsp;</td>
		<td>&nbsp;</td>
		<td valign=top>
		    <table cellpadding=2 border=0 cellspacing=0>
		      	<tr>
		      		<td valign=top colspan=2 align="left"><span class="homeBig"><?php echo HOME_6;?></span></td>
		        </tr>
		      	<tr>
		      		<td valign=top  align="left"><span class="homeSmall"><?php echo HOME_8;?></span>:</td>
		            <td valign=top align="right"><span class="homeSmall"><?php echo $pnewsubscribers; ?></span></td>
		        </tr>
		      	<tr>
		      		<td valign=top  align="left" style="padding-top:5px;"><span class="homeSmall"><?php echo HOME_13;?></span>:</td>
		            <td valign=top style="padding-top:5px;" align="right"><span class="homeSmall"><?php echo $pminussubscribers; ?></span></td>
		        </tr>
		      	<tr>
		      		<td valign=top colspan=2 align="left" style="padding-top:20px;"><span class="homeBig"><?php echo HOME_9;?></span></td>
		        </tr>
		        <tr>
		      		<td valign=bottom  style="padding-top:5px;"  align="left"><?php if ($birthdays<>0) {echo "<img alt='' src='images/star.png' height='16' width='16'>";}?>&nbsp;<span class="homeSmall"><?php echo HOME_43;?></span>:</td>
		            <td valign=bottom  style="padding-top:5px;" align="right"><span class="homeSmall"><?php echo $birthdays; ?></span><?php if ($birthdays<>0) {echo '&nbsp;<a href="birthdays.php">'.HOME_2.'</a>';}?></td>
		        </tr>
		        <tr>
		      		<td valign=top  style="padding-top:5px;"  align="left"><?php if ($pinvalidSyntax_subscribers<>0) {echo "<img alt='' src='images/warning.png' height='14' width='14'>";}?>&nbsp;<span class="homeSmall"><?php echo HOME_16;?>:</span></td>
		            <td valign=top  style="padding-top:5px;" align="right"><span class="homeSmall"><?php echo $pinvalidSyntax_subscribers; ?></span><?php if ($pinvalidSyntax_subscribers<>0) { ?>
		             	 &nbsp;<img alt="" onmouseover="infoBox('homebubble_1', '<?php echo fixJSstring(HOME_16);?>', '<?php echo fixJSstring(HOME_17);?>', '25em','0')" onmouseout="hide_info_bubble('homebubble_1','0')" src="./images/helpSmallWhite.gif" style="vertical-align:bottom;"><span style="display: none;text-align:left;" id="homebubble_1"></span><?php }?>
		            </td>
		        </tr>
				<tr>
		      		<td valign=bottom  colspan="2" align="left" >
						<div style="margin-top:10px;"><a href="#" class="cross" onclick="show_hide_many(Array('q1','q2','q3'), 'cross');return false;"><span id="cross">[+]</span>&nbsp;<?php echo (HOME_27)?></a></div>
						<div id="q1" style="display:none;margin-top:2px;"><a href="../subscriber/newsletterArchive.php" target="_blank"><?php echo (SMARTLINKS_24);?></a></div>
						<div id="q3" style="display:none;margin-top:2px;"><a href="../subscriber/privacy.php" target="_blank"><?php echo (ADMIN_HEADER_71);?></a></div>
						<div id="q2" style="display:none;margin-top:2px;"><a href="../subscriber/subLogin.php" target="_blank"><?php echo (HOME_28);?></a></div>
						<div style="border-radius:5px;-moz-border-radius: 5px;margin-top:10px;padding:10px;BORDER-RIGHT: #CCCCCC 1px solid; BORDER-TOP: #CCCCCC 1px solid; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid">
							nuevoMailer&nbsp;&nbsp;v.<?php echo $nuevoRelease?>&nbsp;&nbsp;<?php echo HOME_47.'&nbsp;'.$databaseType?>
						</div>
		        	</td>
		        </tr>
		    </table>
	  </td>
	</tr>
	</table>
	<!--END--><!-- the main central cell with the content-->
	</div>
</div>
<div align="center" id="chart_campaigns_parent" style="padding-top:20px;float:center;"></div>
<script type="text/javascript" language="javascript">
    $('#chart_campaigns_parent').html('<img id="bigLoader" src="./images/waitBig.gif">');
	$('<iframe>', {src: 'chart_jq_last5c.php', id:'chart_jq_last5c',frameborder:0,scrolling: 'no'}).appendTo('#chart_campaigns_parent');
	$('#chart_jq_last5c').bind('load',
	function() {
		$("#bigLoader").remove();
		$("#chart_jq_last5c").width("900px");
		$("#chart_jq_last5c").height("420px");
	}
);
</script>

<?php
include ('footer.php');
$obj->closeDb();
?>
