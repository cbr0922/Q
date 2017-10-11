<?php
include_once "Check_Admin.php";
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";

$Gid         = $FUNCTIONS->Value_Manage($_GET['gid'],$_POST['gid'],'back','');

$Sql         = "select gl.* ,g.goodsname,g.bn,g.price as gprice from `{$INFO[DBPrefix]}good_link` gl  inner join `{$INFO[DBPrefix]}goods`  g on (gl.s_gid=g.gid) where gl.p_gid=".intval($Gid)." order by gl.idate desc ";
$Query       = $DB->query($Sql);
$Num         = $DB->num_rows($Query);



//更新资料!
if ($_POST['act']=="Save"){
	$Goodlinkid   = $_POST['good_link_id'];
	$S_gid        = $_POST['S_gid'];
	$Allid        = $_POST['Allid'];
	$Zk_price     = $_POST['zk_price'];

	if (count($Goodlinkid)>0) {                   //如果确实有资料提交!
		for ($i=0;$i<count($Allid);$i++){           //将提交的所有资料的ID做大循环
			foreach($Goodlinkid as $k => $v){         //循环提交的将要被操作的资料
				if ($v == $Allid[$i]){                    //如果提交的记录中ID与大循环ID相同的时候
					$link_type = $_POST["link_type".$i];      //这里就能获得提交记录中的类型的值了

					$Update_sql = " update `{$INFO[DBPrefix]}good_link` set zk_price='".intval($Zk_price[$i])."' , link_type='".intval($link_type)."' where good_link_id='".intval($Allid[$i])."' ";
					$DB->query($Update_sql);  //首先将本条记录更新


					switch ($link_type){  // 根据值来判断是交叉还是提升销售
						case 0:   //如果就是默认的提升销售

						break;
						case 1:  //如果是交叉销售,这里就要首先SCAN good_link 表 判断是插入还是UPDATE操作了!!
						$Query = $DB->query("select good_link_id from `{$INFO[DBPrefix]}good_link` where s_gid=".intval($Gid)." and p_gid=".intval($S_gid[$i])." order by good_link_id desc limit 0,1"); //查看有没有主是被选产品,次是本类产品记录!
						$Num   = $DB->num_rows($Query);
						if ($Num==0){  // 如果没有记录,就插入一条记录到数据库
							$DB->query(" insert into `{$INFO[DBPrefix]}good_link` (p_gid,s_gid,link_type) values('".intval($S_gid[$i])."','".intval($Gid)."','1')");
						}
						break;

					}
					// 注意,这里应该对提交类型还要做对应的处理,现在还没有


					break 1;
				}
			}
		}
	}
	$FUNCTIONS->sorry_back("admin_linkgoods.php?gid=$Gid&Goodsname=".trim($Goodsname)."","");
}
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
<script>
var optionslink = {
		success:       function(msg){
						if (msg=="1"){
								closeWin();
								showtajaxfun('goodslink');
							}
					},
		type:      'post',
		dataType:  'json',
		clearForm: true
	};
</script>
                  <TABLE class=allborder cellSpacing=3 cellPadding=3
                  width="100%" bgColor=#f7f7f7 border=0>
                    <TBODY>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD>&nbsp;</TD></TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>

                      <TD width="84%" nowrap><TABLE border="0" id="selectlink">
                        <TBODY>
                          <TR>
                            <TD vAlign=middle noWrap class="link_buttom">
							  <a href="javascript:showWin('url','admin_goods_ajax_linkgoods.php?gid=<?=intval($Gid)?>','',750,450);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-relatedpro.gif" border="0" align="absmiddle" class="fbottonnew" />
	                          <?php echo $Admin_Product[PleaseSelectAboutPrduct];//请选择相关商品?></a></TD>
                          </TR>
                        </TBODY>
                      </TABLE></TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right width="12%"><?php echo $Admin_Product[MCJS_Title];//名词解释?>：</TD>
                      <TD><span class="unnamed1 style3"><?php echo $Admin_Product[MCJS_Title_one];//提升销售 （单向相关）?></span>：
					  <?php echo $Admin_Product[MCJS_Title_One_Content_I];?><?php echo $Admin_Product[MCJS_Title_One_Content_II];?>
					  </TD>
                    </TR>
                    <TR>
                      <TD noWrap align=right>&nbsp;</TD>
                      <TD><span class="unnamed1 style3"><?php echo $Admin_Product[MCJS_Title_two];//交叉销售 （交叉相关）?></span>：
					  <?php echo $Admin_Product[MCJS_Title_Two_Content_I];?><?php echo $Admin_Product[MCJS_Title_Two_Content_II];?>
					  </TD>
                    </TR>

                    <TR>
                      <TD noWrap align=right width="12%"> <?php echo $Admin_Product[AboutProductNum];//相關商品件數?>：</TD>
                      <TD><?php echo  $Num ?></TD>
                    </TR>
                      <TR align="center">
                      <TD valign="top" noWrap></TD>
                      <TD align="left" valign="top" noWrap><input type="button" name="button" id="dellink" value="刪除" onclick=' $("#actlink").attr("value","Del");$("#dellinkform").ajaxSubmit(optionslink);' />
                        <input type="button" name="savebut" id="savebut" value="保存" onclick=' $("#actlink").attr("value","Save");$("#dellinkform").ajaxSubmit(optionslink);' /></TD>
                      </TR>
                    <TR align="center">
                      <TD colspan="2" valign="top" noWrap>
<!--  start    -->
 <FORM name=adminForm action="admin_goods_ajax_linkgoodssave.php" method=post id="dellinkform">
   <INPUT type=hidden name=actlink id=actlink value="Del">
   <INPUT type=hidden value=0  name=boxchecked>
   <INPUT type=hidden  name='gid' value="<?php echo $Gid?>">
   <INPUT type=hidden  name='Goodsname' value="<?php echo $Goodsname?>">
<TABLE class=allborder cellSpacing=1 cellPadding=0  width="95%" bgColor=#666666 border=0>
    <TBODY>
     <TR bgColor=#e7e7e7 height=25>
       <TD noWrap align=left width="10%"    height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Basic_Command['SNo_say'];//序号?></TD>
       <TD noWrap align=center width="12%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[Bn];//貨號?></TD>
       <TD noWrap align=middle width="36%"  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[ProductName];//商品名称?></TD>
       <TD noWrap align=center  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[ProductPrice];//商品价格?></TD>
       <TD noWrap align=center  height=17 bgColor=#e7e7e7>&nbsp;<?php echo $Admin_Product[AboutType] ;//相关类型?>&nbsp;&nbsp;&nbsp;</TD>
	   </TR>
	<?php
	$i=0;
	$j=1;
	while ($Result    = $DB->fetch_array($Query)) {
	?>
     <TR valign="top" bgColor=#f7f7f7>
 	   <TD align="left" valign="middle" class=unnamed1><INPUT id='cb<?php echo $i?>' type=checkbox value='<?php echo intval($Result['good_link_id'])?>' name='good_link_id[]'>&nbsp;<?php echo $j?><INPUT type=hidden value="<?php echo $Result['good_link_id']?>" name="Allid[]"><INPUT type=hidden value="<?php echo $Result['s_gid']?>" name="S_gid[]"></TD>
       <TD align="center" valign="middle" class=unnamed1>&nbsp;<?php echo $Result['bn']?></TD>
	   <TD valign="middle" class=unnamed1>&nbsp;<?php echo $Result['goodsname']?></TD>
	   <TD align="center" valign="middle" class=unnamed1>&nbsp;<?php echo $Result['gprice']?></TD>
	   <TD align="center" valign="middle" nowrap class=unnamed1>&nbsp;<?php echo $FUNCTIONS->Input_Box("radio_strand","link_type".$i,intval($Result['link_type']),$add=array($Admin_Product[MCJS_Up_Sale],$Admin_Product[MCJS_Cross_Sale]));?>       </TD>
	   </TR>
	<?php
	$j++;
	$i++;
	}
	?>
  </TBODY>
</TABLE>
</FORM>
 <!--   end    -->					   </TD>
                      </TR>
                    <TR>
                      <TD noWrap align=right width="12%">&nbsp;</TD>
                      <TD>&nbsp;            </TD></TR></TBODY></TABLE>
