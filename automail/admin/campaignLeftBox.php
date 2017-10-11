<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>
<div style="float:left"><span class="statsLegendEmph"><?php echo ALLSTATS_88?>&nbsp;<?php echo  $pidCampaign?></span></div>
<div style="float:right;padding-right:7px"><?php schedulerFlag($pidCampaign, $idGroup); echo '</div><div style="clear:both"></div>';
if ($pcampaignName) {echo '<div style="margin-top:7px"><span class="statsLegend">'.CAMPAIGNCREATE_54.': </span>'.$pcampaignName.'</div>';}
if ($pfromName OR $pfromEmail OR $preplyToEmail) {
	echo '<div style="margin-top:7px;border-left: #4E4F6A 2px solid">';
	if ($pfromName) {echo '<div style="margin-top:0px;margin-left:5px"><span class="statsLegend">'.CAMPAIGNCREATE_58.': </span>'.$pfromName.'</div>';}
	if ($pfromEmail) {echo '<div style="margin-top:0px;margin-left:5px"><span class="statsLegend">'.CAMPAIGNCREATE_59.': </span>'.$pfromEmail.'</div>';}
	if ($preplyToEmail) {echo '<div style="margin-top:0px;margin-left:5px"><span class="statsLegend">'.CAMPAIGNCREATE_60.': </span>'.$preplyToEmail.'</div>';}
 	echo '</div>';
 }?>
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
