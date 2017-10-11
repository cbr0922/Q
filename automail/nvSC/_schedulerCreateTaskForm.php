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
?>
<style type="text/css" media="screen,projection">
	@import url(./datepicker/default.css);
</style>
<SCRIPT language="Javascript" type="text/javascript">
<!--
$(function() {
		$( "#activationDate" ).datepicker({
			dateFormat: "yy-mm-dd",
			showOn: "button",
			buttonImage: "./datepicker/calendar.gif",
			buttonImageOnly: true
		});
	});

function display() {
	if($("#type").val()=="repeatevery") {
		$("#repeatevery").show();
	}
	else {
		$("#repeatevery").hide();
        $("#numberOfMessagesToSend").clear();
        $("#RepeatEveryPeriod").val("0");
        $("#RepeatEveryPeriodValue").clear();
	}
}

function formvalidation() {
    if ($("#idCampaign").val()=="0" || $("#activationDate").val()=="" || $("#activationTimeH").val()=="hh" || $("#activationTimeM").val()=="mm" || $("#type").val()=="0") {
	    openAlertBox('<?php echo fixJSstring(SCHEDULERTASKS_28);?>','');
    	return false;
    }
    if ($("#type").val()=="repeatevery" && ($("#numberOfMessagesToSend").val()==0 || $("#numberOfMessagesToSend").val()=="" || $("#RepeatEveryPeriodValue").val()=="" || $("#RepeatEveryPeriod").val()=="0") ) {
	    openAlertBox('<?php echo fixJSstring(SCHEDULERTASKS_29);?>','');
    	return false;
    }
	
	if ($('#recurringEvent').is(':checked') && ($("#reactivatePeriodValue").blank() || $("#reactivatePeriod").val()==0)) 	{
		openAlertBox('<?php echo fixJSstring(SCHEDULERTASKS_53);?>','');
		return false
	}
}
//-->
</SCRIPT>

<table border="0" cellpadding=0 width="960px">
	<tr>
		<td valign=top>
   			<span class="title"><?php echo SCHEDULERTASKS_24;?></span>
   			<br><a href="_schedulerTasks.php"><?php echo SCHEDULERTASKS_25;?></a>
		</td>
		<td align=right>
			<img src="./images/addtask.png" width="65" height="51" alt="">
   		</td>
	</tr>
</table>
<br />
<form id="createTaskForm" name="createTaskForm" method="post" action="_schedulerCreateTaskExec.php" onsubmit="return formvalidation();" >
<table border="1" cellpadding="6" cellspacing="0" width="90%" style="BORDER-RIGHT: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;BORDER-COLLAPSE: collapse;">
	<tr>
   		<td valign="top" width=250 class="settingsRow">
   			<span style="color:#fff;"><?php echo SCHEDULERTASKS_26;?></span>
   		</td>
        <td valign=top bgcolor="#ededed">
  			<SELECT name="idCampaign" id="idCampaign" class="select">
   			<option value="0"><?php echo SCHEDULERTASKS_27;?></option>
            <?php $count=0;
            $mySQL="Select ".$idGroup."_campaigns.idCampaign, ".$idGroup."_campaigns.campaignName, ".$idGroup."_campaigns.dateCreated, type, idList, idHtmlNewsletter, idTextNewsletter from ".$idGroup."_campaigns where completed=0 AND idCampaign NOT IN (SELECT idCampaign from ".$idGroup."_tasks) order by idCampaign desc";
			$result	= $obj->query($mySQL);
            while ($row = $obj->fetch_array($result)){
                $idCampaign         =   $row["idCampaign"];
				$campaignName       =   $row["campaignName"];
                $dateCreated        =   $row["dateCreated"];
                $type               =   $row["type"];
                $idList             =   $row["idList"];
                $idHtmlNewsletter   =   $row["idHtmlNewsletter"];
                $idTextNewsletter   =   $row["idTextNewsletter"];
    			if (!contentDeleted($type,$idHtmlNewsletter,$idTextNewsletter,$idGroup) && !listDeleted($idList, $idGroup)) {
    			  $count=$count+1;?>
	    		<option value="<?php echo $idCampaign;?>"><?php echo $idCampaign.' - '.$campaignName.' - '.$dateCreated;?></option>
		    	<?php  }

            } if ($count==0) {?><option value="0"><?php echo SCHEDULERTASKS_58;?></option><?php }
            ?>
			</SELECT>
		</td><?php // echo $count;?>
   	</tr>

   	<tr>
   		<td valign="top" class="settingsRow">
   			<span style="color:#fff;"><?php echo SCHEDULERTASKS_6;?></span>
   		</td>
		<td valign=top bgcolor="#ededed">
			<input class="fieldbox11" style="margin-right:7px" size="20" type="text" id="activationDate" name="activationDate" value="<?php echo date("Y-m-d");?>">&nbsp;
			&nbsp;&nbsp;&nbsp;
			<select class="select" name="activationTimeH" id="activationTimeH">
					<option value="hh">hh</option>
					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
					<option value="21">21</option>
					<option value="22">22</option>
					<option value="23">23</option>
			</select>&nbsp;:
			<select class="select" id="activationTimeM" name="activationTimeM">
					<option value="mm">mm</option>
					<option value="00">00</option>
					<option value="05">05</option>
					<option value="10">10</option>
					<option value="15">15</option>
					<option value="20">20</option>
					<option value="25">25</option>
					<option value="30">30</option>
					<option value="35">35</option>
					<option value="40">40</option>
					<option value="45">45</option>
					<option value="50">50</option>
					<option value="55">55</option>
			</select>
		</td>
	</tr>
	<tr>
		<td valign=top  class="settingsRow">
			<span style="color:#fff;"><?php echo SCHEDULERTASKS_32;?></span>
		</td>
		<td bgcolor="#ededed">
            <select class=select id="type" name="type" onchange="display();">
            <option value="0"><?php echo SCHEDULERTASKS_31;?></option>
            <option value="allatonce"><?php echo SCHEDULERTASKS_33;?></option>
            <option value="repeatevery"><?php echo SCHEDULERTASKS_34;?></option>
            </select>
       </td>
    </tr>
    <tr id="repeatevery" style="display:none;">
        <td  class="settingsRow" valign="top">
            <span style="color:#fff;"><?php echo SCHEDULERTASKS_35;?></span>
        </td>
        <td bgcolor="#ededed">
		    <table  cellspacing="0" cellpadding="2" border=0>
				<tr>
					<td>
						<?php echo SCHEDULERTASKS_7;?>
					</td>
					<td>
						<input class="fieldbox11" type="text" id="numberOfMessagesToSend" name="numberOfMessagesToSend" size="6" value="" />
					</td>
				</tr>
				<tr>
				    <td>
					    <?php echo SCHEDULERTASKS_16;?>
					</td>
					<td>
        				<input class="fieldbox11" size="4" type="text" id="RepeatEveryPeriodValue" name="RepeatEveryPeriodValue" value="">
        				<select id="RepeatEveryPeriod" name="RepeatEveryPeriod" class=select>
        				<!--<option value="0"><?php echo SCHEDULERTASKS_37;?>
        				<option value="we"><?php echo SCHEDULERTASKS_39;?>
        				<option value="da"><?php echo SCHEDULERTASKS_40;?>
        				<option value="ho"><?php echo SCHEDULERTASKS_41;?>-->
        				<option value="mi"><?php echo SCHEDULERTASKS_42;?></option>
        				</select>
        			</td>
				</tr>
            </table>
        </td>
    </tr>
	<tr>
        <td class="settingsRow" valign=top>
            <span style="color:#fff;"><?php echo SCHEDULERTASKS_36;?></span>
        </td>
        <td bgcolor="#ededed">
		    <table>
                <tr>
                    <td><?php echo SCHEDULERTASKS_22;?></td>
                    <td><input type="checkbox" name="recurringEvent" id="recurringEvent" value="-1"/></td>
                </tr>
        	    <tr>
					<td><?php echo SCHEDULERTASKS_23;?>&nbsp;</td>
					<td>
        				<input class="fieldbox11" size="4" type="text" name="reactivatePeriodValue" id="reactivatePeriodValue" value="">
        				<select name="reactivatePeriod" id="reactivatePeriod" class=select>
        				<option value="0"><?php echo SCHEDULERTASKS_37;?></option>
        				<option value="mo"><?php echo SCHEDULERTASKS_38;?></option>
        				<option value="we"><?php echo SCHEDULERTASKS_39;?></option>
        				<option value="da"><?php echo SCHEDULERTASKS_40;?></option>
        				<option value="ho"><?php echo SCHEDULERTASKS_41;?></option>
        				<!--<option value="mi"><?php echo SCHEDULERTASKS_42;?>-->
        				</select>
        			</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
   			<td height=40 style="BORDER-TOP: #999999 1px solid;">&nbsp;</td>
			<td style="BORDER-LEFT: #fff 1px solid;BORDER-TOP: #999999 1px solid;">
				<input type="submit" class="submit" name="sendNow" value="<?php echo SCHEDULERTASKS_43;?>">
			</td>
		</tr>
</table>
</form>

<?php
$obj->free_result($result);
$obj->closeDb();
include('footer.php');
?>