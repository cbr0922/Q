<?php
/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
  +------------------------------------------------------------+
  | Filename.......: pagenav_stard.php                               |
  | Project........: 大白菜芯 翻 页                              |
  | Version........: 2.0.0                                     |
  | Last Modified..: 2003-01-16                                |
  +------------------------------------------------------------+
  | Author.........: tyler <java@cu165.com>                     |
  | Homepage.......: http://tjsohu.com                         |
  | Support........: http://tjsohu.com                         |
  +------------------------------------------------------------+
  | Copyright (C) 2004 tjsohu.com Team. All rights reserved.   |
  +------------------------------------------------------------+
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */
error_reporting(7);

class buildNav{
      
      var $limit;
      var $execute,$query;
      var $total_result = 0;
      var $offset = "offset";
    
     

	  function buildNav($Public,$objClass)
	  {
		$this->Public=$Public;    
        $this->theClass = "class=".$objClass ;
	  }

      function execute($query,$limit){

//               global $product;
               $GLOBALS[$this->offset] = (!isset($GLOBALS[$this->offset]) OR $GLOBALS[$this->offset]<0) ? 0 : $GLOBALS[$this->offset];
               //$this->sql_result = $DB->query($query);

               $GLOBALS[$this->offset] = ($GLOBALS[$this->offset]>$this->total_result) ? $this->total_result-10 : $GLOBALS[$this->offset];

//               if (empty($this->limit)) {
              if (empty($limit) || $limit=="")	{
                   $this->limit = 20;
               }else{
			       $this->limit = $limit;
			   }

               if (isset($this->limit)) {
                   $query .= " LIMIT " . $GLOBALS[$this->offset] . ", $this->limit";
                   $this->sql_result = $this->Public->query($query);
                   $this->num_pages = ceil($this->total_result/$this->limit);
               }
               if ($GLOBALS[$this->offset]+1 > $this->total_result) {
                   $GLOBALS[$this->offset] = $this->total_result-1;
               }

      }

    //function show_num_pages($frew = "&laquo;", $rew='上一页', $ffwd = '&raquo;', $fwd='下一页', $separator = '') 
    function show_num_pages($frew = '' , $rew = '', $ffwd = '', $fwd = '', $separator = '',$ajax=0,$url="",$div="") {
               
			   global $PageNavClass_Pack;
               
			   $rew = $PageNavClass_Pack['Pre_Page']; //'上一页';
               $fwd = $PageNavClass_Pack['Next_Page']; //'下一页'
               $first_page   = $PageNavClass_Pack['First_Page']; //首页 --第一页
               $end_page   = $PageNavClass_Pack['Last_Page']; //最后一页
  			   $frew  =  $first_page;
               $ffwd  =  $end_page;
				if ($this->limit == 0 ){
					 $current_pg = 1;
				}else{
			   $current_pg = $GLOBALS[$this->offset]/$this->limit+1;
			   }
               if ($current_pg > '5') {
                   $fgp = ($current_pg-5 > 0) ? $current_pg-5 : 1;
                   $egp = $current_pg+4;
                   if ($egp > $this->num_pages) {
                       $egp = $this->num_pages;
                       $fgp = ($this->num_pages-9 > 0) ? $this->num_pages-9 : 1;
                   }
               } else {
                   $fgp = 1;
                   $egp = ($this->num_pages >= 10) ? 10 : $this->num_pages;
               }
               if($this->num_pages > 1) {
                  // searching for http_get_vars
                  foreach ($_GET as $_get_name => $_get_value) {
                           if ($_get_name != $this->offset) {
							   
							      if ($_get_name=='skey' && $ajax==1){
									  $this->_get_vars .= "&$_get_name=' + encodeURIComponent('".trim($_get_value)."') + '";
								  }else{
                                      $this->_get_vars .= "&$_get_name=".($_get_value)."";
								  }

								  if (isset($_GET['Type'])){
								      $this->_get_vars    .="&Type=".trim($_GET['Type']);								  
								  }
						   }    
                  }
                  $this->listNext = $GLOBALS[$this->offset] + $this->limit;
                  $this->listPrev = $GLOBALS[$this->offset] - $this->limit;
//                  $this->theClass = $objClass;
                  if (!empty($rew)) {                                                                                                                                                                  if ($ajax == 0)   {                                                                          //$separator [$frew] $rew
                      	$return .= ($GLOBALS[$this->offset] > 0) ? "<a href=\"$GLOBALS[PHP_SELF]?$this->offset=0$this->_get_vars\" $this->theClass title=\"$first_page\">$frew</a> <a href=\"$GLOBALS[PHP_SELF]?$this->offset=$this->listPrev$this->_get_vars\" $this->theClass title=\"$rew\">$rew</a> $separator " : "";}
					  else{
					  	$return .= ($GLOBALS[$this->offset] > 0) ? "<a href=\"javascript:showpage('" . $url . "','$this->_get_vars','" .$div . "');\" $this->theClass title=\"$first_page\">$frew</a> <a href=\"javascript:showpage('" .$url . "','$this->offset=$this->listPrev$this->_get_vars','" .$div . "');\" $this->theClass title=\"$rew\">$rew</a> $separator " : "";}
                  }

                  // showing pages
                  if ($this->show_pages_number || !isset($this->show_pages_number)) {
                      for($this->a = $fgp; $this->a <= $egp; $this->a++) {
                          $this->theNext = ($this->a-1)*$this->limit;
                          if ($this->theNext != $GLOBALS[$this->offset]) {
							  if ($ajax == 0)    
                              	$return .= " <a href=\"$GLOBALS[PHP_SELF]?$this->offset=$this->theNext$this->_get_vars\" $this->theClass> ";
							  else
							  	$return .= " <a href=\"javascript:showpage('" . $url . "','$this->offset=$this->theNext$this->_get_vars','" .$div . "');\" $this->theClass> ";
                              if ($this->number_type == 'alpha') {
                                  $return .= chr(64 + ($this->a));
                              } else {
                                  $return .= $this->a;
                              }
                              $return .= "</a> ";
                          } else {
                              if ($this->number_type == 'alpha') {
                                  $return .= chr(64 + ($this->a));
                              } else {
                                  $return .= "<b>$this->a</b>";
                              }
                              $return .= ($this->a < $this->num_pages) ? " $separator " : "";
                          }
                      }
                      $this->theNext = $GLOBALS[$this->offset] + $this->limit;
                      if (!empty($fwd)) {
                          $offset_end = ($this->num_pages-1)*$this->limit;                                                                                                                                                                       if ($ajax == 0)                                                                            //$separator $fwd [$ffwd]
                          $return .= ($GLOBALS[$this->offset] + $this->limit < $this->total_result) ? "$separator <a href=\"$GLOBALS[PHP_SELF]?$this->offset=$this->listNext$this->_get_vars\" $this->theClass title=\"$fwd\">$fwd</a> <a href=\"$GLOBALS[PHP_SELF]?$this->offset=$offset_end$this->_get_vars\" $this->theClass title=\"$end_page\">$ffwd</a>" : "";
						  else
						  $return .= ($GLOBALS[$this->offset] + $this->limit < $this->total_result) ? "$separator <a href=\"javascript:showpage('" . $url . "','$this->offset=$this->listNext$this->_get_vars','" . $div . "');\" $this->theClass title=\"$fwd\">$fwd</a> <a href=\"javascript:showpage('" . $url . "','$this->offset=$offset_end$this->_get_vars','" .$div . "');\" $this->theClass title=\"$end_page\">$ffwd</a>" : "";
                      }
                  }
               }
               return $return;
      }

      // [Function : Showing the Information for the Offset]
      function show_info() {
               
			   global $PageNavClass_Pack,$Basic_Command;

               $Total_have = $PageNavClass_Pack['TotalPage']; //共有
			   $Display    = $PageNavClass_Pack['Display'];//是否發佈

               $return .= $Total_have.": ".$this->total_result." , ";
               $list_from = ($GLOBALS[$this->offset]+1 > $this->total_result) ? $this->total_result : $GLOBALS[$this->offset]+1;
               $list_to = ($GLOBALS[$this->offset]+$this->limit >= $this->total_result) ? $this->total_result : $GLOBALS[$this->offset]+$this->limit;
               //$return .= 'Showing Results from ' . $list_from . ' - ' . $list_to . '<br>';
               $return .= $Display.": ".$list_from ." - ".$list_to;
               return $return;
      }

      function pagenav($ajax=0,$url="",$div="") {

			//	global $objClass;
            //    $this->theClass = $objClass ;
		  
               $return = "
                           <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                             <tr>
                               <td ".$this->theClass.">".$this->show_info()."</td>
                               <td align=\"right\" ".$this->theClass.">".$this->show_num_pages('' ,'', '', '', '',$ajax,$url,$div)."</td>
                             </tr>
                           </table>";

               return $return;
      }
}
?>
