<h1>Formules et tarifs</h1>

<?php
		if($_SESSION['permission'] == "admin") { ?>
			<div class="outils admin alignCenter">
				<a class="btn btn-info" href="index.php?page=formules&amp;outils=ajouter"><i class="fa fa-plus"></i> Ajouter un texte</a>
			</div>
<? 		} else echo $_SESSION['permission'];


		$rq = 'SELECT id, titre, texte FROM content WHERE page = "formules" ORDER BY ordre';
		$rs = mysqli_query($link,$rq) OR die('Erreur : '.mysqli_error($link));
		
		while($content = mysqli_fetch_array($rs)) {
			echo '<div class="content">';
			if($content['titre'] != "") { echo "<h2><span>".$content['titre']."</span></h2>"; }
			if($content['texte'] != "") { echo "<p>".htmlspecialchars_decode($content['texte'])."</p>"; }
			
			if($_SESSION['permission'] == "admin") { 
				echo '<div class="outils admin">';
					echo '<div class="btn-group">';
						echo '<a class="btn btn-small" href="index.php?page=formules&amp;outils=modifier&amp;id='.$content['id'].'"><i class="fa fa-edit"></i></a>&nbsp;';
						echo '<a class="btn btn-small"  href="index.php?page=formules&amp;outils=supprimer&amp;id='.$content['id'].'"><i class="fa fa-trash-o"></i></a>';
					echo '</div>';
				echo '</div>';
				
			}
			echo '</div>';
		}
		
	?>