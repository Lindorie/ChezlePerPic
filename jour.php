<?php
header('Content-Type: text/html; charset=UTF8'); 
include "inc-connexion.php";

$jour = str_replace("#", "", $_GET['jour']);


$requete = 'SELECT id, etat FROM reservation WHERE "'.$jour.'" BETWEEN date_a AND date_d';
$resultat = mysqli_query($link,$requete) or die (mysqli_error($link));

$nb = mysqli_num_rows($resultat);
		if($nb == 0) { echo 'dispo'; }
		else { 
			$res = mysqli_fetch_array($resultat);
			if ($res['etat'] == 'rose') echo 'rose';
			elseif ($res['etat'] == 'rouge') echo 'rouge';
			elseif ($res['etat'] == 'gris') echo 'gris';
			
		}

mysqli_close($connexion);
?>