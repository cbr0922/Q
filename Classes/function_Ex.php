<?php

function this_form($Sid,$Bid,$S_fieldName,$Sclass){

	global $DB;
	echo "<script language=javascript>\n";
	echo "function StreetIdName(sid,sname)\n";
	echo "{\n";
	echo "	this.id=sid;\n";
	echo "	this.name=sname\n";
	echo "}\n";
	echo "function changeZip(frm)\n";
	echo "{\n";
	echo "	frm.streetid.length = 1;\n";
	echo "	if(frm.zipcodeid.value != \"\")\n";
	echo "	{\n";
	echo "		for(i=0; i<street.length; i++)\n";
	echo "		{\n";
	echo "			if(frm.zipcodeid.value == street[i][0])\n";
	echo "			{\n";
	echo "				for(j=1; j<street[i].length; j++)\n";
	echo "				{\n";
	echo "					frm.streetid.length++;\n";
	echo "					frm.streetid[j].value = street[i][j].id;\n";
	echo "					frm.streetid[j].text = street[i][j].name;\n";
	echo "				}\n";
	echo "				break;\n";
	echo "			}\n";
	echo "		}\n";
	echo "	}\n";
	echo "}\n";

	//select 二级类的ID,大类的ID，二级类的中名名称， FROM 二级类表名,ORDER BY 大类的ID，二级类的中文名称
	$selectStreetSql = "select ".$Sid.",".$Bid.",".$S_fieldName." from ".$Sclass." order by ".$Bid.",".$S_fieldName."";

	//$selectStreetSql = "select * from "" order by channel_id,second_name";
	$resultStreet    = $DB->query($selectStreetSql);
	$totalStreet = $DB->num_rows($resultStreet);
	$streetCount = 0;
	$zipCount = -1;
	$currentZipcode = -1;
	$jsZipAndStreet = "var street = new Array();\n";

	for($i = 0; $i < $totalStreet; $i++)
	{
		$rowStreet = $DB->fetch_array($resultStreet);
		if($currentZipcode != $rowStreet[1])
		{
			$currentZipcode = $rowStreet[1];
			$zipCount++;
			$streetCount = 0;
			$jsZipAndStreet .= "street[$zipCount] = new Array();\n street[$zipCount][0]=".$rowStreet[1].";\n";
		}
		$streetCount++;
		$jsZipAndStreet .= "street[$zipCount][$streetCount] = new StreetIdName(".$rowStreet[0].",\"".$rowStreet[2]."\");\n";
	}


	echo $jsZipAndStreet;

	echo "</script>\n";

}

function city_street($Bclass,$Bid,$B_fieldName,$B_inValue,$Sclass,$Sid,$S_fieldName,$S_inValue){
	// 大类的表名： $Bclass    大类的ID名：$Bid   大类的字段名：$B_fieldName   带入大类的值 $B_inValue
	// 二类的表名： $Sclass         ID 名 $Sid     字段名：$S_fieldName          代入的值    $S_inValue
	global $DB;
	echo "
     <select name='zipcodeid' onchange='JavaScript: changeZip(this.form);'>
     <option value=''>－请选择－</option>
     ";

	$Sql="select * from ".$Bclass." order by ".$Bid." asc";
	$Query = $DB->query($Sql);
	$Num   = $DB->num_rows($Query);

	if ( 0 < $Num ){

		while ( $Result = $DB->fetch_array( $Query ) ) {

			echo "<option value=".$Result[$Bid]." ";
			if ($Result[$Bid] == $B_inValue) {
				echo " selected=\"selected\"  ";
			}
			echo "  > ".$Result[$B_fieldName]."</option>\n";

		}
	}

	echo "
      </select>      
	  
	  
	  <select name='streetid' id='streetid'>
      <option value=''>－－请选则－－</option>
      ";     

	if ($B_inValue!='' && $S_inValue!=''){

		$Sql="select * from ".$Sclass." where ".$Bid."=".$B_inValue;
		$Query = $DB->query($Sql);
		$Num   = $DB->num_rows($Query);

		if ( 0 < $Num ){

			while ( $Result = $DB->fetch_array( $Query ) ) {

				echo "<option value=".$Result[$Sid]." ";
				if ($Result[$Sid] == $S_inValue) {
					echo " selected=\"selected\"  ";
				}
				echo "  > ".$Result[$S_fieldName]."</option>\n";
			}
		}
	}

	echo "
		 </select>
          ";

}

//**********##################################################*******\


?>