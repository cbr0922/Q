<?php
error_reporting(7);
@header("Content-type: text/html; charset=utf-8");
class FUNCTIONS
{
	function Input_Box($type,$name,$value,$add,$event="")
	{
		/**
	   *   class='textbox1'  onmouseover=\"this.className='textbox2'\" onMouseOut=\"this.className='textbox1'\"
	   *   class='box1'  onmouseover="this.className='box2'" onMouseOut="this.className='box1'"
	   */
		global $Ex_Function;
		switch ($type) {
			case "text" :
				$Input_Box = '';
				$Input_Box = $value!="" ? "<input name=\"".$name."\" type='text' id=\"".$name."\" value=\"".$value."\"  ".$add." />" : "<input name=\"".$name."\"  id=\"".$name."\" type=text ".$add." />";
				break;

			case "password" :
				$Input_Box = '';
				$Input_Box = $value!="" ? "<input name=\"".$name."\" type=password  id=\"".$name."\" value=\"".$value."\"  ".$add." />" : "<input name=\"".$name."\" id=\"".$name."\" type=password ".$add." />";
				break;

			case "textarea" :
				$Input_Box = '';
				$Input_Box = $value!="" ? "<textarea  id=\"".$name."\"  name=\"".$name."\" ".$add.">".$value."</textarea>" : "<textarea name=\"".$name."\"  id=\"".$name."\"  ".$add."></textarea>" ;
				break;


			case "radio" :
				$Input_Box = '';
				$Input_Box = " <input type=radio id=\"".$name."\" name=\"".$name."\" value=1 ";
				$Input_Box.= is_array($event) ? " onclick=\"" . $event[0]  . "\"" : "";
				$Input_Box.= $value==1 ? " checked=\"checked\" /> ".$add[0].""  : " /> ".$add[0]." ";
				$Input_Box.= " <input type=radio  id=\"".$name."\"  name=\"".$name."\" value=0 ";
				$Input_Box.= is_array($event) ? " onclick=\"" .$event[1]  . "\"" : "";
				$Input_Box.= $value==0 ? " checked=\"checked\" /> ".$add[1].""  : " /> ".$add[1]." ";

				break;

			case "radio_strand" :
				$Input_Box = '';
				$Input_Box = " <input type=radio  id=\"".$name."\"  name=\"".$name."\" value=0 ";

				$Input_Box.= $value==0 ? " checked=\"checked\" /> ".$add[0].""  : " /> ".$add[0]." ";
				$Input_Box.= " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=1 ";

				$Input_Box.= $value==1 ? " checked=\"checked\" /> ".$add[1].""  : " /> ".$add[1]." ";

				break;

			case "radio_self" :
				$Input_Box = '';
				$Input_Box = " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=".$add[0]." ";
				$Input_Box.= $value==$add[0] ? " checked=\"checked\" /> ".$add[0].""  : " /> ".$add[0]." ";
				$Input_Box.= " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=".$add[1]." ";
				$Input_Box.= $value==$add[1] ? " checked=\"checked\" /> ".$add[1].""  : " /> ".$add[1]." ";

				break;

			case "radios" :
				$Input_Box = '';
				$Input_Box = " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=0 ";
				$Input_Box.= $value==0 ? " checked=\"checked\" /> ".$add[0].""  : " /> ".$add[0]." ";
				$Input_Box.= " <input type=radio name=\"".$name."\" value=1 ";
				$Input_Box.= $value==1 ? " checked=\"checked\" > ".$add[1].""  : " > ".$add[1]." ";
				$Input_Box.= " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=2 ";
				$Input_Box.= $value==2 ? " checked=\"checked\" /> ".$add[2].""  : " /> ".$add[2]." ";

				break;

			case "radio_alarm" :
				$Input_Box = '';
				$Input_Box = " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=1  onclick=view(alarmshow,1)";
				$Input_Box.= $value==1 ? " checked=\"checked\" /> ".$add[0].""  : " /> ".$add[0]." ";
				$Input_Box.= " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=0  onclick=view(alarmshow,0)";
				$Input_Box.= $value==0 ? " checked=\"checked\" /> ".$add[1].""  : " /> ".$add[1]." ";

				break;

			case "radio_bonus" :
				$Input_Box = '';
				$Input_Box = " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=1  onclick=view(bonusshow,1)";
				$Input_Box.= $value==1 ? " checked=\"checked\" /> ".$add[0].""  : " /> ".$add[0]." ";
				$Input_Box.= " <input type=radio  id=\"".$name."\"  name=\"".$name."\" value=0  onclick=view(bonusshow,0)";
				$Input_Box.= $value==0 ? " checked=\"checked\" /> ".$add[1].""  : " /> ".$add[1]." ";

				break;

			case "radio_js" :
				$Input_Box = '';
				$Input_Box = " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=1  onclick=viewjs(1)";
				$Input_Box.= $value==1 ? " checked=\"checked\" /> ".$add[0].""  : " /> ".$add[0]." ";
				$Input_Box.= " <input type=radio  id=\"".$name."\" name=\"".$name."\" value=0  onclick=viewjs(0)";
				$Input_Box.= $value==0 ? " checked=\"checked\" /> ".$add[1].""  : " /> ".$add[1]." ";

				break;

			case "checkbox":
				$Input_Box = '';

				$Num       = count($name);
				$Nums      = count($value);
				$Numss     = count($add);

				if ($Num == $Nums && $Nums == $Numss ){

					for ($i=0;$i<$Num;$i++){
						$Input_Box.= "<input  id=\"".$name."\" name=\"".$name[$i]."\" type=\"checkbox\" value=\"1\"";
						$Input_Box.= $value[$i]==1 ? " checked=\"checked\" />".$add[$i]." ": " /> ".$add[$i]." \n";
					}
				}else{
					echo "The Input_Box function value don't match!!!";
					exit;
				}
				break;
		}

		return $Input_Box;
	}

	function Bgcolor($First_color,$Second_color,$row){
		$Return_color = $row%2==0 ? " bgcolor=".$First_color : " bgcolor=".$Second_color ;
		return $Return_color;
	}


	function value_is($SQL,$Field_Name){

		global $DB;
		$query=$DB->query($SQL);
		$num=$DB->num_rows($query);
		if ($num>0) {
			$rs=$DB->fetch_array($query);
			$value=$rs[$Field_Name];
			return $value;
		}else{
			return false;
		}

	}

	function Level_name($Point){
		global $DB,$INFO;
		$Query = $DB->query("SELECT max(level_num) as maxnum ,level_id,level_name  FROM `{$INFO[DBPrefix]}user_level`  where level_num<".intval($Point)." group by level_id,level_name order by  maxnum desc limit 0,1");
		$Num = $DB->num_rows($Query);

		/*判断是否有相关等级资料*/
		if ($Num>0){
			$Result     =  $DB->fetch_array($Query);
			$Level_name =  $Result['level_name'];
		}else{
			$Querys = $DB->query("SELECT level_num,level_name FROM `{$INFO[DBPrefix]}user_level` order by level_num asc  limit 0,1");
			$Nums   = $DB->num_rows($Querys);
			/*判断是否有等级资料*/
			if ($Nums>0){
				$Results     =  $DB->fetch_array($Querys);
				$Level_name  =  $Results['level_name'];
			}else{
				$Level_name ="";
			}
		}

		return $Level_name;
	}

	function Char_v($Char_v){

		$Char_v=str_replace(" ",",",trim($Char_v));
		$array= explode(",",$Char_v);
		$count=count($array);
		$hanstr='';
		for ($i=0;$i<$count;$i++)
		{
			$co=0;
			for ($j=$i+1;$j<$count;$j++)
			{
				if ($array[$i]==$array[$j])
				{
					$co=$co+1;
					break;
				}

			}

			if ($co==0)	{
				$hanstr.=$array[$i]." ";
			}

		}
		$Hanstr_v=str_replace(" ",",",trim($hanstr));
		return $Hanstr_v;
	}


	/*
	去掉数组中重复的值.并返回本数组
	*/
	function array_unvalue($array){

		if (!is_array($array))
		{
			return false;
		}
		$count=count($array);
		$hanstr='';
		for ($i=0;$i<$count;$i++)
		{
			$co=0;
			for ($j=$i+1;$j<$count;$j++)
			{
				if ($array[$i]==$array[$j])
				{
					$co=$co+1;
					break;
				}

			}

			if ($co==0)	{
				$hanstr.=$array[$i]." ";
			}

		}
		$Hanstr_v = str_replace(" ",",",trim($hanstr));
		$Hanstr_v_array = explode(",",$Hanstr_v);
		return $Hanstr_v_array;
	}

	function select_type($SQL,$Select_Name,$FieldId_Name,$Field_Name,$In_Value_Id,$event="")
	{
		global $DB,$Basic_Command;
		$query=$DB->query($SQL);
		$num=$DB->num_rows($query);
		$table="";
		$table.= "\n<select  id=\"".$Select_Name."\"  name='".$Select_Name."' class=\"trans-input\" " . $event . ">\n";

		//  if (!isset($In_Value_Id) || $In_Value_Id=='' || $In_Value_Id<1){
		$table.= "<option value=0>".$Basic_Command['Please_Select']."</option>\n";
		// }
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$Rs=$DB->fetch_array($query);
				$table.= "<option value='".$Rs[$FieldId_Name]."'";
				if ($In_Value_Id==$Rs[$FieldId_Name] && $In_Value_Id!="" ){
					$table.= " selected=\"selected\" ";
				}
				$table.= " > ".$Rs[$Field_Name]." </option>\n";
			}
		}
		$table .= "</select>";

		return    $table;

	}

	/*获得新闻下级子类的所有ID*/
	function Sun_ncon_class($id)
	{
		global $DB,$INFO;
		$Egg = "";
		$Query  = $DB->query("select * from `{$INFO[DBPrefix]}nclass` where top_id=".intval($id)." ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			while($Rs=$DB->fetch_array($Query)){
				$Eggs .=$Rs['ncid']." ";
				$Egg .=$this->Sun_ncon_class($Rs['ncid'])." ".$Eggs;
			}
		}
		return  $Egg;
	}

	/*获得产品下级子类的所有ID
	返回值是一个字符串
	*/
	function Sun_pcon_class($id)
	{
		global $DB,$INFO;
		$Egg  = "";
		$Eggs = "";
		$Query  = $DB->query("select bid from `{$INFO[DBPrefix]}bclass` where top_id=".intval($id)." ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			while($Rs=$DB->fetch_array($Query)){
				$Eggs .=$Rs['bid'].",";
				$Egg  .=$Eggs.$this->Sun_pcon_class($Rs['bid']);
			}

			return  $Egg;

		}else{

			return 0;
		}

	}

	function Sun_shoppcon_class($id)
	{
		global $DB,$INFO;
		$Egg  = "";
		$Eggs = "";
		$Query  = $DB->query("select bid from `{$INFO[DBPrefix]}shopbclass` where top_id=".intval($id)." ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			while($Rs=$DB->fetch_array($Query)){
				$Eggs .=$Rs['bid'].",";
				$Egg  .=$Eggs.$this->Sun_shoppcon_class($Rs['bid']);
			}

			return  $Egg;

		}else{

			return 0;
		}

	}

	/*获得产品下级子类的所有ID
	返回值是一个字符串
	*/
	function Sun_groupcon_class($id)
	{
		global $DB,$INFO;
		$Egg  = "";
		$Eggs = "";
		$Query  = $DB->query("select bid from `{$INFO[DBPrefix]}groupclass` where top_id=".intval($id)." ");
		$Num   = $DB->num_rows($Query);
		if ($Num>0){
			while($Rs=$DB->fetch_array($Query)){
				$Eggs .=$Rs['bid'].",";
				$Egg  .=$Eggs.$this->Sun_groupcon_class($Rs['bid']);
			}

			return  $Egg;

		}else{

			return 0;
		}

	}
	/*-------------------------------------------------------
	这个函数是将格式化字符串，将重复的字符串清除，并存入数组中。
	--------------------------------------------------------*/
	function Single_char($Egg){

		$Egg=trim($Egg);

		for($i=0;$i<100;$i++){
			$Egg=str_replace("  "," ",$Egg);
		}
		$Egg=str_replace(" ",",",$Egg);
		$array=explode(",",$Egg);
		$count=count($array);

		for ($i=0;$i<$count;$i++)
		{
			$co=0;
			for ($j=$i+1;$j<$count;$j++)
			{
				if ($array[$i]==$array[$j])
				{
					$co=$co+1;
				}
			}


			if ($co<=0)
			{
				if ($i+1==$count){
					$hanstr.=$array[$i];
				}else{
					$hanstr.=$array[$i].",";
				}
			}

		}
		$array_char = explode(",",$hanstr);
		return $array_char ;
	}

	function father_class_topid($top_id)
	{
		global $DB,$INFO;

		/* 如果父亲类不是空的时候。将显示出父亲的类名*/
		if ($top_id>0){
			$Query=$DB->query("select top_id from `{$INFO[DBPrefix]}bclass` where bid=".intval($top_id) );
			$Num= $DB->num_rows($Query);
			if ($Num>0){
				$Result =  $DB->fetch_array($Query);
				$Egg    =  $Result['top_id']." ";
			}
			$Egg   .=  $this->father_class_topid(intval($Result['top_id']),$Egg);
		}
		return trim($Egg);
	}


	function father_nclass_topid($top_id)
	{
		global $DB,$INFO;

		/* 如果父亲类不是空的时候。将显示出父亲的类名*/
		if ($top_id>0){
			$Query=$DB->query("select top_id from `{$INFO[DBPrefix]}nclass` where ncid=".intval($top_id) );
			$Num= $DB->num_rows($Query);
			if ($Num>0){
				$Result =  $DB->fetch_array($Query);
				$Egg    =  $Result['top_id']." ";
			}
			$Egg   .=  $this->father_nclass_topid(intval($Result['top_id']),$Egg);
		}
		return trim($Egg);
	}


	function Sextype($Value){

		global $MemberLanguage_Pack,$Admin_Member;

		switch ($Value){

			case 0:
				return $MemberLanguage_Pack[Sex_men]!="" ? $MemberLanguage_Pack[Sex_men] : $Admin_Member[Sex_men];
				break;
			case 1:
				return $MemberLanguage_Pack[Sex_women]!="" ? $MemberLanguage_Pack[Sex_women] : $Admin_Member[Sex_women];
				break;

		}

	}


	function adv_type_f($type_value){
		global $Basic_Command,$Adv_Pack;
		switch ($type_value){
			case 0:
				$type = $Basic_Command['Null']; //无
				break;
			case 1:
				$type = $Adv_Pack[FloatAdv] ; //浮动广告
				break;
			case 2:
				$type = $Adv_Pack[EarAdv]; //耳朵广告
				break;
			case 3:
				$type = $Adv_Pack[TagAdv]; //标签广告
				break;
			case 4:
				$type = $Adv_Pack[HourseAdv]; //跑马灯
				break;
			case 5:
				$type = $Adv_Pack[BoardAdv]; //轮播广告
				break;
			case 6:
				$type = "Banner"; //轮播广告
				break;
			case 7:
				$type = "Banner1"; //轮播广告
				break;
			case 8:
				$type = "熱門活動"; //轮播广告
				break;
			case 9:
				$type = "熱門活動"; //轮播广告
				break;
			case 11:
				$type = "團購" . $Adv_Pack[FloatAdv] ; //浮动广告
				break;
			case 12:
				$type = "團購" . $Adv_Pack[EarAdv]; //耳朵广告
				break;
			case 13:
				$type = "團購熱門關鍵字"; //轮播广告
				break;
			case 14:
				$type = "商店街商品頁Banner"; //轮播广告
				break;
			case 15:
				$type = "商店街商店頁Banner	"; //轮播广告
				break;
			case 17:
				$type = "手機Banner"; //轮播广告
				break;
			case 16:
				$type = "手機好康快報"; //轮播广告
				break;
		}
		return $type;
	}



	function father_news_class($top_id)
	{
		global $DB,$INFO;

		/* 如果父亲类不是空的时候。将显示出父亲的类名*/
		if ($top_id>0){
			$Query=$DB->query("select ncname,top_id from `{$INFO[DBPrefix]}nclass` where ncid=".intval($top_id) );
			$Num= $DB->num_rows($Query);
			if ($Num>0){
				$Result =  $DB->fetch_array($Query);
				$Egg    =  $Result['ncname']." ";
			}
			$Egg   .=  $this->father_news_class(intval($Result['top_id']),$Egg);
		}
		return trim($Egg);
	}

	function father_class($top_id)
	{
		global $DB,$INFO;

		/* 如果父亲类不是空的时候。将显示出父亲的类名*/
		if ($top_id>0){
			$Query=$DB->query("select ncname,top_id from `{$INFO[DBPrefix]}bclass` where bid=".intval($top_id) );
			$Num= $DB->num_rows($Query);
			if ($Num>0){
				$Result =  $DB->fetch_array($Query);
				$Egg    =  $Result['catname']." ";
			}
			$Egg   .=  $this->father_class(intval($Result['top_id']),$Egg);
		}
		return trim($Egg);
	}

	function father_Nav_banner($top_id)
	{
		global $DB,$INFO;

		/* 如果父亲类不是空的时候。将显示出父亲的类名*/
		if ($top_id>0){
			$Query=$DB->query("select catname,top_id,bid from `{$INFO[DBPrefix]}bclass` where bid=".intval($top_id) );
			$Num= $DB->num_rows($Query);
			if ($Num>0){
				$Result =  $DB->fetch_array($Query);
				$Egg    =  "<a href=product_class_detail.php?bid=".$Result['bid'].">".$Result['catname']."</a>||";
			}
			$Egg    .=  $this->father_Nav_banner(intval($Result['top_id']),$Egg);
		}
		return trim($Egg);
	}


	function Nav_nextClass($id){
		global $DB,$INFO;
		$Sql_sclass       = "select catname,top_id,bid from `{$INFO[DBPrefix]}bclass` where top_id=".$id."  order by bid asc ";
		$Query_sclass     = $DB->query($Sql_sclass);
		while ($Rs_sclass =  $DB->fetch_array($Query_sclass)){
			$Nav_list_detail = "<A  href=\"product_class_detail.php?bid=".$Rs_sclass['bid']."\">".$Rs_sclass['catname']."</A>&nbsp;&nbsp;";
			$Nav_list .=$Nav_list_detail.$this->Nav_nextClass($Rs_sclass['bid']);
		}
		return $Nav_list;
	}

	function Nav_nextClass_foronelevel($level,$id){
		global $DB,$INFO;
		$Sql_sclass       = "select bid,catname from `{$INFO[DBPrefix]}bclass` b where catiffb=1 and top_id=".$id."  order by bid asc ";
		$Query_sclass     = $DB->query($Sql_sclass);
		$l=0;
		while ($l <$level && $Rs_sclass =  $DB->fetch_array($Query_sclass)){
			$Nav_list_detail = "<A  href=\"".$INFO['site_url']."/product/product_class_detail.php?bid=".$Rs_sclass['bid']."\">".$Rs_sclass['catname']."</A>|";
			$Nav_list .=$Nav_list_detail.$this->Nav_nextClass_foronelevel($level,$Rs_sclass['bid']);
			$l++;
		}
		return $Nav_list;
	}







	function select_type_muliti($SQL,$Select_Name,$FieldId_Name,$Field_Name,$In_Value_Id,$Sub_class)
	{
		global $DB,$Basic_Command;
		$query=$DB->query($SQL);
		$num=$DB->num_rows($query);
		echo "\n<select  id=\"".$Select_Name."\"  name='".$Select_Name."'  class=\"trans-input\" >\n";
		if (!isset($In_Value_Id) || $In_Value_Id=='' || $In_Value_Id<1){
			echo "<option value=''>--".$Basic_Command['Please_Select']."--</option>\n";
		}
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$Rs=$DB->fetch_array($query);
				//$Sub_class = "father_class";
				$Egg = $this->$Sub_class(intval($Rs['top_id']));
				$Egg = str_replace(" ","<-",$Egg);
				$Egg = $Egg!="" ? "  { ".$Egg." }"  : "" ;


				echo "<option value='".$Rs[$FieldId_Name]."'";
				if ($In_Value_Id==$Rs[$FieldId_Name] && $In_Value_Id!="" ){
					echo " selected=\"selected\" ";
				}
				echo " > ".$Rs[$Field_Name].$Egg."</option>\n";
			}
		}
		echo "</select>\n";

	}

	function select_type_onchange($SQL,$Select_Name,$FieldId_Name,$Field_Name,$In_Value_Id)
	{
		global $DB,$Basic_Command;
		$query=$DB->query($SQL);
		$num=$DB->num_rows($query);

		echo "\n<select  id=\"".$Select_Name."\" name='".$Select_Name."'  onchange=changecat()>\n";
		if (!isset($In_Value_Id) || $In_Value_Id=='' || $In_Value_Id<1){
			echo "<option value=''>--".$Basic_Command['Please_Select']."--</option>\n";
		}
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$Rs=$DB->fetch_array($query);

				$Egg = $this->father_class(intval($Rs['top_id']));
				$Egg = str_replace(" ","<-",$Egg);
				$Egg = $Egg!="" ? "  { ".$Egg." }"  : "" ;


				echo "<option value='".$Rs[$FieldId_Name]."'";
				if ($In_Value_Id==$Rs[$FieldId_Name] && $In_Value_Id!="" ){
					echo " selected=\"selected\" ";
				}
				echo " > ".$Rs[$Field_Name].$Egg."</option>\n";
			}
		}
		echo "</select>\n";

	}

	function select_type_nochange($SQL,$Select_Name,$FieldId_Name,$Field_Name,$In_Value_Id)
	{
		global $DB,$Basic_Command;
		$query=$DB->query($SQL);
		$num=$DB->num_rows($query);
		echo "\n<select  id=\"".$Select_Name."\"  name='".$Select_Name."'>\n";
		if (!isset($In_Value_Id) || $In_Value_Id=='' || $In_Value_Id<1){
			echo "<option value=''>--".$Basic_Command['Please_Select']."--</option>\n";
		}
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$Rs=$DB->fetch_array($query);

				$Egg = $this->father_class(intval($Rs['top_id']));
				$Egg = str_replace(" ","<-",$Egg);
				$Egg = $Egg!="" ? "  { ".$Egg." }"  : "" ;
				echo "<option value='".$Rs[$FieldId_Name]."'";
				if ($In_Value_Id==$Rs[$FieldId_Name] && $In_Value_Id!="" ){
					echo " selected=\"selected\" ";
				}
				echo " > ".$Rs[$Field_Name].$Egg."</option>\n";
			}
		}
		echo "</select>\n";

	}


	function header_location($url)
	{
		echo "<script language=javascript>location.href='".$url."';</script>";
		exit;
	}

	/***********************************************************
	下列的3个SELECT 表单的函数，分别是实现的操作是对
	1）循环数据显示
	2）对指定的数组中数据循环
	3）补0的操作。

	************************************************************/

	function select_Cyc($select,$start,$end,$value,$add){
		global $Basic_Command;
		$Select="";
		$Select  = "\n<select  id=\"".$Select_Name."\" name='".$select."' ".$add." >\n";
		$Select .= "<option value=''>".$Basic_Command['Please_Select']."</option>\n";
		for ($i=$start;$i<$end;$i++){
			$Select .= "<option value=".$i." ";
			if (intval($value)==$i)
			$Select .= " selected=\"selected\" ";
			$Select .= " >".$i."</option>\n";
		}
		$Select .=  "</select>\n";
		return $Select;
	}


	function select_Cyc_array($select,$value,$add,$Array){

		global $Basic_Command;
		$Select="";
		$Num = count($Array);
		$Select  = "\n<select  id=\"".$select."\" name=".$select." ".$add." >\n";
		$Select .= "<option value=''>".$Basic_Command['Please_Select']."</option>\n";
		for ($i=0;$i<$Num;$i++){
			$Select .= "<option value=".$Array[$i]." ";
			if (intval($value)==$Array[$i])
			$Select .= " selected=\"selected\" ";
			$Select .= " >".$Array[$i]."</option>\n";
		}
		$Select .=  "</select>\n";
		return $Select;
	}


	function select_Cyc_dif_array($select,$value,$add,$Array,$Difarray){

		global $Basic_Command;
		$Select="";
		$Num = count($Array);
		$Select  = "\n<select  id=\"".$select."\"  name='".$select."' ".$add." >\n";
		for ($i=0;$i<$Num;$i++){
			$Select .= "<option value=".$Array[$i]." ";
			if (intval($value)==$Array[$i])
			$Select .= " selected=\"selected\" ";
			$Select .= " >".$Difarray[$i]."</option>\n";
		}
		$Select .=  "</select>\n";
		return $Select;
	}


	function select_Cyc_bc($select,$start,$end,$value,$add){

		global $Basic_Command;
		$Select="";

		$Select  = "\n<select  id=\"".$select."\"  name='".$select."' ".$add." >\n";
		$Select .= "<option value=''>".$Basic_Command['Please_Select']."</option>\n";
		for ($i=$start;$i<$end;$i++){
			$j = $i<10 ? "0".$i : $i ;
			$Select .= "<option value=".$i." ";
			if (intval($value)==$i)
			$Select .= " selected=\"selected\" ";
			$Select .= " >".$j."</option>\n";
		}
		$Select .=  "</select>\n";
		return $Select;
	}


	function select_muliti_type($SQL,$Select_Name,$FieldId_Name,$Field_Name,$In_Value_Id_Array)
	{
		global $DB,$Basic_Command;
		$query=$DB->query($SQL);
		$num=$DB->num_rows($query);
		$table="";
		$table.= "\n<select  id=\"".$Select_Name."\"  name='".$Select_Name."[]' multiple>\n";

		//  if (!isset($In_Value_Id) || $In_Value_Id=='' || $In_Value_Id<1){
		//  $table.= "<option value=0>".$Basic_Command['Please_Select']."</option>\n";
		// }
		if ($num>0){
			for ($i=0;$i<$num;$i++){
				$Rs=$DB->fetch_array($query);
				$table.= "<option value='".$Rs[$FieldId_Name]."'";
				if (intval($In_Value_Id_Array)!= 0  && in_array($Rs[$FieldId_Name],$In_Value_Id_Array) ){
					$table.= " selected=\"selected\" ";
				}
				$table.= " > ".$Rs[$Field_Name]." </option>\n";
			}
		}
		$table .= "</select>";

		return    $table;

	}
	//获得已经使用的空间
	function dirsize($dir) {
		@$dh = opendir($dir);
		$size = 0;
		while ($file = @readdir($dh)) {
			if ($file != "." and $file != "..") {
				$path = $dir."/".$file;
				if (is_dir($path)) {
					$size += $this->dirsize($path);
				}
				elseif (is_file($path)) {
					$size += filesize($path);
				}
			}
		}
		@closedir($dh);
		return $size;
	}



	function get_real_size($size) {

		$kb = 1024;         // Kilobyte
		$mb = 1024 * $kb;   // Megabyte
		$gb = 1024 * $mb;   // Gigabyte
		$tb = 1024 * $gb;   // Terabyte

		if($size < $kb) {
			return $size." B";
		}else if($size < $mb) {
			return round($size/$kb,2)." KB";
		}else if($size < $gb) {
			return round($size/$mb,2)." MB";
		}else if($size < $tb) {
			return round($size/$gb,2)." GB";
		}else {
			return round($size/$tb,2)." TB";
		}

	}



	function validate_email($address) {

		if(ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',$address,$email)){
			return true;
		} else {
			return false;
		}

	}


	function stripslashes_array($array) {

		while (list($k,$v) = each($array)) {
			if (is_string($v)) {
				$array[$k] = stripslashes($v);
			} else if (is_array($v))  {
				$array[$k] = $this->stripslashes_array($v);
			}
		}
		return $array;

	}

	function print_rr($array) {
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}

	function pa_exit($text = "") {
		echo "<p>$text</p>";
		exit;
	}

	function sorry_back($url,$text) {

		//echo $url."--------".$text;
		echo "<script language=javascript>";
		if ($text!=""){
			echo " alert('".$text."');";
		}
		if ($url=='close'){
			echo "javascript:window.close();";
		}elseif($url=='back'){
			echo "javascript:window.history.back();";
		}elseif($url!=''){
			echo "location.href='".$url."';";
		}
		echo "</script>\n";
		if($url!=''){
		exit;
		}
	}

	function getip() {

		if (isset($_SERVER)) {
			if (isset($_SERVER[HTTP_X_FORWARDED_FOR])) {
				$realip = $_SERVER[HTTP_X_FORWARDED_FOR];
			} elseif (isset($_SERVER[HTTP_CLIENT_IP])) {
				$realip = $_SERVER[HTTP_CLIENT_IP];
			} else {
				$realip = $_SERVER[REMOTE_ADDR];
			}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR")) {
				$realip = getenv( "HTTP_X_FORWARDED_FOR");
			} elseif (getenv("HTTP_CLIENT_IP")) {
				$realip = getenv("HTTP_CLIENT_IP");
			} else {
				$realip = getenv("REMOTE_ADDR");
			}
		}
		return $realip;

	}
	function getport() {

		if (isset($_SERVER)) {
				$realport = $_SERVER[REMOTE_PORT];
		} else {
				$realport = getenv("REMOTE_PORT");
		}
		return $realport;

	}


	function Upload_File ($File_name,$File,$old_File,$Path)  {
		global $FUNCTIONS_Pack;
		if(!empty($File_name)){      //判断是否有文件被上传

			$File_size=ceil(filesize($File)/20480);
			//如果文件大于200K则不能上传。

			if ($File_size>2048) {
				echo "<script language=javascript>alert('".$FUNCTIONS_Pack['Upload_File_FileSize_Say']."');"; //文件必须小于200K！
				echo "javascript:window.history.back(-1);</script>";
				exit;
			}


			//$extname=substr($File_name,-3);
			$extname = strtolower(substr(strrchr($File_name, "."), 1));
			//$extname = strtolower($extname);
			if($extname!="gif" && $extname!="jpg" && $extname!="swf" && $extname!="jpeg" && $extname!="tif" && $extname!="png"){

				$err .=$FUNCTIONS_Pack['Upload_File_FileFormat_Say'];  //'文件格式只能是GIF或JPG,SWF格式！'
				echo "<script language=javascript>alert('".$FUNCTIONS_Pack['Upload_File_FileFormatError_Say']."');";
				echo "javascript:window.history.back(-1);</script>";
				exit;
				//检测上传的文件名的后缀是否是GIF OR JPG如果不是。则返回上一步操作。

			} else{
				//如果文件上传条件满足。则将此文件COPY到指定路径中。并赋予数据库中的名称

				$ntime=time();
				$rand =rand(1,31000);
				$File_New            =    $Path."/".$ntime.$rand.".".$extname;
				$File_NewName        =    $ntime.$rand.".".$extname;

				copy($File,$File_New);
				if ($old_File<>"") {
					//如果有旧文件就删除掉旧的！
					@unlink($Path."/".$old_File);
				}

			}    //如果没有上传图片，则将 图片的文件名设置为空。

		} else {

			$File_NewName = $old_File;

		}
		//完成新数据的插入工作。

		return $File_NewName;

	}



	function ImgTypeReturn($Path,$File_name,$height,$width){

		global $INFO;

		if(!empty($File_name)){      //判断是否有文件判断

			//$Path_img = $Path=="" ? "./".$File_name : $Path."/".$File_name ;

			$PathArray = explode("/",$Path);
			$Path_img = $INFO[site_url]."/".$Path."/".$File_name ;

			$extname = strtolower(substr(strrchr($File_name, "."), 1));

            if($extname=="gif" || $extname=="jpg" || $extname=="jpeg" || $extname=="tif" || $extname=="png"){

				$height =  $height!=0 ? " height='".$height."' " : '' ;
				$width  =  $width!=0 ?  " width='".$width."' " :  "" ;  // width=160


				$ReturnVale = "<img src='".$Path_img."' ".$width." ".$height." border='0'  />";

			}elseif ( $extname=="swf" ){

				$height = isset($height) && $height!=0 ? " height='".$height."' " : " " ; // height=400
				$width  = isset($width) && $width!=0 ?  " width='".$width."' " :   "" ;   // width=770

				$ReturnVale ="
	<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0' ".$width."  ".$height.">
    <param name='movie' value=".$Path_img." />
    <param name='quality' value='high' />
    <embed src=".$Path_img." quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'  ".$width."  ".$height." ></embed>
    </object>
	";
			}


			return $ReturnVale;

		}


	}

	function Upload_File_GD ($File_name,$File,$ArrayPic,$Path,$type="all")  {

		global $DB,$INFO,$FUNCTIONS_Pack;
		if(!empty($File_name)){      //判断是否有文件被上传

			$File_size=ceil(filesize($File)/20480);
			//如果文件大于200K则不能上传。

			if ($File_size>2048) {
				echo "<script language=javascript>alert('".$FUNCTIONS_Pack[Upload_File_FileSize_Say]."');";
				echo "javascript:window.history.back(-1);</script>";
				exit;
			}

			$extname = strtolower(substr(strrchr($File_name, "."), 1));

			if($extname!="gif" && $extname!="jpg" && $extname!="jpeg" && $extname!="tif" && $extname!="png"){

				$err .=$FUNCTIONS_Pack['Upload_File_FileFormat_Say'];
				echo "<script language=javascript>alert('".$FUNCTIONS_Pack[Upload_File_FileFormatError_Say]."');";
				echo "javascript:window.history.back(-1);</script>";
				exit;
				//检测上传的文件名的后缀是否是GIF OR JPG如果不是。则返回上一步操作。

			} else{
				//如果文件上传条件满足。则将此文件COPY到指定路径中。并赋予数据库中的名称
				$ntime=time();
				$rand =rand(1,31000);
				$subfile = substr($ntime.$rand,strlen($ntime.$rand)-2);
				//建立文件夾
				if (!is_dir($Path."/".$subfile)){
					mkdir($Path."/".$subfile);
					chmod($Path."/".$subfile, 0777);
					mkdir($Path."/".$subfile."/big");
					chmod($Path."/".$subfile."/big", 0777);
					mkdir($Path."/".$subfile."/middle");
					chmod($Path."/".$subfile."/middle", 0777);
					mkdir($Path."/".$subfile."/small");
					chmod($Path."/".$subfile."/small", 0777);
				}
				$File_New            =    $Path."/".$subfile."/".$ntime.$rand.".".$extname;
				$File_NewName        =    $subfile."/".$ntime.$rand.".".$extname;
				$File_self           =    $ntime.$rand;
				@copy($File,$File_New);

				//如果有旧文件就删除掉旧的！
				if (is_array($ArrayPic)) {
					foreach ($ArrayPic as $k ){
						@unlink($Path."/".$k);
					}
				}
				// 开始做GD库的处理
				include (Classes."/imgthumb.class.php");
				include (RootDocumentShare."/cache/setwatermark.php");
				$text  = $INFO['site_url'];//文字水印
				//指定水印图
				if (trim($INFO[WatermarkPicfile])==""){
					$wm_image_name = RootDocument."/images/watermark.gif";
				}else{
					 $wm_image_name = RootDocument."/UploadFile/UserFiles/".trim($INFO[WatermarkPicfile_big]);
					 $wm_image_name_middle = RootDocument."/UploadFile/UserFiles/".trim($INFO[WatermarkPicfile_middle]);
					 $wm_image_name_small = RootDocument."/UploadFile/UserFiles/".trim($INFO[WatermarkPicfile_small]);
				}
				$GDImg = new ThumbHandler();
				if($type=="all" || strpos($type, "big")!==false){
					//大圖
					$ImgBIG = $subfile."/big/".$ntime.$rand.".".$extname;
					$GDImg->setSrcImg($File_New);
					$GDImg->setDstImg($Path."/".$ImgBIG);
					if (trim($INFO[SystemWaterMark])=='txt'){
						$GDImg->setMaskFont("../" . Resources . "/ttf/verdana.ttf");
						$GDImg->setMaskWord($text);
						$GDImg->setMaskFontColor("#000000");
					}elseif (trim($INFO[SystemWaterMark])=='pic'){
						$GDImg->setMaskImg($wm_image_name);
						$GDImg->setMaskPosition(intval($INFO[WatermarkWhere]));
						$GDImg->setMaskImgPct(intval($INFO[watermark_transition]));
					}
					$GDImg->createImg(intval($INFO['product_big']),intval($INFO['product_big']));
				}
				if($type=="all" || strpos($type, "middle")!==false){
					//中圖
					$ImgMiddle = $subfile."/middle/".$ntime.$rand.".".$extname;
					$GDImg->setSrcImg($File_New);
					$GDImg->setDstImg($Path."/".$ImgMiddle);
					if (trim($INFO[SystemWaterMark])=='txt'){
						$GDImg->setMaskFont("../" . Resources . "/ttf/verdana.ttf");
						$GDImg->setMaskWord($text);
						$GDImg->setMaskFontColor("#000000");
					}elseif (trim($INFO[SystemWaterMark])=='pic'){
						$GDImg->setMaskImg($wm_image_name_middle);
						$GDImg->setMaskPosition(intval($INFO[WatermarkWhere]));
						$GDImg->setMaskImgPct(intval($INFO[watermark_transition]));
					}

					$GDImg->createImg(intval($INFO['product_midlle']),intval($INFO['product_midlle']));
				}
				if($type=="all" || strpos($type, "small")!==false){
					//小圖
					$ImgThumb = $subfile."/small/".$ntime.$rand.".".$extname;
					$GDImg->setSrcImg($File_New);
					$GDImg->setDstImg($Path."/".$ImgThumb);
					$GDImg->setMaskWord("");
					$GDImg->setMaskImg("");
					$GDImg->createImg(intval($INFO['product_small']),intval($INFO['product_small']));
				}
				$File_NewName = array($File_NewName,$ImgThumb,$ImgBIG,$ImgMiddle);  //将最后GD成的资料形成一个ARRAY（）；
			}

		} else {

			$File_NewName = $old_File;

		}
		//完成新数据的插入工作。
		return $File_NewName;

	}





	function yes_no_del($url,$text)
	{
		if ($text==""){ $text="您确认删除所选定的资料吗！";}
		echo "
    <SCRIPT language=javascript>
     if (confirm('".$text."'))
      {
		location.href='".$url."';
      } else {
		javascript:window.history.back();
	  }
        </SCRIPT>
   ";
	}


	function value_isset($get,$post){
		global $Basic_Command;
		if (isset($get)){
			return str_replace("'","",stripslashes($get));
		}elseif(isset($post)){
			return str_replace("'","",stripslashes($post));
		}elseif (!isset($get)||!isset($post)|| empty($get) || empty($post)|| $get=="" || $post=="" ){
			$this->sorry_back("close",$Basic_Command['NullDate']);
		}
	}


	function Value_Manage($get,$post,$url,$text){

		if ($get!=''){
			return str_replace("'","",stripslashes($get));
		}elseif($post!=''){
			return str_replace("'","",stripslashes($post));
		}elseif ($get=="" &&  $post=="" ){
			$this->sorry_back($url,$text);
		}
	}


	//格式化字符串,把回车和空格转换成html
	function formatstr($thevalue)
	{
		$thevalue = nl2br($thevalue);
		$thevalue = str_replace("<br />","<br>",$thevalue);
		$thevalue = str_replace(" ","&nbsp",$thevalue);
		return $thevalue;
	}


	/**
 * 获得一条SQL语句，目的是去掉WHERE的重复
 */
	function CreateSql ($__Create_Sql,$String){
		if (trim($String)!=""){
			$pos = strpos($__Create_Sql,"where");
			if ($pos === false){
				$__Create_Sql = " where ".$String." " ;
			}else{
				$__Create_Sql = $__Create_Sql." and  ".$String." " ;
			}
		}
		return $__Create_Sql;
	}


	function Getcclass($Top_id){
		global $DB,$INFO;
		if ($Top_id!=''){
			$ProductListcc = array();
			$Sql_cclass   = " select bid,catname from `{$INFO[DBPrefix]}bclass` where top_id=".$Top_id." and catiffb=1 order by catord  asc ";
			$query_cclass = $DB->query($Sql_cclass);
			$num_cclass   = $DB->num_rows($query_cclass);
			$j=0;
			while ( $Rs_cclass = $DB->fetch_array($query_cclass) ){
				$ProductListcc[$j]['bid']     = $Rs_cclass['bid'];
				$ProductListcc[$j]['Url']     = $INFO[site_url]."/HTML_C/product_class_".$ProductListcc[$j]['bid']."_0.html";
				$ProductListcc[$j]['catname'] = trim($Rs_cclass['catname']);
				$j++;
			}
		}

		$ProductListcc = count($ProductListcc)>0 && $j>0 ? $ProductListcc : "" ;
		return $ProductListcc;		//返回一个数组
	}

	/**
     * 这里是获得产品库存资料说明的函数
     *
     * @param 返回类型：STRING
     * @param Storage(状态值,库存值,警告值)
     */
	function Storage($State,$StorageNum,$AlertNum){
		global $Good;
		switch (intval($State)){
			case 0:
				break;
			case 1:
			if ( intval($AlertNum)>=intval($StorageNum) && intval($StorageNum)>0  ){
				$StorageSay	= "<FONT COLOR=RED>【庫存已不多，欲購從速】</FONT>";//$Good[StorageLow];
			}
			if ( intval($StorageNum)<=0  ){
				$StorageSay	= "<FONT COLOR=RED>【缺貨中】</FONT>";//$Good[StorageScarce];
			}
				break;
			default:
				break;
		}
		return $StorageSay;
	}
	/* 获得格式化后的数字*/
	function Format_order_serial($Date,$Max_num){
		if ($Max_num<10){
			$Next_order_serial = $Date."000".$Max_num;
		}else if ($Max_num>=10 && $Max_num<100){
			$Next_order_serial = $Date."00".$Max_num;
		}else if ($Max_num>=100 && $Max_num<1000){
			$Next_order_serial = $Date."0".$Max_num;
		}else{
			$Next_order_serial = $Date.$Max_num;
		}
		return $Next_order_serial;
	}





	//==============这个函数为了在文本来显示用户状态的．================================//
	function OrderStateName($value){
		global $Order_Pack;
		switch (intval($value)){
			case 0:
				return $Order_Pack[OrderState_say_one]; //"未確認";
				break;
			case 1:
				return $Order_Pack[OrderState_say_two]; // "已確認";
				break;
			case 2:
				return $Order_Pack[OrderState_say_three]; //"部分發貨"
				break;
			case 3:
				return $Order_Pack[OrderState_say_four] ;// "已發貨"
				break;
			case 4:
				return $Order_Pack[OrderState_say_five]; //"已歸檔";
				break;
			case 5:
				return $Order_Pack[OrderState_say_six] ;// "已取消";
			case 6:
				return "部份退貨";
			case 7:
				return "全部退貨";
		}
	}

	//==============这个函数为了在文本来显示支付状态．================================//
	function OrderPayState($value){
		global $Order_Pack;
		switch (intval($value)){
			case 0:
				return $Order_Pack[OrderPayState_say_one]; //"未支付";
				break;
			case 1:
				return $Order_Pack[OrderPayState_say_two]; // "已支付";
				break;
			case 2:
				return $Order_Pack[OrderPayState_say_three]; //"支付失敗"
				break;
			case 3:
				return $Order_Pack[OrderPayState_say_four] ;// "等待付款"
				break;
			case 4:
				return $Order_Pack[OrderPayState_say_five] ;// "付款中"
			default:
				return $Order_Pack[OrderPayState_say_one]; //"未支付";
				break;

		}
	}
	//----------------------获得会员价格------------------------
	function MemberLevelPrice($level,$goods_id,$detail_id = 0){
		//echo $detail_id;
		//exit;
		global $DB,$INFO;
		$Sql_M    = "select * from `{$INFO[DBPrefix]}member_price` where m_level_id=".intval($level)." and m_detail_id='" . $detail_id . "' and m_goods_id='" . intval($goods_id) . "' limit 0,1";
		$Query_M  = $DB->query($Sql_M);
		$Result_M = $DB->fetch_array($Query_M);
		$Nums_M      = $DB->num_rows($Query_M);
		if($Nums_M>0){
		   $price = $Result_M['m_price'];
		   return $price;
		}


		$Sql = " select pricerate from `{$INFO[DBPrefix]}user_level` where level_id=".intval($level)."  limit 0,1";
		$QueryMemberPrice = $DB->query($Sql);
		$NumMemberPrice   = $DB->num_rows($QueryMemberPrice);
		$ifxygoods = 0;
		if ($NumMemberPrice>0){
			$ResultMemberPrice= $DB->fetch_array($QueryMemberPrice);
			$pricerate = $ResultMemberPrice['pricerate'];
			if (intval($detail_id)>0){
				$gSql = " select detail_pricedes from `{$INFO[DBPrefix]}goods_detail` where gid=".intval($goods_id)." and detail_id='". intval($detail_id) ."'  limit 0,1";
				$Queryg = $DB->query($gSql);
				$Numg   = $DB->num_rows($Queryg);
				if ($Numg>0){
					$Resultg= $DB->fetch_array($Queryg);
					$pricedesc = $Resultg['detail_pricedes'];
				}
			}else{
				$gSql = " select pricedesc,ifxygoods from `{$INFO[DBPrefix]}goods` where gid=".intval($goods_id)."  limit 0,1";
				$Queryg = $DB->query($gSql);
				$Numg   = $DB->num_rows($Queryg);
				if ($Numg>0){
					$Resultg= $DB->fetch_array($Queryg);
					$pricedesc = $Resultg['pricedesc'];
					$ifxygoods = $Resultg['ifxygoods'];
				}
			}
			if ($pricerate>0 && $ifxygoods==0)
				$MemberLevelPrice = round($pricerate*0.01*$pricedesc,0);
			else
				$MemberLevelPrice = $pricedesc;

			return $MemberLevelPrice;
		}else{
			return '0';
		}
	}

	//----------------------获得用户定单状态------------------------
	function UserBackType($sys_say,$value){
		global $FUNCTIONS_Pack;
		$State = $sys_say!="" ? "<font color=black>[".$FUNCTIONS_Pack['Function_Order_allrep'] ."]" : "<font color=green>" ;
		switch ($value){
			case 1:
				$ReturnValue = $FUNCTIONS_Pack['Function_Order_Cancel'];
				break;
			case 2:
				$ReturnValue = $FUNCTIONS_Pack['Function_Order_MemCancel'];
				break;
		}
		return $State.$ReturnValue."</font>&nbsp;";
	}

	//----------------------获得用户尚未处理订单------------------------
	function NoCL(){
		global $DB,$INFO,$TimeClass;

		if (is_object($TimeClass)){
			$InFunction_TimeClass = $TimeClass;
		}else{
			include_once "Time.class.php";
			$InFunction_TimeClass = new TimeClass;
		}


		$begtime  = $_GET['begtime']!="" ? $_GET['begtime'] : $InFunction_TimeClass->ForGetDate("Month","-6","Y-m-d");
		$endtime  = $_GET['endtime']!="" ? $_GET['endtime'] : $InFunction_TimeClass->ForGetDate("Day","1","Y-m-d");

		$begtimeunix  = $InFunction_TimeClass->ForYMDGetUnixTime($begtime,"-");
		$endtimeunix  = $InFunction_TimeClass->ForYMDGetUnixTime($endtime,"-");


		if (intval($_SESSION['LOGINADMIN_TYPE'])==2){
			$Where = "  and provider_id=".intval($_SESSION['LOGINADMIN_TYPE'])."  ";
		}
		$Sql   = " select count(order_state) as ordernum from `{$INFO[DBPrefix]}order_table`  where  order_state=0 ".$Where."    ";
		$Query = $DB->query($Sql);
		$Rs    = $DB->fetch_array($Query);
		$NoCl  = $Rs['ordernum'];  //尚未处理订单
		$DB->free_result($Query);
		return $NoCl;
	}




	function smartshophtmlspecialchars($string) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = $this->smartshophtmlspecialchars($val);
			}
		} else {
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
		}
		return $string;
	}

	function smartshopunhtmlentities($string)
	{
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($string, $trans_tbl);
	}

	//----------------------获得邮件发送类型------------------------
	function sendtype($sendtype)
	{
		global $Mail_Pack;
		switch ($sendtype){
			case "user_register":
				$sendname       = $Mail_Pack[MailSetTitle_I];
				break;
			case "user_chpass":
				$sendname       = $Mail_Pack[MailSetTitle_II];
				break;
			case "user_passback":
				$sendname       = $Mail_Pack[MailSetTitle_III];
				break;
			case "user_bbsback":
				$sendname       = $Mail_Pack[MailSetTitle_IV];
				break;
			case "user_commentback":
				$sendname       = $Mail_Pack[MailSetTitle_V];
				break;
			case "order_create":
				$sendname       = $Mail_Pack[MailSetTitle_VI];
				break;
			case "pay_success":
				$sendname       = $Mail_Pack[MailSetTitle_VII];
				break;
			case "order_check":
				$sendname       = $Mail_Pack[MailSetTitle_VIII];
				break;
			case "order_confirm":
				$sendname       = $Mail_Pack[MailSetTitle_IX];
				break;
			case "order_cancel":
				$sendname       = $Mail_Pack[MailSetTitle_X];
				break;
			case "order_delivery":
				$sendname       = $Mail_Pack[MailSetTitle_XI];
				break;
			case "sendticket":
				$sendname       = "派發優惠券";
				break;
			case "waitbuy":
				$sendname       = "到貨通知";
				break;
			case "pointalert":
				$sendname       = "積分過期提醒";
				break;
		}
		return $sendname ;
	}
	/**
	 * 控制发布商品的数量
	 *
	 * @return true
	 */
	function controlGoodsNum(){
		global $DB,$INFO;
		$sql = " select count(*) as count from `{$INFO[DBPrefix]}goods` ";
		$query = $DB->query($sql);
		$result = $DB->fetch_array($query);
		if ($result['count'] >=50) {
			return false;
		}else {
			return true;
		}
	}

	function Upload_AllFile ($File_name,$File,$old_File,$Path)  {
		global $FUNCTIONS_Pack;
		if(!empty($File_name)){      //判断是否有文件被上传

			$File_size=ceil(filesize($File)/20480);
			//如果文件大于200K则不能上传。

			if ($File_size>2048) {
				echo "<script language=javascript>alert('".$FUNCTIONS_Pack['Upload_File_FileSize_Say']."');"; //文件必须小于200K！
				echo "javascript:window.history.back(-1);</script>";
				exit;
			}


			//$extname=substr($File_name,-3);
			$extname = strtolower(substr(strrchr($File_name, "."), 1));
			//$extname = strtolower($extname);

				//如果文件上传条件满足。则将此文件COPY到指定路径中。并赋予数据库中的名称

				$ntime=time();
				$rand =rand(1,31000);
				$File_New            =    $Path."/".$ntime.$rand.".".$extname;
				$File_NewName        =    $ntime.$rand.".".$extname;

				copy($File,$File_New);
				if ($old_File<>"") {
					//如果有旧文件就删除掉旧的！
					@unlink($Path."/".$old_File);
				}


		} else {

			$File_NewName = $old_File;

		}
		//完成新数据的插入工作。

		return $File_NewName;

	}

	function setLog($log){
		global $DB,$INFO;
		$IP = $this->getip();
		$sql = "insert into `{$INFO[DBPrefix]}adminlog` (admin,content,ip,logtime)values('" . $_SESSION['Admin_Sa'] . "','" . $log . "','" . $IP . "','" . time() . "')";
		return $DB->query($sql);
	}
	function authcode($string, $operation, $key = '') {

        $key = md5($key ? $key : $GLOBALS['auth_key']);
        $key_length = strlen($key);

        $string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
        $string_length = strlen($string);

        $rndkey = $box = array();
        $result = '';

        for($i = 0; $i <= 255; $i++) {
                $rndkey[$i] = ord($key[$i % $key_length]);
                $box[$i] = $i;
        }

        for($j = $i = 0; $i < 256; $i++) {
                $j = ($j + $box[$i] + $rndkey[$i]) % 256;
                $tmp = $box[$i];
                $box[$i] = $box[$j];
                $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
                $a = ($a + 1) % 256;
                $j = ($j + $box[$a]) % 256;
                $tmp = $box[$a];
                $box[$a] = $box[$j];
                $box[$j] = $tmp;
                $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
                if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
                        return substr($result, 8);
                } else {
                        return '';
                }
        } else {
                return str_replace('=', '', base64_encode($result));
        }
	}

	function getStorage($State,$StorageNum){
		global $Good;
		switch (intval($State)){
			case 0:
				return 1;
				break;
			case 1:
				if ($StorageNum>0)
					return 1;
				else
					return 0;
				break;
			default:
				break;
		}
		return 1;
	}
	/*-------------------------------------------这里是类结束的地线---------------*/



/**
增加積點
**/
function AddBonuspoint($userid,$point,$type,$content,$saleorlevel,$orderid=0){
	global $DB,$INFO;
	$d = date('d',time());
	$y = intval(date('Y',time()))+1;
	$m = date('m',time());
	if ($point>0){
		$Sql = "insert into `{$INFO[DBPrefix]}bonuspoint` (`point`,user_id,addtime,endtime,usestate,`type`,content,saleorlevel,orderid)values('" . intval($point) . "','" . intval($userid) . "','" . time() . "','" . gmmktime(0,0,0,$m,$d,$y) . "',0,'" . $type . "','" . $content . "','" . $saleorlevel . "','" . $orderid . "')";
		$Result = $DB->query($Sql);
	}
	return $Result;
}


/**
使用消費積點
**/
function BuyBonuspoint($userid,$usepoint,$content,$orderid=0){
	global $DB,$INFO;
	if ($usepoint>0){
		$point = 0;
		$c_sql = "select c.*,cb.usepoint from `{$INFO[DBPrefix]}bonuspoint` as c left join `{$INFO[DBPrefix]}bonusbuydetail` as cb on c.id=cb.combipoint_id where c.user_id=".intval($userid)." and c.endtime>'" . time() . "' and (c.usestate=1 or c.usestate=0 or c.usestate=3) and saleorlevel=1";
		$c_Query =  $DB->query($c_sql);
		while($c_Rs = $DB->fetch_array($c_Query)){
			if ($point<$usepoint){
				$subpoint = 0;
				if(intval($c_Rs['usestate']) == 1 && intval($c_Rs['usepoint']) > 0){
					$combipoint = intval($c_Rs['point']) - intval($c_Rs['usepoint']);
				}else{
					$combipoint = 	intval($c_Rs['point']);
				}
				if ((intval($combipoint)+$point)>$usepoint){
					$subpoint = $usepoint-$point;
					$state = 1;
				}else{
					$subpoint = intval($combipoint);
					$state = 2;
				}
				$i_Sql = "insert into `{$INFO[DBPrefix]}bonusbuydetail` (user_id,combipoint_id,usepoint,usetime,content,orderid) values ('" . intval($userid) . "','" . intval($c_Rs['id']) . "','" . $subpoint . "','" . time() . "','" . $content . "','" . $orderid . "')";
				$DB->query($i_Sql);
				$u_Sql = "update `{$INFO[DBPrefix]}bonuspoint` set usestate = '" . $state . "' where id=" . $c_Rs['id'];
				$DB->query($u_Sql);
				$point = $point+intval($combipoint);
			}
		}
	}
}

/**
增加折價券
**/
function AddTicket($user_id,$ticketid,$username,$count){
	global $DB,$INFO;

	$Sql = "select * from `{$INFO[DBPrefix]}ticket` where ticketid='" . $ticketid . "' and type='0' and `pub_starttime` <= '".date('Y-m-d',time())."' and `pub_endtime` >= '".date('Y-m-d',time())."'";
	$Query =  $DB->query($Sql);
	$Num   =  $DB->num_rows($Query);

	if($ticketid > 0 && $Num > 0){
		$content = "按會員發放:註冊帳號" . $username;
		$sql_user = "select * from `{$INFO[DBPrefix]}user` where user_id='" . $user_id . "'";

		$db_string = $DB->compile_db_insert_string( array (
		'type'          => 2,
		'ticketid'      => $ticketid,
		'count'         => intval($count),
		'content'       => $content,
		'pubtime'       => time(),
		));

		$Sql="INSERT INTO `{$INFO[DBPrefix]}ticketpubrecord` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";

		$Result_Insert=$DB->query($Sql);
		$send_array = array();
		$ti = 0;
		if ($Result_Insert){
			$Query_user    = $DB->query($sql_user);
			$sql_record = "select * from `{$INFO[DBPrefix]}ticketpubrecord` where ticketid='" . $ticketid . "' and type='2' and count='" . intval($count) . "' order by recordid desc ";
			$Query_record    = $DB->query($sql_record);
			$Rs_record=$DB->fetch_array($Query_record);
			$recordid = intval($Rs_record['recordid']);

			while ($Rs_user=$DB->fetch_array($Query_user)) {
				$send_array[$ti] = intval($Rs_user['user_id']);
				$ti++;
				$db_string = $DB->compile_db_insert_string( array (
				'ticketid'          => $ticketid,
				'userid'          => intval($Rs_user['user_id']),
				'count'          => intval($count),
				'recordid'          => $recordid,
				));

				$Sql_i="INSERT INTO `{$INFO[DBPrefix]}userticket` (".$db_string['FIELD_NAMES'].") VALUES (".$db_string['FIELD_VALUES'].")";
				$DB->query($Sql_i);
			}
			$this->setLog("發放折價券");
		}
	}
}

	/**
用戶積分
	**/
	function Userpoint($userid,$saleorlevel){
		global $DB,$INFO;
		if ($saleorlevel==1)
			$subsql = "  and c.endtime>'" . time() . "'  and c.saleorlevel=1 and (c.usestate=0 or c.usestate=1)";
		else
			$subsql = "  and c.saleorlevel=2";
		$c_sql = "select * from `{$INFO[DBPrefix]}bonuspoint` as c where c.user_id=".intval($userid)." " . $subsql . "";
		$c_Query =  $DB->query($c_sql);
		$sumpoint = 0;
		$usepoint = 0;
		while($c_Rs = $DB->fetch_array($c_Query)){
			$sumpoint += intval($c_Rs['point']);
			if ($saleorlevel==1){
				$sql = "select sum(usepoint) as usepoint from `{$INFO[DBPrefix]}bonusbuydetail` where user_id=".intval($userid)." and combipoint_id='" . $c_Rs['id'] . "'";
				$Query =  $DB->query($sql);
				$Rs = $DB->fetch_array($Query);
				$usepoint += intval($Rs['usepoint']);
			}
			//$sumpoint = $sumpoint-$usepoint;
		}

		return (intval($sumpoint)-intval($usepoint));
	}

	function NowUserpoint($userid,$goods_starttime,$goods_endtime,$bonusrecord_start,$bonusrecord_end){
		global $DB,$INFO;
		$Sql="select p.`point` from `{$INFO[DBPrefix]}user` u JOIN (SELECT a.`user_id` , (a.`point` - IFNull(b.`point`,0)) AS point FROM ";
		$Sql.="(SELECT SUM(`point`) AS point ,`user_id`  FROM `{$INFO[DBPrefix]}bonuspoint` WHERE UNIX_TIMESTAMP('".$goods_starttime."') <= `addtime` AND UNIX_TIMESTAMP('".$goods_endtime."') >= `addtime` AND UNIX_TIMESTAMP('".$goods_endtime."') <= `endtime` AND `saleorlevel`=1 GROUP BY `user_id`) a LEFT JOIN ";
		$Sql.="(SELECT SUM(`usepoint`) AS point,bd.`user_id` FROM `{$INFO[DBPrefix]}bonusbuydetail` bd INNER JOIN `{$INFO[DBPrefix]}bonuspoint` bp ON bd.`combipoint_id`=bp.`id` WHERE UNIX_TIMESTAMP('".$goods_starttime." 23:59:59') >= bp.`addtime` AND UNIX_TIMESTAMP('".$goods_endtime."') <= bp.`endtime` AND bd.`usetime` <= UNIX_TIMESTAMP('".$goods_endtime."') AND bp.`saleorlevel`=1 GROUP BY bd.`user_id`) b ";
		$Sql.="ON a.`user_id`=b.`user_id` WHERE (a.`point` - IFNull(b.`point`,0)) BETWEEN '".$bonusrecord_start."' AND '".$bonusrecord_end."') p ON (u.`user_id`=p.`user_id`) WHERE p.`user_id`='".intval($userid)."'";
		$Query =  $DB->query($Sql);
		$Rs = $DB->fetch_array($Query);
		$usepoint = intval($Rs['point']);
		return $usepoint;
	}
	/**
	會員編號
	**/
	function setMemberCode($firstcode){
		if($firstcode=="")
			$firstcode = "A";
		global $DB,$INFO;
		$c_sql = "select memberno from `{$INFO[DBPrefix]}user` where memberno like '" . $firstcode . "%' order by memberno desc limit 0,1 ";
		$c_Query =  $DB->query($c_sql);
		$c_Rs = $DB->fetch_array($c_Query);
		$lastmemberno = $c_Rs['memberno'] ;
		$code1 = substr($lastmemberno,0,1);
		$code2 = substr($lastmemberno,1,1);
		$code3 = substr($lastmemberno,2);
		if ($lastmemberno ==""){
			$code2 = "A";
			$code3 = 0;
		}
		if (intval($code3)<999999){
			$newcode3 = intval($code3)+1;
			$newcode3 = str_repeat("0",6-strlen($newcode3)) . $newcode3;
			$newcode2 = $code2;
		}else{
			$newcode3 = "000001";
			$newcode2 = chr(ord($code2)+1);
		}
		$newcode1 = $firstcode;
		return $newcode1.$newcode2.$newcode3;
	}

	/**
	設置庫存量
	$storagetype0正1負
	**/
	function setStorage ($count,$storagetype,$gid,$detail_id=0,$size="",$color="",$content="",$sales=0,$auto=0,$order_id=0,$xygoods=array(),$ifadmin=1){
		global $DB,$INFO;
		$newstorage =-999999999;

		if ($gid>0 && ($count>0 || $sales>=0)){
			if ($detail_id>0){
				$detail_Sql   =  "select * from `{$INFO[DBPrefix]}goods_detail` where gid='" . $gid . "' and detail_id='" . $detail_id . "'";
				$detail_Query =  $DB->query($detail_Sql);
				$detail_Num   =  $DB->num_rows($detail_Query );
				if ($detail_Num>0){
					$detail_Rs = $DB->fetch_array($detail_Query);
					$storage = intval($detail_Rs['storage']);
					$newstorage = $storagetype==0?$storage+intval($count):$storage-intval($count);
					$update_Sql = "update `{$INFO[DBPrefix]}goods_detail` set storage='" . $newstorage . "', sales='" . $sales . "' where gid='" . $gid . "' and detail_id='" . $detail_id . "'";
				}
			}elseif(count($xygoods)>0 && is_array($xygoods)){
				foreach($xygoods as $k=>$v){
					$goods_Sql = "select * from `{$INFO[DBPrefix]}goods` where gid='" . $v . "'";
					$goods_Query =  $DB->query($goods_Sql);
					$goods_Rs = $DB->fetch_array($goods_Query);
					$storage = intval($goods_Rs['storage']);
					$newstorage = $storagetype==0?$storage+intval($count):$storage-intval($count);
					$update_Sql_x = "update `{$INFO[DBPrefix]}goods` set storage='" . $newstorage . "', sales='" . $sales . "' where gid='" . $v . "'";
					$DB->query($update_Sql_x);
				}
			}elseif ($size!="" || $color!=""){
				$size_Sql = "select * from `{$INFO[DBPrefix]}storage` where size='" . $size . "' and color='" . $color . "' and goods_id='" . $gid . "'";
				$size_Query =  $DB->query($size_Sql);
				$size_Num   =  $DB->num_rows($size_Query );
				if ($size_Num>0){
					$size_Rs = $DB->fetch_array($size_Query);
					$storage = intval($size_Rs['storage']);
					$storage_id = intval($size_Rs['storage_id']);
					$newstorage = $storagetype==0?$storage+intval($count):$storage-intval($count);
					$update_Sql = "update `{$INFO[DBPrefix]}storage` set storage='" . $newstorage . "', sales='" . $sales . "' where size='" . $size . "' and color='" . $color . "' and goods_id='" . $gid . "'";
				}else{
					$newstorage = $storagetype==0?intval($count):0-intval($count);
					$update_Sql = "insert into `{$INFO[DBPrefix]}storage` (color,size,goods_id,storage,sales) values ('" . $color . "','" . $size . "','" . $gid . "','" . $newstorage ."','" . $sales ."')";
					$insert = 1;
				}
			}else{
				$goods_Sql = "select * from `{$INFO[DBPrefix]}goods` where gid='" . $gid . "'";
				$goods_Query =  $DB->query($goods_Sql);
				$goods_Num   =  $DB->num_rows($goods_Query );
				if ($goods_Num>0){
					$goods_Rs = $DB->fetch_array($goods_Query);
					$storage = intval($goods_Rs['storage']);
					 $newstorage = $storagetype==0?$storage+intval($count):$storage-intval($count);
					 $update_Sql = "update `{$INFO[DBPrefix]}goods` set storage='" . $newstorage . "', sales='" . $sales . "' where gid='" . $gid . "'";
				}
			}
			if ($detail_id>0 || $size!="" || $color!=""){
				$goods_Sql = "select * from `{$INFO[DBPrefix]}goods` where gid='" . $gid . "'";
				$goods_Query =  $DB->query($goods_Sql);
				$goods_Num   =  $DB->num_rows($goods_Query );
				if ($goods_Num>0){
					$goods_Rs = $DB->fetch_array($goods_Query);
					$storage_g = intval($goods_Rs['storage']);
					$newstorage_g = $storagetype==0?$storage_g+intval($count):$storage_g-intval($count);
					$update_Sql_g = "update `{$INFO[DBPrefix]}goods` set storage='" . $newstorage_g . "' where gid='" . $gid . "'";
					$DB->query($update_Sql_g);
				}
			}
			//echo $newstorage;exit;
			if ($newstorage!=-999999999){
			//echo "aa";exit;
				if ($update_Sql!=""){
					$DB->query($update_Sql);
					if ($insert == 1)
						$storage_id = mysql_insert_id();
				}
				if($ifadmin==1){
					$user_id = $_SESSION['sa_id'];
					$usertype = $_SESSION['LOGINADMIN_TYPE'];
				}else{
					$user_id = $_SESSION['user_id'];
					$usertype = -1;

				}
				$insert_Sql = "insert into `{$INFO[DBPrefix]}storagelog`(gid,detail_id,storage_id,changes,counts,storagetype,content,user_id,user_type,datetime,auto,order_id) values ('" . $gid . "','" . $detail_id . "','" . $storage_id . "','" . $count . "','" . $newstorage . "','" . $storagetype . "','" . $content . "','" . $user_id . "','" . $usertype . "','" . time() . "','" . $auto . "','" . $order_id . "')";
				$DB->query($insert_Sql);

			}
		}

	}

	/**
	設置客服記錄
	**/
	function setKefuLog($kefu_id,$content,$ifadmin=1){
		global $DB,$INFO;
		if($ifadmin==1){
			$user_id = $_SESSION['sa_id'];
			$usertype = $_SESSION['LOGINADMIN_TYPE'];
		}else{
			$user_id = $_SESSION['user_id'];
			$usertype = -1;
		}
		$log_Sql = "insert into `{$INFO[DBPrefix]}kefu_log` (kefu_id,user_id,usertype,content,pubtime) values ('" . $kefu_id . "','" . $user_id . "','" . $usertype . "','" . $content . "','" . time() . "')";
		$DB->query($log_Sql);
	}

	/**
	某分類的一級分類的折扣
	**/
	function getTopClass($bid){
		global $DB,$INFO;
		$Sql = "select * from `{$INFO[DBPrefix]}bclass` where bid='" . intval($bid) . "'";
		$Query =  $DB->query($Sql);
		$Rs = $DB->fetch_array($Query);
		//echo $Rs['top_id'];
		if ($Rs['top_id']>0)
		return	$this->getTopClass($Rs['top_id']);
		if(time()>=$Rs['saleoff_starttime'] && time()<=$Rs['saleoff_endtime'])
			$return = array(intval($Rs['rebate']),intval($Rs['costrebate']));
		else
			$return = array(0,0);
			//print_r($return);
		return $return;
	}

/**
增加團購金
**/
function AddGrouppoint($userid,$point,$content,$orderid=0,$sa_id=0,$sa_type=0){
	global $DB,$INFO;
	$d = date('d',time());
	$y = intval(date('Y',time()))+1;
	$m = date('m',time());
	if ($point>0){
		$Sql = "insert into `{$INFO[DBPrefix]}grouppoint` (`point`,user_id,addtime,endtime,usestate,content,orderid,sa_id,sa_type)values('" . intval($point) . "','" . intval($userid) . "','" . time() . "','" . gmmktime(0,0,0,$m,$d,$y) . "',0,'" . $content . "','" . $orderid . "','" . $sa_id . "','" . $sa_type . "')";
		$Result = $DB->query($Sql);
	}
	return $Result;
}


/**
使用團購金
**/
function BuyGrouppoint($userid,$usepoint,$content,$orderid=0,$sa_id=0,$sa_type=0){
	global $DB,$INFO;
	if ($usepoint>0){
		$point = 0;
		$c_sql = "select c.*,cb.usepoint from `{$INFO[DBPrefix]}grouppoint` as c left join `{$INFO[DBPrefix]}grouppointbuydetail` as cb on c.id=cb.grouppoint_id where c.user_id=".intval($userid)." and c.endtime>'" . time() . "' and (c.usestate=1 or c.usestate=0 or c.usestate=3) ";
		$c_Query =  $DB->query($c_sql);
		while($c_Rs = $DB->fetch_array($c_Query)){
			if ($point<$usepoint){
				$subpoint = 0;
				if(intval($c_Rs['usestate']) == 1 && intval($c_Rs['usepoint']) > 0){
					$combipoint = intval($c_Rs['point']) - intval($c_Rs['usepoint']);
				}else{
					$combipoint = 	intval($c_Rs['point']);
				}
				if ((intval($combipoint)+$point)>$usepoint){
					$subpoint = $usepoint-$point;
					$state = 1;
				}else{
					$subpoint = intval($combipoint);
					$state = 2;
				}
				$i_Sql = "insert into `{$INFO[DBPrefix]}grouppointbuydetail` (user_id,grouppoint_id,usepoint,usetime,content,orderid,sa_id,sa_type) values ('" . intval($userid) . "','" . intval($c_Rs['id']) . "','" . $subpoint . "','" . time() . "','" . $content . "','" . $orderid . "','" . $sa_id . "','" . $sa_type . "')";
				$DB->query($i_Sql);
				$u_Sql = "update `{$INFO[DBPrefix]}grouppoint` set usestate = '" . $state . "' where id=" . $c_Rs['id'];
				$DB->query($u_Sql);
				$point = $point+intval($combipoint);
			}
		}
	}
}
	/**
團購金
**/
	function Grouppoint($userid){
		global $DB,$INFO;
		$c_sql = "select sum(c.point) as sumpoint from `{$INFO[DBPrefix]}grouppoint` as c where c.user_id=".intval($userid)." " . $subsql . "";
		$c_Query =  $DB->query($c_sql);
		$c_Rs = $DB->fetch_array($c_Query);
		$sumpoint = intval($c_Rs['sumpoint']);
		//if ($saleorlevel==1){
			$sql = "select sum(usepoint) as usepoint from `{$INFO[DBPrefix]}grouppointbuydetail` where user_id=".intval($userid)."";
			$Query =  $DB->query($sql);
			$Rs = $DB->fetch_array($Query);
			$usepoint = intval($Rs['usepoint']);
		//}

		return (intval($sumpoint)-intval($usepoint));
	}

		/**
	增加團購金
	**/
	function AddBuypoint($userid,$point,$type,$content,$orderid=0,$sa_id=0,$sa_type=0,$buypointtype = 4){
		global $DB,$INFO;
		$d = date('d',time());
		$y = intval(date('Y',time()))+1;
		$m = date('m',time());
		if ($point>0){
			$Sql = "insert into `{$INFO[DBPrefix]}buypoint` (`point`,user_id,addtime,content,orderid,sa_id,sa_type,type,buypointtype)values('" . intval($point) . "','" . intval($userid) . "','" . time() . "','" . $content . "','" . $orderid . "','" . $sa_id . "','" . $sa_type . "','" . $type . "','" . $buypointtype . "')";
			$Result = $DB->query($Sql);
		}
		return $Result;
	}
/**
團購金
**/
	function Buypoint($userid){
		global $DB,$INFO;
		$c_sql = "select sum(c.point) as sumpoint from `{$INFO[DBPrefix]}buypoint` as c where c.user_id=".intval($userid)." and c.type=0" . $subsql . "";
		$c_Query =  $DB->query($c_sql);
		$c_Rs = $DB->fetch_array($c_Query);
		$sumpoint = intval($c_Rs['sumpoint']);
		//if ($saleorlevel==1){
			$sql = "select sum(c.point) as sumpoint from `{$INFO[DBPrefix]}buypoint` as c where c.user_id=".intval($userid)." and c.type=1" . $subsql . "";
			$Query =  $DB->query($sql);
			$Rs = $DB->fetch_array($Query);
			$usepoint = intval($Rs['sumpoint']);
		//}

		return (intval($sumpoint)-intval($usepoint));
	}

	function getOrderUInfo($str,$len){
		include_once ("Char.class.php");
		$Char_Class = new Char_class();
		return "*" . $Char_Class->cut_str(trim($str),$len,1) . "*****";
	}

	/**
	瀏覽等級
	**/
	function CheckUserView($type="goods",$id){
		global $DB,$INFO;
		$ifview = 0;
		if($type=="article"){
			$datatable  = "news_userlevel";
			$title = "文章";
		}else{
			$datatable  = "goods_userlevel";
			$title = "商品";
		}
		 $viewlevel_sql = "select * from `{$INFO[DBPrefix]}" . $datatable . "` as gu inner join `{$INFO[DBPrefix]}user_level` as ul on gu.levelid=ul.level_id where gu.gid='" . intval($id) . "'";
		$Query_viewlevel = $DB->query($viewlevel_sql);
		$viewlevel = array();
		$v = 0;
		while ($Result_viewlevel=$DB->fetch_array($Query_viewlevel)){
			$viewlevel[$v] = $Result_viewlevel['level_name'];
			if (intval($_SESSION['user_level'])>0 && intval($Result_viewlevel['level_id'])==intval($_SESSION['user_level'])){
				$ifview = 1;
			}
			$v++;
		}

		$viewlevel_string = "";
		if (count($viewlevel)>0)
			$viewlevel_string = "僅允許" . implode(" ",$viewlevel) . "查看" . $title . "詳細信息";

		if ($viewlevel_string != "" && $ifview == 0){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'><script language='javascript'>alert('" . $viewlevel_string . "');location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";exit;
		}
	}
	function getSaleOrder($user_id){
		global $DB,$INFO;
		$Sql = "select count(*) as counts from `{$INFO[DBPrefix]}order_table` where user_id='" . $user_id . "' and order_state<>3";
		$Query = $DB->query($Sql);
		$Result=$DB->fetch_array($Query);
		if($Result['counts']>0)
			return 0;
		else
			return 1;
	}

	/**
	商品後臺操作明細
	$action_type 1新增2修改3複製4刪除
	**/
	function setGoodsAction($gid,$action_type,$remark,$field_array = array()){
		global $DB,$INFO;
		$user_id = $_SESSION['sa_id'];
		$usertype = $_SESSION['LOGINADMIN_TYPE'];
		if(is_array($field_array[0]))
			$action_field = implode(",",$field_array[0]);
		if(is_array($field_array[1]))
			$action_value = implode(",",$field_array[1]);
		$Sql_insert = "insert into `{$INFO[DBPrefix]}goods_action` (gid,user_id,usertype,remark,actiontime,state_type,action_field,action_value) values ('" . $gid . "','" . $user_id . "','" . $usertype . "','" . $remark . "','" . time() . "','" . $state_type . "','" . $action_field . "','" . $action_value . "')";
		$DB->query($Sql_insert);
		return 1;
	}
	/***
	判斷商品字段差異
	***/
	function checkGoodsField($gid,$spe_field,$action_type){
		global $DB,$INFO;
		$change_array = array();
		$Query = $DB->query("select * from `{$INFO[DBPrefix]}goods` where gid=".intval($gid)." limit 0,1");
		$Result= $DB->fetch_array($Query);
		$i = 0;
		$field_array = array(
					"goodsname" => "商品名稱",
					"bid" => "商品分類",
					//"bn" => "商品貨號",
					"brand_id" => "商品分類",
					"intro" => "簡介",
					"unit" => "單位",
					"keywords" => "keywords",
					"pricedesc" => "網購價格",
					"price" => "建議市價",
					"memberprice" => "會員價",
					"combipoint" => "折抵紅利點數",
					"cost" => "成本價",
					"salecost" => "促銷成本價",
					"point" => "積分點數",
					"alarmcontent" => "使用規則",
					"ifbonus" => "是否紅利商品",
					"bonusnum" => "積分點數",
					"ifalarm" => "庫存警告",
					"alarmnum" => "庫存警告數量",
					//"ifpub" => "是否發佈",
					"ifrecommend" => "是否推薦",
					"ifspecial" => "是否特價",
					"subject_id" => "主題商品",
					"ifhot" => "是否熱賣",
					"ifjs" => "是否集殺商品",
					"video_url" => "商品影音資料",
					"ifgl" => "指定相關產品",
					"js_begtime" => "集殺時效1",
					"js_endtime" => "集殺時效2",
					"js_price" => "集殺價",
					"jscount" => "集殺件數",
					"js_totalnum" => "集殺已累計件數",
					"provider_id" => "供應商",
					"smallimg" => "商品圖",
					"body" => "詳細描述",
					"ifpresent" => "額滿禮商品",
					"present_money" => "額滿禮金額1",
					"present_endmoney" => "額滿禮金額2",
					"cap_des" => "成分規格",
					"ifxygoods" => "超值任選不同商品",
					"ifxy" => "屬於超值任選商品",
					"ifchange" => "是否是加購商品",
					"xycount" => "任選商品數量",
					"sale_name" => "促銷廣告標語",
					"salename_color" => "促銷廣告標語顏色",
					"sale_subject" => "同商品多件折扣主題",
					"sale_price" => "折扣價格",
					"ifsales" => "同商品多件折扣",
					"ifsaleoff" => "整點促銷商品（未到促銷時間段不能進行購買活動）",
					"saleoff_starttime" => "促銷時間1",
					"saleoff_endtime" => "促銷時間2",
					"ifadd" => "是否是滿額加購商品",
					"addmoney" => "額滿加購金額",
					"addprice" => "加購價格",
					"oeid" => "合作項目代號",
					"saleoffprice" => "促銷價格",
					"iftimesale" => "整點促銷商品",
					"view_num" => "查看次數",
					"timesale_starttime" => "促銷時間1",
					"timesale_endtime" => "促銷時間2",
					"trans_type" => "配送方式",
					"iftransabroad" => "是否支持海外配送",
					"trans_special" => "特殊配送方式",
					"trans_special_money" => "特殊配送費用",
					"transtype" => "貨運寄送類",
					"addtransmoney" => "運費加價",
					"transtypemonty" => "每件運費",
					"ifmood" => "是否滿額免運費",
					"iftogether" => "統倉商品",
					"guojima" => "國際碼",
					"xinghao" => "型號",
					"weight" => "weight",
					"shopclass" => "商店商品分類",
					"shopid" => "商店",
					"month" => "分期付款",
					"chandi" => "產地",
					"ERP" => "ERP",
					"bounsgoods" => "是否為紅利館商品",
					"bounsprice" => "自付額",
					"bounschange" => "現金",
		);
		foreach($Result as $k=>$v){
			switch($k){

				case "js_begtime":
				case "js_endtime":
				case "js_price":
				case "smallimg":
				case "saleoff_starttime":
				case "saleoff_endtime":
				case "timesale_starttime":
				case "timesale_endtime":
				case "jscount":
				case "month":
				case "subject_id":

					if(($v!=trim($spe_field[$k]) || $action_type==1) && trim($spe_field[$k])!=""){
						$change_array1[$i] = $field_array[$k];
						$change_array2[$i] = $v . "=>" . trim($spe_field[$k]);
						$i++;
					}
					break;
				case "body":
					if(($v!=trim($spe_field[$k]) || $action_type==1) && trim($spe_field[$k])!=""){
						$change_array1[$i] = $field_array[$k];
						$change_array2[$i] = "修改";
						$i++;
					}
					break;
				default:
					if(($v!=$_POST[$k]||$action_type==1) && trim($_POST[$k])!="" && $k!="appoint_sendtype"){
						//echo $k . "|" . trim($_POST[$k]);
						$change_array1[$i] = $field_array[$k];
						$change_array2[$i] = $v . "=>" . trim($_POST[$k]);
						$i++;
					}
					break;
			}

		}
		return array($change_array1,$change_array2);
	}
	/**
	得到訂單詳細資料
	**/
	function getOrderDetail($order_id,$Rs_order=array()){
		global $DB,$INFO;
		//訂單信息
		if(intval($Rs_order['order_id'])==0){
			$Sql_order = "select * from `{$INFO[DBPrefix]}order_table` where order_id = '" . intval($order_id) . "'";
			$Query_order  = $DB->query($Sql_order);
			$Rs_order=$DB->fetch_array($Query_order);
		}
		$discount_totalPrices = $Rs_order['discount_totalPrices'];
		$totalprice = $Rs_order['totalprice'];
		$saleoff = $discount_totalPrices/$totalprice;//折扣比例
		$saleoffprice = $totalprice-$discount_totalPrices;//優惠金額
		$d_Sql = "select od.* from `{$INFO[DBPrefix]}order_detail` as od where od.order_id='" . $order_id . "'";
		$d_Query    = $DB->query($d_Sql);
		$i = 0;
		$order_array = array();
		$i = 0;
		while ($d_Rs = $DB->fetch_array($d_Query)){
			if($d_Rs['packgid']==0){
				$order_array[$i] = $d_Rs;
				$order_array[$i]['total']=round($d_Rs['goodscount']*$d_Rs['price']*$saleoff,0);//完稅金額
				$order_array[$i]['total1']=round($order_array[$i]['total']/1.05,0);//未稅金額
				$order_array[$i]['saleoff']=round($d_Rs['goodscount']*$d_Rs['price']*(1-$saleoff),0);//折扣金額

				$i++;
			}
		}
		//糾正金額及折扣金額誤差
		$t = 0;//金額總計
		foreach($order_array as $k=>$v){
			$t += $v['total'];
		}
		$s = 0;//折扣總計
		foreach($order_array as $k=>$v){
			$s += $v['saleoff'];
		}
		$order_array[0]['total'] = $order_array[0]['total'] + ($discount_totalPrices-$t);
		$order_array[0]['total1'] = round($order_array[0]['total']/1.05,0);
		$order_array[0]['saleoff'] = $order_array[$i]['saleoff'] + ($saleoffprice-$s);
		return $order_array;
	}
	/**	 * 全形/半形轉換	*
	* @param string $strs 欲轉換的 ASCII 字元
	* @param int $types 字形模式 1|0 (半形|全形)
	* @return string 轉換後的對應字串	 */
	function strShiftSpace($strs, $types = 1){
		$nt = array(
		"(", ")", "[", "]", "{", "}", ".", ",", ";", ":",
		"-", "?", "!", "@", "#", "$", "%", "&", "|", "\\",
		"/", "+", "=", "*", "~", "`", "'", "\"", "<", ">",
		"^", "_",
		"0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
		"a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
		"k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
		"u", "v", "w", "x", "y", "z",
		"A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
		"K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
		"U", "V", "W", "X", "Y", "Z"," "
	);
		$wt = array(
		"（", "）", "〔", "〕", "｛", "｝", "﹒", "，", "；", "：",
		"－", "？", "！", "＠", "＃", "＄", "％", "＆", "｜", "＼",
		"／", "＋", "＝", "＊", "～", "、", "、", "”", "＜", "＞",
		"︿", "＿",
		"０", "１", "２", "３", "４", "５", "６", "７", "８", "９",
		"ａ", "ｂ", "ｃ", "ｄ", "ｅ", "ｆ", "ｇ", "ｈ", "ｉ", "ｊ",
		"ｋ", "ｌ", "ｍ", "ｎ", "ｏ", "ｐ", "ｑ", "ｒ", "ｓ", "ｔ",
		"ｕ", "ｖ", "ｗ", "ｘ", "ｙ", "ｚ",
		"Ａ", "Ｂ", "Ｃ", "Ｄ", "Ｅ", "Ｆ", "Ｇ", "Ｈ", "Ｉ", "Ｊ",
		"Ｋ", "Ｌ", "Ｍ", "Ｎ", "Ｏ", "Ｐ", "Ｑ", "Ｒ", "Ｓ", "Ｔ",
		"Ｕ", "Ｖ", "Ｗ", "Ｘ", "Ｙ", "Ｚ","　"
	);
	if ($types == 0){
		$strtmp = str_replace($nt, $wt, $strs);
	}else{
		$strtmp = str_replace($wt, $nt, $strs);
	}
	return $strtmp;
}
/**	 * ASCII URL字元轉換	 *
* @param string $strs 欲轉換的 ASCII 字元
* @return string 轉換後的對應字元	 */
function strUrlEncode($strs) {
	$strs = trim(str_replace(" ", "_", $strs));
	$strs = trim(str_replace("%", "％", $strs));
	$strs = trim(str_replace("/", "／", $strs));
	return $strs;
}
function setjson($str){
	 $firstchar = substr($str,0,1);
	 $lastchar = substr($str,strlen($str)-1,1);
	 if($firstchar=="["){
		 return substr($str,1,strlen($str)-2);
	 }else{
		 return $str;
	 }
 }
}


?>
