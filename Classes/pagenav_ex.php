<?php
/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
  +------------------------------------------------------------+
  | Filename.......: pagenav.php                               |
  | Project........: 大白菜芯 翻 页                              |
  | Version........: 2.0.0                                     |
  | Last Modified..: 2003-01-16                                |
  +------------------------------------------------------------+
  | Author.........: Hyeo <java@cu165.com>                     |
  | Homepage.......: http://tjsohu.com                         |
  | Support........: http://tjsohu.com                         |
  +------------------------------------------------------------+
  | Copyright (C) 2004 tjsohu.com Team. All rights reserved.   |
  +------------------------------------------------------------+
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */
error_reporting(7);

class NavFunction{   //这个类里放的基本都是翻页函数功能应用的函数

/*********************************************************************************************************
本函数主要功能是格式化一个多行多列的表格。同时对于$this->$Sub_FuncName($rs,$InValue)方法，可以指定一个循环区间做为
内容来循环。具体应用很多。下边我将每个参数都叙述一遍。
$PUBLIC：是导入的数据库类；
$sql：外部指定的SQL语句；
$lieshu：控制数据分列的个数；
$Table_css,$Td_css,$Font_css：这3个分别是表的CSS属性控制。
$limit：控制数据个数。
$Max_limit：这里为了控制显示是否出现最大个数；
$Sub_FuncName,$InValue：这里将指定具体出现的子方法名称及导入的值。
整体函数将最后产生一个返回值！
**********************************************************************************************************/


function row_nav_return($PUBLIC,$sql,$lieshu,$Table_css,$Td_css,$Font_css,$limit,$Max_limit,$Sub_FuncName,$InValue)
     {
      global $Nav,$MemberLanguage_Pack,$Order_Pack,$Basic_Command;
	  $query=$PUBLIC->query($sql);
      $num = $PUBLIC->num_rows($query);
      $Table_Nav="";  	  
	  if($num>0) {
      
	  /* 如果设置了最大的结束数字.这里就是为了做集中首页显示用的*/
	  if (intval($limit)<=intval($Max_limit) ){
         $num = $num>intval($Max_limit)? $Max_limit : $num;
      }else{
	  $Nav->total_result=$num;
	  $Nav->execute($sql,$limit);
      $query = $Nav->sql_result;
	  }
	    
	  $Table_Nav .= "\n<TABLE ".$Table_css.">\n";
      
	    $zongshu=$num; 
        $hangshu= ceil($zongshu/$lieshu) ;
        $hstar=0;

    	while ($hstar<$hangshu){

     	  $Table_Nav .= "<TR>\n";
	   	 
	      $lie=0; 
  	    	while ((($lie<$lieshu) and $rs=$PUBLIC->fetch_array($query))) //当列数小于应分配值，并且数据不为空的时候。
		      {
		        $Table_Nav .= "<td ".$Td_css.">\n";
				/*-----------------------
				这里放内容!
				-----------------------*/
				
				$Table_Nav .= $this->$Sub_FuncName($PUBLIC,$rs,$InValue);// 将记录集于传入的数据放到指定的子函数中处理。
				
				$Table_Nav .= "</td>\n";

    	        $lie=$lie+1; 
        	  } //结束了对列的循环
        
		  $Table_Nav .= "</TR>\n";
 
		 $hstar=$hstar+1;
			
        }  //结束了对行的循环  
       // echo "<tr height=1 bgcolor=".$trbgcolor."><td nowrap></td></tr>";
    if ($limit>intval($Max_limit)){

    $Table_Nav .= "<tr>\n<td align=left colspan=".$lieshu." >".$Nav->pagenav()."</td>\n</tr>\n";

	}

    $Table_Nav .= "</TABLE>\n";

    }else{
   $Font_css = $Font_css!="" ? $Font_css : " class=p9v ";
   $Table_Nav .= "<br><br><br><br><center><font ".$Font_css.">".$Basic_Command['NullDate']."</font></center>";  //没有相关资料!

   }
$Table_Nav = str_replace("<TR>\n</TR>","",$Table_Nav);
return $Table_Nav;
}



function Collection_list($Public,$Result,$Value){
global $INFO,$Nav,$MemberLanguage_Pack,$Order_Pack,$Basic_Command,$Good;
$Table = "";

$Table .=" 

<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0 class='p14table'>
              <TBODY>
              <TR>
                <TD class=dotY vAlign=top align=middle width='41%'>
				<a href=../product/detail".$Result['gid'].">
				<IMG   src=\"../".$INFO['good_pic_path']."/".$Result['smallimg']."\"   width=120 border=0></A></TD>
                <TD vAlign=top width='69%'>
                  <TABLE height=57 cellSpacing=0 cellPadding=2 width='100%'  border=0>
                    <TBODY>
                    <TR>
                      <TD class=p14red height=11>".$Result['goodsname']."</TD></TR>
                    <TR>
                      <TD class=p9v vAlign=top>
                        <TABLE class=p9v cellSpacing=0 cellPadding=0   width='100%' border=0>
                          <TBODY>
                          <TR>
                            <TD class=p9v  vAlign=top>".$Result['intro']."</TD>
	                      </TR>
	                      </TBODY>
	                    </TABLE>
	                   </TD>
	               </TR>
                    <TR>
                      <TD class=p9black vAlign=top>
                        <TABLE class=p9v cellSpacing=0 cellPadding=0 width='67%'   border=0>
                          <TBODY>
                          <TR>
                            <TD class=p9navyblue width='59%'>
                              <TABLE height=57 cellSpacing=0 cellPadding=2   width='100%' border=0>
                                <TBODY>
                                <TR>
                                <TD class=p9navyblue vAlign=top noWrap>".$Good[yprice_say]."：<SPAN  class=p9navyblue><s>".$Result['price']."</s></SPAN></TD></TR>
                                
";
    $Sql_level   = "select * from `{$INFO[DBPrefix]}user_level` u inner join `{$INFO[DBPrefix]}member_price` m on (m.m_goods_id=".$Result['gid']." && u.level_id=m.m_level_id)";   
	$Query_level = $Public->query($Sql_level);
	$Num_level = $Public->num_rows($Query_level);
	if ($Num_level>0){
		while ($Result_level=$Public->fetch_array($Query_level))
		{
			if ($Result_level['level_name'] == $_SESSION['userlevelname']){

$Table .="	
                    <TR>
                      <TD class=p9orange vAlign=top noWrap>".$Result_level['level_name']."".$Good[PriceIs]."：<SPAN class=p14red>".$Result_level['m_price']."</SPAN></TD>
					</TR>
";
			}
		}
	}else{  //如果没有会员价格！将当前价格定义为会员价格！
	
$Table .="	
                    <TR>
                      <TD class=p9navyblue vAlign=top noWrap>".$Good[Pricedesc_say]."：<SPAN class=p9orange>".$Result['pricedesc']."</SPAN></TD>
					</TR>
";		
	}

$Table .="    

                               </TBODY>
	                           </TABLE>
	                         </TD>
	                      </TR>
                          <TR>
                            <TD align=right>
                              <TABLE cellSpacing=0 cellPadding=2 border=0>
                                <TBODY>
                                <TR>
                                <TD></TD>
                                <TD><a href=".$INFO['site_url']."/product/detail".$Result['gid']." class='job'>
								".$Good[Detail_say]."</A></TD>
                                <TD><A href=\"".$INFO['site_url']."/product/Collection.php?collection_id=".$Result['collection_id']."&Action=Del\" class='job'>".$Basic_Command['Del']."</A>
								</TD>
	                            </TR>
	                            </TBODY>
	                          </TABLE>
	                         </TD>
	                        </TR>
	                        </TBODY>
	                       </TABLE>
	                      </TD>
	                     </TR>
	                     </TBODY>
	                    </TABLE>
	                   </TD>
	                  </TR>
	                  </TBODY>
	                </TABLE>
         
		            

";
return $Table; 
}


function BonusCollection_list($Public,$Result,$Value){
global $INFO,$Nav,$MemberLanguage_Pack,$Order_Pack,$Basic_Command,$Good;
$Table = "";

$Table .=" 

<TABLE class='p14table' cellSpacing=0 cellPadding=0 width='100%' border=0>
              <TBODY>
              <TR>
                <TD class=dotY vAlign=top align=middle width='41%'>
				<IMG   src=\"../".$INFO['good_pic_path']."/".$Result['smallimg']."\"  width=120  border=0></TD>
                <TD vAlign=top width='69%'>
                  <TABLE height=57 cellSpacing=0 cellPadding=2 width='100%'  border=0>
                    <TBODY>
                    <TR>
                      <TD class=p14red height=11>".$Result['goodsname']."</TD></TR>
                    <TR>
                      <TD class=p9v vAlign=top>
                        <TABLE class=p9v cellSpacing=0 cellPadding=0   width='100%' border=0>
                          <TBODY>
                          <TR>
                            <TD class=p9v  vAlign=top>".$Result['intro']."</TD>
	                      </TR>
	                      </TBODY>
	                    </TABLE>
	                   </TD>
	               </TR>
                    <TR>
                      <TD class=p9black vAlign=top>
                        <TABLE class=p9v cellSpacing=0 cellPadding=0 width='67%'   border=0>
                          <TBODY>
                          <TR>
                            <TD class=p9navyblue width='59%'>
                              <TABLE height=57 cellSpacing=0 cellPadding=2   width='100%' border=0>
                                <TBODY>
                                <TR>
                                <TD class=p9navyblue vAlign=top noWrap>".$Good[yprice_say]."：<SPAN  class=p9navyblue><s>".$Result['price']."</s></SPAN></TD></TR>
                                
  
                    <TR>
                      <TD class=p9navyblue vAlign=top noWrap>".$Good[NeedPer_say]."：<SPAN class=p9orange>".$Result['bonusnum']."</SPAN></TD>
					</TR>
                               </TBODY>
	                           </TABLE>
	                         </TD>
	                      </TR>
                          <TR>
                            <TD align=right>
                              <TABLE cellSpacing=0 cellPadding=2 border=0>
                                <TBODY>
                                <TR>
                                <TD><A  href=\"javascript:temp_bonus=window.open('".$INFO['site_url']."/bonus/ChangeInto.php?collection_id=".$Result['collection_id']."&goods_id=".$Result['gid']."','shopcat','width=700,height=500,scrollbars=yes');temp_bonus.focus()\">".$Good[ChangeInto_say]."</A></TD>
                                <TD><a href=".$INFO['site_url']."/product/goods_detail.php?goods_id=".$Result['gid']." target=_blank>
								".$Good[Detail_say]."</A></TD>
                                <TD><A 
                                href=\"".$INFO['site_url']."/bonus/BonusCollection.php?collection_id=".$Result['collection_id']."&Action=Del\">".$Basic_Command['Del']."</A>
								</TD>
	                            </TR>
	                            </TBODY>
	                          </TABLE>
	                         </TD>
	                        </TR>
	                        </TBODY>
	                       </TABLE>
	                      </TD>
	                     </TR>
	                     </TBODY>
	                    </TABLE>
	                   </TD>
	                  </TR>
	                  </TBODY>
	                </TABLE>
         
		             <TABLE height=4 cellSpacing=0 cellPadding=0 width='100%' border=0>
                       <TBODY>
                        <TR>
                         <TD class=dotX><IMG height=8 src='../images/spacer.gif'   width=34></TD>
                        </TR>
					   </TBODY>
					 </TABLE>

";
return $Table; 
}


function templates ($Public,$Result,$Value){
global $INFO;

$Checked = "";
$Checked_tabel = "";
$Checked = $Result[name]==$Value ?  " checked " : ""  ;
$Checked_tabel = $Result[name]==$Value ?  " bgColor=#FFFFCC " : ""  ;

$Table = "";

$Table .=" 
<table width=\"90%\"  border=\"0\" cellspacing=\"2\" cellpadding=\"2\" ".$Checked_tabel." >
                         <tr>
                           <td align=center><a href='../templates/big_".$Result[name].".jpg' target=_blank><img src='../templates/small_".$Result[name].".jpg' width=150 height=194 border=0 ></a></td>
                         </tr>
	                     <tr>
                           <td width=90%  align=center class=9pv><input type=radio name=templates value=".$Result[name]." ".$Checked." >&nbsp;".$Result[name]."</td>
                         </tr>

</table>

";

return $Table;
}


/*--------------------------------------------------------------------------------------------------------------*/
} //类结束标志

?>
