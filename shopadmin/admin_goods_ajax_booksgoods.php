<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Gid  = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');

$Where    =  "";
$Where    = $_GET['skey']!="" ?  " and ntitlte like '%".$_GET['skey']."%'" : $Where ;


$Sql      = "select * from `{$INFO[DBPrefix]}news` where 1=1 " .$Where." ";

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
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<div style="height:380px;overflow:auto">

      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>

        <FORM name=optForm method=get action="admin_goods_ajax_booksgoodslist.php" id="booksgoodsform">        
		<input type="hidden" name="Action" value="Search">		 
		<INPUT type=hidden name='Goodsname' value="<?php echo $Goodsname?>" >
		<INPUT type=hidden name='gid' value="<?php echo $Gid?>" >
        <TR>
          <TD align=center colSpan=2 height=31>
            <TABLE width="95%" border=0 align="center" cellPadding=0 cellSpacing=0 class=p12black>
              <TBODY>
              <TR>
                <TD width="258"   height=31 align=center nowrap class=p9black>文章標題&nbsp;
                  <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"   name='skey'>
				</TD>
                <TD width="66"  height=31 align="left" vAlign=center class=p9black>
		        &nbsp;&nbsp;&nbsp; <a href="#"><img src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 id="bookssearch"></a>
                </TD>
        	    <TD align="right" vAlign=center class=p9black>
				<input type="button" name="bookssave" id="bookssave" value="保存" />
                      <input type="button" name="cPic" id="cPic" value="返回" onclick="closeWin();" />			
				</TD>
        	    </TR>
	          </TBODY>
	        </TABLE></TD>
           
		 </TR>
	    </FORM>
  </TABLE>
                        <FORM name=adminForm action="admin_goods_ajax_booksgoodssave.php" method=post id="selectbooksgoodsform">
					<INPUT type=hidden name=act id="act" value="Save">
					<INPUT type=hidden name='Goodsname' id='Goodsname' value="<?php echo $Goodsname?>" >
					<INPUT type=hidden name='gid' id='gid' value="<?php echo $Gid?>" >
					 <INPUT type=hidden value=0  name=boxchecked> 
	<div id="goodsbookslist">

                  <TABLE   width="95%" border=0 align="center" cellPadding=0 cellSpacing=0 class=listtable>


                    <TBODY>
                    <TR align=middle>
                      <TD width="3%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                      </TD>
                      <TD width="97%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>文章標題</TD>
                      </TR>
					<?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

					?>
                    <TR class=row0>
                      <TD align=middle  height=20>
					  <INPUT id='cb<?php echo $i?>'  type=checkbox value='<?php echo $Rs['news_id']?>' name=cid[]> 
					  </TD>
					  <TD  height=20 align=left nowrap> <?php echo $Rs['ntitle']?></TD> 
                      </TR>
					<?php
					$i++;
					}
					?>
					 
      </TABLE>
                     <?php if ($Num>0){ ?>
			<table class=p9gray cellspacing=0 cellpadding=0 width="100%"    border=0>
              <tbody>
                <tr>
                  <td valign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23><?php echo $Nav->pagenav(1,"admin_goods_ajax_booksgoods.php")?> </td>
                </tr>
                
      </table><?php } ?>
                     </div>
                     </FORM>

</div>		

<script>
$(document).ready(function() {
var options = {
		success:       function(msg){
						$("#goodsbookslist").html(msg);
						//alert(msg);
						
					},
		type:      'get',
		dataType:  'html',
		clearForm: true
	};
var options11 = {
		success:       function(msg){
						if (msg=="1"){
								closeWin();
								showtajaxfun('books');
							}
						
					},
		type:      'post',
		dataType:  'json',
		clearForm: true
	};
$("#bookssearch").click(function(){
	//alert("c");
					   //$("#linkgoodsform").attr("action","admin_goods_ajax_linkgoodslist.php");
					  // $("#act").attr("value","Search");
					  // alert($("#linkgoodsform").attr("action"));
					   $("#booksgoodsform").ajaxSubmit(options);
								});
$("#bookssave").click(function(){
	$("#selectbooksgoodsform").ajaxSubmit(options11);});
});
</script>