
<?php

	$rq = 'SELECT id, titre, texte FROM '.$prefix.'content WHERE page = "reservation" ORDER BY ordre';
	$rs = mysqli_query($link,$rq) OR die('Erreur : '.mysqli_error($link));
	
	while($content = mysqli_fetch_array($rs)) {
		if($content['titre'] != "") { echo "<h1><span>".$content['titre']."</span></h1>"; }
		if($content['texte'] != "") { echo "<p>".htmlspecialchars_decode($content['texte'])."</p>"; }
		
		if($_SESSION['permission'] == "admin") { 
			echo '<div class="outils admin">';
				echo '<a href="index.php?page=reservation&amp;outils=modifier&amp;id='.$content['id'].'">Modifier</a>';
			echo '</div>';
			
		}
	}
	
?>