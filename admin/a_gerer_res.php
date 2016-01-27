<?php 
	
	if (isset($_GET['voir'])) {
		
		if (isset($_POST['submit_maj_etat'])) {
			$rq = 'UPDATE '.$prefix.'reservation SET etat = "'.$_POST['maj_etat'].'" WHERE id = '.$_POST['id_reserv'].';';
			if(!mysqli_query($link,$rq)) { echo '<div class="info light bad">'.mysqli_error($link).'<br />'.$rq.'</div>'; }
		}
		
		if (isset($_POST['submit_maj_client'])) {
			$rq = 'UPDATE '.$prefix.'reservation SET id_client = "'.$_POST['maj_client'].'" WHERE id = '.$_POST['id_reserv'].';';
			if(!mysqli_query($link,$rq)) { echo '<div class="info light bad">'.mysqli_error($link).'<br />'.$rq.'</div>'; }
		}

		echo '<h2><span>Détail de la réservation</span></h2>';
		$req = 'SELECT reservation.id as id_res, id_client, nom, prenom, email, tel, pref_mail, pref_tel, formule, type_paiement, prix, date, DATE_FORMAT(date_a, "%a %d %M %Y") as date_a, DATE_FORMAT(date_d, "%a %d %M %Y") as date_d, nb_total, enfants, bebes, message, etat FROM '.prefix.'reservation, '.prefix.'client WHERE id_client = client.id AND reservation.id = '.$_GET['voir'].'';
		$res = mysqli_query($link,$req) OR die(mysqli_error($link));
		
		echo '<div id="detail_reservation">';
		$nb = mysqli_num_rows($res);
		if ($nb == 0) {
			echo '<p>Erreur : aucune réservation n\'a été trouvée.</p>';
		} else {
			while ($r = mysqli_fetch_array($res)) { ?>
				<p class="num_reserv"><i class="fa fa-book"></i> Réservation n°<?php echo $r['id_res']; ?></p>
				<div class="client">
					<p class="alignRight" style="float: right">
						<a href="?page=prive&amp;show=gerer_cli&amp;email=<?php echo $r['id_client']; ?>#formulaire_contact" class="btn btn-small" title="Contacter le client à propos de cette réservation"><i class="fa fa-envelope"></i> Contacter le client</a>
						<?php if ($_SESSION['permission'] == 'admin') { ?> 
							<a href="#" id="maj_client" title="Modifier le client" class="btn btn-small"><i class="fa fa-refresh"></i> Modifier le client</a>
						<?php } ?>
					</p>
					<p style="line-height: 26px"><i class="fa fa-user"></i> <strong>Client</strong> : <?php echo $r['prenom']; ?> <?php echo $r['nom']; ?> </p>
				</div>
					<div id="form_maj_client" style="display: none">
						<a href="#" class="cancel">X</a>
						<form method="post" action="" class="form_maj_client">
							<fieldset>
								<legend>Modifier</legend>
								<div class="input-prepend">
									<span class="add-on"><i class="fa fa-user"></i></span>
									<select name="maj_client" class="span2">
									<?php $liste =  liste_clients(); 
											foreach($liste as $k => $c) { ?>
												<option value="<?php echo $k; ?>"><?php echo $c['prenom'].' '.$c['nom']; ?></option>
									<?		}
									?>
									</select>
								</div>
								<p><input type="hidden" value="<?php echo $r['id_res']; ?>" name="id_reserv" /></p>
								<p><input type="submit" class="btn btn-success" value="Valider" name="submit_maj_client" /></p>
							</fieldset>
						</form>
					</div>
				<hr />
				<p><strong>Formule</strong> : <?php echo $r['formule']; ?></p>
				<p><strong>Date d'arrivée</strong> : <?php echo $r['date_a']; ?></p>
				<p><strong>Date de départ</strong> : <?php echo $r['date_d']; ?></p>
				<p><strong>Nombre de personnes</strong> : <?php echo $r['nb_total']; ?></p>
				<p><strong>dont enfants</strong> : <?php echo $r['enfants']; ?></p>
				<p><strong>dont bébés</strong> : <?php echo $r['bebes']; ?></p>
				<?php if ($r['message'] != "") :?>
					<p><strong>Message complémentaire</strong> : <?php echo $r['message']; ?></p>
				<?php endif; ?>
				<p><strong>Tarif</strong> : <?php echo $r['prix']; ?>&euro;</p>
				<p><strong>Type de paiement</strong> : <?php echo $r['type_paiement']; ?></p>
				<p><strong>Date d'enregistrement de la réservation</strong> : <?php echo $r['date']; ?></p>
				<?php if ($r['etat'] == "rose") { $etat = "non confirmé"; }
				elseif ($r['etat'] == "rouge") { $etat = "confirmé"; }
				elseif ($r['etat'] == "gris") { $etat = "indisponible"; } ?>
				<p class="etat <?php echo $r['etat']; ?>"><strong>Etat</strong> : <span><?php echo $etat; ?></span>
				<?php if ($_SESSION['permission'] == 'admin') { ?> | 
				<a href="#" class="btn btn-small" id="maj_etat" title="Mettre à jour l'état"><i class="fa fa-refresh"></i> Mettre à jour</a>
				<?php } ?>
				</p>
				<?php if ($_SESSION['permission'] == 'admin') { ?>
				<div id="form_maj_etat" style="display: none">
					<a href="#" class="cancel">X</a>
					<form method="post" action="" class="form_maj_etat">
						<fieldset>
							<legend>Mettre à jour</legend>
								<div class="input-prepend">
									<span class="add-on"><i class="fa fa-book"></i></span>
									<select name="maj_etat">
										<?php if ($r['etat'] == "rose") { ?>
											<option value="rose" class="rose" selected="selected">non confirmé</option>
										<?php } else {  ?><option value="rose" class="rose">non confirmé</option><?php } ?>
										<?php if ($r['etat'] == "rouge") { ?>
											<option value="rouge" class="rouge" selected="selected">confirmé</option>
										<?php } else {  ?> <option value="rouge" class="rouge">confirmé</option><?php } ?>
										<?php if ($r['etat'] == "rouge") { ?>
											<option value="gris" class="gris" selected="selected">indisponible</option>
										<?php } else {  ?> <option value="gris" class="gris">indisponible</option><?php } ?>
									</select>
								</div>
							<p><input type="hidden" value="<?php echo $r['id_res']; ?>" name="id_reserv" /></p>
							<p><input type="submit" class="btn btn-success" value="Valider" name="submit_maj_etat" /></p>
						</fieldset>
					</form>
				</div><?php } ?>
				<hr />
				<div class="outils admin">
					<i class="fa fa-wrench icon-2x pull-left"></i>
					<a class="btn btn-info" href="?page=prive&amp;show=gerer_res&amp;commenter=<?php echo $r['id_res']; ?>#comment"><i class="fa fa-comment"></i> Ajouter un commentaire</a> 
					<?php if ($_SESSION['permission'] == 'admin') { ?>
					<a class="btn" href="?page=prive&amp;show=gerer_res&amp;modifier=<?php echo $r['id_res']; ?>#detail_reservation"><i class="fa fa-edit"></i> Modifier</a> 
					<a class="btn btn-danger" href="?page=prive&amp;show=gerer_res&amp;supprimer=<?php echo $r['id_res']; ?>"><i class="fa fa-trash"></i> Supprimer</a>
					<?php } ?>
				</div>
				
			<?
			}
		}
		echo '</div>';
			
	} elseif (isset($_GET['modifier'])) {

		
		if (isset($_POST['submit_modif_reserv'])) {
			// Traitement des champs texte
			$nb = htmlspecialchars($_POST['nb']);
			$nb_enf = htmlspecialchars($_POST['nb_enf']);
			$nb_bb = htmlspecialchars($_POST['nb_bb']);
			$tarif = htmlspecialchars($_POST['tarif']);
			
			$da = explode('/', $_POST['date_a']);
			$tp_da = mktime(0,0,0,$da[1],$da[0],$da[2]);
			$date_a = date("Y-m-d",$tp_da);
			
			$dd = explode('/', $_POST['date_d']);
			$tp_dd = mktime(0,0,0,$dd[1],$dd[0],$dd[2]);
			$date_d = date("Y-m-d",$tp_dd);
			
			$rq = 'UPDATE '.$prefix.'reservation SET formule = "'.$_POST['formule'].'", type_paiement = "'.$_POST['type_paie'].'", date_a = "'.$date_a.'", date_d = "'.$date_d.'", nb_total = "'.$nb.'", enfants = "'.$nb_enf.'", bebes = "'.$nb_bb.'", prix = "'.$tarif.'"  WHERE id = '.$_GET['modifier'].';';
			if(!mysqli_query($link,$rq)) { echo '<div class="info light bad">'.mysqli_error($link).'<br />'.$rq.'</div>'; }
		}

		echo '<h2><span>Modifier la réservation</span></h2>';
		$req = 'SELECT reservation.id as id_res, id_client, nom, prenom, email, tel, pref_mail, pref_tel, formule, type_paiement, prix, date, DATE_FORMAT(date_a, "%d/%m/%Y") as date_a, DATE_FORMAT(date_d, "%d/%m/%Y") as date_d, nb_total, enfants, bebes, message, etat FROM '.prefix.'reservation, '.prefix.'client WHERE id_client = client.id AND reservation.id = '.$_GET['modifier'].'';
		$res = mysqli_query($link,$req) OR die(mysqli_error($link));
		
		echo '<div id="detail_reservation">';
		$nb = mysqli_num_rows($res);
		if ($nb == 0) {
			echo '<p>Erreur : aucune réservation n\'a été trouvée.</p>';
		} else {
			while ($r = mysqli_fetch_array($res)) { ?>
				<p class="num_reserv"><i class="fa fa-book"></i> Réservation n°<?php echo $r['id_res']; ?></p>
				<form method="post" action="" id="form_modif_reserv">
					<div class="client">
						<p class="alignRight" style="float: right">
							<a href="?page=prive&amp;show=gerer_cli&amp;email=<?php echo $r['id_client']; ?>#formulaire_contact" class="btn btn-small" title="Contacter le client à propos de cette réservation"><i class="fa fa-envelope"></i> Contacter le client</a>
						</p>
						<p style="line-height: 26px"><i class="fa fa-user"></i> <strong>Client</strong> : <?php echo $r['prenom']; ?> <?php echo $r['nom']; ?> </p>
					</div>
					<hr />
					<p><label for="formule">Formule</label> 
						<?php $checked = 'selected="selected"'; ?>
						<select name="formule" id="formule">
							<optgroup label="-- Semaine">
								<option value="semaine" <? if($r['formule'] == "semaine") echo $checked; ?>>Semaine</option>
							</optgroup>
							<optgroup label="-- Week-end">
								<option value="we_prolonge" <? if($r['formule'] == "we_prolonge") echo $checked; ?>>prolongé</option>
								<option value="we_long" <? if($r['formule'] == "we_long") echo $checked; ?>>long</option>
								<option value="we_moyen" <? if($r['formule'] == "we_moyen") echo $checked; ?>>moyen</option>
								<option value="we_court" <? if($r['formule'] == "we_court") echo $checked; ?>>court</option>
							</optgroup>
							<option value="alacarte" <? if($r['formule'] == "alacarte") echo $checked; ?>>A la carte</option>
						</select>
					</p>
					<p><label for="date_a">Date d'arrivée</label> <input type="text" name="date_a" id="date_a" value="<?php echo $r['date_a']; ?>" /></p>
					<p><label for="date_d">Date de départ</label> <input type="text" name="date_d" id="date_d" value="<?php echo $r['date_d']; ?>" /></p>
					<p><label for="nb">Nombre de personnes</label> <input type="text" name="nb" id="nb" value="<?php echo $r['nb_total']; ?>" /></p>
					<p><label for="nb_enf">dont enfants</label> <input type="text" name="nb_enf" id="nb_enf" value="<?php echo $r['enfants']; ?>" /></p>
					<p><label for="nb_bb">dont bébés</label> <input type="text" name="nb_bb" id="nb_bb" value="<?php echo $r['bebes']; ?>" /></p>
					<p><label for="tarif">Tarif</label> <input type="text" name="tarif" id="tarif" value="<?php echo $r['prix']; ?>" />&nbsp;&euro;</p>
					<p><label for="type_paie">Type de paiement</label> 
						<select name="type_paie" id="type_paie">
								<option value="non payé" <? if($r['type_paiement'] == "") echo $checked; ?>>Non payé</option>
								<option value="chèque" <? if($r['type_paiement'] == "chèque") echo $checked; ?>>Chèque</option>
								<option value="paypal" <? if($r['type_paiement'] == "paypal") echo $checked; ?>>Paypal</option>
								<option value="espèces" <? if($r['type_paiement'] == "espèces") echo $checked; ?>>Espèces</option>
								<option value="virement" <? if($r['type_paiement'] == "virement") echo $checked; ?>>Virement</option>
						</select>
					</p>
					<p><?php if ($r['message'] != "") echo $r['message']; ?></p>
					<p><input type="submit" value="Modifier" class="btn btn-success" name="submit_modif_reserv" /></p>
				</form>
			<?php
				echo '<hr />';
				echo '<div class="outils admin"><a class="btn btn-small" href="?page=prive&amp;show=gerer_res&amp;voir='.$r['id_res'].'#detail_reservation"><i class="fa fa-arrow-left"></i> Retour</a> | <a class="btn btn-danger btn-small" href="?page=prive&amp;show=gerer_res&amp;supprimer='.$r['id_res'].'"><i class="fa fa-trash"></i> Supprimer</a></div>
					</div>';
			}
		}
	
	} elseif (isset($_GET['supprimer'])) {
	
		if (isset($_POST['submit_supprimer'])) {
			$rq = 'DELETE FROM '.prefix.'reservation WHERE id = '.$_GET['supprimer'].';';
			if(!mysqli_query($link,$rq)) { echo '<div class="info light bad">'.mysqli_error($link).'<br />'.$rq.'</div>'; }
			else echo '<div class="info good">La réservation a été supprimée.</div>';
		} else {
			
			echo '<h2><span>Supprimer la réservation</span></h2>';
			echo '<div class="detail_reservation">';
			echo '<p>Voulez-vous vraiment supprimer la réservation n°'.$_GET['supprimer'].' ?</p>';
			?>
			<form method="post" action="">
				<p><input type="submit" name="submit_supprimer" value="Oui, supprimer" /></p>
			</form>
			<?php
			echo '<hr />';
			echo '<div class="outils admin"><a href="?page=prive&amp;show=gerer_res&amp;voir='.$_GET['supprimer'].'">Retour à la réservation</a></div>';
			echo '</div>';
		}
	
	} else {
		
		echo '<h2><span>Liste des réservations futures</span></h2>';

		$req = 'SELECT reservation.id as id_res, id_client, nom, prenom, formule, DATE_FORMAT(date_a, "%d %M %Y") as date_arrivee, DATE_FORMAT(date_d, "%d %M %Y") as date_d, nb_total, enfants, bebes, etat FROM '.prefix.'reservation, '.prefix.'client WHERE id_client = client.id AND date_d >= CURDATE() ORDER BY date_a ASC;';
		$res = mysqli_query($link,$req) OR die(mysqli_error($link));
		
		$nb = mysqli_num_rows($res);
		if ($nb == 0) {
			echo '<p>Aucune réservation future.</p>';
		} else { ?>
			<table class="liste_photos" id="liste_reservations">
				<thead>
					<tr>
						<th>ID</th>
						<th>Client</th>
						<th>Formule</th>
						<th>Date d'arrivée</th>
						<th>Date de départ</th>
						<th>Nombre</th>
						<th>enfants</th>
						<th>bébés</th>
						<th>Etat</th>
					</tr>
				</thead>
				<tbody>
		<?php
			while ($r = mysqli_fetch_array($res)) { ?>
				<tr>
					<td><a href="?page=prive&amp;show=gerer_res&amp;voir=<?php echo $r['id_res']; ?>#detail_reservation" title="Voir le détail de cette réservation"><i class="fa fa-eye icon-large"></i></a> <a href="?page=prive&amp;show=gerer_res&amp;modifier=<?php echo $r['id_res']; ?>#detail_reservation" title="Modifier cette réservation"><i class="fa fa-edit icon-large"></i></a></td>
					<td><?php echo $r['prenom']; ?> <?php echo $r['nom']; ?></td>
					<td><?php echo $r['formule']; ?></td>
					<td><?php echo $r['date_arrivee']; ?></td>
					<td><?php echo $r['date_d']; ?></td>
					<td><?php echo $r['nb_total']; ?></td>
					<td><?php echo $r['enfants']; ?></td>
					<td><?php echo $r['bebes']; ?></td>
					<td class="<?php echo $r['etat']; ?>">&nbsp;</td>
				</tr>
			
		<?php } ?>
				</tbody>
			</table>
		<?php }  ?>

	<h2><span>Liste des réservations antérieures</span></h2>
	
	<?php 
	$req = 'SELECT reservation.id as id_res, id_client, nom, prenom, formule, DATE_FORMAT(date_a, "%d %M %Y") as date_a, DATE_FORMAT(date_d, "%d %M %Y") as date_d, nb_total, enfants, bebes, etat FROM reservation, client WHERE id_client = client.id AND date_d < CURDATE() ORDER BY date_a DESC;';
	$res = mysqli_query($link,$req) OR die(mysqli_error($link));
	
	$nb = mysqli_num_rows($res);
	if ($nb == 0) {
		echo '<p>Aucune réservation antérieure.</p>';
	} else { ?>
		<table class="liste_photos" id="liste_reservations_a">
				<thead>
					<tr>
						<th>ID</th>
						<th>Client</th>
						<th>Formule</th>
						<th>Date d'arrivée</th>
						<th>Date de départ</th>
						<th>Nombre</th>
						<th>enfants</th>
						<th>bébés</th>
						<th>Etat</th>
					</tr>
				</thead>
				<tbody>
		<?php
			while ($r = mysqli_fetch_array($res)) { ?>
				<tr>
					<td><a href="?page=prive&amp;show=gerer_res&amp;voir=<?php echo $r['id_res']; ?>#detail_reservation" title="Voir le détail de cette réservation"><i class="fa fa-eye icon-large"></i></a> <a href="?page=prive&amp;show=gerer_res&amp;modifier=<?php echo $r['id_res']; ?>#detail_reservation" title="Modifier cette réservation"><i class="fa fa-edit icon-large"></i></a></td>
					<td><?php echo $r['prenom']; ?> <?php echo $r['nom']; ?></td>
					<td><?php echo $r['formule']; ?></td>
					<td><?php echo $r['date_a']; ?></td>
					<td><?php echo $r['date_d']; ?></td>
					<td><?php echo $r['nb_total']; ?></td>
					<td><?php echo $r['enfants']; ?></td>
					<td><?php echo $r['bebes']; ?></td>
					<td class="<?php echo $r['etat']; ?>">&nbsp;</td>
				</tr>
			
		<?php } ?>
				</tbody>
			</table>
	<?php 
		}
	 }

?>