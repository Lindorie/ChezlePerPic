<?php
/*
 * MODIFIER UN CLIENT
 *
 */
if (isset($_GET['modifier'])) {

	if (isset($_POST['submit_modif'])) {
		extract($_POST);
		$nom = htmlspecialchars($nom);
		$prenom = htmlspecialchars($prenom);
		$email = htmlspecialchars($email);
		$tel = htmlspecialchars($tel);
		$tel2 = htmlspecialchars($tel2);
		$adresse = htmlspecialchars($adresse);
		$ville = htmlspecialchars($ville);
		$code_postal = htmlspecialchars($code_postal);
		$pays = htmlspecialchars($pays);

		$rq = 'UPDATE '.$prefix.'client SET nom = "'.$nom.'", prenom = "'.$prenom.'", email = "'.$email.'", tel = "'.$tel.'", tel2 = "'.$tel2.'", adresse = "'.$adresse.'", ville = "'.$ville.'", code_postal = "'.$code_postal.'", pays = "'.$pays.'"  WHERE id = '.$id_client.';';

		if (mysqli_query($link,$rq)) {
			echo '<div class="info good">Les informations du client ont été modifiées.</div>';
		} else {
			echo '<div class="info bad">Une erreur s\'est produite : '.mysqli_error($link).'<br />'.$rq.'</div>';
		}
	}
	$clients = liste_clients($_GET['modifier']);
	$modifier = true;

}
/*
 *
 * AJOUTER UN CLIENT
 *
*/
else if (isset($_GET['ajouter'])) {
	if (isset($_POST['submit_ajouter'])) {
		extract($_POST);

		$nom = htmlspecialchars($nom);
		$prenom = htmlspecialchars($prenom);
		$email = htmlspecialchars($email);
		$tel = htmlspecialchars($tel);
		$tel2 = htmlspecialchars($tel2);
		$adresse = htmlspecialchars($adresse);
		$ville = htmlspecialchars($ville);
		$code_postal = htmlspecialchars($code_postal);
		$pays = htmlspecialchars($pays);
		if ($password == $password2) {
			if ($password != "") {
				$password = md5(sha1($password));
			} else {
				$mdp = newChaine();
				$password = md5(sha1($mdp));
				$mdpvide = true;
				$msg = 'Vous venez d\'ajouter sur le site www.chezleperpic.fr un nouveau client : '.$prenom. ' '.$nom.'<br />Le mot de passe qui lui a été attribué est le suivant : '.$mdp;

				envoi_mail("Attribution d'un mot de passe au client $prenom $nom", $msg);

			}
		} else {
			$erreur = "Les mots de passe saisis ne sont pas équivalents.";
			$mdp = newChaine();
			$password = md5(sha1($mdp));
		}

		$rq = 'INSERT INTO '.$prefix.'client (nom, prenom, email, tel, tel2, adresse, ville, code_postal, pays, password) VALUES ("'.$nom.'", "'.$prenom.'", "'.$email.'", "'.$tel.'", "'.$tel2.'", "'.$adresse.'", "'.$ville.'", "'.$code_postal.'", "'.$pays.'", "'.$password.'");';

		if (mysqli_query($link,$rq)) {
			echo '<div class="info good">Le client a bien été ajouté.';
			if ($erreur != "") { echo '<br />'.$erreur.' Mot de passe généré automatiquement : '.$mdp; }
			if ($mdpvide) { echo '<br />Mot de passe généré automatiquement : '.$mdp; }
			echo '</div>';
			$clients = liste_clients();
			$liste = true;
		} else {
			echo '<div class="info bad">Une erreur s\'est produite : '.mysqli_error($link).'<br />'.$rq.'</div>';
			$ajouter = true;
		}
	} else { $ajouter = true; }

}
/*
 *
 * VOIR LES RESERVATIONS D'UN CLIENT
 *
*/
elseif (isset($_GET['reservations'])) {
	$client = liste_clients($_GET['reservations']);
	echo '<h2><span>'.$client[$_GET['reservations']]['prenom'].' '.$client[$_GET['reservations']]['nom'].'</span></h2>';
	$rq = 'SELECT id, formule, DATE_FORMAT(date_a, "%d %M %Y") as date_a, DATE_FORMAT(date_d, "%d %M %Y") as date_d, nb_total, enfants, bebes, message, etat, prix FROM '.$prefix.'reservation WHERE id_client = '.$_GET['reservations'];
	$rs = mysqli_query($link,$rq) OR die(mysqli_error($link));
	$nb = mysqli_num_rows($rs);
	if ($nb == 0) { echo '<div class="info bad">Aucune réservation n\'est associée à ce client.</div>'; $clients = liste_clients(); $reservations = false;}
	else {
		$reservations = true;
	}
}
/*
 *
 * SUPPRIMER UN CLIENT
 *
*/
elseif (isset($_GET['supprimer'])) {
	echo supprimer('client',$_GET['supprimer']);
	$clients = liste_clients();
	$suppr = true;
}
/*
 *
 * ENVOYER UN EMAIL
 *
*/
elseif (isset($_GET['email'])) {
	if (isset($_POST['submit_contact'])) {
		if (envoyer_email($_POST['destinataire'], $_POST['objet'], $_POST['message'])) {
			echo '<div class="info good">Le message a bien été envoyé.</div>';
			$formulaire = false;
		} else {
			echo '<div class="info bad">Erreur lors de l\'envoi du message.</div>';
			$formulaire = false;
		}
	} else {
		$client = liste_clients($_GET['email']);
		$destinataire = $client[$_GET['email']]['email'];
		$nom_client = $client[$_GET['email']]['prenom'].' '.$client[$_GET['email']]['nom'];
		$formulaire = true;
	}
}
/*
 *
 * VOIR LA LISTE DES CLIENTS
 *
*/

else {
	$clients = liste_clients();
	$liste = true;
}

if (!$modifier AND !isset($reservations) AND !$formulaire) {
	echo '<div class="alignRight" style="margin-top: 20px;"><a href="?page=prive&amp;show=gerer_cli&ajouter=true" class="btn btn-info"><i class="fa fa-plus"></i> Ajouter un client</a></div>';
}

?>
<?php
if ($formulaire) :
	?>
	<h3>Contacter le client <?php echo $nom_client; ?></h3>

	<div id="formulaire_contact">
		<form method="post" action="">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
				<input class="span2" type="text" name="objet" placeholder="Objet" />
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-comment"></i></span>
				<textarea class="span6" name="message" placeholder="Message" cols="100" rows="6"></textarea>
			</div>
			<div>
				<input type="hidden" name="destinataire" value="<?php echo $destinataire; ?>" />
				<input class="btn btn-success" type="submit" name="submit_contact" value="Envoyer" />
			</div>
		</form>
	</div>
	<div class="address">
		<i class="fa fa-info icon-4x pull-left icon-muted"></i>
		Pour tout contact avec un client, merci de vous identifier clairement : propriétaire, consultant... avec vos noms et prénoms. Merci.
	</div>
	<br />
	<?php
else :
	?>
	<table class="liste_photos" id="liste_clients">
		<thead>
		<tr>
			<?php
			if ($modifier OR $ajouter) :
				?>
				<th>#</th>
				<th>Informations</th>
				<th>Action</th>
				<?php
			elseif ($liste) :
				?>
				<th>#</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Tel</th>
				<th>Email</th>
				<th>Outils</th>
				<th>Réservations</th>
				<?php
			elseif ($reservations) :
				?>
				<th>#</th>
				<th>Formule</th>
				<th>Date d'arrivée</th>
				<th>Date de départ</th>
				<th>Nombre</th>
				<th>Prix</th>
				<th>Etat</th>
				<th>Actions</th>
				<?php
			endif;
			?>
		</tr>
		</thead>
		<tbody>
		<?php
		if ($ajouter) :
			if (isset($_POST['submit_ajouter'])) {
				$k = $_POST;
			}
			?>
			<tr>
				<form method="post" action="">
					<td class="center">Nouveau client</td>
					<td>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input class="span2 form-control" type="text" placeholder="Nom" name="nom" value="<?php if (isset($k['nom'])) echo $k['nom']; ?>" />
						</div>
						<div class="input-group">
							<input class="span2 form-control" type="text" placeholder="Prénom" name="prenom" value="<?php if (isset($k['prenom'])) echo $k['prenom']; ?>" />
						</div>
						<div class="clear"></div>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
							<input class="span2 form-control" type="text" placeholder="Email" name="email" value="<?php if (isset($k['email'])) echo $k['email']; ?>" />
						</div>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-phone"></i></span>
							<input class="span2 form-control" type="text" placeholder="Téléphone" name="tel" value="<?php if (isset($k['tel'])) echo $k['tel']; ?>" />
						</div>
						<div class="input-group">
							<input class="span2 form-control" type="text" placeholder="Téléphone 2" name="tel2" value="<?php if (isset($k['tel2'])) echo $k['tel2']; ?>" />
						</div>
						<div class="clear"></div>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-home"></i></span>
							<input class="span2 form-control width100" type="text" placeholder="Adresse" name="adresse" value="<?php if (isset($k['adresse'])) echo $k['adresse']; ?>" />
						</div>
						<div class="clear"></div>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-home"></i></span>
							<input class="span2 form-control" type="text" placeholder="Code postal" name="code_postal" value="<?php if (isset($k['code_postal'])) echo $k['code_postal']; ?>" />
						</div>
						<div class="input-group">
							<input class="span2 form-control" type="text" placeholder="Ville" name="ville" value="<?php if (isset($k['ville'])) echo $k['ville']; ?>" />
						</div>
						<div class="input-group">
							<input class="span2 form-control" type="text" placeholder="Pays" name="pays" value="<?php if (isset($k['pays'])) echo $k['pays']; else echo 'France' ?>" />
						</div>
						<div class="clear"></div>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-key"></i></span>
							<input class="span2 form-control" type="text" placeholder="Mot de passe" name="password" value="<?php if (isset($k['password'])) echo $k['password']; ?>" />
						</div>
						<div class="input-group">
							<input class="span2 form-control" type="text" placeholder="Confirmez" name="password2" value="<?php if (isset($k['password2'])) echo $k['password2']; ?>" />
						</div>
						<span>Aléatoire : <?php echo newChaine(); ?></span>
						<p>Si aucun mot de passe n'est saisi, il sera généré automatiquement lors de l'enregistrement.</p>
					</td>
					<td class="center"><p><input type="submit" class="btn btn-success" value="Ajouter" name="submit_ajouter" /></p></td>
				</form>
			</tr>
			<?php
		elseif ($modifier) :
			foreach ($clients as $c => $k) {
				?>
				<tr>
					<form method="post" action="" class="form-horizontal">
						<td class="center"><?php echo $c; ?></td>
						<td>
							<div class="form-group">
								<label class="sr-only" for="nom">Nom</label>
								<div class="input-group">
									<div class="input-group-addon"><i class="fa fa-user"></i></div>
									<input class="span2 form-control" type="text" placeholder="Nom" name="nom" id="nom" value="<?php echo $k['nom']; ?>" />
									<input class="span2 form-control" type="text" placeholder="Prénom" name="prenom" value="<?php echo $k['prenom']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="sr-only" for="email">Email</label>
								<div class="input-group">
									<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
									<input class="span2 width100 form-control" type="text" placeholder="Email" id="email" name="email" value="<?php echo $k['email']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="sr-only" for="tel">Téléphone</label>
								<div class="input-group">
									<div class="input-group-addon"><i class="fa fa-phone"></i></div>
									<input class="span2 form-control" type="text" placeholder="Téléphone" id="tel" name="tel" value="<?php echo $k['tel']; ?>" />
									<input class="span2 form-control" type="text" placeholder="Téléphone 2" name="tel2" value="<?php echo $k['tel2']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="sr-only" for="adresse">Adresse</label>
								<div class="input-group">
									<div class="input-group-addon"><i class="fa fa-home"></i></div>
									<input class="span2 width100 form-control" type="text" placeholder="Adresse" name="adresse" value="<?php echo $k['adresse']; ?>" />
									<input class="span2 form-control" type="text" placeholder="Code postal" name="code_postal" value="<?php echo $k['code_postal']; ?>" />
									<input class="span2 form-control" type="text" placeholder="Ville" name="ville" value="<?php echo $k['ville']; ?>" />
									<input class="span2 form-control" type="text" placeholder="Pays" name="pays" value="<?php echo $k['pays']; ?>" />
								</div>
							</div>
						</td>
						<td class="center"><p><input type="hidden" value="<?php echo $c; ?>" name="id_client" /><input type="submit" class="btn btn-warning" value="Modifier" name="submit_modif" /></p></td>
					</form>

				</tr>
				<?php
			}
		elseif ($reservations) :
			while ($res = mysqli_fetch_assoc($rs)) {
				?>
				<tr>
					<td class="text-center"><?php echo $res['id']; ?></td>
					<td class="text-center"><?php echo $res['formule']; ?></td>
					<td class="text-center"><?php echo $res['date_a']; ?></td>
					<td class="text-center"><?php echo $res['date_d']; ?></td>
					<td class="text-center"><?php echo $res['nb_total']; ?></td>
					<td class="text-right"><?php echo number_format((float)$res['prix'], 2, '.', ''); ?>&euro;</td>
					<td class="<?php echo $res['etat']; ?>">&nbsp;</td>
					<td class="text-center"><a class="btn btn-default" href="?page=prive&amp;show=gerer_res&amp;modifier=<?php echo $res['id']; ?>" title="Modifier cette réservation">Voir</a></td>
				</tr>
				<?php
			}
		else :
			if ($liste) {
				foreach ($clients as $c => $k) {
					?>
					<tr>
						<td class="center"><?php echo $k['id']; ?></td>
						<td><?php echo $k['nom']; ?></td>
						<td><?php echo $k['prenom']; ?></td>
						<td><?php echo $k['tel']; ?></td>
						<td><?php echo $k['email']; ?></td>
						<td class="center outils">
                            <button type="button" class="btn btn-default" data-placement="left" data-toggle="popover" data-html="true" title="<strong><?php echo $k['nom'].' '.$k['prenom']; ?> " data-content="<? echo $k['adresse'].'<br />'.$k['code_postal'].' '.$k['ville']; ?>"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></button>
							<a class="btn btn-default" href="?page=prive&amp;show=gerer_cli&modifier=<?php echo $c; ?>" title="Modifier le client"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
							<a style="display:none;" href="?page=prive&amp;show=gerer_cli&email=<?php echo $c; ?>" title="Envoyer un email au client">
								<img src="images/email_compose30.png" alt="Envoyer un email" />
							</a>
							<a class="btn btn-danger" href="?page=prive&amp;show=gerer_cli&supprimer=<?php echo $c; ?>" title="Supprimer le client">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</a>
						</td>
						<td class="center">
							<a class="btn btn-default" href="?page=prive&amp;show=gerer_cli&reservations=<?php echo $c; ?>" title="Voir les réservations associées à ce client">
								<span class="glyphicon glyphicon-book" aria-hidden="true"></span>
							</a>
						</td>

					</tr>
				<?php } }
		endif;
		?>
		</tbody>
	</table>

	<?php
endif;
if ($liste) {
	echo '<p class="hautpage"><a href="#contenu">Haut de page</a></p>';
}
if ((isset($reservations) OR isset($formulaire) OR $modifier OR $suppr) AND $_SESSION['permission'] == 'admin') {
	echo '<p><a href="?page=prive&amp;show=gerer_cli" class="btn btn-default"><i class="fa fa-arrow-left"></i> Retour à la liste des clients</a></p>';
}
?>

