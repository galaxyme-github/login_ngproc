<?php
$url = 'http://login.ngproc.com.br/send-grids';

$opts = array('http' =>
 	array(
	    'method'  => 'GET'
	)
);
$context  = stream_context_create($opts);

file_get_contents($url, false, $context);
?>
