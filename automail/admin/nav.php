<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

/*
nav.php needs to know
$range=10   :	the pages/links to show before/after current page/we set this to what we like
$page;		:	the page we are at now: (isset($_GET['page']))?$page = $_GET['page']:$page = 1;
$rowsPerPage:	we set this to what we like. For subscribers: as a group setting but can also change via menu.
				(isset($_GET['records']))?$rowsPerPage = $_GET['records']:$rowsPerPage = $obj->getSetting("groupNumPerPage", $idGroup);
$numrows	:	total recs in the set
$maxPage	:	= ceil($numrows/$rowsPerPage);
$urlPaging	:	starts with $self and page-specific parameters are added
*/
if ($maxPage!=1) {
		echo '<div style="margin-bottom:14px;margin-top:5px;">'.GENERIC_19 . $page  . GENERIC_20 . $maxPage . '</div>';
		if ($page-$range >1 ) {
			$first = "<a class=inav2 href='$urlPaging&page=1'>".GENERIC_23."</a>&nbsp;";
		}
		else {
			$first = '';
		}
		if (($page-$range)==2) {
			$first = "<a class=inav2 href='$urlPaging&amp;page=1'>1</a>&nbsp;";
		}
		if ($page < $maxPage) {
			$last = "<a class=inav2 href='$urlPaging&amp;page=$maxPage'>".GENERIC_24."</a>&nbsp;";
		}
		else {
		   	$last = '&nbsp;';
		}
		echo '<div style="margin-top:10px;margin-bottom:10px;">'.$first;

		for($i=$page-$range; $i<=$page; $i++) {
			if ($page==$i) {
				echo "<span class=inav>$page</span>&nbsp;";
			}
			else {
				if ($i>=1) {
		  			echo "<a class=inav2 href='$urlPaging&amp;page=$i'>$i</a>&nbsp;";
				}
			}
		}
		for($i = $page; $i<=$page+$range; $i++) {
			if ($page!=$i) {
				echo "<a class=inav2 href='$urlPaging&amp;page=$i'>$i</a>&nbsp;" ;
			}
			if ($i==$maxPage) {$last='';break;}
		}
		echo $last.'</div>';
}?>