<?php
include_once "Check_Admin.php";
/**
 *  装载服务语言包
 */
include "../language/".$INFO['IS']."/StaticHtml_Pack.php";
/**
 *   引入AJAX
 */
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
?>
<HTML  xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK href="../css/theme.css" type=text/css rel=stylesheet>
<LINK href="../css/css.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[StaticHtml];?></TITLE></HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" >
<?php  include $Js_Top ;  ?>
	  <script language="javascript">



	  function JsSystem(obj,v){
	  	if (v==0){
	  		CreateSystemHtmlTable.style.display = "";
	  		obj.innerHTML = "<a href='javascript:JsSystem(touchSystem,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>'/></a>";
	  	}
	  	if (v==1){
	  		CreateSystemHtmlTable.style.display = "none";
	  		obj.innerHTML = "<a href='javascript:JsSystem(touchSystem,0);'><img src='images/suggest_down.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>'  /></a>";
	  	}
	  }



	  function allproduct(obj,v){
	  	if (v==0){
	  		CreateHtmlTable.style.display = "";
	  		obj.innerHTML = "<a href='javascript:allproduct(touchAllproduct,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>'/></a>";
	  	}
	  	if (v==1){
	  		CreateHtmlTable.style.display = "none";
	  		obj.innerHTML = "<a href='javascript:allproduct(touchAllproduct,0);'><img src='images/suggest_down.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>'  /></a>";
	  	}
	  }

	  function Jssubject(obj,v){
	  	if (v==0){
	  		CreateHtml_subject_Table.style.display = "";
	  		obj.innerHTML = "<a href='javascript:Jssubject(touchSubject,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
	  	}
	  	if (v==1){
	  		CreateHtml_subject_Table.style.display = "none";
	  		obj.innerHTML = "<a href='javascript:Jssubject(touchSubject,0);'><img src='images/suggest_down.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a>";
	  	}
	  }

	  function JsBonus(obj,v){
	  	if (v==0){
	  		CreateHtml_bonus_Table.style.display = "";
	  		obj.innerHTML = "<a href='javascript:JsBonus(touchBonus,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
	  	}
	  	if (v==1){
	  		CreateHtml_bonus_Table.style.display = "none";
	  		obj.innerHTML = "<a href='javascript:JsBonus(touchBonus,0);'><img src='images/suggest_down.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a>";
	  	}
	  }

	  function JsRSH(obj,v){
	  	if (v==0){
	  		CreateHtml_RSH_Table.style.display = "";
	  		obj.innerHTML = "<a href='javascript:JsRSH(touchRSH,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
	  	}
	  	if (v==1){
	  		CreateHtml_RSH_Table.style.display = "none";
	  		obj.innerHTML = "<a href='javascript:JsRSH(touchRSH,0);'><img src='images/suggest_down.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a>";
	  	}
	  }

	  function JsAFPH(obj,v){
	  	if (v==0){
	  		CreateHtml_AllForProductHtml_Table.style.display = "";
	  		obj.innerHTML = "<a href='javascript:JsAFPH(touchAFPH,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
	  	}
	  	if (v==1){
	  		CreateHtml_AllForProductHtml_Table.style.display = "none";
	  		obj.innerHTML = "<a href='javascript:JsAFPH(touchAFPH,0);'><img src='images/suggest_down.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a>";
	  	}
	  }


	  function JsAAC(obj,v){
	  	if (v==0){
	  		CreateHtml_AAC_Table.style.display = "";
	  		obj.innerHTML = "<a href='javascript:JsAAC(touchAAC,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
	  	}
	  	if (v==1){
	  		CreateHtml_AAC_Table.style.display = "none";
	  		obj.innerHTML = "<a href='javascript:JsAAC(touchAAC,0);'><img src='images/suggest_down.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a>";
	  	}
	  }


	  </script>
	  
<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=24 cellSpacing=0 cellPadding=2 width="99%" align=center  border=0><TBODY>
  <TR>
    <TD width=0%>&nbsp; </TD>
    <TD width="16%">&nbsp;</TD>
    <TD align=right width="84%">
      <?php  include_once "desktop_title.php";?>
	  </TD></TR></TBODY></TABLE>
      <?php  include_once "Order_state.php";?>
  <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD><IMG height=5 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=778></TD>
	</TR>
  </TBODY>
  </TABLE>
<TABLE cellSpacing=0 cellPadding=0 width="97%" align=center border=0>
  <TBODY>
  <TR>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/lt.gif" width=9></TD>
    <TD width="98%" background=images/<?php echo $INFO[IS]?>/top.gif height=7><IMG height=1 
      src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
    <TD width="1%" height=7><IMG height=9 src="images/<?php echo $INFO[IS]?>/rt.gif" width=9></TD></TR>
  <TR>    <TD width="1%" background=images/<?php echo $INFO[IS]?>/left.gif style="background-repeat: repeat-y;" height=319></TD>
    <TD vAlign=top width="100%" height=319>
      <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="50%">
            <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
              <TR>
                <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                <TD class=p12black noWrap><SPAN  class=p9orange><?php echo $JsMenu[Tools];//工具?>--&gt;<?php echo $JsMenu[StaticHtml];?></SPAN></TD>
              </TR>
			  </TBODY>
			 </TABLE>
		  </TD>
          <TD align=right width="50%">&nbsp;</TD>
        </TR>
			</TBODY>
		  </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top height=262>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
              <TR>
                <TD vAlign=top bgColor=#ffffff height=300>
                  <TABLE class=allborder cellSpacing=0 cellPadding=2  width="96%" align=center bgColor=#f7f7f7 border=0>
                    <TBODY>
					<TR>
					  <TD noWrap>
					  
					  
					  
<TABLE width="100%" bordercolor="#CCCCCC" border=0 align="center" cellPadding=10 cellSpacing=0 class=listtable>
  <TBODY>
    <TR  class="row0">
      <TD align="center" valign="middle">&nbsp;</TD>
      <TD align="left" nowrap="nowrap">&nbsp;</TD>
      <TD align="center" valign="middle" nowrap="nowrap">&nbsp;</TD>
      <TD colspan="2" align="left" nowrap="nowrap">&nbsp;</TD>
      <TD colspan="2" align="right">&nbsp;</TD>
    </TR>
    <TR  class="row1">
      <TD align="center" valign="middle"><div id="AFPHOKimg">&nbsp;<div></TD>
      <TD colspan="5"><A href="javascript:CreateAllProduct_System();"><img src="images/html_allforProduct-48.gif" width="48" height="48"  border="0"/><?php echo $StaticHtml_Pack[CreateProductClassHtml_System] ;?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000"><?php echo $StaticHtml_Pack[CreateProductClassHtml_SystemIntro];?></font></TD>
      <TD align="right" valign="middle">
	  <div id="touchAFPH"><a href="javascript:JsAFPH(touchAFPH,0);"><img src="images/suggest_down.gif" border="0" alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a></div>	  </TD>
    </TR>
    <TR  class="row0" style="display:none" id="CreateHtml_AllForProductHtml_Table">
      <TD height=43 colspan="7">
	  
	  <table width="100%" border="0" cellpadding="3" cellspacing="3" class="input02" >
                    <tr>
                      <td width="20%" align="center" valign="top" >Making.....</td>
                          <td height="25" valign="top" ><div id="show_AFPH_Content" height="30" class="p9orange">&nbsp;</div>
                           <div id="show_AFPH_Textarea">
						   <textarea name="show_AFPH_Content_textarea" cols="120" rows="20" id="show_AFPH_Content_textarea"></textarea>
						   </div>						   </td>
                          </tr>
                    </table>	  </TD>
      </TR>
    <TR  bgcolor="#f7f3f7">
      <TD align="center" valign="middle"><?php echo $StaticHtml_Pack[System_Class_title] ;?></TD>
      <TD align="left" nowrap="nowrap">&nbsp;</TD>
      <TD align="center" valign="middle" nowrap="nowrap">&nbsp;</TD>
      <TD colspan="2" align="left" nowrap="nowrap">&nbsp;</TD>
      <TD colspan="2" align="right">&nbsp;</TD>
    </TR>
    <TR  class="row1">
      <TD align="center" valign="middle"><div id="SystemOKimg">&nbsp;<div></TD>
      <TD align="left" nowrap="nowrap">
	   <A href="javascript:CreateSystemHtml();"><IMG src="./images/HtmlSystem-48.gif"    border="0" /><?php echo $StaticHtml_Pack[CreateSystemHtml] ?></A>	  </TD>
      <TD align="center" valign="middle" nowrap="nowrap">
      <div id="indexpageOKimg">&nbsp;<div></TD>
      <TD colspan="2" align="left" nowrap="nowrap">	   <A href="javascript:CreateIndexPageHtml();"><IMG src="images/html-indexcon-48.gif" width="48" height="48"    border="0" /><?php echo $StaticHtml_Pack[CreateIndexPageHtml]  ?></A></TD>
      <TD colspan="2" align="right">
	  <div id="touchSystem"><a href="javascript:JsSystem(touchSystem,0);"><img src="images/suggest_down.gif" border="0" alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a></div>	  </TD>
    </TR>
     <TR  style="display:none" id="CreateSystemHtmlTable">
      <TD colspan="7" align="center" valign="middle">
	  
	  	           <table width="100%" border="0" cellpadding="3" cellspacing="3" class="input02" >
                    <tr>
                      <td width="20%" align="center" valign="top" >Making.....</td>
                          <td height="25" align="left" valign="top" ><div id="show_system_Content" height="30" class="p9orange">&nbsp;</div>
                           <div id="showSystemTextarea">
						   <textarea name="show_system_Content_textarea" cols="120" rows="12" id="show_system_Content_textarea"></textarea>
						   </div>						   </td>
                          </tr>
                    </table>	  </TD>
     </TR>
     <TR  bgcolor="#f7f3f7">
       <TD align="center" valign="middle"><?php echo $StaticHtml_Pack[Product_Class_title];?></TD>
       <TD align="left" nowrap="nowrap">&nbsp;</TD>
       <TD align="center" valign="middle">&nbsp;</TD>
       <TD align="left" nowrap="nowrap">&nbsp;</TD>
       <TD colspan="2" align="left">&nbsp;</TD>
       <TD align="right">&nbsp;</TD>
     </TR>
     <TR  class="row1">
      <TD width="10%" align="center" valign="middle"><div id="AllClassOKimg">&nbsp;<div></TD>
      <TD width="21%" align="left" nowrap="nowrap">
	  <A href="javascript:CreateAllProductClassList();"><IMG src="./images/Html_ProductClass-48.gif" border=0> <?php echo $StaticHtml_Pack[CreateAllClassHtml]?></A>		   </TD>
      <TD align="center" valign="middle"><div id="AllproductOKimg">&nbsp;<div></TD>
      <TD align="left" nowrap="nowrap">
	  <A href="javascript:CreateAllProduct();"><IMG src="./images/ie-48.gif" border=0> <?php echo $StaticHtml_Pack[CreateAllProductHtml]?></A>	  </TD>
      <TD colspan="2" align="left"><?php echo $StaticHtml_Pack[CreateAllClassHtmlData_Intro];?>	  	  </TD>
      <TD align="right"><div id="touchAllproduct"><a href="javascript:allproduct(touchAllproduct,0);"><img src="images/suggest_down.gif" border="0" alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a></div></TD>
    </TR>
    <TR  style="display:none" id="CreateHtmlTable">
      <TD colspan="7">
	               <table width="100%" border="0" cellpadding="3" cellspacing="3" class="input02" >
                    <tr>
                      <td width="20%" align="center" valign="top" >Making.....</td>
                          <td height="25" valign="top" ><div id="show_Content" height="30" class="p9orange">&nbsp;</div>
                           <div id="showTextarea">
						   <textarea name="show_Content_textarea" cols="120" rows="12" id="show_Content_textarea"></textarea>
						   </div>						   </td>
                          </tr>
                    </table>	  </TD>
    </TR>
    <TR  class="row0">
      <TD align="center" valign="middle"> <div id="SubjectOKimg">&nbsp;<div></TD>
      <TD align="left">
	  	   <A href="javascript:CreateSubjectClass();"><IMG src="./images/Htmlsubject-48.gif" border=0><?php echo $StaticHtml_Pack[CreateHtml_SubjectList]?></A>		   </TD>
      <TD colspan="3" align="left" ><?php //echo $StaticHtml_Pack[CreateSubjectClassHtmlData_Intro];?>
        &nbsp;</TD>
      <TD colspan="2" align="right"><div id="touchSubject"><a href="javascript:Jssubject(touchSubject,0);"><img src="images/suggest_down.gif" border="0" alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a></div></TD>
    </TR>

    <TR  style="display:none" id="CreateHtml_subject_Table">
      <TD colspan="7">
	               <table width="100%" border="0" cellpadding="3" cellspacing="3" class="input02" >
                    <tr>
                      <td width="20%" align="center" valign="top" >Making.....</td>
                          <td height="25" valign="top" ><div id="show_subject_Content" height="30" class="p9orange">&nbsp;</div>
                           <div id="show_subject_Textarea">
						   <textarea name="show_subject_Content_textarea" cols="120" rows="12" id="show_subject_Content_textarea"></textarea>
						   </div>						   </td>
                          </tr>
                    </table>	   </TD>
    </TR>
	
	

    <TR  class="row1">
      <TD align="center" valign="middle"><div id="BonusOKimg">&nbsp;<div></TD>
      <TD align="left">
	   <A href="javascript:CreateBonusList();"><IMG src="./images/Htmlbonusproductlist_48.gif"  border=0> <?php echo $StaticHtml_Pack[CreateBonusListHtml]?></A>	  </TD>
      <TD width="10%" align="center" valign="middle"><div id="BonusDetailOKimg">&nbsp;<div></TD>
      <TD colspan="2" align="left">
	  <A href="javascript:CreateBonusProductDetail();"><IMG src="./images/HtmlBonusDetail-48.gif"  border=0> <?php echo $StaticHtml_Pack[CreateBonusProductDetailHtml]?></A></TD>
      <TD colspan="2" align="right">
	  <div id="touchBonus"><a href="javascript:JsBonus(touchBonus,0);"><img src="images/suggest_down.gif" border="0" alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a></div>	  </TD>
    </TR>
    <TR  class="row0" style="display:none" id="CreateHtml_bonus_Table">
      <TD height=43 colspan="7">
	  
	  <table width="100%" border="0" cellpadding="3" cellspacing="3" class="input02" >
                    <tr>
                      <td width="20%" align="center" valign="top" >Making.....</td>
                          <td height="25" valign="top" ><div id="show_bonus_Content" height="30" class="p9orange">&nbsp;</div>
                           <div id="show_bonus_Textarea">
						   <textarea name="show_bonus_Content_textarea" cols="120" rows="12" id="show_bonus_Content_textarea"></textarea>
						   </div>						   </td>
                          </tr>
                    </table>	  </TD>
      </TR>
    <TR  class="row0">
      <TD height=43 align="center" valign="middle"><div id="RSH_ROKimg">&nbsp;<div></TD>
      <TD align="left"> 
	  <A href="javascript:CreateRSH('Recommend');"><IMG src="./images/Html_recommend-48.gif" border="0"/><?php echo $StaticHtml_Pack[CreateRecommendListHtml];?></A>      </TD>
      <TD align="center" valign="middle"><div id="RSH_SOKimg">&nbsp;<div></TD>
      <TD width="19%" align="left">
	   <A href="javascript:CreateRSH('Special');"><IMG src="./images/html_special-48.gif"  border="0"><?php echo $StaticHtml_Pack[CreateSpecialListHtml] ?></A>	  </TD>
      <TD width="8%" align="center" valign="middle"><div id="RSH_HOKimg">&nbsp;<div></TD>
      <TD width="24%" align="left">
	  <A href="javascript:CreateRSH('Hot');"><IMG src="./images/html_hot-48.gif"  border=0> <?php echo $StaticHtml_Pack[CreateHotListHtml] ;?></A>	  </TD>
      <TD width="8%" align="right">
	  <div id="touchRSH"><a href="javascript:JsRSH(touchRSH,0);"><img src="images/suggest_down.gif" border="0" alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a></div>	  </TD>
    </TR>
    <TR  class="row0" style="display:none" id="CreateHtml_RSH_Table">
      <TD height=43 colspan="7">
	  
	  <table width="100%" border="0" cellpadding="3" cellspacing="3" class="input02" >
                    <tr>
                      <td width="20%" align="center" valign="top" >Making.....</td>
                          <td height="25" valign="top" ><div id="show_RSH_Content" height="30" class="p9orange">&nbsp;</div>
                           <div id="show_RSH_Textarea">
						   <textarea name="show_RSH_Content_textarea" cols="120" rows="12" id="show_RSH_Content_textarea"></textarea>
						   </div>						   </td>
                          </tr>
                    </table>	  </TD>
      </TR>

    <TR bgcolor="#f7f3f7">
      <TD align="center" valign="middle"><?php echo $StaticHtml_Pack[Article_Class_title];?></TD>
      <TD>&nbsp;</TD>
      <TD>&nbsp;</TD>
      <TD>&nbsp;</TD>
      <TD>&nbsp;</TD>
      <TD>&nbsp;</TD>
      <TD>&nbsp;</TD>
    </TR>
    <TR  class="row1">
      <TD height=43 align="center" valign="middle"><div id="AACOKimg">&nbsp;<div></TD>
      <TD height=43>
	  <A href="javascript:CreateAllArticleClass();"><IMG src="./images/Html_articleclass-48.gif" border="0"/><?php echo $StaticHtml_Pack[CreateAllArticleClass] ;?></A>	  </TD>
      <TD height=43 align="center" valign="middle"><div id="AAHOKimg">&nbsp;<div></TD>
      <TD height=43>
	  <A href="javascript:CreateAllArticleHtml();"><IMG src="./images/Html_article_html.gif" border="0"/><?php echo $StaticHtml_Pack[CreateAllArticleHtml] ;?></A>	  </TD>
      <TD height=43>&nbsp;</TD>
      <TD height=43>&nbsp;</TD>
      <TD height=43 align="right" valign="middle"><div id="touchAAC"><a href="javascript:JsAAC(touchAAC,0);"><img src="images/suggest_down.gif" border="0" alt='<?php echo $StaticHtml_Pack[HtmlText_Open] ?>' /></a></div></TD>
    </TR>


    <TR  class="row0" style="display:none" id="CreateHtml_AAC_Table">
      <TD height=43 colspan="7">
	  
	  <table width="100%" border="0" cellpadding="3" cellspacing="3" class="input02" >
                    <tr>
                      <td width="20%" align="center" valign="top" >Making.....</td>
                          <td height="25" valign="top" ><div id="show_AAC_Content" height="30" class="p9orange">&nbsp;</div>
                           <div id="show_AAC_Textarea">
						   <textarea name="show_AAC_Content_textarea" cols="120" rows="12" id="show_AAC_Content_textarea"></textarea>
						   </div>						   </td>
                          </tr>
                    </table>	  </TD>
      </TR>
  </TBODY>
</TABLE>
					  
					  
					  
					  
					  
					  
					  
					  
					  
					  
					  
					  
					  
					  
					  
					  
					  
					   </TD>
					  </TR>
                    </TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD>
                      <TD width="1%" background=images/<?php echo $INFO[IS]?>/right.gif height=319>&nbsp;</TD></TR>
                    <TR>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/lb.gif" width=9></TD>
                      <TD width="98%" background=images/<?php echo $INFO[IS]?>/bottom.gif>
					  <IMG height=1 src="images/<?php echo $INFO[IS]?>/spacer.gif" width=1></TD>
                      <TD width="1%"><IMG height=9 src="images/<?php echo $INFO[IS]?>/rb.gif"  width=9></TD></TR>

					  </TBODY>
</TABLE>
                      <div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>
<?php echo $InitAjax;?>
 <script language="javascript">



 function CreateSystemHtml(){
 	CreateSystemHtmlTable.style.display = "";
 	var url    = "../CreateHtml/Create_Aboutour.php?action=CreateAllSystem";
 	var show   = document.getElementById("show_system_Content");
 	var showid = "system";
 	AjaxGetRequest(url,show,showid,'')
 }

 function CreateIndexPageHtml(){
 	CreateSystemHtmlTable.style.display = "";
 	var url    = "../CreateHtml/create_index.php";
 	var show   = document.getElementById("show_system_Content");
 	var showid = "indexpage";
 	AjaxGetRequest(url,show,showid,'')
 }


 function CreateAllProductClassList(){
 	CreateHtmlTable.style.display = "";
 	var url    = "../CreateHtml/create_product_class.php?action=AllProductClassList";
 	var show   = document.getElementById("show_Content");
 	var showid = "allproductclasslist";
 	AjaxGetRequest(url,show,showid,'')
 }


 function CreateAllProduct(){
 	CreateHtmlTable.style.display = "";
 	var url    = "../CreateHtml/admin_create_goods_detail.php?action=CreateAllProduct";
 	var show   = document.getElementById("show_Content");
 	var showid = "allproduct";
 	AjaxGetRequest(url,show,showid,'')
 }


 function CreateSubjectClass(){
 	//CreateHtmlTable.style.display = "none";
 	CreateHtml_subject_Table.style.display = "";
 	var url = "../CreateHtml/create_subject_index.php?action=CreateAllSubjectClass";
 	var show = document.getElementById("show_subject_Content");
 	var showid = "subject";
 	AjaxGetRequest(url,show,showid,'')
 }

 function CreateBonusList(){
 	//CreateHtmlTable.style.display = "none";
 	CreateHtml_bonus_Table.style.display = "";
 	var url = "../CreateHtml/create_bonus_index.php?action=BonusList";
 	var show = document.getElementById("show_bonus_Content");
 	var showid = "bonuslist";
 	AjaxGetRequest(url,show,showid,'')
 }

 function CreateBonusProductDetail(){
 	//CreateHtmlTable.style.display = "none";
 	CreateHtml_bonus_Table.style.display = "";
 	var url = "../CreateHtml/create_bonus_index.php?action=BonusDetail";
 	var show = document.getElementById("show_bonus_Content");
 	var showid = "bonusdetail";
 	AjaxGetRequest(url,show,showid,'')
 }

 function CreateRSH(theValue){
 	//CreateHtmlTable.style.display = "none";
 	CreateHtml_RSH_Table.style.display = "";
 	var url = "../CreateHtml/create_product_list_detail.php?action="+theValue;
 	var show = document.getElementById("show_RSH_Content");
 	var showid = "RSH";
 	AjaxGetRequest(url,show,showid,theValue)
 }

 function CreateAllProduct_System(){
 	//CreateHtmlTable.style.display = "none";
 	CreateHtml_AllForProductHtml_Table.style.display = "";
 	var url = "../CreateHtml/create_site_forall.php";
 	var show = document.getElementById("show_AFPH_Content");
 	var showid = "AFPH";
 	AjaxGetRequest(url,show,showid,'')
 }

 function CreateAllArticleClass(){
 	//CreateHtmlTable.style.display = "none";
 	CreateHtml_AAC_Table.style.display = "";
 	var url = "../CreateHtml/create_article.php?action=AllForArticleClass";
 	var show = document.getElementById("show_AAC_Content");
 	var showid = "AAC";
 	AjaxGetRequest(url,show,showid,'')
 }

 function CreateAllArticleHtml(){
 	//CreateHtmlTable.style.display = "none";
 	CreateHtml_AAC_Table.style.display = "";
 	var url = "../CreateHtml/create_article.php?action=AllArticleHtml";
 	var show = document.getElementById("show_AAC_Content");
 	var showid = "AAH";
 	AjaxGetRequest(url,show,showid,'')
 }


 function AjaxGetRequest(url,show,showid,showRSHvalue){
 	if (typeof(url) == 'undefined'){
 		　　return false;
 	}
 	if (typeof(showid) == 'undefined'){
 		　　return false;
 	}
 	if (typeof(showRSHvalue) == 'undefined'){
 		　　return false;
 	}


 	var ajax = InitAjax();
 	ajax.open("GET", url, true);
 	ajax.setRequestHeader("Content-Type","text/html; charset=utf-8")
 	ajax.onreadystatechange = function() {
 		//alert ('d');
 		　　//如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		　　show.innerHTML = "<?php echo $StaticHtml_Pack[PleaseWait] ;?>&nbsp;&nbsp;<img src='images/gif018.gif' />";

 		　　if (showid=="system" || showid=="indexpage"){
 		　　	showSystemTextarea.innerHTML = "<div id='show_system_Content_textarea'></div>";
 		　　}

 		　　if (showid=="allproduct" || showid=="allproductclasslist"){
 		　　	showTextarea.innerHTML = "<div id='show_Content_textarea'></div>";
 		　　}
 		　　if (showid=="subject"){
 		　　	show_subject_Textarea.innerHTML = "<div id='show_subject_Content_textarea'></div>";
 		　　}
 		　　if (showid=="bonuslist" || showid=="bonusdetail" ){
 		　　	show_bonus_Textarea.innerHTML = "<div id='show_bonus_Content_textarea'></div>";
 		　　}
 		　　if (showid=="RSH"){
 		　　	show_RSH_Textarea.innerHTML = "<div id='show_RSH_Content_textarea'></div>";
 		　　}
 		　　if (showid=="AFPH"){
 		　　	show_AFPH_Textarea.innerHTML = "<div id='show_AFPH_Content_textarea'></div>";
 		　　}

 		　　if (showid=="AAC" || showid=="AAH"   ){
 		　　	show_AAC_Textarea.innerHTML = "<div id='show_AAC_Content_textarea'></div>";
 		　　}


 		　　if (ajax.readyState == 4 && ajax.status == 200) {
 		　　	　//alert (ajax.responseText);　　　　		　　　
 		　　	　//show.innerHTML = ajax.responseText;
 		　　	　//document.show_Content_textarea.value=ajax.responseText;
 		　　	　//alert(Math.abs(ajax.responseText));
 		　　	　if (Math.abs(ajax.responseText)==0){
 		　　	　	show.innerHTML = '<?php echo $Basic_Command[NullDate];?>';
 		　　	　}else{

 		　　	　if (showid=="system" || showid=="indexpage"){
 		　　	　	if (showid=="system"){
 		　　	　		SystemOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}
 		　　	　	if (showid=="indexpage"){
 		　　	　		indexpageOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}
 		　　	　	touchSystem.innerHTML = "<a href='javascript:JsSystem(touchSystem,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>'/></a>";
 		　　	　	showSystemTextarea.innerHTML = "<textarea name='show_system_Content_textarea' cols='120' rows='10' id='show_system_Content_textarea'></textarea>";
 		　　	　	document.getElementById("show_system_Content_textarea").value = ajax.responseText;
 		　　	　}


 		　　	　if (showid=="allproduct"  || showid=="allproductclasslist"){
 		　　	　	if (showid=="allproduct"){
 		　　	　		AllproductOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}
 		　　	　	if (showid=="allproductclasslist"){
 		　　	　		AllClassOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}
 		　　	　	touchAllproduct.innerHTML = "<a href='javascript:allproduct(touchAllproduct,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>'/></a>";
 		　　	　	showTextarea.innerHTML = "<textarea name='show_Content_textarea' cols='120' rows='10' id='show_Content_textarea'></textarea>";
 		　　	　	document.getElementById("show_Content_textarea").value = ajax.responseText;
 		　　	　}
 		　　	　if (showid=="subject"){
 		　　	　	SubjectOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	touchSubject.innerHTML = "<a href='javascript:Jssubject(touchSubject,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
 		　　	　	show_subject_Textarea.innerHTML = "<textarea name='show_subject_Content_textarea' cols='120' rows='10' id='show_subject_Content_textarea'></textarea>";
 		　　	　	document.getElementById("show_subject_Content_textarea").value = ajax.responseText;
 		　　	　}

 		　　	　if (showid=="bonuslist" || showid=="bonusdetail" ) {
 		　　	　	if (showid=="bonuslist"){
 		　　	　		BonusOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}
 		　　	　	if (showid=="bonusdetail"){
 		　　	　		BonusDetailOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}

 		　　	　	touchBonus.innerHTML = "<a href='javascript:JsBonus(touchBonus,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
 		　　	　	show_bonus_Textarea.innerHTML = "<textarea name='show_bonus_Content_textarea' cols='120' rows='10' id='show_bonus_Content_textarea'></textarea>";
 		　　	　	document.getElementById("show_bonus_Content_textarea").value = ajax.responseText;
 		　　	　}

 		　　	　if (showid=="RSH"){
 		　　	　	if (showRSHvalue=='Recommend'){
 		　　	　		RSH_ROKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}
 		　　	　	if (showRSHvalue=='Special'){
 		　　	　		RSH_SOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}
 		　　	　	if (showRSHvalue=='Hot'){
 		　　	　		RSH_HOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}

 		　　	　	touchRSH.innerHTML = "<a href='javascript:JsRSH(touchRSH,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
 		　　	　	show_RSH_Textarea.innerHTML = "<textarea name='show_RSH_Content_textarea' cols='120' rows='10' id='show_RSH_Content_textarea'></textarea>";
 		　　	　	document.getElementById("show_RSH_Content_textarea").value = ajax.responseText;
 		　　	　}

 		　　	　if (showid=="AFPH"){
 		　　	　	AFPHOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	touchAFPH.innerHTML = "<a href='javascript:JsAFPH(touchAFPH,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
 		　　	　	show_AFPH_Textarea.innerHTML = "<textarea name='show_AFPH_Content_textarea' cols='120' rows='10' id='show_AFPH_Content_textarea'></textarea>";

 		　　	　	//alert(ajax.responseText);
 		　　	　	document.getElementById("show_AFPH_Content_textarea").value = ajax.responseText;
 		　　	　}

 		　　	　if (showid=="AAC" || showid=="AAH"  ){
 		　　	　	if (showid=="AAC"){
 		　　	　		AACOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}
 		　　	　	if (showid=="AAH"){
 		　　	　		AAHOKimg.innerHTML = "<img src='images/ok_16.gif'  border='0' />";
 		　　	　	}
 		　　	　	touchAAC.innerHTML = "<a href='javascript:JsAAC(touchAAC,1);'><img src='images/suggest_up.gif' border='0' alt='<?php echo $StaticHtml_Pack[HtmlText_Close] ?>' /></a>";
 		　　	　	show_AAC_Textarea.innerHTML = "<textarea name='show_AAC_Content_textarea' cols='120' rows='10' id='show_AAC_Content_textarea'></textarea>";
 		　　	　	document.getElementById("show_AAC_Content_textarea").value = ajax.responseText;
 		　　	　}




 		　　	　show.innerHTML = '<?php echo $StaticHtml_Pack[HaveCreatedHtml]?>';
 		　　	　}
 		　　	　　　　	}
 		　　	　　　　	　　　　		　　　
 		　　	　}

 		　　	　ajax.send(null);
 		　　	　}
</script>
