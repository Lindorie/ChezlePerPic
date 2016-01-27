<?php
	if (isset($_POST['submit_deplacer'])) {
		foreach($_POST['action'] as $chkbx){
			$req = 'UPDATE '.$prefix.'photos SET galerie = '.$_POST['deplacer'].' WHERE id = "'.$chkbx.'"';
			mysqli_query($link,$req) OR die(mysqli_error($link));
		}
		echo '<div class="info light good">Les photos ont été déplacées.</div>';
	}
	else if (isset($_POST['submit_ordre'])) {
		$i = 0;
		foreach($_POST['action'] as $chkbx){
			$req = 'UPDATE '.$prefix.'photos SET ordre = '.$_POST['ordre'][$i].' WHERE id = "'.$chkbx.'"';
			mysqli_query($link,$req) OR die(mysqli_error($link).' '.$req.' '.print_r($_POST['ordre']));
			$i++;
		}
		echo '<div class="info light good">Les photos ont été réordonnées.</div>';
	}
	else if (isset($_POST['submit_titre_masse'])) {
		$i = 0;
		foreach($_POST['action'] as $chkbx){
			$titre = htmlspecialchars($_POST['titre'][$i]);
			$req = 'UPDATE '.$prefix.'photos SET titre = "'.$titre.'" WHERE id = "'.$chkbx.'"';
			mysqli_query($link,$req) OR die(mysqli_error($link).' '.$req.' '.print_r($_POST['titre']));
			$i++;
		}
		echo '<div class="info light good">Le titre des photos a été modifié.</div>';
	}
	else if (isset($_POST['submit_supprimer'])) {
		foreach($_POST['action'] as $chkbx){
			$rq = 'SELECT nom FROM '.prefix.'photos WHERE id = '.$chkbx.'';
			$rs = mysqli_query($link,$rq) OR die(mysqli_error($link));
			$n = mysqli_fetch_array($rs);
			$nomphoto = $n['nom'];
			unlink("photos/".$nomphoto);
			unlink("photos/mini/".$nomphoto);
			$req = 'DELETE FROM '.prefix.'photos WHERE id = "'.$chkbx.'"';
			mysqli_query($link,$req) OR die(mysqli_error($link));
		}
		echo '<div class="info light good">Les photos ont été supprimées.</div>';
	}

	$req = 'SELECT id, libelle, libelle_en FROM '.prefix.'galerie ORDER BY ordre ASC';
	$res = mysqli_query($link,$req) OR die(mysqli_error($link));
	
	$nb = mysqli_num_rows($res);
	if ($nb == 0) { echo '<p>Aucune galerie.</p>'; }
	else {
		
		while ($g = mysqli_fetch_array($res)) :
		
		echo '<div class="galerie">';
		echo '<h3><span>'.$g['libelle'].'</span></h3>';
	
		$req2 = 'SELECT id, titre, nom, ordre FROM '.prefix.'photos WHERE galerie = '.$g['id'].' ORDER BY galerie ASC, ordre ASC';
		$res2 = mysqli_query($link,$req2) OR die(mysqli_error($link));
		
		$nb2 = mysqli_num_rows($res2);
		if ($nb2 == 0) { echo '<p>Aucune photo dans cette galerie.</p>'; }
		else {
			if($outils == 'masse') {
		?>
		<form method="post" action="" id="gal<?php echo $g['id']; ?>">
			<table class="liste_photos">
		<thead>
			<tr>
				<th>Image</th>
				<th>Titre</th>
				<th>Ordre</th>
				<th><input type="checkbox" value="all" name="all" id="all<?php echo $g['id']; ?>" /></th>
			</tr>
		</thead>
		<tbody>
<?php
		while ($p = mysqli_fetch_array($res2)) :
?>
		<tr>
			<td class="image">
				<a class="colorbox" title="<?php echo $p['titre']; ?>" href="photos/<?php echo $p['nom']; ?>"><img src="photos/mini/<?php echo $p['nom']; ?>" height="50" alt="<?php echo $p['titre']; ?>" /></a>
			</td>
			<td class="titre">
				<p><input type="text" name="titre[]" value="<?php echo $p['titre']; ?>" /></p>
			</td>
			<td class="ordre">
				<div class="wrap_ordre">
						<p><input type="text" name="ordre[]" id="ordre<?php echo $p['id']; ?>" value="<?php echo $p['ordre']; ?>" /></p>
					<div class="plusmoins">
						<a href="#" class="plus" id="plus<?php echo $p['id']; ?>"><img src="images/plus.png" alt="+" /></a><br />
						<a href="#" class="moins" id="moins<?php echo $p['id']; ?>"><img src="images/moins.png" alt="-" /></a>
					</div>
				</div>
			</td>
			<td class="outils">
				<input type="checkbox" name="action[]" id="action<?php echo $p['id']; ?>" value="<?php echo $p['id']; ?>" />
			</td>
		</tr>					
<?php 	
		endwhile;
?>
		</tbody>
	</table>
	<p>Pour la sélection : 
		<input type="submit" value="Supprimer" name="submit_supprimer" />&nbsp;
		<input type="submit" value="Modifier le titre" name="submit_titre_masse" />&nbsp;
		<input type="submit" value="Modifier l'ordre" name="submit_ordre" />&nbsp;
		<input type="submit" value="Déplacer vers :" name="submit_deplacer" />&nbsp;
		<select name="deplacer" id="deplacer<?php echo $gal['id'];?>">
			<?php 
				$req_dep = 'SELECT id, libelle, libelle_en FROM '.prefix.'galerie WHERE id != '.$g['id'].' ORDER BY ordre ASC';
				$res_dep = mysqli_query($link,$req_dep) OR die(mysqli_error($link));
				while ($gal = mysqli_fetch_array($res_dep)) {
					echo '<option value="'.$gal['id'].'">'.$gal['libelle'].'</option>';
				}
			?>
		</select>
	</p>
	
	</form>
	<div class="hautpage">
		<a href="#contenu">Haut de page</a>
	</div>
	<?php
			
			} else {
?>
	<table class="liste_photos">
		<thead>
			<tr>
				<th>Image</th>
				<th>Titre</th>
				<th>Ordre</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
<?php
		while ($p = mysqli_fetch_array($res2)) :
?>
		<tr>
			<td class="image">
				<a class="colorbox" title="<?php echo $p['titre']; ?>" href="photos/<?php echo $p['nom']; ?>"><img src="photos/mini/<?php echo $p['nom']; ?>" alt="<?php echo $p['titre']; ?>" /></a>
			</td>
			<td class="titre">
				<form method="post" action="">
					<p><input type="text" name="titre" value="<?php echo $p['titre']; ?>" /></p>
					<p><input type="submit" name="submit_titre" value="Modifier le titre" /></p>
					<p><input type="hidden" name="id" value="<?php echo $p['id']; ?>" /></p>
				</form>
			</td>
			<td class="ordre">
				<div class="wrap_ordre">
					<form method="post" action="">
						<p><input type="text" name="ordre" id="ordre<?php echo $p['id']; ?>" value="<?php echo $p['ordre']; ?>" /></p>
						<p><input type="submit" name="submit_ordre_img" value="Modifier l'ordre" /></p>
						<p><input type="hidden" name="id" value="<?php echo $p['id']; ?>" /></p>
					</form>
					<div class="plusmoins">
						<a href="#" class="plus" id="plus<?php echo $p['id']; ?>"><img src="images/plus.png" alt="+" /></a><br />
						<a href="#" class="moins" id="moins<?php echo $p['id']; ?>"><img src="images/moins.png" alt="-" /></a>
					</div>
				</div>
			</td>
			<td class="outils">
				<div class="wrap_outils">
					<a href="?page=photos&amp;action=gerer&amp;outils=suppr&amp;id=<?php echo $p['id']; ?>&amp;nom=<?php echo $p['nom']; ?>" title="Supprimer l'image"><img src="images/recycle_bin2.png" alt="Supprimer" /></a>
					<a class="deplacer" href="#" title="Déplacer l'image"><img src="images/exchange2.png" alt="Déplacer" /></a>
					<div class="deplacer">
						<span class="cancel"><a href="#" title="fermer" class="fermer">X</a></span>
						<form method="post" action="">
							<p><label for="gal_dep<?php echo $p['id']; ?>">Déplacer vers :</label></p>
							<p>
								<select name="gal_dep" id="gal_dep<?php echo $p['id']; ?>">
								<?php 
									$req_dep = 'SELECT id, libelle, libelle_en FROM '.prefix.'galerie WHERE id != '.$g['id'].' ORDER BY ordre ASC';
									$res_dep = mysqli_query($link,$req_dep) OR die(mysqli_error($link));
									while ($gal = mysqli_fetch_array($res_dep)) {
										echo '<option value="'.$gal['id'].'">'.$gal['libelle'].'</option>';
									}
								?>
								</select>
							</p>
							<p><input type="hidden" value="<?php echo $p['id']; ?>" name="id" /></p>
							<p><input type="submit" value="Déplacer" name="submit_gal_dep" /></p>
						</form>
					</div>
				</div>
			</td>
		</tr>					
<?php 	
		endwhile;
?>
		</tbody>
	</table>
	<div class="hautpage">
		<a href="#contenu">Haut de page</a>
	</div>
<?php 	
	}
	}
	echo '</div>';
	endwhile;
	}
?>