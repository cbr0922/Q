<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<div id="details" style="display:none">
<table width="100%" cellpadding="4" cellspacing="0">
	<tr>
        <td width="40%" valign="top" style="BORDER-top: #c9c9c9 1px solid;BORDER-bottom: #c9c9c9  1px solid;">
			<?php if ($pcampaignName) {echo '<span class="statsLegend">'.CAMPAIGNCREATE_54.': </span>'.$pcampaignName;}?>
    	      <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_4; ?>:</span> <?php echo $pidadmin.'. '.getadminname($pidadmin, $idGroup);?></div>
            <!--subscribers-->
            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_76;?>:</span> <?php  echo $plistname; if (listDeleted($pidlist, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}?></div>
            <!--content-->
            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_75;?>:</span>
                <?php  if ($pidHtmlNewsletter!="0") {echo '<br>'.$pidHtmlNewsletter.'. '.wordwrap(getNewsletterData($pidHtmlNewsletter, $idGroup, 0),60,"<br>\r\n", true).' (Html)';
                if (newsletterDeleted($pidHtmlNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                if ($pidTextNewsletter!="0") {echo '<br>'.$pidTextNewsletter.'. '.wordwrap(getNewsletterData($pidTextNewsletter, $idGroup, 0),60,"<br>\r\n", true).' (Text)';
                if (newsletterDeleted($pidTextNewsletter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}}
                if (!empty($pemailSubject)) {echo '<br>'.$pemailSubject." ($ptypeIs)".'<br>'.wordwrap($purlToSend,60,"<br>\r\n", true);}?></div>
            <!--format-->
            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_89;?>:</span> <?php echo $ptypeIs?></div>
            <!--filter-->
            <div style="margin-top:7px"><span class="statsLegend"><?php echo ALLSTATS_103; ?>:</span>&nbsp;
            <?php if ($pidSendFilter!=0) {
             echo '<br>'.$pidSendFilter .'. '.substr(wordwrap($pSendFilterDesc,60,"<br>\r\n", true), 0,100).'...';
             if (filterDeleted($pidSendFilter, $idGroup)) {echo '<font color=red>'.GENERIC_1.'</font>';}} else {echo ALLSTATS_94;} ?></div>
      </td>
      <td width="40%" valign="top" style="BORDER-top: #c9c9c9 1px solid;BORDER-bottom: #c9c9c9  1px solid;">
            <div style="margin-top:1px">
            <span class="statsLegend"><?php echo ALLSTATS_85;?>:</span> <?php echo $pdateCreated?>
            <br><span class="statsLegend"><?php echo ALLSTATS_851;?>:</span> <?php if ($pdateStarted) { echo $pdateStarted;} else {echo ALLSTATS_94;}?>
            <br><span class="statsLegend"><?php echo ALLSTATS_852;?>:</span> <?php if ($pdateCompleted) { echo $pdateCompleted;} else {echo ALLSTATS_94;}?>
            </div>
            <div style="margin-top:10px"><span class="statsLegend"><?php echo ALLSTATS_119.':</span> '.$pconfirmedIs;?><br />
            <span class="statsLegend"><?php echo ALLSTATS_120.':</span> '.$pprefersIs;?>
            </div>
        </td>
        <td width="20%" valign="top" style="BORDER-top: #c9c9c9 1px solid;BORDER-bottom: #c9c9c9  1px solid;">
            <div style="text-align:left;"><span class="statsLegend"><?php echo ALLSTATS_852;?>:</span>&nbsp;<?php if ($pcompleted=="-1") {echo ALLSTATS_93;} else {echo ALLSTATS_94;}?></div>
            <div style="text-align:left;"><span class="statsLegend"><?php echo ALLSTATS_81; ?>:</span>&nbsp;<?php echo $pcounter; ?></div>
        </td>
    </tr>
</table>
</div>