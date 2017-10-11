<?php
$ch = curl_init();

if( count( $argv ) >= 2 ){
	curl_setopt($ch, CURLOPT_URL, $argv[1] );
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_exec($ch);
	curl_close($ch);
}
?>
