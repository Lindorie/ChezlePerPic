<h1><span>Formulaire de demande de réservation</span></h1>

<?php $afficher = 4; require "calendrier.php"; ?>

<?php 
	if (isset($_POST['reservation'])) {
		extract($_POST);
		
		// Gestion des erreurs
		$erreur = "";
		// Mot de passe / Compte client
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
				$req = 'SELECT id FROM client WHERE email = "'.$email.'"';
				$res = mysqli_query($link,$req) OR die('<div class="info bad">1: '.mysqli_error($link).'</div>');
				$nb = mysqli_num_rows($res);
				if ($nb != 0) { 
					echo '<div class="info bad">Nous avons trouvé un compte client relié à cette adresse email. Veuillez saisir le mot de passe correspondant à votre compte client ou modifier l\'adresse email.</div>'; 
					$succes = false;
				} else { 
					// Enregistrement du client dans la table client
					$req = 'INSERT INTO client VALUES ("", "'.$nom.'", "'.$prenom.'", "'.$email.'", "'.$tel.'", "'.$tel2.'", "'.$adresse.'", "'.$cp.'", "'.$ville.'", "'.$pays.'", "'.$password1.'", "'.$pref_mail2.'", "'.$pref_tel2.'", "'.$newsletter.'")';
					$res = mysqli_query($link,$req) OR die('<div class="info bad">2: '.mysqli_error($link).'</div>');
					$id_client = mysqli_insert_id($link);
					$succes = true;
				}
			} else {
				// Recherche de l'id du client déjà enregistré
				$pass = md5(sha1($password));
				$req = 'SELECT id, password FROM client WHERE email = "'.$email_client.'"';
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
			}
			if ($succes) {
				// Enregistrement de la réservation dans la table réservation
				$req2 = 'INSERT INTO reservation VALUES ("", '.$id_client.', "'.$formule.'", "'.$arrivee.'", "'.$depart.'", "'.$nombre.'", "'.$enfants.'", "'.$bebe.'", "'.$message.'", "", "", "rose", NOW())';
				$res2 = mysqli_query($link,$req2) OR die('<div class="info bad">4: '.mysqli_error($link).'</div>');
				
				// Envoi du mail récapitulatif au client 
					/* Récupérer les informations client */
					$req = 'SELECT * FROM client WHERE id = '.$id_client.'';
					$res = mysqli_query($link,$req) OR die('<div class="info bad">5: '.mysqli_error($link).'</div>');
					$client = mysqli_fetch_array($res);
					$identifiant = substr($client['nom'],0,1).substr($client['prenom'],0,1).$client['id'];
					
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
					  if ($_SERVER['SERVER_NAME'] != 'localhost') { mail($emailaddress, $emailsubject, $msg, $headers); }
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
					  if ($_SERVER['SERVER_NAME'] != 'localhost') { mail($emailaddress, $emailsubject, $msg, $headers); }
					ini_restore(sendmail_from); 
				
				echo '<div class="info good">Votre réservation a été enregistrée, nous vous contacterons sous peu pour la validation et le paiement.</div>';
				$succes = true;
			}
		}
		
		
	}	
?>
<?php if (!$succes) {  ?>
<p style="margin-bottom: 20px;font-style: italic">Tous les champs marqués * sont obligatoires.</p>

<form method="post" action="#" id="reservation">
	<fieldset>
		<legend>Si vous êtes déjà client</legend>
		<p class="info">Saisissez vos identifiants :</p>
		<div class="line-2">
			<div class="wrap-elem">
				<label for="email_client">Email *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-envelope"></i></span>
					<input class="span3" type="text" placeholder="Email *" name="email_client" id="email_client" value="<?=$email_client;?>">
				</div>
			</div>
			<div class="wrap-elem">
				<label for="password">Mot de passe *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-key"></i></span>
					<input class="span3" type="password" name="password" id="password" placeholder="Mot de passe *">
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Si vous n'êtes pas client</legend>
		<p class="info">Créez votre compte client (indiquez un mot de passe 2 fois).<br /> Le compte client est utilisé pour le suivi de votre dossier et le paiement.</p>
		
		<div class="line-2">
			<div class="wrap-elem">
				<label for="password1">Mot de passe *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-key"></i></span>
					<input class="span3" type="password" name="password1" id="password1" placeholder="Mot de passe *">
				</div>
			</div>
			<div class="wrap-elem">
				<label for="password2">Confirmez *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-key"></i></span>
					<input class="span3" type="password" name="password2" id="password2" placeholder="Confirmez le Mot de passe *">
				</div>
			</div>
		</div>
		<p class="subtitle">Préférences de contact</p>
		<p class="cent">Je préfère être contacté par <input type="checkbox" name="pref_mail" id="pref_mail" <?php if (isset($pref_mail)) echo 'checked="checked"'; ?> /><label for="pref_mail">Email</label>&nbsp;<input type="checkbox" name="pref_tel" id="pref_tel" <?php if (isset($pref_tel)) echo 'checked="checked"'; ?> /><label for="pref_tel">Téléphone</label></p>
		<p class="cent"><label for="news">Je souhaite recevoir les newsletters (promotions, actualités)</label><input type="checkbox" name="news" id="news" <?php if (isset($news)) echo 'checked="checked"'; ?> /></p>
		
		<p class="subtitle">Vos coordonnées</p>
		<div class="line-2">
			<div class="wrap-elem">
				<label for="nom">Nom *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-user"></i></span>
					<input class="span3" type="text" placeholder="Nom" name="nom" id="nom" value="<?=$nom;?>" />
				</div>
			</div>
			<div class="wrap-elem">
				<label for="prenom">Prénom *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-user"></i></span>
					<input class="span3" type="text" placeholder="Prénom" name="prenom" id="prenom" value="<?=$prenom;?>" />
				</div>
			</div>
		</div>
		<div class="line-1">
			<div class="wrap-elem">
				<label for="email">Email *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-envelope"></i></span>
					<input class="span3" type="text" placeholder="Email *" name="email" id="email" value="<?=$email;?>">
				</div>
			</div>
		</div>
		<div class="line-2">
			<div class="wrap-elem">
				<label for="tel">Téléphone *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-phone"></i></span>
					<input class="span3" type="text" placeholder="Téléphone" name="tel" id="tel" value="<?=$tel;?>" />
				</div>
			</div>
			<div class="wrap-elem">
				<label for="tel2">Téléphone 2</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-phone"></i></span>
					<input class="span3" type="text" placeholder="Téléphone 2" name="tel2" id="tel2" value="<?=$tel2;?>" />
				</div>
			</div>
		</div>
		<div class="line-1">
			<div class="wrap-elem">
			<div class="clear"></div>
				<label for="adresse">Adresse *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-home"></i></span>
					<input class="span5 width100" type="text" placeholder="Adresse" name="adresse" id="adresse" value="<?=$adresse;?>" />
				</div>
			</div>
		</div>
		<div class="line-3">
			<div class="wrap-elem">
				<label for="cp">Code postal *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-globe"></i></span>
					<input class="span2" type="text" placeholder="Code postal" name="cp" id="cp" value="<?=$cp;?>" />
				</div>
			</div>
			<div class="wrap-elem">
				<label for="ville">Ville *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-globe"></i></span>
					<input class="span2" type="text" placeholder="Ville" name="ville" id="ville" value="<?=$ville;?>" />
				</div>
			</div>
			<div class="wrap-elem">
				<label for="pays">Pays *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-globe"></i></span>
					<input class="span2" type="text" placeholder="Pays" name="pays" id="pays" value="<?php if (isset($pays)) echo $pays; else echo 'France'; ?>" />
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Votre réservation</legend>
		<p class="info">Retrouvez toutes les formules disponibles sur la page <a href="?page=formules">Formules et Tarifs</a></p>
		<div class="line-1">
			<div class="wrap-elem">
				<label for="formule">Formule choisie *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-book"></i></span>
				<?php if (isset($formule)) { $checked = 'selected="selected"'; } ?>
					<select name="formule" id="formule" class="span3">
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
		<div class="line-2" id="selection">
			<?php $curDate = date('Y-m-d'); ?>
			<?php $curDate2 = date('Y-m-d', strtotime('+1 week')) ?>
			<div class="wrap-elem">
				<label for="arrivee">Arrivée le *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-calendar"></i></span>
					<input class="span3" type="date" name="arrivee" id="arrivee" value="<?php if (isset($arrivee)) echo $arrivee; else echo $curDate;?>" readonly="readonly" />
				</div>
			</div>
			<div class="wrap-elem">
				<label for="depart">Départ le *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-calendar"></i></span>
					<input class="span3" type="date" name="depart" id="depart" data-value="7" value="<?php if (isset($depart)) echo $depart; else echo $curDate2;?>" readonly="readonly" />
				</div>
			</div>
		</div>
		<div class="line-3">
			<div class="wrap-elem">
				<label for="nombre">Nombre de personnes (max. : 8) *</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-group"></i></span>
					<input type="text" class="span2" name="nombre" id="nombre" value="<?=$nombre;?>" />
				</div>
			</div>
			<div class="wrap-elem">
				<label for="enfants">Dont enfant(s) de moins de 13 ans</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-group"></i></span>
					<input class="span2" type="text" name="enfants" id="enfants" value="<?=$enfants;?>" />
				</div>
			</div>
			<div class="wrap-elem">
				<label for="bebe">Dont bébé(s)</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-group"></i></span>
					<input class="span2" type="text" name="bebe" id="bebe" value="<?=$bebe;?>" />
				</div>
			</div>
		</div>
		<div class="line-1">
			<div class="wrap-elem">
				<label for="message">Message complémentaire</label>
				<div class="input-prepend">
					<span class="add-on"><i class="fa fa-comment"></i></span>
					<textarea class="span4" rows="5" cols="40" name="message" id="message"><?=$message;?></textarea>
				</div>
			</div>
		</div>
		<p class="cent"><input type="checkbox" name="cgv" id="cgv" value="cgv" <?php if (isset($cgv)) echo 'checked="checked"'; ?> /><label for="cgv">J'ai lu et j'accepte les <a target="_blank" href="documents/convention_conditions.pdf">Conditions Générales de Location</a> *</label></p>
	</fieldset>
	<p class="alignCenter"><button type="submit" class="btn btn-success" name="reservation"><i class="fa fa-ok icon-large"></i> Réserver</button></p>
</form>
<?php } ?>