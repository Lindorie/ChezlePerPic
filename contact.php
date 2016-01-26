<h1>Contactez-nous</h1>

<h2><span>Nos coordonnées</span></h2>

<address><i class="fa fa-home fa-4x pull-left muted"></i>
Mr et Mme PIC Gérard<br />
140 lot. la Crozelière<br />
38140 Renage, FRANCE</address>

<p><strong>Téléphone</strong> : +33 .4 76 91 17 93 ou +33 .6 41 84 22 90<br />
<strong>Email</strong> : contact@chezleperpic.fr</p>

<h2><span>Formulaire de contact direct</span></h2>

<?php
	if (isset($_POST['submit'])) {
		extract($_POST);
		$erreur = '';
		if ($nom == '') { $erreur .= 'Vous devez renseigner le champ <strong>Nom, Prénom</strong><br />'; }
		if ($email == '') { $erreur .= 'Vous devez renseigner le champ <strong>Email</strong><br />'; }
		if ($objet == '') { $erreur .= 'Vous devez renseigner le champ <strong>Objet</strong><br />'; }
		if ($message == '') { $erreur .= 'Vous devez renseigner le champ <strong>Message</strong><br />'; }
		if ($captcha == '') { $erreur .= 'Vous devez renseigner le champ <strong>anti-spam</strong>'; }
		if ($captcha != '3') { $erreur .= 'Vous devez saisir la bonne réponse dans le champ <strong>anti-spam</strong> (1+2=?)'; }
	
		if ($erreur != '') {
			echo '<div class="info bad">'.$erreur.'</div>';
		} else {
			$nom = htmlspecialchars($nom);
			$objet = htmlspecialchars($objet);
			$message = htmlspecialchars($message);
			
			$req = 'INSERT INTO contact VALUES ("", "'.$nom.'", "'.$email.'", "'.$objet.'", "'.$message.'", NOW())';
			if (mysql_query($req)) {
				if ($_SERVER['HTTP_HOST'] != "localhost") {
						$sujet = "Chezleperpic.fr : nouveau message";
						$destinataires = 'mireille.pic@gmail.com, pic.carine@gmail.com';
						$headers = "From: ".$email."\r\n";
						$headers .="Content-Type: text/html; charset=iso-8859-1\n"; 
						$headers .='Content-Transfer-Encoding: 8bit'; 
						$message2 = utf8_decode('Message reçu depuis le site internet www.chezleperpic.fr<br /><br />');
						$message2 .= utf8_decode('<strong>Nom, prénom</strong> : '.$nom.'<br />');
						$message2 .= utf8_decode('<strong>Objet</strong> : '.stripslashes($objet).'<br /><br />');
						$message2 .= utf8_decode(stripslashes($message).'<br /><br />');
						if(!mail($destinataires, mb_encode_mimeheader($sujet), $message2, $headers)) {
						
						} else {  }
				}
				echo '<div class="info good">Votre message a bien été enregistré, nous vous recontacterons sous peu.</div>';
			} else {
				echo mysql_error();
			}
		}
	}
?>
<form method="post" action="" id="form_contact">
	<p>
		<label for="nom">Nom, prénom *</label>
		<input type="text" id="nom" name="nom" value="<?=htmlspecialchars_decode(stripslashes($nom));?>" />
	</p>
	<p>
		<label for="email">Votre e-mail *</label>
		<input type="text" id="email" name="email" value="<?=$email;?>" />
	</p>
	<p>
		<label for="objet">Objet *</label>
		<input type="text" id="objet" name="objet" value="<?=htmlspecialchars_decode(stripslashes($objet));?>" />
	</p>
	<p>
		<label for="message">Message *</label>
		<textarea cols="50" rows="6" name="message" id="message"><?=htmlspecialchars_decode(stripslashes($message));?></textarea>
	</p>	
	<div id="recaptcha">
			<p>Pour éviter les spams, je vous invite à répondre à la question suivante * :</p>
			<p><label for="captcha" style="width:auto;">Combien font un plus deux ?</label><input type="text" name="captcha" id="captcha" size="2" maxlength="2" style="width:auto;margin-left:5px;"  value="<?=$captcha;?>" /></p>
	</div>
	<p>
		<input type="submit" name="submit" value="Envoyer le message" class="submit" />
	</p>
</form>