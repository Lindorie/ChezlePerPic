<h1><span>Formulaire de demande de réservation</span></h1>

<?php $afficher = 4; require "calendrier.php"; ?>

<?php 
	if (isset($_POST['reservation'])) {
		extract($_POST);
		
		// Gestion des erreurs
		$erreur = "";
		// Mot de passe / Compte client
		if ($email_client == "admin") {
			$id_client = 14;
			$dejaclient = true;
		} else {
			if ($password == "") {
				if ($password1 == "") { $erreur .= 'Vous devez remplir le champ <strong>Mot de passe</strong><br />'; }
				if ($password2 == "") { $erreur .= 'Vous devez remplir le champ <strong>Confirmer le Mot de passe</strong><br />'; }
				if ($password1 != $password2) { $erreur .= 'Les mots de passe ne sont pas identiques<br />'; }
			} else $dejaclient = true;
			if (!$dejaclient) {
				// Coordonnées
				if ($nom == "") { $erreur .= 'Vous devez remplir le champ <strong>Nom</strong><br />'; }
				if ($prenom == "") { $erreur .= 'Vous devez remplir le champ <strong>Prénom</strong><br />'; }
				if ($email == "") { $erreur .= 'Vous devez remplir le champ <strong>Email</strong><br />'; }
				if ($tel == "") { $erreur .= 'Vous devez remplir le champ <strong>Téléphone</strong><br />'; }
				if ($adresse == "") { $erreur .= 'Vous devez remplir le champ <strong>Adresse</strong><br />'; }
				if ($cp == "") { $erreur .= 'Vous devez remplir le champ <strong>Code postal</strong><br />'; }
				if ($ville == "") { $erreur .= 'Vous devez remplir le champ <strong>Ville</strong><br />'; }
				if ($pays == "") { $erreur .= 'Vous devez remplir le champ <strong>Pays</strong><br />'; }

				if ($pref_mail == 'on') { $pref_mail2 = 'oui'; } else { $pref_mail2 = 'non'; }
				if ($pref_tel == 'on') { $pref_tel2 = 'oui'; } else { $pref_tel2 = 'non'; }
				if ($news == 'on') { $newsletter = 'oui'; } else { $newsletter = 'non'; }
			}
		}

		// Réservation 
		if ($formule == "") { $erreur .= 'Vous devez choisir une formule<br />'; }
		if ($arrivee == "") { $erreur .= 'Vous devez choisir une date d\'arrivée<br />'; }
		if ($depart == "") { $erreur .= 'Vous devez choisir une date de départ<br />'; }
		if ($nombre == "") { $erreur .= 'Vous devez remplir le champ <strong>Nombre de personnes</strong><br />'; }
		// CGV
		if ($cgv != "cgv") { $erreur .= 'Vous devez lire les CGL et cocher la case correspondante<br />'; }
		// Affichage de l'erreur
		if ($erreur != "") { echo '<div class="info bad light">'.$erreur.'</div>'; }
		else {
			$nom = htmlspecialchars($nom);
			$prenom = htmlspecialchars($prenom);
			$adresse = htmlspecialchars($adresse);
			$ville = htmlspecialchars($ville);
			$pays = htmlspecialchars($pays);
			$formule = htmlspecialchars($formule);
			$password1 = md5(sha1($password1));

			if (!$dejaclient) {
				// On cherche d'abord si cette adresse email est déjà reliée à un compte client
				$req = 'SELECT id FROM '.$prefix.'client WHERE email = "'.$email.'"';
				$res = mysqli_query($link,$req) OR die('<div class="info bad">1: '.mysqli_error($link).'</div>');
				$nb = mysqli_num_rows($res);
				if ($nb != 0) {
					echo '<div class="info bad">Nous avons trouvé un compte client relié à cette adresse email. Veuillez saisir le mot de passe correspondant à votre compte client ou modifier l\'adresse email.</div>';
					$succes = false;
				} else {
					// Enregistrement du client dans la table client
					$req = 'INSERT INTO '.$prefix.'client VALUES ("", "'.$nom.'", "'.$prenom.'", "'.$email.'", "'.$tel.'", "'.$tel2.'", "'.$adresse.'", "'.$cp.'", "'.$ville.'", "'.$pays.'", "'.$password1.'", "'.$pref_mail2.'", "'.$pref_tel2.'", "'.$newsletter.'")';
					$res = mysqli_query($link,$req) OR die('<div class="info bad">2: '.mysqli_error($link).'</div>');
					$id_client = mysqli_insert_id($link);
					$succes = true;
				}
			} else if (!isset($id_client)) {
				// Recherche de l'id du client déjà enregistré
				$pass = md5(sha1($password));
				$req = 'SELECT id, password FROM '.$prefix.'client WHERE email = "'.$email_client.'"';
				$res = mysqli_query($link,$req) OR die('<div class="info bad">3: '.mysqli_error($link).'</div>');
				$nb = mysqli_num_rows($res);
				if ($nb == 0) { echo '<div class="info bad">Nous n\'avons pas trouvé de compte client relié à cette adresse email. Veuillez vérifier le champ <strong>Email</strong>.</div>'; $succes = false;}
				else {
					$client = mysqli_fetch_array($res);
					if ($client['password'] == $pass) {
						$id_client = $client['id'];
						$succes = true;
					} else {
						echo '<div class="info bad">Le mot de passe saisi ne correspond pas à l\'adresse email. Veuillez vérifier le champ <strong>Mot de passe</strong> de votre compte client.</div>';
						$succes = false;
					}
				}
			} else if ($id_client == 14) {
				$succes = true;
			}
			if ($succes) {
				// Enregistrement de la réservation dans la table réservation
				$req2 = 'INSERT INTO '.$prefix.'reservation VALUES (NULL, '.$id_client.', "'.$formule.'", "'.$arrivee.'", "'.$depart.'", "'.$nombre.'", "'.$enfants.'", "'.$bebe.'", "'.$message.'", "", "", "rose", NOW())';
				$res2 = mysqli_query($link,$req2) OR die('<div class="info bad">4: '.mysqli_error($link).'</div>');
				
				// Envoi du mail récapitulatif au client 
					/* Récupérer les informations client */
					$req = 'SELECT * FROM '.$prefix.'client WHERE id = '.$id_client.'';
					$res = mysqli_query($link,$req) OR die('<div class="info bad">5: '.mysqli_error($link).'</div>');
					$client = mysqli_fetch_array($res);
					$identifiant_client = substr($client['nom'],0,1).substr($client['prenom'],0,1).$client['id'];
					
					/* Envoi du mail */
					if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
					  $eol="\r\n";
					} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
					  $eol="\r";
					} else {
					  $eol="\n";
					 }
					  
					# File for Attachment
					$f_name="documents/convention_conditions.pdf";    // use relative path OR ELSE big headaches. $letter is my file for attaching.
					$handle=fopen($f_name, 'rb');
					$f_contents=fread($handle, filesize($f_name));
					$f_contents=chunk_split(base64_encode($f_contents));    //Encode The Data For Transition using base64_encode();
					$f_type=filetype($f_name);
					fclose($handle);
					# To Email Address
					$emailaddress= $client['email'].', mireille.pic@gmail.com';
					# Message Subject
					$emailsubject="[Chez le Per'Pic] Récapitulatif de votre demande de réservation";
					# Message Body
					/*ob_start();
					  require("email_recap_reservation.php");     // i made a simple & pretty page for showing in the email
					$body=ob_get_contents(); ob_end_clean();*/
					
					include "email_recap_reservation.php";
					$body = $content_email;
					
					# Common Headers
					$headers .= 'From: Chez le Per Pic <contact@chezleperpic.fr>'.$eol;
					$headers .= 'Reply-To: Chez le Per Pic <contact@chezleperpic.fr>'.$eol;
					$headers .= 'Return-Path: Chez le Per Pic <contact@chezleperpic.fr>'.$eol;     // these two to set reply address
					$headers .= "Message-ID:<TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
					$headers .= "X-Mailer: PHP v".phpversion().$eol;           // These two to help avoid spam-filters
					# Boundry for marking the split & Multitype Headers
					$mime_boundary=md5(time());
					$headers .= 'MIME-Version: 1.0'.$eol;
					$headers .= "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"".$eol;
					$msg = "";

					# HTML Version
					$msg .= "--".$mime_boundary.$eol;
					$msg .= "Content-Type: text/html; charset=utf-8".$eol;
					$msg .= "Content-Transfer-Encoding: 8bit".$eol;
					$msg .= $body.$eol.$eol;

					# Attachment
					/*$msg .= "--".$mime_boundary.$eol;
					$msg .= "Content-Type: application/pdf; name=\"convention_conditions.pdf\"".$eol;   // sometimes i have to send MS Word, use 'msword' instead of 'pdf'
					$msg .= "Content-Transfer-Encoding: base64".$eol;
					$msg .= "Content-Disposition: attachment; filename=\"convention_conditions.pdf\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
					$msg .= $f_contents.$eol.$eol;*/

					# Finished
					$msg .= "--".$mime_boundary."--".$eol.$eol;   // finish with two eol's for better security. see Injection.

					# SEND THE EMAIL
					ini_set(sendmail_from,'contact@chezleperpic.fr');  // the INI lines are to force the From Address to be used !
					  if ($_SERVER['SERVER_NAME'] != 'localhost' OR !$dev) { mail($emailaddress, $emailsubject, $msg, $headers); }
					ini_restore(sendmail_from); 
					
					
				
				// Envoi du mail d'alerte au propriétaire 
				
				
					# To Email Address
					$emailaddress= 'mireille.pic@gmail.com';
					# Message Subject
					$emailsubject="[Chez le Per'Pic] Nouvelle demande de réservation";
					# Message Body
					$body = $content_email2;
					
					# Common Headers
					$headers = "";
					$headers .= 'From: Chez le Per Pic <contact@chezleperpic.fr>'.$eol;
					$headers .= 'Reply-To: Chez le Per Pic <contact@chezleperpic.fr>'.$eol;
					$headers .= 'Return-Path: Chez le Per Pic <contact@chezleperpic.fr>'.$eol;     // these two to set reply address
					$headers .= "Message-ID:<TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
					$headers .= "X-Mailer: PHP v".phpversion().$eol;           // These two to help avoid spam-filters
					$msg = "";

					# HTML Version
					$headers .= "Content-Type: text/html; charset=utf-8".$eol;
					$headers .= "Content-Transfer-Encoding: 8bit".$eol;
					$msg .= $body.$eol.$eol;

					# SEND THE EMAIL
					ini_set(sendmail_from,'contact@chezleperpic.fr');  // the INI lines are to force the From Address to be used !
					  if ($_SERVER['SERVER_NAME'] != 'localhost' OR !$dev) { mail($emailaddress, $emailsubject, $msg, $headers); }
					ini_restore(sendmail_from); 
				
				echo '<div class="info good">Votre réservation a été enregistrée, nous vous contacterons sous peu pour la validation et le paiement.</div>';
				$succes = true;
			}
		}
		
		
	}	
?>
<?php if (!$succes) {  ?>
<p style="margin-bottom: 20px;font-style: italic">Tous les champs marqués * sont obligatoires.</p>

<form method="post" action="#" id="reservation" class="form-horizontal">
	<?php
	if ($_SESSION['permission'] == 'admin') {
		?>
		<p>Vous êtes connecté en tant qu'administrateur. Le client sera PIC'S FAMILY (mireille.pic@gmail.com)</p>
		<input type="hidden" value="admin" name="email_client" />
		<?php
	} else {
	?>
	<fieldset>
		<legend>Si vous êtes déjà client</legend>
			<p class="info">Saisissez vos identifiants :</p>
			<div class="line-2">
				<div class="wrap-elem form-group">
					<label class="control-label col-sm-2" for="email_client">Email *</label>
					<div class="col-sm-8">
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
							<input class="span3 form-control" type="text" placeholder="Email *" name="email_client" id="email_client" value="<?=$email_client;?>">
						</div>
					</div>
				</div>
				<div class="wrap-elem form-group">
					<label class="control-label col-sm-2" for="password">Mot de passe *</label>
					<div class="col-sm-8">
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-key"></i></div>
							<input class="span3 form-control" type="password" name="password" id="password" placeholder="Mot de passe *">
						</div>
					</div>
				</div>
			</div>
	</fieldset>
	<fieldset>
		<legend>Si vous n'êtes pas client</legend>
		<p class="info">Créez votre compte client (indiquez un mot de passe 2 fois).<br /> Le compte client est utilisé pour le suivi de votre dossier et le paiement.</p>
		
		<div class="line-2">
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="password1">Mot de passe *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<div class="input-group-addon"><i class="fa fa-key"></i></div>
						<input class="span3 form-control" type="password" name="password1" id="password1" placeholder="Mot de passe *">
					</div>
				</div>
			</div>
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="password2">Confirmez *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key"></i></span>
						<input class="span3 form-control" type="password" name="password2" id="password2" placeholder="Confirmez le Mot de passe *">
					</div>
				</div>
			</div>
		</div>
		<p class="subtitle">Préférences de contact</p>

		<div>
			<span style="font-weight: bold; padding-top: 7px; display: inline-block; vertical-align: middle; margin-right: 10px;">Je préfère être contacté par</span>
			<label for="pref_mail" class="checkbox-inline">
				<input type="checkbox" name="pref_mail" id="pref_mail" <?php if (isset($pref_mail)) echo 'checked="checked"'; ?>>
				Email
			</label>
			<label for="pref_tel" class="checkbox-inline">
				<input type="checkbox" name="pref_tel" id="pref_tel" <?php if (isset($pref_tel)) echo 'checked="checked"'; ?> />
				Téléphone
			</label>
		</div>

		<div class="checkbox">
			<label for="news">
				<input type="checkbox" name="news" id="news" <?php if (isset($news)) echo 'checked="checked"'; ?> />
				Je souhaite recevoir les newsletters (promotions, actualités)
			</label>
		</div>

		
		<p class="subtitle">Vos coordonnées</p>
		<div class="line-2">
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="nom">Nom *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input class="span3 form-control" type="text" placeholder="Nom" name="nom" id="nom" value="<?=$nom;?>" />
					</div>
				</div>
			</div>
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="prenom">Prénom *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input class="span3 form-control" type="text" placeholder="Prénom" name="prenom" id="prenom" value="<?=$prenom;?>" />
					</div>
				</div>
			</div>
		</div>
		<div class="line-1">
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="email">Email *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
						<input class="span3 form-control" type="text" placeholder="Email *" name="email" id="email" value="<?=$email;?>">
					</div>
				</div>
			</div>
		</div>
		<div class="line-2">
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="tel">Téléphone *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-phone"></i></span>
						<input class="span3 form-control" type="text" placeholder="Téléphone" name="tel" id="tel" value="<?=$tel;?>" />
					</div>
				</div>
			</div>
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="tel2">Téléphone 2</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-phone"></i></span>
						<input class="span3 form-control" type="text" placeholder="Téléphone 2" name="tel2" id="tel2" value="<?=$tel2;?>" />
					</div>
				</div>
			</div>
		</div>
		<div class="line-1">
			<div class="wrap-elem form-group">
			<div class="clear"></div>
				<label class="control-label col-sm-2" for="adresse">Adresse *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-home"></i></span>
						<input class="span5 width100 form-control" type="text" placeholder="Adresse" name="adresse" id="adresse" value="<?=$adresse;?>" />
					</div>
				</div>
			</div>
		</div>
		<div class="line-3">
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="cp">Code postal *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-globe"></i></span>
						<input class="span2 form-control" type="text" placeholder="Code postal" name="cp" id="cp" value="<?=$cp;?>" />
					</div>
				</div>
			</div>
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="ville">Ville *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-globe"></i></span>
						<input class="span2 form-control" type="text" placeholder="Ville" name="ville" id="ville" value="<?=$ville;?>" />
					</div>
				</div>
			</div>
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="pays">Pays *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-globe"></i></span>
						<input class="span2 form-control" type="text" placeholder="Pays" name="pays" id="pays" value="<?php if (isset($pays)) echo $pays; else echo 'France'; ?>" />
					</div>
				</div>
			</div>
		</div>
	</fieldset>
		<?php
	}
	?>
	<fieldset>
		<legend>Votre réservation</legend>
		<p class="info">Retrouvez toutes les formules disponibles sur la page <a href="?page=formules">Formules et Tarifs</a></p>
		<div class="line-1">
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="formule">Formule choisie *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-book"></i></span>
					<?php if (isset($formule)) { $checked = 'selected="selected"'; } ?>
						<select name="formule" id="formule" class="span3 form-control">
							<optgroup label="-- Semaine">
								<option value="semaine" <? if($formule == "semaine") echo $checked; ?>>Semaine</option>
							</optgroup>
							<optgroup label="-- Week-end">
								<option value="we_prolonge" <? if($formule == "we_prolonge") echo $checked; ?>>prolongé</option>
								<option value="we_long" <? if($formule == "we_long") echo $checked; ?>>long</option>
								<option value="we_moyen" <? if($formule == "we_moyen") echo $checked; ?>>moyen</option>
								<option value="we_court" <? if($formule == "we_court") echo $checked; ?>>court</option>
							</optgroup>
							<option value="alacarte" <? if($formule == "alacarte") echo $checked; ?>>A la carte</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="line-2" id="selection">
			<?php $curDate = date('Y-m-d'); ?>
			<?php $curDate2 = date('Y-m-d', strtotime('+1 week')) ?>
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="arrivee">Arrivée le *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input class="span3 datepicker form-control" type="date" name="arrivee" id="arrivee" value="<?php if (isset($arrivee)) echo $arrivee; else echo $curDate;?>" readonly="readonly" />
					</div>
				</div>
			</div>
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="depart">Départ le *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input class="span3 datepicker form-control" type="date" name="depart" id="depart" data-value="7" value="<?php if (isset($depart)) echo $depart; else echo $curDate2;?>" readonly="readonly" />
					</div>
				</div>
			</div>
		</div>
		<div class="line-3">
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="nombre">Nombre de personnes (max. : 8) *</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-group"></i></span>
						<input type="text" class="span2 form-control" name="nombre" id="nombre" placeholder="Nombre total" value="<?=$nombre;?>" />
					</div>
				</div>
			</div>
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="enfants">Dont enfants</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-child"></i></span>
						<input class="span2 form-control" type="text" name="enfants" id="enfants" placeholder="Enfants (-13 ans)" value="<?=$enfants;?>" />
						<input class="span2 form-control" type="text" name="bebe" id="bebe" placeholder="Bébé(s)" value="<?=$bebe;?>" />
					</div>
				</div>
			</div>
		</div>
		<div class="line-1">
			<div class="wrap-elem form-group">
				<label class="control-label col-sm-2" for="message">Message complémentaire</label>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-comment"></i></span>
						<textarea class="span6 form-control" rows="5" cols="70" name="message" id="message"><?=$message;?></textarea>
					</div>
				</div>
			</div>
		</div>

		<div class="checkbox col-sm-offset-2 col-sm-8">
			<label for="cgv">
				<input type="checkbox" name="cgv" id="cgv" value="cgv" <?php if (isset($cgv) OR $_SESSION['permission'] == 'admin') echo 'checked="checked"'; ?> />
				J'ai lu et j'accepte les <a target="_blank" href="documents/convention_conditions.pdf">Conditions Générales de Location</a> *
			</label>
		</div>

	</fieldset>
	<p class="alignCenter"><button type="submit" class="btn btn-success" name="reservation"><i class="fa fa-ok icon-large"></i> Réserver</button></p>
</form>
<?php } ?>

<script>
	$( document ).ready(function() {

		$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
		$( ".datepicker" ).datepicker({
			minDate: 0
		});

	});
</script>
