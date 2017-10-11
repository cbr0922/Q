<?php

include_once "Check_Admin.php";

include      "../language/".$INFO['IS']."/ProductVisit_Pack.php";

//$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y-m-d",time()) ;

$Begtime = $_GET['begtime']!="" ? $_GET['begtime'] : date("Y",time())."-01-01" ;

$Endtime = $_GET['endtime']!="" ? $_GET['endtime'] : date("Y-m-d",time()) ;



include_once "pagenav_stard.php";

include RootDocumentShare."/cache/Productclass_show.php";

$objClass = "9pv";

$Nav      = new buildNav($DB,$objClass);





switch (trim($_GET[act])){



case "viewpoint":

    $title   =$ProductVisit_Packs[PointSort];//點閱數排行

	$Sql     = " SELECT view_num , gid, goodsname FROM `{$INFO[DBPrefix]}goods` order by view_num desc  ";

break;



default:

    $title   =$ProductVisit_Packs[PointSort];//點閱數排行

	$Sql     = " SELECT view_num , gid, goodsname FROM `{$INFO[DBPrefix]}goods` where 1=1";

break;



}

$Where='';
if($_GET['top_id'] != 0 && isset($_GET['top_id'])){
	$Next_ArrayClass  = $FUNCTIONS->Sun_pcon_class(intval($_GET[top_id]));
	$Next_ArrayClass  = array_filter(explode(",",$Next_ArrayClass));
	$Array_class      = array_unique($Next_ArrayClass);
	//print_r($Array_class);
	if (count($Array_class)>0){
		$top_ids = intval($_GET['top_id']).",".implode(",",$Array_class);
		$Where .= " AND bid in (".$top_ids.")";
	}else{
		$top_ids = intval($_GET['top_id']);
		$Where .= " AND bid=".$top_ids;
	}	
}

$Sql      = $Sql.$Where." group by gid order by view_num desc";

$Query    = $DB->query($Sql);

$Num      = $DB->num_rows($Query);

if ($Num>0){

	$limit = $_GET['listnum']!="" ? intval($_GET['listnum']) : 20  ;

	$Nav->total_result=$Num;

	$Nav->execute($Sql,$limit);

	$Query = $Nav->sql_result;

	$Nums     = $Num<$limit ? $Num : $limit ;

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<HEAD>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<TITLE>

<?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[Visit];//訪問統計?>--&gt;<?php echo $title ?>

</TITLE>

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">

<?php include_once "head.php";?>

<div id="contain_out">

<?php  include_once "Order_state.php";?>

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>

        <TBODY>

          <TR>

            <TD width="50%">

              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>

                <TBODY>

                  <TR>

                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"   width=32></TD>

                    <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--><?php echo $JsMenu[TjFx];//统计分析?>--><?php echo $JsMenu[ProductVisit];//商品統計?>--&gt;<?php echo $title ?></SPAN></TD>

                </TR></TBODY></TABLE></TD>
                
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>  

        <FORM name=searchbanner method=get action="">                
           <TR>
<TD height=39 align=left nowrap>
<table width="300" border="0" cellspacing="0" cellpadding="0">
          <TR>

            <TD height="30" align=left class=p9black>商品分類：</TD>

            <TD height="0" colspan="2" align=left class=p9black>
            	<?php echo $Char_class->get_page_select("top_id",$_GET['top_id'],"  class=\"trans-input\" onchange=document.searchbanner.submit(); ");?> 
            </TD>          
         </TR>
</table>
</TD>
<TD width="14%" align="right" vAlign=center nowrap class=p9black><?php echo $Basic_Command['PerPageDisplay'];//每页显示?> 

                      <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.searchbanner.submit(); ",$Array=array('2','10','15','20','30','50','100'))?></TD>
          </TR>
          </FORM>
        </TABLE>
        
			</TR>
          </TBODY>

        </TABLE><TABLE cellSpacing=0 cellPadding=0 width="100%" align=center class="allborder">

                        <TBODY>

                          <TR align="center">

                            <TD height="300" valign="top">

                              <TABLE class=listtable cellSpacing=0 cellPadding=0 width="100%" border=0 id="orderedlist">

                                <TBODY>

                                  <TR align=middle class=row1>

                                    <TD width="100"  align=center noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black> <?php echo $Basic_Command['SNo_say']?></TD>

                                    <TD width="530" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $ProductVisit_Packs[Product_Name] ?></TD>

                                    <TD width="100" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black style1> <?php echo $ProductVisit_Packs[PointSort] ?></TD>

                                  </TR>

                                  <?php

	             			if ($Num>0){

	             				$i=1;

	             				$TotalNum = 0;

	             				while ($Result = $DB->fetch_array($Query)){

						    ?>

                                  <TR class=row0>

                                    <TD width="100" height=20 align=center><?php echo $i?></TD>

                                    <TD height=20 align="left" noWrap><?php echo $Result['goodsname'];?></TD>

                                    <TD height=20 align="left" noWrap><?php echo $Result['view_num'];?>&nbsp;&nbsp;&nbsp;</TD>

                                  </TR>

                                  <?php

   						    $TotalNum = $TotalNum+$Result['totalvisit'];

   						    $i++;

	             				}

						    ?>

                                  <?php

	             			}else{

						    ?>

                                  <TR class=row1>

                                    <TD height=20 colspan="3" align=center class="p9orange"><?php echo $Visit_Packs[NoVisit_Say];//没有参与统计的资料?></TD>

                                  </TR>

                                  <?php

	             			}

						    ?>

                              </TABLE>

                              <?php if ($Num>0){ ?>

                              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>

                                <TBODY>

                                  <TR>

                                    <TD vAlign=center align=right background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>

                                      <?php echo $Nav->pagenav()?>

                                    </TD>

                                  </TR>

                              </TABLE>

                              <?php } ?>

                            </TD>

                            </TR>

                        </TBODY></TABLE>

</div>

<div align="center"><?php include_once "botto.php";?></div>

</BODY>

</HTML>

