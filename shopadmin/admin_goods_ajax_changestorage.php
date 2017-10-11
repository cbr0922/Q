<?php

include_once "Check_Admin.php";

$gid = intval($_GET['gid']);

$Query = $DB->query("select goodsname,good_color,good_size from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)." limit 0,1");

$Num   = $DB->num_rows($Query);

$Result= $DB->fetch_array($Query);

$good_color       =  $Result['good_color'];

$good_size        =  $Result['good_size'];

?>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<script language="javascript" type="text/javascript" src="../js/jquery/jquery.form.js"></script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><a href="javascript:void(0);" onClick="$('#divlistStorage').css('display','none');$('#divchangeStorage').css('display','block');"><i class="icon-gear" style="font-size:14px;color:#666"></i> 調整庫存</a>&nbsp;&nbsp; | &nbsp;&nbsp;<a href="javascript:void(0);" onClick="getStoragelist();"><i class="icon-file-text" style="font-size:14px;color:#666"></i> 庫存最近記錄</a>&nbsp;&nbsp; | &nbsp;&nbsp;<a href="javascript:void(0);" onClick="getshoplist();"><i class="icon-user" style="font-size:14px;color:#666"></i> 訂購人紀錄 </a></td>
  </tr>
  <tr>
    <td><div align="center" id="divchangeStorage"><br>
        <form action="admin_goods_ajax_changestorage_save.php"  method="post" name="storageForm" id="storageForm">
          <INPUT type=hidden name=gid value="<?php echo $gid?>">
          <INPUT type=hidden name=action value="save">
          <table width="480" border="0" cellspacing="1" cellpadding="5" bgcolor="#CCCCCC">
            <tr>
              <td width="85" align="left" bgcolor="#FFFFFF">&nbsp;</td>
              <td width="372" align="left" bgcolor="#FFFFFF"><select name="goodstype" id="storage_goodstype" onChange="showGoods()">
                  <option value="1">商品庫存</option>
                  <option value="2">屬性庫存</option>
                  <option value="3">詳細資料庫存</option>
                </select>
                <br>
                （調整屬性庫存或詳細資料庫存會同時自動調整商品庫存） </td>
            </tr>
            <tr>
              <td colspan="2" align="left" bgcolor="#FFFFFF"><div id="divshowcolor">顏    色
                  <select name="color" id="storage_color" onChange="getStorage()">
                    <?php

		if (trim($good_color)!=""){

			$Good_color_array    =  explode(',',trim($good_color));



			if (is_array($Good_color_array)){

				foreach($Good_color_array as $k=>$v )

				{

					$Good_Color_Option .= "<option value='".$v."'>".$v."</option>\n";

				}

			}else{

				$Good_Color_Option = "<option value='".$v."'>".$v."</option>\n";

				$Good_color_array = array();

			}

		}else{

			$Good_Color_Option = "<option value=''>無</option>\n";

			$Good_color_array = array("");

		}

		echo $Good_Color_Option;

	   ?>
                  </select>
                </div></td>
            </tr>
            <tr>
              <td colspan="2" align="left" bgcolor="#FFFFFF"><div id="divshowsize">尺    寸
                  <select name="size" id="storage_size" onChange="getStorage()">
                    <?php

		if (trim($good_size)!=""){

			$Good_size_array    =  explode(',',trim($good_size));



			if (is_array($Good_size_array)){

				foreach($Good_size_array as $k=>$v ){

					$Good_Size_Option .= "<option value='".$v."'>".$v."</option>\n";

				}

			}else{

				$Good_Size_Option = "<option value='".$v."'>".$v."</option>\n";

				$Good_size_array = array("");

			}

		}else{

			$Good_Size_Option = "<option value=''>無</option>\n";

			$Good_size_array = array("");

		}

		echo $Good_Size_Option;

	?>
                  </select>
                </div></td>
            </tr>
            <tr>
              <td colspan="2" align="left" bgcolor="#FFFFFF"><div id="divshowdetail">詳細資料
                  <select name="detail_id" id="storage_detail_id" onChange="getStorage()">
                    <?php

        $detail_Sql      = "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . intval($gid) . "' order by detail_id desc ";

		$detail_Query    = $DB->query($detail_Sql);

		$detail_Num      = $DB->num_rows($detail_Query);

		if($detail_Num>0){

			while ($detail_Rs=$DB->fetch_array($detail_Query)) {

				echo "<option value='" . $detail_Rs['detail_id'] . "'>" . $detail_Rs['detail_name'] . "</option>";

			}

		}else{

			echo "<option value=''>無</option>\n";

		}

		?>
                  </select>
                </div></td>
            </tr>
            <tr>
              <td align="right" bgcolor="#FFFFFF">現有庫存</td>
              <td align="left" bgcolor="#FFFFFF"><div id="divshowstorage"></div></td>
            </tr>
            <tr>
              <td align="right" bgcolor="#FFFFFF">調整類型</td>
              <td align="left" bgcolor="#FFFFFF"><select name="storagetype" id="storagetype">
                  <option value="0">增加</option>
                  <option value="1">減少</option>
                </select></td>
            </tr>
            <tr>
              <td align="right" bgcolor="#FFFFFF">調整數量</td>
              <td align="left" bgcolor="#FFFFFF"><INPUT name=count id=count type=text value="" size="10" maxlength="10"></td>
            </tr>
            <tr>
              <td align="right" bgcolor="#FFFFFF">銷售數量</td>
              <td align="left" bgcolor="#FFFFFF"><INPUT name=sales id=sales type=text value="" size="10" maxlength="10"></td>
            </tr>
            <tr>
              <td align="right" bgcolor="#FFFFFF">備註</td>
              <td align="left" bgcolor="#FFFFFF"><label>
                  <textarea name="content" id="content" cols="45" rows="5"></textarea>
                </label></td>
            </tr>
            <tr>
              <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
              <td align="left" bgcolor="#FFFFFF"><label>
                  <input type="button" name="storageSave" id="storageSave" value="保存">
                  <input type="button" name="button2" id="button2" value="返回" onclick="closeWin();">
                </label></td>
            </tr>
          </table>
        </form>
      </div>
      <div  id="divlistStorage" align="center"> </div></td>
  </tr>
</table>
<script language="javascript">

function getStoragelist(){

	$('#divlistStorage').css("display","block");

	$('#divchangeStorage').css("display","none");

	$.ajax({

		  url: "admin_goods_ajax_changestoragelist.php",

		  data: "gid=<?php echo $gid;?>",

		  type:'get',

		  dataType:"html",

		  success: function(msg){

		  //alert(msg);

			  $('#divlistStorage').html(msg);

			  //$('#classcount').attr("value",counts+1);

			  //$(msg).appendTo('#extclass')

		  }

	});



}



function getshoplist(){

	$('#divlistStorage').css("display","block");

	$('#divchangeStorage').css("display","none");

	$.ajax({

		  url: "admin_goods_ajax_changestorageshoplist.php",

		  data: "gid=<?php echo $gid;?>",

		  type:'get',

		  dataType:"html",

		  success: function(msg){

		  //alert(msg);

			  $('#divlistStorage').html(msg);

			  //$('#classcount').attr("value",counts+1);

			  //$(msg).appendTo('#extclass')

		  }

	});



}



function showGoods(){

	//alert($('#goodstype').val());

	if ($('#storage_goodstype').val()==1){

		$('#divshowsize').css("display","none");

		$('#divshowcolor').css("display","none");

		$('#divshowdetail').css("display","none");

	}

	if ($('#storage_goodstype').val()==2){

		$('#divshowsize').css("display","block");

		$('#divshowcolor').css("display","block");

		$('#divshowdetail').css("display","none");

	}

	if ($('#storage_goodstype').val()==3){

		$('#divshowsize').css("display","none");

		$('#divshowcolor').css("display","none");

		$('#divshowdetail').css("display","block");

	}

	getStorage();



}

function getStorage(){

  $('#divshowstorage').html("");

  $('#sales').val("");

	$.ajax({

		  url: "admin_goods_ajax_changestorage_save.php",

		  data: "action=get&gid=<?php echo $gid;?>&goodstype=" + $('#storage_goodstype').val() + "&detail_id=" + $('#storage_detail_id').val() + "&color=" + encodeURIComponent($('#storage_color').val()) + "&size=" + encodeURIComponent($('#storage_size').val()) + "",

		  type:'get',

		  dataType:"json",

		  success: function(msg){

		  //alert(msg);

			  $('#divshowstorage').html(msg.storage);

        $('#sales').val(msg.sales);

			  //$('#classcount').attr("value",counts+1);

			  //$(msg).appendTo('#extclass')

		  }

	});

}

showGoods();

var options_s = {

	success:       function(msg){

					if (msg==1){

						//alert(msg);

						//closeWin();

							getStoragelist();





					}else{

						alert(msg);

					}

				},

	type:      'post',

	dataType:  'html',

	clearForm: false

};

$("#storageSave").click(function(){$('#storageForm').ajaxSubmit(options_s);});





</script>
