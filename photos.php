<h1>Photos</h1>

<?php if ($_SESSION['permission'] == 'admin') : ?>
<div class="info light tools">
	<p>Que voulez-vous faire ?</p>
	<ul>
		<li><a href="?page=photos&amp;action=gerer">Gérer les photos</a></li>
		<li><a href="?page=photos&amp;action=gerer_gal">Gérer les galeries</a></li>
	</ul>
</div>
<?php endif; ?>

<?php if (isset($_GET['action']) AND $_GET['action'] == "gerer" AND $_SESSION['permission'] == 'admin') : ?>

	<h2><span>Gérer les photos</span></h2>
	
	<div class="info light tools">
		<ul>
			<li><a href="?page=photos&amp;action=gerer&amp;outils=ajout1p">Ajouter une photo</a></li>
			<li><a href="?page=photos&amp;action=gerer&amp;outils=ajoutmasse">Ajouter des photos en masse</a></li>
			<li><a href="?page=photos&amp;action=gerer&amp;outils=masse">Gestion de masse</a></li>
		</ul>
	</div>
	<?php
		// Modification du titre
		if (isset($_POST['submit_titre'])) {
			$titre = htmlspecialchars($_POST['titre']);
			$req = 'UPDATE photos SET titre = "'.$titre.'" WHERE id = '.$_POST['id'];
			if(mysqli_query($link,$req)) {
				echo '<div class="info good">La titre de la photo a bien été modifié : '.$titre.'.</div>';
			} else {
				echo '<div class="info bad">Erreur MySQL : '.mysqli_error($link).'<br />'.$req.'</div>';
			}
		} 
		// Modification de l'ordre
		else if(isset($_POST['submit_ordre_img'])) {
				$req = 'UPDATE photos SET ordre = "'.$_POST['ordre'].'" WHERE id = '.$_POST['id'];
				if(mysqli_query($link,$req)) {
					echo '<div class="info good">L\'ordre de la photo a bien été modifié : '.$_POST['ordre'].'.</div>';
				} else {
					echo '<div class="info bad">Erreur MySQL : '.mysqli_error($link).'<br />'.$req.'</div>';
				}
		}
		// Déplacement de l'image
		else if(isset($_POST['submit_gal_dep'])) {
				$req = 'UPDATE photos SET galerie = "'.$_POST['gal_dep'].'" WHERE id = '.$_POST['id'];
				if(mysqli_query($link,$req)) {
					echo '<div class="info good">La photo a été déplacée.</div>';
				} else {
					echo '<div class="info bad">Erreur MySQL : '.mysqli_error($link).'<br />'.$req.'</div>';
				}
		}
	
		if(isset($_GET['outils']))  $outils = $_GET['outils'];
		else $outils = "";
		
		switch ($outils) {
			
			case "ajout1p": 
				if(isset($_POST['submit'])) {
					$nom = ajout_photo($_FILES['image']);
					if (is_array($nom)) echo '<div class="info bad">'.$nom['erreur'].'</div>';
					else {
						$titre = htmlspecialchars($_POST['titre']);
						$req = 'INSERT INTO photos VALUES ("", "'.$titre.'", "'.$nom.'", "'.$_POST['galerie'].'", "")';
						if(mysqli_query($link,$req)) { echo '<div class="info good">La photo a bien été ajoutée.</div>'; }
						else echo '<div class="info bad">'.mysqli_error($link).'</div>';
						
					}
				}
				?>
				<div class="form_modif">
					<h3>Ajouter une photo</h3>
					<form method="post" action="" enctype="multipart/form-data">
							<p class="cent"><label for="titre">Titre</label><input type="text" name="titre" id="titre" value="" /></p>
							<p class="cent"><label for="galerie">Galerie</label>
								<select name="galerie" id="galerie">
									<?php 
										$req = 'SELECT id, libelle, libelle_en FROM galerie ORDER BY ordre ASC';
										$res = mysqli_query($link,$req) OR die(mysqli_error($link));
										while ($gal = mysqli_fetch_array($res)) {
											echo '<option value="'.$gal['id'].'">'.$gal['libelle'].'</option>';
										}
									?>
								</select>
							</p>
							<p><label for="image">Photo</label><input type="file" name="image" id="image" /></p>
							<p><input type="submit" name="submit" value="Ajouter" /></p>
					</form>
				</div>
			<?php break;
			
			case "ajoutmasse": 
				if(isset($_POST['submit_zip'])) {
					
					// Dézipper l'archive
					echo unzip_file($_FILES['zip'], 'photos/temp/', $_POST['galerie']);
				}
			
			?>
				<div class="form_modif">
					<h3>Ajouter des photos</h3>
					<form method="post" action="" enctype="multipart/form-data">
						<p>Regroupez vos photos dans une archive ZIP et téléchargez le fichier ZIP ci-dessous :</p>
						<p><label for="zip">Fichier ZIP</label><input type="file" name="zip" id="zip" /></p>
						<p class="cent"><label for="galerie">Galerie</label>
							<select name="galerie" id="galerie">
								<?php 
									$req = 'SELECT id, libelle, libelle_en FROM galerie ORDER BY ordre ASC';
									$res = mysqli_query($link,$req) OR die(mysqli_error($link));
									while ($gal = mysqli_fetch_array($res)) {
										echo '<option value="'.$gal['id'].'">'.$gal['libelle'].'</option>';
									}
								?>
							</select>
						</p>
						<p><input type="submit" name="submit_zip" value="Ajouter" /></p>
					</form>
				</div>
				
			<?php
			break;
			
			case "masse":
				include "admin/a_inc_toutesphotos.php";
			
			break;
			
			case "val_suppr": 
				echo suppr_photo($_GET['id'],$_GET['nom']);
			break;
			
			case "suppr": ?>
				<h3>Supprimer la photo</h3>
				
				<div class="info">
					<p>Voulez-vous vraiment supprimer cette photo ?</p>
					<p><a href="?page=photos&amp;action=gerer&amp;outils=val_suppr&amp;id=<?php echo $_GET['id']; ?>&amp;nom=<?php echo $_GET['nom']; ?>"><img src="images/accept.png" alt="" /> Oui</a> - <a href="?page=photos&action=gerer"><img src="images/cancel.png" alt="" /> Non</a></p>
					<?php echo affich_photo($_GET['id']); ?>
				</div>
			<?php
			break;
			
			default:
				include "admin/a_inc_toutesphotos.php";
			break;
			
		}
	?>

<?php elseif (isset($_GET['action']) AND $_GET['action'] == "gerer_gal" AND $_SESSION['permission'] == 'admin') : ?>
	
	<?php

				?>
				<h2><span>Gérer les galeries</span></h2>
				<div class="form_modif">
					<h3>Ajouter une galerie</h3>
					<form method="post" action="">
							<p class="cent"><label for="titre">Titre</label><input type="text" name="titre" id="titre" value="" /></p>
							<p class="cent"><label for="titre_en">Titre (EN)</label><input type="text" name="titre_en" id="titre_en" value="" /></p>
							<p><input type="submit" name="submit_gal" value="Ajouter" /></p>
					</form>
				</div>
				
				<?php 
					
				
					if (isset($_GET['outils']) AND $_GET['outils'] == 'suppr') {
						echo supprimer('galerie',$_GET['id']);
					}
					
					if(isset($_POST['submit_gal'])) {
						$titre = htmlspecialchars($_POST['titre']);
						$titre_en = htmlspecialchars($_POST['titre_en']);
						$req = 'INSERT INTO galerie VALUES ("", "'.$titre.'", "'.$titre_en.'", 0)';
						if(mysqli_query($link,$req)) { echo '<div class="info good">La galerie « '.$titre.' » a bien été ajoutée.</div>'; }
						else echo '<div class="info bad">'.mysqli_error($link).'</div>';
					}
					else if(isset($_POST['submit_titre_gal'])) {
							$titre = htmlspecialchars($_POST['titre']);
							$titre_en = htmlspecialchars($_POST['titre_en']);
							$req = 'UPDATE galerie SET libelle = "'.$titre.'", libelle_en = "'.$titre_en.'" WHERE id = '.$_POST['id'];
							if(mysqli_query($link,$req)) {
								echo '<div class="info good">La titre de la galerie a bien été modifié : '.$titre.'.</div>';
							} else {
								echo '<div class="info bad">Erreur MySQL : '.mysqli_error($link).'<br />'.$req.'</div>';
							}
					}
					else if(isset($_POST['submit_ordre_gal'])) {
							$req = 'UPDATE galerie SET ordre = "'.$_POST['ordre'].'" WHERE id = '.$_POST['id'];
							if(mysqli_query($link,$req)) {
								echo '<div class="info good">L\'ordre de la galerie a bien été modifié : '.$_POST['ordre'].'.</div>';
							} else {
								echo '<div class="info bad">Erreur MySQL : '.mysqli_error($link).'<br />'.$req.'</div>';
							}
					}
				
					$req = 'SELECT id, libelle, libelle_en, ordre FROM galerie ORDER BY ordre ASC';
					$res = mysqli_query($link,$req) OR die(mysqli_error($link));
					
					$nb = mysqli_num_rows($res);
					if ($nb == 0) { echo '<p>Aucune galerie.</p>'; }
					else {
					?>
						<table class="liste_photos">
							<thead>
								<tr>
									<th>Liste des galeries</th>
									<th>Ordre</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
					<?php
							while ($g = mysqli_fetch_array($res)) :
							
							$rq = 'SELECT COUNT(id) AS nb FROM photos WHERE galerie = '.$g['id'];
							$rs = mysqli_query($link,$rq) OR die(mysqli_error($link));
							$nb_ph = mysqli_fetch_array($rs);
					?>
							<tr>
								<td class="titre galerie">
									<form method="post" action="">
										<p><input type="text" class="fr" name="titre" value="<?php echo $g['libelle']; ?>" /></p>
										<p><input type="text" class="en" name="titre_en" value="<?php echo $g['libelle_en']; ?>" /></p>
										<p>Contient : <?php echo $nb_ph['nb']; ?> photos</p>
										<p><input type="submit" name="submit_titre_gal" value="Modifier le titre" /></p>
										<p><input type="hidden" name="id" value="<?php echo $g['id']; ?>" /></p>
									</form>
								</td>
								<td class="ordre">
									<div class="wrap_ordre">
										<form method="post" action="">
											<p><input type="text" name="ordre" id="ordre<?php echo $g['id']; ?>" value="<?php echo $g['ordre']; ?>" /></p>
											<p><input type="submit" name="submit_ordre_gal" value="Modifier l'ordre" /></p>
											<p><input type="hidden" name="id" value="<?php echo $g['id']; ?>" /></p>
										</form>
										<div class="plusmoins">
											<a href="#" class="plus" id="plus<?php echo $g['id']; ?>"><img src="images/plus.png" alt="+" /></a><br />
											<a href="#" class="moins" id="moins<?php echo $g['id']; ?>"><img src="images/moins.png" alt="-" /></a>
										</div>
									</div>
								</td>
								<td class="outils">
									<a href="?page=photos&amp;action=gerer_gal&amp;outils=suppr&amp;id=<?php echo $g['id']; ?>" title="Supprimer la galerie"><img src="images/recycle_bin2.png" alt="Supprimer" /></a>
								</td>
							</tr>					
					<?php 	
							endwhile;
					?>
							</tbody>
						</table>
					<?php
					}
				?>

<?php else : 
	$req = 'SELECT id, libelle, libelle_en FROM galerie ORDER BY ordre ASC';
	$res = mysqli_query($link,$req) OR die(mysqli_error($link));
	
	$nb = mysqli_num_rows($res);
	if ($nb == 0) { echo '<p>Aucune galerie.</p>'; }
	else {
		
		while ($g = mysqli_fetch_array($res)) :
		
		echo '<div class="galerie">';
		echo '<h2><span>'.$g['libelle'].'</span></h2>';
	
		$req2 = 'SELECT id, titre, nom, ordre FROM photos WHERE galerie = '.$g['id'].' ORDER BY galerie ASC, ordre ASC';
		$res2 = mysqli_query($link,$req2) OR die(mysqli_error($link));
		
		$nb2 = mysqli_num_rows($res2);
		if ($nb2 == 0) { echo '<p>Aucune photo dans cette galerie.</p>'; }
		else {?>
			<ul class="liste_photos">
		<?php
				while ($p = mysqli_fetch_array($res2)) :
		?>
				<li><a class="colorbox" title="<?php echo $p['titre']; ?>" href="photos/<?php echo $p['nom']; ?>"><img src="photos/mini/<?php echo $p['nom']; ?>" alt="<?php echo $p['titre']; ?>" /></a></li>
		<?php 	
				endwhile;
		?>
			</ul>	
			<div class="hautpage">
				<a href="#contenu">Haut de page</a>
			</div>


		
		<?php
		}
			echo '</div>';
			endwhile;
	}
 endif; ?>
