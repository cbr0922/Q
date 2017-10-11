<?php
include_once "Check_Admin.php";
$gid = intval($_GET['gid']);
$Query = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='" . $_GET['tag'] . "' limit 0,1");
$Num   = $DB->num_rows($Query);
$Result= $DB->fetch_array($Query);
$adv_id       =  $Result['adv_id'];
$adv_title       =  $Result['adv_title'];

$adv_type       =  $Result['adv_type'];
$adv_left_url       =  $Result['adv_left_url'];
$adv_left_img       =  $Result['adv_left_img'];
$adv_content       =  $Result['adv_content'];
if ($Result['start_time']!=""){
			$begtime    =  date("Y-m-d",trim($Result['start_time']));
			$start_h    =  date("H",trim($Result['start_time']));
			$start_i    =  date("i",trim($Result['start_time']));
		}
		if ($Result['end_time']!=""){
			$endtime    =  date("Y-m-d",trim($Result['end_time']));
			$end_h    =  date("H",trim($Result['end_time']));
			$end_i    =  date("i",trim($Result['end_time']));
		}
if($Num==0)
	$adv_type=21;
?>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
	<script type="text/javascript" src="../Resources/redactor-js-master/lib/jquery-1.9.0.min.js"></script>
	<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>


	<!-- Redactor is here -->
	<link rel="stylesheet" href="../Resources/redactor-js-master/redactor/redactor.css" />
	<script src="../Resources/redactor-js-master/redactor/redactor.js"></script>
   <!-- Plugin -->
          <script src="/Resources/redactor-js-master/redactor/plugins/source.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/table.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fullscreen.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontsize.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontfamily.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/fontcolor.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/inlinestyle.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/video.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/properties.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/textdirection.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/imagemanager.js"></script>
          <script src="/Resources/redactor-js-master/redactor/plugins/alignment/alignment.js"></script>
          <link rel="stylesheet" href="../Resources/redactor-js-master/redactor/plugins/alignment/alignment.css" />
    <!--/ Plugin -->
    
	<script type="text/javascript">
	$(document).ready(
		function()
		{
			$('#redactor').redactor({
				imageUpload: '../Resources/redactor-js-master/demo/scripts/image_upload.php',
				imageManagerJson: '../Resources/redactor-js-master/demo/scripts/image_json.php',
				plugins: ['source','imagemanager', 'video','fontsize','fontcolor','alignment','fontfamily','table','textdirection','properties','inlinestyle','fullscreen'],
				imagePosition: true,
                imageResizable: true,
				<?php
				if ($_GET['tag']!="" && $Num>0){
				?>
				autosave: 'admin_indexseting_ajax_advsave.php?act=autosave&tag=<?php echo $_GET['tag'];?>',
				callbacks: {
					autosave: function(json)
					{
						 console.log(json);
					}
				}
				<?php
				}
				?>

			});
		}
	);
	</script>
<style>
.onebtn{
	align-items: flex-start;
    text-align: center;
    cursor: default;
    color: buttontext;
    background-color: buttonface;
    box-sizing: border-box;
    border-width: 2px;
    border-style: outset;
    border-color: buttonface;
    border-image: initial;
    padding: 1px 6px;
    -webkit-appearance: push-button;
    user-select: none;
    white-space: pre;
    -webkit-rtl-ordering: logical;
    text-rendering: auto;
    letter-spacing: normal;
    word-spacing: normal;
    text-transform: none;
    text-indent: 0px;
    text-shadow: none;
    display: inline-block;
    margin: 0em;
    font: 13.3333px Arial;
	color:#000 !important;
	
}

</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div align="center" id="divchangeStorage"><br>
    <form action="admin_indexseting_ajax_advsave.php" method="post" name="advform" id="advform" enctype="multipart/form-data">
    <input name="tag" type="hidden"  id='tag' value="<?php echo $_GET['tag'];?>">
    <input name="inpsaveall" type="hidden"  id='inpsaveall' value="">
	<input type="button" name="cPic" id="cPic" value="返回" onclick="closeWin();location.reload();" style="float: right;margin: 0 10px 10px 10px;" />
      <table width="98%" border="0" cellspacing="1" cellpadding="5" bgcolor="#CCCCCC">
			
				
					  
			


            <tr>
              <td width="457" align="left" bgcolor="#FFFFFF"><input id="adv_type" name="adv_type" type="radio" value="21" <?php if($adv_type==21) echo "checked"?> onclick="showAdv('adv_index21')">輪播廣告 <input id="adv_type" name="adv_type" type="radio" value="22" <?php if($adv_type==22) echo "checked"?>  onclick="showAdv('adv_index22')">單張廣告 <input id="adv_type" name="adv_type" type="radio" value="23" <?php if($adv_type==23) echo "checked"?>  onclick="showAdv('adv_index23')">文字 <input id="adv_type" name="adv_type" type="radio" value="24" <?php if($adv_type==24) echo "checked"?>  onclick="showAdv('adv_index24')">影片</td>
            </tr>
            <tr>
              <td align="left" bgcolor="#FFFFFF">
              <div id="adv_index21">
                <p>+新增輪播廣告<br><br>

                  廣告圖片：<input name="ima" type="file"  id='ima'>
                  <br>
				  <div>
                      
                            <div>起迄時間：<INPUT   id=begtime3 size=10 value=""    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime21 />
                              <select name="start_h21">
                                <?php
								for($i=0;$i<=23;$i++){
								?>
                                <option value="<?php echo $i;?>" ><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              時
                              <select name="start_i21">
                                <?php
								for($i=0;$i<=59;$i++){
								?>
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              分 至
                              <INPUT    id=endtime3 size=10 value=""      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime21 />
                              <select name="end_h21">
                                <?php
								for($i=0;$i<=23;$i++){
								?>
                                <option value="<?php echo $i;?>" ><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              時
                              <select name="end_i21">
                                <?php
								for($i=0;$i<=59;$i++){
								?>
                                <option value="<?php echo $i;?>" ><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              分 <a href="#" class="easyui-tooltip" title="若不限制時間請將日期去掉"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </div>
                        </div>
						<br>
				  
                  廣告超連結：<?php echo $FUNCTIONS->Input_Box('text','adv_left_url','',"      maxLength=200 size=60 ")?>
                  <br><br><input type="button" name="advsave" id="advsave" value="新增廣告">

                  <br><br>
				  <?php
                  $Query_l = $DB->query("select * from `{$INFO[DBPrefix]}advertising` where adv_tag='" . $_GET['tag'] . "' ");
				  $Num_l   = $DB->num_rows($Query_l);
				  ?>
				  
				  <?php if($Num_l!=0) { ?>
				  <hr><br>
                  輪播廣告列表<br><br>
				  
				  <table border="1" cellpadding="5" cellspacing="0">
                  <?php
				  while($Result_l= $DB->fetch_array($Query_l)){
					  if ($Result['start_time']!=""){
							$begtime_l    =  date("Y-m-d",trim($Result_l['start_time']));
							$start_h_l    =  date("H",trim($Result_l['start_time']));
							$start_i_l    =  date("i",trim($Result_l['start_time']));
						}
						if ($Result_l['end_time']!=""){
							$endtime_l    =  date("Y-m-d",trim($Result_l['end_time']));
							$end_h_l    =  date("H",trim($Result_l['end_time']));
							 $end_i_l    =  date("i",trim($Result_l['end_time']));
						}
					  $slc_h = "";
					  $slc_i = "";
					  $eslc_h = "";
					  $eslc_i = "";
						for($i=0;$i<=23;$i++){
							$slc_h .= "<option value='" . $i ."'";
							if($start_h_l==$i) $slc_h.= "selected";
							$slc_h .=">" . $i ."</option>";			
						}
					  for($i=0;$i<=59;$i++){
							$slc_i .= "<option value='" . $i ."'";
							if($start_i_l==$i) $slc_i.= "selected";
							$slc_i .=">" . $i ."</option>";			
						}
					  for($i=0;$i<=23;$i++){
							$eslc_h .= "<option value='" . $i ."'";
							if($end_h_l==$i) $eslc_h.= "selected";
							$eslc_h .=">" . $i ."</option>";			
						}
					  for($i=0;$i<=59;$i++){
							$eslc_i .= "<option value='" . $i ."'";
							if($end_i_l==$i) $eslc_i.= "selected";
							$eslc_i .=">" . $i ."</option>";			
						}
			
					  
					  echo "
					  <tr>
					  <td><input type='checkbox' value='" . $Result_l['adv_id'] . "' name='adv_ids[]' id='adv_ids'></td>
					  <td><img src='../UploadFile/AdvPic/" . $Result_l['adv_left_img'] . "' width=50><br>" . $Result_l['adv_left_img']."&nbsp;&nbsp;</td>
					 
					  <td>
					  <div style='margin-bottom:10px;'>起迄時間：<INPUT   id='begtime" . $Result_l['adv_id'] . "' size=10 value='" . $begtime_l . "'    onclick='showcalendar(event, this)' onFocus='showcalendar(event,this);if(this.value=='0000-00-00')this.value='''  name=begtime" . $Result_l['adv_id'] . " />
                              <select name='start_h" . $Result_l['adv_id'] . "' id='start_h" . $Result_l['adv_id'] . "'>
                               " . $slc_h . "
                                </select>
                              時
                              <select name='start_i" . $Result_l['adv_id'] . "' id='start_i" . $Result_l['adv_id'] . "'>" . $slc_i . "
                                </select>
                              分 至
                              <INPUT  id='endtime" . $Result_l['adv_id'] . "' size=10 value='" . $endtime_l . "'      onclick='showcalendar(event, this)' onFocus='showcalendar(event,this);if(this.value=='0000-00-00')this.value='''  name=endtime" . $Result_l['adv_id'] . " />
                              <select name='end_h" . $Result_l['adv_id'] . "'  id='end_h" . $Result_l['adv_id'] . "'>
                              " . $eslc_h . "
                                </select>
                              時
                              <select name='end_i" . $Result_l['adv_id'] . "' id='end_i" . $Result_l['adv_id'] . "'>
                               " . $eslc_i . "
                                </select>
                              分 
                              </div>
					  廣告超連結：<input name='adv_left_url" . $Result_l['adv_id'] . "' id='adv_left_url" . $Result_l['adv_id'] . "' value='" . $Result_l['adv_left_url'] . "' maxLength=200 size=40>
					  </td>
					  
					  
					 
						<td><a class='onebtn' href='javascript:textadv(" .$Result_l['adv_id']   . ")'>修改</a></td>
						<td colspan=5><a class='onebtn' href='javascript:deladv(" .$Result_l['adv_id']   . ")'>刪除</a><br></td>
					  </tr>";
				  }
				  ?>
				  
				  </table>
				  
				  <?php } ?>
                </p>
              </div>
              <div id="adv_index22">
			  <br>
			  +新增單張廣告<br><br>
			  
		
				廣告標題：<?php echo $FUNCTIONS->Input_Box('text','adv_title',$adv_title,"      maxLength=60 size=60 ")?><br><br>
				
			
			  			<input name="oldima2" type="hidden"  id='oldima2' value="<?php echo $adv_left_img;?>">
                  廣告圖片：<input name="ima2" type="file"  id='ima2'><br>
                  <?php if($adv_id>0){?>
				  <br /><img src="../UploadFile/AdvPic/<?php echo $adv_left_img;?>" width="100" />

				  <?php }?>
                  <br>
							
				<div>
                      
                            <div>起迄時間：<INPUT   id=begtime3 size=10 value="<?php echo $begtime?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime22 />
                              <select name="start_h22">
                                <?php
								for($i=0;$i<=23;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($start_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              時
                              <select name="start_i22">
                                <?php
								for($i=0;$i<=59;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($start_i==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              分 至
                              <INPUT    id=endtime3 size=10 value="<?php echo $endtime?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime22 />
                              <select name="end_h22">
                                <?php
								for($i=0;$i<=23;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($end_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              時
                              <select name="end_i22">
                                <?php
								for($i=0;$i<=59;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($end_i==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              分 <a href="#" class="easyui-tooltip" title="若不限制時間請將日期去掉"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </div>
                            </div>			
						
                <br>
                  廣告超連結：<?php echo $FUNCTIONS->Input_Box('text','adv_left_url2',$adv_left_url,"      maxLength=200 size=60 ")?><br><br>
				  
                  <input type="button" name="advsave2" id="advsave2" value="新增廣告"><?php if($adv_id>0){?><input onclick="avascript:deladv(<?php echo $adv_id;?>)" type="button" name="advsave2" id="advsave2" value="刪除"><?php }?><br><br>

               
              </div>
              <div id="adv_index23">
			  <div>
                      
                            <div>起迄時間：<INPUT   id=begtime3 size=10 value="<?php echo $begtime?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime23 />
                              <select name="start_h23">
                                <?php
								for($i=0;$i<=23;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($start_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              時
                              <select name="start_i23">
                                <?php
								for($i=0;$i<=59;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($start_i==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              分 至
                              <INPUT    id=endtime3 size=10 value="<?php echo $endtime?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime23 />
                              <select name="end_h23">
                                <?php
								for($i=0;$i<=23;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($end_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              時
                              <select name="end_i23">
                                <?php
								for($i=0;$i<=59;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($end_i==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              分 <a href="#" class="easyui-tooltip" title="若不限制時間請將日期去掉"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </div>
                            </div>
                <p>
              <textarea name="adv_content" id="redactor" cols="30" rows="10" ><?php echo $adv_content;?></textarea>

  <!--<input type="button" name="advsave3" id="advsave3" value="儲存"><?php if($adv_id>0){?><input onclick="avascript:deladv(<?php echo $adv_id;?>)" type="button" name="advsave3" id="advsave3" value="刪除"><?php }?>-->

                </p>
              </div>
              <div id="adv_index24">
			  <div>
                      
                            <div>起迄時間：<INPUT   id=begtime3 size=10 value="<?php echo $begtime?>"    onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=begtime24 />
                              <select name="start_h24">
                                <?php
								for($i=0;$i<=23;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($start_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              時
                              <select name="start_i24">
                                <?php
								for($i=0;$i<=59;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($start_i==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              分 至
                              <INPUT    id=endtime3 size=10 value="<?php echo $endtime?>"      onclick="showcalendar(event, this)" onFocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''"  name=endtime24 />
                              <select name="end_h24">
                                <?php
								for($i=0;$i<=23;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($end_h==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              時
                              <select name="end_i24">
                                <?php
								for($i=0;$i<=59;$i++){
								?>
                                <option value="<?php echo $i;?>" <?php if($end_i==$i) echo "selected";?>><?php echo $i;?></option>
                                <?php
								}
								?>
                                </select>
                              分 <a href="#" class="easyui-tooltip" title="若不限制時間請將日期去掉"><img src="images/tip.png" width="16" height="16" border="0"></a>
                              </div>
                            </div>
                <p>
                  <textarea name="adv_content4" cols="50" rows="10" id="adv_content4"><?php echo $adv_content;?></textarea>
  <!--<input type="button" name="advsave4" id="advsave4" value="儲存"><?php if($adv_id>0){?><input onclick="avascript:deladv(<?php echo $adv_id;?>)" type="button" name="advsave4" id="advsave4" value="刪除"><?php }?>-->

                </p>
              </div>
              </td>
            </tr>
            <tr>
              <td align="left" bgcolor="#FFFFFF"><input type="button" name="saveall" id="saveall" value="儲存"/>&nbsp;<input type="button" name="" id="" value="刪除全部廣告" onclick="deladvgrid();" /></td>
            </tr>
          </table></form>
      </div>
      <div  id="divlistStorage" align="center"> </div></td>
  </tr>
</table>
<script language="javascript">
jQuery(document).ready(function() {
    //必须为提交按钮绑定click事件，手动去更新----begin-----
    jQuery('#advsave3').click(function(){
     //需要手动更新CKEDITOR字段
     for ( instance in CKEDITOR.instances )
       CKEDITOR.instances[instance].updateElement();
         return true;
    });
 //----------------------------------end----------------


})
function showAdv(type){
	$('#adv_index21').css("display","none");
	$('#adv_index22').css("display","none");
	$('#adv_index23').css("display","none");
	$('#adv_index24').css("display","none");
	$('#' + type).css("display","block");
}
showAdv('adv_index<?php echo $adv_type;?>');
function textadv(id){
//alert($('#end_i'+id).val());
	$.ajax({
		  url: "admin_indexseting_ajax_advsave.php",
		  data: "action=updateadv&adv_id=" + id + "&begtime=" + $('#begtime'+id).val() + "&start_h=" + $('#start_h'+id).val() + "&start_i=" + $('#start_i'+id).val() + "&endtime=" + $('#endtime'+id).val() + "&end_h=" + $('#end_h'+id).val() + "&end_i=" + $('#end_i'+id).val() + "&adv_left_url=" + $('#adv_left_url'+id).val(),
		  type:'get',
		  dataType:"html",
		  success: function(msg){
			 // alert(msg)
			  closeWin();showWin('url','admin_indexseting_ajax_adv.php?tag=<?php echo $_GET['tag'];?>','',850,450);
		  }
	});
}	
function deladv(id){

	$.ajax({
		  url: "admin_indexseting_ajax_advsave.php",
		  data: "action=del&adv_id=" + id + "",
		  type:'get',
		  dataType:"html",
		  success: function(msg){
			  closeWin();showWin('url','admin_indexseting_ajax_adv.php?tag=<?php echo $_GET['tag'];?>','',850,450);
		  }
	});
}
function deladvgrid(){
	$.ajax({
		  url: "admin_indexseting_ajax_advsave.php",
		  data: "action=delgrid&tag=<?php echo $_GET['tag'];?>",
		  type:'get',
		  dataType:"html",
		  success: function(msg){
			  
			  closeWin();showWin('url','admin_indexseting_ajax_adv.php?tag=<?php echo $_GET['tag'];?>','',850,450);
		  }
	});
}

var options_s = {
	success:       function(msg){
		
					if (msg==1){
						closeWin();showWin('url','admin_indexseting_ajax_adv.php?tag=<?php echo $_GET['tag'];?>','',850,450);
					}else{
						alert(msg);
					}
				},
	type:      'post',
	dataType:  'html',
	clearForm: false
};
$("#advsave").click(function(){$('#advform').ajaxSubmit(options_s);});
$("#advsave2").click(function(){$('#advform').ajaxSubmit(options_s);});
$("#advsave3").click(function(){$('#advform').ajaxSubmit(options_s);});
$("#advsave4").click(function(){$('#advform').ajaxSubmit(options_s);});
$("#saveall").click(function(){$('#inpsaveall').val("1");$('#advform').ajaxSubmit(options_s);});
</script>