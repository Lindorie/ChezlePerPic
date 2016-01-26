<?php 
$content_email = '
<h1>Récapitulatif de votre demande de réservation</h1>
<br />
Vous venez de demander une réservation pour la location de l\'appartement <strong>«&nbsp;Chez le Pèr\'Pic&nbsp;»</strong>.<br /> Veuillez trouver ci-dessous le récapitulatif de votre demande :<br /><br />

<strong>Identifiant client</strong>: '. $identifiant .'<br />
<strong>Nom, prénom</strong>: '. $client["nom"].' '.$client['prenom'] .'<br />
<strong>Formule choisie</strong>: '.$formule.' <br />
<strong>Date d\'arrivée</strong>: '. date("d/m/Y", strtotime($arrivee)) .'<br />
<strong>Date de départ</strong>: '. date("d/m/Y", strtotime($depart)) .'<br />
<strong>Nombre total de personnes</strong>: '. $nombre .'<br />
<strong>dont enfants</strong>: '. $enfants .'<br />
<strong>dont bébé(s)</strong>: '. $bebe .'<br /><br />

<strong>Message envoyé au propriétaire</strong>: '. $message .'<br /><br />

<p>Si vous souhaitez modifier ou annuler cette demande de réservation avant la validation définitive, veuillez vous connecter sur l\'espace client du site <a href="http://www.chezleperpic.fr">www.chezleperpic.fr</a>. Votre identifiant est votre adresse email.</p>
<p>Votre demande sera vérifiée et acceptée par le propriétaire dans un délai maximum de 48h. Vous recevrez alors un nouveau mail avec le tarif, la convention et les conditions générales de la location.</p>
<p>N\'hésitez pas à nous contacter par email (contact@chezleperpic.fr) ou par téléphone (04.76.91.17.93 le soir ou 06 41 84 22 90).</p>
'; 
$content_email2 = '
<h1>Récapitulatif de la demande de réservation</h1>
<br />
Une nouvelle demande de réservation a été enregistrée sur le site.<br /> Veuillez trouver ci-dessous le récapitulatif de la demande :<br /><br />

<strong>Identifiant client</strong>: '. $identifiant .'<br />
<strong>Nom, prénom</strong>: '. $client["nom"].' '.$client['prenom'] .'<br />
<strong>Formule choisie</strong>: '.$formule.' <br />
<strong>Date d\'arrivée</strong>: '. date("d/m/Y", strtotime($arrivee)) .'<br />
<strong>Date de départ</strong>: '. date("d/m/Y", strtotime($depart)) .'<br />
<strong>Nombre total de personnes</strong>: '. $nombre .'<br />
<strong>dont enfants</strong>: '. $enfants .'<br />
<strong>dont bébé(s)</strong>: '. $bebe .'<br /><br />

<strong>Message </strong>: '. $message .'<br /><br />

<p>Veuillez vous connecter sur l\'espace client du site <a href="http://www.chezleperpic.fr">www.chezleperpic.fr</a> afin d\'accepter ou non cette réservation.</p>
'; 

?>