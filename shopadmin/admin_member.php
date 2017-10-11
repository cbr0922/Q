<?php
include_once "Check_Admin.php";
include Classes . "/ajax.class.php";
$Ajax      = new Ajax();
$InitAjax  = $Ajax->InitAjax();
include_once 'crypt.class.php';
/**
 *  装载语言包
 */
include "../language/".$INFO['IS']."/Admin_Member_Pack.php";

if ($_GET['user_id']!="" && $_GET['Action']=='Modi'){
	$User_id = intval($_GET['user_id']);
	$Action_value = "Update";
	$UserNameAction = " disabled ";
	$Action_say  = $JsMenu[Member_Modi]; //修改
	$Query = $DB->query("select * from `{$INFO[DBPrefix]}user` where user_id=".intval($User_id)." limit 0,1");
	$Num   = $DB->num_rows($Query);

	if ($Num>0){
		$Result= $DB->fetch_array($Query);
		$username       =  $Result['username'];
		$password       =  $Result['password'];
		$true_name      =  $Result['true_name'];
		$sex            =  $Result['sex'];
		$born_date      =  $Result['born_date'];
		$Country           =  $Result['Country'];
		$city           =  $Result['city'];
		$canton         =  $Result['canton'];
		$email          =  $Result['email'];
		$addr           =  $Result['addr'];
		$fax            =  $Result['fax'];
		$post           =  $Result['post'];
		$zip            =  $Result['zip'];
		$tel            =  MD5Crypt::Decrypt ($Result['tel'], $INFO['tcrypt'] );
		$other_tel      =  MD5Crypt::Decrypt ($Result['other_tel'], $INFO['mcrypt'] );
		$reg_date       =  $Result['reg_date'];
		$reg_ip         =  $Result['reg_ip'];
		$member_point   =  $Result['member_point'];
		$advance        =  $Result['advance'];
		$user_level     =  $Result['user_level'];
		$vloid          =  $Result['vloid'];
		$cert           =  $Result['certcode'];
		$user_state     =  $Result['user_state'];
		$huaiyun     =  $Result['huaiyun'];
		$huaiyunzhou     =  $Result['huaiyunzhou'];
		$baobaoshu     =  $Result['baobaoshu'];
		$schoolname     =  $Result['schoolname'];
		$chenghu     =  $Result['chenghu'];
		$salepoint     =  $Result['salepoint'];
		$levelpoint     =  $Result['levelpoint'];
		$isold     =  $Result['isold'];
		$dianzibao     =  $Result['dianzibao'];
		$memberno     =  $Result['memberno'];
		$recommendno     =  $Result['recommendno'];
		$companyid     =  $Result['companyid'];
		$pic     =  $Result['pic'];
		$nickname     =  $Result['nickname'];
		$msn     =  $Result['msn'];
		$blog     =  $Result['blog'];
		$cn_secondname   = $Result['cn_secondname'];
		$en_firstname   = $Result['en_firstname'];
		$en_secondname   = $Result['en_secondname'];
		$bornCountry   = $Result['bornCountry'];
	}else{
		echo "<script language=javascript>javascript:window.history.back();</script>";
		exit;
	}
}else{
	$Action_value = "Insert";
	$UserNameAction = "  ";
	$Action_say   = $JsMenu[Member_Add]; //添加
	$reg_date     = date("Y-m-d",time());
	$reg_ip       = $FUNCTIONS->getip();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>
<?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;<?php echo $Action_say;?>
</TITLE>
</HEAD>
<script src="../js/area.js" type="text/javascript" charset="utf-8"></script>
 <?php if ( VersionArea == "gb" ) {
 	$Onload =  " onLoad=\"createMenus('".$city."','".$canton."','','')\"  ";
 }else{
 	$Onload =  " onload=\"addMouseEvent();\"";
 }
 ?>
<script language="javascript">
function Order_history(){
	document.form1.action = "Member_HistoryList.php";
	document.form1.method ="get";
	document.form1.target ="_blank";
	document.form1.submit();
}
</script>
<SCRIPT language=javascript>
	function checkform(){
        <?php if ($Action_value == "Insert") { ?>
		if (chkblank(form1.username.value)){
			alert('<?=$Admin_Member[PleaseInputAcc]?>'); //請輸入帳號！!
			form1.username.focus();
			return;
		}
        <?php } ?>
		/*
		if (chkblank(form1.true_name.value) ){
			alert('<?=$Admin_Member[PleaseInputTrueName]?>'); //请输入姓名!
			form1.true_name.focus();
			return;
		}*/
		
		if (chkblank(form1.en_firstname.value) || chkblank(form1.en_secondname.value) ){
			alert('請輸入英文姓名'); //请输入姓名!
			form1.en_firstname.focus();
			return;
		}
		document.form1.method ="post";
		document.form1.target ="_self";
		document.form1.action = "admin_member_save.php";
		form1.submit();
}
</script>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  <?php echo  $Onload ?> >
<?php include_once "head.php";?>
<div id="contain_out">
<?php include_once "Order_state.php";?>
  <FORM name=form1 action='admin_member_save.php' method=post enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">
    <TABLE class=p9black cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD width="50%">
              <TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
                <TBODY>
                  <TR>
                    <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif"  width=32></TD>
                    <TD class=p12black noWrap>
                      <SPAN  class=p9orange><?php echo $JsMenu[Functions];//功能?>--&gt;<?php echo $JsMenu[Member_Man];//会员管理?>--&gt;<?php echo $Action_say;?></SPAN></TD>
                  </TR></TBODY></TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
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
                                  <TD vAlign=bottom noWrap class="link_buttom">
                            <a href="admin_member_list.php"><IMG  src="images/<?php echo $INFO[IS]?>/fb-cancel.gif" border=0>&nbsp;<?php echo $Basic_Command['Cancel'];//取消?></a></TD></TR></TBODY></TABLE><!--BUTTON_END--></TD></TR></TBODY></TABLE></TD>
                  <TD align=middle>
                    <TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a></TD></TR></TBODY></TABLE><!--BUTTON_END-->

                          </TD></TR></TBODY></TABLE>

                  </TD></TR></TBODY></TABLE>
            </TD>
          </TR>
      </TBODY>
        </TABLE>
                <TABLE class=allborder cellSpacing=0 cellPadding=2  width="100%" align=center bgColor=#f7f7f7 border=0>
             <TR>
             <TD>
             <table width="828" border="0" cellspacing="0" cellpadding="0" style="margin-top:20px;margin-left:50px">
                 <tr>
                 <td width="140" height="35">購買總金額：
                  <span class="p9orange"><?php
									$Sql_t = "select user_id,sum(goodscount*price) as total from `{$INFO[DBPrefix]}order_detail` od left join  `{$INFO[DBPrefix]}order_table` ot on (od.order_id=ot.order_id) where detail_order_state=4 and detail_pay_state=1 and user_id='" . $User_id . "' group by ot.user_id";
					   $Query_t = $DB->query($Sql_t);
					   $Rs_t=$DB->fetch_array($Query_t);
					   echo intval($Rs_t['total']);
					   ?></span></td>
                                <td width="140">紅利點數：
                                  <span class="p9orange"><a href="admin_bonusrecord_list.php?user_id=<?php echo $User_id?>"><?php
						echo $point =$FUNCTIONS->Userpoint(intval($User_id),1);
						?></a></span></td>
                                <td width="140">等級積分：
                                  <span class="p9orange"><a href="admin_bonuslevelrecord_list.php?user_id=<?php echo $User_id?>"><?php
						echo $point =$FUNCTIONS->Userpoint(intval($User_id),2);
						?></a></span></td>
                                <td width="190"><?php echo $Admin_Member[RegTime];//注册时间?>
                            ：<?php echo $reg_date ;?></td>
                                <td width="218">最後登入時間：
                                <?php
                            $sql_log = "select * from  `{$INFO[DBPrefix]}user_log` where user_id = '" . trim($user_id) . "' order by logintime desc";
							$Query_log    = $DB->query($sql_log);
							$Num_log      = $DB->num_rows($Query_log);
							if($Num_log>0){
								$Rs_log = $DB->fetch_array($Query_log);
								echo date("Y-m-d H:i:s",$Rs_log['logintime']);
							}
							?></td>

               </tr>
                              </table>
        <TABLE cellSpacing=0 cellPadding=2  width="78%" align=center border=0>
           <TBODY>
             <TR>
               <TD colspan="3" align=left noWrap></TD>
                            </TR>

                          <TR>
                            <TD align=left noWrap>&nbsp;</TD>
                            <TD colspan="2" align=left noWrap>&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD width="12%" align=right noWrap>帳　　號： </TD>
                            <TD width="44%" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','username',$username,"  onchange='getUserName(this.value)'  maxLength=250 size=40  $UserNameAction ")?></TD>
                            <TD width="44%" align=left noWrap><div id='show_UsernameContent'>&nbsp;</div></TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right>會員編號：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $memberno;?>&nbsp;</TD>
                            </TR>
                          <TR>
                            <TD noWrap align=right><?php echo $Admin_Member[TrueName];//姓名?>：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','true_name',$true_name,"      maxLength=20 size=20  ")?>
							<!--<?php echo $FUNCTIONS->Input_Box('text','cn_secondname',$cn_secondname,"      maxLength=20 size=20  ")?>--></TD>
                            </TR>
                            <TR>
                            <TD noWrap align=right>英文姓名：</TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','en_firstname',$en_firstname,"  placeholder='姓'    maxLength=20 size=20  ")?>  <?php echo $FUNCTIONS->Input_Box('text','en_secondname',$en_secondname,"    placeholder='名'  maxLength=20 size=20  ")?></TD>
                            </TR>
                          <?php if ($Action_value == "Insert") { ?>

                          <TR>
                            <TD noWrap align=right><?php echo $Admin_Member[Password] ;?>： </TD>
                            <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','password',$password," maxLength=20 size=20 ")?></TD>
                            </TR>
                          <?php } ?>
                          <?php if ($_GET['user_id']!="" && $_GET['Action']=='Modi'){  ?>
                          <input type="hidden" name="user_id" value="<?php echo $User_id?>">
                        <input type="hidden" name="username" value="<?php echo $username?>">

                        <TR>
                          <TD noWrap align=right>&nbsp;</TD>

                          <TD colspan="2" align=left noWrap>

                            <!--input type="button" onClick="javascript:Order_history()" name="button" value="Order_history"-->
                          <button value="true"  type="button" name="button" id="valuesubmit" onClick="javascript:Order_history()" class="submit"><?php echo $Admin_Member[Order_history];?></button>					  </TD>
                          </TR>
                        <?php  } ?>

                        <TR>
                          <TD noWrap align=right>經銷商：</TD>
                          <TD colspan="2" align=left noWrap>
                            <select name="company">
                              <option value="">請選擇</option>
                              <?php


	$Sql      = "select u.*  from `{$INFO[DBPrefix]}saler` u order by u.id desc";
$Query    = $DB->query($Sql);
$Num_c      = $DB->num_rows($Query);
while ($Rs=$DB->fetch_array($Query)) {
	$company .="<option value=".$Rs['id']." ";
	if ($companyid == $Rs['id']){
		$company .= " selected";
	}
	$company .= " >".$Rs['name']."</option>\n";
}
echo $company;

					  ?></select>					  </TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>推薦人編號：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $recommendno;?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>暱　　稱：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','nickname',$nickname,"      maxLength=20 size=20  ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>性　　别：</TD>
                          <TD colspan="2" align=left noWrap>
                            <?php echo $FUNCTIONS->Input_Box('radio_strand','sex',intval($sex),$add = array($Admin_Member[Sex_men],$Admin_Member[Sex_women]))?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>護照號碼：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','cert',$cert,"      maxLength=10 size=20  ")?></TD>
                          </TR>

                        <TR>
                          <TD align=right noWrap>國　　籍：</TD>
                          <TD colspan="2" align=left noWrap><select id="county2" name="bornCountry">
                          </select></TD>
                        </TR>
                        <TR>
                          <TD align=right noWrap>地　　區：</TD>
                          <TD colspan="2" align=left noWrap>
                            <input name="othercity" id="othercity" size="5" value="">
                            <select id="county" name="county">
                              </select>
                            <select id="province" name="province">
                              </select>
                            <select id="city" name="city">
                              </select>					       </TD>
                          </TR>
                        <TR>
                          <TD align=right noWrap><?php echo $Admin_Member[Address];//联系地址?>：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','addr',$addr,"      maxLength=60 size=60  ")?></TD>
                        </TR>
                        <TR>
                          <TD noWrap align=right> <?php echo $Admin_Member[Born];//出生日期?>：</TD>
                          <TD colspan="2" align=left noWrap>
                            <input type="text" name="born_date" size="25" onclick="showcalendar(event, this)" onfocus="showcalendar(event,this);if(this.value=='0000-00-00')this.value=''" value="<?php echo $born_date;?>">


                            <?php //echo $FUNCTIONS->Input_Box('text','born_date',$born_date,"      maxLength=20 size=20 readonly ")?>    			  </TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right> <?php echo $Admin_Member[Email];//Email?>： </TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','email',$email,"      maxLength=30 size=30  ")?></TD>
                          </TR>




                        <TR>
                          <TD noWrap align=right><?php echo $Admin_Member[Mobile];//移动电话?>：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','other_tel',$other_tel," maxLength=20 size=20 ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right><?php echo $Admin_Member[Phone];//固定电话?>：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','tel',$tel,"      maxLength=20 size=20  ")?></TD>
                          </TR>

                        <!--TR>
                      <TD noWrap align=right> <?php echo $Admin_Member[Post];//邮政编码?> </TD>
                      <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','post',$post,"      maxLength=20 size=20  ")?>

                    <TR-->
                        <tr>
                          <TD noWrap align=right>傳　　真：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','fax',$fax,"      maxLength=20 size=20  ")?></TD>
                          </TR>
 <TR>
                          <TD align=right>圖　　片：</TD>
  <TD colspan="2"><INPUT  id="pic"  type="file" size="40" name="pic" ><INPUT type=hidden   name='old_pic'  value="<?php echo $pic?>"></TD>
                          </TR>
                        <?php if (is_file("../UploadFile/UserPic/".$pic)){?>
                        <TR>
                          <TD align=right \>&nbsp;</TD>
                          <TD colspan="2" class="p9orange"><img src="<?php echo "../UploadFile/UserPic/".$pic?>"><a href="admin_member_save.php?id=<?php echo $User_id;?>&pic=<?php echo $pic;?>&type=pic&Action=delPic"><i class="icon-trash" style="font-size:14px;margin-right:5px;margin-left:10px;color:#ff6600"></i>刪除圖片</a></TD>
                          </TR>
                        <?php
					}
					?>
                        <!--TR>
                          <TD noWrap align=right>Msn
                            ： </TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','msn',$msn,"      maxLength=20 size=20  ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>Blog
                            ： </TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','blog',$blog,"      maxLength=20 size=20  ")?></TD>
                          </TR-->
                        <TR>
                          <TD noWrap align=right> </TD>
                          <TD colspan="2" align=left noWrap></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right> <?php echo $Admin_Member[RegIP] ;//注册IP：?>：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','reg_ip',$reg_ip,"      maxLength=20 size=20  disabled ")?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right> <?php echo $Admin_Member[UserLevel];//会员等级?>：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->select_type("select * from `{$INFO[DBPrefix]}user_level` order by level_id asc ",'user_level','level_id','level_name',intval($user_level)); //$FUNCTIONS->Level_name($member_point)?></TD>
                          </TR>

                        <TR>
                          <TD noWrap align=right><?php echo $Admin_Member[Forum_vol_name]?>：<!--论坛等级名称-->   </TD>
                          <TD colspan="2"  align=left noWrap><?php echo $FUNCTIONS->select_type("select vloid,volname from `{$INFO[DBPrefix]}forum_vol` order by vloid asc ",'vloid','vloid','volname',intval($vloid)); ?></TD>
                          </TR>


                        <TR>
                          <TD noWrap align=right>狀態：</TD>
                          <TD colspan="2" align=left noWrap><?php echo $FUNCTIONS->Input_Box('radio_strand','user_state',intval($user_state),$add=array($Basic_Command['Open'],$Basic_Command['Close']))?></TD>
                          </TR>
                        <TR>
                          <TD noWrap align=right>電子報：</TD>
                          <TD colspan="2" align=left noWrap><input name="dianzibao" type="radio" value="1" <?php if($dianzibao==1) echo "checked";?> />
                            訂閱
                            <input type="radio" name="dianzibao" value="0" <?php if($dianzibao==0) echo "checked";?> />
                            取消</TD>
                          </TR>
                        <?php if ($_GET['user_id']!="" && $_GET['Action']=='Modi'){  ?>
                        <?php
					}
					?>
                        <TR>
                          <TD colspan="3" align=right noWrap>&nbsp;</TD>
                          </TR>
                    </TABLE>
               </TD>
        </TR>
        </TABLE>

  </FORM>
</div>
<div align="center"><?php include_once "botto.php";?></div>
</BODY>
</HTML>





<?php echo $InitAjax ;?>
 <script language="javascript">

 function getUserName(Username){

 	if (typeof(Username) == 'undefined'){
 		    return false;
 	}

 	var url = "Check_ajax.php?type=checkusername&username="+Username;

 	var show = document.getElementById("show_UsernameContent");

 	var ajax = InitAjax();
 	//alert(url);
 	ajax.open("GET", url, true);

 	ajax.onreadystatechange = function() {

 		    //如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
 		  if (ajax.readyState == 4 && ajax.status == 200) {
 		      show.innerHTML = ajax.responseText;
		  }else{
 		                		            		            		        		            		        		        		                      		        		    show.innerHTML = "";
 		                		            		            		        		            		        		        		                      		        	}
 		  ajax.send(null);
	}
 }
</script>
<script language="javascript">
iniArea("",1,"<?=$Country?>","<?=$canton?>","<?=$city?>");
iniArea("2",0,"<?=$bornCountry?>","","");	
</script>
