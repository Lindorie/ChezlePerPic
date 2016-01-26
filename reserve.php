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