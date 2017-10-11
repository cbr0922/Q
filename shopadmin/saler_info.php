<?php
include_once "Check_Admin.php";
include_once "../language/".$INFO['IS']."/Desktop_Pack.php";

$Query = $DB->query("select * from `{$INFO[DBPrefix]}saler` where id=".intval($_SESSION['sa_id'])." limit 0,1");
$Num   = $DB->num_rows($Query);
  
  if ($Num>0){
  $Result= $DB->fetch_array($Query);
   $name            =  $Result['name'];
   $password          =  $Result['password'];
   $tel             =  $Result['tel'];
   $addr           =  $Result['addr'];
   $email   =  $Result['email'];
   $bankuser             =  $Result['bankuser'];
   $bankname            =  $Result['bankname'];
   $bank         =  $Result['bank'];
   $ifpub              =  $Result['ifpub'];   
   $login           =  $Result['login'];
	$pubtime           =  $Result['pubtime'];
	$salerset           =  $Result['salerset'];
   

  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<LINK href="css/theme.css" type=text/css rel=stylesheet>
<LINK href="css/css.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome-ie7.css" type=text/css rel=stylesheet>
<LINK href="css/font-awesome/css/font-awesome.css" type=text/css rel=stylesheet>
<LINK href="css/title_style.css" type=text/css rel=stylesheet>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>經銷商 --> 經銷商資料</TITLE>
<SCRIPT language=javascript>
	function checkform(){

		if (chkblank(form1.name.value)){
			form1.name.focus();
			alert('請輸入經銷商名稱');  //请选择供應商名稱			
			return;
		}

		
		if (chkblank(form1.password.value)){
			form1.password.focus();
			alert('請輸入登入密碼');  //请输入登入密碼
			return;
		}

			
		form1.submit();
	}

</SCRIPT>
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginheight="0" marginwidth="0"  onload="addMouseEvent();">
<?php  include $Js_Top ;  ?> 
  <FORM name=form1 action='saler_info_save.php' method=post enctype="multipart/form-data">
  <input type="hidden" name="Action" value="<?php echo $Action_value?>">

<TABLE height=9 cellSpacing=0 cellPadding=0 width="100%" background=./images/<?php echo $INFO[IS]?>/menubo.gif border=0>
  <TBODY>
  <TR>
    <TD height=9><IMG height=9 src="./images/<?php echo $INFO[IS]?>/spacer.gif"   width=778></TD></TR></TBODY></TABLE>
<TABLE height=15 cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD>&nbsp;</TD></TR></TBODY></TABLE>
<TABLE height=400 cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD vAlign=top align=right width="70%">
      
      <TABLE width="90%" border=0 align="center" cellPadding=0 cellSpacing=0 class=p9black>
        <TBODY>
          <TR>
            <TD width="50%"><TABLE width="90%" border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
                <TR>
                  <TD width=38><IMG height=32 src="images/<?php echo $INFO[IS]?>/program-1.gif" 
                  width=32></TD>
                  <TD class=p12black noWrap><SPAN  class=p9orange>經銷商管理--&gt;經銷商資料</SPAN></TD>
                  </TR>
                </TBODY>
              </TABLE></TD>
            <TD align=right width="50%"><TABLE cellSpacing=0 cellPadding=0 border=0>
                <TBODY>
                  <TR>
                    <TD align=middle><TABLE height=33 cellSpacing=0 cellPadding=0 width=79 border=0>
                      <TBODY>
                        <TR>
                          <TD align=middle width=79><!--BUTTON_BEGIN-->
                            <TABLE>
                              <TBODY>
                                <TR>
                                  <TD vAlign=bottom noWrap class="link_buttom"><a href="javascript:checkform();"><IMG src="images/<?php echo $INFO[IS]?>/fb-save.gif" border=0 >&nbsp;<?php echo $Basic_Command['Save'];//保存?></a>&nbsp; </TD>
                                  </TR>
                                </TBODY>
                              </TABLE>
                            <!--BUTTON_END--></TD>
                          </TR>
                        </TBODY>
                      </TABLE></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
          <TR>
            <TD vAlign=top bgColor=#ffffff height=300><TABLE class=allborder cellSpacing=0 cellPadding=2  width="90%" align=center bgColor=#f7f7f7 border=0>
              <TBODY>
                <TR>
                  <TD noWrap align=right width="18%">&nbsp;</TD>
                  <TD colspan="4" align=right noWrap>&nbsp;</TD>
                  </TR>
                <TR>
                  <TD noWrap align=right width="18%">經銷商名稱：</TD>
                  <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','name',$name,"      maxLength=50 size=50 ")?></TD>
                  </TR>
                <TR>
                  <TD noWrap align=right>經銷商帳號：</TD>
                  <TD width="38%" align=left noWrap><?php echo $login?></TD>
                  <TD width="8%" align=right noWrap>&nbsp;</TD>
                  <TD colspan="2" align=left noWrap>&nbsp;</TD>
                  </TR>
                <TR>
                  <TD noWrap align=right>登入密碼：</TD>
                  <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','password',$password,"      maxLength=50 size=50  ")?></TD>
                  <TD align=right noWrap>&nbsp;</TD>
                  <TD colspan="2" align=left noWrap>&nbsp;</TD>
                  </TR>
                
                <TR>
                  <TD noWrap align=right>電話：</TD>
                  <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','tel',$tel,"      maxLength=20 size=20 ")?></TD>
                  </TR>
                <TR>
                  <TD noWrap align=right>地址：</TD>
                  <TD align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','addr',$addr,"      maxLength=50 size=50 ")?></TD>
                  <TD align=right noWrap>&nbsp;</TD>
                  <TD colspan="2" align=left noWrap>&nbsp;</TD>
                  </TR>
                <TR>
                  <TD noWrap align=right>電子信箱：</TD>
                  <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','email',$email,"      maxLength=50 size=50 ")?></TD>
                  </TR>
                <TR>
                  <TD noWrap align=right width="18%">銀行戶名：</TD>
                  <TD colspan="4" align=left noWrap><?php echo $FUNCTIONS->Input_Box('text','bankuser',$bankuser,"      maxLength=50 size=50 ")?></TD>
                  </TR>
                <TR>
                  <TD align=right >銀行名稱：</TD>
                  <TD colspan="4"><?php echo $FUNCTIONS->Input_Box('text','bankname',$bankname,"      maxLength=50 size=50 ")?></TD>
                  </TR>
                <TR>
                  <TD width="18%" align=right valign="top" noWrap>銀行帳號：</TD>
                  <TD colspan="3" align=left valign="top" noWrap><?php echo $FUNCTIONS->Input_Box('text','bank',$bank,"      maxLength=50 size=50 ")?></TD>
                  <TD width="9%" align=left noWrap>&nbsp;</TD>
                  </TR>
                
                <TR>
                  <TD noWrap align=right>&nbsp;</TD>
                  <TD colspan="4" align=right noWrap>&nbsp;</TD>
                  </TR>
                </TBODY>
              </TABLE></TD>
            </TR>
          </TBODY>
      </TABLE></TD></TR></TBODY></TABLE></form></BODY></HTML>
