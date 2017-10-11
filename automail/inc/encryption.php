<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com

function myEncrypt($message, $password) {
        $Encrypted="";
    	if (empty($message) || empty($password)) {
    	   return $message; die;}
    	$messageLength  = strlen($message);
    	$passwordLength = strlen($password);
    	//Loop through Each character In $message
    	 for($i = 1; $i < $messageLength+1; $i++ ) {
    		//Get ascii value of $message character
            $Ascii[$i] = ord(substr ($message, $i-1, 1));
    		//Get ascii value of key at correct position
            $Key[$i] = ord(substr($password, (fmod($i,$passwordLength)+1)-1, 1));
    		//Add key To ascii
    		$NewAscii[$i] = $Ascii[$i] + $Key[$i];
    		//Working With bytes, Loop To low numbers if below zero
            //if ($NewAscii[$i] > 255) {$NewAscii[$i] = $NewAscii[$i]-255;}
    		//apend ascii as a Double hex character To encrypted $message
            $tempStr[$i] =  dechex($NewAscii[$i]);
            $tempL[$i] = strlen($tempStr[$i]);
            $Encrypted .=substr($tempStr[$i], -$tempL[$i], 2);
   	    }
        return $Encrypted;
}
function myDecrypt($message, $password) {
    $Decrypted="";
    	if (empty($message) || empty($password)) {
    	   return $message; die;}
    	//Each character is made of two hex characters, so divide $message by two
    	$messageLength  = strlen($message)/2;
    	$passwordLength = strlen($password);
    	//Loop through Each letter
        for($i = 1; $i < $messageLength+1; $i++ ) {
    		//Get ascii value of hex
    		$Ascii[$i] = ceil(hexdec(substr($message, (($i-1)* 2), 2)));  //hexdec
    		//Get ascii value of key at correct position
            $Key[$i] = ord(substr($password, (fmod($i,$passwordLength)+1)-1, 1));
    	    //Subtract Key value
    		$NewAscii[$i] = $Ascii[$i] - $Key[$i];
    		//Working With bytes, Loop To high numbers if below zero
            //if ($NewAscii[$i]<0) {$NewAscii[$i] = $NewAscii[$i] + 255;}
    		//Append To decrypted value
    		$Decrypted .=chr($NewAscii[$i]);
    	}
   	    return $Decrypted;
}
?>