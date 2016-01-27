<?php
session_start();
header('Content-Type: text/html; charset=ISO-8859-15'); 
include "inc-connexion.php";

//if (!isset($_GET['date'])) $_GET['date'] = "2010-10-22";

$requete = 'SELECT id_evenement, titre, type, LEFT(code_postal,2) as code_postal, ville, type FROM '.prefix.'calendrier WHERE date = "'.$_GET['date'].'"';
$resultat = mysqli_query($link,$requete) or die (mysqli_error($link));

$nb = mysqli_num_rows($resultat);
		if($nb == 0) {
			echo 'Aucun �v�nement.'; 
		}
			else {
echo '<ul>';
while ($event=mysqli_fetch_array($resultat)) {
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
	} else { echo 'Connectez-vous pour voir l\'�v�nement club.'; }
	
}
echo '</ul>';
}

mysqli_close($connexion);
?>