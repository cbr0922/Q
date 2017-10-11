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
$Where    = $_GET['skey']!="" ?  " and goodsname like '%".$_GET['skey']."%'" : $Where ;

$s = $Where." and  gid!=".$Gid." ";
$Sql      = "select * from `{$INFO[DBPrefix]}goods`  where 1=1 ".$s." order by goodorder ";

$Query    = $DB->query($Sql);
$Num      = $DB->num_rows($Query);

?>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<script>
function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3)
    if ((obj=MM_findObj(args[i]))!=null) {
	  v=args[i+2];
      if (obj.style) {
	    obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
      obj.visibility=v;
	}
}
</script>
<div style="height:400px;overflow:auto">
<FORM name=optForm method=get action="admin_goods_ajax_packgoodssearch.php" id="packgoodsform">        
		<input type="hidden" name="Action" value="Search">		 
		<INPUT type=hidden name='Goodsname' value="<?php echo $Goodsname?>" >
		<INPUT type=hidden name='gid' value="<?php echo $Gid?>" >
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>

        
        <TR>
          <TD align=center colSpan=2 height=31>
            <TABLE width="95%" border=0 align="center" cellPadding=0 cellSpacing=0 class=p12black>
              <TBODY>
              <TR>
                <TD width="258"   height=31 align=center nowrap  class=p9black><?php echo $Admin_Product[PleaseInputPrductName];//請輸入商品名稱?>&nbsp;
                 <INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"   name='skey'>
				</TD>
                <TD width="66"  height=31 align="left" vAlign=center class=p9black>
		        &nbsp;&nbsp;&nbsp; <a href="#"><img src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 id="packsearch"></a>
                </TD>
        	    <TD align="right" vAlign=center class=p9black>
				<input type="button" name="packsave" id="packsave" value="保存" />
                      <input type="button" name="cPic" id="cPic" value="返回" onclick="closeWin();" />			
				</TD>
        	    </TR>
	          </TBODY>
	        </TABLE></TD>
           
		 </TR>
		 
	</TABLE></FORM>
	<div id="goodspacklist">
<FORM name=adminForm action="admin_goods_ajax_packgoodssave.php" method=post id="selectpackgoodsform">
					<INPUT type=hidden name=actpack id="actpack" value="Save">
					<INPUT type=hidden name='Goodsname' id='Goodsname' value="<?php echo $Goodsname?>" >
					<INPUT type=hidden name='gid' id='gid' value="<?php echo $Gid?>" >
					 <INPUT type=hidden value=0  name=boxchecked> 
                  <TABLE   width="95%" border=0 align="center" cellPadding=0 cellSpacing=0 class=listtable>

                    
                    <TBODY>
                    <TR align=middle>
                      <TD width="10%" height=26 align=middle noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
				      </TD>
                      <TD width="10%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductSmPic];//缩图?>
					  </TD>
                      <TD width="15%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>
                      <TD width="49%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductName];//名称?>					  </TD>
                      <TD width="16%"  height=26 colspan="8" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>成本			  </TD>
                      </TR>
					<?php               
					$i=0;

					while ($Rs=$DB->fetch_array($Query)) {

						$Yes = "images/".$INFO[IS]."/publish_g.png";
						$No  = "images/".$INFO[IS]."/publish_x.png";
						$ifpub_pic  = $Rs['ifpub']==1  ? $Yes : $No ;
						$ifrmd_pic  = $Rs['ifrecommend']==1  ? $Yes : $No ;
						$ifspec_pic = $Rs['ifspecial']==1  ? $Yes : $No ;
						$ifhot_pic  = $Rs['ifhot']==1  ? $Yes : $No ;

					?>
                    <TR class=row0>
                      <TD align=middle  height=20>
					  <INPUT id='cb<?php echo $i?>'  type=checkbox value='<?php echo $Rs['gid']?>' name=cid[]> 
					  </TD>
					  <TD align=left  height=20><IMG onMouseOver="MM_showHideLayers('imgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('imgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/icon-viewpic.gif" width=18>
                        <DIV class=shadow id=imgLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 135px; POSITION: absolute; HEIGHT: 135px"   border="1"><IMG src="../<?php echo $INFO['good_pic_path']?>/<?php echo $Rs['smallimg']?>" ></DIV>
					  </TD>
                      <TD height=20 align="left" noWrap><?php echo $Rs['bn']?> &nbsp;</TD>
                      <TD  height=20 align=left nowrap> <?php echo $Rs['goodsname']?></TD> 
                      <TD height=20 colspan="8" align=left nowrap><?php echo $Rs['cost']?><INPUT id='cost<?php echo $i?>'  type=hidden value='<?php echo $Rs['cost']?>' name=cost[]></TD>
                      </TR>
					<?php
					$i++;
					}
					?>
				    
	  </TABLE></FORM>
  </div>

	</div>		

<script>
$(document).ready(function() {
var options_ps = {
		success:       function(msg){
						$("#goodspacklist").html(msg);
						//alert(msg);
						
					},
		type:      'get',
		dataType:  'html',
		clearForm: true
	};
var options_p = {
		success:       function(msg){
						if (msg=="1"){
								closeWin();
								showtajaxfun('packgoods');
							}
						
					},
		type:      'post',
		dataType:  'json',
		clearForm: true
	};
$("#packsearch").click(function(){
					   //$("#linkgoodsform").attr("action","admin_goods_ajax_linkgoodslist.php");
					  // $("#act").attr("value","Search");
					  // alert($("#linkgoodsform").attr("action"));
					   $("#packgoodsform").ajaxSubmit(options_ps);
								});
$("#packsave").click(function(){$("#selectpackgoodsform").ajaxSubmit(options_p);});
});
</script>