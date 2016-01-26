<?php
header('Content-Type: text/html; charset=UTF8'); 
include "inc-connexion.php";

$jour = str_replace("#", "", $_GET['jour']);


$requete = 'SELECT id, etat FROM reservation WHERE "'.$jour.'" BETWEEN date_a AND date_d';
$resultat = mysql_query($requete) or die (mysql_error());

$nb = mysql_num_rows($resultat);        
		if($nb == 0) { echo 'dispo'; }
		else { 
			$res = mysql_fetch_array($resultat);
			if ($res['etat'] == 'rose') echo 'rose';
			elseif ($res['etat'] == 'rouge') echo 'rouge';
			elseif ($res['etat'] == 'gris') echo 'gris';
			
		}

mysql_close($connexion);
?>