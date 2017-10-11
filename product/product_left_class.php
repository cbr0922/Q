<?
	  $Sql_bclass    = "select * from `{$INFO[DBPrefix]}bclass` where catiffb=1 and top_id=".$TopId." order by bid asc ";
	  $query_bclass  = $DB->query($Sql_bclass); 	
	  $num_bclass    = $DB->num_rows($query_bclass);
	  if ($num_bclass>0) {
?>
<TABLE class=p9black cellSpacing=0 cellPadding=2 width="100%" border=0>
  <TBODY>
    <TR>
      <TD height="33" background='../images/newgoods-bg.gif'>	  <SPAN class=p12co><?=$TPL_TAGS["ttag_1890_1"];//商品子分类?></SPAN></TD>
    </TR>
    <TR>
      <TD align=middle>
	  <?
	  $i=0;  
	  while ($Rs_bclass =  $DB->fetch_array($query_bclass)){
	  ?>
        <TABLE class=p9black cellSpacing=0 cellPadding=0 width="98%" border=0>
          <TBODY>
            <TR>
              <TD height="22" class=dotX>
			  <A class=news  href="product_class_detail.php?bid=<?=$Rs_bclass['bid']?>"><?=$Rs_bclass['catname']?></A></TD>
            </TR>
            <TR>
              <TD align=left vAlign=top>
			    &nbsp;&nbsp;
		      <?=$FUNCTIONS->Nav_nextClass($Rs_bclass['bid']); ?>			  </TD>
            </TR>
          <TBODY>
          </TBODY>
      </TABLE>
	  <?
	  $i++;
	   }
	  ?> 
	  </TD>
    </TR>
  </TBODY>
</TABLE>
<? } ?>