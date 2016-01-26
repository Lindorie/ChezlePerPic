<?php
session_start();
session_destroy();
header("location: index.php");

/* $url = str_replace ('&amp;', '&', getenv('QUERY_STRING'));

	$a = explode("&", $url);
	if($a[0] == 'page=erreur' OR $a[0] == 'page=admin') {
		header("location: index.php");
	} else { 
		header("location: index.php?".$url."");
	}
*/
?>