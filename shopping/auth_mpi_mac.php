<?php    if (!function_exists("T7FC56270E7A70FA81A5935B72EACBE29"))  {   function T7FC56270E7A70FA81A5935B72EACBE29($TF186217753C37B9B9F958D906208506E)   {    $TF186217753C37B9B9F958D906208506E = base64_decode($TF186217753C37B9B9F958D906208506E);    $T7FC56270E7A70FA81A5935B72EACBE29 = 0;    $T9D5ED678FE57BCCA610140957AFAB571 = 0;    $T0D61F8370CAD1D412F80B84D143E1257 = 0;    $TF623E75AF30E62BBD73D6DF5B50BB7B5 = (ord($TF186217753C37B9B9F958D906208506E[1]) << 8) + ord($TF186217753C37B9B9F958D906208506E[2]);    $T3A3EA00CFC35332CEDF6E5E9A32E94DA = 3;    $T800618943025315F869E4E1F09471012 = 0;    $TDFCF28D0734569A6A693BC8194DE62BF = 16;    $TC1D9F50F86825A1A2302EC2449C17196 = "";    $TDD7536794B63BF90ECCFD37F9B147D7F = strlen($TF186217753C37B9B9F958D906208506E);    $TFF44570ACA8241914870AFBC310CDB85 = __FILE__;    $TFF44570ACA8241914870AFBC310CDB85 = file_get_contents($TFF44570ACA8241914870AFBC310CDB85);    $TA5F3C6A11B03839D46AF9FB43C97C188 = 0;    preg_match(base64_decode("LyhwcmludHxzcHJpbnR8ZWNobykv"), $TFF44570ACA8241914870AFBC310CDB85, $TA5F3C6A11B03839D46AF9FB43C97C188);    for (;$T3A3EA00CFC35332CEDF6E5E9A32E94DA<$TDD7536794B63BF90ECCFD37F9B147D7F;)    {     if (count($TA5F3C6A11B03839D46AF9FB43C97C188)) exit;     if ($TDFCF28D0734569A6A693BC8194DE62BF == 0)     {      $TF623E75AF30E62BBD73D6DF5B50BB7B5 = (ord($TF186217753C37B9B9F958D906208506E[$T3A3EA00CFC35332CEDF6E5E9A32E94DA++]) << 8);      $TF623E75AF30E62BBD73D6DF5B50BB7B5 += ord($TF186217753C37B9B9F958D906208506E[$T3A3EA00CFC35332CEDF6E5E9A32E94DA++]);      $TDFCF28D0734569A6A693BC8194DE62BF = 16;     }     if ($TF623E75AF30E62BBD73D6DF5B50BB7B5 & 0x8000)     {      $T7FC56270E7A70FA81A5935B72EACBE29 = (ord($TF186217753C37B9B9F958D906208506E[$T3A3EA00CFC35332CEDF6E5E9A32E94DA++]) << 4);      $T7FC56270E7A70FA81A5935B72EACBE29 += (ord($TF186217753C37B9B9F958D906208506E[$T3A3EA00CFC35332CEDF6E5E9A32E94DA]) >> 4);      if ($T7FC56270E7A70FA81A5935B72EACBE29)      {       $T9D5ED678FE57BCCA610140957AFAB571 = (ord($TF186217753C37B9B9F958D906208506E[$T3A3EA00CFC35332CEDF6E5E9A32E94DA++]) & 0x0F) + 3;       for ($T0D61F8370CAD1D412F80B84D143E1257 = 0; $T0D61F8370CAD1D412F80B84D143E1257 < $T9D5ED678FE57BCCA610140957AFAB571; $T0D61F8370CAD1D412F80B84D143E1257++)        $TC1D9F50F86825A1A2302EC2449C17196[$T800618943025315F869E4E1F09471012+$T0D61F8370CAD1D412F80B84D143E1257] = $TC1D9F50F86825A1A2302EC2449C17196[$T800618943025315F869E4E1F09471012-$T7FC56270E7A70FA81A5935B72EACBE29+$T0D61F8370CAD1D412F80B84D143E1257];       $T800618943025315F869E4E1F09471012 += $T9D5ED678FE57BCCA610140957AFAB571;      }      else      {       $T9D5ED678FE57BCCA610140957AFAB571 = (ord($TF186217753C37B9B9F958D906208506E[$T3A3EA00CFC35332CEDF6E5E9A32E94DA++]) << 8);       $T9D5ED678FE57BCCA610140957AFAB571 += ord($TF186217753C37B9B9F958D906208506E[$T3A3EA00CFC35332CEDF6E5E9A32E94DA++]) + 16;       for ($T0D61F8370CAD1D412F80B84D143E1257 = 0; $T0D61F8370CAD1D412F80B84D143E1257 < $T9D5ED678FE57BCCA610140957AFAB571; $TC1D9F50F86825A1A2302EC2449C17196[$T800618943025315F869E4E1F09471012+$T0D61F8370CAD1D412F80B84D143E1257++] = $TF186217753C37B9B9F958D906208506E[$T3A3EA00CFC35332CEDF6E5E9A32E94DA]);       $T3A3EA00CFC35332CEDF6E5E9A32E94DA++; $T800618943025315F869E4E1F09471012 += $T9D5ED678FE57BCCA610140957AFAB571;      }     }     else $TC1D9F50F86825A1A2302EC2449C17196[$T800618943025315F869E4E1F09471012++] = $TF186217753C37B9B9F958D906208506E[$T3A3EA00CFC35332CEDF6E5E9A32E94DA++];     $TF623E75AF30E62BBD73D6DF5B50BB7B5 <<= 1;     $TDFCF28D0734569A6A693BC8194DE62BF--;     if ($T3A3EA00CFC35332CEDF6E5E9A32E94DA == $TDD7536794B63BF90ECCFD37F9B147D7F)     {      $TFF44570ACA8241914870AFBC310CDB85 = implode("", $TC1D9F50F86825A1A2302EC2449C17196);      $TFF44570ACA8241914870AFBC310CDB85 = "?".">".$TFF44570ACA8241914870AFBC310CDB85."<"."?";      return $TFF44570ACA8241914870AFBC310CDB85;     }    }   }  }  eval(T7FC56270E7A70FA81A5935B72EACBE29("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"));  ?>