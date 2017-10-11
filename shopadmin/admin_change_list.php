<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include_once Classes . "/pagenav_stard.php";
include "../language/".$INFO['IS']."/StaticHtml_Pack.php";
$objClass = "9pv";
$Nav      = new buildNav($DB,$objClass);

$Where    = intval($_GET['bid'])!="" ? "  and g.bid=".intval($_GET['bid'])." " : ""  ;
$Where    = $_GET['skey']!=""  && trim(urldecode($_GET['skey']))!=$Admin_Product[PleaseInputPrductName] ?  " and  g.goodsname like '%".trim(urldecode($_GET['skey']))."%'" : $Where ;


$Sql      = "select  g.*,bc.catname from `{$INFO[DBPrefix]}goods` g  left join `{$INFO[DBPrefix]}bclass`  bc  on (g.bid=bc.bid)  where g.ifchange=1  ".$Where." ";

//$Sql      = $_GET['alarm_recsts']=='DO' ? " select * from goods where ifalarm=1 and alarmnum>=storage  and ifbonus=1 " : $Sql ;
$Sql      = $Sql."  order by g.idate desc , g.goodorder desc ";

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
<TITLE><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Product_Man];//商品管理?>--&gt;額滿禮商品</TITLE>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
<?php include_once "head.php";?>
<SCRIPT language=javascript>
/*
function toComment(id){
	var checkvalue;
	
	if (id == 0) {
		checkvalue = isSelected(<?php echo intval($Nums)?>);
	}else{
		checkvalue = id;
	}
	
	if (checkvalue!=false){
		document.adminForm.action = "admin_comment_list.php?goodsid="+checkvalue;
		document.adminForm.submit();
	}
}
*/
function toEdit(id,catid){
	var checkvalue;
	var catvalue = "";
	
	if (id == 0) {
		checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	}else{
		checkvalue = id;
	}
		
	if (catid != 0) {
		catvalue = "&scat="+catid;
	}
	
	if (checkvalue!=false){		
		document.adminForm.action = "admin_goods.php?Action=Modi&gid="+checkvalue;
    	document.adminForm.act.value="";
   	    document.adminForm.Where.value="Bouns";		
		document.adminForm.submit();
	}
}

function toDel(){
	var checkvalue;
	checkvalue = isSelected('<?php echo intval($Nums)?>','<?php echo $Basic_Command['No_Select']?>');
	if (checkvalue!=false){
		if (confirm('<?php echo $Basic_Command['Del_Select']?>')){
			document.adminForm.action = "admin_goods_save.php";
			document.adminForm.act.value="Del";
			document.adminForm.submit();
		}
	}
}

function toSave(){ alert("[deprecated] admin_goods_list_save.php"); }
</SCRIPT>

<SCRIPT language=JavaScript>
<!--
function MM_findObj(n, d) { //v4.0
  var p,i,x;
  if(!d) d=document;
  if((p=n.indexOf("?"))>0 && parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n];
  for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n);
  return x;
}

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
//-->
</SCRIPT>
<div id="contain_out">
  <?php  include_once "Order_state.php";?>

      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" width=32></TD>
                    <TD class=p12black noWrap><SPAN class=p9orange><?php echo $JsMenu[Product_Man];//商品管理?>--&gt;加購所屬商品</SPAN></TD>
                </TR></TBODY></TABLE></TD>
            <TD align=right width="50%">
              <TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  
                  <TR>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="admin_goods.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-new.gif"  border=0>&nbsp;<?php echo $Admin_Product[CreateProduct];//新增?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toEdit(0);"><IMG  src="images/<?php echo $INFO[IS]?>/fb-edit.gif"   border=0>&nbsp;<?php echo $Basic_Command['Edit'];//编辑?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <TD align=middle>
                      <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                        <TBODY>
                          <TR>
                            <TD align=middle width=79><!--BUTTON_BEGIN-->
                              <TABLE>
                                <TBODY>
                                  <TR>
                            <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:toDel();"><IMG src="images/<?php echo $INFO[IS]?>/fb-delete.gif"   border=0>&nbsp;<?php echo $Basic_Command['Del'];//删除?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                    <!--TD align=middle>
                  <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                    <TBODY>
                    <TR>
                      <TD align=middle width=79><!--BUTTON_BEGIN
                        <TABLE>
                          <TBODY>
                          <TR>
                            <TD vAlign=bottom noWrap><a href="javascript:toSave();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif"  border=0>&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD></TR></TBODY></TABLE><!--BUTTON_END</TD></TR></TBODY></TABLE></TD-->			   
                  </TR>			   
                </TBODY>
              </TABLE>
            </TD>
          </TR>
        </TBODY>
  </TABLE>
      
      <TABLE class=p12black cellSpacing=0 cellPadding=0 width="100%"   align=center border=0>
        <FORM name=optForm method=get action="">        
          <input type="hidden" name="Action" value="Search">
          <TR>
            <TD align=right colSpan=2 height=31>
              <TABLE border=0 align="left" cellPadding=0 cellSpacing=0 class=p12black>
                <TBODY>
                  <TR>
                    <TD height=31 align=left>
                      <INPUT  name='skey'  onfocus=this.select()  onclick="if(this.value=='<?php echo $Admin_Product[PleaseInputPrductName]?>')this.value=''"  onmouseover=this.focus() value='<?php echo $Admin_Product[PleaseInputPrductName]?>' size="40">		&nbsp;&nbsp;	    
                      <INPUT type=image src="images/<?php echo $INFO[IS]?>/t_go.gif" border=0 name=imageField align="absmiddle">                </TD>
                  </TR>
                </TBODY>
              </TABLE>
            </TD>
            <TD class=p9black align=right height=31><?php echo $Basic_Command['PerPageDisplay'];//每页显示?> 
              <?php echo $FUNCTIONS->select_Cyc_array("listnum",$limit,"  class=\"trans-input\" onchange=document.optForm.submit(); ",$Array=array('2','10','15','20','30','50','100'))?>		  </TD>
          </TR>
        </FORM>
  </TABLE>
      
      
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="allborder">
        <TBODY>
          <TR>
            <TD vAlign=top height=210>
              <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD bgColor=#ffffff>
                      <div class="input02" id="show_Content" style="display:none">&nbsp;</div>
                      <TABLE class=listtable cellSpacing=0 cellPadding=0   width="100%" border=0 id="orderedlist">
                        <FORM name=adminForm action='' method=post>
                          <INPUT type=hidden name=act>
                          <INPUT type=hidden name=Where>
                          <input type=hidden name=doaction >		
                          <INPUT type=hidden value=0  name=boxchecked> 
                          <TBODY>
                            <TR align=middle>
                              <TD width="6%" height=26 align=left noWrap  background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <INPUT onclick=checkAll('<?php echo intval($Nums)?>'); type=checkbox value=checkbox   name=toggle></TD>
                              <TD width="11%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[Bn];//货号?></TD>
                              <TD width="24%"  height=26 align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black>
                                <?php echo $Admin_Product[PrductClassName];//商品類別名稱?>					  </TD>
                              <TD width="34%" align="left" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductName];//商品名稱?>&nbsp;</TD>
                              <TD width="6%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[ProductSmPic];//缩图?><BR></TD>
                              <TD width="8%"  height=26 background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[pub];//发布?><BR></TD>
                              <TD width="11%"  height=26 align="center" noWrap background=images/<?php echo $INFO[IS]?>/bartop.gif class=p9black><?php echo $Admin_Product[storage];//库存?><BR></TD>
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
                              <TD align=left height=26><INPUT id='cb<?php echo $i?>'  onclick=isChecked(this); type=checkbox value='<?php echo $Rs['gid']?>' name=cid[]> 
                                <!--INPUT type=hidden value='<?php echo $Rs['gid']?>' name=Ci[]--></TD>
                              <TD height=26 align="left" noWrap>
                                <div id='bn<?php echo $i;?>'><a onClick="ChangeBnInnerHtml('bn<?php echo $i;?>','<?php echo $Rs['bn']?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Rs['bn']?></a></div>					  </TD>
                              <TD height=26 align=left nowrap>
                                <div id='Bcatname<?php echo $i;?>'><a onClick="ChangeBcatnameInnerHtml('Bcatname<?php echo $i;?>','<?php echo trim($Rs['bid'])?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Broot = $Rs['catname']!="" ? $Rs['catname'] : "├─/";?></a></div>                      </TD>
                              <TD height=26 align=left nowrap><A href="javascript:toEdit('<?php echo $Rs['gid']?>',0)"><?php echo $Rs['goodsname']?></A>&nbsp;</TD>
                              <TD align=middle height=26><IMG onMouseOver="MM_showHideLayers('imgLayer<?php echo $i?>','','show')" onMouseOut="MM_showHideLayers('imgLayer<?php echo $i?>','','hide')" height=18 src="images/<?php echo $INFO[IS]?>/icon-viewpic.gif" width=18>
                                <DIV class=shadow id=imgLayer<?php echo $i?> style="Z-INDEX: 3; VISIBILITY: hidden; WIDTH: 63px; POSITION: absolute; HEIGHT: 67px"   border="1"><IMG src="../<?php echo $INFO['good_pic_path']?>/<?php echo $Rs['smallimg']?>" ></DIV>					  </TD>
                              <TD align=middle height=26>
                                <div id='ifPub<?php echo $i;?>'><a onClick="ChangeIfPubInnerHtml('ifPub<?php echo $i;?>','<?php echo $Rs['ifpub']?>',<?php echo intval($Rs['gid'])?>)" ><img src="<?php echo $ifpub_pic?>" border="0" /></a></div>                      </TD>
                              <TD align=center height=26>
                                <div id='kc<?php echo $i;?>'><a onClick="ChangeStorageInnerHtml('kc<?php echo $i;?>','<?php echo $Rs['storage']?>',<?php echo intval($Rs['gid'])?>)" ><?php echo $Rs['storage']?></a></div>
                                <!--INPUT  class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"    size=5 value='<?php echo $Rs['storage']?>' name=storage[]--></TD>
                              </TR>
                            <?php
					$i++;
					}
					?>
                            </FORM>
                        </TABLE>
                      </TD>
                    </TR>
                  </TABLE>
              <?php
            if ($Num>0){
			?>
              <TABLE class=p9gray cellSpacing=0 cellPadding=0 width="100%"    border=0>
                
                <TBODY>
                  <TR>
                    <TD vAlign=center align=middle background=images/<?php echo $INFO[IS]?>/03_content_backgr.png height=23>
                      <?php echo $Nav->pagenav()?>
                    </TD>
                  </TR>
                  <?php
            }
			?>
                  
              </TABLE>
            </TD></TR>
  </TABLE>
</div>
</BODY></HTML>

<!-------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
/**
 *   引入AJAX
 */
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
echo $InitAjax;
?>
 <script language="javascript">

 function AjaxGetRequest(url,show){
 	if (typeof(url) == 'undefined'){
 		    return false;
 	}

 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
 	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
 	ajax.onreadystatechange = function() {
 		//alert ('d');
 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		    if (ajax.readyState == 4 && ajax.status == 200) {
 		    	show_Content.style.display="";
 		    	show.innerHTML = "<?php echo $StaticHtml_Pack[CreatedOneHtml].':' ;?>" + ajax.responseText;  //'<img src="images/ok_16.gif"  border="0" />';
 		    }
 		    /*
 		    else{
 		    show.innerHTML = "<?php //echo $Admin_Product[CreatedOneHtml].':' ;?>" + '<img src="images/<?php //echo $INFO[IS]?>/publish_x.png"  border="0" />';
 		    }
 		    */
 		            		      
 		    }

 		    ajax.send(null);
 		    }
</script>



<script language="javascript">
/**
* ajax 改变值状态 产品网络价格
*/
function ChangePriceDescInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT name='pricedesc[]' type='text'  size='6' value="+OldValue+" onblur=changePriceDescInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}

function changePriceDescInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updatePricedesc&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'PriceDesc');

}


/**
* ajax 改变值状态 产品库存量
*/

function ChangeStorageInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT name='storage[]' type='text'  size='6' value="+OldValue+" onblur=changeStorageInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}

function changeStorageInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updateStorage&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'Storage');
}


/**
* ajax 改变值状态 产品顺序
*/

function ChangeSortInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT name='order[]' type='text'  size='4' value="+OldValue+" onblur=changeSortInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}

function changeSortInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updateSort&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'Sort');
}


/**
* ajax 改变值状态 产品货号
*/

function ChangeBnInnerHtml(Element,OldValue,Id){
	var txt;
	txt = "<INPUT name='bn[]' type='text'  size='8' value="+OldValue+" onblur=changeBnInnerHtmlOnblur('"+Element+"',this.value,'"+Id+"')>";
	var show = document.getElementById(Element);
	show.innerHTML =  txt;
}

function changeBnInnerHtmlOnblur(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updateBn&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'Bn');
}

/**
* ajax 改变值状态 产品是否发布
*/

function ChangeIfPubInnerHtml(Element,OldValue,Id){
	var url    = "./ajax_updateProduct.php?Type=updateIfPub&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ifPub');
}


/**
* ajax 改变值状态 产品是否推荐
*/

function ChangeifRmbInnerHtml(Element,OldValue,Id){
	var url    = "./ajax_updateProduct.php?Type=updateifRmb&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ifRmb');
}

/**
* ajax 改变值状态 产品是否特价
*/

function ChangeifSpecialInnerHtml(Element,OldValue,Id){
	var url    = "./ajax_updateProduct.php?Type=updateifSpecial&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ifSpecial');
}


/**
* ajax 改变值状态 产品是否热卖
*/

function ChangeifHotInnerHtml(Element,OldValue,Id){
	var url    = "./ajax_updateProduct.php?Type=updateifHot&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ifHot');
}




/**
* ajax 改变值状态 所归属的产品类
*/

function ChangeBcatnameInnerHtml(Element,OldValue,Id){

	var url    = "./ajax_updateProduct.php?Type=updateBcatname&action=update&Element="+Element+"&Id="+Id+"&Value="+OldValue;
	AjaxGetRequestInnerHtml(url,Id,Element,'ViewBcatname');
}

function ChangeBcatnameActionInnerHtml(Element,Value,Id){
	var url    = "./ajax_updateProduct.php?Type=updateBcatnameAction&action=update&Element="+Element+"&Id="+Id+"&Value="+Value;
	AjaxGetRequestInnerHtml(url,Id,Element,'BcatnameAction');
}


function AjaxGetRequestInnerHtml(url,Id,Element,Type){

	if (typeof(url) == 'undefined'){
		    return false;
	}
	if (typeof(Id) == 'undefined'){
		    return false;
	}
	if (typeof(Element) == 'undefined'){
		    return false;
	}
	if (typeof(Type) == 'undefined'){
		    return false;
	}


	var ajax = InitAjax();
	ajax.open("GET", url, true);
	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
	ajax.onreadystatechange = function() {
		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
		    if (ajax.readyState == 4 && ajax.status == 200) {
		    	if (Type=='PriceDesc'){
		    		txt = "<a onclick=ChangePriceDescInnerHtml('"+Element+"','"+ajax.responseText+"','"+Id+"')>"+ajax.responseText+"</a>";
		    	}
		    	if (Type=='Storage'){
		    		txt = "<a onclick=ChangeStorageInnerHtml('"+Element+"','"+ajax.responseText+"','"+Id+"')>"+ajax.responseText+"</a>";
		    	}
		    	if (Type=='Sort'){
		    		txt = "<a onclick=ChangeSortInnerHtml('"+Element+"','"+ajax.responseText+"','"+Id+"')>"+ajax.responseText+"</a>";
		    	}
		    	if (Type=='Bn'){
		    		txt = "<a onclick=ChangeBnInnerHtml('"+Element+"','"+ajax.responseText+"','"+Id+"')>"+ajax.responseText+"</a>";
		    	}
		    	if (Type=='ifPub'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='ifRmb'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='ifSpecial'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='ifHot'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='ViewBcatname'){
		    		txt =  ajax.responseText;
		    	}
		    	if (Type=='BcatnameAction'){
		    		txt =  ajax.responseText;
		    	}



		    	var show   = document.getElementById(Element);
		    	show.innerHTML =  txt; 		        		      

		    }
		    }
		    ajax.send(null);
		    }

</script>
