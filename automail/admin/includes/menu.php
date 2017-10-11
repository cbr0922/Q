<?php 
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<ul id='menu-1' class='menu' role='menu'>
	<li class="litop"><a href='home.php'><?php echo ADMIN_HEADER_1;?></a></li>
	<li class="litop"><a class='subd'><?php echo ADMIN_HEADER_2;?></a>
		<ul>
			<li class='first_'><a href='listNewsletterSubscribers.php'><?php echo ADMIN_HEADER_3;?></a></li>
			<li><a class='sub'><?php echo ADMIN_HEADER_5;?></a>
				<ul>
					<li class='first_'><a href='createSendFilter.php'><?php echo ADMIN_HEADER_6;?></a></li>
					<li class='last_'><a href='findSubscriberExec.php'><?php echo ADMIN_HEADER_7;?></a></li>
				</ul>
			</li>
			<li><a class='sub'><?php echo ADMIN_HEADER_9;?></a>
				<ul>
					<li class='first_'><a href='editSubscriber.php'><?php echo ADMIN_HEADER_10;?></a></li>
					<li class='last_'><a class='sub'><?php echo ADMIN_HEADER_11;?></a>
						<ul>
							<li class='first_'><a href='quickImport.php'><?php echo ADMIN_HEADER_12;?></a></li>
							<li><a href='csvImport.php'><?php echo ADMIN_HEADER_14;?></a></li>
							<li class='last_'><a href='externalDbImport.php'><?php echo ADMIN_HEADER_15;?></a></li>
						</ul>
					</li>
				</ul>
				</li>
				<li><a href='csvExport.php'><?php echo ADMIN_HEADER_16;?></a></li>
				<li><a class='sub'><?php echo ADMIN_HEADER_22;?></a>
					<ul>
						<li class='first_'><a href='createSignUpForm.php'><?php echo ADMIN_HEADER_23;?></a></li>
						<li class='last_'><a href='optOutForm.php'><?php echo ADMIN_HEADER_24;?></a></li>
					</ul>
				</li>
				<li>
					<a class='sub'><?php echo ADMIN_HEADER_29;?></a>
					<ul>
						<li class='first_'><a href='quickDelete.php'><?php echo ADMIN_HEADER_31;?></a></li>
						<li><a href='deleteNonConfirmedByDate.php'><?php echo ADMIN_HEADER_35;?></a></li>
						<li class='last_'><a href="#" onclick="openConfirmBox('delete.php?action=allsubs','<?php echo fixJSstring(CONFIRM_2);?><br><?php echo fixJSstring(GENERIC_2);?>');return false;"><?php echo ADMIN_HEADER_30;?></a></li>
					</ul>
			</li>
			<li class='last_'><a class='sub'><?php echo ADMIN_HEADER_25;?></a>
				<ul>
					<li class='first_'><a class='sub'><?php echo ADMIN_HEADER_26;?></a>
						<ul>
							<li class='first_'><a href='makeAllHtmlText.php?h=-1'><?php echo ADMIN_HEADER_27;?></a></li>
							<li class='last_'><a href='makeAllHtmlText.php?h=0'><?php echo ADMIN_HEADER_28;?></a></li>
						</ul>
					</li>

					<li><a class='sub'><?php echo ADMIN_HEADER_32;?></a>
						<ul>
							<li class='first_'><a href='makeAllConfirmed.php'><?php echo ADMIN_HEADER_33;?></a></li>
							<li class='last_'><a href='sendConfirmRequest.php'><?php echo ADMIN_HEADER_34;?></a></li>
						</ul>
					</li>
					<li class='last_'><a href='listNewsletterSubscribers.php?idList=-1'><?php echo ADMIN_HEADER_36;?></a></li>
				</ul>
			</li>
		</ul>
	</li>


   	<li class="litop"><a class='subd'><?php echo ADMIN_HEADER_38;?></a>
		<ul>
			<li class='first_'><a href='lists.php'><?php echo ADMIN_HEADER_39;?></a></li>
			<li><a href='modifyList.php'><?php echo ADMIN_HEADER_40;?></a></li>
			<li><a href='followUpList.php'><?php echo ADMIN_HEADER_88;?></a></li>
			<li class='last_'><a href='suppressionList.php'><?php echo ADMIN_HEADER_75;?></a></li>
		</ul>
	</li>


	<li class="litop"><a class='subd'><?php echo ADMIN_HEADER_42;?></a>
		<ul>
			<li class='first_'><a class='sub'><?php echo ADMIN_HEADER_43;?></a>
				<ul>
					<li class='first_'><a href='htmlNewsletters.php'><?php echo ADMIN_HEADER_44;?></a></li>
					<li class='last_'><a href='sendNewsletterForm.php'><?php echo ADMIN_HEADER_45;?></a></li>
				</ul>
			</li>
			<li><a class='sub'><?php echo ADMIN_HEADER_46;?></a>
				<ul>
					<li class='first_'><a href='textNewsletters.php'><?php echo ADMIN_HEADER_44;?></a></li>
					<li class='last_'><a href='sendTextNewsletterForm.php'><?php echo ADMIN_HEADER_45;?></a></li>
				</ul>
			</li>
			<li><a class='sub'><?php echo ADMIN_HEADER_17;?></a>
				<ul>
					<li class='first_'><a href='editMessages.php'><?php echo ADMIN_HEADER_18;?></a></li>
					<li class='last_'><a href='sendToNew.php'><?php echo ADMIN_HEADER_21;?></a></li>
				</ul>
			</li>
			<li class='last_'><a class='sub'><?php echo ADMIN_HEADER_71;?></a>
				<ul>
					<li class='first_'><a href='modifyPrivacyForm.php'><?php echo ADMIN_HEADER_72;?></a></li>
					<li class='last_'><a target="blank" href='../subscriber/privacy.php'><?php echo ADMIN_HEADER_73;?></a></li>
				</ul>
			</li>
		</ul>
	</li>


   	<li class="litop"><a class='subd'><?php echo ADMIN_HEADER_47;?></a>
		<ul>
			<li class='first_'><a href='campaignCreate.php'><?php echo ADMIN_HEADER_48;?></a></li>
			<li><a href='campaigns.php'><?php echo ADMIN_HEADER_49;?></a></li>
			<li><a href='campaigns.php?f=-1'><?php echo ADMIN_HEADER_50;?></a></li>
			<li class='last_'><a href='campaigns.php?f=0'><?php echo ADMIN_HEADER_51;?></a></li>
		</ul>
	</li>


   	<li class="litop"><a class='subd'><?php echo ADMIN_HEADER_52;?></a>
		<ul>
			<li class='first_'><a href='summary.php'><?php echo ADMIN_HEADER_82;?></a></li>
			<li><a href='clickStats.php'><?php echo ADMIN_HEADER_54;?></a></li>
			<li><a href='customReports.php'><?php echo ADMIN_HEADER_53;?></a></li>
			<?php if ($showGoogleApiReports=="-1") {?>
 			<li><a class="sub">Geomaps</a>
				<ul>
					<li class='first_'><a href='chart_gva_countries.php'><?php echo ALLSTATS_155;?></a></li>
					<li class='last_'><a href='chart_gva_states.php'><?php echo ALLSTATS_159;?></a></li>
 			</ul>
			</li>
 			<?php }?>
			<li><a href='listTraffic.php'><?php echo ADMIN_HEADER_56;?></a></li>
			<li><a href='optOuts.php'><?php echo ADMIN_HEADER_37;?></a></li>
			<li><a href='ratings.php'><?php echo ADMIN_HEADER_86;?></a></li>
			<li class='last_'><a href='editTrackingLinksForm.php'><?php echo ADMIN_HEADER_55;?></a></li>
		</ul>
	</li>


   	<li class="litop"><a class='subd'><?php echo ADMIN_HEADER_57;?></a>
		<ul>
			<li class='first_'><a href='filters.php'><?php echo ADMIN_HEADER_58;?></a></li>
			<li class='last_'><a class='sub'><?php echo ADMIN_HEADER_59;?></a>
				<ul>
					<li class='first_'><a href='createSendFilter.php'><?php echo ADMIN_HEADER_90;?></a></li>
					<li><a href='editSendFiltersExec.php?birthday=birthday'><?php echo ADMIN_HEADER_85;?></a></li>
					<li><a href='dateFilters.php'><?php echo ADMIN_HEADER_87;?></a></li>
					<li class='last_'><a href='editSendFiltersExec.php?addblank=addblank'><?php echo ADMIN_HEADER_84;?></a></li>
				</ul>
			</li>
		</ul>
	</li>


   	<li class="litop"><a class='subd'><?php echo ADMIN_HEADER_60;?></a>
		<ul>
			<li class='first_'><a href='settingsForm.php'><?php echo ADMIN_HEADER_61;?></a></li>
			<!--li><a class='sub'><?php echo ADMIN_HEADER_62;?></a>
				<ul>
					<li class='first_'><a href='admins.php'><?php echo ADMIN_HEADER_63;?></a></li>
					<li class='last_'><a href='modifyAdmin.php'><?php echo ADMIN_HEADER_64;?></a></li>
				</ul>
			</li-->
			<li><a onclick="popUpLayer('<?php echo SMTPSRV_1;?>', 'smtpForm.php',780,450);return false;" href="#"><?php echo SMTPSRV_1;?></a></li>
			<li><a class='sub'><?php echo ADMIN_HEADER_65;?></a>
				<ul>
					<li class='first_'><a href='editCountriesForm.php'><?php echo ADMIN_HEADER_66;?></a></li>
					<li class='last_'><a href='editStatesForm.php'><?php echo ADMIN_HEADER_67;?></a></li>
				</ul>
			</li>
			<li><a href='_bm.php'><?php echo ADMIN_HEADER_74;?></a></li>
			<li class='last_'><a class='sub'><?php echo ADMIN_HEADER_77;?></a>
				<ul>
					<li class='first_'><a href='_schedulerTasks.php'><?php echo ADMIN_HEADER_78;?></a></li>
					<li><a href='_schedulerCreateTaskForm.php'><?php echo ADMIN_HEADER_79;?></a></li>
					<li class='last_'><a target="_blank" href="../data_files/scLog_<?php echo $idGroup?>.txt"><?php echo SCHEDULERTASKS_13;?></a></li>
				</ul>
			</li>
		</ul>
	</li>
	<li class="litop"><a href='helpSupport.php'><?php echo ADMIN_HEADER_80;?></a></li>
</ul>
