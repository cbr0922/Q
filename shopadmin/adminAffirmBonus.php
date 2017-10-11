<?php
include_once "Check_Admin.php";
include "../language/".$INFO['IS']."/Admin_Product_Pack.php";
include "../language/".$INFO['IS']."/Good.php";
$Abc_id       = $FUNCTIONS->Value_Manage($_GET['abc_id'],$_POST['Abc_id'],'back','');  //判断是否有正常的ID进入


$Sql = " select * from `{$INFO[DBPrefix]}bonuscoll_goods` where abc_id=".intval($Abc_id)." limit 0,1";
$Query     = $DB->query($Sql);
$Rs        = $DB->fetch_array($Query);
$Bonusnum  = $Rs['bonusnum'];
$User_id   = $Rs['user_id'];
$Goodsname = $Rs['goodsname'];
$User_id   = $Rs['user_id'];


/*提交了信息后的处理部分！
说明：这里需要注意的是当状态为取消的时候，必须已经扣除用户的积分，返还用户！
同时后台在多次取消的时候也不能无限制的返还！这里只需要判断提交需求是否是取消，
并且数据库中是否BONUSNUM为0，就可以知道是否是已经被取消过了！！
*/
if ($_POST[action]=='insert'){

	if (intval($_POST['askfor'])==2 && $Bonusnum>0){
		$DB->query(" update `{$INFO[DBPrefix]}user` set member_point=member_point+".intval($Bonusnum)." where user_id=".intval($User_id));
		$Changed_bonusnum=0;
	}else{
		$Changed_bonusnum=$Bonusnum;
	}


	$DB->query("update `{$INFO[DBPrefix]}bonuscoll_goods` set ssay='".$_POST['ssay']."',askfor='".$_POST['askfor']."',sidate='".time()."',bonusnum='".$Changed_bonusnum."' where abc_id=".intval($Abc_id));
	$FUNCTIONS->setLog("更改紅利兌換商品狀態");
	echo "
   <script language=javascript>
    window.opener.location.href=window.opener.location.href
    window.close();	
   </script>
   
   ";
	exit;
	//$FUNCTIONS->sorry_back("close","");



}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../css/css.css" rel="stylesheet" type="text/css">
<title></title>
</head>

<body>
<script language="javascript">
function shrink()
{
	window.resizeTo('640','480');
}

function expand()
{
	window.resizeTo('800','600');
}
 </script>

 
<table width="100%"  border="0" cellpadding="2" cellspacing="2" bgcolor="#FFFFCC">
  <tr>
    <td>&nbsp;&nbsp;<?php echo $Admin_Product[ProductName];//商品名?>:<?php echo $Goodsname?>&nbsp;&nbsp;[<a href='javascript:shrink()' style='text-decoration:none'>-</a>&nbsp;&nbsp;<a href='javascript:expand()' style='text-decoration:none'>+</a>] </td>
  </tr>
</table>
<form name="form1" method="post" action="">
<input type="hidden" name="action" value="insert">
<input type="hidden" name="Abc_id" value="<?php echo $Abc_id?>">
<TABLE  cellSpacing=1 cellPadding=4 width="100%" align=center  border=0  bgcolor="#BEC1C2" class=p9navyblue>
      <TBODY>
        <TR  bgcolor="#E4E3E1">
          <TD align=left nowrap bgcolor="#E4E3E1" class=p9rednothrough><?php echo $Good[Isay_say]?><!--提问内容-->&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date("Y-m-d H:i a",$Rs['idate'])?><!--提问时间：--><hr>            <?php echo nl2br($Rs['isay'])?> <!--提问内容--></TD>
        </TR>
        <TR  bgcolor="#FFFFFF">
          <TD align=left nowrap bgcolor="#FFFFFF"><?php echo $Good[Ssay_say]?>：&nbsp;&nbsp;&nbsp;<?php echo  $display = $Rs['sidate']!="" ?  date("Y-m-d H:i a",$Rs['sidate']) : "";?>            <hr><textarea name="ssay" cols="40" rows="6"><?php echo $Rs['ssay']?></textarea><!--店主回复-->            <!--回复时间：--></TD>
        </TR>
        <TR align="center"  bgcolor="#FFFFFF">
          <TD nowrap bgcolor="#FFFFFF"><?php echo $Good[IfDHBonus]?>：
          <?php if ($Rs[askfor]!=2 )  { ?><input type="radio" name="askfor" value="3" <?php if ($Rs[askfor]==3) { echo " checked ";}?>><!--是否兑换红利商品--><?php echo $Basic_Command['Yes']?><?php } ?>&nbsp;&nbsp;&nbsp;<?php if ($Rs[askfor]!=3 )  { ?><input name="askfor" type="radio" value="2" <?php if ($Rs[askfor]==2) { echo " checked ";}?>><?php echo $Basic_Command['No']?><?php } ?> </TD>
        </TR>
        <TR align="center"  bgcolor="#FFFFFF">
          <TD nowrap bgcolor="#FFFFFF"><input type="submit" name="Submit" value="<?php echo $Basic_Command['Save']?>"></TD>
        </TR>      
</TABLE>
</form>
</body>
</html>
